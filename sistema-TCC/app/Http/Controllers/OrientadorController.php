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
        if (Auth::user()->funcao !== 'admin') {
            abort(403);
        }

        $users = User::orderBy('name')->get();
        return view('orientadores.create', compact('users'));
    }

    // 4. SALVAR
    public function store(Request $request)
    {
        if (Auth::user()->funcao !== 'admin') {
            abort(403);
        }

        $dados = $request->validate([
            'user_id'         => ['required', 'exists:users,id', 'unique:orientadores,user_id'],
            'area_atuacao'    => ['required', 'string', 'max:255'],
            'disponibilidade' => ['required', 'string'],
            'max_orientandos' => ['required', 'integer', 'min:1', 'max:50'],
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
    public function edit(Orientador $orientador)
    {
        $this->autorizarGerenciar($orientador);

        return view('orientadores.edit', compact('orientador'));
    }

    // 7. ATUALIZAR (Do próprio orientador logado)
    public function update(Request $request, Orientador $orientador)
    {
        $this->autorizarGerenciar($orientador);

        $dados = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'max:255', 'unique:users,email,' . $orientador->user_id],
            'password'        => ['nullable', 'min:6'],
            'avatar'          => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'area_atuacao'    => ['required', 'string', 'max:255'],
            'disponibilidade' => ['required', 'string'],
            'max_orientandos' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        if ($orientador->user) {
            $dadosUsuario = [
                'name'   => $dados['name'],
                'email'  => $dados['email'],
                'avatar' => $dados['avatar'] ?? $orientador->user->avatar,
            ];

            if (!empty($dados['password'])) {
                $dadosUsuario['password'] = bcrypt($dados['password']);
            }

            $orientador->user->update($dadosUsuario);
        }

        $orientador->update([
            'area_atuacao'    => $dados['area_atuacao'],
            'disponibilidade' => $dados['disponibilidade'],
            'max_orientandos' => $dados['max_orientandos'],
        ]);

        return redirect()->route('orientadores.show', $orientador)->with('sucesso', 'Orientador atualizado com sucesso!');
    }

    // 8. MEUS ORIENTANDOS
    public function meusOrientandos(?Orientador $orientador = null)
    {
        $user = Auth::user();
        $orientador = $orientador ?: $user->orientador;

        if (!$orientador) {
            abort(404);
        }

        if ($user->funcao === 'orientador' && (int) $orientador->user_id !== (int) $user->id) {
            abort(403);
        }

        $orientandos = $orientador->orientandos()->with('user')->get();

        return view('orientadores.meus_orientandos', compact('orientador', 'orientandos'));
    }

    // 9. EXCLUIR
    public function destroy(Orientador $orientador)
    {
        if (Auth::user()->funcao !== 'admin') {
            abort(403);
        }

        $orientador->delete();
        return redirect()->route('orientadores.index')->with('sucesso', 'Orientador removido com sucesso!');
    }

    // Limita quem pode alterar as infos do orientador
    private function autorizarGerenciar(Orientador $orientador): void
    {
        $user = Auth::user();

        if ($user->funcao === 'admin') {
            return;
        }

        if ($user->funcao === 'orientador' && (int) $orientador->user_id === (int) $user->id) {
            return;
        }

        abort(403, 'Você não tem permissão para alterar este orientador.');
    }
}