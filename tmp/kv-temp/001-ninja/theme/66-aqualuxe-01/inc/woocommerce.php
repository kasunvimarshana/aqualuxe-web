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
    
    // Add support for WooCommerce features
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Add support for WooCommerce image sizes
    add_image_size('aqualuxe-product-thumbnail', 300, 300, true);
    add_image_size('aqualuxe-product-single', 600, 600, true);
    add_image_size('aqualuxe-product-category', 800, 450, true);
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_setup');

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function aqualuxe_woocommerce_scripts() {
    // Get the mix manifest
    $mix_manifest = aqualuxe_get_mix_manifest();
    
    // Enqueue WooCommerce styles
    wp_enqueue_style(
        'aqualuxe-woocommerce-style',
        isset($mix_manifest['/css/woocommerce.css']) ? AQUALUXE_DIST_URI . 'css/' . ltrim($mix_manifest['/css/woocommerce.css'], '/') : AQUALUXE_DIST_URI . 'css/woocommerce.css',
        ['aqualuxe-style'],
        AQUALUXE_VERSION
    );
    
    // Enqueue WooCommerce scripts
    wp_enqueue_script(
        'aqualuxe-woocommerce-script',
        isset($mix_manifest['/js/woocommerce.js']) ? AQUALUXE_DIST_URI . 'js/' . ltrim($mix_manifest['/js/woocommerce.js'], '/') : AQUALUXE_DIST_URI . 'js/woocommerce.js',
        ['jquery'],
        AQUALUXE_VERSION,
        true
    );
    
    // Add WooCommerce settings to JS
    wp_localize_script('aqualuxe-woocommerce-script', 'aqualuxeWooCommerce', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe-woocommerce-nonce'),
        'isCart' => is_cart(),
        'isCheckout' => is_checkout(),
        'isAccount' => is_account_page(),
        'currency' => get_woocommerce_currency_symbol(),
    ]);
}
add_action('wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts');

/**
 * Get the mix manifest.
 *
 * @return array
 */
function aqualuxe_get_mix_manifest() {
    $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
    
    if (file_exists($manifest_path)) {
        return json_decode(file_get_contents($manifest_path), true);
    }
    
    return [];
}

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
    $defaults = [
        'posts_per_page' => 3,
        'columns' => 3,
    ];
    
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
 * Add custom WooCommerce wrapper.
 */
function aqualuxe_woocommerce_wrapper_before() {
    ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
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
 * Add WooCommerce sidebar.
 */
function aqualuxe_woocommerce_sidebar() {
    if (is_active_sidebar('sidebar-shop')) {
        ?>
        <aside id="secondary" class="widget-area shop-sidebar">
            <?php dynamic_sidebar('sidebar-shop'); ?>
        </aside><!-- #secondary -->
        <?php
    }
}
add_action('woocommerce_sidebar', 'aqualuxe_woocommerce_sidebar');

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
function aqualuxe_woocommerce_header_cart() {
    if (is_cart()) {
        $class = 'current-menu-item';
    } else {
        $class = '';
    }
    ?>
    <div class="site-header-cart">
        <div class="cart-contents-wrapper">
            <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'aqualuxe'); ?>">
                <i class="fas fa-shopping-cart"></i>
                <span class="count"><?php echo wp_kses_data(WC()->cart->get_cart_contents_count()); ?></span>
                <span class="amount"><?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?></span>
            </a>
        </div>
        <div class="mini-cart-dropdown">
            <?php the_widget('WC_Widget_Cart', ['title' => '']); ?>
        </div>
    </div>
    <?php
}

/**
 * Update cart count via AJAX.
 */
function aqualuxe_woocommerce_update_cart_count() {
    check_ajax_referer('aqualuxe-woocommerce-nonce', 'nonce');
    
    $cart_count = WC()->cart->get_cart_contents_count();
    $cart_subtotal = WC()->cart->get_cart_subtotal();
    
    wp_send_json_success([
        'count' => $cart_count,
        'subtotal' => $cart_subtotal,
    ]);
}
add_action('wp_ajax_aqualuxe_update_cart_count', 'aqualuxe_woocommerce_update_cart_count');
add_action('wp_ajax_nopriv_aqualuxe_update_cart_count', 'aqualuxe_woocommerce_update_cart_count');

/**
 * Add to cart via AJAX.
 */
function aqualuxe_woocommerce_ajax_add_to_cart() {
    check_ajax_referer('aqualuxe-woocommerce-nonce', 'nonce');
    
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;
    $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;
    $variation = isset($_POST['variation']) ? $_POST['variation'] : [];
    
    if ($product_id > 0) {
        $added = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
        
        if ($added) {
            $cart_count = WC()->cart->get_cart_contents_count();
            $cart_subtotal = WC()->cart->get_cart_subtotal();
            $product = wc_get_product($product_id);
            
            ob_start();
            woocommerce_mini_cart();
            $mini_cart = ob_get_clean();
            
            wp_send_json_success([
                'count' => $cart_count,
                'subtotal' => $cart_subtotal,
                'product_name' => $product->get_name(),
                'mini_cart' => $mini_cart,
            ]);
        } else {
            wp_send_json_error([
                'message' => __('Failed to add product to cart', 'aqualuxe'),
            ]);
        }
    } else {
        wp_send_json_error([
            'message' => __('Invalid product ID', 'aqualuxe'),
        ]);
    }
}
add_action('wp_ajax_aqualuxe_ajax_add_to_cart', 'aqualuxe_woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_aqualuxe_ajax_add_to_cart', 'aqualuxe_woocommerce_ajax_add_to_cart');

/**
 * Update mini cart contents via AJAX.
 */
function aqualuxe_woocommerce_update_mini_cart() {
    check_ajax_referer('aqualuxe-woocommerce-nonce', 'nonce');
    
    ob_start();
    woocommerce_mini_cart();
    $mini_cart = ob_get_clean();
    
    wp_send_json_success([
        'mini_cart' => $mini_cart,
    ]);
}
add_action('wp_ajax_aqualuxe_update_mini_cart', 'aqualuxe_woocommerce_update_mini_cart');
add_action('wp_ajax_nopriv_aqualuxe_update_mini_cart', 'aqualuxe_woocommerce_update_mini_cart');

/**
 * Add quick view modal.
 */
function aqualuxe_woocommerce_quick_view_button() {
    global $product;
    
    echo '<div class="quick-view-button">';
    echo '<button class="button quick-view" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</button>';
    echo '</div>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_quick_view_button', 15);

/**
 * Quick view modal content.
 */
function aqualuxe_woocommerce_quick_view_modal() {
    ?>
    <div id="quick-view-modal" class="quick-view-modal">
        <div class="quick-view-modal-content">
            <span class="close-modal">&times;</span>
            <div class="quick-view-content"></div>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'aqualuxe_woocommerce_quick_view_modal');

/**
 * Quick view content via AJAX.
 */
function aqualuxe_woocommerce_quick_view_content() {
    check_ajax_referer('aqualuxe-woocommerce-nonce', 'nonce');
    
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    
    if ($product_id > 0) {
        $product = wc_get_product($product_id);
        
        if ($product) {
            ob_start();
            ?>
            <div class="quick-view-product">
                <div class="quick-view-images">
                    <?php echo $product->get_image('aqualuxe-product-single'); ?>
                </div>
                <div class="quick-view-summary">
                    <h2 class="product-title"><?php echo $product->get_name(); ?></h2>
                    <div class="product-price"><?php echo $product->get_price_html(); ?></div>
                    <div class="product-rating">
                        <?php if ($product->get_average_rating() > 0) : ?>
                            <?php echo wc_get_rating_html($product->get_average_rating()); ?>
                            <a href="<?php echo esc_url(get_permalink($product_id)); ?>#reviews" class="review-link">
                                (<?php echo $product->get_review_count(); ?> <?php esc_html_e('reviews', 'aqualuxe'); ?>)
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="product-description">
                        <?php echo wp_trim_words($product->get_short_description(), 30, '...'); ?>
                    </div>
                    <div class="product-add-to-cart">
                        <?php woocommerce_template_single_add_to_cart(); ?>
                    </div>
                    <div class="product-meta">
                        <?php if ($product->get_sku()) : ?>
                            <span class="sku-wrapper">
                                <?php esc_html_e('SKU:', 'aqualuxe'); ?> <span class="sku"><?php echo $product->get_sku(); ?></span>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($product->get_category_ids()) : ?>
                            <span class="categories-wrapper">
                                <?php esc_html_e('Categories:', 'aqualuxe'); ?> <?php echo wc_get_product_category_list($product_id, ', '); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="product-actions">
                        <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="button view-details">
                            <?php esc_html_e('View Details', 'aqualuxe'); ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php
            $content = ob_get_clean();
            
            wp_send_json_success([
                'content' => $content,
            ]);
        } else {
            wp_send_json_error([
                'message' => __('Product not found', 'aqualuxe'),
            ]);
        }
    } else {
        wp_send_json_error([
            'message' => __('Invalid product ID', 'aqualuxe'),
        ]);
    }
}
add_action('wp_ajax_aqualuxe_quick_view_content', 'aqualuxe_woocommerce_quick_view_content');
add_action('wp_ajax_nopriv_aqualuxe_quick_view_content', 'aqualuxe_woocommerce_quick_view_content');

/**
 * Add wishlist button.
 */
function aqualuxe_woocommerce_wishlist_button() {
    global $product;
    
    if (aqualuxe_is_module_active('wishlist')) {
        $product_id = $product->get_id();
        $wishlist = get_user_meta(get_current_user_id(), 'aqualuxe_wishlist', true);
        $in_wishlist = is_array($wishlist) && in_array($product_id, $wishlist);
        
        echo '<div class="wishlist-button">';
        echo '<button class="button wishlist-toggle ' . ($in_wishlist ? 'in-wishlist' : '') . '" data-product-id="' . esc_attr($product_id) . '">';
        echo '<i class="' . ($in_wishlist ? 'fas' : 'far') . ' fa-heart"></i>';
        echo '<span>' . ($in_wishlist ? esc_html__('Remove from Wishlist', 'aqualuxe') : esc_html__('Add to Wishlist', 'aqualuxe')) . '</span>';
        echo '</button>';
        echo '</div>';
    }
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_wishlist_button', 20);
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_wishlist_button', 35);

/**
 * Toggle wishlist via AJAX.
 */
function aqualuxe_woocommerce_toggle_wishlist() {
    check_ajax_referer('aqualuxe-woocommerce-nonce', 'nonce');
    
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $user_id = get_current_user_id();
    
    if ($product_id > 0 && $user_id > 0) {
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        
        if (!is_array($wishlist)) {
            $wishlist = [];
        }
        
        $in_wishlist = in_array($product_id, $wishlist);
        
        if ($in_wishlist) {
            // Remove from wishlist
            $wishlist = array_diff($wishlist, [$product_id]);
            $action = 'removed';
            $message = __('Product removed from wishlist', 'aqualuxe');
        } else {
            // Add to wishlist
            $wishlist[] = $product_id;
            $action = 'added';
            $message = __('Product added to wishlist', 'aqualuxe');
        }
        
        update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
        
        wp_send_json_success([
            'action' => $action,
            'message' => $message,
            'count' => count($wishlist),
        ]);
    } else {
        wp_send_json_error([
            'message' => __('Please log in to add products to your wishlist', 'aqualuxe'),
        ]);
    }
}
add_action('wp_ajax_aqualuxe_toggle_wishlist', 'aqualuxe_woocommerce_toggle_wishlist');
add_action('wp_ajax_nopriv_aqualuxe_toggle_wishlist', 'aqualuxe_woocommerce_toggle_wishlist');

/**
 * Add product comparison button.
 */
function aqualuxe_woocommerce_compare_button() {
    global $product;
    
    if (aqualuxe_is_module_active('compare')) {
        $product_id = $product->get_id();
        $compare_list = isset($_COOKIE['aqualuxe_compare_list']) ? explode(',', $_COOKIE['aqualuxe_compare_list']) : [];
        $in_compare = in_array($product_id, $compare_list);
        
        echo '<div class="compare-button">';
        echo '<button class="button compare-toggle ' . ($in_compare ? 'in-compare' : '') . '" data-product-id="' . esc_attr($product_id) . '">';
        echo '<i class="fas fa-exchange-alt"></i>';
        echo '<span>' . ($in_compare ? esc_html__('Remove from Compare', 'aqualuxe') : esc_html__('Add to Compare', 'aqualuxe')) . '</span>';
        echo '</button>';
        echo '</div>';
    }
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_compare_button', 25);
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_compare_button', 40);

/**
 * Toggle compare via AJAX.
 */
function aqualuxe_woocommerce_toggle_compare() {
    check_ajax_referer('aqualuxe-woocommerce-nonce', 'nonce');
    
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    
    if ($product_id > 0) {
        $compare_list = isset($_COOKIE['aqualuxe_compare_list']) ? explode(',', $_COOKIE['aqualuxe_compare_list']) : [];
        $compare_list = array_filter($compare_list); // Remove empty values
        
        $in_compare = in_array($product_id, $compare_list);
        
        if ($in_compare) {
            // Remove from compare
            $compare_list = array_diff($compare_list, [$product_id]);
            $action = 'removed';
            $message = __('Product removed from comparison', 'aqualuxe');
        } else {
            // Add to compare
            $compare_list[] = $product_id;
            $action = 'added';
            $message = __('Product added to comparison', 'aqualuxe');
        }
        
        // Set cookie
        setcookie('aqualuxe_compare_list', implode(',', $compare_list), time() + (86400 * 30), '/'); // 30 days
        
        wp_send_json_success([
            'action' => $action,
            'message' => $message,
            'count' => count($compare_list),
        ]);
    } else {
        wp_send_json_error([
            'message' => __('Invalid product ID', 'aqualuxe'),
        ]);
    }
}
add_action('wp_ajax_aqualuxe_toggle_compare', 'aqualuxe_woocommerce_toggle_compare');
add_action('wp_ajax_nopriv_aqualuxe_toggle_compare', 'aqualuxe_woocommerce_toggle_compare');

/**
 * Add product filters.
 */
function aqualuxe_woocommerce_product_filters() {
    if (is_shop() || is_product_category() || is_product_tag()) {
        ?>
        <div class="product-filters">
            <div class="filter-toggle">
                <button class="button filter-toggle-button">
                    <i class="fas fa-filter"></i>
                    <span><?php esc_html_e('Filter', 'aqualuxe'); ?></span>
                </button>
            </div>
            
            <div class="filter-content">
                <div class="filter-header">
                    <h3><?php esc_html_e('Filter Products', 'aqualuxe'); ?></h3>
                    <button class="close-filters">&times;</button>
                </div>
                
                <div class="filter-body">
                    <?php dynamic_sidebar('sidebar-shop'); ?>
                </div>
                
                <div class="filter-footer">
                    <button class="button apply-filters"><?php esc_html_e('Apply Filters', 'aqualuxe'); ?></button>
                    <button class="button reset-filters"><?php esc_html_e('Reset Filters', 'aqualuxe'); ?></button>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('woocommerce_before_shop_loop', 'aqualuxe_woocommerce_product_filters', 20);

/**
 * Add product sorting.
 */
function aqualuxe_woocommerce_product_sorting() {
    if (is_shop() || is_product_category() || is_product_tag()) {
        ?>
        <div class="product-sorting">
            <div class="sorting-toggle">
                <button class="button sorting-toggle-button">
                    <i class="fas fa-sort"></i>
                    <span><?php esc_html_e('Sort', 'aqualuxe'); ?></span>
                </button>
            </div>
            
            <div class="sorting-content">
                <div class="sorting-header">
                    <h3><?php esc_html_e('Sort Products', 'aqualuxe'); ?></h3>
                    <button class="close-sorting">&times;</button>
                </div>
                
                <div class="sorting-body">
                    <?php woocommerce_catalog_ordering(); ?>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('woocommerce_before_shop_loop', 'aqualuxe_woocommerce_product_sorting', 25);

/**
 * Add product view switcher.
 */
function aqualuxe_woocommerce_product_view_switcher() {
    if (is_shop() || is_product_category() || is_product_tag()) {
        $view = isset($_COOKIE['aqualuxe_product_view']) ? $_COOKIE['aqualuxe_product_view'] : 'grid';
        ?>
        <div class="product-view-switcher">
            <button class="view-button grid-view <?php echo $view === 'grid' ? 'active' : ''; ?>" data-view="grid">
                <i class="fas fa-th"></i>
            </button>
            <button class="view-button list-view <?php echo $view === 'list' ? 'active' : ''; ?>" data-view="list">
                <i class="fas fa-list"></i>
            </button>
        </div>
        <?php
    }
}
add_action('woocommerce_before_shop_loop', 'aqualuxe_woocommerce_product_view_switcher', 30);

/**
 * Switch product view via AJAX.
 */
function aqualuxe_woocommerce_switch_product_view() {
    check_ajax_referer('aqualuxe-woocommerce-nonce', 'nonce');
    
    $view = isset($_POST['view']) ? sanitize_text_field($_POST['view']) : 'grid';
    
    // Set cookie
    setcookie('aqualuxe_product_view', $view, time() + (86400 * 30), '/'); // 30 days
    
    wp_send_json_success([
        'view' => $view,
    ]);
}
add_action('wp_ajax_aqualuxe_switch_product_view', 'aqualuxe_woocommerce_switch_product_view');
add_action('wp_ajax_nopriv_aqualuxe_switch_product_view', 'aqualuxe_woocommerce_switch_product_view');

/**
 * Add product view class to body.
 *
 * @param array $classes Body classes.
 * @return array
 */
function aqualuxe_woocommerce_product_view_body_class($classes) {
    if (is_shop() || is_product_category() || is_product_tag()) {
        $view = isset($_COOKIE['aqualuxe_product_view']) ? $_COOKIE['aqualuxe_product_view'] : 'grid';
        $classes[] = 'product-view-' . $view;
    }
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_woocommerce_product_view_body_class');

/**
 * Add product countdown.
 */
function aqualuxe_woocommerce_product_countdown() {
    global $product;
    
    if ($product->is_on_sale()) {
        $sale_price_dates_to = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
        
        if ($sale_price_dates_to && $sale_price_dates_to > time()) {
            ?>
            <div class="product-countdown" data-end-time="<?php echo esc_attr($sale_price_dates_to); ?>">
                <div class="countdown-label"><?php esc_html_e('Sale ends in:', 'aqualuxe'); ?></div>
                <div class="countdown-timer">
                    <div class="countdown-item days">
                        <span class="countdown-value">00</span>
                        <span class="countdown-label"><?php esc_html_e('Days', 'aqualuxe'); ?></span>
                    </div>
                    <div class="countdown-item hours">
                        <span class="countdown-value">00</span>
                        <span class="countdown-label"><?php esc_html_e('Hours', 'aqualuxe'); ?></span>
                    </div>
                    <div class="countdown-item minutes">
                        <span class="countdown-value">00</span>
                        <span class="countdown-label"><?php esc_html_e('Minutes', 'aqualuxe'); ?></span>
                    </div>
                    <div class="countdown-item seconds">
                        <span class="countdown-value">00</span>
                        <span class="countdown-label"><?php esc_html_e('Seconds', 'aqualuxe'); ?></span>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_countdown', 15);

/**
 * Add product stock progress bar.
 */
function aqualuxe_woocommerce_product_stock_progress() {
    global $product;
    
    if ($product->is_in_stock() && $product->managing_stock()) {
        $total_stock = get_post_meta($product->get_id(), '_stock', true);
        $stock_quantity = $product->get_stock_quantity();
        $percentage = 0;
        
        if ($total_stock > 0) {
            $percentage = ($stock_quantity / $total_stock) * 100;
        }
        
        ?>
        <div class="product-stock-progress">
            <div class="stock-label">
                <?php esc_html_e('Availability:', 'aqualuxe'); ?>
                <span class="stock-quantity"><?php echo $stock_quantity; ?></span>
                <?php esc_html_e('in stock', 'aqualuxe'); ?>
            </div>
            <div class="progress-bar">
                <div class="progress" style="width: <?php echo esc_attr($percentage); ?>%"></div>
            </div>
        </div>
        <?php
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_stock_progress', 30);

/**
 * Add product size guide.
 */
function aqualuxe_woocommerce_product_size_guide() {
    global $product;
    
    if ($product->is_type('variable') && has_term('clothing', 'product_cat', $product->get_id())) {
        ?>
        <div class="product-size-guide">
            <button class="button size-guide-toggle">
                <i class="fas fa-ruler"></i>
                <span><?php esc_html_e('Size Guide', 'aqualuxe'); ?></span>
            </button>
            
            <div class="size-guide-modal">
                <div class="size-guide-content">
                    <div class="size-guide-header">
                        <h3><?php esc_html_e('Size Guide', 'aqualuxe'); ?></h3>
                        <button class="close-size-guide">&times;</button>
                    </div>
                    
                    <div class="size-guide-body">
                        <?php
                        // Get size guide content from product meta or default
                        $size_guide = get_post_meta($product->get_id(), '_aqualuxe_size_guide', true);
                        
                        if ($size_guide) {
                            echo wp_kses_post($size_guide);
                        } else {
                            // Default size guide
                            ?>
                            <table class="size-guide-table">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e('Size', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('Chest (in)', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('Waist (in)', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('Hips (in)', 'aqualuxe'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>XS</td>
                                        <td>34-36</td>
                                        <td>28-30</td>
                                        <td>34-36</td>
                                    </tr>
                                    <tr>
                                        <td>S</td>
                                        <td>36-38</td>
                                        <td>30-32</td>
                                        <td>36-38</td>
                                    </tr>
                                    <tr>
                                        <td>M</td>
                                        <td>38-40</td>
                                        <td>32-34</td>
                                        <td>38-40</td>
                                    </tr>
                                    <tr>
                                        <td>L</td>
                                        <td>40-42</td>
                                        <td>34-36</td>
                                        <td>40-42</td>
                                    </tr>
                                    <tr>
                                        <td>XL</td>
                                        <td>42-44</td>
                                        <td>36-38</td>
                                        <td>42-44</td>
                                    </tr>
                                    <tr>
                                        <td>XXL</td>
                                        <td>44-46</td>
                                        <td>38-40</td>
                                        <td>44-46</td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="size-guide-note"><?php esc_html_e('Measurements are approximate. Please refer to the specific product description for more details.', 'aqualuxe'); ?></p>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_size_guide', 25);

/**
 * Add product delivery information.
 */
function aqualuxe_woocommerce_product_delivery_info() {
    global $product;
    
    ?>
    <div class="product-delivery-info">
        <div class="delivery-item">
            <i class="fas fa-truck"></i>
            <span><?php esc_html_e('Free shipping on orders over $50', 'aqualuxe'); ?></span>
        </div>
        <div class="delivery-item">
            <i class="fas fa-undo"></i>
            <span><?php esc_html_e('30-day return policy', 'aqualuxe'); ?></span>
        </div>
        <div class="delivery-item">
            <i class="fas fa-shield-alt"></i>
            <span><?php esc_html_e('Secure checkout', 'aqualuxe'); ?></span>
        </div>
    </div>
    <?php
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_delivery_info', 45);

/**
 * Add product social sharing.
 */
function aqualuxe_woocommerce_product_sharing() {
    global $product;
    
    ?>
    <div class="product-sharing">
        <span class="sharing-label"><?php esc_html_e('Share:', 'aqualuxe'); ?></span>
        <div class="sharing-buttons">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode($product->get_name()); ?>" target="_blank" rel="noopener noreferrer" class="twitter">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&media=<?php echo urlencode(wp_get_attachment_url($product->get_image_id())); ?>&description=<?php echo urlencode($product->get_name()); ?>" target="_blank" rel="noopener noreferrer" class="pinterest">
                <i class="fab fa-pinterest-p"></i>
            </a>
            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($product->get_name() . ' ' . get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="whatsapp">
                <i class="fab fa-whatsapp"></i>
            </a>
            <a href="mailto:?subject=<?php echo urlencode($product->get_name()); ?>&body=<?php echo urlencode(get_permalink()); ?>" class="email">
                <i class="fas fa-envelope"></i>
            </a>
        </div>
    </div>
    <?php
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_sharing', 50);

/**
 * Add recently viewed products.
 */
function aqualuxe_woocommerce_recently_viewed_products() {
    if (is_singular('product')) {
        // Get current product ID
        $product_id = get_the_ID();
        
        // Get cookie
        $viewed_products = isset($_COOKIE['aqualuxe_recently_viewed']) ? explode(',', $_COOKIE['aqualuxe_recently_viewed']) : [];
        
        // Remove current product from the list
        $viewed_products = array_diff($viewed_products, [$product_id]);
        
        // Add current product to the beginning
        array_unshift($viewed_products, $product_id);
        
        // Keep only the last 10 products
        $viewed_products = array_slice($viewed_products, 0, 10);
        
        // Set cookie
        setcookie('aqualuxe_recently_viewed', implode(',', $viewed_products), time() + (86400 * 30), '/'); // 30 days
        
        // Remove current product for display
        $viewed_products = array_diff($viewed_products, [$product_id]);
        
        if (!empty($viewed_products)) {
            ?>
            <section class="recently-viewed-products">
                <h2><?php esc_html_e('Recently Viewed Products', 'aqualuxe'); ?></h2>
                <?php
                $args = [
                    'post_type' => 'product',
                    'posts_per_page' => 4,
                    'post__in' => $viewed_products,
                    'orderby' => 'post__in',
                ];
                
                $products = new WP_Query($args);
                
                if ($products->have_posts()) {
                    woocommerce_product_loop_start();
                    
                    while ($products->have_posts()) {
                        $products->the_post();
                        wc_get_template_part('content', 'product');
                    }
                    
                    woocommerce_product_loop_end();
                    
                    wp_reset_postdata();
                }
                ?>
            </section>
            <?php
        }
    }
}
add_action('woocommerce_after_single_product', 'aqualuxe_woocommerce_recently_viewed_products');

/**
 * Add product tabs.
 *
 * @param array $tabs Product tabs.
 * @return array
 */
function aqualuxe_woocommerce_product_tabs($tabs) {
    global $product;
    
    // Add custom information tab
    $tabs['custom_info'] = [
        'title' => __('Additional Information', 'aqualuxe'),
        'priority' => 25,
        'callback' => 'aqualuxe_woocommerce_custom_info_tab',
    ];
    
    // Add shipping tab
    $tabs['shipping'] = [
        'title' => __('Shipping & Returns', 'aqualuxe'),
        'priority' => 30,
        'callback' => 'aqualuxe_woocommerce_shipping_tab',
    ];
    
    // Add FAQ tab
    $tabs['faq'] = [
        'title' => __('FAQ', 'aqualuxe'),
        'priority' => 35,
        'callback' => 'aqualuxe_woocommerce_faq_tab',
    ];
    
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs');

/**
 * Custom information tab content.
 */
function aqualuxe_woocommerce_custom_info_tab() {
    global $product;
    
    $custom_info = get_post_meta($product->get_id(), '_aqualuxe_custom_info', true);
    
    if ($custom_info) {
        echo wp_kses_post($custom_info);
    } else {
        // Default content
        ?>
        <h3><?php esc_html_e('Product Features', 'aqualuxe'); ?></h3>
        <ul>
            <li><?php esc_html_e('High-quality materials', 'aqualuxe'); ?></li>
            <li><?php esc_html_e('Durable construction', 'aqualuxe'); ?></li>
            <li><?php esc_html_e('Easy to maintain', 'aqualuxe'); ?></li>
            <li><?php esc_html_e('Eco-friendly design', 'aqualuxe'); ?></li>
        </ul>
        
        <h3><?php esc_html_e('Care Instructions', 'aqualuxe'); ?></h3>
        <p><?php esc_html_e('Please refer to the product manual for detailed care instructions.', 'aqualuxe'); ?></p>
        <?php
    }
}

/**
 * Shipping tab content.
 */
function aqualuxe_woocommerce_shipping_tab() {
    global $product;
    
    $shipping_info = get_post_meta($product->get_id(), '_aqualuxe_shipping_info', true);
    
    if ($shipping_info) {
        echo wp_kses_post($shipping_info);
    } else {
        // Default content
        ?>
        <h3><?php esc_html_e('Shipping Information', 'aqualuxe'); ?></h3>
        <p><?php esc_html_e('We offer the following shipping options:', 'aqualuxe'); ?></p>
        <ul>
            <li><?php esc_html_e('Standard Shipping: 3-5 business days', 'aqualuxe'); ?></li>
            <li><?php esc_html_e('Express Shipping: 1-2 business days', 'aqualuxe'); ?></li>
            <li><?php esc_html_e('International Shipping: 7-14 business days', 'aqualuxe'); ?></li>
        </ul>
        
        <h3><?php esc_html_e('Return Policy', 'aqualuxe'); ?></h3>
        <p><?php esc_html_e('We offer a 30-day return policy for all products. If you are not satisfied with your purchase, you can return it within 30 days for a full refund or exchange.', 'aqualuxe'); ?></p>
        <p><?php esc_html_e('Please note that the product must be in its original condition and packaging.', 'aqualuxe'); ?></p>
        <?php
    }
}

/**
 * FAQ tab content.
 */
function aqualuxe_woocommerce_faq_tab() {
    global $product;
    
    $faq = get_post_meta($product->get_id(), '_aqualuxe_faq', true);
    
    if ($faq) {
        echo wp_kses_post($faq);
    } else {
        // Default content
        ?>
        <div class="product-faq">
            <div class="faq-item">
                <div class="faq-question"><?php esc_html_e('How long does shipping take?', 'aqualuxe'); ?></div>
                <div class="faq-answer"><?php esc_html_e('Standard shipping takes 3-5 business days, while express shipping takes 1-2 business days.', 'aqualuxe'); ?></div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question"><?php esc_html_e('What is your return policy?', 'aqualuxe'); ?></div>
                <div class="faq-answer"><?php esc_html_e('We offer a 30-day return policy for all products. If you are not satisfied with your purchase, you can return it within 30 days for a full refund or exchange.', 'aqualuxe'); ?></div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question"><?php esc_html_e('Do you offer international shipping?', 'aqualuxe'); ?></div>
                <div class="faq-answer"><?php esc_html_e('Yes, we offer international shipping to most countries. International shipping typically takes 7-14 business days.', 'aqualuxe'); ?></div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question"><?php esc_html_e('How do I track my order?', 'aqualuxe'); ?></div>
                <div class="faq-answer"><?php esc_html_e('Once your order has been shipped, you will receive a tracking number via email. You can use this tracking number to track your order on our website or the carrier\'s website.', 'aqualuxe'); ?></div>
            </div>
        </div>
        <?php
    }
}

/**
 * Add product meta box.
 */
function aqualuxe_woocommerce_product_meta_box() {
    add_meta_box(
        'aqualuxe_product_options',
        __('AquaLuxe Product Options', 'aqualuxe'),
        'aqualuxe_woocommerce_product_meta_box_callback',
        'product',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_woocommerce_product_meta_box');

/**
 * Product meta box callback.
 *
 * @param WP_Post $post Post object.
 */
function aqualuxe_woocommerce_product_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_product_options', 'aqualuxe_product_options_nonce');
    
    // Get current values
    $custom_info = get_post_meta($post->ID, '_aqualuxe_custom_info', true);
    $shipping_info = get_post_meta($post->ID, '_aqualuxe_shipping_info', true);
    $faq = get_post_meta($post->ID, '_aqualuxe_faq', true);
    $size_guide = get_post_meta($post->ID, '_aqualuxe_size_guide', true);
    
    ?>
    <div class="aqualuxe-product-options">
        <div class="aqualuxe-product-option">
            <label for="aqualuxe_custom_info"><?php esc_html_e('Additional Information', 'aqualuxe'); ?></label>
            <?php wp_editor($custom_info, 'aqualuxe_custom_info', ['textarea_name' => 'aqualuxe_custom_info', 'textarea_rows' => 5]); ?>
            <p class="description"><?php esc_html_e('Enter additional information for the product.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="aqualuxe-product-option">
            <label for="aqualuxe_shipping_info"><?php esc_html_e('Shipping & Returns', 'aqualuxe'); ?></label>
            <?php wp_editor($shipping_info, 'aqualuxe_shipping_info', ['textarea_name' => 'aqualuxe_shipping_info', 'textarea_rows' => 5]); ?>
            <p class="description"><?php esc_html_e('Enter shipping and return information for the product.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="aqualuxe-product-option">
            <label for="aqualuxe_faq"><?php esc_html_e('FAQ', 'aqualuxe'); ?></label>
            <?php wp_editor($faq, 'aqualuxe_faq', ['textarea_name' => 'aqualuxe_faq', 'textarea_rows' => 5]); ?>
            <p class="description"><?php esc_html_e('Enter frequently asked questions for the product.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="aqualuxe-product-option">
            <label for="aqualuxe_size_guide"><?php esc_html_e('Size Guide', 'aqualuxe'); ?></label>
            <?php wp_editor($size_guide, 'aqualuxe_size_guide', ['textarea_name' => 'aqualuxe_size_guide', 'textarea_rows' => 5]); ?>
            <p class="description"><?php esc_html_e('Enter size guide information for the product.', 'aqualuxe'); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Save product meta box data.
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_woocommerce_save_product_meta_box($post_id) {
    // Check if nonce is set
    if (!isset($_POST['aqualuxe_product_options_nonce'])) {
        return;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['aqualuxe_product_options_nonce'], 'aqualuxe_product_options')) {
        return;
    }
    
    // Check if autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save custom info
    if (isset($_POST['aqualuxe_custom_info'])) {
        update_post_meta($post_id, '_aqualuxe_custom_info', wp_kses_post($_POST['aqualuxe_custom_info']));
    }
    
    // Save shipping info
    if (isset($_POST['aqualuxe_shipping_info'])) {
        update_post_meta($post_id, '_aqualuxe_shipping_info', wp_kses_post($_POST['aqualuxe_shipping_info']));
    }
    
    // Save FAQ
    if (isset($_POST['aqualuxe_faq'])) {
        update_post_meta($post_id, '_aqualuxe_faq', wp_kses_post($_POST['aqualuxe_faq']));
    }
    
    // Save size guide
    if (isset($_POST['aqualuxe_size_guide'])) {
        update_post_meta($post_id, '_aqualuxe_size_guide', wp_kses_post($_POST['aqualuxe_size_guide']));
    }
}
add_action('save_post', 'aqualuxe_woocommerce_save_product_meta_box');

/**
 * Add currency switcher.
 */
function aqualuxe_woocommerce_currency_switcher() {
    if (aqualuxe_is_module_active('multicurrency')) {
        aqualuxe_get_module_template_part('multicurrency', 'currency-switcher');
    }
}
add_action('woocommerce_before_shop_loop', 'aqualuxe_woocommerce_currency_switcher', 15);
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_currency_switcher', 11);

/**
 * Add product video.
 */
function aqualuxe_woocommerce_product_video() {
    global $product;
    
    $video_url = get_post_meta($product->get_id(), '_aqualuxe_product_video', true);
    
    if ($video_url) {
        ?>
        <div class="product-video">
            <h3><?php esc_html_e('Product Video', 'aqualuxe'); ?></h3>
            <div class="video-container">
                <?php
                if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                    // YouTube video
                    $video_id = '';
                    
                    if (strpos($video_url, 'youtube.com/watch?v=') !== false) {
                        $video_id = str_replace('https://www.youtube.com/watch?v=', '', $video_url);
                    } elseif (strpos($video_url, 'youtu.be/') !== false) {
                        $video_id = str_replace('https://youtu.be/', '', $video_url);
                    }
                    
                    if ($video_id) {
                        echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . esc_attr($video_id) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                    }
                } elseif (strpos($video_url, 'vimeo.com') !== false) {
                    // Vimeo video
                    $video_id = str_replace('https://vimeo.com/', '', $video_url);
                    
                    if ($video_id) {
                        echo '<iframe src="https://player.vimeo.com/video/' . esc_attr($video_id) . '" width="560" height="315" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
                    }
                } else {
                    // Other video
                    echo do_shortcode('[video src="' . esc_url($video_url) . '"]');
                }
                ?>
            </div>
        </div>
        <?php
    }
}
add_action('woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_video', 15);

/**
 * Add product video meta box.
 */
function aqualuxe_woocommerce_product_video_meta_box() {
    add_meta_box(
        'aqualuxe_product_video',
        __('Product Video', 'aqualuxe'),
        'aqualuxe_woocommerce_product_video_meta_box_callback',
        'product',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'aqualuxe_woocommerce_product_video_meta_box');

/**
 * Product video meta box callback.
 *
 * @param WP_Post $post Post object.
 */
function aqualuxe_woocommerce_product_video_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_product_video', 'aqualuxe_product_video_nonce');
    
    // Get current value
    $video_url = get_post_meta($post->ID, '_aqualuxe_product_video', true);
    
    ?>
    <p>
        <label for="aqualuxe_product_video"><?php esc_html_e('Video URL', 'aqualuxe'); ?></label>
        <input type="url" id="aqualuxe_product_video" name="aqualuxe_product_video" value="<?php echo esc_url($video_url); ?>" class="widefat" />
        <span class="description"><?php esc_html_e('Enter the URL of the product video (YouTube, Vimeo, or direct video file).', 'aqualuxe'); ?></span>
    </p>
    <?php
}

/**
 * Save product video meta box data.
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_woocommerce_save_product_video_meta_box($post_id) {
    // Check if nonce is set
    if (!isset($_POST['aqualuxe_product_video_nonce'])) {
        return;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['aqualuxe_product_video_nonce'], 'aqualuxe_product_video')) {
        return;
    }
    
    // Check if autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save video URL
    if (isset($_POST['aqualuxe_product_video'])) {
        update_post_meta($post_id, '_aqualuxe_product_video', esc_url_raw($_POST['aqualuxe_product_video']));
    }
}
add_action('save_post', 'aqualuxe_woocommerce_save_product_video_meta_box');

/**
 * Add product 360 view.
 */
function aqualuxe_woocommerce_product_360_view() {
    global $product;
    
    $images = get_post_meta($product->get_id(), '_aqualuxe_product_360_images', true);
    
    if ($images) {
        $images = explode(',', $images);
        
        if (!empty($images)) {
            ?>
            <div class="product-360-view">
                <button class="button view-360-toggle">
                    <i class="fas fa-sync-alt"></i>
                    <span><?php esc_html_e('360° View', 'aqualuxe'); ?></span>
                </button>
                
                <div class="view-360-modal">
                    <div class="view-360-content">
                        <div class="view-360-header">
                            <h3><?php esc_html_e('360° View', 'aqualuxe'); ?></h3>
                            <button class="close-view-360">&times;</button>
                        </div>
                        
                        <div class="view-360-body">
                            <div class="view-360-container" data-images="<?php echo esc_attr(implode(',', $images)); ?>">
                                <div class="spinner">
                                    <span class="spinner-inner"></span>
                                </div>
                                <div class="view-360-image-container">
                                    <img src="<?php echo esc_url(wp_get_attachment_url($images[0])); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" class="view-360-image" />
                                </div>
                                <div class="view-360-controls">
                                    <button class="view-360-prev"><i class="fas fa-chevron-left"></i></button>
                                    <button class="view-360-next"><i class="fas fa-chevron-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_360_view', 21);

/**
 * Add product 360 view meta box.
 */
function aqualuxe_woocommerce_product_360_view_meta_box() {
    add_meta_box(
        'aqualuxe_product_360_view',
        __('Product 360° View', 'aqualuxe'),
        'aqualuxe_woocommerce_product_360_view_meta_box_callback',
        'product',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'aqualuxe_woocommerce_product_360_view_meta_box');

/**
 * Product 360 view meta box callback.
 *
 * @param WP_Post $post Post object.
 */
function aqualuxe_woocommerce_product_360_view_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_product_360_view', 'aqualuxe_product_360_view_nonce');
    
    // Get current value
    $images = get_post_meta($post->ID, '_aqualuxe_product_360_images', true);
    
    ?>
    <div class="aqualuxe-product-360-view">
        <p><?php esc_html_e('Select images for the 360° view. The images should be in sequence.', 'aqualuxe'); ?></p>
        
        <div class="aqualuxe-product-360-images">
            <input type="hidden" id="aqualuxe_product_360_images" name="aqualuxe_product_360_images" value="<?php echo esc_attr($images); ?>" />
            <button type="button" class="button aqualuxe-product-360-upload"><?php esc_html_e('Upload Images', 'aqualuxe'); ?></button>
            
            <div class="aqualuxe-product-360-preview">
                <?php
                if ($images) {
                    $images = explode(',', $images);
                    
                    foreach ($images as $image_id) {
                        $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                        
                        if ($image_url) {
                            echo '<div class="aqualuxe-product-360-image" data-id="' . esc_attr($image_id) . '">';
                            echo '<img src="' . esc_url($image_url) . '" alt="" />';
                            echo '<button type="button" class="aqualuxe-product-360-remove">&times;</button>';
                            echo '</div>';
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
    
    <script>
        jQuery(document).ready(function($) {
            // Upload images
            $('.aqualuxe-product-360-upload').on('click', function(e) {
                e.preventDefault();
                
                var button = $(this);
                var preview = $('.aqualuxe-product-360-preview');
                var input = $('#aqualuxe_product_360_images');
                
                var frame = wp.media({
                    title: '<?php esc_html_e('Select 360° View Images', 'aqualuxe'); ?>',
                    button: {
                        text: '<?php esc_html_e('Select', 'aqualuxe'); ?>'
                    },
                    multiple: true
                });
                
                frame.on('select', function() {
                    var attachments = frame.state().get('selection').toJSON();
                    var ids = [];
                    
                    // Get existing IDs
                    if (input.val()) {
                        ids = input.val().split(',');
                    }
                    
                    // Add new images
                    $.each(attachments, function(i, attachment) {
                        ids.push(attachment.id);
                        
                        preview.append(
                            '<div class="aqualuxe-product-360-image" data-id="' + attachment.id + '">' +
                            '<img src="' + attachment.sizes.thumbnail.url + '" alt="" />' +
                            '<button type="button" class="aqualuxe-product-360-remove">&times;</button>' +
                            '</div>'
                        );
                    });
                    
                    // Update input value
                    input.val(ids.join(','));
                });
                
                frame.open();
            });
            
            // Remove image
            $(document).on('click', '.aqualuxe-product-360-remove', function(e) {
                e.preventDefault();
                
                var button = $(this);
                var image = button.closest('.aqualuxe-product-360-image');
                var id = image.data('id');
                var input = $('#aqualuxe_product_360_images');
                var ids = input.val().split(',');
                
                // Remove ID from array
                ids = ids.filter(function(value) {
                    return value != id;
                });
                
                // Update input value
                input.val(ids.join(','));
                
                // Remove image
                image.remove();
            });
            
            // Sort images
            $('.aqualuxe-product-360-preview').sortable({
                update: function(event, ui) {
                    var ids = [];
                    
                    $('.aqualuxe-product-360-image').each(function() {
                        ids.push($(this).data('id'));
                    });
                    
                    // Update input value
                    $('#aqualuxe_product_360_images').val(ids.join(','));
                }
            });
        });
    </script>
    
    <style>
        .aqualuxe-product-360-preview {
            display: flex;
            flex-wrap: wrap;
            margin-top: 10px;
        }
        
        .aqualuxe-product-360-image {
            position: relative;
            margin: 5px;
            width: 80px;
            height: 80px;
            border: 1px solid #ddd;
        }
        
        .aqualuxe-product-360-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .aqualuxe-product-360-remove {
            position: absolute;
            top: 0;
            right: 0;
            background: #f44336;
            color: #fff;
            border: none;
            width: 20px;
            height: 20px;
            line-height: 1;
            cursor: pointer;
        }
    </style>
    <?php
}

/**
 * Save product 360 view meta box data.
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_woocommerce_save_product_360_view_meta_box($post_id) {
    // Check if nonce is set
    if (!isset($_POST['aqualuxe_product_360_view_nonce'])) {
        return;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['aqualuxe_product_360_view_nonce'], 'aqualuxe_product_360_view')) {
        return;
    }
    
    // Check if autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save 360 view images
    if (isset($_POST['aqualuxe_product_360_images'])) {
        update_post_meta($post_id, '_aqualuxe_product_360_images', sanitize_text_field($_POST['aqualuxe_product_360_images']));
    }
}
add_action('save_post', 'aqualuxe_woocommerce_save_product_360_view_meta_box');

/**
 * Add product badges.
 */
function aqualuxe_woocommerce_product_badges() {
    global $product;
    
    // Sale badge
    if ($product->is_on_sale()) {
        echo '<span class="product-badge sale">' . esc_html__('Sale', 'aqualuxe') . '</span>';
    }
    
    // New badge (products added in the last 30 days)
    $days_as_new = 30;
    $created_date = strtotime($product->get_date_created());
    
    if ((time() - (60 * 60 * 24 * $days_as_new)) < $created_date) {
        echo '<span class="product-badge new">' . esc_html__('New', 'aqualuxe') . '</span>';
    }
    
    // Featured badge
    if ($product->is_featured()) {
        echo '<span class="product-badge featured">' . esc_html__('Featured', 'aqualuxe') . '</span>';
    }
    
    // Out of stock badge
    if (!$product->is_in_stock()) {
        echo '<span class="product-badge out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
    }
    
    // Custom badge
    $custom_badge = get_post_meta($product->get_id(), '_aqualuxe_custom_badge', true);
    $custom_badge_color = get_post_meta($product->get_id(), '_aqualuxe_custom_badge_color', true);
    
    if ($custom_badge) {
        $style = '';
        
        if ($custom_badge_color) {
            $style = ' style="background-color: ' . esc_attr($custom_badge_color) . ';"';
        }
        
        echo '<span class="product-badge custom"' . $style . '>' . esc_html($custom_badge) . '</span>';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_product_badges', 10);
add_action('woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_product_badges', 10);

/**
 * Add custom badge meta box.
 */
function aqualuxe_woocommerce_custom_badge_meta_box() {
    add_meta_box(
        'aqualuxe_custom_badge',
        __('Custom Badge', 'aqualuxe'),
        'aqualuxe_woocommerce_custom_badge_meta_box_callback',
        'product',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'aqualuxe_woocommerce_custom_badge_meta_box');

/**
 * Custom badge meta box callback.
 *
 * @param WP_Post $post Post object.
 */
function aqualuxe_woocommerce_custom_badge_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_custom_badge', 'aqualuxe_custom_badge_nonce');
    
    // Get current values
    $custom_badge = get_post_meta($post->ID, '_aqualuxe_custom_badge', true);
    $custom_badge_color = get_post_meta($post->ID, '_aqualuxe_custom_badge_color', true);
    
    ?>
    <p>
        <label for="aqualuxe_custom_badge"><?php esc_html_e('Badge Text', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_custom_badge" name="aqualuxe_custom_badge" value="<?php echo esc_attr($custom_badge); ?>" class="widefat" />
    </p>
    
    <p>
        <label for="aqualuxe_custom_badge_color"><?php esc_html_e('Badge Color', 'aqualuxe'); ?></label>
        <input type="color" id="aqualuxe_custom_badge_color" name="aqualuxe_custom_badge_color" value="<?php echo esc_attr($custom_badge_color); ?>" class="widefat" />
    </p>
    <?php
}

/**
 * Save custom badge meta box data.
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_woocommerce_save_custom_badge_meta_box($post_id) {
    // Check if nonce is set
    if (!isset($_POST['aqualuxe_custom_badge_nonce'])) {
        return;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['aqualuxe_custom_badge_nonce'], 'aqualuxe_custom_badge')) {
        return;
    }
    
    // Check if autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save custom badge
    if (isset($_POST['aqualuxe_custom_badge'])) {
        update_post_meta($post_id, '_aqualuxe_custom_badge', sanitize_text_field($_POST['aqualuxe_custom_badge']));
    }
    
    // Save custom badge color
    if (isset($_POST['aqualuxe_custom_badge_color'])) {
        update_post_meta($post_id, '_aqualuxe_custom_badge_color', sanitize_hex_color($_POST['aqualuxe_custom_badge_color']));
    }
}
add_action('save_post', 'aqualuxe_woocommerce_save_custom_badge_meta_box');

/**
 * Add product brand.
 */
function aqualuxe_woocommerce_product_brand() {
    global $product;
    
    $brands = get_the_terms($product->get_id(), 'product_brand');
    
    if ($brands && !is_wp_error($brands)) {
        echo '<div class="product-brand">';
        
        foreach ($brands as $brand) {
            $brand_logo = get_term_meta($brand->term_id, 'brand_logo', true);
            
            if ($brand_logo) {
                echo '<a href="' . esc_url(get_term_link($brand)) . '" class="brand-logo">';
                echo wp_get_attachment_image($brand_logo, 'thumbnail');
                echo '</a>';
            } else {
                echo '<a href="' . esc_url(get_term_link($brand)) . '" class="brand-name">';
                echo esc_html($brand->name);
                echo '</a>';
            }
        }
        
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_brand', 5);

/**
 * Register product brand taxonomy.
 */
function aqualuxe_woocommerce_register_product_brand_taxonomy() {
    $labels = [
        'name' => __('Brands', 'aqualuxe'),
        'singular_name' => __('Brand', 'aqualuxe'),
        'search_items' => __('Search Brands', 'aqualuxe'),
        'all_items' => __('All Brands', 'aqualuxe'),
        'parent_item' => __('Parent Brand', 'aqualuxe'),
        'parent_item_colon' => __('Parent Brand:', 'aqualuxe'),
        'edit_item' => __('Edit Brand', 'aqualuxe'),
        'update_item' => __('Update Brand', 'aqualuxe'),
        'add_new_item' => __('Add New Brand', 'aqualuxe'),
        'new_item_name' => __('New Brand Name', 'aqualuxe'),
        'menu_name' => __('Brands', 'aqualuxe'),
    ];
    
    $args = [
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'brand'],
        'show_in_rest' => true,
    ];
    
    register_taxonomy('product_brand', ['product'], $args);
}
add_action('init', 'aqualuxe_woocommerce_register_product_brand_taxonomy');

/**
 * Add brand logo field to product brand taxonomy.
 */
function aqualuxe_woocommerce_add_product_brand_logo_field() {
    ?>
    <div class="form-field term-brand-logo-wrap">
        <label for="brand_logo"><?php esc_html_e('Brand Logo', 'aqualuxe'); ?></label>
        <div class="brand-logo-preview"></div>
        <input type="hidden" id="brand_logo" name="brand_logo" value="" />
        <button type="button" class="button upload-brand-logo"><?php esc_html_e('Upload Logo', 'aqualuxe'); ?></button>
        <button type="button" class="button remove-brand-logo" style="display: none;"><?php esc_html_e('Remove Logo', 'aqualuxe'); ?></button>
        <p class="description"><?php esc_html_e('Upload a logo for this brand.', 'aqualuxe'); ?></p>
    </div>
    
    <script>
        jQuery(document).ready(function($) {
            // Upload logo
            $('.upload-brand-logo').on('click', function(e) {
                e.preventDefault();
                
                var button = $(this);
                var preview = $('.brand-logo-preview');
                var input = $('#brand_logo');
                var remove_button = $('.remove-brand-logo');
                
                var frame = wp.media({
                    title: '<?php esc_html_e('Select Brand Logo', 'aqualuxe'); ?>',
                    button: {
                        text: '<?php esc_html_e('Select', 'aqualuxe'); ?>'
                    },
                    multiple: false
                });
                
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    
                    preview.html('<img src="' + attachment.url + '" alt="" style="max-width: 100px; max-height: 100px;" />');
                    input.val(attachment.id);
                    remove_button.show();
                });
                
                frame.open();
            });
            
            // Remove logo
            $('.remove-brand-logo').on('click', function(e) {
                e.preventDefault();
                
                var button = $(this);
                var preview = $('.brand-logo-preview');
                var input = $('#brand_logo');
                
                preview.html('');
                input.val('');
                button.hide();
            });
        });
    </script>
    <?php
}
add_action('product_brand_add_form_fields', 'aqualuxe_woocommerce_add_product_brand_logo_field');

/**
 * Edit brand logo field in product brand taxonomy.
 *
 * @param WP_Term $term Term object.
 */
function aqualuxe_woocommerce_edit_product_brand_logo_field($term) {
    $brand_logo = get_term_meta($term->term_id, 'brand_logo', true);
    ?>
    <tr class="form-field term-brand-logo-wrap">
        <th scope="row"><label for="brand_logo"><?php esc_html_e('Brand Logo', 'aqualuxe'); ?></label></th>
        <td>
            <div class="brand-logo-preview">
                <?php if ($brand_logo) : ?>
                    <?php echo wp_get_attachment_image($brand_logo, 'thumbnail', false, ['style' => 'max-width: 100px; max-height: 100px;']); ?>
                <?php endif; ?>
            </div>
            <input type="hidden" id="brand_logo" name="brand_logo" value="<?php echo esc_attr($brand_logo); ?>" />
            <button type="button" class="button upload-brand-logo"><?php esc_html_e('Upload Logo', 'aqualuxe'); ?></button>
            <button type="button" class="button remove-brand-logo" <?php echo $brand_logo ? '' : 'style="display: none;"'; ?>><?php esc_html_e('Remove Logo', 'aqualuxe'); ?></button>
            <p class="description"><?php esc_html_e('Upload a logo for this brand.', 'aqualuxe'); ?></p>
        </td>
    </tr>
    
    <script>
        jQuery(document).ready(function($) {
            // Upload logo
            $('.upload-brand-logo').on('click', function(e) {
                e.preventDefault();
                
                var button = $(this);
                var preview = $('.brand-logo-preview');
                var input = $('#brand_logo');
                var remove_button = $('.remove-brand-logo');
                
                var frame = wp.media({
                    title: '<?php esc_html_e('Select Brand Logo', 'aqualuxe'); ?>',
                    button: {
                        text: '<?php esc_html_e('Select', 'aqualuxe'); ?>'
                    },
                    multiple: false
                });
                
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    
                    preview.html('<img src="' + attachment.url + '" alt="" style="max-width: 100px; max-height: 100px;" />');
                    input.val(attachment.id);
                    remove_button.show();
                });
                
                frame.open();
            });
            
            // Remove logo
            $('.remove-brand-logo').on('click', function(e) {
                e.preventDefault();
                
                var button = $(this);
                var preview = $('.brand-logo-preview');
                var input = $('#brand_logo');
                
                preview.html('');
                input.val('');
                button.hide();
            });
        });
    </script>
    <?php
}
add_action('product_brand_edit_form_fields', 'aqualuxe_woocommerce_edit_product_brand_logo_field');

/**
 * Save brand logo field in product brand taxonomy.
 *
 * @param int $term_id Term ID.
 */
function aqualuxe_woocommerce_save_product_brand_logo_field($term_id) {
    if (isset($_POST['brand_logo'])) {
        update_term_meta($term_id, 'brand_logo', absint($_POST['brand_logo']));
    }
}
add_action('created_product_brand', 'aqualuxe_woocommerce_save_product_brand_logo_field');
add_action('edited_product_brand', 'aqualuxe_woocommerce_save_product_brand_logo_field');

/**
 * Add brand column to product list.
 *
 * @param array $columns Columns.
 * @return array
 */
function aqualuxe_woocommerce_product_brand_column($columns) {
    $new_columns = [];
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        
        if ($key === 'product_cat') {
            $new_columns['product_brand'] = __('Brand', 'aqualuxe');
        }
    }
    
    return $new_columns;
}
add_filter('manage_edit-product_columns', 'aqualuxe_woocommerce_product_brand_column');

/**
 * Add brand column content to product list.
 *
 * @param string $column Column name.
 * @param int $post_id Post ID.
 */
function aqualuxe_woocommerce_product_brand_column_content($column, $post_id) {
    if ($column === 'product_brand') {
        $brands = get_the_terms($post_id, 'product_brand');
        
        if ($brands && !is_wp_error($brands)) {
            $brand_names = [];
            
            foreach ($brands as $brand) {
                $brand_names[] = '<a href="' . esc_url(get_edit_term_link($brand->term_id, 'product_brand')) . '">' . esc_html($brand->name) . '</a>';
            }
            
            echo implode(', ', $brand_names);
        } else {
            echo '<span class="na">–</span>';
        }
    }
}
add_action('manage_product_posts_custom_column', 'aqualuxe_woocommerce_product_brand_column_content', 10, 2);

/**
 * Add brand filter to product list.
 */
function aqualuxe_woocommerce_product_brand_filter() {
    global $typenow;
    
    if ($typenow === 'product') {
        $current_brand = isset($_GET['product_brand']) ? $_GET['product_brand'] : '';
        $brands = get_terms(['taxonomy' => 'product_brand', 'hide_empty' => false]);
        
        if ($brands && !is_wp_error($brands)) {
            ?>
            <select name="product_brand" id="product_brand">
                <option value=""><?php esc_html_e('All Brands', 'aqualuxe'); ?></option>
                <?php foreach ($brands as $brand) : ?>
                    <option value="<?php echo esc_attr($brand->slug); ?>" <?php selected($current_brand, $brand->slug); ?>><?php echo esc_html($brand->name); ?></option>
                <?php endforeach; ?>
            </select>
            <?php
        }
    }
}
add_action('restrict_manage_posts', 'aqualuxe_woocommerce_product_brand_filter');

/**
 * Add brand widget.
 */
class AquaLuxe_Product_Brand_Widget extends WP_Widget {
    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_product_brand_widget',
            __('AquaLuxe Product Brands', 'aqualuxe'),
            ['description' => __('Display product brands.', 'aqualuxe')]
        );
    }
    
    /**
     * Widget output.
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : __('Product Brands', 'aqualuxe');
        $show_logo = !empty($instance['show_logo']) ? (bool) $instance['show_logo'] : false;
        $show_count = !empty($instance['show_count']) ? (bool) $instance['show_count'] : false;
        
        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        $brands = get_terms([
            'taxonomy' => 'product_brand',
            'hide_empty' => true,
        ]);
        
        if ($brands && !is_wp_error($brands)) {
            echo '<ul class="product-brands">';
            
            foreach ($brands as $brand) {
                echo '<li class="product-brand">';
                
                echo '<a href="' . esc_url(get_term_link($brand)) . '">';
                
                if ($show_logo) {
                    $brand_logo = get_term_meta($brand->term_id, 'brand_logo', true);
                    
                    if ($brand_logo) {
                        echo wp_get_attachment_image($brand_logo, 'thumbnail', false, ['class' => 'brand-logo']);
                    } else {
                        echo esc_html($brand->name);
                    }
                } else {
                    echo esc_html($brand->name);
                }
                
                if ($show_count) {
                    echo ' <span class="count">(' . esc_html($brand->count) . ')</span>';
                }
                
                echo '</a>';
                
                echo '</li>';
            }
            
            echo '</ul>';
        } else {
            echo '<p>' . esc_html__('No brands found.', 'aqualuxe') . '</p>';
        }
        
        echo $args['after_widget'];
    }
    
    /**
     * Widget form.
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Product Brands', 'aqualuxe');
        $show_logo = !empty($instance['show_logo']) ? (bool) $instance['show_logo'] : false;
        $show_count = !empty($instance['show_count']) ? (bool) $instance['show_count'] : false;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_logo); ?> id="<?php echo esc_attr($this->get_field_id('show_logo')); ?>" name="<?php echo esc_attr($this->get_field_name('show_logo')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_logo')); ?>"><?php esc_html_e('Show brand logo', 'aqualuxe'); ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_count); ?> id="<?php echo esc_attr($this->get_field_id('show_count')); ?>" name="<?php echo esc_attr($this->get_field_name('show_count')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_count')); ?>"><?php esc_html_e('Show product count', 'aqualuxe'); ?></label>
        </p>
        <?php
    }
    
    /**
     * Widget update.
     *
     * @param array $new_instance New instance.
     * @param array $old_instance Old instance.
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['show_logo'] = !empty($new_instance['show_logo']) ? 1 : 0;
        $instance['show_count'] = !empty($new_instance['show_count']) ? 1 : 0;
        
        return $instance;
    }
}

/**
 * Register brand widget.
 */
function aqualuxe_woocommerce_register_brand_widget() {
    register_widget('AquaLuxe_Product_Brand_Widget');
}
add_action('widgets_init', 'aqualuxe_woocommerce_register_brand_widget');

/**
 * Add brand filter widget.
 */
class AquaLuxe_Product_Brand_Filter_Widget extends WP_Widget {
    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_product_brand_filter_widget',
            __('AquaLuxe Product Brand Filter', 'aqualuxe'),
            ['description' => __('Filter products by brand.', 'aqualuxe')]
        );
    }
    
    /**
     * Widget output.
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     */
    public function widget($args, $instance) {
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }
        
        $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : __('Filter by Brand', 'aqualuxe');
        $show_logo = !empty($instance['show_logo']) ? (bool) $instance['show_logo'] : false;
        $show_count = !empty($instance['show_count']) ? (bool) $instance['show_count'] : false;
        
        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        $brands = get_terms([
            'taxonomy' => 'product_brand',
            'hide_empty' => true,
        ]);
        
        if ($brands && !is_wp_error($brands)) {
            $current_brand = isset($_GET['filter_brand']) ? sanitize_title($_GET['filter_brand']) : '';
            
            echo '<ul class="product-brand-filter">';
            
            foreach ($brands as $brand) {
                $brand_url = add_query_arg('filter_brand', $brand->slug, get_pagenum_link(1, false));
                $active_class = $current_brand === $brand->slug ? ' class="active"' : '';
                
                echo '<li' . $active_class . '>';
                
                echo '<a href="' . esc_url($brand_url) . '">';
                
                if ($show_logo) {
                    $brand_logo = get_term_meta($brand->term_id, 'brand_logo', true);
                    
                    if ($brand_logo) {
                        echo wp_get_attachment_image($brand_logo, 'thumbnail', false, ['class' => 'brand-logo']);
                    } else {
                        echo esc_html($brand->name);
                    }
                } else {
                    echo esc_html($brand->name);
                }
                
                if ($show_count) {
                    echo ' <span class="count">(' . esc_html($brand->count) . ')</span>';
                }
                
                echo '</a>';
                
                echo '</li>';
            }
            
            if ($current_brand) {
                echo '<li class="reset-filter">';
                echo '<a href="' . esc_url(remove_query_arg('filter_brand', get_pagenum_link(1, false))) . '">';
                echo esc_html__('Reset Filter', 'aqualuxe');
                echo '</a>';
                echo '</li>';
            }
            
            echo '</ul>';
        } else {
            echo '<p>' . esc_html__('No brands found.', 'aqualuxe') . '</p>';
        }
        
        echo $args['after_widget'];
    }
    
    /**
     * Widget form.
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Filter by Brand', 'aqualuxe');
        $show_logo = !empty($instance['show_logo']) ? (bool) $instance['show_logo'] : false;
        $show_count = !empty($instance['show_count']) ? (bool) $instance['show_count'] : false;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_logo); ?> id="<?php echo esc_attr($this->get_field_id('show_logo')); ?>" name="<?php echo esc_attr($this->get_field_name('show_logo')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_logo')); ?>"><?php esc_html_e('Show brand logo', 'aqualuxe'); ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_count); ?> id="<?php echo esc_attr($this->get_field_id('show_count')); ?>" name="<?php echo esc_attr($this->get_field_name('show_count')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_count')); ?>"><?php esc_html_e('Show product count', 'aqualuxe'); ?></label>
        </p>
        <?php
    }
    
    /**
     * Widget update.
     *
     * @param array $new_instance New instance.
     * @param array $old_instance Old instance.
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['show_logo'] = !empty($new_instance['show_logo']) ? 1 : 0;
        $instance['show_count'] = !empty($new_instance['show_count']) ? 1 : 0;
        
        return $instance;
    }
}

/**
 * Register brand filter widget.
 */
function aqualuxe_woocommerce_register_brand_filter_widget() {
    register_widget('AquaLuxe_Product_Brand_Filter_Widget');
}
add_action('widgets_init', 'aqualuxe_woocommerce_register_brand_filter_widget');

/**
 * Filter products by brand.
 *
 * @param WP_Query $query Query object.
 */
function aqualuxe_woocommerce_filter_products_by_brand($query) {
    if (!is_admin() && $query->is_main_query() && (is_shop() || is_product_category() || is_product_tag())) {
        if (isset($_GET['filter_brand']) && !empty($_GET['filter_brand'])) {
            $tax_query = $query->get('tax_query');
            
            if (!is_array($tax_query)) {
                $tax_query = [];
            }
            
            $tax_query[] = [
                'taxonomy' => 'product_brand',
                'field' => 'slug',
                'terms' => sanitize_title($_GET['filter_brand']),
            ];
            
            $query->set('tax_query', $tax_query);
        }
    }
}
add_action('pre_get_posts', 'aqualuxe_woocommerce_filter_products_by_brand');

/**
 * Add brand to product structured data.
 *
 * @param array $markup Structured data markup.
 * @param WP_Post $product Product object.
 * @return array
 */
function aqualuxe_woocommerce_product_structured_data_brand($markup, $product) {
    if (isset($markup['Product'])) {
        $brands = get_the_terms($product->get_id(), 'product_brand');
        
        if ($brands && !is_wp_error($brands)) {
            $markup['Product']['brand'] = [
                '@type' => 'Brand',
                'name' => $brands[0]->name,
            ];
        }
    }
    
    return $markup;
}
add_filter('woocommerce_structured_data_product', 'aqualuxe_woocommerce_product_structured_data_brand', 10, 2);

/**
 * Add brand to breadcrumbs.
 *
 * @param array $crumbs Breadcrumbs.
 * @return array
 */
function aqualuxe_woocommerce_product_breadcrumbs_brand($crumbs) {
    if (is_product()) {
        global $product;
        
        if (!is_object($product)) {
            $product = wc_get_product(get_the_ID());
        }
        
        if ($product) {
            $brands = get_the_terms($product->get_id(), 'product_brand');
            
            if ($brands && !is_wp_error($brands)) {
                $brand = $brands[0];
                $brand_crumb = [get_term_link($brand), $brand->name, $brand->term_id];
                
                // Add brand before product name
                array_splice($crumbs, count($crumbs) - 1, 0, [$brand_crumb]);
            }
        }
    }
    
    return $crumbs;
}
add_filter('woocommerce_get_breadcrumb', 'aqualuxe_woocommerce_product_breadcrumbs_brand');

/**
 * Add brand to product tabs.
 *
 * @param array $tabs Product tabs.
 * @return array
 */
function aqualuxe_woocommerce_product_brand_tab($tabs) {
    global $product;
    
    $brands = get_the_terms($product->get_id(), 'product_brand');
    
    if ($brands && !is_wp_error($brands)) {
        $tabs['brand'] = [
            'title' => __('Brand', 'aqualuxe'),
            'priority' => 50,
            'callback' => 'aqualuxe_woocommerce_product_brand_tab_content',
        ];
    }
    
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'aqualuxe_woocommerce_product_brand_tab');

/**
 * Brand tab content.
 */
function aqualuxe_woocommerce_product_brand_tab_content() {
    global $product;
    
    $brands = get_the_terms($product->get_id(), 'product_brand');
    
    if ($brands && !is_wp_error($brands)) {
        $brand = $brands[0];
        $brand_logo = get_term_meta($brand->term_id, 'brand_logo', true);
        
        echo '<div class="brand-info">';
        
        if ($brand_logo) {
            echo '<div class="brand-logo">';
            echo wp_get_attachment_image($brand_logo, 'medium');
            echo '</div>';
        }
        
        echo '<h3 class="brand-name">' . esc_html($brand->name) . '</h3>';
        
        if ($brand->description) {
            echo '<div class="brand-description">' . wp_kses_post($brand->description) . '</div>';
        }
        
        echo '<p class="brand-link"><a href="' . esc_url(get_term_link($brand)) . '" class="button">' . sprintf(__('View all %s products', 'aqualuxe'), $brand->name) . '</a></p>';
        
        echo '</div>';
    }
}

/**
 * Add brand archive description.
 */
function aqualuxe_woocommerce_brand_archive_description() {
    if (is_tax('product_brand')) {
        $brand = get_queried_object();
        $brand_logo = get_term_meta($brand->term_id, 'brand_logo', true);
        
        echo '<div class="brand-archive-header">';
        
        if ($brand_logo) {
            echo '<div class="brand-logo">';
            echo wp_get_attachment_image($brand_logo, 'medium');
            echo '</div>';
        }
        
        echo '<h1 class="brand-title">' . esc_html($brand->name) . '</h1>';
        
        if ($brand->description) {
            echo '<div class="brand-description">' . wp_kses_post($brand->description) . '</div>';
        }
        
        echo '</div>';
    }
}
add_action('woocommerce_archive_description', 'aqualuxe_woocommerce_brand_archive_description');

/**
 * Add brand to product meta.
 */
function aqualuxe_woocommerce_product_meta_brand() {
    global $product;
    
    $brands = get_the_terms($product->get_id(), 'product_brand');
    
    if ($brands && !is_wp_error($brands)) {
        echo '<span class="product-meta-brand">';
        echo esc_html__('Brand:', 'aqualuxe') . ' ';
        
        $brand_links = [];
        
        foreach ($brands as $brand) {
            $brand_links[] = '<a href="' . esc_url(get_term_link($brand)) . '">' . esc_html($brand->name) . '</a>';
        }
        
        echo implode(', ', $brand_links);
        echo '</span>';
    }
}
add_action('woocommerce_product_meta_end', 'aqualuxe_woocommerce_product_meta_brand');

/**
 * Add brand to product filters.
 *
 * @param array $args Filter widget args.
 * @return array
 */
function aqualuxe_woocommerce_product_filter_brand($args) {
    $args['product_brand'] = [
        'taxonomy' => 'product_brand',
        'field' => 'slug',
        'terms' => [],
        'operator' => 'IN',
    ];
    
    return $args;
}
add_filter('woocommerce_product_query_tax_query', 'aqualuxe_woocommerce_product_filter_brand');