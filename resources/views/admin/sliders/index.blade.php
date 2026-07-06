@extends('admin.layouts.master')

@section('page-title', 'স্লাইডার ম্যানেজমেন্ট')

@section('filter_input')
<div class="row g-2 px-3 align-items-center py-2 bg-white rounded shadow-sm border mx-1 mb-3">
    <div class="col-md-3 mb-2 mb-md-0">
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
            <input type="text" id="search-input" class="form-control bg-light border-start-0" placeholder="স্লাইডার খুঁজুন..." autocomplete="off" style="font-size: 13.5px;">
        </div>
    </div>
    <div class="col-md-2 mb-2 mb-md-0">
        <select id="status-filter" class="form-control form-select bg-light" style="font-size: 13.5px;">
            <option value="">সব স্ট্যাটাস</option>
            <option value="1">সক্রিয়</option>
            <option value="0">নিষ্ক্রিয়</option>
        </select>
    </div>
    <div class="col-md-2 mb-2 mb-md-0">
        <select id="position-filter" class="form-control form-select bg-light" style="font-size: 13.5px;">
            <option value="">সব পজিশন</option>
            <option value="homepage">হোমপেজ</option>
            <option value="banner">ব্যানার</option>
            <option value="popup">পপআপ</option>
        </select>
    </div>
    <div class="col-md-3 mb-2 mb-md-0">
        <select id="per-page-filter" class="form-control form-select bg-light" style="font-size: 13.5px;">
            <option value="10">১০টি দেখান</option>
            <option value="20" selected>২০টি দেখান</option>
            <option value="30">৩০টি দেখান</option>
            <option value="50">৫০টি দেখান</option>
            <option value="100">১০০টি দেখান</option>
            <option value="all">সব দেখান</option>
        </select>
    </div>
    <div class="col-md-2">
        <button id="reset-filter" class="btn btn-outline-secondary w-100" style="font-size: 13.5px;">
            <i class="fas fa-sync-alt mr-1"></i> রিসেট ফিল্টার
        </button>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-sm-6 col-12 mb-3 mb-lg-0">
            <div class="info-box shadow-sm border" style="border-radius: 8px; background: #ffffff;">
                <span class="info-box-icon text-primary rounded" style="background: rgba(0, 123, 255, 0.08); width: 55px; height: 55px;"><i class="fas fa-images"></i></span>
                <div class="info-box-content ps-3">
                    <span class="info-box-text text-muted font-weight-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">মোট স্লাইডার</span>
                    <h4 class="info-box-number mb-0 font-weight-bold text-dark" style="font-family: 'Baloo Da 2', sans-serif;">{{ $stats['total'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 col-12 mb-3 mb-lg-0">
            <div class="info-box shadow-sm border" style="border-radius: 8px; background: #ffffff;">
                <span class="info-box-icon text-success rounded" style="background: rgba(40, 167, 69, 0.08); width: 55px; height: 55px;"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content ps-3">
                    <span class="info-box-text text-muted font-weight-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">সক্রিয় স্লাইডার</span>
                    <h4 class="info-box-number mb-0 font-weight-bold text-dark" style="font-family: 'Baloo Da 2', sans-serif;">{{ $stats['active'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 col-12">
            <div class="info-box shadow-sm border" style="border-radius: 8px; background: #ffffff;">
                <span class="info-box-icon text-danger rounded" style="background: rgba(220, 53, 69, 0.08); width: 55px; height: 55px;"><i class="fas fa-times-circle"></i></span>
                <div class="info-box-content ps-3">
                    <span class="info-box-text text-muted font-weight-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">নিষ্ক্রিয় স্লাইডার</span>
                    <h4 class="info-box-number mb-0 font-weight-bold text-dark" style="font-family: 'Baloo Da 2', sans-serif;">{{ $stats['inactive'] }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 8px; overflow: hidden;">
        <div class="card-header bg-white border-bottom py-3">
            <h3 class="card-title font-weight-bold text-dark mb-0">
                <i class="fas fa-images text-primary mr-2"></i> স্লাইডার তালিকা
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary btn-sm" style="border-radius: 4px;">
                    <i class="fas fa-plus mr-1"></i> নতুন স্লাইডার
                </a>
            </div>
        </div>

        <div class="card-body p-0 bg-white">
            <div id="loading-spinner" class="text-center py-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">ডাটা লোড হচ্ছে...</p>
            </div>

            <div id="sliders-table-container">
                @include('admin.sliders.partials.table', ['sliders' => $sliders])
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

    function loadSliders(page = 1) {
        if (isAjaxLoading) return;

        var search = $('#search-input').val();
        var status = $('#status-filter').val();
        var position = $('#position-filter').val();
        var perPage = $('#per-page-filter').val();

        isAjaxLoading = true;
        $('#loading-spinner').show();
        $('#sliders-table-container').hide();

        $.ajax({
            url: "{{ route('admin.sliders.index') }}",
            type: "GET",
            data: {
                search: search,
                status: status,
                position: position,
                per_page: perPage,
                page: page,
                _: Date.now()
            },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.html) {
                    $('#sliders-table-container').html(response.html);

                    if (response.stats) {
                        $('.info-box-number').eq(0).text(response.stats.total);
                        $('.info-box-number').eq(1).text(response.stats.active);
                        $('.info-box-number').eq(2).text(response.stats.inactive);
                    }

                    attachEventHandlers();
                }
                $('#loading-spinner').hide();
                $('#sliders-table-container').show();
            },
            error: function() {
                toastr.error('ডাটা লোড করতে ব্যর্থ হয়েছে');
                $('#loading-spinner').hide();
                $('#sliders-table-container').show();
            },
            complete: function() {
                isAjaxLoading = false;
            }
        });
    }

    function attachEventHandlers() {
        // Toggle status
        $('.toggle-status').off('click').on('click', function() {
            var id = $(this).data('id');
            var btn = $(this);

            $.ajax({
                url: '{{ url("admin/sliders") }}/' + id + '/toggle-status',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        if (response.status) {
                            btn.removeClass('btn-danger').addClass('btn-success');
                            btn.html('<i class="fas fa-check-circle"></i> সক্রিয়');
                        } else {
                            btn.removeClass('btn-success').addClass('btn-danger');
                            btn.html('<i class="fas fa-times-circle"></i> নিষ্ক্রিয়');
                        }
                        toastr.success(response.message);
                    }
                },
                error: function() {
                    toastr.error('স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে');
                }
            });
        });

        // Delete slider
        $('.delete-slider').off('click').on('click', function() {
            var id = $(this).data('id');
            var title = $(this).data('title');
            var row = $(this).closest('tr');

            Swal.fire({
                title: 'স্লাইডার মুছুন?',
                text: title + ' স্লাইডারটি মুছে ফেলতে যাচ্ছেন!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'হ্যাঁ, মুছুন',
                cancelButtonText: 'বাতিল'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("admin/sliders") }}/' + id,
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

        // Update sort order
        $('.update-order').off('click').on('click', function() {
            var btn = $(this);
            var id = btn.data('id');
            var row = btn.closest('tr');
            var sortOrder = row.find('.sort-order-input').val();

            var orders = [{ id: id, sort_order: sortOrder }];

            btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

            $.ajax({
                url: '{{ route("admin.sliders.update-order") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    orders: orders
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                    btn.html('<i class="fas fa-save"></i>').prop('disabled', false);
                },
                error: function() {
                    toastr.error('ক্রম আপডেট করতে ব্যর্থ হয়েছে');
                    btn.html('<i class="fas fa-save"></i>').prop('disabled', false);
                }
            });
        });

        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').match(/page=(\d+)/) ? $(this).attr('href').match(/page=(\d+)/)[1] : 1;
            if (page) loadSliders(page);
        });
    }

    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadSliders(), 500);
    });

    $('#status-filter, #position-filter, #per-page-filter').on('change', function() {
        loadSliders();
    });

    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#status-filter').val('');
        $('#position-filter').val('');
        $('#per-page-filter').val('20');
        loadSliders();
    });

    attachEventHandlers();
});
</script>
@endpush
