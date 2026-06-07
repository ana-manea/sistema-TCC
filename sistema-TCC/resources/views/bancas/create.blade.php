@extends('layouts.app')

@section('title', 'Nova Banca')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    @include('layouts.voltar_titulo', [
        'rota' => 'bancas.index',
        'pagAnterior' => 'às Bancas',
        'pagAtual' => 'Cadastrar Nova Banca Avaliadora'
    ])

</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('bancas.store') }}" method="POST" class="vstack gap-3">
            @include('bancas._form')

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a role="button" href="{{ route('bancas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
