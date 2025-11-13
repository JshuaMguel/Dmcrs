<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $subject ?? 'Notification' }} - USTP DMCRS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            background-color: #f5f7fa;
            padding: 0;
            margin: 0;
        }
        .email-wrapper {
            max-width: 650px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: #ffffff;
            padding: 35px 40px;
            text-align: center;
        }
        .email-header h1 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        .email-header p {
            font-size: 13px;
            opacity: 0.95;
            font-weight: 300;
        }
        .email-body {
            padding: 40px;
        }
        .subject-title {
            font-size: 20px;
            color: #1e3a8a;
            margin-bottom: 25px;
            font-weight: 600;
            padding-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
        }
        .greeting {
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .message-content {
            font-size: 15px;
            color: #4a5568;
            margin-bottom: 25px;
            line-height: 1.8;
            white-space: pre-line;
        }
        .action-button {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 15px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .info-note {
            background-color: #f8fafc;
            border-left: 4px solid #3b82f6;
            padding: 15px 20px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .info-note p {
            font-size: 14px;
            color: #4a5568;
            margin: 0;
            line-height: 1.6;
        }
        .footer {
            background-color: #f8fafc;
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            font-size: 13px;
            color: #718096;
            margin: 5px 0;
            line-height: 1.6;
        }
        .footer .institution {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }
        .footer .system-name {
            color: #4a5568;
            font-size: 12px;
        }
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 25px 20px;
            }
            .email-header {
                padding: 25px 20px;
            }
            .btn {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="email-header">
            <h1>UNIVERSITY OF SCIENCE AND TECHNOLOGY OF SOUTHERN PHILIPPINES</h1>
            <p>Balubal Campus - Digital Makeup Class Request System</p>
        </div>
        
        <!-- Body -->
        <div class="email-body">
            <div class="subject-title">{{ $subject ?? 'Notification' }}</div>
            
            <div class="greeting">Hello {{ $user->name ?? 'User' }},</div>
            
            <div class="message-content">{!! $message ?? '' !!}</div>
            
            @if(isset($actionUrl) && $actionUrl)
            <div class="action-button">
                <a href="{{ $actionUrl }}" class="btn">
                    View in System
                </a>
            </div>
            @endif
            
            <div class="info-note">
                <p>This is an automated notification from the Digital Makeup Class Request System. Please log in to your account to view more details or take action on this notification.</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p class="institution">University of Science and Technology of Southern Philippines</p>
            <p class="system-name">Balubal Campus - Digital Makeup Class Request System (DMCRS)</p>
            <p style="margin-top: 15px; font-size: 11px; color: #a0aec0;">
                This is an automated notification. Please do not reply to this email.<br>
                © {{ date('Y') }} USTP Balubal Campus. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>