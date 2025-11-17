/**
 * AquaLuxe Theme - Main JavaScript
 * 
 * Core functionality for the AquaLuxe WordPress theme
 * Handles global interactions, animations, and theme features
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

import { gsap } from 'gsap';

class AquaLuxeTheme {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.initAnimations();
        this.initMobileMenu();
        this.initScrollEffects();
        this.initLazyLoading();
    }

    bindEvents() {
        document.addEventListener('DOMContentLoaded', () => {
            console.log('AquaLuxe Theme Loaded');
        });

        // Handle window resize
        window.addEventListener('resize', this.debounce(() => {
            this.handleResize();
        }, 250));

        // Handle scroll
        window.addEventListener('scroll', this.throttle(() => {
            this.handleScroll();
        }, 16));
    }

    initAnimations() {
        // Fade in elements with .animate-fade-in class
        const fadeElements = document.querySelectorAll('.animate-fade-in');
        if (fadeElements.length) {
            gsap.from(fadeElements, {
                opacity: 0,
                y: 30,
                duration: 0.8,
                stagger: 0.2,
                ease: 'power2.out'
            });
        }

        // Hero section animations
        const heroContent = document.querySelector('.hero-content');
        if (heroContent) {
            gsap.timeline()
                .from('.hero-title', { opacity: 0, y: 50, duration: 1 })
                .from('.hero-subtitle', { opacity: 0, y: 30, duration: 0.8 }, '-=0.5')
                .from('.hero-cta', { opacity: 0, y: 20, duration: 0.6 }, '-=0.3');
        }
    }

    initMobileMenu() {
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileMenu = document.querySelector('.mobile-menu');
        
        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', () => {
                const isOpen = mobileMenu.classList.contains('active');
                
                if (isOpen) {
                    this.closeMobileMenu();
                } else {
                    this.openMobileMenu();
                }
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!menuToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
                    this.closeMobileMenu();
                }
            });
        }
    }

    openMobileMenu() {
        const mobileMenu = document.querySelector('.mobile-menu');
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        
        mobileMenu.classList.add('active');
        menuToggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    closeMobileMenu() {
        const mobileMenu = document.querySelector('.mobile-menu');
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        
        mobileMenu.classList.remove('active');
        menuToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    initScrollEffects() {
        // Header scroll effect
        const header = document.querySelector('.site-header');
        if (header) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 100) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });
        }

        // Parallax effects
        const parallaxElements = document.querySelectorAll('.parallax');
        if (parallaxElements.length) {
            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                
                parallaxElements.forEach(element => {
                    const rate = scrolled * -0.5;
                    element.style.transform = `translateY(${rate}px)`;
                });
            });
        }
    }

    initLazyLoading() {
        const lazyImages = document.querySelectorAll('img[data-src]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            lazyImages.forEach(img => imageObserver.observe(img));
        } else {
            // Fallback for older browsers
            lazyImages.forEach(img => {
                img.src = img.dataset.src;
                img.classList.remove('lazy');
            });
        }
    }

    handleResize() {
        // Handle responsive adjustments
        if (window.innerWidth > 768) {
            this.closeMobileMenu();
        }
    }

    handleScroll() {
        // Handle scroll-based effects
        const scrollTop = window.pageYOffset;
        
        // Update scroll progress indicator
        const scrollIndicator = document.querySelector('.scroll-progress');
        if (scrollIndicator) {
            const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrollProgress = (scrollTop / scrollHeight) * 100;
            scrollIndicator.style.width = `${scrollProgress}%`;
        }
    }

    // Utility functions
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    }
}

// Initialize theme
new AquaLuxeTheme();

// Export for global access
window.AquaLuxeTheme = AquaLuxeTheme;