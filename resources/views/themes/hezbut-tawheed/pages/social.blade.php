@extends('theme::layouts.app')

@section('title', 'সামাজিক উদ্যোগ - হেযবুত তওহীদ')

@push('styles')

@endpush

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => $page->title,
        'subtitle' => $page->meta_description ?? 'সন্ত্রাসবাদ ও চরমপন্থা বিরোধী সচেতনতা তৈরির পাশাপাশি হেযবুত তওহীদের নানামুখী সামাজিক ও মানবিক উদ্যোগ।',
        'badge_text' => 'সামাজিক উদ্যোগ',
        'badge_icon' => 'fas fa-hands-helping'
    ])

    <section class="soc-section">
        <div class="container">
            
            <div class="soc-header">
                <h1 class="soc-title">আমাদের <span class="highlight">সামাজিক ও সেবামূলক কার্যক্রমসমূহ</span></h1>
                <p class="soc-subtitle">
                    সমাজ ও দেশের উন্নয়নে হেযবুত তওহীদের বিভিন্ন শাখার রক্তদান কর্মসূচি, শীতবস্ত্র ও ত্রাণ বিতরণ এবং পরিবেশ রক্ষায় বৃক্ষরোপণ কর্মসূচির সচিত্র প্রতিবেদন।
                </p>
            </div>

            <!-- Dynamic Activities Grid -->
            <div class="activities-grid">
                @forelse($activitiesPosts as $post)
                    <div class="activity-post-card">
                        <div class="post-img-wrapper">
                            @if($post->featured_image)
                                <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="post-img">
                            @else
                                <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=600" alt="{{ $post->title }}" class="post-img">
                            @endif
                        </div>
                        
                        <div class="post-card-body">
                            <div class="post-meta">
                                <i class="far fa-calendar-alt"></i>
                                <span>{{ $post->published_at ? $post->published_at->format('d M, Y') : $post->created_at->format('d M, Y') }}</span>
                            </div>
                            <h3 class="post-card-title">{{ $post->title }}</h3>
                            <p class="post-card-excerpt">{{ $post->short_description ?? Str::limit(strip_tags($post->content), 140) }}</p>
                            <a href="{{ route('blog.detail', $post->slug) }}" class="post-card-link">
                                বিস্তারিত পড়ুন <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-newspaper"></i>
                        <p class="mb-0 fw-semibold fs-5">বর্তমানে কোনো সামাজিক কার্যক্রমের প্রতিবেদন পাওয়া যায়নি।</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($activitiesPosts->hasPages())
                <div class="pagination-wrapper">
                    {{ $activitiesPosts->links('pagination::bootstrap-4') }}
                </div>
            @endif

        </div>
    </section>

@endsection
