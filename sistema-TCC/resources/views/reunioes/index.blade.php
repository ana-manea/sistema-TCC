@extends('layouts.app')

@section('title', 'Reuniões')

@section('content')
@php
    // Cores dos badges por status
    $corStatus = [
        'agendada'  => 'warning text-dark',
        'realizada' => 'success',
        'cancelada' => 'danger',
    ];
@endphp

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h1 class="h3 mb-0">Reuniões</h1>
        <small class="text-muted">Agende, acompanhe e registre reuniões de orientação.</small>
    </div>

    @if(auth()->user()->funcao !== 'orientando')
        <a href="{{ route('reunioes.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nova Reunião
        </a>
    @endif
</div>

<div class="card">
    <div class="card-body">
        @if($reunioes->isEmpty())
            <div class="alert alert-secondary mb-0">Nenhuma reunião cadastrada.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>TCC</th>
                            <th>Data/Hora</th>
                            <th>Local</th>
                            <th>Status</th>
                            <th>Próximos Passos</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reunioes as $reuniao)
                        <tr>
                            <td>{{ $reuniao->tcc->tema ?? 'TCC #' . $reuniao->tcc_id }}</td>
                            <td>{{ optional($reuniao->data_hora)->format('d/m/Y H:i') }}</td>
                            <td>{{ $reuniao->local ?? '—' }}</td>
                            <td>
                                {{-- Badge com cor por status --}}
                                <span class="badge bg-{{ $corStatus[$reuniao->status] ?? 'secondary' }}">
                                    {{ ucfirst($reuniao->status) }}
                                </span>
                            </td>
                            <td>
                                {{ $reuniao->proximos_passos
                                    ? \Str::limit($reuniao->proximos_passos, 40)
                                    : '—' }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('reunioes.show', $reuniao) }}"
                                    class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i> Ver
                                </a>

                                @if(auth()->user()->funcao !== 'orientando')
                                    <a href="{{ route('reunioes.edit', $reuniao) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>

                                    <form action="{{ route('reunioes.destroy', $reuniao) }}"
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('Excluir esta reunião?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
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
@endsection
