/**
 * AquaLuxe Theme Quick View
 *
 * Handles product quick view functionality.
 */

(function($) {
    'use strict';

    const AqualuxeQuickView = {
        /**
         * Initialize the quick view functionality
         */
        init: function() {
            this.bindEvents();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            $(document).on('click', '.aqualuxe-quick-view', this.openQuickView);
            $(document).on('aqualuxe_close_quick_view', this.closeQuickView);
            $(document).on('click', '.quick-view-overlay', this.closeQuickView);
            $(document).on('keyup', this.handleEscKey);
        },

        /**
         * Open the quick view modal
         * 
         * @param {Event} e - The click event
         */
        openQuickView: function(e) {
            e.preventDefault();

            const $this = $(this);
            const productId = $this.data('product-id');

            if (!productId) {
                return;
            }

            // Show loading overlay
            AqualuxeQuickView.showLoading();

            // Fetch product data via AJAX
            $.ajax({
                url: aqualuxe_quick_view.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_quick_view',
                    product_id: productId,
                    security: aqualuxe_quick_view.nonce
                },
                success: function(response) {
                    if (response.success) {
                        AqualuxeQuickView.renderQuickView(response.data);
                    } else {
                        AqualuxeQuickView.hideLoading();
                        console.error('Quick view error:', response.data);
                    }
                },
                error: function(xhr, status, error) {
                    AqualuxeQuickView.hideLoading();
                    console.error('Quick view AJAX error:', error);
                }
            });
        },

        /**
         * Render the quick view modal
         * 
         * @param {string} html - The HTML content to render
         */
        renderQuickView: function(html) {
            // Remove any existing quick view
            $('.quick-view-modal-wrapper').remove();

            // Create the modal wrapper and overlay
            $('body').append('<div class="quick-view-modal-wrapper fixed inset-0 z-50 overflow-auto flex items-center justify-center p-4"></div>');
            $('.quick-view-modal-wrapper').append('<div class="quick-view-overlay fixed inset-0 bg-dark-900 bg-opacity-75"></div>');
            $('.quick-view-modal-wrapper').append(html);

            // Initialize quantity buttons and variation forms
            this.initializeFormElements();

            // Add body class
            $('body').addClass('quick-view-open');

            // Hide loading overlay
            this.hideLoading();

            // Trap focus within modal
            this.trapFocus($('.quick-view-modal-wrapper')[0]);

            // Trigger event
            $(document).trigger('aqualuxe_quick_view_opened');
        },

        /**
         * Close the quick view modal
         */
        closeQuickView: function() {
            $('.quick-view-modal-wrapper').fadeOut(200, function() {
                $(this).remove();
                $('body').removeClass('quick-view-open');
                
                // Trigger event
                $(document).trigger('aqualuxe_quick_view_closed');
            });
        },

        /**
         * Handle ESC key press to close modal
         * 
         * @param {Event} e - The keyup event
         */
        handleEscKey: function(e) {
            if (e.keyCode === 27 && $('.quick-view-modal-wrapper').length) {
                AqualuxeQuickView.closeQuickView();
            }
        },

        /**
         * Show loading overlay
         */
        showLoading: function() {
            if ($('.aqualuxe-loading-overlay').length === 0) {
                $('body').append('<div class="aqualuxe-loading-overlay fixed inset-0 z-50 bg-dark-900 bg-opacity-50 flex items-center justify-center"><div class="loading-spinner"></div></div>');
            }
        },

        /**
         * Hide loading overlay
         */
        hideLoading: function() {
            $('.aqualuxe-loading-overlay').fadeOut(200, function() {
                $(this).remove();
            });
        },

        /**
         * Initialize form elements in the quick view
         */
        initializeFormElements: function() {
            // Reinitialize WooCommerce quantity buttons
            $('.quick-view-modal .quantity').each(function() {
                $(this).find('input.qty').attr('type', 'number');
            });

            // Reinitialize WooCommerce variation forms
            if (typeof $.fn.wc_variation_form !== 'undefined') {
                $('.quick-view-modal .variations_form').each(function() {
                    $(this).wc_variation_form();
                });
            }

            // Reinitialize WooCommerce add to cart button
            $(document.body).trigger('init_add_to_cart');
        },

        /**
         * Trap focus within an element
         * 
         * @param {HTMLElement} element - The element to trap focus within
         */
        trapFocus: function(element) {
            if (!element) return;

            const focusableElements = element.querySelectorAll(
                'a[href], button:not([disabled]), textarea:not([disabled]), input[type="text"]:not([disabled]), input[type="radio"]:not([disabled]), input[type="checkbox"]:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])'
            );
            
            if (focusableElements.length === 0) return;
            
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];
            
            // Set focus to the first element
            setTimeout(() => {
                firstElement.focus();
            }, 100);
            
            // Handle tab key to trap focus
            element.addEventListener('keydown', function(e) {
                if (e.key === 'Tab' || e.keyCode === 9) {
                    if (e.shiftKey) {
                        // Shift + Tab
                        if (document.activeElement === firstElement) {
                            lastElement.focus();
                            e.preventDefault();
                        }
                    } else {
                        // Tab
                        if (document.activeElement === lastElement) {
                            firstElement.focus();
                            e.preventDefault();
                        }
                    }
                }
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AqualuxeQuickView.init();
    });

})(jQuery);