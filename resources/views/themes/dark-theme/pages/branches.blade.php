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
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.04) !important;
        background: #ffffff;
        overflow: hidden;
    }

    /* Custom Bordered Small Table */
    .directory-table {
        margin-bottom: 0 !important;
        border-collapse: collapse !important;
        border: 1px solid #cbd5e1 !important;
        font-family: 'Baloo Da 2', sans-serif;
    }

    .directory-table th, 
    .directory-table td {
        border: 1px solid #cbd5e1 !important;
        padding: 0.5rem 0.7rem !important;
        vertical-align: middle;
        font-size: 0.92rem;
    }

    /* Main Header Row */
    .directory-table thead tr.header-row-main th {
        background: #006A4E !important;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.96rem;
        letter-spacing: 0.3px;
        border: 1px solid #005a42 !important;
        text-align: center;
    }

    /* Sub Header Row */
    .directory-table thead tr.header-row-sub th {
        background: #047857 !important;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.88rem;
        border: 1px solid #006A4E !important;
        text-align: center;
    }

    /* Alternating branch backgrounds */
    .branch-even {
        background-color: #ffffff !important;
    }
    .branch-odd {
        background-color: #f8fafc !important;
    }

    .directory-table tbody tr:hover {
        background-color: #f0fdf4 !important;
    }

    /* Clear Separator line between different branches */
    .branch-last-row td {
        border-bottom: 2px solid #94a3b8 !important;
    }

    /* Serial number */
    .serial-badge {
        font-weight: 800;
        font-size: 0.98rem;
        color: #006A4E;
        display: inline-block;
    }

    /* Avatar Thumbnail */
    .avatar-frame {
        width: 36px;
        height: 36px;
        border: 1.5px solid #cbd5e1;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        background-color: #e2e8f0;
    }

    .official-name {
        font-weight: 700;
        color: #0f172a;
        font-size: 0.95rem;
        line-height: 1.25;
    }

    /* Designation Badges */
    .badge-amir-pill {
        background: rgba(0, 106, 78, 0.12) !important;
        color: #006A4E !important;
        border: 1px solid rgba(0, 106, 78, 0.3) !important;
        font-weight: 700 !important;
        font-size: 10px !important;
        padding: 0.18rem 0.55rem !important;
        border-radius: 50rem !important;
        white-space: nowrap;
    }

    .badge-assistant-pill {
        background: rgba(14, 116, 144, 0.12) !important;
        color: #0e7490 !important;
        border: 1px solid rgba(14, 116, 144, 0.3) !important;
        font-weight: 700 !important;
        font-size: 10px !important;
        padding: 0.18rem 0.55rem !important;
        border-radius: 50rem !important;
        white-space: nowrap;
    }

    .badge-other-pill {
        background: rgba(100, 116, 139, 0.12) !important;
        color: #475569 !important;
        border: 1px solid rgba(100, 116, 139, 0.3) !important;
        font-weight: 600 !important;
        font-size: 10px !important;
        padding: 0.18rem 0.55rem !important;
        border-radius: 50rem !important;
        white-space: nowrap;
    }

    /* Direct Call Mini Button */
    .btn-call-mini {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, #006A4E, #059669);
        color: #ffffff !important;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 4px rgba(0, 106, 78, 0.25);
    }

    .btn-call-mini:hover {
        background: linear-gradient(135deg, #059669, #10b981);
        transform: scale(1.15);
        color: #ffffff !important;
    }

    .phone-link {
        color: #1e293b;
        font-size: 0.88rem;
        font-weight: 600;
        transition: color 0.2s;
        white-space: nowrap;
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
        font-size: 0.88rem;
        line-height: 1.45;
        font-weight: 500;
    }

    .copy-address-btn {
        transition: all 0.2s ease;
        font-size: 11px !important;
        font-family: 'Baloo Da 2', sans-serif !important;
    }

    .copy-address-btn:hover {
        background-color: #006A4E !important;
        border-color: #006A4E !important;
        color: #ffffff !important;
    }

    /* Custom Responsive Scrollbar */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #94a3b8;
        border-radius: 4px;
    }
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #006A4E;
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

            <!-- Mobile Table Scroll Hint -->
            <div class="d-lg-none alert alert-light py-2 px-3 mb-3 border rounded-3 text-muted small d-flex align-items-center justify-content-between">
                <span style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.85rem;">
                    <i class="fas fa-arrows-left-right text-success me-1.5"></i> সম্পূর্ণ টেবিল দেখতে বামে-ডানে স্ক্রল করুন
                </span>
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25" style="font-size: 10px;">স্ক্রলেবল</span>
            </div>

            <!-- Status counter strip -->
            <div class="d-flex align-items-center justify-content-between mb-2.5 px-1">
                <span class="text-secondary small fw-medium" style="font-family: 'Baloo Da 2', sans-serif;">
                    <i class="fas fa-building text-success me-1"></i> তালিকাভুক্ত মোট কার্যালয়: <strong class="text-dark">{{ count($branches) }}</strong> টি
                </span>
                <span class="text-muted small" id="filtered-status-text" style="font-family: 'Baloo Da 2', sans-serif;">
                    সবগুলো প্রদর্শিত হচ্ছে
                </span>
            </div>

            @php
                $bnDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
                $enDigits = ['0','1','2','3','4','5','6','7','8','9'];

                $formatDesig = function($desig, $isAmir = false) {
                    $d = trim((string)$desig);
                    if ($d === 'দায়িত্বশীল' || $d === 'দায়িত্বশীল') {
                        return 'জেলা আমির';
                    }
                    if ($d === 'সহকারী দায়িত্বশীল' || $d === 'সহকারী দায়িত্বশীল') {
                        return 'সহকারী আমির';
                    }
                    $d = str_replace(['সহকারী দায়িত্বশীল', 'সহকারী দায়িত্বশীল'], 'সহকারী আমির', $d);
                    $d = str_replace(['দায়িত্বশীল', 'দায়িত্বশীল'], 'জেলা আমির', $d);
                    return $d ?: ($isAmir ? 'জেলা আমির' : 'সহকারী আমির');
                };
            @endphp

            <!-- Directory Data Table Card -->
            <div class="directory-table-card">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered directory-table" id="branches-table">
                        <thead>
                            <!-- Header Row 1 -->
                            <tr class="header-row-main">
                                <th rowspan="2" class="text-center align-middle" style="width: 45px;">#</th>
                                <th rowspan="2" class="align-middle" style="min-width: 170px;">জেলার নাম</th>
                                <th rowspan="2" class="align-middle" style="min-width: 200px;">ঠিকানা</th>
                                <th colspan="4" class="text-center align-middle">দায়িত্বপ্রাপ্ত ব্যক্তিবৃন্দ</th>
                            </tr>
                            <!-- Header Row 2: Sub-headers under দায়িত্বপ্রাপ্ত ব্যক্তিবৃন্দ -->
                            <tr class="header-row-sub">
                                <th class="text-center align-middle" style="width: 55px;">ছবি</th>
                                <th class="align-middle" style="min-width: 160px;">নাম</th>
                                <th class="text-center align-middle" style="width: 125px;">পদবী</th>
                                <th class="align-middle" style="min-width: 170px;">মোবাইল নাম্বার</th>
                            </tr>
                        </thead>
                        <tbody id="branches-tbody">
                            @forelse($branches as $index => $branch)
                                @php
                                    $serialBn = str_replace($enDigits, $bnDigits, $index + 1);

                                    // Sort officials: Amir first, then others
                                    $officials = $branch->officials;
                                    $amir = $officials->first(function($off) {
                                        $d = trim($off->designation);
                                        return in_array($d, ['দায়িত্বশীল', 'দায়িত্বশীল', 'জেলা আমির', 'বিভাগীয় আমির', 'আমির']);
                                    }) ?? $officials->first();

                                    $otherOfficials = $officials->filter(function($off) use ($amir) {
                                        return !$amir || $off->id !== $amir->id;
                                    });

                                    $sortedOfficials = collect();
                                    if ($amir) {
                                        $sortedOfficials->push($amir);
                                    }
                                    foreach ($otherOfficials as $other) {
                                        $sortedOfficials->push($other);
                                    }

                                    $rowCount = max(1, $sortedOfficials->count());
                                    $firstOfficial = $sortedOfficials->first();
                                    $branchBgClass = ($index % 2 == 0) ? 'branch-even' : 'branch-odd';
                                @endphp

                                <!-- Row 1 of this branch -->
                                <tr class="branch-row {{ $branchBgClass }} @if($rowCount == 1) branch-last-row @endif" 
                                    data-branch-id="{{ $branch->id }}" 
                                    data-type="{{ $branch->type }}" 
                                    data-name="{{ strtolower($branch->name) }} {{ strtolower($branch->address) }} @foreach($branch->officials as $off){{ strtolower($off->name) }} {{ strtolower($off->formatted_designation) }} {{ strtolower($off->designation) }} {{ $off->phone }} @endforeach">
                                    
                                    <!-- 1. # -->
                                    <td rowspan="{{ $rowCount }}" class="text-center align-middle">
                                        <span class="serial-badge">{{ $serialBn }}</span>
                                    </td>

                                    <!-- 2. জেলার নাম -->
                                    <td rowspan="{{ $rowCount }}" class="align-middle">
                                        <div class="fw-bold text-dark" style="font-size: 0.98rem; line-height: 1.35;">
                                            {{ $branch->name }}
                                        </div>
                                        <div class="mt-1 d-flex align-items-center gap-1.5 flex-wrap">
                                            <span class="badge rounded-pill px-2 py-0.5 fw-bold text-white shadow-xs @if($branch->type === 'central') bg-danger-gradient @elseif($branch->type === 'international') bg-purple-gradient @else bg-success-gradient @endif" style="font-size: 9.5px;">
                                                {{ $branch->type_label }}
                                            </span>
                                            @if($branch->phone)
                                                <a href="tel:{{ $branch->phone }}" class="text-muted small text-decoration-none" title="অফিসের হটলাইন">
                                                    <i class="fas fa-phone-volume text-success" style="font-size: 10px;"></i> {{ $branch->phone }}
                                                </a>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- 3. ঠিকানা -->
                                    <td rowspan="{{ $rowCount }}" class="align-middle">
                                        <div class="address-text-styled mb-1.5">
                                            <i class="fas fa-map-marker-alt text-danger me-1" style="font-size: 11px;"></i>
                                            {{ $branch->address }}
                                        </div>
                                        <div class="d-flex align-items-center gap-1 flex-wrap">
                                            <button class="btn btn-xs btn-outline-secondary rounded px-2 py-0.5 copy-address-btn" 
                                                    data-address="{{ $branch->address }}" 
                                                    title="ঠিকানা কপি করুন">
                                                <i class="far fa-copy me-1"></i>কপি
                                            </button>
                                            @if($branch->google_map_url)
                                                <button class="btn btn-xs btn-success rounded px-2 py-0.5 show-map-btn fw-bold" 
                                                        data-name="{{ $branch->name }}" 
                                                        data-map="{{ $branch->google_map_url }}" 
                                                        title="ম্যাপ দেখুন">
                                                    <i class="fas fa-map-marked-alt me-1"></i>ম্যাপ
                                                </button>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- 4. ছবি (রো ১: জেলা আমির) -->
                                    @if($firstOfficial)
                                        <td class="text-center align-middle p-1" style="width: 55px;">
                                            <div class="avatar-frame rounded-circle overflow-hidden mx-auto">
                                                <img src="{{ $firstOfficial->image_url }}" 
                                                     alt="{{ $firstOfficial->name }}" 
                                                     onerror="this.onerror=null;this.src='{{ asset('uploads/avatars/default.svg') }}';" 
                                                     class="w-100 h-100" 
                                                     style="object-fit: cover;">
                                            </div>
                                        </td>
                                        <!-- 5. নাম -->
                                        <td class="align-middle">
                                            <span class="official-name d-block">{{ $firstOfficial->name }}</span>
                                        </td>
                                        <!-- 6. পদবী -->
                                        <td class="text-center align-middle" style="width: 125px;">
                                            @php
                                                $desig1 = $formatDesig($firstOfficial->designation, true);
                                            @endphp
                                            <span class="badge badge-amir-pill">{{ $desig1 }}</span>
                                        </td>
                                        <!-- 7. মোবাইল নাম্বার -->
                                        <td class="align-middle" style="min-width: 170px;">
                                            @if($firstOfficial->phone)
                                                <div class="d-flex align-items-center justify-content-between gap-1.5">
                                                    <a href="tel:{{ $firstOfficial->phone }}" class="phone-link text-decoration-none" title="কল করতে ট্যাপ করুন">
                                                        <i class="fas fa-phone-alt text-success me-1" style="font-size: 10px;"></i>
                                                        <span class="phone-text">{{ $firstOfficial->phone }}</span>
                                                    </a>
                                                    <a href="tel:{{ $firstOfficial->phone }}" class="btn-call-mini flex-shrink-0" title="{{ $firstOfficial->name }}-কে সরাসরি কল করুন">
                                                        <i class="fas fa-phone-alt"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                    @else
                                        <td colspan="4" class="text-center text-muted small py-2">— কোনো কর্মকর্তা তালিকাভুক্ত নেই —</td>
                                    @endif
                                </tr>

                                <!-- Subsequent rows for this branch (e.g. Row 2 for সহকারী আমির) -->
                                @foreach($sortedOfficials->slice(1) as $subIndex => $nextOfficial)
                                    @php
                                        $desigNext = $formatDesig($nextOfficial->designation, false);
                                        $badgeClass = ($desigNext === 'সহকারী আমির') ? 'badge-assistant-pill' : 'badge-other-pill';
                                        $isLastSubRow = ($subIndex == count($sortedOfficials) - 2);
                                    @endphp
                                    <tr class="branch-row {{ $branchBgClass }} @if($isLastSubRow) branch-last-row @endif" 
                                        data-branch-id="{{ $branch->id }}" 
                                        data-type="{{ $branch->type }}" 
                                        data-name="{{ strtolower($branch->name) }} {{ strtolower($branch->address) }} @foreach($branch->officials as $off){{ strtolower($off->name) }} {{ strtolower($off->formatted_designation) }} {{ strtolower($off->designation) }} {{ $off->phone }} @endforeach">
                                        
                                        <!-- ছবি -->
                                        <td class="text-center align-middle p-1" style="width: 55px;">
                                            <div class="avatar-frame rounded-circle overflow-hidden mx-auto">
                                                <img src="{{ $nextOfficial->image_url }}" 
                                                     alt="{{ $nextOfficial->name }}" 
                                                     onerror="this.onerror=null;this.src='{{ asset('uploads/avatars/default.svg') }}';" 
                                                     class="w-100 h-100" 
                                                     style="object-fit: cover;">
                                            </div>
                                        </td>
                                        <!-- নাম -->
                                        <td class="align-middle">
                                            <span class="official-name d-block">{{ $nextOfficial->name }}</span>
                                        </td>
                                        <!-- পদবী -->
                                        <td class="text-center align-middle" style="width: 125px;">
                                            <span class="badge {{ $badgeClass }}">{{ $desigNext }}</span>
                                        </td>
                                        <!-- মোবাইল নাম্বার -->
                                        <td class="align-middle" style="min-width: 170px;">
                                            @if($nextOfficial->phone)
                                                <div class="d-flex align-items-center justify-content-between gap-1.5">
                                                    <a href="tel:{{ $nextOfficial->phone }}" class="phone-link text-decoration-none" title="কল করতে ট্যাপ করুন">
                                                        <i class="fas fa-phone-alt text-success me-1" style="font-size: 10px;"></i>
                                                        <span class="phone-text">{{ $nextOfficial->phone }}</span>
                                                    </a>
                                                    <a href="tel:{{ $nextOfficial->phone }}" class="btn-call-mini flex-shrink-0" title="{{ $nextOfficial->name }}-কে সরাসরি কল করুন">
                                                        <i class="fas fa-phone-alt"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
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
        var totalBranches = {{ count($branches) }};

        // Toggle clear search button
        if (searchVal.length > 0) {
            $('#clear-search-btn').removeClass('d-none');
        } else {
            $('#clear-search-btn').addClass('d-none');
        }
        
        var visibleBranchIds = {};

        $('.branch-row').each(function() {
            var row = $(this);
            var branchId = row.data('branch-id');
            var type = row.data('type');
            var name = row.data('name');

            var typeMatch = (activeFilter === 'all' || type === activeFilter);
            var searchMatch = (!searchVal || name.indexOf(searchVal) > -1);

            if (typeMatch && searchMatch) {
                visibleBranchIds[branchId] = true;
            }
        });

        // Show/hide all corresponding rows for matching branches
        $('.branch-row').each(function() {
            var row = $(this);
            var branchId = row.data('branch-id');
            if (visibleBranchIds[branchId]) {
                row.show();
            } else {
                row.hide();
            }
        });

        var visibleCount = Object.keys(visibleBranchIds).length;

        // Show/hide empty state
        if (visibleCount === 0) {
            $('#no-search-results').removeClass('d-none');
            $('#branches-table').addClass('d-none');
            $('#filtered-status-text').text('কোনো মিল পাওয়া যায়নি');
        } else {
            $('#no-search-results').addClass('d-none');
            $('#branches-table').removeClass('d-none');
            if (visibleCount < totalBranches) {
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
