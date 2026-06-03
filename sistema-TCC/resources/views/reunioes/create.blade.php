@extends('layouts.app')

@section('title', 'Nova Reunião')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-calendar-plus"></i> Nova Reunião
                </div>

                <div class="card-body">
                    <form action="{{ route('reunioes.store') }}" method="POST" class="vstack gap-3">
                        @include('reunioes._form')

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Salvar
                            </button>
                            <a class="btn btn-outline-secondary" href="{{ route('reunioes.index') }}">
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
