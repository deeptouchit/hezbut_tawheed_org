@extends('admin.layouts.master')

@section('page-title', 'ক্যাম্পেইন বিবরণ - ' . $campaign->subject)

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-campaign me-2"></i> ক্যাম্পেইন বিবরণ: {{ $campaign->subject }}
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    @if($campaign->status == 'draft')
                        <button class="btn btn-success btn-sm send-campaign" data-id="{{ $campaign->id }}">
                            <i class="fas fa-paper-plane"></i> এখনই পাঠান
                        </button>
                        <a href="{{ route('admin.newsletter.campaigns.edit', $campaign->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> সম্পাদনা
                        </a>
                    @endif
                    <a href="{{ route('admin.newsletter.campaigns.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> তালিকায় ফিরুন
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-info-circle"></i> ক্যাম্পেইন তথ্য
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered">
                                <tr><th width="30%">বিষয়</th><td><strong>{{ $campaign->subject }}</strong></td></tr>
                                @if($campaign->title) <tr><th>শিরোনাম</th><td>{{ $campaign->title }}</td></tr> @endif
                                <tr><th>স্ট্যাটাস</th><td>{!! $campaign->status_badge !!}</td></tr>
                                <tr><th>প্রাপকের ধরণ</th>
                                    <td>
                                        @if($campaign->recipient_type == 'all')
                                            সব সাবস্ক্রাইবার
                                        @elseif($campaign->recipient_type == 'active_only')
                                            সক্রিয় সাবস্ক্রাইবার
                                        @else
                                            নির্বাচিত ইমেইল
                                        @endif
                                     </td>
                                </tr>
                                <tr><th>মোট প্রাপক</th><td>{{ number_format($campaign->total_recipients) }} জন</td></tr>
                                @if($campaign->sent_at) <tr><th>পাঠানো হয়েছে</th><td>{{ $campaign->sent_at->format('d M, Y h:i A') }}</td></tr> @endif
                                @if($campaign->scheduled_at) <tr><th>শিডিউল সময়</th><td>{{ $campaign->scheduled_at->format('d M, Y h:i A') }}</td></tr> @endif
                                <tr><th>তৈরির তারিখ</th><td>{{ $campaign->created_at->format('d M, Y h:i A') }}</td></tr>
                                <tr><th>তৈরিকারী</th><td>{{ $campaign->creator->name ?? 'Admin' }}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-chart-line"></i> পরিসংখ্যান
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-text">পাঠানো হয়েছে</span>
                                            <span class="info-box-number">{{ number_format($campaign->sent_count) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-text">ওপেন রেট</span>
                                            <span class="info-box-number">{{ $campaign->total_recipients > 0 ? round(($campaign->opened_count / $campaign->total_recipients) * 100) : 0 }}%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-text">ক্লিক রেট</span>
                                            <span class="info-box-number">{{ $campaign->total_recipients > 0 ? round(($campaign->clicked_count / $campaign->total_recipients) * 100) : 0 }}%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-text">বাউন্স রেট</span>
                                            <span class="info-box-number">0%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($campaign->recipient_type == 'selected' && $campaign->selected_emails)
                    <div class="card card-info card-outline mt-3">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-envelope"></i> নির্বাচিত ইমেইল তালিকা
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0">
                                    <tbody>
                                        @foreach($campaign->selected_emails as $email)
                                        <tr><td>{{ $email }}</td></tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Email Content Preview -->
            <div class="card card-primary card-outline mt-3">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-envelope-open-text"></i> ইমেইল কন্টেন্ট
                    </h5>
                </div>
                <div class="card-body">
                    <div class="border p-3 bg-light" style="min-height: 300px;">
                        {!! $campaign->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Send campaign
    $('.send-campaign').on('click', function() {
        var id = $(this).data('id');
        var btn = $(this);

        Swal.fire({
            title: 'ক্যাম্পেইন পাঠান?',
            text: 'এই ক্যাম্পেইনটি এখনই {{ number_format($campaign->total_recipients) }} জন সাবস্ক্রাইবারকে পাঠানো হবে!',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'হ্যাঁ, পাঠান',
            cancelButtonText: 'বাতিল'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.html('<i class="fas fa-spinner fa-spin"></i> পাঠানো হচ্ছে...').prop('disabled', true);

                $.ajax({
                    url: '{{ route("admin.newsletter.campaigns.send", $campaign->id) }}',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            setTimeout(() => {
                                window.location.href = '{{ route("admin.newsletter.campaigns.index") }}';
                            }, 1500);
                        } else {
                            toastr.error(response.message);
                            btn.html('<i class="fas fa-paper-plane"></i> এখনই পাঠান').prop('disabled', false);
                        }
                    },
                    error: function() {
                        toastr.error('পাঠাতে ব্যর্থ হয়েছে');
                        btn.html('<i class="fas fa-paper-plane"></i> এখনই পাঠান').prop('disabled', false);
                    }
                });
            }
        });
    });
});
</script>
@endpush
