@extends('layouts.app')

@section('title', 'Detalhes da Reunião')

@section('content')
@php
    $dataHora = $reuniao->data_hora
        ? \Carbon\Carbon::parse($reuniao->data_hora)->format('d/m/Y H:i')
        : '—';
    $podeGerenciar = $podeGerenciar ?? (auth()->user() && auth()->user()->funcao !== 'orientando');
@endphp

<div class="d-flex justify-content-between align-items-start mb-3">
    @include('layouts.voltar_titulo', [
        'rota' => 'reunioes.index',
        'pagAnterior' => 'às Reuniões',
        'pagAtual' => 'Detalhes da Reunião'
    ])

    <div>
    @if($podeGerenciar)
            <a href="{{ route('reunioes.edit', $reuniao) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil-square"></i> Editar
            </a>
            <form action="{{ route('reunioes.destroy', $reuniao) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Excluir esta reunião?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> Excluir
                </button>
            </form>
        @endif
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <div class="text-muted small">Data e hora</div>
                    <div class="fw-semibold">{{ $dataHora }}</div>
                </div>

                <div class="mb-3">
                    <div class="text-muted small">Status</div>
                    <div class="fw-semibold">{{ ucfirst($reuniao->status) }}</div>
                </div>

                <div class="mb-3">
                    <div class="text-muted small">Local</div>
                    <div class="fw-semibold">{{ $reuniao->local ?? '—' }}</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <div class="text-muted small">TCC</div>
                    <div class="fw-semibold">{{ $reuniao->tcc?->tema ?? '—' }}</div>
                </div>

                <div class="mb-3">
                    <div class="text-muted small">Orientador</div>
                    <div class="fw-semibold">{{ $reuniao->tcc?->orientador?->user?->name ?? '—' }}</div>
                </div>

                <div class="mb-3">
                    <div class="text-muted small">Orientando(s)</div>
                    <div class="fw-semibold">
                        @if($reuniao->tcc && $reuniao->tcc->orientandos)
                            @forelse($reuniao->tcc->orientandos as $orientando)
                                {{ $orientando->user?->name ?? '—' }}@if(!$loop->last), @endif
                            @empty
                                —
                            @endforelse
                        @else
                            —
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="mb-3">
                    <div class="text-muted small">Observações</div>
                    <div>{{ $reuniao->observacoes ?: '—' }}</div>
                </div>
            </div>

            <div class="col-12">
                <div class="mb-0">
                    <div class="text-muted small">Próximos passos</div>
                    <div>{{ $reuniao->proximos_passos ?: '—' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
