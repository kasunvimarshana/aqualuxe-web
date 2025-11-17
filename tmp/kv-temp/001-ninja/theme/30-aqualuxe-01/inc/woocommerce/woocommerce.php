<?php
/**
 * WooCommerce compatibility file
 *
 * @package AquaLuxe
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
    add_theme_support('woocommerce');
    
    // Add support for WooCommerce product gallery features
    if (get_theme_mod('aqualuxe_enable_product_gallery_zoom', true)) {
        add_theme_support('wc-product-gallery-zoom');
    }
    
    if (get_theme_mod('aqualuxe_enable_product_gallery_lightbox', true)) {
        add_theme_support('wc-product-gallery-lightbox');
    }
    
    if (get_theme_mod('aqualuxe_enable_product_gallery_slider', true)) {
        add_theme_support('wc-product-gallery-slider');
    }
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_setup');

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function aqualuxe_woocommerce_scripts() {
    // Enqueue WooCommerce styles
    wp_enqueue_style('aqualuxe-woocommerce-style', AQUALUXE_URI . '/assets/css/woocommerce.css', array('aqualuxe-style'), AQUALUXE_VERSION);
    
    // Add RTL support
    if (is_rtl()) {
        wp_enqueue_style('aqualuxe-woocommerce-rtl', AQUALUXE_URI . '/assets/css/woocommerce-rtl.css', array('aqualuxe-woocommerce-style'), AQUALUXE_VERSION);
    }
    
    // Enqueue WooCommerce scripts
    wp_enqueue_script('aqualuxe-woocommerce-script', AQUALUXE_URI . '/assets/js/woocommerce.js', array('jquery'), AQUALUXE_VERSION, true);
    
    // Localize WooCommerce script
    wp_localize_script('aqualuxe-woocommerce-script', 'aqualuxeWooCommerce', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe-woocommerce-nonce'),
        'enableQuickView' => get_theme_mod('aqualuxe_enable_quick_view', true),
        'enableWishlist' => get_theme_mod('aqualuxe_enable_wishlist', true),
        'enableAjaxAddToCart' => get_theme_mod('aqualuxe_enable_ajax_add_to_cart', true),
        'shopUrl' => wc_get_page_permalink('shop'),
        'i18n' => array(
            'addToCart' => esc_html__('Add to cart', 'aqualuxe'),
            'addingToCart' => esc_html__('Adding...', 'aqualuxe'),
            'addedToCart' => esc_html__('Added to cart', 'aqualuxe'),
            'viewCart' => esc_html__('View cart', 'aqualuxe'),
            'quickView' => esc_html__('Quick view', 'aqualuxe'),
            'addToWishlist' => esc_html__('Add to wishlist', 'aqualuxe'),
            'removeFromWishlist' => esc_html__('Remove from wishlist', 'aqualuxe'),
            'addedToWishlist' => esc_html__('Added to wishlist', 'aqualuxe'),
            'removedFromWishlist' => esc_html__('Removed from wishlist', 'aqualuxe'),
            'viewWishlist' => esc_html__('View wishlist', 'aqualuxe'),
            'wishlistEmpty' => esc_html__('Your wishlist is empty.', 'aqualuxe'),
            'browseProducts' => esc_html__('Browse products', 'aqualuxe'),
            'errorAddingToCart' => esc_html__('Error adding product to cart.', 'aqualuxe'),
            'errorLoadingProduct' => esc_html__('Error loading product.', 'aqualuxe'),
        ),
    ));
    
    // Enqueue wishlist script if enabled
    if (get_theme_mod('aqualuxe_enable_wishlist', true)) {
        wp_enqueue_script('aqualuxe-wishlist-script', AQUALUXE_URI . '/assets/js/wishlist.js', array('jquery', 'aqualuxe-woocommerce-script'), AQUALUXE_VERSION, true);
    }
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
 * @param array $classes CSS classes applied to the body tag.
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
        'posts_per_page' => get_theme_mod('aqualuxe_related_products_count', 4),
        'columns'        => get_theme_mod('aqualuxe_products_per_row', 4),
    );

    $args = wp_parse_args($defaults, $args);

    return $args;
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args');

/**
 * Upsells Products Args.
 *
 * @param array $args upsell products args.
 * @return array $args upsell products args.
 */
function aqualuxe_woocommerce_upsells_products_args($args) {
    $defaults = array(
        'posts_per_page' => get_theme_mod('aqualuxe_upsells_count', 4),
        'columns'        => get_theme_mod('aqualuxe_products_per_row', 4),
    );

    $args = wp_parse_args($defaults, $args);

    return $args;
}
add_filter('woocommerce_upsell_display_args', 'aqualuxe_woocommerce_upsells_products_args');

/**
 * Cross-sells Products Args.
 *
 * @param array $args cross-sell products args.
 * @return array $args cross-sell products args.
 */
function aqualuxe_woocommerce_cross_sells_products_args($args) {
    $defaults = array(
        'posts_per_page' => get_theme_mod('aqualuxe_cross_sells_count', 4),
        'columns'        => get_theme_mod('aqualuxe_products_per_row', 4),
    );

    $args = wp_parse_args($defaults, $args);

    return $args;
}
add_filter('woocommerce_cross_sells_columns', function() { return get_theme_mod('aqualuxe_products_per_row', 4); });
add_filter('woocommerce_cross_sells_total', function() { return get_theme_mod('aqualuxe_cross_sells_count', 4); });

/**
 * Product gallery thumbnail columns.
 *
 * @return integer number of columns.
 */
function aqualuxe_woocommerce_thumbnail_columns() {
    return 4;
}
add_filter('woocommerce_product_thumbnails_columns', 'aqualuxe_woocommerce_thumbnail_columns');

/**
 * Products per page.
 *
 * @return integer number of products.
 */
function aqualuxe_woocommerce_products_per_page() {
    return get_theme_mod('aqualuxe_products_per_page', 12);
}
add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page');

/**
 * Products per row.
 *
 * @return integer number of columns.
 */
function aqualuxe_woocommerce_loop_columns() {
    return get_theme_mod('aqualuxe_products_per_row', 3);
}
add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_columns');

/**
 * Remove default WooCommerce wrapper.
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

/**
 * Add custom WooCommerce wrapper.
 */
function aqualuxe_woocommerce_wrapper_before() {
    ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
    <?php
}
add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before');

/**
 * Add custom WooCommerce wrapper end.
 */
function aqualuxe_woocommerce_wrapper_after() {
    ?>
        </main><!-- #main -->
    </div><!-- #primary -->
    <?php
}
add_action('woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after');

/**
 * Add custom WooCommerce sidebar.
 */
function aqualuxe_woocommerce_sidebar() {
    // Get sidebar position
    $sidebar_position = '';
    
    if (is_product()) {
        $sidebar_position = get_theme_mod('aqualuxe_product_sidebar_position', 'none');
    } else {
        $sidebar_position = get_theme_mod('aqualuxe_shop_sidebar_position', 'right');
    }
    
    // Return if no sidebar
    if ($sidebar_position === 'none') {
        return;
    }
    
    // Get sidebar
    get_sidebar('shop');
}
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
add_action('woocommerce_sidebar', 'aqualuxe_woocommerce_sidebar');

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
 * <?php
 * if ( function_exists( 'aqualuxe_woocommerce_header_cart' ) ) {
 * aqualuxe_woocommerce_header_cart();
 * }
 * ?>
 */

/**
 * Cart Fragments.
 *
 * Ensure cart contents update when products are added to the cart via AJAX.
 *
 * @param array $fragments Fragments to refresh via AJAX.
 * @return array Fragments to refresh via AJAX.
 */
function aqualuxe_woocommerce_cart_link_fragment($fragments) {
    ob_start();
    aqualuxe_woocommerce_cart_link();
    $fragments['a.cart-contents'] = ob_get_clean();

    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_link_fragment');

/**
 * Cart Link.
 *
 * Displayed a link to the cart including the number of items present and the cart total.
 *
 * @return void
 */
function aqualuxe_woocommerce_cart_link() {
    ?>
    <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'aqualuxe'); ?>">
        <span class="cart-contents-count"><?php echo wp_kses_data(WC()->cart->get_cart_contents_count()); ?></span>
        <span class="cart-contents-total"><?php echo wp_kses_data(WC()->cart->get_cart_total()); ?></span>
    </a>
    <?php
}

/**
 * Header cart.
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
        <div class="widget_shopping_cart">
            <div class="widget_shopping_cart_content">
                <?php woocommerce_mini_cart(); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Header wishlist.
 *
 * @return void
 */
