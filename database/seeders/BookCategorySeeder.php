<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookCategory;
use App\Models\Book;

class BookCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create categories
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

        echo "Created book categories successfully!\n";

        // 2. Fetch all books from WordPress REST API to determine their categories
        echo "Fetching WP book categories mapping...\n";
        
        $page = 1;
        $wpBooksMapping = [];
        $hasMore = true;

        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36\r\n"
            ]
        ];
        $context = stream_context_create($opts);

        while ($hasMore) {
            $apiUrl = "https://hezbuttawheed.org/wp-json/wp/v2/book?per_page=100&page={$page}";
            $responseJson = @file_get_contents($apiUrl, false, $context);
            
            if ($responseJson) {
                $booksData = json_decode($responseJson, true);
                if (is_array($booksData) && count($booksData) > 0) {
                    foreach ($booksData as $bookData) {
                        $slug = urldecode($bookData['slug']);
                        $cats = $bookData['categories'] ?? [];
                        $wpBooksMapping[$slug] = $cats;
                    }
                    $page++;
                } else {
                    $hasMore = false;
                }
            } else {
                $hasMore = false;
            }
        }

        // 3. Define specific slugs that should be categorized as "ছোট পুস্তিকা"
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
            ' central-conference-memoir', // or any other matching
        ];

        // 4. Update existing books in the database
        $books = Book::all();
        $updatedCount = 0;

        foreach ($books as $book) {
            $slug = $book->slug;
            $categoryId = $catBooks->id; // default to "বই"

            // Check if it is a booklet first
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
                // Otherwise check WordPress category mapping
                $wpCats = $wpBooksMapping[$slug] ?? [];
                if (in_array(36, $wpCats)) {
                    $categoryId = $catCompilations->id;
                } elseif (in_array(35, $wpCats)) {
                    $categoryId = $catBooks->id;
                }
            }

            $book->category_id = $categoryId;
            $book->save();
            $updatedCount++;
        }

        echo "Successfully categorized {$updatedCount} books in the database!\n";
    }
}
