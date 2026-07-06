<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th width="50">ID</th>
                <th>থাম্বনেইল</th>
                <th>নাম</th>
                <th>বিষয়</th>
                <th>ডিফল্ট</th>
                <th>স্ট্যাটাস</th>
                <th>তৈরির তারিখ</th>
                <th width="150">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($templates as $template)
            <tr>
                <td class="text-center">{{ $template->id }}</td>
                <td class="text-center">
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
                <td>
                    <strong>{{ $template->name }}</strong>
                    @if($template->is_default)
                        <br><span class="badge bg-warning text-dark">ডিফল্ট</span>
                    @endif
                </td>
                <td>{{ $template->subject }}</td>
                <td class="text-center">
                    @if($template->is_default)
                        <span class="badge bg-warning text-dark">ডিফল্ট</span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td class="text-center">
                    <button class="btn btn-sm toggle-status {{ $template->status ? 'btn-success' : 'btn-danger' }}" data-id="{{ $template->id }}">
                        {{ $template->status ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    </button>
                </td>
                <td>{{ $template->created_at->format('d M, Y') }}<\/td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.newsletter.templates.preview', $template->id) }}" class="btn btn-info" title="প্রিভিউ">
                            <i class="fas fa-eye"></i>
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
                <td colspan="8" class="text-center py-4">
                    <i class="fas fa-file-alt fa-2x mb-2 d-block text-muted"></i>
                    <p class="text-muted">কোনো টেমপ্লেট পাওয়া যায়নি।</p>
                    <a href="{{ route('admin.newsletter.templates.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> প্রথম টেমপ্লেট তৈরি করুন
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($templates->isNotEmpty())
<div class="row mt-3">
    <div class="col-md-12">
        <div class="float-right">
            {{ $templates->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endif
