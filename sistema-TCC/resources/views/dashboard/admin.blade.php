@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
@include('layouts.dashboard_header', ['titulo' => 'Dashboard Admin', 'perfil' => 'Administrador'])

<div class="row dashboard-shell g-4">
    @include('layouts.menu_dashboard', ['usuario' => 'Administrador', 'opcoes' => $opcoes])

    <section class="col-lg-9 col-xl-10">
        <div class="row g-3 mb-4">
            @foreach([
                'Usuários' => $indicadores['usuarios'],
                'Orientadores' => $indicadores['orientadores'],
                'Orientandos' => $indicadores['orientandos'],
                'TCCs' => $indicadores['tccs'],
                'Bancas' => $indicadores['bancas'],
                'Reuniões' => $indicadores['reunioes'],
            ] as $label => $valor)
                <div class="col-sm-6 col-xl-4">
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
            @include('layouts.dashboard_card', ['rota' => route('users.index'), 'icone' => 'bi bi-people', 'titulo' => 'Usuários', 'descricao' => 'Cadastrar, editar, visualizar e filtrar usuários por função.', 'rodape' => 'Admin não altera a própria função.'])
            @include('layouts.dashboard_card', ['rota' => route('tccs.index'), 'icone' => 'bi bi-journal-text', 'titulo' => 'TCCs', 'descricao' => 'Acompanhar projetos, orientadores, orientandos, banca, nota final e histórico.'])
            @include('layouts.dashboard_card', ['rota' => route('bancas.index'), 'icone' => 'bi bi-award', 'titulo' => 'Bancas', 'descricao' => 'Criar, editar, visualizar e acompanhar bancas dos TCCs.', 'rodape' => 'Cada TCC possui no máximo uma banca.'])
            @include('layouts.dashboard_card', ['rota' => route('reunioes.index'), 'icone' => 'bi bi-calendar-event', 'titulo' => 'Reuniões', 'descricao' => 'Agendar, registrar e visualizar reuniões de acompanhamento.'])
            @include('layouts.dashboard_card', ['rota' => route('bancas.index'), 'icone' => 'bi bi-star', 'titulo' => 'Notas dos TCCs', 'descricao' => 'Visualizar notas individuais, média e resultado final calculado.'])
            @include('layouts.dashboard_card', ['rota' => route('tccs.index'), 'icone' => 'bi bi-clock-history', 'titulo' => 'Histórico dos TCCs', 'descricao' => 'Consultar alterações, entregas, arquivos, avaliações e acompanhamento.'])
        </div>
    </section>
</div>
@endsection
