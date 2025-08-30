<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
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
        <main id="primary" class="site-main">
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
            <?php
            $item_count_text = sprintf(
                /* translators: number of items in the mini cart. */
                _n('%d item', '%d items', WC()->cart->get_cart_contents_count(), 'aqualuxe'),
                WC()->cart->get_cart_contents_count()
            );
            ?>
            <span class="amount"><?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?></span> <span class="count"><?php echo esc_html($item_count_text); ?></span>
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
        <ul id="site-header-cart" class="site-header-cart">
            <li class="<?php echo esc_attr($class); ?>">
                <?php aqualuxe_woocommerce_cart_link(); ?>
            </li>
            <li>
                <?php
                $instance = array(
                    'title' => '',
                );

                the_widget('WC_Widget_Cart', $instance);
                ?>
            </li>
        </ul>
        <?php
    }
}

/**
 * Add WooCommerce specific classes to product entries
 */
function aqualuxe_woocommerce_product_classes($classes, $class, $post_id) {
    if (is_product()) {
        $classes[] = 'aqualuxe-single-product';
    }

    if (is_shop() || is_product_category() || is_product_tag()) {
        $classes[] = 'aqualuxe-product-item';
    }

    return $classes;
}
add_filter('post_class', 'aqualuxe_woocommerce_product_classes', 10, 3);

/**
 * Customize WooCommerce pagination
 */
function aqualuxe_woocommerce_pagination_args($args) {
    $args['prev_text'] = '&larr;';
    $args['next_text'] = '&rarr;';
    $args['end_size'] = 3;
    $args['mid_size'] = 3;
    return $args;
}
add_filter('woocommerce_pagination_args', 'aqualuxe_woocommerce_pagination_args');

/**
 * Add custom product tabs
 */
function aqualuxe_woocommerce_custom_product_tabs($tabs) {
    // Add shipping tab
    $tabs['shipping'] = array(
        'title'    => __('Shipping & Returns', 'aqualuxe'),
        'priority' => 30,
        'callback' => 'aqualuxe_woocommerce_shipping_tab_content',
    );

    // Add care instructions tab for specific product categories
    if (has_term(array('fish', 'aquatic-plants'), 'product_cat')) {
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
    // Get shipping content from theme options or use default
    $shipping_content = get_theme_mod('aqualuxe_shipping_tab_content', '');
    
    if (empty($shipping_content)) {
        $shipping_content = '<h3>' . __('Shipping Information', 'aqualuxe') . '</h3>';
        $shipping_content .= '<p>' . __('We ship our products worldwide with special care for live aquatic species. Shipping times and methods vary depending on your location and the products ordered.', 'aqualuxe') . '</p>';
        $shipping_content .= '<h3>' . __('Returns Policy', 'aqualuxe') . '</h3>';
        $shipping_content .= '<p>' . __('Please contact us within 24 hours of receiving your order if there are any issues. Due to the nature of live aquatic products, special return policies apply.', 'aqualuxe') . '</p>';
    }
    
    echo wp_kses_post($shipping_content);
}

/**
 * Care instructions tab content
 */
function aqualuxe_woocommerce_care_tab_content() {
    global $product;
    
    // Get product-specific care instructions from custom field
    $care_instructions = get_post_meta($product->get_id(), '_care_instructions', true);
    
    if (!empty($care_instructions)) {
        echo wp_kses_post($care_instructions);
    } else {
        // Default care instructions based on product category
        if (has_term('fish', 'product_cat')) {
            echo '<h3>' . __('Fish Care Guidelines', 'aqualuxe') . '</h3>';
            echo '<p>' . __('Proper care is essential for the health and longevity of your aquatic pets. Please research the specific needs of your fish species regarding water parameters, tank size, diet, and compatibility with other species.', 'aqualuxe') . '</p>';
        } elseif (has_term('aquatic-plants', 'product_cat')) {
            echo '<h3>' . __('Plant Care Guidelines', 'aqualuxe') . '</h3>';
            echo '<p>' . __('For optimal growth, consider the lighting, substrate, and nutrient requirements specific to this plant species. Regular pruning and maintenance will help ensure a healthy aquascape.', 'aqualuxe') . '</p>';
        }
    }
}

/**
 * Add quick view functionality
 */
function aqualuxe_add_quick_view_button() {
    global $product;
    
    echo '<a href="#" class="aqualuxe-quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</a>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_add_quick_view_button', 15);

/**
 * Add wishlist functionality
 */
function aqualuxe_add_wishlist_button() {
    global $product;
    
    echo '<a href="#" class="aqualuxe-wishlist-button" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</a>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_add_wishlist_button', 20);

/**
 * Modify number of products per row
 */
function aqualuxe_loop_columns() {
    return 3; // 3 products per row
}
add_filter('loop_shop_columns', 'aqualuxe_loop_columns');

/**
 * Modify number of products per page
 */
function aqualuxe_products_per_page() {
    return 12;
}
add_filter('loop_shop_per_page', 'aqualuxe_products_per_page');