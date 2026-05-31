@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
@php
    $nomes = explode(' ', trim($user->name));

    $iniciais = strtoupper(
        substr($nomes[0], 0, 1) .
        (count($nomes) > 1 ? substr(end($nomes), 0, 1) : '')
    );

    $corAvatar = $user->avatar ?? '#b20000';
@endphp

<div class="card mb-4">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-4">
            <div class="avatar-user" @style(['background-color: ' . $corAvatar])>
                {{ $iniciais }}
            </div>

            <div>
                <h1 class="h3 mb-1">Dashboard Admin</h1>
                <p class="mb-0 text-muted">{{ $user->name }} — {{ $user->email }}</p>
            </div>
        </div>

        <a href="" class="btn btn-primary">
            <i class="bi bi-person-circle"></i> Meu Perfil
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-people"></i> Usuários</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li><a href="{{ route('users.index') }}">Todos os usuários</a></li>
                    <li><a href="{{ route('users.index', ['funcao' => 'admin']) }}">Administradores</a></li>
                    <li><a href="{{ route('users.index', ['funcao' => 'orientador']) }}">Orientadores</a></li>
                    <li><a href="{{ route('users.index', ['funcao' => 'orientando']) }}">Orientandos</a></li>
                    <li><a href="{{ route('users.index', ['funcao' => 'membro_banca']) }}">Membros da banca</a></li>
                </ul>
            </div>
            <div class="card-footer">
                <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">Novo usuário</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-journal-text"></i> TCCs</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li><a href="{{ route('tccs.index') }}">Todos os TCCs</a></li>
                    <li><a href="{{ route('tccs.index', ['status' => 'em_andamento']) }}">TCCs em andamento</a></li>
                    <li><a href="{{ route('tccs.index', ['status' => 'concluido']) }}">TCCs concluídos</a></li>
                </ul>
            </div>
            <div class="card-footer text-muted">Sem cadastro/cadastro vazio</div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-award"></i> Bancas</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li><a href="{{ route('bancas.index', ['status' => 'agendada']) }}">Bancas agendadas</a></li>
                    <li><a href="{{ route('bancas.index', ['status' => 'concluida']) }}">Bancas concluídas</a></li>
                </ul>
            </div>
            <div class="card-footer">
                <a href="{{ route('bancas.create') }}" class="btn btn-sm btn-primary">Definir banca</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-calendar-event"></i> Reuniões</div>
            <div class="card-body">
                <a href="">Ver reuniões</a>
            </div>
            <div class="card-footer">
                <a href="" class="btn btn-sm btn-primary">Agendar reunião</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <a href="" class="text-decoration-none text-dark">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-star"></i> Notas dos TCCs</div>
                <div class="card-body text-muted">Somente visualização</div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-4">
        <a href="" class="text-decoration-none text-dark">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-clock-history"></i> Histórico dos TCCs</div>
                <div class="card-body text-muted">Histórico completo dos projetos</div>
            </div>
        </a>
    </div>
</div>
@endsection