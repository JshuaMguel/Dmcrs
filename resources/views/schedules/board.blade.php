@extends('layouts.app')

@section('title', 'Schedule Board (Redirecting) - USTP DMCRS')

@section('content')
<div class="py-16">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-8 text-center">
        <h1 class="text-2xl font-bold text-ustpBlue mb-4">This page has moved</h1>
        <p class="text-gray-700 mb-6">The schedule board has a new unified view. Please use the button below.</p>
        <a href="{{ route('schedules.index') }}" class="inline-block px-6 py-3 bg-ustpBlue text-white rounded-lg hover:bg-blue-800">Go to Schedule Board</a>
    </div>
    <script>
        // Auto-redirect after a short delay
        setTimeout(function(){ window.location.href = "{{ route('schedules.index') }}"; }, 800);
    </script>

        <!-- Day Filter -->
        <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
            <div class="flex gap-2 overflow-x-auto">
                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $d)
                    <a href="{{ route('schedules.index', ['day' => $d]) }}"
                       class="px-6 py-2 rounded-lg font-semibold whitespace-nowrap transition
                              {{ ($selectedDay ?? 'Monday') == $d ? 'bg-ustpBlue text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ $d }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Schedule Grid -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-ustpBlue">
                            <th class="border border-gray-300 px-4 py-3 text-white font-bold sticky left-0 bg-ustpBlue z-10">
                                TIME
                            </th>
                            @foreach($rooms as $room)
                                <th class="border border-gray-300 px-4 py-3 text-white font-bold min-w-[200px]">
                                    {{ $room->name }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timeSlots as $time)
                            <tr>
                                <td class="border border-gray-300 px-4 py-3 font-semibold text-gray-700 bg-gray-50 sticky left-0 z-10 whitespace-nowrap">
                                    {{ $time }}
                                </td>
                                @foreach($rooms as $room)
                                    <td class="border border-gray-300 p-2 align-top relative" style="height: 100px; overflow: visible;">
                                        @php
                                            // Find schedules that START within this 30-minute time slot
                                            $cellSchedules = $schedules->filter(function($sched) use ($time, $room) {
                                                // Convert slot time to 24-hour format
                                                $slotStart = date('H:i', strtotime($time));
                                                $slotEnd = date('H:i', strtotime($time . ' +30 minutes'));

                                                // Get schedule start time in 24-hour format
                                                $schedStart = date('H:i', strtotime($sched->time_start));

                                                // Show if schedule starts within this 30-min slot (e.g., 2:23 PM shows in 2:00 PM slot)
                                                return $sched->room == $room->name &&
                                                       $schedStart >= $slotStart &&
                                                       $schedStart < $slotEnd;
                                            });
                                        @endphp

                                        @foreach($cellSchedules as $schedule)
                                            @php
                                                // Determine color based on department
                                                $deptName = strtoupper($schedule->department->code ?? $schedule->department->name ?? '');

                                                if (str_contains($deptName, 'BSIT') || str_contains($deptName, 'IT')) {
                                                    $bgColor = 'bg-red-500';
                                                    $textColor = 'text-white';
                                                } elseif (str_contains($deptName, 'BSA') || str_contains($deptName, 'ACCOUNTANCY')) {
                                                    $bgColor = 'bg-green-500';
                                                    $textColor = 'text-white';
                                                } elseif (str_contains($deptName, 'BTLED') || str_contains($deptName, 'EDUCATION')) {
                                                    $bgColor = 'bg-blue-500';
                                                    $textColor = 'text-white';
                                                } else {
                                                    $bgColor = 'bg-yellow-500';
                                                    $textColor = 'text-gray-900';
                                                }

                                                // Calculate exact height based on duration
                                                // Each 30-min slot = 100px, calculate exact slots covered
                                                $start = strtotime($schedule->time_start);
                                                $end = strtotime($schedule->time_end);
                                                $durationMinutes = ($end - $start) / 60; // total minutes
                                                $numSlots = ceil($durationMinutes / 30); // number of 30-min slots
                                                $height = $numSlots * 100; // 100px per slot
                                            @endphp

                                    <div class="{{ $bgColor }} {{ $textColor }} rounded-lg p-3 shadow-md absolute top-0 left-2 right-2 text-center"
                                                 style="height: {{ $height }}px; z-index: 10;">

                                                <!-- Subject Code & Title -->
                                                <div class="font-bold text-sm mb-1">
                                                    {{ $schedule->subject_code }}
                                                </div>
                                                <div class="text-xs opacity-90 mb-2 line-clamp-2">
                                                    {{ $schedule->subject_title }}
                                                </div>

                                                <!-- Instructor -->
                                                <div class="text-xs opacity-90 mb-1">
                                                    👨‍🏫 {{ $schedule->instructor->name ?? 'N/A' }}
                                                </div>

                                                <!-- Section -->
                                                <div class="text-xs opacity-90 mb-1">
                                                    📚 {{ $schedule->section }}
                                                </div>

                                                <!-- Time -->
                                                <div class="text-xs font-semibold mt-2 pt-2 border-t border-white/30">
                                                    ⏰ {{ date('g:i A', strtotime($schedule->time_start)) }} - {{ date('g:i A', strtotime($schedule->time_end)) }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-gray-600 text-sm">Total Schedules Today</div>
                <div class="text-2xl font-bold text-ustpBlue">{{ $schedules->count() }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-gray-600 text-sm">BSIT Classes</div>
                <div class="text-2xl font-bold text-red-500">
                    {{ $schedules->filter(fn($s) => str_contains(strtoupper($s->department->code ?? ''), 'IT'))->count() }}
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-gray-600 text-sm">BSA Classes</div>
                <div class="text-2xl font-bold text-green-500">
                    {{ $schedules->filter(fn($s) => str_contains(strtoupper($s->department->code ?? ''), 'BSA'))->count() }}
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-gray-600 text-sm">BTLED Classes</div>
                <div class="text-2xl font-bold text-blue-500">
                    {{ $schedules->filter(fn($s) => str_contains(strtoupper($s->department->name ?? ''), 'EDUCATION'))->count() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
