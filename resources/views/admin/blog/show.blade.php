{{-- resources/views/admin/blog/show.blade.php --}}

@extends('admin.layouts.master')

@section('page-title', 'ব্লগ পোস্ট বিস্তারিত - ' . $blog->title)

@push('styles')
<style>
    .blog-detail-image {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }
    .blog-meta-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid #e9ecef;
    }
    .blog-meta-card .meta-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
    }
    .blog-meta-card .meta-item:last-child {
        border-bottom: none;
    }
    .blog-meta-card .meta-label {
        font-weight: 600;
        color: #495057;
    }
    .blog-meta-card .meta-value {
        color: #212529;
    }
    .blog-content {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        line-height: 1.8;
        font-size: 15px;
    }
    .blog-content h1, .blog-content h2, .blog-content h3 {
        margin-top: 20px;
        margin-bottom: 10px;
    }
    .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 15px 0;
    }
    .blog-content blockquote {
        border-left: 4px solid #0d6efd;
        padding: 10px 20px;
        margin: 15px 0;
        background: #f8f9fa;
        border-radius: 0 8px 8px 0;
    }
    .blog-content ul, .blog-content ol {
        padding-left: 20px;
    }
    .blog-content li {
        margin-bottom: 5px;
    }
    .blog-tag {
        display: inline-block;
        background: #e9ecef;
        color: #495057;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        margin: 2px;
        transition: all 0.3s ease;
    }
    .blog-tag:hover {
        background: #0d6efd;
        color: white;
    }
    .status-badge {
        font-size: 14px;
        padding: 5px 15px;
    }
    .action-btn-group {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .action-btn-group .btn {
        padding: 8px 16px;
        font-size: 13px;
    }
    .comment-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        border-left: 3px solid #0d6efd;
    }
    .comment-card .comment-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .comment-card .comment-author {
        font-weight: 600;
    }
    .comment-card .comment-date {
        font-size: 12px;
        color: #6c757d;
    }
    .comment-card .comment-text {
        color: #495057;
        line-height: 1.6;
    }
    .comment-card .comment-actions {
        margin-top: 8px;
        display: flex;
        gap: 5px;
    }
    .reply-comment {
        margin-left: 40px;
        border-left-color: #28a745;
    }
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }
    .empty-state i {
        font-size: 48px;
        color: #dee2e6;
        margin-bottom: 15px;
    }
    .empty-state p {
        color: #6c757d;
        margin-bottom: 0;
    }
    .seo-preview {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        border: 1px solid #e9ecef;
    }
    .seo-preview .seo-title {
        color: #1a0dab;
        font-size: 18px;
        font-weight: 500;
        text-decoration: none;
    }
    .seo-preview .seo-link {
        color: #006621;
        font-size: 14px;
        margin-top: 2px;
    }
    .seo-preview .seo-description {
        color: #545454;
        font-size: 13px;
        margin-top: 2px;
        line-height: 1.4;
    }
    .blog-stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
    }
    .blog-stats-card .stat-number {
        font-size: 32px;
        font-weight: 700;
    }
    .blog-stats-card .stat-label {
        font-size: 14px;
        opacity: 0.9;
        margin-top: 5px;
    }
    .blog-stats-card.green {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    .blog-stats-card.orange {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .blog-stats-card.blue {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-eye me-2"></i> ব্লগ পোস্ট বিস্তারিত
                <span class="badge bg-primary ms-2">#{{ $blog->id }}</span>
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> ফিরে যান
                    </a>
                    <a href="{{ route('admin.blog.posts.edit', $blog->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> এডিট
                    </a>
                    <a href="{{ $blog->url }}" target="_blank" class="btn btn-info btn-sm">
                        <i class="fas fa-external-link-alt"></i> লাইভ দেখুন
                    </a>
                    <button class="btn btn-success btn-sm toggle-status" data-id="{{ $blog->id }}">
                        <i class="fas fa-sync-alt"></i>
                        {{ $blog->status ? 'খসড়া করুন' : 'প্রকাশ করুন' }}
                    </button>
                    <button class="btn btn-danger btn-sm delete-blog" data-id="{{ $blog->id }}" data-title="{{ $blog->title }}">
                        <i class="fas fa-trash"></i> ডিলিট
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Left Column - Blog Content -->
                <div class="col-md-8">
                    <!-- Featured Image -->
                    @if($blog->featured_image)
                        <div class="mb-4">
                            <img src="{{ $blog->featured_image_url }}"
                                 alt="{{ $blog->title }}"
                                 class="blog-detail-image">
                        </div>
                    @endif

                    <!-- Title -->
                    <h1 class="mb-3">{{ $blog->title }}</h1>

                    <!-- Blog Meta -->
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <span>
                            <i class="fas fa-user text-primary"></i>
                            <strong>{{ $blog->author?->name ?? 'N/A' }}</strong>
                        </span>
                        <span>
                            <i class="fas fa-calendar-alt text-primary"></i>
                            {{ $blog->published_at?->format('F j, Y') ?? 'N/A' }}
                        </span>
                        <span>
                            <i class="fas fa-clock text-primary"></i>
                            {{ $blog->reading_time }}
                        </span>
                        <span>
                            <i class="fas fa-eye text-primary"></i>
                            {{ number_format($blog->views ?? 0) }} ভিউ
                        </span>
                        <span>
                            <i class="fas fa-comments text-primary"></i>
                            {{ $blog->approvedComments()->count() ?? 0 }} মন্তব্য
                        </span>
                        @if($blog->category)
                            <span>
                                <i class="fas fa-tag text-primary"></i>
                                <a href="{{ route('admin.blog.categories.edit', $blog->category_id) }}" class="text-decoration-none">
                                    {{ $blog->category->name }}
                                </a>
                            </span>
                        @endif
                        <span>
                            <i class="fas fa-info-circle text-primary"></i>
                            {!! $blog->status ? '<span class="badge bg-success">প্রকাশিত</span>' : '<span class="badge bg-warning">খসড়া</span>' !!}
                        </span>
                    </div>

                    <!-- Short Description -->
                    @if($blog->short_description)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            {{ $blog->short_description }}
                        </div>
                    @endif

                    <!-- Content -->
                    <div class="blog-content">
                        {!! $blog->content !!}
                    </div>

                    <!-- Tags -->
                    @if($blog->tags_array)
                        <div class="mt-4">
                            <h5><i class="fas fa-tags"></i> ট্যাগ:</h5>
                            <div>
                                @foreach($blog->tags_array as $tag)
                                    <span class="blog-tag">#{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- SEO Preview -->
                    @if($blog->meta_title || $blog->meta_description)
                        <div class="mt-4">
                            <h5><i class="fas fa-search"></i> SEO প্রিভিউ:</h5>
                            <div class="seo-preview">
                                <div class="seo-title">{{ $blog->meta_title ?? $blog->title }}</div>
                                <div class="seo-link">{{ url('/blog') }}/{{ $blog->slug }}</div>
                                <div class="seo-description">{{ $blog->meta_description ?? Str::limit(strip_tags($blog->content), 160) }}</div>
                            </div>
                        </div>
                    @endif

                    <!-- Comments Section -->
                    <div class="mt-5">
                        <h4>
                            <i class="fas fa-comments"></i> মন্তব্য
                            <span class="badge bg-primary">{{ $blog->approvedComments()->count() }}</span>
                        </h4>
                        <hr>

                        @if($blog->approvedComments()->count() > 0)
                            @foreach($blog->approvedComments as $comment)
                                <div class="comment-card">
                                    <div class="comment-meta">
                                        <div>
                                            <span class="comment-author">
                                                <i class="fas fa-user-circle"></i>
                                                {{ $comment->name ?? $comment->user?->name ?? 'গেস্ট' }}
                                            </span>
                                            @if($comment->user && $comment->user->id == $blog->author_id)
                                                <span class="badge bg-primary">লেখক</span>
                                            @endif
                                        </div>
                                        <span class="comment-date">
                                            <i class="far fa-clock"></i>
                                            {{ $comment->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <div class="comment-text">
                                        {{ $comment->comment }}
                                    </div>
                                    <div class="comment-actions">
                                        @if(!$comment->is_approved)
                                            <button class="btn btn-sm btn-success approve-comment" data-id="{{ $comment->id }}">
                                                <i class="fas fa-check"></i> অ্যাপ্রুভ
                                            </button>
                                        @endif
                                        <button class="btn btn-sm btn-danger delete-comment" data-id="{{ $comment->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Replies -->
                                @if($comment->approvedReplies()->count() > 0)
                                    @foreach($comment->approvedReplies as $reply)
                                        <div class="comment-card reply-comment">
                                            <div class="comment-meta">
                                                <div>
                                                    <span class="comment-author">
                                                        <i class="fas fa-user-circle"></i>
                                                        {{ $reply->name ?? $reply->user?->name ?? 'গেস্ট' }}
                                                    </span>
                                                    @if($reply->user && $reply->user->id == $blog->author_id)
                                                        <span class="badge bg-primary">লেখক</span>
                                                    @endif
                                                    <span class="badge bg-secondary">রিপ্লাই</span>
                                                </div>
                                                <span class="comment-date">
                                                    <i class="far fa-clock"></i>
                                                    {{ $reply->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <div class="comment-text">
                                                {{ $reply->comment }}
                                            </div>
                                            <div class="comment-actions">
                                                @if(!$reply->is_approved)
                                                    <button class="btn btn-sm btn-success approve-comment" data-id="{{ $reply->id }}">
                                                        <i class="fas fa-check"></i> অ্যাপ্রুভ
                                                    </button>
                                                @endif
                                                <button class="btn btn-sm btn-danger delete-comment" data-id="{{ $reply->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="fas fa-comment-slash"></i>
                                <p>এই পোস্টে এখনো কোন মন্তব্য নেই</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column - Sidebar -->
                <div class="col-md-4">
                    <!-- Stats -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="blog-stats-card">
                                <div class="stat-number">{{ number_format($blog->views ?? 0) }}</div>
                                <div class="stat-label"><i class="fas fa-eye"></i> ভিউ</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="blog-stats-card green">
                                <div class="stat-number">{{ $blog->approvedComments()->count() ?? 0 }}</div>
                                <div class="stat-label"><i class="fas fa-comments"></i> মন্তব্য</div>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Meta -->
                    <div class="blog-meta-card">
                        <h6><i class="fas fa-info-circle"></i> ব্লগ তথ্য</h6>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-id"></i> আইডি</span>
                            <span class="meta-value">#{{ $blog->id }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-tag"></i> স্লাগ</span>
                            <span class="meta-value">{{ $blog->slug }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-clock"></i> তৈরি</span>
                            <span class="meta-value">{{ $blog->created_at?->format('d M, Y h:i A') }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-edit"></i> আপডেট</span>
                            <span class="meta-value">{{ $blog->updated_at?->format('d M, Y h:i A') }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-calendar-check"></i> প্রকাশ</span>
                            <span class="meta-value">{{ $blog->published_at?->format('d M, Y h:i A') ?? 'N/A' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-sort"></i> সর্ট অর্ডার</span>
                            <span class="meta-value">{{ $blog->sort_order ?? 0 }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-user"></i> লেখক</span>
                            <span class="meta-value">{{ $blog->author?->name ?? 'N/A' }}</span>
                        </div>
                        @if($blog->category)
                            <div class="meta-item">
                                <span class="meta-label"><i class="fas fa-folder"></i> ক্যাটাগরি</span>
                                <span class="meta-value">
                                    <a href="{{ route('admin.blog.categories.edit', $blog->category_id) }}" class="text-decoration-none">
                                        {{ $blog->category->name }}
                                    </a>
                                </span>
                            </div>
                        @endif
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-clock"></i> পড়ার সময়</span>
                            <span class="meta-value">{{ $blog->reading_time }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-flag"></i> স্ট্যাটাস</span>
                            <span class="meta-value">
                                {!! $blog->status ? '<span class="badge bg-success">প্রকাশিত</span>' : '<span class="badge bg-warning">খসড়া</span>' !!}
                            </span>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="blog-meta-card">
                        <h6><i class="fas fa-tools"></i> দ্রুত অ্যাকশন</h6>
                        <div class="action-btn-group">
                            <a href="{{ route('admin.blog.posts.edit', $blog->id) }}" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-edit"></i> এডিট করুন
                            </a>
                            <a href="{{ $blog->url }}" target="_blank" class="btn btn-info btn-sm w-100">
                                <i class="fas fa-external-link-alt"></i> লাইভ দেখুন
                            </a>
                            <button class="btn btn-success btn-sm w-100 toggle-status" data-id="{{ $blog->id }}">
                                <i class="fas fa-sync-alt"></i>
                                {{ $blog->status ? 'খসড়া করুন' : 'প্রকাশ করুন' }}
                            </button>
                            <button class="btn btn-danger btn-sm w-100 delete-blog" data-id="{{ $blog->id }}" data-title="{{ $blog->title }}">
                                <i class="fas fa-trash"></i> ডিলিট করুন
                            </button>
                        </div>
                    </div>

                    <!-- Share -->
                    <div class="blog-meta-card">
                        <h6><i class="fas fa-share-alt"></i> শেয়ার করুন</h6>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $blog->share_url }}" target="_blank" class="btn btn-primary btn-sm">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ $blog->share_url }}&text={{ $blog->share_text }}" target="_blank" class="btn btn-dark btn-sm">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $blog->share_url }}" target="_blank" class="btn btn-info btn-sm">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="https://api.whatsapp.com/send?text={{ $blog->share_text }}%20{{ $blog->share_url }}" target="_blank" class="btn btn-success btn-sm">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <button class="btn btn-secondary btn-sm copy-link" data-url="{{ $blog->url }}">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> নিশ্চিত করুন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>আপনি কি <strong id="delete-title"></strong> শিরোনামের ব্লগ পোস্টটি ডিলিট করতে চান?</p>
                <p class="text-danger"><small>⚠️ এই কাজটি অপরিবর্তনীয়! ডিলিট করার পর আর ফিরিয়ে আনা যাবে না।</small></p>
                @if($blog->allComments()->count() > 0)
                    <p class="text-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        এই পোস্টে <strong>{{ $blog->allComments()->count() }}</strong> টি মন্তব্য আছে। প্রথমে মন্তব্য গুলো মুছুন!
                    </p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">
                    <i class="fas fa-trash"></i> ডিলিট
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let deleteId = null;

    // ============================================
    // Toggle Status
    // ============================================
    $('.toggle-status').click(function() {
        var id = $(this).data('id');
        var btn = $(this);

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: '{{ url("admin/blog/posts") }}/' + id + '/toggle-status',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error(response.message);
                    btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i>');
                }
            },
            error: function() {
                toastr.error('স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে');
                btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i>');
            }
        });
    });

    // ============================================
    // Delete Blog
    // ============================================
    $('.delete-blog').click(function() {
        deleteId = $(this).data('id');
        var title = $(this).data('title');
        var commentCount = {{ $blog->allComments()->count() }};

        $('#delete-title').text(title);

        if (commentCount > 0) {
            $('.text-warning').show();
            $('#confirm-delete').prop('disabled', true);
        } else {
            $('.text-warning').hide();
            $('#confirm-delete').prop('disabled', false);
        }

        $('#deleteModal').modal('show');
    });

    $('#confirm-delete').click(function() {
        if (!deleteId) return;
        if ($(this).prop('disabled')) return;

        $.ajax({
            url: '{{ url("admin/blog/posts") }}/' + deleteId,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                $('#deleteModal').modal('hide');
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.href = '{{ route("admin.blog.posts.index") }}';
                    }, 1500);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                var message = 'ব্লগ পোস্ট ডিলিট করতে ব্যর্থ হয়েছে';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
                $('#deleteModal').modal('hide');
            }
        });
    });

    // ============================================
    // Approve Comment
    // ============================================
    $('.approve-comment').click(function() {
        var id = $(this).data('id');
        var btn = $(this);

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: '{{ url("admin/blog/comments") }}/' + id + '/approve',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                } else {
                    toastr.error(response.message);
                    btn.prop('disabled', false).html('<i class="fas fa-check"></i> অ্যাপ্রুভ');
                }
            },
            error: function() {
                toastr.error('মন্তব্য অ্যাপ্রুভ করতে ব্যর্থ হয়েছে');
                btn.prop('disabled', false).html('<i class="fas fa-check"></i> অ্যাপ্রুভ');
            }
        });
    });

    // ============================================
    // Delete Comment
    // ============================================
    $('.delete-comment').click(function() {
        var id = $(this).data('id');

        Swal.fire({
            title: 'আপনি কি নিশ্চিত?',
            text: 'এই মন্তব্যটি ডিলিট করতে চান?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'হ্যাঁ, ডিলিট',
            cancelButtonText: 'বাতিল'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin/blog/comments") }}/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            location.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('মন্তব্য ডিলিট করতে ব্যর্থ হয়েছে');
                    }
                });
            }
        });
    });

    // ============================================
    // Copy Link
    // ============================================
    $('.copy-link').click(function() {
        var url = $(this).data('url');

        navigator.clipboard.writeText(url).then(function() {
            toastr.success('লিংক কপি করা হয়েছে!');
        }).catch(function() {
            // Fallback
            var $temp = $('<input>');
            $('body').append($temp);
            $temp.val(url).select();
            document.execCommand('copy');
            $temp.remove();
            toastr.success('লিংক কপি করা হয়েছে!');
        });
    });

    // ============================================
    // Toastr Messages
    // ============================================
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif
});
</script>
@endpush
