/**
 * AquaLuxe Accessibility Module
 * WCAG 2.1 AA Compliance Implementation
 */

(function($) {
    'use strict';

    const AquaLuxeAccessibility = {
        
        /**
         * Initialize accessibility features
         */
        init() {
            this.setupKeyboardNavigation();
            this.setupSkipLinks();
            this.setupFocusManagement();
            this.setupARIALabels();
            this.setupHighContrast();
            this.setupTextScaling();
            this.setupScreenReaderEnhancements();
            this.setupReducedMotion();
            this.setupFormAccessibility();
            this.setupModalAccessibility();
            this.setupCarouselAccessibility();
            this.addAccessibilityControls();
        },

        /**
         * Setup keyboard navigation
         */
        setupKeyboardNavigation() {
            // Make all interactive elements keyboard accessible
            $('button, a, input, select, textarea, [tabindex]').each(function() {
                if (!$(this).attr('tabindex') && !$(this).is(':disabled')) {
                    $(this).attr('tabindex', '0');
                }
            });

            // Escape key to close modals/dropdowns
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    // Close modals
                    $('.modal.is-active .modal-close').trigger('click');
                    
                    // Close dropdowns
                    $('.dropdown.is-active').removeClass('is-active');
                    
                    // Close mobile menu
                    $('.mobile-menu.is-open').removeClass('is-open');
                }
            });

            // Arrow key navigation for menus
            $('.nav-menu').on('keydown', 'a', function(e) {
                const $items = $(this).closest('.nav-menu').find('a');
                const currentIndex = $items.index(this);
                
                switch(e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        $items.eq((currentIndex + 1) % $items.length).focus();
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        $items.eq((currentIndex - 1 + $items.length) % $items.length).focus();
                        break;
                    case 'Home':
                        e.preventDefault();
                        $items.first().focus();
                        break;
                    case 'End':
                        e.preventDefault();
                        $items.last().focus();
                        break;
                }
            });

            // Tab navigation for product grids
            $('.products-grid').on('keydown', '.product-item', function(e) {
                const $items = $(this).closest('.products-grid').find('.product-item');
                const currentIndex = $items.index(this);
                const columns = parseInt($(this).closest('.products-grid').attr('data-columns') || 3);
                
                switch(e.key) {
                    case 'ArrowRight':
                        e.preventDefault();
                        $items.eq((currentIndex + 1) % $items.length).find('a, button').first().focus();
                        break;
                    case 'ArrowLeft':
                        e.preventDefault();
                        $items.eq((currentIndex - 1 + $items.length) % $items.length).find('a, button').first().focus();
                        break;
                    case 'ArrowDown':
                        e.preventDefault();
                        const nextRowIndex = currentIndex + columns;
                        if (nextRowIndex < $items.length) {
                            $items.eq(nextRowIndex).find('a, button').first().focus();
                        }
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        const prevRowIndex = currentIndex - columns;
                        if (prevRowIndex >= 0) {
                            $items.eq(prevRowIndex).find('a, button').first().focus();
                        }
                        break;
                }
            });
        },

        /**
         * Setup skip links for screen readers
         */
        setupSkipLinks() {
            const skipLinks = `
                <div class="skip-links sr-only">
                    <a href="#main-content" class="skip-link">Skip to main content</a>
                    <a href="#navigation" class="skip-link">Skip to navigation</a>
                    <a href="#search" class="skip-link">Skip to search</a>
                    <a href="#footer" class="skip-link">Skip to footer</a>
                </div>
            `;
            
            $('body').prepend(skipLinks);
            
            // Show skip links on focus
            $('.skip-link').on('focus', function() {
                $(this).removeClass('sr-only').addClass('skip-link-visible');
            }).on('blur', function() {
                $(this).addClass('sr-only').removeClass('skip-link-visible');
            });
        },

        /**
         * Focus management
         */
        setupFocusManagement() {
            // Trap focus in modals
            $(document).on('keydown', '.modal.is-active', function(e) {
                if (e.key === 'Tab') {
                    const $modal = $(this);
                    const $focusableElements = $modal.find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                    const $firstElement = $focusableElements.first();
                    const $lastElement = $focusableElements.last();
                    
                    if (e.shiftKey) {
                        if (document.activeElement === $firstElement[0]) {
                            e.preventDefault();
                            $lastElement.focus();
                        }
                    } else {
                        if (document.activeElement === $lastElement[0]) {
                            e.preventDefault();
                            $firstElement.focus();
                        }
                    }
                }
            });

            // Focus visible indicator
            $('*').on('focus', function() {
                $(this).addClass('has-focus');
            }).on('blur', function() {
                $(this).removeClass('has-focus');
            });

            // Return focus after modal close
            let lastFocusedElement = null;
            
            $(document).on('click', '[data-modal-trigger]', function() {
                lastFocusedElement = this;
            });
            
            $(document).on('click', '.modal-close', function() {
                if (lastFocusedElement) {
                    $(lastFocusedElement).focus();
                    lastFocusedElement = null;
                }
            });
        },

        /**
         * Enhance ARIA labels and descriptions
         */
        setupARIALabels() {
            // Add ARIA labels to buttons without text
            $('button:not([aria-label])').each(function() {
                const $button = $(this);
                const text = $button.text().trim();
                
                if (!text) {
                    const iconClass = $button.find('i').attr('class') || '';
                    if (iconClass.includes('search')) {
                        $button.attr('aria-label', 'Search');
                    } else if (iconClass.includes('menu')) {
                        $button.attr('aria-label', 'Menu');
                    } else if (iconClass.includes('close')) {
                        $button.attr('aria-label', 'Close');
                    } else if (iconClass.includes('cart')) {
                        $button.attr('aria-label', 'Shopping cart');
                    } else {
                        $button.attr('aria-label', 'Button');
                    }
                }
            });

            // Add ARIA labels to form inputs
            $('input:not([aria-label]), select:not([aria-label]), textarea:not([aria-label])').each(function() {
                const $input = $(this);
                const $label = $('label[for="' + $input.attr('id') + '"]');
                
                if ($label.length === 0) {
                    const placeholder = $input.attr('placeholder');
                    if (placeholder) {
                        $input.attr('aria-label', placeholder);
                    }
                }
            });

            // Add ARIA expanded for dropdowns
            $('[data-dropdown]').attr('aria-expanded', 'false');
            
            $(document).on('click', '[data-dropdown]', function() {
                const isExpanded = $(this).attr('aria-expanded') === 'true';
                $(this).attr('aria-expanded', !isExpanded);
            });

            // Add ARIA live regions for dynamic content
            $('body').append('<div id="aria-live-polite" aria-live="polite" aria-atomic="true" class="sr-only"></div>');
            $('body').append('<div id="aria-live-assertive" aria-live="assertive" aria-atomic="true" class="sr-only"></div>');
        },

        /**
         * High contrast mode
         */
        setupHighContrast() {
            // Check if user prefers high contrast
            if (window.matchMedia('(prefers-contrast: high)').matches) {
                document.body.classList.add('high-contrast');
            }

            // Add high contrast toggle
            const contrastToggle = `
                <button type="button" class="accessibility-toggle contrast-toggle" aria-label="Toggle high contrast">
                    <span class="toggle-icon">⚫</span>
                    <span class="toggle-text">High Contrast</span>
                </button>
            `;
            
            $('.accessibility-controls').append(contrastToggle);
            
            $(document).on('click', '.contrast-toggle', function() {
                document.body.classList.toggle('high-contrast');
                const isHighContrast = document.body.classList.contains('high-contrast');
                
                localStorage.setItem('aqualuxe-high-contrast', isHighContrast);
                this.announceChange('High contrast ' + (isHighContrast ? 'enabled' : 'disabled'));
            });

            // Restore preference
            if (localStorage.getItem('aqualuxe-high-contrast') === 'true') {
                document.body.classList.add('high-contrast');
            }
        },

        /**
         * Text scaling
         */
        setupTextScaling() {
            const textSizeControls = `
                <div class="text-size-controls">
                    <button type="button" class="accessibility-toggle text-size-decrease" aria-label="Decrease text size">A-</button>
                    <button type="button" class="accessibility-toggle text-size-reset" aria-label="Reset text size">A</button>
                    <button type="button" class="accessibility-toggle text-size-increase" aria-label="Increase text size">A+</button>
                </div>
            `;
            
            $('.accessibility-controls').append(textSizeControls);
            
            let currentScale = parseFloat(localStorage.getItem('aqualuxe-text-scale') || '1');
            this.applyTextScale(currentScale);
            
            $(document).on('click', '.text-size-increase', () => {
                currentScale = Math.min(currentScale + 0.1, 2);
                this.applyTextScale(currentScale);
                this.announceChange('Text size increased');
            });
            
            $(document).on('click', '.text-size-decrease', () => {
                currentScale = Math.max(currentScale - 0.1, 0.8);
                this.applyTextScale(currentScale);
                this.announceChange('Text size decreased');
            });
            
            $(document).on('click', '.text-size-reset', () => {
                currentScale = 1;
                this.applyTextScale(currentScale);
                this.announceChange('Text size reset to default');
            });
        },

        /**
         * Apply text scale
         */
        applyTextScale(scale) {
            document.documentElement.style.fontSize = (16 * scale) + 'px';
            localStorage.setItem('aqualuxe-text-scale', scale.toString());
        },

        /**
         * Screen reader enhancements
         */
        setupScreenReaderEnhancements() {
            // Add screen reader only descriptions
            $('.product-item').each(function() {
                const $item = $(this);
                const title = $item.find('.product-title').text();
                const price = $item.find('.price').text();
                const rating = $item.find('.rating').attr('data-rating') || 'No rating';
                
                $item.attr('aria-label', `${title}, ${price}, Rating: ${rating}`);
            });

            // Enhance form validation messages
            $('input, select, textarea').on('invalid', function() {
                const $input = $(this);
                const message = this.validationMessage;
                
                $input.attr('aria-describedby', 'validation-message-' + $input.attr('id'));
                
                if (!$('#validation-message-' + $input.attr('id')).length) {
                    $input.after(`<div id="validation-message-${$input.attr('id')}" class="validation-message sr-only" role="alert">${message}</div>`);
                }
            });

            // Announce page changes for SPAs
            const observer = new MutationObserver(() => {
                const newTitle = document.title;
                if (this.lastAnnouncedTitle !== newTitle) {
                    this.announceChange(`Page changed to: ${newTitle}`, 'assertive');
                    this.lastAnnouncedTitle = newTitle;
                }
            });
            
            observer.observe(document.querySelector('title'), { childList: true });
        },

        /**
         * Reduced motion support
         */
        setupReducedMotion() {
            // Check user preference
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                document.body.classList.add('reduced-motion');
            }

            // Add toggle for reduced motion
            const motionToggle = `
                <button type="button" class="accessibility-toggle motion-toggle" aria-label="Toggle reduced motion">
                    <span class="toggle-icon">🎭</span>
                    <span class="toggle-text">Reduced Motion</span>
                </button>
            `;
            
            $('.accessibility-controls').append(motionToggle);
            
            $(document).on('click', '.motion-toggle', function() {
                document.body.classList.toggle('reduced-motion');
                const isReduced = document.body.classList.contains('reduced-motion');
                
                localStorage.setItem('aqualuxe-reduced-motion', isReduced);
                this.announceChange('Reduced motion ' + (isReduced ? 'enabled' : 'disabled'));
            });

            // Restore preference
            if (localStorage.getItem('aqualuxe-reduced-motion') === 'true') {
                document.body.classList.add('reduced-motion');
            }
        },

        /**
         * Form accessibility enhancements
         */
        setupFormAccessibility() {
            // Required field indicators
            $('input[required], select[required], textarea[required]').each(function() {
                const $input = $(this);
                const $label = $('label[for="' + $input.attr('id') + '"]');
                
                if ($label.length && !$label.find('.required-indicator').length) {
                    $label.append(' <span class="required-indicator" aria-label="required">*</span>');
                }
                
                $input.attr('aria-required', 'true');
            });

            // Form submission feedback
            $('form').on('submit', function() {
                const $form = $(this);
                
                // Check for validation errors
                const $invalidInputs = $form.find(':invalid');
                
                if ($invalidInputs.length > 0) {
                    const errorCount = $invalidInputs.length;
                    this.announceChange(`Form has ${errorCount} error${errorCount > 1 ? 's' : ''}. Please review and correct.`, 'assertive');
                    
                    // Focus first invalid input
                    $invalidInputs.first().focus();
                } else {
                    this.announceChange('Form submitted successfully', 'polite');
                }
            });

            // Real-time validation feedback
            $('input, select, textarea').on('blur', function() {
                const $input = $(this);
                
                if (this.checkValidity()) {
                    $input.removeClass('invalid').addClass('valid');
                    $input.attr('aria-invalid', 'false');
                } else {
                    $input.removeClass('valid').addClass('invalid');
                    $input.attr('aria-invalid', 'true');
                }
            });
        },

        /**
         * Modal accessibility
         */
        setupModalAccessibility() {
            // Add ARIA attributes to modals
            $('.modal').each(function() {
                const $modal = $(this);
                $modal.attr({
                    'role': 'dialog',
                    'aria-modal': 'true',
                    'aria-hidden': 'true'
                });
                
                // Add title if not present
                if (!$modal.attr('aria-labelledby')) {
                    const $title = $modal.find('.modal-title, h1, h2, h3').first();
                    if ($title.length) {
                        const titleId = 'modal-title-' + Math.random().toString(36).substr(2, 9);
                        $title.attr('id', titleId);
                        $modal.attr('aria-labelledby', titleId);
                    }
                }
            });

            // Modal open/close events
            $(document).on('click', '[data-modal-trigger]', function() {
                const targetModal = $(this).attr('data-modal-trigger');
                const $modal = $('#' + targetModal);
                
                $modal.attr('aria-hidden', 'false');
                $modal.find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])').first().focus();
            });

            $(document).on('click', '.modal-close', function() {
                const $modal = $(this).closest('.modal');
                $modal.attr('aria-hidden', 'true');
            });
        },

        /**
         * Carousel accessibility
         */
        setupCarouselAccessibility() {
            $('.carousel, .slider').each(function() {
                const $carousel = $(this);
                
                $carousel.attr({
                    'role': 'region',
                    'aria-label': 'Image carousel'
                });

                // Add slide indicators with proper labels
                const $slides = $carousel.find('.slide');
                $slides.each(function(index) {
                    $(this).attr({
                        'role': 'tabpanel',
                        'aria-label': `Slide ${index + 1} of ${$slides.length}`
                    });
                });

                // Add keyboard navigation
                $carousel.on('keydown', function(e) {
                    switch(e.key) {
                        case 'ArrowLeft':
                            e.preventDefault();
                            $carousel.find('.carousel-prev').trigger('click');
                            break;
                        case 'ArrowRight':
                            e.preventDefault();
                            $carousel.find('.carousel-next').trigger('click');
                            break;
                    }
                });
            });
        },

        /**
         * Add accessibility controls panel
         */
        addAccessibilityControls() {
            const controlsPanel = `
                <div class="accessibility-controls" role="region" aria-label="Accessibility controls">
                    <button type="button" class="accessibility-toggle-panel" aria-label="Toggle accessibility options">
                        <span class="icon">♿</span>
                        <span class="text">Accessibility</span>
                    </button>
                    <div class="accessibility-panel" aria-hidden="true">
                        <h3>Accessibility Options</h3>
                        <!-- Controls will be added by other methods -->
                    </div>
                </div>
            `;
            
            $('body').append(controlsPanel);
            
            $(document).on('click', '.accessibility-toggle-panel', function() {
                const $panel = $('.accessibility-panel');
                const isVisible = $panel.attr('aria-hidden') === 'false';
                
                $panel.attr('aria-hidden', isVisible);
                $panel.toggleClass('is-visible');
                $(this).attr('aria-expanded', !isVisible);
            });
        },

        /**
         * Announce changes to screen readers
         */
        announceChange(message, priority = 'polite') {
            const $liveRegion = $('#aria-live-' + priority);
            $liveRegion.text(message);
            
            // Clear after announcement
            setTimeout(() => {
                $liveRegion.text('');
            }, 1000);
        },

        /**
         * Initialize on page load
         */
        lastAnnouncedTitle: document.title
    };

    // Initialize when document is ready
    $(document).ready(function() {
        AquaLuxeAccessibility.init();
    });

    // Expose globally for other scripts
    window.AquaLuxeAccessibility = AquaLuxeAccessibility;

})(jQuery);