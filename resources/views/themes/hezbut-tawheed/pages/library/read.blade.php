@extends('theme::layouts.app')

@section('title', 'পড়ুন: ' . $book->title . ' - ডিজিটাল লাইব্রেরী')
@section('meta_description', $book->description)

@push('styles')

@endpush

@section('content')

    <div class="reader-container" id="reader-container">
        
        <!-- Left Sidebar: Book Navigator -->
        <aside class="reader-sidebar" id="reader-sidebar">
            <div class="sidebar-header">
                <h6 class="fw-bold mb-0" style="font-family: 'Baloo Da 2', sans-serif; color: #006a4e;"><i class="fas fa-university me-1"></i> লাইব্রেরী সূচী</h6>
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
                <div class="text-truncate fw-bold d-none d-md-block" style="max-width: 450px; font-family: 'Baloo Da 2', sans-serif; font-size: 1.15rem; color: #006a4e;" id="reader-book-title">
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
                    <iframe src="{{ asset($book->pdf_url) }}#toolbar=1&navpanes=0&scrollbar=1&page=1" width="100%" height="100%" class="pdf-desktop-iframe" style="border: none; background: #525659;"></iframe>
                    
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
                    <div style="padding: 3rem; overflow-y: auto; height: 100%; color: #0f172a; font-family: 'SolaimanLipi', serif; line-height: 1.8; font-size: 1.2rem; background: #ffffff;">
                        <div style="max-width: 800px; margin: 0 auto;">
                            <h2 class="text-center fw-bold mb-4" style="color: #006a4e;">{{ $book->title }}</h2>
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
