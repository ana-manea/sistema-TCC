@extends('layouts.app')

@section('title', 'Detalhes do TCC')

@section('content')

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

@endsection
