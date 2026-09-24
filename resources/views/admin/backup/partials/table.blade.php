<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover backup-table">
        <thead class="thead-dark">
            <tr>
                <th width="50">#</th>
                <th>Backup ID</th>
                <th>Type</th>
                <th>Size</th>
                <th>Created At</th>
                <th width="200">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($backups as $index => $backup)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>
                    <strong>{{ $backup['id'] }}</strong>
                    <br>
                    <small class="text-muted">{{ $backup['filename'] }}</small>
                 </td>
                <td class="text-center">
                    <span class="badge-backup badge-{{ $backup['type'] }}">
                        {{ ucfirst($backup['type']) }}
                    </span>
                 </td>
                <td>{{ $backup['size_formatted'] }} </td>
                <td>{{ $backup['created_at'] }} </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-info download-backup" data-id="{{ $backup['id'] }}" title="Download">
                            <i class="fas fa-download"></i>
                        </button>
                        <button class="btn btn-success verify-backup" data-id="{{ $backup['id'] }}" title="Verify">
                            <i class="fas fa-check-circle"></i>
                        </button>
                        @if($backup['type'] == 'database' || $backup['type'] == 'files')
                            <button class="btn btn-warning restore-backup" data-id="{{ $backup['id'] }}" data-type="{{ $backup['type'] }}" title="Restore">
                                <i class="fas fa-undo-alt"></i>
                            </button>
                        @endif
                        <button class="btn btn-danger delete-backup" data-id="{{ $backup['id'] }}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                 </td>
             </tr>
            @empty
             <tr>
                <td colspan="6" class="text-center py-5">
                    <i class="fas fa-database fa-3x text-muted mb-3"></i>
                    <h5>No backups found</h5>
                    <p class="text-muted">Click the "Create Backup" button to create your first backup.</p>
                 </td>
             </tr>
            @endforelse
        </tbody>
    </table>
</div>
