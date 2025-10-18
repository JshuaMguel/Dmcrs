{{-- This file is deprecated. Please use board.blade.php for the new schedule board with FullCalendar. --}}
@extends('layouts.app')

@section('title', 'Schedule Management - USTP DMCRS')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-red-600">This schedule board is outdated. Please use the new board.</h2>
</div>
@endsection

@php
    header('Location: ' . url('/schedules'));
    exit;
@endphp
