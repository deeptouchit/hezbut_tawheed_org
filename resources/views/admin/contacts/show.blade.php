{{-- resources/views/admin/contacts/show.blade.php --}}

@extends('admin.layouts.master')

@section('page-title', 'যোগাযোগ বার্তা বিস্তারিত - ' . $message->name)

@push('styles')
<style>
    .message-detail-card {
        background: #fff;
        border-radius: 10px;
        padding: 25px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .message-detail-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .message-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 20px;
    }
    .message-header .sender-info {
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
    .sender-email {
        color: #6c757d;
        font-size: 14px;
    }
    .message-status {
        padding: 6px 18px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }
    .message-status.unread {
        background: #dc3545;
        color: white;
    }
    .message-status.read {
        background: #0d6efd;
        color: white;
    }
    .message-status.replied {
        background: #198754;
        color: white;
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
    .message-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    .message-meta .meta-item {
        display: flex;
        flex-direction: column;
    }
    .message-meta .meta-item .label {
        font-size: 12px;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .message-meta .meta-item .value {
        font-size: 14px;
        color: #212529;
        margin-top: 2px;
    }
    .reply-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 25px;
        border: 1px solid #e9ecef;
        margin-top: 20px;
    }
    .reply-section .reply-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }
    .reply-section .reply-header .reply-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #198754;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .reply-content {
        background: #fff;
        border-radius: 8px;
        padding: 15px;
        border-left: 4px solid #198754;
        margin-top: 10px;
        line-height: 1.8;
        white-space: pre-wrap;
    }
    .reply-meta {
        font-size: 12px;
        color: #6c757d;
        margin-top: 10px;
    }
    .reply-form textarea {
        border-radius: 8px;
        min-height: 120px;
        resize: vertical;
    }
    .reply-form textarea:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
    }
    .action-btn-group {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .action-btn-group .btn {
        padding: 8px 18px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-envelope me-2"></i> যোগাযোগ বার্তা বিস্তারিত
                <span class="badge bg-primary ms-2">#{{ $message->id }}</span>
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> ফিরে যান
                    </a>
                    <button class="btn btn-danger btn-sm delete-message" data-id="{{ $message->id }}" data-name="{{ $message->name }}">
                        <i class="fas fa-trash"></i> ডিলিট
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Main Content -->
                <div class="col-md-8">
                    <!-- Message Detail -->
                    <div class="message-detail-card">
                        <div class="message-header">
                            <div class="sender-info">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($message->name) }}&size=60&background=0d6efd&color=fff&rounded=true"
                                     alt="{{ $message->name }}"
                                     class="sender-avatar">
                                <div>
                                    <div class="sender-name">{{ $message->name }}</div>
                                    <div class="sender-email">
                                        <i class="fas fa-envelope"></i> {{ $message->email }}
                                        @if($message->phone)
                                            <span class="ms-2"><i class="fas fa-phone"></i> {{ $message->phone }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div>
                                <span class="message-status {{ $message->status }}">
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

                        @if($message->subject)
                            <div class="mb-3">
                                <h5><i class="fas fa-tag"></i> বিষয়: {{ $message->subject }}</h5>
                            </div>
                        @endif

                        <div class="message-body">
                            {{ $message->message }}
                        </div>

                        <div class="message-meta">
                            <div class="meta-item">
                                <span class="label"><i class="fas fa-calendar-alt"></i> প্রাপ্তির তারিখ</span>
                                <span class="value">{{ $message->formatted_date }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="label"><i class="fas fa-clock"></i> আগে</span>
                                <span class="value">{{ $message->time_ago }}</span>
                            </div>
                            @if($message->ip_address)
                                <div class="meta-item">
                                    <span class="label"><i class="fas fa-network-wired"></i> আইপি ঠিকানা</span>
                                    <span class="value">{{ $message->ip_address }}</span>
                                </div>
                            @endif
                            @if($message->user_agent)
                                <div class="meta-item">
                                    <span class="label"><i class="fas fa-laptop"></i> ব্রাউজার</span>
                                    <span class="value"><small>{{ Str::limit($message->user_agent, 50) }}</small></span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Reply Section -->
                    @if($message->status == 'replied' && $message->reply_message)
                        <div class="reply-section">
                            <div class="reply-header">
                                <div class="reply-icon">
                                    <i class="fas fa-reply"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">উত্তর প্রদান করা হয়েছে</h5>
                                    <small class="text-muted">
                                        @if($message->replier)
                                            <i class="fas fa-user"></i> {{ $message->replier->name }}
                                        @endif
                                        <i class="fas fa-clock ms-2"></i> {{ $message->replied_at?->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                            <div class="reply-content">
                                {{ $message->reply_message }}
                            </div>
                            <div class="reply-meta">
                                <i class="fas fa-calendar-alt"></i>
                                {{ $message->replied_at?->format('d M, Y h:i A') }}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-tools me-2"></i> অ্যাকশন</h5>
                        </div>
                        <div class="card-body">
                            <div class="action-btn-group">
                                @if($message->status != 'replied')
                                    <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#replyModal">
                                        <i class="fas fa-reply"></i> উত্তর দিন
                                    </button>
                                @endif
                                @if($message->status == 'unread')
                                    <button class="btn btn-info w-100 mark-read" data-id="{{ $message->id }}">
                                        <i class="fas fa-check-circle"></i> পঠিত চিহ্নিত করুন
                                    </button>
                                @endif
                                <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-arrow-left"></i> ফিরে যান
                                </a>
                                <button class="btn btn-danger w-100 delete-message" data-id="{{ $message->id }}" data-name="{{ $message->name }}">
                                    <i class="fas fa-trash"></i> ডিলিট করুন
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Message Info -->
                    <div class="card mt-3">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i> তথ্য</h5>
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
                                @if($message->status == 'replied' && $message->replied_at)
                                    <tr>
                                        <td><i class="fas fa-reply"></i> উত্তর</td>
                                        <td>{{ $message->replied_at->format('d M, Y h:i A') }}</td>
                                    </tr>
                                    @if($message->replier)
                                        <tr>
                                            <td><i class="fas fa-user"></i> উত্তরদাতা</td>
                                            <td>{{ $message->replier->name }}</td>
                                        </tr>
                                    @endif
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-reply"></i> উত্তর দিন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="replyForm">
                    @csrf
                    <input type="hidden" id="message_id" value="{{ $message->id }}">

                    <div class="mb-3">
                        <label><strong>প্রাপক:</strong></label>
                        <div class="p-2 bg-light rounded">
                            <i class="fas fa-user"></i> {{ $message->name }}
                            <span class="ms-3"><i class="fas fa-envelope"></i> {{ $message->email }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label><strong>মূল বার্তা:</strong></label>
                        <div class="p-3 bg-light rounded" style="max-height: 150px; overflow-y: auto; white-space: pre-wrap;">
                            {{ $message->message }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="required-field">আপনার উত্তর <span class="text-danger">*</span></label>
                        <textarea name="reply_message"
                                  id="reply_message"
                                  class="form-control @error('reply_message') is-invalid @enderror"
                                  rows="5"
                                  placeholder="আপনার উত্তর লিখুন..."
                                  required></textarea>
                        @error('reply_message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                    <small class="text-muted d-block mt-1">
                        <i class="fas fa-info-circle"></i>
                        চেক করলে ইমেইলেও উত্তর পাঠানো হবে
                    </small>
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> নিশ্চিত করুন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>আপনি কি <strong id="delete-name"></strong> এর পাঠানো বার্তাটি ডিলিট করতে চান?</p>
                <p class="text-danger"><small>⚠️ এই কাজটি অপরিবর্তনীয়! ডিলিট করার পর আর ফিরিয়ে আনা যাবে না।</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">
                    <i class="fas fa-trash"></i> ডিলিট
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let deleteId = null;

    // ============================================
    // Delete Message
    // ============================================
    $('.delete-message').click(function() {
        deleteId = $(this).data('id');
        var name = $(this).data('name');
        $('#delete-name').text(name);
        $('#deleteModal').modal('show');
    });

    $('#confirm-delete').click(function() {
        if (!deleteId) return;

        $.ajax({
            url: '{{ url("admin/contacts") }}/' + deleteId,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                $('#deleteModal').modal('hide');
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
                $('#deleteModal').modal('hide');
            }
        });
    });

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
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
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
    // Submit Reply
    // ============================================
    $('#submitReply').click(function() {
        var id = $('#message_id').val();
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
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
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

    // ============================================
    // Toastr Messages
    // ============================================
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif
});
</script>
@endpush
