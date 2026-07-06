{{-- resources/views/admin/blog/categories/index.blade.php --}}

@extends('admin.layouts.master')

@section('page-title', 'ব্লগ ক্যাটাগরি ম্যানেজমেন্ট')

@push('styles')
<style>
    .category-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .category-image:hover {
        transform: scale(1.1);
        border-color: #0d6efd;
    }
    .status-badge {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .status-badge:hover {
        opacity: 0.8;
        transform: scale(0.95);
    }
    .drag-handle {
        cursor: move;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    .drag-handle:hover {
        color: #0d6efd;
    }
    .sortable-placeholder {
        background-color: #e9ecef;
        border: 2px dashed #0d6efd;
        height: 60px;
        border-radius: 8px;
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
    .blog-count-badge {
        background: #0d6efd;
        color: white;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 11px;
        margin-left: 5px;
    }
    .action-btn-group {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }
    .info-box {
        cursor: default;
        transition: all 0.3s ease;
    }
    .info-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('filter_input')
<div class="filter-card">
    <div class="row align-items-end">
        <div class="col-md-3 mb-3">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" id="search-input" class="form-control" placeholder="ক্যাটাগরি নাম, বিবরণ..." autocomplete="off" value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <select id="status-filter" class="form-select">
                <option value="">সব স্ট্যাটাস</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
            </select>
        </div>
        <div class="col-md-2 mb-3">
            <select id="per-page-filter" class="form-select">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>১০</option>
                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>২০</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>৫০</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>১০০</option>
                <option value="-1" {{ request('per_page') == -1 ? 'selected' : '' }}>সব</option>
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                <input type="date" id="date-from-filter" class="form-control" placeholder="তারিখ থেকে" value="{{ request('date_from') }}">
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="d-flex gap-2">
                <button id="reset-filter" class="btn btn-secondary w-50">
                    <i class="fas fa-undo-alt"></i> রিসেট
                </button>
                <button id="bulk-delete-btn" class="btn btn-danger w-50" style="display: none;">
                    <i class="fas fa-trash"></i> (<span id="selected-count">0</span>)
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
                <i class="fas fa-tags me-2"></i> ব্লগ ক্যাটাগরি তালিকা
                <span class="badge bg-primary ms-2" id="total-count">{{ $categories->total() ?? $categories->count() }}</span>
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.blog.categories.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> নতুন ক্যাটাগরি
                    </a>
                    <a href="{{ route('admin.blog.categories.export', request()->query()) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> এক্সপোর্ট
                    </a>
                    <button class="btn btn-info btn-sm" id="refresh-btn" title="রিফ্রেশ">
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

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row m-3">
                <div class="col-md-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1">
                            <i class="fas fa-tags"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">মোট ক্যাটাগরি</span>
                            <span class="info-box-number">{{ number_format($stats['total'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-success elevation-1">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">সক্রিয়</span>
                            <span class="info-box-number">{{ number_format($stats['active'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger elevation-1">
                            <i class="fas fa-times-circle"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">নিষ্ক্রিয়</span>
                            <span class="info-box-number">{{ number_format($stats['inactive'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning elevation-1">
                            <i class="fas fa-file-alt"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">মোট ব্লগ</span>
                            <span class="info-box-number">{{ number_format($stats['total_blogs'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div id="categories-table-container">
                @include('admin.blog.categories.partials.table', ['categories' => $categories])
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> নিশ্চিত করুন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>আপনি কি <strong id="delete-name"></strong> ক্যাটাগরিটি ডিলিট করতে চান?</p>
                <p class="text-danger"><small>⚠️ এই কাজটি অপরিবর্তনীয়! ডিলিট করার পর আর ফিরিয়ে আনা যাবে না।</small></p>
                <div class="text-warning" id="delete-blog-warning" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i>
                    এই ক্যাটাগরির অধীনে <strong id="delete-blog-count"></strong> টি ব্লগ পোস্ট আছে। প্রথমে ব্লগ পোস্ট গুলো সরান বা অন্য ক্যাটাগরিতে স্থানান্তর করুন!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">
                    <i class="fas fa-trash"></i> ডিলিট
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> বাল্ক ডিলিট</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>আপনি কি <strong id="bulk-delete-count"></strong> টি ক্যাটাগরি ডিলিট করতে চান?</p>
                <p class="text-danger"><small>⚠️ এই কাজটি অপরিবর্তনীয়! ডিলিট করার পর আর ফিরিয়ে আনা যাবে না।</small></p>
                <p class="text-warning" id="bulk-delete-warning" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i>
                    কিছু ক্যাটাগরির অধীনে ব্লগ পোস্ট আছে। প্রথমে ব্লগ পোস্ট গুলো সরান বা অন্য ক্যাটাগরিতে স্থানান্তর করুন!
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-danger" id="confirm-bulk-delete">
                    <i class="fas fa-trash"></i> সব ডিলিট
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    let searchTimeout;
    let isAjaxLoading = false;
    let deleteId = null;

    // ============================================
    // Load Categories via AJAX
    // ============================================
    function loadCategories(page = 1) {
        if (isAjaxLoading) return;

        var search = $('#search-input').val();
        var status = $('#status-filter').val();
        var perPage = $('#per-page-filter').val();
        var dateFrom = $('#date-from-filter').val();
        var dateTo = $('#date-to-filter').val();

        isAjaxLoading = true;
        $('#loading-spinner').show();
        $('#categories-table-container').hide();

        $.ajax({
            url: "{{ route('admin.blog.categories.index') }}",
            type: "GET",
            data: {
                search: search,
                status: status,
                per_page: perPage,
                date_from: dateFrom,
                date_to: dateTo,
                page: page,
                _: Date.now()
            },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.html) {
                    $('#categories-table-container').html(response.html);
                    attachEventHandlers();
                    updateTotalCount(response.total || 0);

                    // Initialize sortable
                    if ($('#sortable-table tbody').length) {
                        initializeSortable();
                    }
                }
                $('#loading-spinner').hide();
                $('#categories-table-container').show();
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                toastr.error('ডাটা লোড করতে ব্যর্থ হয়েছে');
                $('#loading-spinner').hide();
                $('#categories-table-container').show();
            },
            complete: function() {
                isAjaxLoading = false;
            }
        });
    }

    // ============================================
    // Update Total Count
    // ============================================
    function updateTotalCount(count) {
        $('#total-count').text(count);
    }

    // ============================================
    // Attach Event Handlers
    // ============================================
    function attachEventHandlers() {
        // Select All
        $('#selectAll').off('change').on('change', function() {
            $('.category-checkbox').prop('checked', $(this).prop('checked'));
            toggleBulkDeleteButton();
        });

        // Individual checkbox
        $('.category-checkbox').off('change').on('change', function() {
            toggleBulkDeleteButton();
        });

        function toggleBulkDeleteButton() {
            var checkedCount = $('.category-checkbox:checked').length;
            $('#selected-count').text(checkedCount);
            if (checkedCount > 0) {
                $('#bulk-delete-btn').show();
            } else {
                $('#bulk-delete-btn').hide();
            }
        }

        // ============================================
        // View Category Detail
        // ============================================
        $('.view-category').off('click').on('click', function() {
            var id = $(this).data('id');
            var url = '{{ url("admin/blog/categories") }}/' + id + '/show';
            window.open(url, '_blank');
        });

        // ============================================
        // Delete Single
        // ============================================
        $('.delete-category').off('click').on('click', function() {
            deleteId = $(this).data('id');
            var name = $(this).data('name');
            var blogCount = $(this).data('blog-count') || 0;
            $('#delete-name').text(name);

            // Show warning if has blogs
            if (blogCount > 0) {
                $('#delete-blog-warning').show();
                $('#delete-blog-count').text(blogCount);
                $('#confirm-delete').prop('disabled', true);
            } else {
                $('#delete-blog-warning').hide();
                $('#confirm-delete').prop('disabled', false);
            }

            $('#deleteModal').modal('show');
        });

        $('#confirm-delete').off('click').on('click', function() {
            if (!deleteId) return;
            if ($(this).prop('disabled')) return;

            $.ajax({
                url: '{{ url("admin/blog/categories") }}/' + deleteId,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    if (response.success) {
                        toastr.success(response.message);
                        loadCategories();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    var message = 'ক্যাটাগরি ডিলিট করতে ব্যর্থ হয়েছে';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    toastr.error(message);
                    $('#deleteModal').modal('hide');
                }
            });
        });

        // ============================================
        // Bulk Delete
        // ============================================
        $('#bulk-delete-btn').off('click').on('click', function() {
            var ids = $('.category-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (ids.length === 0) return;

            $('#bulk-delete-count').text(ids.length);
            $('#bulkDeleteModal').modal('show');

            $('#confirm-bulk-delete').off('click').on('click', function() {
                $.ajax({
                    url: "{{ route('admin.blog.categories.bulk-delete') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: ids
                    },
                    success: function(response) {
                        $('#bulkDeleteModal').modal('hide');
                        if (response.success) {
                            toastr.success(response.message);
                            loadCategories();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        var message = 'বাল্ক ডিলিট করতে ব্যর্থ হয়েছে';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        toastr.error(message);
                        $('#bulkDeleteModal').modal('hide');
                    }
                });
            });
        });

        // ============================================
        // Toggle Status
        // ============================================
        $('.toggle-status').off('click').on('click', function() {
            var id = $(this).data('id');
            var btn = $(this);

            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: '{{ url("admin/blog/categories") }}/' + id + '/toggle-status',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadCategories();
                    } else {
                        toastr.error(response.message);
                        btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i>');
                    }
                },
                error: function() {
                    toastr.error('স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে');
                    btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i>');
                }
            });
        });

        // ============================================
        // Pagination
        // ============================================
        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var page = url.match(/page=(\d+)/) ? url.match(/page=(\d+)/)[1] : 1;
            if (page) loadCategories(page);
        });
    }

    // ============================================
    // Initialize Sortable
    // ============================================
    function initializeSortable() {
        if (typeof Sortable !== 'undefined') {
            var el = document.getElementById('sortable-body');
            if (el) {
                Sortable.create(el, {
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'sortable-placeholder',
                    onEnd: function(evt) {
                        var order = [];
                        $('#sortable-body tr').each(function() {
                            order.push($(this).data('id'));
                        });

                        $.ajax({
                            url: "{{ route('admin.blog.categories.reorder') }}",
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                order: order
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                }
                            },
                            error: function() {
                                toastr.error('অর্ডার আপডেট করতে ব্যর্থ হয়েছে');
                                loadCategories();
                            }
                        });
                    }
                });
            }
        }
    }

    // ============================================
    // Filter Handlers
    // ============================================
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadCategories(), 500);
    });

    $('#status-filter, #per-page-filter, #date-from-filter, #date-to-filter').on('change', function() {
        loadCategories();
    });

    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#status-filter').val('');
        $('#per-page-filter').val('20');
        $('#date-from-filter').val('');
        $('#date-to-filter').val('');
        loadCategories();
    });

    // ============================================
    // Refresh Button
    // ============================================
    $('#refresh-btn').on('click', function() {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        loadCategories();
        setTimeout(function() {
            btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i>');
        }, 1000);
    });

    // ============================================
    // Keyboard Shortcuts
    // ============================================
    $(document).on('keydown', function(e) {
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            $('#search-input').focus();
        }
        if (e.key === 'Escape') {
            $('#reset-filter').click();
        }
        if (e.key === 'Delete' && $('.category-checkbox:checked').length > 0) {
            e.preventDefault();
            $('#bulk-delete-btn').click();
        }
    });

    // ============================================
    // Initialize
    // ============================================
    attachEventHandlers();
    setTimeout(initializeSortable, 500);

    // Toastr configuration
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
});
</script>
@endpush
