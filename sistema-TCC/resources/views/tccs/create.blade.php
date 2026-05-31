@extends('layouts.app')

@section('title', 'Novo TCC')

@section('content')
    <div>
        <div>
            <div>
                <div>
                    <i class="bi bi-journal-plus"></i> Novo Trabalho de Conclusão de Curso
                </div>

                <div>
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
