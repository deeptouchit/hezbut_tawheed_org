@extends('admin.layouts.master')

@section('page-title', 'বই ম্যানেজমেন্ট')

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
            <div class="col-md-4 col-sm-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="search-input" class="form-control" placeholder="শিরোনাম, স্ল্যাগ বা বিবরণ খুঁজুন..." autocomplete="off" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2 col-sm-6">
                <select id="category-filter" class="form-select">
                    <option value="">সব ক্যাটাগরি</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <select id="status-filter" class="form-select">
                    <option value="">সব স্ট্যাটাস</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <select id="per-page-filter" class="form-select">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>১০টি</option>
                    <option value="20" {{ request('per_page') == 20 || request('per_page') == '' ? 'selected' : '' }}>২০টি</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>৫০টি</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>১০০টি</option>
                    <option value="-1" {{ request('per_page') == -1 ? 'selected' : '' }}>সব</option>
                </select>
            </div>
            <div class="col-md-1 col-sm-6">
                <button id="reset-filter" class="btn btn-secondary w-100" title="রিসেট ফিল্টার" style="height: 38px;">
                    <i class="fas fa-undo-alt"></i>
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
        <!-- Total Books -->
        <div class="col-md-4 col-12">
            <div class="card metric-card border-left-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">মোট বই</span>
                            <h5 class="mb-0 fw-bold" id="metric-total">{{ number_format($stats['total'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Books -->
        <div class="col-md-4 col-6">
            <div class="card metric-card border-left-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">সক্রিয় বই</span>
                            <h5 class="mb-0 fw-bold" id="metric-active">{{ number_format($stats['active'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inactive Books -->
        <div class="col-md-4 col-6">
            <div class="card metric-card border-left-danger h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">নিষ্ক্রিয় বই</span>
                            <h5 class="mb-0 fw-bold" id="metric-inactive">{{ number_format($stats['inactive'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                            <i class="fas fa-times-circle"></i>
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
                <i class="fas fa-book me-2"></i> বইয়ের তালিকা
                <span class="badge bg-primary ms-2" id="total-count">{{ $books->total() ?? $books->count() }}</span>
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.books.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> নতুন বই যোগ করুন
                    </a>
                    <button id="refresh-btn" class="btn btn-info btn-sm" title="রিফ্রেশ">
                        <i class="fas fa-sync-alt text-white"></i>
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
                @include('admin.books.partials.table', ['books' => $books])
            </div>
        </div>
    </div>
</div>

<!-- Single Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-triangle"></i> নিশ্চিত করুন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>আপনি কি নিশ্চিত যে আপনি '<strong id="delete-item-name"></strong>' বইটি ডিলিট করতে চান?</p>
                <p class="text-danger small mb-0"><i class="fas fa-exclamation-triangle"></i> এই ফাইল ও ডাটা ডিলিট হলে আর ফেরত আনা যাবে না!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">
                    <i class="fas fa-trash"></i> ডিলিট করুন
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

    // Load data ajax
    function loadBooks(page = 1) {
        if (isAjaxLoading) return;

        var search = $('#search-input').val();
        var status = $('#status-filter').val();
        var categoryId = $('#category-filter').val();
        var perPage = $('#per-page-filter').val();

        isAjaxLoading = true;
        $('#loading-spinner').show();
        $('#table-container').hide();

        $.ajax({
            url: "{{ route('admin.books.index') }}",
            type: 'GET',
            data: {
                page: page,
                search: search,
                status: status,
                category_id: categoryId,
                per_page: perPage,
                _: Date.now()
            },
            dataType: 'json',
            success: function(response) {
                $('#loading-spinner').hide();
                $('#table-container').show();
                if (response.success && response.html) {
                    $('#table-container').html(response.html);
                    $('#total-count').text(response.total || 0);
                    $('#metric-total').text(response.total || 0);
                    attachEventHandlers();
                } else {
                    toastr.error('ডাটা লোড করতে ব্যর্থ হয়েছে');
                }
            },
            error: function() {
                $('#loading-spinner').hide();
                $('#table-container').show();
                toastr.error('সার্ভারে সমস্যা হয়েছে');
            },
            complete: function() {
                isAjaxLoading = false;
            }
        });
    }

    // Attach AJAX event listeners after re-rendering table
    function attachEventHandlers() {
        // Toggle status
        $('.toggle-status').off('click').on('click', function() {
            var id = $(this).data('id');

            $.ajax({
                url: '{{ url("admin/books") }}/' + id + '/toggle-status',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadBooks();
                        updateStats();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে');
                }
            });
        });

        // Toggle popular
        $('.toggle-popular').off('change').on('change', function() {
            var id = $(this).data('id');

            $.ajax({
                url: '{{ url("admin/books") }}/' + id + '/toggle-popular',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadBooks();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('জনপ্রিয় স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে');
                }
            });
        });

        // Toggle bestseller
        $('.toggle-bestseller').off('change').on('change', function() {
            var id = $(this).data('id');

            $.ajax({
                url: '{{ url("admin/books") }}/' + id + '/toggle-bestseller',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadBooks();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('বেস্ট সেলার স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে');
                }
            });
        });

        // Update popular order
        $('.update-order').off('change').on('change', function() {
            var id = $(this).data('id');
            var val = $(this).val();

            $.ajax({
                url: '{{ url("admin/books") }}/' + id + '/update-order',
                type: 'POST',
                data: { 
                    _token: '{{ csrf_token() }}',
                    popular_order: val
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadBooks();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('অর্ডারিং নম্বর আপডেট করতে ব্যর্থ হয়েছে');
                }
            });
        });

        // Single delete
        $('.delete-book').off('click').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');

            $('#delete-item-name').text(name);
            $('#deleteModal').modal('show');

            $('#confirm-delete').off('click').on('click', function() {
                $.ajax({
                    url: '{{ url("admin/books") }}/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        if (response.success) {
                            toastr.success(response.message);
                            loadBooks();
                            updateStats();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        $('#deleteModal').modal('hide');
                        toastr.error('বই ডিলিট করতে ব্যর্থ হয়েছে');
                    }
                });
            });
        });

        // Pagination links
        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var page = url.match(/page=(\d+)/) ? url.match(/page=(\d+)/)[1] : 1;
            if (page) loadBooks(page);
        });
    }

    // Update statistics card helper
    function updateStats() {
        $.ajax({
            url: "{{ route('admin.books.index') }}",
            type: 'GET',
            data: { stats_only: 1 },
            success: function(response) {
                // If the controller has stats endpoint, we can update them
                if (response.stats) {
                    $('#metric-total').text(response.stats.total);
                    $('#metric-active').text(response.stats.active);
                    $('#metric-inactive').text(response.stats.inactive);
                }
            }
        });
    }

    // Keyup search
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadBooks(), 500);
    });

    // Dropdowns
    $('#status-filter, #category-filter, #per-page-filter').on('change', function() {
        loadBooks();
    });

    // Reset
    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#status-filter').val('');
        $('#category-filter').val('');
        $('#per-page-filter').val('20');
        loadBooks();
    });

    // Refresh btn
    $('#refresh-btn').on('click', function() {
        loadBooks();
        updateStats();
        toastr.success('ডাটা রিফ্রেশ করা হয়েছে');
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
