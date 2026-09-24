<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $setting->getSetting('site_name', 'হেযবুত তওহীদ') }} | @yield('title', $setting->getSetting('company_tagline', 'একটি অরাজনৈতিক ধর্মীয় সংস্কারমূলক আন্দোলন'))</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', $setting->getSetting('meta_description', 'হেযবুত তওহীদ একটি অরাজনৈতিক ধর্মীয় সংস্কারমূলক আন্দোলন যা মানবতার কল্যাণ এবং প্রকৃত সত্য তুলে ধরে।'))">
    @yield('seo_meta')

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset($setting->getSetting('favicon', 'themes/hezbut-tawheed/images/favicon.png')) }}">

    <!-- CSS Frameworks -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

   <!-- ============================================= -->
    <!-- FONTS -->
    <!-- ============================================= -->
    <!-- Google Font: Source Sans Pro (Fallback) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,600,700,800&display=fallback">

    <!-- Google Font: Baloo Da 2 (Bengali Support) -->
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Da+2:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Google Font: Baloo Da 2 (Bengali Alternative) -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- SolaimanLipi Font (modern Style) -->
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">

    <!-- Custom CSS (Minified & Cache Busted for Production) -->
    <link rel="stylesheet" href="{{ asset('themes/hezbut-tawheed/css/style.min.css') }}?v={{ filemtime(public_path('themes/hezbut-tawheed/css/style.min.css')) }}">

    <!-- Global Font Fix (Configured dynamically from the admin settings) -->
    <style>
        :root {
            --font-bengali: '{{ $setting->getSetting("primary_font", "SolaimanLipi") }}', '{{ $setting->getSetting("secondary_font", "Baloo Da 2") }}', sans-serif !important;
        }

        @font-face {
            font-family: 'Li Ador Noirrit';
            src: url('/Li_Ador_Noirrit_Unicode.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        /* Force configured primary/secondary font on all elements globally */
        *:not(i):not(.fa):not(.fab):not(.far):not(.fas):not([class*="fa-"]) {
            font-family: var(--font-bengali) !important;
        }

        /* Protect FontAwesome icons from being overridden */
        .fa, .fab, .far, .fas, [class*="fa-"], .fa::before, .fab::before, .far::before, .fas::before, i, i::before {
            font-family: 'Font Awesome 6 Free', 'Font Awesome 6 Brands', sans-serif !important;
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Header / Navbar -->
    @include('theme::partials.header-main')

    <!-- Main Content Area -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('theme::partials.footer')

    <!-- Scroll to Top -->
    <button class="scroll-to-top" id="scrollToTopBtn" aria-label="Scroll to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- JavaScript Frameworks -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Theme Custom JS -->
    <script src="{{ asset('themes/hezbut-tawheed/js/main.js') }}"></script>

    <!-- Toastr configuration -->
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        @if(Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif
        @if(Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif
        @if(Session::has('info'))
            toastr.info("{{ Session::get('info') }}");
        @endif
        @if(Session::has('warning'))
            toastr.warning("{{ Session::get('warning') }}");
        @endif
    </script>

    @stack('scripts')
</body>
</html>
