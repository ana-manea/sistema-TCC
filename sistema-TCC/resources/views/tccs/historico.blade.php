@extends('layouts.app')

@section('title', 'Histórico do TCC')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    @include('layouts.voltar_titulo', [
        'rota' => 'tccs.index',
        'pagAnterior' => 'aos Trabalhos',
        'pagAtual' => 'Histórico de Alterações'
    ])
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="text-primary border-bottom pb-2 mb-2"><strong>Tema:</strong> {{ $tcc->tema }}</h5>

    @if($historicos->isEmpty())
        <div class="alert alert-secondary">
            <i class="bi bi-info-circle"></i> Nenhuma alteração de status registrada para este TCC.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Data</th>
                        <th>Alterado por</th>
                        <th>Status Anterior</th>
                        <th>Novo Status</th>
                        <th>Observação</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($historicos as $h)
                        @php
                            $badge = [
                                'em_andamento' => 'bg-primary',
                                'concluido'    => 'bg-success',
                                'cancelado'    => 'bg-danger',
                                'suspenso'     => 'bg-warning text-dark',
                            ];
                        @endphp
                        <tr>
                            <td>{{ $h->created_at->format('d/m/Y H:i') }}</td>

                            <td>{{ $h->alteradoPor?->name ?? 'Sistema' }}</td>

                            <td>
                                @if($h->status_anterior)
                                    <span class="badge {{ $badge[$h->status_anterior] ?? 'bg-secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $h->status_anterior)) }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge {{ $badge[$h->status_novo] ?? 'bg-secondary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $h->status_novo)) }}
                                </span>
                            </td>

                            <td>{{ $h->observacao ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    </div>
</div>
@endsection