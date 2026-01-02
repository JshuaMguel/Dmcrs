
@extends('layouts.app')

@section('title', 'Department Chair Dashboard - USTP DMCRS')

@section('content')
    <div class="py-4 sm:py-6 lg:py-8 bg-gradient-to-br from-ustpBlue/5 via-white to-ustpGold/5 min-h-screen">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-100 p-4 sm:p-6 lg:p-8">
                <!-- Header Section -->
                <div class="text-center mb-6 sm:mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-ustpBlue rounded-full mb-4">
                        <span class="text-2xl sm:text-3xl text-white">🏢</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-ustpBlue mb-2">Department Chair Dashboard</h1>
                    <p class="text-gray-600 text-sm sm:text-base lg:text-lg">
                        Welcome, <span class="font-semibold text-ustpBlue">{{ Auth::user()->name }}</span>
                        <span class="inline-block ml-2 bg-ustpGold text-ustpBlue text-xs sm:text-sm font-semibold px-2 sm:px-3 py-1 rounded-full">
                            {{ ucwords(str_replace('_', ' ', Auth::user()->role)) }}
                        </span>
                    </p>
                </div>

                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <span class="text-green-500 text-xl mr-3">✅</span>
                            <div>
                                <h4 class="font-semibold text-green-800">Success!</h4>
                                <p class="text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <span class="text-red-500 text-xl mr-3">❌</span>
                            <div>
                                <h4 class="font-semibold text-red-800">Error!</h4>
                                <p class="text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
                    <!-- Pending Requests Card -->
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl shadow-lg border-2 border-amber-200 p-6 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Pending Requests</p>
                                <p class="text-3xl font-bold text-amber-600 mt-1">{{ $pendingCount ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">Awaiting review</p>
                            </div>
                            <div class="bg-amber-500 rounded-full p-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Request History Card -->
                    <div class="bg-gradient-to-br from-ustpBlue/10 to-ustpBlue/5 rounded-xl shadow-lg border-2 border-ustpBlue/20 p-6 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Request History</p>
                                <p class="text-3xl font-bold text-ustpBlue mt-1">{{ $historyCount ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">Processed requests</p>
                            </div>
                            <div class="bg-ustpBlue rounded-full p-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Approvals Log Card -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl shadow-lg border-2 border-green-200 p-6 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Approvals Log</p>
                                <p class="text-3xl font-bold text-green-600 mt-1">{{ $approvalsCount ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">Total approvals</p>
                            </div>
                            <div class="bg-green-500 rounded-full p-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
