{{-- resources/views/admin/contacts/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr>
                <th width="30">
                    <input type="checkbox" id="selectAll">
                </th>
                <th width="50">#</th>
                <th>নাম</th>
                <th>ইমেইল</th>
                <th>বিষয়</th>
                <th>বার্তা</th>
                <th>স্ট্যাটাস</th>
                <th>তারিখ</th>
                <th width="180">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($messages as $index => $message)
            <tr class="table-row-hover {{ $message->status == 'unread' ? 'unread-row' : ($message->status == 'replied' ? 'replied-row' : '') }}"
                data-id="{{ $message->id }}">
                <td>
                    <input type="checkbox" class="contact-checkbox" value="{{ $message->id }}">
                </td>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <strong>{{ $message->name }}</strong>
                    @if($message->phone)
                        <br><small class="text-muted"><i class="fas fa-phone"></i> {{ $message->phone }}</small>
                    @endif
                </td>
                <td>
                    <a href="mailto:{{ $message->email }}" class="text-decoration-none">
                        <i class="fas fa-envelope"></i> {{ $message->email }}
                    </a>
                </td>
                <td>
                    @if($message->subject)
                        {{ Str::limit($message->subject, 30) }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>
                    <div class="message-preview" title="{{ strip_tags($message->message) }}">
                        {{ $message->short_message }}
                    </div>
                </td>
                <td>
                    {!! $message->status_badge !!}
                </td>
                <td>
                    <div class="comment-meta">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $message->created_at?->format('d M, Y') }}
                    </div>
                    <div class="comment-meta">
                        <i class="fas fa-clock"></i>
                        {{ $message->time_ago }}
                    </div>
                    @if($message->status == 'replied' && $message->replied_at)
                        <div class="comment-meta text-success">
                            <i class="fas fa-reply"></i>
                            {{ $message->replied_at->diffForHumans() }}
                        </div>
                    @endif
                </td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ route('admin.contacts.show', $message->id) }}" class="btn btn-info" title="বিস্তারিত দেখুন">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($message->status == 'unread')
                            <button class="btn btn-warning mark-read-single" data-id="{{ $message->id }}" title="পঠিত চিহ্নিত করুন">
                                <i class="fas fa-check-circle"></i>
                            </button>
                        @endif
                        <button class="btn btn-danger delete-contact"
                                data-id="{{ $message->id }}"
                                data-name="{{ $message->name }}"
                                title="ডিলিট করুন">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-5">
                    <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                    <p class="text-muted">কোন যোগাযোগ বার্তা পাওয়া যায়নি</p>
                    <button id="reset-filter-empty" class="btn btn-primary btn-sm">
                        <i class="fas fa-undo-alt"></i> ফিল্টার রিসেট করুন
                    </button>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if(isset($messages) && method_exists($messages, 'links'))
    <div class="d-flex justify-content-center mt-3">
        {{ $messages->appends(request()->query())->links() }}
    </div>
@endif

@push('scripts')
<script>
$(document).ready(function() {
    // Reset filter from empty state
    $('#reset-filter-empty').on('click', function() {
        $('#search-input').val('');
        $('#status-filter').val('');
        $('#per-page-filter').val('20');
        $('#date-from-filter').val('');
        $('#date-to-filter').val('');
        loadContacts();
    });

    // ============================================
    // Mark as Read (Single)
    // ============================================
    $('.mark-read-single').on('click', function() {
        var id = $(this).data('id');
        var btn = $(this);

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: "{{ route('admin.contacts.mark-as-read') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ids: [id]
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    loadContacts();
                } else {
                    toastr.error(response.message);
                    btn.prop('disabled', false).html('<i class="fas fa-check-circle"></i>');
                }
            },
            error: function() {
                toastr.error('বার্তা পড়া চিহ্নিত করতে ব্যর্থ হয়েছে');
                btn.prop('disabled', false).html('<i class="fas fa-check-circle"></i>');
            }
        });
    });
});
</script>
@endpush
