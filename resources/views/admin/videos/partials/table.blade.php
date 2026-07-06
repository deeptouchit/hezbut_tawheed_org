{{-- resources/views/admin/videos/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr>
                <th width="60">#</th>
                <th width="120">থাম্বনেইল</th>
                <th>ভিডিও শিরোনাম ও ইউটিউব লিঙ্ক</th>
                <th>অর্ডার</th>
                <th>স্ট্যাটাস</th>
                <th>তৈরির তারিখ</th>
                <th width="150">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($videos as $index => $video)
            <tr class="align-middle">
                <td>
                    @if($videos instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                        {{ $videos->firstItem() + $index }}
                    @else
                        {{ $index + 1 }}
                    @endif
                </td>
                <td>
                    <div class="position-relative overflow-hidden rounded shadow-sm bg-light" style="width: 100px; height: 60px;">
                        <img src="{{ $video->thumbnail_url }}" 
                             alt="{{ $video->title }}" 
                             class="w-100 h-100"
                             style="object-fit: cover;"
                             loading="lazy">
                        <!-- Tiny play icon -->
                        <div class="position-absolute top-50 start-50 translate-middle bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; opacity: 0.85;">
                            <i class="fas fa-play" style="font-size: 9px; margin-left: 2px;"></i>
                        </div>
                    </div>
                </td>
                <td>
                    <strong class="text-dark">{{ $video->title }}</strong>
                    <div class="small text-muted mt-1">
                        <a href="https://www.youtube.com/watch?v={{ $video->youtube_id }}" target="_blank" class="text-decoration-none">
                            <i class="fab fa-youtube text-danger me-1"></i> ID: <span class="fw-bold">{{ $video->youtube_id }}</span>
                        </a>
                    </div>
                </td>
                <td>
                    <span class="fw-bold">{{ $video->sort_order }}</span>
                </td>
                <td>
                    <button class="btn btn-sm toggle-status {{ $video->is_active ? 'btn-success' : 'btn-danger' }}" data-id="{{ $video->id }}" title="স্ট্যাটাস পরিবর্তন করুন">
                        {{ $video->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    </button>
                </td>
                <td>{{ $video->created_at->format('d M, Y (h:i A)') }}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.videos.edit', $video->id) }}" class="btn btn-primary" title="এডিট করুন">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-danger delete-video" 
                                data-id="{{ $video->id }}" 
                                data-name="{{ $video->title }}"
                                title="ডিলিট করুন">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-5 text-muted">
                    <i class="fab fa-youtube fa-3x mb-3 text-secondary"></i>
                    <p class="mb-0">কোনো ভিডিও পাওয়া যায়নি।</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Link rendering -->
@if($videos instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $videos->hasPages())
    <div class="card-footer clearfix bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="text-muted small mb-2 mb-md-0">
                মোট {{ $videos->total() }} টি ভিডিওর মধ্যে {{ $videos->firstItem() }}-{{ $videos->lastItem() }} টি দেখানো হচ্ছে
            </div>
            <div>
                {{ $videos->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endif
