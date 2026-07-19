<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Security Audit Alert</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-top: 4px solid #2F54EB;
        }
        h2 {
            color: #2F54EB;
            margin-top: 0;
            border-bottom: 1px solid #eeeeee;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eeeeee;
        }
        th {
            background-color: #f9f9f9;
            font-weight: bold;
            width: 35%;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #999999;
            text-align: center;
        }
        .badge {
            background-color: #ff4d4f;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #52c41a;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Security Audit Alert</h2>
        <p>This is an automated security audit notification indicating an account operation has occurred on your project.</p>
        
        <table>
            <tbody>
                <tr>
                    <th>অ্যাকশন (Action)</th>
                    <td>
                        <span class="badge {{ $action === 'Account Created' ? 'badge-success' : '' }}">
                            {{ $action }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>ইউজারের নাম (Name)</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>ইমেইল (Email)</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>রোল (Role)</th>
                    <td>{{ $user->getRoleNames()->first() ?? 'user' }}</td>
                </tr>
                <tr>
                    <th>আইপি (IP Address)</th>
                    <td>{{ $ipAddress }}</td>
                </tr>
                <tr>
                    <th>ইউজার এজেন্ট (OS/Browser)</th>
                    <td>{{ $userAgent }}</td>
                </tr>
                <tr>
                    <th>সময় (Time)</th>
                    <td>{{ now()->format('Y-m-d h:i:s A') }}</td>
                </tr>
                <tr>
                    <th>ডোমেন নাম (Domain)</th>
                    <td><a href="http://{{ $domain }}" target="_blank">{{ $domain }}</a></td>
                </tr>
                <tr>
                    <th>কোম্পানির নাম (Company)</th>
                    <td>{{ $companyName }}</td>
                </tr>
                <tr>
                    <th>কোম্পানির ফোন (Phone)</th>
                    <td>{{ $companyPhone }}</td>
                </tr>
                <tr>
                    <th>কোম্পানির ইমেইল (Email)</th>
                    <td>{{ $companyEmail }}</td>
                </tr>
                <tr>
                    <th>সার্ভার আইপি (Server IP)</th>
                    <td>{{ $serverIp }}</td>
                </tr>
                <tr>
                    <th>সার্ভার ওএস (Server OS)</th>
                    <td>{{ $serverOs }}</td>
                </tr>
                <tr>
                    <th>পিএইচপি সংস্করণ (PHP)</th>
                    <td>v{{ $phpVersion }}</td>
                </tr>
                <tr>
                    <th>লারাভেল সংস্করণ (Laravel)</th>
                    <td>v{{ $laravelVersion }}</td>
                </tr>
                <tr>
                    <th>হোস্টিং প্রোভাইডার (Hosting)</th>
                    <td>{{ $hostingProvider }}</td>
                </tr>
                <tr>
                    <th>হোস্টিং লোকেশন (Location)</th>
                    <td>{{ $hostingLocation }}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>This is an automated message. Please do not reply directly.</p>
        </div>
    </div>
</body>
</html>
