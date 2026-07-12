@extends('theme::layouts.app')

@section('title', 'ডিজিটাল লাইব্রেরী - হেযবুত তওহীদ')
@section('meta_description', 'হেযবুত তওহীদ আন্দোলনের প্রতিষ্ঠাতা এমামুযযামান জনাব মোহাম্মদ বায়াজীদ খান পন্নী এবং হোসাইন মোহাম্মদ সেলিম রচিত সকল বই ও প্রকাশনাসমূহ অনলাইনে পড়ার ডিজিটাল লাইব্রেরী')

@section('content')

    @php
        $searchForm = '
            <div class="row justify-content-center mt-4">
                <div class="col-md-6 col-lg-5">
                    <form action="' . route('library.index') . '" method="GET" class="input-group shadow-sm rounded-pill overflow-hidden bg-white p-1">
                        <input type="text" name="search" class="form-control border-0 px-4" placeholder="বইয়ের নাম বা লেখক খুঁজুন..." value="' . htmlspecialchars(request('search', '')) . '" style="font-family: \'Baloo Da 2\', sans-serif; height: 46px; outline: none !important; box-shadow: none;">
                        <button class="btn btn-success px-4 rounded-pill fw-bold" type="submit" style="height: 46px; background-color: #006A4E; border-color: #006A4E;">
                            <i class="fas fa-search me-1"></i> খুঁজুন
                        </button>
                    </form>
                </div>
            </div>
        ';
    @endphp

    @include('theme::partials.hero_banner', [
        'title' => 'ডিজিটাল লাইব্রেরী',
        'subtitle' => 'জ্ঞান আহরণ ও সত্যের আলো সন্ধানে আমাদের সকল প্রকাশনা অনলাইনে সরাসরি পড়ার প্ল্যাটফর্ম',
        'badge_text' => 'ডিজিটাল লাইব্রেরী',
        'badge_icon' => 'fas fa-book-reader',
        'extra_html' => $searchForm
    ])

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
    

@endsection


