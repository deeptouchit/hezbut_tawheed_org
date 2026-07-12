@extends('theme::layouts.app')

@section('title', 'পড়ুন: ' . $book->title . ' - ডিজিটাল লাইব্রেরী')
@section('meta_description', $book->description)

@push('styles')
<style>
    /* ============================================================
       PREMIUM SMART PDF READER LAYOUT
       ============================================================ */
    .reader-container {
        display: flex;
        height: calc(100vh - 100px);
        background-color: #0f172a;
        position: relative;
        overflow: hidden;
    }

    /* Fullscreen Height Adjustment */
    #reader-container:fullscreen {
        height: 100vh !important;
    }
    #reader-container:-webkit-full-screen {
        height: 100vh !important;
    }
    #reader-container:-moz-full-screen {
        height: 100vh !important;
    }
    #reader-container:-ms-fullscreen {
        height: 100vh !important;
    }
    
    /* Left Sidebar: Book Selector */
    .reader-sidebar {
        width: 320px;
        background: #1e293b;
        border-right: 1px solid #334155;
        display: flex;
        flex-direction: column;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 100;
        flex-shrink: 0;
    }

    .reader-sidebar.collapsed {
        margin-left: -320px;
    }

    .sidebar-header {
        padding: 1.5rem;
        border-bottom: 1px solid #334155;
        background: #0f172a;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .sidebar-search-box {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #334155;
        background: #1e293b;
    }

    .sidebar-search-input {
        background: #0f172a;
        border: 1px solid #334155;
        color: #f8fafc;
        border-radius: 12px;
        padding: 0.6rem 1rem;
        width: 100%;
        font-size: 0.95rem;
        outline: none;
        transition: all 0.2s ease;
    }

    .sidebar-search-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }

    .sidebar-book-list {
        overflow-y: auto;
        flex-grow: 1;
        padding: 1.25rem;
    }

    /* Custom Scrollbar for Sidebar */
    .sidebar-book-list::-webkit-scrollbar {
        width: 6px;
    }
    .sidebar-book-list::-webkit-scrollbar-track {
        background: #1e293b;
    }
    .sidebar-book-list::-webkit-scrollbar-thumb {
        background: #475569;
        border-radius: 3px;
    }

    .sidebar-book-item {
        display: flex;
        gap: 1rem;
        padding: 0.85rem;
        border-radius: 14px;
        text-decoration: none !important;
        color: #cbd5e1;
        margin-bottom: 0.75rem;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        background: rgba(255, 255, 255, 0.02);
    }

    .sidebar-book-item:hover {
        background: rgba(255, 255, 255, 0.06);
        color: #3b82f6;
    }

    .sidebar-book-item.active {
        background: rgba(59, 130, 246, 0.15);
        border-color: rgba(59, 130, 246, 0.4);
        color: #60a5fa;
        font-weight: 700;
    }

    .sidebar-book-item img {
        width: 48px;
        height: 68px;
        object-fit: cover;
        border-radius: 6px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
    }
    
    /* Main Reader Viewport */
    .reader-viewport {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        background: #0f172a;
    }
    
    /* Reader Top Bar Controls */
    .reader-controls {
        background: #1e293b;
        border-bottom: 1px solid #334155;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 90;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
    }
    
    /* Control Buttons */
    .btn-control {
        border: 1px solid #475569;
        background: #0f172a;
        color: #cbd5e1;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-control:hover {
        background: #1e293b;
        color: #ffffff;
        border-color: #3b82f6;
    }
    
    .btn-gold {
        background: linear-gradient(135deg, #3b82f6, #1e40af) !important;
        border: none !important;
        color: white !important;
    }
    .btn-gold:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    /* PDF Viewer Wrapper */
    .pdf-viewer-container {
        flex-grow: 1;
        width: 100%;
        height: 100%;
        position: relative;
        background: #0f172a;
    }

    .mobile-reader-cta {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 4rem 2rem;
        color: #f8fafc;
        height: 100%;
    }

    .mobile-reader-cta i {
        font-size: 4rem;
        color: #3b82f6;
        margin-bottom: 1.5rem;
    }

    /* Desktop/Mobile Responsiveness */
    @media (max-width: 991.98px) {
        .reader-sidebar {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5);
        }
        .reader-sidebar.collapsed {
            left: -320px;
            margin-left: 0;
        }
    }

    @media (max-width: 767.98px) {
        /* Hide embedded PDF iframe on small screens to trigger native viewer */
        .pdf-desktop-iframe {
            display: none;
        }
        .mobile-reader-cta {
            display: flex;
        }
    }
