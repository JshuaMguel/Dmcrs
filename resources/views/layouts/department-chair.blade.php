<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Department Chair</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white flex flex-col">
        <div class="p-6 border-b border-gray-700">
            <h2 class="text-lg font-bold">📌 Chair Menu</h2>
        </div>

        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('department.dashboard') }}"
               class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('department.dashboard') ? 'bg-gray-700' : '' }}">
               🏠 Dashboard
            </a>
            <a href="{{ route('department.requests') }}"
               class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('department.requests') ? 'bg-gray-700' : '' }}">
               📄 Pending Requests
            </a>
            <a href="{{ route('department.history') }}"
               class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('department.history') ? 'bg-gray-700' : '' }}">
               📜 History
            </a>
        </nav>

        <div class="p-4 border-t border-gray-700">
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 rounded hover:bg-gray-700">⚙️ Profile Settings</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 rounded hover:bg-gray-700">🚪 Logout</button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        @yield('content')
    </main>
</div>
</body>
</html>
