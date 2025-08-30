<?php
/**
 * AquaLuxe Core Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists( 'WooCommerce' );
}

/**
 * Check if a module is active
 *
 * @param string $module Module name
 * @return bool
 */
function aqualuxe_is_module_active( $module ) {
    $active_modules = get_option( 'aqualuxe_active_modules', [] );
    return isset( $active_modules[ $module ] ) && $active_modules[ $module ];
}

/**
 * Get theme instance
 *
 * @return AquaLuxe_Theme
 */
function aqualuxe_theme() {
    return AquaLuxe_Theme::instance();
}

/**
 * Get module instance
 *
 * @param string $module Module name
 * @return object|null Module instance or null if not found
 */
function aqualuxe_module( $module ) {
    return aqualuxe_theme()->get_module( $module );
}

/**
 * Get theme option
 *
 * @param string $option Option name
 * @param mixed $default Default value
 * @return mixed Option value
 */
function aqualuxe_get_option( $option, $default = null ) {
    $options = get_theme_mod( 'aqualuxe_options', [] );
    return isset( $options[ $option ] ) ? $options[ $option ] : $default;
}

/**
 * Get theme color
 *
 * @param string $color Color name
 * @param string $default Default color
 * @return string Color value
 */
function aqualuxe_get_color( $color, $default = '' ) {
    return aqualuxe_get_option( 'color_' . $color, $default );
}

/**
 * Get theme font
 *
 * @param string $font Font name
 * @param string $default Default font
 * @return string Font value
 */
function aqualuxe_get_font( $font, $default = '' ) {
    return aqualuxe_get_option( 'font_' . $font, $default );
}

/**
 * Get current language
 *
 * @return string Current language code
 */
function aqualuxe_get_current_language() {
    // Check if multilingual module is active
    if ( aqualuxe_is_module_active( 'multilingual' ) ) {
        // Get language from module
        $module = aqualuxe_module( 'multilingual' );
        if ( $module && method_exists( $module, 'get_current_language' ) ) {
            return $module->get_current_language();
        }
    }
    
    // Default to WordPress locale
    return get_locale();
}

/**
 * Check if dark mode is active
 *
 * @return bool
 */
function aqualuxe_is_dark_mode() {
    // Check if dark mode module is active
    if ( aqualuxe_is_module_active( 'dark-mode' ) ) {
        // Get dark mode status from module
        $module = aqualuxe_module( 'dark-mode' );
        if ( $module && method_exists( $module, 'is_dark_mode' ) ) {
            return $module->is_dark_mode();
        }
    }
    
    // Default to false
    return false;
}

/**
 * Sanitize HTML classes
 *
 * @param string|array $classes Classes to sanitize
 * @return string Sanitized classes
 */
function aqualuxe_sanitize_html_classes( $classes ) {
    if ( ! is_array( $classes ) ) {
        $classes = explode( ' ', $classes );
    }
    
    $classes = array_map( 'sanitize_html_class', $classes );
    return implode( ' ', $classes );
}

/**
 * Get image URL by ID
 *
 * @param int $attachment_id Attachment ID
 * @param string $size Image size
 * @return string Image URL
 */
function aqualuxe_get_image_url( $attachment_id, $size = 'full' ) {
    if ( ! $attachment_id ) {
        return '';
    }
    
    $image = wp_get_attachment_image_src( $attachment_id, $size );
    return $image ? $image[0] : '';
}

/**
 * Get SVG icon
 *
 * @param string $icon Icon name
 * @return string SVG markup
 */
function aqualuxe_get_icon( $icon ) {
    $icons = [
        'cart' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>',
        'search' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
        'user' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
        'heart' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>',
        'menu' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>',
        'close' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>',
        'chevron-down' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>',
        'chevron-up' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>',
        'chevron-left' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>',
        'chevron-right' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
        'mail' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>',
        'phone' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>',
        'map-pin' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>',
        'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
        'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>',
        'twitter' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>',
        'youtube' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>',
        'linkedin' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>',
        'sun' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>',
        'moon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>',
        'globe' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>',
    ];
    
    return isset( $icons[ $icon ] ) ? $icons[ $icon ] : '';
}

/**
 * Get currency symbol
 *
 * @param string $currency Currency code
 * @return string Currency symbol
 */
function aqualuxe_get_currency_symbol( $currency = '' ) {
    if ( ! $currency ) {
        // Get current currency
        $currency = aqualuxe_get_current_currency();
    }
    
    $symbols = [
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
    ];
    
    return isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : $currency;
}

/**
 * Get current currency
 *
 * @return string Current currency code
 */
function aqualuxe_get_current_currency() {
    // Check if WooCommerce is active
    if ( aqualuxe_is_woocommerce_active() ) {
        return get_woocommerce_currency();
    }
    
    // Default to USD
    return 'USD';
}

/**
 * Format price
 *
 * @param float $price Price
 * @param string $currency Currency code
 * @return string Formatted price
 */
function aqualuxe_format_price( $price, $currency = '' ) {
    // Check if WooCommerce is active
    if ( aqualuxe_is_woocommerce_active() ) {
        return wc_price( $price, [ 'currency' => $currency ] );
    }
    
    // Get currency symbol
    $symbol = aqualuxe_get_currency_symbol( $currency );
    
    // Format price
    return $symbol . number_format( $price, 2 );
}

/**
 * Get page URL by template
 *
 * @param string $template Template name
 * @return string Page URL
 */
function aqualuxe_get_page_url_by_template( $template ) {
    // Get pages with template
    $pages = get_pages( [
        'meta_key' => '_wp_page_template',
        'meta_value' => $template,
    ] );
    
    // Return URL of first page
    if ( ! empty( $pages ) ) {
        return get_permalink( $pages[0]->ID );
    }
    
    return '';
}

/**
 * Get social media links
 *
 * @return array Social media links
 */
function aqualuxe_get_social_links() {
    return [
        'facebook' => aqualuxe_get_option( 'social_facebook', '' ),
        'twitter' => aqualuxe_get_option( 'social_twitter', '' ),
        'instagram' => aqualuxe_get_option( 'social_instagram', '' ),
        'youtube' => aqualuxe_get_option( 'social_youtube', '' ),
        'linkedin' => aqualuxe_get_option( 'social_linkedin', '' ),
    ];
}

/**
 * Get contact information
 *
 * @return array Contact information
 */
function aqualuxe_get_contact_info() {
    return [
        'phone' => aqualuxe_get_option( 'contact_phone', '' ),
        'email' => aqualuxe_get_option( 'contact_email', '' ),
        'address' => aqualuxe_get_option( 'contact_address', '' ),
        'hours' => aqualuxe_get_option( 'contact_hours', '' ),
    ];
}

/**
 * Get languages
 *
 * @return array Languages
 */
function aqualuxe_get_languages() {
    // Check if multilingual module is active
    if ( aqualuxe_is_module_active( 'multilingual' ) ) {
        // Get languages from module
        $module = aqualuxe_module( 'multilingual' );
        if ( $module && method_exists( $module, 'get_languages' ) ) {
            return $module->get_languages();
        }
    }
    
    // Default to empty array
    return [];
}

/**
 * Get currencies
 *
 * @return array Currencies
 */
function aqualuxe_get_currencies() {
    // Check if WooCommerce is active
    if ( aqualuxe_is_woocommerce_active() ) {
        // Get currencies from WooCommerce
        return [
            'USD' => 'US Dollar',
            'EUR' => 'Euro',
            'GBP' => 'British Pound',
            'JPY' => 'Japanese Yen',
            'AUD' => 'Australian Dollar',
            'CAD' => 'Canadian Dollar',
            'CHF' => 'Swiss Franc',
            'CNY' => 'Chinese Yuan',
            'HKD' => 'Hong Kong Dollar',
            'NZD' => 'New Zealand Dollar',
        ];
    }
    
    // Default to empty array
    return [];
}