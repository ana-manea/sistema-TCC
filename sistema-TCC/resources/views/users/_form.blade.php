@csrf
@php
    $funcaoSelecionada = old('funcao', $user->funcao ?? '');
@endphp

<div class="mb-3">
    <label class="form-label">Nome</label>
    <input
        type="text"
        name="name"
        class="form-control"
        value="{{ old('name', $user->name ?? '') }}"
    >
    @error('name') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">E-mail</label>
    <input
        type="email"
        name="email"
        class="form-control"
        value="{{ old('email', $user->email ?? '') }}"
    >
    @error('email') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Senha</label>
    <input type="password" name="password" class="form-control">
    @error('password') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Função</label>
    <select name="funcao" id="funcao" class="form-select">
        <option value="">Selecione</option>
        <option value="admin" @selected($funcaoSelecionada == 'admin')>Admin</option>
        <option value="orientador" @selected($funcaoSelecionada == 'orientador')>Orientador</option>
        <option value="orientando" @selected($funcaoSelecionada == 'orientando')>Orientando</option>
        <option value="membro_banca" @selected($funcaoSelecionada == 'membro_banca')>Membro da banca</option>
    </select>
    @error('funcao') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div id="campos-orientador" style="display: none;">
    <hr>

    <h5 class="mb-3">Dados do Orientador</h5>

    <div class="mb-3">
        <label class="form-label">Área de atuação</label>
        <input
            type="text"
            name="area_atuacao"
            class="form-control"
            value="{{ old('area_atuacao', $user->orientador->area_atuacao ?? '') }}"
        >
        @error('area_atuacao') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Disponibilidade</label>
        <textarea
            name="disponibilidade"
            class="form-control"
            rows="3"
        >{{ old('disponibilidade', $user->orientador->disponibilidade ?? '') }}</textarea>
        @error('disponibilidade') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Máximo de orientandos</label>
        <input
            type="number"
            name="max_orientandos"
            class="form-control"
            min="1"
            value="{{ old('max_orientandos', $user->orientador->max_orientandos ?? 5) }}"
        >
        @error('max_orientandos') <div class="form-error">{{ $message }}</div> @enderror
    </div>
</div>

<div id="campos-orientando" style="display: none;">
    <hr>

    <h5 class="mb-3">Dados do Orientando</h5>

    <div class="mb-3">
        <label class="form-label">Matrícula</label>
        <input
            type="text"
            name="matricula"
            class="form-control"
            value="{{ old('matricula', $user->orientando->matricula ?? '') }}"
        >
        @error('matricula') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Curso</label>
        <input
            type="text"
            name="curso"
            class="form-control"
            value="{{ old('curso', $user->orientando->curso ?? '') }}"
        >
        @error('curso') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Semestre</label>
        <input
            type="text"
            name="semestre"
            class="form-control"
            value="{{ old('semestre', $user->orientando->semestre ?? '') }}"
        >
        @error('semestre') <div class="form-error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Cor do Avatar</label>
    <input
        type="color"
        name="avatar"
        class="form-control form-control-color"
        value="{{ old('avatar', $user->avatar ?? '#b20000') }}"
    >
    @error('avatar') <div class="form-error">{{ $message }}</div> @enderror
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectFuncao = document.getElementById('funcao');
    const camposOrientador = document.getElementById('campos-orientador');
    const camposOrientando = document.getElementById('campos-orientando');

    if (!selectFuncao || !camposOrientador || !camposOrientando) {
        return;
    }

    function atualizarCampos() {
        camposOrientador.style.display = 'none';
        camposOrientando.style.display = 'none';

        if (selectFuncao.value === 'orientador') {
            camposOrientador.style.display = 'block';
        }

        if (selectFuncao.value === 'orientando') {
            camposOrientando.style.display = 'block';
        }
    }

    atualizarCampos();

    selectFuncao.addEventListener('change', atualizarCampos);
});
</script>