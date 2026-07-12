

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
        <thead class="">
            <tr class="text-center">
                <th>#</th>
                <th>শিরোনাম</th>
                <th>লিংক (URL)</th>
                <th>স্ল্যাগ (Slug)</th>
                <th>স্ট্যাটাস</th>
                <th>তৈরির তারিখ</th>
                <th>একশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pages as $page)
            <tr class="table-row-hover" data-id="{{ $page->id }}">
                <td class="text-center">{{ method_exists($pages, 'firstItem') && $pages->firstItem() ? $pages->firstItem() + $loop->index : $loop->iteration }}</td>
                <td>
                    <span class="fw-bold">{{ Str::limit($page->title, 70) }}</span>
                </td>
                <td>
                    <a href="/{{ $page->slug }}" target="_blank" class="small text-muted text-decoration-none">
                        <i class="fas fa-external-link-alt" style="font-size: 10px;"></i> /{{ $page->slug }}
                    </a>
                </td>
                <td class="text-center p-0">
                    <span class="badge bg-secondary font-monospace" style="display: inline-block; max-width: 150px; overflow: hidden; text-overflow: ellipsis; vertical-align: middle;">{{ $page->slug }}</span>
                </td>
                <td class="text-center p-0">
                    @php
                        $statusBadge = $page->is_active ? 'success' : 'secondary';
                    @endphp
                    <button type="button"
                            class="btn btn-sm btn-{{ $statusBadge }} toggle-status status-badge"
                            data-id="{{ $page->id }}"
                            title="স্ট্যাটাস পরিবর্তন করুন">
                        <i class="fas {{ $page->is_active ? 'fa-check-circle' : 'fa-pencil-alt' }}"></i>
                        {{ $page->is_active ? 'লাইভ (Live)' : 'খসড়া (Draft)' }}
                    </button>
                </td>
                <td class="text-center">
                    {{ $page->created_at ? $page->created_at->diffForHumans() : 'N/A' }}
                </td>
                <td class="text-center p-0">
                    <div class="btn-group btn-group-sm">
                        <a href="/{{ $page->slug }}" target="_blank" class="btn btn-success text-white" title="দেখুন">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-primary" title="এডিট">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-danger delete-page" 
                                data-id="{{ $page->id }}" 
                                data-name="{{ $page->title }}"
                                title="ডিলিট">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-5">
                    <i class="fas fa-copy fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">কোন পেজ পাওয়া যায়নি</p>
                    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm mt-3">
                        <i class="fas fa-plus mr-1"></i> নতুন পেজ তৈরি করুন
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Link rendering -->
@if(isset($pages) && method_exists($pages, 'links'))
    <div class="row mt-3">
        <div class="col-12 d-flex justify-content-center">
            {{ $pages->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
