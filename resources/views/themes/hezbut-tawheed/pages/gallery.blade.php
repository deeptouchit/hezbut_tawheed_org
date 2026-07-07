@extends('theme::layouts.app')

@section('title', 'চিত্রশালা - হেযবুত তওহীদ')

@push('styles')
<style>
    /* BNP-style Banner */
    .gallery-banner {
        background: linear-gradient(135deg, #022c22 0%, #064e3b 100%);
        padding: 50px 0;
        position: relative;
        overflow: hidden;
    }
    .gallery-banner::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 8px;
        background: linear-gradient(to right, #10b981, #f59e0b);
    }
    
    /* BNP-style Grid & Photo Frames */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
    }
    .gallery-item-card {
        border-radius: 12px;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        padding: 12px; /* Thick white frame/border like real photo frames */
        height: 250px;
        position: relative;
    }
    .gallery-item-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        border-color: #10b981;
    }
    .gallery-item-img-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
        border-radius: 8px;
        overflow: hidden;
        background-color: #f8f9fa;
    }
    .gallery-item-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .gallery-item-card:hover .gallery-item-img {
        transform: scale(1.08);
    }
    .gallery-item-zoom {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(2, 44, 34, 0.75); /* Dark green overlay matching BNP/HT colors */
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 2;
    }
    .gallery-item-card:hover .gallery-item-zoom {
        opacity: 1;
    }
    .gallery-zoom-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #10b981;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        transform: scale(0.8);
        transition: transform 0.3s ease;
        cursor: pointer;
    }
    .gallery-zoom-btn:hover {
        background: #f59e0b;
        transform: scale(1.1) !important;
    }
    .gallery-item-card:hover .gallery-zoom-btn {
        transform: scale(1);
    }

    /* Lightbox Modal */
    .lightbox-modal-content {
        background: #0b1512 !important;
        border: 1px solid rgba(16, 185, 129, 0.25) !important;
        border-radius: 20px;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<!-- Header Banner -->
<div class="gallery-banner text-center text-white">
    <div class="container">
        <h1 class="display-6 fw-bold mb-2" style="font-family: 'Baloo Da 2', sans-serif;">স্থিরচিত্র চিত্রশালা</h1>
        <p class="lead opacity-75 mb-0" style="font-family: 'Hind Siliguri', sans-serif; font-size: 1.05rem;">আন্দোলনের বিভিন্ন কর্মসূচী, সেমিনার ও সামাজিক কার্যক্রমের গ্যালারি</p>
    </div>
</div>

<!-- Gallery Grid Section -->
<div class="py-6 bg-light">
    <div class="container">
        @if(count($galleryPosts) > 0)
            <div class="gallery-grid">
                @foreach($galleryPosts as $post)
                    <div class="gallery-item-card">
                        <!-- Image Container with Hover Overlay -->
                        <div class="gallery-item-img-wrapper">
                            <img src="{{ asset($post->image_path) }}" alt="{{ $post->title ?? '' }}" class="gallery-item-img" loading="lazy">
                            
                            <!-- Hover Green Overlay containing title, date, and zoom btn -->
                            <div class="gallery-item-zoom">
                                <div class="d-flex flex-column align-items-center justify-content-center text-center p-3 h-100 w-100">
                                    <!-- Zoom Trigger -->
                                    <div class="gallery-zoom-btn mb-2 gallery-lightbox-trigger"
                                         data-image="{{ asset($post->image_path) }}"
                                         data-title="{{ $post->title ?? ($post->blog ? $post->blog->title : 'চিত্রশালা') }}"
                                         data-category="{{ ($post->blog && $post->blog->category) ? $post->blog->category->name : 'চিত্রশালা' }}"
                                         data-url="{{ $post->blog ? route('blog.detail', $post->blog->slug) : '#' }}">
                                        <i class="fas fa-search-plus"></i>
                                    </div>
                                    
                                    <!-- Caption/Title -->
                                    <h4 class="text-white fw-bold mb-1 px-1" style="font-family: 'Baloo Da 2', sans-serif; font-size: 13px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $post->title ?? ($post->blog ? $post->blog->title : 'স্থিরচিত্র') }}
                                    </h4>

                                    <!-- Date -->
                                    <span class="text-white-50 small mb-2" style="font-size: 10px;">
                                        <i class="far fa-calendar-alt me-1"></i> {{ $post->created_at->format('d M, Y') }}
                                    </span>

                                    <!-- Blog Detail Link -->
                                    @if($post->blog)
                                        <a href="{{ route('blog.detail', $post->blog->slug) }}" class="btn btn-gold btn-xs py-1 px-3 text-white" style="font-size: 10px; background: #f59e0b; border-radius: 20px; font-family: 'Hind Siliguri', sans-serif;">
                                            বিস্তারিত খবর <i class="fas fa-arrow-right ms-1" style="font-size: 8px;"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Custom Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $galleryPosts->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center py-6">
                <i class="far fa-images text-muted opacity-50" style="font-size: 60px;"></i>
                <p class="text-muted mt-3">এই মুহূর্তে কোনো স্থিরচিত্র পাওয়া যায়নি!</p>
            </div>
        @endif
    </div>
</div>

<!-- Photo Gallery Lightbox Modal -->
<div class="modal fade" id="galleryLightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content lightbox-modal-content text-white">
            <div class="modal-header border-0 pb-0 position-absolute end-0 top-0" style="z-index: 10;">
                <button type="button" class="btn-close btn-close-white p-3" data-bs-dismiss="modal" aria-label="Close" style="background-color: rgba(0,0,0,0.55); border-radius: 50%;"></button>
            </div>
            <div class="modal-body p-0 position-relative">
                <img id="lightbox-image" src="" alt="Gallery Image" class="w-100 object-fit-contain" style="max-height: 70vh; background-color: #000;">
                
                <div class="p-4" style="background: linear-gradient(transparent, rgba(2, 44, 34, 0.95) 20%, #022c22 100%);">
                    <span id="lightbox-category" class="badge text-white mb-2 rounded-pill px-3 py-2" style="font-size: 0.75rem; font-weight: 700; background: linear-gradient(135deg, #10b981 0%, #059669 100%);"></span>
                    <h4 id="lightbox-title" class="fw-bold text-white mb-3" style="font-family: 'Baloo Da 2', sans-serif;"></h4>
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3" style="border-top: 1px solid rgba(255,255,255,0.15);">
                        <span class="small text-muted">হেযবুত তওহীদ চিত্রশালা</span>
                        <a id="lightbox-article-link" href="" class="btn btn-outline-success btn-sm rounded-pill px-4 py-2 text-white hover-bg-gold" style="border: 2px solid #10b981; transition: all 0.3s ease;">
                            বিস্তারিত খবর পড়ুন <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var lightboxModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('galleryLightboxModal'));

    $(document).on('click', '.gallery-lightbox-trigger', function(e) {
        e.preventDefault();
        var imageSrc = $(this).data('image');
        var title = $(this).data('title');
        var category = $(this).data('category');
        var articleUrl = $(this).data('url');

        $('#lightbox-image').attr('src', imageSrc);
        $('#lightbox-category').text(category);
        $('#lightbox-title').text(title);
        
        if (articleUrl && articleUrl !== '#' && articleUrl !== '') {
            $('#lightbox-article-link').attr('href', articleUrl).show();
        } else {
            $('#lightbox-article-link').hide();
        }

        lightboxModal.show();
    });
});
</script>
@endpush
