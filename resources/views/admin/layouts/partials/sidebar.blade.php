@php
    $sidebarUser  = auth()->user();
    $sidebarRole  = $sidebarUser ? $sidebarUser->getRoleNames()->first() : null;
    $sidebarRoles = $sidebarUser? $sidebarUser->getRoleNames()->map(function ($role) { return strtolower($role); })->toArray(): [];
    $hasAdminRole = in_array('admin', $sidebarRoles, true);

    $canViewByRole = function ($allowedRoles = null) use ($hasAdminRole, $sidebarRoles) {
        if ($hasAdminRole) {
            return true;
        }
        if (empty($allowedRoles)) {
            return true;
        }
        if (is_string($allowedRoles)) {
            $allowedRoles = [$allowedRoles];
        }
        return count(array_intersect($sidebarRoles, $allowedRoles)) > 0;
    };

    $resolveRouteUrl = function ($routeConfig) {
        if (empty($routeConfig)) {
            return '#';
        }
        if (is_array($routeConfig)) {
            $routeName  = $routeConfig[0] ?? null;
            $routeParam = $routeConfig[1] ?? null;
            if ($routeName && \Illuminate\Support\Facades\Route::has($routeName)) {
                return route($routeName, $routeParam);
            }
            return '#';
        }
        if (is_string($routeConfig) && \Illuminate\Support\Facades\Route::has($routeConfig)) {
            return route($routeConfig);
        }
        return '#';
    };

    $resolveBadgeValue = function ($badge) {
        if (is_callable($badge)) {
            try {
                return $badge();
            } catch (\Exception $e) {
                return 0;
            }
        }
        return $badge;
    };

    // Recursive function to render menu items (supports nested sub-menus)
    $renderMenuItems = function ($items, $level = 1) use (&$renderMenuItems, $canViewByRole, $resolveRouteUrl) {
        $html = '';
        foreach ($items as $item) {
            if (!$canViewByRole($item['roles'] ?? null)) {
                continue;
            }

            $hasSubItems = isset($item['items']) && is_array($item['items']) && count($item['items']) > 0;
            $isActive = false;

            if (!empty($item['active'])) {
                $isActive = request()->is(...(array) $item['active']);
            }
            if (!$isActive && !empty($item['activeRoute'])) {
                $isActive = request()->routeIs(...(array) $item['activeRoute']);
            }

            $marginClass = $level == 1 ? 'ml-2' : 'ml-3';
            $iconClass = $level == 1 ? 'far fa-circle' : 'fas fa-circle';

            if ($hasSubItems) {
                // Nested sub-menu
                $html .= '<li class="nav-item ' . ($isActive ? 'menu-open' : '') . '">';
                $html .= '<a href="#" class="nav-link ' . ($isActive ? 'active' : '') . '">';
                $html .= '<i class="' . ($item['icon'] ?? $iconClass) . ' nav-icon ' . $marginClass . '"></i>';
                $html .= '<p>' . ($item['label'] ?? 'Menu') . '<i class="nav-arrow bi bi-chevron-right"></i></p>';
                $html .= '</a>';
                $html .= '<ul class="nav nav-treeview">';
                $html .= $renderMenuItems($item['items'], $level + 1);
                $html .= '</ul>';
                $html .= '</li>';
            } else {
                // Single menu item
                $html .= '<li class="nav-item">';
                $html .= '<a href="' . $resolveRouteUrl($item['route'] ?? '#') . '" class="nav-link ' . ($isActive ? 'active' : '') . '">';
                $html .= '<i class="' . ($item['icon'] ?? $iconClass) . ' nav-icon ' . $marginClass . '"></i>';
                $html .= '<p>' . ($item['label'] ?? 'Item') . '</p>';
                $html .= '</a>';
                $html .= '</li>';
            }
        }
        return $html;
    };

    $singleMenus = collect(config('admin_sidebar.single_menus', []))
        ->values()
        ->map(function ($menu, $index) {
            $menu['order'] = $menu['order'] ?? $index + 1;
            $menu['menu_type'] = 'single';
            return $menu;
        })
        ->sortBy('order')
        ->values();

    $menuGroups = collect(config('admin_sidebar.menu_groups', []))
        ->values()
        ->map(function ($group, $groupIndex) {
            $group['order'] = $group['order'] ?? $groupIndex + 1;
            $group['menu_type'] = 'group';
            $group['items'] = collect($group['items'] ?? [])
                ->values()
                ->map(function ($item, $itemIndex) {
                    $item['order'] = $item['order'] ?? $itemIndex + 1;
                    return $item;
                })
                ->sortBy('order')
                ->values()
                ->all();
            return $group;
        })
        ->sortBy('order')
        ->values();

    $mergedMenus = $singleMenus->concat($menuGroups)->sortBy('order')->values()->all();
    $loginLogo   = $setting->getSetting('company_logo_admin') ?: $setting->getSetting('company_logo');

