@extends('admin.layouts.master')

@section('page-title', 'নতুন নিউজলেটার টেমপ্লেট')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-plus-circle me-2"></i> নতুন নিউজলেটার টেমপ্লেট তৈরি
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.newsletter.templates.index') }}" class="btn btn-default btn-sm">
                    <i class="fas fa-arrow-left"></i> ফিরে যান
                </a>
            </div>
        </div>

        <form action="{{ route('admin.newsletter.templates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="name">টেমপ্লেট নাম <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name"
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="subject">সাবজেক্ট <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('subject') is-invalid @enderror"
                                   id="subject" name="subject"
                                   value="{{ old('subject') }}"
                                   required>
                            @error('subject')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="content">ইমেইল কন্টেন্ট <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                      id="content" name="content"
                                      rows="15"
                                      required>{{ old('content') }}</textarea>
                            @error('content')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="thumbnail">থাম্বনেইল ছবি</label>
                            <input type="file"
                                   class="form-control @error('thumbnail') is-invalid @enderror"
                                   id="thumbnail" name="thumbnail"
                                   accept="image/*">
                            <small class="text-muted">সুপারিশকৃত সাইজ: 400x300px</small>
                            @error('thumbnail')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <div id="thumbnail-preview" class="mt-2" style="display: none;">
                                <img id="preview-img" src="" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="is_default"
                                       name="is_default"
                                       value="1"
                                       {{ old('is_default') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_default">
                                    ডিফল্ট টেমপ্লেট হিসেবে সেট করুন
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="status"
                                       name="status"
                                       value="1"
                                       {{ old('status', 1) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="status">
                                    সক্রিয়
                                </label>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            <strong>ভেরিয়েবল ব্যবহার:</strong><br>
                            <code>{name}</code> - গ্রাহকের নাম<br>
                            <code>{email}</code> - গ্রাহকের ইমেইল<br>
                            <code>{unsubscribe_link}</code> - আনসাবস্ক্রাইব লিংক
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> টেমপ্লেট তৈরি করুন
                </button>
                <a href="{{ route('admin.newsletter.templates.index') }}" class="btn btn-default">
                    <i class="fas fa-times"></i> বাতিল
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#thumbnail').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-img').attr('src', e.target.result);
                $('#thumbnail-preview').show();
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
