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
        $entregas = Entrega::with('tcc')->latest()->get();

        return view('entregas.index', [
            'entregas' => $entregas,
            'modo'     => 'orientador',
        ]);
    }

    // Lista entregas do orientando logado
    public function indexOrientando()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $orientando = $user->orientando;
        $entregas = Entrega::with('tcc')
            ->whereHas('tcc.orientandos', fn($q) => $q->where('orientandos.id', $orientando->id))
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
        $tccs = Tcc::where('status', 'em_andamento')->get();

        return view('entregas.create', compact('tccs'));
    }

    // Salva a nova entrega
    public function store(Request $request)
    {
        $request->validate([
            'tcc_id'    => 'required|integer|exists:tccs,id',
            'titulo'    => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'prazo'     => 'required|date',
            'status'    => 'required|in:pendente,entregue,validado,rejeitado,atrasado',
        ]);

        Entrega::create($request->all());

        return redirect()->route('entregas.index')
            ->with('sucesso', 'Entrega cadastrada com sucesso!');
    }

    // Exibe a entrega com os arquivos enviados
    public function show(Entrega $entrega)
    {
        $entrega->load('tcc', 'arquivos.enviadoPor');

        return view('entregas.show', compact('entrega'));
    }

    // Abre o formulário de edição
    public function edit(Entrega $entrega)
    {
        $tccs = Tcc::all();

        return view('entregas.edit', compact('entrega', 'tccs'));
    }

    // Atualiza a entrega
    public function update(Request $request, Entrega $entrega)
    {
        $request->validate([
            'tcc_id'    => 'required|integer|exists:tccs,id',
            'titulo'    => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'prazo'     => 'required|date',
            'status'    => 'required|in:pendente,entregue,validado,rejeitado,atrasado',
        ]);

        $entrega->update($request->all());

        return redirect()->route('entregas.index')
            ->with('sucesso', 'Entrega atualizada com sucesso!');
    }

    // Exclui a entrega
    public function destroy(Entrega $entrega)
    {
        $entrega->delete();

        return redirect()->route('entregas.index')
            ->with('sucesso', 'Entrega excluída com sucesso!');
    }
}
