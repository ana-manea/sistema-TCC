<?php

namespace App\Http\Controllers;

use App\Models\Orientador;
use App\Models\SolicitacaoOrientador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrientadorController extends Controller
{
    // 2. LISTAR (Para administradores ou listagem pública)
    public function index()
    {
        $orientadores = Orientador::with('user')->withCount('orientandos')->latest()->get();
        return view('orientadores.index', compact('orientadores'));
    }

    // 3. FORMULÁRIO DE CRIAÇÃO
    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('orientadores.create', compact('users'));
    }

    // 4. SALVAR
    public function store(Request $request)
    {
        $dados = $request->validate([
            'user_id'         => ['required', 'exists:users,id', 'unique:orientadores,user_id'],
            'area_atuacao'    => ['required', 'string', 'max:255'],
            'disponibilidade' => ['required', 'string'],
            'max_orientandos' => ['required', 'integer', 'min:1', 'max:8'],
        ]);

        Orientador::create($dados);

        return redirect()->route('orientadores.index')->with('sucesso', 'Orientador cadastrado com sucesso!');
    }

    // 5. EXIBIR DETALHES
    public function show(Orientador $orientador)
    {
        $orientador->load('user');
        return view('orientadores.show', compact('orientador'));
    }

    // 6. FORMULÁRIO DE EDIÇÃO (Do próprio orientador logado)
    public function edit()
    {
        $orientador = Orientador::where('user_id', Auth::id())->firstOrFail();
        return view('orientadores.edit', compact('orientador'));
    }

    // 7. ATUALIZAR (Do próprio orientador logado)
    public function update(Request $request)
    {
        $orientador = Orientador::where('user_id', Auth::id())->firstOrFail();

        $dados = $request->validate([
            'area_atuacao'    => ['required', 'string', 'max:255'],
            'disponibilidade' => ['required', 'string'],
            'max_orientandos' => ['required', 'integer', 'min:1', 'max:8'],
        ]);

        $orientador->update($dados);

        return redirect()->route('orientador.dashboard')->with('sucesso', 'Perfil atualizado com sucesso!');
    }

    // 8. MEUS ORIENTANDOS
    public function meusOrientandos()
    {
        $orientador = Orientador::where('user_id', Auth::id())->firstOrFail();
        $orientandos = $orientador->orientandos()->with('user')->get();
        
        return view('orientadores.meus_orientandos', compact('orientador', 'orientandos'));
    }

    // 9. EXCLUIR
    public function destroy(Orientador $orientador)
    {
        $orientador->delete();
        return redirect()->route('orientadores.index')->with('sucesso', 'Orientador removido com sucesso!');
    }
}