@extends('admin.layouts.master')

@section('page-title', 'ভিডিওর তথ্য সংশোধন করুন')

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
        <h3 class="mb-0 fw-bold text-dark-green"><i class="fab fa-youtube text-danger me-1"></i> ভিডিওর তথ্য সংশোধন করুন</h3>
        <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-secondary btn-sm">
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

    <form action="{{ route('admin.videos.update', $video->id) }}" method="POST" id="editVideoForm">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Left Column: Core Fields -->
            <div class="col-md-9">
                <div class="form-section shadow-sm bg-white border border-light">
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label required-field fw-bold">ভিডিওর শিরোনাম</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="যেমন: হেযবুত তওহীদের লক্ষ্য ও উদ্দেশ্য" value="{{ old('title', $video->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- YouTube URL / Video ID -->
                    <div class="mb-3">
                        <label for="youtube_id" class="form-label required-field fw-bold">ইউটিউব ভিডিওর লিঙ্ক অথবা ভিডিও আইডি</label>
                        <input type="text" name="youtube_id" id="youtube_id" class="form-control @error('youtube_id') is-invalid @enderror" placeholder="যেমন: https://www.youtube.com/watch?v=uBqA0sT8Xic অথবা শুধু ID: uBqA0sT8Xic" value="{{ old('youtube_id', $video->youtube_id) }}" required>
                        <small class="text-muted"><i class="fas fa-info-circle"></i> সম্পূর্ণ লিঙ্ক কপি করে বসালেও সিস্টেম অটোমেটিক ভিডিও আইডিটি ফিল্টার করে নেবে।</small>
                        @error('youtube_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">ভিডিওর সংক্ষিপ্ত বিবরণ (যদি থাকে)</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="ভিডিওটির সারসংক্ষেপ এখানে লিখুন...">{{ old('description', $video->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Video Preview Player -->
                    <div class="mt-4">
                        <label class="form-label fw-bold">ভিডিও প্রিভিউ (YouTube Preview)</label>
                        <div class="p-3 bg-light rounded-3 text-center border border-light">
                            <div class="ratio ratio-16x9 mx-auto" style="max-width: 500px;">
                                <iframe src="{{ $video->embed_url }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="rounded-3 shadow-sm"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings -->
            <div class="col-md-3">
                <div class="form-section shadow-sm bg-white border border-light">
                    <h5 class="fw-bold text-dark-green mb-3"><i class="fas fa-paper-plane me-1"></i> ভিডিও সেটিং</h5>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="statusSwitch" value="1" {{ $video->is_active ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="statusSwitch" id="statusLabel">{{ $video->is_active ? 'সক্রিয় (Active)' : 'নিষ্ক্রিয় (Inactive)' }}</label>
                    </div>

                    <div class="mb-3">
                        <label for="sort_order" class="form-label small fw-bold">অর্ডারিং নম্বর (Priority)</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control form-control-sm" placeholder="যেমন: ১" value="{{ old('sort_order', $video->sort_order) }}" min="0">
                    </div>

                    <button type="submit" class="btn btn-success w-100 mb-2">
                        <i class="fas fa-save me-1"></i> সেভ করুন
                    </button>
                    <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-secondary w-100">
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
