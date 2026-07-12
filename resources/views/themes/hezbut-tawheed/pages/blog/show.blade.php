@extends('theme::layouts.app')

@section('title', $blog->title . ' - হেজবুত তওহীদ')
@section('meta_description', Str::limit(strip_tags($blog->short_description ?? $blog->content), 150))

@section('seo_meta')
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:title" content="{{ $blog->meta_title ?? $blog->title }}">
    <meta property="og:description" content="{{ $blog->meta_description ?? Str::limit(strip_tags($blog->short_description ?? $blog->content), 150) }}">
    <meta property="og:image" content="{{ $blog->featured_image_url }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ request()->url() }}">
    <meta name="twitter:title" content="{{ $blog->meta_title ?? $blog->title }}">
    <meta name="twitter:description" content="{{ $blog->meta_description ?? Str::limit(strip_tags($blog->short_description ?? $blog->content), 150) }}">
    <meta name="twitter:image" content="{{ $blog->featured_image_url }}">

    @if($blog->meta_keywords)
        <meta name="keywords" content="{{ $blog->meta_keywords }}">
    @endif

    <!-- JSON-LD Article Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "NewsArticle",
      "headline": "{{ addslashes($blog->title) }}",
      "image": [
        "{{ $blog->featured_image_url }}"
      ],
      "datePublished": "{{ $blog->published_at ? $blog->published_at->toIso8601String() : $blog->created_at->toIso8601String() }}",
      "dateModified": "{{ $blog->updated_at->toIso8601String() }}",
      "author": {
        "@type": "Person",
        "name": "{{ $blog->author->name ?? 'হেজবুত তওহীদ অ্যাডমিন' }}"
      }
    }
    </script>
