@extends('admin.layouts.master')

@section('page-title', 'ইউজার এডিট')

@push('styles')
    <style>
        .user-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .user-card .card-header {
            background: #ffffff;
            border-bottom: 1px solid #edf2f7;
            padding: 1.25rem 1.5rem;
        }

        .user-card .card-header .header-icon {
            width: 42px;
            height: 42px;
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 600;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 1.25rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.4rem;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 0.6rem 0.9rem;
            font-size: 0.9rem;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .input-group-text {
            border-radius: 8px 0 0 8px;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            color: #64748b;
        }

        .input-group .form-control {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .input-group .btn-toggle-eye {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
            border: 1px solid #cbd5e1;
            border-left: none;
            background-color: #f8fafc;
            color: #64748b;
            transition: all 0.2s;
        }

        .input-group .btn-toggle-eye:hover {
            background-color: #e2e8f0;
            color: #1e293b;
        }

        .card-footer-custom {
            background: #f8fafc;
            border-top: 1px solid #edf2f7;
            padding: 1.25rem 1.5rem;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-3">
        <div class="card user-card">
            <!-- Header -->
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon me-3">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 text-dark fw-bold" style="font-size: 1.2rem;">ইউজার এডিট: {{ $user->name }}</h5>
                        <small class="text-muted">ব্যবহারকারীর তথ্য ও সিকিউরিটি আপডেট করুন</small>
                    </div>
                </div>
                <div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm px-3"
                        style="border-radius: 6px;">
                        <i class="fas fa-arrow-left me-1"></i> ফিরে যান
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body p-4">
                    <!-- Section 1: Personal Info -->
                    <div class="mb-4">
                        <div class="section-title">
                            <i class="fas fa-id-card text-primary"></i>
                            <span>ব্যক্তিগত ও যোগাযোগ তথ্য</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name" class="form-label">পূর্ণ নাম <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $user->name) }}"
                                            placeholder="ব্যবহারকারীর নাম" required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email" class="form-label">ইমেইল ঠিকানা <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $user->email) }}"
                                            placeholder="example@domain.com" required>
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone" class="form-label">ফোন নম্বর <small
                                            class="text-muted">(ঐচ্ছিক)</small></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                            placeholder="017XXXXXXXX">
                                        @error('phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Password Update (Optional) -->
                    <div class="mb-4">
                        <div class="section-title d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <i class="fas fa-lock text-warning"></i>
                                <span>পাসওয়ার্ড পরিবর্তন <small class="text-muted font-normal fw-normal">(পরিবর্তন করতে না
                                        চাইলে খালি রাখুন)</small></span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary generate-password-btn"
                                style="border-radius: 6px; font-size: 0.8rem;">
                                <i class="fas fa-magic me-1"></i> অটো পাসওয়ার্ড জেনারেট করুন
                            </button>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label">নতুন পাসওয়ার্ড</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" placeholder="নতুন পাসওয়ার্ড (ঐচ্ছিক)">
                                        <button class="btn btn-outline-secondary generate-password-btn" type="button"
                                            title="অটো পাসওয়ার্ড জেনারেট করুন">
                                            <i class="fas fa-magic text-primary me-1"></i> জেনারেট
                                        </button>
                                        <button class="btn btn-toggle-eye toggle-password" type="button"
                                            data-target="password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('password')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">পাসওয়ার্ড নিশ্চিত করুন</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" placeholder="পাসওয়ার্ড পুনরায় লিখুন">
                                        <button class="btn btn-toggle-eye toggle-password" type="button"
                                            data-target="password_confirmation">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Role & Status -->
                    <div class="mb-3">
                        <div class="section-title">
                            <i class="fas fa-user-shield text-success"></i>
                            <span>রোল ও অ্যাকাউন্ট সেটিংস</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role" class="form-label">ইউজার রোল <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                        <select class="form-control form-select @error('role') is-invalid @enderror"
                                            id="role" name="role" required>
                                            <option value="">-- রোল নির্বাচন করুন --</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}"
                                                    {{ old('role', $user->getRoleNames()->first()) == $role->name ? 'selected' : '' }}>
                                                    {{ ucfirst($role->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label">অ্যাকাউন্ট স্ট্যাটাস <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                                        <select class="form-control form-select @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                            <option value="active"
                                                {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>সক্রিয়
                                                (Active)</option>
                                            <option value="inactive"
                                                {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>
                                                নিষ্ক্রিয় (Inactive)</option>
                                            <option value="suspended"
                                                {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>
                                                সাসপেন্ডেড (Suspended)</option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer Buttons -->
                <div class="card-footer-custom d-flex align-items-center justify-content-end gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light px-4 text-secondary me-2"
                        style="border-radius: 8px;">
                        <i class="fas fa-times me-1"></i> বাতিল
                    </a>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold"
                        style="border-radius: 8px; box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);">
                        <i class="fas fa-save me-1"></i> আপডেট করুন
                    </button>
                </div>
            </form>
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
                var icon = $(this).find('i');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Auto-generate strong password
            function generateStrongPassword(length) {
                length = length || 12;
                var upper = "ABCDEFGHJKLMNPQRSTUVWXYZ";
                var lower = "abcdefghijkmnopqrstuvwxyz";
                var numbers = "23456789";
                var symbols = "!@#$%^&*";
                var allChars = upper + lower + numbers + symbols;

                var password = "";
                password += upper.charAt(Math.floor(Math.random() * upper.length));
                password += lower.charAt(Math.floor(Math.random() * lower.length));
                password += numbers.charAt(Math.floor(Math.random() * numbers.length));
                password += symbols.charAt(Math.floor(Math.random() * symbols.length));

                for (var i = 4; i < length; i++) {
                    password += allChars.charAt(Math.floor(Math.random() * allChars.length));
                }

                return password.split('').sort(function() {
                    return 0.5 - Math.random();
                }).join('');
            }

            $('.generate-password-btn').on('click', function() {
                var newPassword = generateStrongPassword(12);

                $('#password').val(newPassword).attr('type', 'text');
                $('#password_confirmation').val(newPassword).attr('type', 'text');

                // Switch eye icon to eye-slash since text is visible
                $('.toggle-password').find('i').removeClass('fa-eye').addClass('fa-eye-slash');

                if (typeof toastr !== 'undefined') {
                    toastr.success('অটো পাসওয়ার্ড জেনারেট করা হয়েছে!');
                }
            });
        });
    </script>
@endpush
