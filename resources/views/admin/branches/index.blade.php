@extends('admin.layouts.master')

@section('page-title', 'কার্যালয় ও শাখা ব্যবস্থাপনা')

@section('filter_input')
<div class="row px-3">
    <div class="col-md-6 mb-3">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="search-input" class="form-control" placeholder="কার্যালয়ের নাম, ঠিকানা বা কর্মকর্তার নাম..." autocomplete="off">
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <select id="type-filter" class="form-select">
            <option value="">সব কার্যালয়</option>
            <option value="central">কেন্দ্রীয় কার্যালয়</option>
            <option value="division">বিভাগীয় কার্যালয়</option>
            <option value="district">জেলা কার্যালয়</option>
            <option value="upazila">উপজেলা কার্যালয়</option>
            <option value="international">আন্তর্জাতিক শাখা</option>
        </select>
    </div>
    <div class="col-md-2 mb-3">
        <select id="per-page-filter" class="form-select">
            <option value="10">১০</option>
            <option value="15" selected>১৫</option>
            <option value="30">৩০</option>
            <option value="50">৫০</option>
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
    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-light border-0 shadow-sm p-3 rounded-3 text-center animate-fade-in">
                <h6 class="text-muted fw-bold">মোট কার্যালয় ও শাখা</h6>
                <h3 class="fw-bold text-dark mb-0">{{ $stats['total'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white border-0 shadow-sm p-3 rounded-3 text-center animate-fade-in">
                <h6 class="fw-bold">কেন্দ্রীয় কার্যালয়</h6>
                <h3 class="fw-bold mb-0">{{ $stats['central'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white border-0 shadow-sm p-3 rounded-3 text-center animate-fade-in">
                <h6 class="fw-bold">জেলা কার্যালয়</h6>
                <h3 class="fw-bold mb-0">{{ $stats['district'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-purple text-white border-0 shadow-sm p-3 rounded-3 text-center animate-fade-in" style="background-color: #6f42c1 !important;">
                <h6 class="fw-bold">আন্তর্জাতিক শাখা</h6>
                <h3 class="fw-bold mb-0">{{ $stats['international'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-map-marked-alt me-2 text-primary"></i> কার্যালয় ও শাখা তালিকা
                <span class="badge bg-primary ms-2" id="total-count">{{ $branches->total() }}</span>
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.branches.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                    <i class="fas fa-plus"></i> নতুন কার্যালয় যোগ করুন
                </a>
            </div>
        </div>
        
        <div class="card-body p-0 position-relative">
            <div id="loading-spinner" class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center bg-white opacity-75 d-none" style="z-index: 100;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">লোড হচ্ছে...</span>
                </div>
            </div>

            <div id="table-container">
                @include('admin.branches.partials.table', ['branches' => $branches])
            </div>
        </div>
    </div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">কার্যালয় ডিলিট কনফার্মেশন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>আপনি কি নিশ্চিত যে আপনি '<strong id="delete-item-name"></strong>' কার্যালয়টি ডিলিট করতে চান?</p>
                <p class="text-danger small mb-0"><i class="fas fa-exclamation-triangle"></i> এই কার্যালয় ডিলিট হলে আর ফেরত আনা যাবে না!</p>
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
    
    // Load branches with AJAX
    function loadBranches(page = 1) {
        var search = $('#search-input').val();
        var type = $('#type-filter').val();
        var perPage = $('#per-page-filter').val();

        $('#loading-spinner').removeClass('d-none');
        $('#table-container').addClass('opacity-50');

        $.ajax({
            url: "{{ route('admin.branches.index') }}",
            type: 'GET',
            data: {
                page: page,
                search: search,
                type: type,
                per_page: perPage,
                ajax: 1
            },
            success: function(response) {
                if (response.success) {
                    $('#table-container').html(response.html);
                    $('#total-count').text(response.total);
                } else {
                    toastr.error('ডাটা লোড করতে ব্যর্থ হয়েছে!');
                }
            },
            error: function() {
                toastr.error('সার্ভারে সমস্যা হয়েছে!');
            },
            complete: function() {
                $('#loading-spinner').addClass('d-none');
                $('#table-container').removeClass('opacity-50');
            }
        });
    }

    // Keyup search
    var searchTimer;
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
            loadBranches(1);
        }, 350);
    });

    // Dropdown filters
    $('#type-filter, #per-page-filter').on('change', function() {
        loadBranches(1);
    });

    // Reset filter
    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#type-filter').val('');
        $('#per-page-filter').val('15');
        loadBranches(1);
    });

    // Pagination AJAX click handler
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        if (!href) return;
        var match = href.match(/page=(\d+)/);
        var page = match ? match[1] : 1;
        loadBranches(page);
    });

    // AJAX Toggle Status Switch
    $(document).on('change', '.toggle-status', function() {
        var checkbox = $(this);
        var id = checkbox.data('id');
        checkbox.prop('disabled', true);

        $.ajax({
            url: "{{ url('admin/branches') }}/" + id + "/toggle-status",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                checkbox.prop('disabled', false);
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    checkbox.prop('checked', !checkbox.is(':checked'));
                    toastr.error(response.message);
                }
            },
            error: function() {
                checkbox.prop('disabled', false);
                checkbox.prop('checked', !checkbox.is(':checked'));
                toastr.error('স্ট্যাটাস পরিবর্তন করতে সমস্যা হয়েছে!');
            }
        });
    });

    // Single delete
    $(document).on('click', '.delete-branch', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');

        $('#delete-item-name').text(name);
        $('#deleteModal').modal('show');

        $('#confirm-delete').off('click').on('click', function() {
            $.ajax({
                url: '{{ url("admin/branches") }}/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    if (response.success) {
                        toastr.success(response.message);
                        loadBranches(1); // Reload list
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    $('#deleteModal').modal('hide');
                    var msg = xhr.responseJSON ? xhr.responseJSON.message : 'কার্যালয় মুছতে ব্যর্থ হয়েছে!';
                    toastr.error(msg);
                }
            });
        });
    });
});
</script>
@endpush
