<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Dashboard
            ['name' => 'view_dashboard', 'group_name' => 'dashboard', 'guard_name' => 'web'],
            ['name' => 'view_dashboard_stats', 'group_name' => 'dashboard', 'guard_name' => 'web'],

            // User Management
            ['name' => 'view_users', 'group_name' => 'user_management', 'guard_name' => 'web'],
            ['name' => 'create_users', 'group_name' => 'user_management', 'guard_name' => 'web'],
            ['name' => 'edit_users', 'group_name' => 'user_management', 'guard_name' => 'web'],
            ['name' => 'delete_users', 'group_name' => 'user_management', 'guard_name' => 'web'],

            // Role Management
            ['name' => 'view_roles', 'group_name' => 'role_management', 'guard_name' => 'web'],
            ['name' => 'create_roles', 'group_name' => 'role_management', 'guard_name' => 'web'],
            ['name' => 'edit_roles', 'group_name' => 'role_management', 'guard_name' => 'web'],
            ['name' => 'delete_roles', 'group_name' => 'role_management', 'guard_name' => 'web'],
            ['name' => 'assign_permissions', 'group_name' => 'role_management', 'guard_name' => 'web'],

            // Profile
            ['name' => 'view_own_profile', 'group_name' => 'profile', 'guard_name' => 'web'],
            ['name' => 'edit_own_profile', 'group_name' => 'profile', 'guard_name' => 'web'],
            ['name' => 'change_own_password', 'group_name' => 'profile', 'guard_name' => 'web'],

            // Activity Logs
            ['name' => 'view_activity_logs', 'group_name' => 'activity', 'guard_name' => 'web'],
            ['name' => 'export_activity_logs', 'group_name' => 'activity', 'guard_name' => 'web'],
            ['name' => 'clear_activity_logs', 'group_name' => 'activity', 'guard_name' => 'web'],

            // Blog Category Management
            ['name' => 'view_categories', 'group_name' => 'categories', 'guard_name' => 'web'],
            ['name' => 'create_categories', 'group_name' => 'categories', 'guard_name' => 'web'],
            ['name' => 'edit_categories', 'group_name' => 'categories', 'guard_name' => 'web'],
            ['name' => 'delete_categories', 'group_name' => 'categories', 'guard_name' => 'web'],

            // Blog Management
            ['name' => 'view_blogs', 'group_name' => 'blogs', 'guard_name' => 'web'],
            ['name' => 'create_blogs', 'group_name' => 'blogs', 'guard_name' => 'web'],
            ['name' => 'edit_blogs', 'group_name' => 'blogs', 'guard_name' => 'web'],
            ['name' => 'delete_blogs', 'group_name' => 'blogs', 'guard_name' => 'web'],
            ['name' => 'publish_blogs', 'group_name' => 'blogs', 'guard_name' => 'web'],

            // Books Management
            ['name' => 'view_books', 'group_name' => 'books', 'guard_name' => 'web'],
            ['name' => 'create_books', 'group_name' => 'books', 'guard_name' => 'web'],
            ['name' => 'edit_books', 'group_name' => 'books', 'guard_name' => 'web'],
            ['name' => 'delete_books', 'group_name' => 'books', 'guard_name' => 'web'],

            // Video Management
            ['name' => 'view_videos', 'group_name' => 'videos', 'guard_name' => 'web'],
            ['name' => 'create_videos', 'group_name' => 'videos', 'guard_name' => 'web'],
            ['name' => 'edit_videos', 'group_name' => 'videos', 'guard_name' => 'web'],
            ['name' => 'delete_videos', 'group_name' => 'videos', 'guard_name' => 'web'],

            // Live Broadcast Management
            ['name' => 'view_live_broadcasts', 'group_name' => 'live_broadcasts', 'guard_name' => 'web'],
            ['name' => 'create_live_broadcasts', 'group_name' => 'live_broadcasts', 'guard_name' => 'web'],
            ['name' => 'edit_live_broadcasts', 'group_name' => 'live_broadcasts', 'guard_name' => 'web'],
            ['name' => 'delete_live_broadcasts', 'group_name' => 'live_broadcasts', 'guard_name' => 'web'],

            // Gallery Management
            ['name' => 'view_galleries', 'group_name' => 'galleries', 'guard_name' => 'web'],
            ['name' => 'create_galleries', 'group_name' => 'galleries', 'guard_name' => 'web'],
            ['name' => 'edit_galleries', 'group_name' => 'galleries', 'guard_name' => 'web'],
            ['name' => 'delete_galleries', 'group_name' => 'galleries', 'guard_name' => 'web'],

            // Slider Management
            ['name' => 'view_sliders', 'group_name' => 'sliders', 'guard_name' => 'web'],
            ['name' => 'create_sliders', 'group_name' => 'sliders', 'guard_name' => 'web'],
            ['name' => 'edit_sliders', 'group_name' => 'sliders', 'guard_name' => 'web'],
            ['name' => 'delete_sliders', 'group_name' => 'sliders', 'guard_name' => 'web'],

            // Banner Management
            ['name' => 'view_banners', 'group_name' => 'banners', 'guard_name' => 'web'],
            ['name' => 'create_banners', 'group_name' => 'banners', 'guard_name' => 'web'],
            ['name' => 'edit_banners', 'group_name' => 'banners', 'guard_name' => 'web'],
            ['name' => 'delete_banners', 'group_name' => 'banners', 'guard_name' => 'web'],

            // Leader Management
            ['name' => 'view_leaders', 'group_name' => 'leaders', 'guard_name' => 'web'],
            ['name' => 'create_leaders', 'group_name' => 'leaders', 'guard_name' => 'web'],
            ['name' => 'edit_leaders', 'group_name' => 'leaders', 'guard_name' => 'web'],
            ['name' => 'delete_leaders', 'group_name' => 'leaders', 'guard_name' => 'web'],

            // Branch Management
            ['name' => 'view_branches', 'group_name' => 'branches', 'guard_name' => 'web'],
            ['name' => 'create_branches', 'group_name' => 'branches', 'guard_name' => 'web'],
            ['name' => 'edit_branches', 'group_name' => 'branches', 'guard_name' => 'web'],
            ['name' => 'delete_branches', 'group_name' => 'branches', 'guard_name' => 'web'],

            // Team Member Management
            ['name' => 'view_team_members', 'group_name' => 'team_members', 'guard_name' => 'web'],
            ['name' => 'create_team_members', 'group_name' => 'team_members', 'guard_name' => 'web'],
            ['name' => 'edit_team_members', 'group_name' => 'team_members', 'guard_name' => 'web'],
            ['name' => 'delete_team_members', 'group_name' => 'team_members', 'guard_name' => 'web'],

            // Testimonial Management
            ['name' => 'view_testimonials', 'group_name' => 'testimonials', 'guard_name' => 'web'],
            ['name' => 'create_testimonials', 'group_name' => 'testimonials', 'guard_name' => 'web'],
            ['name' => 'edit_testimonials', 'group_name' => 'testimonials', 'guard_name' => 'web'],
            ['name' => 'delete_testimonials', 'group_name' => 'testimonials', 'guard_name' => 'web'],

            // Suggestions
            ['name' => 'view_suggestions', 'group_name' => 'suggestions', 'guard_name' => 'web'],
            ['name' => 'delete_suggestions', 'group_name' => 'suggestions', 'guard_name' => 'web'],

            // Contact Messages
            ['name' => 'view_contact_messages', 'group_name' => 'contacts', 'guard_name' => 'web'],
            ['name' => 'delete_contact_messages', 'group_name' => 'contacts', 'guard_name' => 'web'],

            // Newsletter
            ['name' => 'view_subscribers', 'group_name' => 'newsletter', 'guard_name' => 'web'],
            ['name' => 'add_subscribers', 'group_name' => 'newsletter', 'guard_name' => 'web'],
            ['name' => 'delete_subscribers', 'group_name' => 'newsletter', 'guard_name' => 'web'],
            ['name' => 'export_subscribers', 'group_name' => 'newsletter', 'guard_name' => 'web'],
            ['name' => 'view_campaigns', 'group_name' => 'newsletter', 'guard_name' => 'web'],
            ['name' => 'create_campaigns', 'group_name' => 'newsletter', 'guard_name' => 'web'],
            ['name' => 'edit_campaigns', 'group_name' => 'newsletter', 'guard_name' => 'web'],
            ['name' => 'delete_campaigns', 'group_name' => 'newsletter', 'guard_name' => 'web'],
            ['name' => 'send_campaigns', 'group_name' => 'newsletter', 'guard_name' => 'web'],
            ['name' => 'view_newsletter_templates', 'group_name' => 'newsletter', 'guard_name' => 'web'],
            ['name' => 'create_newsletter_templates', 'group_name' => 'newsletter', 'guard_name' => 'web'],
            ['name' => 'edit_newsletter_templates', 'group_name' => 'newsletter', 'guard_name' => 'web'],
            ['name' => 'delete_newsletter_templates', 'group_name' => 'newsletter', 'guard_name' => 'web'],

            // Themes
            ['name' => 'view_themes', 'group_name' => 'themes', 'guard_name' => 'web'],
            ['name' => 'upload_themes', 'group_name' => 'themes', 'guard_name' => 'web'],
            ['name' => 'activate_themes', 'group_name' => 'themes', 'guard_name' => 'web'],
            ['name' => 'delete_themes', 'group_name' => 'themes', 'guard_name' => 'web'],

            // Settings
            ['name' => 'view_settings', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'edit_settings', 'group_name' => 'settings', 'guard_name' => 'web'],

            // System Tools / Backups
            ['name' => 'view_backups', 'group_name' => 'system', 'guard_name' => 'web'],
            ['name' => 'create_backups', 'group_name' => 'system', 'guard_name' => 'web'],
            ['name' => 'upload_backups', 'group_name' => 'system', 'guard_name' => 'web'],
            ['name' => 'download_backups', 'group_name' => 'system', 'guard_name' => 'web'],
            ['name' => 'delete_backups', 'group_name' => 'system', 'guard_name' => 'web'],
            ['name' => 'restore_backups', 'group_name' => 'system', 'guard_name' => 'web']
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name'], 'guard_name' => $perm['guard_name']],
                ['group_name' => $perm['group_name']]
            );
        }

        $allPermissions = Permission::all();

        // 1. Super Admin Role
        $superAdmin = Role::findOrCreate('super_admin', 'web');
        $superAdmin->syncPermissions($allPermissions);

        // 2. Admin Role
        $admin = Role::findOrCreate('admin', 'web');
        $adminPermissions = $allPermissions->whereNotIn('name', [
            'delete_roles',
            'assign_permissions',
            'view_backups',
            'create_backups',
            'restore_backups',
            'delete_backups',
            'clear_activity_logs'
        ]);
        $admin->syncPermissions($adminPermissions);

        // 3. Editor Role
        $editor = Role::findOrCreate('editor', 'web');
        $editorPermissions = $allPermissions->whereIn('group_name', [
            'dashboard', 'profile', 'categories', 'blogs', 'books', 'videos', 'live_broadcasts',
            'galleries', 'sliders', 'banners', 'leaders', 'branches', 'team_members',
            'testimonials', 'suggestions', 'contacts', 'newsletter'
        ]);
        $editor->syncPermissions($editorPermissions);

        // 4. Writer Role
        $writer = Role::findOrCreate('writer', 'web');
        $writerPermissions = $allPermissions->whereIn('name', [
            'view_dashboard', 'view_own_profile', 'edit_own_profile', 'change_own_password',
            'view_blogs', 'create_blogs', 'edit_blogs', 'view_categories', 'view_books',
            'view_videos', 'view_live_broadcasts', 'view_galleries'
        ]);
        $writer->syncPermissions($writerPermissions);

        // 5. Subscriber Role
        $subscriber = Role::findOrCreate('subscriber', 'web');
        $subscriberPermissions = $allPermissions->whereIn('name', [
            'view_own_profile', 'edit_own_profile', 'change_own_password'
        ]);
        $subscriber->syncPermissions($subscriberPermissions);

        // Create Default Users if not exist
        $defaultUsers = [
            [
                'name'              => 'Super Admin',
                'email'             => env('SUPER_ADMIN_EMAIL', 'superadmin@example.com'),
                'password'          => env('SUPER_ADMIN_PASSWORD', 'SuperAdmin@2026'),
                'role'              => 'super_admin',
                'status'            => 'active',
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name'              => 'Admin User',
                'email'             => env('ADMIN_EMAIL', 'admin@example.com'),
                'password'          => env('ADMIN_PASSWORD', 'Admin@2026'),
                'role'              => 'admin',
                'status'            => 'active',
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name'              => 'Editor User',
                'email'             => 'editor@example.com',
                'password'          => 'Editor@2026',
                'role'              => 'editor',
                'status'            => 'active',
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name'              => 'Writer User',
                'email'             => 'writer@example.com',
                'password'          => 'Writer@2026',
                'role'              => 'writer',
                'status'            => 'active',
                'email_verified_at' => Carbon::now(),
            ]
        ];

        foreach ($defaultUsers as $userData) {
            $user = User::where('email', $userData['email'])->first();
            if (!$user) {
                $user = User::create([
                    'name'              => $userData['name'],
                    'email'             => $userData['email'],
                    'password'          => Hash::make($userData['password']),
                    'role'              => $userData['role'],
                    'status'            => $userData['status'],
                    'email_verified_at' => $userData['email_verified_at'],
                ]);
            }
            
            $user->assignRole($userData['role']);
        }
    }
}
