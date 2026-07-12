{{-- resources/views/admin/blog/comments/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
        <thead class="">
            <tr class="text-center">
                <th>
                    <input type="checkbox" id="selectAll">
                </th>
                <th>ID</th>
                <th>ছবি</th>
                <th>মন্তব্যকারী</th>
                <th>ব্লগ শিরোনাম</th>
                <th>স্ট্যাটাস</th>
                <th>তারিখ</th>
                <th>একশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($comments as $comment)
            <tr>
                <td>
                    <input type="checkbox" class="comment-checkbox" value="{{ $comment->id }}">
                </td>
                <td>{{ $comment->id }}</td>
                <td class="text-center p-0">
                    <div class="d-flex align-items-center justify-content-center" style="height: 30px;">
                        <img src="{{ $comment->avatar }}"
                             class="img-circle img-size-32" alt="{{ $comment->name }}"
                             style="width: 30px; height: 30px; object-fit: cover; border-radius: 2px;"
                             onerror="this.src='https://ui-avatars.com/api/?name='+urlencode('{{ $comment->name ?? 'User' }}')+'&background=2F54EB&color=fff'">
                    </div>
                </td>
                <td>
                    <div class="fw-bold">{{ $comment->name ?? $comment->user?->name ?? 'গেস্ট' }}</div>

                </td>
                <td>
                    @if($comment->blog)
                        <a href="{{ route('admin.blog.posts.edit', $comment->blog_id) }}" class="blog-title-link fw-bold" target="_blank">
                            {{ Str::limit($comment->blog->title, 25) }}
                        </a>
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td class="text-center p-0">
                    @php
                        $statusBadge = $comment->is_approved ? 'success' : 'warning';
                        $btnClass = $comment->is_approved ? 'disapprove-comment' : 'approve-comment';
                    @endphp
                    <button type="button"
                            class="btn btn-sm btn-{{ $statusBadge }} toggle-status {{ $btnClass }}"
                            data-id="{{ $comment->id }}">
                        <i class="fas {{ $comment->is_approved ? 'fa-check-circle' : 'fa-clock' }}"></i>
                        {{ $comment->is_approved ? 'অ্যাপ্রুভড' : 'পেন্ডিং' }}
                    </button>
                </td>
                <td>
                    {{ $comment->created_at ? $comment->created_at->diffForHumans() : 'N/A' }}
                </td>
                <td class="text-center p-0">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-info view-comment-detail" data-id="{{ $comment->id }}" title="বিস্তারিত">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-danger delete-comment"
                                data-id="{{ $comment->id }}"
                                data-author="{{ $comment->name ?? $comment->user?->name ?? 'গেস্ট' }}"
                                data-comment="{{ Str::limit($comment->comment, 50) }}"
                                data-is-parent="{{ $comment->parent_id ? 'false' : 'true' }}"
                                data-replies-count="{{ $comment->replies_count ?? 0 }}"
                                title="ডিলিট">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-5">
                    <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">কোনো মন্তব্য পাওয়া যায়নি</p>
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
@if(isset($comments) && method_exists($comments, 'links'))
    <div class="row mt-3">
        <div class="col-12 d-flex justify-content-center">
            {{ $comments->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