@endphp

<aside class="app-sidebar bg-body shadow" data-bs-theme="light">

    <!-- Brand Logo -->
    <div class="sidebar-brand d-flex align-items-center justify-content-center">
        <a href="{{ route('admin.dashboard') }}" class="brand-link text-center w-100 d-flex align-items-center justify-content-center">
            @if($loginLogo)
                <img src="{{ asset($loginLogo) }}" alt="Logo" class="brand-image" style="max-height: 50px; width: auto; float: none; margin-left: 0; margin-right: 0;" />
            @else
                 <span class="brand-text">{{ $setting->getSetting('company_name') ?? config('app.name') }}</span>
            @endif
        </a>
    </div>

    <!-- Sidebar wrapper -->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">
                @foreach ($mergedMenus as $menu)
                    @if (!$canViewByRole($menu['roles'] ?? null))
                        @continue
                    @endif

                    @if (($menu['menu_type'] ?? null) === 'single')
                        @php
                            $isSingleActive = false;
                            if (!empty($menu['active'])) {
                                $isSingleActive = request()->is(...(array) $menu['active']);
                            }
                            if (!$isSingleActive && !empty($menu['activeRoute'])) {
                                $isSingleActive = request()->routeIs(...(array) $menu['activeRoute']);
                            }
                        @endphp
                        <li class="nav-item">
                            <a href="{{ $resolveRouteUrl($menu['route'] ?? '#') }}"
                               class="nav-link {{ $isSingleActive ? 'active' : '' }}">
                                <i class="nav-icon {{ $menu['icon'] ?? 'fas fa-circle' }}"></i>
                                <p>{{ $menu['label'] ?? 'Menu' }}</p>
                            </a>
                        </li>
                    @else
                        @php
                            $isGroupActive = false;
                            if (!empty($menu['active'])) {
                                $isGroupActive = request()->is(...(array) $menu['active']);
                            }
                            if (!$isGroupActive && !empty($menu['activeRoute'])) {
                                $isGroupActive = request()->routeIs(...(array) $menu['activeRoute']);
                            }
                        @endphp
                        <li class="nav-item {{ $isGroupActive ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ $isGroupActive ? 'active' : '' }}">
                                <i class="nav-icon {{ $menu['icon'] ?? 'fas fa-folder' }}"></i>
                                <p>
                                    {{ $menu['title'] ?? 'Group' }}
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                {!! $renderMenuItems($menu['items'] ?? [], 1) !!}
                            </ul>
                        </li>
                    @endif
                @endforeach


                <!-- Logout Menu Item - মেনুর ভিতরে -->
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<style>
    /* ==========================================================================
       Premium LIGHT Sidebar Styles (Bogra Bazar Natural Accent Theme)
       ========================================================================== */
    .app-sidebar {
        background-color: #ffffff !important; /* Clean pure white background */
        border-right: 1px solid #e2e8f0 !important; /* Subtle gray border */
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
        transition: all 0.3s ease;
    }

    .sidebar-brand {
        background-color: #ffffff !important; /* Pure white brand header */
        padding: 10px 14px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-start !important;
    }

    .sidebar-brand .brand-link {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: #0f172a !important; /* Dark slate text for brand name */
    }

    .sidebar-brand .brand-image {
        max-height: 28px;
        border-radius: 6px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.3s ease;
    }

    .sidebar-brand:hover .brand-image {
        transform: scale(1.05);
    }

    .sidebar-brand .brand-text {
        font-weight: 700 !important;
        font-size: 15px;
        color: #0f172a !important;
        letter-spacing: -0.3px;
        font-family: "Baloo Da 2", sans-serif;
    }

    .sidebar-wrapper {
        padding: 6px 4px !important;
    }

    /* Navigation Links & Groups (Light Mode) */
    .sidebar-menu .nav-link {
        border-radius: 6px;
        padding: 5px 10px !important; /* Tight padding */
        color: #1e293b !important; /* Dark slate text for maximum legibility */
        font-weight: 600 !important; /* Make text sharp and clear */
        font-size: 16px !important; /* Balanced font size */
        margin-bottom: 1px !important; /* Extremely tight item gap */
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 6px !important; /* Tight icon-text gap */
    }

    .sidebar-menu .nav-link i.nav-icon {
        transition: all 0.2s ease;
        font-size: 14px !important; /* Neat small size */
        width: 18px !important;
        text-align: center;
    }

    .sidebar-menu .nav-link:hover {
        background-color: #f1f5f9 !important; /* Soft hover bg */
        color: #0f172a !important; /* Darker slate text */
    }

    .sidebar-menu .nav-link:hover i.nav-icon {
        transform: scale(1.1);
    }

    /* Active Link - Forest Green Highlight */
    .sidebar-menu .nav-link.active {
        background-color: #e6f4ea !important; /* Soft Emerald/Forest Green tint */
        color: #137333 !important; /* Forest green active text */
        font-weight: 700 !important; /* Bolder text when active */
        border-left: 3px solid #137333;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        box-shadow: none !important;
    }

    .sidebar-menu .nav-link.active i.nav-icon {
        color: #137333 !important; /* Forest green active icon */
    }

    /* Open Group State styling */
    .sidebar-menu .menu-open > .nav-link {
        background-color: #f8fafc !important;
        color: #0f172a !important;
        font-weight: 600;
    }

    /* Nested Treeview Sub-menus */
    .sidebar-menu .nav-treeview {
        background-color: #ffffff !important; /* Clean background for sub-menu */
        border-radius: 6px;
        padding: 2px 0;
        margin: 1px 0 2px 0 !important;
        border-left: 1px dashed #cbd5e1; /* Subtle dashed connector line */
        margin-left: 16px !important;
    }

    /* Indent level-2 submenu links nicely */
    .sidebar-menu .nav-treeview .nav-link {
        padding: 4px 10px 4px 12px !important; /* Tight submenu padding */
        font-size: 16px !important;
        color: #334155 !important; /* Clear, sharp gray-blue for submenus */
        font-weight: 550 !important; /* Slightly bolder for clarity */
        margin-bottom: 0px !important;
        border-radius: 6px;
    }

    /* Nested Active links (make it clean and simple) */
    .sidebar-menu .nav-treeview .nav-link.active {
        background-color: #f0fdf4 !important; /* Super soft mint green bg */
        color: #16a34a !important; /* Vibrant green text */
        border-left: 2px solid #16a34a;
        font-weight: 700 !important;
    }

    /* Nav Arrow Icon */
    .sidebar-menu .nav-arrow {
        font-size: 10px;
        margin-left: auto;
        color: #64748b;
        transition: transform 0.2s ease;
    }

    .sidebar-menu .menu-open > .nav-link .nav-arrow {
        transform: rotate(90deg);
        color: #1e293b;
    }

    /* Submenu default small circle icon styling */
    .sidebar-menu .nav-treeview .nav-link i.fa-circle,
    .sidebar-menu .nav-treeview .nav-link i.far.fa-circle {
        font-size: 5px !important;
        color: #94a3b8 !important;
        opacity: 0.8;
    }

    .sidebar-menu .nav-treeview .nav-link.active i.fa-circle,
    .sidebar-menu .nav-treeview .nav-link.active i.far.fa-circle {
        color: #16a34a !important;
    }

    /* ==========================================================================
       Natural Colorful Icons (Harmonious Curated Color Mapping)
       ========================================================================== */

    /* 1. Blue (Dashboard, Home, Analytics, Reports, Logs) */
    .sidebar-menu .nav-link i.fa-tachometer-alt,
    .sidebar-menu .nav-link i.fa-home,
    .sidebar-menu .nav-link i.fa-dashboard,
    .sidebar-menu .nav-link i.fa-chart-bar,
    .sidebar-menu .nav-link i.fa-chart-line,
    .sidebar-menu .nav-link i.fa-chart-pie,
    .sidebar-menu .nav-link i.fa-chart-area,
    .sidebar-menu .nav-link i.fa-history,
    .sidebar-menu .nav-link i.fa-activity,
    .sidebar-menu .nav-link i.fa-user-circle {
        color: #2563eb !important; /* Sky Blue */
    }

    /* 2. Green (Products, Inventory, Categories, Tags, Brands, Customizations) */
    .sidebar-menu .nav-link i.fa-box,
    .sidebar-menu .nav-link i.fa-boxes,
    .sidebar-menu .nav-link i.fa-box-open,
    .sidebar-menu .nav-link i.fa-tags,
    .sidebar-menu .nav-link i.fa-tag,
    .sidebar-menu .nav-link i.fa-trademark,
    .sidebar-menu .nav-link i.fa-layer-group,
    .sidebar-menu .nav-link i.fa-sliders-h,
    .sidebar-menu .nav-link i.fa-broom,
    .sidebar-menu .nav-link i.fa-paint-brush,
    .sidebar-menu .nav-link i.fa-store,
    .sidebar-menu .nav-link i.fa-sitemap,
    .sidebar-menu .nav-link i.fa-th-large {
        color: #059669 !important;

    }

    /* 3. Orange/Amber (Orders, Sales, Cart, Delivery, Shipments, Payments, Money) */
    .sidebar-menu .nav-link i.fa-shopping-cart,
    .sidebar-menu .nav-link i.fa-shopping-bag,
    .sidebar-menu .nav-link i.fa-receipt,
    .sidebar-menu .nav-link i.fa-truck,
    .sidebar-menu .nav-link i.fa-shipping-fast,
    .sidebar-menu .nav-link i.fa-search-location,
    .sidebar-menu .nav-link i.fa-map-marker-alt,
    .sidebar-menu .nav-link i.fa-clipboard-list,
    .sidebar-menu .nav-link i.fa-wallet,
    .sidebar-menu .nav-link i.fa-money-bill-wave,
    .sidebar-menu .nav-link i.fa-cash-register,
    .sidebar-menu .nav-link i.fa-credit-card {
        color: #ea580c !important; /* Warm Amber */
    }

    /* 4. Rose/Pink/Red (Customers, Users, Admin, Roles, Security, Cancelled) */
    .sidebar-menu .nav-link i.fa-user-friends,
    .sidebar-menu .nav-link i.fa-user-tie,
    .sidebar-menu .nav-link i.fa-users,
    .sidebar-menu .nav-link i.fa-user,
    .sidebar-menu .nav-link i.fa-user-shield,
    .sidebar-menu .nav-link i.fa-users-cog,
    .sidebar-menu .nav-link i.fa-key,
    .sidebar-menu .nav-link i.fa-ban,
    .sidebar-menu .nav-link i.fa-exclamation-triangle,
    .sidebar-menu .nav-link i.fa-undo {
        color: #e11d48 !important; /* Rose Red */
    }

    /* 5. Purple/Indigo (Blogs, Pages, Banners, Promotions, Sliders, Campaigns) */
    .sidebar-menu .nav-link i.fa-bullhorn,
    .sidebar-menu .nav-link i.fa-broadcast-tower,
    .sidebar-menu .nav-link i.fa-file-alt,
    .sidebar-menu .nav-link i.fa-images,
    .sidebar-menu .nav-link i.fa-ad,
    .sidebar-menu .nav-link i.fa-blog,
    .sidebar-menu .nav-link i.fa-newspaper,
    .sidebar-menu .nav-link i.fa-comments,
    .sidebar-menu .nav-link i.fa-palette,
    .sidebar-menu .nav-link i.fa-bars,
    .sidebar-menu .nav-link i.fa-star {
        color: #7c3aed !important; /* Purple */
    }

    /* 6. Teal/Cyan (Email, SMS, Templates, Notifications) */
    .sidebar-menu .nav-link i.fa-envelope,
    .sidebar-menu .nav-link i.fa-envelope-open-text,
    .sidebar-menu .nav-link i.fa-bell,
    .sidebar-menu .nav-link i.fa-clock,
    .sidebar-menu .nav-link i.fa-spinner,
    .sidebar-menu .nav-link i.fa-check-circle,
    .sidebar-menu .nav-link i.fa-question-circle,
    .sidebar-menu .nav-link i.fa-phone {
        color: #0d9488 !important; /* Teal */
    }

    /* 7. Slate/Steel (Settings, System Tools, Database, Backup) */
    .sidebar-menu .nav-link i.fa-cog,
    .sidebar-menu .nav-link i.fa-cogs,
    .sidebar-menu .nav-link i.fa-tools,
    .sidebar-menu .nav-link i.fa-database,
    .sidebar-menu .nav-link i.fa-server {
        color: #475569 !important; /* Slate */
    }

    /* Active overrides to keep uniform green */
    .sidebar-menu .nav-link.active i.nav-icon {
        color: #137333 !important;
    }
    .sidebar-menu .nav-treeview .nav-link.active i.nav-icon {
        color: #16a34a !important;
    }

    /* ==========================================================================
       Fullscreen Editor Support - Hide Sidebar & Header Navbar
       ========================================================================== */
    body.admin-fullscreen-active .app-sidebar,
    body.admin-fullscreen-active .app-header {
        display: none !important;
    }
    body.admin-fullscreen-active .app-main {
        margin-left: 0 !important;
        padding-top: 0 !important;
        transition: all 0.2s ease;
    }
