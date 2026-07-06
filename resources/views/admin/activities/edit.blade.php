@extends('admin.layouts.master')

@section('page-title', 'কার্যক্রম এডিট করুন')

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
    .image-preview-container {
        position: relative;
        display: inline-block;
        width: 100%;
        max-width: 300px;
        margin-bottom: 15px;
    }
    .image-preview {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px dashed #ced4da;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.activities.update', $activity->id) }}" method="POST" id="activityForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- Left Column: Form Fields -->
                    <div class="col-md-9">
                        <div class="card card-outline card-success shadow-sm mb-4">
                            <div class="card-body">
                                
                                <!-- Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label required-field fw-bold">কার্যক্রমের শিরোনাম</label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="যেমন: ফ্রি রক্তদান কর্মসূচি, উগ্রবাদ বিরোধী সেমিনার" value="{{ old('title', $activity->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div class="mb-3">
                                    <label for="slug" class="form-label required-field fw-bold">স্ল্যাগ (URL Slug)</label>
                                    <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" placeholder="যেমন: free-blood-donation" value="{{ old('slug', $activity->slug) }}" required>
                                    <small class="text-muted">ইউআরএল হবে: <span class="fw-bold" id="slugPreview">{{ url('/activities') }}/{{ $activity->slug }}</span></small>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Short Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold">সংক্ষিপ্ত বিবরণী (লিস্টিং কার্ডে দেখানোর জন্য)</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="কার্যক্রমের একটি সংক্ষিপ্ত বিবরণী এখানে লিখুন...">{{ old('description', $activity->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Content (CKEditor) -->
                                <div class="mb-3">
                                    <label for="editor" class="form-label fw-bold">কার্যক্রমের বিস্তারিত কন্টেন্ট</label>
                                    <textarea name="content" id="editor" class="form-control @error('content') is-invalid @enderror" rows="15">{{ old('content', $activity->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Settings & Image Upload -->
                    <div class="col-md-3">
                        <!-- Publish Status Box -->
                        <div class="form-section shadow-sm bg-white border border-light">
                            <h5 class="fw-bold text-dark-green mb-3"><i class="fas fa-paper-plane me-1"></i> প্রকাশনা সেটিং</h5>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_active" id="statusSwitch" value="1" {{ $activity->is_active ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="statusSwitch" id="statusLabel">{{ $activity->is_active ? 'সক্রিয় (Active)' : 'নিষ্ক্রিয় (Inactive)' }}</label>
                            </div>

                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="fas fa-save me-1"></i> সেভ করুন
                            </button>
                            <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-times me-1"></i> বাতিল
                            </a>
                        </div>

                        <!-- Image Upload Box -->
                        <div class="form-section shadow-sm bg-white border border-light">
                            <h5 class="fw-bold text-dark-green mb-3"><i class="fas fa-image me-1"></i> ফিচার্ড ইমেজ</h5>
                            
                            <div class="image-preview-container">
                                <img src="{{ $activity->image_url }}" id="imagePreview" class="image-preview" alt="Preview">
                            </div>
                            
                            <div class="mb-3">
                                <label for="imageInput" class="form-label small fw-bold">ছবি পরিবর্তন করুন</label>
                                <input type="file" name="image" id="imageInput" class="form-control form-control-sm @error('image') is-invalid @enderror" accept="image/*">
                                <small class="text-muted">সাইজ সর্বোচ্চ ৫ মেগাবাইট (JPG, PNG, WebP)</small>
                                @error('image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
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
            placeholder: 'কার্যক্রমের সমস্ত বিস্তারিত বিবরণী ও ইমেজ বা ভিডিও লিংক সম্বলিত তথ্য এখানে দিন...'
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
        $('#slugPreview').text('{{ url("/activities") }}/' + slug);
    });

    $('#slug').on('keyup', function() {
        var slug = $(this).val().toLowerCase()
            .replace(/[^a-z0-9-]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
        $(this).val(slug);
        $('#slugPreview').text('{{ url("/activities") }}/' + slug);
    });

    // Toggle status switch label text
    $('#statusSwitch').change(function() {
        if ($(this).is(':checked')) {
            $('#statusLabel').text('সক্রিয় (Active)');
        } else {
            $('#statusLabel').text('নিষ্ক্রিয় (Inactive)');
        }
    });

    // Image Preview
    $('#imageInput').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    // Form submit check
    $('#activityForm').on('submit', function() {
        if (editorInstance) {
            $('#editor').val(editorInstance.getData());
        }
        return true;
    });
});
</script>
@endpush
