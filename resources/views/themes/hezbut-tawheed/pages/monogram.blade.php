@extends('theme::layouts.app')

@section('title', 'হেযবুত তওহীদের প্রতীক বা মনোগ্রাম - হেযবুত তওহীদ')

@section('content')

    {{-- ১. হিরো ব্যানার --}}
    @include('theme::partials.hero_banner', [
        'title' => 'হেযবুত তওহীদের প্রতীক বা মনোগ্রাম',
        'subtitle' => 'হেযবুত তওহীদের অফিশিয়াল মনোগ্রামের উপাদান ও এর অন্তর্নিহিত দর্শন।',
        'badge_text' => 'পরিচিতি',
        'badge_icon' => 'fas fa-shield-alt'
    ])

    <section class="py-5 bg-off-white page-body" style="font-family: 'Baloo Da 2', sans-serif;">
        <div class="container">
            
            {{-- ২. পরিচিতি সেকশন --}}
            <div class="bg-white rounded-4 p-4 p-md-5 shadow-sm border mb-5">
                <div class="row align-items-center">
                    <div class="col-lg-5 text-center mb-4 mb-lg-0">
                        <div class="hover-zoom d-inline-flex align-items-center justify-content-center bg-light p-4 rounded-4 shadow-sm border" style="border-color: #cbd5e1 !important; transition: all 0.3s ease;">
                            <img src="{{ asset('uploads/monogram.png') }}" alt="হেযবুত তওহীদের প্রতীক" class="img-fluid" style="max-height: 320px; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));">
                        </div>
                        <div class="mt-4">
                            <h4 class="fw-bold text-success mb-1" style="font-family: var(--font-bengali); color: #006A4E !important;">অফিশিয়াল মনোগ্রাম</h4>
                            <p class="text-secondary small fw-semibold">ঐক্য, শৃঙ্খলা ও সংগ্রামের প্রতীক</p>
                        </div>
                    </div>
                    <div class="col-lg-7 ps-lg-5">
                        <h2 class="fw-bold mb-4" style="font-family: var(--font-bengali); color: #006A4E; font-size: 2rem;">মনোগ্রামের প্রেক্ষাপট ও পরিচিতি</h2>
                        <p class="text-secondary mb-3" style="line-height: 1.8; text-align: justify; font-size: 1.05rem;">
                            হেযবুত তওহীদের প্রতীকের মূল আকৃতিটি পবিত্র ক্বাবা শরীফের আদলে অঙ্কিত যার অভ্যন্তরে পবিত্র রওজার গম্বুজ ও পবিত্র কেল্লা-সদৃশ পবিত্র কোর’আন রয়েছে। নিচে রয়েছে অন্যায়ের বিরুদ্ধে যে সংগ্রাম নবী করিম (সা.) করেছেন তার প্রতীক তলোয়ার।
                        </p>
                        <p class="text-secondary mb-0" style="line-height: 1.8; text-align: justify; font-size: 1.05rem;">
                            মাননীয় এমামুযযামান যখন কলেজে পড়েন তখন তিনি একটি স্বপ্নে ক্বাবা শরীফের ভেতর ও বাহিরের দৃশ্য দেখেছিলেন। তিনি সেই স্বপ্নের দৃশ্যপটের সাথে মিলিয়ে হেযবুত তওহীদের প্রতিকটি অঙ্কন করেছিলেন। এর মধ্যে পাঁচটি অংশ রয়েছে যা আন্দোলনের কর্মসূচির পাঁচটি দফাকে নির্দেশ করে। সেই পাঁচটি অংশ হচ্ছে: <strong>কাবা শরিফ</strong>, <strong>রওজা মোবারক</strong>, <strong>কোর’আন</strong>, <strong>হেযবুত তওহীদ</strong> ও <strong>তরবারি</strong>।
                        </p>
                    </div>
                </div>
            </div>

            {{-- ৩. মনোগ্রামের পাঁচটি মূল উপাদানের ব্যাখ্যা --}}
            <div class="my-5">
                <h3 class="section-title-center-premium mb-5" style="font-family: var(--font-bengali); color: #006A4E;">মনোগ্রামের পাঁচটি মূল উপাদানের ব্যাখ্যা</h3>
                
                <div class="row g-4">
                    <!-- Element 1: কাবা -->
                    <div class="col-lg-6">
                        <div class="family-member-card p-4 h-100 d-flex flex-column" style="border-left: 5px solid #006A4E !important;">
                            <div class="text-center p-2 bg-light rounded-3 mb-3 border" style="overflow: hidden; background-color: #f8f9fa;">
                                <img src="{{ asset('/uploads/pages/monogram_kaba.svg') }}" alt="১. কাবা" class="img-fluid rounded" style="max-height: 130px; object-fit: contain;" />
                            </div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <h4 class="fw-bold mb-0" style="font-family: var(--font-bengali); color: #006A4E; font-size: 1.25rem;">১. কাবা (ঐক্যের প্রতীক)</h4>
                            </div>
                            <p class="text-secondary mb-0" style="line-height: 1.7; text-align: justify; font-size: 0.95rem;">
                                কাবা হচ্ছে ঐক্যের প্রতীক। আকীদাগতভাবে বা ধারণাগতভাবে সমস্ত মানবজাতি মূলত এক জাতি। তাদের উৎস এক। তারা একই স্রষ্টার সৃষ্টি। তারা একই পিতামাতা আদম-হাওয়ার সন্তান। শান্তি প্রতিষ্ঠার জন্য আল্লাহ প্রদত্ত কর্মসূচির প্রথম দফাটিই হচ্ছে এই ঐক্য। হেযবুত তওহীদ সমগ্র মানবজাতিকে আল্লাহর তওহীদের ভিত্তিতে অর্থাৎ ন্যায়ের পক্ষে সমস্ত অন্যায়ের বিরুদ্ধে ঐক্যবদ্ধ করার জন্য সংগ্রাম করছে। হেযবুত তওহীদের প্রতীকের কাবা এই মহত্তম ঐক্যকেই নির্দেশ করে।
                            </p>
                        </div>
                    </div>

                    <!-- Element 2: রসুলাল্লাহর রওজা -->
                    <div class="col-lg-6">
                        <div class="family-member-card p-4 h-100 d-flex flex-column" style="border-left: 5px solid #D4AF37 !important;">
                            <div class="text-center p-2 bg-light rounded-3 mb-3 border" style="overflow: hidden; background-color: #f8f9fa;">
                                <img src="{{ asset('/uploads/pages/monogram_raoza.svg') }}" alt="২. রসুলাল্লাহর রওজা" class="img-fluid rounded" style="max-height: 130px; object-fit: contain;" />
                            </div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <h4 class="fw-bold mb-0" style="font-family: var(--font-bengali); color: #006A4E; font-size: 1.25rem;">২. রসুলাল্লাহর রওজা (শৃঙ্খলার প্রতীক)</h4>
                            </div>
                            <p class="text-secondary mb-3" style="line-height: 1.7; text-align: justify; font-size: 0.95rem;">
                                রসুলাল্লাহর রওজা মোবারকের উপরে অবস্থিত গম্বুজ যা রসুলাল্লাহকে নির্দেশ করছে। রসুলুল্লাহ হচ্ছেন উম্মাহর সজাগতা, সতর্কতা ও শৃঙ্খলার প্রতীক। রসুল পাক (দ.) আল্লাহর হুকুম মোতাবেক উম্মতে মোহাম্মদী নামক জাতিটিকে তৈরি করেছেন। আল্লাহর রসুল কঠোর শিক্ষার মাধ্যমে তাঁর উম্মাহর চরিত্রে যে গুণাবলী প্রতিষ্ঠিত করে গেলেন সেগুলি হচ্ছে:
                            </p>
                            <ul class="text-secondary ps-3 mb-0" style="font-size: 0.9rem; line-height: 1.6;">
                                <li class="mb-1"><strong>ক.</strong> তাঁর উম্মাহ প্রতিটি উপদেশ থেকে শিক্ষা গ্রহণ করবে।</li>
                                <li class="mb-1"><strong>খ.</strong> তারা হবে বাধ্য, নিয়ন্ত্রিত এবং নিয়মানুবর্তী।</li>
                                <li class="mb-1"><strong>গ.</strong> তারা তাদের মন্দ বা ভুল কাজের জন্য হবে অনুতপ্ত এবং মর্মযন্ত্রণায় কাতর।</li>
                                <li class="mb-1"><strong>ঘ.</strong> তারা তাদের সকল কাজের সুষ্ঠু ও সুন্দর পরিকল্পনা করবে।</li>
                                <li><strong>ঙ.</strong> তারা শাসন করবে, অন্যায় দূর করবে।</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Element 3: পবিত্র কোর’আন -->
                    <div class="col-lg-6">
                        <div class="family-member-card p-4 h-100 d-flex flex-column" style="border-left: 5px solid #006A4E !important;">
                            <div class="text-center p-2 bg-light rounded-3 mb-3 border" style="overflow: hidden; background-color: #f8f9fa;">
                                <img src="{{ asset('/uploads/pages/monogram_quran.svg') }}" alt="৩. পবিত্র কোর’আন" class="img-fluid rounded" style="max-height: 130px; object-fit: contain;" />
                            </div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <h4 class="fw-bold mb-0" style="font-family: var(--font-bengali); color: #006A4E; font-size: 1.25rem;">৩. পবিত্র কোর’আন (আনুগত্যের প্রতীক)</h4>
                            </div>
                            <p class="text-secondary mb-0" style="line-height: 1.7; text-align: justify; font-size: 0.95rem;">
                                পবিত্র কোর’আন যার উপর কোর’আনুল হাকিম কথাটি লিখিত রয়েছে। কোর’আন হল আল্লাহর হুকুমসমূহের সমষ্টি। এই দীনের যাবতীয় হুকুমের, বিধানের মূল ভিত্তি হল কেবল আল্লাহর হুকুম মান্য করার অঙ্গীকার তথা তওহীদ। এই হুকুমগুলোর আনুগত্য, নীতিগুলোর অনুসরণই হচ্ছে ইসলাম। এজন্য হেযবুত তওহীদের কর্মসূচির তৃতীয় ধারা হচ্ছে আনুগত্য আর প্রতীকের তৃতীয় বিষয়টি হচ্ছে কীসের আনুগত্য করতে হবে তার প্রতীক কোর’আন।
                            </p>
                        </div>
                    </div>

                    <!-- Element 4: হেযবুত তওহীদ -->
                    <div class="col-lg-6">
                        <div class="family-member-card p-4 h-100 d-flex flex-column" style="border-left: 5px solid #D4AF37 !important;">
                            <div class="text-center p-2 bg-light rounded-3 mb-3 border" style="overflow: hidden; background-color: #f8f9fa;">
                                <img src="{{ asset('/uploads/pages/monogram_ht.svg') }}" alt="৪. হেযবুত তওহীদ" class="img-fluid rounded" style="max-height: 130px; object-fit: contain;" />
                            </div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <h4 class="fw-bold mb-0" style="font-family: var(--font-bengali); color: #006A4E; font-size: 1.25rem;">৪. হেযবুত তওহীদ (হেজরতের প্রতীক)</h4>
                            </div>
                            <p class="text-secondary mb-0" style="line-height: 1.7; text-align: justify; font-size: 0.95rem;">
                                দীর্ঘ ১৩শ বছরের কাল পরিক্রমায় আল্লাহ-রসুলের প্রকৃত ইসলাম বিকৃত হয়ে বিস্মৃতির অতল গহ্বরে হারিয়ে গিয়েছিল। আল্লাহ মহামান্য এমামুযযামানকে সেই প্রকৃত ইসলামের জ্ঞান দান করেছেন। তিনি সেই প্রকৃত ইসলামের জ্ঞানকে মানুষের মাঝে ছড়িয়ে দেওয়ার জন্য হেযবুত তওহীদ আন্দোলনটি প্রতিষ্ঠা করেছেন। হেযবুত তওহীদ প্রচলিত বিকৃত ইসলামকে বয়কট করেছে, সকল অন্যায় ও বিকৃতি থেকে হেজরত করেছে। তাই হেযবুত তওহীদ নিজেই মিথ্যাকে প্রত্যাখ্যান তথা হেজরতের প্রতীক।
                            </p>
                        </div>
                    </div>

                    <!-- Element 5: তলোয়ার -->
                    <div class="col-lg-12">
                        <div class="family-member-card p-4 h-100 d-flex flex-column" style="border-left: 5px solid #006A4E !important;">
                            <div class="text-center p-2 bg-light rounded-3 mb-3 border" style="overflow: hidden; background-color: #f8f9fa;">
                                <img src="{{ asset('/uploads/pages/monogram_sword.svg') }}" alt="৫. তলোয়ার" class="img-fluid rounded" style="max-height: 140px; object-fit: contain;" />
                            </div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <h4 class="fw-bold mb-0" style="font-family: var(--font-bengali); color: #006A4E; font-size: 1.25rem;">৫. তলোয়ার (অন্যায় অবিচারের বিরুদ্ধে সংগ্রামের প্রতীক)</h4>
                            </div>
                            <p class="text-secondary mb-0" style="line-height: 1.7; text-align: justify; font-size: 0.95rem;">
                                তলোয়ার হচ্ছে অন্যায় অবিচারের বিরুদ্ধে ন্যায় প্রতিষ্ঠার সংগ্রামের প্রতীক, জেহাদের প্রতীক। যুগে যুগে যখন কোনো সমাজে জুলুম রক্তপাত মহামারী আকার ধারণ করে তখন সে অন্যায় অবিচার দূর করার জন্য একদল সত্যনিষ্ঠ মানুষের সর্বাত্মক প্রচেষ্টা অত্যাবশ্যক। সেই সর্বাত্মক প্রচেষ্টা মৌখিক প্রচেষ্টা থেকে শুরু করে চূড়ান্ত পর্যায়ে সংগ্রাম পর্যন্ত হতে পারে। প্রতীকের এই পাঁচটি বিষয়ের মধ্যেই হেযবুত তওহীদের কর্মসূচি ও আদর্শকে প্রকাশ করা হয়েছে।
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
