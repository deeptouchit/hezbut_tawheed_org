@extends('theme::layouts.app')

@section('title', 'মূল পাতা - মানবতার কল্যাণে একটি অরাজনৈতিক আন্দোলন')

@push('styles')
<style>
    .carousel-item {
        height: 75vh;
        min-height: 480px;
        position: relative;
        overflow: hidden;
        background-color: #0b1a13;
    }
    .carousel-item-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        z-index: 1;
        transition: transform 8s ease-out;
    }
    .carousel-item.active .carousel-item-bg {
        transform: scale(1.08);
    }
    .carousel-item-overlay {
        position: relative;
        z-index: 2;
    }
    .text-shadow-premium {
        text-shadow: 0 4px 15px rgba(0, 0, 0, 0.75);
    }
    .slider-title {
        font-family: 'Baloo Da 2', sans-serif;
        font-weight: 800;
        line-height: 1.25;
        letter-spacing: -0.5px;
        color: #ffffff !important; /* Force crisp white color on headings */
    }
    .slider-subtitle {
        font-family: 'Baloo Da 2', sans-serif;
        font-size: 1.25rem;
        font-weight: 500;
        max-width: 720px;
        opacity: 0.95;
        color: #f8f9fa !important; /* Force off-white for description text */
    }
    .slider-badge {
        background-color: rgba(255, 255, 255, 0.12) !important;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.25) !important;
        color: #ffffff !important;
        font-family: 'Baloo Da 2', sans-serif;
        font-weight: 700;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
    }
    .btn-slider-premium {
        font-family: 'Baloo Da 2', sans-serif;
        font-weight: 700;
        padding: 12px 40px;
        border-radius: 30px;
        transition: all 0.3s ease;
        letter-spacing: 0.5px;
        font-size: 1.05rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    .btn-slider-premium:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3) !important;
    }
    .btn-slider-primary {
        background-color: #006A4E !important;
        color: #ffffff !important;
        border: 2px solid #006A4E !important;
    }
    .btn-slider-primary:hover {
        background-color: #004D38 !important;
        color: #ffffff !important;
        border-color: #004D38 !important;
    }
    .btn-slider-secondary {
        background-color: transparent !important;
        color: #ffffff !important;
        border: 2px solid rgba(255, 255, 255, 0.65) !important;
    }
    .btn-slider-secondary:hover {
        background-color: #ffffff !important;
        color: #006A4E !important;
        border-color: #ffffff !important;
    }
    .hover-glow {
        transition: all 0.4s ease !important;
    }
    .hover-glow:hover {
        transform: translateY(-8px) !important;
        box-shadow: 0 15px 35px rgba(52, 211, 153, 0.25) !important;
        border-color: rgba(52, 211, 153, 0.45) !important;
        background: rgba(255, 255, 255, 0.08) !important;
    }

    /* Gallery Hover Effects */
    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .gallery-item img {
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .gallery-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(16, 185, 129, 0.2);
    }
    .gallery-item:hover img {
        transform: scale(1.1);
    }
    .gallery-overlay {
        background: linear-gradient(to top, rgba(2, 44, 34, 0.9) 0%, rgba(2, 44, 34, 0.4) 60%, transparent 100%) !important;
        opacity: 0.95;
        transition: all 0.4s ease;
    }
    .gallery-item:hover .gallery-overlay {
        background: linear-gradient(to top, rgba(0, 106, 78, 0.95) 0%, rgba(0, 106, 78, 0.6) 60%, transparent 100%) !important;
    }
</style>
@if(!empty($homepageCss))
<style>
    {!! $homepageCss !!}
</style>
@endif
@endpush

@section('content')

@php
    $layout = count($homepageLayout) > 0 ? $homepageLayout : [
        ['id' => 'hero_slider', 'is_active' => true, 'bg_color' => '', 'bg_image' => '', 'padding_top' => 0, 'padding_bottom' => 0, 'margin_top' => 0, 'margin_bottom' => 0],
        ['id' => 'news_ticker', 'is_active' => true, 'bg_color' => '', 'bg_image' => '', 'padding_top' => 0, 'padding_bottom' => 0, 'margin_top' => 0, 'margin_bottom' => 0],
        ['id' => 'about_section', 'is_active' => true, 'bg_color' => '', 'bg_image' => '', 'padding_top' => 0, 'padding_bottom' => 0, 'margin_top' => 0, 'margin_bottom' => 0],
        ['id' => 'leaders_section', 'is_active' => true, 'bg_color' => '', 'bg_image' => '', 'padding_top' => 0, 'padding_bottom' => 0, 'margin_top' => 0, 'margin_bottom' => 0],
        ['id' => 'ideology_section', 'is_active' => true, 'bg_color' => '', 'bg_image' => '', 'padding_top' => 0, 'padding_bottom' => 0, 'margin_top' => 0, 'margin_bottom' => 0],
        ['id' => 'activities_section', 'is_active' => true, 'bg_color' => '', 'bg_image' => '', 'padding_top' => 0, 'padding_bottom' => 0, 'margin_top' => 0, 'margin_bottom' => 0],
        ['id' => 'publications_section', 'is_active' => true, 'bg_color' => '', 'bg_image' => '', 'padding_top' => 0, 'padding_bottom' => 0, 'margin_top' => 0, 'margin_bottom' => 0],
        ['id' => 'leadership_section', 'is_active' => true, 'bg_color' => '', 'bg_image' => '', 'padding_top' => 0, 'padding_bottom' => 0, 'margin_top' => 0, 'margin_bottom' => 0],
        ['id' => 'testimonials_section', 'is_active' => true, 'bg_color' => '', 'bg_image' => '', 'padding_top' => 0, 'padding_bottom' => 0, 'margin_top' => 0, 'margin_bottom' => 0],
        ['id' => 'videos_section', 'is_active' => true, 'bg_color' => '', 'bg_image' => '', 'padding_top' => 0, 'padding_bottom' => 0, 'margin_top' => 0, 'margin_bottom' => 0],
    ];

    // Sort layout by order key
    usort($layout, function($a, $b) {
        return ($a['order'] ?? 99) <=> ($b['order'] ?? 99);
    });
