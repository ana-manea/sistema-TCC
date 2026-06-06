@extends('layouts.app')

@section('title', 'Usuários')

@section('content')
<div class="d-flex flex-column mb-4">
    <div class="d-flex justify-content-between align-items-start">
        @include('layouts.voltar_titulo', [
            'rota' => 'dashboard',
            'pagAnterior' => 'ao Dashboard',
            'pagAtual' => request('funcao') ? 'Usuários - ' . ucfirst(str_replace('_', ' ', request('funcao'))) : 'Todos os Usuários'
        ])
        
        <a class="btn btn-primary" href="{{ route('users.create') }}">
            <i class="bi bi-plus-circle"></i> Novo Usuário
        </a>
    </div>
    <div class="w-50 align-self-center btn-group btn-group-sm mt-2">
        <a href="{{ route('users.index') }}"
            class="btn {{ !request('funcao') ? 'btn-primary' : 'btn-outline-primary' }}">
            Todos
        </a>

        <a href="{{ route('users.index', ['funcao' => 'admin']) }}"
            class="btn {{ request('funcao') == 'admin' ? 'btn-primary' : 'btn-outline-primary' }}">
            Admin
        </a>

        <a href="{{ route('users.index', ['funcao' => 'orientador']) }}"
            class="btn {{ request('funcao') == 'orientador' ? 'btn-primary' : 'btn-outline-primary' }}">
            Orientador
        </a>

        <a href="{{ route('users.index', ['funcao' => 'orientando']) }}"
            class="btn {{ request('funcao') == 'orientando' ? 'btn-primary' : 'btn-outline-primary' }}">
            Orientando
        </a>

        <a href="{{ route('users.index', ['funcao' => 'membro_banca']) }}"
            class="btn {{ request('funcao') == 'membro_banca' ? 'btn-primary' : 'btn-outline-primary' }}">
            Banca
        </a>
    </div>
</div>

@if($users->isEmpty())
    <div class="alert alert-secondary">Nenhum usuário cadastrado.</div>
@else
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Avatar</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Função</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    @php
                        $nomes = explode(' ', trim($user->name));
                        $iniciais = strtoupper(
                            substr($nomes[0], 0, 1) .
                            (count($nomes) > 1 ? substr(end($nomes), 0, 1) : '')
                        );
                        $corAvatar = $user->avatar ?? '#b20000';
                    @endphp

                    <tr>
                        <td>
                            <div class="avatar-user-sm" @style(['background-color: ' . $corAvatar])>
                                {{ $iniciais }}
                            </div>
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->funcao }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('users.show', $user) }}">
                                Ver
                            </a>

                            @php
                                $authUser = auth()->user();

                                $podeGerenciar = !(
                                    $authUser->funcao === 'admin'
                                    && (
                                        $authUser->id === $user->id
                                        || $user->funcao === 'admin'
                                    )
                                );
                            @endphp

                            @if($podeGerenciar)
                                <a title="Editar" class="btn btn-sm btn-outline-primary" href="{{ route('users.edit', $user) }}">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Excluir usuário?');">
                                    @csrf
                                    @method('DELETE')

                                    <button title="Excluir" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-secondary">
                                    Protegido
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection