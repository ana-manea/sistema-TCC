@extends('layouts.app')

@section('title', 'Editar TCC')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between gap-4 align-items-center mb-3">
        <div class="d-flex gap-4">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('tccs.show', $tcc) }}">
                <i class="bi bi-arrow-left"></i> Voltar ao Trabalho
            </a>
            <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-pencil-square"></i> Editar Trabalho</h1>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-4 p-3 bg-light rounded d-flex align-items-center gap-4">
                    <div>
                        <h2 class="h4 mb-1">{{ $tcc->tema }}</h2>
                        <p class="mb-0 text-muted small">Alterações nesta tela ficam registradas no histórico do trabalho.</p>
                    </div>
                </div>

                <div>
                    <form class="vstack gap-3" action="{{ route('tccs.update', $tcc) }}" method="POST">
                        @method('PUT')
                        @include('tccs._form')

                        <div class="d-flex gap-2 border-top pt-3 mt-3">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-1"></i> Salvar Alterações
                            </button>
                            <a class="btn btn-outline-secondary px-4" href="{{ route('tccs.show', $tcc) }}">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

