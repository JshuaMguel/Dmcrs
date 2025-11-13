@php
    use App\Helpers\TimeHelper;
@endphp
@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto bg-white p-4 sm:p-6 rounded-xl shadow-lg">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 sm:mb-8">
        <div>
            <div class="flex items-center mb-2">
                <div class="flex items-center justify-center w-10 sm:w-12 h-10 sm:h-12 bg-ustpBlue rounded-lg mr-3 sm:mr-4">
                    <span class="text-white text-lg sm:text-xl">📝</span>
                </div>
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-ustpBlue">My Makeup Requests</h2>
                    <p class="text-gray-600 text-sm sm:text-base">Manage your makeup class requests</p>
                </div>
            </div>
        </div>
        <a href="{{ route('makeup-requests.create') }}"
           class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-ustpBlue text-white rounded-lg hover:bg-blue-800 font-semibold transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-ustpGold text-sm sm:text-base">
            <span class="mr-2">➕</span>
            <span class="hidden sm:inline">New Request</span>
            <span class="sm:hidden">New</span>
        </a>
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

    <!-- Requests Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200"
                   style="min-width: 800px;">
                <thead class="bg-ustpBlue">
                    <tr>
                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-1 sm:mr-2">#️⃣</span>
                                <span class="hidden sm:inline">Tracking</span>
                                <span class="sm:hidden">ID</span>
                            </div>
                        </th>
                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-1 sm:mr-2">💭</span>
                                Reason
                            </div>
                        </th>
                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-1 sm:mr-2">📖</span>
                                Subject
                            </div>
                        </th>
                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-1 sm:mr-2">🏫</span>
                                Room
                            </div>
                        </th>
                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-1 sm:mr-2">📅</span>
                                Date
                            </div>
                        </th>
                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-1 sm:mr-2">🕐</span>
                                Time
                            </div>
                        </th>
                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-1 sm:mr-2">📎</span>
                                <span class="hidden sm:inline">Attachment</span>
                                <span class="sm:hidden">File</span>
                            </div>
                        </th>
                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-1 sm:mr-2">📊</span>
                                Status
                            </div>
                        </th>
                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-1 sm:mr-2">⚙️</span>
                                Actions
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($requests as $req)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <!-- Tracking Number -->
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-ustpGold text-ustpBlue">
                                    <span class="hidden sm:inline">{{ $req->tracking_number }}</span>
                                    <span class="sm:hidden">{{ substr($req->tracking_number, -3) }}</span>
                                </span>
                            </td>

                            <!-- Reason -->
                            <td class="px-3 sm:px-6 py-3 sm:py-4">
                                <div class="text-xs sm:text-sm text-gray-900 max-w-20 sm:max-w-xs truncate" title="{{ $req->reason }}">
                                    {{ $req->reason }}
                                </div>
                            </td>

                            <!-- Subject -->
                            <td class="px-3 sm:px-6 py-3 sm:py-4">
                                @if($req->subject && is_object($req->subject))
                                    <div class="text-xs sm:text-sm font-medium text-gray-900">{{ $req->subject->subject_code }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-32" title="{{ $req->subject->subject_title }}">{{ $req->subject->subject_title }}</div>
                                @else
                                    <div class="text-xs sm:text-sm font-medium text-gray-900">{{ $req->subject }}</div>
                                    @if($req->subject_title)
                                        <div class="text-xs text-gray-500 truncate max-w-32">{{ $req->subject_title }}</div>
                                    @endif
                                @endif
                            </td>

                            <!-- Room -->
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="text-xs sm:text-sm text-gray-900">{{ $req->room }}</div>
                            </td>

                            <!-- Date -->
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="text-xs sm:text-sm text-gray-900">
                                    <span class="hidden sm:inline">{{ \Carbon\Carbon::parse($req->preferred_date)->format('M d, Y') }}</span>
                                    <span class="sm:hidden">{{ \Carbon\Carbon::parse($req->preferred_date)->format('m/d') }}</span>
                                </div>
                            </td>

                            <!-- Time -->
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="text-xs sm:text-sm text-gray-900">
                                    <span class="hidden sm:inline">{{ TimeHelper::formatTime($req->preferred_time) }} - {{ TimeHelper::formatTime($req->end_time) }}</span>
                                    <span class="sm:hidden">{{ TimeHelper::formatTime($req->preferred_time) }}</span>
                                </div>
                            </td>

                            <!-- Attachment -->
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                @if($req->attachment)
                                    <a href="{{ asset('storage/' . $req->attachment) }}" target="_blank"
                                       class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-ustpBlue hover:bg-blue-200">
                                        <span class="hidden sm:inline">📎 View</span>
                                        <span class="sm:hidden">📎</span>
                                    </a>
                                @else
                                    <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                        <span class="hidden sm:inline">N/A</span>
                                        <span class="sm:hidden">-</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg-yellow-100 text-yellow-800', 'Pending'],
                                        'APPROVED' => ['bg-green-100 text-green-800', 'Approved'],
                                        'CHAIR_APPROVED' => ['bg-blue-100 text-blue-800', 'Chair Approved'],
                                        'HEAD_REJECTED' => ['bg-red-100 text-red-800', 'Declined'],
                                        'CHAIR_REJECTED' => ['bg-red-100 text-red-800', 'Declined'],
                                    ];
                                    $config = $statusConfig[$req->status] ?? ['bg-gray-100 text-gray-800', ucfirst(str_replace('_', ' ', $req->status))];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $config[0] }}">
                                    {{ $config[1] }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('makeup-requests.show', $req->id) }}"
                                       class="text-green-600 hover:text-green-900" title="View Details" aria-label="View request details">
                                        👁️
                                    </a>
                                    @if(in_array($req->status, ['pending', 'CHAIR_APPROVED']))
                                    <a href="{{ route('makeup-requests.edit', $req->id) }}"
                                       class="text-blue-600 hover:text-blue-900" title="Edit" aria-label="Edit request">
                                        ✏️
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-4xl mb-4">📝</span>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No requests found</h3>
                                    <p class="text-gray-500">You haven't created any makeup class requests yet.</p>
                                    <a href="{{ route('makeup-requests.create') }}"
                                       class="mt-4 inline-flex items-center px-4 py-2 bg-ustpBlue text-white rounded-lg hover:bg-blue-800 transition-colors">
                                        <span class="mr-2">➕</span>
                                        Create Your First Request
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
