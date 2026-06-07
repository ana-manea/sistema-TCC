@extends('layouts.app')

@section('title', 'Nova Avaliação')

@section('content')
@php
    $horasDecorridas  = $avaliacaoBanca->created_at->diffInHours(now());
    $horasRestantes   = max(0, 48 - $horasDecorridas);
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
            <ul style="color: red;">
                @foreach($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        @endif
        {{-- Exibe o tempo restante para edição --}}
        <p style="color: orange;">
            <strong>Atenção:</strong> Você pode editar esta avaliação por até 48h após o envio.<br>
            Tempo restante: <strong>{{ $horasRestantes }}h {{ $minutosRestantes % 60 }}min</strong>
        </p>
        <form action="{{ route('avaliacoes.update', $banca) }}" method="POST" class="vstack gap-3">
            @method('PUT')
            @include('avaliacoes._form', ['avaliacao' => $avaliacao])

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('bancas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
