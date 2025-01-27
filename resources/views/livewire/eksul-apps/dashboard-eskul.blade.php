<div>
    <!-- Card Header -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                   @if(auth()->user()->hasRole('siswa'))
                        List Eskul Anda Ikuti
                   @else
                        List Eskul
                   @endif
                </h1>
            </div>
        </div>
    </div>

    {{ $this->table }}
</div>
