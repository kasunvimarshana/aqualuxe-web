<?php
/**
 * WooCommerce specific functions and configurations
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize WooCommerce support
 */
function aqualuxe_woocommerce_init() {
    // Add theme support for WooCommerce
    add_theme_support('woocommerce');
    
    // Add support for WooCommerce features
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Remove default WooCommerce styles
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
    
    // Add custom styles
    add_action('wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts');
    
    // Modify WooCommerce templates
    add_filter('woocommerce_locate_template', 'aqualuxe_woocommerce_locate_template', 10, 3);
    
    // Modify WooCommerce product loop
    add_filter('woocommerce_product_loop_start', 'aqualuxe_woocommerce_product_loop_start');
    add_filter('woocommerce_product_loop_end', 'aqualuxe_woocommerce_product_loop_end');
    
    // Modify WooCommerce shop columns
    add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_shop_columns');
    
    // Modify WooCommerce products per page
    add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page');
    
    // Add custom body classes
    add_filter('body_class', 'aqualuxe_woocommerce_body_class');
    
    // Modify breadcrumb
    add_filter('woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumb_defaults');
    
    // Modify related products
    add_filter('woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args');
    
    // Modify upsells
    add_filter('woocommerce_upsell_display_args', 'aqualuxe_woocommerce_upsell_display_args');
    
    // Modify cross-sells
    add_filter('woocommerce_cross_sells_columns', 'aqualuxe_woocommerce_cross_sells_columns');
    add_filter('woocommerce_cross_sells_total', 'aqualuxe_woocommerce_cross_sells_total');
    
    // Add quick view
    if (aqualuxe_get_option('quick_view_enabled', true)) {
        add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax');
        add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax');
        add_action('woocommerce_after_shop_loop_item', 'aqualuxe_quick_view_button', 15);
    }
    
    // Add wishlist
    if (aqualuxe_get_option('wishlist_enabled', true)) {
        // Check if YITH WooCommerce Wishlist is active
        if (!class_exists('YITH_WCWL')) {
            add_action('wp_ajax_aqualuxe_add_to_wishlist', 'aqualuxe_add_to_wishlist_ajax');
            add_action('wp_ajax_nopriv_aqualuxe_add_to_wishlist', 'aqualuxe_add_to_wishlist_ajax');
            add_action('woocommerce_after_shop_loop_item', 'aqualuxe_wishlist_button', 20);
            add_action('woocommerce_single_product_summary', 'aqualuxe_wishlist_button', 35);
        }
    }
    
    // Add AJAX add to cart
    if (aqualuxe_get_option('ajax_add_to_cart', true)) {
        add_action('wp_ajax_aqualuxe_add_to_cart', 'aqualuxe_add_to_cart_ajax');
        add_action('wp_ajax_nopriv_aqualuxe_add_to_cart', 'aqualuxe_add_to_cart_ajax');
    }
    
    // Add sticky add to cart
    if (aqualuxe_get_option('product_sticky_add_to_cart', true)) {
        add_action('woocommerce_after_single_product', 'aqualuxe_sticky_add_to_cart');
    }
    
    // Modify checkout
    add_filter('woocommerce_checkout_fields', 'aqualuxe_woocommerce_checkout_fields');
    
    // Add custom tabs
    add_filter('woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs');
    
    // Add custom fields to product
    add_action('woocommerce_product_options_general_product_data', 'aqualuxe_woocommerce_product_custom_fields');
    add_action('woocommerce_process_product_meta', 'aqualuxe_woocommerce_product_custom_fields_save');
    
    // Add custom fields to product category
    add_action('product_cat_add_form_fields', 'aqualuxe_woocommerce_category_custom_fields');
    add_action('product_cat_edit_form_fields', 'aqualuxe_woocommerce_category_custom_fields_edit', 10, 2);
    add_action('created_product_cat', 'aqualuxe_woocommerce_category_custom_fields_save', 10, 2);
    add_action('edited_product_cat', 'aqualuxe_woocommerce_category_custom_fields_save', 10, 2);
    
    // Add custom order status
    add_action('init', 'aqualuxe_woocommerce_register_custom_order_status');
    add_filter('wc_order_statuses', 'aqualuxe_woocommerce_add_custom_order_status');
    
    // Add custom email templates
    add_filter('woocommerce_email_classes', 'aqualuxe_woocommerce_add_custom_emails');
    
    // Add custom dashboard widgets
    add_action('wp_dashboard_setup', 'aqualuxe_woocommerce_add_dashboard_widgets');
    
    // Add custom admin menu
    add_action('admin_menu', 'aqualuxe_woocommerce_add_admin_menu');
    
    // Add custom admin columns
    add_filter('manage_edit-product_columns', 'aqualuxe_woocommerce_product_columns');
    add_action('manage_product_posts_custom_column', 'aqualuxe_woocommerce_product_column_content', 10, 2);
    
    // Add custom admin filters
    add_action('restrict_manage_posts', 'aqualuxe_woocommerce_admin_filters');
    add_filter('parse_query', 'aqualuxe_woocommerce_admin_filter_query');
    
    // Add custom admin scripts
    add_action('admin_enqueue_scripts', 'aqualuxe_woocommerce_admin_scripts');
    
    // Add custom admin notices
    add_action('admin_notices', 'aqualuxe_woocommerce_admin_notices');
    
    // Add custom admin meta boxes
    add_action('add_meta_boxes', 'aqualuxe_woocommerce_add_meta_boxes');
    add_action('save_post', 'aqualuxe_woocommerce_save_meta_boxes', 10, 2);
    
    // Add custom admin settings
    add_filter('woocommerce_get_settings_pages', 'aqualuxe_woocommerce_add_settings_page');
    
    // Add custom admin reports
    add_filter('woocommerce_admin_reports', 'aqualuxe_woocommerce_add_reports');
    
    // Add custom admin dashboard widgets
    add_action('wp_dashboard_setup', 'aqualuxe_woocommerce_add_dashboard_widgets');
    
    // Add custom admin menu
    add_action('admin_menu', 'aqualuxe_woocommerce_add_admin_menu');
    
    // Add custom admin columns
    add_filter('manage_edit-product_columns', 'aqualuxe_woocommerce_product_columns');
    add_action('manage_product_posts_custom_column', 'aqualuxe_woocommerce_product_column_content', 10, 2);
    
    // Add custom admin filters
    add_action('restrict_manage_posts', 'aqualuxe_woocommerce_admin_filters');
    add_filter('parse_query', 'aqualuxe_woocommerce_admin_filter_query');
    
    // Add custom admin scripts
    add_action('admin_enqueue_scripts', 'aqualuxe_woocommerce_admin_scripts');
    
    // Add custom admin notices
    add_action('admin_notices', 'aqualuxe_woocommerce_admin_notices');
    
    // Add custom admin meta boxes
    add_action('add_meta_boxes', 'aqualuxe_woocommerce_add_meta_boxes');
    add_action('save_post', 'aqualuxe_woocommerce_save_meta_boxes', 10, 2);
    
    // Add custom admin settings
    add_filter('woocommerce_get_settings_pages', 'aqualuxe_woocommerce_add_settings_page');
    
    // Add custom admin reports
    add_filter('woocommerce_admin_reports', 'aqualuxe_woocommerce_add_reports');
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_init');

/**
 * Enqueue WooCommerce scripts and styles
 */
function aqualuxe_woocommerce_scripts() {
    // Get the mix manifest file
    $mix_manifest = aqualuxe_get_mix_manifest();
    
    // Enqueue WooCommerce styles
    wp_enqueue_style(
        'aqualuxe-woocommerce-styles',
        AQUALUXE_ASSETS_URI . 'css/woocommerce' . aqualuxe_get_asset_version('css/woocommerce.css', $mix_manifest) . '.css',
        array('aqualuxe-styles'),
        AQUALUXE_VERSION
    );
    
    // Enqueue WooCommerce scripts
    wp_enqueue_script(
        'aqualuxe-woocommerce-scripts',
        AQUALUXE_ASSETS_URI . 'js/woocommerce' . aqualuxe_get_asset_version('js/woocommerce.js', $mix_manifest) . '.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );
    
    // Localize WooCommerce scripts
    wp_localize_script('aqualuxe-woocommerce-scripts', 'aqualuxeWooCommerce', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe-woocommerce-nonce'),
        'i18n' => array(
            'addToCart' => esc_html__('Add to cart', 'aqualuxe'),
            'viewCart' => esc_html__('View cart', 'aqualuxe'),
            'addToWishlist' => esc_html__('Add to wishlist', 'aqualuxe'),
            'removeFromWishlist' => esc_html__('Remove from wishlist', 'aqualuxe'),
            'quickView' => esc_html__('Quick view', 'aqualuxe'),
            'loadMore' => esc_html__('Load more', 'aqualuxe'),
            'noMoreProducts' => esc_html__('No more products to load', 'aqualuxe'),
        ),
    ));
}

/**
 * Locate WooCommerce templates in the theme
 *
 * @param string $template
 * @param string $template_name
 * @param string $template_path
 * @return string
 */
function aqualuxe_woocommerce_locate_template($template, $template_name, $template_path) {
    // Set the path to the theme's WooCommerce templates
    $theme_template = AQUALUXE_DIR . 'woocommerce/' . $template_name;
    
    // Return the theme template if it exists, otherwise return the original template
    return file_exists($theme_template) ? $theme_template : $template;
}

/**
 * Modify WooCommerce product loop start
 *
 * @param string $loop_start
 * @return string
 */
function aqualuxe_woocommerce_product_loop_start($loop_start) {
    $columns = aqualuxe_woocommerce_loop_shop_columns();
    
    return '<ul class="products columns-' . esc_attr($columns) . '">';
}

/**
 * Modify WooCommerce product loop end
 *
 * @param string $loop_end
 * @return string
 */
function aqualuxe_woocommerce_product_loop_end($loop_end) {
    return '</ul>';
}

/**
 * Set the number of columns in the product loop
 *
 * @return int
 */
function aqualuxe_woocommerce_loop_shop_columns() {
    return aqualuxe_get_option('shop_columns', 3);
}

/**
 * Set the number of products per page
 *
 * @return int
 */
function aqualuxe_woocommerce_products_per_page() {
    return aqualuxe_get_option('products_per_page', 12);
}

/**
 * Add custom body classes for WooCommerce pages
 *
 * @param array $classes
 * @return array
 */
function aqualuxe_woocommerce_body_class($classes) {
    if (is_woocommerce()) {
        $classes[] = 'aqualuxe-woocommerce';
        
        if (is_product()) {
            $classes[] = 'aqualuxe-single-product';
        } elseif (is_shop() || is_product_category() || is_product_tag()) {
            $classes[] = 'aqualuxe-shop';
            
            // Add shop sidebar class
            if (aqualuxe_get_option('shop_sidebar', true)) {
                $classes[] = 'aqualuxe-shop-has-sidebar';
                $classes[] = 'aqualuxe-shop-sidebar-' . aqualuxe_get_option('shop_sidebar_position', 'right');
            } else {
                $classes[] = 'aqualuxe-shop-no-sidebar';
            }
        }
    }
    
    if (is_cart()) {
        $classes[] = 'aqualuxe-cart';
    }
    
    if (is_checkout()) {
        $classes[] = 'aqualuxe-checkout';
        
        // Add checkout layout class
        $classes[] = 'aqualuxe-checkout-layout-' . aqualuxe_get_option('checkout_layout', 'default');
        
        // Add distraction-free checkout class
        if (aqualuxe_get_option('checkout_distraction_free', false)) {
            $classes[] = 'aqualuxe-checkout-distraction-free';
        }
    }
    
    if (is_account_page()) {
        $classes[] = 'aqualuxe-account';
    }
    
    return $classes;
}

/**
 * Modify WooCommerce breadcrumb defaults
 *
 * @param array $defaults
 * @return array
 */
function aqualuxe_woocommerce_breadcrumb_defaults($defaults) {
    $defaults['delimiter'] = '<span class="aqualuxe-breadcrumb-separator">/</span>';
    $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb aqualuxe-breadcrumb" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '"><ol class="aqualuxe-breadcrumb-list">';
    $defaults['wrap_after'] = '</ol></nav>';
    $defaults['before'] = '<li class="aqualuxe-breadcrumb-item">';
    $defaults['after'] = '</li>';
    $defaults['home'] = __('Home', 'aqualuxe');
    
    return $defaults;
}

/**
 * Modify WooCommerce related products args
 *
 * @param array $args
 * @return array
 */
function aqualuxe_woocommerce_related_products_args($args) {
    $args['posts_per_page'] = aqualuxe_get_option('product_related_count', 4);
    $args['columns'] = aqualuxe_get_option('shop_columns', 3);
    
    return $args;
}

/**
 * Modify WooCommerce upsell display args
 *
 * @param array $args
 * @return array
 */
function aqualuxe_woocommerce_upsell_display_args($args) {
    $args['posts_per_page'] = aqualuxe_get_option('product_upsells_count', 4);
    $args['columns'] = aqualuxe_get_option('shop_columns', 3);
    
    return $args;
}

/**
 * Set the number of columns for cross-sells
 *
 * @return int
 */
function aqualuxe_woocommerce_cross_sells_columns() {
    return aqualuxe_get_option('shop_columns', 3);
}

/**
 * Set the number of cross-sells
 *
 * @return int
 */
function aqualuxe_woocommerce_cross_sells_total() {
    return aqualuxe_get_option('product_cross_sells_count', 4);
}

/**
 * Quick view AJAX handler
 */
function aqualuxe_quick_view_ajax() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce-nonce')) {
        wp_send_json_error(array('message' => __('Invalid nonce', 'aqualuxe')));
    }
    
    // Check product ID
    if (!isset($_POST['product_id'])) {
        wp_send_json_error(array('message' => __('Invalid product ID', 'aqualuxe')));
    }
    
    // Get product ID
    $product_id = absint($_POST['product_id']);
    
    // Get product
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error(array('message' => __('Product not found', 'aqualuxe')));
    }
    
    // Start output buffering
    ob_start();
    
    // Include quick view template
    include AQUALUXE_DIR . 'woocommerce/quick-view.php';
    
    // Get the output
    $output = ob_get_clean();
    
    // Send the response
    wp_send_json_success(array(
        'html' => $output,
    ));
}

/**
 * Add quick view button to product loop
 */
function aqualuxe_quick_view_button() {
    global $product;
    
    echo '<a href="#" class="aqualuxe-quick-view-button button" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</a>';
}

/**
 * Add to wishlist AJAX handler
 */
function aqualuxe_add_to_wishlist_ajax() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce-nonce')) {
        wp_send_json_error(array('message' => __('Invalid nonce', 'aqualuxe')));
    }
    
    // Check product ID
    if (!isset($_POST['product_id'])) {
        wp_send_json_error(array('message' => __('Invalid product ID', 'aqualuxe')));
    }
    
    // Get product ID
    $product_id = absint($_POST['product_id']);
    
    // Get product
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error(array('message' => __('Product not found', 'aqualuxe')));
    }
    
    // Get wishlist
    $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? json_decode(stripslashes($_COOKIE['aqualuxe_wishlist']), true) : array();
    
    // Check if product is already in wishlist
    $in_wishlist = in_array($product_id, $wishlist);
    
    // Add or remove product from wishlist
    if ($in_wishlist) {
        $wishlist = array_diff($wishlist, array($product_id));
        $message = __('Product removed from wishlist', 'aqualuxe');
    } else {
        $wishlist[] = $product_id;
        $message = __('Product added to wishlist', 'aqualuxe');
    }
    
    // Save wishlist
    setcookie('aqualuxe_wishlist', json_encode($wishlist), time() + 30 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
    
    // Send the response
    wp_send_json_success(array(
        'message' => $message,
        'in_wishlist' => !$in_wishlist,
        'wishlist_count' => count($wishlist),
    ));
}

