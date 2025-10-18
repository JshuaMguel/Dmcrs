
@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>

                        <h2 class="mt-4 text-2xl font-bold text-gray-900">Attendance Declined</h2>

                        <div class="mt-4 text-gray-600">
                            <p>You have declined attendance for:</p>
                            <div class="mt-4">
                                <p><strong>Subject:</strong> {{ $makeupRequest->subject }}</p>
                                <p><strong>Date:</strong> {{ $makeupRequest->preferred_date }}</p>
                                <p><strong>Time:</strong> {{ $makeupRequest->preferred_time }}</p>
                                <p><strong>Room:</strong> {{ $makeupRequest->room }}</p>
                            </div>
                        </div>

                        <p class="mt-4 text-sm text-gray-500">Your response has been recorded. Thank you for letting us know.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
