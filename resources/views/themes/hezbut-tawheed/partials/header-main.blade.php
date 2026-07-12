@php
// Allow overriding via query parameter for easy testing: ?header_style=classic or ?header_style=modern
$headerStyle = request()->query('header_style') ?? $setting->getSetting('header_style', 'modern');
@endphp

@if($headerStyle == 'classic')
@include('theme::partials.header-main-classic')
@else
@php
$headerBlogs = \App\Models\Blog::where('status', 1)->latest()->take(3)->get();
$topbarLeft = \App\Helpers\MenuHelper::getMenuData()['topbar_left'] ?? [];
$topbarRight = \App\Helpers\MenuHelper::getMenuData()['topbar_right'] ?? [];
@endphp

<!-- modern Style Header (Top Strip - Not Sticky) -->
<div class="ht-top-wrap bg-white border-bottom d-none d-lg-block ">
    <div class="container-fluid px-lg-5">
        <div class="d-flex align-items-center justify-content-between gap-4">
            <!-- Brand Logo (Left) -->
            <div class="flex-shrink-0">
                <a class="navbar-brand d-flex align-items-center gap-2 py-0" href="{{ route('home') }}">
                    @if($setting->getSetting('company_logo'))
                    <img src="{{ asset($setting->getSetting('company_logo')) }}" alt="Hezbut Tawheed Logo" style="height: 52px; width: auto; object-fit: contain;">
                    @else
                    <svg width="52" height="52" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="5" y="3" width="46" height="46" stroke="#1E293B" stroke-width="1.8" fill="#FFFFFF" />
                        <path d="M12 43 C12 23, 18 11, 28 11 C38 11, 44 23, 44 43" stroke="#009639" stroke-width="2.5" stroke-linecap="round" fill="none" />
                        <path d="M22 28 L32 23 L38 35 L28 40 Z" fill="#F8FAFC" stroke="#94A3B8" stroke-width="0.5" />
                        <path d="M20 29 L30 24 L36 36 L26 41 Z" fill="#D4AF37" stroke="#B45309" stroke-width="0.5" />
                        <path d="M22 28.5 L28 25.5 L34 34.5 L28 37.5 Z" fill="#009639" />
                        <circle cx="28" cy="31.5" r="2" fill="#D4AF37" />
                        <line x1="2" y1="50" x2="52" y2="50" stroke="#D4AF37" stroke-width="1.5" />
                        <line x1="2" y1="52" x2="48" y2="52" stroke="#D4AF37" stroke-width="1" />
                        <path d="M51 48 C52.5 48, 54 49.5, 54 51 C54 52.5, 52.5 54, 51 54 C52 53, 52.5 52, 52 51 C51.5 50, 51 49, 51 48 Z" fill="#D4AF37" />
                    </svg>
                    @endif
                </a>
            </div>

            <!-- Two Columns (Left Top Menu & Right Top Menu - Inline Row Layout) -->
            <div class="flex-grow-1 d-flex align-items-center justify-content-center gap-4">
                <!-- Column 1: Left Top Menu (Horizontal) -->
                <div style="font-family: var(--font-bengali); border-right: 1px solid #e2e8f0; padding-right: 1.5rem;">
                    <ul class="list-unstyled mb-0 d-flex align-items-center gap-3">
                        @foreach($topbarLeft as $item)
                        <li>
                            <a href="{{ \App\Helpers\MenuHelper::getMenuUrl($item) }}" class="text-decoration-none" style="font-size: 0.92rem; color: #475569; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='var(--primary-green)';" onmouseout="this.style.color='#475569';">
                                {{ $item['name'] ?? $item['text'] ?? '' }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Column 2: Right Top Menu (Horizontal & Social Circle Icons) -->
                <div style="font-family: var(--font-bengali);" class="d-flex align-items-center gap-3">
                    @php
                    $textItems = array_filter($topbarRight, function($item) {
                    return empty($item['icon']) && (!empty($item['name']) || !empty($item['text']));
                    });
                    $iconItems = array_filter($topbarRight, function($item) {
                    return !empty($item['icon']);
                    });
                    @endphp

                    @if(count($textItems) > 0)
                    <ul class="list-unstyled mb-0 d-flex align-items-center gap-3">
                        @foreach($textItems as $item)
                        <li>
                            <a href="{{ \App\Helpers\MenuHelper::getMenuUrl($item) }}" class="text-decoration-none" style="font-size: 0.92rem; color: #475569; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='var(--primary-green)';" onmouseout="this.style.color='#475569';">
                                {{ $item['name'] ?? $item['text'] ?? '' }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    <div style="width: 1px; height: 16px; background-color: #cbd5e1;"></div>
                    @endif

                    <!-- Circular Social Icons -->
                    <div class="d-flex align-items-center gap-2">
                        @foreach($iconItems as $item)
                        @php
                        // Map dynamic social keys to custom hover colors or keep them elegant
                        $name = strtolower($item['name'] ?? $item['text'] ?? '');
                        $iconColor = '#64748b';
                        $hoverBg = 'var(--primary-green)';

                        if (str_contains($name, 'facebook')) {
                        $iconColor = '#1877f2';
                        $hoverBg = '#1877f2';
                        } elseif (str_contains($name, 'youtube')) {
                        $iconColor = '#ff0000';
                        $hoverBg = '#ff0000';
                        } elseif (str_contains($name, 'twitter') || str_contains($name, 'x')) {
                        $iconColor = '#1da1f2';
                        $hoverBg = '#1da1f2';
                        } elseif (str_contains($name, 'instagram')) {
                        $iconColor = '#e1306c';
                        $hoverBg = '#e1306c';
                        } elseif (str_contains($name, 'linkedin')) {
                        $iconColor = '#0a66c2';
                        $hoverBg = '#0a66c2';
                        } elseif (str_contains($name, 'whatsapp')) {
                        $iconColor = '#25d366';
                        $hoverBg = '#25d366';
                        }
                        @endphp
                        <a href="{{ \App\Helpers\MenuHelper::getMenuUrl($item) }}" target="_blank" class="d-flex align-items-center justify-content-center rounded-circle text-decoration-none" style="width: 26px; height: 26px; background: #fff; border: 1px solid #dee2e6; color: {{ $iconColor }}; transition: all 0.2s ease;" onmouseover="this.style.background='{{ $hoverBg }}'; this.style.color='#fff'; this.style.borderColor='{{ $hoverBg }}';" onmouseout="this.style.background='#fff'; this.style.color='{{ $iconColor }}'; this.style.borderColor='#dee2e6';" title="{{ $item['name'] ?? $item['text'] ?? '' }}">
                            <i class="{{ $item['icon'] }}" style="font-size: 0.75rem;"></i>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modern Style Header (Bottom Navigation Row - Sticky Top) -->
<header class="ht-nav-wrap bg-white py-1 sticky-top shadow-sm d-none d-lg-block">
    <div class="container-fluid px-lg-5">
        <div class="d-flex align-items-center justify-content-between">
            <!-- Left: Navigation Links -->
            <nav class="navbar-ht" style="margin-left: 10px;">
                <ul class="nav-list-ht d-flex align-items-center list-unstyled mb-0 gap-0">
                    @php $firstMenu = true; @endphp
                    @foreach(\App\Helpers\MenuHelper::getNavbarMenu('desktop_nav') as $item)
                    @if(empty($item['children']))
                    @if($item['name'] != 'যোগদান করুন' && $item['name'] != 'যোগ দিন' && $item['name'] != 'Join Us' && $item['name'] != 'যুক্ত হোন')
                    <li>
                        <a class="nav-link-ht {{ \App\Helpers\MenuHelper::isActive($item) ? 'active' : '' }}" href="{{ \App\Helpers\MenuHelper::getMenuUrl($item) }}" {!! $firstMenu ? 'style="padding-left: 12px !important;"' : '' !!}>
                            <span>{{ $item['name'] }}</span>
                        </a>
                    </li>
                    @php $firstMenu = false; @endphp
                    @endif
                    @else
                    <li class="dropdown-ht">
                        <a class="nav-link-ht dropdown-toggle-ht {{ \App\Helpers\MenuHelper::isActive($item) ? 'active' : '' }}" href="#" {!! $firstMenu ? 'style="padding-left: 12px !important;"' : '' !!}>
                            <span>{{ $item['name'] }}</span> <i class="fas fa-chevron-down ms-1" style="font-size: 0.65rem; opacity: 0.7;"></i>
                        </a>
                        <ul class="dropdown-menu-ht list-unstyled py-2 shadow-lg border bg-white">
                            @foreach($item['children'] as $child)
                            <li>
                                <a class="dropdown-item-ht {{ \App\Helpers\MenuHelper::isActive($child) ? 'active' : '' }}" href="{{ \App\Helpers\MenuHelper::getMenuUrl($child) }}">
                                    {{ $child['name'] }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @php $firstMenu = false; @endphp
                    </li>
                    @endif
                    @endforeach
                </ul>
            </nav>

            <!-- Right: Search, E-Paper, Language, Login -->
            <div class="d-flex align-items-center gap-3">
                <!-- Search Toggler -->
                <div class="position-relative header-search-container">
                    <form action="{{ route('blog.search') }}" method="GET" class="d-flex align-items-center position-relative mb-0" id="headerSearchForm">
                        <input type="text" name="q" id="headerSearchInput" class="form-control form-control-sm rounded px-3" placeholder="খুঁজুন..." style="font-size: 0.8rem; width: 240px; padding-right: 28px; border: 1px solid #e2e8f0; height: 32px; transition: width 0.3s ease;" value="{{ request('q') }}" autocomplete="off">
                        <button type="submit" class="border-0 bg-transparent position-absolute" style="right: 8px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 0.8rem;" aria-label="Search">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                    <!-- Ajax Search Results Dropdown -->
                    <div id="ajaxSearchResults" class="position-absolute bg-white shadow-lg border rounded py-1 d-none" style="top: 36px; right: 0; width: 300px; z-index: 1050; max-height: 350px; overflow-y: auto; font-family: var(--font-bengali);">
                        <div class="search-results-loading p-3 text-center text-muted" style="font-size: 0.85rem;">
                            <i class="fas fa-spinner fa-spin me-1"></i> খোঁজা হচ্ছে...
                        </div>
                        <div class="search-results-list"></div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const searchInput = document.getElementById('headerSearchInput');
                        const resultsContainer = document.getElementById('ajaxSearchResults');
                        const resultsList = resultsContainer.querySelector('.search-results-list');
                        const loadingIndicator = resultsContainer.querySelector('.search-results-loading');
                        let debounceTimeout = null;

                        searchInput.addEventListener('input', function() {
                            const query = this.value.trim();

                            clearTimeout(debounceTimeout);

                            if (query.length < 2) {
                                resultsContainer.classList.add('d-none');
                                return;
                            }

                            resultsContainer.classList.remove('d-none');
                            loadingIndicator.classList.remove('d-none');
                            resultsList.innerHTML = '';

                            debounceTimeout = setTimeout(() => {
                                fetch(`{{ route('blog.search') }}?q=${encodeURIComponent(query)}`, {
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        loadingIndicator.classList.add('d-none');

                                        if (data.length === 0) {
                                            resultsList.innerHTML = '<div class="p-3 text-center text-muted" style="font-size: 0.85rem;">কোনো ফলাফল পাওয়া যায়নি</div>';
                                            return;
                                        }

                                        let html = '';
                                        data.forEach(item => {
                                            html += `
                                                <a href="${item.url}" class="d-flex align-items-center gap-2 p-2 border-bottom text-decoration-none text-dark search-result-item" style="transition: background-color 0.2s;">
                                                    <img src="${item.image}" alt="${item.title}" class="rounded flex-shrink-0" style="width: 38px; height: 38px; object-fit: cover;">
                                                    <div style="flex: 1; min-width: 0;">
                                                        <p class="mb-0 fw-semibold" style="font-size: 0.82rem; line-height: 1.3; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">${item.title}</p>
                                                    </div>
                                                </a>
                                            `;
                                        });
                                        resultsList.innerHTML = html;

                                        // Hover effects
                                        resultsList.querySelectorAll('.search-result-item').forEach(el => {
                                            el.addEventListener('mouseenter', function() {
                                                this.style.backgroundColor = '#f8fafc';
                                            });
                                            el.addEventListener('mouseleave', function() {
                                                this.style.backgroundColor = 'transparent';
                                            });
                                        });
                                    })
                                    .catch(err => {
                                        console.error('Search error:', err);
                                        loadingIndicator.classList.add('d-none');
                                        resultsList.innerHTML = '<div class="p-3 text-center text-danger" style="font-size: 0.85rem;"><i class="fas fa-exclamation-circle me-1"></i> সমস্যা ঘটেছে!</div>';
                                    });
                            }, 300);
                        });

                        // Click outside handler
                        document.addEventListener('click', function(e) {
                            if (!e.target.closest('.header-search-container')) {
                                resultsContainer.classList.add('d-none');
                            }
                        });

                        // Re-open on focus if input has value
                        searchInput.addEventListener('focus', function() {
                            if (this.value.trim().length >= 2) {
                                resultsContainer.classList.remove('d-none');
                            }
                        });
                    });
                </script>

                <!-- Highlight Button "যোগدان করুন" -->
                @foreach(\App\Helpers\MenuHelper::getNavbarMenu('desktop_nav') as $item)
                @if(empty($item['children']) && ($item['name'] == 'যোগدان করুন' || $item['name'] == 'যোগ দিন' || $item['name'] == 'Join Us' || $item['name'] == 'যুক্ত হোন'))
                <a class="btn btn-success btn-sm px-3 fw-bold d-flex align-items-center justify-content-center" href="{{ \App\Helpers\MenuHelper::getMenuUrl($item) }}" style="background-color: var(--primary-green) !important; border-color: var(--primary-green) !important; font-size: 0.85rem; height: 32px; border-radius: 4px;">
                    {{ $item['name'] }}
                </a>
                @endif
                @endforeach

                <!-- Language Switcher EN -->
                <a href="https://hezbuttawheed.com" target="_blank" class="d-flex align-items-center border px-3 py-1 bg-white text-secondary font-weight-bold text-decoration-none" style="font-size: 0.78rem; border-color: #e2e8f0 !important; height: 32px; border-radius: 4px;">
                    <span class="text-secondary" style="font-weight: 600;">Eng</span>
                </a>

                <!-- Login -->
                @if(!auth()->check())
                <a href="{{ route('admin.login') }}" class="btn btn-outline-secondary btn-sm px-3 d-flex align-items-center gap-1" style="font-size: 0.85rem; height: 32px; border-radius: 4px;">
                    <i class="far fa-user"></i> Login
                </a>
                @endif
            </div>
        </div>
    </div>
</header>

<!-- Sleek Responsive Header (Mobile View) -->
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow-sm py-2 custom-navbar d-lg-none">
    <div class="container-fluid">
        <!-- Brand Logo / Name -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            @if($setting->getSetting('company_logo'))
            <img src="{{ asset($setting->getSetting('company_logo')) }}" alt="Hezbut Tawheed Logo" style="height: 40px; width: auto; object-fit: contain;">
            @else
            <svg width="40" height="40" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="5" y="3" width="46" height="46" stroke="#1E293B" stroke-width="1.8" fill="#FFFFFF" />
                <path d="M12 43 C12 23, 18 11, 28 11 C38 11, 44 23, 44 43" stroke="#009639" stroke-width="2.5" stroke-linecap="round" fill="none" />
                <path d="M22 28 L32 23 L38 35 L28 40 Z" fill="#F8FAFC" stroke="#94A3B8" stroke-width="0.5" />
                <path d="M20 29 L30 24 L36 36 L26 41 Z" fill="#D4AF37" stroke="#B45309" stroke-width="0.5" />
                <path d="M22 28.5 L28 25.5 L34 34.5 L28 37.5 Z" fill="#009639" />
                <circle cx="28" cy="31.5" r="2" fill="#D4AF37" />
                <line x1="2" y1="50" x2="52" y2="50" stroke="#D4AF37" stroke-width="1.5" />
                <line x1="2" y1="52" x2="48" y2="52" stroke="#D4AF37" stroke-width="1" />
                <path d="M51 48 C52.5 48, 54 49.5, 54 51 C54 52.5, 52.5 54, 51 54 C52 53, 52.5 52, 52 51 C51.5 50, 51 49, 51 48 Z" fill="#D4AF37" />
            </svg>
            @endif
        </a>

        <!-- Toggle Button for Mobile -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNavbar" aria-controls="mobileNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="mobileNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                @foreach(\App\Helpers\MenuHelper::getNavbarMenu('desktop_nav') as $item)
                @if(empty($item['children']))
                @if($item['name'] == 'যোগদান করুন' || $item['name'] == 'যোগ দিন' || $item['name'] == 'Join Us' || $item['name'] == 'যুক্ত হোন')
                <li class="nav-item nav-item-btn my-2 w-100 text-center">
                    <a class="btn bg-gradient-btn px-4 py-1 w-75" href="{{ \App\Helpers\MenuHelper::getMenuUrl($item) }}" style="color: white !important;">
                        {{ $item['name'] }}
                    </a>
                </li>
                @else
                <li class="nav-item w-100 text-center">
                    <a class="nav-link fw-medium {{ \App\Helpers\MenuHelper::isActive($item) ? 'active text-dark-green' : 'text-dark' }}" href="{{ \App\Helpers\MenuHelper::getMenuUrl($item) }}">
                        {{ $item['name'] }}
                    </a>
                </li>
                @endif
                @else
                <li class="nav-item dropdown w-100 text-center">
                    <a class="nav-link dropdown-toggle fw-medium {{ \App\Helpers\MenuHelper::isActive($item) ? 'active text-dark-green' : 'text-dark' }}" href="#" id="navbarDropdown{{ $item['id'] }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $item['name'] }}
                    </a>
                    <ul class="dropdown-menu border-0 shadow-lg py-2" aria-labelledby="navbarDropdown{{ $item['id'] }}" style="border-radius: 8px;">
                        @foreach($item['children'] as $child)
                        <li>
                            <a class="dropdown-item py-2 {{ \App\Helpers\MenuHelper::isActive($child) ? 'active bg-dark-green text-white' : 'text-dark' }}" href="{{ \App\Helpers\MenuHelper::getMenuUrl($child) }}">
                                {{ $child['name'] }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endif
                @endforeach
            </ul>

            <!-- Action Items (Search Filter and Language Switcher) -->
            <div class="action-buttons-group d-flex align-items-center gap-2 justify-content-center mt-3 mt-lg-0">
                <form action="{{ route('blog.search') }}" method="GET" class="d-flex align-items-center position-relative me-1">
                    <input type="text" name="q" class="form-control form-control-sm rounded-pill px-3" placeholder="অনুসন্ধান..." style="font-size: 0.8rem; width: 140px; padding-right: 28px; border-color: #dee2e6;" value="{{ request('q') }}">
                    <button type="submit" class="border-0 bg-transparent position-absolute" style="right: 8px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 0.85rem;" aria-label="Search">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <a href="https://hezbuttawheed.com" target="_blank" class="d-flex align-items-center border rounded-pill px-3 py-1 bg-white text-secondary fs-8 font-weight-bold text-decoration-none" style="font-size: 0.75rem; border-color: #dee2e6 !important;">
                    <span class="text-secondary" style="font-weight: 600;">EN</span>
                </a>
            </div>
        </div>
    </div>
</nav>
@endif