<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ata de Defesa de TCC</title>
</head>
<body>

    <h1>ATA DE DEFESA DE TRABALHO DE CONCLUSÃO DE CURSO</h1>
    <a href="{{ route('bancas.show', $banca) }}">← Voltar para a banca</a>
    <hr>

    <h2>Dados do TCC</h2>
    <p><strong>Tema:</strong> {{ $banca->tcc->tema ?? '—' }}</p>
    <p><strong>Descrição:</strong> {{ $banca->tcc->descricao ?? '—' }}</p>
    <p>
        <strong>Orientador:</strong>
        {{ $banca->tcc->orientador?->user?->name ?? '—' }}
    </p>
    <p>
        <strong>Orientando(s):</strong>
        @forelse($banca->tcc->orientandos as $orientando)
            {{ $orientando->user?->name }}@if(!$loop->last), @endif
        @empty
            —
        @endforelse
    </p>

    <hr>

    <h2>Dados da Banca</h2>
    <p><strong>Data e Hora da Apresentação:</strong>
        {{ \Carbon\Carbon::parse($banca->data_hora)->format('d/m/Y H:i') }}
    </p>
    <p><strong>Local / Link:</strong> {{ $banca->local }}</p>

    <h2>Membros da Banca Avaliadora</h2>
    <ul>
        @foreach($banca->bancaMembros as $membro)
            {{-- Usa user_id (coluna correta do model BancaMembro) --}}
            <li>{{ $membro->user?->name ?? 'ID ' . $membro->usuario_id }} - Papel: {{ ucfirst(str_replace('_', ' ', $membro->papel)) }}</li>
        @endforeach
    </ul>

    <hr>

    <h2>Avaliações Individuais</h2>
    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Avaliador</th>
                <th>Nota</th>
                <th>Parecer</th>
            </tr>
        </thead>
        <tbody>
            {{-- Uma linha por avaliador --}}
            @foreach($banca->avaliacoes->unique('avaliador_id') as $avaliacao)
                <tr>
                    <td>{{ $avaliacao->avaliador?->name ?? '—' }}</td>
                    <td>{{ number_format($avaliacao->nota, 2, ',', '') }}</td>
                    <td>{{ $avaliacao->parecer }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <h2>Resultado e Deliberação Final</h2>
    <p><strong>Nota Final (média):</strong>
        {{ number_format($banca->nota_final, 2, ',', '.') }}
    </p>
    <p>
        <strong>Veredito:</strong>
        @if($banca->resultado_final === 'aprovado')
            <span style="color: green; font-weight: bold;">APROVADO</span>
        @elseif($banca->resultado_final === 'aprovado_com_ressalvas')
            <span style="color: orange; font-weight: bold;">APROVADO COM RESSALVAS</span>
        @else
            <span style="color: red; font-weight: bold;">REPROVADO</span>
        @endif
    </p>

    <h3>Parecer Final / Justificativa da Banca:</h3>
    <p>{{ $banca->parecer_final }}</p>

    <hr>
    <p><em>Documento encerrado e assinado eletronicamente pelo Presidente da Banca.</em></p>

</body>
</html>
