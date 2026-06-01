@extends('layouts.app')

@section('title', 'Editar Arquivo')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="bi bi-pencil-square"></i> Editar Arquivo — v{{ $arquivoEntrega->versao }}
                </div>
                <div class="card-body">

                    {{-- Info somente leitura --}}
                    <div class="alert alert-light border mb-4">
                        <strong>Entrega:</strong> {{ $arquivoEntrega->entrega->titulo ?? '—' }}<br>
                        <strong>Arquivo:</strong>
                        <a href="{{ Storage::url($arquivoEntrega->arquivo_path) }}" target="_blank">
                            {{ basename($arquivoEntrega->arquivo_path) }}
                        </a>
                    </div>

                    <form action="{{ route('arquivos_entrega.update', $arquivoEntrega) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Status de Validação <span class="text-danger">*</span></label>
                            <select class="form-select" name="status_validacao" required>
                                <option value="pendente"  @selected(old('status_validacao', $arquivoEntrega->status_validacao) == 'pendente')>Pendente</option>
                                <option value="validado"  @selected(old('status_validacao', $arquivoEntrega->status_validacao) == 'validado')>Validado</option>
                                <option value="rejeitado" @selected(old('status_validacao', $arquivoEntrega->status_validacao) == 'rejeitado')>Rejeitado</option>
                            </select>
                            @error('status_validacao') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observação</label>
                            <textarea class="form-control" name="observacao" rows="4"
                                placeholder="Comentários sobre este arquivo...">{{ old('observacao', $arquivoEntrega->observacao) }}</textarea>
                            @error('observacao') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-floppy"></i> Atualizar
                            </button>
                            <a class="btn btn-outline-secondary" href="{{ route('arquivos_entrega.index') }}">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
