@extends('layouts.app')

@section('title', 'Class Schedule - USTP DMCRS')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-4 sm:p-6 rounded-xl shadow-lg">
    <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6">Class Schedule Board</h2>
    @php $role = Auth::user()->role ?? null; @endphp
    @if($role === 'academic_head')
        <div class="mb-4 flex flex-col sm:flex-row gap-2">
            <!-- Placeholder for future Add/Edit buttons -->
            <button class="bg-blue-600 text-white px-3 sm:px-4 py-2 rounded shadow opacity-50 cursor-not-allowed text-sm sm:text-base w-full sm:w-auto" disabled>Add Schedule (Coming Soon)</button>
            <button class="bg-yellow-500 text-white p-2 rounded shadow opacity-50 cursor-not-allowed w-full sm:w-auto flex items-center justify-center" disabled title="Edit Schedule">
                <svg class="w-4 h-4 mr-2 sm:mr-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span class="sm:hidden">Edit Schedule</span>
            </button>
        </div>
    @endif
    <div class="overflow-x-auto -mx-2 sm:mx-0">
        <table class="min-w-full border border-gray-300 rounded-lg" style="min-width: 800px;">
            <thead>
                <tr class="bg-ustpGold text-ustpBlack">
                    <th class="p-1 sm:p-2 border-r border-gray-300 text-left text-xs sm:text-sm">Time</th>
                    @foreach($rooms as $room)
                        <th class="p-1 sm:p-2 border-r border-gray-300 text-center text-xs sm:text-sm">{{ $room->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($timeSlots as $slot)
                    <tr class="border-b border-gray-200 hover:bg-ustpGray">
                        <td class="p-1 sm:p-2 font-semibold text-ustpBlue border-r border-gray-300 text-xs sm:text-sm">{{ $slot }}</td>
                        @foreach($rooms as $room)
                            @php
                                $sched = $schedules->first(function($s) use ($room, $slot) {
                                    return $s->room == $room->name && $s->time_start == $slot;
                                });
                            @endphp
                            <td class="p-1 sm:p-2 text-xs sm:text-sm text-center border-r border-gray-300">
                                @if($sched)
                                    <div class="font-bold text-ustpBlack">{{ $sched->subject_code }}</div>
                                    <div class="text-xs text-ustpBlue">{{ $sched->section }}</div>
                                    <div class="text-xs text-gray-600 hidden sm:block">{{ $sched->instructor_name ?? ($sched->instructor->name ?? '') }}</div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
