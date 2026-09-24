{{-- resources/views/admin/blog/tags/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
        <thead>
            <tr class="text-center">
                <th>
                    <input type="checkbox" id="selectAll">
                </th>
                <th>
                    <i class="fas fa-grip-vertical drag-handle" title="ড্র্যাগ করে সাজান"></i>
                </th>
                <th>ID</th>
                <th>ট্যাগ নাম</th>
                <th>স্ল্যাগ</th>
                <th>রঙ</th>
                <th>ব্লগ সংখ্যা</th>
                <th>স্ট্যাটাস</th>
                <th>অ্যাকশন</th>
            </tr>
        </thead>
        <tbody id="sortable-body">
            @forelse($tags as $tag)
            <tr class="table-row-hover" data-id="{{ $tag->id }}">
                <td>
                    <input type="checkbox" class="tag-checkbox" value="{{ $tag->id }}">
                </td>
                <td class="text-center">
                    <i class="fas fa-grip-vertical drag-handle"></i>
                </td>
                <td>{{ $tag->id }}</td>
                <td>
                    <strong>{{ $tag->name }}</strong>
                    @if($tag->description)
                        <br><small class="text-muted">{{ Str::limit($tag->description, 60) }}</small>
                    @endif
                </td>
                <td><code>{{ $tag->slug }}</code></td>
                <td class="text-center">
                    <span class="badge" style="background-color: {{ $tag->color }}; color: #fff; padding: 5px 10px; border-radius: 4px;">
                        {{ $tag->color }}
                    </span>
                </td>
                <td class="text-center">
                    <span class="badge bg-info">
                        <i class="fas fa-file-alt"></i> {{ $tag->blogs_count ?? 0 }}
                    </span>
                </td>
                <td class="text-center p-0">
                    @php
                        $statusBadge = $tag->status ? 'success' : 'danger';
                    @endphp
                    <button type="button"
                            class="btn btn-sm btn-{{ $statusBadge }} toggle-status status-badge"
                            data-id="{{ $tag->id }}">
                        <i class="fas {{ $tag->status ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $tag->status ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    </button>
                </td>
                 <td class="text-center p-0">
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-primary btn-sm edit-tag"
                                data-id="{{ $tag->id }}"
                                data-name="{{ $tag->name }}"
                                data-slug="{{ $tag->slug }}"
                                data-color="{{ $tag->color }}"
                                data-sort-order="{{ $tag->sort_order }}"
                                data-status="{{ $tag->status }}"
                                data-description="{{ $tag->description }}"
                                title="এডিট করুন">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm delete-tag"
                                data-id="{{ $tag->id }}"
                                data-name="{{ $tag->name }}"
                                data-blog-count="{{ $tag->blogs_count ?? 0 }}"
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
                    <p class="text-muted">কোন ট্যাগ পাওয়া যায়নি</p>
                    <button class="btn btn-primary" id="create-tag-btn-empty">
                        <i class="fas fa-plus"></i> নতুন ট্যাগ যোগ করুন
                    </button>
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
@if(isset($tags) && method_exists($tags, 'links'))
    <div class="d-flex justify-content-center mt-3">
        {{ $tags->appends(request()->query())->links('pagination::bootstrap-5') }}
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
        loadTags();
    });
});
</script>
@endpush
