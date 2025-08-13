<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
@auth
<script>
    window.location.href = "{{ route('dashboard') }}";
</script>
@endauth

<head>
    <meta charset="utf-8">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <!-- Add AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @filamentStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Initial dark mode check
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>

<body class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <!-- Navigasi -->
    <nav class="fixed w-full z-50 transition-all duration-300" id="navbar">
        <div class="w-full backdrop-blur-md bg-blue-600/90 transition-all duration-300 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo_smansa.jpg') }}" alt="Logo" class="h-8 w-8">
                    <div class="text-white text-2xl font-bold"> SMA NEGERI 1 PERCUT SEI TUAN </div>
                </div>
                <!-- Tombol menu mobile -->
                <div class="md:hidden">
                    <button type="button" class="text-white" id="mobile-menu-button">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
                <!-- Menu desktop -->
                <div class="hidden md:flex space-x-8">
                    <a href="/#about" class="text-white hover:text-blue-200 transition-colors">Tentang</a>
                    <a href="/#activities" class="text-white hover:text-blue-200 transition-colors">Kegiatan</a>
                </div>
            </div>
        </div>
        <!-- Menu mobile -->
        <div class="hidden md:hidden" id="mobile-menu">
            <div class="bg-blue-600/95 backdrop-blur-md px-4 pt-2 pb-3 space-y-1">
                <a href="/#about" class="block text-white py-2">Tentang</a>
                <a href="/#activities" class="block text-white py-2">Kegiatan</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8" data-aos="fade-up">
                <div>
                    <h3 class="text-xl font-bold mb-4">EXTRAKURIKULER <br> SMAN 1 PERCUT SEI TUAN</h3>
                    <p class="text-gray-400">Mengembangkan bakat, membangun masa depan.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/#about" class="hover:text-white">Tentang Kami</a></li>
                        <li><a href="/#activities" class="hover:text-white">Kegiatan</a></li>
                        <li><a href="/#schedule" class="hover:text-white">Jadwal</a></li>
                        <li><a href="/#contact" class="hover:text-white">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Kegiatan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/#activities" class="hover:text-white">Organisasi</a></li>
                        <li><a href="/#activities" class="hover:text-white">Olahraga</a></li>
                        <li><a href="/#activities" class="hover:text-white">Seni & Budaya</a></li>
                        <li><a href="/#activities" class="hover:text-white">Klub Akademik</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Hubungi Kami</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Jl. Irian Barat Desa Sampali No.37, <br> Kec. Percut Sei Tuan, Kab. Deli Serdang, Sumatera Utara 20371</li>
                        <!-- <li>Kec. Percut Sei Tuan, Kab. Deli Serdang, Sumatera Utara 20371</li> -->
                        <li>Telepon: (061) 6618073 </li>
                        <li>Email: info@extraschool.com</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Add AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });

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
    </script>
    @livewire('notifications')
    @filamentScripts
</body>

</html>