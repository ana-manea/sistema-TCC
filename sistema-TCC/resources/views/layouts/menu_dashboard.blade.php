{{-- Menu lateral reaproveitável dos dashboards.
     Recebe $usuario (nome do perfil) e $opcoes (atalhos permitidos para o perfil). --}}
<aside class="col-lg-3 col-xl-2 dashboard-sidebar">
    <div class="dashboard-sidebar-inner">
        <p class="dashboard-sidebar-title">Menu do {{ $usuario }}</p>

        <div class="list-group list-group-flush dashboard-menu">
            @foreach ($opcoes as $op)
                @include('layouts.opcao_menu', $op)
            @endforeach
        </div>
    </div>
</aside>
