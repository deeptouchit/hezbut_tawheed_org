@extends('theme::layouts.app')

@section('title', 'নেতৃত্ব পরিচিতি - হেযবুত তওহীদ')
@section('meta_description', 'হেযবুত তওহীদ আন্দোলনের প্রতিষ্ঠাতা, কেন্দ্রীয় নেতৃত্ব, উপদেষ্টা পরিষদ এবং নির্বাহী পরিষদের সদস্যদের পরিচিতি ও জীবনী')

@section('content')

    <!-- Banner Header -->
    <div class="py-4 text-white position-relative" style="background: linear-gradient(135deg, rgba(0,106,78,0.95), rgba(0,120,88,0.9)), url('https://images.unsplash.com/photo-1541872703-74c5e44368f9?q=80&w=1200') no-repeat center center; background-size: cover; border-bottom: 4px solid #10B981;">
        <div class="container text-center">
            <h1 class="fw-bold mb-1 text-white" style="font-family: 'Baloo Da 2', sans-serif; font-size: 2.2rem;"><i class="fas fa-user-tie me-2"></i> নেতৃত্ব পরিচিতি</h1>
            <p class="lead small mb-0 opacity-90 text-white" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.05rem;">হেযবুত তওহীদের আদর্শ ও কার্যক্রম এগিয়ে নিয়ে যাওয়া কেন্দ্রীয় ও বিভিন্ন স্তরের নেতৃত্ববৃন্দ</p>
        </div>
    </div>

    <!-- Spotlight Section: Emam/Founder Spotlight -->
    @if($founders->count() > 0)
    <section class="py-5 bg-white position-relative">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-gold-gradient text-dark px-3 py-2 rounded-pill fw-bold text-uppercase mb-2 shadow-sm" style="font-size: 0.8rem; letter-spacing: 0.5px;"><i class="fas fa-star text-warning"></i> সর্বোচ্চ নেতৃত্ব ও প্রতিষ্ঠাতা</span>
                <h2 class="fw-bold text-dark-green" style="font-family: 'Baloo Da 2', sans-serif; font-size: 2rem;">অনুপ্রেরণার মূল ভিত্তি</h2>
                <div class="mx-auto" style="width: 50px; height: 3px; background-color: #006A4E; border-radius: 10px;"></div>
            </div>

            <div class="row justify-content-center g-4">
                @foreach($founders as $founder)
                <div class="col-lg-10">
                    <div class="card spotlight-card border-0 shadow rounded-4 p-4 bg-white position-relative">
                        <div class="row g-4 align-items-center">
                            <!-- Left: Profile portrait with signature (Spotlight Full Cover style) -->
                            <div class="col-md-4 text-center">
                                <div class="portrait-container mx-auto position-relative cursor-pointer drawer-trigger" data-id="{{ $founder->id }}">
                                    <div class="overflow-hidden rounded-3 shadow-sm border border-1 border-light bg-light" style="width: 190px; height: 240px;">
                                        <img src="{{ $founder->image_url }}" alt="{{ $founder->name }}" class="w-100 h-100 zoom-effect" style="object-fit: cover;">
                                    </div>
                                </div>
                                <h4 class="fw-bold text-dark-green mt-3 mb-1" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.35rem;">{{ $founder->name }}</h4>
                                <p class="text-muted small mb-2" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.85rem;">{{ $founder->designation }}</p>
                                
                                @if($founder->signature_image)
                                    <div class="mt-2 text-center">
                                        <img src="{{ $founder->signature_url }}" alt="Signature" style="max-height: 40px; object-fit: contain;">
                                    </div>
                                @endif
                            </div>

                            <!-- Right: Quotes and bio summaries -->
                            <div class="col-md-8">
                                @if($founder->quote)
                                    <div class="position-relative ps-4 mb-3" style="border-left: 4px solid #10B981;">
                                        <blockquote class="blockquote mb-0">
                                            <p class="text-dark fw-bold" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.1rem; line-height: 1.6; color: #1e293b !important;">
                                                "{{ $founder->quote }}"
                                            </p>
                                        </blockquote>
                                    </div>
                                @endif

                                <div class="text-muted small mb-4" style="font-family: 'Baloo Da 2', sans-serif; line-height: 1.8; text-align: justify; font-size: 0.95rem;">
                                    {{ Str::limit(strip_tags($founder->bio), 350, '...') }}
                                </div>

                                <div class="d-flex gap-2 flex-wrap">
                                    <button class="btn btn-success btn-sm rounded-pill px-4 py-2 fw-bold drawer-trigger" data-id="{{ $founder->id }}">
                                        <i class="fas fa-address-card me-1"></i> বিস্তারিত পরিচিতি
                                    </button>
                                    <a href="{{ route('leadership.show', $founder->slug) }}" class="btn btn-outline-success btn-sm rounded-pill px-4 py-2 fw-bold">
                                        <i class="fas fa-external-link-alt me-1"></i> পূর্ণাঙ্গ প্রোফাইল
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- All Leadership List Section -->
    <section class="py-5 bg-off-white" style="background-color: #f8fafc; border-top: 1px solid #e2e8f0;">
        <div class="container">
            
            <div class="text-center mb-5">
                <span class="badge bg-green-soft text-success px-3 py-2 rounded-pill fw-bold text-uppercase mb-2" style="font-size: 0.75rem;"><i class="fas fa-users"></i> হেযবুত তওহীদ পরিষদ</span>
                <h3 class="fw-bold text-dark-green" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.75rem;">নেতৃত্বের তালিকা</h3>
                <div class="mx-auto" style="width: 40px; height: 3px; background-color: #10B981; border-radius: 10px;"></div>
            </div>

            <!-- Smart Filter Bar -->
            <div class="card border-0 shadow-sm rounded-4 p-3 mb-5 bg-white" id="filter-card-container">
                <div class="row g-3 align-items-center">
                    <!-- Filter pills -->
                    <div class="col-lg-9">
                        <div class="d-flex flex-wrap gap-2" id="filter-controls">
                            <button class="btn btn-filter active rounded-pill px-4 py-2 fw-bold" data-filter="all">সকল নেতৃত্ব</button>
                            <button class="btn btn-filter rounded-pill px-4 py-2 fw-bold" data-filter="central">কেন্দ্রীয় নেতৃত্ব</button>
                            <button class="btn btn-filter rounded-pill px-4 py-2 fw-bold" data-filter="advisory">উপদেষ্টা পরিষদ</button>
                            <button class="btn btn-filter rounded-pill px-4 py-2 fw-bold" data-filter="executive">নির্বাহী কমিটি</button>
                            <button class="btn btn-filter rounded-pill px-4 py-2 fw-bold" data-filter="regional">আঞ্চলিক নেতৃত্ব</button>
                        </div>
                    </div>
                    <!-- Search input -->
                    <div class="col-lg-3">
                        <div class="input-group input-group-sm rounded-pill overflow-hidden border border-light shadow-sm">
                            <span class="input-group-text bg-white border-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" id="leader-search" class="form-control border-0 px-2 py-2" placeholder="নাম বা পদবী খুঁজুন..." autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leadership Grid -->
            <div class="row g-4" id="leaders-grid">
                @php
                    $allLeaders = $central->concat($advisory)->concat($executive)->concat($regional);
                @endphp
                
                @forelse($allLeaders as $leader)
                    <div class="col-lg-3 col-md-6 mb-4 leader-card-item" 
                         data-category="{{ $leader->category }}" 
                         data-name="{{ strtolower($leader->name) }} {{ strtolower($leader->english_name) }}"
                         data-designation="{{ strtolower($leader->designation) }}">
                        <div class="card member-grid-card h-100 border-0 shadow-sm rounded-4 bg-white text-center hover-grow transition">
                            
                            <!-- Full Cover Edge-to-Edge Portrait Header -->
                            <div class="avatar-wrapper position-relative overflow-hidden cursor-pointer drawer-trigger" 
                                 data-id="{{ $leader->id }}"
                                 style="width: 100%; height: 230px;">
                                <img src="{{ $leader->image_url }}" alt="{{ $leader->name }}" class="w-100 h-100 zoom-effect" style="object-fit: cover;">
                            </div>
                            
                            <!-- Card Body -->
                            <div class="card-body p-3.5 d-flex flex-column justify-content-between flex-grow-1" style="min-height: 130px;">
                                <div class="mt-2">
                                    <h6 class="fw-bold text-dark mb-3 cursor-pointer drawer-trigger" data-id="{{ $leader->id }}" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.15rem; line-height: 1.4;">
                                        {{ $leader->name }}
                                    </h6>
                                </div>
                                
                                <div>
                                    <!-- Details buttons -->
                                    <div class="d-flex justify-content-center gap-2 mb-3">
                                        <button class="btn btn-view-profile rounded-pill px-3 py-2 fw-bold drawer-trigger" data-id="{{ $leader->id }}">
                                            <i class="fas fa-info-circle me-1"></i> প্রোফাইল
                                        </button>
                                        <a href="{{ route('leadership.show', $leader->slug) }}" class="btn btn-outline-success btn-view-profile rounded-pill px-3 py-2 fw-bold">
                                            পূর্ণাঙ্গ পেজ <i class="fas fa-chevron-right ms-1"></i>
                                        </a>
                                    </div>
                                    
                                    <!-- Designation block at the very bottom -->
                                    <div class="designation-strip">
                                        {{ $leader->designation }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="fas fa-users-slash fa-3x mb-3 text-secondary"></i>
                        <p class="mb-0">কোনো নেতৃত্বের প্রোফাইল পাওয়া যায়নি।</p>
                    </div>
                @endforelse
            </div>
            
        </div>
    </section>

    <!-- Slide-in Profile Drawer Backdrop overlay -->
    <div id="drawer-backdrop" class="drawer-backdrop" style="display: none;"></div>

    <!-- Frosted-Glass Slide-in Profile Drawer -->
    <div id="profile-drawer" class="profile-drawer shadow-lg">
        <div class="drawer-header d-flex align-items-center justify-content-between p-3.5 bg-white border-bottom">
            <h5 class="fw-bold text-dark-green mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.1rem;"><i class="fas fa-user-circle me-2 text-success"></i> প্রোফাইল বিবরণী</h5>
            <button id="close-drawer-btn" class="btn btn-close border-0 bg-transparent" aria-label="Close"><i class="fas fa-times text-muted fs-5"></i></button>
        </div>
        
        <div class="drawer-body p-4 overflow-auto">
            <!-- Loading Indicator Inside Drawer -->
            <div id="drawer-loading" class="text-center py-5">
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">লোড হচ্ছে...</span>
                </div>
                <div class="text-muted small mt-2">প্রোফাইল বিবরণ লোড হচ্ছে...</div>
            </div>

            <!-- Loaded profile content -->
            <div id="drawer-content" style="display: none;">
                <!-- Profile picture & main titles (Drawer cover format) -->
                <div class="text-center mb-4">
                    <div class="mx-auto position-relative mb-3" style="width: 140px; height: 180px;">
                        <div class="overflow-hidden rounded shadow border border-3 border-white bg-light" style="width: 100%; height: 100%;">
                            <img id="dr-image" src="" alt="Leader Name" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                    </div>
                    <h4 class="fw-bold text-dark mb-1" id="dr-name" style="font-family: 'Baloo Da 2', sans-serif;"></h4>
                    <p class="text-muted mb-2 fw-semibold" id="dr-designation" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.9rem;"></p>
                    
                    <!-- Social icons inside drawer -->
                    <div class="d-flex justify-content-center gap-2 mt-2" id="dr-socials">
                        <!-- Filled in dynamically -->
                    </div>
                </div>

                <!-- Custom Quote -->
                <div class="card dr-quote-card border-0 rounded-3 p-3.5 mb-4 d-none" id="dr-quote-card">
                    <i class="fas fa-quote-left text-success opacity-20 fa-lg mb-2"></i>
                    <p class="fst-italic fw-semibold text-dark mb-0 small" id="dr-quote" style="line-height: 1.65; font-family: 'Baloo Da 2', sans-serif;"></p>
                </div>

                <!-- Biography details -->
                <div class="mb-4">
                    <h6 class="fw-bold text-dark-green mb-2 border-bottom pb-2" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-info-circle me-1 text-success"></i> জীবনবৃত্তান্ত</h6>
                    <p class="text-muted small" id="dr-bio" style="line-height: 1.8; text-align: justify; font-family: 'Baloo Da 2', sans-serif; font-size: 0.88rem;"></p>
                </div>

                <!-- Speech video player frame -->
                <div class="mb-4 d-none" id="dr-video-section">
                    <h6 class="fw-bold text-dark-green mb-2 border-bottom pb-2" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fab fa-youtube me-1 text-danger"></i> সরাসরি বক্তব্য / বার্তা</h6>
                    <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-sm bg-black">
                        <iframe id="dr-video-frame" src="" title="Speech Video" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>

                <!-- Dynamic Authored Books publications list -->
                <div class="mb-4 d-none" id="dr-books-section">
                    <h6 class="fw-bold text-dark-green mb-2 border-bottom pb-2" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-book me-1 text-warning"></i> নেতার রচিত বইসমূহ</h6>
                    <div class="row g-3" id="dr-books-list">
                        <!-- Filled in dynamically -->
                    </div>
                </div>

                <!-- Direct link profile page button -->
                <a href="" id="dr-full-profile-link" class="btn btn-success w-100 rounded-pill py-3 fw-bold text-white mb-3 shadow-sm" style="font-family: 'Baloo Da 2', sans-serif;">
                    <i class="fas fa-external-link-alt me-2"></i> পূর্ণাঙ্গ প্রোফাইল পেজ
                </a>
            </div>
        </div>
    </div>

    <!-- Custom CSS Styles for Slide Drawer and Animations -->
    <style>
        /* Org Chart Tree Layout Styling */
        .org-chart-container {
            width: 100%;
            overflow-x: auto;
            padding: 30px 15px;
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
            margin-bottom: 30px;
        }
        .org-chart {
            display: flex;
            justify-content: center;
            min-width: max-content;
            padding-bottom: 20px;
        }
        .org-chart ul {
            padding-top: 20px; 
            position: relative;
            transition: all 0.5s;
            display: flex;
            justify-content: center;
            margin: 0;
            padding-left: 0;
        }
        .org-chart li {
            text-align: center;
            list-style-type: none;
            position: relative;
            padding: 20px 8px 0 8px;
            transition: all 0.5s;
        }
        .org-chart li::before, .org-chart li::after {
            content: '';
            position: absolute;
            top: 0;
            right: 50%;
            border-top: 2px solid #cbd5e1;
            width: 50%;
            height: 20px;
        }
        .org-chart li::after {
            right: auto;
            left: 50%;
            border-left: 2px solid #cbd5e1;
        }
        .org-chart li:only-child::after, .org-chart li:only-child::before {
            display: none;
        }
        .org-chart li:only-child {
            padding-top: 0;
        }
        .org-chart li:first-child::before, .org-chart li:last-child::after {
            border: 0 none;
        }
        .org-chart li:last-child::before {
            border-right: 2px solid #cbd5e1;
            border-radius: 0 5px 0 0;
        }
        .org-chart li:first-child::after {
            border-radius: 5px 0 0 0;
        }
        .org-chart ul ul::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            border-left: 2px solid #cbd5e1;
            width: 0;
            height: 20px;
        }
        .org-node-card {
            display: inline-block;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 12px 15px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            text-decoration: none;
            transition: all 0.25s;
            position: relative;
            min-width: 150px;
            z-index: 10;
        }
        .org-node-card:hover {
            transform: translateY(-4px);
            border-color: #10B981;
            box-shadow: 0 8px 16px rgba(16, 185, 129, 0.08);
        }

        .p-3.5 {
            padding: 1.15rem !important;
        }
        .text-dark-green {
            color: #006A4E !important;
        }
        
        /* Premium Gradient Badges & Buttons */
        .bg-gold-gradient {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white !important;
        }
        .bg-green-soft {
            background-color: rgba(16, 185, 129, 0.1);
        }

        /* Spotlight Card Premium Designs */
        .spotlight-card {
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05) !important;
            border: 1px solid #e2e8f0 !important;
        }
        
        /* Filter Pills styling */
        .btn-filter {
            border: 1px solid #e2e8f0;
            color: #475569;
            background-color: #ffffff;
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }
        .btn-filter:hover {
            border-color: #10B981;
            color: #10B981;
        }
        .btn-filter.active {
            background-color: #006A4E;
            color: #ffffff !important;
            border-color: transparent !important;
            box-shadow: 0 4px 10px rgba(0, 106, 78, 0.15);
        }

        /* Grid cards layout - clean rectangular Wordpress style */
        .member-grid-card {
            border-radius: 16px !important;
            border: 1px solid #e2e8f0 !important;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .member-grid-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.08) !important;
            border-color: #cbd5e1 !important;
        }
        
        /* Edge to Edge Image Header wrapper styling */
        .avatar-wrapper {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            background-color: #f1f5f9;
        }
        .zoom-effect {
            transition: transform 0.4s ease;
        }
        .member-grid-card:hover .zoom-effect {
            transform: scale(1.04);
        }

        /* Designation strip at the bottom of card */
        .designation-strip {
            font-size: 0.95rem;
            color: #334155;
            background-color: #f8fafc;
            padding: 12px 15px;
            margin-left: -20px;
            margin-right: -20px;
            margin-bottom: -20px;
            border-top: 1px solid #e2e8f0;
            font-weight: 600;
            line-height: 1.35;
        }

        /* Social icons & view profile styling */
        .btn-view-profile {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
            font-size: 0.75rem;
            padding: 5px 12px;
            transition: all 0.2s;
        }
        .btn-view-profile:hover {
            background-color: #e2e8f0;
            color: #0f172a;
        }
        .btn-outline-success.btn-view-profile {
            border: 1px solid #10B981;
            color: #10B981;
            background-color: transparent;
        }
        .btn-outline-success.btn-view-profile:hover {
            background-color: #10B981;
            color: white;
        }

        /* Filter Grid animation */
        .leader-card-item {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .leader-card-item.filtered-out {
            opacity: 0;
            transform: scale(0.92);
            pointer-events: none;
        }

        /* Frosted Glass Profile Drawer */
        .profile-drawer {
            position: fixed;
            top: 0;
            right: -420px;
            width: 420px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            z-index: 1050;
            transition: right 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            border-left: 1px solid rgba(0,0,0,0.1);
        }
        .profile-drawer.open {
            right: 0;
        }
        .drawer-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);
            z-index: 1040;
        }
        .dr-quote-card {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-left: 4px solid #10B981 !important;
            box-shadow: 0 4px 6px rgba(0,0,0,0.01);
        }

        /* Books cards inside drawer */
        .drawer-book-card {
            background-color: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            padding: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.01);
            transition: all 0.25s;
        }
        .drawer-book-card:hover {
            transform: translateY(-2px);
            border-color: #10B981;
            box-shadow: 0 6px 12px rgba(16, 185, 129, 0.08);
        }
        .btn-xs {
            padding: 2.5px 8px;
            font-size: 10px;
            border-radius: 100px;
        }
    </style>

