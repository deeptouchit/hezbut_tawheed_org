<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ইমেইল প্রিভিউ - {{ $emailTemplate->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Baloo Da 2', 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            min-height: 100vh;
        }

        .preview-wrapper {
            max-width: 900px;
            margin: 0 auto;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .action-buttons {
            text-align: center;
            margin-bottom: 25px;
        }

        .action-btn {
            padding: 10px 24px;
            border: none;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0 5px;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-print {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .btn-close {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
        }

        .btn-close:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 87, 108, 0.6);
        }

        .preview-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
        }

        .preview-container:hover {
            transform: translateY(-5px);
        }

        .preview-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .preview-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            transform: rotate(45deg);
        }

        .preview-header h3 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }

        .preview-header .template-info {
            font-size: 14px;
            opacity: 0.95;
            position: relative;
            z-index: 1;
        }

        .template-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 12px;
            margin-top: 10px;
        }

        .preview-body {
            padding: 40px;
            background: #ffffff;
            line-height: 1.8;
            color: #333;
            font-size: 15px;
        }

        .preview-body img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .preview-footer {
            background: #f8f9fa;
            padding: 25px 40px;
            border-top: 1px solid #e9ecef;
            font-size: 13px;
            color: #6c757d;
            text-align: center;
        }

        .footer-social {
            margin-top: 15px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .footer-social a {
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-social a:hover {
            color: #764ba2;
        }

        .info-note {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px 20px;
            margin-top: 20px;
            border-radius: 8px;
            font-size: 12px;
            color: #856404;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .action-buttons {
                display: none;
            }
            .preview-container {
                box-shadow: none;
                transform: none;
            }
            .preview-header {
                background: #667eea;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .btn-print, .btn-close {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .preview-body {
                padding: 20px;
            }
            .preview-header h3 {
                font-size: 22px;
            }
            .action-btn {
                padding: 8px 18px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="preview-wrapper">
        <div class="action-buttons">
            <button class="action-btn btn-print" onclick="window.print();">
                <i class="fas fa-print"></i> প্রিন্ট করুন
            </button>
            <button class="action-btn btn-close" onclick="window.close();">
                <i class="fas fa-times"></i> বন্ধ করুন
            </button>
        </div>

        <div class="preview-container">
            <div class="preview-header">
                <h3>
                    <i class="fas fa-envelope-open-text"></i>
                    ইমেইল প্রিভিউ
                </h3>
                <div class="template-info">
                    <strong>{{ $emailTemplate->name }}</strong>
                    @if($emailTemplate->subject)
                        <br><small>{{ $emailTemplate->subject }}</small>
                    @endif
                </div>
                <div class="template-badge">
                    <i class="fas fa-tag"></i> {{ $emailTemplate->type ?? 'General' }}
                </div>
            </div>

            <div class="preview-body">
                {!! $emailTemplate->body !!}
            </div>

            <div class="preview-footer">
                <p>
                    <i class="far fa-copyright"></i> {{ date('Y') }}
                    <strong>আপনার কোম্পানির নাম</strong>।
                    সমস্ত অধিকার সংরক্ষিত।
                </p>
                <div class="footer-social">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
                <div class="info-note">
                    <i class="fas fa-info-circle"></i>
                    এই ইমেইলটি স্বয়ংক্রিয়ভাবে তৈরি। দয়া করে এটির উত্তর দেবেন না।
                </div>
            </div>
        </div>
    </div>

    <script>
        // ডায়নামিক কন্টেন্ট সাপোর্ট
        document.addEventListener('DOMContentLoaded', function() {
            // প্রিন্ট ডায়ালগ বক্স কাস্টমাইজ
            const printBtn = document.querySelector('.btn-print');
            if (printBtn) {
                printBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.print();
                });
            }

            // কীবোর্ড শর্টকাট (ESC কী প্রেস করলে উইন্ডো ক্লোজ)
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    window.close();
                }
            });
        });
    </script>
</body>
</html>


