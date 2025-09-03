<?php
/**
 * Enterprise Theme Configuration
 * 
 * Central configuration for modular, multitenant, multivendor WordPress theme
 * Implements SOLID principles, DRY, KISS, YAGNI, and separation of concerns
 * 
 * @package Enterprise_Theme
 * @version 2.0.0
 * @author Enterprise Development Team
 * @since 2.0.0
 * @license GPL-2.0-or-later
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

/**
 * Theme Configuration Class
 * 
 * Implements Singleton pattern for global configuration management
 * Follows Single Responsibility Principle - only handles configuration
 */
final class Enterprise_Theme_Config {
    
    /**
     * Singleton instance
     * 
     * @var Enterprise_Theme_Config|null
     */
    private static ?Enterprise_Theme_Config $instance = null;
    
    /**
     * Theme configuration data
     * 
     * @var array
     */
    private array $config = [];
    
    /**
     * Supported features
     * 
     * @var array
     */
    private array $features = [];
    
    /**
     * Theme constants
     * 
     * @var array
     */
    private array $constants = [];
    
    /**
     * Private constructor (Singleton pattern)
     */
    private function __construct() {
        $this->init_constants();
        $this->init_config();
        $this->init_features();
    }
    
    /**
     * Get singleton instance
     * 
     * @return Enterprise_Theme_Config
     */
    public static function get_instance(): Enterprise_Theme_Config {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Prevent cloning (Singleton pattern)
     */
    private function __clone() {}
    
    /**
     * Prevent unserialization (Singleton pattern)
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
    
    /**
     * Initialize theme constants
     * 
     * @return void
     */
    private function init_constants(): void {
        $this->constants = [
            // Core theme constants
            'THEME_VERSION' => '2.0.0',
            'THEME_NAME' => 'Enterprise Multitenant Theme',
            'THEME_SLUG' => 'enterprise-theme',
            'THEME_PREFIX' => 'et_',
            'THEME_TEXTDOMAIN' => 'enterprise-theme',
            
            // Directory paths
            'THEME_PATH' => get_template_directory(),
            'THEME_URL' => get_template_directory_uri(),
            'THEME_INC_PATH' => get_template_directory() . '/inc',
            'THEME_ASSETS_PATH' => get_template_directory() . '/assets',
            'THEME_ASSETS_URL' => get_template_directory_uri() . '/assets',
            'THEME_COMPONENTS_PATH' => get_template_directory() . '/components',
            'THEME_TEMPLATES_PATH' => get_template_directory() . '/templates',
            'THEME_LANGUAGES_PATH' => get_template_directory() . '/languages',
            
            // Upload and cache directories
            'THEME_UPLOADS_PATH' => wp_upload_dir()['basedir'] . '/enterprise-theme',
            'THEME_CACHE_PATH' => wp_upload_dir()['basedir'] . '/enterprise-theme/cache',
            'THEME_LOGS_PATH' => wp_upload_dir()['basedir'] . '/enterprise-theme/logs',
            
            // Minimum requirements
            'MIN_PHP_VERSION' => '8.1',
            'MIN_WP_VERSION' => '6.2',
            'MIN_MYSQL_VERSION' => '5.7',
            
            // Security
            'NONCE_LIFETIME' => 12 * HOUR_IN_SECONDS,
            'SESSION_LIFETIME' => 24 * HOUR_IN_SECONDS,
            
            // Performance
            'CACHE_EXPIRATION' => 6 * HOUR_IN_SECONDS,
            'ASSET_VERSION_TYPE' => 'timestamp', // 'timestamp' or 'hash'
            
            // Multitenancy
            'TENANT_ISOLATION_LEVEL' => 'database', // 'database', 'schema', 'shared'
            'MAX_TENANTS_PER_INSTALL' => 100,
            
            // API
            'API_VERSION' => 'v1',
            'API_RATE_LIMIT' => 1000, // requests per hour
            'API_AUTH_METHOD' => 'jwt', // 'jwt', 'oauth', 'api_key'
        ];
        
        // Define constants
        foreach ($this->constants as $name => $value) {
            if (!defined($name)) {
                define($name, $value);
            }
        }
    }
    
    /**
     * Initialize theme configuration
     * 
     * @return void
     */
    private function init_config(): void {
        $this->config = [
            
            // Theme identification
            'theme' => [
                'name' => THEME_NAME,
                'version' => THEME_VERSION,
                'slug' => THEME_SLUG,
                'textdomain' => THEME_TEXTDOMAIN,
                'author' => 'Enterprise Development Team',
                'description' => 'Advanced multitenant, multivendor WordPress theme with enterprise features',
                'license' => 'GPL-2.0-or-later',
                'requires_php' => MIN_PHP_VERSION,
                'requires_wp' => MIN_WP_VERSION,
                'tested_up_to' => '6.4',
                'network' => true,
                'update_uri' => false,
            ],
            
            // Multitenancy configuration
            'multitenancy' => [
                'enabled' => true,
                'isolation_level' => TENANT_ISOLATION_LEVEL,
                'subdomain_routing' => true,
                'subdirectory_routing' => true,
                'custom_domain_mapping' => true,
                'tenant_switching' => true,
                'shared_users' => false,
                'shared_media' => false,
                'tenant_specific_themes' => true,
                'tenant_specific_plugins' => true,
                'database_prefix_separator' => '_tenant_',
                'max_tenants' => MAX_TENANTS_PER_INSTALL,
            ],
            
            // Multivendor configuration
            'multivendor' => [
                'enabled' => true,
                'vendor_registration' => true,
                'vendor_approval_required' => true,
                'commission_type' => 'percentage', // 'percentage', 'fixed', 'hybrid'
                'default_commission_rate' => 15.0,
                'vendor_dashboard' => true,
                'vendor_analytics' => true,
                'vendor_payments' => true,
                'product_approval_required' => true,
                'vendor_review_system' => true,
                'vendor_messaging' => true,
                'vendor_verification' => true,
            ],
            
            // Multi-theme support
            'themes' => [
                'enabled' => true,
                'tenant_specific' => true,
                'theme_switching' => true,
                'theme_customization' => true,
                'custom_css_support' => true,
                'theme_marketplace' => true,
                'theme_inheritance' => true,
                'available_themes' => [
                    'default' => 'Default Enterprise Theme',
                    'ecommerce' => 'E-commerce Focused',
                    'corporate' => 'Corporate Business',
                    'marketplace' => 'Marketplace Platform',
                    'saas' => 'SaaS Application',
                    'portfolio' => 'Portfolio Showcase',
                    'blog' => 'Blog & Content',
                    'education' => 'Educational Platform',
                ],
            ],
            
            // Multilingual support
            'multilingual' => [
                'enabled' => true,
                'default_language' => 'en_US',
                'rtl_support' => true,
                'automatic_detection' => true,
                'language_switcher' => true,
                'translate_urls' => true,
                'content_translation' => true,
                'interface_translation' => true,
                'date_localization' => true,
                'currency_localization' => true,
                'supported_languages' => [
                    'en_US' => 'English (US)',
                    'en_GB' => 'English (UK)',
                    'es_ES' => 'Spanish',
                    'fr_FR' => 'French',
                    'de_DE' => 'German',
                    'it_IT' => 'Italian',
                    'pt_BR' => 'Portuguese (Brazil)',
                    'ru_RU' => 'Russian',
                    'zh_CN' => 'Chinese (Simplified)',
                    'ja' => 'Japanese',
                    'ko_KR' => 'Korean',
                    'ar' => 'Arabic',
                    'hi_IN' => 'Hindi',
                ],
            ],
            
            // Multicurrency support
            'multicurrency' => [
                'enabled' => true,
                'default_currency' => 'USD',
                'auto_detection' => true,
                'currency_switcher' => true,
                'real_time_rates' => true,
                'rate_update_frequency' => 'hourly',
                'supported_currencies' => [
                    'USD' => 'US Dollar',
                    'EUR' => 'Euro',
                    'GBP' => 'British Pound',
                    'JPY' => 'Japanese Yen',
                    'CAD' => 'Canadian Dollar',
                    'AUD' => 'Australian Dollar',
                    'CHF' => 'Swiss Franc',
                    'CNY' => 'Chinese Yuan',
                    'INR' => 'Indian Rupee',
                    'BRL' => 'Brazilian Real',
                ],
                'payment_gateways' => [
                    'stripe' => 'Stripe',
                    'paypal' => 'PayPal',
                    'square' => 'Square',
                    'razorpay' => 'Razorpay',
                    'mollie' => 'Mollie',
                ],
            ],
            
            // Performance optimization
            'performance' => [
                'caching' => [
                    'enabled' => true,
                    'object_cache' => true,
                    'page_cache' => true,
                    'database_cache' => true,
                    'fragment_cache' => true,
                    'cache_lifetime' => CACHE_EXPIRATION,
                ],
                'optimization' => [
                    'lazy_loading' => true,
                    'image_optimization' => true,
                    'css_minification' => true,
                    'js_minification' => true,
                    'html_minification' => false,
                    'critical_css' => true,
                    'defer_non_critical_css' => true,
                    'async_js' => true,
                    'preload_fonts' => true,
                    'gzip_compression' => true,
                ],
                'database' => [
                    'query_optimization' => true,
                    'index_optimization' => true,
                    'connection_pooling' => true,
                    'read_replicas' => false,
                ],
            ],
            
            // Security configuration
            'security' => [
                'authentication' => [
                    'two_factor' => true,
                    'session_management' => true,
                    'login_rate_limiting' => true,
                    'password_policies' => true,
                    'account_lockout' => true,
                ],
                'authorization' => [
                    'role_based_access' => true,
                    'capability_management' => true,
                    'permission_inheritance' => true,
                    'dynamic_permissions' => true,
                ],
                'data_protection' => [
                    'input_sanitization' => true,
                    'output_escaping' => true,
                    'sql_injection_protection' => true,
                    'xss_protection' => true,
                    'csrf_protection' => true,
                    'content_security_policy' => true,
                ],
                'encryption' => [
                    'data_at_rest' => true,
                    'data_in_transit' => true,
                    'field_level_encryption' => true,
                    'key_management' => true,
                ],
            ],
            
            // API configuration
            'api' => [
                'rest_api' => [
                    'enabled' => true,
                    'version' => API_VERSION,
                    'authentication' => API_AUTH_METHOD,
                    'rate_limiting' => true,
                    'rate_limit' => API_RATE_LIMIT,
                    'documentation' => true,
                    'versioning' => true,
                ],
                'graphql' => [
                    'enabled' => false,
                    'introspection' => false,
                    'playground' => false,
                ],
                'webhooks' => [
                    'enabled' => true,
                    'retry_mechanism' => true,
                    'signature_verification' => true,
                    'delivery_logs' => true,
                ],
            ],
            
            // User management
            'users' => [
                'registration' => [
                    'enabled' => true,
                    'email_verification' => true,
                    'admin_approval' => false,
                    'custom_fields' => true,
                    'profile_completion' => true,
                ],
                'roles' => [
                    'custom_roles' => true,
                    'role_hierarchy' => true,
                    'temporary_roles' => true,
                    'role_switching' => true,
                ],
                'profiles' => [
                    'extended_profiles' => true,
                    'profile_privacy' => true,
                    'avatar_management' => true,
                    'social_integration' => true,
                ],
            ],
            
            // Content management
            'content' => [
                'post_types' => [
                    'custom_post_types' => true,
                    'post_type_templates' => true,
                    'post_relationships' => true,
                    'content_versioning' => true,
                ],
                'taxonomies' => [
                    'custom_taxonomies' => true,
                    'hierarchical_terms' => true,
                    'term_meta' => true,
                    'taxonomy_templates' => true,
                ],
                'media' => [
                    'advanced_media_library' => true,
                    'media_folders' => true,
                    'media_permissions' => true,
                    'cdn_integration' => true,
                ],
            ],
            
            // E-commerce features
            'ecommerce' => [
                'enabled' => true,
                'woocommerce_integration' => true,
                'multiple_currencies' => true,
                'multiple_payment_gateways' => true,
                'inventory_management' => true,
                'order_management' => true,
                'shipping_management' => true,
                'tax_management' => true,
                'coupon_management' => true,
                'subscription_support' => true,
                'marketplace_features' => true,
            ],
            
            // SEO optimization
            'seo' => [
                'meta_tags' => true,
                'schema_markup' => true,
                'sitemap_generation' => true,
                'robots_txt' => true,
                'canonical_urls' => true,
                'open_graph' => true,
                'twitter_cards' => true,
                'breadcrumbs' => true,
                'internal_linking' => true,
                'image_optimization' => true,
            ],
            
            // Analytics and tracking
            'analytics' => [
                'google_analytics' => true,
                'google_tag_manager' => true,
                'facebook_pixel' => true,
                'custom_tracking' => true,
                'event_tracking' => true,
                'conversion_tracking' => true,
                'heat_mapping' => false,
                'a_b_testing' => false,
            ],
            
            // Development tools
            'development' => [
                'debug_mode' => WP_DEBUG,
                'query_monitor' => WP_DEBUG,
                'error_logging' => true,
                'profiling' => false,
                'code_coverage' => false,
                'automated_testing' => false,
                'webpack_integration' => true,
                'hot_reload' => WP_DEBUG,
            ],
        ];
        
        // Allow configuration filtering
        $this->config = apply_filters('enterprise_theme_config', $this->config);
    }
    
    /**
     * Initialize supported features
     * 
     * @return void
     */
    private function init_features(): void {
        $this->features = [
            // WordPress core features
            'automatic-feed-links',
            'title-tag',
            'post-thumbnails',
            'responsive-embeds',
            'editor-styles',
            'align-wide',
            'custom-line-height',
            'custom-units',
            'custom-spacing',
            'custom-logo',
            'custom-background',
            'custom-header',
            
            // HTML5 support
            'html5' => [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            ],
            
            // Post formats
            'post-formats' => [
                'aside',
                'gallery',
                'link',
                'image',
                'quote',
                'status',
                'video',
                'audio',
                'chat',
            ],
            
            // Gutenberg features
            'editor-color-palette' => $this->get_color_palette(),
            'editor-font-sizes' => $this->get_font_sizes(),
            'editor-gradient-presets' => $this->get_gradient_presets(),
            
            // WooCommerce features
            'woocommerce',
            'wc-product-gallery-zoom',
            'wc-product-gallery-lightbox',
            'wc-product-gallery-slider',
            
            // Custom features
            'enterprise-multitenancy',
            'enterprise-multivendor',
            'enterprise-multilingual',
            'enterprise-multicurrency',
            'enterprise-api',
            'enterprise-security',
            'enterprise-performance',
        ];
        
        // Allow features filtering
        $this->features = apply_filters('enterprise_theme_features', $this->features);
    }
    
    /**
     * Get configuration value
     * 
     * @param string $key Configuration key (dot notation supported)
     * @param mixed $default Default value if key not found
     * @return mixed Configuration value
     */
    public function get(string $key, $default = null) {
        return $this->get_nested_value($this->config, $key, $default);
    }
    
    /**
     * Set configuration value
     * 
     * @param string $key Configuration key (dot notation supported)
     * @param mixed $value Value to set
     * @return void
     */
    public function set(string $key, $value): void {
        $this->set_nested_value($this->config, $key, $value);
    }
    
    /**
     * Get all configuration
     * 
     * @return array Complete configuration array
     */
    public function get_all(): array {
        return $this->config;
    }
    
    /**
     * Get supported features
     * 
     * @return array Supported features
     */
    public function get_features(): array {
        return $this->features;
    }
    
    /**
     * Check if feature is enabled
     * 
     * @param string $feature Feature name
     * @return bool True if enabled, false otherwise
     */
    public function has_feature(string $feature): bool {
        return in_array($feature, $this->features, true);
    }
    
    /**
     * Get constant value
     * 
     * @param string $name Constant name
     * @return mixed Constant value or null if not defined
     */
    public function get_constant(string $name) {
        return $this->constants[$name] ?? (defined($name) ? constant($name) : null);
    }
    
    /**
     * Get nested array value using dot notation
     * 
     * @param array $array Source array
     * @param string $key Key in dot notation
     * @param mixed $default Default value
     * @return mixed Found value or default
     */
    private function get_nested_value(array $array, string $key, $default = null) {
        $keys = explode('.', $key);
        $value = $array;
        
        foreach ($keys as $k) {
            if (!is_array($value) || !array_key_exists($k, $value)) {
                return $default;
            }
            $value = $value[$k];
        }
        
        return $value;
    }
    
    /**
     * Set nested array value using dot notation
     * 
     * @param array &$array Target array (passed by reference)
     * @param string $key Key in dot notation
     * @param mixed $value Value to set
     * @return void
     */
    private function set_nested_value(array &$array, string $key, $value): void {
        $keys = explode('.', $key);
        $current = &$array;
        
        foreach ($keys as $k) {
            if (!isset($current[$k]) || !is_array($current[$k])) {
                $current[$k] = [];
            }
            $current = &$current[$k];
        }
        
        $current = $value;
    }
    
    /**
     * Get color palette for Gutenberg
     * 
     * @return array Color palette configuration
     */
    private function get_color_palette(): array {
        return [
            [
                'name' => __('Primary', THEME_TEXTDOMAIN),
                'slug' => 'primary',
                'color' => '#007cba',
            ],
            [
                'name' => __('Secondary', THEME_TEXTDOMAIN),
                'slug' => 'secondary',
                'color' => '#006ba1',
            ],
            [
                'name' => __('Accent', THEME_TEXTDOMAIN),
                'slug' => 'accent',
                'color' => '#ff6900',
            ],
            [
                'name' => __('Success', THEME_TEXTDOMAIN),
                'slug' => 'success',
                'color' => '#28a745',
            ],
            [
                'name' => __('Warning', THEME_TEXTDOMAIN),
                'slug' => 'warning',
                'color' => '#ffc107',
            ],
            [
                'name' => __('Danger', THEME_TEXTDOMAIN),
                'slug' => 'danger',
                'color' => '#dc3545',
            ],
            [
                'name' => __('Light', THEME_TEXTDOMAIN),
                'slug' => 'light',
                'color' => '#f8f9fa',
            ],
            [
                'name' => __('Dark', THEME_TEXTDOMAIN),
                'slug' => 'dark',
                'color' => '#343a40',
            ],
        ];
    }
    
    /**
     * Get font sizes for Gutenberg
     * 
     * @return array Font sizes configuration
     */
    private function get_font_sizes(): array {
        return [
            [
                'name' => __('Small', THEME_TEXTDOMAIN),
                'slug' => 'small',
                'size' => 14,
            ],
            [
                'name' => __('Normal', THEME_TEXTDOMAIN),
                'slug' => 'normal',
                'size' => 16,
            ],
            [
                'name' => __('Medium', THEME_TEXTDOMAIN),
                'slug' => 'medium',
                'size' => 20,
            ],
            [
                'name' => __('Large', THEME_TEXTDOMAIN),
                'slug' => 'large',
                'size' => 24,
            ],
            [
                'name' => __('Extra Large', THEME_TEXTDOMAIN),
                'slug' => 'extra-large',
                'size' => 32,
            ],
        ];
    }
    
    /**
     * Get gradient presets for Gutenberg
     * 
     * @return array Gradient presets configuration
     */
    private function get_gradient_presets(): array {
        return [
            [
                'name' => __('Primary Gradient', THEME_TEXTDOMAIN),
                'slug' => 'primary-gradient',
                'gradient' => 'linear-gradient(135deg, #007cba 0%, #006ba1 100%)',
            ],
            [
                'name' => __('Accent Gradient', THEME_TEXTDOMAIN),
                'slug' => 'accent-gradient',
                'gradient' => 'linear-gradient(135deg, #ff6900 0%, #ff5722 100%)',
            ],
            [
                'name' => __('Success Gradient', THEME_TEXTDOMAIN),
                'slug' => 'success-gradient',
                'gradient' => 'linear-gradient(135deg, #28a745 0%, #20c997 100%)',
            ],
        ];
    }
}

/**
 * Get theme configuration instance
 * 
 * @return Enterprise_Theme_Config
 */
function enterprise_theme_config(): Enterprise_Theme_Config {
    return Enterprise_Theme_Config::get_instance();
}

// Initialize configuration
enterprise_theme_config();
