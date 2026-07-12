<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap align-middle" style="width: 100%;">
        <thead class="">
            <tr class="text-center">
                <th class="">#</th>
                <th class="">নাম</th>
                <th class="">বিষয়</th>
                <th class="">টাইপ</th>
                <th class="">স্ট্যাটাস</th>
                <th class="">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($templates as $index => $template)
            <tr id="template-row-{{ $template->id }}" class="template-row">
                <td class="text-center align-middle">
                    {{ $templates->firstItem() + $index }}
                </td>
                <td class="text-start">
                    <strong>{{ $template->name }}</strong>
                </td>
                <td class="text-start">
                    {{ Str::limit($template->subject ?? '-', 50) }}
                </td>
                <td class="text-center">
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
                    <span class="badge bg-{{ $color }}" style="width: 80px;">
                        {{ ucfirst($template->type) }}
                    </span>
                </td>
                <td class="text-center p-0">
                    <button class="btn btn-sm toggle-status w-100 {{ $template->is_active ? 'btn-success' : 'btn-danger' }}"
                            data-id="{{ $template->id }}"
                            data-name="{{ $template->name }}">
                        <i class="fas {{ $template->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $template->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    </button>
                </td>
                <td class="text-center p-0">
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-info preview-template"
                                data-id="{{ $template->id }}"
                                data-name="{{ $template->name }}"
                                title="প্রিভিউ দেখুন">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-warning edit-template"
                                data-id="{{ $template->id }}"
                                data-name="{{ $template->name }}"
                                data-subject="{{ $template->subject }}"
                                data-body="{{ htmlspecialchars($template->body, ENT_QUOTES, 'UTF-8') }}"
                                data-type="{{ $template->type }}"
                                data-is_active="{{ $template->is_active ? 1 : 0 }}"
                                title="এডিট করুন">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger delete-template"
                                data-id="{{ $template->id }}"
                                data-name="{{ $template->name }}"
                                title="ডিলিট করুন">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-envelope-open-text fa-3x mb-3 d-block text-muted"></i>
                        <h5 class="text-muted">কোনো ইমেইল টেমপ্লেট পাওয়া যায়নি</h5>
                        <p class="text-muted mb-3">আপনার প্রথম ইমেইল টেমপ্লেট তৈরি করুন</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTemplateModal">
                            <i class="fas fa-plus"></i> নতুন টেমপ্লেট তৈরি করুন
                        </button>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($templates->isNotEmpty() && method_exists($templates, 'links'))
<div class="row mt-3 align-items-center">
    <div class="col-md-6">
        <div class="text-muted small">
            <i class="fas fa-chart-line"></i>
            মোট {{ $templates->total() }} টি টেমপ্লেটের মধ্যে
            {{ $templates->firstItem() }} - {{ $templates->lastItem() }} দেখানো হচ্ছে
        </div>
    </div>
    <div class="col-md-6">
        <div class="d-flex justify-content-end">
            {{ $templates->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endif

