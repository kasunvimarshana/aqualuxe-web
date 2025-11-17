<?php
/**
 * WooCommerce Quick View Functionality
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add quick view button to product loops
 */
function aqualuxe_add_quick_view_button() {
    global $product;

    echo '<button class="quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">' . 
         '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 3c5.392 0 9.878 3.88 10.819 9-.94 5.12-5.427 9-10.819 9-5.392 0-9.878-3.88-10.819-9C2.121 6.88 6.608 3 12 3zm0 16a9.005 9.005 0 0 0 8.777-7 9.005 9.005 0 0 0-17.554 0A9.005 9.005 0 0 0 12 19zm0-2.5a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9zm0-2a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/></svg>' .
         '<span>' . esc_html__('Quick View', 'aqualuxe') . '</span>' .
         '</button>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_add_quick_view_button', 15);

/**
 * Register quick view AJAX handler
 */
function aqualuxe_register_quick_view_ajax() {
    add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax_handler');
    add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax_handler');
}
add_action('init', 'aqualuxe_register_quick_view_ajax');

/**
 * Handle quick view AJAX request
 */
function aqualuxe_quick_view_ajax_handler() {
    // Check nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'aqualuxe-nonce')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
    }

    // Check product ID
    if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
        wp_send_json_error(array('message' => __('No product selected.', 'aqualuxe')));
    }

    $product_id = absint($_POST['product_id']);
    $product = wc_get_product($product_id);

    if (!$product) {
        wp_send_json_error(array('message' => __('Product not found.', 'aqualuxe')));
    }

    // Start output buffering
    ob_start();

    // Output quick view content
    aqualuxe_quick_view_content($product);

    // Get the buffered content
    $output = ob_get_clean();

    // Send response
    wp_send_json_success($output);
}

/**
 * Output quick view content
 *
 * @param WC_Product $product The product object.
 */
function aqualuxe_quick_view_content($product) {
    ?>
    <div class="quick-view-product">
        <div class="quick-view-product__inner">
            <div class="quick-view-product__images">
                <?php aqualuxe_quick_view_product_images($product); ?>
            </div>
            <div class="quick-view-product__summary">
                <h2 class="quick-view-product__title"><?php echo esc_html($product->get_name()); ?></h2>
                
                <div class="quick-view-product__price">
                    <?php echo wp_kses_post($product->get_price_html()); ?>
                </div>
                
                <?php if ($product->get_rating_count() > 0) : ?>
                    <div class="quick-view-product__rating">
                        <?php echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count()); ?>
                        <a href="<?php echo esc_url(get_permalink($product->get_id())) . '#reviews'; ?>" class="quick-view-product__review-link">
                            <?php printf(_n('(%s review)', '(%s reviews)', $product->get_review_count(), 'aqualuxe'), esc_html($product->get_review_count())); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="quick-view-product__short-description">
                    <?php echo wp_kses_post($product->get_short_description()); ?>
                </div>
                
                <div class="quick-view-product__add-to-cart">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
                
                <div class="quick-view-product__meta">
                    <?php woocommerce_template_single_meta(); ?>
                </div>
                
                <div class="quick-view-product__actions">
                    <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>" class="btn btn-outline">
                        <?php esc_html_e('View Full Details', 'aqualuxe'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Output quick view product images
 *
 * @param WC_Product $product The product object.
 */
function aqualuxe_quick_view_product_images($product) {
    $attachment_ids = $product->get_gallery_image_ids();
    $main_image_id = $product->get_image_id();
    
    // Add main image to the beginning of gallery
    if ($main_image_id) {
        array_unshift($attachment_ids, $main_image_id);
    }
    
    // If no images, use placeholder
    if (empty($attachment_ids)) {
        echo '<div class="woocommerce-product-gallery">';
        echo '<div class="woocommerce-product-gallery__image">';
        echo wc_placeholder_img('woocommerce_single');
        echo '</div>';
        echo '</div>';
        return;
    }
    
    // Output gallery
    echo '<div class="woocommerce-product-gallery">';
    
    // Main image
    echo '<div class="woocommerce-product-gallery__image">';
    $main_image_src = wp_get_attachment_image_src($attachment_ids[0], 'woocommerce_single');
    $main_image_full = wp_get_attachment_image_src($attachment_ids[0], 'full');
    $main_image_srcset = wp_get_attachment_image_srcset($attachment_ids[0], 'woocommerce_single');
    $main_image_sizes = wp_get_attachment_image_sizes($attachment_ids[0], 'woocommerce_single');
    
    echo '<img src="' . esc_url($main_image_src[0]) . '" ';
    echo 'data-large="' . esc_url($main_image_full[0]) . '" ';
    if ($main_image_srcset) {
        echo 'srcset="' . esc_attr($main_image_srcset) . '" ';
    }
    if ($main_image_sizes) {
        echo 'sizes="' . esc_attr($main_image_sizes) . '" ';
    }
    echo 'alt="' . esc_attr($product->get_name()) . '" ';
    echo 'class="wp-post-image" />';
    echo '</div>';
    
    // Thumbnails
    if (count($attachment_ids) > 1) {
        echo '<div class="woocommerce-product-gallery__thumbs">';
        foreach ($attachment_ids as $attachment_id) {
            $thumbnail_src = wp_get_attachment_image_src($attachment_id, 'woocommerce_gallery_thumbnail');
            $full_src = wp_get_attachment_image_src($attachment_id, 'full');
            $srcset = wp_get_attachment_image_srcset($attachment_id, 'woocommerce_single');
            $sizes = wp_get_attachment_image_sizes($attachment_id, 'woocommerce_single');
            
            echo '<img src="' . esc_url($thumbnail_src[0]) . '" ';
            echo 'data-large="' . esc_url($full_src[0]) . '" ';
            if ($srcset) {
                echo 'data-srcset="' . esc_attr($srcset) . '" ';
            }
            if ($sizes) {
                echo 'data-sizes="' . esc_attr($sizes) . '" ';
            }
            echo 'alt="' . esc_attr($product->get_name()) . '" ';
            echo 'class="' . ($attachment_id === $attachment_ids[0] ? 'active' : '') . '" />';
        }
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Register AJAX add to cart handler
 */
function aqualuxe_register_ajax_add_to_cart() {
    add_action('wp_ajax_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart_handler');
    add_action('wp_ajax_nopriv_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart_handler');
}
add_action('init', 'aqualuxe_register_ajax_add_to_cart');

/**
 * Handle AJAX add to cart request
 */
function aqualuxe_ajax_add_to_cart_handler() {
    // Check if WooCommerce AJAX add to cart is enabled
    if (get_option('woocommerce_enable_ajax_add_to_cart') === 'no') {
        wp_send_json_error(array('message' => __('AJAX add to cart is disabled.', 'aqualuxe')));
    }

    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? wc_stock_amount($_POST['quantity']) : 1;
    $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;
    $variations = array();

    // Get variations if any
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'attribute_') === 0) {
            $variations[$key] = $value;
        }
    }

    // Add to cart
    $added = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variations);

    if ($added) {
        WC_AJAX::get_refreshed_fragments();
    } else {
        wp_send_json_error(array('message' => __('Error adding product to cart.', 'aqualuxe')));
    }

    wp_die();
}

/**
 * Enqueue quick view scripts and styles
 */
function aqualuxe_enqueue_quick_view_scripts() {
    // Quick view is already included in the main JS file
    // Add any additional scripts or styles here if needed
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_quick_view_scripts');