function aqualuxe_woocommerce_header_wishlist() {
    // Check if wishlist is enabled
    if (!get_theme_mod('aqualuxe_enable_wishlist', true)) {
        return;
    }
    
    // Get wishlist count
    $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? json_decode(stripslashes($_COOKIE['aqualuxe_wishlist']), true) : array();
    $count = count($wishlist);
    
    // Get wishlist URL
    $wishlist_url = wc_get_account_endpoint_url('wishlist');
    
    // Check if current page is wishlist
    $is_wishlist = is_account_page() && is_wc_endpoint_url('wishlist');
    $class = $is_wishlist ? 'current-menu-item' : '';
    ?>
    <div class="site-header-wishlist">
        <div class="<?php echo esc_attr($class); ?>">
            <a href="<?php echo esc_url($wishlist_url); ?>" class="aqualuxe-wishlist-button" title="<?php esc_attr_e('View your wishlist', 'aqualuxe'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                <span class="wishlist-count <?php echo $count > 0 ? '' : 'hidden'; ?>"><?php echo esc_html($count); ?></span>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Add quick view button to product loop
 */
function aqualuxe_woocommerce_quick_view_button() {
    // Check if quick view is enabled
    if (!get_theme_mod('aqualuxe_enable_quick_view', true)) {
        return;
    }
    
    global $product;
    
    echo '<div class="aqualuxe-quick-view-button">';
    echo '<button class="button quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</button>';
    echo '</div>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_quick_view_button', 15);

/**
 * Add wishlist button to product loop
 */
function aqualuxe_woocommerce_wishlist_button() {
    // Check if wishlist is enabled
    if (!get_theme_mod('aqualuxe_enable_wishlist', true)) {
        return;
    }
    
    global $product;
    
    // Get wishlist
    $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? json_decode(stripslashes($_COOKIE['aqualuxe_wishlist']), true) : array();
    
    // Check if product is in wishlist
    $in_wishlist = in_array($product->get_id(), $wishlist);
    
    echo '<div class="aqualuxe-wishlist-button">';
    echo '<button class="button wishlist-button ' . ($in_wishlist ? 'in-wishlist' : '') . '" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<span class="wishlist-icon">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="' . ($in_wishlist ? 'currentColor' : 'none') . '" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>';
    echo '</span>';
    echo '<span class="wishlist-text">' . ($in_wishlist ? esc_html__('Remove from wishlist', 'aqualuxe') : esc_html__('Add to wishlist', 'aqualuxe')) . '</span>';
    echo '</button>';
    echo '</div>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_wishlist_button', 20);

/**
 * AJAX add to wishlist
 */
function aqualuxe_ajax_add_to_wishlist() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce-nonce')) {
        wp_send_json_error('Invalid nonce');
    }
    
    // Get product ID
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    
    if (!$product_id) {
        wp_send_json_error('Invalid product ID');
    }
    
    // Get wishlist
    $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? json_decode(stripslashes($_COOKIE['aqualuxe_wishlist']), true) : array();
    
    // Check if product is in wishlist
    $in_wishlist = in_array($product_id, $wishlist);
    
    // Add or remove product from wishlist
    if ($in_wishlist) {
        $wishlist = array_diff($wishlist, array($product_id));
        $message = __('Product removed from wishlist', 'aqualuxe');
        $status = 'removed';
    } else {
        $wishlist[] = $product_id;
        $message = __('Product added to wishlist', 'aqualuxe');
        $status = 'added';
    }
    
    // Set cookie
    setcookie('aqualuxe_wishlist', json_encode($wishlist), time() + (86400 * 30), '/');
    
    // Send response
    wp_send_json_success(array(
        'message' => $message,
        'status' => $status,
        'wishlist' => $wishlist,
    ));
}
add_action('wp_ajax_aqualuxe_add_to_wishlist', 'aqualuxe_ajax_add_to_wishlist');
add_action('wp_ajax_nopriv_aqualuxe_add_to_wishlist', 'aqualuxe_ajax_add_to_wishlist');

/**
 * AJAX get quick view
 */
function aqualuxe_ajax_get_quick_view() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce-nonce')) {
        wp_send_json_error('Invalid nonce');
    }
    
    // Get product ID
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    
    if (!$product_id) {
        wp_send_json_error('Invalid product ID');
    }
    
    // Get product
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error('Product not found');
    }
    
    // Get quick view template
    ob_start();
    include(AQUALUXE_DIR . '/woocommerce/quick-view.php');
    $html = ob_get_clean();
    
    // Send response
    wp_send_json_success(array(
        'html' => $html,
    ));
}
add_action('wp_ajax_aqualuxe_get_quick_view', 'aqualuxe_ajax_get_quick_view');
add_action('wp_ajax_nopriv_aqualuxe_get_quick_view', 'aqualuxe_ajax_get_quick_view');

/**
 * Add quick view modal to footer
 */
function aqualuxe_quick_view_modal() {
    // Check if quick view is enabled
    if (!get_theme_mod('aqualuxe_enable_quick_view', true)) {
        return;
    }
    
    ?>
    <div id="aqualuxe-quick-view-modal" class="aqualuxe-quick-view-modal">
        <div class="aqualuxe-quick-view-modal-content">
            <span class="aqualuxe-quick-view-close">&times;</span>
            <div class="aqualuxe-quick-view-content"></div>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'aqualuxe_quick_view_modal');

/**
 * Add wishlist page
 */
function aqualuxe_wishlist_page() {
    // Check if wishlist is enabled
    if (!get_theme_mod('aqualuxe_enable_wishlist', true)) {
        return;
    }
    
    // Register wishlist endpoint
    add_rewrite_endpoint('wishlist', EP_ROOT | EP_PAGES);
    
    // Add wishlist query var
    function aqualuxe_wishlist_query_vars($vars) {
        $vars[] = 'wishlist';
        return $vars;
    }
    add_filter('query_vars', 'aqualuxe_wishlist_query_vars', 0);
    
    // Add wishlist to my account menu
    function aqualuxe_wishlist_menu_items($items) {
        $items['wishlist'] = __('Wishlist', 'aqualuxe');
        return $items;
    }
    add_filter('woocommerce_account_menu_items', 'aqualuxe_wishlist_menu_items');
    
    // Add wishlist endpoint content
    function aqualuxe_wishlist_endpoint_content() {
        // Get wishlist
        $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? json_decode(stripslashes($_COOKIE['aqualuxe_wishlist']), true) : array();
        
        // Get products
        $products = array();
        
        if (!empty($wishlist)) {
            $args = array(
                'post_type' => 'product',
                'post__in' => $wishlist,
                'posts_per_page' => -1,
            );
            
            $products = wc_get_products($args);
        }
        
        // Display wishlist
        wc_get_template(
            'wishlist.php',
            array(
                'wishlist' => $wishlist,
                'products' => $products,
            ),
            '',
            AQUALUXE_DIR . '/woocommerce/'
        );
    }
    add_action('woocommerce_account_wishlist_endpoint', 'aqualuxe_wishlist_endpoint_content');
    
    // Flush rewrite rules
    function aqualuxe_wishlist_flush_rewrite_rules() {
        add_rewrite_endpoint('wishlist', EP_ROOT | EP_PAGES);
        flush_rewrite_rules();
    }
    add_action('after_switch_theme', 'aqualuxe_wishlist_flush_rewrite_rules');
}
add_action('init', 'aqualuxe_wishlist_page');

