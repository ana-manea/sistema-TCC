@extends('layouts.app')

@section('title', 'Detalhes da Banca')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    @include('layouts.voltar_titulo', [
        'rota' => 'bancas.index',
        'pagAnterior' => 'às Bancas',
        'pagAtual' => 'Detalhes da Banca'
    ])
    <div>
        @if(auth()->user()->funcao === 'admin')
            <a href="{{ route('bancas.edit', $banca) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil-square"></i> Editar
            </a>

            <form action="{{ route('bancas.destroy', $banca) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Excluir esta banca?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> Excluir
                </button>
            </form>
        @endif
    </div>
</div>

    @php
        $euSouPresidente = $banca->membros
            ->where('user_id', auth()->id())
            ->where('papel', 'presidente')
            ->isNotEmpty();

        $euSouMembro = $banca->membros
            ->where('user_id', auth()->id())
            ->isNotEmpty();

        $jaAvaliou = $banca->avaliacoes
            ->where('avaliador_id', auth()->id())
            ->isNotEmpty();
    @endphp

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header">Dados do TCC</div>
                <div class="card-body">
                    <table class="table table-bordered align-middle mb-0">
                        <tr>
                            <th width="30%">Tema</th>
                            <td>{{ $banca->tcc->tema ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Descrição</th>
                            <td>{{ $banca->tcc->descricao ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Orientador</th>
                            <td>{{ $banca->tcc->orientador?->user?->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Orientando(s)</th>
                            <td>
                                @forelse($banca->tcc->orientandos as $orientando)
                                    {{ $orientando->user?->name }}@if(!$loop->last), @endif
                                @empty
                                    —
                                @endforelse
                            </td>
                        </tr>
                        <tr>
                            <th>Status do TCC</th>
                            <td>{{ ucfirst(str_replace('_', ' ', $banca->tcc->status ?? '-')) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header">Dados da Banca</div>
                <div class="card-body">
                    <table class="table table-bordered align-middle mb-0">
                        <tr>
                            <th width="35%">Data e Hora</th>
                            <td>{{ optional($banca->data_hora)->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Local / Link</th>
                            <td>{{ $banca->local ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ ucfirst(str_replace('_', ' ', $banca->status)) }}
                                </span>
                            </td>
                        </tr>
                    </table>

                    <div class="d-flex flex-wrap gap-2 mt-3">
                        @if($banca->status === 'agendada' && $euSouPresidente)
                            <form action="{{ route('bancas.confirmarRealizada', $banca) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success"
                                        onclick="return confirm('Confirmar que a apresentação foi realizada?')">
                                    <i class="bi bi-check-circle"></i> Confirmar realizada
                                </button>
                            </form>
                        @endif

                        @if($banca->status === 'realizada' && $euSouMembro && !$jaAvaliou)
                            <a href="{{ route('avaliacoes.criar', $banca) }}" class="btn btn-primary">
                                <i class="bi bi-star"></i> Lançar nota
                            </a>
                        @endif

                        @if($banca->status === 'realizada' && $euSouPresidente && !$banca->resultado_final)
                            <a href="{{ route('bancas.telaFechamento', $banca) }}" class="btn btn-dark">
                                <i class="bi bi-clipboard-check"></i> Fechar banca
                            </a>
                        @endif

                        @if($banca->status === 'realizada' && $banca->resultado_final)
                            <a href="{{ route('bancas.ata', $banca) }}" class="btn btn-outline-dark">
                                <i class="bi bi-file-earmark-text"></i> Ver ata
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">Membros da Banca</div>
        <div class="card-body">
            @if($banca->membros->isEmpty())
                <div class="alert alert-secondary mb-0">Nenhum membro cadastrado ainda.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Papel</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($banca->membros as $membro)
                                <tr>
                                    <td>{{ $membro->user?->name ?? '—' }}</td>
                                    <td>{{ $membro->user?->email ?? '—' }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $membro->papel)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">Avaliações Individuais</div>
        <div class="card-body">
            @if($banca->avaliacoes->isEmpty())
                <div class="alert alert-secondary mb-0">Nenhuma avaliação registrada ainda.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Avaliador</th>
                                <th>Nota</th>
                                <th>Parecer</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($banca->avaliacoes->unique('avaliador_id') as $avaliacao)
                                @php
                                    $podeEditar = $avaliacao->avaliador_id === auth()->id()
                                        && $avaliacao->created_at
                                        && $avaliacao->created_at->diffInHours(now()) <= 48;
                                @endphp
                                <tr>
                                    <td>{{ $avaliacao->avaliador?->name ?? 'ID ' . $avaliacao->avaliador_id }}</td>
                                    <td>{{ number_format((float) $avaliacao->nota, 2, ',', '.') }}</td>
                                    <td>{{ $avaliacao->parecer ?: '—' }}</td>
                                    <td class="text-end">
                                        @if($podeEditar)
                                            <a href="{{ route('avaliacoes.edit', $avaliacao) }}" class="btn btn-sm btn-outline-primary">
                                                Editar
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @php
                    $mediaAtual = $banca->avaliacoes->unique('avaliador_id')->avg('nota');
                @endphp

                <p class="mb-0">
                    <strong>Média atual:</strong>
                    {{ number_format((float) $mediaAtual, 2, ',', '.') }}
                </p>
            @endif
        </div>
    </div>

    @if($banca->status === 'realizada' && $banca->resultado_final)
        <div class="card mt-3">
            <div class="card-header">Resultado Final</div>
            <div class="card-body">
                <table class="table table-bordered align-middle mb-0">
                    <tr>
                        <th width="30%">Nota Final</th>
                        <td><strong>{{ number_format((float) $banca->nota_final, 2, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <th>Resultado</th>
                        <td>
                            <span class="badge bg-dark">
                                {{ strtoupper(str_replace('_', ' ', $banca->resultado_final)) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Parecer Final</th>
                        <td>{{ $banca->parecer_final }}</td>
                    </tr>
                </table>
            </div>
        </div>
    @endif
@endsection
