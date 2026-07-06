@extends('admin.layouts.master')

@section('page-title', 'নিউজলেটার সাবস্ক্রাইবার')

@section('filter_input')
<div class="row px-3">
    <div class="col-md-4">
        <input type="text" id="search-input" class="form-control" placeholder="ইমেইল বা নাম খুঁজুন..." autocomplete="off">
    </div>
    <div class="col-md-2">
        <select id="status-filter" class="form-control">
            <option value="">সব স্ট্যাটাস</option>
            <option value="active">সক্রিয়</option>
            <option value="inactive">নিষ্ক্রিয়</option>
            <option value="verified">ভেরিফাইড</option>
            <option value="pending">পেন্ডিং</option>
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
        <button type="button" class="btn btn-primary w-100" onclick="$('#addSubscriberModal').modal('show');">
            <i class="fas fa-plus"></i> নতুন সাবস্ক্রাইবার
        </button>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">মোট সাবস্ক্রাইবার</span>
                    <span class="info-box-number">{{ $stats['total'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">ভেরিফাইড</span>
                    <span class="info-box-number">{{ $stats['verified'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-envelope"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">সক্রিয়</span>
                    <span class="info-box-number">{{ $stats['active'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">পেন্ডিং</span>
                    <span class="info-box-number">{{ $stats['total'] - $stats['verified'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list mr-1"></i> সাবস্ক্রাইবার তালিকা
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.newsletter.subscribers.export') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-download"></i> এক্সপোর্ট CSV
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div id="loading-spinner" class="text-center py-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">ডাটা লোড হচ্ছে...</p>
            </div>

            <div id="subscribers-table-container">
                @include('admin.newsletter.subscribers.partials.table', ['subscribers' => $subscribers])
            </div>
        </div>
    </div>
</div>

<!-- Add Subscriber Modal -->
<div class="modal fade" id="addSubscriberModal" tabindex="-1" role="dialog" aria-labelledby="addSubscriberModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubscriberModalLabel">
                    <i class="fas fa-user-plus mr-2"></i> নতুন সাবস্ক্রাইবার যোগ করুন
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addSubscriberForm">
                    @csrf
                    <div class="form-group">
                        <label for="email">ইমেইল ঠিকানা <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="example@mail.com" required>
                        <small class="text-muted">সাবস্ক্রাইবারের ইমেইল ঠিকানা</small>
                    </div>
                    <div class="form-group">
                        <label for="name">নাম (ঐচ্ছিক)</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="সাবস্ক্রাইবারের নাম">
                        <small class="text-muted">সাবস্ক্রাইবারের পূর্ণ নাম (যদি থাকে)</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-primary" id="saveSubscriberBtn">সংরক্ষণ করুন</button>
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

    function loadSubscribers(page = 1) {
        if (isAjaxLoading) return;

        var search = $('#search-input').val();
        var status = $('#status-filter').val();
        var perPage = $('#per-page-filter').val();

        isAjaxLoading = true;
        $('#loading-spinner').show();
        $('#subscribers-table-container').hide();

        $.ajax({
            url: "{{ route('admin.newsletter.subscribers.index') }}",
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
                    $('#subscribers-table-container').html(response.html);

                    if (response.stats) {
                        $('.info-box-number').eq(0).text(response.stats.total);
                        $('.info-box-number').eq(1).text(response.stats.verified);
                        $('.info-box-number').eq(2).text(response.stats.active);
                        $('.info-box-number').eq(3).text(response.stats.total - response.stats.verified);
                    }

                    attachEventHandlers();
                }
                $('#loading-spinner').hide();
                $('#subscribers-table-container').show();
            },
            error: function(xhr) {
                console.error('AJAX Error:', xhr);
                toastr.error('ডাটা লোড করতে ব্যর্থ হয়েছে');
                $('#loading-spinner').hide();
                $('#subscribers-table-container').show();
            },
            complete: function() {
                isAjaxLoading = false;
            }
        });
    }

    function attachEventHandlers() {
        $('.delete-subscriber').off('click').on('click', function() {
            var id = $(this).data('id');
            var email = $(this).data('email');
            var row = $(this).closest('tr');

            Swal.fire({
                title: 'সাবস্ক্রাইবার মুছুন?',
                text: email + ' কে সাবস্ক্রাইবার লিস্ট থেকে মুছে ফেলতে যাচ্ছেন!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'হ্যাঁ, মুছুন',
                cancelButtonText: 'বাতিল',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("admin/newsletter-subscribers") }}/' + id,
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
            var url = $(this).attr('href');
            var page = url.match(/page=(\d+)/) ? url.match(/page=(\d+)/)[1] : 1;
            if (page) loadSubscribers(page);
        });
    }

    // Add new subscriber
    $('#saveSubscriberBtn').on('click', function() {
        var email = $('#email').val();
        var name = $('#name').val();

        if (!email) {
            toastr.warning('ইমেইল ঠিকানা দিন');
            $('#email').focus();
            return;
        }

        var btn = $(this);
        var originalHtml = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> সংরক্ষণ...').prop('disabled', true);

        $.ajax({
            url: '{{ route("admin.newsletter.subscribers.add") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                email: email,
                name: name
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#addSubscriberModal').modal('hide');
                    $('#addSubscriberForm')[0].reset();
                    loadSubscribers();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                var error = xhr.responseJSON?.message || 'সাবস্ক্রাইবার যোগ করতে ব্যর্থ হয়েছে';
                toastr.error(error);
            },
            complete: function() {
                btn.html(originalHtml).prop('disabled', false);
            }
        });
    });

    // Search with debounce
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            loadSubscribers();
        }, 500);
    });

    // Filter change handlers
    $('#status-filter, #per-page-filter').on('change', function() {
        loadSubscribers();
    });

    // Reset filter
    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#status-filter').val('');
        $('#per-page-filter').val('20');
        loadSubscribers();
    });

    // Modal reset on show
    $('#addSubscriberModal').on('show.bs.modal', function() {
        $('#addSubscriberForm')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
    });

    // Initial load
    attachEventHandlers();
});
</script>
@endpush
