@csrf
@php
    $fieldPrefix = $fieldPrefix ?? '';
    $requireFields = $requireFields ?? true;

    $matriculaName = $fieldPrefix ? $fieldPrefix . '[matricula]' : 'matricula';
    $cursoName = $fieldPrefix ? $fieldPrefix . '[curso]' : 'curso';
    $semestreName = $fieldPrefix ? $fieldPrefix . '[semestre]' : 'semestre';

    $matriculaKey = $fieldPrefix ? $fieldPrefix . '.matricula' : 'matricula';
    $cursoKey = $fieldPrefix ? $fieldPrefix . '.curso' : 'curso';
    $semestreKey = $fieldPrefix ? $fieldPrefix . '.semestre' : 'semestre';
@endphp

<div class="student-block border rounded p-3 mb-3">
    <h5>Dados do Orientando</h5>
    <div class="mb-3">
        <label class="form-label">Matrícula (RA) *</label>
        <input
            type="text"
            name="{{ $matriculaName }}"
            class="form-control @error($matriculaKey) is-invalid @enderror"
            value="{{ old($matriculaKey, $orientando->matricula ?? '') }}"
            data-role="orientando"
            data-required="true"
            @if($requireFields) required @endif
        >
        @error($matriculaKey) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Curso *</label>
        <input
            type="text"
            name="{{ $cursoName }}"
            class="form-control @error($cursoKey) is-invalid @enderror"
            value="{{ old($cursoKey, $orientando->curso ?? '') }}"
            data-role="orientando"
            data-required="true"
            @if($requireFields) required @endif
        >
        @error($cursoKey) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Semestre</label>
        <input
            type="number"
            name="{{ $semestreName }}"
            class="form-control @error($semestreKey) is-invalid @enderror"
            min="1"
            max="6"
            value="{{ old($semestreKey, $orientando->semestre ?? '') }}"
            data-role="orientando"
        >
        @error($semestreKey) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

</div>