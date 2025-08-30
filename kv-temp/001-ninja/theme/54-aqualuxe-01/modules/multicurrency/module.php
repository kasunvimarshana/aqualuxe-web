<?php
/**
 * AquaLuxe Multicurrency Module
 *
 * @package AquaLuxe
 * @subpackage Multicurrency
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe Multicurrency Module Class
 * 
 * Handles multicurrency functionality for the theme
 */
class AquaLuxe_Multicurrency_Module {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_Multicurrency_Module
     */
    private static $instance = null;

    /**
     * Module settings
     *
     * @var array
     */
    private $settings = [];

    /**
     * Available currencies
     *
     * @var array
     */
    private $currencies = [];

    /**
     * Current currency
     *
     * @var string
     */
    private $current_currency = '';

    /**
     * Default currency
     *
     * @var string
     */
    private $default_currency = '';

    /**
     * Get the singleton instance of this class
     *
     * @return AquaLuxe_Multicurrency_Module
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Load module settings
        $this->load_settings();
        
        // Initialize module
        $this->init();
    }

    /**
     * Load module settings
     */
    private function load_settings() {
        $this->settings = apply_filters('aqualuxe_multicurrency_settings', [
            'enabled' => true,
            'default_currency' => 'USD',
            'currencies' => [
                'USD' => [
                    'name' => 'US Dollar',
                    'symbol' => '$',
                    'position' => 'left',
                    'decimals' => 2,
                    'thousand_sep' => ',',
                    'decimal_sep' => '.',
                    'rate' => 1,
                ],
                'EUR' => [
                    'name' => 'Euro',
                    'symbol' => '€',
                    'position' => 'right',
                    'decimals' => 2,
                    'thousand_sep' => '.',
                    'decimal_sep' => ',',
                    'rate' => 0.85,
                ],
                'GBP' => [
                    'name' => 'British Pound',
                    'symbol' => '£',
                    'position' => 'left',
                    'decimals' => 2,
                    'thousand_sep' => ',',
                    'decimal_sep' => '.',
                    'rate' => 0.75,
                ],
                'CAD' => [
                    'name' => 'Canadian Dollar',
                    'symbol' => 'CA$',
                    'position' => 'left',
                    'decimals' => 2,
                    'thousand_sep' => ',',
                    'decimal_sep' => '.',
                    'rate' => 1.25,
                ],
                'AUD' => [
                    'name' => 'Australian Dollar',
                    'symbol' => 'A$',
                    'position' => 'left',
                    'decimals' => 2,
                    'thousand_sep' => ',',
                    'decimal_sep' => '.',
                    'rate' => 1.35,
                ],
            ],
            'show_currency_switcher' => true,
            'switcher_style' => 'dropdown', // dropdown, flags, text
            'switcher_position' => 'top-bar', // top-bar, header, footer, menu
            'auto_detect_currency' => true,
            'update_rates' => 'manual', // manual, daily, weekly
            'api_key' => '',
            'last_update' => '',
        ]);

        // Set currencies
        $this->currencies = $this->settings['currencies'];
        
        // Set default currency
        $this->default_currency = $this->settings['default_currency'];
        
        // Set current currency
        $this->current_currency = $this->get_current_currency();
    }

    /**
     * Initialize module
     */
    private function init() {
        // Check if module is enabled
        if (!$this->settings['enabled']) {
            return;
        }

        // Check if WooCommerce is active
        if (!aqualuxe_is_woocommerce_active()) {
            return;
        }

        // Register hooks
        $this->register_hooks();
    }

    /**
     * Register hooks
     */
    private function register_hooks() {
        // Add currency switcher
        if ($this->settings['show_currency_switcher']) {
            switch ($this->settings['switcher_position']) {
                case 'top-bar':
                    add_action('aqualuxe_top_bar', [$this, 'currency_switcher']);
                    break;
                case 'header':
                    add_action('aqualuxe_header', [$this, 'currency_switcher']);
                    break;
                case 'footer':
                    add_action('aqualuxe_footer', [$this, 'currency_switcher']);
                    break;
                case 'menu':
                    add_filter('wp_nav_menu_items', [$this, 'add_currency_switcher_to_menu'], 10, 2);
                    break;
            }
        }
        
        // Filter currency
        add_filter('woocommerce_currency', [$this, 'get_woocommerce_currency']);
        
        // Filter currency symbol
        add_filter('woocommerce_currency_symbol', [$this, 'get_woocommerce_currency_symbol'], 10, 2);
        
        // Filter price format
        add_filter('woocommerce_price_format', [$this, 'get_woocommerce_price_format'], 10, 2);
        
        // Filter product prices
        add_filter('woocommerce_product_get_price', [$this, 'convert_price'], 10, 2);
        add_filter('woocommerce_product_get_regular_price', [$this, 'convert_price'], 10, 2);
        add_filter('woocommerce_product_get_sale_price', [$this, 'convert_price'], 10, 2);
        
        // Filter variation prices
        add_filter('woocommerce_variation_prices_price', [$this, 'convert_price'], 10, 2);
        add_filter('woocommerce_variation_prices_regular_price', [$this, 'convert_price'], 10, 2);
        add_filter('woocommerce_variation_prices_sale_price', [$this, 'convert_price'], 10, 2);
        
        // Filter shipping cost
        add_filter('woocommerce_package_rates', [$this, 'convert_shipping_rates'], 10, 2);
        
        // Filter coupon amount
        add_filter('woocommerce_coupon_get_amount', [$this, 'convert_coupon_amount'], 10, 2);
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        
        // Add AJAX handler for currency switching
        add_action('wp_ajax_aqualuxe_switch_currency', [$this, 'ajax_switch_currency']);
        add_action('wp_ajax_nopriv_aqualuxe_switch_currency', [$this, 'ajax_switch_currency']);
        
        // Add admin settings
        add_action('admin_init', [$this, 'admin_init']);
        
        // Add admin menu
        add_action('admin_menu', [$this, 'admin_menu']);
        
        // Add customizer settings
        add_action('customize_register', [$this, 'customize_register']);
        
        // Add widget
        add_action('widgets_init', [$this, 'register_widgets']);
        
        // Update exchange rates
        if ($this->settings['update_rates'] !== 'manual') {
            add_action('aqualuxe_daily_cron', [$this, 'maybe_update_rates']);
        }
    }

