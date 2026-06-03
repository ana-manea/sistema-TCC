<div class="col-md-6 col-xl-4">
    <a href="{{ $rota }}" class="dashboard-card-link">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <div class="dashboard-card-icon"><i class="{{ $icone }}"></i></div>
                <h2 class="h5 mb-2">{{ $titulo }}</h2>
                <p class="text-muted mb-0">{{ $descricao }}</p>
            </div>

            @isset($rodape)
                <div class="card-footer bg-transparent text-muted small">{{ $rodape }}</div>
            @endisset
        </div>
    </a>
</div>
