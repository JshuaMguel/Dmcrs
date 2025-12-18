@extends('layouts.app')

@section('title', 'Edit Faculty Loading - USTP DMCRS')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-4 sm:p-6 lg:p-8 rounded-xl shadow-lg border border-gray-100">
    <!-- Header Section -->
    <div class="text-center mb-6 sm:mb-8">
        <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-ustpBlue rounded-full mb-4">
            <span class="text-lg sm:text-2xl text-white">📋</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-ustpBlue mb-2">Edit Faculty Loading</h2>
        <p class="text-gray-600 text-sm sm:text-base">Update faculty class assignments</p>
    </div>

    <form action="{{ route('department.faculty-loading.update', $facultyLoading) }}" method="POST" id="facultyLoadingForm" class="space-y-4 sm:space-y-6">
        @csrf
        @method('PUT')

        <!-- Header Information -->
        <div class="bg-gray-50 p-4 sm:p-6 rounded-lg border border-gray-200">
            <h3 class="text-base sm:text-lg font-semibold text-ustpBlue mb-3 sm:mb-4 flex items-center">
                <span class="mr-2">📅</span>
                Semester & School Year
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="text-red-500">*</span> Semester
                    </label>
                    <select name="semester" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent transition-all duration-200">
                        <option value="">Select Semester</option>
                        <option value="1st" {{ old('semester', $facultyLoading->semester) == '1st' ? 'selected' : '' }}>1st Semester</option>
                        <option value="2nd" {{ old('semester', $facultyLoading->semester) == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                        <option value="summer" {{ old('semester', $facultyLoading->semester) == 'summer' ? 'selected' : '' }}>Summer</option>
                    </select>
                    @error('semester')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="text-red-500">*</span> School Year
                    </label>
                    <input type="text" name="school_year" value="{{ old('school_year', $facultyLoading->school_year) }}"
                           placeholder="e.g., 2024-2025" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent transition-all duration-200">
                    @error('school_year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="text-red-500">*</span> Status
                    </label>
                    <select name="status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent transition-all duration-200">
                        <option value="draft" {{ old('status', $facultyLoading->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ old('status', $facultyLoading->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="archived" {{ old('status', $facultyLoading->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Remarks
                    </label>
                    <input type="text" name="remarks" value="{{ old('remarks', $facultyLoading->remarks) }}"
                           placeholder="Optional remarks..."
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent transition-all duration-200">
                    @error('remarks')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Faculty Loading Details -->
        <div class="bg-gray-50 p-4 sm:p-6 rounded-lg border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base sm:text-lg font-semibold text-ustpBlue flex items-center">
                    <span class="mr-2">📚</span>
                    Class Assignments
                </h3>
                <button type="button" onclick="addDetailRow()" 
                        class="bg-ustpGold text-ustpBlue px-4 py-2 rounded-lg hover:bg-ustpGold/90 font-medium text-sm">
                    + Add Class
                </button>
            </div>

            <div id="detailsContainer" class="space-y-4">
                <!-- Existing details will be pre-populated here -->
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 sm:gap-0 pt-4 sm:pt-6 border-t border-gray-200">
            <a href="{{ route('department.faculty-loading.index') }}"
               class="inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400 text-sm sm:text-base">
                <span class="mr-2">←</span>
                Cancel
            </a>

            <button type="submit"
                    class="inline-flex items-center justify-center px-6 sm:px-8 py-3 bg-ustpBlue text-white rounded-lg hover:bg-blue-800 font-semibold transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-ustpGold text-sm sm:text-base">
                <span class="mr-2">✅</span>
                Update Faculty Loading
            </button>
        </div>
    </form>
</div>

<script>
    let detailIndex = 0;
    const instructors = @json($instructors->map(fn($u) => ['id' => $u->id, 'name' => $u->name]));
    const subjects = @json($subjects->map(fn($s) => ['code' => $s->subject_code, 'title' => $s->subject_title]));
    const existingDetails = @json($existingDetails);

    function addDetailRow(detail = null) {
        const container = document.getElementById('detailsContainer');
        const row = document.createElement('div');
        row.className = 'bg-white p-4 rounded-lg border border-gray-200';
        
        const detailId = detail ? detail.id : '';
        const instructorId = detail ? detail.instructor_id : '';
        const subjectCode = detail ? detail.subject_code : '';
        const section = detail ? detail.section : '';
        const dayOfWeek = detail ? detail.day_of_week : '';
        const timeStart = detail ? (detail.time_start && detail.time_start.length === 8 ? detail.time_start.substring(0, 5) : (detail.time_start || '')) : '';
        const timeEnd = detail ? (detail.time_end && detail.time_end.length === 8 ? detail.time_end.substring(0, 5) : (detail.time_end || '')) : '';
        const room = detail ? detail.room : '';
        const units = detail ? detail.units : '';

        // Build hidden input separately to avoid Blade parsing issues
        let hiddenInput = '';
        if (detailId) {
            hiddenInput = '<input type="hidden" name="details[' + detailIndex + '][id]" value="' + detailId + '">';
        }

        row.innerHTML = hiddenInput + `
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Instructor *</label>
                    <select name="details[${detailIndex}][instructor_id]" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue">
                        <option value="">Select Instructor</option>
                        ${instructors.map(i => `<option value="${i.id}" ${i.id == instructorId ? 'selected' : ''}>${i.name}</option>`).join('')}
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject Code *</label>
                    <input type="text" name="details[${detailIndex}][subject_code]" value="${subjectCode}" required
                           list="subjects-${detailIndex}" placeholder="e.g., IT101"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue">
                    <datalist id="subjects-${detailIndex}">
                        ${subjects.map(s => `<option value="${s.code}">${s.title}</option>`).join('')}
                    </datalist>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Section *</label>
                    <input type="text" name="details[${detailIndex}][section]" value="${section}" required placeholder="e.g., BSIT-1A"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Day of Week *</label>
                    <select name="details[${detailIndex}][day_of_week]" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue">
                        <option value="">Select Day</option>
                        <option value="Monday" ${dayOfWeek == 'Monday' ? 'selected' : ''}>Monday</option>
                        <option value="Tuesday" ${dayOfWeek == 'Tuesday' ? 'selected' : ''}>Tuesday</option>
                        <option value="Wednesday" ${dayOfWeek == 'Wednesday' ? 'selected' : ''}>Wednesday</option>
                        <option value="Thursday" ${dayOfWeek == 'Thursday' ? 'selected' : ''}>Thursday</option>
                        <option value="Friday" ${dayOfWeek == 'Friday' ? 'selected' : ''}>Friday</option>
                        <option value="Saturday" ${dayOfWeek == 'Saturday' ? 'selected' : ''}>Saturday</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time Start *</label>
                    <input type="time" name="details[${detailIndex}][time_start]" value="${timeStart}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time End *</label>
                    <input type="time" name="details[${detailIndex}][time_end]" value="${timeEnd}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Room *</label>
                    <input type="text" name="details[${detailIndex}][room]" value="${room}" required placeholder="e.g., Room 101"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Units</label>
                    <input type="number" name="details[${detailIndex}][units]" value="${units}" step="0.01" min="0" max="10"
                           placeholder="e.g., 3.0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue">
                </div>
            </div>
            <button type="button" onclick="this.closest('div').remove()" 
                    class="mt-2 text-red-600 hover:text-red-800 text-sm font-medium">
                Remove
            </button>
        `;
        container.appendChild(row);
        detailIndex++;
    }

    // Pre-populate existing details on page load
    document.addEventListener('DOMContentLoaded', function() {
        if (existingDetails.length > 0) {
            existingDetails.forEach(detail => {
                addDetailRow(detail);
            });
        } else {
            // If no existing details, add one empty row
            addDetailRow();
        }
    });
</script>
@endsection

