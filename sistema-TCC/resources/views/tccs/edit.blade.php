@extends('layouts.app')

@section('title', 'Editar TCC')

@section('content')
    <div>
        <div>
            <div>
                <div>
                    <i class="bi bi-pencil-square"></i> Editar TCC — {{ $tcc->tema }}
                </div>

                <div>
                    <form action="{{ route('tccs.update', $tcc) }}" method="POST">
                        @method('PUT')
                        @include('tccs._form')

                        <div>
                            <button type="submit">
                                <i class="bi bi-floppy"></i> Salvar alterações
                            </button>
                            <a href="{{ route('tccs.show', $tcc) }}">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

