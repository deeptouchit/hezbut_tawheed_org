<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>হেজবুত তওহীদ | @yield('title', 'একটি অরাজনৈতিক আন্দোলন')</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'হেজবুত তওহীদ একটি অরাজনৈতিক আন্দোলন যা মানবতার কল্যাণ এবং প্রকৃত সত্য তুলে ধরে।')">
    @yield('seo_meta')

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset($setting->getSetting('favicon', 'themes/default/images/favicon.png')) }}">

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

    <!-- @fontsource/source-sans-3 (Modern) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('themes/default/css/style.css') }}">

    <!-- Global Font Fix -->
    <style>
        body, h1, h2, h3, h4, h5, h6, p, a, span, li, div, button, input, textarea {
            font-family: 'Baloo Da 2', 'Hind Siliguri', sans-serif;
        }
        
        .page-body, .page-body *, 
        .reader-text, .reader-text *, 
        .post-card-excerpt, .post-card-title,
        .research-section, .research-section *,
        .train-section, .train-section *,
        .soc-section, .soc-section * {
            font-family: 'Baloo Da 2', 'Hind Siliguri', sans-serif !important;
        }
        
        .fa, .fab, .far, .fas, i, i * {
            font-family: 'Font Awesome 6 Free', 'Font Awesome 6 Brands' !important;
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
    <script src="{{ asset('themes/default/js/main.js') }}"></script>

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
