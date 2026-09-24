@extends('theme::layouts.app')

@section('title', 'আমাদের সম্পর্কে - হেযবুত তওহীদ')

@section('content')

    <!-- Inner Page Header Banner -->
    @include('theme::partials.hero_banner', [
        'title' => $wp_intro['title'],
        'subtitle' => $wp_intro['subtitle'],
        'badge_text' => 'পরিচিতি ও ইতিহাস',
        'badge_icon' => 'fas fa-info-circle'
    ])

    <!-- Style for about page -->
    <style>
        .about-card {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: none;
            transition: all 0.3s ease;
        }
        .about-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        .border-green-top {
            border-top: 5px solid #10b981 !important;
        }
        .bg-gradient-green {
            background: linear-gradient(135deg, #004b37 0%, #002d21 100%);
            color: #ffffff;
        }
        .text-dark-green {
            color: #10b981;
        }
        .bg-light-green {
            background-color: #064e3b;
            color: #10b981 !important;
        }
        .step-badge {
            background: #10b981;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 12px;
        }
        .structure-node {
            background: #111827;
            border: 2px solid #10b981;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            font-weight: bold;
            color: #10b981;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            position: relative;
        }
        .structure-arrow {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #10B981;
            font-size: 1.5rem;
        }
        .book-badge {
            background: #1f2937;
            color: #e5e7eb;
            border-left: 3px solid #10B981;
            padding: 8px 16px;
            font-size: 0.95rem;
            font-weight: 500;
            border-radius: 0 8px 8px 0;
            transition: all 0.2s ease;
        }
        .book-badge:hover {
            background: #374151;
            transform: translateX(3px);
        }
        .doc-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            background: #1f2937;
        }
        .doc-card:hover {
            transform: translateY(-5px);
        }
        .doc-thumb {
            position: relative;
            aspect-ratio: 16/9;
            background-size: cover;
            background-position: center;
        }
        .play-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
            background: rgba(220, 38, 38, 0.9);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.2s ease;
        }
        .doc-card:hover .play-btn {
            background: #dc2626;
            scale: 1.1;
        }
        .page-body p {
            color: #9ca3af;
            text-align: justify;
        }
        .about-card.bg-white {
            background-color: #111827 !important;
            color: #f3f4f6 !important;
        }
        .text-dark {
            color: #f3f4f6 !important;
        }
    </style>

    <div class="py-6 bg-dark" style="font-family: 'Baloo Da 2', sans-serif; background-color: #0f172a !important;">
        <div class="container">
            <!-- 1. এক নজরে হেযবুত তওহীদ -->
            <div class="card about-card border-green-top p-4 p-md-5 bg-white mb-5">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <img src="{{ $wp_intro['image'] }}" alt="{{ $wp_intro['title'] }}" class="img-fluid rounded-4 shadow-sm">
                    </div>
                    <div class="col-lg-6 ps-lg-5">
                        <span class="text-gold fw-bold text-uppercase tracking-wider">পরিচয়</span>
                        <h2 class="text-dark-green fw-bold mb-4">{{ $wp_intro['title'] }}</h2>
                        <div class="page-body">
                            <p class="fs-5 lh-lg">{{ $wp_intro['text'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. প্রতিষ্ঠা ও প্রতিষ্ঠাতা -->
            <div class="card about-card p-4 p-md-5 bg-white mb-5">
                <div class="row align-items-center">
                    <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                        <img src="{{ $wp_founder['image'] }}" alt="{{ $wp_founder['title'] }}" class="img-fluid rounded-4 shadow-sm">
                    </div>
                    <div class="col-lg-6 pe-lg-5 order-lg-1">
                        <span class="text-gold fw-bold text-uppercase tracking-wider">ঐতিহাসিক সূচনা</span>
                        <h2 class="text-dark-green fw-bold mb-4">{{ $wp_founder['title'] }}</h2>
                        <div class="page-body">
                            <p class="lh-lg">{{ $wp_founder['text'] }}</p>
                        </div>
                        <a href="{{ $wp_founder['link'] }}" class="btn btn-success rounded-pill px-4 mt-3">
                            প্রতিষ্ঠাতা সম্পর্কে বিস্তারিত <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. কাঠামো (Structure) -->
            <div class="card about-card p-4 p-md-5 bg-white mb-5">
                <div class="text-center mb-5">
                    <span class="text-gold fw-bold text-uppercase tracking-wider">সুশৃঙ্খল ব্যবস্থাপনা</span>
                    <h2 class="text-dark-green fw-bold">{{ $wp_structure['title'] }}</h2>
                    <p class="text-muted mt-2">হেযবুত তওহীদের সাংগঠনিক স্তরবিন্যাস</p>
                </div>
                <div class="row justify-content-center align-items-center g-3">
                    <div class="col-md-3">
                        <div class="structure-node shadow-sm">
                            <i class="fas fa-crown fa-2x mb-3 text-gold"></i>
                            <h4>{{ $wp_structure['roles'][0] }}</h4>
                        </div>
                    </div>
                    <div class="col-md-1 structure-arrow">
                        <i class="fas fa-arrow-right d-none d-md-block"></i>
                        <i class="fas fa-arrow-down d-block d-md-none py-2"></i>
                    </div>
                    <div class="col-md-3">
                        <div class="structure-node shadow-sm">
                            <i class="fas fa-user-shield fa-2x mb-3 text-success"></i>
                            <h4>{{ $wp_structure['roles'][1] }}</h4>
                        </div>
                    </div>
                    <div class="col-md-1 structure-arrow">
                        <i class="fas fa-arrow-right d-none d-md-block"></i>
                        <i class="fas fa-arrow-down d-block d-md-none py-2"></i>
                    </div>
                    <div class="col-md-3">
                        <div class="structure-node shadow-sm">
                            <i class="fas fa-users fa-2x mb-3 text-primary"></i>
                            <h4>{{ $wp_structure['roles'][2] }}</h4>
                            <span class="small text-muted d-block mt-2">মোজাহেদ ও মোজাহেদা</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. বর্তমান এমাম -->
            <div class="card about-card p-4 p-md-5 bg-white mb-5">
                <div class="row align-items-center">
                    <div class="col-lg-5 mb-4 mb-lg-0 text-center">
                        <img src="{{ $wp_leader['image'] }}" alt="{{ $wp_leader['title'] }}" class="img-fluid rounded-4 shadow-sm" style="max-height: 400px; object-fit: cover;">
                    </div>
                    <div class="col-lg-7 ps-lg-5">
                        <span class="text-gold fw-bold text-uppercase tracking-wider">নেতৃত্ব</span>
                        <h2 class="text-dark-green fw-bold mb-4">{{ $wp_leader['title'] }}</h2>
                        <div class="page-body">
                            <p class="lh-lg">{{ $wp_leader['text'] }}</p>
                        </div>
                        <a href="{{ $wp_leader['link'] }}" class="btn btn-success rounded-pill px-4 mt-3">
                            এমাম সম্পর্কে বিস্তারিত <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 5. মূলনীতি ও কর্মসূচি -->
            <div class="row g-4 mb-5">
                <!-- মূলনীতি -->
                <div class="col-lg-6">
                    <div class="card about-card border-green-top p-4 p-md-5 h-100 bg-white">
                        <h3 class="text-dark-green fw-bold mb-4"><i class="fas fa-list-check me-2 text-gold"></i> {{ $wp_principles['title'] }}</h3>
                        <ul class="list-unstyled">
                            @foreach($wp_principles['items'] as $item)
                                <li class="d-flex align-items-start mb-3">
                                    <i class="fas fa-check-circle text-success mt-1 me-3 fs-5"></i>
                                    <span class="text-dark fs-5">{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- কর্মসূচি -->
                <div class="col-lg-6">
                    <div class="card about-card border-green-top p-4 p-md-5 h-100 bg-gradient-green text-white">
                        <h3 class="fw-bold mb-4" style="color: #10B981;"><i class="fas fa-bullseye me-2"></i> {{ $wp_programs['title'] }}</h3>
                        <p class="small lh-lg mb-4 text-white-50">{{ $wp_programs['subtitle'] }}</p>
                        <ul class="list-unstyled">
                            @foreach($wp_programs['items'] as $index => $item)
                                <li class="d-flex align-items-center mb-3">
                                    <span class="step-badge">{{ $index + 1 }}</span>
                                    <span class="fs-5 text-white">{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 6. কর্মপ্রক্রিয়া ও অন্যান্য সেকশন (Alternating Cards) -->
            @foreach($wp_sections as $index => $section)
                <div class="card about-card p-4 p-md-5 bg-white mb-5">
                    <div class="row align-items-center">
                        @if($section['layout'] === 'right')
                            <div class="col-lg-6 mb-4 mb-lg-0">
                                <img src="{{ $section['image'] }}" alt="{{ $section['title'] }}" class="img-fluid rounded-4 shadow-sm">
                            </div>
                            <div class="col-lg-6 ps-lg-5">
                                <h3 class="text-dark-green fw-bold mb-4">{{ $section['title'] }}</h3>
                                <div class="page-body">
                                    <p class="lh-lg">{{ $section['text'] }}</p>
                                </div>
                            </div>
                        @else
                            <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                                <img src="{{ $section['image'] }}" alt="{{ $section['title'] }}" class="img-fluid rounded-4 shadow-sm">
                            </div>
                            <div class="col-lg-6 pe-lg-5 order-lg-1">
                                <h3 class="text-dark-green fw-bold mb-4">{{ $section['title'] }}</h3>
                                <div class="page-body">
                                    <p class="lh-lg">{{ $section['text'] }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            <!-- 7. পরিচালিত প্রতিষ্ঠান -->
            <div class="card about-card p-4 p-md-5 bg-white mb-5">
                <div class="text-center mb-4">
                    <span class="text-gold fw-bold text-uppercase tracking-wider">আমাদের প্রতিষ্ঠানসমূহ</span>
                    <h2 class="text-dark-green fw-bold">{{ $wp_institutions['title'] }}</h2>
                </div>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    @foreach($wp_institutions['items'] as $item)
                        <div class="px-4 py-3 bg-light-green fw-bold rounded-pill shadow-sm" style="font-size: 1.1rem; border: 1px solid #10b981;">
                            <i class="fas fa-building me-2"></i> {{ $item }}
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 8. প্রকাশিত পুস্তকসমূহ -->
            <div class="card about-card border-green-top p-4 p-md-5 bg-white mb-5">
                <div class="text-center mb-5">
                    <span class="text-gold fw-bold text-uppercase tracking-wider">জ্ঞানের আলো</span>
                    <h2 class="text-dark-green fw-bold">{{ $wp_books['title'] }}</h2>
                    <p class="text-muted mt-2">এমামুয্যামান জনাব মোহাম্মদ বায়াজীদ খান পন্নী রচিত গুরুত্বপূর্ণ গ্রন্থসমূহ</p>
                </div>
                <div class="row g-3">
                    @foreach($wp_books['items'] as $bookName)
                        <div class="col-md-6 col-lg-4">
                            <div class="book-badge shadow-sm">
                                <i class="fas fa-book-open text-success me-2"></i> {{ $bookName }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 9. প্রামাণ্যচিত্র -->
            <div class="card about-card p-4 p-md-5 bg-white mb-5">
                <div class="text-center mb-5">
                    <span class="text-gold fw-bold text-uppercase tracking-wider">ভিডিও আর্কাইভ</span>
                    <h2 class="text-dark-green fw-bold">{{ $wp_documentaries['title'] }}</h2>
                    <p class="text-muted mt-2">আমাদের প্রকাশিত অন্যতম গুরুত্বপূর্ণ প্রামাণ্যচিত্রসমূহ</p>
                </div>
                <div class="row g-4">
                    @foreach($wp_documentaries['items'] as $doc)
                        <div class="col-md-6 col-lg-4">
                            <div class="card doc-card h-100">
                                <a href="{{ $doc['url'] }}" target="_blank" class="text-decoration-none">
                                    <div class="doc-thumb" style="background-image: url('https://img.youtube.com/vi/{{ $doc['youtube_id'] }}/hqdefault.jpg');">
                                        <div class="play-btn">
                                            <i class="fas fa-play"></i>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold text-white mb-0 lh-base" style="font-size: 1rem;">{{ $doc['title'] }}</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Dynamic Executive Committee (From dynamic settings helper) -->
            @if(count($teamMembers) > 0)
                <div class="card about-card p-4 p-md-5 bg-white">
                    <div class="text-center mb-5">
                        <span class="text-gold fw-bold text-uppercase tracking-wider">আমাদের অভিভাবকবৃন্দ</span>
                        <h2 class="text-dark-green fw-bold">কেন্দ্রীয় কার্যনির্বাহী কমিটি</h2>
                        <div class="title-underline bg-gold mx-auto mt-3" style="width: 80px; height: 3px;"></div>
                    </div>

                    <div class="row g-4 justify-content-center">
                        @foreach($teamMembers as $member)
                            <div class="col-lg-3 col-md-6 col-sm-10">
                                <div class="card team-card border-0 text-center shadow-sm rounded-4 overflow-hidden bg-white h-100" style="transition: all 0.3s ease; background-color: #1f2937 !important;">
                                    <div class="team-img-wrapper" style="aspect-ratio: 1; overflow: hidden; background: #111827;">
                                        <img src="{{ asset($member->image_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400') }}" alt="{{ $member->name }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <div class="card-body p-3">
                                        <h5 class="fw-bold text-white mb-1">{{ $member->name }}</h5>
                                        <p class="text-muted small mb-2">{{ $member->designation }}</p>
                                        <div class="team-social text-gold small">
                                            <span class="mx-1"><i class="fab fa-facebook-f pointer"></i></span>
                                            <span class="mx-1"><i class="fab fa-twitter pointer"></i></span>
                                            <span class="mx-1"><i class="fab fa-linkedin-in pointer"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection
