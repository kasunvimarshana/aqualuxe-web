<?php
/**
 * Multi-currency support for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Initialize multi-currency support
 */
function aqualuxe_multi_currency_init() {
    // Add currency switcher to menu
    if (aqualuxe_get_option('currency_switcher_in_menu', false)) {
        add_filter('wp_nav_menu_items', 'aqualuxe_add_currency_switcher_to_menu', 10, 2);
    }
    
    // Add currency switcher to mobile menu
    if (aqualuxe_get_option('currency_switcher_in_mobile_menu', true)) {
        add_action('aqualuxe_mobile_menu_extras', 'aqualuxe_currency_switcher', 20);
    }
    
    // Add currency switcher to footer
    if (aqualuxe_get_option('currency_switcher_in_footer', false)) {
        add_action('aqualuxe_footer_bottom', 'aqualuxe_currency_switcher', 25);
    }
    
    // Add currency cookie handling
    add_action('init', 'aqualuxe_handle_currency_cookie');
    
    // Add AJAX handler for currency switching
    add_action('wp_ajax_aqualuxe_switch_currency', 'aqualuxe_ajax_switch_currency');
    add_action('wp_ajax_nopriv_aqualuxe_switch_currency', 'aqualuxe_ajax_switch_currency');
    
    // Add currency data to JavaScript
    add_action('wp_enqueue_scripts', 'aqualuxe_add_currency_data_to_js', 20);
}
add_action('after_setup_theme', 'aqualuxe_multi_currency_init');

/**
 * Handle currency cookie
 */
function aqualuxe_handle_currency_cookie() {
    if (isset($_GET['currency']) && !empty($_GET['currency'])) {
        $currency = sanitize_text_field($_GET['currency']);
        $available_currencies = aqualuxe_get_available_currencies();
        
        if (array_key_exists($currency, $available_currencies)) {
            setcookie('aqualuxe_currency', $currency, time() + (86400 * 30), '/'); // 30 days
            $_COOKIE['aqualuxe_currency'] = $currency;
        }
    }
}

/**
 * AJAX handler for currency switching
 */
function aqualuxe_ajax_switch_currency() {
    if (isset($_POST['currency']) && !empty($_POST['currency'])) {
        $currency = sanitize_text_field($_POST['currency']);
        $available_currencies = aqualuxe_get_available_currencies();
        
        if (array_key_exists($currency, $available_currencies)) {
            setcookie('aqualuxe_currency', $currency, time() + (86400 * 30), '/'); // 30 days
            
            wp_send_json_success([
                'currency' => $currency,
                'symbol' => $available_currencies[$currency]['symbol'],
            ]);
        }
    }
    
    wp_send_json_error();
}

/**
 * Add currency data to JavaScript
 */
function aqualuxe_add_currency_data_to_js() {
    $available_currencies = aqualuxe_get_available_currencies();
    $current_currency = aqualuxe_get_current_currency();
    
    wp_localize_script('aqualuxe-app', 'aqualuxeCurrency', [
        'currencies' => $available_currencies,
        'current' => $current_currency,
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'action' => 'aqualuxe_switch_currency',
    ]);
}

/**
 * Add currency switcher to menu
 *
 * @param string $items Menu items
 * @param object $args Menu arguments
 * @return string
 */
function aqualuxe_add_currency_switcher_to_menu($items, $args) {
    if ($args->theme_location == 'primary') {
        ob_start();
        aqualuxe_currency_switcher();
        $currency_switcher = ob_get_clean();
        
        if ($currency_switcher) {
            $items .= '<li class="menu-item menu-item-currency-switcher">' . $currency_switcher . '</li>';
        }
    }
    
    return $items;
}

/**
 * Get current currency
 *
 * @return string
 */
function aqualuxe_get_current_currency() {
    $default_currency = aqualuxe_get_option('default_currency', 'USD');
    
    // Check for cookie
    if (isset($_COOKIE['aqualuxe_currency'])) {
        $currency = sanitize_text_field($_COOKIE['aqualuxe_currency']);
        $available_currencies = aqualuxe_get_available_currencies();
        
        if (array_key_exists($currency, $available_currencies)) {
            return $currency;
        }
    }
    
    // Check for WooCommerce Currency Switcher
    if (class_exists('WOOCS') && isset($GLOBALS['WOOCS'])) {
        return $GLOBALS['WOOCS']->current_currency;
    }
    
    // Check for WooCommerce Multilingual
    if (class_exists('WCML_Multi_Currency') && isset($GLOBALS['woocommerce_wpml'])) {
        if (isset($GLOBALS['woocommerce_wpml']->multi_currency)) {
            return $GLOBALS['woocommerce_wpml']->multi_currency->get_client_currency();
        }
    }
    
    // Check for WooCommerce
    if (function_exists('get_woocommerce_currency')) {
        return get_woocommerce_currency();
    }
    
    return $default_currency;
}

