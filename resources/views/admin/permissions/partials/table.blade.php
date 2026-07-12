<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
        <thead>
            <tr class="text-center">
                <th>#</th>
                <th>Name</th>
                <th>Group</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($permissions as $index => $permission)
            <tr>
                <td class="text-center">{{ $permissions->firstItem() + $index }}</td>
                <td>
                    <code>{{ $permission->name }}</code>
                </td>

                <td class="text-center">
                    <span class="badge bg-secondary" style="width: 80px;">
                        {{ ucfirst($permission->group_name) }}
                    </span>
                </td>

                <td class="text-center p-0">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-warning edit-permission"
                                data-id="{{ $permission->id }}"
                                data-name="{{ $permission->name }}"
                                data-group="{{ $permission->group_name }}"
                                title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger delete-permission"
                                data-id="{{ $permission->id }}"
                                data-name="{{ $permission->name }}"
                                title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-4">
                    <i class="fas fa-key fa-2x mb-2 d-block text-muted"></i>
                    <p class="text-muted">No permissions found.</p>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                        <i class="fas fa-plus"></i> Create First Permission
                    </button>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($permissions->isNotEmpty())
<div class="row mt-3 px-2">
    <div class="col-md-12">
        <div class="float-right">
            {{ $permissions->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endif
