<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #2563eb;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8fafc;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .button {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>USTP Balubal Campus - DMCRS</h1>
        <p>Digital Makeup Class Request System</p>
    </div>
    
    <div class="content">
        <h2>{{ $subject }}</h2>
        
        <p>Hello {{ $user->name ?? 'User' }},</p>
        
        <p>{!! nl2br(e($message)) !!}</p>
        
        @if($actionUrl)
            <p>
                <a href="{{ $actionUrl }}" class="button">
                    View in System
                </a>
            </p>
        @endif
        
        <p>This is an automated notification from the Digital Makeup Class Request System.</p>
        
        <div class="footer">
            <p>© {{ date('Y') }} USTP Balubal Campus. All rights reserved.</p>
            <p>This email was sent via Brevo API for reliable delivery.</p>
        </div>
    </div>
</body>
</html>