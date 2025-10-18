@extends('layouts.app')

@section('title', 'System Settings - USTP DMCRS')

@section('content')
    <div class="py-4 sm:py-8 bg-gradient-to-br from-ustpBlue/5 via-white to-ustpGold/5 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8">
                <div class="text-center mb-6 sm:mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-ustpBlue rounded-full mb-4">
                        <span class="text-2xl sm:text-3xl text-white">⚙️</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-ustpBlue mb-2">System Settings</h1>
                    <p class="text-gray-600 text-base sm:text-lg">Configure application settings and preferences</p>
                </div>

                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-xl mb-6 flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl mb-6 flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl mb-6">
                        <div class="flex items-center gap-3 mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
            </div>

            <!-- Settings Tabs Navigation -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-ustpBlue to-ustpBlue/90 p-6">
                    <h2 class="text-2xl font-bold text-white">System Configuration</h2>
                    <p class="text-ustpGold/90 mt-1">Manage comprehensive system settings and preferences</p>
                </div>

                <!-- Tab Navigation -->
                <div class="bg-gray-50 px-4 sm:px-6 py-3 border-b border-gray-200 overflow-x-auto">
                    <div class="flex space-x-4 sm:space-x-8 min-w-max sm:min-w-0 tab-navigation" role="tablist">
                        <button type="button" class="tab-btn active" data-tab="makeup">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Makeup Classes
                        </button>
                        <button type="button" class="tab-btn" data-tab="system">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            System Info
                        </button>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.updateSettings') }}" class="p-4 sm:p-6 lg:p-8">
                    @csrf

                    <!-- Makeup Classes Settings Tab -->
                    <div id="makeup" class="tab-content active">
                        <div class="space-y-8">
                            <!-- Request Settings -->
                            <div class="bg-gray-50 rounded-xl p-4 sm:p-6 border border-gray-200">
                                <div class="flex items-center gap-3 mb-4 sm:mb-6">
                                    <div class="bg-yellow-100 rounded-full p-2 sm:p-3">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg sm:text-xl font-bold text-gray-900">Makeup Class Configuration</h3>
                                        <p class="text-gray-600 text-sm sm:text-base">Configure makeup class request parameters</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                    <div>
                                        <label for="max_advance_days" class="block text-sm font-semibold text-gray-700 mb-2">Max Advance Request Days</label>
                                        @error('max_advance_days')
                                            <input type="number" id="max_advance_days" name="max_advance_days"
                                                   value="{{ old('max_advance_days', $settings['max_advance_days'] ?? 30) }}"
                                                   min="1" max="90"
                                                   class="w-full px-4 py-3 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                                                   placeholder="30" required>
                                        @else
                                            <input type="number" id="max_advance_days" name="max_advance_days"
                                                   value="{{ old('max_advance_days', $settings['max_advance_days'] ?? 30) }}"
                                                   min="1" max="90"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                   placeholder="30" required>
                                        @enderror
                                        @error('max_advance_days')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-sm text-gray-500 mt-1">Maximum days in advance to request makeup classes</p>
                                    </div>
                                    <div>
                                        <label for="min_notice_hours" class="block text-sm font-semibold text-gray-700 mb-2">Minimum Notice Hours</label>
                                        @error('min_notice_hours')
                                            <input type="number" id="min_notice_hours" name="min_notice_hours"
                                                   value="{{ old('min_notice_hours', $settings['min_notice_hours'] ?? 24) }}"
                                                   min="1" max="168"
                                                   class="w-full px-4 py-3 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                                                   placeholder="24" required>
                                        @else
                                            <input type="number" id="min_notice_hours" name="min_notice_hours"
                                                   value="{{ old('min_notice_hours', $settings['min_notice_hours'] ?? 24) }}"
                                                   min="1" max="168"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                   placeholder="24" required>
                                        @enderror
                                        @error('min_notice_hours')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-sm text-gray-500 mt-1">Minimum hours notice required for requests</p>
                                    </div>
                                    <div>
                                        <label for="auto_approve_threshold" class="block text-sm font-semibold text-gray-700 mb-2">Auto-Approve After (hours)</label>
                                        @error('auto_approve_threshold')
                                            <input type="number" id="auto_approve_threshold" name="auto_approve_threshold"
                                                   value="{{ old('auto_approve_threshold', $settings['auto_approve_threshold'] ?? 72) }}"
                                                   min="0" max="168"
                                                   class="w-full px-4 py-3 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                                                   placeholder="72" required>
                                        @else
                                            <input type="number" id="auto_approve_threshold" name="auto_approve_threshold"
                                                   value="{{ old('auto_approve_threshold', $settings['auto_approve_threshold'] ?? 72) }}"
                                                   min="0" max="168"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                   placeholder="72" required>
                                        @enderror
                                        @error('auto_approve_threshold')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-sm text-gray-500 mt-1">Auto-approve requests after this many hours (0 to disable)</p>
                                    </div>
                                    <div>
                                        <label for="max_daily_requests" class="block text-sm font-semibold text-gray-700 mb-2">Max Daily Requests per Faculty</label>
                                        @error('max_daily_requests')
                                            <input type="number" id="max_daily_requests" name="max_daily_requests"
                                                   value="{{ old('max_daily_requests', $settings['max_daily_requests'] ?? 3) }}"
                                                   min="1" max="10"
                                                   class="w-full px-4 py-3 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                                                   placeholder="3" required>
                                        @else
                                            <input type="number" id="max_daily_requests" name="max_daily_requests"
                                                   value="{{ old('max_daily_requests', $settings['max_daily_requests'] ?? 3) }}"
                                                   min="1" max="10"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                   placeholder="3" required>
                                        @enderror
                                        @error('max_daily_requests')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-sm text-gray-500 mt-1">Maximum requests per faculty per day</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Info Tab -->
                    <div id="system" class="tab-content">
                        <div class="space-y-8">
                            <!-- System Statistics -->
                            <div class="bg-gray-50 rounded-xl p-4 sm:p-6 border border-gray-200">
                                <div class="flex items-center gap-3 mb-4 sm:mb-6">
                                    <div class="bg-purple-100 rounded-full p-2 sm:p-3">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg sm:text-xl font-bold text-gray-900">System Statistics</h3>
                                        <p class="text-gray-600 text-sm sm:text-base">Current system usage and performance metrics</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                                    <!-- Users by Role -->
                                    <div class="bg-white rounded-lg p-3 sm:p-4 border border-gray-200 text-center">
                                        <div class="text-xl sm:text-2xl font-bold text-blue-600">{{ \App\Models\User::where('role', 'faculty')->count() }}</div>
                                        <div class="text-xs sm:text-sm text-gray-500">Faculty Members</div>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 sm:p-4 border border-gray-200 text-center">
                                        <div class="text-xl sm:text-2xl font-bold text-green-600">{{ \App\Models\User::where('role', 'department_chair')->count() }}</div>
                                        <div class="text-xs sm:text-sm text-gray-500">Department Chairs</div>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 sm:p-4 border border-gray-200 text-center">
                                        <div class="text-xl sm:text-2xl font-bold text-purple-600">{{ \App\Models\User::where('role', 'academic_head')->count() }}</div>
                                        <div class="text-xs sm:text-sm text-gray-500">Academic Heads</div>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 sm:p-4 border border-gray-200 text-center">
                                        <div class="text-xl sm:text-2xl font-bold text-orange-600">{{ \App\Models\Department::count() }}</div>
                                        <div class="text-xs sm:text-sm text-gray-500">Departments</div>
                                    </div>
                                </div>

                                <!-- Request Statistics -->
                                <div class="mt-4 sm:mt-6">
                                    <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Request Statistics</h4>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
                                        <div class="bg-white rounded-lg p-3 sm:p-4 border border-gray-200 text-center">
                                            <div class="text-xl sm:text-2xl font-bold text-yellow-600">{{ \App\Models\MakeUpClassRequest::where('status', 'pending')->count() }}</div>
                                            <div class="text-xs sm:text-sm text-gray-500">Pending</div>
                                        </div>
                                        <div class="bg-white rounded-lg p-3 sm:p-4 border border-gray-200 text-center">
                                            <div class="text-xl sm:text-2xl font-bold text-blue-600">{{ \App\Models\MakeUpClassRequest::where('status', 'CHAIR_APPROVED')->count() }}</div>
                                            <div class="text-xs sm:text-sm text-gray-500">Chair Approved</div>
                                        </div>
                                        <div class="bg-white rounded-lg p-3 sm:p-4 border border-gray-200 text-center">
                                            <div class="text-xl sm:text-2xl font-bold text-green-600">{{ \App\Models\MakeUpClassRequest::where('status', 'APPROVED')->count() }}</div>
                                            <div class="text-xs sm:text-sm text-gray-500">Fully Approved</div>
                                        </div>
                                        <div class="bg-white rounded-lg p-3 sm:p-4 border border-gray-200 text-center">
                                            <div class="text-xl sm:text-2xl font-bold text-red-600">{{ \App\Models\MakeUpClassRequest::whereIn('status', ['CHAIR_REJECTED', 'HEAD_REJECTED'])->count() }}</div>
                                            <div class="text-xs sm:text-sm text-gray-500">Rejected</div>
                                        </div>
                                        <div class="bg-white rounded-lg p-3 sm:p-4 border border-gray-200 text-center col-span-2 sm:col-span-1">
                                            <div class="text-xl sm:text-2xl font-bold text-gray-600">{{ \App\Models\MakeUpClassRequest::count() }}</div>
                                            <div class="text-xs sm:text-sm text-gray-500">Total Requests</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4 sm:pt-6 border-t border-gray-200">
                        <button type="submit" class="w-full sm:flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 sm:px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-200 transform hover:scale-105">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                <span class="text-sm sm:text-base">Save All Settings</span>
                            </span>
                        </button>
                        <button type="button" onclick="location.reload()" class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 sm:px-6 py-3 rounded-lg font-semibold border border-gray-300 transition-colors">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <span class="text-sm sm:text-base">Reset Changes</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- CSS and JavaScript for Tabs -->
            <style>
                .tab-btn {
                    @apply flex items-center gap-1 sm:gap-2 px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 transition-colors whitespace-nowrap;
                }
                .tab-btn.active {
                    @apply text-ustpBlue border-ustpBlue bg-white rounded-t-lg;
                }
                .tab-content {
                    @apply hidden;
                }
                .tab-content.active {
                    @apply block;
                }

                /* Custom scrollbar for mobile tab navigation */
                .tab-navigation::-webkit-scrollbar {
                    height: 3px;
                }
                .tab-navigation::-webkit-scrollbar-track {
                    background: #f1f1f1;
                }
                .tab-navigation::-webkit-scrollbar-thumb {
                    background: #c1c1c1;
                    border-radius: 10px;
                }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Tab switching functionality
                    const tabBtns = document.querySelectorAll('.tab-btn');
                    const tabContents = document.querySelectorAll('.tab-content');

                    tabBtns.forEach(btn => {
                        btn.addEventListener('click', function() {
                            const targetTab = this.getAttribute('data-tab');

                            // Remove active class from all tabs and contents
                            tabBtns.forEach(b => b.classList.remove('active'));
                            tabContents.forEach(c => c.classList.remove('active'));

                            // Add active class to clicked tab and corresponding content
                            this.classList.add('active');
                            document.getElementById(targetTab).classList.add('active');
                        });
                    });
                });
            </script>


        </div>
    </div>
@endsection
