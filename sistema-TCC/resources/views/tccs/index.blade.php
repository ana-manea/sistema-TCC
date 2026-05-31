@extends('layouts.app')

@section('title', 'TCCs')

@section('content')
    <div>
        <h1>Trabalhos de Conclusão de Curso</h1>

        <div>
            <a href="{{ route('tccs.em_andamento') }}">
                <i class="bi bi-hourglass-split"></i> Em andamento
            </a>
            <a href="{{ route('tccs.create') }}">
                <i class="bi bi-plus-circle"></i> Novo TCC
            </a>
        </div>
    </div>

    @if($tccs->isEmpty())
        <div>Nenhum TCC cadastrado.</div>
    @else
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Tema</th>
                        <th>Orientador</th>
                        <th>Orientando(s)</th>
                        <th>Status</th>
                        <th>Resultado</th>
                        <th>Nota</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($tccs as $tcc)
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

                            <td>
                                <span>
                                    {{ ucfirst(str_replace('_', ' ', $tcc->status)) }}
                                </span>
                            </td>

                            <td>
                                @if($tcc->resultado_final)
                                    <span>
                                        {{ ucfirst(str_replace('_', ' ', $tcc->resultado_final)) }}
                                    </span>
                                @else
                                    <span>—</span>
                                @endif
                            </td>

                            <td>
                                {{ $tcc->nota_final ? number_format($tcc->nota_final, 2, ',', '') : '—' }}
                            </td>

                            <td>
                                <a href="{{ route('tccs.show', $tcc) }}">
                                    <i class="bi bi-eye"></i> Ver
                                </a>

                                <a href="{{ route('tccs.historico', $tcc) }}">
                                    <i class="bi bi-clock-history"></i> Histórico
                                </a>

                                <a href="{{ route('tccs.edit', $tcc) }}">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </a>

                                <form action="{{ route('tccs.destroy', $tcc) }}" method="POST"
                                      onsubmit="return confirm('Deseja realmente excluir este TCC?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">
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
@endsection
