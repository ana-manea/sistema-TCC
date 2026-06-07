<?php

namespace App\Http\Controllers;

use App\Models\Orientando;
use App\Models\User;
use App\Models\Orientador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrientandoController extends Controller
{
    public function index()
    {
        $orientandos = Orientando::with(['user', 'orientador.user'])->latest()->get();

        return view('orientandos.index', compact('orientandos'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('orientandos.create', compact('users'));
    }

    public function show(Orientando $orientando)
    {
        $orientando->load(['user', 'orientador.user']);

        return view('orientandos.show', compact('orientando'));
    }

    public function edit(Orientando $orientando)
    {
        $orientadores = Orientador::with('user')->get();
        return view('orientandos.edit', compact('orientando', 'orientadores'));
    }

    public function update(Request $request, Orientando $orientando)
    {
        $dados = $request->validate([
            'matricula' => ['required', 'string', 'max:255', 'unique:orientandos,matricula,' . $orientando->id],
            'curso'     => ['required', 'string', 'max:255'],
            'semestre'  => ['nullable', 'integer', 'min:1', 'max:6'],
        ], [
            'matricula.required' => 'Informe a matrícula.',
            'matricula.unique'   => 'Esta matrícula já está cadastrada.',
            'curso.required'     => 'Informe o curso.',
            'semestre.integer'   => 'O semestre deve ser um número inteiro.',
            'semestre.min'       => 'O semestre mínimo é 1.',
            'semestre.max'       => 'O semestre máximo é 6.',
        ]);

        $orientando->update($dados);

        return redirect()->route('orientandos.index')->with('sucesso', 'Orientando atualizado com sucesso!');
    }

    public function destroy(Orientando $orientando)
    {
        $orientando->delete();

        return redirect()->route('orientandos.index')->with('sucesso', 'Orientando removido com sucesso!');
    }
}