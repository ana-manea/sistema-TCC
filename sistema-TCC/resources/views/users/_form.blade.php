@csrf
@php
    $orientando = $orientando ?? ($user->orientando ?? null);
    $orientador = $orientador ?? ($user->orientador ?? null);
@endphp

<div class="student-block border rounded p-3 mb-3">
    <h5>Dados Básicos</h5>

    <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}">
        @error('name') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}">
        @error('email') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Senha</label>
        <input type="password" name="password" class="form-control">
        @error('password') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Função</label>
        <select name="funcao" class="form-select">
            <option value="">Selecione</option>
            <option value="admin" @selected(old('funcao', $funcao ?? $user->funcao ?? '') == 'admin')>Admin</option>
            <option value="orientador" @selected(old('funcao', $funcao ?? $user->funcao ?? '') == 'orientador')>Orientador</option>
            <option value="orientando" @selected(old('funcao', $funcao ?? $user->funcao ?? '') == 'orientando')>Orientando</option>
            <option value="membro_banca" @selected(old('funcao', $funcao ?? $user->funcao ?? '') == 'membro_banca')>Membro da banca</option>
        </select>
        @error('funcao') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Cor do Avatar</label>

        <div class="d-flex align-items-center gap-3">
            <input type="color" name="avatar" class="form-control form-control-color" value="{{ old('avatar', $user->avatar ?? '#b20000') }}">
            <span class="text-muted small">Padrão: #b20000</span>
        </div>

        @error('avatar') <div class="form-error">{{ $message }}</div> @enderror
    </div>
</div>

<div id="orientando-fields" style="display: none;">
    @include('orientando._form', ['orientando' => $orientando, 'fieldPrefix' => 'orientando', 'requireFields' => false])
</div>

<div id="orientador-fields" style="display: none;">
    @include('orientadores._form', ['orientador' => $orientador, 'fieldPrefix' => 'orientador', 'requireFields' => false])
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
            const funcaoSelect = document.querySelector('select[name="funcao"]');
            const orientandoFields = document.getElementById('orientando-fields');
            const orientadorFields = document.getElementById('orientador-fields');

            const orientandoInputs = orientandoFields
                ? orientandoFields.querySelectorAll('[data-role="orientando"]')
                : [];
            const orientadorInputs = orientadorFields
                ? orientadorFields.querySelectorAll('[data-role="orientador"]')
                : [];

            function setGroupState(inputs, enabled) {
                inputs.forEach((input) => {
                    input.disabled = !enabled;
                    input.required = enabled && input.dataset.required === 'true';
                });
            }

            function toggle() {
                if (!funcaoSelect) return;
                const v = funcaoSelect.value;
                const exibirOrientando = v === 'orientando';
                const exibirOrientador = v === 'orientador';

                if (orientandoFields) orientandoFields.style.display = exibirOrientando ? 'block' : 'none';
                if (orientadorFields) orientadorFields.style.display = exibirOrientador ? 'block' : 'none';

                setGroupState(orientandoInputs, exibirOrientando);
                setGroupState(orientadorInputs, exibirOrientador);
            }

            if (funcaoSelect) {
                funcaoSelect.addEventListener('change', toggle);
            }

            // initial state
            toggle();
    });
</script>
