{{-- resources/views/admin/books/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr>
                <th width="60">#</th>
                <th width="100">প্রচ্ছদ</th>
                <th>বইয়ের শিরোনাম ও লেখক</th>
                <th>মূল্য (৳)</th>
                <th>পিডিএফ লিংক</th>
                <th>স্ট্যাটাস</th>
                <th>শীর্ষে</th>
                <th>অর্ডার</th>
                <th>তৈরির তারিখ</th>
                <th width="150">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($books as $index => $book)
            <tr class="align-middle">
                <td>{{ $books->firstItem() + $index }}</td>
                <td>
                    <img src="{{ $book->image_url }}" 
                         alt="{{ $book->title }}" 
                         class="img-thumbnail"
                         style="width: 60px; height: 80px; object-fit: contain; border-radius: 4px;"
                         loading="lazy">
                </td>
                <td>
                    <strong>{{ $book->title }}</strong>
                    @if($book->writer)
                        <div class="small text-muted"><i class="fas fa-pen-nib small"></i> {{ $book->writer }}</div>
                    @endif
                    <a href="/books/{{ $book->slug }}" target="_blank" class="small text-muted text-decoration-none">
                        <i class="fas fa-external-link-alt small"></i> /books/{{ $book->slug }}
                    </a>
                </td>
                <td>
                    @if($book->price !== null && $book->price !== '')
                        <div>৳{{ $book->price }}</div>
                        @if($book->old_price)
                            <del class="text-muted small">৳{{ $book->old_price }}</del>
                        @endif
                    @else
                        <span class="text-muted small">N/A</span>
                    @endif
                </td>
                <td>
                    @if($book->pdf_url)
                        <a href="{{ $book->pdf_url }}" target="_blank" class="badge bg-danger text-decoration-none">
                            <i class="fas fa-file-pdf"></i> PDF Link
                        </a>
                    @else
                        <span class="text-muted small">N/A</span>
                    @endif
                </td>
                <td>
                    <button class="btn btn-sm toggle-status {{ $book->is_active ? 'btn-success' : 'btn-danger' }}" data-id="{{ $book->id }}" title="স্ট্যাটাস পরিবর্তন করুন">
                        {{ $book->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    </button>
                </td>
                <td>
                    <div class="form-check form-switch d-inline-block">
                        <input class="form-check-input toggle-popular" type="checkbox" data-id="{{ $book->id }}" {{ $book->is_popular ? 'checked' : '' }} title="জনপ্রিয় স্ট্যাটাস পরিবর্তন করুন">
                    </div>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm update-order" data-id="{{ $book->id }}" value="{{ $book->popular_order }}" style="width: 75px; text-align: center;" min="0" title="অর্ডারিং নম্বর পরিবর্তন করুন">
                </td>
                <td>{{ $book->created_at->format('d M, Y (h:i A)') }}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-primary" title="এডিট করুন">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-danger delete-book" 
                                data-id="{{ $book->id }}" 
                                data-name="{{ $book->title }}"
                                title="ডিলিট করুন">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center py-5 text-muted">
                    <i class="fas fa-book fa-3x mb-3 text-secondary"></i>
                    <p class="mb-0">কোনো বই পাওয়া যায়নি।</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Link rendering -->
@if($books instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $books->hasPages())
    <div class="card-footer clearfix bg-white border-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="text-muted small mb-2 mb-md-0">
                মোট {{ $books->total() }} টি বইয়ের মধ্যে {{ $books->firstItem() }}-{{ $books->lastItem() }} টি দেখানো হচ্ছে
            </div>
            <div>
                {{ $books->appends(request()->all())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endif
