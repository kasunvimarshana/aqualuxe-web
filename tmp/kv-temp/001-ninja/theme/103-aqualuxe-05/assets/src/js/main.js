/**
 * Main JavaScript for AquaLuxe Theme
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Import dependencies
import Alpine from 'alpinejs';
import AOS from 'aos';
import { GLightbox } from 'glightbox';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger);

// Make Alpine available globally
window.Alpine = Alpine;

// Initialize Alpine.js
Alpine.start();

/**
 * AquaLuxe Theme Main Class
 */
class AquaLuxeTheme {
    constructor() {
        this.init();
    }

    /**
     * Initialize theme functionality
     */
    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.onReady());
        } else {
            this.onReady();
        }
    }

    /**
     * Execute when DOM is ready
     */
    onReady() {
        this.initAOS();
        this.initLightbox();
        this.initNavigation();
        this.initDarkMode();
        this.initBackToTop();
        this.initSmoothScroll();
        this.initParallax();
        this.initAnimations();
        this.initForms();
        this.initSearch();
        this.initAccessibility();
        
        // Theme-specific initializations
        this.initProductInteractions();
        this.initNewsletterSignup();
        this.initTestimonials();
        
        console.log('AquaLuxe Theme initialized successfully');
    }

    /**
     * Initialize AOS (Animate On Scroll)
     */
    initAOS() {
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 100,
            delay: 100,
        });
    }

    /**
     * Initialize GLightbox for image galleries
     */
    initLightbox() {
        if (typeof GLightbox !== 'undefined') {
            const lightbox = GLightbox({
                selector: '.glightbox',
                touchNavigation: true,
                loop: true,
                autoplayVideos: true,
                plyr: {
                    config: {
                        ratio: '16:9',
                        youtube: {
                            noCookie: true,
                            rel: 0,
                            showinfo: 0,
                            iv_load_policy: 3
                        },
                        vimeo: {
                            byline: false,
                            portrait: false,
                            title: false,
                            speed: true,
                            transparent: false
                        }
                    }
                }
            });
        }
    }

    /**
     * Initialize navigation functionality
     */
    initNavigation() {
        const mobileMenuButton = document.querySelector('[data-mobile-menu-toggle]');
        const mobileMenu = document.querySelector('[data-mobile-menu]');
        const mobileMenuOverlay = document.querySelector('[data-mobile-menu-overlay]');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                this.toggleMobileMenu(mobileMenu, mobileMenuOverlay);
            });

            // Close menu when clicking overlay
            if (mobileMenuOverlay) {
                mobileMenuOverlay.addEventListener('click', () => {
                    this.closeMobileMenu(mobileMenu, mobileMenuOverlay);
                });
            }

            // Close menu when pressing escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                    this.closeMobileMenu(mobileMenu, mobileMenuOverlay);
                }
            });
        }

        // Initialize mega menu
        this.initMegaMenu();

        // Initialize sticky header
        this.initStickyHeader();
    }

    /**
     * Toggle mobile menu
     */
    toggleMobileMenu(menu, overlay) {
        const isOpen = menu.classList.contains('active');
        
        if (isOpen) {
            this.closeMobileMenu(menu, overlay);
        } else {
            this.openMobileMenu(menu, overlay);
        }
    }

    /**
     * Open mobile menu
     */
    openMobileMenu(menu, overlay) {
        menu.classList.add('active');
        if (overlay) overlay.classList.add('active');
        document.body.classList.add('menu-open');
        
        // Focus management
        const firstFocusable = menu.querySelector('a, button, input, [tabindex]');
        if (firstFocusable) firstFocusable.focus();
    }

    /**
     * Close mobile menu
     */
    closeMobileMenu(menu, overlay) {
        menu.classList.remove('active');
        if (overlay) overlay.classList.remove('active');
        document.body.classList.remove('menu-open');
    }

    /**
     * Initialize mega menu functionality
     */
    initMegaMenu() {
        const megaMenuItems = document.querySelectorAll('[data-mega-menu]');
        
        megaMenuItems.forEach(item => {
            const trigger = item.querySelector('[data-mega-menu-trigger]');
            const panel = item.querySelector('[data-mega-menu-panel]');
            
            if (trigger && panel) {
                let timeout;
                
                // Show on hover
                item.addEventListener('mouseenter', () => {
                    clearTimeout(timeout);
                    panel.classList.add('active');
                });
                
                // Hide on leave with delay
                item.addEventListener('mouseleave', () => {
                    timeout = setTimeout(() => {
                        panel.classList.remove('active');
                    }, 150);
                });
                
                // Keyboard navigation
                trigger.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        panel.classList.toggle('active');
                    }
                });
            }
        });
    }

    /**
     * Initialize sticky header
     */
    initStickyHeader() {
        const header = document.querySelector('.site-header');
        if (!header) return;

        let lastScrollY = window.scrollY;
        let ticking = false;

        const updateHeader = () => {
            const scrollY = window.scrollY;
            
            if (scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }

            // Hide/show header based on scroll direction
            if (Math.abs(scrollY - lastScrollY) > 10) {
                if (scrollY > lastScrollY && scrollY > 200) {
                    header.classList.add('header-hidden');
                } else {
                    header.classList.remove('header-hidden');
                }
                lastScrollY = scrollY;
            }

            ticking = false;
        };

        const requestTick = () => {
            if (!ticking) {
                requestAnimationFrame(updateHeader);
                ticking = true;
            }
        };

        window.addEventListener('scroll', requestTick, { passive: true });
    }

    /**
     * Initialize dark mode toggle
     */
    initDarkMode() {
        const toggleButton = document.querySelector('[data-dark-mode-toggle]');
        if (!toggleButton) return;

        // Get saved preference or default to light mode
        const savedTheme = localStorage.getItem('aqualuxe-theme') || 'light';
        this.setTheme(savedTheme);

        toggleButton.addEventListener('click', () => {
            const currentTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            this.setTheme(newTheme);
            localStorage.setItem('aqualuxe-theme', newTheme);
        });
    }

    /**
     * Set theme
     */
    setTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        // Update toggle button icon
        const toggleButton = document.querySelector('[data-dark-mode-toggle]');
        if (toggleButton) {
            const icon = toggleButton.querySelector('svg');
            if (icon) {
                icon.classList.toggle('rotate-180', theme === 'dark');
            }
        }
    }

    /**
     * Initialize back to top button
     */
    initBackToTop() {
        const backToTopButton = document.querySelector('#back-to-top');
        if (!backToTopButton) return;

        const showButton = () => {
            if (window.scrollY > 300) {
                backToTopButton.classList.remove('translate-y-16', 'opacity-0');
                backToTopButton.classList.add('translate-y-0', 'opacity-100');
            } else {
                backToTopButton.classList.add('translate-y-16', 'opacity-0');
                backToTopButton.classList.remove('translate-y-0', 'opacity-100');
            }
        };

        window.addEventListener('scroll', showButton, { passive: true });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    /**
     * Initialize smooth scroll for anchor links
     */
    initSmoothScroll() {
        const anchorLinks = document.querySelectorAll('a[href^="#"]');
        
        anchorLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                const href = link.getAttribute('href');
                const target = document.querySelector(href);
                
                if (target) {
                    e.preventDefault();
                    
                    const headerHeight = document.querySelector('.site-header')?.offsetHeight || 0;
                    const targetPosition = target.offsetTop - headerHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    /**
     * Initialize parallax effects
     */
    initParallax() {
        const parallaxElements = document.querySelectorAll('[data-parallax]');
        
        parallaxElements.forEach(element => {
            const speed = parseFloat(element.dataset.parallax) || 0.5;
            
            gsap.to(element, {
                yPercent: -50 * speed,
                ease: "none",
                scrollTrigger: {
                    trigger: element,
                    start: "top bottom",
                    end: "bottom top",
                    scrub: true
                }
            });
        });
    }

    /**
     * Initialize animations
     */
    initAnimations() {
        // Fade in animation for elements
        const fadeElements = document.querySelectorAll('[data-animate="fade"]');
        fadeElements.forEach(element => {
            gsap.fromTo(element, 
                { opacity: 0, y: 30 },
                {
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    scrollTrigger: {
                        trigger: element,
                        start: "top 80%",
                        toggleActions: "play none none reverse"
                    }
                }
            );
        });

        // Scale in animation for cards
        const scaleElements = document.querySelectorAll('[data-animate="scale"]');
        scaleElements.forEach((element, index) => {
            gsap.fromTo(element,
                { opacity: 0, scale: 0.8 },
                {
                    opacity: 1,
                    scale: 1,
                    duration: 0.6,
                    delay: index * 0.1,
                    scrollTrigger: {
                        trigger: element,
                        start: "top 80%",
                        toggleActions: "play none none reverse"
                    }
                }
            );
        });
    }

    /**
     * Initialize form enhancements
     */
    initForms() {
        // Form validation
        const forms = document.querySelectorAll('form[data-validate]');
        forms.forEach(form => this.initFormValidation(form));

        // File upload enhancements
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => this.enhanceFileInput(input));
    }

    /**
     * Initialize form validation
     */
    initFormValidation(form) {
        const inputs = form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearFieldError(input));
        });

        form.addEventListener('submit', (e) => {
            let isValid = true;
            
            inputs.forEach(input => {
                if (!this.validateField(input)) {
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    /**
     * Validate form field
     */
    validateField(field) {
        const value = field.value.trim();
        const type = field.type;
        const required = field.hasAttribute('required');
        let isValid = true;
        let message = '';

        // Required validation
        if (required && !value) {
            isValid = false;
            message = 'This field is required.';
        }

        // Email validation
        if (type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                message = 'Please enter a valid email address.';
            }
        }

        // Phone validation
        if (type === 'tel' && value) {
            const phoneRegex = /^[\+]?[0-9\s\-\(\)]+$/;
            if (!phoneRegex.test(value)) {
                isValid = false;
                message = 'Please enter a valid phone number.';
            }
        }

        this.showFieldError(field, isValid ? '' : message);
        return isValid;
    }

    /**
     * Show field error
     */
    showFieldError(field, message) {
        this.clearFieldError(field);

        if (message) {
            field.classList.add('error');
            
            const errorElement = document.createElement('div');
            errorElement.className = 'form-error';
            errorElement.textContent = message;
            
            field.parentNode.appendChild(errorElement);
        }
    }

    /**
     * Clear field error
     */
    clearFieldError(field) {
        field.classList.remove('error');
        
        const errorElement = field.parentNode.querySelector('.form-error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    /**
     * Enhance file input
     */
    enhanceFileInput(input) {
        const wrapper = document.createElement('div');
        wrapper.className = 'file-input-wrapper';
        
        const label = document.createElement('label');
        label.className = 'file-input-label btn btn-outline';
        label.textContent = 'Choose File';
        label.htmlFor = input.id;
        
        const fileName = document.createElement('span');
        fileName.className = 'file-name text-sm text-neutral-600 ml-2';
        
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        wrapper.appendChild(label);
        wrapper.appendChild(fileName);
        
        input.addEventListener('change', () => {
            const files = input.files;
            if (files.length > 0) {
                fileName.textContent = files.length === 1 ? files[0].name : `${files.length} files selected`;
            } else {
                fileName.textContent = '';
            }
        });
    }

    /**
     * Initialize search functionality
     */
    initSearch() {
        const searchToggle = document.querySelector('[data-search-toggle]');
        const searchModal = document.querySelector('[data-search-modal]');
        const searchInput = document.querySelector('[data-search-input]');
        const searchClose = document.querySelector('[data-search-close]');

        if (searchToggle && searchModal) {
            searchToggle.addEventListener('click', () => {
                searchModal.classList.add('active');
                if (searchInput) {
                    setTimeout(() => searchInput.focus(), 100);
                }
            });

            if (searchClose) {
                searchClose.addEventListener('click', () => {
                    searchModal.classList.remove('active');
                });
            }

            // Close on escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && searchModal.classList.contains('active')) {
                    searchModal.classList.remove('active');
                }
            });
        }

        // Live search functionality
        if (searchInput) {
            let searchTimeout;
            
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                const query = e.target.value.trim();
                
                if (query.length >= 3) {
                    searchTimeout = setTimeout(() => {
                        this.performSearch(query);
                    }, 300);
                }
            });
        }
    }

    /**
     * Perform search
     */
    performSearch(query) {
        // This would typically make an AJAX request to search
        console.log(`Searching for: ${query}`);
        
        // Example AJAX implementation
        if (typeof aqualuxe_ajax !== 'undefined') {
            fetch(aqualuxe_ajax.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'aqualuxe_search',
                    query: query,
                    nonce: aqualuxe_ajax.nonce
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.displaySearchResults(data.data);
                }
            })
            .catch(error => {
                console.error('Search error:', error);
            });
        }
    }

    /**
     * Display search results
     */
    displaySearchResults(results) {
        const resultsContainer = document.querySelector('[data-search-results]');
        if (!resultsContainer) return;

        if (results.length === 0) {
            resultsContainer.innerHTML = '<p class="text-center text-neutral-500">No results found.</p>';
            return;
        }

        const resultsHTML = results.map(result => `
            <div class="search-result p-4 border-b border-neutral-200 last:border-b-0">
                <h3 class="font-semibold mb-2">
                    <a href="${result.url}" class="text-primary-600 hover:text-primary-700">
                        ${result.title}
                    </a>
                </h3>
                <p class="text-sm text-neutral-600">${result.excerpt}</p>
            </div>
        `).join('');

        resultsContainer.innerHTML = resultsHTML;
    }

    /**
     * Initialize accessibility features
     */
    initAccessibility() {
        // Skip link functionality
        const skipLink = document.querySelector('.skip-link');
        if (skipLink) {
            skipLink.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(skipLink.getAttribute('href'));
                if (target) {
                    target.focus();
                    target.scrollIntoView();
                }
            });
        }

        // Keyboard navigation for dropdowns
        const dropdownToggles = document.querySelectorAll('[data-dropdown-toggle]');
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggle.click();
                }
            });
        });

        // Focus management for modals
        this.initModalFocus();
    }

    /**
     * Initialize modal focus management
     */
    initModalFocus() {
        const modals = document.querySelectorAll('[data-modal]');
        
        modals.forEach(modal => {
            modal.addEventListener('keydown', (e) => {
                if (e.key === 'Tab') {
                    this.trapFocus(e, modal);
                }
            });
        });
    }

    /**
     * Trap focus within element
     */
    trapFocus(e, element) {
        const focusableElements = element.querySelectorAll(
            'a[href], button, textarea, input[type="text"], input[type="radio"], input[type="checkbox"], select, [tabindex]:not([tabindex="-1"])'
        );
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        if (e.shiftKey && document.activeElement === firstElement) {
            e.preventDefault();
            lastElement.focus();
        } else if (!e.shiftKey && document.activeElement === lastElement) {
            e.preventDefault();
            firstElement.focus();
        }
    }

    /**
     * Initialize product interactions
     */
    initProductInteractions() {
        // Quick view functionality
        const quickViewButtons = document.querySelectorAll('[data-quick-view]');
        quickViewButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const productId = button.dataset.productId;
                this.openQuickView(productId);
            });
        });

        // Wishlist functionality
        const wishlistButtons = document.querySelectorAll('[data-wishlist]');
        wishlistButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const productId = button.dataset.productId;
                this.toggleWishlist(productId);
            });
        });

        // Compare functionality
        const compareButtons = document.querySelectorAll('[data-compare]');
        compareButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const productId = button.dataset.productId;
                this.toggleCompare(productId);
            });
        });
    }

    /**
     * Open quick view modal
     */
    openQuickView(productId) {
        // Implementation would load product details via AJAX
        console.log(`Opening quick view for product: ${productId}`);
    }

    /**
     * Toggle wishlist
     */
    toggleWishlist(productId) {
        // Implementation would toggle wishlist via AJAX
        console.log(`Toggling wishlist for product: ${productId}`);
    }

    /**
     * Toggle compare
     */
    toggleCompare(productId) {
        // Implementation would toggle compare via AJAX
        console.log(`Toggling compare for product: ${productId}`);
    }

    /**
     * Initialize newsletter signup
     */
    initNewsletterSignup() {
        const forms = document.querySelectorAll('[data-newsletter-form]');
        
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleNewsletterSignup(form);
            });
        });
    }

    /**
     * Handle newsletter signup
     */
    handleNewsletterSignup(form) {
        const email = form.querySelector('input[type="email"]').value;
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;

        // Show loading state
        submitButton.textContent = 'Subscribing...';
        submitButton.disabled = true;

        // Simulate API call (replace with actual implementation)
        setTimeout(() => {
            // Reset button
            submitButton.textContent = originalText;
            submitButton.disabled = false;

            // Show success message
            this.showNotification('Thank you for subscribing!', 'success');
            form.reset();
        }, 1000);
    }

    /**
     * Initialize testimonials carousel
     */
    initTestimonials() {
        const testimonialContainers = document.querySelectorAll('[data-testimonials]');
        
        testimonialContainers.forEach(container => {
            // Implementation would initialize carousel/slider
            console.log('Initializing testimonials carousel');
        });
    }

    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type} fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => notification.classList.add('notification-visible'), 10);

        // Remove after delay
        setTimeout(() => {
            notification.classList.remove('notification-visible');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
}

// Initialize theme when DOM is ready
new AquaLuxeTheme();