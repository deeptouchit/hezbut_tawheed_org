@extends('theme::layouts.app')

@section('title', $book->title . ' - হেযবুত তওহীদ')

@if(!empty($book->description))
    @section('meta_description', $book->description)
@endif

@push('styles')
<style>
    /* Google Fonts import for premium typography */
    @import url('https://fonts.googleapis.com/css2?family=Baloo+Da+2:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap');

    .ht-rokomari-section {
        background-color: #0f172a; /* Sleek dark slate background */
        padding: 30px 0 60px 0;
    }
    
    /* Breadcrumb styling */
    .ht-breadcrumb {
        font-family: 'Baloo Da 2', sans-serif;
        font-size: 13px;
        color: #94a3b8;
        margin-bottom: 20px;
    }
    .ht-breadcrumb a {
        color: #60a5fa;
        text-decoration: none !important;
    }
    .ht-breadcrumb a:hover {
        color: #93c5fd;
        text-decoration: underline !important;
    }

    /* Left Rokomari Cover Container */
    .ht-rokomari-cover-container {
        border: 1px solid #334155;
        background-color: #1e293b;
        padding: 20px;
        border-radius: 4px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
    }
    .ht-rokomari-cover-box {
        position: relative;
        border: 1px solid #334155;
        padding: 10px;
        margin-bottom: 15px;
        background: #0f172a;
        display: inline-block;
        width: 100%;
    }
    .ht-rokomari-cover-img {
        max-width: 100%;
        height: auto;
        max-height: 350px;
        object-fit: contain;
    }
    
    /* Rokomari "একটু পড়ে দেখুন" arrow */
    .ht-read-preview-badge {
        position: absolute;
        top: 10px;
        right: 15px;
        color: #f43f5e;
        font-family: 'Baloo Da 2', sans-serif;
        font-weight: 700;
        font-size: 13px;
    }
    
    /* Percentage off badge */
    .ht-rokomari-off-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background-color: #f43f5e;
        color: white;
        font-size: 11px;
        font-weight: 700;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        line-height: 1.1;
        box-shadow: 0 2px 5px rgba(244,63,94,0.3);
    }

    /* Main book details card */
    .ht-rokomari-details-card {
        background-color: #1e293b;
        border: 1px solid #334155;
        border-radius: 4px;
        padding: 30px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
    }
    
    .ht-rokomari-title {
        font-family: 'Baloo Da 2', sans-serif;
        font-weight: 700;
        font-size: 24px;
        color: #ffffff;
        margin-bottom: 5px;
    }
    .ht-rokomari-subtitle {
        font-family: 'Baloo Da 2', sans-serif;
        font-size: 15px;
        color: #94a3b8;
        margin-bottom: 12px;
    }
    .ht-rokomari-author {
        font-family: 'Baloo Da 2', sans-serif;
        font-size: 15px;
        color: #cbd5e1;
        margin-bottom: 15px;
    }
    .ht-rokomari-author a {
        color: #60a5fa;
        text-decoration: none !important;
        font-weight: 600;
    }
    .ht-rokomari-author a:hover {
        text-decoration: underline !important;
    }
    
    /* Best Seller & tags */
    .ht-badge-bestseller {
        background-color: #2c251b;
        border: 1px solid #7c2d12;
        color: #fdba74;
        font-family: 'Baloo Da 2', sans-serif;
        font-weight: 700;
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    /* Price section */
    .ht-rokomari-price-row {
        font-family: 'Baloo Da 2', sans-serif;
        font-size: 24px;
        font-weight: 700;
        color: #ffffff;
        margin: 20px 0;
        display: flex;
        align-items: baseline;
        gap: 10px;
    }
    .ht-rokomari-price-old {
        font-size: 16px;
        color: #64748b;
        text-decoration: line-through;
        font-weight: normal;
    }
    
    /* App promo banner */
    .ht-rokomari-promo-banner {
        background-color: #1e1b4b;
        color: #ffffff;
        padding: 12px 18px;
        border-radius: 4px;
        font-family: 'Baloo Da 2', sans-serif;
        font-size: 14px;
        margin-bottom: 20px;
        border: 1px solid #312e81;
    }
    
    /* Stock status checkmark */
    .ht-stock-status {
        color: #10b981;
        font-family: 'Baloo Da 2', sans-serif;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 12px;
    }
    
    /* Share and Wishlist options */
    .ht-action-links {
        display: flex;
        gap: 20px;
        font-family: 'Baloo Da 2', sans-serif;
        font-size: 14px;
        color: #94a3b8;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #334155;
    }
    .ht-action-link-item {
        color: #cbd5e1;
        text-decoration: none !important;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: color 0.2s;
    }
    .ht-action-link-item:hover {
        color: #60a5fa;
    }

    /* Buttons */
    .ht-btn-rokomari-orange {
        background-color: #f7941d;
        color: white !important;
        font-family: 'Baloo Da 2', sans-serif;
        font-weight: 700;
        padding: 12px 30px;
        border-radius: 4px;
        border: none;
        font-size: 15px;
        text-decoration: none !important;
        display: inline-block;
        transition: background-color 0.2s;
        box-shadow: 0 2px 5px rgba(247, 148, 29, 0.2);
    }
    .ht-btn-rokomari-orange:hover {
        background-color: #e08316;
    }
    .ht-btn-rokomari-blue {
        background-color: #0073b6;
        color: white !important;
        font-family: 'Baloo Da 2', sans-serif;
        font-weight: 700;
        padding: 12px 30px;
        border-radius: 4px;
        border: none;
        font-size: 15px;
        text-decoration: none !important;
        display: inline-block;
        transition: background-color 0.2s;
        box-shadow: 0 2px 5px rgba(0, 115, 182, 0.2);
    }
    .ht-btn-rokomari-blue:hover {
        background-color: #005f98;
    }
    .ht-btn-rokomari-gray {
        background-color: #334155;
        color: #cbd5e1 !important;
        font-family: 'Baloo Da 2', sans-serif;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 4px;
        border: 1px solid #475569;
        font-size: 14px;
        text-decoration: none !important;
        display: block;
        width: 100%;
        transition: background-color 0.2s;
    }
    .ht-btn-rokomari-gray:hover {
        background-color: #475569;
    }

    /* Tabs system (Rokomari style) */
    .ht-rokomari-tabs {
        border-bottom: 2px solid #334155;
        font-family: 'Baloo Da 2', sans-serif;
    }
    .ht-rokomari-tabs .nav-link {
        border: none !important;
        color: #94a3b8;
        font-weight: 600;
        padding: 12px 24px;
        background-color: transparent !important;
        border-radius: 0;
        font-size: 15px;
        position: relative;
    }
    .ht-rokomari-tabs .nav-link.active {
        color: #10b981 !important;
    }
    .ht-rokomari-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: #10b981;
    }
    .ht-rokomari-tab-content {
        background-color: #1e293b;
        border: 1px solid #334155;
        border-top: none;
        border-radius: 0 0 4px 4px;
        padding: 30px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
    }
    .ht-spec-table th, .ht-spec-table td {
        font-family: 'Baloo Da 2', sans-serif;
        font-size: 15px;
        padding: 12px 18px;
        color: #cbd5e1;
        border-color: #334155;
    }
    .ht-spec-table tr td.bg-light {
        background-color: #1a2333 !important;
        color: #94a3b8;
    }

    /* Sidebar "এ জাতীয় আরও বই দেখুন" (Rokomari style) */
    .ht-rokomari-sidebar {
        background-color: #1e293b;
        border: 1px solid #334155;
        border-radius: 4px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
    }
    .ht-sidebar-header {
        font-family: 'Baloo Da 2', sans-serif;
        font-weight: 700;
        font-size: 17px;
        color: #ffffff;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #334155;
    }
    .ht-rokomari-recent-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #334155;
        text-decoration: none !important;
    }
    .ht-rokomari-recent-item:last-child {
        border-bottom: none;
    }
    .ht-rokomari-recent-img {
        width: 50px;
        height: 70px;
        min-width: 50px;
        background-color: #0f172a;
        border: 1px solid #334155;
        padding: 2px;
        border-radius: 2px;
    }
    .ht-rokomari-recent-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .ht-rokomari-recent-title {
        font-family: 'Baloo Da 2', sans-serif;
        font-weight: 700;
        font-size: 14px;
        color: #ffffff;
        margin-bottom: 2px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .ht-rokomari-recent-writer {
        font-family: 'Baloo Da 2', sans-serif;
        font-size: 12px;
        color: #94a3b8;
        margin-bottom: 2px;
        display: block;
    }
    .ht-rokomari-recent-price {
        font-family: 'Baloo Da 2', sans-serif;
        font-size: 13px;
        font-weight: 700;
        color: #ffffff;
    }
    
    .cursor-pointer {
        cursor: pointer;
    }
    .rating-stars-select input[type="radio"]:checked + label {
        color: #f7941d !important;
    }
    
    /* Dark Theme Inputs adjustment */
    .ht-rokomari-tab-content input.form-control, .ht-rokomari-tab-content textarea.form-control,
    #reviews-section input.form-control, #reviews-section textarea.form-control {
        background-color: #0f172a;
        border-color: #334155;
        color: #f8fafc;
    }
    .ht-rokomari-tab-content input.form-control:focus, .ht-rokomari-tab-content textarea.form-control:focus,
    #reviews-section input.form-control:focus, #reviews-section textarea.form-control:focus {
        background-color: #0f172a;
        border-color: #60a5fa;
        color: #ffffff;
    }
    .ht-rokomari-tab-content .bg-light, #reviews-section .bg-light {
        background-color: #0f172a !important;
    }

    @media (max-width: 767.98px) {
        .ht-rokomari-section {
            padding: 15px 0;
        }
        .ht-rokomari-details-card {
            padding: 20px;
        }
        .ht-rokomari-title {
            font-size: 20px;
        }
        .ht-rokomari-cover-img {
            max-height: 280px;
        }
    }
