<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prevent running in production unless explicitly allowed
        if (app()->environment('production')) {
            return;
        }

        // Clear existing comments to avoid duplicates
        DB::table('blog_comments')->truncate();

        // Get several blogs (limit to first 15 blogs to prevent slow database insertions)
        $blogs = Blog::orderBy('id', 'asc')->take(15)->get();
        if ($blogs->isEmpty()) {
            $this->command->warn('No blogs found to seed comments. Please run BlogSeeder first.');
            return;
        }

        // Get first user (typically Admin) to act as responder
        $adminUser = User::first();
        $adminId = $adminUser ? $adminUser->id : null;
        $adminName = $adminUser ? $adminUser->name : 'অ্যাডমিন';
        $adminEmail = $adminUser ? $adminUser->email : 'admin@hezbuttawheed.org';

        // Guest Commenters Data
        $guestCommenters = [
            ['name' => 'আব্দুর রহমান', 'email' => 'rahman.abdur@gmail.com'],
            ['name' => 'ফাতেমা ইয়াসমিন', 'email' => 'fatema.yasmin@yahoo.com'],
            ['name' => 'সাইফুল ইসলাম', 'email' => 'saiful.islam@hotmail.com'],
            ['name' => 'তানজিনা আক্তার', 'email' => 'tanjina.akter@outlook.com'],
            ['name' => 'আরিফুল হক', 'email' => 'ariful.haque@share.com'],
            ['name' => 'নাসরিন সুলতানা', 'email' => 'nasrin.sultana@edu.bd'],
            ['name' => 'ইমরান আহমেদ', 'email' => 'imran.ahmed@tech.com'],
            ['name' => 'তাসনিম জাহান', 'email' => 'tasnim.jahan@live.com'],
            ['name' => 'জাকির হোসেন', 'email' => 'zakir.hossain@corporation.com'],
            ['name' => 'শারমিন সুলতানা', 'email' => 'sharmin.sultana@gmail.com']
        ];

        // Comments content for Extremism/Peace related blogs
        $peaceComments = [
            [
                'comment' => 'ইসলাম যে শান্তির ধর্ম তা এই লিখাটিতে চমৎকারভাবে উপস্থাপন করা হয়েছে। মহানবী (সা.)-এর বিদায় হজের ভাষণেই পরমতসহিষ্ণুতার চূড়ান্ত বার্তা দেওয়া হয়েছিল। উগ্রবাদ কখনোই কোনো ধর্মের মূল শিক্ষা হতে পারে না।',
                'replies' => [
                    [
                        'is_admin' => true,
                        'comment' => 'একেবারে সঠিক বলেছেন। বিদায় হজের সেই ঐতিহাসিক বার্তা যদি আমরা হৃদয়ে ধারণ করতে পারতাম, তাহলে আজকের সমাজে এই অশান্তি ও হানাহানি থাকত না। আমাদের এই ইতিবাচক ও সঠিক বার্তা ছড়িয়ে দিতে হবে।'
                    ]
                ]
            ],
            [
                'comment' => 'বর্তমান তরুণ সমাজের কাছে ইসলামের এই মূল শিক্ষাটি পৌঁছানো খুব জরুরি। তারা যেন ইন্টারনেটে কোনো উগ্রপন্থী ভাবধারায় বিভ্রান্ত না হয়। ধন্যবাদ লেখককে এমন প্রাসঙ্গিক একটি বিষয় নিয়ে লেখার জন্য।',
                'replies' => [
                    [
                        'is_admin' => false,
                        'comment' => 'সহমত ভাই। আমাদের পারিবারিক ও সামাজিক পর্যায়ে এ বিষয়ে আলোচনা বাড়ানো উচিত যাতে সন্তানরা সঠিক পথ বেছে নেয়।'
                    ]
                ]
            ],
            [
                'comment' => 'খুবই সময়োপযোগী লেখা। কিন্তু কিভাবে আমরা সাধারণ মানুষের কাছে এই চরমপন্থার বিরুদ্ধে সচেতনতা তৈরি করতে পারি? কোনো বাস্তবসম্মত পদক্ষেপ কি নেওয়া হচ্ছে?',
                'replies' => [
                    [
                        'is_admin' => true,
                        'comment' => 'ধন্যবাদ আপনার গুরুত্বপূর্ণ প্রশ্নের জন্য। হেজবুত তওহীদ দেশব্যাপী বিভিন্ন সেমিনার, লিফলেট বিতরণ ও সামাজিক যোগাযোগ মাধ্যমে এই উদার ও মানবিক ইসলামের বার্তা ছড়িয়ে দেওয়ার সার্বক্ষণিক চেষ্টা করছে। আপনিও আপনার অবস্থান থেকে ভূমিকা রাখতে পারেন।'
                    ]
                ]
            ],
            [
                'comment' => 'চমৎকার তথ্যবহুল পোস্ট। উগ্রবাদ রুখতে হলে শিক্ষার পাশাপাশি ধর্মীয় সঠিক জ্ঞান দেওয়া অপরিহার্য। পাঠ্যপুস্তকে এই মানবিক গুণগুলো অন্তর্ভুক্ত করা উচিত।',
                'is_approved' => false // Keep one pending for test
            ]
        ];

        // Comments content for Unity/Society related blogs
        $unityComments = [
            [
                'comment' => 'ধর্মীয় সম্প্রীতি ছাড়া একটি দেশের টেকসই উন্নয়ন সম্ভব নয়। আমরা সবাই সবার আগে মানুষ, এই চেতনা প্রতিটি নাগরিকের হৃদয়ে জাগ্রত হওয়া উচিত। হেজবুত তওহীদের এই মানবিক উদ্যোগ প্রশংসনীয়।',
                'replies' => [
                    [
                        'is_admin' => true,
                        'comment' => 'ধন্যবাদ আপনার মূল্যবান মতামতের জন্য। মানবতার কল্যাণই আমাদের মূল লক্ষ্য এবং এই কাজ আমরা সবাই ঐক্যবদ্ধভাবে করে যাব।'
                    ]
                ]
            ],
            [
                'comment' => 'অসাম্প্রদায়িক সমাজ গঠনে তরুণ প্রজন্মের ভূমিকা কী হওয়া উচিত বলে আপনি মনে করেন? আমাদের তরুণদের জন্য বিশেষ কোনো প্রোগ্রাম আছে কি?',
                'replies' => [
                    [
                        'is_admin' => false,
                        'comment' => 'তরুণরাই পারে কুসংস্কার ও সাম্প্রদায়িকতার শিকল ভাঙতে। তাদের সোশ্যাল মিডিয়ায় হিংসা ছড়ানোর বিরুদ্ধে কথা বলা উচিত এবং সম্প্রীতির বার্তা দিতে হবে।'
                    ]
                ]
            ],
            [
                'comment' => 'মানবতার ঐক্যই বর্তমান বিশ্বের সব সংকটের একমাত্র সমাধান। আজ মানুষে মানুষে যে জাতিগত ও ধর্মীয় বিভেদ, তা দূর করতে অসাম্প্রদায়িক মনোভাব গড়ে তুলতে হবে। চমৎকার লিখেছেন লেখক।',
                'replies' => [
                    [
                        'is_admin' => true,
                        'comment' => 'জ্বি, আমাদের পারস্পরিক ভালোবাসা ও সহমর্মিতাই পারবে এই বিভেদ দূর করতে। আপনার ইতিবাচক দৃষ্টিভঙ্গির জন্য কৃতজ্ঞতা।'
                    ]
                ]
            ],
            [
                'comment' => 'খুবই সুন্দর আলোচনা। আমাদের দেশের অসাম্প্রদায়িক চেতনার ইতিহাস অনেক পুরোনো, আমাদের তা যে কোনো মূল্যে রক্ষা করতে হবে। বর্তমান সময়ে এটি বড় চ্যালেঞ্জ।',
                'is_approved' => true
            ],
            [
                'comment' => 'আমাদের স্থানীয় এলাকাগুলোতে এই ঐক্যের বার্তা নিয়ে কি কোনো টিম কাজ করছে? আমরা কিভাবে এতে সরাসরি যুক্ত হতে পারি?',
                'is_approved' => false // Pending comment
            ]
        ];

        // Default fallback comments for other blogs
        $generalComments = [
            [
                'comment' => 'নিবন্ধটি পড়ে অনেক কিছু জানতে পারলাম। আপনার লেখার স্টাইল ও যুক্তি উপস্থাপন চমৎকার ছিল। পরবর্তী পোস্টের অপেক্ষায় রইলাম।',
                'replies' => [
                    [
                        'is_admin' => true,
                        'comment' => 'আমাদের সাথেই থাকুন। আপনাদের অনুপ্রেরণাই আমাদের নতুন নতুন বিষয় নিয়ে লিখতে উৎসাহিত করে।'
                    ]
                ]
            ],
            [
                'comment' => 'একটি যুগোপযোগী বিষয় আলোচনার জন্য ধন্যবাদ। সমাজের প্রতিটি স্তরে এমন সচেতনতা ছড়িয়ে দেওয়া দরকার।'
            ]
        ];

        $faker = \Faker\Factory::create('bn_BD');

        foreach ($blogs as $blog) {
            // Select comment set based on slug keywords
            if (str_contains($blog->slug, 'extremism') || str_contains($blog->slug, 'islam')) {
                $commentsToSeed = $peaceComments;
            } elseif (str_contains($blog->slug, 'unity') || str_contains($blog->slug, 'society') || str_contains($blog->slug, 'reform')) {
                $commentsToSeed = $unityComments;
            } else {
                $commentsToSeed = $generalComments;
            }

            foreach ($commentsToSeed as $cData) {
                // Pick a random guest commenter
                $commenter = $guestCommenters[array_rand($guestCommenters)];

                $comment = BlogComment::create([
                    'blog_id' => $blog->id,
                    'user_id' => null, // Guest
                    'name' => $commenter['name'],
                    'email' => $commenter['email'],
                    'comment' => $cData['comment'],
                    'parent_id' => null,
                    'is_approved' => $cData['is_approved'] ?? true,
                    'ip_address' => $faker->ipv4,
                    'user_agent' => $faker->userAgent,
                    'created_at' => now()->subDays(rand(1, 10))->subHours(rand(1, 23)),
                    'updated_at' => now()
                ]);

                // Seed replies if any
                if (isset($cData['replies']) && !empty($cData['replies'])) {
                    foreach ($cData['replies'] as $rData) {
                        if ($rData['is_admin']) {
                            // Seeded as Admin User reply
                            BlogComment::create([
                                'blog_id' => $blog->id,
                                'user_id' => $adminId,
                                'name' => $adminName,
                                'email' => $adminEmail,
                                'comment' => $rData['comment'],
                                'parent_id' => $comment->id,
                                'is_approved' => true,
                                'ip_address' => $faker->ipv4,
                                'user_agent' => $faker->userAgent,
                                'created_at' => $comment->created_at->addMinutes(rand(10, 180)),
                                'updated_at' => now()
                            ]);
                        } else {
                            // Seeded as another guest commenter reply
                            $replyCommenter = $guestCommenters[array_rand($guestCommenters)];
                            while ($replyCommenter['email'] === $commenter['email']) {
                                $replyCommenter = $guestCommenters[array_rand($guestCommenters)];
                            }

                            BlogComment::create([
                                'blog_id' => $blog->id,
                                'user_id' => null,
                                'name' => $replyCommenter['name'],
                                'email' => $replyCommenter['email'],
                                'comment' => $rData['comment'],
                                'parent_id' => $comment->id,
                                'is_approved' => true,
                                'ip_address' => $faker->ipv4,
                                'user_agent' => $faker->userAgent,
                                'created_at' => $comment->created_at->addMinutes(rand(10, 180)),
                                'updated_at' => now()
                            ]);
                        }
                    }
                }
            }
        }

        $this->command->info('Professional blog comments seeded successfully!');
    }
}
