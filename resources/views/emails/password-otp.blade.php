<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Change OTP</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <div style="max-width: 500px; margin: 0 auto; padding: 20px; background: #f8f9fa; border-radius: 8px;">
        <div style="text-align: center; background: #007bff; color: white; padding: 15px; border-radius: 8px 8px 0 0;">
            <h2 style="margin: 0;">Password Change Request</h2>
        </div>

        <div style="background: white; padding: 30px; border-radius: 0 0 8px 8px;">
            <p>Dear <strong>{{ $name }}</strong>,</p>

            <p>You have requested to change your password. Please use the following OTP to verify your identity:</p>

            <div style="text-align: center; margin: 30px 0;">
                <div style="font-size: 32px; font-weight: bold; letter-spacing: 5px; background: #e9ecef; display: inline-block; padding: 15px 30px; border-radius: 8px;">
                    {{ $otp }}
                </div>
            </div>

            <p style="color: #dc3545; font-size: 14px;">
                <strong>⚠️ Important:</strong> This OTP is valid for <strong>{{ $expires_in }} minutes</strong> only.
            </p>

            <p>If you didn't request this, please ignore this email or contact support.</p>

            <hr style="margin: 20px 0;">

            <p style="font-size: 12px; color: #6c757d;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
