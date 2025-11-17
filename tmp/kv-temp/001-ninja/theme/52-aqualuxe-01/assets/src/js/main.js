/**
 * AquaLuxe Theme Main JavaScript
 * 
 * Contains all main functionality for the theme frontend
 */

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    AquaLuxe.init();
});

// Main theme object
const AquaLuxe = {
    // Initialize all components
    init: function() {
        this.setupMobileMenu();
        this.setupStickyHeader();
        this.setupDarkMode();
        this.setupSearchToggle();
        this.setupProductQuickView();
        this.setupQuantityButtons();
        this.setupAccordions();
        this.setupTabs();
        this.setupSliders();
    },

    // Mobile Menu
    setupMobileMenu: function() {
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileMenu = document.querySelector('.mobile-menu');
        const closeMenu = document.querySelector('.close-menu');
        const overlay = document.querySelector('.menu-overlay');
        
        if (!menuToggle || !mobileMenu) return;
        
        menuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            mobileMenu.classList.add('active');
            document.body.classList.add('menu-open');
            if (overlay) overlay.classList.add('active');
        });
        
        if (closeMenu) {
            closeMenu.addEventListener('click', function(e) {
                e.preventDefault();
                mobileMenu.classList.remove('active');
                document.body.classList.remove('menu-open');
                if (overlay) overlay.classList.remove('active');
            });
        }
        
        if (overlay) {
            overlay.addEventListener('click', function() {
                mobileMenu.classList.remove('active');
                document.body.classList.remove('menu-open');
                overlay.classList.remove('active');
            });
        }
        
        // Handle submenu toggles
        const subMenuToggles = document.querySelectorAll('.mobile-menu .menu-item-has-children > a');
        
        subMenuToggles.forEach(function(toggle) {
            // Create submenu toggle button
            const toggleBtn = document.createElement('button');
            toggleBtn.className = 'submenu-toggle';
            toggleBtn.setAttribute('aria-label', 'Toggle Submenu');
            toggleBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
            
            toggle.parentNode.insertBefore(toggleBtn, toggle.nextSibling);
            
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.parentNode;
                const submenu = parent.querySelector('.sub-menu');
                
                if (submenu) {
                    if (submenu.classList.contains('active')) {
                        submenu.classList.remove('active');
                        parent.classList.remove('submenu-open');
                        this.classList.remove('active');
                        
                        // Animate submenu height to 0
                        submenu.style.height = submenu.scrollHeight + 'px';
                        setTimeout(() => {
                            submenu.style.height = '0px';
                        }, 10);
                    } else {
                        submenu.classList.add('active');
                        parent.classList.add('submenu-open');
                        this.classList.add('active');
                        
                        // Animate submenu height
                        submenu.style.height = '0px';
                        setTimeout(() => {
                            submenu.style.height = submenu.scrollHeight + 'px';
                        }, 10);
                    }
                }
            });
        });
    },

    // Sticky Header
    setupStickyHeader: function() {
        const header = document.querySelector('.site-header');
        if (!header) return;
        
        const headerHeight = header.offsetHeight;
        let lastScrollTop = 0;
        
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Add sticky class when scrolled past header height
            if (scrollTop > headerHeight) {
                header.classList.add('sticky');
                document.body.style.paddingTop = headerHeight + 'px';
                
                // Hide on scroll down, show on scroll up
                if (scrollTop > lastScrollTop && scrollTop > headerHeight * 2) {
                    header.classList.add('header-hidden');
                } else {
                    header.classList.remove('header-hidden');
                }
            } else {
                header.classList.remove('sticky');
                document.body.style.paddingTop = '0';
            }
            
            lastScrollTop = scrollTop;
        });
    },

    // Dark Mode Toggle
    setupDarkMode: function() {
        const darkModeToggle = document.querySelector('.dark-mode-toggle');
        if (!darkModeToggle) return;
        
        // Check for saved theme preference or respect OS preference
        const savedTheme = localStorage.getItem('aqualuxe-theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        // Set initial theme
        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
            document.documentElement.classList.add('dark');
            darkModeToggle.classList.add('active');
        }
        
        // Toggle theme on click
        darkModeToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('aqualuxe-theme', 'light');
                darkModeToggle.classList.remove('active');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('aqualuxe-theme', 'dark');
                darkModeToggle.classList.add('active');
            }
        });
    },

    // Search Toggle
    setupSearchToggle: function() {
        const searchToggle = document.querySelector('.search-toggle');
        const searchForm = document.querySelector('.header-search-form');
        const searchClose = document.querySelector('.search-close');
        const searchOverlay = document.querySelector('.search-overlay');
        
        if (!searchToggle || !searchForm) return;
        
        searchToggle.addEventListener('click', function(e) {
            e.preventDefault();
            searchForm.classList.add('active');
            document.body.classList.add('search-open');
            if (searchOverlay) searchOverlay.classList.add('active');
            
            // Focus search input
            setTimeout(() => {
                const searchInput = searchForm.querySelector('input[type="search"]');
                if (searchInput) searchInput.focus();
            }, 100);
        });
        
        if (searchClose) {
            searchClose.addEventListener('click', function(e) {
                e.preventDefault();
                searchForm.classList.remove('active');
                document.body.classList.remove('search-open');
                if (searchOverlay) searchOverlay.classList.remove('active');
            });
        }
        
        if (searchOverlay) {
            searchOverlay.addEventListener('click', function() {
                searchForm.classList.remove('active');
                document.body.classList.remove('search-open');
                searchOverlay.classList.remove('active');
            });
        }
    },

    // Product Quick View
    setupProductQuickView: function() {
        const quickViewButtons = document.querySelectorAll('.quick-view-btn');
        if (!quickViewButtons.length) return;
        
        quickViewButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.getAttribute('data-product-id');
                
                if (!productId) return;
                
                // Show loading overlay
                const loadingOverlay = document.createElement('div');
                loadingOverlay.className = 'loading-overlay';
                loadingOverlay.innerHTML = '<div class="loading-spinner"></div>';
                document.body.appendChild(loadingOverlay);
                
                // Fetch product data via AJAX
                const data = new FormData();
                data.append('action', 'aqualuxe_quick_view');
                data.append('product_id', productId);
                data.append('security', aqualuxeVars.nonce);
                
                fetch(aqualuxeVars.ajaxurl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    body: data
                })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        // Create modal
                        const modal = document.createElement('div');
                        modal.className = 'quick-view-modal';
                        modal.innerHTML = `
                            <div class="quick-view-container">
                                <div class="quick-view-content">
                                    <button class="quick-view-close" aria-label="Close">×</button>
                                    <div class="quick-view-inner">
                                        ${response.data.html}
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        document.body.appendChild(modal);
                        
                        // Add active class after a small delay to trigger animation
                        setTimeout(() => {
                            modal.classList.add('active');
                        }, 10);
                        
                        // Setup close button
                        const closeBtn = modal.querySelector('.quick-view-close');
                        if (closeBtn) {
                            closeBtn.addEventListener('click', function() {
                                modal.classList.remove('active');
                                
                                // Remove modal after animation
                                setTimeout(() => {
                                    document.body.removeChild(modal);
                                }, 300);
                            });
                        }
                        
                        // Close on click outside
                        modal.addEventListener('click', function(e) {
                            if (e.target === modal) {
                                modal.classList.remove('active');
                                
                                // Remove modal after animation
                                setTimeout(() => {
                                    document.body.removeChild(modal);
                                }, 300);
                            }
                        });
                        
                        // Initialize quantity buttons in quick view
                        AquaLuxe.setupQuantityButtons(modal);
                        
                        // Initialize product gallery in quick view if needed
                        // This would depend on your gallery implementation
                    }
                    
                    // Remove loading overlay
                    document.body.removeChild(loadingOverlay);
                })
                .catch(error => {
                    console.error('Quick view error:', error);
                    
                    // Remove loading overlay
                    document.body.removeChild(loadingOverlay);
                });
            });
        });
    },

    // Quantity Buttons
    setupQuantityButtons: function(container = document) {
        const quantities = container.querySelectorAll('.quantity');
        
        quantities.forEach(function(quantity) {
            const input = quantity.querySelector('input.qty');
            if (!input) return;
            
            // Check if buttons already exist
            if (quantity.querySelector('.quantity-button')) return;
            
            // Create minus button
            const minusBtn = document.createElement('button');
            minusBtn.type = 'button';
            minusBtn.className = 'quantity-button minus';
            minusBtn.setAttribute('aria-label', 'Decrease quantity');
            minusBtn.innerHTML = '−';
            
            // Create plus button
            const plusBtn = document.createElement('button');
            plusBtn.type = 'button';
            plusBtn.className = 'quantity-button plus';
            plusBtn.setAttribute('aria-label', 'Increase quantity');
            plusBtn.innerHTML = '+';
            
            // Add buttons to DOM
            input.insertAdjacentElement('beforebegin', minusBtn);
            input.insertAdjacentElement('afterend', plusBtn);
            
            // Add event listeners
            minusBtn.addEventListener('click', function() {
                const currentValue = parseInt(input.value);
                const minValue = parseInt(input.getAttribute('min')) || 1;
                
                if (currentValue > minValue) {
                    input.value = currentValue - 1;
                    
                    // Trigger change event
                    const event = new Event('change', { bubbles: true });
                    input.dispatchEvent(event);
                }
            });
            
            plusBtn.addEventListener('click', function() {
                const currentValue = parseInt(input.value);
                const maxValue = parseInt(input.getAttribute('max')) || 999;
                
                if (currentValue < maxValue) {
                    input.value = currentValue + 1;
                    
                    // Trigger change event
                    const event = new Event('change', { bubbles: true });
                    input.dispatchEvent(event);
                }
            });
        });
    },

    // Accordions
    setupAccordions: function() {
        const accordions = document.querySelectorAll('.accordion');
        
        accordions.forEach(function(accordion) {
            const headers = accordion.querySelectorAll('.accordion-header');
            
            headers.forEach(function(header) {
                header.addEventListener('click', function() {
                    const item = this.parentNode;
                    const content = this.nextElementSibling;
                    
                    if (item.classList.contains('active')) {
                        // Close this item
                        item.classList.remove('active');
                        content.style.maxHeight = '0';
                    } else {
                        // If accordion is set to only allow one open item at a time
                        if (accordion.classList.contains('accordion-single')) {
                            // Close all other items
                            const activeItems = accordion.querySelectorAll('.accordion-item.active');
                            activeItems.forEach(function(activeItem) {
                                activeItem.classList.remove('active');
                                activeItem.querySelector('.accordion-content').style.maxHeight = '0';
                            });
                        }
                        
                        // Open this item
                        item.classList.add('active');
                        content.style.maxHeight = content.scrollHeight + 'px';
                    }
                });
            });
            
            // Open first item by default if accordion has 'accordion-first-open' class
            if (accordion.classList.contains('accordion-first-open')) {
                const firstItem = accordion.querySelector('.accordion-item');
                if (firstItem) {
                    firstItem.classList.add('active');
                    const firstContent = firstItem.querySelector('.accordion-content');
                    if (firstContent) {
                        firstContent.style.maxHeight = firstContent.scrollHeight + 'px';
                    }
                }
            }
        });
    },

    // Tabs
    setupTabs: function() {
        const tabContainers = document.querySelectorAll('.tabs');
        
        tabContainers.forEach(function(container) {
            const tabLinks = container.querySelectorAll('.tab-link');
            const tabContents = container.querySelectorAll('.tab-content');
            
            tabLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const tabId = this.getAttribute('href') || this.getAttribute('data-tab');
                    
                    // Remove active class from all links and contents
                    tabLinks.forEach(link => link.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));
                    
                    // Add active class to current link and content
                    this.classList.add('active');
                    
                    const targetContent = container.querySelector(tabId) || container.querySelector(`[data-tab="${tabId}"]`);
                    if (targetContent) {
                        targetContent.classList.add('active');
                    }
                });
            });
            
            // Activate first tab by default
            const firstLink = tabLinks[0];
            if (firstLink) {
                firstLink.click();
            }
        });
    },

    // Sliders
    setupSliders: function() {
        // This is a placeholder for slider initialization
        // You would typically use a library like Swiper or Splide
        // Example implementation would depend on the library used
        
        // Check if Swiper is available
        if (typeof Swiper !== 'undefined') {
            // Product Gallery Slider
            const productGalleries = document.querySelectorAll('.product-gallery-slider');
            
            productGalleries.forEach(function(gallery) {
                const mainSlider = gallery.querySelector('.gallery-main');
                const thumbsSlider = gallery.querySelector('.gallery-thumbs');
                
                if (mainSlider && thumbsSlider) {
                    // Initialize thumbnail slider
                    const thumbsSwiper = new Swiper(thumbsSlider, {
                        spaceBetween: 10,
                        slidesPerView: 4,
                        freeMode: true,
                        watchSlidesProgress: true,
                        breakpoints: {
                            320: {
                                slidesPerView: 3,
                            },
                            768: {
                                slidesPerView: 4,
                            }
                        }
                    });
                    
                    // Initialize main slider
                    const mainSwiper = new Swiper(mainSlider, {
                        spaceBetween: 0,
                        navigation: {
                            nextEl: gallery.querySelector('.swiper-button-next'),
                            prevEl: gallery.querySelector('.swiper-button-prev'),
                        },
                        thumbs: {
                            swiper: thumbsSwiper,
                        }
                    });
                }
            });
            
            // Featured Products Slider
            const featuredSliders = document.querySelectorAll('.featured-products-slider');
            
            featuredSliders.forEach(function(slider) {
                new Swiper(slider, {
                    spaceBetween: 20,
                    slidesPerView: 1,
                    navigation: {
                        nextEl: slider.querySelector('.swiper-button-next'),
                        prevEl: slider.querySelector('.swiper-button-prev'),
                    },
                    pagination: {
                        el: slider.querySelector('.swiper-pagination'),
                        clickable: true,
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
                        }
                    }
                });
            });
            
            // Testimonials Slider
            const testimonialSliders = document.querySelectorAll('.testimonials-slider');
            
            testimonialSliders.forEach(function(slider) {
                new Swiper(slider, {
                    spaceBetween: 30,
                    slidesPerView: 1,
                    autoplay: {
                        delay: 5000,
                    },
                    pagination: {
                        el: slider.querySelector('.swiper-pagination'),
                        clickable: true,
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 2,
                        },
                        1024: {
                            slidesPerView: 3,
                        }
                    }
                });
            });
        }
    }
};