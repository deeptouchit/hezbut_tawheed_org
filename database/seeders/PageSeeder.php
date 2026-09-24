<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing pages
        DB::table('pages')->truncate();

        $pages = [
            [
                'title' => 'আমাদের সম্পর্কে',
                'slug' => 'about-us',
                'meta_description' => 'হেজবুত তওহীদ একটি অরাজনৈতিক আন্দোলন যা সমাজ সংস্কার, মানবতার কল্যাণ ও শান্তি প্রতিষ্ঠায় নিবেদিত।',
                'content' => '
                    <div class="row align-items-center mb-5">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <img src="/uploads/about/at-a-glance.jpg" alt="এক নজরে হেযবুত তওহীদ" class="img-fluid rounded-4 shadow-sm" />
                        </div>
                        <div class="col-lg-6 ps-lg-5">
                            <h3 style="color: #006A4E; font-weight: 700; margin-top: 0; margin-bottom: 0.5rem;">এক নজরে হেযবুত তওহীদ</h3>
                            <p class="lead text-muted mb-4" style="font-size: 1.1rem; font-weight: 600; line-height: 1.6;">হেযবুত তওহীদ সম্পর্কে যাদের জানার আকাঙ্ক্ষা আছে, তাদের জন্য অতি সংক্ষেপে হেযবুত তওহীদ সম্পর্কে তুলে ধরা হলো</p>
                            <p>হেযবুত তওহীদ সম্পূর্ণ অরাজনৈতিক ধর্মীয় সংস্কারমূলক আন্দোলন যার মূল কাজই হলো মানবজাতিকে ন্যায়ের পক্ষে ঐক্যবদ্ধ করা এবং মানবজাতির অশান্তির মূল কারণ দাজ্জালের অনুসরণ না করে সমগ্র পৃথিবীতে সৃষ্টিকর্তার সার্বভৌমত্ব প্রতিষ্ঠা করা। পুরো মানবজাতিকে আল্লাহর তওহীদের ভিত্তিতে অর্থাৎ সত্য ও ন্যায়ের পক্ষে, সকল অন্যায়ের বিরুদ্ধে ঐক্যবদ্ধ করাই হেযবুত তওহীদের মূল লক্ষ্য। মানবজীবনে সঠিক পথ, হেদায়াহ (Right Direction) ও সত্য জীবনব্যবস্থা প্রতিষ্ঠিত হলে সমস্ত মানবজাতি অন্যায় ও অবিচার থেকে মুক্তি পাবে। পৃথিবীতে প্রতিষ্ঠিত হবে অনাবিল শান্তি। সেই শান্তিময় পৃথিবী প্রতিষ্ঠার লক্ষ্যকে সামনে নিয়ে সংগ্রাম করে যাচ্ছে হেযবুত তওহীদের সদস্য-সদস্যারা।</p>
                        </div>
                    </div>

                    <div class="row align-items-center mb-5">
                        <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                            <img src="/uploads/about/bayazid-khan-panni.jpg" alt="প্রতিষ্ঠা" class="img-fluid rounded-4 shadow-sm" />
                        </div>
                        <div class="col-lg-6 pe-lg-5 order-lg-1">
                            <h3 style="color: #006A4E; font-weight: 700;">প্রতিষ্ঠা</h3>
                            <p>হেযবুত তওহীদ ১৬ ফেব্রুয়ারি, ১৯৯৫ ঈসায়ী; টাঙ্গাইল জেলার করটিয়ায় প্রতিষ্ঠা করা হয়। হেযবুত তওহীদের প্রতিষ্ঠাতা এমামুয্যামান জনাব মোহাম্মদ বায়াজীদ খান পন্নী। তিনি ১৯২৫ সনের ১১ মার্চ পবিত্র শবে বরাতে টাঙ্গাইলের করটিয়ার বিখ্যাত পন্নী পরিবারে জন্মগ্রহণ করেন। ১৬ জানুয়ারি ২০১২ ঈসায়ী তারিখে তিনি প্রত্যক্ষ দুনিয়া থেকে পর্দা (ইন্তেকাল) করেন।</p>
                            <p><a href="/emamuzzaman" class="btn btn-success rounded-pill px-4">প্রতিষ্ঠাতা সম্পর্কে বিস্তারিত</a></p>
                        </div>
                    </div>

                    <div class="card p-4 p-md-5 mb-5 border-0 bg-light rounded-4">
                        <h3 class="text-center mb-4" style="color: #006A4E; font-weight: 700;">কাঠামো</h3>
                        <div class="row justify-content-center align-items-center text-center g-3">
                            <div class="col-md-3">
                                <div class="p-3 bg-white rounded-3 shadow-sm border" style="border-top: 4px solid #D4AF37 !important;">
                                    <i class="fas fa-crown fa-2x mb-2 text-warning"></i>
                                    <h5 class="fw-bold mb-0">এমাম</h5>
                                </div>
                            </div>
                            <div class="col-md-1 text-success fs-3 py-2">
                                <span class="d-none d-md-inline">➜</span>
                                <span class="d-inline d-md-none">⬇</span>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 bg-white rounded-3 shadow-sm border" style="border-top: 4px solid #006A4E !important;">
                                    <i class="fas fa-user-shield fa-2x mb-2 text-success"></i>
                                    <h5 class="fw-bold mb-0">আমীর</h5>
                                </div>
                            </div>
                            <div class="col-md-1 text-success fs-3 py-2">
                                <span class="d-none d-md-inline">➜</span>
                                <span class="d-inline d-md-none">⬇</span>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 bg-white rounded-3 shadow-sm border" style="border-top: 4px solid #006A4E !important;">
                                    <i class="fas fa-users fa-2x mb-2 text-primary"></i>
                                    <h5 class="fw-bold mb-0">সদস্য</h5>
                                    <span class="small text-muted">(মোজাহেদ-মোজাহেদা)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center mb-5">
                        <div class="col-lg-5 mb-4 mb-lg-0 text-center">
                            <img src="/uploads/about/h-m-selim.jpg" alt="বর্তমান এমাম" class="img-fluid rounded-4 shadow-sm" style="max-height: 380px; object-fit: cover;" />
                        </div>
                        <div class="col-lg-7 ps-lg-5">
                            <h3 style="color: #006A4E; font-weight: 700;">বর্তমান এমাম</h3>
                            <p>আমরা দৃঢ়ভাবে বিশ্বাস করি হেযবুত তওহীদ মহান সৃষ্টিকর্তার এক মহাদান, তাঁরই অশেষ রহমতে এই আন্দোলন গত ২৩ বছর ধরে অত্যন্ত সুশৃঙ্খলভাবে পরিচালিত হয়ে আসছে। মহান আল্লাহ এই আন্দোলনের প্রতিষ্ঠাতা এমামুয্যামান জনাব মোহাম্মদ বায়াজীদ খান পন্নীকে প্রকৃত ইসলামের জ্ঞান দান করলে তিনি এই আন্দোলন প্রতিষ্ঠা করেন। আমরা (হেযবুত তওহীদের সদস্য-সদস্যারা) তাঁর নিকট থেকে প্রকৃত ইসলামের বিষয়ে জ্ঞান লাভ করেছি, হেদায়াতের রাস্তা পেয়েছি, এ জন্য আমরা তাঁকে মাননীয় এমামুয্যামান তথা যুগের নেতা হিসাবে মান্য করি। তাঁর মহাপ্রয়াণের পর থেকে আন্দোলনের এমাম হিসাবে দায়িত্ব পালন করে আসছেন তাঁরই আদর্শের উত্তরাধিকার জনাব হোসাইন মোহাম্মদ সেলিম। তিনি নোয়াখালী জেলার সোনাইমুড়ি থানার পোরকরা গ্রামে ২৮ নভেম্বর, ১৯৭২ ঈসায়ী সালে জন্মগ্রহণ করেন। তিনি জগন্নাথ বিশ্ববিদ্যালয় থেকে ১৯৯৬-৯৭ শিক্ষাবর্ষে রাষ্ট্রবিজ্ঞানে স্নাতকোত্তর ডিগ্রী অর্জন করেন। পড়ালেখা শেষ করে তিনি ব্যবসা-বাণিজ্যকে জীবিকা নির্বাহের মাধ্যম হিসাবে গ্রহণ করেন।</p>
                            <p><a href="/emam-ht" class="btn btn-success rounded-pill px-4">এমাম সম্পর্কে বিস্তারিত</a></p>
                        </div>
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-lg-6">
                            <div class="p-4 bg-white border rounded-4 h-100" style="border-top: 4px solid #006A4E !important;">
                                <h4 class="fw-bold mb-3" style="color: #006A4E;"><i class="fas fa-list-check me-2"></i> মূলনীতি</h4>
                                <ul class="ps-3">
                                    <li class="mb-2">হেযবুত তওহীদ চেষ্টা করে আল্লাহর রসুলের প্রতিটি পদক্ষেপকে অনুসরণ করতে।</li>
                                    <li class="mb-2">হেযবুত তওহীদের কোনো গোপন কার্যক্রম নেই, সবকিছু হবে প্রকাশ্য এবং দিনের আলোর মতো পরিষ্কার।</li>
                                    <li class="mb-2">হেযবুত তওহীদের কেউ কোনো আইন ভঙ্গ করবে না, অবৈধ অস্ত্রের সংস্পর্শে যাবে না, গেলে তাকে এমাম নিজেই আইন প্রয়োগকারী সংস্থার হাতে তুলে দেবেন।</li>
                                    <li class="mb-2">যারা হেযবুত তওহীদের সদস্য নয়, তাদের কাছ থেকে কোনরূপ অর্থ গ্রহণ করা হবে না।</li>
                                    <li class="mb-2">হেযবুত তওহীদের কোনো সদস্য কোনো প্রচলিত রাজনৈতিক কর্মকাণ্ডে সম্পৃক্ত হতে পারবে না।</li>
                                    <li class="mb-2">কর্মক্ষম কেউ বেকার থাকতে পারবে না, বৈধ উপায়ে রিজিক হাসিলের চেষ্টা করবে।</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="p-4 text-white rounded-4 h-100" style="background: linear-gradient(135deg, #004b37 0%, #002d21 100%);">
                                <h4 class="fw-bold mb-3" style="color: #10B981;"><i class="fas fa-bullseye me-2"></i> কর্মসূচি</h4>
                                <p class="small text-white-50">পাঁচ দফা কর্মসূচি অনুসরণ করেই হেযবুত তওহীদ সত্যদীন, দীনুল হক প্রতিষ্ঠার সংগ্রাম চালিয়ে যাচ্ছে:</p>
                                <ol class="ps-3">
                                    <li class="mb-2">ঐক্যবদ্ধ হও।</li>
                                    <li class="mb-2">(নেতার আদেশ) শোন।</li>
                                    <li class="mb-2">(নেতার ঐ আদেশ) পালন করো।</li>
                                    <li class="mb-2">হেযרת (যাবতীয় অন্যায়ের সঙ্গে সম্পর্কত্যাগ) করো।</li>
                                    <li class="mb-2">এই দীনুল হক (ন্যায়, সত্য) পৃথিবীতে প্রতিষ্ঠার জন্য সর্বাত্মক চেষ্টা, প্রচেষ্টা।</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center mb-5">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <img src="/uploads/about/process.jpg" alt="কর্মপ্রক্রিয়া" class="img-fluid rounded-4 shadow-sm" />
                        </div>
                        <div class="col-lg-6 ps-lg-5">
                            <h3 style="color: #006A4E; font-weight: 700;">কর্মপ্রক্রিয়া</h3>
                            <p>হেযবুত তওহীদ রাষ্ট্রীয় আইনকে পূর্ণরূপে মান্য করে গত ২১ বছর ধরে আন্দোলন পরিচালনা করে আসছে। মানবজাতিকে স্রষ্টার সার্বভৌমত্বের দিকে আহ্বান করার জন্য হেযবুত তওহীদ মাননীয় এমামুয্যামানের বক্তব্য ও লেখা সম্বলিত হ্যান্ডবিল, বই, পত্রিকা, প্রামাণ্যচিত্র ইত্যাদি সর্বশ্রেণির মানুষের কাছে পৌঁছানোর জন্য সর্বাত্মক চেষ্টা করে থাকে। bereavement বাসে, ট্রেনে, লঞ্চে, রাস্তাঘাটে এই প্রকাশনা সামগ্রীগুলি বিক্রয়, বই মেলায় স্টল গ্রহণ, শিল্পকলা একাডেমী, পৌর মিলনায়তন, জাতীয় প্রেসক্লাব, পাবলিক লাইব্রেরির সেমিনার ক¶, ঢাকা বিশ্ববিদ্যালয়ের সিনেট হল, জাতীয় যাদুঘরের সেমিনার ক¶সহ বাংলাদেশ সরকারের মন্ত্রী-এমপিদের উপস্থিতিতে, সকল ধর্মের সম্মানিত ব্যক্তি ও ধর্মগুরুদের নিয়ে মতবিনিময়ের মাধ্যমে, এমনকি আইনশৃঙ্খলা বাহিনীর উর্ধ্বতন কর্মকর্তাদের সাথে দেখা করে আমাদের সকল কার্যক্রম সম্পর্কে নিয়মিতভাবে অবহিত করে থাকি এবং প্রকাশনাসমূহ দিয়ে আমাদের বক্তব্য সম্পর্কে জানিয়ে থাকি।</p>
                        </div>
                    </div>

                    <div class="row align-items-center mb-5">
                        <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                            <img src="/uploads/about/training.jpg" alt="প্রশিক্ষণ" class="img-fluid rounded-4 shadow-sm" />
                        </div>
                        <div class="col-lg-6 pe-lg-5 order-lg-1">
                            <h3 style="color: #006A4E; font-weight: 700;">প্রশিক্ষণ</h3>
                            <p>মানবতার মুক্তির লক্ষ্যে আল্লাহর সত্যদীন প্রতিষ্ঠার জন্য নিঃস্বার্থভাবে নিজেদের জীবন ও সম্পদ উৎসর্গ করে সংগ্রাম করে যাওয়ার জন্য প্রয়োজন ridicuলে চরিত্রবল, আত্মিক শক্তি, সবর, লক্ষের প্রতি অবিচলতা (হানিফ)। সেই চরিত্র হতে হবে প্রধানত উপরোক্ত পাঁচ দফা ভিত্তিক অর্থাৎ তাদেরকে হতে হবে ইস্পাতের মত ঐক্যবদ্ধ, পিঁপড়ার মতো সুশৃঙ্খল, স্রষ্টার প্রতি প্রকৃতির মতো আনুগত্যশীল, সকল অন্যায়ের বিরুদ্ধে নির্ভীক, কঠোর, প্রতিবাদী, নিঃস্বার্থ মানবপ্রেমী ও সংগ্রামী। এই চরিত্র অর্জনের জন্য হেযবুত তওহীদের প্রশি¶ণ হচ্ছে সঠিক পদ্ধতিতে সালাহ বা নামাজ কায়েম করা। হেযবুত তওহীদকে আল্লাহ সালাতের সঠিক উদ্দেশ্য ও প্রক্রিয়া দান করেছেন। সালাতের বাইরে হেযবুত তওহীদ শারীরিক ও মানসিক স্বাস্থ্য বজায় রাখার জন্য বিভিন্ন শরীরচর্চামূলক খেলাকে (যেমন কাবাডি, ফুটবল, সাঁতার) উৎসাহিত করে থাকে।</p>
                        </div>
                    </div>

                    <div class="row align-items-center mb-5">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <img src="/uploads/about/women.jpg" alt="সকল কাজে নারীদের অংশগ্রহণ" class="img-fluid rounded-4 shadow-sm" />
                        </div>
                        <div class="col-lg-6 ps-lg-5">
                            <h3 style="color: #006A4E; font-weight: 700;">সকল কাজে নারীদের অংশগ্রহণ</h3>
                            <p>রসুলাল্লাহর সময় যেমন পুরুষ আসহাবদের পাশাপাশি নারী আসহাবগণও জাতীয় ও সামাজিক প্রায় সকল কাজে অংশগ্রহণ করেছেন ঠিক তেমনি হেযবুত তওহীদ আন্দোলনের প্রায় সকল কাজে পুরুষের পাশাপাশি নারীরাও অংশগ্রহণ করে থাকে। আমীরের দায়িত্ব থেকে শুরু করে অফিসিয়াল কাজ, বিভিন্ন ডিপার্টমেন্টের কাজ (যেমন: প্রিন্ট ও ইলেকট্রনিক মিডিয়ার কাজ, হিসাব র¶ণ বিভাগের কাজ, সাংস্কৃতিক কর্মকাণ্ড ইত্যাদি) এমনকি পত্রিকা, বই বিক্রির কাজেও নারীরা শরিয়াহ নির্ধারিত যথাযথ হেজাব অনুসরণ করে পুরুষের সাথে অংশগ্রহণ করেন।</p>
                        </div>
                    </div>

                    <div class="row align-items-center mb-5">
                        <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                            <img src="/uploads/about/culture.jpg" alt="সাংস্কৃতিক কর্মকাণ্ড" class="img-fluid rounded-4 shadow-sm" />
                        </div>
                        <div class="col-lg-6 pe-lg-5 order-lg-1">
                            <h3 style="color: #006A4E; font-weight: 700;">সাংস্কৃতিক কর্মকাণ্ড</h3>
                            <p>হেযবুত তওহীদ সুস্থ ধারার সংস্কৃতিকে লালন করে। এমামুয্যামানের স্মরণে হেযবুত তওহীদের প্রকাশিত প্রথম গানের অ্যালবাম “দ্য লিডার অব দ্য টাইম’। সেমিনারগুলি হেযবুত তওহীদের সদস্য-সদস্যরা যন্ত্রানুসঙ্গ সহযোগে সঙ্গীত পরিবেশন করে। আমরা জানি অশ্লীলতা ইসলামে হারাম বা অবৈধ কিন্তু যা অশ্লীল নয়, সুস্থ- এমন বিনোদন ইসলামে বৈধ।</p>
                        </div>
                    </div>

                    <div class="row align-items-center mb-5">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <img src="/uploads/about/sports.jpg" alt="খেলাধুলা" class="img-fluid rounded-4 shadow-sm" />
                        </div>
                        <div class="col-lg-6 ps-lg-5">
                            <h3 style="color: #006A4E; font-weight: 700;">খেলাধুলা</h3>
                            <p>একটি শক্তিশালী, বহির্মুখী, গতিশীল জাতির জন্য প্রয়োজন সুস্থ, সবল, গতিশীল, উদ্দমী নাগরিক। আর সুস্থ, সবল নাগরিক গড়ে তুলতে খেলাধুলা ও শরীর চর্চার কোনো বিকল্প নেই। কাজেই হেযবুত তওহীদ আন্দোলনের সদস্যদের মধ্যে শারীরিক সুস্থতা, ক্ষিপ্রতা, গতিশীলতা, সাহসিকতা ইত্যাদি বৃদ্ধির জন্য খেলাধুলার ব্যবস্থা রয়েছে। এক্ষেত্রে দেশীয় বা আন্তর্জাতিক বহিরাঙ্গনের (আউটডোর) খেলা যেমন কাবাডি, হা-ডু-ডু, ফুটবল, দৌঁড়, সাতার, ব্যাডমিন্টন ইত্যাদিকে প্রাধান্য দেওয়া হয়। যেসব খেলা মানুষকে অন্তর্মুখী ও স্থবির করে ফেলে সেগুলো নিরুৎসাহিত করা হয় এবং যে কোনো খেলায়র  অসুস্থ প্রতিযোগিতা, অর্থের লেনদেন, জুয়া বা বাজি ইত্যাদি এড়িয়ে চলতে বলা হয়।</p>
                        </div>
                    </div>

                    <div class="row align-items-center mb-5">
                        <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                            <img src="/uploads/about/milestone.jpg" alt="বৃহত্তম মাইলফলক" class="img-fluid rounded-4 shadow-sm" />
                        </div>
                        <div class="col-lg-6 pe-lg-5 order-lg-1">
                            <h3 style="color: #006A4E; font-weight: 700;">বৃহত্তম মাইলফলক</h3>
                            <p>২ ফেব্রুয়ারি ২০০৮ তারিখে মহান আল্লাহ এক মহান মো’জেজা অর্থাৎ অলৌকিক ঘটনা সংঘটন করেন যার দ্বারা তিনি তিনটি বিষয় সত্যায়ন করেন। যথা: হেযবুত তওহীদ হক (সত্য), এর এমাম আল্লাহর মনোনীত হক এমাম, হেযবুত তওহীদের মাধ্যমে সারা পৃথিবীতে আল্লাহর সত্যদীন প্রতিষ্ঠিত হবে ইনশাল্লাহ।</p>
                        </div>
                    </div>

                    <div class="row align-items-center mb-5">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <img src="/uploads/about/uniqueness.jpg" alt="অনন্যতা" class="img-fluid rounded-4 shadow-sm" />
                        </div>
                        <div class="col-lg-6 ps-lg-5">
                            <h3 style="color: #006A4E; font-weight: 700;">অনন্যতা</h3>
                            <p>হেযবুত তওহীদ গত ২৭ বছরে দেশের একটিও আইনভঙ্গ করেনি, এর কোন সদস্য একটিও অপরাধ করেনি। এর প্রমাণ গত ২৭ বছরে এই আন্দোলনের বিরুদ্ধে পাঁচ শতাধিক অধিক মিথ্যা মামলা দায়ের করা হয়েছে কিন্তু একটি মামলাতেও এর কোন একজন সদস্যেরও কোন আইনভঙ্গের প্রমাণ পাওয়া যায়নি। সুতরাং তাদের কেউ সাজাপ্রাপ্ত হননি। আইন মান্য করার এরূপ দৃষ্টান্ত দেশের আইন-শৃঙ্খলা রক্ষাকারী বাহিনীগুলির একটিও দেখাতে পারে নি। অথচ ধর্মব্যবসায়ীদের ষড়যন্ত্র এবং ধর্মভিত্তিক রাজনীতিক কতিপয় দলের ষড়যন্ত্রের শিকার হয়ে এ পর্যন্ত আমাদের চার জন ভাই-বোন শহীদ হয়েছেন, শত শত আহত ও পঙ্গু হয়েছেন, বহু বাড়ি-ঘর লুটপাট, অগ্নিসংযোগ করা হয়েছে।</p>
                        </div>
                    </div>

                    <div class="row align-items-center mb-5">
                        <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                            <img src="/uploads/about/finance.jpg" alt="অর্থের উৎস" class="img-fluid rounded-4 shadow-sm" />
                        </div>
                        <div class="col-lg-6 pe-lg-5 order-lg-1">
                            <h3 style="color: #006A4E; font-weight: 700;">অর্থের উৎস</h3>
                            <p>হেযবুত তওহীদের সদস্যরা নিজেদের উপার্জিত বা অর্জিত সম্পদ ব্যয় করে আন্দোলনের কাজ করে থাকেন।</p>
                        </div>
                    </div>

                    <div class="card p-4 p-md-5 mb-5 border-0 bg-light rounded-4">
                        <h3 class="mb-3 text-center" style="color: #006A4E; font-weight: 700;">পরিচালিত প্রতিষ্ঠান</h3>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <span class="badge bg-success p-3 fs-6">তওহীদ প্রকাশন</span>
                            <span class="badge bg-success p-3 fs-6">তওহীদ কাবাডি দল</span>
                            <span class="badge bg-success p-3 fs-6">দৈনিক বজ্রশক্তি</span>
                            <span class="badge bg-success p-3 fs-6">ইলদ্রিম মিডিয়া</span>
                            <span class="badge bg-success p-3 fs-6">বাংলাদেশের পত্র (অনলাইন পত্রিকা)</span>
                            <span class="badge bg-success p-3 fs-6">jatiyatv.com (অনলাইন)</span>
                        </div>
                    </div>

                    <div class="card p-4 p-md-5 mb-5 border-0 bg-white rounded-4 border">
                        <h3 class="mb-4 text-center" style="color: #006A4E; font-weight: 700;">প্রকাশিত পুস্তকসমূহ</h3>
                        <div class="row g-3">
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">ইসলামের প্রকৃত রূপরেখা</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">ইসলামের প্রকৃত সালাহ্</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">দাজ্জাল? ইহুদি -খ্রিষ্টান ‘সভ্যতা’!</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">Dajjal? The Judeo-Christian Materialistic \'Civilization\'!</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">হেযবুত তওহীদের লক্ষ্য ও উদ্দেশ্য</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">জেহাদ, কেতাল ও সন্ত্রাস</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">আল্লাহর মো’জেজা: হেযবুত তওহীদের বিজয় ঘোষণা</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">বর্তমানের বিকৃত সুফিবাদ</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">ঔপনিবেশিক ষড়যন্ত্রমূলক শিক্ষাব্যবস্থা</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">যুগসন্ধিণে আমরা</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">বাঘ-বন-বন্দুক</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">গণপ্রজাতন্ত্রী বাংলাদেশ সরকারের প্রতি যামানার এমামের পত্রাবলী</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">বিশ্বনবী মোহাম্মদ (সা.) এর ভাষণ</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">ইসলাম শুধু নাম থাকবে</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">যামানার এমামের পক্ষ থেকে মহাসত্যের আহ্বান</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">এ জাতির পায়ে লুটিয়ে পড়বে বিশ্ব</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">আসুন-সিস্টেমটাকেই পাল্টাই</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">চলমান সঙ্কট নিরসনে হেযবুত তওহীদের প্রস্তাবনা</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">Divide and Rule: শোষণের হাতিয়ার</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">জোরপূর্বক শ্রমব্যবস্থাই দাসত্ব</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">দান: ইসলামের অর্থনীতির চালিকাশক্তি</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">সম্মানিত আলেমদের প্রতি</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">শ্রেণিহীন সমাজ, সাম্যবাদ, প্রকৃত ইসলাম</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">জঙ্গিবاد সঙ্কট সমাধানের উপায়</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">পাশ্চাত্যের মানসিক দাসত্ব এবং আমাদের গণমাধ্যম</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">তওহীদ জান্নাতের চাবি</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">The Lost Islam</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">ধর্মব্যবসার ফাঁদে</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">ইসলাম কেন আবেদন হারাচ্ছে?</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">মোমেন, মুসলিম ও উম্মতে মোহাম্মদীর আকিদা</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">তাকওয়া ও হেদায়াহ</div></div>
                            <div class="col-md-6 col-lg-4"><div class="p-2 border-start border-success border-3 bg-light rounded-1">সওমের উদ্দেশ্য</div></div>
                        </div>
                    </div>

                    <div class="card p-4 p-md-5 border-0 bg-white rounded-4 border">
                        <h3 class="mb-4 text-center" style="color: #006A4E; font-weight: 700;">প্রামাণ্যচিত্র</h3>
                        <div class="row g-4">
                            <div class="col-md-6 col-lg-4">
                                <div class="border rounded-3 overflow-hidden shadow-sm bg-light">
                                    <a href="https://www.youtube.com/watch?v=EMZgDNuyQEc" target="_blank">
                                        <img src="https://img.youtube.com/vi/EMZgDNuyQEc/hqdefault.jpg" class="w-100" style="aspect-ratio: 16/9; object-fit: cover;" />
                                        <div class="p-3 fw-bold text-dark text-decoration-none">এক জাতি এক দেশ, ঐক্যবদ্ধ বাংলাদেশ</div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="border rounded-3 overflow-hidden shadow-sm bg-light">
                                    <a href="https://www.youtube.com/watch?v=NDfi55itQas" target="_blank">
                                        <img src="https://img.youtube.com/vi/NDfi55itQas/hqdefault.jpg" class="w-100" style="aspect-ratio: 16/9; object-fit: cover;" />
                                        <div class="p-3 fw-bold text-dark">ধর্মব্যবসা ও ধর্ম নিয়ে অপ-রাজনীতির ইতিবৃত্ত</div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="border rounded-3 overflow-hidden shadow-sm bg-light">
                                    <a href="https://www.youtube.com/watch?v=CkTYeo_Mprg" target="_blank">
                                        <img src="https://img.youtube.com/vi/CkTYeo_Mprg/hqdefault.jpg" class="w-100" style="aspect-ratio: 16/9; object-fit: cover;" />
                                        <div class="p-3 fw-bold text-dark">নারীর মর্যাদা</div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="border rounded-3 overflow-hidden shadow-sm bg-light">
                                    <a href="https://www.youtube.com/watch?v=xLBkPPFfL1c" target="_blank">
                                        <img src="https://img.youtube.com/vi/xLBkPPFfL1c/hqdefault.jpg" class="w-100" style="aspect-ratio: 16/9; object-fit: cover;" />
                                        <div class="p-3 fw-bold text-dark">দাজ্জাল? ইহুদি-খ্রিষ্টান ‘সভ্যতা’!</div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="border rounded-3 overflow-hidden shadow-sm bg-light">
                                    <a href="https://www.youtube.com/watch?v=o48SfmJmMxc" target="_blank">
                                        <img src="https://img.youtube.com/vi/o48SfmJmMxc/hqdefault.jpg" class="w-100" style="aspect-ratio: 16/9; object-fit: cover;" />
                                        <div class="p-3 fw-bold text-dark">দাজ্জাল প্রতিরোধকারীদের সম্মান ও পুরস্কার</div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="border rounded-3 overflow-hidden shadow-sm bg-light">
                                    <a href="https://www.youtube.com/watch?v=c5e7asI2QRs" target="_blank">
                                        <img src="https://img.youtube.com/vi/c5e7asI2QRs/hqdefault.jpg" class="w-100" style="aspect-ratio: 16/9; object-fit: cover;" />
                                        <div class="p-3 fw-bold text-dark">অন্যান্য দল না করে হেযবুত তওহীদ কেন করব?</div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="border rounded-3 overflow-hidden shadow-sm bg-light">
                                    <a href="https://www.youtube.com/watch?v=0S1eFZCZObk" target="_blank">
                                        <img src="https://img.youtube.com/vi/0S1eFZCZObk/hqdefault.jpg" class="w-100" style="aspect-ratio: 16/9; object-fit: cover;" />
                                        <div class="p-3 fw-bold text-dark">সকল ধর্মের মর্মকথা-সবার ঊর্ধ্বে মানবতা</div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="border rounded-3 overflow-hidden shadow-sm bg-light">
                                    <a href="https://www.youtube.com/watch?v=4OxhXGvIFHw" target="_blank">
                                        <img src="https://img.youtube.com/vi/4OxhXGvIFHw/hqdefault.jpg" class="w-100" style="aspect-ratio: 16/9; object-fit: cover;" />
                                        <div class="p-3 fw-bold text-dark">সন্ত্রাসবাদ</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                '
            ],
            [
                'title' => 'প্রতিষ্ঠাতা এমামুয্যামান',
                'slug' => 'emamuzzaman',
                'meta_description' => 'হেজবুত তওহীদের প্রতিষ্ঠাতা এমামুয্যামান জনাব মোহাম্মদ বায়াজীদ খান পন্নী এর পরিচিতি ও আদর্শ।',
                'content' => '
                    <div class="page-content py-4">
                        <h2 class="text-success mb-4">প্রতিষ্ঠাতা এমামুয্যামান মোহাম্মদ বায়াজীদ খান পন্নী</h2>
                        <div class="row align-items-center mb-5">
                            <div class="col-md-4 mb-4 mb-md-0 text-center">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=300" class="img-fluid rounded-4 shadow-sm" alt="এমামুয্যামান">
                            </div>
                            <div class="col-md-8">
                                <p class="lead text-secondary">টাঙ্গাইলের ঐতিহ্যবাহী পন্নী পরিবারে জন্ম নেওয়া জনাব মোহাম্মদ বায়াজীদ খান পন্নী ছিলেন একাধারে সমাজ সংস্কারক, লেখক, চিন্তাবিদ ও সত্যের সন্ধানী এক মহান ব্যক্তিত্ব। ১৯৯৬ সালে তিনি হেজবুত তওহীদ আন্দোলনের সূচনা করেন।</p>
                                <p class="text-secondary">তিনি সমাজ থেকে ধর্মান্ধতা, অজ্ঞতা ও সংকীর্ণতা দূরীকরণে তাঁর সমগ্র জীবন উৎসর্গ করেছিলেন। তাঁর ক্ষুরধার লেখনী ও অসামান্য বক্তব্যের মাধ্যমে তিনি মানুষের কাছে ইসলামের প্রকৃত ও আদি রূপ তুলে ধরেছেন।</p>
                            </div>
                        </div>

                        <h3 class="text-success mb-4">প্রকাশিত উল্লেখযোগ্য গ্রন্থসমূহ</h3>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light p-3">
                                    <h5><strong>হেজবুত তওহীদ (আন্দোলনের রূপরেখা)</strong></h5>
                                    <p class="text-secondary mb-0">আন্দোলনের আদর্শ ও সামগ্রিক কার্যক্রমের তাত্ত্বিক ভিত্তি নিয়ে লেখা যুগান্তকারী গ্রন্থ।</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light p-3">
                                    <h5><strong>ইসলামের প্রকৃত রূপরেখা</strong></h5>
                                    <p class="text-secondary mb-0">গতানুগতিক ধর্মীয় সংকীর্ণতার ঊর্ধ্বে উঠে ইসলামের শাশ্বত শান্তির আদর্শের ব্যাখ্যা।</p>
                                </div>
                            </div>
                        </div>
                    </div>
                '
            ],
            [
                'title' => 'বর্তমান এমাম',
                'slug' => 'emam-ht',
                'meta_description' => 'হেজবুত তওহীদের বর্তমান এমাম জনাব হোসাইন মোহাম্মদ সেলিম এর সংক্ষিপ্ত জীবনী ও সংস্কারমূলক বার্তা।',
                'content' => '
                    <div class="page-content py-4">
                        <h2 class="text-success mb-4">বর্তমান এমাম হোসাইন মোহাম্মদ সেলিম</h2>
                        <div class="row align-items-center mb-5">
                            <div class="col-md-4 mb-4 mb-md-0 text-center">
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=300" class="img-fluid rounded-4 shadow-sm" alt="এমাম হোসাইন মোহাম্মদ সেলিম">
                            </div>
                            <div class="col-md-8">
                                <p class="lead text-secondary">২০১২ সালে প্রতিষ্ঠাতা এমামুয্যামানের ইন্তেকালের পর আন্দোলনের সামগ্রিক দায়িত্বভার গ্রহণ করেন জনাব হোসাইন মোহাম্মদ সেলিম। তাঁর গতিশীল ও যুগোপযোগী নেতৃত্বে আন্দোলনটি দেশজুড়ে একটি শক্তিশালী শান্তি আন্দোলনের রূপ নিয়েছে।</p>
                                <p class="text-secondary">তিনি উগ্রপন্থা, সাম্প্রদায়িক সহিংসতা এবং অপরাজনীতির বিরুদ্ধে আপসহীন অবস্থান নিয়েছেন। দেশব্যাপী বিভিন্ন জাতীয় সেমিনার, সংবাদ সম্মেলন ও শান্তি সমাবেশে দেওয়া তাঁর যুক্তিনির্ভর বক্তব্য ও বলিষ্ঠ বক্তব্য সর্বমহলে প্রশংসিত হয়েছে।</p>
                            </div>
                        </div>

                        <h3 class="text-success mb-3">সংস্কারমূলক কার্যক্রম ও লক্ষ্য</h3>
                        <p class="text-secondary">এমাম হোসাইন মোহাম্মদ সেলিম সমাজকে ধর্মান্ধতার অন্ধকার থেকে মুক্ত করে আধুনিক বিজ্ঞানমনস্ক ও সম্প্রীতিময় সমাজ গঠনে গুরুত্ব দিচ্ছেন। যুবসমাজকে ইতিবাচক কাজে নিয়োজিত করতে তিনি নানা উদ্ভাবনী উদ্যোগ ও তরুণদের খেলাধুলায় উদ্বুদ্ধ করার কর্মসূচি গ্রহণ করেছেন।</p>
                    </div>
                '
            ],
            [
                'title' => 'প্রতীক ও মনোগ্রাম',
                'slug' => 'monogram',
                'meta_description' => 'হেজবুত তওহীদের মনোগ্রাম, প্রতীক এবং এর অন্তর্নিহিত দর্শন ও অর্থ।',
                'content' => '
                    <div class="page-content py-4">
                        <h2 class="text-success mb-4">হেজবুত তওহীদের প্রতীক ও মনোগ্রাম</h2>
                        <p class="lead text-secondary">হেজবুত তওহীদের অফিশিয়াল মনোগ্রামটি একটি শক্তিশালী প্রতীকী অর্থ বহন করে। এটি সত্যের লড়াই, শান্তি ও মানবতার সুরক্ষার বার্তা ধারণ করে।</p>
                        
                        <div class="row my-5 align-items-center">
                            <div class="col-md-4 text-center mb-4 mb-md-0">
                                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                                    <i class="fas fa-shield-alt fa-4x"></i>
                                </div>
                            </div>
                            <div class="col-md-8 text-secondary">
                                <h4><strong>मनোগ্রামের উপাদানসমূহের ব্যাখ্যাঃ</strong></h4>
                                <ul class="list-unstyled mt-3">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> <strong>ঢাল (Shield):</strong> এটি আত্মরক্ষা, সত্যের সুরক্ষা এবং অন্যায়ের বিরুদ্ধে প্রতিরোধের প্রতীক।</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> <strong>সবুজ রঙ:</strong> এটি শান্তি, নবজীবন, সমৃদ্ধি এবং ইসলামের চিরন্তন সত্যের প্রতীক।</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> <strong>চাঁদ ও তারকা:</strong> এটি সার্বজনীন দিকনির্দেশনা ও অন্ধকারের বুকে সত্যের আলোর প্রতীক।</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                '
            ],
            [
                'title' => 'দৃষ্টিভঙ্গি ও রূপকল্প',
                'slug' => 'vision',
                'meta_description' => 'হেজবুত তওহীদের দৃষ্টিভঙ্গি, রূপকল্প এবং একটি শান্তিময় বিশ্ব গড়ার লক্ষ্য।',
                'content' => '
                    <div class="page-content py-4">
                        <h2 class="text-success mb-4">আমাদের দৃষ্টিভঙ্গি ও রূপকল্প (Vision)</h2>
                        <p class="lead text-secondary">আমাদের মূল লক্ষ্য হচ্ছে সমাজে বিদ্যমান যাবতীয় অন্যায়, অবিচার, ধর্মীয় উগ্রবাদ ও অন্ধকারের মূলোৎপাটন করে সত্য ও ন্যায়ের প্রতিষ্ঠা নিশ্চিত করা।</p>
                        
                        <h4 class="text-success mt-4 mb-3">প্রধান নির্দেশকসমূহঃ</h4>
                        <div class="row g-4 text-secondary">
                            <div class="col-md-6">
                                <div class="p-3 border-start border-success border-3 bg-light">
                                    <h5><strong>অসাম্প্রদায়িক চেতনা</strong></h5>
                                    <p class="mb-0">ধর্ম, বর্ণ ও জাতিগত বৈষম্যহীন সমাজ গঠন, যেখানে প্রতিটি মানুষ পারস্পরিক শ্রদ্ধাবোধ নিয়ে শান্তিতে বসবাস করবে।</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border-start border-success border-3 bg-light">
                                    <h5><strong>ধর্মান্ধতার প্রতিরোধ</strong></h5>
                                    <p class="mb-0">ধর্মকে ব্যক্তিগত স্বার্থ বা হিংসা ছড়ানোর হাতিয়ার হিসেবে ব্যবহারের বিরুদ্ধে গণসচেতনতা তৈরি করা।</p>
                                </div>
                            </div>
                        </div>
                    </div>
                '
            ],
            [
                'title' => 'আন্দোলনের মূলনীতি',
                'slug' => 'principles',
                'meta_description' => 'হেজবুত তওহীদ আন্দোলনের পাঁচটি প্রধান মূলনীতি ও কর্মপন্থা।',
                'content' => '
                    <div class="page-content py-4">
                        <h2 class="text-success mb-4">আন্দোলনের মূলনীতি ও অঙ্গীকার</h2>
                        <p class="lead text-secondary">হেজবুত তওহীদ সুনির্দিষ্ট কিছু স্তম্ভ এবং মূলনীতির ওপর ভিত্তি করে কাজ করে। আমাদের প্রতিটি কর্মী এই আদর্শ মেনে চলতে প্রতিজ্ঞাবদ্ধ।</p>
                        
                        <div class="mt-4">
                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-success text-white rounded-circle px-3 py-2 me-3 fw-bold">১</div>
                                <div>
                                    <h5><strong>অহিংস ও শান্তিময় কর্মপন্থা</strong></h5>
                                    <p class="text-secondary">আমরা সর্বাবস্থায় অহিংস ও নিয়মতান্ত্রিক উপায়ে আমাদের বক্তব্য প্রচার করি। কোনোরূপ বলপ্রয়োগ বা বেআইনি কাজের সমর্থন আমরা করি না।</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-success text-white rounded-circle px-3 py-2 me-3 fw-bold">২</div>
                                <div>
                                    <h5><strong>দেশপ্রেম ও আইনানুগ আনুগত্য</strong></h5>
                                    <p class="text-secondary">দেশের সংবিধান, প্রচলিত আইন এবং আইন প্রয়োগকারী সংস্থার প্রতি পূর্ণ শ্রদ্ধা রাখা এবং যেকোনো সংকট মোকাবেলায় রাষ্ট্রকে সহযোগিতা করা।</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-success text-white rounded-circle px-3 py-2 me-3 fw-bold">৩</div>
                                <div>
                                    <h5><strong>সাম্প্রদায়িক সম্প্রীতি</strong></h5>
                                    <p class="text-secondary">সব ধর্মের মানুষের নাগরিক ও মৌলিক অধিকার নিশ্চিত করার সপক্ষে আপসহীন অবস্থান রাখা।</p>
                                </div>
                            </div>
                        </div>
                    </div>
                '
            ],
            [
                'title' => 'আন্দোলনের ঘোষণাপত্র',
                'slug' => 'declaration',
                'meta_description' => 'হেজবুত তওহীদ আন্দোলনের আনুষ্ঠানিক ঘোষণাপত্র।',
                'content' => '
                    <div class="page-content py-4">
                        <h2 class="text-success mb-4">হেজবুত তওহীদের ঐতিহাসিক ঘোষণাপত্র</h2>
                        <p class="lead text-secondary">এই ঘোষণাপত্রের লক্ষ্য হলো মানবজাতিকে ধর্মান্ধতার অন্ধকার থেকে মুক্ত করে মানবতার জয়গান গাওয়া এবং ভ্রাতৃত্ব প্রতিষ্ঠা করা।</p>
                        <blockquote class="blockquote border-start border-5 border-success ps-4 my-4">
                            <p class="mb-0 text-secondary italic">"আমরা ঘোষণা করছি যে, কোনো ধর্মই উগ্রবাদ, মানুষের রক্তপাত ও অবিচার সমর্থন করে না। প্রকৃত ধর্ম মানুষের কল্যাণের জন্য, তাকে ধ্বংস করার জন্য নয়।"</p>
                        </blockquote>
                        <p class="text-secondary">হেজবুত তওহীদ ধর্মকে রাজনৈতিক স্বার্থে ব্যবহারের বিরুদ্ধে এবং বিজ্ঞানমনস্ক ও যুক্তিনির্ভর মানবিক সমাজ বিনির্মাণের সপক্ষে তার দৃঢ় অঙ্গীকার পুনর্ব্যক্ত করছে।</p>
                    </div>
                '
            ],
            [
                'title' => 'প্রশ্নোত্তর ও জিজ্ঞাসা (FAQ)',
                'slug' => 'faq',
                'meta_description' => 'হেজবুত তওহীদ সম্পর্কে সাধারণ কিছু প্রশ্ন ও তার দাপ্তরিক উত্তর।',
                'content' => '
                    <div class="page-content py-4">
                        <h2 class="text-success mb-4">সাধারণ জিজ্ঞাসা ও প্রশ্নোত্তর (FAQ)</h2>
                        
                        <div class="accordion accordion-flush" id="faqAccordion">
                            <div class="accordion-item mb-3 shadow-sm border-0 rounded">
                                <h2 class="accordion-header">
                                    <button class="accordion-button rounded fw-bold text-success" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        ১. হেজবুত তওহীদ কি কোনো রাজনৈতিক দল?
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-secondary">
                                        না, হেজবুত তওহীদ সম্পূর্ণ অরাজনৈতিক একটি আন্দোলন। এটি কোনো নির্বাচনে অংশ নেয় না এবং ক্ষমতার রাজনীতিতে বিশ্বাসী নয়। এর একমাত্র উদ্দেশ্য নৈতিক ও সামাজিক সংস্কার।
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item mb-3 shadow-sm border-0 rounded">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed rounded fw-bold text-success" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        ২. এই আন্দোলনের আয়ের উৎস কী?
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-secondary">
                                        আন্দোলনের সদস্যরা সম্পূর্ণ নিজস্ব অর্থায়নে এবং স্বেচ্ছাপ্রদত্ত দানের মাধ্যমে এর ব্যয়ভার বহন করেন। এছাড়া আন্দোলনের কিছু নিজস্ব প্রকাশনা ও সমাজসেবামূলক কুটির শিল্পের মাধ্যমে প্রাপ্ত লভ্যাংশ এর তহবিল জোগাতে ব্যবহৃত হয়।
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                '
            ],
            [
                'title' => 'আমাদের কর্মসূচিসমূহ',
                'slug' => 'programs',
                'meta_description' => 'হেজবুত তওহীদের দেশব্যাপী আয়োজিত নিয়মিত কর্মসূচিসমূহ।',
                'content' => '
                    <div class="page-content py-4">
                        <h2 class="text-success mb-4">দেশব্যাপী চলমান কর্মসূচিসমূহ</h2>
                        <p class="lead text-secondary">হেজবুত তওহীদ জনসচেতনতা বৃদ্ধি এবং মানবিক মূল্যবোধের বিকাশে দেশব্যাপী নিয়মিত বিভিন্ন কর্মসূচি আয়োজন করে থাকে।</p>
                        
                        <div class="row g-4 mt-3">
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm p-3">
                                    <div class="text-success mb-2"><i class="fas fa-comments fa-2x"></i></div>
                                    <h5><strong>শান্তি সেমিনার</strong></h5>
                                    <p class="text-secondary small">উগ্রবাদ ও মাদকাসক্তির অসারতা নিয়ে স্কুল-কলেজ, মাদ্রাসা ও বিশ্ববিদ্যালয় পর্যায়ে সেমিনার আয়োজন।</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm p-3">
                                    <div class="text-success mb-2"><i class="fas fa-book-open fa-2x"></i></div>
                                    <h5><strong>পত্রিকা ও প্রকাশনা বিতরণ</strong></h5>
                                    <p class="text-secondary small">ইসলামের সত্য রূপ ব্যাখ্যা করে লিফলেট ও বই সর্বস্তরের জনগণের মাঝে বিনামূল্যে বিতরণ।</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm p-3">
                                    <div class="text-success mb-2"><i class="fas fa-users fa-2x"></i></div>
                                    <h5><strong>মতবিনিময় সভা</strong></h5>
                                    <p class="text-secondary small">বিভিন্ন ধর্মীয় প্রতিনিধি, বুদ্ধিজীবী ও সুশীল সমাজের প্রতিনিধিদের সাথে জাতীয় অগ্রগতি ও সম্প্রীতি নিয়ে আলোচনা।</p>
                                </div>
                            </div>
                        </div>
                    </div>
                '
            ],
            [
                'title' => 'সামাজিক উদ্যোগ',
                'slug' => 'social',
                'meta_description' => 'আর্তমানবতার সেবায় হেজবুত তওহীদের সমাজসেবামূলক ও জনকল্যাণকর কাজসমূহ।',
                'content' => '
                    <div class="page-content py-4">
                        <h2 class="text-success mb-4">মানবিক ও সামাজিক উদ্যোগসমূহ</h2>
                        <p class="lead text-secondary">সন্ত্রাসবাদ বিরোধী সচেতনতা তৈরির পাশাপাশি হেজবুত তওহীদ সমাজ ও দেশের কল্যাণে বহুমুখী মানবিক কাজ সম্পন্ন করে আসছে।</p>
                        
                        <div class="mt-4 text-secondary">
                            <h5 class="text-success"><i class="fas fa-heartbeat me-2"></i>রক্তদান ও ফ্রি মেডিকেল ক্যাম্প</h5>
                            <p class="mb-4">জরুরি প্রয়োজনে রোগীদের জন্য স্বেচ্ছায় রক্তদান করা এবং দেশের প্রত্যন্ত অঞ্চলে অসচ্ছল মানুষের মাঝে বিনামূল্যে চিকিৎসা সেবা ও ঔষধ বিতরণ।</p>
                            
                            <h5 class="text-success"><i class="fas fa-hand-holding-water me-2"></i>দুর্যোগকালীন মানবিক সহায়তা</h5>
                            <p class="mb-4">বন্যা, ঝড় বা যেকোনো প্রাকৃতিক দুর্যোগের সময় আক্রান্ত মানুষদের পাশে দাঁড়িয়ে শুকনো খাবার, বিশুদ্ধ পানি এবং পরিধেয় বস্ত্র বিতরণ।</p>
                            
                            <h5 class="text-success"><i class="fas fa-tree me-2"></i>বৃক্ষরোপণ কর্মসূচি</h5>
                            <p class="mb-0">পরিবেশের ভারসাম্য রক্ষা ও বৈশ্বিক জলবায়ু পরিবর্তন মোকাবেলায় প্রতি বছর দেশব্যাপী হাজার হাজার চারাগাছ রোপণ ও বিতরণ।</p>
                        </div>
                    </div>
                '
            ],
            [
                'title' => 'প্রশিক্ষণ ও ক্রীড়া',
                'slug' => 'training',
                'meta_description' => 'ইউবসমাজকে দক্ষ ও মাদকমুক্ত রাখতে হেজবুত তওহীদের ক্রীড়া ও প্রশিক্ষণ কর্মসূচি।',
                'content' => '
                    <div class="page-content py-4">
                        <h2 class="text-success mb-4">প্রশিক্ষণ, শারীরিক গঠন ও ক্রীড়া</h2>
                        <p class="lead text-secondary">তরুণ প্রজন্মকে মাদকের ভয়াল গ্রাস ও বিভিন্ন অপসংস্কৃতি থেকে দূরে রেখে যোগ্য নাগরিক হিসেবে গড়ে তুলতে ক্রীড়া ও শারীরিক প্রশিক্ষণের বিকল্প নেই।</p>
                        <p class="text-secondary">আমাদের কর্মীবাহিনীকে সর্বদা সুশৃঙ্খল ও কর্মক্ষম রাখতে শারীরিক চর্চা ও নানা আত্মরক্ষামূলক কৌশল প্রশিক্ষণের ব্যবস্থা করা হয়। এছাড়া স্থানীয় ও জাতীয় পর্যায়ে ফুটবল, ভলিবল এবং দৌড় প্রতিযোগিতার মতো নিয়মিত টুর্নামেন্টের আয়োজন করা হয় যা যুবসমাজকে ইতিবাচক কাজে ব্যস্ত রাখে।</p>
                    </div>
                '
            ],
            [
                'title' => 'গবেষণা ও উন্নয়ন',
                'slug' => 'research',
                'meta_description' => 'ইসলামি ইতিহাস, উগ্রবাদের কাউন্টার-ন্যারেটিভ এবং সমসাময়িক সমাজ গবেষণায় আমাদের অবদান।',
                'content' => '
                    <div class="page-content py-4">
                        <h2 class="text-success mb-4">গবেষণা ও উন্নয়ন উইং</h2>
                        <p class="lead text-secondary">হেজবুত তওহীদের অন্যতম মূল শক্তি হচ্ছে এর গবেষণামূলক কাজ। সমাজে সাম্প্রদায়িক অপশক্তি ছড়ানো ভুল ব্যাখ্যার তাত্ত্বিক জবাব তৈরি করা আমাদের গবেষণার অন্যতম মূল লক্ষ্য।</p>
                        <p class="text-secondary">আমাদের গবেষক দল ইসলামের আদি ইতিহাস, খেলাফতের মূল দর্শন এবং উগ্রবাদের কাউন্টার-ন্যারেটিভ বা গঠনমূলক বিশ্লেষণ ও গবেষণামূলক কাজ পরিচালনা করেন। এই গবেষণার ফসলগুলো বিভিন্ন বই, কলাম ও বুকলেটের মাধ্যমে সবার কাছে সহজে পৌঁছে দেওয়া হয়।</p>
                    </div>
                '
            ],
            [
                'title' => 'সোনাইমুড়ী ট্র্যাজেডি',
                'slug' => 'sonaimuri-tragedy',
                'meta_description' => '২০১৪ সালের ১১ই মার্চ নোয়াখালীর সোনাইমুড়ীতে হেজবুত তওহীদের ওপর উগ্রপন্থীদের বর্বর হামলার করুণ ইতিহাস।',
                'content' => '
                    <div class="page-content py-4">
                        <h2 class="text-danger mb-4">১১ই মার্চের সোনাইমুড়ী ট্র্যাজেডি</h2>
                        <p class="lead text-secondary">২০১৪ সালের ১১ই মার্চ নোয়াখালীর সোনাইমুড়ীতে হেজবুত তওহীদের সদস্যদের ওপর একদল উগ্রপন্থী নির্মম ও বর্বরোচিত হামলা চালায়। এই কাপুরুষোচিত হামলায় হেজবুত তওহীদের দুজন নিবেদিতপ্রাণ সদস্যকে নৃশংসভাবে হত্যা করা হয় এবং অনেকের বাড়িঘর পুড়িয়ে দেওয়া হয়।</p>
                        <p class="text-secondary">হামলায় নিহত শহীদ সুলাইমান এবং শহীদ ইব্রাহিম এর এই আত্মত্যাগ চিরকাল উগ্রবাদের বিরুদ্ধে ও শান্তির পথে আমাদের অনুপ্রাণিত করবে। হেজবুত তওহীদ এই বর্বর হামলার তীব্র নিন্দা জানিয়ে শান্তিপূর্ণভাবে ও রাষ্ট্রীয় আইনের প্রতি পূর্ণ আস্থা রেখে বিচার প্রার্থনা করে আসছে।</p>
                    </div>
                '
            ],
            [
                'title' => 'অডিও লাইব্রেরি',
                'slug' => 'audios',
                'meta_description' => 'হেজবুত তওহীদের প্রতিষ্ঠাতা ও বর্তমান এমামের গুরুত্বপূর্ণ বক্তব্য এবং ইসলামি সঙ্গীতের অডিও সংগ্রহশালা।',
                'content' => '
                    <div class="page-content py-4">
                        <h2 class="text-success mb-4">অডিও সংগ্রহশালা (Lectures & Audio)</h2>
                        <p class="lead text-secondary">আমাদের অডিও লাইব্রেরিতে পাবেন আন্দোলনের প্রতিষ্ঠাতা এমামুয্যামান এবং বর্তমান এমামের দেওয়া গুরুত্বপূর্ণ বক্তব্যের অডিও ফাইলসমূহ।</p>
                        <p class="text-secondary">এছাড়াও নৈতিক উদ্দীপনা জাগানো ইসলামি গান, দেশাত্মবোধক সঙ্গীত ও বিভিন্ন শিক্ষণীয় আলোচনা অডিও আকারে শোনার ব্যবস্থা রাখা হয়েছে। আপনি খুব শীঘ্রই আমাদের এই লাইব্রেরি থেকে সরাসরি অডিওসমূহ শুনতে এবং ডাউনলোড করতে পারবেন।</p>
                    </div>
                '
            ]
        ];

        foreach ($pages as $page) {
            Page::create([
                'title' => $page['title'],
                'slug' => $page['slug'],
                'meta_description' => $page['meta_description'],
                'content' => trim($page['content']),
                'is_active' => true
            ]);
        }
    }
}
