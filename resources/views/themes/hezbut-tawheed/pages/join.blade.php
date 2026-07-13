@extends('theme::layouts.app')

@section('title', 'যোগদান করুন - হেজবুত তওহীদ')

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'আন্দোলনে যোগদান করুন',
        'subtitle' => 'সত্যের আলো ছড়াতে এবং মানবকল্যাণে হেজবুত তওহীদের সাথে যুক্ত হোন',
        'badge_text' => 'আন্দোলনে যোগদান',
        'badge_icon' => 'fas fa-user-plus'
    ])

    <!-- Join main container -->
    <div class="py-5" style="background-color: #f8fafc; font-family: 'Baloo Da 2', sans-serif;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    
                    <!-- Tabs Navigation Buttons -->
                    <ul class="nav nav-pills justify-content-center gap-3 mb-5" id="joinTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active py-3 px-4 rounded-3 fw-bold border border-light-grey text-dark transition custom-tab-btn" 
                                    id="primary-tab" data-bs-toggle="tab" data-bs-target="#primary-content" type="button" role="tab" 
                                    aria-controls="primary-content" aria-selected="true" style="font-size: 0.95rem;">
                                <i class="fas fa-id-card me-2 tab-icon"></i> প্রাথমিক সদস্য পদ
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 px-4 rounded-3 fw-bold border border-light-grey text-dark transition custom-tab-btn" 
                                    id="pledge-tab" data-bs-toggle="tab" data-bs-target="#pledge-content" type="button" role="tab" 
                                    aria-controls="pledge-content" aria-selected="false" style="font-size: 0.95rem;">
                                <i class="fas fa-file-contract me-2 tab-icon"></i> পাঁচ দফা ভিত্তিক অঙ্গীকার পত্র
                            </button>
                        </li>
                    </ul>

                    @php
                        $ways = ['বই পড়ে', 'ব্যক্তির মাধ্যমে', 'ভিডিও দেখে', 'সোশ্যাল মিডিয়া', 'ওয়েবসাইট', 'পত্রিকা/ম্যাগাজিন'];
                    @endphp

                    <div class="tab-content" id="joinTabContent">
                        
                        <!-- TAB 1: Primary Membership Description & Form -->
                        <div class="tab-pane fade show active animate-fade-in" id="primary-content" role="tabpanel" aria-labelledby="primary-tab">
                            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white border-light-grey mb-4" 
                                 style="border-top: 4px solid #006A4E !important;">
                                
                                <div class="d-flex justify-content-center my-3">
                                    <div class="px-4 py-2 border border-2 border-dark rounded-3 bg-light" style="max-width: 280px; text-align: center;">
                                        <span class="fw-bold text-dark" style="font-size: 1.15rem; font-family: 'Baloo Da 2', sans-serif; letter-spacing: 0.5px;">প্রাথমিক সদস্য ফরম</span>
                                    </div>
                                </div>
                                <h2 class="text-center fw-bold mb-4" style="color: #006A4E; font-size: 2.2rem; font-family: 'Baloo Da 2', sans-serif;">হেযবুত তওহীদ</h2>

                                <div class="p-4 rounded-4 mb-5 border border-light-grey" style="background-color: #fcfdfc; border-left: 5px solid #006A4E !important; font-family: 'Baloo Da 2', sans-serif;">
                                    <p class="lh-lg text-dark mb-3" style="font-size: 1.05rem; text-align: justify; font-weight: 600;">
                                        আমি এই মর্মে সাক্ষ্য দিচ্ছি যে, allah ছাড়া কোনো ইলাহ (হুকুমদাতা, বিধানদাতা) নেই এবং হযরত মুহাম্মদ (স.) আল্লাহর প্রেরিত রসুল। আমি বিশ্বাস করি, মানবজীবনে শান্তি ও সুবিচার প্রতিষ্ঠার জন্য আল্লাহ তাঁর শেষ নবীর মাধ্যমে হেদায়াহ ও সত্য দীন প্রেরণ করেছেন। বর্তমান বিশ্বে চলমান অশান্তি দূর করে শান্তি কায়েমের জন্য আল্লাহর দেওয়া সেই হেদায়াহ ও সত্য দীন প্রতিষ্ঠার কোনো বিকল্প নেই। পৃথিবীতে শান্তি প্রতিষ্ঠার এই মহান লক্ষ্য নিয়েই হেযবুত তওহীদ সংগ্রাম চালিয়ে যাচ্ছে।
                                    </p>
                                    <p class="lh-lg text-dark mb-0" style="font-size: 1.05rem; text-align: justify; font-weight: 600;">
                                        আমি হেযবুত তওহীদের এই লক্ষ্য ও সংগ্রামের সঙ্গে একাত্মতা পোষণ করে এ আন্দোলনের এমাম জনাব হোসাইন মোহাম্মদ সেলিমের নেতৃত্বে দীন প্রতিষ্ঠার সংগ্রামে শামিল হলাম। আমি আমার জান-মাল দিয়ে আল্লাহর দীন প্রতিষ্ঠার এই সংগ্রামে সদা সচেষ্ট থাকব, ইনশাআল্লাহ।
                                    </p>
                                </div>

                                <!-- FORM 1: Primary Membership Form -->
                                <div class="pt-2">
                                    <form action="{{ route('join.submit') }}" method="POST" style="font-family: 'Baloo Da 2', sans-serif;">
                                        @csrf
                                        <input type="hidden" name="membership_type" value="primary">

                                        <div class="row g-4">
                                            <!-- Group 1: Personal Info -->
                                            <div class="col-12">
                                                <h5 class="fw-bold mb-1 d-flex align-items-center gap-2" style="color: #006A4E; font-size: 1.25rem;">
                                                    <span class="d-inline-flex align-items-center justify-content-center bg-light text-success-brand rounded-circle" style="width: 28px; height: 28px; font-size: 0.85rem;"><i class="fas fa-user-circle"></i></span>
                                                    ব্যক্তিগত তথ্য
                                                </h5>
                                            </div>
                                            
                                            <!-- Name -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">নাম *</label>
                                                    <div class="input-group-custom">
                                                        <span class="input-icon"><i class="fas fa-user"></i></span>
                                                        <input type="text" name="name" class="form-control-custom" placeholder="আপনার পূর্ণ নাম লিখুন" value="{{ old('name') }}" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Date -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">তারিখ *</label>
                                                    <div class="input-group-custom">
                                                        <span class="input-icon"><i class="fas fa-calendar-alt"></i></span>
                                                        <input type="date" name="join_date" class="form-control-custom" value="{{ old('join_date', date('Y-m-d')) }}" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Father's Name -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">পিতার নাম</label>
                                                    <div class="input-group-custom">
                                                        <span class="input-icon"><i class="fas fa-user-friends"></i></span>
                                                        <input type="text" name="father_husband" class="form-control-custom" placeholder="পিতার নাম লিখুন" value="{{ old('father_husband') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Age -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">বয়স *</label>
                                                    <div class="input-group-custom">
                                                        <span class="input-icon"><i class="fas fa-birthday-cake"></i></span>
                                                        <input type="text" name="age" class="form-control-custom" placeholder="আপনার বয়স লিখুন" value="{{ old('age') }}" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Occupation -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">পেশা</label>
                                                    <div class="input-group-custom">
                                                        <span class="input-icon"><i class="fas fa-briefcase"></i></span>
                                                        <input type="text" name="occupation" class="form-control-custom" placeholder="আপনার পেশা লিখুন" value="{{ old('occupation') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Education -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">শিক্ষা</label>
                                                    <div class="input-group-custom">
                                                        <span class="input-icon"><i class="fas fa-graduation-cap"></i></span>
                                                        <input type="text" name="education" class="form-control-custom" placeholder="শিক্ষাগত যোগ্যতা লিখুন" value="{{ old('education') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Group 2: Contact Info -->
                                            <div class="col-12 mt-3">
                                                <h5 class="fw-bold mb-1 d-flex align-items-center gap-2" style="color: #006A4E; font-size: 1.25rem;">
                                                    <span class="d-inline-flex align-items-center justify-content-center bg-light text-success-brand rounded-circle" style="width: 28px; height: 28px; font-size: 0.85rem;"><i class="fas fa-address-book"></i></span>
                                                    যোগাযোগের তথ্য
                                                </h5>
                                            </div>

                                            <!-- Mobile No -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">মোবাইল নম্বর *</label>
                                                    <div class="input-group-custom">
                                                        <span class="input-icon"><i class="fas fa-phone-alt"></i></span>
                                                        <input type="text" name="phone" class="form-control-custom" placeholder="১১ ডিজিটের মোবাইল নম্বর লিখুন" value="{{ old('phone') }}" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Current Unit & Amir -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">বর্তমান ইউনিট ও আমির</label>
                                                    <div class="input-group-custom">
                                                        <span class="input-icon"><i class="fas fa-users-cog"></i></span>
                                                        <input type="text" name="current_unit_amir" class="form-control-custom" placeholder="ইউনিট ও আমিরের নাম (যদি থাকে)" value="{{ old('current_unit_amir') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Address -->
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">ঠিকানা *</label>
                                                    <div class="input-group-custom align-items-start">
                                                        <span class="input-icon pt-3"><i class="fas fa-map-marker-alt"></i></span>
                                                        <textarea name="present_address" rows="3" class="form-control-custom" placeholder="আপনার ঠিকানা বিস্তারিত লিখুন" required style="height: auto; padding-top: 12px;">{{ old('present_address') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Group 3: Media -->
                                            <div class="col-12 mt-3">
                                                <h5 class="fw-bold mb-1 d-flex align-items-center gap-2" style="color: #006A4E; font-size: 1.25rem;">
                                                    <span class="d-inline-flex align-items-center justify-content-center bg-light text-success-brand rounded-circle" style="width: 28px; height: 28px; font-size: 0.85rem;"><i class="fas fa-question-circle"></i></span>
                                                    কীভাবে জেনেছেন (মাধ্যম)? *
                                                </h5>
                                            </div>

                                            <!-- How did you know -->
                                            <div class="col-12">
                                                <div class="row g-2">
                                                    @foreach($ways as $index => $way)
                                                        <div class="col-6 col-md-4">
                                                            <label class="way-select-card" for="wayPrimary{{ $index }}">
                                                                <input class="d-none check-way-primary" type="radio" name="how_knew" value="{{ $way }}" id="wayPrimary{{ $index }}" required {{ old('how_knew') == $way ? 'checked' : '' }}>
                                                                <span class="way-card-content">
                                                                    <i class="far fa-circle status-dot"></i>
                                                                    <span class="way-name">{{ $way }}</span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Conditional Person Fields -->
                                            <div class="col-md-6 person-fields-primary" style="display: none;">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">পরিচিত ব্যক্তির নাম</label>
                                                    <div class="input-group-custom">
                                                        <span class="input-icon"><i class="fas fa-user-check"></i></span>
                                                        <input type="text" name="person_name" class="form-control-custom" placeholder="পরিচিত ব্যক্তির নাম লিখুন" value="{{ old('person_name') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 person-fields-primary" style="display: none;">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">পরিচিত ব্যক্তির মোবাইল নম্বর</label>
                                                    <div class="input-group-custom">
                                                        <span class="input-icon"><i class="fas fa-phone-square-alt"></i></span>
                                                        <input type="text" name="person_phone" class="form-control-custom" placeholder="মোবাইল নম্বর লিখুন" value="{{ old('person_phone') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="col-12 text-end mt-5">
                                                <button type="submit" class="btn btn-brand-success text-white fw-bold px-4 py-2 w-100 transition" style="background-color: #006A4E; border: none; font-size: 0.95rem; border-radius: 8px !important; box-shadow: 0 3px 10px rgba(0, 106, 78, 0.1) !important;">
                                                    আবেদনপত্র জমা দিন <i class="fas fa-paper-plane ms-2 text-warning" style="font-size: 14px;"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: Five Point Pledge Document & Form -->
                        <div class="tab-pane fade animate-fade-in" id="pledge-content" role="tabpanel" aria-labelledby="pledge-tab">
                            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white border-light-grey mb-4" 
                                 style="border-top: 4px solid #F59E0B !important;">
                                <h3 class="fw-bold mb-3 text-center" style="color: #0f172a; font-size: 1.5rem;">পাঁচ দফা ভিত্তিক অঙ্গীকার পত্র</h3>
                                
                                <div class="p-4 rounded-4 mb-4 text-center border-light-grey" 
                                     style="background-color: rgba(0, 106, 78, 0.03); border-left: 5px solid #006A4E !important;">
                                    <p class="fw-bold mb-0 lh-lg text-dark-green" style="font-size: 0.95rem; color: #004D38 !important; text-align: justify;">
                                        <strong>অঙ্গীকার পত্র:</strong> আল্লাহর তওহীদ ভিত্তিক সত্যদীন পৃথিবীতে প্রতিষ্ঠার জন্য আল্লাহ তাঁর শেষ নবীকে যে পাঁচ দফার কর্মসূচি দান করেছিলেন এবং নবী তাঁর জীবনে যে কর্মসূচি মোতাবেক সংগ্রাম করে পৃথিবী থেকে চোলে যাবার সময় যে কর্মসূচি মোতাবেক সংগ্রাম চালিয়ে যাবার জন্য তাঁর উম্মাহর ওপর দায়িত্ব দিয়ে গেলেন, সেই কর্মসূচিভিত্তিক অঙ্গীকার পত্র।
                                    </p>
                                </div>

                                <div class="pledge-points-list d-flex flex-column gap-3.5 mb-5" style="font-size: 0.94rem;">
                                    <!-- Point 1 -->
                                    <div class="d-flex align-items-start p-4 rounded-4 border-light-grey pledge-point-card transition">
                                        <div class="point-number-badge me-3">১</div>
                                        <div class="text-secondary lh-lg" style="text-align: justify; color: #334155 !important;">
                                            আমি এ আন্দোলনের অন্যান্য সদস্য-সদস্যাদের সঙ্গে ইস্পাতের মতো ঐক্যবদ্ধ থাকবো। ঐক্য নষ্ট হয় এমন কোন কথা বোলব না বা কাজ করব না। কোন ব্যাপারে দ্বিমত পোষণ করব না। মতভেদের কোন কারণ উঠলেই চুপ হোয়ে যাব এবং ঐ ব্যাপারে কোন কথা না বোলে নেতার সিদ্ধান্তের ওপর ছেড়ে দেব এবং তারপর নেতার সিদ্ধান্ত স্থির হোয়ে গেলে জান-মাল করবানী করে হলেও সে সিদ্ধান্ত বাস্তবায়িত করব। আমি বিশ্বাস করব যে, এ আন্দোলনের অন্যান্য সদস্য-সদস্যারা আমার ভাই-বোন, তারা আমার রক্তের সম্পর্কের চেয়েও আপন।
                                        </div>
                                    </div>

                                    <!-- Point 2 -->
                                    <div class="d-flex align-items-start p-4 rounded-4 border-light-grey pledge-point-card transition">
                                        <div class="point-number-badge me-3">২</div>
                                        <div class="text-secondary lh-lg" style="text-align: justify; color: #334155 !important;">
                                            নেতা কখন কোন্ আদেশ নির্দেশ দেন সে জন্য আমি সর্বদা সজাগ ও সচেতন থাকবো।
                                        </div>
                                    </div>

                                    <!-- Point 3 -->
                                    <div class="d-flex align-items-start p-4 rounded-4 border-light-grey pledge-point-card transition">
                                        <div class="point-number-badge me-3">৩</div>
                                        <div class="text-secondary lh-lg" style="text-align: justify; color: #334155 !important;">
                                            নেতার আদেশ অক্ষরে অক্ষরে পালন করব। নেতা কোন আদেশ দিলে পছন্দ বা অপখন্দ হোক, সঠিক বা ভুল মনে হোক, বিন্দুমাত্র ইতস্ততঃ না করে সে আদেশ পালন করব। তাতে সম্পত্তির ক্ষতি হয় হোক, জান যায় যাক বা থাক, কিছুরই পরওয়া করব না। শুধুমাত্র ফরদে আইনের বিরুদ্ধে ছাড়া আর কোন আদেশ অমান্য করব না। নেতার আদেশ হলে সুন্নাহ-নফল এবাদত স্থগিত রাখবো। নেতা একবার নিয়োজিত হোয়ে গেলে তিনি যোগ্য কি অযোগ্য দেখব না; তার আদেশ হওয়ার সঙ্গে সঙ্গে তা পালন করব। বিপদ আপদের, ঝড়-বৃষ্টির কোন পরওয়া করব না।
                                        </div>
                                    </div>

                                    <!-- Point 4 -->
                                    <div class="d-flex align-items-start p-4 rounded-4 border-light-grey pledge-point-card transition">
                                        <div class="point-number-badge me-3">৪</div>
                                        <div class="text-secondary lh-lg" style="text-align: justify; color: #334155 !important;">
                                            আমি বিশ্বাস করব যে, আমি নিজে এবং এ আন্দোলনের অন্যান্য সদস্য-সদস্যাারা হেদায়াতপ্রাপ্ত অর্থাৎ আল্লাহর দেয়া সঠিক আকীদায়, সঠিক পথে, সঠিক দিক নির্দেশনায়, সেরাতুল মুস্তাকীমে প্রতিষ্ঠিত বা প্রতিষ্ঠিত হবার চেষ্টা করছি। এই হেদায়াতে যারা নেই, অর্থাৎ বাকী দুনিয়ার সমস্ত মানুষ থেকে আমি ভিন্ন ও বিচ্ছিন্ন। ওদের মধ্যে বাস করলেও আমি ওদের একজন নই। আকীদার বিকৃতি ও হেদায়াতের অভাবের কারণে ওরা আল্লাহ-রসুলকে বিশ্বাস করে সালাহ্ (নামাজ), সওম (রোযা), হজ্ব, যাকাত ও বহু নফল এবাদত করে গেলেও ওগুলো কোন কাজে আসবে না। আমি তাদের সাথে কোন এবাদত করব না। আমি এবাদত করব শুধু এই আন্দোলনের ভাই ও বোনদের সঙ্গে। আমি বর্তমানের বিকৃত এসলাম থেকে হেজরত করলাম। আমি কোন রাজনৈতিক কর্মকাণ্ডে অংশ নেব না।
                                        </div>
                                    </div>

                                    <!-- Point 5 -->
                                    <div class="d-flex align-items-start p-4 rounded-4 border-light-grey pledge-point-card transition">
                                        <div class="point-number-badge me-3">৫</div>
                                        <div class="text-secondary lh-lg" style="text-align: justify; color: #334155 !important;">
                                            আমি সর্বদা মনে রাখব যে উপরের ঐ ঐক্য, ঐ শৃংখলা, ঐ আনুগত্য, ঐ হেজরতের একমাত্র উদ্দেশ্য হোচ্ছে আল্লাহর তওহীদ, সেরাতুল মুস্তাকীম অর্থাৎ প্রকৃত দীনুল হক, দীনুল কাইয়্যেমাকে পৃথিবীতে প্রতিষ্ঠার সংগ্রাম, জেহাদ; এবং একথাও মনে রাখব যে ঐ ঐক্য, ঐ শৃংখলা, ঐ আনুগত্য, ঐ হেজরতের যে কোন একটি দুর্বল হয়ে গেলে জেহাদে আর বিজয় সম্ভব নয়। আরও মনে রাখবো, আল্লাহর রসুল বোলেছেন, যে বা যারা ঐ ঐক্য, ঐ শৃংখলা, ঐ আনুগত্য, ঐ হেজরত ও জেহাদের এই পাঁচ দফা কর্মসূচির ভ্রাতৃত্বের বন্ধনী থেকে এক বিঘত পরিমাণও বিচ্যুত হবে বা সোরে যাবে, তার বা তাদের গলা থেকে এসলামের বন্ধন খুলে যাবে, যদি না সে তওবা করে আবার এই কর্মসূচির বন্ধনে ফিরে আসে এবং যে বা যারা অন্য কোন কর্মসূচি গ্রহণের জন্য ডাক দেয়, সে বা তারা নিজেদের যত বড় মোসলেমই মনে করুক, যত সালাহ্ (নামায) পড়ুুক ও যত সওম (রোযা) রাখুক, যত এবাদতই করুক, তারা জাহান্নামের জ্বালানী পাথরে পরিণত হবে।
                                        </div>
                                    </div>
                                </div>

                                <!-- FORM 2: 5-Point Pledge Form -->
                                <div class="border-top pt-5">
                                    <div class="mb-4 text-center">
                                        <h4 class="fw-bold mb-1 text-dark" style="font-size: 1.35rem;">অঙ্গীকারনামা ফরম</h4>
                                        <p class="text-muted small">অনগ্রহ করে অঙ্গীকারপত্রটি মনোযোগ দিয়ে পড়ে নিচের ফরমটি সঠিক তথ্য দিয়ে পূরণ করুন। * চিহ্নিত ফিল্ডগুলো আবশ্যক।</p>
                                    </div>

                                    <form action="{{ route('join.submit') }}" method="POST" style="font-family: 'Baloo Da 2', sans-serif;">
                                        @csrf
                                        <input type="hidden" name="membership_type" value="pledge">

                                        <div class="row g-4">
                                            <!-- Name -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">নাম *</label>
                                                    <div class="input-group-custom">
                                                        <span class="input-icon"><i class="fas fa-user"></i></span>
                                                        <input type="text" name="name" class="form-control-custom" placeholder="আপনার পূর্ণ নাম লিখুন" value="{{ old('name') }}" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Date of Birth -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">জন্ম তারিখ</label>
                                                    <div class="input-group-custom">
                                                        <span class="input-icon"><i class="fas fa-calendar-alt"></i></span>
                                                        <input type="date" name="dob" class="form-control-custom" value="{{ old('dob') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Father/Husband -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">পিতা / স্বামীর নাম</label>
                                                    <div class="input-group-custom">
                                                        <span class="input-icon"><i class="fas fa-user-friends"></i></span>
                                                        <input type="text" name="father_husband" class="form-control-custom" placeholder="পিতা / স্বামীর নাম লিখুন" value="{{ old('father_husband') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Mobile No -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">মোবাইল নম্বর *</label>
                                                    <div class="input-group-custom">
                                                        <span class="input-icon"><i class="fas fa-phone-alt"></i></span>
                                                        <input type="text" name="phone" class="form-control-custom" placeholder="১১ ডিজিটের মোবাইল নম্বর লিখুন" value="{{ old('phone') }}" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Present Address -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">বর্তমান ঠিকানা *</label>
                                                    <div class="input-group-custom align-items-start">
                                                        <span class="input-icon pt-3"><i class="fas fa-map-marker-alt"></i></span>
                                                        <textarea name="present_address" rows="3" class="form-control-custom" placeholder="বর্তমান ঠিকানা বিস্তারিত লিখুন" required style="height: auto; padding-top: 12px;">{{ old('present_address') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Permanent Address -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">স্থায়ী ঠিকানা</label>
                                                    <div class="input-group-custom align-items-start">
                                                        <span class="input-icon pt-3"><i class="fas fa-map-marker-alt"></i></span>
                                                        <textarea name="permanent_address" rows="3" class="form-control-custom" placeholder="স্থায়ী ঠিকানা বিস্তারিত লিখুন" style="height: auto; padding-top: 12px;">{{ old('permanent_address') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Occupation -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">পেশা</label>
                                                    <div class="input-group-custom">
                                                        <span class="input-icon"><i class="fas fa-briefcase"></i></span>
                                                        <input type="text" name="occupation" class="form-control-custom" placeholder="আপনার পেশা লিখুন" value="{{ old('occupation') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Education -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">শিক্ষাগত যোগ্যতা</label>
                                                    <div class="input-group-custom">
                                                        <span class="input-icon"><i class="fas fa-graduation-cap"></i></span>
                                                        <input type="text" name="education" class="form-control-custom" placeholder="শিক্ষাগত যোগ্যতা লিখুন" value="{{ old('education') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Experience -->
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">কোন বিষয়ে পারদর্শী (একাধিক হলে কমা ব্যবহার করে লিখুন)</label>
                                                    <div class="input-group-custom">
                                                        <span class="input-icon"><i class="fas fa-star"></i></span>
                                                        <input type="text" name="experience" class="form-control-custom" placeholder="উদাঃ উপস্থাপনা, লেখালেখি, গ্রাফিক্স ডিজাইন..." value="{{ old('experience') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- How did you know about the movement -->
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small d-block">কীভাবে জেনেছেন (মাধ্যম)? *</label>
                                                    <div class="row g-2 mt-1">
                                                        @foreach($ways as $index => $way)
                                                            <div class="col-6 col-md-4">
                                                                <label class="way-select-card" for="wayPledge{{ $index }}">
                                                                    <input class="d-none check-way-pledge" type="radio" name="how_knew" value="{{ $way }}" id="wayPledge{{ $index }}" required {{ old('how_knew') == $way ? 'checked' : '' }}>
                                                                    <span class="way-card-content">
                                                                        <i class="far fa-circle status-dot"></i>
                                                                        <span class="way-name">{{ $way }}</span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Conditional Person Fields -->
                                            <div class="col-md-6 person-fields-pledge" style="display: none;">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">পরিচিত ব্যক্তির নাম</label>
                                                    <div class="input-group-custom">
                                                        <span class="input-icon"><i class="fas fa-user-check"></i></span>
                                                        <input type="text" name="person_name" class="form-control-custom" placeholder="পরিচিত ব্যক্তির নাম লিখুন" value="{{ old('person_name') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 person-fields-pledge" style="display: none;">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">পরিচিত ব্যক্তির মোবাইল নম্বর</label>
                                                    <div class="input-group-custom">
                                                        <span class="input-icon"><i class="fas fa-phone-square-alt"></i></span>
                                                        <input type="text" name="person_phone" class="form-control-custom" placeholder="মোবাইল নম্বর লিখুন" value="{{ old('person_phone') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="col-12 text-end mt-5">
                                                <button type="submit" class="btn btn-brand-success text-white fw-bold px-4 py-2 w-100 transition" style="background-color: #006A4E; border: none; font-size: 0.95rem; border-radius: 8px !important; box-shadow: 0 3px 10px rgba(0, 106, 78, 0.1) !important;">
                                                    আবেদনপত্র জমা দিন <i class="fas fa-paper-plane ms-2 text-warning" style="font-size: 14px;"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS Styles -->
    

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Tab Persistence via URL Hash and localStorage
        var hash = window.location.hash;
        var activeTab = localStorage.getItem('activeJoinTab');

        // Check if Laravel validation old input exists
        @if(old('membership_type') === 'pledge')
            activeTab = '#pledge-tab';
        @elseif(old('membership_type') === 'primary')
            activeTab = '#primary-tab';
        @endif

        if (hash) {
            var tabBtn = $('button[data-bs-target="' + hash + '"]');
            if (tabBtn.length) {
                var tab = new bootstrap.Tab(tabBtn[0]);
                tab.show();
            }
        } else if (activeTab) {
            var tabBtn = $(activeTab);
            if (tabBtn.length) {
                var tab = new bootstrap.Tab(tabBtn[0]);
                tab.show();
            }
        }

        // Save active tab on change
        $('.custom-tab-btn').on('shown.bs.tab', function (e) {
            var target = $(e.target).data('bs-target');
            var id = '#' + $(e.target).attr('id');
            
            // Update hash without scrolling page
            if (history.pushState) {
                history.pushState(null, null, target);
            } else {
                location.hash = target;
            }
            
            // Save to localStorage
            localStorage.setItem('activeJoinTab', id);
        });

        // Handle Conditional Fields display for 'Person' reference in Primary Form
        function checkPersonFieldsPrimary() {
            var selectedWay = $('input[name="how_knew"].check-way-primary:checked').val();
            if (selectedWay === 'ব্যক্তির মাধ্যমে') {
                $('.person-fields-primary').slideDown(250);
            } else {
                $('.person-fields-primary').slideUp(250);
                $('.person-fields-primary input').val('');
            }
        }

        $('input[name="how_knew"].check-way-primary').on('change', function() {
            checkPersonFieldsPrimary();
        });

        // Handle Conditional Fields display for 'Person' reference in Pledge Form
        function checkPersonFieldsPledge() {
            var selectedWay = $('input[name="how_knew"].check-way-pledge:checked').val();
            if (selectedWay === 'ব্যক্তির মাধ্যমে') {
                $('.person-fields-pledge').slideDown(250);
            } else {
                $('.person-fields-pledge').slideUp(250);
                $('.person-fields-pledge input').val('');
            }
        }

        $('input[name="how_knew"].check-way-pledge').on('change', function() {
            checkPersonFieldsPledge();
        });

        // Trigger on load
        checkPersonFieldsPrimary();
        checkPersonFieldsPledge();
    });
</script>
@endpush
