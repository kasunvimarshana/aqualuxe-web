<?php
/**
 * WooCommerce template functions
 *
 * @package AquaLuxe
 */

/**
 * Get the shop sidebar template.
 */
function aqualuxe_woocommerce_get_sidebar() {
    if (is_shop() || is_product_category() || is_product_tag()) {
        $sidebar_position = get_theme_mod('aqualuxe_shop_sidebar_position', 'right');
        
        if ($sidebar_position !== 'none') {
            get_sidebar('shop');
        }
    } elseif (is_product()) {
        $sidebar_position = get_theme_mod('aqualuxe_product_sidebar_position', 'none');
        
        if ($sidebar_position !== 'none') {
            get_sidebar('shop');
        }
    }
}

/**
 * Modify the breadcrumb separator.
 *
 * @param array $defaults Default breadcrumb args.
 * @return array
 */
function aqualuxe_woocommerce_breadcrumb_defaults($defaults) {
    $defaults['delimiter'] = '<span class="breadcrumb-separator">/</span>';
    $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" ' . (is_single() ? 'itemprop="breadcrumb"' : '') . '>';
    $defaults['wrap_after'] = '</nav>';
    
    return $defaults;
}
add_filter('woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumb_defaults');

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
 * Products per page.
 *
 * @return integer number of products.
 */
function aqualuxe_woocommerce_products_per_page() {
    return get_theme_mod('aqualuxe_products_per_page', 12);
}
add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page');

/**
 * Product gallery thumbnail columns.
 *
 * @return integer number of columns.
 */
function aqualuxe_woocommerce_thumbnail_columns() {
    return get_theme_mod('aqualuxe_product_gallery_columns', 4);
}
add_filter('woocommerce_product_thumbnails_columns', 'aqualuxe_woocommerce_thumbnail_columns');

/**
 * Default loop columns on product archives.
 *
 * @return integer products per row.
 */
function aqualuxe_woocommerce_loop_columns() {
    return get_theme_mod('aqualuxe_products_per_row', 3);
}
add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_columns');

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function aqualuxe_woocommerce_related_products_args($args) {
    $defaults = array(
        'posts_per_page' => get_theme_mod('aqualuxe_related_products_count', 4),
        'columns'        => get_theme_mod('aqualuxe_products_per_row', 3),
    );

    $args = wp_parse_args($defaults, $args);

    return $args;
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args');

/**
 * Upsell Products Args.
 *
 * @param array $args upsell products args.
 * @return array $args upsell products args.
 */
function aqualuxe_woocommerce_upsell_products_args($args) {
    $defaults = array(
        'posts_per_page' => get_theme_mod('aqualuxe_upsells_count', 4),
        'columns'        => get_theme_mod('aqualuxe_products_per_row', 3),
    );

    $args = wp_parse_args($defaults, $args);

    return $args;
}
add_filter('woocommerce_upsell_display_args', 'aqualuxe_woocommerce_upsell_products_args');

/**
 * Cross-sells Products Args.
 *
 * @param array $args cross-sell products args.
 * @return array $args cross-sell products args.
 */
function aqualuxe_woocommerce_cross_sells_products_args($args) {
    $defaults = array(
        'posts_per_page' => get_theme_mod('aqualuxe_cross_sells_count', 4),
        'columns'        => get_theme_mod('aqualuxe_products_per_row', 3),
    );

    $args = wp_parse_args($defaults, $args);

    return $args;
}
add_filter('woocommerce_cross_sells_columns', function() { return get_theme_mod('aqualuxe_products_per_row', 3); });
add_filter('woocommerce_cross_sells_total', function() { return get_theme_mod('aqualuxe_cross_sells_count', 4); });

/**
 * Add custom classes to product loop items.
 *
 * @param array $classes Array of class names.
 * @return array
 */
function aqualuxe_woocommerce_product_loop_classes($classes) {
    $classes[] = 'aqualuxe-product';
    
    // Add class based on shop layout
    $shop_layout = get_theme_mod('aqualuxe_shop_layout', 'grid');
    $classes[] = 'aqualuxe-product-' . $shop_layout;
    
    return $classes;
}
add_filter('woocommerce_post_class', 'aqualuxe_woocommerce_product_loop_classes', 10, 1);

/**
 * Add custom classes to single product.
 *
 * @param array $classes Array of class names.
 * @return array
 */
function aqualuxe_woocommerce_single_product_classes($classes) {
    if (is_product()) {
        $classes[] = 'aqualuxe-single-product';
    }
    
    return $classes;
}
add_filter('post_class', 'aqualuxe_woocommerce_single_product_classes', 10, 1);

/**
 * Add custom classes to product images.
 *
 * @param array $classes Array of class names.
 * @return array
 */
function aqualuxe_woocommerce_product_image_classes($classes) {
    $classes[] = 'aqualuxe-product-image';
    
    return $classes;
}
add_filter('woocommerce_single_product_image_gallery_classes', 'aqualuxe_woocommerce_product_image_classes', 10, 1);

/**
 * Add custom classes to product thumbnails.
 *
 * @param string $html Product thumbnail HTML.
 * @return string
 */
function aqualuxe_woocommerce_product_thumbnail_html($html) {
    return str_replace('attachment-woocommerce_thumbnail', 'attachment-woocommerce_thumbnail aqualuxe-product-thumbnail', $html);
}
add_filter('woocommerce_product_get_image', 'aqualuxe_woocommerce_product_thumbnail_html', 10, 1);

/**
 * Add custom classes to add to cart button.
 *
 * @param array $args Array of arguments.
 * @return array
 */
function aqualuxe_woocommerce_add_to_cart_args($args) {
    $args['class'] .= ' aqualuxe-add-to-cart-button';
    
    return $args;
}
add_filter('woocommerce_loop_add_to_cart_args', 'aqualuxe_woocommerce_add_to_cart_args', 10, 1);

/**
 * Add custom classes to product price.
 *
 * @param string $price Product price HTML.
 * @return string
 */
function aqualuxe_woocommerce_product_price_class($price) {
    return str_replace('price', 'price aqualuxe-product-price', $price);
}
add_filter('woocommerce_get_price_html', 'aqualuxe_woocommerce_product_price_class', 10, 1);

/**
 * Add custom classes to product rating.
 *
 * @param string $html Product rating HTML.
 * @return string
 */
function aqualuxe_woocommerce_product_rating_class($html) {
    return str_replace('woocommerce-product-rating', 'woocommerce-product-rating aqualuxe-product-rating', $html);
}
add_filter('woocommerce_product_get_rating_html', 'aqualuxe_woocommerce_product_rating_class', 10, 1);

/**
 * Add custom classes to product title.
 *
 * @param string $title Product title HTML.
 * @return string
 */
function aqualuxe_woocommerce_product_title_class($title) {
    return str_replace('woocommerce-loop-product__title', 'woocommerce-loop-product__title aqualuxe-product-title', $title);
}
add_filter('woocommerce_product_loop_title_classes', 'aqualuxe_woocommerce_product_title_class', 10, 1);

/**
 * Add custom classes to product link.
 *
 * @param string $html Product link HTML.
 * @return string
 */
function aqualuxe_woocommerce_product_link_class($html) {
    return str_replace('woocommerce-LoopProduct-link', 'woocommerce-LoopProduct-link aqualuxe-product-link', $html);
}
add_filter('woocommerce_loop_product_link', 'aqualuxe_woocommerce_product_link_class', 10, 1);

/**
 * Add custom classes to product categories.
 *
 * @param string $html Product categories HTML.
 * @return string
 */
function aqualuxe_woocommerce_product_categories_class($html) {
    return str_replace('posted_in', 'posted_in aqualuxe-product-categories', $html);
}
add_filter('woocommerce_get_product_categories', 'aqualuxe_woocommerce_product_categories_class', 10, 1);

/**
 * Add custom classes to product tags.
 *
 * @param string $html Product tags HTML.
 * @return string
 */
function aqualuxe_woocommerce_product_tags_class($html) {
    return str_replace('tagged_as', 'tagged_as aqualuxe-product-tags', $html);
}
add_filter('woocommerce_get_product_tag_list', 'aqualuxe_woocommerce_product_tags_class', 10, 1);

/**
 * Add custom classes to product tabs.
 *
 * @param array $tabs Product tabs.
 * @return array
 */
function aqualuxe_woocommerce_product_tabs_class($tabs) {
    foreach ($tabs as $key => $tab) {
        $tabs[$key]['class'][] = 'aqualuxe-product-tab';
    }
    
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs_class', 10, 1);

/**
 * Add custom classes to product reviews.
 *
 * @param array $comment_classes Comment classes.
 * @return array
 */
function aqualuxe_woocommerce_product_review_class($comment_classes) {
    $comment_classes[] = 'aqualuxe-product-review';
    
    return $comment_classes;
}
add_filter('comment_class', 'aqualuxe_woocommerce_product_review_class', 10, 1);

/**
 * Add custom classes to product review form.
 *
 * @param array $args Review form args.
 * @return array
 */
function aqualuxe_woocommerce_product_review_form_class($args) {
    $args['class_form'] .= ' aqualuxe-product-review-form';
    
    return $args;
}
add_filter('woocommerce_product_review_comment_form_args', 'aqualuxe_woocommerce_product_review_form_class', 10, 1);

/**
 * Add custom classes to product review submit button.
 *
 * @param array $args Review form submit button args.
 * @return array
 */
function aqualuxe_woocommerce_product_review_submit_button_class($args) {
    $args['class_submit'] .= ' aqualuxe-product-review-submit';
    
    return $args;
}
add_filter('woocommerce_product_review_comment_form_args', 'aqualuxe_woocommerce_product_review_submit_button_class', 10, 1);

/**
 * Add custom classes to cart table.
 *
 * @param array $args Cart table args.
 * @return array
 */
function aqualuxe_woocommerce_cart_table_class($args) {
    $args['class'] .= ' aqualuxe-cart-table';
    
    return $args;
}
add_filter('woocommerce_cart_table_args', 'aqualuxe_woocommerce_cart_table_class', 10, 1);

/**
 * Add custom classes to cart totals.
 *
 * @param array $args Cart totals args.
 * @return array
 */
function aqualuxe_woocommerce_cart_totals_class($args) {
    $args['class'] .= ' aqualuxe-cart-totals';
    
    return $args;
}
add_filter('woocommerce_cart_totals_args', 'aqualuxe_woocommerce_cart_totals_class', 10, 1);

/**
 * Add custom classes to checkout form.
 *
 * @param array $args Checkout form args.
 * @return array
 */
function aqualuxe_woocommerce_checkout_form_class($args) {
    $args['class'] .= ' aqualuxe-checkout-form';
    
    return $args;
}
add_filter('woocommerce_checkout_args', 'aqualuxe_woocommerce_checkout_form_class', 10, 1);

/**
 * Add custom classes to checkout fields.
 *
 * @param array $fields Checkout fields.
 * @return array
 */
function aqualuxe_woocommerce_checkout_fields_class($fields) {
    foreach ($fields as $section => $section_fields) {
        foreach ($section_fields as $key => $field) {
            $fields[$section][$key]['class'][] = 'aqualuxe-checkout-field';
        }
    }
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'aqualuxe_woocommerce_checkout_fields_class', 10, 1);

/**
 * Add custom classes to checkout payment methods.
 *
 * @param array $args Payment methods args.
 * @return array
 */
function aqualuxe_woocommerce_payment_methods_class($args) {
    $args['class'] .= ' aqualuxe-payment-methods';
    
    return $args;
}
add_filter('woocommerce_payment_methods_args', 'aqualuxe_woocommerce_payment_methods_class', 10, 1);

/**
 * Add custom classes to checkout order review.
 *
 * @param array $args Order review args.
 * @return array
 */
function aqualuxe_woocommerce_order_review_class($args) {
    $args['class'] .= ' aqualuxe-order-review';
    
    return $args;
}
add_filter('woocommerce_order_review_args', 'aqualuxe_woocommerce_order_review_class', 10, 1);

/**
 * Add custom classes to account navigation.
 *
 * @param array $args Account navigation args.
 * @return array
 */
function aqualuxe_woocommerce_account_navigation_class($args) {
    $args['class'] .= ' aqualuxe-account-navigation';
    
    return $args;
}
add_filter('woocommerce_account_navigation_args', 'aqualuxe_woocommerce_account_navigation_class', 10, 1);

/**
 * Add custom classes to account content.
 *
 * @param array $args Account content args.
 * @return array
 */
function aqualuxe_woocommerce_account_content_class($args) {
    $args['class'] .= ' aqualuxe-account-content';
    
    return $args;
}
add_filter('woocommerce_account_content_args', 'aqualuxe_woocommerce_account_content_class', 10, 1);

/**
 * Add custom classes to account orders.
 *
 * @param array $args Account orders args.
 * @return array
 */
function aqualuxe_woocommerce_account_orders_class($args) {
    $args['class'] .= ' aqualuxe-account-orders';
    
    return $args;
}
add_filter('woocommerce_account_orders_args', 'aqualuxe_woocommerce_account_orders_class', 10, 1);

/**
 * Add custom classes to account downloads.
 *
 * @param array $args Account downloads args.
 * @return array
 */
function aqualuxe_woocommerce_account_downloads_class($args) {
    $args['class'] .= ' aqualuxe-account-downloads';
    
    return $args;
}
add_filter('woocommerce_account_downloads_args', 'aqualuxe_woocommerce_account_downloads_class', 10, 1);

/**
 * Add custom classes to account addresses.
 *
 * @param array $args Account addresses args.
 * @return array
 */
function aqualuxe_woocommerce_account_addresses_class($args) {
    $args['class'] .= ' aqualuxe-account-addresses';
    
    return $args;
}
add_filter('woocommerce_account_addresses_args', 'aqualuxe_woocommerce_account_addresses_class', 10, 1);

/**
 * Add custom classes to account edit address.
 *
 * @param array $args Account edit address args.
 * @return array
 */
function aqualuxe_woocommerce_account_edit_address_class($args) {
    $args['class'] .= ' aqualuxe-account-edit-address';
    
    return $args;
}
add_filter('woocommerce_account_edit_address_args', 'aqualuxe_woocommerce_account_edit_address_class', 10, 1);

/**
 * Add custom classes to account payment methods.
 *
 * @param array $args Account payment methods args.
 * @return array
 */
function aqualuxe_woocommerce_account_payment_methods_class($args) {
    $args['class'] .= ' aqualuxe-account-payment-methods';
    
    return $args;
}
add_filter('woocommerce_account_payment_methods_args', 'aqualuxe_woocommerce_account_payment_methods_class', 10, 1);

/**
 * Add custom classes to account add payment method.
 *
 * @param array $args Account add payment method args.
 * @return array
 */
function aqualuxe_woocommerce_account_add_payment_method_class($args) {
    $args['class'] .= ' aqualuxe-account-add-payment-method';
    
    return $args;
}
add_filter('woocommerce_account_add_payment_method_args', 'aqualuxe_woocommerce_account_add_payment_method_class', 10, 1);

/**
 * Add custom classes to account edit account.
 *
 * @param array $args Account edit account args.
 * @return array
 */
function aqualuxe_woocommerce_account_edit_account_class($args) {
    $args['class'] .= ' aqualuxe-account-edit-account';
    
    return $args;
}
add_filter('woocommerce_account_edit_account_args', 'aqualuxe_woocommerce_account_edit_account_class', 10, 1);

/**
 * Add custom classes to account lost password.
 *
 * @param array $args Account lost password args.
 * @return array
 */
function aqualuxe_woocommerce_account_lost_password_class($args) {
    $args['class'] .= ' aqualuxe-account-lost-password';
    
    return $args;
}
add_filter('woocommerce_account_lost_password_args', 'aqualuxe_woocommerce_account_lost_password_class', 10, 1);

/**
 * Add custom classes to account reset password.
 *
 * @param array $args Account reset password args.
 * @return array
 */
function aqualuxe_woocommerce_account_reset_password_class($args) {
    $args['class'] .= ' aqualuxe-account-reset-password';
    
    return $args;
}
add_filter('woocommerce_account_reset_password_args', 'aqualuxe_woocommerce_account_reset_password_class', 10, 1);

/**
 * Add custom classes to account login.
 *
 * @param array $args Account login args.
 * @return array
 */
function aqualuxe_woocommerce_account_login_class($args) {
    $args['class'] .= ' aqualuxe-account-login';
    
    return $args;
}
add_filter('woocommerce_account_login_args', 'aqualuxe_woocommerce_account_login_class', 10, 1);

/**
 * Add custom classes to account register.
 *
 * @param array $args Account register args.
 * @return array
 */
function aqualuxe_woocommerce_account_register_class($args) {
    $args['class'] .= ' aqualuxe-account-register';
    
    return $args;
}
add_filter('woocommerce_account_register_args', 'aqualuxe_woocommerce_account_register_class', 10, 1);

/**
 * Add custom classes to order details.
 *
 * @param array $args Order details args.
 * @return array
 */
function aqualuxe_woocommerce_order_details_class($args) {
    $args['class'] .= ' aqualuxe-order-details';
    
    return $args;
}
add_filter('woocommerce_order_details_args', 'aqualuxe_woocommerce_order_details_class', 10, 1);

/**
 * Add custom classes to order customer details.
 *
 * @param array $args Order customer details args.
 * @return array
 */
function aqualuxe_woocommerce_order_customer_details_class($args) {
    $args['class'] .= ' aqualuxe-order-customer-details';
    
    return $args;
}
add_filter('woocommerce_order_customer_details_args', 'aqualuxe_woocommerce_order_customer_details_class', 10, 1);

/**
 * Add custom classes to order downloads.
 *
 * @param array $args Order downloads args.
 * @return array
 */
function aqualuxe_woocommerce_order_downloads_class($args) {
    $args['class'] .= ' aqualuxe-order-downloads';
    
    return $args;
}
add_filter('woocommerce_order_downloads_args', 'aqualuxe_woocommerce_order_downloads_class', 10, 1);

/**
 * Add custom classes to order again button.
 *
 * @param array $args Order again button args.
 * @return array
 */
function aqualuxe_woocommerce_order_again_button_class($args) {
    $args['class'] .= ' aqualuxe-order-again-button';
    
    return $args;
}
add_filter('woocommerce_order_again_button_args', 'aqualuxe_woocommerce_order_again_button_class', 10, 1);

/**
 * Add custom classes to thank you page.
 *
 * @param array $args Thank you page args.
 * @return array
 */
function aqualuxe_woocommerce_thankyou_class($args) {
    $args['class'] .= ' aqualuxe-thankyou';
    
    return $args;
}
add_filter('woocommerce_thankyou_args', 'aqualuxe_woocommerce_thankyou_class', 10, 1);

/**
 * Add custom classes to checkout payment.
 *
 * @param array $args Checkout payment args.
 * @return array
 */
function aqualuxe_woocommerce_checkout_payment_class($args) {
    $args['class'] .= ' aqualuxe-checkout-payment';
    
    return $args;
}
add_filter('woocommerce_checkout_payment_args', 'aqualuxe_woocommerce_checkout_payment_class', 10, 1);

/**
 * Add custom classes to checkout coupon form.
 *
 * @param array $args Checkout coupon form args.
 * @return array
 */
function aqualuxe_woocommerce_checkout_coupon_form_class($args) {
    $args['class'] .= ' aqualuxe-checkout-coupon-form';
    
    return $args;
}
add_filter('woocommerce_checkout_coupon_form_args', 'aqualuxe_woocommerce_checkout_coupon_form_class', 10, 1);

/**
 * Add custom classes to checkout login form.
 *
 * @param array $args Checkout login form args.
 * @return array
 */
function aqualuxe_woocommerce_checkout_login_form_class($args) {
    $args['class'] .= ' aqualuxe-checkout-login-form';
    
    return $args;
}
add_filter('woocommerce_checkout_login_form_args', 'aqualuxe_woocommerce_checkout_login_form_class', 10, 1);

/**
 * Add custom classes to checkout billing.
 *
 * @param array $args Checkout billing args.
 * @return array
 */
function aqualuxe_woocommerce_checkout_billing_class($args) {
    $args['class'] .= ' aqualuxe-checkout-billing';
    
    return $args;
}
add_filter('woocommerce_checkout_billing_args', 'aqualuxe_woocommerce_checkout_billing_class', 10, 1);

/**
 * Add custom classes to checkout shipping.
 *
 * @param array $args Checkout shipping args.
 * @return array
 */
function aqualuxe_woocommerce_checkout_shipping_class($args) {
    $args['class'] .= ' aqualuxe-checkout-shipping';
    
    return $args;
}
add_filter('woocommerce_checkout_shipping_args', 'aqualuxe_woocommerce_checkout_shipping_class', 10, 1);

/**
 * Add custom classes to checkout order review.
 *
 * @param array $args Checkout order review args.
 * @return array
 */
function aqualuxe_woocommerce_checkout_order_review_class($args) {
    $args['class'] .= ' aqualuxe-checkout-order-review';
    
    return $args;
}
add_filter('woocommerce_checkout_order_review_args', 'aqualuxe_woocommerce_checkout_order_review_class', 10, 1);

/**
 * Add custom classes to checkout submit button.
 *
 * @param array $args Checkout submit button args.
 * @return array
 */
function aqualuxe_woocommerce_checkout_submit_button_class($args) {
    $args['class'] .= ' aqualuxe-checkout-submit-button';
    
    return $args;
}
add_filter('woocommerce_checkout_submit_button_args', 'aqualuxe_woocommerce_checkout_submit_button_class', 10, 1);

/**
 * Add custom classes to cart submit button.
 *
 * @param array $args Cart submit button args.
 * @return array
 */
function aqualuxe_woocommerce_cart_submit_button_class($args) {
    $args['class'] .= ' aqualuxe-cart-submit-button';
    
    return $args;
}
add_filter('woocommerce_cart_submit_button_args', 'aqualuxe_woocommerce_cart_submit_button_class', 10, 1);

/**
 * Add custom classes to cart coupon form.
 *
 * @param array $args Cart coupon form args.
 * @return array
 */
function aqualuxe_woocommerce_cart_coupon_form_class($args) {
    $args['class'] .= ' aqualuxe-cart-coupon-form';
    
    return $args;
}
add_filter('woocommerce_cart_coupon_form_args', 'aqualuxe_woocommerce_cart_coupon_form_class', 10, 1);

/**
 * Add custom classes to cart shipping calculator.
 *
 * @param array $args Cart shipping calculator args.
 * @return array
 */
function aqualuxe_woocommerce_cart_shipping_calculator_class($args) {
    $args['class'] .= ' aqualuxe-cart-shipping-calculator';
    
    return $args;
}
add_filter('woocommerce_cart_shipping_calculator_args', 'aqualuxe_woocommerce_cart_shipping_calculator_class', 10, 1);

/**
 * Add custom classes to cart shipping calculator button.
 *
 * @param array $args Cart shipping calculator button args.
 * @return array
 */
function aqualuxe_woocommerce_cart_shipping_calculator_button_class($args) {
    $args['class'] .= ' aqualuxe-cart-shipping-calculator-button';
    
    return $args;
}
add_filter('woocommerce_cart_shipping_calculator_button_args', 'aqualuxe_woocommerce_cart_shipping_calculator_button_class', 10, 1);

/**
 * Add custom classes to cart shipping calculator form.
 *
 * @param array $args Cart shipping calculator form args.
 * @return array
 */
function aqualuxe_woocommerce_cart_shipping_calculator_form_class($args) {
    $args['class'] .= ' aqualuxe-cart-shipping-calculator-form';
    
    return $args;
}
add_filter('woocommerce_cart_shipping_calculator_form_args', 'aqualuxe_woocommerce_cart_shipping_calculator_form_class', 10, 1);

/**
 * Add custom classes to cart shipping calculator submit button.
 *
 * @param array $args Cart shipping calculator submit button args.
 * @return array
 */
function aqualuxe_woocommerce_cart_shipping_calculator_submit_button_class($args) {
    $args['class'] .= ' aqualuxe-cart-shipping-calculator-submit-button';
    
    return $args;
}
add_filter('woocommerce_cart_shipping_calculator_submit_button_args', 'aqualuxe_woocommerce_cart_shipping_calculator_submit_button_class', 10, 1);

/**
 * Add custom classes to cart proceed to checkout button.
 *
 * @param array $args Cart proceed to checkout button args.
 * @return array
 */
function aqualuxe_woocommerce_cart_proceed_to_checkout_button_class($args) {
    $args['class'] .= ' aqualuxe-cart-proceed-to-checkout-button';
    
    return $args;
}
add_filter('woocommerce_cart_proceed_to_checkout_button_args', 'aqualuxe_woocommerce_cart_proceed_to_checkout_button_class', 10, 1);

/**
 * Add custom classes to cart update button.
 *
 * @param array $args Cart update button args.
 * @return array
 */
function aqualuxe_woocommerce_cart_update_button_class($args) {
    $args['class'] .= ' aqualuxe-cart-update-button';
    
    return $args;
}
add_filter('woocommerce_cart_update_button_args', 'aqualuxe_woocommerce_cart_update_button_class', 10, 1);

/**
 * Add custom classes to cart empty button.
 *
 * @param array $args Cart empty button args.
 * @return array
 */
function aqualuxe_woocommerce_cart_empty_button_class($args) {
    $args['class'] .= ' aqualuxe-cart-empty-button';
    
    return $args;
}
add_filter('woocommerce_cart_empty_button_args', 'aqualuxe_woocommerce_cart_empty_button_class', 10, 1);

/**
 * Add custom classes to cart return to shop button.
 *
 * @param array $args Cart return to shop button args.
 * @return array
 */
function aqualuxe_woocommerce_cart_return_to_shop_button_class($args) {
    $args['class'] .= ' aqualuxe-cart-return-to-shop-button';
    
    return $args;
}
add_filter('woocommerce_cart_return_to_shop_button_args', 'aqualuxe_woocommerce_cart_return_to_shop_button_class', 10, 1);

/**
 * Add custom classes to product add to cart button.
 *
 * @param array $args Product add to cart button args.
 * @return array
 */
function aqualuxe_woocommerce_product_add_to_cart_button_class($args) {
    $args['class'] .= ' aqualuxe-product-add-to-cart-button';
    
    return $args;
}
add_filter('woocommerce_product_add_to_cart_button_args', 'aqualuxe_woocommerce_product_add_to_cart_button_class', 10, 1);

/**
 * Add custom classes to product single add to cart button.
 *
 * @param array $args Product single add to cart button args.
 * @return array
 */
function aqualuxe_woocommerce_product_single_add_to_cart_button_class($args) {
    $args['class'] .= ' aqualuxe-product-single-add-to-cart-button';
    
    return $args;
}
add_filter('woocommerce_product_single_add_to_cart_button_args', 'aqualuxe_woocommerce_product_single_add_to_cart_button_class', 10, 1);

/**
 * Add custom classes to product quantity input.
 *
 * @param array $args Product quantity input args.
 * @return array
 */
function aqualuxe_woocommerce_product_quantity_input_class($args) {
    $args['class'] .= ' aqualuxe-product-quantity-input';
    
    return $args;
}
add_filter('woocommerce_quantity_input_args', 'aqualuxe_woocommerce_product_quantity_input_class', 10, 1);

/**
 * Add custom classes to product variation select.
 *
 * @param array $args Product variation select args.
 * @return array
 */
function aqualuxe_woocommerce_product_variation_select_class($args) {
    $args['class'] .= ' aqualuxe-product-variation-select';
    
    return $args;
}
add_filter('woocommerce_dropdown_variation_attribute_options_args', 'aqualuxe_woocommerce_product_variation_select_class', 10, 1);

/**
 * Add custom classes to product gallery.
 *
 * @param array $args Product gallery args.
 * @return array
 */
function aqualuxe_woocommerce_product_gallery_class($args) {
    $args['class'] .= ' aqualuxe-product-gallery';
    
    return $args;
}
add_filter('woocommerce_single_product_image_gallery_classes', 'aqualuxe_woocommerce_product_gallery_class', 10, 1);

/**
 * Add custom classes to product gallery image.
 *
 * @param array $args Product gallery image args.
 * @return array
 */
function aqualuxe_woocommerce_product_gallery_image_class($args) {
    $args['class'] .= ' aqualuxe-product-gallery-image';
    
    return $args;
}
add_filter('woocommerce_gallery_image_html_attachment_image_params', 'aqualuxe_woocommerce_product_gallery_image_class', 10, 1);

/**
 * Add custom classes to product gallery thumbnail.
 *
 * @param array $args Product gallery thumbnail args.
 * @return array
 */
function aqualuxe_woocommerce_product_gallery_thumbnail_class($args) {
    $args['class'] .= ' aqualuxe-product-gallery-thumbnail';
    
    return $args;
}
add_filter('woocommerce_gallery_thumbnail_html_attachment_image_params', 'aqualuxe_woocommerce_product_gallery_thumbnail_class', 10, 1);

/**
 * Add custom classes to product gallery wrapper.
 *
 * @param array $args Product gallery wrapper args.
 * @return array
 */
function aqualuxe_woocommerce_product_gallery_wrapper_class($args) {
    $args['class'] .= ' aqualuxe-product-gallery-wrapper';
    
    return $args;
}
add_filter('woocommerce_single_product_image_gallery_classes', 'aqualuxe_woocommerce_product_gallery_wrapper_class', 10, 1);

/**
 * Add custom classes to product gallery trigger.
 *
 * @param array $args Product gallery trigger args.
 * @return array
 */
function aqualuxe_woocommerce_product_gallery_trigger_class($args) {
    $args['class'] .= ' aqualuxe-product-gallery-trigger';
    
    return $args;
}
add_filter('woocommerce_single_product_image_gallery_classes', 'aqualuxe_woocommerce_product_gallery_trigger_class', 10, 1);

/**
 * Add custom classes to product gallery navigation.
 *
 * @param array $args Product gallery navigation args.
 * @return array
 */
function aqualuxe_woocommerce_product_gallery_navigation_class($args) {
    $args['class'] .= ' aqualuxe-product-gallery-navigation';
    
    return $args;
}
add_filter('woocommerce_single_product_carousel_options', 'aqualuxe_woocommerce_product_gallery_navigation_class', 10, 1);

/**
 * Add custom classes to product gallery pagination.
 *
 * @param array $args Product gallery pagination args.
 * @return array
 */
function aqualuxe_woocommerce_product_gallery_pagination_class($args) {
    $args['class'] .= ' aqualuxe-product-gallery-pagination';
    
    return $args;
}
add_filter('woocommerce_single_product_carousel_options', 'aqualuxe_woocommerce_product_gallery_pagination_class', 10, 1);

/**
 * Add custom classes to product gallery thumbnails.
 *
 * @param array $args Product gallery thumbnails args.
 * @return array
 */
function aqualuxe_woocommerce_product_gallery_thumbnails_class($args) {
    $args['class'] .= ' aqualuxe-product-gallery-thumbnails';
    
    return $args;
}
add_filter('woocommerce_single_product_image_thumbnail_html', 'aqualuxe_woocommerce_product_gallery_thumbnails_class', 10, 1);

/**
 * Add custom classes to product gallery thumbnails wrapper.
 *
 * @param array $args Product gallery thumbnails wrapper args.
 * @return array
 */
function aqualuxe_woocommerce_product_gallery_thumbnails_wrapper_class($args) {
    $args['class'] .= ' aqualuxe-product-gallery-thumbnails-wrapper';
    
    return $args;
}
add_filter('woocommerce_single_product_image_gallery_classes', 'aqualuxe_woocommerce_product_gallery_thumbnails_wrapper_class', 10, 1);

/**
 * Add custom classes to product gallery thumbnails navigation.
 *
 * @param array $args Product gallery thumbnails navigation args.
 * @return array
 */
function aqualuxe_woocommerce_product_gallery_thumbnails_navigation_class($args) {
    $args['class'] .= ' aqualuxe-product-gallery-thumbnails-navigation';
    
    return $args;
}
add_filter('woocommerce_single_product_carousel_options', 'aqualuxe_woocommerce_product_gallery_thumbnails_navigation_class', 10, 1);

/**
 * Add custom classes to product gallery thumbnails pagination.
 *
 * @param array $args Product gallery thumbnails pagination args.
 * @return array
 */
function aqualuxe_woocommerce_product_gallery_thumbnails_pagination_class($args) {
    $args['class'] .= ' aqualuxe-product-gallery-thumbnails-pagination';
    
    return $args;
}
add_filter('woocommerce_single_product_carousel_options', 'aqualuxe_woocommerce_product_gallery_thumbnails_pagination_class', 10, 1);

/**
 * Add custom classes to product gallery zoom.
 *
 * @param array $args Product gallery zoom args.
 * @return array
 */
function aqualuxe_woocommerce_product_gallery_zoom_class($args) {
    $args['class'] .= ' aqualuxe-product-gallery-zoom';
    
    return $args;
}
add_filter('woocommerce_single_product_zoom_options', 'aqualuxe_woocommerce_product_gallery_zoom_class', 10, 1);

/**
 * Add custom classes to product gallery lightbox.
 *
 * @param array $args Product gallery lightbox args.
 * @return array
 */
function aqualuxe_woocommerce_product_gallery_lightbox_class($args) {
    $args['class'] .= ' aqualuxe-product-gallery-lightbox';
    
    return $args;
}
add_filter('woocommerce_single_product_photoswipe_options', 'aqualuxe_woocommerce_product_gallery_lightbox_class', 10, 1);

/**
 * Add custom classes to product gallery slider.
 *
 * @param array $args Product gallery slider args.
 * @return array
 */
function aqualuxe_woocommerce_product_gallery_slider_class($args) {
    $args['class'] .= ' aqualuxe-product-gallery-slider';
    
    return $args;
}
add_filter('woocommerce_single_product_carousel_options', 'aqualuxe_woocommerce_product_gallery_slider_class', 10, 1);

/**
 * Add custom classes to product gallery slider wrapper.
 *
 * @param array $args Product gallery slider wrapper args.
 * @return array
 */
function aqualuxe_woocommerce_product_gallery_slider_wrapper_class($args) {
    $args['class'] .= ' aqualuxe-product-gallery-slider-wrapper';
    
    return $args;
}
add_filter('woocommerce_single_product_image_gallery_classes', 'aqualuxe_woocommerce_product_gallery_slider_wrapper_class', 10, 1);

/**
 * Add custom classes to product gallery slider navigation.
 *
 * @param array $args Product gallery slider navigation args.
 * @return array
 */
function aqualuxe_woocommerce_product_gallery_slider_navigation_class($args) {
    $args['class'] .= ' aqualuxe-product-gallery-slider-navigation';
    
    return $args;
}
add_filter('woocommerce_single_product_carousel_options', 'aqualuxe_woocommerce_product_gallery_slider_navigation_class', 10, 1);

/**
 * Add custom classes to product gallery slider pagination.
 *
 * @param array $args Product gallery slider pagination args.
 * @return array
 */
function aqualuxe_woocommerce_product_gallery_slider_pagination_class($args) {
    $args['class'] .= ' aqualuxe-product-gallery-slider-pagination';
    
    return $args;
}
add_filter('woocommerce_single_product_carousel_options', 'aqualuxe_woocommerce_product_gallery_slider_pagination_class', 10, 1);