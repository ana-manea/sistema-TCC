@extends('layouts.app')

@section('title', 'Reuniões')

@section('content')
@php
    $corStatus = [
        'agendada'  => 'warning text-dark',
        'realizada' => 'success',
        'cancelada' => 'danger',
    ];
    $podeGerenciar = $podeGerenciar ?? (auth()->user() && auth()->user()->funcao !== 'orientando');
@endphp

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h1 class="h3 mb-0">Reuniões</h1>
        <small class="text-muted">Agende, acompanhe e registre reuniões de orientação.</small>
    </div>

    @if($podeGerenciar)
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
                            <th>Data/Hora</th>
                            <th>TCC</th>
                            <th>Orientador</th>
                            <th>Orientando(s)</th>
                            <th>Local</th>
                            <th>Status</th>
                            <th>Próximos passos</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reunioes as $reuniao)
                            @php
                                $dataHora = $reuniao->data_hora
                                    ? \Carbon\Carbon::parse($reuniao->data_hora)->format('d/m/Y H:i')
                                    : '—';
                                $badge = $corStatus[$reuniao->status] ?? 'secondary';
                            @endphp
                            <tr>
                                <td>{{ $dataHora }}</td>
                                <td>{{ $reuniao->tcc?->tema ?? '—' }}</td>
                                <td>{{ $reuniao->tcc?->orientador?->user?->name ?? '—' }}</td>
                                <td>
                                    @if($reuniao->tcc && $reuniao->tcc->orientandos)
                                        @forelse($reuniao->tcc->orientandos as $orientando)
                                            {{ $orientando->user?->name ?? '—' }}@if(!$loop->last), @endif
                                        @empty
                                            —
                                        @endforelse
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $reuniao->local ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-{{ $badge }}">
                                        {{ ucfirst($reuniao->status) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $reuniao->proximos_passos
                                        ? \Illuminate\Support\Str::limit($reuniao->proximos_passos, 40)
                                        : '—' }}
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('reunioes.show', $reuniao) }}">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>

                                    @if($podeGerenciar)
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('reunioes.edit', $reuniao) }}">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>
                                        <form class="d-inline" action="{{ route('reunioes.destroy', $reuniao) }}" method="POST"
                                            onsubmit="return confirm('Excluir esta reunião?')">
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
@endsection
