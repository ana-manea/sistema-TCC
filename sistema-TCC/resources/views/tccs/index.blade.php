@extends('layouts.app')

@section('title', 'Trabalhos')

@section('content')
<div class="container-fluid py-4">
    @if(session('sucesso'))
        <p style="color: green;"><strong>✔ {{ session('sucesso') }}</strong></p>
    @endif

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">Trabalhos de Conclusão de Curso</h1>

        <div>
            <a class="btn btn-outline-secondary" href="{{ route('tccs.em_andamento') }}">
                <i class="bi bi-hourglass-split"></i> Em andamento
            </a>
            <a class="btn btn-primary" href="{{ route('tccs.create') }}">
                <i class="bi bi-plus-circle"></i> Novo Trabalho
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
        @if($tccs->isEmpty())
            <div class="alert alert-secondary">Nenhum TCC cadastrado.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tema</th>
                            <th>Orientador</th>
                            <th>Orientando(s)</th>
                            <th>Status</th>
                            <th>Resultado</th>
                            <th>Nota Final</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($tccs as $tcc)
                            @php
                                /*
                                    Resultado e nota vêm da banca (fonte de verdade),
                                    não das colunas diretas do TCC.
                                    A sincronização ocorre no fechamento da banca (fecharBanca).
                                */
                                $notaFinal     = $tcc->banca?->nota_final;
                                $resultadoFinal = $tcc->banca?->resultado_final;
                            @endphp
                            <tr>
                                <td>{{ $tcc->tema }}</td>
                                <td>{{ $tcc->orientador?->user?->name ?? '—' }}</td>
                                <td>
                                    @forelse($tcc->orientandos as $orientando)
                                        {{ $orientando->user?->name }}@if(!$loop->last), @endif
                                    @empty
                                        —
                                    @endforelse
                                </td>
                                <td>{{ ucfirst(str_replace('_', ' ', $tcc->status)) }}</td>
                                <td>
                                    @if($resultadoFinal === 'aprovado')
                                        <span style="color: green;">✔ Aprovado</span>
                                    @elseif($resultadoFinal === 'aprovado_com_ressalvas')
                                        <span style="color: orange;">⚠ Aprovado c/ Ressalvas</span>
                                    @elseif($resultadoFinal === 'reprovado')
                                        <span style="color: red;">✘ Reprovado</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    {{ $notaFinal !== null ? number_format($notaFinal, 2, ',', '') : '—' }}
                                </td>
                                <td>
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('tccs.show', $tcc) }}">
                                    <i class="bi bi-eye"></i> Ver
                                </a>

                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('tccs.historico', $tcc) }}">
                                    <i class="bi bi-clock-history"></i> Histórico
                                </a>

                                <a class="btn btn-sm btn-outline-primary" href="{{ route('tccs.edit', $tcc) }}">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </a>

                                <form class="d-inline" action="{{ route('tccs.destroy', $tcc) }}" method="POST"
                                    onsubmit="return confirm('Deseja realmente excluir este TCC?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
                                </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        </div>
    </div>
</div>
@endsection
