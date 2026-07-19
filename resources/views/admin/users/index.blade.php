@extends('admin.layouts.master')

@section('page-title', 'ইউজার তালিকা')

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
<!-- Filter Section -->
<div class="row px-3">
    <div class="col-md-3">
        <input type="text" id="search-input" class="form-control" placeholder="ইউজার খুঁজুন..." value="{{ request('search') }}" autocomplete="off">
    </div>
    <div class="col-md-3">
        <select id="role-filter" class="form-control">
            <option value="">সব রোল</option>
            @foreach($roles ?? [] as $role)
                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                    {{ ucfirst($role->name) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select id="status-filter" class="form-control">
            <option value="">সব স্ট্যাটাস</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>সক্রিয়</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>সাসপেন্ডেড</option>
        </select>
    </div>

    <div class="col-md-3">
        <select id="per-page-filter" class="form-control">
            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>১০টি দেখান</option>
            <option value="20" {{ request('per_page') == 20 ? 'selected' : (request('per_page') == '' ? 'selected' : '') }}>২০টি দেখান</option>
            <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>৩০টি দেখান</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>৫০টি দেখান</option>
            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>১০০টি দেখান</option>
            <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>২০০টি দেখান</option>
            <option value="-1" {{ request('per_page') == '-1' ? 'selected' : '' }}>সব</option>
        </select>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row g-2 mb-3">
        <div class="col-md-4 col-6">
            <div class="card metric-card border-left-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">মোট ইউজার</span>
                            <h5 class="mb-0 fw-bold metric-number">{{ number_format($stats['total']) }}</h5>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-users text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-6">
            <div class="card metric-card border-left-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">সক্রিয় ইউজার</span>
                            <h5 class="mb-0 fw-bold metric-number">{{ number_format($stats['active']) }}</h5>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-user-check text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="card metric-card border-left-secondary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block mb-0" style="font-size: 11px;">নিষ্ক্রিয় ইউজার</span>
                            <h5 class="mb-0 fw-bold metric-number">{{ number_format($stats['inactive']) }}</h5>
                        </div>
                        <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                            <i class="fas fa-user-slash text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-users me-2"></i> ইউজার তালিকা
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> নতুন ইউজার
                    </a>
                    <a href="{{ route('admin.users.export') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> এক্সপোর্ট
                    </a>
                    <button id="reset-filter" class="btn btn-secondary btn-sm">
                        <i class="fas fa-undo-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-1">

            <!-- Loading Spinner -->
            <div id="loading-spinner" class="text-center py-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">ডাটা লোড হচ্ছে...</p>
            </div>

            <!-- Users Table Container -->
            <div id="users-table-container">
                @include('admin.users.partials.table', ['users' => $users])
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Bulk Delete Form -->
<form id="bulk-delete-form" method="POST" action="{{ route('admin.users.bulk-delete') }}">
    @csrf
    @method('DELETE')
    <input type="hidden" name="user_ids" id="bulk-delete-ids">
</form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let searchTimeout;
    let isAjaxLoading = false;

    function loadUsers(page = 1) {
        if (isAjaxLoading) return;

        var search = $('#search-input').val();
        var role = $('#role-filter').val();
        var status = $('#status-filter').val();
        var emailVerified = $('#email-verified-filter').val();
        var perPage = $('#per-page-filter').val();

        isAjaxLoading = true;
        $('#loading-spinner').show();
        $('#users-table-container').hide();

        $.ajax({
            url: "{{ route('admin.users.index') }}",
            type: "GET",
            data: {
                search: search,
                role: role,
                status: status,
                email_verified: emailVerified,
                per_page: perPage,
                page: page,
                _: Date.now()
            },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.html) {
                    $('#users-table-container').html(response.html);
                    if (response.stats) {
                        $('.metric-number').eq(0).text(response.stats.total);
                        $('.metric-number').eq(1).text(response.stats.active);
                        $('.metric-number').eq(2).text(response.stats.inactive);
                    }
                    attachEventHandlers();
                } else {
                    console.error('Invalid response format');
                }
                $('#loading-spinner').hide();
                $('#users-table-container').show();
            },
            error: function(xhr) {
                console.log('AJAX Error:', xhr);
                if (typeof toastr !== 'undefined') {
                    toastr.error('ডাটা লোড করতে ব্যর্থ হয়েছে');
                }
                $('#loading-spinner').hide();
                $('#users-table-container').show();
            },
            complete: function() {
                isAjaxLoading = false;
            }
        });
    }

    function attachEventHandlers() {
        // Select All
        $('#selectAll').off('change').on('change', function() {
            $('.user-checkbox').prop('checked', $(this).prop('checked'));
            toggleBulkDeleteButton();
        });

        $('.user-checkbox').off('change').on('change', function() {
            toggleBulkDeleteButton();
        });

        function toggleBulkDeleteButton() {
            var checkedCount = $('.user-checkbox:checked').length;
            if (checkedCount > 0) {
                $('.bulk-delete').show();
            } else {
                $('.bulk-delete').hide();
            }
        }

        // Delete single user - AJAX Version
        $('.delete-user').off('click').on('click', function(e) {
            e.preventDefault();

            var id = $(this).data('id');
            var name = $(this).data('name');
            var row = $(this).closest('tr');
            var btn = $(this);

            Swal.fire({
                title: 'আপনি কি নিশ্চিত?',
                text: name + " ইউজারটি মুছে ফেলতে যাচ্ছেন! এই ইউজারের সকল ডাটা মুছে যাবে।",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'হ্যাঁ, মুছুন',
                cancelButtonText: 'বাতিল'
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

                    $.ajax({
                        url: '{{ url("admin/users") }}/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                row.fadeOut(300, function() {
                                    $(this).remove();
                                    if ($('tbody tr').length === 0) {
                                        loadUsers();
                                    }
                                });
                            } else {
                                toastr.error(response.message);
                                btn.prop('disabled', false).html('<i class="fas fa-trash"></i>');
                            }
                        },
                        error: function(xhr) {
                            let message = 'ইউজার মুছে ফেলতে ব্যর্থ হয়েছে।';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            toastr.error(message);
                            btn.prop('disabled', false).html('<i class="fas fa-trash"></i>');
                        }
                    });
                }
            });
        });

        // Bulk delete
        $('.bulk-delete').off('click').on('click', function() {
            var ids = [];
            $('.user-checkbox:checked').each(function() {
                ids.push($(this).val());
            });

            if (ids.length === 0) {
                toastr.warning('কোনো ইউজার নির্বাচন করা হয়নি');
                return;
            }

            Swal.fire({
                title: 'আপনি কি নিশ্চিত?',
                text: ids.length + " টি ইউজার মুছে ফেলতে যাচ্ছেন! এই ইউজারদের সকল ডাটা মুছে যাবে।",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'হ্যাঁ, সব মুছুন',
                cancelButtonText: 'বাতিল'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#bulk-delete-ids').val(JSON.stringify(ids));
                    $('#bulk-delete-form').submit();
                }
            });
        });

        // Toggle status
        $('.toggle-status').off('click').on('click', function() {
            var btn = $(this);
            var id = btn.data('id');
            var currentStatus = btn.data('status');

            Swal.fire({
                title: 'স্ট্যাটাস পরিবর্তন?',
                text: 'ইউজারের স্ট্যাটাস পরিবর্তন করতে চান?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'হ্যাঁ, পরিবর্তন করুন',
                cancelButtonText: 'বাতিল'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("admin/users") }}/' + id + '/status',
                        type: 'PATCH',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: currentStatus == 'active' ? 'inactive' : 'active'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                loadUsers();
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে');
                        }
                    });
                }
            });
        });

        // Pagination using AJAX
        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var page = url.match(/page=(\d+)/) ? url.match(/page=(\d+)/)[1] : 1;
            if (page) {
                loadUsers(page);
            }
        });
    }

    // Filter handlers
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            loadUsers();
        }, 500);
    });

    $('#role-filter, #status-filter, #email-verified-filter, #per-page-filter').on('change', function() {
        loadUsers();
    });

    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#role-filter').val('');
        $('#status-filter').val('');
        $('#email-verified-filter').val('');
        $('#per-page-filter').val('20');
        loadUsers();
    });

    // Initial attach
    attachEventHandlers();
});
</script>
@endpush
