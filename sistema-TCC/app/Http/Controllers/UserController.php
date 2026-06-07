<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Orientador;
use App\Models\Orientando;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function perfil()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return view('users.perfil', compact('user'));
    }

    public function editarPerfil()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return view('users.editar-perfil', compact('user'));
    }

    public function atualizarPerfil(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $dados = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'min:6'],
            'avatar'   => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],

            // Campos específicos do orientador no próprio perfil
            'area_atuacao'    => ['nullable', 'string', 'max:255'],
            'disponibilidade' => ['nullable', 'string'],
            'max_orientandos' => ['nullable', 'integer', 'min:1', 'max:8'],
        ]);

        if (!empty($dados['password'])) {
            $dados['password'] = bcrypt($dados['password']);
        } else {
            unset($dados['password']);
        }

        $dados['avatar'] = $dados['avatar'] ?? $user->avatar ?? '#b20000';

        // A função nunca é alterada pelo próprio usuário no perfil.
        $user->update([
            'name'     => $dados['name'],
            'email'    => $dados['email'],
            'password' => $dados['password'] ?? $user->password,
            'avatar'   => $dados['avatar'],
        ]);

        if ($user->funcao === 'orientador' && $user->orientador) {
            $user->orientador->update([
                'area_atuacao'    => $dados['area_atuacao'] ?? $user->orientador->area_atuacao,
                'disponibilidade' => $dados['disponibilidade'] ?? $user->orientador->disponibilidade,
                'max_orientandos' => $dados['max_orientandos'] ?? $user->orientador->max_orientandos,
            ]);
        }

        return redirect()
            ->route('users.perfil')
            ->with('sucesso', 'Perfil atualizado com sucesso!');
    }

    public function index(Request $request)
    {
        $query = User::latest();

        if ($request->filled('funcao')) {
            $query->where('funcao', $request->input('funcao'));
        }

        $users = $query->get();

        return view('users.index', compact('users'));
    }

    public function create(Request $request)
    {
        $funcao = 'users';

        if ($request->filled('funcao')) {
            $funcao = $request->input('funcao');
        }

        return view('users.create')
            ->with('funcao', $funcao);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email','max:255', 'unique:users,email'],
            'password' => ['required','min:6'],
            'funcao'   => ['required','in:admin,orientador,orientando,membro_banca'],
            'avatar'   => ['nullable','regex:/^#[0-9A-Fa-f]{6}$/'],

            // Validações do Orientando
            'matricula' => ['nullable','required_if:funcao,orientando','string','max:255','unique:orientandos,matricula'],
            'curso'     => ['nullable','required_if:funcao,orientando','string','max:255'],
            'semestre'  => ['nullable','integer','min:1'],

            // Validações do Orientador
            'area_atuacao'    => ['nullable','required_if:funcao,orientador', 'string', 'max:255'],
            'disponibilidade' => ['nullable','required_if:funcao,orientador', 'string'],
            'max_orientandos' => ['nullable','required_if:funcao,orientador', 'integer', 'min:1', 'max:8'],
        ]);

        $user = User::create([
            'name'     => $dados['name'],
            'email'    => $dados['email'],
            'password' => bcrypt($dados['password']),
            'funcao'   => $dados['funcao'],
            'avatar'   => $dados['avatar'] ?? '#b20000',
        ]);

        // Criar orientando
        if ($dados['funcao'] === 'orientando') {
            Orientando::create([
                'user_id'       => $user->id,
                'orientador_id' => null,
                'matricula'     => $dados['matricula'] ?? null,
                'curso'         => $dados['curso'] ?? null,
                'semestre'      => $dados['semestre'] ?? null,
            ]);
        }

        // Criar orientador
        if ($dados['funcao'] === 'orientador') {
            Orientador::create([
                'user_id'         => $user->id,
                'area_atuacao'    => $dados['area_atuacao'] ?? null,
                'disponibilidade' => $dados['disponibilidade'] ?? null,
                'max_orientandos' => $dados['max_orientandos'] ?? 8,
            ]);
        }

        return redirect()
            ->route('users.index')
            ->with('sucesso', 'Registro cadastrado com sucesso!');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        if (
            $authUser->funcao === 'admin'
            && (
                $authUser->id === $user->id
                || $user->funcao === 'admin'
            )
        ) {
            return redirect()
                ->route('users.index')
                ->with('erro', 'Você não pode editar este usuário.');
        }

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        // Admin não pode editar a si próprio nem outro admin.
        if (
            $authUser->funcao === 'admin'
            && (
                $authUser->id === $user->id
                || $user->funcao === 'admin'
            )
        ) {
            return redirect()
                ->route('users.index')
                ->with('erro', 'Você não pode editar este usuário.');
        }

        $dados = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email','max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable','min:6'],
            'funcao'   => ['required','in:admin,orientador,orientando,membro_banca'],
            'avatar'   => ['nullable','regex:/^#[0-9A-Fa-f]{6}$/'],

            'matricula' => ['nullable','required_if:funcao,orientando','string','max:255'],
            'curso'     => ['nullable','required_if:funcao,orientando','string','max:255'],
            'semestre'  => ['nullable','integer','min:1'],

            'area_atuacao'    => ['nullable','required_if:funcao,orientador', 'string', 'max:255'],
            'disponibilidade' => ['nullable','required_if:funcao,orientador', 'string'],
            'max_orientandos' => ['nullable','required_if:funcao,orientador', 'integer', 'min:1', 'max:8'],
        ]);

        $dadosUsuario = [
            'name'   => $dados['name'],
            'email'  => $dados['email'],
            'funcao' => $dados['funcao'],
            'avatar' => $dados['avatar'] ?? '#b20000',
        ];

        if (!empty($dados['password'])) {
            $dadosUsuario['password'] = bcrypt($dados['password']);
        }

        $user->update($dadosUsuario);

        // Sincronizar orientando
        if ($dados['funcao'] === 'orientando') {
            if ($user->orientando) {
                $user->orientando->update([
                    'matricula' => $dados['matricula'] ?? $user->orientando->matricula,
                    'curso'     => $dados['curso'] ?? $user->orientando->curso,
                    'semestre'  => $dados['semestre'] ?? $user->orientando->semestre,
                ]);
            } else {
                Orientando::create([
                    'user_id'       => $user->id,
                    'orientador_id' => null,
                    'matricula'     => $dados['matricula'] ?? null,
                    'curso'         => $dados['curso'] ?? null,
                    'semestre'      => $dados['semestre'] ?? null,
                ]);
            }
        } else {
            // Se deixou de ser orientando, remove o registro
            if ($user->orientando) {
                $user->orientando->delete();
            }
        }

        // Sincronizar orientador
        if ($dados['funcao'] === 'orientador') {
            if ($user->orientador) {
                $user->orientador->update([
                    'area_atuacao'    => $dados['area_atuacao'] ?? $user->orientador->area_atuacao,
                    'disponibilidade' => $dados['disponibilidade'] ?? $user->orientador->disponibilidade,
                    'max_orientandos' => $dados['max_orientandos'] ?? $user->orientador->max_orientandos,
                ]);
            } else {
                Orientador::create([
                    'user_id' => $user->id,
                    'area_atuacao'    => $dados['area_atuacao'] ?? null,
                    'disponibilidade' => $dados['disponibilidade'] ?? null,
                    'max_orientandos' => $dados['max_orientandos'] ?? 8,
                ]);
            }
        } else {
            // Se deixou de ser orientador, remove o registro antigo do banco
            if ($user->orientador) {
                $user->orientador->delete();
            }
        }

        return redirect()
            ->route('users.index')
            ->with('sucesso', 'Registro atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        // Admin não pode excluir a si próprio nem outro admin.
        if (
            $authUser->funcao === 'admin'
            && (
                $authUser->id === $user->id
                || $user->funcao === 'admin'
            )
        ) {
            return redirect()
                ->route('users.index')
                ->with('erro', 'Você não pode excluir este usuário.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('sucesso', 'Registro removido com sucesso!');
    }
}
