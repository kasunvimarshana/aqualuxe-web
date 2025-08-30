<?php
/**
 * Multilingual Helper Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/Multilingual
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Get current language
 *
 * @return array Current language data
 */
function aqualuxe_get_current_language() {
    $multilingual_module = aqualuxe_get_module( 'multilingual' );
    
    if ( $multilingual_module ) {
        return $multilingual_module->get_current_language();
    }
    
    // Default fallback
    return array(
        'name' => __( 'English (US)', 'aqualuxe' ),
        'native_name' => 'English (US)',
        'flag' => 'us',
        'locale' => 'en_US',
        'is_rtl' => false,
    );
}

/**
 * Get current language code
 *
 * @return string Language code
 */
function aqualuxe_get_current_language_code() {
    $current_language = aqualuxe_get_current_language();
    
    if ( isset( $current_language['locale'] ) ) {
        return $current_language['locale'];
    }
    
    return 'en_US';
}

/**
 * Get enabled languages
 *
 * @return array Enabled languages
 */
function aqualuxe_get_enabled_languages() {
    $multilingual_module = aqualuxe_get_module( 'multilingual' );
    
    if ( $multilingual_module ) {
        return $multilingual_module->get_enabled_languages();
    }
    
    return array();
}

/**
 * Check if current language is RTL
 *
 * @return bool
 */
function aqualuxe_is_rtl_language() {
    $multilingual_module = aqualuxe_get_module( 'multilingual' );
    
    if ( $multilingual_module ) {
        return $multilingual_module->is_rtl();
    }
    
    return false;
}

/**
 * Get language URL
 *
 * @param string $locale Language locale.
 * @return string
 */
function aqualuxe_get_language_url( $locale ) {
    $multilingual_module = aqualuxe_get_module( 'multilingual' );
    
    if ( $multilingual_module ) {
        return $multilingual_module->get_language_url( $locale );
    }
    
    return add_query_arg( 'lang', $locale );
}

/**
 * Get language switcher HTML
 *
 * @return string
 */
function aqualuxe_get_language_switcher() {
    ob_start();
    
    $multilingual_module = aqualuxe_get_module( 'multilingual' );
    
    if ( $multilingual_module ) {
        $multilingual_module->render_language_switcher();
    }
    
    return ob_get_clean();
}

/**
 * Output language switcher
 *
 * @return void
 */
function aqualuxe_language_switcher() {
    echo aqualuxe_get_language_switcher();
}

/**
 * Register AJAX handlers for language switcher
 */
function aqualuxe_multilingual_register_ajax_handlers() {
    // Save user language preference
    add_action( 'wp_ajax_aqualuxe_save_language_preference', 'aqualuxe_ajax_save_language_preference' );
    add_action( 'wp_ajax_nopriv_aqualuxe_save_language_preference', 'aqualuxe_ajax_save_language_preference' );
}
add_action( 'init', 'aqualuxe_multilingual_register_ajax_handlers' );

/**
 * AJAX handler for saving language preference
 */
function aqualuxe_ajax_save_language_preference() {
    // Verify nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_language_nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed', 'aqualuxe' ) ) );
    }

    // Get language
    $language = isset( $_POST['language'] ) ? sanitize_text_field( wp_unslash( $_POST['language'] ) ) : 'en_US';
    
    // Validate language
    $multilingual_module = aqualuxe_get_module( 'multilingual' );
    $enabled_languages = $multilingual_module ? $multilingual_module->get_enabled_languages() : array();
    
    if ( ! isset( $enabled_languages[ $language ] ) ) {
        wp_send_json_error( array( 'message' => __( 'Invalid language', 'aqualuxe' ) ) );
    }
    
    // Set cookie for 30 days
    setcookie( 'aqualuxe_language', $language, time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
    
    wp_send_json_success( array( 
        'message' => __( 'Language preference saved', 'aqualuxe' ),
        'language' => $language,
        'url' => aqualuxe_get_language_url( $language ),
    ) );
}

/**
 * Get translated string
 *
 * @param string $text Text to translate.
 * @param string $domain Text domain.
 * @return string
 */
