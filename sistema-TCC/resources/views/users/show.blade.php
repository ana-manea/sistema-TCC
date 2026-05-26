@extends('layouts.app')

@section('title', 'Detalhes do Usuário')

@section('content')
    @php
        $nomes = explode(' ', trim($user->name));

        $iniciais = strtoupper(
            substr($nomes[0], 0, 1) .
            (count($nomes) > 1 ? substr(end($nomes), 0, 1) : '')
        );

        $corAvatar = $user->avatar ?? '#b20000';
    @endphp

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">Detalhes do Usuário</div>

                <div class="card-body">
                    <div class="mb-4">
                        <div class="avatar-user" @style(['background-color: ' . $corAvatar])>
                            {{ $iniciais }}
                        </div>
                    </div>

                    <dl class="row">
                        <dt class="col-sm-3">Nome</dt>
                        <dd class="col-sm-9">{{ $user->name }}</dd>

                        <dt class="col-sm-3">E-mail</dt>
                        <dd class="col-sm-9">{{ $user->email }}</dd>

                        <dt class="col-sm-3">Função</dt>
                        <dd class="col-sm-9">{{ $user->funcao }}</dd>

                        <dt class="col-sm-3">Cor do avatar</dt>
                        <dd class="col-sm-9">{{ $corAvatar }}</dd>
                    </dl>

                    <a class="btn btn-outline-secondary" href="{{ route('users.index') }}">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>

                    <a class="btn btn-primary" href="{{ route('users.edit', $user) }}">
                        <i class="bi bi-pencil-square"></i> Editar
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
