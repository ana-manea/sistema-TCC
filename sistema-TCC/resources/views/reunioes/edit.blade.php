@extends('layouts.app')

@section('title', 'Editar Reunião')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">Editar Reunião</div>

                <div class="card-body">
                    <form action="{{ route('reunioes.update', $reuniao) }}" method="POST" class="vstack gap-3">
                        @method('PUT')
                        @include('reunioes._form', ['reuniao' => $reuniao])

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Salvar
                            </button>

                            <a href="{{ route('reunioes.index') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
