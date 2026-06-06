@extends('layouts.app')

@section('title', 'Editar TCC')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    @include('layouts.voltar_titulo', [
        'rota' => 'tccs.index',
        'pagAnterior' => 'aos Trabalhos',
        'pagAtual' => 'Editar Trabalho de Conclusão de Curso'
    ])
</div>
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
                    <button type="submit" class="btn btn-primary px-4">Salvar</button>
                    <a class="btn btn-outline-secondary px-4" href="{{ route('tccs.show', $tcc) }}">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

