{{-- resources/views/admin/activities/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr>
                <th width="60">#</th>
                <th width="100">ছবি</th>
                <th>শিরোনাম</th>
                <th>সংক্ষিপ্ত বিবরণী</th>
                <th>স্ট্যাটাস</th>
                <th>তৈরির তারিখ</th>
                <th width="150">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activities as $index => $activity)
            <tr class="align-middle">
                <td>{{ $activities->firstItem() + $index }}</td>
                <td>
                    <img src="{{ $activity->image_url }}" 
                         alt="{{ $activity->title }}" 
                         class="img-thumbnail"
                         style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px;"
                         loading="lazy">
                </td>
                <td>
                    <strong>{{ $activity->title }}</strong>
                    <br>
                    <a href="/activities/{{ $activity->slug }}" target="_blank" class="small text-muted text-decoration-none">
                        <i class="fas fa-external-link-alt small"></i> /activities/{{ $activity->slug }}
                    </a>
                </td>
                <td>
                    <div style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $activity->description }}">
                        {{ $activity->description ?? 'N/A' }}
                    </div>
                </td>
                <td>
                    <button class="btn btn-sm toggle-status {{ $activity->is_active ? 'btn-success' : 'btn-danger' }}" data-id="{{ $activity->id }}" title="স্ট্যাটাস পরিবর্তন করুন">
                        {{ $activity->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    </button>
                </td>
                <td>{{ $activity->created_at->format('d M, Y (h:i A)') }}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.activities.edit', $activity->id) }}" class="btn btn-primary" title="এডিট করুন">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-danger delete-activity" 
                                data-id="{{ $activity->id }}" 
                                data-name="{{ $activity->title }}"
                                title="ডিলিট করুন">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-5 text-muted">
                    <i class="fas fa-list-check fa-3x mb-3 text-secondary"></i>
                    <p class="mb-0">কোন কার্যক্রম পাওয়া যায়নি।</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Link rendering -->
@if($activities instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $activities->hasPages())
    <div class="card-footer clearfix bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="text-muted small mb-2 mb-md-0">
                মোট {{ $activities->total() }} টি কার্যক্রমের মধ্যে {{ $activities->firstItem() }}-{{ $activities->lastItem() }} টি দেখানো হচ্ছে
            </div>
            <div>
                {{ $activities->appends(request()->all())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endif
