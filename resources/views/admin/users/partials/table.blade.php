<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
        <thead>
            <tr class="text-center">
                <th>
                    <input type="checkbox" id="selectAll">
                </th>
                <th>ID</th>
                <th>ছবি</th>
                <th>ইউজার</th>
                <th>ইমেইল</th>
                <th>রোল</th>
                <th>স্ট্যাটাস</th>
                <th>ভেরিফিকেশন</th>
                <th>শেষ লগইন</th>
                <th>একশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
            <tr class="text-center">
                <td>
                    <input type="checkbox" class="user-checkbox" value="{{ $user->id }}">
                </td>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <img src="{{ $user->image ? asset($user->image) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=2F54EB&color=fff' }}"
                         alt="{{ $user->name }}" style="width: 32px; height: 32px; object-fit: cover; border-radius: 4px;">
                </td>
                <td class="text-start">
                    <strong>{{ $user->name }}</strong>
                </td>
                <td class="text-start">{{ $user->email }}</td>
                <td>
                    @php
                        $role = $user->getRoleNames()->first() ?? 'user';
                        $badgeColor = 'primary';
                        if($role == 'admin') $badgeColor = 'warning';
                        elseif($role == 'super_admin') $badgeColor = 'danger';
                        elseif($role == 'manager') $badgeColor = 'info';
                    @endphp
                    <span class="badge bg-{{ $badgeColor }}" style="padding: 4px 8px; border-radius: 4px;">{{ ucfirst($role) }}</span>
                </td>
                <td>
                    @php
                        $status = $user->status ?? 'active';
                        $statusBadge = $status == 'active' ? 'success' : ($status == 'inactive' ? 'danger' : 'warning');
                    @endphp
                    <button type="button"
                            class="btn btn-sm btn-{{ $statusBadge }} toggle-status"
                            data-id="{{ $user->id }}"
                            data-status="{{ $status }}"
                            style="padding: 2px 8px; border-radius: 4px;">
                        {{ ucfirst($status) }}
                    </button>
                </td>
                <td>
                    @if($user->email_verified_at)
                        <span class="badge bg-success" style="padding: 4px 8px; border-radius: 4px;">
                            <i class="fas fa-check-circle"></i> ভেরিফাইড
                        </span>
                    @else
                        <span class="badge bg-danger" style="padding: 4px 8px; border-radius: 4px;">
                            <i class="fas fa-times-circle"></i> আনভেরিফাইড
                        </span>
                    @endif
                </td>
                <td>
                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'কখনো নাই' }}
                </td>
                <td class="p-0">
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info" title="দেখুন">
                            <i class="fas fa-eye text-white"></i>
                        </a>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary" title="এডিট">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-danger delete-user"
                                data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}"
                                title="ডিলিট">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center py-5">
                    <i class="fas fa-users-slash fa-3x text-muted mb-3 d-block"></i>
                    <p class="text-muted mb-0">কোনো ইউজার পাওয়া যায়নি</p>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm mt-3">
                        <i class="fas fa-plus mr-1"></i> নতুন ইউজার যোগ করুন
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if(isset($users) && method_exists($users, 'links'))
<div class="row mt-3">
    <div class="col-12 d-flex justify-content-center">
        {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endif
