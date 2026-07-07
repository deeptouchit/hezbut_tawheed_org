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
        height: 290px;
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

    /* Sortable Grid Styles (No text, just images) */
    .active-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 15px;
        padding: 15px 0;
    }
    .active-grid-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        aspect-ratio: 1;
        border: 2px solid #dee2e6;
        background: #f8f9fa;
        cursor: move;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .active-grid-item:hover {
        border-color: #198754;
        transform: scale(1.03);
    }
    .active-grid-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .active-grid-remove {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.85);
        color: white;
        border: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 11px;
        transition: all 0.2s ease;
        z-index: 10;
    }
    .active-grid-remove:hover {
        background: #dc3545;
        transform: scale(1.1);
    }
    .sortable-placeholder {
        background-color: #f1f8f5;
        border: 2px dashed #198754;
        border-radius: 8px;
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
                    <button class="nav-link" id="homepage-tab" data-bs-toggle="tab" data-bs-target="#homepage-pane" type="button" role="tab" aria-controls="homepage-pane" aria-selected="false">
                        <i class="fas fa-home me-2"></i> সক্রিয় চিত্রশালা (হোমপেজ)
                        <span class="badge bg-success ms-2" id="homepage-counter">{{ count($homepagePosts) }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="gallerypage-tab" data-bs-toggle="tab" data-bs-target="#gallerypage-pane" type="button" role="tab" aria-controls="gallerypage-pane" aria-selected="false">
                        <i class="fas fa-images me-2"></i> গ্যালারি পেজ
                        <span class="badge bg-info ms-2 text-white" id="gallerypage-counter">{{ count($galleryPagePosts) }}</span>
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
                        @include('admin.gallery.partials.media_cards')
                    </div>

                    @if($libraryImages->hasMorePages())
                        <div class="text-center my-4" id="load-more-container">
                            <button id="load-more-btn" class="btn btn-outline-success px-4" data-page="2">
                                <i class="fas fa-sync-alt me-1"></i> আরো ছবি লোড করুন
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Tab 2: Active Homepage Gallery (Sortable Grid, No Title) -->
                <div class="tab-pane fade" id="homepage-pane" role="tabpanel" aria-labelledby="homepage-tab">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-home text-success me-2"></i> সক্রিয় হোমপেজ চিত্রশালা (সর্বোচ্চ ৮টি ছবি)
                        </h5>
                        <span class="text-muted small"><i class="fas fa-arrows-alt me-1"></i> ড্র্যাগ করে ইচ্ছামত ক্রমানুসারে সাজান</span>
                    </div>

                    <div class="active-grid" id="homepage-sortable">
                        @forelse($homepagePosts as $item)
                            <div class="active-grid-item" data-id="{{ $item->id }}">
                                <img src="{{ asset($item->image_path) }}" alt="">
                                <button class="active-grid-remove toggle-homepage" data-id="{{ $item->id }}" title="হোমপেজ থেকে বাদ দিন">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 text-muted w-100">
                                <i class="fas fa-home fa-2x mb-2 text-muted opacity-50"></i>
                                <p class="mb-0">হোমপেজ গ্যালারিতে দেখানোর জন্য কোনো ছবি সক্রিয় করা হয়নি।</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Tab 3: Active Gallery Page (Sortable Grid, No Title) -->
                <div class="tab-pane fade" id="gallerypage-pane" role="tabpanel" aria-labelledby="gallerypage-tab">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-images text-info me-2"></i> গ্যালারি পেজে প্রদর্শিত চিত্রসমূহ (আনলিমিটেড)
                        </h5>
                        <span class="text-muted small"><i class="fas fa-arrows-alt me-1"></i> ড্র্যাগ করে গ্যালারি পেজের ক্রমানুসার সাজান</span>
                    </div>

                    <div class="active-grid" id="gallerypage-sortable">
                        @forelse($galleryPagePosts as $item)
                            <div class="active-grid-item" data-id="{{ $item->id }}">
                                <img src="{{ asset($item->image_path) }}" alt="">
                                <button class="active-grid-remove toggle-gallerypage" data-id="{{ $item->id }}" title="গ্যালারি পেজ থেকে বাদ দিন">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 text-muted w-100">
                                <i class="fas fa-images fa-2x mb-2 text-muted opacity-50"></i>
                                <p class="mb-0">গ্যালারি পেজে দেখানোর জন্য কোনো ছবি সক্রিয় করা হয়নি।</p>
                            </div>
                        @endforelse
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
    // Homepage Sortable Grid drag-and-drop reorder
    // ============================================
    var hpEl = document.getElementById('homepage-sortable');
    if (hpEl) {
        Sortable.create(hpEl, {
            animation: 150,
            ghostClass: 'sortable-placeholder',
            onEnd: function() {
                var order = [];
                $('#homepage-sortable .active-grid-item').each(function() {
                    var id = $(this).data('id');
                    if (id) {
                        order.push(id);
                    }
                });

                if (order.length === 0) return;

                $.ajax({
                    url: "{{ route('admin.gallery.reorder-homepage') }}",
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
                        toastr.error('হোমপেজ সর্ট অর্ডার আপডেট করতে ব্যর্থ হয়েছে');
                    }
                });
            }
        });
    }

    // ============================================
    // Gallery Page Sortable Grid drag-and-drop reorder
    // ============================================
    var gpEl = document.getElementById('gallerypage-sortable');
    if (gpEl) {
        Sortable.create(gpEl, {
            animation: 150,
            ghostClass: 'sortable-placeholder',
            onEnd: function() {
                var order = [];
                $('#gallerypage-sortable .active-grid-item').each(function() {
                    var id = $(this).data('id');
                    if (id) {
                        order.push(id);
                    }
                });

                if (order.length === 0) return;

                $.ajax({
                    url: "{{ route('admin.gallery.reorder-gallerypage') }}",
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
                        toastr.error('গ্যালারি পেজ সর্ট অর্ডার আপডেট করতে ব্যর্থ হয়েছে');
                    }
                });
            }
        });
    }

    // ============================================
    // Toggle Homepage visibility Target
    // ============================================
    $(document).on('click', '.toggle-homepage', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var btn = $(this);
        btn.prop('disabled', true);

        $.ajax({
            url: '{{ url("admin/gallery") }}/' + id + '/toggle-homepage',
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
                var message = 'হোমপেজ স্ট্যাটাস আপডেট করতে ব্যর্থ হয়েছে';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
            }
        });
    });

    // ============================================
    // Toggle Gallery page visibility Target
    // ============================================
    $(document).on('click', '.toggle-gallerypage', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var btn = $(this);
        btn.prop('disabled', true);

        $.ajax({
            url: '{{ url("admin/gallery") }}/' + id + '/toggle-gallerypage',
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
                var message = 'গ্যালারি পেজ স্ট্যাটাস আপডেট করতে ব্যর্থ হয়েছে';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
            }
        });
    });

    // ============================================
    // Load More Media Items
    // ============================================
    $(document).on('click', '#load-more-btn', function() {
        var btn = $(this);
        var page = parseInt(btn.attr('data-page'));
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> লোড হচ্ছে...');

        $.ajax({
            url: "{{ route('admin.gallery.index') }}?page=" + page + "&ajax=1",
            type: "GET",
            success: function(response) {
                if (response.success && response.html) {
                    $('.media-grid').append(response.html);
                    
                    if (response.hasMore) {
                        btn.attr('data-page', page + 1);
                        btn.prop('disabled', false).html('<i class="fas fa-sync-alt me-1"></i> আরো ছবি লোড করুন');
                    } else {
                        $('#load-more-container').fadeOut(400, function() {
                            $(this).remove();
                        });
                    }
                }
            },
            error: function() {
                toastr.error('ছবি লোড করতে ব্যর্থ হয়েছে');
                btn.prop('disabled', false).html('<i class="fas fa-sync-alt me-1"></i> আরো ছবি লোড করুন');
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
