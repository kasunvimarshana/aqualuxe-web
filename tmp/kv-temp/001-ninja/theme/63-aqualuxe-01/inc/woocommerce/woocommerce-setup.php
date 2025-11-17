<?php
/**
 * WooCommerce setup for AquaLuxe theme
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
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
    // Add theme support for WooCommerce
    add_theme_support('woocommerce');
    
    // Add theme support for WooCommerce product gallery features
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Declare WooCommerce support
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 400,
        'single_image_width' => 800,
        'product_grid' => array(
            'default_rows' => 3,
            'min_rows' => 1,
            'max_rows' => 8,
            'default_columns' => 4,
            'min_columns' => 1,
            'max_columns' => 6,
        ),
    ));
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_setup');

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function aqualuxe_woocommerce_scripts() {
    // Get the mix-manifest.json file
    $manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : [];

    // Helper function to get versioned asset URL
    $get_asset = function ($path) use ($manifest) {
        $versioned_path = isset($manifest[$path]) ? $manifest[$path] : $path;
        return get_template_directory_uri() . '/assets/dist' . str_replace('/assets/dist', '', $versioned_path);
    };

    // Enqueue WooCommerce styles
    wp_enqueue_style(
        'aqualuxe-woocommerce-style',
        $get_asset('/css/woocommerce.css'),
        array('aqualuxe-style'),
        AQUALUXE_VERSION
    );

    // Enqueue WooCommerce scripts
    wp_enqueue_script(
        'aqualuxe-woocommerce-script',
        $get_asset('/js/woocommerce.js'),
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );

    // Localize WooCommerce script
    wp_localize_script(
        'aqualuxe-woocommerce-script',
        'aqualuxeWooCommerce',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_woocommerce_nonce'),
            'isCart' => is_cart(),
            'isCheckout' => is_checkout(),
            'isAccount' => is_account_page(),
            'currency' => get_woocommerce_currency_symbol(),
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
        'posts_per_page' => 3,
        'columns' => 3,
    );

    $args = wp_parse_args($defaults, $args);

    return $args;
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args');

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
    return aqualuxe_get_option('aqualuxe_products_per_row', 3);
}
add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_columns');

/**
 * Products per page.
 *
 * @return integer products per page.
 */
function aqualuxe_woocommerce_products_per_page() {
    return aqualuxe_get_option('aqualuxe_products_per_page', 12);
}
add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page');

/**
 * Product gallery thumbnail size.
 */
function aqualuxe_woocommerce_gallery_thumbnail_size($size) {
    return array(
        'width' => 100,
        'height' => 100,
        'crop' => 1,
    );
}
add_filter('woocommerce_get_image_size_gallery_thumbnail', 'aqualuxe_woocommerce_gallery_thumbnail_size');

/**
 * Register WooCommerce sidebars.
 */
function aqualuxe_woocommerce_widgets_init() {
    register_sidebar(
        array(
            'name' => esc_html__('Shop Sidebar', 'aqualuxe'),
            'id' => 'shop-sidebar',
            'description' => esc_html__('Add widgets here to appear in shop sidebar.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        )
    );
}
add_action('widgets_init', 'aqualuxe_woocommerce_widgets_init');

/**
 * Remove default WooCommerce wrapper.
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

/**
 * Modify breadcrumb defaults.
 *
 * @param array $defaults Default breadcrumb args.
 * @return array
 */
function aqualuxe_woocommerce_breadcrumb_defaults($defaults) {
    $defaults['delimiter'] = '<span class="breadcrumb-separator">/</span>';
    $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">';
    $defaults['wrap_after'] = '</nav>';
    $defaults['before'] = '<span class="breadcrumb-item">';
    $defaults['after'] = '</span>';
    $defaults['home'] = esc_html__('Home', 'aqualuxe');

    return $defaults;
}
add_filter('woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumb_defaults');

/**
 * Add cart fragment for cart count update.
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
        <i class="fas fa-shopping-cart" aria-hidden="true"></i>
        <span class="count"><?php echo wp_kses_data(WC()->cart->get_cart_contents_count()); ?></span>
        <span class="amount"><?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?></span>
    </a>
    <?php
}

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
        <div class="widget_shopping_cart_content">
            <?php
            if (function_exists('woocommerce_mini_cart')) {
                woocommerce_mini_cart();
            }
            ?>
        </div>
    </div>
    <?php
}

/**
 * Ensure cart contents update when products are added to the cart via AJAX.
 *
 * @param array $fragments Fragments to refresh via AJAX.
 * @return array Fragments to refresh via AJAX.
 */
function aqualuxe_woocommerce_header_add_to_cart_fragment($fragments) {
    ob_start();
    aqualuxe_woocommerce_header_cart();
    $fragments['div.site-header-cart'] = ob_get_clean();

    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_header_add_to_cart_fragment');

/**
 * Change number of products that are displayed per page (shop page)
 */
function aqualuxe_woocommerce_shop_per_page($cols) {
    $cols = aqualuxe_get_option('aqualuxe_products_per_page', 12);
    return $cols;
}
add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_shop_per_page', 20);

/**
 * Change number or products per row
 */
if (!function_exists('aqualuxe_woocommerce_loop_columns')) {
    function aqualuxe_woocommerce_loop_columns() {
        return aqualuxe_get_option('aqualuxe_products_per_row', 3);
    }
}
add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_columns');

/**
 * Add 'View Cart' button after 'Add to Cart' success message
 */
function aqualuxe_woocommerce_add_to_cart_message_html($message) {
    $message .= sprintf('<a href="%s" class="button wc-forward">%s</a>', esc_url(wc_get_cart_url()), esc_html__('View Cart', 'aqualuxe'));
    return $message;
}
add_filter('wc_add_to_cart_message_html', 'aqualuxe_woocommerce_add_to_cart_message_html');

/**
 * Customize sale flash
 */
function aqualuxe_woocommerce_sale_flash($text, $post, $product) {
    return '<span class="onsale">' . esc_html__('Sale!', 'aqualuxe') . '</span>';
}
add_filter('woocommerce_sale_flash', 'aqualuxe_woocommerce_sale_flash', 10, 3);

/**
 * Add Quick View button to product loop
 */
function aqualuxe_woocommerce_quick_view_button() {
    global $product;

    if (!aqualuxe_get_option('aqualuxe_enable_quick_view', true)) {
        return;
    }

    echo '<div class="quick-view-button">';
    echo '<button class="button quick-view" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</button>';
    echo '</div>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_quick_view_button', 15);

/**
 * Add Wishlist button to product loop
 */
function aqualuxe_woocommerce_wishlist_button() {
    global $product;

    if (!aqualuxe_get_option('aqualuxe_enable_wishlist', true)) {
        return;
    }

    echo '<div class="wishlist-button">';
    echo '<button class="button wishlist" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</button>';
    echo '</div>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_wishlist_button', 20);

/**
 * Add Compare button to product loop
 */
function aqualuxe_woocommerce_compare_button() {
    global $product;

    if (!aqualuxe_get_option('aqualuxe_enable_compare', true)) {
        return;
    }

    echo '<div class="compare-button">';
    echo '<button class="button compare" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Compare', 'aqualuxe') . '</button>';
    echo '</div>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_compare_button', 25);

/**
 * Quick View AJAX handler
 */
function aqualuxe_woocommerce_quick_view_ajax() {
    if (!isset($_POST['product_id']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_woocommerce_nonce')) {
        wp_send_json_error('Invalid request');
    }

    $product_id = absint($_POST['product_id']);
    $product = wc_get_product($product_id);

    if (!$product) {
        wp_send_json_error('Product not found');
    }

    ob_start();
    ?>
    <div class="quick-view-content">
        <div class="quick-view-image">
            <?php echo $product->get_image('large'); ?>
        </div>
        <div class="quick-view-details">
            <h2 class="product-title"><?php echo esc_html($product->get_name()); ?></h2>
            <div class="product-price"><?php echo $product->get_price_html(); ?></div>
            <div class="product-rating">
                <?php
                if ($product->get_average_rating()) {
                    echo wc_get_rating_html($product->get_average_rating());
                } else {
                    echo '<div class="star-rating"></div><span class="rating-count">' . esc_html__('No reviews yet', 'aqualuxe') . '</span>';
                }
                ?>
            </div>
            <div class="product-description">
                <?php echo apply_filters('the_excerpt', $product->get_short_description()); ?>
            </div>
            <div class="product-add-to-cart">
                <?php
                echo sprintf(
                    '<a href="%s" data-quantity="1" class="%s" %s>%s</a>',
                    esc_url($product->add_to_cart_url()),
                    esc_attr(implode(' ', array_filter(array(
                        'button',
                        'product_type_' . $product->get_type(),
                        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                        $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
                    )))),
                    wc_implode_html_attributes(array(
                        'data-product_id' => $product->get_id(),
                        'data-product_sku' => $product->get_sku(),
                        'aria-label' => $product->add_to_cart_description(),
                        'rel' => 'nofollow',
                    )),
                    esc_html($product->add_to_cart_text())
                );
                ?>
            </div>
            <div class="product-meta">
                <?php if ($product->get_sku()) : ?>
                    <span class="sku_wrapper"><?php esc_html_e('SKU:', 'aqualuxe'); ?> <span class="sku"><?php echo esc_html($product->get_sku()); ?></span></span>
                <?php endif; ?>

                <?php echo wc_get_product_category_list($product->get_id(), ', ', '<span class="posted_in">' . _n('Category:', 'Categories:', count($product->get_category_ids()), 'aqualuxe') . ' ', '</span>'); ?>

                <?php echo wc_get_product_tag_list($product->get_id(), ', ', '<span class="tagged_as">' . _n('Tag:', 'Tags:', count($product->get_tag_ids()), 'aqualuxe') . ' ', '</span>'); ?>
            </div>
            <div class="product-actions">
                <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>" class="button view-details"><?php esc_html_e('View Details', 'aqualuxe'); ?></a>
            </div>
        </div>
    </div>
    <?php

    $output = ob_get_clean();

    wp_send_json_success($output);
}
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_woocommerce_quick_view_ajax');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_woocommerce_quick_view_ajax');

/**
 * Wishlist AJAX handler
 */
function aqualuxe_woocommerce_wishlist_ajax() {
    if (!isset($_POST['product_id']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_woocommerce_nonce')) {
        wp_send_json_error('Invalid request');
    }

    $product_id = absint($_POST['product_id']);
    $user_id = get_current_user_id();

    if (!$user_id) {
        wp_send_json_error('User not logged in');
    }

    $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);

    if (!$wishlist) {
        $wishlist = array();
    }

    if (in_array($product_id, $wishlist)) {
        $key = array_search($product_id, $wishlist);
        unset($wishlist[$key]);
        $message = __('Product removed from wishlist', 'aqualuxe');
        $status = 'removed';
    } else {
        $wishlist[] = $product_id;
        $message = __('Product added to wishlist', 'aqualuxe');
        $status = 'added';
    }

    update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);

    wp_send_json_success(array(
        'message' => $message,
        'status' => $status,
        'count' => count($wishlist),
    ));
}
add_action('wp_ajax_aqualuxe_wishlist', 'aqualuxe_woocommerce_wishlist_ajax');
add_action('wp_ajax_nopriv_aqualuxe_wishlist', 'aqualuxe_woocommerce_wishlist_ajax');

/**
 * Compare AJAX handler
 */
function aqualuxe_woocommerce_compare_ajax() {
    if (!isset($_POST['product_id']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_woocommerce_nonce')) {
        wp_send_json_error('Invalid request');
    }

    $product_id = absint($_POST['product_id']);
    
    // Get compare list from cookie
    $compare_list = isset($_COOKIE['aqualuxe_compare_list']) ? json_decode(stripslashes($_COOKIE['aqualuxe_compare_list']), true) : array();
    
    if (!is_array($compare_list)) {
        $compare_list = array();
    }

    if (in_array($product_id, $compare_list)) {
        $key = array_search($product_id, $compare_list);
        unset($compare_list[$key]);
        $message = __('Product removed from compare list', 'aqualuxe');
        $status = 'removed';
    } else {
        // Limit to 4 products
        if (count($compare_list) >= 4) {
            array_shift($compare_list);
        }
        
        $compare_list[] = $product_id;
        $message = __('Product added to compare list', 'aqualuxe');
        $status = 'added';
    }

    // Save compare list to cookie
    setcookie('aqualuxe_compare_list', json_encode($compare_list), time() + (86400 * 30), '/'); // 30 days

    wp_send_json_success(array(
        'message' => $message,
        'status' => $status,
        'count' => count($compare_list),
    ));
}
add_action('wp_ajax_aqualuxe_compare', 'aqualuxe_woocommerce_compare_ajax');
add_action('wp_ajax_nopriv_aqualuxe_compare', 'aqualuxe_woocommerce_compare_ajax');

/**
 * Add WooCommerce specific settings to the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_woocommerce_customize_register($wp_customize) {
    // Add WooCommerce section
    $wp_customize->add_section(
        'aqualuxe_woocommerce',
        array(
            'title' => __('WooCommerce', 'aqualuxe'),
            'priority' => 130,
            'panel' => 'aqualuxe_theme_options',
        )
    );

    // Add WooCommerce settings
    $wp_customize->add_setting(
        'aqualuxe_products_per_row',
        array(
            'default' => '3',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_products_per_row',
        array(
            'label' => __('Products per row', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'select',
            'choices' => array(
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
            ),
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_products_per_page',
        array(
            'default' => '12',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_products_per_page',
        array(
            'label' => __('Products per page', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 1,
                'max' => 100,
                'step' => 1,
            ),
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_enable_quick_view',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_quick_view',
        array(
            'label' => __('Enable Quick View', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_enable_wishlist',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_wishlist',
        array(
            'label' => __('Enable Wishlist', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_enable_compare',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_compare',
        array(
            'label' => __('Enable Compare', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_shop_layout',
        array(
            'default' => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_shop_layout',
        array(
            'label' => __('Shop Layout', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'select',
            'choices' => array(
                'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
                'left-sidebar' => __('Left Sidebar', 'aqualuxe'),
                'no-sidebar' => __('No Sidebar', 'aqualuxe'),
            ),
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_product_layout',
        array(
            'default' => 'no-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_layout',
        array(
            'label' => __('Product Layout', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'select',
            'choices' => array(
                'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
                'left-sidebar' => __('Left Sidebar', 'aqualuxe'),
                'no-sidebar' => __('No Sidebar', 'aqualuxe'),
            ),
        )
    );
}
add_action('customize_register', 'aqualuxe_woocommerce_customize_register');