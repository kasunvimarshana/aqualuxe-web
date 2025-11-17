<?php
/**
 * WooCommerce specific functions and configurations
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * WooCommerce setup function.
 *
 * @return void
 */
function aqualuxe_woocommerce_setup() {
    // Add theme support for WooCommerce
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 300,
        'single_image_width'    => 800,
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 1,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 1,
            'max_columns'     => 6,
        ),
    ));

    // Add support for WooCommerce features
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Register WooCommerce specific sidebars
    register_sidebar(array(
        'name'          => __('Shop Sidebar', 'aqualuxe'),
        'id'            => 'shop-sidebar',
        'description'   => __('Widgets in this area will be shown on shop pages.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => __('Product Sidebar', 'aqualuxe'),
        'id'            => 'product-sidebar',
        'description'   => __('Widgets in this area will be shown on product pages.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_setup');

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function aqualuxe_woocommerce_scripts() {
    // WooCommerce styles
    wp_enqueue_style('aqualuxe-woocommerce-style', aqualuxe_asset_path('css/woocommerce.css'), array(), null);

    // WooCommerce scripts
    wp_enqueue_script('aqualuxe-woocommerce', aqualuxe_asset_path('js/woocommerce.js'), array('jquery'), null, true);

    // Localize script with WooCommerce specific data
    wp_localize_script('aqualuxe-woocommerce', 'aqualuxeWooCommerce', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe-woocommerce-nonce'),
        'isCart' => is_cart(),
        'isCheckout' => is_checkout(),
        'isAccount' => is_account_page(),
        'currency' => get_woocommerce_currency_symbol(),
    ));
}
add_action('wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts');

/**
 * Disable the default WooCommerce stylesheet.
 *
 * @return void
 */
function aqualuxe_dequeue_woocommerce_styles() {
    // Remove the default WooCommerce styles
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
}
add_action('init', 'aqualuxe_dequeue_woocommerce_styles');

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function aqualuxe_related_products_args($args) {
    $defaults = array(
        'posts_per_page' => 4,
        'columns'        => 4,
    );

    $args = wp_parse_args($defaults, $args);

    return $args;
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_related_products_args');

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

function aqualuxe_woocommerce_wrapper_after() {
    ?>
        </main><!-- #main -->
    </div><!-- #primary -->
    <?php
}
add_action('woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after');

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * @return void
 */
function aqualuxe_woocommerce_cart_link() {
    ?>
    <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'aqualuxe'); ?>">
        <?php echo aqualuxe_get_icon('cart', array('class' => 'cart-icon')); ?>
        <span class="cart-count"><?php echo wp_kses_data(WC()->cart->get_cart_contents_count()); ?></span>
    </a>
    <?php
}

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
 * Modify the number of products displayed per page
 *
 * @return int Number of products.
 */
function aqualuxe_woocommerce_products_per_page() {
    return 12;
}
add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page');

/**
 * Modify the gallery thumbnail size
 *
 * @return array Image size.
 */
function aqualuxe_woocommerce_gallery_thumbnail_size($size) {
    return array(
        'width'  => 100,
        'height' => 100,
        'crop'   => 1,
    );
}
add_filter('woocommerce_get_image_size_gallery_thumbnail', 'aqualuxe_woocommerce_gallery_thumbnail_size');

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
 * Products per row.
 *
 * @return integer products per row.
 */
function aqualuxe_woocommerce_loop_columns() {
    return 4;
}
add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_columns');

/**
 * Add custom tabs to product page
 *
 * @param array $tabs Product tabs.
 * @return array Modified product tabs.
 */
function aqualuxe_woocommerce_product_tabs($tabs) {
    // Add custom tab
    $tabs['shipping_tab'] = array(
        'title'    => __('Shipping & Returns', 'aqualuxe'),
        'priority' => 30,
        'callback' => 'aqualuxe_woocommerce_shipping_tab_content',
    );

    // Add care guide tab for specific product categories
    global $product;
    if ($product) {
        $product_cats = wc_get_product_term_ids($product->get_id(), 'product_cat');
        $care_guide_cats = array(
            get_term_by('slug', 'fish', 'product_cat')->term_id,
            get_term_by('slug', 'plants', 'product_cat')->term_id,
        );

        if (array_intersect($product_cats, $care_guide_cats)) {
            $tabs['care_guide'] = array(
                'title'    => __('Care Guide', 'aqualuxe'),
                'priority' => 25,
                'callback' => 'aqualuxe_woocommerce_care_guide_tab_content',
            );
        }
    }

    return $tabs;
}
add_filter('woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs');

/**
 * Shipping tab content.
 */
function aqualuxe_woocommerce_shipping_tab_content() {
    // Get shipping content from theme options or use default
    $shipping_content = aqualuxe_get_option('product_shipping_content', '');

    if (empty($shipping_content)) {
        $shipping_content = '
        <h3>' . __('Shipping Information', 'aqualuxe') . '</h3>
        <p>' . __('We ship worldwide with specialized carriers experienced in transporting live aquatic species and delicate equipment.', 'aqualuxe') . '</p>
        
        <h4>' . __('Domestic Shipping', 'aqualuxe') . '</h4>
        <p>' . __('Orders are typically processed within 1-2 business days. Delivery times vary by location, typically 2-5 business days.', 'aqualuxe') . '</p>
        
        <h4>' . __('International Shipping', 'aqualuxe') . '</h4>
        <p>' . __('International orders require special handling and documentation. Please contact us before placing an international order.', 'aqualuxe') . '</p>
        
        <h3>' . __('Returns & Exchanges', 'aqualuxe') . '</h3>
        <p>' . __('We stand behind the quality of our products. If you\'re not satisfied with your purchase, please contact us within 14 days of receipt.', 'aqualuxe') . '</p>
        
        <h4>' . __('Live Arrivals Guarantee', 'aqualuxe') . '</h4>
        <p>' . __('We guarantee that fish and plants will arrive alive and in good condition. Please inspect and acclimate them properly upon arrival.', 'aqualuxe') . '</p>';
    }

    echo wp_kses_post($shipping_content);
}

/**
 * Care guide tab content.
 */
function aqualuxe_woocommerce_care_guide_tab_content() {
    global $product;
    
    // Get product-specific care guide if available
    $care_guide = get_post_meta($product->get_id(), '_care_guide', true);
    
    if (empty($care_guide)) {
        // Get care guide based on product category
        $product_cats = wc_get_product_term_ids($product->get_id(), 'product_cat');
        
        if (in_array(get_term_by('slug', 'fish', 'product_cat')->term_id, $product_cats)) {
            $care_guide = aqualuxe_get_option('fish_care_guide', '');
        } elseif (in_array(get_term_by('slug', 'plants', 'product_cat')->term_id, $product_cats)) {
            $care_guide = aqualuxe_get_option('plant_care_guide', '');
        }
        
        // Default care guide if still empty
        if (empty($care_guide)) {
            $care_guide = '
            <h3>' . __('Basic Care Instructions', 'aqualuxe') . '</h3>
            <p>' . __('Proper care is essential for the health and longevity of your aquatic life. Here are some general guidelines to follow:', 'aqualuxe') . '</p>
            
            <h4>' . __('Water Parameters', 'aqualuxe') . '</h4>
            <ul>
                <li>' . __('Temperature: 72-78°F (22-26°C)', 'aqualuxe') . '</li>
                <li>' . __('pH: 6.8-7.5', 'aqualuxe') . '</li>
                <li>' . __('Ammonia: 0 ppm', 'aqualuxe') . '</li>
                <li>' . __('Nitrite: 0 ppm', 'aqualuxe') . '</li>
                <li>' . __('Nitrate: <20 ppm', 'aqualuxe') . '</li>
            </ul>
            
            <h4>' . __('Maintenance', 'aqualuxe') . '</h4>
            <ul>
                <li>' . __('Perform regular water changes (15-20% weekly)', 'aqualuxe') . '</li>
                <li>' . __('Clean filter media monthly', 'aqualuxe') . '</li>
                <li>' . __('Test water parameters weekly', 'aqualuxe') . '</li>
                <li>' . __('Ensure proper lighting cycles (8-10 hours daily)', 'aqualuxe') . '</li>
            </ul>
            
            <h4>' . __('Feeding', 'aqualuxe') . '</h4>
            <p>' . __('Feed small amounts 1-2 times daily. Remove uneaten food after 2-3 minutes to prevent water quality issues.', 'aqualuxe') . '</p>';
        }
    }

    echo wp_kses_post($care_guide);
}

/**
 * Quick view functionality
 */
function aqualuxe_quick_view_button() {
    global $product;
    
    echo '<a href="#" class="aqualuxe-quick-view" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</a>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_quick_view_button', 15);

/**
 * AJAX quick view
 */
function aqualuxe_ajax_quick_view() {
    if (!isset($_POST['product_id']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce-nonce')) {
        wp_send_json_error('Invalid request');
        exit;
    }

    $product_id = absint($_POST['product_id']);
    $product = wc_get_product($product_id);

    if (!$product) {
        wp_send_json_error('Product not found');
        exit;
    }

    ob_start();
    ?>
    <div class="aqualuxe-quick-view-content">
        <div class="quick-view-image">
            <?php echo $product->get_image('medium'); ?>
        </div>
        <div class="quick-view-summary">
            <h2><?php echo esc_html($product->get_name()); ?></h2>
            <div class="price"><?php echo $product->get_price_html(); ?></div>
            <div class="description">
                <?php echo wp_kses_post($product->get_short_description()); ?>
            </div>
            <?php
            // Add to cart form
            woocommerce_template_single_add_to_cart();
            ?>
            <a href="<?php echo esc_url($product->get_permalink()); ?>" class="view-full-details">
                <?php esc_html_e('View Full Details', 'aqualuxe'); ?>
            </a>
        </div>
    </div>
    <?php
    $output = ob_get_clean();

    wp_send_json_success($output);
    exit;
}
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_ajax_quick_view');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_ajax_quick_view');

/**
 * Add wishlist functionality
 */
function aqualuxe_add_wishlist_button() {
    global $product;
    
    echo '<a href="#" class="aqualuxe-add-to-wishlist" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo aqualuxe_get_icon('heart', array('class' => 'wishlist-icon'));
    echo '</a>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_add_wishlist_button', 20);

/**
 * AJAX wishlist functionality
 */
function aqualuxe_ajax_wishlist() {
    if (!isset($_POST['product_id']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce-nonce')) {
        wp_send_json_error('Invalid request');
        exit;
    }

    $product_id = absint($_POST['product_id']);
    $user_id = get_current_user_id();
    
    // Guest users store wishlist in cookies
    if ($user_id === 0) {
        $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? json_decode(stripslashes($_COOKIE['aqualuxe_wishlist']), true) : array();
        
        if (in_array($product_id, $wishlist)) {
            $wishlist = array_diff($wishlist, array($product_id));
            $action = 'removed';
        } else {
            $wishlist[] = $product_id;
            $action = 'added';
        }
        
        setcookie('aqualuxe_wishlist', json_encode($wishlist), time() + (86400 * 30), '/'); // 30 days
    } else {
        // Logged in users store wishlist in user meta
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        
        if (!is_array($wishlist)) {
            $wishlist = array();
        }
        
        if (in_array($product_id, $wishlist)) {
            $wishlist = array_diff($wishlist, array($product_id));
            $action = 'removed';
        } else {
            $wishlist[] = $product_id;
            $action = 'added';
        }
        
        update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
    }
    
    wp_send_json_success(array(
        'action' => $action,
        'product_id' => $product_id,
        'wishlist_count' => count($wishlist),
    ));
    exit;
}
add_action('wp_ajax_aqualuxe_wishlist', 'aqualuxe_ajax_wishlist');
add_action('wp_ajax_nopriv_aqualuxe_wishlist', 'aqualuxe_ajax_wishlist');

/**
 * Add currency switcher
 */
function aqualuxe_currency_switcher() {
    // Only show if WooCommerce Multilingual is active
    if (!function_exists('wcml_is_multi_currency_on') || !wcml_is_multi_currency_on()) {
        return;
    }
    
    global $woocommerce_wpml;
    
    if (!is_object($woocommerce_wpml) || !isset($woocommerce_wpml->multi_currency)) {
        return;
    }
    
    $currencies = $woocommerce_wpml->multi_currency->get_currencies();
    $current_currency = $woocommerce_wpml->multi_currency->get_client_currency();
    
    if (empty($currencies)) {
        return;
    }
    
    ?>
    <div class="aqualuxe-currency-switcher">
        <span class="current-currency">
            <?php echo esc_html($current_currency); ?>
        </span>
        <ul class="currency-list">
            <?php foreach ($currencies as $code => $currency) : ?>
                <li>
                    <a href="<?php echo esc_url(add_query_arg('currency', $code)); ?>" class="<?php echo $code === $current_currency ? 'active' : ''; ?>">
                        <?php echo esc_html($code); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
}

/**
 * Add advanced product filtering
 */
function aqualuxe_advanced_product_filtering() {
    // Only show on shop and archive pages
    if (!is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }
    
    $min_price = isset($_GET['min_price']) ? esc_attr($_GET['min_price']) : '';
    $max_price = isset($_GET['max_price']) ? esc_attr($_GET['max_price']) : '';
    
    // Get min and max prices from products
    $price_range = aqualuxe_get_product_price_range();
    
    ?>
    <div class="aqualuxe-advanced-filters">
        <h3><?php esc_html_e('Filter Products', 'aqualuxe'); ?></h3>
        
        <form method="get" action="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">
            <?php
            // Keep query string parameters
            foreach ($_GET as $key => $value) {
                if (!in_array($key, array('min_price', 'max_price', 'filter_color', 'filter_size'))) {
                    echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '">';
                }
            }
            ?>
            
            <div class="filter-section price-filter">
                <h4><?php esc_html_e('Price Range', 'aqualuxe'); ?></h4>
                <div class="price-inputs">
                    <input type="number" name="min_price" placeholder="<?php esc_attr_e('Min', 'aqualuxe'); ?>" value="<?php echo esc_attr($min_price); ?>" min="<?php echo esc_attr($price_range['min']); ?>" max="<?php echo esc_attr($price_range['max']); ?>">
                    <span class="price-separator">-</span>
                    <input type="number" name="max_price" placeholder="<?php esc_attr_e('Max', 'aqualuxe'); ?>" value="<?php echo esc_attr($max_price); ?>" min="<?php echo esc_attr($price_range['min']); ?>" max="<?php echo esc_attr($price_range['max']); ?>">
                </div>
            </div>
            
            <?php
            // Add attribute filters
            $attributes = wc_get_attribute_taxonomies();
            
            if (!empty($attributes)) {
                foreach ($attributes as $attribute) {
                    $taxonomy = 'pa_' . $attribute->attribute_name;
                    $terms = get_terms(array(
                        'taxonomy' => $taxonomy,
                        'hide_empty' => true,
                    ));
                    
                    if (!empty($terms) && !is_wp_error($terms)) {
                        $filter_name = 'filter_' . $attribute->attribute_name;
                        $selected = isset($_GET[$filter_name]) ? explode(',', $_GET[$filter_name]) : array();
                        
                        ?>
                        <div class="filter-section attribute-filter">
                            <h4><?php echo esc_html($attribute->attribute_label); ?></h4>
                            <div class="attribute-options">
                                <?php foreach ($terms as $term) : ?>
                                    <label>
                                        <input type="checkbox" name="<?php echo esc_attr($filter_name); ?>[]" value="<?php echo esc_attr($term->slug); ?>" <?php checked(in_array($term->slug, $selected)); ?>>
                                        <?php echo esc_html($term->name); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
            ?>
            
            <button type="submit" class="apply-filters"><?php esc_html_e('Apply Filters', 'aqualuxe'); ?></button>
            <a href="<?php echo esc_url(remove_query_arg(array('min_price', 'max_price', 'filter_color', 'filter_size'))); ?>" class="reset-filters"><?php esc_html_e('Reset Filters', 'aqualuxe'); ?></a>
        </form>
    </div>
    <?php
}

/**
 * Get product price range
 *
 * @return array Min and max prices
 */
function aqualuxe_get_product_price_range() {
    global $wpdb;
    
    $prices = $wpdb->get_row("
        SELECT MIN(min_price) as min_price, MAX(max_price) as max_price
        FROM {$wpdb->wc_product_meta_lookup}
    ");
    
    return array(
        'min' => floor($prices->min_price),
        'max' => ceil($prices->max_price),
    );
}

/**
 * Add vendor information to product page
 */
function aqualuxe_vendor_info() {
    // Only show on product pages
    if (!is_product()) {
        return;
    }
    
    global $product;
    
    // Get vendor ID (author of the product)
    $vendor_id = get_post_field('post_author', $product->get_id());
    $vendor = get_userdata($vendor_id);
    
    if (!$vendor) {
        return;
    }
    
    // Get vendor meta
    $vendor_name = get_user_meta($vendor_id, 'vendor_name', true);
    $vendor_name = !empty($vendor_name) ? $vendor_name : $vendor->display_name;
    
    $vendor_logo = get_user_meta($vendor_id, 'vendor_logo', true);
    $vendor_description = get_user_meta($vendor_id, 'vendor_description', true);
    
    ?>
    <div class="aqualuxe-vendor-info">
        <h3><?php esc_html_e('Vendor Information', 'aqualuxe'); ?></h3>
        
        <div class="vendor-details">
            <?php if (!empty($vendor_logo)) : ?>
                <div class="vendor-logo">
                    <img src="<?php echo esc_url($vendor_logo); ?>" alt="<?php echo esc_attr($vendor_name); ?>">
                </div>
            <?php endif; ?>
            
            <div class="vendor-content">
                <h4 class="vendor-name"><?php echo esc_html($vendor_name); ?></h4>
                
                <?php if (!empty($vendor_description)) : ?>
                    <div class="vendor-description">
                        <?php echo wp_kses_post($vendor_description); ?>
                    </div>
                <?php endif; ?>
                
                <a href="<?php echo esc_url(get_author_posts_url($vendor_id)); ?>" class="vendor-products-link">
                    <?php esc_html_e('View all products from this vendor', 'aqualuxe'); ?>
                </a>
            </div>
        </div>
    </div>
    <?php
}
add_action('woocommerce_single_product_summary', 'aqualuxe_vendor_info', 45);

/**
 * Add estimated delivery date to product page
 */
function aqualuxe_estimated_delivery() {
    // Only show on product pages
    if (!is_product()) {
        return;
    }
    
    global $product;
    
    // Get product shipping class
    $shipping_class_id = $product->get_shipping_class_id();
    
    // Default delivery time
    $min_days = 3;
    $max_days = 7;
    
    // Adjust based on shipping class
    if ($shipping_class_id) {
        $shipping_class = get_term($shipping_class_id, 'product_shipping_class');
        
        if ($shipping_class && !is_wp_error($shipping_class)) {
            switch ($shipping_class->slug) {
                case 'express':
                    $min_days = 1;
                    $max_days = 2;
                    break;
                case 'standard':
                    $min_days = 3;
                    $max_days = 5;
                    break;
                case 'international':
                    $min_days = 7;
                    $max_days = 14;
                    break;
                case 'special-handling':
                    $min_days = 5;
                    $max_days = 10;
                    break;
            }
        }
    }
    
    // Calculate dates
    $min_date = date_i18n(get_option('date_format'), strtotime("+{$min_days} days"));
    $max_date = date_i18n(get_option('date_format'), strtotime("+{$max_days} days"));
    
    ?>
    <div class="aqualuxe-delivery-estimate">
        <p>
            <strong><?php esc_html_e('Estimated Delivery:', 'aqualuxe'); ?></strong>
            <?php echo sprintf(esc_html__('%1$s - %2$s', 'aqualuxe'), $min_date, $max_date); ?>
        </p>
    </div>
    <?php
}
add_action('woocommerce_single_product_summary', 'aqualuxe_estimated_delivery', 35);

/**
 * Add product availability status
 */
function aqualuxe_product_availability() {
    global $product;
    
    if (!$product->is_in_stock()) {
        echo '<div class="aqualuxe-availability out-of-stock">';
        echo aqualuxe_get_icon('x-circle', array('class' => 'availability-icon'));
        echo esc_html__('Out of Stock', 'aqualuxe');
        echo '</div>';
    } else {
        $stock_quantity = $product->get_stock_quantity();
        
        if ($stock_quantity && $stock_quantity <= 5) {
            echo '<div class="aqualuxe-availability low-stock">';
            echo aqualuxe_get_icon('alert-circle', array('class' => 'availability-icon'));
            echo sprintf(esc_html__('Low Stock: Only %d left', 'aqualuxe'), $stock_quantity);
            echo '</div>';
        } else {
            echo '<div class="aqualuxe-availability in-stock">';
            echo aqualuxe_get_icon('check-circle', array('class' => 'availability-icon'));
            echo esc_html__('In Stock', 'aqualuxe');
            echo '</div>';
        }
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_product_availability', 25);

/**
 * Add custom fields to checkout
 */
function aqualuxe_custom_checkout_fields($fields) {
    // Add delivery instructions field
    $fields['order']['order_delivery_instructions'] = array(
        'type'        => 'textarea',
        'label'       => __('Delivery Instructions', 'aqualuxe'),
        'placeholder' => __('Special instructions for delivery (e.g., gate code, preferred delivery time)', 'aqualuxe'),
        'required'    => false,
        'class'       => array('notes'),
        'clear'       => true,
    );
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'aqualuxe_custom_checkout_fields');

/**
 * Save custom checkout fields
 */
function aqualuxe_save_custom_checkout_fields($order_id) {
    if (!empty($_POST['order_delivery_instructions'])) {
        update_post_meta($order_id, 'order_delivery_instructions', sanitize_textarea_field($_POST['order_delivery_instructions']));
    }
}
add_action('woocommerce_checkout_update_order_meta', 'aqualuxe_save_custom_checkout_fields');

/**
 * Display custom fields in admin order page
 */
function aqualuxe_display_custom_fields_in_admin($order) {
    $order_id = $order->get_id();
    $delivery_instructions = get_post_meta($order_id, 'order_delivery_instructions', true);
    
    if ($delivery_instructions) {
        echo '<p><strong>' . __('Delivery Instructions', 'aqualuxe') . ':</strong> ' . esc_html($delivery_instructions) . '</p>';
    }
}
add_action('woocommerce_admin_order_data_after_shipping_address', 'aqualuxe_display_custom_fields_in_admin');

/**
 * Add custom order status for special handling
 */
function aqualuxe_register_custom_order_status() {
    register_post_status('wc-special-handling', array(
        'label'                     => __('Special Handling', 'aqualuxe'),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Special Handling <span class="count">(%s)</span>', 'Special Handling <span class="count">(%s)</span>', 'aqualuxe'),
    ));
}
add_action('init', 'aqualuxe_register_custom_order_status');

/**
 * Add custom order status to WooCommerce order statuses
 */
function aqualuxe_add_custom_order_status($order_statuses) {
    $new_order_statuses = array();
    
    // Add new status after processing
    foreach ($order_statuses as $key => $status) {
        $new_order_statuses[$key] = $status;
        
        if ('wc-processing' === $key) {
            $new_order_statuses['wc-special-handling'] = __('Special Handling', 'aqualuxe');
        }
    }
    
    return $new_order_statuses;
}
add_filter('wc_order_statuses', 'aqualuxe_add_custom_order_status');

/**
 * Add custom product type for live fish
 */
function aqualuxe_add_live_fish_product_type() {
    class WC_Product_Live_Fish extends WC_Product {
        public function __construct($product) {
            $this->product_type = 'live_fish';
            parent::__construct($product);
        }
        
        public function get_type() {
            return 'live_fish';
        }
        
        public function needs_special_handling() {
            return true;
        }
    }
}
add_action('init', 'aqualuxe_add_live_fish_product_type');

/**
 * Add live fish to product type dropdown
 */
function aqualuxe_add_live_fish_product_type_to_dropdown($types) {
    $types['live_fish'] = __('Live Fish', 'aqualuxe');
    return $types;
}
add_filter('product_type_selector', 'aqualuxe_add_live_fish_product_type_to_dropdown');

/**
 * Add live fish product type data tabs
 */
function aqualuxe_live_fish_product_tabs($tabs) {
    $tabs['live_fish'] = array(
        'label'    => __('Live Fish', 'aqualuxe'),
        'target'   => 'live_fish_product_data',
        'class'    => array('show_if_live_fish'),
        'priority' => 21,
    );
    
    return $tabs;
}
add_filter('woocommerce_product_data_tabs', 'aqualuxe_live_fish_product_tabs');

/**
 * Add live fish product type data panel
 */
function aqualuxe_live_fish_product_data_panel() {
    global $post;
    
    ?>
    <div id="live_fish_product_data" class="panel woocommerce_options_panel">
        <div class="options_group">
            <?php
            woocommerce_wp_text_input(array(
                'id'          => '_fish_scientific_name',
                'label'       => __('Scientific Name', 'aqualuxe'),
                'placeholder' => __('e.g., Carassius auratus', 'aqualuxe'),
                'desc_tip'    => true,
                'description' => __('Enter the scientific name of the fish.', 'aqualuxe'),
            ));
            
            woocommerce_wp_text_input(array(
                'id'          => '_fish_origin',
                'label'       => __('Origin', 'aqualuxe'),
                'placeholder' => __('e.g., Southeast Asia', 'aqualuxe'),
                'desc_tip'    => true,
                'description' => __('Enter the geographical origin of the fish.', 'aqualuxe'),
            ));
            
            woocommerce_wp_text_input(array(
                'id'          => '_fish_adult_size',
                'label'       => __('Adult Size', 'aqualuxe'),
                'placeholder' => __('e.g., 3-4 inches', 'aqualuxe'),
                'desc_tip'    => true,
                'description' => __('Enter the typical adult size of the fish.', 'aqualuxe'),
            ));
            
            woocommerce_wp_select(array(
                'id'          => '_fish_difficulty',
                'label'       => __('Care Difficulty', 'aqualuxe'),
                'options'     => array(
                    'beginner'     => __('Beginner', 'aqualuxe'),
                    'intermediate' => __('Intermediate', 'aqualuxe'),
                    'advanced'     => __('Advanced', 'aqualuxe'),
                    'expert'       => __('Expert', 'aqualuxe'),
                ),
                'desc_tip'    => true,
                'description' => __('Select the care difficulty level for this fish.', 'aqualuxe'),
            ));
            
            woocommerce_wp_textarea_input(array(
                'id'          => '_fish_care_guide',
                'label'       => __('Care Guide', 'aqualuxe'),
                'placeholder' => __('Enter detailed care instructions...', 'aqualuxe'),
                'desc_tip'    => true,
                'description' => __('Enter detailed care instructions for this fish.', 'aqualuxe'),
            ));
            
            woocommerce_wp_checkbox(array(
                'id'          => '_fish_requires_permit',
                'label'       => __('Requires Permit', 'aqualuxe'),
                'description' => __('Check if this fish requires special permits for ownership or import/export.', 'aqualuxe'),
            ));
            ?>
        </div>
    </div>
    <?php
}
add_action('woocommerce_product_data_panels', 'aqualuxe_live_fish_product_data_panel');

/**
 * Save live fish product type data
 */
function aqualuxe_save_live_fish_product_data($post_id) {
    // Scientific Name
    if (isset($_POST['_fish_scientific_name'])) {
        update_post_meta($post_id, '_fish_scientific_name', sanitize_text_field($_POST['_fish_scientific_name']));
    }
    
    // Origin
    if (isset($_POST['_fish_origin'])) {
        update_post_meta($post_id, '_fish_origin', sanitize_text_field($_POST['_fish_origin']));
    }
    
    // Adult Size
    if (isset($_POST['_fish_adult_size'])) {
        update_post_meta($post_id, '_fish_adult_size', sanitize_text_field($_POST['_fish_adult_size']));
    }
    
    // Care Difficulty
    if (isset($_POST['_fish_difficulty'])) {
        update_post_meta($post_id, '_fish_difficulty', sanitize_text_field($_POST['_fish_difficulty']));
    }
    
    // Care Guide
    if (isset($_POST['_fish_care_guide'])) {
        update_post_meta($post_id, '_fish_care_guide', wp_kses_post($_POST['_fish_care_guide']));
    }
    
    // Requires Permit
    $requires_permit = isset($_POST['_fish_requires_permit']) ? 'yes' : 'no';
    update_post_meta($post_id, '_fish_requires_permit', $requires_permit);
}
add_action('woocommerce_process_product_meta', 'aqualuxe_save_live_fish_product_data');

/**
 * Display live fish details on product page
 */
function aqualuxe_display_live_fish_details() {
    global $product;
    
    if ($product->get_type() !== 'live_fish') {
        return;
    }
    
    $scientific_name = get_post_meta($product->get_id(), '_fish_scientific_name', true);
    $origin = get_post_meta($product->get_id(), '_fish_origin', true);
    $adult_size = get_post_meta($product->get_id(), '_fish_adult_size', true);
    $difficulty = get_post_meta($product->get_id(), '_fish_difficulty', true);
    $requires_permit = get_post_meta($product->get_id(), '_fish_requires_permit', true);
    
    if (!$scientific_name && !$origin && !$adult_size && !$difficulty) {
        return;
    }
    
    ?>
    <div class="aqualuxe-fish-details">
        <h3><?php esc_html_e('Fish Details', 'aqualuxe'); ?></h3>
        
        <table class="fish-details-table">
            <?php if ($scientific_name) : ?>
                <tr>
                    <th><?php esc_html_e('Scientific Name:', 'aqualuxe'); ?></th>
                    <td><em><?php echo esc_html($scientific_name); ?></em></td>
                </tr>
            <?php endif; ?>
            
            <?php if ($origin) : ?>
                <tr>
                    <th><?php esc_html_e('Origin:', 'aqualuxe'); ?></th>
                    <td><?php echo esc_html($origin); ?></td>
                </tr>
            <?php endif; ?>
            
            <?php if ($adult_size) : ?>
                <tr>
                    <th><?php esc_html_e('Adult Size:', 'aqualuxe'); ?></th>
                    <td><?php echo esc_html($adult_size); ?></td>
                </tr>
            <?php endif; ?>
            
            <?php if ($difficulty) : ?>
                <tr>
                    <th><?php esc_html_e('Care Level:', 'aqualuxe'); ?></th>
                    <td>
                        <?php
                        switch ($difficulty) {
                            case 'beginner':
                                echo esc_html__('Beginner', 'aqualuxe');
                                break;
                            case 'intermediate':
                                echo esc_html__('Intermediate', 'aqualuxe');
                                break;
                            case 'advanced':
                                echo esc_html__('Advanced', 'aqualuxe');
                                break;
                            case 'expert':
                                echo esc_html__('Expert', 'aqualuxe');
                                break;
                            default:
                                echo esc_html($difficulty);
                        }
                        ?>
                    </td>
                </tr>
            <?php endif; ?>
            
            <?php if ($requires_permit === 'yes') : ?>
                <tr>
                    <th><?php esc_html_e('Special Requirements:', 'aqualuxe'); ?></th>
                    <td>
                        <span class="requires-permit">
                            <?php esc_html_e('Requires permit for ownership', 'aqualuxe'); ?>
                        </span>
                    </td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
    <?php
}
add_action('woocommerce_single_product_summary', 'aqualuxe_display_live_fish_details', 40);

/**
 * Add compatibility with WPML for multilingual support
 */
function aqualuxe_wpml_compatibility() {
    // Register strings for translation
    if (function_exists('icl_register_string')) {
        // Register theme options
        $options = get_theme_mods();
        
        if (!empty($options)) {
            foreach ($options as $option_key => $option_value) {
                if (is_string($option_value) && !empty($option_value)) {
                    icl_register_string('Theme Options', $option_key, $option_value);
                }
            }
        }
    }
}
add_action('after_setup_theme', 'aqualuxe_wpml_compatibility');

/**
 * Add compatibility with Polylang for multilingual support
 */
function aqualuxe_polylang_compatibility() {
    // Register strings for translation
    if (function_exists('pll_register_string')) {
        // Register theme options
        $options = get_theme_mods();
        
        if (!empty($options)) {
            foreach ($options as $option_key => $option_value) {
                if (is_string($option_value) && !empty($option_value)) {
                    pll_register_string($option_key, $option_value, 'Theme Options');
                }
            }
        }
    }
}
add_action('after_setup_theme', 'aqualuxe_polylang_compatibility');