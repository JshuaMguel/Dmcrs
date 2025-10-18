@extends('layouts.app')

@section('title', 'Subject Details - USTP DMCRS')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-4 sm:p-6 lg:p-8 rounded-xl shadow-lg border border-gray-100">
    <!-- Header Section -->
    <div class="text-center mb-6 sm:mb-8">
        <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-ustpBlue rounded-full mb-4">
            <span class="text-lg sm:text-2xl text-white">📚</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-ustpBlue mb-2">Subject Details</h2>
        <p class="text-gray-600 text-sm sm:text-base">View subject information</p>
    </div>

    <!-- Subject Information Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Subject Code Card -->
        <div class="bg-gray-50 rounded-lg p-4 sm:p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                <span class="text-2xl mr-3">🔤</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Subject Code</h3>
            </div>
            <p class="text-lg sm:text-xl font-bold text-ustpBlue">{{ $subject->subject_code }}</p>
        </div>

        <!-- Subject Title Card -->
        <div class="bg-gray-50 rounded-lg p-4 sm:p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                <span class="text-2xl mr-3">📖</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Subject Title</h3>
            </div>
            <p class="text-lg sm:text-xl font-bold text-ustpBlue">{{ $subject->subject_title }}</p>
        </div>

        <!-- Department Card -->
        <div class="bg-gray-50 rounded-lg p-4 sm:p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                <span class="text-2xl mr-3">🏢</span>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Department</h3>
            </div>
            <p class="text-lg sm:text-xl font-bold text-ustpBlue">{{ $subject->department->name ?? 'N/A' }}</p>
        </div>
    </div>

    <!-- Description Section -->
    @if($subject->description)
        <div class="bg-gray-50 rounded-lg p-4 sm:p-6 border border-gray-200 mb-6 sm:mb-8">
            <div class="flex items-center mb-3">
                <span class="text-2xl mr-3">📝</span>
                <h3 class="text-lg font-semibold text-ustpBlue">Description</h3>
            </div>
            <p class="text-gray-700 leading-relaxed">{{ $subject->description }}</p>
        </div>
    @endif

    <!-- Timestamps -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <div class="flex items-center mb-2">
                <span class="text-lg mr-2">📅</span>
                <h4 class="text-sm font-semibold text-gray-700">Created</h4>
            </div>
            <p class="text-gray-600 text-sm">{{ $subject->created_at->format('M d, Y g:i A') }}</p>
        </div>

        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <div class="flex items-center mb-2">
                <span class="text-lg mr-2">🔄</span>
                <h4 class="text-sm font-semibold text-gray-700">Last Updated</h4>
            </div>
            <p class="text-gray-600 text-sm">{{ $subject->updated_at->format('M d, Y g:i A') }}</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 sm:gap-4 pt-4 sm:pt-6 border-t border-gray-200">
        <a href="{{ route('admin.subjects.index') }}"
           class="inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400 text-sm sm:text-base">
            <span class="mr-2">←</span>
            Back to Subjects
        </a>

        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
            <a href="{{ route('admin.subjects.edit', $subject) }}"
               class="p-2 bg-yellow-100 text-yellow-800 rounded-lg hover:bg-yellow-200 transition"
               title="Edit" aria-label="Edit subject">
                ✏️
            </a>
        </div>
    </div>
</div>
@endsection
