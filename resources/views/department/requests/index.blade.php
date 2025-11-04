
@extends('layouts.app')

@section('title', 'Makeup Class Requests - USTP DMCRS')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-4 sm:p-6 rounded-xl shadow-lg">
    <!-- Header Section -->
    <div class="text-center mb-6 sm:mb-8">
        <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-ustpBlue rounded-full mb-4">
            <span class="text-lg sm:text-2xl text-white">📋</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-ustpBlue mb-2">Pending Requests</h2>
        <p class="text-sm sm:text-base text-gray-600">Review and manage makeup class requests</p>
    </div>

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
    <!-- Requests Table -->
    <div class="bg-white rounded-lg shadow-md overflow-x-auto border border-gray-200">
        <table class="w-full divide-y divide-gray-200" style="min-width: 1200px;">
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
                            <span class="mr-2">🏷️</span>
                            Department
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
                            <span class="mr-2">👥</span>
                            Section
                        </div>
                    </th>
                    <th class="px-8 py-4 text-center text-xs font-bold text-white uppercase tracking-wider" style="min-width: 120px;">
                        <div class="flex items-center justify-center">
                            <span class="mr-2">⚙️</span>
                            Actions
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($requests as $req)
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

                        <!-- Department -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $deptName = $req->faculty->department->name ?? ($req->subject && is_object($req->subject) && $req->subject->department ? $req->subject->department->name : null);
                            @endphp
                            @if($deptName)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $deptName }}
                                </span>
                            @else
                                <span class="text-sm text-gray-500">N/A</span>
                            @endif
                        </td>

                        <!-- Subject -->
                        <td class="px-6 py-4">
                            @if($req->subject && is_object($req->subject))
                                <div class="text-sm font-medium text-gray-900">{{ $req->subject->subject_code }}</div>
                                <div class="text-xs text-gray-500">{{ $req->subject->subject_title }}</div>
                            @else
                                <div class="text-sm font-medium text-gray-900">{{ $req->subject }}</div>
                                @if($req->subject_title)
                                    <div class="text-xs text-gray-500">{{ $req->subject_title }}</div>
                                @endif
                            @endif
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
                            <div class="text-sm text-gray-900">{{ $req->room ?? 'AVR' }}</div>
                        </td>

                        <!-- Section -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($req->section_id && $req->sectionRelation)
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $req->sectionRelation->year_level }}-{{ $req->sectionRelation->section_name }}
                                </div>
                            @elseif($req->section)
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $req->section }}
                                </div>
                            @else
                                <span class="text-sm text-gray-500">N/A</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-8 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('department.requests.show', $req->id) }}"
                               class="inline-flex items-center justify-center px-4 py-2 bg-ustpBlue text-white text-sm rounded-lg hover:bg-blue-800 transition-colors font-medium shadow-sm">
                                📋 Review
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-4xl mb-4">📭</span>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No pending requests</h3>
                                <p class="text-gray-500">All makeup class requests have been processed.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
