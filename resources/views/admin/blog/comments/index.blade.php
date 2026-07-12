@extends('admin.layouts.master')

@section('page-title', 'ব্লগ মন্তব্য ম্যানেজমেন্ট')

@push('styles')
<style>
    .metric-card {
        border-radius: 8px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 10px -3px rgba(0, 0, 0, 0.08);
    }
    .metric-card .card-body {
        padding: 10px 14px !important;
    }
    .border-left-primary { border-left: 3px solid #006A4E !important; }
    .border-left-success { border-left: 3px solid #2e7d32 !important; }
    .border-left-info { border-left: 3px solid #0288d1 !important; }
    .border-left-warning { border-left: 4px solid #f57c00 !important; }
    .border-left-danger { border-left: 4px solid #d32f2f !important; }
    .border-left-secondary { border-left: 4px solid #757575 !important; }

    .stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
</style>
@endpush

@section('filter_input')
<!-- Filter Section -->
<div class="card border shadow-none mb-3 bg-light-subtle">
    <div class="card-body p-3">
        <div class="row g-2">
            <div class="col-md-3 col-sm-6">
                <input type="text" id="search-input" class="form-control" placeholder="নাম, ইমেইল, মন্তব্য খুঁজুন..." autocomplete="off" value="{{ request('search') }}">
            </div>
            <div class="col-md-3 col-sm-6">
                <select id="blog-filter" class="form-select">
                    <option value="">সব ব্লগ</option>
                    @foreach($blogs as $blog)
                        <option value="{{ $blog->id }}" {{ request('blog_id') == $blog->id ? 'selected' : '' }}>
                            {{ Str::limit($blog->title, 30) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <select id="status-filter" class="form-select">
                    <option value="">সব স্ট্যাটাস</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>অ্যাপ্রুভড</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>পেন্ডিং</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <select id="type-filter" class="form-select">
                    <option value="">সব টাইপ</option>
                    <option value="parent" {{ request('type') == 'parent' ? 'selected' : '' }}>প্যারেন্ট</option>
                    <option value="reply" {{ request('type') == 'reply' ? 'selected' : '' }}>রিপ্লাই</option>
                </select>
            </div>
            <div class="col-md-1 col-sm-6">
                <select id="per-page-filter" class="form-select">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>১০টি</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : (request('per_page') == '' ? 'selected' : '') }}>২০টি</option>
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
        <!-- Total Comments -->
        <div class="col-md-2 col-6">
            <div class="card metric-card border-left-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">মোট মন্তব্য</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['total'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-comments"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved Comments -->
        <div class="col-md-2 col-6">
            <div class="card metric-card border-left-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">অ্যাপ্রুভড</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['approved'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Comments -->
        <div class="col-md-2 col-6">
            <div class="card metric-card border-left-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">পেন্ডিং</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['pending'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today Comments -->
        <div class="col-md-2 col-6">
            <div class="card metric-card border-left-danger h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">আজকের</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['today'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Replies Comments -->
        <div class="col-md-2 col-6">
            <div class="card metric-card border-left-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">রিপ্লাই</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['replies'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-reply"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Parent Comments -->
        <div class="col-md-2 col-6">
            <div class="card metric-card border-left-secondary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">প্যারেন্ট</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['parent_comments'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                            <i class="fas fa-user-friends"></i>
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
                <i class="fas fa-comments me-2"></i> ব্লগ মন্তব্য তালিকা
                <span class="badge bg-primary ms-2" id="total-count">{{ $comments->total() ?? $comments->count() }}</span>
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.blog.comments.export', request()->query()) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> এক্সপোর্ট
                    </a>
                    <button id="bulk-approve-btn" class="btn btn-info btn-sm" style="display: none;">
                        <i class="fas fa-check"></i> অ্যাপ্রুভ (<span id="bulk-approve-count">0</span>)
                    </button>
                    <button id="bulk-delete-btn" class="btn btn-danger btn-sm" style="display: none;">
                        <i class="fas fa-trash"></i> ডিলিট (<span id="selected-count">0</span>)
                    </button>
                    <button id="delete-pending-btn" class="btn btn-warning btn-sm" title="সব পেন্ডিং মন্তব্য মুছুন">
                        <i class="fas fa-trash-alt"></i> পেন্ডিং ক্লিয়ার
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
            <div id="comments-table-container">
                @include('admin.blog.comments.partials.table', ['comments' => $comments])
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
                <p>আপনি কি <strong id="delete-author"></strong> এর মন্তব্যটি ডিলিট করতে চান?</p>
                <p class="text-muted" id="delete-comment-preview"></p>
                @if(isset($comment) && !$comment->parent_id && $comment->replies_count > 0)
                    <p class="text-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        এই মন্তব্যের <strong>{{ $comment->replies_count }}</strong> টি রিপ্লাইও ডিলিট হবে।
                    </p>
                @endif
                <p class="text-danger"><small>⚠️ এই কাজটি অপরিবর্তনীয়!</small></p>
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
                <p>আপনি কি <strong id="bulk-delete-count"></strong> টি মন্তব্য ডিলিট করতে চান?</p>
                <p class="text-danger"><small>⚠️ এই কাজটি অপরিবর্তনীয়!</small></p>
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

<!-- Bulk Approve Modal -->
<div class="modal fade" id="bulkApproveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-check-circle"></i> বাল্ক অ্যাপ্রুভ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>আপনি কি <strong id="bulk-approve-count-text"></strong> টি মন্তব্য অ্যাপ্রুভ করতে চান?</p>
                <p class="text-success"><small>✅ অ্যাপ্রুভ করলে প্যারেন্ট কমেন্টও অ্যাপ্রুভ হবে (যদি প্রয়োজন হয়)</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-success" id="confirm-bulk-approve">
                    <i class="fas fa-check"></i> সব অ্যাপ্রুভ
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Pending Modal -->
<div class="modal fade" id="deletePendingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> সব পেন্ডিং মন্তব্য মুছুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>আপনি কি <strong>{{ $stats['pending'] ?? 0 }}</strong> টি পেন্ডিং মন্তব্য মুছে ফেলতে চান?</p>
                <p class="text-danger"><small>⚠️ এই কাজটি অপরিবর্তনীয়! সব পেন্ডিং মন্তব্য ও তাদের রিপ্লাই মুছে যাবে।</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-pending">
                    <i class="fas fa-trash"></i> সব মুছুন
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let searchTimeout;
    let isAjaxLoading = false;
    let deleteId = null;

    // ============================================
    // Load Comments via AJAX
    // ============================================
    function loadComments(page = 1) {
        if (isAjaxLoading) return;

        var search = $('#search-input').val();
        var blog = $('#blog-filter').val();
        var status = $('#status-filter').val();
        var type = $('#type-filter').val();
        var perPage = $('#per-page-filter').val();

        isAjaxLoading = true;
        $('#loading-spinner').show();
        $('#comments-table-container').hide();

        $.ajax({
            url: "{{ route('admin.blog.comments.index') }}",
            type: "GET",
            data: {
                search: search,
                blog_id: blog,
                status: status,
                type: type,
                per_page: perPage,
                page: page,
                _: Date.now()
            },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.html) {
                    $('#comments-table-container').html(response.html);
                    attachEventHandlers();
                    updateTotalCount(response.total || 0);
                }
                $('#loading-spinner').hide();
                $('#comments-table-container').show();
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                toastr.error('ডাটা লোড করতে ব্যর্থ হয়েছে');
                $('#loading-spinner').hide();
                $('#comments-table-container').show();
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
            $('.comment-checkbox').prop('checked', $(this).prop('checked'));
            toggleBulkButtons();
        });

        // Individual checkbox
        $('.comment-checkbox').off('change').on('change', function() {
            toggleBulkButtons();
        });

        function toggleBulkButtons() {
            var checkedCount = $('.comment-checkbox:checked').length;
            $('#selected-count').text(checkedCount);
            $('#bulk-approve-count').text(checkedCount);

            if (checkedCount > 0) {
                $('#bulk-delete-btn').show();
                $('#bulk-approve-btn').show();
            } else {
                $('#bulk-delete-btn').hide();
                $('#bulk-approve-btn').hide();
            }
        }

        // ============================================
        // Delete Single
        // ============================================
        $('.delete-comment').off('click').on('click', function() {
            deleteId = $(this).data('id');
            var author = $(this).data('author');
            var comment = $(this).data('comment');
            var isParent = $(this).data('is-parent') || false;
            var repliesCount = $(this).data('replies-count') || 0;

            $('#delete-author').text(author);
            $('#delete-comment-preview').text('"' + comment + '"');

            // Show warning if parent with replies
            if (isParent && repliesCount > 0) {
                $('.text-warning').show();
                $('.text-warning strong').text(repliesCount);
            } else {
                $('.text-warning').hide();
            }

            $('#deleteModal').modal('show');
        });

        $('#confirm-delete').off('click').on('click', function() {
            if (!deleteId) return;

            $.ajax({
                url: '{{ url("admin/blog/comments") }}/' + deleteId,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    if (response.success) {
                        toastr.success(response.message);
                        loadComments();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('মন্তব্য ডিলিট করতে ব্যর্থ হয়েছে');
                    $('#deleteModal').modal('hide');
                }
            });
        });

        // ============================================
        // Approve Single
        // ============================================
        $('.approve-comment').off('click').on('click', function() {
            var id = $(this).data('id');
            var btn = $(this);

            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: '{{ url("admin/blog/comments") }}/' + id + '/approve',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadComments();
                    } else {
                        toastr.error(response.message);
                        btn.prop('disabled', false).html('<i class="fas fa-check"></i>');
                    }
                },
                error: function() {
                    toastr.error('মন্তব্য অ্যাপ্রুভ করতে ব্যর্থ হয়েছে');
                    btn.prop('disabled', false).html('<i class="fas fa-check"></i>');
                }
            });
        });

        // ============================================
        // Disapprove Single
        // ============================================
        $('.disapprove-comment').off('click').on('click', function() {
            var id = $(this).data('id');

            Swal.fire({
                title: 'আপনি কি নিশ্চিত?',
                text: 'এই মন্তব্যটি ডিসঅ্যাপ্রুভ করতে চান?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'হ্যাঁ, ডিসঅ্যাপ্রুভ',
                cancelButtonText: 'বাতিল'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("admin/blog/comments") }}/' + id + '/disapprove',
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                loadComments();
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('মন্তব্য ডিসঅ্যাপ্রুভ করতে ব্যর্থ হয়েছে');
                        }
                    });
                }
            });
        });

        // ============================================
        // Bulk Delete
        // ============================================
        $('#bulk-delete-btn').off('click').on('click', function() {
            var ids = $('.comment-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (ids.length === 0) return;

            $('#bulk-delete-count').text(ids.length);
            $('#bulkDeleteModal').modal('show');

            $('#confirm-bulk-delete').off('click').on('click', function() {
                $.ajax({
                    url: "{{ route('admin.blog.comments.bulk-delete') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: ids
                    },
                    success: function(response) {
                        $('#bulkDeleteModal').modal('hide');
                        if (response.success) {
                            toastr.success(response.message);
                            loadComments();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('বাল্ক ডিলিট করতে ব্যর্থ হয়েছে');
                        $('#bulkDeleteModal').modal('hide');
                    }
                });
            });
        });

        // ============================================
        // Bulk Approve
        // ============================================
        $('#bulk-approve-btn').off('click').on('click', function() {
            var ids = $('.comment-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (ids.length === 0) return;

            $('#bulk-approve-count-text').text(ids.length);
            $('#bulkApproveModal').modal('show');

            $('#confirm-bulk-approve').off('click').on('click', function() {
                $.ajax({
                    url: "{{ route('admin.blog.comments.bulk-approve') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: ids
                    },
                    success: function(response) {
                        $('#bulkApproveModal').modal('hide');
                        if (response.success) {
                            toastr.success(response.message);
                            loadComments();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('বাল্ক অ্যাপ্রুভ করতে ব্যর্থ হয়েছে');
                        $('#bulkApproveModal').modal('hide');
                    }
                });
            });
        });

        // ============================================
        // Delete All Pending
        // ============================================
        $('#delete-pending-btn').off('click').on('click', function() {
            var pendingCount = {{ $stats['pending'] ?? 0 }};

            if (pendingCount === 0) {
                toastr.info('কোন পেন্ডিং মন্তব্য নেই');
                return;
            }

            $('#deletePendingModal').modal('show');

            $('#confirm-delete-pending').off('click').on('click', function() {
                $.ajax({
                    url: "{{ route('admin.blog.comments.delete-pending') }}",
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        $('#deletePendingModal').modal('hide');
                        if (response.success) {
                            toastr.success(response.message);
                            loadComments();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('পেন্ডিং মন্তব্য মুছে ফেলতে ব্যর্থ হয়েছে');
                        $('#deletePendingModal').modal('hide');
                    }
                });
            });
        });

        // ============================================
        // View Blog
        // ============================================
        $('.view-blog').off('click').on('click', function() {
            var blogId = $(this).data('blog-id');
            var url = '{{ url("admin/blog/posts") }}/' + blogId;
            window.open(url, '_blank');
        });

        // ============================================
        // View Comment Detail
        // ============================================
        $('.view-comment-detail').off('click').on('click', function() {
            var id = $(this).data('id');
            var url = '{{ url("admin/blog/comments") }}/' + id;
            window.open(url, '_blank');
        });

        // ============================================
        // Pagination
        // ============================================
        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var page = url.match(/page=(\d+)/) ? url.match(/page=(\d+)/)[1] : 1;
            if (page) loadComments(page);
        });
    }

    // ============================================
    // Filter Handlers
    // ============================================
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadComments(), 500);
    });

    $('#blog-filter, #status-filter, #type-filter, #per-page-filter').on('change', function() {
        loadComments();
    });

    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#blog-filter').val('');
        $('#status-filter').val('');
        $('#type-filter').val('');
        $('#per-page-filter').val('20');
        loadComments();
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
        if (e.key === 'Delete' && $('.comment-checkbox:checked').length > 0) {
            e.preventDefault();
            $('#bulk-delete-btn').click();
        }
    });

    // ============================================
    // Initialize
    // ============================================
    attachEventHandlers();

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
