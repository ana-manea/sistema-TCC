<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ata de Defesa de TCC</title>
</head>
<body>

    <h1>ATA DE DEFESA DE TRABALHO DE CONCLUSÃO DE CURSO (TCC)</h1>
    <a href="{{ route('bancas.index') }}">Voltar para a Listagem</a>
    <hr>

    <h2>Dados da Banca</h2>
    <p><strong>Código do TCC:</strong> {{ $banca->tcc_id }}</p>
    <p><strong>Data e Hora da Realização:</strong> {{ \Carbon\Carbon::parse($banca->data_hora)->format('d/m/Y H:i') }}</p>
    <p><strong>Local:</strong> {{ $banca->local }}</p>

    <hr>

    <h2>Membros da Banca Avaliadora</h2>
    <ul>
        @foreach($membros as $membro)
            <li>Usuário ID: {{ $membro->usuario_id }} - Papel: {{ ucfirst($membro->papel) }}</li>
        @endforeach
    </ul>

    <hr>

    <h2>Resultado e Deliberação Final</h2>
    <p>Após a apresentação do trabalho e a avaliação individual dos membros, a banca deliberou o seguinte resultado:</p>
    
    <ul>
        <li><strong>Média Final Consolidada:</strong> {{ number_format($banca->nota_final, 2, ',', '.') }}</li>
        <li><strong>Veredito:</strong> {{ strtoupper($banca->resultado_final) }}</li>
    </ul>

    <h3>Parecer Final / Justificativa da Banca:</h3>
    <p>{{ $banca->parecer_final }}</p>

    <hr>
    <p>*Documento encerrado e assinado eletronicamente pelo Presidente da Banca.*</p>

</body>
</html>