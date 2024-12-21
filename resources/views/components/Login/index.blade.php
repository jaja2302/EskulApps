<x-layouts.app>
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-600 to-indigo-700 min-h-screen">
        <!-- Navigation -->
        <nav class="fixed w-full z-50 transition-all duration-300" id="navbar">
            <div class="w-full bg-blue-600 transition-all duration-300">
                <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                    <div class="text-white text-2xl font-bold">ExtraSchool</div>
                    <div class="space-x-4">
                        <a href="#" class="text-white hover:text-blue-200">About</a>
                        <a href="#" class="text-white hover:text-blue-200">Activities</a>
                        <a href="#" class="text-white hover:text-blue-200">Schedule</a>
                        <a href="/login" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50">Login</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Content -->
        <div class="max-w-7xl mx-auto px-4 min-h-screen flex items-center pt-16">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <!-- Left Side -->
                <div class="text-white" data-aos="fade-right">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6">Discover Your Passion</h1>
                    <p class="text-xl mb-8">Join our exciting extracurricular activities and develop your talents.</p>
                    <div class="space-x-4">
                        <a href="#activities" 
                           class="bg-white text-blue-600 px-6 py-3 rounded-lg hover:bg-blue-50 font-medium inline-block hover:-translate-y-1 transition-transform">
                            Explore Activities
                        </a>
                        <a href="#register" 
                           class="border-2 border-white text-white px-6 py-3 rounded-lg hover:bg-white/10 font-medium inline-block hover:-translate-y-1 transition-transform">
                            Register Now
                        </a>
                    </div>
                </div>
                
                <!-- Right Side -->
                <div class="hidden md:block" data-aos="fade-left">
                    <img src="{{ asset('images/students.svg') }}" alt="Students" class="w-full">
                </div>
            </div>
        </div>
    </div>

    <!-- Activities Section -->
    <div id="activities" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12" data-aos="fade-up">Our Activities</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Sports -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-300"
                     data-aos="fade-right"
                     data-aos-delay="100">
                    <div class="h-48 bg-blue-500 relative overflow-hidden">
                        <div class="absolute inset-0 bg-blue-600 transform -skew-y-6 scale-125"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Sports</h3>
                        <p class="text-gray-600">Basketball, Soccer, Volleyball, and more. Stay active and build team spirit!</p>
                    </div>
                </div>

                <!-- Arts -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-300"
                     data-aos="fade-up"
                     data-aos-delay="200">
                    <div class="h-48 bg-purple-500 relative overflow-hidden">
                        <div class="absolute inset-0 bg-purple-600 transform -skew-y-6 scale-125"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Arts & Culture</h3>
                        <p class="text-gray-600">Music, Dance, Theater, and Visual Arts. Express yourself creatively!</p>
                    </div>
                </div>

                <!-- Academic -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-300"
                     data-aos="fade-left"
                     data-aos-delay="300">
                    <div class="h-48 bg-green-500 relative overflow-hidden">
                        <div class="absolute inset-0 bg-green-600 transform -skew-y-6 scale-125"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Academic Clubs</h3>
                        <p class="text-gray-600">Science Club, Debate Team, Robotics, and more. Expand your knowledge!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section with Counter Animation -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                <div data-aos="zoom-in" data-aos-delay="100">
                    <div class="text-4xl font-bold text-blue-600 mb-2" data-counter="20">0</div>
                    <div class="text-gray-600">Activities</div>
                </div>
                <div data-aos="zoom-in" data-aos-delay="200">
                    <div class="text-4xl font-bold text-blue-600 mb-2" data-counter="500">0</div>
                    <div class="text-gray-600">Active Students</div>
                </div>
                <div data-aos="zoom-in" data-aos-delay="300">
                    <div class="text-4xl font-bold text-blue-600 mb-2" data-counter="50">0</div>
                    <div class="text-gray-600">Expert Mentors</div>
                </div>
                <div data-aos="zoom-in" data-aos-delay="400">
                    <div class="text-4xl font-bold text-blue-600 mb-2" data-counter="100">0</div>
                    <div class="text-gray-600">Awards Won</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer with fade-up animation -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8" data-aos="fade-up">
                <div>
                    <h3 class="text-xl font-bold mb-4">ExtraSchool</h3>
                    <p class="text-gray-400">Developing talents, building futures.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">About Us</a></li>
                        <li><a href="#" class="hover:text-white">Activities</a></li>
                        <li><a href="#" class="hover:text-white">Schedule</a></li>
                        <li><a href="#" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Activities</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Sports</a></li>
                        <li><a href="#" class="hover:text-white">Arts & Culture</a></li>
                        <li><a href="#" class="hover:text-white">Academic Clubs</a></li>
                        <li><a href="#" class="hover:text-white">Special Events</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Contact Us</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>123 School Street</li>
                        <li>City, State 12345</li>
                        <li>Phone: (123) 456-7890</li>
                        <li>Email: info@extraschool.com</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Add this script at the bottom of your page -->
    <script>
        // Navbar color change on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar').querySelector('div');
            if (window.scrollY > 50) {
                navbar.classList.add('bg-blue-600');
            } else {
                navbar.classList.remove('bg-blue-600');
            }
        });

        // Number counter animation
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
    </script>
</x-layouts.app> 