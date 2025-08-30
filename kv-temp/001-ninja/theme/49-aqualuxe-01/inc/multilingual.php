<?php
/**
 * Multilingual support for AquaLuxe theme
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Setup multilingual support
 */
function aqualuxe_multilingual_setup() {
	// Load theme textdomain
	load_theme_textdomain( 'aqualuxe', get_template_directory() . '/languages' );

	// WPML compatibility
	if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
		add_action( 'after_setup_theme', 'aqualuxe_wpml_setup' );
	}

	// Polylang compatibility
	if ( function_exists( 'pll_register_string' ) ) {
		add_action( 'after_setup_theme', 'aqualuxe_polylang_setup' );
	}
}
add_action( 'after_setup_theme', 'aqualuxe_multilingual_setup' );

/**
 * Setup WPML compatibility
 */
function aqualuxe_wpml_setup() {
	// Register strings for translation
	if ( function_exists( 'icl_register_string' ) ) {
		// Register theme mod strings
		$theme_mods_to_translate = aqualuxe_get_translatable_theme_mods();
		
		foreach ( $theme_mods_to_translate as $theme_mod ) {
			$value = get_theme_mod( $theme_mod['name'], $theme_mod['default'] );
			
			if ( $value ) {
				icl_register_string( 'Theme Mod', $theme_mod['name'], $value );
			}
		}
	}
}

/**
 * Setup Polylang compatibility
 */
function aqualuxe_polylang_setup() {
	// Register strings for translation
	$theme_mods_to_translate = aqualuxe_get_translatable_theme_mods();
	
	foreach ( $theme_mods_to_translate as $theme_mod ) {
		$value = get_theme_mod( $theme_mod['name'], $theme_mod['default'] );
		
		if ( $value ) {
			pll_register_string( $theme_mod['name'], $value, 'AquaLuxe Theme' );
		}
	}
}

/**
 * Get translatable theme mods
 *
 * @return array Array of translatable theme mods
 */
function aqualuxe_get_translatable_theme_mods() {
	return array(
		array(
			'name'    => 'aqualuxe_header_top_bar_content',
			'default' => __( 'Free shipping on orders over $100', 'aqualuxe' ),
		),
		array(
			'name'    => 'aqualuxe_copyright_text',
			'default' => sprintf( __( '© %s AquaLuxe. All rights reserved.', 'aqualuxe' ), date( 'Y' ) ),
		),
		array(
			'name'    => 'aqualuxe_newsletter_title',
			'default' => __( 'Subscribe to Our Newsletter', 'aqualuxe' ),
		),
		array(
			'name'    => 'aqualuxe_newsletter_text',
			'default' => __( 'Stay updated with our latest products and news.', 'aqualuxe' ),
		),
		array(
			'name'    => 'aqualuxe_read_more_text',
			'default' => __( 'Read More', 'aqualuxe' ),
		),
	);
}

/**
 * Translate theme mod
 *
 * @param string $theme_mod Theme mod name
 * @param string $default   Default value
 * @return string Translated value
 */
function aqualuxe_translate_theme_mod( $theme_mod, $default = '' ) {
	$value = get_theme_mod( $theme_mod, $default );
	
	// WPML translation
	if ( defined( 'ICL_SITEPRESS_VERSION' ) && function_exists( 'icl_t' ) ) {
		$value = icl_t( 'Theme Mod', $theme_mod, $value );
	}
	
	// Polylang translation
	if ( function_exists( 'pll__' ) ) {
		$value = pll__( $value );
	}
	
	return $value;
}

/**
 * Get current language
 *
 * @return string Current language code
 */
function aqualuxe_get_current_language() {
	// WPML
	if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		return ICL_LANGUAGE_CODE;
	}
	
	// Polylang
	if ( function_exists( 'pll_current_language' ) ) {
		return pll_current_language();
	}
	
	// Default
	return get_locale();
}

/**
 * Get language URL
 *
 * @param string $lang Language code
 * @return string Language URL
 */
function aqualuxe_get_language_url( $lang ) {
	// WPML
	if ( defined( 'ICL_SITEPRESS_VERSION' ) && function_exists( 'icl_get_languages' ) ) {
		$languages = icl_get_languages( 'skip_missing=0' );
		
		if ( isset( $languages[ $lang ] ) ) {
			return $languages[ $lang ]['url'];
		}
	}
	
	// Polylang
	if ( function_exists( 'pll_home_url' ) ) {
		return pll_home_url( $lang );
	}
	
	// Default
	return home_url();
}

/**
 * Get available languages
 *
 * @return array Available languages
 */
function aqualuxe_get_available_languages() {
	$languages = array();
	
	// WPML
	if ( defined( 'ICL_SITEPRESS_VERSION' ) && function_exists( 'icl_get_languages' ) ) {
		$wpml_languages = icl_get_languages( 'skip_missing=0' );
		
		if ( ! empty( $wpml_languages ) ) {
			foreach ( $wpml_languages as $code => $language ) {
				$languages[ $code ] = array(
					'code'      => $code,
					'name'      => $language['native_name'],
					'url'       => $language['url'],
					'flag'      => $language['country_flag_url'],
					'active'    => $language['active'],
				);
			}
		}
		
		return $languages;
	}
	
	// Polylang
	if ( function_exists( 'pll_languages_list' ) && function_exists( 'pll_home_url' ) ) {
		$pll_languages = pll_languages_list( array( 'fields' => 'slug' ) );
		$current_lang = pll_current_language();
		
		if ( ! empty( $pll_languages ) ) {
			foreach ( $pll_languages as $code ) {
				$languages[ $code ] = array(
					'code'      => $code,
					'name'      => pll_translate_string( $code, $code ),
					'url'       => pll_home_url( $code ),
					'flag'      => '',
					'active'    => ( $code === $current_lang ),
				);
			}
		}
		
		return $languages;
	}
	
	// Default
	$languages['en'] = array(
		'code'      => 'en',
		'name'      => 'English',
		'url'       => home_url(),
		'flag'      => '',
		'active'    => true,
	);
	
	return $languages;
}

/**
 * Filter body classes for multilingual sites
 *
 * @param array $classes Body classes
 * @return array Modified body classes
 */
function aqualuxe_multilingual_body_classes( $classes ) {
	$current_lang = aqualuxe_get_current_language();
	
	if ( $current_lang ) {
		$classes[] = 'lang-' . $current_lang;
	}
	
	return $classes;
}
add_filter( 'body_class', 'aqualuxe_multilingual_body_classes' );

/**
 * Add hreflang links to head
 */
function aqualuxe_add_hreflang_links() {
	$languages = aqualuxe_get_available_languages();
	
	if ( ! empty( $languages ) ) {
		foreach ( $languages as $lang ) {
			echo '<link rel="alternate" hreflang="' . esc_attr( $lang['code'] ) . '" href="' . esc_url( $lang['url'] ) . '" />' . "\n";
		}
	}
}
add_action( 'wp_head', 'aqualuxe_add_hreflang_links' );

/**
 * Filter WooCommerce currency based on language
 *
 * @param string $currency Currency code
 * @return string Modified currency code
 */
function aqualuxe_woocommerce_currency( $currency ) {
	// Only apply if WooCommerce is active
	if ( ! class_exists( 'WooCommerce' ) ) {
		return $currency;
	}
	
	// Get current language
	$current_lang = aqualuxe_get_current_language();
	
	// Define currency mapping
	$currency_mapping = apply_filters( 'aqualuxe_currency_mapping', array(
		'en' => 'USD',
		'fr' => 'EUR',
		'de' => 'EUR',
		'es' => 'EUR',
		'it' => 'EUR',
		'ja' => 'JPY',
		'zh' => 'CNY',
	) );
	
	// Return mapped currency if available
	if ( isset( $currency_mapping[ $current_lang ] ) ) {
		return $currency_mapping[ $current_lang ];
	}
	
	return $currency;
}

// Only apply currency filter if WCML is not active
if ( ! class_exists( 'woocommerce_wpml' ) ) {
	add_filter( 'woocommerce_currency', 'aqualuxe_woocommerce_currency' );
}