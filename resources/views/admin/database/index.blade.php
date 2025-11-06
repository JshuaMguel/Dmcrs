@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">🗄️ Database Management</h1>
                    <p class="text-gray-600 mt-2">View and manage all database tables</p>
                </div>
                <div class="text-right">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        ← Back to Admin
                    </a>
                </div>
            </div>
        </div>

        <!-- Tables Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tables as $table)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $table)) }}</h3>
                        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded">
                            {{ $tableData[$table]['count'] }} records
                        </span>
                    </div>
                    
                    <div class="text-sm text-gray-600 mb-4">
                        <strong>Columns:</strong> {{ count($tableData[$table]['columns']) }}
                        <br>
                        <small>{{ implode(', ', array_slice($tableData[$table]['columns'], 0, 3)) }}{{ count($tableData[$table]['columns']) > 3 ? '...' : '' }}</small>
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.database.table', $table) }}" 
                           class="w-full bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            👁️ View & Manage Data
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Quick Stats -->
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">📊 Database Statistics</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($tableData as $table => $data)
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $data['count'] }}</div>
                    <div class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $table)) }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection