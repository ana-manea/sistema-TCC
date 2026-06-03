
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistema de Gestão de TCC')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-mortarboard"></i>
                Sistema de TCC
            </a>


            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            
            <div id="mainNav" class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orientadores.index') }}">
                            <i class="bi bi-person-badge"></i> Orientadores
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orientandos.index') }}">
                            <i class="bi bi-person"></i> Orientandos
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tccs.index') }}">
                            <i class="bi bi-journal-text"></i> TCCs
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reunioes.index') }}">
                            <i class="bi bi-calendar-event"></i> Reuniões
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.index') }}">
                            <i class="bi bi-upload"></i> Entregas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('bancas.index') }}">
                            <i class="bi bi-award"></i> Bancas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.index') }}">
                            <i class="bi bi-people"></i> Usuários
                        </a>
                    </li>

                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">
                                <i class="bi bi-box-arrow-right"></i> Sair
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container-fluid">
        @if(session('sucesso'))
            <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('sucesso') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="border-top py-3">
        <div class="container text-muted small text-center">
            Fatec Prudente – Programação Web (Laravel)
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
