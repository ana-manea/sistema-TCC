@extends('layouts.app')

@section('title', 'Editar Reunião')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-calendar-event"></i> Editar Reunião
                </div>

                <div class="card-body">
                    <form action="{{ route('reunioes.update', $reuniao) }}" method="POST">
                        @method('PUT')
                        @include('reunioes._form')

                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-floppy"></i> Salvar
                            </button>
                            <a class="btn btn-outline-secondary" href="{{ route('reunioes.index') }}">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
