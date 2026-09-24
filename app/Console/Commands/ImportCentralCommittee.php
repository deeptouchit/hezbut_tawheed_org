<?php

namespace App\Console\Commands;

use App\Models\Leader;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImportCentralCommittee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:central-committee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the central committee list from hezbuttawheed.org';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'https://hezbuttawheed.org/central-committee/';
        $this->info("Fetching central committee list from {$url}...");

        try {
            $response = Http::get($url);
            if ($response->failed()) {
                $this->error("Failed to fetch the central committee page.");
                return 1;
            }

            $html = $response->body();
            
            // Ensure uploads directory exists
            $uploadDir = public_path('uploads/leaders');
            File::ensureDirectoryExists($uploadDir);

            // Parse HTML
            $dom = new \DOMDocument();
            @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
            $xpath = new \DOMXPath($dom);

            $memberCards = $xpath->query("//div[contains(@class, 'member-card')]");
            $this->info("Found " . $memberCards->length . " member cards on the page.");

            $importedCount = 0;

            foreach ($memberCards as $card) {
                // 1. Parse Serial Number
                $serialNode = $xpath->query(".//div[contains(@class, 'serial-badge')]", $card)->item(0);
                $serialText = $serialNode ? trim($serialNode->nodeValue) : '';
                $serial = (int) $this->convertBanglaToEnglishNumber($serialText);

                if (!$serial) {
                    continue;
                }

                // 2. Parse Image URL
                $imgNode = $xpath->query(".//img[contains(@class, 'card-img')]", $card)->item(0);
                $imgSrc = $imgNode ? $imgNode->getAttribute('src') : '';

                // 3. Parse Name
                $nameNode = $xpath->query(".//*[contains(@class, 'card-name')]", $card)->item(0);
                $nameText = $nameNode ? trim($nameNode->nodeValue) : '';

                // 4. Parse Designation
                $descNode = $xpath->query(".//*[contains(@class, 'card-designation')]", $card)->item(0);
                $descText = $descNode ? trim($descNode->nodeValue) : '';

                if (empty($nameText)) {
                    continue;
                }

                // Download image if present
                $imagePath = null;
                $englishName = '';
                if ($imgSrc) {
                    $filename = basename(parse_url($imgSrc, PHP_URL_PATH));
                    if ($filename) {
                        // Extract clean english name from filename
                        $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
                        $englishName = preg_replace('/[-_]/', ' ', $nameWithoutExt);
                        $englishName = preg_replace('/\s+\d+$/', '', $englishName); // Remove trailing numbers
                        $englishName = ucwords(strtolower(trim($englishName)));

                        // Download the file
                        $imgResponse = Http::get($imgSrc);
                        if ($imgResponse->successful()) {
                            File::put($uploadDir . '/' . $filename, $imgResponse->body());
                            $imagePath = 'uploads/leaders/' . $filename;
                        }
                    }
                }

                if (empty($englishName)) {
                    $englishName = $nameText; // Fallback
                }

                // Set leader properties
                $isFounder = ($serial === 1);
                $category = 'central';
                
                $quote = null;
                $bio = null;
                if ($isFounder) {
                    $quote = "আমাদের লড়াই কোনো ব্যক্তি, গোষ্ঠী, ধর্ম বা দলের বিরুদ্ধে নয়, আমাদের লড়াই মিথ্যা, অন্যায়, অধর্ম, সন্ত্রাস ও উগ্রবাদের বিরুদ্ধে।";
                    $bio = "হেযবুত তওহীদের মাননীয় এমাম হোসাইন মোহাম্মদ সেলিম দেশের অন্যতম অসাম্প্রদায়িক ও প্রগতিশীল চিন্তাবিদ। তিনি উগ্রবাদ ও সন্ত্রাসবাদের বিরুদ্ধে গণসচেতনতা সৃষ্টিতে এক অনন্য নেতৃত্ব দান করছেন।";
                }

                // Create or update leader profile
                Leader::updateOrCreate(
                    ['english_name' => $englishName],
                    [
                        'name' => $nameText,
                        'designation' => $descText,
                        'category' => $category,
                        'image' => $imagePath ?? '',
                        'sort_order' => $serial,
                        'is_active' => true,
                        'is_founder' => $isFounder,
                        'quote' => $quote,
                        'bio' => $bio,
                    ]
                );

                $this->info("✓ Imported: {$nameText} ({$descText}) - Serial: {$serial}");
                $importedCount++;
            }

            $this->info("Import completed successfully. Imported {$importedCount} leaders.");
            return 0;

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Helper to convert Bangla numbers to English digits.
     */
    private function convertBanglaToEnglishNumber($str)
    {
        $bangla = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
        $english = ['0','1','2','3','4','5','6','7','8','9'];
        return str_replace($bangla, $english, $str);
    }
}
