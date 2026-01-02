
@extends('layouts.app')

@section('title', 'Admin Dashboard - USTP DMCRS')

@section('content')
    <div class="py-8 bg-gradient-to-br from-ustpBlue/5 via-white to-ustpGold/5 min-h-screen">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Welcome Header -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 mb-8">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-ustpBlue rounded-full mb-4">
                        <span class="text-3xl text-white">🛡️</span>
                    </div>
                    <h1 class="text-4xl font-bold text-ustpBlue mb-2">Admin Dashboard</h1>
                    <p class="text-gray-600 text-lg">
                        Welcome, <span class="font-semibold text-ustpBlue">{{ Auth::user()->name }}</span>
                        <span class="inline-block ml-2 bg-ustpGold text-ustpBlue text-sm font-semibold px-3 py-1 rounded-full">
                            {{ Auth::user()->role }}
                        </span>
                    </p>
                    <p class="text-sm text-gray-500 mt-2">{{ now()->format('l, F j, Y') }} • {{ now()->format('g:i A') }}</p>
                </div>

                <div class="bg-gradient-to-r from-ustpBlue/10 to-ustpGold/10 rounded-xl p-4 border border-ustpBlue/20">
                    <div class="text-center">
                        <div class="text-sm text-ustpBlue font-medium">System Status</div>
                        <div class="flex items-center justify-center gap-2 mt-1">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-green-700 font-semibold">All Systems Operational</span>
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Users</p>
                            <p class="text-3xl font-bold text-ustpBlue">{{ \App\Models\User::count() }}</p>
                            <p class="text-sm text-green-600 mt-1">
                                <span class="font-medium">{{ \App\Models\User::where('created_at', '>=', now()->subWeek())->count() }}</span> new this week
                            </p>
                        </div>
                        <div class="bg-ustpBlue/10 rounded-full p-3">
                            <svg class="w-8 h-8 text-ustpBlue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Departments</p>
                            <p class="text-3xl font-bold text-ustpGold">{{ \App\Models\Department::count() }}</p>
                            <p class="text-sm text-gray-500 mt-1">Active departments</p>
                        </div>
                        <div class="bg-ustpGold/10 rounded-full p-3">
                            <svg class="w-8 h-8 text-ustpGold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Requests</p>
                            <p class="text-3xl font-bold text-ustpBlue">{{ \App\Models\MakeUpClassRequest::count() }}</p>
                            <p class="text-sm text-ustpGold mt-1">
                                <span class="font-medium">{{ \App\Models\MakeUpClassRequest::where('created_at', '>=', now()->subDay())->count() }}</span> today
                            </p>
                        </div>
                        <div class="bg-ustpBlue/10 rounded-full p-3">
                            <svg class="w-8 h-8 text-ustpBlue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Pending Requests</p>
                            <p class="text-3xl font-bold text-red-600">{{ \App\Models\MakeUpClassRequest::where('status', 'pending')->count() }}</p>
                            <p class="text-sm text-red-500 mt-1">Require attention</p>
                        </div>
                        <div class="bg-red-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Rooms</p>
                            <p class="text-3xl font-bold text-purple-600">{{ \App\Models\Room::count() }}</p>
                            <p class="text-sm text-gray-500 mt-1">Available rooms</p>
                        </div>
                        <div class="bg-purple-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Subjects</p>
                            <p class="text-3xl font-bold text-green-600">{{ \App\Models\Subject::count() }}</p>
                            <p class="text-sm text-gray-500 mt-1">Active subjects</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Sections</p>
                            <p class="text-3xl font-bold text-indigo-600">{{ \App\Models\Section::count() }}</p>
                            <p class="text-sm text-gray-500 mt-1">Active sections</p>
                        </div>
                        <div class="bg-indigo-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Schedules</p>
                            <p class="text-3xl font-bold text-orange-600">{{ \App\Models\Schedule::count() }}</p>
                            <p class="text-sm text-gray-500 mt-1">Active schedules</p>
                        </div>
                        <div class="bg-orange-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Overview -->
            <div class="space-y-6">
                    <!-- Recent Activity -->
                    <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-ustpGold/10 rounded-full p-3">
                                <span class="text-xl">🕒</span>
                            </div>
                            <h3 class="text-lg font-bold text-ustpBlue">Recent Activity</h3>
                        </div>
                        <div class="space-y-3">
                            @php
                                // Get recent users (last 5)
                                $recentUsers = \App\Models\User::latest()->take(3)->get();
                                // Get recent makeup requests (last 5)
                                $recentRequests = \App\Models\MakeUpClassRequest::with('subject')->latest()->take(3)->get();

                                // Combine and sort all activities
                                $activities = collect();

                                foreach($recentUsers as $user) {
                                    $activities->push([
                                        'type' => 'user_registered',
                                        'message' => 'New user registered: ' . $user->name,
                                        'time' => $user->created_at,
                                        'color' => 'blue'
                                    ]);
                                }

                                foreach($recentRequests as $request) {
                                    $color = $request->status === 'approved' ? 'green' : ($request->status === 'pending' ? 'yellow' : 'red');
                                    $activities->push([
                                        'type' => 'request_' . $request->status,
                                        'message' => 'Makeup request ' . $request->status . ': ' . (($request->subject && is_object($request->subject)) ? $request->subject->subject_code : $request->subject),
                                        'time' => $request->updated_at,
                                        'color' => $color
                                    ]);
                                }

                                // Sort by time (most recent first) and take top 5
                                $activities = $activities->sortByDesc('time')->take(5);
                            @endphp

                            @forelse($activities as $activity)
                                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="w-2 h-2 bg-{{ $activity['color'] }}-500 rounded-full mt-2"></div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $activity['message'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $activity['time']->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="flex items-center justify-center p-6">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                        </svg>
                                        <p class="text-sm text-gray-500">No recent activity</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
            </div>
        </div>
    </div>
@endsection
