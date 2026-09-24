{{-- resources/views/admin/blog/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
        <thead class="">
            <tr class="text-center">
                <th>
                    <input type="checkbox" id="selectAll">
                </th>
                <th>
                    <i class="fas fa-grip-vertical drag-handle" title="ড্র্যাগ করে সাজান"></i>
                </th>
                <th>ID</th>
                <th>ছবি</th>
                <th>শিরোনাম</th>
                <th>ক্যাটাগরি</th>
                <th>স্ট্যাটাস</th>
                <th>তারিখ</th>
                <th>একশন</th>
            </tr>
        </thead>
        <tbody id="sortable-body">
            @forelse($blogs as $blog)
            <tr class="table-row-hover" data-id="{{ $blog->id }}">
                <td>
                    <input type="checkbox" class="blog-checkbox" value="{{ $blog->id }}">
                </td>
                <td class="text-center">
                    <i class="fas fa-grip-vertical drag-handle" style="cursor: move;"></i>
                </td>
                <td>{{ $blog->id }}</td>
                <td class="text-center p-0">
                    <div class="d-flex align-items-center justify-content-center" style="height: 30px;">
                        <img src="{{ $blog->featured_image_url }}"
                             class="img-circle img-size-32" alt="{{ $blog->title }}"
                             style="width: 30px; height: 30px; object-fit: cover; border-radius: 2px;"
                             onerror="this.src='https://ui-avatars.com/api/?name='+urlencode('{{ $blog->title }}')+'&background=2F54EB&color=fff'">
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center fw-bold">
                       {{ Str::limit($blog->title, 50) }}
                    </div>
                </td>
                <td class="text-center p-0">
                    @if($blog->category)
                        <span class="badge bg-info" style="width: 150px;">{{ $blog->category->name }}</span>
                    @else
                        <span class="badge bg-secondary" style="width: 150px;">N/A</span>
                    @endif
                </td>
                <td class="text-center p-0">
                    @php
                        $statusBadge = $blog->status ? 'success' : 'warning';
                    @endphp
                    <button type="button"
                            class="btn btn-sm btn-{{ $statusBadge }} toggle-status status-badge"
                            data-id="{{ $blog->id }}">
                        <i class="fas {{ $blog->status ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $blog->status ? 'প্রকাশিত' : 'খসড়া' }}
                    </button>
                </td>
                <td>
                    {{ $blog->published_at ? $blog->published_at->diffForHumans() : 'N/A' }}
                </td>
                <td class="text-center p-0">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-success view-blog" data-id="{{ $blog->id }}" title="দেখুন">
                            <i class="fas fa-eye text-white"></i>
                        </button>
                        <a href="{{ route('admin.blog.posts.edit', $blog->id) }}" class="btn btn-primary" title="এডিট">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-info duplicate-blog" data-id="{{ $blog->id }}" title="ডুপ্লিকেট">
                            <i class="fas fa-copy text-white"></i>
                        </button>
                        <button type="button" class="btn btn-danger delete-blog"
                                data-id="{{ $blog->id }}"
                                data-title="{{ $blog->title }}"
                                data-comment-count="{{ $blog->allComments()->count() }}"
                                title="ডিলিট">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-5">
                    <i class="fas fa-blog fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">কোন ব্লগ পোস্ট পাওয়া যায়নি</p>
                    <a href="{{ route('admin.blog.posts.create') }}" class="btn btn-primary btn-sm mt-3">
                        <i class="fas fa-plus mr-1"></i> নতুন ব্লগ পোস্ট তৈরি করুন
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if(isset($blogs) && method_exists($blogs, 'links'))
    <div class="row mt-3">
        <div class="col-12 d-flex justify-content-center">
            {{ $blogs->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
