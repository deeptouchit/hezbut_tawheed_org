<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
        <thead>
            <tr class="text-center">
                <th>ID</th>
                <th>নাম</th>
                <th>বিষয়</th>
                <th>টাইপ</th>
                <th>স্ট্যাটাস</th>
                <th>অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($templates as $index => $template)
            <tr id="template-row-{{ $template->id }}" class="text-center">
                <td>{{ $loop->iteration }}</td>
                <td class="text-start">
                    <strong>{{ $template->name }}</strong>
                </td>
                <td class="text-start">
                    {{ Str::limit($template->subject ?? '-', 50) }}
                </td>
                <td>
                    @php
                        $typeColors = [
                            'general'   => 'secondary',
                            'order'     => 'primary',
                            'customer'  => 'success',
                            'security'  => 'danger',
                            'marketing' => 'info'
                        ];
                        $color = $typeColors[$template->type] ?? 'secondary';
                    @endphp
                    <span class="badge bg-{{ $color }}" style="padding: 4px 8px; border-radius: 4px;">
                        {{ ucfirst($template->type) }}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm toggle-status {{ $template->is_active ? 'btn-success' : 'btn-danger' }}"
                            data-id="{{ $template->id }}"
                            data-name="{{ $template->name }}"
                            style="padding: 2px 8px; border-radius: 4px;">
                        {{ $template->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    </button>
                </td>
                <td class="p-0">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-info preview-template"
                                data-id="{{ $template->id }}"
                                data-name="{{ $template->name }}"
                                title="প্রিভিউ দেখুন">
                            <i class="fas fa-eye text-white"></i>
                        </button>
                        <button class="btn btn-primary edit-template"
                                data-id="{{ $template->id }}"
                                data-name="{{ $template->name }}"
                                data-subject="{{ $template->subject }}"
                                data-body="{{ htmlspecialchars($template->body, ENT_QUOTES, 'UTF-8') }}"
                                data-type="{{ $template->type }}"
                                data-is_active="{{ $template->is_active ? 1 : 0 }}"
                                title="সম্পাদনা">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger delete-template"
                                data-id="{{ $template->id }}"
                                data-name="{{ $template->name }}"
                                title="ডিলিট">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-5">
                    <i class="fas fa-envelope-open-text fa-3x text-muted mb-3 d-block"></i>
                    <p class="text-muted mb-0">কোনো ইমেইল টেমপ্লেট পাওয়া যায়নি।</p>
                    <button type="button" class="btn btn-primary btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#addTemplateModal">
                        <i class="fas fa-plus mr-1"></i> প্রথম টেমপ্লেট তৈরি করুন
                    </button>
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

