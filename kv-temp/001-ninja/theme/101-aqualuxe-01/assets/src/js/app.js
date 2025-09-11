// JavaScript main entry point
import Alpine from 'alpinejs';
import { Swiper } from 'swiper';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Initialize Swiper for carousels

// DOM ready function
function domReady(fn) {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fn);
    } else {
        fn();
    }
}

// Theme object
const AquaLuxe = {
    // Initialize theme
    init() {
        this.initMobileMenu();
        this.initDarkMode();
        this.initScrollToTop();
        this.initLazyLoading();
        this.initSwipers();
        this.initWooCommerce();
    },

    // Mobile menu toggle
    initMobileMenu() {
        const menuToggle = document.querySelector('.menu-toggle');
        const primaryMenu = document.querySelector('.primary-menu');

        if (menuToggle && primaryMenu) {
            menuToggle.addEventListener('click', () => {
                const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
                menuToggle.setAttribute('aria-expanded', !isExpanded);
                primaryMenu.classList.toggle('is-open');
            });
        }
    },

    // Dark mode toggle
    initDarkMode() {
        const darkModeToggle = document.querySelector('[data-dark-mode-toggle]');
        const html = document.documentElement;

        // Check for saved theme preference or default to light mode
        const currentTheme = localStorage.getItem('theme') || 'light';
        html.classList.toggle('dark', currentTheme === 'dark');

        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', () => {
                const isDark = html.classList.contains('dark');
                html.classList.toggle('dark', !isDark);
                localStorage.setItem('theme', !isDark ? 'dark' : 'light');
            });
        }
    },

    // Scroll to top button
    initScrollToTop() {
        const scrollToTopBtn = document.querySelector('[data-scroll-to-top]');
        
        if (scrollToTopBtn) {
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    scrollToTopBtn.style.display = 'flex';
                } else {
                    scrollToTopBtn.style.display = 'none';
                }
            });

            scrollToTopBtn.addEventListener('click', (e) => {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    },

    // Lazy loading for images
    initLazyLoading() {
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
    },

    // Initialize Swiper carousels
    initSwipers() {
        // Hero slider
        const heroSlider = document.querySelector('.hero-slider');
        if (heroSlider) {
            new Swiper(heroSlider, {
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

        // Product sliders
        document.querySelectorAll('.product-slider').forEach(slider => {
            new Swiper(slider, {
                loop: false,
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: slider.querySelector('.swiper-button-next'),
                    prevEl: slider.querySelector('.swiper-button-prev'),
                },
                breakpoints: {
                    640: { slidesPerView: 2 },
                    768: { slidesPerView: 3 },
                    1024: { slidesPerView: 4 },
                },
            });
        });

        // Testimonial slider
        const testimonialSlider = document.querySelector('.testimonial-slider');
        if (testimonialSlider) {
            new Swiper(testimonialSlider, {
                loop: true,
                autoplay: {
                    delay: 4000,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: { slidesPerView: 2 },
                    1024: { slidesPerView: 3 },
                },
            });
        }
    },

    // WooCommerce specific functionality
    initWooCommerce() {
        // Quick view functionality
        document.querySelectorAll('[data-quick-view]').forEach(btn => {
            btn.addEventListener('click', this.handleQuickView);
        });

        // Add to cart AJAX
        document.querySelectorAll('.ajax-add-to-cart').forEach(btn => {
            btn.addEventListener('click', this.handleAddToCart);
        });

        // Wishlist toggle
        document.querySelectorAll('[data-wishlist-toggle]').forEach(btn => {
            btn.addEventListener('click', this.handleWishlistToggle);
        });
    },

    // Quick view handler
    handleQuickView(e) {
        e.preventDefault();
        const productId = this.dataset.productId;
        
        // Show loading state
        this.classList.add('is-loading');

        // AJAX request to get product data
        fetch(`${aqualuxe.ajaxurl}?action=quick_view_product&product_id=${productId}&nonce=${aqualuxe.nonce}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Open modal with product data
                    AquaLuxe.openModal(data.data.html);
                }
            })
            .catch(error => {
                console.error('Quick view error:', error);
            })
            .finally(() => {
                this.classList.remove('is-loading');
            });
    },

    // Add to cart handler
    handleAddToCart(e) {
        e.preventDefault();
        const form = this.closest('form');
        const formData = new FormData(form);

        // Show loading state
        this.classList.add('is-loading');

        // AJAX add to cart
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count
                this.updateCartCount();
                // Show success message
                this.showNotification('Product added to cart!', 'success');
            }
        })
        .catch(error => {
            console.error('Add to cart error:', error);
        })
        .finally(() => {
            this.classList.remove('is-loading');
        });
    },

    // Wishlist toggle handler
    handleWishlistToggle(e) {
        e.preventDefault();
        const productId = this.dataset.productId;
        
        fetch(`${aqualuxe.ajaxurl}?action=toggle_wishlist&product_id=${productId}&nonce=${aqualuxe.nonce}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.classList.toggle('is-active');
                    this.showNotification(data.data.message, 'success');
                }
            })
            .catch(error => {
                console.error('Wishlist error:', error);
            });
    },

    // Utility functions
    openModal(content) {
        // Create and show modal
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
        modal.innerHTML = `
            <div class="bg-white dark:bg-gray-800 rounded-lg max-w-4xl max-h-screen overflow-y-auto m-4">
                <div class="p-6">
                    <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" data-close-modal>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    ${content}
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close modal handlers
        modal.querySelector('[data-close-modal]').addEventListener('click', () => {
            document.body.removeChild(modal);
        });
        
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                document.body.removeChild(modal);
            }
        });
    },

    updateCartCount() {
        const cartCount = document.querySelector('.cart-count');
        if (cartCount) {
            fetch(`${aqualuxe.ajaxurl}?action=get_cart_count&nonce=${aqualuxe.nonce}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cartCount.textContent = data.data.count;
                    }
                });
        }
    },

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white ${
            type === 'success' ? 'bg-green-600' : 
            type === 'error' ? 'bg-red-600' : 'bg-blue-600'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 3000);
    }
};

// Initialize when DOM is ready
domReady(() => {
    AquaLuxe.init();
});

// Export for global access
window.AquaLuxe = AquaLuxe;