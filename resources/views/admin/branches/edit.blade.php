@extends('admin.layouts.master')

@section('page-title', 'কার্যালয়ের তথ্য সংশোধন')

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
        <h3 class="mb-0 fw-bold text-dark-green"><i class="fas fa-edit me-1"></i> কার্যালয়ের তথ্য সংশোধন করুন</h3>
        <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary btn-sm">
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

    <form action="{{ route('admin.branches.update', $branch->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Left Column: Core Fields -->
            <div class="col-md-9">
                <div class="form-section shadow-sm bg-white border border-light">
                    <h5 class="fw-bold text-dark-green mb-3 border-bottom pb-2">কার্যালয়ের বিবরণ</h5>
                    
                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label required-field fw-bold">কার্যালয়ের নাম (বাংলা)</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="যেমন: ঢাকা জেলা কার্যালয়" value="{{ old('name', $branch->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label required-field fw-bold">কার্যালয়ের ধরণ (Type)</label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">নির্বাচন করুন</option>
                                <option value="central" {{ old('type', $branch->type) === 'central' ? 'selected' : '' }}>কেন্দ্রীয় কার্যালয়</option>
                                <option value="division" {{ old('type', $branch->type) === 'division' ? 'selected' : '' }}>বিভাগীয় কার্যালয়</option>
                                <option value="district" {{ old('type', $branch->type) === 'district' ? 'selected' : '' }}>জেলা কার্যালয়</option>
                                <option value="upazila" {{ old('type', $branch->type) === 'upazila' ? 'selected' : '' }}>উপজেলা কার্যালয়</option>
                                <option value="international" {{ old('type', $branch->type) === 'international' ? 'selected' : '' }}>আন্তর্জাতিক শাখা</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label for="address" class="form-label required-field fw-bold">কার্যালয়ের পূর্ণাঙ্গ ঠিকানা (Address)</label>
                        <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3" placeholder="কার্যালয়ের বিস্তারিত ঠিকানা এখানে লিখুন..." required>{{ old('address', $branch->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Phone -->
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label fw-bold">যোগাযোগের ফোন/মোবাইল নম্বর</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="যেমন: +৮৮০১xxxxxxxxx" value="{{ old('phone', $branch->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-bold">অফিসিয়াল ইমেল (Email)</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="যেমন: dhaka@hezbuttawheed.org" value="{{ old('email', $branch->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark-green mb-3 border-bottom pb-2 mt-4">কার্যালয়ের কর্মকর্তাবৃন্দ (Branch Officials)</h5>
                    <div id="officials-container">
                        <!-- Dynamic officials items will load here -->
                    </div>
                    <button type="button" class="btn btn-outline-success btn-sm rounded-pill mt-2" id="add-official-btn">
                        <i class="fas fa-plus"></i> কর্মকর্তা যোগ করুন
                    </button>
                </div>

                <!-- Google Map section -->
                <div class="form-section shadow-sm bg-white border border-light">
                    <h5 class="fw-bold text-dark-green mb-3 border-bottom pb-2">গুগল ম্যাপ লোকেশন (Google Map Embed)</h5>
                    <div class="mb-3">
                        <label for="google_map_url" class="form-label fw-bold">ম্যাপ লিঙ্ক বা এমবেড আইফ্রেম সোর্স (Google Map Embed URL / Source)</label>
                        <textarea name="google_map_url" id="google_map_url" class="form-control @error('google_map_url') is-invalid @enderror" rows="3" placeholder="গুগল ম্যাপস থেকে শেয়ার লিংক বা embed map iframe এর src লিঙ্কটি এখানে দিন...">{{ old('google_map_url', $branch->google_map_url) }}</textarea>
                        <small class="text-muted"><i class="fas fa-info-circle"></i> গুগল ম্যাপস এ গিয়ে Share > Embed a map এ ক্লিক করে শুধুমাত্র `src="..."` কোডের লিঙ্কটি কপি করে বসিয়ে দিলে ম্যাপ প্রদর্শন করবে।</small>
                        @error('google_map_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Geographical Coordinates (Latitude & Longitude) -->
                <div class="form-section shadow-sm bg-white border border-light">
                    <h5 class="fw-bold text-dark-green mb-3 border-bottom pb-2">ভৌগোলিক স্থানাঙ্ক (Geographical Coordinates)</h5>
                    <div class="row">
                        <!-- Latitude -->
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label fw-bold">অক্ষাংশ (Latitude)</label>
                            <input type="text" name="latitude" id="latitude" class="form-control @error('latitude') is-invalid @enderror" placeholder="যেমন: 23.8103" value="{{ old('latitude', $branch->latitude) }}">
                            <small class="text-muted"><i class="fas fa-info-circle"></i> মান অবশ্যই -৯০ থেকে ৯০ এর মধ্যে সংখ্যা হতে হবে।</small>
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Longitude -->
                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label fw-bold">দ্রাঘিমাংশ (Longitude)</label>
                            <input type="text" name="longitude" id="longitude" class="form-control @error('longitude') is-invalid @enderror" placeholder="যেমন: 90.4125" value="{{ old('longitude', $branch->longitude) }}">
                            <small class="text-muted"><i class="fas fa-info-circle"></i> মান অবশ্যই -১৮০ থেকে ১৮০ এর মধ্যে সংখ্যা হতে হবে।</small>
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings & Image -->
            <div class="col-md-3">
                <div class="form-section shadow-sm bg-white border border-light">
                    <h5 class="fw-bold text-dark-green mb-3"><i class="fas fa-cog me-1"></i> সেটিংস</h5>
                    
                    <!-- Image -->
                    <div class="mb-4">
                        <label for="image" class="form-label fw-bold">কার্যালয়/ভবনের ছবি</label>
                        <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        <small class="text-muted d-block mb-2"><i class="fas fa-info-circle"></i> নতুন ছবি আপলোড করলে আগেরটি পরিবর্তন হবে।</small>
                        <div class="mt-2 border p-2 rounded bg-light d-inline-block">
                            <span class="d-block small text-muted mb-1">বর্তমান ছবি:</span>
                            <img src="{{ $branch->image_url }}" alt="{{ $branch->name }}" class="rounded shadow-sm" style="max-height: 80px; object-fit: cover;">
                        </div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Sort order -->
                    <div class="mb-3">
                        <label for="sort_order" class="form-label required-field fw-bold">ক্রম বিন্যাস (Sort Order)</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $branch->sort_order) }}" min="0" required>
                        <small class="text-muted"><i class="fas fa-info-circle"></i> ছোট থেকে বড় নম্বরের ক্রমানুসারে দেখাবে।</small>
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Active status switch -->
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" value="1" {{ $branch->is_active ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="activeSwitch" id="activeLabel">{{ $branch->is_active ? 'সক্রিয় (Active)' : 'নিষ্ক্রিয় (Inactive)' }}</label>
                    </div>

                    <button type="submit" class="btn btn-success w-100 mb-2">
                        <i class="fas fa-save me-1"></i> সংশোধন সেভ
                    </button>
                    <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary w-100">
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
    // Switch active status label handler
    $('#activeSwitch').on('change', function() {
        if ($(this).is(':checked')) {
            $('#activeLabel').text('সক্রিয় (Active)');
        } else {
            $('#activeLabel').text('নিষ্ক্রিয় (Inactive)');
        }
    });

    // Officials Repeater Logic
    let officialIndex = 0;

    function addOfficialRow(designation = '', name = '', phone = '', email = '', existingImage = '', previewUrl = '') {
        if (!previewUrl) {
            previewUrl = 'https://ui-avatars.com/api/?name=User&background=cbd5e1&color=64748b&size=128';
        }

        let html = `
        <div class="card bg-light border border-light p-3 mb-3 official-repeater-item position-relative shadow-sm" data-index="${officialIndex}">
            <button type="button" class="btn btn-danger btn-sm rounded-circle remove-official-btn d-flex align-items-center justify-content-center" style="position: absolute; right: 10px; top: 10px; width: 28px; height: 28px;" title="বাতিল করুন">
                <i class="fas fa-times text-white"></i>
            </button>
            <div class="row">
                <!-- Photo input & Preview -->
                <div class="col-md-3">
                    <div class="mb-2">
                        <label class="form-label fw-bold small">কর্মকর্তার ছবি</label>
                        <input type="file" name="officials[${officialIndex}][image]" class="form-control form-control-sm official-img-input" accept="image/*">
                        <input type="hidden" name="officials[${officialIndex}][existing_image]" value="${existingImage}">
                        
                        <div class="mt-2 text-center">
                            <img src="${previewUrl}" class="rounded border shadow-sm official-preview-img" style="width: 70px; height: 70px; object-fit: cover;">
                        </div>
                    </div>
                </div>

                <!-- Fields -->
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold small required-field">পদবী / পরিচিতি</label>
                            <input type="text" name="officials[${officialIndex}][designation]" class="form-control form-control-sm" placeholder="যেমন: জেলা আমির/সভাপতি বা সাধারণ সম্পাদক" value="${designation}" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold small required-field">কর্মকর্তার নাম</label>
                            <input type="text" name="officials[${officialIndex}][name]" class="form-control form-control-sm" placeholder="নাম লিখুন" value="${name}" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold small">মোবাইল নম্বর</label>
                            <input type="text" name="officials[${officialIndex}][phone]" class="form-control form-control-sm" placeholder="মোবাইল নম্বর" value="${phone}">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold small">ইমেইল</label>
                            <input type="email" name="officials[${officialIndex}][email]" class="form-control form-control-sm" placeholder="ইমেল অ্যাড্রেস" value="${email}">
                        </div>
                        <input type="hidden" name="officials[${officialIndex}][sort_order]" value="${officialIndex}">
                    </div>
                </div>
            </div>
        </div>`;

        $('#officials-container').append(html);
        officialIndex++;
    }

    // Populate Existing Officials
    @foreach($branch->officials as $official)
        addOfficialRow(
            '{{ addslashes($official->designation) }}',
            '{{ addslashes($official->name) }}',
            '{{ addslashes($official->phone) }}',
            '{{ addslashes($official->email) }}',
            '{{ $official->image }}',
            '{{ $official->image_url }}'
        );
    @endforeach

    // Add default rows if no official exists
    if (officialIndex === 0) {
        addOfficialRow('জেলা আমির/জেলা সভাপতি');
        addOfficialRow('জেলা সহকারী আমির/জেলা সাধারণ সম্পাদক');
    }

    // Add custom row button
    $('#add-official-btn').on('click', function() {
        addOfficialRow();
    });

    // Remove row
    $(document).on('click', '.remove-official-btn', function() {
        $(this).closest('.official-repeater-item').fadeOut(250, function() {
            $(this).remove();
        });
    });

    // Image preview handler
    $(document).on('change', '.official-img-input', function() {
        let input = this;
        let card = $(this).closest('.official-repeater-item');
        let preview = card.find('.official-preview-img');
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
});
</script>
@endpush
