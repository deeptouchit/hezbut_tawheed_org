<!-- Topbar Strip -->
@php
    $topbarLeft = \App\Helpers\MenuHelper::getMenuData()['topbar_left'] ?? [];
    $topbarRight = \App\Helpers\MenuHelper::getMenuData()['topbar_right'] ?? [];
@endphp

@if(count($topbarLeft) > 0 || count($topbarRight) > 0)
    <div class="header-topbar py-2 d-none d-lg-block">
        <div class="container-fluid px-lg-5 d-flex justify-content-between align-items-center">
            <!-- Left side items -->
            <div class="topbar-left d-flex align-items-center gap-2">
                @foreach($topbarLeft as $index => $item)
                    @if($index > 0)
                        <span class="topbar-separator">|</span>
                    @endif
                    <a href="{{ \App\Helpers\MenuHelper::getMenuUrl($item) }}" class="d-flex align-items-center gap-1">
                        @if(!empty($item['icon']))
                            <i class="{{ $item['icon'] }}" style="font-size: 0.75rem;"></i>
                        @endif
                        <span>{{ $item['name'] ?? $item['text'] ?? '' }}</span>
                    </a>
                @endforeach
            </div>
            
            <!-- Right side items (Icons only) -->
            <div class="topbar-right d-flex align-items-center gap-3">
                @foreach($topbarRight as $item)
                    <a href="{{ \App\Helpers\MenuHelper::getMenuUrl($item) }}" class="d-flex align-items-center text-white text-decoration-none" target="_blank" title="{{ $item['name'] ?? $item['text'] ?? '' }}" style="opacity: 0.9; transition: opacity 0.2s;">
                        @if(!empty($item['icon']))
                            <i class="{{ $item['icon'] }}" style="font-size: 0.95rem;"></i>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif

<!-- Main Navigation Bar (Sticky Top) -->
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow-sm py-2 custom-navbar">
    <div class="container-fluid px-lg-5">
        <!-- Brand Logo / Name -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            @if($setting->getSetting('company_logo'))
                <img src="{{ asset($setting->getSetting('company_logo')) }}" alt="Hezbut Tawheed Logo" class="brand-logo" style="height: 48px; width: auto; object-fit: contain;">
            @else
                <!-- Exact high-quality replica of the official Hezbut Tawheed emblem in vector SVG format -->
                <svg width="52" height="52" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Outer Square Border -->
                    <rect x="5" y="3" width="46" height="46" stroke="#1E293B" stroke-width="1.8" fill="#FFFFFF"/>
                    
                    <!-- Islamic Pointed Arch (Green Dome) -->
                    <path d="M12 43 C12 23, 18 11, 28 11 C38 11, 44 23, 44 43" stroke="#009639" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                    
                    <!-- Quran Book (Tilted) -->
                    <!-- Book Base/Pages -->
                    <path d="M22 28 L32 23 L38 35 L28 40 Z" fill="#F8FAFC" stroke="#94A3B8" stroke-width="0.5"/>
                    <!-- Book Cover (Gold/Brown) -->
                    <path d="M20 29 L30 24 L36 36 L26 41 Z" fill="#D4AF37" stroke="#B45309" stroke-width="0.5"/>
                    <!-- Green Cover Insert -->
                    <path d="M22 28.5 L28 25.5 L34 34.5 L28 37.5 Z" fill="#009639"/>
                    <!-- Gold emblem on book cover -->
                    <circle cx="28" cy="31.5" r="2" fill="#D4AF37"/>
                    
                    <!-- Bottom Double Line with Crescent -->
                    <line x1="2" y1="50" x2="52" y2="50" stroke="#D4AF37" stroke-width="1.5"/>
                    <line x1="2" y1="52" x2="48" y2="52" stroke="#D4AF37" stroke-width="1"/>
                    <!-- Small Crescent on the bottom right -->
                    <path d="M51 48 C52.5 48, 54 49.5, 54 51 C54 52.5, 52.5 54, 51 54 C52 53, 52.5 52, 52 51 C51.5 50, 51 49, 51 48 Z" fill="#D4AF37"/>
                </svg>
            @endif
        </a>

        <!-- Toggle Button for Mobile -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                @foreach(\App\Helpers\MenuHelper::getNavbarMenu('desktop_nav') as $item)
                    @if(empty($item['children']))
                        <!-- Highlight "যোগদান করুন" or custom featured buttons as a standout button -->
                        @if($item['name'] == 'যোগদান করুন' || $item['name'] == 'যোগ দিন' || $item['name'] == 'Join Us' || $item['name'] == 'যুক্ত হোন')
                            <li class="nav-item nav-item-btn">
                                <a class="btn bg-gradient-btn px-4 py-1" href="{{ \App\Helpers\MenuHelper::getMenuUrl($item) }}" style="color: white !important;">
                                    {{ $item['name'] }}
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link fw-medium {{ \App\Helpers\MenuHelper::isActive($item) ? 'active text-dark-green' : 'text-dark' }}" href="{{ \App\Helpers\MenuHelper::getMenuUrl($item) }}">
                                    {{ $item['name'] }}
                                </a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
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
            <div class="action-buttons-group d-flex align-items-center gap-2 ms-lg-3 justify-content-center mt-3 mt-lg-0">
                <!-- Search Filter Form -->
                <form action="{{ route('blog.search') }}" method="GET" class="d-flex align-items-center position-relative me-1">
                    <input type="text" name="q" class="form-control form-control-sm rounded-pill px-3" placeholder="অনুসন্ধান..." style="font-size: 0.8rem; width: 140px; padding-right: 28px; border-color: #dee2e6; font-family: 'Baloo Da 2', sans-serif;" value="{{ request('q') }}">
                    <button type="submit" class="border-0 bg-transparent position-absolute" style="right: 8px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 0.85rem;" aria-label="Search">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <!-- Language Switcher EN -->
                <a href="https://hezbuttawheed.com" target="_blank" class="d-flex align-items-center border rounded-pill px-3 py-1 bg-white text-secondary fs-8 font-weight-bold text-decoration-none" style="font-size: 0.75rem; border-color: #dee2e6 !important; transition: all 0.2s;">
                    <span class="text-secondary" style="font-weight: 600;">EN</span>
                </a>
            </div>
        </div>
    </div>
</nav>
