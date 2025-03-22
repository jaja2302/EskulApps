<x-layouts.guest>
    <!-- Bagian Hero dengan tampilan lebih modern -->
    <div class="relative bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 min-h-screen overflow-hidden">
        <!-- Elemen dekoratif -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-400 opacity-20 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 -right-24 w-80 h-80 bg-indigo-500 opacity-20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-12 left-1/4 w-72 h-72 bg-purple-500 opacity-20 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Konten Hero -->
        <div class="max-w-7xl mx-auto px-4 min-h-screen flex items-center pt-16 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <!-- Sisi Kiri -->
                <div class="text-white" data-aos="fade-right">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">Temukan <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-purple-200">Passionmu</span></h1>
                    <p class="text-xl mb-8 text-blue-100 opacity-90">Bergabunglah dengan kegiatan ekstrakurikuler kami dan kembangkan bakatmu.</p>
                    <div class="space-x-4">
                        <a href="#activities" 
                           class="bg-white text-blue-600 px-6 py-3 rounded-full hover:bg-blue-50 font-medium inline-block hover:-translate-y-1 transition-all duration-300 shadow-lg hover:shadow-xl">
                            Jelajahi Kegiatan
                        </a>
                        <a href="/login" 
                           class="border-2 border-white text-white px-6 py-3 rounded-full hover:bg-white hover:text-blue-600 font-medium inline-block hover:-translate-y-1 transition-all duration-300">
                            Masuk
                        </a>
                    </div>
                </div>
                
                <!-- Sisi Kanan -->
                <div class="hidden md:block relative" data-aos="fade-left">
                    <div class="slideshow-container relative rounded-2xl shadow-2xl overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-900/30 to-transparent z-10"></div>
                        <img src="{{ asset('images/people1.png') }}" alt="Siswa 1" class="slide w-full drop-shadow-2xl transform hover:scale-105 transition-transform duration-500">
                        <img src="{{ asset('images/people2.png') }}" alt="Siswa 2" class="slide w-full drop-shadow-2xl transform hover:scale-105 transition-transform duration-500 hidden">
                        <img src="{{ asset('images/people3.png') }}" alt="Siswa 3" class="slide w-full drop-shadow-2xl transform hover:scale-105 transition-transform duration-500 hidden">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Kegiatan -->
    <div id="activities" class="py-20 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-4 dark:text-white" data-aos="fade-up">Kegiatan Kami</h2>
            <p class="text-gray-600 dark:text-gray-300 text-center max-w-2xl mx-auto mb-12" data-aos="fade-up" data-aos-delay="100">
                Beragam kegiatan ekstrakurikuler untuk mengembangkan bakat dan minat siswa
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($eskuls as $eskul)
                <!-- Kegiatan Card -->
                <div class="bg-white dark:bg-gray-700 rounded-xl shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-300 group"
                     data-aos="fade-up"
                     data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="h-48 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent z-10"></div>
                        <img src="{{ Storage::url($eskul->image) }}" alt="{{ $eskul->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute bottom-0 left-0 p-4 z-20">
                            <span class="bg-blue-600 text-white text-xs px-3 py-1 rounded-full uppercase tracking-wider">{{ $eskul->category ?? 'Ekstrakurikuler' }}</span>
                        </div>
                    </div>
                    <div class="p-6 dark:text-white">
                        <h3 class="font-bold text-xl mb-2">{{ $eskul->name }}</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">{{ $eskul->description }}</p>
                        <a href="{{ route('guest.eskul.detail', $eskul->id) }}" 
                           target="_blank"
                           class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-300">
                            Lihat Detail
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Bagian Statistik -->
    <div class="py-20 bg-white dark:bg-gray-900 relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="h-full w-full bg-[radial-gradient(circle_at_center,#4F46E5_0,transparent_70%)]"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <h2 class="text-3xl font-bold text-center mb-12 dark:text-white" data-aos="fade-up">Pencapaian Kami</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                <div data-aos="zoom-in" data-aos-delay="100" class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                    <div class="text-5xl font-bold text-blue-600 mb-2 counter" data-counter="{{ $stats['kegiatan'] }}">0</div>
                    <div class="text-gray-600 dark:text-gray-300">Kegiatan</div>
                </div>
                <div data-aos="zoom-in" data-aos-delay="200" class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                    <div class="text-5xl font-bold text-blue-600 mb-2 counter" data-counter="{{ $stats['siswaAktif'] }}">0</div>
                    <div class="text-gray-600 dark:text-gray-300">Siswa Aktif</div>
                </div>
                <div data-aos="zoom-in" data-aos-delay="300" class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                    <div class="text-5xl font-bold text-blue-600 mb-2 counter" data-counter="{{ $stats['mentorAhli'] }}">0</div>
                    <div class="text-gray-600 dark:text-gray-300">Mentor Ahli</div>
                </div>
                <div data-aos="zoom-in" data-aos-delay="400" class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                    <div class="text-5xl font-bold text-blue-600 mb-2 counter" data-counter="{{ $stats['penghargaan'] }}">0</div>
                    <div class="text-gray-600 dark:text-gray-300">Penghargaan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Maps -->
    <div class="py-20 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-4 dark:text-white" data-aos="fade-up">Lokasi Kami</h2>
            <p class="text-gray-600 dark:text-gray-300 text-center max-w-2xl mx-auto mb-12" data-aos="fade-up" data-aos-delay="100">
                Kunjungi kami untuk melihat langsung fasilitas dan kegiatan ekstrakurikuler
            </p>
            <div class="w-full rounded-xl overflow-hidden shadow-lg" data-aos="zoom-in">
                <div class="aspect-w-16 aspect-h-9">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d703.9037492299082!2d98.69169174311523!3d3.614700952872009!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3031318f46af2455%3A0xcdec9dff370a9b89!2sSekolah%20Dasar%20Negeri%20No.%20064966!5e0!3m2!1sid!2sid!4v1735029184766!5m2!1sid!2sid"
                        width="100%" 
                        height="450" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        class="w-full h-full">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        // Animasi penghitung angka
        const startCounters = (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = entry.target;
                    const countTo = parseInt(target.getAttribute('data-counter'));
                    let count = 0;
                    const updateCount = () => {
                        const increment = countTo / 50;
                        if (count < countTo) {
                            count += increment;
                            target.innerText = Math.ceil(count) + '+';
                            requestAnimationFrame(updateCount);
                        } else {
                            target.innerText = countTo + '+';
                        }
                    };
                    updateCount();
                    observer.unobserve(target);
                }
            });
        };

        const counterObserver = new IntersectionObserver(startCounters, {
            threshold: 0.5
        });

        document.querySelectorAll('[data-counter]').forEach(counter => {
            counterObserver.observe(counter);
        });

        // Slideshow
        let slideIndex = 0;
        const slides = document.getElementsByClassName("slide");

        function showSlides() {
            for (let i = 0; i < slides.length; i++) {
                slides[i].classList.add('hidden');
            }
            slideIndex++;
            if (slideIndex > slides.length) {
                slideIndex = 1;
            }
            slides[slideIndex - 1].classList.remove('hidden');
            setTimeout(showSlides, 3000); // Ganti gambar setiap 3 detik
        }

        // Jalankan slideshow saat halaman dimuat
        if (slides.length > 0) {
            showSlides();
        }
    </script>
</x-layouts.guest> 