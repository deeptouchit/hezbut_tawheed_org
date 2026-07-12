@extends('admin.layouts.master')

@section('page-title', 'নতুন ক্যাম্পেইন তৈরি')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-plus-circle me-2"></i> নতুন ক্যাম্পেইন তৈরি
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.newsletter.campaigns.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> ক্যাম্পেইন তালিকায় ফিরুন
                </a>
            </div>
        </div>

        <form action="{{ route('admin.newsletter.campaigns.store') }}" method="POST" id="campaignForm">
            @csrf

            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Campaign Information -->
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-info-circle"></i> ক্যাম্পেইন তথ্য
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>বিষয় <span class="text-danger">*</span></label>
                                    <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                                           value="{{ old('subject') }}" placeholder="ইমেইলের বিষয়" required>
                                    @error('subject') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label>শিরোনাম (ঐচ্ছিক)</label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                           value="{{ old('title') }}" placeholder="ক্যাম্পেইনের শিরোনাম">
                                    @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label>ইমেইল কন্টেন্ট <span class="text-danger">*</span></label>
                                    <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror"
                                              rows="10" placeholder="ইমেইলের কন্টেন্ট লিখুন...">{{ old('content') }}</textarea>
                                    @error('content') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Recipient Settings -->
                        <div class="card card-success card-outline">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-users"></i> প্রাপক সেটিংস
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>প্রাপকের ধরণ <span class="text-danger">*</span></label>
                                    <select name="recipient_type" id="recipient_type" class="form-control @error('recipient_type') is-invalid @enderror" required>
                                        <option value="all" {{ old('recipient_type') == 'all' ? 'selected' : '' }}>সব সাবস্ক্রাইবার</option>
                                        <option value="active_only" {{ old('recipient_type') == 'active_only' ? 'selected' : '' }}>সক্রিয় সাবস্ক্রাইবার</option>
                                        <option value="selected" {{ old('recipient_type') == 'selected' ? 'selected' : '' }}>নির্বাচিত ইমেইল</option>
                                    </select>
                                    @error('recipient_type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group" id="selected_emails_group" style="display: none;">
                                    <label>নির্বাচিত ইমেইল</label>
                                    <select name="selected_emails[]" id="selected_emails" class="form-control select2" multiple>
                                        @foreach($subscribers ?? [] as $subscriber)
                                            <option value="{{ $subscriber->email }}" {{ in_array($subscriber->email, old('selected_emails', [])) ? 'selected' : '' }}>
                                                {{ $subscriber->email }} ({{ $subscriber->name ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">একাধিক ইমেইল সিলেক্ট করতে পারেন</small>
                                </div>

                                <div class="form-group">
                                    <label>মোট প্রাপক</label>
                                    <input type="text" id="total_recipients_display" class="form-control" readonly disabled>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Settings -->
                        <div class="card card-warning card-outline mt-3">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-clock"></i> সময় নির্ধারণ
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>শিডিউল সময় (ঐচ্ছিক)</label>
                                    <input type="datetime-local" name="scheduled_at" class="form-control @error('scheduled_at') is-invalid @enderror"
                                           value="{{ old('scheduled_at') }}">
                                    <small class="text-muted">ফাঁকা রাখলে ড্রাফট হিসেবে সংরক্ষণ হবে</small>
                                    @error('scheduled_at') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Stats Preview -->
                        <div class="card card-info card-outline mt-3">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-chart-bar"></i> পরিসংখ্যান
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center">মোট সাবস্ক্রাইবার</span>
                                        <span class="info-box-number text-center">{{ $totalSubscribers ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center">সক্রিয় সাবস্ক্রাইবার</span>
                                        <span class="info-box-number text-center">{{ $activeSubscribers ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn">
                    <i class="fas fa-save"></i> ক্যাম্পেইন সংরক্ষণ করুন
                </button>
                <a href="{{ route('admin.newsletter.campaigns.index') }}" class="btn btn-secondary btn-lg px-4">
                    <i class="fas fa-times"></i> বাতিল
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')

<script>
$(document).ready(function() {
    // CKEditor for content
    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: ['heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', 'link', 'undo', 'redo'],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
                ]
            }
        })
        .catch(error => console.error(error));

    // Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        placeholder: 'ইমেইল নির্বাচন করুন',
        allowClear: true
    });

    // Recipient type change handler
    $('#recipient_type').on('change', function() {
        var type = $(this).val();
        if (type == 'selected') {
            $('#selected_emails_group').show();
        } else {
            $('#selected_emails_group').hide();
        }
        updateTotalRecipients();
    }).trigger('change');

    // Update total recipients
    function updateTotalRecipients() {
        var type = $('#recipient_type').val();
        var total = 0;

        if (type == 'all') {
            total = {{ $totalSubscribers ?? 0 }};
        } else if (type == 'active_only') {
            total = {{ $activeSubscribers ?? 0 }};
        } else if (type == 'selected') {
            total = $('#selected_emails').val()?.length || 0;
        }

        $('#total_recipients_display').val(total + ' জন');
    }

    $('#selected_emails').on('change', function() {
        updateTotalRecipients();
    });

    // Form submit loading state
    $('#campaignForm').on('submit', function() {
        $('#submitBtn').html('<i class="fas fa-spinner fa-spin"></i> সংরক্ষণ হচ্ছে...').prop('disabled', true);
    });
});
</script>
@endpush
