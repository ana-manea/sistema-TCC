@csrf

@php
    $avaliacaoAtual = $avaliacaoBanca ?? null;
@endphp

<div class="mb-3">
    <label for="nota" class="form-label">Nota da Avaliação (0 a 10)</label>
    <input
        type="number"
        name="nota"
        class="form-control @error('nota') is-invalid @enderror"
        value="{{ old('nota', optional($avaliacaoAtual)->nota) }}"
        step="0.1"
        min="0"
        max="10"
        required>
    @error('nota')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="parecer" class="form-label">Parecer / Justificativa</label>
    <textarea
        class="form-control @error('parecer') is-invalid @enderror"
        name="parecer"
        rows="5"
        placeholder="Digite aqui as considerações e feedbacks..."
        required>{{ old('parecer', optional($avaliacaoAtual)->parecer) }}</textarea>
    @error('parecer')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>