    /**
     * Get current currency
     *
     * @return string
     */
    public function get_current_currency() {
        // If already set, return it
        if (!empty($this->current_currency)) {
            return $this->current_currency;
        }
        
        $currency = $this->default_currency;
        
        // Check for currency cookie
        if (isset($_COOKIE['aqualuxe_currency'])) {
            $cookie_currency = sanitize_text_field($_COOKIE['aqualuxe_currency']);
            
            // Verify it's a valid currency
            if (isset($this->currencies[$cookie_currency])) {
                $currency = $cookie_currency;
            }
        }
        
        // Auto detect currency if enabled
        if ($this->settings['auto_detect_currency'] && empty($_COOKIE['aqualuxe_currency'])) {
            $detected_currency = $this->get_geo_currency();
            
            if ($detected_currency && isset($this->currencies[$detected_currency])) {
                $currency = $detected_currency;
            }
        }
        
        return $currency;
    }

    /**
     * Get geo-based currency
     *
     * @return string|null
     */
    private function get_geo_currency() {
        // Get visitor's IP address
        $ip = $this->get_client_ip();
        
        // Skip for localhost
        if (in_array($ip, ['127.0.0.1', '::1'])) {
            return null;
        }
        
        // Use a geolocation API to get country code
        $api_url = 'http://ip-api.com/json/' . $ip;
        $response = wp_remote_get($api_url);
        
        if (is_wp_error($response)) {
            return null;
        }
        
        $data = json_decode(wp_remote_retrieve_body($response), true);
        
        if (!isset($data['countryCode'])) {
            return null;
        }
        
        // Map country code to currency
        $country_currency_map = [
            'US' => 'USD',
            'CA' => 'CAD',
            'GB' => 'GBP',
            'AU' => 'AUD',
            'NZ' => 'NZD',
            'DE' => 'EUR',
            'FR' => 'EUR',
            'IT' => 'EUR',
            'ES' => 'EUR',
            'NL' => 'EUR',
            'BE' => 'EUR',
            'AT' => 'EUR',
            'GR' => 'EUR',
            'IE' => 'EUR',
            'PT' => 'EUR',
            'FI' => 'EUR',
            'LU' => 'EUR',
            'SK' => 'EUR',
            'SI' => 'EUR',
            'LT' => 'EUR',
            'LV' => 'EUR',
            'EE' => 'EUR',
            'CY' => 'EUR',
            'MT' => 'EUR',
        ];
        
        $country_code = $data['countryCode'];
        
        if (isset($country_currency_map[$country_code]) && isset($this->currencies[$country_currency_map[$country_code]])) {
            return $country_currency_map[$country_code];
        }
        
        return null;
    }

    /**
     * Get client IP address
     *
     * @return string
     */
    private function get_client_ip() {
        $ip = '';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        return $ip;
    }

    /**
     * Display currency switcher
     */
    public function currency_switcher() {
        // Get current currency
        $current_currency = $this->get_current_currency();
        
        // Get switcher style
        $style = $this->settings['switcher_style'];
        
        // Start output
        echo '<div class="aqualuxe-currency-switcher aqualuxe-currency-switcher-' . esc_attr($style) . '">';
        
        switch ($style) {
            case 'dropdown':
                $this->dropdown_currency_switcher($current_currency);
                break;
            case 'flags':
                $this->flags_currency_switcher($current_currency);
                break;
            case 'text':
                $this->text_currency_switcher($current_currency);
                break;
        }
        
        echo '</div>';
    }

    /**
     * Display dropdown currency switcher
     *
     * @param string $current_currency Current currency code
     */
    private function dropdown_currency_switcher($current_currency) {
        echo '<select class="aqualuxe-currency-select">';
        
        foreach ($this->currencies as $code => $currency) {
            $selected = $code === $current_currency ? ' selected' : '';
            
            echo '<option value="' . esc_attr($code) . '"' . $selected . '>';
            echo esc_html($currency['name']) . ' (' . esc_html($currency['symbol']) . ')';
            echo '</option>';
        }
        
        echo '</select>';
    }

    /**
     * Display flags currency switcher
     *
     * @param string $current_currency Current currency code
     */
    private function flags_currency_switcher($current_currency) {
        echo '<ul class="aqualuxe-currency-flags">';
        
        foreach ($this->currencies as $code => $currency) {
            $active = $code === $current_currency ? ' aqualuxe-currency-active' : '';
            $flag = $this->get_currency_flag($code);
            
            echo '<li class="aqualuxe-currency-flag-item' . $active . '" data-currency="' . esc_attr($code) . '">';
            echo '<a href="#" title="' . esc_attr($currency['name']) . '">';
            echo '<img src="' . esc_url($flag) . '" alt="' . esc_attr($code) . '">';
            echo '</a>';
            echo '</li>';
        }
        
        echo '</ul>';
    }

    /**
     * Display text currency switcher
     *
     * @param string $current_currency Current currency code
     */
    private function text_currency_switcher($current_currency) {
        echo '<ul class="aqualuxe-currency-text">';
        
        foreach ($this->currencies as $code => $currency) {
            $active = $code === $current_currency ? ' aqualuxe-currency-active' : '';
            
            echo '<li class="aqualuxe-currency-text-item' . $active . '" data-currency="' . esc_attr($code) . '">';
            echo '<a href="#">';
            echo esc_html($currency['symbol']);
            echo '</a>';
            echo '</li>';
        }
        
        echo '</ul>';
    }

