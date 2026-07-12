@extends('admin.layouts.master')

@section('page-title', 'নতুন ব্লগ পোস্ট তৈরি করুন')

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
        width: 100%;
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

    /* WordPress Editor Interface Styles */
    .wp-editor-wrap {
        border: 1px solid #ccc;
        background: #f1f1f1;
        border-radius: 4px;
        overflow: hidden;
    }
    .wp-editor-tools-header {
        padding: 8px 10px 0;
        background: #f1f1f1;
        border-bottom: 1px solid #ccc;
    }
    .wp-editor-tabs-nav {
        margin-bottom: -1px;
    }
    .wp-editor-tabs-nav button {
        background: #ebebeb;
        border: 1px solid #ccc;
        border-bottom: none;
        color: #555;
        padding: 5px 12px;
        margin-left: 4px;
        border-radius: 4px 4px 0 0;
        cursor: pointer;
        font-weight: 500;
        font-size: 13px;
        outline: none;
    }
    .wp-editor-tabs-nav button.active-tab-btn {
        background: #fff;
        border-bottom: 1px solid #fff;
        color: #000;
        font-weight: 600;
    }
    .wp-editor-container {
        background: #fff;
    }
    .wp-editor-container textarea {
        border: none !important;
        border-radius: 0 !important;
        resize: vertical;
        font-family: Consolas, Monaco, Courier, monospace;
        font-size: 14px;
        line-height: 1.5;
        padding: 10px;
        outline: none;
    }
    /* Fix TinyMCE 4 fullscreen z-index conflict with AdminLTE sidebar/navbar */
    .mce-tinymce.mce-fullscreen {
        z-index: 99999 !important;
    }
    .btn-xs {
        padding: 1px 5px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex align-items-center justify-content-between">
            <h5 class="mb-0 text-primary"><i class="fas fa-plus-circle me-2"></i> নতুন ব্লগ পোস্ট তৈরি করুন</h5>
            <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> ফিরে যান
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.blog.posts.store') }}" method="POST" enctype="multipart/form-data" id="blogForm">
                @csrf

                <div class="row">
                    <!-- Left Column (Main Information) -->
                    <div class="col-md-8">
                        <!-- Blog Info Form Section -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-info-circle me-2"></i> ব্লগ তথ্য
                            </h5>

                            <div class="mb-3">
                                <label class="required-field form-label">শিরোনাম</label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="ব্লগ পোস্টের শিরোনাম লিখুন" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">স্লাগ (Slug)</label>
                                <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="স্লাগ লিখুন (ঐচ্ছিক)">
                                <div class="slug-preview">
                                    <i class="fas fa-link me-1"></i>
                                    পোস্ট URL: <span class="slug-text" id="slugPreview">{{ url('/blog') }}/</span>
                                </div>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">সংক্ষিপ্ত বিবরণ</label>
                                <textarea name="short_description" id="shortDescription" class="form-control @error('short_description') is-invalid @enderror" rows="3" placeholder="ব্লগ পোস্টের সংক্ষিপ্ত বিবরণ লিখুন (৫০০ অক্ষরের মধ্যে)">{{ old('short_description') }}</textarea>
                                <div class="d-flex justify-content-end mt-1">
                                    <span class="text-muted small" id="descCount">0/500</span>
                                </div>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="required-field form-label">ব্লগ কন্টেন্ট</label>
                                <div class="wp-editor-wrap">
                                    <div class="wp-editor-tools-header d-flex justify-content-between align-items-end">
                                        <div class="wp-editor-media-buttons pb-2">
                                            <button type="button" class="btn btn-light btn-sm border" id="addMediaBtn">
                                                <i class="fas fa-music text-primary me-1"></i> Add Media
                                            </button>
                                        </div>
                                        <div class="wp-editor-tabs-nav">
                                            <button type="button" class="active-tab-btn" id="editorVisualTab">Visual</button>
                                            <button type="button" class="" id="editorCodeTab">Code</button>
                                        </div>
                                    </div>
                                    
                                    <!-- Quicktags Toolbar for Code Mode -->
                                    <div id="codeModeButtons" class="bg-light p-2 border-bottom d-none flex-wrap gap-1">
                                        <button type="button" class="btn btn-light btn-xs border py-0 px-2" onclick="wrapText('b')"><strong>b</strong></button>
                                        <button type="button" class="btn btn-light btn-xs border py-0 px-2" onclick="wrapText('i')"><em>i</em></button>
                                        <button type="button" class="btn btn-light btn-xs border py-0 px-2" onclick="wrapText('a')">link</button>
                                        <button type="button" class="btn btn-light btn-xs border py-0 px-2" onclick="wrapText('blockquote')">b-quote</button>
                                        <button type="button" class="btn btn-light btn-xs border py-0 px-2" onclick="wrapText('del')"><del>del</del></button>
                                        <button type="button" class="btn btn-light btn-xs border py-0 px-2" onclick="wrapText('ins')"><ins>ins</ins></button>
                                        <button type="button" class="btn btn-light btn-xs border py-0 px-2" onclick="insertTag('img')">img</button>
                                        <button type="button" class="btn btn-light btn-xs border py-0 px-2" onclick="wrapText('ul')">ul</button>
                                        <button type="button" class="btn btn-light btn-xs border py-0 px-2" onclick="wrapText('ol')">ol</button>
                                        <button type="button" class="btn btn-light btn-xs border py-0 px-2" onclick="wrapText('li')">li</button>
                                        <button type="button" class="btn btn-light btn-xs border py-0 px-2" onclick="wrapText('code')">code</button>
                                        <button type="button" class="btn btn-light btn-xs border py-0 px-2" onclick="wrapText('more')">more</button>
                                        <button type="button" class="btn btn-light btn-xs border py-0 px-2" onclick="closeTags()">close tags</button>
                                    </div>

                                    <div class="wp-editor-container">
                                        <textarea name="content" id="editor" class="form-control @error('content') is-invalid @enderror" placeholder="ব্লগ পোস্টের সম্পূর্ণ কন্টেন্ট লিখুন..." style="height: 450px;">{{ old('content') }}</textarea>
                                    </div>
                                </div>
                                @error('content')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- SEO Form Section -->
                        <div class="form-section">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="form-section-title mb-0">
                                    <i class="fas fa-search me-2"></i> SEO তথ্য (ঐচ্ছিক)
                                </h5>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="generateSeoBtn">
                                    <i class="fas fa-robot me-1"></i> AI দিয়ে তৈরি করুন
                                </button>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">মেটা টাইটেল</label>
                                <input type="text" name="meta_title" id="metaTitle" class="form-control @error('meta_title') is-invalid @enderror" value="{{ old('meta_title') }}" placeholder="SEO টাইটেল লিখুন (৬০ অক্ষরের মধ্যে)">
                                <div class="d-flex justify-content-end mt-1">
                                    <span class="text-muted small" id="metaTitleCount">0/60</span>
                                </div>
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">মেটা ডেসক্রিপশন</label>
                                <textarea name="meta_description" id="metaDescription" class="form-control @error('meta_description') is-invalid @enderror" rows="2" placeholder="SEO ডেসক্রিপশন লিখুন (১৬০ অক্ষরের মধ্যে)">{{ old('meta_description') }}</textarea>
                                <div class="d-flex justify-content-end mt-1">
                                    <span class="text-muted small" id="metaDescCount">0/160</span>
                                </div>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">মেটা কীওয়ার্ড</label>
                                <input type="text" name="meta_keywords" id="metaKeywords" class="form-control @error('meta_keywords') is-invalid @enderror" value="{{ old('meta_keywords') }}" placeholder="কীওয়ার্ডগুলো কমা (,) দিয়ে আলাদা করুন">
                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right Column (Sidebar Settings) -->
                    <div class="col-md-4">
                        <!-- Featured Image Section -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-image me-2"></i> ফিচার্ড ইমেজ
                            </h5>
                            <div class="text-center">
                                <div class="image-preview-container">
                                    <img src="{{ asset('images/default-blog.jpg') }}" alt="Featured Image" class="image-preview" id="imagePreview">
                                    <label class="image-upload-btn" title="ছবি আপলোড করুন">
                                        <i class="fas fa-camera"></i>
                                        <input type="file" name="featured_image" id="imageInput" accept="image/*">
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-2">সর্বোচ্চ ৫ এমবি (JPEG, PNG, JPG, WEBP)</small>
                                @error('featured_image')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Category Section -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-list me-2"></i> ক্যাটাগরি
                            </h5>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">ক্যাটাগরি নির্বাচন করুন</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tags Section -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-tags me-2"></i> ট্যাগ
                            </h5>
                            <select name="tags[]" id="tagSelect" class="form-select select2" multiple="multiple" style="width: 100%;">
                                @foreach($tags as $tagString)
                                    <option value="{{ $tagString }}" {{ in_array($tagString, old('tags', [])) ? 'selected' : '' }}>
                                        {{ $tagString }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1">ট্যাগ নির্বাচন করুন অথবা নতুন ট্যাগ লিখে Enter চাপুন।</small>
                            @error('tags')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Author Section -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-user-edit me-2"></i> লেখক (Author) <span class="text-danger">*</span>
                            </h5>
                            <select name="author_id" class="form-select @error('author_id') is-invalid @enderror" required>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" {{ old('author_id', auth()->id()) == $author->id ? 'selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('author_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Settings & Sorting -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-cog me-2"></i> সেটিংস
                            </h5>
                            <div class="mb-3 form-check form-switch">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" name="status" id="statusSwitch" class="form-check-input" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                                <label for="statusSwitch" class="form-check-label" id="statusLabel">প্রকাশিত</label>
                            </div>
                            <div class="mb-3 form-check form-switch">
                                <input type="hidden" name="is_gallery" value="0">
                                <input type="checkbox" name="is_gallery" id="isGallerySwitch" class="form-check-input" value="1" {{ old('is_gallery', 0) ? 'checked' : '' }}>
                                <label for="isGallerySwitch" class="form-check-label" id="galleryLabel">গ্যালারিতে যুক্ত নয়</label>
                            </div>
                            <div class="mb-3" id="galleryOrderSection" style="display: none;">
                                <label class="form-label">গ্যালারি ক্রম (Order)</label>
                                <input type="number" name="gallery_order" class="form-control" value="{{ old('gallery_order', 0) }}" min="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ক্রমসংখ্যা (Sort Order)</label>
                                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">প্রকাশের তারিখ (Published At)</label>
                                <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="form-section bg-white border-0 shadow-sm p-3">
                            <button type="submit" class="btn btn-primary w-100 mb-2">
                                <i class="fas fa-save me-1"></i> সংরক্ষণ করুন
                            </button>
                            <button type="button" class="btn btn-warning text-white w-100 mb-2" id="saveDraftBtn">
                                <i class="fas fa-pencil-alt me-1"></i> খসড়া হিসেবে রাখুন
                            </button>
                            <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-times me-1"></i> বাতিল
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
    // 1. Initialize TinyMCE Editor
    function initTinyMCE() {
        tinymce.init({
            selector: '#editor',
            plugins: 'code fullscreen lists link image charmap media wordcount hr pagebreak',
            toolbar: 'formatselect | bold italic | bullist numlist | blockquote | alignleft aligncenter alignright alignjustify | link | pagebreak | fullscreen',
            menubar: false,
            branding: false,
            height: 450,
            image_title: true,
            automatic_uploads: true,
            images_upload_handler: function (blobInfo, success, failure) {
                var xhr, formData;
                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '{{ route("admin.blog.posts.upload-image") }}');
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                xhr.onload = function() {
                    var json;
                    if (xhr.status != 200) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }
                    json = JSON.parse(xhr.responseText);
                    if (!json || typeof json.url != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    success(json.url);
                };

                formData = new FormData();
                formData.append('upload', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            }
        });
    }

    initTinyMCE();

    // 2. Visual / Code Tab Switching Logic
    $('#editorVisualTab').on('click', function(e) {
        e.preventDefault();
        if ($(this).hasClass('active-tab-btn')) return;
        
        $('#editorCodeTab').removeClass('active-tab-btn');
        $(this).addClass('active-tab-btn');
        $('#codeModeButtons').removeClass('d-flex').addClass('d-none');
        
        // Convert plain textarea back to TinyMCE
        initTinyMCE();
    });

    $('#editorCodeTab').on('click', function(e) {
        e.preventDefault();
        if ($(this).hasClass('active-tab-btn')) return;
        
        $('#editorVisualTab').removeClass('active-tab-btn');
        $(this).addClass('active-tab-btn');
        $('#codeModeButtons').removeClass('d-none').addClass('d-flex');
        
        // Save content and remove TinyMCE editor instance to expose raw textarea
        if (tinymce.get('editor')) {
            var content = tinymce.get('editor').getContent();
            tinymce.execCommand('mceRemoveEditor', false, 'editor');
            $('#editor').val(content).show();
        }
    });

    // 3. Quicktags helper functions for Code Mode
    window.wrapText = function(tag) {
        const textarea = document.getElementById('editor');
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        const selected = text.substring(start, end);
        let replacement = '';
        
        if (tag === 'a') {
            const url = prompt('Enter URL:', 'http://');
            if (url) {
                replacement = `<a href="${url}">${selected || 'link'}</a>`;
            } else {
                return;
            }
        } else if (tag === 'more') {
            replacement = selected + '<!--more-->';
        } else {
            replacement = `<${tag}>${selected}</${tag}>`;
        }
        
        textarea.value = text.substring(0, start) + replacement + text.substring(end);
        textarea.focus();
        textarea.selectionStart = start + replacement.length;
        textarea.selectionEnd = start + replacement.length;
    };

    window.insertTag = function(tag) {
        const textarea = document.getElementById('editor');
        const start = textarea.selectionStart;
        const text = textarea.value;
        let replacement = '';
        
        if (tag === 'img') {
            const url = prompt('Enter Image URL:', 'http://');
            if (url) {
                replacement = `<img src="${url}" alt="" />`;
            } else {
                return;
            }
        }
        
        textarea.value = text.substring(0, start) + replacement + text.substring(start);
        textarea.focus();
        textarea.selectionStart = start + replacement.length;
        textarea.selectionEnd = start + replacement.length;
    };

    window.closeTags = function() {
        const textarea = document.getElementById('editor');
        const text = textarea.value;
        
        // Basic parser to find open tags and close them
        const tags = [];
        const reg = /<\/?([a-z0-9]+)[^>]*>/gi;
        let match;
        while ((match = reg.exec(text)) !== null) {
            const tag = match[1].toLowerCase();
            if (match[0].startsWith('</')) {
                if (tags.length > 0 && tags[tags.length - 1] === tag) {
                    tags.pop();
                }
            } else if (!match[0].endsWith('/>') && !['img', 'br', 'hr', 'input'].includes(tag)) {
                tags.push(tag);
            }
        }
        
        let closing = '';
        while (tags.length > 0) {
            closing += `</${tags.pop()}>`;
        }
        
        if (closing) {
            const start = textarea.selectionStart;
            textarea.value = text.substring(0, start) + closing + text.substring(start);
            textarea.focus();
            textarea.selectionStart = start + closing.length;
            textarea.selectionEnd = start + closing.length;
        }
    };

    // 4. Select2 Initialize
    $('#tagSelect').select2({
        theme: 'bootstrap-5',
        tags: true,
        tokenSeparators: [',', ' '],
        placeholder: 'ট্যাগ নির্বাচন করুন বা লিখুন'
    });

    // 5. Image Input Preview
    $('#imageInput').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    function transliterateBengaliToEnglish(text) {
        var map = {
            'ক': 'k', 'খ': 'kh', 'গ': 'g', 'ঘ': 'gh', 'ঙ': 'ng',
            'চ': 'ch', 'ছ': 'ch', 'জ': 'j', 'ঝ': 'jh', 'ঞ': 'n',
            'ট': 't', 'ঠ': 'th', 'ড': 'd', 'ঢ': 'dh', 'ণ': 'n',
            'ত': 't', 'থ': 'th', 'দ': 'd', 'ধ': 'dh', 'ন': 'n',
            'প': 'p', 'ফ': 'f', 'ব': 'b', 'ভ': 'bh', 'ম': 'm',
            'য': 'z', 'র': 'r', 'ল': 'l', 'শ': 'sh', 'ষ': 'sh', 'স': 's', 'হ': 'h',
            'ড়': 'r', 'ঢ়': 'rh', 'য়': 'y', 'ৎ': 't',
            'া': 'a', 'ি': 'i', 'ী': 'i', 'ু': 'u', 'ূ': 'u', 'ৃ': 'ri',
            'ে': 'e', 'ৈ': 'oi', 'ো': 'o', 'ৌ': 'ou',
            'অ': 'o', 'আ': 'a', 'ই': 'i', 'ঈ': 'i', 'উ': 'u', 'ঊ': 'u', 'ঋ': 'ri',
            'এ': 'e', 'ঐ': 'oi', 'ো': 'o', 'ঔ': 'ou',
            'ং': 'ng', 'ঃ': 'h', 'ঁ': '', '্': ''
        };
        var result = '';
        for (var i = 0; i < text.length; i++) {
            var char = text.charAt(i);
            result += map[char] !== undefined ? map[char] : char;
        }
        return result.toLowerCase()
            .replace(/[^a-z0-9-]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
    }

    // 6. Slug Auto-generation
    $('#title').on('keyup', function() {
        var slugInput = $('#slug');
        if (slugInput.val() === '') {
            var slug = transliterateBengaliToEnglish($(this).val());
            slugInput.val(slug);
            $('#slugPreview').text('{{ url('/blog') }}/' + slug);
        }
    });

    // 7. Short Description Character Counter
    $('#shortDescription').on('input', function() {
        var count = $(this).val().length;
        $('#descCount').text(count + '/500');
    });

    // 8. Settings toggles
    $('#statusSwitch').change(function() {
        $('#statusLabel').text($(this).is(':checked') ? 'প্রকাশিত' : 'খসড়া');
    });

    $('#isGallerySwitch').change(function() {
        if ($(this).is(':checked')) {
            $('#galleryLabel').text('গ্যালারিতে যুক্ত');
            $('#galleryOrderSection').slideDown();
        } else {
            $('#galleryLabel').text('গ্যালারিতে যুক্ত নয়');
            $('#galleryOrderSection').slideUp();
        }
    });

    // 9. Save Draft Button Action
    $('#saveDraftBtn').on('click', function() {
        $('#statusSwitch').prop('checked', false);
        $('#blogForm').submit();
    });

    // 10. AI SEO Generation Action
    $('#generateSeoBtn').on('click', function() {
        var title = $('#title').val();
        var shortDescription = $('#shortDescription').val();
        var content = '';
        
        if (tinymce.get('editor')) {
            content = tinymce.get('editor').getContent();
        } else {
            content = $('#editor').val();
        }

        if (!title) {
            Swal.fire({
                icon: 'warning',
                title: 'সাবধান!',
                text: 'অনুগ্রহ করে আগে ব্লগ পোস্টের শিরোনাম লিখুন!',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'ঠিক আছে'
            });
            return;
        }

        var btn = $(this);
        var originalHtml = btn.html();
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> জেনারেট হচ্ছে...');

        Swal.fire({
            title: 'মেটা ডাটা জেনারেট হচ্ছে...',
            text: 'অনুগ্রহ করে কিছুক্ষণ অপেক্ষা করুন।',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '{{ route("admin.blog.posts.generate-seo") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                title: title,
                short_description: shortDescription,
                content: content
            },
            success: function(response) {
                Swal.close();
                if (response.success) {
                    $('#metaTitle').val(response.meta_title).trigger('input');
                    $('#metaDescription').val(response.meta_description).trigger('input');
                    $('#metaKeywords').val(response.meta_keywords);
                    if (response.slug) {
                        $('#slug').val(response.slug);
                        $('#slugPreview').text('{{ url('/blog') }}/' + response.slug);
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'সফল!',
                        text: 'AI দিয়ে সফলভাবে SEO মেটা ডাটা তৈরি করা হয়েছে!',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'ঠিক আছে'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'ত্রুটি!',
                        text: 'মেটা ডাটা তৈরি করতে ব্যর্থ হয়েছে: ' + response.message,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'ঠিক আছে'
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                var message = 'সার্ভার সংযোগে ত্রুটি ঘটেছে!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'ত্রুটি!',
                    text: 'মেটা ডাটা তৈরি করতে ব্যর্থ হয়েছে: ' + message,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'ঠিক আছে'
                });
            },
            complete: function() {
                btn.prop('disabled', false).html(originalHtml);
            }
        });
    });

    // 11. SEO Character Counters
    $('#metaTitle').on('input', function() {
        var count = $(this).val().length;
        $('#metaTitleCount').text(count + '/60');
        if (count > 60) {
            $('#metaTitleCount').removeClass('text-muted').addClass('text-danger font-weight-bold');
        } else {
            $('#metaTitleCount').removeClass('text-danger font-weight-bold').addClass('text-muted');
        }
    });

    $('#metaDescription').on('input', function() {
        var count = $(this).val().length;
        $('#metaDescCount').text(count + '/160');
        if (count > 160) {
            $('#metaDescCount').removeClass('text-muted').addClass('text-danger font-weight-bold');
        } else {
            $('#metaDescCount').removeClass('text-danger font-weight-bold').addClass('text-muted');
        }
    });

    // Initialize counts
    $('#metaTitle').trigger('input');
    $('#metaDescription').trigger('input');

    // 12. Form Submit Validation
    $('#blogForm').on('submit', function(e) {
        if (tinymce.get('editor')) {
            $('#editor').val(tinymce.get('editor').getContent());
        }
    });
});
</script>
@endpush
