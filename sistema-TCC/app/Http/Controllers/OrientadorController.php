<?php

namespace App\Http\Controllers;

use App\Models\Orientador;
use App\Models\SolicitacaoOrientador;
use App\Models\User;
use Illuminate\Http\Request;

class OrientadorController extends Controller
{
    // 1. DASHBOARD: Agora recebe o orientador pela URL
    public function dashboard(Orientador $orientador)
    {
        // Contagem de orientandos ativos
        $orientandosAtivosCount = $orientador->orientandos()->count();

        // Contagem de solicitações pendentes para este orientador específico
        $totalPendentes = SolicitacaoOrientador::where('orientador_id', $orientador->id)
            ->where('status', 'pendente')
            ->count();

        return view('orientadores.dashboard', compact('orientador', 'orientandosAtivosCount', 'totalPendentes'));
    }

    // 2. LISTAR
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

    // 6. FORMULÁRIO DE EDIÇÃO
    public function edit(Orientador $orientador)
    {
        $orientador->load('user');
        return view('orientadores.edit', compact('orientador'));
    }

    // 7. ATUALIZAR
    public function update(Request $request, Orientador $orientador)
    {
        $dados = $request->validate([
            'area_atuacao'    => ['required', 'string', 'max:255'],
            'disponibilidade' => ['required', 'string'],
            'max_orientandos' => ['required', 'integer', 'min:1', 'max:8'],
        ]);

        $orientador->update($dados);

        return redirect()->route('orientador.dashboard', $orientador->id)->with('sucesso', 'Perfil atualizado com sucesso!');
    }

    // 8. MEUS ORIENTANDOS: Agora recebe o orientador pela URL
    public function meusOrientandos(Orientador $orientador)
    {
        $orientandos = $orientador->orientandos()->with('user')->get();
        return view('orientadores.meus_orientandos',compact('orientador', 'orientandos'));
    }

    // 9. EXCLUIR
    public function destroy(Orientador $orientador)
    {
        $orientador->delete();
        return redirect()->route('orientadores.index')->with('sucesso', 'Orientador removido com sucesso!');
    }
}