/**
 * Get available currencies
 *
 * @return array
 */
function aqualuxe_get_available_currencies() {
    $currencies = [];
    $current_currency = aqualuxe_get_current_currency();
    
    // Check for WooCommerce Currency Switcher
    if (class_exists('WOOCS') && isset($GLOBALS['WOOCS'])) {
        $woocs_currencies = $GLOBALS['WOOCS']->get_currencies();
        
        if (!empty($woocs_currencies)) {
            foreach ($woocs_currencies as $code => $currency) {
                $currencies[$code] = [
                    'code' => $code,
                    'name' => $currency['name'],
                    'symbol' => $currency['symbol'],
                    'position' => $currency['position'],
                    'decimals' => $currency['decimals'],
                    'rate' => $currency['rate'],
                    'active' => ($code === $current_currency),
                ];
            }
            
            return $currencies;
        }
    }
    
    // Check for WooCommerce Multilingual
    if (class_exists('WCML_Multi_Currency') && isset($GLOBALS['woocommerce_wpml'])) {
        if (isset($GLOBALS['woocommerce_wpml']->multi_currency)) {
            $wcml_currencies = $GLOBALS['woocommerce_wpml']->multi_currency->get_currencies();
            
            if (!empty($wcml_currencies)) {
                foreach ($wcml_currencies as $code => $currency) {
                    $currencies[$code] = [
                        'code' => $code,
                        'name' => $currency['name'],
                        'symbol' => $currency['symbol'],
                        'position' => $currency['position'],
                        'decimals' => $currency['decimals'],
                        'rate' => $currency['rate'],
                        'active' => ($code === $current_currency),
                    ];
                }
                
                return $currencies;
            }
        }
    }
    
    // Default currencies if no plugin is active
    $default_currencies = [
        'USD' => [
            'code' => 'USD',
            'name' => __('US Dollar', 'aqualuxe'),
            'symbol' => '$',
            'position' => 'left',
            'decimals' => 2,
            'rate' => 1,
            'active' => ($current_currency === 'USD'),
        ],
        'EUR' => [
            'code' => 'EUR',
            'name' => __('Euro', 'aqualuxe'),
            'symbol' => '€',
            'position' => 'left',
            'decimals' => 2,
            'rate' => 0.85,
            'active' => ($current_currency === 'EUR'),
        ],
        'GBP' => [
            'code' => 'GBP',
            'name' => __('British Pound', 'aqualuxe'),
            'symbol' => '£',
            'position' => 'left',
            'decimals' => 2,
            'rate' => 0.75,
            'active' => ($current_currency === 'GBP'),
        ],
        'JPY' => [
            'code' => 'JPY',
            'name' => __('Japanese Yen', 'aqualuxe'),
            'symbol' => '¥',
            'position' => 'left',
            'decimals' => 0,
            'rate' => 110,
            'active' => ($current_currency === 'JPY'),
        ],
        'AUD' => [
            'code' => 'AUD',
            'name' => __('Australian Dollar', 'aqualuxe'),
            'symbol' => 'A$',
            'position' => 'left',
            'decimals' => 2,
            'rate' => 1.35,
            'active' => ($current_currency === 'AUD'),
        ],
        'CAD' => [
            'code' => 'CAD',
            'name' => __('Canadian Dollar', 'aqualuxe'),
            'symbol' => 'C$',
            'position' => 'left',
            'decimals' => 2,
            'rate' => 1.25,
            'active' => ($current_currency === 'CAD'),
        ],
    ];
    
    // Get enabled currencies from theme options
    $enabled_currencies = aqualuxe_get_option('enabled_currencies', ['USD', 'EUR', 'GBP']);
    
    if (!empty($enabled_currencies)) {
        foreach ($enabled_currencies as $code) {
            if (isset($default_currencies[$code])) {
                $currencies[$code] = $default_currencies[$code];
            }
        }
    } else {
        $currencies = $default_currencies;
    }
    
    return $currencies;
}

/**
 * Format price with currency
 *
 * @param float $price Price amount
 * @param string $currency Currency code
 * @return string
 */