</style>

<script>
$(document).ready(function() {
    // -------------------------------------------------------------
    // HTML5 native fullscreen detector
    // -------------------------------------------------------------
    function handleNativeFullscreenChange() {
        const isNativeFs = document.fullscreenElement ||
                           document.webkitFullscreenElement ||
                           document.mozFullScreenElement ||
                           document.msFullscreenElement;

        if (isNativeFs) {
            $('body').addClass('admin-fullscreen-active');
        } else {
            checkFullscreenClasses();
        }
    }

    document.addEventListener('fullscreenchange', handleNativeFullscreenChange);
    document.addEventListener('webkitfullscreenchange', handleNativeFullscreenChange);
    document.addEventListener('mozfullscreenchange', handleNativeFullscreenChange);
    document.addEventListener('MSFullscreenChange', handleNativeFullscreenChange);

    // -------------------------------------------------------------
    // Fake fullscreen detector via observer
    // -------------------------------------------------------------
    const fullscreenClasses = [
        'fullscreen',
        'editor-fullscreen',
        'ck-editor-fullscreen',
        'ck-editor__editable_fullscreen',
        'cke_fullscreen',
        'note-editor-fullscreen',
        'ck-editor-fullscreen-mode'
    ];

    function checkFullscreenClasses() {
        let hasFs = false;

        fullscreenClasses.forEach(function(cls) {
            if ($('.' + cls).length > 0) {
                hasFs = true;
            }
        });

        if ($('.ck-editor').hasClass('ck-editor-fullscreen') || $('.ck-editor__editable').hasClass('ck-editor__editable-fullscreen')) {
            hasFs = true;
        }

        if (hasFs || document.fullscreenElement) {
            $('body').addClass('admin-fullscreen-active');
        } else {
            $('body').removeClass('admin-fullscreen-active');
        }
    }

    // Initialize observer
    const fsObserver = new MutationObserver(function(mutations) {
        checkFullscreenClasses();
    });

    fsObserver.observe(document.body, {
        attributes: true,
        childList: true,
        subtree: true,
        attributeFilter: ['class', 'style']
    });

    checkFullscreenClasses();
});
</script>
