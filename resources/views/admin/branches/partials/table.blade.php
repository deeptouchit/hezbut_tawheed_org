<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th style="width: 60px;">ক্রম</th>
                <th style="width: 100px;">ছবি</th>
                <th>কার্যালয়ের নাম</th>
                <th>ধরণ (Type)</th>
                <th>ঠিকানা</th>
                <th>দায়িত্বপ্রাপ্ত কর্মকর্তা</th>
                <th style="width: 120px;" class="text-center">স্ট্যাটাস</th>
                <th style="width: 120px;" class="text-center">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($branches as $index => $branch)
            <tr class="align-middle" id="row-{{ $branch->id }}">
                <td>
                    @if($branches instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                        {{ $branches->firstItem() + $index }}
                    @else
                        {{ $index + 1 }}
                    @endif
                </td>
                <td>
                    <img src="{{ $branch->image_url }}" alt="{{ $branch->name }}" class="rounded border shadow-sm" style="width: 64px; height: 48px; object-fit: cover;">
                </td>
                <td>
                    <span class="fw-bold text-dark d-block">{{ $branch->name }}</span>
                    @if($branch->phone)
                        <small class="text-muted"><i class="fas fa-phone-alt me-1"></i> {{ $branch->phone }}</small>
                    @endif
                </td>
                <td>
                    <span class="badge @if($branch->type === 'central') bg-danger @elseif($branch->type === 'international') bg-purple @else bg-success @endif">
                        {{ $branch->type_label }}
                    </span>
                </td>
                <td>
                    <small class="text-dark d-block" style="max-width: 250px; white-space: normal;">{{ Str::limit($branch->address, 100) }}</small>
                </td>
                <td>
                    @forelse($branch->officials as $official)
                        <div class="mb-1">
                            <span class="fw-semibold text-dark-green d-block" style="font-size: 13px;">{{ $official->name }}</span>
                            <span class="badge bg-light text-muted border" style="font-size: 10px;">{{ $official->designation }}</span>
                        </div>
                    @empty
                        <span class="text-muted small">-</span>
                    @endforelse
                </td>
                <td class="text-center">
                    <div class="form-check form-switch d-inline-block">
                        <input class="form-check-input toggle-status" type="checkbox" data-id="{{ $branch->id }}" {{ $branch->is_active ? 'checked' : '' }} style="cursor: pointer;">
                    </div>
                </td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.branches.edit', $branch->id) }}" class="btn btn-outline-primary" title="সংশোধন">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-outline-danger delete-branch" 
                                data-id="{{ $branch->id }}" 
                                data-name="{{ $branch->name }}" 
                                title="মুছে ফেলুন">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-5 text-muted">
                    <i class="fas fa-map-marker-alt fa-3x mb-3 text-secondary"></i>
                    <p class="mb-0">কোনো কার্যালয় বা শাখা পাওয়া যায়নি।</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Link rendering -->
@if($branches instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $branches->hasPages())
    <div class="card-footer clearfix bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="text-muted small mb-2 mb-md-0">
                মোট {{ $branches->total() }} টি ফলাফলের মধ্যে {{ $branches->firstItem() }} থেকে {{ $branches->lastItem() }} দেখানো হচ্ছে।
            </div>
            <div>
                {{ $branches->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endif
