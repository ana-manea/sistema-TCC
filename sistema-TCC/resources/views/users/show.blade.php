@extends('layouts.app')

@section('title', 'Detalhes do Usuário')

@section('content')
@php
    $nomes = explode(' ', trim($user->name));
    $iniciais = strtoupper(substr($nomes[0], 0, 1) . (count($nomes) > 1 ? substr(end($nomes), 0, 1) : ''));
    $corAvatar = $user->avatar ?? '#b20000';
    $authUser = auth()->user();

    $podeGerenciar = !(
        $authUser->funcao === 'admin'
        && (
            $authUser->id === $user->id
            || $user->funcao === 'admin'
        )
    );
@endphp

<div class="d-flex justify-content-between align-items-start mb-3">
    @include('layouts.voltar_titulo', [
        'rota' => 'users.index',
        'pagAnterior' => 'aos Usuários',
        'pagAtual' => 'Usuário: ' . $user->name
    ])
    <div>
        @if($podeGerenciar)
            <a class="btn btn-outline-primary" href="{{ route('users.edit', $user) }}">
                <i class="bi bi-pencil-square"></i> Editar
            </a>

            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Excluir usuário?');">
                @csrf
                @method('DELETE')

                <button class="btn btn-outline-danger">
                    Excluir
                </button>
            </form>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="avatar-user mb-3" @style(['background-color: ' . $corAvatar])>{{ $iniciais }}</div>
        <p><strong>Nome:</strong> {{ $user->name }}</p>
        <p><strong>E-mail:</strong> {{ $user->email }}</p>
        <p><strong>Função:</strong> {{ $user->funcao }}</p>
    </div>
</div>
@endsection
