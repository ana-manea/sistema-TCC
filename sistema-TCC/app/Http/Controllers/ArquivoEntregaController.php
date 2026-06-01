<?php

namespace App\Http\Controllers;

use App\Models\ArquivoEntrega;
use App\Models\Entrega;
use App\Models\HistoricoTcc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArquivoEntregaController extends Controller
{
    public function index()
    {
        $arquivosEntrega = ArquivoEntrega::with('entrega.tcc.orientandos.user', 'enviadoPor')
            ->whereHas('entrega.tcc', fn ($q) => $this->filtrarTccsPermitidos($q))
            ->latest()
            ->get();

        return view('arquivos_entrega.index', compact('arquivosEntrega'));
    }

    public function create()
    {
        $entregas = $this->entregasPermitidasParaFormulario();
        return view('arquivos_entrega.create', compact('entregas'));
    }

    public function store(Request $request)
    {
        // CORRIGIDO: faz upload do arquivo de verdade
        // O original salvava um texto qualquer como arquivo_path
        
        $dados = $request->validate([
            'entrega_id' => ['required','integer','exists:entregas,id'],
            'arquivo' => ['required','file','mimes:pdf,doc,docx','max:20480'],
            'observacao' => ['nullable','string'],
        ]);

        $entrega = Entrega::with('tcc')->findOrFail($dados['entrega_id']);
        $this->autorizarEntrega($entrega, true);

        $versao = ((int) ArquivoEntrega::where('entrega_id', $entrega->id)->max('versao')) + 1;
        // Salva em storage/app/public/entregas — acessível via storage:link
        $path = $request->file('arquivo')->store('entregas/' . $entrega->id, 'public');

        $arquivo = ArquivoEntrega::create([
            'entrega_id' => $entrega->id,
            'enviado_por' => Auth::id(),
            'arquivo_path' => $path,
            'versao' => $versao,
            'observacao' => $dados['observacao'] ?? null,
            'status_validacao' => 'pendente',
        ]);

        $entrega->update(['status' => 'entregue']);
        $this->registrarHistorico($entrega, 'Arquivo enviado para a entrega "' . $entrega->titulo . '". Versão ' . $arquivo->versao . '.');

        return redirect()->route($this->rota('arquivos_entrega.index'))->with('sucesso', 'Arquivo enviado com sucesso!');
    }

    public function show(ArquivoEntrega $arquivoEntrega)
    {
        $arquivoEntrega->load('entrega.tcc.orientandos.user', 'enviadoPor');
        $this->autorizarEntrega($arquivoEntrega->entrega);

        return view('arquivos_entrega.show', compact('arquivoEntrega'));
    }

    public function edit(ArquivoEntrega $arquivoEntrega)
    {
        $arquivoEntrega->load('entrega.tcc');
        $this->autorizarValidacao($arquivoEntrega->entrega);
        $entregas = $this->entregasPermitidasParaFormulario();

        return view('arquivos_entrega.edit', compact('arquivoEntrega', 'entregas'));
    }

    public function update(Request $request, ArquivoEntrega $arquivoEntrega)
    {
        $arquivoEntrega->load('entrega.tcc');
        $this->autorizarValidacao($arquivoEntrega->entrega);

        // Na edição só atualiza observação e status — não troca o arquivo
        $dados = $request->validate([
            'status_validacao' => ['required','in:pendente,validado,rejeitado'],
            'observacao' => ['nullable','string'],
        ]);

        $arquivoEntrega->update($dados);

        $arquivoEntrega->entrega->update([
            'status' => $dados['status_validacao'] === 'validado' ? 'validado' : ($dados['status_validacao'] === 'rejeitado' ? 'rejeitado' : 'entregue'),
        ]);

        $this->registrarHistorico($arquivoEntrega->entrega, 'Validação de arquivo atualizada para: ' . $dados['status_validacao']);

        return redirect()->route($this->rota('arquivos_entrega.index'))->with('sucesso', 'Arquivo atualizado com sucesso!');
    }

    public function destroy(ArquivoEntrega $arquivoEntrega)
    {
        if (Auth::user()->funcao !== 'admin') {
            abort(403);
        }
        // Remove o arquivo físico do disco antes de deletar o registro
        if ($arquivoEntrega->arquivo_path && Storage::disk('public')->exists($arquivoEntrega->arquivo_path)) {
            Storage::disk('public')->delete($arquivoEntrega->arquivo_path);
        }

        $arquivoEntrega->delete();

        return redirect()->route('arquivos_entrega.index')->with('sucesso', 'Registro removido com sucesso!');
    }

    private function filtrarTccsPermitidos($query): void
    {
        $user = Auth::user();

        if ($user->funcao === 'orientando' && $user->orientando) {
            $query->whereHas('orientandos', fn ($q) => $q->where('orientandos.id', $user->orientando->id));
        } elseif ($user->funcao === 'orientador' && $user->orientador) {
            $query->where('orientador_id', $user->orientador->id);
        } elseif ($user->funcao === 'membro_banca') {
            $query->whereHas('banca.membros', fn ($q) => $q->where('user_id', $user->id));
        }
    }

    private function autorizarEntrega(?Entrega $entrega, bool $editar = false): void
    {
        if (!$entrega || !$entrega->tcc) { abort(404); }

        $user = Auth::user();
        if ($user->funcao === 'admin') { return; }

        if ($editar && $user->funcao !== 'orientando') {
            abort(403, 'Somente o aluno pode enviar arquivo de entrega.');
        }

        if ($user->funcao === 'orientando' && $user->orientando && $entrega->tcc->orientandos()->where('orientandos.id', $user->orientando->id)->exists()) {
            return;
        }

        if (!$editar && $user->funcao === 'orientador' && $user->orientador && $entrega->tcc->orientador_id === $user->orientador->id) {
            return;
        }

        if (!$editar && $user->funcao === 'membro_banca' && $entrega->tcc->banca?->membros()->where('user_id', $user->id)->exists()) {
            return;
        }

        abort(403);
    }

    private function autorizarValidacao(?Entrega $entrega): void
    {
        if (!$entrega || !$entrega->tcc) { abort(404); }
        $user = Auth::user();

        if ($user->funcao === 'admin') { return; }
        if ($user->funcao === 'orientador' && $user->orientador && $entrega->tcc->orientador_id === $user->orientador->id) { return; }

        abort(403, 'Somente admin ou orientador do TCC pode validar arquivo.');
    }

    private function entregasPermitidasParaFormulario()
    {
        return Entrega::with('tcc.orientandos.user')
            ->whereHas('tcc', fn ($q) => $this->filtrarTccsPermitidos($q))
            ->orderBy('prazo')
            ->get();
    }

    private function registrarHistorico(Entrega $entrega, string $observacao): void
    {
        HistoricoTcc::create([
            'tcc_id' => $entrega->tcc_id,
            'alterado_por' => Auth::id(),
            'status_anterior' => $entrega->tcc?->status,
            'status_novo' => $entrega->tcc?->status,
            'observacao' => $observacao,
        ]);
    }

    private function rota(string $adminRoute): string
    {
        // Mantém a rota global para evitar RouteNotFoundException.
        return $adminRoute;
    }
}