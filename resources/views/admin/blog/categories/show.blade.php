{{-- resources/views/admin/blog/categories/show.blade.php --}}

@extends('admin.layouts.master')

@section('page-title', 'ক্যাটাগরি বিস্তারিত - ' . $category->name)

@push('styles')
<style>
    .category-detail-image {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border-radius: 12px;
        border: 4px solid #e9ecef;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .category-detail-image:hover {
        transform: scale(1.03);
        border-color: #0d6efd;
        box-shadow: 0 8px 25px rgba(13,110,253,0.2);
    }
    .info-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .info-card:hover {
        background: #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .info-card .info-label {
        font-weight: 600;
        color: #495057;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .info-card .info-value {
        font-size: 16px;
        color: #212529;
        margin-top: 5px;
        word-break: break-word;
    }
    .info-card .info-value .badge {
        font-size: 14px;
        padding: 5px 15px;
    }
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(102,126,234,0.4);
    }
    .stats-card .stat-number {
        font-size: 32px;
        font-weight: 700;
    }
    .stats-card .stat-label {
        font-size: 14px;
        opacity: 0.9;
        margin-top: 5px;
    }
    .stats-card.green {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    .stats-card.orange {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .stats-card.blue {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .seo-preview {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #e9ecef;
    }
    .seo-preview .seo-title {
        color: #1a0dab;
        font-size: 20px;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
    }
    .seo-preview .seo-title:hover {
            text-decoration: underline;
        }
    .seo-preview .seo-link {
        color: #006621;
        font-size: 14px;
        margin-top: 4px;
        word-break: break-all;
    }
    .seo-preview .seo-description {
        color: #545454;
        font-size: 14px;
        margin-top: 4px;
        line-height: 1.6;
    }
    .action-btn-group {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .action-btn-group .btn {
        padding: 8px 20px;
        font-size: 14px;
    }
    .meta-info-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
    }
    .meta-info-item:last-child {
        border-bottom: none;
    }
    .meta-info-item .label {
        font-weight: 600;
        color: #6c757d;
    }
    .meta-info-item .value {
        color: #212529;
    }
    .blog-list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        background: #fff;
        border-radius: 8px;
        margin-bottom: 8px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .blog-list-item:hover {
        background: #f8f9fa;
        border-color: #0d6efd;
    }
    .blog-list-item .blog-title {
        font-weight: 500;
        color: #212529;
    }
    .blog-list-item .blog-title:hover {
        color: #0d6efd;
    }
    .blog-list-item .blog-meta {
        font-size: 12px;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-tag me-2"></i> ক্যাটাগরি বিস্তারিত
                <span class="badge bg-primary ms-2">#{{ $category->id }}</span>
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.blog.categories.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> ফিরে যান
                    </a>
                    <a href="{{ route('admin.blog.categories.edit', $category->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> এডিট
                    </a>
                    @if($category->status)
                        <button class="btn btn-warning btn-sm toggle-status" data-id="{{ $category->id }}">
                            <i class="fas fa-times"></i> নিষ্ক্রিয় করুন
                        </button>
                    @else
                        <button class="btn btn-success btn-sm toggle-status" data-id="{{ $category->id }}">
                            <i class="fas fa-check"></i> সক্রিয় করুন
                        </button>
                    @endif
                    <button class="btn btn-danger btn-sm delete-category"
                            data-id="{{ $category->id }}"
                            data-name="{{ $category->name }}"
                            data-blog-count="{{ $category->blogs_count ?? 0 }}">
                        <i class="fas fa-trash"></i> ডিলিট
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Left Column - Main Info -->
                <div class="col-md-8">
                    <!-- Category Image -->
                    <div class="text-center mb-4">
                        <img src="{{ $category->image_url }}"
                             alt="{{ $category->name }}"
                             class="category-detail-image">
                    </div>

                    <!-- Category Info -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label"><i class="fas fa-tag"></i> ক্যাটাগরি নাম</div>
                                <div class="info-value">{{ $category->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label"><i class="fas fa-link"></i> স্লাগ</div>
                                <div class="info-value">
                                    <code>{{ $category->slug }}</code>
                                    <a href="{{ url('/blog/categories/' . $category->slug) }}" target="_blank" class="ms-2">
                                        <i class="fas fa-external-link-alt text-primary"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="info-card mt-3">
                        <div class="info-label"><i class="fas fa-align-left"></i> বিবরণ</div>
                        <div class="info-value">
                            @if($category->description)
                                {{ $category->description }}
                            @else
                                <span class="text-muted">কোন বিবরণ নেই</span>
                            @endif
                        </div>
                    </div>

                    <!-- SEO Preview -->
                    @if($category->meta_title || $category->meta_description)
                        <div class="mt-4">
                            <h5><i class="fas fa-search text-primary"></i> SEO প্রিভিউ</h5>
                            <div class="seo-preview">
                                <div class="seo-title">{{ $category->meta_title ?? $category->name }}</div>
                                <div class="seo-link">{{ url('/blog/categories') }}/{{ $category->slug }}</div>
                                <div class="seo-description">{{ $category->meta_description ?? $category->description ?? 'ক্যাটাগরি বিবরণ' }}</div>
                            </div>
                        </div>
                    @endif

                    <!-- Blogs under this category -->
                    <div class="mt-4">
                        <h5>
                            <i class="fas fa-file-alt text-primary"></i>
                            এই ক্যাটাগরির ব্লগ পোস্ট
                            <span class="badge bg-primary ms-2">{{ $category->blogs_count ?? 0 }}</span>
                        </h5>
                        <hr>
                        @if($category->blogs_count > 0)
                            <div class="mt-3">
                                @foreach($category->blogs()->published()->limit(10)->get() as $blog)
                                    <div class="blog-list-item">
                                        <div>
                                            <a href="{{ route('admin.blog.posts.show', $blog->id) }}" class="blog-title">
                                                {{ $blog->title }}
                                            </a>
                                            <div class="blog-meta">
                                                <i class="fas fa-calendar-alt"></i> {{ $blog->published_at?->format('d M, Y') }}
                                                <span class="ms-2">
                                                    <i class="fas fa-eye"></i> {{ number_format($blog->views ?? 0) }}
                                                </span>
                                                <span class="ms-2">
                                                    <i class="fas fa-comments"></i> {{ $blog->approvedComments()->count() }}
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.blog.posts.edit', $blog->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ $blog->url }}" target="_blank" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                                @if($category->blogs_count > 10)
                                    <div class="text-center mt-2">
                                        <a href="{{ route('admin.blog.posts.index', ['category' => $category->id]) }}" class="btn btn-sm btn-outline-primary">
                                            আরও দেখুন ({{ $category->blogs_count - 10 }})
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">এই ক্যাটাগরির অধীনে কোন ব্লগ পোস্ট নেই</p>
                                <a href="{{ route('admin.blog.posts.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> নতুন ব্লগ পোস্ট তৈরি করুন
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column - Stats & Meta -->
                <div class="col-md-4">
                    <!-- Stats -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="stats-card">
                                <div class="stat-number">{{ $category->blogs_count ?? 0 }}</div>
                                <div class="stat-label"><i class="fas fa-file-alt"></i> ব্লগ পোস্ট</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-card green">
                                <div class="stat-number">{{ $category->status ? '✅' : '❌' }}</div>
                                <div class="stat-label">{{ $category->status ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="stats-card orange">
                                <div class="stat-number">{{ $category->sort_order ?? 0 }}</div>
                                <div class="stat-label"><i class="fas fa-sort"></i> সর্ট অর্ডার</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-card blue">
                                <div class="stat-number">{{ $category->created_at?->format('d') }}</div>
                                <div class="stat-label">{{ $category->created_at?->format('M, Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Meta Information -->
                    <div class="info-card">
                        <h6><i class="fas fa-info-circle"></i> মেটা তথ্য</h6>
                        <div class="meta-info-item">
                            <span class="label"><i class="fas fa-id"></i> আইডি</span>
                            <span class="value">#{{ $category->id }}</span>
                        </div>
                        <div class="meta-info-item">
                            <span class="label"><i class="fas fa-calendar-plus"></i> তৈরি</span>
                            <span class="value">{{ $category->created_at?->format('d M, Y h:i A') }}</span>
                        </div>
                        <div class="meta-info-item">
                            <span class="label"><i class="fas fa-calendar-edit"></i> আপডেট</span>
                            <span class="value">{{ $category->updated_at?->format('d M, Y h:i A') }}</span>
                        </div>
                        <div class="meta-info-item">
                            <span class="label"><i class="fas fa-clock"></i> আগে</span>
                            <span class="value">{{ $category->created_at?->diffForHumans() }}</span>
                        </div>
                        <div class="meta-info-item">
                            <span class="label"><i class="fas fa-tag"></i> স্ট্যাটাস</span>
                            <span class="value">
                                {!! $category->status ? '<span class="badge bg-success">সক্রিয়</span>' : '<span class="badge bg-danger">নিষ্ক্রিয়</span>' !!}
                            </span>
                        </div>
                        @if($category->meta_title)
                            <div class="meta-info-item">
                                <span class="label"><i class="fas fa-tag"></i> মেটা টাইটেল</span>
                                <span class="value">{{ $category->meta_title }}</span>
                            </div>
                        @endif
                        @if($category->meta_keywords)
                            <div class="meta-info-item">
                                <span class="label"><i class="fas fa-key"></i> মেটা কীওয়ার্ড</span>
                                <span class="value">{{ $category->meta_keywords }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Quick Actions -->
                    <div class="info-card mt-3">
                        <h6><i class="fas fa-tools"></i> দ্রুত অ্যাকশন</h6>
                        <div class="action-btn-group">
                            <a href="{{ route('admin.blog.categories.edit', $category->id) }}" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-edit"></i> এডিট করুন
                            </a>
                            <a href="{{ route('admin.blog.posts.create', ['category_id' => $category->id]) }}" class="btn btn-success btn-sm w-100">
                                <i class="fas fa-plus"></i> ব্লগ পোস্ট তৈরি করুন
                            </a>
                            <a href="{{ url('/blog/categories/' . $category->slug) }}" target="_blank" class="btn btn-info btn-sm w-100">
                                <i class="fas fa-external-link-alt"></i> লাইভ দেখুন
                            </a>
                            <button class="btn btn-danger btn-sm w-100 delete-category"
                                    data-id="{{ $category->id }}"
                                    data-name="{{ $category->name }}"
                                    data-blog-count="{{ $category->blogs_count ?? 0 }}">
                                <i class="fas fa-trash"></i> ডিলিট করুন
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
                <p>আপনি কি <strong id="delete-name"></strong> ক্যাটাগরিটি ডিলিট করতে চান?</p>
                <p class="text-danger"><small>⚠️ এই কাজটি অপরিবর্তনীয়! ডিলিট করার পর আর ফিরিয়ে আনা যাবে না।</small></p>
                <div class="text-warning" id="delete-blog-warning" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i>
                    এই ক্যাটাগরির অধীনে <strong id="delete-blog-count"></strong> টি ব্লগ পোস্ট আছে। প্রথমে ব্লগ পোস্ট গুলো সরান বা অন্য ক্যাটাগরিতে স্থানান্তর করুন!
                </div>
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
        var isActive = btn.text().trim().includes('সক্রিয়');

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: '{{ url("admin/blog/categories") }}/' + id + '/toggle-status',
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
                    btn.prop('disabled', false).html(isActive ? 'নিষ্ক্রিয় করুন' : 'সক্রিয় করুন');
                }
            },
            error: function() {
                toastr.error('স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে');
                btn.prop('disabled', false).html(isActive ? 'নিষ্ক্রিয় করুন' : 'সক্রিয় করুন');
            }
        });
    });

    // ============================================
    // Delete Category
    // ============================================
    $('.delete-category').click(function() {
        deleteId = $(this).data('id');
        var name = $(this).data('name');
        var blogCount = $(this).data('blog-count') || 0;

        $('#delete-name').text(name);

        if (blogCount > 0) {
            $('#delete-blog-warning').show();
            $('#delete-blog-count').text(blogCount);
            $('#confirm-delete').prop('disabled', true);
        } else {
            $('#delete-blog-warning').hide();
            $('#confirm-delete').prop('disabled', false);
        }

        $('#deleteModal').modal('show');
    });

    $('#confirm-delete').click(function() {
        if (!deleteId) return;
        if ($(this).prop('disabled')) return;

        $.ajax({
            url: '{{ url("admin/blog/categories") }}/' + deleteId,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                $('#deleteModal').modal('hide');
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.href = '{{ route("admin.blog.categories.index") }}';
                    }, 1500);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                var message = 'ক্যাটাগরি ডিলিট করতে ব্যর্থ হয়েছে';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
                $('#deleteModal').modal('hide');
            }
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
