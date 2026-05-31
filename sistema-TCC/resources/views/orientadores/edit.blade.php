@extends('layouts.app')

@section('title', 'Editar Meu Perfil')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-start gap-4 align-items-center mb-3">
        <a href="{{ route('orientador.dashboard', $orientador->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Voltar ao Painel
        </a>
        <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-pencil-square me-2"></i> Editar Perfil do Orientador</h1>
    </div>
    <div class="row justify-content-center">
        <div class="card shadow-sm">
            <div class="card-body">

                {{-- EXIBIÇÃO DE ERROS DE VALIDAÇÃO --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>Corrija os campos abaixo:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- DADOS DO USUÁRIO E PRÉ-VISUALIZAÇÃO DO AVATAR --}}
                <div class="mb-4 p-3 bg-light rounded d-flex align-items-center gap-4">
                    @php
                        $user = $orientador->user ?? null;
                        $name = $user->name ?? '';
                        
                        // Gera as iniciais do nome para o Avatar provisório
                        $initials = collect(explode(' ', trim($name)))
                            ->map(function($w){ return mb_substr($w, 0, 1); })
                            ->join('');

                        $avatarColor = $user->avatar ?? '#b20000';
                    @endphp

                    {{-- Círculo do Avatar --}}
                    <div
                        id="avatar-perfil"
                        class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm"
                        data-color="{{ $avatarColor }}"
                        style="width:96px;height:96px;font-weight:600;font-size:28px;transition: background-color 0.3s;"
                    >
                        {{ strtoupper($initials) }}
                    </div>

                    <div>
                        <h2 class="h4 mb-1">{{ $user->name ?? 'Professor' }}</h2>
                        <p class="mb-0 text-muted small">Alterações nesta tela atualizam seu perfil de acesso e suas informações públicas de orientação.</p>
                    </div>
                </div>

                {{-- FORMULÁRIO DE ATUALIZAÇÃO --}}
                <form
                    action="{{ route('orientadores.update', ['orientador' => $orientador->id]) }}"
                    method="POST"
                    class="vstack gap-3"
                >
                    @csrf
                    @method('PUT')

                    <h5 class="text-primary border-bottom pb-2 mb-2">Informações de Acesso (Usuário)</h5>

                    {{-- NOME --}}
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nome Completo</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control"
                            value="{{ old('name', $user->name ?? '') }}"
                            required
                        >
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">E-mail</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email', $user->email ?? '') }}"
                            required
                        >
                    </div>

                    {{-- SENHA --}}
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Nova Senha</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="Deixe em branco para manter a senha atual"
                        >
                        <div class="form-text">Preencha este campo apenas se desejar alterar sua senha de acesso.</div>
                    </div>

                    {{-- AVATAR (SELETOR DE COR) --}}
                    <div class="mb-3">
                        <label for="avatar" class="form-label fw-semibold">Cor do Avatar (Estilo)</label>
                        <div class="d-flex align-items-center gap-3">
                            <input
                                type="color"
                                id="avatar"
                                name="avatar"
                                class="form-control form-control-color"
                                value="{{ old('avatar', $avatarColor) }}"
                                title="Escolha a cor do seu avatar"
                            >
                            <span class="text-muted small">Escolha uma cor para personalizar seu ícone no painel.</span>
                        </div>
                    </div>


                    <h5 class="text-primary border-bottom pb-2 mb-2 mt-3">Dados de Orientação</h5>

                    {{-- ÁREA DE ATUAÇÃO --}}
                    <div class="mb-3">
                        <label for="area_atuacao" class="form-label fw-semibold">Área de Atuação</label>
                        <input
                            type="text"
                            id="area_atuacao"
                            name="area_atuacao"
                            class="form-control"
                            placeholder="Ex: Engenharia de Software, Inteligência Artificial"
                            value="{{ old('area_atuacao', $orientador->area_atuacao ?? '') }}"
                            required
                        >
                    </div>

                    {{-- DISPONIBILIDADE --}}
                    <div class="mb-3">
                        <label for="disponibilidade" class="form-label fw-semibold">Disponibilidade</label>
                        <input
                            type="text"
                            id="disponibilidade"
                            name="disponibilidade"
                            class="form-control"
                            placeholder="Ex: Terças e Quintas à noite, Sábados de manhã"
                            value="{{ old('disponibilidade', $orientador->disponibilidade ?? '') }}"
                            required
                        >
                    </div>

                    {{-- QUANTIDADE MÁXIMA DE ORIENTANDOS --}}
                    <div class="mb-3">
                        <label for="max_orientandos" class="form-label fw-semibold">Quantidade Máxima de Orientandos</label>
                        <input
                            type="number"
                            id="max_orientandos"
                            name="max_orientandos"
                            class="form-control"
                            min="1"
                            max="50"
                            value="{{ old('max_orientandos', $orientador->max_orientandos ?? 5) }}"
                            required
                        >
                    </div>

                    {{-- BOTÕES DE AÇÃO --}}
                    <div class="d-flex gap-2 border-top pt-3 mt-3">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-1"></i> Salvar Alterações
                        </button>

                        <a href="{{ route('orientador.dashboard', $orientador->id) }}" class="btn btn-outline-secondary px-4">
                            Cancelar
                        </a>
                    </div>

                </form>

            </div>

        </div>
    </div>

    {{-- JAVASCRIPT DINÂMICO PARA O AVATAR --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const avatarEl = document.getElementById('avatar-perfil');
            const colorInput = document.getElementById('avatar');

            // 1. Aplica a cor inicial vinda do banco ou padrão
            if (avatarEl && avatarEl.dataset.color) {
                avatarEl.style.backgroundColor = avatarEl.dataset.color;
            }

            // 2. Atualiza a cor do círculo em tempo real ao mudar o seletor (Feedback visual top!)
            if (colorInput && avatarEl) {
                colorInput.addEventListener('input', function () {
                    avatarEl.style.backgroundColor = this.value;
                });
            }
        });
    </script>
</div>
@endsection