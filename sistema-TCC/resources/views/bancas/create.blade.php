@extends('layouts.app')

@section('title', 'Nova Banca')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">Cadastrar Nova Banca Avaliadora</div>

                <div class="card-body">
                    <form action="{{ route('bancas.store') }}" method="POST" class="vstack gap-3">
                        @include('bancas._form')

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Salvar
                            </button>

                            <a role="button" href="{{ route('bancas.index') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
