<?php
/**
 * AquaLuxe Multilingual Module Helper Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/Multilingual
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Get current language
 *
 * @return string
 */
function aqualuxe_get_current_language() {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( $module ) {
        return $module->get_current_language();
    }
    
    return get_locale();
}

/**
 * Get default language
 *
 * @return string
 */
function aqualuxe_get_default_language() {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( $module ) {
        return $module->get_default_language();
    }
    
    return get_locale();
}

/**
 * Get languages
 *
 * @return array
 */
function aqualuxe_get_languages() {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( $module ) {
        return $module->get_languages();
    }
    
    return array(
        get_locale() => array(
            'code' => get_locale(),
            'name' => get_bloginfo( 'language' ),
            'flag' => '',
            'url' => home_url(),
            'active' => true,
        ),
    );
}

/**
 * Get translated URL
 *
 * @param string $url URL
 * @param string $language Language code
 * @return string
 */
function aqualuxe_get_translated_url( $url, $language ) {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( $module ) {
        return $module->get_translated_url( $url, $language );
    }
    
    return $url;
}

/**
 * Get translated post ID
 *
 * @param int $post_id Post ID
 * @param string $language Language code
 * @return int
 */
function aqualuxe_get_translated_post_id( $post_id, $language ) {
    // Check if WPML is active
    if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
        return apply_filters( 'wpml_object_id', $post_id, get_post_type( $post_id ), true, $language );
    }
    
    // Check if Polylang is active
    if ( function_exists( 'pll_get_post' ) ) {
        return pll_get_post( $post_id, $language );
    }
    
    return $post_id;
}

/**
 * Get translated term ID
 *
 * @param int $term_id Term ID
 * @param string $taxonomy Taxonomy
 * @param string $language Language code
 * @return int
 */
function aqualuxe_get_translated_term_id( $term_id, $taxonomy, $language ) {
    // Check if WPML is active
    if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
        return apply_filters( 'wpml_object_id', $term_id, $taxonomy, true, $language );
    }
    
    // Check if Polylang is active
    if ( function_exists( 'pll_get_term' ) ) {
        return pll_get_term( $term_id, $language );
    }
    
    return $term_id;
}

/**
 * Translate string
 *
 * @param string $string String to translate
 * @param string $domain Text domain
 * @param string $name String name (for WPML)
 * @return string
 */
function aqualuxe_translate_string( $string, $domain = 'aqualuxe', $name = '' ) {
    // Check if WPML is active
    if ( defined( 'ICL_SITEPRESS_VERSION' ) && function_exists( 'icl_t' ) ) {
        return icl_t( $domain, $name ? $name : md5( $string ), $string );
    }
    
    // Check if Polylang is active
    if ( function_exists( 'pll__' ) ) {
        return pll__( $string );
    }
    
    return $string;
}

/**
 * Register string for translation
 *
 * @param string $string String to register
 * @param string $domain Text domain
 * @param string $name String name (for WPML)
 * @return void
 */
function aqualuxe_register_string( $string, $domain = 'aqualuxe', $name = '' ) {
    // Check if WPML is active
    if ( defined( 'ICL_SITEPRESS_VERSION' ) && function_exists( 'icl_register_string' ) ) {
        icl_register_string( $domain, $name ? $name : md5( $string ), $string );
    }
    
    // Check if Polylang is active
    if ( function_exists( 'pll_register_string' ) ) {
        pll_register_string( $name ? $name : md5( $string ), $string, $domain );
    }
}

/**
 * Check if current page is translated
 *
 * @return bool
 */
function aqualuxe_is_translated_page() {
    // Get current language
    $current_language = aqualuxe_get_current_language();
    
    // Get default language
    $default_language = aqualuxe_get_default_language();
    
    // If current language is default language, return true
    if ( $current_language === $default_language ) {
        return true;
    }
    
    // Check if current page is translated
    if ( is_singular() ) {
        $post_id = get_the_ID();
        $translated_id = aqualuxe_get_translated_post_id( $post_id, $current_language );
        
        return $translated_id !== $post_id;
    }
    
    // Check if current term is translated
    if ( is_tax() || is_category() || is_tag() ) {
        $term = get_queried_object();
        $translated_id = aqualuxe_get_translated_term_id( $term->term_id, $term->taxonomy, $current_language );
        
        return $translated_id !== $term->term_id;
    }
    
    return true;
}

/**
 * Get language flag URL
 *
 * @param string $language Language code
 * @return string
 */
function aqualuxe_get_language_flag_url( $language ) {
    // Get languages
    $languages = aqualuxe_get_languages();
    
    // Check if language exists
    if ( isset( $languages[ $language ] ) && ! empty( $languages[ $language ]['flag'] ) ) {
        return $languages[ $language ]['flag'];
    }
    
    // Return default flag
    return AQUALUXE_URI . 'modules/multilingual/assets/images/flags/' . $language . '.png';
}

/**
 * Get language name
 *
 * @param string $language Language code
 * @return string
 */
function aqualuxe_get_language_name( $language ) {
    // Get languages
    $languages = aqualuxe_get_languages();
    
    // Check if language exists
    if ( isset( $languages[ $language ] ) && ! empty( $languages[ $language ]['name'] ) ) {
        return $languages[ $language ]['name'];
    }
    
    return $language;
}

/**
 * Get language URL
 *
 * @param string $language Language code
 * @return string
 */
function aqualuxe_get_language_url( $language ) {
    // Get languages
    $languages = aqualuxe_get_languages();
    
    // Check if language exists
    if ( isset( $languages[ $language ] ) && ! empty( $languages[ $language ]['url'] ) ) {
        return $languages[ $language ]['url'];
    }
    
    // Get current URL
    global $wp;
    $current_url = home_url( add_query_arg( array(), $wp->request ) );
    
    // Get translated URL
    return aqualuxe_get_translated_url( $current_url, $language );
}

/**
 * Check if language is active
 *
 * @param string $language Language code
 * @return bool
 */
function aqualuxe_is_language_active( $language ) {
    // Get languages
    $languages = aqualuxe_get_languages();
    
    // Check if language exists
    if ( isset( $languages[ $language ] ) && isset( $languages[ $language ]['active'] ) ) {
        return $languages[ $language ]['active'];
    }
    
    return $language === aqualuxe_get_current_language();
}