<?php
/**
 * Multi-Currency Framework for WooCommerce
 *
 * Provides foundation for multi-currency support with international commerce features
 *
 * @package AquaLuxe\Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class MultiCurrency
 */
class MultiCurrency {
    
    /**
     * Single instance of the class
     *
     * @var MultiCurrency
     */
    private static $instance = null;

    /**
     * Supported currencies
     *
     * @var array
     */
    private $supported_currencies = array();

    /**
     * Current currency
     *
     * @var string
     */
    private $current_currency = 'USD';

    /**
     * Exchange rates
     *
     * @var array
     */
    private $exchange_rates = array();

    /**
     * Get instance
     *
     * @return MultiCurrency
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
        $this->init_currencies();
        $this->init_hooks();
    }

    /**
     * Initialize supported currencies
     */
    private function init_currencies() {
        $this->supported_currencies = array(
            'USD' => array(
                'name' => 'US Dollar',
                'symbol' => '$',
                'symbol_position' => 'before',
                'decimal_places' => 2,
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'countries' => array('US'),
                'flag' => '🇺🇸'
            ),
            'EUR' => array(
                'name' => 'Euro',
                'symbol' => '€',
                'symbol_position' => 'after',
                'decimal_places' => 2,
                'thousand_separator' => '.',
                'decimal_separator' => ',',
                'countries' => array('DE', 'FR', 'ES', 'IT', 'NL', 'BE'),
                'flag' => '🇪🇺'
            ),
            'GBP' => array(
                'name' => 'British Pound',
                'symbol' => '£',
                'symbol_position' => 'before',
                'decimal_places' => 2,
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'countries' => array('GB'),
                'flag' => '🇬🇧'
            ),
            'JPY' => array(
                'name' => 'Japanese Yen',
                'symbol' => '¥',
                'symbol_position' => 'before',
                'decimal_places' => 0,
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'countries' => array('JP'),
                'flag' => '🇯🇵'
            ),
            'CAD' => array(
                'name' => 'Canadian Dollar',
                'symbol' => 'C$',
                'symbol_position' => 'before',
                'decimal_places' => 2,
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'countries' => array('CA'),
                'flag' => '🇨🇦'
            ),
            'AUD' => array(
                'name' => 'Australian Dollar',
                'symbol' => 'A$',
                'symbol_position' => 'before',
                'decimal_places' => 2,
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'countries' => array('AU'),
                'flag' => '🇦🇺'
            ),
            'SGD' => array(
                'name' => 'Singapore Dollar',
                'symbol' => 'S$',
                'symbol_position' => 'before',
                'decimal_places' => 2,
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'countries' => array('SG'),
                'flag' => '🇸🇬'
            ),
            'CHF' => array(
                'name' => 'Swiss Franc',
                'symbol' => 'CHF',
                'symbol_position' => 'after',
                'decimal_places' => 2,
                'thousand_separator' => "'",
                'decimal_separator' => '.',
                'countries' => array('CH'),
                'flag' => '🇨🇭'
            )
        );

        // Get user's preferred currency
        $this->current_currency = $this->get_user_currency();
        
        // Load exchange rates
        $this->load_exchange_rates();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Currency switching
        add_action('wp_ajax_aqualuxe_switch_currency', array($this, 'switch_currency'));
        add_action('wp_ajax_nopriv_aqualuxe_switch_currency', array($this, 'switch_currency'));
        
        // Price conversion
        add_filter('woocommerce_price_format', array($this, 'get_price_format'));
        add_filter('woocommerce_currency_symbol', array($this, 'get_currency_symbol'));
        add_filter('woocommerce_price_trim_zeros', array($this, 'should_trim_zeros'));
        
        // Display currency selector
        add_action('woocommerce_before_shop_loop', array($this, 'display_currency_selector'));
        add_action('woocommerce_before_single_product_summary', array($this, 'display_currency_selector'), 5);
        
        // Admin settings
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        
        // Geolocation
        add_action('wp_enqueue_scripts', array($this, 'enqueue_geolocation_script'));
        
        // Price caching
        add_action('save_post_product', array($this, 'clear_price_cache'));
        
        // International shipping
        add_filter('woocommerce_package_rates', array($this, 'adjust_shipping_rates_for_currency'), 10, 2);
        
        // Tax adjustments
        add_filter('woocommerce_find_rates', array($this, 'adjust_tax_rates_for_currency'));
    }

    /**
     * Get user's preferred currency
     */
    private function get_user_currency() {
        // Check user session
        if (isset($_SESSION['aqualuxe_currency'])) {
            return $_SESSION['aqualuxe_currency'];
        }

        // Check cookie
        if (isset($_COOKIE['aqualuxe_currency'])) {
            return sanitize_text_field($_COOKIE['aqualuxe_currency']);
        }

        // Detect from user's country
        $country = $this->get_user_country();
        if ($country) {
            foreach ($this->supported_currencies as $code => $currency) {
                if (in_array($country, $currency['countries'])) {
                    return $code;
                }
            }
        }

        // Default to USD
        return 'USD';
    }

    /**
     * Get user's country
     */
    private function get_user_country() {
        // Try WooCommerce geolocation first
        if (class_exists('WC_Geolocation')) {
            $geolocation = WC_Geolocation::geolocate_ip();
            if (!empty($geolocation['country'])) {
                return $geolocation['country'];
            }
        }

        // Fallback to HTTP headers
        $country_headers = array(
            'HTTP_CF_IPCOUNTRY',
            'HTTP_X_COUNTRY_CODE',
            'HTTP_X_FORWARDED_COUNTRY'
        );

        foreach ($country_headers as $header) {
            if (!empty($_SERVER[$header])) {
                return strtoupper(sanitize_text_field($_SERVER[$header]));
            }
        }

        return false;
    }

    /**
     * Load exchange rates
     */
    private function load_exchange_rates() {
        $cache_key = 'aqualuxe_exchange_rates';
        $cached_rates = wp_cache_get($cache_key, 'aqualuxe_multicurrency');

        if (false === $cached_rates) {
            // Load from database
            $rates = get_option('aqualuxe_exchange_rates', array());
            
            // Check if rates are stale (older than 1 hour)
            $last_update = get_option('aqualuxe_exchange_rates_updated', 0);
            if (time() - $last_update > HOUR_IN_SECONDS) {
                $this->update_exchange_rates();
                $rates = get_option('aqualuxe_exchange_rates', array());
            }

            wp_cache_set($cache_key, $rates, 'aqualuxe_multicurrency', HOUR_IN_SECONDS);
            $this->exchange_rates = $rates;
        } else {
            $this->exchange_rates = $cached_rates;
        }
    }

    /**
     * Update exchange rates from external API
     */
    private function update_exchange_rates() {
        // Use a free exchange rate API
        $api_url = 'https://api.exchangerate-api.com/v4/latest/USD';
        
        $response = wp_remote_get($api_url, array(
            'timeout' => 10,
            'user-agent' => 'AquaLuxe WordPress Theme'
        ));

        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);

