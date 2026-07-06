{{-- resources/views/admin/blog/categories/partials/table.blade.php --}}

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
                <th width="70">ছবি</th>
                <th>ক্যাটাগরি নাম</th>
                <th>বিবরণ</th>
                <th>ব্লগ</th>
                <th>স্ট্যাটাস</th>
                <th width="180">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody id="sortable-body">
            @forelse($categories as $index => $category)
            <tr class="table-row-hover" data-id="{{ $category->id }}">
                <td>
                    <input type="checkbox" class="category-checkbox" value="{{ $category->id }}">
                </td>
                <td class="text-center">
                    <i class="fas fa-grip-vertical drag-handle"></i>
                </td>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <img src="{{ $category->image_url }}"
                         alt="{{ $category->name }}"
                         class="category-image"
                         loading="lazy"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($category->name) }}&size=50&background=6366f1&color=fff&rounded=true'">
                </td>
                <td>
                    <strong>{{ $category->name }}</strong>
                    @if($category->slug)
                        <br><small class="text-muted"><i class="fas fa-link"></i> {{ $category->slug }}</small>
                    @endif
                    <div class="mt-1">
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> {{ $category->created_at?->diffForHumans() }}
                        </small>
                    </div>
                </td>
                <td>
                    @if($category->description)
                        <span title="{{ $category->description }}">
                            {{ Str::limit($category->description, 50) }}
                        </span>
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>
                    <span class="badge bg-info">
                        <i class="fas fa-file-alt"></i> {{ $category->blogs_count ?? 0 }}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm toggle-status status-badge" data-id="{{ $category->id }}">
                        {!! $category->status ? '<span class="badge bg-success">সক্রিয়</span>' : '<span class="badge bg-danger">নিষ্ক্রিয়</span>' !!}
                    </button>
                </td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ route('admin.blog.categories.show', $category->id) }}" class="btn btn-info" title="বিস্তারিত দেখুন">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.blog.categories.edit', $category->id) }}" class="btn btn-primary" title="এডিট করুন">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-danger delete-category"
                                data-id="{{ $category->id }}"
                                data-name="{{ $category->name }}"
                                data-blog-count="{{ $category->blogs_count ?? 0 }}"
                                title="ডিলিট করুন">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-5">
                    <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                    <p class="text-muted">কোন ক্যাটাগরি পাওয়া যায়নি</p>
                    <a href="{{ route('admin.blog.categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> নতুন ক্যাটাগরি যোগ করুন
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
@if(isset($categories) && method_exists($categories, 'links'))
    <div class="d-flex justify-content-center mt-3">
        {{ $categories->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
@endif

@push('scripts')
<script>
$(document).ready(function() {
    // Reset filter from empty state
    $('#reset-filter-empty').on('click', function() {
        $('#search-input').val('');
        $('#status-filter').val('');
        $('#per-page-filter').val('20');
        $('#date-from-filter').val('');
        $('#date-to-filter').val('');
        loadCategories();
    });
});
</script>
@endpush
