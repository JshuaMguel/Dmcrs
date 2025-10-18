@extends('layouts.app')

@section('title', 'Manage Rooms - USTP DMCRS')

@section('content')
<div class="py-8 bg-gradient-to-br from-ustpBlue/5 via-white to-ustpGold/5 min-h-screen">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-ustpBlue mb-2">🏢 Room Management</h1>
                    <p class="text-gray-600">Manage classroom and facility information</p>
                </div>
                <a href="{{ route('admin.rooms.create') }}"
                   class="px-6 py-3 bg-ustpBlue text-white rounded-lg hover:bg-blue-800 transition shadow-md hover:shadow-lg flex items-center gap-2">
                    <span class="text-xl">➕</span>
                    <span>Add New Room</span>
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow">
                <p class="font-medium">✅ {{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow">
                <p class="font-medium">❌ {{ session('error') }}</p>
            </div>
        @endif

        <!-- Rooms Table -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-ustpBlue">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                Room Name
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                Location
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                Capacity
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                Schedules
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($rooms as $room)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-ustpBlue/10 rounded-lg flex items-center justify-center">
                                            <span class="text-xl">🚪</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $room->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $room->location ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($room->capacity)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                👥 {{ $room->capacity }} persons
                                            </span>
                                        @else
                                            <span class="text-gray-400">Not set</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $scheduleCount = $room->schedules()->count();
                                    @endphp
                                    @if($scheduleCount > 0)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            📅 {{ $scheduleCount }} schedule(s)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            No schedules
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.rooms.edit', $room->id) }}"
                                           class="p-2 bg-yellow-100 text-yellow-800 rounded-lg hover:bg-yellow-200 transition"
                                           title="Edit" aria-label="Edit room">
                                            ✏️
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-400">
                                        <div class="text-6xl mb-4">🏢</div>
                                        <p class="text-lg font-medium">No rooms found</p>
                                        <p class="text-sm mt-2">Click "Add New Room" to create your first room</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($rooms->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $rooms->links() }}
                </div>
            @endif
        </div>

        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-ustpBlue/10 rounded-lg p-3">
                        <span class="text-3xl">🏢</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Rooms</p>
                        <p class="text-2xl font-bold text-ustpBlue">{{ $rooms->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                        <span class="text-3xl">📅</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Rooms in Use</p>
                        <p class="text-2xl font-bold text-green-600">
                            {{ $rooms->filter(fn($r) => $r->schedules()->count() > 0)->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-gray-100 rounded-lg p-3">
                        <span class="text-3xl">🚪</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Available Rooms</p>
                        <p class="text-2xl font-bold text-gray-600">
                            {{ $rooms->filter(fn($r) => $r->schedules()->count() == 0)->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