/**
 * Add multi-currency support
 */
function aqualuxe_multi_currency_support() {
    // Check if multi-currency is enabled
    if (!get_theme_mod('aqualuxe_enable_multi_currency', false)) {
        return;
    }
    
    // Add currency switcher to header
    function aqualuxe_currency_switcher() {
        // Get currencies
        $currencies = get_option('aqualuxe_currencies', array(
            'USD' => array(
                'symbol' => '$',
                'rate' => 1,
            ),
            'EUR' => array(
                'symbol' => '€',
                'rate' => 0.85,
            ),
            'GBP' => array(
                'symbol' => '£',
                'rate' => 0.75,
            ),
        ));
        
        // Get current currency
        $current_currency = isset($_COOKIE['aqualuxe_currency']) ? sanitize_text_field($_COOKIE['aqualuxe_currency']) : 'USD';
        
        // Display currency switcher
        ?>
        <div class="aqualuxe-currency-switcher">
            <select id="aqualuxe-currency-select">
                <?php foreach ($currencies as $code => $currency) : ?>
                    <option value="<?php echo esc_attr($code); ?>" <?php selected($current_currency, $code); ?>>
                        <?php echo esc_html($currency['symbol'] . ' ' . $code); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }
    add_action('aqualuxe_header', 'aqualuxe_currency_switcher', 40);
    
    // Add currency switcher script
    function aqualuxe_currency_switcher_script() {
        // Check if multi-currency is enabled
        if (!get_theme_mod('aqualuxe_enable_multi_currency', false)) {
            return;
        }
        
        // Get currencies
        $currencies = get_option('aqualuxe_currencies', array(
            'USD' => array(
                'symbol' => '$',
                'rate' => 1,
            ),
            'EUR' => array(
                'symbol' => '€',
                'rate' => 0.85,
            ),
            'GBP' => array(
                'symbol' => '£',
                'rate' => 0.75,
            ),
        ));
        
        // Enqueue script
        wp_enqueue_script('aqualuxe-currency-switcher', AQUALUXE_URI . '/assets/js/currency-switcher.js', array('jquery'), AQUALUXE_VERSION, true);
        
        // Localize script
        wp_localize_script('aqualuxe-currency-switcher', 'aqualuxeCurrency', array(
            'currencies' => $currencies,
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-currency-switcher-nonce'),
        ));
    }
    add_action('wp_enqueue_scripts', 'aqualuxe_currency_switcher_script');
    
    // AJAX set currency
    function aqualuxe_ajax_set_currency() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-currency-switcher-nonce')) {
            wp_send_json_error('Invalid nonce');
        }
        
        // Get currency
        $currency = isset($_POST['currency']) ? sanitize_text_field($_POST['currency']) : 'USD';
        
        // Set cookie
        setcookie('aqualuxe_currency', $currency, time() + (86400 * 30), '/');
        
        // Send response
        wp_send_json_success(array(
            'currency' => $currency,
        ));
    }
    add_action('wp_ajax_aqualuxe_set_currency', 'aqualuxe_ajax_set_currency');
    add_action('wp_ajax_nopriv_aqualuxe_set_currency', 'aqualuxe_ajax_set_currency');
    
    // Filter product prices
    function aqualuxe_filter_product_price($price, $product) {
        // Get current currency
        $current_currency = isset($_COOKIE['aqualuxe_currency']) ? sanitize_text_field($_COOKIE['aqualuxe_currency']) : 'USD';
        
        // Get currencies
        $currencies = get_option('aqualuxe_currencies', array(
            'USD' => array(
                'symbol' => '$',
                'rate' => 1,
            ),
            'EUR' => array(
                'symbol' => '€',
                'rate' => 0.85,
            ),
            'GBP' => array(
                'symbol' => '£',
                'rate' => 0.75,
            ),
        ));
        
        // Check if currency exists
        if (!isset($currencies[$current_currency])) {
            return $price;
        }
        
        // Get currency rate
        $rate = $currencies[$current_currency]['rate'];
        
        // Get currency symbol
        $symbol = $currencies[$current_currency]['symbol'];
        
        // Get price
        $price_value = $product->get_price();
        
        // Convert price
        $converted_price = $price_value * $rate;
        
        // Format price
        $formatted_price = wc_price($converted_price, array(
            'currency' => $current_currency,
        ));
        
        return $formatted_price;
    }
    add_filter('woocommerce_get_price_html', 'aqualuxe_filter_product_price', 10, 2);
}
add_action('init', 'aqualuxe_multi_currency_support');

/**
 * Add international shipping support
 */
