<?php
/**
 * WooCommerce Template Hooks
 *
 * @package AquaLuxe
 * @subpackage Modules/WooCommerce
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Content wrappers
 *
 * @see aqualuxe_wc_output_content_wrapper()
 * @see aqualuxe_wc_output_content_wrapper_end()
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_before_main_content', 'aqualuxe_wc_output_content_wrapper', 10);
add_action('woocommerce_after_main_content', 'aqualuxe_wc_output_content_wrapper_end', 10);

/**
 * Breadcrumbs
 *
 * @see aqualuxe_wc_breadcrumb()
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
add_action('woocommerce_before_main_content', 'aqualuxe_wc_breadcrumb', 20);

/**
 * Sale flash
 *
 * @see aqualuxe_wc_show_product_loop_sale_flash()
 * @see aqualuxe_wc_show_product_sale_flash()
 */
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_wc_show_product_loop_sale_flash', 10);
add_action('woocommerce_before_single_product_summary', 'aqualuxe_wc_show_product_sale_flash', 10);

/**
 * Product loop
 *
 * @see aqualuxe_wc_product_loop_start()
 * @see aqualuxe_wc_product_loop_end()
 * @see aqualuxe_wc_product_loop_item_start()
 * @see aqualuxe_wc_product_loop_item_end()
 * @see aqualuxe_wc_product_loop_item_title()
 * @see aqualuxe_wc_product_loop_item_price()
 * @see aqualuxe_wc_product_loop_item_rating()
 * @see aqualuxe_wc_product_loop_item_categories()
 * @see aqualuxe_wc_product_loop_item_description()
 * @see aqualuxe_wc_product_loop_item_actions()
 */
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

add_action('woocommerce_before_shop_loop_item', 'aqualuxe_wc_product_loop_item_start', 10);
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_wc_product_loop_item_end', 10);
add_action('woocommerce_shop_loop_item_title', 'aqualuxe_wc_product_loop_item_title', 10);
add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_wc_product_loop_item_rating', 5);
add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_wc_product_loop_item_price', 10);
add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_wc_product_loop_item_categories', 15);
add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_wc_product_loop_item_description', 20);
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_wc_product_loop_item_actions', 10);

/**
 * Product badges
 *
 * @see aqualuxe_wc_product_badges()
 */
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_wc_product_badges', 15);
add_action('woocommerce_before_single_product_summary', 'aqualuxe_wc_product_badges', 15);

/**
 * Single product
 *
 * @see aqualuxe_wc_single_product_summary_start()
 * @see aqualuxe_wc_single_product_summary_end()
 * @see aqualuxe_wc_single_product_title()
 * @see aqualuxe_wc_single_product_rating()
 * @see aqualuxe_wc_single_product_price()
 * @see aqualuxe_wc_single_product_excerpt()
 * @see aqualuxe_wc_single_product_add_to_cart()
 * @see aqualuxe_wc_single_product_meta()
 * @see aqualuxe_wc_single_product_sharing()
 * @see aqualuxe_wc_single_product_tabs()
 * @see aqualuxe_wc_single_product_upsells()
 * @see aqualuxe_wc_single_product_related()
 * @see aqualuxe_wc_single_product_recently_viewed()
 */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

add_action('woocommerce_single_product_summary', 'aqualuxe_wc_single_product_summary_start', 1);
add_action('woocommerce_single_product_summary', 'aqualuxe_wc_single_product_title', 5);
add_action('woocommerce_single_product_summary', 'aqualuxe_wc_single_product_rating', 10);
add_action('woocommerce_single_product_summary', 'aqualuxe_wc_single_product_price', 15);
add_action('woocommerce_single_product_summary', 'aqualuxe_wc_single_product_excerpt', 20);
add_action('woocommerce_single_product_summary', 'aqualuxe_wc_single_product_add_to_cart', 30);
add_action('woocommerce_single_product_summary', 'aqualuxe_wc_single_product_meta', 40);
add_action('woocommerce_single_product_summary', 'aqualuxe_wc_single_product_sharing', 50);
add_action('woocommerce_single_product_summary', 'aqualuxe_wc_single_product_summary_end', 100);

