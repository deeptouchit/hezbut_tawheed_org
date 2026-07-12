<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>যোগাযোগ ফর্ম বার্তা</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #1e1e2e;
            background: #f8fafc;
            padding: 0;
            margin: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #017e3d, #00A65A);
            padding: 30px 40px;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .header p {
            margin: 4px 0 0;
            opacity: 0.85;
            font-size: 14px;
        }
        .body {
            padding: 30px 40px;
        }
        .body h2 {
            font-size: 18px;
            color: #1e1e2e;
            margin: 0 0 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f1f5f9;
        }
        .message-box {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            margin: 16px 0;
        }
        .message-box p {
            margin: 0;
            color: #4b5563;
            line-height: 1.8;
            white-space: pre-wrap;
        }
        .detail-item {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .detail-item:last-child {
            border-bottom: none;
        }
        .detail-label {
            width: 120px;
            font-weight: 600;
            color: #1e1e2e;
            flex-shrink: 0;
        }
        .detail-value {
            color: #4b5563;
        }
        .footer {
            padding: 20px 40px;
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 13px;
        }
        .footer a {
            color: #017e3d;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            background: #017e3d;
            color: #ffffff;
            font-size: 12px;
            font-weight: 600;
            border-radius: 20px;
        }
        .reply-btn {
            display: inline-block;
            padding: 10px 24px;
            background: #017e3d;
            color: #ffffff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 12px;
        }
        .reply-btn:hover {
            background: #00632f;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <h1>📩 নতুন যোগাযোগ বার্তা</h1>
            <p>বগুড়া বাজার থেকে একটি নতুন বার্তা এসেছে</p>
        </div>

        {{-- Body --}}
        <div class="body">
            <h2>বার্তার বিবরণ</h2>

            <div class="detail-item">
                <span class="detail-label">📛 নাম</span>
                <span class="detail-value">{{ $name }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">📧 ইমেইল</span>
                <span class="detail-value">{{ $email }}</span>
            </div>
            @if($phone)
            <div class="detail-item">
                <span class="detail-label">📱 ফোন</span>
                <span class="detail-value">{{ $phone }}</span>
            </div>
            @endif
            <div class="detail-item">
                <span class="detail-label">📌 সাবজেক্ট</span>
                <span class="detail-value"><strong>{{ $subject }}</strong></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">📅 তারিখ</span>
                <span class="detail-value">{{ $date }}</span>
            </div>

            {{-- Message --}}
            <div style="margin-top: 20px;">
                <div style="font-weight: 600; margin-bottom: 8px;">💬 বার্তা:</div>
                <div class="message-box">
                    <p>{{ $messageBody }}</p>
                </div>
            </div>

            {{-- Reply Button --}}
            <div style="text-align: center; margin-top: 24px;">
                <a href="mailto:{{ $email }}?subject=Re: {{ $subject }}" class="reply-btn">
                    <i class="fas fa-reply"></i> রিপ্লাই করুন
                </a>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>
                © {{ date('Y') }} <a href="{{ url('/') }}">বগুড়া বাজার</a> —
                এই ইমেইলটি স্বয়ংক্রিয়ভাবে জেনারেট করা হয়েছে।
            </p>
            <p style="font-size: 12px; opacity: 0.7;">
                আপনি এই ইমেইল পেয়েছেন কারণ আমাদের ওয়েবসাইটের যোগাযোগ ফর্ম থেকে একটি বার্তা পাঠানো হয়েছে।
            </p>
        </div>
    </div>
</body>
</html>
