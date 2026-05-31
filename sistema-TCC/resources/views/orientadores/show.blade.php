@extends('layouts.app')

@section('title', 'Perfil do Orientador')

@section('content')
    @php
        $nomeUsuario = $orientador->user->name ?? '';
        $nomes = $nomeUsuario ? explode(' ', trim($nomeUsuario)) : [];
        $iniciais = $nomeUsuario ? strtoupper(
            substr($nomes[0], 0, 1) . (count($nomes) > 1 ? substr(end($nomes), 0, 1) : '')
        ) : '';
        $corAvatar = $orientador->user->avatar ?? '#b20000';
    @endphp
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between gap-4 align-items-center mb-3">
        <div class="d-flex gap-4">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('orientadores.index') }}">
                <i class="bi bi-arrow-left"></i> Ver todos os orientadores
            </a>
            <h1 class="h3 mb-0 text-gray-800">Detalhes do Orientador</h1>
        </div>

        @if(auth()->check() && auth()->id() === $orientador->user_id)
        <div>
            <a class="btn btn-outline-primary" href="{{ route('orientadores.edit', $orientador) }}">
                <i class="bi bi-pencil-square"></i> Editar
            </a>
        </div>
        @endif
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-4">
                <div class="avatar-user" @style(['background-color: ' . $corAvatar])>
                    {{ $iniciais }}
                </div>
            </div>

            <dl class="row mb-0">
                <dt class="col-sm-4">Nome</dt>
                <dd class="col-sm-8">{{ $orientador->user->name ?? '-' }}</dd>

                <dt class="col-sm-4">E-mail</dt>
                <dd class="col-sm-8">{{ $orientador->user->email ?? '-' }}</dd>

                <dt class="col-sm-4">Área de Atuação</dt>
                <dd class="col-sm-8">{{ $orientador->area_atuacao ?? '-' }}</dd>

                <dt class="col-sm-4">Disponibilidade</dt>
                <dd class="col-sm-8">{!! nl2br(e($orientador->disponibilidade ?? '-')) !!}</dd>

                <dt class="col-sm-4">Vagas para Orientação</dt>
                <dd class="col-sm-8">Limite Máximo: {{ $orientador->max_orientandos }} alunos</dd>
            </dl>
        </div>
    </div>
</div>
@endsection