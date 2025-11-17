<?php
/**
 * Multi-currency support for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
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
    
    // Check if WPML WooCommerce Multilingual is active
    if (class_exists('WCML_Multi_Currency')) {
        add_action('wp_enqueue_scripts', 'aqualuxe_wcml_scripts');
        add_filter('body_class', 'aqualuxe_wcml_body_class');
    }
    
    // Check if WooCommerce Currency Switcher is active
    if (class_exists('WOOCS')) {
        add_action('wp_enqueue_scripts', 'aqualuxe_woocs_scripts');
        add_filter('body_class', 'aqualuxe_woocs_body_class');
    }
    
    // Check if WooCommerce Multi-Currency is active
    if (class_exists('WOOMC\App')) {
        add_action('wp_enqueue_scripts', 'aqualuxe_woomc_scripts');
        add_filter('body_class', 'aqualuxe_woomc_body_class');
    }
}
add_action('after_setup_theme', 'aqualuxe_multi_currency_init');

/**
 * Enqueue WPML WooCommerce Multilingual scripts
 */
function aqualuxe_wcml_scripts() {
    wp_enqueue_style('aqualuxe-wcml', AQUALUXE_ASSETS_URI . 'css/wcml.css', array(), AQUALUXE_VERSION);
}

/**
 * Add WPML WooCommerce Multilingual body class
 *
 * @param array $classes
 * @return array
 */
function aqualuxe_wcml_body_class($classes) {
    $classes[] = 'wcml-active';
    
    if (function_exists('wcml_get_multi_currency_values')) {
        $currencies = wcml_get_multi_currency_values();
        
        if (count($currencies) > 1) {
            $classes[] = 'wcml-multiple-currencies';
        }
    }
    
    return $classes;
}

/**
 * Enqueue WooCommerce Currency Switcher scripts
 */
function aqualuxe_woocs_scripts() {
    wp_enqueue_style('aqualuxe-woocs', AQUALUXE_ASSETS_URI . 'css/woocs.css', array(), AQUALUXE_VERSION);
}

/**
 * Add WooCommerce Currency Switcher body class
 *
 * @param array $classes
 * @return array
 */
function aqualuxe_woocs_body_class($classes) {
    $classes[] = 'woocs-active';
    
    global $WOOCS;
    
    if (isset($WOOCS) && is_object($WOOCS)) {
        $currencies = $WOOCS->get_currencies();
        
        if (count($currencies) > 1) {
            $classes[] = 'woocs-multiple-currencies';
        }
    }
    
    return $classes;
}

/**
 * Enqueue WooCommerce Multi-Currency scripts
 */
function aqualuxe_woomc_scripts() {
    wp_enqueue_style('aqualuxe-woomc', AQUALUXE_ASSETS_URI . 'css/woomc.css', array(), AQUALUXE_VERSION);
}

/**
 * Add WooCommerce Multi-Currency body class
 *
 * @param array $classes
 * @return array
 */
function aqualuxe_woomc_body_class($classes) {
    $classes[] = 'woomc-active';
    
    if (class_exists('WOOMC\App')) {
        $currencies = get_option('woomc_currencies', array());
        
        if (count($currencies) > 1) {
            $classes[] = 'woomc-multiple-currencies';
        }
    }
    
    return $classes;
}

/**
 * Get currency switcher
 *
 * @param array $args
 * @return string
 */
function aqualuxe_get_currency_switcher($args = array()) {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return '';
    }
    
    $defaults = array(
        'echo' => false,
    );
    
    $args = wp_parse_args($args, $defaults);
    
    $output = '';
    
    // Check if WPML WooCommerce Multilingual is active
    if (function_exists('wcml_multi_currency_is_enabled') && wcml_multi_currency_is_enabled()) {
        ob_start();
        do_action('wcml_currency_switcher', array(
            'format' => '%code%',
            'switcher_style' => 'dropdown',
        ));
        $output = ob_get_clean();
        
        if (!empty($output)) {
            $output = '<div class="aqualuxe-currency-switcher aqualuxe-wcml-currency-switcher">' . $output . '</div>';
        }
    }
    
    // Check if WooCommerce Currency Switcher is active
    elseif (class_exists('WOOCS')) {
        global $WOOCS;
        
        if (isset($WOOCS) && is_object($WOOCS)) {
            ob_start();
            $WOOCS->show_switcher('dropdown');
            $output = ob_get_clean();
            
            if (!empty($output)) {
                $output = '<div class="aqualuxe-currency-switcher aqualuxe-woocs-currency-switcher">' . $output . '</div>';
            }
        }
    }
    
    // Check if WooCommerce Multi-Currency is active
    elseif (class_exists('WOOMC\App')) {
        ob_start();
        do_action('woomc_currency_switcher');
        $output = ob_get_clean();
        
        if (!empty($output)) {
            $output = '<div class="aqualuxe-currency-switcher aqualuxe-woomc-currency-switcher">' . $output . '</div>';
        }
    }
    
    // Fallback to custom currency switcher
    else {
        $currencies = aqualuxe_get_available_currencies();
        
        if (count($currencies) > 1) {
            $output .= '<div class="aqualuxe-currency-switcher aqualuxe-custom-currency-switcher">';
            $output .= '<div class="aqualuxe-currency-switcher-toggle">';
            
            foreach ($currencies as $currency) {
                if ($currency['active']) {
                    $output .= '<span class="aqualuxe-currency-switcher-current">';
                    $output .= '<span class="aqualuxe-currency-switcher-symbol">' . esc_html($currency['symbol']) . '</span>';
                    $output .= '<span class="aqualuxe-currency-switcher-code">' . esc_html($currency['code']) . '</span>';
                    $output .= '</span>';
                    break;
                }
            }
            
            $output .= '</div>';
            
            $output .= '<div class="aqualuxe-currency-switcher-dropdown">';
            $output .= '<ul class="aqualuxe-currency-switcher-list">';
            
            foreach ($currencies as $currency) {
                $output .= '<li class="aqualuxe-currency-switcher-item' . ($currency['active'] ? ' active' : '') . '">';
                $output .= '<a href="' . esc_url(add_query_arg('currency', $currency['code'])) . '" class="aqualuxe-currency-switcher-link">';
                $output .= '<span class="aqualuxe-currency-switcher-symbol">' . esc_html($currency['symbol']) . '</span>';
                $output .= '<span class="aqualuxe-currency-switcher-name">' . esc_html($currency['name']) . '</span>';
                $output .= '<span class="aqualuxe-currency-switcher-code">' . esc_html($currency['code']) . '</span>';
                $output .= '</a>';
                $output .= '</li>';
            }
            
            $output .= '</ul>';
            $output .= '</div>';
            $output .= '</div>';
        }
    }
    
    if ($args['echo']) {
        echo $output;
    }
    
    return $output;
}

/**
 * Get current currency
 *
 * @return string
 */
function aqualuxe_get_current_currency() {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return '';
    }
    
    // Check if WPML WooCommerce Multilingual is active
    if (function_exists('wcml_get_woocommerce_currency_option')) {
        return wcml_get_woocommerce_currency_option();
    }
    
    // Check if WooCommerce Currency Switcher is active
    if (class_exists('WOOCS')) {
        global $WOOCS;
        
        if (isset($WOOCS) && is_object($WOOCS)) {
            return $WOOCS->current_currency;
        }
    }
    
    // Check if WooCommerce Multi-Currency is active
    if (class_exists('WOOMC\App')) {
        return get_option('woomc_current_currency', get_woocommerce_currency());
    }
    
    // Default to WooCommerce currency
    return get_woocommerce_currency();
}

/**
 * Get available currencies
 *
 * @return array
 */