</style>
@endpush

@section('content')

    @php
        // Fetch active reviews dynamically
        $reviews = $book->reviews()->orderBy('created_at', 'desc')->get();
        $totalReviews = $reviews->count();
        $avgRating = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 5.0;
        
        $ratingCounts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        foreach($reviews as $r) {
            if (isset($ratingCounts[$r->rating])) {
                $ratingCounts[$r->rating]++;
            }
        }
    @endphp

    <section class="ht-rokomari-section">
        <div class="container">
            <!-- Breadcrumbs -->
            <div class="ht-breadcrumb">
                <a href="/"><i class="fas fa-home me-1"></i>হোম</a>
                <span class="mx-2"><i class="fas fa-chevron-right" style="font-size: 9px; color: #475569;"></i></span>
                <a href="{{ route('page.show', 'publications') }}">প্রকাশনা</a>
                <span class="mx-2"><i class="fas fa-chevron-right" style="font-size: 9px; color: #475569;"></i></span>
                <span class="text-muted">{{ Str::limit($book->title, 35) }}</span>
            </div>

            <div class="row">
                <!-- Left Main Section -->
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <div class="ht-rokomari-details-card">
                        
                        <div class="row g-4 mb-5">
                            <!-- Left Sub-column: Book Cover Frame -->
                            <div class="col-md-5">
                                <div class="ht-rokomari-cover-container">
                                    <div class="ht-rokomari-cover-box">
                                        <!-- Discount percentage badge -->
                                        @php
                                            $discountVal = 0;
                                            $cleanPrice = preg_replace('/[^\d]/', '', $book->price);
                                            $cleanOldPrice = preg_replace('/[^\d]/', '', $book->old_price);
                                            if ($cleanOldPrice && $cleanPrice && intval($cleanOldPrice) > intval($cleanPrice)) {
                                                $discountVal = round(((intval($cleanOldPrice) - intval($cleanPrice)) / intval($cleanOldPrice)) * 100);
                                            }
                                        @endphp
                                        @if($discountVal > 0)
                                            <div class="ht-rokomari-off-badge">
                                                <span>{{ $discountVal }}%</span>
                                                <span style="font-size: 7px; font-weight: bold;">OFF</span>
                                            </div>
                                        @endif

                                        <span class="ht-read-preview-badge">একটু পড়ে দেখুন <i class="fas fa-arrow-down" style="font-size: 10px;"></i></span>
                                        <img src="{{ $book->image_url }}" alt="{{ $book->title }}" class="ht-rokomari-cover-img" loading="lazy">
                                    </div>
                                    
                                    <!-- Read Button underneath -->
                                    <a href="#bookTabs" class="ht-btn-rokomari-gray text-center">
                                        <i class="fas fa-book-open me-1"></i> বিবরণ ও সুচিপত্র দেখুন
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Right Sub-column: Core details -->
                            <div class="col-md-7">
                                <h1 class="ht-rokomari-title">{{ $book->title }}</h1>
                                <p class="ht-rokomari-subtitle">{{ $book->description ? Str::limit($book->description, 75) : 'সত্য উন্মোচনে এবং চেতনা বিকাশে বিশেষ প্রকাশনা' }}</p>
                                
                                <div class="ht-rokomari-author">
                                    by <a href="#">{{ $book->writer ?? 'হেযবুত তওহীদ' }}</a>
                                </div>
                                
                                <!-- Seller Badge & Stars Rating -->
                                <div class="d-flex align-items-center flex-wrap gap-2.5 mb-3">
                                    <span class="ht-badge-bestseller">
                                        <i class="fas fa-fire"></i> #10 Best Seller
                                    </span>
                                    <span class="text-muted small">in {{ $book->category ? $book->category->name : 'প্রকাশনা' }}</span>
                                </div>

                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="text-warning small">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($avgRating))
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-muted small" style="font-family: 'Baloo Da 2', sans-serif;">{{ $avgRating }} Ratings | {{ $totalReviews }} Reviews</span>
                                </div>

                                <!-- Wishlist counter -->
                                <div class="text-muted small mb-3" style="font-family: 'Baloo Da 2', sans-serif;">
                                    <i class="far fa-heart me-1"></i> ২০৫ জনের প্রিয় তালিকায় আছে বইটি
                                </div>

                                <!-- Pricing -->
                                <div class="ht-rokomari-price-row">
                                    @if($book->price !== null && $book->price !== '')
                                        <span>TK. {{ $book->price }}</span>
                                        @if($book->old_price)
                                            <span class="ht-rokomari-price-old">TK. {{ $book->old_price }}</span>
                                        @endif
                                    @else
                                        <span class="text-success fs-5">ফ্রি সংস্করণ</span>
                                    @endif
                                </div>

                                <!-- Rokomari Blue Offer Box -->
                                <div class="ht-rokomari-promo-banner">
                                    <div class="fw-bold mb-1"><i class="fas fa-shipping-fast me-1"></i> অ্যাপে ১ম অর্ডারে ফ্রি শিপিং ১৯৯+ টাকা এমাউন্টে</div>
                                    <div class="small opacity-90">প্রোমোকোড: APP1ST</div>
                                </div>

                                <!-- Stock status -->
                                <div class="ht-stock-status">
                                    <i class="fas fa-check-circle"></i> In Stock (স্টক আউট হওয়ার আগেই অর্ডার করুন)
                                </div>
                                <div class="text-muted small mb-4" style="font-family: 'Baloo Da 2', sans-serif;">
                                    * স্টক আউট হওয়ার আগেই অর্ডার করুন
                                </div>

                                <!-- Dotted Promo Info Box -->
                                <div class="p-3 mb-4 rounded border" style="border-color: #4c3013 !important; background-color: #2c2215; font-family: 'Baloo Da 2', sans-serif; font-size: 13px; line-height: 1.5; color: #fdba74;">
                                    <span class="text-warning"><i class="fas fa-tags me-1"></i></span> শায়েস্তা খাঁ অফারে বই ও পণ্যে ৭১% পর্যন্ত ছাড় ও <strong>FREE SHIPPING</strong> শুধুমাত্র অ্যাপ থেকে প্রথমবার অর্ডারে <strong>APP1ST</strong> প্রোমোকোড ব্যবহারে ১৯৯৳+ অর্ডারে। <a href="#" class="text-primary text-decoration-none">আরও দেখুন <i class="fas fa-chevron-down" style="font-size: 9px;"></i></a>
                                </div>

                                <!-- Buy / Download Buttons -->
                                <div class="d-flex flex-wrap gap-3">
                                    @if($book->pdf_url)
                                        <a href="{{ $book->pdf_url }}" target="_blank" class="ht-btn-rokomari-blue">
                                            <i class="fas fa-cloud-download-alt me-1"></i> পিডিএফ ডাউনলোড করুন (Free PDF)
                                        </a>
                                    @endif
                                    <a href="https://wa.me/8801611883300" target="_blank" class="ht-btn-rokomari-orange">
                                        <i class="fas fa-shopping-bag me-1"></i> এখনই কিনুন (Buy Now)
                                    </a>
                                </div>

                                <!-- Share / Wishlist -->
                                <div class="ht-action-links">
                                    <a href="#" class="ht-action-link-item"><i class="far fa-heart"></i> পছন্দের তালিকায় রাখুন</a>
                                    <a href="#" class="ht-action-link-item"><i class="fas fa-share-alt"></i> বন্ধুদের সাথে শেয়ার করুন</a>
                                </div>
                            </div>
                        </div>

                        <!-- Tabbed Information (Summary, Specification, Author) -->
                        <div class="ht-tabs-container mt-4" id="bookTabs">
                            <ul class="nav nav-tabs ht-rokomari-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary" type="button" role="tab" aria-controls="summary" aria-selected="true">বইয়ের বিবরণ (Summary)</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="specification-tab" data-bs-toggle="tab" data-bs-target="#specification" type="button" role="tab" aria-controls="specification" aria-selected="false">স্পেসিফিকেশন (Specification)</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="author-tab" data-bs-toggle="tab" data-bs-target="#author" type="button" role="tab" aria-controls="author" aria-selected="false">লেখক পরিচিতি (Author)</button>
                                </li>
                            </ul>
                            
                            <div class="tab-content ht-rokomari-tab-content" id="bookTabsContent">
                                <!-- Summary tab -->
                                <div class="tab-pane fade show active" id="summary" role="tabpanel" aria-labelledby="summary-tab">
                                    <h5 class="fw-bold mb-3 text-white" style="font-family: 'Baloo Da 2', sans-serif;">বইটির বিস্তারিত দেখুন</h5>
                                    <div class="lh-lg text-light-emphasis" style="text-align: justify; font-family: 'Baloo Da 2', sans-serif; font-size: 15.5px; color: #cbd5e1 !important;">
                                        {!! $book->content ?? $book->description !!}
                                    </div>
                                </div>
                                
                                <!-- Specification tab -->
                                <div class="tab-pane fade" id="specification" role="tabpanel" aria-labelledby="specification-tab">
                                    <table class="table table-bordered mb-0 ht-spec-table">
                                        <tbody>
                                            <tr>
                                                <td class="fw-bold bg-light" style="width: 250px;">বইয়ের নাম</td>
                                                <td>{{ $book->title }}</td>
                                            </tr>
                                            @if($book->writer)
                                            <tr>
                                                <td class="fw-bold bg-light">লেখক</td>
                                                <td>{{ $book->writer }}</td>
                                            </tr>
                                            @endif
                                            @if($book->category)
                                            <tr>
                                                <td class="fw-bold bg-light">ক্যাটাগরি</td>
                                                <td>{{ $book->category->name }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td class="fw-bold bg-light">ভাষা</td>
                                                <td>বাংলা</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold bg-light">ফাইল ফরম্যাট</td>
                                                <td>পিডিএফ (PDF) / প্রিন্টেড সংস্করণ</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold bg-light">মূল্য</td>
                                                <td>
                                                    @if($book->price)
                                                        ৳{{ $book->price }}
                                                    @else
                                                        ফ্রি সংস্করণ
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Author tab -->
                                <div class="tab-pane fade" id="author" role="tabpanel" aria-labelledby="author-tab">
                                    <div class="d-flex align-items-center gap-3 mb-4">
                                        <div class="bg-dark p-3 rounded-circle text-muted d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 24px;">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-1 text-white" style="font-family: 'Baloo Da 2', sans-serif;">{{ $book->writer ?? 'হেযবুত তওহীদ' }}</h5>
                                            <p class="mb-0 text-muted small" style="font-family: 'Baloo Da 2', sans-serif;">হেযবুত তওহীদ প্রকাশনার সম্মানিত লেখক ও চিন্তাবিদ।</p>
                                        </div>
                                    </div>
                                    <div class="lh-lg text-secondary" style="font-family: 'Baloo Da 2', sans-serif; font-size: 15px; color: #94a3b8 !important;">
                                        {{ $book->writer ?? 'হেযবুত তওহীদ' }} মূলত সমাজ সংস্কার, ধর্মীয় অপব্যাখ্যার অবসান এবং সত্য প্রতিষ্ঠার লক্ষে বিভিন্ন প্রবন্ধ ও বই রচনা করে আসছেন।
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reviews and Ratings Section (Separated & Underneath) -->
                        <div class="ht-rokomari-details-card mt-4" id="reviews-section">
                            <h4 class="fw-bold mb-4 pb-2 border-bottom text-white" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-star me-2" style="color: #f7941d;"></i> রিভিউ ও রেটিং (Reviews & Ratings)</h4>
                            
                            <div class="row g-4">
                                <!-- Review Summary -->
                                <div class="col-md-5">
                                    <h5 class="fw-bold mb-3 text-white" style="font-family: 'Baloo Da 2', sans-serif;">রিভিউ ও রেটিং সামারি</h5>
                                    
                                    <div class="d-flex align-items-center gap-3 mb-4">
                                        <div class="display-4 fw-bold text-white mb-0">{{ $avgRating }}</div>
                                        <div>
                                            <div class="text-warning fs-5">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= round($avgRating))
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="text-muted small" style="font-family: 'Baloo Da 2', sans-serif; color: #94a3b8 !important;">{{ $totalReviews }} টি রিভিউ এর উপর ভিত্তি করে</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Progress bars for ratings -->
                                    <div class="d-flex flex-column gap-2 mb-4">
                                        @for($star = 5; $star >= 1; $star--)
                                            @php
                                                $percentage = $totalReviews > 0 ? ($ratingCounts[$star] / $totalReviews) * 100 : 0;
                                            @endphp
                                            <div class="d-flex align-items-center gap-2" style="font-family: 'Baloo Da 2', sans-serif;">
                                                <span class="small text-muted" style="width: 50px;">{{ $star }} Star</span>
                                                <div class="progress flex-grow-1" style="height: 8px; background-color: #0f172a;">
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="small text-muted" style="width: 30px; text-align: right;">{{ $ratingCounts[$star] }}</span>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                                
                                <!-- Write a Review Form -->
                                <div class="col-md-7">
                                    <h5 class="fw-bold mb-3 text-white" style="font-family: 'Baloo Da 2', sans-serif;">বইটি সম্পর্কে আপনার মতামত দিন</h5>
                                    
                                    @if(session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <form action="{{ route('books.review.store', $book->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-white small" style="font-family: 'Baloo Da 2', sans-serif;">রেটিং দিন (Rating) *</label>
                                            <div class="d-flex gap-3 rating-stars-select">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <div class="form-check form-check-inline m-0">
                                                        <input class="form-check-input d-none" type="radio" name="rating" id="inlineRadio{{ $i }}" value="{{ $i }}" {{ $i == 5 ? 'checked' : '' }}>
                                                        <label class="form-check-label text-warning cursor-pointer fs-5 rating-star-label" for="inlineRadio{{ $i }}" data-rating="{{ $i }}">
                                                            <i class="fas fa-star"></i>
                                                        </label>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6 mb-3">
                                                <label for="reviewerName" class="form-label fw-bold text-white small" style="font-family: 'Baloo Da 2', sans-serif;">আপনার নাম *</label>
                                                <input type="text" class="form-control" name="name" id="reviewerName" placeholder="নাম লিখুন" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="reviewerEmail" class="form-label fw-bold text-white small" style="font-family: 'Baloo Da 2', sans-serif;">ইমেইল এড্রেস</label>
                                                <input type="email" class="form-control" name="email" id="reviewerEmail" placeholder="ইমেইল লিখুন">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="reviewComment" class="form-label fw-bold text-white small" style="font-family: 'Baloo Da 2', sans-serif;">মন্তব্য *</label>
                                            <textarea class="form-control" name="comment" id="reviewComment" rows="4" placeholder="বইটি সম্পর্কে আপনার সৎ মতামত লিখুন..." required minlength="5"></textarea>
                                        </div>
                                        
                                        <button type="submit" class="ht-btn-rokomari-orange border-0">রিভিউ জমা দিন</button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- User Reviews List -->
                            <div class="mt-5 border-top pt-4" style="border-color: #334155 !important;">
                                <h5 class="fw-bold mb-4 text-white" style="font-family: 'Baloo Da 2', sans-serif;"><i class="far fa-comments me-2"></i> গ্রাহকদের রিভিউসমূহ ({{ $totalReviews }})</h5>
                                
                                <div class="d-flex flex-column gap-4">
                                    @forelse($reviews as $rev)
                                        <div class="d-flex gap-3 align-items-start p-3 bg-light rounded-3">
                                            <div class="bg-dark bg-opacity-30 text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; min-width: 48px; font-size: 20px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
                                                    <h6 class="fw-bold text-white mb-0" style="font-family: 'Baloo Da 2', sans-serif;">{{ $rev->name }}</h6>
                                                    <span class="text-muted small" style="font-family: 'Baloo Da 2', sans-serif;">{{ $rev->created_at->diffForHumans() }}</span>
                                                </div>
                                                <div class="text-warning mb-2" style="font-size: 13px;">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $rev->rating)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <p class="mb-0 text-light-emphasis small" style="font-family: 'Baloo Da 2', sans-serif; text-align: justify; color: #cbd5e1 !important;">{{ $rev->comment }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 text-muted" style="font-family: 'Baloo Da 2', sans-serif;">
                                            <i class="far fa-comment-dots fs-3 mb-2 d-block"></i> এই বইটির জন্য এখনও কোনো রিভিউ দেওয়া হয়নি। প্রথম রিভিউটি আপনিই দিন!
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right Sidebar: এ জাতীয় আরও বই দেখুন -->
                <div class="col-lg-4">
                    <div class="ht-rokomari-sidebar">
                        <h5 class="ht-sidebar-header">এ জাতীয় আরও বই দেখুন</h5>
                        
                        <div class="d-flex flex-column">
                            @forelse($recentBooks as $recent)
                                <a href="{{ route('books.show', $recent->slug) }}" class="ht-rokomari-recent-item">
                                    <div class="ht-rokomari-recent-img">
                                        <img src="{{ $recent->image_url }}" alt="{{ $recent->title }}" loading="lazy">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="ht-rokomari-recent-title">{{ $recent->title }}</h6>
                                        <span class="ht-rokomari-recent-writer">{{ $recent->writer ?? 'হেযবুত তওহীদ' }}</span>
                                        
                                        <!-- Rating stars -->
                                        <div class="text-warning mb-1" style="font-size: 11px;">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <span class="text-muted ms-1" style="font-size: 10px;">(87)</span>
                                        </div>

                                        <!-- Price -->
                                        <div class="ht-rokomari-recent-price">
                                            @if($recent->price !== null && $recent->price !== '')
                                                TK. {{ $recent->price }}
                                                @if($recent->old_price)
                                                    <span class="text-muted text-decoration-line-through fw-normal ms-1" style="font-size: 11px;">TK. {{ $recent->old_price }}</span>
                                                @endif
                                            @else
                                                TK. 0 (Free PDF)
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <p class="text-muted small mb-0 p-3 text-center" style="font-family: 'Baloo Da 2', sans-serif;">অন্য কোনো বই পাওয়া যায়নি।</p>
                            @endforelse
                        </div>

                        <div class="mt-4 pt-3 border-top border-light">
                            <a href="{{ route('page.show', 'publications') }}" class="btn btn-outline-success btn-sm w-100 fw-bold py-2" style="font-family: 'Baloo Da 2', sans-serif; border-color: #10b981; color: #10b981;">
                                সব প্রকাশনা দেখুন <i class="fas fa-list ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ratingLabels = document.querySelectorAll('.rating-star-label');
        ratingLabels.forEach(label => {
            label.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                ratingLabels.forEach(l => {
                    const r = parseInt(l.getAttribute('data-rating'));
                    const icon = l.querySelector('i');
                    if (r <= rating) {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                    } else {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                    }
                });
            });
        });
    });
</script>
@endpush
