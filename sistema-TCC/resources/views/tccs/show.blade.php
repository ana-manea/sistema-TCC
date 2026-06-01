@extends('layouts.app')

@section('title', 'Detalhes do TCC')

@section('content')
<div class="container-fluid py-4">
    @if(session('sucesso'))
        <p style="color: green;"><strong>✔ {{ session('sucesso') }}</strong></p>
    @endif
    @if(session('erro'))
        <p style="color: red;"><strong>✘ {{ session('erro') }}</strong></p>
    @endif

    <div class="d-flex justify-content-between gap-4 align-items-center mb-3">
        <div class="d-flex gap-4">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('tccs.index') }}">
                <i class="bi bi-arrow-left"></i> Ver todos os Trabalhos
            </a>
            <h1 class="h3 mb-0 text-gray-800">Detalhes</h1>
        </div>

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
        </div>
    </div>
</div>
@endsection