function aqualuxe_translate( $text, $domain = 'aqualuxe' ) {
    // If WPML is active, use their translation function
    if ( function_exists( 'icl_translate' ) ) {
        return icl_translate( $domain, md5( $text ), $text );
    }
    
    // If Polylang is active, use their translation function
    if ( function_exists( 'pll__' ) ) {
        return pll__( $text );
    }
    
    // Default WordPress translation
    return __( $text, $domain );
}

/**
 * Register a string for translation
 *
 * @param string $string String to register.
 * @param string $name String name.
 * @param string $domain Text domain.
 * @return void
 */
function aqualuxe_register_string( $string, $name, $domain = 'aqualuxe' ) {
    // If WPML is active, use their string registration function
    if ( function_exists( 'icl_register_string' ) ) {
        icl_register_string( $domain, $name, $string );
    }
    
    // If Polylang is active, use their string registration function
    if ( function_exists( 'pll_register_string' ) ) {
        pll_register_string( $name, $string, $domain );
    }
}

/**
 * Get language flag URL
 *
 * @param string $code Language code.
 * @return string
 */
function aqualuxe_get_language_flag_url( $code ) {
    $flag_code = strtolower( substr( $code, -2 ) );
    
    return AQUALUXE_THEME_URI . 'modules/multilingual/assets/flags/' . $flag_code . '.png';
}

/**
 * Get language name
 *
 * @param string $locale Language locale.
 * @param bool   $native Whether to return native name.
 * @return string
 */
function aqualuxe_get_language_name( $locale, $native = false ) {
    $multilingual_module = aqualuxe_get_module( 'multilingual' );
    $enabled_languages = $multilingual_module ? $multilingual_module->get_enabled_languages() : array();
    
    if ( isset( $enabled_languages[ $locale ] ) ) {
        return $native ? $enabled_languages[ $locale ]['native_name'] : $enabled_languages[ $locale ]['name'];
    }
    
    return $locale;
}

/**
 * Check if a language is enabled
 *
 * @param string $locale Language locale.
 * @return bool
 */
function aqualuxe_is_language_enabled( $locale ) {
    $multilingual_module = aqualuxe_get_module( 'multilingual' );
    $enabled_languages = $multilingual_module ? $multilingual_module->get_enabled_languages() : array();
    
    return isset( $enabled_languages[ $locale ] );
}

/**
 * Get language direction
 *
 * @param string $locale Language locale.
 * @return string 'rtl' or 'ltr'
 */
function aqualuxe_get_language_direction( $locale = '' ) {
    if ( empty( $locale ) ) {
        return aqualuxe_is_rtl_language() ? 'rtl' : 'ltr';
    }
    
    $multilingual_module = aqualuxe_get_module( 'multilingual' );
    $enabled_languages = $multilingual_module ? $multilingual_module->get_enabled_languages() : array();
    
    if ( isset( $enabled_languages[ $locale ] ) && isset( $enabled_languages[ $locale ]['is_rtl'] ) && $enabled_languages[ $locale ]['is_rtl'] ) {
        return 'rtl';
    }
    
    return 'ltr';
}

/**
 * Language switcher shortcode
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_language_switcher_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'style' => '',
            'flags' => '',
        ),
        $atts,
        'language_switcher'
    );
    
    $multilingual_module = aqualuxe_get_module( 'multilingual' );
    
    if ( ! $multilingual_module ) {
        return '';
    }
    
    // Override module settings if specified in shortcode
    if ( ! empty( $atts['style'] ) ) {
        $multilingual_module->settings['switcher_style'] = $atts['style'];
    }
    
    if ( ! empty( $atts['flags'] ) ) {
        $multilingual_module->settings['show_flags'] = filter_var( $atts['flags'], FILTER_VALIDATE_BOOLEAN );
    }
    
    ob_start();
    $multilingual_module->render_language_switcher();
    return ob_get_clean();
}
add_shortcode( 'language_switcher', 'aqualuxe_language_switcher_shortcode' );