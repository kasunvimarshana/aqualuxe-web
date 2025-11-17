<?php
/**
 * AquaLuxe Multi-Currency Support
 *
 * @package AquaLuxe
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize multi-currency support
 */
function aqualuxe_multi_currency_init() {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    // WooCommerce Currency Switcher (WOOCS) compatibility
    add_action('after_setup_theme', 'aqualuxe_woocs_compatibility');
    
    // WPML WooCommerce Multilingual compatibility
    add_action('after_setup_theme', 'aqualuxe_wcml_compatibility');
    
    // Add currency switcher to header
    if (aqualuxe_get_option('aqualuxe_header_show_currency_switcher', true)) {
        add_action('aqualuxe_header_extras', 'aqualuxe_currency_switcher');
    }
    
    // Add currency switcher to footer
    add_action('aqualuxe_footer_extras', 'aqualuxe_currency_switcher_footer');
    
    // Add currency switcher to mobile menu
    add_action('aqualuxe_mobile_menu_extras', 'aqualuxe_currency_switcher_mobile');
}
add_action('after_setup_theme', 'aqualuxe_multi_currency_init');

/**
 * WooCommerce Currency Switcher (WOOCS) compatibility
 */
function aqualuxe_woocs_compatibility() {
    // Check if WOOCS is active
    if (class_exists('WOOCS')) {
        // Add WOOCS styles
        add_action('wp_enqueue_scripts', 'aqualuxe_woocs_styles');
        
        // Add WOOCS switcher to header
        if (aqualuxe_get_option('aqualuxe_header_show_currency_switcher', true)) {
            add_action('aqualuxe_header_extras', 'aqualuxe_woocs_currency_switcher');
        }
        
        // Add WOOCS switcher to footer
        add_action('aqualuxe_footer_extras', 'aqualuxe_woocs_currency_switcher_footer');
        
        // Add WOOCS switcher to mobile menu
        add_action('aqualuxe_mobile_menu_extras', 'aqualuxe_woocs_currency_switcher_mobile');
    }
}

/**
 * WPML WooCommerce Multilingual compatibility
 */
function aqualuxe_wcml_compatibility() {
    // Check if WCML is active
    if (function_exists('wcml_is_multi_currency_on') && wcml_is_multi_currency_on()) {
        // Add WCML styles
        add_action('wp_enqueue_scripts', 'aqualuxe_wcml_styles');
        
        // Add WCML switcher to header
        if (aqualuxe_get_option('aqualuxe_header_show_currency_switcher', true)) {
            add_action('aqualuxe_header_extras', 'aqualuxe_wcml_currency_switcher');
        }
        
        // Add WCML switcher to footer
        add_action('aqualuxe_footer_extras', 'aqualuxe_wcml_currency_switcher_footer');
        
        // Add WCML switcher to mobile menu
        add_action('aqualuxe_mobile_menu_extras', 'aqualuxe_wcml_currency_switcher_mobile');
    }
}

/**
 * WOOCS styles
 */
function aqualuxe_woocs_styles() {
    // Check if WOOCS is active
    if (class_exists('WOOCS')) {
        wp_enqueue_style('woocs-currency-switcher', AQUALUXE_ASSETS_URI . 'css/woocs-currency-switcher.css', [], AQUALUXE_VERSION);
    }
}

/**
 * WCML styles
 */
function aqualuxe_wcml_styles() {
    // Check if WCML is active
    if (function_exists('wcml_is_multi_currency_on') && wcml_is_multi_currency_on()) {
        wp_enqueue_style('wcml-currency-switcher', AQUALUXE_ASSETS_URI . 'css/wcml-currency-switcher.css', [], AQUALUXE_VERSION);
    }
}

/**
 * Currency switcher
 */
// function aqualuxe_currency_switcher() {
//     // WOOCS
//     if (class_exists('WOOCS')) {
//         aqualuxe_woocs_currency_switcher();
//     }
//     // WCML
//     elseif (function_exists('wcml_is_multi_currency_on') && wcml_is_multi_currency_on()) {
//         aqualuxe_wcml_currency_switcher();
//     }
// }

