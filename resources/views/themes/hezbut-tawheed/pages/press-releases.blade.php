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
    <div class="py-5" style="background-color: #f8fafc; min-height: 70vh;">
        <div class="container">
            <div class="row">
                
                <!-- Blog Listing Column (Left: col-lg-8) -->
                <div class="col-lg-8">
                    <!-- Grid list of article cards -->
                    <div class="row g-4" id="blog-posts-container">
                        @forelse($blogs as $blog)
                            @include('theme::pages.blog.partials.blog_card', ['blog' => $blog])
                        @empty
                            <div class="col-12 text-center py-5 bg-white rounded-3 shadow-sm border border-light">
                                <div class="text-muted mb-3"><i class="far fa-sad-tear fa-3x text-success"></i></div>
                                <h4 class="text-dark fw-bold" style="font-family: 'Baloo Da 2', sans-serif;">দুঃখিত, কোনো প্রেস রিলিজ খুঁজে পাওয়া যায়নি!</h4>
                                <p class="text-secondary small">বর্তমানে কোনো প্রেস রিলিজ প্রকাশ করা হয়নি।</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Load More Button -->
                    @if($blogs->hasPages())
                        <div class="text-center mt-5" id="load-more-wrapper">
                            <button id="load-more-btn" data-next-page="2" class="btn btn-success rounded-3 fw-bold px-5 py-2.5 shadow-sm transition hover-grow-card" style="font-family: 'Baloo Da 2', sans-serif; font-size: 14px;">
                                আরও প্রেস রিলিজ লোড করুন <i class="fas fa-sync-alt ms-2" id="load-more-icon"></i>
                            </button>
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
    

@push('scripts')
<script>
$(document).ready(function() {
    $('#load-more-btn').on('click', function() {
        var btn = $(this);
        var page = btn.data('next-page');
        var icon = $('#load-more-icon');
        
        btn.prop('disabled', true);
        icon.addClass('fa-spin');
        
        var currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('page', page);
        
        $.ajax({
            url: currentUrl.toString(),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.html) {
                    $('#blog-posts-container').append(response.html);
                    
                    if (response.hasMore) {
                        btn.data('next-page', page + 1);
                        btn.prop('disabled', false);
                    } else {
                        $('#load-more-wrapper').fadeOut();
                    }
                } else {
                    $('#load-more-wrapper').fadeOut();
                }
            },
            error: function() {
                toastr.error('নিবন্ধ লোড করতে ব্যর্থ হয়েছে');
                btn.prop('disabled', false);
            },
            complete: function() {
                icon.removeClass('fa-spin');
            }
        });
    });
});
</script>
@endpush

@endsection
