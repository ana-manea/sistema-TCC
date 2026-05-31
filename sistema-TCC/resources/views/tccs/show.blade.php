@extends('layouts.app')

@section('title', 'Detalhes do TCC')

@section('content')

    {{-- Cabeçalho --}}
    <div>
        <h1>{{ $tcc->tema }}</h1>

        <div>
            <a href="{{ route('tccs.historico', $tcc) }}">
                <i class="bi bi-clock-history"></i> Histórico
            </a>
            <a href="{{ route('tccs.edit', $tcc) }}">
                <i class="bi bi-pencil-square"></i> Editar
            </a>
            <a href="{{ route('tccs.index') }}">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div>

        {{-- Informações gerais --}}
        <div>
            <div>
                <div><i class="bi bi-journal-text"></i> Informações Gerais</div>
                <div>
                    <dl>
                        <dt>Tema</dt>
                        <dd>{{ $tcc->tema }}</dd>

                        <dt>Descrição</dt>
                        <dd>{{ $tcc->descricao ?? '—' }}</dd>

                        <dt>Orientador</dt>
                        <dd>{{ $tcc->orientador?->user?->name ?? '—' }}</dd>

                        <dt>Orientando(s)</dt>
                        <dd>
                            @forelse($tcc->orientandos as $orientando)
                                {{ $orientando->user?->name }}@if(!$loop->last), @endif
                            @empty
                                —
                            @endforelse
                        </dd>

                        <dt>Status</dt>
                        <dd>
                            <span>
                                {{ ucfirst(str_replace('_', ' ', $tcc->status)) }}
                            </span>
                        </dd>

                        <dt>Cadastrado em</dt>
                        <dd>{{ $tcc->created_at->format('d/m/Y') }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Resultado Final (visível a todos) --}}
        <div>
            <div>
                <div><i class="bi bi-award"></i> Resultado Final da Banca</div>
                <div>
                    @if($tcc->banca && $tcc->banca->status === 'realizada')

                        <dl>
                            <dt>Data da apresentação</dt>
                            <dd>
                                {{ \Carbon\Carbon::parse($tcc->banca->data_hora)->format('d/m/Y \à\s H:i') }}
                            </dd>

                            <dt>Local</dt>
                            <dd>{{ $tcc->banca->local }}</dd>

                            <dt>Resultado</dt>
                            <dd>
                                <span>
                                    {{ ucfirst(str_replace('_', ' ', $tcc->banca->resultado_final)) }}
                                </span>
                            </dd>

                            <dt>Nota final</dt>
                            <dd>
                                {{ $tcc->banca->nota_final !== null
                                    ? number_format($tcc->banca->nota_final, 2, ',', '')
                                    : '—' }}
                            </dd>

                            <dt>Parecer da banca</dt>
                            <dd>{{ $tcc->banca->parecer_final ?? '—' }}</dd>
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
                            <i class="bi bi-calendar-event"></i>
                            Banca agendada para
                            {{ \Carbon\Carbon::parse($tcc->banca->data_hora)->format('d/m/Y \à\s H:i') }}.
                            O resultado será exibido após a realização.
                        </div>
                    @else
                        <div>
                            <i class="bi bi-hourglass"></i>
                            Nenhuma banca cadastrada para este TCC.
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
