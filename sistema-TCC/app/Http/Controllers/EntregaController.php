<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use Illuminate\Http\Request;

class EntregaController extends Controller
{
    public function index()
    {
        $entregas = Entrega::latest()->get();

        return view('entregas.index', compact('entregas'));
    }

    public function create()
    {
        return view('entregas.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'tcc_id'    => ['nullable|integer'],
            'titulo'    => ['required|string|max:255'],
            'descricao' => ['nullable|string'],
            'prazo'     => ['nullable|date'],
            'status'    => ['nullable|string'],
        ]);

        Entrega::create($dados);

        return redirect()
            ->route('entregas.index')
            ->with('sucesso', 'Registro cadastrado com sucesso!');
    }

    public function show(Entrega $entrega)
    {
        return view('entregas.show', compact('entrega'));
    }

    public function edit(Entrega $entrega)
    {
        return view('entregas.edit', compact('entrega'));
    }

    public function update(Request $request, Entrega $entrega)
    {
        $dados = $request->validate([
            'tcc_id'    => ['nullable|integer'],
            'titulo'    => ['required|string|max:255'],
            'descricao' => ['nullable|string'],
            'prazo'     => ['nullable|date'],
            'status'    => ['nullable|string'],
        ]);

        $entrega->update($dados);

        return redirect()
            ->route('entregas.index')
            ->with('sucesso', 'Registro atualizado com sucesso!');
    }

    public function destroy(Entrega $entrega)
    {
        $entrega->delete();

        return redirect()
            ->route('entregas.index')
            ->with('sucesso', 'Registro removido com sucesso!');
    }
}