</style>
@endpush

@section('content')

    <div class="reader-container" id="reader-container">
        
        <!-- Left Sidebar: Book Navigator -->
        <aside class="reader-sidebar" id="reader-sidebar">
            <div class="sidebar-header">
                <h6 class="fw-bold mb-0 text-white" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-university me-1 text-blue"></i> লাইব্রেরী সূচী</h6>
                <button class="btn btn-sm btn-link p-0 text-muted d-lg-none" id="btn-close-sidebar">
                    <i class="fas fa-times fa-lg"></i>
                </button>
            </div>

            <!-- Instant Search Box -->
            <div class="sidebar-search-box">
                <input type="text" id="sidebar-search" class="sidebar-search-input" placeholder="বইয়ের নাম খুঁজুন...">
            </div>
            
            <div class="sidebar-book-list" id="sidebar-book-list">
                <!-- Current Book listed at top for ease -->
                <a href="{{ route('library.read', $book->slug) }}" class="sidebar-book-item active">
                    <img src="{{ $book->image_url }}" alt="{{ $book->title }}" loading="lazy">
                    <div style="flex-grow: 1; overflow: hidden;">
                        <div class="small fw-bold text-truncate" title="{{ $book->title }}">{{ $book->title }}</div>
                        <div class="text-muted small" style="font-size: 11px;">{{ Str::limit($book->writer ?? 'হেযবুত তওহীদ', 30) }}</div>
                    </div>
                </a>

                @foreach($otherBooks as $ob)
                    <a href="{{ route('library.read', $ob->slug) }}" class="sidebar-book-item">
                        <img src="{{ $ob->image_url }}" alt="{{ $ob->title }}" loading="lazy">
                        <div style="flex-grow: 1; overflow: hidden;">
                            <div class="small fw-bold text-truncate" title="{{ $ob->title }}">{{ $ob->title }}</div>
                            <div class="text-muted small" style="font-size: 11px;">{{ Str::limit($ob->writer ?? 'হেযবুত তওহীদ', 30) }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </aside>
        
        <!-- Main Reader Viewport -->
        <main class="reader-viewport">
            
            <!-- Controls Bar -->
            <div class="reader-controls">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-control" id="btn-toggle-sidebar" title="সূচী বন্ধ/চালু করুন">
                        <i class="fas fa-bars"></i> <span class="d-none d-md-inline">সূচীপত্র</span>
                    </button>
                    <button class="btn btn-control" id="btn-fullscreen" title="ফুল স্ক্রিন ভিউ">
                        <i class="fas fa-expand"></i> <span class="d-none d-md-inline">ফুল স্ক্রিন</span>
                    </button>
                    <a href="{{ route('library.index') }}" class="btn btn-control btn-gold">
                        <i class="fas fa-arrow-left"></i> <span class="d-none d-md-inline">লাইব্রেরী</span>
                    </a>
                </div>
                
                <!-- Book Title -->
                <div class="text-truncate fw-bold text-white d-none d-md-block" style="max-width: 450px; font-family: 'Baloo Da 2', sans-serif; font-size: 1.15rem;" id="reader-book-title">
                    {{ $book->title }}
                </div>
                
                <!-- Download button -->
                <div>
                    @if($book->pdf_url)
                        <a href="{{ asset($book->pdf_url) }}" download class="btn btn-control" title="পিডিএফ ডাউনলোড করুন">
                            <i class="fas fa-download"></i> <span class="d-none d-md-inline">ডাউনলোড</span>
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- PDF Viewer Area -->
            <div class="pdf-viewer-container">
                @if($book->pdf_url)
                    <!-- Desktop Iframe View -->
                    <iframe src="{{ asset($book->pdf_url) }}#toolbar=1&navpanes=0&scrollbar=1&page=1" width="100%" height="100%" class="pdf-desktop-iframe" style="border: none; background: #0f172a;"></iframe>
                    
                    <!-- Mobile View CTA -->
                    <div class="mobile-reader-cta">
                        <i class="fas fa-file-pdf"></i>
                        <h4 class="fw-bold mb-3" style="font-family: 'Baloo Da 2', sans-serif;">{{ $book->title }}</h4>
                        <p class="text-muted mb-4 small px-4">মোবাইল স্ক্রিনে স্বাচ্ছন্দ্যে পড়ার জন্য নিচের লিংকে ক্লিক করে সরাসরি পিডিএফ সংস্করণটি ওপেন করুন।</p>
                        <a href="{{ asset($book->pdf_url) }}" target="_blank" class="btn btn-control btn-gold btn-lg px-5 py-3 rounded-pill fw-bold fs-6">
                            <i class="fas fa-book-open"></i> পিডিএফ ফাইল খুলুন
                        </a>
                    </div>
                @else
                    <!-- Fallback to text reading if no PDF is loaded -->
                    <div style="padding: 3rem; overflow-y: auto; height: 100%; color: #f8fafc; font-family: 'SolaimanLipi', serif; line-height: 1.8; font-size: 1.2rem;">
                        <div style="max-width: 800px; margin: 0 auto;">
                            <h2 class="text-center fw-bold mb-4" style="color: #60a5fa;">{{ $book->title }}</h2>
                            {!! $book->content !!}
                        </div>
                    </div>
                @endif
            </div>
            
        </main>
        
    </div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var sidebar = $('#reader-sidebar');
    
    // 1. Sidebar Toggles
    $('#btn-toggle-sidebar').on('click', function() {
        sidebar.toggleClass('collapsed');
    });
    $('#btn-close-sidebar').on('click', function() {
        sidebar.addClass('collapsed');
    });
    
    // 2. Instant JS Sidebar Search Filter
    $('#sidebar-search').on('keyup', function() {
        var query = $(this).val().toLowerCase();
        
        $('.sidebar-book-item').each(function() {
            var title = $(this).find('.fw-bold').text().toLowerCase();
            if (title.indexOf(query) > -1) {
                $(this).show();
            } else {
                // Keep the active item visible
                if ($(this).hasClass('active')) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });
    });

    // 3. Fullscreen View Toggle
    $('#btn-fullscreen').on('click', function() {
        var elem = document.getElementById('reader-container');
        var icon = $(this).find('i');
        var text = $(this).find('span');
        
        if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.mozFullScreenElement && !document.msFullscreenElement) {
            // Enter Fullscreen
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            }
            icon.removeClass('fa-expand').addClass('fa-compress');
            text.text('ছোট করুন');
        } else {
            // Exit Fullscreen
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
            icon.removeClass('fa-compress').addClass('fa-expand');
            text.text('ফুল স্ক্রিন');
        }
    });

    // Reset icons when exiting fullscreen via ESC key
    function exitHandler() {
        var icon = $('#btn-fullscreen').find('i');
        var text = $('#btn-fullscreen').find('span');
        if (!document.fullscreenElement && !document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {
            icon.removeClass('fa-compress').addClass('fa-expand');
            text.text('ফুল স্ক্রিন');
        }
    }
    
    document.addEventListener('fullscreenchange', exitHandler);
    document.addEventListener('webkitfullscreenchange', exitHandler);
    document.addEventListener('mozfullscreenchange', exitHandler);
    document.addEventListener('MSFullscreenChange', exitHandler);
});
</script>
@endpush
