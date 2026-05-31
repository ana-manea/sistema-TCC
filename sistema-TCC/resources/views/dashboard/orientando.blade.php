@extends('layouts.app')

@section('title', 'Dashboard Aluno')

@section('content')
@include('layouts.dashboard_header', ['titulo' => 'Dashboard Aluno', 'perfil' => 'Orientando'])

<div class="row dashboard-shell g-4">
    @include('layouts.menu_dashboard', ['usuario' => 'Aluno', 'opcoes' => $opcoes])

    <section class="col-lg-9 col-xl-10">
        <div class="row g-3 mb-4">
            @foreach([
                'TCCs' => $indicadores['tccs'],
                'Tarefas abertas' => $indicadores['tarefas'],
                'Entregas' => $indicadores['entregas'],
                'Reuniões' => $indicadores['reunioes'],
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

        @if($solicitacao)
            <div class="alert alert-info">
                <strong>Última solicitação de orientador:</strong>
                {{ ucfirst($solicitacao->status) }}
                @if($solicitacao->resposta)
                    — {{ $solicitacao->resposta }}
                @endif
            </div>
        @endif

        <div class="row g-4">
            @include('layouts.dashboard_card', ['rota' => route('aluno.tccs.index'), 'icone' => 'bi bi-journal-text', 'titulo' => 'Meu TCC / Projeto', 'descricao' => 'Criar, editar e visualizar dados do projeto, orientador, banca e resultado final.'])
            @include('layouts.dashboard_card', ['rota' => route('aluno.feedbacks.index'), 'icone' => 'bi bi-chat-left-text', 'titulo' => 'Feedbacks', 'descricao' => 'Visualizar feedbacks enviados pelo orientador.'])
            @include('layouts.dashboard_card', ['rota' => route('aluno.tarefas.index'), 'icone' => 'bi bi-check2-square', 'titulo' => 'Tarefas', 'descricao' => 'Acompanhar tarefas atribuídas, prazos e status.'])
            @include('layouts.dashboard_card', ['rota' => route('aluno.reunioes.index'), 'icone' => 'bi bi-calendar-event', 'titulo' => 'Reuniões', 'descricao' => 'Visualizar reuniões agendadas e registros.'])
            @include('layouts.dashboard_card', ['rota' => route('aluno.entregas.index'), 'icone' => 'bi bi-folder', 'titulo' => 'Entregas / Arquivos', 'descricao' => 'Enviar documentos, consultar entregas e versões anteriores.'])
            @include('layouts.dashboard_card', ['rota' => route('aluno.solicitacoes_orientador.index'), 'icone' => 'bi bi-person-plus', 'titulo' => 'Solicitar Orientador', 'descricao' => 'Enviar solicitação para orientador com vagas disponíveis.'])
        </div>
    </section>
</div>
@endsection
