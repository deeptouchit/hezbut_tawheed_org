@extends('theme::layouts.app')

@section('title', $book->title . ' - হেযবুত তওহীদ')

@if(!empty($book->description))
    @section('meta_description', $book->description)
@endif

@section('content')

    <!-- Banner Header -->
    <div class="py-5 text-white position-relative" style="background: linear-gradient(rgba(0,106,78,0.85), rgba(0,106,78,0.85)), url('https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?q=80&w=1200') no-repeat center center; background-size: cover; border-bottom: 4px solid #10B981;">
        <div class="container py-4 text-center">
            <h1 class="display-5 fw-bold mb-0 text-shadow text-white">{{ $book->title }}</h1>
        </div>
    </div>

    <!-- Detail View Section -->
    <section class="py-6 bg-off-white">
        <div class="container">
            <div class="row">
                <!-- Left: Main Book Content -->
                <div class="col-lg-8 mb-5 mb-lg-0">
                    <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4 bg-white" style="border-top: 5px solid #006A4E !important;">
                        
                        <div class="row g-4 mb-4 pb-4 border-bottom border-light">
                            <!-- Cover Thumbnail -->
                            <div class="col-md-4 text-center">
                                <img src="{{ $book->image_url }}" alt="{{ $book->title }}" class="img-fluid rounded shadow-sm" style="max-height: 320px; object-fit: contain;">
                            </div>
                            <!-- Core details -->
                            <div class="col-md-8 d-flex flex-column justify-content-between">
                                <div>
                                    <h3 class="fw-bold text-dark-green mb-2" style="font-family: 'Baloo Da 2', sans-serif;">{{ $book->title }}</h3>
                                    
                                    @if($book->writer)
                                        <div class="text-muted fw-bold mb-3" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1rem;">
                                            <i class="fas fa-pen-nib me-1"></i> লেখক: {{ $book->writer }}
                                        </div>
                                    @endif

                                    @php
                                        $discountText = '';
                                        $cleanPrice = preg_replace('/[^\d]/', '', $book->price);
                                        $cleanOldPrice = preg_replace('/[^\d]/', '', $book->old_price);
                                        if ($cleanOldPrice && $cleanPrice && intval($cleanOldPrice) > intval($cleanPrice)) {
                                            $discount = round(((intval($cleanOldPrice) - intval($cleanPrice)) / intval($cleanOldPrice)) * 100);
                                            $discountText = $discount . '% ছাড়';
                                        }
                                    @endphp

                                    <div class="d-flex align-items-center gap-3 mb-4">
                                        @if($book->price !== null && $book->price !== '')
                                            <span class="fs-4 fw-bold text-success" style="font-family: 'Baloo Da 2', sans-serif;">মূল্য: ৳{{ $book->price }}</span>
                                            @if($book->old_price)
                                                <span class="text-muted text-decoration-line-through fs-5">৳{{ $book->old_price }}</span>
                                                <span class="badge bg-danger text-white px-3 py-2 rounded-pill fs-7">{{ $discountText }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">ফ্রি সংস্করণ / PDF</span>
                                        @endif
                                    </div>
                                    
                                    @if($book->description)
                                        <p class="text-muted lh-lg small" style="text-align: justify; font-family: 'Baloo Da 2', sans-serif;">
                                            {{ $book->description }}
                                        </p>
                                    @endif
                                </div>

                                <div class="mt-4 d-flex flex-wrap gap-2.5">
                                    @if($book->pdf_url)
                                        <a href="{{ $book->pdf_url }}" target="_blank" class="btn btn-danger btn-sm px-4 rounded-pill fw-bold">
                                            <i class="fas fa-file-pdf me-1"></i> পিডিএফ ডাউনলোড করুন (Free PDF)
                                        </a>
                                    @endif
                                    <a href="{{ route('page.show', 'publications') }}" class="btn btn-outline-secondary btn-sm px-4 rounded-pill fw-bold">
                                        <i class="fas fa-arrow-left me-1"></i> তালিকা দেখুন
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Full Content Body / Introduction -->
                        @if($book->content)
                            <h5 class="fw-bold text-dark-green mb-3" style="font-family: 'Baloo Da 2', sans-serif;">ভূমিকা ও বিস্তারিত কন্টেন্ট:</h5>
                            <article class="book-body text-start text-dark lh-lg" style="font-family: 'Baloo Da 2', sans-serif;">
                                {!! $book->content !!}
                            </article>
                        @endif
                    </div>
                </div>

                <!-- Right: Recent Books Sidebar -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm p-4 rounded-4 bg-white" style="position: sticky; top: 90px; border-top: 5px solid #10B981 !important;">
                        <h5 class="fw-bold text-dark-green mb-4 pb-2 border-bottom border-light">অন্যান্য প্রকাশনা</h5>
                        
                        <div class="d-flex flex-column gap-3">
                            @forelse($recentBooks as $recent)
                                <a href="{{ route('books.show', $recent->slug) }}" class="text-decoration-none d-flex align-items-center gap-3 p-2 rounded hover-bg-light transition">
                                    <img src="{{ $recent->image_url }}" alt="{{ $recent->title }}" class="rounded object-fit-contain" style="width: 50px; height: 70px; min-width: 50px; background: #f8f9fa;">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1 small lh-base" style="font-family: 'Baloo Da 2', sans-serif;">{{ Str::limit($recent->title, 55) }}</h6>
                                        <span class="text-muted small" style="font-size: 0.75rem;">{{ $recent->created_at->format('d M, Y') }}</span>
                                    </div>
                                </a>
                            @empty
                                <p class="text-muted small mb-0">অন্য কোনো বই পাওয়া যায়নি।</p>
                            @endforelse
                        </div>

                        <div class="mt-4 pt-3 border-top border-light">
                            <a href="{{ route('page.show', 'publications') }}" class="btn btn-outline-dark-green btn-sm w-100 fw-bold rounded-pill">
                                সব বই দেখুন <i class="fas fa-list ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Custom CSS for book content formatting -->
    <style>
        .book-body h1, .book-body h2, .book-body h3, .book-body h4, .book-body h5, .book-body h6 {
            color: #006A4E;
            font-weight: 700;
            margin-top: 1.8rem;
            margin-bottom: 1rem;
        }
        .book-body p {
            margin-bottom: 1.25rem;
            color: #475569;
            text-align: justify;
        }
        .book-body ul, .book-body ol {
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
            color: #475569;
        }
        .book-body li {
            margin-bottom: 0.5rem;
        }
        .book-body blockquote {
            border-left: 4px solid #10B981;
            background-color: #f8fafc;
            padding: 1rem 1.5rem;
            margin: 1.5rem 0;
            font-style: italic;
            border-radius: 0 8px 8px 0;
        }
        .hover-bg-light:hover {
            background-color: #f8fafc;
        }
        .gap-2.5 {
            gap: 0.65rem !important;
        }
    </style>

@endsection


