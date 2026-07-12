{{-- resources/views/admin/branches/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
        <thead>
            <tr class="text-center">
                <th>#</th>
                <th>ছবি</th>
                <th>কার্যালয়ের নাম</th>
                <th>ধরণ (Type)</th>
                <th>ঠিকানা</th>
                <th>মোবাইল নাম্বার</th>
                <th>স্ট্যাটাস</th>
                <th>একশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($branches as $index => $branch)
            @php
                $typeMap = [
                    'central' => '<span class="badge bg-danger" style="width: 135px;">কেন্দ্রীয় কার্যালয়</span>',
                    'division' => '<span class="badge bg-primary" style="width: 135px;">বিভাগীয় কার্যালয়</span>',
                    'district' => '<span class="badge bg-success" style="width: 135px;">জেলা কার্যালয়</span>',
                    'upazila' => '<span class="badge bg-warning text-dark" style="width: 135px;">উপজেলা কার্যালয়</span>',
                    'international' => '<span class="badge bg-info text-dark" style="width: 135px;">আন্তর্জাতিক শাখা</span>'
                ];
            @endphp
            <tr class="table-row-hover" data-id="{{ $branch->id }}">
                <td class="text-center">
                    @if($branches instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                        {{ $branches->firstItem() + $index }}
                    @else
                        {{ $index + 1 }}
                    @endif
                </td>
                <td class="text-center p-0">
                    <div class="d-flex align-items-center justify-content-center" style="height: 30px;">
                        <img src="{{ $branch->image_url }}"
                             class="img-circle img-size-32" alt="{{ $branch->name }}"
                             style="width: 30px; height: 30px; object-fit: cover; border-radius: 2px;"
                             onerror="this.src='https://ui-avatars.com/api/?name='+urlencode('{{ $branch->name }}')+'&background=006A4E&color=fff'">
                    </div>
                </td>
                <td>
                    <div class="fw-bold">{{ $branch->name }}</div>
                </td>
                <td class="text-center p-0">
                    {!! $typeMap[$branch->type] ?? '<span class="badge bg-secondary" style="width: 135px;">শাখা</span>' !!}
                </td>
                <td>
                    <div class="text-truncate fw-semibold" style="max-width: 250px;" title="{{ $branch->address }}">{{ $branch->address }}</div>
                </td>
                <td>
                    @if($branch->phone)
                        <span class="fw-bold text-dark">{{ $branch->phone }}</span>
                    @else
                        <span class="text-muted small">-</span>
                    @endif
                </td>
                <td class="text-center p-0">
                    <button type="button"
                            class="btn btn-sm btn-{{ $branch->is_active ? 'success' : 'warning' }} toggle-status status-badge"
                            data-id="{{ $branch->id }}">
                        <i class="fas {{ $branch->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $branch->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    </button>
                </td>
                <td class="text-center p-0">
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.branches.edit', $branch->id) }}" class="btn btn-primary" title="এডিট">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-danger delete-branch"
                                data-id="{{ $branch->id }}"
                                data-name="{{ $branch->name }}"
                                title="ডিলিট">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-5">
                    <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">কোনো কার্যালয় বা শাখা পাওয়া যায়নি।</p>
                    <a href="{{ route('admin.branches.create') }}" class="btn btn-primary btn-sm mt-3">
                        <i class="fas fa-plus mr-1"></i> নতুন কার্যালয় যোগ করুন
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($branches instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $branches->hasPages())
    <div class="row mt-3">
        <div class="col-12 d-flex justify-content-center">
            {{ $branches->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