add_action('woocommerce_after_single_product_summary', 'aqualuxe_wc_single_product_tabs', 10);
add_action('woocommerce_after_single_product', 'aqualuxe_wc_single_product_recently_viewed', 20);

/**
 * Cart
 *
 * @see aqualuxe_wc_cart_wrapper_start()
 * @see aqualuxe_wc_cart_wrapper_end()
 * @see aqualuxe_wc_cart_cross_sells()
 */
add_action('woocommerce_before_cart', 'aqualuxe_wc_cart_wrapper_start', 10);
add_action('woocommerce_after_cart', 'aqualuxe_wc_cart_wrapper_end', 10);
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
add_action('woocommerce_after_cart', 'aqualuxe_wc_cart_cross_sells', 10);

/**
 * Checkout
 *
 * @see aqualuxe_wc_checkout_wrapper_start()
 * @see aqualuxe_wc_checkout_wrapper_end()
 * @see aqualuxe_wc_checkout_login_form()
 * @see aqualuxe_wc_checkout_coupon_form()
 */
add_action('woocommerce_before_checkout_form', 'aqualuxe_wc_checkout_wrapper_start', 5);
add_action('woocommerce_after_checkout_form', 'aqualuxe_wc_checkout_wrapper_end', 20);
remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10);
remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
add_action('woocommerce_before_checkout_form', 'aqualuxe_wc_checkout_login_form', 10);
add_action('woocommerce_before_checkout_form', 'aqualuxe_wc_checkout_coupon_form', 15);

/**
 * My account
 *
 * @see aqualuxe_wc_my_account_wrapper_start()
 * @see aqualuxe_wc_my_account_wrapper_end()
 * @see aqualuxe_wc_my_account_navigation()
 * @see aqualuxe_wc_my_account_content()
 */
add_action('woocommerce_before_account_navigation', 'aqualuxe_wc_my_account_wrapper_start', 10);
add_action('woocommerce_after_my_account', 'aqualuxe_wc_my_account_wrapper_end', 10);

/**
 * Mini cart
 *
 * @see aqualuxe_wc_mini_cart()
 */
add_action('aqualuxe_header_actions', 'aqualuxe_wc_mini_cart', 60);

/**
 * Quick view
 *
 * @see aqualuxe_wc_quick_view_button()
 * @see aqualuxe_wc_quick_view_modal()
 */
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_wc_quick_view_button', 7);
add_action('wp_footer', 'aqualuxe_wc_quick_view_modal');

/**
 * Wishlist
 *
 * @see aqualuxe_wc_wishlist_button()
 */
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_wc_wishlist_button', 8);
add_action('woocommerce_single_product_summary', 'aqualuxe_wc_wishlist_button', 35);

/**
 * Compare
 *
 * @see aqualuxe_wc_compare_button()
 */
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_wc_compare_button', 9);
add_action('woocommerce_single_product_summary', 'aqualuxe_wc_compare_button', 36);

/**
 * Size guide
 *
 * @see aqualuxe_wc_size_guide_button()
 * @see aqualuxe_wc_size_guide_modal()
 */
add_action('woocommerce_before_add_to_cart_form', 'aqualuxe_wc_size_guide_button', 5);
add_action('wp_footer', 'aqualuxe_wc_size_guide_modal');

/**
 * Product countdown
 *
 * @see aqualuxe_wc_product_countdown()
 */
add_action('woocommerce_single_product_summary', 'aqualuxe_wc_product_countdown', 16);

/**
 * Template functions
 */
