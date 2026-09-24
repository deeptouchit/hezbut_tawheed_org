{{-- resources/views/admin/live_broadcasts/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr>
                <th width="60">#</th>
                <th width="120">প্রিভিউ</th>
                <th>অনুষ্ঠানের শিরোনাম ও লিঙ্ক</th>
                <th>প্ল্যাটফর্ম</th>
                <th>সম্প্রচারের সময়</th>
                <th>স্ট্যাটাস</th>
                <th>লাইভ টগল</th>
                <th>মোট ভিউ</th>
                <th width="120">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($broadcasts as $index => $broadcast)
            @php
                $isUpcoming = $broadcast->schedule_time->isFuture();
                $isLive = $broadcast->is_live && $broadcast->is_active;
                
                // Determine display status badge
                if (!$broadcast->is_active) {
                    $statusBadge = '<span class="badge bg-secondary">🚫 নিষ্ক্রিয়</span>';
                } elseif ($isLive) {
                    $statusBadge = '<span class="badge bg-danger pulse-badge">🔴 লাইভ চলছে</span>';
                } elseif ($isUpcoming) {
                    $statusBadge = '<span class="badge bg-warning text-dark">⏳ শিডিউল</span>';
                } else {
                    $statusBadge = '<span class="badge bg-success">✅ আর্কাইভ</span>';
                }
            @endphp
            <tr class="align-middle">
                <td>
                    @if($broadcasts instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                        {{ $broadcasts->firstItem() + $index }}
                    @else
                        {{ $index + 1 }}
                    @endif
                </td>
                <td>
                    <div class="position-relative overflow-hidden rounded shadow-sm bg-light" style="width: 100px; height: 60px;">
                        <img src="{{ $broadcast->thumbnail_url }}" 
                             alt="{{ $broadcast->title }}" 
                             class="w-100 h-100"
                             style="object-fit: cover;"
                             loading="lazy">
                        @if($isLive)
                        <!-- Small pulse dot -->
                        <span class="position-absolute top-0 start-0 m-1 p-1 bg-danger border border-light rounded-circle animate-pulse" style="width: 10px; height: 10px;"></span>
                        @endif
                    </div>
                </td>
                <td>
                    <strong class="text-dark">{{ $broadcast->title }}</strong>
                    <div class="small text-muted mt-1">
                        @if($broadcast->source_type === 'youtube')
                            <a href="https://www.youtube.com/watch?v={{ $broadcast->video_id }}" target="_blank" class="text-decoration-none">
                                <i class="fab fa-youtube text-danger me-1"></i> ID: <span class="fw-bold text-dark">{{ $broadcast->video_id }}</span>
                            </a>
                        @else
                            <a href="{{ $broadcast->video_id }}" target="_blank" class="text-decoration-none text-truncate d-inline-block" style="max-width: 250px;">
                                <i class="fab fa-facebook text-primary me-1"></i> URL: <span class="fw-bold text-dark">{{ $broadcast->video_id }}</span>
                            </a>
                        @endif
                    </div>
                </td>
                <td class="text-center">
                    @if($broadcast->source_type === 'youtube')
                        <span class="text-danger fs-4" title="YouTube"><i class="fab fa-youtube"></i></span>
                    @else
                        <span class="text-primary fs-4" title="Facebook"><i class="fab fa-facebook"></i></span>
                    @endif
                </td>
                <td>
                    <div class="fw-bold">{{ $broadcast->schedule_time->format('d M, Y') }}</div>
                    <div class="small text-muted">{{ $broadcast->schedule_time->format('h:i A') }}</div>
                </td>
                <td>
                    {!! $statusBadge !!}
                </td>
                <td>
                    <button class="btn btn-sm toggle-live {{ $broadcast->is_live ? 'btn-danger' : 'btn-outline-danger' }}" 
                            data-id="{{ $broadcast->id }}" 
                            title="লাইভ অন/অফ করুন"
                            {{ !$broadcast->is_active ? 'disabled' : '' }}>
                        <i class="fas fa-broadcast-tower me-1"></i> {{ $broadcast->is_live ? 'লাইভ বন্ধ' : 'লাইভ অন' }}
                    </button>
                </td>
                <td>
                    <span class="badge bg-light text-dark border"><i class="fas fa-eye me-1"></i> {{ number_format($broadcast->view_count) }}</span>
                </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.live-broadcasts.edit', $broadcast->id) }}" class="btn btn-primary" title="এডিট করুন">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-danger delete-broadcast" 
                                data-id="{{ $broadcast->id }}" 
                                data-name="{{ $broadcast->title }}"
                                title="ডিলিট করুন">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-5 text-muted">
                    <i class="fas fa-broadcast-tower fa-3x mb-3 text-secondary"></i>
                    <p class="mb-0">কোনো লাইভ ব্রডকাস্টের শিডিউল পাওয়া যায়নি।</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Link rendering -->
@if($broadcasts instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $broadcasts->hasPages())
    <div class="card-footer clearfix bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="text-muted small mb-2 mb-md-0">
                মোট {{ $broadcasts->total() }} টির মধ্যে {{ $broadcasts->firstItem() }}-{{ $broadcasts->lastItem() }} টি দেখানো হচ্ছে
            </div>
            <div>
                {{ $broadcasts->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endif

<!-- Styles for Pulsing Animation -->
<style>
.pulse-badge {
    animation: badgePulse 1.5s infinite alternate;
}
@keyframes badgePulse {
    0% { transform: scale(1); opacity: 0.9; }
    100% { transform: scale(1.05); opacity: 1; }
}
.animate-pulse {
    animation: dotPulse 1.5s infinite;
}
@keyframes dotPulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(220, 53, 69, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
}
</style>
