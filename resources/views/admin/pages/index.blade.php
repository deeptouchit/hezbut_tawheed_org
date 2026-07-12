@extends('admin.layouts.master')

@section('page-title', 'পেজ ম্যানেজমেন্ট')

@push('styles')
<style>
    .metric-card {
        border-radius: 6px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
    }
    .metric-card .card-body {
        padding: 8px 12px !important;
    }
    .border-left-primary { border-left: 3px solid #006A4E !important; }
    .border-left-success { border-left: 3px solid #2e7d32 !important; }
    .border-left-info { border-left: 3px solid #0288d1 !important; }
    .border-left-warning { border-left: 4px solid #f57c00 !important; }
    .border-left-danger { border-left: 4px solid #d32f2f !important; }
    .border-left-secondary { border-left: 4px solid #757575 !important; }

    .stat-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
</style>
@endpush

@section('filter_input')
<!-- Filter Section -->
<div class="card border shadow-none mb-2 bg-light-subtle">
    <div class="card-body p-2">
        <div class="row g-2">
            <div class="col-md-6 col-sm-12">
                <input type="text" id="search-input" class="form-control" placeholder="শিরোনাম, স্ল্যাগ বা কনটেন্ট খুঁজুন..." autocomplete="off" value="{{ request('search') }}">
            </div>
            <div class="col-md-2 col-sm-4">
                <select id="status-filter" class="form-select">
                    <option value="">সব স্ট্যাটাস</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>লাইভ (Live)</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>খসড়া (Draft)</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-4">
                <select id="per-page-filter" class="form-select">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>১০টি</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : (request('per_page') == '' ? 'selected' : '') }}>২০টি</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>৫০টি</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>১০০টি</option>
                    <option value="-1" {{ request('per_page') == -1 ? 'selected' : '' }}>সব</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-4">
                <button id="reset-filter" class="btn btn-secondary w-100" title="রিসেট ফিল্টার" style="height: 38px;">
                    <i class="fas fa-undo-alt"></i> রিসেট
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row g-2 mb-3">
        <!-- Total Pages -->
        <div class="col-md-4 col-4">
            <div class="card metric-card border-left-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">মোট পেজ</span>
                            <h5 class="mb-0 fw-bold" id="stats-total">{{ number_format($stats['total'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Pages -->
        <div class="col-md-4 col-4">
            <div class="card metric-card border-left-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">লাইভ (Live)</span>
                            <h5 class="mb-0 fw-bold" id="stats-active">{{ number_format($stats['active'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Draft Pages -->
        <div class="col-md-4 col-4">
            <div class="card metric-card border-left-secondary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">খসড়া (Draft)</span>
                            <h5 class="mb-0 fw-bold" id="stats-inactive">{{ number_format($stats['inactive'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                            <i class="fas fa-edit"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-copy me-2"></i> পেজ তালিকা
                <span class="badge bg-primary ms-2" id="total-count">{{ $pages->total() ?? $pages->count() }}</span>
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> নতুন পেজ তৈরি
                    </a>
                    <button id="refresh-btn" class="btn btn-info btn-sm" title="রিফ্রেশ">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-1">
            <!-- Loading Spinner -->
            <div id="loading-spinner" class="text-center py-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">ডাটা লোড হচ্ছে...</p>
            </div>

            <!-- Table Container -->
            <div id="table-container">
                @include('admin.pages.partials.table')
            </div>
        </div>
    </div>
</div>

<!-- Single Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">পেজ ডিলিট কনফার্মেশন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>আপনি কি নিশ্চিত যে আপনি '<strong id="delete-item-name"></strong>' পেজটি ডিলিট করতে চান?</p>
                <p class="text-danger small mb-0"><i class="fas fa-exclamation-triangle"></i> এই কাজটি ফেরানো সম্ভব নয়!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">ডিলিট করুন</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var searchTimeout = null;

    // Load data ajax
    function loadPages(page = 1) {
        var search = $('#search-input').val();
        var status = $('#status-filter').val();
        var perPage = $('#per-page-filter').val();

        $('#loading-spinner').show();
        $('#table-container').hide();

        $.ajax({
            url: "{{ route('admin.pages.index') }}",
            type: 'GET',
            data: {
                page: page,
                search: search,
                status: status,
                per_page: perPage
            },
            success: function(response) {
                $('#loading-spinner').hide();
                $('#table-container').show();
                if (response.success) {
                    $('#table-container').html(response.html);
                    $('#total-count').text(response.total);
                    
                    if (response.stats) {
                        $('#stats-total').text(response.stats.total);
                        $('#stats-active').text(response.stats.active);
                        $('#stats-inactive').text(response.stats.inactive);
                    }
                    
                    attachEventHandlers();
                } else {
                    toastr.error('ডাটা লোড করতে ব্যর্থ হয়েছে');
                }
            },
            error: function() {
                $('#loading-spinner').hide();
                $('#table-container').show();
                toastr.error('সার্ভারে সমস্যা হয়েছে');
            }
        });
    }

    // Attach AJAX event listeners after re-rendering table
    function attachEventHandlers() {
        // Toggle status
        $('.toggle-status').off('click').on('click', function() {
            var id = $(this).data('id');
            var btn = $(this);

            $.ajax({
                url: '{{ url("admin/pages") }}/' + id + '/toggle-status',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadPages();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে');
                }
            });
        });

        // Single delete
        $('.delete-page').off('click').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');

            $('#delete-item-name').text(name);
            $('#deleteModal').modal('show');

            $('#confirm-delete').off('click').on('click', function() {
                $.ajax({
                    url: '{{ url("admin/pages") }}/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        if (response.success) {
                            toastr.success(response.message);
                            loadPages();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(response) {
                        $('#deleteModal').modal('hide');
                        toastr.error('পেজ ডিলিট করতে ব্যর্থ হয়েছে');
                    }
                });
            });
        });

        // Pagination links
        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var page = url.match(/page=(\d+)/) ? url.match(/page=(\d+)/)[1] : 1;
            if (page) loadPages(page);
        });
    }

    // Keyup search
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadPages(), 500);
    });

    // Dropdowns
    $('#status-filter, #per-page-filter').on('change', function() {
        loadPages();
    });

    // Reset
    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#status-filter').val('');
        $('#per-page-filter').val('20');
        loadPages();
    });

    // Refresh Button
    $('#refresh-btn').on('click', function() {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        loadPages();
        setTimeout(function() {
            btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i>');
        }, 1000);
    });

    // Initial event attach
    attachEventHandlers();

    // Toastr setup
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };
});
</script>
@endpush
