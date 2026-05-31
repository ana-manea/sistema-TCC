<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Banca;
use App\Models\BancaMembro;
use App\Models\AvaliacaoBanca;
use App\Models\Tcc;
use App\Models\User;
use Illuminate\Http\Request;

class BancaController extends Controller
{
    /**
     * Lista todas as bancas, com filtro opcional por status.
     * Função: "Situação Banca" — exibe agendadas, realizadas e canceladas.
     */
    public function index(Request $request)
    {
        $statusFiltro = $request->input('status');

        $query = Banca::with(['tcc', 'bancaMembros', 'avaliacoes']);

        if ($statusFiltro) {
            $query->where('status', $statusFiltro);
        }

        $bancas = $query->orderBy('data_hora', 'desc')->get();

        $usuarioLogadoId = auth()->id();

        return view('bancas.index', compact('bancas', 'usuarioLogadoId', 'statusFiltro'));
    }

    /**
     * Exibe o formulário para criar nova banca.
     * Função: "Criar banca" — só lista TCCs que ainda não possuem banca vinculada.
     * Ao selecionar o TCC, o sistema mostra orientando e orientador vinculados.
     */
    public function create()
    {
        // Apenas TCCs sem banca podem receber uma nova banca
        $tccsDisponiveis = Tcc::with(['orientador.user', 'orientandos.user'])
            ->whereDoesntHave('banca')
            ->orderBy('tema')
            ->get();

        return view('bancas.create', compact('tccsDisponiveis'));
    }

