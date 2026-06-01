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
        $reunioes = Reuniao::with('tcc')->latest('data_hora')->get();

        return view('reunioes.index', [
            'reunioes' => $reunioes,
            'modo'     => 'admin',
        ]);
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

    // Abre o formulário de cadastro
    public function create()
    {
        $tccs = Tcc::where('status', 'em_andamento')->get();

        return view('reunioes.create', compact('tccs'));
    }

    // Salva a nova reunião
    public function store(Request $request)
    {
        $request->validate([
            'tcc_id'    => 'required|integer|exists:tccs,id',
            'data_hora' => 'required|date',
            'local'     => 'nullable|string|max:255',
            'status'    => 'required|in:agendada,realizada,cancelada',
        ]);

        Reuniao::create($request->all());

        return redirect()->route('reunioes.index')
            ->with('sucesso', 'Reunião cadastrada com sucesso!');
    }

    // Abre o formulário de edição
    public function edit(Reuniao $reuniao)
    {
        $tccs = Tcc::all();

        return view('reunioes.edit', compact('reuniao', 'tccs'));
    }

    // Atualiza a reunião
    public function update(Request $request, Reuniao $reuniao)
    {
        $request->validate([
            'tcc_id'          => 'required|integer|exists:tccs,id',
            'data_hora'       => 'required|date',
            'local'           => 'nullable|string|max:255',
            'observacoes'     => 'nullable|string',
            'proximos_passos' => 'nullable|string',
            'status'          => 'required|in:agendada,realizada,cancelada',
        ]);

        $reuniao->update($request->all());

        return redirect()->route('reunioes.index')
            ->with('sucesso', 'Reunião atualizada com sucesso!');
    }

    // Exclui a reunião
    public function destroy(Reuniao $reuniao)
    {
        $reuniao->delete();

        return redirect()->route('reunioes.index')
            ->with('sucesso', 'Reunião excluída com sucesso!');
    }
}
