<?php
/**
 * Enterprise Theme Currency Management Service
 * 
 * Comprehensive multicurrency management system providing
 * currency conversion, exchange rate management, price formatting,
 * and payment gateway integration
 * 
 * @package Enterprise_Theme
 * @version 2.0.0
 * @author Enterprise Development Team
 * @since 2.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

/**
 * Currency Management Service Class
 * 
 * Implements:
 * - Multi-currency support with 10+ currencies
 * - Real-time exchange rate updates
 * - Currency conversion and formatting
 * - Payment gateway integration
 * - Currency-specific pricing
 * - Geolocation-based currency detection
 * - Historical exchange rate tracking
 * - Currency fallback system
 */
class Enterprise_Theme_Currency_Service {
    
    /**
     * Service configuration
     * 
     * @var array
     */
    private array $config;
    
    /**
     * Database service instance
     * 
     * @var Enterprise_Theme_Database_Service
     */
    private Enterprise_Theme_Database_Service $database;
    
    /**
     * Cache service instance
     * 
     * @var Enterprise_Theme_Cache_Service
     */
    private Enterprise_Theme_Cache_Service $cache;
    
    /**
     * Tenant service instance
     * 
     * @var Enterprise_Theme_Tenant_Service
     */
    private Enterprise_Theme_Tenant_Service $tenant_service;
    
    /**
     * Current currency
     * 
     * @var array|null
     */
    private ?array $current_currency = null;
    
    /**
     * Supported currencies
     * 
     * @var array
     */
    private array $supported_currencies = [
        'USD' => [
            'code' => 'USD',
            'name' => 'US Dollar',
            'symbol' => '$',
            'symbol_position' => 'before',
            'decimal_places' => 2,
            'decimal_separator' => '.',
            'thousand_separator' => ',',
            'country_code' => 'US',
            'is_default' => true,
        ],
        'EUR' => [
            'code' => 'EUR',
            'name' => 'Euro',
            'symbol' => '€',
            'symbol_position' => 'after',
            'decimal_places' => 2,
            'decimal_separator' => ',',
            'thousand_separator' => '.',
            'country_code' => 'EU',
            'is_default' => false,
        ],
        'GBP' => [
            'code' => 'GBP',
            'name' => 'British Pound',
            'symbol' => '£',
            'symbol_position' => 'before',
            'decimal_places' => 2,
            'decimal_separator' => '.',
            'thousand_separator' => ',',
            'country_code' => 'GB',
            'is_default' => false,
        ],
        'JPY' => [
            'code' => 'JPY',
            'name' => 'Japanese Yen',
            'symbol' => '¥',
            'symbol_position' => 'before',
            'decimal_places' => 0,
            'decimal_separator' => '',
            'thousand_separator' => ',',
            'country_code' => 'JP',
            'is_default' => false,
        ],
        'CAD' => [
            'code' => 'CAD',
            'name' => 'Canadian Dollar',
            'symbol' => 'C$',
            'symbol_position' => 'before',
            'decimal_places' => 2,
            'decimal_separator' => '.',
            'thousand_separator' => ',',
            'country_code' => 'CA',
            'is_default' => false,
        ],
        'AUD' => [
            'code' => 'AUD',
            'name' => 'Australian Dollar',
            'symbol' => 'A$',
            'symbol_position' => 'before',
            'decimal_places' => 2,
            'decimal_separator' => '.',
            'thousand_separator' => ',',
            'country_code' => 'AU',
            'is_default' => false,
        ],
        'CHF' => [
            'code' => 'CHF',
            'name' => 'Swiss Franc',
            'symbol' => 'CHF',
            'symbol_position' => 'after',
            'decimal_places' => 2,
            'decimal_separator' => '.',
            'thousand_separator' => "'",
            'country_code' => 'CH',
            'is_default' => false,
        ],
        'CNY' => [
            'code' => 'CNY',
            'name' => 'Chinese Yuan',
            'symbol' => '¥',
            'symbol_position' => 'before',
            'decimal_places' => 2,
            'decimal_separator' => '.',
            'thousand_separator' => ',',
            'country_code' => 'CN',
            'is_default' => false,
        ],
        'SEK' => [
            'code' => 'SEK',
            'name' => 'Swedish Krona',
            'symbol' => 'kr',
            'symbol_position' => 'after',
            'decimal_places' => 2,
            'decimal_separator' => ',',
            'thousand_separator' => ' ',
            'country_code' => 'SE',
            'is_default' => false,
        ],
        'NOK' => [
            'code' => 'NOK',
            'name' => 'Norwegian Krone',
            'symbol' => 'kr',
            'symbol_position' => 'after',
            'decimal_places' => 2,
            'decimal_separator' => ',',
            'thousand_separator' => ' ',
            'country_code' => 'NO',
            'is_default' => false,
        ],
        'DKK' => [
            'code' => 'DKK',
            'name' => 'Danish Krone',
            'symbol' => 'kr',
            'symbol_position' => 'after',
            'decimal_places' => 2,
            'decimal_separator' => ',',
            'thousand_separator' => '.',
            'country_code' => 'DK',
            'is_default' => false,
        ],
        'INR' => [
            'code' => 'INR',
            'name' => 'Indian Rupee',
            'symbol' => '₹',
            'symbol_position' => 'before',
            'decimal_places' => 2,
            'decimal_separator' => '.',
            'thousand_separator' => ',',
            'country_code' => 'IN',
            'is_default' => false,
        ],
    ];
    
