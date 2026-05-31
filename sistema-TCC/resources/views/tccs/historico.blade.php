@extends('layouts.app')

@section('title', 'Histórico do TCC')

@section('content')
    <div>
        <div>
            <h1>Histórico de Alterações</h1>
            <p><strong>Tema:</strong> {{ $tcc->tema }}</p>
        </div>

        <div>
            <a href="{{ route('tccs.show', $tcc) }}">
                <i class="bi bi-arrow-left"></i> Voltar ao TCC
            </a>
        </div>
    </div>

    @if($historicos->isEmpty())
        <div>
            <i class="bi bi-info-circle"></i> Nenhuma alteração de status registrada para este TCC.
        </div>
    @else
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Data da Alteração</th>
                        <th>Alterado por</th>
                        <th>Status Anterior</th>
                        <th>Novo Status</th>
                        <th>Observação</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($historicos as $historico)
                        <tr>
                            <td>{{ $historico->created_at->format('d/m/Y \à\s H:i') }}</td>

                            <td>{{ $historico->alteradoPor?->name ?? '—' }}</td>

                            <td>
                                @if($historico->status_anterior)
                                    <span>
                                        {{ ucfirst(str_replace('_', ' ', $historico->status_anterior)) }}
                                    </span>
                                @else
                                    <span>—</span>
                                @endif
                            </td>

                            <td>
                                <span>
                                    {{ ucfirst(str_replace('_', ' ', $historico->status_novo)) }}
                                </span>
                            </td>

                            <td>{{ $historico->observacao ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection