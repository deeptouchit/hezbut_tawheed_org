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
    <section class="py-5" style="background-color: #0f172a; min-height: 70vh;" id="video-theater-section">
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
                            <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-black player-container-card" id="player-card" style="border: 1px solid #334155;">
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
                            <div class="card border-0 shadow-sm rounded-4 mt-3 p-4" style="background-color: #1e293b; border: 1px solid #334155;">
                                <h3 class="fw-bold text-white mb-3" id="mainVideoTitle" style="font-family: 'Baloo Da 2', sans-serif; line-height: 1.4; font-size: 1.45rem; color: #ffffff !important;">
                                    {{ $firstVideo->title }}
                                </h3>

                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 border-bottom pb-3 mb-3" style="border-color: #334155 !important;">
                                    <!-- Channel details -->
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center channel-logo-glow" style="width: 46px; height: 46px; background-color: #ef4444 !important; color: #ffffff;">
                                            <i class="fas fa-mosque" style="font-size: 1.1rem;"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0 fw-bold text-white d-flex align-items-center" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1rem;">
                                                Hezbut Tawheed 
                                                <i class="fas fa-check-circle text-danger ms-1" title="Verified Channel" style="font-size: 12px;"></i>
                                            </h6>
                                            <p class="mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.82rem; color: #94a3b8;">অফিসিয়াল ভিডিও গ্যালারি</p>
                                        </div>
                                    </div>

                                    <!-- Action Subscribe -->
                                    <div>
                                        <a href="https://www.youtube.com/@hezbuttawheed" target="_blank" class="btn btn-danger rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 youtube-btn-pulse" style="font-size: 13px; font-family: 'Baloo Da 2', sans-serif; background-color: #ef4444; border-color: #ef4444;">
                                            <i class="fab fa-youtube text-white" style="font-size: 1.05rem;"></i> Subscribe Channel
                                        </a>
                                    </div>
                                </div>

                                <!-- Description Box -->
                                <div class="video-description-box p-3 rounded-3" style="background-color: #0f172a; border: 1px solid #334155;">
                                    <div class="text-secondary" id="mainVideoDesc" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.92rem; line-height: 1.7; text-align: justify; color: #cbd5e1 !important;">
                                        {{ $firstVideo->description ?? 'এই ভিডিওটির কোনো বিবরণী যুক্ত করা হয়নি।' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sidebar List Column (Right: col-lg-4) -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 p-3 sticky-sidebar-card" style="background-color: #1e293b; border: 1px solid #334155;">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: #334155 !important;">
                                <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.05rem;">
                                    <span class="playlist-icon-wrap"><i class="fas fa-play-circle" style="color: #ef4444;"></i></span>
                                    প্লেলিস্ট ভিডিওসমূহ
                                </h5>
                                <span class="badge rounded-pill text-white border border-secondary" id="total-badge" style="background-color: #0f172a; font-family: 'Baloo Da 2', sans-serif; font-size: 10px; padding: 5px 10px; border-color: #334155 !important;">
                                    {{ count($videos) }} টি ভিডিও
                                </span>
                            </div>
                            
                            <div id="video-sidebar" class="d-flex flex-column gap-2" style="max-height: 62vh; overflow-y: auto; padding-right: 4px;">
                                @foreach($videos as $video)
                                    <div class="video-item d-flex gap-2 p-2 rounded-3 sidebar-trigger cursor-pointer transition border" 
                                         data-video-id="{{ $video->youtube_id }}" 
                                         data-title="{{ $video->title }}"
                                         data-description="{{ $video->description ?? 'এই ভিডিওটির কোনো বিবরণী যুক্ত করা হয়নি।' }}"
                                         style="border-color: #334155;">
                                        
                                        <!-- Thumbnail Left -->
                                        <div class="sidebar-thumb-container position-relative overflow-hidden rounded-3 flex-shrink-0" style="width: 110px; height: 62px; background-color: #0f172a;">
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
                                            <h6 class="sidebar-video-title text-white mb-1 text-truncate-2 fw-semibold" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.85rem; line-height: 1.4; color: #ffffff !important;">
                                                {{ $video->title }}
                                            </h6>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="mb-0" style="font-size: 9.5px; font-family: 'Baloo Da 2', sans-serif; color: #94a3b8;">Hezbut Tawheed</p>
                                                <span class="playing-text-badge text-danger d-none fw-bold" style="font-size: 8.5px; font-family: 'Baloo Da 2', sans-serif; letter-spacing: 0.5px;">PLAYING</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Load More Button -->
                            @if(count($videos) > 15)
                                <div class="text-center mt-3 pt-3 border-top" style="border-color: #334155 !important;">
                                    <button id="load-more-btn" class="btn btn-dark btn-sm w-100 rounded-pill border fw-bold py-2 shadow-sm text-secondary hover-scale" style="background-color: #0f172a; border-color: #334155; font-family: 'Baloo Da 2', sans-serif; font-size: 12px; transition: all 0.2s; color: #cbd5e1 !important;">
                                        আরো ভিডিও দেখুন <i class="fas fa-chevron-down ms-1" style="font-size: 10px; color: #ef4444;"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Bottom Section: Playlists Rows (YouTube Style Playlist-by-Playlist Grid - Dark Theme) -->
    <section class="py-5 border-top" style="background-color: #1e293b; border-color: #334155 !important;">
        <div class="container d-flex flex-column gap-5">
            
            <!-- Latest Uploads Row -->
            @if(count($latestVideos) > 0)
                <div class="playlist-row-section">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="pulse-live-indicator"></span>
                            <h4 class="fw-bold text-white mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.45rem; color: #ffffff !important;">
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
                                
                                <div class="slider-thumb-wrapper" style="background-color: #0f172a;">
                                    <img src="{{ $video->thumbnail_url }}" class="slider-grid-img" alt="Thumbnail" loading="lazy">
                                    <div class="glass-play-overlay">
                                        <div class="glass-play-circle" style="background-color: #ffffff; color: #ef4444;"><i class="fas fa-play"></i></div>
                                    </div>
                                    <span class="video-duration-badge">{{ ['১২:৪৫', '১০:২০', '১৫:১৪', '০৮:৫৬', '২২:৪০'][rand(0, 4)] }}</span>
                                </div>
                                
                                <h6 class="slider-card-title text-white mt-2" style="font-family: 'Baloo Da 2', sans-serif; color: #ffffff !important;">
                                    {{ $video->title }}
                                </h6>
                                <p class="slider-card-channel-name mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 11px; color: #cbd5e1;">
                                    Hezbut Tawheed <i class="fas fa-check-circle verified-tick" style="color: #ef4444;"></i>
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
                                <span class="playlist-marker-icon" style="background-color: rgba(239, 68, 68, 0.1) !important;"><i class="fas fa-list text-danger" style="color: #ef4444 !important;"></i></span>
                                <h4 class="fw-bold text-white mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.45rem; color: #ffffff !important;">
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
                                    
                                    <div class="slider-thumb-wrapper" style="background-color: #0f172a;">
                                        <img src="{{ $video->thumbnail_url }}" class="slider-grid-img" alt="Thumbnail" loading="lazy">
                                        <div class="glass-play-overlay">
                                            <div class="glass-play-circle" style="background-color: #ffffff; color: #ef4444;"><i class="fas fa-play"></i></div>
                                        </div>
                                        <span class="video-duration-badge">{{ ['১২:৪৫', '১০:২০', '১৫:১৪', '০৮:৫৬', '২২:৪০'][rand(0, 4)] }}</span>
                                    </div>
                                    
                                    <h6 class="slider-card-title text-white mt-2" style="font-family: 'Baloo Da 2', sans-serif; color: #ffffff !important;">
                                        {{ $video->title }}
                                    </h6>
                                    <p class="slider-card-channel-name mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 11px; color: #cbd5e1;">
                                        Hezbut Tawheed <i class="fas fa-check-circle verified-tick" style="color: #ef4444;"></i>
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
    <style>
        .object-cover {
            object-fit: cover;
        }

        /* Pulse Indicator */
        .pulse-live-indicator {
            width: 8px;
            height: 8px;
            background-color: #ef4444;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            animation: pulse-live 1.6s infinite;
        }
        @keyframes pulse-live {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        /* Playlist Icon Marker */
        .playlist-marker-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Horizontal Slider Layout */
        .playlist-horizontal-slider {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            padding-bottom: 15px;
            -webkit-overflow-scrolling: touch;
        }
        .playlist-horizontal-slider::-webkit-scrollbar {
            height: 6px;
        }
        .playlist-horizontal-slider::-webkit-scrollbar-track {
            background: #0f172a;
            border-radius: 10px;
        }
        .playlist-horizontal-slider::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }
        .playlist-horizontal-slider::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        /* Video Card in Slider */
        .slider-video-card {
            min-width: 260px;
            width: 260px;
            scroll-snap-align: start;
            cursor: pointer;
            transition: transform 0.22s ease;
        }
        .slider-video-card:hover {
            transform: translateY(-4px);
        }
        .slider-thumb-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 16/9;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 8px rgba(0,0,0,0.2);
        }
        .slider-grid-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        .slider-video-card:hover .slider-grid-img {
            transform: scale(1.05);
        }

        /* Play Button Overlay */
        .glass-play-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15, 23, 42, 0.35);
            opacity: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.22s ease;
        }
        .slider-video-card:hover .glass-play-overlay {
            opacity: 1;
        }
        .glass-play-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            transform: scale(0.85);
            transition: transform 0.22s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .glass-play-circle i {
            font-size: 12px;
            margin-left: 2px;
        }
        .slider-video-card:hover .glass-play-circle {
            transform: scale(1);
        }

        /* Video Duration */
        .video-duration-badge {
            position: absolute;
            bottom: 6px;
            right: 6px;
            background-color: rgba(15, 23, 42, 0.85);
            color: #ffffff;
            font-size: 9.5px;
            font-weight: 700;
            padding: 2px 5px;
            border-radius: 3px;
            font-family: 'Baloo Da 2', sans-serif;
        }

        /* Title and Meta */
        .slider-card-title {
            font-size: 0.88rem;
            font-weight: 700;
            line-height: 1.45;
            margin-bottom: 2px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: color 0.2s ease;
        }
        .slider-video-card:hover .slider-card-title {
            color: #ef4444 !important;
        }
        .verified-tick {
            font-size: 9px;
            margin-left: 2px;
        }

        /* Navigation Arrows */
        .slider-arrow-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 1px solid #334155;
            background-color: #1e293b;
            color: #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .slider-arrow-btn:hover {
            background-color: #0f172a;
            color: #ef4444;
            border-color: #475569;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        /* Layout styles for top player */
        .player-container-card {
            box-shadow: 0 20px 40px rgba(239, 68, 68, 0.08) !important;
            border: 1px solid rgba(239, 68, 68, 0.05);
        }
        .channel-logo-glow {
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.15);
        }
        .youtube-btn-pulse:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4) !important;
        }
        .playlist-icon-wrap {
            display: inline-flex;
            animation: rotatePlaylistIcon 6s linear infinite;
        }
        @keyframes rotatePlaylistIcon {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Hover item bg */
        .video-item {
            transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .video-item:hover {
            background-color: #0f172a;
            border-color: rgba(239, 68, 68, 0.4) !important;
            transform: translateX(3px);
        }
        
        .video-item.active-item {
            background-color: rgba(239, 68, 68, 0.15);
            border-color: #ef4444 !important;
        }
        .video-item.active-item .sidebar-video-title {
            color: #ef4444 !important;
        }
        .video-item.active-item .eq-overlay {
            display: flex !important;
        }
        .video-item.active-item .playing-text-badge {
            display: inline-block !important;
        }
        .video-item.active-item .play-indicator-overlay {
            display: none !important;
        }

        /* Equalizer Bars Animation */
        .eq-bar {
            width: 3px;
            height: 15px;
            background-color: #ef4444;
            animation: bounceEq 1s ease-in-out infinite alternate;
        }
        .eq-bar-1 { animation-delay: 0.1s; }
        .eq-bar-2 { animation-delay: 0.3s; height: 18px; }
        .eq-bar-3 { animation-delay: 0.5s; }

        @keyframes bounceEq {
            0% { transform: scaleY(0.3); }
            100% { transform: scaleY(1); }
        }

        .play-indicator-overlay {
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        .video-item:hover .play-indicator-overlay {
            opacity: 1;
        }

        /* Sticky Sidebar for desktop */
        @media (min-width: 992px) {
            .sticky-sidebar-card {
                position: sticky;
                top: 85px;
                z-index: 10;
            }
        }

        /* Scrollbar styles for sidebar */
        #video-sidebar::-webkit-scrollbar {
            width: 4px;
        }
        #video-sidebar::-webkit-scrollbar-track {
            background: #0f172a;
            border-radius: 10px;
        }
        #video-sidebar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }
        #video-sidebar::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>

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
