<!DOCTYPE html>
<html lang="bn" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>লগইন - {{ $setting->getSetting('company_name', '') }}</title>

    @if($setting->getSetting('favicon'))
        <link rel="shortcut icon" href="{{ asset($setting->getSetting('favicon')) }}">
        <link rel="icon" href="{{ asset($setting->getSetting('favicon')) }}">
    @endif

    <!-- SEO Meta Tags -->
    <meta name="description" content="অ্যাডমিন ড্যাশবোর্ড লগইন - হেজবুত তওহীদ">
    <meta name="robots" content="noindex, nofollow">

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* =============================================
           CSS VARIABLES (Brand theme and mode support)
           ============================================= */
        :root {
            /* Light Mode */
            --primary: #006A4E;
            --primary-dark: #004D38;
            --primary-light: #D4AF37;
            --primary-gradient: linear-gradient(135deg, #004D38 0%, #006A4E 50%, #D4AF37 100%);
            --success: #10b981;
            --error: #ef4444;
            --warning: #f59e0b;
            --info: #06b6d4;

            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --text-light: #cbd5e1;

            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-card: rgba(255, 255, 255, 0.75);

            --border-light: rgba(226, 232, 240, 0.8);
            --border-medium: #cbd5e1;

            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.07);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.05);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.05);
            --shadow-2xl: 0 25px 50px -12px rgb(0 0 0 / 0.05);

            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-normal: 300ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 500ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Dark Mode Support */
        @media (prefers-color-scheme: dark) {
            :root {
                --text-primary: #f8fafc;
                --text-secondary: #cbd5e1;
                --text-muted: #64748b;
                --text-light: #334155;

                --bg-primary: #090d16;
                --bg-secondary: #111827;
                --bg-card: rgba(17, 24, 39, 0.55);

                --border-light: rgba(51, 65, 85, 0.5);
                --border-medium: #475569;

                --shadow-2xl: 0 25px 50px -12px rgb(0 0 0 / 0.4);
            }
        }

        /* =============================================
           RESET & BASE STYLES
           ============================================= */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
           font-family     : "Baloo Da 2", "Baloo Da 2", "Source Sans Pro", sans-serif;
            background: var(--bg-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            color: var(--text-primary);
        }

        /* Background Pattern Grid */
        .bg-pattern {
            position: fixed;
            inset: 0;
            z-index: 0;
            opacity: 0.3;
            background-image: radial-gradient(var(--border-medium) 1px, transparent 1px);
            background-size: 24px 24px;
            pointer-events: none;
        }

        /* Floating Gradient Blobs */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.12;
            z-index: 0;
            pointer-events: none;
        }

        .blob-1 {
            top: -10%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: var(--primary);
            animation: floatBlob1 25s infinite alternate ease-in-out;
        }

        .blob-2 {
            bottom: -10%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: #06b6d4;
            animation: floatBlob2 30s infinite alternate ease-in-out;
        }

        @keyframes floatBlob1 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(80px, 120px) scale(1.15); }
        }

        @keyframes floatBlob2 {
            0% { transform: translate(0, 0) scale(1.1); }
            100% { transform: translate(-100px, -80px) scale(0.9); }
        }

        /* =============================================
           ANIMATIONS
           ============================================= */
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.96) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        /* =============================================
           LOGIN CARD (Glassmorphic)
           ============================================= */
        .login-wrapper {
            width: 100%;
            max-width: 460px;
            margin: 20px;
            position: relative;
            z-index: 1;
            animation: fadeInScale 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .login-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 28px;
            padding: 3rem 2.5rem;
            box-shadow: var(--shadow-2xl);
            border: 1px solid var(--border-light);
            transition: transform var(--transition-normal), box-shadow var(--transition-normal);
        }

        .login-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.15),
                        0 0 80px -10px rgba(16, 185, 129, 0.08);
        }

        /* Header Section */
        .logo-section {
            text-align: center;
            margin-bottom: 2.25rem;
        }

        .logo {
            width: 240px;
            height: auto;
            border-radius: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;

            animation: float 4s ease-in-out infinite;
            overflow: hidden;
            padding: 10px;
        }

        .logo-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 8px;
        }

        .logo i {
            font-size: 2.35rem;
            color: white;
        }

        .logo-section h1 {
            font-size: 1.75rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .logo-section p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* =============================================
           FORM ELEMENTS
           ============================================= */
        .form-group {
            margin-bottom: 1.5rem;
            animation: slideInUp 0.5s ease-out both;
        }

        .form-group:nth-child(1) { animation-delay: 100ms; }
        .form-group:nth-child(2) { animation-delay: 200ms; }

        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .form-label i {
            font-size: 0.85rem;
            color: var(--text-secondary);
            transition: color var(--transition-fast);
        }

        .form-group:focus-within .form-label i {
            color: var(--primary);
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group .input-icon {
            position: absolute;
            left: 16px;
            color: var(--text-muted);
            font-size: 0.95rem;
            transition: color var(--transition-fast);
            pointer-events: none;
        }

        .form-group:focus-within .input-group .input-icon {
            color: var(--primary);
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.65rem;
            font-size: 0.95rem;
            font-family: inherit;
            background: var(--bg-secondary);
            border: 1.5px solid var(--border-light);
            border-radius: 14px;
            color: var(--text-primary);
            transition: all var(--transition-fast);
        }

        /* Dark mode inputs tweak */
        @media (prefers-color-scheme: dark) {
            .form-control {
                background: rgba(15, 23, 42, 0.4);
            }
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.12);
        }

        .form-control.is-invalid {
            border-color: var(--error);
            background-repeat: no-repeat;
            background-position: right 3.5rem center;
            background-size: 1.15rem;
            padding-right: 2.5rem;
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            cursor: pointer;
            color: var(--text-muted);
            transition: color var(--transition-fast);
            background: transparent;
            border: none;
            padding: 4px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            color: var(--primary);
        }

        .invalid-feedback {
            display: block;
            margin-top: 0.45rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--error);
            padding-left: 4px;
        }

        /* Form Options (Remember & Forgot Link) */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.75rem;
            animation: slideInUp 0.5s ease-out both;
            animation-delay: 250ms;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-secondary);
            user-select: none;
        }

        .checkbox-wrapper input {
            width: 17px;
            height: 17px;
            cursor: pointer;
            accent-color: var(--primary);
            border-radius: 4px;
        }

        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: color var(--transition-fast);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* =============================================
           SUBMIT BUTTON
           ============================================= */
        .btn-submit {
            font-family: inherit;
            width: 100%;
            padding: 0.85rem;
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 0.975rem;
            font-weight: 700;
            cursor: pointer;
            transition: all var(--transition-fast);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 8px 20px -6px rgba(16, 185, 129, 0.4);
            animation: slideInUp 0.5s ease-out both;
            animation-delay: 300ms;
        }

        /* Hover Shimmer Effect */
        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.25), transparent);
            transition: left 0.6s ease;
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -5px rgba(16, 185, 129, 0.5);
            filter: brightness(1.05);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Loading Spinner */
        .btn-submit.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-submit.loading .btn-icon {
            display: none;
        }

        .btn-submit.loading .spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* =============================================
           ALERTS
           ============================================= */
        .alert {
            padding: 0.9rem 1.1rem;
            border-radius: 14px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            border-left: 4px solid;
            animation: slideInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.08);
            border-color: rgba(239, 68, 68, 0.15) rgba(239, 68, 68, 0.15) rgba(239, 68, 68, 0.15) var(--error);
            color: var(--error);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.08);
            border-color: rgba(16, 185, 129, 0.15) rgba(16, 185, 129, 0.15) rgba(16, 185, 129, 0.15) var(--success);
            color: var(--success);
        }

        @media (prefers-color-scheme: dark) {
            .alert-danger {
                background: rgba(239, 68, 68, 0.15);
                color: #fca5a5;
            }
            .alert-success {
                background: rgba(16, 185, 129, 0.15);
                color: #a7f3d0;
            }
        }

        .alert i {
            font-size: 1.1rem;
        }

        /* =============================================
           FOOTER LINKS
           ============================================= */
        .register-link {
            text-align: center;
            margin-top: 1.75rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-light);
            animation: slideInUp 0.5s ease-out both;
            animation-delay: 350ms;
        }

        .register-link p {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .register-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
            transition: color var(--transition-fast);
        }

        .register-link a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Password Strength Bar Tweak */
        .password-strength {
            margin-top: 0.5rem;
            height: 4px;
            border-radius: 2px;
            background: var(--border-light);
            overflow: hidden;
            display: none; /* Only show on focus/input */
        }

        .password-strength-bar {
            width: 0%;
            height: 100%;
            transition: width var(--transition-normal), background-color var(--transition-normal);
        }

        /* =============================================
           RESPONSIVE ADJUSTMENTS
           ============================================= */
        @media (max-width: 480px) {
            .login-wrapper {
                margin: 16px;
            }

            .login-card {
                padding: 2.25rem 1.5rem;
                border-radius: 24px;
            }

            .logo-section h1 {
                font-size: 1.5rem;
            }

            .logo {

                border-radius: 18px;
            }

            .logo i {
                font-size: 2.1rem;
            }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }
    </style>
