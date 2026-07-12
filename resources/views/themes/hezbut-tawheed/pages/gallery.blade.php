@extends('theme::layouts.app')

@section('title', 'চিত্রশালা - হেযবুত তওহীদ')

@push('styles')

@endpush

@section('content')
@include('theme::partials.hero_banner', [
    'title' => 'স্থিরচিত্র চিত্রশালা',
    'subtitle' => 'আন্দোলনের বিভিন্ন কর্মসূচী, সেমিনার ও সামাজিক কার্যক্রমের গ্যালারি',
    'badge_text' => 'চিত্রশালা ও স্থিরচিত্র',
    'badge_icon' => 'fas fa-images'
])

<!-- Gallery Grid Section -->
<div class="py-6 bg-light">
    <div class="container">
        @if(count($galleryPosts) > 0)
            <div class="gallery-grid">
                @include('theme::pages.gallery_cards')
            </div>

            <!-- Custom Load More Button -->
            @if($galleryPosts->hasMorePages())
                <div class="text-center mt-5" id="load-more-container">
                    <button id="load-more-btn" class="btn btn-outline-success px-5 py-2 fw-bold" style="border: 2px solid #10b981; border-radius: 30px; color: #022c22;" data-page="2">
                        <i class="fas fa-sync-alt me-2"></i> আরো ছবি লোড করুন
                    </button>
                </div>
            @endif
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

    // ============================================
    // Load More Images via AJAX
    // ============================================
    $(document).on('click', '#load-more-btn', function() {
        var btn = $(this);
        var page = parseInt(btn.attr('data-page'));
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> লোড হচ্ছে...');

        $.ajax({
            url: "{{ route('gallery.index') }}?page=" + page + "&ajax=1",
            type: "GET",
            success: function(response) {
                if (response.success && response.html) {
                    // 1. Append new cards to grid
                    $('.gallery-grid').append(response.html);

                    // 2. Append new images to the galleryImages JS array & sidebar thumbnails
                    response.newImages.forEach(function(imgData) {
                        var globalIndex = galleryImages.length;
                        imgData.index = globalIndex;
                        galleryImages.push(imgData);

                        // Append thumbnail to sidebar
                        var thumbHtml = '<div class="lightbox-sidebar-thumb" id="thumb-' + globalIndex + '" data-index="' + globalIndex + '">' +
                                            '<img src="' + imgData.image + '" alt="">' +
                                        '</div>';
                        $('.lightbox-sidebar').append(thumbHtml);
                    });

                    // 3. Update load more button
                    if (response.hasMore) {
                        btn.attr('data-page', page + 1);
                        btn.prop('disabled', false).html('<i class="fas fa-sync-alt me-2"></i> আরো ছবি লোড করুন');
                    } else {
                        $('#load-more-container').fadeOut(400, function() {
                            $(this).remove();
                        });
                    }
                }
            },
            error: function() {
                alert('ছবি লোড করতে ব্যর্থ হয়েছে');
                btn.prop('disabled', false).html('<i class="fas fa-sync-alt me-2"></i> আরো ছবি লোড করুন');
            }
        });
    });

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
