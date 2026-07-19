@extends('admin.layouts.master')

@section('page-title', 'Role Management')

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
<div class="row px-3">
    <div class="col-md-6 mb-3">
        <input type="text" id="search-input" class="form-control" placeholder="Search roles..." autocomplete="off">
    </div>
    <div class="col-md-3 mb-3">
        <select id="per-page-filter" class="form-control">
            <option value="10">10 items</option>
            <option value="20" selected>20 items</option>
            <option value="30">30 items</option>
            <option value="50">50 items</option>
            <option value="100">100 items</option>
            <option value="-1">All items</option>
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <div class="btn-group w-100">
            <button id="reset-filter" class="btn btn-secondary">
                <i class="fas fa-sync-alt"></i> Reset
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                <i class="fas fa-plus"></i> New Role
            </button>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row g-2 mb-3">
        <div class="col-md-6 col-6">
            <div class="card metric-card border-left-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">Total Roles</span>
                            <h5 class="mb-0 fw-bold metric-number">{{ number_format($stats['total']) }}</h5>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-6">
            <div class="card metric-card border-left-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">Total Permissions</span>
                            <h5 class="mb-0 fw-bold metric-number">{{ number_format($stats['permissions_total']) }}</h5>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-key"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-tags mr-1"></i>
                Roles List
            </h3>
        </div>

        <div class="card-body p-1">
            <div id="loading-spinner" class="text-center py-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading roles...</p>
            </div>

            <div id="roles-table-container">
                @include('admin.roles.partials.table', ['roles' => $roles])
            </div>
        </div>
    </div>
</div>

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle mr-1"></i> Create New Role
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addRoleForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Role Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g., editor">
                        <small class="text-muted">Use snake_case format (e.g., editor, content_manager)</small>
                    </div>
                    <div class="form-group mb-3">
                        <label>Guard Name</label>
                        <input type="text" name="guard_name" class="form-control" value="web" placeholder="web">
                        <small class="text-muted">Default: web</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-edit mr-1"></i> Edit Role
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRoleForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Role Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Guard Name</label>
                        <input type="text" name="guard_name" id="edit_guard_name" class="form-control" value="web">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Role</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let searchTimeout;
    let isAjaxLoading = false;

    function loadRoles(page = 1) {
        if (isAjaxLoading) return;

        var search = $('#search-input').val();
        var perPage = $('#per-page-filter').val();

        isAjaxLoading = true;
        $('#loading-spinner').show();
        $('#roles-table-container').hide();

        $.ajax({
            url: "{{ route('admin.roles.index') }}",
            type: "GET",
            data: { search: search, per_page: perPage, page: page, _: Date.now() },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.html) {
                    $('#roles-table-container').html(response.html);
                    if (response.stats) {
                        $('.metric-number').eq(0).text(response.stats.total);
                        $('.metric-number').eq(1).text(response.stats.permissions_total);
                    }
                    attachEventHandlers();
                }
                $('#loading-spinner').hide();
                $('#roles-table-container').show();
            },
            error: function() {
                toastr.error('Failed to load roles');
                $('#loading-spinner').hide();
                $('#roles-table-container').show();
            },
            complete: function() {
                isAjaxLoading = false;
            }
        });
    }

    function attachEventHandlers() {
        $('.delete-role').off('click').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');

            Swal.fire({
                title: 'Delete Role?',
                html: `Are you sure you want to delete <strong>${name}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("admin/roles") }}/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                loadRoles();
                            } else {
                                toastr.error(response.message);
                            }
                        }
                    });
                }
            });
        });

        $('.edit-role').off('click').on('click', function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_name').val($(this).data('name'));
            $('#edit_guard_name').val($(this).data('guard_name') || 'web');
            $('#editRoleModal').modal('show');
        });

        $('.permissions-role').off('click').on('click', function() {
            var id = $(this).data('id');
            window.location.href = '{{ url("admin/roles") }}/' + id + '/permissions';
        });

        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').match(/page=(\d+)/) ? $(this).attr('href').match(/page=(\d+)/)[1] : 1;
            if (page) loadRoles(page);
        });
    }

    $('#addRoleForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("admin.roles.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#addRoleModal').modal('hide');
                    $('#addRoleForm')[0].reset();
                    loadRoles();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if (errors) {
                    $.each(errors, function(key, value) { toastr.error(value[0]); });
                } else {
                    toastr.error('Failed to create role');
                }
            }
        });
    });

    $('#editRoleForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#edit_id').val();
        $.ajax({
            url: '{{ url("admin/roles") }}/' + id,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#editRoleModal').modal('hide');
                    loadRoles();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if (errors) {
                    $.each(errors, function(key, value) { toastr.error(value[0]); });
                } else {
                    toastr.error('Failed to update role');
                }
            }
        });
    });

    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadRoles(), 500);
    });

    $('#per-page-filter').on('change', function() { loadRoles(); });
    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#per-page-filter').val('20');
        loadRoles();
    });

    attachEventHandlers();
});
</script>
@endpush
