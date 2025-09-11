<?php
/**
 * Quick View Module Bootstrap
 *
 * @package AquaLuxe\Modules\QuickView
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Quick View Module Class
 */
class AquaLuxe_Quick_View {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Only initialize if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_quick_view_product', array($this, 'ajax_quick_view'));
        add_action('wp_ajax_nopriv_quick_view_product', array($this, 'ajax_quick_view'));
        add_action('woocommerce_after_shop_loop_item', array($this, 'add_quick_view_button'), 20);
        add_action('wp_footer', array($this, 'quick_view_modal'));
    }
    
    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'aqualuxe-quick-view',
            AQUALUXE_THEME_URI . '/modules/quick-view/assets/quick-view.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script('aqualuxe-quick-view', 'aqualuxe_quick_view', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('quick_view_nonce'),
            'loading_text' => __('Loading...', 'aqualuxe'),
            'error_text' => __('Error loading product details', 'aqualuxe'),
        ));
    }
    
    /**
     * Add quick view button to product loop
     */
    public function add_quick_view_button() {
        global $product;
        if (!$product) {
            return;
        }
        
        $product_id = $product->get_id();
        
        ?>
        <button 
            class="aqualuxe-quick-view-button"
            data-product-id="<?php echo esc_attr($product_id); ?>"
            aria-label="<?php echo esc_attr(sprintf(__('Quick view %s', 'aqualuxe'), $product->get_name())); ?>"
        >
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span class="quick-view-text"><?php _e('Quick View', 'aqualuxe'); ?></span>
        </button>
        <?php
    }
    
    /**
     * Quick view modal HTML
     */
    public function quick_view_modal() {
        ?>
        <div id="aqualuxe-quick-view-modal" class="aqualuxe-modal" style="display: none;" aria-hidden="true">
            <div class="aqualuxe-modal-overlay"></div>
            <div class="aqualuxe-modal-content">
                <div class="aqualuxe-modal-header">
                    <button class="aqualuxe-modal-close" aria-label="<?php esc_attr_e('Close', 'aqualuxe'); ?>">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="aqualuxe-modal-body">
                    <div class="quick-view-loading">
                        <div class="loading-spinner"></div>
                        <p><?php _e('Loading product details...', 'aqualuxe'); ?></p>
                    </div>
                    <div class="quick-view-content"></div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * AJAX handler for quick view
     */
    public function ajax_quick_view() {
        check_ajax_referer('quick_view_nonce', 'nonce');
        
        $product_id = intval($_POST['product_id']);
        
        if (!$product_id) {
            wp_send_json_error(__('Invalid product ID', 'aqualuxe'));
        }
        
        $product = wc_get_product($product_id);
        
        if (!$product) {
            wp_send_json_error(__('Product not found', 'aqualuxe'));
        }
        
        // Setup global product
        global $woocommerce, $product as $global_product;
        $global_product = $product;
        
        ob_start();
        
        // Load quick view template
        $this->render_quick_view_content($product);
        
        $content = ob_get_clean();
        
        wp_send_json_success(array(
            'content' => $content,
            'product_id' => $product_id,
            'product_title' => $product->get_name(),
        ));
    }
    
    /**
     * Render quick view content
     */
    private function render_quick_view_content($product) {
        ?>
        <div class="quick-view-product" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
            <div class="quick-view-images">
                <?php
                $attachment_ids = $product->get_gallery_image_ids();
                $main_image_id = $product->get_image_id();
                
                if ($main_image_id) {
                    echo '<div class="quick-view-main-image">';
                    echo wp_get_attachment_image($main_image_id, 'woocommerce_single', false, array(
                        'class' => 'quick-view-image',
                        'alt' => esc_attr($product->get_name())
                    ));
                    echo '</div>';
                }
                
                if ($attachment_ids) {
                    echo '<div class="quick-view-gallery">';
                    foreach ($attachment_ids as $attachment_id) {
                        echo wp_get_attachment_image($attachment_id, 'woocommerce_gallery_thumbnail', false, array(
                            'class' => 'quick-view-gallery-image',
                            'data-large-image' => wp_get_attachment_image_url($attachment_id, 'woocommerce_single'),
                            'alt' => esc_attr($product->get_name())
                        ));
                    }
                    echo '</div>';
                }
                ?>
            </div>
            
            <div class="quick-view-details">
                <h2 class="quick-view-title">
                    <a href="<?php echo esc_url($product->get_permalink()); ?>">
                        <?php echo esc_html($product->get_name()); ?>
                    </a>
                </h2>
                
                <?php
                // Price
                echo '<div class="quick-view-price">' . $product->get_price_html() . '</div>';
                
                // Rating
                if (wc_review_ratings_enabled()) {
                    $rating_count = $product->get_rating_count();
                    $review_count = $product->get_review_count();
                    $average = $product->get_average_rating();
                    
                    if ($rating_count > 0) {
                        echo '<div class="quick-view-rating">';
                        echo wc_get_rating_html($average, $rating_count);
                        if ($review_count > 0) {
                            echo '<span class="review-count">(' . sprintf(_n('%s review', '%s reviews', $review_count, 'aqualuxe'), $review_count) . ')</span>';
                        }
                        echo '</div>';
                    }
                }
                
                // Short description
                $short_description = $product->get_short_description();
                if ($short_description) {
                    echo '<div class="quick-view-description">' . wp_kses_post($short_description) . '</div>';
                }
                
                // Stock status
                echo '<div class="quick-view-stock">';
                if ($product->is_in_stock()) {
                    echo '<span class="in-stock">' . __('In stock', 'aqualuxe') . '</span>';
                } else {
                    echo '<span class="out-of-stock">' . __('Out of stock', 'aqualuxe') . '</span>';
                }
                echo '</div>';
                
                // Add to cart form
                if ($product->is_purchasable() && $product->is_in_stock()) {
                    echo '<div class="quick-view-cart">';
                    
                    if ($product->is_type('simple')) {
                        ?>
                        <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype="multipart/form-data">
                            <div class="quantity-wrapper">
                                <label for="quantity_<?php echo esc_attr($product->get_id()); ?>"><?php _e('Quantity:', 'aqualuxe'); ?></label>
                                <input 
                                    type="number" 
                                    id="quantity_<?php echo esc_attr($product->get_id()); ?>"
                                    class="input-text qty text" 
                                    step="1" 
                                    min="1" 
                                    max="<?php echo esc_attr(0 < $product->get_max_purchase_quantity() ? $product->get_max_purchase_quantity() : ''); ?>" 
                                    name="quantity" 
                                    value="1" 
                                    title="<?php echo esc_attr_x('Qty', 'Product quantity input tooltip', 'aqualuxe'); ?>"
                                />
                            </div>
                            
                            <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="single_add_to_cart_button button">
                                <?php echo esc_html($product->single_add_to_cart_text()); ?>
                            </button>
                        </form>
                        <?php
                    } else {
                        // For variable products, show a link to the full product page
                        ?>
                        <a href="<?php echo esc_url($product->get_permalink()); ?>" class="button">
                            <?php _e('Select Options', 'aqualuxe'); ?>
                        </a>
                        <?php
                    }
                    
                    echo '</div>';
                }
                
                // Product meta
                echo '<div class="quick-view-meta">';
                
                // SKU
                if ($product->get_sku()) {
                    echo '<span class="sku-wrapper">' . __('SKU:', 'aqualuxe') . ' <span class="sku">' . esc_html($product->get_sku()) . '</span></span>';
                }
                
                // Categories
                $categories = get_the_terms($product->get_id(), 'product_cat');
                if ($categories && !is_wp_error($categories)) {
                    $category_links = array();
                    foreach ($categories as $category) {
                        $category_links[] = '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';
                    }
                    echo '<span class="categories-wrapper">' . __('Categories:', 'aqualuxe') . ' ' . implode(', ', $category_links) . '</span>';
                }
                
                // Tags
                $tags = get_the_terms($product->get_id(), 'product_tag');
                if ($tags && !is_wp_error($tags)) {
                    $tag_links = array();
                    foreach ($tags as $tag) {
                        $tag_links[] = '<a href="' . esc_url(get_term_link($tag)) . '">' . esc_html($tag->name) . '</a>';
                    }
                    echo '<span class="tags-wrapper">' . __('Tags:', 'aqualuxe') . ' ' . implode(', ', $tag_links) . '</span>';
                }
                
                echo '</div>';
                
                // View full details link
                echo '<div class="quick-view-actions">';
                echo '<a href="' . esc_url($product->get_permalink()) . '" class="view-details-link">' . __('View Full Details', 'aqualuxe') . '</a>';
                echo '</div>';
                ?>
            </div>
        </div>
        <?php
    }
}

// Initialize the module
new AquaLuxe_Quick_View();