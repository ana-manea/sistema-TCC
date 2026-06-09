<?php

namespace App\Http\Controllers;

use App\Models\Tcc;
use App\Models\HistoricoTcc;
use App\Models\Orientador;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class TccController extends Controller
{
    /**
     * Lista todos os TCCs cadastrados.
     * Exibe nota_final e resultado_final vindos da banca (via relacionamento),
     * não da coluna direta do TCC — que só é preenchida após fechamento da banca.
     */
    public function index()
    {
        $user = Auth::user();

        $query = Tcc::with(['orientador.user', 'orientandos.user', 'banca']);

        if ($user->funcao === 'admin') {
            // Admin visualiza todos.
        } elseif ($user->funcao === 'orientador' && $user->orientador) {
            $query->where('orientador_id', $user->orientador->id);
        } elseif ($user->funcao === 'orientando' && $user->orientando) {
            $query->whereHas('orientandos', fn ($oq) => $oq->where('orientandos.id', $user->orientando->id));
        } elseif ($user->funcao === 'membro_banca') {
            $query->whereHas('banca.membros', fn ($mq) => $mq->where('user_id', $user->id));
        } else {
            $query->whereRaw('1 = 0');
        }

        $tccs = $query->orderBy('created_at', 'desc')->get();

        return view('tccs.index', compact('tccs'));
    }

    /**
     * Exibe o formulário de cadastro de um novo TCC.
     */
    public function create()
    {
        $user = Auth::user();

        if (!in_array($user->funcao, ['admin', 'orientando'], true)) {
            abort(403, 'Apenas administrador ou orientando podem criar TCC.');
        }

        if ($user->funcao === 'orientando' && !$user->orientando) {
            abort(403, 'Seu usuário não possui perfil de orientando vinculado.');
        }

        $orientadores = Orientador::with('user')->get();

        return view('tccs.create', compact('orientadores'));
    }

    /**
     * Salva um novo TCC no banco e registra o histórico inicial.
     * nota_final e resultado_final nunca são recebidos aqui — são calculados pela banca.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!in_array($user->funcao, ['admin', 'orientando'], true)) {
            abort(403, 'Apenas administrador ou orientando podem criar TCC.');
        }

        if ($user->funcao === 'orientando' && !$user->orientando) {
            abort(403, 'Seu usuário não possui perfil de orientando vinculado.');
        }

        $dados = $request->validate([
            'orientador_id' => ['nullable', 'exists:orientadores,id'],
            'tema'          => ['required', 'min:3', 'max:255'],
            'descricao'     => ['nullable', 'string'],
            'status'        => ['required', 'in:em_andamento,concluido,cancelado,suspenso'],
        ]);

        $tcc = Tcc::create($dados);

        if ($user->funcao === 'orientando' && $user->orientando) {
            $tcc->orientandos()->syncWithoutDetaching([$user->orientando->id]);
        }

        HistoricoTcc::create([
            'tcc_id'          => $tcc->id,
            'alterado_por'    => Auth::id(),
            'status_anterior' => null,
            'status_novo'     => $tcc->status,
            'observacao'      => 'TCC criado.',
        ]);

        return redirect()
            ->route('tccs.index')
            ->with('sucesso', 'Trabalho de Conclusão de Curso registrado!');
    }

    /**
     * Exibe os detalhes completos de um TCC.
     * Resultado final é lido da banca, nunca do campo direto do TCC.
     */
    public function show(Tcc $tcc)
    {
        $this->autorizarVisualizacao($tcc);

        $tcc->load([
            'orientador.user',
            'orientandos.user',
            'banca.membros.user',
            'banca.avaliacoes.avaliador',
            'feedbacks.orientador.user',
        ]);

        return view('tccs.show', compact('tcc'));
    }

    /**
     * Exibe o formulário de edição de um TCC existente.
     * O formulário não exibe nem aceita nota_final/resultado_final.
     */
    public function edit(Tcc $tcc)
    {
        $this->autorizarEdicao($tcc);

        $orientadores = Orientador::with('user')->get();

        return view('tccs.edit', compact('tcc', 'orientadores'));
    }

    /**
     * Atualiza os dados do TCC e registra no histórico se o status mudar.
     * nota_final e resultado_final são bloqueados aqui — só a banca os define.
     */
    public function update(Request $request, Tcc $tcc)
    {
        $this->autorizarEdicao($tcc);

        $dados = $request->validate([
            'orientador_id' => ['nullable', 'exists:orientadores,id'],
            'tema'          => ['required', 'min:3', 'max:255'],
            'descricao'     => ['nullable', 'string'],
            'status'        => ['required', 'in:em_andamento,concluido,cancelado,suspenso'],
            'observacao'    => ['nullable', 'string', 'max:500'],
        ]);

        $statusAnterior = $tcc->status;

        // 'observacao' é só para o histórico — não existe na tabela tccs. Nota final e resultado final nunca são atualizados por este formulário.
        $tcc->update(Arr::except($dados, ['observacao', 'nota_final', 'resultado_final']));

        // Registra histórico apenas se o status mudou
        if ($statusAnterior !== $dados['status']) {
            HistoricoTcc::create([
                'tcc_id'          => $tcc->id,
                'alterado_por'    => Auth::id(),
                'status_anterior' => $statusAnterior,
                'status_novo'     => $dados['status'],
                'observacao'      => $request->input('observacao'),
            ]);
        }

        return redirect()
            ->route('tccs.show', $tcc)
            ->with('sucesso', 'TCC atualizado com sucesso!');
    }

    /**
     * Remove um TCC do sistema.
     */
    public function destroy(Tcc $tcc)
    {
        $this->autorizarEdicao($tcc);

        $tcc->delete();

        return redirect()
            ->route('tccs.index')
            ->with('sucesso', 'TCC removido com sucesso!');
    }

    /**
     * Lista apenas os TCCs com status "em_andamento".
     * Visível para todos exceto membros de banca (conforme o documento).
     */
    public function emAndamento()
    {
        $user = Auth::user();

        if ($user->funcao === 'membro_banca') {
            abort(403, 'Membros da banca não acessam a lista de TCCs em andamento.');
        }

        $query = Tcc::with(['orientador.user', 'orientandos.user'])
            ->withCount(['tarefas', 'entregas', 'reunioes'])
            ->where('status', 'em_andamento');

        if ($user->funcao === 'admin') {
            // Admin visualiza todos os TCCs em andamento.
        } elseif ($user->funcao === 'orientador' && $user->orientador) {
            $query->where('orientador_id', $user->orientador->id);
        } elseif ($user->funcao === 'orientando' && $user->orientando) {
            $query->whereHas('orientandos', fn ($oq) => $oq->where('orientandos.id', $user->orientando->id));
        } else {
            $query->whereRaw('1 = 0');
        }

        $tccs = $query->orderBy('created_at', 'desc')->get();

        return view('tccs.em_andamento', compact('tccs'));
    }

    /**
     * Exibe o histórico de mudanças de status de um TCC.
     */
    public function historico(Tcc $tcc)
    {
        $this->autorizarVisualizacao($tcc);

        $historicos = HistoricoTcc::with('alteradoPor')
            ->where('tcc_id', $tcc->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tccs.historico', compact('tcc', 'historicos'));
    }

    // Limita a visualização apenas para quem está autorizado/vinculado
    private function autorizarVisualizacao(Tcc $tcc): void
    {
        $user = Auth::user();

        if ($user->funcao === 'admin') {
            return;
        }

        if ($user->funcao === 'orientador' && $user->orientador && $tcc->orientador_id === $user->orientador->id) {
            return;
        }

        if ($user->funcao === 'orientando' && $user->orientando && $tcc->orientandos()->where('orientandos.id', $user->orientando->id)->exists()) {
            return;
        }

        if ($user->funcao === 'membro_banca' && $tcc->banca?->membros()->where('user_id', $user->id)->exists()) {
            return;
        }

        abort(403, 'Você não tem permissão para acessar este TCC.');
    }

    // Limita a edição apenas para quem está autorizado/vincluado
    private function autorizarEdicao(Tcc $tcc): void
    {
        $user = Auth::user();

        if ($user->funcao === 'admin') {
            return;
        }

        if ($user->funcao === 'orientando' && $user->orientando && $tcc->orientandos()->where('orientandos.id', $user->orientando->id)->exists()) {
            return;
        }

        abort(403, 'Você não tem permissão para editar este TCC.');
    }
}
