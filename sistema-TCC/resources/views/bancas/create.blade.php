<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Nova Banca</title>
</head>
<body>

    <h2>Cadastrar Nova Banca Avaliadora</h2>
                    
    <form action="{{ route('bancas.store') }}" method="POST">
        @csrf 

        <div>
            <label for="tcc_id">Cód. TCC:</label>
            <input type="number" name="tcc_id" id="tcc_id" required>
        </div>

        <div>
            <label for="data_hora">Data e Hora:</label>
            <input type="datetime-local" name="data_hora" id="data_hora" required>
        </div>

        <div>
            <label for="local">Local:</label>
            <input type="text" name="local" id="local" placeholder="Ex: Sala 4 ou Link" required>
        </div>

        <div>
            <label for="status">Status Inicial:</label>
            <select name="status" id="status" required>
                <option value="agendada">Agendada</option>
                <option value="realizada">Realizada</option>
                <option value="cancelada">Cancelada</option>
            </select>
        </div>

        <div>
            <a href="{{ route('bancas.index') }}">Cancelar</a>
            <button type="submit">Salvar Banca</button>
        </div>
    </form>

</body>
</html>