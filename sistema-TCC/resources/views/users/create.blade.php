@extends('layouts.app')

@section('title', 'Novo Usuário')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">Cadastrar Usuário</div>

                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST" class="vstack gap-3">
                        @include('users._form')

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Salvar
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
