<?php
/**
 * WooCommerce Quick View functionality for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe WooCommerce Quick View Class
 */
class AquaLuxe_WC_Quick_View {
    /**
     * Constructor
     */
    public function __construct() {
        // Only initialize if quick view is enabled
        if (!get_theme_mod('quick_view', true)) {
            return;
        }
        
        // Add quick view button
        add_action('woocommerce_after_shop_loop_item', array($this, 'quick_view_button'), 15);
        
        // Add quick view modal
        add_action('wp_footer', array($this, 'quick_view_modal'));
        
        // Register AJAX handlers
        add_action('wp_ajax_aqualuxe_load_product_quick_view', array($this, 'load_product_quick_view'));
        add_action('wp_ajax_nopriv_aqualuxe_load_product_quick_view', array($this, 'load_product_quick_view'));
        
        // Enqueue scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    /**
     * Quick view button
     */
    public function quick_view_button() {
        global $product;
        
        echo '<a href="#" class="quick-view-button inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-secondary hover:bg-secondary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary transition-colors duration-300 mr-2" data-product-id="' . esc_attr($product->get_id()) . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
        echo '</svg>';
        echo esc_html__('Quick View', 'aqualuxe');
        echo '</a>';
    }

    /**
     * Quick view modal
     */
    public function quick_view_modal() {
        ?>
        <div id="quick-view-modal" class="quick-view-modal fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
            <div class="modal-content bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-4xl mx-4 transition-colors duration-300">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold product-title"></h3>
                    <button id="quick-view-close" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="quick-view-content grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="product-image"></div>
                    <div class="product-details">
                        <div class="product-price mb-4"></div>
                        <div class="product-rating mb-4"></div>
                        <div class="product-excerpt mb-4"></div>
                        <div class="product-add-to-cart"></div>
                        <div class="product-meta mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="#" class="view-full-details inline-flex items-center text-primary dark:text-primary-dark hover:underline transition-colors duration-300">
                                <?php esc_html_e('View Full Details', 'aqualuxe'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="quick-view-loading hidden flex items-center justify-center p-12">
                    <svg class="animate-spin h-10 w-10 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Load product quick view
     */
    public function load_product_quick_view() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_quick_view_nonce')) {
            wp_send_json_error('Invalid nonce');
            exit;
        }
        
        // Check product ID
        if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
            wp_send_json_error('Invalid product ID');
            exit;
        }
        
        $product_id = absint($_POST['product_id']);
        $product = wc_get_product($product_id);
        
        if (!$product) {
            wp_send_json_error('Product not found');
            exit;
        }
        
        // Prepare response data
        $response = array(
            'title' => $product->get_name(),
            'price_html' => $product->get_price_html(),
            'image' => $this->get_product_image_html($product),
            'rating_html' => $this->get_product_rating_html($product),
            'excerpt' => $product->get_short_description(),
            'add_to_cart_html' => $this->get_add_to_cart_html($product),
            'permalink' => get_permalink($product_id),
        );
        
        wp_send_json_success($response);
        exit;
    }

    /**
     * Get product image HTML
     *
     * @param WC_Product $product Product object.
     * @return string
     */
    private function get_product_image_html($product) {
        $image_id = $product->get_image_id();
        $gallery_image_ids = $product->get_gallery_image_ids();
        
        $html = '<div class="product-images">';
        
        // Main image
        if ($image_id) {
            $html .= '<div class="main-image mb-4">';
            $html .= wp_get_attachment_image($image_id, 'large', false, array('class' => 'w-full rounded-lg'));
            $html .= '</div>';
        } else {
            $html .= '<div class="main-image mb-4">';
            $html .= wc_placeholder_img('large');
            $html .= '</div>';
        }
        
        // Gallery thumbnails (limited to 4)
        if (!empty($gallery_image_ids)) {
            $html .= '<div class="thumbnails grid grid-cols-4 gap-2">';
            
            // Add main image to thumbnails
            if ($image_id) {
                $html .= '<div class="thumbnail cursor-pointer border-2 border-primary rounded-md overflow-hidden">';
                $html .= wp_get_attachment_image($image_id, 'thumbnail', false, array('class' => 'w-full'));
                $html .= '</div>';
            }
            
            // Add gallery images
            $count = $image_id ? 1 : 0;
            foreach ($gallery_image_ids as $gallery_image_id) {
                if ($count >= 4) {
                    break;
                }
                
                $html .= '<div class="thumbnail cursor-pointer border-2 border-transparent hover:border-primary rounded-md overflow-hidden transition-colors duration-300">';
                $html .= wp_get_attachment_image($gallery_image_id, 'thumbnail', false, array('class' => 'w-full'));
                $html .= '</div>';
                
                $count++;
            }
            
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Get product rating HTML
     *
     * @param WC_Product $product Product object.
     * @return string
     */
    private function get_product_rating_html($product) {
        if (!wc_review_ratings_enabled() || !$product->get_rating_count()) {
            return '';
        }
        
        $rating_count = $product->get_rating_count();
        $review_count = $product->get_review_count();
        $average = $product->get_average_rating();
        
        $html = '<div class="product-rating flex items-center">';
        $html .= wc_get_rating_html($average, $rating_count);
        $html .= '<span class="rating-count text-sm text-gray-600 dark:text-gray-400 ml-2">';
        /* translators: %s: review count */
        $html .= sprintf(_n('(%s review)', '(%s reviews)', $review_count, 'aqualuxe'), number_format_i18n($review_count));
        $html .= '</span>';
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Get add to cart HTML
     *
     * @param WC_Product $product Product object.
     * @return string
     */
    private function get_add_to_cart_html($product) {
        ob_start();
        
        echo '<div class="quick-view-add-to-cart">';
        
        // For variable products, show a button that links to the product page
        if ($product->is_type('variable')) {
            echo '<a href="' . esc_url(get_permalink($product->get_id())) . '" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-300 mb-4">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />';
            echo '</svg>';
            echo esc_html__('Select Options', 'aqualuxe');
            echo '</a>';
        } else {
            woocommerce_template_loop_add_to_cart(array('class' => 'w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-300 mb-4'));
        }
        
        // Add wishlist button
        if (get_theme_mod('wishlist', true)) {
            echo '<button class="wishlist-toggle w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-300" data-product-id="' . esc_attr($product->get_id()) . '">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 wishlist-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />';
            echo '</svg>';
            echo esc_html__('Add to Wishlist', 'aqualuxe');
            echo '</button>';
        }
        
        echo '</div>';
        
        return ob_get_clean();
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        wp_enqueue_script('aqualuxe-quick-view', get_template_directory_uri() . '/assets/js/quick-view.js', array('jquery'), AQUALUXE_VERSION, true);
        
        wp_localize_script('aqualuxe-quick-view', 'aqualuxeQuickView', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_quick_view_nonce'),
            'i18n' => array(
                'loading' => __('Loading...', 'aqualuxe'),
                'error' => __('Error loading product', 'aqualuxe'),
            ),
        ));
    }
}

// Initialize the class
new AquaLuxe_WC_Quick_View();