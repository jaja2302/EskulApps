<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="application-name" content="{{ config('app.name') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <style>[x-cloak] { display: none !important; }</style>
        @filamentStyles
        @vite('resources/css/app.css')
        <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
        <script>
            // Initial dark mode check (keep this inline to avoid FOUC)
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
        {{ $slot }}

        @filamentScripts
        <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
        @vite('resources/js/app.js')
    </body>
</html>