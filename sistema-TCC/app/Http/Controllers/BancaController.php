<?php

namespace App\Http\Controllers;

use App\Models\AvaliacaoBanca;
use App\Models\Banca;
use App\Models\BancaMembro;
use App\Models\Tcc;
use App\Models\User;
use App\Notifications\BancaAgendadaNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BancaController extends Controller
{
    /**
     * Lista todas as bancas, com filtro opcional por status.
     * Função: "Situação Banca" — exibe agendadas, realizadas e canceladas.
     */
    public function index(Request $request)
    {
        $statusFiltro = $request->input('status');

        $usuarioLogado = Auth::user();

        $query = Banca::with(['tcc.orientador.user', 'tcc.orientandos.user', 'membros.user', 'avaliacoes']);

        if ($usuarioLogado->funcao === 'admin') {
            // Admin visualiza todas as bancas.
        } elseif ($usuarioLogado->funcao === 'orientador' && $usuarioLogado->orientador) {
            $query->whereHas('tcc', fn ($q) => $q->where('orientador_id', $usuarioLogado->orientador->id));
        } elseif ($usuarioLogado->funcao === 'orientando' && $usuarioLogado->orientando) {
            $query->whereHas('tcc.orientandos', fn ($q) => $q->where('orientandos.id', $usuarioLogado->orientando->id));
        } elseif ($usuarioLogado->funcao === 'membro_banca') {
            $query->whereHas('membros', fn ($q) => $q->where('user_id', $usuarioLogado->id));
        } else {
            $query->whereRaw('1 = 0');
        }

        if ($statusFiltro) {
            $query->where('status', $statusFiltro);
        }

        $bancas = $query->orderBy('data_hora', 'desc')->get();

        $usuarioLogadoId = Auth::id();

        return view('bancas.index', compact('bancas', 'usuarioLogadoId', 'statusFiltro'));
    }

    /**
     * Exibe o formulário para criar nova banca.
     * Função: "Criar banca" — só lista TCCs que ainda não possuem banca vinculada.
     * Ao selecionar o TCC, o sistema mostra orientando e orientador vinculados.
     */
    public function create()
    {
        $this->autorizarAdmin();

        // Apenas TCCs sem banca podem receber uma nova banca
        $tccs = Tcc::with(['orientador.user', 'orientandos.user'])
            ->whereDoesntHave('banca')
            ->orderBy('tema')
            ->get();

        // Apenas usuários com função membro_banca podem ser selecionados no formulário
        $membrosBanca = User::where('funcao', 'membro_banca')
            ->orderBy('name')
            ->get();

        return view('bancas.create', compact('tccs', 'membrosBanca'));
    }

    /**
     * Salva a nova banca.
     * Status inicial sempre "agendada" — não pode ser criada já como "realizada".
     * Também salva os três membros da banca: presidente, membro interno e membro externo.
     */
    public function store(Request $request)
    {
        $this->autorizarAdmin();

        $request->validate([
            'tcc_id'            => 'required|integer|exists:tccs,id|unique:bancas,tcc_id',
            'data_hora'         => 'required|date',
            'local'             => 'required|string|max:255',
            'presidente_id'     => 'required|integer|exists:users,id|different:membro_interno_id|different:membro_externo_id',
            'membro_interno_id' => 'required|integer|exists:users,id|different:presidente_id|different:membro_externo_id',
            'membro_externo_id' => 'required|integer|exists:users,id|different:presidente_id|different:membro_interno_id',
        ], [
            'tcc_id.exists'              => 'O TCC selecionado não existe.',
            'tcc_id.unique'              => 'Este TCC já possui uma banca agendada.',
            'data_hora.date'             => 'Insira uma data e hora válidas.',
            'presidente_id.required'     => 'Selecione um Presidente para a banca.',
            'membro_interno_id.required' => 'Selecione um Membro Interno.',
            'membro_externo_id.required' => 'Selecione um Membro Externo.',
            'different'                  => 'Presidente, Membro Interno e Membro Externo devem ser pessoas diferentes.',
        ]);

        $ids = [
            (int) $request->presidente_id,
            (int) $request->membro_interno_id,
            (int) $request->membro_externo_id,
        ];

        // Todos devem ter função membro_banca
        $totalValidos = User::whereIn('id', $ids)
            ->where('funcao', 'membro_banca')
            ->count();

        if ($totalValidos < 3) {
            return redirect()->back()
                ->with('erro', 'Todos os membros da banca devem ter a função "Membro da Banca".')
                ->withInput();
        }

        // O orientador do TCC não pode ser membro
        $tcc = Tcc::with('orientador')->findOrFail($request->tcc_id);
        $userIdOrientadorBloqueado = $tcc->orientador->user_id ?? null;

        if ($userIdOrientadorBloqueado && in_array($userIdOrientadorBloqueado, $ids)) {
            return back()
                ->with('erro', 'O orientador do TCC não pode ser membro avaliador da banca.')
                ->withInput();
        }

        $banca = Banca::create([
            'tcc_id'    => $request->tcc_id,
            'data_hora' => $request->data_hora,
            'local'     => $request->local,
            'status'    => 'agendada', // sempre começa como agendada
        ]);

        BancaMembro::create(['banca_id' => $banca->id, 'user_id' => $request->presidente_id,     'papel' => 'presidente']);
        BancaMembro::create(['banca_id' => $banca->id, 'user_id' => $request->membro_interno_id, 'papel' => 'membro_interno']);
        BancaMembro::create(['banca_id' => $banca->id, 'user_id' => $request->membro_externo_id, 'papel' => 'membro_externo']);

        $this->notificarBanca($banca->fresh());

        return redirect()->route('bancas.index')->with('sucesso', 'Banca agendada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma banca.
     * Função: "Ver Resultado Final", "Situação Banca", "Lançar notas", "Dar Parecer".
     */
    public function show(Banca $banca)
    {
        $this->autorizarVisualizacao($banca);

        $banca->load([
            'tcc.orientandos.user',
            'tcc.orientador.user',
            'membros.user',
            'avaliacoes.avaliador',
        ]);

        return view('bancas.show', compact('banca'));
    }

    /**
     * Formulário de edição da banca (somente admin).
     * Permite editar TCC, data, local, status e membros.
     */
    public function edit(Banca $banca)
    {
        $this->autorizarAdmin();

        $banca->load(['membros', 'tcc.orientador.user', 'tcc.orientandos.user']);

        // Na edição, lista TCCs sem banca e também mantém o TCC atual da banca.
        $tccs = Tcc::with(['orientador.user', 'orientandos.user'])
            ->where(function ($query) use ($banca) {
                $query->whereDoesntHave('banca')
                    ->orWhere('id', $banca->tcc_id);
            })
            ->orderBy('tema')
            ->get();

        $membrosBanca = User::where('funcao', 'membro_banca')
            ->orderBy('name')
            ->get();

        return view('bancas.edit', compact('banca', 'tccs', 'membrosBanca'));
    }

    /**
     * Atualiza data, local, status e membros da banca.
     */
   public function update(Request $request, Banca $banca)
    {
        $this->autorizarAdmin();

        $request->validate([
            'tcc_id'            => 'required|integer|exists:tccs,id|unique:bancas,tcc_id,' . $banca->id,
            'data_hora'         => 'required|date',
            'local'             => 'required|string|max:255',
            'status'            => 'required|in:agendada,realizada,cancelada',
            'presidente_id'     => 'required|integer|exists:users,id|different:membro_interno_id|different:membro_externo_id',
            'membro_interno_id' => 'required|integer|exists:users,id|different:presidente_id|different:membro_externo_id',
            'membro_externo_id' => 'required|integer|exists:users,id|different:presidente_id|different:membro_interno_id',
        ], [
            'data_hora.date'             => 'Insira uma data e hora válidas.',
            'presidente_id.required'     => 'Selecione um Presidente para a banca.',
            'membro_interno_id.required' => 'Selecione um Membro Interno.',
            'membro_externo_id.required' => 'Selecione um Membro Externo.',
            'different'                  => 'Presidente, Membro Interno e Membro Externo devem ser pessoas diferentes.',
        ]);

        $ids = [
            (int) $request->presidente_id,
            (int) $request->membro_interno_id,
            (int) $request->membro_externo_id,
        ];

        // Todos devem ter função membro_banca
        $totalValidos = User::whereIn('id', $ids)
            ->where('funcao', 'membro_banca')
            ->count();

        if ($totalValidos < 3) {
            return redirect()->back()
                ->with('erro', 'Todos os membros da banca devem ter a função "Membro da Banca".')
                ->withInput();
        }

        // O orientador do TCC não pode ser membro
        $tcc = Tcc::with('orientador')->findOrFail($request->tcc_id);
        $userIdOrientadorBloqueado = $tcc->orientador->user_id ?? null;

        if ($userIdOrientadorBloqueado && in_array($userIdOrientadorBloqueado, $ids)) {
            return redirect()->back()
                ->with('erro', 'O orientador do TCC não pode ser membro avaliador da banca.')
                ->withInput();
        }

        $banca->update($request->only(['tcc_id', 'data_hora', 'local', 'status']));

        // Remove membros anteriores e insere os novos
        BancaMembro::where('banca_id', $banca->id)->delete();

        BancaMembro::create(['banca_id' => $banca->id, 'user_id' => $request->presidente_id,     'papel' => 'presidente']);
        BancaMembro::create(['banca_id' => $banca->id, 'user_id' => $request->membro_interno_id, 'papel' => 'membro_interno']);
        BancaMembro::create(['banca_id' => $banca->id, 'user_id' => $request->membro_externo_id, 'papel' => 'membro_externo']);

        $this->notificarBanca($banca->fresh());

        return redirect()->route('bancas.index')->with('sucesso', 'Banca atualizada com sucesso!');
    }

    /**
     * Remove uma banca do sistema.
     */
    public function destroy(Banca $banca)
    {
        $this->autorizarAdmin();

        $banca->delete();

        return redirect()->route('bancas.index')->with('sucesso', 'Banca excluída com sucesso!');
    }

    /**
     * Presidente confirma que a apresentação foi realizada.
     * Função: "Confirmar Banca Realizada" — apenas o presidente pode confirmar.
     */
    public function confirmarRealizada(Banca $banca)
    {
        $eOPresidente = BancaMembro::where('banca_id', $banca->id)
            ->where('user_id', Auth::id())
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
            ->where('user_id', Auth::id())
            ->where('papel', 'presidente')
            ->exists();

        if (!$eOPresidente) {
            return redirect()->route('bancas.index')
                ->with('erro', 'Acesso negado. Apenas o Presidente da banca pode acessar esta tela.');
        }

        $banca->load(['tcc.orientador.user', 'tcc.orientandos.user', 'membros.user', 'avaliacoes.avaliador']);

        $avaliacoes = $banca->avaliacoes;
        $notas = $avaliacoes->pluck('nota');
        $mediaCalculada = $notas->isEmpty() ? null : round((float) $notas->avg(), 2);

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
            ->where('user_id', Auth::id())
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

        $notaFinal = round((float) $notas->avg(), 2);

        // Salva na tabela bancas
        $banca->update([
            'nota_final'      => $notaFinal,
            'resultado_final' => $request->resultado_final,
            'parecer_final'   => $request->parecer_final,
            'status'          => 'realizada',
        ]);

        // Sincroniza nota_final e resultado_final na tabela tccs
        // para que a listagem de TCCs exiba os dados corretamente
        if ($banca->tcc) {
            $banca->tcc->update([
                'nota_final'      => $notaFinal,
                'resultado_final' => $request->resultado_final,
            ]);
        }

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
            'membros.user',
            'avaliacoes.avaliador',
        ]);

        // Reaproveita a mesma regra de visualização da banca:
        // admin, orientador/orientando vinculados ao TCC e membros participantes.
        $this->autorizarVisualizacao($banca);

        return view('bancas.ata', compact('banca'));
    }

    // Restringe as ações aos admins
    private function autorizarAdmin(): void
    {
        if (Auth::user()?->funcao !== 'admin') {
            abort(403, 'Somente administradores podem executar esta ação.');
        }
    }

    // Limita a visualização de acordo com o perfil
    private function autorizarVisualizacao(Banca $banca): void
    {
        $user = Auth::user();

        if ($user->funcao === 'admin') {
            return;
        }

        $banca->loadMissing('tcc.orientandos', 'tcc.orientador', 'membros');

        if ($user->funcao === 'membro_banca' && $banca->membros->contains('user_id', $user->id)) {
            return;
        }

        if ($user->funcao === 'orientador' && $user->orientador && $banca->tcc?->orientador_id === $user->orientador->id) {
            return;
        }

        if ($user->funcao === 'orientando' && $user->orientando && $banca->tcc?->orientandos->contains('id', $user->orientando->id)) {
            return;
        }

        abort(403, 'Você não tem permissão para visualizar esta banca.');
    }
    private function notificarBanca(?Banca $banca): void
    {
        if (!$banca) {
            return;
        }

        $banca->loadMissing(['tcc.orientador.user', 'tcc.orientandos.user', 'membros.user']);

        $destinatarios = collect();

        if ($banca->tcc?->orientador?->user) {
            $destinatarios->push($banca->tcc->orientador->user);
        }

        foreach ($banca->tcc?->orientandos ?? [] as $orientando) {
            if ($orientando->user) {
                $destinatarios->push($orientando->user);
            }
        }

        foreach ($banca->membros as $membro) {
            if ($membro->user) {
                $destinatarios->push($membro->user);
            }
        }

        $destinatarios->unique('id')->each(fn ($usuario) => $usuario->notify(new BancaAgendadaNotification($banca)));
    }
}
