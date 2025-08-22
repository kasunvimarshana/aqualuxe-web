/**
 * AquaLuxe Main JavaScript
 *
 * This is the main JavaScript file for the AquaLuxe WordPress theme.
 */

// Import dependencies
import Alpine from 'alpinejs';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Import modules
import DarkMode from './modules/dark-mode';
import Navigation from './modules/navigation';
import HeroBanner from './modules/hero-banner';
import FeaturesGrid from './modules/features-grid';
import TestimonialsSlider from './modules/testimonials-slider';
import CallToAction from './modules/call-to-action';
import TeamMembers from './modules/team-members';

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger);

// Make Alpine available globally
window.Alpine = Alpine;

/**
 * AquaLuxe App
 */
const AquaLuxe = {
    /**
     * Initialize the app
     */
    init() {
        // Initialize Alpine.js
        Alpine.start();
        
        // Initialize modules
        this.initModules();
        
        // Initialize GSAP animations
        this.initAnimations();
        
        // Add event listeners
        this.addEventListeners();
        
        // Initialize theme features
        this.initThemeFeatures();
        
        console.log('AquaLuxe theme initialized');
    },
    
    /**
     * Initialize modules
     */
    initModules() {
        // Initialize dark mode
        DarkMode.init();
        
        // Initialize navigation
        Navigation.init();
        
        // Initialize hero banner
        HeroBanner.init();
        
        // Initialize features grid
        FeaturesGrid.init();
        
        // Initialize testimonials slider
        TestimonialsSlider.init();
        
        // Initialize call to action
        CallToAction.init();
        
        // Initialize team members
        TeamMembers.init();
    },
    
    /**
     * Initialize GSAP animations
     */
    initAnimations() {
        // Fade in elements on scroll
        gsap.utils.toArray('.fade-in').forEach(element => {
            gsap.from(element, {
                opacity: 0,
                y: 30,
                duration: 1,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: element,
                    start: 'top 80%',
                    toggleActions: 'play none none none'
                }
            });
        });
        
        // Stagger animations for lists
        gsap.utils.toArray('.stagger-list').forEach(list => {
            const items = list.querySelectorAll('.stagger-item');
            
            gsap.from(items, {
                opacity: 0,
                y: 20,
                duration: 0.8,
                stagger: 0.1,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: list,
                    start: 'top 80%',
                    toggleActions: 'play none none none'
                }
            });
        });
    },
    
    /**
     * Add event listeners
     */
    addEventListeners() {
        // Handle smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                
                if (target) {
                    e.preventDefault();
                    
                    window.scrollTo({
                        top: target.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Handle back to top button
        const backToTopButton = document.querySelector('.back-to-top');
        
        if (backToTopButton) {
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTopButton.classList.add('is-visible');
                } else {
                    backToTopButton.classList.remove('is-visible');
                }
            });
            
            backToTopButton.addEventListener('click', e => {
                e.preventDefault();
                
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    },
    
    /**
     * Initialize theme features
     */
    initThemeFeatures() {
        // Initialize lazy loading for images
        if ('loading' in HTMLImageElement.prototype) {
            const lazyImages = document.querySelectorAll('img[loading="lazy"]');
            lazyImages.forEach(img => {
                img.src = img.dataset.src;
            });
        } else {
            // Fallback for browsers that don't support native lazy loading
            const lazyLoadScript = document.createElement('script');
            lazyLoadScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js';
            document.body.appendChild(lazyLoadScript);
        }
    }
};

// Initialize the app when the DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    AquaLuxe.init();
});

// Export the app
export default AquaLuxe;