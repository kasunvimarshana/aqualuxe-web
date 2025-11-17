<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
 *
 * @return void
 */
function aqualuxe_woocommerce_setup() {
    // Add theme support for WooCommerce
    add_theme_support(
        'woocommerce',
        array(
            'thumbnail_image_width' => 400,
            'single_image_width'    => 800,
            'product_grid'          => array(
                'default_rows'    => 3,
                'min_rows'        => 1,
                'default_columns' => 4,
                'min_columns'     => 1,
                'max_columns'     => 6,
            ),
        )
    );

    // Add theme support for WooCommerce features
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_setup');

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function aqualuxe_woocommerce_scripts() {
    // Enqueue WooCommerce styles
    wp_enqueue_style('aqualuxe-woocommerce-style', AQUALUXE_ASSETS_URI . 'css/woocommerce.css', array(), AQUALUXE_VERSION);

    // Add font-family and base font size for better typography
    $font_path = WC()->plugin_url() . '/assets/fonts/';
    $inline_font = '@font-face {
            font-family: "star";
            src: url("' . $font_path . 'star.eot");
            src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
                url("' . $font_path . 'star.woff") format("woff"),
                url("' . $font_path . 'star.ttf") format("truetype"),
                url("' . $font_path . 'star.svg#star") format("svg");
            font-weight: normal;
            font-style: normal;
        }';

    wp_add_inline_style('aqualuxe-woocommerce-style', $inline_font);

    // Enqueue WooCommerce scripts
    wp_enqueue_script('aqualuxe-woocommerce-script', AQUALUXE_ASSETS_URI . 'js/woocommerce.js', array('jquery'), AQUALUXE_VERSION, true);

    // Localize script for AJAX functionality
    wp_localize_script(
        'aqualuxe-woocommerce-script',
        'aqualuxe_woocommerce_params',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('aqualuxe-woocommerce-nonce'),
        )
    );
}
add_action('wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts');

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function aqualuxe_woocommerce_active_body_class($classes) {
    $classes[] = 'woocommerce-active';

    return $classes;
}
add_filter('body_class', 'aqualuxe_woocommerce_active_body_class');

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function aqualuxe_woocommerce_related_products_args($args) {
    $defaults = array(
        'posts_per_page' => 4,
        'columns'        => 4,
    );

    $args = wp_parse_args($defaults, $args);

    return $args;
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args');

/**
 * Remove default WooCommerce wrapper.
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

/**
 * Before Content.
 * Wraps all WooCommerce content in wrappers which match the theme markup.
 *
 * @return void
 */
function aqualuxe_woocommerce_wrapper_before() {
    ?>
    <main id="primary" class="site-main">
        <div class="container mx-auto px-4 py-8">
    <?php
}
add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before');

/**
 * After Content.
 * Closes the wrapping divs.
 *
 * @return void
 */
function aqualuxe_woocommerce_wrapper_after() {
    ?>
        </div>
    </main><!-- #main -->
    <?php
}
add_action('woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after');

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
 * <?php
 * if ( function_exists( 'aqualuxe_woocommerce_header_cart' ) ) {
 *     aqualuxe_woocommerce_header_cart();
 * }
 * ?>
 */

/**
 * Cart Fragments.
 * Ensure cart contents update when products are added to the cart via AJAX.
 *
 * @param array $fragments Fragments to refresh via AJAX.
 * @return array Fragments to refresh via AJAX.
 */
function aqualuxe_woocommerce_cart_fragments($fragments) {
    ob_start();
    aqualuxe_woocommerce_cart_link();
    $fragments['a.cart-contents'] = ob_get_clean();

    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_fragments');

/**
 * Cart Link.
 * Displayed a link to the cart including the number of items present and the cart total.
 *
 * @return void
 */
function aqualuxe_woocommerce_cart_link() {
    ?>
    <a class="cart-contents relative" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'aqualuxe'); ?>">
        <span class="cart-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </span>
        <span class="cart-count absolute -top-2 -right-2 bg-primary text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
            <?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
        </span>
    </a>
    <?php
}

/**
 * Header Cart.
 *
 * @return void
 */
function aqualuxe_woocommerce_header_cart() {
    if (is_cart()) {
        $class = 'current-menu-item';
    } else {
        $class = '';
    }
    ?>
    <div class="site-header-cart">
        <div class="<?php echo esc_attr($class); ?>">
            <?php aqualuxe_woocommerce_cart_link(); ?>
        </div>
        <div class="cart-dropdown hidden absolute right-0 top-full mt-2 w-80 bg-white shadow-lg rounded-lg z-50 p-4">
            <?php
            $instance = array(
                'title' => '',
            );
            the_widget('WC_Widget_Cart', $instance);
            ?>
        </div>
    </div>
    <?php
}

/**
 * Change number of products per row
 */
function aqualuxe_woocommerce_loop_columns() {
    return get_theme_mod('aqualuxe_product_columns', 4);
}
add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_columns');

/**
 * Change number of products per page
 */
function aqualuxe_woocommerce_products_per_page() {
    return get_theme_mod('aqualuxe_products_per_page', 12);
}
add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page');

/**
 * Reorder product meta
 */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 25);

/**
 * Remove default product tabs
 */
function aqualuxe_woocommerce_remove_product_tabs($tabs) {
    // Uncomment to remove specific tabs
    // unset($tabs['description']);
    // unset($tabs['reviews']);
    // unset($tabs['additional_information']);

    return $tabs;
}
// add_filter('woocommerce_product_tabs', 'aqualuxe_woocommerce_remove_product_tabs', 98);

/**
 * Add custom product tabs
 */
function aqualuxe_woocommerce_custom_product_tabs($tabs) {
    // Shipping tab
    $tabs['shipping'] = array(
        'title'    => __('Shipping', 'aqualuxe'),
        'priority' => 30,
        'callback' => 'aqualuxe_woocommerce_shipping_tab_content',
    );

    // Care tab for specific product categories
    global $product;
    if ($product && has_term(array('fish', 'plants', 'aquatic-animals'), 'product_cat', $product->get_id())) {
        $tabs['care'] = array(
            'title'    => __('Care Guide', 'aqualuxe'),
            'priority' => 40,
            'callback' => 'aqualuxe_woocommerce_care_tab_content',
        );
    }

    return $tabs;
}
add_filter('woocommerce_product_tabs', 'aqualuxe_woocommerce_custom_product_tabs');

/**
 * Shipping tab content
 */
function aqualuxe_woocommerce_shipping_tab_content() {
    // Get shipping content from theme mod or use default
    $shipping_content = get_theme_mod('aqualuxe_shipping_content', '');

    if (empty($shipping_content)) {
        // Default shipping content
        echo '<h2>' . esc_html__('Shipping Information', 'aqualuxe') . '</h2>';
        echo '<p>' . esc_html__('We ship worldwide with special care for live aquatic species. Shipping times and costs vary depending on your location and the products ordered.', 'aqualuxe') . '</p>';
        
        echo '<h3>' . esc_html__('Domestic Shipping', 'aqualuxe') . '</h3>';
        echo '<ul>';
        echo '<li>' . esc_html__('Standard Shipping: 3-5 business days', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('Express Shipping: 1-2 business days', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('Free shipping on orders over $100', 'aqualuxe') . '</li>';
        echo '</ul>';
        
        echo '<h3>' . esc_html__('International Shipping', 'aqualuxe') . '</h3>';
        echo '<ul>';
        echo '<li>' . esc_html__('Standard International: 7-14 business days', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('Express International: 3-5 business days', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('Please note that customs fees may apply', 'aqualuxe') . '</li>';
        echo '</ul>';
        
        echo '<p>' . esc_html__('For live fish and plants, we use special insulated packaging to ensure they arrive healthy and safe.', 'aqualuxe') . '</p>';
    } else {
        echo wp_kses_post($shipping_content);
    }
}

/**
 * Care guide tab content
 */
function aqualuxe_woocommerce_care_tab_content() {
    global $product;
    
    // Get product categories
    $product_cats = wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'slugs'));
    
    if (in_array('fish', $product_cats)) {
        // Fish care guide
        echo '<h2>' . esc_html__('Fish Care Guide', 'aqualuxe') . '</h2>';
        echo '<p>' . esc_html__('Proper care is essential for the health and longevity of your aquatic pets.', 'aqualuxe') . '</p>';
        
        echo '<h3>' . esc_html__('Water Parameters', 'aqualuxe') . '</h3>';
        echo '<ul>';
        echo '<li>' . esc_html__('Temperature: 72-78°F (22-26°C)', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('pH: 6.8-7.5', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('Ammonia: 0 ppm', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('Nitrite: 0 ppm', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('Nitrate: <20 ppm', 'aqualuxe') . '</li>';
        echo '</ul>';
        
        echo '<h3>' . esc_html__('Feeding', 'aqualuxe') . '</h3>';
        echo '<p>' . esc_html__('Feed 1-2 times daily, only as much as can be consumed within 2-3 minutes.', 'aqualuxe') . '</p>';
        
        echo '<h3>' . esc_html__('Maintenance', 'aqualuxe') . '</h3>';
        echo '<ul>';
        echo '<li>' . esc_html__('Weekly water changes of 25-30%', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('Regular filter maintenance', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('Monthly water parameter testing', 'aqualuxe') . '</li>';
        echo '</ul>';
    } elseif (in_array('plants', $product_cats)) {
        // Plant care guide
        echo '<h2>' . esc_html__('Plant Care Guide', 'aqualuxe') . '</h2>';
        echo '<p>' . esc_html__('Proper care will help your aquatic plants thrive and beautify your aquarium.', 'aqualuxe') . '</p>';
        
        echo '<h3>' . esc_html__('Lighting', 'aqualuxe') . '</h3>';
        echo '<p>' . esc_html__('Most aquatic plants need 8-10 hours of light daily. LED lights with the right spectrum are recommended.', 'aqualuxe') . '</p>';
        
        echo '<h3>' . esc_html__('Substrate', 'aqualuxe') . '</h3>';
        echo '<p>' . esc_html__('A nutrient-rich substrate is essential for root-feeding plants.', 'aqualuxe') . '</p>';
        
        echo '<h3>' . esc_html__('Fertilization', 'aqualuxe') . '</h3>';
        echo '<p>' . esc_html__('Regular dosing with liquid fertilizers or root tabs will provide essential nutrients.', 'aqualuxe') . '</p>';
        
        echo '<h3>' . esc_html__('CO2', 'aqualuxe') . '</h3>';
        echo '<p>' . esc_html__('For lush growth, consider adding a CO2 system to your aquarium.', 'aqualuxe') . '</p>';
    } else {
        // Generic care guide
        echo '<h2>' . esc_html__('Care Guide', 'aqualuxe') . '</h2>';
        echo '<p>' . esc_html__('Please refer to the product description for specific care instructions for this item.', 'aqualuxe') . '</p>';
        
        echo '<p>' . esc_html__('For additional help or specific questions about caring for your aquatic purchases, please contact our customer support team.', 'aqualuxe') . '</p>';
    }
}

/**
 * Add product short description after title on archive pages
 */
function aqualuxe_woocommerce_after_shop_loop_item_title() {
    global $product;
    
    if ($product && $product->get_short_description()) {
        echo '<div class="product-short-description mb-4">' . wp_kses_post($product->get_short_description()) . '</div>';
    }
}
// Uncomment to enable short description on archive pages
// add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_after_shop_loop_item_title', 5);

/**
 * Add sale flash badge with percentage
 */
function aqualuxe_woocommerce_sale_flash($text, $post, $product) {
    if ($product->is_on_sale() && $product->get_regular_price() > 0) {
        $percentage = round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100);
        return '<span class="onsale bg-red-600 text-white py-1 px-3 rounded-full text-xs font-bold">' . esc_html__('SAVE', 'aqualuxe') . ' ' . $percentage . '%</span>';
    }
    return $text;
}
add_filter('woocommerce_sale_flash', 'aqualuxe_woocommerce_sale_flash', 10, 3);

/**
 * Add "New" badge for recent products
 */
function aqualuxe_woocommerce_new_badge() {
    global $product;
    
    // Get product publish date
    $publish_date = strtotime($product->get_date_created());
    
    // If product is less than 30 days old, show new badge
    if ((time() - (30 * DAY_IN_SECONDS)) < $publish_date) {
        echo '<span class="new-badge bg-green-600 text-white py-1 px-3 rounded-full text-xs font-bold absolute top-0 right-0 m-2">' . esc_html__('NEW', 'aqualuxe') . '</span>';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_new_badge', 9);

/**
 * Add Quick View button
 */
function aqualuxe_woocommerce_quick_view_button() {
    global $product;
    
    // Check if quick view is enabled in customizer
    if (get_theme_mod('aqualuxe_quick_view', true)) {
        echo '<a href="#" class="quick-view-button button alt" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</a>';
    }
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_quick_view_button', 15);

/**
 * AJAX Quick View
 */
function aqualuxe_woocommerce_quick_view_ajax() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce-nonce')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
    }

    // Check product ID
    if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
        wp_send_json_error(array('message' => __('No product specified.', 'aqualuxe')));
    }

    $product_id = absint($_POST['product_id']);
    $product = wc_get_product($product_id);

    if (!$product) {
        wp_send_json_error(array('message' => __('Product not found.', 'aqualuxe')));
    }

    ob_start();
    ?>
    <div class="quick-view-content">
        <div class="quick-view-image">
            <?php echo $product->get_image('medium'); ?>
        </div>
        <div class="quick-view-details">
            <h2 class="product-title"><?php echo esc_html($product->get_name()); ?></h2>
            <div class="price"><?php echo $product->get_price_html(); ?></div>
            <div class="description"><?php echo wp_kses_post($product->get_short_description()); ?></div>
            
            <?php if ($product->is_in_stock()) : ?>
                <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype="multipart/form-data">
                    <?php if ($product->is_type('variable')) : ?>
                        <p><?php esc_html_e('This product has options. View full product for details.', 'aqualuxe'); ?></p>
                        <a href="<?php echo esc_url($product->get_permalink()); ?>" class="button"><?php esc_html_e('View Product', 'aqualuxe'); ?></a>
                    <?php else : ?>
                        <?php
                        woocommerce_quantity_input(
                            array(
                                'min_value'   => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                                'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                                'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(),
                            )
                        );
                        ?>
                        <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="single_add_to_cart_button button alt"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>
                    <?php endif; ?>
                </form>
            <?php else : ?>
                <p class="stock out-of-stock"><?php esc_html_e('This product is currently out of stock.', 'aqualuxe'); ?></p>
            <?php endif; ?>
            
            <a href="<?php echo esc_url($product->get_permalink()); ?>" class="view-full-details"><?php esc_html_e('View Full Details', 'aqualuxe'); ?></a>
        </div>
    </div>
    <?php
    $output = ob_get_clean();
    
    wp_send_json_success(array('content' => $output));
}
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_woocommerce_quick_view_ajax');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_woocommerce_quick_view_ajax');

/**
 * Add Wishlist functionality
 */
function aqualuxe_woocommerce_wishlist_button() {
    // Check if wishlist is enabled in customizer
    if (get_theme_mod('aqualuxe_wishlist', true)) {
        global $product;
        
        // Get current wishlist
        $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? json_decode(stripslashes($_COOKIE['aqualuxe_wishlist']), true) : array();
        
        // Check if product is in wishlist
        $in_wishlist = in_array($product->get_id(), $wishlist);
        
        echo '<a href="#" class="wishlist-toggle ' . ($in_wishlist ? 'in-wishlist' : '') . '" data-product-id="' . esc_attr($product->get_id()) . '" aria-label="' . esc_attr__('Add to wishlist', 'aqualuxe') . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="' . ($in_wishlist ? 'currentColor' : 'none') . '" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>';
        echo '</a>';
    }
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_wishlist_button', 20);

/**
 * AJAX Wishlist Toggle
 */
function aqualuxe_woocommerce_wishlist_toggle_ajax() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce-nonce')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
    }

    // Check product ID
    if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
        wp_send_json_error(array('message' => __('No product specified.', 'aqualuxe')));
    }

    $product_id = absint($_POST['product_id']);
    
    // Get current wishlist
    $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? json_decode(stripslashes($_COOKIE['aqualuxe_wishlist']), true) : array();
    
    // Toggle product in wishlist
    if (in_array($product_id, $wishlist)) {
        $wishlist = array_diff($wishlist, array($product_id));
        $status = 'removed';
    } else {
        $wishlist[] = $product_id;
        $status = 'added';
    }
    
    // Save updated wishlist
    setcookie('aqualuxe_wishlist', json_encode($wishlist), time() + (30 * DAY_IN_SECONDS), '/');
    
    wp_send_json_success(array(
        'status' => $status,
        'wishlist' => $wishlist,
        'count' => count($wishlist),
    ));
}
add_action('wp_ajax_aqualuxe_wishlist_toggle', 'aqualuxe_woocommerce_wishlist_toggle_ajax');
add_action('wp_ajax_nopriv_aqualuxe_wishlist_toggle', 'aqualuxe_woocommerce_wishlist_toggle_ajax');

/**
 * Add Wishlist page shortcode
 */
function aqualuxe_woocommerce_wishlist_shortcode() {
    // Get current wishlist
    $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? json_decode(stripslashes($_COOKIE['aqualuxe_wishlist']), true) : array();
    
    ob_start();
    
    if (empty($wishlist)) {
        echo '<div class="woocommerce-info">' . esc_html__('Your wishlist is empty.', 'aqualuxe') . '</div>';
        echo '<a href="' . esc_url(wc_get_page_permalink('shop')) . '" class="button">' . esc_html__('Browse Products', 'aqualuxe') . '</a>';
    } else {
        echo '<div class="wishlist-products">';
        
        foreach ($wishlist as $product_id) {
            $product = wc_get_product($product_id);
            
            if ($product) {
                ?>
                <div class="wishlist-product flex items-center mb-6 pb-6 border-b">
                    <div class="wishlist-product-image mr-4">
                        <a href="<?php echo esc_url($product->get_permalink()); ?>">
                            <?php echo $product->get_image('thumbnail'); ?>
                        </a>
                    </div>
                    <div class="wishlist-product-info flex-grow">
                        <h3 class="wishlist-product-title text-lg font-bold">
                            <a href="<?php echo esc_url($product->get_permalink()); ?>">
                                <?php echo esc_html($product->get_name()); ?>
                            </a>
                        </h3>
                        <div class="wishlist-product-price">
                            <?php echo $product->get_price_html(); ?>
                        </div>
                        <div class="wishlist-product-actions mt-2">
                            <?php if ($product->is_in_stock()) : ?>
                                <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="button add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr($product_id); ?>">
                                    <?php esc_html_e('Add to Cart', 'aqualuxe'); ?>
                                </a>
                            <?php else : ?>
                                <span class="stock out-of-stock"><?php esc_html_e('Out of Stock', 'aqualuxe'); ?></span>
                            <?php endif; ?>
                            <a href="#" class="remove-from-wishlist ml-4" data-product-id="<?php echo esc_attr($product_id); ?>">
                                <?php esc_html_e('Remove', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        
        echo '</div>';
        echo '<button class="clear-wishlist button alt mt-4">' . esc_html__('Clear Wishlist', 'aqualuxe') . '</button>';
    }
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_wishlist', 'aqualuxe_woocommerce_wishlist_shortcode');

/**
 * Add product filter widgets
 */
function aqualuxe_woocommerce_add_filter_widgets() {
    // Price Filter Widget
    if (class_exists('WC_Widget_Price_Filter')) {
        register_sidebar(
            array(
                'name'          => esc_html__('Shop Filters', 'aqualuxe'),
                'id'            => 'shop-filters',
                'description'   => esc_html__('Add widgets here to appear in shop sidebar.', 'aqualuxe'),
                'before_widget' => '<section id="%1$s" class="widget %2$s mb-8">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title text-lg font-bold mb-4">',
                'after_title'   => '</h2>',
            )
        );
    }
}
add_action('widgets_init', 'aqualuxe_woocommerce_add_filter_widgets');

/**
 * Add shop sidebar
 */
function aqualuxe_woocommerce_shop_sidebar() {
    if (is_active_sidebar('shop-filters')) {
        ?>
        <div id="shop-sidebar" class="shop-sidebar">
            <?php dynamic_sidebar('shop-filters'); ?>
        </div>
        <?php
    }
}
add_action('woocommerce_before_shop_loop', 'aqualuxe_woocommerce_shop_sidebar', 5);

/**
 * Modify breadcrumb args
 */
function aqualuxe_woocommerce_breadcrumb_args($args) {
    $args['delimiter'] = '<span class="mx-2">/</span>';
    $args['wrap_before'] = '<nav class="woocommerce-breadcrumb text-sm mb-6" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">';
    $args['wrap_after'] = '</nav>';
    
    return $args;
}
add_filter('woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumb_args');

/**
 * Add product structured data
 */
function aqualuxe_woocommerce_structured_data() {
    if (is_product()) {
        global $product;
        
        if (!$product) {
            return;
        }
        
        $shop_name = get_bloginfo('name');
        $shop_url = home_url('/');
        $currency = get_woocommerce_currency();
        $price = $product->get_price();
        
        $markup = array(
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $product->get_name(),
            'description' => wp_strip_all_tags($product->get_short_description() ? $product->get_short_description() : $product->get_description()),
            'sku' => $product->get_sku(),
            'brand' => array(
                '@type' => 'Brand',
                'name' => $shop_name,
            ),
            'offers' => array(
                '@type' => 'Offer',
                'url' => get_permalink(),
                'price' => $price,
                'priceCurrency' => $currency,
                'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller' => array(
                    '@type' => 'Organization',
                    'name' => $shop_name,
                    'url' => $shop_url,
                ),
            ),
        );
        
        // Add product image
        if ($product->get_image_id()) {
            $image_url = wp_get_attachment_image_url($product->get_image_id(), 'full');
            if ($image_url) {
                $markup['image'] = $image_url;
            }
        }
        
        // Add product ratings if available
        if ($product->get_rating_count() > 0) {
            $markup['aggregateRating'] = array(
                '@type' => 'AggregateRating',
                'ratingValue' => $product->get_average_rating(),
                'reviewCount' => $product->get_review_count(),
            );
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($markup) . '</script>';
    }
}
add_action('wp_footer', 'aqualuxe_woocommerce_structured_data');

/**
 * Add custom fields to product data tabs
 */
function aqualuxe_woocommerce_product_data_tabs($tabs) {
    $tabs['aqualuxe_options'] = array(
        'label'    => __('AquaLuxe Options', 'aqualuxe'),
        'target'   => 'aqualuxe_product_options',
        'class'    => array(),
        'priority' => 80,
    );
    
    return $tabs;
}
add_filter('woocommerce_product_data_tabs', 'aqualuxe_woocommerce_product_data_tabs');

/**
 * Add custom fields to product data panels
 */
function aqualuxe_woocommerce_product_data_panels() {
    ?>
    <div id="aqualuxe_product_options" class="panel woocommerce_options_panel">
        <div class="options_group">
            <?php
            // Featured Product
            woocommerce_wp_checkbox(
                array(
                    'id'          => '_aqualuxe_featured',
                    'label'       => __('Featured on Homepage', 'aqualuxe'),
                    'description' => __('Check this to feature this product on the homepage', 'aqualuxe'),
                )
            );
            
            // Video URL
            woocommerce_wp_text_input(
                array(
                    'id'          => '_aqualuxe_video_url',
                    'label'       => __('Product Video URL', 'aqualuxe'),
                    'description' => __('Enter a YouTube or Vimeo URL for product video', 'aqualuxe'),
                    'placeholder' => 'https://',
                    'desc_tip'    => true,
                )
            );
            
            // Specifications
            woocommerce_wp_textarea_input(
                array(
                    'id'          => '_aqualuxe_specifications',
                    'label'       => __('Product Specifications', 'aqualuxe'),
                    'description' => __('Enter product specifications (one per line)', 'aqualuxe'),
                    'desc_tip'    => true,
                )
            );
            
            // Care Instructions
            woocommerce_wp_textarea_input(
                array(
                    'id'          => '_aqualuxe_care_instructions',
                    'label'       => __('Care Instructions', 'aqualuxe'),
                    'description' => __('Enter care instructions for this product', 'aqualuxe'),
                    'desc_tip'    => true,
                )
            );
            ?>
        </div>
    </div>
    <?php
}
add_action('woocommerce_product_data_panels', 'aqualuxe_woocommerce_product_data_panels');

/**
 * Save custom fields
 */
function aqualuxe_woocommerce_process_product_meta($post_id) {
    // Featured Product
    $featured = isset($_POST['_aqualuxe_featured']) ? 'yes' : 'no';
    update_post_meta($post_id, '_aqualuxe_featured', $featured);
    
    // Video URL
    if (isset($_POST['_aqualuxe_video_url'])) {
        update_post_meta($post_id, '_aqualuxe_video_url', esc_url_raw($_POST['_aqualuxe_video_url']));
    }
    
    // Specifications
    if (isset($_POST['_aqualuxe_specifications'])) {
        update_post_meta($post_id, '_aqualuxe_specifications', sanitize_textarea_field($_POST['_aqualuxe_specifications']));
    }
    
    // Care Instructions
    if (isset($_POST['_aqualuxe_care_instructions'])) {
        update_post_meta($post_id, '_aqualuxe_care_instructions', sanitize_textarea_field($_POST['_aqualuxe_care_instructions']));
    }
}
add_action('woocommerce_process_product_meta', 'aqualuxe_woocommerce_process_product_meta');

/**
 * Display custom fields on product page
 */
function aqualuxe_woocommerce_display_custom_fields() {
    global $product;
    
    // Video
    $video_url = get_post_meta($product->get_id(), '_aqualuxe_video_url', true);
    if (!empty($video_url)) {
        echo '<div class="product-video mt-8">';
        echo '<h3 class="text-xl font-bold mb-4">' . esc_html__('Product Video', 'aqualuxe') . '</h3>';
        
        // Check if YouTube
        if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
            // Extract YouTube ID
            preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video_url, $matches);
            if (isset($matches[1])) {
                echo '<div class="video-container relative overflow-hidden pb-[56.25%] h-0">';
                echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . esc_attr($matches[1]) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="absolute top-0 left-0 w-full h-full"></iframe>';
                echo '</div>';
            }
        } 
        // Check if Vimeo
        elseif (strpos($video_url, 'vimeo.com') !== false) {
            // Extract Vimeo ID
            preg_match('/vimeo\.com\/(?:video\/)?([0-9]+)/', $video_url, $matches);
            if (isset($matches[1])) {
                echo '<div class="video-container relative overflow-hidden pb-[56.25%] h-0">';
                echo '<iframe src="https://player.vimeo.com/video/' . esc_attr($matches[1]) . '" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen class="absolute top-0 left-0 w-full h-full"></iframe>';
                echo '</div>';
            }
        } else {
            echo '<a href="' . esc_url($video_url) . '" target="_blank" class="button">' . esc_html__('Watch Video', 'aqualuxe') . '</a>';
        }
        
        echo '</div>';
    }
    
    // Specifications
    $specifications = get_post_meta($product->get_id(), '_aqualuxe_specifications', true);
    if (!empty($specifications)) {
        echo '<div class="product-specifications mt-8">';
        echo '<h3 class="text-xl font-bold mb-4">' . esc_html__('Product Specifications', 'aqualuxe') . '</h3>';
        echo '<ul class="specifications-list space-y-2">';
        
        $specs = explode("\n", $specifications);
        foreach ($specs as $spec) {
            if (!empty(trim($spec))) {
                echo '<li class="flex items-center">';
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                echo esc_html(trim($spec));
                echo '</li>';
            }
        }
        
        echo '</ul>';
        echo '</div>';
    }
    
    // Care Instructions
    $care_instructions = get_post_meta($product->get_id(), '_aqualuxe_care_instructions', true);
    if (!empty($care_instructions)) {
        echo '<div class="product-care mt-8">';
        echo '<h3 class="text-xl font-bold mb-4">' . esc_html__('Care Instructions', 'aqualuxe') . '</h3>';
        echo '<div class="care-instructions prose">';
        echo wp_kses_post(wpautop($care_instructions));
        echo '</div>';
        echo '</div>';
    }
}
add_action('woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_display_custom_fields', 15);

/**
 * Add recently viewed products
 */
function aqualuxe_woocommerce_track_product_view() {
    if (!is_singular('product')) {
        return;
    }

    global $post;

    if (empty($_COOKIE['woocommerce_recently_viewed'])) {
        $viewed_products = array();
    } else {
        $viewed_products = wp_parse_id_list((array) explode('|', wp_unslash($_COOKIE['woocommerce_recently_viewed'])));
    }

    // Remove current product
    $viewed_products = array_diff($viewed_products, array($post->ID));

    // Add new product to start of array
    array_unshift($viewed_products, $post->ID);

    // Limit to 15 items
    if (count($viewed_products) > 15) {
        $viewed_products = array_slice($viewed_products, 0, 15);
    }

    // Store for session only
    wc_setcookie('woocommerce_recently_viewed', implode('|', $viewed_products));
}
add_action('template_redirect', 'aqualuxe_woocommerce_track_product_view', 20);

/**
 * Display recently viewed products
 */
function aqualuxe_woocommerce_recently_viewed_products() {
    if (!is_singular('product') || !isset($_COOKIE['woocommerce_recently_viewed'])) {
        return;
    }

    $viewed_products = wp_parse_id_list((array) explode('|', wp_unslash($_COOKIE['woocommerce_recently_viewed'])));
    $current_product = get_the_ID();
    
    // Remove current product
    $viewed_products = array_diff($viewed_products, array($current_product));
    
    if (empty($viewed_products)) {
        return;
    }

    // Limit to 4 products
    $viewed_products = array_slice($viewed_products, 0, 4);

    $args = array(
        'posts_per_page' => 4,
        'post__in'       => $viewed_products,
        'post_type'      => 'product',
        'orderby'        => 'post__in',
        'post_status'    => 'publish',
    );

    $products = new WP_Query($args);

    if ($products->have_posts()) {
        echo '<section class="recently-viewed-products mt-12 pt-8 border-t border-gray-200">';
        echo '<h2 class="text-2xl font-bold mb-6">' . esc_html__('Recently Viewed Products', 'aqualuxe') . '</h2>';
        echo '<div class="products grid grid-cols-2 md:grid-cols-4 gap-6">';
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        echo '</div>';
        echo '</section>';
    }

    wp_reset_postdata();
}
add_action('woocommerce_after_single_product', 'aqualuxe_woocommerce_recently_viewed_products', 20);

/**
 * Add product category description to category archive
 */
function aqualuxe_woocommerce_category_description() {
    if (is_product_category()) {
        $category = get_queried_object();
        $description = term_description($category->term_id, 'product_cat');
        
        if (!empty($description)) {
            echo '<div class="category-description mb-8 prose max-w-none">' . wp_kses_post($description) . '</div>';
        }
    }
}
add_action('woocommerce_archive_description', 'aqualuxe_woocommerce_category_description', 10);

/**
 * Add product category image to category archive
 */
function aqualuxe_woocommerce_category_image() {
    if (is_product_category()) {
        $category = get_queried_object();
        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
        
        if ($thumbnail_id) {
            $image = wp_get_attachment_image_src($thumbnail_id, 'full');
            if ($image) {
                echo '<div class="category-image mb-8">';
                echo '<img src="' . esc_url($image[0]) . '" alt="' . esc_attr($category->name) . '" class="w-full h-auto rounded">';
                echo '</div>';
            }
        }
    }
}
add_action('woocommerce_archive_description', 'aqualuxe_woocommerce_category_image', 5);

/**
 * Add estimated delivery date to product page
 */
function aqualuxe_woocommerce_estimated_delivery() {
    // Get current date
    $current_date = new DateTime();
    
    // Add 3-5 business days
    $min_days = 3;
    $max_days = 5;
    
    // Calculate min delivery date (skip weekends)
    $min_delivery_date = clone $current_date;
    $days_added = 0;
    while ($days_added < $min_days) {
        $min_delivery_date->modify('+1 day');
        // Skip weekends (6 = Saturday, 0 = Sunday)
        if ($min_delivery_date->format('w') != 0 && $min_delivery_date->format('w') != 6) {
            $days_added++;
        }
    }
    
    // Calculate max delivery date (skip weekends)
    $max_delivery_date = clone $current_date;
    $days_added = 0;
    while ($days_added < $max_days) {
        $max_delivery_date->modify('+1 day');
        // Skip weekends (6 = Saturday, 0 = Sunday)
        if ($max_delivery_date->format('w') != 0 && $max_delivery_date->format('w') != 6) {
            $days_added++;
        }
    }
    
    // Format dates
    $min_date_formatted = $min_delivery_date->format('M j');
    $max_date_formatted = $max_delivery_date->format('M j');
    
    echo '<div class="estimated-delivery mt-4 text-sm">';
    echo '<p><strong>' . esc_html__('Estimated Delivery:', 'aqualuxe') . '</strong> ' . esc_html($min_date_formatted) . ' - ' . esc_html($max_date_formatted) . '</p>';
    echo '</div>';
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_estimated_delivery', 35);

/**
 * Add trust badges to checkout
 */
function aqualuxe_woocommerce_checkout_trust_badges() {
    ?>
    <div class="checkout-trust-badges mt-8 pt-8 border-t border-gray-200">
        <h3 class="text-lg font-bold mb-4"><?php esc_html_e('Safe & Secure Checkout', 'aqualuxe'); ?></h3>
        <div class="trust-badges flex flex-wrap justify-center gap-4">
            <div class="trust-badge flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span><?php esc_html_e('Secure Checkout', 'aqualuxe'); ?></span>
            </div>
            <div class="trust-badge flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span><?php esc_html_e('Privacy Protected', 'aqualuxe'); ?></span>
            </div>
            <div class="trust-badge flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span><?php esc_html_e('Multiple Payment Options', 'aqualuxe'); ?></span>
            </div>
        </div>
    </div>
    <?php
}
add_action('woocommerce_review_order_after_payment', 'aqualuxe_woocommerce_checkout_trust_badges');

/**
 * Add cross-sells to cart page
 */
function aqualuxe_woocommerce_cross_sell_display() {
    // Remove default cross-sells
    remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
    
    // Add custom cross-sells
    add_action('woocommerce_after_cart', 'woocommerce_cross_sell_display');
}
add_action('init', 'aqualuxe_woocommerce_cross_sell_display');

/**
 * Modify cross-sells display
 */
function aqualuxe_woocommerce_cross_sells_columns($columns) {
    return 4;
}
add_filter('woocommerce_cross_sells_columns', 'aqualuxe_woocommerce_cross_sells_columns');

/**
 * Modify cross-sells total
 */
function aqualuxe_woocommerce_cross_sells_total($total) {
    return 4;
}
add_filter('woocommerce_cross_sells_total', 'aqualuxe_woocommerce_cross_sells_total');

/**
 * Add size guide to product page
 */
function aqualuxe_woocommerce_size_guide() {
    global $product;
    
    // Only show for specific categories
    if (!has_term(array('fish', 'aquariums', 'equipment'), 'product_cat', $product->get_id())) {
        return;
    }
    
    ?>
    <div class="size-guide-wrapper mt-4">
        <button type="button" class="size-guide-toggle text-sm underline" data-toggle="size-guide-modal">
            <?php esc_html_e('Size Guide', 'aqualuxe'); ?>
        </button>
        
        <div id="size-guide-modal" class="size-guide-modal hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="size-guide-content bg-white p-6 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="size-guide-header flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold"><?php esc_html_e('Size Guide', 'aqualuxe'); ?></h3>
                    <button type="button" class="size-guide-close">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <?php if (has_term('fish', 'product_cat', $product->get_id())) : ?>
                    <div class="size-guide-fish">
                        <p class="mb-4"><?php esc_html_e('Fish sizes are measured in inches or centimeters from head to tail when fully grown.', 'aqualuxe'); ?></p>
                        
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border p-2 text-left"><?php esc_html_e('Size Category', 'aqualuxe'); ?></th>
                                    <th class="border p-2 text-left"><?php esc_html_e('Length (inches)', 'aqualuxe'); ?></th>
                                    <th class="border p-2 text-left"><?php esc_html_e('Length (cm)', 'aqualuxe'); ?></th>
                                    <th class="border p-2 text-left"><?php esc_html_e('Recommended Tank Size', 'aqualuxe'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border p-2"><?php esc_html_e('Nano', 'aqualuxe'); ?></td>
                                    <td class="border p-2">< 1"</td>
                                    <td class="border p-2">< 2.5 cm</td>
                                    <td class="border p-2">5+ gallons</td>
                                </tr>
                                <tr>
                                    <td class="border p-2"><?php esc_html_e('Small', 'aqualuxe'); ?></td>
                                    <td class="border p-2">1-2"</td>
                                    <td class="border p-2">2.5-5 cm</td>
                                    <td class="border p-2">10+ gallons</td>
                                </tr>
                                <tr>
                                    <td class="border p-2"><?php esc_html_e('Medium', 'aqualuxe'); ?></td>
                                    <td class="border p-2">2-4"</td>
                                    <td class="border p-2">5-10 cm</td>
                                    <td class="border p-2">20+ gallons</td>
                                </tr>
                                <tr>
                                    <td class="border p-2"><?php esc_html_e('Large', 'aqualuxe'); ?></td>
                                    <td class="border p-2">4-6"</td>
                                    <td class="border p-2">10-15 cm</td>
                                    <td class="border p-2">30+ gallons</td>
                                </tr>
                                <tr>
                                    <td class="border p-2"><?php esc_html_e('Extra Large', 'aqualuxe'); ?></td>
                                    <td class="border p-2">6"+ </td>
                                    <td class="border p-2">15+ cm</td>
                                    <td class="border p-2">55+ gallons</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php elseif (has_term('aquariums', 'product_cat', $product->get_id())) : ?>
                    <div class="size-guide-aquariums">
                        <p class="mb-4"><?php esc_html_e('Aquarium sizes are measured by their volume capacity and dimensions.', 'aqualuxe'); ?></p>
                        
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border p-2 text-left"><?php esc_html_e('Tank Size', 'aqualuxe'); ?></th>
                                    <th class="border p-2 text-left"><?php esc_html_e('Dimensions (inches)', 'aqualuxe'); ?></th>
                                    <th class="border p-2 text-left"><?php esc_html_e('Dimensions (cm)', 'aqualuxe'); ?></th>
                                    <th class="border p-2 text-left"><?php esc_html_e('Weight When Full', 'aqualuxe'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border p-2">5 gallons</td>
                                    <td class="border p-2">16" x 8" x 10"</td>
                                    <td class="border p-2">40 x 20 x 25 cm</td>
                                    <td class="border p-2">~62 lbs / 28 kg</td>
                                </tr>
                                <tr>
                                    <td class="border p-2">10 gallons</td>
                                    <td class="border p-2">20" x 10" x 12"</td>
                                    <td class="border p-2">50 x 25 x 30 cm</td>
                                    <td class="border p-2">~111 lbs / 50 kg</td>
                                </tr>
                                <tr>
                                    <td class="border p-2">20 gallons</td>
                                    <td class="border p-2">24" x 12" x 16"</td>
                                    <td class="border p-2">60 x 30 x 40 cm</td>
                                    <td class="border p-2">~225 lbs / 102 kg</td>
                                </tr>
                                <tr>
                                    <td class="border p-2">30 gallons</td>
                                    <td class="border p-2">36" x 12" x 16"</td>
                                    <td class="border p-2">90 x 30 x 40 cm</td>
                                    <td class="border p-2">~348 lbs / 158 kg</td>
                                </tr>
                                <tr>
                                    <td class="border p-2">55 gallons</td>
                                    <td class="border p-2">48" x 13" x 21"</td>
                                    <td class="border p-2">122 x 33 x 53 cm</td>
                                    <td class="border p-2">~625 lbs / 283 kg</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="size-guide-equipment">
                        <p class="mb-4"><?php esc_html_e('Equipment sizes vary by product type. Please refer to product specifications for detailed dimensions.', 'aqualuxe'); ?></p>
                        
                        <p><?php esc_html_e('For assistance in selecting the right equipment size for your aquarium, please contact our customer support team.', 'aqualuxe'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}
add_action('woocommerce_before_add_to_cart_button', 'aqualuxe_woocommerce_size_guide');

/**
 * Add stock status with custom messages
 */
function aqualuxe_woocommerce_stock_status() {
    global $product;
    
    if (!$product->is_in_stock()) {
        echo '<div class="stock-status out-of-stock mb-4 text-red-600">';
        echo '<span class="stock-icon mr-1">●</span> ';
        echo esc_html__('Out of Stock', 'aqualuxe');
        echo '</div>';
    } elseif ($product->is_on_backorder('notify')) {
        echo '<div class="stock-status on-backorder mb-4 text-orange-600">';
        echo '<span class="stock-icon mr-1">●</span> ';
        echo esc_html__('Available on Backorder', 'aqualuxe');
        echo '</div>';
    } elseif ($product->get_stock_quantity() && $product->get_stock_quantity() <= 5) {
        echo '<div class="stock-status low-stock mb-4 text-orange-600">';
        echo '<span class="stock-icon mr-1">●</span> ';
        echo sprintf(esc_html__('Low Stock: Only %s left', 'aqualuxe'), $product->get_stock_quantity());
        echo '</div>';
    } else {
        echo '<div class="stock-status in-stock mb-4 text-green-600">';
        echo '<span class="stock-icon mr-1">●</span> ';
        echo esc_html__('In Stock', 'aqualuxe');
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_stock_status', 29);

/**
 * Add shipping information to product page
 */
function aqualuxe_woocommerce_shipping_info() {
    echo '<div class="shipping-info mt-4 text-sm">';
    echo '<p><strong>' . esc_html__('Shipping:', 'aqualuxe') . '</strong> ';
    
    // Check if product is virtual
    global $product;
    if ($product->is_virtual()) {
        echo esc_html__('Digital product - no shipping required', 'aqualuxe');
    } else {
        echo esc_html__('Free shipping on orders over $100', 'aqualuxe');
    }
    
    echo '</p>';
    echo '</div>';
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_shipping_info', 40);

/**
 * Add social sharing buttons to product page
 */
function aqualuxe_woocommerce_social_share() {
    global $product;
    
    $product_url = urlencode(get_permalink());
    $product_title = urlencode(get_the_title());
    $product_image = urlencode(wp_get_attachment_url($product->get_image_id()));
    
    echo '<div class="social-share mt-6 pt-6 border-t border-gray-200">';
    echo '<h4 class="text-sm font-bold mb-2">' . esc_html__('Share This Product:', 'aqualuxe') . '</h4>';
    echo '<div class="share-buttons flex space-x-2">';
    
    // Facebook
    echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $product_url . '" target="_blank" rel="noopener noreferrer" class="share-button facebook p-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors">';
    echo '<span class="screen-reader-text">' . esc_html__('Share on Facebook', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>';
    echo '</a>';
    
    // Twitter
    echo '<a href="https://twitter.com/intent/tweet?text=' . $product_title . '&url=' . $product_url . '" target="_blank" rel="noopener noreferrer" class="share-button twitter p-2 bg-blue-400 text-white rounded-full hover:bg-blue-500 transition-colors">';
    echo '<span class="screen-reader-text">' . esc_html__('Share on Twitter', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>';
    echo '</a>';
    
    // Pinterest
    echo '<a href="https://pinterest.com/pin/create/button/?url=' . $product_url . '&media=' . $product_image . '&description=' . $product_title . '" target="_blank" rel="noopener noreferrer" class="share-button pinterest p-2 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors">';
    echo '<span class="screen-reader-text">' . esc_html__('Share on Pinterest', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z" fill-rule="evenodd" clip-rule="evenodd"/></svg>';
    echo '</a>';
    
    // Email
    echo '<a href="mailto:?subject=' . $product_title . '&body=' . esc_html__('Check out this product:', 'aqualuxe') . ' ' . $product_url . '" class="share-button email p-2 bg-gray-600 text-white rounded-full hover:bg-gray-700 transition-colors">';
    echo '<span class="screen-reader-text">' . esc_html__('Share via Email', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>';
    echo '</a>';
    
    echo '</div>';
    echo '</div>';
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_social_share', 50);

/**
 * Add product inquiry form
 */
function aqualuxe_woocommerce_product_inquiry() {
    global $product;
    
    echo '<div class="product-inquiry mt-8 pt-8 border-t border-gray-200">';
    echo '<h3 class="text-xl font-bold mb-4">' . esc_html__('Have a Question?', 'aqualuxe') . '</h3>';
    
    echo '<form class="inquiry-form" id="product-inquiry-form">';
    echo '<input type="hidden" name="product_id" value="' . esc_attr($product->get_id()) . '">';
    echo '<input type="hidden" name="product_name" value="' . esc_attr($product->get_name()) . '">';
    
    echo '<div class="form-row mb-4">';
    echo '<label for="inquiry-name" class="block mb-2">' . esc_html__('Your Name', 'aqualuxe') . ' <span class="required">*</span></label>';
    echo '<input type="text" id="inquiry-name" name="inquiry-name" class="w-full p-2 border border-gray-300 rounded" required>';
    echo '</div>';
    
    echo '<div class="form-row mb-4">';
    echo '<label for="inquiry-email" class="block mb-2">' . esc_html__('Your Email', 'aqualuxe') . ' <span class="required">*</span></label>';
    echo '<input type="email" id="inquiry-email" name="inquiry-email" class="w-full p-2 border border-gray-300 rounded" required>';
    echo '</div>';
    
    echo '<div class="form-row mb-4">';
    echo '<label for="inquiry-message" class="block mb-2">' . esc_html__('Your Question', 'aqualuxe') . ' <span class="required">*</span></label>';
    echo '<textarea id="inquiry-message" name="inquiry-message" rows="4" class="w-full p-2 border border-gray-300 rounded" required></textarea>';
    echo '</div>';
    
    echo '<div class="form-row">';
    echo '<button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded transition-colors">' . esc_html__('Send Inquiry', 'aqualuxe') . '</button>';
    echo '</div>';
    
    echo '<div class="inquiry-response mt-4 hidden"></div>';
    
    echo '</form>';
    echo '</div>';
}
add_action('woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_inquiry', 25);

/**
 * AJAX Product Inquiry
 */
function aqualuxe_woocommerce_product_inquiry_ajax() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce-nonce')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
    }

    // Get form data
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $product_name = isset($_POST['product_name']) ? sanitize_text_field($_POST['product_name']) : '';
    $name = isset($_POST['inquiry_name']) ? sanitize_text_field($_POST['inquiry_name']) : '';
    $email = isset($_POST['inquiry_email']) ? sanitize_email($_POST['inquiry_email']) : '';
    $message = isset($_POST['inquiry_message']) ? sanitize_textarea_field($_POST['inquiry_message']) : '';

    // Validate form data
    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error(array('message' => __('Please fill in all required fields.', 'aqualuxe')));
    }

    if (!is_email($email)) {
        wp_send_json_error(array('message' => __('Please enter a valid email address.', 'aqualuxe')));
    }

    // Set up email
    $to = get_option('admin_email');
    $subject = sprintf(__('Product Inquiry: %s', 'aqualuxe'), $product_name);
    
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $name . ' <' . $email . '>',
        'Reply-To: ' . $email,
    );

    $email_content = sprintf(
        '<p><strong>%1$s:</strong> %2$s</p>
        <p><strong>%3$s:</strong> %4$s</p>
        <p><strong>%5$s:</strong> %6$s</p>
        <p><strong>%7$s:</strong> %8$s</p>
        <p><strong>%9$s:</strong></p>
        <p>%10$s</p>',
        __('Product', 'aqualuxe'),
        $product_name,
        __('Product ID', 'aqualuxe'),
        $product_id,
        __('Name', 'aqualuxe'),
        $name,
        __('Email', 'aqualuxe'),
        $email,
        __('Message', 'aqualuxe'),
        nl2br($message)
    );

    // Send email
    $sent = wp_mail($to, $subject, $email_content, $headers);

    if ($sent) {
        wp_send_json_success(array('message' => __('Thank you for your inquiry. We will get back to you soon!', 'aqualuxe')));
    } else {
        wp_send_json_error(array('message' => __('There was an error sending your inquiry. Please try again.', 'aqualuxe')));
    }
}
add_action('wp_ajax_aqualuxe_product_inquiry', 'aqualuxe_woocommerce_product_inquiry_ajax');
add_action('wp_ajax_nopriv_aqualuxe_product_inquiry', 'aqualuxe_woocommerce_product_inquiry_ajax');

/**
 * Add product brand display
 */
function aqualuxe_woocommerce_product_brand() {
    global $product;
    
    // Check if product has brand taxonomy
    if (taxonomy_exists('product_brand')) {
        $brands = get_the_terms($product->get_id(), 'product_brand');
        
        if ($brands && !is_wp_error($brands)) {
            echo '<div class="product-brand mb-2">';
            echo '<span class="text-sm text-gray-600">' . esc_html__('Brand:', 'aqualuxe') . ' </span>';
            
            $brand_links = array();
            foreach ($brands as $brand) {
                $brand_links[] = '<a href="' . esc_url(get_term_link($brand)) . '" class="text-primary hover:underline">' . esc_html($brand->name) . '</a>';
            }
            
            echo implode(', ', $brand_links);
            echo '</div>';
        }
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_brand', 6);

/**
 * Add product tags display
 */
function aqualuxe_woocommerce_product_tags() {
    global $product;
    
    $tags = get_the_terms($product->get_id(), 'product_tag');
    
    if ($tags && !is_wp_error($tags)) {
        echo '<div class="product-tags mt-4">';
        echo '<span class="text-sm text-gray-600">' . esc_html__('Tags:', 'aqualuxe') . ' </span>';
        
        $tag_links = array();
        foreach ($tags as $tag) {
            $tag_links[] = '<a href="' . esc_url(get_term_link($tag)) . '" class="inline-block bg-gray-100 hover:bg-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 mr-2 mb-2 transition-colors">' . esc_html($tag->name) . '</a>';
        }
        
        echo implode('', $tag_links);
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_tags', 45);

/**
 * Add product SKU display
 */
function aqualuxe_woocommerce_product_sku() {
    global $product;
    
    if ($product->get_sku()) {
        echo '<div class="product-sku mb-2">';
        echo '<span class="text-sm text-gray-600">' . esc_html__('SKU:', 'aqualuxe') . ' </span>';
        echo '<span class="sku">' . esc_html($product->get_sku()) . '</span>';
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_sku', 7);

/**
 * Add product categories display
 */
function aqualuxe_woocommerce_product_categories() {
    global $product;
    
    $categories = get_the_terms($product->get_id(), 'product_cat');
    
    if ($categories && !is_wp_error($categories)) {
        echo '<div class="product-categories mb-2">';
        echo '<span class="text-sm text-gray-600">' . esc_html__('Categories:', 'aqualuxe') . ' </span>';
        
        $category_links = array();
        foreach ($categories as $category) {
            $category_links[] = '<a href="' . esc_url(get_term_link($category)) . '" class="text-primary hover:underline">' . esc_html($category->name) . '</a>';
        }
        
        echo implode(', ', $category_links);
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_categories', 5);

/**
 * Add product guarantee
 */
function aqualuxe_woocommerce_product_guarantee() {
    echo '<div class="product-guarantee mt-6 flex items-center">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>';
    echo '<span class="text-sm">' . esc_html__('100% Satisfaction Guarantee', 'aqualuxe') . '</span>';
    echo '</div>';
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_guarantee', 41);

/**
 * Add product reviews summary
 */
function aqualuxe_woocommerce_review_summary() {
    global $product;
    
    if ($product->get_review_count() > 0) {
        $average_rating = $product->get_average_rating();
        $review_count = $product->get_review_count();
        
        echo '<div class="review-summary mb-4 flex items-center">';
        
        // Display stars
        echo '<div class="star-rating" title="' . sprintf(esc_attr__('Rated %s out of 5', 'aqualuxe'), $average_rating) . '">';
        echo wc_get_star_rating_html($average_rating, $review_count);
        echo '</div>';
        
        // Display count
        echo '<a href="#reviews" class="review-count ml-2 text-sm hover:underline">';
        echo sprintf(_n('%s review', '%s reviews', $review_count, 'aqualuxe'), $review_count);
        echo '</a>';
        
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_review_summary', 11);

/**
 * Add product review breakdown
 */
function aqualuxe_woocommerce_review_breakdown() {
    global $product;
    
    if ($product->get_review_count() > 0) {
        $rating_counts = $product->get_rating_counts();
        $average_rating = $product->get_average_rating();
        $review_count = $product->get_review_count();
        
        echo '<div class="review-breakdown mb-6">';
        echo '<h3 class="text-lg font-bold mb-4">' . esc_html__('Customer Reviews', 'aqualuxe') . '</h3>';
        
        echo '<div class="rating-overview flex items-center mb-4">';
        echo '<div class="average-rating text-3xl font-bold mr-4">' . esc_html(number_format($average_rating, 1)) . '</div>';
        
        echo '<div class="rating-stars">';
        echo wc_get_star_rating_html($average_rating, $review_count);
        echo '<div class="review-count text-sm">';
        echo sprintf(_n('%s review', '%s reviews', $review_count, 'aqualuxe'), $review_count);
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        echo '<div class="rating-bars">';
        for ($i = 5; $i >= 1; $i--) {
            $count = isset($rating_counts[$i]) ? $rating_counts[$i] : 0;
            $percentage = ($review_count > 0) ? ($count / $review_count) * 100 : 0;
            
            echo '<div class="rating-bar flex items-center mb-1">';
            echo '<div class="stars mr-2 w-16 text-sm">' . sprintf(esc_html__('%d stars', 'aqualuxe'), $i) . '</div>';
            echo '<div class="bar-container flex-grow bg-gray-200 rounded-full h-2">';
            echo '<div class="bar bg-yellow-400 rounded-full h-2" style="width:' . esc_attr($percentage) . '%"></div>';
            echo '</div>';
            echo '<div class="count ml-2 w-10 text-sm text-right">' . esc_html($count) . '</div>';
            echo '</div>';
        }
        echo '</div>';
        
        echo '</div>';
    }
}
add_action('woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_review_breakdown', 11);

/**
 * Add product review form title
 */
function aqualuxe_woocommerce_review_form_title() {
    echo '<h3 class="text-lg font-bold mb-4">' . esc_html__('Write a Review', 'aqualuxe') . '</h3>';
}
add_action('woocommerce_review_before_comment_form', 'aqualuxe_woocommerce_review_form_title');

/**
 * Add product review form note
 */
function aqualuxe_woocommerce_review_form_note() {
    echo '<p class="review-form-note text-sm text-gray-600 mb-4">' . esc_html__('Your email address will not be published. Required fields are marked *', 'aqualuxe') . '</p>';
}
add_action('woocommerce_review_before_comment_form', 'aqualuxe_woocommerce_review_form_note', 5);

/**
 * Add product review verification badge
 */
function aqualuxe_woocommerce_review_verification_badge($comment) {
    if (wc_review_is_from_verified_owner($comment->comment_ID)) {
        echo '<span class="verified-badge bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full ml-2">' . esc_html__('Verified Purchase', 'aqualuxe') . '</span>';
    }
}
add_action('woocommerce_review_meta', 'aqualuxe_woocommerce_review_verification_badge', 20);

/**
 * Add product review helpful voting
 */
function aqualuxe_woocommerce_review_helpful() {
    $comment_id = get_comment_ID();
    $helpful_count = get_comment_meta($comment_id, 'helpful_count', true) ?: 0;
    
    echo '<div class="review-helpful mt-2 text-sm">';
    echo '<span class="helpful-question">' . esc_html__('Was this review helpful?', 'aqualuxe') . '</span>';
    echo '<button type="button" class="helpful-button ml-2 px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded transition-colors" data-comment-id="' . esc_attr($comment_id) . '">' . esc_html__('Yes', 'aqualuxe') . '</button>';
    echo '<span class="helpful-count ml-2">(' . esc_html($helpful_count) . ')</span>';
    echo '</div>';
}
add_action('woocommerce_review_after_comment_text', 'aqualuxe_woocommerce_review_helpful');

/**
 * AJAX Review Helpful
 */
function aqualuxe_woocommerce_review_helpful_ajax() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce-nonce')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
    }

    // Check comment ID
    if (!isset($_POST['comment_id']) || empty($_POST['comment_id'])) {
        wp_send_json_error(array('message' => __('Invalid comment.', 'aqualuxe')));
    }

    $comment_id = absint($_POST['comment_id']);
    
    // Get current helpful count
    $helpful_count = get_comment_meta($comment_id, 'helpful_count', true) ?: 0;
    
    // Increment helpful count
    $helpful_count++;
    
    // Update helpful count
    update_comment_meta($comment_id, 'helpful_count', $helpful_count);
    
    wp_send_json_success(array(
        'count' => $helpful_count,
        'message' => __('Thank you for your feedback!', 'aqualuxe'),
    ));
}
add_action('wp_ajax_aqualuxe_review_helpful', 'aqualuxe_woocommerce_review_helpful_ajax');
add_action('wp_ajax_nopriv_aqualuxe_review_helpful', 'aqualuxe_woocommerce_review_helpful_ajax');

/**
 * Add product review date
 */
function aqualuxe_woocommerce_review_date($comment) {
    $timestamp = strtotime($comment->comment_date);
    
    echo '<time class="review-date text-sm text-gray-600" datetime="' . esc_attr(date('c', $timestamp)) . '">';
    echo sprintf(esc_html__('Reviewed on %s', 'aqualuxe'), date_i18n(get_option('date_format'), $timestamp));
    echo '</time>';
}
add_action('woocommerce_review_meta', 'aqualuxe_woocommerce_review_date', 15);

/**
 * Add product review title
 */
function aqualuxe_woocommerce_review_title() {
    $comment = get_comment();
    $title = get_comment_meta($comment->comment_ID, 'review_title', true);
    
    if (!empty($title)) {
        echo '<h4 class="review-title font-bold mb-2">' . esc_html($title) . '</h4>';
    }
}
add_action('woocommerce_review_before_comment_text', 'aqualuxe_woocommerce_review_title');

/**
 * Add review title field to comment form
 */
function aqualuxe_woocommerce_review_title_field($fields) {
    $fields['title'] = '<div class="comment-form-title"><label for="review_title">' . esc_html__('Review Title', 'aqualuxe') . '</label><input id="review_title" name="review_title" type="text" value="" size="30" /></div>';
    
    return $fields;
}
add_filter('woocommerce_product_review_comment_form_args', function($comment_form) {
    $comment_form['fields'] = aqualuxe_woocommerce_review_title_field($comment_form['fields']);
    return $comment_form;
});

/**
 * Save review title
 */
function aqualuxe_woocommerce_save_review_title($comment_id) {
    if (isset($_POST['review_title'])) {
        update_comment_meta($comment_id, 'review_title', sanitize_text_field($_POST['review_title']));
    }
}
add_action('comment_post', 'aqualuxe_woocommerce_save_review_title');

/**
 * Add product review pros and cons
 */
function aqualuxe_woocommerce_review_pros_cons() {
    $comment = get_comment();
    $pros = get_comment_meta($comment->comment_ID, 'review_pros', true);
    $cons = get_comment_meta($comment->comment_ID, 'review_cons', true);
    
    if (!empty($pros) || !empty($cons)) {
        echo '<div class="review-pros-cons mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">';
        
        if (!empty($pros)) {
            echo '<div class="review-pros">';
            echo '<h5 class="text-sm font-bold text-green-600 mb-2">' . esc_html__('Pros', 'aqualuxe') . '</h5>';
            echo '<ul class="list-disc list-inside text-sm space-y-1">';
            
            $pros_list = explode("\n", $pros);
            foreach ($pros_list as $pro) {
                if (!empty(trim($pro))) {
                    echo '<li class="text-green-600">' . esc_html(trim($pro)) . '</li>';
                }
            }
            
            echo '</ul>';
            echo '</div>';
        }
        
        if (!empty($cons)) {
            echo '<div class="review-cons">';
            echo '<h5 class="text-sm font-bold text-red-600 mb-2">' . esc_html__('Cons', 'aqualuxe') . '</h5>';
            echo '<ul class="list-disc list-inside text-sm space-y-1">';
            
            $cons_list = explode("\n", $cons);
            foreach ($cons_list as $con) {
                if (!empty(trim($con))) {
                    echo '<li class="text-red-600">' . esc_html(trim($con)) . '</li>';
                }
            }
            
            echo '</ul>';
            echo '</div>';
        }
        
        echo '</div>';
    }
}
add_action('woocommerce_review_after_comment_text', 'aqualuxe_woocommerce_review_pros_cons', 5);

/**
 * Add review pros and cons fields to comment form
 */
function aqualuxe_woocommerce_review_pros_cons_fields($fields) {
    $fields['pros'] = '<div class="comment-form-pros"><label for="review_pros">' . esc_html__('Pros (one per line)', 'aqualuxe') . '</label><textarea id="review_pros" name="review_pros" rows="3"></textarea></div>';
    $fields['cons'] = '<div class="comment-form-cons"><label for="review_cons">' . esc_html__('Cons (one per line)', 'aqualuxe') . '</label><textarea id="review_cons" name="review_cons" rows="3"></textarea></div>';
    
    return $fields;
}
add_filter('woocommerce_product_review_comment_form_args', function($comment_form) {
    $comment_form['fields'] = aqualuxe_woocommerce_review_pros_cons_fields($comment_form['fields']);
    return $comment_form;
});

/**
 * Save review pros and cons
 */
function aqualuxe_woocommerce_save_review_pros_cons($comment_id) {
    if (isset($_POST['review_pros'])) {
        update_comment_meta($comment_id, 'review_pros', sanitize_textarea_field($_POST['review_pros']));
    }
    
    if (isset($_POST['review_cons'])) {
        update_comment_meta($comment_id, 'review_cons', sanitize_textarea_field($_POST['review_cons']));
    }
}
add_action('comment_post', 'aqualuxe_woocommerce_save_review_pros_cons');

/**
 * Add product review images
 */
function aqualuxe_woocommerce_review_images() {
    $comment = get_comment();
    $images = get_comment_meta($comment->comment_ID, 'review_images', true);
    
    if (!empty($images)) {
        echo '<div class="review-images mt-4">';
        echo '<h5 class="text-sm font-bold mb-2">' . esc_html__('Customer Images', 'aqualuxe') . '</h5>';
        echo '<div class="images-grid grid grid-cols-3 gap-2">';
        
        $images_array = explode(',', $images);
        foreach ($images_array as $image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
            $image_full_url = wp_get_attachment_image_url($image_id, 'full');
            
            if ($image_url) {
                echo '<a href="' . esc_url($image_full_url) . '" class="review-image-link" data-lightbox="review-' . esc_attr($comment->comment_ID) . '">';
                echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr__('Review Image', 'aqualuxe') . '" class="w-full h-auto rounded">';
                echo '</a>';
            }
        }
        
        echo '</div>';
        echo '</div>';
    }
}
add_action('woocommerce_review_after_comment_text', 'aqualuxe_woocommerce_review_images', 10);

/**
 * Add review images field to comment form
 */
function aqualuxe_woocommerce_review_images_field($fields) {
    $fields['images'] = '<div class="comment-form-images"><label for="review_images">' . esc_html__('Upload Images (optional)', 'aqualuxe') . '</label><input type="file" id="review_images" name="review_images[]" multiple accept="image/*" /></div>';
    
    return $fields;
}
add_filter('woocommerce_product_review_comment_form_args', function($comment_form) {
    $comment_form['fields'] = aqualuxe_woocommerce_review_images_field($comment_form['fields']);
    return $comment_form;
});

/**
 * Save review images
 */
function aqualuxe_woocommerce_save_review_images($comment_id) {
    if (isset($_FILES['review_images']) && !empty($_FILES['review_images']['name'][0])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        
        $image_ids = array();
        
        foreach ($_FILES['review_images']['name'] as $key => $value) {
            if ($_FILES['review_images']['name'][$key]) {
                $file = array(
                    'name'     => $_FILES['review_images']['name'][$key],
                    'type'     => $_FILES['review_images']['type'][$key],
                    'tmp_name' => $_FILES['review_images']['tmp_name'][$key],
                    'error'    => $_FILES['review_images']['error'][$key],
                    'size'     => $_FILES['review_images']['size'][$key]
                );
                
                $image_id = media_handle_sideload($file, 0);
                
                if (!is_wp_error($image_id)) {
                    $image_ids[] = $image_id;
                }
            }
        }
        
        if (!empty($image_ids)) {
            update_comment_meta($comment_id, 'review_images', implode(',', $image_ids));
        }
    }
}
add_action('comment_post', 'aqualuxe_woocommerce_save_review_images');

/**
 * Add product review form enctype
 */
function aqualuxe_woocommerce_review_form_enctype() {
    echo ' enctype="multipart/form-data"';
}
add_action('woocommerce_review_form_tag', 'aqualuxe_woocommerce_review_form_enctype');

/**
 * Add product review form submit button
 */
function aqualuxe_woocommerce_review_form_submit_button($submit_button) {
    return str_replace('submit', 'submit bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded transition-colors', $submit_button);
}
add_filter('comment_form_submit_button', 'aqualuxe_woocommerce_review_form_submit_button');

/**
 * Add product review form comment field
 */
function aqualuxe_woocommerce_review_form_comment_field($comment_field) {
    $comment_field = str_replace('comment-form-comment', 'comment-form-comment mb-4', $comment_field);
    $comment_field = str_replace('<textarea', '<textarea class="w-full p-2 border border-gray-300 rounded focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"', $comment_field);
    
    return $comment_field;
}
add_filter('comment_form_field_comment', 'aqualuxe_woocommerce_review_form_comment_field');

/**
 * Add product review form fields
 */
function aqualuxe_woocommerce_review_form_fields($fields) {
    foreach ($fields as $key => $field) {
        $fields[$key] = str_replace('input', 'input class="w-full p-2 border border-gray-300 rounded focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"', $field);
        $fields[$key] = str_replace('textarea', 'textarea class="w-full p-2 border border-gray-300 rounded focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"', $fields[$key]);
        $fields[$key] = str_replace('comment-form-', 'comment-form- mb-4 ', $fields[$key]);
    }
    
    return $fields;
}
add_filter('comment_form_default_fields', 'aqualuxe_woocommerce_review_form_fields');

/**
 * Add product review form rating field
 */
function aqualuxe_woocommerce_review_form_rating_field($comment_form) {
    $comment_form['comment_field'] = str_replace('p class="comment-form-comment"', 'p class="comment-form-comment mb-4"', $comment_form['comment_field']);
    
    return $comment_form;
}
add_filter('woocommerce_product_review_comment_form_args', 'aqualuxe_woocommerce_review_form_rating_field');

/**
 * Add product review pagination
 */
function aqualuxe_woocommerce_review_pagination() {
    $args = array(
        'prev_text' => '&larr; ' . esc_html__('Previous', 'aqualuxe'),
        'next_text' => esc_html__('Next', 'aqualuxe') . ' &rarr;',
        'type'      => 'list',
        'echo'      => false,
    );
    
    $pagination = paginate_comments_links($args);
    
    if ($pagination) {
        echo '<nav class="woocommerce-pagination">' . $pagination . '</nav>';
    }
}
add_action('woocommerce_review_after', 'aqualuxe_woocommerce_review_pagination');

/**
 * Add product review sorting
 */
function aqualuxe_woocommerce_review_sorting() {
    echo '<div class="review-sorting mb-6 flex justify-between items-center">';
    echo '<h3 class="text-lg font-bold">' . esc_html__('Customer Reviews', 'aqualuxe') . '</h3>';
    
    echo '<div class="sorting-options">';
    echo '<label for="review-sort" class="mr-2 text-sm">' . esc_html__('Sort by:', 'aqualuxe') . '</label>';
    echo '<select id="review-sort" class="review-sort p-1 border border-gray-300 rounded text-sm">';
    echo '<option value="newest">' . esc_html__('Newest', 'aqualuxe') . '</option>';
    echo '<option value="oldest">' . esc_html__('Oldest', 'aqualuxe') . '</option>';
    echo '<option value="highest">' . esc_html__('Highest Rating', 'aqualuxe') . '</option>';
    echo '<option value="lowest">' . esc_html__('Lowest Rating', 'aqualuxe') . '</option>';
    echo '<option value="helpful">' . esc_html__('Most Helpful', 'aqualuxe') . '</option>';
    echo '</select>';
    echo '</div>';
    
    echo '</div>';
}
add_action('woocommerce_review_before', 'aqualuxe_woocommerce_review_sorting');

/**
 * Add product review filter
 */
function aqualuxe_woocommerce_review_filter() {
    global $product;
    
    if ($product->get_review_count() > 0) {
        echo '<div class="review-filter mb-6">';
        echo '<div class="filter-options flex flex-wrap gap-2">';
        
        echo '<button type="button" class="filter-button all active px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-100 transition-colors" data-filter="all">' . esc_html__('All Reviews', 'aqualuxe') . '</button>';
        
        for ($i = 5; $i >= 1; $i--) {
            echo '<button type="button" class="filter-button px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-100 transition-colors" data-filter="' . esc_attr($i) . '">';
            echo sprintf(esc_html__('%d Stars', 'aqualuxe'), $i);
            echo '</button>';
        }
        
        echo '<button type="button" class="filter-button px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-100 transition-colors" data-filter="images">' . esc_html__('With Images', 'aqualuxe') . '</button>';
        echo '<button type="button" class="filter-button px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-100 transition-colors" data-filter="verified">' . esc_html__('Verified Purchases', 'aqualuxe') . '</button>';
        
        echo '</div>';
        echo '</div>';
    }
}
add_action('woocommerce_review_before', 'aqualuxe_woocommerce_review_filter', 5);

/**
 * Add product review search
 */
function aqualuxe_woocommerce_review_search() {
    global $product;
    
    if ($product->get_review_count() > 0) {
        echo '<div class="review-search mb-6">';
        echo '<form class="search-form flex">';
        echo '<input type="text" class="search-input flex-grow p-2 border border-gray-300 rounded-l focus:outline-none focus:border-primary" placeholder="' . esc_attr__('Search reviews...', 'aqualuxe') . '">';
        echo '<button type="submit" class="search-button bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-r transition-colors">' . esc_html__('Search', 'aqualuxe') . '</button>';
        echo '</form>';
        echo '</div>';
    }
}
add_action('woocommerce_review_before', 'aqualuxe_woocommerce_review_search', 10);

/**
 * Add product review count
 */
function aqualuxe_woocommerce_review_count() {
    global $product;
    
    if ($product->get_review_count() > 0) {
        echo '<div class="review-count mb-4 text-sm text-gray-600">';
        echo sprintf(_n('Showing %s review', 'Showing %s reviews', $product->get_review_count(), 'aqualuxe'), $product->get_review_count());
        echo '</div>';
    }
}
add_action('woocommerce_review_before_comments', 'aqualuxe_woocommerce_review_count');

/**
 * Add product review empty message
 */
function aqualuxe_woocommerce_review_empty_message() {
    global $product;
    
    if ($product->get_review_count() === 0) {
        echo '<div class="review-empty p-6 bg-gray-50 rounded text-center">';
        echo '<p class="mb-4">' . esc_html__('There are no reviews yet.', 'aqualuxe') . '</p>';
        echo '<a href="#review_form" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded transition-colors">' . esc_html__('Be the first to review this product', 'aqualuxe') . '</a>';
        echo '</div>';
    }
}
add_action('woocommerce_review_before_comments', 'aqualuxe_woocommerce_review_empty_message', 5);

/**
 * Add product review wrapper
 */
function aqualuxe_woocommerce_review_wrapper_start() {
    echo '<div class="reviews-wrapper">';
}
add_action('woocommerce_review_before', 'aqualuxe_woocommerce_review_wrapper_start', 1);

/**
 * Add product review wrapper end
 */
function aqualuxe_woocommerce_review_wrapper_end() {
    echo '</div>';
}
add_action('woocommerce_review_after', 'aqualuxe_woocommerce_review_wrapper_end', 99);

/**
 * Add product review form wrapper
 */
function aqualuxe_woocommerce_review_form_wrapper_start() {
    echo '<div class="review-form-wrapper mt-8 pt-8 border-t border-gray-200">';
}
add_action('woocommerce_review_form_before', 'aqualuxe_woocommerce_review_form_wrapper_start');

/**
 * Add product review form wrapper end
 */
function aqualuxe_woocommerce_review_form_wrapper_end() {
    echo '</div>';
}
add_action('woocommerce_review_form_after', 'aqualuxe_woocommerce_review_form_wrapper_end');

/**
 * Add product review item wrapper
 */
function aqualuxe_woocommerce_review_item_wrapper_start() {
    echo '<div class="review-item p-6 bg-white rounded-lg shadow-sm mb-6 border border-gray-100">';
}
add_action('woocommerce_review_before', 'aqualuxe_woocommerce_review_item_wrapper_start', 1);

/**
 * Add product review item wrapper end
 */
function aqualuxe_woocommerce_review_item_wrapper_end() {
    echo '</div>';
}
add_action('woocommerce_review_after', 'aqualuxe_woocommerce_review_item_wrapper_end', 99);

/**
 * Add product review meta wrapper
 */
function aqualuxe_woocommerce_review_meta_wrapper_start() {
    echo '<div class="review-meta mb-4">';
}
add_action('woocommerce_review_before_comment_meta', 'aqualuxe_woocommerce_review_meta_wrapper_start');

/**
 * Add product review meta wrapper end
 */
function aqualuxe_woocommerce_review_meta_wrapper_end() {
    echo '</div>';
}
add_action('woocommerce_review_meta', 'aqualuxe_woocommerce_review_meta_wrapper_end', 99);

/**
 * Add product review text wrapper
 */
function aqualuxe_woocommerce_review_text_wrapper_start() {
    echo '<div class="review-text">';
}
add_action('woocommerce_review_before_comment_text', 'aqualuxe_woocommerce_review_text_wrapper_start', 1);

/**
 * Add product review text wrapper end
 */
function aqualuxe_woocommerce_review_text_wrapper_end() {
    echo '</div>';
}
add_action('woocommerce_review_after_comment_text', 'aqualuxe_woocommerce_review_text_wrapper_end', 99);

/**
 * Add product review author avatar
 */
function aqualuxe_woocommerce_review_author_avatar() {
    $comment = get_comment();
    
    echo '<div class="review-avatar mr-4">';
    echo get_avatar($comment, 60, '', '', array('class' => 'rounded-full'));
    echo '</div>';
}
add_action('woocommerce_review_before_comment_meta', 'aqualuxe_woocommerce_review_author_avatar', 5);

/**
 * Add product review author info wrapper
 */
function aqualuxe_woocommerce_review_author_info_wrapper_start() {
    echo '<div class="review-author-info">';
}
add_action('woocommerce_review_before_comment_meta', 'aqualuxe_woocommerce_review_author_info_wrapper_start', 10);

/**
 * Add product review author info wrapper end
 */
function aqualuxe_woocommerce_review_author_info_wrapper_end() {
    echo '</div>';
}
add_action('woocommerce_review_meta', 'aqualuxe_woocommerce_review_author_info_wrapper_end', 90);

/**
 * Add product review meta header wrapper
 */
function aqualuxe_woocommerce_review_meta_header_wrapper_start() {
    echo '<div class="review-meta-header flex items-center">';
}
add_action('woocommerce_review_before_comment_meta', 'aqualuxe_woocommerce_review_meta_header_wrapper_start', 1);

/**
 * Add product review meta header wrapper end
 */
function aqualuxe_woocommerce_review_meta_header_wrapper_end() {
    echo '</div>';
}
add_action('woocommerce_review_meta', 'aqualuxe_woocommerce_review_meta_header_wrapper_end', 95);

/**
 * Add product review rating wrapper
 */
function aqualuxe_woocommerce_review_rating_wrapper_start() {
    echo '<div class="review-rating">';
}
add_action('woocommerce_review_before_comment_meta', 'aqualuxe_woocommerce_review_rating_wrapper_start', 15);

/**
 * Add product review rating wrapper end
 */
function aqualuxe_woocommerce_review_rating_wrapper_end() {
    echo '</div>';
}
add_action('woocommerce_review_meta', 'aqualuxe_woocommerce_review_rating_wrapper_end', 85);

/**
 * Add product review author name
 */
function aqualuxe_woocommerce_review_author_name() {
    $comment = get_comment();
    
    echo '<div class="review-author font-bold">' . esc_html(get_comment_author($comment)) . '</div>';
}
add_action('woocommerce_review_meta', 'aqualuxe_woocommerce_review_author_name', 10);