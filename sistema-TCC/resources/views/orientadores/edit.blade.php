@extends('layouts.app')

@section('title', 'Editar Orientador')

@section('content')

    <div class="row">

        <div class="col-lg-8">

            <div class="card">

                <div class="card-header">
                    Editar Orientador
                </div>

                <div class="card-body">

                    {{-- ERROS --}}
                    @if($errors->any())

                        <div class="alert alert-danger">

                            <strong>
                                Corrija os campos abaixo:
                            </strong>

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach

                            </ul>

                        </div>

                    @endif

                    {{-- DADOS DO USUÁRIO --}}
                    <div class="mb-4">

                        <div class="d-flex align-items-center gap-4">

                            @php
                                $user = $orientador->user ?? null;

                                $name = $user->name ?? '';

                                $initials = collect(explode(' ', trim($name)))
                                    ->map(function($w){
                                        return mb_substr($w, 0, 1);
                                    })
                                    ->join('');

                                $avatarColor = $user->avatar ?? '#b20000';
                            @endphp

                            {{-- AVATAR --}}
                            <div
                                id="avatar-{{ $orientador->id }}"
                                class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                data-color="{{ $avatarColor }}"
                                style="width:96px;height:96px;font-weight:600;font-size:28px;"
                            >
                                {{ strtoupper($initials) }}
                            </div>

                            {{-- INFOS --}}
                            <div>

                                <h2 class="h4 mb-1">
                                    {{ $user->name ?? '-' }}
                                </h2>

                                <p class="mb-0">
                                    {{ $user->email ?? '-' }}
                                </p>

                                <p class="mb-0 text-muted">
                                    {{ ucfirst($user->funcao ?? '-') }}
                                </p>

                            </div>

                        </div>

                    </div>

                    {{-- FORM --}}
                    <form
                        action="{{ route('orientadores.update', ['orientador' => $orientador->id]) }}"
                        method="POST"
                        class="vstack gap-3"
                    >

                        @csrf
                        @method('PUT')

                        {{-- NOME DO PROFESSOR --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Professor Orientador
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $orientador->user->name }}"
                                disabled
                            >

                        </div>

                        {{-- FORMULÁRIO --}}
                        @include('orientadores._form', [
                            'orientador' => $orientador
                        ])

                        {{-- BOTÕES --}}
                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Atualizar Perfil
                            </button>

                            <a
                                href="{{ route('orientadores.index') }}"
                                class="btn btn-outline-secondary"
                            >
                                Cancelar
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

    {{-- AVATAR COLOR --}}
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const el = document.getElementById('avatar-{{ $orientador->id }}');

            if (el && el.dataset.color) {
                el.style.backgroundColor = el.dataset.color;
            }

        });

    </script>

@endsection