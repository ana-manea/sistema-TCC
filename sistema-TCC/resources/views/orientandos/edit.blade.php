@extends('layouts.app')

@section('title', 'Editar Orientando')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    @include('layouts.voltar_titulo', [
        'rota' => 'orientandos.show',
        'variavel' => $orientando,
        'pagAnterior' => 'ao Orientando',
        'pagAtual' => 'Editar Orientando: ' . $orientando->user->name
    ])

    <div>
        <a class="btn btn-outline-secondary" href="{{ route('orientandos.index') }}">
            Voltar
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Corrija os campos abaixo:</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-4">
            <div class="d-flex align-items-center gap-4">
                @php
                    $user = $orientando->user ?? null;
                    $name = $user->name ?? '';
                    $initials = collect(explode(' ', trim($name)))
                        ->filter()
                        ->map(fn($w) => mb_substr($w, 0, 1))
                        ->take(2)
                        ->join('');
                    $avatarColor = $user->avatar ?? '#b20000';
                @endphp

                <div id="avatar-{{ $orientando->id }}"
                     class="rounded-circle d-flex align-items-center justify-content-center text-white"
                     data-color="{{ $avatarColor }}"
                     style="width:72px;height:72px;font-weight:600;font-size:24px;">
                    {{ strtoupper($initials) }}
                </div>

                <div>
                    <h2 class="h4 mb-1">{{ $user->name ?? '-' }}</h2>
                    <p class="mb-0">{{ $user->email ?? '-' }}</p>
                    <p class="mb-0 text-muted">{{ ucfirst($user->funcao ?? '-') }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('orientandos.update', $orientando) }}" method="POST" class="vstack gap-3">
            @csrf
            @method('PUT')

            @include('orientandos._form', ['orientando' => $orientando])

            <div class="d-flex gap-2 border-top pt-3 mt-3">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('orientandos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const el = document.getElementById('avatar-{{ $orientando->id }}');

        if (el && el.dataset.color) {
            el.style.backgroundColor = el.dataset.color;
        }
    });
</script>
@endsection