{{-- resources/views/admin/join_requests/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr>
                <th width="30">
                    <input type="checkbox" id="selectAll">
                </th>
                <th width="50">#</th>
                <th>নাম</th>
                <th>মোবাইল নম্বর</th>
                <th>আবেদনের ধরন</th>
                <th>বয়স</th>
                <th>পেশা</th>
                <th>কীভাবে জেনেছেন</th>
                <th>স্ট্যাটাস</th>
                <th>আবেদনের তারিখ</th>
                <th width="150">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $index => $req)
            <tr class="table-row-hover {{ $req->status == 'unread' ? 'unread-row' : ($req->status == 'approved' ? 'replied-row' : '') }}"
                data-id="{{ $req->id }}">
                <td>
                    <input type="checkbox" class="request-checkbox" value="{{ $req->id }}">
                </td>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <strong>{{ $req->name }}</strong>
                    @if($req->father_husband)
                        <br><small class="text-muted">পিতা: {{ $req->father_husband }}</small>
                    @endif
                </td>
                <td>
                    <a href="tel:{{ $req->phone }}" class="text-decoration-none">
                        <i class="fas fa-phone"></i> {{ $req->phone }}
                    </a>
                </td>
                <td>
                    {!! $req->type_badge !!}
                </td>
                <td>{{ $req->age ?? 'N/A' }}</td>
                <td>{{ $req->occupation ?? 'N/A' }}</td>
                <td>{{ $req->how_knew }}</td>
                <td>
                    {!! $req->status_badge !!}
                </td>
                <td>
                    <div class="comment-meta">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $req->join_date ?? $req->created_at?->format('Y-m-d') }}
                    </div>
                    <div class="comment-meta">
                        <i class="fas fa-clock"></i>
                        {{ $req->time_ago }}
                    </div>
                </td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ route('admin.join-requests.show', $req->id) }}" class="btn btn-info" title="বিস্তারিত দেখুন">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button class="btn btn-danger delete-request"
                                data-id="{{ $req->id }}"
                                data-name="{{ $req->name }}"
                                title="ডিলিট করুন">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center py-5">
                    <i class="fas fa-user-plus fa-3x text-muted mb-3"></i>
                    <p class="text-muted">কোন সদস্য পদের আবেদন পাওয়া যায়নি</p>
                    <button id="reset-filter-empty" class="btn btn-primary btn-sm">
                        <i class="fas fa-undo-alt"></i> ফিল্টার রিসেট করুন
                    </button>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($requests instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
<div class="d-flex justify-content-between align-items-center p-3">
    <div class="text-muted small">
        Showing {{ $requests->firstItem() ?? 0 }} to {{ $requests->lastItem() ?? 0 }} of {{ $requests->total() }} entries
    </div>
    <div>
        {{ $requests->appends(request()->except('page'))->links() }}
    </div>
</div>
@endif