function aqualuxe_international_shipping_support() {
    // Check if international shipping is enabled
    if (!get_theme_mod('aqualuxe_enable_international_shipping', false)) {
        return;
    }
    
    // Add country selector to checkout
    function aqualuxe_country_selector() {
        // Get countries
        $countries = WC()->countries->get_countries();
        
        // Get current country
        $current_country = isset($_COOKIE['aqualuxe_country']) ? sanitize_text_field($_COOKIE['aqualuxe_country']) : WC()->countries->get_base_country();
        
        // Display country selector
        ?>
        <div class="aqualuxe-country-selector">
            <select id="aqualuxe-country-select">
                <?php foreach ($countries as $code => $country) : ?>
                    <option value="<?php echo esc_attr($code); ?>" <?php selected($current_country, $code); ?>>
                        <?php echo esc_html($country); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }
    add_action('aqualuxe_header', 'aqualuxe_country_selector', 50);
    
    // Add country selector script
    function aqualuxe_country_selector_script() {
        // Check if international shipping is enabled
        if (!get_theme_mod('aqualuxe_enable_international_shipping', false)) {
            return;
        }
        
        // Enqueue script
        wp_enqueue_script('aqualuxe-country-selector', AQUALUXE_URI . '/assets/js/country-selector.js', array('jquery'), AQUALUXE_VERSION, true);
        
        // Localize script
        wp_localize_script('aqualuxe-country-selector', 'aqualuxeCountry', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-country-selector-nonce'),
        ));
    }
    add_action('wp_enqueue_scripts', 'aqualuxe_country_selector_script');
    
    // AJAX set country
    function aqualuxe_ajax_set_country() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-country-selector-nonce')) {
            wp_send_json_error('Invalid nonce');
        }
        
        // Get country
        $country = isset($_POST['country']) ? sanitize_text_field($_POST['country']) : WC()->countries->get_base_country();
        
        // Set cookie
        setcookie('aqualuxe_country', $country, time() + (86400 * 30), '/');
        
        // Send response
        wp_send_json_success(array(
            'country' => $country,
        ));
    }
    add_action('wp_ajax_aqualuxe_set_country', 'aqualuxe_ajax_set_country');
    add_action('wp_ajax_nopriv_aqualuxe_set_country', 'aqualuxe_ajax_set_country');
    
    // Set default country
    function aqualuxe_set_default_country($country) {
        // Get current country
        $current_country = isset($_COOKIE['aqualuxe_country']) ? sanitize_text_field($_COOKIE['aqualuxe_country']) : $country;
        
        return $current_country;
    }
    add_filter('woocommerce_customer_get_shipping_country', 'aqualuxe_set_default_country', 10, 1);
    add_filter('woocommerce_customer_get_billing_country', 'aqualuxe_set_default_country', 10, 1);
    
    // Add international shipping rates
    function aqualuxe_international_shipping_rates($rates, $package) {
        // Get current country
        $current_country = isset($_COOKIE['aqualuxe_country']) ? sanitize_text_field($_COOKIE['aqualuxe_country']) : WC()->countries->get_base_country();
        
        // Get base country
        $base_country = WC()->countries->get_base_country();
        
        // Check if international shipping
        if ($current_country !== $base_country) {
            // Add international shipping rate
            $rates['international_shipping'] = array(
                'id' => 'international_shipping',
                'label' => __('International Shipping', 'aqualuxe'),
                'cost' => 20,
                'taxes' => false,
                'calc_tax' => 'per_order',
            );
        }
        
        return $rates;
    }
    add_filter('woocommerce_package_rates', 'aqualuxe_international_shipping_rates', 10, 2);
}
add_action('init', 'aqualuxe_international_shipping_support');

/**
 * Add optimized checkout flow
 */
function aqualuxe_optimized_checkout_flow() {
    // Check if optimized checkout is enabled
    if (!get_theme_mod('aqualuxe_enable_optimized_checkout', false)) {
        return;
    }
    
    // Add checkout steps
    function aqualuxe_checkout_steps() {
        // Get current step
        $step = isset($_GET['step']) ? absint($_GET['step']) : 1;
        
        // Display checkout steps
        ?>
        <div class="aqualuxe-checkout-steps">
            <div class="aqualuxe-checkout-step <?php echo $step === 1 ? 'active' : ''; ?>" data-step="1">
                <span class="step-number">1</span>
                <span class="step-title"><?php esc_html_e('Cart', 'aqualuxe'); ?></span>
            </div>
            <div class="aqualuxe-checkout-step <?php echo $step === 2 ? 'active' : ''; ?>" data-step="2">
                <span class="step-number">2</span>
                <span class="step-title"><?php esc_html_e('Shipping', 'aqualuxe'); ?></span>
            </div>
            <div class="aqualuxe-checkout-step <?php echo $step === 3 ? 'active' : ''; ?>" data-step="3">
                <span class="step-number">3</span>
                <span class="step-title"><?php esc_html_e('Payment', 'aqualuxe'); ?></span>
            </div>
            <div class="aqualuxe-checkout-step <?php echo $step === 4 ? 'active' : ''; ?>" data-step="4">
                <span class="step-number">4</span>
                <span class="step-title"><?php esc_html_e('Confirmation', 'aqualuxe'); ?></span>
            </div>
        </div>
        <?php
    }
    add_action('woocommerce_before_checkout_form', 'aqualuxe_checkout_steps', 10);
    
    // Add checkout steps script
    function aqualuxe_checkout_steps_script() {
        // Check if optimized checkout is enabled
        if (!get_theme_mod('aqualuxe_enable_optimized_checkout', false)) {
            return;
        }
        
        // Check if checkout page
        if (!is_checkout()) {
            return;
        }
        
        // Enqueue script
        wp_enqueue_script('aqualuxe-checkout-steps', AQUALUXE_URI . '/assets/js/checkout-steps.js', array('jquery'), AQUALUXE_VERSION, true);
        
        // Localize script
        wp_localize_script('aqualuxe-checkout-steps', 'aqualuxeCheckout', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-checkout-steps-nonce'),
        ));
    }
    add_action('wp_enqueue_scripts', 'aqualuxe_checkout_steps_script');
    
    // AJAX update checkout step
    function aqualuxe_ajax_update_checkout_step() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-checkout-steps-nonce')) {
            wp_send_json_error('Invalid nonce');
        }
        
        // Get step
        $step = isset($_POST['step']) ? absint($_POST['step']) : 1;
        
        // Send response
        wp_send_json_success(array(
            'step' => $step,
        ));
    }
    add_action('wp_ajax_aqualuxe_update_checkout_step', 'aqualuxe_ajax_update_checkout_step');
    add_action('wp_ajax_nopriv_aqualuxe_update_checkout_step', 'aqualuxe_ajax_update_checkout_step');
}
add_action('init', 'aqualuxe_optimized_checkout_flow');

