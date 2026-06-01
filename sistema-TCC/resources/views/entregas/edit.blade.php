@extends('layouts.app')

@section('title', 'Editar Entrega')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="bi bi-pencil-square"></i> Editar Entrega — {{ $entrega->titulo }}
                </div>
                <div class="card-body">
                    <form action="{{ route('entregas.update', $entrega) }}" method="POST">
                        @method('PUT')
                        @include('entregas._form')

                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-floppy"></i> Atualizar
                            </button>
                            <a class="btn btn-outline-secondary" href="{{ route('entregas.index') }}">
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
