<div class="table-responsive">
      <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
        <thead class="thead-dark">
            <tr class="text-center">
                <th>ID</th>
                <th>ইমেইল</th>
                <th>নাম</th>
                <th>ভেরিফিকেশন</th>
                <th>স্ট্যাটাস</th>
                <th>সাবস্ক্রাইব তারিখ</th>
                <th>অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subscribers as $subscriber)
            <tr>
                <td class="text-center">{{ $subscriber->id }}</td>
                <td><strong>{{ $subscriber->email }}</strong></td>
                <td>{{ $subscriber->name ?? '-' }}</td>
                <td class="text-center">
                    @if($subscriber->verified_at)
                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> {{ $subscriber->verified_at->format('d M, Y') }}</span>
                    @else
                        <span class="badge bg-warning text-dark">পেন্ডিং</span>
                    @endif
                </td>
                <td class="text-center">{!! $subscriber->status_badge !!}</td>
                <td>{{ $subscriber->created_at->format('d M, Y') }}</td>
                <td class="text-center p-0">
                    <button class="btn btn-sm btn-danger delete-subscriber" data-id="{{ $subscriber->id }}" data-email="{{ $subscriber->email }}" title="ডিলিট">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-4">
                    <i class="fas fa-envelope-open fa-2x mb-2 d-block text-muted"></i>
                    <p class="text-muted">কোনো সাবস্ক্রাইবার পাওয়া যায়নি।</p>
                    <button type="button" class="btn btn-primary btn-sm" onclick="$('#addSubscriberModal').modal('show');">
                        <i class="fas fa-plus"></i> প্রথম সাবস্ক্রাইবার যোগ করুন
                    </button>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($subscribers->isNotEmpty())
<div class="row mt-3">
    <div class="col-md-12">
        <div class="float-right">
            {{ $subscribers->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endif
