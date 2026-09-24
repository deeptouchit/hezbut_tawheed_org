{{-- resources/views/admin/books/show.blade.php --}}

@extends('admin.layouts.master')

@section('page-title', 'বইয়ের বিস্তারিত - ' . $book->title)

@push('styles')
<style>
    .book-show-cover {
        width: 100%;
        max-width: 250px;
        height: 330px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        border: 1px solid #dee2e6;
        display: block;
        margin: 0 auto 20px auto;
    }
    .book-meta-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid #e9ecef;
    }
    .book-meta-card .meta-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
    }
    .book-meta-card .meta-item:last-child {
        border-bottom: none;
    }
    .book-meta-card .meta-label {
        font-weight: 600;
        color: #495057;
    }
    .book-meta-card .meta-value {
        color: #212529;
    }
    .book-content-section {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        line-height: 1.8;
        font-size: 15px;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card card-outline card-success shadow-sm mb-4">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-book me-2"></i> বইয়ের বিস্তারিত তথ্য
                <span class="badge bg-primary ms-2">#{{ $book->id }}</span>
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.books.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> ফিরে যান
                    </a>
                    <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> এডিট করুন
                    </a>
                    <a href="/books/{{ $book->slug }}" target="_blank" class="btn btn-info btn-sm">
                        <i class="fas fa-external-link-alt"></i> লাইভ দেখুন
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Left Column: Image Cover & Basic Info -->
                <div class="col-md-4 text-center border-end">
                    <img src="{{ $book->image_url }}" 
                         alt="{{ $book->title }}" 
                         class="book-show-cover"
                         onerror="this.src='https://ui-avatars.com/api/?name='+urlencode('{{ $book->title }}')+'&background=2F54EB&color=fff'">
                    
                    <h4 class="fw-bold mb-1">{{ $book->title }}</h4>
                    @if($book->writer)
                        <p class="text-muted"><i class="fas fa-pen-nib me-1"></i>{{ $book->writer }}</p>
                    @endif

                    <div class="d-grid gap-2 col-10 mx-auto mt-4">
                        @if($book->pdf_url)
                            <a href="{{ url($book->pdf_url) }}" target="_blank" class="btn btn-danger btn-sm">
                                <i class="fas fa-file-pdf me-1"></i> পিডিএফ ডাউনলোড করুন
                            </a>
                        @else
                            <button class="btn btn-secondary btn-sm" disabled>
                                <i class="fas fa-file-pdf me-1"></i> পিডিএফ সংস্করণ নেই
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Right Column: Meta Info & Content Details -->
                <div class="col-md-8">
                    <h5 class="fw-bold text-dark-green mb-3 border-bottom pb-2">বইয়ের বিবরণ ও কন্টেন্ট</h5>
                    
                    @if($book->description)
                        <div class="alert alert-info">
                            <h6 class="fw-bold"><i class="fas fa-info-circle me-1"></i> সংক্ষিপ্ত বিবরণ:</h6>
                            <p class="mb-0">{{ $book->description }}</p>
                        </div>
                    @endif

                    @if($book->content)
                        <div class="book-content-section shadow-none">
                            <h6 class="fw-bold text-secondary mb-2">সূচিপত্র ও বিস্তারিত সূচী:</h6>
                            <div>{!! $book->content !!}</div>
                        </div>
                    @else
                        <div class="text-muted py-3 text-center bg-light rounded border mb-3">
                            <i class="fas fa-info-circle me-1"></i> কোনো বিস্তারিত কন্টেন্ট দেওয়া নেই।
                        </div>
                    @endif

                    <div class="book-meta-card">
                        <h6 class="fw-bold text-dark-green border-bottom pb-2 mb-3"><i class="fas fa-tags me-1"></i> মেটা এবং ক্যাটাগরি তথ্য</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="meta-item">
                                    <span class="meta-label">ক্যাটাগরি:</span>
                                    <span class="meta-value">
                                        @if($book->category)
                                            <span class="badge bg-info text-white">{{ $book->category->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">স্ট্যাটাস:</span>
                                    <span class="meta-value">
                                        {!! $book->is_active ? '<span class="badge bg-success">সক্রিয়</span>' : '<span class="badge bg-warning">নিষ্ক্রিয়</span>' !!}
                                    </span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">স্ল্যাগ (Slug):</span>
                                    <span class="meta-value font-monospace small">{{ $book->slug }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="meta-item">
                                    <span class="meta-label">মূল্য:</span>
                                    <span class="meta-value">
                                        @if($book->price !== null && $book->price !== '')
                                            <span class="fw-bold">৳{{ $book->price }}</span>
                                            @if($book->old_price)
                                                <span class="text-decoration-line-through text-muted small ms-1" style="font-size: 11px;">৳{{ $book->old_price }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">ফ্রি/নেই</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">শীর্ষে (Popular):</span>
                                    <span class="meta-value">
                                        {!! $book->is_popular ? '<span class="badge bg-primary">হ্যাঁ</span>' : '<span class="badge bg-secondary">না</span>' !!}
                                    </span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">বেস্ট সেলার:</span>
                                    <span class="meta-value">
                                        {!! $book->is_bestseller ? '<span class="badge bg-primary">হ্যাঁ</span>' : '<span class="badge bg-secondary">না</span>' !!}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
