<?php
/**
 * Helper functions for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Get theme option from customizer
 *
 * @param string $option_name
 * @param mixed $default
 * @return mixed
 */
function aqualuxe_get_option($option_name, $default = '') {
    $options = get_theme_mod('aqualuxe_options', array());
    return isset($options[$option_name]) ? $options[$option_name] : $default;
}

/**
 * Get current language code
 *
 * @return string
 */
function aqualuxe_get_current_language() {
    // Check if WPML is active
    if (function_exists('icl_get_current_language')) {
        return icl_get_current_language();
    }
    
    // Check if Polylang is active
    if (function_exists('pll_current_language')) {
        return pll_current_language();
    }
    
    // Default to WordPress locale
    return substr(get_locale(), 0, 2);
}

/**
 * Get current currency code
 *
 * @return string
 */
function aqualuxe_get_current_currency() {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return '';
    }
    
    // Check if WooCommerce Multilingual is active
    if (function_exists('wcml_get_woocommerce_currency_option')) {
        return wcml_get_woocommerce_currency_option();
    }
    
    // Check if WPML Currency is active
    if (function_exists('wcml_get_client_currency')) {
        return wcml_get_client_currency();
    }
    
    // Default to WooCommerce currency
    return get_woocommerce_currency();
}

/**
 * Check if dark mode is enabled
 *
 * @return bool
 */
function aqualuxe_is_dark_mode() {
    // Check cookie first
    if (isset($_COOKIE['aqualuxe_dark_mode']) && $_COOKIE['aqualuxe_dark_mode'] === 'true') {
        return true;
    }
    
    // Then check user preference
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $dark_mode = get_user_meta($user_id, 'aqualuxe_dark_mode', true);
        if ($dark_mode === 'true') {
            return true;
        }
    }
    
    // Finally check theme default
    return aqualuxe_get_option('default_dark_mode', false);
}

/**
 * Get asset URL with version
 *
 * @param string $path
 * @return string
 */
function aqualuxe_asset_url($path) {
    $manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    
    if (file_exists($manifest_path)) {
        $manifest = json_decode(file_get_contents($manifest_path), true);
        $key = '/' . $path;
        
        if (isset($manifest[$key])) {
            return get_template_directory_uri() . '/assets/dist' . $manifest[$key];
        }
    }
    
    return get_template_directory_uri() . '/assets/dist/' . $path;
}

/**
 * Get SVG icon
 *
 * @param string $icon
 * @param array $args
 * @return string
 */
function aqualuxe_get_icon($icon, $args = array()) {
    // Set defaults
    $defaults = array(
        'class' => '',
        'size' => 24,
    );
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Build class
    $class = 'aqualuxe-icon';
    if (!empty($args['class'])) {
        $class .= ' ' . $args['class'];
    }
    
    // Get icon path
    $icon_path = get_template_directory() . '/assets/dist/images/icons/' . $icon . '.svg';
    
    // Check if icon exists
    if (!file_exists($icon_path)) {
        return '';
    }
    
    // Get icon content
    $icon_content = file_get_contents($icon_path);
    
    // Replace size
    $icon_content = str_replace('width="24"', 'width="' . esc_attr($args['size']) . '"', $icon_content);
    $icon_content = str_replace('height="24"', 'height="' . esc_attr($args['size']) . '"', $icon_content);
    
    // Add class
    $icon_content = str_replace('<svg', '<svg class="' . esc_attr($class) . '"', $icon_content);
    
    return $icon_content;
}

/**
 * Get currency symbol
 *
 * @param string $currency
 * @return string
 */
function aqualuxe_get_currency_symbol($currency = '') {
    if (empty($currency)) {
        $currency = aqualuxe_get_current_currency();
    }
    
    if (aqualuxe_is_woocommerce_active()) {
        return get_woocommerce_currency_symbol($currency);
    }
    
    $symbols = array(
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'JPY' => '¥',
        'AUD' => 'A$',
        'CAD' => 'C$',
        'CHF' => 'CHF',
        'CNY' => '¥',
        'HKD' => 'HK$',
        'NZD' => 'NZ$',
    );
    
    return isset($symbols[$currency]) ? $symbols[$currency] : $currency;
}

