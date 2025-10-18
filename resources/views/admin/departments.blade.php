@extends('layouts.app')

@section('title', 'Program Management - USTP DMCRS')

@section('content')
    <div class="py-8 bg-gradient-to-br from-ustpBlue/5 via-white to-ustpGold/5 min-h-screen">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Header Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 mb-8">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-ustpBlue rounded-full mb-4">
                        <span class="text-3xl text-white">🏢</span>
                    </div>
                    <h1 class="text-4xl font-bold text-ustpBlue mb-2">Program Management</h1>
                    <p class="text-gray-600 text-lg">Create, edit, and manage academic programs</p>
                </div>

                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-xl mb-6 flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Add Program Form -->
                <form method="POST" action="{{ route('admin.createDepartment') }}" class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                    @csrf
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Add New Program</h3>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Program Name</label>
                            <input type="text" id="name" name="name" placeholder="Enter program name"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-gradient-to-r from-ustpBlue to-ustpBlue/90 hover:from-ustpBlue/90 hover:to-ustpBlue text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Add Program
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Programs Table Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-ustpBlue to-ustpBlue/90 p-6">
                    <h2 class="text-2xl font-bold text-white">Current Programs</h2>
                    <p class="text-ustpGold/90 mt-1">{{ count($departments) }} program(s) registered</p>
                </div>

                @if(count($departments) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Program Name</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Created</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($departments as $department)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="bg-ustpBlue/10 rounded-full p-2 mr-3">
                                                <svg class="w-5 h-5 text-ustpBlue" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-lg font-semibold text-gray-900">{{ $department->name }}</div>
                                                <div class="text-sm text-gray-500">Program ID: {{ $department->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $department->created_at ? $department->created_at->format('M d, Y') : 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $department->created_at ? $department->created_at->format('h:i A') : '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center">
                                            <a href="{{ route('admin.editDepartment', $department->id) }}"
                                               class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 p-2 rounded-lg font-medium transition-colors flex items-center justify-center"
                                               title="Edit Program">
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
                            <span class="text-4xl text-gray-400">🏢</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Programs Found</h3>
                        <p class="text-gray-500">Create your first program using the form above.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
