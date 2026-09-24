@extends('admin.layouts.master')

@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid pt-3">
    <!-- Header Page title -->
    <div class="row align-items-center mb-4">
        <div class="col-sm-6">
            <h1 class="m-0 fs-2 fw-bold text-dark"><i class="fas fa-tachometer-alt me-2 text-success"></i>ড্যাশবোর্ড</h1>
        </div>
        <div class="col-sm-6 text-sm-end text-end mt-2 mt-sm-0">
            <span class="badge bg-success bg-opacity-10 text-success p-2 fs-7 fw-semibold rounded-pill">
                <i class="far fa-calendar-alt me-2"></i>{{ now()->format('d M, Y') }}
            </span>
        </div>
    </div>

    <!-- Row 1: Key Metrics Grid (using AdminLTE v4 Info Boxes) -->
    <div class="row mb-4">
        <!-- Card 1: Blog Posts -->
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <a href="{{ route('admin.blog.posts.index') }}" class="text-decoration-none text-dark d-block h-100">
                <div class="info-box shadow-sm h-100">
                    <span class="info-box-icon text-bg-success shadow-sm rounded-3 align-self-center ms-3" style="width: 64px; height: 64px; min-width: 64px; display: flex; align-items: center; justify-content: center; float: none;">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">মোট ব্লগ পোস্ট</span>
                        <span class="info-box-number">{{ number_format($stats['total_posts']) }}</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card 2: Join Requests -->
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <a href="{{ route('admin.join-requests.index') }}" class="text-decoration-none text-dark d-block h-100">
                <div class="info-box shadow-sm h-100">
                    <span class="info-box-icon text-bg-danger shadow-sm rounded-3 align-self-center ms-3" style="width: 64px; height: 64px; min-width: 64px; display: flex; align-items: center; justify-content: center; float: none;">
                        <i class="bi bi-person-plus-fill"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">সদস্যতার আবেদন</span>
                        <span class="info-box-number">
                            {{ number_format($stats['total_join_requests']) }}
                            <small class="text-danger d-block text-xs fw-normal mt-1">পেন্ডিং: {{ $stats['unread_join_requests'] }}</small>
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card 3: Newsletter Subscribers -->
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <a href="#" class="text-decoration-none text-dark d-block h-100">
                <div class="info-box shadow-sm h-100">
                    <span class="info-box-icon text-bg-info shadow-sm rounded-3 align-self-center ms-3" style="width: 64px; height: 64px; min-width: 64px; display: flex; align-items: center; justify-content: center; float: none;">
                        <i class="bi bi-envelope-at-fill"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">নিউজলেটার সাবস্ক্রাইবার</span>
                        <span class="info-box-number">{{ number_format($stats['total_subscribers']) }}</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card 4: Contact Messages -->
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <a href="{{ route('admin.contacts.index') }}" class="text-decoration-none text-dark d-block h-100">
                <div class="info-box shadow-sm h-100">
                    <span class="info-box-icon text-bg-warning text-white shadow-sm rounded-3 align-self-center ms-3" style="width: 64px; height: 64px; min-width: 64px; display: flex; align-items: center; justify-content: center; float: none;">
                        <i class="bi bi-chat-left-text-fill text-white"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">যোগাযোগের বার্তা</span>
                        <span class="info-box-number">
                            {{ number_format($stats['total_messages']) }}
                            <small class="text-warning d-block text-xs fw-normal mt-1">অপঠিত: {{ $stats['unread_messages'] }}</small>
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>

        <!-- Row 1.5: Visitor Overview Grid (using AdminLTE v4 Info Boxes) -->
    <div class="row mb-4">
        <!-- Card 1: Unique Visitors -->
        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="info-box shadow-sm h-100">
                <span class="info-box-icon text-bg-primary shadow-sm rounded-3 align-self-center ms-3" style="width: 64px; height: 64px; min-width: 64px; display: flex; align-items: center; justify-content: center; float: none;">
                    <i class="bi bi-people-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">আজকের ইউনিক ভিজিটর</span>
                    <span class="info-box-number">{{ number_format($stats['today_visitors']) }}</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Page Views -->
        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="info-box shadow-sm h-100">
                <span class="info-box-icon text-bg-success shadow-sm rounded-3 align-self-center ms-3" style="width: 64px; height: 64px; min-width: 64px; display: flex; align-items: center; justify-content: center; float: none;">
                    <i class="bi bi-eye-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">আজকের পেজ ভিউ</span>
                    <span class="info-box-number">{{ number_format($stats['today_page_views']) }}</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Unique Visitors -->
        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="info-box shadow-sm h-100">
                <span class="info-box-icon text-bg-info shadow-sm rounded-3 align-self-center ms-3" style="width: 64px; height: 64px; min-width: 64px; display: flex; align-items: center; justify-content: center; float: none;">
                    <i class="bi bi-globe"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">সর্বমোট ইউনিক ভিজিটর</span>
                    <span class="info-box-number">{{ number_format($stats['total_visitors']) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Secondary Metrics -->
    <h5 class="mb-3 fw-bold text-dark"><i class="fas fa-landmark text-success me-2"></i>সাংগঠনিক ও প্রকাশনা ওভারভিউ</h5>
    <div class="row mb-4">
        <!-- Branches -->
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <a href="{{ route('admin.branches.index') }}" class="text-decoration-none text-dark d-block h-100">
                <div class="info-box shadow-sm h-100">
                    <span class="info-box-icon text-bg-success shadow-sm rounded-3 align-self-center ms-3" style="width: 64px; height: 64px; min-width: 64px; display: flex; align-items: center; justify-content: center; float: none;">
                        <i class="bi bi-geo-alt-fill"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">মোট সাংগঠনিক শাখা</span>
                        <span class="info-box-number">{{ $stats['total_branches'] }}টি</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Leaders -->
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <a href="{{ route('admin.leaders.index') }}" class="text-decoration-none text-dark d-block h-100">
                <div class="info-box shadow-sm h-100">
                    <span class="info-box-icon text-bg-info shadow-sm rounded-3 align-self-center ms-3" style="width: 64px; height: 64px; min-width: 64px; display: flex; align-items: center; justify-content: center; float: none;">
                        <i class="bi bi-people-fill"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">মোট দায়িত্বশীল/নেতৃবৃন্দ</span>
                        <span class="info-box-number">{{ $stats['total_leaders'] }}জন</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Books / Publications -->
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <a href="{{ route('admin.books.index') }}" class="text-decoration-none text-dark d-block h-100">
                <div class="info-box shadow-sm h-100">
                    <span class="info-box-icon text-bg-danger shadow-sm rounded-3 align-self-center ms-3" style="width: 64px; height: 64px; min-width: 64px; display: flex; align-items: center; justify-content: center; float: none;">
                        <i class="bi bi-book-half"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">মোট বই ও সাহিত্য</span>
                        <span class="info-box-number">{{ $stats['total_books'] }}টি</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Songs / Nasheeds & Videos -->
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="info-box shadow-sm h-100">
                <span class="info-box-icon text-bg-warning text-white shadow-sm rounded-3 align-self-center ms-3" style="width: 64px; height: 64px; min-width: 64px; display: flex; align-items: center; justify-content: center; float: none;">
                    <i class="bi bi-play-circle-fill text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">মিডিয়া লাইব্রেরি</span>
                    <span class="info-box-number text-xs mt-1 d-block">
                        <a href="{{ route('admin.songs.index') }}" class="text-decoration-none text-success me-2">সঙ্গীত ({{ $stats['total_songs'] }})</a>
                        <a href="{{ route('admin.videos.index') }}" class="text-decoration-none text-danger">ভিডিও ({{ $stats['total_videos'] }})</a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Charts & Graphs (using AdminLTE Card Layout) -->
    <div class="row mb-4">
        <!-- Blog posting trend -->
        <div class="col-lg-6 mb-4">
            <div class="card card-outline card-success h-100">
                <div class="card-header bg-transparent border-0 py-3">
                    <h3 class="card-title fw-bold text-dark">
                        <i class="fas fa-chart-line text-success me-2"></i>ব্লগ পোস্টের ট্রেন্ড (১২ মাস)
                    </h3>
                </div>
                <div class="card-body">
                    <div style="height: 250px;">
                        <canvas id="postsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Join Requests Trend -->
        <div class="col-lg-6 mb-4">
            <div class="card card-outline card-danger h-100">
                <div class="card-header bg-transparent border-0 py-3">
                    <h3 class="card-title fw-bold text-dark">
                        <i class="fas fa-chart-bar text-danger me-2"></i>আবেদনের ট্রেন্ড (১২ মাস)
                    </h3>
                </div>
                <div class="card-body">
                    <div style="height: 250px;">
                        <canvas id="joinRequestsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Section 3.5: Visitor Analytics Charts -->
    <div class="row mb-4">
        <!-- Visitor Monthly Trend -->
        <div class="col-lg-8 mb-4">
            <div class="card card-outline card-primary h-100">
                <div class="card-header bg-transparent border-0 py-3">
                    <h3 class="card-title fw-bold text-dark">
                        <i class="bi bi-graph-up text-primary me-2"></i>মাসিক ইউনিক ভিজিটর ট্রেন্ড (১২ মাস)
                    </h3>
                </div>
                <div class="card-body">
                    <div style="height: 250px;">
                        <canvas id="visitorTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Device share breakdown -->
        <div class="col-lg-4 mb-4">
            <div class="card card-outline card-info h-100">
                <div class="card-header bg-transparent border-0 py-3">
                    <h3 class="card-title fw-bold text-dark">
                        <i class="bi bi-phone text-info me-2"></i>ডিভাইস শেয়ার (ভিজিটর)
                    </h3>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <div style="height: 180px; width: 100%;">
                        <canvas id="deviceShareChart"></canvas>
                    </div>
                    <div class="mt-3 text-center small text-muted">
                        ডেস্কটপ ও মোবাইল ব্রাউজিং পরিসংখ্যান
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 4: Data Tables (using AdminLTE v4 Card and valigned-middle Tables) -->
    <!-- Table 1: Recent Join Requests -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-outline card-danger">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h3 class="card-title fw-bold text-dark mb-0">
                        <i class="fas fa-user-plus text-danger me-2"></i>সাম্প্রতিক সদস্যতার আবেদনসমূহ
                    </h3>
                    <a href="{{ route('admin.join-requests.index') }}" class="btn btn-xs btn-outline-danger ms-auto">সব দেখুন</a>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle mb-0">
                        <thead>
                            <tr>
                                <th>নাম</th>
                                <th>মোবাইল নম্বর</th>
                                <th class="text-center">টাইপ</th>
                                <th class="text-center">স্ট্যাটাস</th>
                                <th class="text-center">সময়</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentJoinRequests as $req)
                            <tr>
                                <td class="fw-semibold">{{ $req['name'] }}</td>
                                <td>{{ $req['phone'] }}</td>
                                <td class="text-center">{!! $req['type_badge'] !!}</td>
                                <td class="text-center">{!! $req['status_badge'] !!}</td>
                                <td class="text-center text-muted"><small>{{ $req['created_at'] }}</small></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">কোন আবেদন পাওয়া যায়নি</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Table 2: Recent Blog Posts -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-outline card-success">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h3 class="card-title fw-bold text-dark mb-0">
                        <i class="fas fa-newspaper text-success me-2"></i>সাম্প্রতিক প্রকাশিত ব্লগ পোস্ট সমূহ
                    </h3>
                    <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-xs btn-outline-success ms-auto">সব দেখুন</a>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle mb-0">
                        <thead>
                            <tr>
                                <th>শিরোনাম</th>
                                <th>ক্যাটাগরি</th>
                                <th class="text-center">ভিউ</th>
                                <th class="text-center">স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPosts as $post)
                            <tr>
                                <td class="fw-semibold">{{ Str::limit($post['title'], 60) }}</td>
                                <td><span class="badge bg-secondary">{{ $post['category'] }}</span></td>
                                <td class="text-center">{{ number_format($post['views']) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $post['status'] === 'published' ? 'success' : 'warning' }}">
                                        {{ $post['status'] === 'published' ? 'প্রকাশিত' : 'খসড়া' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted">কোন ব্লগ পোস্ট পাওয়া যায়নি</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Table 3: Recent Contact Messages -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-outline card-warning">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h3 class="card-title fw-bold text-dark mb-0">
                        <i class="fas fa-inbox text-warning me-2"></i>সাম্প্রতিক যোগাযোগের বার্তা (ইনবক্স)
                    </h3>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-xs btn-outline-warning ms-auto">সব দেখুন</a>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle mb-0">
                        <thead>
                            <tr>
                                <th>প্রেরক</th>
                                <th>বিষয়</th>
                                <th class="text-center">স্ট্যাটাস</th>
                                <th class="text-center">সময়</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMessages as $msg)
                            <tr>
                                <td>
                                    <span class="fw-semibold">{{ Str::limit($msg['name'], 25) }}</span>
                                    <small class="text-muted d-block text-xs">{{ $msg['email'] }}</small>
                                </td>
                                <td>{{ Str::limit($msg['subject'], 50) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $msg['is_read'] ? 'success' : 'danger' }}">
                                        {{ $msg['is_read'] ? 'পঠিত' : 'নতুন' }}
                                    </span>
                                </td>
                                <td class="text-center text-muted"><small>{{ $msg['created_at'] }}</small></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted">কোন যোগাযোগের বার্তা পাওয়া যায়নি</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Table 4: Admin Activity Audit Trail -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-outline card-info">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h3 class="card-title fw-bold text-dark mb-0">
                        <i class="fas fa-user-shield text-info me-2"></i>অ্যাডমিন অ্যাক্টিভিটি লগ (রিয়েল-টাইম)
                    </h3>
                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-xs btn-outline-info ms-auto">সব দেখুন</a>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle mb-0">
                        <thead>
                            <tr>
                                <th>অ্যাডমিন</th>
                                <th class="text-center">অ্যাকশন</th>
                                <th class="text-center">মডিউল</th>
                                <th>বিবরণ</th>
                                <th class="text-center">সময়</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivityLogs as $log)
                            <tr>
                                <td><span class="fw-semibold">{{ $log->user?->name ?? 'সিস্টেম' }}</span></td>
                                <td class="text-center">{!! $log->action_badge !!}</td>
                                <td class="text-center">{!! $log->module_badge !!}</td>
                                <td>{{ Str::limit($log->description, 50) }}</td>
                                <td class="text-center text-muted"><small>{{ $log->created_at->diffForHumans() }}</small></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">কোন লগ পাওয়া যায়নি</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

        <!-- Table 5: Top Visited Pages -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header py-3">
                    <h3 class="card-title fw-bold text-dark mb-0">
                        <i class="bi bi-file-earmark-spreadsheet text-primary me-2"></i>টপ ভিজিটেড পেজ সমূহ (সর্বোচ্চ ভিউ)
                    </h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle mb-0">
                        <thead>
                            <tr>
                                <th>ইউআরএল (URL)</th>
                                <th class="text-center">মোট ভিউ সংখ্যা</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topVisitedPages as $page)
                            <tr>
                                <td class="fw-semibold">{{ $page->url }}</td>
                                <td class="text-center"><span class="badge bg-primary">{{ number_format($page->views_count) }} বার</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="text-center py-4 text-muted">কোন ট্রাফিক ডেটা পাওয়া যায়নি</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 6: Diagnostics & System Monitor -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0 py-3">
                    <h3 class="card-title fw-bold text-dark">
                        <i class="fas fa-server text-secondary me-2"></i>সিস্টেম ডায়াগনস্টিকস ও স্ট্যাটাস
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- DB Info -->
                        <div class="col-md-3 col-sm-6 mb-3 mb-md-0 border-end">
                            <span class="text-muted d-block text-sm">ডেটাবেজ স্ট্যাটাস</span>
                            <span class="fw-bold text-dark d-flex align-items-center mt-1">
                                <span class="badge bg-success me-2" style="width: 8px; height: 8px; border-radius: 50%; padding: 0;"></span>
                                @if($systemInfo['db_status'] === 'Connected')
                                    সংযুক্ত (সক্রিয়)
                                @else
                                    অসংযুক্ত
                                @endif
                            </span>
                            <small class="text-muted d-block text-xs mt-1">কানেকশন: {{ strtoupper($systemInfo['db_connection']) }}</small>
                        </div>

                        <!-- Laravel Version -->
                        <div class="col-md-3 col-sm-6 mb-3 mb-md-0 border-end">
                            <span class="text-muted d-block text-sm">লারাভেল সংস্করণ</span>
                            <span class="fw-bold text-dark d-block mt-1"><i class="fab fa-laravel text-danger me-1"></i> v{{ $systemInfo['laravel_version'] }}</span>
                            <small class="text-muted d-block text-xs mt-1">এনভায়রনমেন্ট: {{ ucfirst($systemInfo['environment']) }}</small>
                        </div>

                        <!-- PHP Version -->
                        <div class="col-md-3 col-sm-6 mb-3 mb-md-0 border-end">
                            <span class="text-muted d-block text-sm">পিএইচপি সংস্করণ</span>
                            <span class="fw-bold text-dark d-block mt-1"><i class="fab fa-php text-primary me-1"></i> v{{ $systemInfo['php_version'] }}</span>
                            <small class="text-muted d-block text-xs mt-1">সার্ভার এপিআই: {{ php_sapi_name() }}</small>
                        </div>

                        <!-- Security & Session -->
                        <div class="col-md-3 col-sm-6">
                            <span class="text-muted d-block text-sm">নিরাপত্তা ও সেশন</span>
                            <span class="fw-bold text-success d-block mt-1"><i class="fas fa-shield-alt me-1"></i> SSL সুরক্ষিত</span>
                            <small class="text-muted d-block text-xs mt-1">ডিবাগ মোড: {{ config('app.debug') ? 'সক্রিয়' : 'বন্ধ (নিরাপদ)' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // 1. Blog Posts Trend Chart
    var postsTrendData = @json($monthlyPosts);
    var postsLabels = postsTrendData.map(function(item) { return item.month; });
    var postsValues = postsTrendData.map(function(item) { return item.count; });

    var postsCtx = document.getElementById('postsChart').getContext('2d');
    var postsGradient = postsCtx.createLinearGradient(0, 0, 0, 200);
    postsGradient.addColorStop(0, 'rgba(40, 167, 69, 0.3)'); // green
    postsGradient.addColorStop(1, 'rgba(40, 167, 69, 0.0)');

    new Chart(postsCtx, {
        type: 'line',
        data: {
            labels: postsLabels,
            datasets: [{
                label: 'ব্লগ পোস্ট সংখ্যা',
                data: postsValues,
                borderColor: '#28a745',
                backgroundColor: postsGradient,
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: '#ffc107', // Gold point
                pointHoverRadius: 6,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(148, 163, 184, 0.1)' }
                }
            }
        }
    });

    // 2. Join Requests Trend Chart
    var joinTrendData = @json($monthlyJoinRequests);
    var joinLabels = joinTrendData.map(function(item) { return item.month; });
    var joinValues = joinTrendData.map(function(item) { return item.count; });

    var joinCtx = document.getElementById('joinRequestsChart').getContext('2d');
    var joinGradient = joinCtx.createLinearGradient(0, 0, 0, 200);
    joinGradient.addColorStop(0, 'rgba(220, 53, 69, 0.4)'); // Red gradient
    joinGradient.addColorStop(1, 'rgba(220, 53, 69, 0.0)');

    new Chart(joinCtx, {
        type: 'bar',
        data: {
            labels: joinLabels,
            datasets: [{
                label: 'নতুন আবেদন',
                data: joinValues,
                borderColor: '#dc3545',
                backgroundColor: joinGradient,
                borderWidth: 1.5,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(148, 163, 184, 0.1)' }
                }
            }
        }
    });


        // 3. Visitor Unique Monthly Trend Chart
    var visitorTrendData = @json($monthlyUniqueVisitors);
    var visitorLabels = visitorTrendData.map(function(item) { return item.month; });
    var visitorValues = visitorTrendData.map(function(item) { return item.count; });

    var visitorCtx = document.getElementById('visitorTrendChart').getContext('2d');
    var visitorGradient = visitorCtx.createLinearGradient(0, 0, 0, 200);
    visitorGradient.addColorStop(0, 'rgba(0, 123, 255, 0.3)'); // primary blue
    visitorGradient.addColorStop(1, 'rgba(0, 123, 255, 0.0)');

    new Chart(visitorCtx, {
        type: 'line',
        data: {
            labels: visitorLabels,
            datasets: [{
                label: 'ইউনিক ভিজিটর সংখ্যা',
                data: visitorValues,
                borderColor: '#007bff',
                backgroundColor: visitorGradient,
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: '#ffc107', // Gold point
                pointHoverRadius: 6,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(148, 163, 184, 0.1)' }
                }
            }
        }
    });

    // 4. Device Share Donut Chart
    var deviceBreakdownData = @json($deviceBreakdown);
    var deviceLabels = Object.keys(deviceBreakdownData);
    var deviceValues = Object.values(deviceBreakdownData);

    // If empty fallback
    if (deviceValues.length === 0) {
        deviceLabels = ['Desktop', 'Mobile', 'Tablet'];
        deviceValues = [0, 0, 0];
    }

    var deviceCtx = document.getElementById('deviceShareChart').getContext('2d');
    new Chart(deviceCtx, {
        type: 'doughnut',
        data: {
            labels: deviceLabels,
            datasets: [{
                data: deviceValues,
                backgroundColor: ['#007bff', '#28a745', '#ffc107'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12 }
                }
            }
        }
    });});
</script>
@endpush