@endphp

@foreach($layout as $section)
    @if($section['is_active'] ?? true)
        @php
            $secStyle = "";
            if (!empty($section['bg_color'])) {
                $secStyle .= "background-color: {$section['bg_color']} !important; ";
            }
            if (!empty($section['bg_image'])) {
                $secStyle .= "background-image: url('" . asset($section['bg_image']) . "') !important; background-size: cover; background-position: center; ";
            }
            if (isset($section['padding_top'])) {
                $secStyle .= "padding-top: {$section['padding_top']}px !important; ";
            }
            if (isset($section['padding_bottom'])) {
                $secStyle .= "padding-bottom: {$section['padding_bottom']}px !important; ";
            }
            if (isset($section['margin_top'])) {
                $secStyle .= "margin-top: {$section['margin_top']}px !important; ";
            }
            if (isset($section['margin_bottom'])) {
                $secStyle .= "margin-bottom: {$section['margin_bottom']}px !important; ";
            }
        @endphp

        <div class="homepage-section-wrapper" id="section-wrapper-{{ $section['id'] }}" style="{{ $secStyle }}">
            @switch($section['id'])
                @case('hero_slider')
                    <!-- Hero Slider Section -->
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            @forelse($sliders as $key => $slider)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <div class="carousel-item-bg" style="background-image: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.65)), url('{{ asset($slider->image_url) }}');"></div>
                    <div class="container h-100 carousel-item-overlay">
                        <div class="row h-100 align-items-center">
                            <div class="col-lg-8 text-white">
                                <span class="badge slider-badge mb-3 px-3 py-2 rounded-pill animated slideInDown">সর্বশেষ আপডেট</span>
                                <h1 class="display-3 slider-title mb-4 animated slideInDown text-shadow-premium">{{ $slider->title }}</h1>
                                <p class="lead slider-subtitle mb-5 animated slideInUp text-shadow-premium">{{ $slider->sub_title }}</p>
                                @if($slider->hasLink())
                                    <a href="{{ $slider->button_link }}"
                                       class="btn btn-slider-premium btn-lg animated slideInUp {{ $slider->button_color == '#D4AF37' ? 'btn-slider-secondary' : 'btn-slider-primary' }}"
                                       target="{{ $slider->target }}">
                                        {{ $slider->button_text }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="carousel-item active">
                    <div class="carousel-item-bg" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1541872703-74c5e44368f9?q=80&width=1920');"></div>
                    <div class="container h-100 carousel-item-overlay">
                        <div class="row h-100 align-items-center justify-content-center text-center">
                            <div class="col-lg-10 text-white">
                                <h1 class="display-3 slider-title mb-4 text-shadow-premium">মানবতার কল্যাণে হেযবুত তওহীদ</h1>
                                <p class="lead slider-subtitle mx-auto mb-4 text-shadow-premium">ধর্মান্ধতা, সাম্প্রদায়িকতা ও জঙ্গিবাদের বিরুদ্ধে এক বজ্রকণ্ঠ। আসুন সত্যের পথে ঐক্যবদ্ধ হই।</p>
                                <a href="{{ route('about') }}" class="btn btn-slider-premium btn-slider-secondary btn-lg px-5 py-3 rounded-pill fw-bold">আমাদের আদর্শ</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        @if($sliders->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        @endif
    </div>


                    @break

                @case('news_ticker')
                    <!-- Breaking News Ticker (Marquee) -->
    <div class="news-ticker-container d-flex align-items-center">
        <div class="ticker-title d-flex align-items-center text-nowrap">
            <i class="fas fa-bullhorn me-2 text-gold"></i> ব্রেকিং নিউজ
        </div>
        <div class="ticker-wrap py-2">
            <div class="ticker-content">
                @forelse($blogs as $blog)
                    <span class="ticker-item me-5">
                        <a href="{{ route('blog.detail', $blog->slug) }}" class="text-decoration-none text-dark hover-gold">
                            <span class="badge bg-dark-green text-white me-2">সংবাদ</span>
                            {{ $blog->title }}
                        </a>
                    </span>
                @empty
                    <span class="text-muted">আন্দোলনের সত্য প্রচার ও মানবতার কল্যাণে এগিয়ে আসুন। হেজবুত তওহীদের সাথেই থাকুন।</span>
                @endforelse
            </div>
        </div>
    </div>


                    @break

                @case('leaders_section')

    <!-- Double Leader Message Section (Founder & Chairman) -->
    <section class="leaders-section py-6 bg-off-white">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-gold fw-bold text-uppercase tracking-wider">নেতৃত্বের আদর্শ</span>
                <h2 class="section-title text-dark-green">আমাদের পথপ্রদর্শক ও নেতৃত্ব</h2>
                <div class="title-underline bg-gold mx-auto mt-3" style="width: 80px; height: 3px;"></div>
            </div>

            <div class="row g-4 justify-content-center">
                <!-- Founder Card -->
                <div class="col-lg-6">
                    <div class="card leader-card-tall founder h-100 bg-white rounded-4 shadow-sm text-center overflow-hidden">
                        <div class="leader-image-header" style="height: 360px; overflow: hidden; position: relative;">
                            @php
                                $founderImg = $homepageContent['leaders_section']['founder_image'] ?? ($founder ? $founder->image : '');
                                if (empty($founderImg)) {
                                    $founderImg = 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=600';
                                }
                            @endphp
                            <img src="{{ str_starts_with($founderImg, 'http') ? $founderImg : asset($founderImg) }}" alt="{{ $homepageContent['leaders_section']['founder_name'] ?? ($founder->name ?? 'জনাব মোহাম্মদ বায়েজীদ খান পন্নী') }}" class="w-100 h-100" style="object-fit: cover; object-position: center 20%;">
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <span class="badge bg-light-green text-dark-green mb-2 px-3 py-1 rounded-pill fw-bold" style="font-size: 0.8rem; width: fit-content; margin: 0 auto;">{{ $homepageContent['leaders_section']['founder_badge'] ?? 'প্রতিষ্ঠাতা এমামুযযামান' }}</span>
                            <h3 class="fw-bold text-dark-green mb-1">{{ $homepageContent['leaders_section']['founder_name'] ?? ($founder->name ?? 'জনাব মোহাম্মদ বায়েজীদ খান পন্নী') }}</h3>
                            <p class="text-muted mb-3" style="font-size: 0.85rem; font-weight: 500;">{{ $homepageContent['leaders_section']['founder_designation'] ?? ($founder->designation ?? 'সংস্কারক ও পায়োনিয়ার (১৯২৫ - ২০১২)') }}</p>
                            <hr class="w-25 mx-auto my-3 border-2 border-dark-green" style="border-color: #006A4E !important;">
                            <p class="text-muted lh-lg mb-4 text-start" style="text-align: justify !important;">
                                {!! $homepageContent['leaders_section']['founder_bio'] ?? ($founder->bio ?? 'হেযবুত তওহীদের মাননীয় প্রতিষ্ঠাতা এমামুযযামান জনাব মোহাম্মদ বায়েজীদ খান পন্নী ধর্মের প্রকৃত শিক্ষাকে পুনরুজ্জীবিত করতে সারা জীবন ব্যয় করেছেন...') !!}
                            </p>
                            <div class="mt-auto">
                                <a href="{{ $homepageContent['leaders_section']['founder_btn_link'] ?? ($founder ? route('leadership.show', $founder->slug) : route('about') . '#founder') }}" class="btn btn-outline-dark-green rounded-pill px-4 py-2">{{ $homepageContent['leaders_section']['founder_btn_text'] ?? 'প্রতিষ্ঠাতার জীবনী ও বইসমূহ' }}</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chairman/Leader Card -->
                <div class="col-lg-6">
                    <div class="card leader-card-tall h-100 bg-white rounded-4 shadow-sm text-center overflow-hidden">
                        <div class="leader-image-header" style="height: 360px; overflow: hidden; position: relative;">
                            @php
                                $emamImg = $homepageContent['leaders_section']['emam_image'] ?? ($currentLeader ? $currentLeader->image : '');
                                if (empty($emamImg)) {
                                    $emamImg = 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=600';
                                }
                            @endphp
                            <img src="{{ str_starts_with($emamImg, 'http') ? $emamImg : asset($emamImg) }}" alt="{{ $homepageContent['leaders_section']['emam_name'] ?? ($currentLeader->name ?? 'জনাব হোসাইন মোহাম্মদ সেলিম') }}" class="w-100 h-100" style="object-fit: cover; object-position: center 20%;">
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <span class="badge bg-dark-green text-white mb-2 px-3 py-1 rounded-pill fw-bold" style="font-size: 0.8rem; width: fit-content; margin: 0 auto;">{{ $homepageContent['leaders_section']['emam_badge'] ?? 'মাননীয় এমাম' }}</span>
                            <h3 class="fw-bold text-dark-green mb-1">{{ $homepageContent['leaders_section']['emam_name'] ?? ($currentLeader->name ?? 'জনাব হোসাইন মোহাম্মদ সেলিম') }}</h3>
                            <p class="text-muted mb-3" style="font-size: 0.85rem; font-weight: 500;">{{ $homepageContent['leaders_section']['emam_designation'] ?? ($currentLeader->designation ?? 'হেযবুত তওহীদের মাননীয় এমাম') }}</p>
                            <hr class="w-25 mx-auto my-3 border-2 border-dark-green" style="border-color: #006A4E !important;">
                            <p class="text-muted lh-lg mb-4 text-start" style="text-align: justify !important; font-style: italic;">
                                "{{ $homepageContent['leaders_section']['emam_quote'] ?? ($currentLeader->quote ?? 'আমাদের এই লড়াই কোনো ব্যক্তিগত বা রাজনৈতিক ফায়দা হাসিলের জন্য নয়...') }}"
                            </p>
                            <div class="mt-auto">
                                <a href="{{ $homepageContent['leaders_section']['emam_btn_link'] ?? ($currentLeader ? route('leadership.show', $currentLeader->slug) : route('about') . '#chairman') }}" class="btn btn-outline-dark-green rounded-pill px-4 py-2">{{ $homepageContent['leaders_section']['emam_btn_text'] ?? 'এমামের সাক্ষাৎকার ও বাণী' }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

                    @break

                @case('activities_section')
                    <!-- Testimonials / Citizen Voices Section -->
                        <section class="testimonials-section py-6 position-relative overflow-hidden" style="background: linear-gradient(135deg, #022c22 0%, #064e3b 100%) !important; border-top: 4px solid #10B981;">
                            <!-- Subtle background glowing lights -->
                            <div class="position-absolute rounded-circle" style="width: 250px; height: 250px; background: rgba(52, 211, 153, 0.1); top: -50px; right: -50px; filter: blur(60px); pointer-events: none;"></div>
                            <div class="position-absolute rounded-circle" style="width: 250px; height: 250px; background: rgba(16, 185, 129, 0.08); bottom: -50px; left: -50px; filter: blur(60px); pointer-events: none;"></div>
                    
                            <div class="container position-relative" style="z-index: 2;">
                                <div class="text-center mb-5">
                                    <span class="fw-bold text-uppercase tracking-wider fs-6 px-3 py-1 rounded-pill" style="background: rgba(16, 185, 129, 0.15); color: #34d399; font-size: 0.85rem; border: 1px solid rgba(52, 211, 153, 0.25);">নাগরিক মতামত ও সুধী বাণী</span>
                                    <h2 class="section-title text-white mt-3 fw-bold">শুভাকাঙ্ক্ষীদের বক্তব্য</h2>
                                    <div class="mx-auto mt-2" style="width: 60px; height: 3px; border-radius: 2px; background-color: #10b981;"></div>
                                </div>
                    
                                <!-- Testimonials Grid / Slider Container -->
                                <div id="smartTestimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="8000">
                                    <div class="carousel-inner">
                                        @php
                                            // Chunk testimonials to show 3 cards per slide on desktop, 1 on mobile
                                            $testimonialChunks = $testimonials->chunk(3);
                                        @endphp
                                        @forelse($testimonialChunks as $chunkIndex => $chunk)
                                            <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                                                <div class="row g-4 justify-content-center">
                                                    @foreach($chunk as $testimonial)
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="testimonial-card">
                    
                                                                <!-- Quote icon watermark -->
                                                                <i class="fas fa-quote-right position-absolute" style="font-size: 4.5rem; color: rgba(16, 185, 129, 0.04); bottom: 15px; right: 20px; pointer-events: none;"></i>
                    
                                                                <div>
                                                                    <!-- Rating Stars -->
                                                                    <div class="mb-3 text-warning fs-6">
                                                                        @for($i = 1; $i <= 5; $i++)
                                                                            @if($i <= $testimonial->rating)
                                                                                <i class="fas fa-star" style="color: #fbbf24;"></i>
                                                                            @else
                                                                                <i class="far fa-star" style="color: #cbd5e1;"></i>
                                                                            @endif
                                                                        @endfor
                                                                    </div>
                    
                                                                    <!-- Content -->
                                                                    <p style="font-family: 'Hind Siliguri', sans-serif;">
                                                                        @if(mb_strlen($testimonial->content) > 130)
                                                                            {{ mb_substr($testimonial->content, 0, 125) }}...
                                                                            <a href="javascript:void(0);" class="read-more-btn" data-name="{{ $testimonial->name }}" data-designation="{{ $testimonial->designation }}" data-content="{{ $testimonial->content }}" data-avatar="{{ $testimonial->avatar_url }}">বিস্তারিত পড়ুন</a>
                                                                        @else
                                                                            {{ $testimonial->content }}
                                                                        @endif
                                                                    </p>
                                                                </div>
                    
                                                                <!-- User Meta -->
                                                                <div class="d-flex align-items-center mt-auto pt-3" style="border-top: 1px solid #f1f5f9;">
                                                                    <img src="{{ $testimonial->avatar_url }}" alt="{{ $testimonial->name }}" class="rounded-circle me-3" style="width: 48px; height: 48px; object-fit: cover; border: 2px solid rgba(16, 185, 129, 0.4);">
                                                                    <div class="text-start">
                                                                        <h6 class="mb-0 fs-6">{{ $testimonial->name }}</h6>
                                                                        <span class="small text-emerald">{{ $testimonial->designation ?? 'শুভাকাঙ্ক্ষী' }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @empty
                                            <!-- Default smart placeholder slide -->
                                            <div class="carousel-item active">
                                                <div class="row g-4 justify-content-center">
                                                    <!-- Dummy Card 1 -->
                                                    <div class="col-lg-4 col-md-6">
                                                        <div class="testimonial-card">
                                                            <div>
                                                                <div class="mb-3 text-warning"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                                                                <p>উগ্রবাদ, সাম্প্রদায়িকতা ও জঙ্গিবাদের বিরুদ্ধে হেযবুত তওহীদের দেশব্যাপী বুদ্ধিবৃত্তিক প্রচারণা অত্যন্ত প্রশংসনীয়। সমাজে শান্তি রক্ষায় তাদের এই কর্মসূচি গুরুত্বপূর্ণ ভূমিকা রাখছে।</p>
                                                            </div>
                                                            <div class="d-flex align-items-center mt-3 pt-3" style="border-top: 1px solid #f1f5f9;">
                                                                <div class="rounded-circle me-3 d-flex align-items-center justify-content-center text-white fw-bold bg-success" style="width: 48px; height: 48px;">শ</div>
                                                                <div class="text-start">
                                                                    <h6 class="mb-0">ড. এ. কে. এম. শাহনেওয়াজ</h6>
                                                                    <span class="small text-emerald">অধ্যাপক, জাহাঙ্গীরনগর বিশ্ববিদ্যালয়</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Dummy Card 2 -->
                                                    <div class="col-lg-4 col-md-6">
                                                        <div class="testimonial-card">
                                                            <div>
                                                                <div class="mb-3 text-warning"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                                                                <p>ধর্মান্ধতা ও কুসংস্কারের বিরুদ্ধে ইসলামের সঠিক অহিংস বাণী মানুষের কাছে তুলে ধরার ব্যাপারে হেযবুত তওহীদের ভূমিকা যুগোপযোগী। তাদের সেবামূলক কার্যক্রম সর্বস্তরের মানুষের প্রশংসা পাওয়ার যোগ্য।</p>
                                                            </div>
                                                            <div class="d-flex align-items-center mt-3 pt-3" style="border-top: 1px solid #f1f5f9;">
                                                                <div class="rounded-circle me-3 d-flex align-items-center justify-content-center text-white fw-bold bg-success" style="width: 48px; height: 48px;">ই</div>
                                                                <div class="text-start">
                                                                    <h6 class="mb-0">হাসানুল হক ইনু</h6>
                                                                    <span class="small text-emerald">সাবেক তথ্য ও সম্প্রচার মন্ত্রী</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Dummy Card 3 -->
                                                    <div class="col-lg-4 col-md-6">
                                                        <div class="testimonial-card">
                                                            <div>
                                                                <div class="mb-3 text-warning"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                                                                <p>একটি শান্তিময় ও ধর্মীয় সম্প্রীতিপূর্ণ সমাজ বিনির্মাণে তাদের অরাজনৈতিক আন্দোলন ও আদর্শিক লড়াই সমাজে ইতিবাচক পরিবর্তন আনতে সক্ষম হয়েছে। তাদের সকল মানবিক কাজের সফলতা কামনা করি।</p>
                                                            </div>
                                                            <div class="d-flex align-items-center mt-3 pt-3" style="border-top: 1px solid #f1f5f9;">
                                                                <div class="rounded-circle me-3 d-flex align-items-center justify-content-center text-white fw-bold bg-success" style="width: 48px; height: 48px;">ম</div>
                                                                <div class="text-start">
                                                                    <h6 class="mb-0">বিচারপতি সিকদার মকবুল হোসেন</h6>
                                                                    <span class="small text-emerald">অবসরপ্রাপ্ত বিচারপতি, সুপ্রিম কোর্ট</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                    
                                    <!-- Slider Controls if chunks > 1 -->
                                    @if($testimonialChunks->count() > 1)
                                        <div class="d-flex justify-content-center gap-3 mt-4">
                                            <button class="carousel-control-btn d-flex align-items-center justify-content-center rounded-circle" type="button" data-bs-target="#smartTestimonialCarousel" data-bs-slide="prev">
                                                <i class="fas fa-chevron-left" style="font-size: 0.9rem;"></i>
                                            </button>
                                            <button class="carousel-control-btn d-flex align-items-center justify-content-center rounded-circle" type="button" data-bs-target="#smartTestimonialCarousel" data-bs-slide="next">
                                                <i class="fas fa-chevron-right" style="font-size: 0.9rem;"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                    
                                <!-- Sleek feedback action link -->
                                <div class="text-center mt-5">
                                    <a href="{{ url('/feedback') }}" class="btn rounded-pill px-5 py-3 fw-bold transition-all" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; color: white; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); font-size: 0.95rem;">
                                        <i class="fas fa-comments me-2"></i> সকল সুধী মতামত দেখুন
                                    </a>
                                </div>
                            </div>
                        </section>
                    
                        <!-- Testimonial Detail Modal -->
                        <div class="modal fade" id="testimonialDetailModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg text-white" style="background: linear-gradient(135deg, #022c22 0%, #064e3b 100%) !important; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1) !important;">
                                    <div class="modal-header border-0 pb-0">
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4 pt-0 text-center">
                                        <i class="fas fa-quote-left fa-3x mb-3 opacity-30" style="color: #10b981;"></i>
                                        <p id="modal-testimonial-content" class="fs-5 lh-relaxed mb-4 text-white opacity-95" style="font-family: 'Hind Siliguri', sans-serif; font-style: italic;"></p>
                    
                                        <div class="d-flex align-items-center justify-content-center mt-4 pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.1);">
                                            <img id="modal-testimonial-avatar" src="" alt="Avatar" class="rounded-circle me-3" style="width: 55px; height: 55px; object-fit: cover; border: 2px solid #10b981;">
                                            <div class="text-start">
                                                <h5 id="modal-testimonial-name" class="fw-bold mb-0 text-white"></h5>
                                                <span id="modal-testimonial-designation" class="small text-emerald" style="color: #34d399;"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @break

                @default
                    @if(str_starts_with($section['id'], 'custom_'))
                        <div class="custom-html-section">
                            {!! $homepageContent[$section['id']]['html_content'] ?? '' !!}
                        </div>
                    @endif
                    @break

                @case('videos_section')
                    <!-- Video Gallery Section -->
    <section class="video-gallery-section py-6 bg-off-white">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-gold fw-bold text-uppercase tracking-wider">ভিডিও সম্ভার</span>
                <h2 class="section-title text-dark-green">গুরুত্বপূর্ণ সেমিনার ও বক্তব্যসমূহ</h2>
                <div class="title-underline bg-gold mx-auto mt-3" style="width: 80px; height: 3px;"></div>
            </div>

            <div class="row g-4">
                <!-- Video 1 -->
                <div class="col-lg-4 col-md-6">
                    <div class="card video-card bg-white h-100 border-0">
                        <div class="video-iframe-wrapper">
                            <iframe src="https://www.youtube.com/embed/zH0jJ0-05E8" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-dark mb-0"> ধর্মান্ধতা ও জঙ্গিবাদের বিরুদ্ধে হেজবুত তওহীদের মহাসমাবেশ</h6>
                        </div>
                    </div>
                </div>

                <!-- Video 2 -->
                <div class="col-lg-4 col-md-6">
                    <div class="card video-card bg-white h-100 border-0">
                        <div class="video-iframe-wrapper">
                            <iframe src="https://www.youtube.com/embed/J7iF1tEewjI" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-dark mb-0">প্রকৃত ইসলামের শিক্ষা ও মানব কল্যাণ - বিশেষ সেমিনার</h6>
                        </div>
                    </div>
                </div>

                <!-- Video 3 -->
                <div class="col-lg-4 col-md-6">
                    <div class="card video-card bg-white h-100 border-0">
                        <div class="video-iframe-wrapper">
                            <iframe src="https://www.youtube.com/embed/Yt-jXn2WpM4" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-dark mb-0">চেয়ারম্যান এমদাদুল হক সেলিমের বিশেষ সাক্ষাৎকার ও দিকনির্দেশনা</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Photo Gallery Section -->
    <section class="photo-gallery-section py-6">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-gold fw-bold text-uppercase tracking-wider">চিত্রশালা</span>
                <h2 class="section-title text-dark-green">ছবি গ্যালারি</h2>
                <p class="text-muted mt-2">আন্দোলনের বিভিন্ন কর্মসূচী ও কর্মকাণ্ডের স্থিরচিত্রসমূহ</p>
                <div class="title-underline bg-gold mx-auto mt-3" style="width: 80px; height: 3px;"></div>
            </div>

            <div class="row g-3">
                @forelse($galleryPosts as $post)
                    <div class="col-lg-3 col-md-6 col-6">
                        <a href="#" class="d-block text-decoration-none gallery-lightbox-trigger"
                           data-image="{{ $post->featured_image_url }}"
                           data-title="{{ $post->title }}"
                           data-category="{{ $post->category->name ?? 'কর্মসূচী' }}"
                           data-url="{{ route('blog.detail', $post->slug) }}">
                            <div class="gallery-item position-relative overflow-hidden rounded-4 shadow-sm" style="height: 220px; border-radius: 16px;">
                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-100 h-100 object-fit-cover transition" style="transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);">
                                <div class="gallery-overlay position-absolute bottom-0 start-0 w-100 p-3 text-white d-flex flex-column justify-content-end h-100" style="background: linear-gradient(transparent, rgba(0,0,0,0.85));">
                                    <span class="small fw-bold text-gold" style="font-size: 0.75rem;">{{ $post->category->name ?? 'কর্মসূচী' }}</span>
                                    <h6 class="mb-0 small fw-bold text-truncate" style="font-size: 0.85rem;" title="{{ $post->title }}">{{ $post->title }}</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-4 text-muted">
                        কোনো স্থিরচিত্র পাওয়া যায়নি!
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Photo Gallery Lightbox Modal -->
    <div class="modal fade" id="galleryLightboxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg text-white" style="border-radius: 20px; overflow: hidden; background: #0b1512 !important; border: 1px solid rgba(16, 185, 129, 0.25) !important;">
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

    <!-- Social & Humanitarian Activities Section -->
    <section class="activities-section py-6">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-gold fw-bold text-uppercase tracking-wider">কর্মধারা</span>
                <h2 class="section-title text-dark-green">সেবামূলক ও সচেতনতা কার্যক্রম</h2>
                <div class="title-underline bg-gold mx-auto mt-3" style="width: 80px; height: 3px;"></div>
            </div>

            <div class="row g-4">
                @forelse($activities as $activity)
                    <div class="col-lg-4 col-md-6">
                        <div class="card activity-card h-100 bg-white border-0 shadow-sm rounded-4 overflow-hidden" style="border-top: 4px solid #006A4E !important;">
                            <div class="activity-img-wrapper" style="height: 200px; overflow: hidden;">
                                <img src="{{ $activity->image_url }}" alt="{{ $activity->title }}" class="w-100 h-100 object-fit-cover">
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <h5 class="fw-bold text-dark-green mb-2">
                                    <a href="{{ route('activities.show', $activity->slug) }}" class="text-decoration-none text-dark-green">
                                        {{ $activity->title }}
                                    </a>
                                </h5>
                                <p class="text-muted lh-lg flex-grow-1" style="font-size: 0.9rem; text-align: justify;">
                                    {{ Str::limit($activity->description, 150) }}
                                </p>
                                <div class="mt-3 pt-3 border-top border-light">
                                    <a href="{{ route('activities.show', $activity->slug) }}" class="btn btn-gold btn-sm px-3 rounded-pill fw-bold w-100">
                                        বিস্তারিত পড়ুন <i class="fas fa-arrow-right ms-1" style="font-size: 0.75rem;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Fallback Static Cards -->

                @endforelse
            </div>

            @if(count($activities) > 0)
                <div class="text-center mt-5">
                    <a href="{{ route('activities.index') }}" class="btn btn-outline-dark-green px-4 py-2 rounded-pill fw-bold">
                        সব কার্যক্রম দেখুন <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            @endif
        </div>
    </section>

    
    <!-- Citizens Feedback / Suggestion Box Section -->
    <section class="feedback-section py-6" style="background-color: #f8fafc;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-5 mb-lg-0 text-start">
                    <span class="badge px-3 py-2 rounded-pill fw-bold text-uppercase tracking-wider mb-3" style="font-size: 0.8rem; background: rgba(16, 185, 129, 0.1); color: #047857;">পরামর্শ কর্নার</span>
                    <h2 class="fw-bold text-dark-green mb-3" style="font-size: 2.4rem; font-family: 'Baloo Da 2', sans-serif; color: #004d38;">আপনার মূল্যবান মতামত ও পরামর্শ দিন</h2>
                    <p class="text-muted lh-lg mb-4" style="text-align: justify; font-size: 1.05rem;">
                        আন্দোলনের নীতি, সমাজ সংস্কারমূলক কার্যক্রম বা আমাদের প্রচারণা সম্পর্কে আপনার মূল্যবান উপদেশ ও গঠনমূলক পরামর্শ আমাদের কাছে অত্যন্ত গুরুত্বপূর্ণ। সরাসরি কেন্দ্রীয় দপ্তরে আপনার মতামত জানাতে পাশের ফরমটি পূরণ করুন।
                    </p>
                    <div class="d-flex align-items-center gap-3 py-2 text-muted">
                        <div class="icon-box text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #10b981; flex-shrink: 0;">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0" style="font-family: 'Baloo Da 2', sans-serif;">সুরক্ষিত ও গোপনীয়</h6>
                            <span class="small">আপনার প্রেরিত সকল পরামর্শ ও তথ্য আমাদের কাছে সম্পূর্ণ নিরাপদ।</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 ps-lg-5">
                    <div class="card border-0 shadow-sm p-4 p-md-5 bg-white" style="border-radius: 24px; border: 1px solid rgba(226, 232, 240, 0.8) !important;">

                        <!-- Success / Error Notifications -->
                        <div class="suggestion-alert-container">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-left: 5px solid #10b981; border-radius: 12px; background-color: #f0fdf4; color: #15803d;">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle me-3 fs-5"></i>
                                        <span>{{ session('success') }}</span>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-left: 5px solid #ef4444; border-radius: 12px; background-color: #fef2f2; color: #b91c1c;">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-circle me-3 fs-5 "></i>
                                        <span>{{ session('error') }}</span>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                        </div>

                        <form action="{{ route('suggestions.store') }}" method="POST" class="suggestion-form" novalidate>
                            @csrf
                            <div class="row g-3 text-start">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small mb-1">আপনার নাম <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control rounded-3 py-3 custom-input-focus" placeholder="পূর্ণ নাম লিখুন" value="{{ old('name') }}" style="border: 1px solid #cbd5e1; font-size: 0.95rem;">
                                    <div class="invalid-feedback" id="ajax-error-name"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small mb-1">ইমেইল / মোবাইল <span class="text-danger">*</span></label>
                                    <input type="text" name="contact" class="form-control rounded-3 py-3 custom-input-focus" placeholder="যোগাযোগের ঠিকানা লিখুন" value="{{ old('contact') }}" style="border: 1px solid #cbd5e1; font-size: 0.95rem;">
                                    <div class="invalid-feedback" id="ajax-error-contact"></div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-dark small mb-1">বিষয়</label>
                                    <input type="text" name="subject" class="form-control rounded-3 py-3 custom-input-focus" placeholder="পরামর্শের বিষয় লিখুন" value="{{ old('subject') }}" style="border: 1px solid #cbd5e1; font-size: 0.95rem;">
                                    <div class="invalid-feedback" id="ajax-error-subject"></div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-dark small mb-1">পরামর্শ / বার্তা <span class="text-danger">*</span></label>
                                    <textarea name="message" class="form-control rounded-3 py-3 custom-input-focus" rows="4" placeholder="আপনার বার্তাটি বিস্তারিত লিখুন..." style="border: 1px solid #cbd5e1; font-size: 0.95rem;">{{ old('message') }}</textarea>
                                    <div class="invalid-feedback" id="ajax-error-message"></div>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-success w-100 rounded-pill py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; font-size: 1.05rem; transition: all 0.3s ease;">
                                        <span>পরামর্শ পাঠান</span>
                                        <i class="fas fa-paper-plane" style="font-size: 0.9rem;"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .activity-card:hover {
            transform: translateY(-8px) !important;
            box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.15), 0 10px 10px -6px rgba(16, 185, 129, 0.08) !important;
        }
        .activity-card:hover .activity-card-img {
            transform: scale(1.08);
        }
        .activity-card:hover .activity-title {
            color: #059669 !important;
        }
        .activity-link {
            color: #10b981 !important;
            font-size: 0.92rem;
            transition: all 0.3s ease;
            font-weight: 700 !important;
        }
        .activity-link i {
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }
        .activity-card:hover .activity-link {
            color: #059669 !important;
        }
        .activity-card:hover .activity-link i {
            transform: translateX(5px);
        }

        #smartTestimonialCarousel .carousel-item {
            height: auto !important;
            min-height: initial !important;
            background: transparent !important;
        }
        .testimonial-card {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-top: 4px solid #10b981 !important;
            border-radius: 20px !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            min-height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2rem !important;
            position: relative;
        }
        .testimonial-card:hover {
            transform: translateY(-8px) !important;
            background: #ffffff !important;
            border-top-color: #059669 !important;
            box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.25), 0 10px 10px -6px rgba(16, 185, 129, 0.15) !important;
        }
        .testimonial-card p {
            color: #475569 !important;
            font-size: 0.95rem !important;
            line-height: 1.7 !important;
            margin-bottom: 1.5rem !important;
            text-align: justify;
        }
        .testimonial-card h6 {
            color: #0f172a !important;
            font-weight: 700 !important;
            font-family: 'Baloo Da 2', sans-serif !important;
            margin-bottom: 2px !important;
        }
        .testimonial-card .text-emerald {
            color: #059669 !important;
            font-weight: 600 !important;
            font-size: 0.82rem !important;
        }
        .read-more-btn {
            color: #10b981 !important;
            font-weight: 700 !important;
            text-decoration: none !important;
            font-size: 0.85rem !important;
            margin-left: 4px;
            transition: all 0.2s ease;
        }
        .read-more-btn:hover {
            color: #059669 !important;
            text-decoration: underline !important;
        }
        .carousel-control-btn {
            width: 45px;
            height: 45px;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            background: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .carousel-control-btn:hover {
            background: #10b981 !important;
            border-color: #10b981 !important;
            color: #ffffff !important;
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.4);
            transform: scale(1.1);
        }
        @media (max-width: 991.98px) {
            #smartTestimonialCarousel .row > div:nth-child(n+2) {
                display: none;
            }
        }
        .custom-input-focus:focus {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15) !important;
            outline: none;
        }
    </style>

    <!-- Join Us CTA Banner Section -->
    <section class="cta-section py-6 bg-gradient-cta text-white text-center position-relative overflow-hidden" style="background: linear-gradient(135deg, #005a43 0%, #002c1f 100%) !important;">
        <!-- Glowing light patterns for visual warmth -->
        <div class="position-absolute rounded-circle" style="width: 300px; height: 300px; background: rgba(52, 211, 153, 0.15); top: -80px; left: -80px; filter: blur(60px);"></div>
        <div class="position-absolute rounded-circle" style="width: 350px; height: 350px; background: rgba(16, 185, 129, 0.12); bottom: -100px; right: -100px; filter: blur(70px);"></div>

        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- High-contrast Premium Card Wrapper -->
                    <div class="p-4 p-md-5 rounded-4 shadow-lg" style="background: #003d2e; border: 2px solid #10b981; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2), 0 0 20px rgba(16, 185, 129, 0.15) !important;">
                        <i class="fas fa-hand-holding-heart fa-3x mb-4" style="color: #34d399; filter: drop-shadow(0 0 10px rgba(52, 211, 153, 0.4));"></i>
                        <h2 class="fw-bold text-white mb-3" style="font-size: 2.2rem; font-family: 'Baloo Da 2', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">আল্লাহর তওহীদ ভিত্তিক সত্যদীন প্রতিষ্ঠার আন্দোলনে যোগ দিন</h2>
                        <!-- Description text color changed to pure bright off-white (#f8fafc) and opacity set to 100% for crisp readability -->
                        <p class="lead mb-4 mx-auto" style="font-size: 1.15rem; line-height: 1.8; color: #f8fafc !important; text-shadow: 0 1px 3px rgba(0,0,0,0.3); max-width: 680px; font-weight: 400;">
                            সমাজ থেকে অন্যায়-অশান্তি দূর করে ন্যায় ও সুবিচার প্রতিষ্ঠার লক্ষ্যে আল্লাহর তওহীদভিত্তিক জীবনব্যবস্থা বিশ্বব্যাপী প্রতিষ্ঠার সংগ্রামে আপনিও শরীক হোন। উগ্রবাদ, সাম্প্রদায়িকতা ও সকল অবিচারের বিরুদ্ধে এবং মানবতার কল্যাণে হেযবুত তওহীদের সহযাত্রী হোন।
                        </p>
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <!-- Vibrant gradient green button for high call-to-action visual weight -->
                            <a href="/join" class="btn btn-success btn-lg px-5 py-3 rounded-pill fw-bold shadow" style="background: linear-gradient(135deg, #34d399 0%, #059669 100%); border: none; color: #002c1f !important; box-shadow: 0 4px 15px rgba(52, 211, 153, 0.4) !important; transition: all 0.3s ease;">সদস্য ফরম পূরণ করুন</a>
                            <a href="/contact" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill fw-bold" style="border: 2px solid #ffffff; color: #ffffff !important; transition: all 0.3s ease;">যোগাযোগ করুন</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


                    @break
            @endswitch
        </div>
    @endif
@endforeach

@push('scripts')
<script>
$(document).ready(function() {
    var testimonialModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('testimonialDetailModal'));
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
        $('#lightbox-article-link').attr('href', articleUrl);

        lightboxModal.show();
    });

    $(document).on('click', '.read-more-btn', function() {
        var name = $(this).data('name');
        var designation = $(this).data('designation');
        var content = $(this).data('content');
        var avatar = $(this).data('avatar');

        $('#modal-testimonial-name').text(name);
        $('#modal-testimonial-designation').text(designation);
        $('#modal-testimonial-content').text('"' + content + '"');
        $('#modal-testimonial-avatar').attr('src', avatar);

        testimonialModal.show();
    });

    $('.suggestion-form').on('submit', function(e) {
        e.preventDefault();

        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalBtnHtml = submitBtn.html();
        var alertContainer = form.siblings('.suggestion-alert-container');

        // Clear previous alerts & validations
        alertContainer.empty();
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');

        // Disable button & show loader spinner
        submitBtn.prop('disabled', true).html('<span>পাঠানো হচ্ছে...</span> <i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show custom success alert
                    alertContainer.html(
                        '<div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-left: 5px solid #10b981; border-radius: 12px; background-color: #f0fdf4; color: #15803d;">' +
                            '<div class="d-flex align-items-center">' +
                                '<i class="fas fa-check-circle me-3 fs-5"></i>' +
                                '<span>' + response.message + '</span>' +
                            '</div>' +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>'
                    );
                    // Reset form
                    form[0].reset();
                } else {
                    alertContainer.html(
                        '<div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-left: 5px solid #ef4444; border-radius: 12px; background-color: #fef2f2; color: #b91c1c;">' +
                            '<div class="d-flex align-items-center">' +
                                '<i class="fas fa-exclamation-circle me-3 fs-5"></i>' +
                                '<span>' + response.message + '</span>' +
                            '</div>' +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>'
                    );
                }
            },
            error: function(xhr) {
                var message = 'পরামর্শ পাঠাতে কারিগরি ত্রুটি হয়েছে! দয়া করে কিছুক্ষণ পর আবার চেষ্টা করুন।';
                if (xhr.status === 422 && xhr.responseJSON) {
                    message = xhr.responseJSON.message || 'দয়া করে ফর্মের প্রতিটি তথ্য সঠিক নিয়মে পূরণ করুন।';
                    if (xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            var input = form.find('[name="' + key + '"]');
                            input.addClass('is-invalid');
                            form.find('#ajax-error-' + key).text(value[0]);
                        });
                    }
                }
                alertContainer.html(
                    '<div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-left: 5px solid #ef4444; border-radius: 12px; background-color: #fef2f2; color: #b91c1c;">' +
                        '<div class="d-flex align-items-center">' +
                            '<i class="fas fa-exclamation-circle me-3 fs-5"></i>' +
                            '<span>' + message + '</span>' +
                        '</div>' +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    '</div>'
                );
            },
            complete: function() {
                // Restore button state
                submitBtn.prop('disabled', false).html(originalBtnHtml);
            }
        });
    });
});
</script>
@endpush

@endsection
