<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th width="50">ID</th>
                <th>বিষয়</th>
                <th>প্রাপক</th>
                <th>স্ট্যাটাস</th>
                <th>পাঠানো হয়েছে</th>
                <th>তৈরির তারিখ</th>
                <th width="120">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($campaigns as $campaign)
            <tr>
                <td class="text-center">{{ $campaign->id }}</td>
                <td>
                    <strong>{{ $campaign->subject }}</strong>
                    @if($campaign->title)<br><small>{{ $campaign->title }}</small>@endif
                </td>
                <td class="text-center">
                    @if($campaign->recipient_type == 'all')
                        <span class="badge bg-primary">সব সাবস্ক্রাইবার</span>
                    @elseif($campaign->recipient_type == 'active_only')
                        <span class="badge bg-success">সক্রিয় সাবস্ক্রাইবার</span>
                    @else
                        <span class="badge bg-info">নির্বাচিত</span>
                    @endif
                    <br><small>{{ number_format($campaign->total_recipients) }} জন</small>
                </td>
                <td class="text-center">{!! $campaign->status_badge !!}</td>
                <td class="text-center">
                    @if($campaign->sent_at)
                        {{ $campaign->sent_at->format('d M, Y') }}
                    @elseif($campaign->scheduled_at)
                        <span class="text-warning">{{ $campaign->scheduled_at->format('d M, Y h:i A') }}</span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td class="text-center">{{ $campaign->created_at->format('d M, Y') }}<br>
                <small>by {{ $campaign->creator->name ?? 'Admin' }}</small></td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.newsletter.campaigns.show', $campaign->id) }}" class="btn btn-info" title="দেখুন">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($campaign->status == 'draft')
                            <button class="btn btn-success send-campaign" data-id="{{ $campaign->id }}" data-name="{{ $campaign->subject }}" title="পাঠান">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                            <a href="{{ route('admin.newsletter.campaigns.edit', $campaign->id) }}" class="btn btn-primary" title="এডিট">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endif
                        @if(in_array($campaign->status, ['draft', 'scheduled', 'failed']))
                            <button class="btn btn-danger delete-campaign" data-id="{{ $campaign->id }}" data-name="{{ $campaign->subject }}" title="ডিলিট">
                                <i class="fas fa-trash"></i>
                            </button>
                        @endif
                    </div>
                </table>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-4">
                    <i class="fas fa-campaign fa-2x mb-2 d-block text-muted"></i>
                    <p class="text-muted">কোনো ক্যাম্পেইন পাওয়া যায়নি।</p>
                    <a href="{{ route('admin.newsletter.campaigns.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> প্রথম ক্যাম্পেইন তৈরি করুন
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($campaigns->isNotEmpty())
<div class="row mt-3">
    <div class="col-md-12">
        <div class="float-right">
            {{ $campaigns->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endif
