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

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Detalhes do Orientador</div>

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

                <div class="mt-3 d-flex gap-2">
                    @if(auth()->check() && auth()->id() === $orientador->user_id)
                        <a class="btn btn-outline-primary" href="{{ route('orientadores.edit', $orientador) }}">Editar Perfil</a>
                    @endif
                    <a class="btn btn-outline-secondary" href="{{ route('orientadores.index') }}">Voltar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection