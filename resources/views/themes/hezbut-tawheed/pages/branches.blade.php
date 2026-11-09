@extends('theme::layouts.app')

@section('title', 'কার্যালয় ও শাখা সমূহ - হেযবুত তওহীদ')
@section('meta_description', 'হেযবুত তওহীদ আন্দোলনের কেন্দ্রীয় কার্যালয়, বিভাগীয়, জেলা, উপজেলা এবং আন্তর্জাতিক শাখাসমূহের ঠিকানা ও যোগাযোগের নম্বর')

@push('styles')
<style>
    .grid-pattern-mask {
        background-image: radial-gradient(rgba(0, 106, 78, 0.05) 1.5px, transparent 0);
        background-size: 24px 24px;
    }
    
    .branch-glass-card {
        border: 1px solid #e2e8f0 !important;
        border-radius: 1.15rem !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03) !important;
        transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
        background: #ffffff;
    }
    .branch-glass-card:hover {
        transform: translateY(-5px);
        border-color: rgba(0, 106, 78, 0.3) !important;
        box-shadow: 0 16px 36px rgba(0, 106, 78, 0.08) !important;
    }
    .branch-top-bar {
        height: 5px;
        width: 100%;
    }
    
    /* Gradients */
    .bg-danger-gradient {
        background: linear-gradient(135deg, #ef4444, #b91c1c) !important;
    }
    .bg-purple-gradient {
        background: linear-gradient(135deg, #8b5cf6, #5b21b6) !important;
    }
    .bg-success-gradient {
        background: linear-gradient(135deg, #006A4E, #059669) !important;
    }

    /* Filter Pills */
    .tabs-scroll-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .tabs-scroll-wrapper::-webkit-scrollbar {
        display: none;
    }
    .btn-filter {
        border: 1px solid #e2e8f0;
        color: #475569;
        background-color: #ffffff;
        transition: all 0.25s ease;
        font-size: 0.88rem;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        font-family: 'Baloo Da 2', sans-serif;
        white-space: nowrap;
    }
    .btn-filter:hover {
        border-color: #006A4E;
        color: #006A4E;
        transform: translateY(-1px);
    }
    .btn-filter.active {
        background: linear-gradient(135deg, #006A4E, #059669) !important;
        color: #ffffff !important;
        border-color: transparent !important;
        box-shadow: 0 6px 14px rgba(0, 106, 78, 0.25) !important;
    }

    /* Official cards & badges */
    .official-item-card {
        background: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 0.85rem;
        transition: all 0.2s ease;
    }
    .official-item-card:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    }
    .avatar-wrapper {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .official-item-card:hover .avatar-wrapper {
        transform: scale(1.05);
        border-color: #006A4E !important;
    }
    
    .badge-amir {
        background: rgba(0, 106, 78, 0.1) !important;
        color: #006A4E !important;
        border: 1px solid rgba(0, 106, 78, 0.25) !important;
        font-weight: 700 !important;
    }
    .badge-assistant {
        background: rgba(14, 116, 144, 0.1) !important;
        color: #0e7490 !important;
        border: 1px solid rgba(14, 116, 144, 0.25) !important;
        font-weight: 700 !important;
    }
    .badge-general {
        background: rgba(100, 116, 139, 0.1) !important;
        color: #475569 !important;
        border: 1px solid rgba(100, 116, 139, 0.25) !important;
        font-weight: 600 !important;
    }

    /* Direct Call Button */
    .btn-call {
        width: 38px;
        height: 38px;
        background: linear-gradient(135deg, #006A4E, #059669);
        color: #ffffff !important;
        border: none;
        font-size: 14px;
        transition: all 0.25s ease;
    }
    .btn-call:hover {
        background: linear-gradient(135deg, #059669, #10b981);
        transform: scale(1.1);
        box-shadow: 0 6px 14px rgba(0, 106, 78, 0.35);
        color: #ffffff !important;
    }
    
    /* Phone Link */
    .phone-link {
        color: #334155;
        transition: color 0.2s;
    }
    .phone-link:hover {
        color: #006A4E !important;
    }
    .phone-link:hover .phone-number {
        color: #006A4E !important;
        text-decoration: underline;
    }

    /* Address box & copy */
    .border-light-grey {
        border: 1px solid #eef2f6 !important;
    }
    .copy-address-btn {
        opacity: 0.6;
        transition: opacity 0.2s ease, color 0.2s;
    }
    .address-box:hover .copy-address-btn {
        opacity: 1;
    }
    .copy-address-btn:hover {
        color: #006A4E !important;
    }

    /* Animation and filter handling */
    .branch-card-item {
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    .branch-card-item.filtered-out {
        opacity: 0;
        transform: scale(0.96) translateY(5px);
        pointer-events: none;
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
                               placeholder="কার্যালয়, জেলা, পদবি বা কর্মকর্তা খুঁজুন..." autocomplete="off" style="font-size: 0.95rem; box-shadow: none; font-family: 'Baloo Da 2', sans-serif;">
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
                         data-name="{{ strtolower($branch->name) }} {{ strtolower($branch->address) }} @foreach($branch->officials as $off){{ strtolower($off->name) }} {{ strtolower($off->formatted_designation) }} {{ strtolower($off->designation) }} {{ $off->phone }} @endforeach">
                        
                        <div class="card h-100 border-0 rounded-4 bg-white branch-glass-card shadow-sm transition d-flex flex-column justify-content-between overflow-hidden">
                            
                            <!-- Top Accent Gradient Line -->
                            <div class="branch-top-bar @if($branch->type === 'central') bg-danger-gradient @elseif($branch->type === 'international') bg-purple-gradient @else bg-success-gradient @endif"></div>

                            <div class="p-4 flex-grow-1">
                                <!-- Top Header Row: Branch Category Badge & Map Quick Button -->
                                <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                                    <span class="badge rounded-pill px-3 py-1.5 fw-bold text-white shadow-sm @if($branch->type === 'central') bg-danger-gradient @elseif($branch->type === 'international') bg-purple-gradient @else bg-success-gradient @endif" 
                                          style="font-size: 11px; letter-spacing: 0.3px; font-family: 'Baloo Da 2', sans-serif;">
                                        <i class="fas @if($branch->type === 'central') fa-hotel @elseif($branch->type === 'international') fa-globe @elseif($branch->type === 'division') fa-sitemap @else fa-map-marked-alt @endif me-1"></i>
                                        {{ $branch->type_label }}
                                    </span>

                                    @if($branch->google_map_url)
                                        <button class="btn btn-xs btn-outline-success rounded-pill px-2.5 py-1 fw-bold show-map-btn d-inline-flex align-items-center gap-1 shadow-xs" 
                                                style="font-size: 11.5px; font-family: 'Baloo Da 2', sans-serif;"
                                                data-name="{{ $branch->name }}" 
                                                data-map="{{ $branch->google_map_url }}"
                                                title="গুগল ম্যাপে কার্যালয়ের অবস্থান দেখুন">
                                            <i class="fas fa-map-marker-alt text-danger"></i>
                                            <span>ম্যাপ লোকেশন</span>
                                        </button>
                                    @endif
                                </div>

                                <!-- Branch Name -->
                                <h5 class="fw-bold text-dark-green mb-3" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.25rem; line-height: 1.4;">
                                    {{ $branch->name }}
                                </h5>

                                <!-- Address Box with Copy Option -->
                                <div class="d-flex align-items-start gap-2.5 bg-light p-3 rounded-3 border border-light-grey mb-3.5 position-relative address-box">
                                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 32px; height: 32px; margin-top: 1px;">
                                        <i class="fas fa-map-marker-alt text-danger" style="font-size: 13px;"></i>
                                    </div>
                                    <div class="flex-grow-1 pe-4">
                                        <span class="text-secondary fw-medium address-text" style="font-family: 'Baloo Da 2', sans-serif; line-height: 1.55; display: block; font-size: 0.88rem;">
                                            {{ $branch->address }}
                                        </span>
                                    </div>
                                    <!-- Copy address button -->
                                    <button class="btn btn-sm btn-link text-muted position-absolute copy-address-btn" style="right: 6px; top: 6px;" title="ঠিকানা কপি করুন" data-address="{{ $branch->address }}">
                                        <i class="far fa-copy" style="font-size: 13px;"></i>
                                    </button>
                                </div>

                                <!-- Officials Section -->
                                @if($branch->officials->count() > 0)
                                    <div class="border-top border-light pt-3 mt-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2.5">
                                            <h6 class="fw-bold text-muted mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.85rem; letter-spacing: 0.3px;">
                                                <i class="fas fa-user-shield me-1.5 text-success"></i> দায়িত্বপ্রাপ্ত ব্যক্তিবৃন্দ:
                                            </h6>
                                            <span class="badge bg-light text-muted border px-2 py-0.5 rounded-pill" style="font-size: 10px; font-family: 'Baloo Da 2', sans-serif;">
                                                {{ $branch->officials->count() }} জন
                                            </span>
                                        </div>

                                        <div class="d-flex flex-column gap-2.5">
                                            @foreach($branch->officials as $official)
                                                @php
                                                    $desig = trim($official->designation);
                                                    if ($desig === 'দায়িত্বশীল' || $desig === 'দায়িত্বশীল') {
                                                        $desigLabel = 'জেলা আমির';
                                                        $badgeClass = 'badge-amir';
                                                    } elseif ($desig === 'সহকারী দায়িত্বশীল' || $desig === 'সহকারী দায়িত্বশীল') {
                                                        $desigLabel = 'সহকারী আমির';
                                                        $badgeClass = 'badge-assistant';
                                                    } else {
                                                        $desigLabel = str_replace(['সহকারী দায়িত্বশীল', 'সহকারী দায়িত্বশীল'], 'সহকারী আমির', $desig);
                                                        $desigLabel = str_replace(['দায়িত্বশীল', 'দায়িত্বশীল'], 'জেলা আমির', $desigLabel);
                                                        $badgeClass = 'badge-general';
                                                    }
                                                @endphp
                                                <div class="p-2.5 rounded-3 border border-light-grey official-item-card">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <!-- Official Avatar (with fallback) -->
                                                        <div class="rounded-circle overflow-hidden border border-2 border-white flex-shrink-0 avatar-wrapper" 
                                                             style="width: 48px; height: 48px; background-color: #e2e8f0;">
                                                            <img src="{{ $official->image_url }}" 
                                                                 alt="{{ $official->name }}" 
                                                                 onerror="this.onerror=null;this.src='{{ asset('uploads/avatars/default.svg') }}';" 
                                                                 class="w-100 h-100" 
                                                                 style="object-fit: cover;">
                                                        </div>

                                                        <!-- Official Info Details -->
                                                        <div class="flex-grow-1 min-w-0">
                                                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                                                <span class="fw-bold text-dark official-name" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.95rem; line-height: 1.3;">
                                                                    {{ $official->name }}
                                                                </span>
                                                                <span class="badge {{ $badgeClass }} px-2 py-0.5 rounded-pill" style="font-size: 9.5px; font-family: 'Baloo Da 2', sans-serif;">
                                                                    {{ $desigLabel }}
                                                                </span>
                                                            </div>

                                                            <!-- Phone & Contact Actions -->
                                                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                                                @if($official->phone)
                                                                    <a href="tel:{{ $official->phone }}" class="d-inline-flex align-items-center text-decoration-none phone-link" title="সরাসরি কল করুন">
                                                                        <i class="fas fa-phone-alt text-success me-1.5" style="font-size: 11px;"></i>
                                                                        <span class="phone-number fw-semibold" style="font-size: 0.85rem; font-family: 'Baloo Da 2', sans-serif;">{{ $official->phone }}</span>
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted small" style="font-size: 0.78rem;">ফোন নম্বর নেই</span>
                                                                @endif

                                                                @if($official->email)
                                                                    <a href="mailto:{{ $official->email }}" class="d-inline-flex align-items-center text-decoration-none text-muted" title="{{ $official->email }}">
                                                                        <i class="fas fa-envelope text-primary me-1" style="font-size: 11px;"></i>
                                                                        <span style="font-size: 0.8rem; font-family: 'Baloo Da 2', sans-serif;">ইমেইল</span>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <!-- Direct Call Button -->
                                                        @if($official->phone)
                                                            <div class="flex-shrink-0">
                                                                <a href="tel:{{ $official->phone }}" 
                                                                   class="btn btn-call rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                                                   title="{{ $official->name }}-কে সরাসরি কল করুন">
                                                                    <i class="fas fa-phone-alt"></i>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Branch General Hotline & Actions Footer (if available) -->
                            @if($branch->phone || $branch->email)
                                <div class="card-footer bg-light bg-opacity-75 border-top border-light px-4 py-2.5 d-flex align-items-center justify-content-between rounded-bottom-4">
                                    <span class="text-muted small fw-medium" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.82rem;">
                                        <i class="fas fa-phone-volume text-success me-1"></i> কার্যালয়ের হটলাইন:
                                    </span>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($branch->phone)
                                            <a href="tel:{{ $branch->phone }}" class="btn btn-xs btn-outline-success rounded-pill px-2.5 py-1 d-inline-flex align-items-center gap-1 fw-bold" title="অফিসে কল করুন: {{ $branch->phone }}">
                                                <i class="fas fa-phone-alt" style="font-size: 10px;"></i>
                                                <span style="font-size: 0.8rem; font-family: 'Baloo Da 2', sans-serif;">{{ $branch->phone }}</span>
                                            </a>
                                        @endif
                                        @if($branch->email)
                                            <a href="mailto:{{ $branch->email }}" class="btn btn-xs btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" title="অফিস ইমেইল: {{ $branch->email }}">
                                                <i class="fas fa-envelope" style="font-size: 10px;"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
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
        
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(address).then(function() {
                if (typeof toastr !== 'undefined') {
                    toastr.success('ঠিকানাটি ক্লিপবোর্ডে কপি করা হয়েছে!');
                } else {
                    alert('ঠিকানাটি ক্লিপবোর্ডে কপি করা হয়েছে!');
                }
            }).catch(function() {
                fallbackCopyText(address);
            });
        } else {
            fallbackCopyText(address);
        }
    });

    function fallbackCopyText(text) {
        var tempInput = $('<input>');
        $('body').append(tempInput);
        tempInput.val(text).select();
        document.execCommand('copy');
        tempInput.remove();
        if (typeof toastr !== 'undefined') {
            toastr.success('ঠিকানাটি ক্লিপবোর্ডে কপি করা হয়েছে!');
        } else {
            alert('ঠিকানাটি ক্লিপবোর্ডে কপি করা হয়েছে!');
        }
    }
});
</script>
@endpush
