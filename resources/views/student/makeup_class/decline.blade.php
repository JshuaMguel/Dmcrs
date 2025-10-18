@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-4 sm:p-6 lg:p-8 rounded-xl shadow-lg border border-gray-100">
    <!-- Header Section -->
    <div class="text-center mb-6 sm:mb-8">
        <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-red-500 rounded-full mb-4">
            <span class="text-lg sm:text-2xl text-white">❌</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-red-600 mb-2">Decline Make-Up Class Attendance</h2>
        <p class="text-sm sm:text-base text-gray-600">Please provide a reason for declining this makeup class</p>
    </div>

    <form method="POST" action="{{ route('student.makeup-class.decline.submit') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="request_id" value="{{ $requestId }}">

        <div>
            <label for="reason" class="block text-sm font-semibold text-gray-700 mb-2">
                <span class="text-red-500">*</span> Reason for Declining
            </label>
            <textarea name="reason" id="reason" rows="4" required
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 text-sm sm:text-base"
                      placeholder="Please explain why you cannot attend the makeup class..."></textarea>
            @error('reason') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 sm:gap-0 pt-4 sm:pt-6 border-t border-gray-200">
            <a href="{{ route('notifications.index') }}"
               class="inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400 text-sm sm:text-base">
                <span class="mr-2">←</span>
                Cancel
            </a>

            <button type="submit"
                    class="inline-flex items-center justify-center px-6 sm:px-8 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 font-semibold transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-400 text-sm sm:text-base">
                <span class="mr-2">❌</span>
                Submit Decline
            </button>
        </div>
    </form>
</div>
@endsection
