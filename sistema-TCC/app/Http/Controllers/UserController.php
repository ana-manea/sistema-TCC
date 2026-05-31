<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Orientador;
use App\Models\Orientando;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('users.index', compact('users'));
    }

    public function create(Request $request)
    {
        $funcao = 'users';

        if($request->filled('funcao')) {
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
            'orientando.matricula' => ['nullable','required_if:funcao,orientando','string','max:255','unique:orientandos,matricula'],
            'orientando.curso' => ['nullable','required_if:funcao,orientando','string','max:255'],
            'orientando.semestre' => ['nullable','integer','min:1'],
            // Validações do Orientador
            'orientador.area_atuacao'    => ['nullable','required_if:funcao,orientador', 'string', 'max:255'],
            'orientador.disponibilidade' => ['nullable','required_if:funcao,orientador', 'string'],
            'orientador.max_orientandos' => ['nullable','required_if:funcao,orientador', 'integer', 'min:1', 'max:8'],
        ]);
       
        $dados['password'] = bcrypt($dados['password']);

        $dados['avatar'] = $dados['avatar'] ?? '#b20000';

        $user = User::create($dados);

        // criar orientando
        if ($dados['funcao'] === 'orientando') {
            $orient = $request->input('orientando', []);
            Orientando::create([
                'user_id' => $user->id,
                'orientador_id' => null,
                'matricula' => $orient['matricula'] ?? null,
                'curso' => $orient['curso'] ?? null,
                'semestre' => $orient['semestre'] ?? null,
            ]);
        }

        // criar orientador
        if ($dados['funcao'] === 'orientador') {
            $prof = $request->input('orientador', []);
            Orientador::create([
                'user_id'         => $user->id,
                'area_atuacao'    => $prof['area_atuacao'] ?? null,
                'disponibilidade' => $prof['disponibilidade'] ?? null,
                'max_orientandos' => $prof['max_orientandos'] ?? 8, // Usa o limite máximo padrão se vazio
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
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $dados = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email','max:255', 'unique:users,email,' . $user->id],
            'password' => ['required','min:6'],
            'funcao'   => ['required','in:admin,orientador,orientando,membro_banca'],
            'avatar'   => ['nullable','regex:/^#[0-9A-Fa-f]{6}$/'],

            'orientando.matricula' => ['nullable','required_if:funcao,orientando','string','max:255'],
            'orientando.curso' => ['nullable','required_if:funcao,orientando','string','max:255'],
            'orientando.semestre' => ['nullable','integer','min:1'],

            'orientador.area_atuacao'    => ['nullable','required_if:funcao,orientador', 'string', 'max:255'],
            'orientador.disponibilidade' => ['nullable','required_if:funcao,orientador', 'string'],
            'orientador.max_orientandos' => ['nullable','required_if:funcao,orientador', 'integer', 'min:1', 'max:8'],
        ]);
        
        if (!empty($dados['password'])) {
            $dados['password'] = bcrypt($dados['password']);
        } else {
            unset($dados['password']);
        }

        $dados['avatar'] = $dados['avatar'] ?? '#b20000';

        $user->update($dados);

        // sincronizar orientando
        if ($dados['funcao'] === 'orientando') {
            $orient = $request->input('orientando', []);
            if ($user->orientando) {
                $user->orientando->update([
                    'matricula' => $orient['matricula'] ?? $user->orientando->matricula,
                    'curso' => $orient['curso'] ?? $user->orientando->curso,
                    'semestre' => $orient['semestre'] ?? $user->orientando->semestre,
                ]);
            } else {
                Orientando::create([
                    'user_id' => $user->id,
                    'orientador_id' => null,
                    'matricula' => $orient['matricula'] ?? null,
                    'curso' => $orient['curso'] ?? null,
                    'semestre' => $orient['semestre'] ?? null,
                ]);
            }
        } else {
            // se deixou de ser orientando, remove o registro
            if ($user->orientando) {
                $user->orientando->delete();
            }
        }

        //sincronizar orientador
        if ($dados['funcao'] === 'orientador') {
            $prof = $request->input('orientador', []);
            if ($user->orientador) {
                // Se já existir o perfil, atualiza
                $user->orientador->update([
                    'area_atuacao'    => $prof['area_atuacao'] ?? $user->orientador->area_atuacao,
                    'disponibilidade' => $prof['disponibilidade'] ?? $user->orientador->disponibilidade,
                    'max_orientandos' => $prof['max_orientandos'] ?? $user->orientador->max_orientandos,
                ]);
            } else {
                // Se mudou a função de outro tipo para Orientador, cria o registro do zero
                Orientador::create([
                    'user_id'         => $user->id,
                    'area_atuacao'    => $prof['area_atuacao'] ?? null,
                    'disponibilidade' => $prof['disponibilidade'] ?? null,
                    'max_orientandos' => $prof['max_orientandos'] ?? 8,
                ]);
            }
        } else {
            // Se o usuário deixou de ser orientador, remove o registro antigo do banco
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
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('sucesso', 'Registro removido com sucesso!');
    }
}
