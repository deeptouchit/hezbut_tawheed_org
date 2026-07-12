<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JoinController;
use App\Http\Controllers\LeadershipController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\LiveController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PrivacyController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\SongController;
use Illuminate\Support\Facades\Route;

// Home & About
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', function() { return redirect()->to('/about-us'); })->name('about');

// Activities
Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/activities/{slug}', [ActivityController::class, 'show'])->name('activities.show');

// Books & Publications
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/load-more', [BookController::class, 'loadMore'])->name('books.load-more');
Route::get('/books/{slug}', [BookController::class, 'show'])->name('books.show');
Route::post('/books/{id}/review', [BookController::class, 'storeReview'])->name('books.review.store');

// Digital Library
Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
Route::get('/library/read/{slug}', [LibraryController::class, 'read'])->name('library.read');

// Videos
Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');

// Songs & Cultural Corner
Route::get('/songs', [SongController::class, 'index'])->name('songs.index');

// Photo Gallery
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

// Live Broadcast
Route::get('/live', [LiveController::class, 'index'])->name('live.index');
Route::get('/live/archive/{id}', [LiveController::class, 'show'])->name('live.show');
Route::post('/live/increment-view/{id}', [LiveController::class, 'incrementView'])->name('live.increment-view');
Route::get('/live/get-view-count/{id}', [LiveController::class, 'getViewCount'])->name('live.get-view-count');

// Leadership
Route::get('/leadership', [LeadershipController::class, 'index'])->name('leadership.index');
Route::get('/leadership/ajax/{id}', [LeadershipController::class, 'getDetailsJson'])->name('leadership.ajax');
Route::get('/leadership/{slug}', [LeadershipController::class, 'show'])->name('leadership.show');
Route::get('/structure', [LeadershipController::class, 'structure'])->name('leadership.structure');

// Branches
Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');

// Blogs & Articles
Route::get('/articles', [BlogController::class, 'index'])->name('blog');
Route::get('/articles/archive', [BlogController::class, 'archive'])->name('blog.archive');
Route::get('/articles/search', [BlogController::class, 'search'])->name('blog.search');
Route::get('/articles/category/{slug}', function ($slug) {
    $redirectMap = [
        'approval' => 'approval-and-legality',
        'mamla' => 'rebuttal-and-legal',
        'attack-on-us' => 'history-of-persecution',
        'পাবনা হত্যাকাণ্ড' => 'history-of-persecution',
        'english-blog' => 'english-articles',
        'english-vlog' => 'english-articles',
        'seminar' => 'events-and-programs',
        'public-seminar' => 'events-and-programs',
        'assembly' => 'events-and-programs',
        'recent-events' => 'events-and-programs',
        'recent-topics' => 'events-and-programs',
        'office-inauguration' => 'events-and-programs',
        'other-activities' => 'events-and-programs',
        'press-conference' => 'events-and-programs',
        'মানববন্ধন' => 'events-and-programs',
        'waz' => 'ideology-and-religion',
        'perspective' => 'ideology-and-religion',
        'compilation' => 'ideology-and-religion',
        'faq' => 'general-discussion',
        'blog' => 'general-discussion',
        'article' => 'general-discussion',
        'gallery' => 'general-discussion',
        'books' => 'general-discussion'
    ];

    $decodedSlug = urldecode($slug);

    if (array_key_exists($slug, $redirectMap)) {
        return redirect()->route('blog.category', $redirectMap[$slug], 301);
    }

    if (array_key_exists($decodedSlug, $redirectMap)) {
        return redirect()->route('blog.category', $redirectMap[$decodedSlug], 301);
    }

    return app(App\Http\Controllers\BlogController::class)->category($slug);
})->name('blog.category');

Route::get('/articles/tag/{tag}', [App\Http\Controllers\BlogController::class, 'tag'])->name('blog.tag');
Route::get('/articles/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.detail');
Route::post('/articles/{blogId}/comment', [App\Http\Controllers\BlogController::class, 'comment'])->name('blog.comment');
Route::delete('/articles/comment/{id}', [App\Http\Controllers\BlogController::class, 'deleteComment'])->name('blog.comment.delete');
Route::post('/articles/refresh-cache', [App\Http\Controllers\BlogController::class, 'refreshCache'])->name('blog.refresh.cache');
Route::get('/feed', [App\Http\Controllers\BlogController::class, 'feed'])->name('blog.feed');

Route::get('/press-releases', [BlogController::class, 'pressReleases'])->name('press-releases.index');
Route::redirect('/announcements', '/press-releases', 301);
Route::get('/events', [BlogController::class, 'events'])->name('events.index');

Route::get('/privacy', [PrivacyController::class, 'index'])->name('privacy');
Route::get('/terms', [TermsController::class, 'index'])->name('terms');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Feedback & Suggestions
Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.submit');
Route::post('/suggestions/store', [SuggestionController::class, 'store'])->name('suggestions.store');

// Join / Membership
Route::get('/join', [JoinController::class, 'index'])->name('join.index');
Route::post('/join', [JoinController::class, 'store'])->name('join.submit');

// Sitemap
Route::get('/sitemap', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap.xml', [SitemapController::class, 'xml'])->name('sitemap.xml');
Route::get('/sitemap/ping-google', [SitemapController::class, 'pingGoogle'])->name('sitemap.ping');

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Dynamic CMS Page
Route::get('/{slug}', [PageController::class, 'show'])->name('page.show');

// Fallback (404 Page)
Route::fallback(function () {
    return view('errors.404');
});
