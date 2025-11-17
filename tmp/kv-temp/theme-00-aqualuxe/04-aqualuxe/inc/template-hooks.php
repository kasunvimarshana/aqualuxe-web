<?php
/**
 * AquaLuxe Template Hooks
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom hooks to Storefront theme
 */

// Add custom header elements
add_action('storefront_header', 'aqualuxe_custom_header_elements', 35);

if (!function_exists('aqualuxe_custom_header_elements')) {
    /**
     * Add custom header elements
     */
    function aqualuxe_custom_header_elements() {
        ?>
        <div class="custom-header-elements">
            <?php aqualuxe_product_search_form(); ?>
            <?php aqualuxe_wishlist_button(); ?>
        </div>
        <?php
    }
}

// Add custom footer elements
add_action('storefront_footer', 'aqualuxe_custom_footer_elements', 35);

if (!function_exists('aqualuxe_custom_footer_elements')) {
    /**
     * Add custom footer elements
     */
    function aqualuxe_custom_footer_elements() {
        ?>
        <div class="custom-footer-elements">
            <?php aqualuxe_newsletter_signup_form(); ?>
            <?php aqualuxe_social_media_links(); ?>
        </div>
        <?php
    }
}

// Add sticky header
add_action('wp_body_open', 'aqualuxe_sticky_header');

if (!function_exists('aqualuxe_sticky_header')) {
    /**
     * Add sticky header
     */
    function aqualuxe_sticky_header() {
        if (get_theme_mod('aqualuxe_sticky_header', true)) {
            ?>
            <div class="sticky-header-wrapper">
                <?php aqualuxe_sticky_header(); ?>
            </div>
            <?php
        }
    }
}

// Add quick view button to product loop
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_quick_view_button_loop', 15);

if (!function_exists('aqualuxe_quick_view_button_loop')) {
    /**
     * Add quick view button to product loop
     */
    function aqualuxe_quick_view_button_loop() {
        global $product;
        aqualuxe_quick_view_button($product->get_id());
    }
}

// Add wishlist button to product loop
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_wishlist_button_loop', 16);

if (!function_exists('aqualuxe_wishlist_button_loop')) {
    /**
     * Add wishlist button to product loop
     */
    function aqualuxe_wishlist_button_loop() {
        global $product;
        aqualuxe_wishlist_button($product->get_id());
    }
}

// Add product filter to shop page
add_action('woocommerce_before_shop_loop', 'aqualuxe_product_filter_shop', 15);

if (!function_exists('aqualuxe_product_filter_shop')) {
    /**
     * Add product filter to shop page
     */
    function aqualuxe_product_filter_shop() {
        if (is_shop() || is_product_category()) {
            aqualuxe_product_filter();
        }
    }
}

// Add product view toggle to shop page
add_action('woocommerce_before_shop_loop', 'aqualuxe_product_view_toggle_shop', 25);

if (!function_exists('aqualuxe_product_view_toggle_shop')) {
    /**
     * Add product view toggle to shop page
     */
    function aqualuxe_product_view_toggle_shop() {
        if (is_shop() || is_product_category()) {
            aqualuxe_product_view_toggle();
        }
    }
}

// Add back to top button
add_action('wp_footer', 'aqualuxe_back_to_top_button_footer');

if (!function_exists('aqualuxe_back_to_top_button_footer')) {
    /**
     * Add back to top button to footer
     */
    function aqualuxe_back_to_top_button_footer() {
        aqualuxe_back_to_top_button();
    }
}

// Add custom CSS classes to body
add_filter('body_class', 'aqualuxe_body_classes');

if (!function_exists('aqualuxe_body_classes')) {
    /**
     * Add custom CSS classes to body
     */
    function aqualuxe_body_classes($classes) {
        // Add page layout class
        $classes[] = 'aqualuxe-theme';
        
        // Add product columns class
        $product_columns = get_theme_mod('aqualuxe_product_columns', 4);
        $classes[] = 'product-columns-' . $product_columns;
        
        // Add sticky header class
        if (get_theme_mod('aqualuxe_sticky_header', true)) {
            $classes[] = 'sticky-header-enabled';
        }
        
        return $classes;
    }
}

// Add custom CSS classes to product loop items
add_filter('post_class', 'aqualuxe_product_loop_classes');

if (!function_exists('aqualuxe_product_loop_classes')) {
    /**
     * Add custom CSS classes to product loop items
     */
    function aqualuxe_product_loop_classes($classes) {
        global $product;
        
        if (!$product) {
            return $classes;
        }
        
        // Add product type class
        $classes[] = 'product-type-' . $product->get_type();
        
        // Add stock status class
        if ($product->is_in_stock()) {
            $classes[] = 'in-stock';
        } else {
            $classes[] = 'out-of-stock';
        }
        
        // Add featured class
        if ($product->is_featured()) {
            $classes[] = 'featured';
        }
        
        // Add on sale class
        if ($product->is_on_sale()) {
            $classes[] = 'on-sale';
        }
        
        return $classes;
    }
}

