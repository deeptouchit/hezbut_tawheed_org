{{-- resources/views/admin/contacts/partials/detail.blade.php --}}

<div class="row">
    <!-- Left Column - Message Content -->
    <div class="col-md-8">
        <!-- Sender Info -->
        <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($message->name) }}&size=50&background=0d6efd&color=fff&rounded=true"
                 alt="{{ $message->name }}"
                 class="rounded-circle me-3"
                 style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #e9ecef;">
            <div>
                <h5 class="mb-0">{{ $message->name }}</h5>
                <small class="text-muted">
                    <i class="fas fa-envelope"></i> {{ $message->email }}
                    @if($message->phone)
                        <span class="ms-2"><i class="fas fa-phone"></i> {{ $message->phone }}</span>
                    @endif
                </small>
            </div>
            <div class="ms-auto">
                <span class="badge {{ $message->status == 'unread' ? 'bg-danger' : ($message->status == 'read' ? 'bg-info' : 'bg-success') }}">
                    @if($message->status == 'unread')
                        <i class="fas fa-circle me-1"></i> অপঠিত
                    @elseif($message->status == 'read')
                        <i class="fas fa-check-circle me-1"></i> পঠিত
                    @else
                        <i class="fas fa-reply me-1"></i> উত্তর দেওয়া
                    @endif
                </span>
            </div>
        </div>

        <!-- Subject -->
        @if($message->subject)
            <div class="mb-3">
                <h6><i class="fas fa-tag text-primary"></i> বিষয়: {{ $message->subject }}</h6>
            </div>
        @endif

        <!-- Message -->
        <div class="p-4 bg-light rounded" style="white-space: pre-wrap; line-height: 1.8; min-height: 120px;">
            {{ $message->message }}
        </div>

        <!-- Meta Info -->
        <div class="row mt-3 g-2">
            <div class="col-md-3 col-6">
                <div class="p-2 bg-white border rounded text-center">
                    <small class="text-muted">প্রাপ্তির তারিখ</small>
                    <div class="fw-bold">{{ $message->created_at?->format('d M, Y') }}</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-2 bg-white border rounded text-center">
                    <small class="text-muted">সময়</small>
                    <div class="fw-bold">{{ $message->created_at?->format('h:i A') }}</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-2 bg-white border rounded text-center">
                    <small class="text-muted">আগে</small>
                    <div class="fw-bold">{{ $message->time_ago }}</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-2 bg-white border rounded text-center">
                    <small class="text-muted">স্ট্যাটাস</small>
                    <div class="fw-bold">{!! $message->status_badge !!}</div>
                </div>
            </div>
        </div>

        <!-- Reply (if any) -->
        @if($message->status == 'replied' && $message->reply_message)
            <div class="mt-4 p-3 bg-success bg-opacity-10 rounded border border-success">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        <i class="fas fa-reply text-success fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-success mb-1">
                            <i class="fas fa-user"></i>
                            @if($message->replier)
                                {{ $message->replier->name }}
                            @else
                                অ্যাডমিন
                            @endif
                            <small class="text-muted ms-2">
                                <i class="fas fa-clock"></i> {{ $message->replied_at?->diffForHumans() }}
                            </small>
                        </h6>
                        <div class="p-2 bg-white rounded" style="white-space: pre-wrap; line-height: 1.6;">
                            {{ $message->reply_message }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Right Column - Actions -->
    <div class="col-md-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="fas fa-tools me-2"></i> অ্যাকশন</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($message->status != 'replied')
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#replyModal">
                            <i class="fas fa-reply"></i> উত্তর দিন
                        </button>
                    @endif
                    @if($message->status == 'unread')
                        <button class="btn btn-info mark-read" data-id="{{ $message->id }}">
                            <i class="fas fa-check-circle"></i> পঠিত চিহ্নিত করুন
                        </button>
                    @endif
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> ফিরে যান
                    </a>
                    <button class="btn btn-danger delete-message" data-id="{{ $message->id }}" data-name="{{ $message->name }}">
                        <i class="fas fa-trash"></i> ডিলিট করুন
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Info -->
        <div class="card mt-3">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i> দ্রুত তথ্য</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td><i class="fas fa-id"></i> আইডি</td>
                        <td>#{{ $message->id }}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-user"></i> নাম</td>
                        <td>{{ $message->name }}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-envelope"></i> ইমেইল</td>
                        <td>{{ $message->email }}</td>
                    </tr>
                    @if($message->phone)
                        <tr>
                            <td><i class="fas fa-phone"></i> ফোন</td>
                            <td>{{ $message->phone }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td><i class="fas fa-tag"></i> বিষয়</td>
                        <td>{{ $message->subject ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-flag"></i> স্ট্যাটাস</td>
                        <td>{!! $message->status_badge !!}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-calendar"></i> প্রাপ্তি</td>
                        <td>{{ $message->formatted_date }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Reply Modal -->
        <div class="modal fade" id="replyModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="fas fa-reply"></i> উত্তর দিন</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="replyForm">
                            @csrf
                            <input type="hidden" id="reply_message_id" value="{{ $message->id }}">

                            <div class="mb-3">
                                <label class="required-field">আপনার উত্তর <span class="text-danger">*</span></label>
                                <textarea name="reply_message"
                                          id="reply_message"
                                          class="form-control"
                                          rows="4"
                                          placeholder="আপনার উত্তর লিখুন..."
                                          required></textarea>
                            </div>

                            <div class="form-check form-switch">
                                <input type="hidden" name="send_email" value="0">
                                <input type="checkbox"
                                       name="send_email"
                                       class="form-check-input"
                                       id="sendEmailSwitch"
                                       value="1"
                                       checked>
                                <label class="form-check-label" for="sendEmailSwitch">
                                    <i class="fas fa-envelope"></i> ইমেইলেও উত্তর পাঠান
                                </label>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                        <button type="button" class="btn btn-success" id="submitReply">
                            <i class="fas fa-reply"></i> উত্তর পাঠান
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // ============================================
    // Mark as Read
    // ============================================
    $('.mark-read').click(function() {
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
                    location.reload();
                } else {
                    toastr.error(response.message);
                    btn.prop('disabled', false).html('<i class="fas fa-check-circle"></i> পঠিত চিহ্নিত করুন');
                }
            },
            error: function() {
                toastr.error('বার্তা পড়া চিহ্নিত করতে ব্যর্থ হয়েছে');
                btn.prop('disabled', false).html('<i class="fas fa-check-circle"></i> পঠিত চিহ্নিত করুন');
            }
        });
    });

    // ============================================
    // Delete Message
    // ============================================
    $('.delete-message').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');

        Swal.fire({
            title: 'আপনি কি নিশ্চিত?',
            text: name + ' এর পাঠানো বার্তাটি ডিলিট করতে চান?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'হ্যাঁ, ডিলিট',
            cancelButtonText: 'বাতিল'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin/contacts") }}/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            setTimeout(function() {
                                window.location.href = '{{ route("admin.contacts.index") }}';
                            }, 1500);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('বার্তা ডিলিট করতে ব্যর্থ হয়েছে');
                    }
                });
            }
        });
    });

    // ============================================
    // Submit Reply
    // ============================================
    $('#submitReply').click(function() {
        var id = $('#reply_message_id').val();
        var replyMessage = $('#reply_message').val().trim();
        var sendEmail = $('#sendEmailSwitch').is(':checked') ? 1 : 0;

        if (!replyMessage || replyMessage.length < 10) {
            Swal.fire({
                icon: 'error',
                title: 'ত্রুটি!',
                text: 'দয়া করে কমপক্ষে ১০ অক্ষরের উত্তর লিখুন',
                confirmButtonColor: '#198754'
            });
            $('#reply_message').focus();
            return;
        }

        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: '{{ url("admin/contacts") }}/' + id + '/reply',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                reply_message: replyMessage,
                send_email: sendEmail
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#replyModal').modal('hide');
                    location.reload();
                } else {
                    toastr.error(response.message);
                    btn.prop('disabled', false).html('<i class="fas fa-reply"></i> উত্তর পাঠান');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                } else {
                    toastr.error('উত্তর পাঠাতে ব্যর্থ হয়েছে');
                }
                btn.prop('disabled', false).html('<i class="fas fa-reply"></i> উত্তর পাঠান');
            }
        });
    });

    // Reset form on modal close
    $('#replyModal').on('hidden.bs.modal', function() {
        $('#replyForm')[0].reset();
        $('#submitReply').prop('disabled', false).html('<i class="fas fa-reply"></i> উত্তর পাঠান');
    });
});
</script>
@endpush
