
@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold mb-4">Decline Make-up Class Attendance</h2>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold">Make-up Class Details:</h3>
                        <p><strong>Subject:</strong> {{ $makeupRequest->subject }}</p>
                        <p><strong>Date:</strong> {{ $makeupRequest->preferred_date }}</p>
                        <p><strong>Time:</strong> {{ $makeupRequest->preferred_time }}</p>
                        <p><strong>Room:</strong> {{ $makeupRequest->room }}</p>
                    </div>

                    <form method="POST" action="{{ route('makeup-class.decline', ['id' => $makeupRequest->id, 'email' => $encryptedEmail]) }}">
                        @csrf

                        <div class="mb-4">
                            <label for="reason" class="block text-gray-700 text-sm font-bold mb-2">
                                Reason for Declining
                            </label>
                            <textarea
                                name="reason"
                                id="reason"
                                rows="4"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required
                            ></textarea>
                        </div>

                        <div class="flex items-center">
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Submit Decline Response
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
