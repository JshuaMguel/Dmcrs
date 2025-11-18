@extends('layouts.app')

@section('title', 'Proof of Conduct - USTP DMCRS')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-4 sm:p-6 rounded-xl shadow-lg">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 sm:mb-8">
        <div>
            <div class="flex items-center mb-2">
                <div class="flex items-center justify-center w-10 sm:w-12 h-10 sm:h-12 bg-ustpBlue rounded-lg mr-3 sm:mr-4">
                    <span class="text-white text-lg sm:text-xl">📸</span>
                </div>
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-ustpBlue">Proof of Conduct</h2>
                    <p class="text-gray-600 text-sm sm:text-base">View proof images uploaded by all faculty members</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4 sm:p-6 mb-6">
        <h3 class="text-base sm:text-lg font-semibold text-ustpBlue mb-2 flex items-center">
            <span class="mr-2">ℹ️</span>
            Information
        </h3>
        <p class="text-sm sm:text-base text-gray-700">
            This page shows all proof of conduct images uploaded by faculty members from all departments. 
            These images serve as verification that makeup classes were actually conducted.
        </p>
    </div>

    <!-- Requests with Proof List -->
    @if($requests->count() > 0)
        <div class="space-y-6">
            @foreach($requests as $request)
                @php
                    $proofs = $request->proof_of_conduct ?? [];
                @endphp
                @if(count($proofs) > 0)
                    <div class="bg-gray-50 rounded-lg border-2 border-gray-200 p-4 sm:p-6 hover:shadow-lg transition-shadow">
                        <!-- Request Header -->
                        <div class="mb-4 pb-4 border-b border-gray-300">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2 gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-ustpGold text-ustpBlue">
                                        {{ $request->tracking_number }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                        {{ $request->status === 'APPROVED' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </div>
                                <a href="{{ route('head.requests.show', $request->id) }}" 
                                   class="text-sm text-ustpBlue hover:underline">
                                    View Full Details →
                                </a>
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-ustpBlue mt-2">
                                @if($request->subject && is_object($request->subject))
                                    {{ $request->subject->subject_code }} - {{ $request->subject->subject_title }}
                                @else
                                    {{ $request->subject }}
                                @endif
                            </h3>
                            <div class="flex flex-wrap gap-2 mt-2 text-sm text-gray-600">
                                <span>👨‍🏫 {{ $request->faculty->name ?? 'N/A' }}</span>
                                <span>🏷️ {{ $request->faculty->department->name ?? 'N/A' }}</span>
                                <span>📅 {{ $request->preferred_date ? \Carbon\Carbon::parse($request->preferred_date)->format('M d, Y') : 'N/A' }}</span>
                                <span>🕐 {{ $request->preferred_time ?? 'N/A' }} - {{ $request->end_time ?? 'N/A' }}</span>
                                <span>🏫 {{ $request->room ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <!-- Proof Images -->
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                <span class="mr-2">📸</span>
                                Proof Images ({{ count($proofs) }} {{ count($proofs) > 1 ? 'images' : 'image' }})
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($proofs as $index => $proof)
                                    <div class="relative border border-gray-200 rounded-lg overflow-hidden bg-white hover:shadow-lg transition-shadow">
                                        <a href="{{ asset('storage/' . $proof) }}" target="_blank" class="block">
                                            <img src="{{ asset('storage/' . $proof) }}" 
                                                 alt="Proof Image {{ $index + 1 }}"
                                                 class="w-full h-48 object-cover hover:opacity-90 transition-opacity cursor-pointer">
                                        </a>
                                        <div class="p-2 bg-gray-50 border-t border-gray-200">
                                            <div class="flex items-center justify-center">
                                                <span class="text-xs text-gray-600">Image {{ $index + 1 }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <div class="flex flex-col items-center">
                <span class="text-4xl mb-4">📸</span>
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">No Proof of Conduct Uploaded Yet</h3>
                <p class="text-yellow-700 text-sm">
                    Faculty members will upload proof images after conducting their approved makeup classes.
                </p>
            </div>
        </div>
    @endif
</div>
@endsection

