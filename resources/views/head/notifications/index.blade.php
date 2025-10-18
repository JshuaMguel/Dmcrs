@extends('layouts.app')

@section('title', 'Notifications - USTP DMCRS')

@section('content')
<!-- USTP-Themed Notifications Page -->
<div class="min-h-screen bg-gradient-to-br from-ustpGray to-white">
    <!-- Header Section with USTP Branding -->
    <div class="bg-gradient-to-r from-ustpBlue to-ustpBlue/90 text-white py-6 px-6 shadow-xl">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-ustpGold/20 backdrop-blur-sm rounded-xl p-3 mr-4">
                        <svg class="w-8 h-8 text-ustpGold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0018 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">Notifications Center</h1>
                        <p class="text-ustpGold mt-1 text-lg">Academic Head Communications</p>
                    </div>
                </div>
                <div class="hidden lg:flex items-center space-x-4">
                    <div class="bg-ustpGold/20 backdrop-blur-sm rounded-lg px-4 py-2 text-center">
                        <div class="text-ustpGold text-sm font-medium">Total Notifications</div>
                        <div class="text-white text-xl font-bold">{{ count($notifications) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Notifications Table Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <!-- Table Header -->
            <div class="bg-gradient-to-r from-ustpBlue to-ustpBlue/90 px-6 py-4">
                <h3 class="text-xl font-bold text-white">Recent Notifications</h3>
                <p class="text-ustpGold text-sm mt-1">System alerts and updates</p>
            </div>

            <!-- Enhanced Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Message</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Request ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Time</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-ustpBlue uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($notifications as $notif)
                            <tr class="hover:bg-ustpGold/5 transition-all duration-200">
                                <!-- Notification Type/Title -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-ustpBlue to-ustpBlue/80 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0018 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-ustpBlue">{{ $notif->data['title'] ?? 'System Notification' }}</div>
                                            <div class="text-gray-500 text-sm">Alert</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Message -->
                                <td class="px-6 py-4">
                                    <div class="text-gray-900 text-sm max-w-xs">
                                        {{ Str::limit($notif->data['message'] ?? ($notif->data['body'] ?? 'No message'), 60) }}
                                    </div>
                                </td>

                                <!-- Request ID -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(isset($notif->data['request_id']))
                                        <div class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-ustpGold/20 text-ustpBlue border border-ustpGold/30">
                                            #{{ $notif->data['request_id'] }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">N/A</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($notif->read_at)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                            Read
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                            <div class="w-2 h-2 bg-amber-400 rounded-full mr-2 animate-pulse"></div>
                                            Unread
                                        </span>
                                    @endif
                                </td>

                                <!-- Time -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $notif->created_at->diffForHumans() }}</span>
                                        <span class="text-xs text-gray-400">{{ $notif->created_at->format('M d, Y g:i A') }}</span>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(!$notif->read_at)
                                        <form method="POST" action="{{ route('notifications.markAsRead', $notif->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-ustpGold to-amber-500 text-white text-sm font-medium rounded-lg hover:from-amber-500 hover:to-ustpGold transition-all duration-200 shadow-md hover:shadow-lg">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Mark Read
                                            </button>
                                        </form>
                                    @else
                                        <div class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-lg">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Read
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0018 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">No Notifications</h3>
                                        <p class="text-gray-500">You're all caught up! No new notifications at this time.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
