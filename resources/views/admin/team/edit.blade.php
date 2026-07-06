@extends('admin.layouts.master')

@section('page-title', 'টিম মেম্বার এডিট করুন - ' . $teamMember->name)

@push('styles')
<style>
    /* Same styles as create.blade.php */
    .image-preview-container {
        position: relative;
        display: inline-block;
    }
    .image-preview {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .image-preview:hover {
        transform: scale(1.05);
        border-color: #0d6efd;
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
    .social-link-item {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .social-link-item:hover {
        background: #e9ecef;
        border-color: #0d6efd;
    }
    .social-link-item .btn-remove {
        color: #dc3545;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .social-link-item .btn-remove:hover {
        transform: scale(1.2);
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
    .skill-tag {
        display: inline-block;
        background: #0d6efd;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        margin: 3px;
        font-size: 12px;
        cursor: default;
    }
    .skill-tag .btn-remove-skill {
        margin-left: 5px;
        cursor: pointer;
        color: white;
        opacity: 0.7;
    }
    .skill-tag .btn-remove-skill:hover {
        opacity: 1;
    }
    .current-image {
        border: 2px solid #28a745;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-edit me-2"></i> টিম মেম্বার এডিট করুন
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.team.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> ফিরে যান
                </a>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.team.update', $teamMember->id) }}" method="POST" enctype="multipart/form-data" id="teamMemberForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-8">
                        <!-- Basic Information -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-user-circle me-2"></i> মৌলিক তথ্য
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="required-field">নাম <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $teamMember->name) }}"
                                           placeholder="পূর্ণ নাম লিখুন"
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
                                           value="{{ old('designation', $teamMember->designation) }}"
                                           placeholder="পদবি লিখুন">
                                    @error('designation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>ইমেইল</label>
                                    <input type="email" 
                                           name="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $teamMember->email) }}"
                                           placeholder="example@company.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>ফোন</label>
                                    <input type="text" 
                                           name="phone" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone', $teamMember->phone) }}"
                                           placeholder="+8801712345678">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>বায়ো</label>
                                <textarea name="bio" 
                                          class="form-control @error('bio') is-invalid @enderror" 
                                          rows="4"
                                          placeholder="টিম মেম্বারের সম্পর্কে সংক্ষিপ্ত বর্ণনা">{{ old('bio', $teamMember->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-briefcase me-2"></i> পেশাগত তথ্য
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>ডিপার্টমেন্ট</label>
                                    <input type="text" 
                                           name="department" 
                                           class="form-control @error('department') is-invalid @enderror" 
                                           value="{{ old('department', $teamMember->department) }}"
                                           placeholder="যেমন: Development, Marketing"
                                           list="departmentList">
                                    <datalist id="departmentList">
                                        @foreach($departments ?? [] as $dept)
                                            <option value="{{ $dept }}">
                                        @endforeach
                                        <option value="Management">
                                        <option value="Technology">
                                        <option value="Development">
                                        <option value="Design">
                                        <option value="Marketing">
                                        <option value="Sales">
                                        <option value="HR">
                                        <option value="Support">
                                        <option value="Finance">
                                        <option value="Operations">
                                    </datalist>
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>অভিজ্ঞতা (বছর)</label>
                                    <select name="experience" class="form-control @error('experience') is-invalid @enderror">
                                        <option value="">অভিজ্ঞতা নির্বাচন করুন</option>
                                        <option value="0-1" {{ old('experience', $teamMember->experience) == '0-1' ? 'selected' : '' }}>০-১ বছর</option>
                                        <option value="1-2" {{ old('experience', $teamMember->experience) == '1-2' ? 'selected' : '' }}>১-২ বছর</option>
                                        <option value="2-3" {{ old('experience', $teamMember->experience) == '2-3' ? 'selected' : '' }}>২-৩ বছর</option>
                                        <option value="3-5" {{ old('experience', $teamMember->experience) == '3-5' ? 'selected' : '' }}>৩-৫ বছর</option>
                                        <option value="5-7" {{ old('experience', $teamMember->experience) == '5-7' ? 'selected' : '' }}>৫-৭ বছর</option>
                                        <option value="7-10" {{ old('experience', $teamMember->experience) == '7-10' ? 'selected' : '' }}>৭-১০ বছর</option>
                                        <option value="10+" {{ old('experience', $teamMember->experience) == '10+' ? 'selected' : '' }}>১০+ বছর</option>
                                    </select>
                                    @error('experience')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>শিক্ষাগত যোগ্যতা</label>
                                    <input type="text" 
                                           name="education" 
                                           class="form-control @error('education') is-invalid @enderror" 
                                           value="{{ old('education', $teamMember->education) }}"
                                           placeholder="যেমন: BSc in Computer Science">
                                    @error('education')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>দক্ষতা (কমা দিয়ে আলাদা করুন)</label>
                                    <input type="text" 
                                           name="skills" 
                                           class="form-control @error('skills') is-invalid @enderror" 
                                           value="{{ old('skills', $teamMember->skills) }}"
                                           placeholder="যেমন: PHP, Laravel, JavaScript, Vue.js"
                                           id="skillsInput">
                                    @error('skills')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">দক্ষতা গুলো কমা (,) দিয়ে আলাদা করুন</small>
                                </div>
                            </div>
                        </div>

                        <!-- Social Links -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-share-alt me-2"></i> সোশ্যাল লিংক
                            </h5>
                            <div id="socialLinksContainer">
                                @php
                                    $socialPlatforms = [
                                        'facebook' => ['icon' => 'fab fa-facebook', 'color' => '#1877f2', 'label' => 'Facebook'],
                                        'twitter' => ['icon' => 'fab fa-twitter', 'color' => '#000000', 'label' => 'Twitter'],
                                        'linkedin' => ['icon' => 'fab fa-linkedin', 'color' => '#0a66c2', 'label' => 'LinkedIn'],
                                        'instagram' => ['icon' => 'fab fa-instagram', 'color' => '#e4405f', 'label' => 'Instagram'],
                                        'youtube' => ['icon' => 'fab fa-youtube', 'color' => '#ff0000', 'label' => 'YouTube'],
                                        'github' => ['icon' => 'fab fa-github', 'color' => '#333', 'label' => 'GitHub'],
                                        'whatsapp' => ['icon' => 'fab fa-whatsapp', 'color' => '#25d366', 'label' => 'WhatsApp'],
                                        'telegram' => ['icon' => 'fab fa-telegram', 'color' => '#0088cc', 'label' => 'Telegram'],
                                        'skype' => ['icon' => 'fab fa-skype', 'color' => '#00aff0', 'label' => 'Skype'],
                                        'website' => ['icon' => 'fas fa-globe', 'color' => '#0d6efd', 'label' => 'Website'],
                                    ];
                                    $socialLinks = old('social_links', $teamMember->social_links ?? []);
                                @endphp

                                @foreach($socialPlatforms as $platform => $data)
                                    <div class="social-link-item">
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <i class="{{ $data['icon'] }}" style="color: {{ $data['color'] }}; width: 20px;"></i>
                                                <strong>{{ $data['label'] }}</strong>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="url" 
                                                       name="social_links[{{ $platform }}]" 
                                                       class="form-control" 
                                                       value="{{ $socialLinks[$platform] ?? '' }}"
                                                       placeholder="https://{{ $platform }}.com/username">
                                            </div>
                                            <div class="col-md-1 text-center">
                                                @if(!$loop->first)
                                                    <i class="fas fa-times btn-remove" onclick="$(this).closest('.social-link-item').remove()"></i>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addSocialLink">
                                <i class="fas fa-plus"></i> আরও সোশ্যাল লিংক যোগ করুন
                            </button>
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
                                       value="{{ old('meta_title', $teamMember->meta_title) }}"
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
                                          placeholder="SEO ডেসক্রিপশন লিখুন">{{ old('meta_description', $teamMember->meta_description) }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>মেটা কীওয়ার্ড</label>
                                <input type="text" 
                                       name="meta_keywords" 
                                       class="form-control @error('meta_keywords') is-invalid @enderror" 
                                       value="{{ old('meta_keywords', $teamMember->meta_keywords) }}"
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
                                <i class="fas fa-image me-2"></i> প্রোফাইল ছবি
                            </h5>
                            <div class="text-center">
                                <div class="image-preview-container">
                                    <img src="{{ $teamMember->image_url ?? 'https://ui-avatars.com/api/?name='.urlencode($teamMember->name).'&size=150&background=6366f1&color=fff&rounded=true' }}" 
                                         alt="Profile Preview" 
                                         class="image-preview {{ $teamMember->image ? 'current-image' : '' }}"
                                         id="imagePreview">
                                    <label class="image-upload-btn">
                                        <i class="fas fa-camera"></i>
                                        <input type="file" 
                                               name="image" 
                                               id="imageInput" 
                                               accept="image/*">
                                    </label>
                                </div>
                                @if($teamMember->image)
                                    <div class="mt-2">
                                        <small class="text-success">
                                            <i class="fas fa-check-circle"></i> বর্তমান ছবি আছে
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i> 
                                            নতুন ছবি আপলোড করলে পুরনো ছবি প্রতিস্থাপিত হবে
                                        </small>
                                    </div>
                                @endif
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle"></i> 
                                    সর্বোচ্চ ২ এমবি, JPEG, PNG, JPG, WEBP
                                </small>
                                @error('image')
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
                                       {{ old('is_active', $teamMember->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusSwitch">
                                    <span id="statusLabel">{{ old('is_active', $teamMember->is_active) ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}</span>
                                </label>
                            </div>
                            <small class="text-muted">টিম মেম্বারকে সক্রিয় বা নিষ্ক্রিয় করুন</small>
                        </div>

                        <!-- Preview Card -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-eye me-2"></i> প্রিভিউ
                            </h5>
                            <div class="card">
                                <div class="card-body text-center">
                                    <img src="{{ $teamMember->image_url ?? 'https://ui-avatars.com/api/?name='.urlencode($teamMember->name).'&size=80&background=6366f1&color=fff&rounded=true' }}" 
                                         alt="Preview" 
                                         class="rounded-circle mb-2"
                                         style="width: 80px; height: 80px; object-fit: cover;"
                                         id="previewImage">
                                    <h6 id="previewName">{{ $teamMember->name }}</h6>
                                    <p class="text-muted small" id="previewDesignation">{{ $teamMember->designation ?? 'ডিজাইনেশন' }}</p>
                                    <div id="previewSocial">
                                        <!-- Social preview will be added here -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="form-section">
                            <button type="submit" class="btn btn-primary w-100 mb-2" id="submitBtn">
                                <i class="fas fa-save"></i> টিম মেম্বার আপডেট করুন
                            </button>
                            <a href="{{ route('admin.team.index') }}" class="btn btn-secondary w-100">
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
    // Same scripts as create.blade.php with slight modifications
    
    // Image Preview
    $('#imageInput').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
                $('#previewImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    // Live Preview Update
    $('input[name="name"]').on('keyup', function() {
        const name = $(this).val() || 'নাম';
        $('#previewName').text(name);
        if (!$('#imageInput').get(0).files.length) {
            const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&size=80&background=6366f1&color=fff&rounded=true`;
            $('#previewImage').attr('src', avatarUrl);
        }
    });

    $('input[name="designation"]').on('keyup', function() {
        const designation = $(this).val() || 'ডিজাইনেশন';
        $('#previewDesignation').text(designation);
    });

    // Status Switch
    $('#statusSwitch').change(function() {
        if ($(this).is(':checked')) {
            $('#statusLabel').text('সক্রিয়');
        } else {
            $('#statusLabel').text('নিষ্ক্রিয়');
        }
    });

    // Add Social Link
    let socialLinkCount = {{ count($socialLinks ?? []) }};
    $('#addSocialLink').click(function() {
        socialLinkCount++;
        const html = `
            <div class="social-link-item">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <i class="fas fa-link" style="width: 20px;"></i>
                        <strong>সোশ্যাল লিংক ${socialLinkCount}</strong>
                    </div>
                    <div class="col-md-8">
                        <input type="url" 
                               name="social_links[custom_${socialLinkCount}]" 
                               class="form-control" 
                               placeholder="https://example.com/username">
                    </div>
                    <div class="col-md-1 text-center">
                        <i class="fas fa-times btn-remove" onclick="$(this).closest('.social-link-item').remove()"></i>
                    </div>
                </div>
            </div>
        `;
        $('#socialLinksContainer').append(html);
    });

    // Form Validation
    $('#teamMemberForm').on('submit', function(e) {
        const name = $('input[name="name"]').val().trim();
        if (!name) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'ত্রুটি!',
                text: 'দয়া করে টিম মেম্বারের নাম দিন',
                confirmButtonColor: '#0d6efd'
            });
            $('input[name="name"]').focus();
            return false;
        }
        return true;
    });

    // Toastr Configuration
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif
});
</script>
@endpush