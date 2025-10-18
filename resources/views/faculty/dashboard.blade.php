
@extends('layouts.app')

@section('title', 'Faculty Dashboard - USTP DMCRS')

@section('content')
    <div class="py-4 sm:py-6 lg:py-8 bg-gradient-to-br from-ustpBlue/5 via-white to-ustpGold/5 min-h-screen">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-100 p-4 sm:p-6 lg:p-8">
                <!-- Header Section -->
                <div class="text-center mb-6 sm:mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-ustpBlue rounded-full mb-4">
                        <span class="text-2xl sm:text-3xl text-white">👨‍🏫</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-ustpBlue mb-2">Faculty Dashboard</h1>
                    <p class="text-gray-600 text-sm sm:text-base lg:text-lg">
                        Welcome, <span class="font-semibold text-ustpBlue">{{ Auth::user()->name }}</span>
                        <span class="inline-block ml-2 bg-ustpGold text-ustpBlue text-xs sm:text-sm font-semibold px-2 sm:px-3 py-1 rounded-full">
                            {{ Auth::user()->role }}
                        </span>
                    </p>
                </div>

                <!-- Dashboard Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    <!-- Makeup Requests Card -->
                    <a href="{{ route('makeup-requests.index') }}" class="group bg-white border-2 border-gray-200 hover:border-ustpBlue rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center mb-4">
                            <div class="flex items-center justify-center w-12 h-12 bg-ustpBlue rounded-lg mr-4">
                                <span class="text-white text-xl">📝</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-ustpBlue group-hover:text-blue-800">Makeup Requests</h3>
                                <p class="text-gray-600 text-sm">Manage class requests</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-ustpBlue">View All</span>
                            <span class="text-ustpBlue group-hover:translate-x-1 transition-transform">→</span>
                        </div>
                    </a>

                    <!-- Class Schedule Board Card -->
                    <a href="{{ route('faculty.schedule') }}" class="group bg-white border-2 border-gray-200 hover:border-ustpGold rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center mb-4">
                            <div class="flex items-center justify-center w-12 h-12 bg-ustpGold rounded-lg mr-4">
                                <span class="text-ustpBlue text-xl">📅</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-ustpBlue group-hover:text-blue-800">Class Schedule</h3>
                                <p class="text-gray-600 text-sm">View your schedule</p>
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
                                <p class="text-gray-600 text-sm">Check updates</p>
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
