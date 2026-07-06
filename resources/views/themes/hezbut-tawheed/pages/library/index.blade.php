@extends('theme::layouts.app')

@section('title', 'ডিজিটাল লাইব্রেরী - হেযবুত তওহীদ')
@section('meta_description', 'হেযবুত তওহীদ আন্দোলনের প্রতিষ্ঠাতা এমামুযযামান জনাব মোহাম্মদ বায়াজীদ খান পন্নী এবং হোসাইন মোহাম্মদ সেলিম রচিত সকল বই ও প্রকাশনাসমূহ অনলাইনে পড়ার ডিজিটাল লাইব্রেরী')

@section('content')

    <!-- Banner Header -->
    <div class="py-5 text-white position-relative" style="background: linear-gradient(rgba(0,106,78,0.88), rgba(0,106,78,0.88)), url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=1200') no-repeat center center; background-size: cover; border-bottom: 4px solid #10B981;">
        <div class="container py-4 text-center">
            <h1 class="display-4 fw-bold mb-0 text-shadow text-white"><i class="fas fa-university me-2 text-gold"></i> ডিজিটাল লাইব্রেরী</h1>
            <p class="lead mt-2 mb-4 opacity-90 text-white" style="font-family: 'Baloo Da 2', sans-serif;">জ্ঞান আহরণ ও সত্যের আলো সন্ধানে আমাদের সকল প্রকাশনা অনলাইনে সরাসরি পড়ার প্ল্যাটফর্ম</p>
            
            <!-- Search Bar -->
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <form action="{{ route('library.index') }}" method="GET" class="input-group shadow-sm rounded-pill overflow-hidden bg-white p-1">
                        <input type="text" name="search" class="form-control border-0 px-4" placeholder="বইয়ের নাম বা লেখক খুঁজুন..." value="{{ request('search') }}" style="font-family: 'Baloo Da 2', sans-serif; height: 46px; outline: none !important; box-shadow: none;">
                        <button class="btn btn-gold px-4 rounded-pill fw-bold" type="submit" style="height: 46px;">
                            <i class="fas fa-search me-1"></i> খুঁজুন
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Shelf Grid Section -->
    <section class="py-6 bg-off-white" style="background-color: #f7f9fb;">
        <div class="container">
            
            @if(request('search'))
                <div class="mb-4 text-muted" style="font-family: 'Baloo Da 2', sans-serif;">
                    <i class="fas fa-search-plus me-1"></i> "<strong>{{ request('search') }}</strong>" অনুসন্ধান ফলাফলে <strong>{{ $books->total() }}</strong> টি বই পাওয়া গিয়েছে।
                    <a href="{{ route('library.index') }}" class="text-success ms-2 fw-bold text-decoration-none">ফিল্টার মুছুন</a>
                </div>
            @endif

            <div class="row g-4">
                @forelse($books as $book)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="lib-book-card">
                            <!-- Image Container -->
                            <div class="lib-book-cover">
                                <img src="{{ $book->image_url }}" alt="{{ $book->title }}" loading="lazy">
                                <div class="lib-book-overlay">
                                    <a href="{{ route('library.read', $book->slug) }}" class="btn btn-gold btn-sm px-3 rounded-pill fw-bold mb-2">
                                        <i class="fas fa-book-reader me-1"></i> অনলাইনে পড়ুন
                                    </a>
                                    @if($book->pdf_url)
                                        <a href="{{ $book->pdf_url }}" target="_blank" class="btn btn-outline-light btn-sm px-3 rounded-pill fw-bold">
                                            <i class="fas fa-download me-1"></i> পিডিএফ
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Book Body -->
                            <div class="lib-book-body">
                                <h6 class="lib-book-title">
                                    <a href="{{ route('library.read', $book->slug) }}">
                                        {{ $book->title }}
                                    </a>
                                </h6>
                                <p class="lib-book-author text-muted small"><i class="fas fa-pen-nib me-1"></i> {{ $book->writer ?? 'হেযবুত তওহীদ' }}</p>
                                
                                <div class="lib-book-footer d-flex gap-2">
                                    <a href="{{ route('library.read', $book->slug) }}" class="btn btn-gold btn-sm rounded-pill fw-bold w-100 py-2" style="font-size: 0.8rem;">
                                        <i class="fas fa-book-reader me-1"></i> পড়তে শুরু করুন
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-book-dead fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted" style="font-family: 'Baloo Da 2', sans-serif;">লাইব্রেরীতে কোনো বই পাওয়া যায়নি</h4>
                        <p class="text-muted small">অনুগ্রহ করে অন্য কোনো শব্দ দিয়ে সার্চ করে দেখুন।</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($books instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $books->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $books->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </section>

    <!-- Custom Styling for Library Bookshelf -->
    <style>
        .lib-book-card {
            background: #ffffff;
            border: 1px solid #eef0f2;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .lib-book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.08);
        }
        .lib-book-cover {
            height: 290px;
            width: 100%;
            overflow: hidden;
            position: relative;
            background: #f8f9fa;
        }
        .lib-book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        .lib-book-card:hover .lib-book-cover img {
            transform: scale(1.05);
        }
        .lib-book-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 106, 78, 0.85); /* Emerald overlay */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            padding: 20px;
        }
        .lib-book-cover:hover .lib-book-overlay {
            opacity: 1;
        }
        .lib-book-body {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .lib-book-title {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 5px;
            line-height: 1.45;
            min-height: 2.7rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .lib-book-title a {
            color: #2c3e50;
            text-decoration: none !important;
            font-family: 'Baloo Da 2', sans-serif;
            transition: color 0.2s;
        }
        .lib-book-title a:hover {
            color: #006A4E;
        }
        .lib-book-author {
            font-size: 12.5px;
            margin-bottom: 15px;
            font-family: 'Baloo Da 2', sans-serif;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .lib-book-footer {
            margin-top: auto;
        }
        .btn-gold {
            background-color: #a0663f !important;
            border-color: #a0663f !important;
            color: white !important;
            transition: background-color 0.2s;
        }
        .btn-gold:hover {
            background-color: #8b5532 !important;
            border-color: #8b5532 !important;
        }
    </style>

@endsection


