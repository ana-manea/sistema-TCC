@extends('layouts.app')

@section('title', 'Editar Orientando')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">Editar Orientando</div>

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

                    {{-- usuário: avatar e informações --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-4">
                            @php
                                $user = $orientando->user ?? null;
                                $name = $user->name ?? '';
                                $initials = collect(explode(' ', trim($name)))->map(function($w){ return mb_substr($w,0,1); })->join('');
                                $avatarColor = $user->avatar ?? '#b20000';
                            @endphp

                            <div id="avatar-{{ $orientando->id }}" class="rounded-circle d-flex align-items-center justify-content-center text-white" data-color="{{ $avatarColor }}" style="width:96px;height:96px;font-weight:600;font-size:28px;">
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

                        @include('orientando._form', ['orientando' => $orientando])

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                            <a class="btn btn-outline-secondary" href="{{ route('orientandos.index') }}">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
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