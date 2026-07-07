{{-- resources/views/admin/blog/edit.blade.php --}}

@extends('admin.layouts.master')

@section('page-title', 'ব্লগ পোস্ট এডিট করুন - ' . $blog->title)

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
        width: 100%;
        max-height: 250px;
        object-fit: cover;
        border-radius: 8px;
        border: 3px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .image-preview:hover {
        transform: scale(1.02);
        border-color: #0d6efd;
    }
    .image-preview.current-image {
        border-color: #28a745;
    }
    .image-upload-btn {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: #0d6efd;
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
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
    .tag-input-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 8px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        min-height: 45px;
        background: #fff;
    }
    .tag-item {
        display: inline-flex;
        align-items: center;
        background: #0d6efd;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 13px;
        gap: 6px;
    }
    .tag-item .remove-tag {
        cursor: pointer;
        opacity: 0.7;
        transition: all 0.3s ease;
    }
    .tag-item .remove-tag:hover {
        opacity: 1;
        transform: scale(1.2);
    }
    .tag-input {
        border: none;
        outline: none;
        flex: 1;
        min-width: 100px;
        padding: 4px 8px;
    }
    .tag-input:focus {
        outline: none;
    }
    .tag-suggestions {
        position: absolute;
        background: white;
        border: 1px solid #ced4da;
        border-radius: 4px;
        max-height: 200px;
        overflow-y: auto;
        width: 100%;
        z-index: 1000;
        display: none;
    }
    .tag-suggestions .suggestion-item {
        padding: 8px 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .tag-suggestions .suggestion-item:hover {
        background: #0d6efd;
        color: white;
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
    .blog-meta-info {
        font-size: 13px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 5px;
    }
    .blog-meta-info .label {
        font-weight: 600;
        color: #495057;
    }
    .view-count-display {
        display: inline-block;
        background: #e9ecef;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 12px;
        color: #495057;
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
    .preview-card .preview-title {
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 5px;
    }
    .preview-card .preview-description {
        color: #6c757d;
        font-size: 13px;
    }
    /* CKEditor customization */
    .ck-editor__editable {
        min-height: 350px !important;
        border-radius: 0 0 4px 4px !important;
    }
    .ck-editor__top {
        border-radius: 4px 4px 0 0 !important;
    }
    #charCount {
        font-weight: 600;
        transition: color 0.3s ease;
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
                <i class="fas fa-edit me-2"></i> ব্লগ পোস্ট এডিট করুন
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> ফিরে যান
                    </a>
                    <a href="{{ route('admin.blog.posts.show', $blog->id) }}" class="btn btn-success btn-sm" target="_blank">
                        <i class="fas fa-eye"></i> বিস্তারিত দেখুন
                    </a>
                    <a href="{{ $blog->url }}" target="_blank" class="btn btn-info btn-sm">
                        <i class="fas fa-external-link-alt"></i> লাইভ দেখুন
                    </a>
                    <button class="btn btn-danger btn-sm delete-blog" data-id="{{ $blog->id }}" data-title="{{ $blog->title }}">
                        <i class="fas fa-trash"></i> ডিলিট
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.blog.posts.update', $blog->id) }}" method="POST" enctype="multipart/form-data" id="blogForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-8">
                        <!-- Blog Information -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-info-circle me-2"></i> ব্লগ তথ্য
                            </h5>

                            <div class="mb-3">
                                <label class="required-field">শিরোনাম <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="title"
                                       id="title"
                                       class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $blog->title) }}"
                                       placeholder="ব্লগ পোস্টের শিরোনাম লিখুন"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>স্লাগ</label>
                                <input type="text"
                                       name="slug"
                                       id="slug"
                                       class="form-control @error('slug') is-invalid @enderror"
                                       value="{{ old('slug', $blog->slug) }}"
                                       placeholder="স্লাগ লিখুন (ঐচ্ছিক)">
                                <div class="slug-preview">
                                    <i class="fas fa-link"></i>
                                    পোস্ট URL: <span class="slug-text" id="slugPreview">{{ url('/blog') }}/</span>
                                </div>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>সংক্ষিপ্ত বিবরণ</label>
                                <textarea name="short_description"
                                          id="shortDescription"
                                          class="form-control @error('short_description') is-invalid @enderror"
                                          rows="3"
                                          placeholder="ব্লগ পোস্টের সংক্ষিপ্ত বিবরণ লিখুন">{{ old('short_description', $blog->short_description) }}</textarea>
                                <div class="d-flex justify-content-end">
                                    <span class="character-count" id="descCount">0/500</span>
                                </div>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ===== BLOG CONTENT WITH CKEDITOR ===== -->
                            <div class="mb-3">
                                <label class="required-field">ব্লগ কন্টেন্ট <span class="text-danger">*</span></label>

                                {{-- CKEditor will be applied to this textarea --}}
                                <textarea name="content"
                                          id="editor"
                                          class="form-control @error('content') is-invalid @enderror"
                                          placeholder="ব্লগ পোস্টের সম্পূর্ণ কন্টেন্ট লিখুন...">{{ old('content', $blog->content) }}</textarea>

                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        Rich Text Editor। টুলবার ব্যবহার করে ফরম্যাট করুন।
                                    </small>
                                    <small class="text-muted">
                                        <span id="charCount">0</span> অক্ষর
                                    </small>
                                </div>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- ===== END BLOG CONTENT ===== -->
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
                                       value="{{ old('meta_title', $blog->meta_title) }}"
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
                                          placeholder="SEO ডেসক্রিপশন লিখুন (১৬০ অক্ষরের মধ্যে)">{{ old('meta_description', $blog->meta_description) }}</textarea>
                                <small class="text-muted">সর্বোচ্চ ১৬০ অক্ষর</small>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>মেটা কীওয়ার্ড</label>
                                <input type="text"
                                       name="meta_keywords"
                                       class="form-control @error('meta_keywords') is-invalid @enderror"
                                       value="{{ old('meta_keywords', $blog->meta_keywords) }}"
                                       placeholder="SEO কীওয়ার্ড গুলো কমা দিয়ে আলাদা করুন">
                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-4">
                        <!-- Featured Image -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-image me-2"></i> ফিচার্ড ইমেজ
                            </h5>
                            <div class="text-center">
                                <div class="image-preview-container">
                                    <img src="{{ $blog->featured_image_url }}"
                                         alt="{{ $blog->title }}"
                                         class="image-preview {{ $blog->featured_image ? 'current-image' : '' }}"
                                         id="imagePreview">
                                    <label class="image-upload-btn" title="ছবি আপলোড করুন">
                                        <i class="fas fa-camera"></i>
                                        <input type="file"
                                               name="featured_image"
                                               id="imageInput"
                                               accept="image/*">
                                    </label>
                                </div>

                                @if($blog->featured_image)
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
                                    সর্বোচ্চ ৫ এমবি, JPEG, PNG, JPG, WEBP
                                </small>
                                @error('featured_image')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Blog Meta Info -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-chart-bar me-2"></i> পোস্ট তথ্য
                            </h5>
                            <div class="blog-meta-info">
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <span class="label"><i class="fas fa-eye"></i> ভিউ:</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="view-count-display">{{ number_format($blog->views ?? 0) }}</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <span class="label"><i class="fas fa-comments"></i> মন্তব্য:</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="view-count-display">{{ $blog->approvedComments()->count() ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <span class="label"><i class="fas fa-clock"></i> তৈরি:</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted">{{ $blog->created_at?->format('d M, Y h:i A') ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <span class="label"><i class="fas fa-edit"></i> আপডেট:</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted">{{ $blog->updated_at?->format('d M, Y h:i A') ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-tags me-2"></i> ক্যাটাগরি
                            </h5>
                            <select name="category_id" id="categorySelect" class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">ক্যাটাগরি নির্বাচন করুন</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $blog->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tags -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-tag me-2"></i> ট্যাগ
                            </h5>
                            <div class="tag-input-container" id="tagContainer">
                                <div id="tagList" class="d-flex flex-wrap gap-1">
                                    @if(old('tags', $blog->tags_array))
                                        @foreach(old('tags', $blog->tags_array) as $tag)
                                            @if($tag)
                                                <span class="tag-item">
                                                    {{ $tag }}
                                                    <span class="remove-tag" onclick="removeTag(this)">&times;</span>
                                                </span>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                <input type="text"
                                       class="tag-input"
                                       id="tagInput"
                                       placeholder="ট্যাগ লিখুন এবং Enter চাপুন..."
                                       autocomplete="off">
                                <div class="tag-suggestions" id="tagSuggestions"></div>
                            </div>
                            <div id="tagHiddenInputs">
                                @if(old('tags', $blog->tags_array))
                                    @foreach(old('tags', $blog->tags_array) as $tag)
                                        @if($tag)
                                            <input type="hidden" name="tags[]" value="{{ $tag }}">
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <small class="text-muted">ট্যাগ লিখে Enter চাপুন, প্রয়োজনীয় ট্যাগ যোগ করুন</small>
                            @error('tags')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Author -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-user me-2"></i> লেখক
                            </h5>
                            <select name="author_id" class="form-select @error('author_id') is-invalid @enderror" required>
                                <option value="">লেখক নির্বাচন করুন</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" {{ old('author_id', $blog->author_id) == $author->id ? 'selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('author_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                       {{ old('status', $blog->status) ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusSwitch">
                                    <span id="statusLabel">{{ old('status', $blog->status) ? 'প্রকাশিত' : 'খসড়া' }}</span>
                                </label>
                            </div>
                            <small class="text-muted">পোস্ট প্রকাশিত বা খসড়া হিসেবে সংরক্ষণ করুন</small>
                        </div>

                        <!-- Gallery Switch -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-image me-2"></i> গ্যালারিতে দেখান
                            </h5>
                            <div class="form-check form-switch">
                                <input type="checkbox"
                                       name="is_gallery"
                                       class="form-check-input"
                                       id="isGallerySwitch"
                                       value="1"
                                       {{ old('is_gallery', $blog->is_gallery) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isGallerySwitch">
                                    <span id="galleryLabel">{{ old('is_gallery', $blog->is_gallery) ? 'যুক্ত' : 'যুক্ত নয়' }}</span>
                                </label>
                            </div>
                            <small class="text-muted">এই পোস্টের Featured Image হোমপেজের চিত্রশালা গ্যালারিতে প্রদর্শন করুন</small>
                        </div>

                        <!-- Gallery Order -->
                        <div class="form-section" id="galleryOrderSection" style="{{ old('is_gallery', $blog->is_gallery) ? '' : 'display: none;' }}">
                            <h5 class="form-section-title">
                                <i class="fas fa-sort-numeric-down me-2"></i> গ্যালারি সর্ট অর্ডার
                            </h5>
                            <input type="number"
                                   name="gallery_order"
                                   class="form-control"
                                   value="{{ old('gallery_order', $blog->gallery_order ?? 0) }}">
                            <small class="text-muted">গ্যালারিতে প্রদর্শনের ক্রম নির্ধারণ করুন (ছোট ক্রম আগে দেখাবে)</small>
                        </div>

                        <!-- Publish Date -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-calendar-alt me-2"></i> প্রকাশের তারিখ
                            </h5>
                            <input type="datetime-local"
                                   name="published_at"
                                   class="form-control @error('published_at') is-invalid @enderror"
                                   value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                            @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">খালি রাখলে বর্তমান সময় সেট হবে</small>
                        </div>

                        <!-- Sort Order -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-sort me-2"></i> সর্ট অর্ডার
                            </h5>
                            <input type="number"
                                   name="sort_order"
                                   class="form-control"
                                   value="{{ old('sort_order', $blog->sort_order ?? 0) }}"
                                   min="0">
                            <small class="text-muted">ছোট সংখ্যা আগে দেখাবে</small>
                        </div>

                        <!-- Preview Card -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-eye me-2"></i> প্রিভিউ
                            </h5>
                            <div class="preview-card">
                                <img src="{{ $blog->featured_image_url }}"
                                     alt="Preview"
                                     class="preview-image"
                                     id="previewImage">
                                <div class="preview-title" id="previewTitle">{{ $blog->title }}</div>
                                <div class="preview-description" id="previewDescription">
                                    {{ $blog->short_description ?? 'সংক্ষিপ্ত বিবরণ এখানে দেখা যাবে' }}
                                </div>
                                <div class="mt-2">
                                    <span class="badge {{ $blog->status ? 'bg-success' : 'bg-warning' }}" id="previewStatus">
                                        {{ $blog->status ? 'প্রকাশিত' : 'খসড়া' }}
                                    </span>
                                    <span class="badge bg-info" id="previewCategory">
                                        {{ $blog->category?->name ?? 'ক্যাটাগরি' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="form-section">
                            <button type="submit" class="btn btn-primary w-100 mb-2" id="submitBtn">
                                <i class="fas fa-save"></i> ব্লগ পোস্ট আপডেট করুন
                            </button>
                            <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-secondary w-100">
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
{{-- CKEditor 5 CDN --}}
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

<script>
$(document).ready(function() {
    // ============================================
    // CKEditor 5 - SINGLE INSTANCE
    // ============================================
    let editorInstance = null;

    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: [
                'heading',
                '|',
                'bold',
                'italic',
                'underline',
                'strikethrough',
                '|',
                'bulletedList',
                'numberedList',
                '|',
                'blockQuote',
                'link',
                '|',
                'undo',
                'redo'
            ],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            },
            placeholder: 'ব্লগ পোস্টের সম্পূর্ণ কন্টেন্ট লিখুন...',
            removePlugins: ['CKFinderUploadAdapter', 'CKFinder', 'EasyImage', 'Image', 'ImageCaption', 'ImageStyle', 'ImageToolbar', 'ImageUpload', 'MediaEmbed'],
        })
        .then(editor => {
            editorInstance = editor;

            // ============================================
            // Character Counter
            // ============================================
            editor.model.document.on('change:data', function() {
                const content = editor.getData();
                const plainText = content.replace(/<[^>]*>/g, '');
                const charCount = document.getElementById('charCount');
                if (charCount) {
                    const count = plainText.length;
                    charCount.textContent = count.toLocaleString();

                    if (count > 5000) {
                        charCount.style.color = '#dc3545';
                    } else if (count > 3000) {
                        charCount.style.color = '#ffc107';
                    } else {
                        charCount.style.color = '#6c757d';
                    }
                }
            });

            // Initial character count
            setTimeout(function() {
                const content = editor.getData();
                const plainText = content.replace(/<[^>]*>/g, '');
                const charCount = document.getElementById('charCount');
                if (charCount) {
                    charCount.textContent = plainText.length.toLocaleString();
                }
            }, 500);

            console.log('✅ CKEditor initialized successfully');
        })
        .catch(error => {
            console.error('❌ CKEditor initialization failed:', error);
        });

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
                $('#imagePreview').addClass('current-image');
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
            text: 'ফিচার্ড ইমেজ মুছে ফেলতে চান?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'হ্যাঁ, মুছুন',
            cancelButtonText: 'বাতিল'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#removeImage').val('1');
                var defaultImage = '{{ asset("images/default-blog.jpg") }}';
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
    $('#title').on('keyup', function() {
        var slugInput = $('#slug');
        var slugPreview = $('#slugPreview');

        if (slugInput.val() === '' || slugInput.val() === '{{ $blog->slug }}') {
            var slug = $(this).val()
                .toLowerCase()
                .replace(/[^a-z0-9-]/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
            slugInput.val(slug);
            slugPreview.text('{{ url('/blog') }}/' + slug);
        }
    });

    $('#slug').on('keyup', function() {
        var slug = $(this).val()
            .toLowerCase()
            .replace(/[^a-z0-9-]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
        $(this).val(slug);
        $('#slugPreview').text('{{ url('/blog') }}/' + slug);
    });

    // ============================================
    // Live Preview
    // ============================================
    $('#title').on('keyup', function() {
        var title = $(this).val() || '{{ $blog->title }}';
        $('#previewTitle').text(title);
    });

    $('#shortDescription').on('keyup', function() {
        var desc = $(this).val() || '{{ $blog->short_description ?? 'সংক্ষিপ্ত বিবরণ এখানে দেখা যাবে' }}';
        $('#previewDescription').text(desc);
    });

    // ============================================
    // Character Count for Short Description
    // ============================================
    $('#shortDescription').on('keyup', function() {
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
    var initialCount = $('#shortDescription').val().length;
    $('#descCount').text(initialCount + '/500');

    // ============================================
    // Category Preview
    // ============================================
    $('#categorySelect').on('change', function() {
        var category = $(this).find('option:selected').text();
        if (category && category !== 'ক্যাটাগরি নির্বাচন করুন') {
            $('#previewCategory').text(category).show();
        } else {
            $('#previewCategory').text('ক্যাটাগরি');
        }
    });

    // ============================================
    // Status Switch
    // ============================================
    $('#statusSwitch').change(function() {
        if ($(this).is(':checked')) {
            $('#statusLabel').text('প্রকাশিত');
            $('#previewStatus').removeClass('bg-warning').addClass('bg-success').text('প্রকাশিত');
        } else {
            $('#statusLabel').text('খসড়া');
            $('#previewStatus').removeClass('bg-success').addClass('bg-warning').text('খসড়া');
        }
    });

    // ============================================
    // Gallery Switch
    // ============================================
    $('#isGallerySwitch').change(function() {
        if ($(this).is(':checked')) {
            $('#galleryLabel').text('যুক্ত');
            $('#galleryOrderSection').slideDown();
        } else {
            $('#galleryLabel').text('যুক্ত নয়');
            $('#galleryOrderSection').slideUp();
        }
    });

    // ============================================
    // Tags Management
    // ============================================
    const tagInput = $('#tagInput');
    const tagList = $('#tagList');
    const tagHiddenInputs = $('#tagHiddenInputs');
    const existingTags = {!! json_encode($tags ?? []) !!};

    tagInput.on('keyup', function() {
        const value = $(this).val().trim().toLowerCase();
        if (value.length > 0) {
            const suggestions = existingTags.filter(tag =>
                tag.toLowerCase().includes(value)
            );
            showSuggestions(suggestions);
        } else {
            $('#tagSuggestions').hide();
        }
    });

    function showSuggestions(suggestions) {
        const container = $('#tagSuggestions');
        container.empty().show();

        if (suggestions.length === 0) {
            container.hide();
            return;
        }

        suggestions.slice(0, 10).forEach(tag => {
            container.append(`
                <div class="suggestion-item" onclick="addTag('${tag}')">
                    <i class="fas fa-tag"></i> ${tag}
                </div>
            `);
        });
    }

    tagInput.on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const tag = $(this).val().trim();
            if (tag) {
                addTag(tag);
                $(this).val('');
                $('#tagSuggestions').hide();
            }
        }
    });

    tagInput.on('blur', function() {
        setTimeout(() => {
            $('#tagSuggestions').hide();
        }, 200);
    });

    window.addTag = function(tag) {
        if (!tag) return;

        const existingTags = $('.tag-item').map(function() {
            return $(this).text().trim().replace('×', '').trim();
        }).get();

        if (existingTags.includes(tag)) {
            toastr.warning('এই ট্যাগটি ইতিমধ্যে যোগ করা হয়েছে!');
            return;
        }

        tagList.append(`
            <span class="tag-item">
                ${tag}
                <span class="remove-tag" onclick="removeTag(this)">&times;</span>
            </span>
        `);

        tagHiddenInputs.append(`<input type="hidden" name="tags[]" value="${tag}">`);
    };

    window.removeTag = function(element) {
        const tagItem = $(element).closest('.tag-item');
        const tagText = tagItem.text().trim().replace('×', '').trim();

        tagHiddenInputs.find(`input[value="${tagText}"]`).remove();
        tagItem.remove();
    };

    // ============================================
    // Delete Blog (from edit page)
    // ============================================
    $('.delete-blog').click(function() {
        var id = $(this).data('id');
        var title = $(this).data('title');

        Swal.fire({
            title: 'আপনি কি নিশ্চিত?',
            text: '"' + title + '" শিরোনামের ব্লগ পোস্টটি ডিলিট করতে চান?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'হ্যাঁ, ডিলিট',
            cancelButtonText: 'বাতিল'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin/blog/posts") }}/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            setTimeout(function() {
                                window.location.href = '{{ route("admin.blog.posts.index") }}';
                            }, 1500);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('ব্লগ পোস্ট ডিলিট করতে ব্যর্থ হয়েছে');
                    }
                });
            }
        });
    });

    // ============================================
    // Form Validation
    // ============================================
    $('#blogForm').on('submit', function(e) {
        const title = $('#title').val().trim();
        let content = '';

        if (editorInstance) {
            content = editorInstance.getData().trim();
        } else {
            content = $('#editor').val().trim();
        }

        if (!title) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'ত্রুটি!',
                text: 'দয়া করে ব্লগ পোস্টের শিরোনাম দিন',
                confirmButtonColor: '#0d6efd'
            });
            $('#title').focus();
            return false;
        }

        if (!content || content === '<p>&nbsp;</p>' || content === '<p></p>') {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'ত্রুটি!',
                text: 'দয়া করে ব্লগ পোস্টের কন্টেন্ট লিখুন',
                confirmButtonColor: '#0d6efd'
            });
            if (editorInstance) {
                editorInstance.editing.view.focus();
            }
            return false;
        }

        // Update textarea with editor content
        if (editorInstance) {
            $('#editor').val(content);
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
            $('#blogForm').submit();
        }
        // Ctrl+Shift+F to focus on title field
        if (e.ctrlKey && e.shiftKey && e.key === 'F') {
            e.preventDefault();
            $('#title').focus();
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
