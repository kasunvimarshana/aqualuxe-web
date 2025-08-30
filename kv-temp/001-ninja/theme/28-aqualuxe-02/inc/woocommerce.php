<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if WooCommerce is active
 *
 * @return bool True if WooCommerce is active, false otherwise
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
 *
 * @return void
 */
function aqualuxe_woocommerce_setup() {
    add_theme_support(
        'woocommerce',
        array(
            'thumbnail_image_width' => 350,
            'single_image_width'    => 600,
            'product_grid'          => array(
                'default_rows'    => 3,
                'min_rows'        => 1,
                'default_columns' => 4,
                'min_columns'     => 1,
                'max_columns'     => 6,
            ),
        )
    );
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
    if (aqualuxe_is_woocommerce_active()) {
        // Load WooCommerce styles and scripts if WooCommerce is active
        wp_enqueue_style('aqualuxe-woocommerce-style', AQUALUXE_URI . 'assets/css/woocommerce.css', array(), AQUALUXE_VERSION);

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
        wp_enqueue_script('aqualuxe-woocommerce', AQUALUXE_URI . 'assets/js/woocommerce.js', array('jquery'), AQUALUXE_VERSION, true);
    } else {
        // Load fallback styles when WooCommerce is not active
        wp_enqueue_style('aqualuxe-woocommerce-fallback', AQUALUXE_URI . 'assets/css/woocommerce-fallback.css', array(), AQUALUXE_VERSION);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts');

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
function aqualuxe_disable_woocommerce_styles() {
    // Only disable if WooCommerce is active
    if (aqualuxe_is_woocommerce_active()) {
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
    }
}
add_action('init', 'aqualuxe_disable_woocommerce_styles');

/**
 * Add WooCommerce status classes to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include WooCommerce status classes.
 */
function aqualuxe_woocommerce_active_body_class($classes) {
    if (aqualuxe_is_woocommerce_active()) {
        $classes[] = 'woocommerce-active';
    } else {
        $classes[] = 'woocommerce-inactive';
    }

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
        'posts_per_page' => 3,
        'columns'        => 3,
    );

    $args = wp_parse_args($defaults, $args);

    return $args;
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args');

/**
 * Remove default WooCommerce wrapper.
 */
function aqualuxe_remove_woocommerce_wrapper() {
    if (aqualuxe_is_woocommerce_active()) {
        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
    }
}
add_action('init', 'aqualuxe_remove_woocommerce_wrapper');

if (!function_exists('aqualuxe_woocommerce_wrapper_before')) {
    /**
     * Before Content.
     *
     * Wraps all WooCommerce content in wrappers which match the theme markup.
     *
     * @return void
     */
    function aqualuxe_woocommerce_wrapper_before() {
        ?>
        <main id="primary" class="site-main container mx-auto px-4 py-12">
        <?php
    }
}
add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before');

if (!function_exists('aqualuxe_woocommerce_wrapper_after')) {
    /**
     * After Content.
     *
     * Closes the wrapping divs.
     *
     * @return void
     */
    function aqualuxe_woocommerce_wrapper_after() {
        ?>
        </main><!-- #main -->
        <?php
    }
}
add_action('woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after');

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
 * <?php
 * if (function_exists('aqualuxe_woocommerce_header_cart')) {
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
        if (!aqualuxe_is_woocommerce_active()) {
            return;
        }
        
        ?>
        <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'aqualuxe'); ?>">
            <span class="cart-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </span>
            <span class="cart-count"><?php echo wp_kses_data(WC()->cart->get_cart_contents_count()); ?></span>
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
        if (!aqualuxe_is_woocommerce_active()) {
            return;
        }
        
        $class = is_cart() ? 'current-menu-item' : '';
        ?>
        <div id="site-header-cart" class="site-header-cart">
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
}

/**
 * Customize WooCommerce product columns
 */
function aqualuxe_woocommerce_loop_columns() {
    return get_theme_mod('aqualuxe_shop_columns', 3);
}
add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_columns');

/**
 * Customize WooCommerce products per page
 */
function aqualuxe_woocommerce_products_per_page() {
    return get_theme_mod('aqualuxe_products_per_page', 12);
}
add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page');

/**
 * Remove related products if disabled in customizer
 */
function aqualuxe_remove_related_products() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    if (!get_theme_mod('aqualuxe_related_products', true)) {
        remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
    }
}
add_action('wp', 'aqualuxe_remove_related_products');

/**
 * Customize WooCommerce breadcrumbs
 */
function aqualuxe_woocommerce_breadcrumbs() {
    return array(
        'delimiter'   => ' <span class="breadcrumb-separator">/</span> ',
        'wrap_before' => '<nav class="woocommerce-breadcrumb text-sm mb-6" itemprop="breadcrumb">',
        'wrap_after'  => '</nav>',
        'before'      => '',
        'after'       => '',
        'home'        => _x('Home', 'breadcrumb', 'aqualuxe'),
    );
}
add_filter('woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumbs');

/**
 * Add Bootstrap classes to WooCommerce buttons
 */
function aqualuxe_woocommerce_button_classes($args, $product) {
    $args['class'] = $args['class'] . ' bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors';
    
    return $args;
}
add_filter('woocommerce_loop_add_to_cart_args', 'aqualuxe_woocommerce_button_classes', 10, 2);

/**
 * Add Bootstrap classes to WooCommerce forms
 */
function aqualuxe_woocommerce_form_field_args($args, $key, $value) {
    // Start field type switch case
    switch ($args['type']) {
        case 'text':
        case 'password':
        case 'email':
        case 'tel':
        case 'number':
        case 'search':
        case 'url':
        case 'date':
        case 'time':
        case 'datetime-local':
        case 'month':
        case 'week':
            $args['input_class'][] = 'w-full p-2 border border-gray-300 rounded';
            $args['label_class'][] = 'block mb-2';
            break;
        case 'textarea':
            $args['input_class'][] = 'w-full p-2 border border-gray-300 rounded';
            $args['label_class'][] = 'block mb-2';
            break;
        case 'checkbox':
            $args['input_class'][] = 'mr-2';
            $args['label_class'][] = 'inline-block';
            break;
        case 'radio':
            $args['input_class'][] = 'mr-2';
            $args['label_class'][] = 'inline-block';
            break;
        case 'select':
            $args['input_class'][] = 'w-full p-2 border border-gray-300 rounded';
            $args['label_class'][] = 'block mb-2';
            break;
        default:
            $args['input_class'][] = 'w-full p-2 border border-gray-300 rounded';
            $args['label_class'][] = 'block mb-2';
            break;
    }

    return $args;
}
add_filter('woocommerce_form_field_args', 'aqualuxe_woocommerce_form_field_args', 10, 3);

/**
 * Add custom product tabs
 */
function aqualuxe_woocommerce_custom_product_tabs($tabs) {
    if (!aqualuxe_is_woocommerce_active()) {
        return $tabs;
    }
    
    // Add shipping tab
    $tabs['shipping'] = array(
        'title'    => __('Shipping & Returns', 'aqualuxe'),
        'priority' => 30,
        'callback' => 'aqualuxe_woocommerce_shipping_tab_content',
    );

    // Add care tab for specific product categories
    global $product;
    if ($product && (has_term('fish', 'product_cat', $product->get_id()) || has_term('plants', 'product_cat', $product->get_id()))) {
        $tabs['care'] = array(
            'title'    => __('Care Instructions', 'aqualuxe'),
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
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    // Get shipping content from theme mod or use default
    $shipping_content = get_theme_mod('aqualuxe_shipping_content', '');
    
    if (empty($shipping_content)) {
        // Default shipping content
        ?>
        <h3><?php esc_html_e('Shipping Information', 'aqualuxe'); ?></h3>
        <p><?php esc_html_e('We ship worldwide with special care for live fish and aquatic plants. All orders are carefully packaged to ensure safe arrival.', 'aqualuxe'); ?></p>
        
        <h4><?php esc_html_e('Domestic Shipping', 'aqualuxe'); ?></h4>
        <p><?php esc_html_e('Orders are typically processed within 1-2 business days. Delivery times vary based on location, but generally take 2-5 business days.', 'aqualuxe'); ?></p>
        
        <h4><?php esc_html_e('International Shipping', 'aqualuxe'); ?></h4>
        <p><?php esc_html_e('International orders require special handling and may be subject to customs duties and taxes. Please contact us for specific shipping details to your country.', 'aqualuxe'); ?></p>
        
        <h3><?php esc_html_e('Returns & Guarantees', 'aqualuxe'); ?></h3>
        <p><?php esc_html_e('We offer a DOA (Dead on Arrival) guarantee for all live fish and plants. Please notify us within 2 hours of delivery with photos for a replacement or refund.', 'aqualuxe'); ?></p>
        <p><?php esc_html_e('Equipment and supplies can be returned within 30 days in original packaging. Custom aquariums and installations are non-refundable.', 'aqualuxe'); ?></p>
        <?php
    } else {
        echo wp_kses_post($shipping_content);
    }
}

/**
 * Care tab content
 */
function aqualuxe_woocommerce_care_tab_content() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    // Get product care instructions from product meta
    $care_instructions = get_post_meta($product->get_id(), '_care_instructions', true);
    
    if (empty($care_instructions)) {
        // Default care instructions based on category
        if (has_term('fish', 'product_cat', $product->get_id())) {
            ?>
            <h3><?php esc_html_e('Fish Care Instructions', 'aqualuxe'); ?></h3>
            <p><?php esc_html_e('Proper care is essential for the health and longevity of your aquatic pets.', 'aqualuxe'); ?></p>
            
            <h4><?php esc_html_e('Water Parameters', 'aqualuxe'); ?></h4>
            <p><?php esc_html_e('Maintain appropriate water temperature, pH, and hardness levels for this species. Regular water testing is recommended.', 'aqualuxe'); ?></p>
            
            <h4><?php esc_html_e('Feeding', 'aqualuxe'); ?></h4>
            <p><?php esc_html_e('Feed small amounts 1-2 times daily. Remove uneaten food after 5 minutes to maintain water quality.', 'aqualuxe'); ?></p>
            
            <h4><?php esc_html_e('Tank Maintenance', 'aqualuxe'); ?></h4>
            <p><?php esc_html_e('Perform regular water changes (20-30% weekly) and clean the filter as needed. Avoid using soap or chemicals when cleaning aquarium equipment.', 'aqualuxe'); ?></p>
            <?php
        } elseif (has_term('plants', 'product_cat', $product->get_id())) {
            ?>
            <h3><?php esc_html_e('Aquatic Plant Care', 'aqualuxe'); ?></h3>
            <p><?php esc_html_e('Proper care will help your aquatic plants thrive and enhance your aquarium.', 'aqualuxe'); ?></p>
            
            <h4><?php esc_html_e('Lighting', 'aqualuxe'); ?></h4>
            <p><?php esc_html_e('Most aquatic plants require 8-10 hours of light daily. Adjust lighting intensity based on plant species and tank depth.', 'aqualuxe'); ?></p>
            
            <h4><?php esc_html_e('Nutrients', 'aqualuxe'); ?></h4>
            <p><?php esc_html_e('Use a quality substrate and consider liquid fertilizers or root tabs for optimal growth. CO2 supplementation may benefit high-demand plants.', 'aqualuxe'); ?></p>
            
            <h4><?php esc_html_e('Maintenance', 'aqualuxe'); ?></h4>
            <p><?php esc_html_e('Trim dead or yellowing leaves regularly. Some plants may need periodic pruning to maintain shape and encourage new growth.', 'aqualuxe'); ?></p>
            <?php
        }
    } else {
        echo wp_kses_post($care_instructions);
    }
}

/**
 * Add wholesale inquiry form to single product pages
 */
function aqualuxe_wholesale_inquiry_form() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if ($product && $product->is_in_stock()) {
        ?>
        <div class="wholesale-inquiry-form mt-8 p-6 bg-gray-50 rounded-lg">
            <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Wholesale Inquiry', 'aqualuxe'); ?></h3>
            <p class="mb-4"><?php esc_html_e('Interested in bulk orders? Contact our wholesale department for special pricing and availability.', 'aqualuxe'); ?></p>
            <a href="<?php echo esc_url(home_url('/wholesale')); ?>" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors">
                <?php esc_html_e('Request Wholesale Quote', 'aqualuxe'); ?>
            </a>
        </div>
        <?php
    }
}
add_action('woocommerce_after_add_to_cart_form', 'aqualuxe_wholesale_inquiry_form');

/**
 * Add estimated shipping time to single product pages
 */
function aqualuxe_estimated_shipping_time() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if ($product && $product->is_in_stock()) {
        // Get shipping time from product meta or use default
        $shipping_time = get_post_meta($product->get_id(), '_estimated_shipping', true);
        
        if (empty($shipping_time)) {
            $shipping_time = __('1-3 business days', 'aqualuxe');
        }
        
        ?>
        <div class="estimated-shipping text-sm text-gray-600 mt-4">
            <span class="font-medium"><?php esc_html_e('Estimated Shipping:', 'aqualuxe'); ?></span> <?php echo esc_html($shipping_time); ?>
        </div>
        <?php
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_estimated_shipping_time', 25);

/**
 * Add quick view functionality
 */
function aqualuxe_quick_view_button() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    echo '<a href="#" class="quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</a>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_quick_view_button', 5);

/**
 * Add AJAX handler for quick view
 */
function aqualuxe_quick_view_ajax() {
    if (!aqualuxe_is_woocommerce_active()) {
        wp_send_json_error();
        die();
    }
    
    if (isset($_POST['product_id'])) {
        $product_id = absint($_POST['product_id']);
        
        // Get product data
        $product = wc_get_product($product_id);
        
        if ($product) {
            ob_start();
            ?>
            <div class="quick-view-content">
                <div class="quick-view-image">
                    <?php echo wp_kses_post($product->get_image('medium')); ?>
                </div>
                <div class="quick-view-details">
                    <h2 class="product-title"><?php echo esc_html($product->get_name()); ?></h2>
                    <div class="product-price"><?php echo wp_kses_post($product->get_price_html()); ?></div>
                    <div class="product-description">
                        <?php echo wp_kses_post($product->get_short_description()); ?>
                    </div>
                    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="view-full-details">
                        <?php esc_html_e('View Full Details', 'aqualuxe'); ?>
                    </a>
                    <?php
                    if ($product->is_in_stock()) {
                        woocommerce_template_loop_add_to_cart();
                    } else {
                        echo '<p class="stock out-of-stock">' . esc_html__('Out of stock', 'aqualuxe') . '</p>';
                    }
                    ?>
                </div>
            </div>
            <?php
            $output = ob_get_clean();
            wp_send_json_success($output);
        }
    }
    
    wp_send_json_error();
    die();
}
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax');

/**
 * Add product filters
 */
function aqualuxe_product_filters() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    // Only show on shop and product archive pages
    if (!is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }
    
    ?>
    <div class="product-filters mb-8 p-6 bg-gray-50 rounded-lg">
        <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Filter Products', 'aqualuxe'); ?></h3>
        
        <form method="get" action="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php
                // Price range filter
                $min_price = isset($_GET['min_price']) ? absint($_GET['min_price']) : '';
                $max_price = isset($_GET['max_price']) ? absint($_GET['max_price']) : '';
                ?>
                <div class="filter-group">
                    <h4 class="font-medium mb-2"><?php esc_html_e('Price Range', 'aqualuxe'); ?></h4>
                    <div class="flex items-center space-x-2">
                        <input type="number" name="min_price" placeholder="<?php esc_attr_e('Min', 'aqualuxe'); ?>" value="<?php echo esc_attr($min_price); ?>" class="w-full p-2 border border-gray-300 rounded">
                        <span>-</span>
                        <input type="number" name="max_price" placeholder="<?php esc_attr_e('Max', 'aqualuxe'); ?>" value="<?php echo esc_attr($max_price); ?>" class="w-full p-2 border border-gray-300 rounded">
                    </div>
                </div>
                
                <?php
                // Categories filter
                $product_categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => true,
                ));
                
                if (!empty($product_categories) && !is_wp_error($product_categories)) {
                    $current_category = isset($_GET['product_cat']) ? sanitize_text_field($_GET['product_cat']) : '';
                    ?>
                    <div class="filter-group">
                        <h4 class="font-medium mb-2"><?php esc_html_e('Categories', 'aqualuxe'); ?></h4>
                        <select name="product_cat" class="w-full p-2 border border-gray-300 rounded">
                            <option value=""><?php esc_html_e('All Categories', 'aqualuxe'); ?></option>
                            <?php foreach ($product_categories as $category) : ?>
                                <option value="<?php echo esc_attr($category->slug); ?>" <?php selected($current_category, $category->slug); ?>>
                                    <?php echo esc_html($category->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php
                }
                
                // Sorting filter
                $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : '';
                ?>
                <div class="filter-group">
                    <h4 class="font-medium mb-2"><?php esc_html_e('Sort By', 'aqualuxe'); ?></h4>
                    <select name="orderby" class="w-full p-2 border border-gray-300 rounded">
                        <option value="menu_order" <?php selected($orderby, 'menu_order'); ?>><?php esc_html_e('Default sorting', 'aqualuxe'); ?></option>
                        <option value="popularity" <?php selected($orderby, 'popularity'); ?>><?php esc_html_e('Sort by popularity', 'aqualuxe'); ?></option>
                        <option value="rating" <?php selected($orderby, 'rating'); ?>><?php esc_html_e('Sort by average rating', 'aqualuxe'); ?></option>
                        <option value="date" <?php selected($orderby, 'date'); ?>><?php esc_html_e('Sort by latest', 'aqualuxe'); ?></option>
                        <option value="price" <?php selected($orderby, 'price'); ?>><?php esc_html_e('Sort by price: low to high', 'aqualuxe'); ?></option>
                        <option value="price-desc" <?php selected($orderby, 'price-desc'); ?>><?php esc_html_e('Sort by price: high to low', 'aqualuxe'); ?></option>
                    </select>
                </div>
            </div>
            
            <div class="filter-actions mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors">
                    <?php esc_html_e('Apply Filters', 'aqualuxe'); ?>
                </button>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="ml-4 text-blue-600 hover:text-blue-800">
                    <?php esc_html_e('Reset Filters', 'aqualuxe'); ?>
                </a>
            </div>
        </form>
    </div>
    <?php
}
add_action('woocommerce_before_shop_loop', 'aqualuxe_product_filters', 20);

/**
 * Add product inquiry form
 */
function aqualuxe_product_inquiry_form() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if ($product) {
        ?>
        <div class="product-inquiry-form mt-8 p-6 bg-gray-50 rounded-lg">
            <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Product Inquiry', 'aqualuxe'); ?></h3>
            <p class="mb-4"><?php esc_html_e('Have questions about this product? Fill out the form below and we\'ll get back to you as soon as possible.', 'aqualuxe'); ?></p>
            
            <form class="inquiry-form" method="post" action="#">
                <input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>">
                <input type="hidden" name="product_name" value="<?php echo esc_attr($product->get_name()); ?>">
                
                <div class="mb-4">
                    <label for="inquiry_name" class="block mb-2"><?php esc_html_e('Your Name', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <input type="text" name="inquiry_name" id="inquiry_name" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                
                <div class="mb-4">
                    <label for="inquiry_email" class="block mb-2"><?php esc_html_e('Your Email', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <input type="email" name="inquiry_email" id="inquiry_email" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                
                <div class="mb-4">
                    <label for="inquiry_message" class="block mb-2"><?php esc_html_e('Your Question', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <textarea name="inquiry_message" id="inquiry_message" rows="4" required class="w-full p-2 border border-gray-300 rounded"></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="inquiry_terms" required class="mr-2">
                        <span><?php esc_html_e('I agree to the privacy policy', 'aqualuxe'); ?> <span class="required">*</span></span>
                    </label>
                </div>
                
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors">
                    <?php esc_html_e('Submit Inquiry', 'aqualuxe'); ?>
                </button>
            </form>
        </div>
        <?php
    }
}
add_action('woocommerce_after_single_product_summary', 'aqualuxe_product_inquiry_form', 15);