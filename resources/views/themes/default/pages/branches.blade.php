@extends('theme::layouts.app')

@section('title', 'কার্যালয় ও শাখা সমূহ - হেযবুত তওহীদ')
@section('meta_description', 'হেযবুত তওহীদ আন্দোলনের কেন্দ্রীয় কার্যালয়, বিভাগীয়, জেলা, উপজেলা এবং আন্তর্জাতিক শাখাসমূহের ঠিকানা ও যোগাযোগের নম্বর')

@push('styles')
<style>
    .fw-extrabold {
        font-weight: 800 !important;
    }
    .text-shadow-sm {
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .max-w-2xl {
        max-width: 650px;
    }
    .text-success-light {
        color: #10B981 !important;
    }
    .border-light-grey {
        border: 1px solid #f1f5f9 !important;
    }
    .gap-2.5 {
        gap: 0.65rem !important;
    }
    .p-3.5 {
        padding: 1.15rem !important;
    }
    .pt-4 {
        padding-top: 0.9rem !important;
    }
    .mt-4 {
        margin-top: 0.9rem !important;
    }
    .object-cover {
        object-fit: cover;
    }
    
    /* Grid background pattern */
    .grid-pattern-mask {
        background-image: radial-gradient(rgba(0, 106, 78, 0.05) 1.5px, transparent 0);
        background-size: 24px 24px;
    }
    
    /* Bubble Background Animation */
    @keyframes float-bubble {
        0% { transform: scale(1) translate(0, 0); }
        50% { transform: scale(1.15) translate(40px, 30px); }
        100% { transform: scale(0.9) translate(-20px, -30px); }
    }
    
    /* Premium Card Layout */
    .branch-glass-card {
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.01) !important;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        background: #ffffff;
    }
    .branch-glass-card:hover {
        transform: translateY(-8px);
        border-color: rgba(16, 185, 129, 0.25) !important;
        box-shadow: 0 25px 45px rgba(16, 185, 129, 0.07) !important;
    }
    .zoom-element {
        transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .branch-glass-card:hover .zoom-element {
        transform: scale(1.08);
    }
    .image-gradient-shade {
        background: linear-gradient(180deg, rgba(0,0,0,0) 60%, rgba(0,0,0,0.15) 100%);
        pointer-events: none;
    }
    .bg-hover-light:hover {
        background-color: #f8fafc !important;
    }
    
    /* Copy address styling */
    .copy-address-btn {
        opacity: 0;
        transition: opacity 0.2s ease, color 0.2s;
    }
    .address-box:hover .copy-address-btn {
        opacity: 1;
    }
    .copy-address-btn:hover {
        color: #10B981 !important;
    }
    
    /* Gradients */
    .bg-danger-gradient {
        background: linear-gradient(135deg, #ef4444, #b91c1c) !important;
    }
    .bg-purple-gradient {
        background: linear-gradient(135deg, #8b5cf6, #5b21b6) !important;
    }
    .bg-success-gradient {
        background: linear-gradient(135deg, #10b981, #047857) !important;
    }
    
    /* Filter Pills styling */
    .btn-filter {
        border: 1px solid #f1f5f9;
        color: #475569;
        background-color: #ffffff;
        transition: all 0.25s ease;
        font-size: 0.88rem;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        font-family: 'Baloo Da 2', sans-serif;
        white-space: nowrap;
    }
    .btn-filter:hover {
        border-color: #10B981;
        color: #10B981;
        transform: translateY(-1px);
    }
    .btn-filter.active {
        background: linear-gradient(135deg, #006A4E, #047857) !important;
        color: #ffffff !important;
        border-color: transparent !important;
        box-shadow: 0 6px 14px rgba(0, 106, 78, 0.25) !important;
    }
    
    /* Custom mini contact link buttons */
    .small-text-link {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        text-decoration: none !important;
        transition: color 0.2s;
        font-family: 'Baloo Da 2', sans-serif;
    }
    .small-text-link:hover {
        color: #10B981;
    }
    
    .hover-glow-btn {
        transition: all 0.2s;
        border-color: #cbd5e1;
        color: #64748b;
    }
    .hover-glow-btn:hover {
        border-color: #10B981;
        color: #ffffff;
        background: #10B981;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
    }
    
    /* pulsing border for officer avatar */
    .avatar-wrapper {
        transition: border-color 0.3s;
    }
    .branch-glass-card:hover .avatar-wrapper {
        border-color: rgba(16, 185, 129, 0.2) !important;
    }
    
    /* Filter transitions */
    .branch-card-item {
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    .branch-card-item.filtered-out {
        opacity: 0;
        transform: scale(0.96) translateY(5px);
        pointer-events: none;
    }

    /* Tabs Scroll Wrapper */
    .tabs-scroll-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 5px;
    }
    /* Hide scrollbar for Chrome, Safari and Opera */
    .tabs-scroll-wrapper::-webkit-scrollbar {
        display: none;
    }
    /* Hide scrollbar for IE, Edge and Firefox */
    .tabs-scroll-wrapper {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
    
    /* Search Bar Focus Glow */
    .search-container-premium {
        border: 1px solid #e2e8f0 !important;
        transition: all 0.3s ease;
    }
    .search-container-premium:focus-within {
        border-color: #10B981 !important;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.15) !important;
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'কার্যালয় ও শাখা পরিচিতি',
        'subtitle' => 'সারাদেশে এবং প্রবাসে আমাদের সকল বিভাগীয়, জেলা ও উপজেলা কার্যালয়ের অফিশিয়াল ঠিকানা এবং দায়িত্বেরত কর্মকর্তাদের সাথে যোগাযোগের মাধ্যম।',
        'badge_text' => 'আমাদের সাংগঠনিক নেটওয়ার্ক',
        'badge_icon' => 'fas fa-network-wired'
    ])

    <!-- Main Content Area -->
    <section class="py-5 grid-pattern-mask" style="background-color: #f8fafc; min-height: 70vh; position: relative;">
        <div class="container">
            
            <!-- Smart Interactive Filter & Search Bar (Premium Float Card) -->
            <div class="card border-0 shadow-lg rounded-4 p-4 mb-5 bg-white bg-opacity-95 backdrop-blur-md" 
                 style="margin-top: -15px; z-index: 20; position: relative; border: 1px solid rgba(255,255,255,0.8) !important; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.04) !important;">
                
                <!-- Search Input Row (Centered & Modern) -->
                <div class="d-flex justify-content-center mb-4">
                    <div class="input-group rounded-pill border shadow-sm bg-white overflow-hidden p-1 search-container-premium" style="max-width: 550px; width: 100%;">
                        <span class="input-group-text bg-white border-0 ps-3 pe-2"><i class="fas fa-search text-success" style="font-size: 16px;"></i></span>
                        <input type="text" id="branch-search" class="form-control border-0 py-2 ps-1 pe-3" 
                               placeholder="কার্যালয়, জেলা বা কর্মকর্তা খুঁজুন..." autocomplete="off" style="font-size: 0.95rem; box-shadow: none; font-family: 'Baloo Da 2', sans-serif;">
                    </div>
                </div>

                <!-- Tabs Row (Centered & Horizontally Scrollable on Mobile) -->
                <div class="tabs-scroll-wrapper">
                    <div class="d-flex justify-content-start justify-content-md-center gap-2" id="filter-controls">
                        <button class="btn btn-filter active rounded-pill px-4 py-2 fw-bold" data-filter="all">
                            <i class="fas fa-border-all me-2"></i> সকল কার্যালয়
                        </button>
                        <button class="btn btn-filter rounded-pill px-4 py-2 fw-bold" data-filter="central">
                            <i class="fas fa-hotel me-2"></i> কেন্দ্রীয় কার্যালয়
                        </button>
                        <button class="btn btn-filter rounded-pill px-4 py-2 fw-bold" data-filter="division">
                            <i class="fas fa-sitemap me-2"></i> বিভাগীয়
                        </button>
                        <button class="btn btn-filter rounded-pill px-4 py-2 fw-bold" data-filter="district">
                            <i class="fas fa-map-marked-alt me-2"></i> জেলা কার্যালয়
                        </button>
                        <button class="btn btn-filter rounded-pill px-4 py-2 fw-bold" data-filter="upazila">
                            <i class="fas fa-street-view me-2"></i> উপজেলা কার্যালয়
                        </button>
                        <button class="btn btn-filter rounded-pill px-4 py-2 fw-bold" data-filter="international">
                            <i class="fas fa-globe-americas me-2"></i> আন্তর্জাতিক
                        </button>
                    </div>
                </div>
            </div>

            <!-- Branches Grid -->
            <div class="row g-4" id="branches-grid">
                @forelse($branches as $branch)
                    <div class="col-lg-4 col-md-6 mb-4 branch-card-item" 
                         data-type="{{ $branch->type }}"
                         data-name="{{ strtolower($branch->name) }} {{ strtolower($branch->address) }} @foreach($branch->officials as $off){{ strtolower($off->name) }} {{ strtolower($off->designation) }} @endforeach">
                        
                        <div class="card h-100 border-0 rounded-4 bg-white branch-glass-card transition d-flex flex-column justify-content-between">
                            
                            <div>
                                <!-- Image cover with glowing categories badge -->
                                <div class="position-relative overflow-hidden w-100 rounded-top-4" style="height: 190px;">
                                    <img src="{{ $branch->image_url }}" alt="{{ $branch->name }}" class="w-100 h-100 zoom-element" style="object-fit: cover;">
                                    
                                    <!-- Beautiful Accent Badge -->
                                    <span class="position-absolute badge rounded-pill px-3 py-2 fw-bold text-white shadow-sm @if($branch->type === 'central') bg-danger-gradient @elseif($branch->type === 'international') bg-purple-gradient @else bg-success-gradient @endif" 
                                          style="font-size: 10px; left: 15px; top: 15px; z-index: 10; letter-spacing: 0.3px; font-family: 'Baloo Da 2', sans-serif;">
                                        {{ $branch->type_label }}
                                    </span>
                                    
                                    <div class="image-gradient-shade position-absolute w-100 h-100 top-0 start-0"></div>
                                </div>

                                <!-- Card Contents -->
                                <div class="p-4">
                                    <h5 class="fw-bold text-dark-green mb-3" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.25rem; line-height: 1.45;">
                                        {{ $branch->name }}
                                    </h5>

                                    <!-- Address Details with interactive Copy Option -->
                                    <div class="d-flex align-items-start gap-3 bg-light p-3 rounded-3 border border-light-grey mb-3 position-relative address-box">
                                        <div class="rounded-circle bg-white d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 32px; height: 32px;">
                                            <i class="fas fa-map-marker-alt text-danger" style="font-size: 13px;"></i>
                                        </div>
                                        <div class="flex-grow-1 pe-4">
                                            <span class="text-secondary small fw-medium address-text" style="font-family: 'Baloo Da 2', sans-serif; line-height: 1.55; display: block; font-size: 0.88rem;">
                                                {{ $branch->address }}
                                            </span>
                                        </div>
                                        <!-- Copy address button -->
                                        <button class="btn btn-xs btn-link text-muted position-absolute copy-address-btn" style="right: 8px; top: 8px;" title="ঠিকানা কপি করুন" data-address="{{ $branch->address }}">
                                            <i class="far fa-copy" style="font-size: 13px;"></i>
                                        </button>
                                    </div>

                                    <!-- Officials Capsules List -->
                                    @if($branch->officials->count() > 0)
                                        <div class="border-top border-light pt-3 mt-3">
                                            <h6 class="fw-bold text-muted mb-3" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.85rem; letter-spacing: 0.3px;">
                                                <i class="fas fa-user-shield me-2 text-success"></i> দায়িত্বপ্রাপ্ত কর্মকর্তাবৃন্দ:
                                            </h6>
                                            <div class="d-flex flex-column gap-3">
                                                @foreach($branch->officials as $official)
                                                    <div class="d-flex align-items-center gap-3 p-2.5 rounded-3 border border-light bg-hover-light transition">
                                                        <!-- Profile Image with pulsing green ring -->
                                                        <div class="rounded-circle overflow-hidden shadow-sm border border-2 border-white flex-shrink-0 avatar-wrapper" 
                                                             style="width: 48px; height: 48px; background-color: #f1f5f9;">
                                                            <img src="{{ $official->image_url }}" alt="{{ $official->name }}" class="w-100 h-100 object-cover">
                                                        </div>
                                                        <!-- Profile info details -->
                                                        <div class="flex-grow-1">
                                                            <span class="fw-bold text-dark d-block" style="font-family: 'Baloo Da 2', sans-serif; font-size: 13.5px; line-height: 1.3;">{{ $official->name }}</span>
                                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 d-inline-block px-2 py-1 mt-1" style="font-size: 9px; font-family: 'Baloo Da 2', sans-serif;">{{ $official->designation }}</span>
                                                            
                                                            <div class="d-flex gap-2.5 mt-2 align-items-center">
                                                                @if($official->phone)
                                                                    <a href="tel:{{ $official->phone }}" class="text-success small-text-link" title="কল করুন">
                                                                        <i class="fas fa-phone-alt me-1"></i>{{ $official->phone }}
                                                                    </a>
                                                                @endif
                                                                @if($official->email)
                                                                    <a href="mailto:{{ $official->email }}" class="text-primary small-text-link" title="ইমেইল">
                                                                        <i class="fas fa-envelope me-1"></i>ইমেল
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Footer: Contacts & Action buttons -->
                            <div class="card-footer bg-light border-0 p-3.5 d-flex align-items-center justify-content-between rounded-bottom-4">
                                <div class="d-flex gap-2">
                                    @if($branch->phone)
                                        <a href="tel:{{ $branch->phone }}" class="btn btn-outline-success btn-xs rounded-circle d-flex align-items-center justify-content-center hover-glow-btn" style="width: 36px; height: 36px;" title="কার্যালয়ে ফোন করুন: {{ $branch->phone }}">
                                            <i class="fas fa-phone-alt" style="font-size: 12px;"></i>
                                        </a>
                                    @endif
                                    @if($branch->email)
                                        <a href="mailto:{{ $branch->email }}" class="btn btn-outline-success btn-xs rounded-circle d-flex align-items-center justify-content-center hover-glow-btn" style="width: 36px; height: 36px;" title="কার্যালয়ে ইমেইল: {{ $branch->email }}">
                                            <i class="fas fa-envelope" style="font-size: 12px;"></i>
                                        </a>
                                    @endif
                                </div>

                                @if($branch->google_map_url)
                                    <button class="btn btn-sm btn-success rounded-pill px-4 py-2 fw-bold show-map-btn shadow-sm transition-all" 
                                            data-name="{{ $branch->name }}" 
                                            data-map="{{ $branch->google_map_url }}">
                                        <i class="fas fa-map-marked-alt me-1"></i> ম্যাপ লোকেশন
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted bg-white rounded shadow-sm border border-light">
                        <i class="fas fa-map-marker-alt fa-3x mb-3 text-secondary"></i>
                        <p class="mb-0" style="font-family: 'Baloo Da 2', sans-serif;">কোনো কার্যালয় বা শাখা পাওয়া যায়নি।</p>
                    </div>
                @endforelse
            </div>
            
        </div>
    </section>

    <!-- Bootstrap Google Map Modal with Premium Backdrop Blur -->
    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true" style="backdrop-filter: blur(10px); background: rgba(0,0,0,0.3);">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden" style="background: #ffffff;">
                <div class="modal-header bg-success-gradient text-white p-3.5 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold" id="mapModalLabel" style="font-family: 'Baloo Da 2', sans-serif;"><i class="fas fa-map-marked-alt me-2"></i> কার্যালয়ের অবস্থান</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 bg-dark">
                    <div class="ratio ratio-16x9">
                        <iframe id="map-iframe" src="" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    // 1. Grid Filtering & Live Searches
    var activeFilter = 'all';

    function filterBranches() {
        var searchVal = $('#branch-search').val().toLowerCase().trim();
        
        $('.branch-card-item').each(function() {
            var card = $(this);
            var type = card.data('type');
            var name = card.data('name');

            var typeMatch = (activeFilter === 'all' || type === activeFilter);
            var searchMatch = (!searchVal || name.indexOf(searchVal) > -1);

            if (typeMatch && searchMatch) {
                card.removeClass('filtered-out').show();
            } else {
                card.addClass('filtered-out').hide();
            }
        });
    }

    // Filter pill click
    $('#filter-controls button').on('click', function() {
        $('#filter-controls button').removeClass('active');
        $(this).addClass('active');
        
        activeFilter = $(this).data('filter');
        filterBranches();
    });

    // Real-time search keyup
    $('#branch-search').on('keyup', function() {
        filterBranches();
    });

    // 2. Google Map Modal dynamic loading
    $('.show-map-btn').on('click', function() {
        var branchName = $(this).data('name');
        var mapUrl = $(this).data('map');

        if (mapUrl.includes('iframe') && mapUrl.includes('src=')) {
            var match = mapUrl.match(/src="([^"]+)"/);
            if (match && match[1]) {
                mapUrl = match[1];
            }
        }

        $('#mapModalLabel').html('<i class="fas fa-map-marked-alt me-2"></i> ' + branchName + ' - অবস্থান');
        $('#map-iframe').attr('src', mapUrl);
        $('#mapModal').modal('show');
    });

    // Clean map src when modal is closed to stop loading
    $('#mapModal').on('hidden.bs.modal', function () {
        $('#map-iframe').attr('src', '');
    });

    // 3. Click to copy address logic
    $('.copy-address-btn').on('click', function(e) {
        e.preventDefault();
        var address = $(this).data('address');
        
        // Use clipboard API
        navigator.clipboard.writeText(address).then(function() {
            toastr.success('ঠিকানাটি ক্লিপবোর্ডে কপি করা হয়েছে!');
        }).catch(function() {
            // Fallback
            var tempInput = $('<input>');
            $('body').append(tempInput);
            tempInput.val(address).select();
            document.execCommand('copy');
            tempInput.remove();
            toastr.success('ঠিকানাটি ক্লিপবোর্ডে কপি করা হয়েছে!');
        });
    });
});
</script>
@endpush
