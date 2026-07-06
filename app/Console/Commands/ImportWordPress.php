<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ImportWordPress extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'import:wordpress 
                            {url=https://hezbuttawheed.org : The WordPress website URL}
                            {--limit= : Limit the number of posts to import}
                            {--force : Force overwrite existing posts}
                            {--fresh : Clear database and start a fresh import}
                            {--page= : Explicitly set the page number to start importing from}';

    /**
     * The console command description.
     */
    protected $description = 'Production-grade importer: Migrates posts, categories, tags, featured images, and downloads in-content images to make posts self-contained.';

    // Keep track of downloaded tags to avoid redundant API calls
    private $tagCache = [];

    /**
     * Execute the console command.
     */
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

        // Determine the page to start from
        if ($pageOption !== null) {
            $page = (int) $pageOption;
        } elseif ($existingPostsCount > 0 && !$fresh) {
            // Automatically resume from the last page (minus 1 to catch any half-completed pages)
            $page = max(1, (int) floor($existingPostsCount / $perPage));
            $this->info("Found {$existingPostsCount} existing posts. Automatically resuming from page {$page}...");
        }

        // Clean up old uploads and truncate tables ONLY if starting a fresh import (page 1 and fresh flag / no existing posts)
        if ($page === 1 && ($fresh || $existingPostsCount === 0)) {
            $this->warn("Cleaning up old blog uploads...");
            \Illuminate\Support\Facades\File::cleanDirectory(public_path('uploads/blog'));
            \Illuminate\Support\Facades\File::ensureDirectoryExists(public_path('uploads/blog/content'));
            $this->info("✓ Old uploads cleared.");

            $this->warn("Clearing existing blog posts and categories database tables...");
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
            Blog::truncate();
            BlogCategory::truncate();
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            $this->info("✓ Database tables cleared.");
        } else {
            $this->info("Resuming import from page {$page}. Old uploads and database tables will NOT be cleared.");
        }

        // 1. Resolve author
        $authorId = User::first()?->id ?? 1;

        // 2. Fetch & Sync Categories
        $this->info("\nSyncing Categories...");
        $categoriesResponse = Http::get("{$apiUrl}/categories", ['per_page' => 100]);
        if ($categoriesResponse->failed()) {
            $this->error("Failed to connect to WordPress API categories endpoint.");
            return 1;
        }

        $wpCategories = $categoriesResponse->json();
        $categoryMapping = []; // wp_category_id => local_category_id

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

        // 3. Import Posts
        $this->info("\nStarting Posts Import...");
        $totalPostsImported = 0;

        while (true) {
            // Check limit constraint
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
            
            // Console Progress Bar for this batch
            $bar = $this->output->createProgressBar(count($wpPosts));
            $bar->start();

            foreach ($wpPosts as $wpPost) {
                $slug = $wpPost['slug'];

                // Check duplicate unless force is active
                if (!$force && Blog::where('slug', $slug)->exists()) {
                    $bar->advance();
                    continue;
                }

                // A. Map Category
                $localCategoryId = null;
                if (!empty($wpPost['categories'])) {
                    $wpCatId = $wpPost['categories'][0];
                    $localCategoryId = $categoryMapping[$wpCatId] ?? null;
                }

                // B. Resolve Tags
                $tags = $this->resolveTags($wpPost, $apiUrl);

                // C. Handle Featured Image
                $featuredImagePath = null;
                if (isset($wpPost['_embedded']['wp:featuredmedia'][0]['source_url'])) {
                    $featuredImageUrl = $wpPost['_embedded']['wp:featuredmedia'][0]['source_url'];
                    $featuredImagePath = $this->downloadAndSaveImage($featuredImageUrl, 'featured');
                }

                // D. Extract and Download Content Images (Inline Images)
                $sanitizedContent = $this->processAndDownloadContentImages($wpPost['content']['rendered'], $siteUrl);

                // E. Save Post
                Blog::updateOrCreate(
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
                        'updated_at' => Carbon::parse($wpPost['modified']),
                        'views' => rand(150, 1200)
                    ]
                );

                $totalPostsImported++;
                $bar->advance();
            }

            $bar->finish();
            $this->line(""); // Carriage return

            $page++;
        }

        $this->info("\n=================================================================");
        $this->info("   ✓ Migration completed successfully!");
        $this->info("   Total Posts Synced: {$totalPostsImported}");
        $this->info("=================================================================");

        return 0;
    }

    /**
     * Parse and retrieve post tags
     */
    private function resolveTags($wpPost, $apiUrl)
    {
        $tagsList = [];

        // Check if tags are embedded
        if (isset($wpPost['_embedded']['wp:term'])) {
            foreach ($wpPost['_embedded']['wp:term'] as $terms) {
                foreach ($terms as $term) {
                    if (isset($term['taxonomy']) && $term['taxonomy'] === 'post_tag') {
                        $tagsList[] = html_entity_decode($term['name']);
                    }
                }
            }
        }

        // Fallback: Query tags endpoint if not embedded
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

    /**
     * Scan rich text HTML content for images, download them, and replace remote links with local links
     */
    private function processAndDownloadContentImages($htmlContent, $siteUrl)
    {
        if (empty($htmlContent)) {
            return $htmlContent;
        }

        // Find all img tags src attributes
        preg_match_all('/<img[^>]+src="([^">]+)"/i', $htmlContent, $matches);

        if (empty($matches[1])) {
            return $htmlContent;
        }

        $uniqueImages = array_unique($matches[1]);

        foreach ($uniqueImages as $imageUrl) {
            // Download only remote images
            if (Str::startsWith($imageUrl, 'http') || Str::startsWith($imageUrl, '//')) {
                // Ignore placeholder or external domain images if they are not from WP uploads
                if (!Str::contains($imageUrl, $siteUrl) && !Str::contains($imageUrl, 'wp-content')) {
                    continue;
                }

                // Download the content image
                $localPath = $this->downloadAndSaveImage($imageUrl, 'content');

                if ($localPath) {
                    // Convert relative storage url to asset url format
                    $localAssetUrl = asset($localPath);
                    $htmlContent = str_replace($imageUrl, $localAssetUrl, $htmlContent);
                }
            }
        }

        return $htmlContent;
    }

    /**
     * Download helper that downloads and stores images locally
     */
    private function downloadAndSaveImage($url, $type = 'featured')
    {
        // Remove query parameters from image URLs
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

            // Save binary to public directory directly
            $absolutePath = public_path($relativePath);
            \Illuminate\Support\Facades\File::put($absolutePath, $response->body());

            // Return path reference matching what's stored in db (uploads/blog/...)
            return $relativePath;
        } catch (\Exception $e) {
            // Keep going, do not break main thread on media failure
            return null;
        }
    }
}
