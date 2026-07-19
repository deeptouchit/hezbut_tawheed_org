<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
        <thead>
            <tr class="text-center">
                <th width="50">ID</th>
                <th>Name</th>
                <th>Guard Name</th>
                <th>Users</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $role)
            <tr class="text-center">
                <td>{{ $loop->iteration }}</td>
                <td class="text-start"><code>{{ $role->name }}</code></td>
                <td>{{ $role->guard_name }}</td>
                <td><span class="badge bg-secondary" style="padding: 4px 8px; border-radius: 4px;">{{ $role->users()->count() }}</span></td>
                <td class="p-0">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-info permissions-role" data-id="{{ $role->id }}" title="Permissions">
                            <i class="fas fa-key text-white"></i>
                        </button>
                        <button class="btn btn-primary edit-role"
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

<!-- Pagination -->
@if(isset($roles) && method_exists($roles, 'links'))
<div class="row mt-3">
    <div class="col-12 d-flex justify-content-center">
        {{ $roles->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endif
