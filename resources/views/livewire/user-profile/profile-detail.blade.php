<div class="space-y-6">
    <header class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-sm rounded-lg">
        <div class="p-4 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Detail Profil
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Lengkapi informasi profil Anda untuk memudahkan pengelolaan data.
            </p>
        </div>
    </header>

    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex justify-end gap-x-4">
            <x-filament::button
                type="submit"
                color="primary"
            >
                Simpan Perubahan
            </x-filament::button>
        </div>
    </form>
</div>