// Modify WooCommerce breadcrumb defaults
add_filter('woocommerce_breadcrumb_defaults', 'aqualuxe_breadcrumb_defaults');

if (!function_exists('aqualuxe_breadcrumb_defaults')) {
    /**
     * Modify WooCommerce breadcrumb defaults
     */
    function aqualuxe_breadcrumb_defaults($defaults) {
        $defaults['delimiter'] = ' > ';
        $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb">';
        $defaults['wrap_after'] = '</nav>';
        $defaults['before'] = '';
        $defaults['after'] = '';
        $defaults['home'] = __('Home', 'aqualuxe');
        
        return $defaults;
    }
}

// Add custom content to single product page
add_action('woocommerce_single_product_summary', 'aqualuxe_single_product_custom_content', 25);

if (!function_exists('aqualuxe_single_product_custom_content')) {
    /**
     * Add custom content to single product page
     */
    function aqualuxe_single_product_custom_content() {
        ?>
        <div class="single-product-custom-content">
            <?php aqualuxe_quick_view_button(get_the_ID()); ?>
            <?php aqualuxe_wishlist_button(get_the_ID()); ?>
        </div>
        <?php
    }
}

// Add related products section
add_action('woocommerce_after_single_product_summary', 'aqualuxe_related_products_section', 15);

if (!function_exists('aqualuxe_related_products_section')) {
    /**
     * Add related products section
     */
    function aqualuxe_related_products_section() {
        aqualuxe_related_products();
    }
}

// Add upsell products section
add_action('woocommerce_after_single_product_summary', 'aqualuxe_upsell_products_section', 25);

if (!function_exists('aqualuxe_upsell_products_section')) {
    /**
     * Add upsell products section
     */
    function aqualuxe_upsell_products_section() {
        aqualuxe_upsell_products();
    }
}

// Modify add to cart button text
add_filter('woocommerce_product_add_to_cart_text', 'aqualuxe_add_to_cart_text', 10, 2);

if (!function_exists('aqualuxe_add_to_cart_text')) {
    /**
     * Modify add to cart button text
     */
    function aqualuxe_add_to_cart_text($text, $product) {
        if ($product->is_type('external')) {
            return __('Buy Now', 'aqualuxe');
        } elseif ($product->is_type('grouped')) {
            return __('View Products', 'aqualuxe');
        } elseif ($product->is_type('simple')) {
            if ($product->is_purchasable() && $product->is_in_stock()) {
                return __('Add to Cart', 'aqualuxe');
            } elseif (!$product->is_in_stock()) {
                return __('Out of Stock', 'aqualuxe');
            }
        }
        
        return $text;
    }
}

// Add custom content to cart page
add_action('woocommerce_before_cart_table', 'aqualuxe_cart_page_custom_content', 5);

if (!function_exists('aqualuxe_cart_page_custom_content')) {
    /**
     * Add custom content to cart page
     */
    function aqualuxe_cart_page_custom_content() {
        ?>
        <div class="cart-page-custom-content">
            <h2><?php _e('Continue Shopping', 'aqualuxe'); ?></h2>
            <p><?php _e('Find more products to add to your cart.', 'aqualuxe'); ?></p>
        </div>
        <?php
    }
}

// Add custom content to checkout page
add_action('woocommerce_before_checkout_form', 'aqualuxe_checkout_page_custom_content', 5);

if (!function_exists('aqualuxe_checkout_page_custom_content')) {
    /**
     * Add custom content to checkout page
     */
    function aqualuxe_checkout_page_custom_content() {
        ?>
        <div class="checkout-page-custom-content">
            <h2><?php _e('Secure Checkout', 'aqualuxe'); ?></h2>
            <p><?php _e('Your information is protected with 256-bit SSL encryption.', 'aqualuxe'); ?></p>
        </div>
        <?php
    }
}

// Add custom content to account page
add_action('woocommerce_before_my_account', 'aqualuxe_account_page_custom_content', 5);

if (!function_exists('aqualuxe_account_page_custom_content')) {
    /**
     * Add custom content to account page
     */
    function aqualuxe_account_page_custom_content() {
        ?>
        <div class="account-page-custom-content">
            <h2><?php _e('Welcome to Your Account', 'aqualuxe'); ?></h2>
            <p><?php _e('Manage your orders, wishlist, and account details.', 'aqualuxe'); ?></p>
        </div>
        <?php
    }
}

// Add custom content to footer
add_action('storefront_footer', 'aqualuxe_footer_custom_content', 40);

if (!function_exists('aqualuxe_footer_custom_content')) {
    /**
     * Add custom content to footer
     */
    function aqualuxe_footer_custom_content() {
        ?>
        <div class="footer-custom-content">
            <div class="footer-widgets">
                <?php dynamic_sidebar('footer-1'); ?>
                <?php dynamic_sidebar('footer-2'); ?>
                <?php dynamic_sidebar('footer-3'); ?>
                <?php dynamic_sidebar('footer-4'); ?>
            </div>
            <div class="footer-copyright">
                <?php aqualuxe_copyright_text(); ?>
            </div>
        </div>
        <?php
    }
}