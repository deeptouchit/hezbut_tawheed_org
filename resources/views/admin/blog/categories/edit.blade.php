{{-- resources/views/admin/blog/categories/edit.blade.php --}}

@extends('admin.layouts.master')

@section('page-title', 'ব্লগ ক্যাটাগরি এডিট করুন - ' . $category->name)

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
        background: #dc3545;
        color: white;
        border: none;
        padding: 5px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .remove-image-btn:hover {
        background: #c82333;
        transform: scale(1.05);
    }
    .blog-count-badge {
        display: inline-block;
        background: #0d6efd;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 14px;
        margin-bottom: 10px;
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
    .preview-card {
        background: #fff;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
    }
    .preview-card .preview-image {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e9ecef;
        margin-bottom: 10px;
    }
    .preview-card .preview-name {
        font-weight: 600;
        font-size: 18px;
        margin-bottom: 5px;
    }
    .preview-card .preview-description {
        color: #6c757d;
        font-size: 14px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-edit me-2"></i> ব্লগ ক্যাটাগরি এডিট করুন
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.blog.categories.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> ফিরে যান
                    </a>
                    <a href="{{ route('admin.blog.categories.show', $category->id) }}" class="btn btn-info btn-sm" target="_blank">
                        <i class="fas fa-eye"></i> বিস্তারিত দেখুন
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.blog.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" id="categoryForm">
                @csrf
                @method('PUT')

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
                                       value="{{ old('name', $category->name) }}"
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
                                       value="{{ old('slug', $category->slug) }}"
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
                                          placeholder="ক্যাটাগরির বিবরণ লিখুন">{{ old('description', $category->description) }}</textarea>
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
                                       value="{{ old('meta_keywords', $category->meta_keywords) }}"
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
                                       value="{{ old('meta_title', $category->meta_title) }}"
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
                                          placeholder="SEO ডেসক্রিপশন লিখুন (১৬০ অক্ষরের মধ্যে)">{{ old('meta_description', $category->meta_description) }}</textarea>
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
                                    <img src="{{ $category->image_url }}"
                                         alt="{{ $category->name }}"
                                         class="image-preview {{ $category->image ? 'current-image' : '' }}"
                                         id="imagePreview">
                                    <label class="image-upload-btn" title="ছবি আপলোড করুন">
                                        <i class="fas fa-camera"></i>
                                        <input type="file"
                                               name="image"
                                               id="imageInput"
                                               accept="image/*">
                                    </label>
                                </div>

                                @if($category->image)
                                    <div class="mt-2">
                                        <span class="current-image-label">
                                            <i class="fas fa-check-circle"></i> বর্তমান ছবি আছে
                                        </span>
                                        <br>
                                        <button type="button" class="remove-image-btn" id="removeImageBtn">
                                            <i class="fas fa-trash"></i> ছবি মুছুন
                                        </button>
                                        <input type="hidden" name="remove_image" id="removeImage" value="0">
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

                        <!-- Blog Count (Read Only) -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-file-alt me-2"></i> ব্লগ তথ্য
                            </h5>
                            <div class="text-center">
                                <span class="blog-count-badge">
                                    <i class="fas fa-file-alt"></i>
                                    {{ $category->blogs_count ?? 0 }} টি ব্লগ
                                </span>
                                <p class="text-muted small mt-2">
                                    এই ক্যাটাগরির অধীনে থাকা ব্লগ পোস্টের সংখ্যা
                                </p>
                                @if(($category->blogs_count ?? 0) > 0)
                                    <div class="alert alert-warning alert-sm mt-2">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        ক্যাটাগরি ডিলিট করতে চাইলে প্রথমে ব্লগ পোস্ট গুলো সরান বা অন্য ক্যাটাগরিতে স্থানান্তর করুন।
                                    </div>
                                @endif
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
                                       {{ old('status', $category->status) ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusSwitch">
                                    <span id="statusLabel">{{ old('status', $category->status) ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}</span>
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
                                   class="form-control @error('sort_order') is-invalid @enderror"
                                   value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                                   min="0">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">ছোট সংখ্যা আগে দেখাবে</small>
                        </div>

                        <!-- Preview Card -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-eye me-2"></i> প্রিভিউ
                            </h5>
                            <div class="preview-card">
                                <img src="{{ $category->image_url }}"
                                     alt="{{ $category->name }}"
                                     class="preview-image"
                                     id="previewImage">
                                <div class="preview-name" id="previewName">{{ $category->name }}</div>
                                <div class="preview-description" id="previewDescription">
                                    {{ $category->description ?? 'বিবরণ নেই' }}
                                </div>
                                <div class="mt-2">
                                    <span class="badge {{ $category->status ? 'bg-success' : 'bg-danger' }}" id="previewStatus">
                                        {{ $category->status ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Meta Information -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-clock me-2"></i> মেটা তথ্য
                            </h5>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">তৈরি হয়েছে:</small>
                                    <p class="mb-0">{{ $category->created_at?->format('d M, Y h:i A') ?? 'N/A' }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">শেষ আপডেট:</small>
                                    <p class="mb-0">{{ $category->updated_at?->format('d M, Y h:i A') ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="form-section">
                            <button type="submit" class="btn btn-primary w-100 mb-2" id="submitBtn">
                                <i class="fas fa-save"></i> ক্যাটাগরি আপডেট করুন
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
                $('#imagePreview').addClass('current-image');
                $('#previewImage').attr('src', e.target.result);
                // Hide remove button if image uploaded
                $('#removeImageBtn').hide();
                $('.current-image-label').hide();
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
            text: 'ক্যাটাগরির ছবি মুছে ফেলতে চান?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'হ্যাঁ, মুছুন',
            cancelButtonText: 'বাতিল'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#removeImage').val('1');
                var defaultImage = '{{ asset("images/default-category.jpg") }}';
                $('#imagePreview').attr('src', defaultImage);
                $('#previewImage').attr('src', defaultImage);
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
    // Slug Auto-generate
    // ============================================
    $('#categoryName').on('keyup', function() {
        var slugInput = $('#categorySlug');
        var slugPreview = $('#slugPreview');

        if (slugInput.val() === '' || slugInput.val() === '{{ $category->slug }}') {
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
        var name = $(this).val() || '{{ $category->name }}';
        $('#previewName').text(name);
    });

    $('#categoryDescription').on('keyup', function() {
        var desc = $(this).val() || '{{ $category->description ?? 'বিবরণ নেই' }}';
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

    // Initial character count
    var initialCount = $('#categoryDescription').val().length;
    $('#descCount').text(initialCount + '/500');

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
