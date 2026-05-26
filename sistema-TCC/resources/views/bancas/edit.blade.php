<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Banca</title>
</head>
<body>

    <h2>Editar Banca Avaliadora</h2>
                    
    <form action="{{ route('bancas.update', $banca->id) }}" method="POST">
        @csrf 
        @method('PUT')

        <div>
            <label for="tcc_id">Cód. TCC:</label>
            <input type="number" name="tcc_id" id="tcc_id" value="{{ $banca->tcc_id }}" required>
        </div>

        <div>
            <label for="data_hora">Data e Hora:</label>
            <input type="datetime-local" name="data_hora" id="data_hora" value="{{ date('Y-m-d\TH:i', strtotime($banca->data_hora)) }}" required>
        </div>

        <div>
            <label for="local">Local:</label>
            <input type="text" name="local" id="local" value="{{ $banca->local }}" required>
        </div>

        <div>
            <label for="status">Status da Banca:</label>
            <select name="status" id="status" required>
                <option value="agendada" {{ $banca->status == 'agendada' ? 'selected' : '' }}>Agendada</option>
                <option value="realizada" {{ $banca->status == 'realizada' ? 'selected' : '' }}>Realizada</option>
                <option value="cancelada" {{ $banca->status == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
        </div>

        <div>
            <a href="{{ route('bancas.index') }}">Cancelar</a>
            <button type="submit">Atualizar Banca</button>
        </div>
    </form>

</body>
</html>