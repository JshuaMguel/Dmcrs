@extends('layouts.app')

@section('title', 'Faculty Profile - USTP DMCRS')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-4 sm:p-6 lg:p-8 rounded-xl sm:rounded-2xl shadow-2xl border border-blue-100 mt-4 sm:mt-8">
    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="flex-shrink-0">
            @if(Auth::user()->profile_image)
                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Profile Image" class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-blue-200">
            @else
                <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full bg-blue-100 flex items-center justify-center text-2xl sm:text-4xl text-blue-600 font-bold border-4 border-blue-200">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
        </div>
        <div class="text-center sm:text-left">
            <h2 class="text-xl sm:text-2xl font-extrabold text-blue-900">{{ Auth::user()->name }}</h2>
            <p class="text-blue-700 font-semibold text-sm sm:text-base">{{ Auth::user()->role }}</p>
            <p class="text-gray-600 text-sm sm:text-base">Department: <span class="font-bold">{{ Auth::user()->department }}</span></p>
            <p class="text-gray-600 text-sm sm:text-base">Contact: <span class="font-bold">{{ Auth::user()->contact_number ?? 'N/A' }}</span></p>
        </div>
    </div>
    <div class="mb-4 sm:mb-6">
        <h3 class="text-base sm:text-lg font-bold text-blue-800 mb-2">About</h3>
        <p class="text-gray-700 text-sm sm:text-base">{{ Auth::user()->bio ?? 'No bio provided.' }}</p>
    </div>
    <div class="mt-6 sm:mt-8">
        <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center bg-blue-600 text-white p-2 sm:p-3 rounded-lg shadow hover:bg-blue-700 w-full sm:w-auto text-sm sm:text-base" title="Edit Profile">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Profile
        </a>
    </div>
</div>
@endsection
