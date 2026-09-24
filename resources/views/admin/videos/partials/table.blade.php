
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
        <thead>
            <tr class="text-center">
                <th>#</th>
                <th>থাম্বনেইল</th>
                <th class="text-start">ভিডিও শিরোনাম ও ইউটিউব লিঙ্ক</th>
                <th>অর্ডার</th>
                <th>স্ট্যাটাস</th>
                <th>তৈরির তারিখ</th>
                <th>অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($videos as $index => $video)
            <tr class="align-middle text-center">
                <td>{{ $videos instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ? $videos->firstItem() + $index : $index + 1 }}</td>
                <td class="p-1">
                    <div class="d-flex align-items-center justify-content-center" style="height: 45px;">
                        <div class="position-relative overflow-hidden rounded bg-light" style="width: 75px; height: 42px;">
                            <img src="{{ $video->thumbnail_url }}" 
                                 alt="{{ $video->title }}" 
                                 class="w-100 h-100"
                                 style="object-fit: cover;"
                                 loading="lazy">
                            <!-- Tiny play icon -->
                            <div class="position-absolute top-50 start-50 translate-middle bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 18px; height: 18px; opacity: 0.85;">
                                <i class="fas fa-play" style="font-size: 7px; margin-left: 1px;"></i>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="text-start fw-bold">
                    <span class="text-dark">{{ Str::limit($video->title, 65) }}</span>
                    <div class="small text-muted mt-1" style="font-weight: normal; font-size: 0.78rem;">
                        <a href="https://www.youtube.com/watch?v={{ $video->youtube_id }}" target="_blank" class="text-decoration-none">
                            <i class="fab fa-youtube text-danger me-1"></i> ID: <span class="fw-bold text-secondary">{{ $video->youtube_id }}</span>
                        </a>
                    </div>
                </td>
                <td>
                    <span class="badge bg-secondary px-2">{{ $video->sort_order }}</span>
                </td>
                <td>
                    @php
                        $statusBadge = $video->is_active ? 'success' : 'warning';
                    @endphp
                    <button type="button" class="btn btn-sm btn-{{ $statusBadge }} toggle-status status-badge w-100" data-id="{{ $video->id }}">
                        <i class="fas {{ $video->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $video->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    </button>
                </td>
                <td class="small text-muted">{{ $video->created_at->format('d M, Y (h:i A)') }}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.videos.edit', $video->id) }}" class="btn btn-primary" title="এডিট">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-danger delete-video" 
                                data-id="{{ $video->id }}" 
                                data-name="{{ $video->title }}"
                                title="ডিলিট">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-5">
                    <i class="fab fa-youtube fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">কোনো ভিডিও পাওয়া যায়নি</p>
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
