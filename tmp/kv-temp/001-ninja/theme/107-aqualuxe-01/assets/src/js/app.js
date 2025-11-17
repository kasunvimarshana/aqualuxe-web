/**
 * AquaLuxe Theme JavaScript
 * 
 * Main application JavaScript file that handles theme functionality
 * and coordinates with Alpine.js for reactive components.
 */

import Alpine from 'alpinejs';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Swiper, Navigation, Pagination, Autoplay } from 'swiper';

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger);

// Configure Swiper modules
Swiper.use([Navigation, Pagination, Autoplay]);

// Theme class for organizing functionality
class AquaLuxeTheme {
    constructor() {
        this.init();
    }

    init() {
        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.setupTheme();
            });
        } else {
            this.setupTheme();
        }
    }

    setupTheme() {
        // Initialize components
        this.setupNavigation();
        this.setupSearch();
        this.setupDarkMode();
        this.setupScrollEffects();
        this.setupBackToTop();
        this.setupImageLazyLoading();
        this.setupFormEnhancements();
        this.setupWooCommerce();
        this.setupAccessibility();

        // Initialize Alpine.js
        Alpine.start();

        // Trigger custom event
        document.dispatchEvent(new CustomEvent('aqualuxe:theme-ready'));
    }

    /**
     * Setup navigation functionality
     */
    setupNavigation() {
        const mobileToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileClose = document.getElementById('mobile-menu-close');

        if (mobileToggle && mobileMenu) {
            mobileToggle.addEventListener('click', () => {
                mobileMenu.classList.remove('translate-x-full');
                document.body.classList.add('overflow-hidden');
            });

            const closeMobileMenu = () => {
                mobileMenu.classList.add('translate-x-full');
                document.body.classList.remove('overflow-hidden');
            };

            if (mobileClose) {
                mobileClose.addEventListener('click', closeMobileMenu);
            }

            // Close on escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !mobileMenu.classList.contains('translate-x-full')) {
                    closeMobileMenu();
                }
            });

            // Close on outside click
            mobileMenu.addEventListener('click', (e) => {
                if (e.target === mobileMenu) {
                    closeMobileMenu();
                }
            });
        }

        // Sticky header
        const header = document.getElementById('masthead');
        if (header) {
            let lastScrollY = window.scrollY;

            window.addEventListener('scroll', () => {
                const currentScrollY = window.scrollY;
                
                if (currentScrollY > 100) {
                    header.classList.add('header-scrolled');
                } else {
                    header.classList.remove('header-scrolled');
                }

                // Hide/show header on scroll
                if (currentScrollY > lastScrollY && currentScrollY > 200) {
                    header.style.transform = 'translateY(-100%)';
                } else {
                    header.style.transform = 'translateY(0)';
                }

                lastScrollY = currentScrollY;
            });
        }
    }

    /**
     * Setup search functionality
     */
    setupSearch() {
        const searchToggle = document.getElementById('search-toggle');
        const searchOverlay = document.getElementById('search-overlay');
        const searchClose = document.getElementById('search-close');
        const searchInput = searchOverlay?.querySelector('input[type="search"]');

        if (searchToggle && searchOverlay) {
            searchToggle.addEventListener('click', () => {
                searchOverlay.classList.remove('hidden');
                searchInput?.focus();
            });

            const closeSearch = () => {
                searchOverlay.classList.add('hidden');
            };

            if (searchClose) {
                searchClose.addEventListener('click', closeSearch);
            }

            // Close on escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !searchOverlay.classList.contains('hidden')) {
                    closeSearch();
                }
            });

            // Close on outside click
            searchOverlay.addEventListener('click', (e) => {
                if (e.target === searchOverlay) {
                    closeSearch();
                }
            });
        }
    }

    /**
     * Setup dark mode functionality
     */
    setupDarkMode() {
        const darkModeToggle = document.getElementById('dark-mode-toggle');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        // Get saved preference or use system preference
        const savedTheme = localStorage.getItem('aqualuxe-theme');
        const isDark = savedTheme ? savedTheme === 'dark' : prefersDark;

        // Apply initial theme
        this.toggleDarkMode(isDark, false);

        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', () => {
                const isCurrentlyDark = document.documentElement.classList.contains('dark');
                this.toggleDarkMode(!isCurrentlyDark, true);
            });
        }

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('aqualuxe-theme')) {
                this.toggleDarkMode(e.matches, false);
            }
        });
    }

    toggleDarkMode(isDark, save = true) {
        if (isDark) {
            document.documentElement.classList.add('dark');
            if (save) localStorage.setItem('aqualuxe-theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            if (save) localStorage.setItem('aqualuxe-theme', 'light');
        }

        // Update toggle icons
        const sunIcon = document.querySelector('.dark-mode-sun');
        const moonIcon = document.querySelector('.dark-mode-moon');
        
        if (sunIcon && moonIcon) {
            if (isDark) {
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            } else {
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
            }
        }

        // Trigger custom event
        document.dispatchEvent(new CustomEvent('aqualuxe:theme-changed', {
            detail: { isDark }
        }));
    }

    /**
     * Setup scroll effects and animations
     */
    setupScrollEffects() {
        // Fade in animations
        gsap.utils.toArray('.animate-on-scroll').forEach((element) => {
            gsap.fromTo(element, 
                { opacity: 0, y: 50 },
                {
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: element,
                        start: 'top 80%',
                        toggleActions: 'play none none reverse'
                    }
                }
            );
        });

        // Parallax effects
        gsap.utils.toArray('.parallax').forEach((element) => {
            gsap.to(element, {
                yPercent: -50,
                ease: 'none',
                scrollTrigger: {
                    trigger: element,
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: true
                }
            });
        });
    }

    /**
     * Setup back to top button
     */
    setupBackToTop() {
        const backToTop = document.getElementById('back-to-top');
        
        if (backToTop) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 300) {
                    backToTop.classList.remove('opacity-0', 'pointer-events-none');
                } else {
                    backToTop.classList.add('opacity-0', 'pointer-events-none');
                }
            });

            backToTop.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    }

    /**
     * Setup image lazy loading
     */
    setupImageLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        observer.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Setup form enhancements
     */
    setupFormEnhancements() {
        // Floating labels
        document.querySelectorAll('.form-field').forEach(field => {
            const label = field.previousElementSibling;
            
            if (label && label.classList.contains('form-label')) {
                field.addEventListener('focus', () => {
                    label.classList.add('focused');
                });

                field.addEventListener('blur', () => {
                    if (!field.value) {
                        label.classList.remove('focused');
                    }
                });

                // Check initial state
                if (field.value) {
                    label.classList.add('focused');
                }
            }
        });

        // Form validation
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('error');
                        isValid = false;
                    } else {
                        field.classList.remove('error');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    }

    /**
     * Setup WooCommerce functionality
     */
    setupWooCommerce() {
        if (typeof woocommerce !== 'undefined' || document.body.classList.contains('woocommerce')) {
            // Product gallery
            this.setupProductGallery();
            
            // Quick view
            this.setupQuickView();
            
            // Cart updates
            this.setupCartUpdates();
        }
    }

    setupProductGallery() {
        const productGallery = document.querySelector('.woocommerce-product-gallery');
        
        if (productGallery) {
            new Swiper('.product-gallery-slider', {
                slidesPerView: 1,
                spaceBetween: 10,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                thumbs: {
                    swiper: new Swiper('.product-gallery-thumbs', {
                        slidesPerView: 4,
                        spaceBetween: 10,
                        freeMode: true,
                        watchSlidesProgress: true,
                    }),
                },
            });
        }
    }

    setupQuickView() {
        document.querySelectorAll('.quick-view-btn').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const productId = btn.dataset.productId;
                
                if (productId) {
                    try {
                        const response = await fetch(`${aqualuxe.ajax_url}?action=quick_view&product_id=${productId}&nonce=${aqualuxe.nonce}`);
                        const data = await response.json();
                        
                        if (data.success) {
                            this.showQuickViewModal(data.data);
                        }
                    } catch (error) {
                        console.error('Quick view error:', error);
                    }
                }
            });
        });
    }

    setupCartUpdates() {
        // Add to cart AJAX
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('ajax-add-to-cart')) {
                e.preventDefault();
                this.addToCartAjax(e.target);
            }
        });
    }

    /**
     * Setup accessibility features
     */
    setupAccessibility() {
        // Skip links
        document.querySelectorAll('.skip-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(link.getAttribute('href'));
                if (target) {
                    target.focus();
                    target.scrollIntoView();
                }
            });
        });

        // ARIA labels for buttons without text
        document.querySelectorAll('button:empty').forEach(btn => {
            if (!btn.getAttribute('aria-label')) {
                btn.setAttribute('aria-label', 'Button');
            }
        });

        // Focus management for modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                this.handleTabNavigation(e);
            }
        });
    }

    handleTabNavigation(e) {
        const modal = document.querySelector('.modal:not(.hidden)');
        if (modal) {
            const focusableElements = modal.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];

            if (e.shiftKey) {
                if (document.activeElement === firstElement) {
                    lastElement.focus();
                    e.preventDefault();
                }
            } else {
                if (document.activeElement === lastElement) {
                    firstElement.focus();
                    e.preventDefault();
                }
            }
        }
    }
}

// Initialize theme
const theme = new AquaLuxeTheme();

// Export for use in other scripts
window.AquaLuxeTheme = theme;