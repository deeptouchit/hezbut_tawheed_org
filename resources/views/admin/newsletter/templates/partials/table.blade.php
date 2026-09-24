{{-- resources/views/admin/newsletter/templates/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
        <thead>
            <tr class="text-center">
                <th>ID</th>
                <th>থাম্বনেইল</th>
                <th>নাম</th>
                <th>বিষয়</th>
                <th>ডিফল্ট</th>
                <th>স্ট্যাটাস</th>
                <th>তৈরির তারিখ</th>
                <th>অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($templates as $template)
            <tr class="text-center">
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if($template->thumbnail && file_exists(public_path($template->thumbnail)))
                    <img src="{{ asset($template->thumbnail) }}" alt="{{ $template->name }}"
                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                    @else
                    <div class="bg-secondary rounded d-inline-flex align-items-center justify-content-center"
                        style="width: 50px; height: 50px;">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                    @endif
                </td>
                <td class="text-start">
                    <strong>{{ $template->name }}</strong>
                    @if($template->is_default)
                    <br><span class="badge bg-warning text-dark">ডিফল্ট</span>
                    @endif
                </td>
                <td class="text-start">{{ $template->subject }}</td>
                <td>
                    @if($template->is_default)
                    <span class="badge bg-warning text-dark">ডিফল্ট</span>
                    @else
                    <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    <button class="btn btn-sm toggle-status {{ $template->status ? 'btn-success' : 'btn-danger' }}" data-id="{{ $template->id }}">
                        {{ $template->status ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    </button>
                </td>
                <td>{{ $template->created_at->format('d M, Y') }}</td>
                <td class="p-0">
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.newsletter.templates.preview', $template->id) }}" class="btn btn-info" title="প্রিভিউ">
                            <i class="fas fa-eye text-white"></i>
                        </a>
                        <a href="{{ route('admin.newsletter.templates.edit', $template->id) }}" class="btn btn-primary" title="এডিট">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if(!$template->is_default)
                        <button class="btn btn-warning set-default" data-id="{{ $template->id }}" data-name="{{ $template->name }}" title="ডিফল্ট সেট">
                            <i class="fas fa-star"></i>
                        </button>
                        <button class="btn btn-danger delete-template" data-id="{{ $template->id }}" data-name="{{ $template->name }}" title="ডিলিট">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-5">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3 d-block"></i>
                    <p class="text-muted mb-0">কোনো টেমপ্লেট পাওয়া যায়নি।</p>
                    <a href="{{ route('admin.newsletter.templates.create') }}" class="btn btn-primary btn-sm mt-3">
                        <i class="fas fa-plus mr-1"></i> প্রথম টেমপ্লেট তৈরি করুন
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if(isset($templates) && method_exists($templates, 'links'))
<div class="row mt-3">
    <div class="col-12 d-flex justify-content-center">
        {{ $templates->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endif