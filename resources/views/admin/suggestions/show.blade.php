@extends('admin.layouts.master')

@section('page-title', 'নাগরিক পরামর্শ বার্তা বিস্তারিত - ' . $suggestion->name)

@push('styles')
<style>
    .detail-card {
        background: #fff;
        border-radius: 10px;
        padding: 25px;
        border: 1px solid #e9ecef;
    }
    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 20px;
    }
    .detail-header .sender-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .sender-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e9ecef;
    }
    .sender-name {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 2px;
    }
    .sender-contact {
        color: #6c757d;
        font-size: 14px;
    }
    .message-body {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        line-height: 1.8;
        font-size: 15px;
        color: #212529;
        white-space: pre-wrap;
        min-height: 150px;
    }
    .meta-box {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    .meta-item {
        display: flex;
        flex-direction: column;
    }
    .meta-item .label {
        font-size: 12px;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .meta-item .value {
        font-size: 14px;
        color: #212529;
        margin-top: 2px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h3 class="card-title fw-bold mb-0">
                <i class="fas fa-comment-dots text-primary me-2"></i> নাগরিক পরামর্শ বিস্তারিত
                <span class="badge bg-secondary ms-2">#{{ $suggestion->id }}</span>
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.suggestions.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> ফিরে যান
                    </a>
                    <button class="btn btn-danger btn-sm delete-suggestion" data-id="{{ $suggestion->id }}" data-name="{{ $suggestion->name }}">
                        <i class="fas fa-trash"></i> ডিলিট
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Main Content -->
                <div class="col-md-8">
                    <div class="detail-card">
                        <div class="detail-header">
                            <div class="sender-info">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($suggestion->name) }}&size=60&background=10b981&color=fff&rounded=true"
                                     alt="{{ $suggestion->name }}"
                                     class="sender-avatar">
                                <div>
                                    <div class="sender-name">{{ $suggestion->name }}</div>
                                    <div class="sender-contact">
                                        <i class="fas fa-address-book"></i> {{ $suggestion->contact }}
                                    </div>
                                </div>
                            </div>
                            <div>
                                @if($suggestion->status == 'pending')
                                    <span class="badge bg-warning text-dark py-2 px-3"><i class="fas fa-clock"></i> পেন্ডিং</span>
                                @else
                                    <span class="badge bg-success py-2 px-3"><i class="fas fa-check-circle"></i> রিভিউড</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="fw-bold"><i class="fas fa-bookmark text-primary me-1"></i> বিষয়: {{ $suggestion->subject ?? 'মতামত/পরামর্শ' }}</h5>
                        </div>

                        <div class="message-body">
                            {{ $suggestion->message }}
                        </div>

                        <div class="meta-box">
                            <div class="meta-item">
                                <span class="label"><i class="fas fa-calendar-alt"></i> প্রাপ্তির তারিখ</span>
                                <span class="value">{{ $suggestion->created_at->format('d M, Y h:i A') }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="label"><i class="fas fa-clock"></i> আগে</span>
                                <span class="value">{{ $suggestion->created_at->diffForHumans() }}</span>
                            </div>
                            @if($suggestion->ip_address)
                                <div class="meta-item">
                                    <span class="label"><i class="fas fa-network-wired"></i> আইপি এড্রেস</span>
                                    <span class="value">{{ $suggestion->ip_address }}</span>
                                </div>
                            @endif
                            @if($suggestion->user_agent)
                                <div class="meta-item">
                                    <span class="label"><i class="fas fa-laptop"></i> ইউজার এজেন্ট (ডিভাইস)</span>
                                    <span class="value"><small>{{ Str::limit($suggestion->user_agent, 80) }}</small></span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Info Sidebar -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h5 class="card-title mb-0" style="font-size: 1rem;"><i class="fas fa-info-circle me-1"></i> কুইক ইনফো</h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped table-borderless mb-0" style="font-size: 0.95rem;">
                                <tr>
                                    <td class="ps-3 py-2 fw-bold" width="120">আইডি:</td>
                                    <td class="py-2">#{{ $suggestion->id }}</td>
                                </tr>
                                <tr>
                                    <td class="ps-3 py-2 fw-bold">প্রেরকের নাম:</td>
                                    <td class="py-2">{{ $suggestion->name }}</td>
                                </tr>
                                <tr>
                                    <td class="ps-3 py-2 fw-bold">যোগাযোগ:</td>
                                    <td class="py-2">{{ $suggestion->contact }}</td>
                                </tr>
                                <tr>
                                    <td class="ps-3 py-2 fw-bold">বিষয়:</td>
                                    <td class="py-2">{{ $suggestion->subject ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="ps-3 py-2 fw-bold">স্ট্যাটাস:</td>
                                    <td class="py-2">
                                        @if($suggestion->status == 'pending')
                                            <span class="badge bg-warning text-dark">পেন্ডিং</span>
                                        @else
                                            <span class="badge bg-success">রিভিউড</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-3 py-2 fw-bold">তারিখ ও সময়:</td>
                                    <td class="py-2">{{ $suggestion->created_at->format('Y-m-d h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> নিশ্চিতকরণ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>আপনি কি নিশ্চিত যে আপনি এই পরামর্শ বার্তাটি ডিলিট করতে চান?</p>
                <p class="text-danger"><small>⚠️ এই কাজটি অপরিবর্তনীয়!</small></p>
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
    let deleteId = null;

    $('.delete-suggestion').on('click', function() {
        deleteId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirm-delete').on('click', function() {
        if (!deleteId) return;

        $.ajax({
            url: "{{ url('admin/suggestions') }}/" + deleteId,
            type: "DELETE",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                $('#deleteModal').modal('hide');
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.href = "{{ route('admin.suggestions.index') }}";
                    }, 1000);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                $('#deleteModal').modal('hide');
                toastr.error('মুছে ফেলতে ব্যর্থ হয়েছে!');
            }
        });
    });
});
</script>
@endpush
