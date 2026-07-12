{{-- resources/views/admin/leaders/index.blade.php --}}

@extends('admin.layouts.master')

@section('page-title', 'নেতৃত্ব ব্যবস্থাপনা')

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

    @media (min-width: 768px) {
        .col-md-2-4 {
            flex: 0 0 auto;
            width: 20%;
        }
    }
</style>
@endpush

@section('filter_input')
<!-- Filter Section -->
<div class="card border shadow-none mb-2 bg-light-subtle">
    <div class="card-body p-2">
        <div class="row g-2">
            <div class="col-md-4 col-sm-6">
                <input type="text" id="search-input" class="form-control" placeholder="নাম, ইংরেজি নাম বা পদবী..." autocomplete="off" value="{{ request('search') }}">
            </div>
            <div class="col-md-3 col-sm-6">
                <select id="category-filter" class="form-select">
                    <option value="">সব ক্যাটাগরি</option>
                    <option value="central" {{ request('category') == 'central' ? 'selected' : '' }}>কেন্দ্রীয় নেতৃত্ব</option>
                    <option value="advisory" {{ request('category') == 'advisory' ? 'selected' : '' }}>উপদেষ্টা পরিষদ</option>
                    <option value="executive" {{ request('category') == 'executive' ? 'selected' : '' }}>নির্বাহী কমিটি</option>
                    <option value="regional" {{ request('category') == 'regional' ? 'selected' : '' }}>আঞ্চলিক নেতৃত্ব</option>
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
        <!-- Total Leaders -->
        <div class="col-md-2-4 col-sm-6 col-12">
            <div class="card metric-card border-left-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">মোট নেতৃত্ব</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['total'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Central Leaders -->
        <div class="col-md-2-4 col-sm-6 col-12">
            <div class="card metric-card border-left-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">কেন্দ্রীয় নেতৃত্ব</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['central'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-crown"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advisory -->
        <div class="col-md-2-4 col-sm-6 col-12">
            <div class="card metric-card border-left-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">উপদেষ্টা পরিষদ</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['advisory'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-university"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Executive -->
        <div class="col-md-2-4 col-sm-6 col-12">
            <div class="card metric-card border-left-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">নির্বাহী কমিটি</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['executive'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-users-cog"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Regional -->
        <div class="col-md-2-4 col-sm-6 col-12">
            <div class="card metric-card border-left-secondary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">আঞ্চলিক নেতৃত্ব</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['regional'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                            <i class="fas fa-map-marked-alt"></i>
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
                <i class="fas fa-user-tie me-2 text-primary"></i> নেতৃত্বের তালিকা
                <span class="badge bg-primary ms-2" id="total-count">{{ $leaders->total() ?? $leaders->count() }}</span>
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.leaders.hierarchy') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-sitemap"></i> সাংগঠনিক কাঠামো সাজান
                    </a>
                    <a href="{{ route('admin.leaders.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> নতুন নেতৃত্ব যোগ করুন
                    </a>
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
                @include('admin.leaders.partials.table', ['leaders' => $leaders])
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
                <p>আপনি কি নিশ্চিত যে আপনি "<strong><span id="delete-item-name"></span></strong>" এর নেতৃত্বের প্রোফাইলটি ডিলিট করতে চান?</p>
                <p class="text-danger small"><i class="fas fa-info-circle"></i> সতর্কবার্তা: এই অ্যাকশনটি আর ফিরিয়ে নেওয়া যাবে না!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" id="confirm-delete" class="btn btn-danger">হ্যাঁ, ডিলিট করুন</button>
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

    // Load data with AJAX
    function loadLeaders(page = 1) {
        if (isAjaxLoading) return;

        var search = $('#search-input').val();
        var category = $('#category-filter').val();
        var perPage = $('#per-page-filter').val();

        isAjaxLoading = true;
        $('#loading-spinner').show();
        $('#table-container').hide();

        $.ajax({
            url: "{{ route('admin.leaders.index') }}",
            type: 'GET',
            data: {
                page: page,
                search: search,
                category: category,
                per_page: perPage,
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
        // Toggle active status
        $('.toggle-active').off('click').on('click', function() {
            var id = $(this).data('id');
            var btn = $(this);

            $.ajax({
                url: '{{ url("admin/leaders") }}/' + id + '/toggle-status',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadLeaders();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে');
                }
            });
        });

        // Inline sort order save
        $('.save-order-btn').off('click').on('click', function() {
            var id = $(this).data('id');
            var input = $('#order-input-' + id);
            var orderVal = input.val();

            $.ajax({
                url: '{{ url("admin/leaders") }}/' + id + '/update-order',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    sort_order: orderVal
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadLeaders();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('ক্রম বিন্যাস পরিবর্তন ব্যর্থ হয়েছে');
                }
            });
        });

        // Single delete
        $('.delete-leader').off('click').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');

            $('#delete-item-name').text(name);
            $('#deleteModal').modal('show');

            $('#confirm-delete').off('click').on('click', function() {
                $.ajax({
                    url: '{{ url("admin/leaders") }}/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        if (response.success) {
                            toastr.success(response.message);
                            loadLeaders();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        $('#deleteModal').modal('hide');
                        toastr.error('প্রোফাইল ডিলিট করতে ব্যর্থ হয়েছে');
                    }
                });
            });
        });

        // Pagination links
        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').match(/page=(\d+)/) ? $(this).attr('href').match(/page=(\d+)/)[1] : 1;
            loadLeaders(page);
        });
    }

    // Search input keyup (delayed)
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
            loadLeaders(1);
        }, 500);
    });

    // Dropdown filters
    $('#category-filter, #per-page-filter').on('change', function() {
        loadLeaders(1);
    });

    // Reset button
    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#category-filter').val('');
        $('#per-page-filter').val('15');
        loadLeaders(1);
    });

    // Run handlers initially
    attachEventHandlers();
});
</script>
@endpush
