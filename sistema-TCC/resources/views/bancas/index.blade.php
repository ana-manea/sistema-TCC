<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Bancas</title>
</head>
<body>

    <h1>Bancas Avaliadoras</h1>

    <a href="{{ route('bancas.create') }}">Nova Banca</a>

    @if(session('sucesso'))
        <p>{{ session('sucesso') }}</p>
    @endif

    @if($bancas->isEmpty())
        <p>Nenhuma banca cadastrada até o momento.</p>
    @else
        <table border="1">
            <thead>
                <tr>
                    <th>Cód. TCC</th>
                    <th>Data e Hora</th>
                    <th>Local</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bancas as $banca)
                    <tr>
                        <td>{{ $banca->tcc_id }}</td>
                        <td>{{ \Carbon\Carbon::parse($banca->data_hora)->format('d/m/Y H:i') }}</td>
                        <td>{{ $banca->local }}</td>
                        <td>{{ $banca->status }}</td>
                        <td>
                            <a href="{{ route('bancas.edit', $banca->id) }}">Editar</a>
                            
                            | <a href="{{ route('avaliacoes.criar', $banca->id) }}">Avaliar Banca</a> |
                            
                            <form action="{{ route('bancas.destroy', $banca->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Deseja mesmo excluir esta banca?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>