/**
 * WooCommerce Module JavaScript
 * 
 * @package AquaLuxe
 */

(function($) {
    'use strict';

    class WooCommerceHandler {
        constructor() {
            this.init();
        }

        init() {
            this.bindEvents();
            this.setupCartUpdates();
            this.setupQuickView();
        }

        bindEvents() {
            $(document).on('click', '.quick-view-btn', this.openQuickView.bind(this));
            $(document).on('click', '.modal-close', this.closeModal.bind(this));
            $(document).on('click', '.modal-backdrop', this.closeModal.bind(this));
        }

        setupCartUpdates() {
            $(document.body).on('added_to_cart', function(event, fragments, cart_hash, button) {
                // Update cart count in header
                const cartCount = $('.cart-count');
                if (fragments && fragments['.cart-count']) {
                    cartCount.replaceWith(fragments['.cart-count']);
                }
                
                // Show success message
                if (typeof aqualuxe_woocommerce !== 'undefined') {
                    this.showNotice(aqualuxe_woocommerce.strings.added_to_cart, 'success');
                }
            }.bind(this));
        }

        setupQuickView() {
            // Initialize quick view modal if it doesn't exist
            if (!$('#quick-view-modal').length) {
                $('body').append('<div id="quick-view-modal" class="modal quick-view-modal"><div class="modal-backdrop"></div><div class="modal-container"><div class="modal-content"><div class="modal-header"><h3 class="modal-title">Quick View</h3><button class="modal-close">&times;</button></div><div class="modal-body"></div></div></div></div>');
            }
        }

        openQuickView(e) {
            e.preventDefault();
            
            const button = $(e.currentTarget);
            const productId = button.data('product-id');
            
            if (!productId) return;
            
            // Show loading
            this.showModal('#quick-view-modal');
            $('#quick-view-modal .modal-body').html('<div class="loading">Loading...</div>');
            
            // AJAX request
            $.ajax({
                url: aqualuxe_woocommerce.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_quick_view',
                    product_id: productId,
                    nonce: aqualuxe_woocommerce.nonce
                },
                success: (response) => {
                    if (response.success) {
                        $('#quick-view-modal .modal-body').html(response.data.html);
                        // Initialize variation forms if present
                        $('#quick-view-modal .variations_form').wc_variation_form();
                    } else {
                        this.showError(response.data.message);
                        this.closeModal();
                    }
                },
                error: () => {
                    this.showError('Quick view failed. Please try again.');
                    this.closeModal();
                }
            });
        }

        showModal(selector) {
            $(selector).addClass('active');
            $('body').addClass('modal-open');
        }

        closeModal(e) {
            if (e && !$(e.target).hasClass('modal-close') && !$(e.target).hasClass('modal-backdrop')) {
                return;
            }
            
            $('.modal').removeClass('active');
            $('body').removeClass('modal-open');
        }

        showNotice(message, type = 'info') {
            const notice = $('<div class="wc-notice wc-notice-' + type + '">' + message + '</div>');
            
            // Add to notices container or create one
            let container = $('.wc-notices-wrapper');
            if (!container.length) {
                container = $('<div class="wc-notices-wrapper"></div>');
                $('main').prepend(container);
            }
            
            container.append(notice);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                notice.fadeOut(() => notice.remove());
            }, 5000);
        }

        showError(message) {
            this.showNotice(message, 'error');
        }
    }

    // Initialize when ready
    $(document).ready(function() {
        if (typeof aqualuxe_woocommerce !== 'undefined') {
            new WooCommerceHandler();
        }
    });

})(jQuery);