    /**
     * Salva a nova banca.
     * Status inicial sempre "agendada" — não pode ser criada já como "realizada".
     */
    public function store(Request $request)
    {
        $request->validate([
            'tcc_id'    => 'required|integer|exists:tccs,id|unique:bancas,tcc_id',
            'data_hora' => 'required|date',
            'local'     => 'required|string|max:255',
        ], [
            'tcc_id.exists'  => 'O TCC selecionado não existe.',
            'tcc_id.unique'  => 'Este TCC já possui uma banca agendada.',
            'data_hora.date' => 'Insira uma data e hora válidas.',
        ]);

        Banca::create([
            'tcc_id'    => $request->tcc_id,
            'data_hora' => $request->data_hora,
            'local'     => $request->local,
            'status'    => 'agendada', // sempre começa como agendada
        ]);

        return redirect()->route('bancas.index')->with('sucesso', 'Banca agendada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma banca.
     * Função: "Ver Resultado Final", "Situação Banca", "Lançar notas", "Dar Parecer".
     */
    public function show(Banca $banca)
    {
        $banca->load([
            'tcc.orientandos.user',
            'tcc.orientador.user',
            'bancaMembros.user',
            'avaliacoes.avaliador',
        ]);

        return view('bancas.show', compact('banca'));
    }

    /**
     * Formulário de edição da banca (somente admin).
     * Permite editar data, local e status — não permite trocar o TCC vinculado.
     */
    public function edit(Banca $banca)
    {
        return view('bancas.edit', compact('banca'));
    }

    /**
     * Atualiza data, local e status da banca.
     * tcc_id não pode ser alterado após criação.
     */
    public function update(Request $request, Banca $banca)
    {
        $request->validate([
            'data_hora' => 'required|date',
            'local'     => 'required|string|max:255',
            'status'    => 'required|in:agendada,realizada,cancelada',
        ], [
            'data_hora.date' => 'Insira uma data e hora válidas.',
        ]);

        $banca->update($request->only(['data_hora', 'local', 'status']));

        return redirect()->route('bancas.index')->with('sucesso', 'Banca atualizada com sucesso!');
    }

    /**
     * Remove uma banca do sistema.
     */
    public function destroy(Banca $banca)
    {
        $banca->delete();

        return redirect()->route('bancas.index')->with('sucesso', 'Banca excluída com sucesso!');
    }

    /**
     * Exibe o formulário para definir os membros da banca.
     * Função: "Def. banca" — selecionar presidente, membro interno e membro externo.
     *
     * Regras do documento:
     * - Somente usuários com função "membro_banca" podem ser membros avaliadores.
     * - O orientador do TCC não pode ser selecionado como membro da banca.
     */
    public function telaDefinirMembros(Banca $banca)
    {
        $banca->load(['tcc.orientador', 'bancaMembros.user']);

        // user_id do orientador deste TCC — bloqueado de ser membro
        $userIdOrientadorBloqueado = $banca->tcc->orientador->user_id ?? null;

        // Apenas usuários com função membro_banca (conforme regra do documento)
        $usuariosDisponiveis = User::join('usuarios', 'users.id', '=', 'usuarios.id')
        ->whereIn('usuarios.funcao', ['orientador', 'membro_banca'])
        ->when($userIdOrientadorBloqueado, function ($query) use ($userIdOrientadorBloqueado) {
            $query->where('users.id', '!=', $userIdOrientadorBloqueado);
        })
        ->select('users.*', 'usuarios.funcao')
        ->orderBy('users.name')
        ->get();

        return view('bancas.definir_membros', compact('banca', 'usuariosDisponiveis'));
    }

    /**
     * Salva os membros da banca.
     * Função: "Def. banca" — valida 3 pessoas distintas, todas com função membro_banca,
     * e garante que o orientador do TCC não seja membro.
     */
    public function salvarMembros(Request $request, Banca $banca)
    {
        $request->validate([
            'presidente'     => 'required|integer|exists:users,id',
            'membro_interno' => 'required|integer|exists:users,id',
            'membro_externo' => 'required|integer|exists:users,id',
        ], [
            'presidente.required'     => 'Selecione um Presidente para a banca.',
            'membro_interno.required' => 'Selecione um Membro Interno.',
            'membro_externo.required' => 'Selecione um Membro Externo.',
        ]);

        $ids = [
            (int) $request->presidente,
            (int) $request->membro_interno,
            (int) $request->membro_externo,
        ];

        // Os 3 membros devem ser pessoas diferentes
        if (count(array_unique($ids)) < 3) {
            return redirect()->back()
                ->with('erro', 'Presidente, Membro Interno e Membro Externo devem ser pessoas diferentes.')
                ->withInput();
        }

        // Todos devem ter função membro_banca
        $totalValidos = User::whereIn('id', $ids)->where('funcao', 'membro_banca')->count();
        if ($totalValidos < 3) {
            return redirect()->back()
                ->with('erro', 'Todos os membros da banca devem ter a função "Membro da Banca".')
                ->withInput();
        }

        // O orientador do TCC não pode ser membro
        $banca->load('tcc.orientador');
        $userIdOrientadorBloqueado = $banca->tcc->orientador->user_id ?? null;

        if ($userIdOrientadorBloqueado && in_array($userIdOrientadorBloqueado, $ids)) {
            return redirect()->back()
                ->with('erro', 'O orientador do TCC não pode ser membro avaliador da banca.')
                ->withInput();
        }

        // Remove membros anteriores e insere os novos
        BancaMembro::where('banca_id', $banca->id)->delete();

        BancaMembro::create(['banca_id' => $banca->id, 'usuario_id' => $request->presidente,     'papel' => 'presidente']);
        BancaMembro::create(['banca_id' => $banca->id, 'usuario_id' => $request->membro_interno, 'papel' => 'membro_interno']);
        BancaMembro::create(['banca_id' => $banca->id, 'usuario_id' => $request->membro_externo, 'papel' => 'membro_externo']);

        return redirect()->route('bancas.show', $banca)
            ->with('sucesso', 'Membros da banca definidos com sucesso!');
    }

    /**
     * Presidente confirma que a apresentação foi realizada.
     * Função: "Confirmar Banca Realizada" — apenas o presidente pode confirmar.
     */
    public function confirmarRealizada(Banca $banca)
    {
        $eOPresidente = BancaMembro::where('banca_id', $banca->id)
            ->where('usuario_id', auth()->id())
            ->where('papel', 'presidente')
            ->exists();

        if (!$eOPresidente) {
            return redirect()->route('bancas.index')
                ->with('erro', 'Somente o Presidente da banca pode confirmar a realização.');
        }

        $banca->update(['status' => 'realizada']);

        return redirect()->route('bancas.show', $banca)
            ->with('sucesso', 'Apresentação confirmada! Status da banca atualizado para realizada.');
    }

    /**
     * Exibe a tela de fechamento da banca para o Presidente.
     * Função: "Fechamento Banca" — mostra as notas já lançadas e a média calculada.
     */
    public function telaFechamento(Banca $banca)
    {
        $eOPresidente = BancaMembro::where('banca_id', $banca->id)
            ->where('usuario_id', auth()->id())
            ->where('papel', 'presidente')
            ->exists();

        if (!$eOPresidente) {
            return redirect()->route('bancas.index')
                ->with('erro', 'Acesso negado. Apenas o Presidente da banca pode acessar esta tela.');
        }

        $avaliacoes = AvaliacaoBanca::with('avaliador')
            ->where('banca_id', $banca->id)
            ->get();

        $notas          = $avaliacoes->pluck('nota');
        $mediaCalculada = $notas->isEmpty() ? null : round($notas->avg(), 2);

        // Calcula o resultado sugerido automaticamente conforme critérios do documento:
        // >= 7      → aprovado
        // 5 a 6.9  → aprovado_com_ressalvas
        // < 5      → reprovado
        $resultadoSugerido = null;
        if ($mediaCalculada !== null) {
            if ($mediaCalculada >= 7) {
                $resultadoSugerido = 'aprovado';
            } elseif ($mediaCalculada >= 5) {
                $resultadoSugerido = 'aprovado_com_ressalvas';
            } else {
                $resultadoSugerido = 'reprovado';
            }
        }

        return view('bancas.fechamento', compact('banca', 'avaliacoes', 'mediaCalculada', 'notas', 'resultadoSugerido'));
    }

    /**
     * Processa o fechamento da banca.
     * Função: "Fechamento Banca" — calcula nota final, salva resultado na bancas
     * e sincroniza nota_final/resultado_final na tabela tccs.
     *
     * Regra do documento: resultado é derivado da média — o campo no formulário
     * é pré-selecionado automaticamente, mas o presidente pode ajustar (ex: ressalvas).
     */
    public function fecharBanca(Request $request, Banca $banca)
    {
        $eOPresidente = BancaMembro::where('banca_id', $banca->id)
            ->where('usuario_id', auth()->id())
            ->where('papel', 'presidente')
            ->exists();

        if (!$eOPresidente) {
            return redirect()->route('bancas.index')->with('erro', 'Ação não permitida.');
        }

        $request->validate([
            'resultado_final' => 'required|in:aprovado,aprovado_com_ressalvas,reprovado',
            'parecer_final'   => 'required|string|max:2000',
        ], [
            'resultado_final.required' => 'O resultado final é obrigatório.',
            'parecer_final.required'   => 'O parecer final é obrigatório.',
        ]);

        $notas = AvaliacaoBanca::where('banca_id', $banca->id)->pluck('nota');

        if ($notas->isEmpty()) {
            return redirect()->back()
                ->with('erro', 'Não é possível fechar a banca sem ao menos uma nota lançada.');
        }

        $notaFinal = round($notas->avg(), 2);

        // Salva na tabela bancas
        $banca->update([
            'nota_final'      => $notaFinal,
            'resultado_final' => $request->resultado_final,
            'parecer_final'   => $request->parecer_final,
            'status'          => 'realizada',
        ]);

        // Sincroniza nota_final e resultado_final na tabela tccs
        // para que a listagem de TCCs exiba os dados corretamente
        $banca->tcc->update([
            'nota_final'      => $notaFinal,
            'resultado_final' => $request->resultado_final,
        ]);

        return redirect()->route('bancas.show', $banca)
            ->with('sucesso', 'Banca encerrada com sucesso! Resultado registrado.');
    }

    /**
     * Exibe a ata da banca.
     * Função: "Ata da banca" — disponível após banca realizada/fechada.
     *
     * Permissões conforme documento:
     * - Admin, orientador, orientando e membros da banca participantes podem visualizar.
     */
    public function mostrarAta(Banca $banca)
    {
        if ($banca->status !== 'realizada' || !$banca->resultado_final) {
            return redirect()->route('bancas.index')
                ->with('erro', 'A ata ainda não está disponível. A banca precisa estar encerrada.');
        }

        $banca->load([
            'tcc.orientandos.user',
            'tcc.orientador.user',
            'bancaMembros.user',
            'avaliacoes.avaliador',
        ]);

        $usuarioLogado = auth()->user();

        // Verifica se o usuário tem permissão de ver a ata
        $eMembroDaBanca = $banca->bancaMembros->contains('usuario_id', $usuarioLogado->id);
        $eOrientando    = optional($usuarioLogado)->funcao === 'orientando';
        $eOrientador    = optional($usuarioLogado)->funcao === 'orientador';
        $eAdmin         = optional($usuarioLogado)->funcao === 'admin';

        if (!$eMembroDaBanca && !$eOrientando && !$eOrientador && !$eAdmin) {
            return redirect()->route('bancas.index')
                ->with('erro', 'Acesso negado. Você não tem permissão para visualizar esta ata.');
        }

        return view('bancas.ata', compact('banca'));
    }
}
