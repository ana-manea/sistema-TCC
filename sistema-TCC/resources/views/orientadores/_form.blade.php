@csrf
@php
    $fieldPrefix = $fieldPrefix ?? '';
    $requireFields = $requireFields ?? true;

    $areaName = $fieldPrefix ? $fieldPrefix . '[area_atuacao]' : 'area_atuacao';
    $dispName = $fieldPrefix ? $fieldPrefix . '[disponibilidade]' : 'disponibilidade';
    $maxName = $fieldPrefix ? $fieldPrefix . '[max_orientandos]' : 'max_orientandos';

    $areaKey = $fieldPrefix ? $fieldPrefix . '.area_atuacao' : 'area_atuacao';
    $dispKey = $fieldPrefix ? $fieldPrefix . '.disponibilidade' : 'disponibilidade';
    $maxKey = $fieldPrefix ? $fieldPrefix . '.max_orientandos' : 'max_orientandos';
@endphp

<div class="teacher-block border rounded p-3 mb-3">
    <h5>Dados do Orientador</h5>

    <div class="mb-3">
        <label for="area_atuacao" class="form-label">
            Área de Atuação
        </label>

        <input
            type="text"
            name="{{ $areaName }}"
            id="area_atuacao"
            class="form-control @error($areaKey) is-invalid @enderror"
            value="{{ old($areaKey, $orientador->area_atuacao ?? '') }}"
            placeholder="Ex: Inteligência Artificial"
            data-role="orientador"
            data-required="true"
            @if($requireFields) required @endif
        >

        @error($areaKey)
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="max_orientandos" class="form-label">
            Limite Máximo de Orientandos
        </label>

        <input
            type="number"
            name="{{ $maxName }}"
            id="max_orientandos"
            class="form-control @error($maxKey) is-invalid @enderror"
            value="{{ old($maxKey, $orientador->max_orientandos ?? 1) }}"
            min="1"
            max="8"
            onkeydown="return false;"
            data-role="orientador"
            data-required="true"
            @if($requireFields) required @endif
        >

        @error($maxKey)
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="disponibilidade" class="form-label">
            Disponibilidade e Horários
        </label>

        <textarea
            name="{{ $dispName }}"
            id="disponibilidade"
            rows="4"
            class="form-control @error($dispKey) is-invalid @enderror"
            placeholder="Ex: Segundas e Quartas, das 14h às 18h."
            data-role="orientador"
            data-required="true"
            @if($requireFields) required @endif
        >{{ old($dispKey, $orientador->disponibilidade ?? '') }}</textarea>

        @error($dispKey)
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>

</div>