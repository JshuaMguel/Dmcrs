@extends('layouts.app')

@section('title', 'Faculty Loading Details - USTP DMCRS')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-4 sm:p-6 rounded-xl shadow-lg">
    <!-- Header Section -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-ustpBlue rounded-full">
                    <span class="text-lg sm:text-2xl text-white">📋</span>
                </div>
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-ustpBlue">Faculty Loading Details</h2>
                    <p class="text-sm sm:text-base text-gray-600">FLH Code: <span class="font-bold">{{ $facultyLoading->flh_code }}</span></p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('department.faculty-loading.edit', $facultyLoading) }}"
                   class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 font-medium text-sm flex items-center gap-2">
                    <span>✏️</span>
                    Edit
                </a>
                <a href="{{ route('department.faculty-loading.index') }}"
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 font-medium text-sm">
                    Back
                </a>
            </div>
        </div>
    </div>

    <!-- Header Information -->
    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-600">Semester</label>
                <p class="text-lg font-semibold text-ustpBlue">{{ ucfirst($facultyLoading->semester) }} Semester</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">School Year</label>
                <p class="text-lg font-semibold text-ustpBlue">{{ $facultyLoading->school_year }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Status</label>
                @php
                    $statusColors = [
                        'draft' => 'bg-gray-100 text-gray-800',
                        'active' => 'bg-green-100 text-green-800',
                        'archived' => 'bg-red-100 text-red-800',
                    ];
                    $color = $statusColors[$facultyLoading->status] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $color }}">
                    {{ ucfirst($facultyLoading->status) }}
                </span>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Uploaded By</label>
                <p class="text-lg font-semibold text-ustpBlue">{{ $facultyLoading->uploadedBy->name ?? 'N/A' }}</p>
            </div>
            @if($facultyLoading->remarks)
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-600">Remarks</label>
                <p class="text-gray-900">{{ $facultyLoading->remarks }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Details Table -->
    <div class="bg-white rounded-lg shadow-md overflow-x-auto border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-ustpBlue">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Instructor</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Subject Code</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Subject Title</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Section</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Total Students</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Day</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Time</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Room</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Units</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($facultyLoading->details as $detail)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $detail->instructor->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-ustpBlue">{{ $detail->subject_code }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs">{{ $detail->subject_title ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $detail->section }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-ustpBlue">{{ $detail->total_students ?? 0 }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $detail->day_of_week }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($detail->time_start)->format('g:i A') }} - 
                                {{ \Carbon\Carbon::parse($detail->time_end)->format('g:i A') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $detail->room }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $detail->units ?? 'N/A' }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                            No details found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-sm text-gray-600">
        Total Classes: {{ $facultyLoading->details->count() }}
    </div>
</div>
@endsection