/**
 * Add advanced product filtering
 */
function aqualuxe_advanced_product_filtering() {
    // Check if advanced filtering is enabled
    if (!get_theme_mod('aqualuxe_enable_advanced_filtering', false)) {
        return;
    }
    
    // Add filter widget area
    function aqualuxe_filter_widget_area() {
        register_sidebar(array(
            'name'          => __('Product Filters', 'aqualuxe'),
            'id'            => 'product-filters',
            'description'   => __('Add widgets here to appear in the product filters area.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ));
    }
    add_action('widgets_init', 'aqualuxe_filter_widget_area');
    
    // Add filter button and active filters
    function aqualuxe_filter_button() {
        // Check if shop page
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }
        
        // Display filter button
        ?>
        <div class="aqualuxe-filter-controls">
            <button class="aqualuxe-filter-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                <?php esc_html_e('Filter', 'aqualuxe'); ?>
            </button>
            
            <div class="aqualuxe-active-filters" style="display: none;"></div>
        </div>
        <?php
    }
    add_action('woocommerce_before_shop_loop', 'aqualuxe_filter_button', 20);
    
    // Add filter sidebar
    function aqualuxe_filter_sidebar() {
        // Check if shop page
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }
        
        // Display filter sidebar
        ?>
        <div class="aqualuxe-filter-sidebar">
            <div class="aqualuxe-filter-sidebar-header">
                <h3><?php esc_html_e('Filter Products', 'aqualuxe'); ?></h3>
                <button class="aqualuxe-filter-close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="aqualuxe-filter-sidebar-content">
                <form id="aqualuxe-filter-form">
                    <?php
                    // Categories filter
                    $product_categories = get_terms(array(
                        'taxonomy' => 'product_cat',
                        'hide_empty' => true,
                    ));
                    
                    if (!empty($product_categories) && !is_wp_error($product_categories)) {
                        ?>
                        <div class="aqualuxe-filter-group">
                            <h4 class="aqualuxe-filter-group-title"><?php esc_html_e('Categories', 'aqualuxe'); ?></h4>
                            <div class="aqualuxe-filter-options">
                                <?php foreach ($product_categories as $category) { ?>
                                    <div class="aqualuxe-filter-checkbox">
                                        <input type="checkbox" id="category-<?php echo esc_attr($category->slug); ?>" name="category[]" value="<?php echo esc_attr($category->slug); ?>">
                                        <label for="category-<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?> (<?php echo esc_html($category->count); ?>)</label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                    }
                    
                    // Price filter
                    $min_price = floor(wc_get_price_to_display(wc_get_product(wc_get_min_price_product_id())));
                    $max_price = ceil(wc_get_price_to_display(wc_get_product(wc_get_max_price_product_id())));
                    ?>
                    <div class="aqualuxe-filter-group">
                        <h4 class="aqualuxe-filter-group-title"><?php esc_html_e('Price', 'aqualuxe'); ?></h4>
                        <div class="aqualuxe-price-slider">
                            <div id="price-slider" class="aqualuxe-range-slider" data-min="<?php echo esc_attr($min_price); ?>" data-max="<?php echo esc_attr($max_price); ?>"></div>
                            <div class="aqualuxe-price-inputs">
                                <input type="number" id="price-min" name="price_min" min="<?php echo esc_attr($min_price); ?>" max="<?php echo esc_attr($max_price); ?>" value="<?php echo esc_attr($min_price); ?>" class="aqualuxe-price-input">
                                <span class="aqualuxe-price-separator">-</span>
                                <input type="number" id="price-max" name="price_max" min="<?php echo esc_attr($min_price); ?>" max="<?php echo esc_attr($max_price); ?>" value="<?php echo esc_attr($max_price); ?>" class="aqualuxe-price-input">
                            </div>
                        </div>
                    </div>
                    
                    <?php
                    // Rating filter
                    ?>
                    <div class="aqualuxe-filter-group">
                        <h4 class="aqualuxe-filter-group-title"><?php esc_html_e('Rating', 'aqualuxe'); ?></h4>
                        <div class="aqualuxe-filter-options">
                            <?php for ($i = 5; $i >= 1; $i--) { ?>
                                <div class="aqualuxe-filter-radio aqualuxe-star-rating">
                                    <input type="radio" id="rating-<?php echo esc_attr($i); ?>" name="rating" value="<?php echo esc_attr($i); ?>">
                                    <label for="rating-<?php echo esc_attr($i); ?>">
                                        <span class="stars">
                                            <?php for ($j = 1; $j <= 5; $j++) { ?>
                                                <span class="star">
                                                    <?php if ($j <= $i) { ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                                    <?php } else { ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                                    <?php } ?>
                                                </span>
                                            <?php } ?>
                                        </span>
                                        <span class="count"><?php echo esc_html(sprintf(__('%d & up', 'aqualuxe'), $i)); ?></span>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <?php
                    // Product attributes
                    $attribute_taxonomies = wc_get_attribute_taxonomies();
                    
                    if (!empty($attribute_taxonomies)) {
                        foreach ($attribute_taxonomies as $attribute) {
                            $taxonomy = 'pa_' . $attribute->attribute_name;
                            $terms = get_terms(array(
                                'taxonomy' => $taxonomy,
                                'hide_empty' => true,
                            ));
                            
                            if (!empty($terms) && !is_wp_error($terms)) {
                                ?>
                                <div class="aqualuxe-filter-group">
                                    <h4 class="aqualuxe-filter-group-title"><?php echo esc_html($attribute->attribute_label); ?></h4>
                                    <div class="aqualuxe-filter-options">
                                        <?php foreach ($terms as $term) { ?>
                                            <div class="aqualuxe-filter-checkbox">
                                                <input type="checkbox" id="<?php echo esc_attr($taxonomy . '-' . $term->slug); ?>" name="<?php echo esc_attr($taxonomy); ?>[]" value="<?php echo esc_attr($term->slug); ?>">
                                                <label for="<?php echo esc_attr($taxonomy . '-' . $term->slug); ?>"><?php echo esc_html($term->name); ?> (<?php echo esc_html($term->count); ?>)</label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    }
                    
                    // Tags filter
                    $product_tags = get_terms(array(
                        'taxonomy' => 'product_tag',
                        'hide_empty' => true,
                    ));
                    
                    if (!empty($product_tags) && !is_wp_error($product_tags)) {
                        ?>
                        <div class="aqualuxe-filter-group">
                            <h4 class="aqualuxe-filter-group-title"><?php esc_html_e('Tags', 'aqualuxe'); ?></h4>
                            <div class="aqualuxe-filter-options">
                                <?php foreach ($product_tags as $tag) { ?>
                                    <div class="aqualuxe-filter-checkbox">
                                        <input type="checkbox" id="tag-<?php echo esc_attr($tag->slug); ?>" name="tag[]" value="<?php echo esc_attr($tag->slug); ?>">
                                        <label for="tag-<?php echo esc_attr($tag->slug); ?>"><?php echo esc_html($tag->name); ?> (<?php echo esc_html($tag->count); ?>)</label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                    }
                    
                    // Allow other plugins to add filters
                    do_action('aqualuxe_product_filters');
                    ?>
                </form>
                
                <?php dynamic_sidebar('product-filters'); ?>
            </div>
            <div class="aqualuxe-filter-sidebar-footer">
                <button class="aqualuxe-filter-apply button"><?php esc_html_e('Apply Filters', 'aqualuxe'); ?></button>
                <button class="aqualuxe-filter-reset"><?php esc_html_e('Reset Filters', 'aqualuxe'); ?></button>
            </div>
        </div>
        <div class="aqualuxe-filter-overlay"></div>
        <?php
    }
    add_action('wp_footer', 'aqualuxe_filter_sidebar');
    
    // Add filter script
    function aqualuxe_filter_script() {
        // Check if advanced filtering is enabled
        if (!get_theme_mod('aqualuxe_enable_advanced_filtering', false)) {
            return;
        }
        
        // Check if shop page
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }
        
        // Enqueue jQuery UI
        wp_enqueue_script('jquery-ui-slider');
        
        // Enqueue filter script
        wp_enqueue_script('aqualuxe-filter', AQUALUXE_URI . '/assets/js/filter.js', array('jquery', 'jquery-ui-slider'), AQUALUXE_VERSION, true);
        
        // Localize script
        wp_localize_script('aqualuxe-filter', 'aqualuxeFilter', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-filter-nonce'),
            'ajax_enabled' => get_theme_mod('aqualuxe_enable_advanced_filtering', false) ? '1' : '0',
            'currency' => get_woocommerce_currency_symbol(),
            'i18n' => array(
                'no_products' => __('No products found matching your selection.', 'aqualuxe'),
                'showing' => __('Showing', 'aqualuxe'),
                'of' => __('of', 'aqualuxe'),
                'results' => __('results', 'aqualuxe'),
                'price' => __('Price', 'aqualuxe'),
                'rating' => __('Rating', 'aqualuxe'),
                'apply' => __('Apply Filters', 'aqualuxe'),
                'reset' => __('Reset Filters', 'aqualuxe'),
                'filter' => __('Filter', 'aqualuxe'),
                'close' => __('Close', 'aqualuxe'),
            ),
        ));
    }
    add_action('wp_enqueue_scripts', 'aqualuxe_filter_script');
    
    // AJAX filter products
    function aqualuxe_ajax_filter_products() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-filter-nonce')) {
            wp_send_json_error('Invalid nonce');
        }
        
        // Get filter data
        $filters = isset($_POST['filters']) ? $_POST['filters'] : array();
        
        // Build query args
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => get_theme_mod('aqualuxe_products_per_page', 12),
            'paged' => isset($filters['page']) ? absint($filters['page']) : 1,
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'exclude-from-catalog',
                    'operator' => 'NOT IN',
                ),
            ),
            'meta_query' => array(
                'relation' => 'AND',
            ),
        );
        
        // Add category filter
        if (isset($filters['category']) && !empty($filters['category'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $filters['category'],
                'operator' => 'IN',
            );
        }
        
        // Add tag filter
        if (isset($filters['tag']) && !empty($filters['tag'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_tag',
                'field' => 'slug',
                'terms' => $filters['tag'],
                'operator' => 'IN',
            );
        }
        
        // Add attribute filters
        foreach ($filters as $key => $value) {
            if (strpos($key, 'pa_') === 0 && !empty($value)) {
                $args['tax_query'][] = array(
                    'taxonomy' => $key,
                    'field' => 'slug',
                    'terms' => $value,
                    'operator' => 'IN',
                );
            }
        }
        
        // Add price filter
        if (isset($filters['price']) && is_array($filters['price']) && 
            isset($filters['price']['min']) && isset($filters['price']['max'])) {
            $args['meta_query'][] = array(
                'key' => '_price',
                'value' => array(floatval($filters['price']['min']), floatval($filters['price']['max'])),
                'compare' => 'BETWEEN',
                'type' => 'NUMERIC',
            );
        }
        
        // Add rating filter
        if (isset($filters['rating']) && !empty($filters['rating'])) {
            $args['meta_query'][] = array(
                'key' => '_wc_average_rating',
                'value' => floatval($filters['rating']),
                'compare' => '>=',
                'type' => 'NUMERIC',
            );
        }
        
        // Add orderby
        if (isset($filters['orderby']) && !empty($filters['orderby'])) {
            switch ($filters['orderby']) {
                case 'price':
                    $args['meta_key'] = '_price';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'ASC';
                    break;
                case 'price-desc':
                    $args['meta_key'] = '_price';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                    break;
                case 'rating':
                    $args['meta_key'] = '_wc_average_rating';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                    break;
                case 'popularity':
                    $args['meta_key'] = 'total_sales';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                    break;
                case 'date':
                    $args['orderby'] = 'date';
                    $args['order'] = 'DESC';
                    break;
                case 'title':
                    $args['orderby'] = 'title';
                    $args['order'] = 'ASC';
                    break;
                default:
                    $args['orderby'] = 'menu_order title';
                    $args['order'] = 'ASC';
                    break;
            }
        }
        
        // Apply WooCommerce product visibility
        $product_visibility_terms  = wc_get_product_visibility_term_ids();
        
        // Hide out of stock products
        if ('yes' === get_option('woocommerce_hide_out_of_stock_items')) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'term_taxonomy_id',
                'terms'    => $product_visibility_terms['outofstock'],
                'operator' => 'NOT IN',
            );
        }
        
        // Get products
        $products_query = new WP_Query($args);
        
        // Get product HTML
        ob_start();
        
        if ($products_query->have_posts()) {
            woocommerce_product_loop_start();
            
            while ($products_query->have_posts()) {
                $products_query->the_post();
                wc_get_template_part('content', 'product');
            }
            
            woocommerce_product_loop_end();
        } else {
            echo '<p class="woocommerce-info">' . esc_html__('No products found matching your selection.', 'aqualuxe') . '</p>';
        }
        
        $products_html = ob_get_clean();
        
        // Get pagination HTML
        ob_start();
        
        woocommerce_pagination();
        
        $pagination_html = ob_get_clean();
        
        // Get count HTML
        ob_start();
        
        woocommerce_result_count();
        
        $count_html = ob_get_clean();
        
        // Reset post data
        wp_reset_postdata();
        
        // Send response
        wp_send_json_success(array(
            'products' => $products_html,
            'pagination' => $pagination_html,
            'count' => $count_html,
            'found_posts' => $products_query->found_posts,
            'max_num_pages' => $products_query->max_num_pages,
            'current_page' => $args['paged'],
        ));
    }
    add_action('wp_ajax_aqualuxe_filter_products', 'aqualuxe_ajax_filter_products');
    add_action('wp_ajax_nopriv_aqualuxe_filter_products', 'aqualuxe_ajax_filter_products');
}
add_action('init', 'aqualuxe_advanced_product_filtering');