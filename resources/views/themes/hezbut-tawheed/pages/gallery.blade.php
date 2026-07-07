@extends('theme::layouts.app')

@section('title', 'চিত্রশালা - হেযবুত তওহীদ')

@push('styles')
<style>
    .gallery-banner {
        background: linear-gradient(135deg, #022c22 0%, #064e3b 100%);
        padding: 60px 0;
        position: relative;
        overflow: hidden;
    }
    .gallery-banner::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 10px;
        background: linear-gradient(to right, #10b981, #f59e0b);
    }
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
    }
    .gallery-item-card {
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        display: flex;
        flex-direction: column;
        height: 380px;
    }
    .gallery-item-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.12);
        border-color: #10b981;
    }
    .gallery-item-img-wrapper {
        position: relative;
        height: 230px;
        overflow: hidden;
        background-color: #f8f9fa;
        cursor: pointer;
    }
    .gallery-item-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .gallery-item-card:hover .gallery-item-img {
        transform: scale(1.06);
    }
    .gallery-item-zoom {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(2, 44, 34, 0.6);
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
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #10b981;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        transform: scale(0.8);
        transition: transform 0.3s ease;
    }
    .gallery-item-card:hover .gallery-zoom-btn {
        transform: scale(1);
    }
    .gallery-item-info {
        padding: 18px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        justify-content: space-between;
    }
    .gallery-item-cat {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #10b981;
        letter-spacing: 1px;
        margin-bottom: 6px;
    }
    .gallery-item-title {
        font-size: 15px;
        font-weight: 700;
        color: #1f2937;
        line-height: 22px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 44px;
        margin-bottom: 8px;
        font-family: 'Baloo Da 2', sans-serif;
    }
    .gallery-item-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        border-top: 1px solid #f3f4f6;
        padding-top: 12px;
        font-size: 12px;
        color: #6b7280;
    }
    .gallery-item-link {
        color: #10b981;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .gallery-item-link:hover {
        color: #f59e0b;
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
        <h1 class="display-5 fw-bold mb-3" style="font-family: 'Baloo Da 2', sans-serif;">স্থিরচিত্র চিত্রশালা</h1>
        <p class="lead opacity-75 mb-0" style="font-family: 'Hind Siliguri', sans-serif;">আন্দোলনের বিভিন্ন কর্মসূচী, সেমিনার ও সামাজিক কার্যক্রমের ফটো গ্যালারি</p>
    </div>
</div>

<!-- Gallery Grid Section -->
<div class="py-6 bg-light">
    <div class="container">
        @if(count($galleryPosts) > 0)
            <div class="gallery-grid">
                @foreach($galleryPosts as $post)
                    <div class="gallery-item-card">
                        <!-- Image Container with Lightbox Trigger -->
                        <div class="gallery-item-img-wrapper gallery-lightbox-trigger"
                             data-image="{{ asset($post->image_path) }}"
                             data-title="{{ $post->title ?? ($post->blog ? $post->blog->title : 'চিত্রশালা') }}"
                             data-category="{{ ($post->blog && $post->blog->category) ? $post->blog->category->name : 'চিত্রশালা' }}"
                             data-url="{{ $post->blog ? route('blog.detail', $post->blog->slug) : '#' }}">
                            
                            <img src="{{ asset($post->image_path) }}" alt="{{ $post->title ?? '' }}" class="gallery-item-img" loading="lazy">
                            
                            <div class="gallery-item-zoom">
                                <div class="gallery-zoom-btn">
                                    <i class="fas fa-search-plus"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="gallery-item-info">
                            <div>
                                <div class="gallery-item-cat">
                                    {{ ($post->blog && $post->blog->category) ? $post->blog->category->name : 'চিত্রশালা' }}
                                </div>
                                <h3 class="gallery-item-title">
                                    {{ $post->title ?? ($post->blog ? $post->blog->title : 'স্থিরচিত্র চিত্রশালা') }}
                                </h3>
                            </div>
                            
                            <div class="gallery-item-footer">
                                <span><i class="far fa-calendar-alt me-1"></i> {{ $post->created_at->format('d M, Y') }}</span>
                                @if($post->blog)
                                    <a href="{{ route('blog.detail', $post->blog->slug) }}" class="gallery-item-link">
                                        বিস্তারিত পড়ুন <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                @endif
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
