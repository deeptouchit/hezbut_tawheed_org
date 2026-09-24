<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RefactorBlogCategoriesTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:refactor-categories-tags {--dry-run : Run in simulation mode without saving to database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Production-grade refactoring of 4,250+ blog categories, tags, image metadata, and slug redirects.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');

        $this->info($isDryRun ? '=== DRY RUN MODE ACTIVE (No database changes will be saved) ===' : '=== STARTING PRODUCTION BLOG REFACTORING ===');

        // Setup Log File
        $logFileName = 'blog_refactoring_' . date('Y-m-d_H-i-s') . ($isDryRun ? '_dryrun' : '') . '.log';
        $logPath = storage_path('logs/' . $logFileName);

        $log = function ($msg, $level = 'info') use ($logPath) {
            $formatted = '[' . date('Y-m-d H:i:s') . "] [{$level}] {$msg}\n";
            file_put_contents($logPath, $formatted, FILE_APPEND);
        };

        $log("Starting Refactoring process. Dry-run: " . ($isDryRun ? 'YES' : 'NO'));

        try {
            // 1. Build Slug to Category ID Lookup Map (Production Best Practice: No hardcoded IDs)
            $slugToIdMap = BlogCategory::pluck('id', 'slug')->toArray();
            if (empty($slugToIdMap)) {
                $this->error('❌ No categories found in blog_categories table. Please run php artisan db:seed --class=TopicCategorySeeder first.');
                return 1;
            }

            $this->info("Loaded " . count($slugToIdMap) . " categories from database.");

            // Clean & prepare tags table if not dry run
            if (!$isDryRun) {
                DB::statement('SET FOREIGN_KEY_CHECKS = 0');
                Tag::truncate();
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
                $this->info('Cleared existing tags table for clean re-population.');
            }

            // 2. Define Category Scoring Keyword Rules (Weighted Scoring System)
            $rules = [
                'cultural-policy' => ['সাংস্কৃতিক নীতিমালা', 'সাংস্কৃতিক নীতি', 'অপসংস্কৃতি', 'সাংস্কৃতিক আগ্রাসন', 'ইসলামের দৃষ্টিতে চলচ্চিত্র', 'শিল্প ও সংস্কৃতি', 'শিল্প-সংস্কৃতি', 'সাংস্কৃতিক দৃষ্টিভঙ্গি', 'ইসলামে কোনো শিল্পচর্চাই', 'হালাল ও হারামের সীমারেখা', 'ধর্মের হাতিয়ার', 'সুস্থ সংস্কৃতি', 'সংস্কৃতি চর্চা', 'পশ্চিমা সংস্কৃতি', 'আঞ্চলিক সংস্কৃতি', 'আরবীয় সংস্কৃতি', 'সংস্কৃতির মাধ্যম', 'সাহিত্যপ্রেমী', 'শিল্প ও ধর্ম', 'সাংস্কৃতিক জাগরণ', 'সাংস্কৃতিক বিকাশ', 'শিল্পচর্চা'],
                'cultural-activities' => ['সাংস্কৃতিক অনুষ্ঠান', 'সাংস্কৃতিক প্রতিযোগিতা', 'সাংস্কৃতিক পরিবেশনা', 'সাংস্কৃতিক সন্ধ্যা', 'সাংস্কৃতিক কর্নার', 'নাটক', 'সংগীত', 'গান', 'আবৃত্তি', 'কবিতা', 'কনসার্ট', 'প্রামাণ্যচিত্র', 'চলচ্চিত্র', 'নাট্যানুষ্ঠান', 'সাংস্কৃতিক পরিবেশন'],
                'core-message' => ['মূল বক্তব্য', 'ঘোষণাপত্র', 'এমামুয্যামান', 'মূল বার্তা', 'আদর্শিক বক্তব্য'],
                'development-vision' => ['উন্নয়ন ভাবনা', 'উন্নয়ন', 'অর্থনৈতিক স্বনির্ভরতা', 'জাতীয় উন্নয়ন'],
                'development-projects' => ['উন্নয়ন প্রকল্প', 'প্রকল্প', 'অবকাঠামো', 'উন্নয়নমূলক প্রকল্প'],
                'womens-policy' => ['নারী নীতি', 'নারী অধিকার', 'নারী স্বাধীনতা', 'নারী সম্মেলন', 'নারীদের'],
                'sports-and-athletics' => ['খেলাধুলা', 'ক্রীড়া', 'ফুটবল', 'ক্রিকেট', 'ব্যাডমিন্টন', 'মার্শাল আর্ট', 'টুর্নামেন্ট', 'শর্টপিচ'],
                'mosque-activities' => ['মসজিদ', 'মসজিদভিত্তিক', 'জুমার নামাজ', 'জুমার', 'জুমা', 'খুতবা', 'মিহরাব', 'শহীদী জামে'],
                'eid-prayer' => ['ঈদের নামাজ', 'ঈদগাহ', 'ঈদুল ফিতর', 'ঈদুল আজহা', 'ঈদ জামাত', 'ঈদ পুনর্মিলনী'],
                'interfaith-dialogue' => ['সর্বধর্মীয়', 'আন্তঃধর্মীয়', 'সর্বধর্মীয়', 'সম্প্রীতি', 'ধর্মীয় মতবিনিময়'],
                'press-dialogue' => ['সাংবাদিক', 'সংবাদ সম্মেলন', 'প্রেস ক্লাব', 'প্রেসক্লাব', 'প্রেস রিলিজ', 'বিবৃতি', 'মিট দ্য প্রেস', 'সাংবাদিকদের'],
                'rallies-and-meetings' => ['জনসভা', 'সমাবেশ', 'র‍্যালি', 'সুধী সমাবেশ', 'সেমিনার', 'সম্মেলন', 'কর্মসূচি', 'আলোচনা সভা', 'কর্মী সভা'],
                'welfare-activities' => ['জনকল্যাণ', 'রক্তদান', 'ত্রাণ', 'মাদকবিরোধী', 'চিকিৎসা', 'সামাজিক উদ্যোগ', 'ত্রাণ বিতরণ', 'ইফতার মাহফিল'],
                'rebuttal-and-legal' => ['মামলা', 'বৈধতা', 'আইনি', 'আদালত', 'বিচার', 'হাইকোর্ট', 'আইনজীবী', 'শুনানি', 'মিথ্যা মামলা', 'অপপ্রচার', 'জবাব', 'প্রত্যাখ্যান', 'ষড়যন্ত্র'],
                'history-of-persecution' => ['হামলা', 'নির্যাতন', 'আক্রান্ত', 'হত্যাকাণ্ড', 'পাবনা', 'নিহত', 'আহত', 'পিটিয়ে হত্যা', 'মিথ্যা অপবাদ', 'মানবাধিকার', 'দাঙ্গা', 'লুটপাট', 'উগ্রপন্থী', 'সোনাইমুড়ী'],
                'approval-and-legality' => ['অনুমোদন', 'অনাপত্তি', 'অনুমতি', 'ছাড়পত্র', 'প্রামাণ্য চিত্র প্রদর্শন'],
                'ideology-and-religion' => ['ইসলাম', 'ধর্মীয়', 'ভ্রান্তি', 'অপনোদন', 'ধর্ম', 'আল্লাহ', 'রাসূল', 'রসুল', 'কোরআন', 'হাদিস', 'তওহীদ', 'রোজা', 'হজ', 'যাকাত', 'আদর্শ', 'শান্তি', 'উগ্রবাদ', 'জঙ্গিবাদ'],
                'articles-and-editorials' => ['নিবন্ধ', 'সম্পাদকীয়', 'রাজনীতি', 'সমাজনীতি', 'বিজ্ঞান', 'ইতিহাস', 'দর্শন', 'বিশ্লেষণ', 'প্রবন্ধ', 'সমাজ সংস্কার', 'কুসংস্কার']
            ];

            $englishRules = [
                'cultural-activities' => ['cultural', 'song', 'music', 'drama', 'play', 'poem', 'poetry', 'concert', 'documentary'],
                'sports-and-athletics' => ['sports', 'football', 'cricket', 'badminton', 'tournament', 'athletics'],
                'press-dialogue' => ['press release', 'press-release', 'announcement', 'declaration', 'journalist', 'press conference'],
                'rallies-and-meetings' => ['seminar', 'conference', 'rally', 'meeting', 'assembly', 'inauguration', 'discussion meeting'],
                'rebuttal-and-legal' => ['court', 'lawsuit', 'legal', 'verdict', 'allegation', 'rebuttal', 'false case', 'conspiracy', 'statement'],
                'history-of-persecution' => ['attack', 'persecution', 'torture', 'kill', 'murder', 'massacre', 'wounded', 'injured', 'assault'],
                'ideology-and-religion' => ['islam', 'prophet', 'quran', 'hadith', 'belief', 'tawheed', 'peace', 'extremism', 'militancy', 'religion', 'faith', 'allah', 'muslim'],
                'articles-and-editorials' => ['article', 'editorial', 'opinion', 'essay', 'analysis', 'column', 'politics', 'history']
            ];

            // 3. Process All 4,250+ Posts in Batches (Production Best Practice: Micro-Transactions per chunk)
            $totalCount = Blog::count();
            $this->info("Found {$totalCount} total blog posts to process.");

            $categoryCounts = array_fill_keys(array_keys($slugToIdMap), 0);
            $uniqueTags = [];
            $slugRedirects = [];
            $generatedPostSlugs = [];
            $processedCount = 0;

            Blog::chunk(200, function ($blogs) use (
                &$categoryCounts, &$uniqueTags, &$slugRedirects, &$generatedPostSlugs, &$processedCount,
                $slugToIdMap, $rules, $englishRules, $isDryRun, $log
            ) {
                if (!$isDryRun) {
                    DB::beginTransaction();
                }

                try {
                    foreach ($blogs as $blog) {
                        $processedCount++;
                        $title = $blog->title;
                        $content = strip_tags($blog->content ?? '');

                        // Parse and clean tags
                        $currentTags = [];
                        if (is_array($blog->tags)) {
                            $currentTags = $blog->tags;
                        } elseif (is_string($blog->tags)) {
                            $decoded = json_decode($blog->tags, true);
                            $currentTags = is_array($decoded) ? $decoded : explode(',', $blog->tags);
                        }

                        $cleanedTags = [];
                        foreach ($currentTags as $t) {
                            $t = trim($t);
                            if (empty($t)) continue;
                            if (mb_strlen($t) > 50 || count(explode(' ', $t)) > 4) continue;

                            // Standardize Tag variants
                            if (in_array(strtolower($t), ['hezbut tawheed', 'hezbut-tawheed', 'হেজবুত তওহীদ'])) {
                                $t = 'হেযবুত তওহীদ';
                            } elseif ($t === 'ইসলামি ইতিহাস') {
                                $t = 'ইসলামের ইতিহাস';
                            }
                            $cleanedTags[] = $t;
                        }
                        $cleanedTags = array_unique($cleanedTags);

                        // Category Scoring Algorithm
                        $targetSlug = 'general-discussion';
                        $isBengali = preg_match('/[\x{0980}-\x{09FF}]/u', $title);

                        if (!$isBengali) {
                            $scores = [];
                            foreach ($englishRules as $slug => $keywords) {
                                $score = 0;
                                foreach ($keywords as $kw) {
                                    if (mb_strpos(strtolower($title), $kw) !== false) $score += 5;
                                }
                                foreach ($cleanedTags as $tagVal) {
                                    foreach ($keywords as $kw) {
                                        if (mb_strpos(strtolower($tagVal), $kw) !== false) $score += 3;
                                    }
                                }
                                $scores[$slug] = $score;
                            }
                            arsort($scores);
                            $topSlug = key($scores);
                            if (current($scores) > 0) {
                                $targetSlug = $topSlug;
                            } else {
                                $targetSlug = 'english-articles';
                            }
                        } else {
                            $scores = [];
                            foreach ($rules as $slug => $keywords) {
                                $score = 0;
                                foreach ($keywords as $kw) {
                                    if (mb_strpos($title, $kw) !== false) $score += 5;
                                }
                                foreach ($cleanedTags as $tagVal) {
                                    foreach ($keywords as $kw) {
                                        if (mb_strpos($tagVal, $kw) !== false) $score += 3;
                                    }
                                }
                                $contentSnippet = mb_substr($content, 0, 1000);
                                foreach ($keywords as $kw) {
                                    if (mb_strpos($contentSnippet, $kw) !== false) $score += 1;
                                }
                                $scores[$slug] = $score;
                            }

                            // Cultural distinction handled via refined rulesets

                            arsort($scores);
                            $topSlug = key($scores);
                            if (current($scores) > 0) {
                                $targetSlug = $topSlug;
                            }
                        }

                        $categoryId = $slugToIdMap[$targetSlug] ?? $slugToIdMap['general-discussion'];

                        // Generate English Slug & Redirect Mapping
                        $oldSlug = $blog->slug;
                        $shortTitle = Str::words($title, 8, '');
                        $newSlug = $this->transliterateBengaliToEnglish($shortTitle);
                        if (empty($newSlug)) {
                            $newSlug = 'post-' . $blog->id;
                        }

                        $originalNewSlug = $newSlug;
                        $c = 1;
                        while (isset($generatedPostSlugs[$newSlug]) || Blog::where('slug', $newSlug)->where('id', '!=', $blog->id)->exists()) {
                            $newSlug = $originalNewSlug . '-' . $c;
                            $c++;
                        }
                        $generatedPostSlugs[$newSlug] = true;

                        if ($oldSlug !== $newSlug) {
                            $slugRedirects[$oldSlug] = $newSlug;
                            $slugRedirects[urldecode($oldSlug)] = $newSlug;
                        }

                        // Populate image alt & caption if empty
                        $imageAlt = $blog->featured_image_alt;
                        if (empty($imageAlt)) {
                            $imageAlt = $title;
                        }
                        $imageCaption = $blog->featured_image_caption;
                        if (empty($imageCaption) && !empty($blog->short_description)) {
                            $imageCaption = Str::limit(strip_tags($blog->short_description), 100);
                        }

                        if (!$isDryRun) {
                            $blog->category_id = $categoryId;
                            $blog->slug = $newSlug;
                            $blog->tags = $cleanedTags;
                            $blog->featured_image_alt = $imageAlt;
                            $blog->featured_image_caption = $imageCaption;
                            $blog->save();
                        }

                        $categoryCounts[$targetSlug]++;
                        foreach ($cleanedTags as $tagName) {
                            $uniqueTags[$tagName] = true;
                        }
                    }

                    if (!$isDryRun) {
                        DB::commit();
                    }
                    $log("Processed batch up to {$processedCount} posts.");

                } catch (\Exception $e) {
                    if (!$isDryRun) DB::rollBack();
                    $log("Error in batch at count {$processedCount}: " . $e->getMessage(), 'error');
                    throw $e;
                }
            });

            // 4. Save Tag Records
            if (!$isDryRun && count($uniqueTags) > 0) {
                $sort = 1;
                foreach (array_keys($uniqueTags) as $tName) {
                    $tSlug = $this->transliterateBengaliToEnglish($tName);
                    if (empty($tSlug)) $tSlug = 'tag-' . Str::random(4);

                    $orig = $tSlug;
                    $c = 1;
                    while (Tag::where('slug', $tSlug)->exists()) {
                        $tSlug = $orig . '-' . $c;
                        $c++;
                    }

                    Tag::create([
                        'name' => $tName,
                        'slug' => $tSlug,
                        'color' => '#6c757d',
                        'status' => true,
                        'sort_order' => $sort++,
                    ]);
                }
                $this->info("Populated " . count($uniqueTags) . " clean tags into database.");
            }

            // 5. Save Redirects File
            if (!$isDryRun) {
                file_put_contents(storage_path('app/blog_slug_redirects.json'), json_encode($slugRedirects, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                \Illuminate\Support\Facades\Cache::forever('blog_slug_redirects', $slugRedirects);
                $this->info("Saved " . count($slugRedirects) . " 301 slug redirects to storage & cache.");
            }

            // 6. Print Summary Report Table
            $this->newLine();
            $this->info("==========================================================");
            $this->info("           REFACTORING SUMMARY REPORT " . ($isDryRun ? '(DRY-RUN)' : '(COMPLETED)'));
            $this->info("==========================================================");
            
            $headers = ['Category Slug', 'Category Name', 'Processed Posts'];
            $rows = [];
            foreach ($slugToIdMap as $catSlug => $catId) {
                $catObj = BlogCategory::find($catId);
                $rows[] = [
                    $catSlug,
                    $catObj ? $catObj->name : 'N/A',
                    $categoryCounts[$catSlug] ?? 0
                ];
            }
            $this->table($headers, $rows);

            $this->info("Total Posts Processed: {$processedCount}");
            $this->info("Total Unique Clean Tags: " . count($uniqueTags));
            $this->info("Detailed log written to: {$logPath}");

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Refactoring error: ' . $e->getMessage());
            $log('Fatal error: ' . $e->getMessage() . "\n" . $e->getTraceAsString(), 'error');
            return 1;
        }
    }

    /**
     * Fallback phonetic transliterator for Bengali tag/slug names to English.
     */
    protected function transliterateBengaliToEnglish($text)
    {
        if (!preg_match('/[\x{0980}-\x{09FF}]/u', $text)) {
            return Str::slug($text);
        }

        $map = [
            'অ' => 'o', 'আ' => 'a', 'ই' => 'i', 'ঈ' => 'i', 'উ' => 'u', 'ঊ' => 'u', 'ঋ' => 'ri', 'এ' => 'e', 'ঐ' => 'oi', 'ও' => 'o', 'ঔ' => 'ou',
            'ক' => 'k', 'খ' => 'kh', 'গ' => 'g', 'ঘ' => 'gh', 'ঙ' => 'ng',
            'চ' => 'ch', 'ছ' => 'ch', 'জ' => 'j', 'ঝ' => 'jh', 'ঞ' => 'n',
            'ট' => 't', 'ঠ' => 'th', 'ড' => 'd', 'ঢ' => 'dh', 'ণ' => 'n',
            'ত' => 't', 'থ' => 'th', 'দ' => 'd', 'ধ' => 'dh', 'ন' => 'n',
            'প' => 'p', 'ফ' => 'f', 'ব' => 'b', 'ভ' => 'bh', 'ম' => 'm',
            'য' => 'z', 'র' => 'r', 'ল' => 'l', 'শ' => 'sh', 'ষ' => 'sh', 'স' => 's', 'হ' => 'h',
            'ড়' => 'r', 'ঢ়' => 'rh', 'য়' => 'y', 'ৎ' => 't',
            'া' => 'a', 'ি' => 'i', 'ী' => 'i', 'ু' => 'u', 'ূ' => 'u', 'ৃ' => 'ri',
            'ে' => 'e', 'ৈ' => 'oi', 'ো' => 'o', 'ৌ' => 'ou',
            'ং' => 'ng', 'ঃ' => 'h', 'ঁ' => '', '্' => ''
        ];

        $result = '';
        $len = mb_strlen($text);
        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($text, $i, 1);
            $result .= $map[$char] ?? $char;
        }

        $slug = preg_replace('/[^\p{L}\p{N}]+/u', '-', $result);
        $slug = trim($slug, '-');
        return mb_strtolower($slug);
    }
}
