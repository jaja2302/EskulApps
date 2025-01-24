<div>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">Detail Eskul: {{ $eskul->name }}</h1>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="font-semibold">Deskripsi:</h3>
                    <p>{{ $eskul->description }}</p>
                </div>
                
                <div>
                    <h3 class="font-semibold">Pelatih:</h3>
                    <p>{{ $eskul->pelatih->name }}</p>
                    
                    <h3 class="font-semibold mt-4">Wakil Pelatih:</h3>
                    <p>{{ $eskul->wakil_pelatih->name }}</p>
                </div>
            </div>
            
            @if($eskul->image)
                <div class="mt-4">
                    <h3 class="font-semibold">Banner Eskul:</h3>
                    <img src="{{ Storage::url($eskul->image) }}" alt="Banner Eskul" class="mt-2 max-w-md rounded-lg">
                </div>
            @endif
        </div>
    </div>
</div>

