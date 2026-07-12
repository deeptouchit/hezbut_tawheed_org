@extends('theme::layouts.app')

@section('title', 'সাংগঠনিক কাঠামো - হেযবুত তওহীদ')
@section('meta_description', 'হেযবুত তওহীদ আন্দোলনের সাংগঠনিক কাঠামো, নেতৃত্ব এবং সাংগঠনিক স্তর বিন্যাস ডায়াগ্রাম')

@push('styles')

@endpush

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'সাংগঠনিক কাঠামো',
        'subtitle' => 'হেযবুত তওহীদের নেতৃত্ব এবং সাংগঠনিক স্তর বিন্যাস ডায়াগ্রাম',
        'badge_text' => 'আন্দোলনের স্তর বিন্যাস',
        'badge_icon' => 'fas fa-sitemap'
    ])

    <!-- Org Chart Canvas Section -->
    <section class="py-5" style="background-color: #f8fafc;">
        <div class="container">
            
            <!-- Sandbox Container -->
            <div class="org-viewer-sandbox shadow-sm">
                
                <!-- Floating Control Panel -->
                <div class="viewer-controls">
                    <button class="control-btn" id="zoom-in-btn" title="জুম ইন"><i class="fas fa-plus"></i></button>
                    <button class="control-btn" id="zoom-out-btn" title="জুম আউট"><i class="fas fa-minus"></i></button>
                    <button class="control-btn" id="zoom-reset-btn" title="রিসেট"><i class="fas fa-compress-arrows-alt"></i></button>
                </div>
                
                <!-- Pannable Scroll Wrapper -->
                <div class="org-chart-wrapper" id="panning-container">
                    <div class="org-chart">
                        @include('theme::pages.partials.org_chart_node', ['leaders' => $treeLeaders])
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- Slide-in Profile Drawer Backdrop overlay -->
    <div id="drawer-backdrop" class="drawer-backdrop" style="display: none;"></div>

    <!-- Frosted-Glass Slide-in Profile Drawer -->
    <div id="profile-drawer" class="profile-drawer shadow-lg">
        <div class="drawer-header d-flex align-items-center justify-content-between p-3.5 bg-white border-bottom">
            <h5 class="fw-bold text-dark-green mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.1rem;"><i class="fas fa-user-circle me-2 text-success"></i> প্রোফাইল বিবরণী</h5>
            <button id="close-drawer-btn" class="btn btn-close border-0 bg-transparent" aria-label="Close"><i class="fas fa-times text-muted fs-5"></i></button>
        </div>
        
        <div class="drawer-body p-4 overflow-auto">
            <!-- Loading Indicator -->
            <div id="drawer-loading" class="text-center py-5">
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">লোড হচ্ছে...</span>
                </div>
                <div class="text-muted small mt-2">প্রোফাইল বিবরণ লোড হচ্ছে...</div>
            </div>

            <!-- Loaded profile content -->
            <div id="drawer-content" style="display: none;">
                <!-- Profile picture & main titles -->
                <div class="text-center mb-4">
                    <div class="overflow-hidden rounded shadow mx-auto mb-3" style="width: 130px; height: 170px; background-color: #f1f5f9;">
                        <img src="" id="dr-image" alt="" class="w-100 h-100" style="object-fit: cover;">
                    </div>
                    <h5 class="fw-bold text-dark-green mb-1" id="dr-name" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.3rem;"></h5>
                    <span class="badge bg-green-soft text-success px-3 py-2 rounded-pill fw-bold" id="dr-designation" style="font-size: 11px; font-family: 'Baloo Da 2', sans-serif;"></span>
                </div>

                <!-- Bio / Message -->
                <div class="mb-4">
                    <h6 class="fw-bold text-dark-green mb-2 border-bottom pb-2" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-quote-left me-1 text-warning"></i> পরিচিতি</h6>
                    <p class="text-muted small" id="dr-bio" style="text-align: justify; line-height: 1.7; font-family: 'Baloo Da 2', sans-serif; font-size: 13px;"></p>
                </div>

                <!-- Quote Box (Dynamic representation) -->
                <div class="card border-0 bg-light p-3 mb-4 rounded-3 d-none" id="dr-quote-card">
                    <p class="mb-0 text-muted fst-italic small" style="line-height: 1.6; font-family: 'Baloo Da 2', sans-serif;">
                        <i class="fas fa-quote-left text-success opacity-50 me-1"></i> <span id="dr-quote"></span>
                    </p>
                </div>

                <!-- Videos section -->
                <div class="mb-4 d-none" id="dr-video-section">
                    <h6 class="fw-bold text-dark-green mb-2 border-bottom pb-2" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-video me-1 text-danger"></i> বক্তব্য ও সাক্ষাৎকার</h6>
                    <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm">
                        <iframe id="dr-video-frame" src="" allowfullscreen></iframe>
                    </div>
                </div>

                <!-- Social media icons -->
                <div class="mb-4 text-center">
                    <div class="d-flex justify-content-center gap-2" id="dr-socials">
                        <!-- Filled in dynamically -->
                    </div>
                </div>

                <!-- Authored Books section -->
                <div class="mb-4 d-none" id="dr-books-section">
                    <h6 class="fw-bold text-dark-green mb-2 border-bottom pb-2" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-book me-1 text-warning"></i> নেতার রচিত বইসমূহ</h6>
                    <div class="row g-3" id="dr-books-list">
                        <!-- Filled in dynamically -->
                    </div>
                </div>

                <!-- Direct link profile page button -->
                <a href="" id="dr-full-profile-link" class="btn btn-success w-100 rounded-pill py-3 fw-bold text-white mb-3 shadow-sm" style="font-family: 'Baloo Da 2', sans-serif;">
                    <i class="fas fa-external-link-alt me-2"></i> পূর্ণাঙ্গ প্রোফাইল পেজ
                </a>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    // --------------------------------------------------
    // 1. Zoom and Pan Interactive Controls
    // --------------------------------------------------
    const slider = document.getElementById('panning-container');
    let isDown = false;
    let startX;
    let startY;
    let scrollLeft;
    let scrollTop;

    // Center chart scroll initially
    setTimeout(() => {
        slider.scrollLeft = (slider.scrollWidth - slider.clientWidth) / 2;
        slider.scrollTop = 0;
    }, 100);

    // Mouse drag scroll panning
    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        slider.classList.add('active-dragging');
        startX = e.pageX - slider.offsetLeft;
        startY = e.pageY - slider.offsetTop;
        scrollLeft = slider.scrollLeft;
        scrollTop = slider.scrollTop;
    });
    slider.addEventListener('mouseleave', () => {
        isDown = false;
        slider.classList.remove('active-dragging');
    });
    slider.addEventListener('mouseup', () => {
        isDown = false;
        slider.classList.remove('active-dragging');
    });
    slider.addEventListener('mousemove', (e) => {
        if(!isDown) return;
        e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        const y = e.pageY - slider.offsetTop;
        const walkX = (x - startX) * 1.5; // multiplier controls speed
        const walkY = (y - startY) * 1.5;
        slider.scrollLeft = scrollLeft - walkX;
        slider.scrollTop = scrollTop - walkY;
    });

    // Zoom level logic
    let currentZoom = 1.0;
    const chart = document.querySelector('.org-chart');

    $('#zoom-in-btn').on('click', function() {
        if(currentZoom < 1.6) {
            currentZoom += 0.1;
            chart.style.transform = `scale(${currentZoom})`;
        }
    });

    $('#zoom-out-btn').on('click', function() {
        if(currentZoom > 0.6) {
            currentZoom -= 0.1;
            chart.style.transform = `scale(${currentZoom})`;
        }
    });

    $('#zoom-reset-btn').on('click', function() {
        currentZoom = 1.0;
        chart.style.transform = `scale(1.0)`;
        slider.scrollLeft = (slider.scrollWidth - slider.clientWidth) / 2;
        slider.scrollTop = 0;
    });

    // --------------------------------------------------
    // 2. Profile Drawer Logic (AJAX details)
    // --------------------------------------------------
    function openDrawer(id) {
        $('#profile-drawer').addClass('open');
        $('#drawer-backdrop').fadeIn(200);
        $('body').css('overflow', 'hidden'); // disable scroll

        // Show loading and hide content
        $('#drawer-loading').show();
        $('#drawer-content').hide();

        $.ajax({
            url: "{{ url('/leadership/ajax') }}/" + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var leader = response.leader;
                    var books = response.books;

                    // Fill fields
                    $('#dr-image').attr('src', response.image_url);
                    $('#dr-name').text(leader.name);
                    $('#dr-designation').text(leader.designation);
                    $('#dr-bio').text(leader.bio || 'পরিচিতি এখনও যোগ করা হয়নি।');
                    
                    // Direct Profile URL
                    $('#dr-full-profile-link').attr('href', "{{ url('/leadership') }}/" + leader.slug);

                    // Quote Card
                    if (leader.quote) {
                        $('#dr-quote').text(leader.quote);
                        $('#dr-quote-card').removeClass('d-none');
                    } else {
                        $('#dr-quote-card').addClass('d-none');
                    }

                    // Video speech
                    if (leader.speech_video_url) {
                        var videoUrl = leader.speech_video_url;
                        if (videoUrl.includes('youtube.com/watch') || videoUrl.includes('youtu.be/')) {
                            var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                            var match = videoUrl.match(regExp);
                            if (match && match[2].length == 11) {
                                videoUrl = "https://www.youtube.com/embed/" + match[2] + "?autoplay=1";
                            }
                        }
                        $('#dr-video-frame').attr('src', videoUrl);
                        $('#dr-video-section').removeClass('d-none');
                    } else {
                        $('#dr-video-frame').attr('src', '');
                        $('#dr-video-section').addClass('d-none');
                    }

                    // Social links
                    var socialsHtml = '';
                    if (leader.facebook_url) {
                        socialsHtml += '<a href="' + leader.facebook_url + '" target="_blank" class="btn btn-outline-primary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Facebook"><i class="fab fa-facebook-f"></i></a>';
                    }
                    if (leader.twitter_url) {
                        socialsHtml += '<a href="' + leader.twitter_url + '" target="_blank" class="btn btn-outline-info btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Twitter"><i class="fab fa-twitter"></i></a>';
                    }
                    if (leader.linkedin_url) {
                        socialsHtml += '<a href="' + leader.linkedin_url + '" target="_blank" class="btn btn-outline-primary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>';
                    }
                    if (leader.email) {
                        socialsHtml += '<a href="mailto:' + leader.email + '" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Email"><i class="fas fa-envelope"></i></a>';
                    }
                    $('#dr-socials').html(socialsHtml);

                    // Authored Books
                    if (books.length > 0) {
                        var booksHtml = '';
                        books.forEach(function(book) {
                            booksHtml += '<div class="col-12">' +
                                            '<div class="drawer-book-card">' +
                                                '<div class="overflow-hidden rounded shadow-sm bg-light" style="width: 38px; height: 56px; flex-shrink: 0;">' +
                                                    '<img src="' + book.image + '" alt="' + book.title + '" class="w-100 h-100" style="object-fit: cover;">' +
                                                '</div>' +
                                                '<div class="flex-grow-1">' +
                                                    '<div class="fw-bold text-dark-green" style="font-size: 12px; line-height: 1.35; font-family: \'Baloo Da 2\', sans-serif;">' + book.title + '</div>' +
                                                    '<a href="' + book.read_url + '" class="btn btn-success btn-xs mt-2 px-3 py-1 fw-bold" style="font-size: 9px;"><i class="fas fa-book-open me-1"></i> পড়ুন</a>' +
                                                '</div>' +
                                            '</div>' +
                                         '</div>';
                        });
                        $('#dr-books-list').html(booksHtml);
                        $('#dr-books-section').removeClass('d-none');
                    } else {
                        $('#dr-books-section').addClass('d-none');
                    }

                    // Hide loading and show content
                    $('#drawer-loading').hide();
                    $('#drawer-content').fadeIn(200);
                } else {
                    closeDrawer();
                    toastr.error('প্রোফাইল বিবরণ লোড করতে ব্যর্থ হয়েছে');
                }
            },
            error: function() {
                closeDrawer();
                toastr.error('সার্ভারে সমস্যা হয়েছে');
            }
        });
    }

    function closeDrawer() {
        $('#profile-drawer').removeClass('open');
        $('#drawer-backdrop').fadeOut(200);
        $('body').css('overflow', 'auto');
        
        // Clean video to stop playing in background
        setTimeout(function() {
            $('#dr-video-frame').attr('src', '');
        }, 400);
    }

    // Trigger click on profile cards inside tree
    $(document).on('click', '.org-node-card', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        openDrawer(id);
    });

    // Close button click
    $('#close-drawer-btn, #drawer-backdrop').on('click', function() {
        closeDrawer();
    });

});
</script>
@endpush