function aqualuxe_get_available_currencies() {
    $currencies = array();
    
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return $currencies;
    }
    
    $current_currency = aqualuxe_get_current_currency();
    
    // Check if WPML WooCommerce Multilingual is active
    if (function_exists('wcml_get_woocommerce_currencies_list')) {
        $wcml_currencies = wcml_get_woocommerce_currencies_list();
        
        foreach ($wcml_currencies as $code => $currency) {
            $currencies[$code] = array(
                'code' => $code,
                'symbol' => get_woocommerce_currency_symbol($code),
                'name' => $currency['name'],
                'active' => ($current_currency === $code),
            );
        }
        
        return $currencies;
    }
    
    // Check if WooCommerce Currency Switcher is active
    if (class_exists('WOOCS')) {
        global $WOOCS;
        
        if (isset($WOOCS) && is_object($WOOCS)) {
            $woocs_currencies = $WOOCS->get_currencies();
            
            foreach ($woocs_currencies as $code => $currency) {
                $currencies[$code] = array(
                    'code' => $code,
                    'symbol' => $currency['symbol'],
                    'name' => $currency['name'],
                    'active' => ($current_currency === $code),
                );
            }
            
            return $currencies;
        }
    }
    
    // Check if WooCommerce Multi-Currency is active
    if (class_exists('WOOMC\App')) {
        $woomc_currencies = get_option('woomc_currencies', array());
        
        foreach ($woomc_currencies as $code => $currency) {
            $currencies[$code] = array(
                'code' => $code,
                'symbol' => get_woocommerce_currency_symbol($code),
                'name' => $currency['name'],
                'active' => ($current_currency === $code),
            );
        }
        
        return $currencies;
    }
    
    // Default to WooCommerce currency
    $default_currency = get_woocommerce_currency();
    $currencies[$default_currency] = array(
        'code' => $default_currency,
        'symbol' => get_woocommerce_currency_symbol($default_currency),
        'name' => $default_currency,
        'active' => true,
    );
    
    return $currencies;
}

/**
 * Format price with currency
 *
 * @param float $price
 * @param string $currency
 * @return string
 */
function aqualuxe_format_price_with_currency($price, $currency = '') {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return $price;
    }
    
    if (empty($currency)) {
        $currency = aqualuxe_get_current_currency();
    }
    
    // Check if WPML WooCommerce Multilingual is active
    if (function_exists('wcml_convert_price')) {
        $price = wcml_convert_price($price, $currency);
    }
    
    // Check if WooCommerce Currency Switcher is active
    elseif (class_exists('WOOCS')) {
        global $WOOCS;
        
        if (isset($WOOCS) && is_object($WOOCS)) {
            $price = $WOOCS->woocs_exchange_value($price);
        }
    }
    
    // Check if WooCommerce Multi-Currency is active
    elseif (class_exists('WOOMC\App')) {
        $woomc_currencies = get_option('woomc_currencies', array());
        
        if (isset($woomc_currencies[$currency]['rate'])) {
            $price = $price * $woomc_currencies[$currency]['rate'];
        }
    }
    
    return wc_price($price, array('currency' => $currency));
}

/**
 * Convert price to current currency
 *
 * @param float $price
 * @param string $from_currency
 * @param string $to_currency
 * @return float
 */
function aqualuxe_convert_price($price, $from_currency = '', $to_currency = '') {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return $price;
    }
    
    if (empty($from_currency)) {
        $from_currency = get_woocommerce_currency();
    }
    
    if (empty($to_currency)) {
        $to_currency = aqualuxe_get_current_currency();
    }
    
    // If currencies are the same, return the original price
    if ($from_currency === $to_currency) {
        return $price;
    }
    
    // Check if WPML WooCommerce Multilingual is active
    if (function_exists('wcml_convert_price')) {
        return wcml_convert_price($price, $to_currency);
    }
    
    // Check if WooCommerce Currency Switcher is active
    if (class_exists('WOOCS')) {
        global $WOOCS;
        
        if (isset($WOOCS) && is_object($WOOCS)) {
            $currencies = $WOOCS->get_currencies();
            
            if (isset($currencies[$from_currency]) && isset($currencies[$to_currency])) {
                $from_rate = $currencies[$from_currency]['rate'];
                $to_rate = $currencies[$to_currency]['rate'];
                
                if ($from_rate > 0) {
                    return $price * ($to_rate / $from_rate);
                }
            }
        }
    }
    
    // Check if WooCommerce Multi-Currency is active
    if (class_exists('WOOMC\App')) {
        $woomc_currencies = get_option('woomc_currencies', array());
        
        if (isset($woomc_currencies[$from_currency]['rate']) && isset($woomc_currencies[$to_currency]['rate'])) {
            $from_rate = $woomc_currencies[$from_currency]['rate'];
            $to_rate = $woomc_currencies[$to_currency]['rate'];
            
            if ($from_rate > 0) {
                return $price * ($to_rate / $from_rate);
            }
        }
    }
    
    return $price;
}

/**
 * Get currency symbol
 *
 * @param string $currency
 * @return string
 */
function aqualuxe_get_currency_symbol($currency = '') {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return $currency;
    }
    
    if (empty($currency)) {
        $currency = aqualuxe_get_current_currency();
    }
    
    return get_woocommerce_currency_symbol($currency);
}