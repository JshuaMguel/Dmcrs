@extends('layouts.app')

@section('title', 'Manage Subjects - USTP DMCRS')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-4 sm:p-6 rounded-xl shadow-lg">
    <!-- Header Section -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col items-center gap-3 sm:gap-4 md:flex-row md:justify-between">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-ustpBlue rounded-full">
                    <span class="text-lg sm:text-2xl text-white">📚</span>
                </div>
                <div class="text-center md:text-left">
                    <h2 class="text-2xl sm:text-3xl font-bold text-ustpBlue">Manage Subjects</h2>
                    <p class="text-sm sm:text-base text-gray-600">Add, edit, and manage all subjects</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                <a href="{{ route('admin.subjects.create') }}"
                   class="bg-ustpBlue text-white hover:bg-ustpBlue/90 px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium flex items-center justify-center gap-2 shadow-sm w-full sm:w-auto">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Subject
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

    @if($subjects->count())
        <!-- Subjects Table -->
        <div class="bg-white rounded-lg shadow-md overflow-x-auto border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200" style="min-width: 800px;">
                <thead class="bg-ustpBlue">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-2">🔤</span>
                                Subject Code
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-2">📖</span>
                                Title
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-2">🏢</span>
                                Department
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-2">📝</span>
                                Description
                            </div>
                        </th>
                        <th class="px-8 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <span class="mr-2">⚙️</span>
                                Actions
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($subjects as $subject)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <!-- Subject Code -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 bg-ustpGold rounded-full flex items-center justify-center">
                                        <span class="text-xs font-bold text-ustpBlue">{{ substr($subject->subject_code, 0, 2) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-bold text-ustpBlue">{{ $subject->subject_code }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Title -->
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $subject->subject_title }}</div>
                            </td>

                            <!-- Department -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $subject->department->name ?? 'N/A' }}
                                </span>
                            </td>

                            <!-- Description -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $subject->description }}">
                                    {{ $subject->description ? Str::limit($subject->description, 50) : 'No description' }}
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.subjects.show', $subject) }}"
                                       class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors">
                                        <span class="mr-1">👁️‍🗨️</span>
                                        View
                                    </a>
                                    <a href="{{ route('admin.subjects.edit', $subject) }}"
                                       class="p-2 bg-yellow-100 text-yellow-800 rounded-lg hover:bg-yellow-200 transition-colors"
                                       title="Edit" aria-label="Edit subject">
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
        @if($subjects->hasPages())
            <div class="mt-6">
                {{ $subjects->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-12">
            <span class="text-6xl mb-4">📚</span>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Subjects Found</h3>
            <p class="text-gray-500 text-center max-w-md mb-6">
                Get started by creating your first subject. Subjects will help organize makeup class requests.
            </p>
            <a href="{{ route('admin.subjects.create') }}"
               class="bg-ustpBlue text-white px-6 py-3 rounded-lg hover:bg-ustpBlue/90 font-medium flex items-center gap-2">
                <span>➕</span>
                Create First Subject
            </a>
        </div>
    @endif
</div>
@endsection
