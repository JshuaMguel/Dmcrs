<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Head Reports - Print | USTP DMCRS</title>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; padding: 15px; }
            .page-break { page-break-after: always; }
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            color: #1f2937;
            background: #fff;
            line-height: 1.5;
        }
        
        .header {
            background: linear-gradient(135deg, #023047 0%, #0a4d6e 100%);
            color: #fff;
            padding: 20px 25px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo-circle {
            width: 50px;
            height: 50px;
            background: #ffc107;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 20px;
            color: #023047;
        }
        
        .header-title {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }
        
        .header-subtitle {
            font-size: 12px;
            opacity: 0.9;
            margin-top: 2px;
        }
        
        .meta-info {
            background: #f3f4f6;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
            font-size: 10px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .meta-item strong {
            color: #023047;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 12px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-left: 4px solid #023047;
            padding: 12px;
            border-radius: 6px;
        }
        
        .stat-label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        
        .stat-value {
            font-size: 18px;
            font-weight: 700;
            color: #023047;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        thead {
            background: linear-gradient(135deg, #023047 0%, #0a4d6e 100%);
            color: #fff;
        }
        
        th {
            padding: 12px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-right: 1px solid rgba(255,255,255,0.2);
        }
        
        th:last-child {
            border-right: none;
        }
        
        td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }
        
        tbody tr {
            transition: background 0.2s;
        }
        
        tbody tr:hover {
            background: #f9fafb;
        }
        
        tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .badge.approved { background: #d1fae5; color: #065f46; }
        .badge.rejected { background: #fee2e2; color: #991b1b; }
        .badge.pending { background: #e0e7ff; color: #3730a3; }
        .badge.other { background: #fef3c7; color: #92400e; }
        
        .no-print {
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s;
        }
        
        .btn-print {
            background: #023047;
            color: #fff;
        }
        
        .btn-print:hover {
            background: #0a4d6e;
        }
        
        .btn-back {
            background: #e5e7eb;
            color: #1f2937;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-back:hover {
            background: #d1d5db;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-print">🖨️ Print</button>
        <a href="{{ route('head.reports.index') }}" class="btn btn-back">← Back</a>
    </div>

    <div class="header">
        <div class="header-top">
            <div class="logo-section">
                <div class="logo-circle">U</div>
                <div>
                    <h1 class="header-title">USTP DMCRS</h1>
                    <p class="header-subtitle">Digital Makeup Class Request System</p>
                </div>
            </div>
        </div>
    </div>

    <div class="meta-info">
        <div class="meta-item">
            <strong>📋 Report:</strong> Academic Head Reports
        </div>
        <div class="meta-item">
            <strong>👤 Generated By:</strong> {{ $generatedBy }}
        </div>
        <div class="meta-item">
            <strong>📅 Generated:</strong> {{ $generatedAt->format('F d, Y g:i A') }}
        </div>
        <div class="meta-item">
            <strong>📊 Total Reports:</strong> {{ count($reports) }}
        </div>
    </div>

    @php
        $approvedCount = collect($reports)->where('final_status', 'approved')->count();
        $rejectedCount = collect($reports)->where('final_status', 'rejected')->count();
        $pendingCount = collect($reports)->where('final_status', 'pending')->count();
    @endphp

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Reports</div>
            <div class="stat-value">{{ count($reports) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Approved</div>
            <div class="stat-value" style="color: #065f46;">{{ $approvedCount }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Rejected</div>
            <div class="stat-value" style="color: #991b1b;">{{ $rejectedCount }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Pending</div>
            <div class="stat-value" style="color: #3730a3;">{{ $pendingCount }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Faculty</th>
                <th>Tracking #</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Time</th>
                <th>Room</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Approved Date</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $i => $r)
            @php
                $status = strtolower($r->final_status ?? 'pending');
                $statusClass = in_array($status, ['approved', 'rejected', 'pending']) ? $status : 'other';
                $fmt = function($t) {
                    if(!$t) return '';
                    $f = substr_count($t, ':') === 2 ? 'H:i:s' : 'H:i';
                    try {
                        return \Carbon\Carbon::createFromFormat($f, $t)->format('g:i A');
                    } catch(Exception $e) {
                        return $t;
                    }
                };
            @endphp
            <tr>
                <td><strong>{{ $i + 1 }}</strong></td>
                <td>{{ $r->faculty }}</td>
                <td><strong>{{ $r->tracking_number ?? '—' }}</strong></td>
                <td>{{ $r->subject }}</td>
                <td>{{ $r->preferred_date ? \Carbon\Carbon::parse($r->preferred_date)->format('M d, Y') : '—' }}</td>
                <td>{{ $r->preferred_time ? $fmt($r->preferred_time) : '—' }}</td>
                <td>{{ $r->room ?? '—' }}</td>
                <td>{{ Str::limit($r->reason ?? '—', 40) }}</td>
                <td>
                    <span class="badge {{ $statusClass }}">
                        {{ ucfirst($status) }}
                    </span>
                </td>
                <td>{{ $r->date_approved ? \Carbon\Carbon::parse($r->date_approved)->format('M d, Y g:i A') : '—' }}</td>
                <td>{{ Str::limit($r->remarks ?? '—', 30) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This is a system-generated report from USTP Digital Makeup Class Request System (DMCRS)</p>
        <p>Generated on {{ $generatedAt->format('F d, Y \a\t g:i A') }} | Page 1</p>
    </div>
</body>
</html>

