@extends('layouts.app')

@section('title', 'System Fallback - USTP DMCRS')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md text-center">
        <h1 class="text-2xl font-bold mb-4 text-red-600">Session Issue Detected</h1>
        <p class="mb-6 text-gray-700">You are logged in, but we couldn't redirect you to your dashboard. This may be due to a role or session problem.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">Logout</button>
        </form>
        <p class="mt-4 text-sm text-gray-500">After logging out, you can log in again to restore access.</p>
    </div>
</div>
@endsection
