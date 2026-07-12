@extends('admin.layouts.master')

@section('page-title', 'নিউজলেটার টেমপ্লেট')

@section('filter_input')
<div class="row px-3">
    <div class="col-md-4">
        <input type="text" id="search-input" class="form-control" placeholder="টেমপ্লেট খুঁজুন..." autocomplete="off">
    </div>
    <div class="col-md-2">
        <select id="status-filter" class="form-control">
            <option value="">সব স্ট্যাটাস</option>
            <option value="1">সক্রিয়</option>
            <option value="0">নিষ্ক্রিয়</option>
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
        <a href="{{ route('admin.newsletter.templates.create') }}" class="btn btn-primary w-100">
            <i class="fas fa-plus"></i> নতুন টেমপ্লেট
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-file-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">মোট টেমপ্লেট</span>
                    <span class="info-box-number">{{ $stats['total'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">সক্রিয়</span>
                    <span class="info-box-number">{{ $stats['active'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-times-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">নিষ্ক্রিয়</span>
                    <span class="info-box-number">{{ $stats['inactive'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-star"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">ডিফল্ট</span>
                    <span class="info-box-number">{{ $stats['default'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list mr-1"></i> টেমপ্লেট তালিকা
            </h3>
        </div>

        <div class="card-body p-0">
            <div id="loading-spinner" class="text-center py-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">ডাটা লোড হচ্ছে...</p>
            </div>

            <div id="templates-table-container">
                @include('admin.newsletter.templates.partials.table', ['templates' => $templates])
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

    function loadTemplates(page = 1) {
        if (isAjaxLoading) return;

        var search = $('#search-input').val();
        var status = $('#status-filter').val();
        var perPage = $('#per-page-filter').val();

        isAjaxLoading = true;
        $('#loading-spinner').show();
        $('#templates-table-container').hide();

        $.ajax({
            url: "{{ route('admin.newsletter.templates.index') }}",
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
                    $('#templates-table-container').html(response.html);

                    if (response.stats) {
                        $('.info-box-number').eq(0).text(response.stats.total);
                        $('.info-box-number').eq(1).text(response.stats.active);
                        $('.info-box-number').eq(2).text(response.stats.inactive);
                        $('.info-box-number').eq(3).text(response.stats.default);
                    }

                    attachEventHandlers();
                }
                $('#loading-spinner').hide();
                $('#templates-table-container').show();
            },
            error: function() {
                toastr.error('ডাটা লোড করতে ব্যর্থ হয়েছে');
                $('#loading-spinner').hide();
                $('#templates-table-container').show();
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
                url: '{{ url("admin/newsletter-templates") }}/' + id + '/toggle-status',
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
                        loadTemplates();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে');
                }
            });
        });

        // Set default template
        $('.set-default').off('click').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var btn = $(this);

            Swal.fire({
                title: 'ডিফল্ট টেমপ্লেট সেট করুন?',
                text: name + ' টেমপ্লেটটি ডিফল্ট হিসেবে সেট করতে যাচ্ছেন!',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'হ্যাঁ, সেট করুন',
                cancelButtonText: 'বাতিল'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("admin/newsletter-templates") }}/' + id + '/set-default',
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                loadTemplates();
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('ডিফল্ট সেট করতে ব্যর্থ হয়েছে');
                        }
                    });
                }
            });
        });

        // Delete template
        $('.delete-template').off('click').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var row = $(this).closest('tr');

            Swal.fire({
                title: 'টেমপ্লেট মুছুন?',
                text: name + ' টেমপ্লেটটি মুছে ফেলতে যাচ্ছেন!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'হ্যাঁ, মুছুন',
                cancelButtonText: 'বাতিল'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("admin/newsletter-templates") }}/' + id,
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

        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').match(/page=(\d+)/) ? $(this).attr('href').match(/page=(\d+)/)[1] : 1;
            if (page) loadTemplates(page);
        });
    }

    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadTemplates(), 500);
    });

    $('#status-filter, #per-page-filter').on('change', function() {
        loadTemplates();
    });

    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#status-filter').val('');
        $('#per-page-filter').val('20');
        loadTemplates();
    });

    attachEventHandlers();
});
</script>
@endpush
