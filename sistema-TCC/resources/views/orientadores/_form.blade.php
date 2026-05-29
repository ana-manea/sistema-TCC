@csrf

<div class="teacher-block border rounded p-3 mb-3">
    <div class="mb-2 fw-bold">
        Dados do Orientador
    </div>
    <div class="mb-3">
        <label for="area_atuacao" class="form-label">
            Área de Atuação
        </label>

        <input type="text" name="area_atuacao" id="area_atuacao"class="form-control @error('area_atuacao') is-invalid @enderror" value="{{ old('area_atuacao', $orientador->area_atuacao ?? '') }}" placeholder="Ex: Inteligência Artificial" required>

        @error('area_atuacao')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="max_orientandos" class="form-label">
            Limite Máximo de Orientandos
        </label>

        <input type="number" name="max_orientandos" id="max_orientandos" class="form-control @error('max_orientandos') is-invalid @enderror" value="{{ old('max_orientandos', $orientador->max_orientandos ?? 1) }}" min="1" max="8" onkeydown="return false;" required>

        @error('max_orientandos')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="disponibilidade" class="form-label">
            Disponibilidade e Horários
        </label>

        <textarea name="disponibilidade" id="disponibilidade" rows="4" class="form-control @error('disponibilidade') is-invalid @enderror" placeholder="Ex: Segundas e Quartas, das 14h às 18h." required>{{ old('disponibilidade', $orientador->disponibilidade ?? '') }}</textarea>

        @error('disponibilidade')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>

</div>