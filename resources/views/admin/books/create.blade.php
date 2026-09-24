@extends('admin.layouts.master')

@section('page-title', 'নতুন বই যোগ করুন')

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
        max-width: 250px;
        margin-bottom: 15px;
    }
    .image-preview {
        width: 100%;
        height: 300px;
        object-fit: contain;
        border-radius: 8px;
        border: 2px dashed #ced4da;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.books.store') }}" method="POST" id="bookForm" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Left Column: Form Fields -->
                    <div class="col-md-9">
                        <div class="card card-outline card-success shadow-sm mb-4">
                            <div class="card-body">
                                
                                <!-- Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label required-field fw-bold">বইয়ের শিরোনাম</label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="যেমন: এ ইসলাম ইসলামই নয়, হেজাব ও সালাত" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Writer/Author -->
                                <div class="mb-3">
                                    <label for="writer" class="form-label fw-bold">লেখক/সম্পাদক</label>
                                    <input type="text" name="writer" id="writer" class="form-control @error('writer') is-invalid @enderror" placeholder="যেমন: মোহাম্মদ বায়াজীদ খান পন্নী" value="{{ old('writer') }}">
                                    @error('writer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div class="mb-3">
                                    <label for="category_id" class="form-label required-field fw-bold">বইয়ের ক্যাটাগরি</label>
                                    <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
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

                                <!-- Slug -->
                                <div class="mb-3">
                                    <label for="slug" class="form-label required-field fw-bold">স্ল্যাগ (URL Slug)</label>
                                    <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" placeholder="যেমন: this-islam-is-not-islam-at-all" value="{{ old('slug') }}" required>
                                    <small class="text-muted">ইউআরএল হবে: <span class="fw-bold" id="slugPreview">{{ url('/books') }}/[slug]</span></small>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- PDF File & Link -->
                                <div class="mb-3 p-3 bg-light border rounded-3">
                                    <h6 class="fw-bold mb-2 text-dark-green"><i class="fas fa-file-pdf me-1"></i> পিডিএফ সংস্করণ</h6>
                                    
                                    <div class="mb-2">
                                        <label for="pdf_file" class="form-label small fw-bold text-muted mb-1">পিডিএফ ফাইল আপলোড করুন</label>
                                        <input type="file" name="pdf_file" id="pdf_file" class="form-control form-control-sm @error('pdf_file') is-invalid @enderror" accept=".pdf">
                                        @error('pdf_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="text-center my-2 text-muted small fw-bold">— অথবা —</div>
                                    
                                    <div>
                                        <label for="pdf_url" class="form-label small fw-bold text-muted mb-1">বহিরাগত পিডিএফ লিংক (URL)</label>
                                        <input type="text" name="pdf_url" id="pdf_url" class="form-control form-control-sm @error('pdf_url') is-invalid @enderror" placeholder="যেমন: https://hezbuttawheed.org/uploads/book.pdf" value="{{ old('pdf_url') }}">
                                        @error('pdf_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Pricing -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="price" class="form-label fw-bold">বিক্রয় মূল্য (৳)</label>
                                        <input type="text" name="price" id="price" class="form-control @error('price') is-invalid @enderror" placeholder="যেমন: ১২০" value="{{ old('price') }}">
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="old_price" class="form-label fw-bold">পূর্ব মূল্য (৳) (যদি ছাড় থাকে)</label>
                                        <input type="text" name="old_price" id="old_price" class="form-control @error('old_price') is-invalid @enderror" placeholder="যেমন: ১৫০" value="{{ old('old_price') }}">
                                        @error('old_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Short Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold">সংক্ষিপ্ত বিবরণী (লিস্টিং কার্ডে দেখানোর জন্য)</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="বইয়ের একটি আকর্ষণীয় সংক্ষিপ্ত বিবরণী এখানে লিখুন...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Content (CKEditor) -->
                                <div class="mb-3">
                                    <label for="editor" class="form-label fw-bold">বইয়ের বিস্তারিত কন্টেন্ট/সূচিপত্র/ভূমিকা</label>
                                    <textarea name="content" id="editor" class="form-control @error('content') is-invalid @enderror" rows="15">{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Settings & Cover Image -->
                    <div class="col-md-3">
                        <!-- Publish Status Box -->
                        <div class="form-section shadow-sm bg-white border border-light">
                            <h5 class="fw-bold text-dark-green mb-3"><i class="fas fa-paper-plane me-1"></i> প্রকাশনা সেটিং</h5>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_active" id="statusSwitch" value="1" checked>
                                <label class="form-check-label fw-bold" for="statusSwitch" id="statusLabel">সক্রিয় (Active)</label>
                            </div>

                            <hr>

                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_popular" id="popularSwitch" value="1">
                                <label class="form-check-label fw-bold" for="popularSwitch">পাঠক চাহিদার শীর্ষে বই</label>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_bestseller" id="bestsellerSwitch" value="1">
                                <label class="form-check-label fw-bold" for="bestsellerSwitch">বেস্ট সেলার (Best Selling)</label>
                            </div>

                            <div class="mb-3">
                                <label for="popular_order" class="form-label small fw-bold">অর্ডারিং নম্বর (Priority)</label>
                                <input type="number" name="popular_order" id="popular_order" class="form-control form-control-sm" placeholder="যেমন: ১" value="0" min="0">
                            </div>

                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="fas fa-save me-1"></i> বই সংরক্ষণ করুন
                            </button>
                            <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-times me-1"></i> বাতিল
                            </a>
                        </div>

                        <!-- Cover Image Upload Box -->
                        <div class="form-section shadow-sm bg-white border border-light">
                            <h5 class="fw-bold text-dark-green mb-3"><i class="fas fa-image me-1"></i> বইয়ের প্রচ্ছদ (Cover)</h5>
                            
                            <div class="image-preview-container text-center w-100">
                                <img src="https://placehold.co/400x600/e9ecef/adb5bd?text=No+Cover+Selected" id="imagePreview" class="image-preview" alt="Preview">
                            </div>
                            
                            <div class="mb-3">
                                <label for="imageInput" class="form-label small fw-bold">প্রচ্ছদ নির্বাচন করুন</label>
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
            placeholder: 'বইয়ের সমস্ত বিস্তারিত বিবরণী, প্রথম অধ্যায় বা সুচিপত্র এখানে দিন...'
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
        $('#slugPreview').text('{{ url("/books") }}/' + slug);
    });

    $('#slug').on('keyup', function() {
        var slug = $(this).val().toLowerCase()
            .replace(/[^a-z0-9-]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
        $(this).val(slug);
        $('#slugPreview').text('{{ url("/books") }}/' + slug);
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
    $('#bookForm').on('submit', function() {
        if (editorInstance) {
            $('#editor').val(editorInstance.getData());
        }
        return true;
    });
});
</script>
@endpush
