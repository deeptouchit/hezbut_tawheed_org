
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
        <thead>
            <tr class="text-center">
                <th>#</th>
                <th>প্রচ্ছদ</th>
                <th class="text-start">বইয়ের শিরোনাম</th>
                <th>ক্যাটাগরি</th>
                <th>পিডিএফ</th>
                <th>স্ট্যাটাস</th>
                <th>শীর্ষে</th>
                <th>বেস্ট সেলার</th>
                <th>অর্ডার</th>
                <th>অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($books as $index => $book)
            <tr class="align-middle text-center">
                <td>{{ $books instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ? $books->firstItem() + $index : $index + 1 }}</td>
                <td class="p-1">
                    <div class="d-flex align-items-center justify-content-center" style="height: 45px;">
                        <img src="{{ $book->image_url }}" 
                             alt="{{ $book->title }}" 
                             class="img-thumbnail"
                             style="width: 35px; height: 45px; object-fit: cover; border-radius: 2px;"
                             loading="lazy"
                             onerror="this.src='https://ui-avatars.com/api/?name='+urlencode('{{ $book->title }}')+'&background=2F54EB&color=fff'">
                    </div>
                </td>
                <td class="text-start fw-bold">
                    <span>{{ Str::limit($book->title, 60) }}</span>
                </td>
                <td>
                    @if($book->category)
                        <span class="badge bg-info text-white" style="font-size: 11px;">{{ $book->category->name }}</span>
                    @else
                        <span class="badge bg-secondary text-white" style="font-size: 11px;">-</span>
                    @endif
                </td>
                <td>
                    @if($book->pdf_url)
                        <a href="{{ url($book->pdf_url) }}" target="_blank" class="badge bg-danger text-decoration-none" title="পিডিএফ ফাইল ডাউনলোড করুন">
                            <i class="fas fa-file-pdf"></i> ডাউনলোড
                        </a>
                    @else
                        <span class="badge bg-secondary">নেই</span>
                    @endif
                </td>
                <td>
                    @php
                        $statusBadge = $book->is_active ? 'success' : 'warning';
                    @endphp
                    <button type="button" class="btn btn-sm btn-{{ $statusBadge }} toggle-status status-badge w-100" data-id="{{ $book->id }}">
                        <i class="fas {{ $book->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $book->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    </button>
                </td>
                <td>
                    <div class="form-check form-switch d-inline-block">
                        <input class="form-check-input toggle-popular" type="checkbox" data-id="{{ $book->id }}" {{ $book->is_popular ? 'checked' : '' }}>
                    </div>
                </td>
                <td>
                    <div class="form-check form-switch d-inline-block">
                        <input class="form-check-input toggle-bestseller" type="checkbox" data-id="{{ $book->id }}" {{ $book->is_bestseller ? 'checked' : '' }}>
                    </div>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm update-order text-center mx-auto" data-id="{{ $book->id }}" value="{{ $book->popular_order }}" style="width: 60px;" min="0">
                </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.books.show', $book->id) }}" class="btn btn-success text-white" title="দেখুন">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-primary" title="এডিট">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-danger delete-book" 
                                data-id="{{ $book->id }}" 
                                data-name="{{ $book->title }}"
                                title="ডিলিট">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">কোনো বই পাওয়া যায়নি</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($books instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $books->hasPages())
    <div class="card-footer clearfix bg-white border-0 d-flex justify-content-center">
        {{ $books->links('pagination::bootstrap-5') }}
    </div>
@endif
