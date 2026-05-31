@extends('layouts.app')

@section('title', 'Editar Banca')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">Editar Banca</div>

                <div class="card-body">
                    <form action="{{ route('bancas.update', $banca) }}" method="POST" class="vstack gap-3">
                        @method('PUT')

                        @include('bancas._form', ['banca' => $banca])

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Atualizar
                            </button>

                            <a role="button" href="{{ route('bancas.show', $banca) }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
