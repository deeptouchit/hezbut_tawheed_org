@extends('theme::layouts.app')

@section('title', 'হেযবুত তওহীদের প্রতীক বা মনোগ্রাম - হেযবুত তওহীদ')

@push('styles')
<style>
    .ht-monogram-text {
        font-family: 'Baloo Da 2', 'Hind Siliguri', 'Outfit', sans-serif !important;
        font-size: 17.5px !important;
        line-height: 1.95 !important;
        color: #1e293b !important; /* Premium dark slate gray, high legibility */
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
        font-weight: 500 !important;
    }
    .ht-monogram-title {
        font-family: 'Baloo Da 2', 'Hind Siliguri', 'Outfit', sans-serif !important;
        color: #0f172a !important; /* Sharp solid dark slate (NOT green) */
        font-weight: 800 !important;
        -webkit-font-smoothing: antialiased;
        text-rendering: optimizeLegibility;
    }
    .ht-monogram-card-title {
        font-family: 'Baloo Da 2', 'Hind Siliguri', 'Outfit', sans-serif !important;
        color: #0f172a !important;
        font-weight: 700 !important;
    }
    .ht-monogram-card {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03) !important;
        transition: all 0.3s ease;
    }
    .ht-monogram-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
    }
    .hover-zoom {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-zoom:hover {
        transform: scale(1.03);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1) !important;
    }
    .text-justify {
        text-align: justify;
    }
