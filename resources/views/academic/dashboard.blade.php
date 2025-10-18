
@extends('layouts.app')

@section('content')
    <div class="py-12 bg-gradient-to-br from-blue-50 via-white to-green-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-2xl border border-blue-100 p-8 flex flex-col gap-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-green-600 text-white rounded-full p-4 shadow">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" /></svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-extrabold text-blue-900">Academic Head Dashboard</h1>
                        <p class="mt-1 text-gray-700">Welcome, <span class="font-bold text-green-700">{{ Auth::user()->name }}</span> <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ Auth::user()->role }}</span></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                    <a href="{{ route('head.requests.index') }}" class="group bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 flex flex-col items-center justify-center min-h-[120px] shadow hover:scale-105 hover:shadow-lg transition-all duration-200">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-200 mb-2 group-hover:bg-blue-300 transition">
                            <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" /></svg>
                        </div>
                        <span class="font-bold text-base text-blue-900 tracking-wide">Pending Approvals</span>
                    </a>
                    <a href="{{ route('head.schedule.index') }}" class="group bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-4 flex flex-col items-center justify-center min-h-[120px] shadow hover:scale-105 hover:shadow-lg transition-all duration-200">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-200 mb-2 group-hover:bg-green-300 transition">
                            <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <span class="font-bold text-base text-green-900 tracking-wide">Master Schedule</span>
                    </a>
                    <a href="{{ route('head.reports.index') }}" class="group bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-4 flex flex-col items-center justify-center min-h-[120px] shadow hover:scale-105 hover:shadow-lg transition-all duration-200">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-purple-200 mb-2 group-hover:bg-purple-300 transition">
                            <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <span class="font-bold text-base text-purple-900 tracking-wide">Logs & Reports</span>
                    </a>
                    <a href="{{ route('notifications.index') }}" class="group bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-xl p-4 flex flex-col items-center justify-center min-h-[120px] shadow hover:scale-105 hover:shadow-lg transition-all duration-200">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-orange-200 mb-2 group-hover:bg-orange-300 transition">
                            <svg class="w-6 h-6 text-orange-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        </div>
                        <span class="font-bold text-base text-orange-900 tracking-wide">Notifications</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
