@csrf
<div class="mb-3">
    <label for="nota" class="form-label">Nota da Avaliação (0 a 10):</label>
    <input 
        type="number" 
        name="nota"
        class="form-control" 
        value="{{ old('nota', $avalicao->nota ?? '' ) }}" 
        step="1" min="0" max="10"
        required>
    @error('nota')
        <span style="color: red;">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label for="parecer" class="form-label">Parecer / Justificativa:</label>
    <textarea class="form-control" name="parecer" rows="5" placeholder="Digite aqui as considerações e feedbacks..." required>{{ old('parecer'), $avalicao->parecer ?? '' }}</textarea>
    @error('parecer')
        <span style="color: red;">{{ $message }}</span>
    @enderror
</div>