</style>
@endpush

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'হেযবুত তওহীদের প্রতীক বা মনোগ্রাম',
        'subtitle' => 'হেযবুত তওহীদের অফিশিয়াল মনোগ্রামের উপাদান ও এর অন্তর্নিহিত দর্শন।',
        'badge_text' => 'পরিচিতি',
        'badge_icon' => 'fas fa-shield-alt'
    ])

    <section class="py-5 bg-off-white page-body">
        <div class="container">
            <!-- Intro Section -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-5 text-center mb-4 mb-lg-0">
                    <div class="hover-zoom d-inline-flex align-items-center justify-content-center bg-white p-3 rounded shadow" style="border: 2px solid #cbd5e1;">
                        <img src="{{ asset('uploads/monogram.png') }}" alt="হেযবুত তওহীদের প্রতীক" class="img-fluid" style="max-height: 350px;">
                    </div>
                    <div class="mt-4">
                        <h4 class="fw-bold text-dark" style="font-family: 'Baloo Da 2', sans-serif;">অফিশিয়াল মনোগ্রাম</h4>
                        <p class="text-secondary small">ঐক্য, শৃঙ্খলা ও সংগ্রামের প্রতীক</p>
                    </div>
                </div>
                <div class="col-lg-7 ps-lg-5">
                    <h2 class="ht-monogram-title mb-4" style="font-size: 32px;">হেযবুত তওহীদের প্রতীক বা মনোগ্রাম</h2>
                    <p class="ht-monogram-text mb-4 text-justify">
                        হেযবুত তওহীদের প্রতীকের মূল আকৃতিটি পবিত্র ক্বাবা শরীফের আদলে অঙ্কিত যার অভ্যন্তরে পবিত্র রওজার গম্বুজ ও পবিত্র কেল্লা-সদৃশ পবিত্র কোর’আন রয়েছে। নিচে রয়েছে অন্যায়ের বিরুদ্ধে যে সংগ্রাম নবী করিম (সা.) করেছেন তার প্রতীক তলোয়ার। মাননীয় এমামুয্যামান যখন কলেজে পড়েন তখন তিনি একটি স্বপ্নে ক্বাবা শরীফের ভেতর ও বাহিরের দৃশ্য দেখেছিলেন। তিনি সেই স্বপ্নের দৃশ্যপটের সাথে মিলিয়ে হেযবুত তওহীদের প্রতিকটি অঙ্কন করেছিলেন। এর মধ্যে পাঁচটি অংশ রয়েছে যা কর্মসূচির পাঁচটি দফাকে তুলে ধরে। সেই পাঁচটি অংশ হচ্ছে কাবা শরিফ, রওজা মোবারক, কোর’আন, হেযবুত তওহীদ ও তরবারি। প্রতীকের ব্যাখ্যা নিচে তুলে ধরা হল।
                    </p>
                </div>
            </div>

            <!-- The Five Pillars Grid -->
            <div class="mt-5">
                <h3 class="ht-monogram-title text-center mb-5" style="font-size: 28px;">মনোগ্রামের পাঁচটি মূল উপাদানের ব্যাখ্যা</h3>
                
                <div class="row g-4">
                    <!-- Element 1: কাবা -->
                    <div class="col-lg-6">
                        <div class="p-4 ht-monogram-card rounded-3 border-start border-secondary border-5 h-100">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="bg-light text-secondary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; min-width: 50px;">
                                    <i class="fas fa-kaaba fa-lg"></i>
                                </div>
                                <h4 class="ht-monogram-card-title mb-0">১. কাবা (ঐক্যের প্রতীক)</h4>
                            </div>
                            <p class="ht-monogram-text text-justify mb-0">
                                কাবা হচ্ছে ঐক্যের প্রতীক। আকীদাগতভাবে বা ধারণাগতভাবে সমস্ত মানবজাতি মূলত এক জাতি। তাদের উৎস এক। তারা একই স্রষ্টার সৃষ্টি। তারা একই পিতামাতা আদম-হাওয়ার সন্তান। কালক্রমে তারা বিভিন্ন জায়গায় ছড়িয়ে ছিটিয়ে পড়ে। ভৌগোলিক এবং আবহাওয়াগত কারণে তাদের রং ভাষার বৈচিত্র্য সৃষ্টি হয়। কিন্তু তারা মূলত একজাতি। এই একক জাতিসত্তাকে স্মরণ করিয়ে দিতে, হাশরের দিনে সবার একত্রিত সমাবেশকে স্মরণ করিয়ে দিতে এবং মানবজাতিকে চূড়ান্তরূপে সত্যের পক্ষে ন্যায়ের পক্ষে ঐক্যবদ্ধ করার জন্য আল্লাহ একটা জায়গা নির্ধারণ করে দেন। সেই জায়গা হল পবিত্র কাবা। এটা হল সমস্ত মানবজাতির ঐক্যবদ্ধ হওয়ার জায়গা। মুসলিম নামক জাতিটি বর্তমানে শতধাবিচ্ছিন্ন হলেও তারা প্রত্যেকে সেই কাবার দিকে ফিরেই পাঁচ ওয়াক্ত সালাহ (নামাজ) কায়েম করে এবং বছরের একটি নির্দিষ্ট সময়ে তাদের সমর্থ ব্যক্তিরা হজ্বের উদ্দেশে সেখানে একত্রিত হয়ে কাবাকে কেন্দ্র করে তাওয়াফ করে। হেযবুত তওহীদ সমগ্র মানবজাতিকে আল্লাহর তওহীদের ভিত্তিতে অর্থাৎ ন্যায়ের পক্ষে সমস্ত অন্যায়ের বিরুদ্ধে ঐক্যবদ্ধ করার জন্য সংগ্রাম করছে। শান্তি প্রতিষ্ঠার জন্য আল্লাহ প্রদত্ত কর্মসূচির প্রথম দফাটিই হচ্ছে এই ঐক্য। হেযবুত তওহীদের প্রতীকের কাবা এই মহত্তম ঐক্যকেই নির্দেশ করে।
                            </p>
                        </div>
                    </div>

                    <!-- Element 2: রসুলাল্লাহর রওজা -->
                    <div class="col-lg-6">
                        <div class="p-4 ht-monogram-card rounded-3 border-start border-secondary border-5 h-100">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="bg-light text-secondary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; min-width: 50px;">
                                    <i class="fas fa-mosque fa-lg"></i>
                                </div>
                                <h4 class="ht-monogram-card-title mb-0">২. রসুলাল্লাহর রওজা (শৃঙ্খলার প্রতীক)</h4>
                            </div>
                            <p class="ht-monogram-text text-justify mb-3">
                                রসুলাল্লাহর রওজা মোবারকের উপরে অবস্থিত গম্বুজ যা রসুলাল্লাহকে নির্দেশ করছে। রসুল পাক (দ.) আল্লাহর হুকুম মোতাবেক উম্মতে মোহাম্মদী নামক জাতিটিকে তৈরি করেছেন। আল্লাহর হুকুমগুলিকে কীভাবে মানবসমাজে বাস্তবায়ন করা হবে সেটার নিয়ম শৃঙ্খলা রসুলাল্লাহ দেখিয়েছেন। কাজেই রসুলুল্লাহ হচ্ছেন উম্মাহর সজাগতা, সতর্কতা ও শৃঙ্খলার প্রতীক, তিনিই সর্বোত্তম অনুকরণীয় অনুসরণীয় আদর্শ। জাতি প্রথমে ঐক্যবদ্ধ হবে ন্যায়ের পক্ষে। ঐক্যবদ্ধ হবে একটি লক্ষ্য নিয়ে আর সেটা হলো পৃথিবীময় মানবজাতির জীবনে শান্তি, ন্যায় ও সুবিচার প্রতিষ্ঠা করা। সেটা প্রতিষ্ঠা করার জন্য জাতির মধ্যে কিছু চারিত্রিক বৈশিষ্ট্য থাকা আবশ্যক। আল্লাহর রসুল কঠোর শিক্ষার মাধ্যমে তাঁর উম্মাহর চরিত্রে যে গুণাবলী প্রতিষ্ঠিত করে গেলেন সেগুলি হচ্ছে এমন-
                            </p>
                            <ul class="ht-monogram-text ps-3 mb-2">
                                <li><strong>ক.</strong> তাঁর উম্মাহ প্রতিটি উপদেশ থেকে শিক্ষা গ্রহণ করবে।</li>
                                <li><strong>খ.</strong> তারা হবে বাধ্য, নিয়ন্ত্রিত এবং নিয়মানুবর্তী।</li>
                                <li><strong>গ.</strong> তারা তাদের মন্দ বা ভুল কাজের জন্য হবে অনুতপ্ত, মর্মযন্ত্রণায় কাতর এবং প্রায়শ্চিত্ত করার জন্য থাকবে সদা প্রস্তুত।</li>
                                <li><strong>ঘ.</strong> তারা তাদের সকল কাজের সুষ্ঠু ও সুন্দর পরিকল্পনা করবে এবং সমস্তকিছু থাকবে সাজানো-গোছানো।</li>
                                <li><strong>ঙ.</strong> তারা শাসন করবে, শাস্তি দিবে।</li>
                            </ul>
                            <p class="ht-monogram-text mt-2 mb-0">এই গুণগুলি দিয়ে রসুলের উম্মাহ থাকবে শৃঙ্খলিত।</p>
                        </div>
                    </div>

                    <!-- Element 3: পবিত্র কোর’আন -->
                    <div class="col-lg-6">
                        <div class="p-4 ht-monogram-card rounded-3 border-start border-secondary border-5 h-100">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="bg-light text-secondary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; min-width: 50px;">
                                    <i class="fas fa-book-open fa-lg"></i>
                                </div>
                                <h4 class="ht-monogram-card-title mb-0">৩. পবিত্র কোর’আন (আনুগত্যের প্রতীক)</h4>
                            </div>
                            <p class="ht-monogram-text text-justify mb-0">
                                পবিত্র কোর’আন যার উপর কোর’আনুল হাকিম কথাটি লিখিত রয়েছে। কোর’আন হল আল্লাহর হুকুমসমূহের সমষ্টি। এই দীনের যাবতীয় হুকুমের, বিধানের মূল ভিত্তি হল কেবল আল্লাহর হুকুম মান্য করার অঙ্গীকার তথা তওহীদ। আল্লাহর সেই হুকুমগুলো, দীনের মূলনীতিগুলো বিধৃত হয়েছে কোর’আনে। এই হুকুমগুলোর আনুগত্য, নীতিগুলোর অনুসরণই হচ্ছে ইসলাম। এজন্য হেযবুত তওহীদের কর্মসূচির তৃতীয় ধারা হচ্ছে আনুগত্য আর প্রতীকের তৃতীয় বিষয়টি হচ্ছে কীসের আনুগত্য করতে হবে তার প্রতীক কোর’আন।
                            </p>
                        </div>
                    </div>

                    <!-- Element 4: হেযবুত তওহীদ -->
                    <div class="col-lg-6">
                        <div class="p-4 ht-monogram-card rounded-3 border-start border-secondary border-5 h-100">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="bg-light text-secondary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; min-width: 50px;">
                                    <i class="fas fa-flag fa-lg"></i>
                                </div>
                                <h4 class="ht-monogram-card-title mb-0">৪. হেযবুত তওহীদ (হেজরতের প্রতীক)</h4>
                            </div>
                            <p class="ht-monogram-text text-justify mb-0">
                                আজকে পৃথিবীময় ইসলামের নামে যে ধর্মটি চালু আছে সেটা আল্লাহর রসুলের প্রকৃত ইসলাম নয়, স্বভাবতই মুসলমান নামক যে জাতিটি রয়েছে তারাও প্রকৃত মুসলিম নয়। আল্লাহর রসুলের প্রকৃত আদর্শ থেকে তারা বহু দূরে সরে গেছে। কীভাবে দীর্ঘ ১৩শ বছরের কাল পরিক্রমায় আল্লাহ-রসুলের প্রকৃত ইসলাম বিকৃত হয়ে বিস্মৃতির অতল গহ্বরে হারিয়ে গেল সেই জ্ঞান এবং প্রকৃত ইসলামের জ্ঞান আল্লাহ মহামান্য এমামুয্যামানকে দান করেছেন। তিনি সেই প্রকৃত ইসলামের জ্ঞানকে মানুষের মাঝে ছড়িয়ে দেওয়ার জন্য হেযবুত তওহীদ আন্দোলনটি প্রতিষ্ঠা করেছেন। কাজেই হেযবুত তওহীদ প্রচলিত বিকৃত ইসলামকে বয়কট করেছে, সকল অন্যায় ও বিকৃতি থেকে হেজরত করেছে। তাই হেযবুত তওহীদ নিজেই মিথ্যাকে প্রত্যাখ্যান তথা হেজরতের প্রতীক।
                            </p>
                        </div>
                    </div>

                    <!-- Element 5: তলোয়ার -->
                    <div class="col-lg-12">
                        <div class="p-4 ht-monogram-card rounded-3 border-start border-secondary border-5 h-100">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="bg-light text-secondary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; min-width: 50px;">
                                    <i class="fas fa-shield-alt fa-lg"></i>
                                </div>
                                <h4 class="ht-monogram-card-title mb-0">৫. তলোয়ার (অন্যায় অবিচারের বিরুদ্ধে সংগ্রামের প্রতীক)</h4>
                            </div>
                            <p class="ht-monogram-text text-justify mb-0">
                                তলোয়ার হচ্ছে অন্যায় অবিচারের বিরুদ্ধে ন্যায় প্রতিষ্ঠার সংগ্রামের প্রতীক, জেহাদের প্রতীক। যুগে যুগে যখন কোনো সমাজে জুলুম রক্তপাত মহামারী আকার ধারণ করে তখন সে অন্যায় অবিচার দূর করার জন্য একদল সত্যনিষ্ঠ মো’মেনের সর্বাত্মক প্রচেষ্টা অত্যাবশ্যক। সেই সর্বাত্মক প্রচেষ্টা মৌখিক প্রচেষ্টা থেকে শুরু করে চূড়ান্ত পর্যায়ে শক্তি প্রয়োগ পর্যন্ত হতে পারে। তবে শক্তি প্রয়োগ কেবল রাষ্ট্রশক্তির কাজ। ব্যক্তি এবং দলগত পর্যায়ে শক্তি প্রয়োগ সম্ভব নয়। প্রতীকের এই পাঁচটি বিষয়ের মধ্যেই হেযবুত তওহীদের কর্মসূচি ও আদর্শকে প্রকাশ করা হয়েছে।
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
