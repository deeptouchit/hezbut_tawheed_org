{{-- resources/views/admin/blog/categories/partials/table.blade.php --}}

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
                <th>ক্যাটাগরি নাম</th>
                <th>স্ল্যাগ (Slug)</th>
                <th>ব্লগ সংখ্যা</th>
                <th>স্ট্যাটাস</th>
                <th>তৈরি হয়েছে</th>
                <th>একশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>
                    <input type="checkbox" class="category-checkbox" value="{{ $category->id }}">
                </td>
                <td class="text-center">
                    <i class="fas fa-grip-vertical drag-handle" style="cursor: move;"></i>
                </td>
                <td>{{ $category->id }}</td>
                <td class="text-center p-0">
                    <div class="d-flex align-items-center justify-content-center" style="height: 30px;">
                        <img src="{{ $category->image_url }}"
                             class="img-circle img-size-32" alt="{{ $category->name }}" 
                             style="width: 30px; height: 30px; object-fit: cover; border-radius: 2px;"
                             onerror="this.src='https://ui-avatars.com/api/?name='+urlencode('{{ $category->name }}')+'&background=2F54EB&color=fff'">
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center fw-bold">
                       {{ $category->name }}
                    </div>
                </td>
                <td>{{ $category->slug }}</td>
                <td class="text-center p-0">
                    <span class="badge bg-info" style="width: 80px;">
                        <i class="fas fa-file-alt"></i> {{ $category->blogs_count ?? 0 }}
                    </span>
                </td>
                <td class="text-center p-0">
                    @php
                        $statusBadge = $category->status ? 'success' : 'danger';
                    @endphp
                    <button type="button"
                            class="btn btn-sm btn-{{ $statusBadge }} toggle-status status-badge"
                            data-id="{{ $category->id }}">
                        <i class="fas {{ $category->status ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $category->status ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    </button>
                </td>
                <td>
                    {{ $category->created_at ? $category->created_at->diffForHumans() : 'N/A' }}
                </td>
                <td class="text-center p-0">
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.blog.categories.show', $category->id) }}" class="btn btn-info" title="দেখুন">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.blog.categories.edit', $category->id) }}" class="btn btn-primary" title="এডিট">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-danger delete-category"
                                data-id="{{ $category->id }}"
                                data-name="{{ $category->name }}"
                                data-blog-count="{{ $category->blogs_count ?? 0 }}"
                                title="ডিলিট">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center py-5">
                    <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">কোনো ক্যাটাগরি পাওয়া যায়নি</p>
                    <a href="{{ route('admin.blog.categories.create') }}" class="btn btn-primary btn-sm mt-3">
                        <i class="fas fa-plus mr-1"></i> নতুন ক্যাটাগরি যোগ করুন
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if(isset($categories) && method_exists($categories, 'links'))
    <div class="row mt-3">
        <div class="col-12 d-flex justify-content-center">
            {{ $categories->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
