<?php

namespace App\Http\Controllers;

use App\Models\ArquivoEntrega;
use Illuminate\Http\Request;

class ArquivoEntregaController extends Controller
{
    public function index()
    {
        $arquivosEntrega = ArquivoEntrega::latest()->get();

        return view('arquivos_entrega.index', compact('arquivosEntrega'));
    }

    public function create()
    {
        return view('arquivos_entrega.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'entrega_id'       => ['nullable|integer'],
            'enviado_por'      => ['nullable|integer'],
            'arquivo_path'     => ['required|string|max:255'],
            'versao'           => ['nullable|integer|min:0'],
            'observacao'       => ['nullable|string'],
            'status_validacao' => ['nullable|string'],
        ]);

        ArquivoEntrega::create($dados);

        return redirect()
            ->route('arquivos_entrega.index')
            ->with('sucesso', 'Registro cadastrado com sucesso!');
    }

    public function show(ArquivoEntrega $arquivoEntrega)
    {
        return view('arquivos_entrega.show', compact('arquivoEntrega'));
    }

    public function edit(ArquivoEntrega $arquivoEntrega)
    {
        return view('arquivos_entrega.edit', compact('arquivoEntrega'));
    }

    public function update(Request $request, ArquivoEntrega $arquivoEntrega)
    {
        $dados = $request->validate([
            'entrega_id'       => ['nullable|integer'],
            'enviado_por'      => ['nullable|integer'],
            'arquivo_path'     => ['required|string|max:255'],
            'versao'           => ['nullable|integer|min:0'],
            'observacao'       => ['nullable|string'],
            'status_validacao' => ['nullable|string'],
        ]);

        $arquivoEntrega->update($dados);

        return redirect()
            ->route('arquivos_entrega.index')
            ->with('sucesso', 'Registro atualizado com sucesso!');
    }

    public function destroy(ArquivoEntrega $arquivoEntrega)
    {
        $arquivoEntrega->delete();

        return redirect()
            ->route('arquivos_entrega.index')
            ->with('sucesso', 'Registro removido com sucesso!');
    }
}
