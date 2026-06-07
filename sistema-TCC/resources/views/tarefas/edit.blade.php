@extends('layouts.app')

@section('title', 'Editar Tarefa')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    @include('layouts.voltar_titulo', [
        'rota' => 'tarefas.index',
        'pagAnterior' => 'às Tarefas',
        'pagAtual' => 'Editar Tarefa: ' . $tarefa->titulo
    ])

</div>
<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('tarefas.update', $tarefa) }}" method="POST">
            @method('PUT')
            @include('tarefas._form')

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a class="btn btn-outline-secondary" href="{{ route('tarefas.index') }}">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
