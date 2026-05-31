@extends('layouts.app')

@section('title', 'Nova Banca')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">Cadastrar Nova Banca Avaliadora</div>
                        
                <div class="card-body">
                    <form action="{{ route('bancas.store') }}" method="POST">
                        @csrf 

                        <div class="mb-3">
                            <label class="form-label"  for="tcc_id">Cód. TCC:</label>
                            <input class="form-control" type="number" name="tcc_id" id="tcc_id" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="data_hora">Data e Hora:</label>
                            <input class="form-control" type="datetime-local" name="data_hora" id="data_hora" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="local">Local:</label>
                            <input class="form-control" type="text" name="local" id="local" placeholder="Ex: Sala 4 ou Link" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="status">Status Inicial:</label>
                            <select class="form-select" name="status" id="status" required>
                                <option value="agendada">Agendada</option>
                                <option value="realizada">Realizada</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Salvar
                            </button>
                            
                            <a role="button" href="{{ route('bancas.index') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection