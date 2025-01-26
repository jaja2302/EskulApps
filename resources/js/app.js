// Simple viewport handler
function handleViewport() {
    // Set viewport height
    const vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
}

// Listen for resize and orientation changes
window.addEventListener('resize', handleViewport);
window.addEventListener('orientationchange', () => {
    setTimeout(handleViewport, 100);
});

// Initial call
handleViewport();

document.addEventListener('DOMContentLoaded', function() {
    // Count nav items and set CSS variable
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        const navItems = sidebar.querySelectorAll('.nav-icon').length;
        document.documentElement.style.setProperty('--nav-items', navItems);
    }
});
