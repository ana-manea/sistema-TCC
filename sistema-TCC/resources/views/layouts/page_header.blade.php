<div class="page-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
    <div>
        <h1 class="h3 mb-1">{{ $titulo }}</h1>
        @isset($subtitulo)
            <p class="text-muted mb-0">{{ $subtitulo }}</p>
        @endisset
    </div>

    @isset($acao)
        <div>{{ $acao }}</div>
    @endisset
</div>
