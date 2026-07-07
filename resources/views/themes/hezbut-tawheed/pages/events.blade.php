@extends('theme::layouts.app')

@section('title', 'সাম্প্রতিক ইভেন্ট ও অনুষ্ঠানসমূহ - হেজবুত তওহীদ')

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'সাম্প্রতিক ইভেন্ট ও অনুষ্ঠানসমূহ',
        'subtitle' => 'সারাদেশে আয়োজিত হেযবুত তওহীদের জনসভা, আলোচনা সভা, সম্মেলন ও র‍্যালির খবর',
        'badge_text' => 'কর্মসূচী ও ইভেন্টস',
        'badge_icon' => 'fas fa-calendar-check'
    ])

    <!-- Blog Archive Main Section -->
    <div class="py-5" style="background-color: #f8fafc; min-height: 70vh;">
        <div class="container">
            <div class="row">
                
                <!-- Blog Listing Column (Left: col-lg-8) -->
                <div class="col-lg-8">
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
                                                {{ $blog->category->name ?? 'ইভেন্ট' }}
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
                                <h4 class="text-dark fw-bold" style="font-family: 'Baloo Da 2', sans-serif;">দুঃখিত, কোনো ইভেন্ট বা অনুষ্ঠান খুঁজে পাওয়া যায়নি!</h4>
                                <p class="text-secondary small">বর্তমানে কোনো ইভেন্ট বা অনুষ্ঠান আপডেট করা হয়নি।</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($blogs->hasPages())
                        <div class="pagination-wrapper mt-5 d-flex justify-content-center">
                            {{ $blogs->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>

                <!-- Sidebar Column (Right: col-lg-4) -->
                <div class="col-lg-4 mt-5 mt-lg-0">
                    <div class="ps-lg-2">
                        
                        <!-- Categories Widget -->
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                            <h5 class="fw-bold text-dark mb-3 widget-title" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.05rem;">
                                ক্যাটাগরি সমূহ
                            </h5>
                            <div class="d-flex flex-column gap-1.5" style="font-family: 'Baloo Da 2', sans-serif;">
                                @foreach($categories as $category)
                                    <a href="{{ route('blog', ['category' => $category->slug]) }}" 
                                       class="category-premium-row transition">
                                        <span class="d-flex align-items-center">
                                            <i class="far fa-folder folder-icon me-3"></i>
                                            <span class="cat-name">{{ $category->name }}</span>
                                        </span>
                                        <span class="badge category-count-badge">{{ $category->blogs_count ?? $category->blogs()->published()->count() }}</span>
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

                        <!-- Tags Widget -->
                        @if(count($allTags) > 0)
                            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                                <h5 class="fw-bold text-dark mb-3 widget-title" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.05rem;">
                                    জনপ্রিয় ট্যাগ
                                </h5>
                                <div class="tag-cloud d-flex flex-wrap gap-1.5" style="font-family: 'Baloo Da 2', sans-serif;">
                                    @foreach($allTags as $tag)
                                        <a href="{{ route('blog', ['tag' => $tag]) }}" class="premium-tag-pill transition">
                                            #{{ $tag }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
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
            object-fit: cover;
        }
        .last-no-border:last-child {
            border-bottom: 0 !important;
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
        
        /* Brand Colors */
        .bg-success-brand {
            background-color: #006A4E !important;
        }
        .hover-green-text:hover {
            color: #006A4E !important;
        }
        
        /* Card transitions */
        .hover-grow-card {
            border: 1px solid #e2e8f0 !important;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .hover-grow-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 106, 78, 0.05) !important;
            border-color: rgba(16, 185, 129, 0.2) !important;
        }
        .zoom-img-hover {
            transition: transform 0.5s ease;
        }
        .hover-grow-card:hover .zoom-img-hover {
            transform: scale(1.05);
        }
        
        /* Sidebar widget accents */
        .widget-title {
            position: relative;
            padding-bottom: 8px;
            border-bottom: 2px solid #f1f5f9;
            color: #0f172a !important;
        }
        .widget-title::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 35px;
            height: 2px;
            background-color: #006A4E;
        }
        
        /* Premium Category List Rows */
        .category-premium-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            border-radius: 8px;
            color: #475569;
            text-decoration: none !important;
            font-size: 0.92rem;
            font-weight: 500;
            background-color: transparent;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .category-premium-row .folder-icon {
            color: #006A4E;
            opacity: 0.5;
            transition: transform 0.25s;
        }
        .category-premium-row .category-count-badge {
            background-color: #f1f5f9;
            color: #64748b;
            font-size: 10.5px;
            padding: 4px 10px;
            border-radius: 50px;
            border: 1px solid #e2e8f0;
            transition: all 0.25s;
        }
        .category-premium-row:hover {
            background-color: #f1f5f9;
            color: #006A4E;
            transform: translateX(4px);
        }
        .category-premium-row:hover .folder-icon {
            transform: scale(1.15);
            opacity: 0.9;
        }
        .category-premium-row:hover .category-count-badge {
            background-color: rgba(0, 106, 78, 0.1);
            color: #006A4E;
            border-color: transparent;
        }
        .category-premium-row.active {
            background-color: rgba(0, 106, 78, 0.05);
            color: #006A4E;
            font-weight: 700;
            border-left: 4px solid #006A4E;
            padding-left: 10px;
        }
        .category-premium-row.active .folder-icon {
            opacity: 1;
        }
        .category-premium-row.active .category-count-badge {
            background-color: #006A4E;
            color: #ffffff;
            border-color: transparent;
        }
        
        /* Premium Minimal Tag Cloud Pill */
        .premium-tag-pill {
            display: inline-flex;
            align-items: center;
            background: #f1f5f9;
            color: #475569;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 4px;
            text-decoration: none !important;
            transition: all 0.2s ease;
        }
        .premium-tag-pill:hover {
            background: #006A4E;
            color: #ffffff;
        }
        
        /* Read more arrow transition */
        .hover-arrow-move:hover .arrow-icon {
            transform: translateX(4px);
        }
        .arrow-icon {
            transition: transform 0.2s ease;
        }

        /* Fix Pagination Large SVGs */
        .pagination-wrapper svg {
            width: 16px !important;
            height: 16px !important;
            display: inline-block;
            vertical-align: middle;
        }
    </style>

@endsection
