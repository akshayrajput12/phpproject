/**
 * Farmer's Friend - Animation Scripts
 * Enhanced animations and effects for the application
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all animations
    initTypewriterEffects();
    initScrollAnimations();
    initHoverEffects();
    initParallaxEffects();
    initGlassmorphismEffects();
});

/**
 * Typewriter effect for hero sections
 */
function initTypewriterEffects() {
    const typewriterElements = document.querySelectorAll('.typewriter');
    
    typewriterElements.forEach(element => {
        const text = element.getAttribute('data-text');
        const speed = parseInt(element.getAttribute('data-speed') || '50');
        
        if (text) {
            element.textContent = '';
            let i = 0;
            
            function typeWriter() {
                if (i < text.length) {
                    element.textContent += text.charAt(i);
                    i++;
                    setTimeout(typeWriter, speed);
                } else {
                    // Add blinking cursor at the end
                    element.classList.add('typewriter-done');
                    
                    // If there's a next element to show after typing
                    const nextElement = element.getAttribute('data-next-element');
                    if (nextElement) {
                        const nextEl = document.querySelector(nextElement);
                        if (nextEl) {
                            setTimeout(() => {
                                nextEl.classList.add('fade-in-up');
                                nextEl.style.opacity = '1';
                                nextEl.style.transform = 'translateY(0)';
                            }, 500);
                        }
                    }
                }
            }
            
            // Start the typewriter effect
            setTimeout(typeWriter, 500);
        }
    });
}

/**
 * Scroll-triggered animations
 */
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    
    // Initial check for elements in viewport
    checkElementsInViewport();
    
    // Check on scroll
    window.addEventListener('scroll', checkElementsInViewport);
    
    function checkElementsInViewport() {
        animatedElements.forEach(element => {
            if (isElementInViewport(element) && !element.classList.contains('animated')) {
                const delay = parseInt(element.getAttribute('data-delay') || '0');
                
                setTimeout(() => {
                    element.classList.add('animated');
                    
                    // Add specific animation class if specified
                    const animationClass = element.getAttribute('data-animation');
                    if (animationClass) {
                        element.classList.add(animationClass);
                    }
                }, delay);
            }
        });
    }
    
    function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 0.8 &&
            rect.bottom >= 0
        );
    }
}

/**
 * Enhanced hover effects
 */
function initHoverEffects() {
    // 3D tilt effect for cards
    const tiltElements = document.querySelectorAll('.tilt-effect');
    
    tiltElements.forEach(element => {
        element.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const deltaX = (x - centerX) / centerX;
            const deltaY = (y - centerY) / centerY;
            
            this.style.transform = `perspective(1000px) rotateX(${-deltaY * 5}deg) rotateY(${deltaX * 5}deg) scale3d(1.02, 1.02, 1.02)`;
        });
        
        element.addEventListener('mouseleave', function() {
            this.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale3d(1, 1, 1)';
        });
    });
    
    // Magnetic effect for buttons
    const magneticElements = document.querySelectorAll('.magnetic-effect');
    
    magneticElements.forEach(element => {
        element.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const deltaX = (x - centerX) / 8;
            const deltaY = (y - centerY) / 8;
            
            this.style.transform = `translate(${deltaX}px, ${deltaY}px)`;
        });
        
        element.addEventListener('mouseleave', function() {
            this.style.transform = 'translate(0, 0)';
        });
    });
}

/**
 * Parallax effects for background elements
 */
function initParallaxEffects() {
    const parallaxElements = document.querySelectorAll('.parallax');
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset;
        
        parallaxElements.forEach(element => {
            const speed = parseFloat(element.getAttribute('data-parallax-speed') || '0.5');
            const offset = scrollTop * speed;
            
            element.style.transform = `translateY(${offset}px)`;
        });
    });
}

/**
 * Enhanced glassmorphism effects
 */
function initGlassmorphismEffects() {
    const glassElements = document.querySelectorAll('.glass-enhanced');
    
    glassElements.forEach(element => {
        element.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            // Create a radial gradient that follows the cursor
            this.style.background = `radial-gradient(circle at ${x}px ${y}px, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1) 40%, rgba(255, 255, 255, 0.05) 60%)`;
        });
        
        element.addEventListener('mouseleave', function() {
            // Reset to default glass effect
            this.style.background = 'rgba(255, 255, 255, 0.15)';
        });
    });
}

/**
 * Weather animation effects based on current weather
 */
function initWeatherAnimations(weatherType) {
    const weatherContainer = document.querySelector('.weather-animation-container');
    if (!weatherContainer) return;
    
    // Clear previous animations
    weatherContainer.innerHTML = '';
    
    switch(weatherType.toLowerCase()) {
        case 'rain':
        case 'drizzle':
        case 'light rain':
        case 'moderate rain':
            createRaindrops(weatherContainer, 50);
            break;
        case 'snow':
        case 'light snow':
        case 'moderate snow':
            createSnowflakes(weatherContainer, 50);
            break;
        case 'clear':
        case 'sunny':
            createSunshine(weatherContainer);
            break;
        case 'clouds':
        case 'cloudy':
        case 'partly cloudy':
        case 'overcast':
            createClouds(weatherContainer, 5);
            break;
        case 'thunderstorm':
            createLightning(weatherContainer);
            createRaindrops(weatherContainer, 30);
            break;
    }
}

// Helper functions for weather animations
function createRaindrops(container, count) {
    for (let i = 0; i < count; i++) {
        const drop = document.createElement('div');
        drop.className = 'raindrop';
        drop.style.left = `${Math.random() * 100}%`;
        drop.style.animationDuration = `${0.5 + Math.random() * 1}s`;
        drop.style.animationDelay = `${Math.random() * 2}s`;
        container.appendChild(drop);
    }
}

function createSnowflakes(container, count) {
    for (let i = 0; i < count; i++) {
        const flake = document.createElement('div');
        flake.className = 'snowflake';
        flake.style.left = `${Math.random() * 100}%`;
        flake.style.animationDuration = `${3 + Math.random() * 5}s`;
        flake.style.animationDelay = `${Math.random() * 5}s`;
        flake.style.opacity = 0.5 + Math.random() * 0.5;
        flake.style.fontSize = `${5 + Math.random() * 10}px`;
        flake.innerHTML = '❄';
        container.appendChild(flake);
    }
}

function createSunshine(container) {
    const sun = document.createElement('div');
    sun.className = 'sun';
    container.appendChild(sun);
    
    const rays = document.createElement('div');
    rays.className = 'sun-rays';
    sun.appendChild(rays);
}

function createClouds(container, count) {
    for (let i = 0; i < count; i++) {
        const cloud = document.createElement('div');
        cloud.className = 'cloud';
        cloud.style.top = `${10 + Math.random() * 40}%`;
        cloud.style.left = `${Math.random() * 100}%`;
        cloud.style.animationDuration = `${20 + Math.random() * 30}s`;
        cloud.style.animationDelay = `${Math.random() * 10}s`;
        cloud.style.opacity = 0.5 + Math.random() * 0.5;
        cloud.style.transform = `scale(${0.5 + Math.random() * 0.5})`;
        container.appendChild(cloud);
    }
}

function createLightning(container) {
    const flash = document.createElement('div');
    flash.className = 'lightning';
    container.appendChild(flash);
    
    // Create random lightning flashes
    setInterval(() => {
        flash.style.opacity = Math.random() > 0.8 ? 1 : 0;
    }, 500);
}

// Expose functions to global scope
window.FarmersFriend = {
    initTypewriterEffects,
    initScrollAnimations,
    initHoverEffects,
    initParallaxEffects,
    initGlassmorphismEffects,
    initWeatherAnimations
};
