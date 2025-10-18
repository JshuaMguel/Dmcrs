<nav x-data="{ open: false }" style="background-color:#FDB813;" class="border-b border-gray-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-800 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-lg">U</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-blue-800 font-bold text-lg leading-tight">USTP DMCRS</span>
                                <span class="text-blue-700 text-xs leading-tight">Digital Makeup Class Request System</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown with Notification -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <!-- Notification Bell -->
                <div class="relative">
                    <a href="{{ route('notifications.index') }}" class="inline-flex items-center px-3 py-2 text-blue-800 hover:text-blue-900 transition duration-150 ease-in-out">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 19V20H3V19L5 17V11C5 7.9 7.03 5.17 10 4.29C10 4.19 10 4.1 10 4C10 2.34 11.34 1 13 1S16 2.34 16 4C16 4.1 16 4.19 16 4.29C18.97 5.17 21 7.9 21 11V17L23 19ZM7 18H17V11C17 8.24 14.76 6 12 6S7 8.24 7 11V18Z"/>
                        </svg>
                        @php
                            $unreadCount = Auth::check() ? Auth::user()->unreadNotifications->count() : 0;
                        @endphp
                        @if($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </a>
                </div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-800 hover:text-gray-900 focus:outline-none transition ease-in-out duration-150" style="background-color:#FDB813;">
                            <!-- Role + Name -->
                            <div class="flex flex-col items-start leading-tight">
                                <span class="text-xs text-blue-800 font-semibold">
                                    {{ Auth::check() ? ucfirst(Auth::user()->role) : 'Guest' }}
                                </span>
                                <span>{{ Auth::check() ? Auth::user()->name : 'Not Logged In' }}</span>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-800 hover:text-gray-900 focus:outline-none transition duration-150 ease-in-out" style="background-color:#FDB813;">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden" style="background-color:#FDB813;">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Mobile Notifications -->
            <x-responsive-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.*')">
                <div class="flex items-center">
                    🔔 Notifications
                    @php
                        $unreadCount = Auth::check() ? Auth::user()->unreadNotifications->count() : 0;
                    @endphp
                    @if($unreadCount > 0)
                        <span class="ml-2 bg-red-500 text-white text-xs rounded-full px-2 py-0.5">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </div>
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-300">
            <div class="px-4">
                <!-- Role + Name (mobile view) -->
                <div class="font-medium text-xs text-blue-800">
                    {{ Auth::check() ? ucfirst(Auth::user()->role) : 'Guest' }}
                </div>
                <div class="font-medium text-base text-gray-900">
                    {{ Auth::check() ? Auth::user()->name : 'Not Logged In' }}
                </div>
                <div class="font-medium text-sm text-gray-700">
                    {{ Auth::check() ? Auth::user()->email : 'No Email' }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