    /**
     * Exchange rate cache
     * 
     * @var array
     */
    private array $exchange_rate_cache = [];
    
    /**
     * Constructor
     * 
     * @param Enterprise_Theme_Config $config Configuration instance
     * @param Enterprise_Theme_Database_Service $database Database service
     * @param Enterprise_Theme_Cache_Service $cache Cache service
     * @param Enterprise_Theme_Tenant_Service $tenant_service Tenant service
     */
    public function __construct(
        Enterprise_Theme_Config $config,
        Enterprise_Theme_Database_Service $database,
        Enterprise_Theme_Cache_Service $cache,
        Enterprise_Theme_Tenant_Service $tenant_service
    ) {
        $this->config = $config->get('multicurrency');
        $this->database = $database;
        $this->cache = $cache;
        $this->tenant_service = $tenant_service;
        $this->init_currency_management();
    }
    
    /**
     * Get current currency
     * 
     * @return array Current currency data
     */
    public function get_current_currency(): array {
        if ($this->current_currency === null) {
            $this->current_currency = $this->resolve_current_currency();
        }
        
        return $this->current_currency;
    }
    
    /**
     * Get available currencies for current tenant
     * 
     * @return array Available currencies
     */
    public function get_available_currencies(): array {
        $tenant = $this->tenant_service->get_current_tenant();
        $cache_key = 'available_currencies_' . ($tenant['id'] ?? 0);
        
        return $this->cache->remember(
            $cache_key,
            function() use ($tenant) {
                if ($tenant) {
                    $enabled_currencies = $this->tenant_service->get_tenant_setting(
                        'currencies.enabled',
                        ['USD'],
                        $tenant['id']
                    );
                } else {
                    $enabled_currencies = $this->config['default_currencies'] ?? ['USD'];
                }
                
                $available = [];
                foreach ($enabled_currencies as $code) {
                    if (isset($this->supported_currencies[$code])) {
                        $available[$code] = $this->supported_currencies[$code];
                    }
                }
                
                return $available;
            },
            3600,
            ['group' => 'currencies']
        );
    }
    
    /**
     * Switch to currency
     * 
     * @param string $currency_code Currency code
     * @return bool Success status
     */
    public function switch_currency(string $currency_code): bool {
        $available_currencies = $this->get_available_currencies();
        
        if (!isset($available_currencies[$currency_code])) {
            return false;
        }
        
        $this->current_currency = $available_currencies[$currency_code];
        
        // Store in session/cookie for persistence
        $this->store_currency_preference($currency_code);
        
        // Trigger currency switch hook
        do_action('enterprise_theme_currency_switched', $currency_code, $this->current_currency);
        
        return true;
    }
    
    /**
     * Convert amount between currencies
     * 
     * @param float $amount Amount to convert
     * @param string $from_currency Source currency code
     * @param string $to_currency Target currency code
     * @return float Converted amount
     */
    public function convert_amount(float $amount, string $from_currency, string $to_currency): float {
        if ($from_currency === $to_currency) {
            return $amount;
        }
        
        $exchange_rate = $this->get_exchange_rate($from_currency, $to_currency);
        
        if ($exchange_rate === null) {
            return $amount; // Return original if no rate available
        }
        
        return round($amount * $exchange_rate, $this->supported_currencies[$to_currency]['decimal_places'] ?? 2);
    }
    
