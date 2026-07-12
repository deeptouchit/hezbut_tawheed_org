@extends('theme::layouts.app')

@section('title', 'সাংস্কৃতিক কর্নার - হেযবুত তওহীদ')
@section('meta_description', 'হেযবুত তওহীদ আন্দোলনের দলীয় সঙ্গীত, দেশাত্মবোধক গান ও জাগরণী গান শুনুন এবং লিরিক্স পড়ুন।')

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'সাংস্কৃতিক কর্নার',
        'subtitle' => 'আন্দোলনের দলীয় সঙ্গীত, দেশাত্মবোধক গান ও জাগরণী গান শুনুন সরাসরি প্রিমিয়াম প্লেয়ারে',
        'badge_text' => 'সাংস্কৃতিক কর্নার',
        'badge_icon' => 'fas fa-music'
    ])

    <!-- Hidden HTML5 Audio Element -->
    <audio id="mainAudio" class="d-none"></audio>

    <!-- Main Player & Gallery Section (Theater Mode - Dark Theme Palette) -->
    <section class="py-5" style="background-color: #0b0f19; min-height: 70vh;" id="song-theater-section">
        <div class="container">
            
            @if(count($songs) > 0)
                @php
                    $firstSong = $featuredSong ?? $songs[0];
                @endphp
                
                <div class="row g-4">
                    <!-- Main Player Column (Left: col-lg-8) -->
                    <div class="col-lg-8">
                        <div class="player-wrapper mb-4">
                            
                            <!-- Video/Audio Player Card -->
                            <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-black player-container-card" id="player-card" style="aspect-ratio: 16/9; position: relative; border: 1px solid rgba(255,255,255,0.06) !important;">
                                
                                <!-- Audio Cover Art (Abstract Music Gradient) -->
                                <div class="audio-cover-art w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-gradient-brand {{ $firstSong->youtube_id ? 'd-none' : '' }}" id="audioPlaybackArea">
                                    <div class="cover-music-circle d-flex align-items-center justify-content-center shadow-lg">
                                        <i class="fas fa-compact-disc text-success spin-cd" id="playerCdIcon" style="font-size: 3.5rem; color: #ef4444 !important;"></i>
                                    </div>
                                    
                                    <!-- Simulated Equalizer Waveform Overlay -->
                                    <div class="equalizer-waveform d-flex gap-1.5 justify-content-center mt-3">
                                        <span class="eq-bar bar-1"></span>
                                        <span class="eq-bar bar-2"></span>
                                        <span class="eq-bar bar-3"></span>
                                        <span class="eq-bar bar-4"></span>
                                        <span class="eq-bar bar-5"></span>
                                        <span class="eq-bar bar-6"></span>
                                    </div>
                                </div>

                                <!-- YouTube Embedded Screen -->
                                <div class="w-100 h-100 position-relative {{ $firstSong->youtube_id ? '' : 'd-none' }}" id="youtubePlaybackArea">
                                    <iframe id="mainVideoPlayer" 
                                            class="w-100 h-100"
                                            src="{{ $firstSong->youtube_id ? 'https://www.youtube.com/embed/' . $firstSong->youtube_id . '?rel=0&autoplay=1' : '' }}" 
                                            title="Song Video Player" 
                                            frameborder="0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen></iframe>
                                </div>
                            </div>

                            <!-- Audio progress and controller panel (Visible only for Audio tracks) -->
                            <div class="card border-0 shadow-sm rounded-4 mt-3 p-4 {{ $firstSong->youtube_id ? 'd-none' : '' }}" id="audioControlsWrapper" style="background-color: rgba(15, 23, 42, 0.6) !important; border: 1px solid rgba(255,255,255,0.05) !important;">
                                <!-- Progress Seekbar -->
                                <div class="progress-container mb-3" id="audioProgressContainer">
                                    <div class="progress-bar-slider" id="progressWrapper" style="background-color: rgba(255,255,255,0.12) !important;">
                                        <div class="progress-fill" id="progressBarFill" style="background-color: #ef4444 !important;"></div>
                                        <div class="progress-dot" id="progressHandle" style="border-color: #ef4444 !important; box-shadow: 0 0 6px rgba(239, 68, 68, 0.4) !important;"></div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2 text-muted" style="font-size: 11px; font-family: 'Outfit', sans-serif; color: #94a3b8 !important;">
                                        <span id="currentTime">00:00</span>
                                        <span id="totalDuration">00:00</span>
                                    </div>
                                </div>

                                <!-- Audio Deck Controls -->
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <button class="btn-deck-control secondary-control" id="prevBtn" title="পূর্ববর্তী">
                                            <i class="fas fa-step-backward" style="color: #cbd5e1;"></i>
                                        </button>
                                        <button class="btn-deck-control play-control" id="playBtn" title="প্লে/পজ" style="background-color: #ef4444 !important; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.25) !important;">
                                            <i class="fas fa-play" id="playIcon"></i>
                                        </button>
                                        <button class="btn-deck-control secondary-control" id="nextBtn" title="পরবর্তী">
                                            <i class="fas fa-step-forward" style="color: #cbd5e1;"></i>
                                        </button>
                                    </div>

                                    <!-- Volume Slider -->
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-volume-up text-muted" id="volumeIcon" style="font-size: 12px; color: #94a3b8 !important;"></i>
                                        <input type="range" class="volume-slider" id="volumeSlider" min="0" max="1" step="0.05" value="0.8" style="width: 100px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Channel & Title Metadata Card -->
                            <div class="card border-0 shadow-sm rounded-4 mt-3 p-4" style="background-color: rgba(15, 23, 42, 0.6) !important; border: 1px solid rgba(255,255,255,0.05) !important;">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                                    <div>
                                        <span class="badge rounded-pill px-3 py-1.5 fw-bold mb-2" id="categoryBadge" style="font-family: 'Baloo Da 2', sans-serif; font-size: 11px; background-color: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.25);">
                                            @if($firstSong->category == 'party_anthem') দলীয় সঙ্গীত
                                            @elseif($firstSong->category == 'national') দেশাত্মবোধক গান
                                            @elseif($firstSong->category == 'awakening') জাগরণী গান
                                            @else {{ $firstSong->category }}
                                            @endif
                                        </span>
                                        <h3 class="fw-bold mb-1" id="mainSongTitle" style="font-family: 'Baloo Da 2', sans-serif; line-height: 1.4; font-size: 1.45rem; color: #ffffff !important;">
                                            {{ $firstSong->title }}
                                        </h3>
                                        <p class="text-danger small mb-0 d-flex align-items-center gap-1.5" style="font-family: 'Baloo Da 2', sans-serif; color: #f87171 !important; font-weight: 600;">
                                            <span>মাটি সাংস্কৃতিক গোষ্ঠী</span> <i class="fas fa-check-circle" style="font-size: 11px;"></i>
                                        </p>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <a id="downloadBtn" href="{{ $firstSong->audio_file ? $firstSong->audio_url : '#' }}" download class="btn-action-round-dark {{ $firstSong->audio_file ? '' : 'd-none' }}" title="ডাউনলোড করুন">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <span class="badge rounded-pill px-2.5 py-1.5 border" id="playbackSourceBadge" style="font-size: 10px; font-family: 'Baloo Da 2', sans-serif; background-color: rgba(0,0,0,0.5); border-color: rgba(255,255,255,0.15) !important; color: #cbd5e1;">
                                            {{ $firstSong->youtube_id ? 'YOUTUBE' : 'LOCAL AUDIO' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Lyrics Box -->
                                <div class="video-description-box p-3 rounded-3" style="background-color: rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.05);">
                                    <h5 class="fw-bold text-white mb-2" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.1rem; border-bottom: 1.5px solid rgba(255,255,255,0.08); padding-bottom: 6px;">
                                        গানের কথা / লিরিক্স
                                    </h5>
                                    <div class="text-center" id="mainSongLyrics" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.1rem; line-height: 2.1; color: #cbd5e1; white-space: pre-line;">
                                        {!! e($firstSong->lyrics) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sidebar List Column (Right: col-lg-4) -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 p-3 sticky-sidebar-card" style="background-color: rgba(15, 23, 42, 0.6) !important; border: 1px solid rgba(255,255,255,0.05) !important;">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
                                <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.05rem;">
                                    <span class="playlist-icon-wrap"><i class="fas fa-play-circle" style="color: #ef4444;"></i></span>
                                    গানের প্লেলিস্ট
                                </h5>
                                <span class="badge rounded-pill bg-dark text-muted border border-secondary" id="total-badge" style="font-family: 'Baloo Da 2', sans-serif; font-size: 10px; padding: 5px 10px; border-color: rgba(255,255,255,0.1) !important;">
                                    {{ count($songs) }} টি গান
                                </span>
                            </div>
                            
                            <div id="song-sidebar" class="d-flex flex-column gap-2" style="max-height: 62vh; overflow-y: auto; padding-right: 4px;">
                                @foreach($songs as $song)
                                    <div class="song-item d-flex gap-2 p-2 rounded-3 sidebar-trigger cursor-pointer transition border" 
                                         data-id="{{ $song->id }}" 
                                         data-title="{{ $song->title }}"
                                         data-audio="{{ $song->audio_url }}"
                                         data-youtube="{{ $song->youtube_id }}"
                                         data-lyrics="{{ e($song->lyrics) }}"
                                         data-category="{{ $song->category }}"
                                         style="border-color: rgba(255,255,255,0.04);">
                                         
                                        <!-- Thumbnail Left -->
                                        <div class="sidebar-thumb-container position-relative overflow-hidden rounded-3 flex-shrink-0" style="width: 110px; height: 62px; background-color: #05070c;">
                                            @if($song->youtube_id)
                                                <img src="{{ $song->thumbnail_url }}" class="w-100 h-100 object-cover" alt="thumbnail">
                                            @else
                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-brand">
                                                    <i class="fas fa-music text-white" style="font-size: 1rem;"></i>
                                                </div>
                                            @endif
                                            
                                            <!-- Tiny play button overlay -->
                                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center play-indicator-overlay" style="background-color: rgba(0, 0, 0, 0.4) !important;">
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
                                            <h6 class="sidebar-video-title text-white mb-1 text-truncate-2 fw-semibold" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.85rem; line-height: 1.4; color: #f8fafc !important;">
                                                {{ $song->title }}
                                            </h6>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="text-muted mb-0" style="font-size: 9.5px; font-family: 'Baloo Da 2', sans-serif;">মাটি সাংস্কৃতিক গোষ্ঠী</p>
                                                <span class="playing-text-badge text-danger d-none fw-bold" style="font-size: 8.5px; font-family: 'Baloo Da 2', sans-serif; letter-spacing: 0.5px; color: #ef4444 !important;">PLAYING</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Bottom Section: All Songs Grid -->
    <section class="py-5 border-top" style="background-color: #0b0f19; border-color: rgba(255,255,255,0.06) !important;">
        <div class="container">
            <div class="d-flex flex-column align-items-center mb-5 mt-2">
                <div class="search-box-container w-100 mb-4" style="max-width: 500px;">
                    <div class="position-relative">
                        <input type="text" id="songSearchInput" class="form-control" placeholder="গানের নাম বা লিরিক্স দিয়ে খুঁজুন..." style="font-family: 'Baloo Da 2', sans-serif; font-size: 14.5px; border-radius: 30px; padding: 12px 24px; border: 1px solid rgba(255,255,255,0.08); background-color: rgba(15, 23, 42, 0.7); color: #ffffff; outline: none; transition: all 0.25s ease;">
                        <span class="position-absolute end-0 top-50 translate-middle-y me-4" style="color: #cbd5e1;"><i class="fas fa-search"></i></span>
                    </div>
                </div>

                <div class="d-flex justify-content-center flex-wrap gap-2" id="filterContainer">
                    <button class="btn filter-btn active" data-filter="all">সব গান</button>
                    <button class="btn filter-btn" data-filter="party_anthem">দলীয় সঙ্গীত</button>
                    <button class="btn filter-btn" data-filter="national">দেশাত্মবোধক গান</button>
                    <button class="btn filter-btn" data-filter="awakening">জাগরণী গান</button>
                </div>
            </div>

            <!-- Grid -->
            <div class="row g-4 justify-content-center" id="songGrid">
                @foreach($songs as $song)
                    <div class="col-xl-3 col-lg-4 col-sm-6 song-card-item" data-category="{{ $song->category }}" id="song-card-{{ $song->id }}">
                        <div class="slider-song-card song-trigger" 
                             data-id="{{ $song->id }}"
                             data-title="{{ $song->title }}"
                             data-audio="{{ $song->audio_url }}"
                             data-youtube="{{ $song->youtube_id }}"
                             data-lyrics="{{ e($song->lyrics) }}"
                             data-category="{{ $song->category }}">
                            
                            <div class="slider-thumb-wrapper" style="background-color: #05070c;">
                                @if($song->youtube_id)
                                    <img src="{{ $song->thumbnail_url }}" class="w-100 h-100 object-cover" alt="thumbnail" loading="lazy">
                                @else
                                    <div class="song-card-album-art d-flex align-items-center justify-content-center bg-gradient-brand">
                                        <i class="fas fa-music text-white" style="font-size: 1.8rem;"></i>
                                    </div>
                                @endif
                                
                                <div class="glass-play-overlay">
                                    <div class="glass-play-circle"><i class="fas fa-play" style="color: #ef4444 !important;"></i></div>
                                </div>
                                <span class="video-duration-badge">{{ $song->youtube_id ? 'YOUTUBE' : 'AUDIO' }}</span>
                            </div>
                            
                            <h6 class="slider-card-title mt-3" style="font-family: 'Baloo Da 2', sans-serif; color: #f8fafc !important;">
                                {{ $song->title }}
                            </h6>
                            <p class="slider-card-channel-name mb-0 text-muted mt-1" style="font-family: 'Baloo Da 2', sans-serif; font-size: 11.5px; color: #94a3b8 !important;">
                                মাটি সাংস্কৃতিক গোষ্ঠী <i class="fas fa-check-circle text-danger" style="color: #ef4444 !important;"></i>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty State -->
            <div class="text-center py-5 d-none" id="emptySearchState">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h5 class="fw-bold" style="font-family: 'Baloo Da 2', sans-serif; color: #94a3b8;">আপনার অনুসন্ধানের সাথে মিলছে এমন কোনো গান পাওয়া যায়নি</h5>
            </div>

            <!-- Load More -->
            <div class="text-center mt-5" id="loadMoreContainer">
                <button class="btn btn-load-more" id="loadMoreBtn" style="color: #ef4444; border-color: #ef4444;">
                    আরো গান দেখুন <i class="fas fa-chevron-down ms-1"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Spotify-style Sticky Bottom Player Bar -->
    <div class="sticky-bottom-player d-none" id="stickyBottomPlayer" style="background: rgba(9, 13, 22, 0.95); border-top: 1px solid rgba(255, 255, 255, 0.06);">
        <div class="container h-100">
            <div class="d-flex align-items-center justify-content-between h-100 gap-3">
                
                <!-- Track Details -->
                <div class="d-flex align-items-center gap-3" style="min-width: 180px; max-width: 320px;">
                    <div class="sticky-thumb-wrapper flex-shrink-0" id="stickyThumbContainer" style="background-color: #090d16;">
                        <i class="fas fa-music text-white" id="stickyDefaultThumb"></i>
                        <img src="" class="w-100 h-100 object-cover d-none" id="stickyImgThumb" alt="thumb">
                    </div>
                    <div class="overflow-hidden">
                        <h6 class="sticky-track-title text-white mb-0 text-truncate" id="stickyTrackTitle">গানের নাম</h6>
                        <small class="text-muted text-truncate d-block" style="font-size: 11px; color: #94a3b8 !important;">মাটি সাংস্কৃতিক গোষ্ঠী</small>
                    </div>
                </div>

                <!-- Controls -->
                <div class="d-flex flex-column align-items-center gap-1.5 flex-grow-1" style="max-width: 480px;">
                    <div class="d-flex align-items-center gap-3">
                        <button class="sticky-control-btn" id="stickyPrevBtn"><i class="fas fa-step-backward" style="color: #cbd5e1;"></i></button>
                        <button class="sticky-play-btn" id="stickyPlayBtn" style="background-color: #ffffff; color: #090d16;"><i class="fas fa-play" id="stickyPlayIcon"></i></button>
                        <button class="sticky-control-btn" id="stickyNextBtn"><i class="fas fa-step-forward" style="color: #cbd5e1;"></i></button>
                    </div>
                    <div class="w-100 sticky-progress-container px-2">
                        <div class="sticky-slider-bar" id="stickyProgressWrapper">
                            <div class="sticky-slider-fill" id="stickyProgressBarFill" style="background-color: #ef4444 !important;"></div>
                        </div>
                    </div>
                </div>

                <!-- Utilities -->
                <div class="d-flex align-items-center gap-3 justify-content-end" style="min-width: 150px;">
                    <div class="d-flex align-items-center gap-2 d-none d-md-flex">
                        <i class="fas fa-volume-up text-muted" style="font-size: 12px; color: #cbd5e1;" id="stickyVolumeIcon"></i>
                        <input type="range" class="volume-slider" id="stickyVolumeSlider" min="0" max="1" step="0.05" value="0.8" style="width: 80px;">
                    </div>
                    <button class="sticky-control-btn ms-2 text-danger" id="stickyCloseBtn" title="প্লেয়ার বন্ধ করুন"><i class="fas fa-times-circle" style="font-size: 18px; color: #ef4444;"></i></button>
                </div>

            </div>
        </div>
    </div>

    <!-- Custom CSS Styles -->
    <style>
        .gap-1\.5 { gap: 6px; }
        .object-cover { object-fit: cover; }
        .z-3 { z-index: 3; position: relative; }

        .iframe-click-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: calc(100% - 40px);
            z-index: 5;
            cursor: pointer;
            background: transparent;
        }

        .btn-action-round-dark {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.08);
            color: #e2e8f0;
            border: 1px solid rgba(255, 255, 255, 0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .btn-action-round-dark:hover {
            background-color: #ef4444;
            color: #ffffff;
            border-color: #ef4444;
            box-shadow: 0 0 12px rgba(239, 68, 68, 0.4);
        }

        /* CD Spinner */
        .cover-music-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.05);
            border: 2px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .spin-cd {
            animation: rotateCD 15s linear infinite;
            animation-play-state: paused;
        }
        .spin-cd.playing {
            animation-play-state: running;
        }
        @keyframes rotateCD {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Simulated EQ Waveform */
        .equalizer-waveform {
            height: 20px;
            align-items: flex-end;
        }
        .eq-bar {
            width: 3.5px;
            height: 4px;
            background-color: #ef4444;
            border-radius: 10px;
            animation: eqWave 0.8s ease-in-out infinite alternate;
            animation-play-state: paused;
        }
        .spin-cd.playing ~ .equalizer-waveform .eq-bar {
            animation-play-state: running;
        }
        .bar-1 { animation-delay: 0.1s; height: 10px; }
        .bar-2 { animation-delay: 0.4s; height: 18px; }
        .bar-3 { animation-delay: 0.2s; height: 6px; }
        .bar-4 { animation-delay: 0.5s; height: 16px; }
        .bar-5 { animation-delay: 0.3s; height: 12px; }
        .bar-6 { animation-delay: 0.15s; height: 8px; }

        @keyframes eqWave {
            0% { height: 3px; }
            100% { height: 18px; }
        }

        /* Seekbar */
        .progress-bar-slider {
            width: 100%;
            height: 4px;
            background-color: rgba(255,255,255,0.12);
            border-radius: 10px;
            position: relative;
            cursor: pointer;
            transition: all 0.2s;
        }
        .progress-bar-slider:hover {
            height: 6px;
        }
        .progress-fill {
            height: 100%;
            background-color: #ef4444;
            width: 0%;
            border-radius: 10px;
        }
        .progress-dot {
            position: absolute;
            top: 50%;
            left: 0%;
            transform: translate(-50%, -50%);
            width: 11px;
            height: 11px;
            border-radius: 50%;
            background-color: #ffffff;
            border: 2px solid #ef4444;
            box-shadow: 0 0 6px rgba(239, 68, 68, 0.4);
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.15s;
        }
        .progress-bar-slider:hover .progress-dot {
            opacity: 1;
        }

        /* Control deck */
        .btn-deck-control {
            border: none;
            background: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
        }
        .secondary-control {
            font-size: 1.15rem;
            color: #cbd5e1;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
        }
        .secondary-control:hover {
            background-color: rgba(255,255,255,0.1);
            color: #ffffff;
        }
        .play-control {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background-color: #ef4444;
            color: #ffffff;
            font-size: 1.3rem;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.25);
        }
        .play-control:hover {
            transform: scale(1.06);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.35);
        }

        /* Volume Slider */
        .volume-slider {
            -webkit-appearance: none;
            appearance: none;
            background: rgba(255,255,255,0.12);
            height: 5px;
            border-radius: 10px;
            outline: none;
            cursor: pointer;
        }
        .volume-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ef4444;
            cursor: pointer;
            box-shadow: 0 0 4px rgba(239, 68, 68, 0.6);
            transition: transform 0.15s;
        }

        /* Sidebar Playlist items */
        .playlist-icon-wrap {
            display: inline-flex;
            animation: rotatePlaylistIcon 6s linear infinite;
        }
        @keyframes rotatePlaylistIcon {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .song-item {
            transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .song-item:hover {
            background-color: rgba(255,255,255,0.02);
            border-color: rgba(255,255,255,0.12) !important;
            transform: translateX(3px);
        }
        .song-item.active-item {
            background-color: rgba(239, 68, 68, 0.05);
            border-color: rgba(239, 68, 68, 0.25) !important;
        }
        .song-item.active-item .sidebar-video-title {
            color: #ef4444 !important;
            font-weight: 700;
        }
        .song-item.active-item .eq-overlay {
            display: flex !important;
        }
        .song-item.active-item .playing-text-badge {
            display: inline-block !important;
        }
        .song-item.active-item .play-indicator-overlay {
            display: none !important;
        }

        /* Sidebar scrollbar styles */
        #song-sidebar::-webkit-scrollbar {
            width: 4px;
        }
        #song-sidebar::-webkit-scrollbar-track {
            background: #0b0f19;
            border-radius: 10px;
        }
        #song-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }

        /* Search & Filters */
        #songSearchInput:focus {
            border-color: #ef4444 !important;
            box-shadow: 0 0 12px rgba(239, 68, 68, 0.2) !important;
        }
        .filter-btn {
            border: 1px solid rgba(255,255,255,0.1) !important;
            background: rgba(15, 23, 42, 0.5);
            color: #94a3b8;
            border-radius: 30px;
            padding: 8px 24px;
            font-family: 'Baloo Da 2', sans-serif;
            font-size: 13.5px;
            transition: all 0.2s ease;
        }
        .filter-btn:hover {
            background: rgba(255,255,255,0.05);
            color: #ffffff;
            border-color: rgba(255,255,255,0.2) !important;
        }
        .filter-btn.active {
            background: #ef4444 !important;
            color: #ffffff !important;
            border-color: #ef4444 !important;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }

        /* Bottom Song Card */
        .slider-song-card {
            background: rgba(15, 23, 42, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 16px;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            cursor: pointer;
            width: 100%;
        }
        .slider-song-card:hover {
            transform: translateY(-6px);
            border-color: rgba(239, 68, 68, 0.3);
            box-shadow: 0 12px 24px rgba(0,0,0,0.4);
        }
        .slider-song-card.now-playing {
            border-color: #ef4444 !important;
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.15) !important;
            background-color: rgba(239, 68, 68, 0.05) !important;
        }
        .slider-thumb-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 16/9;
            border-radius: 8px;
            overflow: hidden;
            background-color: #05070c;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        .song-card-album-art {
            width: 100%; height: 100%;
        }
        .bg-gradient-brand {
            background: linear-gradient(135deg, #1e2640 0%, #05070c 100%);
        }
        .glass-play-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(15, 23, 42, 0.15);
            opacity: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.2s ease;
        }
        .slider-song-card:hover .glass-play-overlay {
            opacity: 1;
        }
        .glass-play-circle {
            background-color: #ffffff;
            color: #ef4444;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .slider-song-card:hover .glass-play-circle {
            transform: scale(1.05);
        }

        .video-duration-badge {
            position: absolute;
            bottom: 6px;
            right: 6px;
            background-color: rgba(9, 13, 22, 0.85);
            color: #ffffff;
            font-size: 9.5px;
            font-weight: 700;
            padding: 2px 5px;
            border-radius: 3px;
        }
        .slider-card-title {
            font-size: 0.9rem;
            font-weight: 700;
            line-height: 1.45;
            color: #f8fafc;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            height: 40px;
        }
        .slider-song-card:hover .slider-card-title {
            color: #ef4444;
        }

        /* Load More Button */
        .btn-load-more {
            background: transparent;
            color: #ef4444;
            border: 1.5px solid #ef4444;
            border-radius: 30px;
            padding: 10px 40px;
            font-weight: 700;
            font-family: 'Baloo Da 2', sans-serif;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        .btn-load-more:hover {
            background: #ef4444;
            color: #ffffff;
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.2);
        }

        /* Spotify-style Sticky Bottom Player */
        .sticky-bottom-player {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 80px;
            background: rgba(9, 13, 22, 0.95);
            backdrop-filter: blur(15px);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            z-index: 999;
            box-shadow: 0 -8px 24px rgba(0,0,0,0.4);
            transform: translateY(0);
            transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .sticky-thumb-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 6px;
            background-color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.06);
        }
        .sticky-track-title {
            font-size: 13.5px;
            font-weight: 700;
            font-family: 'Baloo Da 2', sans-serif;
            max-width: 200px;
        }
        .sticky-control-btn {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 13px;
            transition: color 0.15s;
        }
        .sticky-control-btn:hover {
            color: #ffffff;
        }
        .sticky-play-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #ffffff;
            color: #0f172a;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            transition: transform 0.15s;
        }
        .sticky-play-btn:hover {
            transform: scale(1.08);
        }
        .sticky-slider-bar {
            width: 100%;
            height: 3.5px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 6px;
        }
        .sticky-slider-fill {
            height: 100%;
            background-color: #ef4444;
            width: 0%;
        }

        @media (min-width: 992px) {
            .sticky-sidebar-card {
                position: sticky;
                top: 85px;
                z-index: 10;
            }
        }
    </style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const audio = document.getElementById('mainAudio');
    const playBtn = document.getElementById('playBtn');
    const playIcon = document.getElementById('playIcon');
    const progressWrapper = document.getElementById('progressWrapper');
    const progressBarFill = document.getElementById('progressBarFill');
    const progressHandle = document.getElementById('progressHandle');
    const currentTimeEl = document.getElementById('currentTime');
    const totalDurationEl = document.getElementById('totalDuration');
    const playerCdIcon = document.getElementById('playerCdIcon');
    const volumeSlider = document.getElementById('volumeSlider');
    const volumeIcon = document.getElementById('volumeIcon');

    const stickyBottomPlayer = document.getElementById('stickyBottomPlayer');
    const stickyPlayBtn = document.getElementById('stickyPlayBtn');
    const stickyPlayIcon = document.getElementById('stickyPlayIcon');
    const stickyProgressBarFill = document.getElementById('stickyProgressBarFill');
    const stickyProgressWrapper = document.getElementById('stickyProgressWrapper');
    const stickyTrackTitle = document.getElementById('stickyTrackTitle');
    const stickyImgThumb = document.getElementById('stickyImgThumb');
    const stickyDefaultThumb = document.getElementById('stickyDefaultThumb');
    const stickyVolumeSlider = document.getElementById('stickyVolumeSlider');
    const stickyVolumeIcon = document.getElementById('stickyVolumeIcon');

    const songItems = document.querySelectorAll('.song-item');
    const songCardsGrid = document.querySelectorAll('.song-card-item');
    const mainSongTitle = document.getElementById('mainSongTitle');
    const mainSongLyrics = document.getElementById('mainSongLyrics');

    const audioPlaybackArea = document.getElementById('audioPlaybackArea');
    const youtubePlaybackArea = document.getElementById('youtubePlaybackArea');
    const mainVideoPlayer = document.getElementById('mainVideoPlayer');
    const playbackSourceBadge = document.getElementById('playbackSourceBadge');
    const downloadBtn = document.getElementById('downloadBtn');
    const categoryBadge = document.getElementById('categoryBadge');
    const audioControlsWrapper = document.getElementById('audioControlsWrapper');

    const categoryNames = {
        'party_anthem': 'দলীয় সঙ্গীত',
        'national': 'দেশাত্মবোধক গান',
        'awakening': 'জাগরণী গান'
    };

    // Load Playlist items with fallback checks
    const playlist = Array.from(songItems).map(item => {
        const youtubeId = item.getAttribute('data-youtube') || '';
        return {
            id: item.getAttribute('data-id') || '',
            title: item.getAttribute('data-title') || '',
            audio: item.getAttribute('data-audio') || '',
            youtube: youtubeId,
            lyrics: item.getAttribute('data-lyrics') || '',
            category: item.getAttribute('data-category') || '',
            thumb: youtubeId ? `https://img.youtube.com/vi/${youtubeId}/mqdefault.jpg` : ''
        };
    });

    let currentTrackIndex = 0;
    window.isTrackPlaying = false;
    window.currentTrack = playlist[0] || null;

    // Set first active
    if (songItems.length > 0) {
        songItems[0].classList.add('active-item');
    }

    // Toggle Play/Pause for Audio
    function togglePlay() {
        if (!audio) return;
        const track = playlist[currentTrackIndex];
        if (track && track.youtube) return;

        if (!audio.src) return;
        if (audio.paused) {
            audio.play().catch(err => console.log('Audio autoplay blocked or interrupted'));
            window.isTrackPlaying = true;
            updatePlayPauseUI(true);
        } else {
            audio.pause();
            window.isTrackPlaying = false;
            updatePlayPauseUI(false);
        }
    }

    if (playBtn) playBtn.addEventListener('click', togglePlay);
    if (stickyPlayBtn) stickyPlayBtn.addEventListener('click', togglePlay);

    function updatePlayPauseUI(playing) {
        if (playIcon) playIcon.className = playing ? 'fas fa-pause' : 'fas fa-play';
        if (stickyPlayIcon) stickyPlayIcon.className = playing ? 'fas fa-pause' : 'fas fa-play';
        if (playerCdIcon) {
            if (playing) playerCdIcon.classList.add('playing');
            else playerCdIcon.classList.remove('playing');
        }
        
        // Highlight active track
        const activeTrack = playlist[currentTrackIndex];
        if (!activeTrack) return;
        
        songItems.forEach(item => {
            if (item.getAttribute('data-id') === activeTrack.id) {
                item.classList.add('active-item');
            } else {
                item.classList.remove('active-item');
            }
        });

        const allSongTriggers = document.querySelectorAll('.song-trigger');
        allSongTriggers.forEach(card => {
            if (card.getAttribute('data-id') === activeTrack.id) {
                card.classList.add('now-playing');
            } else {
                card.classList.remove('now-playing');
            }
        });
    }

    // Audio metadata & progress updates
    if (audio) {
        audio.addEventListener('timeupdate', function () {
            const track = playlist[currentTrackIndex];
            if (track && track.youtube) return; 

            if (audio.duration) {
                const percent = (audio.currentTime / audio.duration) * 100;
                if (progressBarFill) progressBarFill.style.width = `${percent}%`;
                if (progressHandle) progressHandle.style.left = `${percent}%`;
                if (currentTimeEl) currentTimeEl.textContent = formatTimeHelper(audio.currentTime);
                if (stickyProgressBarFill) stickyProgressBarFill.style.width = `${percent}%`;
            }
        });

        audio.addEventListener('loadedmetadata', function () {
            if (totalDurationEl) totalDurationEl.textContent = formatTimeHelper(audio.duration);
        });

        audio.addEventListener('ended', function () {
            updatePlayPauseUI(false);
            nextTrack();
        });
    }

    // Seek Click
    if (progressWrapper) {
        progressWrapper.addEventListener('click', function (e) {
            if (!audio) return;
            const width = this.clientWidth;
            const clickX = e.offsetX;
            if (audio.duration) {
                audio.currentTime = (clickX / width) * audio.duration;
            }
        });
    }

    // Volume change
    function setVolume(val) {
        if (!audio) return;
        audio.volume = val;
        if (volumeSlider) volumeSlider.value = val;
        if (stickyVolumeSlider) stickyVolumeSlider.value = val;

        let iconClass = 'fas fa-volume-up';
        if (val == 0) {
            iconClass = 'fas fa-volume-mute';
        } else if (val < 0.5) {
            iconClass = 'fas fa-volume-down';
        }

        if (volumeIcon) volumeIcon.className = iconClass;
        if (stickyVolumeIcon) stickyVolumeIcon.className = iconClass;
    }

    if (volumeSlider) volumeSlider.addEventListener('input', function() { setVolume(this.value); });
    if (stickyVolumeSlider) stickyVolumeSlider.addEventListener('input', function() { setVolume(this.value); });

    function formatTimeHelper(secs) {
        if (isNaN(secs)) return '00:00';
        const minutes = Math.floor(secs / 60);
        const seconds = Math.floor(secs % 60);
        return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    // Load Track
    function loadTrack(index) {
        if (index >= 0 && index < playlist.length) {
            currentTrackIndex = index;
            const track = playlist[index];
            window.currentTrack = track;
            
            // Text sync
            if (mainSongTitle) mainSongTitle.textContent = track.title;
            if (stickyTrackTitle) stickyTrackTitle.textContent = track.title;
            if (mainSongLyrics) mainSongLyrics.innerHTML = track.lyrics || 'কোনো লিরিক্স নেই।';
            if (categoryBadge) categoryBadge.textContent = categoryNames[track.category] || track.category;

            // Reset progress
            if (progressBarFill) progressBarFill.style.width = '0%';
            if (progressHandle) progressHandle.style.left = '0%';
            if (currentTimeEl) currentTimeEl.textContent = '00:00';
            if (totalDurationEl) totalDurationEl.textContent = '00:00';

            // Stop audio
            if (audio) {
                audio.pause();
            }
            updatePlayPauseUI(false);

            if (track.youtube) {
                // Show YouTube, Hide Audio
                if (audioPlaybackArea) audioPlaybackArea.classList.add('d-none');
                if (youtubePlaybackArea) youtubePlaybackArea.classList.remove('d-none');
                if (audioControlsWrapper) audioControlsWrapper.classList.add('d-none');
                
                if (playbackSourceBadge) {
                    playbackSourceBadge.textContent = "YOUTUBE";
                    playbackSourceBadge.className = "badge bg-danger text-white rounded-pill px-2.5 py-1.5 border border-danger";
                }
                if (downloadBtn) downloadBtn.classList.add('d-none');
                if (stickyBottomPlayer) stickyBottomPlayer.classList.add('d-none');

                // Load YouTube video in iframe directly
                if (mainVideoPlayer) mainVideoPlayer.src = `https://www.youtube.com/embed/${track.youtube}?autoplay=1&rel=0`;
            } else {
                // Show Audio, Hide YouTube
                if (youtubePlaybackArea) youtubePlaybackArea.classList.add('d-none');
                if (mainVideoPlayer) mainVideoPlayer.src = ""; // Reset video
                
                if (audioPlaybackArea) audioPlaybackArea.classList.remove('d-none');
                if (audioControlsWrapper) audioControlsWrapper.classList.remove('d-none');
                
                if (downloadBtn) {
                    if (track.audio) {
                        downloadBtn.href = track.audio;
                        downloadBtn.classList.remove('d-none');
                    } else {
                        downloadBtn.classList.add('d-none');
                    }
                }
                
                if (playbackSourceBadge) {
                    playbackSourceBadge.textContent = "LOCAL AUDIO";
                    playbackSourceBadge.className = "badge bg-success text-white rounded-pill px-2.5 py-1.5 border border-success";
                }

                if (audio) {
                    audio.src = track.audio;
                    audio.load();
                    audio.play().catch(err => console.log('Autoplay block handled'));
                }
                window.isTrackPlaying = true;
                updatePlayPauseUI(true);

                checkStickyVisibility();
            }
        }
    }

    function prevTrack() {
        let newIndex = currentTrackIndex - 1;
        if (newIndex < 0) newIndex = playlist.length - 1;
        loadTrack(newIndex);
    }

    function nextTrack() {
        let newIndex = currentTrackIndex + 1;
        if (newIndex >= playlist.length) newIndex = 0;
        loadTrack(newIndex);
    }

    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const stickyPrevBtn = document.getElementById('stickyPrevBtn');
    const stickyNextBtn = document.getElementById('stickyNextBtn');

    if (prevBtn) prevBtn.addEventListener('click', prevTrack);
    if (nextBtn) nextBtn.addEventListener('click', nextTrack);
    if (stickyPrevBtn) stickyPrevBtn.addEventListener('click', prevTrack);
    if (stickyNextBtn) stickyNextBtn.addEventListener('click', nextTrack);

    // Robust Event Delegation for Click Triggers (Handles Children Click Properly)
    document.addEventListener('click', function(e) {
        const trigger = e.target.closest('.sidebar-trigger, .song-trigger');
        if (trigger) {
            const songId = trigger.getAttribute('data-id');
            const foundIndex = playlist.findIndex(p => String(p.id) === String(songId));
            if (foundIndex !== -1) {
                loadTrack(foundIndex);
                const theater = document.getElementById('song-theater-section');
                if (theater) {
                    window.scrollTo({ top: theater.offsetTop - 20, behavior: 'smooth' });
                }
            }
        }
    });

    // Sticky Player Close
    const stickyCloseBtn = document.getElementById('stickyCloseBtn');
    if (stickyCloseBtn) {
        stickyCloseBtn.addEventListener('click', function() {
            if (audio) audio.pause();
            updatePlayPauseUI(false);
            if (stickyBottomPlayer) stickyBottomPlayer.classList.add('d-none');
        });
    }

    // Sticky visibility
    function checkStickyVisibility() {
        const theaterSection = document.getElementById('song-theater-section');
        if (!theaterSection) return;

        const theaterBottom = theaterSection.offsetTop + theaterSection.clientHeight;
        const currentScroll = window.scrollY || window.pageYOffset;
        const activeTrack = playlist[currentTrackIndex];

        if (currentScroll > theaterBottom && activeTrack && !activeTrack.youtube && window.isTrackPlaying) {
            if (stickyBottomPlayer) stickyBottomPlayer.classList.remove('d-none');
        } else {
            if (stickyBottomPlayer) stickyBottomPlayer.classList.add('d-none');
        }
    }

    window.addEventListener('scroll', checkStickyVisibility);

    // Filter, Search, Load More (Safe against missing elements)
    const songSearchInput = document.getElementById('songSearchInput');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const emptySearchState = document.getElementById('emptySearchState');
    
    let visibleCount = 8;
    const itemsPerLoad = 8;
    let currentFilter = 'all';
    let searchQuery = '';

    function applyFilterAndLimits() {
        let matchCount = 0;

        songCardsGrid.forEach(card => {
            const category = card.getAttribute('data-category') || '';
            const titleEl = card.querySelector('.slider-card-title');
            const title = titleEl ? titleEl.textContent.toLowerCase() : '';
            const triggerEl = card.querySelector('.song-trigger');
            const lyrics = triggerEl ? (triggerEl.getAttribute('data-lyrics') || '').toLowerCase() : '';
            
            const matchesFilter = (currentFilter === 'all' || category === currentFilter);
            const matchesSearch = (searchQuery === '' || title.includes(searchQuery) || lyrics.includes(searchQuery));

            if (matchesFilter && matchesSearch) {
                matchCount++;
                if (matchCount <= visibleCount) {
                    card.classList.remove('d-none');
                } else {
                    card.classList.add('d-none');
                }
            } else {
                card.classList.add('d-none');
            }
        });

        const totalMatches = Array.from(songCardsGrid).filter(card => {
            const category = card.getAttribute('data-category') || '';
            const titleEl = card.querySelector('.slider-card-title');
            const title = titleEl ? titleEl.textContent.toLowerCase() : '';
            const triggerEl = card.querySelector('.song-trigger');
            const lyrics = triggerEl ? (triggerEl.getAttribute('data-lyrics') || '').toLowerCase() : '';
            
            const matchesFilter = (currentFilter === 'all' || category === currentFilter);
            const matchesSearch = (searchQuery === '' || title.includes(searchQuery) || lyrics.includes(searchQuery));
            
            return matchesFilter && matchesSearch;
        }).length;

        if (totalMatches === 0) {
            if (emptySearchState) emptySearchState.classList.remove('d-none');
            if (loadMoreBtn) loadMoreBtn.classList.add('d-none');
        } else {
            if (emptySearchState) emptySearchState.classList.add('d-none');
            if (visibleCount >= totalMatches) {
                if (loadMoreBtn) loadMoreBtn.classList.add('d-none');
            } else {
                if (loadMoreBtn) loadMoreBtn.classList.remove('d-none');
            }
        }
    }

    if (songSearchInput) {
        songSearchInput.addEventListener('input', function() {
            searchQuery = this.value.toLowerCase().trim();
            visibleCount = 8;
            applyFilterAndLimits();
        });
    }

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.getAttribute('data-filter') || 'all';
            visibleCount = 8;
            applyFilterAndLimits();
        });
    });

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            visibleCount += itemsPerLoad;
            applyFilterAndLimits();
        });
    }

    applyFilterAndLimits();
});
</script>
@endpush
