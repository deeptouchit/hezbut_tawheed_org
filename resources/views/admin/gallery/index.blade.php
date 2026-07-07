@extends('admin.layouts.master')

@section('page-title', 'ফটো গ্যালারি ও মিডিয়া লাইব্রেরি')

@push('styles')
<style>
    /* Tab Styles */
    .nav-tabs .nav-link {
        font-weight: 600;
        color: #495057;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 12px 20px;
        transition: all 0.3s ease;
    }
    .nav-tabs .nav-link.active {
        color: #198754;
        border-bottom: 3px solid #198754;
        background: transparent;
    }
    .nav-tabs .nav-link:hover:not(.active) {
        border-color: #dee2e6;
        color: #198754;
    }

    /* Media Grid Styles */
    .media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        padding: 20px 0;
    }
    .media-card {
        border-radius: 8px;
        border: 1px solid #e9ecef;
        background: #fff;
        overflow: hidden;
        position: relative;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        display: flex;
        flex-direction: column;
        height: 280px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .media-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        border-color: #198754;
    }
    .media-img-wrapper {
        position: relative;
        width: 100%;
        height: 150px;
        background: #f8f9fa;
        overflow: hidden;
    }
    .media-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .media-card:hover .media-img {
        transform: scale(1.05);
    }
    .media-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 2;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 4px 8px;
        border-radius: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .active-indicator {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 2;
        background: #198754;
        color: white;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
        font-size: 14px;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    .media-info {
        padding: 12px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        justify-content: space-between;
    }
    .media-title {
        font-size: 13px;
        font-weight: 600;
        color: #212529;
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 38px;
        line-height: 19px;
    }
    .media-actions {
        display: flex;
        gap: 8px;
        margin-top: auto;
    }

    /* Active Table Reorder Styles */
    .reorder-thumb {
        width: 80px;
        height: 55px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }
    .drag-handle {
        cursor: move;
        color: #6c757d;
        font-size: 18px;
    }
    .drag-handle:hover {
        color: #198754;
    }
    .sortable-placeholder {
        background-color: #f1f8f5;
        border: 2px dashed #198754;
        height: 70px;
    }
    .dropzone-uploader {
        border: 2px dashed #ced4da;
        background: #f8f9fa;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .dropzone-uploader:hover {
        border-color: #198754;
        background: #f1f8f5;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Tab Controls -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white pb-0">
            <ul class="nav nav-tabs border-bottom-0" id="galleryTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="library-tab" data-bs-toggle="tab" data-bs-target="#library-pane" type="button" role="tab" aria-controls="library-pane" aria-selected="true">
                        <i class="fas fa-photo-video me-2"></i> মিডিয়া লাইব্রেরি
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active-pane" type="button" role="tab" aria-controls="active-pane" aria-selected="false">
                        <i class="fas fa-images me-2"></i> সক্রিয় চিত্রশালা (হোমপেজ)
                        <span class="badge bg-success ms-2" id="active-counter">{{ count($galleryPosts) }}</span>
                    </button>
                </li>
            </ul>
        </div>
        
        <div class="card-body p-4">
            <div class="tab-content" id="galleryTabsContent">
                
                <!-- Tab 1: Media Library -->
                <div class="tab-pane fade show active" id="library-pane" role="tabpanel" aria-labelledby="library-tab">
                    <!-- Custom Upload Area -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card border shadow-none bg-light">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3"><i class="fas fa-cloud-upload-alt text-success me-2"></i> নতুন ছবি আপলোড (ব্লগ পোস্টের বাইরে সরাসরি গ্যালারির জন্য)</h6>
                                    <form action="{{ route('admin.gallery.upload') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row align-items-end">
                                            <div class="col-md-5 mb-2">
                                                <label class="form-label text-muted small">ছবির শিরোনাম / ক্যাপশন</label>
                                                <input type="text" name="title" class="form-control form-control-sm" placeholder="ছবির ক্যাপশন লিখুন (ঐচ্ছিক)">
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label text-muted small">ছবি ফাইল নির্বাচন করুন</label>
                                                <input type="file" name="image" class="form-control form-control-sm @error('image') is-invalid @enderror" required>
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <button type="submit" class="btn btn-success btn-sm w-100">
                                                    <i class="fas fa-upload me-1"></i> লাইব্রেরিতে আপলোড করুন
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- WordPress-style Media Grid -->
                    <h5 class="fw-bold border-bottom pb-2 mb-3">
                        <i class="fas fa-th text-muted me-2"></i> সমস্ত মিডিয়া ফাইলসমূহ
                    </h5>
                    <div class="media-grid">
                        @forelse($libraryImages as $item)
                            <div class="media-card" id="card-{{ $item->id }}">
                                <div class="media-img-wrapper">
                                    <!-- Badges -->
                                    @if($item->is_custom)
                                        <span class="badge bg-purple media-badge"><i class="fas fa-cloud me-1"></i> কাস্টম</span>
                                    @else
                                        <span class="badge bg-primary media-badge"><i class="fas fa-blog me-1"></i> ব্লগ ইমেজ</span>
                                    @endif

                                    <!-- Active indicator -->
                                    @if($item->is_active)
                                        <div class="active-indicator" title="গ্যালারিতে সক্রিয় আছে">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    @endif

                                    <img src="{{ asset($item->image_path) }}" alt="{{ $item->title }}" class="media-img">
                                </div>
                                <div class="media-info">
                                    <div class="media-title" title="{{ $item->title ?? 'শিরোনামহীন ছবি' }}">
                                        {{ $item->title ?? 'শিরোনামহীন ছবি' }}
                                    </div>
                                    <div class="media-actions">
                                        <!-- Add/Remove Toggle -->
                                        <button class="btn btn-xs btn-sm flex-grow-1 toggle-active {{ $item->is_active ? 'btn-danger' : 'btn-success' }}" data-id="{{ $item->id }}">
                                            @if($item->is_active)
                                                <i class="fas fa-times-circle me-1"></i> বাদ দিন
                                            @else
                                                <i class="fas fa-check-circle me-1"></i> গ্যালারিতে দিন
                                            @endif
                                        </button>

                                        <!-- Delete Custom Uploads -->
                                        @if($item->is_custom)
                                            <button class="btn btn-xs btn-sm btn-outline-danger delete-custom" data-id="{{ $item->id }}" title="লাইব্রেরি থেকে মুছুন">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <i class="fas fa-photo-video text-muted" style="font-size: 50px;"></i>
                                <p class="text-muted mt-3">মিডিয়া লাইব্রেরিতে কোনো ছবি নেই!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Tab 2: Active Homepage Gallery (Sortable) -->
                <div class="tab-pane fade" id="active-pane" role="tabpanel" aria-labelledby="active-tab">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-sort-amount-down text-success me-2"></i> সক্রিয় হোমপেজ চিত্রশালা অর্ডার
                        </h5>
                        <span class="text-muted small"><i class="fas fa-arrows-alt me-1"></i> ওপরে-নিচে ড্র্যাগ করে ক্রমানুসারে সাজান</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="active-gallery-table">
                            <thead class="table-light">
                                <tr>
                                    <th width="80" class="text-center">সাজান</th>
                                    <th width="120">ছবি</th>
                                    <th>শিরোনাম</th>
                                    <th>টাইপ</th>
                                    <th width="150" class="text-center">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-body">
                                @forelse($galleryPosts as $item)
                                    <tr data-id="{{ $item->id }}">
                                        <td class="text-center">
                                            <i class="fas fa-grip-vertical drag-handle"></i>
                                        </td>
                                        <td>
                                            <img src="{{ asset($item->image_path) }}" alt="" class="reorder-thumb">
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->title ?? 'শিরোনামহীন ছবি' }}</div>
                                            @if($item->blog)
                                                <small class="text-muted"><i class="fas fa-link me-1"></i> লিঙ্কড ব্লগ: <span class="text-primary">{{ $item->blog->title }}</span></small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->is_custom)
                                                <span class="badge bg-purple"><i class="fas fa-cloud me-1"></i> কাস্টম</span>
                                            @else
                                                <span class="badge bg-primary"><i class="fas fa-blog me-1"></i> ব্লগ ইমেজ</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-danger toggle-active" data-id="{{ $item->id }}">
                                                <i class="fas fa-minus-circle me-1"></i> গ্যালারি থেকে বাদ
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="fas fa-images text-muted" style="font-size: 40px;"></i>
                                            <p class="text-muted mt-2 mb-0">হোমপেজের গ্যালারিতে দেখানোর জন্য কোনো ছবি নির্বাচন করা হয়নি।</p>
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
    // Active List Drag and Drop
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
                    if (id) {
                        order.push(id);
                    }
                });

                if (order.length === 0) return;

                $.ajax({
                    url: "{{ route('admin.gallery.reorder') }}",
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
                        toastr.error('সর্ট অর্ডার আপডেট করতে ব্যর্থ হয়েছে');
                    }
                });
            }
        });
    }

    // ============================================
    // Add/Remove Image from Active Gallery
    // ============================================
    $(document).on('click', '.toggle-active', function() {
        var id = $(this).data('id');
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: '{{ url("admin/gallery") }}/' + id + '/toggle-active',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    toastr.error(response.message);
                    btn.prop('disabled', false);
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false);
                var message = 'স্ট্যাটাস আপডেট করতে ব্যর্থ হয়েছে';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
            }
        });
    });

    // ============================================
    // Delete Custom Image
    // ============================================
    $(document).on('click', '.delete-custom', function() {
        var id = $(this).data('id');
        var card = $('#card-' + id);

        if (confirm('আপনি কি এই ছবিটি গ্যালারি মিডিয়া লাইব্রেরি ও স্টোরেজ থেকে চিরতরে ডিলিট করতে চান?')) {
            $.ajax({
                url: '{{ url("admin/gallery") }}/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        card.fadeOut(400, function() {
                            $(this).remove();
                        });
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('ছবি ডিলিট করতে ব্যর্থ হয়েছে');
                }
            });
        }
    });
});
</script>
@endpush