    /**
     * Add currency switcher to menu
     *
     * @param string $items Menu items HTML
     * @param object $args Menu arguments
     * @return string
     */
    public function add_currency_switcher_to_menu($items, $args) {
        // Only add to primary menu
        if ($args->theme_location !== 'primary') {
            return $items;
        }
        
        // Get current currency
        $current_currency = $this->get_current_currency();
        $current = $this->currencies[$current_currency];
        
        // Start output
        $output = '<li class="menu-item menu-item-has-children aqualuxe-currency-menu-item">';
        $output .= '<a href="#">';
        $output .= esc_html($current['symbol']) . ' ' . esc_html($current_currency);
        $output .= '</a>';
        
        // Sub-menu with currencies
        $output .= '<ul class="sub-menu">';
        
        foreach ($this->currencies as $code => $currency) {
            if ($code === $current_currency) {
                continue;
            }
            
            $output .= '<li class="menu-item">';
            $output .= '<a href="#" data-currency="' . esc_attr($code) . '">';
            $output .= esc_html($currency['symbol']) . ' ' . esc_html($code);
            $output .= '</a>';
            $output .= '</li>';
        }
        
        $output .= '</ul>';
        $output .= '</li>';
        
        return $items . $output;
    }

    /**
     * Get currency flag URL
     *
     * @param string $currency_code Currency code
     * @return string
     */
    private function get_currency_flag($currency_code) {
        $currency_flag_map = [
            'USD' => 'us.png',
            'EUR' => 'eu.png',
            'GBP' => 'gb.png',
            'CAD' => 'ca.png',
            'AUD' => 'au.png',
            'NZD' => 'nz.png',
            'JPY' => 'jp.png',
            'CHF' => 'ch.png',
            'CNY' => 'cn.png',
            'INR' => 'in.png',
            'BRL' => 'br.png',
            'RUB' => 'ru.png',
            'KRW' => 'kr.png',
            'SGD' => 'sg.png',
            'MXN' => 'mx.png',
        ];
        
        $flag = isset($currency_flag_map[$currency_code]) ? $currency_flag_map[$currency_code] : 'generic.png';
        
        return AQUALUXE_URI . 'modules/multicurrency/assets/flags/' . $flag;
    }

    /**
     * Get WooCommerce currency
     *
     * @param string $currency Currency code
     * @return string
     */
    public function get_woocommerce_currency($currency) {
        return $this->current_currency;
    }

    /**
     * Get WooCommerce currency symbol
     *
     * @param string $symbol Currency symbol
     * @param string $currency Currency code
     * @return string
     */
    public function get_woocommerce_currency_symbol($symbol, $currency) {
        if (isset($this->currencies[$this->current_currency]['symbol'])) {
            return $this->currencies[$this->current_currency]['symbol'];
        }
        
        return $symbol;
    }

    /**
     * Get WooCommerce price format
     *
     * @param string $format Price format
     * @param string $currency Currency code
     * @return string
     */
    public function get_woocommerce_price_format($format, $currency) {
        if (isset($this->currencies[$this->current_currency]['position'])) {
            $position = $this->currencies[$this->current_currency]['position'];
            
            switch ($position) {
                case 'left':
                    return '%1$s%2$s';
                case 'right':
                    return '%2$s%1$s';
                case 'left_space':
                    return '%1$s&nbsp;%2$s';
                case 'right_space':
                    return '%2$s&nbsp;%1$s';
            }
        }
        
        return $format;
    }

    /**
     * Convert price
     *
     * @param float $price Price
     * @param object $product Product object
     * @return float
     */
    public function convert_price($price, $product) {
        // Skip if price is empty
        if (empty($price)) {
            return $price;
        }
        
        // Skip if current currency is default currency
        if ($this->current_currency === $this->default_currency) {
            return $price;
        }
        
        // Get exchange rate
        $rate = $this->get_exchange_rate($this->current_currency);
        
        // Convert price
        $converted_price = $price * $rate;
        
        return $converted_price;
    }

    /**
     * Convert shipping rates
     *
     * @param array $rates Shipping rates
     * @param array $package Shipping package
     * @return array
     */
    public function convert_shipping_rates($rates, $package) {
        // Skip if current currency is default currency
        if ($this->current_currency === $this->default_currency) {
            return $rates;
        }
        
        // Get exchange rate
        $rate = $this->get_exchange_rate($this->current_currency);
        
        foreach ($rates as $key => $shipping_rate) {
            // Convert cost
            $rates[$key]->cost = $shipping_rate->cost * $rate;
            
            // Convert taxes
            if (!empty($shipping_rate->taxes)) {
                foreach ($shipping_rate->taxes as $tax_id => $tax) {
                    $rates[$key]->taxes[$tax_id] = $tax * $rate;
                }
            }
        }
        
        return $rates;
    }

    /**
     * Convert coupon amount
     *
     * @param float $amount Coupon amount
     * @param object $coupon Coupon object
     * @return float
     */
    public function convert_coupon_amount($amount, $coupon) {
        // Skip if current currency is default currency
        if ($this->current_currency === $this->default_currency) {
            return $amount;
        }
        
        // Skip if coupon is percentage
        if ($coupon->is_type('percent')) {
            return $amount;
        }
        
        // Get exchange rate
        $rate = $this->get_exchange_rate($this->current_currency);
        
        // Convert amount
        $converted_amount = $amount * $rate;
        
        return $converted_amount;
    }

