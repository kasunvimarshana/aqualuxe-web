<?php
/**
 * Quick View Modal
 *
 * @package AquaLuxe
 * @subpackage Modules/WooCommerce
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get module
$module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];

// Check if quick view is enabled
if (!$module->get_option('quick_view', true)) {
    return;
}
?>

<div id="quick-view-modal" class="quick-view-modal" x-data="{ isOpen: false, productId: null }" x-show="isOpen" x-cloak @click.away="isOpen = false">
    <div class="quick-view-modal-container">
        <button class="quick-view-modal-close" @click="isOpen = false">
            <span class="screen-reader-text"><?php esc_html_e('Close quick view', 'aqualuxe'); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg>
        </button>
        <div class="quick-view-modal-content" x-html="quickViewContent">
            <div class="quick-view-loading">
                <span class="loading-spinner"></span>
                <span class="screen-reader-text"><?php esc_html_e('Loading...', 'aqualuxe'); ?></span>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('quickView', () => ({
            isOpen: false,
            productId: null,
            quickViewContent: '<div class="quick-view-loading"><span class="loading-spinner"></span><span class="screen-reader-text"><?php esc_html_e('Loading...', 'aqualuxe'); ?></span></div>',
            
            init() {
                // Listen for quick view button clicks
                document.addEventListener('click', (e) => {
                    if (e.target.closest('.quick-view-button')) {
                        e.preventDefault();
                        const button = e.target.closest('.quick-view-button');
                        this.productId = button.dataset.productId;
                        this.openQuickView();
                    }
                });
            },
            
            openQuickView() {
                this.isOpen = true;
                this.loadQuickViewContent();
            },
            
            loadQuickViewContent() {
                if (!this.productId) {
                    return;
                }
                
                // Reset content to loading state
                this.quickViewContent = '<div class="quick-view-loading"><span class="loading-spinner"></span><span class="screen-reader-text"><?php esc_html_e('Loading...', 'aqualuxe'); ?></span></div>';
                
                // Fetch quick view content via AJAX
                const data = new FormData();
                data.append('action', 'aqualuxe_quick_view');
                data.append('product_id', this.productId);
                data.append('nonce', aqualuxeWooCommerce.nonce);
                
                fetch(aqualuxeWooCommerce.ajaxUrl, {
                    method: 'POST',
                    body: data,
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        this.quickViewContent = response.data.html;
                        
                        // Initialize product gallery and add to cart variations
                        setTimeout(() => {
                            if (typeof wc_add_to_cart_variation_params !== 'undefined') {
                                jQuery('.quick-view-modal .variations_form').wc_variation_form();
                            }
                            
                            jQuery('.quick-view-modal .woocommerce-product-gallery').each(function() {
                                jQuery(this).wc_product_gallery();
                            });
                        }, 100);
                    } else {
                        this.quickViewContent = '<div class="quick-view-error"><?php esc_html_e('Error loading product information. Please try again.', 'aqualuxe'); ?></div>';
                    }
                })
                .catch(error => {
                    this.quickViewContent = '<div class="quick-view-error"><?php esc_html_e('Error loading product information. Please try again.', 'aqualuxe'); ?></div>';
                    console.error('Quick view error:', error);
                });
            }
        }));
    });
</script>