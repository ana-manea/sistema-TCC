@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    @include('layouts.voltar_titulo', [
        'rota' => 'users.show', 
        'variavel' => $user,
        'pagAnterior' => 'ao Perfil do Usuário',
        'pagAtual' => 'Editar Usuário: ' . $user->name
    ])
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('users.update', $user) }}" method="POST" class="vstack gap-3">
            @method('PUT')
            @include('users._form')
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
