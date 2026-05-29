<?php

namespace App\Http\Controllers;

use App\Models\Orientador;
use App\Models\User;
use Illuminate\Http\Request;

class OrientadorController extends Controller
{

    // 1. LISTAR
    public function index()
    {
        $orientadores = Orientador::with('user')->latest()->get();
        return view('orientadores.index', compact('orientadores'));
    }

    // 2. FORMULÁRIO DE CRIAÇÃO
    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('orientadores.create', compact('users'));
    }

    // 3. SALVAR NO BANCO (Feito pelo Admin)
    public function store(Request $request)
    {
        $dados = $request->validate([
            'user_id'         => ['required', 'exists:users,id', 'unique:orientadores,user_id'],
            'area_atuacao'    => ['required', 'string', 'max:255'],
            'disponibilidade' => ['required', 'string'],
            'max_orientandos' => ['required', 'integer', 'min:1', 'max:8'],
        ], [
            'user_id.required'         => 'Selecione um professor/orientador.',
            'user_id.unique'           => 'Este professor já possui um perfil de orientador cadastrado.',
            'area_atuacao.required'    => 'Informe a área de atuação.',
            'disponibilidade.required' => 'Informe a disponibilidade e horários.',
            'max_orientandos.required' => 'Informe o limite máximo de orientandos.',
            'max_orientandos.integer'  => 'O limite de orientandos deve ser um número inteiro.',
            'max_orientandos.min'      => 'O limite mínimo é 1.',
            'max_orientandos.max'      => 'O limite máximo permitido pelo sistema é 8.',
        ]);

        Orientador::create($dados);

        return redirect()->route('orientadores.index')->with('sucesso', 'Orientador cadastrado com sucesso!');
    }

    // 4. EXIBIR DETALHES
    public function show(Orientador $orientador)
    {
        $orientador->load('user');
        return view('orientadores.show', compact('orientador'));
    }

    // 5. FORMULÁRIO DE EDIÇÃO
    public function edit(Orientador $orientador)
    {
        $orientador->load('user');
        return view('orientadores.edit', compact('orientador'));
    }

    // 6. ATUALIZAR NO BANCO
    public function update(Request $request, Orientador $orientador)
    {
        $dados = $request->validate([
            'area_atuacao'    => ['required', 'string', 'max:255'],
            'disponibilidade' => ['required', 'string'],
            'max_orientandos' => ['required', 'integer', 'min:1', 'max:8'],
        ], [
            'area_atuacao.required'    => 'Informe a área de atuação.',
            'disponibilidade.required' => 'Informe a disponibilidade e horários.',
            'max_orientandos.required' => 'Informe o limite máximo de orientandos.',
            'max_orientandos.integer'  => 'O limite de orientandos deve ser um número inteiro.',
            'max_orientandos.min'      => 'O limite mínimo é 1.',
            'max_orientandos.max'      => 'O limite máximo permitido pelo sistema é 8.',
        ]);

        $orientador->update($dados);

        return redirect()
            ->route('orientadores.index')
            ->with('sucesso', 'Perfil de orientador atualizado com sucesso!');
    }

    // 7. EXCLUIR
    public function destroy(Orientador $orientador)
    {
        $orientador->delete();

        return redirect()
            ->route('orientadores.index')
            ->with('sucesso', 'Orientador removido com sucesso!');
    }
}