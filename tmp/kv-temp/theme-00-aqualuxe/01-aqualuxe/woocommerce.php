
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
    add_theme_support('woocommerce');
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
    wp_enqueue_style('aqualuxe-woocommerce-style', AQUALUXE_ASSETS_URI . '/css/woocommerce.css', array(), AQUALUXE_VERSION);

    $font_path   = WC()->plugin_url() . '/assets/fonts/';
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
    
    // Enqueue WooCommerce custom scripts
    wp_enqueue_script('aqualuxe-woocommerce-script', AQUALUXE_ASSETS_URI . '/js/woocommerce.js', array('jquery'), AQUALUXE_VERSION, true);
    
    // Localize script for AJAX functionality
    wp_localize_script('aqualuxe-woocommerce-script', 'aqualuxe_woocommerce_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe-woocommerce-nonce'),
        'i18n_view_cart' => esc_html__('View cart', 'aqualuxe'),
        'i18n_added_to_cart' => esc_html__('Product added to cart', 'aqualuxe'),
        'cart_url' => wc_get_cart_url(),
        'is_cart' => is_cart(),
        'is_checkout' => is_checkout(),
    ));
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
 * Custom WooCommerce wrapper.
 */
function aqualuxe_woocommerce_wrapper_before() {
    ?>
    <main id="primary" class="site-main <?php echo is_active_sidebar('shop-sidebar') ? 'col-md-9' : 'col-md-12'; ?>">
    <?php
}
add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before');

/**
 * Custom WooCommerce wrapper end.
 */
function aqualuxe_woocommerce_wrapper_after() {
    ?>
    </main><!-- #primary -->
    <?php
}
add_action('woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after');

/**
 * Shop sidebar.
 */
function aqualuxe_woocommerce_shop_sidebar() {
    if (is_active_sidebar('shop-sidebar')) {
        ?>
        <aside id="secondary" class="widget-area shop-sidebar col-md-3">
            <?php dynamic_sidebar('shop-sidebar'); ?>
        </aside><!-- #secondary -->
        <?php
    }
}
add_action('woocommerce_sidebar', 'aqualuxe_woocommerce_shop_sidebar');

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
 *
 * Ensure cart contents update when products are added to the cart via AJAX.
 *
 * @param array $fragments Fragments to refresh via AJAX.
 * @return array Fragments to refresh via AJAX.
 */
function aqualuxe_woocommerce_cart_fragments($fragments) {
    ob_start();
    aqualuxe_woocommerce_cart_link();
    $fragments['a.cart-contents'] = ob_get_clean();

    ob_start();
    aqualuxe_woocommerce_cart_count();
    $fragments['span.cart-count'] = ob_get_clean();

    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_fragments');

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
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-count"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
    </a>
    <?php
}

/**
 * Cart Count.
 *
 * Display the cart count.
 *
 * @return void
 */
