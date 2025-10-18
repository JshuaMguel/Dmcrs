@extends('layouts.app')

@section('title', 'Edit Department - USTP DMCRS')

@section('content')
    <div class="py-8 bg-gradient-to-br from-ustpBlue/5 via-white to-ustpGold/5 min-h-screen">
        <div class="max-w-2xl mx-auto px-4">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-ustpBlue rounded-full mb-4">
                        <span class="text-2xl text-white">✏️</span>
                    </div>
                    <h1 class="text-3xl font-bold text-ustpBlue">Edit Department</h1>
                    <p class="text-gray-600">Update department information and settings</p>
                </div>
            </div>

            <!-- Edit Form Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-ustpBlue to-ustpBlue/90 p-6">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <div>
                            <h2 class="text-xl font-bold text-white">Department Information</h2>
                            <p class="text-ustpGold/90 text-sm">Modify department details below</p>
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

                    <form method="POST" action="{{ route('admin.updateDepartment', $department->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Department Details Section -->
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="bg-ustpBlue/10 rounded-full p-2">
                                    <svg class="w-5 h-5 text-ustpBlue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Department Details</h3>
                                    <p class="text-sm text-gray-600">Update the basic information for this department</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Department Name</label>
                                    <input id="name"
                                           name="name"
                                           type="text"
                                           value="{{ old('name', $department->name) }}"
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="Enter department name">
                                    <p class="text-sm text-gray-500 mt-1">The official name of the academic department</p>
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description (Optional)</label>
                                    <textarea id="description"
                                              name="description"
                                              rows="3"
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                              placeholder="Brief description of the department">{{ old('description', $department->description ?? '') }}</textarea>
                                    <p class="text-sm text-gray-500 mt-1">Optional description of the department's function and scope</p>
                                </div>
                            </div>
                        </div>

                        <!-- Department Statistics -->
                        <div class="bg-ustpGold/5 rounded-xl p-6 border border-ustpGold/20">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="bg-ustpGold/20 rounded-full p-2">
                                    <svg class="w-5 h-5 text-ustpBlue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-ustpBlue">Department Statistics</h3>
                                    <p class="text-sm text-gray-600">Current department metrics and information</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="bg-white rounded-lg p-4 border border-ustpGold/30">
                                    <div class="text-sm text-gray-500 font-medium">Faculty Members</div>
                                    <div class="text-2xl font-bold text-ustpBlue">{{ \App\Models\User::where('department_id', $department->id)->where('role', 'faculty')->count() }}</div>
                                </div>
                                <div class="bg-white rounded-lg p-4 border border-ustpGold/30">
                                    <div class="text-sm text-gray-500 font-medium">Department Chair</div>
                                    <div class="text-2xl font-bold text-ustpGold">{{ \App\Models\User::where('department_id', $department->id)->where('role', 'department_chair')->count() }}</div>
                                </div>
                                <div class="bg-white rounded-lg p-4 border border-ustpGold/30">
                                    <div class="text-sm text-gray-500 font-medium">Created</div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $department->created_at ? $department->created_at->format('M d, Y') : 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                            <button type="submit" class="flex-1 bg-gradient-to-r from-ustpBlue to-ustpBlue/90 hover:from-ustpBlue/90 hover:to-ustpBlue text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                    </svg>
                                    Update Department
                                </span>
                            </button>

                            <a href="{{ route('admin.departments') }}" class="flex-1 sm:flex-none bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold border border-gray-300 transition-colors text-center">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Back to Departments
                                </span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
