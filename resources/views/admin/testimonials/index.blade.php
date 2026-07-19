{{-- resources/views/admin/testimonials/index.blade.php --}}

@extends('admin.layouts.master')

@section('page-title', 'টেস্টিমোনিয়াল ম্যানেজমেন্ট')

@push('styles')
<style>
    .testimonial-avatar {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #e9ecef;
    }
    .rating-stars {
        font-size: 14px;
        letter-spacing: 2px;
    }
    .rating-stars .fa-star {
        color: #ffc107;
    }
    .rating-stars .fa-star-o {
        color: #dee2e6;
    }
    .status-badge {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .status-badge:hover {
        opacity: 0.8;
        transform: scale(0.95);
    }
    .testimonial-content {
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
<div class="row px-3">
    <div class="col-md-4 mb-3">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="search-input" class="form-control" placeholder="নাম, কোম্পানি, কন্টেন্ট..." autocomplete="off">
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <select id="rating-filter" class="form-select">
            <option value="">সব রেটিং</option>
            <option value="5">৫ স্টার</option>
            <option value="4">৪ স্টার</option>
            <option value="3">৩ স্টার</option>
            <option value="2">২ স্টার</option>
            <option value="1">১ স্টার</option>
        </select>
    </div>
    <div class="col-md-2 mb-3">
        <select id="status-filter" class="form-select">
            <option value="">সব স্ট্যাটাস</option>
            <option value="active">সক্রিয়</option>
            <option value="inactive">নিষ্ক্রিয়</option>
        </select>
    </div>
    <div class="col-md-2 mb-3">
        <select id="per-page-filter" class="form-select">
            <option value="10">১০</option>
            <option value="20" selected>২০</option>
            <option value="50">৫০</option>
            <option value="100">১০০</option>
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
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-star me-2"></i> টেস্টিমোনিয়াল তালিকা
                <span class="badge bg-primary ms-2" id="total-count">{{ $testimonials->total() ?? $testimonials->count() }}</span>
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> নতুন টেস্টিমোনিয়াল
                    </a>
                    <a href="{{ route('admin.testimonials.export') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> এক্সপোর্ট
                    </a>
                    <button id="bulk-delete-btn" class="btn btn-danger btn-sm" style="display: none;">
                        <i class="fas fa-trash"></i> ডিলিট (<span id="selected-count">0</span>)
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



    <!-- Statistics Cards -->
    <div class="row g-2 mb-3 px-3">
        <!-- Total Comments -->
        <div class="col-md-2 col-6">
            <div class="card metric-card border-left-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">মোট মতামত</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['total'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-star"></i>
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
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">সক্রিয়</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['active'] ?? 0) }}</h5>
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
            <div class="card metric-card border-left-danger h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">নিষ্ক্রিয়</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['inactive'] ?? 0) }}</h5>
                        </div>
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avg Rating -->
        <div class="col-md-3 col-6">
            <div class="card metric-card border-left-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">গড় রেটিং</span>
                            <h5 class="mb-0 fw-bold">{{ number_format($stats['avg_rating'] ?? 0, 1) }} ★</h5>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rating Distribution -->
        <div class="col-md-3 col-6">
            <div class="card metric-card border-left-secondary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">রেটিং ডিস্ট্রিবিউশন</span>
                            <div class="mt-1">
                                @foreach($stats['ratings'] ?? [] as $star => $count)
                                    <span class="badge bg-warning text-dark me-1" style="font-size: 9px; padding: 2px 4px;">{{ $star }}★: {{ $count }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
            <!-- Table Container -->
            <div id="testimonials-table-container">
                @include('admin.testimonials.partials.table', ['testimonials' => $testimonials])
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
                <p>আপনি কি <strong id="delete-name"></strong> এর টেস্টিমোনিয়ালটি ডিলিট করতে চান?</p>
                <p class="text-danger"><small>এই কাজটি অপরিবর্তনীয়!</small></p>
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
                <p>আপনি কি <strong id="bulk-delete-count"></strong> টি টেস্টিমোনিয়াল ডিলিট করতে চান?</p>
                <p class="text-danger"><small>এই কাজটি অপরিবর্তনীয়!</small></p>
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

    // Load Testimonials via AJAX
    function loadTestimonials(page = 1) {
        if (isAjaxLoading) return;

        var search = $('#search-input').val();
        var rating = $('#rating-filter').val();
        var status = $('#status-filter').val();
        var perPage = $('#per-page-filter').val();

        isAjaxLoading = true;
        $('#loading-spinner').show();
        $('#testimonials-table-container').hide();

        $.ajax({
            url: "{{ route('admin.testimonials.index') }}",
            type: "GET",
            data: {
                search: search,
                rating: rating,
                status: status,
                per_page: perPage,
                page: page,
                _: Date.now()
            },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.html) {
                    $('#testimonials-table-container').html(response.html);
                    attachEventHandlers();
                    updateTotalCount(response.total || 0);
                    
                    // Initialize sortable
                    if ($('#sortable-table tbody').length) {
                        initializeSortable();
                    }
                }
                $('#loading-spinner').hide();
                $('#testimonials-table-container').show();
            },
            error: function() {
                toastr.error('ডাটা লোড করতে ব্যর্থ হয়েছে');
                $('#loading-spinner').hide();
                $('#testimonials-table-container').show();
            },
            complete: function() {
                isAjaxLoading = false;
            }
        });
    }

    // Update total count
    function updateTotalCount(count) {
        $('#total-count').text(count);
    }

    // Attach event handlers
    function attachEventHandlers() {
        // Select All
        $('#selectAll').off('change').on('change', function() {
            $('.testimonial-checkbox').prop('checked', $(this).prop('checked'));
            toggleBulkDeleteButton();
        });

        // Individual checkbox
        $('.testimonial-checkbox').off('change').on('change', function() {
            toggleBulkDeleteButton();
        });

        function toggleBulkDeleteButton() {
            var checkedCount = $('.testimonial-checkbox:checked').length;
            $('#selected-count').text(checkedCount);
            if (checkedCount > 0) {
                $('#bulk-delete-btn').show();
            } else {
                $('#bulk-delete-btn').hide();
            }
        }

        // Delete single
        $('.delete-testimonial').off('click').on('click', function() {
            deleteId = $(this).data('id');
            var name = $(this).data('name');
            $('#delete-name').text(name);
            $('#deleteModal').modal('show');
        });

        $('#confirm-delete').off('click').on('click', function() {
            if (!deleteId) return;

            $.ajax({
                url: '{{ url("admin/testimonials") }}/' + deleteId,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    if (response.success) {
                        toastr.success(response.message);
                        loadTestimonials();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('টেস্টিমোনিয়াল ডিলিট করতে ব্যর্থ হয়েছে');
                    $('#deleteModal').modal('hide');
                }
            });
        });

        // Bulk delete
        $('#bulk-delete-btn').off('click').on('click', function() {
            var ids = $('.testimonial-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (ids.length === 0) return;

            $('#bulk-delete-count').text(ids.length);
            $('#bulkDeleteModal').modal('show');

            $('#confirm-bulk-delete').off('click').on('click', function() {
                $.ajax({
                    url: "{{ route('admin.testimonials.bulk-delete') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: ids
                    },
                    success: function(response) {
                        $('#bulkDeleteModal').modal('hide');
                        if (response.success) {
                            toastr.success(response.message);
                            loadTestimonials();
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

        // Toggle status
        $('.toggle-status').off('click').on('click', function() {
            var id = $(this).data('id');

            $.ajax({
                url: '{{ url("admin/testimonials") }}/' + id + '/toggle-status',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadTestimonials();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে');
                }
            });
        });

        // Pagination
        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var page = url.match(/page=(\d+)/) ? url.match(/page=(\d+)/)[1] : 1;
            if (page) loadTestimonials(page);
        });
    }

    // Initialize Sortable
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
                            url: "{{ route('admin.testimonials.reorder') }}",
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
                                loadTestimonials();
                            }
                        });
                    }
                });
            }
        }
    }

    // Filter handlers
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadTestimonials(), 500);
    });

    $('#rating-filter, #status-filter, #per-page-filter').on('change', function() {
        loadTestimonials();
    });

    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#rating-filter').val('');
        $('#status-filter').val('');
        $('#per-page-filter').val('20');
        loadTestimonials();
    });

    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            $('#search-input').focus();
        }
        if (e.key === 'Escape') {
            $('#reset-filter').click();
        }
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
        "extendedTimeOut": "1000"
    };
});
</script>
@endpush