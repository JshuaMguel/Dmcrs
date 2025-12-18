@extends('layouts.app')

@section('title', 'System Reports - USTP DMCRS')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-4 sm:p-6 rounded-xl shadow-lg">
    <!-- Header Section -->
    <div class="mb-6 sm:mb-8">
        <div class="flex items-center gap-3 sm:gap-4">
            <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-ustpBlue rounded-full">
                <span class="text-lg sm:text-2xl text-white">📊</span>
            </div>
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-ustpBlue">System Reports</h2>
                <p class="text-sm sm:text-base text-gray-600">Generate and export system reports</p>
            </div>
        </div>
    </div>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
            $reports = [
                ['type' => 'users', 'icon' => '👥', 'title' => 'Users Report', 'description' => 'All system users'],
                ['type' => 'departments', 'icon' => '🏢', 'title' => 'Departments Report', 'description' => 'Department information'],
                ['type' => 'subjects', 'icon' => '📚', 'title' => 'Subjects Report', 'description' => 'All subjects'],
                ['type' => 'sections', 'icon' => '👨‍👩‍👧‍👦', 'title' => 'Sections Report', 'description' => 'All sections'],
                ['type' => 'schedules', 'icon' => '📅', 'title' => 'Schedules Report', 'description' => 'Class schedules'],
                ['type' => 'rooms', 'icon' => '🏫', 'title' => 'Rooms Report', 'description' => 'Room information'],
                ['type' => 'students', 'icon' => '🎓', 'title' => 'Students Report', 'description' => 'All students'],
            ];
        @endphp

        @foreach($reports as $report)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-ustpBlue/10 rounded-full p-3">
                            <span class="text-2xl">{{ $report['icon'] }}</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-ustpBlue">{{ $report['title'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $report['description'] }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-2 mt-4">
                    <a href="{{ route('admin.reports.show', $report['type']) }}"
                       class="flex-1 bg-ustpBlue text-white px-4 py-2 rounded-lg hover:bg-ustpBlue/90 font-medium text-center text-sm transition-colors">
                        View
                    </a>
                    <a href="{{ route('admin.reports.exportPdf', $report['type']) }}"
                       class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 font-medium text-center text-sm transition-colors">
                        PDF
                    </a>
                    <a href="{{ route('admin.reports.exportExcel', $report['type']) }}"
                       class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium text-center text-sm transition-colors">
                        Excel
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection


