@extends('theme::layouts.app')

@section('title', 'মূল পাতা - মানবতার কল্যাণে একটি অরাজনৈতিক আন্দোলন')

@push('styles')

@if(!empty($homepageCss))
<style>
    {!! $homepageCss !!}
</style>
@endif
@endpush

@section('content')

    <!-- 1. Hero Slider Section -->
    <div class="homepage-section-wrapper" id="section-wrapper-hero_slider">
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                @forelse($sliders as $key => $slider)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        @if($slider->hasLink())
                            <a href="{{ $slider->button_link }}" target="{{ $slider->target }}">
                                <div class="carousel-item-bg" style="background-image: url('{{ asset($slider->image_url) }}');"></div>
                            </a>
                        @else
                            <div class="carousel-item-bg" style="background-image: url('{{ asset($slider->image_url) }}');"></div>
                        @endif
                    </div>
                @empty
                    <div class="carousel-item active">
                        <div class="carousel-item-bg" style="background-image: url('https://images.unsplash.com/photo-1541872703-74c5e44368f9?q=80&width=1920');"></div>
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
    </div>

    <!-- 1.1. Live Broadcast Section -->
    @if(isset($liveBroadcast) && $liveBroadcast)
    <div class="homepage-section-wrapper" id="section-wrapper-live_broadcast">
        <div class="live-broadcast-banner py-3 position-relative" style="background: #0f172a !important; border-bottom: 2px solid #ef4444; z-index: 10;">
            <div class="container d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded text-white fw-bold shadow-sm" style="background-color: #ef4444; font-size: 0.8rem; font-family: 'Hind Siliguri', sans-serif;">
                        <span class="live-pulse-dot"></span> সরাসরি লাইভ
                    </span>
                    <h5 class="text-white mb-0 fw-bold" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.15rem;">{{ $liveBroadcast->title }}</h5>
                </div>
                <div class="d-flex align-items-center gap-3">
                    @if($liveBroadcast->description)
                        <span class="small d-none d-lg-inline" style="font-family: 'Hind Siliguri', sans-serif; color: #cbd5e1;">{{ Str::limit($liveBroadcast->description, 80) }}</span>
                    @endif
                    <a href="javascript:void(0);" class="btn btn-sm px-3 py-1.5 fw-bold text-white rounded-2 shadow-sm d-flex align-items-center gap-2 live-modal-trigger" data-url="{{ $liveBroadcast->embed_url }}" style="background-color: #ef4444; border: 1px solid #ef4444; font-family: 'Hind Siliguri', sans-serif; font-size: 0.85rem; border-radius: 5px; transition: all 0.3s ease;">
                        <i class="fas fa-play" style="font-size: 0.75rem;"></i> আলোচনা শুনুন
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- 3. Leaders Section (Styled exactly like the screenshot!) -->
    <section class="leaders-section py-6 bg-white" id="section-wrapper-leaders_section" style="border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; padding-top: 80px; padding-bottom: 80px;">
        <div class="container">
            <div class="row g-5 align-items-center">
                <!-- Left Side: Header & CTA Description -->
                <div class="col-lg-4 text-start">
                    <span class="fw-bold text-uppercase tracking-wider" style="color: #64748b; font-size: 0.95rem; font-family: 'Baloo Da 2', sans-serif;">নেতৃত্বের আদর্শ</span>
                    <h2 class="fw-bold mt-2 mb-3" style="font-family: 'Baloo Da 2', sans-serif; font-size: 2.1rem; color: #0f172a; font-weight: 800; line-height: 1.4;">যাঁর অনুপ্রেরণায় আমরা উজ্জীবিত...</h2>
                    <p class="text-muted lh-lg mb-4" style="text-align: justify; font-size: 14.5px; font-family: 'Hind Siliguri', sans-serif;">
                        যিনি আল্লাহ ও তাঁর রাসুল (সা.)-এর আদর্শ অনুসরণ করে হারিয়ে যাওয়া ইসলামের পুনঃপ্রতিষ্ঠার লক্ষ্যে আল্লাহর সাহায্যে হেযবুত তওহীদ প্রতিষ্ঠা করেছেন, তিনি মহামান্য এমামুযযামান, The Leader of the Time, জনাব মোহাম্মদ বায়াজীদ খান পন্নী। এবং হেযবুত তওহীদ এর বর্তমান এমাম মাননীয় এমাম হোসেন মোহাম্মদ সেলিম।
                    </p>
                    <div>
                        <a href="/about-us" class="btn px-4 py-2 fw-bold text-white shadow-sm" style="background-color: #006A4E; border-color: #006A4E; color: white !important; border-radius: 5px; font-size: 14px; font-family: 'Baloo Da 2', sans-serif;">
                            Learn More
                        </a>
                    </div>
                </div>

                <!-- Right Side: 2 Leaders Cards -->
                <div class="col-lg-8">
                    <div class="row g-4 justify-content-center">
                        <!-- Leader 1 -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-zoom d-flex flex-column" style="box-shadow: 0 10px 30px rgba(0,0,0,0.05) !important;">
                                <div class="leader-img-wrapper" style="height: 290px; overflow: hidden; position: relative;">
                                    <img src="{{ asset('uploads/leaders/founder_panni.png') }}" alt="জনাব মোহাম্মদ বায়াজীদ খান পন্নী" class="w-100 h-100 object-fit-cover grayscale-to-color" style="object-position: center 10%;">
                                </div>
                                <div class="card-body p-4 text-center d-flex flex-column align-items-center justify-content-between">
                                    <div>
                                        <h4 class="fw-bold mb-2" style="font-family: 'Baloo Da 2', sans-serif; color: #0f172a; font-size: 1.25rem; white-space: nowrap;">জনাব মোহাম্মদ বায়াজীদ খান পন্নী</h4>
                                        <div class="mb-3">
                                            <span class="px-3 py-1.5 rounded-pill fw-bold" style="font-size: 11px; border: 1.5px solid #cbd5e1; background-color: #f8fafc; color: #475569; font-family: 'Hind Siliguri', sans-serif; display: inline-block;">
                                                <i class="fas fa-award me-1" style="color: #d97706;"></i> হেযবুত তওহীদের প্রতিষ্ঠাতা মাননীয় এমামুযযামান
                                            </span>
                                        </div>
                                        <p class="text-muted lh-lg mb-0" style="font-size: 0.9rem; text-align: justify; text-align-last: center; font-family: 'Hind Siliguri', sans-serif;">
                                            অরাজনৈতিক সংগঠন 'হেযবুত তওহীদ'-এর প্রতিষ্ঠাতা এমামুযযামান মোহাম্মদ বায়াজীদ খান পন্নী—যিনি প্রকৃত ইসলামের শিক্ষা প্রচার করেন—তিনি এই পরিবারেরই সন্তান।
                                        </p>
                                    </div>
                                    <div class="mt-4 w-100">
                                        <a href="{{ $founder ? route('leadership.show', $founder->slug) : route('about') . '#founder' }}" class="btn text-white px-4 py-1.8 w-100 fw-bold shadow-sm" style="background-color: #006A4E; border-color: #006A4E; color: white !important; border-radius: 5px; transition: all 0.3s ease; font-size: 13px; font-family: 'Hind Siliguri', sans-serif;">
                                            বিস্তারিত জীবনী <i class="fas fa-arrow-right ms-1" style="font-size: 11px;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Leader 2 -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-zoom d-flex flex-column" style="box-shadow: 0 10px 30px rgba(0,0,0,0.05) !important;">
                                <div class="leader-img-wrapper" style="height: 290px; overflow: hidden; position: relative;">
                                    <img src="{{ asset('uploads/leaders/emam_selim.png') }}" alt="জনাব হোসাইন মোহাম্মদ সেলিম" class="w-100 h-100 object-fit-cover grayscale-to-color" style="object-position: center 10%;">
                                </div>
                                <div class="card-body p-4 text-center d-flex flex-column align-items-center justify-content-between">
                                    <div>
                                        <h4 class="fw-bold mb-2" style="font-family: 'Baloo Da 2', sans-serif; color: #0f172a; font-size: 1.25rem; white-space: nowrap;">জনাব হোসাইন মোহাম্মদ সেলিম</h4>
                                        <div class="mb-3">
                                            <span class="px-3 py-1.5 rounded-pill fw-bold" style="font-size: 11px; border: 1.5px solid #cbd5e1; background-color: #f8fafc; color: #475569; font-family: 'Hind Siliguri', sans-serif; display: inline-block;">
                                                <i class="fas fa-user-shield me-1" style="color: #64748b;"></i> হেযবুত তওহীদের মাননীয় এমাম
                                            </span>
                                        </div>
                                        <p class="text-muted lh-lg mb-0" style="font-size: 0.9rem; text-align: justify; text-align-last: center; font-family: 'Hind Siliguri', sans-serif;">
                                            হোসেন মোহাম্মদ সেলিম বাংলাদেশের একজন বিশিষ্ট ব্যক্তিত্ব; তিনি অরাজনৈতিক আন্দোলন ‘হেযবুত তওহীদের ইমাম হিসেবে সুপরিচিত। বর্তমান সমাজব্যবস্থায় প্রোথিত অবিচারের বিরুদ্ধে তাঁর আপসহীন অবস্থান তাঁকে ব্যাপক পরিচিতি ও স্বীকৃতি এনে দিয়েছে।
                                        </p>
                                    </div>
                                    <div class="mt-4 w-100">
                                        <a href="{{ $currentLeader ? route('leadership.show', $currentLeader->slug) : route('about') . '#chairman' }}" class="btn text-white px-4 py-1.8 w-100 fw-bold shadow-sm" style="background-color: #006A4E; border-color: #006A4E; color: white !important; border-radius: 5px; transition: all 0.3s ease; font-size: 13px; font-family: 'Hind Siliguri', sans-serif;">
                                            বিস্তারিত জীবনী <i class="fas fa-arrow-right ms-1" style="font-size: 11px;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3.1. About Us Homepage Section -->
    <section class="about-homepage-section py-6" id="section-wrapper-about_homepage" style="background-color: #f8fafc; padding-top: 80px; padding-bottom: 80px;">
        <div class="container">
            <div class="row g-5 align-items-center">
                <!-- Left Side: Image of "At a Glance" -->
                <div class="col-lg-6">
                    <div class="hover-zoom shadow-md rounded-4 overflow-hidden" style="box-shadow: 0 15px 35px rgba(0,0,0,0.06) !important;">
                        <img src="{{ asset('/uploads/about/at-a-glance.jpg') }}" alt="এক নজরে হেযবুত তওহীদ" class="img-fluid w-100" style="transition: transform 0.5s ease;" />
                    </div>
                </div>

                <!-- Right Side: Content -->
                <div class="col-lg-6 ps-lg-5 text-start">
                    <span class="fw-bold text-uppercase tracking-wider" style="color: #64748b; font-size: 0.95rem; font-family: 'Baloo Da 2', sans-serif;">আমাদের পরিচিতি</span>
                    <h2 class="fw-bold mt-2 mb-3" style="font-family: 'Baloo Da 2', sans-serif; font-size: 2.25rem; color: #0f172a; font-weight: 800; line-height: 1.3;">এক নজরে হেযবুত তওহীদ</h2>

                    <p class="lead fw-bold mb-4" style="font-size: 1.15rem; line-height: 1.7; color: #1e293b; font-family: 'Hind Siliguri', sans-serif; text-align: justify;">
                        হেযবুত তওহীদ সম্পূর্ণ অরাজনৈতিক ধর্মীয় সংস্কারমূলক আন্দোলন যার মূল কাজই হলো মানবজাতিকে ন্যায়ের পক্ষে ঐক্যবদ্ধ করা এবং মানবজাতির অশান্তির মূল কারণ দাজ্জালের অনুসরণ না করে সমগ্র পৃথিবীতে সৃষ্টিকর্তার সার্বভৌমত্ব প্রতিষ্ঠা করা.
                    </p>

                    <p class="text-muted lh-lg mb-4" style="text-align: justify; font-size: 14.5px; font-family: 'Hind Siliguri', sans-serif;">
                        পুরো মানবজাতিকে আল্লাহর তওহীদের ভিত্তিতে অর্থাৎ সত্য ও ন্যায়ের পক্ষে, সকল অন্যায়ের বিরুদ্ধে ঐক্যবদ্ধ করাই হেযবুত তওহীদের মূল লক্ষ্য। মানবজীবনে সঠিক পথ, হেদায়াহ (Right Direction) ও সত্য জীবনব্যবস্থা প্রতিষ্ঠিত হলে সমস্ত মানবজাতি অন্যায় ও অবিচার থেকে মুক্তি পাবে। পৃথিবীতে প্রতিষ্ঠিত হবে অনাবিল শান্তি। সেই শান্তিময় পৃথিবী প্রতিষ্ঠার লক্ষ্যকে সামনে নিয়ে সংগ্রাম করে যাচ্ছে হেযবুত তওহীদের সদস্য-সদস্যারা।
                    </p>

                    <div>
                        <a href="/about-us" class="btn px-4 py-2 fw-bold text-white shadow-sm" style="background-color: #006A4E; border-color: #006A4E; color: white !important; border-radius: 5px; font-size: 14px; font-family: 'Baloo Da 2', sans-serif; transition: all 0.3s ease;">
                            আমাদের সম্পর্কে বিস্তারিত জানুন <i class="fas fa-info-circle ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3.2. Events & Programs Section (Redesigned for Premium Aesthetics!) -->
    @php
        $latestEvent = \App\Models\Blog::published()
            ->whereHas('category', function ($query) {
                $query->where('slug', 'events-and-programs')
                      ->orWhere('slug', 'events')
                      ->orWhere('slug', 'recent-events');
            })
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc')
            ->first();
    @endphp

    @if($latestEvent)
        <section class="events-homepage-section py-6 bg-white" id="section-wrapper-events_homepage" style="border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; padding-top: 90px; padding-bottom: 90px;">
            <div class="container">
                <div class="row g-5 align-items-center">

                    <!-- Left Side: Editorial Content -->
                    <div class="col-lg-6 text-start">
                        <!-- Category Badge & View All -->
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <span class="px-3 py-1.5 rounded-3 fw-bold text-uppercase" style="font-size: 12px; background-color: #e6f4f0; color: #006A4E; font-family: 'Baloo Da 2', sans-serif; letter-spacing: 0.5px;">
                                <i class="fas fa-calendar-alt me-1.5"></i> সাম্প্রতিক অনুষ্ঠান
                            </span>
                            <a href="/articles?category=events-and-programs" class="text-decoration-none fw-bold" style="color: #006A4E; font-size: 14.5px; font-family: 'Baloo Da 2', sans-serif; transition: color 0.2s ease;">
                                সকল অনুষ্ঠান দেখুন <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>

                        <!-- Title -->
                        <h2 class="fw-bold mb-4 text-dark" style="font-family: 'Baloo Da 2', sans-serif; font-size: 2.25rem; line-height: 1.35; color: #0f172a !important; font-weight: 800;">
                            {{ $latestEvent->title }}
                        </h2>

                        <!-- Excerpt -->
                        <p class="text-secondary mb-5" style="font-family: 'Hind Siliguri', sans-serif; font-size: 15px; line-height: 1.8; color: #475569 !important; text-align: justify;">
                            {{ Str::limit(strip_tags($latestEvent->content), 320) }}
                        </p>

                        <!-- CTA -->
                        <div>
                            <a href="{{ route('blog.detail', $latestEvent->slug) }}" class="btn px-4 py-2.2 fw-bold text-white shadow-sm" style="background-color: #006A4E; border-color: #006A4E; color: white !important; font-family: 'Hind Siliguri', sans-serif; font-size: 14px; border-radius: 5px; transition: all 0.3s ease;">
                                বিস্তারিত পড়ুন <i class="fas fa-arrow-right ms-2" style="font-size: 12px;"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Right Side: Editorial Image Card -->
                    <div class="col-lg-6">
                        <div class="position-relative d-flex justify-content-center justify-content-lg-end" style="padding-bottom: 20px; padding-left: 20px;">
                            <!-- Background decorative card -->
                            <div class="position-absolute" style="background-color: #f1f5f9; top: 20px; left: 0px; right: 20px; bottom: 0px; z-index: 1; border-radius: 16px;"></div>

                            <!-- Image card wrapper -->
                            <div class="position-relative overflow-hidden rounded-4 shadow-lg w-100" style="z-index: 2; aspect-ratio: 16/10; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;">
                                <img src="{{ asset($latestEvent->image_url) }}" alt="{{ $latestEvent->title }}" class="w-100 h-100 object-fit-cover hover-scale" style="transition: transform 0.5s ease;" />
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
        </section>
    @endif

    <!-- 3.2.1. News & Press Releases Section -->
    @if(isset($blogs) && $blogs->count() > 0)
    <div class="homepage-section-wrapper" id="section-wrapper-news_section">
        <section class="news-section py-6" style="background-color: #ffffff; padding-top: 80px; padding-bottom: 80px; border-bottom: 1px solid #f1f5f9;">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="fw-bold text-uppercase tracking-wider" style="color: #64748b; font-size: 0.95rem; font-family: 'Baloo Da 2', sans-serif;">সংবাদ ও বিবৃতি</span>
                    <h2 class="fw-bold mt-2" style="font-family: 'Baloo Da 2', sans-serif; font-size: 2.25rem; color: #0f172a; font-weight: 800; line-height: 1.3;">সর্বশেষ সংবাদ ও প্রেস রিলিজ</h2>
                    <div class="mx-auto mt-3" style="width: 60px; height: 3px; border-radius: 2px; background-color: #006A4E;"></div>
                </div>

                <div class="row g-4">
                    @foreach($blogs as $blog)
                        <div class="col-lg-4 col-md-6">
                            <div class="news-card shadow-sm h-100 d-flex flex-column">
                                <div class="news-card-img-wrapper" style="aspect-ratio: 16/10;">
                                    <img src="{{ asset($blog->image_url ?? 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?q=80&w=600') }}" alt="{{ $blog->title }}" class="w-100 h-100 object-fit-cover">
                                    @if($blog->category)
                                        <span class="position-absolute px-3 py-1 rounded-pill text-white fw-bold shadow-sm" style="background: #006A4E; font-size: 0.75rem; font-family: 'Hind Siliguri', sans-serif; top: 12px; left: 12px;">
                                            {{ $blog->category->name }}
                                        </span>
                                    @endif
                                </div>
                                <div class="p-4 d-flex flex-column flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 text-muted small mb-2" style="font-family: 'Hind Siliguri', sans-serif;">
                                        <i class="far fa-calendar-alt"></i> {{ $blog->published_at ? $blog->published_at->format('d M, Y') : $blog->created_at->format('d M, Y') }}
                                    </div>
                                    <h4 class="fw-bold mb-3" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.25rem; line-height: 1.4; color: #0f172a;">
                                        {{ Str::limit($blog->title, 65) }}
                                    </h4>
                                    <p class="text-muted small mb-4 flex-grow-1" style="font-family: 'Hind Siliguri', sans-serif; line-height: 1.6;">
                                        {!! Str::limit(strip_tags($blog->content), 120) !!}
                                    </p>
                                    <div class="mt-auto">
                                        <a href="{{ route('blog.detail', $blog->slug) }}" class="btn w-100 text-white fw-bold py-2 shadow-sm" style="background-color: #006A4E; border-color: #006A4E; border-radius: 5px; font-family: 'Hind Siliguri', sans-serif; font-size: 14px; transition: all 0.3s ease;">
                                            বিস্তারিত পড়ুন <i class="fas fa-arrow-right ms-2" style="font-size: 11px;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
    @endif

    <!-- 3.3. Struggle & Stats Section (Redesigned for World-Class Premium Aesthetics!) -->
    <section class="struggle-stats-section py-6 position-relative text-white text-center" id="section-wrapper-struggle_stats" style="background: linear-gradient(135deg, #006A4E 0%, #004D38 100%) !important; padding-top: 110px; padding-bottom: 110px; border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05);">
        <!-- Soft glow effects -->
        <div class="position-absolute rounded-circle" style="width: 400px; height: 400px; background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, rgba(0, 0, 0, 0) 70%); top: 50%; left: 50%; transform: translate(-50%, -50%); filter: blur(50px); pointer-events: none; z-index: 1;"></div>

        <div class="container position-relative" style="z-index: 2;">

            <!-- Glowing Premium Play Button -->
            <div class="d-flex justify-content-center mb-4">
                <a href="https://www.youtube.com/watch?v=EMZgDNuyQEc" target="_blank" class="play-glowing-btn d-flex align-items-center justify-content-center text-decoration-none" style="width: 80px; height: 80px; background: rgba(255,255,255,0.08); border: 2px solid rgba(255,255,255,0.2); border-radius: 50%; box-shadow: 0 0 30px rgba(255, 255, 255, 0.1); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); backdrop-filter: blur(8px);">
                    <div class="d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #ffffff; border-radius: 50%; transition: all 0.3s ease;">
                        <i class="fas fa-play" style="font-size: 1.25rem; color: #006A4E; margin-left: 4px;"></i>
                    </div>
                </a>
            </div>

            <!-- Title with gradient color text -->
            <h2 class="fw-bold mb-4 text-white" style="font-family: 'Baloo Da 2', sans-serif; font-size: 2.8rem; font-weight: 800; text-shadow: 0 2px 8px rgba(0,0,0,0.35);">
                হেযবুত তওহীদের <span style="color: #fbbf24;">সংগ্রামের ৩০ বছর</span>
            </h2>

            <!-- Paragraph Description -->
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8">
                    <p class="lh-lg mb-0 text-white" style="font-family: 'Hind Siliguri', sans-serif; font-size: 16.5px; line-height: 1.9; text-align: justify; text-align-last: center; text-shadow: 0 1px 3px rgba(0,0,0,0.4);">
                        হেযবুত তওহীদ একটি অরাজনৈতিক ধর্মীয় সংস্কারমূলক আন্দোলন। বাংলাদেশের প্রায় সবগুলো জেলাতেই আমাদের সাংগঠনিক কার্যক্রম বিস্তৃত। ধর্মব্যবসা, ধর্ম নিয়ে অপরাজনীতি, জঙ্গিবাদ ও সাম্প্রদায়িকতার বিরুদ্ধে আদর্শিক প্রচারণা চালিয়ে আমরা দেশব্যাপী সুপরিচিতি লাভ করতে পেরেছি।
                    </p>
                </div>
            </div>

            <!-- Glassmorphic Stats Row -->
            <div class="row g-4 justify-content-center">
                <!-- Stat 1 -->
                <div class="col-md-3 col-sm-6">
                    <div class="stat-glass-card p-4 text-center" style="background: rgba(255, 255, 255, 0.08); border: 1.5px solid rgba(255, 255, 255, 0.18); border-radius: 12px; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
                        <h3 class="text-white mb-1" style="font-size: 2.4rem; font-family: 'Baloo Da 2', sans-serif; font-weight: 800;">১,০০০+</h3>
                        <div style="width: 35px; height: 1.5px; background: rgba(255,255,255,0.25); margin: 10px auto;"></div>
                        <p class="mb-0 fw-bold text-white" style="font-family: 'Hind Siliguri', sans-serif; font-size: 16px;">জনসভা</p>
                    </div>
                </div>

                <!-- Stat 2 -->
                <div class="col-md-3 col-sm-6">
                    <div class="stat-glass-card p-4 text-center" style="background: rgba(255, 255, 255, 0.08); border: 1.5px solid rgba(255, 255, 255, 0.18); border-radius: 12px; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
                        <h3 class="text-white mb-1" style="font-size: 2.4rem; font-family: 'Baloo Da 2', sans-serif; font-weight: 800;">১০,০০০+</h3>
                        <div style="width: 35px; height: 1.5px; background: rgba(255,255,255,0.25); margin: 10px auto;"></div>
                        <p class="mb-0 fw-bold text-white" style="font-family: 'Hind Siliguri', sans-serif; font-size: 16px;">সেমিনার</p>
                    </div>
                </div>

                <!-- Stat 3 -->
                <div class="col-md-3 col-sm-6">
                    <div class="stat-glass-card p-4 text-center" style="background: rgba(255, 255, 255, 0.08); border: 1.5px solid rgba(255, 255, 255, 0.18); border-radius: 12px; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
                        <h3 class="text-white mb-1" style="font-size: 2.4rem; font-family: 'Baloo Da 2', sans-serif; font-weight: 800;">১,০০,০০০+</h3>
                        <div style="width: 35px; height: 1.5px; background: rgba(255,255,255,0.25); margin: 10px auto;"></div>
                        <p class="mb-0 fw-bold text-white" style="font-family: 'Hind Siliguri', sans-serif; font-size: 16px;">পথসভা</p>
                    </div>
                </div>

                <!-- Stat 4 -->
                <div class="col-md-3 col-sm-6">
                    <div class="stat-glass-card p-4 text-center" style="background: rgba(255, 255, 255, 0.08); border: 1.5px solid rgba(255, 255, 255, 0.18); border-radius: 12px; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
                        <h3 class="text-white mb-1" style="font-size: 2.4rem; font-family: 'Baloo Da 2', sans-serif; font-weight: 800;">৫০০+</h3>
                        <div style="width: 35px; height: 1.5px; background: rgba(255,255,255,0.25); margin: 10px auto;"></div>
                        <p class="mb-0 fw-bold text-white" style="font-family: 'Hind Siliguri', sans-serif; font-size: 16px;">ওয়াজ মাহফিল</p>
                    </div>
                </div>
            </div>
        </div>

        
    </section>

    <!-- 5. Videos Section -->
    <div class="homepage-section-wrapper" id="section-wrapper-videos_section">
        @php
            $homeVideos = \App\Models\Video::where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        @endphp
        @if($homeVideos->count() > 0)
            <!-- Video Gallery Section -->
            <section class="video-gallery-section py-6 bg-off-white">
                <div class="container">
                    <div class="text-center mb-5">
                        <span class="fw-bold text-uppercase tracking-wider" style="color: #64748b; font-size: 0.95rem; font-family: 'Baloo Da 2', sans-serif;">ভিডিও সম্ভার</span>
                        <h2 class="fw-bold mt-2" style="font-family: 'Baloo Da 2', sans-serif; font-size: 2.25rem; color: #0f172a; font-weight: 800; line-height: 1.3;">গুরুত্বপূর্ণ সেমিনার ও বক্তব্যসমূহ</h2>
                        <div class="mx-auto mt-3" style="width: 60px; height: 3px; border-radius: 2px; background-color: #10b981;"></div>
                    </div>
                    <div class="row g-4">
                        @foreach($homeVideos as $video)
                            <div class="col-lg-4 col-md-6">
                                <div class="card video-card bg-white h-100 border-0 shadow-sm rounded-3 overflow-hidden" style="box-shadow: 0 4px 15px rgba(0,0,0,0.03) !important;">
                                    <div class="video-iframe-wrapper">
                                        <iframe src="{{ $video->embed_url }}" title="{{ $video->title }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold text-dark mb-0" style="font-family: 'Hind Siliguri', sans-serif; line-height: 1.5; font-size: 14.5px;">{{ $video->title }}</h6>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <a href="{{ route('videos.index') }}" class="btn text-white px-4 py-2 fw-bold shadow-sm" style="background-color: #006A4E; border-color: #006A4E; color: white !important; border-radius: 5px; font-family: 'Hind Siliguri', sans-serif; font-size: 14px;">
                                সব ভিডিও দেখুন <i class="fas fa-arrow-right ms-2" style="font-size: 11px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </div>

    <!-- 5.0. Shortcut Navigation Section -->
    <div class="homepage-section-wrapper" id="section-wrapper-shortcuts_section">
        <section class="shortcuts-section py-5" style="background-color: #f8fafc;">
            <div class="container">
                <div class="row g-4">
                    <!-- Item 1: আমাদের বক্তব্য -->
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ route('videos.index') }}" class="shortcut-card-link text-decoration-none">
                            <div class="shortcut-card position-relative overflow-hidden rounded-4 d-flex flex-column align-items-center justify-content-center text-center text-white" style="height: 220px; background: #1e293b; border-radius: 16px;">
                                <div class="icon-circle bg-white d-flex align-items-center justify-content-center mb-3" style="width: 58px; height: 58px; border-radius: 50%; transition: all 0.3s ease;">
                                    <i class="fas fa-bullhorn" style="font-size: 1.4rem; color: #1e293b;"></i>
                                </div>
                                <h4 class="fw-bold mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.45rem; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">আমাদের বক্তব্য</h4>
                            </div>
                        </a>
                    </div>
                    <!-- Item 2: আমাদের কার্যক্রম -->
                    <div class="col-lg-4 col-md-6">
                        <a href="/blog/category/activities" class="shortcut-card-link text-decoration-none">
                            <div class="shortcut-card position-relative overflow-hidden rounded-4 d-flex flex-column align-items-center justify-content-center text-center text-white" style="height: 220px; background: #1e293b; border-radius: 16px;">
                                <div class="icon-circle bg-white d-flex align-items-center justify-content-center mb-3" style="width: 58px; height: 58px; border-radius: 50%; transition: all 0.3s ease;">
                                    <i class="fas fa-tasks" style="font-size: 1.4rem; color: #1e293b;"></i>
                                </div>
                                <h4 class="fw-bold mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.45rem; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">আমাদের কার্যক্রম</h4>
                            </div>
                        </a>
                    </div>
                    <!-- Item 3: আমাদের বইসমূহ -->
                    <div class="col-lg-4 col-md-6">
                        <a href="/publications" class="shortcut-card-link text-decoration-none">
                            <div class="shortcut-card position-relative overflow-hidden rounded-4 d-flex flex-column align-items-center justify-content-center text-center text-white" style="height: 220px; background: #1e293b; border-radius: 16px;">
                                <div class="icon-circle bg-white d-flex align-items-center justify-content-center mb-3" style="width: 58px; height: 58px; border-radius: 50%; transition: all 0.3s ease;">
                                    <i class="fas fa-book" style="font-size: 1.4rem; color: #1e293b;"></i>
                                </div>
                                <h4 class="fw-bold mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.45rem; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">আমাদের বইসমূহ</h4>
                            </div>
                        </a>
                    </div>
                    <!-- Item 4: অপপ্রচারের জবাব -->
                    <div class="col-lg-4 col-md-6">
                        <a href="/blog/category/rebuttal-and-legal" class="shortcut-card-link text-decoration-none">
                            <div class="shortcut-card position-relative overflow-hidden rounded-4 d-flex flex-column align-items-center justify-content-center text-center text-white" style="height: 220px; background: #1e293b; border-radius: 16px;">
                                <div class="icon-circle bg-white d-flex align-items-center justify-content-center mb-3" style="width: 58px; height: 58px; border-radius: 50%; transition: all 0.3s ease;">
                                    <i class="fas fa-comment-slash" style="font-size: 1.4rem; color: #1e293b;"></i>
                                </div>
                                <h4 class="fw-bold mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.45rem; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">অপপ্রচারের জবাব</h4>
                            </div>
                        </a>
                    </div>
                    <!-- Item 5: প্রবন্ধ / ব্লগ / কলাম -->
                    <div class="col-lg-4 col-md-6">
                        <a href="/blog/category/articles-and-editorials" class="shortcut-card-link text-decoration-none">
                            <div class="shortcut-card position-relative overflow-hidden rounded-4 d-flex flex-column align-items-center justify-content-center text-center text-white" style="height: 220px; background: #1e293b; border-radius: 16px;">
                                <div class="icon-circle bg-white d-flex align-items-center justify-content-center mb-3" style="width: 58px; height: 58px; border-radius: 50%; transition: all 0.3s ease;">
                                    <i class="fas fa-feather-alt" style="font-size: 1.4rem; color: #1e293b;"></i>
                                </div>
                                <h4 class="fw-bold mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.45rem; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">প্রবন্ধ / ব্লগ / কলাম</h4>
                            </div>
                        </a>
                    </div>
                    <!-- Item 6: নির্যাতনের ইতিহাস -->
                    <div class="col-lg-4 col-md-6">
                        <a href="/blog/category/history-of-persecution" class="shortcut-card-link text-decoration-none">
                            <div class="shortcut-card position-relative overflow-hidden rounded-4 d-flex flex-column align-items-center justify-content-center text-center text-white" style="height: 220px; background: #1e293b; border-radius: 16px;">
                                <div class="icon-circle bg-white d-flex align-items-center justify-content-center mb-3" style="width: 58px; height: 58px; border-radius: 50%; transition: all 0.3s ease;">
                                    <i class="fas fa-history" style="font-size: 1.4rem; color: #1e293b;"></i>
                                </div>
                                <h4 class="fw-bold mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.45rem; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">নির্যাতনের ইতিহাস</h4>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
        </section>
    </div>

    <!-- 5.1. Photo Gallery Section -->
    <div class="homepage-section-wrapper" id="section-wrapper-gallery_section">
        @php
            $galleryItems = $galleryPosts;
            if (empty($galleryItems) || count($galleryItems) == 0) {
                $galleryItems = \App\Models\Gallery::orderBy('gallery_order', 'asc')
                    ->orderBy('updated_at', 'desc')
                    ->take(8)
                    ->with(['blog', 'blog.category'])
                    ->get()
                    ->filter(function($post) {
                        if (empty($post->image_path)) return false;
                        $path = public_path($post->image_path);
                        return file_exists($path) && filesize($path) > 0;
                    })
                    ->values();
            }
        @endphp
        <section class="photo-gallery-section py-6">
            <div class="container">
                <div class="row g-0">
                    @if(count($galleryItems) > 0)
                        <div class="col-lg-3 col-md-6 order-2 order-lg-1">
                            <div class="d-flex flex-column gap-0">
                                @for($i = 1; $i <= 3; $i++)
                                    @if(isset($galleryItems[$i]))
                                        @php $post = $galleryItems[$i]; @endphp
                                        <a href="#" class="d-block text-decoration-none gallery-lightbox-trigger"
                                           data-image="{{ asset($post->image_path) }}"
                                           data-title="{{ $post->title ?? ($post->blog ? $post->blog->title : 'চিত্রশালা') }}"
                                           data-category="{{ ($post->blog && $post->blog->category) ? $post->blog->category->name : 'চিত্রশালা' }}"
                                           data-url="{{ $post->blog ? route('blog.detail', $post->blog->slug) : '#' }}">
                                            <div class="gallery-card small-card">
                                                <div class="gallery-zoom-icon">
                                                    <i class="fas fa-search-plus"></i>
                                                </div>
                                                <img src="{{ asset($post->image_path) }}" alt="{{ $post->title ?? ($post->blog ? $post->blog->title : '') }}" loading="lazy">
                                                <div class="gallery-card-overlay">
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                @endfor
                            </div>
                        </div>

                        <div class="col-lg-6 order-1 order-lg-2">
                            <div class="d-flex flex-column justify-content-between h-100 pb-0" style="min-height: 540px;">
                                <div class="text-center py-2 d-flex flex-column justify-content-center align-items-center" style="height: 160px;">
                                    <span class="fw-bold text-uppercase tracking-wider" style="color: #64748b; font-size: 0.95rem; font-family: 'Baloo Da 2', sans-serif;">চিত্রশালা</span>
                                    <h2 class="mt-2 fw-bold" style="font-family: 'Baloo Da 2', sans-serif; font-size: 2.25rem; color: #0f172a; font-weight: 800; line-height: 1.3;">ছবি গ্যালারি</h2>
                                    <p class="text-muted mt-2 mb-0" style="font-family: 'Hind Siliguri', sans-serif;">আন্দোলনের বিভিন্ন কর্মসূচী ও কর্মকাণ্ডের স্থিরচিত্রসমূহ</p>
                                    <div class="mt-2" style="width: 60px; height: 3px; border-radius: 2px; background-color: #10b981;"></div>
                                </div>
                                @if(isset($galleryItems[0]))
                                    @php $featuredPost = $galleryItems[0]; @endphp
                                    <a href="#" class="d-block text-decoration-none gallery-lightbox-trigger mt-auto"
                                       data-image="{{ asset($featuredPost->image_path) }}"
                                       data-title="{{ $featuredPost->title ?? ($featuredPost->blog ? $featuredPost->blog->title : 'চিত্রশালা') }}"
                                       data-category="{{ ($featuredPost->blog && $featuredPost->blog->category) ? $featuredPost->blog->category->name : 'চিত্রশালা' }}"
                                       data-url="{{ $featuredPost->blog ? route('blog.detail', $featuredPost->blog->slug) : '#' }}">
                                        <div class="gallery-card featured-card">
                                            <div class="gallery-zoom-icon">
                                                <i class="fas fa-search-plus fa-2x"></i>
                                            </div>
                                            <img src="{{ asset($featuredPost->image_path) }}" alt="{{ $featuredPost->title ?? ($featuredPost->blog ? $featuredPost->blog->title : '') }}" loading="lazy">
                                            <div class="gallery-card-overlay">
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 order-3">
                            <div class="d-flex flex-column gap-0">
                                @for($i = 4; $i <= 6; $i++)
                                    @if(isset($galleryItems[$i]))
                                        @php $post = $galleryItems[$i]; @endphp
                                        <a href="#" class="d-block text-decoration-none gallery-lightbox-trigger"
                                           data-image="{{ asset($post->image_path) }}"
                                           data-title="{{ $post->title ?? ($post->blog ? $post->blog->title : 'চিত্রশালা') }}"
                                           data-category="{{ ($post->blog && $post->blog->category) ? $post->blog->category->name : 'চিত্রশালা' }}"
                                           data-url="{{ $post->blog ? route('blog.detail', $post->blog->slug) : '#' }}">
                                            <div class="gallery-card small-card">
                                                <div class="gallery-zoom-icon">
                                                    <i class="fas fa-search-plus"></i>
                                                </div>
                                                <img src="{{ asset($post->image_path) }}" alt="{{ $post->title ?? ($post->blog ? $post->blog->title : '') }}" loading="lazy">
                                                <div class="gallery-card-overlay">
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    @endif
                </div>
                @if(count($galleryItems) > 0)
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <a href="{{ route('gallery.index') }}" class="btn px-4 py-2 fw-bold text-white shadow-sm" style="background-color: #006A4E; border-color: #006A4E; color: white !important; border-radius: 5px; font-family: 'Hind Siliguri', sans-serif; font-size: 14px; transition: all 0.3s ease;">
                                সব ছবি দেখুন <i class="fas fa-arrow-right ms-2" style="font-size: 11px;"></i>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="far fa-images fa-3x mb-3 text-muted opacity-50"></i>
                        <p class="mb-0">কোনো স্থিরচিত্র পাওয়া যায়নি!</p>
                    </div>
                @endif
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
                            <h4 id="lightbox-title" class="fw-bold text-white mb-0 text-center" style="font-family: 'Baloo Da 2', sans-serif;"></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 5.2. Activities Section -->
    <div class="homepage-section-wrapper" id="section-wrapper-activities_section">
        <!-- Social & Humanitarian Activities Section -->
        <section class="activities-section py-6">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="fw-bold text-uppercase tracking-wider" style="color: #64748b; font-size: 0.95rem; font-family: 'Baloo Da 2', sans-serif;">কর্মধারা</span>
                    <h2 class="fw-bold mt-2" style="font-family: 'Baloo Da 2', sans-serif; font-size: 2.25rem; color: #0f172a; font-weight: 800; line-height: 1.3;">সেবামূলক ও সচেতনতা কার্যক্রম</h2>
                    <div class="mx-auto mt-3" style="width: 60px; height: 3px; border-radius: 2px; background-color: #10b981;"></div>
                </div>
                <div class="row g-4">
                    @forelse($activities as $activity)
                        <div class="col-lg-4 col-md-6">
                            <div class="card activity-card h-100 bg-white border-0 shadow-sm rounded-4 overflow-hidden" style="border-top: 4px solid #006A4E !important;">
                                <div class="activity-img-wrapper" style="height: 200px; overflow: hidden;">
                                    <img src="{{ $activity->image_url }}" alt="{{ $activity->title }}" class="w-100 h-100 object-fit-cover">
                                </div>
                                <div class="card-body p-4 d-flex flex-column">
                                    <h5 class="fw-bold mb-2" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.25rem; line-height: 1.4;">
                                        <a href="{{ route('activities.show', $activity->slug) }}" class="text-decoration-none activity-title">
                                            {{ $activity->title }}
                                        </a>
                                    </h5>
                                    <p class="text-muted lh-lg flex-grow-1" style="font-size: 0.9rem; text-align: justify;">
                                        {{ Str::limit($activity->description, 150) }}
                                    </p>
                                    <div class="mt-3 pt-3 border-top border-light">
                                        <a href="{{ route('activities.show', $activity->slug) }}" class="btn px-4 py-2 fw-bold text-white shadow-sm w-100" style="background-color: #006A4E; border-color: #006A4E; color: white !important; border-radius: 5px; font-family: 'Hind Siliguri', sans-serif; font-size: 13px; transition: all 0.3s ease;">
                                            বিস্তারিত পড়ুন <i class="fas fa-arrow-right ms-1" style="font-size: 0.75rem;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
                @if(count($activities) > 0)
                    <div class="text-center mt-5">
                        <a href="{{ route('activities.index') }}" class="btn px-4 py-2 fw-bold text-white shadow-sm" style="background-color: #006A4E; border-color: #006A4E; color: white !important; border-radius: 5px; font-family: 'Hind Siliguri', sans-serif; font-size: 14px; transition: all 0.3s ease;">
                            সব কার্যক্রম দেখুন <i class="fas fa-arrow-right ms-2" style="font-size: 11px;"></i>
                        </a>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <!-- 5.2.1. Popular Publications Section -->
    <div class="homepage-section-wrapper" id="section-wrapper-popular_books">
        @php
            $popularBooks = \App\Models\Book::where('is_active', true)
                ->where(function($q) {
                    $q->where('is_popular', true)->orWhere('is_bestseller', true);
                })
                ->orderBy('popular_order', 'asc')
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();
        @endphp
        @if($popularBooks->count() > 0)
            <section class="popular-books-section py-6 bg-white" style="border-top: 1px solid #f1f5f9;">
                <div class="container">
                    <div class="text-center mb-5">
                        <span class="fw-bold text-uppercase tracking-wider" style="color: #64748b; font-size: 0.95rem; font-family: 'Baloo Da 2', sans-serif;">প্রকাশনা</span>
                        <h2 class="fw-bold mt-2" style="font-family: 'Baloo Da 2', sans-serif; font-size: 2.25rem; color: #0f172a; font-weight: 800; line-height: 1.3;">আমাদের প্রকাশনাসমূহ</h2>
                        <div class="mx-auto mt-3" style="width: 60px; height: 3px; border-radius: 2px; background-color: #10b981;"></div>
                        <p class="text-muted mt-3 mb-0" style="font-family: 'Hind Siliguri', sans-serif; font-size: 1.15rem; line-height: 1.6; font-weight: 500; color: #475569;">
                            হেযবুত তওহীদের একটি নিবন্ধ বা বই<br>পাল্টে দিতে পারে আপনার জীবন!
                        </p>
                    </div>
                    <div class="row g-4">
                        @foreach($popularBooks as $book)
                            <div class="col-lg-3 col-md-6">
                                <div class="card h-100 border-0 shadow-sm rounded-4 p-3 bg-white text-center" style="box-shadow: 0 10px 30px rgba(0,0,0,0.03) !important; border: 1px solid #f1f5f9 !important;">
                                    <div class="book-cover-wrapper mb-3 position-relative rounded-3 overflow-hidden shadow-sm mx-auto" style="height: 280px; width: 100%; max-width: 190px;">
                                        <img src="{{ $book->image_url }}" alt="{{ $book->title }}" class="w-100 h-100 object-fit-cover">
                                        @if($book->is_bestseller)
                                            <span class="position-absolute top-0 start-0 badge bg-danger text-white m-2 px-2 py-1" style="font-size: 0.7rem; border-radius: 4px;">বেস্টসেলার</span>
                                        @endif
                                    </div>
                                    <h5 class="fw-bold text-dark mb-1 text-truncate" style="font-family: 'Hind Siliguri', sans-serif; font-size: 1.1rem;" title="{{ $book->title }}">{{ $book->title }}</h5>
                                    <p class="text-muted small mb-2" style="font-family: 'Hind Siliguri', sans-serif;">{{ $book->writer }}</p>

                                    <div class="mt-auto">
                                        @if($book->price > 0)
                                            <div class="mb-3">
                                                <span class="text-success fw-bold" style="font-size: 1.1rem; font-family: 'Hind Siliguri', sans-serif;">৳{{ $book->price }}</span>
                                                @if($book->old_price > 0)
                                                    <span class="text-muted text-decoration-line-through ms-2" style="font-size: 0.9rem; font-family: 'Hind Siliguri', sans-serif;">৳{{ $book->old_price }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="mb-3">
                                                <span class="badge bg-light text-success border px-3 py-1" style="font-family: 'Hind Siliguri', sans-serif; font-size: 0.8rem; font-weight: normal;">ফ্রি ডাউনলোড</span>
                                            </div>
                                        @endif
                                        <a href="{{ route('books.show', $book->slug) }}" class="btn px-4 py-2 fw-bold text-white shadow-sm w-100" style="background-color: #006A4E; border-color: #006A4E; color: white !important; border-radius: 5px; font-family: 'Hind Siliguri', sans-serif; font-size: 13px; transition: all 0.3s ease;">
                                            বিস্তারিত পড়ুন <i class="fas fa-arrow-right ms-1" style="font-size: 0.75rem;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row mt-5">
                        <div class="col-12 text-center">
                            <a href="{{ route('books.index') }}" class="btn px-4 py-2 fw-bold text-white shadow-sm" style="background-color: #006A4E; border-color: #006A4E; color: white !important; border-radius: 5px; font-family: 'Hind Siliguri', sans-serif; font-size: 14px; transition: all 0.3s ease;">
                                সব বই দেখুন <i class="fas fa-arrow-right ms-2" style="font-size: 11px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </div>

    <!-- 5.3. Suggestion Section -->
    <div class="homepage-section-wrapper" id="section-wrapper-suggestion_section">
        <!-- Citizens Feedback Section -->
        <section class="feedback-section py-6" style="background-color: #f8fafc;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5 mb-5 mb-lg-0 text-start">
                        <span class="fw-bold text-uppercase tracking-wider mb-3 d-inline-block" style="color: #64748b; font-size: 0.95rem; font-family: 'Baloo Da 2', sans-serif;">পরামর্শ কর্নার</span>
                        <h2 class="fw-bold mb-3" style="font-family: 'Baloo Da 2', sans-serif; font-size: 2.25rem; color: #0f172a; font-weight: 800; line-height: 1.3;">আপনার মূল্যবান মতামত ও পরামর্শ দিন</h2>
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
                                        <button type="submit" class="btn w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" style="background-color: #006A4E; border: 1px solid #006A4E; color: white !important; border-radius: 5px; font-family: 'Hind Siliguri', sans-serif; font-size: 15px; padding: 12px 20px; transition: all 0.3s ease;">
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
    </div>

    <!-- 4. Testimonials Section -->
    <div class="homepage-section-wrapper" id="section-wrapper-testimonials_section">
        <section class="testimonials-section py-6 position-relative overflow-hidden" style="background: #f8fafc !important; background-image: none !important; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9;">
            <div class="position-absolute rounded-circle" style="width: 250px; height: 250px; background: rgba(16, 185, 129, 0.04); top: -50px; right: -50px; filter: blur(60px); pointer-events: none;"></div>
            <div class="position-absolute rounded-circle" style="width: 250px; height: 250px; background: rgba(16, 185, 129, 0.03); bottom: -50px; left: -50px; filter: blur(60px); pointer-events: none;"></div>

            <div class="container position-relative" style="z-index: 2;">
                <div class="text-center mb-5">
                    <span class="fw-bold text-uppercase tracking-wider" style="color: #64748b; font-size: 0.95rem; font-family: 'Baloo Da 2', sans-serif;">নাগরিক মতামত ও সুধী বাণী</span>
                    <h2 class="fw-bold mt-2" style="font-family: 'Baloo Da 2', sans-serif; font-size: 2.25rem; color: #0f172a; font-weight: 800; line-height: 1.3;">শুভাকাঙ্ক্ষীদের বক্তব্য</h2>
                    <div class="mx-auto mt-3" style="width: 60px; height: 3px; border-radius: 2px; background-color: #10b981;"></div>
                </div>

                <div id="smartTestimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="8000">
                    <div class="carousel-inner">
                        @php
                            $testimonialChunks = $testimonials->chunk(3);
                        @endphp
                        @forelse($testimonialChunks as $chunkIndex => $chunk)
                            <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                                <div class="row g-4 justify-content-center">
                                    @foreach($chunk as $testimonial)
                                        <div class="col-lg-4 col-md-6">
                                            <div class="testimonial-card">
                                                <i class="fas fa-quote-right position-absolute" style="font-size: 4.5rem; color: rgba(16, 185, 129, 0.04); bottom: 15px; right: 20px; pointer-events: none;"></i>
                                                <div>
                                                    <div class="mb-3 text-warning fs-6">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $testimonial->rating)
                                                                <i class="fas fa-star" style="color: #fbbf24;"></i>
                                                            @else
                                                                <i class="far fa-star" style="color: #cbd5e1;"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <p style="font-family: 'Hind Siliguri', sans-serif;">
                                                        @if(mb_strlen($testimonial->content) > 130)
                                                            {{ mb_substr($testimonial->content, 0, 125) }}...
                                                            <a href="javascript:void(0);" class="read-more-btn" data-name="{{ $testimonial->name }}" data-designation="{{ $testimonial->designation }}" data-content="{{ $testimonial->content }}" data-avatar="{{ $testimonial->avatar_url }}">বিস্তারিত পড়ুন</a>
                                                        @else
                                                            {{ $testimonial->content }}
                                                        @endif
                                                    </p>
                                                </div>
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
                            <div class="carousel-item active">
                                <div class="row g-4 justify-content-center">
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
                                    <div class="col-lg-4 col-md-6">
                                        <div class="testimonial-card">
                                            <div>
                                                <div class="mb-3 text-warning"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                                                <p>একটি শান্তিময় ও ধর্মীয় সম্প্রীতিপূর্ণ সমাজ বিনির্মাণে তাদের অরাজনৈতিক আন্দোলন ও আদেশিক লড়াই সমাজে ইতিবাচক পরিবর্তন আনতে সক্ষম হয়েছে। তাদের সকল মানবিক কাজের সফলতা কামনা করি।</p>
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
                <div class="text-center mt-5">
                    <a href="{{ url('/feedback') }}" class="btn px-4 py-2 fw-bold text-white shadow-sm" style="background-color: #006A4E; border-color: #006A4E; color: white !important; border-radius: 5px; font-family: 'Hind Siliguri', sans-serif; font-size: 14px; transition: all 0.3s ease;">
                        <i class="fas fa-comments me-2" style="font-size: 13px;"></i> সকল সুধী মতামত দেখুন
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

        <!-- Live Broadcast Video Lightbox Modal -->
        <div class="modal fade" id="liveBroadcastModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg bg-dark text-white" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header border-0 pb-0" style="position: absolute; right: 10px; top: 10px; z-index: 10;">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="ratio ratio-16x9">
                            <iframe id="live-player-iframe" src="" allow="autoplay; encrypted-media" allowfullscreen style="border: none;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 5.4. Join Section -->
    <div class="homepage-section-wrapper" id="section-wrapper-join_section">
        <!-- Join Us CTA Banner Section -->
        <section class="cta-section py-6 text-white text-center position-relative overflow-hidden" style="background: #006A4E !important; background-image: none !important;">
            <div class="position-absolute rounded-circle" style="width: 300px; height: 300px; background: rgba(52, 211, 153, 0.05); top: -80px; left: -80px; filter: blur(60px);"></div>
            <div class="position-absolute rounded-circle" style="width: 350px; height: 350px; background: rgba(16, 185, 129, 0.04); bottom: -100px; right: -100px; filter: blur(70px);"></div>
            <div class="container position-relative" style="z-index: 2;">
                <div class="row justify-content-center">
                    <div class="col-lg-8 py-4">
                        <i class="fas fa-hand-holding-heart fa-3x mb-4" style="color: #fbbf24; filter: drop-shadow(0 0 8px rgba(251, 191, 36, 0.3));"></i>
                        <h2 class="fw-bold text-white mb-3" style="font-size: 2.2rem; font-family: 'Baloo Da 2', sans-serif;">আল্লাহর তওহীদ ভিত্তিক সত্যদীন প্রতিষ্ঠার আন্দোলনে যোগ দিন</h2>
                        <p class="lead mb-4 mx-auto" style="font-size: 1.1rem; line-height: 1.8; color: #e2e8f0 !important; max-width: 680px; font-weight: 400; font-family: 'Hind Siliguri', sans-serif;">
                            সমাজ থেকে অন্যায়-অশান্তি দূর করে ন্যায় ও সুবিচার প্রতিষ্ঠার লক্ষ্যে আল্লাহর তওহীদভিত্তিক জীবনব্যবস্থা বিশ্বব্যাপী প্রতিষ্ঠার সংগ্রামে আপনিও শরীক হোন। উগ্রবাদ, সাম্প্রদায়িকতা ও সকল অবিচারের বিরুদ্ধে এবং মানবতার কল্যাণে হেযবুত তওহীদের সহযাত্রী হোন।
                        </p>
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <a href="/join" class="btn px-4 py-2.5 fw-bold shadow-sm" style="background-color: #fbbf24; border-color: #fbbf24; color: #022c22 !important; border-radius: 5px; font-family: 'Hind Siliguri', sans-serif; font-size: 15px; transition: all 0.3s ease;">সদস্য ফরম পূরণ করুন</a>
                            <a href="/contact" class="btn px-4 py-2.5 fw-bold text-white shadow-sm" style="background-color: transparent; border: 1.5px solid #ffffff; color: white !important; border-radius: 5px; font-family: 'Hind Siliguri', sans-serif; font-size: 15px; transition: all 0.3s ease;">যোগাযোগ করুন</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>






    <!-- Styles for interactive controls and feedbacks -->
    

@push('scripts')
<script>
$(document).ready(function() {
    // Live Broadcast Video Lightbox Trigger
    var liveBroadcastModal = null;
    $(document).on('click', '.live-modal-trigger', function() {
        var url = $(this).data('url');
        $('#live-player-iframe').attr('src', url);
        liveBroadcastModal = new bootstrap.Modal(document.getElementById('liveBroadcastModal'));
        liveBroadcastModal.show();
    });
    $('#liveBroadcastModal').on('hidden.bs.modal', function() {
        $('#live-player-iframe').attr('src', '');
    });

    // Branch Search/Filter Handler
    $('#branch-search').on('input', function() {
        var query = $(this).val().toLowerCase().trim();
        $('.branch-item-card-wrapper').each(function() {
            var name = $(this).data('name') || '';
            var address = $(this).data('address') || '';
            if (name.indexOf(query) !== -1 || address.indexOf(query) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Spotify-style Song Player Handler
    var audio = document.getElementById('main-audio');
    var playPauseBtn = $('#play-pause-btn');
    var playPauseIcon = $('#play-pause-icon');
    var musicDisc = $('#music-disc');
    var progressBar = $('#progress-bar');
    var progressContainer = $('#progress-container');
    var currentTimeEl = $('#current-time');
    var durationTimeEl = $('#duration-time');
    var currentSongTitle = $('#current-song-title');
    var playlistItems = $('.playlist-item');

    function formatTime(secs) {
        var m = Math.floor(secs / 60);
        var s = Math.floor(secs % 60);
        return m + ':' + (s < 10 ? '0' : '') + s;
    }

    if (audio) {
        audio.addEventListener('timeupdate', function() {
            if (audio.duration) {
                var pct = (audio.currentTime / audio.duration) * 100;
                progressBar.css('width', pct + '%');
                currentTimeEl.text(formatTime(audio.currentTime));
            }
        });

        audio.addEventListener('loadedmetadata', function() {
            durationTimeEl.text(formatTime(audio.duration));
        });

        audio.addEventListener('ended', function() {
            playNextSong();
        });

        playPauseBtn.on('click', function() {
            if (audio.paused) {
                audio.play();
                playPauseIcon.removeClass('fa-play').addClass('fa-pause');
                musicDisc.css('animation-play-state', 'running');
            } else {
                audio.pause();
                playPauseIcon.removeClass('fa-pause').addClass('fa-play');
                musicDisc.css('animation-play-state', 'paused');
            }
        });

        progressContainer.on('click', function(e) {
            var width = $(this).width();
            var clickX = e.offsetX;
            audio.currentTime = (clickX / width) * audio.duration;
        });

        function playSongAtIndex(index) {
            var item = playlistItems.eq(index);
            if (item.length) {
                playlistItems.removeClass('active');
                item.addClass('active');
                var src = item.data('src');
                var title = item.data('title');

                audio.src = src;
                currentSongTitle.text(title);
                audio.load();
                audio.play();

                playPauseIcon.removeClass('fa-play').addClass('fa-pause');
                musicDisc.css('animation-play-state', 'running');
            }
        }

        function playNextSong() {
            var activeIndex = $('.playlist-item.active').data('index');
            var nextIndex = activeIndex + 1;
            if (nextIndex >= playlistItems.length) {
                nextIndex = 0;
            }
            playSongAtIndex(nextIndex);
        }

        function playPrevSong() {
            var activeIndex = $('.playlist-item.active').data('index');
            var prevIndex = activeIndex - 1;
            if (prevIndex < 0) {
                prevIndex = playlistItems.length - 1;
            }
            playSongAtIndex(prevIndex);
        }

        playlistItems.on('click', function() {
            var index = $(this).data('index');
            playSongAtIndex(index);
        });

        $('#next-song-btn').on('click', playNextSong);
        $('#prev-song-btn').on('click', playPrevSong);
    }

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

        var lightboxModal = new bootstrap.Modal(document.getElementById('galleryLightboxModal'));
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

        var testimonialModal = new bootstrap.Modal(document.getElementById('testimonialDetailModal'));
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
