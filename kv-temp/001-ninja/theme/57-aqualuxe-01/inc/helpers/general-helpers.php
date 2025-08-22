<?php
/**
 * General helper functions
 *
 * @package AquaLuxe
 */

/**
 * Check if dark mode is enabled
 *
 * @return bool Whether dark mode is enabled
 */
function aqualuxe_is_dark_mode() {
    // Check user preference cookie first
    if (isset($_COOKIE['aqualuxe_dark_mode'])) {
        return $_COOKIE['aqualuxe_dark_mode'] === 'true';
    }

    // Fall back to theme option
    $default_mode = get_theme_mod('aqualuxe_default_color_scheme', 'light');
    return $default_mode === 'dark';
}

/**
 * Get header style
 *
 * @return string Header style
 */
function aqualuxe_get_header_style() {
    $header_style = get_theme_mod('aqualuxe_header_style', 'default');
    return apply_filters('aqualuxe_header_style', $header_style);
}

/**
 * Get footer style
 *
 * @return string Footer style
 */
function aqualuxe_get_footer_style() {
    $footer_style = get_theme_mod('aqualuxe_footer_style', 'default');
    return apply_filters('aqualuxe_footer_style', $footer_style);
}

/**
 * Get theme color scheme
 *
 * @return array Color scheme
 */
function aqualuxe_get_color_scheme() {
    $default_color_scheme = array(
        'primary' => '#0073aa',
        'secondary' => '#005177',
        'dark_blue' => '#1e3a8a',
        'light_blue' => '#bfdbfe',
        'teal' => '#14b8a6',
        'dark' => '#111827',
        'light' => '#f9fafb',
        'white' => '#ffffff',
    );

    $color_scheme = array(
        'primary' => get_theme_mod('aqualuxe_primary_color', $default_color_scheme['primary']),
        'secondary' => get_theme_mod('aqualuxe_secondary_color', $default_color_scheme['secondary']),
        'dark_blue' => get_theme_mod('aqualuxe_dark_blue_color', $default_color_scheme['dark_blue']),
        'light_blue' => get_theme_mod('aqualuxe_light_blue_color', $default_color_scheme['light_blue']),
        'teal' => get_theme_mod('aqualuxe_teal_color', $default_color_scheme['teal']),
        'dark' => get_theme_mod('aqualuxe_dark_color', $default_color_scheme['dark']),
        'light' => get_theme_mod('aqualuxe_light_color', $default_color_scheme['light']),
        'white' => get_theme_mod('aqualuxe_white_color', $default_color_scheme['white']),
    );

    return apply_filters('aqualuxe_color_scheme', $color_scheme);
}

/**
 * Get theme typography settings
 *
 * @return array Typography settings
 */
function aqualuxe_get_typography() {
    $default_typography = array(
        'body_font' => 'Inter, sans-serif',
        'heading_font' => 'Playfair Display, serif',
        'base_font_size' => '16px',
        'heading_font_weight' => '700',
        'body_line_height' => '1.6',
        'heading_line_height' => '1.2',
    );

    $typography = array(
        'body_font' => get_theme_mod('aqualuxe_body_font', $default_typography['body_font']),
        'heading_font' => get_theme_mod('aqualuxe_heading_font', $default_typography['heading_font']),
        'base_font_size' => get_theme_mod('aqualuxe_base_font_size', $default_typography['base_font_size']),
        'heading_font_weight' => get_theme_mod('aqualuxe_heading_font_weight', $default_typography['heading_font_weight']),
        'body_line_height' => get_theme_mod('aqualuxe_body_line_height', $default_typography['body_line_height']),
        'heading_line_height' => get_theme_mod('aqualuxe_heading_line_height', $default_typography['heading_line_height']),
    );

    return apply_filters('aqualuxe_typography', $typography);
}

/**
 * Check if WooCommerce is active
 *
 * @return bool Whether WooCommerce is active
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Get currency symbol
 *
 * @return string Currency symbol
 */
function aqualuxe_get_currency_symbol() {
    if (aqualuxe_is_woocommerce_active()) {
        return get_woocommerce_currency_symbol();
    }
    
    return '$';
}

/**
 * Sanitize HTML classes
 *
 * @param string|array $classes Classes to sanitize
 * @return string Sanitized classes
 */
function aqualuxe_sanitize_html_classes($classes) {
    if (is_array($classes)) {
        $classes = array_map('sanitize_html_class', $classes);
        return implode(' ', $classes);
    }
    
    $classes = explode(' ', $classes);
    $classes = array_map('sanitize_html_class', $classes);
    return implode(' ', $classes);
}

/**
 * Get social media links
 *
 * @return array Social media links
 */
function aqualuxe_get_social_links() {
    $social_links = array(
        'facebook' => get_theme_mod('aqualuxe_facebook_url', ''),
        'twitter' => get_theme_mod('aqualuxe_twitter_url', ''),
        'instagram' => get_theme_mod('aqualuxe_instagram_url', ''),
        'youtube' => get_theme_mod('aqualuxe_youtube_url', ''),
        'linkedin' => get_theme_mod('aqualuxe_linkedin_url', ''),
        'pinterest' => get_theme_mod('aqualuxe_pinterest_url', ''),
    );

    return array_filter($social_links);
}

/**
 * Get contact information
 *
 * @return array Contact information
 */
function aqualuxe_get_contact_info() {
    $contact_info = array(
        'phone' => get_theme_mod('aqualuxe_phone', ''),
        'email' => get_theme_mod('aqualuxe_email', ''),
        'address' => get_theme_mod('aqualuxe_address', ''),
        'hours' => get_theme_mod('aqualuxe_hours', ''),
    );

    return array_filter($contact_info);
}

/**
 * Get Google Maps API key
 *
 * @return string Google Maps API key
 */
function aqualuxe_get_google_maps_api_key() {
    return get_theme_mod('aqualuxe_google_maps_api_key', '');
}

/**
 * Get page layout
 *
 * @return string Page layout
 */
function aqualuxe_get_page_layout() {
    $default_layout = get_theme_mod('aqualuxe_default_layout', 'right-sidebar');
    
    // Check for individual page/post setting
    if (is_singular()) {
        $layout = get_post_meta(get_the_ID(), '_aqualuxe_page_layout', true);
        if ($layout && $layout !== 'default') {
            return $layout;
        }
    }
    
    // Check for archive settings
    if (is_archive() || is_home()) {
        $archive_layout = get_theme_mod('aqualuxe_archive_layout', $default_layout);
        return $archive_layout;
    }
    
    // WooCommerce layouts
    if (aqualuxe_is_woocommerce_active()) {
        if (is_shop() || is_product_category() || is_product_tag()) {
            return get_theme_mod('aqualuxe_shop_layout', $default_layout);
        }
        
        if (is_product()) {
            return get_theme_mod('aqualuxe_product_layout', $default_layout);
        }
    }
    
    return $default_layout;
}

/**
 * Check if sidebar should be displayed
 *
 * @return bool Whether sidebar should be displayed
 */
function aqualuxe_display_sidebar() {
    $layout = aqualuxe_get_page_layout();
    return $layout === 'left-sidebar' || $layout === 'right-sidebar';
}

/**
 * Get sidebar position
 *
 * @return string Sidebar position
 */
function aqualuxe_get_sidebar_position() {
    $layout = aqualuxe_get_page_layout();
    return $layout === 'left-sidebar' ? 'left' : 'right';
}