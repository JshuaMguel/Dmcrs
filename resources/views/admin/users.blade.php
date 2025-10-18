
@extends('layouts.app')

@section('title', 'User Management - USTP DMCRS')

@section('content')
    <div class="py-8 bg-gradient-to-br from-ustpBlue/5 via-white to-ustpGold/5 min-h-screen">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Header Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 mb-8">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-ustpBlue rounded-full mb-4">
                        <span class="text-3xl text-white">👥</span>
                    </div>
                    <h1 class="text-4xl font-bold text-ustpBlue mb-2">User Management</h1>
                    <p class="text-gray-600 text-lg">Create, edit, and manage system users</p>
                </div>

                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-xl mb-6 flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Add User Form -->
                <form method="POST" action="{{ route('admin.createUser') }}" class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                    @csrf
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Add New User</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input id="name" name="name" type="text" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Enter full name">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input id="email" name="email" type="email" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Enter email address">
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input id="password" name="password" type="password" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Enter password">
                        </div>
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">User Role</label>
                            <select id="role" name="role" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="faculty">Faculty</option>
                                <option value="department_chair">Department Chair</option>
                                <option value="academic_head">Academic Head</option>
                            </select>
                        </div>
                        <div id="department-select" class="md:col-span-2">
                            <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                            <select id="department_id" name="department_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-ustpBlue to-ustpBlue/90 hover:from-ustpBlue/90 hover:to-ustpBlue text-white font-semibold py-3 rounded-lg shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add User
                    </button>
                </form>
            </div>

            <!-- Users Table Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-ustpBlue to-ustpBlue/90 p-6">
                    <h2 class="text-2xl font-bold text-white">System Users</h2>
                    <p class="text-ustpGold/90 mt-1">{{ count($users) }} user(s) registered</p>
                </div>

                @if(count($users) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">User Details</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Role & Department</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="bg-ustpBlue/10 rounded-full p-2 mr-3">
                                                <svg class="w-5 h-5 text-ustpBlue" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-lg font-semibold text-gray-900">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            @if($user->role == 'academic_head')
                                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                                </span>
                                            @elseif($user->role == 'department_chair')
                                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                                </span>
                                            @else
                                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                                </span>
                                            @endif
                                            <div class="text-sm text-gray-500 mt-1">
                                                {{ $user->department_id ? $departments->find($user->department_id)->name : 'No Department' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->is_active ?? true)
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center">
                                            <a href="{{ route('admin.editUser', $user->id) }}"
                                               class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 p-2 rounded-lg font-medium transition-colors flex items-center justify-center"
                                               title="Edit {{ $user->name }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="bg-gray-100 rounded-full p-4 w-16 h-16 mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Users Found</h3>
                        <p class="text-gray-500">Create your first user using the form above.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
    <script>
        document.getElementById('role').addEventListener('change', function() {
            var deptSelect = document.getElementById('department-select');
            if (this.value === 'academic_head') {
                deptSelect.style.display = 'none';
            } else {
                deptSelect.style.display = 'block';
            }
        });
    </script>

