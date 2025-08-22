<?php
/**
 * Multicurrency module functions
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get default currency
 *
 * @return string Default currency code
 */
function aqualuxe_multicurrency_get_default_currency() {
    // Get WooCommerce default currency
    $default_currency = get_option('woocommerce_currency');
    
    // Allow filtering
    return apply_filters('aqualuxe_multicurrency_default_currency', $default_currency);
}

/**
 * Get current currency
 *
 * @return string Current currency code
 */
function aqualuxe_multicurrency_get_current_currency() {
    // Check if currency is set in session
    $currency = WC()->session ? WC()->session->get('aqualuxe_currency') : null;
    
    // Check if currency is set in cookie
    if (!$currency && isset($_COOKIE['aqualuxe_currency'])) {
        $currency = sanitize_text_field($_COOKIE['aqualuxe_currency']);
    }
    
    // Check if currency is set in URL
    if (isset($_GET['currency'])) {
        $currency = sanitize_text_field($_GET['currency']);
    }
    
    // Fallback to default currency
    if (!$currency || !aqualuxe_multicurrency_is_currency_enabled($currency)) {
        $currency = aqualuxe_multicurrency_get_default_currency();
    }
    
    // Allow filtering
    return apply_filters('aqualuxe_multicurrency_current_currency', $currency);
}

/**
 * Set current currency
 *
 * @param string $currency Currency code
 * @return bool True if currency was set successfully
 */
function aqualuxe_multicurrency_set_current_currency($currency) {
    // Check if currency is enabled
    if (!aqualuxe_multicurrency_is_currency_enabled($currency)) {
        return false;
    }
    
    // Set currency in session
    if (WC()->session) {
        WC()->session->set('aqualuxe_currency', $currency);
    }
    
    // Set currency in cookie (30 days)
    setcookie('aqualuxe_currency', $currency, time() + (30 * DAY_IN_SECONDS), '/');
    
    // Allow other plugins to hook into currency change
    do_action('aqualuxe_multicurrency_currency_changed', $currency);
    
    return true;
}

/**
 * Get available currencies
 *
 * @return array List of available currencies
 */
function aqualuxe_multicurrency_get_currencies() {
    // Get enabled currencies
    $enabled_currencies = aqualuxe_get_module_option('multicurrency', 'enabled_currencies', array());
    
    // Get all currencies
    $all_currencies = aqualuxe_multicurrency_get_all_currencies();
    
    // Filter enabled currencies
    $currencies = array();
    
    foreach ($enabled_currencies as $currency_code) {
        if (isset($all_currencies[$currency_code])) {
            $currencies[$currency_code] = $all_currencies[$currency_code];
        }
    }
    
    // Add default currency if not already included
    $default_currency = aqualuxe_multicurrency_get_default_currency();
    
    if (!isset($currencies[$default_currency])) {
        $currencies[$default_currency] = $all_currencies[$default_currency];
    }
    
    // Allow filtering
    return apply_filters('aqualuxe_multicurrency_currencies', $currencies);
}

/**
 * Check if currency is enabled
 *
 * @param string $currency Currency code
 * @return bool True if currency is enabled
 */
function aqualuxe_multicurrency_is_currency_enabled($currency) {
    // Default currency is always enabled
    if ($currency === aqualuxe_multicurrency_get_default_currency()) {
        return true;
    }
    
    // Check if currency is in enabled currencies
    $enabled_currencies = aqualuxe_get_module_option('multicurrency', 'enabled_currencies', array());
    
    return in_array($currency, $enabled_currencies);
}

/**
 * Get all currencies
 *
 * @return array List of all currencies
 */
function aqualuxe_multicurrency_get_all_currencies() {
    // Get WooCommerce currencies
    $wc_currencies = get_woocommerce_currencies();
    
    // Get custom currencies
    $custom_currencies = aqualuxe_get_module_option('multicurrency', 'custom_currencies', array());
    
    // Merge currencies
    $currencies = array();
    
    foreach ($wc_currencies as $code => $name) {
        $currencies[$code] = array(
            'code' => $code,
            'name' => $name,
            'symbol' => get_woocommerce_currency_symbol($code),
            'rate' => 1,
            'decimals' => 2,
            'format' => get_option('woocommerce_currency_pos'),
            'thousand_sep' => wc_get_price_thousand_separator(),
            'decimal_sep' => wc_get_price_decimal_separator(),
        );
    }
    
    // Add custom currencies
    foreach ($custom_currencies as $code => $currency) {
        $currencies[$code] = array(
            'code' => $code,
            'name' => $currency['name'],
            'symbol' => $currency['symbol'],
            'rate' => 1,
            'decimals' => isset($currency['decimals']) ? $currency['decimals'] : 2,
            'format' => isset($currency['format']) ? $currency['format'] : 'left',
            'thousand_sep' => isset($currency['thousand_sep']) ? $currency['thousand_sep'] : ',',
            'decimal_sep' => isset($currency['decimal_sep']) ? $currency['decimal_sep'] : '.',
        );
    }
    
    // Get exchange rates
    $exchange_rates = aqualuxe_multicurrency_get_exchange_rates();
    
    // Apply exchange rates
    foreach ($currencies as $code => $currency) {
        if (isset($exchange_rates[$code])) {
            $currencies[$code]['rate'] = $exchange_rates[$code];
        }
    }
    
    // Allow filtering
    return apply_filters('aqualuxe_multicurrency_all_currencies', $currencies);
}

