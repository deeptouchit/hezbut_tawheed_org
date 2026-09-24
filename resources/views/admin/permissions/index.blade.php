@extends('admin.layouts.master')

@section('page-title', 'Permissions Management')

@section('filter_input')
<div class="row px-3">
    <div class="col-md-4 mb-3">
        <input type="text" id="search-input" class="form-control" placeholder="Search permissions..." autocomplete="off">
    </div>
    <div class="col-md-3 mb-3">
        <select id="group-filter" class="form-control">
            <option value="">All Groups</option>
            @foreach($groups as $group)
                <option value="{{ $group }}">{{ ucfirst($group) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2 mb-3">
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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                <i class="fas fa-plus"></i> New Permission
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
                <i class="fas fa-key mr-1"></i>
                Permissions List
            </h3>
        </div>

        <div class="card-body p-1">
            <div id="loading-spinner" class="text-center py-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading permissions...</p>
            </div>

            <div id="permissions-table-container">
                @include('admin.permissions.partials.table', ['permissions' => $permissions])
            </div>
        </div>
    </div>
</div>

<!-- Add Permission Modal -->
<div class="modal fade" id="addPermissionModal" tabindex="-1" aria-labelledby="addPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addPermissionModalLabel">
                    <i class="fas fa-plus-circle mr-1"></i> Create New Permission
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addPermissionForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Permission Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g., user_create">
                        <small class="text-muted">Use snake_case format (e.g., user_create, role_edit)</small>
                    </div>

                    <div class="form-group mb-3">
                        <label>Group <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="group_name" id="add_group_name" class="form-control" required>
                                <option value="">Select Group</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group }}">{{ ucfirst($group) }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-secondary" id="addNewGroupBtn" title="Add New Group">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <small class="text-muted">Select existing group or add new group</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Permission</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Permission Modal -->
<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-labelledby="editPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editPermissionModalLabel">
                    <i class="fas fa-edit mr-1"></i> Edit Permission
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPermissionForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Permission Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                        <small class="text-muted">Use snake_case format</small>
                    </div>
                    <div class="form-group mb-3">
                        <label>Group <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="group_name" id="edit_group_name" class="form-control" required>
                                <option value="">Select Group</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group }}">{{ ucfirst($group) }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-secondary" id="editNewGroupBtn" title="Add New Group">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <small class="text-muted">Select existing group or add new group</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Permission</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add New Group Modal -->
