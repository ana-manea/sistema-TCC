@php
    $nomes = explode(' ', trim($user->name));
    $iniciais = strtoupper(substr($nomes[0], 0, 1) . (count($nomes) > 1 ? substr(end($nomes), 0, 1) : ''));
    $corAvatar = $user->avatar ?? '#b20000';
@endphp

<div class="card dashboard-hero mb-4">
    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-user" @style(['background-color: ' . $corAvatar])>{{ $iniciais }}</div>

            <div>
                <span class="badge text-bg-light mb-2">{{ $perfil }}</span>
                <h1 class="h3 mb-1">{{ $titulo }}</h1>
                <p class="mb-0 text-muted">{{ $user->name }} — {{ $user->email }}</p>
            </div>
        </div>

        <a href="{{ route('users.perfil') }}" class="btn btn-primary">
            <i class="bi bi-person-circle"></i> Meu Perfil
        </a>
    </div>
</div>
