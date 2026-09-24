{{-- resources/views/admin/testimonials/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;" id="sortable-table">
        <thead class="">
            <tr class="text-center">
                <th>
                    <input type="checkbox" id="selectAll">
                </th>
                <th>সাজান</th>
                <th>ID</th>
                <th>ছবি</th>
                <th>মতামতদাতা</th>
                <th>মতামত</th>
                <th>রেটিং</th>
                <th>স্ট্যাটাস</th>
                <th>তারিখ</th>
                <th>একশন</th>
            </tr>
        </thead>
        <tbody id="sortable-body">
            @forelse($testimonials as $index => $testimonial)
            <tr class="table-row-hover text-center" data-id="{{ $testimonial->id }}">
                <td>
                    <input type="checkbox" class="testimonial-checkbox" value="{{ $testimonial->id }}">
                </td>
                <td>
                    <i class="fas fa-grip-vertical drag-handle text-muted" style="cursor: move;"></i>
                </td>
                <td>{{ $testimonial->id }}</td>
                <td class="text-center p-0">
                    <div class="d-flex align-items-center justify-content-center" style="height: 30px;">
                        <img src="{{ $testimonial->avatar_url }}"
                            class="img-circle img-size-32" alt="{{ $testimonial->name }}"
                            style="width: 30px; height: 30px; object-fit: cover; border-radius: 2px;"
                            onerror="this.src='https://ui-avatars.com/api/?name='+urlencode('{{ $testimonial->name ?? 'User' }}')+'&background=2F54EB&color=fff'">
                    </div>
                </td>
                <td class="text-start">
                    <div class="fw-bold">{{ $testimonial->name }}</div>
                    <!-- @if($testimonial->designation)
                        <small class="text-muted">{{ $testimonial->designation }}</small>
                    @endif -->
                </td>
                <td class="text-start">
                    <div class="testimonial-content" title="{{ $testimonial->content }}" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ $testimonial->short_content }}
                    </div>
                </td>
                <td>
                    <div class="rating-stars">
                        {!! $testimonial->rating_stars !!}
                    </div>
                </td>
                <td>
                    @php
                    $statusClass = $testimonial->is_active ? 'success' : 'danger';
                    $btnClass = $testimonial->is_active ? 'deactivate-testimonial' : 'activate-testimonial';
                    @endphp
                    <button type="button"
                        class="btn btn-sm btn-{{ $statusClass }} toggle-status {{ $btnClass }}"
                        data-id="{{ $testimonial->id }}">
                        <i class="fas {{ $testimonial->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $testimonial->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    </button>
                </td>
                <td>
                    {{ $testimonial->created_at ? $testimonial->created_at->diffForHumans() : 'N/A' }}
                </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-info view-testimonial" data-id="{{ $testimonial->id }}" title="বিস্তারিত">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="btn btn-primary" title="সম্পাদনা">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-danger delete-testimonial"
                            data-id="{{ $testimonial->id }}"
                            data-name="{{ $testimonial->name }}"
                            title="মুছে ফেলুন">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center py-5">
                    <i class="fas fa-star-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">কোনো টেস্টিমোনিয়াল পাওয়া যায়নি</p>
                    <button id="reset-filter-empty" class="btn btn-primary btn-sm mt-3">
                        <i class="fas fa-undo-alt mr-1"></i> ফিল্টার রিসেট করুন
                    </button>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if(isset($testimonials) && method_exists($testimonials, 'links'))
<div class="row mt-3">
    <div class="col-12 d-flex justify-content-center">
        {{ $testimonials->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endif