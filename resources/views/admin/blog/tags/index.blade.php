{{-- resources/views/admin/blog/tags/index.blade.php --}}

@extends('admin.layouts.master')

@section('page-title', 'ট্যাগ ম্যানেজমেন্ট')

@push('styles')
<style>
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
            <div class="col-md-3 col-sm-6">
                <input type="text" id="search-input" class="form-control" placeholder="ট্যাগের নাম, বিবরণ খুঁজুন..." autocomplete="off" value="{{ request('search') }}">
            </div>
            <div class="col-md-2 col-sm-6">
                <select id="status-filter" class="form-select">
                    <option value="">সব স্ট্যাটাস</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <select id="per-page-filter" class="form-select">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>১০টি</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : (request('per_page') == '' ? 'selected' : '') }}>২০টি</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>৫০টি</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>১০০টি</option>
                    <option value="-1" {{ request('per_page') == -1 ? 'selected' : '' }}>সব</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <input type="date" id="date-from-filter" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2 col-sm-6">
                <div class="d-flex gap-2 w-100">
                    <button id="reset-filter" class="btn btn-secondary w-100" title="রিসেট ফিল্টার" style="height: 38px;">
                        <i class="fas fa-undo-alt me-1"></i> রিসেট
                    </button>
                    <button id="bulk-delete-btn" class="btn btn-danger w-100" style="display: none; height: 38px;">
                        <i class="fas fa-trash me-1"></i> (<span id="selected-count">0</span>)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row g-2 mb-3">
        <!-- Total Tags -->
        <div class="col-md-4 col-6">
            <div class="card metric-card border-left-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">মোট ট্যাগ</span>
                            <h5 class="mb-0 fw-bold" id="stats-total">{{ number_format($stats['total'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-tags"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Tags -->
        <div class="col-md-4 col-6">
            <div class="card metric-card border-left-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">সক্রিয়</span>
                            <h5 class="mb-0 fw-bold" id="stats-active">{{ number_format($stats['active'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inactive Tags -->
        <div class="col-md-4 col-6">
            <div class="card metric-card border-left-danger h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">নিষ্ক্রিয়</span>
                            <h5 class="mb-0 fw-bold" id="stats-inactive">{{ number_format($stats['inactive'] ?? 0) }}</h5>
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
                <i class="fas fa-tags me-2"></i> ব্লগ ট্যাগ তালিকা
                <span class="badge bg-primary ms-2" id="total-count">{{ $tags->total() ?? $tags->count() }}</span>
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary btn-sm" id="create-tag-btn">
                        <i class="fas fa-plus"></i> নতুন ট্যাগ
                    </button>
                    <a href="{{ route('admin.blog.tags.export', request()->query()) }}" class="btn btn-success btn-sm">
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

            <!-- Table Container -->
            <div id="tags-table-container">
                @include('admin.blog.tags.partials.table', ['tags' => $tags])
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
                <p>আপনি কি <strong id="delete-name"></strong> ট্যাগটি ডিলিট করতে চান?</p>
                <p class="text-danger"><small>⚠️ এই কাজটি অপরিবর্তনীয়! ডিলিট করার পর আর ফিরিয়ে আনা যাবে না।</small></p>
                <div class="text-warning" id="delete-blog-warning" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i>
                    এই ট্যাগের অধীনে <strong id="delete-blog-count"></strong> টি ব্লগ পোস্ট আছে। প্রথমে ব্লগ পোস্ট গুলো থেকে ট্যাগটি সরান!
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

<!-- Add/Edit Tag Modal -->
<div class="modal fade" id="tagModal" tabindex="-1" aria-labelledby="tagModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="modalTagForm" novalidate>
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" id="tagId" value="">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="tagModalLabel">
                        <i class="fas fa-plus-circle me-1"></i> <span id="modalTitleText">নতুন ট্যাগ যোগ করুন</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Tag Name -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">ট্যাগের নাম <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="modalTagName" class="form-control" placeholder="ট্যাগের নাম লিখুন" required>
                        <div class="invalid-feedback d-block" id="error-name" style="display: none;"></div>
                    </div>

                    <!-- Tag Slug -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">স্ল্যাগ (Slug)</label>
                        <input type="text" name="slug" id="modalTagSlug" class="form-control" placeholder="যেমন: anti-extremism">
                        <div class="invalid-feedback d-block" id="error-slug" style="display: none;"></div>
                    </div>

                    <!-- Color -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">ব্যাজের রঙ</label>
                        <div class="d-flex align-items-center">
                            <input type="color" name="color" id="modalColor" class="form-control form-control-color me-2" value="#6c757d" title="রঙ নির্বাচন করুন" style="width: 45px; height: 38px; padding: 3px;">
                            <input type="text" id="modalColorHex" class="form-control" placeholder="#6c757d" value="#6c757d">
                        </div>
                        <div class="invalid-feedback d-block" id="error-color" style="display: none;"></div>
                    </div>

                    <!-- Sort Order -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">ক্রম (Sort Order)</label>
                        <input type="number" name="sort_order" id="modalSortOrder" class="form-control" value="0">
                        <div class="invalid-feedback d-block" id="error-sort-order" style="display: none;"></div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">সংক্ষিপ্ত বিবরণ</label>
                        <textarea name="description" id="modalTagDescription" rows="3" class="form-control" placeholder="ট্যাগের সংক্ষিপ্ত বর্ণনা..."></textarea>
                        <div class="invalid-feedback d-block" id="error-description" style="display: none;"></div>
                    </div>

                    <!-- Status -->
                    <div class="mb-1 form-check form-switch">
                        <input type="checkbox" name="status" id="modalStatus" class="form-check-input" value="1" checked>
                        <label for="modalStatus" class="form-check-label fw-bold">সক্রিয় স্ট্যাটাস</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary" id="saveTagBtn">
                        <i class="fas fa-save me-1"></i> <span id="saveBtnText">সংরক্ষণ করুন</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> বাল্ক ডিলিট নিশ্চিত করুন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>আপনি কি নির্বাচিত <strong id="bulk-delete-count">0</strong> টি ট্যাগ ডিলিট করতে চান?</p>
                <p class="text-danger"><small>⚠️ এই কাজটি অপরিবর্তনীয়! ডিলিট করার পর আর ফিরিয়ে আনা যাবে না।</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-danger" id="confirm-bulk-delete">
                    <i class="fas fa-trash"></i> হ্যাঁ, ডিলিট করুন
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    var searchTimeout;
    var currentDeleteId = null;

    // ============================================
    // Load Tags AJAX
    // ============================================
    function loadTags(page = 1) {
        var search = $('#search-input').val();
        var status = $('#status-filter').val();
        var per_page = $('#per-page-filter').val();
        var date_from = $('#date-from-filter').val();

        $('#tags-table-container').hide();
        $('#loading-spinner').show();

        $.ajax({
            url: "{{ route('admin.blog.tags.index') }}",
            type: 'GET',
            data: {
                page: page,
                search: search,
                status: status,
                per_page: per_page,
                date_from: date_from
            },
            success: function(response) {
                $('#loading-spinner').hide();
                $('#tags-table-container').html(response.html).show();
                $('#total-count').text(response.total);
                
                attachEventHandlers();
                setTimeout(initializeSortable, 200);
            },
            error: function() {
                $('#loading-spinner').hide();
                $('#tags-table-container').show();
                toastr.error('ডাটা লোড করতে ব্যর্থ হয়েছে');
            }
        });
    }

    // ============================================
    // Attach Event Handlers
    // ============================================
    function attachEventHandlers() {
        // Checkbox Select All
        $('#selectAll').off('change').on('change', function() {
            $('.tag-checkbox').prop('checked', $(this).is(':checked'));
            updateBulkDeleteButton();
        });

        $('.tag-checkbox').off('change').on('change', function() {
            updateBulkDeleteButton();
        });

        // Edit Handler
        $('.edit-tag').off('click').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var slug = $(this).data('slug');
            var color = $(this).data('color') || '#6c757d';
            var sortOrder = $(this).data('sort-order') || 0;
            var status = $(this).data('status');
            var description = $(this).data('description') || '';

            $('#tagId').val(id);
            $('#formMethod').val('PUT');
            $('#modalTagName').val(name);
            $('#modalTagSlug').val(slug);
            $('#modalColor').val(color);
            $('#modalColorHex').val(color);
            $('#modalSortOrder').val(sortOrder);
            $('#modalTagDescription').val(description);
            $('#modalStatus').prop('checked', status == 1);

            $('#modalTitleText').text('ট্যাগ এডিট করুন');
            $('#saveBtnText').text('আপডেট করুন');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').hide().text('');
            $('#tagModal').modal('show');
        });

        // Create empty button Handler
        $('#create-tag-btn-empty').off('click').on('click', function() {
            $('#modalTagForm')[0].reset();
            $('#tagId').val('');
            $('#formMethod').val('POST');
            $('#modalColor').val('#6c757d');
            $('#modalColorHex').val('#6c757d');
            $('#modalStatus').prop('checked', true);
            $('#modalTitleText').text('নতুন ট্যাগ যোগ করুন');
            $('#saveBtnText').text('সংরক্ষণ করুন');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').hide().text('');
            $('#tagModal').modal('show');
        });

        // Delete Handler
        $('.delete-tag').off('click').on('click', function() {
            currentDeleteId = $(this).data('id');
            var name = $(this).data('name');
            var blogCount = parseInt($(this).data('blog-count'));

            $('#delete-name').text(name);
            $('#delete-blog-count').text(blogCount);

            if (blogCount > 0) {
                $('#delete-blog-warning').show();
                $('#confirm-delete').prop('disabled', true);
            } else {
                $('#delete-blog-warning').hide();
                $('#confirm-delete').prop('disabled', false);
            }

            $('#deleteModal').modal('show');
        });

        // Confirm Delete
        $('#confirm-delete').off('click').on('click', function() {
            if (!currentDeleteId) return;

            $.ajax({
                url: '{{ url("admin/blog/tags") }}/' + currentDeleteId,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    if (response.success) {
                        toastr.success(response.message);
                        loadTags();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    var message = 'ট্যাগ মুছে ফেলতে ব্যর্থ হয়েছে';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    toastr.error(message);
                    $('#deleteModal').modal('hide');
                }
            });
        });

        // Toggle Status
        $('.toggle-status').off('click').on('click', function() {
            var id = $(this).data('id');
            var btn = $(this);

            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: '{{ url("admin/blog/tags") }}/' + id + '/toggle-status',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadTags();
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

        // Pagination
        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var page = url.match(/page=(\d+)/) ? url.match(/page=(\d+)/)[1] : 1;
            if (page) loadTags(page);
        });
    }

    // ============================================
    // Update Bulk Delete Button Visibility
    // ============================================
    function updateBulkDeleteButton() {
        var checkedCount = $('.tag-checkbox:checked').length;
        $('#selected-count').text(checkedCount);
        if (checkedCount > 0) {
            $('#bulk-delete-btn').fadeIn();
        } else {
            $('#bulk-delete-btn').fadeOut();
        }
    }

    // ============================================
    // Bulk Delete Click Action
    // ============================================
    $('#bulk-delete-btn').on('click', function() {
        var checkedCount = $('.tag-checkbox:checked').length;
        $('#bulk-delete-count').text(checkedCount);
        $('#bulkDeleteModal').modal('show');
    });

    $('#confirm-bulk-delete').on('click', function() {
        var ids = [];
        $('.tag-checkbox:checked').each(function() {
            ids.push($(this).val());
        });

        $.ajax({
            url: "{{ route('admin.blog.tags.bulk-delete') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ids: ids
            },
            success: function(response) {
                $('#bulkDeleteModal').modal('hide');
                if (response.success) {
                    toastr.success(response.message);
                    loadTags();
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
                            url: "{{ route('admin.blog.tags.reorder') }}",
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
                                loadTags();
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
        searchTimeout = setTimeout(() => loadTags(), 500);
    });

    $('#status-filter, #per-page-filter, #date-from-filter').on('change', function() {
        loadTags();
    });

    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#status-filter').val('');
        $('#per-page-filter').val('20');
        $('#date-from-filter').val('');
        loadTags();
    });

    // Refresh Button
    $('#refresh-btn').on('click', function() {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        loadTags();
        setTimeout(function() {
            btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i>');
        }, 1000);
    });

    // ============================================
    // Add/Edit Modal Handlers & Submit
    // ============================================
    $('#create-tag-btn').on('click', function() {
        $('#modalTagForm')[0].reset();
        $('#tagId').val('');
        $('#formMethod').val('POST');
        $('#modalColor').val('#6c757d');
        $('#modalColorHex').val('#6c757d');
        $('#modalStatus').prop('checked', true);
        $('#modalTitleText').text('নতুন ট্যাগ যোগ করুন');
        $('#saveBtnText').text('সংরক্ষণ করুন');
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').hide().text('');
        $('#tagModal').modal('show');
    });

    // Color sync
    $('#modalColor').on('input', function() {
        $('#modalColorHex').val(this.value);
    });
    $('#modalColorHex').on('input', function() {
        var val = this.value;
        if (val.match(/^#[a-fA-F0-9]{6}$/)) {
            $('#modalColor').val(val);
        }
    });

    // Auto slug generation for creation only
    $('#modalTagName').on('keyup', function() {
        var id = $('#tagId').val();
        if (!id) {
            var slug = $(this).val()
                .toLowerCase()
                .replace(/[^a-z0-9\u0980-\u09ff-]/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
            $('#modalTagSlug').val(slug);
        }
    });

    // AJAX Form Submit
    $('#modalTagForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#tagId').val();
        var method = $('#formMethod').val();
        var url = method === 'POST' ? "{{ route('admin.blog.tags.store') }}" : "{{ url('admin/blog/tags') }}/" + id;

        $('#saveTagBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> প্রসেসিং...');

        var formData = {
            _token: "{{ csrf_token() }}",
            _method: method,
            name: $('#modalTagName').val(),
            slug: $('#modalTagSlug').val(),
            color: $('#modalColor').val(),
            sort_order: $('#modalSortOrder').val(),
            description: $('#modalTagDescription').val(),
            status: $('#modalStatus').is(':checked') ? 1 : 0
        };

        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').hide().text('');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#saveTagBtn').prop('disabled', false).html('<i class="fas fa-save me-1"></i> ' + $('#saveBtnText').text());
                if (response.success) {
                    $('#tagModal').modal('hide');
                    toastr.success(response.message);
                    loadTags();
                } else {
                    toastr.error(response.message || 'ট্যাগ সংরক্ষণ করতে ব্যর্থ হয়েছে');
                }
            },
            error: function(xhr) {
                $('#saveTagBtn').prop('disabled', false).html('<i class="fas fa-save me-1"></i> ' + $('#saveBtnText').text());
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, messages) {
                        var inputField = $('#modalTagName');
                        if (key === 'slug') inputField = $('#modalTagSlug');
                        if (key === 'sort_order') inputField = $('#modalSortOrder');
                        if (key === 'color') inputField = $('#modalColorHex');
                        if (key === 'description') inputField = $('#modalTagDescription');
                        
                        inputField.addClass('is-invalid');
                        var errorDiv = $('#error-' + key.replace('_', '-'));
                        if (errorDiv.length) {
                            errorDiv.text(messages[0]).show();
                        }
                    });
                    toastr.error('ফর্ম পূরণ সঠিকভাবে হয়নি, অনুগ্রহ করে চেক করুন।');
                } else {
                    var errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'সার্ভারে সমস্যা হয়েছে';
                    toastr.error(errorMsg);
                }
            }
        });
    });

    // Initialize
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
