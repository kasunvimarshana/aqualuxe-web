/**
 * Accessibility Module
 * Handles accessibility features
 * 
 * @package AquaLuxe
 */

(function($) {
    'use strict';

    class AccessibilityHandler {
        constructor() {
            this.init();
        }

        init() {
            this.setupSkipLinks();
            this.setupFocusManagement();
            this.setupKeyboardNavigation();
            this.setupReducedMotion();
        }

        setupSkipLinks() {
            $('.skip-link').on('click', function(e) {
                const target = $($(this).attr('href'));
                if (target.length) {
                    target.focus();
                    // Ensure element is focusable
                    if (!target.attr('tabindex')) {
                        target.attr('tabindex', '-1');
                    }
                }
            });
        }

        setupFocusManagement() {
            // Outline for keyboard users only
            $('body').on('mousedown', function() {
                $('body').addClass('using-mouse');
            });

            $('body').on('keydown', function(e) {
                if (e.key === 'Tab') {
                    $('body').removeClass('using-mouse');
                }
            });
        }

        setupKeyboardNavigation() {
            // Trap focus in modals
            $(document).on('keydown', '.modal.active', this.trapFocus.bind(this));
        }

        trapFocus(e) {
            if (e.key !== 'Tab') return;

            const modal = $(e.currentTarget);
            const focusableElements = modal.find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            const firstElement = focusableElements.first();
            const lastElement = focusableElements.last();

            if (e.shiftKey) {
                if (document.activeElement === firstElement[0]) {
                    lastElement.focus();
                    e.preventDefault();
                }
            } else {
                if (document.activeElement === lastElement[0]) {
                    firstElement.focus();
                    e.preventDefault();
                }
            }
        }

        setupReducedMotion() {
            // Respect prefers-reduced-motion
            if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                document.documentElement.classList.add('motion-reduce');
            }
        }
    }

    // Initialize accessibility handler
    new AccessibilityHandler();

})(jQuery);