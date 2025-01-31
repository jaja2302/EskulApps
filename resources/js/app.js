import lottie from 'lottie-web';
window.lottie = lottie;

import $ from 'jquery';
window.$ = $;
window.jQuery = $;

// Add debug logging
console.log('jQuery version:', $.fn.jquery);

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

// Add debug logging function
function logDebug(message) {
    console.log(`[Loading Screen Debug] ${message}`);
}

// Modify the loading screen handling
function showLoadingScreen() {
    const loadingScreen = document.getElementById('loading-screen');
    if (loadingScreen) {
        loadingScreen.classList.remove('hidden');
        loadingScreen.style.opacity = '1';
        logDebug('Loading screen shown');
    }
}

function hideLoadingScreen() {
    const loadingScreen = document.getElementById('loading-screen');
    if (loadingScreen) {
        loadingScreen.style.opacity = '0';
        setTimeout(() => {
            loadingScreen.classList.add('hidden');
            logDebug('Loading screen hidden');
        }, 300); // Match this with CSS transition duration
    }
}

// Update the event listeners
document.addEventListener('alpine:navigating', () => {
    logDebug('Alpine navigation started');
    showLoadingScreen();
});

document.addEventListener('alpine:navigated', () => {
    logDebug('Alpine navigation completed');
    hideLoadingScreen();
});

document.addEventListener('livewire:navigating', () => {
    logDebug('Livewire navigation started');
    showLoadingScreen();
});

document.addEventListener('livewire:navigated', () => {
    logDebug('Livewire navigation completed');
    hideLoadingScreen();
});

// Update Lottie initialization
$(function(){
    logDebug('Initializing Lottie animations');
    
    $('.lottie-animation').each(function() {
        var animationPath = $(this).data('animation-path');
        logDebug(`Loading animation from path: ${animationPath}`);
        
        try {
            const animation = lottie.loadAnimation({
                container: this,
                renderer: 'svg',
                loop: true,
                autoplay: true,
                path: animationPath,
                rendererSettings: {
                    progressiveLoad: true,
                    preserveAspectRatio: 'xMidYMid meet'
                }
            });

            // Add loading success callback
            animation.addEventListener('DOMLoaded', () => {
                logDebug('Lottie animation DOM loaded and visible');
            });

            logDebug('Lottie animation loaded successfully');
        } catch (error) {
            console.error('Error loading Lottie animation:', error);
        }
    });
});

// Check if loading screen exists on page load
document.addEventListener('DOMContentLoaded', () => {
    const loadingScreen = document.getElementById('loading-screen');
    if (loadingScreen) {
        logDebug('Loading screen element found on page');
    } else {
        console.error('Loading screen element not found on page load!');
    }
    
    // Verify Lottie JSON file exists
    fetch('/images/lottie_loading.json')
        .then(response => {
            if (!response.ok) {
                throw new Error('Lottie JSON file not found');
            }
            logDebug('Lottie JSON file exists');
            return response.json();
        })
        .then(data => {
            logDebug('Lottie JSON file is valid');
        })
        .catch(error => {
            console.error('Error checking Lottie file:', error);
        });
});

// For regular link navigation
document.addEventListener('beforeunload', () => {
    logDebug('Page unload started');
    const loadingScreen = document.getElementById('loading-screen');
    if (loadingScreen) {
        loadingScreen.classList.remove('hidden');
        logDebug('Loading screen shown before unload');
    }
});

