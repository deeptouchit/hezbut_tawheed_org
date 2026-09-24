@if(session('message'))
    <div class="alert alert-{{ session('alert-type', 'info') }} alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-{{ session('alert-type') === 'success' ? 'check-circle' : (session('alert-type') === 'error' ? 'exclamation-circle' : 'info-circle') }} me-2"></i>
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
