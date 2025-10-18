@extends('layouts.app')

@section('title', 'Manage Sections - USTP DMCRS')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-4 sm:p-6 rounded-xl shadow-lg">
    <!-- Header Section -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col items-center gap-3 sm:gap-4 md:flex-row md:justify-between">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-ustpBlue rounded-full">
                    <span class="text-lg sm:text-2xl text-white">🏫</span>
                </div>
                <div class="text-center md:text-left">
                    <h2 class="text-2xl sm:text-3xl font-bold text-ustpBlue">Manage Sections</h2>
                    <p class="text-sm sm:text-base text-gray-600">Add, edit, and manage all class sections</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                <a href="{{ route('admin.sections.create') }}"
                   class="bg-ustpBlue text-white hover:bg-ustpBlue/90 px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium flex items-center justify-center gap-2 shadow-sm w-full sm:w-auto">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Section
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
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

    @if($sections->count())
        <!-- Sections Table -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-ustpBlue">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">
                                Department
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">
                                Year Level
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">
                                Section Name
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">
                                Full Name
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">
                                Created
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($sections as $section)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-ustpBlue rounded-full mr-3"></div>
                                        <div class="text-sm font-medium text-gray-900">{{ $section->department->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-ustpBlue/10 text-ustpBlue">
                                        Year {{ $section->year_level }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $section->section_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-ustpGold text-ustpBlue shadow-sm">
                                        {{ $section->full_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4h12l-1 8H7L6 11z"/>
                                        </svg>
                                        {{ $section->created_at->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-3">
                                        <a href="{{ route('admin.sections.edit', $section->id) }}"
                                           class="p-2 bg-yellow-100 text-yellow-800 rounded-lg hover:bg-yellow-200 transition-colors"
                                           title="Edit" aria-label="Edit section">
                                            ✏️
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-4">
                <span class="text-2xl">🏫</span>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No sections found</h3>
            <p class="text-gray-500 mb-6">
                Get started by creating your first section. Sections help organize makeup class requests.
            </p>
            <a href="{{ route('admin.sections.create') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-ustpBlue hover:bg-ustpBlue/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ustpBlue">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add First Section
            </a>
        </div>
    @endif
</div>
@endsection
