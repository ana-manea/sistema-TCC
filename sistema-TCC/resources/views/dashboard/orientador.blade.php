@extends('layouts.app')

@section('title', 'Dashboard Orientador')

@section('content')
@include('layouts.dashboard_header', ['titulo' => 'Dashboard Orientador', 'perfil' => 'Orientador'])

<div class="row dashboard-shell g-4">
    @include('layouts.menu_dashboard', ['usuario' => 'Orientador', 'opcoes' => $opcoes])

    <section class="col-lg-9 col-xl-10">
        <div class="row g-3 mb-4">
            @foreach([
                'Orientandos vinculados' => $indicadores['orientandos'],
                'TCCs orientados' => $indicadores['tccs'],
                'Solicitações pendentes' => $indicadores['solicitacoes'],
                'Tarefas abertas' => $indicadores['tarefas'],
            ] as $label => $valor)
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <div class="stat-value">{{ $valor }}</div>
                            <div class="stat-label">{{ $label }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row g-4">
            @include('layouts.dashboard_card', ['rota' => ($orientador ? route('orientador.meus_orientandos', $orientador) : route('dashboard.orientador')), 'icone' => 'bi bi-people', 'titulo' => 'Meus Orientandos', 'descricao' => 'Visualizar alunos vinculados, curso, semestre, título e status do TCC.'])
            @include('layouts.dashboard_card', ['rota' => route('tccs.index'), 'icone' => 'bi bi-journal-text', 'titulo' => 'TCCs Orientados', 'descricao' => 'Acompanhar projetos, entregas, arquivos, banca, nota final e histórico.'])
            @include('layouts.dashboard_card', ['rota' => route('orientador.reunioes.index'), 'icone' => 'bi bi-calendar-event', 'titulo' => 'Reuniões', 'descricao' => 'Agendar, registrar e visualizar reuniões com orientandos.'])
            @include('layouts.dashboard_card', ['rota' => route('tccs.index'), 'icone' => 'bi bi-chat-left-text', 'titulo' => 'Feedbacks', 'descricao' => 'Criar feedbacks e acompanhar mensagens enviadas aos orientandos.'])
            @include('layouts.dashboard_card', ['rota' => route('tarefas.index'), 'icone' => 'bi bi-check2-square', 'titulo' => 'Tarefas', 'descricao' => 'Definir tarefas, prazos e acompanhar status das atividades.'])
            @include('layouts.dashboard_card', ['rota' => ($orientador ? route('solicitacoes_orientador.index', $orientador) : route('dashboard.orientador')), 'icone' => 'bi bi-envelope', 'titulo' => 'Solicitações', 'descricao' => 'Aceitar ou recusar solicitações de orientação conforme vagas disponíveis.'])
        </div>
    </section>
</div>
@endsection
