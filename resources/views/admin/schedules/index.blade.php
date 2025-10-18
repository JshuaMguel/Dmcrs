@extends('layouts.app')

@section('title', 'Manage Schedules - USTP DMCRS')

@section('content')
    <div class="py-8 bg-gradient-to-br from-ustpBlue/5 via-white to-ustpGold/5 min-h-screen">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Header with Add Button -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6 flex justify-between items-center">
                    <h3 class="text-lg font-semibold" style="color: #023047;">Schedule Management</h3>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.schedules.board') }}"
                           class="px-4 py-2 rounded-lg bg-green-600 text-white font-semibold transition hover:bg-green-700">
                            📅 Calendar View
                        </a>
                        <a href="{{ route('admin.schedules.create') }}"
                           class="px-4 py-2 rounded-lg text-white font-semibold transition"
                           style="background-color: #023047; hover:background-color: #034a6b;">
                            ➕ Add New Schedule
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.schedules.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Subject, Section..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Department Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                            <select name="department_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Instructor Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Instructor</label>
                            <select name="instructor_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Instructors</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" {{ request('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                        {{ $instructor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Day Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Day</label>
                            <select name="day" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Days</option>
                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                    <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="md:col-span-4 flex gap-2">
                            <button type="submit" class="px-4 py-2 rounded text-white" style="background-color: #023047;">
                                🔍 Filter
                            </button>
                            <a href="{{ route('admin.schedules.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Schedules Table -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead style="background-color: #023047;">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Subject</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Section</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Instructor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Day</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Room</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($schedules as $schedule)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $schedule->subject_code }}</div>
                                            <div class="text-sm text-gray-500">{{ $schedule->subject_title }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @php
                                                $sectionDisplay = $schedule->section;
                                                // Handle if section is accidentally stored as JSON
                                                if (is_string($sectionDisplay) && str_starts_with($sectionDisplay, '{')) {
                                                    try {
                                                        $sectionData = json_decode($sectionDisplay);
                                                        if ($sectionData && isset($sectionData->year_level, $sectionData->section_name)) {
                                                            $sectionDisplay = $sectionData->year_level . '-' . $sectionData->section_name;
                                                        }
                                                    } catch (\Exception $e) {
                                                        // Keep original if parsing fails
                                                    }
                                                }
                                            @endphp
                                            {{ $sectionDisplay }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $schedule->instructor->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $schedule->day_of_week }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ date('g:i A', strtotime($schedule->time_start)) }} - {{ date('g:i A', strtotime($schedule->time_end)) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $schedule->room }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $schedule->status == 'active' || $schedule->status == 'APPROVED' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($schedule->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex justify-center gap-2">
                                                <a href="{{ route('admin.schedules.edit', $schedule->id) }}"
                                                   class="text-blue-600 hover:text-blue-900" title="Edit" aria-label="Edit schedule">
                                                    ✏️
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                            No schedules found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $schedules->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
