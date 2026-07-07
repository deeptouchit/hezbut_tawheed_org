@extends('admin.layouts.master')

@section('page-title', 'চিত্রশালা গ্যালারি ম্যানেজমেন্ট')

@push('styles')
<style>
    .gallery-thumb {
        width: 100px;
        height: 70px;
        object-fit: cover;
        border-radius: 6px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .gallery-thumb:hover {
        transform: scale(1.05);
        border-color: #198754;
    }
    .drag-handle {
        cursor: move;
        color: #6c757d;
        transition: all 0.2s ease;
    }
    .drag-handle:hover {
        color: #198754;
    }
    .sortable-placeholder {
        background-color: #f1f8f5;
        border: 2px dashed #198754;
        height: 80px;
        border-radius: 8px;
    }
    .gallery-card-container {
        border-radius: 8px;
        border: 1px solid #e9ecef;
        background: #fff;
    }
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }
    .empty-state i {
        font-size: 48px;
        color: #ced4da;
        margin-bottom: 15px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Add Image Card -->
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i> গ্যালারিতে ছবি যোগ করুন
                    </h5>
                </div>
                <div class="card-body">
                    <form id="add-to-gallery-form">
                        @csrf
                        <div class="mb-3">
                            <label for="blog_id" class="form-label text-muted">পাবলিশড ব্লগ পোস্টসমূহ (যার ফিচার্ড ইমেজ আছে)</label>
                            <select name="blog_id" id="blog_id" class="form-select @error('blog_id') is-invalid @enderror" required>
                                <option value="">একটি পোস্ট নির্বাচন করুন</option>
                                @foreach($availablePosts as $post)
                                    <option value="{{ $post->id }}">
                                        {{ $post->title }} ({{ $post->category?->name ?? 'ক্যাটাগরিহীন' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100" id="submit-btn">
                            <i class="fas fa-plus me-1"></i> গ্যালারিতে যুক্ত করুন
                        </button>
                    </form>
                </div>
            </div>

            <!-- Helpful tips -->
            <div class="card shadow-sm">
                <div class="card-body bg-light">
                    <h6 class="fw-bold"><i class="fas fa-info-circle text-primary me-2"></i> কিছু তথ্য ও নিয়মাবলী:</h6>
                    <ul class="small text-muted ps-3 mb-0">
                        <li>হোমপেজের গ্যালারিতে সর্বোচ্চ <strong>৮টি</strong> ছবি সুন্দর গ্রিড আকারে প্রদর্শিত হয়।</li>
                        <li>ছবিগুলোর প্রদর্শনের ক্রম পরিবর্তন করতে ডানদিকের তালিকায় থাকা ড্র্যাগ আইকন (<i class="fas fa-grip-vertical"></i>) টেনে ওপরে বা নিচে সাজান।</li>
                        <li>গ্যালারি থেকে কোনো ছবি সরালে মূল ব্লগ পোস্ট ডিলিট হবে না, কেবল ছবি গ্যালারিতে দেখাবে না।</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Gallery List Card -->
        <div class="col-md-8">
            <div class="card shadow-sm gallery-card-container">
                <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-images text-success me-2"></i> গ্যালারিতে প্রদর্শিত ছবিসমূহ
                        <span class="badge bg-success ms-2" id="gallery-count">{{ count($galleryPosts) }}</span>
                    </h5>
                    <small class="text-muted"><i class="fas fa-sync me-1"></i> ড্র্যাগ অ্যান্ড ড্রপ করে সর্ট করুন</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="gallery-table">
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="text-center">ক্রম</th>
                                    <th width="120">ছবি</th>
                                    <th>শিরোনাম</th>
                                    <th>ক্যাটাগরি</th>
                                    <th width="120" class="text-center">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-body">
                                @forelse($galleryPosts as $index => $post)
                                    <tr data-id="{{ $post->id }}">
                                        <td class="text-center">
                                            <i class="fas fa-grip-vertical drag-handle" title="ড্র্যাগ করে সাজান"></i>
                                        </td>
                                        <td>
                                            @if($post->featured_image)
                                                <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="gallery-thumb">
                                            @else
                                                <span class="text-muted">কোনো ছবি নেই</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $post->title }}</div>
                                            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> {{ $post->published_at?->format('d M, Y') ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $post->category?->name ?? 'ক্যাটাগরিহীন' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-danger remove-gallery" data-id="{{ $post->id }}" title="গ্যালারি থেকে মুছুন">
                                                <i class="fas fa-trash-alt"></i> বাদ দিন
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="empty-row">
                                        <td colspan="5" class="p-0">
                                            <div class="empty-state">
                                                <i class="fas fa-images"></i>
                                                <p class="text-muted mb-0">গ্যালারিতে কোনো ছবি যুক্ত করা নেই। বামদিকের ফর্ম থেকে ছবি যোগ করুন।</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    // ============================================
    // Sortable Drag and Drop
    // ============================================
    var el = document.getElementById('sortable-body');
    if (el) {
        Sortable.create(el, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-placeholder',
            onEnd: function(evt) {
                var order = [];
                $('#sortable-body tr').each(function() {
                    var id = $(this).data('id');
                    if(id) {
                        order.push(id);
                    }
                });

                if (order.length === 0) return;

                $.ajax({
                    url: "{{ route('admin.blog.posts.reorder-gallery') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order: order
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('অর্ডার আপডেট করতে ব্যর্থ হয়েছে');
                    }
                });
            }
        });
    }

    // ============================================
    // Add Post to Gallery
    // ============================================
    $('#add-to-gallery-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var submitBtn = $('#submit-btn');
        var blogId = $('#blog_id').val();

        if (!blogId) {
            toastr.warning('দয়া করে একটি পোস্ট নির্বাচন করুন');
            return;
        }

        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> যুক্ত হচ্ছে...');

        $.ajax({
            url: "{{ route('admin.gallery.add') }}",
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                submitBtn.prop('disabled', false).html('<i class="fas fa-plus me-1"></i> গ্যালারিতে যুক্ত করুন');
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html('<i class="fas fa-plus me-1"></i> গ্যালারিতে যুক্ত করুন');
                var message = 'গ্যালারিতে ছবি যোগ করতে ব্যর্থ হয়েছে';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
            }
        });
    });

    // ============================================
    // Remove Post from Gallery
    // ============================================
    $('.remove-gallery').on('click', function() {
        var id = $(this).data('id');
        var btn = $(this);

        if (confirm('আপনি কি এই পোস্টের ছবি গ্যালারি থেকে বাদ দিতে চান?')) {
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: '{{ url("admin/blog/posts") }}/' + id + '/toggle-gallery',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        toastr.success('গ্যালারি থেকে ছবি সফলভাবে মুছে ফেলা হয়েছে!');
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                        btn.prop('disabled', false).html('<i class="fas fa-trash-alt"></i> বাদ দিন');
                    }
                },
                error: function() {
                    toastr.error('গ্যালারি থেকে ছবি মুছতে ব্যর্থ হয়েছে');
                    btn.prop('disabled', false).html('<i class="fas fa-trash-alt"></i> বাদ দিন');
                }
            });
        }
    });
});
</script>
@endpush
