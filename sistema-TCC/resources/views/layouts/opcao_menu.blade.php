@php
    $ativo = request()->routeIs(($routeName ?? '') . '*');
@endphp

<a href="{{ $rota }}" class="list-group-item list-group-item-action dashboard-menu-item {{ $ativo ? 'active' : '' }}">
    <i class="{{ $icone }}"></i>
    <span>{{ $opcao }}</span>
    {!! $extra ?? '' !!}
</a>