@endsection

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => $blog->title,
        'badge_text' => $blog->category->name ?? 'নিবন্ধ',
        'badge_icon' => 'far fa-newspaper',
        'extra_html' => '
            <div class="d-flex align-items-center justify-content-center gap-3 text-white opacity-75 small mt-3" style="font-family: \'Baloo Da 2\', sans-serif; font-size: 0.9rem;">
                <span><i class="far fa-calendar-alt me-1" style="color: #10B981;"></i> ' . ($blog->published_at ? $blog->published_at->format('d M, Y') : $blog->created_at->format('d M, Y')) . '</span>
                <span><i class="far fa-user me-1" style="color: #10B981;"></i> ' . ($blog->author->name ?? 'অ্যাডমিন') . '</span>
                <span><i class="far fa-eye me-1" style="color: #10B981;"></i> ' . $blog->views . ' ভিউ</span>
            </div>
        '
    ])

    <!-- Blog Details Main Area -->
    <div class="py-5" style="background-color: #f8fafc; min-height: 70vh;">
        <div class="container">
            <div class="row">
                
                <!-- Blog Content Column (Left: col-lg-8) -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4">
                        @if($blog->featured_image_url)
                            <div class="mb-4 rounded-4 overflow-hidden shadow-sm">
                                <img src="{{ $blog->featured_image_url }}" alt="{{ $blog->title }}" class="img-fluid w-100 object-cover" style="max-height: 400px;">
                            </div>
                        @endif

                        <!-- Blog Rich-Text Content -->
                        <div class="blog-detail-content text-dark lh-lg fs-5" style="font-family: 'Baloo Da 2', sans-serif; text-align: justify; font-size: 1.05rem !important;">
                            {!! $blog->content !!}
                        </div>

                        <!-- Tags Cloud -->
                        @if(is_array($blog->tags_array) && count($blog->tags_array) > 0)
                            <div class="mt-5 pt-4 border-top border-light">
                                <span class="fw-bold text-dark me-2" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.95rem;"><i class="fas fa-tags text-success opacity-75"></i> ট্যাগসমূহ:</span>
                                <div class="d-inline-flex flex-wrap gap-1.5">
                                    @foreach($blog->tags_array as $tag)
                                        <a href="{{ route('blog', ['tag' => $tag]) }}" class="premium-tag-pill transition">
                                            #{{ $tag }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Social Share -->
                        <div class="social-share mt-4 d-flex align-items-center flex-wrap gap-2" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.9rem;">
                            <span class="fw-bold text-dark me-2"><i class="fas fa-share-alt text-success opacity-75"></i> শেয়ার করুন:</span>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" class="btn btn-sm btn-primary rounded px-3 py-2" target="_blank"><i class="fab fa-facebook-f me-2"></i> ফেসবুক</a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->title) }}" class="btn btn-sm btn-info text-white rounded px-3 py-2" target="_blank"><i class="fab fa-twitter me-2"></i> টুইটার</a>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($blog->title . ' - ' . request()->url()) }}" class="btn btn-sm btn-success rounded px-3 py-2" target="_blank"><i class="fab fa-whatsapp me-2"></i> হোয়াটসঅ্যাপ</a>
                        </div>

                        <!-- Next / Previous Post Navigation -->
                        @php
                            $prevPost = $blog->getPreviousPost();
                            $nextPost = $blog->getNextPost();
                        @endphp
                        @if($prevPost || $nextPost)
                            <div class="next-prev-navigation d-flex justify-content-between align-items-center gap-3 mt-5 pt-4 border-top" style="font-family: 'Baloo Da 2', sans-serif;">
                                <div class="prev-post-link text-start w-50">
                                    @if($prevPost)
                                        <a href="{{ route('blog.detail', $prevPost->slug) }}" class="text-decoration-none text-secondary hover-green-text d-block">
                                            <span class="text-muted d-block small mb-1"><i class="fas fa-chevron-left me-2 nav-arrow-icon"></i> আগের পোস্ট</span>
                                            <strong class="text-dark d-block small" style="font-size: 0.9rem; line-height: 1.4;">{{ Str::limit($prevPost->title, 40) }}</strong>
                                        </a>
                                    @endif
                                </div>
                                <div class="next-post-link text-end w-50 border-start ps-3">
                                    @if($nextPost)
                                        <a href="{{ route('blog.detail', $nextPost->slug) }}" class="text-decoration-none text-secondary hover-green-text d-block">
                                            <span class="text-muted d-block small mb-1">পরের পোস্ট <i class="fas fa-chevron-right ms-2 nav-arrow-icon"></i></span>
                                            <strong class="text-dark d-block small" style="font-size: 0.9rem; line-height: 1.4;">{{ Str::limit($nextPost->title, 40) }}</strong>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Related News Area -->
                    @if(isset($relatedPosts) && $relatedPosts->count() > 0)
                        <div class="related-posts mb-5">
                            <h3 class="fw-bold text-dark mb-4 widget-title" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.25rem;">সম্পর্কিত নিবন্ধ</h3>
                            <div class="row g-4">
                                @foreach($relatedPosts->take(2) as $related)
                                    <div class="col-md-6">
                                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-grow-card transition bg-white" style="border: 1px solid #e2e8f0 !important;">
                                            <div class="position-relative overflow-hidden" style="height: 160px;">
                                                <img src="{{ $related->featured_image_url }}" alt="{{ $related->title }}" class="w-100 h-100 object-cover zoom-img-hover">
                                            </div>
                                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                                <h5 class="fw-bold mb-3" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1rem; line-height: 1.45;">
                                                    <a href="{{ route('blog.detail', $related->slug) }}" class="text-decoration-none text-dark hover-green-text transition">{{ Str::limit($related->title, 55) }}</a>
                                                </h5>
                                                <span class="text-muted small d-block" style="font-family: 'Baloo Da 2', sans-serif; font-size: 10px;"><i class="far fa-calendar-alt text-success me-1 opacity-70"></i> {{ $related->published_at ? $related->published_at->format('d M, Y') : $related->created_at->format('d M, Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Comments Section -->
                    <div class="bg-white rounded-4 p-4 p-md-5 shadow-sm mb-4 border border-light-grey">
                        <h3 class="fw-bold text-dark mb-4 widget-title" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.25rem;">মন্তব্য সমূহ ({{ $blog->comments->count() }})</h3>

                        <!-- Comments List -->
                        <div class="comments-list mb-5" style="font-family: 'Baloo Da 2', sans-serif;">
                            @forelse($blog->comments->where('parent_id', null)->where('status', 'approved') as $comment)
                                <div class="d-flex mb-4 pb-4 border-bottom border-light">
                                    <div class="rounded-circle overflow-hidden shadow-sm flex-shrink-0 bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border: 1px solid #e2e8f0;">
                                        <i class="fas fa-user-circle text-secondary fa-2x"></i>
                                    </div>
                                    <div class="w-100 ms-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 14.5px;">{{ $comment->name ?? $comment->user->name ?? 'বেনামী' }}</h6>
                                            <span class="text-muted small" style="font-size: 10.5px;"><i class="far fa-clock me-1 text-success opacity-75"></i> {{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-secondary mb-0 lh-base" style="font-size: 13.5px; text-align: justify;">
                                            {{ $comment->comment }}
                                        </p>

                                        <!-- Replies -->
                                        @foreach($comment->replies->where('status', 'approved') as $reply)
                                            <div class="d-flex mt-4 ps-4 border-start border-success border-2">
                                                <div class="rounded-circle overflow-hidden shadow-sm flex-shrink-0 bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border: 1px solid #e2e8f0;">
                                                    <i class="fas fa-user-circle text-secondary fa-lg"></i>
                                                </div>
                                                <div class="w-100 ms-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 13.5px;">{{ $reply->name ?? $reply->user->name ?? 'বেনামী' }}</h6>
                                                        <span class="text-muted small" style="font-size: 10px;"><i class="far fa-clock me-1 text-success opacity-75"></i> {{ $reply->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-secondary mb-0 lh-base" style="font-size: 13.5px; text-align: justify;">
                                                        {{ $reply->comment }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center py-3" style="font-size: 13.5px;">বর্তমানে কোনো মন্তব্য নেই। প্রথম মন্তব্যটি আপনিই করুন!</p>
                            @endforelse
                        </div>

                        <!-- Comment Submit Form -->
                        <div class="comment-form-wrapper" style="font-family: 'Baloo Da 2', sans-serif;">
                            <h4 class="fw-bold text-dark mb-4" style="font-size: 1.15rem;">আপনার মতামত দিন</h4>
                            <form action="{{ route('blog.comment', $blog->id) }}" method="POST">
                                @csrf
                                <div style="display: none;">
                                    <input type="text" name="website_url" value="">
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="comment_name" class="form-label text-dark fw-semibold small">আপনার নাম *</label>
                                            <input type="text" name="name" id="comment_name" class="form-control" placeholder="নাম..." required style="box-shadow: none;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="comment_email" class="form-label text-dark fw-semibold small">আপনার ইমেল *</label>
                                            <input type="email" name="email" id="comment_email" class="form-control" placeholder="ইমেল..." required style="box-shadow: none;">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="comment_text" class="form-label text-dark fw-semibold small">আপনার মন্তব্য *</label>
                                            <textarea name="comment" id="comment_text" rows="5" class="form-control" placeholder="মন্তব্য লিখুন..." required style="box-shadow: none;"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-success text-white fw-bold px-4 py-2 rounded shadow-sm">মন্তব্য জমা দিন</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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
                                       class="category-premium-row transition {{ $blog->category_id == $category->id ? 'active' : '' }}">
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

                        <!-- Recent Posts Widget -->
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                            <h5 class="fw-bold text-dark mb-3 widget-title" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.05rem;">
                                সাম্প্রতিক নিবন্ধ
                            </h5>
                            <ul class="list-unstyled mb-0" style="font-family: 'Baloo Da 2', sans-serif;">
                                @foreach($recentPosts as $recPost)
                                    <li class="d-flex align-items-start mb-3 pb-3 border-bottom border-light last-no-border">
                                        <div class="rounded overflow-hidden shadow-sm flex-shrink-0" style="width: 60px; height: 60px; background: #f8fafc;">
                                            <img src="{{ $recPost->featured_image_url }}" alt="{{ $recPost->title }}" class="w-100 h-100 object-cover">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.88rem; line-height: 1.4; color: #334155 !important;">
                                                <a href="{{ route('blog.detail', $recPost->slug) }}" class="text-decoration-none text-dark hover-green-text transition">{{ Str::limit($recPost->title, 45) }}</a>
                                            </h6>
                                            <span class="text-muted small d-block" style="font-size: 10px;"><i class="far fa-calendar-alt text-success opacity-70 me-1"></i> {{ $recPost->published_at ? $recPost->published_at->format('d M, Y') : $recPost->created_at->format('d M, Y') }}</span>
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
    

@endsection
