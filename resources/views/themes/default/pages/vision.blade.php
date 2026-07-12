@extends('theme::layouts.app')

@section('title', 'আমাদের দৃষ্টিভঙ্গি ও রূপকল্প - হেযবুত তওহীদ')

@push('styles')
<style>
    /* ============================================================
       VISION SECTION - Clean, Premium Color Combination & Sharp
       ============================================================ */
    .vision-section {
        background: #fafbfc;
        position: relative;
        padding: 4rem 0 6rem;
    }

    .vision-title {
        font-size: 2.8rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.01em;
        line-height: 1.25;
        margin-bottom: 0.5rem;
    }
    .vision-title .highlight {
        color: #1e40af; /* Premium Indigo Blue */
        position: relative;
    }
    .vision-title .highlight::after {
        content: '';
        position: absolute;
        bottom: 4px;
        left: 0;
        width: 100%;
        height: 4px;
        background: #3b82f6; /* Vibrant Blue */
        border-radius: 2px;
    }

    .vision-subtitle {
        color: #475569;
        font-weight: 600;
        line-height: 1.8;
        letter-spacing: 0.01em;
    }

    .section-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #eff6ff; /* Soft Blue */
        color: #1e40af;
        padding: 0.4rem 1.2rem;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 1rem;
        letter-spacing: 0.02em;
    }

    /* ---------- Dashboard Container ---------- */
    .vision-dashboard {
        display: flex;
        background: #ffffff;
        border: 1px solid #eaedf0;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02), 0 16px 48px rgba(0, 0, 0, 0.04);
        min-height: 680px;
    }

    /* ---------- Sidebar Navigation ---------- */
    .vision-sidebar {
        width: 310px;
        background: #f8fafc;
        border-right: 1px solid #eaedf0;
        padding: 1.5rem 0;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        overflow-y: auto;
        max-height: 680px;
    }
    .vision-sidebar::-webkit-scrollbar {
        width: 3px;
    }
    .vision-sidebar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .vision-tab-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.9rem 1.5rem;
        border: none;
        background: transparent;
        color: #475569;
        font-size: 0.95rem;
        font-weight: 600;
        text-align: left;
        transition: all 0.2s ease;
        width: 100%;
        cursor: pointer;
        line-height: 1.5;
        border-left: 4px solid transparent;
        outline: none;
    }
    .vision-tab-btn .tab-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        color: #64748b;
        font-size: 0.95rem;
        flex-shrink: 0;
        transition: color 0.2s ease;
    }
    .vision-tab-btn .tab-label {
        flex: 1;
        font-size: 0.95rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .vision-tab-btn .tab-badge {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.15rem 0.45rem;
        border-radius: 6px;
        background: #e2e8f0;
        color: #475569;
        transition: all 0.2s ease;
    }
    .vision-tab-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-left-color: #cbd5e1;
    }
    .vision-tab-btn:hover .tab-icon {
        color: #0f172a;
    }
    .vision-tab-btn.active {
        background: #eff6ff; /* Soft Blue Active Background */
        color: #1e40af; /* Deep Blue Active Text */
        border-left-color: #3b82f6; /* Vibrant Blue left bar */
        font-weight: 700;
    }
    .vision-tab-btn.active .tab-icon {
        color: #1e40af;
    }
    .vision-tab-btn.active .tab-badge {
        background: #dbeafe;
        color: #1e40af;
    }

    /* ---------- Content Pane ---------- */
    .vision-content-pane {
        flex: 1;
        padding: 2.5rem 3rem 3rem;
        background: #ffffff;
        overflow-y: auto;
        max-height: 680px;
    }
    .vision-content-pane::-webkit-scrollbar {
        width: 3px;
    }
    .vision-content-pane::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    /* ---------- Panel ---------- */
    .vision-panel {
        display: none;
        animation: fadeSlide 0.2s ease-out forwards;
    }
    .vision-panel.active {
        display: block;
    }

    @keyframes fadeSlide {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }

    .panel-header {
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .panel-subtitle-tag {
        font-size: 0.7rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .panel-subtitle-tag::before {
        content: '';
        display: inline-block;
        width: 18px;
        height: 2px;
        background: #3b82f6;
        border-radius: 2px;
    }
    .panel-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.01em;
        line-height: 1.3;
        margin: 0;
    }
    .panel-title i {
        color: #1e40af;
        margin-right: 0.5rem;
        font-size: 1.2rem;
    }

    /* ---------- Core Statement ---------- */
    .panel-core-statement {
        background: #f8fafc;
        border-left: 4px solid #3b82f6;
        padding: 1.25rem 1.75rem;
        border-radius: 0 12px 12px 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.8;
        margin-bottom: 1.75rem;
        position: relative;
        letter-spacing: 0.01em;
    }
    .panel-core-statement .quote-icon {
        position: absolute;
        right: 1.25rem;
        top: 0.75rem;
        font-size: 1.4rem;
        color: #dbeafe;
    }

    /* ---------- QA Cards ---------- */
    .qa-block {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.5rem 1.75rem;
        margin-bottom: 1rem;
        background: #ffffff;
        transition: border-color 0.2s ease, background-color 0.2s ease;
    }
    .qa-block:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }
    .qa-block:last-child {
        margin-bottom: 0;
    }

    .qa-question {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        letter-spacing: 0.01em;
    }
    .qa-question .q-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #3b82f6;
        color: #ffffff;
        font-size: 0.55rem;
        flex-shrink: 0;
        margin-top: 3px;
    }

    .qa-answer {
        font-size: 1.05rem;
        font-weight: 600;
        color: #475569;
        line-height: 1.8;
        margin-bottom: 0;
        padding-left: 2.25rem;
        letter-spacing: 0.01em;
    }

    /* ---------- Responsive ---------- */
    @media (max-width: 991.98px) {
        .vision-dashboard {
            flex-direction: column;
            border-radius: 18px;
            min-height: auto;
        }
        .vision-sidebar {
            width: 100%;
            max-height: none;
            border-right: none;
            border-bottom: 1px solid #eaedf0;
            padding: 0.75rem 1rem;
            flex-direction: row;
            overflow-x: auto;
            overflow-y: hidden;
            gap: 0.2rem;
            scroll-behavior: smooth;
            background: #f8fafc;
        }
        .vision-sidebar::-webkit-scrollbar {
            display: none;
        }
        .vision-tab-btn {
            display: inline-flex;
            width: auto;
            padding: 0.5rem 0.9rem;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            font-size: 0.85rem;
            flex-shrink: 0;
            gap: 0.4rem;
            border-left: none;
            border-bottom: 3px solid transparent;
        }
        .vision-tab-btn .tab-icon {
            width: 24px;
            height: 24px;
            font-size: 0.7rem;
        }
        .vision-tab-btn .tab-label {
            font-size: 0.82rem;
        }
        .vision-tab-btn .tab-badge {
            display: none;
        }
        .vision-tab-btn:hover {
            border-bottom-color: #cbd5e1;
        }
        .vision-tab-btn.active {
            border-bottom-color: #3b82f6;
            background: #eff6ff;
            color: #1e40af;
            box-shadow: none;
        }
        .vision-tab-btn.active .tab-icon {
            color: #1e40af;
        }
        .vision-content-pane {
            padding: 1.75rem 1.5rem 2rem;
            max-height: none;
            min-height: 400px;
        }
        .panel-title {
            font-size: 1.4rem;
        }
        .panel-title i {
            font-size: 1rem;
        }
        .panel-core-statement {
            font-size: 0.98rem;
            padding: 1rem 1.25rem;
        }
        .panel-core-statement .quote-icon {
            font-size: 1.2rem;
        }
        .qa-block {
            padding: 1.1rem 1.25rem;
        }
        .qa-answer {
            padding-left: 1.5rem;
            font-size: 0.93rem;
        }
        .vision-title {
            font-size: 2.2rem;
        }
        .vision-section {
            padding: 2.5rem 0 4rem;
        }
    }

    @media (max-width: 575.98px) {
        .vision-title {
            font-size: 1.6rem;
        }
        .vision-subtitle {
            font-size: 0.95rem;
        }
        .section-badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.9rem;
        }
        .vision-content-pane {
            padding: 1rem 0.75rem 1.25rem;
            min-height: 300px;
        }
        .panel-title {
            font-size: 1.15rem;
        }
        .panel-title i {
            font-size: 0.9rem;
        }
        .panel-core-statement {
            font-size: 0.9rem;
            padding: 0.85rem 1rem;
            border-left-width: 3px;
        }
        .panel-core-statement .quote-icon {
            display: none;
        }
        .qa-block {
            padding: 0.85rem;
        }
        .qa-question {
            font-size: 1.05rem;
            gap: 0.5rem;
        }
        .qa-question .q-icon {
            width: 20px;
            height: 20px;
            margin-top: 2px;
        }
        .qa-answer {
            padding-left: 0;
            margin-top: 0.25rem;
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'আমাদের দৃষ্টিভঙ্গি',
        'subtitle' => 'একটি শান্তিময়, বৈষম্যহীন ও ধর্মান্ধতামুক্ত সমাজ বিনির্মাণের রূপকল্প।',
        'badge_text' => 'ভিশন ও মিশন',
        'badge_icon' => 'fas fa-eye'
    ])

    <!-- Main Vision Section -->
    <section class="vision-section">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-badge">
                    <i class="fas fa-compass"></i>
                    আমাদের পথচলা
                </div>
                <h1 class="vision-title">
                    <span class="highlight">দৃষ্টিভঙ্গি</span> ও রূপকল্প
                </h1>
                <p class="vision-subtitle mx-auto" style="max-width: 680px;">
                    হেযবুত তওহীদ সমাজে শান্তি, সম্প্রীতি ও ন্যায়বিচার প্রতিষ্ঠার লক্ষ্যে কিছু সুনির্দিষ্ট আদর্শ ও দৃষ্টিভঙ্গি ধারণ করে।
                </p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="vision-dashboard">

                        <!-- Sidebar -->
                        <div class="vision-sidebar" role="tablist">
                            <button class="vision-tab-btn active" onclick="switchTab(event, 'tab-structure')" role="tab" aria-selected="true">
                                <span class="tab-icon"><i class="fas fa-sitemap"></i></span>
                                <span class="tab-label">সাংগঠনিক কাঠামো</span>
                                <span class="tab-badge">০১</span>
                            </button>
                            <button class="vision-tab-btn" onclick="switchTab(event, 'tab-goals')" role="tab" aria-selected="false">
                                <span class="tab-icon"><i class="fas fa-bullseye"></i></span>
                                <span class="tab-label">লক্ষ্য ও উদ্দেশ্য</span>
                                <span class="tab-badge">০২</span>
                            </button>
                            <button class="vision-tab-btn" onclick="switchTab(event, 'tab-women')" role="tab" aria-selected="false">
                                <span class="tab-icon"><i class="fas fa-venus-mars"></i></span>
                                <span class="tab-label">নারী অধিকার</span>
                                <span class="tab-badge">০৩</span>
                            </button>
                            <button class="vision-tab-btn" onclick="switchTab(event, 'tab-extremism')" role="tab" aria-selected="false">
                                <span class="tab-icon"><i class="fas fa-shield-alt"></i></span>
                                <span class="tab-label">জঙ্গিবাদ বিরোধী</span>
                                <span class="tab-badge">০৪</span>
                            </button>
                            <button class="vision-tab-btn" onclick="switchTab(event, 'tab-harmony')" role="tab" aria-selected="false">
                                <span class="tab-icon"><i class="fas fa-handshake"></i></span>
                                <span class="tab-label">সাম্প্রদায়িক সম্প্রীতি</span>
                                <span class="tab-badge">০৫</span>
                            </button>
                            <button class="vision-tab-btn" onclick="switchTab(event, 'tab-law')" role="tab" aria-selected="false">
                                <span class="tab-icon"><i class="fas fa-gavel"></i></span>
                                <span class="tab-label">আইন ও সংবিধান</span>
                                <span class="tab-badge">০৬</span>
                            </button>
                            <button class="vision-tab-btn" onclick="switchTab(event, 'tab-finance')" role="tab" aria-selected="false">
                                <span class="tab-icon"><i class="fas fa-coins"></i></span>
                                <span class="tab-label">অর্থায়ন ও তহবিল</span>
                                <span class="tab-badge">০৭</span>
                            </button>
                            <button class="vision-tab-btn" onclick="switchTab(event, 'tab-revival')" role="tab" aria-selected="false">
                                <span class="tab-icon"><i class="fas fa-quran"></i></span>
                                <span class="tab-label">কোর’আন ও সুন্নাহ</span>
                                <span class="tab-badge">০৮</span>
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="vision-content-pane">

                            <!-- Panel 1 -->
                            <div id="tab-structure" class="vision-panel active" role="tabpanel">
                                <div class="panel-header">
                                    <span class="panel-subtitle-tag">দৃষ্টিভঙ্গি ০১</span>
                                    <span style="font-size:0.7rem; color:#64748b; font-weight:700; letter-spacing:0.04em;">সাংগঠনিক কাঠামো</span>
                                </div>
                                <div class="panel-core-statement">
                                    <i class="fas fa-quote-right quote-icon"></i>
                                    হেযবুত তওহীদ কোনো রাজনৈতিক দল নয়, বরং এটি একটি অরাজনৈতিক সমাজ সংস্কারমূলক আন্দোলন।
                                </div>
                                <div class="qa-block">
                                    <h4 class="qa-question">
                                        <span class="q-icon"><i class="fas fa-chevron-right"></i></span>
                                        কীভাবে আপনাদের সংগঠন পরিচালিত হয়?
                                    </h4>
                                    <p class="qa-answer">
                                        সংগঠনটি একটি কেন্দ্রিক ও সুশৃঙ্খল নেতৃত্বের অধীনে পরিচালিত হয়। হেযবুত তওহীদের প্রধানকে 'এমাম' হিসেবে অভিহিত করা হয়। প্রতিষ্ঠাতা এমাম মোহাম্মদ বায়াজীদ খান পন্নীর মৃত্যুর পর থেকে জনাব হোসাইন মোহাম্মদ সেলিম সংগঠনের এমাম হিসেবে দায়িত্ব পালন করছেন। তাঁর অধীনে বিভিন্ন এলাকা ও কাজের স্তরবিন্যাস অনুযায়ী আমীর এবং সাধারণ কর্মী হিসেবে মোজাহেদ ও মোজাহেদা শৃঙ্খলা বজায় রেখে কাজ করেন।
                                    </p>
                                </div>
                            </div>

                            <!-- Panel 2 -->
                            <div id="tab-goals" class="vision-panel" role="tabpanel">
                                <div class="panel-header">
                                    <span class="panel-subtitle-tag">দৃষ্টিভঙ্গি ০২</span>
                                    <span style="font-size:0.7rem; color:#64748b; font-weight:700; letter-spacing:0.04em;">লক্ষ্য ও উদ্দেশ্য</span>
                                </div>
                                <div class="panel-core-statement">
                                    <i class="fas fa-quote-right quote-icon"></i>
                                    সমগ্র মানবজাতিকে এক স্রষ্টার হুকুমের অধীনে ঐক্যবদ্ধ করে অশান্তিময় সমাজকে শান্তিময় সমাজে রূপান্তরিত করা।
                                </div>
                                <div class="qa-block">
                                    <h4 class="qa-question">
                                        <span class="q-icon"><i class="fas fa-chevron-right"></i></span>
                                        হেযবুত তওহীদের মূল অভিলক্ষ্য কী?
                                    </h4>
                                    <p class="qa-answer">
                                        বর্তমান বস্তুবাদী দাজ্জালীয় সভ্যতার কারণে বিশ্বজুড়ে যে অন্যায়, অশান্তি ও অবিচার ছড়িয়ে পড়েছে, তার পরিবর্তে স্রষ্টার নিখাদ একত্ববাদ বা তওহীদের ভিত্তিতে একটি শান্তিময় সমাজ গঠন করা। ধর্মকে ব্যক্তিগত বা রাজনৈতিক স্বার্থে ব্যবহার না করে, ধর্মব্যবসা, জঙ্গিবاد ও সাম্প্রদায়িকতার অবসান ঘটিয়ে মানবজাতিকে সত্য ও ন্যায়ের ওপর ঐক্যবদ্ধ করা আমাদের প্রধান লক্ষ্য।
                                    </p>
                                </div>
                            </div>

                            <!-- Panel 3 -->
                            <div id="tab-women" class="vision-panel" role="tabpanel">
                                <div class="panel-header">
                                    <span class="panel-subtitle-tag">দৃষ্টিভঙ্গি ০৩</span>
                                    <span style="font-size:0.7rem; color:#64748b; font-weight:700; letter-spacing:0.04em;">নারী অধিকার ও মর্যাদা</span>
                                </div>
                                <div class="panel-core-statement">
                                    <i class="fas fa-quote-right quote-icon"></i>
                                    ইসলামে নারীদের মুখ ঢেকে পর্দা করার কোনো বিধান নেই। দেশ ও সমাজ গঠনে পুরুষ ও নারীর সমান অংশীদারিত্ব আবশ্যক।
                                </div>
                                <div class="qa-block">
                                    <h4 class="qa-question">
                                        <span class="q-icon"><i class="fas fa-chevron-right"></i></span>
                                        ইসলামে কি নারীদের মুখ ঢাকা বাধ্যতামূলক?
                                    </h4>
                                    <p class="qa-answer">
                                        না, ইসলামে নারীদের মুখমণ্ডল ঢেকে রাখার কোনো হুকুম বা নির্দেশনা কোর’আনে নেই। পবিত্র কোর’আনে সাধারণত প্রকাশমান সৌন্দর্য ব্যতিরেকে বক্ষদেশ চাদর বা ওড়না দিয়ে ঢেকে রাখার নির্দেশ রয়েছে (সুরা নূর ৩১)। রসুলাল্লাহর যুগেও নারীরা মুখ খোলা রেখে জাতীয় ও সামাজিক প্রায় সকল কর্মকাণ্ডে অংশ নিতেন।
                                    </p>
                                </div>
                                <div class="qa-block">
                                    <h4 class="qa-question">
                                        <span class="q-icon"><i class="fas fa-chevron-right"></i></span>
                                        হেযবুত তওহীদের নারীরা কি ঘরের বাইরে কাজ করতে পারবে?
                                    </h4>
                                    <p class="qa-answer">
                                        হ্যাঁ, অবশ্যই। রসুলাল্লাহর যুগে নারী আসহাবগণ যেমন যুদ্ধে অংশ নেওয়া থেকে শুরু করে কৃষিকাজ, ব্যবসা-বাণিজ্য ও চিকিৎসাসেবা দিতেন, ঠিক তেমনি হেযবুত তওহীদেও শালীনতা রক্ষা করে নারীরা প্রকাশনা ও বই বিক্রি, দাপ্তরিক কাজ এবং বিভিন্ন গুরুত্বপূর্ণ সাংগঠনিক ও নেতৃত্বমূলক কার্যক্রমে সরাসরি ভূমিকা রাখছেন।
                                    </p>
                                </div>
                            </div>

                            <!-- Panel 4 -->
                            <div id="tab-extremism" class="vision-panel" role="tabpanel">
                                <div class="panel-header">
                                    <span class="panel-subtitle-tag">দৃষ্টিভঙ্গি ০৪</span>
                                    <span style="font-size:0.7rem; color:#64748b; font-weight:700; letter-spacing:0.04em;">জঙ্গিবাদ ও উগ্রবাদ প্রতিবাদ</span>
                                </div>
                                <div class="panel-core-statement">
                                    <i class="fas fa-quote-right quote-icon"></i>
                                    ইসলামে সন্ত্রাসী কার্যকলাপ বা কোনো নিরীহ মানুষের ওপর আক্রমণের স্থান নেই। হেযবুত তওহীদ বোমাবাজি ও উগ্রপন্থার ঘোর বিরোধী।
                                </div>
                                <div class="qa-block">
                                    <h4 class="qa-question">
                                        <span class="q-icon"><i class="fas fa-chevron-right"></i></span>
                                        হেযবুত তওহীদ কি কোনো সশস্ত্র জেহাদে বিশ্বাস করে?
                                    </h4>
                                    <p class="qa-answer">
                                        না, হেযবুত তওহীদ কোনো ধরণের সহিংসতা, বোমাবাজি বা সশস্ত্র যুদ্ধকে সমর্থন করে না। জেহাদ অর্থ সত্য ও ন্যায় প্রতিষ্ঠার লক্ষ্যে জান-মাল দিয়ে সর্বাত্মক ও শান্তিময় আদর্শিক লড়াই চালানো। মানুষের হৃদয়ের পরিবর্তন বুলেট বা বোমার সাহায্যে করা সম্ভব নয়, তা শুধুমাত্র সত্যের অকাট্য যুক্তি ও সঠিক বার্তার মাধ্যমেই সম্ভব।
                                    </p>
                                </div>
                            </div>

                            <!-- Panel 5 -->
                            <div id="tab-harmony" class="vision-panel" role="tabpanel">
                                <div class="panel-header">
                                    <span class="panel-subtitle-tag">দৃষ্টিভঙ্গি ০৫</span>
                                    <span style="font-size:0.7rem; color:#64748b; font-weight:700; letter-spacing:0.04em;">সাম্প্রদায়িক সম্প্রীতি</span>
                                </div>
                                <div class="panel-core-statement">
                                    <i class="fas fa-quote-right quote-icon"></i>
                                    সব ধর্মের মানুষের পূর্ণ নিরাপত্তা ও ধর্মীয় স্বাধীনতার প্রতিষ্ঠা করাই ইসলামের মূল সুর।
                                </div>
                                <div class="qa-block">
                                    <h4 class="qa-question">
                                        <span class="q-icon"><i class="fas fa-chevron-right"></i></span>
                                        অমুসলিমদের প্রতি হেযবুত তওহীদের দৃষ্টিভঙ্গি কী?
                                    </h4>
                                    <p class="qa-answer">
                                        হেযবুত তওহীদ মনে করে প্রতিটি ধর্মের মানুষের সমান নাগরিক ও ধর্মীয় অধিকার রয়েছে। ইসলাম অমুসলিমদের ওপর জোর করে ধর্ম চাপিয়ে দেওয়া সমর্থন করে না। তাদের উপাসনালয়ের নিরাপত্তা, জান ও মালের হেফাজত করা প্রত্যেক মুসলিমের অপরিহার্য দায়িত্ব।
                                    </p>
                                </div>
                            </div>

                            <!-- Panel 6 -->
                            <div id="tab-law" class="vision-panel" role="tabpanel">
                                <div class="panel-header">
                                    <span class="panel-subtitle-tag">দৃষ্টিভঙ্গি ০৬</span>
                                    <span style="font-size:0.7rem; color:#64748b; font-weight:700; letter-spacing:0.04em;">আইন ও দেশের সংবিধান</span>
                                </div>
                                <div class="panel-core-statement">
                                    <i class="fas fa-quote-right quote-icon"></i>
                                    দেশের স্বাধীনতা, সংবিধান ও sovereignty রক্ষা করা এবং প্রচলিত আইন মেনে চলা প্রত্যেক নাগরিকের পবিত্র দায়িত্ব।
                                </div>
                                <div class="qa-block">
                                    <h4 class="qa-question">
                                        <span class="q-icon"><i class="fas fa-chevron-right"></i></span>
                                        হেযবুত তওহীদ কি প্রচলিত সংবিধান ও আইন মান্য করে?
                                    </h4>
                                    <p class="qa-answer">
                                        হ্যাঁ, হেযবুত তওহীদ রাষ্ট্রকে এবং দেশের প্রচলিত সকল আইনকে শতভাগ শ্রদ্ধা ও মান্য করে কাজ করে। আমাদের কোনো কর্মী কখনো কোনো বেআইনি কাজের সাথে যুক্ত হবে না এবং আইনের পরিপন্থী কোনো কর্মকাণ্ড করবে না।
                                    </p>
                                </div>
                            </div>

                            <!-- Panel 7 -->
                            <div id="tab-finance" class="vision-panel" role="tabpanel">
                                <div class="panel-header">
                                    <span class="panel-subtitle-tag">দৃষ্টিভঙ্গি ০৭</span>
                                    <span style="font-size:0.7rem; color:#64748b; font-weight:700; letter-spacing:0.04em;">অর্থায়ন ও তহবিল</span>
                                </div>
                                <div class="panel-core-statement">
                                    <i class="fas fa-quote-right quote-icon"></i>
                                    হেযবুত তওহীদ কোনো বিদেশী অনুদান বা কোনো প্রকার অসচ্ছ উৎস থেকে ফান্ড গ্রহণ করে না।
                                </div>
                                <div class="qa-block">
                                    <h4 class="qa-question">
                                        <span class="q-icon"><i class="fas fa-chevron-right"></i></span>
                                        আপনাদের প্রচারণা ও কার্যক্রমের অর্থ কোথা থেকে আসে?
                                    </h4>
                                    <p class="qa-answer">
                                        আমাদের সমস্ত প্রকাশনা ও কার্যক্রমের অর্থ যোগান দেওয়া হয় সংগঠনের সদস্যদের স্বতঃস্ফূর্ত মাসিক এয়ানত (হাদিয়া) এবং আমাদের বই, পত্রিকা ও ডিজিটাল লাইব্রেরির প্রকাশনা বিক্রয়ের আয়ের মাধ্যমে। বাইরের কোনো দাতাগোষ্ঠী বা অনুদানকারীর ওপর আমাদের সংগঠন নির্ভরশীল নয়, যা আমাদের মত প্রকাশের স্বাধীনতা ও আদর্শিক পথচলাকে নিরপেক্ষ রাখে।
                                    </p>
                                </div>
                            </div>

                            <!-- Panel 8 -->
                            <div id="tab-revival" class="vision-panel" role="tabpanel">
                                <div class="panel-header">
                                    <span class="panel-subtitle-tag">দৃষ্টিভঙ্গি ০৮</span>
                                    <span style="font-size:0.7rem; color:#64748b; font-weight:700; letter-spacing:0.04em;">কোর’আন ও সুন্নাহ</span>
                                </div>
                                <div class="panel-core-statement">
                                    <i class="fas fa-quote-right quote-icon"></i>
                                    ইসলামের আদি ও অকৃত্রিম রূপ যা মানুষকে একতাবদ্ধ ও শান্তিময় করে, তার পুর্নজাগরণই আমাদের প্রধান কাজ।
                                </div>
                                <div class="qa-block">
                                    <h4 class="qa-question">
                                        <span class="q-icon"><i class="fas fa-chevron-right"></i></span>
                                        হেযবুত তওহীদ কি কোর’আন ও সুন্নাহ মেনে চলে?
                                    </h4>
                                    <p class="qa-answer">
                                        হ্যাঁ, হেযবুত তওহীদের একমাত্র ভিত্তিই হলো পবিত্র কোর’আন এবং রসুলাল্লাহর বাস্তব সুন্নাহ। আমরা কোনো মাজহাব বা তরিকতের সাথে বৈরিতা করি না, তবে ইসলামের আদি সোনালী যুগের সেই প্রকৃত শিক্ষা—যা মানবজাতিকে শৃঙ্খলমুক্ত করে ঐক্যবদ্ধ করত—তার পুনরুদ্ধার করতে আমরা অঙ্গীকারাবদ্ধ।
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
    function switchTab(event, tabId) {
        if (event) event.preventDefault();

        const buttons = document.querySelectorAll('.vision-tab-btn');
        buttons.forEach(btn => {
            const isActive = btn === event?.currentTarget;
            btn.classList.toggle('active', isActive);
            btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });

        const panels = document.querySelectorAll('.vision-panel');
        panels.forEach(panel => {
            const isTarget = panel.id === tabId;
            panel.classList.toggle('active', isTarget);
            panel.style.display = isTarget ? 'block' : 'none';
        });

        if (window.innerWidth <= 991.98 && event?.currentTarget) {
            const sidebar = document.querySelector('.vision-sidebar');
            const target = event.currentTarget;
            const scrollLeft = target.offsetLeft - (sidebar.clientWidth / 2) + (target.clientWidth / 2);
            sidebar.scrollTo({ left: scrollLeft, behavior: 'smooth' });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const activeTab = document.querySelector('.vision-tab-btn.active');
        if (activeTab) {
            const onclickAttr = activeTab.getAttribute('onclick');
            const match = onclickAttr ? onclickAttr.match(/'([^']+)'/) : null;
            const targetId = match ? match[1] : 'tab-structure';
            const panels = document.querySelectorAll('.vision-panel');
            panels.forEach(panel => {
                const isTarget = panel.id === targetId;
                panel.classList.toggle('active', isTarget);
                panel.style.display = isTarget ? 'block' : 'none';
            });
        }
    });
</script>
@endpush
