<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>টেস্ট ইমেইল</title>
</head>
<body style="font-family: 'Baloo Da 2', Arial, sans-serif; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; background: #f8f9fa;">
        <div style="background: #007bff; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
            <h2 style="margin: 0;">টেস্ট ইমেইল</h2>
        </div>

        <div style="background: white; padding: 30px; border-radius: 0 0 8px 8px;">
            <p>প্রিয় ব্যবহারকারী,</p>
            <p>এটি একটি টেস্ট ইমেইল। আপনার মেইল সেটিংস সঠিকভাবে কাজ করছে।</p>

            <div style="background: #e9ecef; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <p style="margin: 5px 0;"><strong>মেইলবক্স:</strong> {{ ucfirst($mailbox) }}</p>
                <p style="margin: 5px 0;"><strong>পাঠানোর সময়:</strong> {{ $date }}</p>
                <p style="margin: 5px 0;"><strong>অ্যাপ্লিকেশন:</strong> {{ config('app.name') }}</p>
                <p style="margin: 5px 0;"><strong>URL:</strong> {{ config('app.url') }}</p>
            </div>

            <p>আপনার ব্যবসার শুভ কামনায়,</p>
            <p><strong>{{ config('app.name') }} টিম</strong></p>
        </div>

        <div style="text-align: center; padding: 15px; font-size: 12px; color: #6c757d;">
            <p>© {{ date('Y') }} {{ config('app.name') }} - সমস্ত অধিকার সংরক্ষিত।</p>
            <p>এই ইমেইলটি স্বয়ংক্রিয়ভাবে পাঠানো হয়েছে। দয়া করে এটির উত্তর দেবেন না।</p>
        </div>
    </div>
</body>
</html>


