<?php
/**
 * Helper functions for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!function_exists('aqualuxe_asset_url')) {
    /**
     * Get the URL for an asset with versioning from mix-manifest.json
     *
     * @param string $path The path to the asset relative to the assets/dist directory
     * @return string The URL to the asset
     */
    function aqualuxe_asset_url($path) {
        static $manifest = null;
        
        if (is_null($manifest)) {
            $manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
            $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : [];
        }
        
        $path = '/' . ltrim($path, '/');
        $versioned_path = isset($manifest[$path]) ? $manifest[$path] : $path;
        
        return AQUALUXE_ASSETS_URI . $versioned_path;
    }
}

if (!function_exists('aqualuxe_is_woocommerce_active')) {
    /**
     * Check if WooCommerce is active
     *
     * @return bool
     */
    function aqualuxe_is_woocommerce_active() {
        return class_exists('WooCommerce');
    }
}

if (!function_exists('aqualuxe_get_option')) {
    /**
     * Get theme option with fallback
     *
     * @param string $option_name The option name
     * @param mixed $default The default value if option doesn't exist
     * @return mixed
     */
    function aqualuxe_get_option($option_name, $default = '') {
        $options = get_theme_mod('aqualuxe_options', []);
        return isset($options[$option_name]) ? $options[$option_name] : $default;
    }
}

if (!function_exists('aqualuxe_get_language')) {
    /**
     * Get current language code
     *
     * @return string
     */
    function aqualuxe_get_language() {
        // Check for WPML
        if (defined('ICL_LANGUAGE_CODE')) {
            return ICL_LANGUAGE_CODE;
        }
        
        // Check for Polylang
        if (function_exists('pll_current_language')) {
            return pll_current_language();
        }
        
        // Default to locale
        return get_locale();
    }
}

if (!function_exists('aqualuxe_get_currency')) {
    /**
     * Get current currency code
     *
     * @return string
     */
    function aqualuxe_get_currency() {
        // Check if WooCommerce is active
        if (aqualuxe_is_woocommerce_active()) {
            return get_woocommerce_currency();
        }
        
        // Default currency from theme options
        return aqualuxe_get_option('default_currency', 'USD');
    }
}

if (!function_exists('aqualuxe_is_dark_mode')) {
    /**
     * Check if dark mode is enabled
     *
     * @return bool
     */
    function aqualuxe_is_dark_mode() {
        return isset($_COOKIE['aqualuxe_dark_mode']) && $_COOKIE['aqualuxe_dark_mode'] === 'true';
    }
}

if (!function_exists('aqualuxe_get_fallback_image')) {
    /**
     * Get fallback image URL
     *
     * @param string $size Image size
     * @return string
     */
    function aqualuxe_get_fallback_image($size = 'thumbnail') {
        $fallback_id = aqualuxe_get_option('fallback_image');
        
        if ($fallback_id) {
            $image = wp_get_attachment_image_src($fallback_id, $size);
            if ($image) {
                return $image[0];
            }
        }
        
        // Default fallback
        return AQUALUXE_ASSETS_URI . '/images/fallback.jpg';
    }
}

if (!function_exists('aqualuxe_get_social_links')) {
    /**
     * Get social media links
     *
     * @return array
     */
    function aqualuxe_get_social_links() {
        $social_links = [];
        $networks = ['facebook', 'twitter', 'instagram', 'youtube', 'linkedin', 'pinterest'];
        
        foreach ($networks as $network) {
            $url = aqualuxe_get_option($network . '_url', '');
            if (!empty($url)) {
                $social_links[$network] = $url;
            }
        }
        
        return $social_links;
    }
}

