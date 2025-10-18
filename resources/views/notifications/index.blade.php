

@extends('layouts.app')

@section('title', 'Notifications - USTP DMCRS')

@section('content')
<!-- Compact Notifications Page -->
<div class="bg-gray-50 min-h-screen">
    <!-- Simple Header -->
    <div class="bg-gradient-to-r from-ustpBlue to-ustpBlue/90 text-white py-3 sm:py-4 px-4 sm:px-6 shadow">
        <div class="max-w-4xl mx-auto flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0">
            <div class="flex items-center">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-ustpGold mr-2 sm:mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0018 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <h1 class="text-lg sm:text-xl font-bold">Notifications</h1>
            </div>
            <span class="bg-ustpGold/20 px-2 sm:px-3 py-1 rounded-lg text-xs sm:text-sm">{{ count(auth()->user()->notifications) }} total</span>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 sm:py-6">
        <div class="space-y-3 sm:space-y-4">
                @forelse (auth()->user()->notifications as $notification)
                    <div class="bg-white rounded-lg shadow border {{ $notification->read_at ? 'border-gray-200' : 'border-ustpGold/30 bg-blue-50/30' }} hover:shadow-md transition-shadow">
                        <div class="p-3 sm:p-4">
                            <div class="flex flex-col sm:flex-row items-start justify-between gap-3 sm:gap-4">
                                <!-- Content -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="font-semibold text-ustpBlue">{{ $notification->data['title'] ?? 'System Notification' }}</h3>
                                        @if(!$notification->read_at)
                                            <span class="w-2 h-2 bg-ustpGold rounded-full animate-pulse"></span>
                                        @endif
                                    </div>

                                    <p class="text-gray-700 text-sm mb-3">{{ $notification->data['message'] ?? $notification->data['body'] ?? 'No message content' }}</p>

                                    <!-- Compact Details -->
                                    <div class="flex flex-col sm:flex-row sm:flex-wrap gap-2 sm:gap-3 text-xs text-gray-600">
                                        @if(isset($notification->data['subject']))
                                            <span class="bg-ustpGold/10 px-2 py-1 rounded">
                                                <strong>Subject:</strong> {{ $notification->data['subject'] }}
                                            </span>
                                        @endif
                                        @if(isset($notification->data['date']))
                                            <span class="bg-blue-50 px-2 py-1 rounded">
                                                <strong>Date:</strong> {{ \Carbon\Carbon::parse($notification->data['date'])->format('M d, Y') }}
                                            </span>
                                        @endif
                                        @if(isset($notification->data['time']))
                                            <span class="bg-green-50 px-2 py-1 rounded">
                                                <strong>Time:</strong>
                                                @php
                                                    try {
                                                        echo \Carbon\Carbon::createFromFormat('H:i:s', $notification->data['time'])->format('g:i A');
                                                    } catch (Exception $e) {
                                                        echo $notification->data['time'];
                                                    }
                                                @endphp
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Right Side -->
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                    <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>

                                    @unless($notification->read_at)
                                        <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs bg-ustpBlue text-white px-2 sm:px-3 py-1 rounded hover:bg-ustpBlue/90 transition w-full sm:w-auto">
                                                Mark as read
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-green-600 font-medium">✓ Read</span>
                                    @endunless
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow border border-gray-200 p-8 text-center">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0018 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">No Notifications</h3>
                        <p class="text-gray-600">You're all caught up!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
