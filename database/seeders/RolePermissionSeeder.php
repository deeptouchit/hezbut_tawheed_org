<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('🚀 Starting Role & Permission Seeder');
        $this->command->info('═══════════════════════════════════════════════════════');

        // ⚠️ WARNING: This will delete all existing data
        $this->command->warn("\n⚠️  WARNING: This will DELETE all existing:");
        $this->command->warn("   - Permissions");
        $this->command->warn("   - Roles");
        $this->command->warn("   - Role assignments");
        $this->command->warn("   - Users (all existing users will be deleted!)");

        // Only confirm if in interactive console mode
        if ($this->command && app()->runningInConsole() && !app()->environment('testing')) {
            $output = $this->command->getOutput();
            if ($output && method_exists($output, 'isInteractive') && $output->isInteractive()) {
                if (!$this->command->confirm('Do you wish to continue?', false)) {
                    $this->command->info('Seeding cancelled.');
                    return;
                }
            }
        }

        // =============================================
        // CLEAN EXISTING DATA (Reset everything)
        // =============================================
        $this->command->info("\n🗑️  Cleaning existing data...");

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate all related tables (this will reset auto-increment to 1)
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();

        // Also truncate users table if you want fresh users
        DB::table('users')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✓ All existing data cleared successfully!');
        $this->command->info('✓ Auto-increment will start from ID 1');

        // =============================================
        // SEED NEW DATA
        // =============================================
        $this->command->info("\n📋 Creating permissions...");
        $this->seedPermissions();

        $this->command->info("\n👥 Creating roles & assigning permissions...");
        $this->seedRoles();

        $this->command->info("\n👤 Creating default users...");
        $this->seedUsers();

        $this->command->info("\n✅ Seeding completed successfully!");
        $this->command->info("═══════════════════════════════════════════════════════");
    }

    /**
     * Create all permissions for the e-commerce system.
     */
    private function seedPermissions(): void
    {
        $permissions = [
            // =============================================
            // DASHBOARD
            // =============================================
            ['name' => 'view_dashboard', 'group_name' => 'dashboard', 'guard_name' => 'web'],
            ['name' => 'view_dashboard_stats', 'group_name' => 'dashboard', 'guard_name' => 'web'],
            ['name' => 'view_recent_orders', 'group_name' => 'dashboard', 'guard_name' => 'web'],

            // =============================================
            // USER MANAGEMENT (Admin Users)
            // =============================================
            ['name' => 'view_users', 'group_name' => 'user_management', 'guard_name' => 'web'],
            ['name' => 'create_users', 'group_name' => 'user_management', 'guard_name' => 'web'],
            ['name' => 'edit_users', 'group_name' => 'user_management', 'guard_name' => 'web'],
            ['name' => 'delete_users', 'group_name' => 'user_management', 'guard_name' => 'web'],
            ['name' => 'toggle_user_status', 'group_name' => 'user_management', 'guard_name' => 'web'],
            ['name' => 'bulk_delete_users', 'group_name' => 'user_management', 'guard_name' => 'web'],
            ['name' => 'export_users', 'group_name' => 'user_management', 'guard_name' => 'web'],

            // =============================================
            // ROLE & PERMISSION MANAGEMENT
            // =============================================
            ['name' => 'view_roles', 'group_name' => 'role_management', 'guard_name' => 'web'],
            ['name' => 'create_roles', 'group_name' => 'role_management', 'guard_name' => 'web'],
            ['name' => 'edit_roles', 'group_name' => 'role_management', 'guard_name' => 'web'],
            ['name' => 'delete_roles', 'group_name' => 'role_management', 'guard_name' => 'web'],
            ['name' => 'assign_permissions', 'group_name' => 'role_management', 'guard_name' => 'web'],
            ['name' => 'view_role_permissions', 'group_name' => 'role_management', 'guard_name' => 'web'],

            // =============================================
            // PROFILE MANAGEMENT
            // =============================================
            ['name' => 'view_own_profile', 'group_name' => 'profile', 'guard_name' => 'web'],
            ['name' => 'edit_own_profile', 'group_name' => 'profile', 'guard_name' => 'web'],
            ['name' => 'change_own_password', 'group_name' => 'profile', 'guard_name' => 'web'],
            ['name' => 'view_profile_activities', 'group_name' => 'profile', 'guard_name' => 'web'],

            // =============================================
            // ACTIVITY LOG
            // =============================================
            ['name' => 'view_activity_logs', 'group_name' => 'activity', 'guard_name' => 'web'],
            ['name' => 'export_activity_logs', 'group_name' => 'activity', 'guard_name' => 'web'],
            ['name' => 'clear_activity_logs', 'group_name' => 'activity', 'guard_name' => 'web'],

            // =============================================
            // CATEGORY MANAGEMENT
            // =============================================
            ['name' => 'view_categories', 'group_name' => 'categories', 'guard_name' => 'web'],
            ['name' => 'create_categories', 'group_name' => 'categories', 'guard_name' => 'web'],
            ['name' => 'edit_categories', 'group_name' => 'categories', 'guard_name' => 'web'],
            ['name' => 'delete_categories', 'group_name' => 'categories', 'guard_name' => 'web'],
            ['name' => 'toggle_category_status', 'group_name' => 'categories', 'guard_name' => 'web'],
            ['name' => 'toggle_category_featured', 'group_name' => 'categories', 'guard_name' => 'web'],
            ['name' => 'update_category_order', 'group_name' => 'categories', 'guard_name' => 'web'],
            ['name' => 'export_categories', 'group_name' => 'categories', 'guard_name' => 'web'],
            ['name' => 'bulk_delete_categories', 'group_name' => 'categories', 'guard_name' => 'web'],

            // =============================================
            // BRAND MANAGEMENT
            // =============================================
            ['name' => 'view_brands', 'group_name' => 'brands', 'guard_name' => 'web'],
            ['name' => 'create_brands', 'group_name' => 'brands', 'guard_name' => 'web'],
            ['name' => 'edit_brands', 'group_name' => 'brands', 'guard_name' => 'web'],
            ['name' => 'delete_brands', 'group_name' => 'brands', 'guard_name' => 'web'],
            ['name' => 'toggle_brand_status', 'group_name' => 'brands', 'guard_name' => 'web'],
            ['name' => 'toggle_brand_featured', 'group_name' => 'brands', 'guard_name' => 'web'],
            ['name' => 'update_brand_order', 'group_name' => 'brands', 'guard_name' => 'web'],
            ['name' => 'export_brands', 'group_name' => 'brands', 'guard_name' => 'web'],
            ['name' => 'bulk_delete_brands', 'group_name' => 'brands', 'guard_name' => 'web'],

            // =============================================
            // PRODUCT MANAGEMENT
            // =============================================
            ['name' => 'view_products', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'create_products', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'edit_products', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'delete_products', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'toggle_product_status', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'duplicate_products', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'export_products', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'bulk_delete_products', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'manage_product_gallery', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'bulk_create_products', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'preview_bulk_csv', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'download_sample_csv', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'view_low_stock', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'update_stock_alert', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'update_stock_price', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'bulk_update_stock', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'export_low_stock', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'view_featured_products', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'add_featured_products', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'remove_featured_products', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'update_featured_order', 'group_name' => 'products', 'guard_name' => 'web'],
            ['name' => 'auto_add_featured', 'group_name' => 'products', 'guard_name' => 'web'],

            // =============================================
            // TAG MANAGEMENT
            // =============================================
            ['name' => 'view_tags', 'group_name' => 'tags', 'guard_name' => 'web'],
            ['name' => 'create_tags', 'group_name' => 'tags', 'guard_name' => 'web'],
            ['name' => 'edit_tags', 'group_name' => 'tags', 'guard_name' => 'web'],
            ['name' => 'delete_tags', 'group_name' => 'tags', 'guard_name' => 'web'],
            ['name' => 'toggle_tag_status', 'group_name' => 'tags', 'guard_name' => 'web'],
            ['name' => 'bulk_delete_tags', 'group_name' => 'tags', 'guard_name' => 'web'],
            ['name' => 'assign_product_tags', 'group_name' => 'tags', 'guard_name' => 'web'],
            ['name' => 'remove_product_tags', 'group_name' => 'tags', 'guard_name' => 'web'],
            ['name' => 'bulk_assign_tags', 'group_name' => 'tags', 'guard_name' => 'web'],

            // =============================================
            // REVIEW MANAGEMENT
            // =============================================
            ['name' => 'view_reviews', 'group_name' => 'reviews', 'guard_name' => 'web'],
            ['name' => 'approve_reviews', 'group_name' => 'reviews', 'guard_name' => 'web'],
            ['name' => 'disapprove_reviews', 'group_name' => 'reviews', 'guard_name' => 'web'],
            ['name' => 'reply_to_reviews', 'group_name' => 'reviews', 'guard_name' => 'web'],
            ['name' => 'delete_reviews', 'group_name' => 'reviews', 'guard_name' => 'web'],
            ['name' => 'bulk_review_actions', 'group_name' => 'reviews', 'guard_name' => 'web'],

            // =============================================
            // QUESTION MANAGEMENT
            // =============================================
            ['name' => 'view_questions', 'group_name' => 'questions', 'guard_name' => 'web'],
            ['name' => 'approve_questions', 'group_name' => 'questions', 'guard_name' => 'web'],
            ['name' => 'disapprove_questions', 'group_name' => 'questions', 'guard_name' => 'web'],
            ['name' => 'answer_questions', 'group_name' => 'questions', 'guard_name' => 'web'],
            ['name' => 'edit_question_answers', 'group_name' => 'questions', 'guard_name' => 'web'],
            ['name' => 'toggle_featured_questions', 'group_name' => 'questions', 'guard_name' => 'web'],
            ['name' => 'delete_questions', 'group_name' => 'questions', 'guard_name' => 'web'],
            ['name' => 'bulk_question_actions', 'group_name' => 'questions', 'guard_name' => 'web'],

            // =============================================
            // ORDER MANAGEMENT
            // =============================================
            ['name' => 'view_orders', 'group_name' => 'orders', 'guard_name' => 'web'],
            ['name' => 'view_pending_orders', 'group_name' => 'orders', 'guard_name' => 'web'],
            ['name' => 'view_processing_orders', 'group_name' => 'orders', 'guard_name' => 'web'],
            ['name' => 'view_shipped_orders', 'group_name' => 'orders', 'guard_name' => 'web'],
            ['name' => 'view_delivered_orders', 'group_name' => 'orders', 'guard_name' => 'web'],
            ['name' => 'view_cancelled_orders', 'group_name' => 'orders', 'guard_name' => 'web'],
            ['name' => 'view_pending_payment_orders', 'group_name' => 'orders', 'guard_name' => 'web'],
            ['name' => 'edit_orders', 'group_name' => 'orders', 'guard_name' => 'web'],
            ['name' => 'delete_orders', 'group_name' => 'orders', 'guard_name' => 'web'],
            ['name' => 'update_order_status', 'group_name' => 'orders', 'guard_name' => 'web'],
            ['name' => 'update_payment_status', 'group_name' => 'orders', 'guard_name' => 'web'],
            ['name' => 'view_order_invoices', 'group_name' => 'orders', 'guard_name' => 'web'],
            ['name' => 'download_invoices', 'group_name' => 'orders', 'guard_name' => 'web'],
            ['name' => 'cancel_orders', 'group_name' => 'orders', 'guard_name' => 'web'],
            ['name' => 'bulk_update_order_status', 'group_name' => 'orders', 'guard_name' => 'web'],
            ['name' => 'export_orders', 'group_name' => 'orders', 'guard_name' => 'web'],
            ['name' => 'print_orders', 'group_name' => 'orders', 'guard_name' => 'web'],

            // =============================================
            // DELIVERY ZONE MANAGEMENT
            // =============================================
            ['name' => 'view_delivery_zones', 'group_name' => 'deliveries', 'guard_name' => 'web'],
            ['name' => 'create_delivery_zones', 'group_name' => 'deliveries', 'guard_name' => 'web'],
            ['name' => 'edit_delivery_zones', 'group_name' => 'deliveries', 'guard_name' => 'web'],
            ['name' => 'delete_delivery_zones', 'group_name' => 'deliveries', 'guard_name' => 'web'],
            ['name' => 'toggle_delivery_zone_status', 'group_name' => 'deliveries', 'guard_name' => 'web'],

            // =============================================
            // DELIVERY MAN MANAGEMENT
            // =============================================
            ['name' => 'view_delivery_men', 'group_name' => 'deliveries', 'guard_name' => 'web'],
            ['name' => 'create_delivery_men', 'group_name' => 'deliveries', 'guard_name' => 'web'],
            ['name' => 'edit_delivery_men', 'group_name' => 'deliveries', 'guard_name' => 'web'],
            ['name' => 'delete_delivery_men', 'group_name' => 'deliveries', 'guard_name' => 'web'],
            ['name' => 'toggle_delivery_man_status', 'group_name' => 'deliveries', 'guard_name' => 'web'],
            ['name' => 'bulk_delete_delivery_men', 'group_name' => 'deliveries', 'guard_name' => 'web'],
            ['name' => 'export_delivery_men', 'group_name' => 'deliveries', 'guard_name' => 'web'],

            // =============================================
            // DELIVERY TRACKING & ASSIGNMENT
            // =============================================
            ['name' => 'view_delivery_tracking', 'group_name' => 'deliveries', 'guard_name' => 'web'],
            ['name' => 'view_pending_deliveries', 'group_name' => 'deliveries', 'guard_name' => 'web'],
            ['name' => 'assign_deliveries', 'group_name' => 'deliveries', 'guard_name' => 'web'],
            ['name' => 'update_delivery_status', 'group_name' => 'deliveries', 'guard_name' => 'web'],
            ['name' => 'view_delivery_timeline', 'group_name' => 'deliveries', 'guard_name' => 'web'],
            ['name' => 'view_delivery_assignments', 'group_name' => 'deliveries', 'guard_name' => 'web'],
            ['name' => 'cancel_delivery_assignments', 'group_name' => 'deliveries', 'guard_name' => 'web'],
            ['name' => 'export_delivery_data', 'group_name' => 'deliveries', 'guard_name' => 'web'],
            ['name' => 'public_track_orders', 'group_name' => 'deliveries', 'guard_name' => 'web'],

            // =============================================
            // CUSTOMER MANAGEMENT
            // =============================================
            ['name' => 'view_customers', 'group_name' => 'customers', 'guard_name' => 'web'],
            ['name' => 'create_customers', 'group_name' => 'customers', 'guard_name' => 'web'],
            ['name' => 'edit_customers', 'group_name' => 'customers', 'guard_name' => 'web'],
            ['name' => 'delete_customers', 'group_name' => 'customers', 'guard_name' => 'web'],
            ['name' => 'toggle_customer_status', 'group_name' => 'customers', 'guard_name' => 'web'],
            ['name' => 'verify_customer_phone', 'group_name' => 'customers', 'guard_name' => 'web'],
            ['name' => 'bulk_delete_customers', 'group_name' => 'customers', 'guard_name' => 'web'],
            ['name' => 'bulk_update_customer_status', 'group_name' => 'customers', 'guard_name' => 'web'],
            ['name' => 'bulk_export_customers', 'group_name' => 'customers', 'guard_name' => 'web'],
            ['name' => 'view_customer_orders', 'group_name' => 'customers', 'guard_name' => 'web'],
            ['name' => 'manage_loyalty_points', 'group_name' => 'customers', 'guard_name' => 'web'],
            ['name' => 'manage_wallet_balance', 'group_name' => 'customers', 'guard_name' => 'web'],
            ['name' => 'export_customers', 'group_name' => 'customers', 'guard_name' => 'web'],

            // =============================================
            // COUPON MANAGEMENT
            // =============================================
            ['name' => 'view_coupons', 'group_name' => 'coupons', 'guard_name' => 'web'],
            ['name' => 'create_coupons', 'group_name' => 'coupons', 'guard_name' => 'web'],
            ['name' => 'edit_coupons', 'group_name' => 'coupons', 'guard_name' => 'web'],
            ['name' => 'delete_coupons', 'group_name' => 'coupons', 'guard_name' => 'web'],
            ['name' => 'toggle_coupon_status', 'group_name' => 'coupons', 'guard_name' => 'web'],
            ['name' => 'bulk_delete_coupons', 'group_name' => 'coupons', 'guard_name' => 'web'],

            // =============================================
            // NEWSLETTER MANAGEMENT
            // =============================================
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
            ['name' => 'toggle_template_status', 'group_name' => 'newsletter', 'guard_name' => 'web'],
            ['name' => 'set_default_template', 'group_name' => 'newsletter', 'guard_name' => 'web'],
            ['name' => 'preview_templates', 'group_name' => 'newsletter', 'guard_name' => 'web'],

            // =============================================
            // SLIDER MANAGEMENT
            // =============================================
            ['name' => 'view_sliders', 'group_name' => 'sliders', 'guard_name' => 'web'],
            ['name' => 'create_sliders', 'group_name' => 'sliders', 'guard_name' => 'web'],
            ['name' => 'edit_sliders', 'group_name' => 'sliders', 'guard_name' => 'web'],
            ['name' => 'delete_sliders', 'group_name' => 'sliders', 'guard_name' => 'web'],
            ['name' => 'toggle_slider_status', 'group_name' => 'sliders', 'guard_name' => 'web'],
            ['name' => 'update_slider_order', 'group_name' => 'sliders', 'guard_name' => 'web'],

            // =============================================
            // REPORT MANAGEMENT
            // =============================================
            ['name' => 'view_sales_reports', 'group_name' => 'reports', 'guard_name' => 'web'],
            ['name' => 'export_sales_reports', 'group_name' => 'reports', 'guard_name' => 'web'],
            ['name' => 'view_product_reports', 'group_name' => 'reports', 'guard_name' => 'web'],
            ['name' => 'export_product_reports', 'group_name' => 'reports', 'guard_name' => 'web'],
            ['name' => 'view_customer_reports', 'group_name' => 'reports', 'guard_name' => 'web'],
            ['name' => 'export_customer_reports', 'group_name' => 'reports', 'guard_name' => 'web'],
            ['name' => 'view_profit_reports', 'group_name' => 'reports', 'guard_name' => 'web'],
            ['name' => 'export_profit_reports', 'group_name' => 'reports', 'guard_name' => 'web'],

            // =============================================
            // SETTINGS MANAGEMENT
            // =============================================
            ['name' => 'view_general_settings', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'update_general_settings', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'delete_general_settings', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'view_payment_methods', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'create_payment_methods', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'edit_payment_methods', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'delete_payment_methods', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'manage_payment_gateway', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'view_email_templates', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'create_email_templates', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'edit_email_templates', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'delete_email_templates', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'preview_email_templates', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'toggle_email_template_status', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'duplicate_email_templates', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'view_placeholders', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'test_email_render', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'view_mail_settings', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'update_mail_settings', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'test_mail_connection', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'test_mail_send', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'get_mail_config', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'quick_ping_mail', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'view_sms_gateway', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'update_sms_gateway', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'test_sms_gateway', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'check_sms_balance', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'view_sms_templates', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'create_sms_templates', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'edit_sms_templates', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'delete_sms_templates', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'toggle_sms_template_status', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'preview_sms_templates', 'group_name' => 'settings', 'guard_name' => 'web'],
            ['name' => 'test_sms_templates', 'group_name' => 'settings', 'guard_name' => 'web'],

            // =============================================
            // SYSTEM TOOLS
            // =============================================
            ['name' => 'view_cache_tools', 'group_name' => 'system', 'guard_name' => 'web'],
            ['name' => 'run_cache_commands', 'group_name' => 'system', 'guard_name' => 'web'],
            ['name' => 'run_custom_cache_commands', 'group_name' => 'system', 'guard_name' => 'web'],
            ['name' => 'view_cache_status', 'group_name' => 'system', 'guard_name' => 'web'],
            ['name' => 'view_backups', 'group_name' => 'system', 'guard_name' => 'web'],
            ['name' => 'create_backups', 'group_name' => 'system', 'guard_name' => 'web'],
            ['name' => 'upload_backups', 'group_name' => 'system', 'guard_name' => 'web'],
            ['name' => 'download_backups', 'group_name' => 'system', 'guard_name' => 'web'],
            ['name' => 'delete_backups', 'group_name' => 'system', 'guard_name' => 'web'],
            ['name' => 'restore_backups', 'group_name' => 'system', 'guard_name' => 'web'],
            ['name' => 'verify_backups', 'group_name' => 'system', 'guard_name' => 'web'],
            ['name' => 'view_backup_details', 'group_name' => 'system', 'guard_name' => 'web'],
            ['name' => 'cleanup_backups', 'group_name' => 'system', 'guard_name' => 'web'],
        ];

        // Remove duplicates from permissions array (if any)
        $uniquePermissions = [];
        foreach ($permissions as $permission) {
            $uniquePermissions[$permission['name']] = $permission;
        }
        $uniquePermissions = array_values($uniquePermissions);

        $progress = $this->command->getOutput()->createProgressBar(count($uniquePermissions));
        $progress->start();

        foreach ($uniquePermissions as $permission) {
            Permission::create($permission);
            $progress->advance();
        }

        $progress->finish();
        $this->command->info("\n✓ " . count($uniquePermissions) . " permissions created successfully!");
    }

    /**
     * Create roles and assign appropriate permissions.
     */
    private function seedRoles(): void
    {
        $allPermissions = Permission::all();

        // 1. SUPER ADMIN - Full access to everything
        $superAdmin = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions($allPermissions);
        $this->command->info("✓ super_admin: " . $superAdmin->permissions->count() . " permissions");

        // 2. ADMIN - Almost full access except critical permissions
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $adminPermissions = $allPermissions->whereNotIn('name', [
            'delete_roles',
            'assign_permissions',
            'view_backups',
            'create_backups',
            'restore_backups',
            'delete_backups',
            'run_cache_commands',
            'run_custom_cache_commands',
            'clear_activity_logs',
        ]);
        $admin->syncPermissions($adminPermissions);
        $this->command->info("✓ admin: " . $admin->permissions->count() . " permissions");

        // 3. PRODUCT MANAGER
        $productManager = Role::create(['name' => 'product_manager', 'guard_name' => 'web']);
        $productManagerPermissions = $allPermissions->whereIn('group_name', [
            'dashboard', 'categories', 'brands', 'products', 'tags', 'reviews', 'questions'
        ]);
        $productManager->syncPermissions($productManagerPermissions);
        $this->command->info("✓ product_manager: " . $productManager->permissions->count() . " permissions");

        // 4. ORDER MANAGER
        $orderManager = Role::create(['name' => 'order_manager', 'guard_name' => 'web']);
        $orderManagerPermissions = $allPermissions->whereIn('group_name', [
            'dashboard', 'orders', 'deliveries', 'customers', 'coupons'
        ]);
        $orderManager->syncPermissions($orderManagerPermissions);
        $this->command->info("✓ order_manager: " . $orderManager->permissions->count() . " permissions");

        // 5. CUSTOMER SUPPORT
        $customerSupport = Role::create(['name' => 'customer_support', 'guard_name' => 'web']);
        $customerSupportPermissions = $allPermissions->whereIn('group_name', [
            'dashboard', 'profile', 'customers', 'orders', 'reviews', 'questions'
        ])->whereNotIn('name', [
            'delete_customers', 'delete_orders', 'bulk_delete_customers', 'bulk_delete_orders',
            'export_customers', 'export_orders', 'manage_loyalty_points', 'manage_wallet_balance'
        ]);
        $customerSupport->syncPermissions($customerSupportPermissions);
        $this->command->info("✓ customer_support: " . $customerSupport->permissions->count() . " permissions");

        // 6. DELIVERY MAN
        $deliveryMan = Role::create(['name' => 'delivery_man', 'guard_name' => 'web']);
        $deliveryManPermissions = $allPermissions->whereIn('name', [
            'view_dashboard',
            'view_delivery_tracking',
            'update_delivery_status',
            'view_delivery_assignments',
            'view_delivery_timeline',
            'view_orders',
            'view_own_profile',
            'edit_own_profile',
            'change_own_password',
            'public_track_orders',
        ]);
        $deliveryMan->syncPermissions($deliveryManPermissions);
        $this->command->info("✓ delivery_man: " . $deliveryMan->permissions->count() . " permissions");

        // 7. CUSTOMER - Frontend user role
        $customer = Role::create(['name' => 'customer', 'guard_name' => 'web']);
        $customerPermissions = $allPermissions->whereIn('name', [
            'view_own_profile',
            'edit_own_profile',
            'change_own_password',
            'view_profile_activities',
            'public_track_orders',
        ]);
        $customer->syncPermissions($customerPermissions);
        $this->command->info("✓ customer: " . $customer->permissions->count() . " permissions");
    }

    /**
     * Create default system users for each role.
     */
    private function seedUsers(): void
    {
        $defaultUsers = [
            [
                'name'              => 'Super Admin',
                'email'             => 'superadmin@example.com',
                'password'          => 'SuperAdmin@2024',
                'role'              => 'super_admin',
                'status'            => 'active',
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name'              => 'Admin User',
                'email'             => 'admin@example.com',
                'password'          => 'Admin@2024',
                'role'              => 'admin',
                'status'            => 'active',
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name'              => 'Product Manager',
                'email'             => 'product.manager@example.com',
                'password'          => 'Product@2024',
                'role'              => 'product_manager',
                'status'            => 'active',
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name'              => 'Order Manager',
                'email'             => 'order.manager@example.com',
                'password'          => 'Order@2024',
                'role'              => 'order_manager',
                'status'            => 'active',
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name'              => 'Customer Support',
                'email'             => 'support@example.com',
                'password'          => 'Support@2024',
                'role'              => 'customer_support',
                'status'            => 'active',
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name'              => 'Delivery Man',
                'email'             => 'delivery@example.com',
                'password'          => 'Delivery@2024',
                'role'              => 'delivery_man',
                'status'            => 'active',
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name'              => 'Test Customer',
                'email'             => 'customer@example.com',
                'password'          => 'Customer@2024',
                'role'              => 'customer',
                'status'            => 'active',
                'email_verified_at' => Carbon::now(),
            ],
        ];

        $progress = $this->command->getOutput()->createProgressBar(count($defaultUsers));
        $progress->start();

        foreach ($defaultUsers as $userData) {
            $user = User::create([
                'name'              => $userData['name'],
                'email'             => $userData['email'],
                'password'          => Hash::make($userData['password']),
                'role'              => $userData['role'],
                'status'            => $userData['status'],
                'email_verified_at' => $userData['email_verified_at'],
            ]);

            // Assign role
            $user->assignRole($userData['role']);

            $progress->advance();
        }

        $progress->finish();
        $this->command->info("\n✓ " . count($defaultUsers) . " default users created successfully!");

        // Display user credentials
        $this->command->info("\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->command->info("🔐 DEFAULT USER CREDENTIALS");
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->command->info("📧 superadmin@example.com  |  🔑 SuperAdmin@2024  |  👑 Super Admin");
        $this->command->info("📧 admin@example.com        |  🔑 Admin@2024       |  👤 Admin");
        $this->command->info("📧 product.manager@example.com | 🔑 Product@2024   |  📦 Product Manager");
        $this->command->info("📧 order.manager@example.com   | 🔑 Order@2024     |  📋 Order Manager");
        $this->command->info("📧 support@example.com      |  🔑 Support@2024    |  🎧 Customer Support");
        $this->command->info("📧 delivery@example.com     |  🔑 Delivery@2024   |  🚚 Delivery Man");
        $this->command->info("📧 customer@example.com     |  🔑 Customer@2024   |  👨‍💻 Customer");
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->command->warn("⚠️  PLEASE CHANGE THESE PASSWORDS IN PRODUCTION!");
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
    }
}
