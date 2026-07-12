@extends('theme::layouts.app')

@section('title', 'গবেষণা ও উন্নয়ন - হেযবুত তওহীদ')

@push('styles')

@endpush

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => $page->title,
        'subtitle' => $page->meta_description ?? 'ভ্রান্ত অপব্যাখ্যার তাত্ত্বিক খণ্ডন এবং কৃষিতে বিপ্লবের মাধ্যমে খাদ্য নিরাপত্তা ও উন্নয়ন প্রকল্পসমূহ।',
        'badge_text' => 'গবেষণা ও উন্নয়ন',
        'badge_icon' => 'fas fa-microscope'
    ])

    <section class="research-section">
        <div class="container">

            <!-- Intro Section -->
            <div class="research-grid">
                <div class="research-content-left">
                    <span class="research-tagline">হেযবুত তওহীদ গবেষণা সেল</span>
                    <h2 class="research-main-title">গবেষণা ও <span class="highlight">উন্নয়ন উইং</span></h2>

                    <p class="research-lead-text">
                        হেযবুত তওহীদের অন্যতম মূল শক্তি হচ্ছে এর গবেষণামূলক কাজ। সমাজে সাম্প্রদায়িক অপশক্তি ছড়ানো ভুল ব্যাখ্যার তাত্ত্বিক জবাব তৈরি করা আমাদের গবেষণার অন্যতম মূল লক্ষ্য।
                    </p>

                    <p class="research-body-text">
                        আমাদের গবেষক দল ইসলামের আদি ইতিহাস, খেলাফতের মূল দর্শন এবং উগ্রবাদের কাউন্টার-ন্যারেটিভ বা গঠনমূলক বিশ্লেষণ ও গবেষণামূলক কাজ পরিচালনা করেন। এই গবেষণার ফসলগুলো বিভিন্ন বই, কলাম ও বুকলেটের মাধ্যমে সবার কাছে সহজে পৌঁছে দেওয়া হয়।
                    </p>
                </div>

                <div class="research-visual-card">
                    <h3 class="fw-bold mb-3" style="color: #0f172a; font-size: 1.4rem;">সত্যের আলোর সন্ধানে গবেষণা</h3>
                    <p class="text-secondary mb-4" style="font-size: 0.98rem; line-height: 1.6; font-weight: 500;">
                        ভুল ব্যাখ্যা নিরসন ও সঠিক জ্ঞান বিস্তারের মাধ্যমে একটি বৈষম্যহীন ও ভারসাম্যপূর্ণ সমাজ গঠন আমাদের অঙ্গীকার।
                    </p>
                    <ul class="list-unstyled p-0 m-0" style="font-weight: 600; color: #475569;">
                        <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> ইসলামের মূল চেতনা পুনরুজ্জীবন</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> চরমপন্থা ও জঙ্গিবাদের বুদ্ধিবৃত্তিক মোকাবেলা</li>
                        <li class="mb-0"><i class="fas fa-check-circle text-success me-2"></i> ভ্রান্ত অপপ্রচারের উপযুক্ত জবাব খণ্ডন</li>
                    </ul>
                </div>
            </div>

            <!-- Three Pillars Grid -->
            <div class="mb-6">
                <h2 class="pillars-section-title">গবেষণার মূল ক্ষেত্রসমূহ</h2>
                <div class="pillars-grid">

                    <!-- Card 1 -->
                    <div class="pillar-card">
                        <div class="pillar-icon-box">
                            <i class="fas fa-history"></i>
                        </div>
                        <h3>ইসলামের আদি ইতিহাস ও দর্শন</h3>
                        <p>ইসলামের আদি যুগের প্রকৃত ইতিহাস ও সমাজব্যবস্থার প্রকৃত রূপ গবেষণা করে সমসাময়িক যুগের সাথে তার সামঞ্জস্যপূর্ণ সমাধান বের করা।</p>
                    </div>

                    <!-- Card 2 -->
                    <div class="pillar-card">
                        <div class="pillar-icon-box">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>উগ্রবাদের বুদ্ধিবৃত্তিক মোকাবেলা</h3>
                        <p>উগ্রবাদ ও জঙ্গিবাদের পেছনের মনস্তত্ত্ব বিশ্লেষণ করে তাদের ধর্মের বিকৃত ব্যাখ্যার বিরুদ্ধে শক্তিশালী তাত্ত্বিক কাউন্টার-ন্যারেটিভ তৈরি করা।</p>
                    </div>

                    <!-- Card 3 -->
                    <div class="pillar-card">
                        <div class="pillar-icon-box">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <h3>অপপ্রচার ও অপব্যাখ্যা খণ্ডন</h3>
                        <p>ইসলামের নামে প্রচলিত বিভিন্ন সামাজিক কুসংস্কার ও কুপ্রথার তাত্ত্বিক খণ্ডন এবং আন্দোলনের বিরুদ্ধে মিথ্যা প্রোপাগান্ডার উপযুক্ত জবাব তৈরি।</p>
                    </div>

                </div>
            </div>

            <!-- ============================================================
                 DEVELOPMENT PROJECTS SECTION (উন্নয়ন প্রকল্পসমূহ)
                 ============================================================ -->
            <div class="projects-container pt-5 border-top">
                <h2 class="pillars-section-title">আমাদের উন্নয়ন প্রকল্পসমূহ</h2>

                <p class="text-center text-secondary mx-auto mb-5 max-w-800" style="font-size: 1.15rem; line-height: 1.75; font-weight: 500; max-width: 850px;">
                    বাংলাদেশের মতো একটি কৃষিপ্রধান দেশে প্রকৃত অর্থনৈতিক ক্ষমতায়ন ও খাদ্য নিরাপত্তা কৃষিক্ষেত্রে একটি বিপ্লবের মাধ্যমেই শুরু হতে হবে। এই বিপ্লব পতিত জমিকে সুযোগ ও আত্মনির্ভরশীলতার উর্বর ভূমিতে রূপান্তরিত করেছে।
                </p>

                <!-- Project 1: Agriculture -->
                <div class="project-card-row">
                    <div class="project-img-box">
                        <img src="{{ asset('uploads/projects/3.webp') }}" alt="বৈচিত্র্যময় কৃষির মাধ্যমে খাদ্য নিরাপত্তা">
                    </div>
                    <div class="project-details-box">
                        <span class="project-badge">কৃষি বিপ্লব ও খাদ্য নিরাপত্তা</span>
                        <h3 class="project-title">বৈচিত্র্যময় কৃষির মাধ্যমে খাদ্য নিরাপত্তা</h3>
                        <p class="project-desc">
                            প্রাথমিক উদ্দেশ্য হলো বিভিন্ন ধরনের নিত্যপ্রয়োজনীয় ফসল চাষের মাধ্যমে স্থানীয় স্বনির্ভরতা অর্জন করা এবং একটি স্থিতিশীল ও বৈচিত্র্যময় খাদ্য সরবরাহ নিশ্চিত করা।
                        </p>
                        <ul class="project-bullets-list">
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>ধান, ভুট্টা ও আলুর মতো প্রধান ফসলের ব্যাপক চাষাবাদ।</span>
                            </li>
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>বিভিন্ন মৌসুমি শাকসবজি ও ফলের ব্যাপক চাষাবাদ।</span>
                            </li>
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>স্বাস্থ্যকর খাদ্যাভ্যাসের প্রচার এবং অতিরিক্ত আয়ের উৎস তৈরি করা।</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Project 2: Fisheries -->
                <div class="project-card-row">
                    <div class="project-details-box order-1 order-lg-0">
                        <span class="project-badge">মৎস্য চাষ প্রকল্প</span>
                        <h3 class="project-title">বাণিজ্যিক মাছ চাষ</h3>
                        <p class="project-desc">
                            ২০ একর জুড়ে একটি বিশাল মাছের খামার স্থাপন করা হয়েছে, যা গ্রামের অব্যবহৃত পুকুরগুলোকে উৎপাদনশীল সম্পদে এবং স্থানীয় অর্থনীতির ভিত্তিপ্রস্তরে রূপান্তরিত করেছে।
                        </p>
                        <ul class="project-bullets-list">
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>উচ্চ ফলনশীল উৎপাদন, যেখানে নয়টি নতুন পুকুর থেকে প্রতিদিন দুই টন মাছ যোগ হচ্ছে।</span>
                            </li>
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>টেকসই প্রজননের জন্য একটি সরকার-স্বীকৃত পুরস্কৃত হ্যাচারি।</span>
                            </li>
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>স্থানীয় পুষ্টি পূরণ ও বাণিজ্যিক সাফল্যের একটি প্রধান উৎস।</span>
                            </li>
                        </ul>
                    </div>
                    <div class="project-img-box order-0 order-lg-1">
                        <img src="{{ asset('uploads/projects/6.webp') }}" alt="বাণিজ্যিক মাছ চাষ">
                    </div>
                </div>

                <!-- Project 3: Cattle -->
                <div class="project-card-row">
                    <div class="project-img-box">
                        <img src="{{ asset('uploads/projects/8.webp') }}" alt="আধুনিক গবাদি পশু পালন">
                    </div>
                    <div class="project-details-box">
                        <span class="project-badge">এমএম এগ্রো ফার্ম লিমিটেড</span>
                        <h3 class="project-title">আধুনিক গবাদি পশু পালন</h3>
                        <p class="project-desc">
                            উন্নত মানের প্রোটিনের স্থানীয় চাহিদা মেটাতে এমএম এগ্রো ফার্ম লিমিটেড-এর অধীনে সম্পূর্ণ নৈতিক ও জৈব পশু পালনের মডেল হিসেবে আধুনিক গবাদি পশুর খামার স্থাপন করা হয়েছে।
                        </p>
                        <ul class="project-bullets-list">
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>প্রতি বছর ৫০০-র বেশি গরু ক্ষতিকর স্টেরয়েড বা হরমোন ছাড়াই সম্পূর্ণ প্রাকৃতিকভাবে পালন করা হয়।</span>
                            </li>
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>২০ একর জমিতে উন্নত মানের উন্নত জাতের ঘাস চাষের মাধ্যমে সমর্থিত।</span>
                            </li>
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>খামারের গুণমান নিশ্চিত করতে এবং খাদ্য খরচ কমাতে এর নিজস্ব আধুনিক ফিড মিল রয়েছে।</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Project 4: Integrated Economy -->
                <div class="project-card-row">
                    <div class="project-details-box order-1 order-lg-0">
                        <span class="project-badge">কাররানি ফুডস ও সমন্বিত চক্র</span>
                        <h3 class="project-title">সমন্বিত অর্থনৈতিক চক্র ও স্বনির্ভরতা</h3>
                        <p class="project-desc">
                            এই কৃষি প্রকল্পগুলো অন্যান্য অর্থনৈতিক উদ্যোগের সাথে নির্বিঘ্নে সমন্বিত। উৎপাদিত ফসল ও পণ্য স্থানীয় জনগোষ্ঠী এবং “কাররানি ফুডস” প্রক্রিয়াজাতকরণ শিল্পে সরবরাহ করা হয়, যা একটি সমন্বিত অর্থনৈতিক চক্র তৈরি করে।
                        </p>
                        <ul class="project-bullets-list">
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>খামারগুলো থেকে উৎপাদিত পণ্য “কারানি ফুডস”-এর প্রক্রিয়াজাতকরণ শিল্পে সরাসরি সরবরাহ করা হয়।</span>
                            </li>
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>গরুগুলো সম্পূর্ণ জৈব পদ্ধতিতে পালন করা হয়, যা বাজারের জন্য শতভাগ স্বাস্থ্যকর ও নিরাপদ মাংসের সরবরাহ নিশ্চিত করে।</span>
                            </li>
                            <li class="project-bullet-item">
                                <i class="fas fa-check-circle"></i>
                                <span>শত শত বেকার যুবকের কর্মসংস্থান সৃষ্টি করে স্বনির্ভর ভবিষ্যৎ গড়ে তোলা।</span>
                            </li>
                        </ul>
                    </div>
                    <div class="project-img-box order-0 order-lg-1">
                        <img src="{{ asset('uploads/projects/7.webp') }}" alt="সমন্বিত অর্থনৈতিক চক্র ও স্বনির্ভরতা">
                    </div>
                </div>

            </div>

            <!-- Research Outcomes: Books -->
            @if(isset($researchBooks) && count($researchBooks) > 0)
                <div class="pt-5 border-top">
                    <h2 class="pillars-section-title">গবেষণামূলক মূল প্রকাশনাসমূহ</h2>
                    <div class="books-shelf-grid">
                        @foreach($researchBooks as $book)
                            <div class="book-item-card">
                                <img src="{{ $book->image_url }}" alt="{{ $book->title }}" class="book-cover-img">
                                <h3 class="book-title-text">{{ $book->title }}</h3>
                                <p class="book-writer-text">{{ $book->writer ?? 'হেযবুত তওহীদ' }}</p>
                                <a href="{{ route('library.read', $book->slug) }}" class="book-btn-link">
                                    পড়তে শুরু করুন <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Research Articles Section -->
            <div class="mt-5 pt-5 border-top">
                <h2 class="pillars-section-title">গবেষণামূলক নিবন্ধ ও প্রবন্ধ</h2>

                <div class="articles-grid">
                    @forelse($researchPosts as $post)
                        <div class="article-post-card">
                            <div class="post-img-wrapper">
                                @if($post->featured_image)
                                    <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="post-img">
                                @else
                                    <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?q=80&w=600" alt="{{ $post->title }}" class="post-img">
                                @endif
                            </div>

                            <div class="post-card-body">
                                <div class="post-meta">
                                    <i class="far fa-calendar-alt"></i>
                                    <span>{{ $post->published_at ? $post->published_at->format('d M, Y') : $post->created_at->format('d M, Y') }}</span>
                                </div>
                                <h3 class="post-card-title">{{ $post->title }}</h3>
                                <p class="post-card-excerpt">{{ $post->short_description ?? Str::limit(strip_tags($post->content), 120) }}</p>
                                <a href="{{ route('blog.detail', $post->slug) }}" class="post-card-link">
                                    বিস্তারিত পড়ুন <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-newspaper"></i>
                            <p class="mb-0 fw-semibold">বর্তমানে কোনো গবেষণা প্রবন্ধ পাওয়া যায়নি।</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </section>

@endsection
