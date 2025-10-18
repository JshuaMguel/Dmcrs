
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
                            {{ Auth::user()->role }}
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

                <!-- Dashboard Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    <!-- Pending Requests Card -->
                    <a href="{{ route('department.requests') }}" class="group bg-white border-2 border-gray-200 hover:border-ustpBlue rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center mb-4">
                            <div class="flex items-center justify-center w-12 h-12 bg-ustpBlue rounded-lg mr-4">
                                <span class="text-white text-xl">📋</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-ustpBlue group-hover:text-blue-800">Pending Requests</h3>
                                <p class="text-gray-600 text-sm">Review makeup class requests</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-ustpBlue">{{ $pendingCount ?? 0 }}</span>
                            <span class="text-ustpBlue group-hover:translate-x-1 transition-transform">→</span>
                        </div>
                    </a>

                    <!-- Request History Card -->
                    <a href="{{ route('department.history') }}" class="group bg-white border-2 border-gray-200 hover:border-ustpGold rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center mb-4">
                            <div class="flex items-center justify-center w-12 h-12 bg-ustpGold rounded-lg mr-4">
                                <span class="text-ustpBlue text-xl">📚</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-ustpBlue group-hover:text-blue-800">Request History</h3>
                                <p class="text-gray-600 text-sm">View all processed requests</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-ustpBlue">{{ $historyCount ?? 0 }}</span>
                            <span class="text-ustpBlue group-hover:translate-x-1 transition-transform">→</span>
                        </div>
                    </a>

                    <!-- Approvals Log Card -->
                    <a href="{{ route('department.approvals') }}" class="group bg-white border-2 border-gray-200 hover:border-green-500 rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center mb-4">
                            <div class="flex items-center justify-center w-12 h-12 bg-green-500 rounded-lg mr-4">
                                <span class="text-white text-xl">✅</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-ustpBlue group-hover:text-blue-800">Approvals Log</h3>
                                <p class="text-gray-600 text-sm">Track approval status</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-ustpBlue">{{ $approvalsCount ?? 0 }}</span>
                            <span class="text-ustpBlue group-hover:translate-x-1 transition-transform">→</span>
                        </div>
                    </a>

                    <!-- Class Schedule Board Card -->
                    <a href="{{ route('department.schedule') }}" class="group bg-white border-2 border-gray-200 hover:border-purple-500 rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center mb-4">
                            <div class="flex items-center justify-center w-12 h-12 bg-purple-500 rounded-lg mr-4">
                                <span class="text-white text-xl">📅</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-ustpBlue group-hover:text-blue-800">Class Schedule Board</h3>
                                <p class="text-gray-600 text-sm">View department schedules</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-ustpBlue">View Board</span>
                            <span class="text-ustpBlue group-hover:translate-x-1 transition-transform">→</span>
                        </div>
                    </a>

                    <!-- Notifications Card -->
                    <a href="{{ route('notifications.index') }}" class="group bg-white border-2 border-gray-200 hover:border-orange-500 rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center mb-4">
                            <div class="flex items-center justify-center w-12 h-12 bg-orange-500 rounded-lg mr-4">
                                <span class="text-white text-xl">🔔</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-ustpBlue group-hover:text-blue-800">Notifications</h3>
                                <p class="text-gray-600 text-sm">View system notifications</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-ustpBlue">View All</span>
                            <span class="text-ustpBlue group-hover:translate-x-1 transition-transform">→</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
