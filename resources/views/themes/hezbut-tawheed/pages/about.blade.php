@extends('theme::layouts.app')

@section('title', 'আমাদের সম্পর্কে - হেজবুত তওহীদ')

@section('content')

    <!-- Inner Page Header Banner -->
    <div class="inner-page-header py-5 bg-dark-green text-white position-relative">
        <div class="container py-3">
            <h1 class="fw-bold mb-2">আমাদের সম্পর্কে</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-gold text-decoration-none">মূল পাতা</a></li>
                    <li class="breadcrumb-item active text-white opacity-75" aria-current="page">আমাদের সম্পর্কে</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- About Section Content -->
    <section class="about-intro-section py-6 bg-off-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1541872703-74c5e44368f9?q=80&w=800" alt="Hezbut Tawheed Intro" class="img-fluid rounded-4 shadow-sm">
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <span class="text-gold fw-bold text-uppercase tracking-wider">পরিচিতি</span>
                    <h2 class="section-title text-dark-green mb-4">মানবতার কল্যাণে এক বজ্রকণ্ঠ</h2>
                    <p class="text-dark fs-5 lh-lg mb-4">
                        হেজবুত তওহীদ ১৯৯৫ সালের ১২ই জুলাই টাঙ্গাইলের করটিয়ায় জনাব মোহাম্মদ বায়োজেদ খান পন্নী কর্তৃক প্রতিষ্ঠিত একটি অরাজনৈতিক আন্দোলন। এর একমাত্র লক্ষ্য ও উদ্দেশ্য হচ্ছে ধর্মীয় গোঁড়ামি, কুসংস্কার, জঙ্গিবাদ ও উগ্রবাদের বিরুদ্ধে মানুষকে সচেতন করা এবং প্রকৃত সত্য ও মানবতার বাণী তুলে ধরা।
                    </p>
                    <p class="text-muted lh-lg">
                        আমাদের এই আন্দোলন কোনো রাজনৈতিক উদ্দেশ্যে পরিচালিত হয় না। আমরা বিশ্বাস করি সমাজে শান্তি ও শৃঙ্খলা প্রতিষ্ঠার জন্য প্রতিটি মানুষের মনের ইতিবাচক পরিবর্তন জরুরি। আমরা মানবতার জয়গান গাই এবং সব ধরণের বৈষম্য ও নাশকতার বিরুদ্ধে বুদ্ধিবৃত্তিক লড়াই চালাই।
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="mission-vision-section py-6">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 border-0 p-4 p-md-5 shadow-sm rounded-4 text-center bg-white hover-grow transition">
                        <div class="card-icon bg-light-green rounded-circle d-inline-flex justify-content-center align-items-center mb-4 text-dark-green mx-auto" style="width: 80px; height: 80px;">
                            <i class="fas fa-bullseye fa-2x"></i>
                        </div>
                        <h3 class="fw-bold text-dark-green mb-3">আমাদের লক্ষ্য (Mission)</h3>
                        <p class="text-muted lh-lg">
                            ধর্মের নামে ব্যবসা, হানাহানি ও কুসংস্কার দূর করে মানুষের কল্যাণ সাধন করা। অহিংসা, ভ্রাতৃত্ববোধ ও পরমতসহিষ্ণুতার বাণী সমাজের সর্বস্তরে পৌঁছে দেওয়া এবং একটি সমৃদ্ধ ও প্রগতিশীল বাংলাদেশ বিনির্মাণে ভূমিকা রাখা।
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 border-0 p-4 p-md-5 shadow-sm rounded-4 text-center bg-white hover-grow transition">
                        <div class="card-icon bg-light-green rounded-circle d-inline-flex justify-content-center align-items-center mb-4 text-dark-green mx-auto" style="width: 80px; height: 80px;">
                            <i class="fas fa-eye fa-2x"></i>
                        </div>
                        <h3 class="fw-bold text-dark-green mb-3">আমাদের উদ্দেশ্য (Vision)</h3>
                        <p class="text-muted lh-lg">
                            এমন একটি মানবিক ও বৈষম্যহীন সমাজ গড়ে তোলা যেখানে সব ধর্মের ও বর্ণের মানুষ পরম শান্তিতে সহাবস্থান করতে পারবে এবং উগ্রবাদের কালো ছায়া সমাজ থেকে চিরতরে মুছে যাবে।
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Executive Committee (Team Members) -->
    <section class="committee-section py-6 bg-off-white">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-gold fw-bold text-uppercase tracking-wider">আমাদের অভিভাবকবৃন্দ</span>
                <h2 class="section-title text-dark-green">কেন্দ্রীয় কার্যনির্বাহী কমিটি</h2>
                <div class="title-underline bg-gold mx-auto mt-3" style="width: 80px; height: 3px;"></div>
            </div>

            <div class="row g-4 justify-content-center">
                @forelse($teamMembers as $member)
                    <div class="col-lg-3 col-md-6 col-sm-10">
                        <div class="card team-card border-0 text-center shadow-sm rounded-4 overflow-hidden hover-grow transition h-100 bg-white">
                            <div class="team-img-wrapper">
                                <img src="{{ asset($member->image_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400') }}" alt="{{ $member->name }}" class="img-fluid team-img">
                            </div>
                            <div class="card-body p-3">
                                <h5 class="fw-bold text-dark mb-1">{{ $member->name }}</h5>
                                <p class="text-muted small mb-2">{{ $member->designation }}</p>
                                <div class="team-social text-gold small">
                                    <span class="mx-1"><i class="fab fa-facebook-f hover-gold pointer"></i></span>
                                    <span class="mx-1"><i class="fab fa-twitter hover-gold pointer"></i></span>
                                    <span class="mx-1"><i class="fab fa-linkedin-in hover-gold pointer"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <p class="text-muted">কোনো কমিটি সদস্যের বিবরণ পাওয়া যায়নি।</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

@endsection
