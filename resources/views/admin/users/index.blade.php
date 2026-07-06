@extends('admin.layouts.master')

@section('page-title', 'ইউজার তালিকা')

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
            <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>সব দেখান</option>
        </select>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
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