    /**
     * Get exchange rate
     *
     * @param string $currency Currency code
     * @return float
     */
    public function get_exchange_rate($currency) {
        if (isset($this->currencies[$currency]['rate'])) {
            return floatval($this->currencies[$currency]['rate']);
        }
        
        return 1;
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        // Enqueue styles
        wp_enqueue_style(
            'aqualuxe-multicurrency',
            AQUALUXE_URI . 'modules/multicurrency/assets/css/multicurrency.css',
            [],
            AQUALUXE_VERSION
        );
        
        // Enqueue scripts
        wp_enqueue_script(
            'aqualuxe-multicurrency',
            AQUALUXE_URI . 'modules/multicurrency/assets/js/multicurrency.js',
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('aqualuxe-multicurrency', 'aqualuxeMulticurrency', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-multicurrency-nonce'),
            'currentCurrency' => $this->current_currency,
            'defaultCurrency' => $this->default_currency,
        ]);
    }

    /**
     * AJAX switch currency
     */
    public function ajax_switch_currency() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-multicurrency-nonce')) {
            wp_send_json_error('Invalid nonce');
        }
        
        // Check currency
        if (!isset($_POST['currency'])) {
            wp_send_json_error('Currency not specified');
        }
        
        $currency = sanitize_text_field($_POST['currency']);
        
        // Verify currency is valid
        if (!isset($this->currencies[$currency])) {
            wp_send_json_error('Invalid currency');
        }
        
        // Set cookie
        setcookie('aqualuxe_currency', $currency, time() + (86400 * 30), '/');
        
        wp_send_json_success([
            'currency' => $currency,
            'symbol' => $this->currencies[$currency]['symbol'],
        ]);
    }

    /**
     * Admin init
     */
    public function admin_init() {
        // Register settings
        register_setting('aqualuxe_multicurrency_settings', 'aqualuxe_multicurrency_settings', [
            'sanitize_callback' => [$this, 'sanitize_settings'],
        ]);
        
        // Add settings section
        add_settings_section(
            'aqualuxe_multicurrency_section',
            __('Multicurrency Settings', 'aqualuxe'),
            [$this, 'settings_section_callback'],
            'aqualuxe_multicurrency'
        );
        
        // Add settings fields
        add_settings_field(
            'aqualuxe_multicurrency_enabled',
            __('Enable Multicurrency', 'aqualuxe'),
            [$this, 'enabled_field_callback'],
            'aqualuxe_multicurrency',
            'aqualuxe_multicurrency_section'
        );
        
        add_settings_field(
            'aqualuxe_multicurrency_default',
            __('Default Currency', 'aqualuxe'),
            [$this, 'default_field_callback'],
            'aqualuxe_multicurrency',
            'aqualuxe_multicurrency_section'
        );
        
        add_settings_field(
            'aqualuxe_multicurrency_currencies',
            __('Available Currencies', 'aqualuxe'),
            [$this, 'currencies_field_callback'],
            'aqualuxe_multicurrency',
            'aqualuxe_multicurrency_section'
        );
        
        add_settings_field(
            'aqualuxe_multicurrency_switcher',
            __('Show Currency Switcher', 'aqualuxe'),
            [$this, 'switcher_field_callback'],
            'aqualuxe_multicurrency',
            'aqualuxe_multicurrency_section'
        );
        
        add_settings_field(
            'aqualuxe_multicurrency_switcher_style',
            __('Switcher Style', 'aqualuxe'),
            [$this, 'switcher_style_field_callback'],
            'aqualuxe_multicurrency',
            'aqualuxe_multicurrency_section'
        );
        
        add_settings_field(
            'aqualuxe_multicurrency_switcher_position',
            __('Switcher Position', 'aqualuxe'),
            [$this, 'switcher_position_field_callback'],
            'aqualuxe_multicurrency',
            'aqualuxe_multicurrency_section'
        );
        
        add_settings_field(
            'aqualuxe_multicurrency_auto_detect',
            __('Auto-detect Currency', 'aqualuxe'),
            [$this, 'auto_detect_field_callback'],
            'aqualuxe_multicurrency',
            'aqualuxe_multicurrency_section'
        );
        
        add_settings_field(
            'aqualuxe_multicurrency_update_rates',
            __('Update Exchange Rates', 'aqualuxe'),
            [$this, 'update_rates_field_callback'],
            'aqualuxe_multicurrency',
            'aqualuxe_multicurrency_section'
        );
        
        add_settings_field(
            'aqualuxe_multicurrency_api_key',
            __('Exchange Rate API Key', 'aqualuxe'),
            [$this, 'api_key_field_callback'],
            'aqualuxe_multicurrency',
            'aqualuxe_multicurrency_section'
        );
    }

    /**
     * Admin menu
     */
    public function admin_menu() {
        add_submenu_page(
            'woocommerce',
            __('Multicurrency Settings', 'aqualuxe'),
            __('Multicurrency', 'aqualuxe'),
            'manage_options',
            'aqualuxe_multicurrency',
            [$this, 'settings_page']
        );
    }

    /**
     * Settings section callback
     */
    public function settings_section_callback() {
        echo '<p>' . esc_html__('Configure multicurrency settings for your store.', 'aqualuxe') . '</p>';
    }

    /**
     * Enabled field callback
     */
    public function enabled_field_callback() {
        $settings = get_option('aqualuxe_multicurrency_settings', []);
        $enabled = isset($settings['enabled']) ? $settings['enabled'] : true;
        
        echo '<input type="checkbox" name="aqualuxe_multicurrency_settings[enabled]" value="1"' . checked($enabled, true, false) . '>';
    }

    /**
     * Default field callback
     */
    public function default_field_callback() {
        $settings = get_option('aqualuxe_multicurrency_settings', []);
        $default = isset($settings['default_currency']) ? $settings['default_currency'] : 'USD';
        
        echo '<select name="aqualuxe_multicurrency_settings[default_currency]">';
        
        foreach ($this->currencies as $code => $currency) {
            echo '<option value="' . esc_attr($code) . '"' . selected($default, $code, false) . '>';
            echo esc_html($currency['name']) . ' (' . esc_html($code) . ')';
            echo '</option>';
        }
        
        echo '</select>';
    }

    /**
     * Currencies field callback
     */
    public function currencies_field_callback() {
        $settings = get_option('aqualuxe_multicurrency_settings', []);
        $currencies = isset($settings['currencies']) ? $settings['currencies'] : $this->currencies;
        
        echo '<div class="aqualuxe-multicurrency-currencies">';
        echo '<table class="widefat" id="aqualuxe-multicurrency-table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>' . esc_html__('Code', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Name', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Symbol', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Position', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Decimals', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Thousand Sep', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Decimal Sep', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Exchange Rate', 'aqualuxe') . '</th>';
        echo '<th>' . esc_html__('Actions', 'aqualuxe') . '</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        foreach ($currencies as $code => $currency) {
            echo '<tr>';
            echo '<td><input type="text" name="aqualuxe_multicurrency_settings[currencies][' . esc_attr($code) . '][code]" value="' . esc_attr($code) . '" readonly></td>';
            echo '<td><input type="text" name="aqualuxe_multicurrency_settings[currencies][' . esc_attr($code) . '][name]" value="' . esc_attr($currency['name']) . '"></td>';
            echo '<td><input type="text" name="aqualuxe_multicurrency_settings[currencies][' . esc_attr($code) . '][symbol]" value="' . esc_attr($currency['symbol']) . '"></td>';
            echo '<td>';
            echo '<select name="aqualuxe_multicurrency_settings[currencies][' . esc_attr($code) . '][position]">';
            echo '<option value="left"' . selected($currency['position'], 'left', false) . '>' . esc_html__('Left', 'aqualuxe') . '</option>';
            echo '<option value="right"' . selected($currency['position'], 'right', false) . '>' . esc_html__('Right', 'aqualuxe') . '</option>';
            echo '<option value="left_space"' . selected($currency['position'], 'left_space', false) . '>' . esc_html__('Left with space', 'aqualuxe') . '</option>';
            echo '<option value="right_space"' . selected($currency['position'], 'right_space', false) . '>' . esc_html__('Right with space', 'aqualuxe') . '</option>';
            echo '</select>';
            echo '</td>';
            echo '<td><input type="number" name="aqualuxe_multicurrency_settings[currencies][' . esc_attr($code) . '][decimals]" value="' . esc_attr($currency['decimals']) . '" min="0" max="4" step="1"></td>';
            echo '<td><input type="text" name="aqualuxe_multicurrency_settings[currencies][' . esc_attr($code) . '][thousand_sep]" value="' . esc_attr($currency['thousand_sep']) . '"></td>';
            echo '<td><input type="text" name="aqualuxe_multicurrency_settings[currencies][' . esc_attr($code) . '][decimal_sep]" value="' . esc_attr($currency['decimal_sep']) . '"></td>';
            echo '<td><input type="number" name="aqualuxe_multicurrency_settings[currencies][' . esc_attr($code) . '][rate]" value="' . esc_attr($currency['rate']) . '" min="0" step="0.0001"></td>';
            echo '<td>';
            if ($code !== $this->default_currency) {
                echo '<button type="button" class="button aqualuxe-remove-currency" data-code="' . esc_attr($code) . '">' . esc_html__('Remove', 'aqualuxe') . '</button>';
            } else {
                echo esc_html__('Default', 'aqualuxe');
            }
            echo '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '<tfoot>';
        echo '<tr>';
        echo '<td colspan="9">';
        echo '<button type="button" class="button button-primary" id="aqualuxe-add-currency">' . esc_html__('Add Currency', 'aqualuxe') . '</button>';
        echo '</td>';
        echo '</tr>';
        echo '</tfoot>';
        echo '</table>';
        echo '</div>';
        
        // Add JavaScript for adding/removing currencies
        ?>
        <script>
            jQuery(document).ready(function($) {
                // Add currency
                $('#aqualuxe-add-currency').on('click', function() {
                    var code = prompt('<?php echo esc_js(__('Enter currency code (e.g., USD, EUR, GBP)', 'aqualuxe')); ?>');
                    
                    if (!code) {
                        return;
                    }
                    
                    code = code.toUpperCase();
                    
                    // Check if currency already exists
                    if ($('input[value="' + code + '"]').length > 0) {
                        alert('<?php echo esc_js(__('Currency already exists', 'aqualuxe')); ?>');
                        return;
                    }
                    
                    var name = prompt('<?php echo esc_js(__('Enter currency name (e.g., US Dollar)', 'aqualuxe')); ?>');
                    
                    if (!name) {
                        return;
                    }
                    
                    var symbol = prompt('<?php echo esc_js(__('Enter currency symbol (e.g., $)', 'aqualuxe')); ?>');
                    
                    if (!symbol) {
                        return;
                    }
                    
                    var rate = prompt('<?php echo esc_js(__('Enter exchange rate (e.g., 1.2)', 'aqualuxe')); ?>');
                    
                    if (!rate) {
                        return;
                    }
                    
                    var html = '<tr>';
                    html += '<td><input type="text" name="aqualuxe_multicurrency_settings[currencies][' + code + '][code]" value="' + code + '" readonly></td>';
                    html += '<td><input type="text" name="aqualuxe_multicurrency_settings[currencies][' + code + '][name]" value="' + name + '"></td>';
                    html += '<td><input type="text" name="aqualuxe_multicurrency_settings[currencies][' + code + '][symbol]" value="' + symbol + '"></td>';
                    html += '<td>';
                    html += '<select name="aqualuxe_multicurrency_settings[currencies][' + code + '][position]">';
                    html += '<option value="left"><?php echo esc_js(__('Left', 'aqualuxe')); ?></option>';
                    html += '<option value="right"><?php echo esc_js(__('Right', 'aqualuxe')); ?></option>';
                    html += '<option value="left_space"><?php echo esc_js(__('Left with space', 'aqualuxe')); ?></option>';
                    html += '<option value="right_space"><?php echo esc_js(__('Right with space', 'aqualuxe')); ?></option>';
                    html += '</select>';
                    html += '</td>';
                    html += '<td><input type="number" name="aqualuxe_multicurrency_settings[currencies][' + code + '][decimals]" value="2" min="0" max="4" step="1"></td>';
                    html += '<td><input type="text" name="aqualuxe_multicurrency_settings[currencies][' + code + '][thousand_sep]" value=","></td>';
                    html += '<td><input type="text" name="aqualuxe_multicurrency_settings[currencies][' + code + '][decimal_sep]" value="."></td>';
                    html += '<td><input type="number" name="aqualuxe_multicurrency_settings[currencies][' + code + '][rate]" value="' + rate + '" min="0" step="0.0001"></td>';
                    html += '<td><button type="button" class="button aqualuxe-remove-currency" data-code="' + code + '"><?php echo esc_js(__('Remove', 'aqualuxe')); ?></button></td>';
                    html += '</tr>';
                    
                    $('#aqualuxe-multicurrency-table tbody').append(html);
                });
                
                // Remove currency
                $(document).on('click', '.aqualuxe-remove-currency', function() {
                    var code = $(this).data('code');
                    
                    if (confirm('<?php echo esc_js(__('Are you sure you want to remove this currency?', 'aqualuxe')); ?>')) {
                        $(this).closest('tr').remove();
                    }
                });
            });
        </script>
        <?php
    }

    /**
     * Switcher field callback
     */
    public function switcher_field_callback() {
        $settings = get_option('aqualuxe_multicurrency_settings', []);
        $show_switcher = isset($settings['show_currency_switcher']) ? $settings['show_currency_switcher'] : true;
        
        echo '<input type="checkbox" name="aqualuxe_multicurrency_settings[show_currency_switcher]" value="1"' . checked($show_switcher, true, false) . '>';
    }

    /**
     * Switcher style field callback
     */
    public function switcher_style_field_callback() {
        $settings = get_option('aqualuxe_multicurrency_settings', []);
        $style = isset($settings['switcher_style']) ? $settings['switcher_style'] : 'dropdown';
        
        echo '<select name="aqualuxe_multicurrency_settings[switcher_style]">';
        echo '<option value="dropdown"' . selected($style, 'dropdown', false) . '>' . esc_html__('Dropdown', 'aqualuxe') . '</option>';
        echo '<option value="flags"' . selected($style, 'flags', false) . '>' . esc_html__('Flags', 'aqualuxe') . '</option>';
        echo '<option value="text"' . selected($style, 'text', false) . '>' . esc_html__('Text', 'aqualuxe') . '</option>';
        echo '</select>';
    }

    /**
     * Switcher position field callback
     */
    public function switcher_position_field_callback() {
        $settings = get_option('aqualuxe_multicurrency_settings', []);
        $position = isset($settings['switcher_position']) ? $settings['switcher_position'] : 'top-bar';
        
        echo '<select name="aqualuxe_multicurrency_settings[switcher_position]">';
        echo '<option value="top-bar"' . selected($position, 'top-bar', false) . '>' . esc_html__('Top Bar', 'aqualuxe') . '</option>';
        echo '<option value="header"' . selected($position, 'header', false) . '>' . esc_html__('Header', 'aqualuxe') . '</option>';
        echo '<option value="footer"' . selected($position, 'footer', false) . '>' . esc_html__('Footer', 'aqualuxe') . '</option>';
        echo '<option value="menu"' . selected($position, 'menu', false) . '>' . esc_html__('Primary Menu', 'aqualuxe') . '</option>';
        echo '</select>';
    }

    /**
     * Auto detect field callback
     */
    public function auto_detect_field_callback() {
        $settings = get_option('aqualuxe_multicurrency_settings', []);
        $auto_detect = isset($settings['auto_detect_currency']) ? $settings['auto_detect_currency'] : true;
        
        echo '<input type="checkbox" name="aqualuxe_multicurrency_settings[auto_detect_currency]" value="1"' . checked($auto_detect, true, false) . '>';
    }

    /**
     * Update rates field callback
     */
    public function update_rates_field_callback() {
        $settings = get_option('aqualuxe_multicurrency_settings', []);
        $update_rates = isset($settings['update_rates']) ? $settings['update_rates'] : 'manual';
        
        echo '<select name="aqualuxe_multicurrency_settings[update_rates]">';
        echo '<option value="manual"' . selected($update_rates, 'manual', false) . '>' . esc_html__('Manual', 'aqualuxe') . '</option>';
        echo '<option value="daily"' . selected($update_rates, 'daily', false) . '>' . esc_html__('Daily', 'aqualuxe') . '</option>';
        echo '<option value="weekly"' . selected($update_rates, 'weekly', false) . '>' . esc_html__('Weekly', 'aqualuxe') . '</option>';
        echo '</select>';
        
        // Add update button
        echo ' <button type="button" class="button" id="aqualuxe-update-rates">' . esc_html__('Update Now', 'aqualuxe') . '</button>';
        
        // Add last update info
        if (isset($settings['last_update']) && !empty($settings['last_update'])) {
            echo '<p><em>' . sprintf(
                esc_html__('Last updated: %s', 'aqualuxe'),
                date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $settings['last_update'])
            ) . '</em></p>';
        }
        
        // Add JavaScript for updating rates
        ?>
        <script>
            jQuery(document).ready(function($) {
                $('#aqualuxe-update-rates').on('click', function() {
                    var button = $(this);
                    button.prop('disabled', true).text('<?php echo esc_js(__('Updating...', 'aqualuxe')); ?>');
                    
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_update_rates',
                            nonce: '<?php echo wp_create_nonce('aqualuxe-update-rates-nonce'); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                alert('<?php echo esc_js(__('Exchange rates updated successfully', 'aqualuxe')); ?>');
                                location.reload();
                            } else {
                                alert(response.data);
                            }
                        },
                        error: function() {
                            alert('<?php echo esc_js(__('Error updating exchange rates', 'aqualuxe')); ?>');
                        },
                        complete: function() {
                            button.prop('disabled', false).text('<?php echo esc_js(__('Update Now', 'aqualuxe')); ?>');
                        }
                    });
                });
            });
        </script>
        <?php
    }

    /**
     * API key field callback
     */
    public function api_key_field_callback() {
        $settings = get_option('aqualuxe_multicurrency_settings', []);
        $api_key = isset($settings['api_key']) ? $settings['api_key'] : '';
        
        echo '<input type="text" name="aqualuxe_multicurrency_settings[api_key]" value="' . esc_attr($api_key) . '" class="regular-text">';
        echo '<p class="description">' . esc_html__('API key for exchangeratesapi.io (required for automatic updates)', 'aqualuxe') . '</p>';
    }

    /**
     * Settings page
     */
    public function settings_page() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Show settings form
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('aqualuxe_multicurrency_settings');
                do_settings_sections('aqualuxe_multicurrency');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Sanitize settings
     *
     * @param array $input Settings input
     * @return array
     */
    public function sanitize_settings($input) {
        $output = [];
        
        // Sanitize enabled
        $output['enabled'] = isset($input['enabled']) ? (bool) $input['enabled'] : false;
        
        // Sanitize default currency
        $output['default_currency'] = isset($input['default_currency']) ? sanitize_text_field($input['default_currency']) : 'USD';
        
        // Sanitize currencies
        $output['currencies'] = [];
        
        if (isset($input['currencies']) && is_array($input['currencies'])) {
            foreach ($input['currencies'] as $code => $currency) {
                $code = sanitize_text_field($code);
                
                $output['currencies'][$code] = [
                    'name' => sanitize_text_field($currency['name']),
                    'symbol' => sanitize_text_field($currency['symbol']),
                    'position' => sanitize_text_field($currency['position']),
                    'decimals' => absint($currency['decimals']),
                    'thousand_sep' => sanitize_text_field($currency['thousand_sep']),
                    'decimal_sep' => sanitize_text_field($currency['decimal_sep']),
                    'rate' => floatval($currency['rate']),
                ];
            }
        }
        
        // Sanitize show currency switcher
        $output['show_currency_switcher'] = isset($input['show_currency_switcher']) ? (bool) $input['show_currency_switcher'] : false;
        
        // Sanitize switcher style
        $output['switcher_style'] = isset($input['switcher_style']) && in_array($input['switcher_style'], ['dropdown', 'flags', 'text']) ? $input['switcher_style'] : 'dropdown';
        
        // Sanitize switcher position
        $output['switcher_position'] = isset($input['switcher_position']) && in_array($input['switcher_position'], ['top-bar', 'header', 'footer', 'menu']) ? $input['switcher_position'] : 'top-bar';
        
        // Sanitize auto detect currency
        $output['auto_detect_currency'] = isset($input['auto_detect_currency']) ? (bool) $input['auto_detect_currency'] : false;
        
        // Sanitize update rates
        $output['update_rates'] = isset($input['update_rates']) && in_array($input['update_rates'], ['manual', 'daily', 'weekly']) ? $input['update_rates'] : 'manual';
        
        // Sanitize API key
        $output['api_key'] = isset($input['api_key']) ? sanitize_text_field($input['api_key']) : '';
        
        // Keep last update timestamp
        $settings = get_option('aqualuxe_multicurrency_settings', []);
        $output['last_update'] = isset($settings['last_update']) ? $settings['last_update'] : '';
        
        return $output;
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer object
     */
    public function customize_register($wp_customize) {
        // Add section
        $wp_customize->add_section('aqualuxe_multicurrency', [
            'title' => __('Multicurrency Settings', 'aqualuxe'),
            'priority' => 140,
        ]);
        
        // Add settings
        $wp_customize->add_setting('aqualuxe_multicurrency_enabled', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        $wp_customize->add_setting('aqualuxe_multicurrency_default', [
            'default' => 'USD',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $wp_customize->add_setting('aqualuxe_multicurrency_switcher', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        $wp_customize->add_setting('aqualuxe_multicurrency_switcher_style', [
            'default' => 'dropdown',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $wp_customize->add_setting('aqualuxe_multicurrency_switcher_position', [
            'default' => 'top-bar',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $wp_customize->add_setting('aqualuxe_multicurrency_auto_detect', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        // Add controls
        $wp_customize->add_control('aqualuxe_multicurrency_enabled', [
            'label' => __('Enable Multicurrency', 'aqualuxe'),
            'section' => 'aqualuxe_multicurrency',
            'type' => 'checkbox',
        ]);
        
        $wp_customize->add_control('aqualuxe_multicurrency_default', [
            'label' => __('Default Currency', 'aqualuxe'),
            'section' => 'aqualuxe_multicurrency',
            'type' => 'select',
            'choices' => $this->get_currency_choices(),
        ]);
        
        $wp_customize->add_control('aqualuxe_multicurrency_switcher', [
            'label' => __('Show Currency Switcher', 'aqualuxe'),
            'section' => 'aqualuxe_multicurrency',
            'type' => 'checkbox',
        ]);
        
        $wp_customize->add_control('aqualuxe_multicurrency_switcher_style', [
            'label' => __('Switcher Style', 'aqualuxe'),
            'section' => 'aqualuxe_multicurrency',
            'type' => 'select',
            'choices' => [
                'dropdown' => __('Dropdown', 'aqualuxe'),
                'flags' => __('Flags', 'aqualuxe'),
                'text' => __('Text', 'aqualuxe'),
            ],
        ]);
        
        $wp_customize->add_control('aqualuxe_multicurrency_switcher_position', [
            'label' => __('Switcher Position', 'aqualuxe'),
            'section' => 'aqualuxe_multicurrency',
            'type' => 'select',
            'choices' => [
                'top-bar' => __('Top Bar', 'aqualuxe'),
                'header' => __('Header', 'aqualuxe'),
                'footer' => __('Footer', 'aqualuxe'),
                'menu' => __('Primary Menu', 'aqualuxe'),
            ],
        ]);
        
        $wp_customize->add_control('aqualuxe_multicurrency_auto_detect', [
            'label' => __('Auto-detect Currency', 'aqualuxe'),
            'section' => 'aqualuxe_multicurrency',
            'type' => 'checkbox',
        ]);
    }

    /**
     * Get currency choices for customizer
     *
     * @return array
     */
    private function get_currency_choices() {
        $choices = [];
        
        foreach ($this->currencies as $code => $currency) {
            $choices[$code] = $currency['name'] . ' (' . $code . ')';
        }
        
        return $choices;
    }

    /**
     * Register widgets
     */
    public function register_widgets() {
        register_widget('AquaLuxe_Multicurrency_Widget');
    }

    /**
     * Maybe update rates
     */
    public function maybe_update_rates() {
        $settings = get_option('aqualuxe_multicurrency_settings', []);
        
        // Skip if update rates is manual
        if (!isset($settings['update_rates']) || $settings['update_rates'] === 'manual') {
            return;
        }
        
        // Check if API key is set
        if (!isset($settings['api_key']) || empty($settings['api_key'])) {
            return;
        }
        
        // Check if last update is set
        if (!isset($settings['last_update']) || empty($settings['last_update'])) {
            $this->update_rates();
            return;
        }
        
        // Check if it's time to update
        $now = time();
        $last_update = $settings['last_update'];
        
        if ($settings['update_rates'] === 'daily' && ($now - $last_update) >= DAY_IN_SECONDS) {
            $this->update_rates();
        } elseif ($settings['update_rates'] === 'weekly' && ($now - $last_update) >= WEEK_IN_SECONDS) {
            $this->update_rates();
        }
    }

    /**
     * Update rates
     */
    public function update_rates() {
        $settings = get_option('aqualuxe_multicurrency_settings', []);
        
        // Check if API key is set
        if (!isset($settings['api_key']) || empty($settings['api_key'])) {
            return false;
        }
        
        // Get default currency
        $default_currency = isset($settings['default_currency']) ? $settings['default_currency'] : 'USD';
        
        // Get currencies
        $currencies = isset($settings['currencies']) ? $settings['currencies'] : [];
        
        // Skip if no currencies
        if (empty($currencies)) {
            return false;
        }
        
        // Get currency codes
        $currency_codes = array_keys($currencies);
        
        // Remove default currency
        $currency_codes = array_diff($currency_codes, [$default_currency]);
        
        // Skip if no currencies to update
        if (empty($currency_codes)) {
            return false;
        }
        
        // Build API URL
        $api_url = 'http://api.exchangeratesapi.io/v1/latest';
        $api_url .= '?access_key=' . urlencode($settings['api_key']);
        $api_url .= '&base=' . urlencode($default_currency);
        $api_url .= '&symbols=' . urlencode(implode(',', $currency_codes));
        
        // Get exchange rates
        $response = wp_remote_get($api_url);
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $data = json_decode(wp_remote_retrieve_body($response), true);
        
        if (!isset($data['rates']) || empty($data['rates'])) {
            return false;
        }
        
        // Update exchange rates
        foreach ($data['rates'] as $code => $rate) {
            if (isset($currencies[$code])) {
                $currencies[$code]['rate'] = $rate;
            }
        }
        
        // Set default currency rate to 1
        $currencies[$default_currency]['rate'] = 1;
        
        // Update settings
        $settings['currencies'] = $currencies;
        $settings['last_update'] = time();
        
        update_option('aqualuxe_multicurrency_settings', $settings);
        
        return true;
    }
}

/**
 * AquaLuxe Multicurrency Widget
 */
class AquaLuxe_Multicurrency_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_multicurrency_widget',
            __('AquaLuxe Currency Switcher', 'aqualuxe'),
            [
                'description' => __('Displays a currency switcher for your store.', 'aqualuxe'),
            ]
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments
     * @param array $instance Widget instance
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        // Get style
        $style = !empty($instance['style']) ? $instance['style'] : 'dropdown';
        
        // Get multicurrency module
        $multicurrency = AquaLuxe_Multicurrency_Module::instance();
        
        // Override style
        add_filter('aqualuxe_multicurrency_settings', function($settings) use ($style) {
            $settings['switcher_style'] = $style;
            return $settings;
        });
        
        // Display currency switcher
        $multicurrency->currency_switcher();
        
        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance
     * @return void
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $style = !empty($instance['style']) ? $instance['style'] : 'dropdown';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Title:', 'aqualuxe'); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('style')); ?>">
                <?php esc_html_e('Style:', 'aqualuxe'); ?>
            </label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('style')); ?>" name="<?php echo esc_attr($this->get_field_name('style')); ?>">
                <option value="dropdown" <?php selected($style, 'dropdown'); ?>><?php esc_html_e('Dropdown', 'aqualuxe'); ?></option>
                <option value="flags" <?php selected($style, 'flags'); ?>><?php esc_html_e('Flags', 'aqualuxe'); ?></option>
                <option value="text" <?php selected($style, 'text'); ?>><?php esc_html_e('Text', 'aqualuxe'); ?></option>
            </select>
        </p>
        <?php
    }

    /**
     * Update widget
     *
     * @param array $new_instance New instance
     * @param array $old_instance Old instance
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['style'] = (!empty($new_instance['style'])) ? sanitize_text_field($new_instance['style']) : 'dropdown';
        
        return $instance;
    }
}

// Initialize the module
AquaLuxe_Multicurrency_Module::instance();