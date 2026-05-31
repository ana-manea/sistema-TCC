@extends('layouts.app')

@section('title', 'Tarefas')

@section('content')
@php
    $modoAtual = $modo ?? 'orientador';
    $badgeMap = [
        'pendente' => 'secondary',
        'em_andamento' => 'primary',
        'concluida' => 'success',
        'atrasada' => 'danger',
    ];
@endphp

<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">Tarefas</h1>

        @if($modoAtual === 'orientador')
            <a class="btn btn-primary" href="{{ route('tarefas.create') }}">
                <i class="bi bi-plus-circle"></i> Nova tarefa
            </a>
        @endif
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
        @if($tarefas->isEmpty())
            <div class="alert alert-secondary">Nenhuma tarefa cadastrada.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Titulo</th>
                            <th>TCC</th>
                            <th>Orientando(s)</th>
                            <th>Prazo</th>
                            <th>Status</th>
                            @if($modoAtual === 'orientador')
                                <th>Acoes</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tarefas as $tarefa)
                            <tr>
                                <td class="fw-medium">{{ $tarefa->titulo }}</td>
                                <td>{{ $tarefa->tcc?->tema ?? '—' }}</td>
                                <td>
                                    @if($tarefa->tcc && $tarefa->tcc->orientandos)
                                        @forelse($tarefa->tcc->orientandos as $orientando)
                                            {{ $orientando->user?->name ?? '—' }}@if(!$loop->last), @endif
                                        @empty
                                            —
                                        @endforelse
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $tarefa->prazo ? $tarefa->prazo->format('d/m/Y') : '—' }}</td>
                                <td>
                                    @php
                                        $badge = $badgeMap[$tarefa->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">
                                        {{ ucfirst(str_replace('_', ' ', $tarefa->status)) }}
                                    </span>
                                </td>
                                @if($modoAtual === 'orientador')
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('tarefas.edit', $tarefa) }}">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>
                                        <form class="d-inline" action="{{ route('tarefas.destroy', $tarefa) }}" method="POST"
                                            onsubmit="return confirm('Deseja excluir esta tarefa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">
                                                <i class="bi bi-trash"></i> Excluir
                                            </button>
                                        </form>
                                    </td>
                                @endif
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
