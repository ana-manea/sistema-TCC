@extends('layouts.app')

@section('title', 'Entregas')

@section('content')
@php
    $modoAtual = $modo ?? 'orientador';

    $corStatus = [
        'pendente'  => 'secondary',
        'entregue'  => 'primary',
        'validado'  => 'success',
        'rejeitado' => 'danger',
        'atrasado'  => 'warning',
    ];
@endphp

<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">
            <i class="bi bi-box-seam"></i> Controle de Entregas
        </h1>

        @if($modoAtual === 'orientador')
            <a class="btn btn-primary" href="{{ route('entregas.create') }}">
                <i class="bi bi-plus-circle"></i> Nova Entrega
            </a>
        @endif
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($entregas->isEmpty())
                <div class="alert alert-secondary mb-0">
                    <i class="bi bi-info-circle"></i> Nenhuma entrega cadastrada.
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>TCC</th>
                            <th>Título</th>
                            <th>Prazo</th>
                            <th>Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entregas as $entrega)
                        @php
                            $atrasada = $entrega->prazo->isPast()
                                        && !in_array($entrega->status, ['entregue', 'validado']);
                        @endphp
                        <tr class="{{ $atrasada ? 'table-danger' : '' }}">
                            <td class="fw-medium">{{ $entrega->tcc->tema ?? '—' }}</td>
                            <td>{{ $entrega->titulo }}</td>
                            <td>
                                {{ $entrega->prazo->format('d/m/Y') }}
                                @if($atrasada)
                                    <span class="badge bg-danger ms-1">Atrasada</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $corStatus[$entrega->status] ?? 'secondary' }}">
                                    {{ ucfirst($entrega->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-info"
                                    href="{{ route('entregas.show', $entrega) }}">
                                    <i class="bi bi-eye"></i> Ver
                                </a>

                                @if($modoAtual === 'orientador')
                                <a class="btn btn-sm btn-outline-primary"
                                    href="{{ route('entregas.edit', $entrega) }}">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </a>

                                <form class="d-inline"
                                    action="{{ route('entregas.destroy', $entrega) }}"
                                    method="POST"
                                    onsubmit="return confirm('Deseja excluir esta entrega?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
                                </form>
                                @endif
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
