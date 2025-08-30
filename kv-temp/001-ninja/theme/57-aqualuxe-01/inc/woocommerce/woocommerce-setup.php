<?php
/**
 * WooCommerce setup
 *
 * @package AquaLuxe
 */

/**
 * WooCommerce setup function.
 *
 * @return void
 */
function aqualuxe_woocommerce_setup() {
    // Add theme support for WooCommerce
    add_theme_support('woocommerce');
    
    // Add support for WooCommerce features
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Remove default WooCommerce styles
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_setup');

/**
 * Define image sizes
 */
function aqualuxe_woocommerce_image_dimensions() {
    global $pagenow;
    
    // Only on the WooCommerce settings page
    if ($pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === 'wc-settings' && isset($_GET['tab']) && $_GET['tab'] === 'products' && isset($_GET['section']) && $_GET['section'] === 'display') {
        return;
    }
    
    // Update image sizes
    update_option('woocommerce_thumbnail_image_width', 400);
    update_option('woocommerce_single_image_width', 800);
    
    // Hard crop product thumbnails
    update_option('woocommerce_thumbnail_cropping', 'custom');
    update_option('woocommerce_thumbnail_cropping_custom_width', 4);
    update_option('woocommerce_thumbnail_cropping_custom_height', 3);
}
add_action('init', 'aqualuxe_woocommerce_image_dimensions');

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
        'columns'        => get_theme_mod('aqualuxe_shop_columns_desktop', 3),
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
        'posts_per_page' => 4,
        'columns'        => get_theme_mod('aqualuxe_shop_columns_desktop', 3),
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
        'posts_per_page' => 2,
        'columns'        => 2,
    );

    $args = wp_parse_args($defaults, $args);

    return $args;
}
add_filter('woocommerce_cross_sells_columns', function() { return 2; });
add_filter('woocommerce_cross_sells_total', function() { return 2; });

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
 * Default loop columns on product archives.
 *
 * @return integer products per row.
 */
function aqualuxe_woocommerce_loop_columns() {
    return get_theme_mod('aqualuxe_shop_columns_desktop', 3);
}
add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_columns');

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
 * Add sidebar to WooCommerce pages.
 */
function aqualuxe_woocommerce_sidebar() {
    // Get the layout
    $layout = 'right-sidebar';
    
    if (is_shop() || is_product_category() || is_product_tag()) {
        $layout = get_theme_mod('aqualuxe_shop_layout', 'right-sidebar');
    } elseif (is_product()) {
        $layout = get_theme_mod('aqualuxe_product_layout', 'right-sidebar');
    }
    
    // Return if full width
    if ($layout === 'full-width') {
        return;
    }
    
    // Get the sidebar
    if (is_shop() || is_product_category() || is_product_tag()) {
        get_sidebar('shop');
    } else {
        get_sidebar();
    }
}
add_action('woocommerce_sidebar', 'aqualuxe_woocommerce_sidebar');

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

if (!function_exists('aqualuxe_woocommerce_cart_link_fragment')) {
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
}
add_filter('woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_link_fragment');

if (!function_exists('aqualuxe_woocommerce_cart_link')) {
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
}

if (!function_exists('aqualuxe_woocommerce_header_cart')) {
    /**
     * Display Header Cart.
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
        <div id="site-header-cart" class="site-header-cart">
            <div class="<?php echo esc_attr($class); ?>">
                <?php aqualuxe_woocommerce_cart_link(); ?>
            </div>
            <div class="site-header-cart-contents">
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
}

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
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
function aqualuxe_woocommerce_active_body_class_setup($classes) {
    $classes[] = 'woocommerce-active';

    return $classes;
}
add_filter('body_class', 'aqualuxe_woocommerce_active_body_class_setup');

/**
 * Products per page.
 *
 * @return integer number of products.
 */
function aqualuxe_woocommerce_products_per_page_setup() {
    return get_theme_mod('aqualuxe_products_per_page', 12);
}
add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page_setup', 20);

/**
 * Product gallery thumbnail columns.
 *
 * @return integer number of columns.
 */
function aqualuxe_woocommerce_thumbnail_columns_setup() {
    return 4;
}
add_filter('woocommerce_product_thumbnails_columns', 'aqualuxe_woocommerce_thumbnail_columns_setup');

/**
 * Default loop columns on product archives.
 *
 * @return integer products per row.
 */
function aqualuxe_woocommerce_loop_columns_setup() {
    return get_theme_mod('aqualuxe_shop_columns_desktop', 3);
}
add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_columns_setup');

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function aqualuxe_woocommerce_related_products_args_setup($args) {
    $defaults = array(
        'posts_per_page' => get_theme_mod('aqualuxe_related_products_count', 4),
        'columns'        => get_theme_mod('aqualuxe_shop_columns_desktop', 3),
    );

    $args = wp_parse_args($defaults, $args);

    return $args;
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args_setup');

/**
 * Remove default WooCommerce wrapper.
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

/**
 * Remove WooCommerce breadcrumbs.
 */
function aqualuxe_remove_wc_breadcrumbs() {
    if (get_theme_mod('aqualuxe_enable_breadcrumbs', true)) {
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
    }
}
add_action('init', 'aqualuxe_remove_wc_breadcrumbs');

/**
 * Rename product tabs.
 */
function aqualuxe_rename_tabs($tabs) {
    // Rename the description tab
    if (isset($tabs['description'])) {
        $tabs['description']['title'] = esc_html__('Description', 'aqualuxe');
    }
    
    // Rename the additional information tab
    if (isset($tabs['additional_information'])) {
        $tabs['additional_information']['title'] = esc_html__('Specifications', 'aqualuxe');
    }

    return $tabs;
}
add_filter('woocommerce_product_tabs', 'aqualuxe_rename_tabs', 98);

/**
 * Change number of products that are displayed per page (shop page).
 */
function aqualuxe_products_per_page($cols) {
    return get_theme_mod('aqualuxe_products_per_page', 12);
}
add_filter('loop_shop_per_page', 'aqualuxe_products_per_page', 20);

/**
 * Change number of related products output.
 */
function aqualuxe_related_products_limit($args) {
    $args['posts_per_page'] = get_theme_mod('aqualuxe_related_products_count', 4);
    $args['columns'] = get_theme_mod('aqualuxe_shop_columns_desktop', 3);
    
    return $args;
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_related_products_limit');

/**
 * Change gallery thumbnail size.
 */
function aqualuxe_gallery_thumbnail_size($size) {
    return array(
        'width'  => 100,
        'height' => 100,
        'crop'   => 1,
    );
}
add_filter('woocommerce_get_image_size_gallery_thumbnail', 'aqualuxe_gallery_thumbnail_size');

/**
 * Change number of upsells output.
 */
function aqualuxe_upsell_products_args($args) {
    $args['posts_per_page'] = 4;
    $args['columns'] = get_theme_mod('aqualuxe_shop_columns_desktop', 3);
    
    return $args;
}
add_filter('woocommerce_upsell_display_args', 'aqualuxe_upsell_products_args');

/**
 * Change number of cross sells columns.
 */
function aqualuxe_cross_sells_columns($columns) {
    return 2;
}
add_filter('woocommerce_cross_sells_columns', 'aqualuxe_cross_sells_columns');

/**
 * Change number of cross sells products.
 */
function aqualuxe_cross_sells_total($columns) {
    return 2;
}
add_filter('woocommerce_cross_sells_total', 'aqualuxe_cross_sells_total');

/**
 * Add custom badges to products.
 */
function aqualuxe_product_badges() {
    global $product;
    
    // Sale badge
    if ($product->is_on_sale()) {
        $sale_text = get_theme_mod('aqualuxe_sale_badge_text', esc_html__('Sale!', 'aqualuxe'));
        echo '<span class="onsale">' . esc_html($sale_text) . '</span>';
    }
    
    // New badge
    if (get_theme_mod('aqualuxe_new_badge', true)) {
        $postdate = get_the_time('Y-m-d');
        $postdatestamp = strtotime($postdate);
        $newness = get_theme_mod('aqualuxe_new_badge_duration', 7);
        
        if ((time() - (60 * 60 * 24 * $newness)) < $postdatestamp) {
            $new_text = get_theme_mod('aqualuxe_new_badge_text', esc_html__('New!', 'aqualuxe'));
            echo '<span class="new-badge">' . esc_html($new_text) . '</span>';
        }
    }
    
    // Out of stock badge
    if (!$product->is_in_stock()) {
        echo '<span class="out-of-stock-badge">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_product_badges', 10);
add_action('woocommerce_before_single_product_summary', 'aqualuxe_product_badges', 10);

/**
 * Modify sale flash HTML.
 */
function aqualuxe_sale_flash($html, $post, $product) {
    $sale_text = get_theme_mod('aqualuxe_sale_badge_text', esc_html__('Sale!', 'aqualuxe'));
    
    return '<span class="onsale">' . esc_html($sale_text) . '</span>';
}
add_filter('woocommerce_sale_flash', 'aqualuxe_sale_flash', 10, 3);

/**
 * Add wishlist button to product loop.
 */
function aqualuxe_add_wishlist_button() {
    if (get_theme_mod('aqualuxe_enable_wishlist', true) && function_exists('aqualuxe_add_to_wishlist_button')) {
        aqualuxe_add_to_wishlist_button();
    }
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_add_wishlist_button', 15);

/**
 * Add compare button to product loop.
 */
function aqualuxe_add_compare_button() {
    if (get_theme_mod('aqualuxe_enable_compare', true) && function_exists('aqualuxe_add_to_compare_button')) {
        aqualuxe_add_to_compare_button();
    }
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_add_compare_button', 20);

/**
 * Add quick view button to product loop.
 */
function aqualuxe_add_quick_view_button() {
    if (get_theme_mod('aqualuxe_quick_view', true) && function_exists('aqualuxe_quick_view_button')) {
        aqualuxe_quick_view_button();
    }
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_add_quick_view_button', 25);

/**
 * Modify add to cart button text.
 */
function aqualuxe_add_to_cart_text($text) {
    if (get_theme_mod('aqualuxe_catalog_mode', false)) {
        return esc_html__('View Product', 'aqualuxe');
    }
    
    return $text;
}
add_filter('woocommerce_product_add_to_cart_text', 'aqualuxe_add_to_cart_text');
add_filter('woocommerce_product_single_add_to_cart_text', 'aqualuxe_add_to_cart_text');

/**
 * Hide price in catalog mode.
 */
function aqualuxe_hide_price_catalog_mode() {
    if (get_theme_mod('aqualuxe_catalog_mode', false)) {
        remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    }
}
add_action('init', 'aqualuxe_hide_price_catalog_mode');

/**
 * Hide add to cart button in catalog mode.
 */
function aqualuxe_hide_add_to_cart_catalog_mode() {
    if (get_theme_mod('aqualuxe_catalog_mode', false)) {
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    }
}
add_action('init', 'aqualuxe_hide_add_to_cart_catalog_mode');

/**
 * Add product categories to product loop.
 */
function aqualuxe_add_categories_to_product_loop() {
    global $product;
    
    if (get_theme_mod('aqualuxe_product_categories', true)) {
        echo wc_get_product_category_list($product->get_id(), ', ', '<div class="product-categories">', '</div>');
    }
}
add_action('woocommerce_shop_loop_item_title', 'aqualuxe_add_categories_to_product_loop', 5);

/**
 * Add product rating to product loop.
 */
function aqualuxe_add_rating_to_product_loop() {
    global $product;
    
    if (get_theme_mod('aqualuxe_product_ratings', true) && $product->get_rating_count() > 0) {
        echo wc_get_rating_html($product->get_average_rating());
    }
}
add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_add_rating_to_product_loop', 5);

/**
 * Add product stock status to product loop.
 */
function aqualuxe_add_stock_status_to_product_loop() {
    global $product;
    
    if (get_theme_mod('aqualuxe_product_stock_status', true)) {
        echo wc_get_stock_html($product);
    }
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_add_stock_status_to_product_loop', 5);

/**
 * Add product excerpt to product loop.
 */
function aqualuxe_add_excerpt_to_product_loop() {
    global $product;
    
    if ($product->get_short_description()) {
        echo '<div class="product-short-description">' . wp_kses_post($product->get_short_description()) . '</div>';
    }
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_add_excerpt_to_product_loop', 7);

/**
 * Modify product tabs.
 */
function aqualuxe_modify_product_tabs($tabs) {
    // Rename tabs
    if (isset($tabs['description'])) {
        $tabs['description']['title'] = esc_html__('Description', 'aqualuxe');
    }
    
    if (isset($tabs['additional_information'])) {
        $tabs['additional_information']['title'] = esc_html__('Specifications', 'aqualuxe');
    }
    
    // Reorder tabs
    $tabs['description']['priority'] = 10;
    $tabs['additional_information']['priority'] = 20;
    $tabs['reviews']['priority'] = 30;
    
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'aqualuxe_modify_product_tabs', 98);

/**
 * Add recently viewed products.
 */
function aqualuxe_recently_viewed_products() {
    if (!is_product() || !get_theme_mod('aqualuxe_recently_viewed_products', true)) {
        return;
    }
    
    $viewed_products = !empty($_COOKIE['woocommerce_recently_viewed']) ? (array) explode('|', $_COOKIE['woocommerce_recently_viewed']) : array();
    $viewed_products = array_filter(array_map('absint', $viewed_products));
    
    if (empty($viewed_products)) {
        return;
    }
    
    $current_product_id = get_the_ID();
    $viewed_products = array_diff($viewed_products, array($current_product_id));
    
    if (empty($viewed_products)) {
        return;
    }
    
    $count = get_theme_mod('aqualuxe_recently_viewed_products_count', 4);
    $viewed_products = array_slice($viewed_products, 0, $count);
    
    woocommerce_related_products(array(
        'posts_per_page' => $count,
        'columns'        => get_theme_mod('aqualuxe_shop_columns_desktop', 3),
        'orderby'        => 'rand',
        'post__in'       => $viewed_products,
    ));
}
add_action('woocommerce_after_single_product_summary', 'aqualuxe_recently_viewed_products', 21);

/**
 * Track product views.
 */
function aqualuxe_track_product_view() {
    if (!is_singular('product')) {
        return;
    }
    
    global $post;
    
    if (empty($_COOKIE['woocommerce_recently_viewed'])) {
        $viewed_products = array();
    } else {
        $viewed_products = (array) explode('|', $_COOKIE['woocommerce_recently_viewed']);
    }
    
    if (!in_array($post->ID, $viewed_products)) {
        $viewed_products[] = $post->ID;
    }
    
    if (count($viewed_products) > 15) {
        array_shift($viewed_products);
    }
    
    // Store for session only
    wc_setcookie('woocommerce_recently_viewed', implode('|', $viewed_products));
}
add_action('template_redirect', 'aqualuxe_track_product_view', 20);

/**
 * Add sticky add to cart.
 */
function aqualuxe_sticky_add_to_cart() {
    if (!is_product() || !get_theme_mod('aqualuxe_sticky_add_to_cart', true)) {
        return;
    }
    
    global $product;
    
    // Only show if product is purchasable
    if (!$product->is_purchasable() || !$product->is_in_stock()) {
        return;
    }
    
    // Don't show in catalog mode
    if (get_theme_mod('aqualuxe_catalog_mode', false)) {
        return;
    }
    
    ?>
    <div class="aqualuxe-sticky-add-to-cart">
        <div class="container">
            <div class="aqualuxe-sticky-add-to-cart__content">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="aqualuxe-sticky-add-to-cart__thumbnail">
                        <?php echo woocommerce_get_product_thumbnail('thumbnail'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="aqualuxe-sticky-add-to-cart__content-product-info">
                    <span class="aqualuxe-sticky-add-to-cart__content-title"><?php the_title(); ?></span>
                    <span class="aqualuxe-sticky-add-to-cart__content-price"><?php echo $product->get_price_html(); ?></span>
                    <?php echo wc_get_rating_html($product->get_average_rating()); ?>
                </div>
                
                <div class="aqualuxe-sticky-add-to-cart__content-button">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'aqualuxe_sticky_add_to_cart');

/**
 * Distraction free checkout.
 */
function aqualuxe_distraction_free_checkout() {
    if (!is_checkout() || !get_theme_mod('aqualuxe_distraction_free_checkout', false)) {
        return;
    }
    
    // Remove header
    remove_all_actions('aqualuxe_header');
    add_action('aqualuxe_header', 'aqualuxe_checkout_header');
    
    // Remove footer
    remove_all_actions('aqualuxe_footer');
    add_action('aqualuxe_footer', 'aqualuxe_checkout_footer');
}
add_action('wp', 'aqualuxe_distraction_free_checkout');

/**
 * Checkout header for distraction free checkout.
 */
function aqualuxe_checkout_header() {
    ?>
    <header id="masthead" class="site-header checkout-header">
        <div class="container">
            <div class="checkout-header-inner">
                <div class="site-branding">
                    <?php aqualuxe_site_logo(); ?>
                </div>
                <div class="checkout-steps">
                    <?php aqualuxe_checkout_steps(); ?>
                </div>
            </div>
        </div>
    </header>
    <?php
}

/**
 * Checkout footer for distraction free checkout.
 */
function aqualuxe_checkout_footer() {
    ?>
    <footer id="colophon" class="site-footer checkout-footer">
        <div class="container">
            <div class="checkout-footer-inner">
                <div class="site-info">
                    <?php echo wp_kses_post(get_theme_mod('aqualuxe_copyright_text', sprintf(esc_html__('© %s AquaLuxe. All rights reserved.', 'aqualuxe'), date('Y')))); ?>
                </div>
                <?php if (has_nav_menu('footer-menu')) : ?>
                    <nav class="footer-navigation">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer-menu',
                            'menu_class'     => 'footer-menu',
                            'depth'          => 1,
                        ));
                        ?>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </footer>
    <?php
}

/**
 * Checkout steps.
 */
function aqualuxe_checkout_steps() {
    $steps = array(
        'cart'     => esc_html__('Cart', 'aqualuxe'),
        'checkout' => esc_html__('Checkout', 'aqualuxe'),
        'order'    => esc_html__('Order Complete', 'aqualuxe'),
    );
    
    $current_step = is_checkout() ? 'checkout' : (is_cart() ? 'cart' : (is_order_received_page() ? 'order' : ''));
    
    echo '<ul class="checkout-steps-list">';
    
    $i = 1;
    foreach ($steps as $step => $label) {
        $class = $step === $current_step ? 'active' : ($i < array_search($current_step, array_keys($steps)) + 1 ? 'completed' : '');
        
        echo '<li class="' . esc_attr($class) . '">';
        echo '<span class="step-number">' . esc_html($i) . '</span>';
        echo '<span class="step-label">' . esc_html($label) . '</span>';
        echo '</li>';
        
        $i++;
    }
    
    echo '</ul>';
}

/**
 * Modify checkout fields.
 */
function aqualuxe_checkout_fields($fields) {
    // Make Company field optional
    if (isset($fields['billing']['billing_company'])) {
        $fields['billing']['billing_company']['required'] = false;
    }
    
    if (isset($fields['shipping']['shipping_company'])) {
        $fields['shipping']['shipping_company']['required'] = false;
    }
    
    // Add placeholder to all fields
    foreach ($fields as $section => $section_fields) {
        foreach ($section_fields as $key => $field) {
            if (!isset($field['placeholder']) && isset($field['label'])) {
                $fields[$section][$key]['placeholder'] = $field['label'];
            }
        }
    }
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'aqualuxe_checkout_fields');

/**
 * Add order tracking form to account page.
 */
function aqualuxe_add_order_tracking_endpoint() {
    if (!get_theme_mod('aqualuxe_order_tracking', true)) {
        return;
    }
    
    add_rewrite_endpoint('order-tracking', EP_ROOT | EP_PAGES);
}
add_action('init', 'aqualuxe_add_order_tracking_endpoint');

/**
 * Add order tracking to account menu.
 */
function aqualuxe_add_order_tracking_menu_item($items) {
    if (!get_theme_mod('aqualuxe_order_tracking', true)) {
        return $items;
    }
    
    $items['order-tracking'] = esc_html__('Order Tracking', 'aqualuxe');
    
    return $items;
}
add_filter('woocommerce_account_menu_items', 'aqualuxe_add_order_tracking_menu_item');

/**
 * Add order tracking content.
 */
function aqualuxe_order_tracking_content() {
    if (!get_theme_mod('aqualuxe_order_tracking', true)) {
        return;
    }
    
    echo '<h2>' . esc_html__('Order Tracking', 'aqualuxe') . '</h2>';
    echo '<p>' . esc_html__('To track your order please enter your Order ID in the box below and press the "Track" button. This was given to you on your receipt and in the confirmation email you should have received.', 'aqualuxe') . '</p>';
    
    echo do_shortcode('[woocommerce_order_tracking]');
}
add_action('woocommerce_account_order-tracking_endpoint', 'aqualuxe_order_tracking_content');

/**
 * Add product recommendations.
 */
function aqualuxe_product_recommendations() {
    if (!is_account_page() || !get_theme_mod('aqualuxe_product_recommendations', true)) {
        return;
    }
    
    // Get customer orders
    $customer_orders = get_posts(array(
        'numberposts' => -1,
        'meta_key'    => '_customer_user',
        'meta_value'  => get_current_user_id(),
        'post_type'   => wc_get_order_types(),
        'post_status' => array_keys(wc_get_order_statuses()),
    ));
    
    if (empty($customer_orders)) {
        return;
    }
    
    // Get ordered products
    $ordered_products = array();
    foreach ($customer_orders as $customer_order) {
        $order = wc_get_order($customer_order->ID);
        foreach ($order->get_items() as $item) {
            $ordered_products[] = $item->get_product_id();
        }
    }
    
    if (empty($ordered_products)) {
        return;
    }
    
    // Get product categories
    $product_cats = array();
    foreach ($ordered_products as $product_id) {
        $terms = get_the_terms($product_id, 'product_cat');
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $product_cats[] = $term->term_id;
            }
        }
    }
    
    if (empty($product_cats)) {
        return;
    }
    
    // Get recommendations
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 4,
        'post__not_in'   => $ordered_products,
        'orderby'        => 'rand',
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => array_unique($product_cats),
            ),
        ),
    );
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) {
        echo '<div class="woocommerce">';
        echo '<h2>' . esc_html__('Recommended Products', 'aqualuxe') . '</h2>';
        echo '<ul class="products columns-4">';
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        echo '</ul>';
        echo '</div>';
        
        wp_reset_postdata();
    }
}
add_action('woocommerce_account_dashboard', 'aqualuxe_product_recommendations', 20);