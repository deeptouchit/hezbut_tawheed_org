@extends('admin.layouts.master')

@section('page-title', 'Change Password')

@section('content')
<div class="container-fluid">
    <div class="card card-warning card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-key"></i> Change Password
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.profile.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Step 1: Verify Current Password -->
            <div id="step1">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> First, verify your current password. OTP will be sent to your email.
                </div>
                <div class="form-group mb-3">
                    <label>Current Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" id="current_password" class="form-control" placeholder="Enter your current password">
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-primary" id="sendOtpBtn">
                        <i class="fas fa-paper-plane"></i> Send OTP
                    </button>
                </div>
            </div>

            <!-- Step 2: Verify OTP and Set New Password (Hidden Initially) -->
            <div id="step2" style="display: none;">
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> OTP sent to your email. Valid for 10 minutes.
                </div>
                <div class="form-group mb-3">
                    <label>OTP Code <span class="text-danger">*</span></label>
                    <input type="text" id="otp" class="form-control" placeholder="Enter 6-digit OTP" maxlength="6">
                </div>
                <div class="form-group mb-3">
                    <label>New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" id="new_password" class="form-control" placeholder="Enter new password">
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <small class="text-muted">Minimum 8 characters</small>
                </div>
                <div class="form-group mb-3">
                    <label>Confirm New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" id="new_password_confirmation" class="form-control" placeholder="Confirm new password">
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password_confirmation">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-warning" id="resendOtpBtn">
                        <i class="fas fa-redo"></i> Resend OTP
                    </button>
                    <button type="button" class="btn btn-success" id="changePasswordBtn">
                        <i class="fas fa-save"></i> Change Password
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle password visibility
    $('.toggle-password').on('click', function() {
        var target = $(this).data('target');
        var input = $('#' + target);
        var type = input.attr('type') === 'password' ? 'text' : 'password';
        input.attr('type', type);
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    // Send OTP
    $('#sendOtpBtn').on('click', function() {
        var currentPassword = $('#current_password').val();
        var btn = $(this);

        if (!currentPassword) {
            toastr.error('Please enter your current password');
            return;
        }

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');

        $.ajax({
            url: '{{ route("admin.password.send-otp") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                current_password: currentPassword
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#step1').hide();
                    $('#step2').show();
                    $('#current_password').val('');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                var message = xhr.responseJSON?.message || 'Failed to send OTP';
                toastr.error(message);
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Send OTP');
            }
        });
    });

    // Resend OTP
    $('#resendOtpBtn').on('click', function() {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Resending...');

        $.ajax({
            url: '{{ route("admin.password.send-otp") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                current_password: $('#current_password').val()
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                var message = xhr.responseJSON?.message || 'Failed to resend OTP';
                toastr.error(message);
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-redo"></i> Resend OTP');
            }
        });
    });

    // Change Password
    $('#changePasswordBtn').on('click', function() {
        var otp = $('#otp').val();
        var newPassword = $('#new_password').val();
        var newPasswordConfirmation = $('#new_password_confirmation').val();
        var btn = $(this);

        if (!otp) {
            toastr.error('Please enter OTP');
            return;
        }

        if (!newPassword) {
            toastr.error('Please enter new password');
            return;
        }

        if (newPassword !== newPasswordConfirmation) {
            toastr.error('New password and confirmation do not match');
            return;
        }

        if (newPassword.length < 8) {
            toastr.error('Password must be at least 8 characters');
            return;
        }

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Changing...');

        $.ajax({
            url: '{{ route("admin.password.verify") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                otp: otp,
                new_password: newPassword,
                new_password_confirmation: newPasswordConfirmation
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.logout") }}';
                    }, 2000);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                var message = xhr.responseJSON?.message || 'Failed to change password';
                toastr.error(message);
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Change Password');
            }
        });
    });
});
</script>
@endpush
