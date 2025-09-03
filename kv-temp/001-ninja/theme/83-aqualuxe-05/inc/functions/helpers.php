<?php
/**
 * Helper Functions
 * 
 * Collection of utility functions following DRY principle
 * 
 * @package KV_Enterprise
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

/**
 * Get theme option with fallback
 * 
 * @param string $option_name Option name
 * @param mixed  $default     Default value
 * @return mixed Option value or default
 */
function kv_get_theme_option($option_name, $default = null) {
    $options = get_option('kv_enterprise_options', []);
    return isset($options[$option_name]) ? $options[$option_name] : $default;
}

/**
 * Set theme option
 * 
 * @param string $option_name Option name
 * @param mixed  $value       Option value
 * @return bool Success status
 */
function kv_set_theme_option($option_name, $value) {
    $options = get_option('kv_enterprise_options', []);
    $options[$option_name] = $value;
    return update_option('kv_enterprise_options', $options);
}

/**
 * Check if user has specific capability
 * 
 * @param string $capability Capability to check
 * @param int    $user_id    User ID (optional, defaults to current user)
 * @return bool Whether user has capability
 */
function kv_user_can($capability, $user_id = null) {
    if (null === $user_id) {
        return current_user_can($capability);
    }
    
    $user = get_user_by('id', $user_id);
    return $user && user_can($user, $capability);
}

/**
 * Sanitize input data
 * 
 * @param mixed  $data Input data
 * @param string $type Sanitization type
 * @return mixed Sanitized data
 */
function kv_sanitize_input($data, $type = 'text') {
    switch ($type) {
        case 'email':
            return sanitize_email($data);
        case 'url':
            return esc_url_raw($data);
        case 'textarea':
            return sanitize_textarea_field($data);
        case 'html':
            return wp_kses_post($data);
        case 'integer':
            return absint($data);
        case 'float':
            return floatval($data);
        case 'boolean':
            return (bool) $data;
        case 'array':
            return is_array($data) ? array_map('sanitize_text_field', $data) : [];
        case 'text':
        default:
            return sanitize_text_field($data);
    }
}

/**
 * Validate input data
 * 
 * @param mixed  $data  Input data
 * @param string $type  Validation type
 * @param array  $rules Additional validation rules
 * @return bool|WP_Error True if valid, WP_Error if invalid
 */
function kv_validate_input($data, $type = 'text', $rules = []) {
    switch ($type) {
        case 'email':
            if (!is_email($data)) {
                return new WP_Error('invalid_email', __('Invalid email address.', KV_THEME_TEXTDOMAIN));
            }
            break;
            
        case 'url':
            if (!filter_var($data, FILTER_VALIDATE_URL)) {
                return new WP_Error('invalid_url', __('Invalid URL.', KV_THEME_TEXTDOMAIN));
            }
            break;
            
        case 'required':
            if (empty($data)) {
                return new WP_Error('required_field', __('This field is required.', KV_THEME_TEXTDOMAIN));
            }
            break;
            
        case 'min_length':
            $min_length = isset($rules['min_length']) ? $rules['min_length'] : 3;
            if (strlen($data) < $min_length) {
                return new WP_Error('min_length', sprintf(__('Minimum length is %d characters.', KV_THEME_TEXTDOMAIN), $min_length));
            }
            break;
            
        case 'max_length':
            $max_length = isset($rules['max_length']) ? $rules['max_length'] : 255;
            if (strlen($data) > $max_length) {
                return new WP_Error('max_length', sprintf(__('Maximum length is %d characters.', KV_THEME_TEXTDOMAIN), $max_length));
            }
            break;
    }
    
    return true;
}

/**
 * Get current tenant ID
 * For multi-tenant functionality
 * 
 * @return int Tenant ID
 */
function kv_get_current_tenant_id() {
    // Default implementation - can be extended
    if (is_multisite()) {
        return get_current_blog_id();
    }
    
    // Check for custom tenant parameter
    $tenant_id = get_query_var('tenant_id');
    if ($tenant_id) {
        return absint($tenant_id);
    }
    
    // Check subdomain
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $subdomain = explode('.', $host)[0];
    
    // Get tenant by subdomain
    $tenant = get_option("kv_tenant_by_subdomain_{$subdomain}");
    if ($tenant) {
        return absint($tenant);
    }
    
    return 1; // Default tenant
}

/**
 * Get current vendor ID
 * For multi-vendor functionality
 * 
 * @return int Vendor ID
 */
function kv_get_current_vendor_id() {
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $vendor_id = get_user_meta($user_id, 'vendor_id', true);
        if ($vendor_id) {
            return absint($vendor_id);
        }
    }
    
    return 0; // No vendor
}

/**
 * Get current language code
 * For multilingual functionality
 * 
 * @return string Language code
 */
