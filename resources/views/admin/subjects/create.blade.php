@extends('layouts.app')

@section('title', 'Add Subject - USTP DMCRS')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-4 sm:p-6 lg:p-8 rounded-xl shadow-lg border border-gray-100">
    <!-- Header Section -->
    <div class="text-center mb-6 sm:mb-8">
        <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-ustpBlue rounded-full mb-4">
            <span class="text-lg sm:text-2xl text-white">📚</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-ustpBlue mb-2">Add New Subject</h2>
        <p class="text-gray-600 text-sm sm:text-base">Create a new subject for the system</p>
    </div>

    <form action="{{ route('admin.subjects.store') }}" method="POST" class="space-y-4 sm:space-y-6">
        @csrf

        <!-- Department Selection -->
        <div class="bg-gray-50 p-4 sm:p-6 rounded-lg border border-gray-200">
            <h3 class="text-base sm:text-lg font-semibold text-ustpBlue mb-3 sm:mb-4 flex items-center">
                <span class="mr-2">🏢</span>
                Department Information
            </h3>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <span class="text-red-500">*</span> Department
                </label>
                <select name="department_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent transition-all duration-200">
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
                @error('department_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Subject Information -->
        <div class="bg-gray-50 p-4 sm:p-6 rounded-lg border border-gray-200">
            <h3 class="text-base sm:text-lg font-semibold text-ustpBlue mb-3 sm:mb-4 flex items-center">
                <span class="mr-2">📖</span>
                Subject Details
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="text-red-500">*</span> Subject Code
                    </label>
                    <input type="text" name="subject_code" value="{{ old('subject_code') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent transition-all duration-200"
                           placeholder="e.g., PURCOM, COMPROG"
                           style="text-transform: uppercase;">
                    @error('subject_code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="text-red-500">*</span> Subject Title
                    </label>
                    <input type="text" name="subject_title" value="{{ old('subject_title') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent transition-all duration-200"
                           placeholder="e.g., Purposive Communication">
                    @error('subject_title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4 sm:mt-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Description (Optional)
                </label>
                <textarea name="description" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent transition-all duration-200"
                          placeholder="Brief description of the subject...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 sm:gap-0 pt-4 sm:pt-6 border-t border-gray-200">
            <a href="{{ route('admin.subjects.index') }}"
               class="inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400 text-sm sm:text-base">
                <span class="mr-2">←</span>
                Cancel
            </a>

            <button type="submit"
                    class="inline-flex items-center justify-center px-6 sm:px-8 py-3 bg-ustpBlue text-white rounded-lg hover:bg-blue-800 font-semibold transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-ustpGold text-sm sm:text-base">
                <span class="mr-2">✅</span>
                Create Subject
            </button>
        </div>
    </form>
</div>

<script>
    // Auto-uppercase subject code
    document.querySelector('input[name="subject_code"]').addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });
</script>
@endsection
