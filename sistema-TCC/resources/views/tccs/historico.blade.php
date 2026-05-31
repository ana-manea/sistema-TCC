@extends('layouts.app')

@section('title', 'Histórico do TCC')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">Histórico — {{ $tcc->tema }}</h1>
        <a class="btn btn-outline-secondary" href="{{ route('tccs.show', $tcc) }}">
            <i class="bi bi-arrow-left"></i> Voltar para o TCC
        </a>
    </div>

    @if($historicos->isEmpty())
        <div class="alert alert-secondary">Nenhuma alteração registrada para este TCC.</div>
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
@endsection