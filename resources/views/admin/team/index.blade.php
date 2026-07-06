@extends('admin.layouts.master')

@section('page-title', 'টিম ম্যানেজমেন্ট')

@push('styles')
<style>
    .team-member-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #e9ecef;
    }
    .team-member-image:hover {
        transform: scale(1.1);
        transition: transform 0.3s ease;
    }
    .social-links .btn-social {
        width: 28px;
        height: 28px;
        padding: 0;
        line-height: 28px;
        font-size: 12px;
        border-radius: 50%;
    }
    .status-badge {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .status-badge:hover {
        opacity: 0.8;
        transform: scale(0.95);
    }
    .table-row-hover {
        transition: background-color 0.3s ease;
    }
    .table-row-hover:hover {
        background-color: #f8f9fa;
    }
    .drag-handle {
        cursor: move;
        color: #6c757d;
    }
    .drag-handle:hover {
        color: #0d6efd;
    }
    .sortable-placeholder {
        background-color: #e9ecef;
        border: 2px dashed #0d6efd;
        height: 60px;
    }
    .filter-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('filter_input')
<div class="filter-card">
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" id="search-input" class="form-control" placeholder="নাম, ডিজাইনেশন, ইমেইল..." autocomplete="off" value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <select id="department-filter" class="form-select">
                <option value="">সব ডিপার্টমেন্ট</option>
                @php
                    $departments = App\Models\TeamMember::select('department')->distinct()->pluck('department')->filter();
                @endphp
                @foreach($departments as $dept)
                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 mb-3">
            <select id="status-filter" class="form-select">
                <option value="">সব স্ট্যাটাস</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
            </select>
        </div>
        <div class="col-md-2 mb-3">
            <select id="experience-filter" class="form-select">
                <option value="">সব অভিজ্ঞতা</option>
                <option value="0-2">০-২ বছর</option>
                <option value="3-5">৩-৫ বছর</option>
                <option value="6-10">৬-১০ বছর</option>
                <option value="10+">১০+ বছর</option>
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
        <div class="col-md-1 mb-3">
            <button id="reset-filter" class="btn btn-secondary w-100" title="রিসেট ফিল্টার">
                <i class="fas fa-undo-alt"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-friends me-2"></i> টিম মেম্বার তালিকা
                <span class="badge bg-primary ms-2" id="total-count">{{ $teamMembers->total() ?? $teamMembers->count() }}</span>
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.team.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> নতুন টিম মেম্বার
                    </a>
                    <a href="{{ route('admin.team.export') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> এক্সপোর্ট
                    </a>
                    <button id="bulk-delete-btn" class="btn btn-danger btn-sm" style="display: none;">
                        <i class="fas fa-trash"></i> ডিলিট (<span id="selected-count">0</span>)
                    </button>
                    <button id="reset-filter" class="btn btn-secondary btn-sm d-md-none">
                        <i class="fas fa-undo-alt"></i>
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
            <div id="team-table-container">
                @include('admin.team.partials.table', ['teamMembers' => $teamMembers])
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> নিশ্চিত করুন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>আপনি কি <strong id="delete-name"></strong> নামের টিম মেম্বারটিকে ডিলিট করতে চান?</p>
                <p class="text-danger"><small>এই কাজটি অপরিবর্তনীয়! ডিলিট করার পর আর ফিরিয়ে আনা যাবে না।</small></p>
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
                <p>আপনি কি <strong id="bulk-delete-count"></strong> টি টিম মেম্বার ডিলিট করতে চান?</p>
                <p class="text-danger"><small>এই কাজটি অপরিবর্তনীয়! ডিলিট করার পর আর ফিরিয়ে আনা যাবে না।</small></p>
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

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-user"></i> টিম মেম্বার বিস্তারিত</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="view-modal-body">
                <!-- Dynamic content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ</button>
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
    // Load Team Members via AJAX
    // ============================================
    function loadTeamMembers(page = 1) {
        if (isAjaxLoading) return;

        var search = $('#search-input').val();
        var department = $('#department-filter').val();
        var status = $('#status-filter').val();
        var experience = $('#experience-filter').val();
        var perPage = $('#per-page-filter').val();

        isAjaxLoading = true;
        $('#loading-spinner').show();
        $('#team-table-container').hide();

        $.ajax({
            url: "{{ route('admin.team.index') }}",
            type: "GET",
            data: {
                search: search,
                department: department,
                status: status,
                experience: experience,
                per_page: perPage,
                page: page,
                _: Date.now()
            },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.html) {
                    $('#team-table-container').html(response.html);
                    attachEventHandlers();
                    updateTotalCount(response.total || 0);
                    
                    // Initialize sortable if exists
                    if ($('#sortable-table tbody').length) {
                        initializeSortable();
                    }
                }
                $('#loading-spinner').hide();
                $('#team-table-container').show();
            },
            error: function(xhr) {
                console.error('Error loading data:', xhr);
                toastr.error('ডাটা লোড করতে ব্যর্থ হয়েছে');
                $('#loading-spinner').hide();
                $('#team-table-container').show();
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
            $('.team-checkbox').prop('checked', $(this).prop('checked'));
            toggleBulkDeleteButton();
        });

        // Individual checkbox
        $('.team-checkbox').off('change').on('change', function() {
            toggleBulkDeleteButton();
        });

        // Toggle bulk delete button
        function toggleBulkDeleteButton() {
            var checkedCount = $('.team-checkbox:checked').length;
            $('#selected-count').text(checkedCount);
            if (checkedCount > 0) {
                $('#bulk-delete-btn').show();
            } else {
                $('#bulk-delete-btn').hide();
            }
        }

        // ============================================
        // Delete Single
        // ============================================
        $('.delete-team-member').off('click').on('click', function() {
            deleteId = $(this).data('id');
            var name = $(this).data('name');
            $('#delete-name').text(name);
            $('#deleteModal').modal('show');
        });

        $('#confirm-delete').off('click').on('click', function() {
            if (!deleteId) return;

            $.ajax({
                url: '{{ url("admin/team") }}/' + deleteId,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    if (response.success) {
                        toastr.success(response.message);
                        loadTeamMembers();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('টিম মেম্বার ডিলিট করতে ব্যর্থ হয়েছে');
                    $('#deleteModal').modal('hide');
                }
            });
        });

        // ============================================
        // Bulk Delete
        // ============================================
        $('#bulk-delete-btn').off('click').on('click', function() {
            var ids = $('.team-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (ids.length === 0) return;

            $('#bulk-delete-count').text(ids.length);
            $('#bulkDeleteModal').modal('show');

            $('#confirm-bulk-delete').off('click').on('click', function() {
                $.ajax({
                    url: "{{ route('admin.team.bulk-delete') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: ids
                    },
                    success: function(response) {
                        $('#bulkDeleteModal').modal('hide');
                        if (response.success) {
                            toastr.success(response.message);
                            loadTeamMembers();
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
        // Toggle Status
        // ============================================
        $('.toggle-status').off('click').on('click', function() {
            var id = $(this).data('id');
            var btn = $(this);

            $.ajax({
                url: '{{ url("admin/team") }}/' + id + '/toggle-status',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadTeamMembers();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে');
                }
            });
        });

        // ============================================
        // View Details
        // ============================================
        $('.view-team-member').off('click').on('click', function() {
            var id = $(this).data('id');
            
            $.ajax({
                url: '{{ url("admin/team") }}/' + id,
                type: 'GET',
                success: function(response) {
                    if (response.success && response.html) {
                        $('#view-modal-body').html(response.html);
                        $('#viewModal').modal('show');
                    }
                },
                error: function() {
                    toastr.error('বিস্তারিত লোড করতে ব্যর্থ হয়েছে');
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
            if (page) loadTeamMembers(page);
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
                            url: "{{ route('admin.team.reorder') }}",
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
                                loadTeamMembers();
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
        searchTimeout = setTimeout(() => loadTeamMembers(), 500);
    });

    $('#department-filter, #status-filter, #experience-filter, #per-page-filter').on('change', function() {
        loadTeamMembers();
    });

    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#department-filter').val('');
        $('#status-filter').val('');
        $('#experience-filter').val('');
        $('#per-page-filter').val('20');
        loadTeamMembers();
    });

    // ============================================
    // Keyboard Shortcuts
    // ============================================
    $(document).on('keydown', function(e) {
        // Ctrl+F to focus search
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            $('#search-input').focus();
        }
        // Escape to reset filters
        if (e.key === 'Escape') {
            $('#reset-filter').click();
        }
    });

    // ============================================
    // Initialize
    // ============================================
    attachEventHandlers();
    
    // Initialize sortable after page load
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