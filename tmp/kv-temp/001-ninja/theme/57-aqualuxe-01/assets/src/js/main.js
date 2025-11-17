/**
 * Main JavaScript file for the AquaLuxe theme
 * 
 * This file handles the main frontend functionality
 */

// Import styles
import '../scss/main.scss';

// Import AlpineJS
import Alpine from 'alpinejs';

// Import Swiper
import Swiper, { Navigation, Pagination, Autoplay } from 'swiper';

// Configure Swiper
Swiper.use([Navigation, Pagination, Autoplay]);

// Make Alpine available globally
window.Alpine = Alpine;

// Initialize Alpine
Alpine.start();

// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize header functionality
    initHeader();
    
    // Initialize sliders
    initSliders();
    
    // Initialize scroll effects
    initScrollEffects();
    
    // Initialize responsive videos
    makeVideosResponsive();
});

/**
 * Initialize header functionality
 */
function initHeader() {
    // Sticky header
    if (document.body.classList.contains('has-sticky-header')) {
        const header = document.getElementById('masthead');
        const headerHeight = header.offsetHeight;
        let lastScrollTop = 0;
        
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > headerHeight) {
                header.classList.add('is-sticky');
                document.body.style.paddingTop = headerHeight + 'px';
            } else {
                header.classList.remove('is-sticky');
                document.body.style.paddingTop = '0';
            }
            
            // Hide/show on scroll
            if (scrollTop > lastScrollTop && scrollTop > headerHeight) {
                // Scrolling down
                header.classList.add('is-hidden');
            } else {
                // Scrolling up
                header.classList.remove('is-hidden');
            }
            
            lastScrollTop = scrollTop;
        });
    }
    
    // Mobile menu toggle
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function() {
            menuToggle.classList.toggle('is-active');
            mobileMenu.classList.toggle('is-active');
            document.body.classList.toggle('mobile-menu-open');
        });
    }
    
    // Sub-menu toggles for mobile
    const subMenuToggles = document.querySelectorAll('.mobile-menu .menu-item-has-children > a');
    
    subMenuToggles.forEach(function(toggle) {
        const subMenuToggle = document.createElement('span');
        subMenuToggle.classList.add('sub-menu-toggle');
        toggle.appendChild(subMenuToggle);
        
        subMenuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const menuItem = toggle.parentNode;
            menuItem.classList.toggle('sub-menu-open');
        });
    });
}

/**
 * Initialize sliders
 */
function initSliders() {
    // Hero slider
    const heroSlider = document.querySelector('.hero-slider');
    if (heroSlider) {
        new Swiper(heroSlider, {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    }
    
    // Testimonial slider
    const testimonialSlider = document.querySelector('.testimonial-slider');
    if (testimonialSlider) {
        new Swiper(testimonialSlider, {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    }
    
    // Product slider
    const productSlider = document.querySelector('.product-slider');
    if (productSlider) {
        new Swiper(productSlider, {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 4,
                },
            },
        });
    }
}

/**
 * Initialize scroll effects
 */
function initScrollEffects() {
    // Animate elements on scroll
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    
    if (animatedElements.length > 0) {
        // Check if IntersectionObserver is supported
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            });
            
            animatedElements.forEach(element => {
                observer.observe(element);
            });
        } else {
            // Fallback for browsers that don't support IntersectionObserver
            animatedElements.forEach(element => {
                element.classList.add('animated');
            });
        }
    }
    
    // Parallax effect
    const parallaxElements = document.querySelectorAll('.parallax');
    
    if (parallaxElements.length > 0) {
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset;
            
            parallaxElements.forEach(element => {
                const speed = element.dataset.speed || 0.5;
                const yPos = -(scrollTop * speed);
                element.style.transform = `translate3d(0px, ${yPos}px, 0px)`;
            });
        });
    }
}

/**
 * Make videos responsive
 */
function makeVideosResponsive() {
    const contentAreas = document.querySelectorAll('.entry-content, .comment-content');
    
    contentAreas.forEach(contentArea => {
        // Find all iframes within content areas
        const iframes = contentArea.querySelectorAll('iframe');
        
        iframes.forEach(iframe => {
            // Check if it's a video iframe (YouTube, Vimeo, etc.)
            const src = iframe.getAttribute('src') || '';
            if (src.includes('youtube.com') || src.includes('youtu.be') || 
                src.includes('vimeo.com') || src.includes('dailymotion.com')) {
                
                // Create wrapper
                const wrapper = document.createElement('div');
                wrapper.classList.add('responsive-video-wrapper');
                
                // Replace iframe with wrapper containing iframe
                iframe.parentNode.insertBefore(wrapper, iframe);
                wrapper.appendChild(iframe);
            }
        });
    });
}