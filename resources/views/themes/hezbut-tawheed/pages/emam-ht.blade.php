@extends('theme::layouts.app')

@section('title', 'মাননীয় এমাম জনাব হোসাইন মোহাম্মদ সেলিম - হেযবুত তওহীদ')

@section('content')

{{-- ১. হিরো ব্যানার --}}
@include('theme::partials.hero_banner', [
'title' => 'জঙ্গিবাদ, সন্ত্রাস, সাম্প্রদায়িকতা, নারী নির্যাতন ও মাদকের বিরুদ্ধে সংগ্রাম চলবে',
'subtitle' => '- মাননীয় এমাম জনাব হোসাইন মোহাম্মদ সেলিম',
'badge_text' => 'নেতৃত্ব',
'badge_icon' => 'fas fa-user-shield'
])

<section class="py-5 bg-off-white page-body" style="font-family: 'Baloo Da 2', sans-serif;">
    <div class="container">

        {{-- ২. জীবন বৃত্তান্ত --}}
        <div class="bg-white rounded-4 p-4 p-md-5 shadow-sm border mb-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-4 text-center">
                    <img src="{{ asset('/uploads/pages/emam-266x300.png') }}" alt="মাননীয় এমাম হোসাইন মোহাম্মদ সেলিম" class="img-fluid" style="max-height: 380px; object-fit: contain;" />
                </div>
                <div class="col-lg-8">
                    <h3 class="section-title-premium mb-4" style="font-family: var(--font-bengali); color: #006A4E;">মাননীয় এমামের জন্ম ও বংশ পরিচয়</h3>
                    <p class="text-secondary mb-4" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                        হেযবুত তওহীদের মাননীয় এমাম জনাব হোসাইন মোহাম্মদ সেলিম ২৮ নভেম্বর, ১৯৭২ ঈসায়ী সালে নোয়াখালী জেলার সোনাইমুড়ি থানার পোরকরা গ্রামের এক সম্ভ্রান্ত মুসলিম পরিবারে জন্মগ্রহণ করেন। তাঁর পিতা জনাব নুরুল হক এবং মাতা হোসনে-আরা বেগম।
                    </p>

                    <h4 class="fw-bold mb-3" style="font-family: var(--font-bengali); color: #006A4E; font-size: 1.25rem;">শিক্ষাগত যোগ্যতা ও পেশাগত জীবন</h4>
                    <p class="text-secondary mb-0" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                        তিনি স্থানীয় সরকারি প্রাথমিক বিদ্যালয় থেকে প্রাথমিক শিক্ষা শেষ করার পর পার্শ্ববর্তী বিপুলাসার আহম্মদ উল্লাহ উচ্চ বিদ্যালয় থেকে ১৯৮৯ সালে এস.এস.সি পাশ করেন। লাকসাম নওয়াব ফয়জুন্নেছা সরকারি college থেকে ১৯৯১ সালে এইচ.এস.সি এবং একই কলেজ থেকে ১৯৯৩ ইং সালে জাতীয় বিশ্ববিদ্যালয়ের অধীনে স্নাতক পরীক্ষায় প্রথম শ্রেণিতে প্রথম স্থান অধিকার করেন। তিনি জগন্নাথ বিশ্ববিদ্যালয় থেকে ১৯৯৬-৯৭ শিক্ষাবর্ষে রাষ্ট্রবিজ্ঞানে স্নাতকোত্তর ডিগ্রী অর্জন করেন। পড়ালেখা শেষ করে তিনি ব্যবসা-বাণিজ্যকে জীবিকা নির্বাহের মাধ্যম হিসাবে গ্রহণ করেন।
                    </p>
                </div>
            </div>
        </div>

        {{-- ৩. হেযবুত তওহীদ গ্রহণ --}}
        <div class="bg-light rounded-4 p-4 p-md-5 border mb-5">
            <h3 class="section-title-premium mb-4" style="font-family: var(--font-bengali); color: #006A4E;">হেযবুত তওহীদ গ্রহণ</h3>
            <p class="text-secondary mb-0" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                যামানার এমামের আদর্শের উত্তরাধিকার জনাব হোসাইন মোহাম্মদ সেলিম ১৯৯৯ সালের ফেব্রুয়ারি মাসে টাঙ্গাইলের করটিয়ার জমিদার বাড়ির দাউদ মহলে প্রথমবারের মতো মাননীয় এমামুযযামানের সংস্পর্শে আসেন। একই মাসে তিনি মাননীয় এমামুযযামানের বক্তব্যের সাথে একাত্মতা ঘোষণা করে হেযবুত তওহীদ আন্দোলনে যোগদান করেন। কিছুদিনের মধ্যেই তিনি মাননীয় এমামুযযামানের স্নেহের পাত্রে পরিণত হন এবং সার্বক্ষণিক সঙ্গী হয়ে ওঠেন। এমামুযযামানের জীবদ্দশায় তিনি হেযবুত তওহীদের সমন্বয়কারী হিসাবে দায়িত্ব পালন করতেন।
            </p>
        </div>

        {{-- ৪. এমাম হিসেবে দায়িত্ব গ্রহণ --}}
        <div class="bg-white rounded-4 p-4 p-md-5 shadow-sm border mb-5">
            <h3 class="section-title-premium mb-4" style="font-family: var(--font-bengali); color: #006A4E;">হেযবুত তওহীদের এমাম হিসেবে দায়িত্ব গ্রহণ</h3>

            <p class="text-secondary mb-3" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                ২০১২ সালের ১৬ জানুয়ারি মহামান্য এমামুযযামানের ইন্তেকালের পর তাঁর অসমাপ্ত কাজ সমাপ্ত করার জন্য আল্লাহর সিদ্ধান্ত অনুযায়ী সর্বসম্মতিক্রমে হেযবুত তওহীদের এমামের দায়িত্ব গ্রহণ করেন জনাব হোসাইন মোহাম্মদ সেলিম। তিনি শুরুতেই আন্দোলন পরিচালনার নীতি হিসাবে মাননীয় এমামুযযামানের গৃহীত নীতিকেই গ্রহণ করেন। যে সত্য মাননীয় এমামুযযামান মানবজাতিকে দান করে গেছেন, হেযবুত তওহীদকে দিয়ে গেছেন, সেই সত্য মানুষের কাছে শান্তিপূর্ণ উপায়ে পৌঁছে দেওয়াকেই আপাতত হেযবুত তওহীদের একমাত্র কাজ হিসাবে তিনি গ্রহণ করেন।
            </p>

            <p class="text-secondary mb-4" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                তাই দেশব্যাপী আমাদের বালাগ ছড়িয়ে দেওয়ার জন্য সকল মোজাহেদ-মোজাহেদাগণকে বাড়ি-ঘর ছেড়ে বহিরাগত হয়ে সারাদেশে ছড়িয়ে পড়ার ব্যাপারে তিনি গুরুত্ব দেন। খুব দ্রুতই মোজাহেদ-মোজাহেদাগণ মাননীয় এমামের আহ্বানে সাড়া দিয়ে বাড়ি-ঘর ছেড়ে দেশব্যাপী প্রত্যন্ত গ্রামে-গঞ্জে ছড়িয়ে পড়ে এবং মানবতার কল্যাণে নিবেদিত প্রাণ মোজাহেদ-মোজাহেদাদের ঐকান্তিক প্রচেষ্টা ও অক্লান্ত পরিশ্রমে প্রায় সকল জেলায় হেযবুত তওহীদের বালাগ কার্যক্রম সম্প্রসারিত হয়। তওহীদ প্রকাশন থেকে প্রকাশিত “আল্লাহর মো’জেজা হেযবুত তওহীদের বিজয় ঘোষণা” নামক বইটি সারা দেশে ব্যাপকভাবে বিক্রি করা হয়।
            </p>

            {{-- ছবি মাঝখানে থাকবে --}}
            <div class="text-center my-4">
                <img src="{{ asset('/uploads/pages/Emam-writing1.png') }}" alt="মাননীয় এমাম জনাব হোসাইন মোহাম্মদ সেলিম" class="img-fluid rounded-3 shadow-sm border p-2 bg-white" style="max-height: 420px; object-fit: contain;" />
            </div>

            <p class="text-secondary mb-4" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                মাননীয় এমামের সিদ্ধান্ত অনুসারে ২০১২ সালের ডিসেম্বর মাস থেকে দৈনিক নিউজ পত্রিকার মাধ্যমে শুরু হয় হেযবুত তওহীদের উদ্যোগে পত্রিকা প্রকাশ যার প্রধান লক্ষ্যই ছিল মহাসত্যের বালাগ। বালাগ কার্যক্রম আরও ত্বরাণ্বিত করার লক্ষ্যে একে একে হেযবুত তওহীদের উদ্যোগে যাত্রা শুরু করে আরও দুটি পত্রিকা দৈনিক দেশেরপত্র ও দৈনিক বজ্রশক্তি, একটি অনলাইন পত্রিকা বাংলাদেশেরপত্র.কম, একটি অনলাইন টেলিভিশন চ্যানেল জেটিভি অনলাইন। প্রামাণ্যচিত্র নির্মাণের জন্য খোলা হয় মিডিয়া হাউজ ‘ইলদিরিম মিডিয়া’।
            </p>

            <p class="text-secondary mb-4" style="line-height: 1.8; text-align: justify; font-size: 1.02rem;">
                মাননীয় এমামের সিদ্ধান্ত অনুসারে বালাগের এ পর্যায়ে এসে হেযবুত তওহীদ ও হেযবুত তওহীদ কর্তৃক পরিচালিত মিডিয়াগুলোর উদ্যোগে দেশব্যাপী সেমিনার, আলোচনা অনুষ্ঠান, মতবিনিময় সভা, প্রামাণ্যচিত্র প্রদর্শনী, গোলটেবিল বৈঠক, সাংস্কৃতিক অনুষ্ঠান ইত্যাদির মাধ্যমে সমাজের গণ্যমান্য ব্যক্তিবর্গের কাছে হেযবুত তওহীদের বক্তব্য পৌঁছে দেওয়া সম্ভব হয়। অনুষ্ঠানগুলোতে বাংলাদেশ সরকারের বেশ কয়েকজন মন্ত্রী, সাংসদ, বিচারপতি, সাংবাদিক, বিভিন্ন রাজনৈতিক দলের নেতৃবৃন্দ, শিক্ষক, কণ্ঠশিল্পী, অভিনেতাসহ সমাজের বিশিষ্ট ব্যক্তিগণ এ বক্তব্যের সাথে ঐকমত্য পোষণ করেন এবং সার্বিক সহযোগিতার আশ্বাস দেন। প্রামাণ্যচিত্র প্রদর্শনী দেখে দেশের আপামর জনগণ হেযবুত তওহীদের বক্তব্যের সাথে দু’হাত তুলে ঐকমত্য প্রকাশ করেন। মহামহীম আল্লাহ রাব্বুল আলামিনের কাছে প্রার্থনা- তিনি যেন আমাদের মাননীয় এমামকে সর্বোত্তম দৃঢ়তা দান করেন এবং সাহসিকতা, বলিষ্ঠতা, ন্যায়-নিষ্ঠা, দক্ষতা, ঐকান্তিকতা, পরিশ্রম করার শক্তি দান করেন।
            </p>

            {{-- বাণী/উদ্ধৃতি ব্লক --}}
            <div class="p-4 bg-light border-start border-success border-4 rounded-3 my-4">
                <p class="text-dark fw-bold mb-0" style="font-size: 1.05rem; line-height: 1.8; font-style: italic; text-align: justify;">
                    “হেযবুত তওহীদ পরিচালনা করেন মহান আল্লাহ, তোমরা তাঁর প্রশংসা কর, তাঁর শুকরিয়া আদায় কর। আমি অত্যন্ত গোনাহগার অতি সাধারণ একজন মানুষ। হেযবুত তওহীদের মতো এমন মহান পবিত্র আন্দোলন পরিচালনা করার কোনো যোগ্যতাই আমার নেই। কিন্তু শক্তিশালী জাতি গঠনের পূর্বশর্ত হলো একজন নেতার প্রশ্নহীন, শর্তহীন আনুগত্যে ঐক্যবদ্ধ হওয়া। তাই আপনারা আমার আনুগত্য করবেন। এক মহাপবিত্র আন্দোলন হেযবুত তওহীদ এর এমাম হিসাবে আমার মতো গুনাহগারকে দায়িত্ব দেওয়া এবং দাজ্জালকে ধ্বংস করার মতো এত বড় বিশাল কাজকে আমার মতো অতি সাধারণ মানুষের উপর অর্পণ করার অর্থ হলো সকল কার্য সমাধান করবেন মহান আল্লাহ স্বয়ং। আমরা শুধু ওসিলা মাত্র।”
                </p>
            </div>
        </div>

        {{-- ৫. সাহিত্যকর্ম (বইসমূহ) --}}
        <div class="my-5">
            <h3 class="section-title-center-premium mb-4" style="font-family: var(--font-bengali); color: #006A4E;">সাহিত্যকর্ম</h3>
            <p class="text-center text-secondary mx-auto mb-4" style="max-width: 800px; line-height: 1.8; font-size: 1.02rem;">
                সময়ের প্রয়োজনে মহান আল্লাহ হেযবুত তওহীদের মাননীয় এমাম জনাব হোসাইন মোহাম্মদ সেলিমকে গত সাড়ে তিন বছরে অনেক নতুন নতুন উপলব্ধি দান করেছেন। কখনো স্বপ্নের মাধ্যমে, কখনো আল্লাহর সৃষ্টিকে অবলোকন করে, কখনো চিন্তা-ভাবনা করে কখনো বা অবচেতন মনেই তিনি উপলব্ধিগুলি করেছেন। তাঁর লেখা বইগুলো পাঠক মহলে ব্যাপক সাড়া জাগিয়েছে।
            </p>

            <div class="row g-4 justify-content-center">
                <!-- বই ১: Divide and Rule -->
                <div class="col-md-6 col-lg-3">
                    <div class="family-member-card d-flex flex-column h-100 text-center">
                        <div style="height: 240px; width: 100%; border-bottom: 1px solid var(--border-color); overflow: hidden; background: #f8f9fa;" class="d-flex align-items-center justify-content-center p-3">
                            <img src="{{ asset('/uploads/pages/book_dividerule.png') }}" alt="Divide and Rule" class="img-fluid" style="max-height: 100%; object-fit: contain;" />
                        </div>
                        <div class="family-member-info p-3 d-flex flex-column flex-grow-1 justify-content-between">
                            <div>
                                <h6 class="fw-bold text-dark text-truncate mb-2" style="font-family: var(--font-bengali); font-size: 1rem; line-height: 1.3;" title="Divide and Rule: শোষণের হাতিয়ার">
                                    Divide and Rule
                                </h6>
                            </div>
                            <p class="text-secondary small mb-0 mt-1" style="line-height: 1.5; font-size: 0.85rem;">Divide and Rule: শোষণের হাতিয়ার</p>
                        </div>
                    </div>
                </div>

                <!-- বই ২: জোরপূর্বক শ্রমব্যবস্থাই দাসত্ব -->
                <div class="col-md-6 col-lg-3">
                    <div class="family-member-card d-flex flex-column h-100 text-center">
                        <div style="height: 240px; width: 100%; border-bottom: 1px solid var(--border-color); overflow: hidden; background: #f8f9fa;" class="d-flex align-items-center justify-content-center p-3">
                            <img src="{{ asset('/uploads/pages/book_dasotto.png') }}" alt="জোরপূর্বক শ্রমব্যবস্থাই দাসত্ব" class="img-fluid" style="max-height: 100%; object-fit: contain;" />
                        </div>
                        <div class="family-member-info p-3 d-flex flex-column flex-grow-1 justify-content-between">
                            <div>
                                <h6 class="fw-bold text-dark text-truncate mb-2" style="font-family: var(--font-bengali); font-size: 1rem; line-height: 1.3;" title="জোরপূর্বক শ্রমব্যবস্থাই দাসত্ব">
                                    জোরপূর্বক শ্রমব্যবস্থাই দাসত্ব
                                </h6>
                            </div>
                            <p class="text-secondary small mb-0 mt-1" style="line-height: 1.5; font-size: 0.85rem;">জোরপূর্বক শ্রমব্যবস্থাই দাসত্ব</p>
                        </div>
                    </div>
                </div>

                <!-- বই ৩: ধর্মব্যবসার ফাঁদে -->
                <div class="col-md-6 col-lg-3">
                    <div class="family-member-card d-flex flex-column h-100 text-center">
                        <div style="height: 240px; width: 100%; border-bottom: 1px solid var(--border-color); overflow: hidden; background: #f8f9fa;" class="d-flex align-items-center justify-content-center p-3">
                            <img src="{{ asset('/uploads/pages/dharmobebsha-book-cover-Copy.png') }}" alt="ধর্মব্যবসার ফাঁদে" class="img-fluid" style="max-height: 100%; object-fit: contain;" />
                        </div>
                        <div class="family-member-info p-3 d-flex flex-column flex-grow-1 justify-content-between">
                            <div>
                                <h6 class="fw-bold text-dark text-truncate mb-2" style="font-family: var(--font-bengali); font-size: 1rem; line-height: 1.3;" title="ধর্মব্যবসার ফাঁদে">
                                    ধর্মব্যবসার ফাঁদে
                                </h6>
                            </div>
                            <p class="text-secondary small mb-0 mt-1" style="line-height: 1.5; font-size: 0.85rem;">ধর্মব্যবসার ফাঁদে</p>
                        </div>
                    </div>
                </div>

                <!-- বই ৪: জঙ্গিবাদ সঙ্কট সমাধানের উপায় -->
                <div class="col-md-6 col-lg-3">
                    <div class="family-member-card d-flex flex-column h-100 text-center">
                        <div style="height: 240px; width: 100%; border-bottom: 1px solid var(--border-color); overflow: hidden; background: #f8f9fa;" class="d-flex align-items-center justify-content-center p-3">
                            <img src="{{ asset('/uploads/pages/book_jongidab_songkot.png') }}" alt="জঙ্গিবাদ সঙ্কট সমাধানের উপায়" class="img-fluid" style="max-height: 100%; object-fit: contain;" />
                        </div>
                        <div class="family-member-info p-3 d-flex flex-column flex-grow-1 justify-content-between">
                            <div>
                                <h6 class="fw-bold text-dark text-truncate mb-2" style="font-family: var(--font-bengali); font-size: 1rem; line-height: 1.3;" title="জঙ্গিবাদ সঙ্কট সমাধানের উপায়">
                                    জঙ্গিবাদ সঙ্কট সমাধানের উপায়
                                </h6>
                            </div>
                            <p class="text-secondary small mb-0 mt-1" style="line-height: 1.5; font-size: 0.85rem;">জঙ্গিবাদ সঙ্কট সমাধানের উপায়</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ৬. ভিডিও সেকশন --}}
        <div class="card p-4 p-md-5 border-0 shadow-sm bg-white rounded-4 mt-5">
            <h3 class="section-title-center-premium"><i class="fab fa-youtube text-danger me-2"></i>ভিডিওতে একনজরে মাননীয় এমাম জনাব হোসাইন মোহাম্মদ সেলিম</h3>
            <p class="text-center text-muted mb-4">হেযবুত তওহীদের মাননীয় এমাম জনাব হোসাইন মোহাম্মদ সেলিমের সংক্ষিপ্ত পরিচিতি ও প্রামাণ্যচিত্র</p>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow">
                        <iframe src="https://www.youtube.com/embed/PC_F7hKLWHI" title="হেযবুত তওহীদের মাননীয় এমাম জনাব হোসাইন মোহাম্মদ সেলিম" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection