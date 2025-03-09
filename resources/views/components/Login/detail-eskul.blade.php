<x-layouts.guest>
    <div class="min-h-screen bg-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="h-96 bg-blue-500 relative overflow-hidden">
                    <img src="{{ Storage::url($eskul->image) }}" 
                         alt="{{ $eskul->name }}" 
                         class="w-full h-full object-cover">
                </div>
                <div class="p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $eskul->name }}</h1>
                    <div class="prose max-w-none">
                        <p class="text-gray-600">{{ $eskul->description }}</p>
                    </div>
                    
                    <div class="mt-8 border-t pt-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Informasi Ekstrakurikuler</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="font-semibold text-gray-700">Jadwal Latihan</h3>
                                <p class="text-gray-600">{{ $eskul->schedule ?? 'Jadwal akan diumumkan' }}</p>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-700">Pembina</h3>
                                <p class="text-gray-600">{{ $eskul->coach ?? 'Akan ditentukan' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <a href="/" 
                           class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-300">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest> 