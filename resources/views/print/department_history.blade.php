<!DOCTYPE html><html><head><meta charset="utf-8"><title>Request History - Print</title><style>@media print { .no-print{display:none} body{margin:10px} } body{font-family:Arial,sans-serif;font-size:12px;color:#111}h1{font-size:22px;margin:0 0 6px}table{width:100%;border-collapse:collapse;margin-top:12px}th,td{border:1px solid #444;padding:6px;font-size:11px}th{background:#023047;color:#fff;text-align:left}tr:nth-child(even){background:#f5f7fa}.meta{margin-top:4px;font-size:11px;color:#555}.badge{display:inline-block;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:600}.approved{background:#d1fae5;color:#065f46}.rejected{background:#fee2e2;color:#991b1b}.chair_approved{background:#fef3c7;color:#92400e}.pending{background:#e0e7ff;color:#3730a3}button{cursor:pointer}</style></head><body>
<div class="no-print" style="display:flex;justify-content:flex-end;align-items:center;margin-bottom:8px;gap:8px">
	<button onclick="window.print()" style="background:#023047;color:#fff;border:none;padding:6px 12px;border-radius:4px">Print</button>
	<a href="{{ route('department.history') }}" style="background:#e5e7eb;color:#111;text-decoration:none;padding:6px 12px;border-radius:4px;font-size:12px;border:1px solid #d1d5db">Back</a>
</div>
<h1>Department Request History</h1>
<div class="meta">Generated: {{ $generatedAt->format('M d, Y g:i A') }} | By: {{ $generatedBy }}</div>
<table><thead><tr><th>Faculty</th><th>Subject</th><th>Date</th><th>Time</th><th>Reason</th><th>Attachment</th><th>Students</th><th>Tracking</th><th>Room</th><th>Status</th><th>Remarks</th></tr></thead><tbody>
@foreach($requests as $req)
@php $status=strtolower($req->status); @endphp
<tr>
<td>{{ $req->faculty->name ?? 'N/A' }}</td>
<td>{{ $req->subject ?? $req->subject_title ?? 'N/A' }}</td>
<td>{{ $req->preferred_date ? \Carbon\Carbon::parse($req->preferred_date)->format('M d, Y') : 'N/A' }}</td>
<td>@php $start=$req->preferred_time; $end=$req->end_time; $fmt=function($t){ if(!$t) return ''; $f=substr_count($t,':')===2?'H:i:s':'H:i'; try{ return \Carbon\Carbon::createFromFormat($f,$t)->format('g:i A'); }catch(Exception $e){ return $t; } }; @endphp {{ $start ? $fmt($start) : '' }}@if($end) - {{ $fmt($end) }}@endif</td>
<td>{{ $req->reason ?? 'N/A' }}</td>
<td>{{ $req->attachment ? 'Yes' : 'No' }}</td>
<td>{{ $req->student_list ? collect(explode("\n", trim($req->student_list)))->filter()->count() : 0 }}</td>
<td>{{ $req->tracking_number ?? '—' }}</td>
<td>{{ $req->room ?? 'N/A' }}</td>
<td><span class="badge {{ $status }}">{{ ucfirst(strtolower(str_replace('_',' ', $req->status))) }}</span></td>
<td>{{ $req->chair_remarks ?? $req->head_remarks ?? '—' }}</td>
</tr>
@endforeach
</tbody></table>
</body></html>
