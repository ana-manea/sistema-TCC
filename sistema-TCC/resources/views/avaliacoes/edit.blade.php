@extends('layouts.app')

@section('title', 'Editar Avaliação')

@section('content')
@php
    $banca = $avaliacaoBanca->banca;
    $horasDecorridas = $avaliacaoBanca->created_at->diffInHours(now());
    $horasRestantes = max(0, 48 - $horasDecorridas);
    $minutosRestantes = max(0, 48 * 60 - $avaliacaoBanca->created_at->diffInMinutes(now()));
@endphp

<div class="d-flex justify-content-between align-items-start mb-3">
    @include('layouts.voltar_titulo', [
        'rota' => 'bancas.show',
        'variavel' => $banca,
        'pagAnterior' => 'à Banca',
        'pagAtual' => 'Editar Avaliação'
    ])
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $erro)
                    {{ $erro }}<br>
                @endforeach
            </div>
        @endif
        {{-- Exibe o tempo restante para edição --}}
        <div class="alert alert-warning">
            Você pode editar esta avaliação por até 48h após o envio.<br>
            Tempo restante: <strong>{{ $horasRestantes }}h {{ $minutosRestantes % 60 }}min</strong>
        </div>

        <form action="{{ route('avaliacoes.update', $avaliacaoBanca) }}" method="POST" class="vstack gap-3">
            @method('PUT')
            @include('avaliacoes._form', ['avaliacaoBanca' => $avaliacaoBanca])

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('bancas.show', $banca) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
