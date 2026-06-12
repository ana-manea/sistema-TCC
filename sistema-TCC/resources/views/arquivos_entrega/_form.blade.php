@csrf

@if(isset($arquivoEntrega))
    {{-- Modo EDIÇÃO --}}
    <div class="alert alert-light border">
        <strong>Entrega:</strong> {{ $arquivoEntrega->entrega->titulo ?? '—' }}<br>
        <strong>TCC:</strong> {{ $arquivoEntrega->entrega->tcc->tema ?? '—' }}<br>
        <strong>Versão:</strong> v{{ $arquivoEntrega->versao }}
    </div>

    {{-- Status de validação: apenas orientador e admin podem alterar --}}
    @if(auth()->user()->funcao !== 'orientando')
        <div class="mb-3">
            <label class="form-label">Status de Validação</label>
            <select name="status_validacao"
                class="form-select @error('status_validacao') is-invalid @enderror" required>
                @foreach(['pendente' => 'Pendente', 'validado' => 'Validado', 'rejeitado' => 'Rejeitado'] as $valor => $label)
                    <option value="{{ $valor }}"
                        @selected(old('status_validacao', $arquivoEntrega->status_validacao) === $valor)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('status_validacao')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @else
        {{-- Aluno vê o status atual, mas não pode alterar --}}
        @php
            $corValidacao = ['pendente' => 'secondary', 'validado' => 'success', 'rejeitado' => 'danger'];
        @endphp
        <div class="mb-3">
            <label class="form-label">Status de Validação</label><br>
            <span class="badge bg-{{ $corValidacao[$arquivoEntrega->status_validacao] ?? 'secondary' }} fs-6">
                {{ ucfirst($arquivoEntrega->status_validacao) }}
            </span>
            <div class="form-text">Somente o orientador pode alterar o status de validação.</div>
        </div>
    @endif

@else
    {{-- Modo CRIAÇÃO --}}
    <div class="mb-3">
        <label class="form-label">Entrega <span class="text-danger">*</span></label>
        <select class="form-select @error('entrega_id') is-invalid @enderror" name="entrega_id" required>
            <option value="">— Selecione a entrega —</option>
            @foreach($entregas as $entrega)
                <option value="{{ $entrega->id }}" @selected(old('entrega_id') == $entrega->id)>
                    {{ $entrega->titulo }}
                    @if($entrega->tcc)— {{ $entrega->tcc->tema }}@endif
                </option>
            @endforeach
        </select>
        @error('entrega_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Arquivo <span class="text-danger">*</span></label>
        <input class="form-control @error('arquivo') is-invalid @enderror"
            type="file" name="arquivo" required>
        <div class="form-text">Formatos aceitos: PDF, DOC e DOCX. Tamanho máximo: 20 MB.</div>
        @error('arquivo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
@endif

<div class="mb-3">
    <label class="form-label">Observação</label>
    <textarea class="form-control @error('observacao') is-invalid @enderror"
        name="observacao" rows="3"
        placeholder="Descreva observações sobre esta versão...">{{ old('observacao', $arquivoEntrega->observacao ?? '') }}</textarea>
    @error('observacao')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
