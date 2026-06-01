<?php

namespace App\Http\Controllers;

use App\Models\Tcc;
use Illuminate\Http\Request;

class TccController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tccs = Tcc::orderBy('created_at', 'desc')->get();
        return view('tccs.index', compact('tccs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tccs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dados = $request->validate([
            'orientador_id'     => ['nullable'],
            'tema'              => ['required', 'min:3', 'max:255'],
            'descricao'         => ['nullable'],
            'status'            => ['required']
        ]);

        Tcc::create($dados);

        return redirect()->route('tccs.index')->with('sucesso', 'Trabalho de Conclusão de Curso registrado!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tcc->load([
            'orientador.user',
            'orientandos.user',
            'banca.membros.user',
            'banca.avaliacoes.avaliador',
            'feedbacks.orientador.user',
        ]);

        return view('tccs.show', compact('tcc'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('tccs.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tcc $trabalho)
    {
        $dados = $request->validate([
            'orientador_id' => ['required'],
            'tema'          => ['required', 'min:3'],
            'descricao'     => ['nullable'],
            'criado_em'     => ['required', 'date'],
            'atualizado_em' => ['required', 'date']
        ]);

        $trabalho->update($dados);

        return redirect()->route('tccs.index')->with('sucesso', 'Trabalho de Conclusão de Curso atualizado!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
