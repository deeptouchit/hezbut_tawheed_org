@extends('theme::layouts.app')

@section('title', 'আন্দোলনের মূলনীতি ও অঙ্গীকার - হেযবুত তওহীদ')

@push('styles')

@endpush

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'আন্দোলনের মূলনীতি',
        'subtitle' => 'হেযবুত তওহীদ সুনির্দিষ্ট কিছু স্তম্ভ এবং মূলনীতির ওপর ভিত্তি করে কাজ করে।',
        'badge_text' => 'আদর্শ',
        'badge_icon' => 'fas fa-shield-alt'
    ])

    <!-- Main Principles Section -->
    <section class="py-6 principles-section grid-pattern-mask">
        <div class="container">
            <div class="text-center mb-5 mt-2">
                <h2 class="principles-title mb-3">হেযবুত তওহীদ আন্দোলনের মূলনীতি</h2>
                <p class="principles-subtitle mx-auto" style="max-width: 750px;">
                    আমাদের প্রতিটি কর্মী ও সদস্য সুনির্দিষ্ট এই মূলনীতিগুলো অবশ্যই অনুশীলনের মাধ্যমে সত্যের প্রচার ও সমাজ সংস্কারের কাজে প্রতিজ্ঞাবদ্ধ।
                </p>
            </div>

            <!-- Premium Timeline Roadmap -->
            <div class="timeline-container">
                <div class="timeline-line"></div>

                <!-- Principle 1 -->
                <div class="timeline-item timeline-item-left">
                    <div class="timeline-badge">১</div>
                    <div class="timeline-content">
                        <!-- Desktop Icon -->
                        <div class="icon-box">
                            <i class="fas fa-route"></i>
                        </div>
                        <!-- Mobile Capsule Badge -->
                        <div class="mobile-capsule">
                            <span class="mobile-capsule-num">১</span>
                            <i class="fas fa-route mobile-capsule-icon"></i>
                        </div>
                        <div class="text-content">
                            <p class="principle-text">
                                হেযবুত তওহীদ চেষ্টা করবে আল্লাহর রসুলের প্রতিটি পদক্ষেপকে অনুসরণ করতে।
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Principle 2 -->
                <div class="timeline-item timeline-item-right">
                    <div class="timeline-badge">২</div>
                    <div class="timeline-content">
                        <!-- Desktop Icon -->
                        <div class="icon-box">
                            <i class="fas fa-sun"></i>
                        </div>
                        <!-- Mobile Capsule Badge -->
                        <div class="mobile-capsule">
                            <span class="mobile-capsule-num">২</span>
                            <i class="fas fa-sun mobile-capsule-icon"></i>
                        </div>
                        <div class="text-content">
                            <p class="principle-text">
                                হেযবুত তওহীদের কোন গোপন কার্যক্রম থাকবে না, সবকিছু হবে প্রকাশ্য এবং দিনের আলোর মত পরিষ্কার।
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Principle 3 -->
                <div class="timeline-item timeline-item-left">
                    <div class="timeline-badge">৩</div>
                    <div class="timeline-content">
                        <!-- Desktop Icon -->
                        <div class="icon-box">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <!-- Mobile Capsule Badge -->
                        <div class="mobile-capsule">
                            <span class="mobile-capsule-num">৩</span>
                            <i class="fas fa-balance-scale mobile-capsule-icon"></i>
                        </div>
                        <div class="text-content">
                            <p class="principle-text">
                                হেযবুত তওহীদের কেউ কোন আইনভঙ্গ করবে না, অবৈধ অস্ত্রের সংস্পর্শে যাবে না, গেলে তাকে এমাম নিজেই আইন প্রয়োগকারী সংস্থার হাতে তুলে দেবেন।
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Principle 4 -->
                <div class="timeline-item timeline-item-right">
                    <div class="timeline-badge">৪</div>
                    <div class="timeline-content">
                        <!-- Desktop Icon -->
                        <div class="icon-box">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <!-- Mobile Capsule Badge -->
                        <div class="mobile-capsule">
                            <span class="mobile-capsule-num">৪</span>
                            <i class="fas fa-hand-holding-heart mobile-capsule-icon"></i>
                        </div>
                        <div class="text-content">
                            <p class="principle-text">
                                হেযবুত তওহীদের সদস্যরা দীনের কাজ করে কোনো পার্থিব বিনিময় নেবে না। বিনিময় আল্লাহর নিকট রয়েছে।
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Principle 5 -->
                <div class="timeline-item timeline-item-left">
                    <div class="timeline-badge">৫</div>
                    <div class="timeline-content">
                        <!-- Desktop Icon -->
                        <div class="icon-box">
                            <i class="fas fa-users"></i>
                        </div>
                        <!-- Mobile Capsule Badge -->
                        <div class="mobile-capsule">
                            <span class="mobile-capsule-num">৫</span>
                            <i class="fas fa-users mobile-capsule-icon"></i>
                        </div>
                        <div class="text-content">
                            <p class="principle-text">
                                যথা সম্ভব সমাজের সর্বস্তরের মানুষকে সঙ্গে নিয়ে কাজ করবে।
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Principle 6 -->
                <div class="timeline-item timeline-item-right">
                    <div class="timeline-badge">৬</div>
                    <div class="timeline-content">
                        <!-- Desktop Icon -->
                        <div class="icon-box">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <!-- Mobile Capsule Badge -->
                        <div class="mobile-capsule">
                            <span class="mobile-capsule-num">৬</span>
                            <i class="fas fa-shield-alt mobile-capsule-icon"></i>
                        </div>
                        <div class="text-content">
                            <p class="principle-text">
                                যারা হেযবুত তওহীদের সদস্য নয়, তাদের থেকে কোনরূপ অর্থ গ্রহণ করা হবে না।
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Principle 7 -->
                <div class="timeline-item timeline-item-left">
                    <div class="timeline-badge">৭</div>
                    <div class="timeline-content">
                        <!-- Desktop Icon -->
                        <div class="icon-box">
                            <i class="fas fa-landmark"></i>
                        </div>
                        <!-- Mobile Capsule Badge -->
                        <div class="mobile-capsule">
                            <span class="mobile-capsule-num">৭</span>
                            <i class="fas fa-landmark mobile-capsule-icon"></i>
                        </div>
                        <div class="text-content">
                            <p class="principle-text">
                                হেযবুত তওহীদের কোন সদস্য কোন প্রচলিত রাজনৈতিক কর্মকান্ডে সম্পৃক্ত হতে পারবে না।
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
