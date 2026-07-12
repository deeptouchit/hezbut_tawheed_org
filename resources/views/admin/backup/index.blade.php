@extends('admin.layouts.master')

@section('page-title', 'Backup Management')

@section('page_style')
<style>
    .backup-card {
        transition: transform 0.2s;
    }
    .backup-card:hover {
        transform: translateY(-5px);
    }
    .disk-usage-bar {
        height: 8px;
        border-radius: 4px;
        background-color: #e9ecef;
        overflow: hidden;
    }
    .disk-usage-fill {
        height: 100%;
        border-radius: 4px;
        background-color: #28a745;
        transition: width 0.3s ease;
    }
    .disk-usage-fill.warning {
        background-color: #ffc107;
    }
    .disk-usage-fill.danger {
        background-color: #dc3545;
    }
    .backup-table tr {
        transition: all 0.2s;
    }
    .backup-table tr:hover {
        background-color: #f5f5f5;
    }
    .badge-backup {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge-database { background: #17a2b8; color: white; }
    .badge-files { background: #28a745; color: white; }
    .badge-full { background: #6f42c1; color: white; }
    .badge-unknown { background: #6c757d; color: white; }
</style>
@endsection

@section('filter_input')
<div class="row px-3">
    <div class="col-md-12 mb-3">
        <div class="btn-group w-100">
            <button type="button" class="btn btn-primary" id="createBackupBtn">
                <i class="fas fa-plus"></i> Create Backup
            </button>
            <button type="button" class="btn btn-info" id="refreshBackupsBtn">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <button type="button" class="btn btn-secondary" id="uploadBackupBtn">
                <i class="fas fa-upload"></i> Upload Backup
            </button>
            <button type="button" class="btn btn-danger" id="cleanupBackupsBtn">
                <i class="fas fa-trash-alt"></i> Clean Old Backups
            </button>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Alert Messages -->
    @if(session('message'))
        <div class="alert alert-{{ session('alert-type', 'info') }} alert-dismissible fade show shadow-sm mb-3" role="alert">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    @if(session('alert-type') == 'success')
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    @elseif(session('alert-type') == 'error')
                        <i class="fas fa-exclamation-circle fa-2x text-danger"></i>
                    @else
                        <i class="fas fa-info-circle fa-2x text-info"></i>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <strong>{{ session('message') }}</strong>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

   <!-- Statistics Cards - Alternative Style -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-archive"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Backups</span>
                <span class="info-box-number">{{ $totalBackups }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-hdd"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Backup Size</span>
                <span class="info-box-number">{{ $totalSize }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-chart-line"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Free Space</span>
                <span class="info-box-number">{{ $diskSpace['free'] }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fas fa-chart-pie"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Used Space</span>
                <span class="info-box-number">{{ $diskSpace['used'] }}</span>
            </div>
        </div>
    </div>
</div>
   <!-- Disk Usage Card -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Storage Overview
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="chart">
                            <div class="progress-group">
                                <span class="progress-text">Used Space</span>
                                <span class="progress-number"><b>{{ $diskSpace['used'] }}</b>/{{ $diskSpace['total'] }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-{{ $diskSpace['percentage_used'] > 80 ? 'danger' : ($diskSpace['percentage_used'] > 60 ? 'warning' : 'success') }}"
                                         style="width: {{ $diskSpace['percentage_used'] }}%">
                                    </div>
                                </div>
                            </div>
                            <div class="progress-group mt-3">
                                <span class="progress-text">Free Space</span>
                                <span class="progress-number"><b>{{ $diskSpace['free'] }}</b>/{{ $diskSpace['total'] }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-info" style="width: {{ $diskSpace['percentage_free'] }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center pt-3">
                            <p class="mb-0">
                                <i class="fas fa-database text-primary"></i>
                                <strong>{{ $totalBackups }}</strong> Backups
                            </p>
                            <p class="mb-0 mt-2">
                                <i class="fas fa-hdd text-success"></i>
                                Total: <strong>{{ $diskSpace['total'] }}</strong>
                            </p>
                            <button type="button" class="btn btn-sm btn-primary mt-3" id="cleanupBackupsBtn">
                                <i class="fas fa-trash-alt"></i> Clean Old Backups
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Backup List Card -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-archive mr-2"></i> Backup Files
                    </h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" id="backupSearch" class="form-control" placeholder="Search backups...">
                            <div class="input-group-append">
                                <button class="btn btn-default" id="clearSearchBtn">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-1">
                    <div id="loadingSpinner" class="text-center py-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading backups...</p>
                    </div>
                    <div id="backupsList">
                        @include('admin.backup.partials.table', ['backups' => $backups])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Backup Modal -->
<div class="modal fade" id="createBackupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle mr-2"></i> Create New Backup
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createBackupForm">
                    @csrf
                    <div class="form-group mb-3">
                        <label>Backup Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-control" required>
                            <option value="">Select backup type</option>
                            <option value="database">Database Backup</option>
                            <option value="files">Files Backup</option>
                            <option value="full">Full Backup (Database + Files)</option>
                        </select>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Database: Backup only database<br>
                            Files: Backup only uploaded files<br>
                            Full: Backup both database and files
                        </small>
                    </div>
                    <div class="form-group mb-3">
                        <label>Description (Optional)</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Add a description for this backup..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> Full backup includes database and all files.
                        This may take a few minutes depending on your data size.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmCreateBackup">
                    <i class="fas fa-play"></i> Create Backup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Upload Backup Modal -->
<div class="modal fade" id="uploadBackupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">
                    <i class="fas fa-upload mr-2"></i> Upload Backup File
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadBackupForm" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label>Backup File <span class="text-danger">*</span></label>
                        <input type="file" name="backup_file" class="form-control" accept=".zip" required>
                        <small class="text-muted">Only ZIP files, max 200MB</small>
                    </div>
                    <div class="form-group mb-3">
                        <label>Description (Optional)</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Add a description for this backup..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info" id="confirmUploadBackup">
                    <i class="fas fa-upload"></i> Upload
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Restore Confirmation Modal -->
<div class="modal fade" id="restoreModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Restore Backup
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Are you sure you want to restore this backup?</strong></p>
                <p class="text-muted">This action will overwrite your current data. This cannot be undone!</p>
                <div id="restoreInfo"></div>
                <div class="form-check mt-3">
                    <input type="checkbox" class="form-check-input" id="confirmRestore">
                    <label class="form-check-label text-danger" for="confirmRestore">
                        I understand that this will overwrite my current data
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmRestoreBtn" disabled>
                    <i class="fas fa-undo-alt"></i> Restore Now
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentRestoreId = null;
    let currentRestoreType = null;

    // Auto-hide alerts after 5 seconds
    $('.alert-dismissible').each(function() {
        setTimeout(() => {
            $(this).fadeOut(500, function() { $(this).remove(); });
        }, 5000);
    });

    // ============================================
    // Create Backup
    // ============================================
    $('#createBackupBtn').on('click', function() {
        $('#createBackupForm')[0].reset();
        $('#createBackupModal').modal('show');
    });

    $('#confirmCreateBackup').on('click', function() {
        const formData = $('#createBackupForm').serialize();
        const btn = $(this);

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating...');

        $.ajax({
            url: '{{ route("admin.backup.create") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Backup Created!',
                        html: `<strong>${response.message}</strong><br>Size: ${response.data?.size_formatted || 'N/A'}`,
                        confirmButtonColor: '#28a745'
                    });
                    $('#createBackupModal').modal('hide');
                    loadBackups();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Backup Failed',
                        text: response.message,
                        confirmButtonColor: '#d33'
                    });
                }
            },
            error: function(xhr) {
                let message = 'Failed to create backup';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: message,
                    confirmButtonColor: '#d33'
                });
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-play"></i> Create Backup');
            }
        });
    });

    // ============================================
    // Upload Backup
    // ============================================
    $('#uploadBackupBtn').on('click', function() {
        $('#uploadBackupForm')[0].reset();
        $('#uploadBackupModal').modal('show');
    });

    $('#confirmUploadBackup').on('click', function() {
        const formData = new FormData($('#uploadBackupForm')[0]);
        const btn = $(this);

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Uploading...');

        $.ajax({
            url: '{{ route("admin.backup.upload") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Upload Complete!',
                        text: response.message,
                        confirmButtonColor: '#28a745'
                    });
                    $('#uploadBackupModal').modal('hide');
                    loadBackups();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Failed',
                        text: response.message,
                        confirmButtonColor: '#d33'
                    });
                }
            },
            error: function(xhr) {
                let message = 'Failed to upload backup';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: message,
                    confirmButtonColor: '#d33'
                });
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-upload"></i> Upload');
            }
        });
    });

    // ============================================
    // Cleanup Old Backups
    // ============================================
    $('#cleanupBackupsBtn').on('click', function() {
        Swal.fire({
            title: 'Clean Old Backups?',
            html: 'This will delete backups older than 30 days.<br><strong>This action cannot be undone!</strong>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, clean old backups',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.backup.cleanup") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        days: 30
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cleanup Complete!',
                                text: response.message,
                                confirmButtonColor: '#28a745'
                            });
                            loadBackups();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Cleanup Failed',
                                text: response.message,
                                confirmButtonColor: '#d33'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to cleanup old backups',
                            confirmButtonColor: '#d33'
                        });
                    }
                });
            }
        });
    });

    // ============================================
    // Load Backups Function
    // ============================================
    function loadBackups() {
        $('#loadingSpinner').show();
        $('#backupsList').hide();

        $.ajax({
            url: '{{ route("admin.backup.index") }}',
            method: 'GET',
            dataType: 'html',
            success: function(response) {
                const html = $(response);
                const newTable = html.find('#backupsList').html();
                $('#backupsList').html(newTable);
                attachBackupEvents();
            },
            error: function() {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Failed to load backups');
                } else {
                    alert('Failed to load backups');
                }
            },
            complete: function() {
                $('#loadingSpinner').hide();
                $('#backupsList').show();
            }
        });
    }

    // ============================================
    // Attach Events to Backup Buttons
    // ============================================
    function attachBackupEvents() {
        // Download backup
        $('.download-backup').off('click').on('click', function() {
            const backupId = $(this).data('id');
            window.location.href = '{{ url("admin/backup") }}/' + backupId + '/download';
        });

        // Delete backup
        $('.delete-backup').off('click').on('click', function() {
            const backupId = $(this).data('id');
            const row = $(this).closest('tr');

            Swal.fire({
                title: 'Delete Backup?',
                html: `Are you sure you want to delete <strong>${backupId}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("admin/backup") }}/' + backupId,
                        method: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    confirmButtonColor: '#28a745',
                                    timer: 2000
                                });
                                row.fadeOut(300, function() { $(this).remove(); });
                            } else {
                                if (typeof toastr !== 'undefined') {
                                    toastr.error(response.message);
                                } else {
                                    alert(response.message);
                                }
                            }
                        },
                        error: function() {
                            if (typeof toastr !== 'undefined') {
                                toastr.error('Failed to delete backup');
                            } else {
                                alert('Failed to delete backup');
                            }
                        }
                    });
                }
            });
        });

        // Verify backup
        $('.verify-backup').off('click').on('click', function() {
            const backupId = $(this).data('id');
            const btn = $(this);

            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: '{{ url("admin/backup") }}/' + backupId + '/verify',
                method: 'GET',
                success: function(response) {
                    if (response.success && response.valid) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Backup Valid',
                            text: 'Backup integrity check passed',
                            confirmButtonColor: '#28a745'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Backup Invalid',
                            text: response.message || 'Backup integrity check failed',
                            confirmButtonColor: '#d33'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Verification Failed',
                        text: 'Could not verify backup integrity',
                        confirmButtonColor: '#d33'
                    });
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="fas fa-check-circle"></i>');
                }
            });
        });

        // Restore backup
        $('.restore-backup').off('click').on('click', function() {
            currentRestoreId = $(this).data('id');
            currentRestoreType = $(this).data('type');

            $('#restoreInfo').html(`
                <div class="alert alert-warning">
                    <strong>Backup ID:</strong> ${currentRestoreId}<br>
                    <strong>Type:</strong> ${currentRestoreType}
                </div>
            `);
            $('#confirmRestore').prop('checked', false);
            $('#confirmRestoreBtn').prop('disabled', true);
            $('#restoreModal').modal('show');
        });
    }

    // Confirm restore checkbox
    $('#confirmRestore').on('change', function() {
        $('#confirmRestoreBtn').prop('disabled', !$(this).is(':checked'));
    });

    // Confirm restore button
    $('#confirmRestoreBtn').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Restoring...');

        $.ajax({
            url: '{{ url("admin/backup") }}/' + currentRestoreId + '/restore',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                type: currentRestoreType,
                confirm: true
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Restore Complete!',
                        text: response.message,
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        window.location.reload();
                    });
                    $('#restoreModal').modal('hide');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Restore Failed',
                        text: response.message,
                        confirmButtonColor: '#d33'
                    });
                }
            },
            error: function(xhr) {
                let message = 'Failed to restore backup';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: message,
                    confirmButtonColor: '#d33'
                });
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-undo-alt"></i> Restore Now');
            }
        });
    });

    // Refresh backups
    $('#refreshBackupsBtn').on('click', function() {
        loadBackups();
    });

    // Search backups
    $('#backupSearch').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('#backupsList tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(searchTerm) > -1);
        });
    });

    $('#clearSearchBtn').on('click', function() {
        $('#backupSearch').val('');
        $('#backupsList tbody tr').show();
    });

    // Initial attach events
    attachBackupEvents();
});
</script>
@endpush
