@extends('layouts.app')

@section('title', 'Redefinir Senha')

@section('content')
    <div class="login-wrapper">
        <div class="card shadow-sm login-card">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-key"></i> Redefinir senha
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('password.update') }}" class="vstack gap-3">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label class="form-label">E-mail</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $email) }}"
                            readonly
                        >

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Nova senha</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                        >

                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Confirmar nova senha</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                        >
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Redefinir senha
                    </button>

                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                        Voltar
                    </a>
                </form>
            </div>
        </div>
    </div>
@endsection