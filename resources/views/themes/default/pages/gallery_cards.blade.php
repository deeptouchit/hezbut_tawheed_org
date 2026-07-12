@foreach($galleryPosts as $index => $post)
    @php
        $globalIndex = ($galleryPosts->currentPage() - 1) * $galleryPosts->perPage() + $index;
    @endphp
    <div class="gallery-item-card open-lightbox" data-index="{{ $globalIndex }}">
        <img src="{{ asset($post->image_path) }}" alt="{{ $post->title ?? '' }}" class="gallery-item-img" loading="lazy">
    </div>
@endforeach
