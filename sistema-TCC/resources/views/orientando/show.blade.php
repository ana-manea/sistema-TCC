@extends('layouts.app')

@section('title', 'Orientando')

@section('content')
    @php
        $nomeUsuario = $orientando->user->name ?? '';
        $nomes = $nomeUsuario ? explode(' ', trim($nomeUsuario)) : [];
        $iniciais = $nomeUsuario ? strtoupper(
        substr($nomes[0], 0, 1) . (count($nomes) > 1 ? substr(end($nomes), 0, 1) : '')
        ) : '';
        $corAvatar = $orientando->user->avatar ?? '#b20000';
    @endphp
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Detalhes do Orientando</div>

            <div class="card-body">
                <div class="mb-4">
                    <div class="avatar-user" @style(['background-color: ' . $corAvatar])>
                            {{ $iniciais }}
                        </div>
                    </div>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Nome</dt>
                        <dd class="col-sm-8">{{ $orientando->user->name ?? '-' }}</dd>

                        <dt class="col-sm-4">Matrícula</dt>
                        <dd class="col-sm-8">{{ $orientando->matricula }}</dd>

                        <dt class="col-sm-4">Curso</dt>
                        <dd class="col-sm-8">{{ $orientando->curso }}</dd>

                        <dt class="col-sm-4">Semestre</dt>
                        <dd class="col-sm-8">{{ $orientando->semestre ?? '-' }}</dd>

                        <dt class="col-sm-4">Orientador</dt>
                        <dd class="col-sm-8">{{ $orientando->orientador->user->name ?? '-' }}</dd>
                    </dl>

                    <div class="mt-3 d-flex gap-2">
                        <a class="btn btn-outline-primary" href="{{ route('orientandos.edit', $orientando) }}">Editar</a>
                        <a class="btn btn-outline-secondary" href="{{ route('orientandos.index') }}">Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection