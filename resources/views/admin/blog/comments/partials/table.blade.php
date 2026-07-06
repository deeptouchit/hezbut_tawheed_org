{{-- resources/views/admin/blog/comments/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr>
                <th width="30">
                    <input type="checkbox" id="selectAll">
                </th>
                <th width="50">#</th>
                <th width="50">ছবি</th>
                <th>মন্তব্যকারী</th>
                <th>মন্তব্য</th>
                <th>ব্লগ</th>
                <th>স্ট্যাটাস</th>
                <th>তারিখ</th>
                <th width="160">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($comments as $index => $comment)
            <tr class="table-row-hover {{ $comment->parent_id ? 'reply-comment' : 'parent-comment' }}" data-id="{{ $comment->id }}">
                <td>
                    <input type="checkbox" class="comment-checkbox" value="{{ $comment->id }}">
                </td>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <img src="{{ $comment->avatar }}"
                         alt="{{ $comment->name }}"
                         class="comment-avatar"
                         loading="lazy"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($comment->name ?? 'User') }}&size=40&background=6366f1&color=fff&rounded=true'">
                </td>
                <td>
                    <strong>{{ $comment->name ?? $comment->user?->name ?? 'গেস্ট' }}</strong>
                    <br>
                    <small class="text-muted">
                        <i class="fas fa-envelope"></i> {{ $comment->email ?? $comment->user?->email ?? 'N/A' }}
                    </small>
                    @if($comment->user && $comment->user->id == $comment->blog?->author_id)
                        <br><span class="badge bg-primary"><i class="fas fa-pen"></i> লেখক</span>
                    @endif
                    @if($comment->parent_id)
                        <br><span class="reply-badge"><i class="fas fa-reply"></i> রিপ্লাই</span>
                    @else
                        <br><span class="badge bg-secondary"><i class="fas fa-comment"></i> প্যারেন্ট</span>
                    @endif
                </td>
                <td>
                    <div class="comment-content" title="{{ $comment->comment }}">
                        {{ Str::limit($comment->comment, 80) }}
                    </div>
                    @if($comment->parent_id && $comment->parent)
                        <small class="text-muted">
                            <i class="fas fa-reply-all"></i>
                            রিপ্লাই টু: {{ Str::limit($comment->parent->name ?? 'N/A', 20) }}
                        </small>
                    @endif
                </td>
                <td>
                    @if($comment->blog)
                        <a href="{{ route('admin.blog.posts.edit', $comment->blog_id) }}" class="blog-title-link" target="_blank">
                            {{ Str::limit($comment->blog->title, 25) }}
                        </a>
                        <br>
                        <div class="btn-group btn-group-sm mt-1">
                            <button class="btn btn-outline-info view-blog" data-blog-id="{{ $comment->blog_id }}" title="ব্লগ দেখুন">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="{{ $comment->blog->url ?? '#' }}" target="_blank" class="btn btn-outline-secondary" title="লাইভ দেখুন">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        @if($comment->is_approved)
                            <span class="badge bg-success"><i class="fas fa-check"></i> অ্যাপ্রুভড</span>
                        @else
                            <span class="badge bg-warning"><i class="fas fa-clock"></i> পেন্ডিং</span>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="comment-meta">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $comment->created_at?->format('d M, Y') }}
                    </div>
                    <div class="comment-meta">
                        <i class="fas fa-clock"></i>
                        {{ $comment->created_at?->diffForHumans() }}
                    </div>
                    @if($comment->created_at != $comment->updated_at)
                        <div class="comment-meta text-muted">
                            <i class="fas fa-edit"></i>
                            {{ $comment->updated_at?->diffForHumans() }}
                        </div>
                    @endif
                </td>
                <td>
                    <div class="action-btn-group">
                        @if(!$comment->is_approved)
                            <button class="btn btn-sm btn-success approve-comment" data-id="{{ $comment->id }}" title="অ্যাপ্রুভ">
                                <i class="fas fa-check"></i>
                            </button>
                        @else
                            <button class="btn btn-sm btn-warning disapprove-comment" data-id="{{ $comment->id }}" title="ডিসঅ্যাপ্রুভ">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif


                        <button class="btn btn-sm btn-danger delete-comment"
                                data-id="{{ $comment->id }}"
                                data-author="{{ $comment->name ?? $comment->user?->name ?? 'গেস্ট' }}"
                                data-comment="{{ Str::limit($comment->comment, 50) }}"
                                data-is-parent="{{ $comment->parent_id ? 'false' : 'true' }}"
                                data-replies-count="{{ $comment->replies_count ?? 0 }}"
                                title="ডিলিট">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="btn btn-sm btn-info view-comment-detail" data-id="{{ $comment->id }}" title="বিস্তারিত">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-5">
                    <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted">কোন মন্তব্য পাওয়া যায়নি</p>
                    <button id="reset-filter-empty" class="btn btn-primary btn-sm">
                        <i class="fas fa-undo-alt"></i> ফিল্টার রিসেট করুন
                    </button>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if(isset($comments) && method_exists($comments, 'links'))
    <div class="d-flex justify-content-center mt-3">
        {{ $comments->appends(request()->query())->links() }}
    </div>
@endif

@push('scripts')
<script>
$(document).ready(function() {
    // Reset filter from empty state
    $('#reset-filter-empty').on('click', function() {
        $('#search-input').val('');
        $('#blog-filter').val('');
        $('#status-filter').val('');
        $('#type-filter').val('');
        $('#per-page-filter').val('20');
        loadComments();
    });
});
</script>
@endpush