    /**
     * Get exchange rate between currencies
     * 
     * @param string $from_currency Source currency code
     * @param string $to_currency Target currency code
     * @return float|null Exchange rate or null if not available
     */
    public function get_exchange_rate(string $from_currency, string $to_currency): ?float {
        if ($from_currency === $to_currency) {
            return 1.0;
        }
        
        $cache_key = "exchange_rate_{$from_currency}_{$to_currency}";
        
        if (isset($this->exchange_rate_cache[$cache_key])) {
            return $this->exchange_rate_cache[$cache_key];
        }
        
        $rate = $this->cache->remember(
            $cache_key,
            function() use ($from_currency, $to_currency) {
                return $this->get_exchange_rate_from_database($from_currency, $to_currency);
            },
            1800, // 30 minutes
            ['group' => 'exchange_rates']
        );
        
        $this->exchange_rate_cache[$cache_key] = $rate;
        
        return $rate;
    }
    
    /**
     * Update exchange rates
     * 
     * @param array $rates Exchange rates data
     * @return bool Success status
     */
    public function update_exchange_rates(array $rates): bool {
        $tenant = $this->tenant_service->get_current_tenant();
        $success = true;
        
        foreach ($rates as $from_currency => $to_rates) {
            foreach ($to_rates as $to_currency => $rate) {
                $rate_data = [
                    'tenant_id' => $tenant['id'] ?? 0,
                    'from_currency' => $from_currency,
                    'to_currency' => $to_currency,
                    'rate' => $rate,
                    'source' => 'manual',
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                
                // Check if rate exists
                $existing = $this->database->get('exchange_rates', [
                    'where' => [
                        'tenant_id' => $tenant['id'] ?? 0,
                        'from_currency' => $from_currency,
                        'to_currency' => $to_currency,
                    ],
                    'limit' => 1
                ]);
                
                if (!empty($existing)) {
                    $result = $this->database->update('exchange_rates', $rate_data, [
                        'id' => $existing[0]['id']
                    ]);
                } else {
                    $rate_data['created_at'] = date('Y-m-d H:i:s');
                    $result = $this->database->insert('exchange_rates', $rate_data);
                }
                
                if ($result === false) {
                    $success = false;
                }
            }
        }
        
        if ($success) {
            // Clear exchange rate cache
            $this->cache->flush_group('exchange_rates');
            $this->exchange_rate_cache = [];
        }
        
        return $success;
    }
    
    /**
     * Fetch exchange rates from external API
     * 
     * @param array $currencies Currency codes to fetch
     * @return array|false Exchange rates or false on failure
     */
    public function fetch_external_exchange_rates(array $currencies = []): array|false {
        if (empty($currencies)) {
            $currencies = array_keys($this->get_available_currencies());
        }
        
        $api_key = $this->config['exchange_rate_api_key'] ?? null;
        $api_provider = $this->config['exchange_rate_provider'] ?? 'fixer';
        
        switch ($api_provider) {
            case 'fixer':
                return $this->fetch_fixer_rates($currencies, $api_key);
            case 'openexchangerates':
                return $this->fetch_openexchangerates_rates($currencies, $api_key);
            case 'currencylayer':
                return $this->fetch_currencylayer_rates($currencies, $api_key);
            default:
                return false;
        }
    }
    
    /**
     * Format price according to currency
     * 
     * @param float $amount Amount to format
     * @param string $currency_code Currency code (optional)
     * @param array $options Formatting options
     * @return string Formatted price
     */
    public function format_price(float $amount, string $currency_code = null, array $options = []): string {
        if ($currency_code === null) {
            $currency_code = $this->get_current_currency()['code'];
        }
        
        $currency = $this->supported_currencies[$currency_code] ?? $this->supported_currencies['USD'];
        
        $decimal_places = $options['decimal_places'] ?? $currency['decimal_places'];
        $decimal_separator = $options['decimal_separator'] ?? $currency['decimal_separator'];
        $thousand_separator = $options['thousand_separator'] ?? $currency['thousand_separator'];
        $show_currency = $options['show_currency'] ?? true;
        $show_symbol = $options['show_symbol'] ?? true;
        
        // Format the number
        $formatted_amount = number_format($amount, $decimal_places, $decimal_separator, $thousand_separator);
        
        if (!$show_currency && !$show_symbol) {
            return $formatted_amount;
        }
        
        $symbol = $show_symbol ? $currency['symbol'] : $currency['code'];
        
        if ($currency['symbol_position'] === 'before') {
            return $symbol . $formatted_amount;
        } else {
            return $formatted_amount . ' ' . $symbol;
        }
    }
    
    /**
     * Parse price from string
     * 
     * @param string $price_string Price string
     * @param string $currency_code Currency code (optional)
     * @return float Parsed amount
     */
    public function parse_price(string $price_string, string $currency_code = null): float {
        if ($currency_code === null) {
            $currency_code = $this->get_current_currency()['code'];
        }
        
        $currency = $this->supported_currencies[$currency_code] ?? $this->supported_currencies['USD'];
        
        // Remove currency symbols and codes
        $cleaned = preg_replace('/[^\d\.,\-]/', '', $price_string);
        
        // Handle different decimal separators
        if ($currency['decimal_separator'] === ',') {
            $cleaned = str_replace(',', '.', $cleaned);
        }
        
        // Remove thousand separators
        $cleaned = str_replace($currency['thousand_separator'], '', $cleaned);
        
        return floatval($cleaned);
    }
    
    /**
     * Detect currency from location
     * 
     * @param string $country_code Country code (optional)
     * @return string Currency code
     */
    public function detect_currency_from_location(string $country_code = null): string {
        if ($country_code === null) {
            $country_code = $this->detect_country_from_ip();
        }
        
        $available_currencies = $this->get_available_currencies();
        
        // Map country codes to currencies
        $country_currency_map = [
            'US' => 'USD',
            'EU' => 'EUR',
            'GB' => 'GBP',
            'JP' => 'JPY',
            'CA' => 'CAD',
            'AU' => 'AUD',
            'CH' => 'CHF',
            'CN' => 'CNY',
            'SE' => 'SEK',
            'NO' => 'NOK',
            'DK' => 'DKK',
            'IN' => 'INR',
        ];
        
        $detected_currency = $country_currency_map[$country_code] ?? 'USD';
        
        // Check if detected currency is available
        if (isset($available_currencies[$detected_currency])) {
            return $detected_currency;
        }
        
        // Fall back to default currency
        foreach ($available_currencies as $currency) {
            if ($currency['is_default'] ?? false) {
                return $currency['code'];
            }
        }
        
        return 'USD';
    }
    
    /**
     * Get currency statistics
     * 
     * @return array Currency statistics
     */
    public function get_currency_statistics(): array {
        $tenant = $this->tenant_service->get_current_tenant();
        $tenant_id = $tenant['id'] ?? 0;
        
        return [
            'available_currencies' => count($this->get_available_currencies()),
            'total_exchange_rates' => $this->database->count('exchange_rates', ['tenant_id' => $tenant_id]),
            'last_rate_update' => $this->get_last_rate_update(),
            'default_currency' => $this->get_default_currency()['code'],
        ];
    }
    
    /**
     * Get currency conversion history
     * 
     * @param string $from_currency Source currency
     * @param string $to_currency Target currency
     * @param int $days Number of days to retrieve
     * @return array Conversion history
     */
    public function get_conversion_history(string $from_currency, string $to_currency, int $days = 30): array {
        $tenant = $this->tenant_service->get_current_tenant();
        
        $history = $this->database->query(
            "SELECT rate, DATE(updated_at) as date FROM " . $this->database->get_table_name('exchange_rates') . " 
             WHERE tenant_id = %d AND from_currency = %s AND to_currency = %s 
             AND updated_at >= DATE_SUB(NOW(), INTERVAL %d DAY)
             ORDER BY updated_at DESC",
            [$tenant['id'] ?? 0, $from_currency, $to_currency, $days]
        );
        
        return $history ?: [];
    }
    
    /**
     * Initialize currency management
     * 
     * @return void
     */
    private function init_currency_management(): void {
        // Hook into WordPress init to detect currency
        add_action('init', [$this, 'early_currency_detection'], 1);
        
        // Schedule exchange rate updates
        if ($this->config['auto_update_rates'] ?? false) {
            add_action('enterprise_theme_update_exchange_rates', [$this, 'scheduled_rate_update']);
            
            if (!wp_next_scheduled('enterprise_theme_update_exchange_rates')) {
                wp_schedule_event(time(), 'hourly', 'enterprise_theme_update_exchange_rates');
            }
        }
        
        // Initialize database tables
        $this->create_currency_tables();
    }
    
    /**
     * Resolve current currency
     * 
     * @return array Current currency data
     */
    private function resolve_current_currency(): array {
        $available_currencies = $this->get_available_currencies();
        
        // 1. Check stored preference (session/cookie)
        $stored_currency = $this->get_stored_currency_preference();
        if ($stored_currency && isset($available_currencies[$stored_currency])) {
            return $available_currencies[$stored_currency];
        }
        
        // 2. Check geolocation
        if ($this->config['auto_detect_currency'] ?? false) {
            $detected_currency = $this->detect_currency_from_location();
            if (isset($available_currencies[$detected_currency])) {
                return $available_currencies[$detected_currency];
            }
        }
        
        // 3. Fall back to default currency
        foreach ($available_currencies as $currency) {
            if ($currency['is_default'] ?? false) {
                return $currency;
            }
        }
        
        // 4. Fall back to USD
        return $this->supported_currencies['USD'];
    }
    
    /**
     * Get exchange rate from database
     * 
     * @param string $from_currency Source currency
     * @param string $to_currency Target currency
     * @return float|null Exchange rate or null
     */
    private function get_exchange_rate_from_database(string $from_currency, string $to_currency): ?float {
        $tenant = $this->tenant_service->get_current_tenant();
        
        $results = $this->database->get('exchange_rates', [
            'where' => [
                'tenant_id' => $tenant['id'] ?? 0,
                'from_currency' => $from_currency,
                'to_currency' => $to_currency,
            ],
            'order_by' => 'updated_at DESC',
            'limit' => 1
        ]);
        
        return !empty($results) ? floatval($results[0]['rate']) : null;
    }
    
    /**
     * Store currency preference
     * 
     * @param string $currency_code Currency code
     * @return void
     */
    private function store_currency_preference(string $currency_code): void {
        // Store in cookie
        setcookie(
            'enterprise_theme_currency',
            $currency_code,
            time() + (86400 * 30), // 30 days
            '/',
            '',
            is_ssl(),
            true
        );
        
        // Store in session if available
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['enterprise_theme_currency'] = $currency_code;
        }
    }
    
