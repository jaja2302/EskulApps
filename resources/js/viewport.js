import viewportUnitsBuggyfill from 'viewport-units-buggyfill';

// Initialize viewport units buggyfill
viewportUnitsBuggyfill.init({
    force: true,
    refreshDebounceWait: 250
});

// Function to handle viewport adjustments
function setViewportHeight() {
    // First we get the viewport height and multiply it by 1% to get a value for a vh unit
    let vh = window.innerHeight * 0.01;
    // Then we set the value in the --vh custom property to the root of the document
    document.documentElement.style.setProperty('--vh', `${vh}px`);

    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (window.innerWidth < 768) { // mobile view
        const bottomSafeArea = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--vh')) * 100 - document.documentElement.clientHeight;
        
        if (bottomSafeArea > 0) {
            sidebar.classList.add('has-safe-area');
            mainContent.style.paddingBottom = `calc(60px + ${bottomSafeArea}px)`;
        } else {
            sidebar.classList.remove('has-safe-area');
            mainContent.style.paddingBottom = '60px';
        }
    }
}

// Add event listeners
window.addEventListener('resize', setViewportHeight);
window.addEventListener('orientationchange', () => {
    setTimeout(setViewportHeight, 100);
});

// Initial call
setViewportHeight(); 