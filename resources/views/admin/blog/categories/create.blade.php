{{-- resources/views/admin/blog/categories/create.blade.php --}}

@extends('admin.layouts.master')

@section('page-title', 'নতুন ব্লগ ক্যাটাগরি যোগ করুন')

@push('styles')
<style>
    .form-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #0d6efd;
        transition: all 0.3s ease;
    }
    .form-section:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
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
    .image-preview-container {
        position: relative;
        display: inline-block;
    }
    .image-preview {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
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
    .slug-preview {
        font-size: 13px;
        color: #6c757d;
        margin-top: 4px;
        padding: 6px 12px;
        background: #f8f9fa;
        border-radius: 4px;
        border: 1px solid #e9ecef;
    }
    .slug-preview .slug-text {
        color: #0d6efd;
        font-weight: 500;
    }
    .character-count {
        font-size: 12px;
        color: #6c757d;
        transition: color 0.3s ease;
    }
    .character-count.warning {
        color: #ffc107;
    }
    .character-count.danger {
        color: #dc3545;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-plus-circle me-2"></i> নতুন ব্লগ ক্যাটাগরি যোগ করুন
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.blog.categories.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> ফিরে যান
                    </a>
                    <a href="{{ route('admin.blog.categories.index') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-list"></i> তালিকা দেখুন
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.blog.categories.store') }}" method="POST" enctype="multipart/form-data" id="categoryForm">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <!-- Category Information -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-info-circle me-2"></i> ক্যাটাগরি তথ্য
                            </h5>
                            <div class="mb-3">
                                <label class="required-field">ক্যাটাগরি নাম <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="name"
                                       id="categoryName"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}"
                                       placeholder="ক্যাটাগরি নাম লিখুন"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">শুধু ইংরেজি অক্ষর, সংখ্যা ও স্পেস</small>
                            </div>

                            <div class="mb-3">
                                <label>স্লাগ</label>
                                <input type="text"
                                       name="slug"
                                       id="categorySlug"
                                       class="form-control @error('slug') is-invalid @enderror"
                                       value="{{ old('slug') }}"
                                       placeholder="স্লাগ লিখুন (ঐচ্ছিক)">
                                <div class="slug-preview">
                                    <i class="fas fa-link"></i>
                                    URL: <span class="slug-text" id="slugPreview">{{ url('/blog/categories') }}/</span>
                                </div>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>বিবরণ</label>
                                <textarea name="description"
                                          id="categoryDescription"
                                          class="form-control @error('description') is-invalid @enderror"
                                          rows="4"
                                          placeholder="ক্যাটাগরির বিবরণ লিখুন">{{ old('description') }}</textarea>
                                <div class="d-flex justify-content-end">
                                    <span class="character-count" id="descCount">0/500</span>
                                </div>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>মেটা কীওয়ার্ড</label>
                                <input type="text"
                                       name="meta_keywords"
                                       class="form-control @error('meta_keywords') is-invalid @enderror"
                                       value="{{ old('meta_keywords') }}"
                                       placeholder="SEO কীওয়ার্ড গুলো কমা দিয়ে আলাদা করুন">
                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                       value="{{ old('meta_title') }}"
                                       placeholder="SEO টাইটেল লিখুন (৬০ অক্ষরের মধ্যে)">
                                <small class="text-muted">সর্বোচ্চ ৬০ অক্ষর</small>
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>মেটা ডেসক্রিপশন</label>
                                <textarea name="meta_description"
                                          class="form-control @error('meta_description') is-invalid @enderror"
                                          rows="2"
                                          placeholder="SEO ডেসক্রিপশন লিখুন (১৬০ অক্ষরের মধ্যে)">{{ old('meta_description') }}</textarea>
                                <small class="text-muted">সর্বোচ্চ ১৬০ অক্ষর</small>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Image -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-image me-2"></i> ক্যাটাগরি ছবি
                            </h5>
                            <div class="text-center">
                                <div class="image-preview-container">
                                    <img src="{{ asset('images/default-category.jpg') }}"
                                         alt="Category Image"
                                         class="image-preview"
                                         id="imagePreview">
                                    <label class="image-upload-btn" title="ছবি আপলোড করুন">
                                        <i class="fas fa-camera"></i>
                                        <input type="file"
                                               name="image"
                                               id="imageInput"
                                               accept="image/*">
                                    </label>
                                </div>
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
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox"
                                       name="status"
                                       class="form-check-input"
                                       id="statusSwitch"
                                       value="1"
                                       {{ old('status', '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusSwitch">
                                    <span id="statusLabel">{{ old('status', '1') == '1' ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}</span>
                                </label>
                            </div>
                            <small class="text-muted">ক্যাটাগরি সক্রিয় বা নিষ্ক্রিয় করুন</small>
                        </div>

                        <!-- Sort Order -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-sort me-2"></i> সর্ট অর্ডার
                            </h5>
                            <input type="number"
                                   name="sort_order"
                                   class="form-control"
                                   value="{{ old('sort_order', 0) }}"
                                   min="0">
                            <small class="text-muted">ছোট সংখ্যা আগে দেখাবে</small>
                        </div>

                        <!-- Preview Card -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-eye me-2"></i> প্রিভিউ
                            </h5>
                            <div class="card">
                                <div class="card-body text-center">
                                    <img src="{{ asset('images/default-category.jpg') }}"
                                         alt="Preview"
                                         class="rounded-circle mb-2"
                                         style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #e9ecef;"
                                         id="previewImage">
                                    <h6 id="previewName">ক্যাটাগরি নাম</h6>
                                    <p class="text-muted small" id="previewDescription">বিবরণ এখানে দেখা যাবে</p>
                                    <span class="badge bg-success" id="previewStatus">সক্রিয়</span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="form-section">
                            <button type="submit" class="btn btn-primary w-100 mb-2" id="submitBtn">
                                <i class="fas fa-save"></i> ক্যাটাগরি তৈরি করুন
                            </button>
                            <a href="{{ route('admin.blog.categories.index') }}" class="btn btn-secondary w-100">
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
    // Image Preview
    // ============================================
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

    // ============================================
    // Slug Auto-generate
    // ============================================
    $('#categoryName').on('keyup', function() {
        var slugInput = $('#categorySlug');
        var slugPreview = $('#slugPreview');

        if (slugInput.val() === '') {
            var slug = $(this).val()
                .toLowerCase()
                .replace(/[^a-z0-9-]/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
            slugInput.val(slug);
            slugPreview.text('{{ url('/blog/categories') }}/' + slug);
        }
    });

    $('#categorySlug').on('keyup', function() {
        var slug = $(this).val()
            .toLowerCase()
            .replace(/[^a-z0-9-]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
        $(this).val(slug);
        $('#slugPreview').text('{{ url('/blog/categories') }}/' + slug);
    });

    // ============================================
    // Live Preview
    // ============================================
    $('#categoryName').on('keyup', function() {
        var name = $(this).val() || 'ক্যাটাগরি নাম';
        $('#previewName').text(name);
    });

    $('#categoryDescription').on('keyup', function() {
        var desc = $(this).val() || 'বিবরণ এখানে দেখা যাবে';
        $('#previewDescription').text(desc);
    });

    // ============================================
    // Character Count for Description
    // ============================================
    $('#categoryDescription').on('keyup', function() {
        var count = $(this).val().length;
        var max = 500;
        var $countEl = $('#descCount');
        $countEl.text(count + '/' + max);

        if (count > max) {
            $countEl.removeClass('warning').addClass('danger');
        } else if (count > (max * 0.8)) {
            $countEl.removeClass('danger').addClass('warning');
        } else {
            $countEl.removeClass('warning danger');
        }
    });

    // ============================================
    // Status Switch
    // ============================================
    $('#statusSwitch').change(function() {
        if ($(this).is(':checked')) {
            $('#statusLabel').text('সক্রিয়');
            $('#previewStatus').removeClass('bg-danger').addClass('bg-success').text('সক্রিয়');
        } else {
            $('#statusLabel').text('নিষ্ক্রিয়');
            $('#previewStatus').removeClass('bg-success').addClass('bg-danger').text('নিষ্ক্রিয়');
        }
    });

    // ============================================
    // Form Validation
    // ============================================
    $('#categoryForm').on('submit', function(e) {
        var name = $('#categoryName').val().trim();

        if (!name) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'ত্রুটি!',
                text: 'দয়া করে ক্যাটাগরির নাম দিন',
                confirmButtonColor: '#0d6efd'
            });
            $('#categoryName').focus();
            return false;
        }

        return true;
    });

    // ============================================
    // Keyboard Shortcuts
    // ============================================
    $(document).on('keydown', function(e) {
        // Ctrl+S to submit form
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            $('#categoryForm').submit();
        }
        // Ctrl+Shift+F to focus on name field
        if (e.ctrlKey && e.shiftKey && e.key === 'F') {
            e.preventDefault();
            $('#categoryName').focus();
        }
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
