<div class="col-12 mb-4">
    <article class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white p-4 p-md-5">
        <!-- Live Badge & Metadata -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5" style="font-size: 0.82rem; font-family: 'Baloo Da 2', sans-serif;">
                    <span class="spinner-grow spinner-grow-sm text-white" role="status" style="width: 8px; height: 8px;"></span>
                    লাইভ আপডেট
                </span>
                <span class="text-secondary small fw-medium" style="font-family: 'Baloo Da 2', sans-serif;">
                    <i class="far fa-calendar-alt me-1 text-danger"></i>
                    {{ $blog->published_at ? $blog->published_at->format('d M, Y - h:i A') : $blog->created_at->format('d M, Y - h:i A') }}
                </span>
            </div>
            <div>
                <span class="text-muted small" style="font-family: 'Baloo Da 2', sans-serif;">
                    <i class="far fa-eye me-1"></i> {{ $blog->views }} ভিউ
                </span>
            </div>
        </div>

        <!-- Title -->
        <h2 class="card-title fw-bold text-dark mb-4" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.55rem; line-height: 1.45; color: #0f172a !important;">
            <a href="{{ route('blog.detail', $blog->slug) }}" class="text-decoration-none text-dark hover-red-text transition">
                {{ $blog->title }}
            </a>
        </h2>

        <!-- Featured Image -->
        @if(!empty($blog->featured_image))
            <div class="rounded-3 overflow-hidden mb-4 shadow-sm" style="max-height: 480px; background-color: #f8fafc;">
                <img src="{{ $blog->featured_image_url }}" alt="{{ $blog->title }}" class="w-100 h-100 object-cover">
            </div>
        @endif

        <!-- Full Article Content -->
        <div class="blog-full-content text-dark mb-4" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.05rem; line-height: 1.85; text-align: justify; color: #334155 !important;">
            {!! $blog->formatted_content !!}
        </div>

        <!-- Footer Share / Direct link -->
        <div class="d-flex align-items-center justify-content-between pt-3 border-top" style="font-family: 'Baloo Da 2', sans-serif;">
            <span class="text-muted small">
                প্রকাশক: <strong class="text-dark">{{ $blog->author->name ?? 'এডমিন' }}</strong>
            </span>
            <a href="{{ route('blog.detail', $blog->slug) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-bold d-inline-flex align-items-center gap-1" style="font-size: 0.85rem;">
                <span>স্থায়ী লিংক</span> <i class="fas fa-arrow-right ms-1" style="font-size: 11px;"></i>
            </a>
        </div>
    </article>
</div>
