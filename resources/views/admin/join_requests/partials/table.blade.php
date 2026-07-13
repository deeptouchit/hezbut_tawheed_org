{{-- resources/views/admin/join_requests/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
        <thead>
            <tr class="text-center">
                <th>
                    <input type="checkbox" id="selectAll">
                </th>
                <th>#</th>
                <th>নাম</th>
                <th>মোবাইল নম্বর</th>
                <th>আবেদনের ধরন</th>
                <th>বয়স</th>
                <th>পেশা</th>
                <th>কীভাবে জেনেছেন</th>
                <th>স্ট্যাটাস</th>
                <th>আবেদনের তারিখ</th>
                <th>অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $index => $req)
            <tr class="table-row-hover {{ $req->status == 'unread' ? 'unread-row' : '' }}" data-id="{{ $req->id }}">
                <td class="text-center">
                    <input type="checkbox" class="request-checkbox" value="{{ $req->id }}">
                </td>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>
                    <strong>{{ $req->name }}</strong>
                    @if($req->father_husband)
                        <br><small class="text-muted">পিতা: {{ $req->father_husband }}</small>
                    @endif
                </td>
                <td class="text-center">
                    <a href="tel:{{ $req->phone }}" class="text-decoration-none">
                        <i class="fas fa-phone"></i> {{ $req->phone }}
                    </a>
                </td>
                <td class="text-center">
                    @if($req->membership_type === 'primary')
                        <span class="badge bg-success" style="padding: 5px 10px; border-radius: 4px;">প্রাথমিক সদস্য</span>
                    @else
                        <span class="badge bg-warning text-dark" style="padding: 5px 10px; border-radius: 4px;">পাঁচ দফা অঙ্গীকার</span>
                    @endif
                </td>
                <td class="text-center">{{ $req->age ?? 'N/A' }}</td>
                <td class="text-center">{{ $req->occupation ?? 'N/A' }}</td>
                <td class="text-center">{{ $req->how_knew }}</td>
                <td class="text-center p-0">
                    @if($req->status === 'unread')
                        <button type="button" class="btn btn-sm btn-danger status-badge" style="pointer-events: none; padding: 2px 8px;">
                            <i class="fas fa-circle"></i> অপঠিত
                        </button>
                    @elseif($req->status === 'read')
                        <button type="button" class="btn btn-sm btn-info status-badge text-white" style="pointer-events: none; padding: 2px 8px;">
                            <i class="fas fa-check-circle"></i> পঠিত
                        </button>
                    @elseif($req->status === 'approved')
                        <button type="button" class="btn btn-sm btn-success status-badge" style="pointer-events: none; padding: 2px 8px;">
                            <i class="fas fa-check"></i> অনুমোদিত
                        </button>
                    @else
                        <button type="button" class="btn btn-sm btn-secondary status-badge" style="pointer-events: none; padding: 2px 8px;">
                            <i class="fas fa-times"></i> প্রত্যাখ্যাত
                        </button>
                    @endif
                </td>
                <td class="text-center">
                    <div class="comment-meta">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $req->join_date ?? $req->created_at?->format('Y-m-d') }}
                    </div>
                </td>
                <td class="text-center p-0">
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ route('admin.join-requests.show', $req->id) }}" class="btn btn-success" title="বিস্তারিত দেখুন">
                            <i class="fas fa-eye text-white"></i>
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
