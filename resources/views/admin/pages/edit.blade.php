@extends('admin.layouts.master')

@section('page-title', 'পেজ এডিট করুন')

@push('styles')
<style>
    .required-field::after {
        content: ' *';
        color: #e63946;
        font-weight: bold;
    }
    .slug-preview-box {
        background-color: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 0.9rem;
    }
    /* WordPress Editor Interface Styles */
    .wp-editor-wrap {
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        overflow: hidden;
    }
    .wp-editor-tools-header {
        background: #f8fafc;
        border-bottom: 1px solid #cbd5e1;
        padding: 6px 12px 0 12px;
    }
    .wp-editor-tabs-nav {
        display: flex;
    }
    .wp-editor-tabs-nav button {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-bottom: none;
        color: #64748b;
        padding: 4px 12px;
        margin-left: 4px;
        border-radius: 4px 4px 0 0;
        cursor: pointer;
        font-weight: 600;
        font-size: 12px;
        outline: none;
        transition: all 0.15s ease;
    }
    .wp-editor-tabs-nav button:hover {
        color: #1e293b;
        background: #e2e8f0;
    }
    .wp-editor-tabs-nav button.active-tab-btn {
        background: #fff;
        border-bottom: 1px solid #fff;
        color: #006A4E;
        font-weight: 700;
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
        line-height: 1.6;
        padding: 12px;
        outline: none;
    }
    .mce-tinymce.mce-fullscreen {
        z-index: 99990 !important;
    }
    .mce-floatpanel {
        z-index: 99999 !important;
    }
    .btn-xs {
        padding: 2px 6px;
        font-size: 11px;
        font-weight: 600;
        line-height: 1.5;
        border-radius: 3px;
    }
</style>
@endpush

