<?php
declare(strict_types=1);

// Simple currency switcher placeholder; integrates with common plugins when present.
add_shortcode('aqlx_currency_switcher', static function () {
    if (function_exists('wmc_dropdown_currencies')) {
        ob_start();
        echo do_shortcode('[woomulti_currency_switcher]');
        return ob_get_clean();
    }
    return '<span>' . esc_html__('Currency: USD', 'aqualuxe') . '</span>';
});

// Format price suffix for international shipping note (demo).
add_filter('woocommerce_get_price_suffix', static function ($suffix, $product) {
    return $suffix;
}, 10, 2);
