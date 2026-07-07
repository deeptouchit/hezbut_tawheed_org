<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LiveController;
use App\Http\Controllers\LeadershipController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PrivacyController;
use App\Http\Controllers\ReturnsController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\SuggestionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Web Routes - Bogura Bazar E-commerce
|--------------------------------------------------------------------------
*/

// ============================================
// HOME & STATIC PAGES
// ============================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');

// ACTIVITIES ROUTES
Route::get('/activities', [HomeController::class, 'activities'])->name('activities.index');
Route::get('/activities/{slug}', [HomeController::class, 'activityShow'])->name('activities.show');

// BOOKS ROUTES
Route::get('/books', [HomeController::class, 'books'])->name('books.index');
Route::get('/books/load-more', [HomeController::class, 'loadMoreBooks'])->name('books.load-more');
Route::get('/books/{slug}', [HomeController::class, 'bookShow'])->name('books.show');

// DIGITAL LIBRARY ROUTES
Route::get('/library', [HomeController::class, 'library'])->name('library.index');
Route::get('/library/read/{slug}', [HomeController::class, 'libraryRead'])->name('library.read');

// VIDEOS ROUTES
Route::get('/videos', [HomeController::class, 'videos'])->name('videos.index');

// PHOTO GALLERY ROUTE
Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery.index');

// LIVE BROADCAST ROUTES
Route::get('/live', [LiveController::class, 'index'])->name('live.index');
Route::get('/live/archive/{id}', [LiveController::class, 'show'])->name('live.show');
Route::post('/live/increment-view/{id}', [LiveController::class, 'incrementView'])->name('live.increment-view');
Route::get('/live/get-view-count/{id}', [LiveController::class, 'getViewCount'])->name('live.get-view-count');

// LEADERSHIP ROUTES
Route::get('/leadership', [LeadershipController::class, 'index'])->name('leadership.index');
Route::get('/leadership/ajax/{id}', [LeadershipController::class, 'getDetailsJson'])->name('leadership.ajax');
Route::get('/leadership/{slug}', [LeadershipController::class, 'show'])->name('leadership.show');
Route::get('/structure', [LeadershipController::class, 'structure'])->name('leadership.structure');

// BRANCHES ROUTES
Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');

Route::get('/articles', [BlogController::class, 'index'])->name('blog');
Route::get('/articles/archive', [BlogController::class, 'archive'])->name('blog.archive');
Route::get('/articles/search', [BlogController::class, 'search'])->name('blog.search');
Route::get('/articles/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/articles/tag/{tag}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/articles/{slug}', [BlogController::class, 'show'])->name('blog.detail');
Route::post('/articles/{blogId}/comment', [BlogController::class, 'comment'])->name('blog.comment');
Route::delete('/articles/comment/{id}', [BlogController::class, 'deleteComment'])->name('blog.comment.delete');
Route::post('/articles/refresh-cache', [BlogController::class, 'refreshCache'])->name('blog.refresh.cache');
Route::get('/feed', [BlogController::class, 'feed'])->name('blog.feed');

// 301 Redirects for legacy category slugs to preserve SEO link equity
Route::redirect('/articles/category/approval', '/articles/category/approval-and-legality', 301);
Route::redirect('/articles/category/mamla', '/articles/category/rebuttal-and-legal', 301);
Route::redirect('/articles/category/attack-on-us', '/articles/category/history-of-persecution', 301);

Route::get('/announcements', [BlogController::class, 'announcements'])->name('announcements.index');
Route::get('/events', [BlogController::class, 'events'])->name('events.index');


// E-commerce static routes (faq, help, shipping, returns) removed
Route::get('/privacy', [PrivacyController::class, 'index'])->name('privacy');
Route::get('/terms', [TermsController::class, 'index'])->name('terms');


// ============================================
// CONTACT ROUTES
// ============================================
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// ============================================
// FEEDBACK ROUTES
// ============================================
Route::get('/feedback', [HomeController::class, 'feedback'])->name('feedback.index');
Route::post('/feedback', [HomeController::class, 'submitFeedback'])->name('feedback.submit');
Route::post('/suggestions/store', [SuggestionController::class, 'store'])->name('suggestions.store');

// ============================================
// JOIN / MEMBERSHIP ROUTES
// ============================================
Route::get('/join', [HomeController::class, 'join'])->name('join.index');
Route::post('/join', [HomeController::class, 'submitJoin'])->name('join.submit');

// ============================================
// SITEMAP ROUTES
// ============================================
Route::get('/sitemap', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap.xml', [SitemapController::class, 'xml'])->name('sitemap.xml');
Route::get('/sitemap/ping-google', [SitemapController::class, 'pingGoogle'])->name('sitemap.ping');



// E-commerce routes (Category, product search, product reviews) removed

// ============================================
// NEWSLETTER ROUTES
// ============================================
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// ============================================
// DYNAMIC CMS PAGE ROUTE
// ============================================
Route::get('/{slug}', [HomeController::class, 'page'])->name('page.show');

// ============================================
// FALLBACK ROUTE (404 Page)
// ============================================
Route::fallback(function () {
    return view('errors.404');
});



