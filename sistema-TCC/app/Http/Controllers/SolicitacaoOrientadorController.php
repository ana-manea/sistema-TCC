<?php

namespace App\Http\Controllers;

use App\Models\SolicitacaoOrientador;
use Illuminate\Http\Request;

class SolicitacaoOrientadorController extends Controller
{
    public function index()
    {
        $solicitacoesOrientador = SolicitacaoOrientador::latest()->get();

        return view('solicitacoes_orientador.index', compact('solicitacoesOrientador'));
    } 

    public function create()
    {
        return view('solicitacoes_orientador.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'orientando_id' => ['nullable|integer'],
            'orientador_id' => ['nullable|integer'],
            'mensagem'      => ['nullable|string'],
            'resposta'      => ['nullable|string'],
            'status'        => ['nullable|string'],
            'respondido_em' => ['nullable|date'],
        ]);

        SolicitacaoOrientador::create($dados);

        return redirect()
            ->route('solicitacoes_orientador.index')
            ->with('sucesso', 'Registro cadastrado com sucesso!');
    }

    public function show(SolicitacaoOrientador $solicitacaoOrientador)
    {
        return view('solicitacoes_orientador.show', compact('solicitacaoOrientador'));
    }

    public function edit(SolicitacaoOrientador $solicitacaoOrientador)
    {
        return view('solicitacoes_orientador.edit', compact('solicitacaoOrientador'));
    }

    public function update(Request $request, SolicitacaoOrientador $solicitacaoOrientador)
    {
        $dados = $request->validate([
            'orientando_id' => ['nullable|integer'],
            'orientador_id' => ['nullable|integer'],
            'mensagem'      => ['nullable|string'],
            'resposta'      => ['nullable|string'],
            'status'        => ['nullable|string'],
            'respondido_em' => ['nullable|date'],
        ]);

        $solicitacaoOrientador->update($dados);

        return redirect()
            ->route('solicitacoes_orientador.index')
            ->with('sucesso', 'Registro atualizado com sucesso!');
    }

    public function destroy(SolicitacaoOrientador $solicitacaoOrientador)
    {
        $solicitacaoOrientador->delete();

        return redirect()
            ->route('solicitacoes_orientador.index')
            ->with('sucesso', 'Registro removido com sucesso!');
    }
}
