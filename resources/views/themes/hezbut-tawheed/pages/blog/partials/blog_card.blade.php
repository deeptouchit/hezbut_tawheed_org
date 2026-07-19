<div class="col-md-6 mb-2">
    <article class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden bg-white hover-grow-card transition d-flex flex-column justify-content-between">
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
