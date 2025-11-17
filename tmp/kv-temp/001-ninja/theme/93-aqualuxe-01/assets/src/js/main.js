// AquaLuxe Main JavaScript
// This file contains the core JavaScript functionality for the theme

(function($) {
    'use strict';

    // Global AquaLuxe object
    window.AquaLuxe = {
        
        // Configuration
        config: {
            breakpoints: {
                xs: 475,
                sm: 640,
                md: 768,
                lg: 1024,
                xl: 1280,
                '2xl': 1536
            },
            animations: {
                duration: 300,
                easing: 'ease-in-out'
            }
        },

        // Initialize the theme
        init: function() {
            this.setupEventListeners();
            this.initializeComponents();
            this.handleAccessibility();
            this.setupPerformance();
            
            // Trigger custom event
            $(document).trigger('aqualuxe:initialized');
        },

        // Set up global event listeners
        setupEventListeners: function() {
            const self = this;
            
            // DOM ready
            $(document).ready(function() {
                self.onDocumentReady();
            });
            
            // Window load
            $(window).on('load', function() {
                self.onWindowLoad();
            });
            
            // Resize handler with debouncing
            let resizeTimer;
            $(window).on('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    self.onWindowResize();
                }, 250);
            });
            
            // Scroll handler with throttling
            let scrollTimer;
            $(window).on('scroll', function() {
                if (!scrollTimer) {
                    scrollTimer = setTimeout(function() {
                        self.onWindowScroll();
                        scrollTimer = null;
                    }, 16); // ~60fps
                }
            });
        },

        // Initialize all components
        initializeComponents: function() {
            this.navigation.init();
            this.search.init();
            this.darkMode.init();
            this.backToTop.init();
            this.lazyLoading.init();
            this.forms.init();
            this.animations.init();
            this.modal.init();
            this.dropdown.init();
            this.tooltip.init();
        },

        // Handle accessibility features
        handleAccessibility: function() {
            // Keyboard navigation
            $(document).on('keydown', function(e) {
                // Escape key handling
                if (e.keyCode === 27) {
                    AquaLuxe.modal.closeAll();
                    AquaLuxe.search.close();
                    AquaLuxe.navigation.closeMobile();
                }
                
                // Tab trapping in modals
                if (e.keyCode === 9) {
                    AquaLuxe.modal.trapFocus(e);
                }
            });
            
            // Skip links
            $('.skip-link').on('focus', function() {
                $(this).removeClass('sr-only');
            }).on('blur', function() {
                $(this).addClass('sr-only');
            });
            
            // ARIA live regions
            this.setupAriaLiveRegions();
        },

        // Setup ARIA live regions for dynamic content
        setupAriaLiveRegions: function() {
            if (!$('#aria-live-polite').length) {
                $('body').append('<div id="aria-live-polite" aria-live="polite" aria-atomic="true" class="sr-only"></div>');
            }
            if (!$('#aria-live-assertive').length) {
                $('body').append('<div id="aria-live-assertive" aria-live="assertive" aria-atomic="true" class="sr-only"></div>');
            }
        },

        // Announce to screen readers
        announce: function(message, priority = 'polite') {
            const region = priority === 'assertive' ? '#aria-live-assertive' : '#aria-live-polite';
            $(region).text(message);
            
            // Clear after announcement
            setTimeout(function() {
                $(region).empty();
            }, 1000);
        },

        // Performance optimizations
        setupPerformance: function() {
            // Preload critical resources
            this.preloadCriticalResources();
            
            // Setup intersection observer for animations
            this.setupIntersectionObserver();
            
            // Debounce resize events
            this.debounceResize();
        },

        // Preload critical resources
        preloadCriticalResources: function() {
            // Preload important images
            const criticalImages = [
                // Add critical image paths here
            ];
            
            criticalImages.forEach(function(src) {
                const img = new Image();
                img.src = src;
            });
        },

        // Setup intersection observer for performance
        setupIntersectionObserver: function() {
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            $(entry.target).addClass('in-view').trigger('aqualuxe:inView');
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '50px'
                });
                
                // Observe elements with animation classes
                $('.animate-on-scroll').each(function() {
                    observer.observe(this);
                });
            }
        },

        // Event handlers
        onDocumentReady: function() {
            $('body').addClass('loaded');
            this.announce('Page loaded', 'polite');
        },

        onWindowLoad: function() {
            $('body').addClass('fully-loaded');
            this.removeLoadingStates();
        },

        onWindowResize: function() {
            this.navigation.handleResize();
            this.updateViewportHeight();
        },

        onWindowScroll: function() {
            this.backToTop.update();
            this.navigation.handleScroll();
        },

        // Remove loading states
        removeLoadingStates: function() {
            $('.loading').removeClass('loading');
            $('.skeleton').removeClass('skeleton');
        },

        // Update CSS custom property for viewport height
        updateViewportHeight: function() {
            const vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        },

        // Get current breakpoint
        getCurrentBreakpoint: function() {
            const width = $(window).width();
            
            if (width >= this.config.breakpoints['2xl']) return '2xl';
            if (width >= this.config.breakpoints.xl) return 'xl';
            if (width >= this.config.breakpoints.lg) return 'lg';
            if (width >= this.config.breakpoints.md) return 'md';
            if (width >= this.config.breakpoints.sm) return 'sm';
            if (width >= this.config.breakpoints.xs) return 'xs';
            return 'base';
        },

        // Check if mobile
        isMobile: function() {
            return $(window).width() < this.config.breakpoints.lg;
        },

        // Debounce function
        debounce: function(func, wait, immediate) {
            let timeout;
            return function executedFunction() {
                const context = this;
                const args = arguments;
                const later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                const callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        },

        // Throttle function
        throttle: function(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        }
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        AquaLuxe.init();
    });

})(jQuery);

// Navigation module
AquaLuxe.navigation = {
    
    isOpen: false,
    
    init: function() {
        this.setupToggle();
        this.setupDropdowns();
        this.setupMegaMenus();
        this.handleKeyboardNavigation();
    },
    
    setupToggle: function() {
        const self = this;
        
        $('[data-toggle="mobile-menu"]').on('click', function(e) {
            e.preventDefault();
            self.toggleMobile();
        });
    },
    
    toggleMobile: function() {
        const $menu = $('#mobile-menu');
        const $toggle = $('[data-toggle="mobile-menu"]');
        
        if (this.isOpen) {
            this.closeMobile();
        } else {
            this.openMobile();
        }
    },
    
    openMobile: function() {
        const $menu = $('#mobile-menu');
        const $toggle = $('[data-toggle="mobile-menu"]');
        
        $menu.removeClass('hidden').addClass('block');
        $toggle.attr('aria-expanded', 'true');
        $toggle.find('.hamburger-icon').addClass('hidden');
        $toggle.find('.close-icon').removeClass('hidden');
        
        $('body').addClass('mobile-menu-open');
        this.isOpen = true;
        
        // Focus management
        $menu.find('a').first().focus();
        
        AquaLuxe.announce('Menu opened', 'polite');
    },
    
    closeMobile: function() {
        const $menu = $('#mobile-menu');
        const $toggle = $('[data-toggle="mobile-menu"]');
        
        $menu.removeClass('block').addClass('hidden');
        $toggle.attr('aria-expanded', 'false');
        $toggle.find('.hamburger-icon').removeClass('hidden');
        $toggle.find('.close-icon').addClass('hidden');
        
        $('body').removeClass('mobile-menu-open');
        this.isOpen = false;
        
        AquaLuxe.announce('Menu closed', 'polite');
    },
    
    setupDropdowns: function() {
        // Dropdown menu handling
        $('.menu-item-has-children > a').on('click', function(e) {
            if (AquaLuxe.isMobile()) {
                e.preventDefault();
                $(this).next('.sub-menu').slideToggle(200);
                $(this).attr('aria-expanded', function(i, attr) {
                    return attr === 'true' ? 'false' : 'true';
                });
            }
        });
    },
    
    setupMegaMenus: function() {
        // Mega menu handling for desktop
        $('.mega-menu-item').hover(
            function() {
                if (!AquaLuxe.isMobile()) {
                    $(this).find('.mega-menu-content').addClass('active');
                }
            },
            function() {
                if (!AquaLuxe.isMobile()) {
                    $(this).find('.mega-menu-content').removeClass('active');
                }
            }
        );
    },
    
    handleKeyboardNavigation: function() {
        // Escape key handling
        $(document).on('keydown', function(e) {
            if (e.keyCode === 27 && AquaLuxe.navigation.isOpen) {
                AquaLuxe.navigation.closeMobile();
            }
        });
    },
    
    handleResize: function() {
        if (!AquaLuxe.isMobile() && this.isOpen) {
            this.closeMobile();
        }
    },
    
    handleScroll: function() {
        // Add scroll effects here if needed
    }
};

// Search module
AquaLuxe.search = {
    
    isOpen: false,
    
    init: function() {
        this.setupToggle();
        this.setupForm();
        this.setupAutocomplete();
    },
    
    setupToggle: function() {
        const self = this;
        
        $('[data-toggle="search"]').on('click', function(e) {
            e.preventDefault();
            self.toggle();
        });
    },
    
    toggle: function() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    },
    
    open: function() {
        const $search = $('#header-search');
        const $input = $search.find('.search-field');
        
        $search.removeClass('hidden');
        $('[data-toggle="search"]').attr('aria-expanded', 'true');
        
        // Focus on input
        setTimeout(function() {
            $input.focus();
        }, 100);
        
        this.isOpen = true;
        AquaLuxe.announce('Search opened', 'polite');
    },
    
    close: function() {
        const $search = $('#header-search');
        
        $search.addClass('hidden');
        $('[data-toggle="search"]').attr('aria-expanded', 'false');
        
        this.isOpen = false;
        AquaLuxe.announce('Search closed', 'polite');
    },
    
    setupForm: function() {
        // Enhanced search form handling
        $('.search-form').on('submit', function(e) {
            const $form = $(this);
            const $input = $form.find('.search-field');
            const query = $input.val().trim();
            
            if (!query) {
                e.preventDefault();
                $input.focus();
                AquaLuxe.announce('Please enter a search term', 'assertive');
            }
        });
    },
    
    setupAutocomplete: function() {
        // Search autocomplete functionality
        const $searchInput = $('.search-field');
        let searchTimeout;
        
        $searchInput.on('input', function() {
            const query = $(this).val().trim();
            
            clearTimeout(searchTimeout);
            
            if (query.length >= 3) {
                searchTimeout = setTimeout(function() {
                    AquaLuxe.search.fetchSuggestions(query);
                }, 300);
            } else {
                AquaLuxe.search.hideSuggestions();
            }
        });
    },
    
    fetchSuggestions: function(query) {
        // AJAX search suggestions
        if (typeof aqualuxe_ajax !== 'undefined') {
            $.ajax({
                url: aqualuxe_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_search_suggestions',
                    query: query,
                    nonce: aqualuxe_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        AquaLuxe.search.showSuggestions(response.data);
                    }
                }
            });
        }
    },
    
    showSuggestions: function(suggestions) {
        // Display search suggestions
        // Implementation depends on your design
    },
    
    hideSuggestions: function() {
        // Hide search suggestions
        $('.search-suggestions').hide();
    }
};

// Dark mode module
AquaLuxe.darkMode = {
    
    init: function() {
        this.setupToggle();
        this.loadPreference();
        this.setupSystemPreference();
    },
    
    setupToggle: function() {
        const self = this;
        
        $('[data-toggle="dark-mode"]').on('click', function(e) {
            e.preventDefault();
            self.toggle();
        });
    },
    
    toggle: function() {
        if (this.isDark()) {
            this.setLight();
        } else {
            this.setDark();
        }
    },
    
    setDark: function() {
        $('html').addClass('dark');
        this.savePreference('dark');
        this.updateToggleIcon();
        AquaLuxe.announce('Dark mode enabled', 'polite');
    },
    
    setLight: function() {
        $('html').removeClass('dark');
        this.savePreference('light');
        this.updateToggleIcon();
        AquaLuxe.announce('Light mode enabled', 'polite');
    },
    
    isDark: function() {
        return $('html').hasClass('dark');
    },
    
    savePreference: function(mode) {
        if (typeof localStorage !== 'undefined') {
            localStorage.setItem('aqualuxe-dark-mode', mode);
        }
    },
    
    loadPreference: function() {
        if (typeof localStorage !== 'undefined') {
            const preference = localStorage.getItem('aqualuxe-dark-mode');
            
            if (preference === 'dark') {
                this.setDark();
            } else if (preference === 'light') {
                this.setLight();
            }
        }
    },
    
    setupSystemPreference: function() {
        // Listen for system dark mode changes
        if (window.matchMedia) {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            
            mediaQuery.addEventListener('change', function(e) {
                if (!localStorage.getItem('aqualuxe-dark-mode')) {
                    if (e.matches) {
                        AquaLuxe.darkMode.setDark();
                    } else {
                        AquaLuxe.darkMode.setLight();
                    }
                }
            });
        }
    },
    
    updateToggleIcon: function() {
        const $toggle = $('[data-toggle="dark-mode"]');
        const $lightIcon = $toggle.find('.dark-mode-icon-light');
        const $darkIcon = $toggle.find('.dark-mode-icon-dark');
        
        if (this.isDark()) {
            $lightIcon.addClass('hidden');
            $darkIcon.removeClass('hidden');
        } else {
            $lightIcon.removeClass('hidden');
            $darkIcon.addClass('hidden');
        }
    }
};

// Back to top module
AquaLuxe.backToTop = {
    
    init: function() {
        this.setupButton();
        this.update();
    },
    
    setupButton: function() {
        const self = this;
        
        $('#back-to-top').on('click', function(e) {
            e.preventDefault();
            self.scrollToTop();
        });
    },
    
    update: function() {
        const $button = $('#back-to-top');
        const scrollTop = $(window).scrollTop();
        
        if (scrollTop > 300) {
            $button.show().removeClass('translate-y-16 opacity-0');
        } else {
            $button.addClass('translate-y-16 opacity-0');
            setTimeout(function() {
                if ($button.hasClass('translate-y-16')) {
                    $button.hide();
                }
            }, 300);
        }
    },
    
    scrollToTop: function() {
        $('html, body').animate({
            scrollTop: 0
        }, 600, 'swing');
        
        AquaLuxe.announce('Scrolled to top', 'polite');
    }
};

// Forms module
AquaLuxe.forms = {
    
    init: function() {
        this.setupValidation();
        this.setupEnhancements();
        this.setupAjaxForms();
    },
    
    setupValidation: function() {
        // Form validation
        $('form[data-validate]').on('submit', function(e) {
            const $form = $(this);
            let isValid = true;
            
            $form.find('[required]').each(function() {
                const $field = $(this);
                
                if (!$field.val().trim()) {
                    isValid = false;
                    AquaLuxe.forms.showFieldError($field, 'This field is required');
                } else {
                    AquaLuxe.forms.clearFieldError($field);
                }
            });
            
            // Email validation
            $form.find('input[type="email"]').each(function() {
                const $field = $(this);
                const email = $field.val().trim();
                
                if (email && !AquaLuxe.forms.isValidEmail(email)) {
                    isValid = false;
                    AquaLuxe.forms.showFieldError($field, 'Please enter a valid email address');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                const firstError = $form.find('.error').first();
                if (firstError.length) {
                    firstError.focus();
                }
            }
        });
    },
    
    setupEnhancements: function() {
        // Floating labels
        $('.floating-label input, .floating-label textarea').on('focus blur', function() {
            const $field = $(this);
            const $label = $field.siblings('label');
            
            if ($field.val() || $field.is(':focus')) {
                $label.addClass('floating');
            } else {
                $label.removeClass('floating');
            }
        });
        
        // Character count
        $('[data-max-length]').on('input', function() {
            const $field = $(this);
            const maxLength = parseInt($field.data('max-length'));
            const currentLength = $field.val().length;
            const remaining = maxLength - currentLength;
            
            let $counter = $field.siblings('.char-counter');
            if (!$counter.length) {
                $counter = $('<div class="char-counter text-sm text-gray-500 mt-1"></div>');
                $field.after($counter);
            }
            
            $counter.text(`${remaining} characters remaining`);
            
            if (remaining < 0) {
                $counter.addClass('text-red-500').removeClass('text-gray-500');
            } else {
                $counter.addClass('text-gray-500').removeClass('text-red-500');
            }
        });
    },
    
    setupAjaxForms: function() {
        // AJAX form submission
        $('form[data-ajax]').on('submit', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $submitButton = $form.find('[type="submit"]');
            const originalText = $submitButton.text();
            
            // Show loading state
            $submitButton.prop('disabled', true).text('Submitting...');
            
            $.ajax({
                url: $form.attr('action') || aqualuxe_ajax.ajax_url,
                type: $form.attr('method') || 'POST',
                data: $form.serialize(),
                success: function(response) {
                    if (response.success) {
                        AquaLuxe.forms.showSuccess($form, response.data.message);
                        $form[0].reset();
                    } else {
                        AquaLuxe.forms.showError($form, response.data.message);
                    }
                },
                error: function() {
                    AquaLuxe.forms.showError($form, 'An error occurred. Please try again.');
                },
                complete: function() {
                    $submitButton.prop('disabled', false).text(originalText);
                }
            });
        });
    },
    
    showFieldError: function($field, message) {
        $field.addClass('error');
        
        let $error = $field.siblings('.field-error');
        if (!$error.length) {
            $error = $('<div class="field-error text-red-500 text-sm mt-1"></div>');
            $field.after($error);
        }
        
        $error.text(message);
    },
    
    clearFieldError: function($field) {
        $field.removeClass('error');
        $field.siblings('.field-error').remove();
    },
    
    showSuccess: function($form, message) {
        const $message = $('<div class="form-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">' + message + '</div>');
        $form.prepend($message);
        
        setTimeout(function() {
            $message.fadeOut();
        }, 5000);
        
        AquaLuxe.announce(message, 'polite');
    },
    
    showError: function($form, message) {
        const $message = $('<div class="form-error bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">' + message + '</div>');
        $form.prepend($message);
        
        setTimeout(function() {
            $message.fadeOut();
        }, 5000);
        
        AquaLuxe.announce(message, 'assertive');
    },
    
    isValidEmail: function(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
};

// Initialize Alpine.js if available
if (typeof Alpine !== 'undefined') {
    Alpine.start();
}
