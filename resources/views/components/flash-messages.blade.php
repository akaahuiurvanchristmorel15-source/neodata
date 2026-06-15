@if(session()->has('success'))
    <div class="alert alert-success" data-flash-type="success" role="alert">
        <span>✓</span> 
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session()->has('error'))
    <div class="alert alert-error" data-flash-type="error" role="alert">
        <span>✕</span> 
        <span>{{ session('error') }}</span>
    </div>
@endif

@if(session()->has('warning'))
    <div class="alert alert-warning" data-flash-type="warning" role="alert">
        <span>⚠</span> 
        <span>{{ session('warning') }}</span>
    </div>
@endif

@if(session()->has('info'))
    <div class="alert alert-info" data-flash-type="info" role="alert">
        <span>ℹ</span> 
        <span>{{ session('info') }}</span>
    </div>
@endif

<style>
    /* ── Alerts ── */
    .alert {
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        border-left: 4px solid;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.95rem;
        animation: slideInDown 0.3s ease-out;
    }

    .alert span:first-child {
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .alert-success {
        background-color: #ecfdf5;
        border-color: #10b981;
        color: #047857;
    }

    .alert-error {
        background-color: #fef2f2;
        border-color: #ef4444;
        color: #991b1b;
    }

    .alert-warning {
        background-color: #fffbeb;
        border-color: #f59e0b;
        color: #92400e;
    }

    .alert-info {
        background-color: #eff6ff;
        border-color: #3b82f6;
        color: #1e40af;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
