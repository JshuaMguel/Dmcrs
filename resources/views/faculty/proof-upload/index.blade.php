@extends('layouts.app')

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
                    <h2 class="text-2xl sm:text-3xl font-bold text-ustpBlue">Upload Proof of Conduct</h2>
                    <p class="text-gray-600 text-sm sm:text-base">ISO Requirement - Upload proof images for approved makeup classes</p>
                </div>
            </div>
        </div>
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

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <span class="text-red-500 text-xl mr-3">❌</span>
                <div>
                    <h4 class="font-semibold text-red-800">Error!</h4>
                    <p class="text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Info Box -->
    <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4 sm:p-6 mb-6">
        <h3 class="text-base sm:text-lg font-semibold text-ustpBlue mb-2 flex items-center">
            <span class="mr-2">ℹ️</span>
            Instructions
        </h3>
        <ul class="text-sm sm:text-base text-gray-700 space-y-2 list-disc list-inside">
            <li>Only approved makeup class requests are shown here</li>
            <li>Print the student list, take pictures with students during the makeup class</li>
            <li>You can upload multiple images (max 15MB each) to capture all students</li>
            <li>This proof is required for ISO compliance documentation</li>
        </ul>
    </div>

    <!-- Approved Requests List -->
    @if($approvedRequests->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($approvedRequests as $request)
                <div class="bg-gray-50 rounded-lg border-2 border-gray-200 p-4 sm:p-6 hover:shadow-lg transition-shadow">
                    <!-- Request Header -->
                    <div class="mb-4 pb-4 border-b border-gray-300">
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-ustpGold text-ustpBlue">
                                {{ $request->tracking_number }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                ✅ Approved
                            </span>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-ustpBlue mt-2">
                            @if($request->subject && is_object($request->subject))
                                {{ $request->subject->subject_code }} - {{ $request->subject->subject_title }}
                            @else
                                {{ $request->subject }}
                            @endif
                        </h3>
                        <div class="flex flex-wrap gap-2 mt-2 text-sm text-gray-600">
                            <span>📅 {{ \Carbon\Carbon::parse($request->preferred_date)->format('M d, Y') }}</span>
                            <span>🕐 {{ $request->preferred_time }} - {{ $request->end_time }}</span>
                            <span>🏫 {{ $request->room }}</span>
                        </div>
                    </div>

                    <!-- Print Student List Button -->
                    <div class="mb-4">
                        <a href="{{ route('makeup-requests.print-student-list', $request->id) }}" target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-ustpGold text-ustpBlue rounded-lg hover:bg-yellow-500 transition-colors font-semibold text-sm">
                            <span class="mr-2">🖨️</span>
                            Print Student List (PDF)
                        </a>
                    </div>

                    <!-- Upload Form -->
                    <form action="{{ route('makeup-requests.upload-proof', $request->id) }}" method="POST" enctype="multipart/form-data" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Proof Images (JPG/PNG, Max 15MB each)
                            </label>
                            <div class="flex flex-col sm:flex-row gap-2">
                                <input type="file" name="proof_of_conduct[]" accept="image/jpeg,image/jpg,image/png" multiple required
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-ustpBlue file:text-white hover:file:bg-blue-800">
                                <button type="submit"
                                        class="inline-flex items-center justify-center px-4 py-2 bg-ustpBlue text-white rounded-lg hover:bg-blue-800 transition-colors font-semibold whitespace-nowrap text-sm">
                                    <span class="mr-2">📤</span>
                                    Upload
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Hold Ctrl (Windows) or Cmd (Mac) to select multiple images</p>
                            @error('proof_of_conduct.*')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </form>

                    <!-- Display Uploaded Images -->
                    @php
                        $proofs = $request->proof_of_conduct ?? [];
                    @endphp
                    @if(count($proofs) > 0)
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <h4 class="font-semibold text-gray-700 mb-3 text-sm">
                                ✅ Uploaded Proof Images ({{ count($proofs) }} {{ count($proofs) > 1 ? 'images' : 'image' }})
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                @foreach($proofs as $index => $proof)
                                    <div class="relative border border-gray-200 rounded-lg overflow-hidden bg-gray-50">
                                        <a href="{{ asset('storage/' . $proof) }}" target="_blank" class="block">
                                            <img src="{{ asset('storage/' . $proof) }}" 
                                                 alt="Proof Image {{ $index + 1 }}"
                                                 class="w-full h-32 object-cover hover:opacity-90 transition-opacity">
                                        </a>
                                        <div class="p-1 bg-white border-t border-gray-200">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs text-gray-600">#{{ $index + 1 }}</span>
                                                <form action="{{ route('makeup-requests.delete-proof-image', ['id' => $request->id, 'imageIndex' => $index]) }}" 
                                                      method="POST" 
                                                      class="inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this image?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-800 text-xs font-medium">
                                                        🗑️
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                            <p class="text-yellow-700 text-sm">⏳ No proof images uploaded yet. Please upload proof images above.</p>
                        </div>
                    @endif

                    <!-- View Details Link -->
                    <div class="mt-4 pt-4 border-t border-gray-300">
                        <a href="{{ route('makeup-requests.show', $request->id) }}"
                           class="text-ustpBlue hover:text-blue-800 text-sm font-medium">
                            👁️ View Full Details →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gray-50 rounded-lg border-2 border-gray-200 p-12 text-center">
            <span class="text-6xl mb-4 block">📸</span>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Approved Requests</h3>
            <p class="text-gray-600 mb-4">You don't have any approved makeup class requests yet.</p>
            <p class="text-sm text-gray-500">Once your requests are approved, they will appear here for proof upload.</p>
        </div>
    @endif
</div>
@endsection

