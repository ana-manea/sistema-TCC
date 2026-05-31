@extends('layouts.app')

@section('title', 'Usuários')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">Usuários</h1>

        <a class="btn btn-primary" href="{{ route('users.create') }}">
            <i class="bi bi-plus-circle"></i> Novo Usuário
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
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
                                    <div class="avatar-user" @style(['background-color: ' . $corAvatar])>
                                {{ $iniciais }}
                            </div>
                                </td>

                                <td class="fw-medium">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->funcao }}</td>

                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('users.show', $user) }}">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>

                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('users.edit', $user) }}">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>

                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Excluir este usuário?');">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-outline-danger" type="submit">
                                            <i class="bi bi-trash"></i> Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        </div>
    </div>
</div>
@endsection
