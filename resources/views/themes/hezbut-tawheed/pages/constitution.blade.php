@extends('theme::layouts.app')

@section('title', 'আমাদের গঠনতন্ত্র - হেযবুত তওহীদ')
@section('meta_description', 'হেযবুত তওহীদ আন্দোলনের লক্ষ্য, উদ্দেশ্য, মূলনীতি, সদস্যপদ, সাংগঠনিক কাঠামো ও পরিচালিত আইন সংবলিত অফিশিয়াল গঠনতন্ত্র।')

@push('styles')
    <style>
        .book-3d-card:hover {
            transform: perspective(1000px) rotateY(0deg) rotateX(0deg) translateY(-8px) !important;
            box-shadow: -15px 22px 35px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(0, 0, 0, 0.12) !important;
        }

        .chapter-card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #ffffff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .chapter-card:hover {
            border-color: #006A4E;
            box-shadow: 0 12px 30px -10px rgba(0, 106, 78, 0.15) !important;
        }

        .chapter-card-header {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
        }

        .chapter-body {
            color: #334155;
            font-size: 0.98rem;
            line-height: 1.85;
            font-family: 'Hind Siliguri', sans-serif;
        }

        .chapter-body h5 {
            font-family: 'Baloo Da 2', sans-serif;
            color: #064e3b;
            background: #f0fdf4;
            border-left: 4px solid #006A4E;
            padding: 10px 16px;
            border-radius: 0 8px 8px 0;
            font-size: 1.15rem;
            font-weight: 700;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .chapter-body p {
            margin-bottom: 1rem;
            text-align: justify;
        }

        .chapter-body ul {
            list-style: none;
            padding-left: 0;
            margin-bottom: 1.25rem;
        }

        .chapter-body ul li {
            position: relative;
            padding: 8px 12px 8px 36px;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 8px;
            border: 1px solid #f1f5f9;
        }

        .chapter-body ul li::before {
            content: "\f058";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            left: 12px;
            top: 10px;
            color: #006A4E;
            font-size: 14px;
        }

        .toc-nav-link {
            display: flex;
            align-items: center;
            padding: 10px 14px;
            border-radius: 10px;
            color: #475569;
            font-weight: 600;
            font-size: 13.5px;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .toc-nav-link:hover, .toc-nav-link.active {
            background-color: #f0fdf4;
            color: #006A4E;
            border-color: #bbf7d0;
        }

        .toc-nav-link .ch-num {
            background: #e2e8f0;
            color: #334155;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 6px;
            margin-right: 10px;
            font-weight: 700;
        }

        .toc-nav-link:hover .ch-num, .toc-nav-link.active .ch-num {
            background: #006A4E;
            color: #ffffff;
        }

        /* Mobile Smart Optimization Media Queries */
        @media (max-width: 767.98px) {
            .chapter-card-header {
                padding: 1rem;
            }
            .chapter-card-header h4 {
                font-size: 1.15rem !important;
            }
            .chapter-body {
                font-size: 0.92rem;
                line-height: 1.75;
            }
            .chapter-body h5 {
                font-size: 1rem;
                padding: 8px 12px;
            }
            .book-3d-card {
                max-width: 140px !important;
            }
        }
    </style>
@endpush

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'আমাদের গঠনতন্ত্র',
        'subtitle' => 'হেযবুত তওহীদ আন্দোলনের লক্ষ্য, উদ্দেশ্য, মূলনীতি ও সাংগঠনিক কাঠামো সংবলিত অফিশিয়াল ঘোষণাপত্র ও গঠনতন্ত্র',
        'badge_text' => 'অফিশিয়াল দলিল ও গঠনতন্ত্র',
        'badge_icon' => 'fas fa-balance-scale',
    ])

    <!-- Constitution Main Section -->
    <div class="py-4 py-md-5" style="background-color: #f8fafc; min-height: 70vh; font-family: 'Hind Siliguri', sans-serif;">
        <div class="container">

            <!-- 1. Featured Constitution Book Hero Banner -->
            <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mb-4 mb-md-5 bg-white overflow-hidden position-relative"
                style="border-top: 4px solid #006A4E !important;">
                <div class="row align-items-center g-4">
                    <div class="col-lg-3 col-md-4 text-center">
                        <div class="book-3d-wrapper py-2">
                            <div class="book-3d-card position-relative d-inline-block rounded-3 overflow-hidden">
                                <div class="position-absolute top-0 bottom-0 start-0"
                                    style="width: 14px; background: linear-gradient(90deg, rgba(0,0,0,0.45) 0%, rgba(255,255,255,0.25) 45%, rgba(0,0,0,0.2) 100%); z-index: 3; pointer-events: none;">
                                </div>
                                <div class="position-absolute top-0 bottom-0 end-0"
                                    style="width: 5px; background: linear-gradient(90deg, #f8fafc 0%, #e2e8f0 100%); border-left: 1px solid #cbd5e1; z-index: 2; pointer-events: none;">
                                </div>
                                <img src="{{ asset('uploads/books/constitution.jpg') }}"
                                    onerror="this.onerror=null; this.src='https://placehold.co/400x600/006A4E/ffffff?text=%E0%A6%97%E0%A6%A0%E0%A6%A8%E0%A6%A4%E0%A6%A8%E0%A7%8D%E0%A6%A4%E0%A7%8D%E0%A6%B0';"
                                    alt="হেযবুত তওহীদের অফিশিয়াল গঠনতন্ত্র বই" class="w-100 img-fluid d-block"
                                    style="aspect-ratio: 2/3; object-fit: cover;">
                                <div class="position-absolute top-0 start-0 end-0 bottom-0"
                                    style="background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0) 60%); z-index: 4; pointer-events: none;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8 text-center text-md-start">
                        <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-2">
                            <span class="badge px-3 py-1.5 rounded-pill text-white fw-bold"
                                style="background-color: #006A4E; font-size: 11px;">
                                <i class="fas fa-book me-1"></i> অফিশিয়াল ই-বুক
                            </span>
                            <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill fw-semibold"
                                style="font-size: 11px;">
                                <i class="fas fa-certificate text-warning me-1"></i> অনুমোদিত সংস্করণ
                            </span>
                        </div>
                        <h3 class="fw-bold text-dark mb-2"
                            style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.5rem;">
                            {{ $book->title ?? 'হেযবুত তওহীদের অফিশিয়াল গঠনতন্ত্র' }}
                        </h3>
                        <p class="text-secondary small mb-3 lh-lg" style="text-align: justify;">
                            {{ $book->description ?? 'হেযবুত তওহীদ আন্দোলনের মূল লক্ষ্য, উদ্দেশ্য, প্রকাশ্য ও অরাজনৈতিক কার্যপদ্ধতি, সাংগঠনিক শৃঙ্খলা ও অর্থায়ন নীতি সম্পর্কিত সংবিধিবদ্ধ বিধানাবলী।' }}
                        </p>
                        <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-2 pt-1">
                            @if ($book)
                                <a href="{{ route('books.show', $book->slug) }}"
                                    class="btn text-white px-4 py-2 rounded-3 fw-bold shadow-sm d-inline-flex align-items-center gap-2"
                                    style="background-color: #006A4E; font-size: 13.5px;">
                                    <i class="fas fa-book-reader"></i> <span>গঠনতন্ত্র বই দেখুন</span>
                                </a>
                            @endif
                            <button onclick="window.print()"
                                class="btn btn-outline-secondary px-3 py-2 rounded-3 fw-semibold d-inline-flex align-items-center gap-2"
                                style="font-size: 13.5px;">
                                <i class="fas fa-print"></i> <span>প্রিন্ট করুন</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Modern 2-Column Split Reader Section -->
            <div class="row g-4">
                
                <!-- Desktop Left Sticky Table of Contents Sidebar -->
                <div class="col-lg-4 col-md-5 d-none d-lg-block">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white sticky-top" style="top: 100px; z-index: 10;">
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                            <h6 class="fw-bold mb-0 text-dark" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.1rem;">
                                <i class="fas fa-list-ul text-success me-2"></i> অধ্যায় সূচিপত্র
                            </h6>
                            <span class="badge bg-soft-success text-success rounded-pill fw-bold" style="font-size: 11px;">
                                {{ $chapters->count() }} টি অধ্যায়
                            </span>
                        </div>

                        <!-- Quick Search Filter in Sidebar -->
                        <div class="mb-3">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-search"></i></span>
                                <input type="text" id="toc-search-desktop" class="form-control bg-light border-start-0" placeholder="অধ্যায় বা ধারা খুঁজুন..." onkeyup="filterToc('desktop')">
                            </div>
                        </div>

                        <!-- Sidebar Nav Items -->
                        <div class="nav flex-column gap-1 overflow-auto" id="toc-list-desktop" style="max-height: 60vh;">
                            @foreach ($chapters as $index => $ch)
                                <a href="#{{ $ch->slug }}" class="toc-nav-link" id="nav-desktop-{{ $ch->slug }}">
                                    <span class="ch-num">{{ $ch->chapter_number ?: ($index + 1) . 'ম' }}</span>
                                    <span class="text-truncate">{{ $ch->title }}</span>
                                </a>
                            @endforeach
                        </div>

                        <div class="border-top pt-3 mt-3 text-center">
                            <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="btn btn-sm btn-light border w-100 rounded-3 text-muted fw-semibold" style="font-size: 12px;">
                                <i class="fas fa-arrow-up me-1"></i> একদম উপরে যান
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Chapters Reader Area -->
                <div class="col-lg-8 col-md-12">
                    @forelse($chapters as $index => $ch)
                        <div class="chapter-card mb-4 shadow-sm" id="{{ $ch->slug }}" style="scroll-margin-top: 100px;">
                            
                            <!-- Card Top Decorative Gradient Bar -->
                            <div style="height: 4px; background: linear-gradient(90deg, #006A4E 0%, #d97706 100%);"></div>

                            <!-- Chapter Header -->
                            <div class="chapter-card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge px-3 py-1.5 rounded-pill text-white fw-bold"
                                        style="background-color: #006A4E; font-size: 12px; font-family: 'Baloo Da 2', sans-serif;">
                                        {{ $ch->chapter_number ?: ($index + 1) . 'ম অধ্যায়' }}
                                    </span>
                                    <h4 class="fw-bold mb-0 text-dark" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.25rem;">
                                        {{ $ch->title }}
                                    </h4>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($ch->pdf_url)
                                        <a href="{{ $ch->pdf_url }}" target="_blank" class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3" style="font-size: 12px;">
                                            <i class="fas fa-file-pdf me-1"></i> PDF
                                        </a>
                                    @endif
                                    <button onclick="copyLink('{{ url('/constitution#' . $ch->slug) }}')" class="btn btn-sm btn-light border text-secondary rounded-circle" title="লিঙ্ক কপি করুন" style="width: 32px; height: 32px; padding: 0;">
                                        <i class="fas fa-link" style="font-size: 11px;"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Chapter Body -->
                            <div class="card-body p-3 p-md-4">
                                <div class="chapter-body">
                                    {!! $ch->content !!}
                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                            <i class="fas fa-book-open fa-3x text-muted mb-3 opacity-50"></i>
                            <h4 class="fw-bold text-dark mb-2" style="font-family: 'Baloo Da 2', sans-serif;">গঠনতন্ত্রের বিবরণ প্রস্তুত হচ্ছে!</h4>
                            <p class="text-secondary small">খুব শীঘ্রই গঠনতন্ত্রের সম্পূর্ণ বিষয়বস্তু আপডেট করা হবে।</p>
                        </div>
                    @endforelse
                </div>

            </div>

        </div>
    </div>

    <!-- 3. Smart Mobile Floating FAB Button & Offcanvas Drawer -->
    <div class="d-lg-none">
        <!-- Floating FAB Trigger Button -->
        <button type="button" class="btn text-white shadow-lg rounded-pill px-4 py-2.5 d-flex align-items-center gap-2"
            data-bs-toggle="offcanvas" data-bs-target="#mobileTocOffcanvas"
            style="position: fixed; bottom: 24px; right: 18px; z-index: 1050; background: linear-gradient(135deg, #006A4E 0%, #064e3b 100%); font-family: 'Baloo Da 2', sans-serif; border: 2px solid #ffffff; box-shadow: 0 10px 25px rgba(0,106,78,0.4) !important;">
            <i class="fas fa-list-ol"></i> <span class="fw-bold">অধ্যায় সূচিপত্র</span>
        </button>

        <!-- Offcanvas Drawer for Mobile -->
        <div class="offcanvas offcanvas-bottom rounded-top-4" tabindex="-1" id="mobileTocOffcanvas" style="height: 75vh;">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title fw-bold text-dark" style="font-family: 'Baloo Da 2', sans-serif;">
                    <i class="fas fa-list-ul text-success me-2"></i> গঠনতন্ত্রের অধ্যায় সূচিপত্র
                </h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" id="toc-search-mobile" class="form-control bg-light border-start-0" placeholder="অধ্যায় খুঁজুন..." onkeyup="filterToc('mobile')">
                    </div>
                </div>
                <div class="nav flex-column gap-2" id="toc-list-mobile">
                    @foreach ($chapters as $index => $ch)
                        <a href="#{{ $ch->slug }}" class="toc-nav-link" data-bs-dismiss="offcanvas" onclick="scrollToChapter('{{ $ch->slug }}')">
                            <span class="ch-num">{{ $ch->chapter_number ?: ($index + 1) . 'ম' }}</span>
                            <span class="text-truncate">{{ $ch->title }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Mobile & Desktop Nav -->
    <script>
        function filterToc(type) {
            var input = document.getElementById('toc-search-' + type);
            var filter = input.value.toLowerCase();
            var links = document.querySelectorAll('#toc-list-' + type + ' .toc-nav-link');

            links.forEach(function(link) {
                var text = link.innerText.toLowerCase();
                if (text.indexOf(filter) > -1) {
                    link.style.display = "flex";
                } else {
                    link.style.display = "none";
                }
            });
        }

        function scrollToChapter(slug) {
            setTimeout(function() {
                var el = document.getElementById(slug);
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth' });
                }
            }, 300);
        }

        function copyLink(url) {
            navigator.clipboard.writeText(url).then(function() {
                alert('অধ্যায়ের ডাইরেক্ট লিঙ্ক কপি করা হয়েছে!');
            });
        }
    </script>
@endsection