function aqualuxe_woocommerce_cart_count() {
    ?>
    <span class="cart-count"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
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
        <div class="cart-dropdown">
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
 * Modify the number of products per row
 */
function aqualuxe_woocommerce_loop_columns() {
    return intval(get_theme_mod('aqualuxe_products_per_row', 3));
}
add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_columns');

/**
 * Modify the number of products per page
 */
function aqualuxe_woocommerce_products_per_page() {
    return intval(get_theme_mod('aqualuxe_products_per_page', 12));
}
add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page');

/**
 * Custom sale flash
 */
function aqualuxe_woocommerce_sale_flash($html, $post, $product) {
    if ($product->is_on_sale()) {
        $percentage = 0;
        
        if ($product->is_type('variable')) {
            $prices = $product->get_variation_prices();
            
            if (!empty($prices['regular_price']) && !empty($prices['sale_price'])) {
                $max_percentage = 0;
                
                foreach ($prices['regular_price'] as $key => $regular_price) {
                    $sale_price = $prices['sale_price'][$key];
                    
                    if ($regular_price > 0) {
                        $percentage = round(100 - ($sale_price / $regular_price * 100));
                        $max_percentage = max($max_percentage, $percentage);
                    }
                }
                
                $percentage = $max_percentage;
            }
        } else {
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
            
            if ($regular_price > 0) {
                $percentage = round(100 - ($sale_price / $regular_price * 100));
            }
        }
        
        if ($percentage > 0) {
            return '<span class="onsale">-' . $percentage . '%</span>';
        } else {
            return '<span class="onsale">' . esc_html__('Sale!', 'aqualuxe') . '</span>';
        }
    }
    
    return $html;
}
add_filter('woocommerce_sale_flash', 'aqualuxe_woocommerce_sale_flash', 10, 3);

/**
 * Add featured badge
 */
function aqualuxe_woocommerce_featured_badge() {
    global $product;
    
    if ($product->is_featured()) {
        echo '<span class="featured-badge">' . esc_html__('Featured', 'aqualuxe') . '</span>';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_featured_badge', 5);

/**
 * Add new badge
 */
function aqualuxe_woocommerce_new_badge() {
    global $product;
    
    $newness_days = 30;
    $created = strtotime($product->get_date_created());
    
    if ((time() - (60 * 60 * 24 * $newness_days)) < $created) {
        echo '<span class="new-badge">' . esc_html__('New', 'aqualuxe') . '</span>';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_new_badge', 6);

/**
 * Add out of stock badge
 */
function aqualuxe_woocommerce_out_of_stock_badge() {
    global $product;
    
    if (!$product->is_in_stock()) {
        echo '<span class="out-of-stock-badge">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_out_of_stock_badge', 7);

/**
 * Add product countdown
 */
function aqualuxe_woocommerce_product_countdown() {
    global $product;
    
    if ($product->is_on_sale()) {
        $sale_price_dates_to = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
        
        if ($sale_price_dates_to) {
            $now = current_time('timestamp');
            
            if ($now < $sale_price_dates_to) {
                echo '<div class="aqualuxe-countdown" data-date="' . esc_attr(date('Y/m/d H:i:s', $sale_price_dates_to)) . '">';
                echo '<div class="countdown-title">' . esc_html__('Sale Ends In:', 'aqualuxe') . '</div>';
                echo '<div class="countdown-timer">';
                echo '<div class="countdown-item"><span class="days">00</span><span class="label">' . esc_html__('Days', 'aqualuxe') . '</span></div>';
                echo '<div class="countdown-item"><span class="hours">00</span><span class="label">' . esc_html__('Hours', 'aqualuxe') . '</span></div>';
                echo '<div class="countdown-item"><span class="minutes">00</span><span class="label">' . esc_html__('Minutes', 'aqualuxe') . '</span></div>';
                echo '<div class="countdown-item"><span class="seconds">00</span><span class="label">' . esc_html__('Seconds', 'aqualuxe') . '</span></div>';
                echo '</div>';
                echo '</div>';
            }
        }
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_product_countdown', 8);

/**
 * Add product quick view button
 */
function aqualuxe_woocommerce_quick_view_button() {
    global $product;
    
    echo '<div class="aqualuxe-quick-view-button">';
    echo '<a href="#" class="button quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</a>';
    echo '</div>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_quick_view_button', 15);

/**
 * Add product wishlist button
 */
function aqualuxe_woocommerce_wishlist_button() {
    global $product;
    
    echo '<div class="aqualuxe-wishlist-button">';
    echo '<a href="#" class="button wishlist-button" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</a>';
    echo '</div>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_wishlist_button', 20);

/**
 * Add product compare button
 */
function aqualuxe_woocommerce_compare_button() {
    global $product;
    
    echo '<div class="aqualuxe-compare-button">';
    echo '<a href="#" class="button compare-button" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Compare', 'aqualuxe') . '</a>';
    echo '</div>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_compare_button', 25);

/**
 * Modify add to cart button
 */
function aqualuxe_woocommerce_loop_add_to_cart_link($html, $product) {
    // Add AJAX class to simple products
    if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) {
        $html = str_replace('add_to_cart_button', 'add_to_cart_button aqualuxe-ajax-add-to-cart', $html);
    }
    
    return $html;
}
add_filter('woocommerce_loop_add_to_cart_link', 'aqualuxe_woocommerce_loop_add_to_cart_link', 10, 2);

/**
 * AJAX add to cart
 */
function aqualuxe_woocommerce_ajax_add_to_cart() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce-nonce')) {
        wp_send_json_error(array('message' => esc_html__('Security check failed.', 'aqualuxe')));
        exit;
    }
    
    // Check product ID
    if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
        wp_send_json_error(array('message' => esc_html__('No product selected.', 'aqualuxe')));
        exit;
    }
    
    $product_id = absint($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;
    
    // Add to cart
    $added = WC()->cart->add_to_cart($product_id, $quantity);
    
    if ($added) {
        $data = array(
            'message' => esc_html__('Product added to cart.', 'aqualuxe'),
            'fragments' => apply_filters('woocommerce_add_to_cart_fragments', array()),
        );
        wp_send_json_success($data);
    } else {
        wp_send_json_error(array('message' => esc_html__('Failed to add product to cart.', 'aqualuxe')));
    }
    
    exit;
}
add_action('wp_ajax_aqualuxe_ajax_add_to_cart', 'aqualuxe_woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_aqualuxe_ajax_add_to_cart', 'aqualuxe_woocommerce_ajax_add_to_cart');

/**
 * AJAX quick view
 */
function aqualuxe_woocommerce_ajax_quick_view() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce-nonce')) {
        wp_send_json_error(array('message' => esc_html__('Security check failed.', 'aqualuxe')));
        exit;
    }
    
    // Check product ID
    if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
        wp_send_json_error(array('message' => esc_html__('No product selected.', 'aqualuxe')));
        exit;
    }
    
    $product_id = absint($_POST['product_id']);
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error(array('message' => esc_html__('Product not found.', 'aqualuxe')));
        exit;
    }
    
    // Start output buffering
    ob_start();
    
    // Include quick view template
    include(AQUALUXE_DIR . '/woocommerce/quick-view.php');
    
    // Get the buffered content
    $html = ob_get_clean();
    
    wp_send_json_success(array('html' => $html));
    exit;
}
add_action('wp_ajax_aqualuxe_ajax_quick_view', 'aqualuxe_woocommerce_ajax_quick_view');
add_action('wp_ajax_nopriv_aqualuxe_ajax_quick_view', 'aqualuxe_woocommerce_ajax_quick_view');

/**
 * Quick view modal
 */
function aqualuxe_woocommerce_quick_view_modal() {
    ?>
    <div id="aqualuxe-quick-view-modal" class="aqualuxe-modal">
        <div class="aqualuxe-modal-overlay"></div>
        <div class="aqualuxe-modal-container">
            <div class="aqualuxe-modal-content">
                <span class="aqualuxe-modal-close">&times;</span>
                <div class="aqualuxe-modal-body"></div>
            </div>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'aqualuxe_woocommerce_quick_view_modal');

/**
 * Track product view
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
    
    // Add current product to start of array
    array_unshift($viewed_products, $post->ID);
    
    // Limit to 15 items
    if (count($viewed_products) > 15) {
        $viewed_products = array_slice($viewed_products, 0, 15);
    }
    
    // Store in cookie
    wc_setcookie('woocommerce_recently_viewed', implode('|', $viewed_products));
}
add_action('template_redirect', 'aqualuxe_woocommerce_track_product_view');

/**
 * Recently viewed products
 */
function aqualuxe_woocommerce_recently_viewed_products() {
    if (!is_singular('product')) {
        return;
    }
    
    if (empty($_COOKIE['woocommerce_recently_viewed'])) {
        return;
    }
    
    $viewed_products = wp_parse_id_list((array) explode('|', wp_unslash($_COOKIE['woocommerce_recently_viewed'])));
    
    // Remove current product
    global $post;
    $current_product_id = $post->ID;
    $viewed_products = array_diff($viewed_products, array($current_product_id));
    
    if (empty($viewed_products)) {
        return;
    }
    
    $title = esc_html__('Recently Viewed Products', 'aqualuxe');
    $products_per_row = 4;
    
    $args = array(
        'posts_per_page' => $products_per_row,
        'no_found_rows'  => 1,
        'post_status'    => 'publish',
        'post_type'      => 'product',
        'post__in'       => $viewed_products,
        'orderby'        => 'post__in',
    );
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) {
        echo '<section class="aqualuxe-recently-viewed-products">';
        echo '<h2>' . esc_html($title) . '</h2>';
        
        woocommerce_product_loop_start();
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        woocommerce_product_loop_end();
        
        echo '</section>';
        
        wp_reset_postdata();
    }
}
add_action('woocommerce_after_single_product', 'aqualuxe_woocommerce_recently_viewed_products');

