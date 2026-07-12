@extends('theme::layouts.app')

@section('title', 'সোনাইমুড়ী ট্র্যাজেডি - পোরকরা পৈশাচিক হত্যাকাণ্ড')

@push('styles')
<style>
    .tragedy-section {
        background: #0f172a;
        padding: 5rem 0 7rem;
    }

    .tragedy-section,
    .tragedy-section * {
        font-family: 'Baloo Da 2', 'SolaimanLipi', sans-serif !important;
    }

    .tragedy-container {
        max-width: 950px;
        margin: 0 auto;
    }

    /* Lead Block */
    .tragedy-lead-card {
        background: #1e293b;
        border-radius: 28px;
        padding: 3rem;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.3);
        border: 1px solid #334155;
        margin-bottom: 4rem;
        position: relative;
        overflow: hidden;
    }

    .tragedy-lead-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 8px;
        background: linear-gradient(90deg, #ef4444, #b91c1c);
    }

    .tragedy-heading {
        font-size: 2.25rem;
        font-weight: 700;
        color: #f8fafc;
        margin-bottom: 1.5rem;
        letter-spacing: -0.02em;
    }

    .tragedy-text {
        font-size: 1.12rem;
        color: #cbd5e1;
        line-height: 1.85;
        font-weight: 500;
        text-align: justify;
        margin-bottom: 1.5rem;
    }

    .tragedy-text:last-child {
        margin-bottom: 0;
    }

    /* Video wrapper */
    .tragedy-video-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 24px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        border: 1px solid #334155;
        margin: 2.5rem 0;
    }

    .tragedy-video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    /* Media/Image Containers */
    .tragedy-media-box {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 24px;
        padding: 1.25rem;
        margin: 3.5rem 0;
        box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.3);
    }

    .tragedy-media-box img {
        width: 100%;
        height: auto;
        border-radius: 16px;
        display: block;
        max-height: 520px;
        object-fit: cover;
    }

    .tragedy-media-caption {
        font-size: 0.95rem;
        font-weight: 700;
        color: #94a3b8;
        text-align: center;
        margin-top: 1rem;
        display: block;
    }

    /* Staggered Content Sections */
    .tragedy-sub-heading {
        font-size: 1.85rem;
        font-weight: 700;
        color: #f8fafc;
        margin-top: 3.5rem;
        margin-bottom: 1.25rem;
        border-left: 5px solid #ef4444;
        padding-left: 1rem;
    }

    /* Gallery Grid */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
        margin: 2rem 0;
    }

    .gallery-item {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 0.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .gallery-item img {
        width: 100%;
        height: 140px;
        object-fit: cover;
        border-radius: 10px;
        display: block;
    }

    /* List styling */
    .tragedy-list {
        list-style: none;
        padding: 0;
        margin: 2rem 0;
    }

    .tragedy-list-item {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        transition: transform 0.2s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .tragedy-list-item:hover {
        transform: translateX(4px);
        border-color: #f87171;
    }

    .tragedy-list-item i {
        color: #ef4444;
        font-size: 1.2rem;
        margin-top: 0.25rem;
    }

    .tragedy-list-text {
        font-size: 1.05rem;
        color: #cbd5e1;
        font-weight: 600;
        line-height: 1.6;
    }

    /* Group grid */
    .conspirators-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin: 2.5rem 0;
    }

    .conspirator-card {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 20px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .conspirator-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: #2d1a1a;
        color: #ef4444;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .conspirator-name {
        font-size: 1.05rem;
        font-weight: 700;
        color: #f8fafc;
    }

    /* Stats Grid */
    .stats-block-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        margin: 3rem 0;
    }

    .stat-box-card {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 24px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .stat-number-label {
        font-size: 2.5rem;
        font-weight: 700;
        color: #ef4444;
        margin-bottom: 0.5rem;
    }

    .stat-text-label {
        font-size: 0.95rem;
        color: #94a3b8;
        font-weight: 600;
    }

    /* Info Alerts */
    .tragedy-alert-box {
        background: #2d1a1a;
        border: 1px solid #7f1d1d;
        border-radius: 24px;
        padding: 2rem;
        margin: 3.5rem 0;
        display: flex;
        gap: 1.25rem;
        align-items: flex-start;
    }

    .tragedy-alert-box i {
        color: #ef4444;
        font-size: 2rem;
        margin-top: 0.25rem;
    }

    .tragedy-alert-content h4 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #fca5a5;
        margin-bottom: 0.5rem;
    }

    .tragedy-alert-content p {
        font-size: 1.05rem;
        color: #fecaca;
        line-height: 1.7;
        font-weight: 500;
        margin-bottom: 0;
        text-align: justify;
    }

    /* Lightbox Modal */
    .tragedy-lightbox {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.95);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.3s ease;
        backdrop-filter: blur(8px);
    }
    
    .tragedy-lightbox.active {
        display: flex;
        opacity: 1;
    }
    
    .tragedy-lightbox img {
        max-width: 90%;
        max-height: 85%;
        border-radius: 12px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        transform: scale(0.95);
        transition: transform 0.3s ease;
    }
    
    .tragedy-lightbox.active img {
        transform: scale(1);
    }
    
    .tragedy-lightbox-close {
        position: absolute;
        top: 20px;
        right: 30px;
        color: #ffffff;
        font-size: 2.5rem;
        cursor: pointer;
        transition: color 0.2s ease;
    }
    
    .tragedy-lightbox-close:hover {
        color: #ef4444;
    }
    
    .tragedy-section img {
        cursor: pointer;
        transition: transform 0.2s ease, filter 0.2s ease;
    }
    
    .tragedy-section img:hover {
        transform: scale(1.015);
        filter: brightness(0.95);
    }

    /* Mobile Adaptability */
    @media (max-width: 767.98px) {
        .tragedy-heading {
            font-size: 1.85rem;
        }
        .tragedy-sub-heading {
            font-size: 1.5rem;
        }
        .tragedy-lead-card {
            padding: 2rem;
        }
        .conspirators-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        .stats-block-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'সোনাইমুড়ী ট্র্যাজেডি',
        'subtitle' => '১৪ মার্চ ২০১৬: নোয়াখালীর সোনাইমুড়িতে ধর্মব্যবসায়ী অপশক্তির বর্বরোচিত হামলা ও হত্যাকাণ্ড।',
        'badge_text' => 'ইতিহাস ও মানবাধিকার',
        'badge_icon' => 'fas fa-shield-alt'
    ])

    <section class="tragedy-section">
        <div class="container">
            <div class="tragedy-container">
                
                <!-- Lead Card -->
                <div class="tragedy-lead-card">
                    <h2 class="tragedy-heading">নোয়াখালীর সোনাইমুড়িতে পৈশাচিক হত্যাযজ্ঞ</h2>
                    
                    <!-- Intro YouTube Video -->
                    <div class="tragedy-video-container">
                        <iframe src="https://www.youtube.com/embed/qoRSRo2A6oQ" allowfullscreen></iframe>
                    </div>

                    <p class="tragedy-text">
                        নোয়াখালীর সোনাইমুড়ি উপজেলার পোরকরা গ্রাম। হেযবুত তওহীদের মাননীয় এমাম হোসাইন মোহাম্মদ সেলিমের গ্রামের বাড়ি এখানেই। ২০১৬ সালের ১৪ মার্চ এ বাড়িতেই সশস্ত্র হামলা চালিয়ে হেযবুত তওহীদের দুই সদস্যকে নৃশংসভাবে খুন করে ধর্মব্যবসায়ী একটি শ্রেণি। কী হয়েছিল সেদিন, কীভাবে ইতিহাসের জঘন্যতম এ হামলার পট রচনা হয়েছিল তা জানতে আমাদের পেছনে ফিরে যেতে হবে।
                    </p>
                    <p class="tragedy-text">
                        ১৯৯৯ সাল থেকেই পোরকরা গ্রামের বাসিন্দা নুরুল হক মেম্বারের পরিবারসহ আশেপাশের আরও আট-দশটি বাড়ির ৪০-৫০জন ব্যক্তি হেযবুত তওহীদ আন্দোলনের সঙ্গে সম্পৃক্ত রয়েছেন। হেযবুত তওহীদ হচ্ছে সম্পূর্ণ অরাজনৈতিক আন্দোলন। এই আন্দোলনের কর্মীরা ইসলামের নামে চলমান সকল প্রকার ধর্মব্যবসা, অপরাজনীতি ও জঙ্গিবাদের বিরুদ্ধে জনসাধারণকে সচেতন করে চলেছে।
                    </p>
                    <p class="tragedy-text">
                        যেহেতু এই আন্দোলন সকল প্রকার ধর্মব্যবসা, জঙ্গিবাদ ও অপরাজনীতির বিরুদ্ধে গণসচেতনতা সৃষ্টির লক্ষ্যে কাজ করে যাচ্ছে, তাই স্বভাবতই এই আন্দোলনের কর্মীদেরকে এক শ্রেণির ধর্মব্যবসায়ী ও জঙ্গিবাদী গোষ্ঠীর রোষানলে পড়তে হয়।
                    </p>
                </div>

                <!-- Media 1: Body -->
                <div class="tragedy-media-box">
                    <img src="{{ asset('uploads/tragedy/Sonaimuri_killing.jpg') }}" alt="হেযবুত তওহীদের দুই সদস্যের নিথর দেহ">
                    <span class="tragedy-media-caption"><i class="fas fa-camera me-1"></i> হেযবুত তওহীদের দুই সদস্যের নিথর দেহ।</span>
                </div>

                <!-- 2009 Attack -->
                <h3 class="tragedy-sub-heading">২০০৯ সালের হামলা</h3>
                <p class="tragedy-text">
                    ২০০৯ সালে নোয়াখালীর সোনাইমুড়িতে স্থানীয় ধর্মব্যবসায়ী গোষ্ঠীর ইন্ধনে হেযবুত তওহীদের বিরুদ্ধে ব্যাপক অপপ্রচার শুরু হয়। ধর্মভীরু মানুষকে বোঝানো হয় হেযবুত তওহীদ খ্রিস্টান হয়ে গেছে। মসজিদের খুৎবায়, ওয়াজে-মাহফিলে ধারাবাহিকভাবে বিভিন্ন মিথ্যা অপবাদ দিয়ে অপপ্রচার অব্যাহত রাখা হয়। গ্রামবাসীকে হেযবুত তওহীদের বিরুদ্ধে ভুলভাল তথ্য দিয়ে উত্তেজিত করা হতে থাকে। এভাবে ধর্মভীরু মানুষের ধর্মবিশ্বাসকে স্বার্থ হাসিলের মাধ্যম বানিয়ে নেয় ধর্মজীবী ষড়যন্ত্রকারীরা।
                </p>
                <p class="tragedy-text">
                    তারপর একদিন সুযোগ বুঝে হেযবুত তওহীদের সদস্যদের উপর হামলা চালায় তারা। আগুন দিয়ে জ্বালিয়ে দেওয়া হয় আটটি বাড়ি। লুণ্ঠিত হয় টাকা-পয়সা, আসবাবপত্র। পৈশাচিক ওই আক্রমণে সেদিন নারী-শিশুসহ হেযবুত তওহীদের অনেক সদস্য গুরুতর আহত হয়। পরিহাসের বিষয় হলো- বর্বরোচিত ওই হামলায় ভয়াবহ ক্ষতিগ্রস্ত হেযবুত তওহীদের সদস্যদের বিরুদ্ধেই মামলা দায়ের করে তাদেরকে জেলে ঢোকানো হয়।
                </p>
                <p class="tragedy-text">
                    আর আক্রমণকারী ষড়যন্ত্রকারীরা এলাকা দাপিয়ে বেড়ায় নির্বিঘ্নে। এরপর আদালত থেকে নির্দোষ প্রমাণিত হলে হেযবুত তওহীদের সদস্যরা পুনরায় তাদের আবাসভূমিতে ফিরে গিয়ে নতুনভাবে বাড়ি নির্মাণ করে। তারা অতীতের দুঃসহ স্মৃতিকে মুছে ফেলে গ্রামের আর দশজনের মতই স্বাভাবিক জীবনযাপন করতে থাকে। কিন্তু স্থানীয় ষড়যন্ত্রকারী জঙ্গিবাদী জামাত-হেফাজত-চরমোনাইদের অপপ্রচার বন্ধ থাকে নি একদিনের জন্যও। তারা সুযোগ খুঁজতে থাকে নতুন কোনো ইস্যুর সন্ধানে যাতে করে আবারও ২০০৯ সালের নৃশংসতার পুনরাবৃত্তি ঘটাতে পারে।
                </p>

                <!-- Media 2: Returning -->
                <div class="tragedy-media-box">
                    <img src="{{ asset('uploads/tragedy/Emam_noakhali1.jpg') }}" alt="মাননীয় এমামের জনসমাবেশ">
                    <span class="tragedy-media-caption"><i class="fas fa-camera me-1"></i> মাননীয় এমামের জনসমাবেশ</span>
                </div>

                <!-- Returning Home after 8 years -->
                <h3 class="tragedy-sub-heading">৮ বছর পর সোনাইমুড়িতে মাননীয় এমামের প্রত্যাবর্তন</h3>
                <p class="tragedy-text">
                    সেই ঘটনার পর নিজ জন্মস্থান নোয়াখালির সোনাইমুড়ি থেকে আরো ৭টি পরিবারকে সপরিবারে বের করে দেওয়া হয়েছিল মাননীয় এমামকে। ধর্মব্যবসায়ী শ্রেণিটি সাধারণ মানুষকে উত্তেজিত করে সত্যকে স্বীকৃতি দানকারী এই পরিবারগুলোকে খ্রিস্টান অপবাদ দিয়ে বিতাড়িত করে তাদের জন্মস্থান থেকে, জ্বালিয়ে ভস্ম করে দেয় তাদের বসতভিটা।
                </p>
                
                <!-- Media 3: Rally -->
                <div class="tragedy-media-box">
                    <img src="{{ asset('uploads/tragedy/Emam_noakhali2.jpg') }}" alt="আপামর জনতার অংশগ্রহণ">
                    <span class="tragedy-media-caption"><i class="fas fa-camera me-1"></i> এক যুগেরও বেশি সময় পর আপনজনদের কাছে পেয়ে আবেগে আপলুত হয়েছেন মাননীয় এমাম।</span>
                </div>

                <p class="tragedy-text">
                    সেই সোনাইমুড়িতে গত ২৪ ফেব্রুয়ারি ২০১৬ তারিখে তিনি প্রত্যাবর্তন করেন বিজয়ীর বেশে। হাজার হাজার জনতা তাকে বরণ করে নেয়। সেদিনের বৈরী আবহাওয়া, বৃষ্টিকে উপেক্ষা করে দীর্ঘ দুই ঘণ্টা মুগ্ধচিত্তে তার বক্তব্য শুনেছেন তারা। আর ওয়াদা করেছেন, সমাজের যাবতীয় মিথ্যা, অন্যায়, অবিচার, ধর্মের নামে বিরাজিত অধর্ম, ধর্মব্যবসা, অপরাজনীতির বিরুদ্ধে হেযবুত তাওহীদের সংগ্রামে সাথী হওয়ার।
                </p>
                <p class="tragedy-text">
                    তিনি ক্ষমা করেছেন সেই সরল-মনা মানুষগুলোকে যারা ষড়যন্ত্রকারীদের প্ররোচনায় তাকে ভিটেছাড়া করেছিল। হৃদয়ে এখনও বাজছে তার সেই প্রেমমাখা কথাগুলো, <em>“এটা প্রতিশোধের সময় নয়, এটা হিংসার সময় নয়, এটা একে অপরকে ভালোবেসে সমাজকে সুন্দর করে গড়ে তোলার সময়। কিন্তু যারা ষড়যন্ত্রকারী, যারা আজও মিথ্যার চাদরে সত্যকে আবৃত করে রাখার সর্বপ্রকার চেষ্টা করে যাচ্ছে, তাদেরও স্মরণে রাখা উচিত— সত্য যখন আসে তখন মিথ্যার ধ্বংস অনিবার্য হয়ে পড়ে।”</em>
                </p>

                <!-- Decision to Build Mosque -->
                <h3 class="tragedy-sub-heading">মসজিদ তৈরির সিদ্ধান্ত</h3>
                <p class="tragedy-text">
                    দীর্ঘদিন ধরে হেযবুত তওহীদের বিরুদ্ধে একটি অপপ্রচার চালানো হয় যে, আমরা নামাজ পড়ি না। তাদের এই কথাটি যে সবৈর্ব মিথ্যা, তা প্রমাণ করার জন্য মাননীয় এমাম সিদ্ধান্ত নেন তিনি তার বাড়ির আঙিণায় একটি মসজিদ নির্মাণ করবেন। এ লক্ষ্যে আশেপাশের কয়েকটি জেলা থেকে হেযবুত তওহীদের কিছু সদস্য নির্মাণকাজে অংশ নিতে সেখানে যান।
                </p>
                <p class="tragedy-text">
                    এলাকার গন্যমান্য লোকদের দাওয়াত দিয়ে মসজিদ নির্মাণকার্যের উদ্বোধনও করা হয়, দোয়া, মিলাদ, মিষ্টিমুখ করা হয়। আর এ ব্যাপারটিকেই ধর্মব্যবসায়ী শ্রেণিটি তাদের হাতিয়ার হিসেবে ব্যবহার করে। আমরা যেন সেখানে মসজিদ নির্মাণ না করতে পারি সেজন্য তারা সেখানে প্রচার করে যে, ‘হেযবুত তওহীদ খ্রিষ্টান, তারা সেখানে গির্জা নির্মাণ করছে’।
                </p>

                <!-- Grid of Foundation vs Destroyed -->
                <div class="row g-4 my-4">
                    <div class="col-md-6">
                        <div class="tragedy-media-box m-0 h-100">
                            <img src="{{ asset('uploads/tragedy/Mosque_build0.jpg') }}" alt="মসজিদের ভিত্তি প্রস্তর স্থাপন" class="h-100">
                            <span class="tragedy-media-caption"><i class="fas fa-camera me-1"></i> মসজিদের ভিত্তি প্রস্তর স্থাপন</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="tragedy-media-box m-0 h-100">
                            <img src="{{ asset('uploads/tragedy/Mosque_build2.jpg') }}" alt="নির্মাণাধীন মসজিদ ভেঙ্গে ফেলার পর" class="h-100">
                            <span class="tragedy-media-caption"><i class="fas fa-camera me-1"></i> নির্মাণাধীন মসজিদ ভেঙ্গে ফেলার পর</span>
                        </div>
                    </div>
                </div>

                <!-- Propagandas -->
                <h3 class="tragedy-sub-heading">সীমাহীন অপপ্রচার ও উস্কানি</h3>
                <p class="tragedy-text">
                    হামলার ঘটনার ২ দিন আগে “হেযবুত তওহীদ কুফরি সংগঠন” শীর্ষক একটি বেনামী উস্কানিমূলক হ্যান্ডবিল জনগণের মধ্যে, বাজারে বাজারে বিতরণ করা হয়। পাঞ্জাবি, টুпи পরা কয়েকটি যুবক মটর সাইকেলে করে চৌমুহনি থেকে এসে হ্যান্ডবিলটি জুমার আগে গ্রামের মসজিদগুলোতে দিয়ে গেছে। মসজিদের ইমামরা সেই হ্যান্ডবিল পড়ে পড়ে শুনিয়ে মুসল্লিদেরকে উত্তেজিত করেন। তারা অপপ্রচার চালাতে লাগল যে, হেযবুত তওহীদ হচ্ছে কুফরী সংগঠন, এরা গ্রামে গীর্জা নির্মাণ করতে চাচ্ছে। এদেরকে প্রতিরোধ করা ঈমানী দায়িত্ব এবং হত্যা করা ফরজ।
                </p>

                <!-- Media Handbills -->
                <div class="row g-4 my-4">
                    <div class="col-md-6">
                        <div class="tragedy-media-box m-0 h-100">
                            <img src="{{ asset('uploads/tragedy/Handbill1.jpg') }}" alt="উসকানিমূলক হ্যান্ডবিল ১" class="h-100">
                            <span class="tragedy-media-caption"><i class="fas fa-camera me-1"></i> উসকানিমূলক হ্যান্ডবিল ১</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="tragedy-media-box m-0 h-100">
                            <img src="{{ asset('uploads/tragedy/Handbill2.jpg') }}" alt="উসকানিমূলক হ্যান্ডবিল ২" class="h-100">
                            <span class="tragedy-media-caption"><i class="fas fa-camera me-1"></i> উসকানিমূলক হ্যান্ডবিল ২</span>
                        </div>
                    </div>
                </div>

                <!-- Media 4: Madrasa students -->
                <div class="tragedy-media-box">
                    <img src="{{ asset('uploads/tragedy/1620940_1782774971946487_5199541759375788594_n.jpg') }}" alt="উসকানিমূলক স্মারকলিপি প্রদান">
                    <span class="tragedy-media-caption"><i class="fas fa-camera me-1"></i> মাদ্রাসার ছাত্রদের ব্যবহার করে সাধারণ মানুষকে উসকে দেওয়ার চেষ্টা।</span>
                </div>

                <p class="tragedy-text">
                    নোয়াখালীর সোনাইমুড়িতে বর্বরোচিত এ হামলার পিছনে মদদ ও ইন্ধন যুগিয়েছে ইন্ডিপেন্ডেন্ট টেলিভিশনের তালাশ অনুষ্ঠানের একটি পর্ব। ধর্মব্যবসায়ী ও তাদের অনুসারীরা এই উদ্দেশ্যমূলক মিথ্যানির্ভর অনুষ্ঠানটিকে ষড়যন্ত্রের গুটি হিসাবে ব্যবহার করেছে। তারা প্রত্যেকের মোবাইলে মোবাইলে এ অনুষ্ঠানটি দিয়ে ধর্মপ্রাণ মানুষকে হামলার উস্কানি দিয়েছে।
                </p>

                <!-- Video response to Talash -->
                <h3 class="tragedy-sub-heading">তালাশের মিথ্যাচারের জবাব</h3>
                <div class="tragedy-video-container">
                    <iframe src="https://www.youtube.com/embed/x-42ZGssca4" allowfullscreen></iframe>
                </div>

                <p class="tragedy-text">
                    Facebook-এ ব্যপক অপপ্রচার চালিয়ে হামলার দিন মানুষকে সংগঠিত করা হয়। সিক্রেট গ্রুপ খুলে হামলার পরিকল্পনা, অস্ত্রশস্ত্রসহ নানা রকম হামলার সরঞ্জাম একত্রিত করেছে। জেহাদের নামে সাধারণ মানুষদের খ্রিষ্টান মারার জন্য আহ্বান জানানো হয়েছে।
                </p>

                <!-- Media 5: Facebook screenshots Gallery -->
                <h3 class="tragedy-sub-heading">ফেসবুকে অপপ্রচারের কিছু নমুনা</h3>
                <div class="gallery-grid">
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/Sk-Nural-Karim-Sultan.jpg') }}" alt="fb"></div>
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/Arman-Khondokar.jpg') }}" alt="fb"></div>
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/Awlad-Mia.jpg') }}" alt="fb"></div>
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/Basher-Kella-2.jpg') }}" alt="fb"></div>
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/Daud-Alam.jpg') }}" alt="fb"></div>
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/Edris-Islam.jpg') }}" alt="fb"></div>
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/Faysal-Kabir-Rashed.jpg') }}" alt="fb"></div>
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/IS-Ca.jpg') }}" alt="fb"></div>
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/Islami-Shashon-Andolon.jpg') }}" alt="fb"></div>
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/Mahmud-Hassan.jpg') }}" alt="fb"></div>
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/Md-Zihade.jpg') }}" alt="fb"></div>
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/Murad-Chawdory.jpg') }}" alt="fb"></div>
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/Qawmi-Online-Media.jpg') }}" alt="fb"></div>
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/Rasel-Said.jpg') }}" alt="fb"></div>
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/shapla.jpg') }}" alt="fb"></div>
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/Shibir-Page.jpg') }}" alt="fb"></div>
                </div>

                <div class="tragedy-alert-box">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div class="tragedy-alert-content">
                        <h4>প্রশাসনের গাফিলতি</h4>
                        <p>
                            ষড়যন্ত্রের আভাস পেয়ে আমরা স্থানীয় police প্রশাসনকে বিষয়টি অবহিত করেছিলাম। স্বশরীরে গিয়ে ওসি সাহেব ও এসপি মহোদয়কে জানাই। স্বরাষ্ট্রমন্ত্রী ও প্রধানমন্ত্রীর কার্যালয়কেও মৌখিকভাবে জানানো হয়েছিল। তারা সবাই আশ্বাস দিলেও দুর্ভাগ্যক্রমে প্রশাসন বিষয়টির যথাযথ গুরুত্ব উপলব্ধি করতে ব্যর্থ হয়।
                        </p>
                    </div>
                </div>

                <!-- Day of attack -->
                <h3 class="tragedy-sub-heading">১৪ মার্চ ২০১৬: হামলার দিন</h3>
                <p class="tragedy-text">
                    রৌদ্রজ্জ্বল সকালে হাজার হাজার মানুষে গ্রাম ভরে গেল। দূর দূরান্ত থেকে বাস ভর্তি করে মানুষ এসে হাজির হলো পোরকরায়। সবার মুখে স্লোগান— ‘গীর্জা ভাঙ্গো, খৃষ্টান মার।’ মসজিদের মাইকেও একই ঘোষণা ভেসে এল। সেই দাঙ্গা সৃষ্টিকারী আলেম ও মাদ্রাসার শিক্ষকরা মাদ্রাসার ছাত্রদের নিয়ে স্মারকলিপি দেওয়ার নাম করে ইউএনও অফিসের দিকে মিছিল বের করে।
                </p>
                <p class="tragedy-text">
                    মিছিলটি police বাধা মোকাবেলা করে আমাদের সদস্যদের ঘরবাড়ি অভিমুখে রওনা দেয়। তারা মসজিদের মাইকে ঘোষণা দেয় যে, হেযবুত তওহীদের সদস্যরা নাকি তাদের উপর হামলা করেছে। এই মিথ্যা শুনে হাজার হাজার মানুষ আরও ক্ষিপ্ত হয়ে নিরাপরাধ কর্মীদের উপর হামলে পড়ে।
                </p>
                <p class="tragedy-text">
                    নির্মাণাধীন মসজিদটিকে তারা ভেঙ্গে গুড়িয়ে দেয় এবং সেখানে থাকা ইট নিক্ষেপ করে আমাদের সদস্যদের গুরুতর আহত করে। এরপর পুলিশের সামনেই সন্ত্রাসীরা বাড়ির আঙিনার ভিতরে ঢুকে আমাদের দুজনকে প্রথমে পাথর মেরে মাটিতে ফেলে দেয়। তারপর তাদের বুকের উপর চেপে বসে হাতপায়ের রগ কেটে দেয়। এরপর গরু জবাই করার ছুরি দিয়ে গলা কেটে নৃশংসভাবে হত্যা করে। বর্বরতার এখানেই শেষ নয়, এরপর তাদের শরীরে পেট্রল ঢেলে আগুন ধরিয়ে দেওয়া হয়, ফলে দেহ দুটি সম্পূর্ণ পুড়ে অঙ্গার হয়ে যায়। বাকিদের হাত কেটে ফেলা হয় ও পা ভেঙে দেওয়া হয়।
                </p>

                <!-- Media Gallery: Newspapers -->
                <h3 class="tragedy-sub-heading">গণমাধ্যমে প্রকাশিত সংবাদসমূহ</h3>
                <div class="gallery-grid">
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/BBC-News-About-Noakhali.jpg') }}" alt="BBC News"></div>
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/Cheanel-i-News-About-Noakhali.jpg') }}" alt="Channel i News"></div>
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/Nayadiganta.jpg') }}" alt="Naya Diganta"></div>
                    <div class="gallery-item"><img src="{{ asset('uploads/tragedy/Shomokal.jpg') }}" alt="Shomokal"></div>
                </div>

                <!-- Conspirators -->
                <h3 class="tragedy-sub-heading">হামলার নেপথ্যে যারা ছিল</h3>
                <p class="tragedy-text">
                    পোরকরায় এই বর্বরোচিত ও পৈশাচিক হামলার নেপথ্যে বেশ কয়েকটি সক্রিয় সংগঠন ও স্বার্থান্বেষী গোষ্ঠী জড়িত ছিল:
                </p>
                <div class="conspirators-grid">
                    <div class="conspirator-card">
                        <div class="conspirator-icon"><i class="fas fa-user-secret"></i></div>
                        <span class="conspirator-name">স্থানীয় জামায়াত-শিবির গোষ্ঠী</span>
                    </div>
                    <div class="conspirator-card">
                        <div class="conspirator-icon"><i class="fas fa-user-secret"></i></div>
                        <span class="conspirator-name">স্থানীয় হেফাজত-ই-ইসলাম কর্মী</span>
                    </div>
                    <div class="conspirator-card">
                        <div class="conspirator-icon"><i class="fas fa-user-secret"></i></div>
                        <span class="conspirator-name">ইসলামী শাসনতন্ত্র আন্দোলন সমর্থক</span>
                    </div>
                    <div class="conspirator-card">
                        <div class="conspirator-icon"><i class="fas fa-user-secret"></i></div>
                        <span class="conspirator-name">উস্কানিদাতা মসজিদের একাংশ আলেম</span>
                    </div>
                </div>

                <!-- Press Conference and Demands -->
                <h3 class="tragedy-sub-heading">আমাদের প্রতিবাদ ও সংবাদ সম্মেলন</h3>
                <p class="tragedy-text">
                    এই ঘটনার পরদিন ঢাকা রিপোর্টার্স ইউনিটিতে সংবাদ সম্মেলন করেন হেযবুত তওহীদের মাননীয় এমাম হোসাইন মোহাম্মদ সেলিম ও মুখপাত্র রূফায়দাহ পন্নী। সংবাদ সম্মেলন থেকে সরকারের কাছে নিম্নলিখিত ৫টি দাবি উত্থাপন করা হয়:
                </p>

                <!-- Media Gallery: Press Conference -->
                <div class="gallery-grid">
                    <div class="gallery-item" style="grid-column: span 2;"><img src="{{ asset('uploads/tragedy/press-conference.jpg') }}" alt="press" style="height:200px;"></div>
                    <div class="gallery-item" style="grid-column: span 2;"><img src="{{ asset('uploads/tragedy/dru2.jpg') }}" alt="dru" style="height:200px;"></div>
                </div>
                <div class="tragedy-media-box my-3">
                    <img src="{{ asset('uploads/tragedy/lazy_2.webp') }}" alt="আমাদের সংবাদ সম্মেলন">
                    <span class="tragedy-media-caption"><i class="fas fa-camera me-1"></i> সংবাদ সম্মেলনে উগ্রবাদের বিরুদ্ধে বক্তব্য রাখছেন মাননীয় এমাম।</span>
                </div>

                <ul class="tragedy-list">
                    <li class="tragedy-list-item">
                        <i class="fas fa-shield-alt"></i>
                        <span class="tragedy-list-text">আসামিদের দ্রুত গ্রেফতার করে আইনের আওতায় এনে দ্রুত বিচার ট্রাইব্যুনালে শাস্তির ব্যবস্থা করা হোক।</span>
                    </li>
                    <li class="tragedy-list-item">
                        <i class="fas fa-shield-alt"></i>
                        <span class="tragedy-list-text">মিথ্যা ও উস্কানিমূলক হ্যান্ডবিলটি কোথায় এবং কাদের দ্বারা রচিত ও বিলি হয়েছিল তা সনাক্ত করে বিচারের আওতায় আনা হোক।</span>
                    </li>
                    <li class="tragedy-list-item">
                        <i class="fas fa-shield-alt"></i>
                        <span class="tragedy-list-text">আমাদের নির্দোষ আহত সদস্যদের উদ্ধার করে নিয়ে যাওয়ার পর তাদের বিরুদ্ধেই ষড়যন্ত্রমূলকভাবে দায়েরকৃত মিথ্যা মামলা প্রত্যাহার করা হোক।</span>
                    </li>
                    <li class="tragedy-list-item">
                        <i class="fas fa-shield-alt"></i>
                        <span class="tragedy-list-text">সামাজিক যোগাযোগমাধ্যমসহ বিভিন্নভাবে ক্রমাগত প্রাণনাশের হুমকিদাতাদের বিরুদ্ধে দ্রুত আইনী পদক্ষেপ গ্রহণ করা হোক।</span>
                    </li>
                    <li class="tragedy-list-item">
                        <i class="fas fa-shield-alt"></i>
                        <span class="tragedy-list-text">উক্ত ঘটনায় ভস্মীভূত ঘরসমূহ পুনর্নিমাণ ও ক্ষতিগ্রস্ত হেযবুত তওহীদ সদস্যদের পর্যাপ্ত ক্ষতিপূরণ ও পুনর্বাসনের ব্যবস্থা গ্রহণ করা হোক।</span>
                    </li>
                </ul>

                <!-- Stats and cases -->
                <h3 class="tragedy-sub-heading">মামলা ও আইনি জটিলতা</h3>
                <p class="tragedy-text">
                    এই বর্বরোচিত ঘটনার সুষ্ঠু বিচারের লক্ষ্যে হেযবুত তওহীদের পক্ষ থেকে দায়েরকৃত মামলা এবং আসামীদের বর্তমান অবস্থা নিম্নরূপ:
                </p>
                <div class="stats-block-grid">
                    <div class="stat-box-card">
                        <div class="stat-number-label">৫টি</div>
                        <div class="stat-text-label">মোট দায়েরকৃত মামলা</div>
                    </div>
                    <div class="stat-box-card">
                        <div class="stat-number-label">৪৯৫ জন</div>
                        <div class="stat-text-label">এজাহারে অভিযুক্ত আসামী</div>
                    </div>
                    <div class="stat-box-card">
                        <div class="stat-number-label">৮৩ জন</div>
                        <div class="stat-text-label">প্রধান চিহ্নিত আসামী</div>
                    </div>
                </div>

                <!-- Current Situation -->
                <h3 class="tragedy-sub-heading">বর্তমান পরিস্থিতি ও বিচারহীনতা</h3>
                <p class="tragedy-text">
                    ১. এজাহারে উল্লেখিত মোট ৮৩ জন আসামির মধ্য থেকে মাত্র অল্প কয়েকজনকে আজ পর্যন্ত আইনের আওতায় আনা হয়েছে। নুরুল আলম মুন্সী, জহিরসহ বহু চিহ্নিত আসামিকে এখনও গ্রেফতারই করা হয় নি। গ্রেফতারকৃত আসামিরাও সহজে জামিন পেয়ে এলাকায় বীরদর্পে ঘুরে বেড়াচ্ছে এবং হুমকি-ধামকি অব্যাহত রেখেছে।
                </p>
                <p class="tragedy-text">
                    ২. দুঃখজনকভাবে, কোনো একজন আসামীকেও আজ পর্যন্ত রিমান্ডে নিয়ে জিজ্ঞাসাবাদ করা হয় নি যে— কারা প্রকাশ্য দিবালোকে শত শত মানুষের সামনে গলায় ছুরি চালিয়ে দুই সদস্যকে জবাই করল, কারা তাদের চোখ উপড়ে নিল এবং কারা পেট্রল ঢেলে অগ্নিসংযোগের নেতৃত্ব দিল।
                </p>
                <p class="tragedy-text">
                    ৩. ফেসবুকে প্রতিনিয়ত হুমকি-ধামকি ও অপপ্রচার চলছে। আক্রান্তদের অনেকেই এখনও পোরকরা গ্রামে নিজেদের বসতবাড়িতে ফিরতে পারছেন না সন্ত্রাসীদের ভয়ে। বিচারহীনতার এই সংস্কৃতি ভুক্তگونه পরিবারগুলোকে এক চরম নিরাপত্তাহীনতায় ঠেলে দিয়েছে।
                </p>

                <!-- Media 10: Concluding Speech -->
                <div class="tragedy-media-box">
                    <img src="{{ asset('uploads/tragedy/lazy_3.webp') }}" alt="মাননীয় এমামের ভাষণ">
                    <span class="tragedy-media-caption"><i class="fas fa-camera me-1"></i> ষড়যন্ত্রের বিরুদ্ধে বজ্রকণ্ঠে সত্য প্রচার অব্যাহত রাখার প্রত্যয়।</span>
                </div>

            </div>
        </div>
    </section>

    <!-- Lightbox Modal HTML -->
    <div class="tragedy-lightbox" id="tragedyLightbox">
        <span class="tragedy-lightbox-close" id="lightboxClose">&times;</span>
        <img id="lightboxImg" src="" alt="Zoomed Image">
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lightbox = document.getElementById('tragedyLightbox');
        const lightboxImg = document.getElementById('lightboxImg');
        const lightboxClose = document.getElementById('lightboxClose');
        const images = document.querySelectorAll('.tragedy-section img');

        images.forEach(img => {
            img.addEventListener('click', function () {
                lightboxImg.src = this.src;
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden'; // Disable background scrolling
            });
        });

        function closeLightbox() {
            lightbox.classList.remove('active');
            document.body.style.overflow = ''; // Re-enable background scrolling
        }

        lightboxClose.addEventListener('click', closeLightbox);
        lightbox.addEventListener('click', function (e) {
            if (e.target !== lightboxImg) {
                closeLightbox();
            }
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });
    });
</script>
@endpush
