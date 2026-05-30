@extends('layouts.app')

@section('title', 'Dashboard do Orientador')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 px-0 bg-light border-end min-vh-100">
            <div class="p-3">
                <h5 class="text-muted text-uppercase fs-7 fw-bold">Menu do Orientador</h5>
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('orientadores.edit', $orientador->id) }}" class="list-group-item list-group-item-action bg-light py-3">
                    <i class="bi bi-person-circle me-2"></i> Meu Perfil
                </a>
                <a href="{{ route('orientador.meus_orientandos', $orientador->id) }}" class="list-group-item list-group-item-action bg-light py-3">
                    <i class="bi bi-people me-2"></i> Meus Orientandos
                </a>
                <a href="#tccs" class="list-group-item list-group-item-action bg-light py-3">
                    <i class="bi bi-book me-2"></i> TCCs Orientados
                </a>
                <a href="#reunioes" class="list-group-item list-group-item-action bg-light py-3">
                    <i class="bi bi-calendar-event me-2"></i> Reuniões
                </a>
                <a href="#feedbacks" class="list-group-item list-group-item-action bg-light py-3">
                    <i class="bi bi-chat-left-text me-2"></i> Feedbacks
                </a>
                <a href="#tarefas" class="list-group-item list-group-item-action bg-light py-3">
                    <i class="bi bi-check2-square me-2"></i> Tarefas dos Alunos
                </a>
                <a href="{{ route('solicitacoes_orientador.index', $orientador->id) }}" class="list-group-item list-group-item-action bg-light py-3 d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-envelope me-2"></i> Solicitações</span>
                    @if($totalPendentes > 0)
                        <span class="badge bg-danger rounded-pill">{{ $totalPendentes }}</span>
                    @endif
                </a>
            </div>
        </div>

        <div class="col-md-9 col-lg-10 ps-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Painel de Orientação</h1>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card h-100 border-start border-primary border-4 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase fs-7 fw-bold">Orientandos Ativos</h6>
                            {{-- DINÂMICO: Exibe o total real de alunos vinculados --}}
                            <h2 class="fw-bold my-2 text-primary">{{ $orientandosAtivosCount }}</h2>
                            <a href="{{ route('orientador.meus_orientandos', $orientador->id) }}" class="text-decoration-none small text-primary">
                                Gerenciar alunos <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card h-100 border-start border-success border-4 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase fs-7 fw-bold">Projetos de TCC</h6>
                            <h2 class="fw-bold my-2 text-success">3</h2>
                            <a href="#tccs" class="text-decoration-none small text-success">
                                Ver trabalhos <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card h-100 border-start border-warning border-4 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase fs-7 fw-bold">Reuniões Agendadas</h6>
                            <h2 class="fw-bold my-2 text-warning">2</h2>
                            <a href="#reunioes" class="text-decoration-none small text-warning">
                                Ver calendário <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card h-100 border-start border-danger border-4 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase fs-7 fw-bold">Novos Pedidos</h6>
                            {{-- DINÂMICO: Exibe a quantidade de solicitações com status 'pendente' --}}
                            <h2 class="fw-bold my-2 text-danger">{{ $totalPendentes }}</h2>
                            <a href="{{ route('solicitacoes_orientador.index', $orientador->id) }}" class="text-decoration-none small text-danger">
                                Analisar solicitações <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white fw-bold py-3">
                    Avisos Rápidos / Próximas Tarefas
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div>
                                <strong class="text-dark">Entrega de Relatório Parcial</strong><br>
                                <small class="text-muted">Aluno: Carla Rodrigues • Prazo: Amanhã</small>
                            </div>
                            <a href="#tarefas" class="btn btn-sm btn-outline-secondary">Ver Tarefa</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div>
                                <strong class="text-dark">Reunião de Alinhamento (Google Meet)</strong><br>
                                <small class="text-muted">Hoje às 15:00 com o Grupo de Engenharia de Software</small>
                            </div>
                            <span class="badge bg-warning text-dark">Em breve</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection