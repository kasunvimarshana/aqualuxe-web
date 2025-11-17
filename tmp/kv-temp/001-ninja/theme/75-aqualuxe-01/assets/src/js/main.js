/**
 * AquaLuxe Theme Main JavaScript
 */

class AquaLuxeTheme {
    constructor() {
        this.init();
    }

    init() {
        this.setupDOMReady();
        this.setupEventListeners();
        this.initializeComponents();
    }

    setupDOMReady() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.onDOMReady();
            });
        } else {
            this.onDOMReady();
        }
    }

    onDOMReady() {
        this.initNavigation();
        this.initStickyHeader();
        this.initSmoothScrolling();
        this.initMobileMenu();
        this.initDarkMode();
        this.initLazyLoading();
        this.initAnimations();
        this.initFormValidation();
        this.initTooltips();
        this.initModals();
        this.initCarousels();
    }

    setupEventListeners() {
        // Window events
        window.addEventListener('scroll', this.throttle(this.handleScroll.bind(this), 10));
        window.addEventListener('resize', this.debounce(this.handleResize.bind(this), 250));
        window.addEventListener('load', this.handleLoad.bind(this));

        // Form events
        document.addEventListener('submit', this.handleFormSubmit.bind(this));
        
        // Click events
        document.addEventListener('click', this.handleGlobalClick.bind(this));
        
        // Keyboard events
        document.addEventListener('keydown', this.handleKeydown.bind(this));
    }

    initializeComponents() {
        // Initialize WooCommerce components if available
        if (typeof wc_add_to_cart_params !== 'undefined') {
            this.initWooCommerce();
        }

        // Initialize search functionality
        this.initSearch();

        // Initialize contact forms
        this.initContactForms();

        // Initialize gallery components
        this.initGallery();
    }

    // Navigation
    initNavigation() {
        const nav = document.querySelector('.main-navigation');
        if (!nav) return;

        // Sub-menu toggles for mobile
        const menuItems = nav.querySelectorAll('.menu-item-has-children');
        menuItems.forEach(item => {
            const link = item.querySelector('a');
            const submenu = item.querySelector('.sub-menu');
            
            if (link && submenu) {
                // Add toggle button
                const toggle = document.createElement('button');
                toggle.className = 'submenu-toggle';
                toggle.innerHTML = '<i class="fas fa-chevron-down"></i>';
                toggle.setAttribute('aria-expanded', 'false');
                toggle.setAttribute('aria-label', 'Toggle submenu');
                
                link.parentNode.insertBefore(toggle, link.nextSibling);
                
                toggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                    toggle.setAttribute('aria-expanded', !isExpanded);
                    submenu.style.display = isExpanded ? 'none' : 'block';
                    toggle.classList.toggle('active', !isExpanded);
                });
            }
        });

        // Keyboard navigation
        nav.addEventListener('keydown', this.handleNavKeydown.bind(this));
    }

    initStickyHeader() {
        const header = document.querySelector('.site-header');
        if (!header) return;

        let lastScrollY = window.scrollY;
        const headerHeight = header.offsetHeight;

        const updateHeader = () => {
            const currentScrollY = window.scrollY;
            
            if (currentScrollY > headerHeight) {
                header.classList.add('sticky');
                if (currentScrollY > lastScrollY) {
                    header.classList.add('header-hidden');
                } else {
                    header.classList.remove('header-hidden');
                }
            } else {
                header.classList.remove('sticky', 'header-hidden');
            }
            
            lastScrollY = currentScrollY;
        };

        window.addEventListener('scroll', this.throttle(updateHeader, 10));
    }

    initSmoothScrolling() {
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                const href = anchor.getAttribute('href');
                if (href === '#') return;
                
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    const headerOffset = document.querySelector('.site-header')?.offsetHeight || 0;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset - 20;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    initMobileMenu() {
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileMenu = document.querySelector('.mobile-menu');
        const body = document.body;

        if (!menuToggle || !mobileMenu) return;

        menuToggle.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.contains('active');
            
            if (isOpen) {
                this.closeMobileMenu();
            } else {
                this.openMobileMenu();
            }
        });

        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!mobileMenu.contains(e.target) && !menuToggle.contains(e.target)) {
                this.closeMobileMenu();
            }
        });

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                this.closeMobileMenu();
            }
        });
    }

    openMobileMenu() {
        const mobileMenu = document.querySelector('.mobile-menu');
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        
        mobileMenu.classList.add('active');
        menuToggle.classList.add('active');
        menuToggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    closeMobileMenu() {
        const mobileMenu = document.querySelector('.mobile-menu');
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        
        mobileMenu.classList.remove('active');
        menuToggle.classList.remove('active');
        menuToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    initDarkMode() {
        const darkModeToggle = document.querySelector('.dark-mode-toggle');
        if (!darkModeToggle) return;

        // Check for saved theme preference or default to 'light'
        const currentTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', currentTheme);
        
        if (currentTheme === 'dark') {
            document.documentElement.classList.add('dark');
            darkModeToggle.checked = true;
        }

        darkModeToggle.addEventListener('change', () => {
            if (darkModeToggle.checked) {
                document.documentElement.classList.add('dark');
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                document.documentElement.setAttribute('data-theme', 'light');
                localStorage.setItem('theme', 'light');
            }
        });

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) {
                if (e.matches) {
                    document.documentElement.classList.add('dark');
                    darkModeToggle.checked = true;
                } else {
                    document.documentElement.classList.remove('dark');
                    darkModeToggle.checked = false;
                }
            }
        });
    }

    initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const lazyImages = document.querySelectorAll('img[data-src]');
            const lazyImageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        img.classList.add('lazy-loaded');
                        lazyImageObserver.unobserve(img);
                    }
                });
            });

            lazyImages.forEach(img => {
                lazyImageObserver.observe(img);
            });
        }
    }

    initAnimations() {
        if ('IntersectionObserver' in window) {
            const animatedElements = document.querySelectorAll('.animate-on-scroll');
            const animationObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                    }
                });
            }, { threshold: 0.1 });

            animatedElements.forEach(el => {
                animationObserver.observe(el);
            });
        }
    }

    initFormValidation() {
        const forms = document.querySelectorAll('form[data-validate]');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                }
            });

            // Real-time validation
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('blur', () => {
                    this.validateField(input);
                });
            });
        });
    }

    validateForm(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });

        return isValid;
    }

    validateField(field) {
        const value = field.value.trim();
        const type = field.type;
        const required = field.hasAttribute('required');
        let isValid = true;
        let errorMessage = '';

        // Clear previous errors
        this.clearFieldError(field);

        // Required validation
        if (required && !value) {
            isValid = false;
            errorMessage = 'This field is required.';
        }

        // Type-specific validation
        if (value && type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid email address.';
            }
        }

        if (value && type === 'tel') {
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
            if (!phoneRegex.test(value.replace(/\s/g, ''))) {
                isValid = false;
                errorMessage = 'Please enter a valid phone number.';
            }
        }

        // Show error if invalid
        if (!isValid) {
            this.showFieldError(field, errorMessage);
        }

        return isValid;
    }

    showFieldError(field, message) {
        field.classList.add('error');
        const errorEl = document.createElement('div');
        errorEl.className = 'field-error';
        errorEl.textContent = message;
        field.parentNode.appendChild(errorEl);
    }

    clearFieldError(field) {
        field.classList.remove('error');
        const errorEl = field.parentNode.querySelector('.field-error');
        if (errorEl) {
            errorEl.remove();
        }
    }

    initTooltips() {
        const tooltipElements = document.querySelectorAll('[data-tooltip]');
        tooltipElements.forEach(el => {
            el.addEventListener('mouseenter', this.showTooltip.bind(this));
            el.addEventListener('mouseleave', this.hideTooltip.bind(this));
        });
    }

    showTooltip(e) {
        const element = e.target;
        const text = element.getAttribute('data-tooltip');
        
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.textContent = text;
        
        document.body.appendChild(tooltip);
        
        const rect = element.getBoundingClientRect();
        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
        tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
        
        element._tooltip = tooltip;
    }

    hideTooltip(e) {
        const element = e.target;
        if (element._tooltip) {
            element._tooltip.remove();
            delete element._tooltip;
        }
    }

    initModals() {
        const modalTriggers = document.querySelectorAll('[data-modal]');
        modalTriggers.forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                const modalId = trigger.getAttribute('data-modal');
                this.openModal(modalId);
            });
        });

        // Close modal events
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay') || e.target.classList.contains('modal-close')) {
                this.closeModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModal();
            }
        });
    }

    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Focus management
            const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            if (focusableElements.length > 0) {
                focusableElements[0].focus();
            }
        }
    }

    closeModal() {
        const openModal = document.querySelector('.modal[style*="display: flex"]');
        if (openModal) {
            openModal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    initCarousels() {
        const carousels = document.querySelectorAll('.carousel');
        carousels.forEach(carousel => {
            this.setupCarousel(carousel);
        });
    }

    setupCarousel(carousel) {
        const slides = carousel.querySelectorAll('.carousel-slide');
        const prevBtn = carousel.querySelector('.carousel-prev');
        const nextBtn = carousel.querySelector('.carousel-next');
        const dots = carousel.querySelectorAll('.carousel-dot');
        
        let currentSlide = 0;
        
        const showSlide = (index) => {
            slides.forEach((slide, i) => {
                slide.style.display = i === index ? 'block' : 'none';
            });
            
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
            
            currentSlide = index;
        };
        
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                currentSlide = currentSlide > 0 ? currentSlide - 1 : slides.length - 1;
                showSlide(currentSlide);
            });
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                currentSlide = currentSlide < slides.length - 1 ? currentSlide + 1 : 0;
                showSlide(currentSlide);
            });
        }
        
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                showSlide(index);
            });
        });
        
        // Auto-play if enabled
        if (carousel.hasAttribute('data-autoplay')) {
            const interval = parseInt(carousel.getAttribute('data-autoplay')) || 5000;
            setInterval(() => {
                currentSlide = currentSlide < slides.length - 1 ? currentSlide + 1 : 0;
                showSlide(currentSlide);
            }, interval);
        }
        
        // Initialize first slide
        showSlide(0);
    }

    initWooCommerce() {
        // Quick view functionality
        this.initQuickView();
        
        // Add to cart AJAX
        this.initAjaxAddToCart();
        
        // Wishlist functionality
        this.initWishlist();
        
        // Product image zoom
        this.initProductZoom();
    }

    initQuickView() {
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('quick-view-btn')) {
                e.preventDefault();
                const productId = e.target.getAttribute('data-product-id');
                this.openQuickView(productId);
            }
        });
    }

    openQuickView(productId) {
        // Create and show loading modal
        const modal = this.createQuickViewModal();
        document.body.appendChild(modal);
        
        // Fetch product data
        fetch(`${aqualuxe_ajax.ajax_url}?action=get_quick_view&product_id=${productId}&nonce=${aqualuxe_ajax.nonce}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modal.querySelector('.modal-content').innerHTML = data.data.html;
                    this.openModal(modal.id);
                } else {
                    console.error('Quick view failed:', data.data);
                    modal.remove();
                }
            })
            .catch(error => {
                console.error('Quick view error:', error);
                modal.remove();
            });
    }

    createQuickViewModal() {
        const modal = document.createElement('div');
        modal.className = 'modal quick-view-modal';
        modal.id = 'quick-view-modal';
        modal.innerHTML = `
            <div class="modal-overlay">
                <div class="modal-content">
                    <div class="loading-spinner"></div>
                </div>
                <button class="modal-close" aria-label="Close modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        return modal;
    }

    initAjaxAddToCart() {
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('ajax-add-to-cart')) {
                e.preventDefault();
                this.ajaxAddToCart(e.target);
            }
        });
    }

    ajaxAddToCart(button) {
        const productId = button.getAttribute('data-product-id');
        const quantity = button.getAttribute('data-quantity') || 1;
        
        button.classList.add('loading');
        
        const formData = new FormData();
        formData.append('action', 'woocommerce_ajax_add_to_cart');
        formData.append('product_id', productId);
        formData.append('quantity', quantity);
        formData.append('nonce', aqualuxe_ajax.nonce);
        
        fetch(aqualuxe_ajax.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            button.classList.remove('loading');
            
            if (data.success) {
                // Update cart count
                this.updateCartCount(data.data.cart_count);
                
                // Show success message
                this.showNotification('Product added to cart!', 'success');
                
                // Update button text
                button.textContent = 'Added to Cart';
                button.classList.add('added');
            } else {
                this.showNotification('Failed to add product to cart.', 'error');
            }
        })
        .catch(error => {
            button.classList.remove('loading');
            console.error('Add to cart error:', error);
            this.showNotification('An error occurred. Please try again.', 'error');
        });
    }

    initWishlist() {
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('wishlist-btn')) {
                e.preventDefault();
                this.toggleWishlist(e.target);
            }
        });
    }

    toggleWishlist(button) {
        const productId = button.getAttribute('data-product-id');
        const isAdded = button.classList.contains('added');
        
        button.classList.add('loading');
        
        const formData = new FormData();
        formData.append('action', 'toggle_wishlist');
        formData.append('product_id', productId);
        formData.append('remove', isAdded ? '1' : '0');
        formData.append('nonce', aqualuxe_ajax.nonce);
        
        fetch(aqualuxe_ajax.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            button.classList.remove('loading');
            
            if (data.success) {
                button.classList.toggle('added');
                const message = isAdded ? 'Removed from wishlist' : 'Added to wishlist';
                this.showNotification(message, 'success');
            } else {
                this.showNotification('Failed to update wishlist.', 'error');
            }
        })
        .catch(error => {
            button.classList.remove('loading');
            console.error('Wishlist error:', error);
            this.showNotification('An error occurred. Please try again.', 'error');
        });
    }

    initProductZoom() {
        const productImages = document.querySelectorAll('.product-image-zoom');
        productImages.forEach(img => {
            img.addEventListener('mouseenter', this.enableImageZoom.bind(this));
            img.addEventListener('mouseleave', this.disableImageZoom.bind(this));
        });
    }

    enableImageZoom(e) {
        const img = e.target;
        img.style.cursor = 'zoom-in';
        img.addEventListener('mousemove', this.handleImageZoom);
    }

    disableImageZoom(e) {
        const img = e.target;
        img.style.cursor = 'default';
        img.style.transformOrigin = 'center';
        img.style.transform = 'scale(1)';
        img.removeEventListener('mousemove', this.handleImageZoom);
    }

    handleImageZoom(e) {
        const img = e.target;
        const rect = img.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        
        img.style.transformOrigin = `${x}% ${y}%`;
        img.style.transform = 'scale(2)';
    }

    initSearch() {
        const searchForms = document.querySelectorAll('.search-form');
        searchForms.forEach(form => {
            const input = form.querySelector('input[type="search"]');
            if (input) {
                input.addEventListener('input', this.debounce(this.handleSearchInput.bind(this), 300));
            }
        });
    }

    handleSearchInput(e) {
        const query = e.target.value.trim();
        if (query.length < 3) return;
        
        // Implement live search suggestions here
        this.fetchSearchSuggestions(query);
    }

    fetchSearchSuggestions(query) {
        const formData = new FormData();
        formData.append('action', 'get_search_suggestions');
        formData.append('query', query);
        formData.append('nonce', aqualuxe_ajax.nonce);
        
        fetch(aqualuxe_ajax.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showSearchSuggestions(data.data.suggestions);
            }
        })
        .catch(error => {
            console.error('Search suggestions error:', error);
        });
    }

    showSearchSuggestions(suggestions) {
        // Implementation for showing search suggestions dropdown
        console.log('Search suggestions:', suggestions);
    }

    initContactForms() {
        const contactForms = document.querySelectorAll('.contact-form');
        contactForms.forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleContactFormSubmit(form);
            });
        });
    }

    handleContactFormSubmit(form) {
        if (!this.validateForm(form)) return;
        
        const formData = new FormData(form);
        formData.append('action', 'submit_contact_form');
        formData.append('nonce', aqualuxe_ajax.nonce);
        
        const submitBtn = form.querySelector('[type="submit"]');
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        
        fetch(aqualuxe_ajax.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
            
            if (data.success) {
                this.showNotification('Thank you! Your message has been sent.', 'success');
                form.reset();
            } else {
                this.showNotification(data.data.message || 'Failed to send message.', 'error');
            }
        })
        .catch(error => {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
            console.error('Contact form error:', error);
            this.showNotification('An error occurred. Please try again.', 'error');
        });
    }

    initGallery() {
        const galleries = document.querySelectorAll('.image-gallery');
        galleries.forEach(gallery => {
            const images = gallery.querySelectorAll('img');
            images.forEach((img, index) => {
                img.addEventListener('click', () => {
                    this.openLightbox(images, index);
                });
            });
        });
    }

    openLightbox(images, startIndex) {
        const lightbox = this.createLightbox(images, startIndex);
        document.body.appendChild(lightbox);
        this.openModal(lightbox.id);
    }

    createLightbox(images, startIndex) {
        const lightbox = document.createElement('div');
        lightbox.className = 'modal lightbox-modal';
        lightbox.id = 'lightbox-modal';
        
        const imageElements = Array.from(images).map((img, index) => {
            return `
                <div class="lightbox-slide ${index === startIndex ? 'active' : ''}" data-index="${index}">
                    <img src="${img.src}" alt="${img.alt || ''}" />
                </div>
            `;
        }).join('');
        
        lightbox.innerHTML = `
            <div class="modal-overlay">
                <div class="lightbox-content">
                    <div class="lightbox-slides">
                        ${imageElements}
                    </div>
                    <button class="lightbox-prev" aria-label="Previous image">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="lightbox-next" aria-label="Next image">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <div class="lightbox-counter">
                        <span class="current">${startIndex + 1}</span> / <span class="total">${images.length}</span>
                    </div>
                </div>
                <button class="modal-close" aria-label="Close lightbox">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        this.setupLightboxNavigation(lightbox, images.length);
        
        return lightbox;
    }

    setupLightboxNavigation(lightbox, totalImages) {
        const slides = lightbox.querySelectorAll('.lightbox-slide');
        const prevBtn = lightbox.querySelector('.lightbox-prev');
        const nextBtn = lightbox.querySelector('.lightbox-next');
        const counter = lightbox.querySelector('.lightbox-counter .current');
        
        let currentIndex = 0;
        
        const showSlide = (index) => {
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });
            counter.textContent = index + 1;
            currentIndex = index;
        };
        
        prevBtn.addEventListener('click', () => {
            const newIndex = currentIndex > 0 ? currentIndex - 1 : totalImages - 1;
            showSlide(newIndex);
        });
        
        nextBtn.addEventListener('click', () => {
            const newIndex = currentIndex < totalImages - 1 ? currentIndex + 1 : 0;
            showSlide(newIndex);
        });
        
        // Keyboard navigation
        lightbox.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                prevBtn.click();
            } else if (e.key === 'ArrowRight') {
                nextBtn.click();
            }
        });
    }

    // Event handlers
    handleScroll() {
        // Scroll-based functionality
        this.updateScrollProgress();
    }

    handleResize() {
        // Resize-based functionality
        this.updateMobileMenu();
    }

    handleLoad() {
        // Page load functionality
        this.hideLoader();
    }

    handleFormSubmit(e) {
        const form = e.target;
        if (form.hasAttribute('data-validate')) {
            if (!this.validateForm(form)) {
                e.preventDefault();
            }
        }
    }

    handleGlobalClick(e) {
        // Global click handling
        if (e.target.classList.contains('back-to-top')) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    handleKeydown(e) {
        // Global keyboard handling
        if (e.key === 'Tab') {
            document.body.classList.add('keyboard-navigation');
        }
    }

    handleNavKeydown(e) {
        // Navigation-specific keyboard handling
        const focusableElements = e.currentTarget.querySelectorAll('a, button, [tabindex]:not([tabindex="-1"])');
        const focusedIndex = Array.from(focusableElements).indexOf(document.activeElement);
        
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
            e.preventDefault();
            const nextIndex = focusedIndex < focusableElements.length - 1 ? focusedIndex + 1 : 0;
            focusableElements[nextIndex].focus();
        } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
            e.preventDefault();
            const prevIndex = focusedIndex > 0 ? focusedIndex - 1 : focusableElements.length - 1;
            focusableElements[prevIndex].focus();
        }
    }

    // Utility methods
    updateScrollProgress() {
        const scrollProgress = document.querySelector('.scroll-progress');
        if (scrollProgress) {
            const scrollPercent = (window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100;
            scrollProgress.style.width = Math.min(scrollPercent, 100) + '%';
        }
    }

    updateMobileMenu() {
        const mobileBreakpoint = 1024;
        if (window.innerWidth > mobileBreakpoint) {
            this.closeMobileMenu();
        }
    }

    hideLoader() {
        const loader = document.querySelector('.page-loader');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
            }, 300);
        }
    }

    updateCartCount(count) {
        const cartCounts = document.querySelectorAll('.cart-count');
        cartCounts.forEach(el => {
            el.textContent = count;
            if (count > 0) {
                el.style.display = 'inline';
            } else {
                el.style.display = 'none';
            }
        });
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close" aria-label="Close notification">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            this.hideNotification(notification);
        }, 5000);
        
        // Close button
        notification.querySelector('.notification-close').addEventListener('click', () => {
            this.hideNotification(notification);
        });
    }

    hideNotification(notification) {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }

    // Utility functions
    throttle(func, delay) {
        let timeoutId;
        let lastExecTime = 0;
        
        return function(...args) {
            const currentTime = Date.now();
            
            if (currentTime - lastExecTime > delay) {
                func.apply(this, args);
                lastExecTime = currentTime;
            } else {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => {
                    func.apply(this, args);
                    lastExecTime = Date.now();
                }, delay - (currentTime - lastExecTime));
            }
        };
    }

    debounce(func, delay) {
        let timeoutId;
        
        return function(...args) {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => func.apply(this, args), delay);
        };
    }
}

// Initialize theme when DOM is ready
new AquaLuxeTheme();
