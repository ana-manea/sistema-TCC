<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Avaliação</title>
</head>
<body>

    <h1>Editar Avaliação</h1>
    <p>
        <strong>TCC:</strong>
        {{ $avaliacaoBanca->banca->tcc->tema ?? 'TCC #' . $avaliacaoBanca->banca->tcc_id }}
    </p>
    <a href="{{ route('bancas.show', $avaliacaoBanca->banca_id) }}">← Voltar para a banca</a>
    <hr>

    @if($errors->any())
        <ul style="color: red;">
            @foreach($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    @endif

    {{-- Exibe o tempo restante para edição --}}
    @php
        $horasDecorridas  = $avaliacaoBanca->created_at->diffInHours(now());
        $horasRestantes   = max(0, 48 - $horasDecorridas);
        $minutosRestantes = max(0, 48 * 60 - $avaliacaoBanca->created_at->diffInMinutes(now()));
    @endphp

    <p style="color: orange;">
        <strong>Atenção:</strong> Você pode editar esta avaliação por até 48h após o envio.<br>
        Tempo restante: <strong>{{ $horasRestantes }}h {{ $minutosRestantes % 60 }}min</strong>
    </p>

    <form action="{{ route('avaliacoes.update', $avaliacaoBanca->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label for="nota" style="display: block; font-weight: bold;">
                Nota (0 a 10):
            </label>
            <input type="number" step="0.01" min="0" max="10"
                   name="nota" id="nota"
                   value="{{ old('nota', $avaliacaoBanca->nota) }}" required>
            @error('nota')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="parecer" style="display: block; font-weight: bold;">
                Parecer / Considerações:
            </label>
            <textarea name="parecer" id="parecer" rows="6" cols="60"
                      required>{{ old('parecer', $avaliacaoBanca->parecer) }}</textarea>
            @error('parecer')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <a href="{{ route('bancas.show', $avaliacaoBanca->banca_id) }}">Cancelar</a>
        <button type="submit">Salvar Alterações</button>
    </form>

</body>
</html>
