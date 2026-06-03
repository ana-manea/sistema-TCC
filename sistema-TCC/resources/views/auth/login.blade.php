@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="login-wrapper">
        <div class="card shadow-sm login-card">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-box-arrow-in-right"></i> Acesso ao Sistema
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('login.attempt') }}" class="vstack gap-3">
                    @csrf

                    <div>
                        <label class="form-label">Usuário ou e-mail</label>
                        <input type="text" name="login" class="form-control @error('login') is-invalid @enderror" value="{{ old('login') }}">
                        @error('login') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="form-label">Senha</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle"></i> Entrar
                    </button>

                    <a href="{{ route('password.request') }}" class="small">Esqueci minha senha</a>
                </form>
            </div>
        </div>
    </div>
@endsection
