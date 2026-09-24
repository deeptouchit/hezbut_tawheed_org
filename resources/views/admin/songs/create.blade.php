@extends('admin.layouts.master')

@section('page-title', 'নতুন গান যোগ করুন')

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
        <h3 class="mb-0 fw-bold text-dark-green"><i class="fas fa-music text-primary me-1"></i> নতুন গান যোগ করুন</h3>
        <a href="{{ route('admin.songs.index') }}" class="btn btn-outline-secondary btn-sm">
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

    <form action="{{ route('admin.songs.store') }}" method="POST" enctype="multipart/form-data" id="createSongForm">
        @csrf
        <div class="row">
            <!-- Left Column: Core Fields -->
            <div class="col-md-9">
                <div class="form-section shadow-sm bg-white border border-light">
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label required-field fw-bold">গানের শিরোনাম</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="যেমন: হেযবুত তওহীদ দলীয় সঙ্গীত" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <label for="category" class="form-label required-field fw-bold">ক্যাটাগরি</label>
                        <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">ক্যাটাগরি নির্বাচন করুন</option>
                            <option value="party_anthem" {{ old('category') == 'party_anthem' ? 'selected' : '' }}>দলীয় সঙ্গীত</option>
                            <option value="national" {{ old('category') == 'national' ? 'selected' : '' }}>দেশাত্মবোধক গান</option>
                            <option value="awakening" {{ old('category') == 'awakening' ? 'selected' : '' }}>জাগরণী গান</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- YouTube ID (Optional, for sync) -->
                    <div class="mb-3">
                        <label for="youtube_id" class="form-label fw-bold">ইউটিউব ভিডিও লিঙ্ক অথবা আইডি (ঐচ্ছিক)</label>
                        <input type="text" name="youtube_id" id="youtube_id" class="form-control @error('youtube_id') is-invalid @enderror" placeholder="যেমন: https://www.youtube.com/watch?v=uBqA0sT8Xic অথবা শুধু ID" value="{{ old('youtube_id') }}">
                        <small class="text-muted"><i class="fas fa-info-circle"></i> ইউটিউব থেকে গানটি এম্বেড করতে চাইলে লিঙ্ক বসালে সিস্টেম অটোমেটিক আইডি ফিল্টার করে নেবে।</small>
                    </div>

                    <!-- Local Audio Upload -->
                    <div class="mb-3">
                        <label for="audio_file" class="form-label fw-bold">লোকাল অডিও ফাইল (.mp3, .wav, .aac, .m4a)</label>
                        <input type="file" name="audio_file" id="audio_file" class="form-control @error('audio_file') is-invalid @enderror" accept="audio/*">
                        <small class="text-muted"><i class="fas fa-info-circle"></i> সর্বোচ্চ ফাইল সাইজ ২০ মেগাবাইট (20MB) হতে হবে।</small>
                    </div>

                    <!-- Local Video Upload -->
                    <div class="mb-3">
                        <label for="video_file" class="form-label fw-bold">লোকাল ভিডিও ফাইল (.mp4, .webm)</label>
                        <input type="file" name="video_file" id="video_file" class="form-control @error('video_file') is-invalid @enderror" accept="video/*">
                        <small class="text-muted"><i class="fas fa-info-circle"></i> সর্বোচ্চ ফাইল সাইজ ৫০ মেগাবাইট (50MB) হতে হবে।</small>
                    </div>

                    <!-- Lyrics -->
                    <div class="mb-3">
                        <label for="lyrics" class="form-label fw-bold">গানের কথা / লিরিক্স</label>
                        <textarea name="lyrics" id="lyrics" class="form-control @error('lyrics') is-invalid @enderror" rows="6" placeholder="গানের লিরিক্স এখানে লিখুন বা পেস্ট করুন...">{{ old('lyrics') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings -->
            <div class="col-md-3">
                <div class="form-section shadow-sm bg-white border border-light">
                    <h5 class="fw-bold text-dark-green mb-3"><i class="fas fa-paper-plane me-1"></i> গান সেটিং</h5>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="statusSwitch" value="1" checked>
                        <label class="form-check-label fw-bold" for="statusSwitch" id="statusLabel">সক্রিয় (Active)</label>
                    </div>

                    <div class="mb-3">
                        <label for="sort_order" class="form-label small fw-bold">অর্ডারিং নম্বর (Priority)</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control form-control-sm" placeholder="যেমন: ১" value="0" min="0">
                    </div>

                    <button type="submit" class="btn btn-success w-100 mb-2">
                        <i class="fas fa-save me-1"></i> গান সংরক্ষণ করুন
                    </button>
                    <a href="{{ route('admin.songs.index') }}" class="btn btn-outline-secondary w-100">
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
    // Dynamic active label status
    $('#statusSwitch').on('change', function() {
        if ($(this).is(':checked')) {
            $('#statusLabel').text('সক্রিয় (Active)');
        } else {
            $('#statusLabel').text('নিষ্ক্রিয় (Inactive)');
        }
    });
});
</script>
@endpush
