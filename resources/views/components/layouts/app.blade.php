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
        <div class="min-h-screen">
            <!-- Sidebar Component -->
            <x-sidebar />

            <!-- Main Content Area -->
            <div class="min-h-screen">
                <!-- Top Header - Full Width -->
                <div class="sticky top-0 z-30 w-full bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="h-16 bg-gradient-to-r from-coral-500/10 to-coral-600/10 dark:from-coral-500/5 dark:to-coral-600/5">
                        <div class="h-full px-4 sm:px-6 lg:px-8">
                            <div class="flex items-center justify-between h-full">
                                <!-- Left side -->
                                <div class="flex items-center space-x-4">
                                    <h1 class="text-xl font-semibold text-gray-800 dark:text-white">
                                        Good {{ now()->format('A') }}, {{ auth()->user()->name }}!
                                    </h1>
                                </div>

                                <!-- Right side -->
                                <div class="flex items-center space-x-4">
                                    <!-- Dark mode toggle -->
                                    <button type="button"
                                            class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg custom-transition"
                                            @click="darkMode = !darkMode; localStorage.setItem('dark', darkMode.toString())">
                                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                        </svg>
                                        <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>
                                        </svg>
                                    </button>

                                    <!-- User Profile -->
                                    <div class="relative" x-data="{ userMenu: false }">
                                        <button @click="userMenu = !userMenu" 
                                                class="flex items-center space-x-3 focus:outline-none custom-transition">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-9 h-9 rounded-full bg-coral-100 dark:bg-coral-800 flex items-center justify-center">
                                                    <span class="text-coral-600 dark:text-coral-200 font-medium">
                                                        {{ substr(auth()->user()->name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div class="hidden md:block text-left">
                                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                                        {{ auth()->user()->name }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ auth()->user()->email }}
                                                    </p>
                                                </div>
                                            </div>
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div x-show="userMenu" 
                                             @click.away="userMenu = false"
                                             x-transition:enter="dropdown-enter"
                                             x-transition:enter-start="dropdown-enter-from"
                                             x-transition:enter-end="dropdown-enter-to"
                                             x-transition:leave="dropdown-leave"
                                             x-transition:leave-start="dropdown-leave-from"
                                             x-transition:leave-end="dropdown-leave-to"
                                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg py-2 z-50">
                                            <div class="px-4 py-2 border-b dark:border-gray-700 md:hidden">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                                            </div>
                                            
                                            <!-- User Detail Button -->
                                            <a href="{{ route('profile.show') }}" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 custom-transition">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    User Detail
                                                </div>
                                            </a>
                                            
                                            <!-- Logout Button -->
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" 
                                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 custom-transition">
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                        </svg>
                                                        Logout
                                                    </div>
                                                </button>
                                            </form>
                                        </div>
                                        <!-- user detail  -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Container with proper padding -->
                <div class="md:ml-28 p-6 pb-24">
                    <div class="max-w-7xl mx-auto">
                        {{ $slot }}
                    </div>
                </div>

                <!-- Footer -->
                <footer class="fixed bottom-0 left-0 right-0 w-full bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border-t border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="bg-gradient-to-r from-coral-500/10 to-coral-600/10 dark:from-coral-500/5 dark:to-coral-600/5">
                        <div class="md:ml-28">
                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                                <div class="text-center text-sm text-gray-700 dark:text-gray-300">
                                    &copy; {{ date('Y') }} ELsa canteek. All rights reserved.
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        @livewire('notifications')
        @filamentScripts
        @stack('scripts')
    </body>
</html>