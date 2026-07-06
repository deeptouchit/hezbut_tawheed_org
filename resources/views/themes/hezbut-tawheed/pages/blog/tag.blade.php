@extends('theme::layouts.app')

@section('title', 'ট্যাগ: #' . $tag . ' - হেজবুত তওহীদ')

@section('content')

    <!-- Classic Banner Header with Correct Brand Colors -->
    <div class="py-5 text-white position-relative" 
         style="background: linear-gradient(135deg, #006A4E 0%, #004D38 100%); border-bottom: 4px solid #10B981;">
        <div class="container text-center py-2">
            <h1 class="fw-bold mb-2 text-white" style="font-family: 'Baloo Da 2', sans-serif; font-size: 2.2rem;">
                <i class="fas fa-hashtag me-2" style="color: #34D399;"></i> {{ $tag }}
            </h1>
            <p class="lead small mb-0 opacity-90 text-white" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.05rem;">
                ট্যাগ: #{{ $tag }} এর অধীনে প্রকাশিত সকল নিবন্ধ ও প্রকাশনাসমূহ
            </p>
        </div>
    </div>

    <!-- Blog Archive Main Section -->
    <div class="py-5" style="background-color: #f8fafc; min-height: 70vh;">
        <div class="container">
            <div class="row">
                
                <!-- Blog Listing Column (Left: col-lg-8) -->
                <div class="col-lg-8">
                    <!-- Tag active filter notice -->
                    <div class="bg-white p-3 rounded-3 shadow-sm border border-light-grey mb-4 d-flex justify-content-between align-items-center" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.92rem;">
                        <span class="text-secondary">
                            বর্তমানে প্রদর্শিত হচ্ছে ট্যাগ: <strong class="text-primary">"#{{ $tag }}"</strong> এর নিবন্ধসমূহ
                        </span>
                        <a href="{{ route('blog') }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-bold" style="font-size: 11px;">সকল নিবন্ধ দেখুন</a>
                    </div>

                    <!-- Grid list of article cards -->
                    <div class="row g-4">
                        @forelse($blogs as $blog)
                            <div class="col-md-6 mb-2">
                                <article class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-grow-card transition d-flex flex-column justify-content-between">
                                    <div>
                                        <!-- Image Header -->
                                        <div class="position-relative overflow-hidden w-100" style="height: 180px; background-color: #f8fafc;">
                                            <img src="{{ $blog->featured_image_url }}" alt="{{ $blog->title }}" class="w-100 h-100 object-cover zoom-img-hover">
                                            <span class="position-absolute badge rounded px-3 py-2 fw-bold text-white bg-success-brand" 
                                                  style="font-size: 9px; right: 15px; top: 15px; z-index: 10; font-family: 'Baloo Da 2', sans-serif;">
                                                {{ $blog->category->name ?? 'নিবন্ধ' }}
                                            </span>
                                        </div>

                                        <!-- Card Body -->
                                        <div class="p-4">
                                            <!-- Date and views meta row -->
                                            <div class="d-flex align-items-center gap-3 mb-2 text-muted" style="font-size: 11px; font-family: 'Baloo Da 2', sans-serif;">
                                                <span><i class="far fa-calendar-alt text-success me-1"></i> {{ $blog->published_at ? $blog->published_at->format('d M, Y') : $blog->created_at->format('d M, Y') }}</span>
                                                <span><i class="far fa-eye text-success me-1"></i> {{ $blog->views }} ভিউ</span>
                                            </div>

                                            <h4 class="card-title fw-bold mb-3" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.15rem; line-height: 1.45; color: #1e293b !important;">
                                                <a href="{{ route('blog.detail', $blog->slug) }}" class="text-decoration-none text-dark hover-green-text transition">
                                                    {{ Str::limit($blog->title, 60) }}
                                                </a>
                                            </h4>

                                            <p class="card-text text-secondary mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.9rem; line-height: 1.6; text-align: justify;">
                                                {{ Str::limit(strip_tags($blog->content), 120) }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Footer reading action -->
                                    <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0">
                                        <a href="{{ route('blog.detail', $blog->slug) }}" class="text-success fw-bold text-decoration-none d-inline-flex align-items-center gap-1 hover-arrow-move" style="font-family: 'Baloo Da 2', sans-serif; font-size: 13px;">
                                            বিস্তারিত পড়ুন <i class="fas fa-arrow-right arrow-icon" style="font-size: 11px;"></i>
                                        </a>
                                    </div>
                                </article>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm border border-light">
                                <div class="text-muted mb-3"><i class="far fa-sad-tear fa-3x text-success"></i></div>
                                <h4 class="text-dark fw-bold" style="font-family: 'Baloo Da 2', sans-serif;">দুঃখিত, এই ট্যাগে কোনো নিবন্ধ পাওয়া যায়নি!</h4>
                                <p class="text-secondary small">অনুগ্রহ করে পরবর্তীতে আবার চেষ্টা করুন বা অন্যান্য নিবন্ধ ব্রাউজ করুন।</p>
                                <a href="{{ route('blog') }}" class="btn btn-success rounded-pill fw-bold px-4 py-2 mt-2" style="font-family: 'Baloo Da 2', sans-serif; font-size: 13px;">সকল নিবন্ধ</a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($blogs->hasPages())
                        <div class="pagination-wrapper mt-5 d-flex justify-content-center">
                            {{ $blogs->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>

                <!-- Sidebar Column (Right: col-lg-4) -->
                <div class="col-lg-4 mt-5 mt-lg-0">
                    <div class="ps-lg-2">
                        
                        <!-- Search Widget -->
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                            <h5 class="fw-bold text-dark mb-3 widget-title" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.05rem;">
                                অনুসন্ধান করুন
                            </h5>
                            <form action="{{ route('blog') }}" method="GET" class="search-form-sidebar">
                                <div class="input-group border rounded-pill bg-white overflow-hidden p-0.5" id="search-wrapper">
                                    <input type="text" name="search" class="form-control border-0 py-2 ps-3 pe-1" 
                                           placeholder="কীওয়ার্ড লিখুন..." value="{{ request('search') }}" style="font-size: 0.9rem; box-shadow: none; font-family: 'Baloo Da 2', sans-serif;">
                                    <button type="submit" class="btn btn-success rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0;"><i class="fas fa-search"></i></button>
                                </div>
                            </form>
                        </div>

                        <!-- Categories Widget -->
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                            <h5 class="fw-bold text-dark mb-3 widget-title" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.05rem;">
                                ক্যাটাগরি সমূহ
                            </h5>
                            <div class="d-flex flex-column gap-1.5" style="font-family: 'Baloo Da 2', sans-serif;">
                                @foreach($categories as $catItem)
                                    <a href="{{ route('blog.category', $catItem->slug) }}" 
                                       class="category-premium-row transition">
                                        <span class="d-flex align-items-center">
                                            <i class="far fa-folder folder-icon me-3"></i>
                                            <span class="cat-name">{{ $catItem->name }}</span>
                                        </span>
                                        <span class="badge category-count-badge">{{ $catItem->blogs_count ?? $catItem->blogs()->published()->count() }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Popular Posts Widget -->
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                            <h5 class="fw-bold text-dark mb-3 widget-title" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.05rem;">
                                জনপ্রিয় নিবন্ধ
                            </h5>
                            <ul class="list-unstyled mb-0" style="font-family: 'Baloo Da 2', sans-serif;">
                                @foreach($popularPosts as $popPost)
                                    <li class="d-flex align-items-start mb-3 pb-3 border-bottom border-light last-no-border">
                                        <div class="rounded overflow-hidden shadow-sm flex-shrink-0" style="width: 60px; height: 60px; background: #f8fafc;">
                                            <img src="{{ $popPost->featured_image_url }}" alt="{{ $popPost->title }}" class="w-100 h-100 object-cover">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.88rem; line-height: 1.4; color: #334155 !important;">
                                                <a href="{{ route('blog.detail', $popPost->slug) }}" class="text-decoration-none text-dark hover-green-text transition">{{ Str::limit($popPost->title, 45) }}</a>
                                            </h6>
                                            <span class="text-muted small d-block" style="font-size: 10px;"><i class="far fa-calendar-alt text-success opacity-70 me-1"></i> {{ $popPost->published_at ? $popPost->published_at->format('d M, Y') : $popPost->created_at->format('d M, Y') }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Custom CSS Styles -->
    <style>
        .border-light-grey {
            border: 1px solid #e2e8f0 !important;
        }
        .object-cover {
            object-fit: cover !important;
        }
        .bg-success-brand {
            background-color: #006A4E !important;
        }
        .category-premium-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            border-radius: 10px;
            color: #475569;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
        }
        .category-premium-row:hover, .category-premium-row.active {
            background-color: #E6F2EE;
            color: #006A4E;
        }
        .category-count-badge {
            background-color: #f1f5f9;
            color: #64748b;
            font-size: 11px;
            border-radius: 20px;
            padding: 4px 8px;
        }
        .category-premium-row:hover .category-count-badge, .category-premium-row.active .category-count-badge {
            background-color: #006A4E;
            color: white;
        }
        .premium-tag-pill {
            display: inline-block;
            padding: 5px 12px;
            background-color: #f1f5f9;
            color: #475569;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid #e2e8f0;
        }
        .premium-tag-pill:hover, .premium-tag-pill.active {
            background-color: #006A4E;
            color: white;
            border-color: #006A4E;
        }
        .zoom-img-hover {
            transition: transform 0.5s ease;
        }
        .hover-grow-card:hover .zoom-img-hover {
            transform: scale(1.06);
        }
        .hover-green-text:hover {
            color: #006A4E !important;
        }
    </style>
@endsection
