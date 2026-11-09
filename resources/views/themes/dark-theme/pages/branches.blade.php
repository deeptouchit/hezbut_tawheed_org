@extends('theme::layouts.app')

@section('title', 'কার্যালয় ও শাখা সমূহ - হেযবুত তওহীদ')
@section('meta_description', 'হেযবুত তওহীদ আন্দোলনের কেন্দ্রীয় কার্যালয়, বিভাগীয়, জেলা, উপজেলা এবং আন্তর্জাতিক শাখাসমূহের ঠিকানা ও যোগাযোগের নম্বর')

@push('styles')
<style>
    .grid-pattern-mask {
        background-image: radial-gradient(rgba(0, 106, 78, 0.05) 1.5px, transparent 0);
        background-size: 24px 24px;
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

    /* Modern Table Container */
    .directory-table-card {
        border: 1px solid #e2e8f0 !important;
        border-radius: 1rem !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        background: #ffffff;
        overflow: hidden;
    }

    .directory-table {
        margin-bottom: 0 !important;
        font-family: 'Baloo Da 2', sans-serif;
    }

    .directory-table thead th {
        background: #006A4E !important;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.96rem;
        letter-spacing: 0.3px;
        padding: 1rem 1.15rem;
        border: none !important;
        vertical-align: middle;
    }

    .directory-table tbody tr {
        transition: background-color 0.2s ease;
        border-bottom: 1px solid #eef2f6;
    }

    .directory-table tbody tr:hover {
        background-color: #f0fdf4 !important;
    }

    .directory-table td {
        padding: 1rem 1.15rem;
        vertical-align: middle;
        font-size: 0.95rem;
        border-top: 1px solid #f1f5f9;
    }

    /* Serial number pill */
    .serial-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #006A4E;
        font-weight: 700;
        font-size: 0.92rem;
        border: 1px solid #e2e8f0;
    }

    .directory-table tbody tr:hover .serial-badge {
        background: #006A4E;
        color: #ffffff;
        border-color: #006A4E;
    }

    /* 2 Rows inside 'দায়িত্বপ্রাপ্ত ব্যক্তিবৃন্দ' cell */
    .official-row-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.65rem;
        padding: 0.5rem 0.75rem;
        transition: all 0.2s ease;
    }

    .directory-table tbody tr:hover .official-row-box {
        background: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    .avatar-frame {
        width: 44px;
        height: 44px;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
        background-color: #e2e8f0;
    }

    .official-name-text {
        font-weight: 700;
        color: #0f172a;
        font-size: 0.98rem;
        line-height: 1.25;
    }

    /* Designation Badges */
    .badge-amir-pill {
        background: rgba(0, 106, 78, 0.12) !important;
        color: #006A4E !important;
        border: 1px solid rgba(0, 106, 78, 0.3) !important;
        font-weight: 700 !important;
        font-size: 10px !important;
        padding: 0.2rem 0.6rem !important;
        border-radius: 50rem !important;
    }

    .badge-assistant-pill {
        background: rgba(14, 116, 144, 0.12) !important;
        color: #0e7490 !important;
        border: 1px solid rgba(14, 116, 144, 0.3) !important;
        font-weight: 700 !important;
        font-size: 10px !important;
        padding: 0.2rem 0.6rem !important;
        border-radius: 50rem !important;
    }

    /* Direct Call Action Button */
    .btn-call-mini {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #006A4E, #059669);
        color: #ffffff !important;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13.5px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(0, 106, 78, 0.25);
    }

    .btn-call-mini:hover {
        background: linear-gradient(135deg, #059669, #10b981);
        transform: scale(1.1);
        box-shadow: 0 4px 10px rgba(0, 106, 78, 0.35);
        color: #ffffff !important;
    }

    .phone-link {
        color: #334155;
        font-size: 0.88rem;
        font-weight: 600;
        transition: color 0.2s;
    }

    .phone-link:hover {
        color: #006A4E !important;
    }

    .phone-link:hover .phone-text {
        text-decoration: underline;
    }

    /* Address Box styling */
    .address-text-styled {
        color: #475569;
        font-size: 0.9rem;
        line-height: 1.55;
        font-weight: 500;
    }

    .copy-address-btn {
        transition: all 0.2s ease;
        font-size: 11.5px !important;
        font-family: 'Baloo Da 2', sans-serif !important;
    }

    .copy-address-btn:hover {
        background-color: #006A4E !important;
        border-color: #006A4E !important;
        color: #ffffff !important;
    }

    /* Row filter handling */
    .branch-table-row.filtered-out {
        display: none !important;
    }

    /* Mobile Responsive Card adaptation */
    @media (max-width: 991.98px) {
        .directory-table thead {
            display: none;
        }

        .directory-table, 
        .directory-table tbody, 
        .directory-table tr, 
        .directory-table td {
            display: block;
            width: 100%;
        }

        .directory-table tbody tr {
            margin-bottom: 1.25rem;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.25rem;
            background: #ffffff;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        }

        .directory-table td {
            padding: 0.65rem 0;
            border-top: none;
            border-bottom: 1px dashed #e2e8f0;
        }

        .directory-table td:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .mobile-label {
            display: inline-block;
            font-size: 0.8rem;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 0.35rem;
        }
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
            <div class="card border-0 shadow-lg rounded-4 p-4 mb-4 bg-white bg-opacity-95 backdrop-blur-md" 
                 style="margin-top: -15px; z-index: 20; position: relative; border: 1px solid rgba(255,255,255,0.8) !important; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.04) !important;">
                
                <!-- Search Input Row (Centered & Modern) -->
                <div class="d-flex justify-content-center mb-4">
                    <div class="input-group rounded-pill border shadow-sm bg-white overflow-hidden p-1 search-container-premium" style="max-width: 550px; width: 100%;">
                        <span class="input-group-text bg-white border-0 ps-3 pe-2"><i class="fas fa-search text-success" style="font-size: 16px;"></i></span>
                        <input type="text" id="branch-search" class="form-control border-0 py-2 ps-1 pe-3" 
                               placeholder="কার্যালয়, জেলা, পদবি বা কর্মকর্তা খুঁজুন..." autocomplete="off" style="font-size: 0.95rem; box-shadow: none; font-family: 'Baloo Da 2', sans-serif;">
                        <button class="btn btn-link text-muted pe-3 d-none" id="clear-search-btn" type="button" title="মুছে ফেলুন">
                            <i class="fas fa-times-circle"></i>
                        </button>
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

            <!-- Status counter strip -->
            <div class="d-flex align-items-center justify-content-between mb-3 px-2">
                <span class="text-secondary small fw-medium" style="font-family: 'Baloo Da 2', sans-serif;">
                    <i class="fas fa-database text-success me-1"></i> তালিকাভুক্ত মোট কার্যালয়: <strong class="text-dark">{{ count($branches) }}</strong> টি
                </span>
                <span class="text-muted small" id="filtered-status-text" style="font-family: 'Baloo Da 2', sans-serif;">
                    সবগুলো প্রদর্শিত হচ্ছে
                </span>
            </div>

            @php
                $bnDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
                $enDigits = ['0','1','2','3','4','5','6','7','8','9'];
            @endphp

            <!-- Directory Data Table Card -->
            <div class="directory-table-card">
                <div class="table-responsive">
                    <table class="table directory-table" id="branches-table">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 70px;">#</th>
                                <th style="width: 220px;">জেলার নাম</th>
                                <th style="width: 280px;">ঠিকানা</th>
                                <th>দায়িত্বপ্রাপ্ত ব্যক্তিবৃন্দ</th>
                            </tr>
                        </thead>
                        <tbody id="branches-tbody">
                            @forelse($branches as $index => $branch)
                                @php
                                    $serialBn = str_replace($enDigits, $bnDigits, str_pad($index + 1, 2, '0', STR_PAD_LEFT));

                                    // Primary: জেলা আমির
                                    $amir = $branch->officials->first(function($off) {
                                        $d = trim($off->designation);
                                        return in_array($d, ['দায়িত্বশীল', 'দায়িত্বশীল', 'জেলা আমির', 'বিভাগীয় আমির', 'আমির']);
                                    }) ?? $branch->officials->first();

                                    // Secondary: সহকারী আমির (অন্যান্য দায়িত্বশীল)
                                    $assistant = $branch->officials->first(function($off) use ($amir) {
                                        return !$amir || $off->id !== $amir->id;
                                    });

                                    // More assistants if any
                                    $otherAssistants = $branch->officials->filter(function($off) use ($amir, $assistant) {
                                        $excludeIds = array_filter([$amir ? $amir->id : null, $assistant ? $assistant->id : null]);
                                        return !in_array($off->id, $excludeIds);
                                    });
                                @endphp
                                <tr class="branch-table-row" 
                                    data-type="{{ $branch->type }}"
                                    data-name="{{ strtolower($branch->name) }} {{ strtolower($branch->address) }} @foreach($branch->officials as $off){{ strtolower($off->name) }} {{ strtolower($off->formatted_designation) }} {{ strtolower($off->designation) }} {{ $off->phone }} @endforeach">
                                    
                                    <!-- 1. # -->
                                    <td class="text-center align-middle">
                                        <span class="d-md-none mobile-label">ক্রমিক:</span>
                                        <span class="serial-badge">{{ $serialBn }}</span>
                                    </td>

                                    <!-- 2. জেলার নাম -->
                                    <td class="align-middle">
                                        <span class="d-md-none mobile-label">জেলার নাম:</span>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1" style="font-size: 1.05rem; line-height: 1.35;">
                                                {{ $branch->name }}
                                            </h6>
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <span class="badge rounded-pill px-2.5 py-1 fw-bold text-white shadow-xs @if($branch->type === 'central') bg-danger-gradient @elseif($branch->type === 'international') bg-purple-gradient @else bg-success-gradient @endif" 
                                                      style="font-size: 10px; letter-spacing: 0.3px;">
                                                    <i class="fas @if($branch->type === 'central') fa-hotel @elseif($branch->type === 'international') fa-globe @elseif($branch->type === 'division') fa-sitemap @else fa-map-marked-alt @endif me-1"></i>
                                                    {{ $branch->type_label }}
                                                </span>

                                                @if($branch->phone)
                                                    <a href="tel:{{ $branch->phone }}" class="text-muted small text-decoration-none" title="অফিসের হটলাইনে কল করুন">
                                                        <i class="fas fa-phone-volume text-success me-1"></i> {{ $branch->phone }}
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- 3. ঠিকানা -->
                                    <td class="align-middle">
                                        <span class="d-md-none mobile-label">ঠিকানা:</span>
                                        <div>
                                            <div class="address-text-styled mb-2">
                                                <i class="fas fa-map-marker-alt text-danger me-1.5"></i>
                                                {{ $branch->address }}
                                            </div>
                                            <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                                <button class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1 copy-address-btn" 
                                                        data-address="{{ $branch->address }}"
                                                        title="ঠিকানা ক্লিপবোর্ডে কপি করুন">
                                                    <i class="far fa-copy me-1"></i> কপি
                                                </button>

                                                @if($branch->google_map_url)
                                                    <button class="btn btn-sm btn-success rounded-pill px-2.5 py-1 show-map-btn fw-bold" 
                                                            data-name="{{ $branch->name }}" 
                                                            data-map="{{ $branch->google_map_url }}"
                                                            title="গুগল ম্যাপে কার্যালয়ের অবস্থান দেখুন">
                                                        <i class="fas fa-map-marked-alt me-1"></i> ম্যাপ
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- 4. দায়িত্বপ্রাপ্ত ব্যক্তিবৃন্দ (রো ১: জেলা আমির, রো ২: সহকারী আমির) -->
                                    <td class="align-middle">
                                        <span class="d-md-none mobile-label">দায়িত্বপ্রাপ্ত ব্যক্তিবৃন্দ:</span>
                                        <div class="d-flex flex-column gap-2" style="min-width: 320px;">
                                            
                                            <!-- রো ১: জেলা আমির -->
                                            @if($amir)
                                                <div class="official-row-box d-flex align-items-center justify-content-between gap-2.5">
                                                    <div class="d-flex align-items-center gap-2.5 min-w-0">
                                                        <!-- ছবি -->
                                                        <div class="rounded-circle overflow-hidden flex-shrink-0 avatar-frame">
                                                            <img src="{{ $amir->image_url }}" 
                                                                 alt="{{ $amir->name }}" 
                                                                 onerror="this.onerror=null;this.src='{{ asset('uploads/avatars/default.svg') }}';" 
                                                                 class="w-100 h-100" 
                                                                 style="object-fit: cover;">
                                                        </div>
                                                        <!-- নাম, পদবী ও ফোন -->
                                                        <div class="min-w-0">
                                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                                <span class="official-name-text text-truncate">{{ $amir->name }}</span>
                                                                <span class="badge badge-amir-pill">জেলা আমির</span>
                                                            </div>
                                                            @if($amir->phone)
                                                                <div class="mt-1">
                                                                    <a href="tel:{{ $amir->phone }}" class="phone-link text-decoration-none" title="কল করতে ট্যাপ করুন">
                                                                        <i class="fas fa-phone-alt text-success me-1.5" style="font-size: 11px;"></i>
                                                                        <span class="phone-text">{{ $amir->phone }}</span>
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <span class="text-muted small">ফোন নম্বর নেই</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <!-- সরাসরি কল বোতাম -->
                                                    @if($amir->phone)
                                                        <a href="tel:{{ $amir->phone }}" class="btn-call-mini flex-shrink-0" title="{{ $amir->name }}-কে সরাসরি কল করুন">
                                                            <i class="fas fa-phone-alt"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="text-muted small ps-2">— জেলা আমির নির্ধারিত নেই —</div>
                                            @endif

                                            <!-- রো ২: সহকারী আমির -->
                                            @if($assistant)
                                                <div class="official-row-box d-flex align-items-center justify-content-between gap-2.5">
                                                    <div class="d-flex align-items-center gap-2.5 min-w-0">
                                                        <!-- ছবি -->
                                                        <div class="rounded-circle overflow-hidden flex-shrink-0 avatar-frame">
                                                            <img src="{{ $assistant->image_url }}" 
                                                                 alt="{{ $assistant->name }}" 
                                                                 onerror="this.onerror=null;this.src='{{ asset('uploads/avatars/default.svg') }}';" 
                                                                 class="w-100 h-100" 
                                                                 style="object-fit: cover;">
                                                        </div>
                                                        <!-- নাম, পদবী ও ফোন -->
                                                        <div class="min-w-0">
                                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                                <span class="official-name-text text-truncate">{{ $assistant->name }}</span>
                                                                <span class="badge badge-assistant-pill">সহকারী আমির</span>
                                                            </div>
                                                            @if($assistant->phone)
                                                                <div class="mt-1">
                                                                    <a href="tel:{{ $assistant->phone }}" class="phone-link text-decoration-none" title="কল করতে ট্যাপ করুন">
                                                                        <i class="fas fa-phone-alt text-success me-1.5" style="font-size: 11px;"></i>
                                                                        <span class="phone-text">{{ $assistant->phone }}</span>
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <span class="text-muted small">ফোন নম্বর নেই</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <!-- সরাসরি কল বোতাম -->
                                                    @if($assistant->phone)
                                                        <a href="tel:{{ $assistant->phone }}" class="btn-call-mini flex-shrink-0" title="{{ $assistant->name }}-কে সরাসরি কল করুন">
                                                            <i class="fas fa-phone-alt"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif

                                            <!-- অতিরিক্ত সহকারী বা কর্মকর্তা থাকলে -->
                                            @foreach($otherAssistants as $other)
                                                <div class="official-row-box d-flex align-items-center justify-content-between gap-2.5">
                                                    <div class="d-flex align-items-center gap-2.5 min-w-0">
                                                        <div class="rounded-circle overflow-hidden flex-shrink-0 avatar-frame">
                                                            <img src="{{ $other->image_url }}" 
                                                                 alt="{{ $other->name }}" 
                                                                 onerror="this.onerror=null;this.src='{{ asset('uploads/avatars/default.svg') }}';" 
                                                                 class="w-100 h-100" 
                                                                 style="object-fit: cover;">
                                                        </div>
                                                        <div class="min-w-0">
                                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                                <span class="official-name-text text-truncate">{{ $other->name }}</span>
                                                                <span class="badge badge-assistant-pill">{{ $other->formatted_designation }}</span>
                                                            </div>
                                                            @if($other->phone)
                                                                <div class="mt-1">
                                                                    <a href="tel:{{ $other->phone }}" class="phone-link text-decoration-none" title="কল করতে ট্যাপ করুন">
                                                                        <i class="fas fa-phone-alt text-success me-1.5" style="font-size: 11px;"></i>
                                                                        <span class="phone-text">{{ $other->phone }}</span>
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if($other->phone)
                                                        <a href="tel:{{ $other->phone }}" class="btn-call-mini flex-shrink-0" title="{{ $other->name }}-কে সরাসরি কল করুন">
                                                            <i class="fas fa-phone-alt"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            @endforeach

                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-map-marker-alt fa-3x mb-3 text-secondary"></i>
                                        <p class="mb-0">কোনো কার্যালয় বা শাখা পাওয়া যায়নি।</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- No Results message when search filters everything out -->
                <div id="no-search-results" class="text-center py-5 d-none">
                    <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                    <h6 class="fw-bold text-dark mb-1">কোনো তথ্য পাওয়া যায়নি</h6>
                    <p class="text-muted small mb-0">আপনার সার্চ করা কিওয়ার্ডের সাথে কোনো কার্যালয় বা কর্মকর্তার নাম মেলেনি।</p>
                </div>
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
    
    // 1. Table Filtering & Live Search
    var activeFilter = 'all';

    function filterBranches() {
        var searchVal = $('#branch-search').val().toLowerCase().trim();
        var visibleCount = 0;
        var totalCount = $('.branch-table-row').length;

        // Toggle clear search button
        if (searchVal.length > 0) {
            $('#clear-search-btn').removeClass('d-none');
        } else {
            $('#clear-search-btn').addClass('d-none');
        }
        
        $('.branch-table-row').each(function() {
            var row = $(this);
            var type = row.data('type');
            var name = row.data('name');

            var typeMatch = (activeFilter === 'all' || type === activeFilter);
            var searchMatch = (!searchVal || name.indexOf(searchVal) > -1);

            if (typeMatch && searchMatch) {
                row.removeClass('filtered-out');
                visibleCount++;
            } else {
                row.addClass('filtered-out');
            }
        });

        // Show/hide empty state
        if (visibleCount === 0) {
            $('#no-search-results').removeClass('d-none');
            $('#branches-table').addClass('d-none');
            $('#filtered-status-text').text('কোনো মিল পাওয়া যায়নি');
        } else {
            $('#no-search-results').addClass('d-none');
            $('#branches-table').removeClass('d-none');
            if (visibleCount < totalCount) {
                $('#filtered-status-text').text(visibleCount + ' টি প্রদর্শিত হচ্ছে');
            } else {
                $('#filtered-status-text').text('সবগুলো প্রদর্শিত হচ্ছে');
            }
        }
    }

    // Filter pill click
    $('#filter-controls button').on('click', function() {
        $('#filter-controls button').removeClass('active');
        $(this).addClass('active');
        
        activeFilter = $(this).data('filter');
        filterBranches();
    });

    // Real-time search keyup & input
    $('#branch-search').on('input keyup', function() {
        filterBranches();
    });

    // Clear search
    $('#clear-search-btn').on('click', function() {
        $('#branch-search').val('').focus();
        filterBranches();
    });

    // 2. Google Map Modal dynamic loading
    $(document).on('click', '.show-map-btn', function() {
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
    $(document).on('click', '.copy-address-btn', function(e) {
        e.preventDefault();
        var address = $(this).data('address');
        var btn = $(this);
        
        function showSuccess() {
            var originalHtml = btn.html();
            btn.html('<i class="fas fa-check text-success me-1"></i>কপি হয়েছে').addClass('btn-light');
            setTimeout(function() {
                btn.html(originalHtml).removeClass('btn-light');
            }, 2000);

            if (typeof toastr !== 'undefined') {
                toastr.success('ঠিকানাটি ক্লিপবোর্ডে কপি করা হয়েছে!');
            }
        }

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(address).then(function() {
                showSuccess();
            }).catch(function() {
                fallbackCopyText(address, showSuccess);
            });
        } else {
            fallbackCopyText(address, showSuccess);
        }
    });

    function fallbackCopyText(text, callback) {
        var tempInput = $('<input>');
        $('body').append(tempInput);
        tempInput.val(text).select();
        document.execCommand('copy');
        tempInput.remove();
        if (callback) callback();
    }
});
</script>
@endpush
