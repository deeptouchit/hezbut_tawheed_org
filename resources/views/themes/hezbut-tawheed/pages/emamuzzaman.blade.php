@extends('theme::layouts.app')

@section('title', 'প্রতিষ্ঠাতা এমামুযযামান জনাব মোহাম্মদ বায়াজীদ খান পন্নী - হেযবুত তওহীদ')

@section('content')

{{-- ১. হিরো ব্যানার --}}
@include('theme::partials.hero_banner', [
'title' => 'মানুষ আজ যে সভ্যতার বড়াই করছে সত্যি কি এটা সভ্যতা? আমি বলবো, না। এটা সভ্যতা নয়।',
'subtitle' => ' — মাননীয় এমামুযযামান জনাব মোহাম্মদ বায়াজীদ খান পন্নী',
'badge_text' => 'আন্দোলনের প্রতিষ্ঠাতা',
'badge_icon' => 'fas fa-user-tie'
])

<section class="py-5 page-body">
    <div class="container">

        <!-- {{-- ২. বড় বাণী / কোট সেকশন --}}
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <div class="bio-quote-box p-4 p-md-5 text-center">
                    <p class="lead fw-bold mb-3" style="font-size: 1.4rem; line-height: 1.8; font-family: 'Baloo Da 2', sans-serif;">
                        "মানুষ আজ যে সভ্যতার বড়াই করছে সত্যি কি এটা সভ্যতা? আমি বলবো, না। এটা সভ্যতা নয়।"
                    </p>
                    <div class="fw-bold text-success" style="font-size: 1.15rem;">
                        — মাননীয় এমামুযযামান জনাব মোহাম্মদ বায়াজীদ খান পন্নী
                    </div>
                </div>
            </div>
        </div> -->

        {{-- ৩. জন্ম ও পরিচিতি সেকশন --}}
        <div class="row align-items-center mb-5 pb-4">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <div class="hover-zoom shadow-sm rounded-4 overflow-hidden bg-white p-3 border">
                    <img src="{{ asset('/uploads/about/bayazid-khan-panni.jpg') }}" alt="জনাব মোহাম্মদ বায়াজীদ খান পন্নী" class="img-fluid rounded-3 w-100" style="max-height: 480px; object-fit: cover;" />
                    <div class="mt-3 fw-bold text-success fs-5">জনাব মোহাম্মদ বায়াজীদ খান পন্নী</div>
                    <div class="text-muted small">এমামুযযামান ও হেযবুত তওহীদের প্রতিষ্ঠাতা</div>
                </div>
            </div>
            <div class="col-lg-8 ps-lg-5">
                <h3 class="section-title-premium">এমামুযযামানের জন্ম</h3>
                <p class="lead fw-bold text-success mb-3" style="font-size: 1.15rem; line-height: 1.7;">
                    হেযবুত তওহীদের প্রতিষ্ঠাতা এ যুগের পরশপাথর এমামুযযামান জনাব মোহাম্মদ বায়াজীদ খান পন্নী।
                </p>
                <p class="text-secondary mb-4" style="line-height: 1.8; font-size: 1.02rem; text-align: justify;">
                    মাননীয় এমামুযযামান করটিয়া, টাঙ্গাইলের ঐতিহ্যবাহী পন্নী পরিবারে ১৫ শাবান ১৩৪৩ হেজরী, মোতাবেক ১৯২৫ সনের ১১ মার্চ শেষ রাতে জন্মগ্রহণ করেন। তাঁর শিক্ষাজীবন শুরু হয় রোকেয়া উচ্চ মাদ্রাসায়, দু’বছর তিনি সেখানে পড়াশুনা করেন। তারপর এইচ. এম. ইনস্টিটিউশন থেকে ১৯৪২ সনে মেট্রিকুলেশন পাশ করেন। এরপর সা’দাত কলেজে কিছুদিন পড়াশুনা করেন। এ সবগুলো প্রতিষ্ঠানেরই প্রতিষ্ঠাতা ছিলেন তাঁরই পূর্বপুরুষ মোহাম্মদ ওয়াজেদ আলী খান পন্নী ওরফে চান মিয়া যিনি ব্রিটিশ বিরোধী আন্দোলনে নিজের প্রায় সকল সম্পদ ব্যয় করে ফেলেছিলেন এমন কি জেলও খেটেছিলেন। এরপর মাননীয় এমামুযযামান ভর্তি হন বগুড়ার আজিজুল হক কলেজে। তাঁর খালার বাড়ি বগুড়ার নওয়াব প্যালেসে থেকে প্রথম বর্ষের পাঠ সমাপ্ত করেন, দ্বিতীয় বর্ষে কলকাতার ইসলামিয়া কলেজে ভর্তি হন। সেখান থেকে তিনি উচ্চ মাধ্যমিক সমাপ্ত করেন।
                </p>

                <div class="p-3 bg-white border border-success border-opacity-25 rounded-3 d-flex align-items-center gap-3">
                    <div class="flex-shrink-0 bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 46px; height: 46px;">
                        <i class="fas fa-history"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-success">আভিজাত্য ও আদর্শের মেলবন্ধন</h6>
                        <p class="text-secondary small mb-0">করেরানি ও পন্নী রাজপরিবারের ঐতিহাসিক ঐতিহ্য এবং নিঃস্বার্থ সমাজসেবার আদর্শের উত্তরাধিকারী ছিলেন তিনি।</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ৪. পন্নী পরিবারের সংক্ষিপ্ত ইতিহাস --}}
        <div class="bg-white rounded-4 p-4 p-md-5 shadow-sm border mb-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-4 text-center">
                    <img src="{{ asset('/uploads/pages/Repeat-Grid-6.png') }}" alt="পন্নী পরিবারের সংক্ষিপ্ত ইতিহাস" class="img-fluid" />
                </div>
                <div class="col-lg-8">
                    <h3 class="section-title-premium mb-4" style="font-family: var(--font-bengali);">পন্নী পরিবারের সংক্ষিপ্ত ইতিহাস</h3>
                    <p class="text-secondary mb-3" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                        মাননীয় এমামুযযামানের পরিবারের ইতিহাস অতি প্রাচীন। রসুলাল্লাহর প্রিয় সাহাবী এবং জামাতা, এসলামের চতুর্থ খলিফা আলী (রা.) এর বংশধারায় পন্নী বংশের উদ্ভব। তাঁর পূর্বপুরুষগণ সমগ্র পৃথিবীর বুকে আল্লাহর দেওয়া জীবনব্যবস্থা প্রতিষ্ঠা করার লক্ষ্যে উম্মতে মোহাম্মদীর সঙ্গে আরবভূমি থেকে বহির্গত হয়েছিলেন। ধারণা করা হয় ৬৫২ সনে রসুলাল্লাহর সাহাবি আহনাফ ইবনে কায়েস (রা.) এর নেতৃত্বে পরিচালিত খোরাসান অভিযানের সেনানী হিসাবে তাঁরা আফগানিস্তানের হিরাত অঞ্চলে আগমন করেন এবং এখানে বাস করতে থাকেন।
                    </p>
                    <p class="text-secondary mb-3" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                        পরবর্তীতে এসলাম যখন ভারতবর্ষে প্রবেশ করে, এই বংশেরই এক ব্যক্তি দিল্লিতে আগমন করেন। ততদিনে আল্লাহর দেওয়া প্রকৃত এসলাম বিকৃত হয়ে এর মধ্যে বিকৃত অাধ্যাত্মবাদ বা সুফিবাদের প্রভাব বৃদ্ধি পেয়েছে। এমামুযযামানের পূর্বপুরুষগণের মধ্যেও জন্ম নেন অনেক আধ্যাত্মিক সাধক, সুফি ও দরবেশ যার মধ্যে ভারতের বিখ্যাত সুফি সাধক সৈয়দ খাজা মোহাম্মদ হোসাইনী গেসুদারাজ বন্দে নেওয়াজ (র.) অন্যতম। তিনি ধর্মপ্রচারের উদ্দেশ্যে দীর্ঘদিন আফগানিস্তানের বিভিন্ন এলাকায় অবস্থান করেছিলেন। এ সময় তিনি কারলানী (মূল গোত্র কাকার) নামক পাঠান গোত্রে বিবাহ করেন। এই কারণে পরবর্তীতে তার বংশধরগণ তাদের নামের শেষে কারলানী ব্যবহার করতেন। কারলানী স্ত্রীর গর্ভে সৈয়দ খাজা মোহাম্মদ হোসাইনী (র.) এর একটি পুত্র জন্মগ্রহণ করে যার নাম পান্নী (উচ্চারণভেদে হান্নি)। তার বংশধরেরা নামের শেষে কাররানি বা পন্নী দুটোই ব্যবহার করতেন।
                    </p>
                    <p class="text-secondary mb-3" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                        এ বংশেই জন্ম নেন তাজ খান কররানি যিনি ষোড়শ শতাব্দীতে বঙ্গভূমিতে কররানি রাজবংশের পত্তন করেন। প্রবাদপ্রতিম বীর যোদ্ধা কালাপাহাড় ছিলেন তারই সেনাপতি। বাংলা, বিহার, গৌড়, উড়িষ্যা, ভারতের উত্তর প্রদেশ পর্যন্ত রাজ্য বিস্তৃত করেন কররানি বংশীয় শাসকবর্গ। তারা নিজ নামে খোতবা প্রদান ও মুদ্রা প্রবর্তন করেন এবং পুরো ভারতবর্ষ মোগল সম্রাট আকবরের প্রভাবাধীন থাকা সত্ত্বেও দাউদ খান কররানি নিজেকে বাদশাহ ঘোষণা দিয়ে দিল্লির সঙ্গে যুদ্ধের সূত্রপাত ঘটান। ১৫৭২-১৫৭৬ পর্যন্ত বার বার মোগল বাহিনী বাংলা আক্রমণ করে কিন্তু একবারও নিরঙ্কুশ জয়লাভ করতে পারে নি, একবার আপাত জয়ী হলেও কিছুদিন বাদেই দাউদ খান কররানি তা পুনরুদ্ধার করেন। অতঃপর সাতমাসের রক্তক্ষয়ী রাজমহলের যুদ্ধে দাউদ খান কররানি দেশের স্বাধীনতা রক্ষার জন্য জীবন দিয়ে বাংলার শেষ স্বাধীন সুলতান হিসাবে ইতিহাসের পাতায় আশ্রয় নেন। কররানি রাজবংশের অধীনে যে জমিদারগণ বাস করতেন তারা ইতিহাসে বারভূঁইয়া নামে খ্যাত। তাদের প্রতিরোধের কারণে মোগল সাম্রাজ্য এ অঞ্চলে পূর্ণাঙ্গরূপে শাসন প্রতিষ্ঠা করতে সক্ষম হন নি দীর্ঘ দিন যাবত। ১৭১৬ পর্যন্ত সাঁইত্রিশজন মোগল সুবাদার বাংলায় শাসন করেন, তারা কেউই শান্তিপূর্ণরূপে শাসন করতে সক্ষম হন নি।
                    </p>
                    <p class="text-secondary mb-0" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                        তাজখান কররানির ভাই সুলায়মান খান কররানি তার শাসনামলে জনৈক সুফি সাধক আলী শাহান শাহ্ বাবা আদম কাশ্মিরী (র.)-কে ধর্মপ্রচারের উদ্দেশ্যে টাঙ্গাইল জেলার জায়গিরদার নিয়োগ দান করলে তিনি এই অঞ্চলে এসে বসবাস শুরু করেন; এলাকাটি আতিয়া পরগনা বলে খ্যাত হয়, কারণ আতা শব্দের অর্থ দান। পরবর্তীতে বাবা আদম কাশ্মিরী সম্রাট জাহাঙ্গীরকে পরামর্শ দেন কররানি বংশীয় বায়াজীদ খান পন্নীর সন্তান সাঈদ খান পন্নীকে আতিয়া পরগণার শাসনকর্তা হিসেবে নিয়োগ দান করার জন্য, সম্রাট জাহাঙ্গীরও সাঈদ খান পন্নীর গুণমুগ্ধ ছিলেন বলে সানন্দে তিনি এ প্রস্তাবে রাজি হন। এই সাঈদ খান পন্নীই করটিয়ার জমিদার পরিবারের প্রতিষ্ঠাতা। ১৬০৯ সালে বাবা আদম কাশ্মিরীর কবরের সন্নিকটে আতিয়া মসজিদ নির্মাণ করেন যা বাংলাদেশের অন্যতম প্রত্নতাত্ত্বিক নিদর্শন; বর্তমানে বাংলাদেশে প্রচলিত বাংলাদেশ ব্যাংক কর্তৃক মুদ্রিত ১০ (দশ) টাকা মূল্যমানের নোটের একপার্শ্বে আতিয়া মসজিদের ছবি রয়েছে। পরবর্তী সময়ে সমগ্র ভারতবর্ষই ব্রিটিশ শাসনের অধীন হয়। তখনও টাঙ্গাইল-ময়মনসিংহ-বগুড়া অঞ্চলে এ ক্ষয়িষ্ণু রাজপরিবারের জমিদারি বজায় থাকে। প্রজাহিতৈষীতা ও ধর্মপরায়ণতার জন্য তারা ছিলেন অত্যন্ত জনপ্রিয়। ব্রিটিশ শাসনের বিরুদ্ধে সংগ্রাম করে বাঙালি জাতিকে পরাধীনতার শৃঙ্খলমুক্ত করতে তারা ছিলেন নিবেদিতপ্রাণ। এক কথায় মাননীয় এমামুযযামানের পরিবারের সঙ্গে আবহমান বাংলার ইতিহাস, ঐতিহ্য, শিক্ষা, সংস্কৃতি এক সূত্রে গাঁথা।
                    </p>
                </div>
            </div>
        </div>

        {{-- ৫. বেড়ে ওঠা ও জীবনধারা --}}
        <div class="row align-items-center mb-5 py-4">
            <div class="col-lg-8 pe-lg-5 order-2 order-lg-1">
                <h3 class="section-title-premium">মাননীয় এমামুযযামানের বেড়ে ওঠা</h3>
                <p class="text-secondary mb-4" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                    পারিবারিক ধারা বজায় রেখে মাননীয় এমামুযযামানও ছিলেন আধ্যাত্মিক ও মানবিক চরিত্রে বলীয়ান এক মহান পুরুষ, যাঁর ঘটনাবহুল ৮৬ বছরের জীবনে একটি মিথ্যা বলার বা অপরাধ সংঘটনের দৃষ্টান্ত নেই। তাঁর চরিত্রের সবচেয়ে উল্লেখযোগ্য বিষয় হলো তাঁর নিরহংকার ব্যক্তিত্ব। তিনি অভিজাত পরিবারের সন্তান হয়েও সারা জীবন কাটিয়েছেন সাধারণ মানুষের সঙ্গে।
                </p>
                <p class="text-secondary mb-4" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                    তাঁর পিতৃনিবাস টাঙ্গাইল করটিয়া জমিদারবাড়ির দাউদমহল। পিতা মোহাম্মদ মেহেদী আলী খান পন্নী, পিতামহ সুফি সাধক আধ্যাত্মিক পুরুষ মোহাম্মদ হায়দার আলী খান পন্নী। ঢাকা বিশ্ববিদ্যালয়ের প্রতিষ্ঠাতা ধনবাড়ির জমিদার নবাব নওয়াব আলী চৌধরী ছিলেন এমামুযযামানের নানা।
                </p>
            </div>
            <div class="col-lg-4 text-center mb-4 mb-lg-0 order-1 order-lg-2">
                <div class="hover-zoom shadow-sm rounded-4 overflow-hidden bg-white p-3 border">
                    <img src="{{ asset('/uploads/pages/Emamuzzaman.jpg-170x300.png') }}" alt="মাননীয় এমামুযযামানের বেড়ে ওঠা" class="img-fluid rounded-3 w-100" style="max-height: 380px; object-fit: contain;" />
                </div>
            </div>
        </div>

        {{-- ৫.৫ ব্রিটিশ বিরোধী আন্দোলন --}}
        <div class="row align-items-center mb-5 py-4 bg-light rounded-4 p-4 border">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <div class="hover-zoom shadow-sm rounded-4 overflow-hidden bg-white p-3 border">
                    <img src="{{ asset('/uploads/pages/emamuzzaman-old-2-216x300.png') }}" alt="ব্রিটিশ বিরোধী আন্দোলন" class="img-fluid rounded-3 w-100" style="max-height: 380px; object-fit: contain;" />
                </div>
            </div>
            <div class="col-lg-8 ps-lg-5">
                <h3 class="section-title-premium">ব্রিটিশ বিরোধী আন্দোলন</h3>
                <p class="text-secondary mb-4" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                    কলকাতায় শিক্ষালাভের সময় ভারত উপমহাদেশ ছিল ব্রিটিশ শাসনের বিরুদ্ধে সংগ্রামে উত্তাল। তরুণ এমামুযযামানও এ সংগ্রামে ঝাঁপিয়ে পড়েন। এই সুবাদে তিনি ব্রিটিশ বিরোধী সংগ্রামের বহু কিংবদন্তী নেতার সাহচর্য লাভ করেন। যাঁদের মধ্যে মহাত্মা গান্ধী, কায়েদে আযম মোহাম্মদ আলী জিন্নাহ্, অরবিন্দ ঘোষ, শহীদ হোসেন সোহরাওয়ার্দী, মাওলানা সাইয়্যেদ আবুল আলা মওদুদী অন্যতম।
                </p>
                <p class="text-secondary mb-4" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                    এমামুযযামান যোগ দিয়েছিলেন আল্লামা এনায়েত উল্লাহ খান আল মাশরেকী প্রতিষ্ঠিত ‘খাকসার’ আন্দোলনে। আন্দোলনের প্রয়োজনে তিনি ফুটপাতে কমলার ব্যবসাও করেছেন। ছাত্র বয়সে একজন সাধারণ সদস্য হিসেবে যোগদান করেও তিনি দ্রুত জ্যেষ্ঠ নেতাদের ছাড়িয়ে পূর্ববাংলার কমান্ডারের পদ লাভ করেন। মাত্র ২২ বছর বয়সে দুঃসাহসী কর্মকাণ্ড ও সহজাত নেতৃত্বের গুণে তিনি ‘সালার-এ-খাস হিন্দ’ মনোনীত হন। দেশ বিভাগের অল্পদিন পর তিনি বাংলাদেশে (তদানিন্তন পূর্বপাকিস্তান) নিজ গ্রামে প্রত্যাবর্তন করেন এবং রাজনীতির সংস্রব থেকে বিচ্ছিন্ন হয়ে নিরিবিলি জীবনযাপন আরম্ভ করেন। বিভিন্ন ব্যবসা বাণিজ্য কোরে অবসর সময় পার করতে থাকেন, যদিও কোন ব্যবসাাতেই তিনি সফলতার মুখ দেখেন নি।
                </p>
            </div>
        </div>

        {{-- ৬. জন্মগত শিকারী সেকশন --}}
        <div class="row align-items-center mb-5 py-4">
            <div class="col-lg-8 pe-lg-5 order-2 order-lg-1">
                <h3 class="section-title-premium">জন্মগত শিকারী</h3>
                <p class="text-secondary mb-4" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                    তিনি ছিলেন একজন জন্মগত শিকারি; শিকারের নেশা তাঁর রক্তে মিশে ছিল। তাঁর পোষা প্রাণি ছিল একটি চিতাবাঘ। শিশুকালে দাদার কোলে মাথা রেখে শিকারের গল্প শুনে ঘুমিয়েছেন। দাদার কাছেই বন্দুকের হাতেখড়ি। প্রথম শিকার পাখি, শিকারসঙ্গী সাদত কলেজের অধ্যক্ষ প্রিন্সিপাল ইব্রাহিম খাঁ-এর ছেলে। ১৪/১৫ বছর বয়সে বাবা ও চাচার সঙ্গে শিকারে গিয়ে গোড়াই নদী থেকে একটি কুমির শিকার করেন। হিংস্র প্রাণির মধ্যে এটিই তাঁর প্রথম শিকার।
                </p>
                <p class="text-secondary mb-4" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                    এরপর থেকে সুযোগ পেলেই রায়ফেল হাতে চলে যেতেন দেশের বিভিন্ন বনাঞ্চলে। শিকারের লোমহর্ষক অভিজ্ঞতা নিয়ে পরে তিনি ‘বাঘ-বন-বন্দুক’ নামক একটি বই লেখেন। বইটি পড়ে অধ্যাপক মুনির চৌধুরী মন্তব্য করেছিলেন: “বাঘ-বন-বন্দুক এক উপেক্ষিত এবং অনাস্বাদিত জগতের যাবতীয় রোমাঞ্চ ও উৎকন্ঠাকে এমন সরসরূপে উপস্থিত করেছে যে পঞ্চমুখে আমি তার তারিফ করতে কুণ্ঠিত নই।…আমি বিশেষ করে মনে করি এই বই আমাদের দশম কি দ্বাদশ শ্রেণীর দ্রুতপাঠের গ্রন্থরূপে গৃহিত হওয়া উচিত।”
                </p>
            </div>
            <div class="col-lg-4 text-center mb-4 mb-lg-0 order-1 order-lg-2">
                <img src="{{ asset('/uploads/pages/emamuzzaman-1-216x300.png') }}" alt="জন্মগত শিকারী" class="img-fluid" />
            </div>
        </div>

        {{-- ৭. রাজনীতির মাঠে এমামুযযামান --}}
        <div class="bg-light rounded-4 p-4 p-md-5 border mb-5">
            <div class="row">
                <div class="col-12">
                    <h3 class="section-title-premium mb-4" style="font-family: var(--font-bengali);">রাজনীতির মাঠে এমামুযযামান</h3>

                    <p class="text-secondary mb-3" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                        ১৯৪৭ সনে পাকিস্তানের অভ্যুদয়ের পর পাকিস্তান সরকার পূর্ব ও পশ্চিম পাকিস্তানে ব্রিটিশদের রেখে যাওয়া গণতান্ত্রিক ব্যবস্থাকে রাজনৈতিকভাবে প্রয়োগ কোরে জনগণকে এর সাথে খাপ খাওয়ানোর চেষ্টা করতে লাগলো। ইউরোপে উদ্ভূত এবং বিকশিত এই গণতান্ত্রিক ব্যবস্থাকে ব্রিটিশ শাসকরা জোর কোরে প্রাচ্যদেশীয় উপনিবেশগুলিতে প্রয়োগ করার চেষ্টা চালিয়েছিল, যদিও এটি এদেশের মানুষের আর্থ-সামাজিক অবস্থা, ধর্মবিশ্বাস, মনোবৃত্তি ও ধ্যান-ধারণার সাথে মোটেই সামঞ্জস্যপূর্ণ ছিল না। এটি উপলব্ধি করতে ব্যর্থ হয়ে পাকিস্তান সরকারও একই নিষ্ফল প্রচেষ্টা চালিয়ে গেল। ফলে স্বভাবতই নিত্য নতুন সমস্যার উদ্ভব হতে লাগল এবং এই নৈরাজ্যময় পরিস্থিতিতে একের পর এক সামরিক অভ্যুত্থান আর সশস্ত্র উপায়ে ক্ষমতার হাতবদল ঘটে চলল। এমামুযযামান এই রাজনৈতিক কর্মকাণ্ডে সম্পৃক্ত হন নি।
                    </p>

                    <p class="text-secondary mb-4" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                        পঞ্চাশের দশকের মাঝামাঝিতে এসে তিনি হোমিওপ্যাথি চিকিৎসার প্রতি আকৃষ্ট হন এবং দাদার উৎসাহে টাঙ্গাইল হোমিও মেডিক্যাল কলেজে ভর্তি হন। ১৯৫৭ সনে হোমিওপ্যাথিতে ডিগ্রী অর্জন শেষে নিজ গ্রামে চিকিৎসা শুরু করেন। তাঁর খালু নওয়াবজাদা মোহাম্মদ আলী চৌধুরী (বগুড়া) ছিলেন পাকিস্তানের প্রধানমন্ত্রী (১৯৫৩ – ১৯৫৫), চাচাতো ভাই মোহাম্মদ খুররম খান পন্নীও (কে.কে. পন্নী) ছিলেন আইন পরিষদের সদস্য যিনি পরবর্তীতে ফিলিপাইনে বাংলাদেশের রাষ্ট্রদূত হিসাবে দায়িত্ব পালন করেন এবং যুদ্ধের স্বপক্ষে বিশ্বের অঙ্গনে ব্যাপক কূটনীতিক তৎপরতা চালান।
                    </p>

                    <!-- ছবি মাঝখানে থাকবে -->
                    <div class="text-center my-4">
                        <img src="{{ asset('/uploads/pages/emamuzzaman-old-5.png') }}" alt="রাজনীতির মাঠে এমামুযযামান" class="img-fluid rounded-3 shadow-sm border p-2 bg-white" style="max-height: 420px; object-fit: contain;" />
                    </div>

                    <p class="text-secondary mb-3" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                        পরিবারের অনেকেই সক্রিয় রাজনীতির সঙ্গে জড়িত ছিলেন। সমসাময়িক রাজনীতিকদের পীড়াপীড়িতে এক যুগ পরে এমামুযযামান আবার রাজনীতির অঙ্গনে ফিরে আসেন এবং ১৯৬৩ সনে টাঙ্গাইল-বাসাইল নির্বাচনী আসনে স্বতন্ত্র পদপ্রার্থী হন। তাঁর প্রতিদ্বন্দ্বীরা অনেকেই চেয়েছিলেন প্রবীণ রাজনীতিবিদ মওলানা ভাসানীকে দিয়ে ক্যাম্পেইন করাতে, কিন্তু মওলানা ভাসানী বলেছিলেন, ‘সেলিমের (এমামুযযামানের ডাক নাম) বিপক্ষে আমি ভোট চাইতে পারব না, চাইলেও লাভ হবে না। কারণ তাঁর বিপক্ষে তোমরা কেউ জিততে পারবে না।’ বাস্তবেও তা-ই হয়েছিল। মাননীয় এমামুযযামান আওয়ামী লীগ, মুসলিম লীগের প্রার্থীগণসহ বিপক্ষীয় মোট ছয়জন প্রার্থীকে বিপুল ব্যবধানে পরাজিত কোরে এম.পি. নির্বাচিত হন। তাঁর প্রতিদ্বন্দ্বী সকল প্রার্থীই এত কম ভোট পান যে সকলেরই জামানত বাজেয়াপ্ত হয়ে যায়।
                    </p>

                    <p class="text-secondary mb-0" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                        প্রাদেশিক পরিষদের সদস্য থাকা অবস্থায় তিনি ‘কমনওয়েলথ পার্লামেন্টারি এ্যাসোসিয়েশন’ এর সদস্যপদ লাভ করেন। এছাড়াও তিনি আরও যে সংসদীয় উপকমিটিগুলির সদস্য ছিলেন তার মধ্যে স্ট্যান্ডিং কমিটি অন পাবলিক-একাউন্ট, কমিটি অফ রুল এ্যান্ড প্রসিডিউর, কমিটি অন কনডাক্ট অফ মেম্বারস, সিলেক্ট কমিটি অন হুইপিং বিল ইত্যাদি উল্লেখযোগ্য। ১৯৬৩ সনে তিনি করটিয়ায় হায়দার আলী রেড ক্রস ম্যাটার্নিটি অ্যান্ড চাইল্ড ওয়েলফেয়ার হসপিটাল প্রতিষ্ঠা করেন। ১৯৬৪ সালে ঢাকা, নারায়ণগঞ্জের বিভিন্ন এলাকায় মুসলিম-হিন্দু, বাঙালি ও বিহারিদের মধ্যে দাঙ্গা ছড়িয়ে পড়ে। এতে অসংখ্য মানুষের মৃত্যু ঘটছিল এবং অবাধে চলছিল লুটপাট ও অগ্নিসংযোগ। রাজনীতিক স্বার্থহাসিলের জন্য আইয়ুব সরকার এ দাঙ্গাকে উৎসাহিত করে। এমামুযযামান এম.পি. হয়েও সরকারের নীতির বিরুদ্ধে গিয়ে দাঙ্গা কবলিত এলাকাগুলোয় হিন্দু-মুসলিম সম্প্রীতি ফিরিয়ে আনতে জীবনের ঝুঁকি নিয়ে দিনরাত কাজ করেন।
                    </p>
                </div>
            </div>
        </div>

        {{-- ৮. এমামুযযামান ও কবি নজরুল --}}
        <div class="bg-white rounded-4 p-4 p-md-5 shadow-sm border mb-5">
            <h3 class="section-title-premium mb-4" style="font-family: var(--font-bengali);">এমামুযযামান ও কবি নজরুল</h3>
            <div class="row">
                <div class="col-12">
                    <p class="text-secondary mb-3" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                        এমামুযযামান কবে প্রথম কবির সাক্ষাৎ লাভ করেন তা আমাদের জানা নেই। ১৯৭২ সনের ২৪ মে কবিকে যখন ঢাকায় নিয়ে আসা হয়, তখন তার চিকিৎসার জন্য রাষ্ট্রপতি বঙ্গবন্ধু শেখ মুজিবুর রহমানের নির্দেশে বিশেষজ্ঞ ডাক্তারদের নিয়ে একটি বোর্ড গঠন করা হয়। ছয় সদস্যের বোর্ডে মাননীয় এমামুযযামান ছিলেন একমাত্র হোমিওপ্যাথ। ইতঃপূর্বে তিনি পূর্ব পাকিস্তান আইন পরিষদের সদস্য থাকার সুবাদে রাজনৈতিক অঙ্গনে চিকিৎসক হিসাবেও তাঁর সুখ্যাতি ছড়িয়ে পড়েছিল। সেজন্যই বঙ্গবন্ধু বিশেষভাবে এমামুযযামানকে বোর্ডের অন্তর্ভুক্ত করেন।
                    </p>
                    <p class="text-secondary mb-4" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                        কবি তখন ঢাকার পিজি হাসপাতালে ভর্তি। সেখানে অনেক লোক কবিকে দেখতে আসতেন। চোখের দেখা। কবি তখন কাউকে চিনতে পারতেন না, কথাও বলতে পারতেন না। এর উপরে ঘুমের ওষুধের ক্রিয়ায় তিনি প্রায় সময়ই অচেতন হয়ে ঘুমাতেন। এমামুযযামান বোর্ডের অন্য চিকিৎসকদেরকে অনুরোধ করেন ঘুমের ওষুধ না দিতে। কারণ জোর করে স্নায়ুকে অচেতন করে ঘুম পাড়িয়ে লাভ নেই, সুস্থতার জন্য দরকার স্বাভাবিক নিদ্রা। বোর্ডের কয়েকজন চিকিৎসক তাঁর সঙ্গে একমত হওয়ায় তিনি বেশ কয়েকদিন নিজের প্রেসক্রিপশান অনুযায়ী চিকিৎসা করেন। কবির ঘুম স্বাভাবিকও হয়ে আসে।
                    </p>

                    <!-- ছবি মাঝখানে থাকবে -->
                    <div class="text-center my-4">
                        <img src="{{ asset('/uploads/pages/emamuzzaman-old-4.png') }}" alt="এমামুযযামান ও কবি নজরুল" class="img-fluid rounded-3 shadow-sm border p-2 bg-white" style="max-height: 420px; object-fit: contain;" />
                    </div>

                    <p class="text-secondary mb-3" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                        কিন্তু দর্শনার্থী এত বেশী আসতে থাকে যে ভিড়ের জন্যই তিনি ঘুমাতে পারতেন না। এজন্য এমামুযযামান হাসপাতালের কর্তৃপক্ষের প্রতি কড়া হুকুম দেন যেন কোন দর্শনার্থীকেই অনুমতি না দেওয়া হয়। কিন্তু যারা সেখানকার কর্তৃপক্ষ তারা নিজেদের প্রভাব খাটিয়ে তাদের আত্মীয়-স্বজন বন্ধুবান্ধবদের কবি-দর্শনের সুযোগ প্রদান করতে থাকেন। আবেগের এই আতিশায্যের কারণে কবির চিকিৎসার বিরাট ত্রুটি হয়েছে তা বলার অপেক্ষা রাখে না। এমতাবস্থায় অ্যালোপ্যাথ চিকিৎসকরা আবার বাধ্য হন কড়া ঘুমের ওষুধ দিয়ে কবিকে ঘুম পাড়িয়ে রাখতে।
                    </p>
                    <p class="text-secondary mb-0" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                        এমামুযযামানের চিকিৎসাকালীন সময়ে আল্লাহর ইচ্ছায় কবির শরীর বেশ খানিকটা সুস্থ হয়ে উঠেছিল। মাননীয় এমামুযযামান প্রায়ই এ সময়ের একটি মজার ঘটনা বলতেন। তিনি বলতেন, ‘তোমরা কি জানো- আমিই বোধ হয় বর্তমানে একমাত্র ব্যক্তি যে কিনা কবি নজরুল ইসলামের হাতের চড় খেয়েছি?’ ঘটনাটি হচ্ছে- চিকিৎসা চলাকালে একদিন কবিকে ওষুধ খাওয়াতে গিয়েছিলেন এমামুযযামান। কবি কিছুতেই ওষুধ খেতে চান না। এমামুযযামান দু’একবার চেষ্টা করতেই কবি এমামুযযামানের গালে একটি জোরে চড় বসিয়ে দেন। ওষুধ আর খাওয়ানো হয় না। যখনই তিনি নজরুল ইসলামের প্রসঙ্গে বলতেন এই স্মৃতি তাঁকে পুনর্বার গভীরভাবে পুলকিত করত।
                    </p>
                </div>
            </div>
        </div>

        {{-- ৯. মাননীয় এমামুযযামান কীভাবে সত্যের সন্ধান পেলেন? --}}
        <div class="bg-light rounded-4 p-4 p-md-5 border mb-5">
            <h3 class="section-title-premium mb-4" style="font-family: var(--font-bengali);">মাননীয় এমামুযযামান কীভাবে সত্যের সন্ধান পেলেন?</h3>
            <div class="row">
                <div class="col-12">
                    <p class="text-secondary mb-3" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                        ছোটবেলায় যখন তিনি মোসলেম জাতির পূর্ব ইতিহাসগুলি পাঠ করেন তখন থেকেই তিনি মোসলেম জাতির অতীতের সাথে বর্তমান অবস্থার এই বিরাট পার্থক্য দেখে তিনি রীতিমত সংশয়ে পড়ে যান যে এরাই কি সেই জাতি যারা শিক্ষায়, জ্ঞানে বিজ্ঞানে, সামরিক শক্তিতে, ধনবলে ছিল পৃথিবীর সবচেয়ে শক্তিমান, জ্ঞান-বিজ্ঞানের প্রতিটি অঙ্গনে যারা ছিল সকলের অগ্রণী?
                    </p>
                    <p class="text-secondary mb-4" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                        কিসের পরশে এই জাতি ১৪০০ বছর পূর্বে একটি মহান উম্মাহয় পরিণত হোয়েছিল, আর কিসের অভাবে আজকে তাদের এই চরম দুর্দশা, তারা সকল জাতির দ্বারা পরাজিত, শোষিত, দাসত্বের শৃঙ্খলে আবদ্ধ, দুনিয়ার সবচেয়ে হত-দরিদ্র ও অশিক্ষা-কুশিক্ষায় জর্জরিত, সব জাতির দ্বারা লান্ছিত এবং অপমানিত?
                    </p>

                    <!-- ছবি মাঝখানে থাকবে -->
                    <div class="text-center my-4">
                        <img src="{{ asset('/uploads/about/bayazid-khan-panni.jpg') }}" alt="মোহাম্মদ বায়াজীদ খান পন্নী" class="img-fluid rounded-3 shadow-sm border p-2 bg-white" style="max-height: 420px; object-fit: contain;" />
                    </div>

                    <p class="text-secondary mb-0" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                        মহান আল্লাহর অশেষ রহমতে তিনি ধীরে ধীরে অনুধাবন কোরলেন কি সেই শুভঙ্করের ফাঁকি। ষাটের দশকে এসে তাঁর কাছে এই বিষয়টি দিনের আলোর মত পরিষ্কার হোয়ে ধরা দিল। ১৯৯৫ সনে তিনি হেযবুত তওহীদ আন্দোলনের সূচনা করেন এবং জীবনের শেষ দিন পর্যন্ত মানুষকে প্রকৃত এসলামে ফিরে আসার জন্য আহ্বান জানাতে থাকেন। ১৬ জানুয়ারী ২০১২ ঈসায়ী এই মহামানব প্রত্যক্ষ দুনিয়া থেকে পর্দা করেন।
                    </p>
                </div>
            </div>
        </div>

        {{-- ১০. পারিবারিক জীবন ও সন্তানসমূহ --}}
        <div class="my-5 bg-white rounded-4 p-4 p-md-5 shadow-sm border">
            <h3 class="section-title-center-premium">পারিবারিক জীবন</h3>
            <p class="text-center text-secondary mx-auto mb-4" style="max-width: 750px; line-height: 1.8; font-size: 1.02rem;">
                ১৯৬৯ সনে ৪৪ বৎসর বয়সে তিনি এদেশে বসবাসরত বোম্বের কাচ্ এলাকার অধিবাসী মেমন বংশের মেয়ে মরিয়ম সাত্তারের সাথে বিবাহ বন্ধনে আবদ্ধ হন। ১৯৯৬ সনে স্ত্রীর এন্তেকালের পর ১৯৯৯ সনে বিক্রমপুর মুন্সিগঞ্জের খাদিজা খাতুনের সাথে বৈবাহিক সূত্রে আবদ্ধ হন। তিনি দুটি পুত্র ও দুটি কন্যা সন্তানের জনক। তারা হচ্ছেন:
            </p>

            <div class="row g-4 justify-content-center">
                <!-- সা'দাত আলী খান পন্নী -->
                <div class="col-md-6 col-lg-3">
                    <div class="family-member-card d-flex flex-column h-100 text-center">
                        <div class="d-flex align-items-center justify-content-center bg-light" style="height: 220px; width: 100%; border-bottom: 1px solid var(--border-color);">
                            <i class="fas fa-user-circle fa-5x text-muted opacity-50"></i>
                        </div>
                        <div class="family-member-info p-3 d-flex flex-column flex-grow-1 justify-content-between">
                            <div>
                                <span class="family-member-relation mb-2 d-inline-block">১ম পুত্র (মরহুম)</span>
                                <h6 class="fw-bold text-dark text-truncate mb-2" style="font-family: var(--font-bengali); font-size: 0.95rem; line-height: 1.2;" title="মোহাম্মদ সা’দাত আলী খান পন্নী">
                                    মোহাম্মদ সা’দাত আলী খান পন্নী
                                </h6>
                            </div>
                            <p class="text-secondary small mb-0 mt-1" style="line-height: 1.5; font-size: 0.85rem;">১৯৯৮ সনে মাত্র ২৭ বছর বয়সে সড়ক দুর্ঘটনায় অকাল মৃত্যুবরণ করেন।</p>
                        </div>
                    </div>
                </div>

                <!-- ডা. মাখদুমা পন্নী -->
                <div class="col-md-6 col-lg-3">
                    <div class="family-member-card d-flex flex-column h-100 text-center">
                        <div style="height: 220px; width: 100%; border-bottom: 1px solid var(--border-color); overflow: hidden;">
                            <img src="{{ asset('/uploads/leaders/Ummut-Tijan-Makhduma-Panni.jpg') }}" alt="ডা. মাখদুমা পন্নী" class="w-100 h-100" style="object-fit: cover;" onerror="this.src='/uploads/leaders/default.jpg'">
                        </div>
                        <div class="family-member-info p-3 d-flex flex-column flex-grow-1 justify-content-between">
                            <div>
                                <span class="family-member-relation mb-2 d-inline-block">১ম কন্যা</span>
                                <h6 class="fw-bold text-dark text-truncate mb-2" style="font-family: var(--font-bengali); font-size: 0.95rem; line-height: 1.2;" title="উম্মুত তিজান মাখদুমা পন্নী">
                                    উম্মুত তিজান মাখদুমা পন্নী
                                </h6>
                            </div>
                            <p class="text-secondary small mb-0 mt-1" style="line-height: 1.5; font-size: 0.85rem;">পেশায় একজন চিকিৎসক, চিন্তাবিদ ও চমৎকার লেখিকা।</p>
                        </div>
                    </div>
                </div>

                <!-- রুফায়দাহ পন্নী -->
                <div class="col-md-6 col-lg-3">
                    <div class="family-member-card d-flex flex-column h-100 text-center">
                        <div style="height: 220px; width: 100%; border-bottom: 1px solid var(--border-color); overflow: hidden;">
                            <img src="{{ asset('/uploads/leaders/rufaidahpanni.jpg') }}" alt="রুফায়দাহ পন্নী" class="w-100 h-100" style="object-fit: cover;" onerror="this.src='/uploads/leaders/default.jpg'">
                        </div>
                        <div class="family-member-info p-3 d-flex flex-column flex-grow-1 justify-content-between">
                            <div>
                                <span class="family-member-relation mb-2 d-inline-block">২য় কন্যা</span>
                                <h6 class="fw-bold text-dark text-truncate mb-2" style="font-family: var(--font-bengali); font-size: 0.95rem; line-height: 1.2;" title="কুররাতুল আইন রুফায়দাহ পন্নী">
                                    কুররাতুল আইন রুফায়দাহ পন্নী
                                </h6>
                            </div>
                            <p class="text-secondary small mb-0 mt-1" style="line-height: 1.5; font-size: 0.85rem;">সাবেক সম্পাদক, দেশেরপত্র; উপদেষ্টা, বজ্রশক্তি; এমডি শাদীয়ানা।</p>
                        </div>
                    </div>
                </div>

                <!--  সাইফ আল মুসান্না খান পন্নী -->
                <div class="col-md-6 col-lg-3">
                    <div class="family-member-card d-flex flex-column h-100 text-center">
                        <div class="d-flex align-items-center justify-content-center bg-light" style="height: 220px; width: 100%; border-bottom: 1px solid var(--border-color);">
                            <i class="fas fa-child fa-5x text-muted opacity-50"></i>
                        </div>
                        <div class="family-member-info p-3 d-flex flex-column flex-grow-1 justify-content-between">
                            <div>
                                <span class="family-member-relation mb-2 d-inline-block">২য় পুত্র</span>
                                <h6 class="fw-bold text-dark text-truncate mb-2" style="font-family: var(--font-bengali); font-size: 0.95rem; line-height: 1.2;" title="সাইফ আল মুсан্না খান পন্নী">
                                    সাইফ আল মুসান্না খান পন্নী
                                </h6>
                            </div>
                            <p class="text-secondary small mb-0 mt-1" style="line-height: 1.5; font-size: 0.85rem;">দ্বিতীয় শ্রেণীর ছাত্র।</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ১১. বিশেষ অর্জনসমূহ (Achievements) --}}
        <div class="my-5">
            <h3 class="section-title-center-premium mb-4" style="font-family: var(--font-bengali);">বিশেষ অর্জনসমূহ (Achievements)</h3>

            <div class="row g-4 justify-content-center">
                <!-- চিকিৎসা -->
                <div class="col-md-6 col-lg-4">
                    <div class="achievement-card d-flex flex-column h-100">
                        <div class="achievement-icon"><i class="fas fa-hand-holding-medical"></i></div>
                        <h5 class="fw-bold mb-2" style="font-family: var(--font-bengali);">১. চিকিৎসা</h5>
                        <p class="text-secondary small mb-0" style="line-height: 1.6; text-align: justify;">
                            বাংলাদেশের সাবেক রাষ্ট্রপ্রধান, তৎকালীন ক্ষমতাসীন প্রধানমন্ত্রী, সাবেক প্রধানমন্ত্রী, জাতীয় কবি কাজী নজরুল ইসলামসহ অনেক বরেণ্য ব্যক্তি তাঁর চিকিৎসাধীন রোগীদের অন্তর্ভুক্ত ছিলেন।
                        </p>
                    </div>
                </div>

                <!-- সাহিত্যকর্ম -->
                <div class="col-md-6 col-lg-4">
                    <div class="achievement-card d-flex flex-column h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="achievement-icon mb-0"><i class="fas fa-book-open"></i></div>
                            <span class="badge bg-success text-white px-2 py-1 rounded-pill" style="font-size: 0.75rem;">বেস্টসেলার</span>
                        </div>
                        <h5 class="fw-bold mb-2" style="font-family: var(--font-bengali);">২. সাহিত্যকর্ম</h5>
                        <p class="text-secondary small mb-3" style="line-height: 1.6; text-align: justify; flex-grow: 1;">
                            বেশ কিছু আলোড়ন সৃষ্টিকারী বইয়ের রচয়িতা। তাঁর ‘বাঘ-বন-বন্দুক’ বইটি দ্বাদশ শ্রেণীর দ্রুতপাঠ হিসেবে শিক্ষা বোর্ড কর্তৃক অনুমোদিত ছিল এবং ‘দাজ্জাল? ইহুদী-খ্রীস্টান সভ্যতা’ বইটি ব্যাপক পাঠকপ্রিয়তা লাভ করে।
                        </p>
                        <div class="d-flex align-items-center gap-3 p-2 bg-light rounded border">
                            <img src="{{ asset('/uploads/pages/book_dajjal.png') }}" alt="দাজ্জাল বই" style="width: 40px; height: 52px; object-fit: contain;" />
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.8rem;">দাজ্জাল? ইহুদী-খ্রীস্টান ‘সভ্যতা’!</div>
                                <div class="text-muted" style="font-size: 0.75rem;">২০০৮ এর সর্বাধিক বিক্রয়কৃত বই</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- শিকারী -->
                <div class="col-md-6 col-lg-4">
                    <div class="achievement-card d-flex flex-column h-100">
                        <div class="achievement-icon"><i class="fas fa-paw"></i></div>
                        <h5 class="fw-bold mb-2" style="font-family: var(--font-bengali);">৩. শিকার</h5>
                        <p class="text-secondary small mb-0" style="line-height: 1.6; text-align: justify;">
                            তিনি ছিলেন একজন অসম সাহসী শিকারী। তাঁর শিকারের ঝুলিতে রয়েছে চিতাবাঘ, বন্য শুকর, অজগর সাপ এবং বিশাল আকৃতির কুমিরসহ বহু হিংস্র বন্য প্রাণী।
                        </p>
                    </div>
                </div>

                <!-- রায়ফেল শুটিং -->
                <div class="col-md-6 col-lg-4">
                    <div class="achievement-card d-flex flex-column h-100">
                        <div class="achievement-icon"><i class="fas fa-bullseye"></i></div>
                        <h5 class="fw-bold mb-2" style="font-family: var(--font-bengali);">৪. রায়ফেল শুটিং</h5>
                        <p class="text-secondary small mb-0" style="line-height: 1.6; text-align: justify;">
                            ১৯৫৪ সনে অস্ট্রেলিয়ার মেলবোর্নে অনুষ্ঠিত বিশ্ব অলিম্পিক চ্যাম্পিয়নশিপে অংশগ্রহণের লক্ষ্যে তৎকালীন পাকিস্তান জাতীয় দলের অন্যতম সেরা রাইফেল শুটার নির্বাচিত হয়েছিলেন।
                        </p>
                    </div>
                </div>

                <!-- রাজনীতি -->
                <div class="col-md-6 col-lg-4">
                    <div class="achievement-card d-flex flex-column h-100">
                        <div class="achievement-icon"><i class="fas fa-landmark"></i></div>
                        <h5 class="fw-bold mb-2" style="font-family: var(--font-bengali);">৫. রাজনীতি</h5>
                        <p class="text-secondary small mb-0" style="line-height: 1.6; text-align: justify;">
                            জনগণের বিপুল ভোটে টাঙ্গাইল-বাসাইল নির্বাচনী এলাকা থেকে স্বতন্ত্র প্রার্থী হিসেবে ১৯৬৩ সনে তৎকালীন পূর্ব-পাকিস্তান প্রাদেশিক আইন পরিষদের সদস্য (এম.পি.) নির্বাচিত হন।
                        </p>
                    </div>
                </div>

                <!-- সমাজসেবা -->
                <div class="col-md-6 col-lg-4">
                    <div class="achievement-card d-flex flex-column h-100">
                        <div class="achievement-icon"><i class="fas fa-hospital-user"></i></div>
                        <h5 class="fw-bold mb-2" style="font-family: var(--font-bengali);">৬. সমাজসেবা</h5>
                        <p class="text-secondary small mb-0" style="line-height: 1.6; text-align: justify;">
                            শিক্ষা ও সমাজ উন্নয়নে তিনি আতিয়া মসজিদ সংস্কার, হায়দার আলী রেডক্রস ম্যাটার্নিটি অ্যান্ড চাইল্ড ওয়েলফেয়ার হসপিটাল এবং সা’দাত ওয়েলফেয়ার ফাউন্ডেশনের প্রতিষ্ঠাতা।
                        </p>
                    </div>
                </div>

                <!-- শিল্প সংস্কৃতি -->
                <div class="col-md-6 col-lg-4">
                    <div class="achievement-card d-flex flex-column h-100">
                        <div class="achievement-icon"><i class="fas fa-music"></i></div>
                        <h5 class="fw-bold mb-2" style="font-family: var(--font-bengali);">৭. শিল্প সংস্কৃতি</h5>
                        <p class="text-secondary small mb-0" style="line-height: 1.6; text-align: justify;">
                            জাতীয় কবি কাজী নজরুল ইসলামের গান ও সাহিত্যের বিস্তারে নিবেদিত দেশের অন্যতম প্রাচীন ও স্বনামধন্য প্রতিষ্ঠান নজরুল একাডেমির আজীবন সদস্য ছিলেন।
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ১২. ভিডিও সেকশন --}}
        <div class="card p-4 p-md-5 border-0 shadow-sm bg-white rounded-4 mt-5">
            <h3 class="section-title-center-premium"><i class="fab fa-youtube text-danger me-2"></i>ভিডিওতে এমামুযযামান</h3>
            <p class="text-center text-muted mb-4">হেযবুত তওহীদের প্রতিষ্ঠাতা এমামুযযামান জনাব মোহাম্মদ বায়াজীদ খান পন্নীর বর্ণাঢ্য জীবনীর ওপর প্রামাণ্যচিত্র</p>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow">
                        <iframe src="https://www.youtube.com/embed/g4SBE_hUWik?si=3i2nbtNwnD50itDz" title="মাননীয় এমামুযযামান জনাব মোহাম্মদ বায়াজীদ খান পন্নী" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection