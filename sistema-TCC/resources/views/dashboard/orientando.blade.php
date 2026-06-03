@extends('layouts.app')

@section('title', 'Dashboard Aluno')

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
                <h1 class="h3 mb-1">Dashboard Aluno</h1>
                <p class="mb-0 text-muted">{{ $user->name }} — {{ $user->email }}</p>
            </div>
        </div>

        <a href="{{ route('users.perfil') }}" class="btn btn-primary">
            <i class="bi bi-person-circle"></i> Meu Perfil
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- Meu TCC / Projeto --}}
    <div class="col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-journal-text"></i> Meu TCC / Projeto
            </div>

            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <a href="{{ route('tccs.index') }}">
                            Ver meu TCC
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('tccs.create') }}">
                            Criar meu TCC
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-footer text-muted">
                Dados do projeto, orientador, status, banca atribuída, nota final e considerações.
            </div>
        </div>
    </div>

    {{-- Feedbacks --}}
    <div class="col-md-6 col-xl-4">
        <a href="{{ route('dashboard.orientando') }}" class="text-decoration-none text-dark">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-chat-left-text"></i> Feedbacks
                </div>

                <div class="card-body text-muted">
                    Ver feedbacks enviados pelo orientador.
                </div>
            </div>
        </a>
    </div>

    {{-- Tarefas --}}
    <div class="col-md-6 col-xl-4">
        <a href="{{ route('aluno.tarefas.index') }}" class="text-decoration-none text-dark">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-check2-square"></i> Tarefas
                </div>

                <div class="card-body text-muted">
                    Ver tarefas definidas pelo orientador.
                </div>
            </div>
        </a>
    </div>

    {{-- Reuniões --}}
    <div class="col-md-6 col-xl-4">
        <a href="{{ route('reunioes.index') }}" class="text-decoration-none text-dark">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-calendar-event"></i> Reuniões
                </div>

                <div class="card-body text-muted">
                    Ver reuniões agendadas.
                </div>
            </div>
        </a>
    </div>

    {{-- Entregas / Arquivos --}}
    <div class="col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-folder"></i> Entregas / Arquivos
            </div>

            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <a href="{{ route('dashboard.orientando') }}">
                            Ver entregas
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="{{ route('dashboard.orientando') }}">
                            Enviar documento
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('dashboard.orientando') }}">
                            Ver arquivos e versões anteriores
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Solicitar Orientador --}}
    <div class="col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-person-plus"></i> Solicitar Orientador
            </div>

            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <a href="{{ route('solicitacoes_orientando.create') }}">
                            Nova solicitação
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('solicitacoes_orientando.index') }}">
                            Ver minhas solicitações
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Status da Solicitação --}}
    <div class="col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-person-check"></i> Status da Solicitação
            </div>

            <div class="card-body">
                @if($solicitacao)
                    <p class="mb-2">
                        <strong>Status:</strong>

                        @if($solicitacao->status === 'pendente')
                            <span class="badge bg-warning text-dark">Pendente</span>
                        @elseif($solicitacao->status === 'aceita')
                            <span class="badge bg-success">Aceita</span>
                        @else
                            <span class="badge bg-danger">Recusada</span>
                        @endif
                    </p>

                    @if($solicitacao->resposta)
                        <p class="mb-0">
                            <strong>Resposta:</strong>
                            {{ $solicitacao->resposta }}
                        </p>
                    @endif
                @else
                    <p class="text-muted mb-0">
                        Nenhuma solicitação enviada.
                    </p>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection