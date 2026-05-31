@extends('layouts.app')

@section('title', 'Editar Tarefa')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="bi bi-pencil-square"></i> Editar Tarefa — {{ $tarefa->titulo }}
                </div>

                <div class="card-body">
                    <form action="{{ route('tarefas.update', $tarefa) }}" method="POST">
                        @method('PUT')
                        @include('tarefas._form')

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-floppy"></i> Salvar alteracoes
                            </button>
                            <a class="btn btn-outline-secondary" href="{{ route('tarefas.index') }}">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
