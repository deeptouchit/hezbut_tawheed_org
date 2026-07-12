@extends('theme::layouts.app')

@section('title', 'প্রতিষ্ঠাতা এমামুয্যামান মোহাম্মদ বায়াজীদ খান পন্নী - হেযবুত তওহীদ')

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'প্রতিষ্ঠাতা এমামুয্যামান',
        'subtitle' => 'মোহাম্মদ বায়াজীদ খান পন্নী (১৯২৫ - ২০১২)',
        'badge_text' => 'আন্দোলনের প্রতিষ্ঠাতা',
        'badge_icon' => 'fas fa-user-tie'
    ])

    <section class="py-5 bg-off-white page-body">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-4 text-center mb-4 mb-lg-0">
                    <div class="hover-zoom shadow rounded-4 overflow-hidden bg-white p-3 border">
                        <img src="{{ asset('/uploads/about/bayazid-khan-panni.jpg') }}" alt="মোহাম্মদ বায়াজীদ খান পন্নী" class="img-fluid rounded-3 w-100" style="max-height: 450px; object-fit: cover;" />
                        <div class="mt-3 fw-bold text-success fs-5">মোহাম্মদ বায়াজীদ খান পন্নী</div>
                        <div class="text-muted small">প্রতিষ্ঠাতা এমামুয্যামান, হেযবুত তওহীদ</div>
                    </div>
                </div>
                <div class="col-lg-8 ps-lg-5">
                    <h2 class="fw-bold mb-4" style="color: #006A4E; font-family: 'Baloo Da 2', sans-serif;">প্রতিষ্ঠাতা এমামুয্যামান মোহাম্মদ বায়াজীদ খান পন্নী</h2>
                    <p class="lead fw-bold text-success mb-3" style="font-size: 1.2rem; line-height: 1.7;">
                        টাঙ্গাইলের ঐতিহ্যবাহী জমিদার পন্নী পরিবারে জন্ম নেওয়া জনাব মোহাম্মদ বায়াজীদ খান পন্নী ছিলেন একাধারে সমাজ সংস্কারক, লেখক, চিন্তাবিদ ও সত্যের সন্ধানী এক মহান ব্যক্তিত্ব। ১৯৯৬ সালে তিনি হেযবুত তওহীদ আন্দোলনের সূচনা করেন।
                    </p>
                    <p class="text-secondary mb-4" style="line-height: 1.8;">
                        তিনি সমাজ থেকে ধর্মান্ধতা, অজ্ঞতা ও সংকীর্ণতা দূরীকরণে তাঁর সমগ্র জীবন উৎসর্গ করেছিলেন। তাঁর ক্ষুরধার লেখনী ও অসামান্য বক্তব্যের মাধ্যমে তিনি মানুষের কাছে ইসলামের প্রকৃত ও আদি রূপ তুলে ধরেছেন। তিনি প্রমাণ করেছেন যে ইসলাম কোনো স্থবির আনুষ্ঠানিকতার ধর্ম নয়, বরং এটি সমগ্র মানবজাতির মুক্তির জন্য একটি গতিশীল ও শান্তিময় জীবনব্যবস্থা।
                    </p>
                    <blockquote class="blockquote border-start border-5 border-success ps-4 py-2 bg-white rounded-3 shadow-sm mb-0">
                        <p class="mb-0 text-dark italic fw-semibold" style="font-size: 1.05rem; line-height: 1.7;">
                            "প্রকৃত ধর্ম মানুষকে সত্যের পথে ঐক্যবদ্ধ করে কল্যাণের শিক্ষা দেয়। ধর্মান্ধতা ও উগ্রবাদ মানুষের আত্মার ধ্বংস ডেকে আনে।"
                        </p>
                    </blockquote>
                </div>
            </div>

            <!-- Published Books Section -->
            <div class="card p-4 p-md-5 border-0 shadow-sm bg-white rounded-4 mt-5">
                <h3 class="mb-4 text-center fw-bold" style="color: #006A4E; font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-book me-2"></i> এমামুয্যামানের কালজয়ী প্রকাশনাসমূহ</h3>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100 border bg-light p-4 rounded-3 hover-lift" style="transition: all 0.3s ease;">
                            <div class="text-success mb-3"><i class="fas fa-book-open fa-2x"></i></div>
                            <h5 class="fw-bold mb-2">হেযবুত তওহীদ (আন্দোলনের রূপরেখা)</h5>
                            <p class="text-secondary mb-0 small" style="line-height: 1.6;">
                                আন্দোলনের আদর্শ, উদ্দেশ্য ও সামগ্রিক কার্যক্রমের তাত্ত্বিক ভিত্তি নিয়ে লেখা তাঁর যুগান্তকারী মূল গ্রন্থ।
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border bg-light p-4 rounded-3 hover-lift" style="transition: all 0.3s ease;">
                            <div class="text-success mb-3"><i class="fas fa-book-open fa-2x"></i></div>
                            <h5 class="fw-bold mb-2">ইসলামের প্রকৃত রূপরেখা</h5>
                            <p class="text-secondary mb-0 small" style="line-height: 1.6;">
                                গতানুগতিক ধর্মীয় সংকীর্ণতার ঊর্ধ্বে উঠে ইসলামের শাশ্বত শান্তির আদর্শ এবং তওহীদের সঠিক ব্যাখ্যা।
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border bg-light p-4 rounded-3 hover-lift" style="transition: all 0.3s ease;">
                            <div class="text-success mb-3"><i class="fas fa-book-open fa-2x"></i></div>
                            <h5 class="fw-bold mb-2">দাজ্জাল? ইহুদি-খ্রিষ্টান ‘সভ্যতা’!</h5>
                            <p class="text-secondary mb-0 small" style="line-height: 1.6;">
                                বর্তমান বস্তুবাদী যান্ত্রিক সভ্যতার অপূর্ণতা ও বিভ্রান্তি নিয়ে লেখা এক অনন্য তাত্ত্বিক বিশ্লেষণ।
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border bg-light p-4 rounded-3 hover-lift" style="transition: all 0.3s ease;">
                            <div class="text-success mb-3"><i class="fas fa-book-open fa-2x"></i></div>
                            <h5 class="fw-bold mb-2">জেহাদ, কেতাল ও সন্ত্রাস</h5>
                            <p class="text-secondary mb-0 small" style="line-height: 1.6;">
                                জেহাদ ও কেতালের ভুল ব্যাখ্যা দূর করে সন্ত্রাসবাদের বিরুদ্ধে ইসলামের অবস্থান ও অহিংস বার্তা।
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
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.1) !important;
        }
    </style>
@endsection
