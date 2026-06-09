<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Models\Tcc;
use App\Notifications\TarefaAgendadaNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarefaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->funcao === 'orientando') {
            return redirect()->route('aluno.tarefas.index');
        }

        if (!in_array($user->funcao, ['admin', 'orientador'], true)) {
            abort(403, 'Você não tem permissão para acessar tarefas.');
        }

        $query = Tarefa::with(['tcc.orientador.user', 'tcc.orientandos.user'])->latest();

        if ($user->funcao === 'orientador' && $user->orientador) {
            $query->whereHas('tcc', fn ($q) => $q->where('orientador_id', $user->orientador->id));
        } elseif ($user->funcao === 'orientador') {
            $query->whereRaw('1 = 0');
        }

        $tarefas = $query->get();

        return view('tarefas.index', [
            'tarefas' => $tarefas,
            'modo' => 'orientador',
        ]);
    }

    public function indexOrientando()
    {
        $user = Auth::user();

        if ($user->funcao !== 'orientando' || !$user->orientando) {
            abort(403, 'Somente orientandos acessam esta listagem.');
        }

        $tccIds = $user->orientando->tccs()->pluck('tccs.id');

        $tarefas = Tarefa::with(['tcc.orientador.user', 'tcc.orientandos.user'])
            ->whereIn('tcc_id', $tccIds)
            ->orderBy('prazo')
            ->get();

        return view('tarefas.index', [
            'tarefas' => $tarefas,
            'modo' => 'orientando',
        ]);
    }

    public function create()
    {
        $user = Auth::user();

        if (!in_array($user->funcao, ['admin', 'orientador'], true)) {
            abort(403, 'Somente admin ou orientador podem criar tarefas.');
        }

        $tccs = Tcc::where('status', 'em_andamento')
            ->when($user->funcao === 'orientador' && $user->orientador, fn ($q) => $q->where('orientador_id', $user->orientador->id))
            ->when($user->funcao === 'orientador' && !$user->orientador, fn ($q) => $q->whereRaw('1 = 0'))
            ->orderBy('tema')
            ->get();

        return view('tarefas.create', compact('tccs'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!in_array($user->funcao, ['admin', 'orientador'], true)) {
            abort(403, 'Somente admin ou orientador podem criar tarefas.');
        }

        $dados = $request->validate([
            'tcc_id' => ['required', 'integer', 'exists:tccs,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'prazo' => ['nullable', 'date'],
            'status' => ['required', 'in:pendente,em_andamento,concluida,atrasada'],
        ]);

        $tcc = Tcc::findOrFail($dados['tcc_id']);
        $this->autorizarGerenciarTcc($tcc);

        $tarefa = Tarefa::create($dados);
        $tarefa->load(['tcc.orientandos.user']);

        foreach ($tarefa->tcc?->orientandos ?? [] as $orientando) {
            if ($orientando->user) {
                $orientando->user->notify(new TarefaAgendadaNotification($tarefa));
            }
        }

        return redirect()
            ->route('tarefas.index')
            ->with('sucesso', 'Tarefa cadastrada com sucesso!');
    }

    public function edit(Tarefa $tarefa)
    {
        $this->autorizarGerenciarTcc($tarefa->tcc);

        $user = Auth::user();

        $tccs = Tcc::where(fn ($q) => $this->filtrarTccsGerenciaveis($q))
            ->orderBy('tema')
            ->get();

        return view('tarefas.edit', compact('tarefa', 'tccs'));
    }

    public function update(Request $request, Tarefa $tarefa)
    {
        $this->autorizarGerenciarTcc($tarefa->tcc);

        $dados = $request->validate([
            'tcc_id' => ['required', 'integer', 'exists:tccs,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'prazo' => ['nullable', 'date'],
            'status' => ['required', 'in:pendente,em_andamento,concluida,atrasada'],
        ]);

        $novoTcc = Tcc::findOrFail($dados['tcc_id']);
        $this->autorizarGerenciarTcc($novoTcc);

        $tarefa->update($dados);

        return redirect()
            ->route('tarefas.index')
            ->with('sucesso', 'Tarefa atualizada com sucesso!');
    }

    public function destroy(Tarefa $tarefa)
    {
        $this->autorizarGerenciarTcc($tarefa->tcc);

        $tarefa->delete();

        return redirect()
            ->route('tarefas.index')
            ->with('sucesso', 'Tarefa excluida com sucesso!');
    }

    private function filtrarTccsGerenciaveis($query): void
    {
        $user = Auth::user();

        if ($user->funcao === 'admin') {
            return;
        }

        if ($user->funcao === 'orientador' && $user->orientador) {
            $query->where('orientador_id', $user->orientador->id);
            return;
        }

        $query->whereRaw('1 = 0');
    }

    private function autorizarGerenciarTcc(?Tcc $tcc): void
    {
        if (!$tcc) {
            abort(404);
        }

        $user = Auth::user();

        if ($user->funcao === 'admin') {
            return;
        }

        if ($user->funcao === 'orientador' && $user->orientador && (int) $tcc->orientador_id === (int) $user->orientador->id) {
            return;
        }

        abort(403, 'Você não tem permissão para gerenciar tarefas deste TCC.');
    }
}
