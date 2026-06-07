@extends('layouts.app')

@section('title', 'Nova Avaliação')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    @include('layouts.voltar_titulo', [
        'rota' => 'bancas.show',
        'variavel' => $banca,
        'pagAnterior' => 'à Banca',
        'pagAtual' => 'Avaliação da Banca — TCC Nº ' . $banca->tcc_id
    ])
</div>

<div class="card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form action="{{ route('avaliacoes.store', $banca) }}" method="POST" class="vstack gap-3">
            @include('avaliacoes._form')

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('bancas.show', $banca) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
