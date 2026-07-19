<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsletterSubscriber;
use App\Models\ContactMessage;
use App\Models\JoinRequest;
use App\Models\NewsletterTemplate;
use App\Models\NewsletterCampaign;
use App\Models\Suggestion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CommunicationDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $creator = User::where('role', 'super_admin')->first() ?? User::first();
        $creatorId = $creator ? $creator->id : 1;

        // ----------------------------------------------------
        // 1. Newsletter Subscribers
        // ----------------------------------------------------
        $subscribersData = [
            ['name' => 'আব্দুর রহমান', 'email' => 'abdur.rahman@gmail.com', 'is_active' => true, 'verified_offset' => 10],
            ['name' => 'ফাতেমা আক্তার', 'email' => 'fatema.akter@yahoo.com', 'is_active' => true, 'verified_offset' => 5],
            ['name' => 'নজরুল ইসলাম', 'email' => 'nazrul.islam@outlook.com', 'is_active' => true, 'verified_offset' => 15],
            ['name' => 'তাসনিম জাহান', 'email' => 'tasnim.jahan@hotmail.com', 'is_active' => false, 'verified_offset' => null],
            ['name' => 'মোঃ রফিকুল ইসলাম', 'email' => 'rafiqul.islam@gmail.com', 'is_active' => true, 'verified_offset' => 20],
            ['name' => 'শামীমা নাসরিন', 'email' => 'shamima.nasrin@gmail.com', 'is_active' => true, 'verified_offset' => null],
            ['name' => 'আরিফুর রহমান', 'email' => 'arifur.rahman@yahoo.com', 'is_active' => true, 'verified_offset' => 2],
            ['name' => 'সাদিয়া আফরিন', 'email' => 'sadia.afrin@outlook.com', 'is_active' => false, 'verified_offset' => null],
            ['name' => 'মহিউদ্দিন আহমেদ', 'email' => 'mohiuddin.ahmed@gmail.com', 'is_active' => true, 'verified_offset' => 25],
            ['name' => 'নুসরাত জাহান', 'email' => 'nusrat.jahan@gmail.com', 'is_active' => true, 'verified_offset' => 1],
        ];

        foreach ($subscribersData as $sub) {
            NewsletterSubscriber::updateOrCreate(
                ['email' => $sub['email']],
                [
                    'name' => $sub['name'],
                    'verification_token' => Str::random(32),
                    'verified_at' => $sub['verified_offset'] ? Carbon::now()->subDays($sub['verified_offset']) : null,
                    'is_active' => $sub['is_active'],
                    'ip_address' => '192.168.1.' . rand(1, 254),
                    'source' => 'website_footer',
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                ]
            );
        }

        // ----------------------------------------------------
        // 2. Contact Messages
        // ----------------------------------------------------
        $contactMessages = [
            [
                'name' => 'মোঃ জহিরুল হক',
                'email' => 'zahirul.haque@gmail.com',
                'phone' => '01711223344',
                'subject' => 'শাখা কার্যালয়ের ঠিকানা জানতে চাই',
                'message' => 'আসসালামু আলাইকুম। আমি রাজশাহী জেলায় থাকি। আপনাদের রাজশাহী শাখার কার্যালয় এবং যোগাযোগ করার মোবাইল নম্বরটি প্রয়োজন। দয়া করে বিস্তারিত জানাবেন। ধন্যবাদ।',
                'status' => 'unread',
            ],
            [
                'name' => 'মোসাম্মৎ রেহানা আক্তার',
                'email' => 'rehana.akter@yahoo.com',
                'phone' => '01822334455',
                'subject' => 'নতুন বই অর্ডার সংক্রান্ত',
                'message' => 'আপনাদের নতুন প্রকাশিত "হেযবুত তওহীদের আদর্শিক ও ধর্মীয় দৃষ্টিভঙ্গি" বইটি পেতে চাই। হোম ডেলিভারির জন্য কীভাবে অর্ডার করতে হবে বিস্তারিত জানাবেন।',
                'status' => 'read',
            ],
            [
                'name' => 'এইচ এম মশিউর রহমান',
                'email' => 'moshiur.rahman@outlook.com',
                'phone' => '01933445566',
                'subject' => 'যোগাযোগ ও তথ্য প্রাপ্তি',
                'message' => 'আমি আপনাদের প্রকাশিত প্রবন্ধ এবং মাসব্যাপী কার্যক্রমের খতিয়ান ও প্রেস রিলিজ নিয়মিত আমার নিউজ পোর্টালে প্রকাশ করতে আগ্রহী। মিডিয়া সেলের কারো সাথে যোগাযোগের ব্যবস্থা করা সম্ভব কি?',
                'status' => 'replied',
                'reply_message' => 'ধন্যবাদ আমাদের সাথে যোগাযোগের জন্য। আমাদের মিডিয়া বিভাগের প্রধানের সাথে যোগাযোগ করতে পারেন। ইমেইল: media@hezbuttawheed.org, ফোন: ০১৭১১০০০৫২৫।',
            ],
            [
                'name' => 'ফারজানা ইয়াসমিন',
                'email' => 'farzana.yasmin@gmail.com',
                'phone' => '01544556677',
                'subject' => 'অনলাইন ডিজিটাল প্রকাশনা',
                'message' => 'আপনাদের অনলাইন লাইব্রেরিতে পিডিএফ রিডারটি খুবই চমৎকার হয়েছে। আমি মোবাইল থেকে আপনাদের সবগুলো বই পড়তে পারছি। তবে পিডিএফ ডাউনলোড করার কোনো সুযোগ আছে কি না জানালে উপকৃত হতাম।',
                'status' => 'unread',
            ],
            [
                'name' => 'আবুল কালাম আজাদ',
                'email' => 'azad.abdul@yahoo.com',
                'phone' => '01655667788',
                'subject' => 'মাসিক বুলেটিন প্রাপ্তি',
                'message' => 'আমি নিয়মিত ইমেইল নিউজলেটার পেতে চাই। এর জন্য আমার ইমেইলটি সাবস্ক্রাইব করেছি কিন্তু ভেরিফিকেশন লিংক পাইনি। দয়া করে চেক করবেন।',
                'status' => 'replied',
                'reply_message' => 'আপনার ইমেইল ভেরিফিকেশনটি সফলভাবে করে দেওয়া হয়েছে। এখন থেকে আপনি আমাদের সাপ্তাহিক এবং মাসিক বুলেটিন নিয়মিত ইমেইলে পেয়ে যাবেন। ধন্যবাদ।',
            ]
        ];

        foreach ($contactMessages as $msg) {
            ContactMessage::create([
                'name' => $msg['name'],
                'email' => $msg['email'],
                'phone' => $msg['phone'],
                'subject' => $msg['subject'],
                'message' => $msg['message'],
                'status' => $msg['status'],
                'ip_address' => '192.168.2.' . rand(1, 254),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'replied_by' => isset($msg['reply_message']) ? $creatorId : null,
                'reply_message' => $msg['reply_message'] ?? null,
                'replied_at' => isset($msg['reply_message']) ? Carbon::now()->subDays(1) : null,
                'created_at' => Carbon::now()->subDays(rand(1, 10)),
            ]);
        }

        // ----------------------------------------------------
        // 3. Join Requests
        // ----------------------------------------------------
        $joinRequests = [
            [
                'membership_type' => 'primary',
                'name' => 'তারিকুল ইসলাম',
                'father_husband' => 'মো রফিকুল ইসলাম',
                'age' => '২৮',
                'occupation' => 'বেসরকারি চাকরি',
                'education' => 'স্নাতকোত্তর (এমবিএ)',
                'phone' => '01712345678',
                'present_address' => 'মিরপুর-১০, ঢাকা',
                'permanent_address' => 'সোনাইমুড়ী, নোয়াখালী',
                'how_knew' => 'ফেসবুক ও সোশ্যাল মিডিয়া',
                'experience' => 'সামাজিক কার্যক্রমে ২ বছরের অভিজ্ঞতা আছে।',
                'status' => 'unread',
            ],
            [
                'membership_type' => 'pledge',
                'name' => 'সাইদা ফেরদৌস',
                'father_husband' => 'আবুল খায়ের',
                'age' => '২২',
                'occupation' => 'শিক্ষার্থী',
                'education' => 'অনার্স ৩য় বর্ষ',
                'phone' => '01812345678',
                'present_address' => 'মতিহার, রাজশাহী',
                'permanent_address' => 'বাঘা, রাজশাহী',
                'how_knew' => 'শাখা কার্যালয়ের মাধ্যমে',
                'experience' => 'কোনো পূর্ব অভিজ্ঞতা নেই।',
                'status' => 'read',
            ],
            [
                'membership_type' => 'primary',
                'name' => 'মোঃ মামুনুর রশিদ',
                'father_husband' => 'মোঃ আশরাফ আলী',
                'age' => '৩৫',
                'occupation' => 'ব্যবসায়ী',
                'education' => 'এইচএসসি',
                'phone' => '01912345678',
                'present_address' => 'হালিশহর, চট্টগ্রাম',
                'permanent_address' => 'মিরসরাই, চট্টগ্রাম',
                'how_knew' => 'বই ও প্রকাশনা পড়ে',
                'experience' => 'আপনাদের একাধিক সেমিনারে অংশগ্রহণ করেছি।',
                'status' => 'approved',
            ],
            [
                'membership_type' => 'pledge',
                'name' => 'আফজাল হোসেন',
                'father_husband' => 'মৃত আবুল হাসেম',
                'age' => '৪০',
                'occupation' => 'কৃষিজীবী',
                'education' => 'অষ্টম শ্রেণী',
                'phone' => '01512345678',
                'present_address' => 'রামগঞ্জ, লক্ষ্মীপুর',
                'permanent_address' => 'রামগঞ্জ, লক্ষ্মীপুর',
                'how_knew' => 'জনসাধারণের মধ্যে লিফলেট বিতরণ',
                'experience' => 'না, নেই।',
                'status' => 'rejected',
            ]
        ];

        foreach ($joinRequests as $join) {
            JoinRequest::create([
                'membership_type' => $join['membership_type'],
                'name' => $join['name'],
                'join_date' => Carbon::now()->subDays(rand(1, 10))->format('Y-m-d'),
                'father_husband' => $join['father_husband'],
                'age' => $join['age'],
                'occupation' => $join['occupation'],
                'education' => $join['education'],
                'phone' => $join['phone'],
                'present_address' => $join['present_address'],
                'permanent_address' => $join['permanent_address'],
                'how_knew' => $join['how_knew'],
                'experience' => $join['experience'],
                'status' => $join['status'],
                'ip_address' => '192.168.3.' . rand(1, 254),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subDays(rand(1, 15)),
            ]);
        }

        // ----------------------------------------------------
        // 4. Newsletter Templates
        // ----------------------------------------------------
        $templates = [
            [
                'name' => 'ঈদ মোবারক শুভেচ্ছা বার্তা',
                'subject' => 'পবিত্র ঈদুল ফিতরের শুভেচ্ছা ও অভিনন্দন - হেযবুত তওহীদ',
                'content' => '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
                                <div style="text-align: center; margin-bottom: 20px;">
                                    <h2 style="color: #006A4E;">ঈদ মোবারক!</h2>
                                </div>
                                <p>প্রিয় সুধী,</p>
                                <p>পবিত্র ঈদুল ফিতরের এই আনন্দঘন মুহূর্তে হেযবুত তওহীদের পক্ষ থেকে আপনাকে ও আপনার পরিবারকে জানাই আন্তরিক শুভেচ্ছা ও অভিনন্দন।</p>
                                <p>ঈদের খুশি সবার জীবনে শান্তি, সম্প্রীতি ও ভ্রাতৃত্বের বার্তা বয়ে আনুক। দেশের শান্তি রক্ষায় এবং সত্য প্রতিষ্ঠায় আমাদের প্রচেষ্টা অব্যাহত থাকুক।</p>
                                <div style="text-align: center; margin: 30px 0;">
                                    <a href="https://hezbuttawheed.org" style="background-color: #006A4E; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">আমাদের ওয়েবসাইট ভিজিট করুন</a>
                                </div>
                                <hr style="border: 0; border-top: 1px solid #eee;">
                                <p style="font-size: 12px; color: #777; text-align: center;">আপনি এই ইমেইলটি পেয়েছেন কারণ আপনি হেযবুত তওহীদের নিউজলেটারে সাবস্ক্রাইব করেছেন।</p>
                              </div>',
                'thumbnail' => 'https://placehold.co/400x400/006a4e/ffffff?text=Eid+Mubarak',
                'is_default' => false,
                'status' => true,
            ],
            [
                'name' => 'নতুন প্রকাশনা নোটিশ',
                'subject' => 'নতুন প্রকাশনা: হেযবুত তওহীদের আদর্শিক ও ধর্মীয় দৃষ্টিভঙ্গি',
                'content' => '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
                                <div style="text-align: center; margin-bottom: 20px;">
                                    <h2 style="color: #2F54EB;">নতুন বই প্রকাশনা</h2>
                                </div>
                                <p>প্রিয় সুধী,</p>
                                <p>অত্যন্ত আনন্দের সাথে জানাচ্ছি যে, হেযবুত তওহীদের লাইব্রেরিতে যুক্ত হয়েছে নতুন বই <strong>"হেযবুত তওহীদের আদর্শিক ও ধর্মীয় দৃষ্টিভঙ্গি"</strong>।</p>
                                <p>বইটিতে হেযবুত তওহীদের মূল লক্ষ্য ও উদ্দেশ্য ধর্মীয় এবং আদর্শিকভাবে আলোচনা করা হয়েছে। আপনি এখনই এটি আমাদের অনলাইন লাইব্রেরি থেকে বিনামূল্যে পড়তে পারবেন।</p>
                                <div style="text-align: center; margin: 30px 0;">
                                    <a href="https://hezbuttawheed.org/books" style="background-color: #2F54EB; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">বইটি পড়ুন</a>
                                </div>
                                <hr style="border: 0; border-top: 1px solid #eee;">
                                <p style="font-size: 12px; color: #777; text-align: center;">হেযবুত তওহীদ প্রকাশনা বিভাগ।</p>
                              </div>',
                'thumbnail' => 'https://placehold.co/400x400/2f54eb/ffffff?text=New+Book',
                'is_default' => true,
                'status' => true,
            ],
            [
                'name' => 'মাসিক তথ্য বুলেটিন',
                'subject' => 'মাসিক বুলেটিন - হেযবুত তওহীদ কার্যক্রমের খতিয়ান',
                'content' => '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
                                <div style="text-align: center; margin-bottom: 20px;">
                                    <h2 style="color: #6c757d;">মাসিক নিউজলেটর বুলেটিন</h2>
                                </div>
                                <p>প্রিয় পাঠক,</p>
                                <p>বিগত মাসে হেযবুত তওহীদের দেশব্যাপী সামাজিক কার্যক্রম, মতবিনিময় সভা এবং সেমিনারগুলোর হাইলাইটস এবং প্রেস রিলিজ নিয়ে আমাদের এই মাসিক বুলেটিন।</p>
                                <ul>
                                    <li>ঢাকা প্রেসক্লাবে মতবিনিময় সভা অনুষ্ঠিত</li>
                                    <li>সোনাইমুড়ীতে দুস্থদের মধ্যে সাহায্য বিতরণ</li>
                                    <li>অনলাইন প্রকাশনা লাইব্রেরি চালুর আপডেট</li>
                                </ul>
                                <p>আমাদের কার্যক্রম সম্পর্কে নিয়মিত আপডেট পেতে ওয়েবসাইটের নিউজ ব্লগে চোখ রাখুন।</p>
                                <hr style="border: 0; border-top: 1px solid #eee;">
                                <p style="font-size: 12px; color: #777; text-align: center;">হেযবুত তওহীদ প্রচার ও প্রকাশনা সেল।</p>
                              </div>',
                'thumbnail' => 'https://placehold.co/400x400/6c757d/ffffff?text=Monthly+Bulletin',
                'is_default' => false,
                'status' => true,
            ]
        ];

        foreach ($templates as $tmpl) {
            NewsletterTemplate::create($tmpl);
        }

        // ----------------------------------------------------
        // 5. Email Campaigns
        // ----------------------------------------------------
        $campaigns = [
            [
                'subject' => 'নতুন বছর ২০২৬ এর বিশেষ শুভেচ্ছা বার্তা',
                'title' => 'নতুন বছরের শুভেচ্ছা ক্যাম্পেইন',
                'content' => 'নতুন বছরে সবার সুখ ও সমৃদ্ধি কামনা করছি। হেযবুত তওহীদ নতুন উদ্দীপনায় সত্য প্রচার কাজ অব্যাহত রাখবে।',
                'template' => 'ঈদ মোবারক শুভেচ্ছা বার্তা',
                'status' => 'sent',
                'recipient_type' => 'all',
                'total_recipients' => 500,
                'sent_count' => 500,
                'opened_count' => 320,
                'clicked_count' => 110,
                'sent_at' => Carbon::now()->subDays(15),
            ],
            [
                'subject' => 'নতুন আদর্শিক বই পড়তে ভিজিট করুন',
                'title' => 'নতুন বই প্রমোশন ক্যাম্পেইন',
                'content' => 'আমাদের নতুন বইটি এখন সম্পূর্ণ বিনামূল্যে পড়তে পারবেন আমাদের সাইটে। বিস্তারিত জানতে ক্লিক করুন।',
                'template' => 'নতুন প্রকাশনা নোটিশ',
                'status' => 'draft',
                'recipient_type' => 'active_only',
                'total_recipients' => 250,
                'sent_count' => 0,
                'opened_count' => 0,
                'clicked_count' => 0,
                'sent_at' => null,
            ],
            [
                'subject' => 'গত মাসের কার্যক্রমের মাসিক সারসংক্ষেপ',
                'title' => 'জানুয়ারি ২০২৬ কার্যক্রমের বুলেটিন',
                'content' => 'বিগত জানুয়ারি মাসের সকল শাখার কার্যক্রম এবং প্রেস বিজ্ঞপ্তিগুলো একসাথে দেখুন।',
                'template' => 'মাসিক তথ্য বুলেটিন',
                'status' => 'scheduled',
                'recipient_type' => 'all',
                'total_recipients' => 800,
                'sent_count' => 0,
                'opened_count' => 0,
                'clicked_count' => 0,
                'scheduled_at' => Carbon::now()->addDays(2),
                'sent_at' => null,
            ]
        ];

        foreach ($campaigns as $camp) {
            NewsletterCampaign::create([
                'subject' => $camp['subject'],
                'title' => $camp['title'],
                'content' => $camp['content'],
                'template' => $camp['template'],
                'status' => $camp['status'],
                'recipient_type' => $camp['recipient_type'],
                'total_recipients' => $camp['total_recipients'],
                'sent_count' => $camp['sent_count'],
                'opened_count' => $camp['opened_count'],
                'clicked_count' => $camp['clicked_count'],
                'scheduled_at' => $camp['scheduled_at'] ?? null,
                'sent_at' => $camp['sent_at'] ?? null,
                'created_by' => $creatorId,
                'created_at' => Carbon::now()->subDays(20),
            ]);
        }

        // ----------------------------------------------------
        // 6. Suggestions & Feedback
        // ----------------------------------------------------
        $suggestions = [
            [
                'name' => 'ড. এ কে এম রফিকুল আলম',
                'contact' => 'rafiq.alam@du.ac.bd',
                'subject' => 'ধর্মীয় গোঁড়ামি দূরীকরণে প্রকাশনা বাড়ানো',
                'message' => 'আপনাদের বইগুলোতে ইসলামের মূল শান্তিপ্রিয় শিক্ষা খুব সুন্দরভাবে ফুটিয়ে তোলা হয়েছে। বর্তমান সমাজে ধর্মীয় গোঁড়ামি ও চরমপন্থা দূর করতে এ ধরনের বইগুলোর প্রচার বাড়ানো উচিত এবং স্কুল-কলেজ পর্যায়ে শিক্ষার্থীদের মাঝে বিতরণের ব্যবস্থা করলে আরও ভালো হতো।',
                'status' => 'reviewed',
            ],
            [
                'name' => 'মোসাঃ তানজিলা রহমান',
                'contact' => '01755667788',
                'subject' => 'ওয়েবসাইট কন্টেন্ট সর্টিং ও ফিল্টারিং সুবিধা',
                'message' => 'নাগরিক মতামত পেজের ডিজাইনটি খুব ভালো হয়েছে, তবে প্রকাশনাগুলোর মধ্যে কোনগুলো নতুন আর কোনগুলো বেশি জনপ্রিয় তা সহজে ফিল্টার করার অপশন থাকলে ইউজারদের জন্য আরও সুবিধা হতো। আশা করি এই বিষয়টি বিবেচনা করবেন।',
                'status' => 'pending',
            ],
            [
                'name' => 'আব্দুল্লাহ আল মামুন',
                'contact' => 'mamun.al@gmail.com',
                'subject' => 'শাখা কার্যালয়ের মাধ্যমে সমাজসেবামূলক উদ্যোগ',
                'message' => 'প্রতিটি জেলা শহরের শাখা কার্যালয়গুলোতে একটি করে ফ্রি ডেন্টাল বা সাধারণ মেডিকেল ক্যাম্প করা যেতে পারে। এতে করে মানুষের সাথে আপনাদের সংযোগ বাড়বে এবং সমাজ গঠনে সাহায্য হবে।',
                'status' => 'reviewed',
            ]
        ];

        foreach ($suggestions as $sug) {
            Suggestion::create([
                'name' => $sug['name'],
                'contact' => $sug['contact'],
                'subject' => $sug['subject'],
                'message' => $sug['message'],
                'status' => $sug['status'],
                'ip_address' => '192.168.4.' . rand(1, 254),
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
                'created_at' => Carbon::now()->subDays(rand(1, 12)),
            ]);
        }
    }
}
