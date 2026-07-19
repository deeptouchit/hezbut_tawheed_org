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
    protected $signature = 'blog:refactor-categories-tags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refactor blog categories and tags, and clean up duplicate and invalid tags.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting blog categories and tags refactoring...');

        try {
            // 1. Temporarily disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');

            // 2. Truncate categories table
            BlogCategory::truncate();
            $this->info('Cleared existing blog categories.');

            // 3. Insert 9 new categories with Meta and SEO details
            $categoriesData = [
                [
                    'id' => 1,
                    'name' => 'আদর্শিক ও ধর্মীয় দৃষ্টিভঙ্গি',
                    'slug' => 'ideology-and-religion',
                    'description' => 'ধর্মীয় ব্যাখ্যা, ঈমান, ইসলাম এবং আদেশকারী নেতৃত্ব',
                    'meta_title' => 'আদর্শিক ও ধর্মীয় দৃষ্টিভঙ্গি - হেজবুত তওহীদ',
                    'meta_description' => 'ইসলামের প্রকৃত আদর্শ, জিহাদ, তওহীদ এবং বিভিন্ন ধর্মীয় বিভ্রান্তি নিরসনমূলক আলোচনা',
                    'status' => true,
                    'sort_order' => 1,
                ],
                [
                    'id' => 2,
                    'name' => 'সেবামূলক ও সচেতনতা কার্যক্রম',
                    'slug' => 'activities',
                    'description' => 'রক্তদান, মাদকবিরোধী সচেতনতা এবং সামাজিক উদ্যোগ',
                    'meta_title' => 'সেবামূলক ও সচেতনতা কার্যক্রম - হেজবুত তওহীদ',
                    'meta_description' => 'সমাজ সংস্কার, ত্রাণ বিতরণ, মাদকবিরোধী আন্দোলন ও মানবিক সেবামূলক কার্যক্রম',
                    'status' => true,
                    'sort_order' => 2,
                ],
                [
                    'id' => 3,
                    'name' => 'অনুষ্ঠান ও কর্মসূচি',
                    'slug' => 'events-and-programs',
                    'description' => 'জনসভা, সেমিনার, র্যালি ও সম্মেলন সংক্রান্ত প্রতিবেদন',
                    'meta_title' => 'অনুষ্ঠান ও কর্মসূচি - হেজবুত তওহীদ',
                    'meta_description' => 'হেজবুত তওহীদের বিভিন্ন শাখার সম্মেলন, আলোচনা সভা ও কর্মসূচির বিবরণ',
                    'status' => true,
                    'sort_order' => 3,
                ],
                [
                    'id' => 4,
                    'name' => 'অপপ্রচারের জবাব ও আইনি লড়াই',
                    'slug' => 'rebuttal-and-legal',
                    'description' => 'হেযবুত তওহীদ বৈধতার প্রমাণ ও আইনি লড়াইয়ের বিবরণ',
                    'meta_title' => 'অপপ্রচারের জবাব ও আইনি লড়াই - হেজবুত তওহীদ',
                    'meta_description' => 'হেজবুত তওহীদের বিরুদ্ধে অপপ্রচারের যৌক্তিক জবাব ও আইনি লড়াইয়ের তথ্য',
                    'status' => true,
                    'sort_order' => 4,
                ],
                [
                    'id' => 5,
                    'name' => 'নির্যাতনের ইতিহাস ও মানবাধিকার',
                    'slug' => 'history-of-persecution',
                    'description' => 'আমাদের ওপর হামলা ও নির্যাতনের ইতিহাস',
                    'meta_title' => 'নির্যাতনের ইতিহাস ও মানবাধিকার - হেজবুত তওহীদ',
                    'meta_description' => 'হেজবুত তওহীদের সদস্যদের ওপর উগ্রপন্থীদের বর্বর হামলা ও নির্যাতনের সত্য ঘটনা',
                    'status' => true,
                    'sort_order' => 5,
                ],
                [
                    'id' => 6,
                    'name' => 'কার্যক্রমের অনুমোদন ও বৈধতা',
                    'slug' => 'approval-and-legality',
                    'description' => 'হেযবুত তাওহীদের কার্যক্রমের সরকারি অনুমোদন ও বৈধতা',
                    'meta_title' => 'কার্যক্রমের অনুমোদন ও বৈধতা - হেজবুত তওহীদ',
                    'meta_description' => 'হেজবুত তওহীদের সামাজিক কার্যক্রমের সরকারি অনুমোদন ও বিভিন্ন এজেন্সির ছাড়পত্র',
                    'status' => true,
                    'sort_order' => 6,
                ],
                [
                    'id' => 7,
                    'name' => 'নিবন্ধ ও সম্পাদকীয়',
                    'slug' => 'articles-and-editorials',
                    'description' => 'রাজনীতি, সমাজনীতি, বিজ্ঞান ও চিন্তাশীল কলাম',
                    'meta_title' => 'নিবন্ধ ও সম্পাদকীয় - হেজবুত তওহীদ',
                    'meta_description' => 'সমসামयिक বিষয়াবলী, সমাজ সংস্কার ও দর্শনের ওপর কলামিস্টদের প্রবন্ধ',
                    'status' => true,
                    'sort_order' => 7,
                ],
                [
                    'id' => 8,
                    'name' => 'English Articles',
                    'slug' => 'english-articles',
                    'description' => 'All articles and contents published in English language',
                    'meta_title' => 'English Articles - Hezbut Tawheed',
                    'meta_description' => 'Read articles, opinions, and statements by Hezbut Tawheed in English',
                    'status' => true,
                    'sort_order' => 8,
                ],
                [
                    'id' => 9,
                    'name' => 'সাধারণ আলোচনা',
                    'slug' => 'general-discussion',
                    'description' => 'অন্যান্য সাধারণ বিষয়ের ব্লগ পোস্ট',
                    'meta_title' => 'সাধারণ আলোচনা - হেজবুত তওহীদ',
                    'meta_description' => 'হেজবুত তওহীদের সাধারণ আলোচনা ও বিবিধ বিষয়ের পোস্ট',
                    'status' => true,
                    'sort_order' => 9,
                ],
                [
                    'id' => 10,
                    'name' => 'প্রেস রিলিজ ও বিবৃতি',
                    'slug' => 'press-release',
                    'description' => 'হেযবুত তওহীদের নীতিগত অবস্থান, বিবৃতি ও প্রেস রিলিজ',
                    'meta_title' => 'প্রেস রিলিজ ও বিবৃতি - হেজবুত তওহীদ',
                    'meta_description' => 'হেজবুত তওহীদের অফিশিয়াল প্রেস রিলিজ, বিজ্ঞপ্তি ও নীতিগত বিবৃতি',
                    'status' => true,
                    'sort_order' => 10,
                ]
            ];

            foreach ($categoriesData as $cat) {
                BlogCategory::create($cat);
            }
            $this->info('Successfully created 10 new categories.');

            // 4. Clean and Truncate the tags table
            Tag::truncate();
            $this->info('Cleared tags table.');

            DB::beginTransaction();

            // 5. Define Category Rules (Keywords Dictionary for Bengali and English)
            $rules = [
                'rebuttal-and-legal' => ['মামলা', 'বৈধতা', 'আইনি', 'আদালত', 'বিচার', 'হাইকোর্ট', 'আইনজীবী', 'শুনানি', 'মিথ্যা মামলা', 'অপপ্রচার', 'জবাব', 'প্রত্যাখ্যান', 'কূটচাল', 'ষড়যন্ত্র', 'ষড়যন্ত্রমূলক'],
                'history-of-persecution' => ['হামলা', 'নির্যাতন', 'আক্রান্ত', 'হত্যাকাণ্ড', 'পাবনা', 'নিহত', 'আহত', 'পিটিয়ে হত্যা', 'মিথ্যা অপবাদ', 'মানবাধিকার', 'দাঙ্গা', 'লুটপাট', 'উগ্রপন্থী'],
                // Strictly synced by WP API at the end
                'approval-and-legality' => [],
                'press-release' => ['বিজ্ঞপ্তি', 'বিবৃতি', 'সংবাদ বিজ্ঞপ্তি', 'প্রেস বিজ্ঞপ্তি', 'প্রেস রিলিজ', 'আনুষ্ঠানিক বিবৃতি', 'লিখিত বক্তব্য', 'সংবাদ সম্মেলন', 'প্রেসক্লাব', 'প্রেস ক্লাব', 'সাংবাদিক', 'সাংবাদিকদের', 'প্রেস ব্রিফিং', 'মিট দ্য প্রেস', 'মিট দ্যা প্রেস'],
                'events-and-programs' => ['সভা', 'সেমিনার', 'সম্মেলন', 'জনসভা', 'র‌্যালি', 'মানববন্ধন', 'সুধী সমাবেশ', 'উদ্বোধন', 'সফর', 'উপস্থিত', 'আয়োজন', 'কর্মসূচি', 'অনুষ্ঠান'],
                // Strictly synced by WP API at the end
                'activities' => [],
                'ideology-and-religion' => ['ইসলাম', 'ধর্মীয়', 'ভ্রান্তি', 'অপনোদন', 'ধর্ম', 'আল্লাহ', 'রাসূল', 'রসুল', 'কোরআন', 'হাদিস', 'তওহীদ', 'নামাজ', 'রোজা', 'হজ', 'যাকাত', 'মসজিদ', 'আদর্শ', 'সিরিয়া', 'দাজ্জাল', 'জেহাদ', 'জিহাদ', 'শান্তি', 'ওয়াজ', 'মাহফিল', 'উগ্রবাদ', 'জঙ্গিবাদ', 'অপব্যাখ্যা'],
                'articles-and-editorials' => ['নিবন্ধ', 'সম্পাদকীয়', 'রাজনীতি', 'সমাজনীতি', 'বিজ্ঞান', 'ইতিহাস', 'দর্শন', 'বিশ্লেষণ', 'প্রবন্ধ', 'সমাজ সংস্কার', 'কুসংস্কার']
            ];

            $englishRules = [
                'ideology-and-religion' => ['islam', 'prophet', 'quran', 'hadith', 'belief', 'tawheed', 'peace', 'extremism', 'militancy', 'religion', 'religious', 'faith', 'allah', 'muslim', 'muslims'],
                // Strictly synced by WP API at the end
                'activities' => [],
                'press-release' => ['press release', 'press-release', 'announcement', 'declaration'],
                'events-and-programs' => ['seminar', 'conference', 'rally', 'meeting', 'assembly', 'inauguration', 'discussion meeting'],
                'rebuttal-and-legal' => ['court', 'lawsuit', 'legal', 'verdict', 'allegation', 'rebuttal', 'false case', 'conspiracy', 'statement'],
                'history-of-persecution' => ['attack', 'persecution', 'torture', 'kill', 'murder', 'massacre', 'wounded', 'injured', 'assault'],
                // Strictly synced by WP API at the end
                'approval-and-legality' => [],
                'articles-and-editorials' => ['article', 'editorial', 'opinion', 'essay', 'analysis', 'column', 'politics', 'history']
            ];

            // 6. Recategorize posts using chunking (100 posts at a time for memory safety)
            $categoryCounts = array_fill(1, 10, 0);
            $uniqueTags = [];
            $slugRedirects = [];
            $generatedPostSlugs = [];

            Blog::chunk(100, function ($blogs) use (&$categoryCounts, &$uniqueTags, &$slugRedirects, &$generatedPostSlugs, $rules, $englishRules) {
                foreach ($blogs as $blog) {
                    $title = $blog->title;
                    $content = strip_tags($blog->content);
                    
                    // Get current tags array
                    $currentTags = [];
                    if (is_array($blog->tags)) {
                        $currentTags = $blog->tags;
                    } elseif (is_string($blog->tags)) {
                        $decoded = json_decode($blog->tags, true);
                        $currentTags = is_array($decoded) ? $decoded : explode(',', $blog->tags);
                    }

                    // Clean and filter current tags
                    $cleanedTags = [];
                    foreach ($currentTags as $t) {
                        $t = trim($t);
                        if (empty($t)) continue;
                        
                        // Rule 1: Delete long/sentence-like tags (len > 50 or > 4 words)
                        if (mb_strlen($t) > 50 || count(explode(' ', $t)) > 4) {
                            continue;
                        }

                        // Rule 2: Merge tags
                        if (in_array(strtolower($t), ['hezbut tawheed', 'à¦¹à§‡à¦¯à¦¬à§�à¦¤ à¦¤à¦“à¦¹à§€', 'hezbut-tawheed'])) {
                            $t = 'à¦¹à§‡à¦¯à¦¬à§�à¦¤ à¦¤à¦“à¦¹à§€à¦¦';
                        } elseif ($t === 'à¦‡à¦¸à¦²à¦¾à¦®à¦¿ à¦‡à¦¤à¦¿à¦¹à¦¾à¦¸') {
                            $t = 'à¦‡à¦¸à¦²à¦¾à¦®à§‡à¦° à¦‡à¦¤à¦¿à¦¹à¦¾à¦¸';
                        } elseif ($t === 'à¦§à¦°à§�à¦®à¦œà§€à¦¬à¦¿à¦•à¦¾') {
                            $t = 'à¦§à¦°à§�à¦®à¦¬à§�à¦¯à¦¬à¦¸à¦¾';
                        }

                        $cleanedTags[] = $t;
                    }

                    // De-duplicate tags
                    $cleanedTags = array_unique($cleanedTags);

                    // Determine Category
                    $categoryId = 9; // Default: general-discussion

                    // Check Language (Bangla vs English)
                    $isBengali = preg_match('/[\x{0980}-\x{09FF}]/u', $title);

                    if (!$isBengali) {
                        // Case A: English categorization based on english keyword score
                        $scores = [];
                        foreach ($englishRules as $slug => $keywords) {
                            $score = 0;
                            foreach ($keywords as $kw) {
                                if (mb_strpos(strtolower($title), $kw) !== false) {
                                    $score += 3;
                                }
                            }
                            foreach ($cleanedTags as $tagVal) {
                                foreach ($keywords as $kw) {
                                    if (mb_strpos(strtolower($tagVal), $kw) !== false) {
                                        $score += 5;
                                    }
                                }
                            }
                            $contentSnippet = mb_substr(strtolower($content), 0, 1000);
                            foreach ($keywords as $kw) {
                                if (mb_strpos($contentSnippet, $kw) !== false) {
                                    $score += 1;
                                }
                            }
                            $scores[$slug] = $score;
                        }

                        arsort($scores);
                        $highestSlug = key($scores);
                        $highestScore = current($scores);

                        if ($highestScore > 0) {
                            $slugMap = [
                                'ideology-and-religion' => 1,
                                'activities' => 2,
                                'events-and-programs' => 3,
                                'rebuttal-and-legal' => 4,
                                'history-of-persecution' => 5,
                                'approval-and-legality' => 6,
                                'articles-and-editorials' => 7,
                            ];
                            $categoryId = $slugMap[$highestSlug] ?? 8; // fallback to english-articles
                        } else {
                            $categoryId = 8; // default to english-articles
                        }
                    } else {
                        // Case B: Bengali categorization based on keyword score
                        $scores = [];
                        foreach ($rules as $slug => $keywords) {
                            $score = 0;
                            
                            // 1. Check title (3 points per match)
                            foreach ($keywords as $kw) {
                                if (mb_strpos($title, $kw) !== false) {
                                    $score += 3;
                                }
                            }

                            // 2. Check tags (5 points per match)
                            foreach ($cleanedTags as $tagVal) {
                                foreach ($keywords as $kw) {
                                    if (mb_strpos($tagVal, $kw) !== false) {
                                        $score += 5;
                                    }
                                }
                            }

                            // 3. Check content (1 point per match, limit to first 1000 chars)
                            $contentSnippet = mb_substr($content, 0, 1000);
                            foreach ($keywords as $kw) {
                                if (mb_strpos($contentSnippet, $kw) !== false) {
                                    $score += 1;
                                }
                            }

                            $scores[$slug] = $score;
                        }

                        // Get category with highest score
                        arsort($scores);
                        $highestSlug = key($scores);
                        $highestScore = current($scores);

                        if ($highestScore > 0) {
                            // Map slug to category ID
                            $slugMap = [
                                'ideology-and-religion' => 1,
                                'activities' => 2,
                                'events-and-programs' => 3,
                                'rebuttal-and-legal' => 4,
                                'history-of-persecution' => 5,
                                'approval-and-legality' => 6,
                                'articles-and-editorials' => 7,
                                'press-release' => 10,
                            ];
                            $categoryId = $slugMap[$highestSlug] ?? 9;
                        }
                    }

                    // Validate history-of-persecution (ID 5) to only contain Hezbut Tawheed members persecution
                    if ($categoryId == 5) {
                        $isHTRelated = false;
                        
                        // Check if the title itself contains persecution terms
                        $titleLower = mb_strtolower($title);
                        $persecutionWords = ['হামলা', 'নির্যাতন', 'হত্যাকাণ্ড', 'হানি', 'খুন', 'হত্যা', 'আক্রান্ত', 'লুটপাট', 'দাঙ্গা', 'উগ্রপন্থী', 'মিট দ্যা প্রেস', 'সোনাইমুড়ী', 'সোনাইমুড়ি', 'সন্ত্রাসী', 'persecution', 'attack', 'kill', 'murder', 'wounded', 'injured', 'assault'];
                        $hasPersecutionWordInTitle = false;
                        foreach ($persecutionWords as $pw) {
                            if (mb_strpos($titleLower, $pw) !== false) {
                                $hasPersecutionWordInTitle = true;
                                break;
                            }
                        }

                        if ($hasPersecutionWordInTitle) {
                            // Check if it also has HT keywords or location keywords in the title
                            $targetKeywords = [
                                'হেযবুত', 'হেজবুত', 'তাওহীদ', 'তওহীদ', 'সুজন', 'সদস্য', 'আমাদের কর্মী', 'আমাদের উপর', 'আমাদের ওপর',
                                'পাবনা', 'হবিগঞ্জ', 'সুনামগঞ্জ', 'মাদারীপুর', 'রংপুর', 'ব্রাহ্মণবাড়ীয়া', 'কুষ্টিয়া', 'টাঙ্গাইল', 'ফেনী', 'নরসিংদী', 'নোয়াখালী', 'সোনাইমুড়ী', 'সোনাইমুড়ি',
                                'hezbut', 'tawheed', 'tawhid', 'salim', 'panni', 'sujon', 'imam', 'our member', 'our members', 'attack on us'
                            ];
                            foreach ($targetKeywords as $tkw) {
                                if (mb_strpos($titleLower, $tkw) !== false) {
                                    $isHTRelated = true;
                                    break;
                                }
                            }
                        }
                        
                        if (!$isHTRelated) {
                            // If it's English, demote to 8 (english-articles)
                            if (!$isBengali) {
                                $categoryId = 8;
                            } else {
                                // For Bengali, choose between 1 (ideology-and-religion) and 7 (articles-and-editorials)
                                if (isset($scores['ideology-and-religion']) && $scores['ideology-and-religion'] > $scores['articles-and-editorials']) {
                                    $categoryId = 1;
                                } else {
                                    $categoryId = 7;
                                }
                            }
                        }
                    }

                    // Generate new English slug
                    $oldSlug = $blog->slug;
                    $shortTitle = Str::words($title, 8, '');
                    $newSlug = $this->transliterateBengaliToEnglish($shortTitle);
                    
                    if (empty($newSlug)) {
                        $newSlug = 'post-' . Str::random(6);
                    }

                    // Prevent duplicate slugs
                    $originalNewSlug = $newSlug;
                    $c = 1;
                    while (isset($generatedPostSlugs[$newSlug]) || Blog::where('slug', $newSlug)->where('id', '!=', $blog->id)->exists()) {
                        $newSlug = $originalNewSlug . '-' . $c;
                        $c++;
                    }
                    $generatedPostSlugs[$newSlug] = true;

                    // Store redirection mapping (raw, decoded, lowercase)
                    if ($oldSlug !== $newSlug) {
                        $oldSlugDecoded = urldecode($oldSlug);
                        $slugRedirects[$oldSlug] = $newSlug;
                        $slugRedirects[$oldSlugDecoded] = $newSlug;
                        $slugRedirects[strtolower($oldSlug)] = $newSlug;
                        $slugRedirects[strtolower($oldSlugDecoded)] = $newSlug;
                    }

                    // Update blog post category, slug, and cleaned tags
                    $blog->category_id = $categoryId;
                    $blog->slug = $newSlug;
                    $blog->tags = $cleanedTags;
                    $blog->save();

                    $categoryCounts[$categoryId]++;

                    // Collect unique tags to populate tags table
                    foreach ($cleanedTags as $tagName) {
                        if (!isset($uniqueTags[$tagName])) {
                            $uniqueTags[$tagName] = true;
                        }
                    }
                }
            });

            // 6.5. Strict Verification & Sync of Category 6 (approval-and-legality) and Category 2 (activities) from WP API / Verbatim fallbacks
            $this->info('Performing strict sync for category 6 (approval-and-legality)...');
            
            $approvalTitles = [];
            try {
                $page = 1;
                while (true) {
                    $response = \Illuminate\Support\Facades\Http::timeout(10)->get("https://hezbuttawheed.org/wp-json/wp/v2/posts", [
                        'categories' => 41,
                        'per_page' => 100,
                        'page' => $page
                    ]);
                    if ($response->failed() || empty($response->json())) {
                        break;
                    }
                    foreach ($response->json() as $post) {
                        $title = html_entity_decode($post['title']['rendered']);
                        $title = trim(preg_replace('/\s+/', ' ', $title));
                        $approvalTitles[$title] = true;
                    }
                    $page++;
                }
            } catch (\Exception $e) {
                $this->warn('WP API connection failed for category 41 sync. Falling back to offline verbatim list: ' . $e->getMessage());
            }
            if (empty($approvalTitles)) {
                $approvalTitles = array_fill_keys([
                    'প্রামাণ্য চিত্র প্রদর্শনে নওগাঁ-৬ সংসদ সদস্যের অনুমোদন',
                    'প্রামাণ্য চিত্র প্রদর্শনে বগুড়া-৫ আসনের সংসদ সদস্যের অনুমোদন',
                    'প্রামাণ্য চিত্র প্রদর্শনে দিনাজপুর-৬ সংসদ সদস্যের অনুমোদন',
                    'প্রামাণ্য চিত্র প্রদর্শনে সাতক্ষীরা-৪ সংসদ সদস্যের অনুমোদন',
                    'প্রামাণ্য চিত্র প্রদর্শনে বরগুনা-২ সংসদ সদস্যের অনুমোদন',
                    'প্রামাণ্য চিত্র প্রদর্শনে ধর্ম মন্ত্রণালয়ের অনুমোদন',
                    'প্রামাণ্য চিত্র প্রদর্শনে দিনাজপুর-১ সংসদ সদস্যের অনুমোদন',
                    'যুব ও ক্রীড়া মন্ত্রণালয়ের প্রামাণ্যচিত্র প্রদর্শনীর অনুমোদন',
                    'স্বরাষ্ট্র মন্ত্রণালয়ে প্রেরিত আবেদন পত্র- ৩',
                    'স্বরাষ্ট্র মন্ত্রণালয়ে প্রেরিত আবেদন পত্র- ২',
                    'স্বরাষ্ট্র মন্ত্রণালয়ে প্রেরিত আবেদন পত্র',
                    'প্রধানমন্ত্রীর কার্যালয়ে প্রেরিত আবেদন পত্র',
                    'ঢাকা মেট্রোপলিটন পুলিশ কমিশনার এর অনুমোদন',
                    'टांगेल जिला पुलिस की मंजूरी',
                    'টাঙ্গাইল জেলা পুলিশের অনুমোদন',
                    'টাঙ্গাইল জেলা প্রশাসকের অনুমোদন',
                    'মেহেরপুর জেলা প্রশাসক সন্ত্রাস, জঙ্গিবাদ, ধর্মবিশ্বাস, অপরাজনীতি বিষয়ক আলোচনা সভা করার অনুমোদন।',
                    'প্রামান্যচিত্র প্রদর্শনে পঞ্চগড় জেলা প্রশাসকের অনুমোদন',
                    'পুলিশের উপ-মহাপরিদর্শকের অনুমোদন',
                    'প্রামাণ্যচিত্র প্রদর্শনে ফরিদপুর সদর ইউনিয়নের আওয়ামী লীগ সভাপতির সুপারিশ',
                    'প্রামান্যচিত্র প্রদর্শনে মাদারীপুর জেলা আওয়ামী লীগ এর অনুমোদন',
                    'ফریدপুরে ধর্ম ব্যবসা ও ধর্ম নিয়ে অপরাজনীতির বিরুদ্ধে গণসচেতনতা সৃষ্টি ও মতবিনিময় সভার মাধ্যমে জাতীয় ঐক্য গঠনে প্রামাণ্যচিত্র প্রদর্শনে অনুমতি',
                    'প্রামাণ্যচিত্র প্রদর্শনে মাদারিপুর সদর উপজেলা চেয়ারম্যানের অনুমোদন',
                    'প্রামাণ্যচিত্র প্রদর্শনে মাদারিপুর রাজৈর উপজেলা চেয়ারম্যানের অনুমোদন',
                    'প্রামাণ্যচিত্র প্রদর্শনে গোপালগঞ্জ সদর উপজেলা চেয়ারম্যানের সহযোগিতার আশ্বাস',
                    'প্রামাণ্যচিত্র প্রদর্শনে নোয়াখালী জেলা প্রশাসকের অনুমোদন',
                    'প্রামাণ্যচিত্র প্রদর্শনে গাইবান্ধা জেলা প্রশাসকের অনুমোদন',
                    'বাংলাদেশ ইসলামিক ফাউন্ডেশনে প্রামাণ্যচিত্রের কপি জমাদান',
                    'প্রামাণ্য চিত্র প্রদর্শনে কক্সবাজার মহিলা আসন-৫০ সংসদ সদস্যের অনুমোদন',
                    'মানবজাতি হোক এক পরিবার'
                ], true);
            }
            
            $this->info('Performing strict sync for category 2 (activities)...');
            $activitiesTitles = [];
            try {
                $page = 1;
                while (true) {
                    $response = \Illuminate\Support\Facades\Http::timeout(10)->get("https://hezbuttawheed.org/wp-json/wp/v2/posts", [
                        'categories' => 30,
                        'per_page' => 100,
                        'page' => $page
                    ]);
                    if ($response->failed() || empty($response->json())) {
                        break;
                    }
                    foreach ($response->json() as $post) {
                        $title = html_entity_decode($post['title']['rendered']);
                        $title = trim(preg_replace('/\s+/', ' ', $title));
                        $activitiesTitles[$title] = true;
                    }
                    $page++;
                }
            } catch (\Exception $e) {
                $this->warn('WP API connection failed for category 30 sync. Falling back to offline verbatim list: ' . $e->getMessage());
            }
            if (empty($activitiesTitles)) {
                $activitiesTitles = array_fill_keys([
                    'শ্রমিকের অধিকার ও মাদকমুক্ত সমাজ গঠন ‘নৈতিক জেহাদ’: রুফায়দাহ পন্নী',
                    'ময়মনসিংহে হেযবুত তওহীদের উদ্যোগে আলোচনা সভা ও ইফতার মাহফিল অনুষ্ঠিত',
                    'রংপুর আদালত ভবনে আইনজীবীদের সাথে হেযবুত তওহীদের ইফতার মাহফিল',
                    'কুমিল্লার শিল্পকলায় হেযবুত তওহীদের ইফতার মাহফিল',
                    'নোয়াখালীতে ডাবল মার্ডারের ১০ বছর! বিচার দাবিতে হেযবুত তওহীদের সংবাদ সম্মেলন',
                    'বাধা উপেক্ষা করে সিরাজগঞ্জে হেযবুত তওহীদের ইফতার মাহফিল অনুষ্ঠিত',
                    'গোপালগঞ্জে হেযবুত তওহীদের ইফতার মাহফিল অনুষ্ঠিত',
                    'টাঙ্গাইলে হেযবুত তওহীদ ব্যবসায়ী ফোরামের উদ্যোগে ইফতার মাহফিল অনুষ্ঠিত।',
                    'ফরিদপুর প্রেসক্লাবে হেযবুত তওহীদের ইফতার ও আলোচনা সভা',
                    'বরিশালে হেযবুত তওহীদ ছাত্র ফোরামের ইফতার মাহফিল',
                    'চট্টগ্রামে হেযবুত তওহীদ ছাত্র ফোরামের ইফতার মাহফিল অনুষ্ঠিত',
                    'কুষ্টিয়ার ভেড়ামারায় হেযবুত তওহীদের আলোচনা সভা',
                    'কুমিল্লায় হেযবুত তওহীদের ঈদ পুনর্মিলনী: ‘বাংলার মাটিকে মদিনার মতো রক্ষা করার অঙ্গীকার’',
                    'নোয়াখালীতে হোসাইন মোহাম্মদ সেলিম ফুটবল টুর্নামেন্টের ফাইনাল অনুষ্ঠিত',
                    'পাবনায় হেযবুত তওহীদ সদস্যদের উপর হামলার প্রতিবাদে সংবাদ সম্মেলন ও মানববন্ধন',
                    'হেযবুত তওহীদের উপর উগ্রপন্থীদের হামলা-হুমকির প্রতিবাদে কোলকাতায় মানববন্ধন',
                    'তারুণ্যের সভার উদ্যোগে নানা আয়োজনে মুখরিত কেন্দ্রীয় কচি-কাঁচার মেলা',
                    'জাতীয় প্রেসক্লাবে হেযবুত তওহীদের নারীদের প্রতিবাদ সমাবেশ ও বিক্ষোভ মিছিল'
                ], true);
            }
            
            // Normalization helper function
            $normalize = function($str) {
                $str = html_entity_decode($str);
                $str = preg_replace('/\s+/', '', $str);
                $str = str_replace(['।', ',', '.', '?', '-', '_', ':', '(', ')', '"', '\''], '', $str);
                return mb_strtolower($str);
            };
            
            $normalizedApprovalTitles = [];
            foreach ($approvalTitles as $title => $val) {
                $normalizedApprovalTitles[$normalize($title)] = true;
            }
            
            $normalizedActivitiesTitles = [];
            foreach ($activitiesTitles as $title => $val) {
                $normalizedActivitiesTitles[$normalize($title)] = true;
            }
            
            // Update database posts strictly
            $allBlogs = Blog::all();
            foreach ($allBlogs as $blog) {
                $normTitle = $normalize($blog->title);
                if (isset($normalizedApprovalTitles[$normTitle])) {
                    $blog->category_id = 6; // Set to approval-and-legality
                    $blog->save();
                } elseif (isset($normalizedActivitiesTitles[$normTitle])) {
                    $blog->category_id = 2; // Set to activities
                    $blog->save();
                }
            }
            
            // Recount category counts after final adjustments
            foreach (range(1, 9) as $id) {
                $categoryCounts[$id] = Blog::where('category_id', $id)->count();
            }
            
            // Save slug redirects mapping to file and cache
            file_put_contents(storage_path('app/blog_slug_redirects.json'), json_encode($slugRedirects, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            \Illuminate\Support\Facades\Cache::forever('blog_slug_redirects', $slugRedirects);
            $this->info('Saved ' . count($slugRedirects) . ' slug redirects to JSON mapping.');

            // Try to translate Bengali tags using Gemini API
            $apiKey = env('GEMINI_API_KEY');
            $tagSlugsMap = [];

            // Pre-defined offline mapping for common tags
            $offlineMap = [
                'হেযবুত তওহীদ' => 'hezbut-tawheed',
                'হেজবুত তওহীদ' => 'hezbut-tawheed',
                'ইসলাম' => 'islam',
                'শান্তি' => 'peace',
                'জিহাদ' => 'jihad',
                'ধর্মব্যবসা' => 'religion-business',
                'অপপ্রচার' => 'propaganda',
                'উগ্রবাদ' => 'extremism',
                'জঙ্গিবাদ' => 'militancy',
                'মামলা' => 'lawsuit',
                'নির্যাতন' => 'persecution',
                'মানববন্ধন' => 'human-chain',
                'সম্মেলন' => 'conference',
                'সেমিনার' => 'seminar',
                'প্রেস বিজ্ঞপ্তি' => 'press-release',
                'ইতিহাস' => 'history',
                'বিজ্ঞান' => 'science',
                'রাজনীতি' => 'politics',
                'বাংলাদেশ' => 'bangladesh',
                'ওয়াজ মাহফিল' => 'waz-mahfil',
                'ধর্ম' => 'religion',
                'আল্লাহ' => 'allah',
                'রসুল' => 'prophet',
                'রাসূল' => 'prophet',
                'ঈমান' => 'imaan',
                'তওহীদ' => 'tawheed'
            ];

            if ($apiKey && count($uniqueTags) > 0) {
                try {
                    $this->info('Translating tags to English slugs using Gemini API...');
                    $client = new \GuzzleHttp\Client(['timeout' => 25.0]);
                    
                    // Filter out tags that are already in the offline map
                    $tagsToTranslate = array_diff(array_keys($uniqueTags), array_keys($offlineMap));
                    
                    if (count($tagsToTranslate) > 0) {
                        $promptText = "Translate the following list of Bengali tag names into short, clean, SEO-friendly English slug strings (lowercase, only letters, numbers, and hyphens, no special characters). Return the result ONLY as a raw JSON key-value object where the key is the original Bengali tag name and the value is the English slug string. Do not include markdown code block formatting or any other text.\n\n" . json_encode(array_values($tagsToTranslate), JSON_UNESCAPED_UNICODE);

                        $response = $client->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey, [
                            'json' => [
                                'contents' => [[
                                    'parts' => [['text' => $promptText]]
                                ]]
                            ],
                            'headers' => ['Content-Type' => 'application/json']
                        ]);

                        $result = json_decode($response->getBody()->getContents(), true);
                        $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
                        
                        $firstBracket = strpos($text, '{');
                        $lastBracket = strrpos($text, '}');
                        if ($firstBracket !== false && $lastBracket !== false) {
                            $jsonString = substr($text, $firstBracket, $lastBracket - $firstBracket + 1);
                            $data = json_decode($jsonString, true);
                            if (is_array($data)) {
                                $tagSlugsMap = $data;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    $this->warn('Gemini API tag translation failed, falling back to local generator: ' . $e->getMessage());
                }
            }

            // Merge maps
            $finalSlugsMap = array_merge($offlineMap, $tagSlugsMap);

            // 7. Insert all unique tags into tags table
            $tagSortOrder = 1;
            foreach (array_keys($uniqueTags) as $tagName) {
                // Find translated slug or generate via local transliterator
                $tagSlug = $finalSlugsMap[$tagName] ?? null;

                if (empty($tagSlug)) {
                    $tagSlug = $this->transliterateBengaliToEnglish($tagName);
                }

                if (empty($tagSlug)) {
                    $tagSlug = 'tag-' . Str::random(5);
                }

                // Prevent duplicate slugs
                $originalSlug = $tagSlug;
                $c = 1;
                while (Tag::where('slug', $tagSlug)->exists()) {
                    $tagSlug = $originalSlug . '-' . $c;
                    $c++;
                }

                Tag::create([
                    'name' => $tagName,
                    'slug' => $tagSlug,
                    'color' => '#6c757d',
                    'status' => true,
                    'sort_order' => $tagSortOrder++,
                ]);
            }

            // 8. Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');

            DB::commit();

            // Clear cache
            try {
                \Illuminate\Support\Facades\Artisan::call('cache:clear');
                \Illuminate\Support\Facades\Artisan::call('view:clear');
            } catch (\Exception $e) {
                $this->warn('Failed to clear cache: ' . $e->getMessage());
            }

            $this->info('Refactoring completed successfully!');
            $this->info('Summary of posts per category:');
            $categoryNames = [
                1 => 'à¦†à¦¦à¦°à§�à¦¶à¦¿à¦• à¦“ à¦§à¦°à§�à¦®à§€à§Ÿ à¦¦à§ƒà¦·à§�à¦Ÿà¦¿à¦­à¦™à§�à¦—à¦¿',
                2 => 'à¦¸à§‡à¦¬à¦¾à¦®à§‚à¦²à¦• à¦“ à¦¸à¦šà§‡à¦¤à¦¨à¦¤à¦¾ à¦•à¦¾à¦°à§�à¦¯à¦•à§�à¦°à¦®',
                3 => 'à¦…à¦¨à§�à¦·à§�à¦ à¦¾à¦¨ à¦“ à¦•à¦°à§�à¦®à¦¸à§‚à¦šà¦¿',
                1 => 'à¦†à¦¦à¦°à§à¦¶à¦¿à¦• à¦“ à¦§à¦°à§à¦®à§€à§Ÿ à¦¦à§ƒà¦·à§à¦Ÿà¦¿à¦­à¦™à§à¦—à¦¿',
                2 => 'à¦¸à§‡à¦¬à¦¾à¦®à§‚à¦²à¦• à¦“ à¦¸à¦šà§‡à¦¤à¦¨à¦¤à¦¾ à¦•à¦¾à¦°à§à¦¯à¦•à§à¦°à¦®',
                3 => 'à¦…à¦¨à§à¦·à§à¦ à¦¾à¦¨ à¦“ à¦•à¦°à§à¦®à¦¸à§‚à¦šà¦¿',
                4 => 'à¦…à¦ªà¦ªà§à¦°à¦šà¦¾à¦°à§‡à¦° à¦œà¦¬à¦¾à¦¬ à¦“ à¦†à¦‡à¦¨à¦¿ à¦²à§œà¦¾à¦‡',
                5 => 'à¦¨à¦¿à¦°à§à¦¯à¦¾à¦¤à¦¨à§‡à¦° à¦‡à¦¤à¦¿à¦¹à¦¾à¦¸ à¦“ à¦®à¦¾à¦¨à¦¬à¦¾à¦§à¦¿à¦•à¦¾à¦°',
                6 => 'à¦•à¦¾à¦°à§à¦¯à¦•à§à¦°à¦®à§‡à¦° à¦…à¦¨à§à¦®à§‹à¦¦à¦¨ à¦“ à¦¬à§ˆà¦§à¦¤à¦¾',
                7 => 'à¦¨à¦¿à¦¬à¦¨à§à¦§ à¦“ à¦¸à¦®à§à¦ªà¦¾à¦¦à¦•à§€à§Ÿ',
                8 => 'English Articles',
                9 => 'à¦¸à¦¾à¦§à¦¾à¦°à¦£ à¦†à¦²à§‹à¦šà¦¨à¦¾',
                10 => 'প্রেস রিলিজ ও বিবৃতি'
            ];

            foreach ($categoryNames as $id => $name) {
                $this->line("- {$name}: {$categoryCounts[$id]} posts");
            }
            $this->info("Total unique tags populated: " . count($uniqueTags));

        } catch (\Exception $e) {
            $originalMessage = $e->getMessage();
            try {
                DB::rollBack();
            } catch (\Exception $rollBackException) {
                // Ignore rollback exception to avoid masking the original error
            }
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            } catch (\Exception $fkException) {
                // Ignore FK statement exception
            }
            $this->error('Refactoring failed: ' . $originalMessage);
            $this->error('Trace: ' . $e->getTraceAsString());
            Log::error('Categories and tags refactoring failed: ' . $originalMessage . "\n" . $e->getTraceAsString());
        }
    }

    /**
     * Fallback phonetic transliterator for Bengali tag names to English slugs.
     */
    protected function transliterateBengaliToEnglish($text)
    {
        // If it is already mostly English, just slugify it
        if (!preg_match('/[\x{0980}-\x{09FF}]/u', $text)) {
            return Str::slug($text);
        }

        $map = [
            // Independent Vowels
            'à¦…' => 'o', 'à¦†' => 'a', 'à¦‡' => 'i', 'à¦ˆ' => 'i', 'à¦‰' => 'u', 'à¦Š' => 'u', 'à¦‹' => 'ri', 'à¦�' => 'e', 'à¦�' => 'oi', 'à¦“' => 'o', 'à¦”' => 'ou',
            // Consonants
            'à¦•' => 'k', 'à¦–' => 'kh', 'à¦—' => 'g', 'à¦˜' => 'gh', 'à¦™' => 'ng',
            'à¦š' => 'ch', 'à¦›' => 'ch', 'à¦œ' => 'j', 'à¦�' => 'jh', 'à¦ž' => 'n',
            'à¦Ÿ' => 't', 'à¦ ' => 'th', 'à¦¡' => 'd', 'à¦¢' => 'dh', 'à¦£' => 'n',
            'à¦¤' => 't', 'à¦¥' => 'th', 'à¦¦' => 'd', 'à¦§' => 'dh', 'à¦¨' => 'n',
            'à¦ª' => 'p', 'à¦«' => 'f', 'à¦¬' => 'b', 'à¦­' => 'bh', 'à¦®' => 'm',
            'à¦¯' => 'z', 'à¦°' => 'r', 'à¦²' => 'l', 'à¦¶' => 'sh', 'à¦·' => 'sh', 'à¦¸' => 's', 'à¦¹' => 'h',
            'à§œ' => 'r', 'à§�' => 'rh', 'à§Ÿ' => 'y', 'à§Ž' => 't',
            // Vowel Signs
            'à¦¾' => 'a', 'à¦¿' => 'i', 'à§€' => 'i', 'à§�' => 'u', 'à§‚' => 'u', 'à§ƒ' => 'ri',
            'à§‡' => 'e', 'à§ˆ' => 'oi', 'à§‹' => 'o', 'à§Œ' => 'ou',
            // Modifiers
            'à¦‚' => 'ng', 'à¦ƒ' => 'h', 'à¦�' => '', 'à§�' => '', 'à¦¤à§�' => 't'
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
