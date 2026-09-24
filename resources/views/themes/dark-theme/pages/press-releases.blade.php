@extends('theme::layouts.app')

@section('title', 'প্রেস রিলিজ - হেজবুত তওহীদ')

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'প্রেস রিলিজ',
        'subtitle' => 'হেযবুত তওহীদের নীতিগত অবস্থান, বিবৃতি ও বিভিন্ন গুরুত্বপূর্ণ প্রেস রিলিজসমূহ',
        'badge_text' => 'অফিশিয়াল প্রেস রিলিজ',
        'badge_icon' => 'fas fa-bullhorn'
    ])

    <!-- Blog Archive Main Section -->
    <div class="py-5" style="background-color: #0f172a; min-height: 70vh;">
        <div class="container">
            <div class="row">
                
                <!-- Blog Listing Column (Left: col-lg-8) -->
                <div class="col-lg-8">
                    <!-- Grid list of article cards -->
                    <div class="row g-4">
                        @forelse($blogs as $blog)
                            <div class="col-md-6 mb-2">
                                <article class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-grow-card transition d-flex flex-column justify-content-between" style="background-color: #1e293b; border: 1px solid #334155 !important;">
                                    <div>
                                        <!-- Image Header -->
                                        <div class="position-relative overflow-hidden w-100" style="height: 180px; background-color: #0f172a;">
                                            <img src="{{ $blog->featured_image_url }}" alt="{{ $blog->title }}" class="w-100 h-100 object-cover zoom-img-hover">
                                            <span class="position-absolute badge rounded px-3 py-2 fw-bold text-white bg-danger-brand" 
                                                  style="font-size: 9px; right: 15px; top: 15px; z-index: 10; font-family: 'Baloo Da 2', sans-serif; background-color: #ef4444;">
                                                প্রেস রিলিজ
                                            </span>
                                        </div>

                                        <!-- Card Body -->
                                        <div class="p-4">
                                            <!-- Date and views meta row -->
                                            <div class="d-flex align-items-center gap-3 mb-2 text-slate-400" style="font-size: 11px; font-family: 'Baloo Da 2', sans-serif; color: #94a3b8;">
                                                <span><i class="far fa-calendar-alt text-danger me-1" style="color: #ef4444;"></i> {{ $blog->published_at ? $blog->published_at->format('d M, Y') : $blog->created_at->format('d M, Y') }}</span>
                                                <span><i class="far fa-eye text-danger me-1" style="color: #ef4444;"></i> {{ $blog->views }} ভিউ</span>
                                            </div>

                                            <h4 class="card-title fw-bold mb-3" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.15rem; line-height: 1.45; color: #f8fafc !important;">
                                                <a href="{{ route('blog.detail', $blog->slug) }}" class="text-decoration-none hover-danger-text transition" style="color: #f8fafc;">
                                                    {{ Str::limit($blog->title, 60) }}
                                                </a>
                                            </h4>

                                            <p class="card-text mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.9rem; line-height: 1.6; text-align: justify; color: #cbd5e1;">
                                                {{ Str::limit(strip_tags($blog->content), 120) }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Footer reading action -->
                                    <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0">
                                        <a href="{{ route('blog.detail', $blog->slug) }}" class="fw-bold text-decoration-none d-inline-flex align-items-center gap-1 hover-arrow-move" style="font-family: 'Baloo Da 2', sans-serif; font-size: 13px; color: #ef4444;">
                                            বিস্তারিত পড়ুন <i class="fas fa-arrow-right arrow-icon" style="font-size: 11px;"></i>
                                        </a>
                                    </div>
                                </article>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 rounded-4 shadow-sm border" style="background-color: #1e293b; border-color: #334155 !important;">
                                <div class="text-muted mb-3"><i class="far fa-sad-tear fa-3x text-danger"></i></div>
                                <h4 class="text-white fw-bold" style="font-family: 'Baloo Da 2', sans-serif;">দুঃখিত, কোনো প্রেস রিলিজ খুঁজে পাওয়া যায়নি!</h4>
                                <p class="small" style="color: #cbd5e1;">বর্তমানে কোনো প্রেস রিলিজ প্রকাশ করা হয়নি।</p>
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
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background-color: #1e293b; border: 1px solid #334155 !important;">
                            <h5 class="fw-bold text-white mb-3 widget-title" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.05rem;">
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
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background-color: #1e293b; border: 1px solid #334155 !important;">
                            <h5 class="fw-bold text-white mb-3 widget-title" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.05rem;">
                                জনপ্রিয় নিবন্ধ
                            </h5>
                            <ul class="list-unstyled mb-0" style="font-family: 'Baloo Da 2', sans-serif;">
                                @foreach($popularPosts as $popPost)
                                    <li class="d-flex align-items-start mb-3 pb-3 last-no-border" style="border-bottom: 1px solid #334155;">
                                        <div class="rounded overflow-hidden shadow-sm flex-shrink-0" style="width: 60px; height: 60px; background: #0f172a;">
                                            <img src="{{ $popPost->featured_image_url }}" alt="{{ $popPost->title }}" class="w-100 h-100 object-cover">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fw-bold mb-1" style="font-size: 0.88rem; line-height: 1.4; color: #f8fafc !important;">
                                                <a href="{{ route('blog.detail', $popPost->slug) }}" class="text-decoration-none hover-danger-text transition" style="color: #cbd5e1;">{{ Str::limit($popPost->title, 45) }}</a>
                                            </h6>
                                            <span class="small d-block" style="font-size: 10px; color: #94a3b8;"><i class="far fa-calendar-alt text-danger opacity-70 me-1" style="color: #ef4444;"></i> {{ $popPost->published_at ? $popPost->published_at->format('d M, Y') : $popPost->created_at->format('d M, Y') }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Tags Widget -->
                        @if(count($allTags) > 0)
                            <div class="card border-0 shadow-sm rounded-4 p-4" style="background-color: #1e293b; border: 1px solid #334155 !important;">
                                <h5 class="fw-bold text-white mb-3 widget-title" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.05rem;">
                                    জনপ্রিয় ট্যাগ
                                </h5>
                                <div class="tag-cloud-grid" style="font-family: 'Baloo Da 2', sans-serif;">
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
        .tag-cloud-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .object-cover {
            object-fit: cover;
        }
        .last-no-border:last-child {
            border-bottom: 0 !important;
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
        
        .hover-danger-text:hover {
            color: #ef4444 !important;
        }
        
        /* Card transitions */
        .hover-grow-card {
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .hover-grow-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(239, 68, 68, 0.1) !important;
            border-color: rgba(239, 68, 68, 0.4) !important;
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
            border-bottom: 2px solid #334155;
            color: #f8fafc !important;
        }
        .widget-title::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 35px;
            height: 2px;
            background-color: #ef4444;
        }
        
        /* Premium Category List Rows */
        .category-premium-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            border-radius: 8px;
            color: #cbd5e1;
            text-decoration: none !important;
            font-size: 0.92rem;
            font-weight: 500;
            background-color: transparent;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .category-premium-row .folder-icon {
            color: #ef4444;
            opacity: 0.7;
            transition: transform 0.25s;
        }
        .category-premium-row .category-count-badge {
            background-color: #0f172a;
            color: #cbd5e1;
            font-size: 10.5px;
            padding: 4px 10px;
            border-radius: 50px;
            border: 1px solid #334155;
            transition: all 0.25s;
        }
        .category-premium-row:hover {
            background-color: #0f172a;
            color: #ef4444;
            transform: translateX(4px);
        }
        .category-premium-row:hover .folder-icon {
            transform: scale(1.15);
            opacity: 1;
        }
        .category-premium-row:hover .category-count-badge {
            background-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border-color: transparent;
        }
        .category-premium-row.active {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            font-weight: 700;
            border-left: 4px solid #ef4444;
            padding-left: 10px;
        }
        .category-premium-row.active .folder-icon {
            opacity: 1;
        }
        .category-premium-row.active .category-count-badge {
            background-color: #ef4444;
            color: #ffffff;
            border-color: transparent;
        }
        
        /* Premium Minimal Tag Cloud Pill */
        .premium-tag-pill {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            text-align: left;
            background: #0f172a;
            color: #cbd5e1;
            font-size: 11px;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none !important;
            border: 1px solid #334155;
            transition: all 0.2s ease;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .premium-tag-pill:hover, .premium-tag-pill.active {
            background: #ef4444;
            color: #ffffff;
            border-color: transparent;
        }
        
        /* Read more arrow transition */
        .hover-arrow-move:hover .arrow-icon {
            transform: translateX(4px);
        }
        .arrow-icon {
            transition: transform 0.2s ease;
        }
    </style>

@endsection
