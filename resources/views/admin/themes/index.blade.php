@extends('admin.layouts.master')

@section('page-title', 'Theme Management')

@push('styles')
<style>
    /* Modern Dashboard Header */
    .theme-mgmt-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 16px;
        padding: 24px 30px;
        margin-bottom: 30px;
        color: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .theme-mgmt-title {
        font-size: 22px;
        font-weight: 800;
        margin: 0;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .theme-mgmt-subtitle {
        color: #94a3b8;
        font-size: 13px;
        margin-top: 5px;
        font-weight: 400;
    }

    /* Active Theme Container (Hero Design) */
    .active-theme-container {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        border: 2px solid #10b981; /* Glowing emerald green border */
        overflow: hidden;
        margin-bottom: 40px;
        position: relative;
    }

    .active-theme-badge-ribbon {
        position: absolute;
        top: 20px;
        left: 20px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 12px;
        padding: 6px 14px;
        border-radius: 30px;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .active-theme-body {
        padding: 30px;
    }

    .active-theme-screenshot {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        transition: transform 0.3s ease;
        max-height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
    }

    .active-theme-screenshot img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .active-theme-screenshot:hover {
        transform: scale(1.02);
    }

    .active-theme-details {
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
    }

    .active-theme-title {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .active-theme-meta {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 15px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .active-theme-meta span {
        background: #f1f5f9;
        padding: 4px 12px;
        border-radius: 6px;
        font-weight: 500;
    }

    .active-theme-desc {
        font-size: 14px;
        color: #334155;
        line-height: 1.6;
        margin-bottom: 25px;
    }

    /* Available Themes Grid & Cards */
    .section-title-custom {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title-custom::after {
        content: '';
        flex-grow: 1;
        height: 1px;
        background: #e2e8f0;
    }

    .theme-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .theme-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
    }

    .theme-card-img-container {
        position: relative;
        height: 190px;
        background: #f8fafc;
        overflow: hidden;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .theme-card-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .theme-card:hover .theme-card-img-container img {
        transform: scale(1.05);
    }

    .theme-card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.6);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        z-index: 5;
    }

    .theme-card-img-container:hover .theme-card-overlay {
        opacity: 1;
    }

    .theme-card-body {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .theme-card-title {
        font-size: 17px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .theme-card-meta {
        font-size: 11px;
        color: #64748b;
        margin-bottom: 12px;
    }

    .theme-card-desc {
        font-size: 13px;
        color: #475569;
        line-height: 1.5;
        margin-bottom: 15px;
        flex-grow: 1;
    }

    /* Custom buttons and actions */
    .theme-btn {
        font-weight: 600;
        font-size: 12px;
        padding: 6px 14px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    /* Upload Theme Dropzone Style */
    .custom-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .custom-dropzone:hover, .custom-dropzone.dragover {
        border-color: #10b981;
        background: #f0fdf4;
    }

    .custom-dropzone-icon {
        font-size: 40px;
        color: #64748b;
        margin-bottom: 15px;
        transition: color 0.3s ease;
    }

    .custom-dropzone:hover .custom-dropzone-icon {
        color: #10b981;
    }

    .custom-dropzone input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- ১. আধুনিক পেজ হেডার --}}
    <div class="theme-mgmt-header">
        <div>
            <h3 class="theme-mgmt-title">
                <i class="fas fa-palette text-success"></i>Theme Management
            </h3>
            <span class="theme-mgmt-subtitle">বগুড়া বাজারের ভিজ্যুয়াল চেহারা এবং থিম নিয়ন্ত্রণ করুন</span>
        </div>
        <div>
            <button type="button" class="btn btn-light fw-bold" data-bs-toggle="modal" data-bs-target="#uploadThemeModal" style="border-radius: 10px; padding: 8px 18px;">
                <i class="fas fa-upload text-success me-1"></i> নতুন থিম আপলোড করুন
            </button>
        </div>
    </div>

    {{-- ২. হিরো সেকশন: সক্রিয় থিম --}}
    @php
        $activeThemeItem = null;
        foreach($themes as $theme) {
            if ($theme['is_active']) {
                $activeThemeItem = $theme;
                break;
            }
        }
    @endphp

    @if($activeThemeItem)
    <div class="active-theme-container">
        <div class="active-theme-badge-ribbon">
            <i class="fas fa-check-circle"></i> সক্রিয় থিম (Active Theme)
        </div>

        <div class="active-theme-body">
            <div class="row align-items-center">
                <div class="col-md-5 col-lg-4 mb-4 mb-md-0">
                    <div class="active-theme-screenshot">
                        @if(isset($activeThemeItem['screenshot_url']))
                            <img src="{{ $activeThemeItem['screenshot_url'] }}" alt="{{ $activeThemeItem['name'] }}">
                        @else
                            <div class="text-secondary d-flex align-items-center justify-content-center w-100" style="height: 220px; background: #f1f5f9;">
                                <i class="fas fa-palette fa-4x text-muted"></i>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-7 col-lg-8">
                    <div class="active-theme-details">
                        <h4 class="active-theme-title">{{ $activeThemeItem['name'] }}</h4>
                        <div class="active-theme-meta">
                            <span>ভার্সন: <strong>{{ $activeThemeItem['version'] }}</strong></span>
                            <span>লেখক: <strong>{{ $activeThemeItem['author'] }}</strong></span>
                            @if($activeThemeItem['is_core'])
                                <span class="bg-success-subtle text-success">সিস্টেম কোর থিম</span>
                            @endif
                        </div>
                        <p class="active-theme-desc">
                            {{ $activeThemeItem['description'] ?: 'এই থিমটির জন্য কোনো বিবরণ দেওয়া নেই।' }}
                        </p>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ url('/') }}" target="_blank" class="btn btn-success theme-btn">
                                <i class="fas fa-external-link-alt"></i> ওয়েবসাইট ভিজিট করুন
                            </a>

                            @if(isset($activeThemeItem['id']) && $activeThemeItem['id'] && count($themes) > 1)
                                <button class="btn btn-outline-warning theme-btn deactivate-theme" data-id="{{ $activeThemeItem['id'] }}">
                                    <i class="fas fa-ban"></i> নিষ্ক্রিয় করুন
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ৩. গ্রিড সেকশন: উপলব্ধ অন্যান্য থিমসমূহ --}}
    <h5 class="section-title-custom">
        <i class="fas fa-th-large text-secondary me-2"></i>Available Themes
    </h5>

    <div class="row">
        @php $hasOtherThemes = false; @endphp
        @foreach($themes as $theme)
            @if(!$theme['is_active'])
                @php $hasOtherThemes = true; @endphp
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="theme-card">
                        <div class="theme-card-img-container">
                            @if(isset($theme['screenshot_url']))
                                <img src="{{ $theme['screenshot_url'] }}" alt="{{ $theme['name'] }}">
                            @else
                                <div class="text-secondary d-flex align-items-center justify-content-center w-100 h-100 bg-light">
                                    <i class="fas fa-palette fa-3x text-muted"></i>
                                </div>
                            @endif
                            <div class="theme-card-overlay">
                                @if(isset($theme['id']) && $theme['id'])
                                    <a href="{{ route('admin.themes.preview', $theme['id']) }}" class="btn btn-sm btn-info fw-bold px-3">
                                        <i class="fas fa-eye me-1"></i> প্রিভিউ
                                    </a>
                                    <button class="btn btn-sm btn-success fw-bold px-3 activate-theme" data-id="{{ $theme['id'] }}">
                                        <i class="fas fa-check me-1"></i> সক্রিয় করুন
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="theme-card-body">
                            <h6 class="theme-card-title">
                                {{ $theme['name'] }}
                            </h6>
                            <div class="theme-card-meta">
                                ভার্সন: {{ $theme['version'] }} | লেখক: {{ $theme['author'] }}
                            </div>
                            <p class="theme-card-desc">
                                {{ Str::limit($theme['description'], 100) ?: 'এই থিমটির কোনো বিবরণ নেই।' }}
                            </p>
                        </div>
                        <div class="theme-card-footer">
                            @if(isset($theme['id']) && $theme['id'])
                                <a href="{{ route('admin.themes.preview', $theme['id']) }}" class="btn btn-xs btn-outline-info">
                                    <i class="fas fa-eye"></i> প্রিভিউ
                                </a>
                                <button class="btn btn-xs btn-success activate-theme" data-id="{{ $theme['id'] }}">
                                    <i class="fas fa-check-circle"></i> একটিভেট
                                </button>
                                @if(!($theme['is_core'] ?? false))
                                    <button class="btn btn-xs btn-outline-danger delete-theme" data-id="{{ $theme['id'] }}" data-name="{{ $theme['name'] }}">
                                        <i class="fas fa-trash"></i> ডিলিট
                                    </button>
                                @endif
                            @else
                                <span class="badge bg-secondary p-2"><i class="fas fa-exclamation-triangle"></i> থিম ডাটাবেসে নিবন্ধিত নয়</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        @if(!$hasOtherThemes)
            <div class="col-12">
                <div class="alert alert-light text-center py-5 shadow-sm" style="border-radius: 12px; border: 1px dashed #cbd5e1;">
                    <i class="fas fa-palette fa-3x text-muted mb-3"></i>
                    <h6 class="fw-bold text-secondary">কোনো অতিরিক্ত থিম পাওয়া যায়নি</h6>
                    <p class="text-muted small mb-0">নতুন থিম ব্যবহার করতে চাইলে ওপরের আপলোড বাটনটি ব্যবহার করে থিম জিপ ফাইল আপলোড করুন।</p>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- নতুন থিম আপলোড মোডাল --}}
<div class="modal fade" id="uploadThemeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-secondary"><i class="fas fa-upload text-success me-1"></i> নতুন থিম আপলোড করুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <form id="uploadThemeForm" enctype="multipart/form-data">
                    @csrf
                    <div class="custom-dropzone" id="themeDropzone">
                        <i class="fas fa-cloud-upload-alt custom-dropzone-icon"></i>
                        <h6 class="fw-bold mb-1 text-slate-700">থিম জিপ (.zip) ফাইলটি এখানে টেনে আনুন বা ক্লিক করুন</h6>
                        <p class="text-muted small mb-0">সর্বোচ্চ সাইজ: 10MB</p>
                        <input type="file" id="theme_zip" name="theme_zip" accept=".zip" required>
                    </div>
                    <div id="fileSelectedInfo" class="mt-2 text-center text-success small fw-bold" style="display: none;"></div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal" style="border-radius: 8px;">বাতিল</button>
                <button type="button" class="btn btn-success fw-bold px-4" id="uploadThemeBtn" style="border-radius: 8px;">
                    <i class="fas fa-arrow-circle-up me-1"></i> আপলোড শুরু করুন
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // ড্রপজোন ফাইল সিলেক্ট ইফেক্ট
    const fileInput = document.getElementById('theme_zip');
    const dropzone = document.getElementById('themeDropzone');
    const fileInfo = document.getElementById('fileSelectedInfo');

    if (fileInput && dropzone) {
        fileInput.addEventListener('change', function(e) {
            if (this.files && this.files.length > 0) {
                fileInfo.innerHTML = `<i class="fas fa-file-archive me-1"></i> নির্বাচিত ফাইল: ${this.files[0].name} (${(this.files[0].size / (1024 * 1024)).toFixed(2)} MB)`;
                fileInfo.style.display = 'block';
                dropzone.style.borderColor = '#10b981';
                dropzone.style.background = '#f0fdf4';
            }
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropzone.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropzone.classList.remove('dragover');
            }, false);
        });
    }

    // থিম একটিভেট
    document.querySelectorAll('.activate-theme').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            Swal.fire({
                title: 'নিশ্চিত?',
                text: "থিমটি সক্রিয় করতে চান?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'হ্যাঁ, সক্রিয় করুন',
                cancelButtonText: 'বাতিল'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'প্রক্রিয়াধীন...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                    fetch(`/admin/themes/${id}/activate`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                    })
                    .then(res => {
                        if (!res.ok) {
                            return res.json().then(err => { throw new Error(err.message || 'সার্ভার ত্রুটি ঘটেছে'); });
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message);
                            setTimeout(() => location.reload(), 1200);
                        } else {
                            Swal.fire('ত্রুটি!', data.message, 'error');
                        }
                    })
                    .catch(err => {
                        Swal.fire('ত্রুটি!', err.message || 'থিম একটিভেট করতে সমস্যা হয়েছে', 'error');
                    });
                }
            });
        });
    });

    // থিম ডিঅ্যাকটিভেট
    document.querySelectorAll('.deactivate-theme').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            Swal.fire({
                title: 'নিশ্চিত?',
                text: "থিমটি ডিঅ্যাকটিভেট করতে চান?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'হ্যাঁ, নিষ্ক্রিয় করুন',
                cancelButtonText: 'বাতিল'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'প্রক্রিয়াধীন...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                    fetch(`/admin/themes/${id}/deactivate`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                    })
                    .then(res => {
                        if (!res.ok) {
                            return res.json().then(err => { throw new Error(err.message || 'সার্ভার ত্রুটি ঘটেছে'); });
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message);
                            setTimeout(() => location.reload(), 1200);
                        } else {
                            Swal.fire('ত্রুটি!', data.message, 'error');
                        }
                    })
                    .catch(err => {
                        Swal.fire('ত্রুটি!', err.message || 'থিম ডিঅ্যাকটিভেট করতে সমস্যা হয়েছে', 'error');
                    });
                }
            });
        });
    });

    // থিম ডিলিট
    document.querySelectorAll('.delete-theme').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            Swal.fire({
                title: 'নিশ্চিত?',
                text: `"${name}" থিমটি চিরতরে ডিলিট করতে চান? এর ফাইলসমূহ আর পুনরুদ্ধার করা যাবে না।`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'হ্যাঁ, ডিলিট করুন',
                cancelButtonText: 'বাতিল'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'প্রক্রিয়াধীন...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                    fetch(`/admin/themes/${id}`, {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                    })
                    .then(res => {
                        if (!res.ok) {
                            return res.json().then(err => { throw new Error(err.message || 'সার্ভার ত্রুটি ঘটেছে'); });
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message);
                            setTimeout(() => location.reload(), 1200);
                        } else {
                            Swal.fire('ত্রুটি!', data.message, 'error');
                        }
                    })
                    .catch(err => {
                        Swal.fire('ত্রুটি!', err.message || 'থিম ডিলিট করতে সমস্যা হয়েছে', 'error');
                    });
                }
            });
        });
    });

    // থিম আপলোড
    document.getElementById('uploadThemeBtn')?.addEventListener('click', function() {
        const fileInputVal = fileInput.value;
        if (!fileInputVal) {
            toastr.error('দয়া করে একটি জিপ ফাইল সিলেক্ট করুন।');
            return;
        }

        const formData = new FormData(document.getElementById('uploadThemeForm'));
        Swal.fire({
            title: 'থিম আপলোড ও নিরাপত্তা স্ক্যান হচ্ছে...',
            text: 'দয়া করে কিছুক্ষণ অপেক্ষা করুন...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('{{ route("admin.themes.upload") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            body: formData
        })
        .then(res => {
            if (!res.ok) {
                return res.json().then(err => { throw new Error(err.message || 'থিম আপলোড ব্যর্থ হয়েছে'); });
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire('সফল!', data.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('ত্রুটি!', data.message, 'error');
            }
        })
        .catch(err => {
            Swal.fire('ত্রুটি!', err.message || 'থিম আপলোড করতে সমস্যা হয়েছে', 'error');
        });
    });
});
</script>
@endpush
