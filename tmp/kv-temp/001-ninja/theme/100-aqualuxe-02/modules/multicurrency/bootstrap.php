<?php
/**
 * Multicurrency Module Bootstrap
 *
 * @package AquaLuxe\Modules\Multicurrency
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Multicurrency Module Class
 */
class AquaLuxe_Multicurrency {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Only initialize if WooCommerce is active
        if (!aqualuxe_is_woocommerce_active()) {
            return;
        }
        
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'render_currency_switcher'));
        add_filter('woocommerce_currency', array($this, 'get_current_currency'));
        add_filter('woocommerce_currency_symbol', array($this, 'get_currency_symbol'), 10, 2);
        add_filter('raw_woocommerce_price', array($this, 'convert_price'), 10, 2);
    }
    
    /**
     * Initialize
     */
    public function init() {
        // Check if existing multicurrency plugin is active
        if ($this->is_multicurrency_plugin_active()) {
            return;
        }
        
        // Set up currency detection
        add_action('wp', array($this, 'detect_currency'));
        add_action('wp_ajax_switch_currency', array($this, 'ajax_switch_currency'));
        add_action('wp_ajax_nopriv_switch_currency', array($this, 'ajax_switch_currency'));
    }
    
    /**
     * Check if multicurrency plugin is active
     */
    private function is_multicurrency_plugin_active() {
        return (
            class_exists('WOOMULTI_CURRENCY_F') || // WooCommerce Multi Currency
            class_exists('WOOCS') || // WooCommerce Currency Switcher
            defined('WCML_VERSION') // WooCommerce Multilingual
        );
    }
    
    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        wp_localize_script('aqualuxe-script', 'aqualuxe_multicurrency', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('currency_switch_nonce'),
            'current_currency' => $this->get_current_currency(),
            'available_currencies' => $this->get_available_currencies(),
        ));
    }
    
    /**
     * Get current currency
     */
    public function get_current_currency($currency = '') {
        // Check for existing plugin
        if ($this->is_multicurrency_plugin_active()) {
            return $currency;
        }
        
        // Check session/cookie
        if (isset($_COOKIE['aqualuxe_currency'])) {
            $stored_currency = sanitize_text_field($_COOKIE['aqualuxe_currency']);
            $available = array_keys($this->get_available_currencies());
            if (in_array($stored_currency, $available)) {
                return $stored_currency;
            }
        }
        
        // Geolocation-based currency detection
        $detected_currency = $this->detect_currency_by_location();
        if ($detected_currency) {
            return $detected_currency;
        }
        
        return get_woocommerce_currency();
    }
    
    /**
     * Get currency symbol
     */
    public function get_currency_symbol($symbol, $currency) {
        $currencies = $this->get_available_currencies();
        return isset($currencies[$currency]['symbol']) ? $currencies[$currency]['symbol'] : $symbol;
    }
    
    /**
     * Get available currencies
     */
    public function get_available_currencies() {
        return array(
            'USD' => array(
                'name' => 'US Dollar',
                'symbol' => '$',
                'rate' => 1.0,
                'position' => 'left',
                'decimal_sep' => '.',
                'thousand_sep' => ',',
                'decimals' => 2,
            ),
            'EUR' => array(
                'name' => 'Euro',
                'symbol' => '€',
                'rate' => 0.85,
                'position' => 'left',
                'decimal_sep' => ',',
                'thousand_sep' => '.',
                'decimals' => 2,
            ),
            'GBP' => array(
                'name' => 'British Pound',
                'symbol' => '£',
                'rate' => 0.73,
                'position' => 'left',
                'decimal_sep' => '.',
                'thousand_sep' => ',',
                'decimals' => 2,
            ),
            'CAD' => array(
                'name' => 'Canadian Dollar',
                'symbol' => 'C$',
                'rate' => 1.25,
                'position' => 'left',
                'decimal_sep' => '.',
                'thousand_sep' => ',',
                'decimals' => 2,
            ),
            'AUD' => array(
                'name' => 'Australian Dollar',
                'symbol' => 'A$',
                'rate' => 1.35,
                'position' => 'left',
                'decimal_sep' => '.',
                'thousand_sep' => ',',
                'decimals' => 2,
            ),
            'JPY' => array(
                'name' => 'Japanese Yen',
                'symbol' => '¥',
                'rate' => 110.0,
                'position' => 'left',
                'decimal_sep' => '',
                'thousand_sep' => ',',
                'decimals' => 0,
            ),
            'CNY' => array(
                'name' => 'Chinese Yuan',
                'symbol' => '¥',
                'rate' => 6.45,
                'position' => 'left',
                'decimal_sep' => '.',
                'thousand_sep' => ',',
                'decimals' => 2,
            ),
            'INR' => array(
                'name' => 'Indian Rupee',
                'symbol' => '₹',
                'rate' => 75.0,
                'position' => 'left',
                'decimal_sep' => '.',
                'thousand_sep' => ',',
                'decimals' => 2,
            ),
            'BRL' => array(
                'name' => 'Brazilian Real',
                'symbol' => 'R$',
                'rate' => 5.2,
                'position' => 'left',
                'decimal_sep' => ',',
                'thousand_sep' => '.',
                'decimals' => 2,
            ),
            'RUB' => array(
                'name' => 'Russian Ruble',
                'symbol' => '₽',
                'rate' => 75.0,
                'position' => 'right',
                'decimal_sep' => ',',
                'thousand_sep' => ' ',
                'decimals' => 2,
            ),
        );
    }
    
    /**
     * Convert price to current currency
     */
    public function convert_price($price, $args = array()) {
        if ($this->is_multicurrency_plugin_active()) {
            return $price;
        }
        
        $current_currency = $this->get_current_currency();
        $base_currency = get_woocommerce_currency();
        
        if ($current_currency === $base_currency) {
            return $price;
        }
        
        $currencies = $this->get_available_currencies();
        $rate = isset($currencies[$current_currency]['rate']) ? $currencies[$current_currency]['rate'] : 1.0;
        
        return $price * $rate;
    }
    
    /**
     * Detect currency by location
     */
    private function detect_currency_by_location() {
        // Simple geolocation-based currency detection
        // In a real implementation, you would use a geolocation service
        
        $country_currency_map = array(
            'US' => 'USD',
            'CA' => 'CAD',
            'GB' => 'GBP',
            'AU' => 'AUD',
            'DE' => 'EUR',
            'FR' => 'EUR',
            'IT' => 'EUR',
            'ES' => 'EUR',
            'JP' => 'JPY',
            'CN' => 'CNY',
            'IN' => 'INR',
            'BR' => 'BRL',
            'RU' => 'RUB',
        );
        
        // Try to get country from HTTP headers or IP
        $country = $this->get_visitor_country();
        
        return isset($country_currency_map[$country]) ? $country_currency_map[$country] : null;
    }
    
    /**
     * Get visitor country (simplified)
     */
    private function get_visitor_country() {
        // This is a simplified implementation
        // In production, you would use a proper geolocation service
        
        if (isset($_SERVER['HTTP_CF_IPCOUNTRY'])) {
            return $_SERVER['HTTP_CF_IPCOUNTRY']; // Cloudflare
        }
        
        if (isset($_SERVER['GEOIP_COUNTRY_CODE'])) {
            return $_SERVER['GEOIP_COUNTRY_CODE']; // GeoIP
        }
        
        return null;
    }
    
    /**
     * Detect currency
     */
    public function detect_currency() {
        if (isset($_GET['currency'])) {
            $currency = sanitize_text_field($_GET['currency']);
            $available = array_keys($this->get_available_currencies());
            
            if (in_array($currency, $available)) {
                setcookie('aqualuxe_currency', $currency, time() + (30 * DAY_IN_SECONDS), '/');
                wp_redirect(remove_query_arg('currency'));
                exit;
            }
        }
    }
    
    /**
     * AJAX currency switch
     */
    public function ajax_switch_currency() {
        check_ajax_referer('currency_switch_nonce', 'nonce');
        
        $currency = sanitize_text_field($_POST['currency']);
        $available = array_keys($this->get_available_currencies());
        
        if (in_array($currency, $available)) {
            setcookie('aqualuxe_currency', $currency, time() + (30 * DAY_IN_SECONDS), '/');
            
            wp_send_json_success(array(
                'currency' => $currency,
                'message' => sprintf(__('Currency switched to %s', 'aqualuxe'), $currency),
            ));
        } else {
            wp_send_json_error(__('Invalid currency', 'aqualuxe'));
        }
    }
    
    /**
     * Render currency switcher
     */
    public function render_currency_switcher() {
        if ($this->is_multicurrency_plugin_active()) {
            return; // Let the plugin handle it
        }
        
        $currencies = $this->get_available_currencies();
        $current_currency = $this->get_current_currency();
        
        if (count($currencies) <= 1) {
            return;
        }
        
        ?>
        <div id="currency-switcher" class="fixed top-20 right-6 z-40">
            <div class="currency-switcher-dropdown">
                <button class="currency-toggle" aria-expanded="false">
                    <span class="current-currency">
                        <?php 
                        echo isset($currencies[$current_currency]['symbol']) ? $currencies[$current_currency]['symbol'] : '$';
                        echo ' ' . $current_currency;
                        ?>
                    </span>
                    <span class="arrow">▼</span>
                </button>
                <ul class="currency-list" aria-hidden="true">
                    <?php foreach ($currencies as $currency_code => $currency_data) : ?>
                        <?php if ($currency_code !== $current_currency) : ?>
                            <li>
                                <a href="#" 
                                   data-currency="<?php echo esc_attr($currency_code); ?>"
                                   class="currency-option">
                                    <span class="symbol"><?php echo esc_html($currency_data['symbol']); ?></span>
                                    <span class="code"><?php echo esc_html($currency_code); ?></span>
                                    <span class="name"><?php echo esc_html($currency_data['name']); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        
        <style>
        #currency-switcher {
            font-size: 14px;
        }
        
        .currency-switcher-dropdown {
            position: relative;
        }
        
        .currency-toggle {
            background: rgba(255, 255, 255, 0.95);
            color: #333;
            border: 1px solid #ddd;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .currency-list {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            list-style: none;
            margin: 0;
            padding: 0;
            min-width: 200px;
            display: none;
            z-index: 1000;
        }
        
        .currency-list[aria-hidden="false"] {
            display: block;
        }
        
        .currency-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            text-decoration: none;
            color: #333;
            border-bottom: 1px solid #eee;
        }
        
        .currency-option:hover {
            background: #f5f5f5;
        }
        
        .currency-option:last-child {
            border-bottom: none;
        }
        
        .currency-option .symbol {
            font-weight: bold;
            width: 20px;
        }
        
        .currency-option .code {
            font-weight: 600;
            width: 40px;
        }
        
        .currency-option .name {
            flex: 1;
            font-size: 12px;
            color: #666;
        }
        </style>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.querySelector('#currency-switcher .currency-toggle');
            const list = document.querySelector('#currency-switcher .currency-list');
            
            if (toggle && list) {
                toggle.addEventListener('click', function() {
                    const isOpen = toggle.getAttribute('aria-expanded') === 'true';
                    toggle.setAttribute('aria-expanded', !isOpen);
                    list.setAttribute('aria-hidden', isOpen);
                });
                
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('#currency-switcher')) {
                        toggle.setAttribute('aria-expanded', 'false');
                        list.setAttribute('aria-hidden', 'true');
                    }
                });
                
                // Handle currency switch
                list.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const option = e.target.closest('.currency-option');
                    if (!option) return;
                    
                    const currency = option.dataset.currency;
                    if (!currency) return;
                    
                    // Switch currency via AJAX
                    if (typeof aqualuxe_multicurrency !== 'undefined') {
                        fetch(aqualuxe_multicurrency.ajax_url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: new URLSearchParams({
                                action: 'switch_currency',
                                currency: currency,
                                nonce: aqualuxe_multicurrency.nonce
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            }
                        });
                    } else {
                        // Fallback to URL parameter
                        window.location.href = window.location.href + (window.location.href.includes('?') ? '&' : '?') + 'currency=' + currency;
                    }
                });
            }
        });
        </script>
        <?php
    }
}

// Initialize the module
new AquaLuxe_Multicurrency();