<?php

namespace App\Http\Controllers;

use App\Models\Reuniao;
use App\Models\Tcc;
use Illuminate\Http\Request;

class ReuniaoController extends Controller
{
    // Lista todas as reuniões
    public function index()
    {
        $reunioes = Reuniao::with('tcc')->latest('data_hora')->get();

        return view('reunioes.index', compact('reunioes'));
    }

    // Abre o formulário de cadastro
    public function create()
    {
        // Só exibe TCCs em andamento no select
        $tccs = Tcc::where('status', 'em_andamento')->get();

        return view('reunioes.create', compact('tccs'));
    }

    // Salva a nova reunião
    public function store(Request $request)
    {
        $request->validate([
            'tcc_id'   => 'required|integer|exists:tccs,id',
            'data_hora' => 'required|date',
            'local'    => 'nullable|string|max:255',
            'status'   => 'required|in:agendada,realizada,cancelada',
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

    // Atualiza a reunião (inclui observações e próximos passos pós-realização)
    public function update(Request $request, Reuniao $reuniao)
    {
        $request->validate([
            'tcc_id'          => 'required|integer|exists:tccs,id',
            'data_hora'        => 'required|date',
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