function aqualuxe_format_price_with_currency($price, $currency = '') {
    if (empty($currency)) {
        $currency = aqualuxe_get_current_currency();
    }
    
    $currencies = aqualuxe_get_available_currencies();
    
    if (!isset($currencies[$currency])) {
        $currency = aqualuxe_get_option('default_currency', 'USD');
    }
    
    $currency_data = $currencies[$currency];
    $formatted_price = number_format($price, $currency_data['decimals'], '.', ',');
    
    switch ($currency_data['position']) {
        case 'left':
            return $currency_data['symbol'] . $formatted_price;
        case 'right':
            return $formatted_price . $currency_data['symbol'];
        case 'left_space':
            return $currency_data['symbol'] . ' ' . $formatted_price;
        case 'right_space':
            return $formatted_price . ' ' . $currency_data['symbol'];
        default:
            return $currency_data['symbol'] . $formatted_price;
    }
}

/**
 * Convert price to current currency
 *
 * @param float $price Price in default currency
 * @param string $from_currency Source currency code
 * @param string $to_currency Target currency code
 * @return float
 */
function aqualuxe_convert_price($price, $from_currency = '', $to_currency = '') {
    if (empty($from_currency)) {
        $from_currency = aqualuxe_get_option('default_currency', 'USD');
    }
    
    if (empty($to_currency)) {
        $to_currency = aqualuxe_get_current_currency();
    }
    
    // If currencies are the same, no conversion needed
    if ($from_currency === $to_currency) {
        return $price;
    }
    
    $currencies = aqualuxe_get_available_currencies();
    
    // Check if both currencies exist
    if (!isset($currencies[$from_currency]) || !isset($currencies[$to_currency])) {
        return $price;
    }
    
    // Get exchange rates
    $from_rate = $currencies[$from_currency]['rate'];
    $to_rate = $currencies[$to_currency]['rate'];
    
    // Convert price
    $converted_price = ($price / $from_rate) * $to_rate;
    
    return $converted_price;
}

/**
 * Add multi-currency support to customizer
 *
 * @param WP_Customize_Manager $wp_customize Customizer object
 */
