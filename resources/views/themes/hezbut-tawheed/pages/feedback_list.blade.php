@foreach($feedbacks as $feedback)
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white border-light-grey position-relative hover-grow-card transition">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle overflow-hidden shadow-sm flex-shrink-0" style="width: 55px; height: 55px; border: 2px solid #e2e8f0;">
                    <img src="{{ $feedback->avatar_url }}" alt="{{ $feedback->name }}" class="w-100 h-100 object-cover">
                </div>
                <div class="ms-3">
                    <h6 class="fw-bold text-dark mb-0" style="font-size: 1.02rem;">{{ $feedback->name }}</h6>
                    <span class="text-muted small d-block" style="font-size: 0.82rem;">{{ $feedback->designation }}{{ $feedback->company ? ' | ' . $feedback->company : '' }}</span>
                </div>
            </div>
            <div class="mb-3 rating-stars-row">
                {!! $feedback->rating_stars !!}
            </div>
            <p class="text-secondary lh-lg mb-0" style="font-size: 0.92rem; text-align: justify;">
                "{{ $feedback->content }}"
            </p>
        </div>
    </div>
@endforeach
