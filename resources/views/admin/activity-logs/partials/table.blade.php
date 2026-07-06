<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm nowrap log-table"   style="width: 100%;">
        <thead>
            <tr class="text-center">
                <th>#</th>
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
            @forelse($logs as $index => $log)
            <tr>
                <td class="text-center">{{ $logs->firstItem() + $index }}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-2" style="width: 32px; height: 32px; background: #0d6efd; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                            {{ substr($log->user?->name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <strong>{{ $log->user?->name ?? 'System' }}</strong>


                        </div>
                    </div>
                </td>
                <td>{!! $log->action_badge !!}</td>
                <td>{!! $log->module_badge !!}</td>
                <td>{{ Str::limit($log->description, 80) }}</td>
                <td><code>{{ $log->ip_address ?? '-' }}</code></td>
                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                <td class="text-center p-0">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-info view-log" data-id="{{ $log->id }}" title="View Details">
                            <i class="fas fa-eye"></i>
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
                    <h5>No activity logs found</h5>
                    <p class="text-muted">Activities will appear here when users perform actions.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($logs->isNotEmpty())
<div class="row  px-3">
    <div class="col-md-12">
        <div class="float-right">
            {{ $logs->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endif