/**
 * Format price with currency symbol
 *
 * @param float $price
 * @param string $currency
 * @return string
 */
function aqualuxe_format_price($price, $currency = '') {
    if (aqualuxe_is_woocommerce_active()) {
        return wc_price($price, array('currency' => $currency));
    }
    
    $symbol = aqualuxe_get_currency_symbol($currency);
    return $symbol . number_format($price, 2);
}

/**
 * Get available languages
 *
 * @return array
 */
function aqualuxe_get_available_languages() {
    $languages = array();
    
    // Check if WPML is active
    if (function_exists('icl_get_languages')) {
        $wpml_languages = icl_get_languages('skip_missing=0');
        
        foreach ($wpml_languages as $code => $language) {
            $languages[$code] = array(
                'code' => $code,
                'name' => $language['native_name'],
                'flag' => $language['country_flag_url'],
                'url' => $language['url'],
                'active' => $language['active'],
            );
        }
        
        return $languages;
    }
    
    // Check if Polylang is active
    if (function_exists('pll_languages_list')) {
        $pll_languages = pll_languages_list(array('fields' => array()));
        
        foreach ($pll_languages as $language) {
            $languages[$language->slug] = array(
                'code' => $language->slug,
                'name' => $language->name,
                'flag' => $language->flag_url,
                'url' => $language->url,
                'active' => $language->slug === pll_current_language(),
            );
        }
        
        return $languages;
    }
    
    // Default to WordPress locale
    $languages[substr(get_locale(), 0, 2)] = array(
        'code' => substr(get_locale(), 0, 2),
        'name' => get_locale(),
        'flag' => '',
        'url' => home_url(),
        'active' => true,
    );
    
    return $languages;
}

/**
 * Get available currencies
 *
 * @return array
 */
function aqualuxe_get_available_currencies() {
    $currencies = array();
    
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return $currencies;
    }
    
    // Check if WooCommerce Multilingual is active
    if (function_exists('wcml_get_multi_currency_values')) {
        $wcml_currencies = wcml_get_multi_currency_values();
        
        foreach ($wcml_currencies as $code => $currency) {
            $currencies[$code] = array(
                'code' => $code,
                'symbol' => get_woocommerce_currency_symbol($code),
                'name' => $currency['name'],
                'active' => wcml_get_client_currency() === $code,
            );
        }
        
        return $currencies;
    }
    
    // Default to WooCommerce currency
    $default_currency = get_woocommerce_currency();
    $currencies[$default_currency] = array(
        'code' => $default_currency,
        'symbol' => get_woocommerce_currency_symbol($default_currency),
        'name' => $default_currency,
        'active' => true,
    );
    
    return $currencies;
}

/**
 * Check if current page is a WooCommerce page
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_page() {
    if (!aqualuxe_is_woocommerce_active()) {
        return false;
    }
    
    return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
}

/**
 * Get fallback template part when WooCommerce is not active
 *
 * @param string $slug
 * @param string $name
 * @return void
 */
function aqualuxe_get_fallback_template_part($slug, $name = null) {
    $templates = array();
    $name = (string) $name;
    
    if ('' !== $name) {
        $templates[] = "{$slug}-{$name}-fallback.php";
    }
    
    $templates[] = "{$slug}-fallback.php";
    
    locate_template($templates, true, false);
}

/**
 * Get template part with fallback for WooCommerce
 *
 * @param string $slug
 * @param string $name
 * @return void
 */
function aqualuxe_get_template_part($slug, $name = null) {
    if (strpos($slug, 'woocommerce') === 0 && !aqualuxe_is_woocommerce_active()) {
        aqualuxe_get_fallback_template_part($slug, $name);
        return;
    }
    
    get_template_part($slug, $name);
}

/**
 * Get page ID by template
 *
 * @param string $template
 * @return int
 */
function aqualuxe_get_page_id_by_template($template) {
    $pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => $template,
    ));
    
    if (!empty($pages)) {
        return $pages[0]->ID;
    }
    
    return 0;
}

/**
 * Get tenant ID
 *
 * @return int
 */
function aqualuxe_get_tenant_id() {
    // Check if multisite is enabled
    if (is_multisite()) {
        return get_current_blog_id();
    }
    
    // Default to 1
    return 1;
}

