@extends('admin.layouts.master')

@section('page-title', 'মন্তব্য বিস্তারিত')

@push('styles')
<style>
    .comment-avatar-large {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .comment-avatar-large:hover {
        transform: scale(1.05);
        border-color: #0d6efd;
    }
    .comment-content-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #0d6efd;
        line-height: 1.8;
        white-space: pre-wrap;
    }
    .comment-meta-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
    }
    .comment-meta-item:last-child {
        border-bottom: none;
    }
    .comment-meta-item .label {
        font-weight: 600;
        color: #495057;
    }
    .comment-meta-item .value {
        color: #212529;
    }
    .reply-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        border-left: 3px solid #28a745;
        transition: all 0.3s ease;
    }
    .reply-card:hover {
        background: #e9ecef;
    }
    .reply-avatar {
        width: 35px;
        height: 35px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #e9ecef;
    }
    .parent-comment-box {
        background: #e9ecef;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #ffc107;
    }
    .status-badge-large {
        font-size: 16px;
        padding: 8px 20px;
        border-radius: 25px;
    }
    .action-btn-group-large {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .action-btn-group-large .btn {
        padding: 10px 20px;
        font-size: 14px;
    }
    .info-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
    }
    .info-card .info-item {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        border-bottom: 1px solid #e9ecef;
    }
    .info-card .info-item:last-child {
        border-bottom: none;
    }
    .info-card .info-item .label {
        font-weight: 600;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-comment me-2"></i> মন্তব্য বিস্তারিত
                <span class="badge bg-primary ms-2">#{{ $comment->id }}</span>
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.blog.comments.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> ফিরে যান
                    </a>
                    @if($comment->blog)
                        <a href="{{ route('admin.blog.posts.edit', $comment->blog_id) }}" class="btn btn-info btn-sm" target="_blank">
                            <i class="fas fa-edit"></i> ব্লগ এডিট
                        </a>
                        <a href="{{ $comment->blog->url ?? '#' }}" class="btn btn-success btn-sm" target="_blank">
                            <i class="fas fa-external-link-alt"></i> লাইভ দেখুন
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Left Column - Comment Details -->
                <div class="col-md-8">
                    <!-- Comment Card -->
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-comment me-2"></i> মন্তব্য
                                </h5>
                                <span>
                                    @if($comment->is_approved)
                                        <span class="badge bg-success status-badge-large">
                                            <i class="fas fa-check"></i> অ্যাপ্রুভড
                                        </span>
                                    @else
                                        <span class="badge bg-warning status-badge-large">
                                            <i class="fas fa-clock"></i> পেন্ডিং
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Commenter Info -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $comment->avatar }}"
                                             alt="{{ $comment->name }}"
                                             class="comment-avatar-large me-3">
                                        <div>
                                            <h4 class="mb-0">{{ $comment->name ?? $comment->user?->name ?? 'গেস্ট' }}</h4>
                                            <small class="text-muted">
                                                <i class="fas fa-envelope"></i> {{ $comment->email ?? $comment->user?->email ?? 'N/A' }}
                                            </small>
                                            <br>
                                            @if($comment->user && $comment->user->id == $comment->blog?->author_id)
                                                <span class="badge bg-primary"><i class="fas fa-pen"></i> লেখক</span>
                                            @endif
                                            @if($comment->parent_id)
                                                <span class="badge bg-success"><i class="fas fa-reply"></i> রিপ্লাই</span>
                                            @else
                                                <span class="badge bg-secondary"><i class="fas fa-comment"></i> প্যারেন্ট</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-md-end mt-3 mt-md-0">
                                        <div class="comment-meta">
                                            <i class="fas fa-calendar-alt"></i>
                                            <strong>তৈরি:</strong> {{ $comment->created_at?->format('d M, Y h:i A') }}
                                        </div>
                                        <div class="comment-meta">
                                            <i class="fas fa-clock"></i>
                                            <strong>আগে:</strong> {{ $comment->created_at?->diffForHumans() }}
                                        </div>
                                        @if($comment->created_at != $comment->updated_at)
                                            <div class="comment-meta text-muted">
                                                <i class="fas fa-edit"></i>
                                                <strong>শেষ আপডেট:</strong> {{ $comment->updated_at?->diffForHumans() }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Comment Content -->
                            <div class="border-top pt-3">
                                <h6><i class="fas fa-quote-left text-primary"></i> মন্তব্য</h6>
                                <div class="comment-content-box">
                                    {{ $comment->comment }}
                                </div>
                            </div>

                            <!-- Parent Comment (if reply) -->
                            @if($comment->parent_id && $comment->parent)
                                <div class="border-top pt-3 mt-3">
                                    <h6><i class="fas fa-reply-all text-success"></i> প্যারেন্ট মন্তব্য</h6>
                                    <div class="parent-comment-box">
                                        <div class="d-flex align-items-start">
                                            <img src="{{ $comment->parent->avatar }}"
                                                 alt="{{ $comment->parent->name }}"
                                                 class="reply-avatar me-2">
                                            <div>
                                                <strong>{{ $comment->parent->name ?? $comment->parent->user?->name ?? 'গেস্ট' }}</strong>
                                                <span class="text-muted ms-2" style="font-size: 12px;">
                                                    {{ $comment->parent->created_at?->diffForHumans() }}
                                                </span>
                                                <p class="mb-0 mt-1">{{ $comment->parent->comment }}</p>
                                                <a href="{{ route('admin.blog.comments.show', $comment->parent_id) }}" class="btn btn-sm btn-outline-primary mt-1">
                                                    <i class="fas fa-eye"></i> সম্পূর্ণ দেখুন
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Replies (if parent comment) -->
                            @if(!$comment->parent_id && $comment->replies_count > 0)
                                <div class="border-top pt-3 mt-3">
                                    <h6>
                                        <i class="fas fa-reply text-success"></i>
                                        রিপ্লাই ({{ $comment->replies_count }})
                                    </h6>
                                    <div class="mt-2">
                                        @forelse($comment->approvedReplies as $reply)
                                            <div class="reply-card">
                                                <div class="d-flex align-items-start">
                                                    <img src="{{ $reply->avatar }}"
                                                         alt="{{ $reply->name }}"
                                                         class="reply-avatar me-2">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <strong>{{ $reply->name ?? $reply->user?->name ?? 'গেস্ট' }}</strong>
                                                                @if($reply->user && $reply->user->id == $comment->blog?->author_id)
                                                                    <span class="badge bg-primary ms-1" style="font-size: 10px;">লেখক</span>
                                                                @endif
                                                                <span class="text-muted ms-2" style="font-size: 12px;">
                                                                    {{ $reply->created_at?->diffForHumans() }}
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <a href="{{ route('admin.blog.comments.show', $reply->id) }}" class="btn btn-sm btn-outline-info">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <p class="mb-0 mt-1">{{ $reply->comment }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-muted text-center">কোন রিপ্লাই নেই</p>
                                        @endforelse
                                    </div>
                                </div>
                            @endif

                            <!-- Blog Info -->
                            @if($comment->blog)
                                <div class="border-top pt-3 mt-3">
                                    <h6><i class="fas fa-blog text-primary"></i> ব্লগ তথ্য</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>শিরোনাম:</strong>
                                            <a href="{{ route('admin.blog.posts.edit', $comment->blog_id) }}" target="_blank">
                                                {{ $comment->blog->title }}
                                            </a>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>লেখক:</strong>
                                            {{ $comment->blog->author?->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <strong>ক্যাটাগরি:</strong>
                                            {{ $comment->blog->category?->name ?? 'N/A' }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>প্রকাশের তারিখ:</strong>
                                            {{ $comment->blog->published_at?->format('d M, Y h:i A') ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.blog.posts.edit', $comment->blog_id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i> ব্লগ এডিট
                                                </a>
                                                <a href="{{ $comment->blog->url ?? '#' }}" target="_blank" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-external-link-alt"></i> লাইভ দেখুন
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column - Actions & Info -->
                <div class="col-md-4">
                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-tools me-2"></i> অ্যাকশন</h5>
                        </div>
                        <div class="card-body">
                            <div class="action-btn-group-large">
                                @if(!$comment->is_approved)
                                    <button class="btn btn-success w-100 approve-comment" data-id="{{ $comment->id }}">
                                        <i class="fas fa-check"></i> অ্যাপ্রুভ করুন
                                    </button>
                                @else
                                    <button class="btn btn-warning w-100 disapprove-comment" data-id="{{ $comment->id }}">
                                        <i class="fas fa-times"></i> ডিসঅ্যাপ্রুভ করুন
                                    </button>
                                @endif

                                <button class="btn btn-danger w-100 delete-comment"
                                        data-id="{{ $comment->id }}"
                                        data-author="{{ $comment->name ?? $comment->user?->name ?? 'গেস্ট' }}">
                                    <i class="fas fa-trash"></i> ডিলিট করুন
                                </button>

                                @if(!$comment->parent_id)
                                    <button class="btn btn-success w-100 reply-comment" data-id="{{ $comment->id }}" data-toggle="modal" data-target="#replyModal">
                                        <i class="fas fa-reply"></i> রিপ্লাই দিন
                                    </button>
                                @endif

                                <a href="{{ route('admin.blog.comments.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-arrow-left"></i> ফিরে যান
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Comment Info -->
                    <div class="card mt-3">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i> তথ্য</h5>
                        </div>
                        <div class="card-body">
                            <div class="info-card">
                                <div class="info-item">
                                    <span class="label"><i class="fas fa-id"></i> আইডি</span>
                                    <span>#{{ $comment->id }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label"><i class="fas fa-user"></i> মন্তব্যকারী</span>
                                    <span>{{ $comment->name ?? $comment->user?->name ?? 'গেস্ট' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label"><i class="fas fa-envelope"></i> ইমেইল</span>
                                    <span>{{ $comment->email ?? $comment->user?->email ?? 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label"><i class="fas fa-check-circle"></i> স্ট্যাটাস</span>
                                    <span>
                                        @if($comment->is_approved)
                                            <span class="badge bg-success">অ্যাপ্রুভড</span>
                                        @else
                                            <span class="badge bg-warning">পেন্ডিং</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="label"><i class="fas fa-reply"></i> টাইপ</span>
                                    <span>
                                        @if($comment->parent_id)
                                            <span class="badge bg-success">রিপ্লাই</span>
                                        @else
                                            <span class="badge bg-secondary">প্যারেন্ট</span>
                                        @endif
                                    </span>
                                </div>
                                @if($comment->parent_id && $comment->parent)
                                    <div class="info-item">
                                        <span class="label"><i class="fas fa-reply-all"></i> প্যারেন্ট আইডি</span>
                                        <span>
                                            <a href="{{ route('admin.blog.comments.show', $comment->parent_id) }}">
                                                #{{ $comment->parent_id }}
                                            </a>
                                        </span>
                                    </div>
                                @endif
                                <div class="info-item">
                                    <span class="label"><i class="fas fa-calendar"></i> তৈরি</span>
                                    <span>{{ $comment->created_at?->format('d M, Y h:i A') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label"><i class="fas fa-edit"></i> আপডেট</span>
                                    <span>{{ $comment->updated_at?->format('d M, Y h:i A') }}</span>
                                </div>
                                @if($comment->ip_address)
                                    <div class="info-item">
                                        <span class="label"><i class="fas fa-network-wired"></i> IP</span>
                                        <span>{{ $comment->ip_address }}</span>
                                    </div>
                                @endif
                                @if($comment->user_agent)
                                    <div class="info-item">
                                        <span class="label"><i class="fas fa-laptop"></i> ব্রাউজার</span>
                                        <span><small>{{ Str::limit($comment->user_agent, 30) }}</small></span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Replies Count -->
                    @if(!$comment->parent_id)
                        <div class="card mt-3">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-reply me-2"></i> রিপ্লাই ({{ $comment->replies_count }})
                                </h5>
                            </div>
                            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                @if($comment->replies_count > 0)
                                    @foreach($comment->approvedReplies as $reply)
                                        <div class="d-flex align-items-start mb-2 p-2 border-bottom">
                                            <img src="{{ $reply->avatar }}"
                                                 alt="{{ $reply->name }}"
                                                 class="reply-avatar me-2">
                                            <div class="flex-grow-1">
                                                <strong>{{ $reply->name ?? $reply->user?->name ?? 'গেস্ট' }}</strong>
                                                <br>
                                                <small class="text-muted" style="font-size: 11px;">
                                                    {{ $reply->created_at?->diffForHumans() }}
                                                </small>
                                                <p class="mb-0" style="font-size: 13px;">{{ Str::limit($reply->comment, 50) }}</p>
                                            </div>
                                            <a href="{{ route('admin.blog.comments.show', $reply->id) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted text-center">কোন রিপ্লাই নেই</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-reply"></i> রিপ্লাই দিন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="replyForm">
                    @csrf
                    <input type="hidden" id="reply_comment_id" value="{{ $comment->id }}">
                    <div class="mb-3">
                        <label>আপনার নাম <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="reply_name" value="{{ auth()->user()?->name ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label>ইমেইল <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="reply_email" value="{{ auth()->user()?->email ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label>রিপ্লাই <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reply_comment" rows="4" placeholder="আপনার রিপ্লাই লিখুন..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-success" id="submitReply">
                    <i class="fas fa-reply"></i> রিপ্লাই দিন
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // ============================================
    // Approve Comment
    // ============================================
    $('.approve-comment').on('click', function() {
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
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error(response.message);
                    btn.prop('disabled', false).html('<i class="fas fa-check"></i> অ্যাপ্রুভ করুন');
                }
            },
            error: function() {
                toastr.error('মন্তব্য অ্যাপ্রুভ করতে ব্যর্থ হয়েছে');
                btn.prop('disabled', false).html('<i class="fas fa-check"></i> অ্যাপ্রুভ করুন');
            }
        });
    });

    // ============================================
    // Disapprove Comment
    // ============================================
    $('.disapprove-comment').on('click', function() {
        var id = $(this).data('id');

        Swal.fire({
            title: 'আপনি কি নিশ্চিত?',
            text: 'এই মন্তব্যটি ডিসঅ্যাপ্রুভ করতে চান?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'হ্যাঁ, ডিসঅ্যাপ্রুভ',
            cancelButtonText: 'বাতিল'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin/blog/comments") }}/' + id + '/disapprove',
                    type: 'POST',
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
                        toastr.error('মন্তব্য ডিসঅ্যাপ্রুভ করতে ব্যর্থ হয়েছে');
                    }
                });
            }
        });
    });

    // ============================================
    // Delete Comment
    // ============================================
    $('.delete-comment').on('click', function() {
        var id = $(this).data('id');
        var author = $(this).data('author');

        Swal.fire({
            title: 'আপনি কি নিশ্চিত?',
            html: 'আপনি কি <strong>' + author + '</strong> এর মন্তব্যটি ডিলিট করতে চান?',
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
                            setTimeout(function() {
                                window.location.href = '{{ route("admin.blog.comments.index") }}';
                            }, 1500);
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
    // Reply to Comment
    // ============================================
    $('.reply-comment').on('click', function() {
        var id = $(this).data('id');
        $('#reply_comment_id').val(id);
        $('#replyModal').modal('show');
    });

    $('#submitReply').on('click', function() {
        var id = $('#reply_comment_id').val();
        var name = $('#reply_name').val().trim();
        var email = $('#reply_email').val().trim();
        var comment = $('#reply_comment').val().trim();

        if (!name) {
            toastr.error('আপনার নাম দিন');
            $('#reply_name').focus();
            return;
        }

        if (!email) {
            toastr.error('আপনার ইমেইল দিন');
            $('#reply_email').focus();
            return;
        }

        if (!comment || comment.length < 2) {
            toastr.error('কমপক্ষে ২ অক্ষরের রিপ্লাই লিখুন');
            $('#reply_comment').focus();
            return;
        }

        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: '{{ url("admin/blog/comments") }}/' + id + '/reply',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                name: name,
                email: email,
                comment: comment
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#replyModal').modal('hide');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error(response.message);
                    btn.prop('disabled', false).html('<i class="fas fa-reply"></i> রিপ্লাই দিন');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                } else {
                    toastr.error('রিপ্লাই দিতে ব্যর্থ হয়েছে');
                }
                btn.prop('disabled', false).html('<i class="fas fa-reply"></i> রিপ্লাই দিন');
            }
        });
    });

    // Reset form on modal close
    $('#replyModal').on('hidden.bs.modal', function() {
        $('#replyForm')[0].reset();
        $('#submitReply').prop('disabled', false).html('<i class="fas fa-reply"></i> রিপ্লাই দিন');
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
