<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @if(session('sucesso'))
        <div class="flash">{{ session('sucesso') }}</div>
    @endif
    <h2>Listagem de TCCs</h2>
    <a href="{{ route('tccs.create') }}">Novo Trabalho</a>
    @if($tccs->isEmpty())
        <p>Nenhum tcc cadastrado.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($tccs as $trabalho)
                    <tr>
                        <td>{{ $trabalho->tema }}</td>
                        <td>{{ $trabalho->orientador_id }}</td>
                        <td>{{ $trabalho->descricao }}</td>
                        <td>{{ $trabalho->status }}</td>
                        <td>{{ $trabalho->resultado_final }}</td>
                        <td>{{ $trabalho->nota_final }}</td> <!-- colocar verificação de acordo com status para mostrar isso só depois de ser avaliado -->
                        <td>{{ $trabalho->created_at }}</td>
                        <td>{{ $trabalho->updated_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>