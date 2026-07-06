@extends('theme::layouts.app')

@section('title', $leader->name . ' - হেযবুত তওহীদ')
@section('meta_description', $leader->name . ' (' . $leader->designation . ') এর বিস্তারিত প্রোফাইল, জীবনবৃত্তান্ত, বাণী ও কর্মকাণ্ডের আর্কাইভ')

@section('content')

    <!-- Profile Page Container -->
    <section class="py-5 bg-off-white" style="background-color: #f4f6f8; min-height: 80vh;">
        <div class="container">
            
            <!-- Breadcrumbs -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="/" class="text-success text-decoration-none"><i class="fas fa-home"></i> হোম</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('leadership.index') }}" class="text-success text-decoration-none">নেতৃত্ব পরিচিতি</a></li>
                    <li class="breadcrumb-item active text-muted" aria-current="page">{{ $leader->name }}</li>
                </ol>
            </nav>

            <div class="row g-4">
                
                <!-- Left Sidebar: Profile Details -->
                <div class="col-lg-4 col-md-5">
                    
                    <!-- Profile Card -->
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden text-center bg-white p-4 mb-4" style="border-top: 4px solid #006A4E !important;">
                        <div class="mx-auto overflow-hidden rounded-circle shadow border border-3 border-light mb-3" style="width: 180px; height: 180px;">
                            <img src="{{ $leader->image_url }}" alt="{{ $leader->name }}" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                        
                        <h4 class="fw-bold text-dark-green mb-1" style="font-family: 'Baloo Da 2', sans-serif;">{{ $leader->name }}</h4>
                        <p class="text-muted small mb-3" style="font-family: 'Baloo Da 2', sans-serif;">{{ $leader->designation }}</p>
                        
                        @if($leader->signature_image)
                            <div class="my-3 border-top border-bottom border-light py-2 text-center">
                                <span class="d-block small text-muted mb-1" style="font-family: 'Baloo Da 2', sans-serif; font-size: 10px;">ডিজিটাল স্বাক্ষর</span>
                                <img src="{{ $leader->signature_url }}" alt="Signature" class="bg-white p-1 rounded" style="max-height: 50px; object-fit: contain;">
                            </div>
                        @endif

                        <!-- Social Contacts -->
                        <div class="d-flex justify-content-center gap-2.5 mt-3">
                            @if($leader->facebook_url)
                                <a href="{{ $leader->facebook_url }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            @endif
                            @if($leader->twitter_url)
                                <a href="{{ $leader->twitter_url }}" target="_blank" class="btn btn-outline-info btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" title="Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            @endif
                            @if($leader->linkedin_url)
                                <a href="{{ $leader->linkedin_url }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" title="LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            @endif
                            @if($leader->email)
                                <a href="mailto:{{ $leader->email }}" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" title="Email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Small Info Box -->
                    <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                        <h6 class="fw-bold text-dark-green mb-3 border-bottom pb-2" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-bullhorn me-1"></i> শান্তির বার্তা</h6>
                        <p class="text-muted small mb-0" style="font-family: 'Baloo Da 2', sans-serif; line-height: 1.6; text-align: justify;">
                            হেযবুত তওহীদের সাথে যুক্ত হয়ে আমরা ধর্মীয় উগ্রবাদ ও সাম্প্রদায়িকতার অবসান ঘটিয়ে সমাজে ঐক্য ও প্রকৃত শান্তির বার্তা পৌঁছে দিতে কাজ করে চলেছি।
                        </p>
                    </div>

                </div>

                <!-- Right Content Area: Bio & Publications -->
                <div class="col-lg-8 col-md-7">
                    
                    <!-- Quote Box -->
                    @if($leader->quote)
                        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4" style="border-left: 6px solid #f59e0b !important;">
                            <div class="position-relative ps-4">
                                <i class="fas fa-quote-left text-warning opacity-20 position-absolute" style="font-size: 2.5rem; top: -15px; left: 10px;"></i>
                                <blockquote class="blockquote mb-0 position-relative" style="z-index: 1;">
                                    <p class="fst-italic text-dark fw-semibold" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.05rem; line-height: 1.6;">
                                        "{{ $leader->quote }}"
                                    </p>
                                </blockquote>
                            </div>
                        </div>
                    @endif

                    <!-- Biography details -->
                    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
                        <h5 class="fw-bold text-dark-green mb-3 border-bottom pb-2" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-history me-1 text-gold"></i> জীবনবৃত্তান্ত ও পরিচিতি</h5>
                        <div class="text-muted" style="font-family: 'Baloo Da 2', sans-serif; line-height: 1.8; text-align: justify; font-size: 0.95rem; white-space: pre-line;">
                            {{ $leader->bio ?? 'পরিচিতি এখনও যোগ করা হয়নি।' }}
                        </div>
                    </div>

                    <!-- Video Speech Embed Player -->
                    @if($leader->speech_video_url)
                        @php
                            $videoUrl = $leader->speech_video_url;
                            if (str_contains($videoUrl, 'youtube.com/watch') || str_contains($videoUrl, 'youtu.be/')) {
                                $regExp = '/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/';
                                if (preg_match($regExp, $videoUrl, $match) && strlen($match[2]) == 11) {
                                    $videoUrl = "https://www.youtube.com/embed/" . $match[2];
                                }
                            }
                        @endphp
                        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
                            <h5 class="fw-bold text-dark-green mb-3 border-bottom pb-2" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fab fa-youtube me-1 text-danger"></i> সরাসরি বার্তা / খুতবা</h5>
                            <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-sm bg-black">
                                <iframe src="{{ $videoUrl }}" title="Speech Video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                    @endif

                    <!-- Authored Books shelf Grid -->
                    @if($books->count() > 0)
                        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                            <h5 class="fw-bold text-dark-green mb-3 border-bottom pb-2" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-book me-1 text-gold"></i> রচিত গ্রন্থাবলী</h5>
                            
                            <div class="row g-3">
                                @foreach($books as $book)
                                    <div class="col-md-6 col-12">
                                        <div class="p-3 border rounded-3 bg-light d-flex align-items-center gap-3 transition hover-border-success">
                                            <div class="overflow-hidden rounded shadow-sm bg-white" style="width: 50px; height: 75px;">
                                                <img src="{{ $book->image_url }}" alt="{{ $book->title }}" class="w-100 h-100" style="object-fit: cover;">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold text-dark-green mb-1 text-truncate" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.95rem;">
                                                    {{ $book->title }}
                                                </h6>
                                                <p class="text-muted small mb-2 text-truncate" style="font-size: 11px;">লেখক: {{ $book->writer }}</p>
                                                <a href="{{ route('library.read', $book->slug) }}" class="btn btn-sm btn-success rounded-pill px-3 py-1 fw-bold" style="font-size: 11px;">
                                                    <i class="fas fa-book-open me-1"></i> পড়ুন
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </section>

    <!-- Custom CSS Styles -->
    <style>
        .text-dark-green {
            color: #006A4E !important;
        }
        .gap-2.5 {
            gap: 0.65rem !important;
        }
        .hover-border-success {
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .hover-border-success:hover {
            border-color: #10b981 !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        }
    </style>

@endsection