/**
 * Currency switcher in footer
 */
function aqualuxe_currency_switcher_footer() {
    // WOOCS
    if (class_exists('WOOCS')) {
        aqualuxe_woocs_currency_switcher_footer();
    }
    // WCML
    elseif (function_exists('wcml_is_multi_currency_on') && wcml_is_multi_currency_on()) {
        aqualuxe_wcml_currency_switcher_footer();
    }
}

/**
 * Currency switcher in mobile menu
 */
function aqualuxe_currency_switcher_mobile() {
    // WOOCS
    if (class_exists('WOOCS')) {
        aqualuxe_woocs_currency_switcher_mobile();
    }
    // WCML
    elseif (function_exists('wcml_is_multi_currency_on') && wcml_is_multi_currency_on()) {
        aqualuxe_wcml_currency_switcher_mobile();
    }
}

/**
 * WOOCS currency switcher
 */
function aqualuxe_woocs_currency_switcher() {
    // Check if WOOCS is active
    if (class_exists('WOOCS')) {
        echo '<div class="header-currency-switcher woocs-switcher">';
        echo do_shortcode('[woocs]');
        echo '</div>';
    }
}

/**
 * WOOCS currency switcher in footer
 */
function aqualuxe_woocs_currency_switcher_footer() {
    // Check if WOOCS is active
    if (class_exists('WOOCS')) {
        echo '<div class="footer-currency-switcher woocs-switcher">';
        echo do_shortcode('[woocs]');
        echo '</div>';
    }
}

/**
 * WOOCS currency switcher in mobile menu
 */
function aqualuxe_woocs_currency_switcher_mobile() {
    // Check if WOOCS is active
    if (class_exists('WOOCS')) {
        echo '<div class="mobile-currency-switcher woocs-switcher">';
        echo do_shortcode('[woocs]');
        echo '</div>';
    }
}

/**
 * WCML currency switcher
 */
function aqualuxe_wcml_currency_switcher() {
    // Check if WCML is active
    if (function_exists('wcml_is_multi_currency_on') && wcml_is_multi_currency_on()) {
        echo '<div class="header-currency-switcher wcml-switcher">';
        do_action('wcml_currency_switcher', [
            'format' => '%code%',
        ]);
        echo '</div>';
    }
}

/**
 * WCML currency switcher in footer
 */
function aqualuxe_wcml_currency_switcher_footer() {
    // Check if WCML is active
    if (function_exists('wcml_is_multi_currency_on') && wcml_is_multi_currency_on()) {
        echo '<div class="footer-currency-switcher wcml-switcher">';
        do_action('wcml_currency_switcher', [
            'format' => '%code%',
        ]);
        echo '</div>';
    }
}

/**
 * WCML currency switcher in mobile menu
 */
function aqualuxe_wcml_currency_switcher_mobile() {
    // Check if WCML is active
    if (function_exists('wcml_is_multi_currency_on') && wcml_is_multi_currency_on()) {
        echo '<div class="mobile-currency-switcher wcml-switcher">';
        do_action('wcml_currency_switcher', [
            'format' => '%code%',
        ]);
        echo '</div>';
    }
}

/**
 * Get current currency
 *
 * @return string
 */
// function aqualuxe_get_current_currency() {
//     // WOOCS
//     if (class_exists('WOOCS')) {
//         global $WOOCS;
//         return $WOOCS->current_currency;
//     }
    
//     // WCML
//     if (function_exists('wcml_get_woocommerce_currency_option')) {
//         return wcml_get_woocommerce_currency_option();
//     }
    
//     // WooCommerce default
//     if (function_exists('get_woocommerce_currency')) {
//         return get_woocommerce_currency();
//     }
    
//     // Default
//     return 'USD';
// }

/**
 * Get currencies
 *
 * @return array
 */
function aqualuxe_get_currencies() {
    $currencies = [];
    
    // WOOCS
    if (class_exists('WOOCS')) {
        global $WOOCS;
        $currencies = $WOOCS->get_currencies();
    }
    
    // WCML
    elseif (function_exists('wcml_get_woocommerce_currencies_list')) {
        $wcml_currencies = wcml_get_woocommerce_currencies_list();
        $wcml_settings = get_option('_wcml_settings');
        $enabled_currencies = isset($wcml_settings['currency_options']) ? $wcml_settings['currency_options'] : [];
        
        foreach ($enabled_currencies as $code => $currency) {
            if (isset($wcml_currencies[$code])) {
                $currencies[$code] = [
                    'name' => $wcml_currencies[$code],
                    'symbol' => get_woocommerce_currency_symbol($code),
                    'position' => isset($currency['position']) ? $currency['position'] : 'left',
                    'decimals' => isset($currency['num_decimals']) ? $currency['num_decimals'] : 2,
                    'rate' => isset($currency['rate']) ? $currency['rate'] : 1,
                ];
            }
        }
    }
    
    // WooCommerce default
    elseif (function_exists('get_woocommerce_currencies')) {
        $wc_currencies = get_woocommerce_currencies();
        $default_currency = get_woocommerce_currency();
        
        $currencies[$default_currency] = [
            'name' => $wc_currencies[$default_currency],
            'symbol' => get_woocommerce_currency_symbol($default_currency),
            'position' => get_option('woocommerce_currency_pos'),
            'decimals' => get_option('woocommerce_price_num_decimals'),
            'rate' => 1,
        ];
    }
    
    return $currencies;
}

/**
 * Get currency switcher
 *
 * @param array $args Currency switcher arguments
 * @return string
 */
function aqualuxe_get_currency_switcher($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'div',
        'container_class' => 'currency-switcher',
        'echo' => false,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Get currencies
    $currencies = aqualuxe_get_currencies();
    $current_currency = aqualuxe_get_current_currency();
    
    if (empty($currencies)) {
        return '';
    }
    
    // Build output
    $output = '';
    
    $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
    
    $output = '<' . esc_html($args['container']) . $container_class . '>';
    $output .= '<ul class="currency-switcher-list">';
    
    foreach ($currencies as $code => $currency) {
        $class = $code === $current_currency ? ' class="currency-switcher-item active"' : ' class="currency-switcher-item"';
        
        $output .= '<li' . $class . '>';
        
        // WOOCS
        if (class_exists('WOOCS')) {
            $output .= '<a href="#" data-currency="' . esc_attr($code) . '" class="woocs-switcher-link">';
        }
        // WCML
        elseif (function_exists('wcml_is_multi_currency_on') && wcml_is_multi_currency_on()) {
            $output .= '<a href="' . esc_url(add_query_arg('currency', $code)) . '" class="wcml-switcher-link">';
        }
        // Default
        else {
            $output .= '<a href="#" class="currency-switcher-link">';
        }
        
        $output .= esc_html($code) . ' (' . esc_html($currency['symbol']) . ')';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    $output .= '</ul>';
    $output .= '</' . esc_html($args['container']) . '>';
    
    if ($args['echo']) {
        echo $output;
    }
    
    return $output;
}

/**
 * Format price in current currency
 *
 * @param float $price Price
 * @param array $args Format arguments
 * @return string
 */
function aqualuxe_format_price($price, $args = []) {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return number_format($price, 2);
    }
    
    // WOOCS
    if (class_exists('WOOCS')) {
        global $WOOCS;
        return $WOOCS->wc_price($price, $args);
    }
    
    // WCML
    if (function_exists('wcml_convert_price')) {
        $price = wcml_convert_price($price);
    }
    
    // WooCommerce default
    return wc_price($price, $args);
}

/**
 * Convert price to current currency
 *
 * @param float $price Price
 * @param string $currency_from Source currency
 * @return float
 */
function aqualuxe_convert_price($price, $currency_from = '') {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return $price;
    }
    
    // WOOCS
    if (class_exists('WOOCS')) {
        global $WOOCS;
        
        if (empty($currency_from)) {
            $currency_from = $WOOCS->default_currency;
        }
        
        return $WOOCS->woocs_exchange_value($price);
    }
    
    // WCML
    if (function_exists('wcml_convert_price')) {
        return wcml_convert_price($price);
    }
    
    // WooCommerce default
    return $price;
}