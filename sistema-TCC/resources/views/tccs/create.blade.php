@extends('layouts.app')

@section('title', 'Novo TCC')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-journal-plus"></i> Novo Trabalho de Conclusão de Curso
                </div>

                <div class="card-body">
                    <form action="{{ route('tccs.store') }}" method="POST">
                        @include('tccs._form')

                        <div>
                            <button type="submit">
                                <i class="bi bi-floppy"></i> Salvar
                            </button>
                            <a href="{{ route('tccs.index') }}">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
