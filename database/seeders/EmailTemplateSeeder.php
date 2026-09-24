<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'welcome_email',
                'subject' => 'হেযবুত তওহীদে আপনাকে স্বাগতম!',
                'body' => '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
                            <h2>স্বাগতম, {{name}}!</h2>
                            <p>হেযবুত তওহীদে আপনার সফল নিবন্ধনের জন্য অভিনন্দন। আমরা সত্য ও সম্প্রীতির বার্তা প্রচারের মাধ্যমে একটি শান্তিপূর্ণ সমাজ গঠনে বিশ্বাসী।</p>
                            <p>আপনার অ্যাকাউন্ট লগইন করতে নিচের বাটনে ক্লিক করুন:</p>
                            <div style="text-align: center; margin: 20px 0;">
                                <a href="{{login_url}}" style="background-color: #006A4E; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">লগইন করুন</a>
                            </div>
                            <hr style="border: 0; border-top: 1px solid #eee;">
                            <p style="font-size: 12px; color: #777;">ধন্যবাদ,<br>হেযবুত তওহীদ টিম।</p>
                          </div>',
                'type' => 'auth',
                'is_active' => true,
            ],
            [
                'name' => 'password_reset',
                'subject' => 'পাসওয়ার্ড রিসেট করার অনুরোধ',
                'body' => '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
                            <h2>পাসওয়ার্ড রিসেট করুন</h2>
                            <p>আপনি সম্প্রতি আপনার অ্যাকাউন্টের পাসওয়ার্ড রিসেট করার জন্য অনুরোধ করেছেন। পাসওয়ার্ড পরিবর্তন করতে নিচের বাটনে ক্লিক করুন:</p>
                            <div style="text-align: center; margin: 20px 0;">
                                <a href="{{reset_url}}" style="background-color: #d32f2f; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">পাসওয়ার্ড রিসেট করুন</a>
                            </div>
                            <p>যদি আপনি এই অনুরোধ না করে থাকেন, তবে এই ইমেইলটি উপেক্ষা করুন।</p>
                            <hr style="border: 0; border-top: 1px solid #eee;">
                            <p style="font-size: 12px; color: #777;">ধন্যবাদ,<br>হেযবুত তওহীদ টিম।</p>
                          </div>',
                'type' => 'auth',
                'is_active' => true,
            ],
            [
                'name' => 'contact_reply',
                'subject' => 'আপনার জিজ্ঞাসার উত্তর - হেযবুত তওহীদ',
                'body' => '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
                            <h2>আসসালামু আলাইকুম, {{name}}!</h2>
                            <p>আমাদের যোগাযোগ পাতায় আপনার প্রেরিত বার্তার প্রেক্ষিতে জানাচ্ছি যে:</p>
                            <blockquote style="background: #f9f9f9; border-left: 5px solid #006A4E; padding: 10px 15px; margin: 20px 0;">
                                {{reply_message}}
                            </blockquote>
                            <p>আপনার কোনো অতিরিক্ত তথ্যের প্রয়োজন হলে নির্দ্বিধায় যোগাযোগ করতে পারেন।</p>
                            <hr style="border: 0; border-top: 1px solid #eee;">
                            <p style="font-size: 12px; color: #777;">ধন্যবাদ,<br>হেযবুত তওহীদ কাস্টমার রিলেশন বিভাগ।</p>
                          </div>',
                'type' => 'general',
                'is_active' => true,
            ]
        ];

        foreach ($templates as $tmpl) {
            EmailTemplate::updateOrCreate(
                ['name' => $tmpl['name']],
                $tmpl
            );
        }
    }
}
