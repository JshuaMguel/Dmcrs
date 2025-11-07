@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                @foreach ($errors->all() as $error)
                    <span class="block sm:inline">{{ $error }}</span>
                @endforeach
            </div>
        @endif

        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">🗄️ {{ ucfirst(str_replace('_', ' ', $tableName)) }} Table</h1>
                    <p class="text-gray-600 mt-2">{{ $data->total() }} total records</p>
                </div>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                    <a href="{{ route('admin.database.index') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors text-sm sm:text-base">
                        ← Back to Tables
                    </a>
                    @if($data->total() > 0)
                    <form method="POST" action="{{ route('admin.database.truncate', $tableName) }}" 
                          onsubmit="return confirm('Are you sure you want to delete ALL data from {{ $tableName }}? This cannot be undone!')"
                          class="inline">
                        @csrf
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm sm:text-base w-full sm:w-auto">
                            🗑️ <span class="hidden sm:inline">Clear All Data</span><span class="sm:hidden">Clear All</span>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
        @endif

        <!-- Data Table -->
        @if($data->count() > 0)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach($columns as $column)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $column }}
                            </th>
                            @endforeach
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($data as $record)
                        <tr class="hover:bg-gray-50">
                            @foreach($columns as $column)
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @php
                                    $value = $record->$column;
                                    if (is_null($value)) {
                                        echo '<span class="text-gray-400 italic">NULL</span>';
                                    } elseif (is_string($value) && strlen($value) > 50) {
                                        echo '<span title="' . htmlspecialchars($value) . '">' . htmlspecialchars(substr($value, 0, 50)) . '...</span>';
                                    } elseif ($column === 'created_at' || $column === 'updated_at') {
                                        echo $value ? \Carbon\Carbon::parse($value)->format('M d, Y H:i') : '';
                                    } elseif ($column === 'password') {
                                        echo '<span class="text-gray-400">••••••••</span>';
                                    } else {
                                        echo htmlspecialchars($value);
                                    }
                                @endphp
                            </td>
                            @endforeach
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if(isset($record->id))
                                <form method="POST" action="{{ route('admin.database.delete-record', [$tableName, $record->id]) }}" 
                                      onsubmit="return confirm('Delete this {{ $tableName }} record (ID: {{ $record->id }})? This cannot be undone!')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-100 text-red-700 px-3 py-1 rounded-md hover:bg-red-200 transition-colors text-sm">
                                        🗑️ Delete
                                    </button>
                                </form>
                                @else
                                <span class="text-gray-400 text-sm">No ID</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $data->links() }}
            </div>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-lg p-12 text-center">
            <div class="text-gray-400 text-6xl mb-4">📭</div>
            <h3 class="text-xl font-medium text-gray-900 mb-2">No Data Found</h3>
            <p class="text-gray-600">The {{ $tableName }} table is empty.</p>
        </div>
        @endif
    </div>
</div>
@endsection