<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Single Menus (Direct Links without Submenu)
    |--------------------------------------------------------------------------
    | */
    'single_menus' => [
        [
            'order'       => 1,
            'route'       => 'admin.dashboard',
            'label'       => 'Dashboard',
            'icon'        => 'fas fa-tachometer-alt',
            'active'      => ['admin/dashboard'],
            'activeRoute' => ['admin.dashboard'],
        ],
        [
            'order'       => 14,
            'route'       => 'admin.profile.index',
            'label'       => 'My Profile',
            'icon'        => 'fas fa-user-circle',
            'active'      => ['admin/profile*'],
            'activeRoute' => ['admin.profile.index'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Groups (With Submenus)
    |--------------------------------------------------------------------------
    | */
    'menu_groups' => [
        // Blog Management
        [
            'order'       => 2,
            'title'       => 'Blog Management',
            'icon'        => 'fas fa-blog',
            'active'      => ['admin/blog*'],
            'activeRoute' => ['admin.blog.*'],
            'roles'       => ['super_admin', 'admin', 'manager'],
            'items'       => [
                [
                    'order'       => 1,
                    'route'       => 'admin.blog.categories.index',
                    'label'       => 'Blog Categories',
                    'icon'        => 'fas fa-tags nav-icon ml-2',
                    'active'      => ['admin/blog/categories*'],
                    'activeRoute' => ['admin.blog.categories.*'],
                ],
                [
                    'order'       => 2,
                    'route'       => 'admin.blog.posts.index',
                    'label'       => 'Blog Posts',
                    'icon'        => 'fas fa-file-alt nav-icon ml-2',
                    'active'      => ['admin/blog/posts*'],
                    'activeRoute' => ['admin.blog.posts.*'],
                ],
                [
                    'order'       => 3,
                    'route'       => 'admin.blog.comments.index',
                    'label'       => 'Blog Comments',
                    'icon'        => 'fas fa-comments nav-icon ml-2',
                    'active'      => ['admin/blog/comments*'],
                    'activeRoute' => ['admin.blog.comments.*'],
                ],
                [
                    'order'       => 4,
                    'route'       => 'admin.blog.tags.index',
                    'label'       => 'Blog Tags',
                    'icon'        => 'fas fa-hashtag nav-icon ml-2',
                    'active'      => ['admin/blog/tags*'],
                    'activeRoute' => ['admin.blog.tags.*'],
                ],
            ],
        ],


        // Page Management
        [
            'order'       => 4,
            'title'       => 'Page Management',
            'icon'        => 'fas fa-file-invoice',
            'active'      => ['admin/pages*', 'admin/activities*', 'admin/books*', 'admin/videos*', 'admin/live-broadcasts*', 'admin/songs*', 'admin/leaders*', 'admin/branches*', 'admin/gallery*'],
            'activeRoute' => ['admin.pages.*', 'admin.activities.*', 'admin.books.*', 'admin.videos.*', 'admin.live-broadcasts.*', 'admin.songs.*', 'admin.leaders.*', 'admin.branches.*', 'admin.gallery.*'],
            'roles'       => ['super_admin', 'admin', 'manager'],
            'items'       => [
                [
                    'order'       => 1,
                    'route'       => 'admin.pages.index',
                    'label'       => 'All Pages',
                    'icon'        => 'fas fa-copy nav-icon ml-2',
                    'active'      => ['admin/pages*'],
                    'activeRoute' => ['admin.pages.*'],
                ],
                [
                    'order'       => 3,
                    'route'       => 'admin.books.index',
                    'label'       => 'All Books',
                    'icon'        => 'fas fa-book nav-icon ml-2',
                    'active'      => ['admin/books*'],
                    'activeRoute' => ['admin.books.*'],
                ],
                [
                    'order'       => 4,
                    'route'       => 'admin.videos.index',
                    'label'       => 'All Videos',
                    'icon'        => 'fab fa-youtube nav-icon ml-2',
                    'active'      => ['admin/videos*'],
                    'activeRoute' => ['admin.videos.*'],
                ],
                [
                    'order'       => 5,
                    'route'       => 'admin.live-broadcasts.index',
                    'label'       => 'Live Broadcasts',
                    'icon'        => 'fas fa-broadcast-tower nav-icon ml-2',
                    'active'      => ['admin/live-broadcasts*'],
                    'activeRoute' => ['admin.live-broadcasts.*'],
                ],
                [
                    'order'       => 5,
                    'route'       => 'admin.songs.index',
                    'label'       => 'Songs & Lyrics',
                    'icon'        => 'fas fa-music nav-icon ml-2',
                    'active'      => ['admin/songs*'],
                    'activeRoute' => ['admin.songs.*'],
                ],
                [
                    'order'       => 6,
                    'route'       => 'admin.leaders.index',
                    'label'       => 'All Leaders',
                    'icon'        => 'fas fa-user-tie nav-icon ml-2',
                    'active'      => ['admin/leaders*'],
                    'activeRoute' => ['admin.leaders.*'],
                ],
                [
                    'order'       => 7,
                    'route'       => 'admin.branches.index',
                    'label'       => 'All Branches',
                    'icon'        => 'fas fa-map-marked-alt nav-icon ml-2',
                    'active'      => ['admin/branches*'],
                    'activeRoute' => ['admin.branches.*'],
                ],
                [
                    'order'       => 8,
                    'route'       => 'admin.gallery.index',
                    'label'       => 'Photo Gallery',
                    'icon'        => 'fas fa-images nav-icon ml-2',
                    'active'      => ['admin/gallery*'],
                    'activeRoute' => ['admin.gallery.*'],
                ],
            ],
        ],

        // Marketing & Communication
        [
            'order'       => 5,
            'title'       => 'Communication',
            'icon'        => 'fas fa-bullhorn',
            'active'      => ['admin/newsletter*', 'admin/contacts*', 'admin/testimonials*', 'admin/suggestions*', 'admin/join-requests*'],
            'activeRoute' => ['admin.newsletter.*', 'admin.contacts.*', 'admin.testimonials.*', 'admin.suggestions.*', 'admin.join-requests.*'],
            'roles'       => ['super_admin', 'admin', 'manager'],
            'items'       => [
                [
                    'order'       => 1,
                    'route'       => 'admin.newsletter.subscribers.index',
                    'label'       => 'Subscribers',
                    'icon'        => 'fas fa-users nav-icon ml-2',
                    'active'      => ['admin/newsletter-subscribers*'],
                    'activeRoute' => ['admin.newsletter.subscribers.*'],
                ],
                [
                    'order'       => 2,
                    'route'       => 'admin.testimonials.index',
                    'label'       => 'Testimonials / Quotes',
                    'icon'        => 'fas fa-quote-left nav-icon ml-2',
                    'active'      => ['admin/testimonials*'],
                    'activeRoute' => ['admin.testimonials.*'],
                ],
                [
                    'order'       => 3,
                    'route'       => 'admin.contacts.index',
                    'label'       => 'Contact Messages',
                    'icon'        => 'fas fa-phone nav-icon ml-2',
                    'active'      => ['admin/contacts*'],
                    'activeRoute' => ['admin.contacts.*'],
                ],
                [
                    'order'       => 3,
                    'route'       => 'admin.join-requests.index',
                    'label'       => 'Join Requests',
                    'icon'        => 'fas fa-user-plus nav-icon ml-2',
                    'active'      => ['admin/join-requests*'],
                    'activeRoute' => ['admin.join-requests.*'],
                ],
                [
                    'order'       => 4,
                    'route'       => 'admin.newsletter.campaigns.index',
                    'label'       => 'Email Campaigns',
                    'icon'        => 'fas fa-paper-plane nav-icon ml-2',
                    'active'      => ['admin/newsletter-campaigns*'],
                    'activeRoute' => ['admin.newsletter.campaigns.*'],
                ],
                [
                    'order'       => 5,
                    'route'       => 'admin.newsletter.templates.index',
                    'label'       => 'Newsletter Templates',
                    'icon'        => 'fas fa-file-alt nav-icon ml-2',
                    'active'      => ['admin/newsletter-templates*'],
                    'activeRoute' => ['admin.newsletter.templates.*'],
                ],
                [
                    'order'       => 6,
                    'route'       => 'admin.suggestions.index',
                    'label'       => 'Suggestions & Feedback',
                    'icon'        => 'fas fa-comment-dots nav-icon ml-2',
                    'active'      => ['admin/suggestions*'],
                    'activeRoute' => ['admin.suggestions.*'],
                ],
            ],
        ],

        // Design & Appearance
        [
            'order'       => 6,
            'title'       => 'Design & Appearance',
            'icon'        => 'fas fa-palette',
            'active'      => ['admin/themes*', 'admin/menu*', 'admin/sliders*'],
            'activeRoute' => ['admin.themes.*', 'admin.menu.*', 'admin.sliders.*'],
            'roles'       => ['super_admin', 'admin', 'manager'],
            'items'       => [
                [
                    'order'       => 1,
                    'route'       => 'admin.themes.index',
                    'label'       => 'Themes',
                    'icon'        => 'fas fa-paint-brush nav-icon ml-2',
                    'active'      => ['admin/themes*'],
                    'activeRoute' => ['admin.themes.*'],
                ],
                [
                    'order'       => 2,
                    'route'       => 'admin.menu.index',
                    'label'       => 'Menu Manager',
                    'icon'        => 'fas fa-bars nav-icon ml-2',
                    'active'      => ['admin/menu*'],
                    'activeRoute' => ['admin.menu.*'],
                ],
                [
                    'order'       => 3,
                    'route'       => 'admin.sliders.index',
                    'label'       => 'Sliders',
                    'icon'        => 'fas fa-images nav-icon ml-2',
                    'active'      => ['admin/sliders*'],
                    'activeRoute' => ['admin.sliders.*'],
                ],
            ],
        ],

        // System Settings
        [
            'order'       => 7,
            'title'       => 'System Settings',
            'icon'        => 'fas fa-cog',
            'active'      => ['admin/settings*', 'admin/email*', 'admin/email-templates*', 'admin/cache*', 'admin/backup*'],
            'activeRoute' => ['admin.settings.*', 'admin.email.*', 'admin.email-templates.*', 'admin.cache-tools.*', 'admin.backup.*'],
            'roles'       => ['super_admin', 'admin'],
            'items'       => [
                [
                    'order'       => 1,
                    'route'       => 'admin.settings.index',
                    'label'       => 'General Settings',
                    'icon'        => 'fas fa-sliders-h nav-icon ml-2',
                    'active'      => ['admin/settings*'],
                    'activeRoute' => ['admin.settings.*'],
                ],
                [
                    'order'       => 2,
                    'route'       => 'admin.email.index',
                    'label'       => 'Email Settings',
                    'icon'        => 'fas fa-envelope nav-icon ml-2',
                    'active'      => ['admin/email', 'admin/email/*'],
                    'activeRoute' => ['admin.email.*'],
                ],
                [
                    'order'       => 3,
                    'route'       => 'admin.email-templates.index',
                    'label'       => 'Email Templates',
                    'icon'        => 'fas fa-envelope-open-text nav-icon ml-2',
                    'active'      => ['admin/email-templates*'],
                    'activeRoute' => ['admin.email-templates.*'],
                ],
                [
                    'order'       => 4,
                    'route'       => 'admin.cache-tools.index',
                    'label'       => 'Cache Tools',
                    'icon'        => 'fas fa-broom nav-icon ml-2',
                    'active'      => ['admin/cache-tools*'],
                    'activeRoute' => ['admin.cache-tools.*'],
                ],
                [
                    'order'       => 5,
                    'route'       => 'admin.backup.index',
                    'label'       => 'Backup',
                    'icon'        => 'fas fa-database nav-icon ml-2',
                    'active'      => ['admin/backup*'],
                    'activeRoute' => ['admin.backup.*'],
                ],
            ],
        ],

        // User Management
        [
            'order'       => 8,
            'title'       => 'User Management',
            'icon'        => 'fas fa-user-shield',
            'active'      => ['admin/users*', 'admin/roles*', 'admin/permissions*', 'admin/activity-logs*'],
            'activeRoute' => ['admin.users.*', 'admin.roles.*', 'admin.permissions.*', 'admin.activity-logs.*'],
            'roles'       => ['super_admin'],
            'items'       => [
                [
                    'order'       => 1,
                    'route'       => 'admin.permissions.index',
                    'label'       => 'Permissions',
                    'icon'        => 'fas fa-key nav-icon ml-2',
                    'active'      => ['admin/permissions*'],
                    'activeRoute' => ['admin.permissions.*'],
                ],
                [
                    'order'       => 2,
                    'route'       => 'admin.roles.index',
                    'label'       => 'Roles',
                    'icon'        => 'fas fa-tags nav-icon ml-2',
                    'active'      => ['admin/roles*'],
                    'activeRoute' => ['admin.roles.*'],
                ],
                [
                    'order'       => 3,
                    'route'       => 'admin.users.index',
                    'label'       => 'All Users',
                    'icon'        => 'fas fa-users-cog nav-icon ml-2',
                    'active'      => ['admin/users*'],
                    'activeRoute' => ['admin.users.*'],
                ],
                [
                    'order'       => 4,
                    'route'       => 'admin.activity-logs.index',
                    'label'       => 'Activity Logs',
                    'icon'        => 'fas fa-history nav-icon ml-2',
                    'active'      => ['admin/activity-logs*'],
                    'activeRoute' => ['admin.activity-logs.*'],
                ],
            ],
        ],
    ],
];
