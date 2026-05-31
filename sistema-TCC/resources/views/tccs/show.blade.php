@extends('layouts.app')

@section('title', 'Detalhes do TCC')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between gap-4 align-items-center mb-3">
        <div class="d-flex gap-4">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('tccs.index') }}">
                <i class="bi bi-arrow-left"></i> Ver todos os Trabalhos
            </a>
            <h1 class="h3 mb-0 text-gray-800">Detalhes</h1>
        </div>

        <div>
            <a class="btn btn-outline-secondary" href="{{ route('tccs.historico', $tcc) }}">
                <i class="bi bi-clock-history"></i> Histórico
            </a>
            <a class="btn btn-outline-primary" href="{{ route('tccs.edit', $tcc) }}">
                <i class="bi bi-pencil-square"></i> Editar
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            {{-- Informações gerais --}}
            <h5><i class="bi bi-journal-text"></i> Informações Gerais</h5>
            <dl class="row mb-0">
                <dt class="col-sm-4">Tema</dt>
                <dd class="col-sm-8">{{ $tcc->tema }}</dd>

                <dt class="col-sm-4">Descrição</dt>
                <dd class="col-sm-8">{{ $tcc->descricao ?? '—' }}</dd>

                <dt class="col-sm-4">Orientador</dt>
                <dd class="col-sm-8">{{ $tcc->orientador?->user?->name ?? '—' }}</dd>

                <dt class="col-sm-4">Orientando(s)</dt>
                <dd class="col-sm-8">
                    @forelse($tcc->orientandos as $orientando)
                        {{ $orientando->user?->name }}@if(!$loop->last), @endif
                    @empty
                        —
                    @endforelse
                </dd>

                <dt class="col-sm-4">Status</dt>
                <dd class="col-sm-8">
                    <span>
                        {{ ucfirst(str_replace('_', ' ', $tcc->status)) }}
                    </span>
                </dd>

                <dt class="col-sm-4">Cadastrado em</dt>
                <dd class="col-sm-8">{{ $tcc->created_at->format('d/m/Y') }}</dd>
            </dl>

            {{-- Resultado Final (visível a todos) --}}
            <h5><i class="bi bi-award"></i> Resultado Final da Banca</h5>
            @if($tcc->banca && $tcc->banca->status === 'realizada')
                <dl class="row mb-0">
                    <dt class="col-sm-4">Data da apresentação</dt>
                    <dd class="col-sm-8">
                        {{ \Carbon\Carbon::parse($tcc->banca->data_hora)->format('d/m/Y \à\s H:i') }}
                    </dd>

                    <dt class="col-sm-4">Local</dt>
                    <dd class="col-sm-8">{{ $tcc->banca->local }}</dd>

                    <dt class="col-sm-4">Resultado</dt>
                    <dd class="col-sm-8">
                        <span>
                            {{ ucfirst(str_replace('_', ' ', $tcc->banca->resultado_final)) }}
                        </span>
                    </dd>

                    <dt class="col-sm-4">Nota final</dt>
                    <dd class="col-sm-8">
                        {{ $tcc->banca->nota_final !== null
                            ? number_format($tcc->banca->nota_final, 2, ',', '')
                            : '—' }}
                    </dd>

                    <dt class="col-sm-4">Parecer da banca</dt>
                    <dd class="col-sm-8">{{ $tcc->banca->parecer_final ?? '—' }}</dd>
                </dl>

                @if($tcc->banca->bancaMembros->isNotEmpty())
                    <hr>
                    <p>Membros da banca:</p>
                    <ul>
                        @foreach($tcc->banca->bancaMembros as $membro)
                            <li>
                                {{ $membro->user?->name ?? 'Usuário #' . $membro->user_id }}
                                <span>({{ ucfirst(str_replace('_', ' ', $membro->papel)) }})</span>
                            </li>
                        @endforeach
                    </ul>
                @endif

            @elseif($tcc->banca)
                <div>
                    <i class="bi bi-calendar-event"></i> Banca agendada para
                    {{ \Carbon\Carbon::parse($tcc->banca->data_hora)->format('d/m/Y \à\s H:i') }}.
                    O resultado será exibido após a realização.
                </div>
            @else
                <div class="alert alert-secondary">
                    <i class="bi bi-hourglass"></i>
                    Nenhuma banca cadastrada para este TCC.
                </div>
            @endif
        </div>
    </div>
@endsection
