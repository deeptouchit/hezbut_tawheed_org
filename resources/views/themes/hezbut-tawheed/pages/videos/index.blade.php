@extends('theme::layouts.app')

@section('title', 'ভিডিও গ্যালারী - হেযবুত তওহীদ')
@section('meta_description', 'হেযবুত তওহীদ আন্দোলনের জুমার বয়ান, আলোচনা সভা, প্রশ্ন-উত্তর পর্ব এবং বিভিন্ন সামাজিক ও উন্নয়নমূলক কার্যক্রমের ভিডিওসমূহ')

@section('content')

    <!-- Banner Header -->
    <div class="py-4 text-white position-relative" style="background: linear-gradient(rgba(0,106,78,0.9), rgba(0,106,78,0.9)), url('https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?q=80&w=1200') no-repeat center center; background-size: cover; border-bottom: 4px solid #10B981;">
        <div class="container text-center">
            <h1 class="fw-bold mb-1 text-white" style="font-family: 'Baloo Da 2', sans-serif; font-size: 2.2rem;"><i class="fab fa-youtube me-2 text-danger"></i> ভিডিও গ্যালারী</h1>
            <p class="lead small mb-0 opacity-90 text-white" style="font-family: 'Baloo Da 2', sans-serif;">হেযবুত তওহীদের নীতি, আদর্শ ও সাম্প্রতিক কার্যক্রমের ওপর ভিডিওসমূহ সরাসরি প্লেয়ারে দেখুন</p>
        </div>
    </div>

    <!-- Main Player & Gallery Section -->
    <section class="py-5 bg-off-white" style="background-color: #f4f6f8;">
        <div class="container">
            
            @if(count($videos) > 0)
                @php
                    $firstVideo = $videos[0];
                @endphp
                
                <!-- Main Featured Player Section -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5 bg-white p-3" id="player-section" style="border-top: 4px solid #006A4E !important;">
                    <div class="row g-4">
                        <!-- Left Column: Video Iframe -->
                        <div class="col-lg-8">
                            <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-sm bg-black">
                                <iframe id="mainVideoPlayer" 
                                        src="https://www.youtube.com/embed/{{ $firstVideo->youtube_id }}" 
                                        title="Hezbut Tawheed Video Player" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen></iframe>
                            </div>
                        </div>
                        
                        <!-- Right Column: Video Details -->
                        <div class="col-lg-4 d-flex flex-column justify-content-between">
                            <div>
                                <span class="badge bg-danger mb-2"><i class="fas fa-play me-1"></i> এখন প্লে হচ্ছে</span>
                                <h4 class="fw-bold text-dark-green mb-3" id="mainVideoTitle" style="font-family: 'Baloo Da 2', sans-serif; line-height: 1.45; font-size: 1.3rem;">
                                    {{ $firstVideo->title }}
                                </h4>
                                <div class="text-muted small overflow-auto" id="mainVideoDesc" style="font-family: 'Baloo Da 2', sans-serif; max-height: 180px; text-align: justify; line-height: 1.7;">
                                    {{ $firstVideo->description ?? 'এই ভিডিওটির কোনো বিবরণী যুক্ত করা হয়নি।' }}
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-3 border-top border-light">
                                <a href="https://www.youtube.com/@hezbuttawheed" target="_blank" class="btn btn-outline-danger btn-sm rounded-pill fw-bold w-100 py-2">
                                    <i class="fab fa-youtube me-1"></i> ইউটিউব চ্যানেল সাবস্ক্রাইব করুন
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Header: Other Videos -->
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom border-light pb-2">
                    <h4 class="fw-bold text-dark-green mb-0" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-th-list me-1 text-gold"></i> অন্যান্য ভিডিওসমূহ</h4>
                    <span class="text-muted small">যেকোনো ভিডিওর ওপর ক্লিক করলে সেটি উপরের প্লেয়ারে লোড হবে</span>
                </div>

                <!-- Video Grid -->
                <div class="row g-4">
                    @foreach($videos as $video)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white video-card-trigger cursor-pointer hover-grow transition" 
                                 data-video-id="{{ $video->youtube_id }}" 
                                 data-title="{{ $video->title }}" 
                                 data-description="{{ $video->description ?? 'এই ভিডিওটির কোনো বিবরণী যুক্ত করা হয়নি।' }}">
                                
                                <!-- Thumbnail -->
                                <div class="position-relative overflow-hidden" style="height: 200px;">
                                    <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="w-100 h-100" style="object-fit: cover; transition: transform 0.3s;">
                                    <!-- Hover Overlay -->
                                    <div class="play-grid-overlay d-flex align-items-center justify-content-center">
                                        <div class="play-grid-btn">
                                            <i class="fas fa-play text-white"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Body -->
                                <div class="card-body p-3">
                                    <h6 class="fw-bold text-dark-green mb-2 text-truncate-2" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1rem; line-height: 1.45; min-height: 2.7rem;">
                                        {{ $video->title }}
                                    </h6>
                                    @if($video->description)
                                        <p class="text-muted small mb-0 text-truncate-2" style="font-family: 'Baloo Da 2', sans-serif; line-height: 1.6;">
                                            {{ strip_tags($video->description) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($videos instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $videos->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        {{ $videos->links('pagination::bootstrap-5') }}
                    </div>
                @endif
                
            @else
                <div class="text-center py-5">
                    <i class="fab fa-youtube fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">বর্তমানে কোনো ভিডিও পাওয়া যায়নি</h4>
                    <p class="text-muted small">এডমিন প্যানেল থেকে নতুন ইউটিউব ভিডিও যুক্ত করুন।</p>
                </div>
            @endif
            
        </div>
    </section>

    <!-- Custom CSS Styles -->
    <style>
        .hover-grow {
            transition: all 0.3s ease;
        }
        .hover-grow:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
        }
        .text-dark-green {
            color: #006A4E !important;
        }
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Video Grid Play Button Overlay */
        .video-card-trigger {
            cursor: pointer;
        }
        .play-grid-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 106, 78, 0.4);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .video-card-trigger:hover .play-grid-overlay {
            opacity: 1;
        }
        .video-card-trigger:hover img {
            transform: scale(1.05);
        }
        .play-grid-btn {
            background-color: #ff0000;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            transition: transform 0.2s;
        }
        .play-grid-btn i {
            font-size: 1.1rem;
            margin-left: 3px; /* Align triangle */
        }
        .video-card-trigger:hover .play-grid-btn {
            transform: scale(1.1);
        }
    </style>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.video-card-trigger').on('click', function(e) {
        e.preventDefault();
        
        var videoId = $(this).data('video-id');
        var title = $(this).data('title');
        var description = $(this).data('description');
        
        // Update Video Iframe src (with autoplay)
        $('#mainVideoPlayer').attr('src', 'https://www.youtube.com/embed/' + videoId + '?autoplay=1');
        
        // Update Title and Description in Sidebar
        $('#mainVideoTitle').text(title);
        $('#mainVideoDesc').text(description);
        
        // Smooth scroll to player card
        $('html, body').animate({
            scrollTop: $("#player-section").offset().top - 30
        }, 400);
    });
});
</script>
@endpush


