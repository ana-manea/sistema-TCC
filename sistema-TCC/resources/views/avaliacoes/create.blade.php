<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lançar Avaliação</title>
</head>
<body>

    <h1>Lançar Nota e Parecer</h1>
    <p>
        <strong>TCC:</strong> {{ $banca->tcc->tema ?? 'TCC #' . $banca->tcc_id }}<br>
        <strong>Orientando(s):</strong>
        @forelse($banca->tcc->orientandos as $orientando)
            {{ $orientando->user?->name }}@if(!$loop->last), @endif
        @empty
            —
        @endforelse
    </p>
    <a href="{{ route('bancas.show', $banca->id) }}">← Voltar para a banca</a>
    <hr>

    @if($errors->any())
        <ul style="color: red;">
            @foreach($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    @endif

    @if(session('erro'))
        <p style="color: red;"><strong>{{ session('erro') }}</strong></p>
    @endif

    <form action="{{ route('avaliacoes.store', $banca->id) }}" method="POST">
        @csrf

        <div style="margin-bottom: 15px;">
            <label for="nota" style="display: block; font-weight: bold;">
                Nota (0 a 10):
            </label>
            <input type="number" step="0.01" min="0" max="10"
                   name="nota" id="nota"
                   value="{{ old('nota') }}" required>
            @error('nota')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="parecer" style="display: block; font-weight: bold;">
                Parecer / Considerações:
            </label>
            <textarea name="parecer" id="parecer" rows="6" cols="60"
                      placeholder="Descreva suas considerações sobre o trabalho..."
                      required>{{ old('parecer') }}</textarea>
            @error('parecer')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <a href="{{ route('bancas.show', $banca->id) }}">Cancelar</a>
        <button type="submit">Salvar Avaliação</button>
    </form>

</body>
</html>
