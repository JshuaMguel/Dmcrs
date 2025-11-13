<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $subject ?? 'Makeup Class Request Notification' }} - USTP DMCRS</title>
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
            line-height: 1.7;
        }
        .details-card {
            background: linear-gradient(to bottom, #f8fafc 0%, #ffffff 100%);
            border: 1px solid #e2e8f0;
            border-left: 4px solid #3b82f6;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .detail-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #1e3a8a;
            width: 130px;
            font-size: 14px;
            flex-shrink: 0;
        }
        .detail-value {
            color: #2d3748;
            font-size: 14px;
            flex: 1;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-top: 10px;
        }
        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #f59e0b;
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
        .remarks-box {
            background-color: #f8fafc;
            border-left: 4px solid #6b7280;
            padding: 15px 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .remarks-box p {
            margin: 5px 0;
            font-size: 14px;
            color: #374151;
        }
        .remarks-box strong {
            color: #1f2937;
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
            .detail-row {
                flex-direction: column;
            }
            .detail-label {
                width: 100%;
                margin-bottom: 5px;
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
            <div class="greeting">Hello {{ $notifiable->name ?? 'User' }},</div>
            
            <div class="message-content">
                {{ $message ?? 'Your makeup class request status has been updated.' }}
            </div>

            <!-- Request Details Card -->
            <div class="details-card">
                <div class="detail-row">
                    <div class="detail-label">Subject Code:</div>
                    <div class="detail-value"><strong>{{ $subjectCode ?? $request->subject ?? 'N/A' }}</strong></div>
                </div>
                @if(isset($subjectTitle) && $subjectTitle)
                <div class="detail-row">
                    <div class="detail-label">Subject Title:</div>
                    <div class="detail-value">{{ $subjectTitle }}</div>
                </div>
                @endif
                <div class="detail-row">
                    <div class="detail-label">Room/Venue:</div>
                    <div class="detail-value">{{ $request->room ?? 'N/A' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Date:</div>
                    <div class="detail-value">
                        <strong>
                            @if(isset($request->preferred_date))
                                @if($request->preferred_date instanceof \Carbon\Carbon)
                                    {{ $request->preferred_date->format('l, F d, Y') }}
                                @else
                                    {{ \Carbon\Carbon::parse($request->preferred_date)->format('l, F d, Y') }}
                                @endif
                            @else
                                N/A
                            @endif
                        </strong>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Time:</div>
                    <div class="detail-value">
                        @if(isset($request->preferred_time))
                            {{ $request->preferred_time }}
                            @if(isset($request->end_time) && $request->end_time)
                                - {{ $request->end_time }}
                            @endif
                        @else
                            N/A
                        @endif
                    </div>
                </div>
                @if(isset($request->tracking_number) && $request->tracking_number)
                <div class="detail-row">
                    <div class="detail-label">Tracking No.:</div>
                    <div class="detail-value"><code style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-family: monospace;">{{ $request->tracking_number }}</code></div>
                </div>
                @endif
                @if(isset($status))
                <div class="status-badge 
                    @if($status === 'APPROVED' || $status === 'CHAIR_APPROVED') status-approved
                    @elseif($status === 'HEAD_REJECTED' || $status === 'CHAIR_REJECTED') status-rejected
                    @else status-pending
                    @endif">
                    @if($status === 'APPROVED')
                        ✓ Approved by Academic Head
                    @elseif($status === 'CHAIR_APPROVED')
                        ✓ Approved by Department Chair
                    @elseif($status === 'HEAD_REJECTED')
                        ✗ Rejected by Academic Head
                    @elseif($status === 'CHAIR_REJECTED')
                        ✗ Rejected by Department Chair
                    @elseif($status === 'submitted')
                        ⏳ Submitted for Review
                    @elseif($status === 'updated')
                        📝 Request Updated
                    @else
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    @endif
                </div>
                @endif
            </div>

            @if(isset($remarks) && $remarks)
            <div class="remarks-box">
                <p><strong>Remarks:</strong> {{ $remarks }}</p>
            </div>
            @endif

            @if(isset($actionUrl) && $actionUrl)
            <div class="action-button">
                <a href="{{ $actionUrl }}" class="btn">
                    {{ $actionText ?? 'View Request Details' }}
                </a>
            </div>
            @endif

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                <p style="font-size: 14px; color: #4a5568; margin: 0;">
                    This is an automated notification from the Digital Makeup Class Request System. 
                    Please log in to your account to view more details or take action on this request.
                </p>
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

