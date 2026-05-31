@extends('layouts.app')

@section('title', 'TCCs')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h1>Trabalhos de Conclusão de Curso</h1>
        <div>
            <a href="{{ route('tccs.em_andamento') }}">Em andamento</a> |
            <a href="{{ route('tccs.create') }}">+ Novo TCC</a>
        </div>
    </div>

    @if(session('sucesso'))
        <p style="color: green;"><strong>✔ {{ session('sucesso') }}</strong></p>
    @endif

    @if($tccs->isEmpty())
        <p>Nenhum TCC cadastrado.</p>
    @else
        <table border="1" cellpadding="8" style="width: 100%; border-collapse: collapse;">
            <thead style="background-color: #f0f0f0;">
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
                            <a href="{{ route('tccs.show', $tcc) }}">Ver</a> |
                            <a href="{{ route('tccs.historico', $tcc) }}">Histórico</a> |
                            <a href="{{ route('tccs.edit', $tcc) }}">Editar</a> |
                            <form action="{{ route('tccs.destroy', $tcc) }}" method="POST" style="display:inline;"
                                  onsubmit="return confirm('Deseja realmente excluir este TCC?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
