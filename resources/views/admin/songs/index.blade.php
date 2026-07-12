@extends('admin.layouts.master')

@section('page-title', '🎵 গান ও লিরিক্স ম্যানেজমেন্ট')

@push('styles')
<style>
    /* ===== MODERN METRIC CARDS ===== */
    .metric-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .metric-card-modern {
        background: var(--bs-card-bg, #ffffff);
        border-radius: 16px;
        padding: 16px 20px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.04);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        cursor: default;
    }

    .metric-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        border-radius: 16px 16px 0 0;
    }

    .metric-card-modern:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.10);
    }

    .metric-card-modern.primary::before { background: linear-gradient(90deg, #006A4E, #00A876); }
    .metric-card-modern.success::before { background: linear-gradient(90deg, #2e7d32, #66bb6a); }
    .metric-card-modern.danger::before { background: linear-gradient(90deg, #d32f2f, #ef5350); }
    .metric-card-modern.info::before { background: linear-gradient(90deg, #0288d1, #4fc3f7); }

    .metric-card-modern .metric-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .metric-card-modern:hover .metric-icon {
        transform: scale(1.05) rotate(-3deg);
    }

    .metric-card-modern .metric-icon.primary { background: rgba(0, 106, 78, 0.12); color: #006A4E; }
    .metric-card-modern .metric-icon.success { background: rgba(46, 125, 50, 0.12); color: #2e7d32; }
    .metric-card-modern .metric-icon.danger { background: rgba(211, 47, 47, 0.12); color: #d32f2f; }
    .metric-card-modern .metric-icon.info { background: rgba(2, 136, 209, 0.12); color: #0288d1; }

    .metric-card-modern .metric-value {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1.2;
        letter-spacing: -0.02em;
        color: var(--bs-heading-color, #1a1a2e);
    }

    .metric-card-modern .metric-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--bs-secondary-color, #6c757d);
        margin-top: 2px;
    }

    .metric-card-modern .metric-trend {
        font-size: 0.65rem;
        font-weight: 600;
        padding: 2px 10px;
        border-radius: 20px;
        background: rgba(46, 125, 50, 0.12);
        color: #2e7d32;
    }

    /* ===== FILTER BAR ===== */
    .filter-bar {
        background: var(--bs-card-bg, #ffffff);
        border-radius: 16px;
        padding: 14px 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.04);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
    }

    .filter-bar .filter-group {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1 1 200px;
    }

    .filter-bar .filter-group .input-group {
        flex: 1;
    }

    .filter-bar .filter-group .input-group-text {
        background: transparent;
        border: none;
        padding: 0 0 0 12px;
        color: var(--bs-secondary-color, #6c757d);
    }

    .filter-bar .filter-group .form-control,
    .filter-bar .filter-group .form-select {
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 0.875rem;
        background: var(--bs-body-bg, #f8f9fa);
        transition: all 0.2s ease;
        height: 40px;
    }

    .filter-bar .filter-group .form-control:focus,
    .filter-bar .filter-group .form-select:focus {
        border-color: #006A4E;
        box-shadow: 0 0 0 3px rgba(0, 106, 78, 0.15);
        background: var(--bs-card-bg, #ffffff);
    }

    .filter-bar .filter-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .filter-bar .btn-reset {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, 0.08);
        background: var(--bs-body-bg, #f8f9fa);
        color: var(--bs-secondary-color, #6c757d);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .filter-bar .btn-reset:hover {
        background: #e9ecef;
        color: #1a1a2e;
        transform: rotate(180deg);
    }

    /* ===== TABLE CARD ===== */
    .table-card {
        background: var(--bs-card-bg, #ffffff);
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .table-card .card-header-custom {
        padding: 16px 24px;
        background: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .table-card .card-header-custom .header-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-card .card-header-custom .header-title h5 {
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
        color: var(--bs-heading-color, #1a1a2e);
    }

    .table-card .card-header-custom .header-title .badge-count {
        background: rgba(0, 106, 78, 0.12);
        color: #006A4E;
        font-weight: 700;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
    }

    .table-card .card-header-custom .header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-card .card-header-custom .header-actions .btn-primary-custom {
        background: linear-gradient(135deg, #006A4E, #00875c);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-card .card-header-custom .header-actions .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 106, 78, 0.30);
    }

    .table-card .card-header-custom .header-actions .btn-ghost {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, 0.06);
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--bs-secondary-color, #6c757d);
        transition: all 0.2s ease;
    }

    .table-card .card-header-custom .header-actions .btn-ghost:hover {
        background: var(--bs-body-bg, #f8f9fa);
        color: #1a1a2e;
    }

    /* ===== TABLE STYLING ===== */
    .table-modern {
        margin-bottom: 0;
        font-size: 0.875rem;
    }

    .table-modern thead th {
        background: var(--bs-body-bg, #f8f9fa);
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 12px 16px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        color: var(--bs-secondary-color, #6c757d);
        position: sticky;
        top: 0;
        z-index: 5;
    }

    .table-modern tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
    }

    .table-modern tbody tr:last-child {
        border-bottom: none;
    }

    .table-modern tbody tr:hover {
        background: rgba(0, 106, 78, 0.03);
    }

    .table-modern tbody td {
        padding: 12px 16px;
        vertical-align: middle;
        color: var(--bs-body-color, #212529);
    }

    .table-modern .song-title {
        font-weight: 600;
        color: var(--bs-heading-color, #1a1a2e);
    }

    .table-modern .song-category {
        display: inline-block;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .table-modern .song-category.national { background: rgba(0, 106, 78, 0.12); color: #006A4E; }
    .table-modern .song-category.awakening { background: rgba(211, 47, 47, 0.12); color: #d32f2f; }
    .table-modern .song-category.party_anthem { background: rgba(2, 136, 209, 0.12); color: #0288d1; }

    /* ===== STATUS BADGE ===== */
    .badge-status {
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-status.active {
        background: rgba(46, 125, 50, 0.12);
        color: #2e7d32;
    }

    .badge-status.inactive {
        background: rgba(211, 47, 47, 0.12);
        color: #d32f2f;
    }

    .badge-status .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .badge-status.active .dot { background: #2e7d32; }
    .badge-status.inactive .dot { background: #d32f2f; }

    /* ===== ACTION BUTTONS ===== */
    .action-group {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .action-group .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        transition: all 0.2s ease;
        color: var(--bs-secondary-color, #6c757d);
        background: transparent;
    }

    .action-group .btn-action:hover {
        background: var(--bs-body-bg, #f8f9fa);
        color: #1a1a2e;
    }

    .action-group .btn-action.edit:hover { background: rgba(0, 106, 78, 0.10); color: #006A4E; }
    .action-group .btn-action.delete:hover { background: rgba(211, 47, 47, 0.10); color: #d32f2f; }
    .action-group .btn-action.play:hover { background: rgba(2, 136, 209, 0.10); color: #0288d1; }

    /* ===== LOADING ===== */
    .loading-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 50;
        border-radius: 16px;
    }

    .loading-overlay .spinner-custom {
        width: 40px;
        height: 40px;
        border: 3px solid rgba(0, 106, 78, 0.10);
        border-top-color: #006A4E;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* ===== PAGINATION ===== */
    .pagination-modern {
        padding: 12px 20px;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .pagination-modern .pagination-info {
        font-size: 0.85rem;
        color: var(--bs-secondary-color, #6c757d);
    }

    .pagination-modern .pagination-links .pagination {
        margin: 0;
        gap: 4px;
    }

    .pagination-modern .pagination-links .page-link {
        border: none;
        padding: 6px 14px;
        border-radius: 8px;
        color: var(--bs-body-color, #212529);
        font-weight: 500;
        font-size: 0.85rem;
        background: transparent;
        transition: all 0.2s ease;
    }

    .pagination-modern .pagination-links .page-link:hover {
        background: rgba(0, 106, 78, 0.08);
        color: #006A4E;
    }

    .pagination-modern .pagination-links .active .page-link {
        background: linear-gradient(135deg, #006A4E, #00875c);
        color: white;
        box-shadow: 0 4px 12px rgba(0, 106, 78, 0.30);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .metric-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .metric-card-modern .metric-value {
            font-size: 1.3rem;
        }
        .filter-bar {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-bar .filter-group {
            flex: 1 1 100%;
        }
        .table-card .card-header-custom {
            flex-direction: column;
            align-items: stretch;
        }
        .table-card .card-header-custom .header-actions {
            justify-content: stretch;
        }
        .table-card .card-header-custom .header-actions .btn-primary-custom {
            flex: 1;
            justify-content: center;
        }
        .table-modern {
            font-size: 0.75rem;
        }
        .table-modern thead th,
        .table-modern tbody td {
            padding: 8px 10px;
        }
        .action-group .btn-action {
            width: 28px;
            height: 28px;
            font-size: 0.65rem;
        }
        .pagination-modern {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .metric-grid {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .metric-card-modern {
            padding: 12px 14px;
        }
        .metric-card-modern .metric-value {
            font-size: 1.1rem;
        }
        .metric-card-modern .metric-icon {
            width: 32px;
            height: 32px;
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    <!-- ===== METRIC CARDS ===== -->
    <div class="metric-grid">
        <!-- Total Songs -->
        <div class="metric-card-modern primary">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="metric-value" id="metric-total">{{ number_format($stats['total'] ?? 0) }}</div>
                    <div class="metric-label">মোট গান</div>
                </div>
                <div class="metric-icon primary">
                    <i class="fas fa-music"></i>
                </div>
            </div>
        </div>

        <!-- Active Songs -->
        <div class="metric-card-modern success">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="metric-value" id="metric-active">{{ number_format($stats['active'] ?? 0) }}</div>
                    <div class="metric-label">সক্রিয় গান</div>
                </div>
                <div class="metric-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <!-- Inactive Songs -->
        <div class="metric-card-modern danger">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="metric-value" id="metric-inactive">{{ number_format($stats['inactive'] ?? 0) }}</div>
                    <div class="metric-label">নিষ্ক্রিয় গান</div>
                </div>
                <div class="metric-icon danger">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="metric-card-modern info">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="metric-value">{{ count($categories ?? []) }}</div>
                    <div class="metric-label">ক্যাটাগরি</div>
                </div>
                <div class="metric-icon info">
                    <i class="fas fa-tags"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== FILTER BAR ===== -->
    <div class="filter-bar">
        <div class="filter-group">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" id="search-input" class="form-control" placeholder="শিরোনাম বা লিরিক্স খুঁজুন..." autocomplete="off" value="{{ request('search') }}">
            </div>
        </div>

        <div class="filter-group" style="flex: 0 0 160px;">
            <select id="status-filter" class="form-select">
                <option value="">সব স্ট্যাটাস</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
            </select>
        </div>

        <div class="filter-group" style="flex: 0 0 120px;">
            <select id="per-page-filter" class="form-select">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>১০</option>
                <option value="15" {{ request('per_page') == 15 || request('per_page') == '' ? 'selected' : '' }}>১৫</option>
                <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>৩০</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>৫০</option>
                <option value="-1" {{ request('per_page') == -1 ? 'selected' : '' }}>সব</option>
            </select>
        </div>

        <div class="filter-actions">
            <button id="reset-filter" class="btn-reset" title="ফিল্টার রিসেট">
                <i class="fas fa-undo-alt"></i>
            </button>
        </div>
    </div>

    <!-- ===== TABLE CARD ===== -->
    <div class="table-card position-relative" id="table-wrapper">
        <!-- Loading Overlay -->
        <div id="loading-overlay" class="loading-overlay" style="display: none;">
            <div class="spinner-custom"></div>
        </div>

        <!-- Header -->
        <div class="card-header-custom">
            <div class="header-title">
                <h5><i class="fas fa-music me-2 text-primary"></i>গান ও লিরিক্স তালিকা</h5>
                <span class="badge-count" id="total-count">{{ $songs->total() ?? $songs->count() }}</span>
            </div>
            <div class="header-actions">
                <button id="refresh-btn" class="btn-ghost" title="রিফ্রেশ">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <a href="{{ route('admin.songs.create') }}" class="btn-primary-custom">
                    <i class="fas fa-plus"></i> নতুন গান
                </a>
            </div>
        </div>

        <!-- Table Container -->
        <div id="table-container" style="overflow-x: auto;">
            @include('admin.songs.partials.table', ['songs' => $songs])
        </div>
    </div>
</div>

<!-- ===== DELETE MODAL ===== -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
            <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
                <h5 class="modal-title fw-bold" style="color: #d32f2f;">
                    <i class="fas fa-exclamation-triangle me-2"></i>নিশ্চিত করুন
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 20px 24px;">
                <p style="font-size: 0.95rem; margin-bottom: 4px;">
                    "<strong id="delete-item-name" style="color: #1a1a2e;"></strong>" গানটি মুছতে চান?
                </p>
                <p class="text-muted small" style="font-size: 0.8rem;">
                    <i class="fas fa-info-circle me-1"></i> এই অপারেশন পুনরুদ্ধারযোগ্য নয়।
                </p>
            </div>
            <div class="modal-footer" style="border-top: none; padding-top: 0; gap: 8px;">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 500;">বাতিল</button>
                <button type="button" class="btn btn-danger" id="confirm-delete" style="border-radius: 10px; font-weight: 600; padding: 8px 24px;">
                    <i class="fas fa-trash me-1"></i> মুছুন
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var searchTimeout = null;
    var isAjaxLoading = false;

    // ===== LOAD SONGS =====
    function loadSongs(page = 1) {
        if (isAjaxLoading) return;

        var search = $('#search-input').val();
        var status = $('#status-filter').val();
        var perPage = $('#per-page-filter').val();

        isAjaxLoading = true;
        $('#loading-overlay').fadeIn(200);

        $.ajax({
            url: "{{ route('admin.songs.index') }}",
            type: 'GET',
            data: {
                page: page,
                search: search,
                status: status,
                per_page: perPage,
                _: Date.now()
            },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.html) {
                    $('#table-container').html(response.html);
                    $('#total-count').text(response.total || 0);
                    $('#metric-total').text(response.total || 0);

                    // Update active/inactive metrics if available
                    if (response.stats) {
                        $('#metric-active').text(response.stats.active || 0);
                        $('#metric-inactive').text(response.stats.inactive || 0);
                    }

                    attachEventHandlers();
                } else {
                    toastr.error('ডাটা লোড করতে ব্যর্থ হয়েছে');
                }
            },
            error: function() {
                toastr.error('সার্ভারে সমস্যা হয়েছে');
            },
            complete: function() {
                $('#loading-overlay').fadeOut(200);
                isAjaxLoading = false;
            }
        });
    }

    // ===== UPDATE METRICS =====
    function updateMetrics() {
        $.ajax({
            url: "{{ route('admin.songs.index') }}",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success && response.stats) {
                    $('#metric-total').text(response.stats.total || 0);
                    $('#metric-active').text(response.stats.active || 0);
                    $('#metric-inactive').text(response.stats.inactive || 0);
                }
            }
        });
    }

    // ===== ATTACH EVENT HANDLERS =====
    function attachEventHandlers() {
        // Toggle Status
        $('.toggle-status').off('click').on('click', function() {
            var btn = $(this);
            var id = btn.data('id');
            btn.prop('disabled', true);

            $.ajax({
                url: "{{ url('admin/songs') }}/" + id + "/toggle-status",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadSongs();
                    } else {
                        toastr.error(response.message || 'ব্যর্থ হয়েছে');
                    }
                },
                error: function() {
                    toastr.error('সার্ভারে সমস্যা হয়েছে');
                },
                complete: function() {
                    btn.prop('disabled', false);
                }
            });
        });

        // Delete Modal Trigger
        $('.delete-song').off('click').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#delete-item-name').text(name);
            $('#confirm-delete').data('id', id);
            $('#deleteModal').modal('show');
        });

        // Pagination
        $('.pagination a').off('click').on('click', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            if (page) loadSongs(page);
        });

        // Play Preview (if audio exists)
        $('.play-preview').off('click').on('click', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            if (url) {
                // Simple preview - can be enhanced with a modal player
                var audio = new Audio(url);
                audio.play();
                toastr.info('🎵 গান বাজানো হচ্ছে...');
            }
        });
    }

    // ===== CONFIRM DELETE =====
    $('#confirm-delete').on('click', function() {
        var id = $(this).data('id');
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> মুছছি...');

        $.ajax({
            url: "{{ url('admin/songs') }}/" + id,
            type: 'DELETE',
            data: {
                _token: "{{ csrf_token() }}"
            },
            dataType: 'json',
            success: function(response) {
                $('#deleteModal').modal('hide');
                if (response.success) {
                    toastr.success(response.message);
                    loadSongs();
                } else {
                    toastr.error(response.message || 'মুছে ফেলা সম্ভব হয়নি');
                }
            },
            error: function() {
                $('#deleteModal').modal('hide');
                toastr.error('সার্ভারে সমস্যা হয়েছে');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-trash me-1"></i> মুছুন');
            }
        });
    });

    // ===== FILTER EVENTS =====
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            loadSongs(1);
        }, 500);
    });

    $('#status-filter, #per-page-filter').on('change', function() {
        loadSongs(1);
    });

    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#status-filter').val('');
        $('#per-page-filter').val('15');
        loadSongs(1);
    });

    $('#refresh-btn').on('click', function() {
        $(this).find('i').addClass('fa-spin');
        loadSongs();
        setTimeout(function() {
            $('#refresh-btn i').removeClass('fa-spin');
        }, 800);
    });

    // ===== INITIALIZE =====
    attachEventHandlers();
});
</script>
@endpush
