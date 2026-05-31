@extends('layouts.app')

@section('title', 'Novo Trabalho')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">Cadastrar Trabalho</div>
                
                <div class="card-body">
                    <form action="{{ route('tccs.store') }}" method="POST">
                        @include('tccs._form')

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Salvar
                            </button>
                            <a role="button" class="btn btn-outline-secondary" href="{{ route('tccs.index') }}">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
