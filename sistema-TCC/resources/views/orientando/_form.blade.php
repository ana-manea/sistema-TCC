@csrf

<div class="student-block border rounded p-3 mb-3">
    <div class="mb-2 fw-bold">Dados do Orientando</div>
    <div class="mb-3">
        <label class="form-label">Matrícula (RA) *</label>
        <input type="text" name="matricula" class="form-control @error('matricula') is-invalid @enderror" value="{{ old('matricula', $orientando->matricula ?? '') }}" required>
        @error('matricula') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Curso *</label>
        <input type="text" name="curso" class="form-control @error('curso') is-invalid @enderror" value="{{ old('curso', $orientando->curso ?? '') }}" required>
        @error('curso') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Semestre</label>
        <input type="number" name="semestre" class="form-control @error('semestre') is-invalid @enderror" min="1" max="6" value="{{ old('semestre', $orientando->semestre ?? '') }}">
        @error('semestre') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

</div>