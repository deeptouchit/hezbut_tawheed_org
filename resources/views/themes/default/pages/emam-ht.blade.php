@extends('theme::layouts.app')

@section('title', 'বর্তমান এমাম হোসাইন মোহাম্মদ সেলিম - হেযবুত তওহীদ')

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'বর্তমান এমাম',
        'subtitle' => 'হোসাইন মোহাম্মদ সেলিম',
        'badge_text' => 'যামানার এমাম',
        'badge_icon' => 'fas fa-user-shield'
    ])

    <section class="py-5 bg-off-white page-body">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-4 text-center mb-4 mb-lg-0">
                    <div class="hover-zoom shadow rounded-4 overflow-hidden bg-white p-3 border">
                        <img src="{{ asset('/uploads/about/h-m-selim.jpg') }}" alt="হোসাইন মোহাম্মদ সেলিম" class="img-fluid rounded-3 w-100" style="max-height: 450px; object-fit: cover;" />
                        <div class="mt-3 fw-bold text-success fs-5">হোসাইন মোহাম্মদ সেলিম</div>
                        <div class="text-muted small">যামানার এমাম, হেযবুত তওহীদ</div>
                    </div>
                </div>
                <div class="col-lg-8 ps-lg-5">
                    <h2 class="fw-bold mb-4" style="color: #006A4E; font-family: 'Baloo Da 2', sans-serif;">বর্তমান এমাম হোসাইন মোহাম্মদ সেলিম</h2>
                    <p class="lead fw-bold text-success mb-3" style="font-size: 1.2rem; line-height: 1.7;">
                        ২০১২ সালে প্রতিষ্ঠাতা এমামুয্যামানের ইন্তেকালের পর আন্দোলনের সামগ্রিক দায়িত্বভার গ্রহণ করেন জনাব হোসাইন মোহাম্মদ সেলিম। তাঁর গতিশীল ও যুগোপযোগী নেতৃত্বে আন্দোলনটি দেশজুড়ে একটি শক্তিশালী শান্তি আন্দোলনের রূপ নিয়েছে।
                    </p>
                    <p class="text-secondary mb-4" style="line-height: 1.8;">
                        তিনি উগ্রপন্থা, সাম্প্রদায়িক সহিংসতা এবং অপরাজনীতির বিরুদ্ধে আপসহীন অবস্থান নিয়েছেন। দেশব্যাপী বিভিন্ন জাতীয় সেমিনার, সংবাদ সম্মেলন ও শান্তি সমাবেশে দেওয়া তাঁর যুক্তিনির্ভর বক্তব্য ও বলিষ্ঠ বক্তব্য সর্বমহলে প্রশংসিত হয়েছে। তিনি দেশের শান্তি ও স্থিতিশীলতা রক্ষায় এবং যুবসমাজকে মাদকমুক্ত, সুশৃঙ্খল নাগরিক হিসেবে গড়ে তুলতে অনন্য ভূমিকা রাখছেন।
                    </p>
                </div>
            </div>

            <!-- Focus and Achievements -->
            <div class="card p-4 p-md-5 border-0 shadow-sm bg-white rounded-4 mt-5">
                <h3 class="mb-4 text-center fw-bold" style="color: #006A4E; font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-bullseye me-2"></i> সংস্কারমূলক কার্যক্রম ও লক্ষ্য</h3>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="p-3 border-start border-success border-4 bg-light rounded-3 shadow-sm h-100">
                            <h5 class="fw-bold text-success">অসাম্প্রদায়িক সমাজ গঠন</h5>
                            <p class="text-secondary small mb-0" style="line-height: 1.6;">
                                এমাম হোসাইন মোহাম্মদ সেলিম সমাজকে ধর্মান্ধতার অন্ধকার থেকে মুক্ত করে আধুনিক বিজ্ঞানমনস্ক ও সম্প্রীতিময় সমাজ গঠনে গুরুত্ব দিচ্ছেন।
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border-start border-success border-4 bg-light rounded-3 shadow-sm h-100">
                            <h5 class="fw-bold text-success">যুব উন্নয়ন ও ক্রীড়া</h5>
                            <p class="text-secondary small mb-0" style="line-height: 1.6;">
                                যুবসমাজকে ইতিবাচক কাজে নিয়োজিত করতে তিনি নানা উদ্ভাবনী উদ্যোগ ও তরুণদের শরীরচর্চা এবং খেলাধুলায় উদ্বুদ্ধ করার কর্মসূচি গ্রহণ করেছেন।
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .hover-zoom {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-zoom:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
        }
    </style>
@endsection
