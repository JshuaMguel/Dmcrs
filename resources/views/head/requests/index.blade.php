@extends('layouts.app')

@section('title', 'Academic Head Requests - USTP DMCRS')

@section('content')
<!-- USTP-Themed Academic Head Requests Page -->
<div class="min-h-screen bg-gradient-to-br from-ustpGray to-white">
    <!-- Header Section with USTP Branding -->
    <div class="bg-gradient-to-r from-ustpBlue to-ustpBlue/90 text-white py-4 sm:py-6 px-4 sm:px-6 shadow-xl">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Request Management</h1>
                    <p class="text-ustpGold mt-1 text-base sm:text-lg">Pending Make-up Class Approvals</p>
                </div>
                <div class="flex lg:hidden items-center space-x-4 w-full sm:w-auto">
                    <div class="bg-ustpGold/20 backdrop-blur-sm rounded-lg px-3 sm:px-4 py-2 text-center flex-1 sm:flex-none">
                        <div class="text-ustpGold text-xs sm:text-sm font-medium">Total Pending</div>
                        <div class="text-white text-lg sm:text-xl font-bold">{{ count($requests) }}</div>
                    </div>
                </div>
                <div class="hidden lg:flex items-center space-x-4">
                    <div class="bg-ustpGold/20 backdrop-blur-sm rounded-lg px-4 py-2 text-center">
                        <div class="text-ustpGold text-sm font-medium">Total Pending</div>
                        <div class="text-white text-xl font-bold">{{ count($requests) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
        <!-- Main Content Card -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <!-- Table Header -->
            <div class="bg-gradient-to-r from-ustpBlue to-ustpBlue/90 px-4 sm:px-6 py-3 sm:py-4">
                <h3 class="text-lg sm:text-xl font-bold text-white">Make-up Class Requests Awaiting Review</h3>
                <p class="text-ustpGold text-xs sm:text-sm mt-1">Review and approve faculty make-up class requests</p>
            </div>

            <!-- Enhanced Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Faculty</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Department</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Tracking #</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Subject</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider hidden md:table-cell">Section</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider hidden sm:table-cell">Reason</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider hidden md:table-cell">Room</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Date</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider hidden lg:table-cell">Time</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider hidden lg:table-cell">Attachment</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Status</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            use App\Helpers\TimeHelper;
                        @endphp
                        @forelse($requests as $request)
                            <tr class="hover:bg-ustpGold/5 transition-all duration-200 group">
                                <!-- Faculty -->
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-ustpBlue to-ustpBlue/80 rounded-full flex items-center justify-center text-white font-bold text-xs sm:text-sm mr-2 sm:mr-3">
                                            {{ substr($request->faculty->name ?? 'N', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-ustpBlue text-xs sm:text-sm">{{ $request->faculty->name ?? 'N/A' }}</div>
                                            <div class="text-gray-500 text-xs hidden sm:block">Faculty Member</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Department -->
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    @php
                                        $deptName = $request->faculty->department->name ?? ($request->subject && is_object($request->subject) && $request->subject->department ? $request->subject->department->name : null);
                                    @endphp
                                    @if($deptName)
                                        <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $deptName }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">—</span>
                                    @endif
                                </td>

                                <!-- Tracking Number -->
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <div class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-ustpGold/20 text-ustpBlue border border-ustpGold/30">
                                        <span class="hidden sm:inline">{{ $request->tracking_number }}</span>
                                        <span class="sm:hidden">{{ substr($request->tracking_number, -6) }}</span>
                                    </div>
                                </td>

                                <!-- Subject -->
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    @if($request->subject && is_object($request->subject))
                                        <div class="font-semibold text-ustpBlue text-xs sm:text-sm">{{ $request->subject->subject_code }}</div>
                                        <div class="text-gray-600 text-xs hidden sm:block" title="{{ $request->subject->subject_title }}">{{ Str::limit($request->subject->subject_title, 25) }}</div>
                                    @else
                                        <div class="font-semibold text-ustpBlue text-xs sm:text-sm">{{ $request->subject }}</div>
                                        @if($request->subject_title)
                                            <div class="text-gray-600 text-xs hidden sm:block">{{ $request->subject_title }}</div>
                                        @endif
                                    @endif
                                </td>

                                <!-- Section -->
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden md:table-cell">
                                    @if($request->section)
                                        @if(is_object($request->section))
                                        <div class="inline-flex items-center px-2 sm:px-2.5 py-1 rounded-lg text-xs sm:text-sm font-medium bg-ustpBlue/10 text-ustpBlue">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            {{ $request->section->year_level }}-{{ $request->section->section_name }}
                                        </div>
                                        @else
                                            <span class="text-gray-400 text-sm">{{ $request->section }}</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-sm">—</span>
                                    @endif
                                </td>

                                <!-- Reason -->
                                <td class="px-3 sm:px-6 py-3 sm:py-4 hidden sm:table-cell">
                                    <div class="text-sm text-gray-900 max-w-xs">
                                        <div class="truncate" title="{{ $request->reason }}">
                                            {{ Str::limit($request->reason, 50) }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Room -->
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden md:table-cell">
                                    <div class="inline-flex items-center px-2 sm:px-2.5 py-1 rounded-lg text-xs sm:text-sm font-medium bg-gray-100 text-gray-800">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        {{ $request->room }}
                                    </div>
                                </td>

                                <!-- Date -->
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <div class="text-xs sm:text-sm font-medium text-ustpBlue">
                                        <span class="hidden sm:inline">{{ \Carbon\Carbon::parse($request->preferred_date)->format('M d, Y') }}</span>
                                        <span class="sm:hidden">{{ \Carbon\Carbon::parse($request->preferred_date)->format('M d') }}</span>
                                    </div>
                                </td>

                                <!-- Time -->
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden lg:table-cell">
                                    <div class="text-sm text-gray-600">
                                        {{ TimeHelper::formatTime($request->preferred_time) }} - {{ TimeHelper::formatTime($request->end_time) }}
                                    </div>
                                </td>

                                <!-- Attachment -->
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-center hidden lg:table-cell">
                                    @if($request->attachment)
                                        <a href="{{ asset('storage/' . $request->attachment) }}"
                                           class="inline-flex items-center text-ustpBlue hover:text-ustpGold transition-colors"
                                           download title="Download attachment">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-sm">—</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    @if($request->status === 'PENDING_HEAD_APPROVAL')
                                        <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                            <div class="w-2 h-2 bg-amber-400 rounded-full mr-1 sm:mr-2 animate-pulse"></div>
                                            <span class="hidden sm:inline">Pending Review</span>
                                            <span class="sm:hidden">Pending</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-1 sm:space-x-2">
                                        <a href="{{ route('head.requests.show', $request->id) }}"
                                           class="inline-flex items-center px-2 sm:px-4 py-2 bg-gradient-to-r from-ustpGold to-amber-500 text-white text-xs sm:text-sm font-medium rounded-lg hover:from-amber-500 hover:to-ustpGold transition-all duration-200 shadow-md hover:shadow-lg group">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 sm:mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <span class="hidden sm:inline">Review</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">No Pending Requests</h3>
                                        <p class="text-gray-500">All make-up class requests have been processed.</p>
                                        <div class="mt-4">
                                            <a href="{{ route('head.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-ustpBlue text-white rounded-lg hover:bg-ustpBlue/90 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9l10-6"></path>
                                                </svg>
                                                Return to Dashboard
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
