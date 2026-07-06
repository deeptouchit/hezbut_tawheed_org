@extends('admin.layouts.master')

@section('page-title', 'নতুন লাইভ অনুষ্ঠান যোগ করুন')

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
        <h3 class="mb-0 fw-bold text-dark-green"><i class="fas fa-broadcast-tower text-danger me-1"></i> নতুন লাইভ অনুষ্ঠান যোগ করুন</h3>
        <a href="{{ route('admin.live-broadcasts.index') }}" class="btn btn-outline-secondary btn-sm">
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

    <form action="{{ route('admin.live-broadcasts.store') }}" method="POST" id="createLiveForm">
        @csrf
        <div class="row">
            <!-- Left Column: Core Fields -->
            <div class="col-md-9">
                <div class="form-section shadow-sm bg-white border border-light">
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label required-field fw-bold">অনুষ্ঠানের শিরোনাম</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="যেমন: বিশ্বশান্তি ও ধর্মীয় উগ্রবাদ দমনে হেযবুত তওহীদের ভূমিকা" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Source Type (YouTube vs Facebook) -->
                    <div class="mb-4">
                        <label class="form-label required-field fw-bold d-block">লাইভ প্ল্যাটফর্ম সোর্স</label>
                        <div class="form-check form-check-inline me-4">
                            <input class="form-check-input" type="radio" name="source_type" id="sourceYoutube" value="youtube" {{ old('source_type', 'youtube') === 'youtube' ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-danger" for="sourceYoutube"><i class="fab fa-youtube"></i> YouTube Live</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="source_type" id="sourceFacebook" value="facebook" {{ old('source_type') === 'facebook' ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-primary" for="sourceFacebook"><i class="fab fa-facebook"></i> Facebook Live</label>
                        </div>
                    </div>

                    <!-- Video ID / URL -->
                    <div class="mb-3">
                        <label for="video_id" class="form-label required-field fw-bold" id="videoIdLabel">ইউটিউব ভিডিওর আইডি বা লিঙ্ক</label>
                        <input type="text" name="video_id" id="video_id" class="form-control @error('video_id') is-invalid @enderror" placeholder="যেমন: uBqA0sT8Xic" value="{{ old('video_id') }}" required>
                        <small class="text-muted" id="videoIdHelp"><i class="fas fa-info-circle"></i> ভিডিওর লিঙ্ক বসালেও সিস্টেম স্বয়ংক্রিয়ভাবে ভিডিও আইডিটি ফিল্টার করে নেবে।</small>
                        @error('video_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Schedule Time -->
                    <div class="mb-3">
                        <label for="schedule_time" class="form-label required-field fw-bold">সম্প্রচারের দিন ও সময়</label>
                        <input type="datetime-local" name="schedule_time" id="schedule_time" class="form-control @error('schedule_time') is-invalid @enderror" value="{{ old('schedule_time', date('Y-m-d\TH:i')) }}" required>
                        @error('schedule_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">অনুষ্ঠানের সংক্ষিপ্ত বিবরণী</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="অনুষ্ঠানের বিবরণী এখানে লিখুন...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings -->
            <div class="col-md-3">
                <div class="form-section shadow-sm bg-white border border-light">
                    <h5 class="fw-bold text-dark-green mb-3"><i class="fas fa-cog me-1"></i> স্ট্যাটাস সেটিং</h5>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_live" id="liveSwitch" value="1">
                        <label class="form-check-label fw-bold" for="liveSwitch" id="liveLabel">অফলাইন (Offline)</label>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" value="1" checked>
                        <label class="form-check-label fw-bold" for="activeSwitch" id="activeLabel">সক্রিয় (Active)</label>
                    </div>

                    <button type="submit" class="btn btn-success w-100 mb-2">
                        <i class="fas fa-save me-1"></i> সংরক্ষণ করুন
                    </button>
                    <a href="{{ route('admin.live-broadcasts.index') }}" class="btn btn-outline-secondary w-100">
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
    $('#liveSwitch').on('change', function() {
        if ($(this).is(':checked')) {
            $('#liveLabel').text('🔴 লাইভ চলছে (Live)');
        } else {
            $('#liveLabel').text('অফলাইন (Offline)');
        }
    });

    $('#activeSwitch').on('change', function() {
        if ($(this).is(':checked')) {
            $('#activeLabel').text('সক্রিয় (Active)');
        } else {
            $('#activeLabel').text('নিষ্ক্রিয় (Inactive)');
        }
    });

    // Dynamic field labels based on source selection
    $('input[name="source_type"]').on('change', function() {
        if ($(this).val() === 'youtube') {
            $('#videoIdLabel').text('ইউটিউব ভিডিওর আইডি বা লিঙ্ক');
            $('#video_id').attr('placeholder', 'যেমন: uBqA0sT8Xic');
            $('#videoIdHelp').html('<i class="fas fa-info-circle"></i> ভিডিওর লিঙ্ক বসালেও সিস্টেম স্বয়ংক্রিয়ভাবে ভিডিও আইডিটি ফিল্টার করে নেবে।');
        } else {
            $('#videoIdLabel').text('ফেসবুক ভিডিওর ইউআরএল বা আইডি');
            $('#video_id').attr('placeholder', 'যেমন: https://www.facebook.com/watch/?v=123456789');
            $('#videoIdHelp').html('<i class="fas fa-info-circle"></i> ফেসবুক লাইভ ভিডিও বা ওয়াচ লিঙ্কটি সম্পূর্ণ কপি করে বসান।');
        }
    });
});
</script>
@endpush
