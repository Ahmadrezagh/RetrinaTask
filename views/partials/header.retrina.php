<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-primary text-white border-0">
        <div class="d-flex align-items-center">
            <i class="bi bi-info-circle me-2"></i>
            <h5 class="card-title mb-0">
                {{{ $title ?? 'Component Header' }}}
            </h5>
        </div>
    </div>
    @isset($subtitle)
    <div class="card-body py-2">
        <small class="text-muted">
            <i class="bi bi-arrow-right me-1"></i>
            {{{ $subtitle }}}
        </small>
    </div>
    @endisset
</div> 