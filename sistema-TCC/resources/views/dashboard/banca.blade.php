@extends('layouts.app')

@section('title', 'Dashboard Banca')

@section('content')
@php
    $nomes = explode(' ', trim($user->name));
    $iniciais = strtoupper(substr($nomes[0], 0, 1) . (count($nomes) > 1 ? substr(end($nomes), 0, 1) : ''));
    $corAvatar = $user->avatar ?? '#b20000';
@endphp

<div class="card mb-4">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-4">
            <div class="avatar-user" @style(['background-color: ' . $corAvatar])>{{ $iniciais }}</div>
            <div>
                <h1 class="h3 mb-1">Dashboard Banca</h1>
                <p class="mb-0 text-muted">{{ $user->name }} — {{ $user->email }}</p>
            </div>
        </div>

        <a href="{{ route('users.perfil') }}" class="btn btn-primary">Meu Perfil</a>
    </div>
</div>

<div class="row g-4">

    <div class="col-md-6 col-xl-4">
        <a href="{{ route('banca.bancas.index') }}" class="text-decoration-none text-dark">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-award"></i> Minhas Bancas</div>
                <div class="card-body">Data da apresentação, local/link e status da banca.</div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-4">
        <a href="{{ route('banca.tccs.index') }}" class="text-decoration-none text-dark">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-file-earmark-text"></i> TCC Recebido</div>
                <div class="card-body">Arquivo final enviado pelo aluno.</div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-4">
        <a href="{{ route('banca.avaliacoes_banca.index') }}" class="text-decoration-none text-dark">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-star"></i> Notas Finais</div>
                <div class="card-body">Notas finais do TCC.</div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-4">
        <a href="{{ route('banca.avaliacoes_banca.index') }}" class="text-decoration-none text-dark">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-chat-left-text"></i> Observações</div>
                <div class="card-body">Observações e pareceres do orientador.</div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-4">
        <a href="{{ route('banca.avaliacoes_banca.create') }}" class="text-decoration-none text-dark">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-clipboard-check"></i> Avaliação</div>
                <div class="card-body">Lançar nota e dar parecer.</div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-4">
        <a href="{{ route('banca.avaliacoes_banca.index') }}" class="text-decoration-none text-dark">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-check-circle"></i> Resultado Final</div>
                <div class="card-body">Resultado final do TCC.</div>
            </div>
        </a>
    </div>

</div>
@endsection