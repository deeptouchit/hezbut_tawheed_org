@extends('theme::layouts.app')

@section('title', 'সামাজিক উদ্যোগ - হেযবুত তওহীদ')

@push('styles')
<style>
    /* ============================================================
       HUMANITARIAN & SOCIAL INITIATIVES PAGE (DARK THEME CLEAN)
       ============================================================ */
    .soc-section {
        background: #0f172a;
        padding: 5rem 0 6rem;
    }

    .soc-header {
        max-width: 800px;
        margin: 0 auto 4rem;
        text-align: center;
    }

    .soc-title {
        font-size: 3rem;
        font-weight: 900;
        color: #f8fafc;
        letter-spacing: -0.025em;
        line-height: 1.2;
        margin-bottom: 1.25rem;
    }

    .soc-title .highlight {
        background: linear-gradient(135deg, #60a5fa, #3b82f6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .soc-subtitle {
        color: #94a3b8;
        font-size: 1.2rem;
        font-weight: 500;
        line-height: 1.6;
    }

    .activities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 2.5rem;
        margin-bottom: 4rem;
    }

    .activity-post-card {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 24px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .activity-post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
        border-color: #3b82f6;
    }

    .post-img-wrapper {
        aspect-ratio: 16/10;
        overflow: hidden;
        background: #0f172a;
        position: relative;
    }

    .post-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .activity-post-card:hover .post-img {
        transform: scale(1.03);
    }

    .post-card-body {
        padding: 2.5rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .post-meta {
        font-size: 0.95rem;
        font-weight: 600;
        color: #94a3b8;
        margin-bottom: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .post-card-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #f8fafc;
        line-height: 1.4;
        margin-bottom: 1.25rem;
        flex-grow: 1;
        transition: color 0.2s ease;
    }

    .activity-post-card:hover .post-card-title {
        color: #60a5fa;
    }

    .post-card-excerpt {
        color: #94a3b8;
        font-size: 1.02rem;
        line-height: 1.65;
        font-weight: 500;
        margin-bottom: 1.75rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .post-card-link {
        font-size: 0.98rem;
        font-weight: 700;
        color: #60a5fa;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: color 0.2s ease;
        text-decoration: none;
    }

    .activity-post-card:hover .post-card-link {
        color: #93c5fd;
    }

    /* Custom Pagination Styling (Dark Theme) */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
    }

    .pagination-wrapper .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .pagination-wrapper .page-item .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        border: 1px solid #334155;
        background: #1e293b;
        color: #f8fafc;
        font-weight: 700;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .pagination-wrapper .page-item.active .page-link {
        background: #3b82f6;
        color: #ffffff;
        border-color: #3b82f6;
    }

    .pagination-wrapper .page-item:hover:not(.active) .page-link {
        background: #334155;
        border-color: #475569;
    }

    /* Empty state */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 6rem 2rem;
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 24px;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 2rem;
        color: #475569;
    }

    /* Mobile Adaptability */
    @media (max-width: 767.98px) {
        .soc-title {
            font-size: 2.25rem;
        }
        .soc-section {
            padding: 4rem 0 5rem;
        }
        .activities-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        .initiative-card {
            padding: 2rem 1.75rem;
        }
        .post-card-body {
            padding: 2rem 1.5rem;
        }
    }
</style>
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
