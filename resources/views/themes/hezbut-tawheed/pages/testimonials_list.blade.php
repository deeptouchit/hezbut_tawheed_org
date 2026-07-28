@foreach ($testimonials as $testimonial)
    <div class="col-12">
        <div
            class="card border-0 shadow-sm rounded-3 p-4 bg-white border-light-grey position-relative hover-grow-card transition">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle overflow-hidden shadow-sm flex-shrink-0"
                    style="width: 55px; height: 55px; border: 2px solid #e2e8f0;">
                    <img src="{{ $testimonial->avatar_url }}" alt="{{ $testimonial->name }}"
                        class="w-100 h-100 object-cover">
                </div>
                <div class="ms-3">
                    <h6 class="fw-bold text-dark mb-0" style="font-size: 1.02rem;">{{ $testimonial->name }}</h6>
                    <span class="text-muted small d-block"
                        style="font-size: 0.82rem;">{{ $testimonial->designation }}{{ $testimonial->company ? ' | ' . $testimonial->company : '' }}</span>
                </div>
            </div>
            <div class="mb-3 rating-stars-row">
                {!! $testimonial->rating_stars !!}
            </div>
            <p class="text-secondary lh-lg mb-0" style="font-size: 0.92rem; text-align: justify;">
                "{{ $testimonial->content }}"
            </p>
        </div>
    </div>
@endforeach
