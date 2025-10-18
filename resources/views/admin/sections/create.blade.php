@extends('layouts.app')

@section('title', 'Add New Section - USTP DMCRS')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-lg">
    <!-- Header Section -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-ustpBlue rounded-full mb-4">
            <span class="text-2xl text-white">🏫</span>
        </div>
        <h2 class="text-3xl font-bold text-ustpBlue mb-2">Add New Section</h2>
        <p class="text-gray-600">Create a new class section for the system</p>
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

    <form action="{{ route('admin.sections.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Department Selection -->
        <div>
            <label for="department_id" class="block text-sm font-semibold text-gray-700 mb-2">
                <span class="text-red-500">*</span> Department
            </label>
            <select name="department_id" id="department_id"
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

        <!-- Year Level -->
        <div>
            <label for="year_level" class="block text-sm font-semibold text-gray-700 mb-2">
                <span class="text-red-500">*</span> Year Level
            </label>
            <select name="year_level" id="year_level"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent transition-all duration-200">
                <option value="">Select Year Level</option>
                @for($i = 1; $i <= 6; $i++)
                    <option value="{{ $i }}" {{ old('year_level') == $i ? 'selected' : '' }}>
                        {{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }} Year
                    </option>
                @endfor
            </select>
            @error('year_level')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Section Name -->
        <div>
            <label for="section_name" class="block text-sm font-semibold text-gray-700 mb-2">
                <span class="text-red-500">*</span> Section Name
            </label>
            <input type="text" name="section_name" id="section_name" value="{{ old('section_name') }}"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent transition-all duration-200"
                   placeholder="e.g., A, B, C" maxlength="10">
            @error('section_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-sm text-gray-500 mt-1">💡 Use uppercase letters only (A, B, C, etc.)</p>
        </div>

        <!-- Preview Section -->
        <div id="preview" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="font-semibold text-blue-800 mb-2">Preview:</h4>
            <p id="preview-text" class="text-blue-700"></p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.sections.index') }}"
               class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400">
                <span class="mr-2">←</span>
                Back to Sections
            </a>

            <button type="submit"
                    class="inline-flex items-center justify-center px-8 py-3 bg-ustpBlue text-white rounded-lg hover:bg-blue-800 font-semibold transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-ustpGold">
                <span class="mr-2">✅</span>
                Create Section
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const departmentSelect = document.getElementById('department_id');
        const yearLevelSelect = document.getElementById('year_level');
        const sectionNameInput = document.getElementById('section_name');
        const preview = document.getElementById('preview');
        const previewText = document.getElementById('preview-text');

        function updatePreview() {
            const departmentText = departmentSelect.options[departmentSelect.selectedIndex]?.text;
            const yearLevel = yearLevelSelect.value;
            const sectionName = sectionNameInput.value.toUpperCase();

            if (departmentText && yearLevel && sectionName && departmentText !== 'Select Department') {
                const departmentCode = departmentText.split(' ')[0]; // Get first word (e.g., BSIT from "BSIT - Information Technology")
                const fullName = `${departmentCode}-${yearLevel}${sectionName}`;
                previewText.textContent = `Full Section Name: ${fullName}`;
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        }

        // Auto-uppercase section name
        sectionNameInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            updatePreview();
        });

        departmentSelect.addEventListener('change', updatePreview);
        yearLevelSelect.addEventListener('change', updatePreview);

        // Initial update
        updatePreview();
    });
</script>
@endsection
