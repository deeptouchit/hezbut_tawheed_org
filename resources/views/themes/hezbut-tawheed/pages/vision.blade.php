@extends('theme::layouts.app')

@section('title', 'আমাদের ভিশন ও লক্ষ্য - হেযবুত তওহীদ')

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'এক নজরে হেযবুত তওহীদ',
        'subtitle' => 'আন্দোলনের পরিচিতি, মূলনীতি, লক্ষ্য ও বিস্তারিত কর্মধারা',
        'badge_text' => 'আন্দোলনের রূপরেখা ও ভিশন',
        'badge_icon' => 'fas fa-eye'
    ])

    <!-- Vision Content Section -->
    <section class="py-6 bg-off-white">
        <div class="container">
            <div class="row g-4">
                
                <!-- Left Sidebar: Sticky Scroll Navigation -->
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="position-sticky card border-0 shadow-sm rounded-4 p-3 bg-white" style="top: 100px; z-index: 10;">
                        <h6 class="fw-bold text-dark-green border-bottom pb-2 mb-3">সূচিপত্র</h6>
                        <div class="nav flex-column nav-pills gap-1">
                            <a href="#about" class="nav-link text-start py-2 px-3 small fw-bold text-muted hover-gold">১. আন্দোলনের পরিচিতি</a>
                            <a href="#establishment" class="nav-link text-start py-2 px-3 small fw-bold text-muted hover-gold">২. প্রতিষ্ঠা ও প্রতিষ্ঠাতা</a>
                            <a href="#leadership" class="nav-link text-start py-2 px-3 small fw-bold text-muted hover-gold">৩. নেতৃত্ব ও বর্তমান এমাম</a>
                            <a href="#principles" class="nav-link text-start py-2 px-3 small fw-bold text-muted hover-gold">৪. আন্দোলনের মূলনীতি</a>
                            <a href="#program" class="nav-link text-start py-2 px-3 small fw-bold text-muted hover-gold">৫. ৫ দফা কর্মসূচি</a>
                            <a href="#work-process" class="nav-link text-start py-2 px-3 small fw-bold text-muted hover-gold">৬. কর্মপ্রক্রিয়া</a>
                            <a href="#training" class="nav-link text-start py-2 px-3 small fw-bold text-muted hover-gold">৭. প্রশিক্ষণ ও খেলাধুলা</a>
                            <a href="#women" class="nav-link text-start py-2 px-3 small fw-bold text-muted hover-gold">৮. নারীদের অংশগ্রহণ</a>
                            <a href="#culture" class="nav-link text-start py-2 px-3 small fw-bold text-muted hover-gold">৯. সুস্থ সংস্কৃতি</a>
                            <a href="#uniqueness" class="nav-link text-start py-2 px-3 small fw-bold text-muted hover-gold">১০. অনন্যতা ও মাইলফলক</a>
                            <a href="#finance" class="nav-link text-start py-2 px-3 small fw-bold text-muted hover-gold">১১. অর্থের উৎস</a>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Detail Content -->
                <div class="col-lg-9 text-start">
                    
                    <!-- 1. Introduction -->
                    <div id="about" class="card border-0 shadow-sm p-4 p-md-5 rounded-4 mb-4 bg-white scroll-margin" style="border-left: 5px solid #006A4E !important;">
                        <h3 class="fw-bold text-dark-green mb-3"><i class="fas fa-info-circle text-gold me-2"></i> হেযবুত তওহীদের পরিচিতি</h3>
                        <p class="text-muted lh-lg mb-0" style="text-align: justify;">
                            হেযবুত তওহীদ সম্পূর্ণ অরাজনৈতিক ধর্মীয় সংস্কারমূলক আন্দোলন যার মূল কাজই হলো মানবজাতিকে ন্যায়ের পক্ষে ঐক্যবদ্ধ করা এবং মানবজাতির অশান্তির মূল কারণ দাজ্জালের অনুসরণ না করে সমগ্র পৃথিবীতে সৃষ্টিকর্তার সার্বভৌমত্ব প্রতিষ্ঠা করা। পুরো মানবজাতিকে আল্লাহর তওহীদের ভিত্তিতে অর্থাৎ সত্য ও ন্যায়ের পক্ষে, সকল অন্যায়ের বিরুদ্ধে ঐক্যবদ্ধ করাই হেযবুত তওহীদের মূল লক্ষ্য। মানবজীবনে সঠিক পথ, হেদায়াহ (Right Direction) ও সত্য জীবনব্যবস্থা প্রতিষ্ঠিত হলে সমস্ত মানবজাতি অন্যায় ও অবিচার থেকে মুক্তি পাবে। পৃথিবীতে প্রতিষ্ঠিত হবে অনাবিল শান্তি। সেই শান্তিময় পৃথিবী প্রতিষ্ঠার লক্ষ্যকে সামনে নিয়ে সংগ্রাম করে যাচ্ছে হেযবুত তওহীদের সদস্য-সদস্যারা।
                        </p>
                    </div>

                    <!-- 2. Establishment -->
                    <div id="establishment" class="card border-0 shadow-sm p-4 p-md-5 rounded-4 mb-4 bg-white scroll-margin" style="border-left: 5px solid #D4AF37 !important;">
                        <h3 class="fw-bold text-dark-green mb-3"><i class="fas fa-calendar-alt text-gold me-2"></i> প্রতিষ্ঠা ও প্রতিষ্ঠাতা পরিচিতি</h3>
                        <p class="text-muted lh-lg mb-3" style="text-align: justify;">
                            হেযবুত তওহীদ ১৬ ফেব্রুয়ারি, ১৯৯৫ ঈসায়ী; টাঙ্গাইল জেলার করটিয়ায় প্রতিষ্ঠা করা হয়।
                        </p>
                        <div class="p-3 rounded-3 bg-light-green mb-0" style="background-color: #f4fbf7; border-left: 4px solid #006A4E;">
                            <h5 class="fw-bold text-dark-green mb-2">প্রতিষ্ঠাতা এমামুয্যামান জনাব মোহাম্মদ বায়াজীদ খান পন্নী</h5>
                            <p class="text-muted lh-lg mb-0 small" style="text-align: justify;">
                                তিনি ১৯২৫ সনের ১১ মার্চ পবিত্র শবে বরাতে টাঙ্গাইলের করটিয়ার বিখ্যাত পন্নী পরিবারে জন্মগ্রহণ করেন। তিনি সারা জীবন ধর্মের প্রকৃত ও বৈজ্ঞানিক সংস্কারের ডাক দিয়ে গিয়েছেন। ১৬ জানুয়ারি ২০১২ ঈসায়ী তারিখে তিনি প্রত্যক্ষ দুনিয়া থেকে পর্দা (ইন্তেকাল) করেন।
                            </p>
                        </div>
                    </div>

                    <!-- 3. Leadership -->
                    <div id="leadership" class="card border-0 shadow-sm p-4 p-md-5 rounded-4 mb-4 bg-white scroll-margin" style="border-left: 5px solid #006A4E !important;">
                        <h3 class="fw-bold text-dark-green mb-3"><i class="fas fa-sitemap text-gold me-2"></i> সাংগঠনিক কাঠামো ও নেতৃত্ব</h3>
                        <p class="text-muted lh-lg mb-3">
                            হেযবুত তওহীদের সাংগঠনিক কাঠামো সুদৃঢ় শৃঙ্খলা মেনে ৩টি প্রধান স্তরে বিন্যস্ত:
                        </p>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="p-3 text-center border rounded-3 bg-white">
                                    <h5 class="fw-bold text-dark-green mb-0">এমাম</h5>
                                    <span class="text-muted small">আন্দোলনের সর্বপ্রধান নির্দেশক</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 text-center border rounded-3 bg-white">
                                    <h5 class="fw-bold text-dark-green mb-0">আমীর</h5>
                                    <span class="text-muted small">সাংগঠনিক জেলা ও কেন্দ্র প্রধান</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 text-center border rounded-3 bg-white">
                                    <h5 class="fw-bold text-dark-green mb-0">সদস্য</h5>
                                    <span class="text-muted small">موجاہد و مجاہدہ</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 mb-0" style="background-color: #fffbeb; border-left: 4px solid #D4AF37;">
                            <h5 class="fw-bold text-dark-green mb-2">বর্তমান এমাম: জনাব হোসাইন মোহাম্মদ সেলিম</h5>
                            <p class="text-muted lh-lg mb-0 small" style="text-align: justify;">
                                আমরা দৃঢ়ভাবে বিশ্বাস করি হেযবুত তওহীদ মহান সৃষ্টিকর্তার এক মহাদান, তাঁরই অশেষ রহমতে এই আন্দোলন গত ২৩ বছর ধরে অত্যন্ত সুশৃঙ্খলভাবে পরিচালিত হয়ে আসছে। আন্দোলনের প্রতিষ্ঠাতা এমামুয্যামান জনাব মোহাম্মদ বায়াজীদ খান পন্নীর মহাপ্রয়াণের পর থেকে আন্দোলনের এমাম হিসাবে দায়িত্ব পালন করে আসছেন তাঁরই আদর্শের উত্তরাধিকার জনাব হোসাইন মোহাম্মদ সেলিম। তিনি নোয়াখালী জেলার সোনাইমুড়ি থানার পোরকরা গ্রামে ২৮ নভেম্বর, ১৯৭২ ঈসায়ী সালে জন্মগ্রহণ করেন। তিনি জগন্নাথ বিশ্ববিদ্যালয় থেকে ১৯৯৬-৯৭ শিক্ষাবর্ষে রাষ্ট্রবিজ্ঞানে স্নাতকোত্তর ডিগ্রী অর্জন করেন। পড়ালেখা শেষ করে তিনি ব্যবসা-বাণিজ্যকে জীবিকা নির্বাহের মাধ্যম হিসাবে গ্রহণ করেন।
                            </p>
                        </div>
                    </div>

                    <!-- 4. Principles -->
                    <div id="principles" class="card border-0 shadow-sm p-4 p-md-5 rounded-4 mb-4 bg-white scroll-margin" style="border-left: 5px solid #D4AF37 !important;">
                        <h3 class="fw-bold text-dark-green mb-3"><i class="fas fa-balance-scale text-gold me-2"></i> আন্দোলনের মূলনীতিসমূহ</h3>
                        <p class="text-muted lh-lg mb-3">
                            হেযবুত তওহীদ কয়েকটি দৃঢ় ও প্রকাশ্য মূলনীতির উপর প্রতিষ্ঠিত ও পরিচালিত:
                        </p>
                        <ul class="list-unstyled d-flex flex-column gap-3 text-muted lh-lg mb-0">
                            <li class="d-flex align-items-start gap-2">
                                <i class="fas fa-check-circle text-gold mt-1"></i>
                                <span>হেযবুত তওহীদ চেষ্টা করে আল্লাহর রসুলের প্রতিটি পদক্ষেপকে অনুসরণ করতে।</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="fas fa-check-circle text-gold mt-1"></i>
                                <span>হেযবুত তওহীদের কোনো গোপন কার্যক্রম নেই, সবকিছু হবে প্রকাশ্য এবং দিনের আলোর মতো পরিষ্কার।</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="fas fa-check-circle text-gold mt-1"></i>
                                <span>হেযবুত তওহীদের কেউ কোনো আইন ভঙ্গ করবে না, অবৈধ অস্ত্রের সংস্পর্শে যাবে না, গেলে তাকে এমাম নিজেই আইন প্রয়োগকারী সংস্থার হাতে তুলে দেবেন।</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="fas fa-check-circle text-gold mt-1"></i>
                                <span>যারা হেযবুত তওহীদের সদস্য নয়, তাদের কাছ থেকে কোনরূপ অর্থ গ্রহণ করা হবে না।</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="fas fa-check-circle text-gold mt-1"></i>
                                <span>হেযবুত তওহীদের কোনো সদস্য কোনো প্রচলিত রাজনৈতিক কর্মকাণ্ডে সম্পৃক্ত হতে পারবে না।</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="fas fa-check-circle text-gold mt-1"></i>
                                <span>কর্মক্ষম কেউ বেকার থাকতে পারবে না, বৈধ উপায়ে রিজিক হাসিলের চেষ্টা করবে।</span>
                            </li>
                        </ul>
                    </div>

                    <!-- 5. 5-Point Program -->
                    <div id="program" class="card border-0 shadow-sm p-4 p-md-5 rounded-4 mb-4 bg-white scroll-margin" style="border-left: 5px solid #006A4E !important;">
                        <h3 class="fw-bold text-dark-green mb-3"><i class="fas fa-list-ol text-gold me-2"></i> ৫ দফা কর্মসূচি</h3>
                        <p class="text-muted lh-lg mb-4" style="text-align: justify;">
                            মানবজীবনে শান্তি প্রতিষ্ঠার জন্য মহান আল্লাহ যে কর্মসূচি তাঁর শেষ রসুলকে দান করেছিলেন, যে কর্মসূচি স্বয়ং আল্লাহর রসুল এবং তাঁর হাতে গড়া উম্মতে মোহাম্মদী অনুসরণ করেছিলেন, সেই পাঁচ দফা কর্মসূচি অনুসরণ করেই হেযবুত তওহীদ সত্যদীন, দীনুল হক প্রতিষ্ঠার সংগ্রাম চালিয়ে যাচ্ছে:
                        </p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 bg-off-white rounded-3 border-start border-3 border-green h-100">
                                    <h6 class="fw-bold text-dark-green mb-2">১. ঐক্যবদ্ধ হও</h6>
                                    <p class="text-muted small mb-0">সত্য ও ন্যায়ের পক্ষে এবং সমস্ত অন্যায়ের বিরুদ্ধে মানবজাতিকে ঐক্যবদ্ধ করা।</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-off-white rounded-3 border-start border-3 border-gold h-100">
                                    <h6 class="fw-bold text-dark-green mb-2">২. শোন</h6>
                                    <p class="text-muted small mb-0">ন্যায়ের পথে পরিচালিত করতে নেতার আদেশ আন্তরিকতার সাথে শোনা।</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-off-white rounded-3 border-start border-3 border-gold h-100">
                                    <h6 class="fw-bold text-dark-green mb-2">৩. পালন করো</h6>
                                    <p class="text-muted small mb-0">নেতার আদেশকে নিষ্ঠার সাথে আনুগত্য করা।</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-off-white rounded-3 border-start border-3 border-green h-100">
                                    <h6 class="fw-bold text-dark-green mb-2">৪. হেযরত করো</h6>
                                    <p class="text-muted small mb-0">যাবতীয় অন্যায়, অপরাধ ও অসত্যের সাথে সম্পর্ক ছিন্ন করা।</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3 bg-off-white rounded-3 border-start border-3 border-green">
                                    <h6 class="fw-bold text-dark-green mb-2">৫. সর্বাত্মক চেষ্টা</h6>
                                    <p class="text-muted small mb-0">দীনুল হক (ন্যায় ও সত্যের বিধান) পৃথিবীতে প্রতিষ্ঠার জন্য জান-মাল উৎসর্গ করে সর্বাত্মক চেষ্টা চালানো।</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 6. Work Process -->
                    <div id="work-process" class="card border-0 shadow-sm p-4 p-md-5 rounded-4 mb-4 bg-white scroll-margin" style="border-left: 5px solid #D4AF37 !important;">
                        <h3 class="fw-bold text-dark-green mb-3"><i class="fas fa-running text-gold me-2"></i> কর্মপ্রক্রিয়া</h3>
                        <p class="text-muted lh-lg mb-0" style="text-align: justify;">
                            হেযবুত তওহীদ রাষ্ট্রীয় আইনকে পূর্ণরূপে মান্য করে গত ২১ বছর ধরে আন্দোলন পরিচালনা করে আসছে। মানবজাতিকে স্রষ্টার sovereignty-র দিকে আহ্বান করার জন্য হেযবুত তওহীদ এমামুয্যামানের বক্তব্য ও লেখা সম্বলিত হ্যান্ডবিল, বই, পত্রিকা, প্রামাণ্যচিত্র ইত্যাদি সর্বশ্রেণীর মানুষের কাছে পৌঁছানোর জন্য সর্বাত্মক চেষ্টা করে থাকে। এরই অংশ হিসাবে বাসে, ট্রেনে, লঞ্চে, রাস্তাঘাটে এই প্রকাশনা সামগ্রীগুলি বিক্রয়, বই মেলায় স্টল গ্রহণ, মিলনায়তন ও প্রেস ক্লাবগুলিতে প্রকাশ্য সেমিনার, আইনশৃঙ্খলা বাহিনীর উর্ধ্বতন কর্মকর্তা ও সরকারের মন্ত্রীদের সাথে মতবিনিময়ের মাধ্যমে আমাদের সব কার্যক্রম নিয়মিত অবহিত করে থাকি।
                        </p>
                    </div>

                    <!-- 7. Training & Sports -->
                    <div id="training" class="card border-0 shadow-sm p-4 p-md-5 rounded-4 mb-4 bg-white scroll-margin" style="border-left: 5px solid #006A4E !important;">
                        <h3 class="fw-bold text-dark-green mb-3"><i class="fas fa-dumbbell text-gold me-2"></i> প্রশিক্ষণ ও খেলাধুলা</h3>
                        <h5 class="fw-bold text-dark mb-2">প্রশিক্ষণ</h5>
                        <p class="text-muted lh-lg mb-4" style="text-align: justify;">
                            চরিত্র গঠনের জন্য প্রধান প্রশিক্ষণ হচ্ছে সঠিক পদ্ধতিতে সালাহ বা নামাজ কায়েম করা। হেযবুত তওহীদকে আল্লাহ সালাতের সঠিক উদ্দেশ্য ও প্রক্রিয়া দান করেছেন। সদস্যদের ইস্পাতের মতো ঐক্যবদ্ধ এবং পিঁপড়ার মতো সুশৃঙ্খল চরিত্র গড়ে তোলার জন্য এই আধ্যাত্মিক প্রশিক্ষণ অত্যন্ত গুরুত্বপূর্ণ ভূমিকা রাখে।
                        </p>
                        <h5 class="fw-bold text-dark mb-2">খেলাধুলা ও শরীরচর্চা</h5>
                        <p class="text-muted lh-lg mb-0" style="text-align: justify;">
                            সদস্যদের শারীরিক সুস্থতা ও গতিশীলতা বৃদ্ধির জন্য খেলাধুলার ব্যবস্থা রয়েছে। এক্ষেত্রে দেশীয় আউটডোর খেলা যেমন কাবাডি, ফুটবল, ব্যাডমিন্টন ইত্যাদিকে প্রাধান্য দেওয়া হয়। জুয়া ও বাজিমুক্ত সুস্থ ধারার খেলাধুলাই আমাদের মূল স্পিরিট।
                        </p>
                    </div>

                    <!-- 8. Women's Participation -->
                    <div id="women" class="card border-0 shadow-sm p-4 p-md-5 rounded-4 mb-4 bg-white scroll-margin" style="border-left: 5px solid #D4AF37 !important;">
                        <h3 class="fw-bold text-dark-green mb-3"><i class="fas fa-female text-gold me-2"></i> সকল কাজে নারীদের অংশগ্রহণ</h3>
                        <p class="text-muted lh-lg mb-0" style="text-align: justify;">
                            রসুলাল্লাহর সময় যেমন পুরুষদের পাশাপাশি নারীরাও সামাজিক কাজে অংশ নিয়েছেন, ঠিক তেমনি হেযবুত তওহীদেও আমীরের দায়িত্ব থেকে শুরু করে অফিসিয়াল কাজ, অ্যাকাউন্টস, মিডিয়া-প্রকাশনা এবং বই-পত্রিকা বিক্রির কাজেও নারীরা শরিয়াহ নির্ধারিত যথাযথ হেজাব অনুসরণ করে সক্রিয়ভাবে অংশগ্রহণ করেন।
                        </p>
                    </div>

                    <!-- 9. Culture -->
                    <div id="culture" class="card border-0 shadow-sm p-4 p-md-5 rounded-4 mb-4 bg-white scroll-margin" style="border-left: 5px solid #006A4E !important;">
                        <h3 class="fw-bold text-dark-green mb-3"><i class="fas fa-music text-gold me-2"></i> সুস্থ সংস্কৃতি ও বিনোদন</h3>
                        <p class="text-muted lh-lg mb-0" style="text-align: justify;">
                            হেযবুত তওহীদ সুস্থ ধারার সংস্কৃতিকে লালন করে। এমামুয্যামানের স্মরণে হেযবুত তওহীদের প্রকাশিত প্রথম গানের অ্যালবাম “দ্য লিডার অব দ্য টাইম’। সেমিনারগুলিতে হেযবুত তওহীদের সদস্যরা সঙ্গীত পরিবেশন করে থাকে। অশ্লীলতাহীন সুস্থ বিনোদন ইসলামে সম্পূর্ণ বৈধ।
                        </p>
                    </div>

                    <!-- 10. Uniqueness & Milestones -->
                    <div id="uniqueness" class="card border-0 shadow-sm p-4 p-md-5 rounded-4 mb-4 bg-white scroll-margin" style="border-left: 5px solid #D4AF37 !important;">
                        <h3 class="fw-bold text-dark-green mb-3"><i class="fas fa-award text-gold me-2"></i> অনন্যতা ও বৃহত্তম মাইলফলক</h3>
                        <h5 class="fw-bold text-dark mb-2">আইন মান্য করার অনন্য রেকর্ড</h5>
                        <p class="text-muted lh-lg mb-4" style="text-align: justify;">
                            হেযবুত তওহীদ গত ২৭ বছরে দেশের একটিও আইনভঙ্গ করেনি, এর কোনো সদস্য একটিও অপরাধ করেনি। আমাদের বিরুদ্ধে পাঁচ শতাধিক মিথ্যা মামলা দায়ের করা হলেও কোনো সদস্যের আইনভঙ্গের প্রমাণ পাওয়া যায়নি। আইন মান্য করার এরূপ দৃষ্টান্ত বিরল।
                        </p>
                        <h5 class="fw-bold text-dark mb-2">বৃহত্তম মাইলফলক</h5>
                        <p class="text-muted lh-lg mb-0" style="text-align: justify;">
                            ২ ফেব্রুয়ারি ২০০৮ তারিখে মহান আল্লাহ এক মহান মো’জেজা অর্থাৎ অলৌকিক ঘটনা সংঘটন করেন যার দ্বারা তিনি হেযবুত তওহীদের সত্যতা ও এর এমাম আল্লাহর মনোনীত হক এমাম হওয়ার বিষয়টি সত্যায়ন করেন।
                        </p>
                    </div>

                    <!-- 11. Source of Funds -->
                    <div id="finance" class="card border-0 shadow-sm p-4 p-md-5 rounded-4 mb-0 bg-white scroll-margin" style="border-left: 5px solid #006A4E !important;">
                        <h3 class="fw-bold text-dark-green mb-3"><i class="fas fa-coins text-gold me-2"></i> অর্থের উৎস</h3>
                        <p class="text-muted lh-lg mb-0" style="text-align: justify;">
                            হেযবুত তওহীদের সদস্যরা সম্পূর্ণ নিজেদের উপার্জিত বা অর্জিত ব্যক্তিগত সম্পদ ব্যয় করে এই আদর্শিক আন্দোলন পরিচালনা করেন। আন্দোলনের সদস্য নন এমন কারো কাছ থেকে কোনো অর্থ গ্রহণ করা হয় না।
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Style additions for scroll margins and active pills -->
    <style>
        .scroll-margin {
            scroll-margin-top: 100px;
        }
        .nav-pills .nav-link {
            transition: all 0.3s ease;
            font-size: 0.9rem;
            border-left: 3px solid transparent !important;
            border-radius: 0 8px 8px 0;
            text-align: left;
        }
        .nav-pills .nav-link:hover {
            background-color: #f1f5f9;
            color: #006A4E !important;
            border-left-color: #D4AF37 !important;
        }
        /* Sticky sidebar navigation highlight logic simple css */
        .nav-pills .nav-link.active {
            background-color: #006A4E !important;
            color: #fff !important;
            border-left-color: #D4AF37 !important;
        }
    </style>

@endsection
