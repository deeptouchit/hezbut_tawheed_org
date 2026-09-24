@extends('theme::layouts.app')

@section('title', 'গবেষণা ও উন্নয়ন - হেযবুত তওহীদ')

@push('styles')
<style>
    /* ============================================================
       RESEARCH & DEVELOPMENT PAGE DESIGN
       ============================================================ */
    .research-section {
        background: #f8fafc;
        padding: 5rem 0 6rem;
    }

    .research-section,
    .research-section * {
        font-family: 'Baloo Da 2', 'SolaimanLipi', sans-serif !important;
    }

    .research-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 4rem;
        align-items: center;
        margin-bottom: 5rem;
    }

    .research-content-left {
        max-width: 700px;
    }

    .research-tagline {
        font-size: 0.95rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #006A4E;
        margin-bottom: 1rem;
        display: inline-block;
    }

    .research-main-title {
        font-size: 2.75rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.25;
        margin-bottom: 1.5rem;
        letter-spacing: -0.02em;
    }

    .research-main-title .highlight {
        background: linear-gradient(135deg, #006A4E, #15803d);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .research-lead-text {
        font-size: 1.2rem;
        color: #475569;
        line-height: 1.8;
        font-weight: 500;
        margin-bottom: 2rem;
        text-align: justify;
    }

    .research-body-text {
        font-size: 1.05rem;
        color: #64748b;
        line-height: 1.75;
        font-weight: 500;
        margin-bottom: 0;
        text-align: justify;
    }

    /* Right visual card */
    .research-visual-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 32px;
        padding: 3rem;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04);
        position: relative;
        overflow: hidden;
    }

    .research-visual-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(90deg, #006A4E, #15803d);
    }

    /* Section Titles */
    .pillars-section-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: #0f172a;
        text-align: center;
        margin-bottom: 3.5rem;
        position: relative;
    }

    .pillars-section-title::after {
        content: '';
        position: absolute;
        bottom: -12px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: #006A4E;
        border-radius: 2px;
    }

    /* Pillars Grid */
    .pillars-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2.5rem;
        margin-bottom: 6rem;
    }

    .pillar-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        padding: 2.5rem;
        transition: all 0.3s ease;
        text-align: center;
    }

    .pillar-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        border-color: #006A4E;
    }

    .pillar-icon-box {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        background: #f0fdf4;
        color: #006A4E;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1.5rem;
        transition: all 0.3s ease;
    }

    .pillar-card:hover .pillar-icon-box {
        background: #006A4E;
        color: #ffffff;
        transform: scale(1.05);
    }

    .pillar-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 1rem;
    }

    .pillar-card p {
        color: #475569;
        font-size: 0.98rem;
        line-height: 1.6;
        margin-bottom: 0;
        text-align: justify;
    }

    /* ============================================================
       DEVELOPMENT PROJECTS SECTION (উন্নয়ন প্রকল্পসমূহ)
       ============================================================ */
    .projects-container {
        margin-bottom: 6rem;
    }

    .project-card-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
        margin-bottom: 5rem;
    }

    .project-card-row:nth-child(even) {
        grid-template-columns: 1fr 1fr;
    }

    .project-img-box {
        border-radius: 28px;
        overflow: hidden;
        aspect-ratio: 16/10;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }

    .project-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .project-card-row:hover .project-img-box img {
        transform: scale(1.03);
    }

    .project-details-box {
        padding: 1rem 0;
    }

    .project-badge {
        background: #f0fdf4;
        color: #006A4E;
        padding: 0.4rem 1.2rem;
        border-radius: 50px;
        font-size: 0.88rem;
        font-weight: 700;
        display: inline-block;
        margin-bottom: 1.25rem;
    }

    .project-title {
        font-size: 1.85rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 1rem;
    }

    .project-desc {
        font-size: 1.05rem;
        color: #475569;
        line-height: 1.7;
        margin-bottom: 1.75rem;
        text-align: justify;
    }

    .project-bullets-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .project-bullet-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 0.85rem;
        font-size: 1rem;
        color: #334155;
        font-weight: 600;
    }

    .project-bullet-item i {
        color: #006A4E;
        margin-top: 0.25rem;
        font-size: 1.1rem;
    }

    /* Books Section */
    .books-shelf-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
        margin-bottom: 6rem;
    }

    .book-item-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 1.25rem;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }

    .book-item-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 20px -5px rgba(0, 0, 0, 0.05);
        border-color: #006A4E;
    }

    .book-cover-img {
        width: 100%;
        aspect-ratio: 3/4;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.25rem;
        transition: transform 0.3s ease;
    }

    .book-item-card:hover .book-cover-img {
        transform: scale(1.02);
    }

    .book-title-text {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.8rem;
    }

    .book-writer-text {
        font-size: 0.88rem;
        color: #64748b;
        margin-bottom: 1.25rem;
    }

    .book-btn-link {
        font-size: 0.9rem;
        font-weight: 700;
        color: #006A4E;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    /* Articles / Blog posts Grid */
    .articles-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2.5rem;
    }

    .article-post-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }

    .article-post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        border-color: #006A4E;
    }

    .post-img-wrapper {
        aspect-ratio: 16/10;
        overflow: hidden;
        background: #f1f5f9;
    }

    .post-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .article-post-card:hover .post-img {
        transform: scale(1.03);
    }

    .post-card-body {
        padding: 2rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .post-meta {
        font-size: 0.9rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .post-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.4;
        margin-bottom: 1rem;
        flex-grow: 1;
    }

    .article-post-card:hover .post-card-title {
        color: #006A4E;
    }

    .post-card-excerpt {
        color: #475569;
        font-size: 0.98rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .post-card-link {
        font-size: 0.95rem;
        font-weight: 700;
        color: #006A4E;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: color 0.2s ease;
        text-decoration: none;
    }

    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 5rem 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        color: #64748b;
    }

    .empty-state i {
        font-size: 3.5rem;
        margin-bottom: 1.5rem;
        color: #cbd5e1;
    }

    /* Mobile Adaptability */
    @media (max-width: 991.98px) {
        .research-grid {
            grid-template-columns: 1fr;
            gap: 3rem;
        }
        .research-main-title {
            font-size: 2.25rem;
        }
        .pillars-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        .project-card-row {
            grid-template-columns: 1fr !important;
            gap: 2rem;
            margin-bottom: 4rem;
        }
        .project-card-row:nth-child(even) .project-img-box {
            order: -1;
        }
        .books-shelf-grid {
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        .articles-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }
</style>
@endpush

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => $page->title,
        'subtitle' => $page->meta_description ?? 'ভ্রান্ত অপব্যাখ্যার তাত্ত্বিক খণ্ডন এবং কৃষিতে বিপ্লবের মাধ্যমে খাদ্য নিরাপত্তা ও উন্নয়ন প্রকল্পসমূহ।',
        'badge_text' => 'গবেষণা ও উন্নয়ন',
        'badge_icon' => 'fas fa-microscope'
    ])

    <section class="research-section">
        <div class="container">
            
            <!-- Intro Section -->
            <div class="research-grid">
                <div class="research-content-left">
                    <span class="research-tagline">হেযবুত তওহীদ গবেষণা সেল</span>
                    <h2 class="research-main-title">গবেষণা ও <span class="highlight">উন্নয়ন উইং</span></h2>
                    
                    <p class="research-lead-text">
                        হেযবুত তওহীদের অন্যতম মূল শক্তি হচ্ছে এর গবেষণামূলক কাজ। সমাজে সাম্প্রদায়িক অপশক্তি ছড়ানো ভুল ব্যাখ্যার তাত্ত্বিক জবাব তৈরি করা আমাদের গবেষণার অন্যতম মূল লক্ষ্য।
                    </p>
                    
                    <p class="research-body-text">
                        আমাদের গবেষক দল ইসলামের আদি ইতিহাস, খেলাফতের মূল দর্শন এবং উগ্রবাদের কাউন্টার-ন্যারেティブ বা গঠনমূলক বিশ্লেষণ ও গবেষণামূলক কাজ পরিচালনা করেন। এই গবেষণার ফসলগুলো বিভিন্ন বই, কলাম ও বুকলেটের মাধ্যমে সবার কাছে সহজে পৌঁছে দেওয়া হয়।
                    </p>
                </div>

                <div class="research-visual-card">
                    <h3 class="fw-bold mb-3" style="color: #0f172a; font-size: 1.4rem;">সত্যের আলোর সন্ধানে গবেষণা</h3>
                    <p class="text-secondary mb-4" style="font-size: 0.98rem; line-height: 1.6; font-weight: 500;">
                        ভুল ব্যাখ্যা নিরসন ও সঠিক জ্ঞান বিস্তারের মাধ্যমে একটি বৈষম্যহীন ও ভারসাম্যপূর্ণ সমাজ গঠন আমাদের অঙ্গীকার।
                    </p>
                    <ul class="list-unstyled p-0 m-0" style="font-weight: 600; color: #475569;">
                        <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> ইসলামের মূল চেতনা পুনরুজ্জীবন</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> চরমপন্থা ও জঙ্গিবাদের বুদ্ধিবৃত্তিক মোকাবেলা</li>
                        <li class="mb-0"><i class="fas fa-check-circle text-success me-2"></i> ভ্রান্ত অপপ্রচারের উপযুক্ত জবাব খণ্ডন</li>
                    </ul>
                </div>
            </div>

            <!-- Three Pillars Grid -->
            <div class="mb-6">
                <h2 class="pillars-section-title">গবেষণার মূল ক্ষেত্রসমূহ</h2>
                <div class="pillars-grid">
                    
                    <!-- Card 1 -->
                    <div class="pillar-card">
                        <div class="pillar-icon-box">
                            <i class="fas fa-history"></i>
                        </div>
                        <h3>ইসলামের আদি ইতিহাস ও দর্শন</h3>
                        <p>ইসলামের আদি যুগের প্রকৃত ইতিহাস ও সমাজব্যবস্থার প্রকৃত রূপ গবেষণা করে সমসাময়িক যুগের সাথে তার সামঞ্জস্যপূর্ণ সমাধান বের করা।</p>
                    </div>

                    <!-- Card 2 -->
                    <div class="pillar-card">
                        <div class="pillar-icon-box">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>উগ্রবাদের বুদ্ধিবৃত্তিক মোকাবেলা</h3>
                        <p>উগ্রবাদ ও জঙ্গিবাদের পেছনের মনস্তত্ত্ব বিশ্লেষণ করে তাদের ধর্মের বিকৃত ব্যাখ্যার বিরুদ্ধে শক্তিশালী তাত্ত্বিক কাউন্টার-ন্যারেティブ তৈরি করা।</p>
                    </div>

                    <!-- Card 3 -->
                    <div class="pillar-card">
                        <div class="pillar-icon-box">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <h3>অপপ্রচার ও অপব্যাখ্যা খণ্ডন</h3>
                        <p>ইসলামের নামে প্রচলিত বিভিন্ন সামাজিক কুসংস্কার ও কুপ্রথার তাত্ত্বিক খণ্ডন এবং আন্দোলনের বিরুদ্ধে মিথ্যা প্রোপাগান্ডার উপযুক্ত জবাব তৈরি।</p>
                    </div>

                </div>
            </div>

            <!-- ============================================================
                 DEVELOPMENT PROJECTS SECTION (উন্নয়ন প্রকল্পসমূহ)
                 ============================================================ -->
            <div class="projects-container pt-5 border-top">
                <h2 class="pillars-section-title">আমাদের উন্নয়ন প্রকল্পসমূহ</h2>
                
                <p class="text-center text-secondary mx-auto mb-5 max-w-800" style="font-size: 1.15rem; line-height: 1.75; font-weight: 500; max-width: 850px;">
                    বাংলাদেশের মতো একটি কৃষিপ্রধান দেশে প্রকৃত অর্থনৈতিক ক্ষমতায়ন ও খাদ্য নিরাপত্তা কৃষিক্ষেত্রে একটি বিপ্লবের মাধ্যমেই শুরু হতে হবে। এই বিপ্লব পতিত জমিকে সুযোগ ও আত্মনির্ভরশীলতার উর্বর ভূমিতে রূপান্তরিত করেছে।
                </p>

                <!-- Project 1: Agriculture -->
                <div class="project-card-row">
                    <div class="project-img-box">
                        <img src="{{ asset('uploads/projects/3.webp') }}" alt="বৈচিত্র্যময় কৃষির মাধ্যমে খাদ্য নিরাপত্তা">
                    </div>
                    <div class="project-details-box">
                        <span class="project-badge">কৃষি বিপ্লব ও খাদ্য নিরাপত্তা</span>
                        <h3 class="project-title">বৈচিত্র্যময় কৃষির মাধ্যমে খাদ্য নিরাপত্তা</h3>
                        <p class="project-desc">
                            প্রাথমিক উদ্দেশ্য হলো বিভিন্ন ধরনের নিত্যপ্রয়োজনীয় ফসল চাষের মাধ্যমে স্থানীয় স্বনির্ভরতা অর্জন করা এবং একটি স্থিতিশীল ও বৈচিত্র্যময় খাদ্য সরবরাহ নিশ্চিত করা।
                        </p>
                        <ul class="project-bullets-list">
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>ধান, ভুট্টা ও আলুর মতো প্রধান ফসলের ব্যাপক চাষাবাদ।</span>
                            </li>
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>বিভিন্ন মৌসুমি শাকসবজি ও ফলের ব্যাপক চাষাবাদ।</span>
                            </li>
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>স্বাস্থ্যকর খাদ্যাভ্যাসের প্রচার এবং অতিরিক্ত আয়ের উৎস তৈরি করা।</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Project 2: Fisheries -->
                <div class="project-card-row">
                    <div class="project-details-box order-1 order-lg-0">
                        <span class="project-badge">মৎস্য চাষ প্রকল্প</span>
                        <h3 class="project-title">বাণিজ্যিক মাছ চাষ</h3>
                        <p class="project-desc">
                            ২০ একর জুড়ে একটি বিশাল মাছের খামার স্থাপন করা হয়েছে, যা গ্রামের অব্যবহৃত পুকুরগুলোকে উৎপাদনশীল সম্পদে এবং স্থানীয় অর্থনীতির ভিত্তিপ্রস্তরে রূপান্তরিত করেছে।
                        </p>
                        <ul class="project-bullets-list">
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>উচ্চ ফলনশীল উৎপাদন, যেখানে নয়টি নতুন পুকুর থেকে প্রতিদিন দুই টন মাছ যোগ হচ্ছে।</span>
                            </li>
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>টেকসই প্রজননের জন্য একটি সরকার-স্বীকৃত পুরস্কৃত হ্যাচারি।</span>
                            </li>
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>স্থানীয় পুষ্টি পূরণ ও বাণিজ্যিক সাফল্যের একটি প্রধান উৎস।</span>
                            </li>
                        </ul>
                    </div>
                    <div class="project-img-box order-0 order-lg-1">
                        <img src="{{ asset('uploads/projects/6.webp') }}" alt="বাণিজ্যিক মাছ চাষ">
                    </div>
                </div>

                <!-- Project 3: Cattle -->
                <div class="project-card-row">
                    <div class="project-img-box">
                        <img src="{{ asset('uploads/projects/8.webp') }}" alt="আধুনিক গবাদি পশু পালন">
                    </div>
                    <div class="project-details-box">
                        <span class="project-badge">এমএম এগ্রো ফার্ম লিমিটেড</span>
                        <h3 class="project-title">আধুনিক গবাদি পশু পালন</h3>
                        <p class="project-desc">
                            উন্নত মানের প্রোটিনের স্থানীয় চাহিদা মেটাতে এমএম এগ্রো ফার্ম লিমিটেড-এর অধীনে সম্পূর্ণ নৈতিক ও জৈব পশু পালনের মডেল হিসেবে আধুনিক গবাদি পশুর খামার স্থাপন করা হয়েছে।
                        </p>
                        <ul class="project-bullets-list">
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>প্রতি বছর ৫০০-র বেশি গরু ক্ষতিকর স্টেরয়েড বা হরমোন ছাড়াই সম্পূর্ণ প্রাকৃতিকভাবে পালন করা হয়।</span>
                            </li>
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>২০ একর জমিতে উন্নত মানের উন্নত জাতের ঘাস চাষের মাধ্যমে সমর্থিত।</span>
                            </li>
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>খামারের গুণমান নিশ্চিত করতে এবং খাদ্য খরচ কমাতে এর নিজস্ব আধুনিক ফিড মিল রয়েছে।</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Project 4: Integrated Economy -->
                <div class="project-card-row">
                    <div class="project-details-box order-1 order-lg-0">
                        <span class="project-badge">কাররানি ফুডস ও সমন্বিত চক্র</span>
                        <h3 class="project-title">সমন্বিত অর্থনৈতিক চক্র ও স্বনির্ভরতা</h3>
                        <p class="project-desc">
                            এই কৃষি প্রকল্পগুলো অন্যান্য অর্থনৈতিক উদ্যোগের সাথে নির্বিঘ্নে সমন্বিত। উৎপাদিত ফসল ও পণ্য স্থানীয় জনগোষ্ঠী এবং “কাররানি ফুডস” প্রক্রিয়াজাতকরণ শিল্পে সরবরাহ করা হয়, যা একটি সমন্বিত অর্থনৈতিক চক্র তৈরি করে।
                        </p>
                        <ul class="project-bullets-list">
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>খামারগুলো থেকে উৎপাদিত পণ্য “কারানি ফুডস”-এর প্রক্রিয়াজাতকরণ শিল্পে সরাসরি সরবরাহ করা হয়।</span>
                            </li>
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>গরুগুলো সম্পূর্ণ জৈব পদ্ধতিতে পালন করা হয়, যা বাজারের জন্য শতভাগ স্বাস্থ্যকর ও নিরাপদ মাংসের সরবরাহ নিশ্চিত করে।</span>
                            </li>
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>শত শত বেকার যুবকের কর্মসংস্থান সৃষ্টি করে স্বনির্ভর ভবিষ্যৎ গড়ে তোলা।</span>
                            </li>
                        </ul>
                    </div>
                    <div class="project-img-box order-0 order-lg-1">
                        <img src="{{ asset('uploads/projects/7.webp') }}" alt="সমন্বিত অর্থনৈতিক চক্র ও স্বনির্ভরতা">
                    </div>
                </div>

            </div>

            <!-- Research Outcomes: Books -->
            @if(isset($researchBooks) && count($researchBooks) > 0)
                <div class="pt-5 border-top">
                    <h2 class="pillars-section-title">গবেষণামূলক মূল প্রকাশনাসমূহ</h2>
                    <div class="books-shelf-grid">
                        @foreach($researchBooks as $book)
                            <div class="book-item-card">
                                <img src="{{ $book->image_url }}" alt="{{ $book->title }}" class="book-cover-img">
                                <h3 class="book-title-text">{{ $book->title }}</h3>
                                <p class="book-writer-text">{{ $book->writer ?? 'হেযবুত তওহীদ' }}</p>
                                <a href="{{ route('library.read', $book->slug) }}" class="book-btn-link">
                                    পড়তে শুরু করুন <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Research Articles Section -->
            <div class="mt-5 pt-5 border-top">
                <h2 class="pillars-section-title">গবেষণামূলক নিবন্ধ ও প্রবন্ধ</h2>
                
                <div class="articles-grid">
                    @forelse($researchPosts as $post)
                        <div class="article-post-card">
                            <div class="post-img-wrapper">
                                @if($post->featured_image)
                                    <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="post-img">
                                @else
                                    <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?q=80&w=600" alt="{{ $post->title }}" class="post-img">
                                @endif
                            </div>
                            
                            <div class="post-card-body">
                                <div class="post-meta">
                                    <i class="far fa-calendar-alt"></i>
                                    <span>{{ $post->published_at ? $post->published_at->format('d M, Y') : $post->created_at->format('d M, Y') }}</span>
                                </div>
                                <h3 class="post-card-title">{{ $post->title }}</h3>
                                <p class="post-card-excerpt">{{ $post->short_description ?? Str::limit(strip_tags($post->content), 120) }}</p>
                                <a href="{{ route('blog.detail', $post->slug) }}" class="post-card-link">
                                    বিস্তারিত পড়ুন <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-newspaper"></i>
                            <p class="mb-0 fw-semibold">বর্তমানে কোনো গবেষণা প্রবন্ধ পাওয়া যায়নি।</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </section>

@endsection
