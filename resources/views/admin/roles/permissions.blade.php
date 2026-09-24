@extends('admin.layouts.master')

@section('page-title', 'Role Permissions - ' . $role->name)

@push('styles')
<style>
    .permission-group-card {
        margin-bottom: 1.5rem;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .permission-group-card .card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 0.75rem 1rem;
        flex-shrink: 0;
    }
    .permission-group-card .card-body {
        padding: 1rem;
        flex: 1;
        overflow-y: auto;
        max-height: 350px;
    }
    .permission-item {
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }
    .permission-item .form-check {
        padding-left: 1.75rem;
        width: 100%;
    }
    .permission-item .form-check-input {
        margin-top: 0.15rem;
        cursor: pointer;
    }
    .permission-item .form-check-label {
        font-size: 0.875rem;
        cursor: pointer;
        user-select: none;
        display: inline-block;
        width: calc(100% - 1.25rem);
    }
    .permission-item .form-check-label:hover {
        color: #0d6efd;
    }
    .permission-item .form-check-input:checked + .form-check-label {
        color: #198754;
        font-weight: 500;
    }
    .permissions-count {
        font-size: 0.7rem;
        background: #e9ecef;
        padding: 0.2rem 0.5rem;
        border-radius: 1rem;
        margin-left: 0.5rem;
        display: inline-block;
    }
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.6);
        z-index: 9999;
        display: none;
        justify-content: center;
        align-items: center;
    }
    .loading-content {
        background: white;
        padding: 1.5rem 2rem;
        border-radius: 0.5rem;
        text-align: center;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }
    .equal-height-row {
        display: flex;
        flex-wrap: wrap;
    }
    .equal-height-row > [class*='col-'] {
        display: flex;
        flex-direction: column;
    }
</style>
@endpush

