<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Models\Tcc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarefaController extends Controller
{
    public function index()
    {
        $tarefas = Tarefa::with(['tcc.orientador.user', 'tcc.orientandos.user'])
            ->latest()
            ->get();

        return view('tarefas.index', [
            'tarefas' => $tarefas,
            'modo' => 'orientador',
        ]);
    }

    public function indexOrientando()
    {
        $user = Auth::user();
        $orientando = $user?->orientando;

        if (!$orientando) {
            return redirect()
                ->route('dashboard.orientando')
                ->with('sucesso', 'Nenhum orientando vinculado a este usuario.');
        }

        $tccIds = $orientando->tccs()->pluck('tccs.id');

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
        $tccs = Tcc::where('status', 'em_andamento')
            ->orderBy('tema')
            ->get();

        return view('tarefas.create', compact('tccs'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'tcc_id' => ['required', 'integer', 'exists:tccs,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'prazo' => ['nullable', 'date'],
            'status' => ['required', 'in:pendente,em_andamento,concluida,atrasada'],
        ]);

        Tarefa::create($dados);

        return redirect()
            ->route('tarefas.index')
            ->with('sucesso', 'Tarefa cadastrada com sucesso!');
    }

    public function edit(Tarefa $tarefa)
    {
        $tccs = Tcc::orderBy('tema')->get();

        return view('tarefas.edit', compact('tarefa', 'tccs'));
    }

    public function update(Request $request, Tarefa $tarefa)
    {
        $dados = $request->validate([
            'tcc_id' => ['required', 'integer', 'exists:tccs,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'prazo' => ['nullable', 'date'],
            'status' => ['required', 'in:pendente,em_andamento,concluida,atrasada'],
        ]);

        $tarefa->update($dados);

        return redirect()
            ->route('tarefas.index')
            ->with('sucesso', 'Tarefa atualizada com sucesso!');
    }

    public function destroy(Tarefa $tarefa)
    {
        $tarefa->delete();

        return redirect()
            ->route('tarefas.index')
            ->with('sucesso', 'Tarefa excluida com sucesso!');
    }
}
