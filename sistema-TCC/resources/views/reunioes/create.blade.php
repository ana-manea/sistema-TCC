@extends('layouts.app')

@section('title', 'Nova Reunião')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    @include('layouts.voltar_titulo', [
        'rota' => 'reunioes.index',
        'pagAnterior' => 'às Reuniões',
        'pagAtual' => 'Nova Reunião'
    ])
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('reunioes.store') }}" method="POST" class="vstack gap-3">
            @include('reunioes._form')

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a class="btn btn-outline-secondary" href="{{ route('reunioes.index') }}">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