if (!function_exists('aqualuxe_wc_output_content_wrapper')) {
    /**
     * Output the start of the page wrapper.
     */
    function aqualuxe_wc_output_content_wrapper() {
        // Get module
        $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
        
        // Get template
        $module->get_template_part('wrapper-start');
    }
}

if (!function_exists('aqualuxe_wc_output_content_wrapper_end')) {
    /**
     * Output the end of the page wrapper.
     */
    function aqualuxe_wc_output_content_wrapper_end() {
        // Get module
        $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
        
        // Get template
        $module->get_template_part('wrapper-end');
    }
}

if (!function_exists('aqualuxe_wc_breadcrumb')) {
    /**
     * Output the WooCommerce breadcrumb.
     */
    function aqualuxe_wc_breadcrumb() {
        woocommerce_breadcrumb([
            'delimiter' => '<span class="breadcrumb-separator">/</span>',
            'wrap_before' => '<nav class="woocommerce-breadcrumb">',
            'wrap_after' => '</nav>',
            'before' => '<span class="breadcrumb-item">',
            'after' => '</span>',
            'home' => esc_html__('Home', 'aqualuxe'),
        ]);
    }
}

if (!function_exists('aqualuxe_wc_show_product_loop_sale_flash')) {
    /**
     * Output the product sale flash.
     */
    function aqualuxe_wc_show_product_loop_sale_flash() {
        // Get module
        $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
        
        // Check if product badges is enabled
        if (!$module->get_option('product_badges', true)) {
            woocommerce_show_product_loop_sale_flash();
            return;
        }
        
        // Get template
        $module->get_template_part('product-badges');
    }
}

if (!function_exists('aqualuxe_wc_show_product_sale_flash')) {
    /**
     * Output the product sale flash.
     */
    function aqualuxe_wc_show_product_sale_flash() {
        // Get module
        $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
        
        // Check if product badges is enabled
        if (!$module->get_option('product_badges', true)) {
            woocommerce_show_product_sale_flash();
            return;
        }
        
        // Get template
        $module->get_template_part('product-badges');
    }
}

if (!function_exists('aqualuxe_wc_product_loop_item_start')) {
    /**
     * Output the start of the product loop item.
     */
    function aqualuxe_wc_product_loop_item_start() {
        // Get module
        $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
        
        // Get product card style
        $style = $module->get_option('product_card_style', 'standard');
        
        // Get template
        $module->get_template_part('product-card-start', $style);
    }
}

if (!function_exists('aqualuxe_wc_product_loop_item_end')) {
    /**
     * Output the end of the product loop item.
     */
    function aqualuxe_wc_product_loop_item_end() {
        // Get module
        $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
        
        // Get product card style
        $style = $module->get_option('product_card_style', 'standard');
        
        // Get template
        $module->get_template_part('product-card-end', $style);
    }
}

if (!function_exists('aqualuxe_wc_product_loop_item_title')) {
    /**
     * Output the product title.
     */
    function aqualuxe_wc_product_loop_item_title() {
        echo '<h2 class="woocommerce-loop-product__title"><a href="' . esc_url(get_the_permalink()) . '">' . get_the_title() . '</a></h2>';
    }
}

if (!function_exists('aqualuxe_wc_product_loop_item_rating')) {
    /**
     * Output the product rating.
     */
    function aqualuxe_wc_product_loop_item_rating() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        echo aqualuxe_wc_get_rating_html($product, false);
    }
}

if (!function_exists('aqualuxe_wc_product_loop_item_price')) {
    /**
     * Output the product price.
     */
    function aqualuxe_wc_product_loop_item_price() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        echo '<div class="price">' . aqualuxe_wc_get_price_html($product) . '</div>';
    }
}

if (!function_exists('aqualuxe_wc_product_loop_item_categories')) {
    /**
     * Output the product categories.
     */
    function aqualuxe_wc_product_loop_item_categories() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        echo aqualuxe_wc_get_categories_html($product);
    }
}

if (!function_exists('aqualuxe_wc_product_loop_item_description')) {
    /**
     * Output the product description.
     */
    function aqualuxe_wc_product_loop_item_description() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        // Get module
        $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
        
        // Get product card style
        $style = $module->get_option('product_card_style', 'standard');
        
        // Only show description for certain styles
        if ($style === 'standard' || $style === 'elegant') {
            return;
        }
        
        echo aqualuxe_wc_get_short_description_html($product, 100);
    }
}

if (!function_exists('aqualuxe_wc_product_loop_item_actions')) {
    /**
     * Output the product actions.
     */
    function aqualuxe_wc_product_loop_item_actions() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        // Get module
        $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
        
        // Get template
        $module->get_template_part('product-actions');
    }
}

if (!function_exists('aqualuxe_wc_product_badges')) {
    /**
     * Output the product badges.
     */
    function aqualuxe_wc_product_badges() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        // Get module
        $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
        
        // Check if product badges is enabled
        if (!$module->get_option('product_badges', true)) {
            return;
        }
        
        // Get template
        $module->get_template_part('product-badges');
    }
}

if (!function_exists('aqualuxe_wc_single_product_summary_start')) {
    /**
     * Output the start of the single product summary.
     */
    function aqualuxe_wc_single_product_summary_start() {
        echo '<div class="product-summary-inner">';
    }
}

if (!function_exists('aqualuxe_wc_single_product_summary_end')) {
    /**
     * Output the end of the single product summary.
     */
    function aqualuxe_wc_single_product_summary_end() {
        echo '</div>';
    }
}

if (!function_exists('aqualuxe_wc_single_product_title')) {
    /**
     * Output the single product title.
     */
    function aqualuxe_wc_single_product_title() {
        the_title('<h1 class="product_title entry-title">', '</h1>');
    }
}

if (!function_exists('aqualuxe_wc_single_product_rating')) {
    /**
     * Output the single product rating.
     */
    function aqualuxe_wc_single_product_rating() {
        global $product;
        
        if (!$product || !wc_review_ratings_enabled()) {
            return;
        }
        
        $rating_count = $product->get_rating_count();
        $review_count = $product->get_review_count();
        $average = $product->get_average_rating();
        
        if ($rating_count > 0) :
            ?>
            <div class="woocommerce-product-rating">
                <?php echo wc_get_rating_html($average, $rating_count); ?>
                <?php if ($review_count > 0) : ?>
                    <a href="#reviews" class="woocommerce-review-link" rel="nofollow">
                        (<?php printf(_n('%s customer review', '%s customer reviews', $review_count, 'aqualuxe'), '<span class="count">' . esc_html($review_count) . '</span>'); ?>)
                    </a>
                <?php endif; ?>
            </div>
            <?php
        endif;
    }
}

if (!function_exists('aqualuxe_wc_single_product_price')) {
    /**
     * Output the single product price.
     */
    function aqualuxe_wc_single_product_price() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        ?>
        <div class="price-wrapper">
            <p class="<?php echo esc_attr(apply_filters('woocommerce_product_price_class', 'price')); ?>">
                <?php echo aqualuxe_wc_get_price_html($product); ?>
            </p>
        </div>
        <?php
    }
}

if (!function_exists('aqualuxe_wc_single_product_excerpt')) {
    /**
     * Output the single product excerpt.
     */
    function aqualuxe_wc_single_product_excerpt() {
        global $post;
        
        $short_description = apply_filters('woocommerce_short_description', $post->post_excerpt);
        
        if (!$short_description) {
            return;
        }
        
        ?>
        <div class="woocommerce-product-details__short-description">
            <?php echo $short_description; ?>
        </div>
        <?php
    }
}

if (!function_exists('aqualuxe_wc_single_product_add_to_cart')) {
    /**
     * Output the single product add to cart area.
     */
    function aqualuxe_wc_single_product_add_to_cart() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        echo '<div class="product-add-to-cart">';
        woocommerce_template_single_add_to_cart();
        echo '</div>';
    }
}

if (!function_exists('aqualuxe_wc_single_product_meta')) {
    /**
     * Output the single product meta.
     */
    function aqualuxe_wc_single_product_meta() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        echo '<div class="product-meta">';
        woocommerce_template_single_meta();
        echo '</div>';
    }
}

if (!function_exists('aqualuxe_wc_single_product_sharing')) {
    /**
     * Output the single product sharing.
     */
    function aqualuxe_wc_single_product_sharing() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        echo aqualuxe_wc_get_social_sharing_html($product);
    }
}

if (!function_exists('aqualuxe_wc_single_product_tabs')) {
    /**
     * Output the single product tabs.
     */
    function aqualuxe_wc_single_product_tabs() {
        woocommerce_output_product_data_tabs();
    }
}

if (!function_exists('aqualuxe_wc_single_product_recently_viewed')) {
    /**
     * Output the single product recently viewed products.
     */
    function aqualuxe_wc_single_product_recently_viewed() {
        // Get module
        $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
        
        // Check if recently viewed products is enabled
        if (!$module->get_option('recently_viewed', true)) {
            return;
        }
        
        // Get recently viewed products
        $viewed_products = $module->get_recently_viewed();
        
        // Remove current product from list
        if (is_product()) {
            $product_id = get_the_ID();
            $viewed_products = array_diff($viewed_products, [$product_id]);
        }
        
        // Check if we have any products
        if (empty($viewed_products)) {
            return;
        }
        
        // Get template
        $module->get_template('recently-viewed.php', ['viewed_products' => $viewed_products]);
    }
}

if (!function_exists('aqualuxe_wc_cart_wrapper_start')) {
    /**
     * Output the start of the cart wrapper.
     */
    function aqualuxe_wc_cart_wrapper_start() {
        echo '<div class="aqualuxe-cart-wrapper">';
    }
}

if (!function_exists('aqualuxe_wc_cart_wrapper_end')) {
    /**
     * Output the end of the cart wrapper.
     */
    function aqualuxe_wc_cart_wrapper_end() {
        echo '</div>';
    }
}

if (!function_exists('aqualuxe_wc_cart_cross_sells')) {
    /**
     * Output the cart cross-sells.
     */
    function aqualuxe_wc_cart_cross_sells() {
        // Get module
        $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
        
        // Get products per row
        $products_per_row = $module->get_option('products_per_row', 3);
        
        woocommerce_cross_sell_display($products_per_row * 2, $products_per_row);
    }
}

if (!function_exists('aqualuxe_wc_checkout_wrapper_start')) {
    /**
     * Output the start of the checkout wrapper.
     */
    function aqualuxe_wc_checkout_wrapper_start() {
        echo '<div class="aqualuxe-checkout-wrapper">';
    }
}

if (!function_exists('aqualuxe_wc_checkout_wrapper_end')) {
    /**
     * Output the end of the checkout wrapper.
     */
    function aqualuxe_wc_checkout_wrapper_end() {
        echo '</div>';
    }
}

if (!function_exists('aqualuxe_wc_checkout_login_form')) {
    /**
     * Output the checkout login form.
     */
    function aqualuxe_wc_checkout_login_form() {
        if (is_user_logged_in() || 'no' === get_option('woocommerce_enable_checkout_login_reminder')) {
            return;
        }
        
        echo '<div class="aqualuxe-checkout-login-form">';
        woocommerce_checkout_login_form();
        echo '</div>';
    }
}

if (!function_exists('aqualuxe_wc_checkout_coupon_form')) {
    /**
     * Output the checkout coupon form.
     */
    function aqualuxe_wc_checkout_coupon_form() {
        if (!wc_coupons_enabled()) {
            return;
        }
        
        echo '<div class="aqualuxe-checkout-coupon-form">';
        woocommerce_checkout_coupon_form();
        echo '</div>';
    }
}

