<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliar Banca</title>
</head>
<body>

    <h1>Avaliação da Banca — TCC Nº {{ $banca->tcc_id }}</h1>
    <hr>
    @if ($errors->any())
    @foreach ($errors->all() as $error)
        {{ $error }}<br>
    @endforeach
    @endif
    <form action="{{ route('avaliacoes.store', $banca->id) }}" method="POST">
        @csrf

        <div style="margin-bottom: 15px;">
            <label for="nota" style="display: block; font-weight: bold;">Nota da Avaliação (0 a 10):</label>
            <input type="number" step="0.1" min="0" max="10" name="nota" id="nota" value="{{ old('nota') }}" required>
            @error('nota')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="parecer" style="display: block; font-weight: bold;">Parecer / Justificativa:</label>
            <textarea name="parecer" id="parecer" rows="5" cols="50" placeholder="Digite aqui as considerações e feedbacks..." required>{{ old('parecer') }}</textarea>
            @error('parecer')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <a href="{{ route('bancas.index') }}">Cancelar</a>
            <button type="submit">Salvar Avaliação</button>
        </div>
    </form>

</body>
</html>