<div>
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center space-x-4">
                <h1 class="text-2xl font-bold">Atur user dan role di sini</h1>
                <a href="{{ asset('templates/user-import-template.xlsx') }}" 
                   class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm">
                    Download Template Excel
                </a>
            </div>
            <button 
                @click="$dispatch('open-modal', 'role-permissions')"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
            >
                Panduan Role & Permissions
            </button>
        </div>

        {{ $this->table }}

        <!-- Modal -->
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
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black bg-opacity-50"></div>

            <!-- Modal content -->
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative bg-white rounded-xl max-w-4xl w-full max-h-[80vh] overflow-y-auto p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Daftar Role dan Permissions</h2>
                        <button 
                            @click="show = false"
                            class="text-gray-500 hover:text-gray-700"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($rolePermissions as $role => $permissions)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-bold text-lg mb-2 capitalize text-blue-600">
                                    {{ str_replace('_', ' ', $role) }}
                                </h3>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($permissions as $permission)
                                        <li class="text-sm text-gray-600">
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
</div>