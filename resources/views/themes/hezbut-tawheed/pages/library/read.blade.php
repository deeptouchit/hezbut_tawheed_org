@extends('theme::layouts.app')

@section('title', 'পড়ুন: ' . $book->title . ' - ডিজিটাল লাইব্রেরী')
@section('meta_description', $book->description)

@push('styles')
<style>
    /* Reader Layout */
    .reader-container {
        display: flex;
        min-height: calc(100vh - 100px);
        background-color: #f8fafc;
        position: relative;
    }
    
    /* Left Sidebar: Book Selector */
    .reader-sidebar {
        width: 300px;
        background: #ffffff;
        border-right: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        transition: all 0.3s;
        z-index: 100;
    }
    .reader-sidebar.collapsed {
        margin-left: -300px;
    }
    .sidebar-header {
        padding: 15px 20px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .sidebar-book-list {
        overflow-y: auto;
        flex-grow: 1;
        padding: 15px;
    }
    .sidebar-book-item {
        display: flex;
        gap: 12px;
        padding: 10px;
        border-radius: 8px;
        text-decoration: none !important;
        color: #334155;
        margin-bottom: 10px;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    .sidebar-book-item:hover {
        background: #f1f5f9;
        color: #006A4E;
    }
    .sidebar-book-item.active {
        background: #ecfdf5;
        border-color: #a7f3d0;
        color: #006a4e;
        font-weight: bold;
    }
    .sidebar-book-item img {
        width: 45px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    /* Main Reader Viewport */
    .reader-viewport {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        transition: all 0.3s;
    }
    
    /* Reader Top Bar Controls */
    .reader-controls {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        position: sticky;
        top: 0;
        z-index: 90;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    
    /* Reader Content Area */
    .reader-content-scroll {
        overflow-y: auto;
        flex-grow: 1;
        padding: 40px 20px;
        display: flex;
        justify-content: center;
    }
    .reader-content-body {
        width: 100%;
        max-width: 800px;
        font-family: 'SolaimanLipi', 'Baloo Da 2', serif;
        font-size: 19px;
        line-height: 1.8;
        text-align: justify;
        transition: all 0.2s;
    }
    
    /* Themes */
    /* Light (Default) */
    .theme-light {
        background-color: #ffffff;
        color: #1e293b;
    }
    .theme-light .reader-content-scroll {
        background-color: #f8fafc;
    }
    .theme-light .reader-content-body {
        color: #1e293b;
    }
    
    /* Sepia (Soft Reading) */
    .theme-sepia {
        background-color: #f4ecd8;
        color: #5c4033;
    }
    .theme-sepia .reader-content-scroll {
        background-color: #ebe1c5;
    }
    .theme-sepia .reader-content-body {
        color: #4a2f13;
    }
    
    /* Dark (Night Reading) */
    .theme-dark {
        background-color: #1e1e1e;
        color: #e0e0e0;
    }
    .theme-dark .reader-content-scroll {
        background-color: #121212;
    }
    .theme-dark .reader-content-body {
        color: #d1d5db;
    }
    .theme-dark .reader-controls {
        background-color: #1e1e1e;
        border-bottom-color: #2d2d2d;
    }
    .theme-dark .reader-sidebar {
        background-color: #1e1e1e;
        border-right-color: #2d2d2d;
    }
    .theme-dark .sidebar-header {
        background-color: #181818;
        border-bottom-color: #2d2d2d;
        color: #ffffff;
    }
    .theme-dark .sidebar-book-item {
        color: #cbd5e1;
    }
    .theme-dark .sidebar-book-item:hover {
        background: #2a2a2a;
        color: #10B981;
    }
    .theme-dark .sidebar-book-item.active {
        background: #064e3b;
        border-color: #065f46;
        color: #34d399;
    }
    
    /* Control Buttons */
    .btn-control {
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #334155;
        padding: 5px 12px;
        border-radius: 6px;
        font-weight: bold;
        transition: all 0.2s;
    }
    .btn-control:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }
    .theme-dark .btn-control {
        background: #2d2d2d;
        border-color: #444;
        color: #e0e0e0;
    }
    .theme-dark .btn-control:hover {
        background: #3d3d3d;
        border-color: #555;
    }
    
    .theme-dot {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: inline-block;
        cursor: pointer;
        border: 2px solid transparent;
        transition: transform 0.2s;
    }
    .theme-dot:hover {
        transform: scale(1.15);
    }
    .theme-dot.active {
        border-color: #006A4E;
    }
    .theme-dark .theme-dot.active {
        border-color: #10B981;
    }
    
    .btn-gold {
        background-color: #a0663f !important;
        border-color: #a0663f !important;
        color: white !important;
    }
    .btn-gold:hover {
        background-color: #8b5532 !important;
        border-color: #8b5532 !important;
    }
</style>
@endpush

@section('content')

    <div class="reader-container theme-light" id="reader-container">
        
        <!-- Left Sidebar: Book Navigator -->
        <aside class="reader-sidebar" id="reader-sidebar">
            <div class="sidebar-header">
                <h6 class="fw-bold mb-0 text-success"><i class="fas fa-university me-1"></i> লাইব্রেরী তালিকা</h6>
                <button class="btn btn-sm btn-link p-0 text-muted d-lg-none" id="btn-close-sidebar">
                    <i class="fas fa-times fa-lg"></i>
                </button>
            </div>
            
            <div class="sidebar-book-list">
                @foreach($otherBooks as $ob)
                    <a href="{{ route('library.read', $ob->slug) }}" class="sidebar-book-item">
                        <img src="{{ $ob->image_url }}" alt="{{ $ob->title }}" loading="lazy">
                        <div style="flex-grow: 1; overflow: hidden;">
                            <div class="small fw-bold text-truncate" title="{{ $ob->title }}">{{ $ob->title }}</div>
                            <div class="text-muted" style="font-size: 11px;">{{ Str::limit($ob->writer ?? 'হেযবুত তওহীদ', 30) }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </aside>
        
        <!-- Main Reader -->
        <main class="reader-viewport">
            
            <!-- Controls Bar -->
            <div class="reader-controls">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-control btn-sm" id="btn-toggle-sidebar" title="তালিকা বন্ধ/চালু করুন">
                        <i class="fas fa-bars"></i>
                    </button>
                    <a href="{{ route('library.index') }}" class="btn btn-gold btn-sm rounded-pill px-3 fw-bold">
                        <i class="fas fa-arrow-left me-1"></i> লাইব্রেরী
                    </a>
                </div>
                
                <!-- Book Title -->
                <div class="text-truncate fw-bold text-dark-green d-none d-md-block" style="max-width: 350px; font-family: 'Baloo Da 2', sans-serif;" id="reader-book-title">
                    {{ $book->title }}
                </div>
                
                <!-- Font size and Theme controls -->
                <div class="d-flex align-items-center gap-3">
                    <!-- Font Sizing -->
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-control" id="font-dec" title="ফন্ট ছোট করুন">A-</button>
                        <button class="btn btn-control" id="font-inc" title="ফন্ট বড় করুন">A+</button>
                    </div>
                    
                    <!-- Reading Themes -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="theme-dot" id="theme-btn-light" style="background-color: #ffffff; border: 1px solid #cbd5e1;" title="Light Mode"></span>
                        <span class="theme-dot" id="theme-btn-sepia" style="background-color: #f4ecd8;" title="Sepia Mode"></span>
                        <span class="theme-dot" id="theme-btn-dark" style="background-color: #1e1e1e;" title="Dark Mode"></span>
                    </div>
                </div>
            </div>
            
            <!-- Reading Area -->
            <div class="reader-content-scroll">
                <article class="reader-content-body shadow-sm p-4 p-md-5 rounded-4 bg-white" id="reader-content-body">
                    <h2 class="fw-bold mb-3 text-center border-bottom border-light pb-3" style="color: #006A4E; font-family: 'Baloo Da 2', sans-serif;" id="content-book-title">
                        {{ $book->title }}
                    </h2>
                    @if($book->writer)
                        <div class="text-center text-muted fw-bold mb-4" style="font-family: 'Baloo Da 2', sans-serif;">
                            {{ $book->writer }}
                        </div>
                    @endif
                    
                    <div class="reader-text" id="reader-text">
                        @if(!empty(trim($book->content)))
                            {!! $book->content !!}
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-book-open fa-3x mb-3 text-secondary"></i>
                                <p class="mb-0">দুঃখিত, এই বইটির বিস্তারিত অন-লাইন পঠন সংস্করণ এখনো যুক্ত করা হয়নি।</p>
                                @if($book->pdf_url)
                                    <a href="{{ $book->pdf_url }}" target="_blank" class="btn btn-danger btn-sm mt-3 rounded-pill px-4 fw-bold">
                                        <i class="fas fa-file-pdf me-1"></i> পিডিএফ ফাইল ডাউনলোড করুন
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </article>
            </div>
            
        </main>
        
    </div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var container = $('#reader-container');
    var readerBody = $('#reader-content-body');
    var readerText = $('#reader-text');
    var sidebar = $('#reader-sidebar');
    
    // 1. Sidebar toggles
    $('#btn-toggle-sidebar').on('click', function() {
        sidebar.toggleClass('collapsed');
    });
    $('#btn-close-sidebar').on('click', function() {
        sidebar.addClass('collapsed');
    });
    
    // 2. Reading themes switcher
    function changeTheme(themeName) {
        container.removeClass('theme-light theme-sepia theme-dark').addClass('theme-' + themeName);
        
        // Adjust reader card background/border
        if (themeName === 'light') {
            readerBody.css({'background-color': '#ffffff', 'border-color': '#eef0f2', 'box-shadow': '0 4px 15px rgba(0,0,0,0.03)'});
            $('#reader-book-title, #content-book-title').css('color', '#006A4E');
        } else if (themeName === 'sepia') {
            readerBody.css({'background-color': '#f4ecd8', 'border-color': '#ebe1c5', 'box-shadow': 'none'});
            $('#reader-book-title, #content-book-title').css('color', '#5c4033');
        } else if (themeName === 'dark') {
            readerBody.css({'background-color': '#1e1e1e', 'border-color': '#2d2d2d', 'box-shadow': 'none'});
            $('#reader-book-title, #content-book-title').css('color', '#10B981');
        }
        
        $('.theme-dot').removeClass('active');
        $('#theme-btn-' + themeName).addClass('active');
        
        // Save user preference
        localStorage.setItem('reader-theme', themeName);
    }
    
    $('#theme-btn-light').on('click', function() { changeTheme('light'); });
    $('#theme-btn-sepia').on('click', function() { changeTheme('sepia'); });
    $('#theme-btn-dark').on('click', function() { changeTheme('dark'); });
    
    // Restore theme preference
    var savedTheme = localStorage.getItem('reader-theme') || 'light';
    changeTheme(savedTheme);
    
    // 3. Font Sizing Controls
    var defaultFontSize = parseInt(localStorage.getItem('reader-font-size')) || 19;
    readerText.css('font-size', defaultFontSize + 'px');
    
    $('#font-inc').on('click', function() {
        var size = parseInt(readerText.css('font-size'));
        if (size < 28) {
            size += 2;
            readerText.css('font-size', size + 'px');
            localStorage.setItem('reader-font-size', size);
        }
    });
    
    $('#font-dec').on('click', function() {
        var size = parseInt(readerText.css('font-size'));
        if (size > 15) {
            size -= 2;
            readerText.css('font-size', size + 'px');
            localStorage.setItem('reader-font-size', size);
        }
    });
});
</script>
@endpush


