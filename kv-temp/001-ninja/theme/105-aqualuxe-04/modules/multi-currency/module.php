<?php
/**
 * Multi-Currency Module
 *
 * Handles multiple currency support for global operations
 *
 * @package AquaLuxe\Modules\MultiCurrency
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class AquaLuxe_Multi_Currency_Module
 *
 * Manages multi-currency functionality
 */
class AquaLuxe_Multi_Currency_Module {
    
    /**
     * Single instance of the class
     *
     * @var AquaLuxe_Multi_Currency_Module
     */
    private static $instance = null;

    /**
     * Module configuration
     *
     * @var array
     */
    private $config = array(
        'enabled' => true,
        'default_currency' => 'USD',
        'supported_currencies' => array(
            'USD' => array(
                'name' => 'US Dollar',
                'symbol' => '$',
                'code' => 'USD',
                'position' => 'left',
                'decimals' => 2,
                'decimal_separator' => '.',
                'thousand_separator' => ','
            ),
            'EUR' => array(
                'name' => 'Euro',
                'symbol' => '€',
                'code' => 'EUR',
                'position' => 'left',
                'decimals' => 2,
                'decimal_separator' => ',',
                'thousand_separator' => '.'
            ),
            'GBP' => array(
                'name' => 'British Pound',
                'symbol' => '£',
                'code' => 'GBP',
                'position' => 'left',
                'decimals' => 2,
                'decimal_separator' => '.',
                'thousand_separator' => ','
            ),
            'JPY' => array(
                'name' => 'Japanese Yen',
                'symbol' => '¥',
                'code' => 'JPY',
                'position' => 'left',
                'decimals' => 0,
                'decimal_separator' => '',
                'thousand_separator' => ','
            ),
            'AUD' => array(
                'name' => 'Australian Dollar',
                'symbol' => 'A$',
                'code' => 'AUD',
                'position' => 'left',
                'decimals' => 2,
                'decimal_separator' => '.',
                'thousand_separator' => ','
            ),
            'CAD' => array(
                'name' => 'Canadian Dollar',
                'symbol' => 'C$',
                'code' => 'CAD',
                'position' => 'left',
                'decimals' => 2,
                'decimal_separator' => '.',
                'thousand_separator' => ','
            ),
            'SGD' => array(
                'name' => 'Singapore Dollar',
                'symbol' => 'S$',
                'code' => 'SGD',
                'position' => 'left',
                'decimals' => 2,
                'decimal_separator' => '.',
                'thousand_separator' => ','
            ),
            'CNY' => array(
                'name' => 'Chinese Yuan',
                'symbol' => '¥',
                'code' => 'CNY',
                'position' => 'left',
                'decimals' => 2,
                'decimal_separator' => '.',
                'thousand_separator' => ','
            )
        ),
        'exchange_rate_provider' => 'fixer', // fixer, openexchangerates, manual
        'cache_duration' => 3600, // 1 hour
        'auto_detect_currency' => true,
        'geolocation_currency_mapping' => array(
            'US' => 'USD',
            'CA' => 'CAD',
            'GB' => 'GBP',
            'AU' => 'AUD',
            'JP' => 'JPY',
            'DE' => 'EUR',
            'FR' => 'EUR',
            'IT' => 'EUR',
            'ES' => 'EUR',
            'NL' => 'EUR',
            'SG' => 'SGD',
            'CN' => 'CNY'
        )
    );

    /**
     * Exchange rates cache
     *
     * @var array
     */
    private $exchange_rates = array();

    /**
     * Current user currency
     *
     * @var string
     */
    private $current_currency = '';

    /**
     * Get instance
     *
     * @return AquaLuxe_Multi_Currency_Module
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->current_currency = $this->get_user_currency();
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('init', array($this, 'init_currency_session'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_aqualuxe_switch_currency', array($this, 'handle_currency_switch'));
        add_action('wp_ajax_nopriv_aqualuxe_switch_currency', array($this, 'handle_currency_switch'));
        add_action('wp_ajax_aqualuxe_get_exchange_rates', array($this, 'get_exchange_rates_ajax'));
        add_action('wp_ajax_aqualuxe_convert_price', array($this, 'convert_price_ajax'));
        
        // WooCommerce integration
        if (class_exists('WooCommerce')) {
            add_filter('woocommerce_currency', array($this, 'get_woocommerce_currency'));
            add_filter('woocommerce_currency_symbol', array($this, 'get_woocommerce_currency_symbol'));
            add_filter('woocommerce_price_format', array($this, 'get_woocommerce_price_format'));
            add_filter('woocommerce_get_price_decimals', array($this, 'get_woocommerce_price_decimals'));
            add_filter('woocommerce_get_price_decimal_separator', array($this, 'get_woocommerce_decimal_separator'));
            add_filter('woocommerce_get_price_thousand_separator', array($this, 'get_woocommerce_thousand_separator'));
            add_filter('raw_woocommerce_price', array($this, 'convert_woocommerce_price'), 10, 2);
        }

        // Shortcodes
        add_shortcode('aqualuxe_currency_switcher', array($this, 'currency_switcher_shortcode'));
        add_shortcode('aqualuxe_price_converter', array($this, 'price_converter_shortcode'));
        add_shortcode('aqualuxe_exchange_rates', array($this, 'exchange_rates_shortcode'));

        // Scheduled events
        add_action('aqualuxe_update_exchange_rates', array($this, 'update_exchange_rates'));
        if (!wp_next_scheduled('aqualuxe_update_exchange_rates')) {
            wp_schedule_event(time(), 'hourly', 'aqualuxe_update_exchange_rates');
        }

        // Admin hooks
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Initialize currency session
     */
    public function init_currency_session() {
        if (!session_id()) {
            session_start();
        }

        // Auto-detect currency if enabled
        if ($this->config['auto_detect_currency'] && empty($_SESSION['aqualuxe_currency'])) {
            $detected_currency = $this->detect_user_currency();
            if ($detected_currency) {
                $_SESSION['aqualuxe_currency'] = $detected_currency;
                $this->current_currency = $detected_currency;
            }
        }
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'aqualuxe-multi-currency',
            AQUALUXE_ASSETS_URI . '/js/modules/multi-currency.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );

        wp_localize_script('aqualuxe-multi-currency', 'aqualuxe_currency', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_currency_nonce'),
            'current_currency' => $this->current_currency,
            'supported_currencies' => $this->config['supported_currencies'],
            'exchange_rates' => $this->get_exchange_rates(),
            'messages' => array(
                'switching' => __('Switching currency...', 'aqualuxe'),
                'success' => __('Currency switched successfully!', 'aqualuxe'),
                'error' => __('Failed to switch currency.', 'aqualuxe'),
                'loading_rates' => __('Loading exchange rates...', 'aqualuxe'),
                'converting' => __('Converting price...', 'aqualuxe')
            )
        ));

