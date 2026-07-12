@extends('theme::layouts.app')

@section('title', 'নাগরিক মতামত ও প্রতিক্রিয়া - হেজবুত তওহীদ')

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'নাগরিক মতামত ও প্রতিক্রিয়া',
        'subtitle' => 'হেজবুত তওহীদের আদর্শ, সমাজ সংস্কার ও মানবিক কার্যক্রম সম্পর্কে দেশ-বিদেশের নাগরিকদের মূল্যবান মতামত',
        'badge_text' => 'মতামত ও ফিডব্যাক',
        'badge_icon' => 'fas fa-comments'
    ])

    <!-- Feedback Main Section -->
    <div class="py-5" style="background-color: #f8fafc; font-family: 'Baloo Da 2', sans-serif;">
        <div class="container">
            <div class="row g-4">
                
                <!-- Feedback List Column (Left: col-lg-7) -->
                <div class="col-lg-7">
                    <h3 class="fw-bold mb-4" style="color: #0f172a; font-size: 1.5rem;"><i class="fas fa-quote-left text-success-brand me-2"></i>প্রতিক্রিয়াসমূহ</h3>
                    
                    <div class="row g-3">
                        @forelse($feedbacks as $feedback)
                            <div class="col-12">
                                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white border-light-grey position-relative hover-grow-card transition">
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
                        @empty
                            <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm border border-light-grey">
                                <div class="text-muted mb-3"><i class="far fa-comments fa-3x text-success"></i></div>
                                <h4 class="text-dark fw-bold">বর্তমানে কোনো মতামত নেই!</h4>
                                <p class="text-secondary small">প্রথম মতামতটি ডানপাশের ফর্ম ব্যবহার করে আপনিই দিন।</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($feedbacks->hasPages())
                        <div class="pagination-wrapper mt-5 d-flex justify-content-center">
                            {{ $feedbacks->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>

                <!-- Feedback Submit Form Column (Right: col-lg-5) -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white border-light-grey position-sticky" style="top: 90px; z-index: 10;">
                        <span class="fw-bold text-uppercase tracking-wider text-success-brand" style="font-size: 0.8rem; letter-spacing: 1px;">মতামত দিন</span>
                        <h3 class="fw-bold mb-4 mt-1" style="color: #0f172a; font-size: 1.5rem;">আপনার প্রতিক্রিয়া জানান</h3>
                        
                        <form action="{{ route('feedback.submit') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <!-- Name -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="name" class="form-label text-dark fw-semibold small">আপনার নাম *</label>
                                        <input type="text" name="name" id="name" class="form-control py-3 rounded-3 @error('name') is-invalid @enderror" placeholder="নাম লিখুন..." value="{{ old('name') }}" required style="font-size: 0.9rem; box-shadow: none;">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="email" class="form-label text-dark fw-semibold small">আপনার ইমেল *</label>
                                        <input type="email" name="email" id="email" class="form-control py-3 rounded-3 @error('email') is-invalid @enderror" placeholder="ইমেল..." value="{{ old('email') }}" required style="font-size: 0.9rem; box-shadow: none;">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Profession -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="designation" class="form-label text-dark fw-semibold small">পেশা / পদবী *</label>
                                        <input type="text" name="designation" id="designation" class="form-control py-3 rounded-3 @error('designation') is-invalid @enderror" placeholder="উদাঃ চাকুরীজীবী, শিক্ষক, ব্যবসায়ী..." value="{{ old('designation') }}" required style="font-size: 0.9rem; box-shadow: none;">
                                        @error('designation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Rating Selector -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label text-dark fw-semibold small d-block">আপনার রেটিং (স্টার) *</label>
                                        <div class="rating-input-wrapper d-flex gap-2">
                                            @for($i = 5; $i >= 1; $i--)
                                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="btn-check" {{ old('rating', 5) == $i ? 'checked' : '' }}>
                                                <label for="star{{ $i }}" class="btn btn-outline-light border border-light-grey text-dark-50 rounded-pill py-1 px-3" style="font-size: 0.82rem; cursor: pointer;">
                                                    {{ $i }} <i class="fas fa-star text-warning ms-1"></i>
                                                </label>
                                            @endfor
                                        </div>
                                        @error('rating')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="content" class="form-label text-dark fw-semibold small">আপনার প্রতিক্রিয়া *</label>
                                        <textarea name="content" id="content" rows="4" class="form-control rounded-3 @error('content') is-invalid @enderror" placeholder="আমাদের আদর্শ ও কার্যক্রম সম্পর্কে আপনার মতামত লিখুন..." required style="font-size: 0.9rem; box-shadow: none;">{{ old('content') }}</textarea>
                                        @error('content')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 text-end mt-4">
                                    <button type="submit" class="btn btn-brand-success text-white fw-bold px-4 py-3 rounded shadow-sm w-100 transition">
                                        মতামত জমা দিন <i class="fas fa-paper-plane ms-2 text-warning" style="font-size: 13px;"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Custom CSS Styles -->
    

@endsection
