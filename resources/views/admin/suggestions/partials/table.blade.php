{{-- resources/views/admin/suggestions/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped mb-0">
        <thead class="table-light">
            <tr>
                <th width="30">
                    <input type="checkbox" id="selectAll">
                </th>
                <th width="50">#</th>
                <th>নাম</th>
                <th>যোগাযোগ (ইমেইল/মোবাইল)</th>
                <th>বিষয়</th>
                <th>পরামর্শ বার্তা</th>
                <th>স্ট্যাটাস</th>
                <th>তারিখ</th>
                <th width="150">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suggestions as $index => $suggestion)
            <tr class="table-row-hover {{ $suggestion->status == 'pending' ? 'pending-row' : '' }}" data-id="{{ $suggestion->id }}">
                <td>
                    <input type="checkbox" class="suggestion-checkbox" value="{{ $suggestion->id }}">
                </td>
                <td>{{ $loop->iteration + ($suggestions->firstItem() - 1) }}</td>
                <td><strong>{{ $suggestion->name }}</strong></td>
                <td>{{ $suggestion->contact }}</td>
                <td>{{ $suggestion->subject ?? 'N/A' }}</td>
                <td>
                    <div class="message-preview" title="{{ $suggestion->message }}">
                        {{ Str::limit($suggestion->message, 60) }}
                    </div>
                </td>
                <td>
                    @if($suggestion->status == 'pending')
                        <span class="badge bg-warning text-dark suggestion-status-badge toggle-status-btn" data-id="{{ $suggestion->id }}">
                            <i class="fas fa-clock"></i> পেন্ডিং
                        </span>
                    @else
                        <span class="badge bg-success suggestion-status-badge toggle-status-btn" data-id="{{ $suggestion->id }}">
                            <i class="fas fa-check-circle"></i> রিভিউড
                        </span>
                    @endif
                </td>
                <td>
                    <div><i class="far fa-calendar-alt"></i> {{ $suggestion->created_at->format('d M, Y') }}</div>
                    <small class="text-muted"><i class="far fa-clock"></i> {{ $suggestion->created_at->diffForHumans() }}</small>
                </td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ route('admin.suggestions.show', $suggestion->id) }}" class="btn btn-info text-white" title="বিস্তারিত দেখুন">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button class="btn btn-danger delete-suggestion" 
                                data-id="{{ $suggestion->id }}" 
                                data-name="{{ $suggestion->name }}" 
                                title="মুছে ফেলুন">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-5">
                    <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted">কোন পরামর্শ বা মতামত পাওয়া যায়নি</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Links -->
@if(isset($suggestions) && method_exists($suggestions, 'links'))
    <div class="d-flex justify-content-center p-3">
        {{ $suggestions->appends(request()->query())->links() }}
    </div>
@endif
