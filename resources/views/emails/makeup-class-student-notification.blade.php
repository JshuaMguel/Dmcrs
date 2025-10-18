@php
    use Illuminate\Support\Facades\Crypt;
    use App\Helpers\TimeHelper;
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Makeup Class Notification</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2563eb;">Makeup Class Scheduled</h2>

        <p>Dear Student,</p>

        <p>You have been scheduled for a makeup class. Here are the details:</p>

        <div style="background-color: #f8fafc; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p><strong>Subject:</strong> {{ $makeupRequest->subject }}</p>
            <p><strong>Faculty:</strong> {{ $makeupRequest->faculty->name }}</p>
            <p><strong>Room:</strong> {{ $makeupRequest->room }}</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($makeupRequest->preferred_date)->format('M d, Y') }}</p>
            <p><strong>Start Time:</strong> {{ TimeHelper::formatTime($makeupRequest->preferred_time) }}</p>
            <p><strong>End Time:</strong> {{ TimeHelper::formatTime($makeupRequest->end_time) }}</p>
            <p><strong>Reason:</strong> {{ $makeupRequest->reason }}</p>
            @if($makeupRequest->tracking_number)
                <p><strong>Tracking Number:</strong> {{ $makeupRequest->tracking_number }}</p>
            @endif
        </div>

        <p>Please confirm your attendance by clicking one of the buttons below:</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/makeup-class/confirm/' . $makeupRequest->id . '/' . Crypt::encrypt($email)) }}"
               style="background-color: #10b981; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin-right: 10px; display: inline-block;">
                ✅ Confirm Attendance
            </a>

            <a href="{{ url('/makeup-class/decline/' . $makeupRequest->id . '/' . Crypt::encrypt($email)) }}"
               style="background-color: #ef4444; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">
                ❌ Decline
            </a>
        </div>

        <p>If you have any questions, please contact your faculty member.</p>

        <p>Best regards,<br>
        DMCRS System</p>
    </div>
</body>
</html>
