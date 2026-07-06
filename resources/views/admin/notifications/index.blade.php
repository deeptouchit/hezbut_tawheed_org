@extends('admin.layouts.master')

@section('page-title', 'Notifications')

@section('content')
<div class="container-fluid">
    <!-- Filter Row -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <a href="{{ route('admin.notifications.index') }}" class="btn btn-sm btn-outline-primary {{ !request('type') && !request('status') ? 'active' : '' }}">All</a>
                                <a href="{{ route('admin.notifications.index', ['status' => 'unread']) }}" class="btn btn-sm btn-outline-warning {{ request('status') == 'unread' ? 'active' : '' }}">Unread</a>
                                <a href="{{ route('admin.notifications.index', ['type' => 'order']) }}" class="btn btn-sm btn-outline-success {{ request('type') == 'order' ? 'active' : '' }}">Orders</a>
                                <a href="{{ route('admin.notifications.index', ['type' => 'stock']) }}" class="btn btn-sm btn-outline-danger {{ request('type') == 'stock' ? 'active' : '' }}">Stock</a>
                                <a href="{{ route('admin.notifications.index', ['type' => 'customer']) }}" class="btn btn-sm btn-outline-info {{ request('type') == 'customer' ? 'active' : '' }}">Customers</a>
                                <a href="{{ route('admin.notifications.index', ['type' => 'system']) }}" class="btn btn-sm btn-outline-secondary {{ request('type') == 'system' ? 'active' : '' }}">System</a>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-sm btn-danger" id="deleteAllReadBtn">
                                <i class="fas fa-trash"></i> Delete All Read
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Notifications</h3>
                    <div class="card-tools">
                        <span class="badge bg-primary" id="totalCount">{{ $counts['all'] }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Notification</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="notificationsTableBody">
                                @forelse($notifications as $notif)
                                <tr>
                                    <td class="text-center">
                                        <i class="fas fa-{{ $notif->icon }} text-{{ $notif->color }}"></i>
                                    </td>
                                    <td>
                                        <strong>{{ $notif->title }}</strong>
                                    </td>
                                    <td class="text-center p-0">
                                        {!! $notif->type_badge !!}
                                    </td>
                                    <td class="text-center p-0">
                                        {{ $notif->created_at->format('M d, Y h:i A') }}

                                        <small class="text-muted">{{ $notif->time_ago }}</small>
                                    </td>
                                    <td class="text-center p-0">
                                        {!! $notif->status_badge !!}
                                    </td>
                                    <td class="text-center p-0">
                                        <div class="btn-group btn-group-sm">
                                            @if(!$notif->is_read)
                                                <button class="btn btn-info mark-read" data-id="{{ $notif->id }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-danger delete-notif" data-id="{{ $notif->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No notifications found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $notifications->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Mark single notification as read
    $('.mark-read').on('click', function() {
        var id = $(this).data('id');
        var row = $(this).closest('tr');

        $.ajax({
            url: '{{ url("admin/notifications") }}/' + id + '/read',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    row.find('.status').html('<span class="badge bg-success">Read</span>');
                    row.find('.mark-read').remove();
                    toastr.success('Marked as read');
                    updateCounts();
                }
            }
        });
    });

    // Delete single notification
    $('.delete-notif').on('click', function() {
        var id = $(this).data('id');
        var row = $(this).closest('tr');

        Swal.fire({
            title             : 'Delete Notification?',
            text              : 'This action cannot be undone!',
            icon              : 'warning',
            showCancelButton  : true,
            confirmButtonColor: '#d33',
            confirmButtonText : 'Yes, delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin/notifications") }}/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            row.fadeOut(300, function() { $(this).remove(); });
                            toastr.success('Deleted');
                            updateCounts();
                            if ($('#notificationsTableBody tr').length <= 1) {
                                location.reload();
                            }
                        }
                    }
                });
            }
        });
    });

    // Delete all read notifications
    $('#deleteAllReadBtn').on('click', function() {
        Swal.fire({
            title             : 'Delete All Read Notifications?',
            text              : 'This will delete all read notifications permanently!',
            icon              : 'warning',
            showCancelButton  : true,
            confirmButtonColor: '#d33',
            confirmButtonText : 'Yes, delete all'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.notifications.delete-all-read") }}',
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            location.reload();
                        }
                    }
                });
            }
        });
    });

    // Update counts
    function updateCounts() {
        $.ajax({
            url: '{{ route("admin.notifications.unread-count") }}',
            type: 'GET',
            success: function(response) {
                $('#notificationCount').text(response.unread_count);
                $('#notificationCount').toggle(response.unread_count > 0);
                $('#totalCount').text($('#notificationsTableBody tr').length);
            }
        });
    }
});
</script>
@endpush