</head>

<body>
    <!-- Background Patterns -->
    <div class="bg-pattern"></div>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo">
                    @php
                        $loginLogo = $setting->getSetting('login_page_logo') ?: $setting->getSetting('company_logo');
                    @endphp
                    @if($loginLogo)
                        <img src="{{ asset($loginLogo) }}" alt="Logo" class="logo-img">
                    @else
                        <i class="fas fa-flag"></i>
                    @endif
                </div>
                <h1>{{ $setting->getSetting('company_name', '') }}</h1>
                <p>অ্যাডমিন প্যানেলে লগইন করুন</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>{{ session('status') }}</div>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('admin.login') }}" id="loginForm" novalidate>
                @csrf

                <!-- Email Field -->
                <div class="form-group">
                    <label class="form-label" for="email">
                        <i class="fas fa-envelope"></i>
                        ইমেইল এড্রেস
                    </label>
                    <div class="input-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email"
                               name="email"
                               id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="example@mail.com"
                               value="{{ old('email') }}"
                               required
                               autofocus>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label class="form-label" for="password">
                        <i class="fas fa-lock"></i>
                        পাসওয়ার্ড
                    </label>
                    <div class="input-group">
                        <i class="fas fa-key input-icon"></i>
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="••••••••"
                               required>
                        <button type="button" class="toggle-password" id="togglePassword" aria-label="Toggle Password Visibility">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                    </div>
                    <div class="password-strength" id="passwordStrength">
                        <div class="password-strength-bar"></div>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Options -->
                <div class="form-options">
                    <label class="checkbox-wrapper">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>মনে রাখুন</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            <i class="fas fa-question-circle"></i> পাসওয়ার্ড ভুলে গেছেন?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit" id="submitBtn">
                    <span class="spinner" style="display: none;"></span>
                    <i class="fas fa-arrow-right-to-bracket btn-icon"></i>
                    <span>লগইন করুন</span>
                </button>

                <!-- Register Link -->
                @if (Route::has('register'))
                    <div class="register-link">
                        <p>অ্যাকাউন্ট নেই? <a href="{{ route('register') }}">এখনই রেজিস্ট্রেশন করুন</a></p>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle Password Visibility
            $('#togglePassword').on('click', function() {
                const passwordInput = $('#password');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                $(this).find('i').toggleClass('fa-eye-slash fa-eye');
            });

            // Real-time Email Validation
            $('#email').on('input', function() {
                const email = $(this).val();
                const emailRegex = /^[^\s@]+@([^\s@.,]+\.)+[^\s@.,]{2,}$/;

                if (email && !emailRegex.test(email)) {
                    $(this).addClass('is-invalid');
                    if (!$(this).parent().siblings('.invalid-feedback').length && !$(this).siblings('.invalid-feedback').length) {
                        $(this).closest('.form-group').append('<div class="invalid-feedback email-error">সঠিক ইমেইল এড্রেস দিন</div>');
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).closest('.form-group').find('.email-error').remove();
                }
            });

            // Password Strength Indicator (Show on focus & input)
            $('#password').on('focus', function() {
                $('#passwordStrength').slideDown(200);
            });

            // Remove password strength indicator if input is empty
            $('#password').on('blur', function() {
                if ($(this).val().length === 0) {
                    $('#passwordStrength').slideUp(200);
                }
            });

            $('#password').on('input', function() {
                const password = $(this).val();
                const strengthBar = $('.password-strength-bar');
                let strength = 0;

                if (password.length > 0) {
                    if (password.length >= 6) strength += 25;
                    if (password.match(/[a-z]/)) strength += 25;
                    if (password.match(/[A-Z]/) || password.match(/[0-9]/)) strength += 25;
                    if (password.match(/[^a-zA-Z0-9]/)) strength += 25;
                }

                strengthBar.css('width', strength + '%');

                if (strength <= 25) {
                    strengthBar.css('background-color', '#ef4444'); // Red
                } else if (strength <= 50) {
                    strengthBar.css('background-color', '#f59e0b'); // Yellow
                } else if (strength <= 75) {
                    strengthBar.css('background-color', '#3b82f6'); // Blue
                } else {
                    strengthBar.css('background-color', '#10b981'); // Green
                }
            });

            // Form Submission with Loading State
            $('#loginForm').on('submit', function(e) {
                const email = $('#email').val();
                const password = $('#password').val();
                let hasError = false;

                // Clear previous invalid feed-backs
                $('.invalid-feedback.submit-error').remove();

                // Client-side validation
                if (!email) {
                    $('#email').addClass('is-invalid');
                    if (!$('#email').closest('.form-group').find('.invalid-feedback').length) {
                        $('#email').closest('.form-group').append('<div class="invalid-feedback submit-error">ইমেইল এড্রেস দিন</div>');
                    }
                    hasError = true;
                }

                if (!password) {
                    $('#password').addClass('is-invalid');
                    if (!$('#password').closest('.form-group').find('.invalid-feedback').length) {
                        $('#password').closest('.form-group').append('<div class="invalid-feedback submit-error">পাসওয়ার্ড দিন</div>');
                    }
                    hasError = true;
                }

                if (hasError) {
                    e.preventDefault();
                    return false;
                }

                const btn = $('#submitBtn');
                btn.addClass('loading');
                btn.find('.spinner').show();
                btn.find('span:not(.spinner)').text('লগইন হচ্ছে...');
                btn.prop('disabled', true);
            });

            // Remove invalid feedback on focus/input
            $('.form-control').on('focus input', function() {
                $(this).removeClass('is-invalid');
                $(this).closest('.form-group').find('.invalid-feedback').remove();
            });
        });

        // Reset submit button state on page show (e.g. going back in history)
        window.addEventListener('pageshow', function() {
            const btn = $('#submitBtn');
            btn.removeClass('loading');
            btn.find('.spinner').hide();
            btn.find('.btn-icon').show();
            btn.find('span:not(.spinner)').text('লগইন করুন');
            btn.prop('disabled', false);
        });
    </script>
</body>

</html>


