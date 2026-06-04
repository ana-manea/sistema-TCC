@extends('layouts.app')

@section('title', 'Trabalhos em Andamento')

@section('content')
<div class="container-fluid py-4">
    @if(session('sucesso'))
        <p style="color: green;"><strong>{{ session('sucesso') }}</strong></p>
    @endif

    <div class="d-flex justify-content-start gap-4 align-items-center mb-3">
        <a href="{{ route('tccs.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Ver todos os Trabalhos
        </a>
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-hourglass-split me-2"></i>Trabalhos em Andamento
        </h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($tccs->isEmpty())
                <div class="alert alert-secondary">
                    <i class="bi bi-info-circle"></i> Nenhum TCC em andamento no momento.
                </div>
            @else
                <p><strong>Total: {{ $tccs->count() }} TCC(s) em andamento.</strong></p>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Tema</th>
                                <th>Orientador</th>
                                <th>Orientando(s)</th>
                                <th>Tarefas</th>
                                <th>Entregas</th>
                                <th>Reunioes</th>
                                <th>Data de Cadastro</th>
                                <th>Acoes</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($tccs as $tcc)
                                <tr>
                                    <td>{{ $tcc->id }}</td>
                                    <td>{{ $tcc->tema }}</td>
                                    <td>{{ $tcc->orientador?->user?->name ?? '-' }}</td>
                                    <td>
                                        @forelse($tcc->orientandos as $orientando)
                                            {{ $orientando->user?->name }}@if(!$loop->last), @endif
                                        @empty
                                            -
                                        @endforelse
                                    </td>
                                    <td><span class="badge bg-primary">{{ $tcc->tarefas_count }}</span></td>
                                    <td><span class="badge bg-secondary">{{ $tcc->entregas_count }}</span></td>
                                    <td><span class="badge bg-info text-dark">{{ $tcc->reunioes_count }}</span></td>
                                    <td>{{ $tcc->created_at?->format('d/m/Y') ?? 'Nao informada' }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('tccs.show', $tcc) }}">
                                            <i class="bi bi-eye"></i> Ver detalhes
                                        </a>
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('tccs.historico', $tcc) }}">
                                            <i class="bi bi-clock-history"></i> Historico
                                        </a>
                                    </td>
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
