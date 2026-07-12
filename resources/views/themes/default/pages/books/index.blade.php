@extends('theme::layouts.app')

@section('title', isset($page) ? $page->title . ' - হেযবুত তওহীদ' : 'আমাদের প্রকাশিত বইসমূহ - হেযবুত তওহীদ')
@section('meta_description', isset($page) && $page->meta_description ? $page->meta_description : 'হেযবুত তওহীদ আন্দোলনের প্রতিষ্ঠাতা এমামুযযামান জনাব মোহাম্মদ বায়াজীদ খান পন্নী এবং হোসাইন মোহাম্মদ সেলিম রচিত সকল বই ও প্রকাশনাসমূহ')

@push('styles')
<style>
    /* Google Fonts import for premium typography */
    @import url('https://fonts.googleapis.com/css2?family=Baloo+Da+2:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap');

    .ht-book-section {
        padding: 60px 0;
    }
    .ht-section-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 40px;
        padding-bottom: 15px;
        border-bottom: 2px solid #edf2f7;
        position: relative;
    }
    .ht-section-title {
        font-size: 26px;
        font-weight: 800;
        color: #1a202c;
        position: relative;
        padding-bottom: 15px;
        margin-bottom: -17px;
        font-family: 'Baloo Da 2', sans-serif;
    }
    .ht-section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #a0663f, #d4af37);
        border-radius: 10px;
    }
    .ht-view-all {
        background: linear-gradient(135deg, #a0663f 0%, #8b5532 100%);
        color: white !important;
        padding: 8px 24px;
        border-radius: 30px;
        font-weight: bold;
        text-decoration: none !important;
        font-family: 'Baloo Da 2', sans-serif;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(160, 102, 63, 0.15);
    }
    .ht-view-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(160, 102, 63, 0.25);
    }
    
    /* Premium Book Card Design */
    .ht-book-item {
        background: #fff;
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid #f0f2f5;
    }
    .ht-book-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    /* Cover Image Wrapper with Breathing Room & 3D Shadow */
    .ht-book-img {
        padding: 20px;
        height: 290px;
        width: 100%;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid #f1f5f9;
    }
    .ht-book-img img {
        max-height: 100%;
        max-width: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: 2px 6px 6px 2px;
        box-shadow: 4px 6px 18px rgba(0,0,0,0.18);
        transition: transform 0.4s ease;
    }
    
    /* Real Book Left Edge Spine Highlight overlay */
    .ht-book-img::after {
        content: '';
        position: absolute;
        top: 20px;
        left: calc(50% - 1px);
        pointer-events: none;
    }
    .ht-book-item:hover .ht-book-img img {
        transform: scale(1.04) rotate(-1deg);
    }
    
    /* Badge styling */
    .ht-book-badge-popular {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 10;
        background: linear-gradient(135deg, #d4af37 0%, #aa7c11 100%);
        color: white;
        font-size: 11px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 30px;
        font-family: 'Baloo Da 2', sans-serif;
        box-shadow: 0 4px 10px rgba(170,124,17,0.3);
        border: 1px solid rgba(255,255,255,0.2);
    }
    .ht-book-badge-bestseller {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 10;
        background: linear-gradient(135deg, #a0663f 0%, #7a4624 100%);
        color: white;
        font-size: 11px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 30px;
        font-family: 'Baloo Da 2', sans-serif;
        box-shadow: 0 4px 10px rgba(160,102,63,0.3);
        border: 1px solid rgba(255,255,255,0.2);
    }
    .ht-book-badge-discount {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 10;
        background: linear-gradient(135deg, #ff4e50 0%, #f9d423 100%);
        color: white;
        font-size: 11px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 30px;
        font-family: 'Baloo Da 2', sans-serif;
        box-shadow: 0 4px 10px rgba(255,78,80,0.3);
        border: 1px solid rgba(255,255,255,0.2);
    }
    
    /* Info Section */
    .ht-book-info {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .ht-book-name {
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 8px;
        line-height: 1.4;
        text-align: center;
        width: 100%;
    }
    .ht-book-name a {
        color: #2d3748;
        text-decoration: none !important;
        font-family: 'Baloo Da 2', sans-serif;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.2s;
        width: 100%;
    }
    .ht-book-name a:hover {
        color: #a0663f;
    }
    .ht-book-writer {
        font-size: 13px;
        color: #718096;
        margin-bottom: 12px;
        font-family: 'Baloo Da 2', sans-serif;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-align: center;
        width: 100%;
    }
    .ht-book-pricing {
        font-size: 17px;
        font-weight: 700;
        color: #a0663f;
        font-family: 'Baloo Da 2', sans-serif;
        margin-top: auto;
        text-align: center;
        width: 100%;
    }
    
    /* Clean button layout with inset margin */
    .ht-btn-wrapper {
        padding: 0 20px 20px 20px;
        margin-top: auto;
    }
    .ht-details-btn {
        display: block;
        width: 100%;
        background: linear-gradient(135deg, #a0663f 0%, #8b5532 100%);
        color: white !important;
        text-align: center;
        padding: 10px 15px;
        font-weight: 700;
        text-decoration: none !important;
        border: none;
        font-family: 'Baloo Da 2', sans-serif;
        font-size: 14px;
        border-radius: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(160, 102, 63, 0.15);
    }
    .ht-details-btn:hover {
        background: linear-gradient(135deg, #8b5532 0%, #724223 100%);
        box-shadow: 0 6px 18px rgba(160, 102, 63, 0.3);
        transform: translateY(-1px);
    }
    
    /* Category Filter Tabs Styling */
    .ht-cat-tabs-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 40px;
    }
    .ht-cat-tab {
        background-color: #fff;
        color: #4a5568;
        border: 1px solid #e2e8f0;
        padding: 10px 26px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none !important;
        font-family: 'Baloo Da 2', sans-serif;
        font-size: 15px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }
    .ht-cat-tab:hover {
        color: #a0663f !important;
        border-color: #a0663f;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(160, 102, 63, 0.08);
    }
    .ht-cat-tab.active {
        background: linear-gradient(135deg, #a0663f 0%, #8b5532 100%);
        color: white !important;
        border-color: transparent;
        box-shadow: 0 8px 16px rgba(160, 102, 63, 0.2);
    }
    
    /* Banner styles */
    .ht-banner-title {
        font-family: 'Baloo Da 2', sans-serif;
        font-weight: 800;
        letter-spacing: -0.5px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }
    
    /* Load more styles */
    .ht-load-more-container {
        text-align: center;
        margin-top: 50px;
    }
    .ht-load-more-btn {
        background: linear-gradient(135deg, #a0663f 0%, #8b5532 100%);
        color: white;
        border: none;
        padding: 12px 50px;
        font-size: 16px;
        font-weight: 700;
        border-radius: 50px;
        cursor: pointer;
        font-family: 'Baloo Da 2', sans-serif;
        transition: all 0.3s ease;
        box-shadow: 0 6px 15px rgba(160, 102, 63, 0.2);
    }
    .ht-load-more-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(160, 102, 63, 0.3);
    }

    /* ======================================================= */
    /* RESPONSIVE MEDIA QUERIES FOR MOBILE DEVICES */
    /* ======================================================= */
    @media (max-width: 767.98px) {
        .ht-book-section {
            padding: 35px 0;
        }
        .ht-section-top {
            margin-bottom: 25px;
            padding-bottom: 10px;
        }
        .ht-section-title {
            font-size: 19px;
            padding-bottom: 10px;
            margin-bottom: -12px;
        }
        .ht-section-title::after {
            width: 50px;
            height: 3px;
        }
        .ht-view-all {
            padding: 5px 16px;
            font-size: 12px;
            border-radius: 20px;
        }
        
        /* Compact cards on mobile grid */
        .ht-book-item {
            border-radius: 12px;
        }
        
        /* Proportional image box height instead of 290px */
        .ht-book-img {
            padding: 10px;
            height: 160px; /* Perfectly scales 2-columns on mobile portrait */
        }
        .ht-book-img img {
            box-shadow: 2px 4px 10px rgba(0,0,0,0.15);
        }
        
        /* Shrink badges so they don't cover cover title */
        .ht-book-badge-popular, .ht-book-badge-bestseller, .ht-book-badge-discount {
            top: 6px;
            left: 6px;
            font-size: 9px;
            padding: 3px 8px;
        }
        
        /* Tight info layout with smaller text sizes */
        .ht-book-info {
            padding: 10px;
        }
        .ht-book-name {
            font-size: 13px;
            margin-bottom: 4px;
        }
        .ht-book-writer {
            font-size: 11px;
            margin-bottom: 6px;
        }
        .ht-book-pricing {
            font-size: 13px;
        }
        
        /* Small button inside padded card */
        .ht-btn-wrapper {
            padding: 0 10px 10px 10px;
        }
        .ht-details-btn {
            padding: 6px 8px;
            font-size: 12px;
            border-radius: 6px;
        }
        
        /* Category tab adjustments */
        .ht-cat-tabs-container {
            margin-bottom: 25px;
            gap: 6px;
        }
        .ht-cat-tab {
            padding: 6px 16px;
            font-size: 13px;
        }
        
        /* Banner heading size adjustments */
        .ht-banner-title {
            font-size: 28px !important;
        }
        .lead {
            font-size: 16px !important;
        }
    }
</style>
@endpush

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => isset($page) ? $page->title : 'আমাদের প্রকাশিত বইসমূহ',
        'subtitle' => 'সত্য উন্মোচনে এবং চেতনা বিকাশে জ্ঞানগর্ভ প্রকাশনাসমূহ',
        'badge_text' => 'বই ও প্রকাশনা',
        'badge_icon' => 'fas fa-book'
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

            <!-- Category Filter Tabs -->
            <div class="ht-cat-tabs-container">
                <a href="{{ route('page.show', 'publications') }}#all-books-section" 
                   class="ht-cat-tab {{ !request('category') ? 'active' : '' }}">
                    সকল প্রকাশনা
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('page.show', 'publications') }}?category={{ $cat->slug }}#all-books-section" 
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
                category: category
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