@endsection

@push('scripts')
<script>
$(document).ready(function() {

    // --------------------------------------------------
    // 1. Grid Filtering & Live Searches
    // --------------------------------------------------
    var activeCategory = 'all';

    function filterGrid() {
        var searchVal = $('#leader-search').val().toLowerCase().trim();
        
        $('.leader-card-item').each(function() {
            var card = $(this);
            var cat = card.data('category');
            var name = card.data('name');
            var designation = card.data('designation');

            // Category match
            var catMatch = (activeCategory === 'all' || cat === activeCategory);
            // Search match
            var searchMatch = (!searchVal || name.indexOf(searchVal) > -1 || designation.indexOf(searchVal) > -1);

            if (catMatch && searchMatch) {
                card.removeClass('filtered-out').show();
            } else {
                card.addClass('filtered-out').hide();
            }
        });
    }

    // Filter pill click
    $('#filter-controls button').on('click', function() {
        $('#filter-controls button').removeClass('active btn-success');
        $(this).addClass('active');
        
        activeCategory = $(this).data('filter');
        filterGrid();
    });

    // Real-time search keyup
    $('#leader-search').on('keyup', function() {
        filterGrid();
    });

    // --------------------------------------------------
    // 2. Frosted Glass Profile Drawer logic
    // --------------------------------------------------
    function openDrawer(id) {
        $('#profile-drawer').addClass('open');
        $('#drawer-backdrop').fadeIn(200);
        $('body').css('overflow', 'hidden'); // disable scroll

        // Show loading and hide content
        $('#drawer-loading').show();
        $('#drawer-content').hide();

        // Query leader details using AJAX
        $.ajax({
            url: "{{ url('/leadership/ajax') }}/" + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var leader = response.leader;
                    var books = response.books;

                    // Fill fields
                    $('#dr-image').attr('src', response.image_url);
                    $('#dr-name').text(leader.name);
                    $('#dr-designation').text(leader.designation);
                    $('#dr-bio').text(leader.bio || 'পরিচিতি এখনও যোগ করা হয়নি।');
                    
                    // Direct Profile URL
                    $('#dr-full-profile-link').attr('href', "{{ url('/leadership') }}/" + leader.slug);

                    // Handle Quote Card
                    if (leader.quote) {
                        $('#dr-quote').text(leader.quote);
                        $('#dr-quote-card').removeClass('d-none');
                    } else {
                        $('#dr-quote-card').addClass('d-none');
                    }

                    // Handle Video Speech
                    if (leader.speech_video_url) {
                        var videoUrl = leader.speech_video_url;
                        // Map standard youtube URLs to embed format if matching
                        if (videoUrl.includes('youtube.com/watch') || videoUrl.includes('youtu.be/')) {
                            var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                            var match = videoUrl.match(regExp);
                            if (match && match[2].length == 11) {
                                videoUrl = "https://www.youtube.com/embed/" + match[2] + "?autoplay=1";
                            }
                        }
                        $('#dr-video-frame').attr('src', videoUrl);
                        $('#dr-video-section').removeClass('d-none');
                    } else {
                        $('#dr-video-frame').attr('src', '');
                        $('#dr-video-section').addClass('d-none');
                    }

                    // Handle Social Media icons
                    var socialsHtml = '';
                    if (leader.facebook_url) {
                        socialsHtml += '<a href="' + leader.facebook_url + '" target="_blank" class="btn btn-outline-primary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Facebook"><i class="fab fa-facebook-f"></i></a>';
                    }
                    if (leader.twitter_url) {
                        socialsHtml += '<a href="' + leader.twitter_url + '" target="_blank" class="btn btn-outline-info btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Twitter"><i class="fab fa-twitter"></i></a>';
                    }
                    if (leader.linkedin_url) {
                        socialsHtml += '<a href="' + leader.linkedin_url + '" target="_blank" class="btn btn-outline-primary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>';
                    }
                    if (leader.email) {
                        socialsHtml += '<a href="mailto:' + leader.email + '" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Email"><i class="fas fa-envelope"></i></a>';
                    }
                    $('#dr-socials').html(socialsHtml);

                    // Handle Books lists
                    if (books.length > 0) {
                        var booksHtml = '';
                        books.forEach(function(book) {
                            booksHtml += '<div class="col-12">' +
                                            '<div class="drawer-book-card">' +
                                                '<div class="overflow-hidden rounded shadow-sm bg-light" style="width: 38px; height: 56px; flex-shrink: 0;">' +
                                                    '<img src="' + book.image + '" alt="' + book.title + '" class="w-100 h-100" style="object-fit: cover;">' +
                                                '</div>' +
                                                '<div class="flex-grow-1">' +
                                                    '<div class="fw-bold text-dark-green" style="font-size: 12px; line-height: 1.35; font-family: \'Baloo Da 2\', sans-serif;">' + book.title + '</div>' +
                                                    '<a href="' + book.read_url + '" class="btn btn-success btn-xs mt-2 px-3 py-1 fw-bold" style="font-size: 9px;"><i class="fas fa-book-open me-1"></i> পড়ুন</a>' +
                                                '</div>' +
                                            '</div>' +
                                         '</div>';
                        });
                        $('#dr-books-list').html(booksHtml);
                        $('#dr-books-section').removeClass('d-none');
                    } else {
                        $('#dr-books-section').addClass('d-none');
                    }

                    // Hide loading and show content
                    $('#drawer-loading').hide();
                    $('#drawer-content').fadeIn(200);
                } else {
                    closeDrawer();
                    toastr.error('প্রোফাইল বিবরণ লোড করতে ব্যর্থ হয়েছে');
                }
            },
            error: function() {
                closeDrawer();
                toastr.error('সার্ভারে সমস্যা হয়েছে');
            }
        });
    }

    // Trigger click on profile cards or triggers
    $(document).on('click', '.drawer-trigger', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        openDrawer(id);
    });

    // Close button click
    $('#close-drawer-btn, #drawer-backdrop').on('click', function() {
        closeDrawer();
    });

});
</script>
@endpush


