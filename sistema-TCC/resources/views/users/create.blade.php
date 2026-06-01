@extends('layouts.app')

@section('title', 'Novo Usuário')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">Novo Usuário</div>
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
        </div>
    </div>
@endsection
