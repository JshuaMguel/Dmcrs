@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-ustpBlue">Student Confirmations</h1>
        </div>

        @if($makeupRequests->isEmpty())
            <div class="text-center py-8">
                <div class="text-gray-500 text-lg">No makeup class requests found.</div>
                <p class="text-gray-400 mt-2">Create a makeup class request to see student confirmations here.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($makeupRequests as $request)
                    <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-ustpBlue">{{ $request->subject }}</h3>
                                <p class="text-gray-600">{{ $request->subject_title }}</p>
                                <div class="flex items-center mt-2 text-sm text-gray-500">
                                    <span class="mr-4">📅 {{ \Carbon\Carbon::parse($request->preferred_date)->format('M d, Y') }}</span>
                                    <span class="mr-4">🕐 {{ \App\Helpers\TimeHelper::formatTime($request->preferred_time) }} - {{ \App\Helpers\TimeHelper::formatTime($request->end_time) }}</span>
                                    <span>🏠 {{ $request->room }}</span>
                                </div>
                                @if($request->tracking_number)
                                    <div class="text-xs text-gray-400 mt-1">Tracking: {{ $request->tracking_number }}</div>
                                @endif
                            </div>
                            @if($request->status === 'pending')
                                @if($request->submitted_to_chair_at)
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                        📤 Submitted to Department Chair
                                    </span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                        ⏳ Pending Student Confirmation
                                    </span>
                                @endif
                            @elseif($request->status === 'APPROVED')
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                    ✅ Approved
                                </span>
                            @elseif($request->status === 'CHAIR_APPROVED')
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                    📋 Chair Approved - Waiting for Academic Head
                                </span>
                            @else
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $request->status }}
                                </span>
                            @endif
                        </div>

                        @if($request->confirmations->isEmpty())
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <p class="text-yellow-700">⏳ No student responses yet. Students will receive email notifications to confirm or decline attendance.</p>
                                @if($request->status === 'pending')
                                    <p class="text-yellow-600 text-sm mt-2">Please wait for at least 1 student to confirm before submitting the official request.</p>
                                @endif
                            </div>
                        @else
                            <div class="mt-4">
                                <h4 class="font-semibold mb-3 text-gray-700">Student Responses:</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($request->confirmations as $confirmation)
                                        <div class="border rounded-lg p-4 {{ $confirmation->status === 'confirmed' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                                            <div class="flex items-center justify-between mb-2">
                                                <div>
                                                    <span class="font-medium text-gray-900">
                                                        {{ $confirmation->student_name ?? ($confirmation->student ? $confirmation->student->name : explode('@', $confirmation->student_email)[0]) }}
                                                    </span>
                                                    @if($confirmation->student_id_number)
                                                        <div class="text-sm text-gray-600 font-mono">
                                                            ID: {{ $confirmation->student_id_number }}
                                                        </div>
                                                    @endif
                                                </div>
                                                @if($confirmation->status === 'confirmed')
                                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                                        ✅ Confirmed
                                                    </span>
                                                @else
                                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">
                                                        ❌ Declined
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <div class="text-sm text-gray-600">
                                                <p><strong>Email:</strong> {{ $confirmation->student ? $confirmation->student->email : $confirmation->student_email }}</p>
                                                <p><strong>Response Date:</strong> {{ $confirmation->confirmation_date ? $confirmation->confirmation_date->format('M d, Y g:i A') : 'N/A' }}</p>
                                                @if($confirmation->status === 'confirmed')
                                                    <p class="text-green-700 mt-2"><strong>Status:</strong> Will attend</p>
                                                @else
                                                    <p class="text-red-700 mt-2"><strong>Reason:</strong> {{ $confirmation->reason ?: 'No reason provided' }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Summary -->
                                <div class="mt-4 bg-white border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h5 class="font-semibold text-gray-700 mb-2">Summary:</h5>
                                            <div class="flex space-x-6 text-sm">
                                                @php
                                                    $confirmed = $request->confirmations->where('status', 'confirmed')->count();
                                                    $declined = $request->confirmations->where('status', 'declined')->count();
                                                    $total = $request->confirmations->count();
                                                @endphp
                                                <span class="text-green-600">✅ Confirmed: {{ $confirmed }}</span>
                                                <span class="text-red-600">❌ Declined: {{ $declined }}</span>
                                                <span class="text-gray-600">📊 Total Responses: {{ $total }}</span>
                                            </div>
                                        </div>
                                        
                                        @if($request->status === 'pending')
                                            <div class="flex flex-col items-end">
                                                @if($request->submitted_to_chair_at)
                                                    <!-- Already Submitted -->
                                                    <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4 text-center">
                                                        <div class="flex items-center justify-center mb-2">
                                                            <span class="text-2xl mr-2">✅</span>
                                                            <span class="font-bold text-blue-800">Official Request Submitted</span>
                                                        </div>
                                                        <p class="text-sm text-blue-700 mb-1">
                                                            Submitted to Department Chair on:
                                                        </p>
                                                        <p class="text-sm font-semibold text-blue-900">
                                                            {{ \Carbon\Carbon::parse($request->submitted_to_chair_at)->format('M d, Y g:i A') }}
                                                        </p>
                                                        <p class="text-xs text-blue-600 mt-2">
                                                            Waiting for Department Chair approval...
                                                        </p>
                                                    </div>
                                                @elseif($confirmed >= 1)
                                                    <!-- Ready to Submit -->
                                                    <form action="{{ route('makeup-requests.submit-official', $request->id) }}" method="POST" class="mb-2">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="bg-ustpBlue hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg"
                                                                onclick="return confirm('Are you sure you want to submit this official request to the Department Chair? This action cannot be undone.')">
                                                            📤 Submit Official Request
                                                        </button>
                                                    </form>
                                                    <p class="text-xs text-gray-500 text-right">Ready to submit ({{ $confirmed }} confirmed)</p>
                                                @else
                                                    <!-- Waiting for Confirmations -->
                                                    <button type="button" 
                                                            class="bg-gray-400 text-white font-bold py-2 px-6 rounded-lg cursor-not-allowed"
                                                            disabled>
                                                        ⏳ Waiting for Confirmations
                                                    </button>
                                                    <p class="text-xs text-red-500 text-right mt-1">Need at least 1 confirmed student</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection