@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-4 sm:p-6 lg:p-8 rounded-xl shadow-lg border border-gray-100">
    <!-- Header Section -->
    <div class="text-center mb-6 sm:mb-8">
        <div class="inline-flex items-center justify-center w-12 sm:w-16 h-12 sm:h-16 bg-ustpBlue rounded-full mb-4">
            <span class="text-xl sm:text-2xl text-white">📝</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-ustpBlue mb-2">Submit Makeup Request</h2>
        <p class="text-gray-600 text-sm sm:text-base">Create a new makeup class request</p>
    </div>

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

    <form action="{{ route('makeup-requests.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
        @csrf

        <!-- Schedule Information Section (moved up) -->
        <div class="bg-gray-50 p-4 sm:p-6 rounded-lg border border-gray-200">
            <h3 class="text-base sm:text-lg font-semibold text-ustpBlue mb-3 sm:mb-4 flex items-center">
                <span class="mr-2">📅</span>
                Schedule Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="text-red-500">*</span> Preferred Date
                    </label>
                    <input type="date" name="preferred_date" value="{{ old('preferred_date') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent transition-all duration-200">
                    @error('preferred_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="text-red-500">*</span> Start Time
                    </label>
                    <input type="time" name="preferred_time" value="{{ old('preferred_time') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent transition-all duration-200">
                    @error('preferred_time') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="text-red-500">*</span> End Time
                    </label>
                    <input type="time" name="end_time" value="{{ old('end_time') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent transition-all duration-200">
                    @error('end_time') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Subject Information Section -->
        <div class="bg-gray-50 p-4 sm:p-6 rounded-lg border border-gray-200">
            <h3 class="text-base sm:text-lg font-semibold text-ustpBlue mb-3 sm:mb-4 flex items-center">
                <span class="mr-2">📚</span>
                Subject Information
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
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
                    @error('department_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="text-red-500">*</span> Subject
                    </label>
                    <select name="subject_id" id="subject_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent transition-all duration-200">
                        <option value="">Select Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}"
                                    data-code="{{ $subject->subject_code }}"
                                    data-title="{{ $subject->subject_title }}"
                                    data-department="{{ $subject->department_id }}"
                                    data-description="{{ $subject->description }}"
                                    {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->subject_code }} - {{ $subject->subject_title }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Hidden fields for backward compatibility -->
                <input type="hidden" name="subject" id="subject_code_hidden" value="{{ old('subject') }}">
                <input type="hidden" name="subject_title" id="subject_title_hidden" value="{{ old('subject_title') }}">

                <!-- Subject Description Display -->
                <div class="col-span-1 sm:col-span-2">
                    <div id="subject_description" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-800 mb-2">Subject Description:</h4>
                        <p id="description_text" class="text-blue-700 text-sm"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="text-red-500">*</span> Section
                    </label>
                    <select name="section_id" id="section_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent transition-all duration-200">
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}"
                                    data-department="{{ $section->department_id }}"
                                    data-full-name="{{ $section->full_name }}"
                                    {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->abbreviated_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('section_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror

                    <!-- Hidden field for backward compatibility -->
                    <input type="hidden" name="section" id="section_hidden" value="{{ old('section') }}">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="text-red-500">*</span> Room
                    </label>
                    <select name="room" id="room_select"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent transition-all duration-200">
                        <option value="">Select a room</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->name }}" {{ old('room') == $room->name ? 'selected' : '' }}>
                                {{ $room->name }}
                            </option>
                        @endforeach
                    </select>
                    <p id="room_help" class="text-xs text-gray-500 mt-1">Tip: Pick date and time to filter rooms to available only.</p>
                    @error('room') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Request Details Section -->
        <div class="bg-gray-50 p-4 sm:p-6 rounded-lg border border-gray-200">
            <h3 class="text-base sm:text-lg font-semibold text-ustpBlue mb-3 sm:mb-4 flex items-center">
                <span class="mr-2">💭</span>
                Request Details
            </h3>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <span class="text-red-500">*</span> Reason for Makeup Class
                </label>
                <textarea name="reason" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent transition-all duration-200"
                          placeholder="Please provide a detailed reason for the makeup class request...">{{ old('reason') }}</textarea>
                @error('reason') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>


        <!-- File Attachments Section -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h3 class="text-lg font-semibold text-ustpBlue mb-4 flex items-center">
                <span class="mr-2">📎</span>
                File Attachments
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Supporting Document (Optional)
                    </label>
                    <input type="file" name="attachment"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent transition-all duration-200">
                    <p class="text-sm text-gray-500 mt-2">💡 Upload any supporting documents (PDF, DOC, JPG, PNG)</p>
                    @error('attachment') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="text-red-500">*</span> Student List
                    </label>
                    <input type="file" name="student_list" accept=".csv,.xlsx" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ustpBlue focus:border-transparent transition-all duration-200">
                    <p class="text-sm text-gray-500 mt-2">💡 Upload student list in CSV or Excel format</p>
                    @error('student_list') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 sm:gap-0 pt-4 sm:pt-6 border-t border-gray-200">
            <a href="{{ route('makeup-requests.index') }}"
               class="inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400 text-sm sm:text-base">
                <span class="mr-2">←</span>
                Cancel
            </a>

            <button type="submit"
                    class="inline-flex items-center justify-center px-6 sm:px-8 py-3 bg-ustpBlue text-white rounded-lg hover:bg-blue-800 font-semibold transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-ustpGold text-sm sm:text-base">
                <span class="mr-2">✅</span>
                Submit Request
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roomSelect = document.getElementById('room_select');
        const preferredDate = document.querySelector('input[name="preferred_date"]');
        const startTime = document.querySelector('input[name="preferred_time"]');
        const endTime = document.querySelector('input[name="end_time"]');
        const roomHelp = document.getElementById('room_help');

        async function refreshAvailableRooms() {
            const date = preferredDate.value;
            const start = startTime.value;
            const end = endTime.value;
            if (!date || !start || !end) { return; }
            try {
                const params = new URLSearchParams({ preferred_date: date, preferred_time: start, end_time: end });
                const res = await fetch(`{{ route('makeup-requests.available-rooms') }}?${params.toString()}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) throw new Error('Failed to load available rooms');
                const data = await res.json();
                const current = roomSelect.value;
                roomSelect.innerHTML = '<option value="">Select a room</option>';
                data.available.forEach(r => {
                    const opt = document.createElement('option');
                    opt.value = r.name; opt.textContent = r.name;
                    roomSelect.appendChild(opt);
                });
                // Keep selection if still available
                if (current && data.available.some(r => r.name === current)) {
                    roomSelect.value = current;
                }
                roomHelp.textContent = `${data.available.length} available · ${data.busy.length} busy at selected time`;
            } catch (e) {
                roomHelp.textContent = 'Could not load available rooms.';
            }
        }

        preferredDate.addEventListener('change', refreshAvailableRooms);
        startTime.addEventListener('change', refreshAvailableRooms);
        endTime.addEventListener('change', refreshAvailableRooms);
        // If fields are already prefilled (e.g., after validation error), refresh on load
        if (preferredDate.value && startTime.value && endTime.value) {
            refreshAvailableRooms();
        }
        const departmentSelect = document.getElementById('department_id');
        const subjectSelect = document.getElementById('subject_id');
        const sectionSelect = document.getElementById('section_id');
        const subjectCodeHidden = document.getElementById('subject_code_hidden');
        const subjectTitleHidden = document.getElementById('subject_title_hidden');
        const sectionHidden = document.getElementById('section_hidden');

        // Filter subjects and sections when department changes
        departmentSelect.addEventListener('change', function() {
            const selectedDepartment = this.value;
            const subjectOptions = subjectSelect.querySelectorAll('option');
            const sectionOptions = sectionSelect.querySelectorAll('option');

            // Reset subject dropdown
            subjectSelect.value = '';
            subjectCodeHidden.value = '';
            subjectTitleHidden.value = '';
            hideDescription();

            // Reset section dropdown
            sectionSelect.value = '';
            sectionHidden.value = '';

            // Show/hide subjects based on department
            subjectOptions.forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block'; // Always show "Select Subject"
                } else {
                    const subjectDepartment = option.getAttribute('data-department');
                    option.style.display = subjectDepartment === selectedDepartment ? 'block' : 'none';
                }
            });

            // Show/hide sections based on department
            sectionOptions.forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block'; // Always show "Select Section"
                } else {
                    const sectionDepartment = option.getAttribute('data-department');
                    option.style.display = sectionDepartment === selectedDepartment ? 'block' : 'none';
                }
            });
        });

        // Update hidden fields and show description when subject changes
        subjectSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];

            if (selectedOption.value) {
                subjectCodeHidden.value = selectedOption.getAttribute('data-code');
                subjectTitleHidden.value = selectedOption.getAttribute('data-title');

                const description = selectedOption.getAttribute('data-description');
                if (description && description.trim() !== '') {
                    showDescription(description);
                } else {
                    hideDescription();
                }
            } else {
                subjectCodeHidden.value = '';
                subjectTitleHidden.value = '';
                hideDescription();
            }
        });

        // Update hidden field when section changes
        sectionSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                sectionHidden.value = selectedOption.getAttribute('data-full-name');
            } else {
                sectionHidden.value = '';
            }
        });

        function showDescription(description) {
            const descriptionText = document.getElementById('description_text');
            const subjectDescription = document.getElementById('subject_description');
            descriptionText.textContent = description;
            subjectDescription.classList.remove('hidden');
        }

        function hideDescription() {
            const subjectDescription = document.getElementById('subject_description');
            subjectDescription.classList.add('hidden');
        }

        // Initialize on page load if values are already selected
        if (departmentSelect.value) {
            departmentSelect.dispatchEvent(new Event('change'));
        }
        if (subjectSelect.value) {
            subjectSelect.dispatchEvent(new Event('change'));
        }
        if (sectionSelect.value) {
            sectionSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection
