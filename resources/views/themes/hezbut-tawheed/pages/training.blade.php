@extends('theme::layouts.app')

@section('title', 'প্রশিক্ষণ ও ক্রীড়া - হেযবুত তওহীদ')

@push('styles')

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
                    <h3 class="fw-bold mb-3" style="color: #0f172a; font-size: 1.5rem;">ক্রীড়াবিদ্যা ও প্রশিক্ষণের মূল স্তম্ভ</h3>
                    <p class="text-secondary" style="font-size: 0.98rem; line-height: 1.6; font-weight: 500;">
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
