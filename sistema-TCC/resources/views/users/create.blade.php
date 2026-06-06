@extends('layouts.app')

@section('title', 'Novo Usuário')

@section('content')
    <div class="d-flex flex-column mb-4">
        <div class="d-flex justify-content-between align-items-start">
            @include('layouts.voltar_titulo', [
                'rota' => 'users.index',
                'pagAnterior' => 'aos Usuários',
                'pagAtual' => 'Novo Usuário'
            ])
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST" class="vstack gap-3">

                @include('users._form')
                <div class="d-flex gap-2">
                    <button class="btn btn-primary">Salvar</button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
