{{-- resources/views/admin/leaders/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
        <thead>
            <tr class="text-center">
                <th>#</th>
                <th>ছবি</th>
                <th>নেতার নাম</th>
                <th>পদবী</th>
                <th>ক্যাটাগরি</th>
                <th>অর্ডার (ক্রম)</th>
                <th>স্ট্যাটাস</th>
                <th>একশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaders as $index => $leader)
            @php
                // Category Translate Map
                $catMap = [
                    'central' => '<span class="badge bg-primary" style="width: 130px;">কেন্দ্রীয় নেতৃত্ব</span>',
                    'advisory' => '<span class="badge bg-info text-dark" style="width: 130px;">উপদেষ্টা পরিষদ</span>',
                    'executive' => '<span class="badge bg-success" style="width: 130px;">নির্বাহী কমিটি</span>',
                    'regional' => '<span class="badge bg-secondary" style="width: 130px;">আঞ্চলিক নেতৃত্ব</span>'
                ];
            @endphp
            <tr class="table-row-hover" data-id="{{ $leader->id }}">
                <td class="text-center">
                    @if($leaders instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                        {{ $leaders->firstItem() + $index }}
                    @else
                        {{ $index + 1 }}
                    @endif
                </td>
                <td class="text-center p-0">
                    <div class="d-flex align-items-center justify-content-center" style="height: 30px;">
                        <img src="{{ $leader->image_url }}"
                             class="img-circle img-size-32" alt="{{ $leader->name }}"
                             style="width: 30px; height: 30px; object-fit: cover; border-radius: 2px;"
                             onerror="this.src='https://ui-avatars.com/api/?name='+urlencode('{{ $leader->name }}')+'&background=006A4E&color=fff'">
                    </div>
                </td>
                <td>
                    <div class="fw-bold">
                        {{ $leader->name }}
                        @if($leader->is_founder)
                            <span class="badge bg-danger ms-1" style="font-size: 9px; padding: 2px 4px;">Founder</span>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="fw-bold">{{ $leader->designation }}</div>
                </td>
                <td class="text-center p-0">
                    {!! $catMap[$leader->category] ?? '<span class="badge bg-secondary" style="width: 130px;">আঞ্চলিক</span>' !!}
                </td>
                <td class="p-1">
                    <div class="input-group input-group-sm mx-auto" style="max-width: 110px;">
                        <input type="number" id="order-input-{{ $leader->id }}" class="form-control text-center py-0" value="{{ $leader->sort_order }}" min="0" style="height: 25px;">
                        <button class="btn btn-sm btn-outline-success save-order-btn py-0" data-id="{{ $leader->id }}" title="সংরক্ষণ করুন" style="height: 25px; line-height: 1;">
                            <i class="fas fa-save" style="font-size: 11px;"></i>
                        </button>
                    </div>
                </td>
                <td class="text-center p-0">
                    <button type="button"
                            class="btn btn-sm btn-{{ $leader->is_active ? 'success' : 'warning' }} toggle-active status-badge"
                            data-id="{{ $leader->id }}">
                        <i class="fas {{ $leader->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $leader->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    </button>
                </td>
                <td class="text-center p-0">
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('leadership.show', $leader->slug) }}" class="btn btn-success" target="_blank" title="দেখুন">
                            <i class="fas fa-eye text-white"></i>
                        </a>
                        <a href="{{ route('admin.leaders.edit', $leader->id) }}" class="btn btn-primary" title="এডিট">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-danger delete-leader"
                                data-id="{{ $leader->id }}"
                                data-name="{{ $leader->name }}"
                                title="ডিলিট">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-5">
                    <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">কোনো নেতৃত্বের প্রোফাইল পাওয়া যায়নি।</p>
                    <a href="{{ route('admin.leaders.create') }}" class="btn btn-primary btn-sm mt-3">
                        <i class="fas fa-plus mr-1"></i> নতুন প্রোফাইল যোগ করুন
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($leaders instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $leaders->hasPages())
    <div class="row mt-3">
        <div class="col-12 d-flex justify-content-center">
            {{ $leaders->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
