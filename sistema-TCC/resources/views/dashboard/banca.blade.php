@extends('layouts.app')

@section('title', 'Dashboard Banca')

@section('content')
@include('layouts.dashboard_header', ['titulo' => 'Dashboard Banca', 'perfil' => 'Membro da banca'])

<div class="row dashboard-shell g-4">
    @include('layouts.menu_dashboard', ['usuario' => 'Banca', 'opcoes' => $opcoes])

    <section class="col-lg-9 col-xl-10">
        <div class="row g-3 mb-4">
            @foreach([
                'Bancas vinculadas' => $indicadores['bancas'],
                'Avaliações registradas' => $indicadores['avaliacoes'],
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
            @include('layouts.dashboard_card', ['rota' => route('bancas.index'), 'icone' => 'bi bi-award', 'titulo' => 'Minhas Bancas', 'descricao' => 'Visualizar TCC, orientando, orientador, data, local e papel na banca.'])
            @include('layouts.dashboard_card', ['rota' => route('tccs.index'), 'icone' => 'bi bi-file-earmark-text', 'titulo' => 'TCCs Recebidos', 'descricao' => 'Consultar arquivo final, documentação complementar e histórico de entregas.'])
            @include('layouts.dashboard_card', ['rota' => route('bancas.index'), 'icone' => 'bi bi-clipboard-check', 'titulo' => 'Avaliações', 'descricao' => 'Lançar nota, registrar parecer e acompanhar resultado final.'])
        </div>
    </section>
</div>
@endsection
