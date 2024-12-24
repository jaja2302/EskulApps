<x-layouts.guest>
    <!-- Bagian Hero -->
    <div class="relative bg-gradient-to-r from-blue-600 to-indigo-700 min-h-screen">
        <!-- Navigasi -->
        <nav class="fixed w-full z-50 transition-all duration-300" id="navbar">
            <div class="w-full backdrop-blur-md bg-blue-600/90 transition-all duration-300 shadow-lg">
                <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <img src="{{ asset('images/sekolah.webp') }}" alt="Logo" class="h-8 w-8">
                        <div class="text-white text-2xl font-bold">ExtraSchool</div>
                    </div>
                    <!-- Tombol menu mobile -->
                    <div class="md:hidden">
                        <button type="button" class="text-white" id="mobile-menu-button">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                    <!-- Menu desktop -->
                    <div class="hidden md:flex space-x-8">
                        <a href="#about" class="text-white hover:text-blue-200 transition-colors">Tentang</a>
                        <a href="#activities" class="text-white hover:text-blue-200 transition-colors">Kegiatan</a>
                        <a href="#schedule" class="text-white hover:text-blue-200 transition-colors">Jadwal</a>

                    </div>
                </div>
            </div>
            <!-- Menu mobile -->
            <div class="hidden md:hidden" id="mobile-menu">
                <div class="bg-blue-600/95 backdrop-blur-md px-4 pt-2 pb-3 space-y-1">
                    <a href="#about" class="block text-white py-2">Tentang</a>
                    <a href="#activities" class="block text-white py-2">Kegiatan</a>
                    <a href="#schedule" class="block text-white py-2">Jadwal</a>
                </div>
            </div>
        </nav>

        <!-- Konten Hero -->
        <div class="max-w-7xl mx-auto px-4 min-h-screen flex items-center pt-16">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <!-- Sisi Kiri -->
                <div class="text-white" data-aos="fade-right">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6">Temukan Passionmu</h1>
                    <p class="text-xl mb-8 text-blue-100">Bergabunglah dengan kegiatan ekstrakurikuler kami dan kembangkan bakatmu.</p>
                    <div class="space-x-4">
                        <a href="#activities" 
                           class="bg-white text-blue-600 px-6 py-3 rounded-lg hover:bg-blue-50 font-medium inline-block hover:-translate-y-1 transition-all duration-300 shadow-lg hover:shadow-xl">
                            Jelajahi Kegiatan
                        </a>
                        <a href="/login" 
                           class="border-2 border-white text-white px-6 py-3 rounded-lg hover:bg-white hover:text-blue-600 font-medium inline-block hover:-translate-y-1 transition-all duration-300">
                            Masuk
                        </a>
                    </div>
                </div>
                
                <!-- Sisi Kanan -->
                <div class="hidden md:block relative" data-aos="fade-left">
                    <div class="slideshow-container">
                        <img src="{{ asset('images/people1.png') }}" alt="Siswa 1" class="slide w-full drop-shadow-2xl transform hover:scale-105 transition-transform duration-300">
                        <img src="{{ asset('images/people2.png') }}" alt="Siswa 2" class="slide w-full drop-shadow-2xl transform hover:scale-105 transition-transform duration-300 hidden">
                        <img src="{{ asset('images/people3.png') }}" alt="Siswa 3" class="slide w-full drop-shadow-2xl transform hover:scale-105 transition-transform duration-300 hidden">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Kegiatan -->
    <div id="activities" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12" data-aos="fade-up">Kegiatan Kami</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Olahraga -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-300"
                     data-aos="fade-right"
                     data-aos-delay="100">
                    <div class="h-48 bg-blue-500 relative overflow-hidden">
                        <div class="absolute inset-0 bg-blue-600 transform -skew-y-6 scale-125"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Olahraga</h3>
                        <p class="text-gray-600">Basket, Sepak Bola, Voli, dan lainnya. Tetap aktif dan bangun semangat tim!</p>
                    </div>
                </div>

                <!-- Seni -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-300"
                     data-aos="fade-up"
                     data-aos-delay="200">
                    <div class="h-48 bg-purple-500 relative overflow-hidden">
                        <div class="absolute inset-0 bg-purple-600 transform -skew-y-6 scale-125"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Seni & Budaya</h3>
                        <p class="text-gray-600">Musik, Tari, Teater, dan Seni Rupa. Ekspresikan dirimu secara kreatif!</p>
                    </div>
                </div>

                <!-- Akademik -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-300"
                     data-aos="fade-left"
                     data-aos-delay="300">
                    <div class="h-48 bg-green-500 relative overflow-hidden">
                        <div class="absolute inset-0 bg-green-600 transform -skew-y-6 scale-125"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Klub Akademik</h3>
                        <p class="text-gray-600">Klub Sains, Tim Debat, Robotika, dan lainnya. Perluas pengetahuanmu!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Statistik -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                <div data-aos="zoom-in" data-aos-delay="100">
                    <div class="text-4xl font-bold text-blue-600 mb-2" data-counter="20">0</div>
                    <div class="text-gray-600">Kegiatan</div>
                </div>
                <div data-aos="zoom-in" data-aos-delay="200">
                    <div class="text-4xl font-bold text-blue-600 mb-2" data-counter="500">0</div>
                    <div class="text-gray-600">Siswa Aktif</div>
                </div>
                <div data-aos="zoom-in" data-aos-delay="300">
                    <div class="text-4xl font-bold text-blue-600 mb-2" data-counter="50">0</div>
                    <div class="text-gray-600">Mentor Ahli</div>
                </div>
                <div data-aos="zoom-in" data-aos-delay="400">
                    <div class="text-4xl font-bold text-blue-600 mb-2" data-counter="100">0</div>
                    <div class="text-gray-600">Penghargaan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Maps -->
    <div class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12" data-aos="fade-up">Lokasi Kami</h2>
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

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8" data-aos="fade-up">
                <div>
                    <h3 class="text-xl font-bold mb-4">ExtraSchool</h3>
                    <p class="text-gray-400">Mengembangkan bakat, membangun masa depan.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white">Kegiatan</a></li>
                        <li><a href="#" class="hover:text-white">Jadwal</a></li>
                        <li><a href="#" class="hover:text-white">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Kegiatan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Olahraga</a></li>
                        <li><a href="#" class="hover:text-white">Seni & Budaya</a></li>
                        <li><a href="#" class="hover:text-white">Klub Akademik</a></li>
                        <li><a href="#" class="hover:text-white">Acara Khusus</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Hubungi Kami</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Jl. Sekolah No. 123</li>
                        <li>Kota, Provinsi 12345</li>
                        <li>Telepon: (123) 456-7890</li>
                        <li>Email: info@extraschool.com</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Script -->
    <script>
        // Fungsi navbar
        const navbar = document.getElementById('navbar').querySelector('div');
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        // Toggle menu mobile
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Perubahan warna navbar saat scroll
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('bg-blue-600/95');
                navbar.classList.add('shadow-lg');
            } else {
                navbar.classList.remove('bg-blue-600/95');
                navbar.classList.remove('shadow-lg');
            }
        });

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