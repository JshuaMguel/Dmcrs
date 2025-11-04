@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-4 sm:p-6 lg:p-8 rounded-xl shadow-lg border border-gray-100">
    <!-- Header Section -->
    <div class="text-center mb-6 sm:mb-8">
        <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-ustpBlue rounded-full mb-4">
            <span class="text-lg sm:text-2xl text-white">📋</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-ustpBlue mb-2">Request Details</h2>
        <p class="text-sm sm:text-base text-gray-600">View your makeup class request information</p>
    </div>

    <!-- Request Information Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Tracking Number Card -->
        <div class="bg-gray-50 rounded-lg p-4 sm:p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                <span class="text-2xl mr-3">#️⃣</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Tracking No.</h3>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-ustpGold text-ustpBlue">
                {{ $request->tracking_number }}
            </span>
        </div>

        <!-- Subject Card -->
        <div class="bg-gray-50 rounded-lg p-4 sm:p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                <span class="text-xl sm:text-2xl mr-2 sm:mr-3">📖</span>
                <h3 class="text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wide">Subject</h3>
            </div>
            @if($request->subject && is_object($request->subject))
                <p class="text-base sm:text-lg font-semibold text-ustpBlue">{{ $request->subject->subject_code }} - {{ $request->subject->subject_title }}</p>
                @if(is_object($request->subject) && $request->subject->description)
                    <p class="text-xs sm:text-sm text-gray-600 mt-2 bg-blue-50 p-3 rounded border">{{ $request->subject->description }}</p>
                @endif
            @else
                <p class="text-base sm:text-lg font-semibold text-ustpBlue">{{ $request->subject }}</p>
                @if($request->subject_title)
                    <p class="text-xs sm:text-sm text-gray-600 mt-1">{{ $request->subject_title }}</p>
                @endif
            @endif
        </div>

        <!-- Room Card -->
        <div class="bg-gray-50 rounded-lg p-4 sm:p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                <span class="text-xl sm:text-2xl mr-2 sm:mr-3">🏫</span>
                <h3 class="text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wide">Room</h3>
            </div>
            <p class="text-base sm:text-lg font-semibold text-ustpBlue">{{ $request->room }}</p>
        </div>

        <!-- Date Card -->
        <div class="bg-gray-50 rounded-lg p-4 sm:p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                <span class="text-xl sm:text-2xl mr-2 sm:mr-3">📅</span>
                <h3 class="text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wide">Date</h3>
            </div>
            <p class="text-base sm:text-lg font-semibold text-ustpBlue">
                {{ \Carbon\Carbon::parse($request->preferred_date)->format('M d, Y') }}
            </p>
        </div>

        <!-- Time Card -->
        <div class="bg-gray-50 rounded-lg p-4 sm:p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                <span class="text-xl sm:text-2xl mr-2 sm:mr-3">🕐</span>
                <h3 class="text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wide">Time</h3>
            </div>
            <p class="text-base sm:text-lg font-semibold text-ustpBlue">
                {{ $request->preferred_time }} - {{ $request->end_time }}
            </p>
        </div>

        <!-- Status Card -->
        <div class="bg-gray-50 rounded-lg p-4 sm:p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                <span class="text-xl sm:text-2xl mr-2 sm:mr-3">📊</span>
                <h3 class="text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wide">Status</h3>
            </div>
            @php
                $statusConfig = [
                    'pending' => ['bg-yellow-100 text-yellow-800', 'Pending Review'],
                    'APPROVED' => ['bg-green-100 text-green-800', 'Approved'],
                    'CHAIR_APPROVED' => ['bg-blue-100 text-blue-800', 'Chair Approved'],
                    'HEAD_REJECTED' => ['bg-red-100 text-red-800', 'Declined'],
                    'CHAIR_REJECTED' => ['bg-red-100 text-red-800', 'Declined'],
                ];
                $config = $statusConfig[$request->status] ?? ['bg-gray-100 text-gray-800', ucfirst(str_replace('_', ' ', $request->status))];
            @endphp
            <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium {{ $config[0] }}">
                {{ $config[1] }}
            </span>
        </div>

        <!-- Section Card -->
        @if($request->sectionRelation)
        <div class="bg-gray-50 rounded-lg p-4 sm:p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                <span class="text-xl sm:text-2xl mr-2 sm:mr-3">👥</span>
                <h3 class="text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wide">Section</h3>
            </div>
            <p class="text-base sm:text-lg font-semibold text-ustpBlue">
                @if(is_object($request->sectionRelation))
                    {{ $request->sectionRelation->year_level ?? 'N/A' }}-{{ $request->sectionRelation->section_name ?? 'N/A' }}
                @else
                    {{ $request->section ?? 'N/A' }}
                @endif
            </p>
        </div>
        @endif
    </div>

    <!-- Reason Section -->
    <div class="bg-gray-50 rounded-lg p-4 sm:p-6 border border-gray-200 mb-6 sm:mb-8">
        <h3 class="text-base sm:text-lg font-semibold text-ustpBlue mb-4 flex items-center">
            <span class="mr-2">💭</span>
            Reason for Makeup Class
        </h3>
        <p class="text-sm sm:text-base text-gray-900 leading-relaxed">{{ $request->reason }}</p>
    </div>

    <!-- Files Section -->
    @if($request->attachment || $request->student_list)
    <div class="bg-gray-50 rounded-lg p-4 sm:p-6 border border-gray-200 mb-6 sm:mb-8">
        <h3 class="text-base sm:text-lg font-semibold text-ustpBlue mb-4 flex items-center">
            <span class="mr-2">📎</span>
            Attachments
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @if($request->attachment)
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <h4 class="font-semibold text-gray-700 mb-2">Supporting Document</h4>
                <a href="{{ asset('storage/' . $request->attachment) }}"
                   class="inline-flex items-center px-4 py-2 bg-ustpBlue text-white rounded-lg hover:bg-blue-800 transition-colors"
                   download>
                    <span class="mr-2">📄</span>
                    Download File
                </a>
            </div>
            @endif

            @if($request->student_list)
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <h4 class="font-semibold text-gray-700 mb-2">Student List</h4>
                <a href="{{ asset('storage/' . $request->student_list) }}"
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                   download>
                    <span class="mr-2">👥</span>
                    Download List
                </a>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 sm:gap-0">
        <a href="{{ route('makeup-requests.index') }}"
           class="inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold transition-all duration-200 text-sm sm:text-base">
            <span class="mr-2">←</span>
            Back to Requests
        </a>

        @if(in_array($request->status, ['pending', 'CHAIR_APPROVED']))
        <a href="{{ route('makeup-requests.edit', $request->id) }}"
           class="inline-flex items-center justify-center px-4 py-3 sm:p-3 bg-ustpBlue text-white rounded-lg hover:bg-blue-800 transition-all duration-200 text-sm sm:text-base"
           title="Edit Request">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 sm:mr-0 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            <span class="sm:hidden">Edit Request</span>
        </a>
        @endif
    </div>
</div>
@endsection
