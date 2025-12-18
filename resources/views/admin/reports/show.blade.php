@extends('layouts.app')

@section('title', ucfirst($type) . ' Report - USTP DMCRS')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-4 sm:p-6 rounded-xl shadow-lg">
    <!-- Header Section -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-ustpBlue rounded-full">
                    <span class="text-lg sm:text-2xl text-white">📊</span>
                </div>
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-ustpBlue">{{ ucfirst($type) }} Report</h2>
                    <p class="text-sm sm:text-base text-gray-600">Generated on {{ now()->format('F d, Y g:i A') }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.reports.exportPdf', $type) }}"
                   class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 font-medium text-sm flex items-center gap-2">
                    <span>📄</span>
                    Export PDF
                </a>
                <a href="{{ route('admin.reports.exportExcel', $type) }}"
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium text-sm flex items-center gap-2">
                    <span>📊</span>
                    Export Excel
                </a>
                <a href="{{ route('admin.reports.index') }}"
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 font-medium text-sm">
                    Back
                </a>
            </div>
        </div>
    </div>

    <!-- Report Data Table -->
    <div class="bg-white rounded-lg shadow-md overflow-x-auto border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-ustpBlue">
                <tr>
                    @if($type == 'users')
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Department</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Status</th>
                    @elseif($type == 'departments')
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Users</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Subjects</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Sections</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Students</th>
                    @elseif($type == 'subjects')
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Subject Code</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Title</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Department</th>
                    @elseif($type == 'sections')
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Section</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Year Level</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Department</th>
                    @elseif($type == 'schedules')
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Subject</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Section</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Day</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Time</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Room</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Instructor</th>
                    @elseif($type == 'rooms')
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Capacity</th>
                    @elseif($type == 'students')
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Student ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Department</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Year Level</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Section</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Status</th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($data as $item)
                    <tr class="hover:bg-gray-50">
                        @if($type == 'users')
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst(str_replace('_', ' ', $item->role)) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->department->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->is_active ? 'Active' : 'Inactive' }}</td>
                        @elseif($type == 'departments')
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->users_count ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->subjects_count ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->sections_count ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->students_count ?? 0 }}</td>
                        @elseif($type == 'subjects')
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-ustpBlue">{{ $item->subject_code }}</td>
                            <td class="px-6 py-4">{{ $item->subject_title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->department->name ?? 'N/A' }}</td>
                        @elseif($type == 'sections')
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->full_name ?? ($item->year_level . $item->section_name) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->year_level }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->department->name ?? 'N/A' }}</td>
                        @elseif($type == 'schedules')
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->subject_code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->section }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->day_of_week }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $item->time_start ? \Carbon\Carbon::parse($item->time_start)->format('g:i A') : '' }}
                                @if($item->time_end)
                                    - {{ \Carbon\Carbon::parse($item->time_end)->format('g:i A') }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->room }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->instructor ? $item->instructor->name : ($item->instructor_name ?? 'N/A') }}</td>
                        @elseif($type == 'rooms')
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->capacity ?? 'N/A' }}</td>
                        @elseif($type == 'students')
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-ustpBlue">{{ $item->student_id_number }}</td>
                            <td class="px-6 py-4">{{ $item->full_name ?? ($item->first_name . ' ' . $item->last_name) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->department->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->year_level }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->section ? ($item->section->full_name ?? $item->section->section_name) : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($item->status) }}</td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                            No data available
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-sm text-gray-600">
        Total Records: {{ $data->count() }}
    </div>
</div>
@endsection


