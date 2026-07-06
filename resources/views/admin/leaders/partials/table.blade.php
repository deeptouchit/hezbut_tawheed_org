{{-- resources/views/admin/leaders/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr>
                <th width="60">#</th>
                <th width="80">ছবি</th>
                <th>নেতার নাম (বাংলা ও ইংরেজি)</th>
                <th>পদবী</th>
                <th>ক্যাটাগরি</th>
                <th width="140">অর্ডার (ক্রম)</th>
                <th width="100">স্ট্যাটাস</th>
                <th width="120">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaders as $index => $leader)
            @php
                // Category Translate Map
                $catMap = [
                    'central' => '<span class="badge bg-primary">কেন্দ্রীয় নেতৃত্ব</span>',
                    'advisory' => '<span class="badge bg-info text-dark">উপদেষ্টা পরিষদ</span>',
                    'executive' => '<span class="badge bg-success">নির্বাহী কমিটি</span>',
                    'regional' => '<span class="badge bg-secondary">আঞ্চলিক নেতৃত্ব</span>'
                ];
            @endphp
            <tr class="align-middle">
                <td>
                    @if($leaders instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                        {{ $leaders->firstItem() + $index }}
                    @else
                        {{ $index + 1 }}
                    @endif
                </td>
                <td>
                    <div class="overflow-hidden rounded-circle bg-light shadow-sm" style="width: 50px; height: 50px; border: 2px solid #ddd;">
                        <img src="{{ $leader->image_url }}" 
                             alt="{{ $leader->name }}" 
                             class="w-100 h-100"
                             style="object-fit: cover;"
                             loading="lazy">
                    </div>
                </td>
                <td>
                    <strong class="text-dark">{{ $leader->name }}</strong>
                    @if($leader->is_founder)
                        <span class="badge bg-danger ms-1" style="font-size: 10px;">Spotlight / Founder</span>
                    @endif
                    <div class="small text-muted mt-1">
                        <i class="fas fa-globe me-1"></i> {{ $leader->english_name }} | <span class="fw-bold">{{ $leader->slug }}</span>
                    </div>
                </td>
                <td>
                    <span class="text-dark fw-bold">{{ $leader->designation }}</span>
                </td>
                <td>
                    {!! $catMap[$leader->category] ?? '<span class="badge bg-secondary">আঞ্চলিক</span>' !!}
                </td>
                <td>
                    <div class="input-group input-group-sm" style="max-width: 120px;">
                        <input type="number" id="order-input-{{ $leader->id }}" class="form-control text-center" value="{{ $leader->sort_order }}" min="0">
                        <button class="btn btn-outline-success save-order-btn" data-id="{{ $leader->id }}" title="সংরক্ষণ করুন">
                            <i class="fas fa-save"></i>
                        </button>
                    </div>
                </td>
                <td>
                    <button class="btn btn-sm toggle-active {{ $leader->is_active ? 'btn-success' : 'btn-outline-secondary' }}" 
                            data-id="{{ $leader->id }}" 
                            title="সক্রিয়/নিষ্ক্রিয় টগল">
                        <i class="fas {{ $leader->is_active ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i> {{ $leader->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    </button>
                </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('leadership.show', $leader->slug) }}" class="btn btn-info" target="_blank" title="প্রোফাইল ভিউ">
                            <i class="fas fa-eye text-white"></i>
                        </a>
                        <a href="{{ route('admin.leaders.edit', $leader->id) }}" class="btn btn-primary" title="এডিট করুন">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-danger delete-leader" 
                                data-id="{{ $leader->id }}" 
                                data-name="{{ $leader->name }}"
                                title="ডিলিট করুন">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-5 text-muted">
                    <i class="fas fa-user-tie fa-3x mb-3 text-secondary"></i>
                    <p class="mb-0">কোনো নেতৃত্বের প্রোফাইল পাওয়া যায়নি।</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Link rendering -->
@if($leaders instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $leaders->hasPages())
    <div class="card-footer clearfix bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="text-muted small mb-2 mb-md-0">
                মোট {{ $leaders->total() }} টির মধ্যে {{ $leaders->firstItem() }}-{{ $leaders->lastItem() }} টি দেখানো হচ্ছে
            </div>
            <div>
                {{ $leaders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endif