/**
 * Get vendor ID
 *
 * @return int
 */
function aqualuxe_get_vendor_id() {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return 0;
    }
    
    // Check if WC Vendors is active
    if (class_exists('WC_Vendors')) {
        if (function_exists('wcv_get_vendor_id')) {
            return wcv_get_vendor_id();
        }
    }
    
    // Check if Dokan is active
    if (class_exists('WeDevs_Dokan')) {
        if (function_exists('dokan_get_current_user_id')) {
            return dokan_get_current_user_id();
        }
    }
    
    // Check if WCFM is active
    if (class_exists('WCFM')) {
        if (function_exists('wcfm_get_vendor_id_by_user')) {
            return wcfm_get_vendor_id_by_user(get_current_user_id());
        }
    }
    
    // Default to 0
    return 0;
}

/**
 * Check if current user is a vendor
 *
 * @return bool
 */
function aqualuxe_is_vendor() {
    return aqualuxe_get_vendor_id() > 0;
}

/**
 * Get tenant data
 *
 * @param int $tenant_id
 * @return array
 */
function aqualuxe_get_tenant_data($tenant_id = 0) {
    if ($tenant_id === 0) {
        $tenant_id = aqualuxe_get_tenant_id();
    }
    
    // Check if multisite is enabled
    if (is_multisite()) {
        $blog_details = get_blog_details($tenant_id);
        
        if ($blog_details) {
            return array(
                'id' => $tenant_id,
                'name' => $blog_details->blogname,
                'url' => $blog_details->siteurl,
                'description' => $blog_details->blogdescription,
            );
        }
    }
    
    // Default to current site
    return array(
        'id' => $tenant_id,
        'name' => get_bloginfo('name'),
        'url' => home_url(),
        'description' => get_bloginfo('description'),
    );
}

/**
 * Get vendor data
 *
 * @param int $vendor_id
 * @return array
 */
function aqualuxe_get_vendor_data($vendor_id = 0) {
    if ($vendor_id === 0) {
        $vendor_id = aqualuxe_get_vendor_id();
    }
    
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return array();
    }
    
    // Check if WC Vendors is active
    if (class_exists('WC_Vendors')) {
        if (function_exists('wcv_get_vendor_shop_name')) {
            return array(
                'id' => $vendor_id,
                'name' => wcv_get_vendor_shop_name($vendor_id),
                'url' => wcv_get_vendor_shop_page($vendor_id),
                'description' => get_user_meta($vendor_id, 'pv_shop_description', true),
            );
        }
    }
    
    // Check if Dokan is active
    if (class_exists('WeDevs_Dokan')) {
        if (function_exists('dokan_get_store_info')) {
            $store_info = dokan_get_store_info($vendor_id);
            
            return array(
                'id' => $vendor_id,
                'name' => $store_info['store_name'],
                'url' => dokan_get_store_url($vendor_id),
                'description' => $store_info['store_description'],
            );
        }
    }
    
    // Check if WCFM is active
    if (class_exists('WCFM')) {
        if (function_exists('wcfm_get_vendor_store_info')) {
            $store_info = wcfm_get_vendor_store_info($vendor_id);
            
            return array(
                'id' => $vendor_id,
                'name' => $store_info['store_name'],
                'url' => wcfmmp_get_store_url($vendor_id),
                'description' => $store_info['store_description'],
            );
        }
    }
    
    // Default to empty array
    return array();
}

/**
 * Get breadcrumb
 *
 * @return void
 */