if (!function_exists('aqualuxe_wc_my_account_wrapper_start')) {
    /**
     * Output the start of the my account wrapper.
     */
    function aqualuxe_wc_my_account_wrapper_start() {
        echo '<div class="aqualuxe-my-account-wrapper">';
    }
}

if (!function_exists('aqualuxe_wc_my_account_wrapper_end')) {
    /**
     * Output the end of the my account wrapper.
     */
    function aqualuxe_wc_my_account_wrapper_end() {
        echo '</div>';
    }
}

if (!function_exists('aqualuxe_wc_mini_cart')) {
    /**
     * Output the mini cart.
     */
    function aqualuxe_wc_mini_cart() {
        // Get module
        $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
        
        // Get template
        $module->get_template_part('mini-cart');
    }
}

if (!function_exists('aqualuxe_wc_quick_view_button')) {
    /**
     * Output the quick view button.
     */
    function aqualuxe_wc_quick_view_button() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        // Get module
        $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
        
        // Check if quick view is enabled
        if (!$module->get_option('quick_view', true)) {
            return;
        }
        
        // Get template
        $module->get_template_part('quick-view-button');
    }
}

if (!function_exists('aqualuxe_wc_quick_view_modal')) {
    /**
     * Output the quick view modal.
     */
    function aqualuxe_wc_quick_view_modal() {
        // Get module
        $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
        
        // Check if quick view is enabled
        if (!$module->get_option('quick_view', true)) {
            return;
        }
        
        // Get template
        $module->get_template_part('quick-view-modal');
    }
}

if (!function_exists('aqualuxe_wc_wishlist_button')) {
    /**
     * Output the wishlist button.
     */
    function aqualuxe_wc_wishlist_button() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        // Get module
        $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
        
        // Check if wishlist is enabled
        if (!$module->get_option('wishlist', true)) {
            return;
        }
        
        // Get template
        $module->get_template_part('wishlist-button');
    }
}

if (!function_exists('aqualuxe_wc_compare_button')) {
    /**
     * Output the compare button.
     */
    function aqualuxe_wc_compare_button() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        // Get module
        $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
        
        // Check if compare is enabled
        if (!$module->get_option('compare', true)) {
            return;
        }
        
        // Get template
        $module->get_template_part('compare-button');
    }
}

if (!function_exists('aqualuxe_wc_size_guide_button')) {
    /**
     * Output the size guide button.
     */
    function aqualuxe_wc_size_guide_button() {
        global $product;
        
        if (!$product || !$product->is_type('variable')) {
            return;
        }
        
        // Get module
        $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
        
        // Check if size guide is enabled
        if (!$module->get_option('size_guide', true)) {
            return;
        }
        
        // Get template
        $module->get_template_part('size-guide-button');
    }
}

if (!function_exists('aqualuxe_wc_size_guide_modal')) {
    /**
     * Output the size guide modal.
     */
    function aqualuxe_wc_size_guide_modal() {
        // Check if we're on a product page
        if (!is_product()) {
            return;
        }
        
        global $product;
        
        if (!$product || !$product->is_type('variable')) {
            return;
        }
        
        // Get module
        $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
        
        // Check if size guide is enabled
        if (!$module->get_option('size_guide', true)) {
            return;
        }
        
        // Get template
        $module->get_template_part('size-guide-modal');
    }
}

if (!function_exists('aqualuxe_wc_product_countdown')) {
    /**
     * Output the product countdown.
     */
    function aqualuxe_wc_product_countdown() {
        global $product;
        
        if (!$product || !$product->is_on_sale()) {
            return;
        }
        
        $sale_end_date = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
        
        if (!$sale_end_date) {
            return;
        }
        
        echo aqualuxe_wc_get_countdown_html($product);
    }
}