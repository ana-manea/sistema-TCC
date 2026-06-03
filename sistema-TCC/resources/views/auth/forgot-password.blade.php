@extends('layouts.app')

@section('title', 'Recuperar Senha')

@section('content')
    <div class="login-wrapper">
        <div class="card shadow-sm login-card">
            <div class="card-header bg-dark text-white">Recuperar senha</div>

            <div class="card-body">
                <form method="POST" action="{{ route('password.email') }}" class="vstack gap-3">
                    @csrf
                    <div>
                        <label class="form-label">E-mail cadastrado</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Enviar link</button>
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">Voltar</a>
                </form>
            </div>
        </div>
    </div>
@endsection
