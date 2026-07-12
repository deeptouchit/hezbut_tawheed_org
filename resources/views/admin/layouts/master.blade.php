<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.layouts.partials.head')

</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">

        {{-- Header Navbar --}}
        @include('admin.layouts.partials.header')

        {{-- Sidebar --}}
        @include('admin.layouts.partials.sidebar')

        {{-- App Main Content --}}
        <main class="app-main">

            {{-- App Content Header --}}
            <div class="app-content-header">
                <div class="container-fluid">
                     @yield('filter_input')
                </div>
            </div>

            {{-- App Content --}}
            <div class="app-content">
                  <div class="container-fluid">

                    {{-- Alert Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible px-3">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible px-3">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>
                            <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible px-3">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('warning') }}
                        </div>
                    @endif

                    @yield('content')

                </div>
            </div>

        </main>

        {{-- Footer --}}
        @include('admin.layouts.partials.footer')

    </div>

    @include('admin.layouts.partials.scripts')

    @stack('scripts')
</body>
</html>
