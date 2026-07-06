@extends('theme::layouts.app')

@section('title', isset($page) ? $page->title . ' - হেযবুত তওহীদ' : 'আমাদের প্রকাশিত বইসমূহ - হেযবুত তওহীদ')
@section('meta_description', isset($page) && $page->meta_description ? $page->meta_description : 'হেযবুত তওহীদ আন্দোলনের প্রতিষ্ঠাতা এমামুযযামান জনাব মোহাম্মদ বায়াজীদ খান পন্নী এবং হোসাইন মোহাম্মদ সেলিম রচিত সকল বই ও প্রকাশনাসমূহ')

@push('styles')
<style>
    .ht-book-section {
        padding: 40px 0;
    }
    .ht-section-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 30px;
        padding-bottom: 12px;
        border-bottom: 2px solid #eef0f2;
        position: relative;
    }
    .ht-section-title {
        font-size: 22px;
        font-weight: 700;
        color: #333;
        position: relative;
        padding-bottom: 12px;
        margin-bottom: -14px;
        font-family: 'Baloo Da 2', sans-serif;
    }
    .ht-section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100px;
        height: 3px;
        background-color: #a0663f; /* Copper brown */
    }
    .ht-view-all {
        background-color: #a0663f;
        color: white !important;
        padding: 6px 18px;
        border-radius: 4px;
        font-weight: bold;
        text-decoration: none !important;
        font-family: 'Baloo Da 2', sans-serif;
        font-size: 14px;
        transition: background-color 0.2s;
    }
    .ht-view-all:hover {
        background-color: #8b5532;
    }
    .ht-book-item {
        background: #fff;
        border: 1px solid #eef0f2;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .ht-book-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    }
    .ht-book-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 10;
        background: #e24e43; /* Red badge */
        color: white;
        font-size: 11px;
        font-weight: bold;
        padding: 3px 8px;
        border-radius: 4px;
        font-family: 'Baloo Da 2', sans-serif;
    }
    .ht-book-img {
        padding: 0;
        height: 280px;
        width: 100%;
        overflow: hidden;
        background: #f8f9fa;
        display: block;
    }
    .ht-book-img img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Full cover fill */
        transition: transform 0.3s;
    }
    .ht-book-info {
        padding: 10px 15px 15px 15px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .ht-book-name {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 5px;
        line-height: 1.4;
    }
    .ht-book-name a {
        color: #2c3e50;
        text-decoration: none !important;
        font-family: 'Baloo Da 2', sans-serif;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .ht-book-name a:hover {
        color: #a0663f;
    }
    .ht-book-writer {
        font-size: 13px;
        color: #7f8c8d;
        margin-bottom: 10px;
        font-family: 'Baloo Da 2', sans-serif;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .ht-book-pricing {
        font-size: 16px;
        font-weight: bold;
        color: #a0663f; /* Copper brown price */
        font-family: 'Baloo Da 2', sans-serif;
    }
    .ht-add-cart-btn {
        display: block;
        width: 100%;
        background-color: #a0663f; /* Copper brown button */
        color: white !important;
        text-align: center;
        padding: 10px;
        font-weight: bold;
        text-decoration: none !important;
        border: none;
        font-family: 'Baloo Da 2', sans-serif;
        font-size: 15px;
        margin-top: auto;
        border-radius: 0 0 7px 7px;
        transition: background-color 0.2s;
    }
    .ht-add-cart-btn:hover {
        background-color: #8b5532;
    }
    .ht-load-more-container {
        text-align: center;
        margin-top: 40px;
    }
    .ht-load-more-btn {
        background-color: #a0663f;
        color: white;
        border: none;
        padding: 10px 45px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 30px;
        cursor: pointer;
        font-family: 'Baloo Da 2', sans-serif;
        transition: background-color 0.2s;
    }
    .ht-load-more-btn:hover {
        background-color: #8b5532;
    }
</style>
@endpush

@section('content')

    <!-- Banner Header -->
    <div class="py-5 text-white position-relative" style="background: linear-gradient(rgba(0,106,78,0.85), rgba(0,106,78,0.85)), url('https://images.unsplash.com/photo-1506880018603-83d5b814b5a6?q=80&w=1200') no-repeat center center; background-size: cover; border-bottom: 4px solid #10B981;">
        <div class="container py-4 text-center">
            <h1 class="display-4 fw-bold mb-0 text-shadow text-white">{{ isset($page) ? $page->title : 'আমাদের প্রকাশিত বইসমূহ' }}</h1>
            <p class="lead mt-2 mb-0 opacity-90 text-white" style="font-family: 'Baloo Da 2', sans-serif;">সত্য উন্মোচনে এবং চেতনা বিকাশে জ্ঞানগর্ভ প্রকাশনাসমূহ</p>
        </div>
    </div>

    <!-- Dynamic Page Content -->
    @if(isset($page) && $page->content)
        <section class="py-5 bg-white border-bottom">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="card border-0 shadow-sm p-4 bg-light rounded-4">
                            <div class="card-body">
                                {!! $page->content !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

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
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="ht-book-item">
                                <!-- Badge -->
                                <div class="ht-book-badge">সেরা পাঠক প্রিয়</div>
                                
                                <!-- Image -->
                                <div class="ht-book-img">
                                    <a href="{{ route('books.show', $book->slug) }}">
                                        <img src="{{ $book->image_url }}" alt="{{ $book->title }}" loading="lazy">
                                    </a>
                                </div>
                                
                                <!-- Info -->
                                <div class="ht-book-info">
                                    <div class="ht-book-name">
                                        <a href="{{ route('books.show', $book->slug) }}">
                                            {{ $book->title }}
                                        </a>
                                    </div>
                                    <div class="ht-book-writer">{{ $book->writer ?? 'হেযবুত তওহীদ' }}</div>
                                    <div class="ht-book-pricing">
                                        @if($book->price !== null && $book->price !== '')
                                            মূল্য ৳{{ $book->price }}{{ str_contains($book->price, 'ক') || str_contains($book->price, 'খ') || str_contains($book->price, 'গ') || $book->price == '০' || $book->price == '0' ? '' : '/-' }}
                                        @else
                                            ফ্রি / PDF
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Button -->
                                <a href="{{ route('books.show', $book->slug) }}" class="ht-add-cart-btn">বিস্তারিত দেখুন</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Section 2: All Books Grid with Ajax Load More -->
    <section class="ht-book-section bg-off-white" id="all-books-section" style="background-color: #fcfcfc;">
        <div class="container">
            <div class="ht-section-top">
                <h3 class="ht-section-title">
                    সকল বইসমূহ
                </h3>
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
        
        $('#spinner').show();
        btn.prop('disabled', true);
        
        $.ajax({
            url: "{{ route('books.load-more') }}",
            type: 'GET',
            data: { page: page },
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


