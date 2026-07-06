{{-- resources/views/admin/pages/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr>
                <th width="60">#</th>
                <th>শিরোনাম</th>
                <th>স্ল্যাগ (Slug)</th>
                <th>স্ট্যাটাস</th>
                <th>তৈরির তারিখ</th>
                <th width="150">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pages as $index => $page)
            <tr class="align-middle">
                <td>{{ $pages->firstItem() + $index }}</td>
                <td>
                    <strong>{{ $page->title }}</strong>
                    <br>
                    <a href="/{{ $page->slug }}" target="_blank" class="small text-muted text-decoration-none">
                        <i class="fas fa-external-link-alt small"></i> /{{ $page->slug }}
                    </a>
                </td>
                <td>
                    <span class="badge bg-secondary font-monospace">{{ $page->slug }}</span>
                </td>
                <td>
                    <button class="btn btn-sm toggle-status {{ $page->is_active ? 'btn-success' : 'btn-danger' }}" data-id="{{ $page->id }}" title="স্ট্যাটাস পরিবর্তন করুন">
                        {{ $page->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    </button>
                </td>
                <td>{{ $page->created_at->format('d M, Y (h:i A)') }}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-primary" title="এডিট করুন">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-danger delete-page" 
                                data-id="{{ $page->id }}" 
                                data-name="{{ $page->title }}"
                                title="ডিলিট করুন">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                    <i class="fas fa-copy fa-3x mb-3 text-secondary"></i>
                    <p class="mb-0">কোন পেজ পাওয়া যায়নি।</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Link rendering -->
@if($pages instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $pages->hasPages())
    <div class="card-footer clearfix bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="text-muted small mb-2 mb-md-0">
                মোট {{ $pages->total() }} টি পেজের মধ্যে {{ $pages->firstItem() }}-{{ $pages->lastItem() }} টি দেখানো হচ্ছে
            </div>
            <div>
                {{ $pages->appends(request()->all())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endif
