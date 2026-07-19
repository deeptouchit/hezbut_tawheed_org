{{-- resources/views/admin/newsletter/campaigns/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
        <thead>
            <tr class="text-center">
                <th>ID</th>
                <th>বিষয়</th>
                <th>প্রাপক</th>
                <th>স্ট্যাটাস</th>
                <th>পাঠানো হয়েছে</th>
                <th>তৈরির তারিখ</th>
                <th>অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($campaigns as $campaign)
            <tr class="text-center">
                <td>{{ $loop->iteration }}</td>
                <td class="text-start">
                    <strong>{{ $campaign->subject }}</strong>
                    <!-- @if($campaign->title)<br><small class="text-muted">{{ $campaign->title }}</small>@endif -->
                </td>
                <td>
                    @if($campaign->recipient_type == 'all')
                    <span class="badge bg-primary">সব সাবস্ক্রাইবার</span>
                    @elseif($campaign->recipient_type == 'active_only')
                    <span class="badge bg-success">সক্রিয় সাবস্ক্রাইবার</span>
                    @else
                    <span class="badge bg-info">নির্বাচিত</span>
                    @endif
                    <!-- <br><small class="text-muted">{{ number_format($campaign->total_recipients) }} জন</small> -->
                </td>
                <td>{!! $campaign->status_badge !!}</td>
                <td>
                    @if($campaign->sent_at)
                    {{ $campaign->sent_at->format('d M, Y') }}
                    @elseif($campaign->scheduled_at)
                    <span class="text-warning">{{ $campaign->scheduled_at->format('d M, Y h:i A') }}</span>
                    @else
                    <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    {{ $campaign->created_at->format('d M, Y') }}<br>
                    <!-- <small class="text-muted">by {{ $campaign->creator->name ?? 'Admin' }}</small> -->
                </td>
                <td class="p-0">
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.newsletter.campaigns.show', $campaign->id) }}" class="btn btn-info" title="দেখুন">
                            <i class="fas fa-eye text-white"></i>
                        </a>
                        @if($campaign->status == 'draft')
                        <button class="btn btn-success send-campaign" data-id="{{ $campaign->id }}" data-name="{{ $campaign->subject }}" title="পাঠান">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                        @endif
                        @if(in_array($campaign->status, ['draft', 'scheduled', 'failed']))
                        <button class="btn btn-danger delete-campaign" data-id="{{ $campaign->id }}" data-name="{{ $campaign->subject }}" title="ডিলিট">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-5">
                    <i class="fas fa-bullhorn fa-3x text-muted mb-3 d-block"></i>
                    <p class="text-muted mb-0">কোনো ক্যাম্পেইন পাওয়া যায়নি।</p>
                    <a href="{{ route('admin.newsletter.campaigns.create') }}" class="btn btn-primary btn-sm mt-3">
                        <i class="fas fa-plus mr-1"></i> প্রথম ক্যাম্পেইন তৈরি করুন
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if(isset($campaigns) && method_exists($campaigns, 'links'))
<div class="row mt-3">
    <div class="col-12 d-flex justify-content-center">
        {{ $campaigns->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endif