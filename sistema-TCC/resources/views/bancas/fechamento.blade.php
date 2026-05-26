<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fechamento da Banca</title>
</head>
<body>

    <h1>Ata de Conclusão da Banca - TCC Cód. {{ $banca->tcc_id }}</h1>
    <a href="{{ route('bancas.index') }}">Voltar para a Listagem de Bancas</a>
    <hr>

    @if (session('erro'))
        <p><strong>Erro: {{ session('erro') }}</strong></p>
    @endif

    <h3>Resumo das Avaliações dos Membros</h3>
    <ul>
        <li>Total de avaliadores que pontuaram: {{ $notas->count() }}</li>
        <li>Média Aritmética Atual: <strong>{{ number_format($mediaCalculada, 2, ',', '.') }}</strong></li>
    </ul>

    <hr>

    <h3>Veredito Final da Banca</h3>
    <p>Esta ação irá alterar o status da banca para "realizada" e salvar a nota final do aluno permanentemente.</p>

    <form action="{{ route('bancas.fechar', $banca->id) }}" method="POST">
        @csrf
        
        <div>
            <label for="resultado_final">Resultado Final / Veredito:</label><br>
            <select name="resultado_final" id="resultado_final" required>
                <option value="">-- Selecione a Decisão --</option>
                <option value="aprovado">Aprovado</option>
                <option value="aprovado_com_ressalvas">Aprovado com Ressalvas</option>
                <option value="reprovado">Reprovado</option>
            </select>
        </div>

        <br>

        <div>
            <label for="parecer_final">Parecer Final (Texto da Ata de Conclusão):</label><br>
            <textarea name="parecer_final" id="parecer_final" rows="8" cols="65" placeholder="Escreva aqui o resumo das considerações e a justificativa do veredito da banca..." required></textarea>
        </div>

        <br>

        <button type="submit">
            Confirmar e Assinar Ata de Fechamento
        </button>
    </form>

</body>
</html>