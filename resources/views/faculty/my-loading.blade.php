@extends('layouts.app')

@section('title', 'My Class Loading - USTP DMCRS')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-4 sm:p-6 rounded-xl shadow-lg">
    <!-- Header Section -->
    <div class="mb-6 sm:mb-8">
        <div class="flex items-center gap-3 sm:gap-4">
            <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-ustpBlue rounded-full">
                <span class="text-lg sm:text-2xl text-white">📚</span>
            </div>
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-ustpBlue">My Class Loading</h2>
                <p class="text-sm sm:text-base text-gray-600">View your assigned classes and subjects</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
        <form method="GET" action="{{ route('faculty.my-loading') }}" id="filterForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                <select name="semester" id="semester_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent" onchange="this.form.submit()">
                    <option value="">All Semesters</option>
                    <option value="1st" {{ request('semester') == '1st' ? 'selected' : '' }}>1st Semester</option>
                    <option value="2nd" {{ request('semester') == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                    <option value="summer" {{ request('semester') == 'summer' ? 'selected' : '' }}>Summer</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">School Year</label>
                <input type="text" name="school_year" id="school_year_filter" value="{{ request('school_year') }}"
                       placeholder="e.g., 2024-2025"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent"
                       onchange="this.form.submit()">
            </div>
            <div class="md:col-span-3 flex gap-2">
                <button type="submit" class="bg-ustpBlue text-white px-4 py-2 rounded-lg hover:bg-ustpBlue/90 font-medium">
                    Filter
                </button>
                <a href="{{ route('faculty.my-loading') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 font-medium">
                    Clear
                </a>
            </div>
        </form>
    </div>

    @if($myClasses->count() > 0)
        <!-- Classes Grouped by Day -->
        <div class="space-y-6">
            @php
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            @endphp

            @foreach($days as $day)
                @if(isset($classesByDay[$day]) && $classesByDay[$day]->count() > 0)
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                        <div class="bg-ustpBlue px-6 py-4">
                            <h3 class="text-lg font-bold text-white">{{ $day }}</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($classesByDay[$day] as $class)
                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                            <div>
                                                <label class="text-xs font-medium text-gray-600">Subject Code</label>
                                                <p class="text-sm font-bold text-ustpBlue">{{ $class->subject_code }}</p>
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="text-xs font-medium text-gray-600">Subject Title</label>
                                                <p class="text-sm text-gray-900">{{ $class->subject_title ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <label class="text-xs font-medium text-gray-600">Section</label>
                                                <p class="text-sm text-gray-900">{{ $class->section }}</p>
                                            </div>
                                            <div>
                                                <label class="text-xs font-medium text-gray-600">Total Students</label>
                                                <p class="text-sm font-semibold text-ustpBlue">{{ $class->total_students ?? 0 }}</p>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3">
                                            <div>
                                                <label class="text-xs font-medium text-gray-600">Time</label>
                                                <p class="text-sm text-gray-900">
                                                    {{ \Carbon\Carbon::parse($class->time_start)->format('g:i A') }} - 
                                                    {{ \Carbon\Carbon::parse($class->time_end)->format('g:i A') }}
                                                </p>
                                            </div>
                                            <div>
                                                <label class="text-xs font-medium text-gray-600">Room</label>
                                                <p class="text-sm text-gray-900">{{ $class->room }}</p>
                                            </div>
                                            @if($class->units)
                                            <div>
                                                <label class="text-xs font-medium text-gray-600">Units</label>
                                                <span class="text-xs bg-ustpGold text-ustpBlue px-2 py-1 rounded font-medium">
                                                    {{ $class->units }} units
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="mt-2 text-xs text-gray-500">
                                            <span class="font-medium">Semester:</span> {{ ucfirst($class->header->semester) }} | 
                                            <span class="font-medium">SY:</span> {{ $class->header->school_year }} |
                                            <span class="font-medium">Department:</span> {{ $class->header->department->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Summary -->
        <div class="mt-6 bg-ustpBlue/10 p-4 rounded-lg border border-ustpBlue/20">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-sm text-gray-600">Total Classes</p>
                    <p class="text-2xl font-bold text-ustpBlue">{{ $myClasses->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Units</p>
                    <p class="text-2xl font-bold text-ustpBlue">
                        {{ $myClasses->sum('units') ?? 0 }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Active Loading</p>
                    <p class="text-2xl font-bold text-ustpBlue">
                        {{ $myClasses->pluck('header.school_year')->unique()->count() }}
                    </p>
                </div>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-12">
            <span class="text-6xl mb-4">📚</span>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Class Loading Found</h3>
            <p class="text-gray-500 text-center max-w-md mb-6">
                You don't have any assigned classes yet. Please contact your Department Chair to assign your class loading.
            </p>
        </div>
    @endif
</div>
@endsection

