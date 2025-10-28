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
                <div class="text-gray-500 text-lg">No approved makeup classes found.</div>
                <p class="text-gray-400 mt-2">Student confirmations will appear here once you have approved makeup classes.</p>
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
                            </div>
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                Approved
                            </span>
                        </div>

                        @if($request->confirmations->isEmpty())
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <p class="text-yellow-700">⏳ No student responses yet. Students will receive email notifications to confirm or decline attendance.</p>
                            </div>
                        @else
                            <div class="mt-4">
                                <h4 class="font-semibold mb-3 text-gray-700">Student Responses:</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($request->confirmations as $confirmation)
                                        <div class="border rounded-lg p-4 {{ $confirmation->status === 'confirmed' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="font-medium text-gray-900">{{ $confirmation->student->name }}</span>
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
                                                <p><strong>Email:</strong> {{ $confirmation->student->email }}</p>
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
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection