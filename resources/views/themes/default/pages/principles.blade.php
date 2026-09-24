@extends('theme::layouts.app')

@section('title', 'আন্দোলনের মূলনীতি ও অঙ্গীকার - হেযবুত তওহীদ')

@push('styles')
<style>
    .principles-section {
        background: radial-gradient(circle at 50% 0%, rgba(16, 185, 129, 0.03) 0%, rgba(248, 250, 252, 1) 70%);
        position: relative;
        overflow: hidden;
    }
    .grid-pattern-mask {
        background-image: radial-gradient(rgba(0, 106, 78, 0.03) 1.5px, transparent 0);
        background-size: 24px 24px;
    }
    
    /* Timeline Container */
    .timeline-container {
        position: relative;
        max-width: 1000px;
        margin: 4rem auto;
        padding: 0 1rem;
    }
    
    /* Central Timeline Line */
    .timeline-line {
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(to bottom, #10B981 0%, #006A4E 50%, #059669 100%);
        transform: translateX(-50%);
        border-radius: 10px;
        z-index: 1;
    }
    
    /* Timeline Item */
    .timeline-item {
        position: relative;
        margin-bottom: 5rem;
        display: flex;
        align-items: center;
        width: 100%;
    }
    .timeline-item:last-child {
        margin-bottom: 2rem;
    }
    
    /* Left / Right alignment */
    .timeline-item-left {
        justify-content: flex-start;
    }
    .timeline-item-right {
        justify-content: flex-end;
    }
    
    /* Timeline Badge (Center Nodes) */
    .timeline-badge {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: #ffffff;
        color: #006A4E;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Baloo Da 2', sans-serif;
        font-size: 1.4rem;
        font-weight: 800;
        border: 4px solid #006A4E;
        box-shadow: 0 0 15px rgba(0, 106, 78, 0.15);
        z-index: 2;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .timeline-item:hover .timeline-badge {
        background: linear-gradient(135deg, #006A4E 0%, #10B981 100%);
        color: #ffffff;
        border-color: #ffffff;
        transform: translate(-50%, -50%) scale(1.15);
        box-shadow: 0 0 25px rgba(16, 185, 129, 0.4);
    }
    
    /* Timeline Content (Cards) */
    .timeline-content {
        width: 44%;
        padding: 2.25rem;
        background: #ffffff;
        border: 1px solid rgba(0, 106, 78, 0.1);
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
    }
    .timeline-content:hover {
        transform: translateY(-6px);
        border-color: rgba(16, 185, 129, 0.3);
        box-shadow: 0 20px 40px rgba(0, 106, 78, 0.06);
    }
    
    /* Icon Box */
    .icon-box {
        min-width: 60px;
        height: 60px;
        border-radius: 16px;
        background: rgba(0, 106, 78, 0.05);
        color: #006A4E;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin-right: 1.5rem;
        transition: all 0.4s ease;
    }
    .timeline-content:hover .icon-box {
        background: linear-gradient(135deg, #006A4E 0%, #10B981 100%);
        color: #ffffff;
        transform: scale(1.05);
    }
    
    /* Text inside Card */
    .principle-text {
        color: #0f172a !important;
        font-weight: 700;
        line-height: 1.8;
        font-size: 1.15rem;
        font-family: 'Baloo Da 2', sans-serif;
        margin-bottom: 0;
    }
    
    /* Hide mobile capsule badge on desktop */
    @media (min-width: 576px) {
        .mobile-capsule {
            display: none !important;
        }
    }
    
    /* Responsive Styling (Tablets) */
    @media (max-width: 991.98px) {
        .timeline-line {
            left: 30px;
            transform: none;
        }
        .timeline-item {
            justify-content: flex-start;
            margin-bottom: 3.5rem;
        }
        .timeline-badge {
            left: 30px;
            transform: translate(-50%, -50%);
        }
        .timeline-item:hover .timeline-badge {
            transform: translate(-50%, -50%) scale(1.15);
        }
        .timeline-content {
            width: calc(100% - 80px);
            margin-left: 80px !important;
            padding: 1.75rem;
        }
    }
    
    /* Responsive Styling (Mobile Devices) - High-end Modern Row Layout */
    @media (max-width: 575.98px) {
        .timeline-line {
            display: none;
        }
        .timeline-badge {
            display: none;
        }
        .timeline-container {
            margin: 2rem 0;
            padding: 0;
        }
        .timeline-item {
            margin-bottom: 1.25rem;
            display: block;
        }
        .timeline-content {
            width: 100%;
            margin-left: 0 !important;
            padding: 1.25rem;
            position: relative;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.03);
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid rgba(0, 106, 78, 0.06);
            border-left: 5px solid #10B981 !important; /* Premium Green Left Accent Border */
            display: flex;
            align-items: center;
        }
        
        /* Mobile left capsule badge containing number and small icon */
        .mobile-capsule {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 48px;
            width: 48px;
            height: 72px;
            background: linear-gradient(135deg, rgba(0, 106, 78, 0.05) 0%, rgba(16, 185, 129, 0.12) 100%);
            border-radius: 16px;
            margin-right: 1.25rem;
            border: 1px solid rgba(16, 185, 129, 0.15);
        }
        
        .mobile-capsule-num {
            font-family: 'Baloo Da 2', sans-serif;
            font-size: 1.2rem;
            font-weight: 800;
            color: #006A4E;
            line-height: 1;
            margin-bottom: 6px;
        }
        
        .mobile-capsule-icon {
            font-size: 0.85rem;
            color: #10B981;
        }
        
        .icon-box {
            display: none !important;
        }
        
        .principle-text {
            font-size: 1.05rem;
            line-height: 1.65;
            color: #0f172a !important;
            font-weight: 700;
        }
    }
    
    .principles-title {
        font-family: 'Baloo Da 2', sans-serif;
        font-size: 2.5rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.5px;
    }
    .principles-subtitle {
        color: #1e293b !important;
        font-weight: 600;
        font-family: 'Baloo Da 2', sans-serif;
        font-size: 1.25rem;
        line-height: 1.7;
    }
</style>
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
                    <div class="timeline-content" data-number="১">
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
                    <div class="timeline-content" data-number="২">
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
                    <div class="timeline-content" data-number="৩">
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
                    <div class="timeline-content" data-number="৪">
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
                    <div class="timeline-content" data-number="৫">
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
                    <div class="timeline-content" data-number="৬">
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
                    <div class="timeline-content" data-number="৭">
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
