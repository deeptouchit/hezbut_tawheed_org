<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
        <thead>
            <tr class="text-center">
                <th>ID</th>
                <th>User</th>
                <th>Action</th>
                <th>Module</th>
                <th>Description</th>
                <th>IP Address</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr class="text-center">
                <td>{{ $loop->iteration }}</td>
                <td class="text-start">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-2" style="width: 32px; height: 32px; background: #0d6efd; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                            {{ substr($log->user?->name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <strong>{{ $log->user?->name ?? 'System' }}</strong>
                        </div>
                    </div>
                </td>
                <td>{!! $log->action_badge !!}</td>
                <td>{!! $log->module_badge !!}</td>
                <td class="text-start">{{ Str::limit($log->description, 80) }}</td>
                <td><code>{{ $log->ip_address ?? '-' }}</code></td>
                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                <td class="p-0">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-info view-log" data-id="{{ $log->id }}" title="View Details">
                            <i class="fas fa-eye text-white"></i>
                        </button>
                        <button class="btn btn-danger delete-log" data-id="{{ $log->id }}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-5">
                    <i class="fas fa-history fa-3x text-muted mb-3 d-block"></i>
                    <p class="text-muted mb-0">No activity logs found</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if(isset($logs) && method_exists($logs, 'links') && $logs->isNotEmpty())
<div class="row mt-3">
    <div class="col-12 d-flex justify-content-center">
        {{ $logs->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endif