function aqualuxe_breadcrumb() {
    // Check if WooCommerce is active
    if (aqualuxe_is_woocommerce_active() && function_exists('woocommerce_breadcrumb')) {
        woocommerce_breadcrumb();
        return;
    }
    
    // Custom breadcrumb
    $breadcrumb = array();
    
    // Add home
    $breadcrumb[] = array(
        'text' => __('Home', 'aqualuxe'),
        'url' => home_url(),
    );
    
    // Add current page
    if (is_home()) {
        $breadcrumb[] = array(
            'text' => __('Blog', 'aqualuxe'),
            'url' => '',
        );
    } elseif (is_category()) {
        $breadcrumb[] = array(
            'text' => __('Blog', 'aqualuxe'),
            'url' => get_permalink(get_option('page_for_posts')),
        );
        $breadcrumb[] = array(
            'text' => single_cat_title('', false),
            'url' => '',
        );
    } elseif (is_tag()) {
        $breadcrumb[] = array(
            'text' => __('Blog', 'aqualuxe'),
            'url' => get_permalink(get_option('page_for_posts')),
        );
        $breadcrumb[] = array(
            'text' => single_tag_title('', false),
            'url' => '',
        );
    } elseif (is_author()) {
        $breadcrumb[] = array(
            'text' => __('Blog', 'aqualuxe'),
            'url' => get_permalink(get_option('page_for_posts')),
        );
        $breadcrumb[] = array(
            'text' => get_the_author(),
            'url' => '',
        );
    } elseif (is_year()) {
        $breadcrumb[] = array(
            'text' => __('Blog', 'aqualuxe'),
            'url' => get_permalink(get_option('page_for_posts')),
        );
        $breadcrumb[] = array(
            'text' => get_the_date('Y'),
            'url' => '',
        );
    } elseif (is_month()) {
        $breadcrumb[] = array(
            'text' => __('Blog', 'aqualuxe'),
            'url' => get_permalink(get_option('page_for_posts')),
        );
        $breadcrumb[] = array(
            'text' => get_the_date('Y'),
            'url' => get_year_link(get_the_date('Y')),
        );
        $breadcrumb[] = array(
            'text' => get_the_date('F'),
            'url' => '',
        );
    } elseif (is_day()) {
        $breadcrumb[] = array(
            'text' => __('Blog', 'aqualuxe'),
            'url' => get_permalink(get_option('page_for_posts')),
        );
        $breadcrumb[] = array(
            'text' => get_the_date('Y'),
            'url' => get_year_link(get_the_date('Y')),
        );
        $breadcrumb[] = array(
            'text' => get_the_date('F'),
            'url' => get_month_link(get_the_date('Y'), get_the_date('m')),
        );
        $breadcrumb[] = array(
            'text' => get_the_date('j'),
            'url' => '',
        );
    } elseif (is_single()) {
        if (get_post_type() === 'post') {
            $breadcrumb[] = array(
                'text' => __('Blog', 'aqualuxe'),
                'url' => get_permalink(get_option('page_for_posts')),
            );
            
            $categories = get_the_category();
            if (!empty($categories)) {
                $category = $categories[0];
                $breadcrumb[] = array(
                    'text' => $category->name,
                    'url' => get_category_link($category->term_id),
                );
            }
        }
        
        $breadcrumb[] = array(
            'text' => get_the_title(),
            'url' => '',
        );
    } elseif (is_page()) {
        $ancestors = get_post_ancestors(get_the_ID());
        
        if (!empty($ancestors)) {
            $ancestors = array_reverse($ancestors);
            
            foreach ($ancestors as $ancestor) {
                $breadcrumb[] = array(
                    'text' => get_the_title($ancestor),
                    'url' => get_permalink($ancestor),
                );
            }
        }
        
        $breadcrumb[] = array(
            'text' => get_the_title(),
            'url' => '',
        );
    } elseif (is_search()) {
        $breadcrumb[] = array(
            'text' => sprintf(__('Search results for: %s', 'aqualuxe'), get_search_query()),
            'url' => '',
        );
    } elseif (is_404()) {
        $breadcrumb[] = array(
            'text' => __('Page not found', 'aqualuxe'),
            'url' => '',
        );
    }
    
    // Output breadcrumb
    echo '<nav class="aqualuxe-breadcrumb" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">';
    echo '<ol class="aqualuxe-breadcrumb-list">';
    
    foreach ($breadcrumb as $key => $item) {
        echo '<li class="aqualuxe-breadcrumb-item">';
        
        if (!empty($item['url'])) {
            echo '<a href="' . esc_url($item['url']) . '">' . esc_html($item['text']) . '</a>';
        } else {
            echo '<span>' . esc_html($item['text']) . '</span>';
        }
        
        if ($key < count($breadcrumb) - 1) {
            echo '<span class="aqualuxe-breadcrumb-separator">/</span>';
        }
        
        echo '</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
}