@section('filter_input')
<div class="row px-3">
    <div class="col-12">
        <div class="btn-group w-100">
            <button type="button" id="selectAllBtn" class="btn btn-outline-primary">
                <i class="fas fa-check-double"></i> Select All
            </button>
            <button type="button" id="deselectAllBtn" class="btn btn-outline-secondary">
                <i class="fas fa-times"></i> Deselect All
            </button>
            <button type="submit" form="permissionsForm" class="btn btn-primary" id="savePermissionsBtn">
                <i class="fas fa-save"></i> Save Permissions
            </button>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    {{-- <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-content">
            <div class="spinner-border text-primary mb-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mb-0">Saving permissions...</p>
        </div>
    </div> --}}

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-key me-2 text-primary"></i>
                Permissions for: <span class="badge bg-{{ $role->name == 'super_admin' ? 'danger' : ($role->name == 'admin' ? 'warning' : 'primary') }}">{{ $role->name }}</span>
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Roles
                </a>
            </div>
        </div>

        <form id="permissionsForm">
            @csrf
            <div class="card-body">
                @php $totalPermissions = 0; @endphp

                <div class="row equal-height-row">
                    @foreach($permissionsByGroup as $group => $perms)
                        @php
                            $groupPermissions = $perms->count();
                            $selectedInGroup = collect($perms)->filter(fn($p) => in_array($p->name, $rolePermissions))->count();
                            $totalPermissions += $groupPermissions;
                            $groupSlug = Str::slug($group, '_');
                        @endphp
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="permission-group-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-folder-open me-2 text-primary"></i>
                                        <strong>{{ ucfirst($group) }}</strong>
                                        <span class="permissions-count">{{ $selectedInGroup }}/{{ $groupPermissions }}</span>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox"
                                               class="form-check-input select-all"
                                               data-group="{{ $group }}"
                                               id="selectAll_{{ $groupSlug }}"
                                               {{ $selectedInGroup == $groupPermissions ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="selectAll_{{ $groupSlug }}">
                                            Select All
                                        </label>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @foreach($perms as $permission)
                                        <div class="permission-item">
                                            <div class="form-check">
                                                <input type="checkbox"
                                                       name="permissions[]"
                                                       value="{{ $permission->name }}"
                                                       class="form-check-input permission-checkbox permission-group-{{ $group }}"
                                                       id="perm_{{ $permission->id }}"
                                                       {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                    {{ $permission->display_name ?? str_replace('_', ' ', $permission->name) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($permissionsByGroup->isEmpty())
                    <div class="alert alert-warning text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3 d-block"></i>
                        <p class="mb-3">No permissions found. Please create permissions first.</p>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Permissions
                        </a>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let isSubmitting = false;
    let formChanged = false;

    // Update select all checkbox state
    function updateSelectAll(group) {
        var total = $('.permission-group-' + group).length;
        var checked = $('.permission-group-' + group + ':checked').length;
        var selectAllCheckbox = $('#selectAll_' + group.replace(/\s+/g, '_'));

        if (total === checked && total > 0) {
            selectAllCheckbox.prop('checked', true);
            selectAllCheckbox.prop('indeterminate', false);
        } else if (checked === 0) {
            selectAllCheckbox.prop('checked', false);
            selectAllCheckbox.prop('indeterminate', false);
        } else {
            selectAllCheckbox.prop('checked', false);
            selectAllCheckbox.prop('indeterminate', true);
        }

        // Update group count display
        var countSpan = $('.permission-group-card:has(.permission-group-' + group + ') .permissions-count');
        if (countSpan.length) {
            countSpan.text(checked + '/' + total);
        }
    }

    // Select All functionality
    $('.select-all').on('change', function() {
        var group = $(this).data('group');
        var isChecked = $(this).is(':checked');
        $('.permission-group-' + group).prop('checked', isChecked).trigger('change');
    });

    // Update select all when individual checkboxes change
    $(document).on('change', '.permission-checkbox', function() {
        var classes = $(this).attr('class');
        var match = classes.match(/permission-group-(\w+)/);
        if (match && match[1]) {
            updateSelectAll(match[1]);
        }
        formChanged = true;
    });

    // Select All button
    $('#selectAllBtn').on('click', function() {
        $('.permission-checkbox').prop('checked', true).trigger('change');
        $('.select-all').prop('checked', true);
        if (typeof toastr !== 'undefined') {
            toastr.info('All permissions selected');
        }
    });

    // Deselect All button
    $('#deselectAllBtn').on('click', function() {
        $('.permission-checkbox').prop('checked', false).trigger('change');
        $('.select-all').prop('checked', false);
        if (typeof toastr !== 'undefined') {
            toastr.info('All permissions deselected');
        }
    });

    // Initialize all groups
    $('.permission-checkbox').each(function() {
        var classes = $(this).attr('class');
        var match = classes.match(/permission-group-(\w+)/);
        if (match && match[1]) {
            updateSelectAll(match[1]);
        }
    });

    // Form submit
    $('#permissionsForm').on('submit', function(e) {
        e.preventDefault();

        if (isSubmitting) return;

        var selectedCount = $('.permission-checkbox:checked').length;

        Swal.fire({
            title: 'Save Permissions?',
            html: `<p>You are about to assign <strong class="text-primary">${selectedCount}</strong> permissions to role <strong class="text-${roleBadgeColor}">${roleName}</strong>.</p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-save"></i> Save Changes',
            cancelButtonText: '<i class="fas fa-times"></i> Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                submitForm();
            }
        });
    });

    function submitForm() {
        isSubmitting = true;
        var btn = $('#savePermissionsBtn');
        var form = $('#permissionsForm');
        var formData = form.serialize();

        $('#loadingOverlay').fadeIn(200);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: '{{ route("admin.roles.permissions.sync", $role->id) }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message || 'Permissions saved successfully!');
                    }
                    formChanged = false;
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(response.message || 'Failed to save permissions');
                    }
                }
            },
            error: function(xhr) {
                var errorMsg = 'Failed to save permissions';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                if (typeof toastr !== 'undefined') {
                    toastr.error(errorMsg);
                }
            },
            complete: function() {
                isSubmitting = false;
                $('#loadingOverlay').fadeOut(200);
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Save Permissions');
            }
        });
    }

    // Unsaved changes warning
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            return e.returnValue;
        }
    });
});

// JavaScript variables for Swal
var roleName = '{{ $role->name }}';
var roleBadgeColor = '{{ $role->name == "super_admin" ? "danger" : ($role->name == "admin" ? "warning" : "primary") }}';
</script>
@endpush
