@extends('admin.layouts.master')

@section('page-title', 'নেতৃত্বের প্রোফাইল যোগ করুন')

@push('styles')
<style>
    .form-section {
        background: #ffffff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .required-field::after {
        content: " *";
        color: red;
        font-weight: bold;
    }
    .text-dark-green {
        color: #006A4E !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header Page Actions -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="mb-0 fw-bold text-dark-green"><i class="fas fa-user-tie me-1"></i> নতুন নেতৃত্বের প্রোফাইল যোগ করুন</h3>
        <a href="{{ route('admin.leaders.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> তালিকায় ফিরে যান
        </a>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6 class="fw-bold mb-2"><i class="fas fa-exclamation-triangle"></i> কিছু ফিল্ডে ত্রুটি রয়েছে:</h6>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.leaders.store') }}" method="POST" enctype="multipart/form-data" id="createLeaderForm">
        @csrf
        <div class="row">
            <!-- Left Column: Core Fields -->
            <div class="col-md-9">
                <div class="form-section shadow-sm bg-white border border-light">
                    <h5 class="fw-bold text-dark-green mb-3 border-bottom pb-2">প্রাথমিক পরিচিতি</h5>
                    
                    <div class="row">
                        <!-- Name (Bangla) -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label required-field fw-bold">নেতার নাম (বাংলা)</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="যেমন: এমাম সেলিম রেজা" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- English Name -->
                        <div class="col-md-6 mb-3">
                            <label for="english_name" class="form-label required-field fw-bold">নেতার নাম (ইংরেজি)</label>
                            <input type="text" name="english_name" id="english_name" class="form-control @error('english_name') is-invalid @enderror" placeholder="যেমন: Emam Selim Reza" value="{{ old('english_name') }}" required>
                            <small class="text-muted"><i class="fas fa-info-circle"></i> ইংরেজি নাম থেকে স্বয়ংক্রিয়ভাবে ইউনিক প্রোফাইল স্ল্যাগ/লিঙ্ক তৈরি হবে।</small>
                            @error('english_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Designation -->
                        <div class="col-md-6 mb-3">
                            <label for="designation" class="form-label required-field fw-bold">পদবী</label>
                            <input type="text" name="designation" id="designation" class="form-control @error('designation') is-invalid @enderror" placeholder="যেমন: চেয়ারম্যান / সাধারণ সম্পাদক" value="{{ old('designation') }}" required>
                            @error('designation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label required-field fw-bold">নেতৃত্বের ক্যাটাগরি</label>
                            <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="central" {{ old('category') === 'central' ? 'selected' : '' }}>কেন্দ্রীয় নেতৃত্ব</option>
                                <option value="advisory" {{ old('category') === 'advisory' ? 'selected' : '' }}>উপদেষ্টা পরিষদ</option>
                                <option value="executive" {{ old('category') === 'executive' ? 'selected' : '' }}>নির্বাহী কমিটি</option>
                                <option value="regional" {{ old('category') === 'regional' ? 'selected' : '' }}>আঞ্চলিক নেতৃত্ব</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark-green mb-3 border-bottom pb-2 mt-4">বিস্তারিত বায়োগ্রাফি ও ফাইল</h5>

                    <!-- Image & Signature Image -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label required-field fw-bold">প্রোফাইল ছবি (Portrait Photo)</label>
                            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" required>
                            <small class="text-muted"><i class="fas fa-info-circle"></i> স্কয়ার সাইজের (যেমন: 400x400) ছবি আপলোড করুন। সর্বোচ্চ ২ মেগাবাইট।</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="signature_image" class="form-label fw-bold">হাতে লেখা স্বাক্ষর ছবি (Digital Signature PNG)</label>
                            <input type="file" name="signature_image" id="signature_image" class="form-control @error('signature_image') is-invalid @enderror" accept="image/png,image/svg+xml">
                            <small class="text-muted"><i class="fas fa-info-circle"></i> ব্যাকগ্রাউন্ড ছাড়া স্বচ্ছ (Transparent PNG) বা সিগনেচার পিএনজি।</small>
                            @error('signature_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Speech Video URL -->
                    <div class="mb-3">
                        <label for="speech_video_url" class="form-label fw-bold">নেতার বক্তব্য বা ভিডিও লিঙ্ক (Speech Embed URL)</label>
                        <input type="text" name="speech_video_url" id="speech_video_url" class="form-control @error('speech_video_url') is-invalid @enderror" placeholder="যেমন: ইউটিউব ভিডিও লিঙ্ক বা অডিও ফাইল লিঙ্ক" value="{{ old('speech_video_url') }}">
                        @error('speech_video_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Quote -->
                    <div class="mb-3">
                        <label for="quote" class="form-label fw-bold">গুরুত্বপূর্ণ উক্তি বা বাণী (Quote)</label>
                        <textarea name="quote" id="quote" class="form-control @error('quote') is-invalid @enderror" rows="3" placeholder="নেতার একটি উল্লেখযোগ্য উক্তি বা বাণী এখানে লিখুন...">{{ old('quote') }}</textarea>
                        @error('quote')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bio -->
                    <div class="mb-3">
                        <label for="bio" class="form-label fw-bold">বিস্তারিত পরিচিতি (Bio / Biography)</label>
                        <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror" rows="7" placeholder="নেতার শিক্ষা, অবদান ও জীবনবৃত্তান্ত এখানে বিস্তারিত লিখুন...">{{ old('bio') }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Contact & Social links -->
                <div class="form-section shadow-sm bg-white border border-light">
                    <h5 class="fw-bold text-dark-green mb-3 border-bottom pb-2">যোগাযোগ ও সোশ্যাল মিডিয়া লিঙ্ক</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="facebook_url" class="form-label fw-bold"><i class="fab fa-facebook text-primary me-1"></i> ফেসবুক লিংক (Facebook URL)</label>
                            <input type="url" name="facebook_url" id="facebook_url" class="form-control" placeholder="https://facebook.com/username" value="{{ old('facebook_url') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="twitter_url" class="form-label fw-bold"><i class="fab fa-twitter text-info me-1"></i> টুইটার লিংক (Twitter URL)</label>
                            <input type="url" name="twitter_url" id="twitter_url" class="form-control" placeholder="https://twitter.com/username" value="{{ old('twitter_url') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="linkedin_url" class="form-label fw-bold"><i class="fab fa-linkedin text-primary me-1"></i> লিঙ্কডইন লিংক (LinkedIn URL)</label>
                            <input type="url" name="linkedin_url" id="linkedin_url" class="form-control" placeholder="https://linkedin.com/in/username" value="{{ old('linkedin_url') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-bold"><i class="fas fa-envelope text-secondary me-1"></i> অফিসিয়াল ইমেইল (Email)</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="username@hezbuttawheed.org" value="{{ old('email') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings & Order -->
            <div class="col-md-3">
                <div class="form-section shadow-sm bg-white border border-light">
                    <h5 class="fw-bold text-dark-green mb-3"><i class="fas fa-cog me-1"></i> স্ট্যাটাস সেটিং</h5>
                    
                    <div class="mb-3">
                        <label for="sort_order" class="form-label required-field fw-bold">ক্রম (Sort Order)</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', 0) }}" min="0" required>
                        <small class="text-muted"><i class="fas fa-info-circle"></i> ছোট থেকে বড় নম্বরের ক্রমানুসারে দেখাবে।</small>
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_founder" id="founderSwitch" value="1">
                        <label class="form-check-label fw-bold" for="founderSwitch" id="founderLabel">সাধারণ প্রোফাইল</label>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" value="1" checked>
                        <label class="form-check-label fw-bold" for="activeSwitch" id="activeLabel">সক্রিয় (Active)</label>
                    </div>

                    <button type="submit" class="btn btn-success w-100 mb-2">
                        <i class="fas fa-save me-1"></i> প্রোফাইল সংরক্ষণ
                    </button>
                    <a href="{{ route('admin.leaders.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-1"></i> বাতিল
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Switch labels
    $('#founderSwitch').on('change', function() {
        if ($(this).is(':checked')) {
            $('#founderLabel').text('⭐ সর্বোচ্চ স্পটলাইট (Founder/Leader)');
        } else {
            $('#founderLabel').text('সাধারণ প্রোফাইল');
        }
    });

    $('#activeSwitch').on('change', function() {
        if ($(this).is(':checked')) {
            $('#activeLabel').text('সক্রিয় (Active)');
        } else {
            $('#activeLabel').text('নিষ্ক্রিয় (Inactive)');
        }
    });
});
</script>
@endpush