@section('content')
@php
    $themeView = "theme::pages." . $page->slug;
    $hasCustomTemplate = view()->exists($themeView);
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" id="pageForm">
                @method('PUT')
                @csrf
                <div class="row">
                    <!-- Left Column: Form Fields -->
                    <div class="col-md-9">


                        <div class="card card-outline card-success shadow-sm mb-4">
                            <div class="card-body">
                                
                                <!-- Title -->
                                <div class="mb-4">
                                    <label for="title" class="form-label required-field fw-bold"><i class="fas fa-heading me-1"></i> পেজের শিরোনাম</label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="যেমন: দৃষ্টিভঙ্গি, পরিচিতি" value="{{ old('title', $page->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div class="mb-4">
                                    <label for="slug" class="form-label required-field fw-bold"><i class="fas fa-link me-1"></i> স্ল্যাগ (URL Slug)</label>
                                    <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" placeholder="যেমন: vision, about-us" value="{{ old('slug', $page->slug) }}" required>
                                    <div class="mt-2 slug-preview-box">
                                        <i class="fas fa-external-link-alt text-muted me-1"></i> পেজটির ইউআরএল হবে: 
                                        <span class="fw-bold text-success font-monospace" id="slugPreview">{{ url('/') }}/{{ $page->slug }}</span>
                                    </div>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Content (WP-Style Editor with TinyMCE) -->
                                @if($hasCustomTemplate)
                                    <div class="mb-3">
                                        <div class="alert alert-info border-start border-4 border-info p-4 my-3 bg-light rounded shadow-sm" style="border-left: 4px solid #17a2b8 !important;" role="alert">
                                            <h5 class="fw-bold text-info"><i class="fas fa-info-circle me-2"></i> কাস্টম ব্লেড টেমপ্লেট সক্রিয়</h5>
                                            <p class="mb-2">এই পেজটি একটি ডাইনামিক ব্লেড টেমপ্লেট ব্যবহার করছে: <strong><code>{{ $page->slug }}.blade.php</code></strong></p>
                                            <p class="mb-0 text-muted small">পেজটির মূল কন্টেন্ট ও লেআউট সরাসরি কোড ফাইলে সুসংগঠিত থাকায় এখানে কন্টেন্ট এডিট করার প্রয়োজন নেই। তবে আপনি পেজের শিরোনাম, ইউআরএল স্ল্যাগ এবং এসইও (SEO) বিবরণ ডানপাশের বক্সগুলো থেকে যথারীতি আপডেট করতে পারবেন।</p>
                                        </div>
                                        <input type="hidden" name="content" value="{{ $page->content }}">
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <label for="editor" class="form-label fw-bold mb-2"><i class="fas fa-file-alt me-1"></i> পেজের সম্পূর্ণ কন্টেন্ট (HTML/টেক্সট)</label>
                                        <div class="d-flex justify-content-between align-items-center mb-2 px-3 bg-light rounded py-2 border">
                                            <span class="small text-muted"><i class="fas fa-keyboard me-1"></i> শব্দ সংখ্যা: <strong id="editor-word-count" class="text-success">০</strong> শব্দ</span>
                                            <span class="small text-muted"><i class="fas fa-clock me-1"></i> পড়ার সময়: <strong id="editor-reading-time" class="text-info">০</strong> মিনিট</span>
                                        </div>
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
                                                <button type="button" class="btn btn-light btn-xs border py-0 px-2" onclick="closeTags()">close tags</button>
                                            </div>

                                            <div class="wp-editor-container">
                                                <textarea name="content" id="editor" class="form-control @error('content') is-invalid @enderror" placeholder="পেজের সম্পূর্ণ কন্টেন্ট লিখুন..." style="height: 450px;">{{ old('content', $page->content) }}</textarea>
                                            </div>
                                        </div>
                                        @error('content')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Settings & Publish -->
                    <div class="col-md-3">
                        <!-- Publish Status Box -->
                        <div class="card card-outline card-success shadow-sm mb-4">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h3 class="card-title fw-bold text-success mb-0"><i class="fas fa-paper-plane me-2"></i> প্রকাশনা সেটিং</h3>
                                <div class="card-tools ms-auto">
                                    <button type="button" class="btn btn-tool btn-sm" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-check form-switch mb-3 d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="statusSwitch" value="1" checked>
                                    <label class="form-check-label fw-bold ms-2" for="statusSwitch" id="statusLabel">লাইভ (Live)</label>
                                </div>

                                <button type="submit" class="btn btn-success w-100 mb-2 py-2" id="saveBtn">
                                    <i class="fas fa-save me-1"></i> সংরক্ষণ করুন
                                </button>
                                <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-primary w-100 py-2">
                                    <i class="fas fa-list me-1"></i> পেজ তালিকায় ফিরে যান
                                </a>
                            </div>
                        </div>

                        <!-- SEO Metadata Box -->
                        <div class="card card-outline card-info shadow-sm mb-4">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h3 class="card-title fw-bold text-info mb-0"><i class="fas fa-search me-2"></i> SEO বিবরণী</h3>
                                <div class="card-tools ms-auto">
                                    <button type="button" class="btn btn-tool btn-sm" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-0">
                                    <label for="meta_description" class="form-label small fw-bold">মেটা ডেসক্রিপশন</label>
                                    <textarea name="meta_description" id="meta_description" class="form-control" rows="4" placeholder="মেটা বর্ণনা লিখুন...">{{ old('meta_description', $page->meta_description) }}</textarea>
                                    <div class="d-flex justify-content-between mt-1 small">
                                        <span class="text-muted"><strong id="meta-char-count">০</strong> / ১৬০ ক্যারেক্টার</span>
                                        <span id="meta-verdict" class="fw-bold text-secondary">খালি</span>
                                    </div>
                                    <div class="progress mt-1" style="height: 6px;">
                                        <div id="meta-progress" class="progress-bar bg-secondary" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="160"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Google Preview Box -->
                        <div class="card card-outline card-primary shadow-sm mb-4">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h3 class="card-title fw-bold text-primary mb-0"><i class="fab fa-google me-2"></i> গুগল সার্চ প্রিভিউ</h3>
                                <div class="card-tools ms-auto">
                                    <button type="button" class="btn btn-tool btn-sm" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            <div class="card-body bg-white p-3">
                                <div class="google-preview-container" style="font-family: Arial, sans-serif; font-size: 14px; line-height: 1.4;">
                                    <div class="google-preview-title" id="google-title" style="color: #1a0dab; font-size: 17px; font-weight: normal; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer; text-decoration: none;">শিরোনাম এখানে দেখাবে</div>
                                    <div class="google-preview-url text-success" id="google-url" style="color: #006621; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 2px;">{{ url('/') }}/{{ $page->slug }}</div>
                                    <div class="google-preview-desc" id="google-desc" style="color: #545454; font-size: 12px; word-wrap: break-word; line-height: 1.5;">মেটা বর্ণনা এখানে দেখাবে। অনুগৃহপূর্বক একটি সঠিক বর্ণনা লিখুন...</div>
                                </div>
                            </div>
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
    const autosaveKey = 'page-edit-autosave-content-' + '{{ $page->id }}';

    // 1. Initialize TinyMCE Editor
    function initTinyMCE() {
        tinymce.init({
            selector: '#editor',
            plugins: [
                'advlist autolink lists link image charmap preview anchor',
                'searchreplace visualblocks code fullscreen template autosave',
                'insertdatetime media table paste code help wordcount textcolor colorpicker'
            ],
            toolbar1: 'undo redo | formatselect styleselect | fontselect fontsizeselect | forecolor backcolor',
            toolbar2: 'bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table template | code fullscreen preview',
            menubar: 'file edit view insert format table tools help',
            branding: false,
            height: 500,
            image_title: true,
            automatic_uploads: true,
            image_advtab: true,
            image_caption: true,
            image_dimensions: true,
            image_class_list: [
                {title: 'None (ডিফল্ট)', value: ''},
                {title: 'Float Left (ইমেজ বামে, ডানে লেখা থাকবে)', value: 'alignleft'},
                {title: 'Float Right (ইমেজ ডানে, বামে লেখা থাকবে)', value: 'alignright'},
                {title: 'Center (ইমেজ মাঝখানে থাকবে)', value: 'aligncenter'},
                {title: 'Responsive (রেসপনসিভ ফুল-উইডথ)', value: 'img-fluid'}
            ],
            templates: [
                {
                    title: '২ কলাম লেআউট (2 Columns)',
                    description: 'বামপাশে ইমেজ এবং ডানপাশে বিবরণ কলাম গ্রিড',
                    content: '<div class="row align-items-center mb-4"><div class="col-md-6 mb-3 mb-md-0"><img src="/uploads/about/committee.jpg" class="img-fluid rounded" alt="Image"></div><div class="col-md-6"><h3>কলামের শিরোনাম</h3><p>এখানে আপনার বিবরণ লিখুন। কলামটি সম্পূর্ণ রেসপনসিভ এবং ডেক্সটপ ও মোবাইলে সুন্দরভাবে সাজানো থাকবে।</p></div></div>'
                },
                {
                    title: '৩ কলাম ফিচার বক্স (3 Columns Features)',
                    description: 'পাশাপাশি ৩টি ইনফরমেশন বক্স',
                    content: '<div class="row mb-4"><div class="col-md-4 mb-3"><div class="card h-100 border-0 shadow-sm p-4 text-center" style="background:#f8f9fa; border-radius:8px;"><i class="fas fa-gem fa-3x text-success mb-3"></i><h4>ফিচার ১</h4><p class="text-muted">সংক্ষিপ্ত বিবরণ লিখুন।</p></div></div><div class="col-md-4 mb-3"><div class="card h-100 border-0 shadow-sm p-4 text-center" style="background:#f8f9fa; border-radius:8px;"><i class="fas fa-shield-alt fa-3x text-info mb-3"></i><h4>ফিচার ২</h4><p class="text-muted">সংক্ষিপ্ত বিবরণ লিখুন।</p></div></div><div class="col-md-4 mb-3"><div class="card h-100 border-0 shadow-sm p-4 text-center" style="background:#f8f9fa; border-radius:8px;"><i class="fas fa-rocket fa-3x text-warning mb-3"></i><h4>ফিচার ৩</h4><p class="text-muted">সংক্ষিপ্ত বিবরণ লিখুন।</p></div></div></div>'
                },
                {
                    title: 'কল-টু-অ্যাকশন বাটন (Call to Action Button)',
                    description: 'একটি আকর্ষণীয় সবুজ বাটন',
                    content: '<div class="text-center my-4"><a href="#" class="btn btn-success btn-lg px-5 py-3 fw-bold shadow-sm" style="border-radius:30px; background-color:#006A4E; border-color:#006A4E;"><i class="fas fa-arrow-right me-2"></i> বিস্তারিত জানতে এখানে ক্লিক করুন</a></div>'
                },
                {
                    title: 'অ্যালার্ট/ইনফো বক্স (Alert Box)',
                    description: 'সবুজ বর্ডারযুক্ত হাইলাইটেড টেক্সট বক্স',
                    content: '<div class="alert alert-success border-start border-4 border-success p-4 my-3 bg-light rounded" style="border-left: 4px solid #006A4E !important;" role="alert"><h5 class="fw-bold text-success"><i class="fas fa-info-circle me-2"></i> গুরুত্বপূর্ণ তথ্য</h5><p class="mb-0">এখানে আপনার গুরুত্বপূর্ণ তথ্য বা ঘোষণা লিখুন। এই বক্সটি সকলের নজর কাড়বে।</p></div>'
                }
            ],
            style_formats: [
                { title: 'লাইন হাইট (Line Height)', items: [
                    { title: '১.০ (ডিফল্ট)', selector: 'p,span,h1,h2,h3,h4,h5,h6,li,div', styles: { 'line-height': '1.0' } },
                    { title: '১.১৫', selector: 'p,span,h1,h2,h3,h4,h5,h6,li,div', styles: { 'line-height': '1.15' } },
                    { title: '১.২৫', selector: 'p,span,h1,h2,h3,h4,h5,h6,li,div', styles: { 'line-height': '1.25' } },
                    { title: '১.৫', selector: 'p,span,h1,h2,h3,h4,h5,h6,li,div', styles: { 'line-height': '1.5' } },
                    { title: '১.৭৫', selector: 'p,span,h1,h2,h3,h4,h5,h6,li,div', styles: { 'line-height': '1.75' } },
                    { title: '২.০', selector: 'p,span,h1,h2,h3,h4,h5,h6,li,div', styles: { 'line-height': '2.0' } },
                    { title: '২.৫', selector: 'p,span,h1,h2,h3,h4,h5,h6,li,div', styles: { 'line-height': '2.5' } },
                    { title: '৩.০', selector: 'p,span,h1,h2,h3,h4,h5,h6,li,div', styles: { 'line-height': '3.0' } }
                ] }
            ],
            font_formats: 'হিন্দ শিলিগুড়ি (Hind Siliguri)=Hind Siliguri, sans-serif;' +
                         'বালু দা ২ (Baloo Da 2)=Baloo Da 2, sans-serif;' +
                         'ইন্টার (Inter)=Inter, sans-serif;' +
                         'সোলাইমান লিপি (SolaimanLipi)=SolaimanLipi, Arial, sans-serif;' +
                         'Arial=arial,helvetica,sans-serif;' +
                         'Courier New=courier new,courier,monospace;' +
                         'Georgia=georgia,palatino,serif;' +
                         'Times New Roman=times new roman,times,serif;' +
                         'Verdana=verdana,geneva,sans-serif',
            content_css: [
                'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
                'https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Baloo+Da+2:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap'
            ],
            autosave_ask_before_unload: true,
            autosave_interval: '15s',
            autosave_restore_when_empty: false,
            autosave_retention: '30m',
            content_style: 'body { font-family: "Baloo Da 2", "SolaimanLipi", "Inter", Helvetica, Arial, sans-serif; font-size:16px; color: #1e293b; padding: 15px; } .alignleft { float: left; margin: 0.5em 1em 0.5em 0; } .alignright { float: right; margin: 0.5em 0 0.5em 1em; } .aligncenter { display: block; margin-left: auto; margin-right: auto; } img { max-width: 100%; height: auto; }',
            setup: function(editor) {
                editor.on('keyup change Undo Redo Init', function() {
                    // Update Word Count & Reading Time
                    var text = editor.getContent({format: 'text'});
                    var wordCount = text.trim() ? text.trim().split(/\s+/).length : 0;
                    $('#editor-word-count').text(wordCount);
                    var readingTime = Math.ceil(wordCount / 200);
                    $('#editor-reading-time').text(readingTime);

                    // Autosave silently in localStorage
                    localStorage.setItem(autosaveKey, editor.getContent());
                });
            },
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
        
        initTinyMCE();
    });

    $('#editorCodeTab').on('click', function(e) {
        e.preventDefault();
        if ($(this).hasClass('active-tab-btn')) return;
        
        $(this).addClass('active-tab-btn');
        $('#editorVisualTab').removeClass('active-tab-btn');
        $('#codeModeButtons').removeClass('d-none').addClass('d-flex');
        
        if (tinymce.get('editor')) {
            var content = tinymce.get('editor').getContent();
            tinymce.get('editor').destroy();
            $('#editor').val(content);
        }
    });

    // 3. Local Autosave Restoration
    var savedContent = localStorage.getItem(autosaveKey);
    if (savedContent && savedContent.trim().length > 10) {
        $('#autosaveAlert').removeClass('d-none');
    }

    $('#restoreDraftBtn').on('click', function(e) {
        e.preventDefault();
        if (savedContent) {
            if (tinymce.get('editor')) {
                tinymce.get('editor').setContent(savedContent);
            } else {
                $('#editor').val(savedContent);
            }
            $('#autosaveAlert').addClass('d-none');
            toastr.success('ড্রাফটটি সফলভাবে পুনরুদ্ধার করা হয়েছে');
        }
    });

    // 4. Real-time SEO Google Search Preview
    function updateGoogleTitle() {
        var title = $('#title').val().trim();
        if (title) {
            $('#google-title').text(title + ' - হেযবুত তওহীদ');
        } else {
            $('#google-title').text('শিরোনাম এখানে দেখাবে');
        }
    }

    function updateGoogleUrl() {
        var slug = $('#slug').val().trim();
        if (slug) {
            $('#google-url').text('{{ url("/") }}/' + slug);
        } else {
            $('#google-url').text('{{ url("/") }}/[slug]');
        }
    }

    function updateGoogleDesc() {
        var desc = $('#meta_description').val().trim();
        if (desc) {
            $('#google-desc').text(desc);
        } else {
            $('#google-desc').text('মেটা বর্ণনা এখানে দেখাবে। অনুগৃহপূর্বক একটি সঠিক বর্ণনা লিখুন...');
        }
    }

    $('#title').on('input change keyup', updateGoogleTitle);
    $('#slug').on('input change keyup', updateGoogleUrl);
    $('#meta_description').on('input change keyup', function() {
        updateGoogleDesc();
        updateMetaProgress();
    });

    // 5. SEO Meta Characters Progress Bar
    function updateMetaProgress() {
        var count = $('#meta_description').val().length;
        $('#meta-char-count').text(count);
        
        var percent = Math.min((count / 160) * 100, 100);
        var progress = $('#meta-progress');
        var verdict = $('#meta-verdict');
        
        progress.css('width', percent + '%');
        
        if (count === 0) {
            progress.removeClass('bg-success bg-warning bg-danger').addClass('bg-secondary');
            verdict.text('খালি').removeClass('text-success text-warning text-danger').addClass('text-secondary');
        } else if (count < 120) {
            progress.removeClass('bg-secondary bg-success bg-danger').addClass('bg-warning');
            verdict.text('খুব ছোট (Too Short)').removeClass('text-secondary text-success text-danger').addClass('text-warning');
        } else if (count <= 160) {
            progress.removeClass('bg-secondary bg-warning bg-danger').addClass('bg-success');
            verdict.text('চমৎকার (Perfect)').removeClass('text-secondary text-warning text-danger').addClass('text-success');
        } else {
            progress.removeClass('bg-secondary bg-warning bg-success').addClass('bg-danger');
            verdict.text('অতিরিক্ত লম্বা (Too Long)').removeClass('text-secondary text-warning text-success').addClass('text-danger');
        }
    }

    // 6. Title and Slug generation
    $('#title').on('keyup', function() {
        var slugInput = $('#slug');
        var title = $(this).val();
        
        var slug = title.toLowerCase()
            .replace(/[^a-z0-9-]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
            
        slugInput.val(slug);
        updateGoogleUrl();
    });

    $('#slug').on('keyup', function() {
        var slug = $(this).val().toLowerCase()
            .replace(/[^a-z0-9-]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
        $(this).val(slug);
        updateGoogleUrl();
    });

    // Toggle status switch label text
    $('#statusSwitch').change(function() {
        if ($(this).is(':checked')) {
            $('#statusLabel').text('লাইভ (Live)');
        } else {
            $('#statusLabel').text('খসড়া (Draft)');
        }
    });

    // Form submit check & clear autosave
    $('#pageForm').on('submit', function() {
        if (tinymce.get('editor')) {
            $('#editor').val(tinymce.get('editor').getContent());
        }
        
        return true;
    });
});

// Quicktags helpers for Code Mode
window.wrapText = function(tag) {
    const textarea = document.getElementById('editor');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    const selected = text.substring(start, end);
    let replacement = '';
    
    if (tag === 'a') {
        const url = prompt('Enter Link URL:', 'http://');
        if (url) {
            replacement = `<a href="${url}">${selected}</a>`;
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
</script>
@endpush
