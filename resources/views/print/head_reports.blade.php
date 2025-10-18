<!DOCTYPE html><html><head><meta charset="utf-8" /><title>Print - Academic Head Reports</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
<style>
 @media print{.no-print{display:none !important} body{padding:0}}
 table{font-size:12px}
 .badge{display:inline-block;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:600}
 .approved{background:#d1fae5;color:#065f46}
 .rejected{background:#fee2e2;color:#991b1b}
 .pending{background:#e0e7ff;color:#3730a3}
 .other{background:#fef3c7;color:#92400e}
</style></head><body class="p-6">
<div class="no-print mb-4 flex justify-between items-start">
  <div>
    <h1 class="text-2xl font-bold text-blue-800">Academic Head Reports</h1>
    <p class="text-gray-600 text-sm">Generated {{ $generatedAt->format('M d, Y g:i A') }} by {{ $generatedBy }} • Total: {{ count($reports) }}</p>
  </div>
  <div class="space-x-2">
    <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">Print</button>
    <a href="{{ route('head.reports.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded shadow hover:bg-gray-300">Back</a>
  </div>
</div>
<table class="min-w-full border border-gray-300 text-sm">
 <thead>
  <tr class="bg-blue-800 text-white">
    <th class="px-2 py-2 border">#</th>
    <th class="px-2 py-2 border">Faculty</th>
    <th class="px-2 py-2 border">Tracking</th>
    <th class="px-2 py-2 border">Subject</th>
    <th class="px-2 py-2 border">Reason</th>
    <th class="px-2 py-2 border">Date</th>
    <th class="px-2 py-2 border">Time</th>
    <th class="px-2 py-2 border">Room</th>
    <th class="px-2 py-2 border">Status</th>
    <th class="px-2 py-2 border">Approved</th>
    <th class="px-2 py-2 border">Remarks</th>
  </tr>
 </thead>
 <tbody>
  @foreach($reports as $i => $r)
   @php
     $status = strtolower($r->final_status ?? 'pending');
     $statusClass = in_array($status,['approved','rejected','pending']) ? $status : 'other';
     $fmt = function($t){ if(!$t) return ''; $f = substr_count($t, ':')===2 ? 'H:i:s':'H:i'; try { return \Carbon\Carbon::createFromFormat($f,$t)->format('g:i A'); } catch(Exception $e){ return $t; } };
   @endphp
   <tr class="odd:bg-white even:bg-gray-50">
     <td class="px-2 py-1 border">{{ $i+1 }}</td>
     <td class="px-2 py-1 border">{{ $r->faculty }}</td>
     <td class="px-2 py-1 border">{{ $r->tracking_number ?? '—' }}</td>
     <td class="px-2 py-1 border">{{ $r->subject }}</td>
     <td class="px-2 py-1 border">{{ Str::limit($r->reason ?? '—', 40) }}</td>
     <td class="px-2 py-1 border">{{ $r->preferred_date ? \Carbon\Carbon::parse($r->preferred_date)->format('M d, Y') : '—' }}</td>
     <td class="px-2 py-1 border">{{ $r->preferred_time ? $fmt($r->preferred_time) : '' }}</td>
     <td class="px-2 py-1 border">{{ $r->room ?? '—' }}</td>
     <td class="px-2 py-1 border"><span class="badge {{ $statusClass }}">{{ ucfirst($status) }}</span></td>
     <td class="px-2 py-1 border">{{ $r->date_approved ? \Carbon\Carbon::parse($r->date_approved)->format('M d, Y g:i A') : '—' }}</td>
     <td class="px-2 py-1 border">{{ $r->remarks ? Str::limit($r->remarks, 50) : '—' }}</td>
   </tr>
  @endforeach
 </tbody>
</table>
</body></html>
