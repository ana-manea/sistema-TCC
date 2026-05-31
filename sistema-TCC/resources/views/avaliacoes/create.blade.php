@extends('layouts.app')

@section('title', 'Nova Avaliação')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card-header">Avaliação da Banca — TCC Nº {{ $banca->tcc_id }}</div>
            <hr>
            @if ($errors->any())
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            @endif
            <div class="card-body">
                <form action="{{ route('avaliacoes.store', $banca->id) }}" method="POST">
                    @csrf

                    <div style="margin-bottom: 15px;">
                        <label for="nota" style="display: block; font-weight: bold;">Nota da Avaliação (0 a 10):</label>
                        <input type="number" step="0.1" min="0" max="10" name="nota" id="nota" value="{{ old('nota') }}" required>
                        @error('nota')
                            <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label for="parecer" style="display: block; font-weight: bold;">Parecer / Justificativa:</label>
                        <textarea name="parecer" id="parecer" rows="5" cols="50" placeholder="Digite aqui as considerações e feedbacks..." required>{{ old('parecer') }}</textarea>
                        @error('parecer')
                            <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Salvar
                        </button>
                        <a role="button" class="btn btn-outline-secondary" href="{{ route('bancas.index') }}">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection