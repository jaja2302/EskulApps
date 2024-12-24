<x-layouts.app>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <h2 class="text-xl font-semibold mb-4">Welcome, {{ auth()->user()->name }}!</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @role('admin')
                <div class="bg-blue-50 dark:bg-blue-900/50 p-6 rounded-lg">
                    <h3 class="font-semibold text-blue-800 dark:text-blue-200">Total Users</h3>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-300">{{ \App\Models\User::count() }}</p>
                </div>
                @endrole

                <!-- Add more dashboard widgets based on user role -->
            </div>
        </div>
    </div>
</x-layouts.app> 