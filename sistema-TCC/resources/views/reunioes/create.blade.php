@extends('layouts.app')

@section('title', 'Nova Reunião')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="bi bi-calendar-plus"></i> Nova Reunião
                </div>
                <div class="card-body">
                    <form action="{{ route('reunioes.store') }}" method="POST">
                        @include('reunioes._form')

                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-primary" type="submit">
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
</div>
@endsection
