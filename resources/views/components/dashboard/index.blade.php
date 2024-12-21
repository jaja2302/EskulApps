<x-layouts.app>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                Logout
            </button>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Welcome to your Dashboard</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Sample Dashboard Cards -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-medium">Card 1</h3>
                <p class="text-gray-600">Sample content here</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-medium">Card 2</h3>
                <p class="text-gray-600">Sample content here</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-medium">Card 3</h3>
                <p class="text-gray-600">Sample content here</p>
            </div>
        </div>
    </div>
</x-layouts.app> 