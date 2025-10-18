@extends('layouts.app')

@section('title', 'Schedule Board - USTP DMCRS')

@section('content')


<div class="max-w-7xl mx-auto bg-white p-4 sm:p-6 rounded-xl shadow-lg">
    <!-- Semester Display -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-4">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-ustpBlue">📅 Class Schedule Board</h2>
            <div class="mt-2">
                <span class="inline-block bg-ustpGold text-ustpBlue font-semibold px-3 sm:px-4 py-1 rounded-full shadow-sm text-base sm:text-lg">
                    {{ $semester ?? '2025 - 2026' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-gray-50 p-3 sm:p-4 rounded-lg mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4 items-start sm:items-center">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 w-full sm:w-auto">
                <label class="font-semibold text-gray-700 text-sm sm:text-base">Filter by Room:</label>
                <select id="roomFilter" class="border rounded px-2 sm:px-3 py-1 text-sm sm:text-base w-full sm:w-auto">
                    <option value="">All Rooms</option>
                    @foreach(collect($schedules)->pluck('room')->unique()->sort() as $room)
                        <option value="{{ $room }}">{{ $room }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 w-full sm:w-auto">
                <label class="font-semibold text-gray-700 text-sm sm:text-base">Filter by Day:</label>
                <select id="dayFilter" class="border rounded px-2 sm:px-3 py-1 text-sm sm:text-base w-full sm:w-auto">
                    <option value="">All Days</option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="flex flex-wrap gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 bg-red-200 border border-red-300 rounded"></div>
            <span class="text-sm">BSIT</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 bg-blue-200 border border-blue-300 rounded"></div>
            <span class="text-sm">BTLED</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 bg-green-200 border border-green-300 rounded"></div>
            <span class="text-sm">BSA</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 bg-yellow-200 border border-yellow-300 rounded"></div>
            <span class="text-sm">Others</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 bg-red-500 border border-red-600 rounded"></div>
            <span class="text-sm">Conflict/Overlap</span>
        </div>
    </div>

    <!-- Schedule Cards Layout -->
    <div class="space-y-6">
        @php
            $roomNames = collect($schedules)->pluck('room')->unique()->sort()->values();
        @endphp

        @foreach($roomNames as $roomName)
            <div class="room-section" data-room="{{ $roomName }}">
                <h3 class="text-xl font-bold text-ustpBlue mb-4 border-b-2 border-ustpGold pb-2">
                    🏫 {{ $roomName }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @php
                        $roomSchedules = collect($schedules)->where('room', $roomName)->sortBy('time_start');

                        // Check for conflicts
                        foreach($roomSchedules as $sched) {
                            $sched->conflicts = [];
                            foreach($roomSchedules as $other) {
                                if($other->id === $sched->id) continue;
                                if($sched->day_of_week !== $other->day_of_week) continue;

                                $schedStart = strtotime($sched->time_start);
                                $schedEnd = strtotime($sched->time_end);
                                $otherStart = strtotime($other->time_start);
                                $otherEnd = strtotime($other->time_end);

                                // Check for overlap
                                if(!($schedEnd <= $otherStart || $schedStart >= $otherEnd)) {
                                    $sched->conflicts[] = $other;
                                }
                            }
                        }
                    @endphp

                    @foreach($roomSchedules as $sched)
                        @php
                            $startObj = DateTime::createFromFormat('H:i:s', $sched->time_start) ?: DateTime::createFromFormat('H:i', $sched->time_start);
                            $endObj = DateTime::createFromFormat('H:i:s', $sched->time_end) ?: DateTime::createFromFormat('H:i', $sched->time_end);
                            $start = $startObj ? $startObj->format('g:i A') : $sched->time_start;
                            $end = $endObj ? $endObj->format('g:i A') : $sched->time_end;

                            // Department colors
                            $bgColor = 'bg-gray-100 border-gray-300';
                            $textColor = 'text-gray-800';
                            if(isset($sched->section)) {
                                if(str_contains($sched->section, 'BSIT')) {
                                    $bgColor = 'bg-red-100 border-red-300';
                                    $textColor = 'text-red-800';
                                } elseif(str_contains($sched->section, 'BTLED')) {
                                    $bgColor = 'bg-blue-100 border-blue-300';
                                    $textColor = 'text-blue-800';
                                } elseif(str_contains($sched->section, 'BSA')) {
                                    $bgColor = 'bg-green-100 border-green-300';
                                    $textColor = 'text-green-800';
                                } else {
                                    $bgColor = 'bg-yellow-100 border-yellow-300';
                                    $textColor = 'text-yellow-800';
                                }
                            }

                            // Conflict styling
                            if(count($sched->conflicts) > 0) {
                                $bgColor = 'bg-red-200 border-red-500';
                                $textColor = 'text-red-900';
                            }
                        @endphp

                        <div class="schedule-card {{ $bgColor }} border-2 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                             data-day="{{ $sched->day_of_week }}"
                             data-room="{{ $roomName }}"
                             onclick="showScheduleDetails({{ json_encode($sched) }}, {{ json_encode($sched->conflicts) }})">

                            <!-- Header -->
                            <div class="flex justify-between items-start mb-2">
                                <div class="font-bold text-lg {{ $textColor }}">
                                    {{ $sched->subject_code ?? 'N/A' }}
                                </div>
                                @if(count($sched->conflicts) > 0)
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                        ⚠️ {{ count($sched->conflicts) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Subject Title -->
                            <div class="text-sm font-medium {{ $textColor }} mb-2">
                                {{ $sched->subject_title ?? 'No Title' }}
                            </div>

                            <!-- Section -->
                            <div class="text-sm font-semibold {{ $textColor }} mb-2">
                                📚 {{ $sched->section ?? 'No Section' }}
                            </div>

                            <!-- Day & Time -->
                            <div class="text-sm {{ $textColor }} mb-2">
                                📅 {{ $sched->day_of_week ?? 'No Day' }}
                            </div>
                            <div class="text-sm font-medium {{ $textColor }} mb-2">
                                🕐 {{ $start }} - {{ $end }}
                            </div>

                            <!-- Instructor -->
                            <div class="text-xs {{ $textColor }} mb-3">
                                👨‍🏫 {{ $sched->instructor_name ?? ($sched->instructor->name ?? 'No Instructor') }}
                            </div>

                            <!-- View Only Notice -->
                            <div class="pt-2 border-t border-gray-300 text-center">
                                <span class="text-xs text-gray-500 italic">View Only</span>
                            </div>
                        </div>
                    @endforeach

                    @if($roomSchedules->isEmpty())
                        <div class="col-span-full text-center text-gray-500 py-8">
                            No schedules found for {{ $roomName }}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal for Schedule Details -->
<div id="scheduleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="closeScheduleModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-96 overflow-y-auto" onclick="event.stopPropagation()">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-ustpBlue">Schedule Details</h3>
                    <button onclick="closeScheduleModal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="modalContent"></div>
            </div>
        </div>
    </div>
</div>

<script>
// Filter functionality
document.getElementById('roomFilter').addEventListener('change', function() {
    const selectedRoom = this.value;
    const roomSections = document.querySelectorAll('.room-section');

    roomSections.forEach(section => {
        if (selectedRoom === '' || section.dataset.room === selectedRoom) {
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
        }
    });
});

document.getElementById('dayFilter').addEventListener('change', function() {
    const selectedDay = this.value;
    const scheduleCards = document.querySelectorAll('.schedule-card');

    scheduleCards.forEach(card => {
        if (selectedDay === '' || card.dataset.day === selectedDay) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Modal functionality
function showScheduleDetails(schedule, conflicts) {
    const modal = document.getElementById('scheduleModal');
    const content = document.getElementById('modalContent');

    let conflictsHtml = '';
    if (conflicts && conflicts.length > 0) {
        conflictsHtml = `
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <h4 class="font-bold text-red-800 mb-2">⚠️ Schedule Conflicts (${conflicts.length})</h4>
                <div class="space-y-2">
                    ${conflicts.map(conflict => `
                        <div class="bg-white p-3 rounded border border-red-300">
                            <div class="font-semibold">${conflict.subject_code || 'N/A'} - ${conflict.section || 'N/A'}</div>
                            <div class="text-sm text-gray-600">${conflict.subject_title || 'No Title'}</div>
                            <div class="text-sm">${conflict.day_of_week || 'N/A'} | ${formatTime(conflict.time_start)} - ${formatTime(conflict.time_end)}</div>
                            <div class="text-sm">Instructor: ${conflict.instructor_name || 'N/A'}</div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }

    content.innerHTML = `
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="font-semibold text-gray-700">Subject Code:</label>
                    <div class="text-lg">${schedule.subject_code || 'N/A'}</div>
                </div>
                <div>
                    <label class="font-semibold text-gray-700">Section:</label>
                    <div class="text-lg">${schedule.section || 'N/A'}</div>
                </div>
                <div>
                    <label class="font-semibold text-gray-700">Subject Title:</label>
                    <div>${schedule.subject_title || 'No Title'}</div>
                </div>
                <div>
                    <label class="font-semibold text-gray-700">Room:</label>
                    <div>${schedule.room || 'N/A'}</div>
                </div>
                <div>
                    <label class="font-semibold text-gray-700">Day:</label>
                    <div>${schedule.day_of_week || 'N/A'}</div>
                </div>
                <div>
                    <label class="font-semibold text-gray-700">Time:</label>
                    <div>${formatTime(schedule.time_start)} - ${formatTime(schedule.time_end)}</div>
                </div>
                <div class="md:col-span-2">
                    <label class="font-semibold text-gray-700">Instructor:</label>
                    <div>${schedule.instructor_name || 'No Instructor'}</div>
                </div>
            </div>
            ${conflictsHtml}
        </div>
    `;

    modal.classList.remove('hidden');
}

function closeScheduleModal() {
    document.getElementById('scheduleModal').classList.add('hidden');
}

function formatTime(timeStr) {
    if (!timeStr) return 'N/A';

    try {
        const time = new Date('1970-01-01T' + timeStr);
        return time.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    } catch (e) {
        return timeStr;
    }
}
</script>

@endsection
