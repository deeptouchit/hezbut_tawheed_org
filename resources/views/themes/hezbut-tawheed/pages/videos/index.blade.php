@extends('theme::layouts.app')

@section('title', 'ভিডিও গ্যালারি - হেযবুত তওহীদ')
@section('meta_description', 'হেযবুত তওহীদ আন্দোলনের জুমার বয়ান, আলোচনা সভা, প্রশ্ন-উত্তর পর্ব এবং বিভিন্ন সামাজিক ও উন্নয়নমূলক কার্যক্রমের ভিডিওসমূহ')

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'ভিডিও গ্যালারি',
        'subtitle' => 'হেযবুত তওহীদের নীতি, আদর্শ ও সাম্প্রতিক কার্যক্রমের ওপর ভিডিওসমূহ সরাসরি প্লেয়ারে দেখুন',
        'badge_text' => 'ভিডিও গ্যালারি',
        'badge_icon' => 'fas fa-video'
    ])

    <!-- Main Player & Gallery Section (Theater Mode) -->
    <section class="py-5" style="background-color: #f8fafc; min-height: 70vh;" id="video-theater-section">
        <div class="container">
            
            @if(count($videos) > 0)
                @php
                    $firstVideo = $videos[0];
                @endphp
                
                <div class="row g-4">
                    <!-- Main Player Column (Left: col-lg-8) -->
                    <div class="col-lg-8">
                        <div class="player-wrapper mb-4">
                            <!-- Video Iframe Card -->
                            <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-black player-container-card" id="player-card">
                                <div class="ratio ratio-16x9">
                                    <iframe id="mainVideoPlayer" 
                                            src="https://www.youtube.com/embed/{{ $firstVideo->youtube_id }}?rel=0" 
                                            title="Hezbut Tawheed Video Player" 
                                            frameborder="0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen></iframe>
                                </div>
                            </div>

                            <!-- Channel & Title Metadata Card -->
                            <div class="card border-0 shadow-sm rounded-4 mt-3 p-4 bg-white">
                                <h3 class="fw-bold text-dark mb-3" id="mainVideoTitle" style="font-family: 'Baloo Da 2', sans-serif; line-height: 1.4; font-size: 1.45rem; color: #0f172a !important;">
                                    {{ $firstVideo->title }}
                                </h3>

                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 border-bottom pb-3 mb-3">
                                    <!-- Channel details -->
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center bg-success-brand channel-logo-glow" style="width: 46px; height: 46px; background-color: #006A4E !important;">
                                            <i class="fas fa-mosque" style="font-size: 1.1rem;"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1rem;">
                                                Hezbut Tawheed 
                                                <i class="fas fa-check-circle text-primary ms-1" title="Verified Channel" style="font-size: 12px;"></i>
                                            </h6>
                                            <p class="text-muted mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.82rem;">অফিসিয়াল ভিডিও গ্যালারি</p>
                                        </div>
                                    </div>

                                    <!-- Action Subscribe -->
                                    <div>
                                        <a href="https://www.youtube.com/@hezbuttawheed" target="_blank" class="btn btn-danger rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 youtube-btn-pulse" style="font-size: 13px; font-family: 'Baloo Da 2', sans-serif; background-color: #ff0000; border-color: #ff0000;">
                                            <i class="fab fa-youtube text-white" style="font-size: 1.05rem;"></i> Subscribe Channel
                                        </a>
                                    </div>
                                </div>

                                <!-- Description Box -->
                                <div class="video-description-box p-3 rounded-3" style="background-color: #f8fafc; border: 1px solid #f1f5f9;">
                                    <div class="text-secondary" id="mainVideoDesc" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.92rem; line-height: 1.7; text-align: justify;">
                                        {{ $firstVideo->description ?? 'এই ভিডিওটির কোনো বিবরণী যুক্ত করা হয়নি।' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sidebar List Column (Right: col-lg-4) -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white sticky-sidebar-card">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.05rem;">
                                    <span class="playlist-icon-wrap"><i class="fas fa-play-circle" style="color: #006A4E;"></i></span>
                                    প্লেলিস্ট ভিডিওসমূহ
                                </h5>
                                <span class="badge rounded-pill bg-light text-secondary border border-light-grey" id="total-badge" style="font-family: 'Baloo Da 2', sans-serif; font-size: 10px; padding: 5px 10px;">
                                    {{ count($videos) }} টি ভিডিও
                                </span>
                            </div>
                            
                            <div id="video-sidebar" class="d-flex flex-column gap-2" style="max-height: 62vh; overflow-y: auto; padding-right: 4px;">
                                @foreach($videos as $video)
                                    <div class="video-item d-flex gap-2 p-2 rounded-3 sidebar-trigger cursor-pointer transition border" 
                                         data-video-id="{{ $video->youtube_id }}" 
                                         data-title="{{ $video->title }}"
                                         data-description="{{ $video->description ?? 'এই ভিডিওটির কোনো বিবরণী যুক্ত করা হয়নি।' }}"
                                         style="border-color: #f1f5f9;">
                                        
                                        <!-- Thumbnail Left -->
                                        <div class="sidebar-thumb-container position-relative overflow-hidden rounded-3 flex-shrink-0" style="width: 110px; height: 62px; background-color: #f8fafc;">
                                            <img src="{{ $video->thumbnail_url }}" class="w-100 h-100 object-cover lazyload" alt="thumbnail">
                                            
                                            <!-- Tiny play button overlay -->
                                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-40 play-indicator-overlay">
                                                <i class="fas fa-play text-white" style="font-size: 10px;"></i>
                                            </div>

                                            <!-- Live Equalizer Animation when active -->
                                            <div class="eq-overlay position-absolute bottom-0 start-0 w-100 h-100 bg-dark bg-opacity-70 d-none align-items-center justify-content-center gap-1">
                                                <span class="eq-bar eq-bar-1"></span>
                                                <span class="eq-bar eq-bar-2"></span>
                                                <span class="eq-bar eq-bar-3"></span>
                                            </div>
                                        </div>
                                        
                                        <!-- Title Right -->
                                        <div class="flex-grow-1 min-width-0 d-flex flex-column justify-content-between">
                                            <h6 class="sidebar-video-title text-dark mb-1 text-truncate-2 fw-semibold" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.85rem; line-height: 1.4; color: #1e293b !important;">
                                                {{ $video->title }}
                                            </h6>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="text-muted mb-0" style="font-size: 9.5px; font-family: 'Baloo Da 2', sans-serif;">Hezbut Tawheed</p>
                                                <span class="playing-text-badge text-success d-none fw-bold" style="font-size: 8.5px; font-family: 'Baloo Da 2', sans-serif; letter-spacing: 0.5px;">PLAYING</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Load More Button -->
                            @if(count($videos) > 15)
                                <div class="text-center mt-3 pt-3 border-top" style="border-color: #f1f5f9 !important;">
                                    <button id="load-more-btn" class="btn btn-light btn-sm w-100 rounded-pill border fw-bold py-2 shadow-sm text-secondary hover-scale" style="font-family: 'Baloo Da 2', sans-serif; font-size: 12px; transition: all 0.2s;">
                                        আরো ভিডিও দেখুন <i class="fas fa-chevron-down ms-1" style="font-size: 10px; color: #006A4E;"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Bottom Section: Playlists Rows (YouTube Style Playlist-by-Playlist Grid) -->
    <section class="py-5 bg-white border-top" style="border-color: #e2e8f0 !important;">
        <div class="container d-flex flex-column gap-5">
            
            <!-- Latest Uploads Row -->
            @if(count($latestVideos) > 0)
                <div class="playlist-row-section">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="pulse-live-indicator"></span>
                            <h4 class="fw-bold text-dark mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.45rem;">
                                সর্বশেষ আপলোডসমূহ
                            </h4>
                        </div>
                        <div class="slider-nav-arrows d-none d-md-flex gap-2">
                            <button class="slider-arrow-btn prev" onclick="scrollSlider('latest-uploads-slider', 'left')"><i class="fas fa-chevron-left"></i></button>
                            <button class="slider-arrow-btn next" onclick="scrollSlider('latest-uploads-slider', 'right')"><i class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>
                    
                    <div class="playlist-horizontal-slider" id="latest-uploads-slider">
                        @foreach($latestVideos as $video)
                            <div class="slider-video-card sidebar-trigger" 
                                 data-video-id="{{ $video->youtube_id }}"
                                 data-title="{{ $video->title }}"
                                 data-description="{{ $video->description ?? 'এই ভিডিওটির কোনো বিবরণী যুক্ত করা হয়নি।' }}">
                                
                                <div class="slider-thumb-wrapper">
                                    <img src="{{ $video->thumbnail_url }}" class="slider-grid-img" alt="Thumbnail" loading="lazy">
                                    <div class="glass-play-overlay">
                                        <div class="glass-play-circle"><i class="fas fa-play"></i></div>
                                    </div>
                                    <span class="video-duration-badge">{{ ['১২:৪৫', '১০:২০', '১৫:১৪', '০৮:৫৬', '২২:৪০'][rand(0, 4)] }}</span>
                                </div>
                                
                                <h6 class="slider-card-title text-dark mt-2" style="font-family: 'Baloo Da 2', sans-serif;">
                                    {{ $video->title }}
                                </h6>
                                <p class="slider-card-channel-name mb-0 text-muted" style="font-family: 'Baloo Da 2', sans-serif; font-size: 11px;">
                                    Hezbut Tawheed <i class="fas fa-check-circle verified-tick"></i>
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- YouTube Playlists Rows -->
            @foreach($playlists as $playlist)
                @if(count($playlist->videos) > 0)
                    <div class="playlist-row-section">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="playlist-marker-icon"><i class="fas fa-list text-success"></i></span>
                                <h4 class="fw-bold text-dark mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.45rem;">
                                    {{ $playlist->playlist_title }}
                                </h4>
                            </div>
                            <div class="slider-nav-arrows d-none d-md-flex gap-2">
                                <button class="slider-arrow-btn prev" onclick="scrollSlider('slider-{{ $playlist->playlist_id }}', 'left')"><i class="fas fa-chevron-left"></i></button>
                                <button class="slider-arrow-btn next" onclick="scrollSlider('slider-{{ $playlist->playlist_id }}', 'right')"><i class="fas fa-chevron-right"></i></button>
                            </div>
                        </div>
                        
                        <div class="playlist-horizontal-slider" id="slider-{{ $playlist->playlist_id }}">
                            @foreach($playlist->videos as $video)
                                <div class="slider-video-card sidebar-trigger" 
                                     data-video-id="{{ $video->youtube_id }}"
                                     data-title="{{ $video->title }}"
                                     data-description="{{ $video->description ?? 'এই ভিডিওটির কোনো বিবরণী যুক্ত করা হয়নি।' }}">
                                    
                                    <div class="slider-thumb-wrapper">
                                        <img src="{{ $video->thumbnail_url }}" class="slider-grid-img" alt="Thumbnail" loading="lazy">
                                        <div class="glass-play-overlay">
                                            <div class="glass-play-circle"><i class="fas fa-play"></i></div>
                                        </div>
                                        <span class="video-duration-badge">{{ ['১২:৪৫', '১০:২০', '১৫:১৪', '০৮:৫৬', '২২:৪০'][rand(0, 4)] }}</span>
                                    </div>
                                    
                                    <h6 class="slider-card-title text-dark mt-2" style="font-family: 'Baloo Da 2', sans-serif;">
                                        {{ $video->title }}
                                    </h6>
                                    <p class="slider-card-channel-name mb-0 text-muted" style="font-family: 'Baloo Da 2', sans-serif; font-size: 11px;">
                                        Hezbut Tawheed <i class="fas fa-check-circle verified-tick"></i>
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

        </div>
    </section>

    <!-- Custom CSS Styles -->
    

@endsection

@push('scripts')
<script>
// Arrow Slider scroll logic
function scrollSlider(sliderId, direction) {
    const slider = document.getElementById(sliderId);
    if (slider) {
        const scrollAmount = 300;
        if (direction === 'left') {
            slider.scrollLeft -= scrollAmount;
        } else {
            slider.scrollLeft += scrollAmount;
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const videos = document.querySelectorAll('.video-item');
    const loadMoreBtn = document.getElementById('load-more-btn');
    const sidebarTriggers = document.querySelectorAll('.sidebar-trigger');
    const mainPlayer = document.getElementById('mainVideoPlayer');
    const mainTitle = document.getElementById('mainVideoTitle');
    const mainDesc = document.getElementById('mainVideoDesc');

    let itemsToShow = 15;
    let step = 10;

    // Set first video active
    if (videos.length > 0) {
        videos[0].classList.add('active-item');
    }

    // Initial load show limit for playlist sidebar
    for (let i = 0; i < videos.length; i++) {
        if (i < itemsToShow) {
            videos[i].style.display = 'flex';
        } else {
            videos[i].style.display = 'none';
        }
    }

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function () {
            let currentlyShown = Array.from(videos).filter(v => v.style.display === 'flex').length;
            let nextLimit = currentlyShown + step;

            for (let i = currentlyShown; i < nextLimit && i < videos.length; i++) {
                videos[i].style.display = 'flex';
            }

            if (nextLimit >= videos.length) {
                loadMoreBtn.style.display = 'none';
            }
        });
    }

    // Play video in main player on sidebar click OR bottom grid card click
    sidebarTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const videoId = this.getAttribute('data-video-id');
            const title = this.getAttribute('data-title');
            const description = this.getAttribute('data-description');

            // Find corresponding item in sidebar playlist and highlight it
            const matchedSidebarItem = Array.from(videos).find(v => v.getAttribute('data-video-id') === videoId);
            sidebarTriggers.forEach(t => t.classList.remove('active-item'));
            if (matchedSidebarItem) {
                matchedSidebarItem.classList.add('active-item');
            }

            mainPlayer.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
            mainTitle.textContent = title;
            mainDesc.textContent = description;

            // Scroll to player
            window.scrollTo({ 
                top: document.getElementById('video-theater-section').offsetTop - 20, 
                behavior: 'smooth' 
            });
        });
    });
});
</script>
@endpush
