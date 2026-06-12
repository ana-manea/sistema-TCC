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
    // Lista todos os arquivos enviados
    public function index()
    {
        $arquivosEntrega = ArquivoEntrega::with('entrega.tcc.orientandos.user', 'enviadoPor')
            ->whereHas('entrega.tcc', fn ($q) => $this->filtrarTccsPermitidos($q))
            ->latest()
            ->get();

        return view('arquivos_entrega.index', compact('arquivosEntrega'));
    }

    // Abre o formulário de upload
    public function create()
    {
        $entregas = $this->entregasPermitidasParaFormulario();
        return view('arquivos_entrega.create', compact('entregas'));
    }

    // Faz o upload e salva o registro
    public function store(Request $request)
    {
        $dados = $request->validate([
            'entrega_id' => ['required', 'integer', 'exists:entregas,id'],
            'arquivo'    => ['required', 'file', 'mimes:pdf,doc,docx', 'max:20480'],
            'observacao' => ['nullable', 'string'],
        ]);

        $entrega = Entrega::with('tcc')->findOrFail($dados['entrega_id']);
        $this->autorizarEntrega($entrega, true);

        $versao = ((int) ArquivoEntrega::where('entrega_id', $entrega->id)->max('versao')) + 1;
        $path   = $request->file('arquivo')->store('entregas/' . $entrega->id, 'public');

        $arquivo = ArquivoEntrega::create([
            'entrega_id'       => $entrega->id,
            'enviado_por'      => Auth::id(),
            'arquivo_path'     => $path,
            'versao'           => $versao,
            'observacao'       => $dados['observacao'] ?? null,
            'status_validacao' => 'pendente',
        ]);

        $entrega->update(['status' => 'entregue']);
        $this->registrarHistorico(
            $entrega,
            'Arquivo enviado para a entrega "' . $entrega->titulo . '". Versão ' . $arquivo->versao . '.'
        );

        return redirect()->route('arquivos_entrega.index')
            ->with('sucesso', 'Arquivo enviado com sucesso!');
    }

    // Exibe detalhes de um arquivo
    public function show(ArquivoEntrega $arquivoEntrega)
    {
        $arquivoEntrega->load('entrega.tcc.orientandos.user', 'enviadoPor');
        $this->autorizarEntrega($arquivoEntrega->entrega);

        return view('arquivos_entrega.show', compact('arquivoEntrega'));
    }

    // Abre o formulário de edição
    // CORRIGIDO: usa autorizarEntrega() para que aluno também possa acessar
    public function edit(ArquivoEntrega $arquivoEntrega)
    {
        $arquivoEntrega->load('entrega.tcc');
        $this->autorizarEntrega($arquivoEntrega->entrega);

        $entregas = $this->entregasPermitidasParaFormulario();

        return view('arquivos_entrega.edit', compact('arquivoEntrega', 'entregas'));
    }

    // Atualiza o arquivo
    // CORRIGIDO: aluno só atualiza observação; orientador/admin também atualiza status_validacao
    public function update(Request $request, ArquivoEntrega $arquivoEntrega)
    {
        $arquivoEntrega->load('entrega.tcc');
        $this->autorizarEntrega($arquivoEntrega->entrega);

        $user = Auth::user();

        // Aluno só pode alterar a observação
        if ($user->funcao === 'orientando') {
            $dados = $request->validate([
                'observacao' => ['nullable', 'string'],
            ]);

            $arquivoEntrega->update(['observacao' => $dados['observacao'] ?? null]);

            return redirect()->route('arquivos_entrega.index')
                ->with('sucesso', 'Observação atualizada com sucesso!');
        }

        // Orientador e admin podem alterar status_validacao e observação
        $dados = $request->validate([
            'status_validacao' => ['required', 'in:pendente,validado,rejeitado'],
            'observacao'       => ['nullable', 'string'],
        ]);

        $arquivoEntrega->update($dados);

        // Atualiza status da entrega conforme validação
        $novoStatusEntrega = match ($dados['status_validacao']) {
            'validado'  => 'validado',
            'rejeitado' => 'rejeitado',
            default     => 'entregue',
        };

        $arquivoEntrega->entrega->update(['status' => $novoStatusEntrega]);

        $this->registrarHistorico(
            $arquivoEntrega->entrega,
            'Validação de arquivo atualizada para: ' . $dados['status_validacao']
        );

        return redirect()->route('arquivos_entrega.index')
            ->with('sucesso', 'Arquivo atualizado com sucesso!');
    }

    // Remove o arquivo — somente admin
    public function destroy(ArquivoEntrega $arquivoEntrega)
    {
        if (Auth::user()->funcao !== 'admin') {
            abort(403, 'Somente o administrador pode excluir arquivos.');
        }

        if ($arquivoEntrega->arquivo_path && Storage::disk('public')->exists($arquivoEntrega->arquivo_path)) {
            Storage::disk('public')->delete($arquivoEntrega->arquivo_path);
        }

        $arquivoEntrega->delete();

        return redirect()->route('arquivos_entrega.index')
            ->with('sucesso', 'Registro removido com sucesso!');
    }

    // ── Helpers privados ──────────────────────────────────────────────────────

    private function filtrarTccsPermitidos($query): void
    {
        $user = Auth::user();

        if ($user->funcao === 'admin') {
            return;
        } elseif ($user->funcao === 'orientando' && $user->orientando) {
            $query->whereHas('orientandos', fn ($q) => $q->where('orientandos.id', $user->orientando->id));
        } elseif ($user->funcao === 'orientador' && $user->orientador) {
            $query->where('orientador_id', $user->orientador->id);
        } elseif ($user->funcao === 'membro_banca') {
            $query->whereHas('banca.membros', fn ($q) => $q->where('user_id', $user->id));
        } else {
            $query->whereRaw('1 = 0');
        }
    }

    private function autorizarEntrega(?Entrega $entrega, bool $apenasAluno = false): void
    {
        if (!$entrega || !$entrega->tcc) {
            abort(404);
        }

        $user = Auth::user();

        if ($user->funcao === 'admin') {
            return;
        }

        // Somente aluno pode enviar arquivo (store)
        if ($apenasAluno && $user->funcao !== 'orientando') {
            abort(403, 'Somente o aluno pode enviar arquivo de entrega.');
        }

        if ($user->funcao === 'orientando' && $user->orientando
            && $entrega->tcc->orientandos()->where('orientandos.id', $user->orientando->id)->exists()) {
            return;
        }

        if ($user->funcao === 'orientador' && $user->orientador
            && $entrega->tcc->orientador_id === $user->orientador->id) {
            return;
        }

        if ($user->funcao === 'membro_banca'
            && $entrega->tcc->banca?->membros()->where('user_id', $user->id)->exists()) {
            return;
        }

        abort(403, 'Você não tem permissão para acessar este arquivo.');
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
            'tcc_id'          => $entrega->tcc_id,
            'alterado_por'    => Auth::id(),
            'status_anterior' => $entrega->tcc?->status,
            'status_novo'     => $entrega->tcc?->status,
            'observacao'      => $observacao,
        ]);
    }
}