/**
 * Get exchange rates
 *
 * @return array Exchange rates
 */
function aqualuxe_multicurrency_get_exchange_rates() {
    // Get exchange rates from options
    $exchange_rates = aqualuxe_get_module_option('multicurrency', 'exchange_rates', array());
    
    // Get default currency
    $default_currency = aqualuxe_multicurrency_get_default_currency();
    
    // Default currency always has rate of 1
    $exchange_rates[$default_currency] = 1;
    
    // Allow filtering
    return apply_filters('aqualuxe_multicurrency_exchange_rates', $exchange_rates);
}

/**
 * Convert price to current currency
 *
 * @param float $price Price in default currency
 * @param string $currency Currency code (optional)
 * @return float Price in current currency
 */
function aqualuxe_multicurrency_convert_price($price, $currency = '') {
    // Get current currency if not specified
    if (empty($currency)) {
        $currency = aqualuxe_multicurrency_get_current_currency();
    }
    
    // Get default currency
    $default_currency = aqualuxe_multicurrency_get_default_currency();
    
    // If current currency is default currency, return price as is
    if ($currency === $default_currency) {
        return $price;
    }
    
    // Get exchange rates
    $exchange_rates = aqualuxe_multicurrency_get_exchange_rates();
    
    // Get exchange rate for current currency
    $exchange_rate = isset($exchange_rates[$currency]) ? $exchange_rates[$currency] : 1;
    
    // Convert price
    $converted_price = $price * $exchange_rate;
    
    // Round price
    $currencies = aqualuxe_multicurrency_get_all_currencies();
    $decimals = isset($currencies[$currency]['decimals']) ? $currencies[$currency]['decimals'] : 2;
    $converted_price = round($converted_price, $decimals);
    
    // Allow filtering
    return apply_filters('aqualuxe_multicurrency_converted_price', $converted_price, $price, $currency, $exchange_rate);
}

/**
 * Format price in current currency
 *
 * @param float $price Price in current currency
 * @param string $currency Currency code (optional)
 * @return string Formatted price
 */
function aqualuxe_multicurrency_format_price($price, $currency = '') {
    // Get current currency if not specified
    if (empty($currency)) {
        $currency = aqualuxe_multicurrency_get_current_currency();
    }
    
    // Get currency data
    $currencies = aqualuxe_multicurrency_get_all_currencies();
    
    if (!isset($currencies[$currency])) {
        // Fallback to WooCommerce formatting
        return wc_price($price);
    }
    
    $currency_data = $currencies[$currency];
    
    // Format price
    $formatted_price = aqualuxe_multicurrency_price_format(
        $price,
        $currency_data['symbol'],
        $currency_data['decimals'],
        $currency_data['thousand_sep'],
        $currency_data['decimal_sep'],
        $currency_data['format']
    );
    
    // Allow filtering
    return apply_filters('aqualuxe_multicurrency_formatted_price', $formatted_price, $price, $currency);
}

/**
 * Format price with currency symbol
 *
 * @param float $price Price
 * @param string $symbol Currency symbol
 * @param int $decimals Number of decimal places
 * @param string $thousand_sep Thousands separator
 * @param string $decimal_sep Decimal separator
 * @param string $format Price format
 * @return string Formatted price
 */
function aqualuxe_multicurrency_price_format($price, $symbol, $decimals, $thousand_sep, $decimal_sep, $format) {
    // Format number
    $formatted_number = number_format($price, $decimals, $decimal_sep, $thousand_sep);
    
    // Format price with currency symbol
    switch ($format) {
        case 'left':
            return $symbol . $formatted_number;
        case 'right':
            return $formatted_number . $symbol;
        case 'left_space':
            return $symbol . ' ' . $formatted_number;
        case 'right_space':
            return $formatted_number . ' ' . $symbol;
        default:
            return $symbol . $formatted_number;
    }
}

/**
 * Get currency switcher
 *
 * @param array $args Currency switcher arguments
 * @return string Currency switcher HTML
 */
function aqualuxe_multicurrency_get_currency_switcher($args = array()) {
    $defaults = array(
        'show_symbols' => true,
        'show_names' => true,
        'dropdown' => true,
        'echo' => false,
    );
    
    $args = wp_parse_args($args, $defaults);
    $currencies = aqualuxe_multicurrency_get_currencies();
    $current_currency = aqualuxe_multicurrency_get_current_currency();
    
    // If only one currency, don't show switcher
    if (count($currencies) <= 1) {
        return '';
    }
    
    $output = '';
    
    if ($args['dropdown']) {
        $output .= '<div class="currency-switcher dropdown">';
        $output .= '<button class="currency-switcher-toggle" aria-expanded="false">';
        
        if (isset($currencies[$current_currency])) {
            if ($args['show_symbols']) {
                $output .= '<span class="currency-symbol">' . esc_html($currencies[$current_currency]['symbol']) . '</span>';
            }
            
            if ($args['show_names']) {
                $output .= '<span class="currency-name">' . esc_html($currencies[$current_currency]['code']) . '</span>';
            }
        }
        
        $output .= '<span class="dropdown-arrow"></span>';
        $output .= '</button>';
        
        $output .= '<ul class="currency-switcher-dropdown">';
        
        foreach ($currencies as $code => $currency) {
            $active_class = ($code === $current_currency) ? ' active' : '';
            
            $output .= '<li class="currency-item' . $active_class . '">';
            $output .= '<a href="' . esc_url(add_query_arg('currency', $code)) . '" data-currency="' . esc_attr($code) . '">';
            
            if ($args['show_symbols']) {
                $output .= '<span class="currency-symbol">' . esc_html($currency['symbol']) . '</span>';
            }
            
            if ($args['show_names']) {
                $output .= '<span class="currency-name">' . esc_html($code) . '</span>';
            }
            
            $output .= '</a>';
            $output .= '</li>';
        }
        
        $output .= '</ul>';
        $output .= '</div>';
    } else {
        $output .= '<ul class="currency-switcher-list">';
        
        foreach ($currencies as $code => $currency) {
            $active_class = ($code === $current_currency) ? ' active' : '';
            
            $output .= '<li class="currency-item' . $active_class . '">';
            $output .= '<a href="' . esc_url(add_query_arg('currency', $code)) . '" data-currency="' . esc_attr($code) . '">';
            
            if ($args['show_symbols']) {
                $output .= '<span class="currency-symbol">' . esc_html($currency['symbol']) . '</span>';
            }
            
            if ($args['show_names']) {
                $output .= '<span class="currency-name">' . esc_html($code) . '</span>';
            }
            
            $output .= '</a>';
            $output .= '</li>';
        }
        
        $output .= '</ul>';
    }
    
    if ($args['echo']) {
        echo $output;
    }
    
    return $output;
}

/**
 * Display currency switcher
 *
 * @param array $args Currency switcher arguments
 */
function aqualuxe_multicurrency_currency_switcher($args = array()) {
    echo aqualuxe_multicurrency_get_currency_switcher($args);
}

/**
 * Update exchange rates
 *
 * @param string $api_provider API provider
 * @return bool True if exchange rates were updated successfully
 */
function aqualuxe_multicurrency_update_exchange_rates($api_provider = '') {
    // Get API provider if not specified
    if (empty($api_provider)) {
        $api_provider = aqualuxe_get_module_option('multicurrency', 'api_provider', 'manual');
    }
    
    // Get default currency
    $default_currency = aqualuxe_multicurrency_get_default_currency();
    
    // Get enabled currencies
    $enabled_currencies = aqualuxe_get_module_option('multicurrency', 'enabled_currencies', array());
    
    // Initialize exchange rates
    $exchange_rates = array(
        $default_currency => 1,
    );
    
    // Update exchange rates based on API provider
    switch ($api_provider) {
        case 'openexchangerates':
            $api_key = aqualuxe_get_module_option('multicurrency', 'openexchangerates_api_key', '');
            
            if (empty($api_key)) {
                return false;
            }
            
            // Build API URL
            $api_url = 'https://openexchangerates.org/api/latest.json?app_id=' . $api_key . '&base=' . $default_currency;
            
            // Get exchange rates from API
            $response = wp_remote_get($api_url);
            
            if (is_wp_error($response)) {
                return false;
            }
            
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);
            
            if (!isset($data['rates'])) {
                return false;
            }
            
            // Update exchange rates
            foreach ($enabled_currencies as $currency) {
                if (isset($data['rates'][$currency])) {
                    $exchange_rates[$currency] = $data['rates'][$currency];
                }
            }
            
            break;
            
        case 'exchangeratesapi':
            $api_key = aqualuxe_get_module_option('multicurrency', 'exchangeratesapi_api_key', '');
            
            if (empty($api_key)) {
                return false;
            }
            
            // Build API URL
            $api_url = 'https://api.exchangeratesapi.io/v1/latest?access_key=' . $api_key . '&base=' . $default_currency;
            
            // Get exchange rates from API
            $response = wp_remote_get($api_url);
            
            if (is_wp_error($response)) {
                return false;
            }
            
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);
            
            if (!isset($data['rates'])) {
                return false;
            }
            
            // Update exchange rates
            foreach ($enabled_currencies as $currency) {
                if (isset($data['rates'][$currency])) {
                    $exchange_rates[$currency] = $data['rates'][$currency];
                }
            }
            
            break;
            
        case 'manual':
            // Get manual exchange rates
            $manual_rates = aqualuxe_get_module_option('multicurrency', 'manual_exchange_rates', array());
            
            // Update exchange rates
            foreach ($enabled_currencies as $currency) {
                if (isset($manual_rates[$currency])) {
                    $exchange_rates[$currency] = $manual_rates[$currency];
                }
            }
            
            break;
    }
    
    // Save exchange rates
    aqualuxe_update_module_option('multicurrency', 'exchange_rates', $exchange_rates);
    
    // Save last update time
    aqualuxe_update_module_option('multicurrency', 'last_update', time());
    
    // Allow other plugins to hook into exchange rates update
    do_action('aqualuxe_multicurrency_exchange_rates_updated', $exchange_rates);
    
    return true;
}

/**
 * Schedule exchange rates update
 */
function aqualuxe_multicurrency_schedule_exchange_rates_update() {
    // Check if automatic updates are enabled
    $auto_update = aqualuxe_get_module_option('multicurrency', 'auto_update', false);
    
    if (!$auto_update) {
        return;
    }
    
    // Check if event is already scheduled
    if (wp_next_scheduled('aqualuxe_multicurrency_update_exchange_rates')) {
        return;
    }
    
    // Schedule event
    wp_schedule_event(time(), 'daily', 'aqualuxe_multicurrency_update_exchange_rates');
}

/**
 * Unschedule exchange rates update
 */
function aqualuxe_multicurrency_unschedule_exchange_rates_update() {
    // Unschedule event
    $timestamp = wp_next_scheduled('aqualuxe_multicurrency_update_exchange_rates');
    
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'aqualuxe_multicurrency_update_exchange_rates');
    }
}

/**
 * Get currency by IP
 *
 * @return string Currency code
 */
function aqualuxe_multicurrency_get_currency_by_ip() {
    // Get visitor IP
    $ip = $_SERVER['REMOTE_ADDR'];
    
    // Get geolocation data
    $geolocation = WC_Geolocation::geolocate_ip($ip);
    
    // Get country code
    $country_code = $geolocation['country'];
    
    // Get currency by country
    $currency = aqualuxe_multicurrency_get_currency_by_country($country_code);
    
    // Allow filtering
    return apply_filters('aqualuxe_multicurrency_currency_by_ip', $currency, $country_code, $ip);
}

/**
 * Get currency by country
 *
 * @param string $country_code Country code
 * @return string Currency code
 */
function aqualuxe_multicurrency_get_currency_by_country($country_code) {
    // Get country-currency mapping
    $country_currency = aqualuxe_get_module_option('multicurrency', 'country_currency', array());
    
    // Get currency for country
    if (isset($country_currency[$country_code])) {
        return $country_currency[$country_code];
    }
    
    // Fallback to default currency
    return aqualuxe_multicurrency_get_default_currency();
}

/**
 * Get currency by language
 *
 * @param string $language Language code
 * @return string Currency code
 */
function aqualuxe_multicurrency_get_currency_by_language($language) {
    // Get language-currency mapping
    $language_currency = aqualuxe_get_module_option('multicurrency', 'language_currency', array());
    
    // Get currency for language
    if (isset($language_currency[$language])) {
        return $language_currency[$language];
    }
    
    // Fallback to default currency
    return aqualuxe_multicurrency_get_default_currency();
}