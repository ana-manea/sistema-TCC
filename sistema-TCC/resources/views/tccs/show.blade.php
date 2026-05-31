@extends('layouts.app')

@section('title', 'Detalhes do TCC')

@section('content')
<<<<<<< HEAD

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h1>{{ $tcc->tema }}</h1>
        <a href="{{ route('tccs.index') }}">← Voltar para a listagem</a>
    </div>

    @if(session('sucesso'))
        <p style="color: green;"><strong>✔ {{ session('sucesso') }}</strong></p>
    @endif
    @if(session('erro'))
        <p style="color: red;"><strong>✘ {{ session('erro') }}</strong></p>
    @endif

    {{-- Informações do TCC --}}
    <h2>Informações do TCC</h2>
    <table border="1" cellpadding="8" style="border-collapse: collapse;">
        <tr>
            <th style="background:#f0f0f0;">Tema</th>
            <td>{{ $tcc->tema }}</td>
        </tr>
        <tr>
            <th style="background:#f0f0f0;">Descrição</th>
            <td>{{ $tcc->descricao ?? '—' }}</td>
        </tr>
        <tr>
            <th style="background:#f0f0f0;">Orientador</th>
            <td>{{ $tcc->orientador?->user?->name ?? '—' }}</td>
        </tr>
        <tr>
            <th style="background:#f0f0f0;">Orientando(s)</th>
            <td>
                @forelse($tcc->orientandos as $orientando)
                    {{ $orientando->user?->name }}@if(!$loop->last), @endif
                @empty
                    —
                @endforelse
            </td>
        </tr>
        <tr>
            <th style="background:#f0f0f0;">Status</th>
            <td>{{ ucfirst(str_replace('_', ' ', $tcc->status)) }}</td>
        </tr>
        <tr>
            <th style="background:#f0f0f0;">Cadastrado em</th>
            <td>{{ $tcc->created_at?->format('d/m/Y H:i') ?? 'Data não registrada' }}</td>
        </tr>
    </table>

    <br>
    <a href="{{ route('tccs.edit', $tcc) }}">Editar TCC</a> |
    <a href="{{ route('tccs.historico', $tcc) }}">Ver Histórico</a>

    <br><br>

    {{-- Banca vinculada --}}
    @if($tcc->banca)
        <h2>Banca</h2>
        <table border="1" cellpadding="8" style="border-collapse: collapse;">
            <tr>
                <th style="background:#f0f0f0;">Data e Hora</th>
                <td>{{ \Carbon\Carbon::parse($tcc->banca->data_hora)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <th style="background:#f0f0f0;">Local</th>
                <td>{{ $tcc->banca->local ?? '—' }}</td>
            </tr>
            <tr>
                <th style="background:#f0f0f0;">Status da Banca</th>
                <td>{{ ucfirst($tcc->banca->status) }}</td>
            </tr>
            <tr>
                <th style="background:#f0f0f0;">Membros</th>
                <td>
                    @forelse($tcc->banca->bancaMembros as $membro)
                        {{ $membro->user?->name }}
                        ({{ ucfirst(str_replace('_', ' ', $membro->papel)) }})@if(!$loop->last), @endif
                    @empty
                        Nenhum membro definido ainda.
                    @endforelse
                </td>
            </tr>
        </table>

        <br>
        <a href="{{ route('bancas.show', $tcc->banca) }}">Ver Detalhes da Banca →</a>

        {{--
            Resultado Final — só exibido após fechamento da banca.
            Nota e resultado são lidos da banca (fonte de verdade).
        --}}
        @if($tcc->banca->status === 'realizada' && $tcc->banca->resultado_final)
            <br><br>
            <h2>Resultado Final</h2>
            <table border="1" cellpadding="8" style="border-collapse: collapse;">
                <tr>
                    <th style="background:#f0f0f0;">Nota Final</th>
                    <td><strong>{{ number_format($tcc->banca->nota_final, 2, ',', '') }}</strong></td>
                </tr>
                <tr>
                    <th style="background:#f0f0f0;">Situação</th>
                    <td>
                        @if($tcc->banca->resultado_final === 'aprovado')
                            <span style="color: green; font-weight: bold;">✔ Aprovado</span>
                        @elseif($tcc->banca->resultado_final === 'aprovado_com_ressalvas')
                            <span style="color: orange; font-weight: bold;">⚠ Aprovado com Ressalvas</span>
                        @else
                            <span style="color: red; font-weight: bold;">✘ Reprovado</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th style="background:#f0f0f0;">Parecer Final</th>
                    <td>{{ $tcc->banca->parecer_final ?? '—' }}</td>
                </tr>
            </table>

            <br>
            <a href="{{ route('bancas.ata', $tcc->banca) }}">📄 Ver Ata da Banca</a>
        @endif

    @else
        <h2>Banca</h2>
        <p>Nenhuma banca agendada para este TCC.</p>
        <a href="{{ route('bancas.create') }}">+ Agendar Banca</a>
    @endif

    <br><br>
    <a href="{{ route('tccs.index') }}">← Voltar para a listagem</a>

=======
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
>>>>>>> dev
@endsection
