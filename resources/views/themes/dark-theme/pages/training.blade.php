@extends('theme::layouts.app')

@section('title', 'প্রশিক্ষণ ও ক্রীড়া - হেযবুত তওহীদ')

@push('styles')
<style>
    /* ============================================================
       TRAINING & SPORTS PAGE DESIGN (DARK THEME)
       ============================================================ */
    .train-section {
        background: #0f172a;
        padding: 5rem 0 6rem;
    }

    .train-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
        margin-bottom: 5rem;
    }

    .train-content-left {
        max-width: 600px;
    }

    .train-tagline {
        font-size: 0.95rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #60a5fa;
        margin-bottom: 1rem;
        display: inline-block;
    }

    .train-main-title {
        font-size: 2.75rem;
        font-weight: 900;
        color: #f8fafc;
        line-height: 1.25;
        margin-bottom: 1.5rem;
        letter-spacing: -0.02em;
    }

    .train-main-title .highlight {
        background: linear-gradient(135deg, #60a5fa, #3b82f6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .train-lead-text {
        font-size: 1.15rem;
        color: #94a3b8;
        line-height: 1.75;
        font-weight: 500;
        margin-bottom: 2.5rem;
        text-align: justify;
    }

    /* Training Programs List */
    .program-item {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2.5rem;
        transition: all 0.3s ease;
    }

    .program-icon-box {
        flex-shrink: 0;
        width: 64px;
        height: 64px;
        border-radius: 18px;
        background: #1e1b4b;
        color: #60a5fa;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        transition: all 0.3s ease;
    }

    .program-item:hover .program-icon-box {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: #ffffff;
        transform: scale(1.05);
    }

    .program-details h3 {
        font-size: 1.35rem;
        font-weight: 800;
        color: #f8fafc;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .program-details p {
        color: #94a3b8;
        font-size: 1rem;
        line-height: 1.6;
        font-weight: 500;
        margin: 0;
        text-align: justify;
    }

    /* Right visual side */
    .train-visual-card {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 32px;
        padding: 3rem;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.2);
        position: relative;
        overflow: hidden;
    }

    .train-visual-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(90deg, #3b82f6, #1d4ed8);
    }

    .visual-stat-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-top: 2rem;
    }

    .stat-box {
        background: #0f172a;
        border: 1px solid #334155;
        border-radius: 20px;
        padding: 2rem 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-box:hover {
        border-color: #3b82f6;
        background: #1e293b;
        transform: translateY(-2px);
    }

    .stat-num {
        font-size: 2rem;
        font-weight: 900;
        color: #60a5fa;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.95rem;
        font-weight: 700;
        color: #94a3b8;
    }

    /* Dynamic Activities/Sports News Grid (Dark Theme) */
    .recent-section-title {
        font-size: 2.25rem;
        font-weight: 800;
        color: #f8fafc;
        margin-bottom: 3.5rem;
        text-align: center;
        position: relative;
    }

    .recent-section-title::after {
        content: '';
        position: absolute;
        bottom: -12px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: #3b82f6;
        border-radius: 2px;
    }

    .activities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 2.5rem;
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

    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 5rem 2rem;
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 24px;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 3.5rem;
        margin-bottom: 1.5rem;
        color: #475569;
    }

    /* Mobile Adaptability */
    @media (max-width: 991.98px) {
        .train-grid {
            grid-template-columns: 1fr;
            gap: 3rem;
        }
        .train-main-title {
            font-size: 2.25rem;
        }
        .train-section {
            padding: 4rem 0 5rem;
        }
        .train-visual-card {
            padding: 2rem;
        }
        .activities-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
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
        'subtitle' => $page->meta_description ?? 'সুশৃঙ্খল কর্মীবাহিনী গঠন ও যুবসমাজকে মাদকমুক্ত রাখতে আমাদের ক্রীড়া ও প্রশিক্ষণ কার্যক্রম।',
        'badge_text' => 'প্রশিক্ষণ ও ক্রীড়া',
        'badge_icon' => 'fas fa-running'
    ])

    <section class="train-section">
        <div class="container">
            
            <!-- Grid Intro -->
            <div class="train-grid">
                
                <!-- Left Details -->
                <div class="train-content-left">
                    <span class="train-tagline">যুবসমাজ ও কর্মীবাহিনীর দক্ষতা উন্নয়ন</span>
                    <h2 class="train-main-title">শারীরিক গঠন, <span class="highlight">অনুশাসন ও ক্রীড়া চর্চা</span></h2>
                    
                    <p class="train-lead-text">
                        তরুণ প্রজন্মকে মাদকেরাল গ্রাস ও বিভিন্ন অপসংস্কৃতি থেকে দূরে রেখে যোগ্য নাগরিক হিসেবে গড়ে তুলতে ক্রীড়া ও শারীরিক প্রশিক্ষণের বিকল্প নেই।
                    </p>

                    <!-- Programs List -->
                    <div class="programs-list">
                        
                        <!-- Program 1 -->
                        <div class="program-item">
                            <div class="program-icon-box">
                                <i class="fas fa-running"></i>
                            </div>
                            <div class="program-details">
                                <h3>শারীরিক গঠন ও সুশাসন</h3>
                                <p>আমাদের কর্মীবাহিনীকে সর্বদা সুশৃঙ্খল, কর্মক্ষম ও উদ্যমী রাখতে নিয়মিত শারীরিক চর্চা, পিটি ও প্যারেড প্রশিক্ষণের ব্যবস্থা করা হয়।</p>
                            </div>
                        </div>

                        <!-- Program 2 -->
                        <div class="program-item">
                            <div class="program-icon-box">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="program-details">
                                <h3>আত্মরক্ষামূলক প্রশিক্ষণ</h3>
                                <p>কর্মীদের আত্মবিশ্বাস বৃদ্ধি ও সুরক্ষায় আত্মরক্ষামূলক কৌশল এবং মার্শাল আর্টের বিশেষ প্রশিক্ষণের আয়োজন করা হয়।</p>
                            </div>
                        </div>

                        <!-- Program 3 -->
                        <div class="program-item">
                            <div class="program-icon-box">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="program-details">
                                <h3>ক্রীড়া টুর্নামেন্ট ও সামাজিক ব্যাধি প্রতিরোধ</h3>
                                <p>যুবসমাজকে অপরাধমূলক কাজ ও মাদক থেকে দূরে রাখতে ফুটবল, ভলিবল, ক্রিকেট ও অ্যাথলেটিকস সহ দেশব্যাপী ক্রীড়া টুর্নামেন্টের আয়োজন করা হয়।</p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right Visual Stats -->
                <div class="train-visual-card">
                    <h3 class="fw-bold mb-3" style="color: #f8fafc; font-size: 1.5rem;">ক্রীড়াবিদ্যা ও প্রশিক্ষণের মূল স্তম্ভ</h3>
                    <p class="text-secondary" style="font-size: 0.98rem; line-height: 1.6; font-weight: 500; color: #94a3b8 !important;">
                        সুস্থ শরীর ও সতেজ মনের সমন্বয়ে একটি সুন্দর ও সুশৃঙ্খল ভবিষ্যৎ প্রজন্ম গড়ে তোলাই আমাদের লক্ষ্য।
                    </p>

                    <div class="visual-stat-grid">
                        <div class="stat-box">
                            <div class="stat-num">১০০%</div>
                            <div class="stat-label">সুশৃঙ্খল কর্মী</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-num">দেশব্যাপী</div>
                            <div class="stat-label">ক্রীড়া ইভেন্ট</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-num">মাদকমুক্ত</div>
                            <div class="stat-label">যুবসমাজ</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-num">নিয়মিত</div>
                            <div class="stat-label">শারীরিক চর্চা</div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Dynamic Sports & Training Posts Section -->
            <div class="mt-5 pt-5 border-top">
                <h2 class="recent-section-title">ক্রীড়া ও প্রশিক্ষণ বিষয়ক সংবাদ</h2>
                
                <div class="activities-grid">
                    @forelse($trainingPosts as $post)
                        <div class="activity-post-card">
                            <div class="post-img-wrapper">
                                @if($post->featured_image)
                                    <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="post-img">
                                @else
                                    <img src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?q=80&w=600" alt="{{ $post->title }}" class="post-img">
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
                            <p class="mb-0 fw-semibold">বর্তমানে কোনো ক্রীড়া ও প্রশিক্ষণ বিষয়ক সংবাদ পাওয়া যায়নি।</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </section>

@endsection
