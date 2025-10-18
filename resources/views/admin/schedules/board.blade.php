@extends('layouts.app')

@section('title', 'Schedule Board - USTP DMCRS')

@section('content')
<div class="py-8 bg-gradient-to-br from-ustpBlue/5 via-white to-ustpGold/5 min-h-screen">
    <div class="max-w-full mx-auto px-4">
        @php
            // Allow this board to be reused for view-only roles (faculty/chair/head)
            // If not explicitly provided, infer from role
            $canManage = $canManage ?? in_array(Auth::user()->role ?? null, ['admin','super_admin']);
        @endphp
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-ustpBlue mb-2">📅 Schedule Board</h1>
                    <p class="text-gray-600">Color-coded by Department • Visual Schedule Management</p>
                </div>
                @if($canManage)
                    <div class="flex gap-4">
                        <a href="{{ route('admin.schedules.index') }}"
                           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                            📋 List View
                        </a>
                        <a href="{{ route('admin.schedules.create') }}"
                           class="px-4 py-2 bg-ustpBlue text-white rounded-lg hover:bg-blue-800 transition">
                            ➕ Add Schedule
                        </a>
                    </div>
                @endif
            </div>

            <!-- Legend -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-sm font-semibold text-gray-700 mb-2">Department Color Legend:</p>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-red-500 rounded"></div>
                        <span class="text-sm font-medium">BSIT</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-green-500 rounded"></div>
                        <span class="text-sm font-medium">BSA</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-blue-500 rounded"></div>
                        <span class="text-sm font-medium">BTLED</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-yellow-500 rounded"></div>
                        <span class="text-sm font-medium">Other Departments</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Day Filter -->
        <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
            <div class="flex gap-2 overflow-x-auto">
                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $d)
                    <a href="{{ $canManage ? route('admin.schedules.board', ['day' => $d]) : route('schedules.index', ['day' => $d]) }}"
                       class="px-6 py-2 rounded-lg font-semibold whitespace-nowrap transition
                              {{ ($selectedDay ?? 'Monday') == $d ? 'bg-ustpBlue text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ $d }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Schedule Grid -->
        @php
            // Grid configuration
            $slotMinutes = 30;               // minutes per row
            $slotHeight = 100;               // px per row (kept as-is)
            $totalSlots = count($timeSlots); // total rows
            $gridHeight = $totalSlots * $slotHeight;

            // Day start for positioning (take first time slot start)
            $dayStartTs = strtotime($timeSlots[0]);
        @endphp

        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <!-- Day banner like the sheet -->
            <div class="bg-yellow-400 text-black font-bold text-center uppercase tracking-wide py-1">
                {{ strtoupper($selectedDay ?? 'Monday') }}
            </div>

            <div class="grid" style="grid-template-columns: 140px 1fr;">
                <!-- Time Axis (left) -->
                <div>
                    <div class="bg-ustpBlue text-white font-bold px-4 py-3 border-b border-gray-300 sticky top-0 z-10">TIME</div>
                    <div class="relative" style="height: {{ $gridHeight }}px;">
                        @foreach($timeSlots as $i => $label)
                            @php
                                $from = strtotime($label);
                                $to = strtotime($label . ' +'.$slotMinutes.' minutes');
                                $top = $i * $slotHeight;
                                $isHour = intval(date('i', $from)) === 0; // minute == 00
                            @endphp
                            <div class="px-3 flex items-center justify-between text-sm text-gray-700 {{ $isHour ? 'font-semibold' : '' }}"
                                 style="position: absolute; top: {{ $top }}px; height: {{ $slotHeight }}px; left: 0; right: 0;">
                                <span>{{ date('g:i', $from) }} - {{ date('g:i', $to) }}</span>
                            </div>
                            <!-- Hour separator line -->
                            <div class="absolute left-0 right-0 {{ $isHour ? 'border-t-2 border-gray-400' : 'border-t border-gray-200' }}"
                                 style="top: {{ $top }}px"></div>
                        @endforeach
                        <!-- Bottom line -->
                        <div class="absolute left-0 right-0 border-t-2 border-gray-400" style="top: {{ $gridHeight }}px"></div>
                    </div>
                </div>

                <!-- Rooms Area (right) -->
                <div class="overflow-x-auto">
                    <!-- Header with room names -->
                    <div class="grid bg-ustpBlue text-white font-bold border-b border-gray-300 sticky top-0 z-10"
                         style="grid-template-columns: repeat({{ count($rooms) }}, minmax(200px, 1fr));">
                        @foreach($rooms as $room)
                            <div class="px-4 py-3 border-l border-gray-300">{{ $room->name }}</div>
                        @endforeach
                    </div>

                    <!-- Board: grid of room columns with overlay blocks -->
                    <div class="relative" style="height: {{ $gridHeight }}px;">
                        <!-- Horizontal time lines across all rooms -->
                        @foreach($timeSlots as $i => $label)
                            @php $isHour = intval(date('i', strtotime($label))) === 0; @endphp
                            <div class="absolute left-0 right-0 {{ $isHour ? 'border-t-2 border-gray-300' : 'border-t border-gray-200' }}"
                                 style="top: {{ $i * $slotHeight }}px"></div>
                        @endforeach
                        <div class="absolute left-0 right-0 border-t-2 border-gray-300" style="top: {{ $gridHeight }}px"></div>

                        <!-- Room columns -->
                        <div class="grid h-full" style="grid-template-columns: repeat({{ count($rooms) }}, minmax(200px, 1fr));">
                            @foreach($rooms as $room)
                                <div class="relative border-l border-gray-200">
                                    @php
                                        $roomSchedules = $schedules->filter(fn($s) => $s->room == $room->name);
                                    @endphp

                                    @foreach($roomSchedules as $schedule)
                                        @php
                                            // Colors by department
                                            $deptName = strtoupper($schedule->department->code ?? $schedule->department->name ?? '');
                                            if (str_contains($deptName, 'BSIT') || str_contains($deptName, 'IT')) {
                                                $bgColor = 'bg-red-500'; $textColor = 'text-white';
                                            } elseif (str_contains($deptName, 'BSA') || str_contains($deptName, 'ACCOUNTANCY')) {
                                                $bgColor = 'bg-green-500'; $textColor = 'text-white';
                                            } elseif (str_contains($deptName, 'BTLED') || str_contains($deptName, 'EDUCATION')) {
                                                $bgColor = 'bg-blue-500'; $textColor = 'text-white';
                                            } else { $bgColor = 'bg-yellow-500'; $textColor = 'text-gray-900'; }

                                            // Position and size
                                            $startTs = strtotime($schedule->time_start);
                                            $endTs = strtotime($schedule->time_end);
                                            $topMinutes = max(0, ($startTs - $dayStartTs) / 60);
                                            $durationMinutes = max(0, ($endTs - $startTs) / 60);
                                            $topPx = ($topMinutes / $slotMinutes) * $slotHeight;
                                            $heightPx = ($durationMinutes / $slotMinutes) * $slotHeight;

                                            // Labels
                                            $deptLabel = strtoupper($schedule->department->code ?? ($schedule->department->name ?? ''));
                                            $typeRaw = strtoupper($schedule->type ?? '');
                                            $typeLabel = $typeRaw === 'MAKEUP' ? 'Make-up Class' : 'Regular Class';
                                        @endphp

                                          <div class="absolute left-2 right-2 {{ $bgColor }} {{ $textColor }} rounded-md p-3 shadow-md hover:shadow-lg {{ $canManage ? 'cursor-pointer' : 'cursor-default' }} text-center"
                                    style="top: {{ $topPx }}px; height: {{ $heightPx }}px; z-index: 20;"
                                    @if($canManage)
                                       onclick="window.location='{{ route('admin.schedules.edit', $schedule->id) }}'"
                                    @endif>

                                            <div class="flex flex-col items-center mb-2">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/20 {{ $textColor }}">{{ $deptLabel ?: 'DEPT' }}</span>
                                                <span class="mt-1 text-[11px] font-semibold opacity-95">{{ $typeLabel }}</span>
                                            </div>

                                            <div class="font-bold text-sm mb-1">{{ $schedule->subject_code }}</div>
                                            <div class="text-xs opacity-90 mb-2 line-clamp-2">{{ $schedule->subject_title }}</div>
                                            <div class="text-xs opacity-90 mb-1">👨‍🏫 {{ $schedule->instructor->name ?? 'N/A' }}</div>
                                            <div class="text-xs opacity-90 mb-1">📚 {{ $schedule->section }}</div>
                                            <div class="text-xs font-semibold mt-2 pt-2 border-t border-white/30">⏰ {{ date('g:i A', strtotime($schedule->time_start)) }} - {{ date('g:i A', strtotime($schedule->time_end)) }}</div>

                                            @if($canManage)
                                                <!-- Icon-only edit affordance in the top-right of the block -->
                                                <a href="{{ route('admin.schedules.edit', $schedule->id) }}" title="Edit" aria-label="Edit schedule"
                                                   class="absolute top-1 right-1 bg-white/80 hover:bg-white text-ustpBlue rounded-full p-1 shadow"
                                                   onclick="event.stopPropagation();">
                                                    ✏️
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        @php
            $deptClassifier = function($s) {
                $code = strtoupper($s->department->code ?? '');
                $name = strtoupper($s->department->name ?? '');
                return $code . ' ' . $name;
            };

            $bsitCount = $schedules->filter(function($s) use ($deptClassifier) {
                $dept = $deptClassifier($s);
                return str_contains($dept, 'BSIT') || str_contains($dept, ' IT'); // space IT to avoid matching 'BSA' accidentally
            })->count();

            $bsaCount = $schedules->filter(function($s) use ($deptClassifier) {
                $dept = $deptClassifier($s);
                return str_contains($dept, 'BSA') || str_contains($dept, 'ACCOUNTANCY');
            })->count();

            $btledCount = $schedules->filter(function($s) use ($deptClassifier) {
                $dept = $deptClassifier($s);
                return str_contains($dept, 'BTLED') || str_contains($dept, 'EDUCATION');
            })->count();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-gray-600 text-sm">Total Schedules</div>
                <div class="text-2xl font-bold text-ustpBlue">{{ $schedules->count() }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-gray-600 text-sm">BSIT Classes</div>
                <div class="text-2xl font-bold text-red-500">{{ $bsitCount }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-gray-600 text-sm">BSA Classes</div>
                <div class="text-2xl font-bold text-green-500">{{ $bsaCount }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-gray-600 text-sm">BTLED Classes</div>
                <div class="text-2xl font-bold text-blue-500">{{ $btledCount }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
