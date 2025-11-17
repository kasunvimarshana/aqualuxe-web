<?php
/**
 * Subscriptions/Memberships module (lightweight):
 * - Detects WooCommerce Subscriptions products and adds a small badge to titles.
 * - Adds a simple price suffix like "/ month" when period is detectable.
 *
 * All integrations are safely guarded to work without WooCommerce or the Subscriptions plugin.
 */

if (!defined('ABSPATH')) { exit; }

// Optionally append to titles (off by default, use filter aqualuxe/subscriptions/title_badge to enable)
add_filter('the_title', function ($title, $id) {
    if (is_admin()) return $title;
    if (get_post_type($id) !== 'product') return $title;
    $ui = get_option('aqlx_ui', []);
    $enabled_by_ui = !empty($ui['title_badges']);
    if (!apply_filters('aqualuxe/subscriptions/title_badge', $enabled_by_ui)) return $title;

    $is_subscription = false;

    // Prefer the helper if available.
    if (function_exists('wcs_is_subscription')) {
        // Call dynamically to appease static analyzers.
        $is_subscription = (bool) call_user_func('wcs_is_subscription', $id);
    } elseif (class_exists('WC_Subscriptions_Product') && function_exists('wc_get_product')) {
        // Fallback to WC_Subscriptions_Product check.
        $product = call_user_func('wc_get_product', $id);
        if ($product) {
            try {
                $cls = 'WC_Subscriptions_Product';
                if (is_callable([$cls, 'is_subscription'])) {
                    $is_subscription = (bool) call_user_func([$cls, 'is_subscription'], $product);
                }
            } catch (\Throwable $e) {
                $is_subscription = false;
            }
        }
    }

    if ($is_subscription) {
        $title .= ' • ' . esc_html__('Subscription', 'aqualuxe');
    }

    return $title;
}, 10, 2);

// Contribute a unified badge for product cards
add_filter('aqualuxe/product_badges', function(array $badges, $product){
    $is_subscription = false;
    if (function_exists('wcs_is_subscription')) {
        $is_subscription = (bool) call_user_func('wcs_is_subscription', $product->get_id());
    } elseif (class_exists('WC_Subscriptions_Product')) {
        $cls = 'WC_Subscriptions_Product';
        if (is_callable([$cls, 'is_subscription'])) {
            $is_subscription = (bool) call_user_func([$cls, 'is_subscription'], $product);
        }
    }
    if ($is_subscription) $badges[] = esc_html__('Subscription','aqualuxe');
    return $badges;
}, 10, 2);

// Add a price suffix for subscription products when possible.
add_filter('woocommerce_get_price_suffix', function ($suffix, $product) {
    if (!is_object($product)) return $suffix;
    if (!class_exists('WC_Subscriptions_Product')) return $suffix;

    try {
        $cls = 'WC_Subscriptions_Product';
        if (is_callable([$cls, 'is_subscription']) && call_user_func([$cls, 'is_subscription'], $product)) {
            // Attempt to read a period if exposed; otherwise default to generic label.
            $period = '';
            if (is_callable([$cls, 'get_period'])) {
                // Some versions expose get_period($product)
                $period = (string) call_user_func([$cls, 'get_period'], $product);
            } elseif (method_exists($product, 'get_billing_period')) {
                $period = (string) $product->get_billing_period();
            }

            if ($period) {
                $suffix .= ' / ' . esc_html($period);
            } else {
                $suffix .= ' / ' . esc_html__('period', 'aqualuxe');
            }
        }
    } catch (\Throwable $e) {
        // Silently ignore if API shape differs.
    }

    return $suffix;
}, 10, 2);
