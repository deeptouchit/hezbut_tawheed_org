@extends('admin.layouts.master')

@section('page-title', 'নতুন পেজ তৈরি করুন')

@push('styles')
<style>
    .form-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #006A4E;
    }
    .required-field::after {
        content: '*';
        color: #dc3545;
        margin-left: 4px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.pages.store') }}" method="POST" id="pageForm">
                @csrf
                <div class="row">
                    <!-- Left Column: Form Fields -->
                    <div class="col-md-9">
                        <div class="card card-outline card-success shadow-sm mb-4">
                            <div class="card-body">
                                
                                <!-- Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label required-field fw-bold">পেজের শিরোনাম</label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="যেমন: দৃষ্টিভঙ্গি, পরিচিতি" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div class="mb-3">
                                    <label for="slug" class="form-label required-field fw-bold">স্ল্যাগ (URL Slug)</label>
                                    <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" placeholder="যেমন: vision, about" value="{{ old('slug') }}" required>
                                    <small class="text-muted">পেজটির ইউআরএল হবে: <span class="fw-bold" id="slugPreview">{{ url('/') }}/[slug]</span></small>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Content (CKEditor) -->
                                <div class="mb-3">
                                    <label for="editor" class="form-label fw-bold">পেজের সম্পূর্ণ কন্টেন্ট (HTML/টেক্সট)</label>
                                    <textarea name="content" id="editor" class="form-control @error('content') is-invalid @enderror" rows="15">{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Settings & Publish -->
                    <div class="col-md-3">
                        <!-- Publish Status Box -->
                        <div class="form-section shadow-sm bg-white border border-light">
                            <h5 class="fw-bold text-dark-green mb-3"><i class="fas fa-paper-plane me-1"></i> প্রকাশনা সেটিং</h5>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_active" id="statusSwitch" value="1" checked>
                                <label class="form-check-label fw-bold" for="statusSwitch" id="statusLabel">সক্রিয় (Active)</label>
                            </div>

                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="fas fa-save me-1"></i> পেজ তৈরি করুন
                            </button>
                            <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-times me-1"></i> বাতিল
                            </a>
                        </div>

                        <!-- SEO Metadata Box -->
                        <div class="form-section shadow-sm bg-white border border-light">
                            <h5 class="fw-bold text-dark-green mb-3"><i class="fas fa-search me-1"></i> SEO বিবরণী</h5>
                            
                            <div class="mb-0">
                                <label for="meta_description" class="form-label small fw-bold">মেটা ডেসক্রিপশন</label>
                                <textarea name="meta_description" id="meta_description" class="form-control" rows="4" placeholder="অনুসন্ধানের জন্য পেজের সংক্ষিপ্ত বর্ণনা লিখুন...">{{ old('meta_description') }}</textarea>
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
{{-- CKEditor 5 CDN --}}
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

<script>
$(document).ready(function() {
    let editorInstance = null;

    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: [
                'heading', '|', 'bold', 'italic', 'underline', 'strikethrough', '|',
                'bulletedList', 'numberedList', '|', 'blockQuote', 'link', '|', 'undo', 'redo'
            ],
            placeholder: 'পেজের সমস্ত কন্টেন্ট ও ইমেজ বা লিংক সম্বলিত তথ্য এখানে সাজান...'
        })
        .then(editor => {
            editorInstance = editor;
        })
        .catch(error => {
            console.error(error);
        });

    // Auto-generate slug from title
    $('#title').on('keyup', function() {
        var slugInput = $('#slug');
        var title = $(this).val();
        
        var slug = title.toLowerCase()
            .replace(/[^a-z0-9-]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
            
        slugInput.val(slug);
        $('#slugPreview').text('{{ url("/") }}/' + slug);
    });

    $('#slug').on('keyup', function() {
        var slug = $(this).val().toLowerCase()
            .replace(/[^a-z0-9-]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
        $(this).val(slug);
        $('#slugPreview').text('{{ url("/") }}/' + slug);
    });

    // Toggle status switch label text
    $('#statusSwitch').change(function() {
        if ($(this).is(':checked')) {
            $('#statusLabel').text('সক্রিয় (Active)');
        } else {
            $('#statusLabel').text('নিষ্ক্রিয় (Inactive)');
        }
    });

    // Form submit check
    $('#pageForm').on('submit', function() {
        if (editorInstance) {
            $('#editor').val(editorInstance.getData());
        }
        return true;
    });
});
</script>
@endpush
