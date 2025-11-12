<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student List - {{ $makeupRequest->subject }}</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2563eb;
            margin: 0;
            font-size: 24px;
        }
        .header h2 {
            color: #1e40af;
            margin: 10px 0 0 0;
            font-size: 18px;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-label {
            font-weight: bold;
            color: #374151;
        }
        .info-value {
            color: #6b7280;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #2563eb;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            border-top: 2px solid #374151;
            padding-top: 10px;
            text-align: center;
        }
        .print-button {
            background-color: #2563eb;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .print-button:hover {
            background-color: #1e40af;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" class="print-button">🖨️ Print Student List</button>
    </div>

    <div class="header">
        <h1>USTP Balubal Campus - DMCRS</h1>
        <h2>Makeup Class Student Attendance List</h2>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Subject:</span>
            <span class="info-value">{{ $makeupRequest->subject }} - {{ $makeupRequest->subject_title }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Faculty:</span>
            <span class="info-value">{{ $makeupRequest->faculty->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Date:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($makeupRequest->preferred_date)->format('F d, Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Time:</span>
            <span class="info-value">{{ \App\Helpers\TimeHelper::formatTime($makeupRequest->preferred_time) }} - {{ \App\Helpers\TimeHelper::formatTime($makeupRequest->end_time) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Room:</span>
            <span class="info-value">{{ $makeupRequest->room }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Section:</span>
            <span class="info-value">
                @if($makeupRequest->sectionRelation)
                    {{ $makeupRequest->sectionRelation->year_level }}-{{ $makeupRequest->sectionRelation->section_name }}
                @else
                    {{ $makeupRequest->section ?? 'N/A' }}
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Tracking Number:</span>
            <span class="info-value">{{ $makeupRequest->tracking_number }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Signature</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student['student_id'] }}</td>
                    <td>{{ $student['name'] }}</td>
                    <td>{{ $student['email'] }}</td>
                    <td>
                        @if($student['confirmed'])
                            <span style="color: #10b981; font-weight: bold;">✅ Confirmed</span>
                        @elseif($student['status'] === 'declined')
                            <span style="color: #ef4444;">❌ Declined</span>
                        @else
                            <span style="color: #f59e0b;">⏳ Pending</span>
                        @endif
                    </td>
                    <td style="height: 40px; border: 1px solid #d1d5db;"></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #6b7280;">
                        No students found in CSV file.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total Students: {{ count($students) }}</p>
        <p>Confirmed: {{ collect($students)->where('confirmed', true)->count() }} | 
           Declined: {{ collect($students)->where('status', 'declined')->count() }} | 
           Pending: {{ collect($students)->where('status', 'pending')->count() }}</p>
        <p>Generated on: {{ now()->format('F d, Y g:i A') }}</p>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p><strong>Faculty Signature</strong></p>
            <p style="margin-top: 50px;">_________________________</p>
            <p>{{ $makeupRequest->faculty->name }}</p>
        </div>
        <div class="signature-box">
            <p><strong>Date</strong></p>
            <p style="margin-top: 50px;">_________________________</p>
        </div>
    </div>

    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>

