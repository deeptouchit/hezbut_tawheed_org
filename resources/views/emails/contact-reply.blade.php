{{-- resources/views/emails/contact-reply.blade.php --}}

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>যোগাযোগের উত্তর</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #0d6efd;">
        <h2 style="color: #0d6efd;">আপনার প্রশ্নের উত্তর</h2>
        <p>প্রিয় <strong>{{ $message->name }}</strong>,</p>
        <p>আপনার পাঠানো বার্তার উত্তরে নিম্নলিখিত উত্তর প্রদান করা হলো:</p>

        <div style="background: #fff; padding: 15px; border-radius: 5px; margin: 15px 0; border: 1px solid #e9ecef;">
            <h4 style="margin-top: 0;">আপনার বার্তা:</h4>
            <p style="color: #6c757d;">{{ $message->message }}</p>
        </div>

        <div style="background: #e7f5ff; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 3px solid #0d6efd;">
            <h4 style="margin-top: 0; color: #0d6efd;">আমাদের উত্তর:</h4>
            <p>{{ $reply }}</p>
        </div>

        <p>যদি আপনার আরও কোনো প্রশ্ন থাকে, তাহলে নির্দ্বিধায় আমাদের সাথে যোগাযোগ করুন।</p>

        <p style="margin-top: 20px;">
            ধন্যবাদ,<br>
            <strong>{{ config('app.name') }}</strong>
        </p>

        <hr style="border: none; border-top: 1px solid #e9ecef; margin: 20px 0;">

        <p style="font-size: 12px; color: #6c757d;">
            এই ইমেইল স্বয়ংক্রিয়ভাবে পাঠানো হয়েছে। দয়া করে এই ইমেইলের উত্তর দেবেন না।
        </p>
    </div>
</body>
</html>
