@extends('layouts.app')


@section('content')
<div class="container mt-4">
    <h3>Novo Feedback</h3>
    <form action="{{ route('feedbacks.store') }}" method="POST">
        @csrf
        <input type="hidden" name="tcc_id" value="{{ $tccId }}">
        <input type="hidden" name="orientador_id" value="3">


        @include('feedbacks._form')


        <button type="submit" class="btn btn-primary">Salvar Feedback</button>
        <a href="{{ route('tccs.show', $tccId) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
