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
                            <option value="{{ $department->id }}" 
                                {{ old('department_id', $userDepartment) == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                                @if($department->id == $userDepartment)
                                    <span class="text-blue-600">(Your Department)</span>
                                @endif
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
                    
                    <!-- Students List Display -->
                    <div id="students_list_container" class="mt-4 hidden">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                                <span class="mr-2">👥</span>
                                Students in Selected Section
                            </h4>
                            <div id="students_loading" class="text-blue-600 text-sm">Loading students...</div>
                            <div id="students_list" class="hidden">
                                <p class="text-sm text-blue-700 mb-2">
                                    <span id="students_count">0</span> active student(s) will receive confirmation emails:
                                </p>
                                <div id="students_display" class="max-h-40 overflow-y-auto bg-white rounded border border-blue-200 p-3">
                                    <!-- Students will be loaded here via AJAX -->
                                </div>
                            </div>
                            <div id="students_empty" class="hidden">
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                    <p class="text-yellow-800 font-semibold mb-1">⚠️ No Active Students Found</p>
                                    <p class="text-yellow-700 text-sm">
                                        No active students are currently assigned to this section in the database. 
                                        Please ensure:
                                    </p>
                                    <ul class="text-yellow-700 text-sm list-disc list-inside mt-2 space-y-1">
                                        <li>Students are imported/created in the system</li>
                                        <li>Students have this section assigned (section_id)</li>
                                        <li>Students have status = "active"</li>
                                    </ul>
                                    <p class="text-yellow-700 text-sm mt-2">
                                        <strong>Note:</strong> Confirmation emails will still be sent when you submit the request if students exist in the database.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
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

            // Reset section dropdown and hide students container
            sectionSelect.value = '';
            sectionHidden.value = '';
            const studentsContainer = document.getElementById('students_list_container');
            if (studentsContainer) {
                studentsContainer.classList.add('hidden');
            }

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
            // Also load students when section changes
            loadStudentsBySection();
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
        
        // Load students when section is selected (on page load if section is already selected)
        if (sectionSelect.value) {
            loadStudentsBySection();
        }
    });
    
    // Function to load students by section via AJAX (defined outside DOMContentLoaded for global access)
    function loadStudentsBySection() {
        const sectionId = document.getElementById('section_id').value;
        const container = document.getElementById('students_list_container');
        const loading = document.getElementById('students_loading');
        const studentsList = document.getElementById('students_list');
        const studentsDisplay = document.getElementById('students_display');
        const studentsCount = document.getElementById('students_count');
        const studentsEmpty = document.getElementById('students_empty');
        
        if (!sectionId) {
            container.classList.add('hidden');
            return;
        }
        
        // Show container and loading state
        container.classList.remove('hidden');
        loading.classList.remove('hidden');
        studentsList.classList.add('hidden');
        studentsEmpty.classList.add('hidden');
        studentsDisplay.innerHTML = '';
        
        // Fetch students from API
        fetch(`{{ route('faculty.makeup-requests.students-by-section') }}?section_id=${sectionId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.error || `HTTP error! status: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            loading.classList.add('hidden');
            
            console.log('Students loaded:', data); // Debug log
            
            if (data && Array.isArray(data) && data.length > 0) {
                studentsCount.textContent = data.length;
                studentsList.classList.remove('hidden');
                
                // Display students
                const studentsHtml = data.map(student => 
                    `<div class="text-sm py-1 border-b border-gray-100 last:border-0">
                        <span class="font-medium">${student.name}</span>
                        <span class="text-gray-600">(${student.student_id_number})</span>
                        <span class="text-gray-500 text-xs"> - ${student.email}</span>
                    </div>`
                ).join('');
                
                studentsDisplay.innerHTML = studentsHtml;
            } else {
                studentsEmpty.classList.remove('hidden');
                if (data && data.error) {
                    studentsEmpty.innerHTML = `⚠️ ${data.error}`;
                }
            }
        })
        .catch(error => {
            console.error('Error loading students:', error);
            loading.classList.add('hidden');
            studentsEmpty.classList.remove('hidden');
            studentsEmpty.innerHTML = `⚠️ Error loading students: ${error.message}. Please check browser console for details.`;
        });
    }
    
    // Make function globally accessible
    window.loadStudentsBySection = loadStudentsBySection;
</script>
@endsection
