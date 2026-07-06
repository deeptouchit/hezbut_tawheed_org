<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        TeamMember::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $teamMembers = [
            // ============================================
            // Management Team
            // ============================================
            [
                'name' => 'মোঃ আব্দুল করিম',
                'designation' => 'প্রধান নির্বাহী কর্মকর্তা (CEO)',
                'bio' => '১৫+ বছরের অভিজ্ঞতা সম্পন্ন একজন দক্ষ উদ্যোক্তা। ডিজিটাল ট্রান্সফরমেশন এবং স্ট্রাটেজিক প্ল্যানিং এ বিশেষজ্ঞ।',
                'email' => 'ceo@company.com',
                'phone' => '+8801712345678',
                'department' => 'Management',
                'experience' => '15+',
                'education' => 'MBA (Harvard Business School)',
                'skills' => 'লিডারশিপ, স্ট্রাটেজি, ডিজিটাল ট্রান্সফরমেশন, বিজনেস ডেভেলপমেন্ট',
                'social_links' => json_encode([
                    'facebook' => 'https://facebook.com/ceo',
                    'linkedin' => 'https://linkedin.com/in/ceo',
                    'twitter' => 'https://twitter.com/ceo',
                ]),
                'meta_title' => 'মোঃ আব্দুল করিম - প্রধান নির্বাহী কর্মকর্তা',
                'meta_description' => '১৫+ বছরের অভিজ্ঞতা সম্পন্ন CEO। ডিজিটাল ট্রান্সফরমেশন এবং স্ট্রাটেজিক প্ল্যানিং এ বিশেষজ্ঞ।',
                'meta_keywords' => 'CEO, ব্যবস্থাপনা, নেতৃত্ব, ডিজিটাল ট্রান্সফরমেশন',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'মোছা. ফাতেমা খাতুন',
                'designation' => 'প্রধান কার্যনির্বাহী কর্মকর্তা (COO)',
                'bio' => '১২+ বছরের অভিজ্ঞতা সম্পন্ন একজন পেশাদার। অপারেশনাল এক্সিলেন্স এবং প্রসেস ইমপ্রুভমেন্ট এ বিশেষজ্ঞ।',
                'email' => 'coo@company.com',
                'phone' => '+8801712345679',
                'department' => 'Management',
                'experience' => '12+',
                'education' => 'MSc (Operations Management)',
                'skills' => 'অপারেশন ম্যানেজমেন্ট, প্রসেস ইমপ্রুভমেন্ট, টিম লিডারশিপ',
                'social_links' => json_encode([
                    'linkedin' => 'https://linkedin.com/in/coo',
                ]),
                'meta_title' => 'মোছা. ফাতেমা খাতুন - প্রধান কার্যনির্বাহী কর্মকর্তা',
                'meta_description' => '১২+ বছরের অভিজ্ঞতা সম্পন্ন COO। অপারেশনাল এক্সিলেন্স এবং প্রসেস ইমপ্রুভমেন্ট এ বিশেষজ্ঞ।',
                'meta_keywords' => 'COO, অপারেশন, ম্যানেজমেন্ট, প্রসেস',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'মোঃ রফিকুল ইসলাম',
                'designation' => 'প্রধান প্রযুক্তি কর্মকর্তা (CTO)',
                'bio' => '১০+ বছরের অভিজ্ঞতা সম্পন্ন একজন টেকনোলজি বিশেষজ্ঞ। AI, Machine Learning এবং Cloud Architecture এ বিশেষজ্ঞ।',
                'email' => 'cto@company.com',
                'phone' => '+8801712345680',
                'department' => 'Technology',
                'experience' => '10+',
                'education' => 'MS (Computer Science)',
                'skills' => 'AI, Machine Learning, Cloud Architecture, Python, Laravel, Vue.js',
                'social_links' => json_encode([
                    'facebook' => 'https://facebook.com/cto',
                    'linkedin' => 'https://linkedin.com/in/cto',
                    'github' => 'https://github.com/cto',
                ]),
                'meta_title' => 'মোঃ রফিকুল ইসলাম - প্রধান প্রযুক্তি কর্মকর্তা',
                'meta_description' => '১০+ বছরের অভিজ্ঞতা সম্পন্ন CTO। AI, Machine Learning এবং Cloud Architecture এ বিশেষজ্ঞ।',
                'meta_keywords' => 'CTO, প্রযুক্তি, AI, Machine Learning, Cloud',
                'is_active' => true,
                'sort_order' => 3,
            ],

            // ============================================
            // Development Team
            // ============================================
            [
                'name' => 'মোঃ সাইফুল ইসলাম',
                'designation' => 'সিনিয়র সফটওয়্যার ইঞ্জিনিয়ার',
                'bio' => '৮+ বছরের অভিজ্ঞতা সম্পন্ন একজন ফুল-স্ট্যাক ডেভেলপার। Laravel, Vue.js, এবং React এ বিশেষজ্ঞ।',
                'email' => 'saiful@company.com',
                'phone' => '+8801712345681',
                'department' => 'Development',
                'experience' => '8+',
                'education' => 'BSc (Computer Science)',
                'skills' => 'Laravel, Vue.js, React, PHP, JavaScript, MySQL',
                'social_links' => json_encode([
                    'facebook' => 'https://facebook.com/saiful',
                    'linkedin' => 'https://linkedin.com/in/saiful',
                    'github' => 'https://github.com/saiful',
                ]),
                'meta_title' => 'মোঃ সাইফুল ইসলাম - সিনিয়র সফটওয়্যার ইঞ্জিনিয়ার',
                'meta_description' => '৮+ বছরের অভিজ্ঞতা সম্পন্ন ফুল-স্ট্যাক ডেভেলপার। Laravel, Vue.js বিশেষজ্ঞ।',
                'meta_keywords' => 'Laravel, Vue.js, PHP, ডেভেলপার, সফটওয়্যার',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'মোছা. নাসরিন আক্তার',
                'designation' => 'সফটওয়্যার ইঞ্জিনিয়ার',
                'bio' => '৫+ বছরের অভিজ্ঞতা সম্পন্ন একজন মোবাইল অ্যাপ ডেভেলপার। Flutter এবং React Native এ বিশেষজ্ঞ।',
                'email' => 'nasrin@company.com',
                'phone' => '+8801712345682',
                'department' => 'Development',
                'experience' => '5+',
                'education' => 'BSc (Computer Science)',
                'skills' => 'Flutter, React Native, Dart, JavaScript, Mobile App Development',
                'social_links' => json_encode([
                    'linkedin' => 'https://linkedin.com/in/nasrin',
                ]),
                'meta_title' => 'মোছা. নাসরিন আক্তার - সফটওয়্যার ইঞ্জিনিয়ার',
                'meta_description' => '৫+ বছরের অভিজ্ঞতা সম্পন্ন মোবাইল অ্যাপ ডেভেলপার। Flutter এবং React Native বিশেষজ্ঞ।',
                'meta_keywords' => 'Flutter, React Native, মোবাইল অ্যাপ, ডেভেলপার',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'মোঃ শাহীন মিয়া',
                'designation' => 'ফ্রন্ট-এন্ড ডেভেলপার',
                'bio' => '৪+ বছরের অভিজ্ঞতা সম্পন্ন একজন UI/UX ডিজাইনার এবং ফ্রন্ট-এন্ড ডেভেলপার।',
                'email' => 'shaheen@company.com',
                'phone' => '+8801712345683',
                'department' => 'Development',
                'experience' => '4+',
                'education' => 'BSc (Computer Science)',
                'skills' => 'HTML, CSS, JavaScript, React, Vue.js, UI/UX Design',
                'social_links' => json_encode([
                    'facebook' => 'https://facebook.com/shaheen',
                    'linkedin' => 'https://linkedin.com/in/shaheen',
                ]),
                'meta_title' => 'মোঃ শাহীন মিয়া - ফ্রন্ট-এন্ড ডেভেলপার',
                'meta_description' => '৪+ বছরের অভিজ্ঞতা সম্পন্ন UI/UX ডিজাইনার এবং ফ্রন্ট-এন্ড ডেভেলপার।',
                'meta_keywords' => 'UI/UX, ফ্রন্ট-এন্ড, React, Vue.js, ডিজাইন',
                'is_active' => true,
                'sort_order' => 6,
            ],

            // ============================================
            // Marketing Team
            // ============================================
            [
                'name' => 'মোঃ কামাল হোসেন',
                'designation' => 'মার্কেটিং ম্যানেজার',
                'bio' => '৭+ বছরের অভিজ্ঞতা সম্পন্ন একজন ডিজিটাল মার্কেটার। SEO, SEM এবং সোশ্যাল মিডিয়া মার্কেটিং এ বিশেষজ্ঞ।',
                'email' => 'kamal@company.com',
                'phone' => '+8801712345684',
                'department' => 'Marketing',
                'experience' => '7+',
                'education' => 'MBA (Marketing)',
                'skills' => 'SEO, SEM, Social Media Marketing, Content Marketing, Analytics',
                'social_links' => json_encode([
                    'facebook' => 'https://facebook.com/kamal',
                    'linkedin' => 'https://linkedin.com/in/kamal',
                    'twitter' => 'https://twitter.com/kamal',
                ]),
                'meta_title' => 'মোঃ কামাল হোসেন - মার্কেটিং ম্যানেজার',
                'meta_description' => '৭+ বছরের অভিজ্ঞতা সম্পন্ন ডিজিটাল মার্কেটার। SEO, SEM বিশেষজ্ঞ।',
                'meta_keywords' => 'মার্কেটিং, SEO, SEM, ডিজিটাল মার্কেটিং, সোশ্যাল মিডিয়া',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'মোছা. তাসলিমা বেগম',
                'designation' => 'সোশ্যাল মিডিয়া স্পেশালিস্ট',
                'bio' => '৪+ বছরের অভিজ্ঞতা সম্পন্ন একজন ক্রিয়েটিভ কন্টেন্ট ক্রিয়েটর এবং সোশ্যাল মিডিয়া স্পেশালিস্ট।',
                'email' => 'taslima@company.com',
                'phone' => '+8801712345685',
                'department' => 'Marketing',
                'experience' => '4+',
                'education' => 'BA (Mass Communication)',
                'skills' => 'Content Creation, Social Media Management, Photography, Video Editing',
                'social_links' => json_encode([
                    'facebook' => 'https://facebook.com/taslima',
                    'instagram' => 'https://instagram.com/taslima',
                    'youtube' => 'https://youtube.com/taslima',
                ]),
                'meta_title' => 'মোছা. তাসলিমা বেগম - সোশ্যাল মিডিয়া স্পেশালিস্ট',
                'meta_description' => '৪+ বছরের অভিজ্ঞতা সম্পন্ন কন্টেন্ট ক্রিয়েটর এবং সোশ্যাল মিডিয়া স্পেশালিস্ট।',
                'meta_keywords' => 'সোশ্যাল মিডিয়া, কন্টেন্ট ক্রিয়েশন, ভিডিও এডিটিং, ফটোগ্রাফি',
                'is_active' => true,
                'sort_order' => 8,
            ],

            // ============================================
            // Design Team
            // ============================================
            [
                'name' => 'মোঃ আরিফ হোসেন',
                'designation' => 'সিনিয়র গ্রাফিক ডিজাইনার',
                'bio' => '৬+ বছরের অভিজ্ঞতা সম্পন্ন একজন ক্রিয়েটিভ গ্রাফিক ডিজাইনার। ব্র্যান্ডিং, UI/UX এবং অ্যানিমেশন এ বিশেষজ্ঞ।',
                'email' => 'arif@company.com',
                'phone' => '+8801712345686',
                'department' => 'Design',
                'experience' => '6+',
                'education' => 'BFA (Graphic Design)',
                'skills' => 'Adobe Photoshop, Illustrator, Figma, After Effects, Branding',
                'social_links' => json_encode([
                    'facebook' => 'https://facebook.com/arif',
                    'linkedin' => 'https://linkedin.com/in/arif',
                    'instagram' => 'https://instagram.com/arif',
                ]),
                'meta_title' => 'মোঃ আরিফ হোসেন - সিনিয়র গ্রাফিক ডিজাইনার',
                'meta_description' => '৬+ বছরের অভিজ্ঞতা সম্পন্ন ক্রিয়েটিভ গ্রাফিক ডিজাইনার। ব্র্যান্ডিং এবং UI/UX বিশেষজ্ঞ।',
                'meta_keywords' => 'গ্রাফিক ডিজাইন, UI/UX, ব্র্যান্ডিং, অ্যানিমেশন, Adobe',
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'মোছা. রুমা আক্তার',
                'designation' => 'UI/UX ডিজাইনার',
                'bio' => '৩+ বছরের অভিজ্ঞতা সম্পন্ন একজন UI/UX ডিজাইনার। ইউজার রিসার্চ এবং প্রোটোটাইপিং এ বিশেষজ্ঞ।',
                'email' => 'ruma@company.com',
                'phone' => '+8801712345687',
                'department' => 'Design',
                'experience' => '3+',
                'education' => 'BSc (Computer Science)',
                'skills' => 'Figma, Sketch, Adobe XD, User Research, Prototyping',
                'social_links' => json_encode([
                    'linkedin' => 'https://linkedin.com/in/ruma',
                ]),
                'meta_title' => 'মোছা. রুমা আক্তার - UI/UX ডিজাইনার',
                'meta_description' => '৩+ বছরের অভিজ্ঞতা সম্পন্ন UI/UX ডিজাইনার। ইউজার রিসার্চ এবং প্রোটোটাইপিং বিশেষজ্ঞ।',
                'meta_keywords' => 'UI/UX, Figma, Sketch, ইউজার রিসার্চ, প্রোটোটাইপিং',
                'is_active' => true,
                'sort_order' => 10,
            ],

            // ============================================
            // HR & Administration
            // ============================================
            [
                'name' => 'মোঃ জাকির হোসেন',
                'designation' => 'এইচআর ম্যানেজার',
                'bio' => '৮+ বছরের অভিজ্ঞতা সম্পন্ন একজন এইচআর প্রফেশনাল। রিক্রুটমেন্ট, ট্রেইনিং এবং এমপ্লয়ী রিলেশন এ বিশেষজ্ঞ।',
                'email' => 'zakir@company.com',
                'phone' => '+8801712345688',
                'department' => 'HR',
                'experience' => '8+',
                'education' => 'MBA (HRM)',
                'skills' => 'Recruitment, Training, Performance Management, Employee Relations',
                'social_links' => json_encode([
                    'linkedin' => 'https://linkedin.com/in/zakir',
                ]),
                'meta_title' => 'মোঃ জাকির হোসেন - এইচআর ম্যানেজার',
                'meta_description' => '৮+ বছরের অভিজ্ঞতা সম্পন্ন এইচআর প্রফেশনাল। রিক্রুটমেন্ট এবং ট্রেইনিং বিশেষজ্ঞ।',
                'meta_keywords' => 'HR, রিক্রুটমেন্ট, ট্রেইনিং, পারফরম্যান্স, এমপ্লয়ী',
                'is_active' => true,
                'sort_order' => 11,
            ],

            // ============================================
            // Customer Support
            // ============================================
            [
                'name' => 'মোছা. সালমা খাতুন',
                'designation' => 'কাস্টমার সাপোর্ট লিড',
                'bio' => '৫+ বছরের অভিজ্ঞতা সম্পন্ন একজন কাস্টমার সার্ভিস প্রফেশনাল। কাস্টমার স্যাটিসফ্যাকশন এবং টিম ম্যানেজমেন্ট এ বিশেষজ্ঞ।',
                'email' => 'salma@company.com',
                'phone' => '+8801712345689',
                'department' => 'Support',
                'experience' => '5+',
                'education' => 'BA (English)',
                'skills' => 'Customer Service, Team Management, Communication, Problem Solving',
                'social_links' => json_encode([
                    'facebook' => 'https://facebook.com/salma',
                    'linkedin' => 'https://linkedin.com/in/salma',
                ]),
                'meta_title' => 'মোছা. সালমা খাতুন - কাস্টমার সাপোর্ট লিড',
                'meta_description' => '৫+ বছরের অভিজ্ঞতা সম্পন্ন কাস্টমার সার্ভিস প্রফেশনাল।',
                'meta_keywords' => 'কাস্টমার সার্ভিস, সাপোর্ট, কমিউনিকেশন, টিম ম্যানেজমেন্ট',
                'is_active' => true,
                'sort_order' => 12,
            ],

            // ============================================
            // Sales Team
            // ============================================
            [
                'name' => 'মোঃ মহিউদ্দিন',
                'designation' => 'সেলস ম্যানেজার',
                'bio' => '১০+ বছরের অভিজ্ঞতা সম্পন্ন একজন সেলস প্রফেশনাল। B2B সেলস, নেগোশিয়েশন এবং ক্লায়েন্ট রিলেশন এ বিশেষজ্ঞ।',
                'email' => 'mohiuddin@company.com',
                'phone' => '+8801712345690',
                'department' => 'Sales',
                'experience' => '10+',
                'education' => 'MBA (Marketing)',
                'skills' => 'B2B Sales, Negotiation, Client Relations, Sales Strategy',
                'social_links' => json_encode([
                    'linkedin' => 'https://linkedin.com/in/mohiuddin',
                ]),
                'meta_title' => 'মোঃ মহিউদ্দিন - সেলস ম্যানেজার',
                'meta_description' => '১০+ বছরের অভিজ্ঞতা সম্পন্ন সেলস প্রফেশনাল। B2B সেলস এবং নেগোশিয়েশন বিশেষজ্ঞ।',
                'meta_keywords' => 'সেলস, B2B, নেগোশিয়েশন, ক্লায়েন্ট, সেলস স্ট্রাটেজি',
                'is_active' => true,
                'sort_order' => 13,
            ],
        ];

        // Insert team members
        foreach ($teamMembers as $member) {
            TeamMember::create($member);
        }

        // Output success message
        $this->command->info('✅ ' . count($teamMembers) . ' টি টিম মেম্বার সফলভাবে যোগ করা হয়েছে!');
    }
}