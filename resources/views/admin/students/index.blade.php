@extends('layouts.app')

@section('title', 'Manage Students - USTP DMCRS')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-4 sm:p-6 rounded-xl shadow-lg">
    <!-- Header Section -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col items-center gap-3 sm:gap-4 md:flex-row md:justify-between">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-ustpBlue rounded-full">
                    <span class="text-lg sm:text-2xl text-white">👥</span>
                </div>
                <div class="text-center md:text-left">
                    <h2 class="text-2xl sm:text-3xl font-bold text-ustpBlue">Manage Students</h2>
                    <p class="text-sm sm:text-base text-gray-600">Add, edit, and manage all students</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                <a href="{{ route('admin.students.create') }}"
                   class="bg-ustpBlue text-white hover:bg-ustpBlue/90 px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium flex items-center justify-center gap-2 shadow-sm w-full sm:w-auto">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Student
                </a>
                <form id="importForm" action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data" class="inline">
                    @csrf
                    <label for="csv_file" class="bg-ustpGold text-ustpBlue hover:bg-ustpGold/90 px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium flex items-center justify-center gap-2 shadow-sm w-full sm:w-auto cursor-pointer">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <span id="importText">Import CSV</span>
                        <span id="importLoading" class="hidden">Uploading...</span>
                    </label>
                    <input type="file" id="csv_file" name="csv_file" accept=".csv,.txt" class="hidden" onchange="handleFileSelect()" required>
                </form>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <span class="text-green-500 text-xl mr-3">✅</span>
                <div>
                    <h4 class="font-semibold text-green-800">Success!</h4>
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <span class="text-yellow-500 text-xl mr-3">⚠️</span>
                <div>
                    <h4 class="font-semibold text-yellow-800">Warning!</h4>
                    <p class="text-yellow-700">{{ session('warning') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('import_errors'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <span class="text-red-500 text-xl mr-3">❌</span>
                <div>
                    <h4 class="font-semibold text-red-800">Import Errors:</h4>
                    <ul class="text-red-700 list-disc list-inside mt-2">
                        @foreach(session('import_errors') as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- CSV Format Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex items-start">
            <span class="text-blue-500 text-xl mr-3">ℹ️</span>
            <div>
                <h4 class="font-semibold text-blue-800 mb-2">CSV Format Guide</h4>
                <p class="text-blue-700 text-sm mb-2">Your CSV file should include these columns (case-insensitive):</p>
                <ul class="text-blue-700 text-sm list-disc list-inside space-y-1">
                    <li><strong>Required:</strong> student_id / student_id_number / id, first_name, last_name, email, program / department / dept, year_level / year / level</li>
                    <li><strong>Optional:</strong> middle_name, section, contact_number / contact / phone, status (active/inactive/graduated/dropped)</li>
                </ul>
                <p class="text-blue-700 text-sm mt-2"><strong>Example:</strong> student_id,first_name,last_name,email,program,year_level,section,status</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
        <form method="GET" action="{{ route('admin.students.index') }}" id="filterForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search_input" value="{{ request('search') }}"
                       placeholder="Name, ID, Email..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                <select name="department_id" id="department_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent" onchange="autoSubmitFilter()">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent" onchange="autoSubmitFilter()">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Graduated</option>
                    <option value="dropped" {{ request('status') == 'dropped' ? 'selected' : '' }}>Dropped</option>
                </select>
            </div>
            <div class="md:col-span-3 flex gap-2">
                <button type="submit" class="bg-ustpBlue text-white px-4 py-2 rounded-lg hover:bg-ustpBlue/90 font-medium">
                    Filter
                </button>
                <a href="{{ route('admin.students.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 font-medium">
                    Clear
                </a>
            </div>
        </form>
    </div>

    @if($students->count())
        <!-- Students Table -->
        <div class="bg-white rounded-lg shadow-md overflow-x-auto border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-ustpBlue">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Student ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Department</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Year Level</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Section</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                        <th class="px-8 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($students as $student)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-ustpBlue">{{ $student->student_id_number }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $student->full_name ?? ($student->first_name . ' ' . $student->last_name) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $student->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $student->department->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $student->year_level }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $student->section ? ($student->section->full_name ?? $student->section->section_name) : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-100 text-green-800',
                                        'inactive' => 'bg-gray-100 text-gray-800',
                                        'graduated' => 'bg-blue-100 text-blue-800',
                                        'dropped' => 'bg-red-100 text-red-800',
                                    ];
                                    $color = $statusColors[$student->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.students.edit', $student) }}"
                                       class="p-2 bg-yellow-100 text-yellow-800 rounded-lg hover:bg-yellow-200 transition-colors"
                                       title="Edit">
                                        ✏️
                                    </a>
                                    @if($student->status == 'active')
                                        <form action="{{ route('admin.students.deactivate', $student) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors" title="Deactivate">
                                                ⏸️
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.students.activate', $student) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 bg-green-100 text-green-800 rounded-lg hover:bg-green-200 transition-colors" title="Activate">
                                                ▶️
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($students->hasPages())
            <div class="mt-6">
                {{ $students->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-12">
            <span class="text-6xl mb-4">👥</span>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Students Found</h3>
            <p class="text-gray-500 text-center max-w-md mb-6">
                @if(request('department_id'))
                    No students found in the selected department. Try selecting a different department or clearing the filters.
                @elseif(request('status'))
                    No students found with the selected status. Try selecting a different status or clearing the filters.
                @elseif(request('search'))
                    No students found matching your search. Try a different search term or clearing the filters.
                @else
                    Get started by creating your first student or importing from CSV.
                @endif
            </p>
            <div class="flex gap-3">
                @if(request()->anyFilled(['search', 'department_id', 'status']))
                    <a href="{{ route('admin.students.index') }}"
                       class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-medium flex items-center gap-2">
                        Clear Filters
                    </a>
                @endif
                <a href="{{ route('admin.students.create') }}"
                   class="bg-ustpBlue text-white px-6 py-3 rounded-lg hover:bg-ustpBlue/90 font-medium flex items-center gap-2">
                    <span>➕</span>
                    Create Student
                </a>
            </div>
        </div>
    @endif
</div>

<script>
    function handleFileSelect() {
        const fileInput = document.getElementById('csv_file');
        const form = document.getElementById('importForm');
        const importText = document.getElementById('importText');
        const importLoading = document.getElementById('importLoading');
        
        if (fileInput.files.length > 0) {
            // Show loading state
            if (importText) importText.classList.add('hidden');
            if (importLoading) importLoading.classList.remove('hidden');
            
            // Submit form
            form.submit();
        }
    }

    // Auto-submit filter form when dropdowns change
    function autoSubmitFilter() {
        document.getElementById('filterForm').submit();
    }
</script>
@endsection


