@extends('layouts.app')

@section('title', 'Novo TCC')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    @include('layouts.voltar_titulo', [
        'rota' => 'tccs.index',
        'pagAnterior' => 'aos Trabalhos',
        'pagAtual' => 'Novo Trabalho de Conclusão de Curso'
    ])
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('tccs.store') }}" method="POST">
            @include('tccs._form')

            <div class="d-flex gap-2">
                <button class="btn btn-primary">Salvar</button>
                <a class="btn btn-outline-secondary" href="{{ route('tccs.index') }}">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
