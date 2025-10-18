@extends('layouts.app')

@section('title', 'Academic Head Dashboard - USTP DMCRS')

@section('content')
<!-- USTP-Themed Academic Head Dashboard -->
<div class="min-h-screen bg-gradient-to-br from-ustpGray to-white">
    <!-- Header Section with USTP Branding -->
    <div class="bg-gradient-to-r from-ustpBlue to-ustpBlue/90 text-white py-4 sm:py-6 lg:py-8 px-4 sm:px-6 shadow-xl">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight">Academic Head Dashboard</h1>
                    <p class="text-ustpGold mt-2 text-base sm:text-lg">Department Make-up Class Request System</p>
                    <p class="text-blue-100 mt-1 text-sm sm:text-base">University of Science & Technology of Southern Philippines</p>
                </div>
                <div class="flex lg:hidden items-center space-x-4 w-full sm:w-auto">
                    <div class="bg-ustpGold/20 backdrop-blur-sm rounded-lg p-3 sm:p-4 text-center flex-1 sm:flex-none">
                        <div class="text-ustpGold text-xs sm:text-sm font-medium">Current Date</div>
                        <div class="text-white text-base sm:text-lg font-bold">{{ date('M d, Y') }}</div>
                    </div>
                </div>
                <div class="hidden lg:flex items-center space-x-4">
                    <div class="bg-ustpGold/20 backdrop-blur-sm rounded-lg p-4 text-center">
                        <div class="text-ustpGold text-sm font-medium">Current Date</div>
                        <div class="text-white text-lg font-bold">{{ date('M d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
        <!-- Statistics Cards with USTP Theme -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <!-- Pending Requests Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="bg-gradient-to-r from-amber-500 to-ustpGold p-1">
                    <div class="bg-white rounded-xl p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-ustpBlue">{{ $pendingCount }}</div>
                                <div class="text-gray-600 font-medium mt-1">Pending Requests</div>
                                <div class="text-sm text-gray-500 mt-1">Awaiting Review</div>
                            </div>
                            <div class="bg-gradient-to-br from-amber-500 to-ustpGold p-4 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approved Requests Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-1">
                    <div class="bg-white rounded-xl p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-ustpBlue">{{ $approvedCount }}</div>
                                <div class="text-gray-600 font-medium mt-1">Approved Requests</div>
                                <div class="text-sm text-gray-500 mt-1">Successfully Processed</div>
                            </div>
                            <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-4 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rejected Requests Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-red-600 p-1">
                    <div class="bg-white rounded-xl p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-ustpBlue">{{ $rejectedCount }}</div>
                                <div class="text-gray-600 font-medium mt-1">Rejected Requests</div>
                                <div class="text-sm text-gray-500 mt-1">Declined Applications</div>
                            </div>
                            <div class="bg-gradient-to-br from-red-500 to-red-600 p-4 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Latest Pending Requests Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <!-- Section Header -->
            <div class="bg-gradient-to-r from-ustpBlue to-ustpBlue/90 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white">Latest Pending Requests</h3>
                        <p class="text-ustpGold text-sm mt-1">Requires immediate attention</p>
                    </div>
                    <div class="bg-ustpGold/20 backdrop-blur-sm rounded-lg px-3 py-1">
                        <span class="text-ustpGold text-sm font-medium">{{ count($latestPending) }} Total</span>
                    </div>
                </div>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Faculty</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Tracking #</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Reason</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Room</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Time</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            use App\Helpers\TimeHelper;
                        @endphp
                        @forelse($latestPending as $request)
                            <tr class="hover:bg-ustpGold/5 transition-colors duration-200">
                                <!-- Faculty -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-br from-ustpBlue to-ustpBlue/80 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                            {{ substr($request->faculty->name ?? 'N', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-ustpBlue">{{ $request->faculty->name ?? 'N/A' }}</div>
                                            <div class="text-gray-500 text-sm">Faculty Member</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Tracking Number -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-ustpGold/20 text-ustpBlue border border-ustpGold/30">
                                        {{ $request->tracking_number }}
                                    </div>
                                </td>

                                <!-- Subject -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($request->subject && is_object($request->subject))
                                        <div class="font-semibold text-ustpBlue">{{ $request->subject->subject_code }}</div>
                                        <div class="text-gray-600 text-sm">{{ $request->subject->subject_title }}</div>
                                    @else
                                        <div class="font-semibold text-ustpBlue">{{ $request->subject }}</div>
                                        @if($request->subject_title)
                                            <div class="text-gray-600 text-sm">{{ $request->subject_title }}</div>
                                        @endif
                                    @endif
                                </td>

                                <!-- Reason -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs">
                                        <div class="truncate" title="{{ $request->reason }}">
                                            {{ Str::limit($request->reason, 40) }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Room -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-medium bg-gray-100 text-gray-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        {{ $request->room }}
                                    </div>
                                </td>

                                <!-- Date -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-ustpBlue">
                                        {{ \Carbon\Carbon::parse($request->preferred_date)->format('M d, Y') }}
                                    </div>
                                </td>

                                <!-- Time -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">
                                        {{ TimeHelper::formatTime($request->preferred_time) }} - {{ TimeHelper::formatTime($request->end_time) }}
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('head.requests.show', $request->id) }}"
                                       class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-ustpGold to-amber-500 text-white text-sm font-medium rounded-lg hover:from-amber-500 hover:to-ustpGold transition-all duration-200 shadow-md hover:shadow-lg">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-gray-500 text-lg font-medium">No pending requests</p>
                                        <p class="text-gray-400 text-sm mt-1">All requests have been processed</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <h4 class="text-lg font-bold text-ustpBlue mb-4">Quick Actions</h4>
                <div class="space-y-3">
                    <a href="{{ route('head.requests.index') }}" class="flex items-center justify-between p-3 rounded-lg border border-ustpGold/20 hover:bg-ustpGold/5 transition-colors duration-200 group">
                        <div class="flex items-center">
                            <div class="bg-ustpGold/20 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-ustpBlue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-700 group-hover:text-ustpBlue">View All Requests</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-ustpGold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('head.schedule.board') }}" class="flex items-center justify-between p-3 rounded-lg border border-ustpGold/20 hover:bg-ustpGold/5 transition-colors duration-200 group">
                        <div class="flex items-center">
                            <div class="bg-ustpGold/20 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-ustpBlue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-700 group-hover:text-ustpBlue">Schedule Board</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-ustpGold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <h4 class="text-lg font-bold text-ustpBlue mb-4">System Status</h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Total Requests</span>
                        <span class="font-bold text-ustpBlue">{{ $pendingCount + $approvedCount + $rejectedCount }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Approval Rate</span>
                        <span class="font-bold text-green-600">
                            {{ $approvedCount + $rejectedCount > 0 ? round(($approvedCount / ($approvedCount + $rejectedCount)) * 100) : 0 }}%
                        </span>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <div class="text-sm text-gray-500 text-center">
                            Last updated: {{ date('g:i A') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
