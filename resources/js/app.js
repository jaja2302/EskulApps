

// Dark mode functionality
function initDarkMode() {
    const themeToggleBtn = document.getElementById('theme-toggle');
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            
            localStorage.theme = document.documentElement.classList.contains('dark') 
                ? 'dark' 
                : 'light';
        });
    }
}

// Navbar scroll functionality
function initNavbar() {
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            const navbarInner = navbar.querySelector('div');
            if (window.scrollY > 50) {
                navbarInner.classList.add('bg-blue-600', 'dark:bg-blue-800');
            } else {
                navbarInner.classList.remove('bg-blue-600', 'dark:bg-blue-800');
            }
        });
    }
}

// Counter animation functionality
function initCounters() {
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
}

// Initialize AOS
function initAOS() {
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 1000,
            once: false,
            mirror: true
        });
    }
}

// Initialize all functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initDarkMode();
    initNavbar();
    initCounters();
    initAOS();
});
