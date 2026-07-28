@extends('admin.layouts.master')

@section('page-title', 'ডিজিটাল বুক - অধ্যায় ও ধারা সমূহের তালিকা')

@section('content')
    <div class="container-fluid px-4 py-3">
        <!-- Header Action -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><i class="fas fa-book-open text-success me-2"></i>ডিজিটাল বুক ও অধ্যায় সমূহের তালিকা
                </h4>
                <p class="text-muted small mb-0">বইয়ের অধ্যায়, ধারা ও বিষয়বস্তু পরিচালনা করুন</p>
            </div>
            <a href="{{ route('admin.book-chapters.create', ['book_id' => request('book_id')]) }}"
                class="btn btn-success px-4 py-2 fw-semibold">
                <i class="fas fa-plus me-1"></i> নতুন অধ্যায় যুক্ত করুন
            </a>
        </div>

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <form action="{{ route('admin.book-chapters.index') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted mb-1">বই নির্বাচন করুন</label>
                        <select name="book_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- সকল বই --</option>
                            @foreach ($books as $b)
                                <option value="{{ $b->id }}" {{ request('book_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->title }} ({{ $b->chapters_count ?? $b->chapters()->count() }} অধ্যায়)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted mb-1">অধ্যায় বা ধারা খুঁজুন</label>
                        <input type="text" name="search" class="form-control"
                            placeholder="অধ্যায়ের শিরোনাম বা নম্বর টাইপ করুন..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 text-end pt-4">
                        <button type="submit" class="btn btn-primary px-3 py-2 w-100"><i class="fas fa-search me-1"></i>
                            ফিল্টার</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3" style="width: 50px;">ক্রম</th>
                                <th>বইয়ের নাম</th>
                                <th>অধ্যায় নম্বর</th>
                                <th>অধ্যায়ের শিরোনাম</th>
                                <th class="text-center">স্ট্যাটাস</th>
                                <th class="text-end pe-3">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($chapters as $chapter)
                                <tr>
                                    <td class="ps-3 fw-bold text-muted">{{ $chapter->sort_order }}</td>
                                    <td>
                                        <span class="badge bg-soft-success text-success fw-bold">
                                            <i class="fas fa-book me-1"></i> {{ $chapter->book->title ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="fw-bold">{{ $chapter->chapter_number ?: '-' }}</td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $chapter->title }}</span>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.book-chapters.toggle-status', $chapter->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-sm border-0 {{ $chapter->is_active ? 'text-success' : 'text-danger' }}">
                                                <i
                                                    class="fas {{ $chapter->is_active ? 'fa-toggle-on fa-lg' : 'fa-toggle-off fa-lg' }}"></i>
                                                <span
                                                    class="ms-1 small fw-bold">{{ $chapter->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}</span>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('admin.book-chapters.edit', $chapter->id) }}"
                                            class="btn btn-sm btn-outline-primary me-1" title="এডিট">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.book-chapters.destroy', $chapter->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('আপনি কি নিশ্চিত যে এই অধ্যায়টি মুছে ফেলতে চান?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="মুছে ফেলুন">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-book-open fa-3x mb-3 text-secondary opacity-50"></i>
                                        <h5>কোনো অধ্যায় পাওয়া যায়নি!</h5>
                                        <p class="small">উপরে "নতুন অধ্যায় যুক্ত করুন" বাটনে ক্লিক করে অধ্যায় যোগ করুন।</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($chapters->hasPages())
                <div class="card-footer bg-white py-3">
                    {{ $chapters->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
