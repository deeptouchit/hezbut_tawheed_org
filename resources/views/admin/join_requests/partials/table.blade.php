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
            <tr class="table-row-hover text-center {{ $req->status == 'unread' ? 'unread-row' : '' }}" data-id="{{ $req->id }}">
                <td>
                    <input type="checkbox" class="request-checkbox" value="{{ $req->id }}">
                </td>
                <td>{{ $loop->iteration }}</td>
                <td class="text-start">
                    <strong>{{ $req->name }}</strong>
                    <!-- @if($req->father_husband)
                        <br><small class="text-muted">পিতা: {{ $req->father_husband }}</small>
                    @endif -->
                </td>
                <td>
                    <a href="tel:{{ $req->phone }}" class="text-decoration-none">
                        <i class="fas fa-phone"></i> {{ $req->phone }}
                    </a>
                </td>
                <td>
                    @if($req->membership_type === 'primary')
                    <span class="badge bg-success" style="padding: 5px 10px; border-radius: 4px;">প্রাথমিক সদস্য</span>
                    @else
                    <span class="badge bg-warning text-dark" style="padding: 5px 10px; border-radius: 4px;">পাঁচ দফা অঙ্গীকার</span>
                    @endif
                </td>
                <td>{{ $req->age ?? 'N/A' }}</td>
                <td>{{ $req->occupation ?? 'N/A' }}</td>
                <td>{{ $req->how_knew }}</td>
                <td class="p-0">
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
                <td>
                    <div class="comment-meta">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $req->join_date ?? $req->created_at?->format('Y-m-d') }}
                    </div>
                </td>
                <td class="p-0">
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
                    <p class="text-muted mb-0">কোন সদস্য পদের আবেদন পাওয়া যায়নি</p>
                    <button id="reset-filter-empty" class="btn btn-primary btn-sm mt-3">
                        <i class="fas fa-undo-alt"></i> ফিল্টার রিসেট করুন
                    </button>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if(isset($requests) && method_exists($requests, 'links'))
<div class="row mt-3">
    <div class="col-12 d-flex justify-content-center">
        {{ $requests->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endif