    /**
     * Get stored currency preference
     * 
     * @return string|null Stored currency code or null
     */
    private function get_stored_currency_preference(): ?string {
        // Check session first
        if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['enterprise_theme_currency'])) {
            return $_SESSION['enterprise_theme_currency'];
        }
        
        // Check cookie
        return $_COOKIE['enterprise_theme_currency'] ?? null;
    }
    
    /**
     * Detect country from IP address
     * 
     * @return string Country code
     */
    private function detect_country_from_ip(): string {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        
        // Use a geolocation service or database
        // This is a simplified implementation
        $geoip_service = $this->config['geoip_service'] ?? null;
        
        if ($geoip_service) {
            $country_code = $this->query_geoip_service($ip, $geoip_service);
            if ($country_code) {
                return $country_code;
            }
        }
        
        return 'US'; // Default fallback
    }
    
    /**
     * Query GeoIP service
     * 
     * @param string $ip IP address
     * @param string $service Service name
     * @return string|null Country code or null
     */
    private function query_geoip_service(string $ip, string $service): ?string {
        // Implement GeoIP service queries
        // This is a placeholder for actual implementation
        return null;
    }
    
    /**
     * Fetch exchange rates from Fixer.io
     * 
     * @param array $currencies Currency codes
     * @param string $api_key API key
     * @return array|false Exchange rates or false
     */
    private function fetch_fixer_rates(array $currencies, string $api_key): array|false {
        if (!$api_key) {
            return false;
        }
        
        $base_currency = 'EUR'; // Fixer.io uses EUR as base
        $symbols = implode(',', $currencies);
        
        $url = "http://data.fixer.io/api/latest?access_key={$api_key}&symbols={$symbols}";
        
        $response = wp_remote_get($url);
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (!$data || !$data['success']) {
            return false;
        }
        
        return $data['rates'] ?? [];
    }
    
    /**
     * Fetch exchange rates from Open Exchange Rates
     * 
     * @param array $currencies Currency codes
     * @param string $api_key API key
     * @return array|false Exchange rates or false
     */
    private function fetch_openexchangerates_rates(array $currencies, string $api_key): array|false {
        if (!$api_key) {
            return false;
        }
        
        $symbols = implode(',', $currencies);
        $url = "https://openexchangerates.org/api/latest.json?app_id={$api_key}&symbols={$symbols}";
        
        $response = wp_remote_get($url);
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        return $data['rates'] ?? [];
    }
    
    /**
     * Fetch exchange rates from CurrencyLayer
     * 
     * @param array $currencies Currency codes
     * @param string $api_key API key
     * @return array|false Exchange rates or false
     */
    private function fetch_currencylayer_rates(array $currencies, string $api_key): array|false {
        if (!$api_key) {
            return false;
        }
        
        $currencies_string = implode(',', $currencies);
        $url = "http://api.currencylayer.com/live?access_key={$api_key}&currencies={$currencies_string}";
        
        $response = wp_remote_get($url);
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (!$data || !$data['success']) {
            return false;
        }
        
        return $data['quotes'] ?? [];
    }
    
    /**
     * Create currency database tables
     * 
     * @return void
     */
    private function create_currency_tables(): void {
        // Currencies table
        $this->database->create_table('currencies', [
            'columns' => [
                'id' => ['type' => 'bigint', 'auto_increment' => true, 'primary' => true],
                'tenant_id' => ['type' => 'bigint'],
                'code' => ['type' => 'varchar', 'length' => 3],
                'name' => ['type' => 'varchar', 'length' => 100],
                'symbol' => ['type' => 'varchar', 'length' => 10],
                'symbol_position' => ['type' => 'varchar', 'length' => 10, 'default' => 'before'],
                'decimal_places' => ['type' => 'int', 'default' => 2],
                'decimal_separator' => ['type' => 'varchar', 'length' => 1, 'default' => '.'],
                'thousand_separator' => ['type' => 'varchar', 'length' => 1, 'default' => ','],
                'is_default' => ['type' => 'boolean', 'default' => false],
                'is_active' => ['type' => 'boolean', 'default' => true],
                'sort_order' => ['type' => 'int', 'default' => 0],
                'created_at' => ['type' => 'datetime'],
                'updated_at' => ['type' => 'datetime'],
            ],
            'indexes' => [
                'tenant_code_unique' => ['type' => 'unique', 'columns' => ['tenant_id', 'code']],
                'tenant_index' => ['columns' => ['tenant_id']],
                'code_index' => ['columns' => ['code']],
            ],
        ]);
        
        // Exchange rates table
        $this->database->create_table('exchange_rates', [
            'columns' => [
                'id' => ['type' => 'bigint', 'auto_increment' => true, 'primary' => true],
                'tenant_id' => ['type' => 'bigint'],
                'from_currency' => ['type' => 'varchar', 'length' => 3],
                'to_currency' => ['type' => 'varchar', 'length' => 3],
                'rate' => ['type' => 'decimal', 'precision' => 15, 'scale' => 6],
                'source' => ['type' => 'varchar', 'length' => 50, 'default' => 'manual'],
                'created_at' => ['type' => 'datetime'],
                'updated_at' => ['type' => 'datetime'],
            ],
            'indexes' => [
                'rate_lookup' => ['columns' => ['tenant_id', 'from_currency', 'to_currency']],
                'tenant_index' => ['columns' => ['tenant_id']],
                'updated_index' => ['columns' => ['updated_at']],
            ],
        ]);
    }
    
    /**
     * Get default currency
     * 
     * @return array Default currency data
     */
    private function get_default_currency(): array {
        $available_currencies = $this->get_available_currencies();
        
        foreach ($available_currencies as $currency) {
            if ($currency['is_default'] ?? false) {
                return $currency;
            }
        }
        
        return $this->supported_currencies['USD'];
    }
    
    /**
     * Get last rate update timestamp
     * 
     * @return string|null Last update timestamp or null
     */
    private function get_last_rate_update(): ?string {
        $tenant = $this->tenant_service->get_current_tenant();
        
        $results = $this->database->get('exchange_rates', [
            'where' => ['tenant_id' => $tenant['id'] ?? 0],
            'order_by' => 'updated_at DESC',
            'limit' => 1
        ]);
        
        return !empty($results) ? $results[0]['updated_at'] : null;
    }
    
    /**
     * Early currency detection
     * 
     * @return void
     */
    public function early_currency_detection(): void {
        // Detect and set current currency early
        $this->get_current_currency();
    }
    
    /**
     * Scheduled exchange rate update
     * 
     * @return void
     */
    public function scheduled_rate_update(): void {
        $rates = $this->fetch_external_exchange_rates();
        
        if ($rates) {
            $this->update_exchange_rates(['USD' => $rates]);
        }
    }
}
