@extends('theme::layouts.app')

@section('title', 'সরাসরি সম্প্রচার - হেযবুত তওহীদ')
@section('meta_description', 'হেযবুত তওহীদ আন্দোলনের জুমার খুতবা, আলোচনা সভা এবং বিভিন্ন সচেতনতামূলক অনুষ্ঠানের সরাসরি সম্প্রচার ও পূর্ববর্তী আর্কাইভ')

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'সরাসরি সম্প্রচার',
        'subtitle' => 'হেযবুত তওহীদের নীতি ও সাম্প্রতিক কার্যক্রমের লাইভ প্রোগ্রামসমূহ সরাসরি সম্প্রচার ও আর্কাইভ দেখুন',
        'badge_text' => 'লাইভ ব্রডকাস্ট ও ভিডিও আর্কাইভ',
        'badge_icon' => 'fas fa-broadcast-tower'
    ])

    <!-- Main Live Player & Details Section -->
    <section class="py-5 bg-off-white" style="background-color: #f4f6f8;">
        <div class="container">
            
            {{-- CASE 1: Currently Live or viewing specific Archive --}}
            @if($live)
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5 bg-white p-3" id="live-player-section" style="border-top: 4px solid #006A4E !important;">
                    <div class="row g-4">
                        <!-- Left Column: Video Embed Player -->
                        <div class="col-lg-8">
                            <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow bg-black">
                                <iframe id="liveVideoPlayer" 
                                        src="{{ $live->embed_url }}" 
                                        title="{{ $live->title }}" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen></iframe>
                            </div>
                        </div>
                        
                        <!-- Right Column: Stream Details -->
                        <div class="col-lg-4 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    @if($live->is_live)
                                        <span class="badge bg-danger pulse-badge d-inline-flex align-items-center gap-1">
                                            <span class="pulse-dot"></span> 🔴 সরাসরি সম্প্রচার
                                        </span>
                                    @else
                                        <span class="badge bg-success d-inline-flex align-items-center gap-1">
                                            <i class="fas fa-check-circle"></i> আর্কাইভকৃত
                                        </span>
                                    @endif
                                    
                                    <span class="badge bg-light text-dark border d-inline-flex align-items-center gap-1" style="font-size: 0.85rem;">
                                        <i class="fas fa-eye text-danger"></i> <span id="view-count" class="fw-bold">0</span> দর্শক
                                    </span>
                                </div>

                                <h4 class="fw-bold text-dark-green mb-3" style="font-family: 'Baloo Da 2', sans-serif; line-height: 1.45; font-size: 1.3rem;">
                                    {{ $live->title }}
                                </h4>

                                <div class="mb-3 text-muted small" style="font-family: 'Baloo Da 2', sans-serif;">
                                    <i class="far fa-calendar-alt me-1"></i> সম্প্রচারের সময়: <strong>{{ $live->schedule_time->format('d M, Y (h:i A)') }}</strong>
                                </div>

                                <div class="text-muted small overflow-auto" style="font-family: 'Baloo Da 2', sans-serif; max-height: 160px; text-align: justify; line-height: 1.7;">
                                    {{ $live->description ?? 'এই লাইভ প্রোগ্রামটির কোনো বিবরণী যুক্ত করা হয়নি।' }}
                                </div>
                            </div>
                            
                            <!-- Share Actions -->
                            <div class="mt-4 pt-3 border-top border-light">
                                <h6 class="fw-bold text-secondary mb-2 small" style="font-family: 'Baloo Da 2', sans-serif;">ভিডিওটি শেয়ার করুন:</h6>
                                <div class="d-flex gap-2">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold" style="font-size: 0.8rem;">
                                        <i class="fab fa-facebook-f me-1"></i> ফেসবুক
                                    </a>
                                    <a href="https://api.whatsapp.com/send?text={{ urlencode($live->title . ' - ' . request()->fullUrl()) }}" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold" style="font-size: 0.8rem;">
                                        <i class="fab fa-whatsapp me-1"></i> হোয়াটসঅ্যাপ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            {{-- CASE 2: No Live but has Upcoming Scheduled Program --}}
            @elseif($upcoming)
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5 bg-white p-5 text-center" id="upcoming-section" style="border-top: 4px solid #f59e0b !important;">
                    <div class="mx-auto bg-warning text-dark p-3 rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    
                    <h3 class="fw-bold text-dark-green mb-2" style="font-family: 'Baloo Da 2', sans-serif;">পরবর্তী সরাসরি সম্প্রচার কর্মসূচি</h3>
                    <h5 class="text-secondary fw-bold mb-4" style="font-family: 'Baloo Da 2', sans-serif;">{{ $upcoming->title }}</h5>
                    
                    <div class="p-3 bg-light rounded-3 d-inline-block mb-4 border border-light">
                        <span class="text-muted fw-bold small d-block mb-1" style="font-family: 'Baloo Da 2', sans-serif;">সম্প্রচারের সময়সূচী:</span>
                        <h6 class="fw-bold text-dark-green mb-0" style="font-family: 'Baloo Da 2', sans-serif;">
                            <i class="far fa-calendar-alt me-1"></i> {{ $upcoming->schedule_time->format('d M, Y (h:i A)') }}
                        </h6>
                    </div>

                    <!-- Countdown Digital Clock -->
                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <div class="countdown-item shadow-sm">
                            <span class="number" id="cd-days">00</span>
                            <span class="label">দিন</span>
                        </div>
                        <div class="countdown-item shadow-sm">
                            <span class="number" id="cd-hours">00</span>
                            <span class="label">ঘণ্টা</span>
                        </div>
                        <div class="countdown-item shadow-sm">
                            <span class="number" id="cd-minutes">00</span>
                            <span class="label">মিনিট</span>
                        </div>
                        <div class="countdown-item shadow-sm">
                            <span class="number" id="cd-seconds">00</span>
                            <span class="label">সেকেন্ড</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-2">
                        <a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text={{ urlencode($upcoming->title) }}&dates={{ $upcoming->schedule_time->format('Ymd\THms') }}&details={{ urlencode($upcoming->description) }}" target="_blank" class="btn btn-outline-success rounded-pill px-4 py-2 fw-bold btn-sm">
                            <i class="far fa-bell me-1"></i> গুগল ক্যালেন্ডারে যুক্ত করুন
                        </a>
                    </div>
                </div>

            {{-- CASE 3: Offline --}}
            @else
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5 bg-white p-5 text-center" style="border-top: 4px solid #6b7280 !important;">
                    <div class="mx-auto bg-secondary text-white p-3 rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                        <i class="fas fa-video-slash fa-2x"></i>
                    </div>
                    <h3 class="fw-bold text-secondary mb-2" style="font-family: 'Baloo Da 2', sans-serif;">বর্তমানে কোনো সরাসরি সম্প্রচার নেই</h3>
                    <p class="text-muted small mb-0" style="font-family: 'Baloo Da 2', sans-serif;">আমাদের পরবর্তী অনুষ্ঠান শিডিউল হওয়া মাত্রই এখানে কাউন্টডাউন শুরু হবে। পূর্ববর্তী লাইভগুলো দেখতে নিচের আর্কাইভগুলো ব্রাউজ করুন।</p>
                </div>
            @endif

            <!-- Section Header: Live Archives -->
            <div class="d-flex align-items-center justify-content-between mb-4 border-bottom border-light pb-2">
                <h4 class="fw-bold text-dark-green mb-0" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-photo-video me-1 text-gold"></i> পূর্ববর্তী সম্প্রচার আর্কাইভসমূহ</h4>
                <span class="text-muted small">ভিডিওগুলো সরাসরি প্লেয়ারে দেখতে যেকোনোটির ওপর ক্লিক করুন</span>
            </div>

            <!-- Archives Grid -->
            <div class="row g-4">
                @forelse($archives as $archive)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-grow transition" style="display: flex; flex-direction: column;">
                            <!-- Thumbnail with Play Overlay -->
                            <div class="position-relative overflow-hidden cursor-pointer archive-trigger" data-id="{{ $archive->id }}" style="height: 200px;">
                                <img src="{{ $archive->thumbnail_url }}" alt="{{ $archive->title }}" class="w-100 h-100" style="object-fit: cover; transition: transform 0.3s;">
                                <div class="play-grid-overlay d-flex align-items-center justify-content-center">
                                    <div class="play-grid-btn">
                                        <i class="fas fa-play text-white"></i>
                                    </div>
                                </div>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body p-3.5 d-flex flex-column flex-grow-1">
                                <h6 class="fw-bold text-dark-green mb-2 text-truncate-2 cursor-pointer archive-trigger" data-id="{{ $archive->id }}" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1rem; line-height: 1.45; min-height: 2.7rem;">
                                    {{ $archive->title }}
                                </h6>
                                <div class="mb-2 text-muted small d-flex justify-content-between" style="font-size: 11px;">
                                    <span><i class="far fa-calendar-alt"></i> {{ $archive->schedule_time->format('d M, Y') }}</span>
                                    <span><i class="fas fa-eye"></i> {{ number_format($archive->view_count) }} Views</span>
                                </div>
                                @if($archive->description)
                                    <p class="text-muted small mb-0 text-truncate-2" style="font-family: 'Baloo Da 2', sans-serif; line-height: 1.6; font-size: 0.85rem;">
                                        {{ strip_tags($archive->description) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="fas fa-video fa-3x mb-3 text-secondary"></i>
                        <p class="mb-0">আর্কাইভে কোনো পূর্ববর্তী লাইভ সম্প্রচার পাওয়া যায়নি।</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($archives instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $archives->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $archives->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </section>

    <!-- Custom CSS Styles -->
    

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    // --------------------------------------------------
    // 1. Visitor View Count Updates (Only if live is playing)
    // --------------------------------------------------
    @if($live)
        var liveId = "{{ $live->id }}";
        
        function numberFormat(num) {
            if (num >= 1000000) {
                return (num / 1000000).toFixed(1) + 'M';
            }
            if (num >= 1000) {
                return (num / 1000).toFixed(1) + 'K';
            }
            return num;
        }

        function updateViewCount(count) {
            $('#view-count').text(numberFormat(count));
        }

        // Increment view count on page load
        $.ajax({
            url: "{{ url('/live/increment-view') }}/" + liveId,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    updateViewCount(response.view_count);
                }
            }
        });

        // Query view count every 60 seconds (60000ms)
        setInterval(function() {
            $.ajax({
                url: "{{ url('/live/get-view-count') }}/" + liveId,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        updateViewCount(response.view_count);
                    }
                }
            });
        }, 60000);
    @endif

    // --------------------------------------------------
    // 2. Countdown Timer (Only if upcoming program is active)
    // --------------------------------------------------
    @if($upcoming)
        var targetTime = new Date("{{ $upcoming->schedule_time->toISOString() }}").getTime();
        
        function startCountdown() {
            var interval = setInterval(function() {
                var now = new Date().getTime();
                var distance = targetTime - now;

                if (distance < 0) {
                    clearInterval(interval);
                    location.reload();
                    return;
                }

                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Format numbers to have preceding zero if < 10
                days = days < 10 ? '0' + days : days;
                hours = hours < 10 ? '0' + hours : hours;
                minutes = minutes < 10 ? '0' + minutes : minutes;
                seconds = seconds < 10 ? '0' + seconds : seconds;

                $('#cd-days').text(days);
                $('#cd-hours').text(hours);
                $('#cd-minutes').text(minutes);
                $('#cd-seconds').text(seconds);
            }, 1000);
        }
        
        startCountdown();
    @endif

    // --------------------------------------------------
    // 3. Smooth redirect on clicking past archive cards
    // --------------------------------------------------
    $('.archive-trigger').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        window.location.href = "{{ url('/live/archive') }}/" + id;
    });

});
</script>
@endpush