            if ($data && isset($data['rates'])) {
                // Add USD as base currency
                $rates = array('USD' => 1.0);
                
                // Add supported currencies
                foreach ($this->supported_currencies as $code => $currency) {
                    if (isset($data['rates'][$code])) {
                        $rates[$code] = floatval($data['rates'][$code]);
                    }
                }

                update_option('aqualuxe_exchange_rates', $rates);
                update_option('aqualuxe_exchange_rates_updated', time());
                
                $this->exchange_rates = $rates;
            }
        }
    }

    /**
     * Convert price to current currency
     */
    public function convert_price($price, $from_currency = 'USD', $to_currency = null) {
        if (!$to_currency) {
            $to_currency = $this->current_currency;
        }

        if ($from_currency === $to_currency) {
            return $price;
        }

        $cache_key = "price_conversion_{$price}_{$from_currency}_{$to_currency}";
        $converted = wp_cache_get($cache_key, 'aqualuxe_multicurrency');

        if (false === $converted) {
            $from_rate = isset($this->exchange_rates[$from_currency]) ? $this->exchange_rates[$from_currency] : 1;
            $to_rate = isset($this->exchange_rates[$to_currency]) ? $this->exchange_rates[$to_currency] : 1;

            // Convert to USD first, then to target currency
            $usd_price = $price / $from_rate;
            $converted = $usd_price * $to_rate;

            // Apply rounding rules
            $converted = $this->round_price($converted, $to_currency);

            wp_cache_set($cache_key, $converted, 'aqualuxe_multicurrency', DAY_IN_SECONDS);
        }

        return $converted;
    }

    /**
     * Round price according to currency rules
     */
    private function round_price($price, $currency) {
        $currency_info = $this->supported_currencies[$currency] ?? $this->supported_currencies['USD'];
        $decimal_places = $currency_info['decimal_places'];

        // Special rounding for JPY (no decimals)
        if ($currency === 'JPY') {
            return round($price);
        }

        // Round to nearest 0.05 for some currencies
        if (in_array($currency, array('CAD', 'AUD'))) {
            return round($price * 20) / 20;
        }

        return round($price, $decimal_places);
    }

    /**
     * Get price format for current currency
     */
    public function get_price_format($format) {
        $currency_info = $this->supported_currencies[$this->current_currency] ?? $this->supported_currencies['USD'];
        
        $symbol_position = $currency_info['symbol_position'];
        
        if ($symbol_position === 'before') {
            return '%1$s%2$s';
        } else {
            return '%2$s%1$s';
        }
    }

    /**
     * Get currency symbol
     */
    public function get_currency_symbol($symbol) {
        $currency_info = $this->supported_currencies[$this->current_currency] ?? $this->supported_currencies['USD'];
        return $currency_info['symbol'];
    }

    /**
     * Should trim zeros
     */
    public function should_trim_zeros($trim) {
        // Don't trim zeros for JPY
        if ($this->current_currency === 'JPY') {
            return false;
        }
        return $trim;
    }

    /**
     * Display currency selector
     */
    public function display_currency_selector() {
        if (!$this->is_multicurrency_enabled()) {
            return;
        }

        $current_info = $this->supported_currencies[$this->current_currency];
        ?>
        <div class="aqualuxe-currency-selector mb-4">
            <div class="currency-dropdown relative inline-block">
                <button type="button" class="currency-toggle flex items-center px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                    <span class="currency-flag mr-2"><?php echo esc_html($current_info['flag']); ?></span>
                    <span class="currency-code font-medium"><?php echo esc_html($this->current_currency); ?></span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div class="currency-dropdown-menu absolute top-full left-0 mt-1 w-48 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg z-10 hidden">
                    <?php foreach ($this->supported_currencies as $code => $currency) : ?>
                        <button type="button" 
                                class="currency-option w-full flex items-center px-4 py-2 text-left hover:bg-gray-50 dark:hover:bg-gray-700 <?php echo $code === $this->current_currency ? 'bg-blue-50 dark:bg-blue-900' : ''; ?>"
                                data-currency="<?php echo esc_attr($code); ?>">
                            <span class="currency-flag mr-3"><?php echo esc_html($currency['flag']); ?></span>
                            <div>
                                <div class="font-medium"><?php echo esc_html($code); ?></div>
                                <div class="text-sm text-gray-500 dark:text-gray-400"><?php echo esc_html($currency['name']); ?></div>
                            </div>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.querySelector('.currency-toggle');
            const dropdown = document.querySelector('.currency-dropdown-menu');
            const options = document.querySelectorAll('.currency-option');

            if (toggle && dropdown) {
                toggle.addEventListener('click', function() {
                    dropdown.classList.toggle('hidden');
                });

                document.addEventListener('click', function(e) {
                    if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                });

                options.forEach(option => {
                    option.addEventListener('click', function() {
                        const currency = this.dataset.currency;
                        switchCurrency(currency);
                        dropdown.classList.add('hidden');
                    });
                });
            }

            function switchCurrency(currency) {
                const formData = new FormData();
                formData.append('action', 'aqualuxe_switch_currency');
                formData.append('currency', currency);
                formData.append('nonce', '<?php echo wp_create_nonce('aqualuxe_currency_nonce'); ?>');

                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Set cookie and reload page
                        document.cookie = `aqualuxe_currency=${currency}; path=/; max-age=${30 * 24 * 60 * 60}`;
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Currency switch error:', error);
                });
            }
        });
        </script>
        <?php
    }

    /**
     * Switch currency AJAX handler
     */
    public function switch_currency() {
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_currency_nonce')) {
            wp_send_json_error(__('Security check failed.', 'aqualuxe'));
        }

        $currency = sanitize_text_field($_POST['currency'] ?? '');
        
        if (!isset($this->supported_currencies[$currency])) {
            wp_send_json_error(__('Invalid currency.', 'aqualuxe'));
        }

        // Save to session
        if (!session_id()) {
            session_start();
        }
        $_SESSION['aqualuxe_currency'] = $currency;

        // Update current currency
        $this->current_currency = $currency;

        wp_send_json_success(array(
            'currency' => $currency,
            'message' => sprintf(__('Currency switched to %s', 'aqualuxe'), $currency)
        ));
    }

    /**
     * Adjust shipping rates for currency
     */
    public function adjust_shipping_rates_for_currency($rates, $package) {
        if ($this->current_currency === 'USD') {
            return $rates;
        }

        foreach ($rates as $rate) {
            $rate->cost = $this->convert_price($rate->cost);
            
            if (!empty($rate->taxes)) {
                foreach ($rate->taxes as $key => $tax) {
                    $rate->taxes[$key] = $this->convert_price($tax);
                }
            }
        }

        return $rates;
    }

    /**
     * Adjust tax rates for currency
     */
    public function adjust_tax_rates_for_currency($rates) {
        // Tax rates are percentages, so no conversion needed
        // But we might need to adjust for different tax systems
        return $rates;
    }

    /**
     * Check if multicurrency is enabled
     */
    private function is_multicurrency_enabled() {
        return get_option('aqualuxe_enable_multicurrency', true);
    }

    /**
     * Clear price cache
     */
    public function clear_price_cache($product_id) {
        wp_cache_flush_group('aqualuxe_multicurrency');
    }

    /**
     * Enqueue geolocation script
     */
    public function enqueue_geolocation_script() {
        if (!$this->is_multicurrency_enabled() || is_admin()) {
            return;
        }

        wp_enqueue_script(
            'aqualuxe-geolocation',
            get_template_directory_uri() . '/assets/dist/js/geolocation.js',
            array(),
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'woocommerce',
            __('Multi-Currency Settings', 'aqualuxe'),
            __('Multi-Currency', 'aqualuxe'),
            'manage_woocommerce',
            'aqualuxe-multicurrency',
            array($this, 'admin_page')
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('aqualuxe_multicurrency', 'aqualuxe_enable_multicurrency');
        register_setting('aqualuxe_multicurrency', 'aqualuxe_auto_detect_currency');
        register_setting('aqualuxe_multicurrency', 'aqualuxe_exchange_rate_provider');
    }

    /**
     * Admin page
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Multi-Currency Settings', 'aqualuxe'); ?></h1>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('aqualuxe_multicurrency');
                do_settings_sections('aqualuxe_multicurrency');
                ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Multi-Currency', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_enable_multicurrency" value="1" <?php checked(get_option('aqualuxe_enable_multicurrency', true)); ?>>
                            <p class="description"><?php esc_html_e('Enable multi-currency support for international customers.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Auto-Detect Currency', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_auto_detect_currency" value="1" <?php checked(get_option('aqualuxe_auto_detect_currency', true)); ?>>
                            <p class="description"><?php esc_html_e('Automatically detect customer currency based on their location.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <h2><?php esc_html_e('Supported Currencies', 'aqualuxe'); ?></h2>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Currency', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Symbol', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Rate (USD)', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Last Updated', 'aqualuxe'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->supported_currencies as $code => $currency) : ?>
                            <tr>
                                <td>
                                    <?php echo esc_html($currency['flag']); ?> 
                                    <strong><?php echo esc_html($code); ?></strong> - 
                                    <?php echo esc_html($currency['name']); ?>
                                </td>
                                <td><?php echo esc_html($currency['symbol']); ?></td>
                                <td><?php echo esc_html($this->exchange_rates[$code] ?? 'N/A'); ?></td>
                                <td><?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), get_option('aqualuxe_exchange_rates_updated', 0))); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'aqualuxe'); ?>">
                    <button type="button" class="button" onclick="updateExchangeRates()"><?php esc_html_e('Update Exchange Rates', 'aqualuxe'); ?></button>
                </p>
            </form>
        </div>

        <script>
        function updateExchangeRates() {
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = '<?php esc_html_e('Updating...', 'aqualuxe'); ?>';
            button.disabled = true;

            fetch(ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=aqualuxe_update_exchange_rates&nonce=<?php echo wp_create_nonce('aqualuxe_update_rates'); ?>'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.data || '<?php esc_html_e('Failed to update exchange rates.', 'aqualuxe'); ?>');
                }
            })
            .finally(() => {
                button.textContent = originalText;
                button.disabled = false;
            });
        }
        </script>
        <?php
    }

    /**
     * Get current currency
     */
    public function get_current_currency() {
        return $this->current_currency;
    }

    /**
     * Get supported currencies
     */
    public function get_supported_currencies() {
        return $this->supported_currencies;
    }

    /**
     * Get exchange rate
     */
    public function get_exchange_rate($currency) {
        return $this->exchange_rates[$currency] ?? 1;
    }
}