/**
 * Add wishlist button to product loop
 */
function aqualuxe_wishlist_button() {
    global $product;
    
    // Get wishlist
    $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? json_decode(stripslashes($_COOKIE['aqualuxe_wishlist']), true) : array();
    
    // Check if product is in wishlist
    $in_wishlist = in_array($product->get_id(), $wishlist);
    
    echo '<a href="#" class="aqualuxe-wishlist-button' . ($in_wishlist ? ' added' : '') . '" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path></svg>';
    echo '<span class="screen-reader-text">' . esc_html($in_wishlist ? __('Remove from wishlist', 'aqualuxe') : __('Add to wishlist', 'aqualuxe')) . '</span>';
    echo '</a>';
}

/**
 * Add to cart AJAX handler
 */
function aqualuxe_add_to_cart_ajax() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce-nonce')) {
        wp_send_json_error(array('message' => __('Invalid nonce', 'aqualuxe')));
    }
    
    // Check product ID
    if (!isset($_POST['product_id'])) {
        wp_send_json_error(array('message' => __('Invalid product ID', 'aqualuxe')));
    }
    
    // Get product ID
    $product_id = absint($_POST['product_id']);
    
    // Get product
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error(array('message' => __('Product not found', 'aqualuxe')));
    }
    
    // Get quantity
    $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;
    
    // Get variation ID
    $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;
    
    // Get variation attributes
    $variation = array();
    
    if ($variation_id) {
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'attribute_') === 0) {
                $variation[$key] = $value;
            }
        }
    }
    
    // Add to cart
    $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
    
    if (!$cart_item_key) {
        wp_send_json_error(array('message' => __('Failed to add product to cart', 'aqualuxe')));
    }
    
    // Get cart fragments
    $fragments = WC_AJAX::get_refreshed_fragments();
    
    // Send the response
    wp_send_json_success(array(
        'message' => __('Product added to cart', 'aqualuxe'),
        'fragments' => $fragments,
    ));
}

/**
 * Add sticky add to cart
 */
