@extends('admin.layouts.master')

@section('page-title', 'অধ্যায় সম্পাদনা করুন')

@section('content')
    <div class="container-fluid px-4 py-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><i class="fas fa-edit text-primary me-2"></i>অধ্যায় সম্পাদনা করুন</h4>
                <p class="text-muted small mb-0">{{ $chapter->title }}</p>
            </div>
            <a href="{{ route('admin.book-chapters.index', ['book_id' => $chapter->book_id]) }}"
                class="btn btn-outline-secondary px-3 py-1.5">
                <i class="fas fa-arrow-left me-1"></i> তালিকায় ফিরে যান
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.book-chapters.update', $chapter->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">বই নির্বাচন করুন <span class="text-danger">*</span></label>
                            <select name="book_id" class="form-select @error('book_id') is-invalid @enderror" required>
                                @foreach ($books as $b)
                                    <option value="{{ $b->id }}"
                                        {{ old('book_id', $chapter->book_id) == $b->id ? 'selected' : '' }}>
                                        {{ $b->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('book_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">অধ্যায়ের নাম/নম্বর</label>
                            <input type="text" name="chapter_number"
                                class="form-control @error('chapter_number') is-invalid @enderror"
                                value="{{ old('chapter_number', $chapter->chapter_number) }}">
                            @error('chapter_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-9">
                            <label class="form-label fw-bold">অধ্যায়ের শিরোনাম/বিষয়বস্তু <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $chapter->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">ক্রমানুসার (Sort Order)</label>
                            <input type="number" name="sort_order" class="form-control"
                                value="{{ old('sort_order', $chapter->sort_order) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">অধ্যায়ের বিস্তারিত টেক্সট/ধারা (HTML/Text) <span
                                    class="text-danger">*</span></label>
                            <textarea name="content" class="form-control richtext @error('content') is-invalid @enderror" rows="12" required>{{ old('content', $chapter->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-bold">PDF ডাউনলোড ইউআরএল (ঐচ্ছিক)</label>
                            <input type="text" name="pdf_url" class="form-control"
                                value="{{ old('pdf_url', $chapter->pdf_url) }}">
                        </div>

                        <div class="col-md-4 d-flex align-items-center pt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                    value="1" {{ old('is_active', $chapter->is_active) ? 'checked' : '' }}>
                                <label class="form-check-input-label fw-bold ms-2" for="is_active">অনলাইন পেজে প্রদর্শন করুন
                                    (Active)</label>
                            </div>
                        </div>

                        <div class="col-12 text-end pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-5 py-2.5 fw-bold">
                                <i class="fas fa-save me-1"></i> পরিবর্তন আপডেট করুন
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
