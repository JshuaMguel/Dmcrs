
@extends('layouts.app')

@section('title', 'Department History - USTP DMCRS')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-4 sm:p-6 rounded-xl shadow-lg">
    <!-- Header Section -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col items-center gap-3 sm:gap-4 md:flex-row md:justify-between">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-ustpBlue rounded-full">
                    <span class="text-lg sm:text-2xl text-white">📚</span>
                </div>
                <div class="text-center md:text-left">
                    <h2 class="text-2xl sm:text-3xl font-bold text-ustpBlue">Request History</h2>
                    <p class="text-sm sm:text-base text-gray-600">View all processed makeup class requests</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                <a href="{{ route('department.history.print') }}" target="_blank" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium flex items-center justify-center gap-2 shadow-sm w-full sm:w-auto">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9V4h12v5M6 18H5a2 2 0 01-2-2v-5a2 2 0 012-2h14a2 2 0 012 2v5a2 2 0 01-2 2h-1M6 14h12v4H6v-4z"/></svg>
                    Print
                </a>
                <a href="{{ route('department.history.export.pdf') }}" class="bg-ustpBlue text-white hover:bg-ustpBlue/90 px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium flex items-center justify-center gap-2 shadow-sm w-full sm:w-auto">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m0 0l-6-6m6 6l6-6M5 5h14"/></svg>
                    PDF
                </a>
            </div>
        </div>
    </div>

    @if($requests->count())
        <!-- History Table -->
        <div class="bg-white rounded-lg shadow-md overflow-x-auto border border-gray-200">
            <table class="w-full divide-y divide-gray-200" style="min-width: 1400px;">
                <thead class="bg-ustpBlue">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-2">👨‍🏫</span>
                                Faculty
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-2">📖</span>
                                Subject
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-2">📅</span>
                                Date
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-2">🕐</span>
                                Time
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-2">💭</span>
                                Reason
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-2">📎</span>
                                Attachment
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-2">👥</span>
                                Students
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-2">#️⃣</span>
                                Tracking
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-2">🏫</span>
                                Room
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-2">📊</span>
                                Status
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-2">💬</span>
                                Remarks
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($requests as $req)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <!-- Faculty -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-ustpBlue flex items-center justify-center">
                                            <span class="text-xs font-medium text-white">
                                                {{ substr($req->faculty->name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-semibold text-gray-900">{{ $req->faculty->name }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Subject -->
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $req->subject }}</div>
                            </td>

                            <!-- Date -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($req->preferred_date)->format('M d, Y') }}
                                </div>
                            </td>

                            <!-- Time -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @php
                                        if (!function_exists('formatTime')) {
                                            function formatTime($time) {
                                                if (!$time) return '';
                                                if (preg_match('/^\d{1,2}:\d{2}(:\d{2})?$/', $time)) {
                                                    try {
                                                        $format = (substr_count($time, ':') === 2) ? 'H:i:s' : 'H:i';
                                                        return \Carbon\Carbon::createFromFormat($format, $time)->format('g:i A');
                                                    } catch (Exception $e) {
                                                        return $time;
                                                    }
                                                }
                                                return $time;
                                            }
                                        }
                                    @endphp
                                    {{ formatTime($req->preferred_time) }} - {{ formatTime($req->end_time) }}
                                </div>
                            </td>

                            <!-- Reason -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $req->reason }}">
                                    {{ $req->reason }}
                                </div>
                            </td>

                            <!-- Attachment -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($req->attachment)
                                    <a href="{{ asset('storage/' . $req->attachment) }}" target="_blank"
                                       class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-ustpBlue hover:bg-blue-200">
                                        📎 View
                                    </a>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                        N/A
                                    </span>
                                @endif
                            </td>

                            <!-- Student List -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($req->student_list)
                                    <a href="{{ asset('storage/' . $req->student_list) }}" target="_blank"
                                       class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 hover:bg-green-200">
                                        👥 View
                                    </a>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                        N/A
                                    </span>
                                @endif
                            </td>

                            <!-- Tracking Number -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-ustpGold text-ustpBlue">
                                    {{ $req->tracking_number }}
                                </span>
                            </td>

                            <!-- Room -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $req->room }}</div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'completed' => 'bg-blue-100 text-blue-800'
                                    ];
                                    $colorClass = $statusColors[$req->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>

                            <!-- Remarks -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $req->chair_remarks ?? 'N/A' }}">
                                    {{ $req->chair_remarks ?? 'N/A' }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-12">
            <span class="text-6xl mb-4">📚</span>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No History Available</h3>
            <p class="text-gray-500 text-center max-w-md">
                Request history will appear here once makeup classes have been processed.
            </p>
        </div>
    @endif
</div>
@endsection

