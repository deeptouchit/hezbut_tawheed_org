{{-- resources/views/admin/join_requests/index.blade.php --}}

@extends('admin.layouts.master')

@section('page-title', 'সদস্য পদের আবেদন ব্যবস্থাপনা')

@push('styles')
<style>
    .request-status-badge {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .request-status-badge:hover {
        opacity: 0.8;
        transform: scale(0.95);
    }
    .table-row-hover {
        transition: background-color 0.3s ease;
    }
    .table-row-hover:hover {
        background-color: #f8f9fa;
    }
    .filter-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
    }
    .info-box {
        cursor: default;
        transition: all 0.3s ease;
    }
    .info-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .unread-row {
        font-weight: 600;
        background-color: #fff3cd;
    }
    .unread-row:hover {
        background-color: #ffe69c;
    }
    .replied-row {
        background-color: #d1e7dd;
    }
    .replied-row:hover {
        background-color: #a3cfbb;
    }
</style>
@endpush

@section('filter_input')
<div class="filter-card">
    <div class="row align-items-end">
        <div class="col-md-3 mb-3">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" id="search-input" class="form-control" placeholder="নাম, মোবাইল, পিতা, ঠিকানা..." autocomplete="off" value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <select id="type-filter" class="form-select">
                <option value="">সব আবেদনের ধরন</option>
                <option value="primary" {{ request('membership_type') == 'primary' ? 'selected' : '' }}>প্রাথমিক সদস্য</option>
                <option value="pledge" {{ request('membership_type') == 'pledge' ? 'selected' : '' }}>পাঁচ দফা ভিত্তিক অঙ্গীকার</option>
            </select>
        </div>
        <div class="col-md-2 mb-3">
            <select id="status-filter" class="form-select">
                <option value="">সব স্ট্যাটাস</option>
                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>অপঠিত</option>
                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>পঠিত</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>অনুমোদিত</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>প্রত্যাখ্যাত</option>
            </select>
        </div>
        <div class="col-md-2 mb-3">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                <input type="date" id="date-from-filter" class="form-control" placeholder="তারিখ থেকে" value="{{ request('date_from') }}">
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="d-flex gap-2">
                <button id="reset-filter" class="btn btn-secondary w-50">
                    <i class="fas fa-undo-alt"></i> রিসেট
                </button>
                <button id="bulk-delete-btn" class="btn btn-danger w-50" style="display: none;">
                    <i class="fas fa-trash"></i> ডিলিট (<span id="selected-count">0</span>)
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-plus me-2"></i> সদস্য পদের আবেদন তালিকা
                <span class="badge bg-primary ms-2" id="total-count">{{ $requests->total() }}</span>
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <select id="bulk-status-select" class="form-select form-select-sm me-2" style="display: none; width: auto;">
                        <option value="">স্ট্যাটাস পরিবর্তন...</option>
                        <option value="unread">অপঠিত চিহ্নিত করুন</option>
                        <option value="read">পঠিত চিহ্নিত করুন</option>
                        <option value="approved">অনুমোদিত চিহ্নিত করুন</option>
                        <option value="rejected">প্রত্যাখ্যাত চিহ্নিত করুন</option>
                    </select>
                    <button id="bulk-status-btn" class="btn btn-primary btn-sm me-2" style="display: none;">
                        <i class="fas fa-edit"></i> আপডেট
                    </button>
                    <button id="refresh-btn" class="btn btn-secondary btn-sm" title="রিফ্রেশ">
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

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row m-3">
                <div class="col-md-2 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1">
                            <i class="fas fa-user-plus"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">মোট আবেদন</span>
                            <span class="info-box-number">{{ number_format($stats['total']) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger elevation-1">
                            <i class="fas fa-circle"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">অপঠিত</span>
                            <span class="info-box-number">{{ number_format($stats['unread']) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">পঠিত</span>
                            <span class="info-box-number">{{ number_format($stats['read']) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-success elevation-1">
                            <i class="fas fa-check"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">অনুমোদিত</span>
                            <span class="info-box-number">{{ number_format($stats['approved']) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary elevation-1">
                            <i class="fas fa-times"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">প্রত্যাখ্যাত</span>
                            <span class="info-box-number">{{ number_format($stats['rejected']) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div id="requests-table-container">
                @include('admin.join_requests.partials.table', ['requests' => $requests])
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">আবেদন মুছে ফেলার নিশ্চিতকরণ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                আপনি কি নিশ্চিত যে আপনি <strong id="delete-name"></strong> এর সদস্য পদের আবেদনটি মুছে ফেলতে চান?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">মুছে ফেলুন</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Confirmation Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkDeleteModalLabel">বাল্ক ডিলিট নিশ্চিতকরণ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                আপনি কি নিশ্চিত যে আপনি নির্বাচিত <strong id="bulk-delete-count"></strong> টি আবেদন স্থায়ীভাবে মুছে ফেলতে চান?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-danger" id="confirm-bulk-delete">মুছে ফেলুন</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var isAjaxLoading = false;
    var deleteId = null;

    // toastr setup if not already globally configured
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };

    // Initialize Event Handlers
    attachEventHandlers();

    // ============================================
    // Load Requests AJAX
    // ============================================
    function loadRequests(page = 1) {
        if (isAjaxLoading) return;
        isAjaxLoading = true;

        var search = $('#search-input').val();
        var status = $('#status-filter').val();
        var type = $('#type-filter').val();
        var dateFrom = $('#date-from-filter').val();

        $('#requests-table-container').hide();
        $('#loading-spinner').show();

        $.ajax({
            url: "{{ route('admin.join-requests.index') }}",
            type: "GET",
            data: {
                search: search,
                status: status,
                membership_type: type,
                date_from: dateFrom,
                page: page,
                _: Date.now()
            },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.html) {
                    $('#requests-table-container').html(response.html);
                    attachEventHandlers();
                    $('#total-count').text(response.total || 0);
                }
                $('#loading-spinner').hide();
                $('#requests-table-container').show();
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                toastr.error('ডাটা লোড করতে ব্যর্থ হয়েছে');
                $('#loading-spinner').hide();
                $('#requests-table-container').show();
            },
            complete: function() {
                isAjaxLoading = false;
            }
        });
    }

    // ============================================
    // Attach Event Handlers
    // ============================================
    function attachEventHandlers() {
        // Select All
        $('#selectAll').off('change').on('change', function() {
            $('.request-checkbox').prop('checked', $(this).prop('checked'));
            toggleBulkControls();
        });

        // Individual checkbox
        $('.request-checkbox').off('change').on('change', function() {
            toggleBulkControls();
        });

        function toggleBulkControls() {
            var checkedCount = $('.request-checkbox:checked').length;
            $('#selected-count').text(checkedCount);

            if (checkedCount > 0) {
                $('#bulk-delete-btn').show();
                $('#bulk-status-select').show();
                $('#bulk-status-btn').show();
            } else {
                $('#bulk-delete-btn').hide();
                $('#bulk-status-select').hide();
                $('#bulk-status-btn').hide();
            }
        }

        // Delete Single Trigger
        $('.delete-request').off('click').on('click', function() {
            deleteId = $(this).data('id');
            var name = $(this).data('name');
            $('#delete-name').text(name);
            $('#deleteModal').modal('show');
        });

        // Pagination links
        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            loadRequests(page);
        });
    }

    // ============================================
    // Delete Single Confirm
    // ============================================
    $('#confirm-delete').on('click', function() {
        if (!deleteId) return;

        $.ajax({
            url: '{{ url("admin/join-requests") }}/' + deleteId,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                $('#deleteModal').modal('hide');
                if (response.success) {
                    toastr.success(response.message);
                    loadRequests();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('আবেদন ডিলিট করতে ব্যর্থ হয়েছে');
                $('#deleteModal').modal('hide');
            }
        });
    });

    // ============================================
    // Bulk Delete Trigger
    // ============================================
    $('#bulk-delete-btn').on('click', function() {
        var checkedCount = $('.request-checkbox:checked').length;
        $('#bulk-delete-count').text(checkedCount);
        $('#bulkDeleteModal').modal('show');
    });

    // Bulk Delete Confirm
    $('#confirm-bulk-delete').on('click', function() {
        var selectedIds = [];
        $('.request-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        $.ajax({
            url: "{{ route('admin.join-requests.bulk-delete') }}",
            type: "POST",
            data: {
                ids: selectedIds,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#bulkDeleteModal').modal('hide');
                if (response.success) {
                    toastr.success(response.message);
                    loadRequests();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('বাল্ক ডিলিট করতে ব্যর্থ হয়েছে');
                $('#bulkDeleteModal').modal('hide');
            }
        });
    });

    // ============================================
    // Bulk Status Update
    // ============================================
    $('#bulk-status-btn').on('click', function() {
        var selectedStatus = $('#bulk-status-select').val();
        if (!selectedStatus) {
            toastr.warning('দয়া করে একটি স্ট্যাটাস নির্বাচন করুন');
            return;
        }

        var selectedIds = [];
        $('.request-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        $.ajax({
            url: "{{ route('admin.join-requests.bulk-status') }}",
            type: "POST",
            data: {
                ids: selectedIds,
                status: selectedStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    loadRequests();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('স্ট্যাটাস আপডেট করতে ব্যর্থ হয়েছে');
            }
        });
    });

    // ============================================
    // Search & Filter change
    // ============================================
    var searchTimer;
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
            loadRequests();
        }, 500);
    });

    $('#status-filter, #type-filter, #date-from-filter').on('change', function() {
        loadRequests();
    });

    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#status-filter').val('');
        $('#type-filter').val('');
        $('#date-from-filter').val('');
        loadRequests();
    });

    $(document).on('click', '#reset-filter-empty', function() {
        $('#reset-filter').trigger('click');
    });

    $('#refresh-btn').on('click', function() {
        loadRequests();
    });
});
</script>
@endpush
