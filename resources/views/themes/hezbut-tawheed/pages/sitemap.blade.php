@extends('theme::layouts.app')

@section('title', 'সাইটম্যাপ - হেজবুত তওহীদ')
@section('meta_description', 'হেজবুত তওহীদ নিউজ পোর্টাল ও ব্লগ সাইটের সকল লিংক এবং সাইট ম্যাপ।')

@section('content')
    @include('theme::partials.hero_banner', [
        'title' => 'সাইটম্যাপ',
        'subtitle' => 'আমাদের ওয়েবসাইটের সকল পৃষ্ঠা, সংবাদ বিভাগ ও প্রধান সংবাদগুলোর তালিকা নিচে দেওয়া হলো।',
        'badge_text' => 'ওয়েবসাইট ম্যাপ',
        'badge_icon' => 'fas fa-sitemap'
    ])

<!-- Sitemap Content -->
<div class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Static Pages -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h4 class="fw-bold text-dark-green mb-4 pb-2 border-bottom">
                            <i class="fas fa-file-alt me-2 text-gold"></i>পৃষ্ঠাসমূহ
                        </h4>
                        <ul class="list-unstyled">
                            @foreach($staticPages as $page)
                                <li class="mb-3">
                                    <a href="{{ $page['url'] }}" class="text-decoration-none text-secondary hover-text-dark-green d-flex align-items-center">
                                        <i class="fas fa-chevron-right text-gold me-2" style="font-size: 0.8rem;"></i>
                                        {{ $page['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Blog Categories -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h4 class="fw-bold text-dark-green mb-4 pb-2 border-bottom">
                            <i class="fas fa-folder-open me-2 text-gold"></i>সংবাদ বিভাগসমূহ
                        </h4>
                        <ul class="list-unstyled">
                            @forelse($categories as $category)
                                <li class="mb-3">
                                    <a href="{{ route('blog.category', $category->slug) }}" class="text-decoration-none text-secondary hover-text-dark-green d-flex align-items-center">
                                        <i class="fas fa-folder text-gold me-2" style="font-size: 0.8rem;"></i>
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @empty
                                <li class="text-muted text-center py-3">কোন ক্যাটাগরি পাওয়া যায়নি</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Latest Blogs -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h4 class="fw-bold text-dark-green mb-4 pb-2 border-bottom">
                            <i class="fas fa-newspaper me-2 text-gold"></i>সর্বশেষ সংবাদ ও নিবন্ধ
                        </h4>
                        <ul class="list-unstyled">
                            @forelse($blogs as $blog)
                                <li class="mb-3">
                                    <a href="{{ route('blog.detail', $blog->slug) }}" class="text-decoration-none text-secondary hover-text-dark-green d-flex align-items-start">
                                        <i class="fas fa-caret-right text-gold mt-1 me-2" style="font-size: 0.8rem;"></i>
                                        <span>{{ Str::limit($blog->title, 45) }}</span>
                                    </a>
                                </li>
                            @empty
                                <li class="text-muted text-center py-3">কোন সংবাদ পাওয়া যায়নি</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
