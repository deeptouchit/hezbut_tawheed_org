
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
        <thead>
            <tr class="text-center">
                <th>#</th>
                <th>থাম্বনেইল</th>
                <th class="text-start">গানের শিরোনাম ও ইউটিউব লিঙ্ক</th>
                <th>ক্যাটাগরি</th>

                <th>অর্ডার</th>
                <th>স্ট্যাটাস</th>

                <th>অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($songs as $index => $song)
            <tr class="align-middle text-center">
                <td>{{ $songs instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ? $songs->firstItem() + $index : $index + 1 }}</td>
                <td class="p-1">
                    <div class="d-flex align-items-center justify-content-center" style="height: 45px;">
                        <div class="position-relative overflow-hidden rounded bg-light" style="width: 75px; height: 42px;">
                            <img src="{{ $song->thumbnail_url }}"
                                 alt="{{ $song->title }}"
                                 class="w-100 h-100"
                                 style="object-fit: cover;"
                                 loading="lazy">
                            <!-- Play indicator overlay -->
                            <div class="position-absolute top-50 start-50 translate-middle bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 18px; height: 18px; opacity: 0.85;">
                                <i class="fas fa-play" style="font-size: 7px; margin-left: 1px;"></i>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="text-start fw-bold">
                    <span class="text-dark">{{ Str::limit($song->title, 65) }}</span>
                    @if($song->youtube_id)
                        <div class="small text-muted mt-1" style="font-weight: normal; font-size: 0.78rem;">
                            <a href="https://www.youtube.com/watch?v={{ $song->youtube_id }}" target="_blank" class="text-decoration-none">
                                <i class="fab fa-youtube text-danger me-1"></i> ID: <span class="fw-bold text-secondary">{{ $song->youtube_id }}</span>
                            </a>
                        </div>
                    @else
                        <div class="small text-muted mt-1" style="font-weight: normal; font-size: 0.78rem;">
                            <i class="fas fa-music text-success me-1"></i> Local Upload
                        </div>
                    @endif
                </td>
                <td>
                    <span class="badge bg-secondary">
                        @if($song->category == 'party_anthem') দলীয় সঙ্গীত
                        @elseif($song->category == 'national') দেশাত্মবোধক
                        @elseif($song->category == 'awakening') জাগরণী গান
                        @else {{ $song->category }}
                        @endif
                    </span>
                </td>

                <td>
                    <span class="badge bg-secondary px-2">{{ $song->sort_order }}</span>
                </td>
                <td>
                    @php
                        $statusBadge = $song->is_active ? 'success' : 'warning';
                    @endphp
                    <button type="button" class="btn btn-sm btn-{{ $statusBadge }} toggle-status status-badge w-100" data-id="{{ $song->id }}">
                        <i class="fas {{ $song->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $song->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    </button>
                </td>

                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.songs.edit', $song->id) }}" class="btn btn-primary" title="এডিট">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-danger delete-song"
                                data-id="{{ $song->id }}"
                                data-name="{{ $song->title }}"
                                title="ডিলিট">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center py-5">
                    <i class="fas fa-music fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">কোনো গান পাওয়া যায়নি</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Link rendering -->
@if($songs instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $songs->hasPages())
    <div class="card-footer clearfix bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="text-muted small mb-2 mb-md-0">
                মোট {{ $songs->total() }} টি গানের মধ্যে {{ $songs->firstItem() }}-{{ $songs->lastItem() }} টি দেখানো হচ্ছে
            </div>
            <div>
                {{ $songs->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endif
