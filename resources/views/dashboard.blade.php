
@extends('layouts.app')

@section('title', 'Dashboard - USTP DMCRS')

@section('content')
    <div class="py-6 sm:py-8 lg:py-12 bg-gradient-to-br from-ustpBlue/5 via-white to-ustpGold/5 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl border border-gray-100 rounded-xl sm:rounded-2xl">
                <div class="p-4 sm:p-6 lg:p-8">
                    <!-- Welcome Header -->
                    <div class="text-center mb-6 sm:mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-ustpBlue rounded-full mb-4">
                            <span class="text-2xl sm:text-3xl text-white">👋</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-ustpBlue mb-2">Welcome to DMCRS</h1>
                        <p class="text-sm sm:text-base lg:text-lg text-gray-600">
                            Hello, <span class="font-semibold text-ustpBlue">{{ Auth::user()->name }}</span>
                            <span class="inline-block ml-2 bg-ustpGold text-ustpBlue text-xs sm:text-sm font-semibold px-2 sm:px-3 py-1 rounded-full">
                                {{ ucfirst(Auth::user()->role) }}
                            </span>
                        </p>
                        <p class="text-xs sm:text-sm text-gray-500 mt-2">Department Make-up Class Request System</p>
                    </div>

                    <!-- Quick Info Card -->
                    <div class="bg-gradient-to-r from-ustpBlue/10 to-ustpGold/10 rounded-lg p-4 sm:p-6 border border-ustpBlue/20">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="text-center sm:text-left">
                                <h3 class="text-lg sm:text-xl font-semibold text-ustpBlue mb-2">System Dashboard</h3>
                                <p class="text-sm sm:text-base text-gray-600">Use the navigation menu to access your role-specific features and manage makeup class requests.</p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="bg-ustpBlue text-white p-3 sm:p-4 rounded-full">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
