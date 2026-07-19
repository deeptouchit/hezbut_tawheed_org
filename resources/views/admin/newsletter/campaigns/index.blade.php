@extends('admin.layouts.master')

@section('page-title', 'নিউজলেটার ক্যাম্পেইন')

@push('styles')
<style>
    .metric-card {
        border-radius: 8px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 10px -3px rgba(0, 0, 0, 0.08);
    }
    .metric-card .card-body {
        padding: 10px 14px !important;
    }
    .border-left-primary { border-left: 3px solid #006A4E !important; }
    .border-left-success { border-left: 3px solid #2e7d32 !important; }
    .border-left-info { border-left: 3px solid #0288d1 !important; }
    .border-left-warning { border-left: 4px solid #f57c00 !important; }
    .border-left-danger { border-left: 4px solid #d32f2f !important; }
    .border-left-secondary { border-left: 4px solid #757575 !important; }

    .stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
</style>
@endpush

@section('filter_input')
<div class="row px-3">
    <div class="col-md-4">
        <input type="text" id="search-input" class="form-control" placeholder="ক্যাম্পেইন খুঁজুন..." autocomplete="off">
    </div>
    <div class="col-md-2">
        <select id="status-filter" class="form-control">
            <option value="">সব স্ট্যাটাস</option>
            <option value="draft">ড্রাফট</option>
            <option value="scheduled">শিডিউলড</option>
            <option value="sent">সেন্ট</option>
            <option value="failed">ফেইলড</option>
        </select>
    </div>
    <div class="col-md-2">
        <select id="per-page-filter" class="form-control">
            <option value="10">১০টি দেখান</option>
            <option value="20" selected>২০টি দেখান</option>
            <option value="30">৩০টি দেখান</option>
            <option value="50">৫০টি দেখান</option>
            <option value="100">১০০টি দেখান</option>
            <option value="all">সব দেখান</option>
        </select>
    </div>
    <div class="col-md-2">
        <button id="reset-filter" class="btn btn-secondary w-100">
            <i class="fas fa-sync-alt"></i> রিসেট
        </button>
    </div>
    <div class="col-md-2">
        <a href="{{ route('admin.newsletter.campaigns.create') }}" class="btn btn-primary w-100">
            <i class="fas fa-plus"></i> নতুন ক্যাম্পেইন
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row g-2 mb-3">
        <div class="col-md-3 col-6">
            <div class="card metric-card border-left-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">মোট ক্যাম্পেইন</span>
                            <h5 class="mb-0 fw-bold metric-number">{{ number_format($stats['total']) }}</h5>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card metric-card border-left-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">সেন্ট</span>
                            <h5 class="mb-0 fw-bold metric-number">{{ number_format($stats['sent']) }}</h5>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card metric-card border-left-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">শিডিউলড</span>
                            <h5 class="mb-0 fw-bold metric-number">{{ number_format($stats['scheduled']) }}</h5>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card metric-card border-left-secondary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">ড্রাফট</span>
                            <h5 class="mb-0 fw-bold metric-number">{{ number_format($stats['draft']) }}</h5>
                        </div>
                        <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                            <i class="fas fa-pen"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list mr-1"></i> ক্যাম্পেইন তালিকা
            </h3>
        </div>

        <div class="card-body p-0">
            <div id="loading-spinner" class="text-center py-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">ডাটা লোড হচ্ছে...</p>
            </div>

            <div id="campaigns-table-container">
                @include('admin.newsletter.campaigns.partials.table', ['campaigns' => $campaigns])
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let searchTimeout;
    let isAjaxLoading = false;

    function loadCampaigns(page = 1) {
        if (isAjaxLoading) return;

        var search = $('#search-input').val();
        var status = $('#status-filter').val();
        var perPage = $('#per-page-filter').val();

        isAjaxLoading = true;
        $('#loading-spinner').show();
        $('#campaigns-table-container').hide();

        $.ajax({
            url: "{{ route('admin.newsletter.campaigns.index') }}",
            type: "GET",
            data: {
                search: search,
                status: status,
                per_page: perPage,
                page: page,
                _: Date.now()
            },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.html) {
                    $('#campaigns-table-container').html(response.html);

                    if (response.stats) {
                        $('.metric-number').eq(0).text(response.stats.total);
                        $('.metric-number').eq(1).text(response.stats.sent);
                        $('.metric-number').eq(2).text(response.stats.scheduled);
                        $('.metric-number').eq(3).text(response.stats.draft);
                    }

                    attachEventHandlers();
                }
                $('#loading-spinner').hide();
                $('#campaigns-table-container').show();
            },
            error: function() {
                toastr.error('ডাটা লোড করতে ব্যর্থ হয়েছে');
                $('#loading-spinner').hide();
                $('#campaigns-table-container').show();
            },
            complete: function() {
                isAjaxLoading = false;
            }
        });
    }

    function attachEventHandlers() {
        // Delete campaign
        $('.delete-campaign').off('click').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var row = $(this).closest('tr');

            Swal.fire({
                title: 'ক্যাম্পেইন মুছুন?',
                text: name + ' ক্যাম্পেইনটি মুছে ফেলতে যাচ্ছেন!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'হ্যাঁ, মুছুন',
                cancelButtonText: 'বাতিল'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("admin/newsletter-campaigns") }}/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                row.fadeOut(300, function() { $(this).remove(); });
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('মুছে ফেলতে ব্যর্থ হয়েছে');
                        }
                    });
                }
            });
        });

        // Send campaign
        $('.send-campaign').off('click').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');

            Swal.fire({
                title: 'ক্যাম্পেইন পাঠান?',
                text: name + ' ক্যাম্পেইনটি এখনই পাঠাতে যাচ্ছেন!',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'হ্যাঁ, পাঠান',
                cancelButtonText: 'বাতিল'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("admin/newsletter-campaigns") }}/' + id + '/send',
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'পাঠানো হচ্ছে...',
                                allowOutsideClick: false,
                                didOpen: () => Swal.showLoading()
                            });
                        },
                        success: function(response) {
                            Swal.close();
                            if (response.success) {
                                toastr.success(response.message);
                                loadCampaigns();
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            Swal.close();
                            toastr.error('পাঠাতে ব্যর্থ হয়েছে');
                        }
                    });
                }
            });
        });

        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').match(/page=(\d+)/) ? $(this).attr('href').match(/page=(\d+)/)[1] : 1;
            if (page) loadCampaigns(page);
        });
    }

    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadCampaigns(), 500);
    });

    $('#status-filter, #per-page-filter').on('change', function() {
        loadCampaigns();
    });

    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#status-filter').val('');
        $('#per-page-filter').val('20');
        loadCampaigns();
    });

    attachEventHandlers();
});
</script>
@endpush
