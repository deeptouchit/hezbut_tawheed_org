@extends('admin.layouts.master')

@section('page-title', 'লাইভ সম্প্রচার ব্যবস্থাপনা')

@section('filter_input')
<div class="row px-3">
    <div class="col-md-6 mb-3">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="search-input" class="form-control" placeholder="শিরোনাম, ভিডিও আইডি বা বিবরণ..." autocomplete="off">
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <select id="status-filter" class="form-select">
            <option value="">সব স্ট্যাটাস</option>
            <option value="live">🔴 লাইভ চলছে</option>
            <option value="scheduled">⏳ আসন্ন শিডিউল</option>
            <option value="archive">✅ আর্কাইভ (অতীত)</option>
            <option value="inactive">🚫 নিষ্ক্রিয়</option>
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
                <h6 class="text-muted fw-bold">মোট লাইভ অনুষ্ঠান</h6>
                <h3 class="fw-bold text-dark mb-0">{{ $stats['total'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white border-0 shadow-sm p-3 rounded-3 text-center">
                <h6 class="fw-bold">🔴 বর্তমানে লাইভ</h6>
                <h3 class="fw-bold mb-0" id="live-count-stat">{{ $stats['live'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark border-0 shadow-sm p-3 rounded-3 text-center">
                <h6 class="fw-bold">⏳ আসন্ন শিডিউল</h6>
                <h3 class="fw-bold mb-0">{{ $stats['scheduled'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white border-0 shadow-sm p-3 rounded-3 text-center">
                <h6 class="fw-bold">✅ আর্কাইভ সমূহ</h6>
                <h3 class="fw-bold mb-0">{{ $stats['archive'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Main List Container -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-broadcast-tower me-2 text-danger"></i> সম্প্রচার তালিকা
                <span class="badge bg-primary ms-2" id="total-count">{{ $broadcasts->total() ?? $broadcasts->count() }}</span>
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.live-broadcasts.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                    <i class="fas fa-plus"></i> নতুন লাইভ যোগ করুন
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
                @include('admin.live_broadcasts.partials.table', ['broadcasts' => $broadcasts])
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
                <p>আপনি কি নিশ্চিত যে আপনি "<strong><span id="delete-item-name"></span></strong>" লাইভ অনুষ্ঠানটি ডিলিট করতে চান?</p>
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
    function loadBroadcasts(page = 1) {
        var search = $('#search-input').val();
        var status = $('#status-filter').val();
        var perPage = $('#per-page-filter').val();

        $('#loading-spinner').show();
        $('#table-container').hide();

        $.ajax({
            url: "{{ route('admin.live-broadcasts.index') }}",
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
        // Toggle live status
        $('.toggle-live').off('click').on('click', function() {
            var id = $(this).data('id');
            var btn = $(this);

            $.ajax({
                url: '{{ url("admin/live-broadcasts/toggle-status") }}/' + id,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadBroadcasts();
                        // Reload stats count
                        if (response.is_live) {
                            $('#live-count-stat').text('1');
                        } else {
                            $('#live-count-stat').text('0');
                        }
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('লাইভ স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে');
                }
            });
        });

        // Single delete
        $('.delete-broadcast').off('click').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');

            $('#delete-item-name').text(name);
            $('#deleteModal').modal('show');

            $('#confirm-delete').off('click').on('click', function() {
                $.ajax({
                    url: '{{ url("admin/live-broadcasts") }}/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        if (response.success) {
                            toastr.success(response.message);
                            loadBroadcasts();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        $('#deleteModal').modal('hide');
                        toastr.error('লাইভ অনুষ্ঠান ডিলিট করতে ব্যর্থ হয়েছে');
                    }
                });
            });
        });

        // Pagination links
        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').match(/page=(\d+)/) ? $(this).attr('href').match(/page=(\d+)/)[1] : 1;
            loadBroadcasts(page);
        });
    }

    // Search input keyup (delayed)
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
            loadBroadcasts(1);
        }, 500);
    });

    // Dropdown filters
    $('#status-filter, #per-page-filter').on('change', function() {
        loadBroadcasts(1);
    });

    // Reset button
    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#status-filter').val('');
        $('#per-page-filter').val('15');
        loadBroadcasts(1);
    });

    // Run handlers initially
    attachEventHandlers();
});
</script>
@endpush
