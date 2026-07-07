<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogCommentController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CacheToolsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\LandingPageController;
use App\Http\Controllers\Admin\MailManagementController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PaymentGatewayManagementController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductQuestionController;
use App\Http\Controllers\Admin\ProductReviewController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ServiceFeatureController;
use App\Http\Controllers\Admin\PromoBannersController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\PageController;

use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\LiveBroadcastController;
use App\Http\Controllers\Admin\LeaderController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SuggestionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Guest Routes (Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.submit');
});

/*
|--------------------------------------------------------------------------
| Admin Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Auth
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


    // Dashboard
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/stats', [AdminDashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('dashboard/chart-data', [AdminDashboardController::class, 'getChartData'])->name('dashboard.chart-data');

    // E-commerce routes (Categories, Landing Pages, Tags, Reviews, Questions) removed




    // Newsletter
    Route::get('newsletter-subscribers', [NewsletterController::class, 'subscribers'])->name('newsletter.subscribers.index');
    Route::post('newsletter-subscribers/add', [NewsletterController::class, 'addSubscriber'])->name('newsletter.subscribers.add');
    Route::delete('newsletter-subscribers/{id}', [NewsletterController::class, 'deleteSubscriber'])->name('newsletter.subscribers.delete');
    Route::get('newsletter-subscribers/export', [NewsletterController::class, 'exportSubscribers'])->name('newsletter.subscribers.export');

    Route::get('newsletter-campaigns', [NewsletterController::class, 'campaigns'])->name('newsletter.campaigns.index');
    Route::get('newsletter-campaigns/create', [NewsletterController::class, 'createCampaign'])->name('newsletter.campaigns.create');
    Route::post('newsletter-campaigns/store', [NewsletterController::class, 'storeCampaign'])->name('newsletter.campaigns.store');
    Route::get('newsletter-campaigns/{id}', [NewsletterController::class, 'showCampaign'])->name('newsletter.campaigns.show');
    Route::delete('newsletter-campaigns/{id}', [NewsletterController::class, 'deleteCampaign'])->name('newsletter.campaigns.delete');
    Route::post('newsletter-campaigns/{id}/send', [NewsletterController::class, 'sendCampaign'])->name('newsletter.campaigns.send');

    Route::get('newsletter/templates', [NewsletterController::class, 'templates'])->name('newsletter.templates.index');
    Route::get('newsletter/templates/create', [NewsletterController::class, 'createTemplate'])->name('newsletter.templates.create');
    Route::post('newsletter/templates/store', [NewsletterController::class, 'storeTemplate'])->name('newsletter.templates.store');
    Route::get('newsletter/templates/{id}/edit', [NewsletterController::class, 'editTemplate'])->name('newsletter.templates.edit');
    Route::put('newsletter/templates/{id}', [NewsletterController::class, 'updateTemplate'])->name('newsletter.templates.update');
    Route::delete('newsletter/templates/{id}', [NewsletterController::class, 'deleteTemplate'])->name('newsletter.templates.delete');
    Route::post('newsletter/templates/{id}/toggle-status', [NewsletterController::class, 'toggleTemplateStatus'])->name('newsletter.templates.toggle-status');
    Route::post('newsletter/templates/{id}/set-default', [NewsletterController::class, 'setDefaultTemplate'])->name('newsletter.templates.set-default');
    Route::get('newsletter/templates/{id}/preview', [NewsletterController::class, 'previewTemplate'])->name('newsletter.templates.preview');


    // Settings Routes
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    Route::post('/settings/create', [SettingController::class, 'createSetting'])->name('settings.create');
    Route::put('/settings/{id}', [SettingController::class, 'updateSetting'])->name('settings.update');
    Route::post('/settings/update-value/{id}', [SettingController::class, 'updateValue'])->name('settings.update-value');
    Route::delete('/settings/{id}', [SettingController::class, 'destroy'])->name('settings.destroy');
    Route::post('/settings/bulk-delete', [SettingController::class, 'bulkDelete'])->name('settings.bulk-delete');
    Route::get('/settings/export', [SettingController::class, 'export'])->name('settings.export');
    Route::post('/settings/import', [SettingController::class, 'import'])->name('settings.import');

    // Payment routes (Methods, Gateways) removed

    // Email Templates
    Route::resource('email-templates', EmailTemplateController::class);
    Route::get('email-templates/{email_template}/preview', [EmailTemplateController::class, 'preview'])->name('email-templates.preview');
    Route::post('email-templates/{email_template}/toggle-status', [EmailTemplateController::class, 'toggleStatus'])->name('email-templates.toggle-status');
    Route::post('email-templates/bulk-delete', [EmailTemplateController::class, 'bulkDelete'])->name('email-templates.bulk-delete');
    Route::post('email-templates/{email_template}/duplicate', [EmailTemplateController::class, 'duplicate'])->name('email-templates.duplicate');
    Route::get('email-templates/{email_template}/placeholders', [EmailTemplateController::class, 'getPlaceholders'])->name('email-templates.placeholders');
    Route::post('email-templates/{email_template}/test-render', [EmailTemplateController::class, 'testRender'])->name('email-templates.test-render');

    // Mail/SMTP
    Route::get('email', [MailManagementController::class, 'index'])->name('email.index');
    Route::post('email', [MailManagementController::class, 'update'])->name('email.update');
    Route::post('email/test', [MailManagementController::class, 'testMail'])->name('email.test');
    Route::post('email/test-connection', [MailManagementController::class, 'testConnection'])->name('email.test.connection');
    Route::get('email/config', [MailManagementController::class, 'getConfig'])->name('email.config');
    Route::post('email/quick-ping', [MailManagementController::class, 'quickPing'])->name('email.quick.ping');
    // SMS routes commented out because SmsServiceProvider was removed

    // Cache Tools
    Route::get('cache-tools', [CacheToolsController::class, 'index'])->name('cache-tools.index');
    Route::post('cache-tools/run', [CacheToolsController::class, 'run'])->name('cache-tools.run');
    Route::post('cache-tools/custom-run', [CacheToolsController::class, 'runCustom'])->name('cache-tools.custom-run');
    Route::get('cache-tools/status', [CacheToolsController::class, 'getStatus'])->name('cache-tools.status');

    // Backup
    Route::get('backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('backup/create', [BackupController::class, 'create'])->name('backup.create');
    Route::post('backup/upload', [BackupController::class, 'upload'])->name('backup.upload');
    Route::get('backup/{backupId}/download', [BackupController::class, 'download'])->name('backup.download');
    Route::delete('backup/{backupId}', [BackupController::class, 'destroy'])->name('backup.destroy');
    Route::post('backup/{backupId}/restore', [BackupController::class, 'restore'])->name('backup.restore');
    Route::get('backup/{backupId}/verify', [BackupController::class, 'verify'])->name('backup.verify');
    Route::get('backup/{backupId}/details', [BackupController::class, 'details'])->name('backup.details');
    Route::post('backup/cleanup', [BackupController::class, 'cleanup'])->name('backup.cleanup');

    // Permissions / User Management
    Route::get('permissions/groups', [PermissionController::class, 'getGroups'])->name('permissions.groups');
    Route::resource('permissions', PermissionController::class);

    Route::get('roles/{id}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
    Route::post('roles/{id}/permissions', [RoleController::class, 'syncPermissions'])->name('roles.permissions.sync');
    Route::resource('roles', RoleController::class);

    Route::resource('users', UserController::class);
    Route::delete('users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::patch('users/{user}/status', [UserController::class, 'updateStatus'])->name('users.update-status');
    Route::get('users/export', [UserController::class, 'export'])->name('users.export');

    // =============================================
    // ACTIVITY LOGS MANAGEMENT
    // =============================================
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('activity-logs/statistics', [ActivityLogController::class, 'statistics'])->name('activity-logs.statistics');
    Route::get('activity-logs/export', [ActivityLogController::class, 'export'])->name('activity-logs.export');
    Route::get('activity-logs/{id}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
    Route::delete('activity-logs/{id}', [ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
    Route::post('activity-logs/clear', [ActivityLogController::class, 'clearAll'])->name('activity-logs.clear');

    // =============================================
    // PROFILE MANAGEMENT
    // =============================================
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
    Route::get('/profile/stats', [ProfileController::class, 'getStats'])->name('profile.stats');
    Route::get('/profile/activities', [ProfileController::class, 'getActivities'])->name('profile.activities');
    Route::get('/profile/sessions', [ProfileController::class, 'getSessions'])->name('profile.sessions');
    Route::post('/profile/logout-all', [ProfileController::class, 'logoutAllDevices'])->name('profile.logout-all');
    Route::post('/profile/logout-session', [ProfileController::class, 'logoutSession'])->name('profile.logout-session');
    Route::post('/profile/notifications', [ProfileController::class, 'updateNotifications'])->name('profile.notifications');
    Route::get('/profile/download-data', [ProfileController::class, 'downloadData'])->name('profile.download-data');

    // =============================================
    // PASSWORD MANAGEMENT
    // =============================================
    Route::get('/password/change', [ProfileController::class, 'changePassword'])->name('password.change');
    Route::post('/password/send-otp', [ProfileController::class, 'sendOtp'])->name('password.send-otp');
    Route::post('/password/verify', [ProfileController::class, 'verifyAndChangePassword'])->name('password.verify');

    // =============================================
    // NOTIFICATION ROUTES
    // =============================================
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/fetch', [NotificationController::class, 'fetch'])->name('notifications.fetch');
    Route::get('notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('notifications/delete-all-read', [NotificationController::class, 'deleteAllRead'])->name('notifications.delete-all-read');
    Route::get('notifications/settings', [NotificationController::class, 'getSettings'])->name('notifications.settings.get');
    Route::post('notifications/settings', [NotificationController::class, 'updateSettings'])->name('notifications.settings.update');





    // =============================================
    // THEME MANAGEMENT ROUTES
    // =============================================
    Route::get('themes', [ThemeController::class, 'index'])->name('themes.index');
    Route::post('themes/upload', [ThemeController::class, 'upload'])->name('themes.upload');
    Route::post('themes/{id}/activate', [ThemeController::class, 'activate'])->name('themes.activate');
    Route::post('themes/{id}/deactivate', [ThemeController::class, 'deactivate'])->name('themes.deactivate');
    Route::delete('themes/{id}', [ThemeController::class, 'destroy'])->name('themes.destroy');
    Route::get('/themes/{id}/preview', [ThemeController::class, 'preview'])->name('themes.preview');
    Route::get('/themes/exit-preview', [ThemeController::class, 'exitPreview'])->name('themes.exit-preview');

    // =============================================
    // MENU MANAGEMENT ROUTES
    // =============================================
    Route::get('menu', [MenuController::class, 'index'])->name('menu.index');
    Route::post('menu/update', [MenuController::class, 'update'])->name('menu.update');
    Route::post('menu/add-item', [MenuController::class, 'addItem'])->name('menu.add.item');
    Route::post('menu/update-item/{id}', [MenuController::class, 'updateItem'])->name('menu.update.item');
    Route::delete('menu/delete-item/{id}', [MenuController::class, 'deleteItem'])->name('menu.delete.item');
    Route::post('menu/reorder', [MenuController::class, 'reorder'])->name('menu.reorder');
    Route::post('menu/cache/refresh', [MenuController::class, 'refreshCache'])->name('menu.cache.refresh');
    Route::get('menu/preview', [MenuController::class, 'preview'])->name('menu.preview');

    // =============================================
    // SLIDER MANAGEMENT ROUTES
    // =============================================
    Route::get('sliders', [SliderController::class, 'index'])->name('sliders.index');
    Route::get('sliders/create', [SliderController::class, 'create'])->name('sliders.create');
    Route::post('sliders/store', [SliderController::class, 'store'])->name('sliders.store');
    Route::get('sliders/{id}/edit', [SliderController::class, 'edit'])->name('sliders.edit');
    Route::put('sliders/{id}', [SliderController::class, 'update'])->name('sliders.update');
    Route::delete('sliders/{id}', [SliderController::class, 'destroy'])->name('sliders.destroy');
    Route::post('sliders/{id}/toggle-status', [SliderController::class, 'toggleStatus'])->name('sliders.toggle-status');
    Route::post('sliders/update-order', [SliderController::class, 'updateOrder'])->name('sliders.update-order');

    // =============================================
    // HOMEPAGE BUILDER ROUTES
    // =============================================
    Route::get('homepage-builder', [\App\Http\Controllers\Admin\HomepageController::class, 'index'])->name('homepage-builder.index');
    Route::post('homepage-builder/update', [\App\Http\Controllers\Admin\HomepageController::class, 'update'])->name('homepage-builder.update');

    // Service Features & Promo Banners routes removed


    // =============================================
    // TESTIMONIALS ROUTES
    // =============================================
    Route::resource('testimonials', TestimonialController::class)->except(['show']);
    Route::get('testimonials/{testimonial}', [TestimonialController::class, 'show'])->name('testimonials.show');
    Route::post('testimonials/{testimonial}/toggle-status', [TestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status');
    Route::post('testimonials/reorder', [TestimonialController::class, 'reorder'])->name('testimonials.reorder');
    Route::get('testimonials/export', [TestimonialController::class, 'export'])->name('testimonials.export');
    Route::post('testimonials/bulk-delete', [TestimonialController::class, 'bulkDelete'])->name('testimonials.bulk-delete');



// =============================================
// BLOG CATEGORIES ROUTES
// =============================================
Route::get('blog/categories', [BlogCategoryController::class, 'index'])->name('blog.categories.index');
Route::get('blog/categories/create', [BlogCategoryController::class, 'create'])->name('blog.categories.create');
Route::post('blog/categories', [BlogCategoryController::class, 'store'])->name('blog.categories.store');
Route::get('blog/categories/{id}/edit', [BlogCategoryController::class, 'edit'])->name('blog.categories.edit');
Route::put('blog/categories/{id}', [BlogCategoryController::class, 'update'])->name('blog.categories.update');
Route::delete('blog/categories/{id}', [BlogCategoryController::class, 'destroy'])->name('blog.categories.destroy');
Route::post('blog/categories/{id}/toggle-status', [BlogCategoryController::class, 'toggleStatus'])->name('blog.categories.toggle-status');
Route::post('blog/categories/reorder', [BlogCategoryController::class, 'reorder'])->name('blog.categories.reorder');
Route::get('blog/categories/export', [BlogCategoryController::class, 'export'])->name('blog.categories.export');
Route::post('blog/categories/bulk-delete', [BlogCategoryController::class, 'bulkDelete'])->name('blog.categories.bulk-delete');
Route::get('blog/categories/get-all', [BlogCategoryController::class, 'getAll'])->name('blog.categories.get-all');
Route::get('blog/categories/statistics', [BlogCategoryController::class, 'getStatistics'])->name('blog.categories.statistics');
Route::get('blog/categories/{id}/show', [BlogCategoryController::class, 'show'])->name('blog.categories.show');
Route::get('blog/categories/{slug}', [BlogCategoryController::class, 'showBySlug'])->name('blog.categories.show-by-slug');



// =============================================
// BLOG POSTS ROUTES
// =============================================

Route::get('blog/posts', [BlogController::class, 'index'])->name('blog.posts.index');
Route::get('blog/posts/create', [BlogController::class, 'create'])->name('blog.posts.create');
Route::post('blog/posts', [BlogController::class, 'store'])->name('blog.posts.store');
Route::get('blog/posts/{id}', [BlogController::class, 'show'])->name('blog.posts.show');
Route::get('blog/posts/{id}/edit', [BlogController::class, 'edit'])->name('blog.posts.edit');
Route::put('blog/posts/{id}', [BlogController::class, 'update'])->name('blog.posts.update');
Route::delete('blog/posts/{id}', [BlogController::class, 'destroy'])->name('blog.posts.destroy');
Route::post('blog/posts/{id}/toggle-status', [BlogController::class, 'toggleStatus'])->name('blog.posts.toggle-status');
Route::post('blog/posts/bulk-delete', [BlogController::class, 'bulkDelete'])->name('blog.posts.bulk-delete');
Route::get('blog/posts/export', [BlogController::class, 'export'])->name('blog.posts.export');
Route::post('blog/posts/{id}/duplicate', [BlogController::class, 'duplicate'])->name('blog.posts.duplicate');
Route::post('blog/posts/{id}/increment-views', [BlogController::class, 'incrementViews'])->name('blog.posts.increment-views');
Route::get('blog/posts/{id}/related', [BlogController::class, 'getRelatedPosts'])->name('blog.posts.related');
Route::post('blog/posts/bulk-status', [BlogController::class, 'bulkStatusUpdate'])->name('blog.posts.bulk-status');
Route::post('blog/posts/bulk-category', [BlogController::class, 'bulkCategoryUpdate'])->name('blog.posts.bulk-category');
Route::post('blog/posts/bulk-gallery', [BlogController::class, 'bulkGalleryUpdate'])->name('blog.posts.bulk-gallery');
Route::post('blog/posts/{id}/toggle-gallery', [BlogController::class, 'toggleGallery'])->name('blog.posts.toggle-gallery');
Route::post('blog/posts/reorder-gallery', [BlogController::class, 'reorderGallery'])->name('blog.posts.reorder-gallery');
Route::get('gallery', [BlogController::class, 'galleryIndex'])->name('gallery.index');
Route::post('gallery/add', [BlogController::class, 'galleryAdd'])->name('gallery.add');
Route::get('blog/posts/by-category/{categoryId}', [BlogController::class, 'getPostsByCategory'])->name('blog.posts.by-category');
Route::get('blog/posts/by-tag/{tag}', [BlogController::class, 'getPostsByTag'])->name('blog.posts.by-tag');
Route::get('blog/posts/search', [BlogController::class, 'searchPosts'])->name('blog.posts.search');
Route::get('blog/posts/recent', [BlogController::class, 'getRecentPosts'])->name('blog.posts.recent');
Route::get('blog/posts/popular', [BlogController::class, 'getPopularPosts'])->name('blog.posts.popular');
Route::get('blog/posts/statistics', [BlogController::class, 'getStatistics'])->name('blog.posts.statistics');
Route::post('blog/posts/reorder', [BlogController::class, 'reorder'])->name('blog.posts.reorder');

// =============================================
// BLOG COMMENTS ROUTES
// =============================================

Route::get('blog/comments', [BlogCommentController::class, 'index'])->name('blog.comments.index');
Route::get('blog/comments/{id}', [BlogCommentController::class, 'show'])->name('blog.comments.show');
Route::post('blog/comments/{id}/approve', [BlogCommentController::class, 'approve'])->name('blog.comments.approve');
Route::post('blog/comments/{id}/disapprove', [BlogCommentController::class, 'disapprove'])->name('blog.comments.disapprove');
Route::delete('blog/comments/{id}', [BlogCommentController::class, 'destroy'])->name('blog.comments.destroy');
Route::post('blog/comments/bulk-delete', [BlogCommentController::class, 'bulkDelete'])->name('blog.comments.bulk-delete');
Route::post('blog/comments/bulk-approve', [BlogCommentController::class, 'bulkApprove'])->name('blog.comments.bulk-approve');
Route::get('blog/comments/export', [BlogCommentController::class, 'export'])->name('blog.comments.export');
Route::get('blog/comments/by-blog/{blogId}', [BlogCommentController::class, 'getCommentsByBlog'])->name('blog.comments.by-blog');
Route::post('blog/comments/{id}/reply', [BlogCommentController::class, 'reply'])->name('blog.comments.reply');
Route::delete('blog/comments/pending', [BlogCommentController::class, 'deleteAllPending'])->name('blog.comments.delete-pending');


// =============================================
// CONTACT MESSAGES ROUTES
// =============================================

Route::get('contacts/fetch-unread', [ContactMessageController::class, 'fetchUnread'])->name('contacts.fetch-unread');
Route::get('contacts', [ContactMessageController::class, 'index'])->name('contacts.index');
Route::get('contacts/{id}', [ContactMessageController::class, 'show'])->name('contacts.show');
Route::post('contacts/{id}/reply', [ContactMessageController::class, 'reply'])->name('contacts.reply');
Route::delete('contacts/{id}', [ContactMessageController::class, 'destroy'])->name('contacts.destroy');
Route::post('contacts/bulk-delete', [ContactMessageController::class, 'bulkDelete'])->name('contacts.bulk-delete');
Route::post('contacts/mark-as-read', [ContactMessageController::class, 'markAsRead'])->name('contacts.mark-as-read');
Route::post('contacts/mark-as-unread', [ContactMessageController::class, 'markAsUnread'])->name('contacts.mark-as-unread');
    Route::get('contacts/export', [ContactMessageController::class, 'export'])->name('contacts.export');
    Route::get('contacts/statistics', [ContactMessageController::class, 'getStatistics'])->name('contacts.statistics');

    // =============================================
    // SUGGESTIONS / FEEDBACK ROUTES
    // =============================================
    Route::get('suggestions', [SuggestionController::class, 'index'])->name('suggestions.index');
    Route::get('suggestions/{id}', [SuggestionController::class, 'show'])->name('suggestions.show');
    Route::post('suggestions/{id}/toggle-status', [SuggestionController::class, 'toggleStatus'])->name('suggestions.toggle-status');
    Route::delete('suggestions/{id}', [SuggestionController::class, 'destroy'])->name('suggestions.destroy');
    Route::post('suggestions/bulk-delete', [SuggestionController::class, 'bulkDelete'])->name('suggestions.bulk-delete');

    // =============================================
    // PAGE MANAGEMENT ROUTES
    // =============================================
    Route::get('pages', [PageController::class, 'index'])->name('pages.index');
    Route::get('pages/create', [PageController::class, 'create'])->name('pages.create');
    Route::post('pages', [PageController::class, 'store'])->name('pages.store');
    Route::get('pages/{id}/edit', [PageController::class, 'edit'])->name('pages.edit');
    Route::put('pages/{id}', [PageController::class, 'update'])->name('pages.update');
    Route::delete('pages/{id}', [PageController::class, 'destroy'])->name('pages.destroy');
    Route::post('pages/{id}/toggle-status', [PageController::class, 'toggleStatus'])->name('pages.toggle-status');



    // =============================================
    // BOOK MANAGEMENT ROUTES
    // =============================================
    Route::get('books', [BookController::class, 'index'])->name('books.index');
    Route::get('books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('books', [BookController::class, 'store'])->name('books.store');
    Route::get('books/{id}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('books/{id}', [BookController::class, 'update'])->name('books.update');
    Route::delete('books/{id}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::post('books/{id}/toggle-status', [BookController::class, 'toggleStatus'])->name('books.toggle-status');
    Route::post('books/{id}/toggle-popular', [BookController::class, 'togglePopular'])->name('books.toggle-popular');
    Route::post('books/{id}/update-order', [BookController::class, 'updateOrder'])->name('books.update-order');

    // =============================================
    // VIDEO MANAGEMENT ROUTES
    // =============================================
    Route::get('videos', [VideoController::class, 'index'])->name('videos.index');
    Route::get('videos/create', [VideoController::class, 'create'])->name('videos.create');
    Route::post('videos', [VideoController::class, 'store'])->name('videos.store');
    Route::get('videos/{id}/edit', [VideoController::class, 'edit'])->name('videos.edit');
    Route::put('videos/{id}', [VideoController::class, 'update'])->name('videos.update');
    Route::delete('videos/{id}', [VideoController::class, 'destroy'])->name('videos.destroy');
    // =============================================
    // LIVE BROADCAST MANAGEMENT ROUTES
    // =============================================
    Route::resource('live-broadcasts', LiveBroadcastController::class);
    Route::post('live-broadcasts/toggle-status/{id}', [LiveBroadcastController::class, 'toggleStatus'])->name('live-broadcasts.toggle-status');

    // =============================================
    // LEADERSHIP MANAGEMENT ROUTES
    // =============================================
    Route::get('leaders/hierarchy', [LeaderController::class, 'hierarchy'])->name('leaders.hierarchy');
    Route::post('leaders/hierarchy/update', [LeaderController::class, 'updateHierarchy'])->name('leaders.hierarchy.update');
    Route::resource('leaders', LeaderController::class);
    Route::post('leaders/{id}/toggle-status', [LeaderController::class, 'toggleStatus'])->name('leaders.toggle-status');
    Route::post('leaders/{id}/update-order', [LeaderController::class, 'updateOrder'])->name('leaders.update-order');

    // =============================================
    // BRANCHES MANAGEMENT ROUTES
    // =============================================
    Route::resource('branches', BranchController::class);
    Route::post('branches/{id}/toggle-status', [BranchController::class, 'toggleStatus'])->name('branches.toggle-status');

});
