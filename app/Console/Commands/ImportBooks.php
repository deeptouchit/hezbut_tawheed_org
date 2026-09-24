<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImportBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:books';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'One-time migration: Import books metadata from WordPress API and associate with local cover images and PDF files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=================================================================");
        $this->info("   WordPress Books Migration Tool");
        $this->info("=================================================================");

        // 1. Ensure book categories exist
        $catBooks = BookCategory::updateOrCreate(
            ['slug' => 'books'],
            ['name' => 'বই', 'is_active' => true]
        );

        $catCompilations = BookCategory::updateOrCreate(
            ['slug' => 'compilations'],
            ['name' => 'সংকলন', 'is_active' => true]
        );

        $catBooklets = BookCategory::updateOrCreate(
            ['slug' => 'booklets'],
            ['name' => 'ছোট পুস্তিকা', 'is_active' => true]
        );

        $this->info("✓ Book categories verified.");

        // Define specific slugs that should be categorized as "ছোট পুস্তিকা" (Booklets)
        $bookletSlugs = [
            'সওমের-উদ্দেশ্য',
            'overview-of-hezbut-tawheed',
            'hezbut-tawheed-in-brief',
            'ধর্মব্যবসায়ীদের-ভয়াবহ-জা',
            'জেহাদ-কেতাল-ও-সন্ত্রাস',
            'পর্দাপ্রথার-গোড়ার-কথা',
            'তাকওয়া-ও-হেদায়াহ',
            'সম্মানিত-আলেমদের-প্রতি',
            'আসুন-সিস্টেমটাকেই-পাল্টাই',
            'জোরপূর্বক-শ্রমব্যবস্থাই',
            'চলমান-সংকট-নিরসনে-আদর্শি',
            'central-conference-memoir',
        ];

        $page = 1;
        $importedCount = 0;
        $updatedCount = 0;

        $coversPath = public_path('uploads/books/covers');
        $pdfsPath = public_path('uploads/books/pdfs');

        while (true) {
            $this->info("\nFetching books page {$page}...");
            $response = Http::get("https://hezbuttawheed.org/wp-json/wp/v2/book", [
                'per_page' => 100,
                'page' => $page
            ]);

            if ($response->failed() || empty($response->json())) {
                $this->info("Reached the end of books or API request failed at page {$page}.");
                break;
            }

            $wpBooks = $response->json();
            $bar = $this->output->createProgressBar(count($wpBooks));
            $bar->start();

            foreach ($wpBooks as $wpBook) {
                $slug = urldecode($wpBook['slug']);
                
                // Determine Category
                $categoryId = $catBooks->id; // default
                $isBooklet = false;
                foreach ($bookletSlugs as $bSlug) {
                    if (strpos($slug, $bSlug) !== false) {
                        $isBooklet = true;
                        break;
                    }
                }

                if ($isBooklet) {
                    $categoryId = $catBooklets->id;
                } else {
                    $wpCats = $wpBook['categories'] ?? [];
                    if (in_array(36, $wpCats)) {
                        $categoryId = $catCompilations->id;
                    } elseif (in_array(35, $wpCats)) {
                        $categoryId = $catBooks->id;
                    }
                }

                // Match Cover Image locally
                $localImage = null;
                if (File::exists($coversPath)) {
                    $matchingImages = File::glob($coversPath . '/' . $slug . '.*');
                    if (!empty($matchingImages)) {
                        $localImage = 'uploads/books/covers/' . basename($matchingImages[0]);
                    }
                }

                // Match PDF file locally
                $localPdf = null;
                if (File::exists($pdfsPath)) {
                    $matchingPdfs = File::glob($pdfsPath . '/' . $slug . '.pdf');
                    if (!empty($matchingPdfs)) {
                        $localPdf = 'uploads/books/pdfs/' . basename($matchingPdfs[0]);
                    }
                }

                // Fetch permalink page to parse author, price, and old price
                $writer = null;
                $price = null;
                $oldPrice = null;
                try {
                    $permalink = $wpBook['link'] ?? '';
                    if ($permalink) {
                        $htmlResponse = Http::timeout(10)->get($permalink);
                        if ($htmlResponse->successful()) {
                            $htmlBody = $htmlResponse->body();
                            
                            // Parse writer
                            if (preg_match('/লেখক\s*:\s*([^<\t\r\n]+)/u', $htmlBody, $m)) {
                                $writer = trim(strip_tags(html_entity_decode($m[1])));
                            }
                            
                            // Parse price
                            if (preg_match('/মূল্যঃ\s*৳\s*([০-৯\d]+)/u', $htmlBody, $m)) {
                                $price = trim($m[1]);
                            } elseif (preg_match('/মূল্য\s*:\s*৳\s*([০-৯\d]+)/u', $htmlBody, $m)) {
                                $price = trim($m[1]);
                            } elseif (preg_match('/মূল্যঃ\s*([০-৯\d]+)/u', $htmlBody, $m)) {
                                $price = trim($m[1]);
                            }
                            
                            // Parse old price
                            if (preg_match('/<del[^>]*>.*?৳\s*([০-৯\d]+).*?<\/del>/u', $htmlBody, $m)) {
                                $oldPrice = trim($m[1]);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    $this->warn(" Failed to fetch metadata for slug {$slug}: " . $e->getMessage());
                }

                $bookData = [
                    'title' => html_entity_decode($wpBook['title']['rendered']),
                    'slug' => $slug,
                    'image' => $localImage,
                    'pdf_url' => $localPdf,
                    'writer' => $writer,
                    'price' => $price,
                    'old_price' => $oldPrice,
                    'description' => isset($wpBook['excerpt']['rendered']) 
                        ? Str::limit(strip_tags(html_entity_decode($wpBook['excerpt']['rendered'])), 300) 
                        : Str::limit(strip_tags(html_entity_decode($wpBook['content']['rendered'] ?? '')), 300),
                    'content' => $wpBook['content']['rendered'] ?? '',
                    'category_id' => $categoryId,
                    'is_active' => true,
                    'is_popular' => false,
                ];

                $exists = Book::where('slug', $slug)->exists();
                
                Book::updateOrCreate(
                    ['slug' => $slug],
                    $bookData
                );

                if ($exists) {
                    $updatedCount++;
                } else {
                    $importedCount++;
                }

                $bar->advance();
            }

            $bar->finish();
            $this->line("");
            $page++;
        }

        $this->info("\n=================================================================");
        $this->info("   Migration completed successfully!");
        $this->info("   Imported New: {$importedCount} books");
        $this->info("   Updated Existing: {$updatedCount} books");
        $this->info("=================================================================");

        return 0;
    }
}
