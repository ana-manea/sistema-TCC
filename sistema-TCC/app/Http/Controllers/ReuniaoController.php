<?php

namespace App\Http\Controllers;

use App\Models\Reuniao;
use App\Models\Tcc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReuniaoController extends Controller
{
    // Lista todas as reuniões
    public function index()
    {
        $reunioes = Reuniao::with('tcc')->latest('data_hora')->get();

        return view('reunioes.index', compact('reunioes'));
    }

    // Lista reuniões dos TCCs orientados pelo orientador logado
    public function indexOrientador()
    {
        $user = Auth::user();
        $orientador = $user->orientador;

        $reunioes = Reuniao::with('tcc')
            ->when($orientador, function ($query) use ($orientador) {
                $query->whereHas('tcc', function ($q) use ($orientador) {
                    $q->where('orientador_id', $orientador->id);
                });
            })
            ->latest('data_hora')
            ->get();

        return view('reunioes.index', compact('reunioes'));
    }

    // Lista reuniões dos TCCs do orientando logado
    public function indexOrientando()
    {
        $user = Auth::user();
        $orientando = $user->orientando;

        $tccIds = $orientando
            ? $orientando->tccs()->pluck('tccs.id')
            : collect();

        $reunioes = Reuniao::with('tcc')
            ->when($tccIds->isNotEmpty(), function ($query) use ($tccIds) {
                $query->whereIn('tcc_id', $tccIds);
            })
            ->when($tccIds->isEmpty(), function ($query) {
                $query->whereRaw('1 = 0');
            })
            ->latest('data_hora')
            ->get();

        return view('reunioes.index', compact('reunioes'));
    }

    // Abre o formulário de cadastro
    public function create()
    {
        // Só exibe TCCs em andamento no select
        $tccs = Tcc::where('status', 'em_andamento')->orderBy('tema')->get();

        return view('reunioes.create', compact('tccs'));
    }

    // Salva a nova reunião
    public function store(Request $request)
    {
        $dados = $request->validate([
            'tcc_id'          => 'required|integer|exists:tccs,id',
            'data_hora'       => 'required|date',
            'local'           => 'nullable|string|max:255',
            'observacoes'     => 'nullable|string',
            'proximos_passos' => 'nullable|string',
            'status'          => 'required|in:agendada,realizada,cancelada',
        ]);

        Reuniao::create($dados);

        return redirect()->route('reunioes.index')
            ->with('sucesso', 'Reunião cadastrada com sucesso!');
    }

    // Exibe detalhes da reunião
    public function show(Reuniao $reuniao)
    {
        $reuniao->load('tcc');

        return view('reunioes.show', compact('reuniao'));
    }

    // Abre o formulário de edição
    public function edit(Reuniao $reuniao)
    {
        $tccs = Tcc::orderBy('tema')->get();

        return view('reunioes.edit', compact('reuniao', 'tccs'));
    }

    // Atualiza a reunião
    public function update(Request $request, Reuniao $reuniao)
    {
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
        $reuniao->delete();

        return redirect()->route('reunioes.index')
            ->with('sucesso', 'Reunião excluída com sucesso!');
    }
}
