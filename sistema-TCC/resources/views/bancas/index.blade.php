@extends('layouts.app')

@section('title', 'Bancas')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h3 mb-0">Bancas Avaliadoras</h1>
            <small class="text-muted">Gerencie bancas, avaliações, fechamento e atas.</small>
        </div>

        <a class="btn btn-primary" href="{{ route('bancas.create') }}">
            <i class="bi bi-plus-circle"></i> Nova Banca
        </a>
    </div>

    @if(session('sucesso'))
        <div class="alert alert-success">{{ session('sucesso') }}</div>
    @endif

    @if(session('erro'))
        <div class="alert alert-danger">{{ session('erro') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($bancas->isEmpty())
                <div class="alert alert-secondary mb-0">Nenhuma banca cadastrada até o momento.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>TCC</th>
                                <th>Data e Hora</th>
                                <th>Local</th>
                                <th>Status</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bancas as $banca)
                                @php
                                    $membroLogado = $banca->membros->firstWhere('user_id', auth()->id());
                                    $eOPresidente = $membroLogado && $membroLogado->papel === 'presidente';
                                    $funcaoUsuario = auth()->user()->funcao ?? null;
                                @endphp

                                <tr>
                                    <td>
                                        <strong>{{ $banca->tcc->tema ?? 'TCC #' . $banca->tcc_id }}</strong>
                                        <br>
                                        <small class="text-muted">Código: {{ $banca->tcc_id }}</small>
                                    </td>
                                    <td>{{ optional($banca->data_hora)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $banca->local ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ ucfirst(str_replace('_', ' ', $banca->status)) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('bancas.show', $banca) }}">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>

                                        @if($funcaoUsuario === 'admin')
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('bancas.edit', $banca) }}">
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </a>
                                        @endif

                                        @if($banca->status === 'realizada')
                                            <a class="btn btn-sm btn-outline-success" href="{{ route('avaliacoes.criar', $banca) }}">
                                                <i class="bi bi-star"></i> Avaliar
                                            </a>
                                        @endif

                                        @if($eOPresidente && $banca->status === 'agendada')
                                            <form action="{{ route('bancas.confirmarRealizada', $banca) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                        onclick="return confirm('Confirmar que a apresentação foi realizada?')">
                                                    <i class="bi bi-check-circle"></i> Confirmar
                                                </button>
                                            </form>
                                        @endif

                                        @if($eOPresidente && $banca->status === 'realizada' && !$banca->resultado_final)
                                            <a class="btn btn-sm btn-outline-dark" href="{{ route('bancas.telaFechamento', $banca) }}">
                                                <i class="bi bi-clipboard-check"></i> Fechar
                                            </a>
                                        @endif

                                        @if($banca->status === 'realizada' && $banca->resultado_final)
                                            <a class="btn btn-sm btn-outline-dark" href="{{ route('bancas.ata', $banca) }}">
                                                <i class="bi bi-file-earmark-text"></i> Ata
                                            </a>
                                        @endif

                                        @if($funcaoUsuario === 'admin')
                                            <form action="{{ route('bancas.destroy', $banca) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Deseja mesmo excluir esta banca?');">
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
