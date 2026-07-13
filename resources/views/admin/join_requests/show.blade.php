{{-- resources/views/admin/join_requests/show.blade.php --}}

@extends('admin.layouts.master')

@section('page-title', 'আবেদনের বিস্তারিত বিবরণ - ' . $requestData->name)

@push('styles')
<style>
    .detail-card {
        background: #fff;
        border-radius: 10px;
        padding: 25px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .info-group {
        border-bottom: 1px solid #f1f5f9;
        padding: 12px 0;
    }
    .info-group:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: 600;
        color: #475569;
        font-size: 0.95rem;
    }
    .info-value {
        color: #1e293b;
        font-size: 0.95rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Main details -->
        <div class="col-lg-8 mb-4">
            <div class="detail-card">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                    <h4 class="fw-bold mb-0 text-success-brand">
                        <i class="fas fa-id-card me-2"></i> আবেদনকারীর তথ্য বিবরণী
                    </h4>
                    <div>
                        {!! $requestData->type_badge !!}
                    </div>
                </div>

                <div class="info-group row">
                    <div class="col-md-4 info-label">আবেদনকারীর নাম:</div>
                    <div class="col-md-8 info-value fw-semibold text-primary">{{ $requestData->name }}</div>
                </div>

                <div class="info-group row">
                    <div class="col-md-4 info-label">মোবাইল নম্বর:</div>
                    <div class="col-md-8 info-value">
                        <a href="tel:{{ $requestData->phone }}" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-phone-alt me-1"></i> {{ $requestData->phone }}
                        </a>
                    </div>
                </div>

                @if($requestData->join_date)
                <div class="info-group row">
                    <div class="col-md-4 info-label">আবেদনের তারিখ:</div>
                    <div class="col-md-8 info-value">{{ $requestData->join_date }}</div>
                </div>
                @endif

                @if($requestData->father_husband)
                <div class="info-group row">
                    <div class="col-md-4 info-label">পিতা / স্বামীর নাম:</div>
                    <div class="col-md-8 info-value">{{ $requestData->father_husband }}</div>
                </div>
                @endif

                <div class="info-group row">
                    <div class="col-md-4 info-label">বয়স:</div>
                    <div class="col-md-8 info-value">{{ $requestData->age ?? 'N/A' }}</div>
                </div>

                @if($requestData->occupation)
                <div class="info-group row">
                    <div class="col-md-4 info-label">পেশা:</div>
                    <div class="col-md-8 info-value">{{ $requestData->occupation }}</div>
                </div>
                @endif

                @if($requestData->education)
                <div class="info-group row">
                    <div class="col-md-4 info-label">শিক্ষাগত যোগ্যতা:</div>
                    <div class="col-md-8 info-value">{{ $requestData->education }}</div>
                </div>
                @endif

                @if($requestData->current_unit_amir)
                <div class="info-group row">
                    <div class="col-md-4 info-label">বর্তমান ইউনিট ও আমির:</div>
                    <div class="col-md-8 info-value text-dark fw-bold">{{ $requestData->current_unit_amir }}</div>
                </div>
                @endif

                <div class="info-group row">
                    <div class="col-md-4 info-label">ঠিকানা:</div>
                    <div class="col-md-8 info-value text-secondary">{{ $requestData->present_address }}</div>
                </div>

                @if($requestData->permanent_address)
                <div class="info-group row">
                    <div class="col-md-4 info-label">স্থায়ী ঠিকানা:</div>
                    <div class="col-md-8 info-value text-secondary">{{ $requestData->permanent_address }}</div>
                </div>
                @endif

                @if($requestData->experience)
                <div class="info-group row">
                    <div class="col-md-4 info-label">দক্ষতা / পারদর্শিতা:</div>
                    <div class="col-md-8 info-value">{{ $requestData->experience }}</div>
                </div>
                @endif

                <div class="info-group row">
                    <div class="col-md-4 info-label">কীভাবে আন্দোলন সম্পর্কে জেনেছেন?</div>
                    <div class="col-md-8 info-value badge bg-light text-dark text-start p-2 fs-6 fw-semibold">{{ $requestData->how_knew }}</div>
                </div>

                @if($requestData->person_name)
                <div class="info-group row bg-light p-2 rounded">
                    <div class="col-md-4 info-label">পরিচিত ব্যক্তির নাম:</div>
                    <div class="col-md-8 info-value fw-bold text-dark">{{ $requestData->person_name }}</div>
                </div>
                @endif

                @if($requestData->person_phone)
                <div class="info-group row bg-light p-2 rounded">
                    <div class="col-md-4 info-label">পরিচিত ব্যক্তির মোবাইল নম্বর:</div>
                    <div class="col-md-8 info-value">{{ $requestData->person_phone }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Actions & Metadata -->
        <div class="col-lg-4">
            <div class="detail-card mb-4">
                <h5 class="fw-bold border-bottom pb-2 mb-3">আবেদনের অবস্থা</h5>
                <div class="mb-3 d-flex align-items-center gap-2">
                    <span>স্ট্যাটাস:</span>
                    <span id="status-badge-container">{!! $requestData->status_badge !!}</span>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-success update-status-btn" data-status="approved">
                        <i class="fas fa-check-circle me-1"></i> অনুমোদন করুন
                    </button>
                    <button class="btn btn-secondary update-status-btn" data-status="rejected">
                        <i class="fas fa-times-circle me-1"></i> প্রত্যাখ্যান করুন
                    </button>
                    <button class="btn btn-warning update-status-btn" data-status="unread">
                        <i class="fas fa-envelope me-1"></i> অপঠিত চিহ্নিত করুন
                    </button>
                    <hr>
                    <a href="{{ route('admin.join-requests.index') }}" class="btn btn-outline-dark">
                        <i class="fas fa-arrow-left me-1"></i> তালিকায় ফিরে যান
                    </a>
                </div>
            </div>

            <div class="detail-card">
                <h5 class="fw-bold border-bottom pb-2 mb-3">মেটাডাটা</h5>
                <div class="small">
                    <div class="mb-2"><strong>আইপি অ্যাড্রেস:</strong> {{ $requestData->ip_address ?? 'N/A' }}</div>
                    <div class="mb-2" style="word-break: break-all;"><strong>ইউজার এজেন্ট:</strong> {{ $requestData->user_agent ?? 'N/A' }}</div>
                    <div class="mb-2"><strong>সৃষ্টির সময়:</strong> {{ $requestData->created_at?->format('d M, Y h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.update-status-btn').on('click', function() {
        var status = $(this).data('status');
        
        $.ajax({
            url: "{{ route('admin.join-requests.update-status', $requestData->id) }}",
            type: "POST",
            data: {
                status: status,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে!');
            }
        });
    });
});
</script>
@endpush
