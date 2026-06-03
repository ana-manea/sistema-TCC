@extends('layouts.app')

@section('title', 'Dashboard Orientador')

@section('content')
@php
    $nomes = explode(' ', trim($user->name));
    $iniciais = strtoupper(substr($nomes[0], 0, 1) . (count($nomes) > 1 ? substr(end($nomes), 0, 1) : ''));
    $corAvatar = $user->avatar ?? '#b20000';
    $orientadorId = $user->orientador?->id;
@endphp

<div class="card mb-4">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-4">
            <div class="avatar-user" @style(['background-color: ' . $corAvatar])>{{ $iniciais }}</div>
            <div>
                <h1 class="h3 mb-1">Dashboard Orientador</h1>
                <p class="mb-0 text-muted">{{ $user->name }} — {{ $user->email }}</p>
            </div>
        </div>

        <a href="" class="btn btn-primary">Meu Perfil</a>
    </div>
</div>

<div class="row g-4">

    <div class="col-md-6 col-xl-4">
        <a href="" class="text-decoration-none text-dark">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-people"></i> Meus Orientandos</div>
                <div class="card-body">Perfil de cada aluno e título do TCC.</div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-4">
        <a href="" class="text-decoration-none text-dark">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-journal-text"></i> TCCs Orientados</div>
                <div class="card-body">Projeto por orientando, status, entregas e histórico.</div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-calendar-event"></i> Reuniões</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li><a href="{{ route('reunioes.create') }}">Agendar reunião</a></li>
                    <li><a href="{{ route('reunioes.index') }}">Registrar/ver reuniões</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <a href="" class="text-decoration-none text-dark">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-chat-left-text"></i> Feedbacks</div>
                <div class="card-body">Dar feedback aos orientandos.</div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-4">
        <a href="{{ route('tarefas.index') }}" class="text-decoration-none text-dark">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-check2-square"></i> Tarefas</div>
                <div class="card-body">Definir tarefas para os orientandos.</div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-4">
        @if($orientadorId)
            <a href="{{ route('solicitacoes_orientador.index', $orientadorId) }}" class="text-decoration-none text-dark">
        @else
            <a href="#" class="text-decoration-none text-dark">
        @endif
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-envelope"></i> Solicitações</div>
                <div class="card-body">Aceitar ou recusar solicitações de orientação.</div>
            </div>
        </a>
    </div>

</div>
@endsection