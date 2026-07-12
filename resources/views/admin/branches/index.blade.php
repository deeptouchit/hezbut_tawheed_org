{{-- resources/views/admin/branches/index.blade.php --}}

@extends('admin.layouts.master')

@section('page-title', 'কার্যালয় ও শাখা ব্যবস্থাপনা')

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
                <input type="text" id="search-input" class="form-control" placeholder="কার্যালয়ের নাম, ঠিকানা বা কর্মকর্তার নাম..." autocomplete="off" value="{{ request('search') }}">
            </div>
            <div class="col-md-3 col-sm-6">
                <select id="type-filter" class="form-select">
                    <option value="">সব কার্যালয়</option>
                    <option value="central" {{ request('type') == 'central' ? 'selected' : '' }}>কেন্দ্রীয় কার্যালয়</option>
                    <option value="division" {{ request('type') == 'division' ? 'selected' : '' }}>বিভাগীয় কার্যালয়</option>
                    <option value="district" {{ request('type') == 'district' ? 'selected' : '' }}>জেলা কার্যালয়</option>
                    <option value="upazila" {{ request('type') == 'upazila' ? 'selected' : '' }}>উপজেলা কার্যালয়</option>
                    <option value="international" {{ request('type') == 'international' ? 'selected' : '' }}>আন্তর্জাতিক শাখা</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <select id="per-page-filter" class="form-select">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>১০টি</option>
                    <option value="15" {{ request('per_page') == 15 ? 'selected' : (request('per_page') == '' ? 'selected' : '') }}>১৫টি</option>
                    <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>৩০টি</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>৫০টি</option>
                    <option value="-1" {{ request('per_page') == -1 ? 'selected' : '' }}>সব</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
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
        <!-- Total Branches -->
        <div class="col-md-2 col-sm-6 col-12">
            <div class="card metric-card border-left-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">মোট কার্যালয়</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['total'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Central Branches -->
        <div class="col-md-2 col-sm-6 col-12">
            <div class="card metric-card border-left-danger h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">কেন্দ্রীয় কার্যালয়</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['central'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                            <i class="fas fa-hotel"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Division Branches -->
        <div class="col-md-2 col-sm-6 col-12">
            <div class="card metric-card border-left-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">বিভাগীয় কার্যালয়</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['division'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-city"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- District Branches -->
        <div class="col-md-2 col-sm-6 col-12">
            <div class="card metric-card border-left-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">জেলা কার্যালয়</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['district'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upazila Branches -->
        <div class="col-md-2 col-sm-6 col-12">
            <div class="card metric-card border-left-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">উপজেলা কার্যালয়</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['upazila'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-home"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- International Branches -->
        <div class="col-md-2 col-sm-6 col-12">
            <div class="card metric-card border-left-secondary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">আন্তর্জাতিক শাখা</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['international'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                            <i class="fas fa-globe-asia"></i>
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
                <i class="fas fa-map-marked-alt me-2 text-primary"></i> কার্যালয় ও শাখা তালিকা
                <span class="badge bg-primary ms-2" id="total-count">{{ $branches->total() }}</span>
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.branches.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> নতুন কার্যালয় যোগ করুন
                </a>
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
                @include('admin.branches.partials.table', ['branches' => $branches])
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-triangle"></i> নিশ্চিত করুন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>আপনি কি নিশ্চিত যে আপনি '<strong id="delete-item-name"></strong>' কার্যালয়টি ডিলিট করতে চান?</p>
                <p class="text-danger small mb-0"><i class="fas fa-exclamation-triangle"></i> এই কার্যালয় ডিলিট হলে আর ফেরত আনা যাবে না!</p>
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
    var searchTimer;
    var isAjaxLoading = false;

    // Load branches with AJAX
    function loadBranches(page = 1) {
        if (isAjaxLoading) return;

        var search = $('#search-input').val();
        var type = $('#type-filter').val();
        var perPage = $('#per-page-filter').val();

        isAjaxLoading = true;
        $('#loading-spinner').show();
        $('#table-container').hide();

        $.ajax({
            url: "{{ route('admin.branches.index') }}",
            type: 'GET',
            data: {
                page: page,
                search: search,
                type: type,
                per_page: perPage,
                ajax: 1,
                _: Date.now()
            },
            success: function(response) {
                $('#loading-spinner').hide();
                $('#table-container').show();
                if (response.success) {
                    $('#table-container').html(response.html);
                    $('#total-count').text(response.total);
                    attachEventHandlers();
                } else {
                    toastr.error('ডাটা লোড করতে ব্যর্থ হয়েছে!');
                }
            },
            error: function() {
                $('#loading-spinner').hide();
                $('#table-container').show();
                toastr.error('সার্ভারে সমস্যা হয়েছে!');
            },
            complete: function() {
                isAjaxLoading = false;
            }
        });
    }

    // Attach AJAX event listeners after re-rendering table
    function attachEventHandlers() {
        // Toggle active status button
        $('.toggle-status').off('click').on('click', function() {
            var id = $(this).data('id');
            var btn = $(this);

            $.ajax({
                url: '{{ url("admin/branches") }}/' + id + '/toggle-status',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadBranches();
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
        $('.delete-branch').off('click').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');

            $('#delete-item-name').text(name);
            $('#deleteModal').modal('show');

            $('#confirm-delete').off('click').on('click', function() {
                $.ajax({
                    url: '{{ url("admin/branches") }}/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        if (response.success) {
                            toastr.success(response.message);
                            loadBranches(1);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        $('#deleteModal').modal('hide');
                        var msg = xhr.responseJSON ? xhr.responseJSON.message : 'কার্যালয় মুছতে ব্যর্থ হয়েছে!';
                        toastr.error(msg);
                    }
                });
            });
        });

        // Pagination links
        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').match(/page=(\d+)/) ? $(this).attr('href').match(/page=(\d+)/)[1] : 1;
            loadBranches(page);
        });
    }

    // Keyup search
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
            loadBranches(1);
        }, 500);
    });

    // Dropdown filters
    $('#type-filter, #per-page-filter').on('change', function() {
        loadBranches(1);
    });

    // Reset filter
    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#type-filter').val('');
        $('#per-page-filter').val('15');
        loadBranches(1);
    });

    // Run handlers initially
    attachEventHandlers();
});
</script>
@endpush
