
@extends('layouts.app')

@section('title', $user->name . ' - USTP DMCRS')

@section('content')
    <div class="py-8 bg-gradient-to-br from-ustpBlue/5 via-white to-ustpGold/5 min-h-screen">
        <div class="max-w-3xl mx-auto px-4">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-ustpBlue rounded-full mb-4">
                        <span class="text-2xl text-white">👤</span>
                    </div>
                    <h1 class="text-3xl font-bold text-ustpBlue">{{ $user->name }}</h1>
                    <p class="text-gray-600">Update user profile and account settings</p>
                </div>
            </div>

            <!-- Edit Form Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-ustpBlue to-ustpBlue/90 p-6">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <div>
                            <h2 class="text-xl font-bold text-white">User Information</h2>
                            <p class="text-ustpGold/90 text-sm">Modify user account details and permissions</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl mb-6">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="font-semibold">Please correct the following errors:</h3>
                            </div>
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-xl mb-6">
                            <div class="flex items-center gap-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.updateUser', $user->id) }}" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- Personal Information Section -->
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="bg-blue-100 rounded-full p-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
                                    <p class="text-sm text-gray-600">Basic user profile information</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                                    <input id="name"
                                           name="name"
                                           type="text"
                                           value="{{ old('name', $user->name) }}"
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="Enter full name">
                                    <p class="text-sm text-gray-500 mt-1">User's complete name as it appears in records</p>
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                                    <input id="email"
                                           name="email"
                                           type="email"
                                           value="{{ old('email', $user->email) }}"
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="Enter email address">
                                    <p class="text-sm text-gray-500 mt-1">Primary email for system communications</p>
                                </div>
                            </div>
                        </div>

                        <!-- Role & Permissions Section -->
                        <div class="bg-yellow-50 rounded-xl p-6 border border-yellow-200">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="bg-yellow-100 rounded-full p-2">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Role & Permissions</h3>
                                    <p class="text-sm text-gray-600">Define user role and department assignment</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">User Role</label>
                                    <select id="role"
                                            name="role"
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="faculty" @if(old('role', $user->role) == 'faculty') selected @endif>Faculty Member</option>
                                        <option value="department_chair" @if(old('role', $user->role) == 'department_chair') selected @endif>Department Chair</option>
                                        <option value="academic_head" @if(old('role', $user->role) == 'academic_head') selected @endif>Academic Head</option>
                                    </select>
                                    <p class="text-sm text-gray-500 mt-1">Determines user permissions and access level</p>
                                </div>

                                <div id="department-select" style="display: {{ old('role', $user->role) == 'academic_head' ? 'none' : 'block' }};">
                                    <label for="department_id" class="block text-sm font-semibold text-gray-700 mb-2">Department Assignment</label>
                                    <select id="department_id"
                                            name="department_id"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">No Department Assigned</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" @if(old('department_id', $user->department_id) == $department->id) selected @endif>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-sm text-gray-500 mt-1">Department affiliation for faculty and chairs</p>
                                </div>
                            </div>

                            <!-- Role Information Cards -->
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-white rounded-lg p-4 border border-yellow-200">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                        <h4 class="font-semibold text-sm text-gray-900">Faculty</h4>
                                    </div>
                                    <p class="text-xs text-gray-600">Can create and manage makeup class requests</p>
                                </div>
                                <div class="bg-white rounded-lg p-4 border border-yellow-200">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                        <h4 class="font-semibold text-sm text-gray-900">Department Chair</h4>
                                    </div>
                                    <p class="text-xs text-gray-600">Can approve department requests and manage faculty</p>
                                </div>
                                <div class="bg-white rounded-lg p-4 border border-yellow-200">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                                        <h4 class="font-semibold text-sm text-gray-900">Academic Head</h4>
                                    </div>
                                    <p class="text-xs text-gray-600">System-wide oversight and final approvals</p>
                                </div>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="bg-blue-100 rounded-full p-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-blue-900">Account Status</h3>
                                    <p class="text-sm text-blue-600">Manage user account status and information</p>
                                </div>
                            </div>

                            <!-- Account Status Toggle -->
                            <div class="mb-6">
                                <label for="is_active" class="block text-sm font-semibold text-gray-700 mb-3">Account Status</label>
                                <div class="flex items-center gap-4">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio"
                                               name="is_active"
                                               value="1"
                                               @if(old('is_active', $user->is_active ?? 1) == 1) checked @endif
                                               class="sr-only peer">
                                        <div class="relative w-5 h-5 bg-white border-2 border-green-300 rounded-full peer-checked:bg-green-500 peer-checked:border-green-500 transition-colors">
                                            <div class="absolute inset-0 hidden peer-checked:block">
                                                <svg class="w-3 h-3 text-white m-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <span class="ml-3 text-sm font-medium text-green-700">Active</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio"
                                               name="is_active"
                                               value="0"
                                               @if(old('is_active', $user->is_active ?? 1) == 0) checked @endif
                                               class="sr-only peer">
                                        <div class="relative w-5 h-5 bg-white border-2 border-red-300 rounded-full peer-checked:bg-red-500 peer-checked:border-red-500 transition-colors">
                                            <div class="absolute inset-0 hidden peer-checked:block">
                                                <svg class="w-3 h-3 text-white m-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <span class="ml-3 text-sm font-medium text-red-700">Inactive</span>
                                    </label>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Inactive users cannot login to the system</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="bg-white rounded-lg p-4 border border-blue-200">
                                    <div class="text-sm text-gray-500 font-medium">Member Since</div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</div>
                                </div>
                                <div class="bg-white rounded-lg p-4 border border-blue-200">
                                    <div class="text-sm text-gray-500 font-medium">Last Updated</div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $user->updated_at ? $user->updated_at->format('M d, Y') : 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                            <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-200 transform hover:scale-105">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                    </svg>
                                    Update User
                                </span>
                            </button>

                            <a href="{{ route('admin.users') }}" class="flex-1 sm:flex-none bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold border border-gray-300 transition-colors text-center">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Back to Users
                                </span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const departmentSelect = document.getElementById('department-select');

            roleSelect.addEventListener('change', function() {
                if (this.value === 'academic_head') {
                    departmentSelect.style.display = 'none';
                    document.getElementById('department_id').value = '';
                } else {
                    departmentSelect.style.display = 'block';
                }
            });
        });
    </script>
@endsection
