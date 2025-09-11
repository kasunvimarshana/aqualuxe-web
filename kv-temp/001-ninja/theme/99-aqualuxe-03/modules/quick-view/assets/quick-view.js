/**
 * AquaLuxe Quick View Module
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Quick View functionality
     */
    class AquaLuxeQuickView {
        constructor() {
            this.modal = $('#aqualuxe-quick-view-modal');
            this.init();
        }

        /**
         * Initialize quick view functionality
         */
        init() {
            this.bindEvents();
        }

        /**
         * Bind events
         */
        bindEvents() {
            // Quick view button clicks
            $(document).on('click', '.aqualuxe-quick-view-button', this.handleQuickView.bind(this));
            
            // Modal close events
            $(document).on('click', '.aqualuxe-modal-close, .aqualuxe-modal-overlay', this.closeModal.bind(this));
            
            // ESC key to close modal
            $(document).on('keydown', this.handleKeydown.bind(this));
            
            // Gallery image clicks
            $(document).on('click', '.quick-view-gallery-image', this.handleGalleryClick.bind(this));
            
            // Add to cart form submission
            $(document).on('submit', '.quick-view-cart form.cart', this.handleAddToCart.bind(this));
        }

        /**
         * Handle quick view button click
         */
        handleQuickView(e) {
            e.preventDefault();
            
            const $button = $(e.currentTarget);
            const productId = $button.data('product-id');
            
            if (!productId) return;
            
            this.loadProduct(productId);
        }

        /**
         * Load product data via AJAX
         */
        loadProduct(productId) {
            this.showModal();
            this.showLoading();

            $.ajax({
                url: aqualuxe_quick_view.ajax_url,
                type: 'POST',
                data: {
                    action: 'quick_view_product',
                    product_id: productId,
                    nonce: aqualuxe_quick_view.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.displayProduct(response.data);
                    } else {
                        this.showError(response.data || aqualuxe_quick_view.error_text);
                    }
                },
                error: () => {
                    this.showError(aqualuxe_quick_view.error_text);
                }
            });
        }

        /**
         * Display product in modal
         */
        displayProduct(data) {
            const $content = this.modal.find('.quick-view-content');
            $content.html(data.content);
            
            this.hideLoading();
            this.initProductFeatures();
            
            // Focus management for accessibility
            this.modal.find('.quick-view-title a').focus();
        }

        /**
         * Initialize product-specific features
         */
        initProductFeatures() {
            // Initialize quantity inputs
            this.initQuantityInputs();
            
            // Initialize gallery
            this.initGallery();
            
            // Initialize variations if present
            this.initVariations();
        }

        /**
         * Initialize quantity inputs
         */
        initQuantityInputs() {
            const $quantityInput = this.modal.find('input[name="quantity"]');
            
            if ($quantityInput.length) {
                // Add increment/decrement buttons
                const $wrapper = $quantityInput.parent();
                
                if (!$wrapper.find('.qty-btn').length) {
                    $quantityInput.before('<button type="button" class="qty-btn qty-minus">-</button>');
                    $quantityInput.after('<button type="button" class="qty-btn qty-plus">+</button>');
                }
                
                // Handle quantity changes
                $(document).off('click.qty').on('click.qty', '.qty-minus', (e) => {
                    e.preventDefault();
                    const $input = $(e.target).siblings('input[name="quantity"]');
                    const currentVal = parseInt($input.val()) || 1;
                    const minVal = parseInt($input.attr('min')) || 1;
                    
                    if (currentVal > minVal) {
                        $input.val(currentVal - 1);
                    }
                });
                
                $(document).off('click.qty').on('click.qty', '.qty-plus', (e) => {
                    e.preventDefault();
                    const $input = $(e.target).siblings('input[name="quantity"]');
                    const currentVal = parseInt($input.val()) || 1;
                    const maxVal = parseInt($input.attr('max')) || 999;
                    
                    if (currentVal < maxVal) {
                        $input.val(currentVal + 1);
                    }
                });
            }
        }

        /**
         * Initialize gallery
         */
        initGallery() {
            const $mainImage = this.modal.find('.quick-view-main-image img');
            const $galleryImages = this.modal.find('.quick-view-gallery-image');
            
            if ($galleryImages.length) {
                $galleryImages.on('click', (e) => {
                    e.preventDefault();
                    const $clicked = $(e.currentTarget);
                    const newSrc = $clicked.data('large-image') || $clicked.attr('src');
                    
                    if (newSrc && $mainImage.length) {
                        $mainImage.attr('src', newSrc);
                        
                        // Update active state
                        $galleryImages.removeClass('active');
                        $clicked.addClass('active');
                    }
                });
                
                // Set first image as active
                $galleryImages.first().addClass('active');
            }
        }

        /**
         * Initialize variations (if WooCommerce variations are present)
         */
        initVariations() {
            const $variationsForm = this.modal.find('.variations_form');
            
            if ($variationsForm.length && typeof $variationsForm.wc_variation_form === 'function') {
                $variationsForm.wc_variation_form();
            }
        }

        /**
         * Handle gallery image click
         */
        handleGalleryClick(e) {
            e.preventDefault();
            // This is handled in initGallery
        }

        /**
         * Handle add to cart form submission
         */
        handleAddToCart(e) {
            e.preventDefault();
            
            const $form = $(e.currentTarget);
            const $button = $form.find('button[type="submit"]');
            const formData = new FormData($form[0]);
            
            $button.addClass('loading').prop('disabled', true);
            
            $.ajax({
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    if (response.error) {
                        this.showNotification(response.error_message || 'Error adding to cart', 'error');
                    } else {
                        this.showNotification('Product added to cart!', 'success');
                        
                        // Update cart fragments
                        if (response.fragments) {
                            $.each(response.fragments, function(key, value) {
                                $(key).replaceWith(value);
                            });
                        }
                        
                        // Trigger cart update event
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash]);
                        
                        // Close modal after short delay
                        setTimeout(() => {
                            this.closeModal();
                        }, 1500);
                    }
                },
                error: () => {
                    this.showNotification('Error adding to cart', 'error');
                },
                complete: () => {
                    $button.removeClass('loading').prop('disabled', false);
                }
            });
        }

        /**
         * Show modal
         */
        showModal() {
            this.modal.show().attr('aria-hidden', 'false');
            $('body').addClass('modal-open');
            
            // Trap focus in modal
            this.trapFocus();
        }

        /**
         * Close modal
         */
        closeModal() {
            this.modal.hide().attr('aria-hidden', 'true');
            $('body').removeClass('modal-open');
            
            // Clear content
            this.modal.find('.quick-view-content').empty();
            
            // Remove focus trap
            this.removeFocusTrap();
        }

        /**
         * Show loading state
         */
        showLoading() {
            this.modal.find('.quick-view-loading').show();
            this.modal.find('.quick-view-content').hide();
        }

        /**
         * Hide loading state
         */
        hideLoading() {
            this.modal.find('.quick-view-loading').hide();
            this.modal.find('.quick-view-content').show();
        }

        /**
         * Show error message
         */
        showError(message) {
            this.hideLoading();
            this.modal.find('.quick-view-content').html(`
                <div class="quick-view-error">
                    <p>${message}</p>
                    <button class="button" onclick="jQuery('#aqualuxe-quick-view-modal .aqualuxe-modal-close').click()">
                        Close
                    </button>
                </div>
            `);
        }

        /**
         * Handle keydown events
         */
        handleKeydown(e) {
            if (e.key === 'Escape' && this.modal.is(':visible')) {
                this.closeModal();
            }
        }

        /**
         * Trap focus in modal
         */
        trapFocus() {
            const $focusableElements = this.modal.find('a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])');
            const $firstElement = $focusableElements.first();
            const $lastElement = $focusableElements.last();

            $(document).on('keydown.modal-focus', (e) => {
                if (e.key === 'Tab') {
                    if (e.shiftKey && $(document.activeElement).is($firstElement)) {
                        e.preventDefault();
                        $lastElement.focus();
                    } else if (!e.shiftKey && $(document.activeElement).is($lastElement)) {
                        e.preventDefault();
                        $firstElement.focus();
                    }
                }
            });
        }

        /**
         * Remove focus trap
         */
        removeFocusTrap() {
            $(document).off('keydown.modal-focus');
        }

        /**
         * Show notification
         */
        showNotification(message, type = 'info') {
            // Remove existing notifications
            $('.aqualuxe-notification').remove();
            
            const $notification = $(`
                <div class="aqualuxe-notification aqualuxe-notification--${type}">
                    <div class="aqualuxe-notification__content">
                        <span class="aqualuxe-notification__message">${message}</span>
                        <button class="aqualuxe-notification__close" aria-label="Close">×</button>
                    </div>
                </div>
            `);
            
            $('body').append($notification);
            
            // Auto-hide after 3 seconds
            setTimeout(() => {
                $notification.fadeOut(() => {
                    $notification.remove();
                });
            }, 3000);
            
            // Manual close
            $notification.find('.aqualuxe-notification__close').on('click', () => {
                $notification.fadeOut(() => {
                    $notification.remove();
                });
            });
        }
    }

    // Initialize when document is ready
    $(document).ready(() => {
        if (typeof aqualuxe_quick_view !== 'undefined') {
            window.AquaLuxeQuickView = new AquaLuxeQuickView();
        }
    });

})(jQuery);