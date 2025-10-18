@extends('layouts.app')

@section('title', 'Create New Schedule - USTP DMCRS')

@section('content')
    <div class="py-8 bg-gradient-to-br from-ustpBlue/5 via-white to-ustpGold/5 min-h-screen">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <strong class="font-bold">Whoops!</strong>
                            <span class="block sm:inline">There were some problems with your input.</span>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.schedules.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Department -->
                            <div>
                                <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Department <span class="text-red-500">*</span>
                                </label>
                                <select name="department_id" id="department_id" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Instructor -->
                            <div>
                                <label for="instructor_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Instructor <span class="text-red-500">*</span>
                                </label>
                                <select name="instructor_id" id="instructor_id" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select Instructor</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                            {{ $instructor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Subject Code -->
                            <div>
                                <label for="subject_code" class="block text-sm font-medium text-gray-700 mb-2">
                                    Subject Code <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="subject_code" id="subject_code" value="{{ old('subject_code') }}" required
                                       placeholder="e.g., CS101"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Subject Title -->
                            <div>
                                <label for="subject_title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Subject Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="subject_title" id="subject_title" value="{{ old('subject_title') }}" required
                                       placeholder="e.g., Introduction to Programming"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Section -->
                            <div>
                                <label for="section" class="block text-sm font-medium text-gray-700 mb-2">
                                    Section <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="section" id="section" value="{{ old('section') }}" required
                                       placeholder="e.g., BSIT 3A"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Day of Week -->
                            <div>
                                <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-2">
                                    Day <span class="text-red-500">*</span>
                                </label>
                                <select name="day_of_week" id="day_of_week" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select Day</option>
                                    @foreach($days as $day)
                                        <option value="{{ $day }}" {{ old('day_of_week') == $day ? 'selected' : '' }}>
                                            {{ $day }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Time Start -->
                            <div>
                                <label for="time_start" class="block text-sm font-medium text-gray-700 mb-2">
                                    Time Start <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="time_start" id="time_start" value="{{ old('time_start') }}" required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Time End -->
                            <div>
                                <label for="time_end" class="block text-sm font-medium text-gray-700 mb-2">
                                    Time End <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="time_end" id="time_end" value="{{ old('time_end') }}" required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Room -->
                            <div>
                                <label for="room" class="block text-sm font-medium text-gray-700 mb-2">
                                    Room <span class="text-red-500">*</span>
                                </label>
                                <select name="room" id="room" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select Room</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->name }}" {{ old('room') == $room->name ? 'selected' : '' }}>
                                            {{ $room->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Semester -->
                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">
                                    Semester
                                </label>
                                <input type="text" name="semester" id="semester" value="{{ old('semester', '2025-2026') }}"
                                       placeholder="e.g., 2025-2026"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select name="status" id="status" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="APPROVED" {{ old('status') == 'APPROVED' ? 'selected' : '' }}>Approved</option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end gap-4 mt-6 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.schedules.index') }}"
                               class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-semibold transition">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-6 py-2 rounded-lg text-white font-semibold transition"
                                    style="background-color: #023047; hover:background-color: #034a6b;">
                                ✅ Create Schedule
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
