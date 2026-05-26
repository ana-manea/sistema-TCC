<?php

namespace App\Http\Controllers;

use App\Models\ArquivoEntrega;
use App\Models\Entrega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArquivoEntregaController extends Controller
{
    public function index()
    {
        $arquivosEntrega = ArquivoEntrega::with('entrega', 'usuarioEnvio')->latest()->get();

        return view('arquivos_entrega.index', compact('arquivosEntrega'));
    }

    public function create()
    {
        $entregas = Entrega::with('tcc')->get();

        return view('arquivos_entrega.create', compact('entregas'));
    }

    public function store(Request $request)
    {
        // CORRIGIDO: faz upload do arquivo de verdade
        // O original salvava um texto qualquer como arquivo_path
        $request->validate([
            'entrega_id'       => 'required|integer|exists:entregas,id',
            'arquivo'          => 'required|file|max:20480', // máx 20 MB
            'versao'           => 'nullable|integer|min:1',
            'observacao'       => 'nullable|string',
            'status_validacao' => 'nullable|in:pendente,validado,rejeitado',
        ]);

        // Salva em storage/app/public/entregas — acessível via storage:link
        $caminho = $request->file('arquivo')->store('entregas', 'public');

        ArquivoEntrega::create([
            'entrega_id'       => $request->entrega_id,
            'enviado_por'      => auth()->id() ?? 1, // usa o usuário logado
            'arquivo_path'     => $caminho,
            'versao'           => $request->versao ?? 1,
            'observacao'       => $request->observacao,
            'status_validacao' => $request->status_validacao ?? 'pendente',
        ]);

        return redirect()
            ->route('arquivos_entrega.index')
            ->with('sucesso', 'Arquivo enviado com sucesso!');
    }

    public function show(ArquivoEntrega $arquivoEntrega)
    {
        $arquivoEntrega->load('entrega.tcc', 'usuarioEnvio');

        return view('arquivos_entrega.show', compact('arquivoEntrega'));
    }

    public function edit(ArquivoEntrega $arquivoEntrega)
    {
        $arquivoEntrega->load('entrega');

        return view('arquivos_entrega.edit', compact('arquivoEntrega'));
    }

    public function update(Request $request, ArquivoEntrega $arquivoEntrega)
    {
        // Na edição só atualiza observação e status — não troca o arquivo
        $request->validate([
            'observacao'       => 'nullable|string',
            'status_validacao' => 'required|in:pendente,validado,rejeitado',
        ]);

        $arquivoEntrega->update([
            'observacao'       => $request->observacao,
            'status_validacao' => $request->status_validacao,
        ]);

        return redirect()
            ->route('arquivos_entrega.index')
            ->with('sucesso', 'Arquivo atualizado com sucesso!');
    }

    public function destroy(ArquivoEntrega $arquivoEntrega)
    {
        // Remove o arquivo físico do disco antes de deletar o registro
        Storage::disk('public')->delete($arquivoEntrega->arquivo_path);

        $arquivoEntrega->delete();

        return redirect()
            ->route('arquivos_entrega.index')
            ->with('sucesso', 'Arquivo excluído com sucesso!');
    }
}