<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th width="50">#</th>
                <th>Name</th>
                <th>Guard Name</th>
                <th>Users</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $index => $role)
            <tr>
                <td class="text-center">{{ $roles->firstItem() + $index }}</td>
                <td><code>{{ $role->name }}</code></td>
                <td>{{ $role->guard_name }}</td>
                <td class="text-center">{{ $role->users()->count() }}</td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-info permissions-role" data-id="{{ $role->id }}" title="Permissions">
                            <i class="fas fa-key"></i>
                        </button>
                        <button class="btn btn-warning edit-role"
                                data-id="{{ $role->id }}"
                                data-name="{{ $role->name }}"
                                data-guard_name="{{ $role->guard_name }}"
                                title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        @if($role->name != 'super_admin')
                        <button class="btn btn-danger delete-role" data-id="{{ $role->id }}" data-name="{{ $role->name }}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-4">
                    <i class="fas fa-tags fa-2x mb-2 d-block text-muted"></i>
                    <p>No roles found.</p>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                        <i class="fas fa-plus"></i> Create First Role
                    </button>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
