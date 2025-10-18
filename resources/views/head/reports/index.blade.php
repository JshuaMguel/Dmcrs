@extends('layouts.app')

@section('title', 'Reports - USTP DMCRS')

@section('content')
<!-- USTP-Themed Reports & Logs Page -->
<div class="min-h-screen bg-gradient-to-br from-ustpGray to-white">
    <!-- Header Section with USTP Branding -->
    <div class="bg-gradient-to-r from-ustpBlue to-ustpBlue/90 text-white py-4 sm:py-6 px-4 sm:px-6 shadow-xl">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <div class="flex items-center">
                    <div class="bg-ustpGold/20 backdrop-blur-sm rounded-xl p-2 sm:p-3 mr-3 sm:mr-4">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-ustpGold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Reports & Analytics</h1>
                        <p class="text-ustpGold mt-1 text-base sm:text-lg">System Logs & Performance Data</p>
                    </div>
                </div>
                <div class="flex lg:hidden items-center space-x-4 w-full sm:w-auto">
                    <div class="bg-ustpGold/20 backdrop-blur-sm rounded-lg px-3 sm:px-4 py-2 text-center flex-1 sm:flex-none">
                        <div class="text-ustpGold text-xs sm:text-sm font-medium">Total Records</div>
                        <div class="text-white text-lg sm:text-xl font-bold">{{ count($reports) }}</div>
                    </div>
                </div>
                <div class="hidden lg:flex items-center space-x-4">
                    <div class="bg-ustpGold/20 backdrop-blur-sm rounded-lg px-4 py-2 text-center">
                        <div class="text-ustpGold text-sm font-medium">Total Records</div>
                        <div class="text-white text-xl font-bold">{{ count($reports) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
        <!-- Export Actions Card -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 p-4 sm:p-6 mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-3 sm:gap-0">
                <div>
                    <h3 class="text-lg sm:text-xl font-bold text-ustpBlue">Export Options</h3>
                    <p class="text-gray-600 text-xs sm:text-sm mt-1">Generate reports in various formats</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4">
                <a href="{{ route('head.reports.exportPdf') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium rounded-lg hover:from-emerald-600 hover:to-green-500 transition-all duration-200 shadow-md hover:shadow-lg group">
                    <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export PDF
                </a>
                <a href="{{ route('head.reports.exportExcel') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-ustpGold to-amber-500 text-white font-medium rounded-lg hover:from-amber-500 hover:to-ustpGold transition-all duration-200 shadow-md hover:shadow-lg group">
                    <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Excel
                </a>
                <a href="{{ route('head.reports.print') }}" target="_blank" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-ustpBlue to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-ustpBlue transition-all duration-200 shadow-md hover:shadow-lg group">
                    <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V4h12v5M6 18H5a2 2 0 01-2-2v-5a2 2 0 012-2h14a2 2 0 012 2v5a2 2 0 01-2 2h-1M6 14h12v4H6v-4z" />
                    </svg>
                    Print
                </a>
            </div>
        </div>
        <!-- Reports Data Table -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <!-- Table Header -->
            <div class="bg-gradient-to-r from-ustpBlue to-ustpBlue/90 px-6 py-4">
                <h3 class="text-xl font-bold text-white">Make-up Class Reports</h3>
                <p class="text-ustpGold text-sm mt-1">Complete history of processed requests</p>
            </div>

            <!-- Enhanced Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Faculty</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Tracking</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Schedule</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Approved</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            use App\Helpers\TimeHelper;
                        @endphp
                        @forelse($reports as $report)
                            <tr class="hover:bg-ustpGold/5 transition-all duration-200">
                                <!-- Faculty -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-ustpBlue to-ustpBlue/80 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                            {{ substr($report->faculty ?? 'U', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-ustpBlue">{{ $report->faculty ?? 'Unknown' }}</div>
                                            <div class="text-gray-500 text-sm">Faculty Member</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Tracking Number -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($report->tracking_number)
                                        <div class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-ustpGold/20 text-ustpBlue border border-ustpGold/30">
                                            {{ $report->tracking_number }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">N/A</span>
                                    @endif
                                </td>

                                <!-- Subject -->
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-ustpBlue">{{ $report->subject }}</div>
                                    @if($report->reason)
                                        <div class="text-gray-600 text-sm">{{ Str::limit($report->reason, 30) }}</div>
                                    @endif
                                </td>

                                <!-- Schedule -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm">
                                        @if($report->preferred_date)
                                            <div class="font-medium text-ustpBlue">{{ \Carbon\Carbon::parse($report->preferred_date)->format('M d, Y') }}</div>
                                        @endif
                                        <div class="text-gray-600">
                                            @if($report->preferred_time)
                                                {{ TimeHelper::formatTime($report->preferred_time) }}
                                            @endif
                                            @if($report->room)
                                                • {{ $report->room }}
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($report->final_status)
                                        @if(strtolower($report->final_status) === 'approved')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                                {{ ucfirst($report->final_status) }}
                                            </span>
                                        @elseif(strtolower($report->final_status) === 'rejected')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                                {{ ucfirst($report->final_status) }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                                <div class="w-2 h-2 bg-amber-400 rounded-full mr-2"></div>
                                                {{ ucfirst($report->final_status) }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-sm">Pending</span>
                                    @endif
                                </td>

                                <!-- Date Approved -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    @if($report->date_approved)
                                        <div class="flex flex-col">
                                            <span class="font-medium">{{ \Carbon\Carbon::parse($report->date_approved)->format('M d, Y') }}</span>
                                            <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($report->date_approved)->format('g:i A') }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">Not approved</span>
                                    @endif
                                </td>

                                <!-- Remarks -->
                                <td class="px-6 py-4">
                                    @if($report->remarks)
                                        <div class="text-gray-700 text-sm max-w-xs">
                                            {{ Str::limit($report->remarks, 50) }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm italic">No remarks</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">No Reports Available</h3>
                                        <p class="text-gray-500">No make-up class requests have been processed yet.</p>
                                        <div class="mt-4">
                                            <a href="{{ route('head.requests.index') }}" class="inline-flex items-center px-4 py-2 bg-ustpBlue text-white rounded-lg hover:bg-ustpBlue/90 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                                View Pending Requests
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