function aqualuxe_multi_currency_customizer($wp_customize) {
    // Add currency section to customizer
    $wp_customize->add_section('aqualuxe_multi_currency', [
        'title' => __('Multi-Currency Settings', 'aqualuxe'),
        'priority' => 35,
    ]);
    
    // Default currency
    $wp_customize->add_setting('aqualuxe_options[default_currency]', [
        'default' => 'USD',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    
    $wp_customize->add_control('aqualuxe_default_currency', [
        'label' => __('Default Currency', 'aqualuxe'),
        'section' => 'aqualuxe_multi_currency',
        'settings' => 'aqualuxe_options[default_currency]',
        'type' => 'select',
        'choices' => [
            'USD' => __('US Dollar ($)', 'aqualuxe'),
            'EUR' => __('Euro (€)', 'aqualuxe'),
            'GBP' => __('British Pound (£)', 'aqualuxe'),
            'JPY' => __('Japanese Yen (¥)', 'aqualuxe'),
            'AUD' => __('Australian Dollar (A$)', 'aqualuxe'),
            'CAD' => __('Canadian Dollar (C$)', 'aqualuxe'),
        ],
    ]);
    
    // Enabled currencies
    $wp_customize->add_setting('aqualuxe_options[enabled_currencies]', [
        'default' => ['USD', 'EUR', 'GBP'],
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_multi_choices',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Multiple_Checkbox_Control($wp_customize, 'aqualuxe_enabled_currencies', [
        'label' => __('Enabled Currencies', 'aqualuxe'),
        'section' => 'aqualuxe_multi_currency',
        'settings' => 'aqualuxe_options[enabled_currencies]',
        'choices' => [
            'USD' => __('US Dollar ($)', 'aqualuxe'),
            'EUR' => __('Euro (€)', 'aqualuxe'),
            'GBP' => __('British Pound (£)', 'aqualuxe'),
            'JPY' => __('Japanese Yen (¥)', 'aqualuxe'),
            'AUD' => __('Australian Dollar (A$)', 'aqualuxe'),
            'CAD' => __('Canadian Dollar (C$)', 'aqualuxe'),
        ],
    ]));
    
    // Currency switcher position
    $wp_customize->add_setting('aqualuxe_options[currency_switcher_in_menu]', [
        'default' => false,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_currency_switcher_in_menu', [
        'label' => __('Show currency switcher in main menu', 'aqualuxe'),
        'section' => 'aqualuxe_multi_currency',
        'settings' => 'aqualuxe_options[currency_switcher_in_menu]',
        'type' => 'checkbox',
    ]);
    
    $wp_customize->add_setting('aqualuxe_options[currency_switcher_in_mobile_menu]', [
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_currency_switcher_in_mobile_menu', [
        'label' => __('Show currency switcher in mobile menu', 'aqualuxe'),
        'section' => 'aqualuxe_multi_currency',
        'settings' => 'aqualuxe_options[currency_switcher_in_mobile_menu]',
        'type' => 'checkbox',
    ]);
    
    $wp_customize->add_setting('aqualuxe_options[currency_switcher_in_footer]', [
        'default' => false,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_currency_switcher_in_footer', [
        'label' => __('Show currency switcher in footer', 'aqualuxe'),
        'section' => 'aqualuxe_multi_currency',
        'settings' => 'aqualuxe_options[currency_switcher_in_footer]',
        'type' => 'checkbox',
    ]);
    
    // Currency switcher style
    $wp_customize->add_setting('aqualuxe_options[currency_switcher_style]', [
        'default' => 'dropdown',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);
    
    $wp_customize->add_control('aqualuxe_currency_switcher_style', [
        'label' => __('Currency switcher style', 'aqualuxe'),
        'section' => 'aqualuxe_multi_currency',
        'settings' => 'aqualuxe_options[currency_switcher_style]',
        'type' => 'select',
        'choices' => [
            'dropdown' => __('Dropdown', 'aqualuxe'),
            'list' => __('Horizontal list', 'aqualuxe'),
            'symbols' => __('Symbols only', 'aqualuxe'),
        ],
    ]);
    
    // Show currency symbols
    $wp_customize->add_setting('aqualuxe_options[currency_switcher_show_symbols]', [
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_currency_switcher_show_symbols', [
        'label' => __('Show currency symbols', 'aqualuxe'),
        'section' => 'aqualuxe_multi_currency',
        'settings' => 'aqualuxe_options[currency_switcher_show_symbols]',
        'type' => 'checkbox',
    ]);
    
    // Show currency codes
    $wp_customize->add_setting('aqualuxe_options[currency_switcher_show_codes]', [
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_currency_switcher_show_codes', [
        'label' => __('Show currency codes', 'aqualuxe'),
        'section' => 'aqualuxe_multi_currency',
        'settings' => 'aqualuxe_options[currency_switcher_show_codes]',
        'type' => 'checkbox',
    ]);
}
add_action('customize_register', 'aqualuxe_multi_currency_customizer');

/**
 * Sanitize multi choices
 *
 * @param array $input Input value
 * @return array
 */
function aqualuxe_sanitize_multi_choices($input) {
    if (!is_array($input)) {
        return [];
    }
    
    $valid_choices = [
        'USD', 'EUR', 'GBP', 'JPY', 'AUD', 'CAD',
    ];
    
    $output = [];
    
    foreach ($input as $value) {
        if (in_array($value, $valid_choices)) {
            $output[] = $value;
        }
    }
    
    return $output;
}

/**
 * Multiple checkbox customize control class
 */
if (!class_exists('WP_Customize_Multiple_Checkbox_Control')) {
    class WP_Customize_Multiple_Checkbox_Control extends WP_Customize_Control {
        public $type = 'multiple-checkbox';
        
        public function render_content() {
            if (empty($this->choices)) {
                return;
            }
            
            if (!empty($this->label)) {
                echo '<span class="customize-control-title">' . esc_html($this->label) . '</span>';
            }
            
            if (!empty($this->description)) {
                echo '<span class="description customize-control-description">' . esc_html($this->description) . '</span>';
            }
            
            $multi_values = !is_array($this->value()) ? explode(',', $this->value()) : $this->value();
            
            echo '<ul>';
            foreach ($this->choices as $value => $label) {
                echo '<li>';
                echo '<label>';
                echo '<input type="checkbox" value="' . esc_attr($value) . '" ' . checked(in_array($value, $multi_values), true, false) . ' />';
                echo esc_html($label);
                echo '</label>';
                echo '</li>';
            }
            echo '</ul>';
            
            echo '<input type="hidden" ' . $this->get_link() . ' value="' . esc_attr(implode(',', $multi_values)) . '" />';
            
            echo '<script>
                jQuery(document).ready(function($) {
                    $(".customize-control-multiple-checkbox input[type=\'checkbox\']").on("change", function() {
                        var checkbox_values = $(this).parents(".customize-control").find("input[type=\'checkbox\']:checked").map(function() {
                            return this.value;
                        }).get().join(",");
                        
                        $(this).parents(".customize-control").find("input[type=\'hidden\']").val(checkbox_values).trigger("change");
                    });
                });
            </script>';
        }
    }
}

/**
 * Filter WooCommerce price format
 *
 * @param string $format Price format
 * @return string
 */
function aqualuxe_woocommerce_price_format($format) {
    $currency = aqualuxe_get_current_currency();
    $currencies = aqualuxe_get_available_currencies();
    
    if (isset($currencies[$currency])) {
        $currency_data = $currencies[$currency];
        
        switch ($currency_data['position']) {
            case 'left':
                return '%1$s%2$s';
            case 'right':
                return '%2$s%1$s';
            case 'left_space':
                return '%1$s&nbsp;%2$s';
            case 'right_space':
                return '%2$s&nbsp;%1$s';
            default:
                return $format;
        }
    }
    
    return $format;
}
add_filter('woocommerce_price_format', 'aqualuxe_woocommerce_price_format');

/**
 * Filter WooCommerce currency symbol
 *
 * @param string $currency_symbol Currency symbol
 * @param string $currency Currency code
 * @return string
 */
function aqualuxe_woocommerce_currency_symbol($currency_symbol, $currency) {
    $current_currency = aqualuxe_get_current_currency();
    $currencies = aqualuxe_get_available_currencies();
    
    if ($currency === $current_currency && isset($currencies[$current_currency])) {
        return $currencies[$current_currency]['symbol'];
    }
    
    return $currency_symbol;
}
add_filter('woocommerce_currency_symbol', 'aqualuxe_woocommerce_currency_symbol', 10, 2);

/**
 * Filter WooCommerce currency
 *
 * @param string $currency Currency code
 * @return string
 */
function aqualuxe_woocommerce_currency($currency) {
    return aqualuxe_get_current_currency();
}
add_filter('woocommerce_currency', 'aqualuxe_woocommerce_currency');

/**
 * Filter WooCommerce product price
 *
 * @param float $price Product price
 * @param object $product WC_Product object
 * @return float
 */
function aqualuxe_woocommerce_product_get_price($price, $product) {
    $default_currency = aqualuxe_get_option('default_currency', 'USD');
    $current_currency = aqualuxe_get_current_currency();
    
    // If using a currency plugin, let it handle the conversion
    if (class_exists('WOOCS') || (class_exists('WCML_Multi_Currency') && isset($GLOBALS['woocommerce_wpml']))) {
        return $price;
    }
    
    // Convert price if needed
    if ($default_currency !== $current_currency) {
        return aqualuxe_convert_price($price, $default_currency, $current_currency);
    }
    
    return $price;
}
add_filter('woocommerce_product_get_price', 'aqualuxe_woocommerce_product_get_price', 10, 2);
add_filter('woocommerce_product_get_regular_price', 'aqualuxe_woocommerce_product_get_price', 10, 2);
add_filter('woocommerce_product_get_sale_price', 'aqualuxe_woocommerce_product_get_price', 10, 2);

/**
 * Filter WooCommerce variation price
 *
 * @param float $price Product price
 * @param object $variation WC_Product_Variation object
 * @param object $product WC_Product object
 * @return float
 */
function aqualuxe_woocommerce_product_variation_get_price($price, $variation, $product) {
    $default_currency = aqualuxe_get_option('default_currency', 'USD');
    $current_currency = aqualuxe_get_current_currency();
    
    // If using a currency plugin, let it handle the conversion
    if (class_exists('WOOCS') || (class_exists('WCML_Multi_Currency') && isset($GLOBALS['woocommerce_wpml']))) {
        return $price;
    }
    
    // Convert price if needed
    if ($default_currency !== $current_currency) {
        return aqualuxe_convert_price($price, $default_currency, $current_currency);
    }
    
    return $price;
}
add_filter('woocommerce_product_variation_get_price', 'aqualuxe_woocommerce_product_variation_get_price', 10, 3);
add_filter('woocommerce_product_variation_get_regular_price', 'aqualuxe_woocommerce_product_variation_get_price', 10, 3);
add_filter('woocommerce_product_variation_get_sale_price', 'aqualuxe_woocommerce_product_variation_get_price', 10, 3);