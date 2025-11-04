@extends('layouts.app')

@section('title', 'Review Request - USTP DMCRS')

@section('content')
<!-- USTP-Themed Request Detail Page -->
<div class="min-h-screen bg-gradient-to-br from-ustpGray to-white">
    <!-- Header Section with USTP Branding -->
    <div class="bg-gradient-to-r from-ustpBlue to-ustpBlue/90 text-white py-4 sm:py-6 px-4 sm:px-6 shadow-xl">
        <div class="max-w-4xl mx-auto">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <div class="flex items-center">
                    <div class="bg-ustpGold/20 backdrop-blur-sm rounded-xl p-2 sm:p-3 mr-3 sm:mr-4">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-ustpGold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Request Review</h1>
                        <p class="text-ustpGold mt-1 text-base sm:text-lg">Make-up Class Request Details</p>
                    </div>
                </div>
                <div class="flex lg:hidden items-center space-x-4 w-full sm:w-auto">
                    <div class="bg-ustpGold/20 backdrop-blur-sm rounded-lg px-3 sm:px-4 py-2 text-center flex-1 sm:flex-none">
                        <div class="text-ustpGold text-xs sm:text-sm font-medium">Tracking #</div>
                        <div class="text-white text-base sm:text-lg font-bold">{{ $request->tracking_number }}</div>
                    </div>
                </div>
                <div class="hidden lg:flex items-center space-x-4">
                    <div class="bg-ustpGold/20 backdrop-blur-sm rounded-lg px-4 py-2 text-center">
                        <div class="text-ustpGold text-sm font-medium">Tracking #</div>
                        <div class="text-white text-lg font-bold">{{ $request->tracking_number }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
        <!-- Error Messages -->
        @if($errors->has('room'))
            <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $errors->first('room') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif
        <!-- Request Details Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-ustpBlue to-ustpBlue/90 px-6 py-4">
                <h3 class="text-xl font-bold text-white">Request Information</h3>
                <p class="text-ustpGold text-sm mt-1">Complete details of the make-up class request</p>
            </div>

            <!-- Request Details Grid -->
            <div class="p-6">
                @php
                    use App\Helpers\TimeHelper;
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Faculty -->
                    <div class="bg-gradient-to-br from-ustpGold/10 to-ustpGold/5 rounded-xl p-4 border border-ustpGold/20">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-ustpBlue rounded-lg flex items-center justify-center mr-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-ustpBlue text-sm">Faculty Member</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $request->faculty->name ?? 'N/A' }}</span>
                    </div>

                    <!-- Department -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center mr-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-purple-700 text-sm">Department</span>
                        </div>
                        @php
                            $deptName = $request->faculty->department->name ?? ($request->subject && is_object($request->subject) && $request->subject->department ? $request->subject->department->name : null);
                        @endphp
                        <span class="text-lg font-bold text-gray-900">{{ $deptName ?? 'N/A' }}</span>
                    </div>

                    <!-- Subject -->
                    <div class="bg-gradient-to-br from-ustpBlue/10 to-ustpBlue/5 rounded-xl p-4 border border-ustpBlue/20">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-ustpGold rounded-lg flex items-center justify-center mr-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-ustpBlue text-sm">Subject</span>
                        </div>
                        @if($request->subject && is_object($request->subject))
                            <span class="text-lg font-bold text-gray-900">
                                {{ $request->subject->subject_code }} - {{ $request->subject->subject_title }}
                            </span>
                            @if($request->subject->description)
                                <div class="text-sm text-gray-600 mt-2">{{ $request->subject->description }}</div>
                            @endif
                        @else
                            <span class="text-lg font-bold text-gray-900">{{ $request->subject }}</span>
                            @if($request->subject_title)
                                <div class="text-sm text-gray-600 mt-1">{{ $request->subject_title }}</div>
                            @endif
                        @endif
                    </div>

                    <!-- Room -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center mr-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-green-700 text-sm">Room</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $request->room }}</span>
                    </div>

                    <!-- Schedule -->
                    <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl p-4 border border-purple-200">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center mr-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-purple-700 text-sm">Schedule</span>
                        </div>
                        <div class="space-y-1">
                            <div class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($request->preferred_date)->format('M d, Y') }}</div>
                            <div class="text-sm text-gray-600">{{ TimeHelper::formatTime($request->preferred_time) }} - {{ TimeHelper::formatTime($request->end_time) }}</div>
                        </div>
                    </div>

                    <!-- Section -->
                    @if($request->sectionRelation)
                        <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl p-4 border border-orange-200">
                            <div class="flex items-center mb-2">
                                <div class="w-8 h-8 bg-orange-600 rounded-lg flex items-center justify-center mr-2">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <span class="font-semibold text-orange-700 text-sm">Section</span>
                            </div>
                            <span class="text-lg font-bold text-gray-900">
                                @if(is_object($request->sectionRelation))
                                    {{ $request->sectionRelation->year_level ?? 'N/A' }}-{{ $request->sectionRelation->section_name ?? 'N/A' }}
                                @else
                                    {{ $request->section ?? 'N/A' }}
                                @endif
                            </span>
                        </div>
                    @endif                    <!-- Tracking Number -->
                    <div class="bg-gradient-to-br from-ustpGold/10 to-ustpGold/5 rounded-xl p-4 border border-ustpGold/20">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-ustpBlue rounded-lg flex items-center justify-center mr-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-ustpBlue text-sm">Tracking Number</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $request->tracking_number }}</span>
                    </div>
                </div>

                <!-- Reason Section -->
                <div class="mt-6 bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-ustpBlue mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-semibold text-ustpBlue">Reason for Make-up Class</span>
                    </div>
                    <p class="text-gray-700 leading-relaxed">{{ $request->reason }}</p>
                </div>

                <!-- Attachments Section -->
                @if($request->attachment || $request->student_list)
                    <div class="mt-6 bg-blue-50 rounded-xl p-4 border border-blue-200">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-ustpBlue mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            <span class="font-semibold text-ustpBlue">Attachments</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($request->attachment)
                                <a href="{{ asset('storage/' . $request->attachment) }}" class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors duration-200" download>
                                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div>
                                        <div class="font-medium text-gray-900">Request Attachment</div>
                                        <div class="text-sm text-gray-500">Click to download</div>
                                    </div>
                                </a>
                            @endif
                            @if($request->student_list)
                                <a href="{{ asset('storage/' . $request->student_list) }}" class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors duration-200" download>
                                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"></path>
                                    </svg>
                                    <div>
                                        <div class="font-medium text-gray-900">Student List</div>
                                        <div class="text-sm text-gray-500">CSV/Excel file</div>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Chair Remarks Section -->
                @if($request->chair_remarks)
                    <div class="mt-6 bg-green-50 rounded-xl p-4 border border-green-200">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                            <span class="font-semibold text-green-700">Department Chair Remarks</span>
                        </div>
                        <p class="text-gray-700 leading-relaxed">{{ $request->chair_remarks }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Approval Status Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-ustpBlue to-ustpBlue/90 px-6 py-4">
                <h3 class="text-xl font-bold text-white">Approval Status</h3>
                <p class="text-ustpGold text-sm mt-1">Track the approval process across departments</p>
            </div>

            <!-- Status Grid -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Department Chair Status -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Department Chair</h4>
                                    <p class="text-sm text-gray-600">First Level Approval</p>
                                </div>
                            </div>
                        </div>
                        @if($request->chair_status === 'pending')
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-400 rounded-full mr-2 animate-pulse"></div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    Pending Review
                                </span>
                            </div>
                        @elseif($request->chair_status === 'approved')
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    ✓ Approved
                                </span>
                            </div>
                            @if($request->chair_approved_at)
                                <p class="text-xs text-gray-500 mt-2">{{ \Carbon\Carbon::parse($request->chair_approved_at)->format('M d, Y g:i A') }}</p>
                            @endif
                        @elseif($request->chair_status === 'rejected')
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    ✗ Rejected
                                </span>
                            </div>
                            @if($request->chair_rejected_at)
                                <p class="text-xs text-gray-500 mt-2">{{ \Carbon\Carbon::parse($request->chair_rejected_at)->format('M d, Y g:i A') }}</p>
                            @endif
                        @endif
                    </div>

                    <!-- Academic Head Status -->
                    <div class="bg-gradient-to-br from-ustpGold/10 to-ustpGold/5 rounded-xl p-6 border border-ustpGold/20">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-ustpBlue rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Academic Head</h4>
                                    <p class="text-sm text-gray-600">Final Approval</p>
                                </div>
                            </div>
                        </div>
                        @if($request->head_status === 'pending')
                            @if($request->chair_status === 'approved')
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-yellow-400 rounded-full mr-2 animate-pulse"></div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Awaiting Review
                                    </span>
                                </div>
                            @else
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-gray-400 rounded-full mr-2"></div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700">
                                        Waiting for Chair
                                    </span>
                                </div>
                            @endif
                        @elseif($request->head_status === 'approved')
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    ✓ Approved
                                </span>
                            </div>
                            @if($request->head_approved_at)
                                <p class="text-xs text-gray-500 mt-2">{{ \Carbon\Carbon::parse($request->head_approved_at)->format('M d, Y g:i A') }}</p>
                            @endif
                        @elseif($request->head_status === 'rejected')
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    ✗ Rejected
                                </span>
                            </div>
                            @if($request->head_rejected_at)
                                <p class="text-xs text-gray-500 mt-2">{{ \Carbon\Carbon::parse($request->head_rejected_at)->format('M d, Y g:i A') }}</p>
                            @endif
                        @endif
                    </div>

                    <!-- Overall Status -->
                    <div class="bg-gradient-to-br from-gray-50 to-slate-50 rounded-xl p-6 border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-600 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Overall Status</h4>
                                    <p class="text-sm text-gray-600">Current State</p>
                                </div>
                            </div>
                        </div>
                        @if($request->status === 'pending')
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-400 rounded-full mr-2 animate-pulse"></div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    In Progress
                                </span>
                            </div>
                        @elseif($request->status === 'approved')
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    ✓ Fully Approved
                                </span>
                            </div>
                        @elseif($request->status === 'rejected')
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    ✗ Rejected
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-300 text-red-900 p-4 rounded-lg mb-6 flex items-center gap-2">
            <span>{{ session('error') }}</span>
        </div>
    @endif

        <!-- Action Forms -->
        @if($request->status === 'CHAIR_APPROVED' || $request->status === 'pending')
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-ustpBlue to-ustpBlue/90 px-6 py-4">
                    <h3 class="text-xl font-bold text-white">Academic Head Decision</h3>
                    <p class="text-ustpGold text-sm mt-1">Review and make your decision on this request</p>
                </div>

                <!-- Action Forms -->
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Approve Form -->
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-green-800">Approve Request</h4>
                                    <p class="text-sm text-green-600">Grant approval for this make-up class</p>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('head.requests.approve', $request->id) }}" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="approve_remarks" class="block text-sm font-semibold text-green-800 mb-2">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        Approval Remarks
                                    </label>
                                    <textarea
                                        name="remarks"
                                        id="approve_remarks"
                                        rows="4"
                                        class="w-full px-4 py-3 border border-green-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 resize-none"
                                        placeholder="Add your remarks or approval notes (optional)..."
                                    ></textarea>
                                </div>

                                <button
                                    type="submit"
                                    class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center justify-center"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Approve Request
                                </button>
                            </form>
                        </div>

                        <!-- Reject Form -->
                        <div class="bg-gradient-to-br from-red-50 to-rose-50 rounded-2xl p-6 border border-red-200">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-red-800">Reject Request</h4>
                                    <p class="text-sm text-red-600">Decline this make-up class request</p>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('head.requests.reject', $request->id) }}" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="reject_remarks" class="block text-sm font-semibold text-red-800 mb-2">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        Rejection Reason <span class="text-red-600">*</span>
                                    </label>
                                    <textarea
                                        name="remarks"
                                        id="reject_remarks"
                                        rows="4"
                                        required
                                        class="w-full px-4 py-3 border border-red-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 resize-none"
                                        placeholder="Please provide a clear reason for rejection (required)..."
                                    ></textarea>
                                </div>

                                <button
                                    type="submit"
                                    class="w-full bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center justify-center"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Reject Request
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Request Already Processed -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4">
                    <h3 class="text-xl font-bold text-white">Request Status</h3>
                    <p class="text-gray-200 text-sm mt-1">This request has been processed</p>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-center py-8">
                        <div class="text-center">
                            @if($request->status === 'approved')
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-green-800 mb-2">Request Approved</h4>
                                <p class="text-gray-600">This make-up class request has been approved and processed.</p>
                            @elseif($request->status === 'rejected')
                                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-red-800 mb-2">Request Rejected</h4>
                                <p class="text-gray-600">This make-up class request has been rejected.</p>
                            @else
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-gray-800 mb-2">Request Processed</h4>
                                <p class="text-gray-600">Current status: <strong class="text-ustpBlue">{{ ucfirst($request->status) }}</strong></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
</div>
@endsection
