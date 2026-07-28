@extends('theme::layouts.app')

@section('title', 'প্রশ্নোত্তর ও সাধারণ জিজ্ঞাসা (FAQ) - হেযবুত তওহীদ')
@section('meta_description',
    'হেযবুত তওহীদ সম্পর্কে সাধারণ প্রশ্ন ও তার দাপ্তরিক উত্তরসমূহ। আমাদের আদর্শ, সাংগঠনিক
    কাঠামো, অর্থায়ন, শান্তিনীতি ও সামাজিক অবস্থান জানুন।')

    @push('styles')
        <style>
            /* Modern FAQ Page Styling */
            .faq-page-wrapper {
                background-color: #f8fafc;
                background-image: radial-gradient(at 0% 0%, rgba(5, 150, 105, 0.05) 0px, transparent 50%),
                    radial-gradient(at 100% 100%, rgba(217, 119, 6, 0.05) 0px, transparent 50%);
                padding: 3rem 0 5rem;
            }

            .faq-stat-card {
                background: #ffffff;
                border: 1.5px solid #cbd5e1;
                border-radius: 16px;
                padding: 1.25rem 1.5rem;
                display: flex;
                align-items: center;
                gap: 1rem;
                box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
                transition: all 0.3s ease;
            }

            .faq-stat-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
                border-color: #059669;
            }

            .faq-stat-card h6 {
                color: #020617 !important;
                font-weight: 800 !important;
            }

            .faq-stat-card p {
                color: #1e293b !important;
                font-weight: 600 !important;
            }

            .faq-stat-icon {
                width: 52px;
                height: 52px;
                border-radius: 12px;
                background: linear-gradient(135deg, rgba(0, 77, 56, 0.12), rgba(16, 185, 129, 0.22));
                color: #004d38;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.4rem;
                flex-shrink: 0;
            }

            .faq-control-panel {
                background: #ffffff;
                border: 1.5px solid #cbd5e1;
                border-radius: 24px;
                padding: 2.25rem 2rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
                margin-bottom: 2.5rem;
            }

            .faq-control-panel h2 {
                color: #020617 !important;
                font-weight: 800 !important;
            }

            .faq-control-panel p.text-muted {
                color: #1e293b !important;
                font-weight: 600 !important;
            }

            .search-box-wrapper {
                position: relative;
                max-width: 720px;
                margin: 0 auto 1.75rem;
            }

            .search-box-input {
                width: 100%;
                height: 56px;
                padding: 0 3.25rem 0 3.25rem;
                background: #ffffff;
                border: 2px solid #94a3b8;
                border-radius: 50px;
                font-size: 1.08rem;
                font-weight: 700;
                color: #020617;
                outline: none;
                transition: all 0.25s ease;
            }

            .search-box-input::placeholder {
                color: #475569;
                font-weight: 600;
            }

            .search-box-input:focus {
                background: #ffffff;
                border-color: #004d38;
                box-shadow: 0 0 0 4px rgba(0, 77, 56, 0.18);
            }

            .search-box-icon {
                position: absolute;
                left: 1.35rem;
                top: 50%;
                transform: translateY(-50%);
                color: #004d38;
                font-size: 1.25rem;
                pointer-events: none;
                transition: color 0.2s ease;
            }

            .search-clear-btn {
                position: absolute;
                right: 1rem;
                top: 50%;
                transform: translateY(-50%);
                background: #cbd5e1;
                color: #020617;
                border: none;
                width: 30px;
                height: 30px;
                border-radius: 50%;
                display: none;
                align-items: center;
                justify-content: center;
                font-size: 0.9rem;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .search-clear-btn:hover {
                background: #0f172a;
                color: #ffffff;
            }

            .filter-pills-wrapper {
                display: flex;
                flex-wrap: wrap;
                gap: 0.65rem;
                justify-content: center;
            }

            .faq-filter-pill {
                border: 1.5px solid #cbd5e1;
                background: #ffffff;
                color: #0f172a;
                padding: 0.6rem 1.35rem;
                border-radius: 50px;
                font-size: 0.98rem;
                font-weight: 700;
                cursor: pointer;
                transition: all 0.25s ease;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                user-select: none;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
            }

            .faq-filter-pill i {
                font-size: 0.95rem;
                color: #004d38;
            }

            .faq-filter-pill .pill-count {
                background: #0f172a;
                color: #ffffff;
                font-size: 0.78rem;
                padding: 0.12rem 0.55rem;
                border-radius: 12px;
                margin-left: 0.2rem;
                font-weight: 800;
                transition: all 0.25s ease;
            }

            .faq-filter-pill:hover {
                background: #ffffff;
                border-color: #004d38;
                color: #004d38;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 77, 56, 0.15);
            }

            .faq-filter-pill.active {
                background: linear-gradient(135deg, #004d38 0%, #022c16 100%);
                border-color: #004d38;
                color: #ffffff;
                font-weight: 800;
                box-shadow: 0 6px 18px rgba(0, 77, 56, 0.35);
            }

            .faq-filter-pill.active i {
                color: #f59e0b;
            }

            .faq-filter-pill.active .pill-count {
                background: #f59e0b;
                color: #020617;
                font-weight: 900;
            }

            .faq-cards-container {
                display: flex;
                flex-direction: column;
                gap: 1.1rem;
            }

            .faq-card {
                background: #ffffff;
                border: 1.5px solid #cbd5e1;
                border-radius: 18px;
                overflow: hidden;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .faq-card:hover {
                border-color: #004d38;
                box-shadow: 0 8px 24px rgba(0, 77, 56, 0.1);
            }

            .faq-card.expanded {
                border-color: #004d38;
                box-shadow: 0 10px 30px rgba(0, 77, 56, 0.15);
            }

            .faq-card-trigger {
                width: 100%;
                background: transparent;
                border: none;
                padding: 1.35rem 1.65rem;
                text-align: left;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1.25rem;
                cursor: pointer;
                outline: none;
                transition: background-color 0.2s ease;
            }

            .faq-card-trigger:hover {
                background-color: rgba(0, 77, 56, 0.03);
            }

            .faq-card.expanded .faq-card-trigger {
                background-color: rgba(0, 77, 56, 0.04);
            }

            .faq-q-left {
                display: flex;
                align-items: center;
                gap: 1.15rem;
                flex: 1;
            }

            .faq-num-badge {
                width: 40px;
                height: 40px;
                border-radius: 12px;
                background: linear-gradient(135deg, #004d38 0%, #059669 100%);
                color: #ffffff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 800;
                font-size: 1.05rem;
                flex-shrink: 0;
                box-shadow: 0 3px 8px rgba(0, 77, 56, 0.2);
                transition: all 0.3s ease;
            }

            .faq-card.expanded .faq-num-badge {
                background: linear-gradient(135deg, #022c16 0%, #004d38 100%);
                color: #ffffff;
                box-shadow: 0 4px 12px rgba(0, 77, 56, 0.35);
            }

            .faq-q-title {
                margin: 0;
                font-size: 1.15rem;
                font-weight: 800;
                color: #020617;
                line-height: 1.5;
                letter-spacing: -0.1px;
                transition: color 0.2s ease;
            }

            .faq-card.expanded .faq-q-title {
                color: #004d38;
                font-weight: 800;
            }

            .faq-q-right {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                flex-shrink: 0;
            }

            .faq-cat-tag {
                font-size: 0.78rem;
                font-weight: 800;
                padding: 0.3rem 0.85rem;
                border-radius: 30px;
                background: #e2e8f0;
                color: #0f172a;
                border: 1px solid #cbd5e1;
                letter-spacing: 0.2px;
            }

            .faq-card.expanded .faq-cat-tag {
                background: rgba(0, 77, 56, 0.12);
                color: #004d38;
                border-color: rgba(0, 77, 56, 0.2);
            }

            .faq-indicator {
                width: 34px;
                height: 34px;
                border-radius: 50%;
                background: #e2e8f0;
                color: #0f172a;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.9rem;
                font-weight: 700;
                transition: all 0.3s ease;
            }

            .faq-card.expanded .faq-indicator {
                transform: rotate(180deg);
                background: #004d38;
                color: #ffffff;
            }

            .faq-card-content {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.35s cubic-bezier(0, 1, 0, 1);
            }

            .faq-card-body {
                padding: 0 1.65rem 1.65rem 4.85rem;
                color: #0f172a;
                font-size: 1.06rem;
                font-weight: 500;
                line-height: 1.85;
                border-top: 1.5px dashed #cbd5e1;
                margin-top: 0.25rem;
                padding-top: 1.35rem;
            }

            .faq-answer-callout {
                background: #f0fdf4;
                border-left: 5px solid #059669;
                border: 1px solid #bbf7d0;
                border-left-width: 5px;
                border-radius: 0 14px 14px 0;
                padding: 1.1rem 1.35rem;
                margin-top: 1.1rem;
                font-size: 1rem;
                color: #064e3b;
                font-weight: 700;
            }

            .faq-empty-state {
                text-align: center;
                padding: 4rem 2rem;
                background: #ffffff;
                border: 2px dashed #cbd5e1;
                border-radius: 20px;
                display: none;
            }

            .faq-empty-icon {
                width: 70px;
                height: 70px;
                border-radius: 50%;
                background: #f1f5f9;
                color: #94a3b8;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 2rem;
                margin: 0 auto 1.25rem;
            }

            .faq-cta-card {
                background: linear-gradient(135deg, #022c16 0%, #004d38 60%, #055f46 100%);
                border-radius: 24px;
                padding: 3rem 2rem;
                color: #ffffff;
                position: relative;
                overflow: hidden;
                box-shadow: 0 15px 35px rgba(0, 77, 56, 0.2);
            }

            .faq-cta-card::before {
                content: '';
                position: absolute;
                top: -60px;
                right: -60px;
                width: 240px;
                height: 240px;
                background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
                border-radius: 50%;
                pointer-events: none;
            }

            @media (max-width: 768px) {
                .faq-card-body {
                    padding-left: 1.25rem;
                }

                .faq-q-left {
                    gap: 0.75rem;
                }

                .faq-num-badge {
                    width: 32px;
                    height: 32px;
                    font-size: 0.9rem;
                }

                .faq-q-title {
                    font-size: 0.98rem;
                }

                .faq-cat-tag {
                    display: none;
                }

                .faq-control-panel {
                    padding: 1.25rem;
                }

                .search-box-input {
                    height: 50px;
                    font-size: 0.95rem;
                }
            }
        </style>
    @endpush

@section('content')

    {{-- Hero Banner --}}
    @include('theme::partials.hero_banner', [
        'title' => 'প্রশ্নোত্তর ও সাধারণ জিজ্ঞাসা',
        'subtitle' =>
            'হেযবুত তওহীদ-এর আদর্শ, লক্ষ্য, নীতি ও কর্মকাণ্ড সম্পর্কে আপনার যাবতীয় প্রশ্নের স্পষ্ট ও প্রামাণ্য উত্তর।',
        'badge_text' => 'দাপ্তরিক প্রশ্নোত্তর',
        'badge_icon' => 'fas fa-circle-question',
    ])

    <section class="faq-page-wrapper">
        <div class="container">

            {{-- Top Feature / Stats Bar --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="faq-stat-card">
                        <div class="faq-stat-icon">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="font-size: 0.98rem;">প্রামাণ্য তথ্য</h6>
                            <p class="small mb-0">কুরআন ও হাদিসের আলোকে যৌক্তিক উত্তর</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="faq-stat-card">
                        <div class="faq-stat-icon">
                            <i class="fas fa-filter"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="font-size: 0.98rem;">বিষয়ভিত্তিক অনুসন্ধান</h6>
                            <p class="small mb-0">ক্যাটাগরি ফিল্টার ও সার্চ সুবিধা</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="faq-stat-card">
                        <div class="faq-stat-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="font-size: 0.98rem;">সরাসরি প্রশ্ন করুন</h6>
                            <p class="small mb-0">উত্তর না পেলে সরাসরি যোগাযোগের মাধ্যম</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Smart Controls Box (Search Bar & Category Pills) --}}
            <div class="faq-control-panel">
                <div class="text-center mb-3">
                    <h2 class="h4 mb-1">যেকোনো প্রশ্ন অনুসন্ধান করুন</h2>
                    <p class="text-muted small mb-0">নিচের সার্চ বক্সে টাইপ করুন অথবা যেকোনো ক্যাটাগরিতে ক্লিক করুন</p>
                </div>

                {{-- Search Box --}}
                <div class="search-box-wrapper">
                    <input type="text" id="faq-search-input" class="search-box-input"
                        placeholder="যেকোনো বিষয় বা প্রশ্ন লিখুন (যেমন: রাজনীতি, নারী, জেহাদ, অর্থায়ন)..."
                        oninput="onFaqSearchInput(this)">
                    <i class="fas fa-search search-box-icon"></i>
                    <button type="button" id="faq-search-clear" class="search-clear-btn" onclick="clearFaqSearch()"
                        title="মুছে ফেলুন">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Filter Pills --}}
                <div class="filter-pills-wrapper">
                    <button type="button" class="faq-filter-pill active" data-category="all"
                        onclick="setFaqCategory('all', this)">
                        <i class="fas fa-layer-group"></i> সব প্রশ্ন <span class="pill-count" id="count-all">0</span>
                    </button>
                    <button type="button" class="faq-filter-pill" data-category="org"
                        onclick="setFaqCategory('org', this)">
                        <i class="fas fa-sitemap"></i> সাংগঠনিক কাঠামো <span class="pill-count" id="count-org">0</span>
                    </button>
                    <button type="button" class="faq-filter-pill" data-category="ideology"
                        onclick="setFaqCategory('ideology', this)">
                        <i class="fas fa-compass"></i> আদর্শ ও মূল লক্ষ্য <span class="pill-count"
                            id="count-ideology">0</span>
                    </button>
                    <button type="button" class="faq-filter-pill" data-category="jihad"
                        onclick="setFaqCategory('jihad', this)">
                        <i class="fas fa-shield-alt"></i> শান্তিনীতি ও জেহাদ <span class="pill-count"
                            id="count-jihad">0</span>
                    </button>
                    <button type="button" class="faq-filter-pill" data-category="women"
                        onclick="setFaqCategory('women', this)">
                        <i class="fas fa-female"></i> নারী ও সমাজ <span class="pill-count" id="count-women">0</span>
                    </button>
                </div>
            </div>

            {{-- FAQ Accordion Cards Container --}}
            <div class="faq-cards-container" id="faq-list-container">

                @php $counter = 1; @endphp

                {{-- Dynamic Posts from Database if Available --}}
                @if (isset($faqPosts) && count($faqPosts) > 0)
                    @foreach ($faqPosts as $faqPost)
                        @php
                            $catFilter = 'ideology';
                            $catLabel = 'আদর্শ ও লক্ষ্য';
                            $titleText = $faqPost->title;

                            if (
                                mb_strpos($titleText, 'নারী') !== false ||
                                mb_strpos($titleText, 'মেয়েদের') !== false ||
                                mb_strpos($titleText, 'পর্দা') !== false
                            ) {
                                $catFilter = 'women';
                                $catLabel = 'নারী ও সমাজ';
                            } elseif (
                                mb_strpos($titleText, 'জেহাদ') !== false ||
                                mb_strpos($titleText, 'জঙ্গিবাদ') !== false ||
                                mb_strpos($titleText, 'চরম পন্থা') !== false
                            ) {
                                $catFilter = 'jihad';
                                $catLabel = 'শান্তিনীতি ও জেহাদ';
                            } elseif (
                                mb_strpos($titleText, 'সংগঠন') !== false ||
                                mb_strpos($titleText, 'অর্থ') !== false ||
                                mb_strpos($titleText, 'দল') !== false ||
                                mb_strpos($titleText, 'রাজনীতি') !== false
                            ) {
                                $catFilter = 'org';
                                $catLabel = 'সাংগঠনিক কাঠামো';
                            }
                        @endphp

                        <div class="faq-card" data-category="{{ $catFilter }}">
                            <button type="button" class="faq-card-trigger" onclick="toggleFaqCard(this)">
                                <div class="faq-q-left">
                                    <span class="faq-num-badge">{{ $counter++ }}</span>
                                    <h3 class="faq-q-title">{{ $faqPost->title }}</h3>
                                </div>
                                <div class="faq-q-right">
                                    <span class="faq-cat-tag">{{ $catLabel }}</span>
                                    <span class="faq-indicator"><i class="fas fa-chevron-down"></i></span>
                                </div>
                            </button>
                            <div class="faq-card-content">
                                <div class="faq-card-body">
                                    {!! Str::limit(strip_tags($faqPost->content ?? $faqPost->short_description), 500) !!}
                                    <div class="faq-answer-callout">
                                        <a href="{{ route('blog.detail', $faqPost->slug) }}"
                                            class="text-decoration-none fw-bold text-success">
                                            সম্পূর্ণ বিস্তারিত পোস্টটি পড়ুন <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                {{-- Static Curated Core FAQs --}}

                <!-- FAQ Item 1 -->
                <div class="faq-card" data-category="org">
                    <button type="button" class="faq-card-trigger" onclick="toggleFaqCard(this)">
                        <div class="faq-q-left">
                            <span class="faq-num-badge">{{ $counter++ }}</span>
                            <h3 class="faq-q-title">হেযবুত তওহীদ কী একটি ইসলামিক রাজনৈতিক দল?</h3>
                        </div>
                        <div class="faq-q-right">
                            <span class="faq-cat-tag">সাংগঠনিক কাঠামো</span>
                            <span class="faq-indicator"><i class="fas fa-chevron-down"></i></span>
                        </div>
                    </button>
                    <div class="faq-card-content">
                        <div class="faq-card-body">
                            না, হেযবুত তওহীদ সম্পূর্ণ একটি অরাজনীতিক, অসাম্প্রদায়িক ও সংস্কারমূলক সামাজিক আন্দোলন। এটি কোনো
                            নির্বাচনে অংশ নেয় না এবং ক্ষমতার রাজনীতিতে বিশ্বাসী নয়। এর একমাত্র উদ্দেশ্য সমাজে নৈতিক ও
                            সামাজিক সংস্কার ঘটানো এবং ধর্মের অপব্যাখ্যা দূর করে প্রকৃত শান্তি প্রতিষ্ঠা করা।
                            <div class="faq-answer-callout">
                                <i class="fas fa-info-circle text-success me-1"></i> ডানপন্থী, বামপন্থী কিংবা প্রচলিত কোনো
                                রাজনৈতিক ক্ষমতার মেরুকরণের সাথে হেযবুত তওহীদের কোনো সম্পর্ক নেই।
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="faq-card" data-category="org">
                    <button type="button" class="faq-card-trigger" onclick="toggleFaqCard(this)">
                        <div class="faq-q-left">
                            <span class="faq-num-badge">{{ $counter++ }}</span>
                            <h3 class="faq-q-title">হেযবুত তওহীদের আন্দোলন কার বিরুদ্ধে?</h3>
                        </div>
                        <div class="faq-q-right">
                            <span class="faq-cat-tag">সাংগঠনিক কাঠামো</span>
                            <span class="faq-indicator"><i class="fas fa-chevron-down"></i></span>
                        </div>
                    </button>
                    <div class="faq-card-content">
                        <div class="faq-card-body">
                            আমাদের আন্দোলন কোনো ব্যক্তি, নির্দিষ্ট জাতি বা ধর্মীয় গোষ্ঠীর বিরুদ্ধে নয়। আমাদের লড়াই অন্যায়ের
                            বিরুদ্ধে, অনৈক্যের বিরুদ্ধে, ধর্মব্যবসার বিরুদ্ধে, উগ্রবাদ-জঙ্গিবাদের বিরুদ্ধে এবং সমাজের
                            সর্বগ্রাসী নৈতিক অবক্ষয়ের বিরুদ্ধে।
                            <div class="faq-answer-callout">
                                <i class="fas fa-quote-left text-amber me-1"></i> হেযবুত তওহীদ বিশ্বাস করে, সকল হিংসা ও
                                রক্তপাত পরিহার করে সত্য ও ঐক্যের মাধ্যমে মানবজাতির মধ্যে স্থায়ী শান্তি ফেরানো সম্ভব।
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="faq-card" data-category="org">
                    <button type="button" class="faq-card-trigger" onclick="toggleFaqCard(this)">
                        <div class="faq-q-left">
                            <span class="faq-num-badge">{{ $counter++ }}</span>
                            <h3 class="faq-q-title">আন্দোলনের আয়ের উৎস ও অর্থায়ন কীভাবে পরিচালিত হয়?</h3>
                        </div>
                        <div class="faq-q-right">
                            <span class="faq-cat-tag">সাংগঠনিক কাঠামো</span>
                            <span class="faq-indicator"><i class="fas fa-chevron-down"></i></span>
                        </div>
                    </button>
                    <div class="faq-card-content">
                        <div class="faq-card-body">
                            আন্দোলনের সদস্যরা সম্পূর্ণ নিজস্ব অর্থায়নে এবং স্বেচ্ছাপ্রদত্ত দানের (এয়ানত) মাধ্যমে এর ব্যয়ভার
                            বহন করেন। কোনো দেশি-বিদেশি কালো তহবিল বা অনৈতিক রাজনৈতিক অনুদান গ্রহণ করা হয় না। এছাড়া আন্দোলনের
                            নিজস্ব নিরপেক্ষ প্রকাশনা, বই ও পত্রিকা বিক্রয়ের আয়ের মাধ্যমে এর কর্মকাণ্ড পরিচালিত হয়।
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="faq-card" data-category="org">
                    <button type="button" class="faq-card-trigger" onclick="toggleFaqCard(this)">
                        <div class="faq-q-left">
                            <span class="faq-num-badge">{{ $counter++ }}</span>
                            <h3 class="faq-q-title">হেযবুত তওহীদের প্রতিষ্ঠাতার পরিচয় কী?</h3>
                        </div>
                        <div class="faq-q-right">
                            <span class="faq-cat-tag">সাংগঠনিক কাঠামো</span>
                            <span class="faq-indicator"><i class="fas fa-chevron-down"></i></span>
                        </div>
                    </button>
                    <div class="faq-card-content">
                        <div class="faq-card-body">
                            হেযবুত তওহীদের প্রতিষ্ঠাতা এম. এ. বায়জীদ খান পন্নী (১৬ই জানুয়ারি ১৯২৫ - ১৬ই জানুয়ারি ২০১২)। তিনি
                            টাঙ্গাইলের ঐতিহ্যবাহী পন্নী পরিবারের সন্তান ছিলেন। বস্তুনিষ্ঠ গবেষণা ও সত্য অনুসন্ধানের মাধ্যমে
                            তিনি সমাজের সর্বস্তরে ধর্মব্যবসা ও বিকৃতির অবসান ঘটাতে ১৯৯৫ সালে এই আন্দোলনের সূচনা করেন।
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 5 -->
                <div class="faq-card" data-category="ideology">
                    <button type="button" class="faq-card-trigger" onclick="toggleFaqCard(this)">
                        <div class="faq-q-left">
                            <span class="faq-num-badge">{{ $counter++ }}</span>
                            <h3 class="faq-q-title">হেযবুত তওহীদের মূল লক্ষ্য ও উদ্দেশ্য কী?</h3>
                        </div>
                        <div class="faq-q-right">
                            <span class="faq-cat-tag">আদর্শ ও লক্ষ্য</span>
                            <span class="faq-indicator"><i class="fas fa-chevron-down"></i></span>
                        </div>
                    </button>
                    <div class="faq-card-content">
                        <div class="faq-card-body">
                            সমগ্র মানবজাতিকে এক স্রষ্টার হুকুমের অধীনে ঐক্যবদ্ধ করে অশান্তিময় পৃথিবীকে শান্তিময় সমাজে
                            রূপান্তরিত করা। ধর্মকে ব্যক্তিগত স্বার্থে ব্যবহার না করে, বৈষম্যহীন ও ন্যায়ভিত্তিক সমাজ গঠন করা
                            আমাদের প্রধান লক্ষ্য।
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 6 -->
                <div class="faq-card" data-category="ideology">
                    <button type="button" class="faq-card-trigger" onclick="toggleFaqCard(this)">
                        <div class="faq-q-left">
                            <span class="faq-num-badge">{{ $counter++ }}</span>
                            <h3 class="faq-q-title">হেযবুত তওহীদ কি প্রচলিত কোনো পীর-মুরিদী বা সুফিবাদ সমর্থন করে?</h3>
                        </div>
                        <div class="faq-q-right">
                            <span class="faq-cat-tag">আদর্শ ও লক্ষ্য</span>
                            <span class="faq-indicator"><i class="fas fa-chevron-down"></i></span>
                        </div>
                    </button>
                    <div class="faq-card-content">
                        <div class="faq-card-body">
                            না, হেযবুত তওহীদ কোনো নির্দিষ্ট তরিকাপন্থা বা পীর-মুরিদী ব্যবস্থার অংশ নয়। আমরা মুসলিম উম্মাহর
                            বিভিন্ন ফেরকা বা তরিকতভিত্তিক অনৈক্যের বিপরীতে রসুলের দেখানো প্রকৃত ইসলামের সোনালী যুগের বৈশ্বিক
                            ঐক্য পুনরুদ্ধারে কাজ করি।
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 7 -->
                <div class="faq-card" data-category="jihad">
                    <button type="button" class="faq-card-trigger" onclick="toggleFaqCard(this)">
                        <div class="faq-q-left">
                            <span class="faq-num-badge">{{ $counter++ }}</span>
                            <h3 class="faq-q-title">জেহাদ ও উগ্রবাদের বিষয়ে হেযবুত তওহীদের অবস্থান কী?</h3>
                        </div>
                        <div class="faq-q-right">
                            <span class="faq-cat-tag">শান্তিনীতি ও জেহাদ</span>
                            <span class="faq-indicator"><i class="fas fa-chevron-down"></i></span>
                        </div>
                    </button>
                    <div class="faq-card-content">
                        <div class="faq-card-body">
                            হেযবুত তওহীদ যে কোনো ধরণের সহিংসতা, বোমাবাজি বা সশস্ত্র চরমপন্থাকে কঠোরভাবে প্রত্যাখ্যান করে।
                            আসল জেহাদ হলো সত্য ও ন্যায় প্রতিষ্ঠার অহিংস আদর্শিক প্রকাশ। আইন নিজের হাতে তুলে নেওয়া ইসলামের
                            বিধান নয়; সশস্ত্র যুদ্ধ শুধুমাত্র একটি সার্বভৌম রাষ্ট্রই অনুমোদন করতে পারে।
                            <div class="faq-answer-callout">
                                <i class="fas fa-shield-alt text-success me-1"></i> উগ্রবাদ ও জঙ্গিবাদের বিরুদ্ধে হেযবুত
                                তওহীদ সারা বাংলাদেশে হাজার হাজার সেমিনার ও সচেতনতামূলক সভা পরিচালনা করে আসছে।
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 8 -->
                <div class="faq-card" data-category="women">
                    <button type="button" class="faq-card-trigger" onclick="toggleFaqCard(this)">
                        <div class="faq-q-left">
                            <span class="faq-num-badge">{{ $counter++ }}</span>
                            <h3 class="faq-q-title">ইসলামে পর্দা ও নারী অধিকারের ব্যাপারে আপনাদের নীতি কী?</h3>
                        </div>
                        <div class="faq-q-right">
                            <span class="faq-cat-tag">নারী ও সমাজ</span>
                            <span class="faq-indicator"><i class="fas fa-chevron-down"></i></span>
                        </div>
                    </button>
                    <div class="faq-card-content">
                        <div class="faq-card-body">
                            ইসলামে শালীনতা বজায় রেখে নারীরা সমাজ ও দেশ গঠনের সকল ক্ষেত্র—শিক্ষা, গবেষণা, চিকিৎসা ও দাপ্তরিক
                            কাজে স্বতঃস্ফূর্তভাবে অংশগ্রহণ করতে পারেন। রসুলাল্লাহর যুগেও নারী আসহাবগণ জ্ঞানচর্চা, জনসেবা ও
                            গঠনমূলক কাজে সক্রিয় ভূমিকা রেখেছিলেন।
                        </div>
                    </div>
                </div>

            </div>

            {{-- Empty Search Results Notice --}}
            <div class="faq-empty-state" id="faq-empty-notice">
                <div class="faq-empty-icon">
                    <i class="fas fa-search-minus"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">কোনো প্রশ্ন খুঁজে পাওয়া যায়নি!</h4>
                <p class="text-muted max-w-md mx-auto mb-3" style="max-width: 480px;">
                    আপনার অনুসন্ধান পদটির সাথে মিল রেখে কোনো প্রশ্ন পাওয়া যায়নি। অনুগ্রহ করে বানান পরিবর্তন করে অথবা অন্য
                    কি-ওয়ার্ড ব্যবহার করে দেখুন।
                </p>
                <button type="button" class="btn btn-outline-success rounded-pill px-4" onclick="clearFaqSearch()">
                    <i class="fas fa-rotate-left me-1"></i> ফিল্টার রিসেট করুন
                </button>
            </div>

            {{-- Bottom Call To Action (Ask Question Box) --}}
            <div class="faq-cta-card mt-5 text-center text-md-start">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3">
                            <i class="fas fa-question me-1"></i> আরো কিছু জানার আছে?
                        </span>
                        <h3 class="fw-bold text-white mb-2">আপনার কাঙ্ক্ষিত প্রশ্নের উত্তর এখানে পাননি?</h3>
                        <p class="text-white-50 mb-0 leading-relaxed" style="font-size: 1.05rem;">
                            আমাদের পরিচিতি, গবেষণা সাহিত্য কিংবা যে কোনো স্পষ্টীকরণের জন্য সরাসরি কেন্দ্রীয় অফিসে যোগাযোগ
                            করুন অথবা অনলাইনে মতামত জানান।
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <div class="d-flex flex-column flex-sm-row flex-lg-column gap-2 justify-content-center">
                            <a href="{{ route('contact') }}"
                                class="btn btn-warning text-dark fw-bold rounded-pill py-2.5 px-4 shadow-sm">
                                <i class="fas fa-paper-plane me-2"></i> সরাসরি যোগাযোগ করুন
                            </a>
                            <a href="{{ route('feedback.index') }}"
                                class="btn btn-outline-light rounded-pill py-2.5 px-4">
                                <i class="fas fa-pen-to-square me-2"></i> মতামত পাঠ্য লিখুন
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection

@push('scripts')
    <script>
        let activeFaqCategory = 'all';

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize category counters
            updateCategoryCounts();

            // Auto open the very first FAQ card for better initial UI visual
            const firstCard = document.querySelector('.faq-card');
            if (firstCard) {
                toggleFaqCard(firstCard.querySelector('.faq-card-trigger'));
            }
        });

        // Toggle Single Accordion Item
        function toggleFaqCard(triggerBtn) {
            const card = triggerBtn.closest('.faq-card');
            const content = card.querySelector('.faq-card-content');
            const isExpanded = card.classList.contains('expanded');

            // Close all other expanded items
            document.querySelectorAll('.faq-card.expanded').forEach(otherCard => {
                if (otherCard !== card) {
                    otherCard.classList.remove('expanded');
                    otherCard.querySelector('.faq-card-content').style.maxHeight = null;
                }
            });

            if (isExpanded) {
                card.classList.remove('expanded');
                content.style.maxHeight = null;
            } else {
                card.classList.add('expanded');
                content.style.maxHeight = content.scrollHeight + "px";
            }
        }

        // Set Active Filter Category
        function setFaqCategory(category, buttonEl) {
            activeFaqCategory = category;

            // Update Pill CSS Active State
            document.querySelectorAll('.faq-filter-pill').forEach(pill => {
                pill.classList.remove('active');
            });
            buttonEl.classList.add('active');

            applyFaqFilters();
        }

        // Input Search Handler
        function onFaqSearchInput(inputEl) {
            const clearBtn = document.getElementById('faq-search-clear');
            if (inputEl.value.trim().length > 0) {
                clearBtn.style.display = 'flex';
            } else {
                clearBtn.style.display = 'none';
            }
            applyFaqFilters();
        }

        // Clear Search Input
        function clearFaqSearch() {
            const searchInput = document.getElementById('faq-search-input');
            searchInput.value = '';
            document.getElementById('faq-search-clear').style.display = 'none';

            // Reset category to 'all'
            const allPill = document.querySelector('.faq-filter-pill[data-category="all"]');
            if (allPill) {
                setFaqCategory('all', allPill);
            } else {
                applyFaqFilters();
            }
        }

        // Core Filtering Logic
        function applyFaqFilters() {
            const query = document.getElementById('faq-search-input').value.toLowerCase().trim();
            const cards = document.querySelectorAll('.faq-card');
            const emptyNotice = document.getElementById('faq-empty-notice');
            let visibleCount = 0;

            cards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                const cardText = card.textContent.toLowerCase();

                const matchesCategory = (activeFaqCategory === 'all' || cardCategory === activeFaqCategory);
                const matchesQuery = (query === '' || cardText.includes(query));

                if (matchesCategory && matchesQuery) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                    // Close if hidden
                    card.classList.remove('expanded');
                    const content = card.querySelector('.faq-card-content');
                    if (content) content.style.maxHeight = null;
                }
            });

            // Toggle Empty State Box
            if (visibleCount === 0) {
                emptyNotice.style.display = 'block';
            } else {
                emptyNotice.style.display = 'none';
            }
        }

        // Calculate & Display Count Badges on Pills
        function updateCategoryCounts() {
            const cards = document.querySelectorAll('.faq-card');
            const counts = {
                all: cards.length,
                org: 0,
                ideology: 0,
                jihad: 0,
                women: 0
            };

            cards.forEach(card => {
                const cat = card.getAttribute('data-category');
                if (counts.hasOwnProperty(cat)) {
                    counts[cat]++;
                }
            });

            for (const [key, val] of Object.entries(counts)) {
                const countEl = document.getElementById(`count-${key}`);
                if (countEl) {
                    countEl.textContent = val;
                }
            }
        }
    </script>
@endpush
