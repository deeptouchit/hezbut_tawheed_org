{{-- resources/views/admin/testimonials/edit.blade.php --}}

@extends('admin.layouts.master')

@section('page-title', 'টেস্টিমোনিয়াল এডিট করুন - ' . $testimonial->name)

@push('styles')
<style>
    .image-preview-container {
        position: relative;
        display: inline-block;
    }
    .image-preview {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .image-preview:hover {
        transform: scale(1.05);
        border-color: #0d6efd;
    }
    .image-preview.current-image {
        border-color: #28a745;
    }
    .image-upload-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #0d6efd;
        color: white;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px solid white;
        transition: all 0.3s ease;
    }
    .image-upload-btn:hover {
        background: #0b5ed7;
        transform: scale(1.1);
    }
    .image-upload-btn input {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
    .form-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #0d6efd;
    }
    .form-section-title {
        font-weight: 600;
        color: #0d6efd;
        margin-bottom: 15px;
    }
    .required-field::after {
        content: '*';
        color: #dc3545;
        margin-left: 4px;
    }
    .rating-stars-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 5px;
    }
    .rating-stars-input input {
        display: none;
    }
    .rating-stars-input label {
        font-size: 30px;
        color: #ddd;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .rating-stars-input label:hover,
    .rating-stars-input label:hover ~ label,
    .rating-stars-input input:checked ~ label {
        color: #ffc107;
    }
    .rating-stars-input input:checked ~ label {
        color: #ffc107;
    }
    .rating-stars-input label:hover {
        transform: scale(1.2);
    }
    .preview-card {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
    }
    .preview-card .avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 10px;
        border: 3px solid #e9ecef;
    }
    .preview-card .name {
        font-weight: 600;
        font-size: 18px;
        margin-bottom: 5px;
    }
    .preview-card .designation {
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 5px;
    }
    .preview-card .content {
        color: #495057;
        font-size: 14px;
        margin-top: 10px;
        font-style: italic;
    }
    .preview-card .rating-display {
        margin-top: 10px;
    }
    .preview-card .rating-display i {
        color: #ffc107;
        font-size: 18px;
    }
    .current-image-label {
        display: inline-block;
        background: #28a745;
        color: white;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 11px;
        margin-top: 5px;
    }
    .remove-image-btn {
        margin-top: 8px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-star me-2"></i> টেস্টিমোনিয়াল এডিট করুন
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> ফিরে যান
                </a>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data" id="testimonialForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-8">
                        <!-- Client Information -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-user-circle me-2"></i> ক্লায়েন্ট তথ্য
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="required-field">নাম <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $testimonial->name) }}"
                                           placeholder="ক্লায়েন্টের নাম লিখুন"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>ডিজাইনেশন</label>
                                    <input type="text" 
                                           name="designation" 
                                           class="form-control @error('designation') is-invalid @enderror" 
                                           value="{{ old('designation', $testimonial->designation) }}"
                                           placeholder="পদবি লিখুন">
                                    @error('designation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>কোম্পানি</label>
                                    <input type="text" 
                                           name="company" 
                                           class="form-control @error('company') is-invalid @enderror" 
                                           value="{{ old('company', $testimonial->company) }}"
                                           placeholder="কোম্পানির নাম লিখুন">
                                    @error('company')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>ইমেইল</label>
                                    <input type="email" 
                                           name="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $testimonial->email) }}"
                                           placeholder="example@company.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>ফোন</label>
                                    <input type="text" 
                                           name="phone" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone', $testimonial->phone) }}"
                                           placeholder="+8801712345678">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Testimonial Content -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-comment-dots me-2"></i> টেস্টিমোনিয়াল কন্টেন্ট
                            </h5>
                            <div class="mb-3">
                                <label class="required-field">টেস্টিমোনিয়াল <span class="text-danger">*</span></label>
                                <textarea name="content" 
                                          class="form-control @error('content') is-invalid @enderror" 
                                          rows="5"
                                          placeholder="ক্লায়েন্টের মতামত লিখুন..."
                                          required>{{ old('content', $testimonial->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">নূন্যতম ১০ অক্ষর</small>
                            </div>
                        </div>

                        <!-- SEO Information -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-search me-2"></i> SEO তথ্য
                            </h5>
                            <div class="mb-3">
                                <label>মেটা টাইটেল</label>
                                <input type="text" 
                                       name="meta_title" 
                                       class="form-control @error('meta_title') is-invalid @enderror" 
                                       value="{{ old('meta_title', $testimonial->meta_title) }}"
                                       placeholder="SEO টাইটেল লিখুন">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>মেটা ডেসক্রিপশন</label>
                                <textarea name="meta_description" 
                                          class="form-control @error('meta_description') is-invalid @enderror" 
                                          rows="2"
                                          placeholder="SEO ডেসক্রিপশন লিখুন">{{ old('meta_description', $testimonial->meta_description) }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>মেটা কীওয়ার্ড</label>
                                <input type="text" 
                                       name="meta_keywords" 
                                       class="form-control @error('meta_keywords') is-invalid @enderror" 
                                       value="{{ old('meta_keywords', $testimonial->meta_keywords) }}"
                                       placeholder="SEO কীওয়ার্ড গুলো কমা দিয়ে আলাদা করুন">
                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-4">
                        <!-- Profile Image -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-image me-2"></i> ক্লায়েন্ট ছবি
                            </h5>
                            <div class="text-center">
                                <div class="image-preview-container">
                                    <img src="{{ $testimonial->avatar_url }}" 
                                         alt="Avatar Preview" 
                                         class="image-preview {{ $testimonial->avatar ? 'current-image' : '' }}"
                                         id="imagePreview">
                                    <label class="image-upload-btn">
                                        <i class="fas fa-camera"></i>
                                        <input type="file" 
                                               name="avatar" 
                                               id="avatarInput" 
                                               accept="image/*">
                                    </label>
                                </div>
                                @if($testimonial->avatar)
                                    <div class="mt-2">
                                        <span class="current-image-label">
                                            <i class="fas fa-check-circle"></i> বর্তমান ছবি আছে
                                        </span>
                                        <br>
                                        <button type="button" class="btn btn-danger btn-sm remove-image-btn" id="removeImageBtn">
                                            <i class="fas fa-trash"></i> ছবি মুছুন
                                        </button>
                                        <input type="hidden" name="remove_avatar" id="removeAvatar" value="0">
                                    </div>
                                @endif
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle"></i> 
                                    সর্বোচ্চ ২ এমবি, JPEG, PNG, JPG, WEBP
                                </small>
                                @error('avatar')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-star me-2"></i> রেটিং
                            </h5>
                            <div class="text-center">
                                <div class="rating-stars-input" id="ratingStars">
                                    <input type="radio" name="rating" value="5" id="star5" {{ old('rating', $testimonial->rating) == 5 ? 'checked' : '' }}>
                                    <label for="star5"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" name="rating" value="4" id="star4" {{ old('rating', $testimonial->rating) == 4 ? 'checked' : '' }}>
                                    <label for="star4"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" name="rating" value="3" id="star3" {{ old('rating', $testimonial->rating) == 3 ? 'checked' : '' }}>
                                    <label for="star3"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" name="rating" value="2" id="star2" {{ old('rating', $testimonial->rating) == 2 ? 'checked' : '' }}>
                                    <label for="star2"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" name="rating" value="1" id="star1" {{ old('rating', $testimonial->rating) == 1 ? 'checked' : '' }}>
                                    <label for="star1"><i class="fas fa-star"></i></label>
                                </div>
                                <small class="text-muted">রেটিং নির্বাচন করুন</small>
                                @error('rating')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-toggle-on me-2"></i> স্ট্যাটাস
                            </h5>
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" 
                                       name="is_active" 
                                       class="form-check-input" 
                                       id="statusSwitch" 
                                       value="1"
                                       {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusSwitch">
                                    <span id="statusLabel">{{ old('is_active', $testimonial->is_active) ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}</span>
                                </label>
                            </div>
                            <small class="text-muted">টেস্টিমোনিয়াল সক্রিয় বা নিষ্ক্রিয় করুন</small>
                        </div>

                        <!-- Preview Card -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-eye me-2"></i> প্রিভিউ
                            </h5>
                            <div class="preview-card">
                                <img src="{{ $testimonial->avatar_url }}" 
                                     alt="Preview" 
                                     class="avatar"
                                     id="previewAvatar">
                                <div class="name" id="previewName">{{ $testimonial->name }}</div>
                                <div class="designation" id="previewDesignation">{{ $testimonial->designation ?? 'ডিজাইনেশন' }}</div>
                                <div class="designation text-muted" id="previewCompany">{{ $testimonial->company ?? 'কোম্পানি' }}</div>
                                <div class="rating-display" id="previewRating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $testimonial->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="content" id="previewContent">
                                    {{ $testimonial->content ?? 'ক্লায়েন্টের মতামত এখানে দেখানো হবে...' }}
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="form-section">
                            <button type="submit" class="btn btn-primary w-100 mb-2" id="submitBtn">
                                <i class="fas fa-save"></i> টেস্টিমোনিয়াল আপডেট করুন
                            </button>
                            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-times"></i> বাতিল করুন
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // ============================================
    // Avatar Preview
    // ============================================
    $('#avatarInput').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
                $('#previewAvatar').attr('src', e.target.result);
                $('#imagePreview').addClass('current-image');
                // Remove existing image label if any
                $('.current-image-label').hide();
                $('#removeImageBtn').hide();
            }
            reader.readAsDataURL(file);
        }
    });

    // ============================================
    // Remove Image
    // ============================================
    $('#removeImageBtn').click(function() {
        Swal.fire({
            title: 'আপনি কি নিশ্চিত?',
            text: 'ক্লায়েন্টের ছবি মুছে ফেলতে চান?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'হ্যাঁ, মুছুন',
            cancelButtonText: 'বাতিল'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#removeAvatar').val('1');
                $('#imagePreview').attr('src', 'https://ui-avatars.com/api/?name={{ urlencode($testimonial->name) }}&size=120&background=6366f1&color=fff&rounded=true');
                $('#previewAvatar').attr('src', 'https://ui-avatars.com/api/?name={{ urlencode($testimonial->name) }}&size=80&background=6366f1&color=fff&rounded=true');
                $('#imagePreview').removeClass('current-image');
                $('#removeImageBtn').hide();
                $('.current-image-label').hide();
                
                Swal.fire({
                    icon: 'success',
                    title: 'ছবি মুছে ফেলা হয়েছে!',
                    text: 'ফর্ম সাবমিট করলে ছবি স্থায়ীভাবে মুছে যাবে',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });

    // ============================================
    // Live Preview Update
    // ============================================
    $('input[name="name"]').on('keyup', function() {
        const name = $(this).val() || 'ক্লায়েন্টের নাম';
        $('#previewName').text(name);
        
        // Update avatar if no image uploaded and no existing image
        if (!$('#avatarInput').get(0).files.length && $('#removeAvatar').val() != '1') {
            const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&size=80&background=6366f1&color=fff&rounded=true`;
            $('#previewAvatar').attr('src', avatarUrl);
        }
    });

    $('input[name="designation"]').on('keyup', function() {
        const designation = $(this).val() || 'ডিজাইনেশন';
        $('#previewDesignation').text(designation);
    });

    $('input[name="company"]').on('keyup', function() {
        const company = $(this).val() || 'কোম্পানি';
        $('#previewCompany').text(company);
    });

    $('textarea[name="content"]').on('keyup', function() {
        const content = $(this).val() || 'ক্লায়েন্টের মতামত এখানে দেখানো হবে...';
        $('#previewContent').text(content);
    });

    // ============================================
    // Rating Preview
    // ============================================
    $('input[name="rating"]').change(function() {
        const rating = parseInt($(this).val());
        const stars = $('#previewRating');
        stars.empty();
        
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                stars.append('<i class="fas fa-star"></i>');
            } else {
                stars.append('<i class="far fa-star"></i>');
            }
        }
    });

    // ============================================
    // Status Switch
    // ============================================
    $('#statusSwitch').change(function() {
        if ($(this).is(':checked')) {
            $('#statusLabel').text('সক্রিয়');
        } else {
            $('#statusLabel').text('নিষ্ক্রিয়');
        }
    });

    // ============================================
    // Form Validation
    // ============================================
    $('#testimonialForm').on('submit', function(e) {
        const name = $('input[name="name"]').val().trim();
        const content = $('textarea[name="content"]').val().trim();
        
        if (!name) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'ত্রুটি!',
                text: 'দয়া করে ক্লায়েন্টের নাম দিন',
                confirmButtonColor: '#0d6efd'
            });
            $('input[name="name"]').focus();
            return false;
        }
        
        if (!content || content.length < 10) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'ত্রুটি!',
                text: 'দয়া করে কমপক্ষে ১০ অক্ষরের মতামত লিখুন',
                confirmButtonColor: '#0d6efd'
            });
            $('textarea[name="content"]').focus();
            return false;
        }
        
        return true;
    });

    // ============================================
    // Toastr Messages
    // ============================================
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            toastr.error('{{ $error }}');
        @endforeach
    @endif
});
</script>
@endpush