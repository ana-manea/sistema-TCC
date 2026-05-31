
<div class="col-md-3 col-lg-2 px-0 bg-light border-end min-vh-100">
    <div class="p-3">
        <h5 class="text-muted text-uppercase fs-7 fw-bold">Menu do {{ $usuario }}</h5>
    </div>
    <div class="list-group list-group-flush">
        @foreach ($opcoes as $op)
            @include('layouts.opcao_menu', $op)
        @endforeach
    </div>
</div>
