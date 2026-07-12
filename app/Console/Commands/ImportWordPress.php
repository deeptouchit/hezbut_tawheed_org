<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ImportWordPress extends Command
{
    protected $signature = 'import:wordpress 
                            {url=https://hezbuttawheed.org : The WordPress website URL}
                            {--limit= : Limit the number of posts to import}
                            {--force : Force overwrite existing posts}
                            {--fresh : Clear database and start a fresh import}
                            {--page= : Explicitly set the page number to start importing from}';

    protected $description = 'Production-grade importer: Migrates posts, categories, tags, comments, featured images, and downloads in-content images to make posts self-contained.';

    private $tagCache = [];

    public function handle()
    {
        $siteUrl = rtrim($this->argument('url'), '/');
        $apiUrl = $siteUrl . '/wp-json/wp/v2';
        $limit = $this->option('limit');
        $force = $this->option('force');
        $fresh = $this->option('fresh');
        $pageOption = $this->option('page');
        $perPage = 20;

        $this->info("=================================================================");
        $this->info("   WordPress Production-Grade Migration Tool");
        $this->info("   Target Site: {$siteUrl}");
        $this->info("=================================================================");

        $existingPostsCount = Blog::count();
        $page = 1;

        if ($pageOption !== null) {
            $page = (int) $pageOption;
        } elseif ($existingPostsCount > 0 && !$fresh) {
            $page = max(1, (int) floor($existingPostsCount / $perPage));
            $this->info("Found {$existingPostsCount} existing posts. Automatically resuming from page {$page}...");
        }

        if ($page === 1 && ($fresh || $existingPostsCount === 0)) {
            $this->warn("Cleaning up old blog uploads...");
            \Illuminate\Support\Facades\File::cleanDirectory(public_path('uploads/blog'));
            \Illuminate\Support\Facades\File::ensureDirectoryExists(public_path('uploads/blog/content'));
            $this->info("✓ Old uploads cleared.");

            $this->warn("Clearing existing blog posts, categories, comments, and tags database tables...");
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
            Blog::truncate();
            BlogCategory::truncate();
            BlogComment::truncate();
            Tag::truncate();
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            $this->info("✓ Database tables cleared.");
        } else {
            $this->info("Resuming import from page {$page}. Old uploads and database tables will NOT be cleared.");
        }

        $authorId = User::first()?->id ?? 1;

        // 1. Fetch & Sync Categories
        $this->info("\nSyncing Categories...");
        $categoriesResponse = Http::get("{$apiUrl}/categories", ['per_page' => 100]);
        if ($categoriesResponse->failed()) {
            $this->error("Failed to connect to WordPress API categories endpoint.");
            return 1;
        }

        $wpCategories = $categoriesResponse->json();
        $categoryMapping = [];

        foreach ($wpCategories as $wpCat) {
            $category = BlogCategory::updateOrCreate(
                ['slug' => $wpCat['slug']],
                [
                    'name' => html_entity_decode($wpCat['name']),
                    'description' => strip_tags(html_entity_decode($wpCat['description'])),
                    'status' => true,
                ]
            );
            $categoryMapping[$wpCat['id']] = $category->id;
        }
        $this->info("✓ " . count($wpCategories) . " categories synced.");

        // 2. Import Posts
        $this->info("\nStarting Posts Import...");
        $totalPostsImported = 0;

        while (true) {
            if ($limit && $totalPostsImported >= $limit) {
                break;
            }

            $fetchLimit = $limit ? min($perPage, $limit - $totalPostsImported) : $perPage;

            $this->info("\nFetching page {$page} (20 posts per page)...");
            $postsResponse = Http::get("{$apiUrl}/posts", [
                'per_page' => $fetchLimit,
                'page' => $page,
                '_embed' => 'wp:featuredmedia,wp:term'
            ]);

            if ($postsResponse->failed() || empty($postsResponse->json())) {
                $this->info("Reached the end of the posts or API request failed at page {$page}.");
                break;
            }

            $wpPosts = $postsResponse->json();
            $bar = $this->output->createProgressBar(count($wpPosts));
            $bar->start();

            foreach ($wpPosts as $wpPost) {
                $slug = $wpPost['slug'];

                if (!$force && Blog::where('slug', $slug)->exists()) {
                    $existingBlog = Blog::where('slug', $slug)->first();
                    if ($existingBlog) {
                        Cache::forever("wp_post_blog_id_{$wpPost['id']}", $existingBlog->id);
                    }
                    $bar->advance();
                    continue;
                }

                $localCategoryId = null;
                if (!empty($wpPost['categories'])) {
                    $wpCatId = $wpPost['categories'][0];
                    $localCategoryId = $categoryMapping[$wpCatId] ?? null;
                }

                $tags = $this->resolveTags($wpPost, $apiUrl);

                foreach ($tags as $tagName) {
                    Tag::firstOrCreate(
                        ['slug' => Str::slug($tagName)],
                        [
                            'name' => $tagName,
                            'status' => true,
                            'color' => '#6c757d',
                        ]
                    );
                }

                $featuredImagePath = null;
                if (isset($wpPost['_embedded']['wp:featuredmedia'][0]['source_url'])) {
                    $featuredImageUrl = $wpPost['_embedded']['wp:featuredmedia'][0]['source_url'];
                    $featuredImagePath = $this->downloadAndSaveImage($featuredImageUrl, 'featured');
                }

                $sanitizedContent = $this->processAndDownloadContentImages($wpPost['content']['rendered'], $siteUrl);

                $blog = Blog::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'title' => html_entity_decode($wpPost['title']['rendered']),
                        'short_description' => Str::limit(strip_tags(html_entity_decode($wpPost['excerpt']['rendered'])), 250),
                        'content' => $sanitizedContent,
                        'category_id' => $localCategoryId,
                        'author_id' => $authorId,
                        'featured_image' => $featuredImagePath,
                        'tags' => $tags,
                        'status' => $wpPost['status'] === 'publish',
                        'published_at' => Carbon::parse($wpPost['date']),
                        'created_at' => Carbon::parse($wpPost['date']),
                        'updated_at' => Carbon::parse($wpPost['date']),
                        'views' => rand(150, 1200)
                    ]
                );

                Cache::forever("wp_post_blog_id_{$wpPost['id']}", $blog->id);

                $totalPostsImported++;
                $bar->advance();
            }

            $bar->finish();
            $this->line("");
            $page++;
        }
        $this->info("✓ " . $totalPostsImported . " posts synced.");

        // 3. Sync Comments
        $this->info("\nStarting Comments Import...");
        $commentPage = 1;
        $totalCommentsImported = 0;

        while (true) {
            $this->info("Fetching comments page {$commentPage}...");
            $commentsResponse = Http::get("{$apiUrl}/comments", [
                'per_page' => 100,
                'page' => $commentPage,
            ]);

            if ($commentsResponse->failed() || empty($commentsResponse->json())) {
                $this->info("Reached the end of comments or API request failed at page {$commentPage}.");
                break;
            }

            $wpComments = $commentsResponse->json();
            $bar = $this->output->createProgressBar(count($wpComments));
            $bar->start();

            foreach ($wpComments as $wpComment) {
                $localBlogId = Cache::get("wp_post_blog_id_{$wpComment['post']}");
                if (!$localBlogId) {
                    $bar->advance();
                    continue;
                }

                $localParentId = null;
                if ($wpComment['parent'] > 0) {
                    $localParentId = Cache::get("wp_comment_id_{$wpComment['parent']}");
                }

                $commentText = strip_tags(html_entity_decode($wpComment['content']['rendered']));
                $commentDate = Carbon::parse($wpComment['date']);

                $localComment = BlogComment::updateOrCreate(
                    [
                        'blog_id' => $localBlogId,
                        'name' => html_entity_decode($wpComment['author_name']),
                        'email' => $wpComment['author_email'],
                        'created_at' => $commentDate,
                    ],
                    [
                        'comment' => $commentText,
                        'parent_id' => $localParentId,
                        'is_approved' => $wpComment['status'] === 'approved',
                        'ip_address' => $wpComment['author_ip'] ?? null,
                        'user_agent' => $wpComment['author_user_agent'] ?? null,
                        'updated_at' => $commentDate,
                    ]
                );

                Cache::forever("wp_comment_id_{$wpComment['id']}", $localComment->id);
                $totalCommentsImported++;
                $bar->advance();
            }

            $bar->finish();
            $this->line("");
            $commentPage++;
        }
        $this->info("✓ " . $totalCommentsImported . " comments synced.");

        $this->info("\n=================================================================");
        $this->info("   ✓ Migration completed successfully!");
        $this->info("=================================================================");

        return 0;
    }

    private function resolveTags($wpPost, $apiUrl)
    {
        $tagsList = [];

        if (isset($wpPost['_embedded']['wp:term'])) {
            foreach ($wpPost['_embedded']['wp:term'] as $terms) {
                foreach ($terms as $term) {
                    if (isset($term['taxonomy']) && $term['taxonomy'] === 'post_tag') {
                        $tagsList[] = html_entity_decode($term['name']);
                    }
                }
            }
        }

        if (empty($tagsList) && !empty($wpPost['tags'])) {
            foreach ($wpPost['tags'] as $tagId) {
                if (isset($this->tagCache[$tagId])) {
                    $tagsList[] = $this->tagCache[$tagId];
                    continue;
                }

                $tagResponse = Http::get("{$apiUrl}/tags/{$tagId}");
                if ($tagResponse->successful()) {
                    $tagName = html_entity_decode($tagResponse->json()['name']);
                    $this->tagCache[$tagId] = $tagName;
                    $tagsList[] = $tagName;
                }
            }
        }

        return $tagsList;
    }

    private function processAndDownloadContentImages($htmlContent, $siteUrl)
    {
        if (empty($htmlContent)) {
            return $htmlContent;
        }

        // 1. Strip all <noscript> tags to prevent duplicate fallback images
        $htmlContent = preg_replace('/<noscript>.*?<\/noscript>/is', '', $htmlContent);

        // 2. Find all <img> tags
        preg_match_all('/<img[^>]+>/i', $htmlContent, $matches);

        if (empty($matches[0])) {
            return $htmlContent;
        }

        foreach ($matches[0] as $imgTag) {
            // Extract the actual image URL, prioritizing lazy loading attributes
            $imageUrl = null;
            if (preg_match('/data-src="([^">]+)"/i', $imgTag, $m)) {
                $imageUrl = $m[1];
            } elseif (preg_match('/data-lazy-src="([^">]+)"/i', $imgTag, $m)) {
                $imageUrl = $m[1];
            } elseif (preg_match('/src="([^">]+)"/i', $imgTag, $m)) {
                $imageUrl = $m[1];
            }

            if (empty($imageUrl) || Str::startsWith($imageUrl, 'data:')) {
                continue;
            }

            // Resolve host
            $urlHost = parse_url($imageUrl, PHP_URL_HOST);
            $isLocalDomain = empty($urlHost) || Str::endsWith($urlHost, 'hezbuttawheed.org');

            if ($isLocalDomain || Str::contains($imageUrl, 'wp-content')) {
                // Determine absolute URL for download
                $downloadUrl = $imageUrl;
                if (Str::startsWith($imageUrl, '//')) {
                    $downloadUrl = 'https:' . $imageUrl;
                } elseif (!Str::startsWith($imageUrl, 'http')) {
                    $downloadUrl = $siteUrl . '/' . ltrim($imageUrl, '/');
                }

                $localPath = $this->downloadAndSaveImage($downloadUrl, 'content');

                if ($localPath) {
                    $localAssetUrl = asset($localPath);
                    // Replace the entire original <img> tag with a clean, responsive local <img> tag
                    $cleanImgTag = '<img src="' . $localAssetUrl . '" class="img-fluid my-3 rounded shadow-sm d-block mx-auto" alt="Post Image" style="max-height: 500px; object-fit: contain;">';
                    $htmlContent = str_replace($imgTag, $cleanImgTag, $htmlContent);
                }
            }
        }

        return $htmlContent;
    }

    private function downloadAndSaveImage($url, $type = 'featured')
    {
        $cleanUrl = strtok($url, '?');

        try {
            $response = Http::timeout(45)->get($cleanUrl);
            if ($response->failed()) {
                return null;
            }

            $extension = pathinfo(parse_url($cleanUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
            if (empty($extension)) {
                $extension = 'jpg';
            }

            $fileName = 'wp_' . Str::random(16) . '.' . $extension;
            
            if ($type === 'content') {
                $relativePath = 'uploads/blog/content/' . $fileName;
            } else {
                $relativePath = 'uploads/blog/' . $fileName;
            }

            $absolutePath = public_path($relativePath);
            \Illuminate\Support\Facades\File::put($absolutePath, $response->body());

            return $relativePath;
        } catch (\Exception $e) {
            return null;
        }
    }
}
