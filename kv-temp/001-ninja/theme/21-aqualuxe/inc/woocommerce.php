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
    wp_enqueue_style('aqualuxe-woocommerce-style', get_template_directory_uri() . '/assets/css/woocommerce.css', array(), AQUALUXE_VERSION);

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
    wp_enqueue_script('aqualuxe-woocommerce-script', get_template_directory_uri() . '/assets/js/woocommerce.js', array('jquery'), AQUALUXE_VERSION, true);
    
    // Localize script for AJAX functionality
    wp_localize_script('aqualuxe-woocommerce-script', 'aqualuxe_wc', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('aqualuxe_wc_nonce'),
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
            <div class="container mx-auto px-4 py-8">
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
            </div><!-- .container -->
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
        ?>
        <a class="cart-contents relative text-white hover:text-teal-200 transition-colors" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'aqualuxe'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="screen-reader-text"><?php esc_html_e('Cart', 'aqualuxe'); ?></span>
            <?php if (WC()->cart->get_cart_contents_count() > 0) : ?>
                <span class="cart-count absolute -top-2 -right-2 bg-teal-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                    <?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
                </span>
            <?php endif; ?>
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
        <div class="site-header-cart relative group">
            <div class="<?php echo esc_attr($class); ?>">
                <?php aqualuxe_woocommerce_cart_link(); ?>
            </div>
            <div class="cart-dropdown hidden group-hover:block absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-md shadow-lg z-50">
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
 * Customize product loop items
 */

// Change number of products per row
function aqualuxe_loop_columns() {
    return get_theme_mod('aqualuxe_shop_columns', 3);
}
add_filter('loop_shop_columns', 'aqualuxe_loop_columns');

// Change number of products per page
function aqualuxe_products_per_page() {
    return get_theme_mod('aqualuxe_products_per_page', 12);
}
add_filter('loop_shop_per_page', 'aqualuxe_products_per_page');

// Add custom classes to product loop items
function aqualuxe_product_loop_classes() {
    return 'bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-shadow hover:shadow-lg';
}
add_filter('woocommerce_product_loop_start', function($html) {
    $columns = wc_get_loop_prop('columns');
    $html = '<ul class="products grid grid-cols-1 md:grid-cols-2 lg:grid-cols-' . esc_attr($columns) . ' gap-6">';
    return $html;
});

// Modify product loop item
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

// Add custom product loop item structure
add_action('woocommerce_before_shop_loop_item', 'aqualuxe_template_loop_product_link_open', 10);
function aqualuxe_template_loop_product_link_open() {
    echo '<li class="' . esc_attr(aqualuxe_product_loop_classes()) . '">';
    echo '<a href="' . esc_url(get_the_permalink()) . '" class="block">';
}

add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_template_loop_product_thumbnail', 10);
function aqualuxe_template_loop_product_thumbnail() {
    echo '<div class="product-thumbnail relative overflow-hidden">';
    
    // Sale flash
    if (wc_get_loop_prop('show_sale_flash') && get_post_meta(get_the_ID(), '_sale_price', true)) {
        echo '<span class="onsale absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded z-10">' . esc_html__('Sale!', 'aqualuxe') . '</span>';
    }
    
    // Featured badge
    if (get_post_meta(get_the_ID(), '_featured', true) === 'yes') {
        echo '<span class="featured absolute top-2 right-2 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded z-10">' . esc_html__('Featured', 'aqualuxe') . '</span>';
    }
    
    // Product image
    echo woocommerce_get_product_thumbnail('woocommerce_thumbnail');
    
    // Quick view button
    if (get_theme_mod('aqualuxe_quick_view', true)) {
        echo '<div class="quick-view-wrapper absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">';
        echo '<button type="button" class="quick-view-button bg-white text-gray-900 hover:bg-teal-500 hover:text-white transition-colors rounded-full p-2" data-product-id="' . esc_attr(get_the_ID()) . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>';
        echo '<span class="screen-reader-text">' . esc_html__('Quick View', 'aqualuxe') . '</span>';
        echo '</button>';
        echo '</div>';
    }
    
    echo '</div>'; // .product-thumbnail
}

add_action('woocommerce_shop_loop_item_title', 'aqualuxe_template_loop_product_title', 10);
function aqualuxe_template_loop_product_title() {
    echo '<div class="product-details p-4">';
    echo '<h2 class="woocommerce-loop-product__title text-lg font-medium text-gray-900 dark:text-white mb-2">' . get_the_title() . '</h2>';
}

add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_template_loop_rating', 5);
function aqualuxe_template_loop_rating() {
    global $product;
    
    if (wc_review_ratings_enabled()) {
        $rating_count = $product->get_rating_count();
        $review_count = $product->get_review_count();
        $average      = $product->get_average_rating();
        
        if ($rating_count > 0) {
            echo '<div class="star-rating-wrapper flex items-center mb-2">';
            echo wc_get_rating_html($average, $rating_count);
            echo '<span class="rating-count text-xs text-gray-600 dark:text-gray-400 ml-2">(' . esc_html($review_count) . ')</span>';
            echo '</div>';
        } else {
            echo '<div class="star-rating-wrapper mb-2">';
            echo '<div class="star-rating"></div>';
            echo '</div>';
        }
    }
}

add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_template_loop_price', 10);
function aqualuxe_template_loop_price() {
    global $product;
    
    echo '<div class="price-wrapper mb-3">';
    echo '<span class="price">' . $product->get_price_html() . '</span>';
    echo '</div>';
}

add_action('woocommerce_after_shop_loop_item', 'aqualuxe_template_loop_add_to_cart', 10);
function aqualuxe_template_loop_add_to_cart() {
    global $product;
    
    echo '<div class="add-to-cart-wrapper flex justify-between items-center">';
    
    // Add to cart button
    echo '<div class="add-to-cart">';
    woocommerce_template_loop_add_to_cart(array(
        'class' => 'button bg-teal-600 hover:bg-teal-700 text-white text-sm py-1 px-3 rounded transition-colors',
    ));
    echo '</div>';
    
    // Wishlist button
    echo '<div class="wishlist">';
    echo '<button type="button" class="add-to-wishlist text-gray-500 hover:text-red-500 transition-colors" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>';
    echo '<span class="screen-reader-text">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</span>';
    echo '</button>';
    echo '</div>';
    
    echo '</div>'; // .add-to-cart-wrapper
    echo '</div>'; // .product-details
    echo '</a>';
    echo '</li>';
}

/**
 * Quick View functionality
 */
function aqualuxe_quick_view_ajax() {
    if (!isset($_POST['product_id']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_wc_nonce')) {
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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="quick-view-images">
                <?php
                $attachment_ids = $product->get_gallery_image_ids();
                $featured_image = get_the_post_thumbnail_url($product_id, 'medium_large');
                
                if ($featured_image) {
                    echo '<img src="' . esc_url($featured_image) . '" alt="' . esc_attr($product->get_name()) . '" class="w-full h-auto rounded-lg">';
                }
                
                if ($attachment_ids) {
                    echo '<div class="quick-view-thumbnails grid grid-cols-4 gap-2 mt-4">';
                    foreach ($attachment_ids as $attachment_id) {
                        $thumbnail = wp_get_attachment_image_url($attachment_id, 'thumbnail');
                        echo '<img src="' . esc_url($thumbnail) . '" alt="" class="w-full h-auto rounded cursor-pointer">';
                    }
                    echo '</div>';
                }
                ?>
            </div>
            
            <div class="quick-view-details">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2"><?php echo esc_html($product->get_name()); ?></h2>
                
                <?php if (wc_review_ratings_enabled() && $product->get_rating_count() > 0) : ?>
                <div class="star-rating-wrapper flex items-center mb-4">
                    <?php echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count()); ?>
                    <span class="rating-count text-sm text-gray-600 dark:text-gray-400 ml-2">(<?php echo esc_html($product->get_review_count()); ?>)</span>
                </div>
                <?php endif; ?>
                
                <div class="price-wrapper mb-4">
                    <span class="price text-xl font-bold text-teal-600 dark:text-teal-400"><?php echo $product->get_price_html(); ?></span>
                </div>
                
                <div class="description-wrapper mb-6">
                    <?php echo wp_kses_post($product->get_short_description()); ?>
                </div>
                
                <div class="add-to-cart-wrapper">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
                
                <div class="meta-wrapper mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <?php if ($product->get_sku()) : ?>
                    <div class="sku-wrapper mb-2 text-sm">
                        <span class="font-medium"><?php esc_html_e('SKU:', 'aqualuxe'); ?></span>
                        <span><?php echo esc_html($product->get_sku()); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($product->get_category_ids()) : ?>
                    <div class="categories-wrapper mb-2 text-sm">
                        <span class="font-medium"><?php esc_html_e('Categories:', 'aqualuxe'); ?></span>
                        <span><?php echo wc_get_product_category_list($product_id); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($product->get_tag_ids()) : ?>
                    <div class="tags-wrapper text-sm">
                        <span class="font-medium"><?php esc_html_e('Tags:', 'aqualuxe'); ?></span>
                        <span><?php echo wc_get_product_tag_list($product_id); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="actions-wrapper mt-6 flex items-center space-x-4">
                    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="view-details-link text-teal-600 dark:text-teal-400 hover:underline">
                        <?php esc_html_e('View Full Details', 'aqualuxe'); ?>
                    </a>
                    
                    <button type="button" class="add-to-wishlist text-gray-500 hover:text-red-500 transition-colors flex items-center" data-product-id="<?php echo esc_attr($product_id); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <?php esc_html_e('Add to Wishlist', 'aqualuxe'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php
    
    $output = ob_get_clean();
    wp_send_json_success($output);
}
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax');

/**
 * Add Quick View modal container to footer
 */
function aqualuxe_quick_view_container() {
    if (!is_product() && (is_shop() || is_product_category() || is_product_tag())) {
        ?>
        <div id="quick-view-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
            <div class="quick-view-container bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto p-6 relative">
                <button type="button" id="quick-view-close" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span class="screen-reader-text"><?php esc_html_e('Close', 'aqualuxe'); ?></span>
                </button>
                
                <div id="quick-view-content-wrapper" class="quick-view-content-wrapper">
                    <div class="quick-view-loading flex items-center justify-center py-12">
                        <svg class="animate-spin h-8 w-8 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('wp_footer', 'aqualuxe_quick_view_container');

/**
 * Wishlist functionality
 */
function aqualuxe_add_to_wishlist_ajax() {
    if (!isset($_POST['product_id']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_wc_nonce')) {
        wp_send_json_error('Invalid request');
    }
    
    $product_id = absint($_POST['product_id']);
    $user_id = get_current_user_id();
    
    if ($user_id === 0) {
        // For non-logged in users, use cookies
        $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? json_decode(stripslashes($_COOKIE['aqualuxe_wishlist']), true) : array();
        
        if (in_array($product_id, $wishlist)) {
            // Remove from wishlist
            $wishlist = array_diff($wishlist, array($product_id));
            $message = __('Product removed from wishlist', 'aqualuxe');
            $status = 'removed';
        } else {
            // Add to wishlist
            $wishlist[] = $product_id;
            $message = __('Product added to wishlist', 'aqualuxe');
            $status = 'added';
        }
        
        setcookie('aqualuxe_wishlist', json_encode($wishlist), time() + (86400 * 30), '/'); // 30 days
    } else {
        // For logged in users, use user meta
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        
        if (!is_array($wishlist)) {
            $wishlist = array();
        }
        
        if (in_array($product_id, $wishlist)) {
            // Remove from wishlist
            $wishlist = array_diff($wishlist, array($product_id));
            $message = __('Product removed from wishlist', 'aqualuxe');
            $status = 'removed';
        } else {
            // Add to wishlist
            $wishlist[] = $product_id;
            $message = __('Product added to wishlist', 'aqualuxe');
            $status = 'added';
        }
        
        update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
    }
    
    wp_send_json_success(array(
        'message' => $message,
        'status' => $status,
    ));
}
add_action('wp_ajax_aqualuxe_add_to_wishlist', 'aqualuxe_add_to_wishlist_ajax');
add_action('wp_ajax_nopriv_aqualuxe_add_to_wishlist', 'aqualuxe_add_to_wishlist_ajax');

/**
 * Get wishlist items
 */
function aqualuxe_get_wishlist_items() {
    $user_id = get_current_user_id();
    
    if ($user_id === 0) {
        // For non-logged in users, use cookies
        return isset($_COOKIE['aqualuxe_wishlist']) ? json_decode(stripslashes($_COOKIE['aqualuxe_wishlist']), true) : array();
    } else {
        // For logged in users, use user meta
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        return is_array($wishlist) ? $wishlist : array();
    }
}

/**
 * Check if product is in wishlist
 */
function aqualuxe_is_product_in_wishlist($product_id) {
    $wishlist = aqualuxe_get_wishlist_items();
    return in_array($product_id, $wishlist);
}

/**
 * Create wishlist page on theme activation
 */
function aqualuxe_create_wishlist_page() {
    // Check if wishlist page exists
    $wishlist_page_id = get_option('aqualuxe_wishlist_page');
    
    if (!$wishlist_page_id) {
        $wishlist_page = array(
            'post_title'    => __('Wishlist', 'aqualuxe'),
            'post_content'  => '[aqualuxe_wishlist]',
            'post_status'   => 'publish',
            'post_type'     => 'page',
        );
        
        $wishlist_page_id = wp_insert_post($wishlist_page);
        
        if (!is_wp_error($wishlist_page_id)) {
            update_option('aqualuxe_wishlist_page', $wishlist_page_id);
        }
    }
}
add_action('after_switch_theme', 'aqualuxe_create_wishlist_page');

/**
 * Wishlist shortcode
 */
function aqualuxe_wishlist_shortcode() {
    $wishlist = aqualuxe_get_wishlist_items();
    
    ob_start();
    
    if (empty($wishlist)) {
        ?>
        <div class="wishlist-empty bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2"><?php esc_html_e('Your wishlist is empty', 'aqualuxe'); ?></h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6"><?php esc_html_e('Add items to your wishlist by clicking the heart icon on products.', 'aqualuxe'); ?></p>
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="inline-block bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-6 rounded transition-colors">
                <?php esc_html_e('Browse Products', 'aqualuxe'); ?>
            </a>
        </div>
        <?php
    } else {
        ?>
        <div class="wishlist-content">
            <table class="wishlist-table w-full bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-900 dark:text-white"><?php esc_html_e('Product', 'aqualuxe'); ?></th>
                        <th class="px-4 py-3 text-left text-gray-900 dark:text-white"><?php esc_html_e('Price', 'aqualuxe'); ?></th>
                        <th class="px-4 py-3 text-left text-gray-900 dark:text-white"><?php esc_html_e('Stock Status', 'aqualuxe'); ?></th>
                        <th class="px-4 py-3 text-left text-gray-900 dark:text-white"><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($wishlist as $product_id) : 
                        $product = wc_get_product($product_id);
                        
                        if (!$product) {
                            continue;
                        }
                    ?>
                        <tr class="border-t border-gray-200 dark:border-gray-700">
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="block w-16 h-16 mr-4">
                                        <?php echo $product->get_image('thumbnail', array('class' => 'w-full h-full object-cover rounded')); ?>
                                    </a>
                                    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="text-gray-900 dark:text-white hover:text-teal-600 dark:hover:text-teal-400 transition-colors">
                                        <?php echo esc_html($product->get_name()); ?>
                                    </a>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-gray-900 dark:text-white">
                                <?php echo $product->get_price_html(); ?>
                            </td>
                            <td class="px-4 py-4">
                                <?php if ($product->is_in_stock()) : ?>
                                    <span class="inline-block bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">
                                        <?php esc_html_e('In Stock', 'aqualuxe'); ?>
                                    </span>
                                <?php else : ?>
                                    <span class="inline-block bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded">
                                        <?php esc_html_e('Out of Stock', 'aqualuxe'); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-2">
                                    <?php if ($product->is_in_stock()) : ?>
                                        <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="bg-teal-600 hover:bg-teal-700 text-white text-sm py-1 px-3 rounded transition-colors">
                                            <?php esc_html_e('Add to Cart', 'aqualuxe'); ?>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <button type="button" class="remove-from-wishlist text-gray-500 hover:text-red-500 transition-colors" data-product-id="<?php echo esc_attr($product_id); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span class="screen-reader-text"><?php esc_html_e('Remove', 'aqualuxe'); ?></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_wishlist', 'aqualuxe_wishlist_shortcode');

/**
 * Customize single product page
 */
// Reorder single product elements
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

add_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 15);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
add_action('woocommerce_single_product_summary', 'aqualuxe_single_product_wishlist_button', 35);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

// Add wishlist button to single product
function aqualuxe_single_product_wishlist_button() {
    global $product;
    
    $product_id = $product->get_id();
    $is_in_wishlist = aqualuxe_is_product_in_wishlist($product_id);
    $button_class = $is_in_wishlist ? 'text-red-500' : 'text-gray-500';
    
    ?>
    <div class="wishlist-button-wrapper mt-4 mb-4">
        <button type="button" class="add-to-wishlist <?php echo esc_attr($button_class); ?> hover:text-red-500 transition-colors flex items-center" data-product-id="<?php echo esc_attr($product_id); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="<?php echo $is_in_wishlist ? 'currentColor' : 'none'; ?>" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span><?php echo $is_in_wishlist ? esc_html__('Remove from Wishlist', 'aqualuxe') : esc_html__('Add to Wishlist', 'aqualuxe'); ?></span>
        </button>
    </div>
    <?php
}

// Customize product tabs
add_filter('woocommerce_product_tabs', 'aqualuxe_product_tabs', 98);
function aqualuxe_product_tabs($tabs) {
    global $product;
    
    // Rename the description tab
    if (isset($tabs['description'])) {
        $tabs['description']['title'] = __('Product Details', 'aqualuxe');
    }
    
    // Add a custom tab for shipping information
    $tabs['shipping'] = array(
        'title'    => __('Shipping & Returns', 'aqualuxe'),
        'priority' => 30,
        'callback' => 'aqualuxe_shipping_tab_content',
    );
    
    // Add a custom tab for care instructions if it's a fish or plant product
    $product_cats = wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'slugs'));
    
    if (in_array('fish', $product_cats) || in_array('plants', $product_cats)) {
        $tabs['care'] = array(
            'title'    => __('Care Instructions', 'aqualuxe'),
            'priority' => 20,
            'callback' => 'aqualuxe_care_tab_content',
        );
    }
    
    return $tabs;
}

// Shipping tab content
function aqualuxe_shipping_tab_content() {
    // Get shipping content from theme options or use default
    $shipping_content = get_theme_mod('aqualuxe_shipping_content', '');
    
    if (empty($shipping_content)) {
        ?>
        <h3><?php esc_html_e('Shipping Information', 'aqualuxe'); ?></h3>
        <p><?php esc_html_e('We ship our products worldwide using specialized shipping methods to ensure the safety and health of all aquatic life.', 'aqualuxe'); ?></p>
        
        <h4><?php esc_html_e('Domestic Shipping', 'aqualuxe'); ?></h4>
        <ul>
            <li><?php esc_html_e('Standard Shipping: 2-3 business days', 'aqualuxe'); ?></li>
            <li><?php esc_html_e('Express Shipping: 1-2 business days', 'aqualuxe'); ?></li>
            <li><?php esc_html_e('Orders over $100 qualify for free standard shipping', 'aqualuxe'); ?></li>
        </ul>
        
        <h4><?php esc_html_e('International Shipping', 'aqualuxe'); ?></h4>
        <ul>
            <li><?php esc_html_e('Standard International: 5-10 business days', 'aqualuxe'); ?></li>
            <li><?php esc_html_e('Express International: 3-5 business days', 'aqualuxe'); ?></li>
            <li><?php esc_html_e('Please note that import duties and taxes may apply', 'aqualuxe'); ?></li>
        </ul>
        
        <h3><?php esc_html_e('Returns Policy', 'aqualuxe'); ?></h3>
        <p><?php esc_html_e('Due to the nature of live aquatic products, we have a specialized return policy:', 'aqualuxe'); ?></p>
        
        <ul>
            <li><?php esc_html_e('Equipment and dry goods: 30-day return policy', 'aqualuxe'); ?></li>
            <li><?php esc_html_e('Live fish and plants: 48-hour DOA (Dead on Arrival) guarantee', 'aqualuxe'); ?></li>
            <li><?php esc_html_e('Photo evidence required for DOA claims', 'aqualuxe'); ?></li>
            <li><?php esc_html_e('Contact our customer service team for return authorization', 'aqualuxe'); ?></li>
        </ul>
        <?php
    } else {
        echo wp_kses_post($shipping_content);
    }
}

// Care instructions tab content
function aqualuxe_care_tab_content() {
    global $product;
    
    // Get care instructions from product meta
    $care_instructions = get_post_meta($product->get_id(), '_care_instructions', true);
    
    if (!empty($care_instructions)) {
        echo wp_kses_post($care_instructions);
    } else {
        // Default care instructions based on product category
        $product_cats = wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'slugs'));
        
        if (in_array('fish', $product_cats)) {
            ?>
            <h3><?php esc_html_e('Fish Care Instructions', 'aqualuxe'); ?></h3>
            <p><?php esc_html_e('Proper care is essential for the health and longevity of your aquatic pets.', 'aqualuxe'); ?></p>
            
            <h4><?php esc_html_e('Water Parameters', 'aqualuxe'); ?></h4>
            <ul>
                <li><?php esc_html_e('Temperature: 72-78°F (22-26°C)', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('pH: 6.8-7.5', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('Ammonia: 0 ppm', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('Nitrite: 0 ppm', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('Nitrate: <20 ppm', 'aqualuxe'); ?></li>
            </ul>
            
            <h4><?php esc_html_e('Feeding', 'aqualuxe'); ?></h4>
            <ul>
                <li><?php esc_html_e('Feed 1-2 times daily', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('Provide only what can be consumed in 2-3 minutes', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('Vary diet with high-quality flakes, pellets, and occasional live or frozen foods', 'aqualuxe'); ?></li>
            </ul>
            
            <h4><?php esc_html_e('Maintenance', 'aqualuxe'); ?></h4>
            <ul>
                <li><?php esc_html_e('Perform 25-30% water changes weekly', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('Clean filter media monthly (in tank water, not tap water)', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('Test water parameters regularly', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('Treat tap water with conditioner before adding to tank', 'aqualuxe'); ?></li>
            </ul>
            <?php
        } elseif (in_array('plants', $product_cats)) {
            ?>
            <h3><?php esc_html_e('Aquatic Plant Care Instructions', 'aqualuxe'); ?></h3>
            <p><?php esc_html_e('Proper care will ensure your aquatic plants thrive and beautify your aquarium.', 'aqualuxe'); ?></p>
            
            <h4><?php esc_html_e('Lighting', 'aqualuxe'); ?></h4>
            <ul>
                <li><?php esc_html_e('Provide 8-10 hours of light daily', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('Medium to high light intensity recommended', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('LED lights with plant-specific spectrum are ideal', 'aqualuxe'); ?></li>
            </ul>
            
            <h4><?php esc_html_e('Substrate & Nutrients', 'aqualuxe'); ?></h4>
            <ul>
                <li><?php esc_html_e('Use nutrient-rich substrate or root tabs', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('Dose liquid fertilizers weekly', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('Consider CO2 supplementation for optimal growth', 'aqualuxe'); ?></li>
            </ul>
            
            <h4><?php esc_html_e('Maintenance', 'aqualuxe'); ?></h4>
            <ul>
                <li><?php esc_html_e('Trim regularly to promote bushier growth', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('Remove dead or yellowing leaves', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('Clean plant leaves gently to remove algae', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('Perform regular water changes to replenish minerals', 'aqualuxe'); ?></li>
            </ul>
            <?php
        }
    }
}

/**
 * Add custom fields to product data tabs
 */
function aqualuxe_product_data_tabs($tabs) {
    $tabs['care_instructions'] = array(
        'label'    => __('Care Instructions', 'aqualuxe'),
        'target'   => 'care_instructions_product_data',
        'class'    => array(),
        'priority' => 21,
    );
    
    return $tabs;
}
add_filter('woocommerce_product_data_tabs', 'aqualuxe_product_data_tabs');

function aqualuxe_product_data_panels() {
    ?>
    <div id="care_instructions_product_data" class="panel woocommerce_options_panel">
        <?php
        woocommerce_wp_textarea_input(array(
            'id'          => '_care_instructions',
            'label'       => __('Care Instructions', 'aqualuxe'),
            'desc_tip'    => true,
            'description' => __('Enter care instructions for this product. This will be displayed in the Care Instructions tab.', 'aqualuxe'),
        ));
        ?>
    </div>
    <?php
}
add_action('woocommerce_product_data_panels', 'aqualuxe_product_data_panels');

function aqualuxe_save_product_data($post_id) {
    if (isset($_POST['_care_instructions'])) {
        update_post_meta($post_id, '_care_instructions', wp_kses_post($_POST['_care_instructions']));
    }
}
add_action('woocommerce_process_product_meta', 'aqualuxe_save_product_data');

/**
 * Customize related products section
 */
function aqualuxe_woocommerce_output_related_products_args($args) {
    $args['posts_per_page'] = 4; // 4 related products
    $args['columns'] = 4; // arranged in 4 columns
    return $args;
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_woocommerce_output_related_products_args');

/**
 * Customize cart page
 */
// Add continue shopping button
function aqualuxe_continue_shopping_button() {
    $shop_page_url = wc_get_page_permalink('shop');
    ?>
    <a href="<?php echo esc_url($shop_page_url); ?>" class="button continue-shopping bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded transition-colors">
        <?php esc_html_e('Continue Shopping', 'aqualuxe'); ?>
    </a>
    <?php
}
add_action('woocommerce_cart_actions', 'aqualuxe_continue_shopping_button');

// Add cross-sells to cart page
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
add_action('woocommerce_after_cart', 'woocommerce_cross_sell_display');

// Customize cross-sells display
function aqualuxe_woocommerce_cross_sells_columns($columns) {
    return 4;
}
add_filter('woocommerce_cross_sells_columns', 'aqualuxe_woocommerce_cross_sells_columns');

function aqualuxe_woocommerce_cross_sells_total($total) {
    return 4;
}
add_filter('woocommerce_cross_sells_total', 'aqualuxe_woocommerce_cross_sells_total');

/**
 * Customize checkout page
 */
// Add order notes to a different position
function aqualuxe_move_order_notes() {
    remove_action('woocommerce_before_checkout_billing_form', 'woocommerce_checkout_coupon_form');
    add_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
}
add_action('wp', 'aqualuxe_move_order_notes');

// Add trust badges to checkout
function aqualuxe_checkout_trust_badges() {
    ?>
    <div class="checkout-trust-badges mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4"><?php esc_html_e('Secure Checkout', 'aqualuxe'); ?></h4>
        <div class="flex flex-wrap items-center justify-center gap-6">
            <div class="trust-badge flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span class="text-sm text-gray-700 dark:text-gray-300"><?php esc_html_e('Secure Payments', 'aqualuxe'); ?></span>
            </div>
            <div class="trust-badge flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span class="text-sm text-gray-700 dark:text-gray-300"><?php esc_html_e('Privacy Protected', 'aqualuxe'); ?></span>
            </div>
            <div class="trust-badge flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm text-gray-700 dark:text-gray-300"><?php esc_html_e('24/7 Support', 'aqualuxe'); ?></span>
            </div>
            <div class="trust-badge flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span class="text-sm text-gray-700 dark:text-gray-300"><?php esc_html_e('Money-Back Guarantee', 'aqualuxe'); ?></span>
            </div>
        </div>
    </div>
    <?php
}
add_action('woocommerce_review_order_after_payment', 'aqualuxe_checkout_trust_badges');

/**
 * Customize my account page
 */
// Add custom endpoints
function aqualuxe_add_my_account_endpoints() {
    add_rewrite_endpoint('wishlist', EP_ROOT | EP_PAGES);
    add_rewrite_endpoint('trade-in', EP_ROOT | EP_PAGES);
    add_rewrite_endpoint('wholesale', EP_ROOT | EP_PAGES);
}
add_action('init', 'aqualuxe_add_my_account_endpoints');

// Add new items to my account menu
function aqualuxe_my_account_menu_items($items) {
    // Add new items after dashboard
    $new_items = array();
    
    foreach ($items as $key => $value) {
        $new_items[$key] = $value;
        
        if ($key === 'dashboard') {
            $new_items['wishlist'] = __('My Wishlist', 'aqualuxe');
            $new_items['trade-in'] = __('Trade-In Program', 'aqualuxe');
            $new_items['wholesale'] = __('Wholesale Access', 'aqualuxe');
        }
    }
    
    return $new_items;
}
add_filter('woocommerce_account_menu_items', 'aqualuxe_my_account_menu_items');

// Add content to new endpoints
function aqualuxe_my_account_wishlist_content() {
    echo do_shortcode('[aqualuxe_wishlist]');
}
add_action('woocommerce_account_wishlist_endpoint', 'aqualuxe_my_account_wishlist_content');

function aqualuxe_my_account_trade_in_content() {
    ?>
    <div class="trade-in-program bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4"><?php esc_html_e('Trade-In Program', 'aqualuxe'); ?></h2>
        <p class="text-gray-700 dark:text-gray-300 mb-6"><?php esc_html_e('Our Trade-In Program allows you to exchange your healthy fish and plants for store credit. Complete the form below to start the process.', 'aqualuxe'); ?></p>
        
        <form class="trade-in-form space-y-6">
            <div class="form-row">
                <label for="trade_item_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Item Type', 'aqualuxe'); ?></label>
                <select id="trade_item_type" name="trade_item_type" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:text-white">
                    <option value=""><?php esc_html_e('Select Item Type', 'aqualuxe'); ?></option>
                    <option value="fish"><?php esc_html_e('Fish', 'aqualuxe'); ?></option>
                    <option value="plant"><?php esc_html_e('Aquatic Plant', 'aqualuxe'); ?></option>
                    <option value="equipment"><?php esc_html_e('Equipment', 'aqualuxe'); ?></option>
                </select>
            </div>
            
            <div class="form-row">
                <label for="trade_item_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Item Name/Species', 'aqualuxe'); ?></label>
                <input type="text" id="trade_item_name" name="trade_item_name" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:text-white" placeholder="<?php esc_attr_e('e.g., Discus Fish, Amazon Sword Plant', 'aqualuxe'); ?>">
            </div>
            
            <div class="form-row">
                <label for="trade_item_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Quantity', 'aqualuxe'); ?></label>
                <input type="number" id="trade_item_quantity" name="trade_item_quantity" min="1" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:text-white">
            </div>
            
            <div class="form-row">
                <label for="trade_item_age" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Age/Condition', 'aqualuxe'); ?></label>
                <input type="text" id="trade_item_age" name="trade_item_age" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:text-white" placeholder="<?php esc_attr_e('e.g., 1 year old, Excellent condition', 'aqualuxe'); ?>">
            </div>
            
            <div class="form-row">
                <label for="trade_item_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Description', 'aqualuxe'); ?></label>
                <textarea id="trade_item_description" name="trade_item_description" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:text-white" placeholder="<?php esc_attr_e('Please provide details about the item, including size, color, and any other relevant information.', 'aqualuxe'); ?>"></textarea>
            </div>
            
            <div class="form-row">
                <label for="trade_item_photos" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Photos', 'aqualuxe'); ?></label>
                <input type="file" id="trade_item_photos" name="trade_item_photos" multiple class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:text-white">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?php esc_html_e('Upload clear photos of the item (max 5 photos, 2MB each)', 'aqualuxe'); ?></p>
            </div>
            
            <div class="form-row">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-6 rounded transition-colors">
                    <?php esc_html_e('Submit Trade-In Request', 'aqualuxe'); ?>
                </button>
            </div>
        </form>
        
        <div class="trade-in-info mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4"><?php esc_html_e('How It Works', 'aqualuxe'); ?></h3>
            <ol class="list-decimal list-inside space-y-2 text-gray-700 dark:text-gray-300">
                <li><?php esc_html_e('Submit your trade-in request with photos and details', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('Our team will review your submission within 48 hours', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('We\'ll contact you with an offer for store credit', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('If you accept, bring your items to our store for verification', 'aqualuxe'); ?></li>
                <li><?php esc_html_e('Receive store credit to use on your next purchase', 'aqualuxe'); ?></li>
            </ol>
        </div>
    </div>
    <?php
}
add_action('woocommerce_account_trade-in_endpoint', 'aqualuxe_my_account_trade_in_content');

function aqualuxe_my_account_wholesale_content() {
    $user_id = get_current_user_id();
    $is_wholesale = get_user_meta($user_id, 'is_wholesale_customer', true);
    
    if ($is_wholesale) {
        ?>
        <div class="wholesale-access bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
            <div class="wholesale-header flex items-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-teal-600 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white"><?php esc_html_e('Wholesale Access Granted', 'aqualuxe'); ?></h2>
                    <p class="text-gray-600 dark:text-gray-400"><?php esc_html_e('Your account has wholesale privileges', 'aqualuxe'); ?></p>
                </div>
            </div>
            
            <div class="wholesale-content space-y-6">
                <div class="wholesale-info bg-teal-50 dark:bg-teal-900 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-teal-800 dark:text-teal-200 mb-2"><?php esc_html_e('Your Wholesale Benefits', 'aqualuxe'); ?></h3>
                    <ul class="list-disc list-inside space-y-1 text-teal-700 dark:text-teal-300">
                        <li><?php esc_html_e('Special wholesale pricing on all products', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Bulk order discounts', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Priority shipping and handling', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Dedicated account manager', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Early access to new products', 'aqualuxe'); ?></li>
                    </ul>
                </div>
                
                <div class="wholesale-actions">
                    <a href="<?php echo esc_url(home_url('/shop')); ?>" class="inline-block bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-6 rounded transition-colors mr-4">
                        <?php esc_html_e('Shop Wholesale Prices', 'aqualuxe'); ?>
                    </a>
                    
                    <a href="<?php echo esc_url(home_url('/wholesale')); ?>" class="inline-block bg-white hover:bg-gray-100 text-teal-600 font-bold py-2 px-6 rounded border border-teal-600 transition-colors">
                        <?php esc_html_e('View Wholesale Catalog', 'aqualuxe'); ?>
                    </a>
                </div>
                
                <div class="wholesale-contact mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4"><?php esc_html_e('Need Assistance?', 'aqualuxe'); ?></h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4"><?php esc_html_e('Contact your dedicated wholesale account manager:', 'aqualuxe'); ?></p>
                    
                    <div class="contact-info">
                        <p class="flex items-center text-gray-700 dark:text-gray-300 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>wholesale@aqualuxe.com</span>
                        </p>
                        <p class="flex items-center text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>+94 123 456 7890 (Wholesale Dept.)</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="wholesale-application bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4"><?php esc_html_e('Apply for Wholesale Access', 'aqualuxe'); ?></h2>
            <p class="text-gray-700 dark:text-gray-300 mb-6"><?php esc_html_e('Complete the form below to apply for a wholesale account. Our team will review your application and contact you within 2-3 business days.', 'aqualuxe'); ?></p>
            
            <form class="wholesale-form space-y-6">
                <div class="form-row">
                    <label for="business_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Business Name', 'aqualuxe'); ?> <span class="text-red-600">*</span></label>
                    <input type="text" id="business_name" name="business_name" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:text-white">
                </div>
                
                <div class="form-row">
                    <label for="business_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Business Type', 'aqualuxe'); ?> <span class="text-red-600">*</span></label>
                    <select id="business_type" name="business_type" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:text-white">
                        <option value=""><?php esc_html_e('Select Business Type', 'aqualuxe'); ?></option>
                        <option value="pet_store"><?php esc_html_e('Pet Store', 'aqualuxe'); ?></option>
                        <option value="aquarium_shop"><?php esc_html_e('Aquarium Shop', 'aqualuxe'); ?></option>
                        <option value="breeder"><?php esc_html_e('Fish Breeder', 'aqualuxe'); ?></option>
                        <option value="landscaper"><?php esc_html_e('Aquascape Designer', 'aqualuxe'); ?></option>
                        <option value="zoo"><?php esc_html_e('Zoo/Aquarium', 'aqualuxe'); ?></option>
                        <option value="hotel"><?php esc_html_e('Hotel/Resort', 'aqualuxe'); ?></option>
                        <option value="other"><?php esc_html_e('Other', 'aqualuxe'); ?></option>
                    </select>
                </div>
                
                <div class="form-row">
                    <label for="business_website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Business Website', 'aqualuxe'); ?></label>
                    <input type="url" id="business_website" name="business_website" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:text-white">
                </div>
                
                <div class="form-row">
                    <label for="business_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Business Address', 'aqualuxe'); ?> <span class="text-red-600">*</span></label>
                    <textarea id="business_address" name="business_address" required rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:text-white"></textarea>
                </div>
                
                <div class="form-row">
                    <label for="tax_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Tax ID / Business Registration Number', 'aqualuxe'); ?> <span class="text-red-600">*</span></label>
                    <input type="text" id="tax_id" name="tax_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:text-white">
                </div>
                
                <div class="form-row">
                    <label for="business_license" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Business License', 'aqualuxe'); ?> <span class="text-red-600">*</span></label>
                    <input type="file" id="business_license" name="business_license" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:text-white">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?php esc_html_e('Upload a copy of your business license or registration (PDF, JPG, PNG)', 'aqualuxe'); ?></p>
                </div>
                
                <div class="form-row">
                    <label for="monthly_volume" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Estimated Monthly Order Volume', 'aqualuxe'); ?> <span class="text-red-600">*</span></label>
                    <select id="monthly_volume" name="monthly_volume" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:text-white">
                        <option value=""><?php esc_html_e('Select Volume', 'aqualuxe'); ?></option>
                        <option value="small"><?php esc_html_e('Small ($500-$1,000)', 'aqualuxe'); ?></option>
                        <option value="medium"><?php esc_html_e('Medium ($1,000-$5,000)', 'aqualuxe'); ?></option>
                        <option value="large"><?php esc_html_e('Large ($5,000-$10,000)', 'aqualuxe'); ?></option>
                        <option value="xlarge"><?php esc_html_e('Very Large (Over $10,000)', 'aqualuxe'); ?></option>
                    </select>
                </div>
                
                <div class="form-row">
                    <label for="products_interest" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Products of Interest', 'aqualuxe'); ?> <span class="text-red-600">*</span></label>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input type="checkbox" id="interest_fish" name="products_interest[]" value="fish" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                            <label for="interest_fish" class="ml-2 block text-sm text-gray-700 dark:text-gray-300"><?php esc_html_e('Ornamental Fish', 'aqualuxe'); ?></label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="interest_plants" name="products_interest[]" value="plants" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                            <label for="interest_plants" class="ml-2 block text-sm text-gray-700 dark:text-gray-300"><?php esc_html_e('Aquatic Plants', 'aqualuxe'); ?></label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="interest_equipment" name="products_interest[]" value="equipment" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                            <label for="interest_equipment" class="ml-2 block text-sm text-gray-700 dark:text-gray-300"><?php esc_html_e('Equipment & Supplies', 'aqualuxe'); ?></label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="interest_tanks" name="products_interest[]" value="tanks" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                            <label for="interest_tanks" class="ml-2 block text-sm text-gray-700 dark:text-gray-300"><?php esc_html_e('Custom Aquariums', 'aqualuxe'); ?></label>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <label for="additional_info" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Additional Information', 'aqualuxe'); ?></label>
                    <textarea id="additional_info" name="additional_info" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:text-white" placeholder="<?php esc_attr_e('Please provide any additional information that might be relevant to your application.', 'aqualuxe'); ?>"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-medium text-gray-700 dark:text-gray-300"><?php esc_html_e('I agree to the wholesale terms and conditions', 'aqualuxe'); ?> <span class="text-red-600">*</span></label>
                            <p class="text-gray-500 dark:text-gray-400"><?php esc_html_e('By checking this box, you agree to our wholesale terms and conditions, including minimum order requirements and payment terms.', 'aqualuxe'); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-6 rounded transition-colors">
                        <?php esc_html_e('Submit Application', 'aqualuxe'); ?>
                    </button>
                </div>
            </form>
        </div>
        <?php
    }
}
add_action('woocommerce_account_wholesale_endpoint', 'aqualuxe_my_account_wholesale_content');

// Flush rewrite rules on theme activation
function aqualuxe_rewrite_flush() {
    aqualuxe_add_my_account_endpoints();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'aqualuxe_rewrite_flush');