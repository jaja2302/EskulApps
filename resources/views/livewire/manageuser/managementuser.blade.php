<div class="space-y-4">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            Atur user dan role di sini
        </h1>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <a href="{{ asset('templates/user-import-template.xlsx') }}" 
               class="flex items-center justify-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download Template
            </a>
            
            <button 
                @click="$dispatch('open-modal', 'role-permissions')"
                class="flex items-center justify-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Panduan Role & Permissions
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
       
        <!-- Filament Table -->
        <div class="overflow-x-auto">
            {{ $this->table }}
        </div>
    </div>

    <!-- Role Permissions Modal -->
    <div
        x-data="{ show: false }"
        x-show="show"
        x-on:open-modal.window="if ($event.detail === 'role-permissions') show = true"
        x-on:close-modal.window="show = false"
        x-on:keydown.escape.window="show = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <!-- Modal Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50"></div>

        <!-- Modal Content -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-xl max-w-4xl w-full max-h-[80vh] overflow-y-auto p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">Daftar Role dan Permissions</h2>
                    <button 
                        @click="show = false"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($rolePermissions as $role => $permissions)
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="font-bold text-lg mb-2 capitalize text-blue-600 dark:text-blue-400">
                                {{ str_replace('_', ' ', $role) }}
                            </h3>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($permissions as $permission)
                                    <li class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ str_replace('_', ' ', $permission) }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>