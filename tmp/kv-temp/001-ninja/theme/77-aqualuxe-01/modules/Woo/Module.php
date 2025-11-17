<?php
namespace AquaLuxe\Modules\Woo;

if (!defined('ABSPATH')) exit;

class Module {
    public static function boot(): void {
        if (!class_exists('WooCommerce')) return;
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        add_action('after_setup_theme', [__CLASS__, 'supports']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'scripts']);
        add_filter('woocommerce_add_to_cart_fragments', [__CLASS__, 'cart_fragments']);
    }

    public static function supports(): void {
        add_theme_support('woocommerce');
    }

    public static function scripts(): void {
        // Inline CSS tweaks for Woo elements with Tailwind utilities.
        $css = '.woocommerce .button{ @apply bg-sky-600 text-white rounded px-4 py-2; }';
        wp_register_style('aqlx-woo-inline', false);
        wp_enqueue_style('aqlx-woo-inline');
        wp_add_inline_style('aqlx-woo-inline', $css);
    }

    public static function cart_fragments($fragments) {
        $wc = function_exists('WC') ? \call_user_func('WC') : null;
        if (!$wc || !isset($wc->cart)) return $fragments;
        $count = method_exists($wc->cart, 'get_cart_contents_count') ? (int) $wc->cart->get_cart_contents_count() : 0;
        ob_start();
        echo '<span class="aqlx-cart-count">' . $count . '</span>';
        $fragments['span.aqlx-cart-count'] = ob_get_clean();
        return $fragments;
    }
}