        wp_enqueue_style(
            'aqualuxe-multi-currency',
            AQUALUXE_ASSETS_URI . '/css/modules/multi-currency.css',
            array(),
            AQUALUXE_VERSION
        );
    }

    /**
     * Get user currency
     *
     * @return string
     */
    public function get_user_currency() {
        if (!empty($_SESSION['aqualuxe_currency'])) {
            return $_SESSION['aqualuxe_currency'];
        }

        if (is_user_logged_in()) {
            $user_currency = get_user_meta(get_current_user_id(), 'preferred_currency', true);
            if ($user_currency && isset($this->config['supported_currencies'][$user_currency])) {
                return $user_currency;
            }
        }

        return $this->config['default_currency'];
    }

    /**
     * Detect user currency based on geolocation
     *
     * @return string|null
     */
    private function detect_user_currency() {
        $ip = $this->get_client_ip();
        $country = $this->get_country_by_ip($ip);
        
        if ($country && isset($this->config['geolocation_currency_mapping'][$country])) {
            return $this->config['geolocation_currency_mapping'][$country];
        }

        return null;
    }

    /**
     * Get client IP address
     *
     * @return string
     */
    private function get_client_ip() {
        $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Get country by IP (simplified - in production use a geolocation service)
     *
     * @param string $ip
     * @return string|null
     */
    private function get_country_by_ip($ip) {
        // This is a simplified implementation
        // In production, integrate with services like MaxMind, IPinfo, or ip-api
        $response = wp_remote_get("http://ip-api.com/json/{$ip}");
        
        if (is_wp_error($response)) {
            return null;
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);
        
        return isset($data['countryCode']) ? $data['countryCode'] : null;
    }

    /**
     * Handle currency switch AJAX
     */
    public function handle_currency_switch() {
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_currency_nonce')) {
            wp_send_json_error(__('Security check failed.', 'aqualuxe'));
        }

        $currency = sanitize_text_field($_POST['currency'] ?? '');
        
        if (empty($currency) || !isset($this->config['supported_currencies'][$currency])) {
            wp_send_json_error(__('Invalid currency.', 'aqualuxe'));
        }

        // Update session
        $_SESSION['aqualuxe_currency'] = $currency;
        
        // Update user meta if logged in
        if (is_user_logged_in()) {
            update_user_meta(get_current_user_id(), 'preferred_currency', $currency);
        }

        // Set current currency
        $this->current_currency = $currency;

        wp_send_json_success(array(
            'message' => __('Currency switched successfully!', 'aqualuxe'),
            'currency' => $currency,
            'symbol' => $this->config['supported_currencies'][$currency]['symbol']
        ));
    }

    /**
     * Get exchange rates
     *
     * @param bool $force_refresh
     * @return array
     */
    public function get_exchange_rates($force_refresh = false) {
        if (!empty($this->exchange_rates) && !$force_refresh) {
            return $this->exchange_rates;
        }

        $cache_key = 'aqualuxe_exchange_rates';
        $rates = get_transient($cache_key);

        if ($rates === false || $force_refresh) {
            $rates = $this->fetch_exchange_rates();
            set_transient($cache_key, $rates, $this->config['cache_duration']);
        }

        $this->exchange_rates = $rates;
        return $rates;
    }

    /**
     * Fetch exchange rates from provider
     *
     * @return array
     */
    private function fetch_exchange_rates() {
        switch ($this->config['exchange_rate_provider']) {
            case 'fixer':
                return $this->fetch_rates_from_fixer();
            case 'openexchangerates':
                return $this->fetch_rates_from_openexchangerates();
            case 'manual':
                return $this->get_manual_exchange_rates();
            default:
                return $this->get_fallback_exchange_rates();
        }
    }

    /**
     * Fetch rates from Fixer.io
     *
     * @return array
     */
    private function fetch_rates_from_fixer() {
        $api_key = get_option('aqualuxe_fixer_api_key', '');
        
        if (empty($api_key)) {
            return $this->get_fallback_exchange_rates();
        }

        $base_currency = $this->config['default_currency'];
        $currencies = implode(',', array_keys($this->config['supported_currencies']));
        
        $url = "http://data.fixer.io/api/latest?access_key={$api_key}&base={$base_currency}&symbols={$currencies}";
        
        $response = wp_remote_get($url);
        
        if (is_wp_error($response)) {
            return $this->get_fallback_exchange_rates();
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);
        
        if (!isset($data['rates'])) {
            return $this->get_fallback_exchange_rates();
        }

        return $data['rates'];
    }

    /**
     * Get fallback exchange rates
     *
     * @return array
     */
    private function get_fallback_exchange_rates() {
        return array(
            'USD' => 1.00,
            'EUR' => 0.85,
            'GBP' => 0.73,
            'JPY' => 110.00,
            'AUD' => 1.35,
            'CAD' => 1.25,
            'SGD' => 1.35,
            'CNY' => 6.45
        );
    }

    /**
     * Get manual exchange rates from settings
     *
     * @return array
     */
    private function get_manual_exchange_rates() {
        $manual_rates = get_option('aqualuxe_manual_exchange_rates', array());
        
        if (empty($manual_rates)) {
            return $this->get_fallback_exchange_rates();
        }

        return $manual_rates;
    }

    /**
     * Convert price to current currency
     *
     * @param float $price
     * @param string $from_currency
     * @param string $to_currency
     * @return float
     */
    public function convert_price($price, $from_currency = '', $to_currency = '') {
        if (empty($from_currency)) {
            $from_currency = $this->config['default_currency'];
        }
        
        if (empty($to_currency)) {
            $to_currency = $this->current_currency;
        }

        if ($from_currency === $to_currency) {
            return $price;
        }

        $rates = $this->get_exchange_rates();
        
        // Convert to base currency first if needed
        if ($from_currency !== $this->config['default_currency']) {
            if (!isset($rates[$from_currency])) {
                return $price; // No conversion available
            }
            $price = $price / $rates[$from_currency];
        }

        // Convert to target currency
        if ($to_currency !== $this->config['default_currency']) {
            if (!isset($rates[$to_currency])) {
                return $price; // No conversion available
            }
            $price = $price * $rates[$to_currency];
        }

        return round($price, $this->config['supported_currencies'][$to_currency]['decimals']);
    }

    /**
     * Format price with currency
     *
     * @param float $price
     * @param string $currency
     * @return string
     */
    public function format_price($price, $currency = '') {
        if (empty($currency)) {
            $currency = $this->current_currency;
        }

        if (!isset($this->config['supported_currencies'][$currency])) {
            $currency = $this->config['default_currency'];
        }

        $currency_config = $this->config['supported_currencies'][$currency];
        
        $formatted_price = number_format(
            $price,
            $currency_config['decimals'],
            $currency_config['decimal_separator'],
            $currency_config['thousand_separator']
        );

        if ($currency_config['position'] === 'left') {
            return $currency_config['symbol'] . $formatted_price;
        } else {
            return $formatted_price . $currency_config['symbol'];
        }
    }

    /**
     * Currency switcher shortcode
     */
    public function currency_switcher_shortcode($atts = array()) {
        $atts = shortcode_atts(array(
            'style' => 'dropdown', // dropdown, buttons, flags
            'show_names' => false
        ), $atts);

        ob_start();
        ?>
        <div class="aqualuxe-currency-switcher" data-style="<?php echo esc_attr($atts['style']); ?>">
            <?php if ($atts['style'] === 'dropdown'): ?>
                <select id="currency-switcher" class="currency-switcher-dropdown">
                    <?php foreach ($this->config['supported_currencies'] as $code => $currency): ?>
                        <option value="<?php echo esc_attr($code); ?>" <?php selected($this->current_currency, $code); ?>>
                            <?php echo esc_html($currency['symbol'] . ' ' . $code); ?>
                            <?php if ($atts['show_names']): ?>
                                - <?php echo esc_html($currency['name']); ?>
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php elseif ($atts['style'] === 'buttons'): ?>
                <div class="currency-buttons">
                    <?php foreach ($this->config['supported_currencies'] as $code => $currency): ?>
                        <button class="currency-btn <?php echo $this->current_currency === $code ? 'active' : ''; ?>" 
                                data-currency="<?php echo esc_attr($code); ?>">
                            <?php echo esc_html($currency['symbol']); ?>
                            <?php if ($atts['show_names']): ?>
                                <span class="currency-name"><?php echo esc_html($code); ?></span>
                            <?php endif; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Price converter shortcode
     */
    public function price_converter_shortcode($atts = array()) {
        $atts = shortcode_atts(array(
            'amount' => 100,
            'from' => 'USD',
            'show_all' => false
        ), $atts);

        $base_amount = floatval($atts['amount']);
        $from_currency = strtoupper($atts['from']);

        ob_start();
        ?>
        <div class="aqualuxe-price-converter">
            <div class="base-price">
                <strong><?php echo esc_html($this->format_price($base_amount, $from_currency)); ?></strong>
                <span class="base-currency"><?php echo esc_html($from_currency); ?></span>
            </div>
            
            <?php if ($atts['show_all']): ?>
                <div class="converted-prices grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                    <?php foreach ($this->config['supported_currencies'] as $code => $currency): ?>
                        <?php if ($code !== $from_currency): ?>
                            <div class="converted-price">
                                <div class="currency-code text-sm text-gray-600"><?php echo esc_html($code); ?></div>
                                <div class="converted-amount font-semibold">
                                    <?php echo esc_html($this->format_price($this->convert_price($base_amount, $from_currency, $code), $code)); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="current-price mt-2">
                    <span class="label"><?php _e('In your currency:', 'aqualuxe'); ?></span>
                    <strong class="converted-amount">
                        <?php echo esc_html($this->format_price($this->convert_price($base_amount, $from_currency), $this->current_currency)); ?>
                    </strong>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Exchange rates shortcode
     */
    public function exchange_rates_shortcode($atts = array()) {
        $atts = shortcode_atts(array(
            'base' => 'USD',
            'show_last_updated' => true
        ), $atts);

        $rates = $this->get_exchange_rates();
        $base_currency = strtoupper($atts['base']);

        ob_start();
        ?>
        <div class="aqualuxe-exchange-rates">
            <h4><?php printf(__('Exchange Rates (Base: %s)', 'aqualuxe'), $base_currency); ?></h4>
            
            <div class="rates-table">
                <table class="exchange-rates-table">
                    <thead>
                        <tr>
                            <th><?php _e('Currency', 'aqualuxe'); ?></th>
                            <th><?php _e('Rate', 'aqualuxe'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->config['supported_currencies'] as $code => $currency): ?>
                            <?php if ($code !== $base_currency): ?>
                                <tr>
                                    <td>
                                        <span class="currency-symbol"><?php echo esc_html($currency['symbol']); ?></span>
                                        <?php echo esc_html($code); ?>
                                        <small class="currency-name"><?php echo esc_html($currency['name']); ?></small>
                                    </td>
                                    <td class="rate-value">
                                        <?php 
                                        $rate = $this->convert_price(1, $base_currency, $code);
                                        echo number_format($rate, 4);
                                        ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($atts['show_last_updated']): ?>
                <div class="last-updated text-sm text-gray-600 mt-4">
                    <?php 
                    $last_updated = get_option('aqualuxe_exchange_rates_last_updated', time());
                    printf(__('Last updated: %s', 'aqualuxe'), date('Y-m-d H:i:s', $last_updated));
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * WooCommerce integration methods
     */
    public function get_woocommerce_currency() {
        return $this->current_currency;
    }

    public function get_woocommerce_currency_symbol() {
        return $this->config['supported_currencies'][$this->current_currency]['symbol'];
    }

    public function get_woocommerce_price_format() {
        $currency_config = $this->config['supported_currencies'][$this->current_currency];
        
        if ($currency_config['position'] === 'left') {
            return '%1$s%2$s';
        } else {
            return '%2$s%1$s';
        }
    }

    public function get_woocommerce_price_decimals() {
        return $this->config['supported_currencies'][$this->current_currency]['decimals'];
    }

    public function get_woocommerce_decimal_separator() {
        return $this->config['supported_currencies'][$this->current_currency]['decimal_separator'];
    }

    public function get_woocommerce_thousand_separator() {
        return $this->config['supported_currencies'][$this->current_currency]['thousand_separator'];
    }

    public function convert_woocommerce_price($price, $currency = null) {
        if ($currency && $currency !== $this->current_currency) {
            return $this->convert_price($price, $currency, $this->current_currency);
        }
        
        return $this->convert_price($price, $this->config['default_currency'], $this->current_currency);
    }

    /**
     * AJAX handlers
     */
    public function get_exchange_rates_ajax() {
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_currency_nonce')) {
            wp_send_json_error(__('Security check failed.', 'aqualuxe'));
        }

        $force_refresh = isset($_POST['force_refresh']) && $_POST['force_refresh'] === 'true';
        $rates = $this->get_exchange_rates($force_refresh);

        wp_send_json_success(array(
            'rates' => $rates,
            'last_updated' => get_option('aqualuxe_exchange_rates_last_updated', time())
        ));
    }

    public function convert_price_ajax() {
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_currency_nonce')) {
            wp_send_json_error(__('Security check failed.', 'aqualuxe'));
        }

        $amount = floatval($_POST['amount'] ?? 0);
        $from = sanitize_text_field($_POST['from'] ?? $this->config['default_currency']);
        $to = sanitize_text_field($_POST['to'] ?? $this->current_currency);

        $converted_amount = $this->convert_price($amount, $from, $to);
        $formatted_price = $this->format_price($converted_amount, $to);

        wp_send_json_success(array(
            'original_amount' => $amount,
            'converted_amount' => $converted_amount,
            'formatted_price' => $formatted_price,
            'from_currency' => $from,
            'to_currency' => $to
        ));
    }

    /**
     * Update exchange rates scheduled task
     */
    public function update_exchange_rates() {
        $this->get_exchange_rates(true);
        update_option('aqualuxe_exchange_rates_last_updated', time());
    }

    /**
     * Admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            __('Multi-Currency Settings', 'aqualuxe'),
            __('Multi-Currency', 'aqualuxe'),
            'manage_options',
            'aqualuxe-multi-currency',
            array($this, 'admin_page')
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('aqualuxe_currency_settings', 'aqualuxe_fixer_api_key');
        register_setting('aqualuxe_currency_settings', 'aqualuxe_manual_exchange_rates');
        register_setting('aqualuxe_currency_settings', 'aqualuxe_exchange_rate_provider');
    }

    /**
     * Admin page
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Multi-Currency Settings', 'aqualuxe'); ?></h1>
            
            <form method="post" action="options.php">
                <?php settings_fields('aqualuxe_currency_settings'); ?>
                <?php do_settings_sections('aqualuxe_currency_settings'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Exchange Rate Provider', 'aqualuxe'); ?></th>
                        <td>
                            <select name="aqualuxe_exchange_rate_provider">
                                <option value="fixer" <?php selected(get_option('aqualuxe_exchange_rate_provider', 'fixer'), 'fixer'); ?>>Fixer.io</option>
                                <option value="manual" <?php selected(get_option('aqualuxe_exchange_rate_provider', 'fixer'), 'manual'); ?>>Manual</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Fixer.io API Key', 'aqualuxe'); ?></th>
                        <td>
                            <input type="text" name="aqualuxe_fixer_api_key" value="<?php echo esc_attr(get_option('aqualuxe_fixer_api_key', '')); ?>" class="regular-text" />
                            <p class="description"><?php _e('Get your API key from fixer.io', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
            
            <h2><?php _e('Current Exchange Rates', 'aqualuxe'); ?></h2>
            <div class="exchange-rates-preview">
                <?php echo $this->exchange_rates_shortcode(); ?>
            </div>
        </div>
        <?php
    }

    /**
     * Fetch rates from Open Exchange Rates
     */
    private function fetch_rates_from_openexchangerates() {
        // Implementation for Open Exchange Rates API
        return $this->get_fallback_exchange_rates();
    }
}

// Initialize module
AquaLuxe_Multi_Currency_Module::get_instance();