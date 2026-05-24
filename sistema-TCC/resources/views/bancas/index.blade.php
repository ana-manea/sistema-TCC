<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Bancas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Bancas Avaliadoras</h2>
        <a href="{{ route('bancas.create') }}" class="btn btn-primary">Nova Banca</a>
    </div>

    @if(session('sucesso'))
        <div class="alert alert-success">{{ session('sucesso') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            @if($bancas->isEmpty())
                <p class="text-center text-muted my-4">Nenhuma banca cadastrada até o momento.</p>
            @else
            <table class="table table-striped table-hover vertical-align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Cód. TCC</th> <th>Data e Hora</th>
                        <th>Local</th>
                        <th>Status</th>
                        <th>Nota Final</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bancas as $banca)
                        <tr>
                            <td>{{ $banca->tcc_id }}</td>
                            <td>{{ \Carbon\Carbon::parse($banca->data_hora)->format('d/m/Y H:i') }}</td> <td>{{ $banca->local }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $banca->status }}</span>
                            </td>
                            <td>{{ $banca->nota_final ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('bancas.edit', $banca->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                
                                <form action="{{ route('bancas.destroy', $banca->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Deseja mesmo excluir esta banca?')">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

</body>
</html>