<?php

namespace App\Http\Controllers;

use App\Models\Reuniao;
use App\Models\Tcc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReuniaoController extends Controller
{
    // Lista todas as reuniões (admin)
    public function index()
    {
        $user = Auth::user();

        $query = Reuniao::with(['tcc.orientador.user', 'tcc.orientandos.user'])
            ->latest('data_hora');

        if ($user && $user->funcao === 'orientador' && $user->orientador) {
            $query->whereHas('tcc', function ($tccQuery) use ($user) {
                $tccQuery->where('orientador_id', $user->orientador->id);
            });
        } elseif ($user && $user->funcao === 'orientando' && $user->orientando) {
            $tccIds = $user->orientando->tccs()->pluck('tccs.id');
            $query->whereIn('tcc_id', $tccIds);
        }

        $reunioes = $query->get();
        $podeGerenciar = $user && in_array($user->funcao, ['orientador', 'admin'], true);

        return view('reunioes.index', compact('reunioes', 'podeGerenciar'));
    }

    // Lista reuniões do orientador logado
    public function indexOrientador()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $orientador = $user->orientador;

        $reunioes = Reuniao::with('tcc')
            ->whereHas('tcc', fn($q) => $q->where('orientador_id', $orientador->id))
            ->latest('data_hora')
            ->get();

        return view('reunioes.index', [
            'reunioes' => $reunioes,
            'modo'     => 'orientador',
        ]);
    }

    // Lista reuniões do orientando logado
    public function indexOrientando()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $orientando = $user->orientando;

        $reunioes = Reuniao::with('tcc')
            ->whereHas('tcc.orientandos', fn($q) => $q->where('orientandos.id', $orientando->id))
            ->latest('data_hora')
            ->get();

        return view('reunioes.index', [
            'reunioes' => $reunioes,
            'modo'     => 'orientando',
        ]);
    }

    // Abre o formulário de cadastro (agendar reunião)
    public function create()
    {
        $user = Auth::user();

        if (!$user || !in_array($user->funcao, ['orientador', 'admin'], true)) {
            abort(403);
        }

        // Só exibe TCCs em andamento no select
        $tccs = Tcc::where('status', 'em_andamento')
            ->when($user->funcao === 'orientador' && $user->orientador, function ($query) use ($user) {
                $query->where('orientador_id', $user->orientador->id);
            })
            ->with(['orientador.user', 'orientandos.user'])
            ->orderBy('tema')
            ->get();

        return view('reunioes.create', compact('tccs'));
    }

    // Exibe detalhes da reunião
    public function show(Reuniao $reuniao)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        if ($user->funcao === 'orientador' && $user->orientador && $reuniao->tcc?->orientador_id !== $user->orientador->id) {
            abort(403);
        }

        if ($user->funcao === 'orientando' && $user->orientando) {
            $tccIds = $user->orientando->tccs()->pluck('tccs.id');
            if (!$tccIds->contains($reuniao->tcc_id)) {
                abort(403);
            }
        }

        $reuniao->load(['tcc.orientador.user', 'tcc.orientandos.user']);

        $podeGerenciar = in_array($user->funcao, ['orientador', 'admin'], true);

        return view('reunioes.show', compact('reuniao', 'podeGerenciar'));
    }

    // Salva a nova reunião
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->funcao, ['orientador', 'admin'], true)) {
            abort(403);
        }

        $dados = $request->validate([
            'tcc_id'   => 'required|integer|exists:tccs,id',
            'data_hora' => 'required|date',
            'local'    => 'nullable|string|max:255',
            'observacoes'     => 'nullable|string',
            'proximos_passos' => 'nullable|string',
            'status'   => 'required|in:agendada,realizada,cancelada',
        ]);

        Reuniao::create($dados);

        return redirect()->route('reunioes.index')
            ->with('sucesso', 'Reunião cadastrada com sucesso!');
    }

    // Abre o formulário de edição
    public function edit(Reuniao $reuniao)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->funcao, ['orientador', 'admin'], true)) {
            abort(403);
        }

        if ($user->funcao === 'orientador' && $user->orientador && $reuniao->tcc?->orientador_id !== $user->orientador->id) {
            abort(403);
        }

        $tccs = Tcc::with(['orientador.user', 'orientandos.user'])
            ->when($user->funcao === 'orientador' && $user->orientador, function ($query) use ($user) {
                $query->where('orientador_id', $user->orientador->id);
            })
            ->orderBy('tema')
            ->get();

        return view('reunioes.edit', compact('reuniao', 'tccs'));
    }

    // Atualiza a reunião (inclui observações e próximos passos pós-realização)
    public function update(Request $request, Reuniao $reuniao)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->funcao, ['orientador', 'admin'], true)) {
            abort(403);
        }

        if ($user->funcao === 'orientador' && $user->orientador && $reuniao->tcc?->orientador_id !== $user->orientador->id) {
            abort(403);
        }

        $dados = $request->validate([
            'tcc_id'          => 'required|integer|exists:tccs,id',
            'data_hora'       => 'required|date',
            'local'           => 'nullable|string|max:255',
            'observacoes'     => 'nullable|string',
            'proximos_passos' => 'nullable|string',
            'status'          => 'required|in:agendada,realizada,cancelada',
        ]);

        $reuniao->update($dados);

        return redirect()->route('reunioes.index')
            ->with('sucesso', 'Reunião atualizada com sucesso!');
    }

    // Exclui a reunião
    public function destroy(Reuniao $reuniao)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->funcao, ['orientador', 'admin'], true)) {
            abort(403);
        }

        if ($user->funcao === 'orientador' && $user->orientador && $reuniao->tcc?->orientador_id !== $user->orientador->id) {
            abort(403);
        }

        $reuniao->delete();

        return redirect()->route('reunioes.index')
            ->with('sucesso', 'Reunião excluída com sucesso!');
    }
}