/**
 * Customize checkout fields
 */
function aqualuxe_woocommerce_checkout_fields($fields) {
    // Make company field optional
    if (isset($fields['billing']['billing_company'])) {
        $fields['billing']['billing_company']['required'] = false;
    }
    
    if (isset($fields['shipping']['shipping_company'])) {
        $fields['shipping']['shipping_company']['required'] = false;
    }
    
    // Add placeholder to fields
    foreach ($fields as $section => $section_fields) {
        foreach ($section_fields as $key => $field) {
            if (!isset($field['placeholder']) && isset($field['label'])) {
                $fields[$section][$key]['placeholder'] = $field['label'];
            }
        }
    }
    
    // Add custom fields
    $fields['order']['order_comments']['placeholder'] = esc_html__('Notes about your order, e.g. special notes for delivery.', 'aqualuxe');
    
    $fields['order']['order_gift'] = array(
        'type'        => 'checkbox',
        'label'       => esc_html__('This is a gift', 'aqualuxe'),
        'class'       => array('form-row-wide'),
        'priority'    => 110,
    );
    
    $fields['order']['gift_message'] = array(
        'type'        => 'textarea',
        'label'       => esc_html__('Gift Message', 'aqualuxe'),
        'placeholder' => esc_html__('Enter your gift message here', 'aqualuxe'),
        'class'       => array('form-row-wide'),
        'priority'    => 120,
        'required'    => false,
    );
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'aqualuxe_woocommerce_checkout_fields');

/**
 * Save custom checkout fields
 */
function aqualuxe_woocommerce_save_custom_checkout_fields($order_id) {
    if (isset($_POST['order_gift'])) {
        update_post_meta($order_id, '_order_gift', 'yes');
    }
    
    if (isset($_POST['gift_message']) && !empty($_POST['gift_message'])) {
        update_post_meta($order_id, '_gift_message', sanitize_textarea_field($_POST['gift_message']));
    }
}
add_action('woocommerce_checkout_update_order_meta', 'aqualuxe_woocommerce_save_custom_checkout_fields');

/**
 * Display custom order fields in admin
 */
function aqualuxe_woocommerce_display_custom_order_fields_in_admin($order) {
    $order_id = $order->get_id();
    
    if (get_post_meta($order_id, '_order_gift', true) === 'yes') {
        echo '<p><strong>' . esc_html__('This is a gift', 'aqualuxe') . ':</strong> ' . esc_html__('Yes', 'aqualuxe') . '</p>';
    }
    
    $gift_message = get_post_meta($order_id, '_gift_message', true);
    if (!empty($gift_message)) {
        echo '<p><strong>' . esc_html__('Gift Message', 'aqualuxe') . ':</strong> ' . esc_html($gift_message) . '</p>';
    }
}
add_action('woocommerce_admin_order_data_after_billing_address', 'aqualuxe_woocommerce_display_custom_order_fields_in_admin', 10, 1);

/**
 * Custom product tabs
 */
function aqualuxe_woocommerce_custom_product_tabs($tabs) {
    // Add shipping tab
    $tabs['shipping'] = array(
        'title'    => esc_html__('Shipping & Returns', 'aqualuxe'),
        'priority' => 30,
        'callback' => 'aqualuxe_woocommerce_shipping_tab_content',
    );
    
    // Add care tab for fish products
    if (has_term('fish', 'product_cat')) {
        $tabs['care'] = array(
            'title'    => esc_html__('Care Instructions', 'aqualuxe'),
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
        $shipping_content = '<h3>' . esc_html__('Shipping Information', 'aqualuxe') . '</h3>';
        $shipping_content .= '<p>' . esc_html__('We ship our ornamental fish worldwide with special care to ensure they arrive healthy and safe.', 'aqualuxe') . '</p>';
        $shipping_content .= '<ul>';
        $shipping_content .= '<li>' . esc_html__('Domestic orders: 1-2 business days', 'aqualuxe') . '</li>';
        $shipping_content .= '<li>' . esc_html__('International orders: 3-5 business days', 'aqualuxe') . '</li>';
        $shipping_content .= '</ul>';
        
        $shipping_content .= '<h3>' . esc_html__('Returns Policy', 'aqualuxe') . '</h3>';
        $shipping_content .= '<p>' . esc_html__('If your fish arrives dead or severely injured, please contact us within 24 hours with photos for a replacement or refund.', 'aqualuxe') . '</p>';
    }
    
    echo wp_kses_post($shipping_content);
}

/**
 * Care tab content
 */
function aqualuxe_woocommerce_care_tab_content() {
    global $product;
    
    // Get care instructions from product meta or use default
    $care_instructions = get_post_meta($product->get_id(), '_care_instructions', true);
    
    if (empty($care_instructions)) {
        $care_instructions = '<h3>' . esc_html__('Basic Care Instructions', 'aqualuxe') . '</h3>';
        $care_instructions .= '<p>' . esc_html__('Follow these guidelines to keep your fish healthy and happy:', 'aqualuxe') . '</p>';
        $care_instructions .= '<ul>';
        $care_instructions .= '<li>' . esc_html__('Water Temperature: 72-82\u00b0F (22-28\u00b0C)', 'aqualuxe') . '</li>';
        $care_instructions .= '<li>' . esc_html__('pH Level: 6.5-7.5', 'aqualuxe') . '</li>';
        $care_instructions .= '<li>' . esc_html__('Tank Size: Minimum 10 gallons', 'aqualuxe') . '</li>';
        $care_instructions .= '<li>' . esc_html__('Diet: High-quality flake food, frozen or live foods', 'aqualuxe') . '</li>';
        $care_instructions .= '<li>' . esc_html__('Feeding: 2-3 times daily in small amounts', 'aqualuxe') . '</li>';
        $care_instructions .= '</ul>';
        
        $care_instructions .= '<h3>' . esc_html__('Water Maintenance', 'aqualuxe') . '</h3>';
        $care_instructions .= '<p>' . esc_html__('Regular water changes of 25% every 2 weeks are recommended to maintain water quality.', 'aqualuxe') . '</p>';
    }
    
    echo wp_kses_post($care_instructions);
}

/**
 * Size guide button
 */
function aqualuxe_woocommerce_size_guide_button() {
    global $product;
    
    // Only show for specific categories
    if (!has_term(array('aquariums', 'tanks', 'equipment'), 'product_cat')) {
        return;
    }
    
    echo '<div class="aqualuxe-size-guide-button">';
    echo '<a href="#" class="button size-guide-button">' . esc_html__('Size Guide', 'aqualuxe') . '</a>';
    echo '</div>';
}
add_action('woocommerce_before_add_to_cart_form', 'aqualuxe_woocommerce_size_guide_button');

/**
 * Size guide modal
 */
function aqualuxe_woocommerce_size_guide_modal() {
    ?>
    <div id="aqualuxe-size-guide-modal" class="aqualuxe-modal">
        <div class="aqualuxe-modal-overlay"></div>
        <div class="aqualuxe-modal-container">
            <div class="aqualuxe-modal-content">
                <span class="aqualuxe-modal-close">&times;</span>
                <div class="aqualuxe-modal-body">
                    <h2><?php esc_html_e('Aquarium Size Guide', 'aqualuxe'); ?></h2>
                    
                    <table class="size-guide-table">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Tank Size', 'aqualuxe'); ?></th>
                                <th><?php esc_html_e('Dimensions (L\u00d7W\u00d7H)', 'aqualuxe'); ?></th>
                                <th><?php esc_html_e('Recommended Fish', 'aqualuxe'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>5 Gallons</td>
                                <td>16" \u00d7 8" \u00d7 10"</td>
                                <td>Bettas, Small Tetras</td>
                            </tr>
                            <tr>
                                <td>10 Gallons</td>
                                <td>20" \u00d7 10" \u00d7 12"</td>
                                <td>Guppies, Tetras, Dwarf Gouramis</td>
                            </tr>
                            <tr>
                                <td>20 Gallons</td>
                                <td>24" \u00d7 12" \u00d7 16"</td>
                                <td>Angelfish, Gouramis, Small Cichlids</td>
                            </tr>
                            <tr>
                                <td>30 Gallons</td>
                                <td>36" \u00d7 12" \u00d7 16"</td>
                                <td>Larger Tetras, Barbs, Small Catfish</td>
                            </tr>
                            <tr>
                                <td>55 Gallons</td>
                                <td>48" \u00d7 13" \u00d7 21"</td>
                                <td>Cichlids, Larger Gouramis, Discus</td>
                            </tr>
                            <tr>
                                <td>75 Gallons</td>
                                <td>48" \u00d7 18" \u00d7 21"</td>
                                <td>Larger Cichlids, Rainbowfish, Catfish</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <h3><?php esc_html_e('Fish Stocking Guidelines', 'aqualuxe'); ?></h3>
                    <p><?php esc_html_e('As a general rule, stock 1 inch of fish per gallon of water for small fish, and 1 inch per 2 gallons for larger fish.', 'aqualuxe'); ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'aqualuxe_woocommerce_size_guide_modal');

/**
 * Product navigation
 */
function aqualuxe_woocommerce_product_navigation() {
    if (!is_product()) {
        return;
    }
    
    $previous = get_previous_post(true, '', 'product_cat');
    $next = get_next_post(true, '', 'product_cat');
    
    echo '<div class="aqualuxe-product-navigation">';
    
    if ($previous) {
        echo '<div class="product-nav product-prev">';
        echo '<a href="' . esc_url(get_permalink($previous->ID)) . '" title="' . esc_attr(get_the_title($previous->ID)) . '">';
        echo '<i class="fas fa-chevron-left"></i>';
        echo '<span>' . esc_html__('Previous', 'aqualuxe') . '</span>';
        echo '</a>';
        echo '</div>';
    }
    
    if ($next) {
        echo '<div class="product-nav product-next">';
        echo '<a href="' . esc_url(get_permalink($next->ID)) . '" title="' . esc_attr(get_the_title($next->ID)) . '">';
        echo '<span>' . esc_html__('Next', 'aqualuxe') . '</span>';
        echo '<i class="fas fa-chevron-right"></i>';
        echo '</a>';
        echo '</div>';
    }
    
    echo '</div>';
}
add_action('woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_product_navigation', 5);

/**
 * Product sharing
 */
function aqualuxe_woocommerce_product_sharing() {
    global $product;
    
    $product_url = get_permalink();
    $product_title = get_the_title();
    $product_image = wp_get_attachment_url(get_post_thumbnail_id());
    
    echo '<div class="aqualuxe-product-sharing">';
    echo '<h4>' . esc_html__('Share This Product', 'aqualuxe') . '</h4>';
    echo '<ul class="social-icons">';
    
    // Facebook
    echo '<li><a href="https://www.facebook.com/sharer/sharer.php?u=' . esc_url($product_url) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__('Share on Facebook', 'aqualuxe') . '"><i class="fab fa-facebook-f"></i></a></li>';
    
    // Twitter
    echo '<li><a href="https://twitter.com/intent/tweet?url=' . esc_url($product_url) . '&text=' . esc_attr($product_title) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__('Share on Twitter', 'aqualuxe') . '"><i class="fab fa-twitter"></i></a></li>';
    
    // Pinterest
    echo '<li><a href="https://pinterest.com/pin/create/button/?url=' . esc_url($product_url) . '&media=' . esc_url($product_image) . '&description=' . esc_attr($product_title) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__('Pin on Pinterest', 'aqualuxe') . '"><i class="fab fa-pinterest-p"></i></a></li>';
    
    // Email
    echo '<li><a href="mailto:?subject=' . esc_attr($product_title) . '&body=' . esc_attr__('Check out this product: ', 'aqualuxe') . esc_url($product_url) . '" title="' . esc_attr__('Share via Email', 'aqualuxe') . '"><i class="fas fa-envelope"></i></a></li>';
    
    echo '</ul>';
    echo '</div>';
}
add_action('woocommerce_share', 'aqualuxe_woocommerce_product_sharing');
