@extends('admin.layouts.master')

@section('page-title', 'Activity Logs')

@push('styles')
<style>
    .stats-card {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .log-table tr {
        transition: background 0.2s;
    }
    .log-table tr:hover {
        background: #f8f9fa;
    }
</style>
@endpush
@section('filter_input')
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="search-input" class="form-control" placeholder="Search logs..." autocomplete="off" value="{{ request('search', $search ?? '') }}">
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <select id="action-filter" class="form-select">
            <option value="">All Actions</option>
            @foreach($actions as $act)
                <option value="{{ $act }}" {{ (request('action', $action ?? '')) == $act ? 'selected' : '' }}>
                    {{ ucfirst($act) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <select id="module-filter" class="form-select">
            <option value="">All Modules</option>
            @foreach($modules as $mod)
                <option value="{{ $mod }}" {{ (request('module', $module ?? '')) == $mod ? 'selected' : '' }}>
                    {{ ucfirst($mod) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2 mb-3">
        <select id="per-page-filter" class="form-select">
            <option value="10" {{ (request('per_page', $perPage ?? 20)) == 10 ? 'selected' : '' }}>10 items</option>
            <option value="20" {{ (request('per_page', $perPage ?? 20)) == 20 ? 'selected' : '' }}>20 items</option>
            <option value="30" {{ (request('per_page', $perPage ?? 20)) == 30 ? 'selected' : '' }}>30 items</option>
            <option value="50" {{ (request('per_page', $perPage ?? 20)) == 50 ? 'selected' : '' }}>50 items</option>
            <option value="100" {{ (request('per_page', $perPage ?? 20)) == 100 ? 'selected' : '' }}>100 items</option>
            <option value="-1" {{ (request('per_page', $perPage ?? 20)) == '-1' ? 'selected' : '' }}>All items</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <select id="user-filter" class="form-select">
            <option value="">All Users</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ (request('user_id', $userId ?? '')) == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2 mb-3">
        <input type="date" id="date-from" class="form-control" value="{{ request('date_from', $dateFrom ?? '') }}" placeholder="Date From">
    </div>
    <div class="col-md-2 mb-3">
        <input type="date" id="date-to" class="form-control" value="{{ request('date_to', $dateTo ?? '') }}" placeholder="Date To">
    </div>
    <div class="col-md-5 mb-3">
        <div class="btn-group w-100">
            <button id="reset-filter" class="btn btn-secondary">
                <i class="fas fa-sync-alt"></i> Reset
            </button>
            <button type="button" class="btn btn-danger" id="clearAllLogsBtn">
                <i class="fas fa-trash-alt"></i> Clear All
            </button>
            <button type="button" class="btn btn-success" id="exportLogsBtn">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
  <!-- Statistics Cards - Info Box Style -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-database"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Logs</span>
                <span class="info-box-number" id="totalLogs">{{ $stats['total'] ?? 0 }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-calendar-day"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Today</span>
                <span class="info-box-number" id="todayLogs">{{ $stats['today'] ?? 0 }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-calendar-week"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">This Week</span>
                <span class="info-box-number" id="weekLogs">{{ $stats['week'] ?? 0 }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fas fa-calendar-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">This Month</span>
                <span class="info-box-number" id="monthLogs">{{ $stats['month'] ?? 0 }}</span>
            </div>
        </div>
    </div>
</div>
    <!-- Chart Row -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Last 7 Days Activity</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="activityChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-history mr-1"></i>
                Activity Logs
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body p-1">
            <div id="loading-spinner" class="text-center py-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading logs...</p>
            </div>

            <div id="logs-table-container">
                @include('admin.activity-logs.partials.table', ['logs' => $logs])
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-download mr-1"></i> Export Logs
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label>Export Format</label>
                    <select id="exportFormat" class="form-control">
                        <option value="csv">CSV Format</option>
                        <option value="json">JSON Format</option>
                    </select>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Exports data based on current filters.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmExport">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    let searchTimeout;
    let isAjaxLoading = false;
    let activityChart = null;

    // Initialize chart
    function initChart() {
        var ctx = document.getElementById('activityChart').getContext('2d');
        activityChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Activities',
                    data: [],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });
    }

    // Load statistics and chart
    function loadStats() {
        $.ajax({
            url: '{{ route("admin.activity-logs.statistics") }}',
            type: 'GET',
            success: function(response) {
                if (response.success && response.data) {
                    $('#totalLogs').text(response.data.total);
                    $('#todayLogs').text(response.data.today);
                    $('#weekLogs').text(response.data.week);
                    $('#monthLogs').text(response.data.month);

                    // Update chart
                    if (response.data.last_7_days && activityChart) {
                        activityChart.data.labels = response.data.last_7_days.map(item => item.date);
                        activityChart.data.datasets[0].data = response.data.last_7_days.map(item => item.count);
                        activityChart.update();
                    }
                }
            }
        });
    }

    // Load logs
    function loadLogs(page = 1) {
        if (isAjaxLoading) return;

        var search = $('#search-input').val();
        var action = $('#action-filter').val();
        var module = $('#module-filter').val();
        var userId = $('#user-filter').val();
        var dateFrom = $('#date-from').val();
        var dateTo = $('#date-to').val();
        var perPage = $('#per-page-filter').val();

        isAjaxLoading = true;
        $('#loading-spinner').show();
        $('#logs-table-container').hide();

        $.ajax({
            url: "{{ route('admin.activity-logs.index') }}",
            type: "GET",
            data: {
                search: search,
                action: action,
                module: module,
                user_id: userId,
                date_from: dateFrom,
                date_to: dateTo,
                per_page: perPage,
                page: page,
                _: Date.now()
            },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.html) {
                    $('#logs-table-container').html(response.html);
                    attachEventHandlers();
                }
                $('#loading-spinner').hide();
                $('#logs-table-container').show();
            },
            error: function() {
                toastr.error('Failed to load logs');
                $('#loading-spinner').hide();
                $('#logs-table-container').show();
            },
            complete: function() {
                isAjaxLoading = false;
            }
        });
    }

    // Attach event handlers
    function attachEventHandlers() {
        $('.delete-log').off('click').on('click', function() {
            var id = $(this).data('id');

            Swal.fire({
                title: 'Delete Log?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("admin/activity-logs") }}/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                loadLogs();
                                loadStats();
                            } else {
                                toastr.error(response.message);
                            }
                        }
                    });
                }
            });
        });

        $('.view-log').off('click').on('click', function() {
            var id = $(this).data('id');
            window.location.href = '{{ url("admin/activity-logs") }}/' + id;
        });

        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').match(/page=(\d+)/) ? $(this).attr('href').match(/page=(\d+)/)[1] : 1;
            if (page) loadLogs(page);
        });
    }

    // Clear all logs
    $('#clearAllLogsBtn').on('click', function() {
        Swal.fire({
            title: 'Clear All Logs?',
            html: 'This will delete <strong>ALL</strong> activity logs permanently!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, clear all',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.activity-logs.clear") }}',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            loadLogs();
                            loadStats();
                        } else {
                            toastr.error(response.message);
                        }
                    }
                });
            }
        });
    });

    // Export logs
    $('#exportLogsBtn').on('click', function() {
        $('#exportModal').modal('show');
    });

    $('#confirmExport').on('click', function() {
        var format = $('#exportFormat').val();
        var params = new URLSearchParams({
            format: format,
            search: $('#search-input').val(),
            action: $('#action-filter').val(),
            module: $('#module-filter').val(),
            user_id: $('#user-filter').val(),
            date_from: $('#date-from').val(),
            date_to: $('#date-to').val()
        });

        window.location.href = '{{ route("admin.activity-logs.export") }}?' + params.toString();
        $('#exportModal').modal('hide');
    });

    // Filter handlers
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadLogs(), 500);
    });

    $('#action-filter, #module-filter, #user-filter, #date-from, #date-to, #per-page-filter').on('change', function() {
        loadLogs();
    });

    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#action-filter').val('');
        $('#module-filter').val('');
        $('#user-filter').val('');
        $('#date-from').val('');
        $('#date-to').val('');
        $('#per-page-filter').val('20');
        loadLogs();
    });

    // Initialize
    initChart();
    loadStats();
    attachEventHandlers();
});
</script>
@endpush