function kv_get_current_language() {
    // Check for WPML
    if (function_exists('icl_get_current_language')) {
        return icl_get_current_language();
    }
    
    // Check for Polylang
    if (function_exists('pll_current_language')) {
        return pll_current_language();
    }
    
    // Check for custom language parameter
    $lang = get_query_var('lang');
    if ($lang) {
        return sanitize_text_field($lang);
    }
    
    // Default to WordPress locale
    return get_locale();
}

/**
 * Get current currency code
 * For multi-currency functionality
 * 
 * @return string Currency code
 */
function kv_get_current_currency() {
    // Check user preference
    if (is_user_logged_in()) {
        $user_currency = get_user_meta(get_current_user_id(), 'preferred_currency', true);
        if ($user_currency) {
            return sanitize_text_field($user_currency);
        }
    }
    
    // Check session
    if (session_id() && isset($_SESSION['currency'])) {
        return sanitize_text_field($_SESSION['currency']);
    }
    
    // Check cookie
    if (isset($_COOKIE['kv_currency'])) {
        return sanitize_text_field($_COOKIE['kv_currency']);
    }
    
    // Default currency
    return kv_get_theme_option('default_currency', 'USD');
}

/**
 * Format price with currency
 * 
 * @param float  $amount   Price amount
 * @param string $currency Currency code
 * @return string Formatted price
 */
function kv_format_price($amount, $currency = null) {
    if (null === $currency) {
        $currency = kv_get_current_currency();
    }
    
    $currency_symbols = [
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'JPY' => '¥',
        'CNY' => '¥',
        'INR' => '₹',
    ];
    
    $symbol = isset($currency_symbols[$currency]) ? $currency_symbols[$currency] : $currency . ' ';
    $decimals = in_array($currency, ['JPY', 'KRW']) ? 0 : 2;
    
    return $symbol . number_format($amount, $decimals);
}

/**
 * Get responsive image HTML
 * 
 * @param int    $attachment_id Image attachment ID
 * @param string $size          Image size
 * @param array  $attr          Additional attributes
 * @return string Image HTML
 */
function kv_get_responsive_image($attachment_id, $size = 'large', $attr = []) {
    if (!$attachment_id) {
        return '';
    }
    
    // Set default attributes
    $default_attr = [
        'class' => 'img-responsive',
        'loading' => 'lazy',
    ];
    
    $attr = array_merge($default_attr, $attr);
    
    // Get image with srcset for responsiveness
    return wp_get_attachment_image($attachment_id, $size, false, $attr);
}

/**
 * Get breadcrumb trail
 * 
 * @return array Breadcrumb items
 */
function kv_get_breadcrumbs() {
    $breadcrumbs = [];
    
    // Home
    $breadcrumbs[] = [
        'title' => __('Home', KV_THEME_TEXTDOMAIN),
        'url'   => home_url('/'),
    ];
    
    if (is_category() || is_single()) {
        $category = get_the_category();
        if (!empty($category)) {
            $category = $category[0];
            $breadcrumbs[] = [
                'title' => $category->name,
                'url'   => get_category_link($category->term_id),
            ];
        }
    }
    
    if (is_single()) {
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url'   => '',
        ];
    } elseif (is_page()) {
        // Handle page hierarchy
        $ancestors = get_post_ancestors(get_the_ID());
        $ancestors = array_reverse($ancestors);
        
        foreach ($ancestors as $ancestor) {
            $breadcrumbs[] = [
                'title' => get_the_title($ancestor),
                'url'   => get_permalink($ancestor),
            ];
        }
        
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url'   => '',
        ];
    } elseif (is_archive()) {
        $breadcrumbs[] = [
            'title' => get_the_archive_title(),
            'url'   => '',
        ];
    } elseif (is_search()) {
        $breadcrumbs[] = [
            'title' => sprintf(__('Search Results for: %s', KV_THEME_TEXTDOMAIN), get_search_query()),
            'url'   => '',
        ];
    }
    
    return apply_filters('kv_breadcrumbs', $breadcrumbs);
}

/**
 * Get social sharing links
 * 
 * @param int $post_id Post ID
 * @return array Social sharing links
 */
function kv_get_social_sharing_links($post_id = null) {
    if (null === $post_id) {
        $post_id = get_the_ID();
    }
    
    $post_url = get_permalink($post_id);
    $post_title = get_the_title($post_id);
    $post_excerpt = get_the_excerpt($post_id);
    
    $links = [
        'facebook' => [
            'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($post_url),
            'title' => __('Share on Facebook', KV_THEME_TEXTDOMAIN),
            'icon' => 'fab fa-facebook-f',
        ],
        'twitter' => [
            'url' => 'https://twitter.com/intent/tweet?url=' . urlencode($post_url) . '&text=' . urlencode($post_title),
            'title' => __('Share on Twitter', KV_THEME_TEXTDOMAIN),
            'icon' => 'fab fa-twitter',
        ],
        'linkedin' => [
            'url' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($post_url),
            'title' => __('Share on LinkedIn', KV_THEME_TEXTDOMAIN),
            'icon' => 'fab fa-linkedin-in',
        ],
        'email' => [
            'url' => 'mailto:?subject=' . urlencode($post_title) . '&body=' . urlencode($post_excerpt . ' ' . $post_url),
            'title' => __('Share via Email', KV_THEME_TEXTDOMAIN),
            'icon' => 'fas fa-envelope',
        ],
    ];
    
    return apply_filters('kv_social_sharing_links', $links, $post_id);
}

