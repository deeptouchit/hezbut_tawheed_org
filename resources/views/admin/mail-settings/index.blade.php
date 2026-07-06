@extends('admin.layouts.master')

@section('page-title', 'ইমেইল সেটিংস')

@section('filter_input')
    <div class="row px-3">
        <div class="col-md-12 mb-3">
            <div class="btn-group w-100">
                <button type="button" id="reset-filters" class="btn btn-secondary">
                    <i class="fas fa-sync-alt"></i> রিসেট
                </button>
                <button type="button" class="btn btn-info" id="testConnectionBtn">
                    <i class="fas fa-plug"></i> কানেকশন টেস্ট
                </button>
                <button type="button" class="btn btn-warning" id="testMailBtn">
                    <i class="fas fa-paper-plane"></i> টেস্ট ইমেইল
                </button>
                <button type="button" id="saveSettingsBtn" class="btn btn-primary">
                    <i class="fas fa-save"></i> সংরক্ষণ করুন
                </button>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <form id="mailSettingsForm" action="{{ route('admin.email.update') }}" method="POST">
            @csrf

            <!-- Primary Mailbox Card -->
            <div class="card card-primary card-outline mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-inbox me-1"></i>
                        প্রাইমারি মেইলবক্স
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label for="MAIL_MAILER" class="form-label">মেইলার <span class="text-danger">*</span></label>
                                <select name="MAIL_MAILER" id="MAIL_MAILER" class="form-select" required>
                                    <option value="smtp" {{ env('MAIL_MAILER', 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="mail" {{ env('MAIL_MAILER') == 'mail' ? 'selected' : '' }}>Mail</option>
                                    <option value="sendmail" {{ env('MAIL_MAILER') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="log" {{ env('MAIL_MAILER') == 'log' ? 'selected' : '' }}>Log</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label for="MAIL_HOST" class="form-label">হোস্ট <span class="text-danger">*</span></label>
                                <input type="text" name="MAIL_HOST" id="MAIL_HOST" class="form-control"
                                    value="{{ old('MAIL_HOST', env('MAIL_HOST', 'smtp.gmail.com')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label for="MAIL_PORT" class="form-label">পোর্ট <span class="text-danger">*</span></label>
                                <input type="number" name="MAIL_PORT" id="MAIL_PORT" class="form-control"
                                    value="{{ old('MAIL_PORT', env('MAIL_PORT', 465)) }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label for="MAIL_ENCRYPTION" class="form-label">এনক্রিপশন <span class="text-danger">*</span></label>
                                <select name="MAIL_ENCRYPTION" id="MAIL_ENCRYPTION" class="form-select" required>
                                    <option value="ssl" {{ env('MAIL_ENCRYPTION', 'ssl') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="tls" {{ env('MAIL_ENCRYPTION') == 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="starttls" {{ env('MAIL_ENCRYPTION') == 'starttls' ? 'selected' : '' }}>STARTTLS</option>
                                    <option value="null" {{ env('MAIL_ENCRYPTION') == 'null' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="MAIL_USERNAME" class="form-label">ইউজারনেম (ইমেইল) <span class="text-danger">*</span></label>
                                <input type="email" name="MAIL_USERNAME" id="MAIL_USERNAME" class="form-control"
                                    value="{{ old('MAIL_USERNAME', env('MAIL_USERNAME')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="MAIL_PASSWORD" class="form-label">পাসওয়ার্ড <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="MAIL_PASSWORD" id="MAIL_PASSWORD" class="form-control"
                                        value="{{ old('MAIL_PASSWORD', env('MAIL_PASSWORD')) }}" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="MAIL_PASSWORD">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Gmail এর জন্য App Password ব্যবহার করুন</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="MAIL_FROM_ADDRESS" class="form-label">ফ্রম এড্রেস <span class="text-danger">*</span></label>
                                <input type="email" name="MAIL_FROM_ADDRESS" id="MAIL_FROM_ADDRESS"
                                    class="form-control" value="{{ old('MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="MAIL_FROM_NAME" class="form-label">ফ্রম নাম</label>
                                <input type="text" name="MAIL_FROM_NAME" id="MAIL_FROM_NAME" class="form-control"
                                    value="{{ old('MAIL_FROM_NAME', env('MAIL_FROM_NAME', env('APP_NAME'))) }}">
                                <small class="text-muted">যেমন: আপনার কোম্পানির নাম</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Mailbox Card -->
            <div class="card card-info card-outline mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-headset me-1"></i>
                        সাপোর্ট মেইলবক্স
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="MAIL_SUPPORT_HOST" class="form-label">হোস্ট <span class="text-danger">*</span></label>
                                <input type="text" name="MAIL_SUPPORT_HOST" id="MAIL_SUPPORT_HOST"
                                    class="form-control" value="{{ old('MAIL_SUPPORT_HOST', env('MAIL_SUPPORT_HOST', env('MAIL_HOST'))) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="MAIL_SUPPORT_PORT" class="form-label">পোর্ট <span class="text-danger">*</span></label>
                                <input type="number" name="MAIL_SUPPORT_PORT" id="MAIL_SUPPORT_PORT"
                                    class="form-control" value="{{ old('MAIL_SUPPORT_PORT', env('MAIL_SUPPORT_PORT', env('MAIL_PORT', 465))) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="MAIL_SUPPORT_ENCRYPTION" class="form-label">এনক্রিপশন <span class="text-danger">*</span></label>
                                <select name="MAIL_SUPPORT_ENCRYPTION" id="MAIL_SUPPORT_ENCRYPTION" class="form-select" required>
                                    <option value="ssl" {{ env('MAIL_SUPPORT_ENCRYPTION', env('MAIL_ENCRYPTION', 'ssl')) == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="tls" {{ env('MAIL_SUPPORT_ENCRYPTION') == 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="starttls" {{ env('MAIL_SUPPORT_ENCRYPTION') == 'starttls' ? 'selected' : '' }}>STARTTLS</option>
                                    <option value="null" {{ env('MAIL_SUPPORT_ENCRYPTION') == 'null' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="MAIL_SUPPORT_USERNAME" class="form-label">ইউজারনেম <span class="text-danger">*</span></label>
                                <input type="email" name="MAIL_SUPPORT_USERNAME" id="MAIL_SUPPORT_USERNAME"
                                    class="form-control" value="{{ old('MAIL_SUPPORT_USERNAME', env('MAIL_SUPPORT_USERNAME')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="MAIL_SUPPORT_PASSWORD" class="form-label">পাসওয়ার্ড <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="MAIL_SUPPORT_PASSWORD" id="MAIL_SUPPORT_PASSWORD"
                                        class="form-control" value="{{ old('MAIL_SUPPORT_PASSWORD', env('MAIL_SUPPORT_PASSWORD')) }}" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="MAIL_SUPPORT_PASSWORD">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="MAIL_SUPPORT_FROM_ADDRESS" class="form-label">ফ্রম এড্রেস <span class="text-danger">*</span></label>
                                <input type="email" name="MAIL_SUPPORT_FROM_ADDRESS" id="MAIL_SUPPORT_FROM_ADDRESS"
                                    class="form-control" value="{{ old('MAIL_SUPPORT_FROM_ADDRESS', env('MAIL_SUPPORT_FROM_ADDRESS')) }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Mailbox Card -->
            <div class="card card-warning card-outline mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-circle me-1"></i>
                        অ্যাকাউন্ট মেইলবক্স
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="MAIL_ACCOUNT_HOST" class="form-label">হোস্ট <span class="text-danger">*</span></label>
                                <input type="text" name="MAIL_ACCOUNT_HOST" id="MAIL_ACCOUNT_HOST"
                                    class="form-control" value="{{ old('MAIL_ACCOUNT_HOST', env('MAIL_ACCOUNT_HOST', env('MAIL_HOST'))) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="MAIL_ACCOUNT_PORT" class="form-label">পোর্ট <span class="text-danger">*</span></label>
                                <input type="number" name="MAIL_ACCOUNT_PORT" id="MAIL_ACCOUNT_PORT"
                                    class="form-control" value="{{ old('MAIL_ACCOUNT_PORT', env('MAIL_ACCOUNT_PORT', env('MAIL_PORT', 465))) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="MAIL_ACCOUNT_ENCRYPTION" class="form-label">এনক্রিপশন <span class="text-danger">*</span></label>
                                <select name="MAIL_ACCOUNT_ENCRYPTION" id="MAIL_ACCOUNT_ENCRYPTION" class="form-select" required>
                                    <option value="ssl" {{ env('MAIL_ACCOUNT_ENCRYPTION', env('MAIL_ENCRYPTION', 'ssl')) == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="tls" {{ env('MAIL_ACCOUNT_ENCRYPTION') == 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="starttls" {{ env('MAIL_ACCOUNT_ENCRYPTION') == 'starttls' ? 'selected' : '' }}>STARTTLS</option>
                                    <option value="null" {{ env('MAIL_ACCOUNT_ENCRYPTION') == 'null' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="MAIL_ACCOUNT_USERNAME" class="form-label">ইউজারনেম <span class="text-danger">*</span></label>
                                <input type="email" name="MAIL_ACCOUNT_USERNAME" id="MAIL_ACCOUNT_USERNAME"
                                    class="form-control" value="{{ old('MAIL_ACCOUNT_USERNAME', env('MAIL_ACCOUNT_USERNAME')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="MAIL_ACCOUNT_PASSWORD" class="form-label">পাসওয়ার্ড <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="MAIL_ACCOUNT_PASSWORD" id="MAIL_ACCOUNT_PASSWORD"
                                        class="form-control" value="{{ old('MAIL_ACCOUNT_PASSWORD', env('MAIL_ACCOUNT_PASSWORD')) }}" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="MAIL_ACCOUNT_PASSWORD">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="MAIL_ACCOUNT_FROM_ADDRESS" class="form-label">ফ্রম এড্রেস <span class="text-danger">*</span></label>
                                <input type="email" name="MAIL_ACCOUNT_FROM_ADDRESS" id="MAIL_ACCOUNT_FROM_ADDRESS"
                                    class="form-control" value="{{ old('MAIL_ACCOUNT_FROM_ADDRESS', env('MAIL_ACCOUNT_FROM_ADDRESS')) }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden submit button for form submission -->
            <button type="submit" id="hiddenSubmitBtn" style="display: none;"></button>

            <!-- Loading Overlay -->
            <div id="form-loading"
                style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; text-align: center; padding-top: 20%;">
                <div class="spinner-border text-light" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">পাঠানো হচ্ছে...</span>
                </div>
                <p class="text-light mt-2">সেটিংস সংরক্ষণ করা হচ্ছে...</p>
            </div>
        </form>
    </div>

    <!-- Test Email Modal -->
    <div class="modal fade" id="testEmailModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">
                        <i class="fas fa-paper-plane me-1"></i> টেস্ট ইমেইল
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ইমেইল এড্রেস <span class="text-danger">*</span></label>
                        <input type="email" id="test_email" class="form-control" placeholder="test@example.com" required>
                        <small class="text-muted">এই ইমেইল এড্রেসে টেস্ট মেইল পাঠানো হবে</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">মেইলবক্স সিলেক্ট করুন</label>
                        <select id="test_mailbox" class="form-select">
                            <option value="primary">প্রাইমারি মেইলবক্স</option>
                            <option value="support">সাপোর্ট মেইলবক্স</option>
                            <option value="account">অ্যাকাউন্ট মেইলবক্স</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="button" class="btn btn-info" id="sendTestEmail">
                        <i class="fas fa-paper-plane"></i> টেস্ট ইমেইল পাঠান
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Connection Test Modal -->
    <div class="modal fade" id="connectionTestModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">
                        <i class="fas fa-plug me-1"></i> কানেকশন টেস্ট
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">মেইলবক্স সিলেক্ট করুন</label>
                        <select id="test_connection_mailbox" class="form-select">
                            <option value="primary">প্রাইমারি মেইলবক্স</option>
                            <option value="support">সাপোর্ট মেইলবক্স</option>
                            <option value="account">অ্যাকাউন্ট মেইলবক্স</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="button" class="btn btn-info" id="testConnectionBtnModal">
                        <i class="fas fa-plug"></i> কানেকশন টেস্ট করুন
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Vanilla JavaScript for AdminLTE 4 (Bootstrap 5)
(function() {
    'use strict';

    // DOM Elements
    const form = document.getElementById('mailSettingsForm');
    const saveSettingsBtn = document.getElementById('saveSettingsBtn');
    const hiddenSubmitBtn = document.getElementById('hiddenSubmitBtn');
    const resetBtn = document.getElementById('reset-filters');
    const testMailBtn = document.getElementById('testMailBtn');
    const testConnectionBtn = document.getElementById('testConnectionBtn');
    const testConnectionModalBtn = document.getElementById('testConnectionBtnModal');
    const sendTestEmailBtn = document.getElementById('sendTestEmail');
    const loadingOverlay = document.getElementById('form-loading');

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            if (input) {
                const type = input.type === 'password' ? 'text' : 'password';
                input.type = type;
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            }
        });
    });

    // Reset filters
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (confirm('সব সেটিংস রিসেট করতে চান? পৃষ্ঠা রিলোড হবে।')) {
                location.reload();
            }
        });
    }

    // Save settings button - trigger form submit
    if (saveSettingsBtn) {
        saveSettingsBtn.addEventListener('click', function() {
            if (form) {
                // Create and dispatch submit event
                const submitEvent = new Event('submit', { bubbles: true, cancelable: true });
                form.dispatchEvent(submitEvent);
            }
        });
    }

    // Form submit handler
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Show loading state on save button
            if (saveSettingsBtn) {
                saveSettingsBtn.disabled = true;
                saveSettingsBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> সংরক্ষণ করা হচ্ছে...';
            }

            if (loadingOverlay) loadingOverlay.style.display = 'block';

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(data.message, 'সফল!', { closeButton: true, progressBar: true, timeOut: 5000 });
                    } else {
                        alert(data.message);
                    }
                    setTimeout(() => location.reload(), 2000);
                } else {
                    const errorMsg = data.message || 'আপডেট করতে ব্যর্থ হয়েছে';
                    if (typeof toastr !== 'undefined') {
                        toastr.error(errorMsg, 'ত্রুটি!');
                    } else {
                        alert(errorMsg);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof toastr !== 'undefined') {
                    toastr.error('আপডেট করতে ব্যর্থ হয়েছে', 'ত্রুটি!');
                } else {
                    alert('আপডেট করতে ব্যর্থ হয়েছে');
                }
            })
            .finally(() => {
                if (saveSettingsBtn) {
                    saveSettingsBtn.disabled = false;
                    saveSettingsBtn.innerHTML = '<i class="fas fa-save"></i> সংরক্ষণ করুন';
                }
                if (loadingOverlay) loadingOverlay.style.display = 'none';
            });
        });
    }

    // Test email modal trigger
    if (testMailBtn) {
        testMailBtn.addEventListener('click', function() {
            const testEmailInput = document.getElementById('test_email');
            if (testEmailInput) testEmailInput.value = '';
            const modalEl = document.getElementById('testEmailModal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        });
    }

    // Connection test modal trigger
    if (testConnectionBtn) {
        testConnectionBtn.addEventListener('click', function() {
            const modalEl = document.getElementById('connectionTestModal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        });
    }

    // Send test email
    if (sendTestEmailBtn) {
        sendTestEmailBtn.addEventListener('click', function() {
            const email = document.getElementById('test_email').value;
            const mailbox = document.getElementById('test_mailbox').value;
            const btn = this;

            if (!email) {
                if (typeof toastr !== 'undefined') toastr.error('ইমেইল এড্রেস দিন');
                else alert('ইমেইল এড্রেস দিন');
                return;
            }

            if (!validateEmail(email)) {
                if (typeof toastr !== 'undefined') toastr.error('সঠিক ইমেইল এড্রেস দিন');
                else alert('সঠিক ইমেইল এড্রেস দিন');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> পাঠানো হচ্ছে...';

            fetch('{{ route("admin.email.test") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ email: email, mailbox: mailbox })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'success', title: 'সফল!', text: data.message, confirmButtonColor: '#28a745' });
                    } else if (typeof toastr !== 'undefined') {
                        toastr.success(data.message);
                    } else {
                        alert(data.message);
                    }
                    const modalEl = document.getElementById('testEmailModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    const testEmailInput = document.getElementById('test_email');
                    if (testEmailInput) testEmailInput.value = '';
                } else {
                    const errorMsg = data.message || 'টেস্ট ইমেইল পাঠাতে ব্যর্থ হয়েছে';
                    if (typeof toastr !== 'undefined') toastr.error(errorMsg);
                    else alert(errorMsg);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof toastr !== 'undefined') toastr.error('টেস্ট ইমেইল পাঠাতে ব্যর্থ হয়েছে');
                else alert('টেস্ট ইমেইল পাঠাতে ব্যর্থ হয়েছে');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane"></i> টেস্ট ইমেইল পাঠান';
            });
        });
    }

    // Test connection
    const runConnectionTest = function(btn) {
        const mailbox = document.getElementById('test_connection_mailbox').value;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> টেস্ট হচ্ছে...';

        fetch('{{ route("admin.email.test.connection") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ mailbox: mailbox })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'success', title: 'সংযোগ সফল!', text: data.message, confirmButtonColor: '#28a745' });
                } else if (typeof toastr !== 'undefined') {
                    toastr.success(data.message);
                } else {
                    alert(data.message);
                }
                const modalEl = document.getElementById('connectionTestModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            } else {
                const errorMsg = data.message || 'সংযোগ ব্যর্থ হয়েছে';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'সংযোগ ব্যর্থ!', text: errorMsg, confirmButtonColor: '#dc3545' });
                } else if (typeof toastr !== 'undefined') {
                    toastr.error(errorMsg);
                } else {
                    alert(errorMsg);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof toastr !== 'undefined') toastr.error('কানেকশন টেস্ট করতে ব্যর্থ হয়েছে');
            else alert('কানেকশন টেস্ট করতে ব্যর্থ হয়েছে');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-plug"></i> কানেকশন টেস্ট করুন';
        });
    };

    if (testConnectionModalBtn) {
        testConnectionModalBtn.addEventListener('click', function() { runConnectionTest(this); });
    }

    // Email validation function
    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
})();
</script>
@endpush