<div class="modal fade" id="addNewGroupModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle mr-1"></i> Add New Group
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label>Group Name <span class="text-danger">*</span></label>
                    <input type="text" id="new_group_name" class="form-control" placeholder="e.g., reports, dashboard">
                    <small class="text-muted">Use lowercase and underscore (e.g., reports, user_management)</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmAddGroupBtn">
                    <i class="fas fa-check"></i> Add Group
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
    let currentModal = 'add'; // 'add' or 'edit'

    // Load permissions via AJAX
    function loadPermissions(page = 1) {
        if (isAjaxLoading) return;

        var search = $('#search-input').val();
        var group = $('#group-filter').val();
        var perPage = $('#per-page-filter').val();

        isAjaxLoading = true;
        $('#loading-spinner').show();
        $('#permissions-table-container').hide();

        $.ajax({
            url: "{{ route('admin.permissions.index') }}",
            type: "GET",
            data: {
                search: search,
                group: group,
                per_page: perPage,
                page: page,
                _: Date.now()
            },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.html) {
                    $('#permissions-table-container').html(response.html);
                    attachEventHandlers();
                }
                $('#loading-spinner').hide();
                $('#permissions-table-container').show();
            },
            error: function() {
                toastr.error('Failed to load permissions');
                $('#loading-spinner').hide();
                $('#permissions-table-container').show();
            },
            complete: function() {
                isAjaxLoading = false;
            }
        });
    }

    // Reload groups dropdown
    function reloadGroups(selectedGroup = null) {
        $.ajax({
            url: '{{ route("admin.permissions.groups") }}',
            type: 'GET',
            success: function(response) {
                if (response.success && response.groups) {
                    var options = '<option value="">Select Group</option>';
                    $.each(response.groups, function(index, group) {
                        options += `<option value="${group}">${group.charAt(0).toUpperCase() + group.slice(1)}</option>`;
                    });

                    $('#add_group_name').html(options);
                    $('#edit_group_name').html(options);

                    if (selectedGroup) {
                        $('#add_group_name').val(selectedGroup);
                        $('#edit_group_name').val(selectedGroup);
                    }
                }
            },
            error: function() {
                toastr.error('Failed to load groups');
            }
        });
    }

    // Add new group function
    function addNewGroup(modalType) {
        var newGroup = $('#new_group_name').val().trim().toLowerCase().replace(/\s+/g, '_');

        if (!newGroup) {
            toastr.error('Please enter group name');
            return;
        }

        // Check if group already exists in dropdown
        var exists = false;
        $('#add_group_name option, #edit_group_name option').each(function() {
            if ($(this).val() === newGroup) {
                exists = true;
                return false;
            }
        });

        if (exists) {
            toastr.warning('Group already exists');
            $('#addNewGroupModal').modal('hide');
            $('#new_group_name').val('');

            if (modalType === 'add') {
                $('#add_group_name').val(newGroup);
            } else {
                $('#edit_group_name').val(newGroup);
            }
            return;
        }

        // Add new group to both dropdowns
        var newOption = `<option value="${newGroup}">${newGroup.charAt(0).toUpperCase() + newGroup.slice(1)}</option>`;
        $('#add_group_name').append(newOption);
        $('#edit_group_name').append(newOption);

        // Select the new group
        if (modalType === 'add') {
            $('#add_group_name').val(newGroup);
        } else {
            $('#edit_group_name').val(newGroup);
        }

        toastr.success('New group added successfully');
        $('#addNewGroupModal').modal('hide');
        $('#new_group_name').val('');

        // Reload groups in filter
        reloadGroups();
    }

    // Attach event handlers
    function attachEventHandlers() {
        // Delete permission
        $('.delete-permission').off('click').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');

            Swal.fire({
                title: 'Delete Permission?',
                html: `Are you sure you want to delete <strong>${name}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("admin/permissions") }}/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                loadPermissions();
                                reloadGroups();
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            var message = xhr.responseJSON?.message || 'Failed to delete permission';
                            toastr.error(message);
                        }
                    });
                }
            });
        });

        // Edit permission
        $('.edit-permission').off('click').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var group = $(this).data('group');

            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_group_name').val(group);

            $('#editPermissionModal').modal('show');
        });

        // Pagination
        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').match(/page=(\d+)/) ? $(this).attr('href').match(/page=(\d+)/)[1] : 1;
            if (page) loadPermissions(page);
        });
    }

    // Add new group button click for add modal
    $('#addNewGroupBtn').on('click', function() {
        currentModal = 'add';
        $('#new_group_name').val('');
        $('#addNewGroupModal').modal('show');
    });

    // Add new group button click for edit modal
    $('#editNewGroupBtn').on('click', function() {
        currentModal = 'edit';
        $('#new_group_name').val('');
        $('#addNewGroupModal').modal('show');
    });

    // Confirm add group
    $('#confirmAddGroupBtn').on('click', function() {
        addNewGroup(currentModal);
    });

    // Enter key in new group input
    $('#new_group_name').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            addNewGroup(currentModal);
        }
    });

    // Add permission form submit
    $('#addPermissionForm').on('submit', function(e) {
        e.preventDefault();

        var formData = {
            name: $('input[name="name"]').val(),
            group_name: $('#add_group_name').val(),
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: '{{ route("admin.permissions.store") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#addPermissionModal').modal('hide');
                    $('#addPermissionForm')[0].reset();
                    loadPermissions();
                    reloadGroups();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if (errors) {
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                } else {
                    toastr.error('Failed to create permission');
                }
            }
        });
    });

    // Edit permission form submit
    $('#editPermissionForm').on('submit', function(e) {
        e.preventDefault();

        var id = $('#edit_id').val();
        var formData = {
            name: $('#edit_name').val(),
            group_name: $('#edit_group_name').val(),
            _token: '{{ csrf_token() }}',
            _method: 'PUT'
        };

        $.ajax({
            url: '{{ url("admin/permissions") }}/' + id,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#editPermissionModal').modal('hide');
                    loadPermissions();
                    reloadGroups();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if (errors) {
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                } else {
                    toastr.error('Failed to update permission');
                }
            }
        });
    });

    // Filter handlers
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadPermissions(), 500);
    });

    $('#group-filter, #per-page-filter').on('change', function() {
        loadPermissions();
    });

    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#group-filter').val('');
        $('#per-page-filter').val('20');
        loadPermissions();
    });

    attachEventHandlers();
});
</script>
@endpush
