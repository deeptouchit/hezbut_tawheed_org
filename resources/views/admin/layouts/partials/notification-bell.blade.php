<!--begin::Notifications Dropdown Menu-->
<li class="nav-item dropdown">
    <a class="nav-link" data-bs-toggle="dropdown" href="#" id="notificationBell">
        <i class="bi bi-bell-fill"></i>
         <span class="navbar-badge badge text-bg-danger" id="notificationCount" style="display: none;">0</span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end" style="width: 380px;">
        <div class="dropdown-header d-flex justify-content-between align-items-center">
            <span><strong>Notifications</strong></span>
            <a href="#" id="markAllReadBtn" class="text-sm">Mark all as read</a>
        </div>
        <div class="dropdown-divider"></div>
        <div id="notificationList" style="max-height: 400px; overflow-y: auto;">
            <div class="text-center py-3 text-muted">
                <i class="bi bi-bell-slash me-2"></i> No notifications
            </div>
        </div>
        <div class="dropdown-divider"></div>
        <a href="{{ route('admin.notifications.index') }}" class="dropdown-item dropdown-footer text-center">
            View All Notifications
        </a>
    </div>
</li>
<!--end::Notifications Dropdown Menu-->
