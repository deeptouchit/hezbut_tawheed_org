{{-- resources/views/admin/testimonials/partials/table.blade.php --}}

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
                <th>নাম</th>
                <th>কোম্পানি</th>
                <th>কন্টেন্ট</th>
                <th>রেটিং</th>
                <th>স্ট্যাটাস</th>
                <th width="150">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody id="sortable-body">
            @forelse($testimonials as $index => $testimonial)
            <tr class="table-row-hover" data-id="{{ $testimonial->id }}">
                <td>
                    <input type="checkbox" class="testimonial-checkbox" value="{{ $testimonial->id }}">
                </td>
                <td class="text-center">
                    <i class="fas fa-grip-vertical drag-handle"></i>
                </td>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <img src="{{ $testimonial->avatar_url }}" 
                         alt="{{ $testimonial->name }}" 
                         class="testimonial-avatar"
                         loading="lazy">
                </td>
                <td>
                    <strong>{{ $testimonial->name }}</strong>
                    @if($testimonial->designation)
                        <br><small class="text-muted">{{ $testimonial->designation }}</small>
                    @endif
                </td>
                <td>
                    @if($testimonial->company)
                        <span class="badge bg-info">{{ $testimonial->company }}</span>
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>
                    <div class="testimonial-content" title="{{ $testimonial->content }}">
                        {{ $testimonial->short_content }}
                    </div>
                </td>
                <td>
                    <div class="rating-stars">
                        {!! $testimonial->rating_stars !!}
                        <br><small class="text-muted">{{ $testimonial->rating }}/৫</small>
                    </div>
                </td>
                <td>
                    <button class="btn btn-sm toggle-status status-badge" data-id="{{ $testimonial->id }}">
                        {!! $testimonial->status_badge !!}
                    </button>
                </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-info view-testimonial" data-id="{{ $testimonial->id }}" title="বিস্তারিত দেখুন">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="btn btn-primary" title="এডিট করুন">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-danger delete-testimonial" 
                                data-id="{{ $testimonial->id }}" 
                                data-name="{{ $testimonial->name }}"
                                title="ডিলিট করুন">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center py-5">
                    <i class="fas fa-star fa-3x text-muted mb-3"></i>
                    <p class="text-muted">কোন টেস্টিমোনিয়াল পাওয়া যায়নি</p>
                    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> নতুন টেস্টিমোনিয়াল যোগ করুন
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if(isset($testimonials) && method_exists($testimonials, 'links'))
    <div class="d-flex justify-content-center mt-3">
        {{ $testimonials->appends(request()->query())->links() }}
    </div>
@endif