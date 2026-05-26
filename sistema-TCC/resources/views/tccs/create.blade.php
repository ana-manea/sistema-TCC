<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h2>Criar TCC</h2>
    
    <form action="{{ route('tccs.store') }}" method="POST">
        @include('tccs._form')
        <button type="submit">Salvar</button>
        <a href="{{ route('tccs.index') }}">Cancelar</a>
    </form>
</body>
</html>