@extends('theme::layouts.app')

@section('title', isset($page) ? $page->title . ' - হেযবুত তওহীদ' : 'আমাদের প্রকাশিত বইসমূহ - হেযবুত তওহীদ')
@section('meta_description', isset($page) && $page->meta_description ? $page->meta_description : 'হেযবুত তওহীদ আন্দোলনের প্রতিষ্ঠাতা এমামুযযামান জনাব মোহাম্মদ বায়াজীদ খান পন্নী এবং হোসাইন মোহাম্মদ সেলিম রচিত সকল বই ও প্রকাশনাসমূহ')

@push('styles')

@endpush

@section('content')

    @php
        $searchForm = '
            <div class="row justify-content-center mt-4">
                <div class="col-md-6 col-lg-5">
                    <form action="' . route('books.index') . '" method="GET" class="input-group shadow-sm rounded-pill overflow-hidden bg-white p-1">
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
        'title' => isset($page) ? $page->title : 'আমাদের প্রকাশিত বইসমূহ',
        'subtitle' => 'সত্য উন্মোচনে এবং চেতনা বিকাশে জ্ঞানগর্ভ প্রকাশনাসমূহ',
        'badge_text' => 'বই ও প্রকাশনা',
        'badge_icon' => 'fas fa-book',
        'extra_html' => $searchForm
    ])

    <!-- Section 1: Popular Books (Top 10 Reader Choice) -->
    @if($popularBooks && count($popularBooks) > 0)
        <section class="ht-book-section bg-white">
            <div class="container">
                <div class="ht-section-top">
                    <h3 class="ht-section-title">
                        পাঠক চাহিদার শীর্ষে বই
                    </h3>
                    <a href="#all-books-section" class="ht-view-all">সব দেখুন</a>
                </div>
                <div class="row g-4">
                    @foreach($popularBooks as $book)
                        <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="ht-book-item shadow-sm">
                                <!-- Badge -->
                                <div class="ht-book-badge-popular"><i class="fas fa-star me-1"></i>সেরা পাঠক প্রিয়</div>
                                
                                <!-- Image -->
                                <div class="ht-book-img">
                                    <a href="{{ route('books.show', $book->slug) }}">
                                        <img src="{{ $book->image_url }}" alt="{{ $book->title }}" loading="lazy">
                                    </a>
                                </div>
                                
                                <!-- Info -->
                                <div class="ht-book-info text-center" style="align-items: center !important; text-align: center !important;">
                                    <div class="ht-book-name text-center" style="text-align: center !important;">
                                        <a href="{{ route('books.show', $book->slug) }}" style="text-align: center !important; display: block;">
                                            {{ $book->title }}
                                        </a>
                                    </div>
                                    <div class="ht-book-writer text-center" style="text-align: center !important; width: 100%;">{{ $book->writer ?? 'হেযবুত তওহীদ' }}</div>
                                    <div class="ht-book-pricing text-center" style="text-align: center !important; width: 100%;">
                                        @if($book->price !== null && $book->price !== '')
                                            মূল্য ৳{{ $book->price }}{{ str_contains($book->price, 'ক') || str_contains($book->price, 'খ') || str_contains($book->price, 'গ') || $book->price == '০' || $book->price == '0' ? '' : '/-' }}
                                        @else
                                            ফ্রি / PDF
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Button Wrapper -->
                                <div class="ht-btn-wrapper">
                                    <a href="{{ route('books.show', $book->slug) }}" class="ht-details-btn">বিস্তারিত দেখুন</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Section 1.5: Best Selling Books -->
    @if($bestSellingBooks && count($bestSellingBooks) > 0)
        <section class="ht-book-section bg-light" style="background-color: #f8fafc; border-top: 1px solid #edf2f7; border-bottom: 1px solid #edf2f7;">
            <div class="container">
                <div class="ht-section-top">
                    <h3 class="ht-section-title">
                        সর্বাধিক বিক্রিত বইসমূহ (Best Selling)
                    </h3>
                </div>
                <div class="row g-4">
                    @foreach($bestSellingBooks as $book)
                        <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="ht-book-item shadow-sm">
                                <!-- Badge -->
                                <div class="ht-book-badge-bestseller"><i class="fas fa-fire me-1"></i>Best Selling</div>
                                
                                <!-- Image -->
                                <div class="ht-book-img">
                                    <a href="{{ route('books.show', $book->slug) }}">
                                        <img src="{{ $book->image_url }}" alt="{{ $book->title }}" loading="lazy">
                                    </a>
                                </div>
                                
                                <!-- Info -->
                                <div class="ht-book-info text-center" style="align-items: center !important; text-align: center !important;">
                                    <div class="ht-book-name text-center" style="text-align: center !important;">
                                        <a href="{{ route('books.show', $book->slug) }}" style="text-align: center !important; display: block;">
                                            {{ $book->title }}
                                        </a>
                                    </div>
                                    <div class="ht-book-writer text-center" style="text-align: center !important; width: 100%;">{{ $book->writer ?? 'হেযবুত তওহীদ' }}</div>
                                    <div class="ht-book-pricing text-center" style="text-align: center !important; width: 100%;">
                                        @if($book->price !== null && $book->price !== '')
                                            মূল্য ৳{{ $book->price }}{{ str_contains($book->price, 'ক') || str_contains($book->price, 'খ') || str_contains($book->price, 'গ') || $book->price == '০' || $book->price == '0' ? '' : '/-' }}
                                        @else
                                            ফ্রি / PDF
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Button Wrapper -->
                                <div class="ht-btn-wrapper">
                                    <a href="{{ route('books.show', $book->slug) }}" class="ht-details-btn">বিস্তারিত দেখুন</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Section 2: All Books Grid with Ajax Load More -->
    <section class="ht-book-section bg-off-white" id="all-books-section" style="background-color: #fbfcfd;">
        <div class="container">
            
            <!-- Dynamic Page Content styled elegantly as centered intro -->
            @if(isset($page) && $page->content && strip_tags($page->content) !== 'আমাদের বই ও প্রকাশনাসমূহ')
                <div class="text-center mx-auto mb-5" style="max-width: 800px;">
                    <p class="text-muted leading-relaxed mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 18px;">
                        {!! strip_tags($page->content) !!}
                    </p>
                    <div style="width: 50px; height: 3px; background: linear-gradient(90deg, #a0663f, #d4af37); margin: 20px auto 0 auto; border-radius: 10px;"></div>
                </div>
            @endif

            <div class="ht-section-top">
                <h3 class="ht-section-title">
                    সকল বইসমূহ
                </h3>
            </div>

            @if(request('search'))
                <div class="mb-4 text-muted" style="font-family: 'Baloo Da 2', sans-serif;">
                    <i class="fas fa-search-plus me-1"></i> "<strong>{{ request('search') }}</strong>" অনুসন্ধান ফলাফলে <strong>{{ $books->total() }}</strong> টি বই পাওয়া গিয়েছে।
                    <a href="{{ route('books.index') }}" class="text-success ms-2 fw-bold text-decoration-none">ফিল্টার মুছুন</a>
                </div>
            @endif

            <!-- Category Filter Tabs -->
            <div class="ht-cat-tabs-container">
                <a href="{{ route('books.index') }}#all-books-section" 
                   class="ht-cat-tab {{ !request('category') ? 'active' : '' }}">
                    সকল প্রকাশনা
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('books.index') }}?category={{ $cat->slug }}#all-books-section" 
                       class="ht-cat-tab {{ request('category') == $cat->slug ? 'active' : '' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
            
            <div class="row g-4" id="all-books-grid">
                @include('theme::pages.books.partials.grid_items', ['books' => $books])
            </div>

            <!-- AJAX Load More Button -->
            @if($books->hasMorePages())
                <div class="ht-load-more-container" id="load-more-wrapper">
                    <button class="ht-load-more-btn" id="btn-load-more" data-page="1">
                        আরও দেখুন <i class="fas fa-spinner fa-spin ms-2" id="spinner" style="display: none;"></i>
                    </button>
                </div>
            @endif
        </div>
    </section>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#btn-load-more').on('click', function() {
        var btn = $(this);
        var page = parseInt(btn.attr('data-page')) + 1;
        var urlParams = new URLSearchParams(window.location.search);
        var category = urlParams.get('category') || '';
        
        $('#spinner').show();
        btn.prop('disabled', true);
        
        $.ajax({
            url: "{{ route('books.load-more') }}",
            type: 'GET',
            data: { 
                page: page,
                category: category,
                search: urlParams.get('search') || ''
            },
            success: function(response) {
                $('#spinner').hide();
                btn.prop('disabled', false);
                
                if (response.success) {
                    $('#all-books-grid').append(response.html);
                    btn.attr('data-page', page);
                    
                    if (!response.hasMore) {
                        $('#load-more-wrapper').fadeOut();
                    }
                }
            },
            error: function() {
                $('#spinner').hide();
                btn.prop('disabled', false);
                alert('বই লোড করতে সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।');
            }
        });
    });
});
</script>
@endpush
