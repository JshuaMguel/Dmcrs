@extends('layouts.app')

@section('title', 'View Request - USTP DMCRS')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg">
    <!-- Header Section -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-ustpBlue rounded-full mb-4">
            <span class="text-2xl text-white">📋</span>
        </div>
        <h2 class="text-3xl font-bold text-ustpBlue mb-2">Request Details</h2>
        <p class="text-gray-600">Review makeup class request information</p>
    </div>

    <!-- Request Information Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Faculty Card -->
        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                <span class="text-2xl mr-3">👨‍🏫</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Faculty</h3>
            </div>
            <p class="text-lg font-semibold text-ustpBlue">{{ $request->faculty->name }}</p>
        </div>

        <!-- Department Card -->
        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                <span class="text-2xl mr-3">🏷️</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Department</h3>
            </div>
            @php
                $deptName = $request->faculty->department->name ?? ($request->subject && is_object($request->subject) && $request->subject->department ? $request->subject->department->name : null);
            @endphp
            <p class="text-lg font-semibold text-ustpBlue">{{ $deptName ?? 'N/A' }}</p>
        </div>

        <!-- Subject Card -->
        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                <span class="text-2xl mr-3">📖</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Subject</h3>
            </div>
            @if($request->subject && is_object($request->subject))
                <p class="text-lg font-semibold text-ustpBlue">
                    {{ $request->subject->subject_code }} - {{ $request->subject->subject_title }}
                </p>
                @if($request->subject->description)
                    <p class="text-sm text-gray-600 mt-2">{{ $request->subject->description }}</p>
                @endif
            @else
                <p class="text-lg font-semibold text-ustpBlue">
                    {{ $request->subject }}{{ $request->subject_title ? ' - ' . $request->subject_title : '' }}
                </p>
            @endif
        </div>

        <!-- Tracking Number Card -->
        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                <span class="text-2xl mr-3">#️⃣</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Tracking No.</h3>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-ustpGold text-ustpBlue">
                {{ $request->tracking_number }}
            </span>
        </div>

        <!-- Date Card -->
        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                <span class="text-2xl mr-3">📅</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Date</h3>
            </div>
            <p class="text-lg font-semibold text-ustpBlue">
                {{ \Carbon\Carbon::parse($request->preferred_date)->format('M d, Y') }}
            </p>
        </div>

        <!-- Time Card -->
        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                <span class="text-2xl mr-3">🕐</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Time</h3>
            </div>
            <p class="text-lg font-semibold text-ustpBlue">
                {{ $request->preferred_time }} - {{ $request->end_time }}
            </p>
        </div>

        <!-- Room Card -->
        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                <span class="text-2xl mr-3">🏫</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Room</h3>
            </div>
            <p class="text-lg font-semibold text-ustpBlue">{{ $request->room }}</p>
        </div>

        <!-- Section Card -->
        @if($request->sectionRelation)
        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                <span class="text-2xl mr-3">👥</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Section</h3>
            </div>
            <p class="text-lg font-semibold text-ustpBlue">
                {{ $request->sectionRelation->year_level ?? 'N/A' }}-{{ $request->sectionRelation->section_name ?? 'N/A' }}
            </p>
        </div>
        @endif

        <!-- Status Card -->
        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                <span class="text-2xl mr-3">📊</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Status</h3>
            </div>
            @php
                $statusColors = [
                    'approved' => 'bg-green-100 text-green-800',
                    'rejected' => 'bg-red-100 text-red-800',
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'completed' => 'bg-blue-100 text-blue-800'
                ];
                $colorClass = $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800';
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $colorClass }}">
                {{ ucfirst($request->status) }}
            </span>
        </div>

        <!-- Reason Card -->
        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 md:col-span-2">
            <div class="flex items-center mb-3">
                <span class="text-2xl mr-3">💭</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Reason</h3>
            </div>
            <p class="text-gray-900">{{ $request->reason }}</p>
        </div>
    </div>

    <!-- Files Section -->
    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-8">
        <h3 class="text-lg font-semibold text-ustpBlue mb-4 flex items-center">
            <span class="mr-2">📎</span>
            Attachments
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Main Attachment -->
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <h4 class="font-semibold text-gray-700 mb-2">Request Attachment</h4>
                @if($request->attachment)
                    <a href="{{ asset('storage/'.$request->attachment) }}" target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-ustpBlue text-white rounded-lg hover:bg-blue-800 transition-colors">
                        <span class="mr-2">📄</span>
                        Download File
                    </a>
                @else
                    <span class="text-gray-500 text-sm">No attachment provided</span>
                @endif
            </div>

            <!-- Student List -->
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <h4 class="font-semibold text-gray-700 mb-2">Student List</h4>
                @if($request->student_list)
                    <a href="{{ asset('storage/' . $request->student_list) }}" target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <span class="mr-2">👥</span>
                        Download List
                    </a>
                @else
                    <span class="text-gray-500 text-sm">No student list provided</span>
                @endif
            </div>
        </div>
    </div>

    @if($request->status === 'pending')
        <!-- Action Section -->
        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-ustpBlue mb-6 flex items-center">
                <span class="mr-2">⚙️</span>
                Take Action
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Approve Form -->
                <div class="bg-white rounded-lg p-6 border-2 border-green-200">
                    <div class="flex items-center mb-4">
                        <span class="text-2xl mr-3">✅</span>
                        <h4 class="text-lg font-semibold text-green-700">Approve Request</h4>
                    </div>
                    <form method="POST" action="{{ route('department.chair.approve', $request->id) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Remarks (optional)
                            </label>
                            <textarea name="remarks" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                      placeholder="Add any remarks or notes..."></textarea>
                        </div>
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                            <span class="mr-2">✅</span>
                            Approve Request
                        </button>
                    </form>
                </div>

                <!-- Reject Form -->
                <div class="bg-white rounded-lg p-6 border-2 border-red-200">
                    <div class="flex items-center mb-4">
                        <span class="text-2xl mr-3">❌</span>
                        <h4 class="text-lg font-semibold text-red-700">Reject Request</h4>
                    </div>
                    <form method="POST" action="{{ route('department.chair.reject', $request->id) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="text-red-500">*</span> Remarks (required for rejection)
                            </label>
                            <textarea name="remarks" rows="3" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200"
                                      placeholder="Please provide reason for rejection..."></textarea>
                        </div>
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                            <span class="mr-2">❌</span>
                            Reject Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Back Button -->
    <div class="flex justify-center mt-8">
        <a href="{{ route('department.requests') }}"
           class="inline-flex items-center px-6 py-3 bg-ustpBlue text-white rounded-lg hover:bg-blue-800 font-semibold transition-all duration-200">
            <span class="mr-2">←</span>
            Back to Requests
        </a>
    </div>
    </div>
@endsection
