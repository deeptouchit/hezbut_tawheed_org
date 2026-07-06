<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="color-scheme" content="light dark">
<meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)">
<meta name="description" content="@yield('meta_description', config('app.name') . ' - Admin Dashboard')">
<meta name="author" content="{{ config('app.name') }}">
<meta name="keywords" content="admin, dashboard, ecommerce, laravel, adminlte">
<meta name="robots" content="noindex, nofollow">



<title>@yield('page-title') | {{ $setting->getSetting('company_name_english') }}</title>
@if($setting->getSetting('favicon'))
    <link rel="shortcut icon" href="{{ asset($setting->getSetting('favicon')) }}">
    <link rel="icon" href="{{ asset($setting->getSetting('favicon')) }}">
@endif
<!-- ============================================= -->
<!-- PRELOAD & DNS PREFETCH (Performance) -->
<!-- ============================================= -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="dns-prefetch" href="//cdn.jsdelivr.net">
<link rel="dns-prefetch" href="//code.ionicframework.com">
<link rel="preload" href="{{ asset('backend/css/adminlte.css') }}" as="style">
<link rel="preload" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}" as="style">

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

<!-- ============================================= -->
<!-- CSS LIBRARIES -->
<!-- ============================================= -->
<!-- OverlayScrollbars (For custom scrollbar) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous">

<!-- Ionicons (Alternative Icons) -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

<!-- Font Awesome 6 (Free) -->
<link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">

<!-- ============================================= -->
<!-- ADMINLTE CORE CSS -->
<!-- ============================================= -->
<!-- AdminLTE CSS (Custom compiled) -->
<link rel="stylesheet" href="{{ asset('backend/css/adminlte.css') }}">

<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- Select2 CSS (Only CSS, JS will be in scripts) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- jQuery UI CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">

<!-- ============================================= -->
<!-- CUSTOM CSS (Production Optimized) -->
<!-- ============================================= -->
<style>
    /* =============================================
       CSS VARIABLES
       ============================================= */
    :root {
        --primary-color    : #007bff;
        --primary-hover    : #0056b3;
        --secondary-color  : #6c757d;
        --success-color    : #28a745;
        --danger-color     : #dc3545;
        --warning-color    : #ffc107;
        --info-color       : #17a2b8;
        --dark-color       : #343a40;
        --light-color      : #f8f9fa;
        --sidebar-width    : 250px;
        --sidebar-collapsed: 70px;
        --header-height    : 60px;
        --transition-speed : 0.3s;
    }

    /* =============================================
       BASE BODY STYLES
       ============================================= */
    body {
        font-family     : "Baloo Da 2", "Baloo Da 2", "Source Sans Pro", sans-serif;
        font-weight     : 400;
        line-height     : 1.6;
        background-color: #f4f6f9;
        overflow-x      : hidden;
    }

    /* Bengali text optimization */
    .bn-text, [lang="bn"] {
        font-family   : "Baloo Da 2", "Baloo Da 2", "Source Sans Pro", sans-serif;
        font-weight   : 500;
        letter-spacing: 0.5px;
    }

    .sidebar-brand {
        align-items: normal;
        justify-content: normal;
    }
</style>

<!-- ============================================= -->
<!-- ADDITIONAL DYNAMIC STYLES -->
<!-- ============================================= -->
@stack('styles')


