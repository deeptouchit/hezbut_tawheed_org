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
    <section class="py-5 page-body text-light" style="background-color: #111827;">
        <div class="container">
            <!-- 1. At a Glance -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="hover-zoom shadow-sm rounded-4 overflow-hidden">
                        <img src="{{ asset('/uploads/about/at-a-glance.jpg') }}" alt="এক নজরে হেযবুত তওহীদ" class="img-fluid w-100" />
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <h3 class="section-title mb-3" style="color: #10B981; font-weight: 700;"><i class="fas fa-eye text-success me-2"></i> এক নজরে হেযবুত তওহীদ</h3>
                    <p class="lead fw-bold text-success mb-3" style="font-size: 1.15rem; line-height: 1.6;">
                        হেযবুত তওহীদ সম্পূর্ণ অরাজনৈতিক ধর্মীয় সংস্কারমূলক আন্দোলন যার মূল কাজই হলো মানবজাতিকে ন্যায়ের পক্ষে ঐক্যবদ্ধ করা এবং মানবজাতির অশান্তির মূল কারণ দাজ্জালের অনুসরণ না করে সমগ্র পৃথিবীতে সৃষ্টিকর্তার সার্বভৌমত্ব প্রতিষ্ঠা করা।
                    </p>
                    <p class="text-light-50">
                        পুরো মানবজাতিকে আল্লাহর তওহীদের ভিত্তিতে অর্থাৎ সত্য ও ন্যায়ের পক্ষে, সকল অন্যায়ের বিরুদ্ধে ঐক্যবদ্ধ করাই হেযবুত তওহীদের মূল লক্ষ্য। মানবজীবনে সঠিক পথ, হেদায়াহ (Right Direction) ও সত্য জীবনব্যবস্থা প্রতিষ্ঠিত হলে সমস্ত মানবজাতি অন্যায় ও অবিচার থেকে মুক্তি পাবে। পৃথিবীতে প্রতিষ্ঠিত হবে অনাবিল শান্তি। সেই শান্তিময় পৃথিবী প্রতিষ্ঠার লক্ষ্যকে সামনে নিয়ে সংগ্রাম করে যাচ্ছে হেযবুত তওহীদের সদস্য-সদস্যারা।
                    </p>
                </div>
            </div>

            <!-- 2. Founder & Current Leader -->
            <div class="row g-4 mb-5 pt-4">
                <div class="col-lg-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4" style="background-color: #1f2937; border: 1px solid #374151 !important;">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center mb-3 mb-md-0">
                                <img src="{{ asset('/uploads/about/bayazid-khan-panni.jpg') }}" alt="মোহাম্মদ বায়াজীদ খান পন্নী" class="img-fluid rounded-circle border border-3 border-success shadow" style="width: 140px; height: 140px; object-fit: cover;" />
                            </div>
                            <div class="col-md-8">
                                <span class="badge bg-success bg-opacity-20 text-success mb-2 px-3 py-1">প্রতিষ্ঠাতা</span>
                                <h4 class="fw-bold mb-1 text-white">মোহাম্মদ বায়াজীদ খান পন্নী</h4>
                                <p class="text-light small opacity-50 mb-2">প্রতিষ্ঠাতা এমামুয্যামান</p>
                                <p class="small mb-0 text-light opacity-75" style="line-height: 1.6;">
                                    টাঙ্গাইলের ঐতিহ্যবাহী পন্নী পরিবারে জন্ম নেওয়া জনাব মোহাম্মদ বায়াজীদ খান পন্নী ছিলেন একাধারে সমাজ সংস্কারক, লেখক, চিন্তাবিদ ও সত্যের সন্ধানী এক মহান ব্যক্তিত্ব। ১৯৯৬ সালে তিনি হেযবুত তওহীদ আন্দোলনের সূচনা করেন।
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4" style="background-color: #1f2937; border: 1px solid #374151 !important;">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center mb-3 mb-md-0">
                                <img src="{{ asset('/uploads/about/h-m-selim.jpg') }}" alt="হোসাইন মোহাম্মদ সেলিম" class="img-fluid rounded-circle border border-3 border-success shadow" style="width: 140px; height: 140px; object-fit: cover;" />
                            </div>
                            <div class="col-md-8">
                                <span class="badge bg-success bg-opacity-20 text-success mb-2 px-3 py-1">বর্তমান এমাম</span>
                                <h4 class="fw-bold mb-1 text-white">হোসাইন মোহাম্মদ সেলিম</h4>
                                <p class="text-muted small mb-2">যামানার এমাম</p>
                                <p class="small mb-0 text-light opacity-75" style="line-height: 1.6;">
                                    ২০১২ সালের ১৬ জানুয়ারি জনাব মোহাম্মদ বায়াজীদ খান পন্নীর ইন্তেকালের পর হেযবুত তওহীদের এমামের দায়িত্ব গ্রহণ করেন জনাব হোসাইন মোহাম্মদ সেলিম। তাঁর গতিশীল নেতৃত্বে আন্দোলনটি দেশব্যাপী এক বিশাল সংস্কারমূলক আলোড়ন তৈরি করেছে।
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Principles & Program -->
            <div class="row g-4 mb-5">
                <div class="col-lg-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 text-white p-4 p-md-5" style="background: linear-gradient(135deg, #1f2937 0%, #111827 100%); border: 1px solid #374151 !important;">
                        <h4 class="fw-bold text-success mb-3" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-scroll me-2"></i> মূলনীতি</h4>
                        <p class="small text-light opacity-50 mb-4">হেযবুত তওহীদের মূল ভিত্তি হচ্ছে সার্বিক মানবকল্যাণ ও সত্যের জন্য ঐক্যবদ্ধ প্রচেষ্টা:</p>
                        <ul class="ps-3 text-light" style="line-height: 1.8;">
                            <li class="mb-3">মানবজাতি এক ও অবিভাজ্য।</li>
                            <li class="mb-3">ধর্মকে স্বার্থ বা রাজনীতির হাতিয়ার না বানিয়ে নিঃস্বার্থ মানবকল্যাণে ব্যবহার করা।</li>
                            <li class="mb-3">অন্যায় ও অসত্যের বিরুদ্ধে সত্য ও ন্যায়ের লড়াই করা।</li>
                            <li class="mb-3">সকল প্রকার উগ্রবাদ ও অন্ধ গোঁড়ামির অবসান ঘটানো।</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 text-white p-4 p-md-5" style="background: linear-gradient(135deg, #022c1b 0%, #00120b 100%); border: 1px solid #064e3b !important;">
                        <h4 class="fw-bold text-warning mb-3" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-bullseye me-2"></i> ৫ দফা কর্মসূচি</h4>
                        <p class="small text-light opacity-50 mb-4">দীনুল হক বা ন্যায় প্রতিষ্ঠার লক্ষ্যে হেযবুত তওহীদ নিম্নোক্ত ৫ দফা কর্মসূচি পরিচালনা করে:</p>
                        <ol class="ps-3 text-light" style="line-height: 1.8;">
                            <li class="mb-2">ঐক্যবদ্ধ হওয়া (অন্যায় ও অবিচারের বিরুদ্ধে)।</li>
                            <li class="mb-2">নেতার সৎ আদেশ শোনা।</li>
                            <li class="mb-2">নেতার সৎ আদেশ মান্য ও পালন করা।</li>
                            <li class="mb-2">হেযরত (যাবতীয় অন্যায়ের সাথে সম্পর্কচ্ছেদ করা)।</li>
                            <li class="mb-2">সত্য ও ন্যায় প্রতিষ্ঠার লক্ষ্যে সর্বাত্মক প্রচেষ্টা চালানো।</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- 4. Alternating Grid for Operational sections -->
            <!-- Process -->
            <div class="row align-items-center mb-5 pt-4">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="hover-zoom shadow-sm rounded-4 overflow-hidden">
                        <img src="{{ asset('/uploads/about/process.jpg') }}" alt="কর্মপ্রক্রিয়া" class="img-fluid w-100" />
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <h3 class="fw-bold mb-3" style="color: #10B981; font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-cogs me-2"></i> কর্মপ্রক্রিয়া</h3>
                    <p class="text-light opacity-75 leading-relaxed" style="line-height: 1.8;">
                        হেযবুত তওহীদ রাষ্ট্রীয় আইনকে পূর্ণরূপে মান্য করে দীর্ঘ ২১ বছরেরও বেশি সময় ধরে আন্দোলন পরিচালনা করে আসছে। মানবজাতিকে স্রষ্টার সার্বভৌমত্বের দিকে আহ্বান করার জন্য হ্যান্ডবিল, বই, পত্রিকা, প্রামাণ্যচিত্র ইত্যাদি সর্বশ্রেণি মানুষের কাছে পৌঁছানোর জন্য সর্বাত্মক চেষ্টা করে থাকে। বাসে, ট্রেনে, লঞ্চে, রাস্তাঘাটে এই প্রকাশনা সামগ্রীগুলি বিক্রয়, বই মেলায় স্টল গ্রহণ, বিভিন্ন মিলনায়তন ও সেমিনার কক্ষে দেশের সুধী ও সর্বস্তরের মানুষের সাথে নিয়মিত মতবিনিময় করা হয়।
                    </p>
                </div>
            </div>

            <!-- Training -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                    <div class="hover-zoom shadow-sm rounded-4 overflow-hidden">
                        <img src="{{ asset('/uploads/about/training.jpg') }}" alt="প্রশিক্ষণ" class="img-fluid w-100" />
                    </div>
                </div>
                <div class="col-lg-6 pe-lg-5 order-lg-1">
                    <h3 class="fw-bold mb-3" style="color: #10B981; font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-dumbbell me-2"></i> প্রশিক্ষণ ও জীবনধারা</h3>
                    <p class="text-light opacity-75 leading-relaxed" style="line-height: 1.8;">
                        মানবতার মুক্তির লক্ষ্যে আল্লাহর সত্যদীন প্রতিষ্ঠার জন্য নিঃস্বার্থভাবে নিজেদের জীবন ও সম্পদ উৎসর্গ করে সংগ্রাম করে যাওয়ার জন্য প্রয়োজন চরিত্রবল, আত্মিক শক্তি, সবর ও অবিচলতা। হেযবুত তওহীদের প্রশিক্ষণ হচ্ছে সঠিক পদ্ধতিতে সালাহ বা নামাজ কায়েম করা। সালাতের বাইরে হেযবুত তওহীদ শারীরিক ও মানসিক স্বাস্থ্য বজায় রাখার জন্য বিভিন্ন শরীরচর্চামূলক খেলাকে (যেমন কাবাডি, ফুটবল, সাঁতার) উৎসাহিত করে থাকে।
                    </p>
                </div>
            </div>

            <!-- Women participation -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="hover-zoom shadow-sm rounded-4 overflow-hidden">
                        <img src="{{ asset('/uploads/about/women.jpg') }}" alt="সকল কাজে নারীদের অংশগ্রহণ" class="img-fluid w-100" />
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <h3 class="fw-bold mb-3" style="color: #10B981; font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-users-cog me-2"></i> সকল কাজে নারীদের অংশগ্রহণ</h3>
                    <p class="text-light opacity-75 leading-relaxed" style="line-height: 1.8;">
                        রসুলাল্লাহর সময় যেমন পুরুষ আসহাবদের পাশাপাশি নারী আসহাবগণও জাতীয় ও সামাজিক প্রায় সকল কাজে অংশগ্রহণ করেছেন ঠিক তেমনি হেযবুত তওহীদ আন্দোলনের প্রায় সকল কাজে পুরুষের পাশাপাশি নারীরাও সক্রিয়ভাবে অংশগ্রহণ করে থাকেন। আমীরের দায়িত্ব থেকে শুরু করে অফিসিয়াল কাজ, বিভিন্ন ডিপার্টমেন্টের কাজ (যেমন: প্রিন্ট ও ইলেকট্রনিক মিডিয়ার কাজ, হিসাব রক্ষণ বিভাগের কাজ, সাংস্কৃতিক কর্মকাণ্ড ইত্যাদি) এমনকি পত্রিকা ও বই বিক্রির কাজেও নারীরা শরিয়াহ নির্ধারিত যথাযথ হেজাব অনুসরণ করে পুরুষের সাথে অংশগ্রহণ করেন।
                    </p>
                </div>
            </div>

            <!-- Culture -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                    <div class="hover-zoom shadow-sm rounded-4 overflow-hidden">
                        <img src="{{ asset('/uploads/about/culture.jpg') }}" alt="সাংস্কৃতিক কর্মকাণ্ড" class="img-fluid w-100" />
                    </div>
                </div>
                <div class="col-lg-6 pe-lg-5 order-lg-1">
                    <h3 class="fw-bold mb-3" style="color: #10B981; font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-music me-2"></i> সুস্থ ধারার সাংস্কৃতিক কর্মকাণ্ড</h3>
                    <p class="text-light opacity-75 leading-relaxed" style="line-height: 1.8;">
                        হেযবুত তওহীদ সুস্থ ধারার সংস্কৃতিকে লালন করে। এমামুয্যামানের স্মরণে হেযবুত তওহীদের প্রকাশিত প্রথম গানের অ্যালবাম “দ্য লিডার অব দ্য টাইম’। বিভিন্ন জাতীয় অনুষ্ঠান ও সেমিনারগুলিতে হেযবুত তওহীদের সদস্য-সদস্যরা সুস্থ বিনোদনের অংশ হিসেবে দেশাত্মবোধক ও সচেতনতামূলক সঙ্গীত পরিবেশন করে থাকেন। আমরা জানি অশ্লীলতা ইসলামে সম্পূর্ণ অবৈধ কিন্তু যা অশ্লীল নয়, সুস্থ—এমন বিনোদন মানুষের মনকে সতেজ রাখে।
                    </p>
                </div>
            </div>

            <!-- Sports -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="hover-zoom shadow-sm rounded-4 overflow-hidden">
                        <img src="{{ asset('/uploads/about/sports.jpg') }}" alt="খেলাধুলা" class="img-fluid w-100" />
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <h3 class="fw-bold mb-3" style="color: #10B981; font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-running me-2"></i> শরীরচর্চা ও খেলাধুলা</h3>
                    <p class="text-light opacity-75 leading-relaxed" style="line-height: 1.8;">
                        সুস্থ, সবল নাগরিক গড়ে তুলতে খেলাধুলা ও শরীর চর্চার কোনো বিকল্প নেই। হেযবুত তওহীদ আন্দোলনের সদস্যদের মধ্যে শারীরিক সুস্থতা, ক্ষিপ্রতা, গতিশীলতা ও সাহসিকতা বৃদ্ধির জন্য খেলাধুলার ব্যবস্থা রয়েছে। এক্ষেত্রে দেশীয় বা আন্তর্জাতিক বহিরাঙ্গনের (আউটডোর) খেলা যেমন কাবাডি, হা-ডু-ডু, ফুটবল, দৌঁড়, সাতার, ব্যাডমিন্টন ইত্যাদিকে প্রাধান্য দেওয়া হয়।
                    </p>
                </div>
            </div>

            <!-- Milestone & Uniqueness & Finance -->
            <div class="row g-4 mb-5 pt-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4" style="background-color: #1f2937; border: 1px solid #374151 !important;">
                        <h4 class="fw-bold mb-2 text-success" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-calendar-check me-2"></i> বৃহত্তম মাইলফলক</h4>
                        <p class="small text-light opacity-75 mb-0" style="line-height: 1.6;">
                            ২ ফেব্রুয়ারি ২০০৮ তারিখে মহান আল্লাহ এক অলৌকিক ঘটনা সংঘটন করেন যার দ্বারা তিনি হেযবুত তওহীদের সত্যতা, এর এমামের গ্রহণযোগ্যতা এবং এই আন্দোলনের চূড়ান্ত লক্ষ্যকে সত্যায়ন করেন।
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4" style="background-color: #1f2937; border: 1px solid #374151 !important;">
                        <h4 class="fw-bold mb-2 text-success" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-fingerprint me-2"></i> অনন্যতা</h4>
                        <p class="small text-light opacity-75 mb-0" style="line-height: 1.6;">
                            হেযবুত তওহীদ বিগত দীর্ঘ বছরগুলোতে দেশের একটিও আইনভঙ্গ করেনি। বহু ষড়যন্ত্র ও মিথ্যা মামলা সত্ত্বেও একটি মামলাতেও এ আন্দোলনের কোনো সদস্যের আইনভঙ্গের কোনো প্রমাণ পাওয়া যায়নি।
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4" style="background-color: #1f2937; border: 1px solid #374151 !important;">
                        <h4 class="fw-bold mb-2 text-success" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-hand-holding-usd me-2"></i> অর্থের উৎস</h4>
                        <p class="small text-light opacity-75 mb-0" style="line-height: 1.6;">
                            কোনো জাতীয় বা আন্তর্জাতিক কালো তহবিল বা রাজনৈতিক অনুদান ছাড়াই, হেযবুত তওহীদের সদস্যরা সম্পূর্ণ নিজেদের উপার্জিত বা অর্জিত সৎ উপায়ের সম্পদ ব্যয় করে আন্দোলনের কাজ পরিচালনা করেন।
                        </p>
                    </div>
                </div>
            </div>

            <!-- Managed Institutes -->
            <div class="card p-4 p-md-5 mb-5 border-0 shadow-sm rounded-4 text-center text-light" style="background-color: #1f2937; border: 1px solid #374151 !important;">
                <h3 class="mb-4 fw-bold" style="color: #10B981; font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-building me-2"></i> পরিচালিত প্রতিষ্ঠানসমূহ</h3>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <span class="badge bg-success bg-opacity-20 text-success p-3 fs-6 rounded-pill border border-success border-opacity-25"><i class="fas fa-book-open me-1"></i> তওহীদ প্রকাশন</span>
                    <span class="badge bg-success bg-opacity-20 text-success p-3 fs-6 rounded-pill border border-success border-opacity-25"><i class="fas fa-users me-1"></i> তওহীদ কাবাডি দল</span>
                    <span class="badge bg-success bg-opacity-20 text-success p-3 fs-6 rounded-pill border border-success border-opacity-25"><i class="fas fa-newspaper me-1"></i> দৈনিক বজ্রশক্তি</span>
                    <span class="badge bg-success bg-opacity-20 text-success p-3 fs-6 rounded-pill border border-success border-opacity-25"><i class="fas fa-photo-video me-1"></i> ইলদ্রিম মিডিয়া</span>
                    <span class="badge bg-success bg-opacity-20 text-success p-3 fs-6 rounded-pill border border-success border-opacity-25"><i class="fas fa-globe me-1"></i> বাংলাদেশের পত্র (অনলাইন)</span>
                    <span class="badge bg-success bg-opacity-20 text-success p-3 fs-6 rounded-pill border border-success border-opacity-25"><i class="fas fa-tv me-1"></i> jatiyatv.com (অনলাইন)</span>
                </div>
            </div>

            <!-- Published Books -->
            <div class="card p-4 p-md-5 mb-5 border-0 shadow-sm rounded-4" style="background-color: #1f2937; border: 1px solid #374151 !important;">
                <h3 class="mb-4 text-center fw-bold" style="color: #10B981; font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-book me-2"></i> প্রকাশিত পুস্তকসমূহ</h3>
                <div class="row g-3">
                    <div class="col-md-6 col-lg-4"><div class="p-3 border-start border-success border-4 rounded-3 shadow-sm fw-semibold" style="background-color: #111827; color: #f3f4f6;"><i class="fas fa-check text-success me-2"></i> ইসলামের প্রকৃত রূপরেখা</div></div>
                    <div class="col-md-6 col-lg-4"><div class="p-3 border-start border-success border-4 rounded-3 shadow-sm fw-semibold" style="background-color: #111827; color: #f3f4f6;"><i class="fas fa-check text-success me-2"></i> ইসলামের প্রকৃত সালাহ্</div></div>
                    <div class="col-md-6 col-lg-4"><div class="p-3 border-start border-success border-4 rounded-3 shadow-sm fw-semibold" style="background-color: #111827; color: #f3f4f6;"><i class="fas fa-check text-success me-2"></i> দাজ্জাল? ইহুদি-খ্রিষ্টান ‘সভ্যতা’!</div></div>
                    <div class="col-md-6 col-lg-4"><div class="p-3 border-start border-success border-4 rounded-3 shadow-sm fw-semibold" style="background-color: #111827; color: #f3f4f6;"><i class="fas fa-check text-success me-2"></i> The Lost Islam</div></div>
                    <div class="col-md-6 col-lg-4"><div class="p-3 border-start border-success border-4 rounded-3 shadow-sm fw-semibold" style="background-color: #111827; color: #f3f4f6;"><i class="fas fa-check text-success me-2"></i> হেযবুত তওহীদের লক্ষ্য ও উদ্দেশ্য</div></div>
                    <div class="col-md-6 col-lg-4"><div class="p-3 border-start border-success border-4 rounded-3 shadow-sm fw-semibold" style="background-color: #111827; color: #f3f4f6;"><i class="fas fa-check text-success me-2"></i> জেহাদ, কেতাল ও সন্ত্রাস</div></div>
                    <div class="col-md-6 col-lg-4"><div class="p-3 border-start border-success border-4 rounded-3 shadow-sm fw-semibold" style="background-color: #111827; color: #f3f4f6;"><i class="fas fa-check text-success me-2"></i> আল্লাহর মো’জেজা: বিজয় ঘোষণা</div></div>
                    <div class="col-md-6 col-lg-4"><div class="p-3 border-start border-success border-4 rounded-3 shadow-sm fw-semibold" style="background-color: #111827; color: #f3f4f6;"><i class="fas fa-check text-success me-2"></i> বর্তমানের বিকৃত সুফিবাদ</div></div>
                    <div class="col-md-6 col-lg-4"><div class="p-3 border-start border-success border-4 rounded-3 shadow-sm fw-semibold" style="background-color: #111827; color: #f3f4f6;"><i class="fas fa-check text-success me-2"></i> ঔপনিবেশিক ষড়যন্ত্রমূলক শিক্ষাব্যবস্থা</div></div>
                </div>
            </div>

            <!-- Youtube Documentaries -->
            <div class="card p-4 p-md-5 mb-5 border-0 shadow-sm rounded-4" style="background-color: #1f2937; border: 1px solid #374151 !important;">
                <h3 class="mb-4 text-center fw-bold" style="color: #10B981; font-family: 'Baloo Da 2', sans-serif;"><i class="fab fa-youtube text-danger me-2"></i> উল্লেখযোগ্য প্রামাণ্যচিত্রসমূহ</h3>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded-3 overflow-hidden shadow-sm hover-lift" style="background-color: #111827; border: 1px solid #374151 !important;">
                            <a href="https://www.youtube.com/watch?v=EMZgDNuyQEc" target="_blank" class="text-decoration-none">
                                <div class="position-relative">
                                    <img src="https://img.youtube.com/vi/EMZgDNuyQEc/hqdefault.jpg" class="w-100" style="aspect-ratio: 16/9; object-fit: cover;" />
                                    <div class="position-absolute top-50 start-50 translate-middle"><i class="fab fa-youtube text-danger fa-3x bg-white rounded-circle p-1"></i></div>
                                </div>
                                <div class="p-3 fw-bold text-light text-center" style="font-size: 0.95rem;">এক জাতি এক দেশ, ঐক্যবদ্ধ বাংলাদেশ</div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded-3 overflow-hidden shadow-sm hover-lift" style="background-color: #111827; border: 1px solid #374151 !important;">
                            <a href="https://www.youtube.com/watch?v=NDfi55itQas" target="_blank" class="text-decoration-none">
                                <div class="position-relative">
                                    <img src="https://img.youtube.com/vi/NDfi55itQas/hqdefault.jpg" class="w-100" style="aspect-ratio: 16/9; object-fit: cover;" />
                                    <div class="position-absolute top-50 start-50 translate-middle"><i class="fab fa-youtube text-danger fa-3x bg-white rounded-circle p-1"></i></div>
                                </div>
                                <div class="p-3 fw-bold text-light text-center" style="font-size: 0.95rem;">ধর্মব্যবসা ও অপ-রাজনীতির ইতিবৃত্ত</div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded-3 overflow-hidden shadow-sm hover-lift" style="background-color: #111827; border: 1px solid #374151 !important;">
                            <a href="https://www.youtube.com/watch?v=CkTYeo_Mprg" target="_blank" class="text-decoration-none">
                                <div class="position-relative">
                                    <img src="https://img.youtube.com/vi/CkTYeo_Mprg/hqdefault.jpg" class="w-100" style="aspect-ratio: 16/9; object-fit: cover;" />
                                    <div class="position-absolute top-50 start-50 translate-middle"><i class="fab fa-youtube text-danger fa-3x bg-white rounded-circle p-1"></i></div>
                                </div>
                                <div class="p-3 fw-bold text-light text-center" style="font-size: 0.95rem;">ইসলামে নারীর প্রকৃত মর্যাদা</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dynamic Executive Committee -->
            @if(isset($teamMembers) && count($teamMembers) > 0)
                <div class="mt-5 pt-4">
                    <h3 class="mb-5 text-center fw-bold" style="color: #10B981; font-size: 2rem; font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-users-cog me-2 text-success"></i> কেন্দ্রীয় কার্যনির্বাহী কমিটি</h3>
                    <div class="row g-4 justify-content-center">
                        @foreach($teamMembers as $member)
                            <div class="col-lg-3 col-md-4 col-sm-6 col-10">
                                <div class="card text-center border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-lift" style="transition: all 0.3s ease; background-color: #1f2937; border: 1px solid #374151 !important;">
                                    <div style="aspect-ratio: 1; overflow: hidden; background: #111827;">
                                        <img src="{{ asset($member->image_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400') }}" alt="{{ $member->name }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <div class="card-body p-3">
                                        <h5 class="fw-bold text-white mb-1" style="font-size: 1.15rem;">{{ $member->name }}</h5>
                                        <p class="text-muted small mb-0 fw-semibold">{{ $member->designation }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Page Extra Styles -->
    <style>
        .hover-zoom {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-zoom:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3) !important;
        }
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.4) !important;
        }
    </style>
@endsection
