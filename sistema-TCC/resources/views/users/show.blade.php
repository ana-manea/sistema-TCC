@extends('layouts.app')

@section('title', 'Detalhes do Usuário')

@section('content')
    @php
        $nomes = explode(' ', trim($user->name));
        $iniciais = strtoupper(substr($nomes[0], 0, 1) . (count($nomes) > 1 ? substr(end($nomes), 0, 1) : ''));
        $corAvatar = $user->avatar ?? '#b20000';
    @endphp

    <div class="card">
        <div class="card-body">
            <div class="avatar-user mb-3" @style(['background-color: ' . $corAvatar])>{{ $iniciais }}</div>
            <p><strong>Nome:</strong> {{ $user->name }}</p>
            <p><strong>E-mail:</strong> {{ $user->email }}</p>
            <p><strong>Função:</strong> {{ $user->funcao }}</p>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Voltar</a>
        </div>
    </div>
@endsection
