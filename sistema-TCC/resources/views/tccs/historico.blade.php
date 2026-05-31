@extends('layouts.app')

@section('title', 'Histórico do TCC')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-start gap-4 align-items-center mb-3">
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('tccs.show', $tcc) }}">
            <i class="bi bi-arrow-left"></i> Voltar ao Trabalho
        </a>
        <h1 class="h3 mb-0 text-gray-800">Histórico de Alterações</h1>
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
                            <th>Data da Alteração</th>
                            <th>Alterado por</th>
                            <th>Status Anterior</th>
                            <th>Novo Status</th>
                            <th>Observação</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($historicos as $historico)
                            <tr>
                                <td>{{ $historico->created_at->format('d/m/Y \à\s H:i') }}</td>

                                <td>{{ $historico->alteradoPor?->name ?? '—' }}</td>

                                <td>
                                    @if($historico->status_anterior)
                                        <span>
                                            {{ ucfirst(str_replace('_', ' ', $historico->status_anterior)) }}
                                        </span>
                                    @else
                                        <span>—</span>
                                    @endif
                                </td>

                                <td>
                                    <span>
                                        {{ ucfirst(str_replace('_', ' ', $historico->status_novo)) }}
                                    </span>
                                </td>

                                <td>{{ $historico->observacao ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        </div>
    </div>
</div>
@endsection