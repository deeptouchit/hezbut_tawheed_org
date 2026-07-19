{{-- resources/views/admin/newsletter/subscribers/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm text-nowrap" style="width: 100%;">
        <thead class="">
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
            <tr class="text-center">
                <td>{{ $subscriber->id }}</td>
                <td class="text-start"><strong>{{ $subscriber->email }}</strong></td>
                <td class="text-start">{{ $subscriber->name ?? '-' }}</td>
                <td>
                    @if($subscriber->verified_at)
                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> {{ $subscriber->verified_at->format('d M, Y') }}</span>
                    @else
                        <span class="badge bg-warning text-dark">পেন্ডিং</span>
                    @endif
                </td>
                <td>{!! $subscriber->status_badge !!}</td>
                <td>{{ $subscriber->created_at->format('d M, Y') }}</td>
                <td class="p-0">
                    <button class="btn btn-sm btn-danger delete-subscriber" data-id="{{ $subscriber->id }}" data-email="{{ $subscriber->email }}" title="ডিলিট">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-5">
                    <i class="fas fa-envelope-open fa-3x text-muted mb-3 d-block"></i>
                    <p class="text-muted mb-0">কোনো সাবস্ক্রাইবার পাওয়া যায়নি।</p>
                    <button type="button" class="btn btn-primary btn-sm mt-3" onclick="$('#addSubscriberModal').modal('show');">
                        <i class="fas fa-plus mr-1"></i> প্রথম সাবস্ক্রাইবার যোগ করুন
                    </button>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if(isset($subscribers) && method_exists($subscribers, 'links'))
    <div class="row mt-3">
        <div class="col-12 d-flex justify-content-center">
            {{ $subscribers->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
