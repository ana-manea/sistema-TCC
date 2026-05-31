@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-start gap-4 align-items-center mb-3">
        <a href="{{ route('users.show', $user) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Voltar ao Usuário
        </a>
        <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-pencil-square me-2"></i> Editar Usuário</h1>
    </div>
    <div class="row justify-content-center">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('users.update', $user) }}" method="POST" class="vstack gap-3">
                    @csrf
                    @method('PUT')

                    @include('users._form', ['user' => $user])

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Atualizar
                        </button>

                        <a class="btn btn-outline-secondary" href="{{ route('users.index') }}">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
