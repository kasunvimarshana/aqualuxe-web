/**
 * AquaLuxe Theme Main JavaScript
 * 
 * Contains all the custom JavaScript functionality for the AquaLuxe theme
 */

(function() {
    'use strict';

    // DOM elements
    const body = document.body;
    const header = document.getElementById('masthead');
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mainNavigation = document.querySelector('.main-navigation');
    const mainMenu = document.getElementById('primary-menu');
    const darkModeToggle = document.querySelector('.dark-mode-toggle');
    
    /**
     * Initialize all scripts
     */
    function init() {
        setupMobileMenu();
        setupStickyHeader();
        setupSmoothScroll();
        setupDarkMode();
        setupBackToTop();
        setupAnimations();
        
        // Initialize WooCommerce specific scripts if WooCommerce is active
        if (document.body.classList.contains('woocommerce')) {
            setupWooCommerceScripts();
        }
        
        // Initialize sliders if they exist
        setupSliders();
    }

    /**
     * Mobile Menu functionality
     */
    function setupMobileMenu() {
        if (!mobileMenuToggle || !mainNavigation) return;
        
        mobileMenuToggle.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true' || false;
            this.setAttribute('aria-expanded', !expanded);
            mainNavigation.classList.toggle('menu-open');
            body.classList.toggle('menu-is-open');
            
            // Prevent scrolling when menu is open
            if (!expanded) {
                body.style.overflow = 'hidden';
            } else {
                body.style.overflow = '';
            }
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (mainNavigation.classList.contains('menu-open') && 
                !mainNavigation.contains(event.target) && 
                !mobileMenuToggle.contains(event.target)) {
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                mainNavigation.classList.remove('menu-open');
                body.classList.remove('menu-is-open');
                body.style.overflow = '';
            }
        });
        
        // Add dropdown functionality for submenus on mobile
        if (mainMenu) {
            const menuItems = mainMenu.querySelectorAll('.menu-item-has-children');
            
            menuItems.forEach(function(menuItem) {
                const dropdownToggle = document.createElement('button');
                dropdownToggle.className = 'dropdown-toggle';
                dropdownToggle.setAttribute('aria-expanded', 'false');
                dropdownToggle.innerHTML = '<span class="screen-reader-text">Expand submenu</span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
                
                menuItem.appendChild(dropdownToggle);
                
                dropdownToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const expanded = this.getAttribute('aria-expanded') === 'true' || false;
                    this.setAttribute('aria-expanded', !expanded);
                    this.classList.toggle('toggled-on');
                    menuItem.classList.toggle('submenu-open');
                });
            });
        }
    }

    /**
     * Sticky Header functionality
     */
    function setupStickyHeader() {
        if (!header) return;
        
        let lastScrollTop = 0;
        const scrollThreshold = 100;
        
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Add sticky class when scrolling down past threshold
            if (scrollTop > scrollThreshold) {
                header.classList.add('sticky-header');
            } else {
                header.classList.remove('sticky-header');
            }
            
            // Hide header when scrolling down, show when scrolling up
            if (scrollTop > lastScrollTop && scrollTop > 200) {
                header.classList.add('header-hidden');
            } else {
                header.classList.remove('header-hidden');
            }
            
            lastScrollTop = scrollTop;
        });
    }

    /**
     * Smooth Scroll functionality for anchor links
     */
    function setupSmoothScroll() {
        document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    e.preventDefault();
                    
                    const headerOffset = header ? header.offsetHeight : 0;
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                    
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    /**
     * Dark Mode Toggle functionality
     */
    function setupDarkMode() {
        if (!darkModeToggle) return;
        
        // Check for saved user preference
        const darkModeEnabled = localStorage.getItem('darkMode') === 'enabled';
        
        // Set initial state
        if (darkModeEnabled) {
            document.documentElement.classList.add('dark-mode');
            darkModeToggle.setAttribute('aria-pressed', 'true');
        }
        
        darkModeToggle.addEventListener('click', function() {
            const isDarkMode = document.documentElement.classList.toggle('dark-mode');
            this.setAttribute('aria-pressed', isDarkMode);
            
            // Save user preference
            if (isDarkMode) {
                localStorage.setItem('darkMode', 'enabled');
            } else {
                localStorage.setItem('darkMode', 'disabled');
            }
        });
    }

    /**
     * Back to Top button functionality
     */
    function setupBackToTop() {
        const backToTopButton = document.querySelector('.back-to-top');
        if (!backToTopButton) return;
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });
        
        backToTopButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    /**
     * Animation functionality using Intersection Observer
     */
    function setupAnimations() {
        if (!('IntersectionObserver' in window)) return;
        
        const animatedElements = document.querySelectorAll('.animate-on-scroll');
        
        const animationObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    animationObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });
        
        animatedElements.forEach(element => {
            animationObserver.observe(element);
        });
    }

    /**
     * Slider functionality
     */
    function setupSliders() {
        // Testimonials slider
        const testimonialsSlider = document.querySelector('.testimonials-slider');
        if (testimonialsSlider) {
            // Simple slider functionality
            // This is a basic implementation - consider using a library like Swiper or Slick for more complex needs
            const testimonialItems = testimonialsSlider.querySelectorAll('.testimonial-item');
            const totalItems = testimonialItems.length;
            if (totalItems <= 1) return;
            
            let currentIndex = 0;
            
            // Create navigation dots
            const dotsContainer = document.createElement('div');
            dotsContainer.className = 'slider-dots';
            
            for (let i = 0; i < totalItems; i++) {
                const dot = document.createElement('button');
                dot.className = 'slider-dot';
                dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
                if (i === 0) dot.classList.add('active');
                
                dot.addEventListener('click', () => {
                    goToSlide(i);
                });
                
                dotsContainer.appendChild(dot);
            }
            
            testimonialsSlider.appendChild(dotsContainer);
            
            // Create prev/next buttons
            const prevButton = document.createElement('button');
            prevButton.className = 'slider-nav slider-prev';
            prevButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>';
            prevButton.setAttribute('aria-label', 'Previous slide');
            
            const nextButton = document.createElement('button');
            nextButton.className = 'slider-nav slider-next';
            nextButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>';
            nextButton.setAttribute('aria-label', 'Next slide');
            
            testimonialsSlider.appendChild(prevButton);
            testimonialsSlider.appendChild(nextButton);
            
            prevButton.addEventListener('click', () => {
                goToSlide(currentIndex - 1);
            });
            
            nextButton.addEventListener('click', () => {
                goToSlide(currentIndex + 1);
            });
            
            function goToSlide(index) {
                // Handle circular navigation
                if (index < 0) index = totalItems - 1;
                if (index >= totalItems) index = 0;
                
                // Update active slide
                testimonialItems.forEach((item, i) => {
                    if (i === index) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });
                
                // Update dots
                const dots = dotsContainer.querySelectorAll('.slider-dot');
                dots.forEach((dot, i) => {
                    if (i === index) {
                        dot.classList.add('active');
                    } else {
                        dot.classList.remove('active');
                    }
                });
                
                currentIndex = index;
            }
            
            // Initialize first slide
            goToSlide(0);
            
            // Auto-rotate slides
            let slideInterval = setInterval(() => {
                goToSlide(currentIndex + 1);
            }, 5000);
            
            // Pause on hover
            testimonialsSlider.addEventListener('mouseenter', () => {
                clearInterval(slideInterval);
            });
            
            testimonialsSlider.addEventListener('mouseleave', () => {
                slideInterval = setInterval(() => {
                    goToSlide(currentIndex + 1);
                }, 5000);
            });
        }
    }

    /**
     * WooCommerce specific scripts
     */
    function setupWooCommerceScripts() {
        // Product image gallery
        const productGallery = document.querySelector('.woocommerce-product-gallery');
        if (productGallery) {
            const mainImage = productGallery.querySelector('.woocommerce-product-gallery__image');
            const thumbnails = productGallery.querySelectorAll('.woocommerce-product-gallery__image a');
            
            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function(e) {
                    e.preventDefault();
                    const fullSizeUrl = this.getAttribute('href');
                    const imgSrc = this.querySelector('img').getAttribute('src');
                    const imgAlt = this.querySelector('img').getAttribute('alt');
                    
                    mainImage.querySelector('a').setAttribute('href', fullSizeUrl);
                    mainImage.querySelector('img').setAttribute('src', imgSrc);
                    mainImage.querySelector('img').setAttribute('alt', imgAlt);
                });
            });
        }
        
        // Quantity input buttons
        const quantityInputs = document.querySelectorAll('.quantity input[type="number"]');
        quantityInputs.forEach(input => {
            const wrapper = document.createElement('div');
            wrapper.className = 'quantity-wrapper';
            
            const minusBtn = document.createElement('button');
            minusBtn.className = 'quantity-btn minus';
            minusBtn.type = 'button';
            minusBtn.textContent = '-';
            
            const plusBtn = document.createElement('button');
            plusBtn.className = 'quantity-btn plus';
            plusBtn.type = 'button';
            plusBtn.textContent = '+';
            
            input.parentNode.insertBefore(wrapper, input);
            wrapper.appendChild(minusBtn);
            wrapper.appendChild(input);
            wrapper.appendChild(plusBtn);
            
            minusBtn.addEventListener('click', () => {
                const currentValue = parseInt(input.value);
                const minValue = parseInt(input.getAttribute('min')) || 1;
                if (currentValue > minValue) {
                    input.value = currentValue - 1;
                    triggerEvent(input, 'change');
                }
            });
            
            plusBtn.addEventListener('click', () => {
                const currentValue = parseInt(input.value);
                const maxValue = parseInt(input.getAttribute('max')) || 999;
                if (currentValue < maxValue) {
                    input.value = currentValue + 1;
                    triggerEvent(input, 'change');
                }
            });
        });
        
        // Quick view functionality
        const quickViewButtons = document.querySelectorAll('.quick-view-button');
        quickViewButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.getAttribute('data-product-id');
                
                // Show loading overlay
                const loadingOverlay = document.createElement('div');
                loadingOverlay.className = 'loading-overlay';
                loadingOverlay.innerHTML = '<div class="loading-spinner"></div>';
                document.body.appendChild(loadingOverlay);
                
                // Fetch product data via AJAX
                // This is a placeholder - you would need to implement the actual AJAX call
                console.log(`Quick view requested for product ID: ${productId}`);
                
                // Simulate AJAX response for demo purposes
                setTimeout(() => {
                    document.body.removeChild(loadingOverlay);
                    alert('Quick view functionality would show product details in a modal');
                }, 1000);
            });
        });
    }

    /**
     * Helper function to trigger events on elements
     */
    function triggerEvent(element, eventName) {
        const event = new Event(eventName, { bubbles: true });
        element.dispatchEvent(event);
    }

    // Initialize when DOM is fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();