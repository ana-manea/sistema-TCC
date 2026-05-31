<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Banca</title>
</head>
<body>

    <h2>Editar Banca — {{ $banca->tcc->tema ?? 'TCC #' . $banca->tcc_id }}</h2>
    <a href="{{ route('bancas.show', $banca) }}">← Voltar para a banca</a>
    <hr>

    @if($errors->any())
        <ul style="color: red;">
            @foreach($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    @endif

    {{--
        Regra: tcc_id não pode ser alterado após a criação da banca.
        O admin edita apenas data, local e status.
    --}}
    <form action="{{ route('bancas.update', $banca->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold;">TCC vinculado:</label><br>
            <span>{{ $banca->tcc->tema ?? 'TCC #' . $banca->tcc_id }}</span>
            {{-- tcc_id não é editável após criação --}}
        </div>

        <div style="margin-bottom: 15px;">
            <label for="data_hora" style="font-weight: bold;">Data e Hora:</label><br>
            <input type="datetime-local" name="data_hora" id="data_hora"
                   value="{{ \Carbon\Carbon::parse($banca->data_hora)->format('Y-m-d\TH:i') }}" required>
            @error('data_hora')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="local" style="font-weight: bold;">Local ou Link:</label><br>
            <input type="text" name="local" id="local" value="{{ $banca->local }}" required>
            @error('local')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="status" style="font-weight: bold;">Status:</label><br>
            <select name="status" id="status" required>
                <option value="agendada"  {{ $banca->status === 'agendada'  ? 'selected' : '' }}>Agendada</option>
                <option value="realizada" {{ $banca->status === 'realizada' ? 'selected' : '' }}>Realizada</option>
                <option value="cancelada" {{ $banca->status === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
        </div>

        <div>
            <a href="{{ route('bancas.show', $banca) }}">Cancelar</a>
            <button type="submit">Atualizar Banca</button>
        </div>
    </form>

</body>
</html>
