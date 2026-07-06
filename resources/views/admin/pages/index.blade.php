@extends('admin.layouts.master')

@section('page-title', 'পেজ ম্যানেজমেন্ট')

@section('filter_input')
<div class="row px-3">
    <div class="col-md-6 mb-3">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="search-input" class="form-control" placeholder="শিরোনাম, স্ল্যাগ বা কনটেন্ট..." autocomplete="off">
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <select id="status-filter" class="form-select">
            <option value="">সব স্ট্যাটাস</option>
            <option value="active">সক্রিয়</option>
            <option value="inactive">নিষ্ক্রিয়</option>
        </select>
    </div>
    <div class="col-md-2 mb-3">
        <select id="per-page-filter" class="form-select">
            <option value="10">১০</option>
            <option value="20" selected>২০</option>
            <option value="50">৫০</option>
            <option value="100">১০০</option>
            <option value="-1">সব</option>
        </select>
    </div>
    <div class="col-md-2 mb-3">
        <button id="reset-filter" class="btn btn-secondary w-100">
            <i class="fas fa-undo-alt"></i> রিসেট
        </button>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-copy me-2"></i> পেজ তালিকা
                <span class="badge bg-primary ms-2" id="total-count">{{ $pages->total() ?? $pages->count() }}</span>
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> নতুন পেজ তৈরি
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <!-- Loading Spinner -->
            <div id="loading-spinner" class="text-center py-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">ডাটা লোড হচ্ছে...</p>
            </div>

            <!-- Stats -->
            <div class="row g-3 p-3 bg-light border-bottom">
                <div class="col-md-4 col-sm-6">
                    <div class="d-flex align-items-center bg-white p-3 rounded shadow-sm border-start border-3 border-info">
                        <div class="fs-2 text-info me-3"><i class="fas fa-file-invoice"></i></div>
                        <div>
                            <span class="text-muted small">মোট পেজ</span>
                            <h4 class="mb-0 fw-bold">{{ $stats['total'] }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="d-flex align-items-center bg-white p-3 rounded shadow-sm border-start border-3 border-success">
                        <div class="fs-2 text-success me-3"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <span class="text-muted small">সক্রিয়</span>
                            <h4 class="mb-0 fw-bold">{{ $stats['active'] }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="d-flex align-items-center bg-white p-3 rounded shadow-sm border-start border-3 border-danger">
                        <div class="fs-2 text-danger me-3"><i class="fas fa-times-circle"></i></div>
                        <div>
                            <span class="text-muted small">নিষ্ক্রিয়</span>
                            <h4 class="mb-0 fw-bold">{{ $stats['inactive'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div id="table-container">
                @include('admin.pages.partials.table')
            </div>
        </div>
    </div>
</div>

<!-- Single Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">পেজ ডিলিট কনফার্মেশন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>আপনি কি নিশ্চিত যে আপনি '<strong id="delete-item-name"></strong>' পেজটি ডিলিট করতে চান?</p>
                <p class="text-danger small mb-0"><i class="fas fa-exclamation-triangle"></i> এই কাজটি ফেরানো সম্ভব নয়!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">ডিলিট করুন</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var searchTimeout = null;

    // Load data ajax
    function loadPages(page = 1) {
        var search = $('#search-input').val();
        var status = $('#status-filter').val();
        var perPage = $('#per-page-filter').val();

        $('#loading-spinner').show();
        $('#table-container').hide();

        $.ajax({
            url: "{{ route('admin.pages.index') }}",
            type: 'GET',
            data: {
                page: page,
                search: search,
                status: status,
                per_page: perPage
            },
            success: function(response) {
                $('#loading-spinner').hide();
                $('#table-container').show();
                if (response.success) {
                    $('#table-container').html(response.html);
                    $('#total-count').text(response.total);
                    attachEventHandlers();
                } else {
                    toastr.error('ডাটা লোড করতে ব্যর্থ হয়েছে');
                }
            },
            error: function() {
                $('#loading-spinner').hide();
                $('#table-container').show();
                toastr.error('সার্ভারে সমস্যা হয়েছে');
            }
        });
    }

    // Attach AJAX event listeners after re-rendering table
    function attachEventHandlers() {
        // Toggle status
        $('.toggle-status').off('click').on('click', function() {
            var id = $(this).data('id');
            var btn = $(this);

            $.ajax({
                url: '{{ url("admin/pages") }}/' + id + '/toggle-status',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadPages();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে');
                }
            });
        });

        // Single delete
        $('.delete-page').off('click').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');

            $('#delete-item-name').text(name);
            $('#deleteModal').modal('show');

            $('#confirm-delete').off('click').on('click', function() {
                $.ajax({
                    url: '{{ url("admin/pages") }}/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        if (response.success) {
                            toastr.success(response.message);
                            loadPages();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(response) {
                        $('#deleteModal').modal('hide');
                        toastr.error('পেজ ডিলিট করতে ব্যর্থ হয়েছে');
                    }
                });
            });
        });

        // Pagination links
        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var page = url.match(/page=(\d+)/) ? url.match(/page=(\d+)/)[1] : 1;
            if (page) loadPages(page);
        });
    }

    // Keyup search
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadPages(), 500);
    });

    // Dropdowns
    $('#status-filter, #per-page-filter').on('change', function() {
        loadPages();
    });

    // Reset
    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#status-filter').val('');
        $('#per-page-filter').val('20');
        loadPages();
    });

    // Initial event attach
    attachEventHandlers();

    // Toastr setup
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };
});
</script>
@endpush
