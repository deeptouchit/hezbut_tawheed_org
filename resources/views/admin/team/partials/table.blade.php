<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped" id="sortable-table">
        <thead class="table-light">
            <tr>
                <th width="30">
                    <input type="checkbox" id="selectAll">
                </th>
                <th width="30">
                    <i class="fas fa-grip-vertical drag-handle" title="ড্র্যাগ করে সাজান"></i>
                </th>
                <th width="50">#</th>
                <th width="70">ইমেজ</th>
                <th>নাম</th>
                <th>ডিজাইনেশন</th>
                <th>ডিপার্টমেন্ট</th>
                <th>অভিজ্ঞতা</th>
                <th>স্ট্যাটাস</th>
                <th width="150">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody id="sortable-body">
            @forelse($teamMembers as $index => $member)
            <tr class="table-row-hover" data-id="{{ $member->id }}">
                <td>
                    <input type="checkbox" class="team-checkbox" value="{{ $member->id }}">
                </td>
                <td class="text-center">
                    <i class="fas fa-grip-vertical drag-handle"></i>
                </td>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <img src="{{ $member->image_url }}" alt="{{ $member->name }}" class="team-member-image">
                </td>
                <td>
                    <strong>{{ $member->name }}</strong>
                    @if($member->email)
                        <br><small class="text-muted"><i class="fas fa-envelope"></i> {{ $member->email }}</small>
                    @endif
                    @if($member->phone)
                        <br><small class="text-muted"><i class="fas fa-phone"></i> {{ $member->phone }}</small>
                    @endif
                </td>
                <td>
                    {{ $member->designation ?? 'N/A' }}
                    @if($member->education)
                        <br><small class="text-muted"><i class="fas fa-graduation-cap"></i> {{ Str::limit($member->education, 30) }}</small>
                    @endif
                </td>
                <td>
                    @if($member->department)
                        <span class="badge bg-info">{{ $member->department }}</span>
                    @else
                        <span class="badge bg-secondary">N/A</span>
                    @endif
                </td>
                <td>
                    <span class="badge bg-warning">{{ $member->experience_label }}</span>
                    @if($member->skills)
                        <br><small class="text-muted" title="{{ $member->skills }}">
                            <i class="fas fa-tools"></i> {{ Str::limit($member->skills, 20) }}
                        </small>
                    @endif
                </td>
                <td>
                    <button class="btn btn-sm toggle-status status-badge" data-id="{{ $member->id }}">
                        {!! $member->status_badge !!}
                    </button>
                </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-info view-team-member" data-id="{{ $member->id }}" title="বিস্তারিত দেখুন">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="{{ route('admin.team.edit', $member->id) }}" class="btn btn-primary" title="এডিট করুন">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-danger delete-team-member" 
                                data-id="{{ $member->id }}" 
                                data-name="{{ $member->name }}"
                                title="ডিলিট করুন">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">কোন টিম মেম্বার পাওয়া যায়নি</p>
                    <a href="{{ route('admin.team.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> নতুন টিম মেম্বার যোগ করুন
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if(isset($teamMembers) && method_exists($teamMembers, 'links'))
    <div class="d-flex justify-content-center mt-3">
        {{ $teamMembers->appends(request()->query())->links() }}
    </div>
@endif