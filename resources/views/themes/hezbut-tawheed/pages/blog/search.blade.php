@extends('theme::layouts.app')

@section('title', (!empty($query) ? '"' . $query . '" - অনুসন্ধানের ফলাফল' : 'অনুসন্ধানের ফলাফল') . ' | হেযবুত তওহীদ')

@section('content')
    <!-- Search Banner Section (Production Grade UI) -->
    <section class="py-6 text-white position-relative overflow-hidden" 
        style="background: linear-gradient(135deg, #005840 0%, #003B2B 100%) !important; padding-top: 60px; padding-bottom: 70px;">
        <!-- Soft Ambient Background Glows -->
        <div class="position-absolute rounded-circle"
            style="width: 450px; height: 450px; background: radial-gradient(circle, rgba(16, 185, 129, 0.25) 0%, rgba(0, 0, 0, 0) 70%); top: -100px; right: -50px; filter: blur(60px); pointer-events: none;">
        </div>
        <div class="position-absolute rounded-circle"
            style="width: 350px; height: 350px; background: radial-gradient(circle, rgba(251, 191, 36, 0.15) 0%, rgba(0, 0, 0, 0) 70%); bottom: -80px; left: -50px; filter: blur(50px); pointer-events: none;">
        </div>

        <div class="container position-relative" style="z-index: 2;">
            <!-- Breadcrumbs -->
            <div class="d-flex align-items-center justify-content-center gap-2 mb-3 small opacity-85" style="font-family: 'Hind Siliguri', sans-serif;">
                <a href="{{ route('home') }}" class="text-white text-decoration-none hover-underline"><i class="fas fa-home me-1"></i> মূল পাতা</a>
                <span>/</span>
                <span class="text-warning fw-semibold">অনুসন্ধান</span>
            </div>

            <div class="row align-items-center justify-content-center text-center">
                <div class="col-lg-9 col-xl-8">
                    <!-- Glassmorphic Subtitle Badge -->
                    <div class="mb-3">
                        <span class="px-3.5 py-1.5 rounded-pill fw-bold text-white shadow-sm d-inline-flex align-items-center gap-2"
                            style="font-size: 12px; background: rgba(255, 255, 255, 0.12); backdrop-filter: blur(10px); border: 1.5px solid rgba(255, 255, 255, 0.25); font-family: 'Hind Siliguri', sans-serif;">
                            <i class="fas fa-search text-warning" style="font-size: 11px;"></i> ডিজিটাল আর্কাইভ অনুসন্ধান
                        </span>
                    </div>

                    <!-- Main Heading -->
                    <h1 class="fw-bold mb-3 text-white" 
                        style="font-family: 'Baloo Da 2', sans-serif; font-size: 2.5rem; font-weight: 800; line-height: 1.3; text-shadow: 0 2px 12px rgba(0,0,0,0.3);">
                        @if(!empty($query))
                            "<span style="color: #fbbf24; text-shadow: 0 0 20px rgba(251, 191, 36, 0.4);">{{ $query }}</span>" এর অনুসন্ধানের ফলাফল
                        @else
                            সকল বিষয়বস্তু অনুসন্ধান
                        @endif
                    </h1>

                    <!-- Results Count Badge -->
                    <p class="mb-4 text-white opacity-90" style="font-family: 'Hind Siliguri', sans-serif; font-size: 15.5px; font-weight: 500;">
                        সর্বমোট <span class="badge bg-warning text-dark px-3 py-1 fw-bold shadow-sm" style="font-size: 14px; border-radius: 20px;">{{ str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $blogs->total()) }}</span> টি প্রকাশিত কন্টেন্ট পাওয়া গেছে
                    </p>

                    <!-- World-class Search Box -->
                    <div class="search-hero-box-wrapper max-w-2xl mx-auto mb-4">
                        <form action="{{ route('blog.search') }}" method="GET" 
                            class="d-flex align-items-center bg-white rounded-pill p-1.5 shadow-lg border"
                            style="box-shadow: 0 20px 40px rgba(0,0,0,0.25) !important; border-color: rgba(255,255,255,0.4) !important;">
                            <div class="ps-3 pe-2 text-muted">
                                <i class="fas fa-search fs-5" style="color: #006A4E;"></i>
                            </div>
                            <input type="text" name="q" class="form-control border-0 shadow-none px-2 py-2.5 text-dark fw-medium" 
                                placeholder="ক্যাটাগরি, সংবাদ বা যেকোনো বিষয়বস্তু লিখে অনুসন্ধান করুন..." 
                                value="{{ $query }}" style="font-family: 'Hind Siliguri', sans-serif; font-size: 15.5px; background: transparent;">
                            @if(!empty($categoryId))
                                <input type="hidden" name="category_id" value="{{ $categoryId }}">
                            @endif
                            <button type="submit" class="btn px-4 py-2.5 text-white fw-bold rounded-pill shadow-sm d-flex align-items-center gap-2" 
                                style="background: linear-gradient(135deg, #006A4E 0%, #004D38 100%); border: none; font-family: 'Hind Siliguri', sans-serif; font-size: 14.5px; flex-shrink: 0; transition: all 0.3s ease;">
                                <span>অনুসন্ধান</span> <i class="fas fa-arrow-right" style="font-size: 12px;"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Quick Category Suggestion Chips -->
                    <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 pt-1" style="font-family: 'Hind Siliguri', sans-serif;">
                        <span class="small opacity-75 text-white me-1"><i class="fas fa-tags me-1"></i> পপুলার সার্চ:</span>
                        <a href="{{ route('blog.search', ['q' => 'ইফতার মাহফিল']) }}" class="badge bg-white text-dark text-decoration-none px-3 py-1.5 rounded-pill shadow-xs hover-scale" style="font-size: 12px; font-weight: 600;"># ইফতার মাহফিল</a>
                        <a href="{{ route('blog.search', ['q' => 'সভা সমাবেশ']) }}" class="badge bg-white text-dark text-decoration-none px-3 py-1.5 rounded-pill shadow-xs hover-scale" style="font-size: 12px; font-weight: 600;"># সভা সমাবেশ</a>
                        <a href="{{ route('blog.search', ['q' => 'সংবাদ সম্মেলন']) }}" class="badge bg-white text-dark text-decoration-none px-3 py-1.5 rounded-pill shadow-xs hover-scale" style="font-size: 12px; font-weight: 600;"># সংবাদ সম্মেলন</a>
                        <a href="{{ route('blog.search', ['q' => 'সমাজসেবা']) }}" class="badge bg-white text-dark text-decoration-none px-3 py-1.5 rounded-pill shadow-xs hover-scale" style="font-size: 12px; font-weight: 600;"># সমাজসেবা</a>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Search Results Content Section -->
    <section class="py-5" style="background-color: #f8fafc;">
        <div class="container">
            <!-- Filter & Sorting Bar -->
            <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
                <form action="{{ route('blog.search') }}" method="GET" class="row g-3 align-items-center">
                    <input type="hidden" name="q" value="{{ $query }}">
                    
                    <!-- Category Select -->
                    <div class="col-md-5 col-12">
                        <label class="form-label small fw-bold text-muted mb-1" style="font-family: 'Hind Siliguri', sans-serif;">
                            <i class="fas fa-folder me-1" style="color: #006A4E;"></i> ক্যাটাগরি ফিল্টার:
                        </label>
                        <select name="category_id" class="form-select rounded-3 border-light shadow-xs" 
                            onchange="this.form.submit()" style="font-family: 'Hind Siliguri', sans-serif; font-size: 14px;">
                            <option value="">-- সকল ক্যাটাগরি --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }} ({{ $cat->blogs_count ?? $cat->blogs()->count() }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort Select -->
                    <div class="col-md-5 col-12">
                        <label class="form-label small fw-bold text-muted mb-1" style="font-family: 'Hind Siliguri', sans-serif;">
                            <i class="fas fa-sort-amount-down me-1" style="color: #006A4E;"></i> ক্রমানুসারে সাজান:
                        </label>
                        <select name="sort" class="form-select rounded-3 border-light shadow-xs" 
                            onchange="this.form.submit()" style="font-family: 'Hind Siliguri', sans-serif; font-size: 14px;">
                            <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>সর্বশেষ প্রকাশিত</option>
                            <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>প্রাচীনতম</option>
                            <option value="popular" {{ $sort == 'popular' ? 'selected' : '' }}>সর্বাধিক পঠিত (জনপ্রিয়)</option>
                        </select>
                    </div>

                    <!-- Reset Button -->
                    <div class="col-md-2 col-12 text-md-end text-center pt-md-4">
                        <a href="{{ route('blog.search', ['q' => $query]) }}" class="btn btn-outline-secondary btn-sm w-100 rounded-3" style="font-family: 'Hind Siliguri', sans-serif;">
                            <i class="fas fa-sync-alt me-1"></i> রিসেট
                        </a>
                    </div>
                </form>
            </div>

            <!-- Blog Grid -->
            @if($blogs->count() > 0)
                <div class="row g-4">
                    @foreach($blogs as $blog)
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card h-100 bg-white border-0 shadow-sm rounded-4 overflow-hidden d-flex flex-column hover-shadow transition-all"
                                style="border-top: 4px solid #006A4E !important;">
                                <!-- Image & Category Badge -->
                                <div class="position-relative overflow-hidden" style="aspect-ratio: 16/10;">
                                    <img src="{{ asset($blog->featured_image_url) }}" alt="{{ $blog->title }}" 
                                        class="w-100 h-100 object-fit-cover">
                                    @if($blog->category)
                                        <span class="position-absolute px-3 py-1 rounded-pill text-white fw-bold shadow-sm"
                                            style="background: #006A4E; font-size: 0.75rem; font-family: 'Hind Siliguri', sans-serif; top: 12px; left: 12px;">
                                            {{ $blog->category->name }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Card Body -->
                                <div class="p-4 d-flex flex-column flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 text-muted small mb-2" style="font-family: 'Hind Siliguri', sans-serif;">
                                        <i class="far fa-calendar-alt" style="color: #006A4E;"></i>
                                        {{ $blog->published_at ? $blog->published_at->format('d M, Y') : $blog->created_at->format('d M, Y') }}
                                    </div>

                                    <h5 class="fw-bold mb-3" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.2rem; line-height: 1.4; color: #0f172a;">
                                        <a href="{{ route('blog.detail', $blog->slug) }}" class="text-decoration-none text-dark hover-green">
                                            {{ $blog->title }}
                                        </a>
                                    </h5>

                                    <p class="text-muted small mb-4 flex-grow-1" style="font-family: 'Hind Siliguri', sans-serif; line-height: 1.6;">
                                        {!! Str::limit(strip_tags($blog->content), 125) !!}
                                    </p>

                                    <!-- CTA Button matching Leader Cards button UI -->
                                    <div class="mt-auto">
                                        <a href="{{ route('blog.detail', $blog->slug) }}" 
                                            class="btn text-white w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" 
                                            style="background-color: #006A4E; border-color: #006A4E; color: white !important; border-radius: 5px; font-family: 'Hind Siliguri', sans-serif; font-size: 13.5px; padding: 9px 16px;">
                                            বিস্তারিত পড়ুন <i class="fas fa-arrow-right" style="font-size: 11px;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $blogs->links('pagination::bootstrap-5') }}
                </div>
            @else
                <!-- No Results State -->
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-4">
                    <div class="mb-3">
                        <i class="fas fa-search fa-3x opacity-40 text-secondary"></i>
                    </div>
                    <h4 class="fw-bold mb-2" style="font-family: 'Baloo Da 2', sans-serif; color: #0f172a;">কোনো কন্টেন্ট পাওয়া যায়নি!</h4>
                    <p class="text-muted mb-4" style="font-family: 'Hind Siliguri', sans-serif;">
                        আপনার অনুসন্ধানকৃত "<strong class="text-dark">{{ $query }}</strong>" সম্পর্কিত কোনো নিবন্ধ বা প্রতিবেদন খুঁজে পাওয়া যায়নি। দয়া করে অন্য কোনো শব্দ বা ক্যাটাগরি লিখে আবার চেষ্টা করুন।
                    </p>
                    <div>
                        <a href="{{ route('blog') }}" class="btn text-white px-4 py-2.5 fw-bold rounded-3 shadow-sm" 
                            style="background-color: #006A4E; font-family: 'Hind Siliguri', sans-serif;">
                            <i class="fas fa-th-large me-2"></i> সকল পোস্ট দেখুন
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
