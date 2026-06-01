<div class="mb-3">
    <label for="descricao" class="form-label">Descrição do Feedback</label>
    <textarea
        name="descricao"
        id="descricao"
        class="form-control @error('descricao') is-invalid @enderror"
        rows="5"
        required>{{ old('descricao', $feedback->descricao ?? '') }}</textarea>
    @error('descricao')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>