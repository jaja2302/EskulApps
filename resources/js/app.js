import './bootstrap';

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
