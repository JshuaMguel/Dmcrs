<!DOCTYPE html><html><head><meta charset="utf-8"><title>Academic Head Reports PDF</title><style>
body{font-family:DejaVu Sans,Arial,sans-serif;font-size:12px;color:#111}
h1{font-size:20px;margin:0 0 10px}
table{width:100%;border-collapse:collapse;margin-top:10px}
th,td{border:1px solid #ccc;padding:6px;font-size:11px}
th{background:#023047;color:#fff;text-align:left}
tr:nth-child(even){background:#f7f9fc}
.meta{margin-top:5px;font-size:11px;color:#555}
.badge{display:inline-block;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:600}
.approved{background:#d1fae5;color:#065f46}
.rejected{background:#fee2e2;color:#991b1b}
.pending{background:#e0e7ff;color:#3730a3}
.other{background:#fef3c7;color:#92400e}
</style></head><body>
<h1>Academic Head Reports</h1>
<div class="meta">Generated: {{ $generatedAt->format('M d, Y g:i A') }} | By: {{ $generatedBy }} | Total: {{ count($reports) }}</div>
<table>
<thead><tr>
<th>#</th>
<th>Faculty</th>
<th>Tracking</th>
<th>Subject</th>
<th>Reason</th>
<th>Date</th>
<th>Time</th>
<th>Room</th>
<th>Status</th>
<th>Approved</th>
<th>Remarks</th>
</tr></thead>
<tbody>
@foreach($reports as $i => $r)
@php
 $status = strtolower($r->final_status ?? 'pending');
 $statusClass = in_array($status,['approved','rejected','pending']) ? $status : 'other';
 $fmt = function($t){ if(!$t) return ''; $f = substr_count($t, ':')===2 ? 'H:i:s':'H:i'; try { return \Carbon\Carbon::createFromFormat($f,$t)->format('g:i A'); } catch(Exception $e){ return $t; } };
@endphp
<tr>
<td>{{ $i+1 }}</td>
<td>{{ $r->faculty }}</td>
<td>{{ $r->tracking_number ?? '—' }}</td>
<td>{{ $r->subject }}</td>
<td>{{ Str::limit($r->reason ?? '—', 40) }}</td>
<td>{{ $r->preferred_date ? \Carbon\Carbon::parse($r->preferred_date)->format('M d, Y') : '—' }}</td>
<td>{{ $r->preferred_time ? $fmt($r->preferred_time) : '' }}</td>
<td>{{ $r->room ?? '—' }}</td>
<td><span class="badge {{ $statusClass }}">{{ ucfirst($status) }}</span></td>
<td>{{ $r->date_approved ? \Carbon\Carbon::parse($r->date_approved)->format('M d, Y g:i A') : '—' }}</td>
<td>{{ $r->remarks ? Str::limit($r->remarks, 50) : '—' }}</td>
</tr>
@endforeach
</tbody></table>
</body></html>
