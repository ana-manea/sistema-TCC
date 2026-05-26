<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email','max:255', 'unique:users,email'],
            'password' => ['required','min:6'],
            'funcao'   => ['required','in:admin,orientador,orientando,membro_banca'],
            'avatar'   => ['nullable','regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);
       
        $dados['password'] = bcrypt($dados['password']);

        $dados['avatar'] = $dados['avatar'] ?? '#b20000';

        User::create($dados);

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
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $dados = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email','max:255', 'unique:users,email' . $user->id],
            'password' => ['required','min:6'],
            'funcao'   => ['required','in:admin,orientador,orientando,membro_banca'],
            'avatar'   => ['nullable','regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);
        
        if (!empty($dados['password'])) {
            $dados['password'] = bcrypt($dados['password']);
        } else {
            unset($dados['password']);
        }

        $dados['avatar'] = $dados['avatar'] ?? '#b20000';

        $user->update($dados);

        return redirect()
            ->route('users.index')
            ->with('sucesso', 'Registro atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('sucesso', 'Registro removido com sucesso!');
    }
}
