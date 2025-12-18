@extends('layouts.app')

@section('title', 'Faculty Loading - USTP DMCRS')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-4 sm:p-6 rounded-xl shadow-lg">
    <!-- Header Section -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col items-center gap-3 sm:gap-4 md:flex-row md:justify-between">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-ustpBlue rounded-full">
                    <span class="text-lg sm:text-2xl text-white">📋</span>
                </div>
                <div class="text-center md:text-left">
                    <h2 class="text-2xl sm:text-3xl font-bold text-ustpBlue">Faculty Loading</h2>
                    <p class="text-sm sm:text-base text-gray-600">Manage faculty class assignments</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                <a href="{{ route('department.faculty-loading.create') }}"
                   class="bg-ustpBlue text-white hover:bg-ustpBlue/90 px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium flex items-center justify-center gap-2 shadow-sm w-full sm:w-auto">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Faculty Loading
                </a>
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

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <span class="text-red-500 text-xl mr-3">❌</span>
                <div>
                    <h4 class="font-semibold text-red-800">Error!</h4>
                    <p class="text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
        <form method="GET" action="{{ route('department.faculty-loading.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                <select name="semester" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent">
                    <option value="">All Semesters</option>
                    <option value="1st" {{ request('semester') == '1st' ? 'selected' : '' }}>1st Semester</option>
                    <option value="2nd" {{ request('semester') == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                    <option value="summer" {{ request('semester') == 'summer' ? 'selected' : '' }}>Summer</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">School Year</label>
                <input type="text" name="school_year" value="{{ request('school_year') }}"
                       placeholder="e.g., 2024-2025"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent">
            </div>
            <div class="md:col-span-3 flex gap-2">
                <button type="submit" class="bg-ustpBlue text-white px-4 py-2 rounded-lg hover:bg-ustpBlue/90 font-medium">
                    Filter
                </button>
                <a href="{{ route('department.faculty-loading.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 font-medium">
                    Clear
                </a>
            </div>
        </form>
    </div>

    @if($headers->count())
        <!-- Faculty Loading Headers Table -->
        <div class="bg-white rounded-lg shadow-md overflow-x-auto border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-ustpBlue">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">FLH Code</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Semester</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">School Year</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Uploaded By</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Details Count</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Created At</th>
                        <th class="px-8 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($headers as $header)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-ustpBlue">{{ $header->flh_code }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ ucfirst($header->semester) }} Semester</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $header->school_year }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-800',
                                        'active' => 'bg-green-100 text-green-800',
                                        'archived' => 'bg-red-100 text-red-800',
                                    ];
                                    $color = $statusColors[$header->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                    {{ ucfirst($header->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $header->uploadedBy->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $header->details->count() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $header->created_at->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('department.faculty-loading.show', $header) }}"
                                       class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors">
                                        <span class="mr-1">👁️</span>
                                        View
                                    </a>
                                    <a href="{{ route('department.faculty-loading.edit', $header) }}"
                                       class="p-2 bg-yellow-100 text-yellow-800 rounded-lg hover:bg-yellow-200 transition-colors"
                                       title="Edit">
                                        ✏️
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($headers->hasPages())
            <div class="mt-6">
                {{ $headers->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-12">
            <span class="text-6xl mb-4">📋</span>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Faculty Loading Found</h3>
            <p class="text-gray-500 text-center max-w-md mb-6">
                Get started by creating your first faculty loading record.
            </p>
            <a href="{{ route('department.faculty-loading.create') }}"
               class="bg-ustpBlue text-white px-6 py-3 rounded-lg hover:bg-ustpBlue/90 font-medium flex items-center gap-2">
                <span>➕</span>
                Create First Faculty Loading
            </a>
        </div>
    @endif
</div>
@endsection


