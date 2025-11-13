<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Welcome to USTP DMCRS - Account Created</title>
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
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        .email-header p {
            font-size: 14px;
            opacity: 0.95;
            font-weight: 300;
        }
        .email-body {
            padding: 40px;
        }
        .greeting {
            font-size: 18px;
            color: #1e3a8a;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .intro-text {
            font-size: 15px;
            color: #4a5568;
            margin-bottom: 25px;
            line-height: 1.7;
        }
        .credentials-card {
            background: linear-gradient(to bottom, #f8fafc 0%, #ffffff 100%);
            border: 1px solid #e2e8f0;
            border-left: 4px solid #3b82f6;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .credentials-card h3 {
            color: #1e3a8a;
            font-size: 16px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .credential-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .credential-row:last-child {
            border-bottom: none;
        }
        .credential-label {
            font-weight: 600;
            color: #1e3a8a;
            width: 120px;
            font-size: 14px;
            flex-shrink: 0;
        }
        .credential-value {
            color: #2d3748;
            font-size: 14px;
            flex: 1;
        }
        .password-box {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            padding: 12px 15px;
            border-radius: 6px;
            margin-top: 10px;
        }
        .password-box code {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            font-weight: 600;
            color: #92400e;
            letter-spacing: 1px;
        }
        .section-title {
            font-size: 16px;
            color: #1e3a8a;
            margin: 25px 0 15px 0;
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }
        .steps-list {
            background-color: #f8fafc;
            padding: 20px 25px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .steps-list ol {
            margin-left: 20px;
            color: #4a5568;
        }
        .steps-list li {
            margin: 10px 0;
            line-height: 1.7;
            font-size: 14px;
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
        .security-notice {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 18px 20px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .security-notice p {
            margin: 5px 0;
            font-size: 14px;
            color: #991b1b;
        }
        .security-notice strong {
            color: #7f1d1d;
        }
        .features-box {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .features-box h4 {
            color: #1e3a8a;
            font-size: 15px;
            margin-bottom: 12px;
            font-weight: 600;
        }
        .features-box ul {
            margin-left: 20px;
            color: #4a5568;
        }
        .features-box li {
            margin: 8px 0;
            font-size: 14px;
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
            .credential-row {
                flex-direction: column;
            }
            .credential-label {
                width: 100%;
                margin-bottom: 5px;
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
            <div class="greeting">Welcome, {{ $user->name }}!</div>
            
            <div class="intro-text">
                Your account has been successfully created for the <strong>University of Science and Technology of Southern Philippines (USTP) - Digital Makeup Class Request System</strong>. 
                You can now access the system using the credentials provided below.
            </div>
            
            <!-- Account Credentials Card -->
            <div class="credentials-card">
                <h3>Your Account Details</h3>
                <div class="credential-row">
                    <div class="credential-label">Email:</div>
                    <div class="credential-value"><strong>{{ $user->email }}</strong></div>
                </div>
                <div class="credential-row">
                    <div class="credential-label">Role:</div>
                    <div class="credential-value">{{ ucwords(str_replace('_', ' ', $user->role)) }}</div>
                </div>
                @if(isset($user->department) && $user->department)
                <div class="credential-row">
                    <div class="credential-label">Department:</div>
                    <div class="credential-value">{{ $user->department->name }}</div>
                </div>
                @endif
                <div class="password-box">
                    <div style="font-size: 12px; color: #92400e; margin-bottom: 5px; font-weight: 600;">Temporary Password:</div>
                    <code>{{ $password }}</code>
                </div>
            </div>
            
            <div class="section-title">Getting Started</div>
            <div class="steps-list">
                <ol>
                    <li>Visit the DMCRS login page at <a href="{{ config('app.url') }}/login" style="color: #3b82f6; text-decoration: none;">{{ config('app.url') }}/login</a></li>
                    <li>Enter your email address and the temporary password provided above</li>
                    <li>Complete your profile information after logging in</li>
                    <li>Start using the system based on your assigned role</li>
                </ol>
            </div>
            
            <div class="action-button">
                <a href="{{ config('app.url') }}/login" class="btn">
                    Login to DMCRS
                </a>
            </div>
            
            <div class="security-notice">
                <p><strong>⚠️ Important Security Notice:</strong> Please change your password immediately after your first login for security purposes. Keep your login credentials confidential and do not share them with anyone.</p>
            </div>
            
            @if($user->role === 'faculty' || $user->role === 'department_chair' || $user->role === 'academic_head')
            <div class="features-box">
                <h4>System Features Available to You:</h4>
                <ul>
                    @if($user->role === 'faculty')
                    <li>Create and manage makeup class requests</li>
                    <li>View your request history and status</li>
                    <li>Receive real-time notifications about request updates</li>
                    <li>Upload proof of conduct for approved classes</li>
                    @elseif($user->role === 'department_chair')
                    <li>Review and approve/reject makeup class requests</li>
                    <li>View department request history and reports</li>
                    <li>Manage faculty requests within your department</li>
                    <li>Export reports in PDF and Excel formats</li>
                    @elseif($user->role === 'academic_head')
                    <li>Final approval authority for all makeup class requests</li>
                    <li>System-wide oversight and comprehensive reporting</li>
                    <li>Manage requests across all departments</li>
                    <li>Access to advanced analytics and exports</li>
                    @endif
                </ul>
            </div>
            @endif
            
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                <p style="font-size: 14px; color: #4a5568; margin: 0;">
                    If you have any questions or need assistance, please contact your system administrator or the IT support team.
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p class="institution">University of Science and Technology of Southern Philippines</p>
            <p class="system-name">Balubal Campus - Digital Makeup Class Request System (DMCRS)</p>
            <p style="margin-top: 15px; font-size: 11px; color: #a0aec0;">
                This is an automated message. Please do not reply to this email.<br>
                © {{ date('Y') }} USTP Balubal Campus. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>