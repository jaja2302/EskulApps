<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <title>{{ config('app.name') }}</title>
        <style>
            body {
                background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
                transition: background 0.3s ease;
            }
            html.dark body {
                background: linear-gradient(135deg, #0c4a6e 0%, #075985 100%);
            }
        </style>
        @filamentStyles
        @vite('resources/css/app.css','resources/js/app.js')
    </head>
    <body class="min-h-screen dark:bg-gray-900 transition-colors duration-300">
        <div class="flex min-h-screen">
            <!-- Left Floating Sidebar Navigation -->
            <aside class="fixed md:left-6 md:top-1/2 md:-translate-y-1/2 md:h-[500px] md:w-16 
                        bottom-0 left-0 right-0 h-[60px] w-full md:rounded-full rounded-none
                        sidebar flex md:flex-col items-center md:py-6 md:space-y-8 
                        justify-around md:justify-start shadow-lg">
                
                <!-- Navigation Items -->
                <nav class="flex md:flex-col items-center md:space-y-6 w-full px-4 md:px-0 justify-around md:justify-start">
                    <!-- Home -->
                    <a href="{{ route('dashboard') }}" 
                       class="nav-icon tooltip rounded-xl {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition-all duration-200">
                        <svg class="w-6 h-6 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="tooltip-text md:left-14 md:top-auto md:-translate-y-1/2 
                                   bottom-14 left-1/2 -translate-x-1/2 md:translate-x-0">Home</span>
                    </a>

                    @role('admin')
                    <!-- Documents -->
                    <a href="{{ route('dashboard.eskul') }}" 
                       class="nav-icon tooltip rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200">
                        <svg class="w-6 h-6 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span class="tooltip-text md:left-14 md:top-auto md:-translate-y-1/2 bottom-14 left-1/2 -translate-x-1/2 md:translate-x-0">Dashboard Eskul</span>
                    </a>
      
   
                    <!-- Manage Users -->   
                    <a href="{{ route('manageusers') }}" 
                       class="nav-icon tooltip rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200">
                        <svg class="w-6 h-6 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.333c-4.8 0-8.627 3.828-8.627 8.667 0 4.839 3.827 8.667 8.627 8.667s8.627-3.828 8.627-8.667c0-4.839-3.827-8.667-8.627-8.667zM2.666 12c0-4.839 3.827-8.667 8.627-8.667s8.627 3.828 8.627 8.667c0 4.839-3.827 8.667-8.627 8.667s-8.627-3.828-8.627-8.667z"/>
                        </svg>
                        <span class="tooltip-text md:left-14 md:top-auto md:-translate-y-1/2 bottom-14 left-1/2 -translate-x-1/2 md:translate-x-0">Manage Users</span>
                    </a>
                    @endrole
                    
                    <!-- Settings -->
                    <a href="#" 
                       class="nav-icon tooltip rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200">
                        <svg class="w-6 h-6 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="tooltip-text md:left-14 md:top-auto md:-translate-y-1/2 
                                   bottom-14 left-1/2 -translate-x-1/2 md:translate-x-0">Settings</span>
                    </a>
                    <!-- Dark mode toggle -->
                    <button type="button"
                            class="nav-icon tooltip rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200"
                            @click="darkMode = !darkMode; localStorage.setItem('dark', darkMode.toString())">
                        <svg x-show="!darkMode" class="w-6 h-6 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="darkMode" class="w-6 h-6 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>
                        </svg>
                        <span class="tooltip-text md:left-14 md:top-auto md:-translate-y-1/2 
                                   bottom-14 left-1/2 -translate-x-1/2 md:translate-x-0" x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
                    </button>
                </nav>
            </aside>

            <!-- Main Content Area -->
            <div class="md:ml-28 flex-1 min-h-screen p-8 main-content">
                <!-- Top Header -->
                <header class="flex items-center justify-between mb-8">
                    <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">
                        Good {{ now()->format('A') }}, {{ auth()->user()->name }}!
                    </h1>

                    <div class="flex items-center space-x-4">
                        <!-- User Profile -->
                        <div class="flex items-center" x-data="{ userMenu: false }">
                            <button @click="userMenu = !userMenu" class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-coral-100 dark:bg-coral-800 flex items-center justify-center">
                                    <span class="text-coral-600 dark:text-coral-200 font-medium">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </span>
                                </div>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="userMenu" 
                                 @click.away="userMenu = false"
                                 class="absolute right-8 mt-16 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg py-2 z-50">
                                <div class="px-4 py-2 border-b dark:border-gray-700">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Centered Content Container -->
                <div class="p-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer - Moved outside main content area -->
            <footer class="fixed bottom-0 left-0 right-0 py-4 text-center text-sm text-gray-500 dark:text-gray-400 bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm z-10">
                &copy; {{ date('Y') }} ELsa canteek. All rights reserved.
            </footer>
        </div>
        @livewire('notifications')
        @filamentScripts
        @stack('scripts')
    </body>
</html>