@extends('layouts.app')

@section('title', 'Reuniões')

@section('content')
@php
    $modoAtual = $modo ?? 'admin';

    $corStatus = [
        'agendada'  => 'warning',
        'realizada' => 'success',
        'cancelada' => 'danger',
    ];
@endphp

<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">
            <i class="bi bi-calendar-event"></i> Reuniões de Orientação
        </h1>

        @if($modoAtual !== 'orientando')
            <a class="btn btn-primary" href="{{ route('reunioes.create') }}">
                <i class="bi bi-plus-circle"></i> Nova Reunião
            </a>
        @endif
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($reunioes->isEmpty())
                <div class="alert alert-secondary mb-0">
                    <i class="bi bi-info-circle"></i> Nenhuma reunião cadastrada.
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>TCC</th>
                            <th>Data e Hora</th>
                            <th>Local</th>
                            <th>Status</th>
                            <th>Próximos Passos</th>
                            @if($modoAtual !== 'orientando')
                                <th class="text-end">Ações</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reunioes as $reuniao)
                        <tr>
                            <td class="fw-medium">{{ $reuniao->tcc->tema ?? '—' }}</td>
                            <td>{{ $reuniao->data_hora->format('d/m/Y H:i') }}</td>
                            <td>{{ $reuniao->local ?? '—' }}</td>
                            <td>
                                <span class="badge bg-{{ $corStatus[$reuniao->status] ?? 'secondary' }}">
                                    {{ ucfirst($reuniao->status) }}
                                </span>
                            </td>
                            <td>{{ $reuniao->proximos_passos ? \Str::limit($reuniao->proximos_passos, 50) : '—' }}</td>

                            @if($modoAtual !== 'orientando')
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary"
                                    href="{{ route('reunioes.edit', $reuniao) }}">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </a>

                                <form class="d-inline"
                                    action="{{ route('reunioes.destroy', $reuniao) }}"
                                    method="POST"
                                    onsubmit="return confirm('Deseja excluir esta reunião?')">
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
