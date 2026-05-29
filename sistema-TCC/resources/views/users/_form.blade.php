@csrf

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
        <option value="admin" @selected(old('funcao', $user->funcao ?? '') == 'admin')>Admin</option>
        <option value="orientador" @selected(old('funcao', $user->funcao ?? '') == 'orientador')>Orientador</option>
        <option value="orientando" @selected(old('funcao', $user->funcao ?? '') == 'orientando')>Orientando</option>
        <option value="membro_banca" @selected(old('funcao', $user->funcao ?? '') == 'membro_banca')>Membro da banca</option>
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

<div id="orientando-fields" style="display: none;">
    <hr>
    <h5>Dados do Orientando</h5>

    <div class="mb-3">
        <label class="form-label">Matrícula (RA) *</label>
        <input type="text" name="orientando[matricula]" class="form-control @error('orientando.matricula') is-invalid @enderror" value="{{ old('orientando.matricula', $user->orientando->matricula ?? '') }}">
        @error('orientando.matricula') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Curso *</label>
        <input type="text" name="orientando[curso]" class="form-control @error('orientando.curso') is-invalid @enderror" value="{{ old('orientando.curso', $user->orientando->curso ?? '') }}">
        @error('orientando.curso') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Semestre</label>
        <input type="number" name="orientando[semestre]" class="form-control @error('orientando.semestre') is-invalid @enderror" min="1" value="{{ old('orientando.semestre', $user->orientando->semestre ?? '') }}">
        @error('orientando.semestre') <div class="form-error">{{ $message }}</div> @enderror
    </div>
</div>

<div id="orientador-fields" style="display: none;">
    <hr>
    <h5>Dados do Orientador</h5>

    <div class="mb-3">
        <label class="form-label">Área de Atuação</label>
        <input type="text" name="orientador[area_atuacao]" class="form-control @error('orientador.area_atuacao') is-invalid @enderror" value="{{ old('orientador.area_atuacao', $user->orientador->area_atuacao ?? '') }}">
        @error('orientador.area_atuacao') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Disponibilidade</label>
        <input type="text" name="orientador[disponibilidade]" class="form-control @error('orientador.disponibilidade') is-invalid @enderror" value="{{ old('orientador.disponibilidade', $user->orientador->disponibilidade ?? '') }}">
        @error('orientador.disponibilidade') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Máximo de Orientandos</label>
        <input type="number" name="orientador[max_orientandos]" class="form-control @error('orientador.max_orientandos') is-invalid @enderror" value="{{ old('orientador.max_orientandos', $user->orientador->max_orientandos ?? '') }} min="1" max="8"  onkeydown="return false;">
        @error('orientador.max_orientandos') <div class="form-error">{{ $message }}</div> @enderror
    </div>
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
