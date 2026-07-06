@extends('admin.layouts.master')

@section('page-title', 'নাগরিক মতামত ও পরামর্শ ব্যবস্থাপনা')

@push('styles')
<style>
    .suggestion-status-badge {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .suggestion-status-badge:hover {
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
    .message-preview {
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .info-box {
        cursor: default;
        transition: all 0.3s ease;
    }
    .info-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .pending-row {
        font-weight: 600;
        background-color: #fff3cd;
    }
    .pending-row:hover {
        background-color: #ffe69c;
    }
</style>
@endpush

@section('filter_input')
<div class="filter-card">
    <form action="{{ route('admin.suggestions.index') }}" method="GET">
        <div class="row align-items-end">
            <div class="col-md-4 mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="নাম, কন্টাক্ট, বিষয় বা বার্তা..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <select name="status" class="form-select">
                    <option value="">সব স্ট্যাটাস</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>পেন্ডিং (অপঠিত)</option>
                    <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>রিভিউড (পঠিত)</option>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <select name="per_page" class="form-select">
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>২০ টি</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>৫০ টি</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>১০০ টি</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-50">
                        <i class="fas fa-search"></i> খুঁজুন
                    </button>
                    <a href="{{ route('admin.suggestions.index') }}" class="btn btn-secondary w-50">
                        <i class="fas fa-undo-alt"></i> রিসেট
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-6">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary"><i class="fas fa-comments"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">মোট পরামর্শ</span>
                    <span class="info-box-number">{{ number_format($stats['total']) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">পেন্ডিং পরামর্শ</span>
                    <span class="info-box-number text-warning">{{ number_format($stats['pending']) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">রিভিউড পরামর্শ</span>
                    <span class="info-box-number text-success">{{ number_format($stats['reviewed']) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-info"><i class="fas fa-calendar-day"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">আজকের প্রাপ্তি</span>
                    <span class="info-box-number text-info">{{ number_format($stats['today']) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions toolbar -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
            <button id="bulk-delete-btn" class="btn btn-danger btn-sm" style="display: none;">
                <i class="fas fa-trash"></i> বাল্ক ডিলিট (<span id="selected-count">0</span>)
            </button>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h3 class="card-title fw-bold text-dark mb-0">
                <i class="fas fa-comment-dots text-primary me-2"></i> নাগরিক পরামর্শ ও মতামত তালিকা
            </h3>
        </div>

        <div class="card-body p-0">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div id="suggestions-table-container">
                @include('admin.suggestions.partials.table', ['suggestions' => $suggestions])
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> নিশ্চিতকরণ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>আপনি কি নিশ্চিত যে আপনি <strong id="delete-name"></strong> এর পাঠানো পরামর্শ বার্তাটি ডিলিট করতে চান?</p>
                <p class="text-danger"><small>⚠️ এই কাজটি অপরিবর্তনীয়!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">ডিলিট করুন</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> বাল্ক ডিলিট নিশ্চিতকরণ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>আপনি কি নির্বাচিত <strong id="bulk-delete-count"></strong> টি পরামর্শ বার্তা ডিলিট করতে চান?</p>
                <p class="text-danger"><small>⚠️ এই কাজটি অপরিবর্তনীয়!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-danger" id="confirm-bulk-delete">সব মুছে ফেলুন</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let deleteId = null;

    // Attach checkbox event handlers
    function attachCheckboxes() {
        $('#selectAll').on('change', function() {
            $('.suggestion-checkbox').prop('checked', $(this).prop('checked'));
            updateBulkToolbar();
        });

        $('.suggestion-checkbox').on('change', function() {
            updateBulkToolbar();
        });
    }

    function updateBulkToolbar() {
        let checkedCount = $('.suggestion-checkbox:checked').length;
        $('#selected-count').text(checkedCount);
        if (checkedCount > 0) {
            $('#bulk-delete-btn').fadeIn(200);
        } else {
            $('#bulk-delete-btn').fadeOut(200);
        }
    }

    // Toggle status via AJAX
    $(document).on('click', '.toggle-status-btn', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let badge = $(this);
        
        badge.html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: "{{ url('admin/suggestions') }}/" + id + "/toggle-status",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('স্ট্যাটাস পরিবর্তন ব্যর্থ হয়েছে!');
            }
        });
    });

    // Delete Modal trigger
    $(document).on('click', '.delete-suggestion', function() {
        deleteId = $(this).data('id');
        let name = $(this).data('name');
        $('#delete-name').text(name);
        $('#deleteModal').modal('show');
    });

    // Confirm single delete
    $('#confirm-delete').on('click', function() {
        if (!deleteId) return;

        $.ajax({
            url: "{{ url('admin/suggestions') }}/" + deleteId,
            type: "DELETE",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                $('#deleteModal').modal('hide');
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                $('#deleteModal').modal('hide');
                toastr.error('মুছে ফেলতে ব্যর্থ হয়েছে!');
            }
        });
    });

    // Bulk Delete Action
    $('#bulk-delete-btn').on('click', function() {
        let ids = $('.suggestion-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (ids.length === 0) return;

        $('#bulk-delete-count').text(ids.length);
        $('#bulkDeleteModal').modal('show');

        $('#confirm-bulk-delete').off('click').on('click', function() {
            $.ajax({
                url: "{{ route('admin.suggestions.bulk-delete') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    ids: ids
                },
                success: function(response) {
                    $('#bulkDeleteModal').modal('hide');
                    if (response.success) {
                        toastr.success(response.message);
                        location.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    $('#bulkDeleteModal').modal('hide');
                    toastr.error('বাল্ক ডিলিট ব্যর্থ হয়েছে!');
                }
            });
        });
    });

    // Initialize checkers
    attachCheckboxes();

    // Toastr configurations
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };
});
</script>
@endpush
