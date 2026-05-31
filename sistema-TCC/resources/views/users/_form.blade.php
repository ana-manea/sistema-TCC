@csrf
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
            <option value="admin" @selected(old('funcao', $funcao ?? '') == 'admin')>Admin</option>
            <option value="orientador" @selected(old('funcao', $funcao ?? '') == 'orientador')>Orientador</option>
            <option value="orientando" @selected(old('funcao', $funcao ?? '') == 'orientando')>Orientando</option>
            <option value="membro_banca" @selected(old('funcao', $funcao ?? '') == 'membro_banca')>Membro da banca</option>
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
    @include('orientando._form')
    
</div>

<div id="orientador-fields" style="display: none;">
    @include('orientadores._form')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
            const funcaoSelect = document.querySelector('select[name="funcao"]');
            const orientandoFields = document.getElementById('orientando-fields');
            const orientadorFields = document.getElementById('orientador-fields');

            function toggle() {
                if (!funcaoSelect) return;
                const v = funcaoSelect.value;
                if (orientandoFields) orientandoFields.style.display = v === 'orientando' ? 'block' : 'none';
                if (orientadorFields) orientadorFields.style.display = v === 'orientador' ? 'block' : 'none';
            }

            if (funcaoSelect) {
                funcaoSelect.addEventListener('change', toggle);
            }

            // initial state
            toggle();
    });
</script>
