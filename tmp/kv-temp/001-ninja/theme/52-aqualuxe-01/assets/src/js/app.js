/**
 * AquaLuxe Theme Frontend JavaScript
 * 
 * Main JavaScript file for the AquaLuxe theme frontend.
 */

import Alpine from 'alpinejs';
import Swiper, { Navigation, Pagination, Autoplay } from 'swiper';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Configure Swiper to use modules
Swiper.use([Navigation, Pagination, Autoplay]);

// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Dark Mode
    initDarkMode();
    
    // Initialize Sliders
    initSliders();
    
    // Initialize Mobile Menu
    initMobileMenu();
    
    // Initialize Quick View
    if (document.body.classList.contains('woocommerce-active')) {
        initQuickView();
    }
    
    // Initialize Sticky Header
    initStickyHeader();
});

/**
 * Initialize Dark Mode functionality
 */
function initDarkMode() {
    // Check for saved theme preference or respect OS preference
    const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    const savedTheme = localStorage.getItem('aqualuxe-theme');
    
    // Set initial theme based on saved preference or OS preference
    if (savedTheme === 'dark' || (!savedTheme && darkModeMediaQuery.matches)) {
        document.documentElement.classList.add('dark');
        document.documentElement.classList.remove('light');
    } else {
        document.documentElement.classList.add('light');
        document.documentElement.classList.remove('dark');
    }
    
    // Toggle functionality
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
                localStorage.setItem('aqualuxe-theme', 'light');
            } else {
                document.documentElement.classList.remove('light');
                document.documentElement.classList.add('dark');
                localStorage.setItem('aqualuxe-theme', 'dark');
            }
        });
    }
}

/**
 * Initialize Sliders using Swiper
 */
function initSliders() {
    // Hero Slider
    const heroSlider = document.querySelector('.hero-slider');
    if (heroSlider) {
        new Swiper(heroSlider, {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            autoplay: {
                delay: 5000,
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
    
    // Featured Products Slider
    const featuredSlider = document.querySelector('.featured-products-slider');
    if (featuredSlider) {
        new Swiper(featuredSlider, {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
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
    
    // Testimonials Slider
    const testimonialSlider = document.querySelector('.testimonial-slider');
    if (testimonialSlider) {
        new Swiper(testimonialSlider, {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 6000,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    }
}

/**
 * Initialize Mobile Menu
 */
function initMobileMenu() {
    const menuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            document.body.classList.toggle('mobile-menu-open');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!mobileMenu.contains(event.target) && !menuToggle.contains(event.target) && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
                document.body.classList.remove('mobile-menu-open');
            }
        });
    }
    
    // Handle submenu toggles
    const subMenuToggles = document.querySelectorAll('.submenu-toggle');
    subMenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const subMenu = this.nextElementSibling;
            subMenu.classList.toggle('hidden');
            this.classList.toggle('active');
        });
    });
}

/**
 * Initialize Quick View for WooCommerce products
 */
function initQuickView() {
    const quickViewButtons = document.querySelectorAll('.quick-view-button');
    const quickViewModal = document.getElementById('quick-view-modal');
    const quickViewContent = document.getElementById('quick-view-content');
    const quickViewClose = document.getElementById('quick-view-close');
    
    if (quickViewButtons.length && quickViewModal && quickViewContent) {
        quickViewButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.dataset.productId;
                
                // Show loading state
                quickViewContent.innerHTML = '<div class="flex justify-center items-center h-64"><div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary-500"></div></div>';
                quickViewModal.classList.remove('hidden');
                document.body.classList.add('modal-open');
                
                // Fetch product data via AJAX
                fetch(aqualuxe_params.ajax_url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'aqualuxe_quick_view',
                        product_id: productId,
                        nonce: aqualuxe_params.nonce
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        quickViewContent.innerHTML = data.html;
                        // Reinitialize product gallery if needed
                        if (typeof wc_single_product_params !== 'undefined') {
                            // Initialize product gallery
                            jQuery('.woocommerce-product-gallery').each(function() {
                                jQuery(this).wc_product_gallery(wc_single_product_params);
                            });
                        }
                    } else {
                        quickViewContent.innerHTML = '<p class="text-center text-red-500 p-8">Error loading product information.</p>';
                    }
                })
                .catch(error => {
                    quickViewContent.innerHTML = '<p class="text-center text-red-500 p-8">Error loading product information.</p>';
                    console.error('Quick view error:', error);
                });
            });
        });
        
        // Close modal
        if (quickViewClose) {
            quickViewClose.addEventListener('click', function() {
                quickViewModal.classList.add('hidden');
                document.body.classList.remove('modal-open');
            });
        }
        
        // Close when clicking outside
        quickViewModal.addEventListener('click', function(e) {
            if (e.target === quickViewModal) {
                quickViewModal.classList.add('hidden');
                document.body.classList.remove('modal-open');
            }
        });
    }
}

/**
 * Initialize Sticky Header
 */
function initStickyHeader() {
    const header = document.getElementById('site-header');
    const headerHeight = header ? header.offsetHeight : 0;
    let lastScrollTop = 0;
    
    if (header) {
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > headerHeight) {
                header.classList.add('sticky-header');
                document.body.style.paddingTop = headerHeight + 'px';
                
                // Hide on scroll down, show on scroll up
                if (scrollTop > lastScrollTop && scrollTop > headerHeight * 2) {
                    header.classList.add('header-hidden');
                } else {
                    header.classList.remove('header-hidden');
                }
            } else {
                header.classList.remove('sticky-header');
                document.body.style.paddingTop = '0';
            }
            
            lastScrollTop = scrollTop;
        });
    }
}