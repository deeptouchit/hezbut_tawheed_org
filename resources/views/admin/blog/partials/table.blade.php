{{-- resources/views/admin/blog/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped" id="sortable-table">
        <thead class="table-light">
            <tr>
                <th width="30">
                    <input type="checkbox" id="selectAll">
                </th>
                <th width="30">
                    <i class="fas fa-grip-vertical drag-handle" title="ড্র্যাগ করে সাজান"></i>
                </th>
                <th width="50">#</th>
                <th width="80">ছবি</th>
                <th>শিরোনাম</th>
                <th>ক্যাটাগরি</th>
                <th>লেখক</th>
                <th>ভিউ</th>
                <th>স্ট্যাটাস</th>
                <th>তারিখ</th>
                <th width="180">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody id="sortable-body">
            @forelse($blogs as $index => $blog)
            <tr class="table-row-hover" data-id="{{ $blog->id }}">
                <td>
                    <input type="checkbox" class="blog-checkbox" value="{{ $blog->id }}">
                </td>
                <td class="text-center">
                    <i class="fas fa-grip-vertical drag-handle"></i>
                </td>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <img src="{{ $blog->featured_image_url }}"
                         alt="{{ $blog->title }}"
                         class="blog-image"
                         loading="lazy"
                         onerror="this.src='{{ asset('themes/bogura-bazar/images/blog/blog-default.png') }}'">
                </td>
                <td>
                    <div class="blog-title">{{ Str::limit($blog->title, 50) }}</div>
                    @if($blog->tags_array)
                        <div class="mt-1">
                            @foreach($blog->tags_array as $tag)
                                <span class="tag-badge"><i class="fas fa-tag"></i> {{ Str::limit($tag, 15) }}</span>
                            @endforeach
                        </div>
                    @endif
                    <div class="blog-meta mt-1">
                        <i class="fas fa-clock"></i> {{ $blog->created_at?->diffForHumans() }}
                    </div>
                </td>
                <td>
                    @if($blog->category)
                        <span class="badge bg-info">{{ $blog->category->name }}</span>
                    @else
                        <span class="badge bg-secondary">N/A</span>
                    @endif
                </td>
                <td>
                    {{ $blog->author?->name ?? 'N/A' }}
                </td>
                <td>
                    <span class="view-count">
                        <i class="fas fa-eye"></i> {{ number_format($blog->views ?? 0) }}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm toggle-status status-badge" data-id="{{ $blog->id }}">
                        @if($blog->status)
                            <span class="badge bg-success">প্রকাশিত</span>
                        @else
                            <span class="badge bg-warning">খসড়া</span>
                        @endif
                    </button>
                </td>
                <td>
                    <div class="blog-meta">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $blog->published_at?->format('d M, Y') ?? 'N/A' }}
                    </div>
                    <div class="blog-meta">
                        <i class="fas fa-clock"></i>
                        {{ $blog->published_at?->diffForHumans() ?? 'N/A' }}
                    </div>
                </td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-success view-blog" data-id="{{ $blog->id }}" title="বিস্তারিত দেখুন">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="{{ route('admin.blog.posts.edit', $blog->id) }}" class="btn btn-primary" title="এডিট করুন">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-info duplicate-blog" data-id="{{ $blog->id }}" title="ডুপ্লিকেট করুন">
                            <i class="fas fa-copy"></i>
                        </button>
                        <button class="btn btn-danger delete-blog"
                                data-id="{{ $blog->id }}"
                                data-title="{{ $blog->title }}"
                                data-comment-count="{{ $blog->allComments()->count() }}"
                                title="ডিলিট করুন">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center py-5">
                    <i class="fas fa-blog fa-3x text-muted mb-3"></i>
                    <p class="text-muted">কোন ব্লগ পোস্ট পাওয়া যায়নি</p>
                    <a href="{{ route('admin.blog.posts.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> নতুন ব্লগ পোস্ট তৈরি করুন
                    </a>
                    <button class="btn btn-secondary ms-2" id="reset-filter-empty">
                        <i class="fas fa-undo-alt"></i> ফিল্টার রিসেট
                    </button>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if(isset($blogs) && method_exists($blogs, 'links'))
    <div class="d-flex justify-content-center mt-3">
        {{ $blogs->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
@endif

@push('scripts')
<script>
$(document).ready(function() {
    // Reset filter from empty state
    $('#reset-filter-empty').on('click', function() {
        $('#search-input').val('');
        $('#category-filter').val('');
        $('#status-filter').val('');
        $('#author-filter').val('');
        $('#per-page-filter').val('20');
        loadBlogs();
    });
});
</script>
@endpush
