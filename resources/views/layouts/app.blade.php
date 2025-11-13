<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'USTP DMCRS'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js for mobile menu -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Flash Messages Handler for Production -->
    <script src="{{ asset('js/flash-messages.js') }}"></script>
    


    <!-- Alpine.js cloak styles -->
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        midnight: '#191970',
                        lightblue: '#ADD8E6',
                        combo: '#274c77',

                        /* ✅ USTP Official Colors */
                        ustpGold: '#FFB703',
                        ustpBlue: '#023047',
                        ustpBlack: '#000000',
                        ustpGray: '#F5F5F5'
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-ustpGray text-ustpBlack">

<div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-50 lg:hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="sidebarOpen = false"></div>
    </div>

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-ustpBlue text-white p-6 shadow-lg transform -translate-x-full transition-transform duration-200 ease-in-out lg:translate-x-0"
           :class="{'translate-x-0': sidebarOpen}"
           x-show="true"
           x-cloak>
        @php
            $role = Auth::user()->role ?? null;
        @endphp

        {{-- 📌 Faculty Sidebar --}}
        @if($role === 'faculty')
            <div class="flex flex-col items-center mb-4">
                @php
                    $profileImage = Auth::user()->profile_image;
                    $imageExists = $profileImage && Storage::disk('public')->exists($profileImage);
                @endphp
                @if($profileImage && $imageExists)
                    <img src="{{ asset('storage/' . $profileImage) }}" alt="Profile Image" class="w-14 h-14 rounded-full object-cover border-2 border-ustpGold mb-1 shadow" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-14 h-14 rounded-full bg-ustpGold flex items-center justify-center text-xl text-ustpBlue font-bold mb-1 border-2 border-ustpGold shadow" style="display: none;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @else
                    <div class="w-14 h-14 rounded-full bg-ustpGold flex items-center justify-center text-xl text-ustpBlue font-bold mb-1 border-2 border-ustpGold shadow">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="text-center mt-1">
                    <div class="font-bold text-base">{{ Auth::user()->name }}</div>
                    <div class="text-ustpGold font-semibold text-sm">{{ ucwords(str_replace('_', ' ', Auth::user()->role)) }}</div>
                    <div class="text-xs text-white">{{ Auth::user()->department ? Auth::user()->department->name : '-' }}</div>

                </div>
            </div>
            <div class="mb-10"></div>
            <h2 class="text-base font-semibold mb-6 text-ustpGold tracking-wide text-center">Menu</h2>
            <ul class="space-y-3">
                <li><a href="{{ route('faculty.dashboard') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('faculty.dashboard') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">🏠 Dashboard</a></li>
                <li><a href="{{ route('makeup-requests.index') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('makeup-requests.*') && !request()->routeIs('proof-upload.*') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">📄 Make-Up Requests</a></li>
                <li><a href="{{ route('faculty.student-confirmations') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('faculty.student-confirmations') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">✅ Student Confirmations</a></li>
                <li><a href="{{ route('proof-upload.index') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('proof-upload.*') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">📸 Upload Proof</a></li>
                <li><a href="{{ route('schedules.index') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('schedules.index') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">📅 Class Schedule Board</a></li>
                <li><a href="{{ route('profile.edit') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('profile.edit') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">⚙️ Profile Settings</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow">🚪 Logout</button>
                    </form>
                </li>
            </ul>

        {{-- 📌 Department Chair Sidebar --}}
        @elseif($role === 'department_chair')
            <div class="flex flex-col items-center mb-4">
                @php
                    $profileImage = Auth::user()->profile_image;
                    $imageExists = $profileImage && Storage::disk('public')->exists($profileImage);
                @endphp
                @if($profileImage && $imageExists)
                    <img src="{{ asset('storage/' . $profileImage) }}" alt="Profile Image" class="w-14 h-14 rounded-full object-cover border-2 border-ustpGold mb-1 shadow" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-14 h-14 rounded-full bg-ustpGold flex items-center justify-center text-xl text-ustpBlue font-bold mb-1 border-2 border-ustpGold shadow" style="display: none;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @else
                    <div class="w-14 h-14 rounded-full bg-ustpGold flex items-center justify-center text-xl text-ustpBlue font-bold mb-1 border-2 border-ustpGold shadow">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="text-center mt-1">
                    <div class="font-bold text-base">{{ Auth::user()->name }}</div>
                    <div class="text-ustpGold font-semibold text-sm">{{ ucwords(str_replace('_', ' ', Auth::user()->role)) }}</div>
                    <div class="text-xs text-white">{{ Auth::user()->department ? Auth::user()->department->name : '-' }}</div>
                </div>
            </div>
            <h2 class="text-base font-semibold mb-6 mt-2 text-ustpGold text-center">Menu</h2>
            <ul class="space-y-3">
                <li><a href="{{ route('department.dashboard') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('department.dashboard') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">🏠 Dashboard</a></li>
                <li><a href="{{ route('department.requests') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('department.requests*') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">📄 Pending Requests</a></li>
                <li><a href="{{ route('department.history') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('department.history') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">📜 Request History</a></li>
                <li><a href="{{ route('department.approvals') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('department.approvals') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">✅ Approvals Log</a></li>
                <li><a href="{{ route('schedules.index') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('schedules.index') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">📅 Class Schedule Board</a></li>
                <li><a href="{{ route('profile.edit') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('profile.edit') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">⚙️ Profile Settings</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow">🚪 Logout</button>
                    </form>
                </li>
            </ul>

        {{-- 📌 Academic Head Sidebar --}}
        @elseif($role === 'academic_head')
            <div class="flex flex-col items-center mb-4">
                @php
                    $profileImage = Auth::user()->profile_image;
                    $imageExists = $profileImage && Storage::disk('public')->exists($profileImage);
                @endphp
                @if($profileImage && $imageExists)
                    <img src="{{ asset('storage/' . $profileImage) }}" alt="Profile Image" class="w-14 h-14 rounded-full object-cover border-2 border-ustpGold mb-1 shadow" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-14 h-14 rounded-full bg-ustpGold flex items-center justify-center text-xl text-ustpBlue font-bold mb-1 border-2 border-ustpGold shadow" style="display: none;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @else
                    <div class="w-14 h-14 rounded-full bg-ustpGold flex items-center justify-center text-xl text-ustpBlue font-bold mb-1 border-2 border-ustpGold shadow">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="text-center mt-1">
                    <div class="font-bold text-base">{{ Auth::user()->name }}</div>
                    <div class="text-ustpGold font-semibold text-sm">{{ ucwords(str_replace('_', ' ', Auth::user()->role)) }}</div>
                    <div class="text-xs text-white">{{ Auth::user()->department ? Auth::user()->department->name : '-' }}</div>
                </div>
            </div>
            <h2 class="text-base font-semibold mb-6 mt-2 text-ustpGold text-center">Menu</h2>
            <ul class="space-y-3">
                <li><a href="{{ route('head.dashboard') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('head.dashboard') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">🏠 Dashboard</a></li>
                <li><a href="{{ route('head.requests.index') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('head.requests.index') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">📄 Pending Approvals</a></li>
                <li><a href="{{ route('head.schedule.index') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('head.schedule.index') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">📅 Class Schedule Board</a></li>
                <li><a href="{{ route('head.reports.index') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('head.reports.index') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">📊 Logs & Reports</a></li>
                <li><a href="{{ route('profile.edit') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('profile.edit') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">⚙️ Profile Settings</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow">🚪 Logout</button>
                    </form>
                </li>
            </ul>
        {{-- 📌 Admin/Super Admin Sidebar --}}
        @elseif($role === 'admin' || $role === 'super_admin')
            <div class="flex flex-col items-center mb-4">
                @php
                    $profileImage = Auth::user()->profile_image;
                    $imageExists = $profileImage && Storage::disk('public')->exists($profileImage);
                @endphp
                @if($profileImage && $imageExists)
                    <img src="{{ asset('storage/' . $profileImage) }}" alt="Profile Image" class="w-14 h-14 rounded-full object-cover border-2 border-ustpGold mb-1 shadow" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-14 h-14 rounded-full bg-ustpGold flex items-center justify-center text-xl text-ustpBlue font-bold mb-1 border-2 border-ustpGold shadow" style="display: none;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @else
                    <div class="w-14 h-14 rounded-full bg-ustpGold flex items-center justify-center text-xl text-ustpBlue font-bold mb-1 border-2 border-ustpGold shadow">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="text-center mt-1">
                    <div class="font-bold text-base">{{ Auth::user()->name }}</div>
                    <div class="text-ustpGold font-semibold text-sm">{{ ucwords(str_replace('_', ' ', Auth::user()->role)) }}</div>
                </div>
            </div>
            <h2 class="text-base font-semibold mb-6 mt-2 text-ustpGold text-center">Menu</h2>
            <ul class="space-y-3">
                <li><a href="{{ route('admin.dashboard') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('admin.dashboard') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">🏠 Dashboard</a></li>
                <li><a href="{{ route('admin.users') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('admin.users') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">👥 Manage Users</a></li>
                <li><a href="{{ route('admin.departments') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('admin.departments') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">🏢 Manage Departments</a></li>
                <li><a href="{{ route('admin.subjects.index') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('admin.subjects.*') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">📚 Manage Subjects</a></li>
                <li><a href="{{ route('admin.sections.index') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('admin.sections.*') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">🏫 Manage Sections</a></li>
                <li><a href="{{ route('admin.schedules.board') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('admin.schedules.*') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">📅 Manage Schedules</a></li>
                <li><a href="{{ route('admin.rooms.index') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('admin.rooms.*') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">🏢 Manage Rooms</a></li>
                <li><a href="{{ route('admin.database.index') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('admin.database.*') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">🗄️ Database Manager</a></li>
                <li><a href="{{ route('admin.settings') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('admin.settings') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">⚙️ System Settings</a></li>
                <li><a href="{{ route('profile.edit') }}" class="block bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow {{ request()->routeIs('profile.edit') ? 'bg-ustpGold text-ustpBlack font-bold' : '' }}">📝 Profile Settings</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left bg-white text-ustpBlack hover:bg-ustpGold hover:text-ustpBlack p-2 rounded shadow">🚪 Logout</button>
                    </form>
                </li>
            </ul>
        @endif
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col lg:ml-64">
        <!-- ✅ Top Navigation -->
        <header class="bg-ustpGold shadow relative">
            <!-- Mobile menu button -->
            <div class="lg:hidden flex items-center justify-between p-4">
                <button @click="sidebarOpen = !sidebarOpen" class="text-ustpBlue hover:bg-ustpBlue hover:text-white p-2 rounded-md transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-ustpBlue rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">U</span>
                    </div>
                    <span class="text-ustpBlue font-bold text-lg">DMCRS</span>
                </div>
            </div>

            <!-- Desktop navigation -->
            <div class="hidden lg:block">
                @include('layouts.navigation')
            </div>
        </header>

        <!-- ✅ Page Content -->
        <main class="p-4 sm:p-6 flex-1 overflow-x-auto">
            @hasSection('content')
                @yield('content')
            @endif
        </main>
    </div>
</div>

</body>
</html>