if (!function_exists('aqualuxe_get_vendor_info')) {
    /**
     * Get vendor information for multivendor support
     *
     * @param int $vendor_id Vendor ID
     * @return array
     */
    function aqualuxe_get_vendor_info($vendor_id = 0) {
        // Default vendor info
        $vendor_info = [
            'id' => $vendor_id,
            'name' => '',
            'description' => '',
            'logo' => '',
            'banner' => '',
            'address' => '',
            'phone' => '',
            'email' => '',
            'social' => [],
        ];
        
        // If no vendor ID provided, return empty info
        if (empty($vendor_id)) {
            return $vendor_info;
        }
        
        // Check for WC Marketplace
        if (function_exists('wcmp_get_vendor') && $vendor = wcmp_get_vendor($vendor_id)) {
            $vendor_info['name'] = $vendor->page_title;
            $vendor_info['description'] = $vendor->description;
            $vendor_info['logo'] = $vendor->get_image() ? $vendor->get_image() : '';
            $vendor_info['banner'] = $vendor->get_image('banner') ? $vendor->get_image('banner') : '';
            $vendor_info['address'] = $vendor->get_formatted_address();
            $vendor_info['phone'] = $vendor->phone;
            $vendor_info['email'] = $vendor->user_data->user_email;
            
            // Social links
            $social_fields = ['fb_profile', 'twitter_profile', 'instagram', 'youtube', 'linkedin'];
            foreach ($social_fields as $field) {
                $value = get_user_meta($vendor_id, '_' . $field, true);
                if (!empty($value)) {
                    $vendor_info['social'][$field] = $value;
                }
            }
        }
        
        // Check for Dokan
        if (function_exists('dokan_get_store_info') && $store_info = dokan_get_store_info($vendor_id)) {
            $vendor_info['name'] = isset($store_info['store_name']) ? $store_info['store_name'] : '';
            $vendor_info['description'] = isset($store_info['store_description']) ? $store_info['store_description'] : '';
            $vendor_info['logo'] = isset($store_info['gravatar']) ? wp_get_attachment_url($store_info['gravatar']) : '';
            $vendor_info['banner'] = isset($store_info['banner']) ? wp_get_attachment_url($store_info['banner']) : '';
            $vendor_info['address'] = isset($store_info['address']) ? $store_info['address'] : '';
            $vendor_info['phone'] = isset($store_info['phone']) ? $store_info['phone'] : '';
            
            // Social links
            if (isset($store_info['social'])) {
                $vendor_info['social'] = $store_info['social'];
            }
        }
        
        // Check for WC Vendors
        if (function_exists('WCV_Vendors') && class_exists('WCV_Vendors')) {
            $vendor_info['name'] = WCV_Vendors::get_vendor_shop_name($vendor_id);
            $vendor_info['description'] = get_user_meta($vendor_id, 'pv_shop_description', true);
            $vendor_info['logo'] = get_user_meta($vendor_id, '_wcv_store_icon_id', true) ? wp_get_attachment_url(get_user_meta($vendor_id, '_wcv_store_icon_id', true)) : '';
            $vendor_info['banner'] = get_user_meta($vendor_id, '_wcv_store_banner_id', true) ? wp_get_attachment_url(get_user_meta($vendor_id, '_wcv_store_banner_id', true)) : '';
            
            // Social links
            $social_fields = ['facebook_url', 'twitter_url', 'instagram_url', 'youtube_url', 'linkedin_url'];
            foreach ($social_fields as $field) {
                $value = get_user_meta($vendor_id, '_' . $field, true);
                if (!empty($value)) {
                    $vendor_info['social'][$field] = $value;
                }
            }
        }
        
        return apply_filters('aqualuxe_vendor_info', $vendor_info, $vendor_id);
    }
}

if (!function_exists('aqualuxe_get_tenant_info')) {
    /**
     * Get tenant information for multitenant support
     *
     * @return array
     */
    function aqualuxe_get_tenant_info() {
        // Get current site info for multisite
        $tenant_info = [
            'id' => get_current_blog_id(),
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'url' => get_site_url(),
            'admin_email' => get_bloginfo('admin_email'),
            'language' => aqualuxe_get_language(),
            'timezone' => get_option('timezone_string'),
            'date_format' => get_option('date_format'),
            'time_format' => get_option('time_format'),
        ];
        
        // Add custom tenant info from theme options
        $tenant_info['logo'] = aqualuxe_get_option('tenant_logo') ? wp_get_attachment_url(aqualuxe_get_option('tenant_logo')) : '';
        $tenant_info['favicon'] = aqualuxe_get_option('tenant_favicon') ? wp_get_attachment_url(aqualuxe_get_option('tenant_favicon')) : '';
        $tenant_info['primary_color'] = aqualuxe_get_option('tenant_primary_color', '#0077B6');
        $tenant_info['secondary_color'] = aqualuxe_get_option('tenant_secondary_color', '#CAF0F8');
        $tenant_info['accent_color'] = aqualuxe_get_option('tenant_accent_color', '#FFD700');
        
        return apply_filters('aqualuxe_tenant_info', $tenant_info);
    }
}

if (!function_exists('aqualuxe_format_price')) {
    /**
     * Format price with currency
     *
     * @param float $price Price amount
     * @param string $currency Currency code
     * @return string
     */
    function aqualuxe_format_price($price, $currency = '') {
        if (aqualuxe_is_woocommerce_active()) {
            return wc_price($price, ['currency' => $currency]);
        }
        
        // Fallback price formatting
        $currency = $currency ? $currency : aqualuxe_get_currency();
        $currency_symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'AUD' => 'A$',
            'CAD' => 'C$',
            'CHF' => 'CHF',
        ];
        
        $symbol = isset($currency_symbols[$currency]) ? $currency_symbols[$currency] : $currency;
        
        return $symbol . number_format($price, 2);
    }
}