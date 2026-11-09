@extends('theme::layouts.app')

@section('title', 'কার্যালয় ও শাখা সমূহ - হেযবুত তওহীদ')
@section('meta_description', 'হেযবুত তওহীদ আন্দোলনের কেন্দ্রীয় কার্যালয়, বিভাগীয়, জেলা, উপজেলা এবং আন্তর্জাতিক শাখাসমূহের ঠিকানা ও যোগাযোগের নম্বর')

@push('styles')
<style>
    .grid-pattern-mask {
        background-color: #f8fafc;
    }

    /* Directory Toolbar */
    .directory-toolbar {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 0.5rem;
        padding: 0.65rem 0.85rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .search-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .search-input-wrap .search-icon {
        position: absolute;
        left: 12px;
        font-size: 13px;
        color: #006A4E !important;
        pointer-events: none;
        z-index: 4;
    }

    .branch-search-input {
        height: 38px !important;
        padding-left: 34px !important;
        padding-right: 32px !important;
        font-size: 0.88rem !important;
        font-family: 'Baloo Da 2', sans-serif !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 20px !important;
        background-color: #f8fafc !important;
        box-shadow: none !important;
        transition: all 0.2s ease !important;
    }

    .branch-search-input:focus {
        background-color: #ffffff !important;
        border-color: #006A4E !important;
        box-shadow: 0 0 0 3px rgba(0, 106, 78, 0.1) !important;
    }

    .clear-search-btn {
        position: absolute;
        right: 4px;
        z-index: 4;
        padding: 4px 8px;
        font-size: 14px;
        color: #94a3b8;
        box-shadow: none !important;
        text-decoration: none !important;
    }
    .clear-search-btn:hover {
        color: #64748b;
    }

    /* Filter Horizontal Scroll Strip */
    .filter-scroll-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        padding: 2px 0;
    }
    .filter-scroll-container::-webkit-scrollbar {
        display: none;
    }

    .filter-pills-track {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .btn-filter {
        border: 1px solid #cbd5e1;
        color: #475569;
        background-color: #ffffff;
        transition: all 0.15s ease;
        font-size: 0.82rem;
        font-family: 'Baloo Da 2', sans-serif;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
        cursor: pointer;
        line-height: 1.2;
    }

    .btn-filter:hover {
        border-color: #006A4E;
        color: #006A4E;
        background-color: #f0fdf4;
    }

    .btn-filter.active {
        background: #006A4E !important;
        color: #ffffff !important;
        border-color: #006A4E !important;
        box-shadow: 0 1px 3px rgba(0, 106, 78, 0.2) !important;
    }

    @media (max-width: 767.98px) {
        .directory-toolbar {
            padding: 0.55rem 0.65rem;
        }
        .filter-pills-track {
            padding-top: 2px;
        }
    }

    /* Natural Table Card */
    .directory-table-card {
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.375rem !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
        background: #ffffff;
        overflow: hidden;
    }

    /* Natural, Compact, Bordered Table */
    .directory-table {
        margin-bottom: 0 !important;
        border-collapse: collapse !important;
        border: 1px solid #cbd5e1 !important;
        font-family: 'Baloo Da 2', sans-serif;
        background: #ffffff;
        font-size: 0.88rem;
    }

    .directory-table th, 
    .directory-table td {
        border: 1px solid #cbd5e1 !important;
        padding: 4px 7px !important; /* Low height padding */
        vertical-align: middle;
        line-height: 1.25 !important;
    }

    /* Clean Natural Light Header Row 1 */
    .directory-table thead tr.header-row-main th {
        background-color: #f1f5f9 !important;
        color: #1e293b !important;
        font-weight: 700;
        font-size: 0.9rem;
        padding: 6px 8px !important;
        border: 1px solid #cbd5e1 !important;
        text-align: center;
    }

    /* Clean Natural Light Header Row 2 */
    .directory-table thead tr.header-row-sub th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-weight: 600;
        font-size: 0.84rem;
        padding: 5px 8px !important;
        border: 1px solid #cbd5e1 !important;
        text-align: center;
    }

    /* Natural Compact Rows */
    .directory-table tbody tr {
        background-color: #ffffff;
        transition: background-color 0.1s ease;
    }

    .directory-table tbody tr:hover {
        background-color: #f8fafc !important;
    }

    /* Separator line between different districts */
    .branch-last-row td {
        border-bottom: 2px solid #94a3b8 !important;
    }

    /* Serial number */
    .serial-badge {
        font-weight: 700;
        font-size: 0.9rem;
        color: #334155;
        display: inline-block;
    }

    /* Compact Rounded Avatar Frame (Rounded, not circular, Click to Zoom) */
    .avatar-frame {
        width: 28px;
        height: 28px;
        border-radius: 4px !important;
        border: 1px solid #cbd5e1;
        background-color: #f1f5f9;
        cursor: pointer;
        display: inline-block;
        overflow: hidden;
        transition: transform 0.15s ease, border-color 0.15s ease;
        vertical-align: middle;
    }

    .avatar-frame:hover {
        transform: scale(1.2);
        border-color: #006A4E !important;
    }

    .avatar-frame img {
        border-radius: 3px !important;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .official-name {
        font-weight: 600;
        color: #0f172a;
        font-size: 0.89rem;
        line-height: 1.2;
    }

    /* Natural Subtle Designation Badges */
    .badge-amir-pill {
        background: #f1f5f9 !important;
        color: #006A4E !important;
        border: 1px solid #cbd5e1 !important;
        font-weight: 700 !important;
        font-size: 10px !important;
        padding: 1px 5px !important;
        border-radius: 3px !important;
        white-space: nowrap;
        display: inline-block;
    }

    .badge-assistant-pill {
        background: #f8fafc !important;
        color: #475569 !important;
        border: 1px solid #cbd5e1 !important;
        font-weight: 600 !important;
        font-size: 10px !important;
        padding: 1px 5px !important;
        border-radius: 3px !important;
        white-space: nowrap;
        display: inline-block;
    }

    .badge-other-pill {
        background: #f8fafc !important;
        color: #64748b !important;
        border: 1px solid #cbd5e1 !important;
        font-weight: 600 !important;
        font-size: 10px !important;
        padding: 1px 5px !important;
        border-radius: 3px !important;
        white-space: nowrap;
        display: inline-block;
    }

    /* Phone Link and Micro Call Button */
    .phone-link {
        color: #1e293b;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
    }

    .phone-link:hover {
        color: #006A4E !important;
        text-decoration: underline;
    }

    .btn-call-mini {
        width: 22px;
        height: 22px;
        border-radius: 3px;
        background: #f1f5f9;
        color: #006A4E !important;
        border: 1px solid #cbd5e1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 9.5px;
        transition: all 0.15s ease;
    }

    .btn-call-mini:hover {
        background: #006A4E;
        color: #ffffff !important;
        border-color: #006A4E;
    }

    /* Compact Address Text */
    .address-text-styled {
        color: #334155;
        font-size: 0.84rem;
        line-height: 1.3;
        font-weight: 500;
    }

    .copy-address-btn {
        transition: all 0.15s ease;
        font-size: 10px !important;
        padding: 1px 5px !important;
        font-family: 'Baloo Da 2', sans-serif !important;
        border-color: #cbd5e1 !important;
        color: #475569 !important;
    }

    .copy-address-btn:hover {
        background-color: #f1f5f9 !important;
        color: #006A4E !important;
        border-color: #94a3b8 !important;
    }

    /* Custom Responsive Scrollbar */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
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
    <section class="py-4 grid-pattern-mask" style="min-height: 70vh; position: relative;">
        <div class="container">
            
            <!-- Sleek Compact Filter & Search Toolbar -->
            <div class="directory-toolbar mb-2.5">
                <div class="row g-2 align-items-center">
                    <!-- Search Input Box -->
                    <div class="col-12 col-md-5 col-lg-4">
                        <div class="search-input-wrap">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="branch-search" class="form-control branch-search-input" 
                                   placeholder="কার্যালয়, জেলা বা কর্মকর্তা খুঁজুন..." autocomplete="off">
                            <button class="btn btn-link clear-search-btn d-none" id="clear-search-btn" type="button" title="মুছে ফেলুন">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Filter Pills Horizontal Strip -->
                    <div class="col-12 col-md-7 col-lg-8">
                        <div class="filter-scroll-container">
                            <div class="filter-pills-track" id="filter-controls">
                                <button class="btn btn-filter active" data-filter="all">
                                    <i class="fas fa-border-all me-1"></i>সকল
                                </button>
                                <button class="btn btn-filter" data-filter="central">
                                    <i class="fas fa-hotel me-1"></i>কেন্দ্রীয়
                                </button>
                                <button class="btn btn-filter" data-filter="division">
                                    <i class="fas fa-sitemap me-1"></i>বিভাগীয়
                                </button>
                                <button class="btn btn-filter" data-filter="district">
                                    <i class="fas fa-map-marked-alt me-1"></i>জেলা
                                </button>
                                <button class="btn btn-filter" data-filter="upazila">
                                    <i class="fas fa-street-view me-1"></i>উপজেলা
                                </button>
                                <button class="btn btn-filter" data-filter="international">
                                    <i class="fas fa-globe-americas me-1"></i>আন্তর্জাতিক
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Natural Clean Status & Mobile Scroll Hint Row -->
            <div class="d-flex align-items-center justify-content-between mb-2 px-1">
                <span class="text-secondary small fw-medium" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.83rem;">
                    <i class="fas fa-building text-success me-1"></i> মোট কার্যালয়: <strong class="text-dark">{{ count($branches) }}</strong> টি
                    <span class="text-muted ms-1" id="filtered-status-text">(সবগুলো প্রদর্শিত হচ্ছে)</span>
                </span>
                <span class="d-md-none text-muted small" style="font-family: 'Baloo Da 2', sans-serif; font-size: 0.78rem;">
                    <i class="fas fa-arrows-left-right text-success me-1"></i>ডানে স্ক্রল করুন
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

            <!-- Natural Compact Directory Data Table Card -->
            <div class="directory-table-card">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered directory-table" id="branches-table">
                        <thead>
                            <!-- Header Row 1 (Natural Light Grey) -->
                            <tr class="header-row-main">
                                <th rowspan="2" class="text-center align-middle" style="width: 42px;">#</th>
                                <th rowspan="2" class="align-middle" style="min-width: 160px;">জেলার নাম</th>
                                <th rowspan="2" class="align-middle" style="min-width: 190px;">ঠিকানা</th>
                                <th colspan="4" class="text-center align-middle">দায়িত্বপ্রাপ্ত ব্যক্তিবৃন্দ</th>
                            </tr>
                            <!-- Header Row 2: Sub-headers under দায়িত্বপ্রাপ্ত ব্যক্তিবৃন্দ -->
                            <tr class="header-row-sub">
                                <th class="text-center align-middle" style="width: 44px;">ছবি</th>
                                <th class="align-middle" style="min-width: 150px;">নাম</th>
                                <th class="text-center align-middle" style="width: 110px;">পদবী</th>
                                <th class="align-middle" style="min-width: 160px;">মোবাইল নাম্বার</th>
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
                                @endphp

                                <!-- Row 1 of this branch -->
                                <tr class="branch-row @if($rowCount == 1) branch-last-row @endif" 
                                    data-branch-id="{{ $branch->id }}" 
                                    data-type="{{ $branch->type }}" 
                                    data-name="{{ strtolower($branch->name) }} {{ strtolower($branch->address) }} @foreach($branch->officials as $off){{ strtolower($off->name) }} {{ strtolower($off->formatted_designation) }} {{ strtolower($off->designation) }} {{ $off->phone }} @endforeach">
                                    
                                    <!-- 1. # -->
                                    <td rowspan="{{ $rowCount }}" class="text-center align-middle">
                                        <span class="serial-badge">{{ $serialBn }}</span>
                                    </td>

                                    <!-- 2. জেলার নাম (শুধু কার্যালয়ের নাম) -->
                                    <td rowspan="{{ $rowCount }}" class="align-middle">
                                        <div class="fw-bold text-dark" style="font-size: 0.92rem; line-height: 1.35;">
                                            {{ $branch->name }}
                                        </div>
                                    </td>

                                    <!-- 3. ঠিকানা (ঠিকানা এবং নিচের লাইনে মোবাইল নাম্বার) -->
                                    <td rowspan="{{ $rowCount }}" class="align-middle">
                                        <div class="address-text-styled mb-0.5">
                                            <i class="fas fa-map-marker-alt text-danger me-1" style="font-size: 9.5px;"></i>
                                            {{ $branch->address }}
                                        </div>
                                        @if($branch->phone)
                                            <div class="mb-1">
                                                <a href="tel:{{ $branch->phone }}" class="phone-link text-decoration-none" style="font-size: 0.83rem;" title="অফিসের মোবাইল নাম্বার: কল করতে ক্লিক করুন">
                                                    <i class="fas fa-phone-alt text-success me-1" style="font-size: 9px;"></i>
                                                    <span class="phone-text">{{ $branch->phone }}</span>
                                                </a>
                                            </div>
                                        @endif
                                        <div class="d-flex align-items-center gap-1 flex-wrap">
                                            <button class="btn btn-xs btn-outline-secondary rounded copy-address-btn" 
                                                    data-address="{{ $branch->address }}" 
                                                    title="ঠিকানা কপি করুন">
                                                <i class="far fa-copy me-1"></i>কপি
                                            </button>
                                            @if($branch->google_map_url)
                                                <button class="btn btn-xs btn-outline-success rounded show-map-btn fw-bold" 
                                                        data-name="{{ $branch->name }}" 
                                                        data-map="{{ $branch->google_map_url }}" 
                                                        style="font-size: 10px !important; padding: 1px 5px !important;"
                                                        title="ম্যাপ দেখুন">
                                                    <i class="fas fa-map-marked-alt me-1"></i>ম্যাপ
                                                </button>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- 4. ছবি (রো ১: জেলা আমির - রাউন্ডেড এবং ক্লিক করলে জুম) -->
                                    @if($firstOfficial)
                                        @php
                                            $desig1 = $formatDesig($firstOfficial->designation, true);
                                        @endphp
                                        <td class="text-center align-middle avatar-cell" style="width: 44px; padding: 2px 4px !important;">
                                            <div class="avatar-frame zoomable-avatar mx-auto" 
                                                 data-name="{{ $firstOfficial->name }}" 
                                                 data-desig="{{ $desig1 }}" 
                                                 data-img="{{ $firstOfficial->image_url }}"
                                                 title="ছবি বড় করে দেখতে ক্লিক করুন">
                                                <img src="{{ $firstOfficial->image_url }}" 
                                                     alt="{{ $firstOfficial->name }}" 
                                                     onerror="this.onerror=null;this.src='{{ asset('uploads/avatars/default.svg') }}';">
                                            </div>
                                        </td>
                                        <!-- 5. নাম -->
                                        <td class="align-middle">
                                            <span class="official-name d-block">{{ $firstOfficial->name }}</span>
                                        </td>
                                        <!-- 6. পদবী -->
                                        <td class="text-center align-middle" style="width: 110px;">
                                            <span class="badge badge-amir-pill">{{ $desig1 }}</span>
                                        </td>
                                        <!-- 7. মোবাইল নাম্বার -->
                                        <td class="align-middle" style="min-width: 160px;">
                                            @if($firstOfficial->phone)
                                                <div class="d-flex align-items-center justify-content-between gap-1">
                                                    <a href="tel:{{ $firstOfficial->phone }}" class="phone-link text-decoration-none" title="কল করতে ট্যাপ করুন">
                                                        <i class="fas fa-phone-alt text-success me-1" style="font-size: 9px;"></i>
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
                                        <td colspan="4" class="text-center text-muted small py-1">— কোনো কর্মকর্তা তালিকাভুক্ত নেই —</td>
                                    @endif
                                </tr>

                                <!-- Subsequent rows for this branch (e.g. Row 2 for সহকারী আমির) -->
                                @foreach($sortedOfficials->slice(1) as $subIndex => $nextOfficial)
                                    @php
                                        $desigNext = $formatDesig($nextOfficial->designation, false);
                                        $badgeClass = ($desigNext === 'সহকারী আমির') ? 'badge-assistant-pill' : 'badge-other-pill';
                                        $isLastSubRow = ($subIndex == count($sortedOfficials) - 2);
                                    @endphp
                                    <tr class="branch-row @if($isLastSubRow) branch-last-row @endif" 
                                        data-branch-id="{{ $branch->id }}" 
                                        data-type="{{ $branch->type }}" 
                                        data-name="{{ strtolower($branch->name) }} {{ strtolower($branch->address) }} @foreach($branch->officials as $off){{ strtolower($off->name) }} {{ strtolower($off->formatted_designation) }} {{ strtolower($off->designation) }} {{ $off->phone }} @endforeach">
                                        
                                        <!-- ছবি (রাউন্ডেড এবং ক্লিক করলে জুম) -->
                                        <td class="text-center align-middle avatar-cell" style="width: 44px; padding: 2px 4px !important;">
                                            <div class="avatar-frame zoomable-avatar mx-auto" 
                                                 data-name="{{ $nextOfficial->name }}" 
                                                 data-desig="{{ $desigNext }}" 
                                                 data-img="{{ $nextOfficial->image_url }}"
                                                 title="ছবি বড় করে দেখতে ক্লিক করুন">
                                                <img src="{{ $nextOfficial->image_url }}" 
                                                     alt="{{ $nextOfficial->name }}" 
                                                     onerror="this.onerror=null;this.src='{{ asset('uploads/avatars/default.svg') }}';">
                                            </div>
                                        </td>
                                        <!-- নাম -->
                                        <td class="align-middle">
                                            <span class="official-name d-block">{{ $nextOfficial->name }}</span>
                                        </td>
                                        <!-- পদবী -->
                                        <td class="text-center align-middle" style="width: 110px;">
                                            <span class="badge {{ $badgeClass }}">{{ $desigNext }}</span>
                                        </td>
                                        <!-- মোবাইল নাম্বার -->
                                        <td class="align-middle" style="min-width: 160px;">
                                            @if($nextOfficial->phone)
                                                <div class="d-flex align-items-center justify-content-between gap-1">
                                                    <a href="tel:{{ $nextOfficial->phone }}" class="phone-link text-decoration-none" title="কল করতে ট্যাপ করুন">
                                                        <i class="fas fa-phone-alt text-success me-1" style="font-size: 9px;"></i>
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
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-map-marker-alt fa-2x mb-2 text-secondary"></i>
                                        <p class="mb-0">কোনো কার্যালয় বা শাখা পাওয়া যায়নি।</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- No Results message when search filters everything out -->
                <div id="no-search-results" class="text-center py-4 d-none">
                    <i class="fas fa-search fa-2x mb-2 text-muted"></i>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">কোনো তথ্য পাওয়া যায়নি</h6>
                    <p class="text-muted small mb-0">আপনার সার্চ করা কিওয়ার্ডের সাথে কোনো কার্যালয় বা কর্মকর্তার নাম মেলেনি।</p>
                </div>
            </div>
            
        </div>
    </section>

    <!-- Bootstrap Google Map Modal with Premium Backdrop Blur -->
    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true" style="backdrop-filter: blur(8px); background: rgba(0,0,0,0.45);">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden" style="background: #ffffff;">
                <div class="modal-header bg-dark text-white p-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold" id="mapModalLabel" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.05rem;"><i class="fas fa-map-marked-alt me-2 text-success"></i> কার্যালয়ের অবস্থান</h5>
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

    <!-- Bootstrap Image Zoom Modal -->
    <div class="modal fade" id="imageZoomModal" tabindex="-1" aria-hidden="true" style="backdrop-filter: blur(8px); background: rgba(0,0,0,0.45);">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 300px;">
            <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden text-center" style="background: #ffffff;">
                <div class="modal-header border-0 pb-0 pt-2.5 pe-2.5 justify-content-end">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 pt-0">
                    <div class="rounded-3 overflow-hidden shadow-sm mx-auto mb-2 border border-light" style="max-width: 220px; aspect-ratio: 1/1; background-color: #f1f5f9;">
                        <img id="zoomModalImg" src="" alt="" class="w-100 h-100" style="object-fit: cover;" onerror="this.onerror=null;this.src='{{ asset('uploads/avatars/default.svg') }}';">
                    </div>
                    <h5 class="fw-bold text-dark mb-1" id="zoomModalName" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.05rem;"></h5>
                    <span class="badge badge-amir-pill" id="zoomModalDesig" style="font-size: 10.5px;"></span>
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
            $('#filtered-status-text').text('(কোনো মিল পাওয়া যায়নি)');
        } else {
            $('#no-search-results').addClass('d-none');
            $('#branches-table').removeClass('d-none');
            if (visibleCount < totalBranches) {
                $('#filtered-status-text').text('(' + visibleCount + ' টি প্রদর্শিত হচ্ছে)');
            } else {
                $('#filtered-status-text').text('(সবগুলো প্রদর্শিত হচ্ছে)');
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

        $('#mapModalLabel').html('<i class="fas fa-map-marked-alt me-2 text-success"></i> ' + branchName + ' - অবস্থান');
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
            btn.html('<i class="fas fa-check text-success me-1"></i>কপি').addClass('btn-light');
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

    // 4. Click to zoom official image
    $(document).on('click', '.zoomable-avatar', function() {
        var imgUrl = $(this).data('img');
        var name = $(this).data('name');
        var desig = $(this).data('desig');

        $('#zoomModalImg').attr('src', imgUrl);
        $('#zoomModalName').text(name);
        $('#zoomModalDesig').text(desig);
        $('#imageZoomModal').modal('show');
    });
});
</script>
@endpush
