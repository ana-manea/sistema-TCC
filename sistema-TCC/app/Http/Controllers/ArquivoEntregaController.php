<?php

namespace App\Http\Controllers;

use App\Models\ArquivoEntrega;
use App\Models\Entrega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArquivoEntregaController extends Controller
{
    // Lista todos os arquivos enviados
    public function index()
    {
        // CORRIGIDO: usa enviadoPor() — nome correto do relacionamento no Model
        $arquivosEntrega = ArquivoEntrega::with('entrega.tcc', 'enviadoPor')->latest()->get();

        return view('arquivos_entrega.index', compact('arquivosEntrega'));
    }

    // Abre o formulário de upload
    public function create()
    {
        $entregas = Entrega::with('tcc')->get();

        return view('arquivos_entrega.create', compact('entregas'));
    }

    // Faz o upload e salva o registro
    public function store(Request $request)
    {
        $request->validate([
            'entrega_id'       => 'required|integer|exists:entregas,id',
            'arquivo'          => 'required|file|max:20480', // máx 20 MB
            'versao'           => 'nullable|integer|min:1',
            'observacao'       => 'nullable|string',
            'status_validacao' => 'nullable|in:pendente,validado,rejeitado',
        ]);

        // Salva em storage/app/public/entregas — rode php artisan storage:link uma vez
        $caminho = $request->file('arquivo')->store('entregas', 'public');

        ArquivoEntrega::create([
            'entrega_id'       => $request->entrega_id,
            'enviado_por' => auth()->id(),
            'arquivo_path'     => $caminho,
            'versao'           => $request->versao ?? 1,
            'observacao'       => $request->observacao,
            'status_validacao' => $request->status_validacao ?? 'pendente',
        ]);

        return redirect()->route('arquivos_entrega.index')
            ->with('sucesso', 'Arquivo enviado com sucesso!');
    }

    // Exibe detalhes de um arquivo
    public function show(ArquivoEntrega $arquivoEntrega)
    {
        $arquivoEntrega->load('entrega.tcc', 'enviadoPor');

        return view('arquivos_entrega.show', compact('arquivoEntrega'));
    }

    // Abre o formulário de edição (só observação e status)
    public function edit(ArquivoEntrega $arquivoEntrega)
    {
        $arquivoEntrega->load('entrega');

        return view('arquivos_entrega.edit', compact('arquivoEntrega'));
    }

    // Atualiza observação e status de validação (não troca o arquivo)
    public function update(Request $request, ArquivoEntrega $arquivoEntrega)
    {
        $request->validate([
            'observacao'       => 'nullable|string',
            'status_validacao' => 'required|in:pendente,validado,rejeitado',
        ]);

        $arquivoEntrega->update([
            'observacao'       => $request->observacao,
            'status_validacao' => $request->status_validacao,
        ]);

        return redirect()->route('arquivos_entrega.index')
            ->with('sucesso', 'Arquivo atualizado com sucesso!');
    }

    // Remove o arquivo do disco e o registro do banco
    public function destroy(ArquivoEntrega $arquivoEntrega)
    {
        Storage::disk('public')->delete($arquivoEntrega->arquivo_path);

        $arquivoEntrega->delete();

        return redirect()->route('arquivos_entrega.index')
            ->with('sucesso', 'Arquivo excluído com sucesso!');
    }
}
