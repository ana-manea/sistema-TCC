<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use App\Models\Tcc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntregaController extends Controller
{
    // Lista todas as entregas (admin / orientador)
    public function index()
    {
        $user = Auth::user();

        $entregas = Entrega::with('tcc')
            ->whereHas('tcc', fn ($q) => $this->filtrarTccsPermitidos($q))
            ->latest()
            ->get();

        return view('entregas.index', [
            'entregas' => $entregas,
            'modo'     => $user->funcao === 'orientando' ? 'orientando' : 'orientador',
        ]);
    }

    // Lista entregas do orientando logado
    public function indexOrientando()
    {
        $user = Auth::user();

        if ($user->funcao !== 'orientando' || !$user->orientando) {
            abort(403, 'Somente orientandos acessam esta listagem.');
        }

        $entregas = Entrega::with('tcc')
            ->whereHas('tcc.orientandos', fn ($q) => $q->where('orientandos.id', $user->orientando->id))
            ->latest()
            ->get();

        return view('entregas.index', [
            'entregas' => $entregas,
            'modo'     => 'orientando',
        ]);
    }

    // Abre o formulário de cadastro
    public function create()
    {
        $user = Auth::user();

        if (!in_array($user->funcao, ['admin', 'orientador'], true)) {
            abort(403, 'Somente administrador ou orientador podem criar entregas.');
        }

        $tccs = Tcc::where('status', 'em_andamento')
            ->where(fn ($q) => $this->filtrarTccsPermitidos($q))
            ->orderBy('tema')
            ->get();

        return view('entregas.create', compact('tccs'));
    }

    // Salva a nova entrega
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!in_array($user->funcao, ['admin', 'orientador'], true)) {
            abort(403, 'Somente admin ou orientador podem criar entregas.');
        }

        $dados = $request->validate([
            'tcc_id'    => 'required|integer|exists:tccs,id',
            'titulo'    => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'prazo'     => 'required|date',
            'status'    => 'required|in:pendente,entregue,validado,rejeitado,atrasado',
        ]);

        $tcc = Tcc::findOrFail($dados['tcc_id']);
        $this->autorizarTcc($tcc);

        Entrega::create($dados);

        return redirect()->route('entregas.index')
            ->with('sucesso', 'Entrega cadastrada com sucesso!');
    }

    // Exibe a entrega com os arquivos enviados
    public function show(Entrega $entrega)
    {
        $entrega->load('tcc', 'arquivos.enviadoPor');
        $this->autorizarTcc($entrega->tcc);

        return view('entregas.show', compact('entrega'));
    }

    // Abre o formulário de edição
    public function edit(Entrega $entrega)
    {
        $user = Auth::user();

        if (!in_array($user->funcao, ['admin', 'orientador'], true)) {
            abort(403, 'Somente admin ou orientador podem editar entregas.');
        }

        $this->autorizarTcc($entrega->tcc);

        $tccs = Tcc::where(fn ($q) => $this->filtrarTccsPermitidos($q))
            ->orderBy('tema')
            ->get();

        return view('entregas.edit', compact('entrega', 'tccs'));
    }

    // Atualiza a entrega
    public function update(Request $request, Entrega $entrega)
    {
        $user = Auth::user();

        if (!in_array($user->funcao, ['admin', 'orientador'], true)) {
            abort(403, 'Somente admin ou orientador podem editar entregas.');
        }

        $dados = $request->validate([
            'tcc_id'    => 'required|integer|exists:tccs,id',
            'titulo'    => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'prazo'     => 'required|date',
            'status'    => 'required|in:pendente,entregue,validado,rejeitado,atrasado',
        ]);

        $tcc = Tcc::findOrFail($dados['tcc_id']);
        $this->autorizarTcc($tcc);

        $entrega->update($dados);

        return redirect()->route('entregas.index')
            ->with('sucesso', 'Entrega atualizada com sucesso!');
    }

    // Exclui a entrega
    public function destroy(Entrega $entrega)
    {
        $user = Auth::user();

        if (!in_array($user->funcao, ['admin', 'orientador'], true)) {
            abort(403, 'Somente admin ou orientador podem excluir entregas.');
        }

        $this->autorizarTcc($entrega->tcc);
        $entrega->delete();

        return redirect()->route('entregas.index')
            ->with('sucesso', 'Entrega excluída com sucesso!');
    }

    // Limita acesso aos TCCs de acordo com o perfil (orientador, orientando, banca)
    private function filtrarTccsPermitidos($query): void
    {
        $user = Auth::user();

        if ($user->funcao === 'orientador' && $user->orientador) {
            $query->where('orientador_id', $user->orientador->id);
        } elseif ($user->funcao === 'orientando' && $user->orientando) {
            $query->whereHas('orientandos', fn ($q) => $q->where('orientandos.id', $user->orientando->id));
        } elseif ($user->funcao === 'membro_banca') {
            $query->whereHas('banca.membros', fn ($q) => $q->where('user_id', $user->id));
        }
    }

    // Limita acesso às entregas dos TCCs de acordo com o perfil
    private function autorizarTcc(?Tcc $tcc): void
    {
        if (!$tcc) {
            abort(404);
        }

        $user = Auth::user();

        if ($user->funcao === 'admin') {
            return;
        }

        if ($user->funcao === 'orientador' && $user->orientador && $tcc->orientador_id === $user->orientador->id) {
            return;
        }

        if ($user->funcao === 'orientando' && $user->orientando && $tcc->orientandos()->where('orientandos.id', $user->orientando->id)->exists()) {
            return;
        }

        if ($user->funcao === 'membro_banca' && $tcc->banca?->membros()->where('user_id', $user->id)->exists()) {
            return;
        }

        abort(403, 'Você não tem permissão para acessar esta entrega.');
    }
}