function aqualuxe_sticky_add_to_cart() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    // Get product data
    $product_id = $product->get_id();
    $product_title = $product->get_title();
    $product_price = $product->get_price_html();
    $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'thumbnail');
    $product_url = $product->get_permalink();
    
    // Check if product is in stock
    $in_stock = $product->is_in_stock();
    
    // Check if product is purchasable
    $purchasable = $product->is_purchasable();
    
    // Check if product is sold individually
    $sold_individually = $product->is_sold_individually();
    
    // Get product type
    $product_type = $product->get_type();
    
    // Get add to cart URL
    $add_to_cart_url = $product->add_to_cart_url();
    
    // Get add to cart text
    $add_to_cart_text = $product->add_to_cart_text();
    
    // Get add to cart class
    $add_to_cart_class = $product->add_to_cart_class();
    
    // Get add to cart attributes
    $add_to_cart_attributes = $product->add_to_cart_attributes();
    
    // Get add to cart description
    $add_to_cart_description = $product->add_to_cart_description();
    
    // Get add to cart ajax
    $add_to_cart_ajax = $product->supports('ajax_add_to_cart') && $product->is_purchasable() && $product->is_in_stock();
    
    // Get add to cart data
    $add_to_cart_data = array(
        'product_id' => $product_id,
        'product_sku' => $product->get_sku(),
        'quantity' => 1,
        'variation_id' => 0,
        'variation' => array(),
    );
    
    // Get add to cart data attributes
    $add_to_cart_data_attributes = array(
        'data-product_id' => $product_id,
        'data-product_sku' => $product->get_sku(),
        'data-quantity' => 1,
        'aria-label' => $product->add_to_cart_description(),
        'rel' => 'nofollow',
    );
    
    // Add class for ajax add to cart
    if ($add_to_cart_ajax) {
        $add_to_cart_class .= ' ajax_add_to_cart';
        $add_to_cart_data_attributes['data-product_id'] = $product_id;
        $add_to_cart_data_attributes['data-product_sku'] = $product->get_sku();
        $add_to_cart_data_attributes['data-quantity'] = 1;
    }
    
    // Build data attributes string
    $data_attributes = '';
    
    foreach ($add_to_cart_data_attributes as $key => $value) {
        $data_attributes .= ' ' . $key . '="' . esc_attr($value) . '"';
    }
    
    // Output sticky add to cart
    ?>
    <div class="aqualuxe-sticky-add-to-cart">
        <div class="container">
            <div class="aqualuxe-sticky-add-to-cart-content">
                <?php if ($product_image) : ?>
                    <div class="aqualuxe-sticky-add-to-cart-image">
                        <img src="<?php echo esc_url($product_image[0]); ?>" alt="<?php echo esc_attr($product_title); ?>">
                    </div>
                <?php endif; ?>
                
                <div class="aqualuxe-sticky-add-to-cart-info">
                    <h4 class="aqualuxe-sticky-add-to-cart-title"><?php echo esc_html($product_title); ?></h4>
                    <span class="aqualuxe-sticky-add-to-cart-price"><?php echo wp_kses_post($product_price); ?></span>
                </div>
                
                <div class="aqualuxe-sticky-add-to-cart-action">
                    <?php if ($purchasable && $in_stock) : ?>
                        <?php if ($product_type === 'simple') : ?>
                            <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product_url)); ?>" method="post" enctype="multipart/form-data">
                                <?php if (!$sold_individually) : ?>
                                    <div class="aqualuxe-sticky-add-to-cart-quantity">
                                        <?php woocommerce_quantity_input(array('min_value' => 1, 'max_value' => $product->get_max_purchase_quantity())); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product_id); ?>" class="button button-primary alt<?php echo esc_attr($add_to_cart_class ? ' ' . $add_to_cart_class : ''); ?>"<?php echo $data_attributes; ?>><?php echo esc_html($add_to_cart_text); ?></button>
                            </form>
                        <?php else : ?>
                            <a href="<?php echo esc_url($add_to_cart_url); ?>" class="button button-primary alt<?php echo esc_attr($add_to_cart_class ? ' ' . $add_to_cart_class : ''); ?>"<?php echo $add_to_cart_attributes; ?>><?php echo esc_html($add_to_cart_text); ?></a>
                        <?php endif; ?>
                    <?php else : ?>
                        <p class="aqualuxe-sticky-add-to-cart-out-of-stock"><?php esc_html_e('Out of stock', 'aqualuxe'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Modify WooCommerce checkout fields
 *
 * @param array $fields
 * @return array
 */
function aqualuxe_woocommerce_checkout_fields($fields) {
    // Make company field optional
    if (isset($fields['billing']['billing_company'])) {
        $fields['billing']['billing_company']['required'] = false;
    }
    
    if (isset($fields['shipping']['shipping_company'])) {
        $fields['shipping']['shipping_company']['required'] = false;
    }
    
    // Add placeholder to address fields
    if (isset($fields['billing']['billing_address_1'])) {
        $fields['billing']['billing_address_1']['placeholder'] = __('Street address', 'aqualuxe');
    }
    
    if (isset($fields['shipping']['shipping_address_1'])) {
        $fields['shipping']['shipping_address_1']['placeholder'] = __('Street address', 'aqualuxe');
    }
    
    if (isset($fields['billing']['billing_address_2'])) {
        $fields['billing']['billing_address_2']['placeholder'] = __('Apartment, suite, unit, etc. (optional)', 'aqualuxe');
    }
    
    if (isset($fields['shipping']['shipping_address_2'])) {
        $fields['shipping']['shipping_address_2']['placeholder'] = __('Apartment, suite, unit, etc. (optional)', 'aqualuxe');
    }
    
    // Add class to fields
    foreach ($fields as $section => $section_fields) {
        foreach ($section_fields as $key => $field) {
            $fields[$section][$key]['class'][] = 'aqualuxe-form-field';
        }
    }
    
    return $fields;
}

/**
 * Add custom tabs to product
 *
 * @param array $tabs
 * @return array
 */
function aqualuxe_woocommerce_product_tabs($tabs) {
    // Add care guide tab
    $tabs['care_guide'] = array(
        'title' => __('Care Guide', 'aqualuxe'),
        'priority' => 30,
        'callback' => 'aqualuxe_woocommerce_care_guide_tab',
    );
    
    // Add shipping tab
    $tabs['shipping'] = array(
        'title' => __('Shipping', 'aqualuxe'),
        'priority' => 40,
        'callback' => 'aqualuxe_woocommerce_shipping_tab',
    );
    
    return $tabs;
}

/**
 * Care guide tab content
 */
function aqualuxe_woocommerce_care_guide_tab() {
    global $product;
    
    // Get care guide from product meta
    $care_guide = get_post_meta($product->get_id(), '_care_guide', true);
    
    if ($care_guide) {
        echo wp_kses_post($care_guide);
    } else {
        echo '<p>' . esc_html__('No care guide available for this product.', 'aqualuxe') . '</p>';
    }
}

/**
 * Shipping tab content
 */
function aqualuxe_woocommerce_shipping_tab() {
    global $product;
    
    // Get shipping info from product meta
    $shipping_info = get_post_meta($product->get_id(), '_shipping_info', true);
    
    if ($shipping_info) {
        echo wp_kses_post($shipping_info);
    } else {
        echo '<p>' . esc_html__('No shipping information available for this product.', 'aqualuxe') . '</p>';
    }
}

/**
 * Add custom fields to product
 */
function aqualuxe_woocommerce_product_custom_fields() {
    global $woocommerce, $post;
    
    echo '<div class="options_group">';
    
    // Care guide
    woocommerce_wp_textarea_input(array(
        'id' => '_care_guide',
        'label' => __('Care Guide', 'aqualuxe'),
        'placeholder' => __('Enter care guide information', 'aqualuxe'),
        'desc_tip' => true,
        'description' => __('Enter care guide information for this product.', 'aqualuxe'),
    ));
    
    // Shipping info
    woocommerce_wp_textarea_input(array(
        'id' => '_shipping_info',
        'label' => __('Shipping Information', 'aqualuxe'),
        'placeholder' => __('Enter shipping information', 'aqualuxe'),
        'desc_tip' => true,
        'description' => __('Enter shipping information for this product.', 'aqualuxe'),
    ));
    
    echo '</div>';
}

/**
 * Save custom fields for product
 *
 * @param int $post_id
 */
function aqualuxe_woocommerce_product_custom_fields_save($post_id) {
    // Care guide
    $care_guide = isset($_POST['_care_guide']) ? wp_kses_post($_POST['_care_guide']) : '';
    update_post_meta($post_id, '_care_guide', $care_guide);
    
    // Shipping info
    $shipping_info = isset($_POST['_shipping_info']) ? wp_kses_post($_POST['_shipping_info']) : '';
    update_post_meta($post_id, '_shipping_info', $shipping_info);
}

/**
 * Add custom fields to product category
 */
function aqualuxe_woocommerce_category_custom_fields() {
    ?>
    <div class="form-field">
        <label for="category_icon"><?php esc_html_e('Category Icon', 'aqualuxe'); ?></label>
        <input type="text" name="category_icon" id="category_icon" value="">
        <p class="description"><?php esc_html_e('Enter category icon class (e.g., fa-fish).', 'aqualuxe'); ?></p>
    </div>
    
    <div class="form-field">
        <label for="category_banner"><?php esc_html_e('Category Banner', 'aqualuxe'); ?></label>
        <input type="text" name="category_banner" id="category_banner" value="">
        <p class="description"><?php esc_html_e('Enter category banner image URL.', 'aqualuxe'); ?></p>
    </div>
    <?php
}

/**
 * Edit custom fields for product category
 *
 * @param object $term
 * @param string $taxonomy
 */
function aqualuxe_woocommerce_category_custom_fields_edit($term, $taxonomy) {
    $category_icon = get_term_meta($term->term_id, 'category_icon', true);
    $category_banner = get_term_meta($term->term_id, 'category_banner', true);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="category_icon"><?php esc_html_e('Category Icon', 'aqualuxe'); ?></label></th>
        <td>
            <input type="text" name="category_icon" id="category_icon" value="<?php echo esc_attr($category_icon); ?>">
            <p class="description"><?php esc_html_e('Enter category icon class (e.g., fa-fish).', 'aqualuxe'); ?></p>
        </td>
    </tr>
    
    <tr class="form-field">
        <th scope="row" valign="top"><label for="category_banner"><?php esc_html_e('Category Banner', 'aqualuxe'); ?></label></th>
        <td>
            <input type="text" name="category_banner" id="category_banner" value="<?php echo esc_attr($category_banner); ?>">
            <p class="description"><?php esc_html_e('Enter category banner image URL.', 'aqualuxe'); ?></p>
        </td>
    </tr>
    <?php
}

/**
 * Save custom fields for product category
 *
 * @param int $term_id
 * @param int $tt_id
 */
function aqualuxe_woocommerce_category_custom_fields_save($term_id, $tt_id) {
    if (isset($_POST['category_icon'])) {
        update_term_meta($term_id, 'category_icon', sanitize_text_field($_POST['category_icon']));
    }
    
    if (isset($_POST['category_banner'])) {
        update_term_meta($term_id, 'category_banner', sanitize_text_field($_POST['category_banner']));
    }
}

/**
 * Register custom order status
 */
function aqualuxe_woocommerce_register_custom_order_status() {
    register_post_status('wc-processing-special', array(
        'label' => __('Processing Special', 'aqualuxe'),
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop('Processing Special <span class="count">(%s)</span>', 'Processing Special <span class="count">(%s)</span>', 'aqualuxe'),
    ));
}

/**
 * Add custom order status to WooCommerce order statuses
 *
 * @param array $order_statuses
 * @return array
 */
function aqualuxe_woocommerce_add_custom_order_status($order_statuses) {
    $new_order_statuses = array();
    
    // Add new order status after processing
    foreach ($order_statuses as $key => $status) {
        $new_order_statuses[$key] = $status;
        
        if ($key === 'wc-processing') {
            $new_order_statuses['wc-processing-special'] = __('Processing Special', 'aqualuxe');
        }
    }
    
    return $new_order_statuses;
}

/**
 * Add custom email templates
 *
 * @param array $email_classes
 * @return array
 */
function aqualuxe_woocommerce_add_custom_emails($email_classes) {
    // Include custom email class
    include_once AQUALUXE_DIR . 'inc/woocommerce/class-aqualuxe-wc-email-custom.php';
    
    // Add custom email class
    $email_classes['WC_Email_Custom'] = new WC_Email_Custom();
    
    return $email_classes;
}

/**
 * Add custom dashboard widgets
 */
function aqualuxe_woocommerce_add_dashboard_widgets() {
    // Check if user has permission
    if (current_user_can('manage_woocommerce')) {
        wp_add_dashboard_widget(
            'aqualuxe_woocommerce_dashboard_widget',
            __('AquaLuxe Store Overview', 'aqualuxe'),
            'aqualuxe_woocommerce_dashboard_widget_callback'
        );
    }
}

/**
 * Dashboard widget callback
 */
function aqualuxe_woocommerce_dashboard_widget_callback() {
    // Get sales data
    $sales_data = aqualuxe_woocommerce_get_sales_data();
    
    // Get order data
    $order_data = aqualuxe_woocommerce_get_order_data();
    
    // Get product data
    $product_data = aqualuxe_woocommerce_get_product_data();
    
    // Display sales data
    echo '<h3>' . esc_html__('Sales', 'aqualuxe') . '</h3>';
    echo '<p>' . esc_html__('Today:', 'aqualuxe') . ' ' . wc_price($sales_data['today']) . '</p>';
    echo '<p>' . esc_html__('This Week:', 'aqualuxe') . ' ' . wc_price($sales_data['week']) . '</p>';
    echo '<p>' . esc_html__('This Month:', 'aqualuxe') . ' ' . wc_price($sales_data['month']) . '</p>';
    
    // Display order data
    echo '<h3>' . esc_html__('Orders', 'aqualuxe') . '</h3>';
    echo '<p>' . esc_html__('Pending:', 'aqualuxe') . ' ' . esc_html($order_data['pending']) . '</p>';
    echo '<p>' . esc_html__('Processing:', 'aqualuxe') . ' ' . esc_html($order_data['processing']) . '</p>';
    echo '<p>' . esc_html__('Completed:', 'aqualuxe') . ' ' . esc_html($order_data['completed']) . '</p>';
    
    // Display product data
    echo '<h3>' . esc_html__('Products', 'aqualuxe') . '</h3>';
    echo '<p>' . esc_html__('Total Products:', 'aqualuxe') . ' ' . esc_html($product_data['total']) . '</p>';
    echo '<p>' . esc_html__('Out of Stock:', 'aqualuxe') . ' ' . esc_html($product_data['out_of_stock']) . '</p>';
    echo '<p>' . esc_html__('Low Stock:', 'aqualuxe') . ' ' . esc_html($product_data['low_stock']) . '</p>';
}

/**
 * Get sales data
 *
 * @return array
 */
function aqualuxe_woocommerce_get_sales_data() {
    // Get today's sales
    $today_sales = wc_price(0);
    
    // Get this week's sales
    $week_sales = wc_price(0);
    
    // Get this month's sales
    $month_sales = wc_price(0);
    
    return array(
        'today' => $today_sales,
        'week' => $week_sales,
        'month' => $month_sales,
    );
}

/**
 * Get order data
 *
 * @return array
 */
function aqualuxe_woocommerce_get_order_data() {
    // Get pending orders
    $pending_orders = 0;
    
    // Get processing orders
    $processing_orders = 0;
    
    // Get completed orders
    $completed_orders = 0;
    
    return array(
        'pending' => $pending_orders,
        'processing' => $processing_orders,
        'completed' => $completed_orders,
    );
}

/**
 * Get product data
 *
 * @return array
 */
function aqualuxe_woocommerce_get_product_data() {
    // Get total products
    $total_products = 0;
    
    // Get out of stock products
    $out_of_stock_products = 0;
    
    // Get low stock products
    $low_stock_products = 0;
    
    return array(
        'total' => $total_products,
        'out_of_stock' => $out_of_stock_products,
        'low_stock' => $low_stock_products,
    );
}

/**
 * Add custom admin menu
 */
function aqualuxe_woocommerce_add_admin_menu() {
    // Check if user has permission
    if (current_user_can('manage_woocommerce')) {
        add_menu_page(
            __('AquaLuxe', 'aqualuxe'),
            __('AquaLuxe', 'aqualuxe'),
            'manage_woocommerce',
            'aqualuxe',
            'aqualuxe_woocommerce_admin_page_callback',
            'dashicons-admin-site',
            56
        );
        
        add_submenu_page(
            'aqualuxe',
            __('Dashboard', 'aqualuxe'),
            __('Dashboard', 'aqualuxe'),
            'manage_woocommerce',
            'aqualuxe',
            'aqualuxe_woocommerce_admin_page_callback'
        );
        
        add_submenu_page(
            'aqualuxe',
            __('Settings', 'aqualuxe'),
            __('Settings', 'aqualuxe'),
            'manage_woocommerce',
            'aqualuxe-settings',
            'aqualuxe_woocommerce_admin_settings_callback'
        );
    }
}

/**
 * Admin page callback
 */
function aqualuxe_woocommerce_admin_page_callback() {
    // Get sales data
    $sales_data = aqualuxe_woocommerce_get_sales_data();
    
    // Get order data
    $order_data = aqualuxe_woocommerce_get_order_data();
    
    // Get product data
    $product_data = aqualuxe_woocommerce_get_product_data();
    
    // Display admin page
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('AquaLuxe Dashboard', 'aqualuxe'); ?></h1>
        
        <div class="aqualuxe-admin-dashboard">
            <div class="aqualuxe-admin-dashboard-section">
                <h2><?php esc_html_e('Sales', 'aqualuxe'); ?></h2>
                
                <div class="aqualuxe-admin-dashboard-card">
                    <div class="aqualuxe-admin-dashboard-card-header">
                        <h3><?php esc_html_e('Today', 'aqualuxe'); ?></h3>
                    </div>
                    
                    <div class="aqualuxe-admin-dashboard-card-content">
                        <p class="aqualuxe-admin-dashboard-card-value"><?php echo wc_price($sales_data['today']); ?></p>
                    </div>
                </div>
                
                <div class="aqualuxe-admin-dashboard-card">
                    <div class="aqualuxe-admin-dashboard-card-header">
                        <h3><?php esc_html_e('This Week', 'aqualuxe'); ?></h3>
                    </div>
                    
                    <div class="aqualuxe-admin-dashboard-card-content">
                        <p class="aqualuxe-admin-dashboard-card-value"><?php echo wc_price($sales_data['week']); ?></p>
                    </div>
                </div>
                
                <div class="aqualuxe-admin-dashboard-card">
                    <div class="aqualuxe-admin-dashboard-card-header">
                        <h3><?php esc_html_e('This Month', 'aqualuxe'); ?></h3>
                    </div>
                    
                    <div class="aqualuxe-admin-dashboard-card-content">
                        <p class="aqualuxe-admin-dashboard-card-value"><?php echo wc_price($sales_data['month']); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="aqualuxe-admin-dashboard-section">
                <h2><?php esc_html_e('Orders', 'aqualuxe'); ?></h2>
                
                <div class="aqualuxe-admin-dashboard-card">
                    <div class="aqualuxe-admin-dashboard-card-header">
                        <h3><?php esc_html_e('Pending', 'aqualuxe'); ?></h3>
                    </div>
                    
                    <div class="aqualuxe-admin-dashboard-card-content">
                        <p class="aqualuxe-admin-dashboard-card-value"><?php echo esc_html($order_data['pending']); ?></p>
                    </div>
                </div>
                
                <div class="aqualuxe-admin-dashboard-card">
                    <div class="aqualuxe-admin-dashboard-card-header">
                        <h3><?php esc_html_e('Processing', 'aqualuxe'); ?></h3>
                    </div>
                    
                    <div class="aqualuxe-admin-dashboard-card-content">
                        <p class="aqualuxe-admin-dashboard-card-value"><?php echo esc_html($order_data['processing']); ?></p>
                    </div>
                </div>
                
                <div class="aqualuxe-admin-dashboard-card">
                    <div class="aqualuxe-admin-dashboard-card-header">
                        <h3><?php esc_html_e('Completed', 'aqualuxe'); ?></h3>
                    </div>
                    
                    <div class="aqualuxe-admin-dashboard-card-content">
                        <p class="aqualuxe-admin-dashboard-card-value"><?php echo esc_html($order_data['completed']); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="aqualuxe-admin-dashboard-section">
                <h2><?php esc_html_e('Products', 'aqualuxe'); ?></h2>
                
                <div class="aqualuxe-admin-dashboard-card">
                    <div class="aqualuxe-admin-dashboard-card-header">
                        <h3><?php esc_html_e('Total Products', 'aqualuxe'); ?></h3>
                    </div>
                    
                    <div class="aqualuxe-admin-dashboard-card-content">
                        <p class="aqualuxe-admin-dashboard-card-value"><?php echo esc_html($product_data['total']); ?></p>
                    </div>
                </div>
                
                <div class="aqualuxe-admin-dashboard-card">
                    <div class="aqualuxe-admin-dashboard-card-header">
                        <h3><?php esc_html_e('Out of Stock', 'aqualuxe'); ?></h3>
                    </div>
                    
                    <div class="aqualuxe-admin-dashboard-card-content">
                        <p class="aqualuxe-admin-dashboard-card-value"><?php echo esc_html($product_data['out_of_stock']); ?></p>
                    </div>
                </div>
                
                <div class="aqualuxe-admin-dashboard-card">
                    <div class="aqualuxe-admin-dashboard-card-header">
                        <h3><?php esc_html_e('Low Stock', 'aqualuxe'); ?></h3>
                    </div>
                    
                    <div class="aqualuxe-admin-dashboard-card-content">
                        <p class="aqualuxe-admin-dashboard-card-value"><?php echo esc_html($product_data['low_stock']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Admin settings callback
 */
function aqualuxe_woocommerce_admin_settings_callback() {
    // Display admin settings
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('AquaLuxe Settings', 'aqualuxe'); ?></h1>
        
        <form method="post" action="options.php">
            <?php
            settings_fields('aqualuxe_settings');
            do_settings_sections('aqualuxe_settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Add custom product columns
 *
 * @param array $columns
 * @return array
 */
function aqualuxe_woocommerce_product_columns($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $column) {
        $new_columns[$key] = $column;
        
        if ($key === 'price') {
            $new_columns['stock_status'] = __('Stock Status', 'aqualuxe');
        }
    }
    
    return $new_columns;
}

/**
 * Add custom product column content
 *
 * @param string $column
 * @param int $post_id
 */
function aqualuxe_woocommerce_product_column_content($column, $post_id) {
    if ($column === 'stock_status') {
        $product = wc_get_product($post_id);
        
        if ($product) {
            if ($product->is_in_stock()) {
                echo '<span class="aqualuxe-stock-status in-stock">' . esc_html__('In Stock', 'aqualuxe') . '</span>';
            } else {
                echo '<span class="aqualuxe-stock-status out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
            }
        }
    }
}

/**
 * Add custom admin filters
 */
function aqualuxe_woocommerce_admin_filters() {
    global $typenow;
    
    if ($typenow === 'product') {
        // Add stock status filter
        $stock_statuses = array(
            'instock' => __('In Stock', 'aqualuxe'),
            'outofstock' => __('Out of Stock', 'aqualuxe'),
            'onbackorder' => __('On Backorder', 'aqualuxe'),
        );
        
        echo '<select name="stock_status">';
        echo '<option value="">' . esc_html__('All Stock Statuses', 'aqualuxe') . '</option>';
        
        foreach ($stock_statuses as $value => $label) {
            $selected = isset($_GET['stock_status']) && $_GET['stock_status'] === $value ? ' selected="selected"' : '';
            echo '<option value="' . esc_attr($value) . '"' . $selected . '>' . esc_html($label) . '</option>';
        }
        
        echo '</select>';
    }
}

/**
 * Add custom admin filter query
 *
 * @param object $query
 * @return object
 */
function aqualuxe_woocommerce_admin_filter_query($query) {
    global $typenow, $pagenow;
    
    if ($typenow === 'product' && $pagenow === 'edit.php' && isset($_GET['stock_status']) && $_GET['stock_status'] !== '') {
        $meta_query = $query->get('meta_query');
        
        if (!is_array($meta_query)) {
            $meta_query = array();
        }
        
        $meta_query[] = array(
            'key' => '_stock_status',
            'value' => sanitize_text_field($_GET['stock_status']),
            'compare' => '=',
        );
        
        $query->set('meta_query', $meta_query);
    }
    
    return $query;
}

/**
 * Add custom admin scripts
 */
function aqualuxe_woocommerce_admin_scripts() {
    wp_enqueue_style('aqualuxe-admin', AQUALUXE_ASSETS_URI . 'css/admin.css', array(), AQUALUXE_VERSION);
    wp_enqueue_script('aqualuxe-admin', AQUALUXE_ASSETS_URI . 'js/admin.js', array('jquery'), AQUALUXE_VERSION, true);
}

/**
 * Add custom admin notices
 */
function aqualuxe_woocommerce_admin_notices() {
    // Check if user has permission
    if (current_user_can('manage_woocommerce')) {
        // Check if WooCommerce is active
        if (!aqualuxe_is_woocommerce_active()) {
            ?>
            <div class="notice notice-warning">
                <p><?php esc_html_e('AquaLuxe theme works best with WooCommerce plugin. Please install and activate WooCommerce.', 'aqualuxe'); ?></p>
            </div>
            <?php
        }
    }
}

/**
 * Add custom meta boxes
 */
function aqualuxe_woocommerce_add_meta_boxes() {
    add_meta_box(
        'aqualuxe_product_options',
        __('AquaLuxe Product Options', 'aqualuxe'),
        'aqualuxe_woocommerce_product_options_meta_box',
        'product',
        'side',
        'default'
    );
}

/**
 * Product options meta box
 *
 * @param object $post
 */
function aqualuxe_woocommerce_product_options_meta_box($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_product_options_meta_box', 'aqualuxe_product_options_meta_box_nonce');
    
    // Get product options
    $featured = get_post_meta($post->ID, '_aqualuxe_featured', true);
    $new = get_post_meta($post->ID, '_aqualuxe_new', true);
    $sale = get_post_meta($post->ID, '_aqualuxe_sale', true);
    
    // Display product options
    ?>
    <p>
        <label for="aqualuxe_featured">
            <input type="checkbox" id="aqualuxe_featured" name="aqualuxe_featured" value="1" <?php checked($featured, '1'); ?>>
            <?php esc_html_e('Featured Product', 'aqualuxe'); ?>
        </label>
    </p>
    
    <p>
        <label for="aqualuxe_new">
            <input type="checkbox" id="aqualuxe_new" name="aqualuxe_new" value="1" <?php checked($new, '1'); ?>>
            <?php esc_html_e('New Product', 'aqualuxe'); ?>
        </label>
    </p>
    
    <p>
        <label for="aqualuxe_sale">
            <input type="checkbox" id="aqualuxe_sale" name="aqualuxe_sale" value="1" <?php checked($sale, '1'); ?>>
            <?php esc_html_e('Sale Product', 'aqualuxe'); ?>
        </label>
    </p>
    <?php
}

/**
 * Save meta boxes
 *
 * @param int $post_id
 * @param object $post
 */
function aqualuxe_woocommerce_save_meta_boxes($post_id, $post) {
    // Check if nonce is set
    if (!isset($_POST['aqualuxe_product_options_meta_box_nonce'])) {
        return;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['aqualuxe_product_options_meta_box_nonce'], 'aqualuxe_product_options_meta_box_nonce')) {
        return;
    }
    
    // Check if user has permission
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Check if autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check if post type is product
    if ($post->post_type !== 'product') {
        return;
    }
    
    // Save product options
    $featured = isset($_POST['aqualuxe_featured']) ? '1' : '';
    update_post_meta($post_id, '_aqualuxe_featured', $featured);
    
    $new = isset($_POST['aqualuxe_new']) ? '1' : '';
    update_post_meta($post_id, '_aqualuxe_new', $new);
    
    $sale = isset($_POST['aqualuxe_sale']) ? '1' : '';
    update_post_meta($post_id, '_aqualuxe_sale', $sale);
}

/**
 * Add custom settings page
 *
 * @param array $settings
 * @return array
 */
function aqualuxe_woocommerce_add_settings_page($settings) {
    $settings[] = include AQUALUXE_DIR . 'inc/woocommerce/class-aqualuxe-wc-settings.php';
    
    return $settings;
}

/**
 * Add custom reports
 *
 * @param array $reports
 * @return array
 */
function aqualuxe_woocommerce_add_reports($reports) {
    $reports['aqualuxe'] = array(
        'title' => __('AquaLuxe', 'aqualuxe'),
        'reports' => array(
            'sales' => array(
                'title' => __('Sales', 'aqualuxe'),
                'description' => __('View sales reports', 'aqualuxe'),
                'callback' => 'aqualuxe_woocommerce_sales_report',
            ),
            'products' => array(
                'title' => __('Products', 'aqualuxe'),
                'description' => __('View product reports', 'aqualuxe'),
                'callback' => 'aqualuxe_woocommerce_products_report',
            ),
        ),
    );
    
    return $reports;
}

/**
 * Sales report callback
 */
function aqualuxe_woocommerce_sales_report() {
    // Display sales report
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('AquaLuxe Sales Report', 'aqualuxe'); ?></h1>
        
        <p><?php esc_html_e('This is a custom sales report for AquaLuxe.', 'aqualuxe'); ?></p>
    </div>
    <?php
}

/**
 * Products report callback
 */
function aqualuxe_woocommerce_products_report() {
    // Display products report
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('AquaLuxe Products Report', 'aqualuxe'); ?></h1>
        
        <p><?php esc_html_e('This is a custom products report for AquaLuxe.', 'aqualuxe'); ?></p>
    </div>
    <?php
}

/**
 * Get mix manifest
 *
 * @return array
 */
function aqualuxe_get_mix_manifest() {
    $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
    
    if (file_exists($manifest_path)) {
        return json_decode(file_get_contents($manifest_path), true);
    }
    
    return array();
}

/**
 * Get asset version from mix manifest
 *
 * @param string $asset
 * @param array $manifest
 * @return string
 */
function aqualuxe_get_asset_version($asset, $manifest) {
    if (isset($manifest['/' . $asset])) {
        return str_replace('.js', '', str_replace('.css', '', $manifest['/' . $asset]));
    }
    
    return '';
}