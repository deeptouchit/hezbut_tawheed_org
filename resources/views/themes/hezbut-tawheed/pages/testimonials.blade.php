@extends('theme::layouts.app')

@section('title', 'নাগরিক প্রতিক্রিয়া ও মতামত - হেযবুত তওহীদ')

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'নাগরিক প্রতিক্রিয়া ও সুধী মতামত',
        'subtitle' =>
            'হেযবুত তofহীদের আদর্শ, সমাজ সংস্কার ও মানবিক কার্যক্রম সম্পর্কে দেশ-বিদেশের নাগরিকদের সুচিন্তিত মূল্যায়ন',
        'badge_text' => 'নাগরিক প্রতিক্রিয়া',
        'badge_icon' => 'fas fa-quote-left',
    ])

    <!-- Testimonials Main Section -->
    <div class="py-5" style="background-color: #f8fafc; font-family: 'Baloo Da 2', sans-serif;">
        <div class="container">

            <!-- Alert Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show p-4 rounded-3 shadow-sm mb-4 border-0"
                    style="background-color: #ecfdf5; color: #065f46; border-left: 4px solid #059669 !important;"
                    role="alert">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-check-circle fa-xl text-success"></i>
                        <div>
                            <h5 class="fw-bold mb-1" style="font-size: 1.1rem;">ধন্যবাদ!</h5>
                            <p class="mb-0 small" style="font-size: 0.95rem;">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show p-4 rounded-3 shadow-sm mb-4 border-0"
                    style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #dc2626 !important;"
                    role="alert">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-exclamation-triangle fa-xl text-danger"></i>
                        <div>
                            <h5 class="fw-bold mb-1" style="font-size: 1.1rem;">দুঃখিত!</h5>
                            <p class="mb-0 small" style="font-size: 0.95rem;">{{ session('error') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4 align-items-stretch">

                <!-- Testimonial List Column (Left: col-lg-7) -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white h-100"
                        style="border-top: 4px solid #006A4E !important;">
                        <h3 class="fw-bold mb-4 text-dark" style="font-size: 1.5rem;">
                            <i class="fas fa-comments text-success-brand me-2"></i>নাগরিক প্রতিক্রিয়াসমূহ
                        </h3>

                        <div class="row g-3" id="testimonial-posts-container">
                            @if (count($testimonials) > 0)
                                @include('theme::pages.testimonials_list', compact('testimonials'))
                            @else
                                <div class="col-12 text-center py-5 bg-light rounded-3">
                                    <div class="text-muted mb-3"><i class="far fa-comments fa-3x text-success"></i>
                                    </div>
                                    <h5 class="text-dark fw-bold mb-1">বর্তমানে কোনো মতামত প্রকাশ করা হয়নি!</h5>
                                    <p class="text-secondary small">প্রথম প্রতিক্রিয়াটি ডানপাশের ফর্ম ব্যবহার করে
                                        আপনিই প্রদান করুন।</p>
                                </div>
                            @endif
                        </div>

                        <!-- Load More Button -->
                        @if ($testimonials->hasPages())
                            <div class="text-center mt-4" id="load-more-wrapper">
                                <button id="load-more-btn" data-next-page="2"
                                    class="btn text-white rounded-3 fw-bold px-4 py-2.5 shadow-sm transition"
                                    style="background-color: #006A4E; font-size: 0.95rem;">
                                    আরও মতামত লোড করুন <i class="fas fa-sync-alt ms-2" id="load-more-icon"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Testimonial Submit Form Column (Right: col-lg-5) -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white position-sticky"
                        style="top: 90px; z-index: 10; border-top: 4px solid #F59E0B !important;">

                        <div class="mb-4 pb-2 border-bottom">
                            <span class="fw-bold text-uppercase text-warning"
                                style="font-size: 0.8rem; letter-spacing: 0.5px;">প্রতিক্রিয়া পাঠান</span>
                            <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem;">আপনার মূল্যবান বক্তব্য
                            </h3>
                        </div>

                        <form action="{{ route('testimonials.submit') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <!-- Name -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="name" class="form-label text-dark fw-semibold small">আপনার
                                            নাম *</label>
                                        <input type="text" name="name" id="name"
                                            class="form-control py-3 rounded-3 clean-input @error('name') is-invalid @enderror"
                                            placeholder="আপনার নাম লিখুন..." value="{{ old('name') }}" required
                                            style="font-size: 0.95rem;">
                                        @error('name')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="email" class="form-label text-dark fw-semibold small">আপনার
                                            ইমেল *</label>
                                        <input type="email" name="email" id="email"
                                            class="form-control py-3 rounded-3 clean-input @error('email') is-invalid @enderror"
                                            placeholder="ইমেল ঠিকানা..." value="{{ old('email') }}" required
                                            style="font-size: 0.95rem;">
                                        @error('email')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Designation / Profession -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="designation" class="form-label text-dark fw-semibold small">পেশা
                                            বা পরিচয় *</label>
                                        <input type="text" name="designation" id="designation"
                                            class="form-control py-3 rounded-3 clean-input @error('designation') is-invalid @enderror"
                                            placeholder="উদাঃ শিক্ষক, ব্যবসায়ী, সংগঠক..." value="{{ old('designation') }}"
                                            required style="font-size: 0.95rem;">
                                        @error('designation')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Rating Selector -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label text-dark fw-semibold small d-block mb-2">রেটিং
                                            (স্টার) *</label>
                                        <div class="rating-input-wrapper d-flex gap-2">
                                            @for ($i = 5; $i >= 1; $i--)
                                                <input type="radio" id="star{{ $i }}" name="rating"
                                                    value="{{ $i }}" class="btn-check"
                                                    {{ old('rating', 5) == $i ? 'checked' : '' }}>
                                                <label for="star{{ $i }}"
                                                    class="btn btn-outline-secondary border text-dark rounded-3 py-1.5 px-3 flex-fill"
                                                    style="font-size: 0.85rem; cursor: pointer;">
                                                    {{ $i }} <i class="fas fa-star text-warning ms-1"></i>
                                                </label>
                                            @endfor
                                        </div>
                                        @error('rating')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Content / Review -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="content" class="form-label text-dark fw-semibold small">আপনার
                                            প্রতিক্রিয়া *</label>
                                        <textarea name="content" id="content" rows="4"
                                            class="form-control p-3 rounded-3 clean-input @error('content') is-invalid @enderror"
                                            placeholder="আপনার অভিজ্ঞতা বা মূল্যবান মতামত লিখুন..." required style="font-size: 0.95rem; min-height: 110px;">{{ old('content') }}</textarea>
                                        @error('content')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 mt-4">
                                    <button type="submit"
                                        class="btn text-white fw-bold py-3 w-100 rounded-3 transition shadow-sm d-flex align-items-center justify-content-center gap-2"
                                        style="background-color: #006A4E; font-size: 1rem; border: none;">
                                        <span>প্রতিক্রিয়া পাঠান</span>
                                        <i class="fas fa-paper-plane text-warning"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Load More Testimonials AJAX
            $(document).on('click', '#load-more-btn', function(e) {
                e.preventDefault();
                var btn = $(this);
                var nextPage = btn.data('next-page');
                var icon = $('#load-more-icon');

                icon.addClass('fa-spin');
                btn.prop('disabled', true);

                $.ajax({
                    url: "{{ route('testimonials.index') }}?page=" + nextPage,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.html) {
                            $('#testimonial-posts-container').append(response.html);
                            btn.data('next-page', nextPage + 1);

                            if (!response.hasMore) {
                                $('#load-more-wrapper').remove();
                            }
                        } else {
                            $('#load-more-wrapper').remove();
                        }
                    },
                    error: function() {
                        alert('নেটওয়ার্ক সমস্যা! অনুগ্রহ করে কিছুক্ষন পর আবার চেষ্টা করুন।');
                    },
                    complete: function() {
                        icon.removeClass('fa-spin');
                        btn.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
