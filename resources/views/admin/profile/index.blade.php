@extends('admin.layouts.master')

@section('page-title', 'My Profile')

@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px;
        border-radius: 10px 10px 0 0;
        margin-bottom: 20px;
        position: relative;
    }
    .profile-avatar {
        border: 5px solid white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        transition: transform 0.3s;
    }
    .profile-avatar:hover {
        transform: scale(1.05);
    }
    .edit-avatar-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        background: #007bff;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        border: 2px solid white;
    }
    .info-card {
        border-left: 4px solid #007bff;
        margin-bottom: 15px;
        transition: all 0.3s;
    }
    .info-card:hover {
        transform: translateX(5px);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .stat-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        transition: all 0.3s;
    }
    .stat-card:hover {
        background: #e9ecef;
        transform: translateY(-3px);
    }
    .activity-timeline {
        max-height: 400px;
        overflow-y: auto;
    }
    .session-item {
        padding: 10px;
        background: #f8f9fa;
        border-radius: 6px;
        margin-bottom: 10px;
    }
    .current-session {
        background: #e8f5e9;
        border-left: 3px solid #4caf50;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <?php
        $user = auth()->user();

        // Get role display name
        $roleDisplayName = $user->role ?? 'user';
        $roleDisplayName = ucfirst($roleDisplayName);

        // Get role badge color
        $roleBadgeColor = 'info';
        if ($roleDisplayName == 'Super_admin' || $roleDisplayName == 'super_admin') {
            $roleBadgeColor = 'danger';
        } elseif ($roleDisplayName == 'Admin' || $roleDisplayName == 'admin') {
            $roleBadgeColor = 'warning';
        }

        // Get status badge
        $statusBadge = $user->status == 'active' ? 'success' : 'danger';
        $statusText = $user->status == 'active' ? 'Active' : 'Inactive';
    ?>

    <!-- Profile Header -->
    <div class="row">
        <div class="col-12">
            <div class="profile-header text-white">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <div class="position-relative d-inline-block">
                            <img class="profile-avatar rounded-circle"
                                 id="profileImage"
                                 src="{{ $user->image ? asset($user->image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0d6efd&color=fff&size=120' }}"
                                 alt="User profile picture"
                                 style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%;">
                            <label for="avatarUpload" class="edit-avatar-btn" style="cursor: pointer;">
                                <i class="fas fa-camera fa-sm"></i>
                            </label>
                            <input type="file" id="avatarUpload" accept="image/*" style="display: none;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h2 class="mb-1">{{ $user->name }}</h2>
                        <p class="mb-1"><i class="fas fa-envelope me-2"></i> {{ $user->email }}</p>
                        <p class="mb-0"><i class="fas fa-phone me-2"></i> {{ $user->phone ?? 'No phone added' }}</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <span class="badge bg-{{ $roleBadgeColor }} px-3 py-2 fs-6 me-1">
                            <i class="fas fa-shield-alt me-1"></i> {{ $roleDisplayName }}
                        </span>
                        <span class="badge bg-{{ $statusBadge }} px-3 py-2 fs-6">
                            <i class="fas fa-circle me-1"></i> {{ $statusText }}
                        </span>
                        <div class="mt-2">
                            <small><i class="fas fa-calendar-alt me-1"></i> Member since: {{ $user->created_at ? $user->created_at->format('F d, Y') : 'N/A' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Stats & Info -->
        <div class="col-md-4">
            <!-- Statistics Cards -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="stat-card">
                                <i class="fas fa-history fa-2x text-info mb-2"></i>
                                <h4 class="mb-0" id="totalActivities">0</h4>
                                <small class="text-muted">Total Activities</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="stat-card">
                                <i class="fas fa-calendar-day fa-2x text-success mb-2"></i>
                                <h4 class="mb-0" id="todayActivities">0</h4>
                                <small class="text-muted">Today</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card">
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <h4 class="mb-0" id="lastWeekActivities">0</h4>
                                <small class="text-muted">Last 7 Days</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card">
                                <i class="fas fa-database fa-2x text-danger mb-2"></i>
                                <h4 class="mb-0" id="totalLogins">0</h4>
                                <small class="text-muted">Total Logins</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Information Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-circle me-2"></i>Profile Information
                    </h5>
                    <div class="card-tools">
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body p-1">
                    <table class="table table-bordered table-striped table-hover table-sm text-nowrap">
                        <tr>
                            <th width="40%"><i class="fas fa-user me-2"></i>Full Name</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-envelope me-2"></i>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-phone me-2"></i>Phone</th>
                            <td>{{ $user->phone ?? 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-map-marker-alt me-2"></i>Address</th>
                            <td>{{ $user->address ?? 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-calendar me-2"></i>Joined</th>
                            <td>{{ $user->created_at ? $user->created_at->format('F d, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-clock me-2"></i>Last Login</th>
                            <td>{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('M d, Y H:i:s') : 'Never' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-globe me-2"></i>Last Login IP</th>
                            <td>{{ $user->last_login_ip ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.password.change') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-key"></i> Change Password
                    </a>
                </div>
            </div>

            <!-- Current Sessions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-laptop me-2"></i>Active Sessions
                    </h5>
                    <div class="card-tools">
                        <button type="button" class="btn btn-danger btn-sm" id="logoutAllDevicesBtn">
                            <i class="fas fa-sign-out-alt"></i> Logout All
                        </button>
                    </div>
                </div>
                <div class="card-body p-1">
                    <div id="sessionsContainer">
                        <div class="text-center py-3">
                            <i class="fas fa-spinner fa-spin"></i> Loading sessions...
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Recent Activities -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Recent Activities
                    </h5>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-secondary" id="refreshActivities">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="activity-timeline" id="activitiesContainer">
                        <div class="text-center py-5">
                            <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                            <p class="mt-2">Loading activities...</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.activity-logs.index') }}?user_id={{ $user->id }}" class="btn btn-link">
                        View All Activities <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Account Security Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Security Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-card p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-key fa-2x text-warning me-3"></i>
                                        <strong>Password</strong>
                                        <p class="mb-0 text-muted small">Last changed: {{ $user->password_changed_at ? \Carbon\Carbon::parse($user->password_changed_at)->diffForHumans() : 'Never' }}</p>
                                    </div>
                                    <a href="{{ route('admin.password.change') }}" class="btn btn-sm btn-outline-warning">
                                        Change
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-bell fa-2x text-info me-3"></i>
                                        <strong>Email Notifications</strong>
                                        <p class="mb-0 text-muted small">Activity alerts to your email</p>
                                    </div>
                                    <label class="switch">
                                        <input type="checkbox" id="emailNotifications" {{ $user->email_notifications ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="info-card p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-mobile-alt fa-2x text-success me-3"></i>
                                        <strong>2FA Security</strong>
                                        <p class="mb-0 text-muted small">Two factor authentication</p>
                                    </div>
                                    <button class="btn btn-sm btn-outline-success" id="enable2faBtn">
                                        Enable
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-download fa-2x text-secondary me-3"></i>
                                        <strong>Download Data</strong>
                                        <p class="mb-0 text-muted small">Export your profile data</p>
                                    </div>
                                    <button class="btn btn-sm btn-outline-secondary" id="downloadDataBtn">
                                        Export
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Avatar Crop Modal -->
<div class="modal fade" id="cropModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Crop Avatar</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="cropImage" style="max-width: 100%;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveAvatarBtn">Save Avatar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<script>
$(document).ready(function() {
    let cropper = null;
    let selectedFile = null;

    function loadStats() {
        $.ajax({
            url: '{{ route("admin.profile.stats") }}',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#totalActivities').text(response.data.total_activities || 0);
                    $('#todayActivities').text(response.data.today_activities || 0);
                    $('#lastWeekActivities').text(response.data.last_week_activities || 0);
                    $('#totalLogins').text(response.data.total_logins || 0);
                }
            }
        });
    }

    function loadActivities() {
        $('#activitiesContainer').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-muted"></i><p class="mt-2">Loading activities...</p></div>');

        $.ajax({
            url: '{{ route("admin.profile.activities") }}',
            type: 'GET',
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let html = '<div class="list-group list-group-flush">';
                    $.each(response.data, function(index, activity) {
                        let icon = getActivityIcon(activity.action);
                        let badgeClass = getBadgeClass(activity.action);
                        html += `
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="${icon} me-2"></i>
                                        <span class="badge ${badgeClass} me-2">${activity.action}</span>
                                        <span class="small">${activity.description}</span>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">
                                            <i class="fas fa-globe"></i> ${activity.ip_address || 'N/A'}
                                        </small>
                                        <br>
                                        <small class="text-muted">${activity.time_ago}</small>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    $('#activitiesContainer').html(html);
                } else {
                    $('#activitiesContainer').html('<div class="text-center py-5"><i class="fas fa-history fa-3x text-muted mb-3"></i><p>No activities found</p></div>');
                }
            },
            error: function() {
                $('#activitiesContainer').html('<div class="text-center py-5"><i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i><p>Failed to load activities</p></div>');
            }
        });
    }

    function loadSessions() {
        $.ajax({
            url: '{{ route("admin.profile.sessions") }}',
            type: 'GET',
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let html = '<div class="list-group list-group-flush">';
                    $.each(response.data, function(index, session) {
                        let currentClass = session.is_current ? 'current-session' : '';
                        html += `
                            <div class="session-item ${currentClass}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-${session.device_type} me-2"></i>
                                        <strong>${session.browser || 'Unknown Browser'}</strong>
                                        <br>
                                        <small class="text-muted">${session.platform || 'Unknown OS'}</small>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">${session.ip_address}</small>
                                        <br>
                                        <small>${session.last_activity_ago}</small>
                                        ${!session.is_current ? `<button class="btn btn-sm btn-danger mt-1 logout-session" data-session="${session.id}">Revoke</button>` : '<span class="badge bg-success mt-1">Current</span>'}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    $('#sessionsContainer').html(html);
                } else {
                    $('#sessionsContainer').html('<div class="text-center py-3"><p class="text-muted mb-0">No active sessions</p></div>');
                }
            },
            error: function() {
                $('#sessionsContainer').html('<div class="text-center py-3"><p class="text-danger mb-0">Failed to load sessions</p></div>');
            }
        });
    }

    function getActivityIcon(action) {
        const icons = {
            'create': 'fas fa-plus-circle text-success',
            'update': 'fas fa-edit text-info',
            'delete': 'fas fa-trash-alt text-danger',
            'login': 'fas fa-sign-in-alt text-primary',
            'logout': 'fas fa-sign-out-alt text-secondary',
            'change_password': 'fas fa-key text-warning'
        };
        return icons[action] || 'fas fa-bell text-secondary';
    }

    function getBadgeClass(action) {
        const classes = {
            'create': 'bg-success',
            'update': 'bg-info',
            'delete': 'bg-danger',
            'login': 'bg-primary',
            'logout': 'bg-secondary',
            'change_password': 'bg-warning'
        };
        return classes[action] || 'bg-secondary';
    }

    $('#avatarUpload').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                $('#cropImage').attr('src', event.target.result);
                $('#cropModal').modal('show');

                if (cropper) {
                    cropper.destroy();
                }
                cropper = new Cropper(document.getElementById('cropImage'), {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.8,
                    cropBoxMovable: true,
                    cropBoxResizable: true
                });
                selectedFile = file;
            };
            reader.readAsDataURL(file);
        }
    });

    $('#saveAvatarBtn').on('click', function() {
        if (cropper && selectedFile) {
            const canvas = cropper.getCroppedCanvas({
                width: 300,
                height: 300
            });

            canvas.toBlob(function(blob) {
                const formData = new FormData();
                formData.append('avatar', blob, 'avatar.jpg');
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: '{{ route("admin.profile.avatar") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Avatar updated successfully');
                            $('#profileImage').attr('src', response.avatar_url + '?t=' + Date.now());
                            $('#cropModal').modal('hide');
                        } else {
                            toastr.error(response.message || 'Failed to update avatar');
                        }
                    },
                    error: function() {
                        toastr.error('Failed to update avatar');
                    }
                });
            }, 'image/jpeg', 0.9);
        }
    });

    $('#logoutAllDevicesBtn').on('click', function() {
        Swal.fire({
            title: 'Logout All Devices?',
            text: 'You will be logged out from all other devices including this one.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, logout all'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.profile.logout-all") }}',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Logged out from all devices');
                            setTimeout(() => window.location.reload(), 1500);
                        }
                    }
                });
            }
        });
    });

    $(document).on('click', '.logout-session', function() {
        const sessionId = $(this).data('session');
        Swal.fire({
            title: 'Revoke Session?',
            text: 'This device will be logged out immediately.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, revoke'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.profile.logout-session") }}',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}', session_id: sessionId },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Session revoked');
                            loadSessions();
                        }
                    }
                });
            }
        });
    });

    $('#emailNotifications').on('change', function() {
        const isEnabled = $(this).is(':checked');
        $.ajax({
            url: '{{ route("admin.profile.notifications") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', enabled: isEnabled },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                }
            }
        });
    });

    $('#downloadDataBtn').on('click', function() {
        window.location.href = '{{ route("admin.profile.download-data") }}';
    });

    $('#enable2faBtn').on('click', function() {
        Swal.fire({
            title: 'Enable 2FA?',
            text: 'This will add an extra layer of security to your account.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Enable',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                toastr.info('2FA feature coming soon');
            }
        });
    });

    $('#refreshActivities').on('click', function() {
        loadActivities();
        loadStats();
    });

    loadStats();
    loadActivities();
    loadSessions();
});
</script>
@endpush
