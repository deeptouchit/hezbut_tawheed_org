@extends('theme::layouts.app')

@section('title', $page->title . ' - হেযবুত তওহীদ')

@if(!empty($page->meta_description))
    @section('meta_description', $page->meta_description)
@endif

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => $page->title,
        'subtitle' => 'হেযবুত তওহীদ সম্পর্কে যাদের জানার আকাঙ্ক্ষা আছে, তাদের জন্য অতি সংক্ষেপে হেযবুত তওহীদ সম্পর্কে তুলে ধরা হলো।',
        'badge_text' => 'পরিচিতি',
        'badge_icon' => 'fas fa-info-circle'
    ])

    <!-- Main About Content Area -->
    <section class="py-5 page-body bg-light bg-opacity-50">
        <div class="container">

            <!-- 1. At a Glance -->
            <div class="row align-items-center mb-5 pb-3">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="about-img-wrap">
                        @php
                            $glanceImg = '/uploads/about/at-a-glance.jpg';
                            $glanceImgExists = file_exists(public_path($glanceImg));
                        @endphp
                        @if($glanceImgExists)
                            <img src="{{ asset($glanceImg) }}" alt="এক নজরে হেযবুত তওহীদ" loading="lazy" />
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center text-white py-5 px-4 text-center h-100 rounded-3" style="background: linear-gradient(135deg, #006A4E 0%, #004b37 100%);">
                                <i class="fas fa-eye fa-4x mb-3" style="color: #fbbf24;"></i>
                                <h4 class="fw-bold mb-2">মানবতার কল্যাণে নিবেদিত</h4>
                                <p class="small opacity-75">হেযবুত তওহীদ একটি সম্পূর্ণ অরাজনৈতিক ধর্মীয় সংস্কারমূলক অসাম্প্রদায়িক আন্দোলন</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <div class="d-inline-flex align-items-center gap-2 mb-3 bg-success bg-opacity-10 text-success px-3 py-1.5 rounded-pill">
                        <i class="fas fa-eye" style="font-size: 0.85rem;"></i>
                        <span class="fw-bold" style="font-size: 0.8rem; letter-spacing: 0.5px;">এক নজরে হেযবুত তওহীদ</span>
                    </div>

                    <p class="lead fw-semibold text-success mb-4" style="font-size: 1.12rem; line-height: 1.6; border-left: 3px solid var(--primary-green); padding-left: 1rem;">
                        হেযবুত তওহীদ সম্পূর্ণ অরাজনৈতিক ধর্মীয় সংস্কারমূলক আন্দোলন যার মূল কাজই হলো মানবজাতিকে ন্যায়ের পক্ষে ঐক্যবদ্ধ করা এবং মানবজাতির অশান্তির মূল কারণ দাজ্জালের অনুসরণ না করে সমগ্র পৃথিবীতে সৃষ্টিকর্তার সার্বভৌমত্ব প্রতিষ্ঠা করা।
                    </p>
                    <p class="text-secondary" style="font-size: 0.98rem; line-height: 1.7;">
                        পুরো মানবজাতিকে আল্লাহর তওহীদের ভিত্তিতে অর্থাৎ সত্য ও ন্যায়ের পক্ষে, সকল অন্যায়ের বিরুদ্ধে ঐক্যবদ্ধ করাই হেযবুত তওহীদের মূল লক্ষ্য। মানবজীবনে সঠিক পথ, হেদায়াহ (Right Direction) ও সত্য জীবনব্যবস্থা প্রতিষ্ঠিত হলে সমস্ত মানবজাতি অন্যায় ও অবিচার থেকে মুক্তি পাবে। পৃথিবীতে প্রতিষ্ঠিত হবে অনাবিল শান্তি। সেই শান্তিময় পৃথিবী প্রতিষ্ঠার লক্ষ্যকে সামনে নিয়ে সংগ্রাম করে যাচ্ছে হেযবুত তওহীদের সদস্য-সদস্যারা।
                    </p>
                </div>
            </div>

            <!-- 2. Founder & Current Leader -->
            @php
                $founder = \App\Models\Leader::where('is_founder', true)->first();
                $currentLeader = \App\Models\Leader::where('is_founder', false)->orderBy('sort_order', 'asc')->first();
            @endphp
            <div class="row g-4 mb-5 pt-4">
                <!-- Leader 1 (Founder) -->
                <div class="col-lg-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-lift" style="transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                        <div class="row g-0 align-items-stretch h-100">
                            <div class="col-md-6 position-relative">
                                <div class="leader-img-wrapper h-100 w-100" style="min-height: 250px; overflow: hidden; position: relative;">
                                    <img src="{{ asset('uploads/leaders/founder_panni.png') }}" alt="জনাব মোহাম্মদ বায়াজীদ খান পন্নী" class="w-100 h-100 object-fit-cover" style="object-position: center 8%; position: absolute; top: 0; left: 0; height: 100% !important; width: 100% !important; margin: 0 !important; border-radius: 0 !important; box-shadow: none !important; max-width: none !important;" loading="lazy">
                                </div>
                            </div>
                            <div class="col-md-6 d-flex flex-column justify-content-between p-4 bg-white">
                                <div>
                                    <div class="mb-2 text-center">
                                        <span class="px-3 py-1 rounded-pill fw-bold text-success" style="font-size: 11px; background-color: rgba(0, 106, 78, 0.08); display: inline-block; border: 1px solid rgba(0, 106, 78, 0.12);">
                                            <i class="fas fa-award me-1" style="color: #d97706;"></i> প্রতিষ্ঠাতা এমামুযযামান
                                        </span>
                                    </div>
                                    <h6 class="fw-bold mb-2 text-dark text-center" style="white-space: nowrap;">জনাব মোহাম্মদ বায়াজীদ খান পন্নী</h6>
                                    <p class="text-secondary mb-0" style="font-size: 0.88rem; line-height: 1.6; text-align: justify;">
                                        টাঙ্গাইলের ঐতিহ্যবাহী পন্নী পরিবারে জন্ম নেওয়া জনাব মোহাম্মদ বায়াজীদ খান পন্নী ছিলেন একাধারে সমাজ সংস্কারক, লেখক, চিন্তাবিদ ও সত্যের সন্ধানী এক মহান ব্যক্তিত্ব। ১৯৯৬ সালে তিনি হেযবুত তওহীদ আন্দোলনের সূচনা করেন।
                                    </p>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ $founder ? route('leadership.show', $founder->slug) : route('about') . '#founder' }}" class="btn text-white w-100 fw-bold d-flex align-items-center justify-content-center gap-2" style="background-color: #006A4E; border-radius: 8px; padding: 10px 16px; font-size: 13.5px; transition: all 0.2s ease; border: none; ">
                                        <span>বিস্তারিত জীবনী</span>
                                        <i class="fas fa-arrow-right" style="font-size: 11px;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Leader 2 (Current) -->
                <div class="col-lg-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-lift" style="transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                        <div class="row g-0 align-items-stretch h-100">
                            <div class="col-md-6 position-relative">
                                <div class="leader-img-wrapper h-100 w-100" style="min-height: 250px; overflow: hidden; position: relative;">
                                    <img src="{{ asset('uploads/leaders/emam_selim.png') }}" alt="জনাব হোসাইন মোহাম্মদ সেলিম" class="w-100 h-100 object-fit-cover" style="object-position: center 8%; position: absolute; top: 0; left: 0; height: 100% !important; width: 100% !important; margin: 0 !important; border-radius: 0 !important; box-shadow: none !important; max-width: none !important;" loading="lazy">
                                </div>
                            </div>
                            <div class="col-md-6 d-flex flex-column justify-content-between p-4 bg-white">
                                <div>
                                    <div class="mb-2 text-center">
                                        <span class="px-3 py-1 rounded-pill fw-bold text-success" style="font-size: 11px; background-color: rgba(0, 106, 78, 0.08); display: inline-block; border: 1px solid rgba(0, 106, 78, 0.12);">
                                            <i class="fas fa-user-shield me-1" style="color: #006A4E;"></i> বর্তমান এমাম
                                        </span>
                                    </div>
                                    <h6 class="fw-bold mb-2 text-dark text-center text-nowrap">জনাব হোসাইন মোহাম্মদ সেলিম</h6>
                                    <p class="text-secondary mb-0" style="font-size: 0.88rem; line-height: 1.6; text-align: justify;">
                                        ২০১২ সালের ১৬ জানুয়ারি জনাব মোহাম্মদ বায়াজীদ খান পন্নীর ইন্তেকালের পর হেযবুত তওহীদের এমামের দায়িত্ব গ্রহণ করেন জনাব হোসাইন মোহাম্মদ সেলিম। তাঁর গতিশীল নেতৃত্বে আন্দোলনটি দেশব্যাপী এক বিশাল সংস্কারমূলক আলোড়ন তৈরি করেছে।
                                    </p>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ $currentLeader ? route('leadership.show', $currentLeader->slug) : route('about') . '#chairman' }}" class="btn text-white w-100 fw-bold d-flex align-items-center justify-content-center gap-2" style="background-color: #006A4E; border-radius: 8px; padding: 10px 16px; font-size: 13.5px; transition: all 0.2s ease; border: none; ">
                                        <span>বিস্তারিত জীবনী</span>
                                        <i class="fas fa-arrow-right" style="font-size: 11px;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Principles & Program -->
            <div class="row g-4 mb-5">
                <div class="col-lg-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 py-3 py-md-4 px-4 px-md-5 hover-lift" style="background: #f8fafc; border-top: 4px solid #006A4E !important; border: 1px solid #e2e8f0; transition: all 0.3s ease;">
                        <h4 class="fw-bold text-success mb-4" style="font-size: 1.5rem;"><i class="fas fa-scroll me-2" style="color: #006A4E;"></i> মূলনীতি</h4>
                        <ul class="list-unstyled ps-0" style="line-height: 1.8; font-size: 0.95rem; color: #334155;">
                            <li class="mb-3 d-flex align-items-start gap-2">
                                <span class="text-success mt-0.5"><i class="fas fa-play" style="font-size: 0.8rem; color: #006A4E;"></i></span>
                                <span class="fw-medium">হেযবুত তওহীদ চেষ্টা করে আল্লাহর রসুলের প্রতিটি পদক্ষেপকে অনুসরণ করতে।</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start gap-2">
                                <span class="text-success mt-0.5"><i class="fas fa-play" style="font-size: 0.8rem; color: #006A4E;"></i></span>
                                <span class="fw-medium">হেযবুত তওহীদের কোনো গোপন কার্যক্রম নেই, সবকিছু হবে প্রকাশ্য এবং দিনের আলোয়।</span>
                            </li>
                        </ul>
                        <ol class="list-unstyled ps-0" style="line-height: 1.8; font-size: 0.95rem; color: #334155;">
                            <li class="mb-3 d-flex align-items-start gap-2">
                                <span class="text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mt-1" style="width: 20px; height: 20px; font-size: 0.75rem; flex-shrink: 0; background-color: #d97706;">১</span>
                                <span class="fw-semibold">ঐক্যবদ্ধ হও।</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start gap-2">
                                <span class="text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mt-1" style="width: 20px; height: 20px; font-size: 0.75rem; flex-shrink: 0; background-color: #d97706;">২</span>
                                <span class="fw-semibold">(নেতার আদেশ) শোন।</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start gap-2">
                                <span class="text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mt-1" style="width: 20px; height: 20px; font-size: 0.75rem; flex-shrink: 0; background-color: #d97706;">৩</span>
                                <span class="fw-semibold">(নেতার ঐ আদেশ) পালন করো।</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start gap-2">
                                <span class="text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mt-1" style="width: 20px; height: 20px; font-size: 0.75rem; flex-shrink: 0; background-color: #d97706;">৪</span>
                                <span class="fw-semibold">হেযরত (যাবতীয় অন্যায়ের সঙ্গে সম্পর্কত্যাগ) করো।</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start gap-2">
                                <span class="text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mt-1" style="width: 20px; height: 20px; font-size: 0.75rem; flex-shrink: 0; background-color: #d97706;">৫</span>
                                <span class="fw-semibold">এই দীনুল হক (ন্যায়, সত্য) পৃথিবীতে প্রতিষ্ঠার জন্য সর্বাত্মক চেষ্টা, প্রচেষ্টা।</span>
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 py-3 py-md-4 px-4 px-md-5 hover-lift" style="background: #f8fafc; border-top: 4px solid #0284c7 !important; border: 1px solid #e2e8f0; transition: all 0.3s ease;">
                        <h4 class="fw-bold text-success mb-4" style="font-size: 1.5rem;"><i class="fas fa-tasks me-2" style="color: #0284c7;"></i> কর্মসূচি</h4>
                        <p class="fw-semibold mb-3" style="font-size: 0.95rem; color: #1e293b;">মানুষকে সৎপথে আহ্বান জানানোর জন্য পাঁচ দফা কার্যক্রম:</p>
                        <ol class="list-unstyled ps-0" style="line-height: 1.8; font-size: 0.95rem; color: #334155;">
                            <li class="mb-3 d-flex align-items-start gap-2">
                                <span class="text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mt-1" style="width: 20px; height: 20px; font-size: 0.75rem; flex-shrink: 0; background-color: #d97706;">১</span>
                                <span class="fw-semibold">মানুষকে তওহীদের দাওয়াত দেওয়া।</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start gap-2">
                                <span class="text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mt-1" style="width: 20px; height: 20px; font-size: 0.75rem; flex-shrink: 0; background-color: #d97706;">২</span>
                                <span class="fw-semibold">জুলুম ও অবিচারের বিরুদ্ধে প্রতিবাদ জানানো।</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start gap-2">
                                <span class="text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mt-1" style="width: 20px; height: 20px; font-size: 0.75rem; flex-shrink: 0; background-color: #d97706;">৩</span>
                                <span class="fw-semibold">সমাজে সুবিচার ও ন্যায় প্রতিষ্ঠার চেষ্টা করা।</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start gap-2">
                                <span class="text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mt-1" style="width: 20px; height: 20px; font-size: 0.75rem; flex-shrink: 0; background-color: #d97706;">৪</span>
                                <span class="fw-semibold">অসাম্প্রদায়িক সম্প্রীতি সৃষ্টি করা।</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start gap-2">
                                <span class="text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mt-1" style="width: 20px; height: 20px; font-size: 0.75rem; flex-shrink: 0; background-color: #d97706;">৫</span>
                                <span class="fw-semibold">আত্মশুদ্ধি ও চরিত্র গঠনে মনোনিবেশ করা।</span>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- 4. Process -->
            <div class="row align-items-center mb-5 pt-4">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="about-img-wrap">
                        @php
                            $processImg = '/uploads/about/process.jpg';
                            $processImgExists = file_exists(public_path($processImg));
                        @endphp
                        @if($processImgExists)
                            <img src="{{ asset($processImg) }}" alt="কর্মপ্রক্রিয়া" loading="lazy" />
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center text-white py-5 px-4 text-center h-100 rounded-3" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                                <i class="fas fa-cogs fa-3x mb-3" style="color: #fbbf24;"></i>
                                <h5 class="fw-bold mb-1">সক্রিয় কর্মপ্রক্রিয়া</h5>
                                <p class="small opacity-75">সারাদেশে হ্যান্ডবিল, বই, সুধী সমাবেশ ও সেমিনারের মাধ্যমে প্রচার</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <div class="d-inline-flex align-items-center gap-2 mb-3 bg-success bg-opacity-10 text-success px-3 py-1.5 rounded-pill border border-success border-opacity-10">
                        <i class="fas fa-cogs" style="font-size: 0.8rem; color: #006A4E;"></i>
                        <span class="fw-bold" style="font-size: 0.78rem; letter-spacing: 0.5px; color: #006A4E;">কর্মপ্রক্রিয়া</span>
                    </div>

                    <p class="text-secondary leading-relaxed text-justify mb-4" style="line-height: 1.8; font-size: 0.96rem; text-align: justify;">
                        হেযবুত তওহীদ রাষ্ট্রীয় আইনকে পূর্ণরূপে মান্য করে গত ২১ বছর ধরে আন্দোলন পরিচালনা করে আসছে। মানবজাতিকে স্রষ্টার সার্বভৌমত্বের দিকে আহ্বান করার জন্য হেযবুত তওহীদ মাননীয় এমামুয্যামানের বক্তব্য ও লেখা সম্বলিত হ্যান্ডবিল, বই, পত্রিকা, প্রামাণ্যচিত্র ইত্যাদি সর্বশ্রেণি মানুষের কাছে পেঁছানোর জন্য সর্বাত্মক চেষ্টা করে থাকে। এরই অংশ হিসাবে বাসে, ট্রেনে, লঞ্চে, রাস্তাঘাটে এই প্রকাশনা সামগ্রীগুলি বিক্রয়, বই মেলায় স্টল গ্রহণ, শিল্পকলা একাডেমী, পৌর মিলনায়তন, জাতীয় প্রেসক্লাব, পাবলিক লাইব্রেরির সেমিনার কক্ষ, ঢাকা বিশ্ববিদ্যালয়ের সিনেট হল, জাতীয় যাদুঘরের সেমিনার কক্ষসহ বাংলাদেশ সরকারের মন্ত্রী-এমপিদের উপস্থিতিতে, সকল ধর্মের সম্মানিত ব্যক্তি ও ধর্মগুরুদের নিয়ে মতবিনিময়ের মাধ্যমে, এমনকি আইনশৃঙ্খলা বাহিনীর উর্ধ্বতন কর্মকর্তাদের সাথে দেখা করে আমাদের সকল কার্যক্রম সম্পর্কে নিয়মিতভাবে অবহিত করে থাকি এবং প্রকাশনাসমূহ দিয়ে আমাদের বক্তব্য সম্পর্কে জানিয়ে থাকি।
                    </p>

                </div>
            </div>

            <!-- 5. Training -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                    <div class="about-img-wrap">
                        @php
                            $trainingImg = '/uploads/about/training.jpg';
                            $trainingImgExists = file_exists(public_path($trainingImg));
                        @endphp
                        @if($trainingImgExists)
                            <img src="{{ asset($trainingImg) }}" alt="প্রশিক্ষণ" loading="lazy" />
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center text-white py-5 px-4 text-center h-100 rounded-3" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                                <i class="fas fa-dumbbell fa-3x mb-3" style="color: #fbbf24;"></i>
                                <h5 class="fw-bold mb-1">শরীরচর্চা ও আত্মিক প্রশিক্ষণ</h5>
                                <p class="small opacity-75">সঠিক সালাহ অনুশীলন এবং শরীরচর্চামূলক খেলাধুলার সংমিশ্রণ</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 pe-lg-5 order-lg-1">
                    <div class="d-inline-flex align-items-center gap-2 mb-3 bg-success bg-opacity-10 text-success px-3 py-1.5 rounded-pill border border-success border-opacity-10">
                        <i class="fas fa-dumbbell" style="font-size: 0.8rem; color: #006A4E;"></i>
                        <span class="fw-bold" style="font-size: 0.78rem; letter-spacing: 0.5px; color: #006A4E;">প্রশিক্ষণ</span>
                    </div>
                    <p class="text-secondary leading-relaxed" style="line-height: 1.8; font-size: 0.96rem;">
                       মানবতার মুক্তির লক্ষ্যে আল্লাহর সত্যদীন প্রতিষ্ঠার জন্য নিঃস্বার্থভাবে নিজেদের জীবন ও সম্পদ উৎসর্গ করে সংগ্রাম করে যাওয়ার জন্য প্রয়োজন দৃঢ় চরিত্রবল, আত্মিক শক্তি, সবর, লক্ষের প্রতি অবিচলতা (হানিফ)। সেই চরিত্র হতে হবে প্রধানত উপরোক্ত পাঁচ দফা ভিত্তিক অর্থাৎ তাদেরকে হতে হবে ইস্পাতের মত ঐক্যবদ্ধ, পিঁপড়ার মতো সুশৃঙ্খল, স্রষ্টার প্রতি প্রকৃতির মতো আনুগত্যশীল, সকল অন্যায়ের বিরুদ্ধে নির্ভীক, কঠোর, প্রতিবাদী, নিঃস্বার্থ মানবপ্রেমী ও সংগ্রামী। এই চরিত্র অর্জনের জন্য হেযবুত তওহীদের প্রশিক্ষণ হচ্ছে সঠিক পদ্ধতিতে সালাহ বা নামাজ কায়েম করা। হেযবুত তওহীদকে আল্লাহ সালাতের সঠিক উদ্দেশ্য ও প্রক্রিয়া দান করেছেন। সালাতের বাইরে হেযবুত তওহীদ শারীরিক ও মানসিক স্বাস্থ্য বজায় রাখার জন্য বিভিন্ন শরীরচর্চামূলক খেলাকে (যেমন কাবাডি, ফুটবল, সাঁতার) উৎসাহিত করে থাকে।
                    </p>
                </div>
            </div>

            <!-- 6. Women participation -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="about-img-wrap">
                        @php
                            $womenImg = '/uploads/about/women.jpg';
                            $womenImgExists = file_exists(public_path($womenImg));
                        @endphp
                        @if($womenImgExists)
                            <img src="{{ asset($womenImg) }}" alt="সকল কাজে নারীদের অংশগ্রহণ" loading="lazy" />
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center text-white py-5 px-4 text-center h-100 rounded-3" style="background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);">
                                <i class="fas fa-users-cog fa-3x mb-3" style="color: #fbbf24;"></i>
                                <h5 class="fw-bold mb-1">নারীদের সক্রিয় অংশগ্রহণ</h5>
                                <p class="small opacity-75">শরিয়াহ সম্মত পর্দায় থেকে সামাজিক ও প্রাতিষ্ঠানিক কাজে সমান অংশীদার</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5">
                   <div class="d-inline-flex align-items-center gap-2 mb-3 bg-success bg-opacity-10 text-success px-3 py-1.5 rounded-pill border border-success border-opacity-10">
                        <i class="fas fa-venus" style="font-size: 0.8rem; color: #006A4E;"></i>
                        <span class="fw-bold" style="font-size: 0.78rem; letter-spacing: 0.5px; color: #006A4E;">সকল কাজে নারীদের অংশগ্রহণ</span>
                    </div>
                    <p class="text-secondary leading-relaxed" style="line-height: 1.8; font-size: 0.96rem;">
                      রসুলাল্লাহর সময় যেমন পুরুষ আসহাবদের পাশাপাশি নারী আসহাবগণও জাতীয় ও সামাজিক প্রায় সকল কাজে অংশগ্রহণ করেছেন ঠিক তেমনি হেযবুত তওহীদ আন্দোলনের প্রায় সকল কাজে পুরুষের পাশাপাশি নারীরাও অংশগ্রহণ করে থাকে। আমীরের দায়িত্ব থেকে শুরু করে অফিসিয়াল কাজ, বিভিন্ন ডিপার্টমেন্টের কাজ (যেমন: প্রিন্ট ও ইলেকট্রনিক মিডিয়ার কাজ, হিসাব রক্ষণ বিভাগের কাজ, সাংস্কৃতিক কর্মকাণ্ড ইত্যাদি) এমনকি পত্রিকা, বই বিক্রির কাজেও নারীরা শরিয়াহ নির্ধারিত যথাযথ হেজাব অনুসরণ করে পুরুষের সাথে অংশগ্রহণ করেন।
                    </p>
                </div>
            </div>

            <!-- 7. Culture -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                    <div class="about-img-wrap">
                        @php
                            $cultureImg = '/uploads/about/culture.jpg';
                            $cultureImgExists = file_exists(public_path($cultureImg));
                        @endphp
                        @if($cultureImgExists)
                            <img src="{{ asset($cultureImg) }}" alt="সাংস্কৃতিক কর্মকাণ্ড" loading="lazy" />
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center text-white py-5 px-4 text-center h-100 rounded-3" style="background: linear-gradient(135deg, #f43f5e 0%, #be123c 100%);">
                                <i class="fas fa-music fa-3x mb-3" style="color: #fbbf24;"></i>
                                <h5 class="fw-bold mb-1">সুস্থ সংস্কৃতির বিকাশ</h5>
                                <p class="small opacity-75">অপসংস্কৃতি ও অশ্লীলতার বিকল্প হিসেবে দেশাত্মবোধক গান পরিবেশন</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 pe-lg-5 order-lg-1">
                    <div class="d-inline-flex align-items-center gap-2 mb-3 bg-success bg-opacity-10 text-success px-3 py-1.5 rounded-pill border border-success border-opacity-10">
                        <i class="fas fa-music" style="font-size: 0.8rem; color: #006A4E;"></i>
                        <span class="fw-bold" style="font-size: 0.78rem; letter-spacing: 0.5px; color: #006A4E;">সাংস্কৃতিক কর্মকাণ্ড</span>
                    </div>
                    <p class="text-secondary leading-relaxed" style="line-height: 1.8; font-size: 0.96rem;">
                        হেযবুত তওহীদ সুস্থ ধারার সংস্কৃতিকে লালন করে। এমামুয্যামানের স্মরণে হেযবুত তওহীদের প্রকাশিত প্রথম গানের অ্যালবাম “দ্য লিডার অব দ্য টাইম’। সেমিনারগুলিতে হেযবুত তওহীদের সদস্য-সদস্যরা যন্ত্রানুসঙ্গ সহযোগে সঙ্গীত পরিবেশন করে। আমরা জানি অশ্লীলতা ইসলামে হারাম বা অবৈধ কিন্তু যা অশ্লীল নয়, সুস্থ- এমন বিনোদন ইসলামে বৈধ।
                    </p>
                </div>
            </div>

            <!-- 8. Sports -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="about-img-wrap">
                        @php
                            $sportsImg = '/uploads/about/sports.jpg';
                            $sportsImgExists = file_exists(public_path($sportsImg));
                        @endphp
                        @if($sportsImgExists)
                            <img src="{{ asset($sportsImg) }}" alt="খেলাধুলা" loading="lazy" />
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center text-white py-5 px-4 text-center h-100 rounded-3" style="background: linear-gradient(135deg, #d97706 0%, #92400e 100%);">
                                <i class="fas fa-running fa-3x mb-3" style="color: #fbbf24;"></i>
                                <h5 class="fw-bold mb-1">শরীরচর্চা ও খেলাধুলা</h5>
                                <p class="small opacity-75">হাডুডু, ফুটবল, দৈনিক দৌড় ও সাঁতারের মাধ্যমে যুবসমাজকে চাঙ্গা করা</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <div class="d-inline-flex align-items-center gap-2 mb-3 bg-success bg-opacity-10 text-success px-3 py-1.5 rounded-pill border border-success border-opacity-10">
                        <i class="fas fa-futbol" style="font-size: 0.8rem; color: #006A4E;"></i>
                        <span class="fw-bold" style="font-size: 0.78rem; letter-spacing: 0.5px; color: #006A4E;">খেলাধুলা</span>
                    </div>
                    <p class="text-secondary leading-relaxed" style="line-height: 1.8; font-size: 0.96rem;">
                      একটি শক্তিশালী, বহির্মুখী, গতিশীল জাতির জন্য প্রয়োজন সুস্থ, সবল, গতিশীল, উদ্দমী নাগরিক। আর সুস্থ, সবল নাগরিক গড়ে তুলতে খেলাধুলা ও শরীর চর্চার কোনো বিকল্প নেই। কাজেই হেযবুত তওহীদ আন্দোলনের সদস্যদের মধ্যে শারীরিক সুস্থতা, ক্ষিপ্রতা, গতিশীলতা, সাহসিকতা ইত্যাদি বৃদ্ধির জন্য খেলাধুলার ব্যবস্থা রয়েছে। এক্ষেত্রে দেশীয় বা আন্তর্জাতিক বহিরাঙ্গনের (আউটডোর) খেলা যেমন কাবাডি, হা-ডু-ডু, ফুটবল, দৌঁড়, সাতার, ব্যাডমিন্টন ইত্যাদিকে প্রাধান্য দেওয়া হয়। যেসব খেলা মানুষকে অন্তর্মুখী ও স্থবির করে ফেলে সেগুলো নিরুৎসাহিত করা হয় এবং যে কোনো খেলায় অসুস্থ প্রতিযোগিতা, অর্থের লেনদেন, জুয়া বা বাজি ইত্যাদি এড়িয়ে চলতে বলা হয়।
                    </p>
                </div>
            </div>

            <!-- 9. Milestone -->
            <div class="row align-items-center mb-5 pt-4">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="about-img-wrap">
                        @php
                            $milestoneImg = '/uploads/about/milestone.jpg';
                            $milestoneImgExists = file_exists(public_path($milestoneImg));
                        @endphp
                        @if($milestoneImgExists)
                            <img src="{{ asset($milestoneImg) }}" alt="বৃহত্তম মাইলফলক" loading="lazy" />
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center text-white py-5 px-4 text-center h-100 rounded-3" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                                <i class="fas fa-calendar-check fa-3x mb-3" style="color: #fbbf24;"></i>
                                <h5 class="fw-bold mb-1">বৃহত্তম মাইলফলক</h5>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <div class="d-inline-flex align-items-center gap-2 mb-3 bg-success bg-opacity-10 text-success px-3 py-1.5 rounded-pill border border-success border-opacity-10">
                        <i class="fas fa-calendar-check" style="font-size: 0.8rem; color: #006A4E;"></i>
                        <span class="fw-bold" style="font-size: 0.78rem; letter-spacing: 0.5px; color: #006A4E;">মাইলফলক</span>
                    </div>
                    <h3 class="fw-bold mb-3" style="color: #006A4E; "><i class="fas fa-trophy text-success me-2"></i> বৃহত্তম মাইলফলক</h3>
                    <p class="text-secondary leading-relaxed text-justify mb-4" style="line-height: 1.8; font-size: 0.96rem; text-align: justify;">
                        ২ ফেব্রুয়ারি ২০০৮ তারিখে মহান আল্লাহ এক মহান মো’জেজা অর্থাৎ অলৌকিক ঘটনা সংঘটন করেন যার দ্বারা তিনি তিনটি বিষয় সত্যায়ন করেন। যথা: হেযবুত তওহীদ হক (সত্য), এর এমাম আল্লাহর মনোনীত হক এমাম, হেযবুত তওহীদের মাধ্যমে সারা পৃথিবীতে আল্লাহর সত্যদীন প্রতিষ্ঠিত হবে ইনশাল্লাহ।
                    </p>
                </div>
            </div>

            <!-- 10. Uniqueness -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                    <div class="about-img-wrap">
                        @php
                            $uniquenessImg = '/uploads/about/uniqueness.jpg';
                            $uniquenessImgExists = file_exists(public_path($uniquenessImg));
                        @endphp
                        @if($uniquenessImgExists)
                            <img src="{{ asset($uniquenessImg) }}" alt="অনন্যতা" loading="lazy" />
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center text-white py-5 px-4 text-center h-100 rounded-3" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                                <i class="fas fa-fingerprint fa-3x mb-3" style="color: #fbbf24;"></i>
                                <h5 class="fw-bold mb-1">অনন্যতা</h5>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 pe-lg-5 order-lg-1">
                    <div class="d-inline-flex align-items-center gap-2 mb-3 bg-success bg-opacity-10 text-success px-3 py-1.5 rounded-pill border border-success border-opacity-10">
                        <i class="fas fa-fingerprint" style="font-size: 0.8rem; color: #006A4E;"></i>
                        <span class="fw-bold" style="font-size: 0.78rem; letter-spacing: 0.5px; color: #006A4E;">অনন্যতা</span>
                    </div>
                    <h3 class="fw-bold mb-3" style="color: #006A4E; "><i class="fas fa-star text-success me-2"></i> অনন্যতা</h3>
                    <p class="text-secondary leading-relaxed text-justify mb-4" style="line-height: 1.8; font-size: 0.96rem; text-align: justify;">
                        হেযবুত তওহীদ গত ২৭ বছরে দেশের একটিও আইনভঙ্গ করেনি, এর কোন সদস্য একটিও অপরাধ করেনি। এর প্রমাণ গত ২৭ বছরে এই আন্দোলনের বিরুদ্ধে পাঁচ শতাধিক অধিক মিথ্যা মামলা দায়ের করা হয়েছে কিন্তু একটি মামলাতেও এর কোন একজন সদস্যেরও কোন আইনভঙ্গের প্রমাণ পাওয়া যায়নি। সুতরাং তাদের কেউ সাজাপ্রাপ্ত হননি। আইন মান্য করার এরূপ দৃষ্টান্ত দেশের আইন-শৃঙ্খলা রক্ষাকারী বাহিনীগুলির একটিও দেখাতে পারে নি। অথচ ধর্মব্যবসায়ীদের ষড়যন্ত্র এবং ধর্মভিত্তিক রাজনীতিক কতিপয় দলের ষড়যন্ত্রের শিকার হয়ে এ পর্যন্ত আমাদের চার জন ভাই-বোন শহীদ হয়েছেন, শত শত আহত ও পঙ্গু হয়েছেন, বহু বাড়ি-ঘর লুটপাট, অগ্নিসংযোগ করা হয়েছে।
                    </p>
                </div>
            </div>

            <!-- 11. Finance -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="about-img-wrap">
                        @php
                            $financeImg = '/uploads/about/finance.jpg';
                            $financeImgExists = file_exists(public_path($financeImg));
                        @endphp
                        @if($financeImgExists)
                            <img src="{{ asset($financeImg) }}" alt="অর্থের উৎস" loading="lazy" />
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center text-white py-5 px-4 text-center h-100 rounded-3" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                                <i class="fas fa-hand-holding-usd fa-3x mb-3" style="color: #fbbf24;"></i>
                                <h5 class="fw-bold mb-1">অর্থের উৎস</h5>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <div class="d-inline-flex align-items-center gap-2 mb-3 bg-success bg-opacity-10 text-success px-3 py-1.5 rounded-pill border border-success border-opacity-10">
                        <i class="fas fa-hand-holding-usd" style="font-size: 0.8rem; color: #006A4E;"></i>
                        <span class="fw-bold" style="font-size: 0.78rem; letter-spacing: 0.5px; color: #006A4E;">অর্থায়ন</span>
                    </div>
                    <h3 class="fw-bold mb-3" style="color: #006A4E; "><i class="fas fa-wallet text-success me-2"></i> অর্থের উৎস</h3>
                    <p class="text-secondary leading-relaxed text-justify mb-4" style="line-height: 1.8; font-size: 0.96rem; text-align: justify;">
                        হেযবুত তওহীদের সদস্যরা নিজেদের উপার্জিত বা অর্জিত সম্পদ ব্যয় করে আন্দোলনের কাজ করে থাকেন।
                    </p>
                </div>
            </div>

            <!-- 12. Managed Institutes -->
            <div class="card py-3 py-md-4 px-4 px-md-5 mb-5 border-0 shadow-sm bg-white rounded-4 text-center">
                <h3 class="mb-4 fw-bold" style="color: #006A4E; "><i class="fas fa-building me-2"></i> পরিচালিত প্রতিষ্ঠানসমূহ</h3>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <span class="badge bg-success bg-opacity-10 text-success p-3 fs-6 rounded-pill border border-success border-opacity-25" style="transition: all 0.2s;" onmouseover="this.style.background='var(--primary-green)'; this.style.color='#fff';" onmouseout="this.style.background='rgba(40, 167, 69, 0.1)'; this.style.color='var(--primary-green)';"><i class="fas fa-book-open me-1"></i> তওহীদ প্রকাশন</span>
                    <span class="badge bg-success bg-opacity-10 text-success p-3 fs-6 rounded-pill border border-success border-opacity-25" style="transition: all 0.2s;" onmouseover="this.style.background='var(--primary-green)'; this.style.color='#fff';" onmouseout="this.style.background='rgba(40, 167, 69, 0.1)'; this.style.color='var(--primary-green)';"><i class="fas fa-users me-1"></i> তওহীদ কাবাডি দল</span>
                    <span class="badge bg-success bg-opacity-10 text-success p-3 fs-6 rounded-pill border border-success border-opacity-25" style="transition: all 0.2s;" onmouseover="this.style.background='var(--primary-green)'; this.style.color='#fff';" onmouseout="this.style.background='rgba(40, 167, 69, 0.1)'; this.style.color='var(--primary-green)';"><i class="fas fa-newspaper me-1"></i> দৈনিক বজ্রশক্তি</span>
                    <span class="badge bg-success bg-opacity-10 text-success p-3 fs-6 rounded-pill border border-success border-opacity-25" style="transition: all 0.2s;" onmouseover="this.style.background='var(--primary-green)'; this.style.color='#fff';" onmouseout="this.style.background='rgba(40, 167, 69, 0.1)'; this.style.color='var(--primary-green)';"><i class="fas fa-photo-video me-1"></i> ইলদ্রিম মিডিয়া</span>
                    <span class="badge bg-success bg-opacity-10 text-success p-3 fs-6 rounded-pill border border-success border-opacity-25" style="transition: all 0.2s;" onmouseover="this.style.background='var(--primary-green)'; this.style.color='#fff';" onmouseout="this.style.background='rgba(40, 167, 69, 0.1)'; this.style.color='var(--primary-green)';"><i class="fas fa-globe me-1"></i> বাংলাদেশের পত্র (অনলাইন)</span>
                    <span class="badge bg-success bg-opacity-10 text-success p-3 fs-6 rounded-pill border border-success border-opacity-25" style="transition: all 0.2s;" onmouseover="this.style.background='var(--primary-green)'; this.style.color='#fff';" onmouseout="this.style.background='rgba(40, 167, 69, 0.1)'; this.style.color='var(--primary-green)';"><i class="fas fa-tv me-1"></i> jatiyatv.com (অনলাইন)</span>
                </div>
            </div>

            <!-- 13. Published Books -->
            <div class="card py-3 py-md-4 px-4 px-md-5 mb-5 border-0 shadow-sm bg-white rounded-4">
                <h3 class="mb-4 text-center fw-bold" style="color: #006A4E; "><i class="fas fa-book me-2"></i> প্রকাশিত পুস্তকসমূহ</h3>
                <div class="row g-3">
                    <div class="col-md-6 col-lg-4"><div class="p-3 border-start border-success border-4 bg-light bg-opacity-75 rounded-3 shadow-sm fw-semibold hover-lift" style="transition: all 0.25s;"><i class="fas fa-check text-success me-2"></i> ইসলামের প্রকৃত রূপরেখা</div></div>
                    <div class="col-md-6 col-lg-4"><div class="p-3 border-start border-success border-4 bg-light bg-opacity-75 rounded-3 shadow-sm fw-semibold hover-lift" style="transition: all 0.25s;"><i class="fas fa-check text-success me-2"></i> ইসলামের প্রকৃত সালাহ্</div></div>
                    <div class="col-md-6 col-lg-4"><div class="p-3 border-start border-success border-4 bg-light bg-opacity-75 rounded-3 shadow-sm fw-semibold hover-lift" style="transition: all 0.25s;"><i class="fas fa-check text-success me-2"></i> দাজ্জাল? ইহুদি-খ্রিষ্টান ‘সভ্যতা’!</div></div>
                    <div class="col-md-6 col-lg-4"><div class="p-3 border-start border-success border-4 bg-light bg-opacity-75 rounded-3 shadow-sm fw-semibold hover-lift" style="transition: all 0.25s;"><i class="fas fa-check text-success me-2"></i> The Lost Islam</div></div>
                    <div class="col-md-6 col-lg-4"><div class="p-3 border-start border-success border-4 bg-light bg-opacity-75 rounded-3 shadow-sm fw-semibold hover-lift" style="transition: all 0.25s;"><i class="fas fa-check text-success me-2"></i> হেযবুত তওহীদের লক্ষ্য ও উদ্দেশ্য</div></div>
                    <div class="col-md-6 col-lg-4"><div class="p-3 border-start border-success border-4 bg-light bg-opacity-75 rounded-3 shadow-sm fw-semibold hover-lift" style="transition: all 0.25s;"><i class="fas fa-check text-success me-2"></i> জেহাদ, কেতাল ও সন্ত্রাস</div></div>
                    <div class="col-md-6 col-lg-4"><div class="p-3 border-start border-success border-4 bg-light bg-opacity-75 rounded-3 shadow-sm fw-semibold hover-lift" style="transition: all 0.25s;"><i class="fas fa-check text-success me-2"></i> আল্লাহর মো’জেজা: বিজয় ঘোষণা</div></div>
                    <div class="col-md-6 col-lg-4"><div class="p-3 border-start border-success border-4 bg-light bg-opacity-75 rounded-3 shadow-sm fw-semibold hover-lift" style="transition: all 0.25s;"><i class="fas fa-check text-success me-2"></i> বর্তমানের বিকৃত সুফিবাদ</div></div>
                    <div class="col-md-6 col-lg-4"><div class="p-3 border-start border-success border-4 bg-light bg-opacity-75 rounded-3 shadow-sm fw-semibold hover-lift" style="transition: all 0.25s;"><i class="fas fa-check text-success me-2"></i> ঔপনিবেশিক ষড়যন্ত্রমূলক শিক্ষাব্যবস্থা</div></div>
                </div>
            </div>

            <!-- 14. Youtube Documentaries -->
            <div class="card py-3 py-md-4 px-4 px-md-5 mb-5 border-0 shadow-sm bg-white rounded-4">
                <h3 class="mb-4 text-center fw-bold" style="color: #006A4E; "><i class="fab fa-youtube text-danger me-2"></i> উল্লেখযোগ্য প্রামাণ্যচিত্রসমূহ</h3>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded-3 overflow-hidden shadow-sm bg-light hover-lift" style="transition: all 0.3s ease;">
                            <a href="https://www.youtube.com/watch?v=EMZgDNuyQEc" target="_blank" class="text-decoration-none">
                                <div class="position-relative overflow-hidden">
                                    <img src="https://img.youtube.com/vi/EMZgDNuyQEc/hqdefault.jpg" class="w-100" style="aspect-ratio: 16/9; object-fit: cover;" loading="lazy" />
                                    <div class="position-absolute top-50 start-50 translate-middle"><i class="fab fa-youtube text-danger fa-3x bg-white rounded-circle p-1"></i></div>
                                </div>
                                <div class="p-3 fw-bold text-dark text-center" style="font-size: 0.92rem; line-height: 1.4;">এক জাতি এক দেশ, ঐক্যবদ্ধ বাংলাদেশ</div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded-3 overflow-hidden shadow-sm bg-light hover-lift" style="transition: all 0.3s ease;">
                            <a href="https://www.youtube.com/watch?v=NDfi55itQas" target="_blank" class="text-decoration-none">
                                <div class="position-relative overflow-hidden">
                                    <img src="https://img.youtube.com/vi/NDfi55itQas/hqdefault.jpg" class="w-100" style="aspect-ratio: 16/9; object-fit: cover;" loading="lazy" />
                                    <div class="position-absolute top-50 start-50 translate-middle"><i class="fab fa-youtube text-danger fa-3x bg-white rounded-circle p-1"></i></div>
                                </div>
                                <div class="p-3 fw-bold text-dark text-center" style="font-size: 0.92rem; line-height: 1.4;">ধর্মব্যবসা ও অপ-রাজনীতির ইতিবৃত্ত</div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded-3 overflow-hidden shadow-sm bg-light hover-lift" style="transition: all 0.3s ease;">
                            <a href="https://www.youtube.com/watch?v=CkTYeo_Mprg" target="_blank" class="text-decoration-none">
                                <div class="position-relative overflow-hidden">
                                    <img src="https://img.youtube.com/vi/CkTYeo_Mprg/hqdefault.jpg" class="w-100" style="aspect-ratio: 16/9; object-fit: cover;" loading="lazy" />
                                    <div class="position-absolute top-50 start-50 translate-middle"><i class="fab fa-youtube text-danger fa-3x bg-white rounded-circle p-1"></i></div>
                                </div>
                                <div class="p-3 fw-bold text-dark text-center" style="font-size: 0.92rem; line-height: 1.4;">ইসলামে নারীর প্রকৃত মর্যাদা</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