/**
 * Log error message
 * 
 * @param string $message Error message
 * @param string $level   Error level (error, warning, info, debug)
 * @return void
 */
function kv_log_error($message, $level = 'error') {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        $timestamp = date('Y-m-d H:i:s');
        $log_message = "[{$timestamp}] [{$level}] {$message}";
        
        // Write to debug log
        error_log($log_message);
        
        // Also log to custom log file if writable
        $log_file = KV_THEME_PATH . '/logs/theme.log';
        if (is_writable(dirname($log_file))) {
            file_put_contents($log_file, $log_message . PHP_EOL, FILE_APPEND | LOCK_EX);
        }
    }
}

/**
 * Check if request is AJAX
 * 
 * @return bool Whether request is AJAX
 */
function kv_is_ajax() {
    return defined('DOING_AJAX') && DOING_AJAX;
}

/**
 * Check if request is REST API
 * 
 * @return bool Whether request is REST API
 */
function kv_is_rest() {
    return defined('REST_REQUEST') && REST_REQUEST;
}

/**
 * Get template part with fallback
 * 
 * @param string $slug Template slug
 * @param string $name Template name
 * @param array  $args Template arguments
 * @return void
 */
function kv_get_template_part($slug, $name = null, $args = []) {
    // Extract args to local scope
    if (!empty($args) && is_array($args)) {
        extract($args);
    }
    
    // Build template hierarchy
    $templates = [];
    if ($name) {
        $templates[] = "{$slug}-{$name}.php";
    }
    $templates[] = "{$slug}.php";
    
    // Look for template in theme directory
    $template = locate_template($templates);
    
    if ($template) {
        include $template;
    } else {
        // Fallback to plugin template if exists
        foreach ($templates as $template_name) {
            $fallback_template = KV_THEME_PATH . "/templates/{$template_name}";
            if (file_exists($fallback_template)) {
                include $fallback_template;
                break;
            }
        }
    }
}

/**
 * Generate unique ID
 * 
 * @param string $prefix ID prefix
 * @return string Unique ID
 */
function kv_generate_unique_id($prefix = 'kv') {
    return $prefix . '_' . uniqid() . '_' . mt_rand(1000, 9999);
}

/**
 * Check if feature is enabled
 * 
 * @param string $feature Feature name
 * @return bool Whether feature is enabled
 */
function kv_is_feature_enabled($feature) {
    $enabled_features = kv_get_theme_option('enabled_features', []);
    return in_array($feature, $enabled_features, true);
}

/**
 * Get mobile detect instance
 * 
 * @return Mobile_Detect|null Mobile detect instance
 */
function kv_get_mobile_detect() {
    static $mobile_detect = null;
    
    if (null === $mobile_detect && class_exists('Mobile_Detect')) {
        $mobile_detect = new Mobile_Detect();
    }
    
    return $mobile_detect;
}

/**
 * Check if current device is mobile
 * 
 * @return bool Whether device is mobile
 */
function kv_is_mobile() {
    $mobile_detect = kv_get_mobile_detect();
    return $mobile_detect ? $mobile_detect->isMobile() : false;
}

/**
 * Check if current device is tablet
 * 
 * @return bool Whether device is tablet
 */
function kv_is_tablet() {
    $mobile_detect = kv_get_mobile_detect();
    return $mobile_detect ? $mobile_detect->isTablet() : false;
}

/**
 * Get cache key with prefix
 * 
 * @param string $key Cache key
 * @return string Prefixed cache key
 */
function kv_get_cache_key($key) {
    return 'kv_enterprise_' . md5($key . get_current_blog_id() . kv_get_current_language());
}

/**
 * Set cache with expiration
 * 
 * @param string $key        Cache key
 * @param mixed  $data       Data to cache
 * @param int    $expiration Expiration time in seconds
 * @return bool Success status
 */
function kv_set_cache($key, $data, $expiration = 3600) {
    $cache_key = kv_get_cache_key($key);
    return wp_cache_set($cache_key, $data, 'kv_enterprise', $expiration);
}

/**
 * Get cached data
 * 
 * @param string $key Cache key
 * @return mixed Cached data or false if not found
 */
function kv_get_cache($key) {
    $cache_key = kv_get_cache_key($key);
    return wp_cache_get($cache_key, 'kv_enterprise');
}

/**
 * Delete cached data
 * 
 * @param string $key Cache key
 * @return bool Success status
 */
function kv_delete_cache($key) {
    $cache_key = kv_get_cache_key($key);
    return wp_cache_delete($cache_key, 'kv_enterprise');
}
