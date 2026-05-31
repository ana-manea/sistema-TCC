<?php

namespace App\Http\Controllers;

use App\Models\Tcc;
use App\Models\HistoricoTcc;
use App\Models\Orientador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TccController extends Controller
{
    /**
     * Lista todos os TCCs cadastrados.
     */
    public function index()
    {
        $tccs = Tcc::with(['orientador.user', 'orientandos.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tccs.index', compact('tccs'));
    }
    /**
     * Exibe o formulário de cadastro de um novo TCC.
     */
    public function create()
    {
        $orientadores = Orientador::with('user')->get();

        return view('tccs.create', compact('orientadores'));
    }
    /**
     * Salva um novo TCC no banco e registra o histórico inicial.
     */
    public function store(Request $request)
    {
        $dados = $request->validate([
            'orientador_id' => ['nullable', 'exists:orientadores,id'],
            'tema'          => ['required', 'min:3', 'max:255'],
            'descricao'     => ['nullable', 'string'],
            'status'        => ['required', 'in:em_andamento,concluido,cancelado,suspenso'],
        ]);

        $tcc = Tcc::create($dados);

        // Registra a criação no histórico
        HistoricoTcc::create([
            'tcc_id'          => $tcc->id,
            'alterado_por'    => Auth::id(),
            'status_anterior' => null,
            'status_novo'     => $tcc->status,
            'observacao'      => 'TCC criado.',
        ]);

        return redirect()
            ->route('tccs.index')
            ->with('sucesso', 'Trabalho de Conclusão de Curso registrado!');
    }

    /**
     * Exibe o formulário de edição de um TCC existente.
     */
    public function edit(Tcc $tcc)
    {
        $orientadores = Orientador::with('user')->get();

        return view('tccs.edit', compact('tcc', 'orientadores'));
    }

    /**
     * Remove um TCC do sistema.
     */
    public function destroy(Tcc $tcc)
    {
        $tcc->delete();

        return redirect()
            ->route('tccs.index')
            ->with('sucesso', 'TCC removido com sucesso!');
    }

  
}