@extends('admin.layouts.master')

@section('page-title', 'নেতৃত্ব ব্যবস্থাপনা')

@section('filter_input')
<div class="row px-3">
    <div class="col-md-6 mb-3">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="search-input" class="form-control" placeholder="নাম, ইংরেজি নাম বা পদবী..." autocomplete="off">
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <select id="category-filter" class="form-select">
            <option value="">সব ক্যাটাগরি</option>
            <option value="central">কেন্দ্রীয় নেতৃত্ব</option>
            <option value="advisory">উপদেষ্টা পরিষদ</option>
            <option value="executive">নির্বাহী কমিটি</option>
            <option value="regional">আঞ্চলিক নেতৃত্ব</option>
        </select>
    </div>
    <div class="col-md-2 mb-3">
        <select id="per-page-filter" class="form-select">
            <option value="10">১০</option>
            <option value="15" selected>১৫</option>
            <option value="30">৩০</option>
            <option value="50">৫০</option>
            <option value="-1">সব</option>
        </select>
    </div>
    <div class="col-md-2 mb-3">
        <button id="reset-filter" class="btn btn-secondary w-100">
            <i class="fas fa-undo-alt"></i> রিসেট
        </button>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Stats Cards Summary Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-light border-0 shadow-sm p-3 rounded-3 text-center">
                <h6 class="text-muted fw-bold">মোট নেতৃত্ব প্রোফাইল</h6>
                <h3 class="fw-bold text-dark mb-0">{{ $stats['total'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white border-0 shadow-sm p-3 rounded-3 text-center">
                <h6 class="fw-bold">কেন্দ্রীয় নেতৃত্ব</h6>
                <h3 class="fw-bold mb-0">{{ $stats['central'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white border-0 shadow-sm p-3 rounded-3 text-center">
                <h6 class="fw-bold">উপদেষ্টা পরিষদ</h6>
                <h3 class="fw-bold mb-0">{{ $stats['advisory'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white border-0 shadow-sm p-3 rounded-3 text-center">
                <h6 class="fw-bold">নির্বাহী কমিটি</h6>
                <h3 class="fw-bold mb-0">{{ $stats['executive'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Main List Container -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-tie me-2 text-primary"></i> নেতৃত্বের তালিকা
                <span class="badge bg-primary ms-2" id="total-count">{{ $leaders->total() ?? $leaders->count() }}</span>
            </h3>
            <div class="card-tools d-flex gap-2">
                <a href="{{ route('admin.leaders.hierarchy') }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
                    <i class="fas fa-sitemap"></i> সাংগঠনিক কাঠামো সাজান
                </a>
                <a href="{{ route('admin.leaders.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                    <i class="fas fa-plus"></i> নতুন প্রোফাইল যোগ করুন
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <!-- Loading Spinner -->
            <div id="loading-spinner" class="text-center py-5" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">লোড হচ্ছে...</span>
                </div>
                <div class="text-muted mt-2">ডাটা লোড হচ্ছে, দয়া করে অপেক্ষা করুন...</div>
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
                <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-triangle"></i> ডিলিট নিশ্চিত করুন</h5>
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

    // Load data with AJAX
    function loadLeaders(page = 1) {
        var search = $('#search-input').val();
        var category = $('#category-filter').val();
        var perPage = $('#per-page-filter').val();

        $('#loading-spinner').show();
        $('#table-container').hide();

        $.ajax({
            url: "{{ route('admin.leaders.index') }}",
            type: 'GET',
            data: {
                page: page,
                search: search,
                category: category,
                per_page: perPage
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
