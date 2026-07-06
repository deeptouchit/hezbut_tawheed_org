@extends('theme::layouts.app')

@section('title', 'যোগদান করুন - হেজবুত তওহীদ')

@section('content')

    <!-- Premium Inner Page Header Banner -->
    <div class="py-5 text-white position-relative" 
         style="background: linear-gradient(135deg, #006A4E 0%, #004D38 100%); border-bottom: 4px solid #F59E0B;">
         <div class="container text-center py-2" style="font-family: 'Baloo Da 2', sans-serif;">
            <h1 class="fw-bold mb-2 text-white animate-fade-in" style="font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0,0,0,0.15);">
                <i class="fas fa-user-plus me-2 text-warning animate-bounce-slow" style="color: #F59E0B !important;"></i> আন্দোলনে যোগদান করুন
            </h1>
            <p class="lead small mb-0 opacity-90 text-white" style="font-size: 1.05rem; letter-spacing: 0.5px;">
                সত্যের আলো ছড়াতে এবং মানবকল্যাণে হেজবুত তওহীদের সাথে যুক্ত হোন
            </p>
         </div>
    </div>

    <!-- Join main container -->
    <div class="py-5" style="background-color: #f8fafc; font-family: 'Baloo Da 2', sans-serif;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    
                    <!-- Tabs Navigation Buttons -->
                    <ul class="nav nav-pills justify-content-center gap-3 mb-5" id="joinTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active py-3 px-4 rounded-pill fw-bold border border-light-grey text-dark transition custom-tab-btn" 
                                    id="primary-tab" data-bs-toggle="tab" data-bs-target="#primary-content" type="button" role="tab" 
                                    aria-controls="primary-content" aria-selected="true" style="font-size: 0.95rem;">
                                <i class="fas fa-id-card me-2 tab-icon"></i> প্রাথমিক সদস্য পদ
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 px-4 rounded-pill fw-bold border border-light-grey text-dark transition custom-tab-btn" 
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
                                <h3 class="fw-bold mb-3 text-success-brand text-center" style="font-size: 1.5rem;">প্রাথমিক সদস্য পদ লাভের অঙ্গীকার</h3>
                                <p class="text-secondary lh-lg mb-4 text-center" style="font-size: 0.96rem; max-width: 800px; margin: 0 auto;">
                                    আল্লাহর তওহীদ ভিত্তিক সত্যদীন ও শান্তির বাণী সাধারণ মানুষের মাঝে ছড়িয়ে দিতে এবং হেযবুত তওহীদের সমাজ সংস্কারমূলক মানবিক উদ্যোগে অংশ নিতে আপনি প্রাথমিক সদস্য পদের আবেদন করতে পারেন।
                                </p>
                                
                                <!-- FORM 1: Primary Membership Form -->
                                <div class="border-top pt-5 mt-4">
                                    <div class="mb-4 text-center">
                                        <span class="badge badge-gold px-3 py-2 rounded fw-bold text-uppercase tracking-wider" style="font-size: 0.78rem;">আবেদন পত্র</span>
                                        <h4 class="fw-bold mb-1 text-dark mt-2" style="font-size: 1.35rem;">ফরম পূরণ করুন (প্রাথমিক সদস্য পদ)</h4>
                                        <p class="text-muted small">অনগ্রহ করে নিচের ফরমটি সঠিক তথ্য দিয়ে পূরণ করুন। তারকা (*) চিহ্নিত ফিল্ডগুলো আবশ্যক।</p>
                                    </div>

                                    <form action="{{ route('join.submit') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="membership_type" value="primary">

                                        <div class="row g-3">
                                            <!-- Name -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">নাম *</label>
                                                    <input type="text" name="name" class="form-control py-3 rounded-3" placeholder="নাম..." value="{{ old('name') }}" required style="font-size: 0.9rem; box-shadow: none;">
                                                </div>
                                            </div>

                                            <!-- Mobile No -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">মোবাইল নম্বর *</label>
                                                    <input type="text" name="phone" class="form-control py-3 rounded-3" placeholder="মোবাইল নম্বর..." value="{{ old('phone') }}" required style="font-size: 0.9rem; box-shadow: none;">
                                                </div>
                                            </div>

                                            <!-- Present Address -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">বর্তমান ঠিকানা *</label>
                                                    <input type="text" name="present_address" class="form-control py-3 rounded-3" placeholder="বর্তমান ঠিকানা..." value="{{ old('present_address') }}" required style="font-size: 0.9rem; box-shadow: none;">
                                                </div>
                                            </div>

                                            <!-- Occupation -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">পেশা</label>
                                                    <input type="text" name="occupation" class="form-control py-3 rounded-3" placeholder="পেশা..." value="{{ old('occupation') }}" style="font-size: 0.9rem; box-shadow: none;">
                                                </div>
                                            </div>

                                            <!-- How did you know about the movement -->
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small d-block">কিভাবে আন্দোলন সম্পর্কে জেনেছেন? *</label>
                                                    <div class="d-flex flex-wrap gap-3 mt-1">
                                                        @foreach($ways as $index => $way)
                                                            <div class="form-check">
                                                                <input class="form-check-input check-way-primary" type="radio" name="how_knew" value="{{ $way }}" id="wayPrimary{{ $index }}" required
                                                                       {{ old('how_knew') == $way ? 'checked' : '' }}>
                                                                <label class="form-check-label text-secondary" for="wayPrimary{{ $index }}" style="font-size: 0.88rem; cursor: pointer;">
                                                                    {{ $way }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Conditional Fields for Primary -->
                                            <div class="col-md-6 person-fields-primary" style="display: none;">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">ব্যক্তির নাম (ব্যক্তির মাধ্যমে হয়ে থাকলে)</label>
                                                    <input type="text" name="person_name" class="form-control py-3 rounded-3" placeholder="পরিচিত ব্যক্তির নাম..." value="{{ old('person_name') }}" style="font-size: 0.9rem; box-shadow: none;">
                                                </div>
                                            </div>

                                            <div class="col-md-6 person-fields-primary" style="display: none;">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">পরিচিত ব্যক্তির মোবাইল নম্বর</label>
                                                    <input type="text" name="person_phone" class="form-control py-3 rounded-3" placeholder="মোবাইল নম্বর..." value="{{ old('person_phone') }}" style="font-size: 0.9rem; box-shadow: none;">
                                                </div>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="col-12 text-end mt-4">
                                                <button type="submit" class="btn btn-brand-success text-white fw-bold px-5 py-3 rounded shadow-sm w-100 transition">
                                                    আবেদনপত্র জমা দিন <i class="fas fa-paper-plane ms-2 text-warning" style="font-size: 13px;"></i>
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
                                        <span class="badge badge-gold px-3 py-2 rounded fw-bold text-uppercase tracking-wider" style="font-size: 0.78rem;">অঙ্গীকার পত্র ফর্ম</span>
                                        <h4 class="fw-bold mb-1 text-dark mt-2" style="font-size: 1.35rem;">ফরম পূরণ করুন (পাঁচ দফা ভিত্তিক অঙ্গীকার)</h4>
                                        <p class="text-muted small">অনগ্রহ করে অঙ্গীকারপত্রটি মনোযোগ দিয়ে পড়ে নিচের ফরমটি সঠিক তথ্য দিয়ে পূরণ করুন।</p>
                                    </div>

                                    <form action="{{ route('join.submit') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="membership_type" value="pledge">

                                        <div class="row g-3">
                                            <!-- Name -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">নাম *</label>
                                                    <input type="text" name="name" class="form-control py-3 rounded-3" placeholder="নাম..." value="{{ old('name') }}" required style="font-size: 0.9rem; box-shadow: none;">
                                                </div>
                                            </div>

                                            <!-- Date of Birth -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">জন্ম তারিখ</label>
                                                    <input type="date" name="dob" class="form-control py-3 rounded-3" value="{{ old('dob') }}" style="font-size: 0.9rem; box-shadow: none;">
                                                </div>
                                            </div>

                                            <!-- Father/Husband -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">পিতা / স্বামীর নাম</label>
                                                    <input type="text" name="father_husband" class="form-control py-3 rounded-3" placeholder="পিতা / স্বামীর নাম..." value="{{ old('father_husband') }}" style="font-size: 0.9rem; box-shadow: none;">
                                                </div>
                                            </div>

                                            <!-- Mobile No -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">মোবাইল নম্বর *</label>
                                                    <input type="text" name="phone" class="form-control py-3 rounded-3" placeholder="মোবাইল নম্বর..." value="{{ old('phone') }}" required style="font-size: 0.9rem; box-shadow: none;">
                                                </div>
                                            </div>

                                            <!-- Present Address -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">বর্তমান ঠিকানা</label>
                                                    <textarea name="present_address" rows="3" class="form-control rounded-3" placeholder="বর্তমান ঠিকানা..." style="font-size: 0.9rem; box-shadow: none;">{{ old('present_address') }}</textarea>
                                                </div>
                                            </div>

                                            <!-- Permanent Address -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">স্থায়ী ঠিকানা</label>
                                                    <textarea name="permanent_address" rows="3" class="form-control rounded-3" placeholder="স্থায়ী ঠিকানা..." style="font-size: 0.9rem; box-shadow: none;">{{ old('permanent_address') }}</textarea>
                                                </div>
                                            </div>

                                            <!-- Occupation -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">পেশা</label>
                                                    <input type="text" name="occupation" class="form-control py-3 rounded-3" placeholder="পেশা..." value="{{ old('occupation') }}" style="font-size: 0.9rem; box-shadow: none;">
                                                </div>
                                            </div>

                                            <!-- Education -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">শিক্ষাগত যোগ্যতা</label>
                                                    <input type="text" name="education" class="form-control py-3 rounded-3" placeholder="শিক্ষাগত যোগ্যতা..." value="{{ old('education') }}" style="font-size: 0.9rem; box-shadow: none;">
                                                </div>
                                            </div>

                                            <!-- Experience -->
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">কোন বিষয়ে পারদর্শী (একাধিক হলে কমা ব্যবহার করে লিখুন)</label>
                                                    <input type="text" name="experience" class="form-control py-3 rounded-3" placeholder="উদাঃ উপস্থাপনা, লেখালেখি, গ্রাফিক্স ডিজাইন..." value="{{ old('experience') }}" style="font-size: 0.9rem; box-shadow: none;">
                                                </div>
                                            </div>

                                            <!-- How did you know about the movement -->
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small d-block">কিভাবে আন্দোলন সম্পর্কে জেনেছেন? *</label>
                                                    <div class="d-flex flex-wrap gap-3 mt-1">
                                                        @foreach($ways as $index => $way)
                                                            <div class="form-check">
                                                                <input class="form-check-input check-way-pledge" type="radio" name="how_knew" value="{{ $way }}" id="wayPledge{{ $index }}" required
                                                                       {{ old('how_knew') == $way ? 'checked' : '' }}>
                                                                <label class="form-check-label text-secondary" for="wayPledge{{ $index }}" style="font-size: 0.88rem; cursor: pointer;">
                                                                    {{ $way }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Person Details (Conditional) -->
                                            <div class="col-md-6 person-fields-pledge" style="display: none;">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">ব্যक्तियों নাম (ব্যক্তির মাধ্যমে হয়ে থাকলে)</label>
                                                    <input type="text" name="person_name" class="form-control py-3 rounded-3" placeholder="পরিচিত ব্যক্তির নাম..." value="{{ old('person_name') }}" style="font-size: 0.9rem; box-shadow: none;">
                                                </div>
                                            </div>

                                            <div class="col-md-6 person-fields-pledge" style="display: none;">
                                                <div class="form-group">
                                                    <label class="form-label text-dark fw-semibold small">পরিচিত ব্যক্তির মোবাইল নম্বর</label>
                                                    <input type="text" name="person_phone" class="form-control py-3 rounded-3" placeholder="মোবাইল নম্বর..." value="{{ old('person_phone') }}" style="font-size: 0.9rem; box-shadow: none;">
                                                </div>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="col-12 text-end mt-4">
                                                <button type="submit" class="btn btn-brand-success text-white fw-bold px-5 py-3 rounded shadow-sm w-100 transition">
                                                    আবেদনপত্র জমা দিন <i class="fas fa-paper-plane ms-2 text-warning" style="font-size: 13px;"></i>
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
    <style>
        .border-light-grey {
            border: 1px solid #e2e8f0 !important;
        }
        .text-success-brand {
            color: #006A4E !important;
        }
        
        .badge-gold {
            background-color: rgba(245, 158, 11, 0.1) !important;
            color: #D97706 !important;
            border: 1px solid rgba(245, 158, 11, 0.2) !important;
        }

        .btn-brand-success {
            background: linear-gradient(135deg, #006A4E 0%, #00563F 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-brand-success:hover {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3) !important;
        }
        .form-control:focus {
            border-color: rgba(0, 106, 78, 0.5) !important;
            box-shadow: 0 0 0 0.25rem rgba(0, 106, 78, 0.15) !important;
        }
        
        /* Pills Tab Navigation Premium Design */
        .custom-tab-btn {
            background-color: #ffffff !important;
            color: #475569 !important;
            border: 1px solid #cbd5e1 !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .custom-tab-btn:hover {
            background-color: rgba(0, 106, 78, 0.04) !important;
            border-color: #006A4E !important;
            color: #006A4E !important;
        }
        .custom-tab-btn.active {
            background: linear-gradient(135deg, #006A4E 0%, #004D38 100%) !important;
            color: #ffffff !important;
            border-color: transparent !important;
            box-shadow: 0 8px 20px rgba(0, 106, 78, 0.15) !important;
        }
        .custom-tab-btn .tab-icon {
            transition: transform 0.25s;
        }
        .custom-tab-btn.active .tab-icon {
            transform: scale(1.15);
            color: #F59E0B !important;
        }
        
        /* Premium Pledge point cards and badges */
        .pledge-point-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .pledge-point-card:hover {
            border-color: rgba(0, 106, 78, 0.2) !important;
            box-shadow: 0 10px 25px rgba(0, 106, 78, 0.04) !important;
            transform: translateY(-2px);
        }
        .point-number-badge {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            color: #ffffff;
            font-weight: bold;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.9rem;
            box-shadow: 0 4px 10px rgba(245, 158, 11, 0.25);
        }

        /* Animations */
        .animate-bounce-slow {
            animation: bounceSlow 3s infinite;
        }
        @keyframes bounceSlow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

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
