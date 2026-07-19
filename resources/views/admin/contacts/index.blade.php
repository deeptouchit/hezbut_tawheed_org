{{-- resources/views/admin/contacts/index.blade.php --}}

@extends('admin.layouts.master')

@section('page-title', 'যোগাযোগ বার্তা ব্যবস্থাপনা')

@push('styles')
<style>
    .contact-status-badge {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .contact-status-badge:hover {
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
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
<div class="filter-card">
    <div class="row align-items-end">
        <div class="col-md-3 mb-3">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" id="search-input" class="form-control" placeholder="নাম, ইমেইল, বার্তা..." autocomplete="off" value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <select id="status-filter" class="form-select">
                <option value="">সব স্ট্যাটাস</option>
                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>অপঠিত</option>
                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>পঠিত</option>
                <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>উত্তর দেওয়া</option>
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
                <i class="fas fa-envelope me-2"></i> যোগাযোগ বার্তা তালিকা
                <span class="badge bg-primary ms-2" id="total-count">{{ $messages->total() ?? $messages->count() }}</span>
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.contacts.export', request()->query()) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> এক্সপোর্ট
                    </a>
                    <button id="mark-read-btn" class="btn btn-info btn-sm" style="display: none;">
                        <i class="fas fa-check"></i> পড়া হয়েছে (<span id="mark-read-count">0</span>)
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

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row g-2 mb-3 px-3">
                <!-- Total -->
                <div class="col-md-2 col-6">
                    <div class="card metric-card border-left-info h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small d-block mb-0" style="font-size: 11px;">মোট বার্তা</span>
                                    <h5 class="mb-0 fw-bold">{{ number_format($stats['total'] ?? 0) }}</h5>
                                </div>
                                <div class="stat-icon bg-info bg-opacity-10 text-info">
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Unread -->
                <div class="col-md-2 col-6">
                    <div class="card metric-card border-left-danger h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small d-block mb-0" style="font-size: 11px;">অপঠিত</span>
                                    <h5 class="mb-0 fw-bold">{{ number_format($stats['unread'] ?? 0) }}</h5>
                                </div>
                                <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                                    <i class="fas fa-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Read -->
                <div class="col-md-2 col-6">
                    <div class="card metric-card border-left-primary h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small d-block mb-0" style="font-size: 11px;">পঠিত</span>
                                    <h5 class="mb-0 fw-bold">{{ number_format($stats['read'] ?? 0) }}</h5>
                                </div>
                                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Replied -->
                <div class="col-md-2 col-6">
                    <div class="card metric-card border-left-success h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small d-block mb-0" style="font-size: 11px;">উত্তর দেওয়া</span>
                                    <h5 class="mb-0 fw-bold">{{ number_format($stats['replied'] ?? 0) }}</h5>
                                </div>
                                <div class="stat-icon bg-success bg-opacity-10 text-success">
                                    <i class="fas fa-reply"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today -->
                <div class="col-md-2 col-6">
                    <div class="card metric-card border-left-warning h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small d-block mb-0" style="font-size: 11px;">আজকের</span>
                                    <h5 class="mb-0 fw-bold">{{ number_format($stats['today'] ?? 0) }}</h5>
                                </div>
                                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- This Week -->
                <div class="col-md-2 col-6">
                    <div class="card metric-card border-left-secondary h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small d-block mb-0" style="font-size: 11px;">এই সপ্তাহে</span>
                                    <h5 class="mb-0 fw-bold">{{ number_format($stats['this_week'] ?? 0) }}</h5>
                                </div>
                                <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                                    <i class="fas fa-calendar-week"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div id="contacts-table-container">
                @include('admin.contacts.partials.table', ['messages' => $messages])
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
                <p>আপনি কি <strong id="delete-name"></strong> এর পাঠানো বার্তাটি ডিলিট করতে চান?</p>
                <p class="text-danger"><small>⚠️ এই কাজটি অপরিবর্তনীয়! ডিলিট করার পর আর ফিরিয়ে আনা যাবে না।</small></p>
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
                <p>আপনি কি <strong id="bulk-delete-count"></strong> টি বার্তা ডিলিট করতে চান?</p>
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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let searchTimeout;
    let isAjaxLoading = false;
    let deleteId = null;

    // ============================================
    // Load Contacts via AJAX
    // ============================================
    function loadContacts(page = 1) {
        if (isAjaxLoading) return;

        var search = $('#search-input').val();
        var status = $('#status-filter').val();
        var perPage = $('#per-page-filter').val();
        var dateFrom = $('#date-from-filter').val();
        var dateTo = $('#date-to-filter').val();

        isAjaxLoading = true;
        $('#loading-spinner').show();
        $('#contacts-table-container').hide();

        $.ajax({
            url: "{{ route('admin.contacts.index') }}",
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
                    $('#contacts-table-container').html(response.html);
                    attachEventHandlers();
                    updateTotalCount(response.total || 0);
                }
                $('#loading-spinner').hide();
                $('#contacts-table-container').show();
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                toastr.error('ডাটা লোড করতে ব্যর্থ হয়েছে');
                $('#loading-spinner').hide();
                $('#contacts-table-container').show();
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
            $('.contact-checkbox').prop('checked', $(this).prop('checked'));
            toggleBulkButtons();
        });

        // Individual checkbox
        $('.contact-checkbox').off('change').on('change', function() {
            toggleBulkButtons();
        });

        function toggleBulkButtons() {
            var checkedCount = $('.contact-checkbox:checked').length;
            $('#selected-count').text(checkedCount);
            $('#mark-read-count').text(checkedCount);

            if (checkedCount > 0) {
                $('#bulk-delete-btn').show();
                $('#mark-read-btn').show();
            } else {
                $('#bulk-delete-btn').hide();
                $('#mark-read-btn').hide();
            }
        }

        // ============================================
        // Delete Single
        // ============================================
        $('.delete-contact').off('click').on('click', function() {
            deleteId = $(this).data('id');
            var name = $(this).data('name');
            $('#delete-name').text(name);
            $('#deleteModal').modal('show');
        });

        $('#confirm-delete').off('click').on('click', function() {
            if (!deleteId) return;

            $.ajax({
                url: '{{ url("admin/contacts") }}/' + deleteId,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    if (response.success) {
                        toastr.success(response.message);
                        loadContacts();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('বার্তা ডিলিট করতে ব্যর্থ হয়েছে');
                    $('#deleteModal').modal('hide');
                }
            });
        });

        // ============================================
        // Bulk Delete
        // ============================================
        $('#bulk-delete-btn').off('click').on('click', function() {
            var ids = $('.contact-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (ids.length === 0) return;

            $('#bulk-delete-count').text(ids.length);
            $('#bulkDeleteModal').modal('show');

            $('#confirm-bulk-delete').off('click').on('click', function() {
                $.ajax({
                    url: "{{ route('admin.contacts.bulk-delete') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: ids
                    },
                    success: function(response) {
                        $('#bulkDeleteModal').modal('hide');
                        if (response.success) {
                            toastr.success(response.message);
                            loadContacts();
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
        // Mark as Read
        // ============================================
        $('#mark-read-btn').off('click').on('click', function() {
            var ids = $('.contact-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (ids.length === 0) return;

            Swal.fire({
                title: 'নিশ্চিত করুন',
                text: ids.length + ' টি বার্তা পড়া হয়েছে হিসেবে চিহ্নিত করতে চান?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'হ্যাঁ, চিহ্নিত করুন',
                cancelButtonText: 'বাতিল'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.contacts.mark-as-read') }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: ids
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                loadContacts();
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('বার্তা পড়া চিহ্নিত করতে ব্যর্থ হয়েছে');
                        }
                    });
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
            if (page) loadContacts(page);
        });
    }

    // ============================================
    // Filter Handlers
    // ============================================
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadContacts(), 500);
    });

    $('#status-filter, #per-page-filter, #date-from-filter, #date-to-filter').on('change', function() {
        loadContacts();
    });

    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#status-filter').val('');
        $('#per-page-filter').val('20');
        $('#date-from-filter').val('');
        $('#date-to-filter').val('');
        loadContacts();
    });

    // ============================================
    // Refresh Button
    // ============================================
    $('#refresh-btn').on('click', function() {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        loadContacts();
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
        if (e.key === 'Delete' && $('.contact-checkbox:checked').length > 0) {
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
