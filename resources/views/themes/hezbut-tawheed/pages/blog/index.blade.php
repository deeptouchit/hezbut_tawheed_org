@extends('theme::layouts.app')

@section('title', 'নিবন্ধ ও ব্লগ - হেজবুত তওহীদ')

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'নিবন্ধ ও ব্লগ',
        'subtitle' => 'হেযবুত তওহীদের আদর্শিক দৃষ্টিভঙ্গি, প্রবন্ধ, নিবন্ধ এবং সাম্প্রতিক ব্লগসমূহ',
        'badge_text' => 'আন্দোলনের প্রবন্ধ ও ব্লগ',
        'badge_icon' => 'fas fa-feather-alt'
    ])

    <!-- Blog Archive Main Section -->
    <div class="py-5" style="background-color: #f8fafc; min-height: 70vh;">
        <div class="container">
            <div class="row">
                
                <!-- Blog Listing Column (Left: col-lg-8) -->
                <div class="col-lg-8">
                    <!-- Search & Filter Summary -->
                    @if(request()->filled('search') || request()->filled('category') || request()->filled('tag'))
                        <div class="bg-white p-3 rounded-3 shadow-sm border border-light-grey mb-4 d-flex justify-content-between align-items-center" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.92rem;">
                            <span class="text-secondary">
                                ফিল্টারিং রেজাল্ট: 
                                @if(request()->filled('search'))
                                    সার্চ কীওয়ার্ড: <strong class="text-dark">"{{ request('search') }}"</strong>
                                @endif
                                @if(request()->filled('category'))
                                    ক্যাটাগরি: <strong class="text-success">"{{ request('category') }}"</strong>
                                @endif
                                @if(request()->filled('tag'))
                                    ট্যাগ: <strong class="text-primary">"#{{ request('tag') }}"</strong>
                                @endif
                            </span>
                            <a href="{{ route('blog') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-bold" style="font-size: 11px;">ক্লিয়ার করুন</a>
                        </div>
                    @endif

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
                                <h4 class="text-dark fw-bold" style="font-family: 'Baloo Da 2', sans-serif;">দুঃখিত, কোনো নিবন্ধ খুঁজে পাওয়া যায়নি!</h4>
                                <p class="text-secondary small">দয়া করে অন্য কোনো কীওয়ার্ড ব্যবহার করে সার্চ করুন বা সম্পূর্ণ সংবাদের লিস্ট দেখুন।</p>
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
                                @if(request()->filled('category'))
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif
                                @if(request()->filled('tag'))
                                    <input type="hidden" name="tag" value="{{ request('tag') }}">
                                @endif
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
                                @foreach($categories as $category)
                                    <a href="{{ route('blog', ['category' => $category->slug]) }}" 
                                       class="category-premium-row transition {{ request('category') == $category->slug ? 'active' : '' }}">
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
                                <div class="tag-cloud-grid" style="font-family: 'Baloo Da 2', sans-serif;">
                                    @foreach($allTags as $tag)
                                        <a href="{{ route('blog', ['tag' => $tag]) }}" class="premium-tag-pill transition {{ request('tag') == $tag ? 'active' : '' }}">
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
    

@endsection
