@extends('layouts.app')

@section('title', 'Add New Room - USTP DMCRS')

@section('content')
<div class="py-8 bg-gradient-to-br from-ustpBlue/5 via-white to-ustpGold/5 min-h-screen">
    <div class="max-w-3xl mx-auto px-4">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.rooms.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    ← Back
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-ustpBlue">➕ Add New Room</h1>
                    <p class="text-gray-600 mt-1">Create a new classroom or facility</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-xl p-8">
            <form action="{{ route('admin.rooms.store') }}" method="POST">
                @csrf

                <!-- Room Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">
                        Room Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent"
                           placeholder="e.g., Comlab 1A, AVR, Room 301"
                           required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">Enter a unique name for the room</p>
                </div>

                <!-- Location -->
                <div class="mb-6">
                    <label for="location" class="block text-sm font-bold text-gray-700 mb-2">
                        Location
                    </label>
                    <input type="text"
                           name="location"
                           id="location"
                           value="{{ old('location') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent"
                           placeholder="e.g., 2nd Floor, Building A">
                    @error('location')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">Optional: Specify the building or floor location</p>
                </div>

                <!-- Capacity -->
                <div class="mb-6">
                    <label for="capacity" class="block text-sm font-bold text-gray-700 mb-2">
                        Capacity
                    </label>
                    <input type="number"
                           name="capacity"
                           id="capacity"
                           value="{{ old('capacity') }}"
                           min="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent"
                           placeholder="e.g., 40">
                    @error('capacity')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">Optional: Maximum number of persons the room can accommodate</p>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.rooms.index') }}"
                       class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition shadow hover:shadow-md">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-ustpBlue text-white rounded-lg hover:bg-blue-800 transition shadow hover:shadow-md">
                        ✅ Create Room
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
