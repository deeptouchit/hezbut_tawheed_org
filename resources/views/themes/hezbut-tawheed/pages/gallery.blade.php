@extends('theme::layouts.app')

@section('title', 'চিত্রশালা - হেযবুত তওহীদ')

@push('styles')
<style>
    /* Banner styles */
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
        height: 6px;
        background: linear-gradient(to right, #10b981, #f59e0b);
    }
    
    /* Clean grid matching the user's second screenshot */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 15px; /* Clean gap */
        padding: 15px 0;
    }
    .gallery-item-card {
        border-radius: 8px; /* Rounded corners matching screenshot */
        overflow: hidden;
        aspect-ratio: 16/10; /* Clean photo ratio */
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        background: #e5e7eb;
    }
    .gallery-item-card:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .gallery-item-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* Custom Split Lightbox matching bnpbd.org screenshot */
    #custom-lightbox {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.95);
        z-index: 10000;
        display: none; /* Hidden by default */
    }
    
    /* Lightbox main container splitting into left (preview) and right (thumbnails) */
    .lightbox-container {
        display: flex;
        width: 100%;
        height: 100%;
        position: relative;
    }
    
    /* Left / Center Area: Main Preview */
    .lightbox-preview-area {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: relative;
        padding: 40px;
        background: #000;
    }
    
    .lightbox-main-img {
        max-width: 90%;
        max-height: 75vh;
        object-fit: contain;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        border: 3px solid rgba(255,255,255,0.05);
        border-radius: 4px;
        transition: opacity 0.2s ease-in-out;
    }
    
    /* Navigation arrows floating on left and right edges */
    .lightbox-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.6);
        color: white;
        border: 1px solid rgba(255,255,255,0.15);
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 20px;
        transition: all 0.3s ease;
        z-index: 100;
    }
    .lightbox-nav-btn:hover {
        background: #10b981;
        border-color: #10b981;
        transform: translateY(-50%) scale(1.1);
    }
    .lightbox-prev {
        left: 20px;
    }
    .lightbox-next {
        right: 20px;
    }
    
    /* Close button on top-right of preview */
    .lightbox-close-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(0,0,0,0.5);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.3s ease;
        z-index: 1000;
    }
    .lightbox-close-btn:hover {
        background: #dc3545;
    }
    
    /* Info bar below preview */
    .lightbox-info-bar {
        position: absolute;
        bottom: 20px;
        left: 40px;
        right: 40px;
        text-align: center;
        color: white;
        z-index: 90;
        background: rgba(0,0,0,0.7);
        padding: 15px;
        border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.1);
        backdrop-filter: blur(5px);
    }
    .lightbox-title {
        font-family: 'Baloo Da 2', sans-serif;
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .lightbox-meta {
        font-size: 12px;
        color: #9ca3af;
    }
    .lightbox-link {
        display: inline-block;
        margin-top: 8px;
        color: #10b981;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
    }
    .lightbox-link:hover {
        color: #f59e0b;
    }
    
    /* Right Area: Vertical Thumbnails Sidebar */
    .lightbox-sidebar {
        width: 140px;
        min-width: 140px;
        background: #111;
        border-left: 1px solid rgba(255,255,255,0.1);
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 15px;
        z-index: 95;
    }
    .lightbox-sidebar::-webkit-scrollbar {
        width: 6px;
    }
    .lightbox-sidebar::-webkit-scrollbar-thumb {
        background: #333;
        border-radius: 3px;
    }
    .lightbox-sidebar-thumb {
        width: 100%;
        aspect-ratio: 4/3;
        border-radius: 4px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s ease;
        opacity: 0.6;
    }
    .lightbox-sidebar-thumb:hover {
        opacity: 0.9;
    }
    .lightbox-sidebar-thumb.active {
        border-color: #007bff; /* Bright blue border matching BNP site active thumb */
        opacity: 1;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
    }
    .lightbox-sidebar-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
                @foreach($galleryPosts as $index => $post)
                    <div class="gallery-item-card open-lightbox" data-index="{{ $index }}">
                        <img src="{{ asset($post->image_path) }}" alt="{{ $post->title ?? '' }}" class="gallery-item-img" loading="lazy">
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

<!-- Custom Split-Screen Lightbox Modal (bnpbd.org Style) -->
<div id="custom-lightbox">
    <div class="lightbox-container">
        <!-- Close Button -->
        <button class="lightbox-close-btn">&times;</button>
        
        <!-- Left: Main Image Preview Area -->
        <div class="lightbox-preview-area">
            <!-- Navigation Prev -->
            <button class="lightbox-nav-btn lightbox-prev"><i class="fas fa-chevron-left"></i></button>
            
            <!-- Large Image -->
            <img class="lightbox-main-img" src="" alt="">
            
            <!-- Navigation Next -->
            <button class="lightbox-nav-btn lightbox-next"><i class="fas fa-chevron-right"></i></button>
            
            <!-- Bottom Description Details -->
            <div class="lightbox-info-bar">
                <div class="lightbox-title" id="lb-title"></div>
                <div class="lightbox-meta">
                    <span id="lb-category"></span> | <i class="far fa-calendar-alt"></i> <span id="lb-date"></span>
                </div>
                <a href="" id="lb-link" class="lightbox-link" target="_blank">
                    বিস্তারিত খবর পড়ুন <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        
        <!-- Right: Vertical Thumbnails List Sidebar -->
        <div class="lightbox-sidebar">
            @foreach($galleryPosts as $index => $post)
                <div class="lightbox-sidebar-thumb" id="thumb-{{ $index }}" data-index="{{ $index }}">
                    <img src="{{ asset($post->image_path) }}" alt="">
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Generate JS array of images for fast navigation
    var galleryImages = [
        @foreach($galleryPosts as $index => $post)
        {
            index: {{ $index }},
            image: "{{ asset($post->image_path) }}",
            title: "{{ $post->title ?? ($post->blog ? $post->blog->title : 'স্থিরচিত্র') }}",
            category: "{{ ($post->blog && $post->blog->category) ? $post->blog->category->name : 'চিত্রশালা' }}",
            date: "{{ $post->created_at->format('d M, Y') }}",
            url: "{{ $post->blog ? route('blog.detail', $post->blog->slug) : '' }}"
        },
        @endforeach
    ];

    var currentIndex = 0;

    // Open Lightbox
    $(document).on('click', '.open-lightbox', function() {
        var index = parseInt($(this).data('index'));
        showImage(index);
        $('#custom-lightbox').fadeIn(300);
    });

    // Close Lightbox
    $(document).on('click', '.lightbox-close-btn', function() {
        $('#custom-lightbox').fadeOut(300);
    });

    // Prev Button
    $(document).on('click', '.lightbox-prev', function(e) {
        e.stopPropagation();
        var prevIndex = (currentIndex === 0) ? galleryImages.length - 1 : currentIndex - 1;
        showImage(prevIndex);
    });

    // Next Button
    $(document).on('click', '.lightbox-next', function(e) {
        e.stopPropagation();
        var nextIndex = (currentIndex === galleryImages.length - 1) ? 0 : currentIndex + 1;
        showImage(nextIndex);
    });

    // Thumbnail Click inside sidebar
    $(document).on('click', '.lightbox-sidebar-thumb', function() {
        var index = parseInt($(this).data('index'));
        showImage(index);
    });

    // Function to render active image and highlight sidebar thumbnail
    function showImage(index) {
        currentIndex = index;
        var data = galleryImages[index];

        // Animate main image transition
        $('.lightbox-main-img').css('opacity', '0');
        setTimeout(function() {
            $('.lightbox-main-img').attr('src', data.image).css('opacity', '1');
        }, 150);

        // Update descriptions
        $('#lb-title').text(data.title);
        $('#lb-category').text(data.category);
        $('#lb-date').text(data.date);

        // Details link condition
        if (data.url && data.url !== '') {
            $('#lb-link').attr('href', data.url).show();
        } else {
            $('#lb-link').hide();
        }

        // Highlight active thumbnail and scroll into view
        $('.lightbox-sidebar-thumb').removeClass('active');
        var activeThumb = $('#thumb-' + index);
        activeThumb.addClass('active');

        // Scroll sidebar
        var container = $('.lightbox-sidebar');
        container.animate({
            scrollTop: activeThumb.offset().top - container.offset().top + container.scrollTop() - 50
        }, 300);
    }

    // Keyboard navigation (Left, Right, Escape)
    $(document).keydown(function(e) {
        if ($('#custom-lightbox').is(':visible')) {
            if (e.keyCode === 37) { // Left arrow
                $('.lightbox-prev').click();
            } else if (e.keyCode === 39) { // Right arrow
                $('.lightbox-next').click();
            } else if (e.keyCode === 27) { // Escape
                $('.lightbox-close-btn').click();
            }
        }
    });
});
</script>
@endpush
