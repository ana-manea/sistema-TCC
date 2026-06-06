@extends('layouts.app')

@section('title', 'Detalhes do TCC')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    @include('layouts.voltar_titulo', [
        'rota' => 'tccs.index',
        'pagAnterior' => 'aos Trabalhos',
        'pagAtual' => 'Trabalho: ' . $tcc->tema
    ])

    <div>
        <a class="btn btn-outline-secondary" href="{{ route('tccs.historico', $tcc) }}">
            <i class="bi bi-clock-history"></i> Ver Histórico
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
            <dd class="col-sm-8">{{ $tcc->created_at->format('d/m/Y H:i') ?? 'Data não registrada' }}</dd>

            <dt class="col-sm-4">Atualizado em</dt>
            <dd class="col-sm-8">{{ $tcc->updated_at->format('d/m/Y H:i') ?? 'Data não registrada' }}</dd>
        </dl>

        @if($tcc->banca)
            <h5><i class="bi bi-award"></i> Banca</h5>
            <dl class="row mb-0">
                <dt class="col-sm-4">Data e Hora</dt>
                <dd class="col-sm-8">{{ \Carbon\Carbon::parse($tcc->banca->data_hora)->format('d/m/Y H:i') }}</dd>

                <dt class="col-sm-4">Local</dt>
                <dd class="col-sm-8">{{ $tcc->banca->local ?? '—' }}</dd>

                <dt class="col-sm-4">Status da Banca</dt>
                <dd class="col-sm-8">{{ ucfirst($tcc->banca->status) }}</dd>

                <dt class="col-sm-4">Membros</dt>
                <dd class="col-sm-8">
                    @forelse($tcc->banca->membros as $membro)
                        {{ $membro->user?->name }}
                        ({{ ucfirst(str_replace('_', ' ', $membro->papel)) }})@if(!$loop->last), @endif
                    @empty
                        Nenhum membro definido ainda.
                    @endforelse
                </dd>

                <a class="btn btn-outline-secondary" href="{{ route('bancas.show', $tcc->banca) }}">Ver Detalhes da Banca</a>
            </dl>

            {{--
                Resultado Final — só exibido após fechamento da banca.
                Nota e resultado são lidos da banca (fonte de verdade).
            --}}
            @if($tcc->banca->status === 'realizada' && $tcc->banca->resultado_final)
                <h5><i class="bi bi-award"></i> Resultado Final da Banca</h5>
                <dl class="row mb-0">
                    <dt class="col-sm-4">Nota Final</dt>
                    <dd class="col-sm-8">
                        {{ number_format($tcc->banca->nota_final, 2, ',', '') }}
                    </dd>

                    <dt class="col-sm-4">Situação</dt>
                    <dd class="col-sm-8">
                        @if($tcc->banca->resultado_final === 'aprovado')
                            <span style="color: green; font-weight: bold;">✔ Aprovado</span>
                        @elseif($tcc->banca->resultado_final === 'aprovado_com_ressalvas')
                            <span style="color: orange; font-weight: bold;">⚠ Aprovado com Ressalvas</span>
                        @else
                            <span style="color: red; font-weight: bold;">✘ Reprovado</span>
                        @endif
                    </dd>

                    <dt class="col-sm-4">Parecer Final</dt>
                    <dd class="col-sm-8">{{ $tcc->banca->parecer_final ?? '—' }}</dd>

                    <a class="btn btn-outline-secondary" href="{{ route('bancas.ata', $tcc->banca) }}">Ver Ata da Banca</a>
                </dl>
            @endif

        @else
            <div class="alert alert-secondary">
                <i class="bi bi-hourglass"></i>
                Nenhuma banca cadastrada para este TCC.
            </div>
            <a class="btn btn-outline-primary" href="{{ route('bancas.create') }}">
                <i class="bi bi-pencil-square"></i> Agendar Banca
            </a>
        @endif
        {{-- Feedbacks --}}
        <div class="card-header bg-white border-bottom-0 pt-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-bold">
                <i class="bi bi-chat-left-text me-2"></i>Feedbacks de Orientação
            </h5>
            
            @if(Auth::check() && Auth::user()->orientador)
                <a href="{{ route('feedbacks.create', ['tcc_id' => $tcc->id]) }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="bi bi-plus-lg"></i> Novo Feedback
                </a>
            @endif
        </div>
        
        <div class="card-body">
            @forelse($tcc->feedbacks as $feedback)
                <div class="p-3 mb-3 border rounded bg-light">
                    <div class="d-flex justify-content-between align-items-start">
                        <p class="mb-2 text-dark">{{ $feedback->descricao }}</p>
                        
                        @if(Auth::check() && Auth::user()->orientador && Auth::user()->orientador->id === $feedback->orientador_id)
                            <div class="btn-group ms-2">
                                <a href="{{ route('feedbacks.edit', $feedback->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('feedbacks.destroy', $feedback->id) }}" method="POST" onsubmit="return confirm('Excluir este feedback?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    <small class="text-muted d-block">
                        <i class="bi bi-person me-1"></i>{{ $feedback->orientador->user->name }} | 
                        <i class="bi bi-clock me-1"></i>{{ $feedback->created_at->format('d/m/Y H:i') }}
                    </small>
                </div>
            @empty
                <div class="text-center py-4">
                    <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                    <p class="text-muted mt-2">Nenhum feedback registrado para este TCC.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
