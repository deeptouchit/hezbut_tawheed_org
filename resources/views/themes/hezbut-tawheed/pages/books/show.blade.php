@extends('theme::layouts.app')

@section('title', $book->title . ' - হেযবুত তওহীদ')

@if(!empty($book->description))
    @section('meta_description', $book->description)
@endif

@push('styles')

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
                <span class="mx-2"><i class="fas fa-chevron-right" style="font-size: 9px; color: #a0aec0;"></i></span>
                <a href="{{ route('books.index') }}">প্রকাশনা</a>
                <span class="mx-2"><i class="fas fa-chevron-right" style="font-size: 9px; color: #a0aec0;"></i></span>
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
                                <p class="ht-rokomari-subtitle">{{ $book->description ? Str::limit($book->description, 150) : 'সত্য উন্মোচনে এবং চেতনা বিকাশে বিশেষ প্রকাশনা' }}</p>
                                
                                <div class="ht-rokomari-author">
                                    লেখক: <span class="fw-semibold">{{ $book->writer ?? 'হেযবুত তওহীদ' }}</span>
                                </div>
                                
                                <div class="d-flex align-items-center flex-wrap gap-2.5 mb-3 mt-2">
                                    @if($book->is_bestseller)
                                        <span class="ht-badge-bestseller">
                                            <i class="fas fa-fire"></i> বেস্ট সেলার
                                        </span>
                                    @endif
                                    <span class="text-muted small">ক্যাটাগরি: {{ $book->category ? $book->category->name : 'প্রকাশনা' }}</span>
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
                                    <span class="text-muted small" style="font-family: 'Baloo Da 2', sans-serif;">({{ $avgRating }} রেটিং | {{ $totalReviews }} রিভিউ)</span>
                                </div>

                                <!-- Pricing -->
                                <div class="ht-rokomari-price-row mb-4">
                                    @if($book->price !== null && $book->price !== '' && $book->price != '০' && $book->price != '0')
                                        <span>৳ {{ $book->price }}</span>
                                        @if($book->old_price)
                                            <span class="ht-rokomari-price-old">৳ {{ $book->old_price }}</span>
                                        @endif
                                    @else
                                        <span class="text-success fs-5 fw-bold"><i class="fas fa-check-circle me-1"></i> বিনামূল্যে</span>
                                    @endif
                                </div>

                                <!-- Buy / Download Buttons -->
                                <div class="d-flex flex-wrap gap-3">
                                    <a href="{{ route('books.read', $book->slug) }}" class="ht-btn-rokomari-blue" style="background-color: #006A4E; border-color: #006A4E; color: white !important;">
                                        <i class="fas fa-book-open me-1"></i> অনলাইনে পড়ুন (Read Online)
                                    </a>
                                    @if($book->pdf_url)
                                        <a href="{{ asset($book->pdf_url) }}" target="_blank" class="ht-btn-rokomari-gray text-decoration-none d-flex align-items-center justify-content-center" style="border: 1px solid #cbd5e1; color: #475569; padding: 0 20px; height: 46px; border-radius: 4px; font-weight: 600; font-size: 0.95rem;">
                                            <i class="fas fa-cloud-download-alt me-1"></i> পিডিএফ ডাউনলোড করুন
                                        </a>
                                    @endif
                                    <a href="https://wa.me/8801711005025" target="_blank" class="ht-btn-rokomari-orange text-decoration-none d-flex align-items-center justify-content-center">
                                        <i class="fab fa-whatsapp me-1"></i> হোয়াটসঅ্যাপে অর্ডার করুন
                                    </a>
                                </div>

                                <!-- Share -->
                                <div class="ht-action-links mt-3">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="ht-action-link-item"><i class="fab fa-facebook me-1"></i> ফেসবুকে শেয়ার করুন</a>
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
                                    <h5 class="fw-bold mb-3 text-dark" style="font-family: 'Baloo Da 2', sans-serif;">বইটির বিস্তারিত দেখুন</h5>
                                    <div class="lh-lg text-dark-emphasis" style="text-align: justify; font-family: 'Baloo Da 2', sans-serif; font-size: 15.5px;">
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
                                        <div class="bg-light p-3 rounded-circle text-muted d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 24px;">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-1 text-dark" style="font-family: 'Baloo Da 2', sans-serif;">{{ $book->writer ?? 'হেযবুত তওহীদ' }}</h5>
                                            <p class="mb-0 text-muted small" style="font-family: 'Baloo Da 2', sans-serif;">হেযবুত তওহীদ প্রকাশনার সম্মানিত লেখক ও চিন্তাবিদ।</p>
                                        </div>
                                    </div>
                                    <div class="lh-lg text-secondary" style="font-family: 'Baloo Da 2', sans-serif; font-size: 15px;">
                                        {{ $book->writer ?? 'হেযবুত তওহীদ' }} মূলত সমাজ সংস্কার, ধর্মীয় অপব্যাখ্যার অবসান এবং সত্য প্রতিষ্ঠার লক্ষে বিভিন্ন প্রবন্ধ ও বই রচনা করে আসছেন।
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reviews and Ratings Section (Separated & Underneath) -->
                        <div class="ht-rokomari-details-card mt-4" id="reviews-section">
                            <h4 class="fw-bold mb-4 pb-2 border-bottom text-dark" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-star me-2" style="color: #f7941d;"></i> রিভিউ ও রেটিং (Reviews & Ratings)</h4>
                            
                            <div class="row g-4">
                                <!-- Review Summary -->
                                <div class="col-md-5">
                                    <h5 class="fw-bold mb-3 text-dark" style="font-family: 'Baloo Da 2', sans-serif;">রিভিউ ও রেটিং সামারি</h5>
                                    
                                    <div class="d-flex align-items-center gap-3 mb-4">
                                        <div class="display-4 fw-bold text-dark mb-0">{{ $avgRating }}</div>
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
                                            <div class="text-muted small" style="font-family: 'Baloo Da 2', sans-serif;">{{ $totalReviews }} টি রিভিউ এর উপর ভিত্তি করে</div>
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
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="small text-muted" style="width: 30px; text-align: right;">{{ $ratingCounts[$star] }}</span>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                                
                                <!-- Write a Review Form -->
                                <div class="col-md-7">
                                    <h5 class="fw-bold mb-3 text-dark" style="font-family: 'Baloo Da 2', sans-serif;">বইটি সম্পর্কে আপনার মতামত দিন</h5>
                                    
                                    @if(session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <form action="{{ route('books.review.store', $book->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-dark small" style="font-family: 'Baloo Da 2', sans-serif;">রেটিং দিন (Rating) *</label>
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
                                                <label for="reviewerName" class="form-label fw-bold text-dark small" style="font-family: 'Baloo Da 2', sans-serif;">আপনার নাম *</label>
                                                <input type="text" class="form-control" name="name" id="reviewerName" placeholder="নাম লিখুন" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="reviewerEmail" class="form-label fw-bold text-dark small" style="font-family: 'Baloo Da 2', sans-serif;">ইমেইল এড্রেস</label>
                                                <input type="email" class="form-control" name="email" id="reviewerEmail" placeholder="ইমেইল লিখুন">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="reviewComment" class="form-label fw-bold text-dark small" style="font-family: 'Baloo Da 2', sans-serif;">মন্তব্য *</label>
                                            <textarea class="form-control" name="comment" id="reviewComment" rows="4" placeholder="বইটি সম্পর্কে আপনার সৎ মতামত লিখুন..." required minlength="5"></textarea>
                                        </div>
                                        
                                        <button type="submit" class="ht-btn-rokomari-orange border-0">রিভিউ জমা দিন</button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- User Reviews List -->
                            <div class="mt-5 border-top pt-4">
                                <h5 class="fw-bold mb-4 text-dark" style="font-family: 'Baloo Da 2', sans-serif;"><i class="far fa-comments me-2"></i> গ্রাহকদের রিভিউসমূহ ({{ $totalReviews }})</h5>
                                
                                <div class="d-flex flex-column gap-4">
                                    @forelse($reviews as $rev)
                                        <div class="d-flex gap-3 align-items-start p-3 bg-light rounded-3">
                                            <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; min-width: 48px; font-size: 20px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
                                                    <h6 class="fw-bold text-dark mb-0" style="font-family: 'Baloo Da 2', sans-serif;">{{ $rev->name }}</h6>
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
                                                <p class="mb-0 text-dark-emphasis small" style="font-family: 'Baloo Da 2', sans-serif; text-align: justify;">{{ $rev->comment }}</p>
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
                                        <!-- Price -->
                                        <div class="ht-rokomari-recent-price">
                                            @if($recent->price !== null && $recent->price !== '' && $recent->price != 0)
                                                ৳ {{ $recent->price }}
                                                @if($recent->old_price)
                                                    <span class="text-muted text-decoration-line-through fw-normal ms-1" style="font-size: 11px;">৳ {{ $recent->old_price }}</span>
                                                @endif
                                            @else
                                                ফ্রি (Free PDF)
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <p class="text-muted small mb-0 p-3 text-center" style="font-family: 'Baloo Da 2', sans-serif;">অন্য কোনো বই পাওয়া যায়নি।</p>
                            @endforelse
                        </div>

                        <div class="mt-4 pt-3 border-top border-light">
                            <a href="{{ route('books.index') }}" class="btn btn-outline-success btn-sm w-100 fw-bold py-2" style="font-family: 'Baloo Da 2', sans-serif; border-color: #38a169; color: #38a169;">
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
