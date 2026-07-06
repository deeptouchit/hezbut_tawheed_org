@extends('admin.layouts.master')

@section('page-title', 'Dashboard')

@push('styles')
<style>
    /* Premium Dashboard Styles */
    .welcome-card {
        background: linear-gradient(135deg, #004D38 0%, #006A4E 100%);
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 30px -5px rgba(0, 106, 78, 0.2);
        position: relative;
        overflow: hidden;
    }
    .welcome-card::before {
        content: "";
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(212, 175, 55, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .metric-card {
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .border-left-primary { border-left: 4px solid #006A4E !important; }
    .border-left-success { border-left: 4px solid #2e7d32 !important; }
    .border-left-info { border-left: 4px solid #0288d1 !important; }
    .border-left-warning { border-left: 4px solid #f57c00 !important; }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }
    .dashboard-card {
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .text-gold {
        color: #D4AF37 !important;
    }
    .bg-dark-green {
        background-color: #006A4E !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Welcome Area -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card text-white">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2"><i class="fas fa-landmark text-gold me-2"></i>Welcome, {{ auth()->user()->name }}!</h2>
                            <p class="mb-0 opacity-75">Here is the Hezbut Tawheed Portal administration dashboard overview.</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-inline-block bg-white bg-opacity-25 rounded-3 px-4 py-2">
                                <i class="fas fa-calendar-alt me-2 text-gold"></i>
                                {{ now()->format('d M, Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Grid -->
    <div class="row mb-4">
        <!-- Total Blog Posts -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card metric-card border-left-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-sm d-block mb-1">Total Blog Posts</span>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['total_posts']) }}</h3>
                            <small class="text-success"><i class="fas fa-file-alt"></i> Articles & News</small>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-blog"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Comments -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card metric-card border-left-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-sm d-block mb-1">Total Comments</span>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['total_comments']) }}</h3>
                            <small class="text-warning">
                                <i class="fas fa-hourglass-half"></i> {{ $stats['pending_comments'] }} pending approval
                            </small>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-comments"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Subscribers -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card metric-card border-left-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-sm d-block mb-1">Newsletter Subscribers</span>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['total_subscribers']) }}</h3>
                            <small class="text-info"><i class="fas fa-users"></i> Active emails</small>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Messages -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card metric-card border-left-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-sm d-block mb-1">Contact Messages</span>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['total_messages']) }}</h3>
                            <small class="text-danger">
                                <i class="fas fa-envelope"></i> {{ $stats['unread_messages'] }} unread inbox
                            </small>
                        </div>
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="row mb-4">
        <!-- Team Members -->
        <div class="col-md-3 col-sm-6">
            <div class="info-box shadow-sm">
                <span class="info-box-icon text-bg-primary shadow-sm bg-dark-green text-white">
                    <i class="fas fa-user-tie"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Team Members</span>
                    <span class="info-box-number">{{ $stats['total_team'] }}</span>
                </div>
            </div>
        </div>

        <!-- Blog Categories -->
        <div class="col-md-3 col-sm-6">
            <div class="info-box shadow-sm">
                <span class="info-box-icon text-bg-success shadow-sm">
                    <i class="fas fa-folder-open"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Blog Categories</span>
                    <span class="info-box-number">{{ $stats['total_categories'] }}</span>
                </div>
            </div>
        </div>

        <!-- Active Administrators -->
        <div class="col-md-3 col-sm-6">
            <div class="info-box shadow-sm">
                <span class="info-box-icon text-bg-info shadow-sm">
                    <i class="fas fa-users-cog"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Admin Users</span>
                    <span class="info-box-number">{{ $stats['total_users'] }}</span>
                </div>
            </div>
        </div>

        <!-- Logged-in Visitors -->
        <div class="col-md-3 col-sm-6">
            <div class="info-box shadow-sm">
                <span class="info-box-icon text-bg-warning shadow-sm">
                    <i class="fas fa-user-check"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Today Visitors</span>
                    <span class="info-box-number">{{ $stats['today_visitors'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Blog posting trend -->
        <div class="col-md-8">
            <div class="card dashboard-card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-line text-success me-2"></i>Blog Posting Trends
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="postsChart" height="280"></canvas>
                </div>
            </div>
        </div>

        <!-- Comment Distribution -->
        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-comments text-success me-2"></i>Comments Breakdown
                    </h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <div style="height: 180px; width: 100%; display: flex; justify-content: center; align-items: center;">
                        <canvas id="commentsBreakdownChart"></canvas>
                    </div>
                    <div class="mt-4">
                        @php
                            $totalComments = max($stats['total_comments'], 1);
                            $pendingPercent = round(($stats['pending_comments'] / $totalComments) * 100, 1);
                            $approvedPercent = round(($stats['approved_comments'] / $totalComments) * 100, 1);
                        @endphp
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="d-inline-block rounded-circle bg-success me-2" style="width: 10px; height: 10px;"></span>
                                Approved
                            </div>
                            <span class="small text-muted">{{ $stats['approved_comments'] }} ({{ $approvedPercent }}%)</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="d-inline-block rounded-circle bg-warning me-2" style="width: 10px; height: 10px;"></span>
                                Pending Approval
                            </div>
                            <span class="small text-muted">{{ $stats['pending_comments'] }} ({{ $pendingPercent }}%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lists Row -->
    <div class="row">
        <!-- Recent Blog Posts -->
        <div class="col-md-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-newspaper text-success me-2"></i>Recent Blog Posts
                    </h5>
                    <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-xs btn-outline-success">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th class="text-center">Views</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPosts as $post)
                                <tr>
                                    <td><span class="fw-semibold text-dark">{{ Str::limit($post['title'], 35) }}</span></td>
                                    <td><span class="badge bg-secondary">{{ $post['category'] }}</span></td>
                                    <td class="text-center">{{ number_format($post['views']) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $post['status'] === 'published' ? 'success' : 'warning' }}">
                                            {{ ucfirst($post['status']) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted">No posts found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Contact Messages -->
        <div class="col-md-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-inbox text-success me-2"></i>Recent Messages
                    </h5>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-xs btn-outline-success">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Sender</th>
                                    <th>Subject</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Received</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentMessages as $msg)
                                <tr>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ Str::limit($msg['name'], 20) }}</span>
                                        <small class="text-muted d-block">{{ $msg['email'] }}</small>
                                    </td>
                                    <td>{{ Str::limit($msg['subject'], 30) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $msg['is_read'] ? 'success' : 'danger' }}">
                                            {{ $msg['is_read'] ? 'Read' : 'New' }}
                                        </span>
                                    </td>
                                    <td class="text-center text-muted"><small>{{ $msg['created_at'] }}</small></td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted">No messages found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
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
    // 1. Posts Trend Chart
    var postsTrendData = @json($monthlyPosts);
    var postsLabels = postsTrendData.map(function(item) { return item.month; });
    var postsValues = postsTrendData.map(function(item) { return item.count; });

    var postsCtx = document.getElementById('postsChart').getContext('2d');
    var postsGradient = postsCtx.createLinearGradient(0, 0, 0, 250);
    postsGradient.addColorStop(0, 'rgba(0, 106, 78, 0.3)'); // deep green
    postsGradient.addColorStop(1, 'rgba(0, 106, 78, 0.0)');

    new Chart(postsCtx, {
        type: 'line',
        data: {
            labels: postsLabels,
            datasets: [{
                label: 'Posts Published',
                data: postsValues,
                borderColor: '#006A4E',
                backgroundColor: postsGradient,
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: '#D4AF37', // Gold point
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

    // 2. Comments Breakdown Donut Chart
    var approvedComments = {{ $stats['approved_comments'] }};
    var pendingComments = {{ $stats['pending_comments'] }};

    var commentsCtx = document.getElementById('commentsBreakdownChart').getContext('2d');
    new Chart(commentsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Approved', 'Pending Approval'],
            datasets: [{
                data: [approvedComments, pendingComments],
                backgroundColor: ['#2e7d32', '#f57c00'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
@endpush
