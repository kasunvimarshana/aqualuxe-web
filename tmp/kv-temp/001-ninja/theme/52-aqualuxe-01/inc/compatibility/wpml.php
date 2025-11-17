<?php
/**
 * WPML Compatibility File
 *
 * @package AquaLuxe
 */

/**
 * Make theme WPML compatible
 */
class AquaLuxe_WPML {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_WPML
     */
    private static $instance;

    /**
     * Get the singleton instance of this class
     *
     * @return AquaLuxe_WPML
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Initialize WPML compatibility
        $this->init();
    }

    /**
     * Initialize WPML compatibility
     */
    private function init() {
        // Add language switcher to header
        add_action( 'aqualuxe_header_actions', array( $this, 'add_language_switcher' ), 20 );
        
        // Add language switcher to mobile menu
        add_action( 'aqualuxe_mobile_menu_after', array( $this, 'add_language_switcher_mobile' ) );
        
        // Add language switcher to footer
        add_action( 'aqualuxe_footer_before', array( $this, 'add_language_switcher_footer' ) );
        
        // Filter translatable theme mods
        add_filter( 'option_theme_mods_aqualuxe', array( $this, 'translate_theme_mods' ) );
        
        // Register strings for translation
        add_action( 'after_setup_theme', array( $this, 'register_strings' ) );
        
        // Filter WooCommerce endpoints
        if ( class_exists( 'WooCommerce' ) ) {
            add_filter( 'woocommerce_get_endpoint_url', array( $this, 'translate_wc_endpoints' ), 10, 4 );
        }
    }

    /**
     * Add language switcher to header
     */
    public function add_language_switcher() {
        if ( function_exists( 'icl_get_languages' ) ) {
            $languages = icl_get_languages( 'skip_missing=0' );
            
            if ( ! empty( $languages ) ) {
                echo '<div class="header-language-switcher">';
                echo '<div class="language-switcher-wrapper">';
                
                // Current language
                $current_lang = '';
                foreach ( $languages as $lang ) {
                    if ( $lang['active'] ) {
                        $current_lang = $lang;
                        break;
                    }
                }
                
                if ( $current_lang ) {
                    echo '<button class="language-switcher-toggle" aria-expanded="false">';
                    if ( $current_lang['country_flag_url'] ) {
                        echo '<img src="' . esc_url( $current_lang['country_flag_url'] ) . '" alt="' . esc_attr( $current_lang['language_code'] ) . '" width="18" height="12">';
                    }
                    echo '<span>' . esc_html( $current_lang['language_code'] ) . '</span>';
                    echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
                    echo '</button>';
                }
                
                // Language dropdown
                echo '<ul class="language-switcher-dropdown">';
                foreach ( $languages as $lang ) {
                    $class = $lang['active'] ? 'active' : '';
                    echo '<li class="' . esc_attr( $class ) . '">';
                    echo '<a href="' . esc_url( $lang['url'] ) . '">';
                    if ( $lang['country_flag_url'] ) {
                        echo '<img src="' . esc_url( $lang['country_flag_url'] ) . '" alt="' . esc_attr( $lang['language_code'] ) . '" width="18" height="12">';
                    }
                    echo '<span>' . esc_html( $lang['native_name'] ) . '</span>';
                    echo '</a>';
                    echo '</li>';
                }
                echo '</ul>';
                
                echo '</div>';
                echo '</div>';
            }
        }
    }

    /**
     * Add language switcher to mobile menu
     */
    public function add_language_switcher_mobile() {
        if ( function_exists( 'icl_get_languages' ) ) {
            $languages = icl_get_languages( 'skip_missing=0' );
            
            if ( ! empty( $languages ) ) {
                echo '<div class="mobile-language-switcher">';
                echo '<h3>' . esc_html__( 'Languages', 'aqualuxe' ) . '</h3>';
                echo '<ul class="mobile-languages">';
                
                foreach ( $languages as $lang ) {
                    $class = $lang['active'] ? 'active' : '';
                    echo '<li class="' . esc_attr( $class ) . '">';
                    echo '<a href="' . esc_url( $lang['url'] ) . '">';
                    if ( $lang['country_flag_url'] ) {
                        echo '<img src="' . esc_url( $lang['country_flag_url'] ) . '" alt="' . esc_attr( $lang['language_code'] ) . '" width="18" height="12">';
                    }
                    echo '<span>' . esc_html( $lang['native_name'] ) . '</span>';
                    echo '</a>';
                    echo '</li>';
                }
                
                echo '</ul>';
                echo '</div>';
            }
        }
    }

    /**
     * Add language switcher to footer
     */
    public function add_language_switcher_footer() {
        if ( function_exists( 'icl_get_languages' ) && get_theme_mod( 'aqualuxe_footer_language_switcher', true ) ) {
            $languages = icl_get_languages( 'skip_missing=0' );
            
            if ( ! empty( $languages ) ) {
                echo '<div class="footer-language-switcher">';
                echo '<ul class="footer-languages">';
                
                foreach ( $languages as $lang ) {
                    $class = $lang['active'] ? 'active' : '';
                    echo '<li class="' . esc_attr( $class ) . '">';
                    echo '<a href="' . esc_url( $lang['url'] ) . '">';
                    if ( $lang['country_flag_url'] ) {
                        echo '<img src="' . esc_url( $lang['country_flag_url'] ) . '" alt="' . esc_attr( $lang['language_code'] ) . '" width="18" height="12">';
                    }
                    echo '<span>' . esc_html( $lang['native_name'] ) . '</span>';
                    echo '</a>';
                    echo '</li>';
                }
                
                echo '</ul>';
                echo '</div>';
            }
        }
    }

    /**
     * Translate theme mods
     *
     * @param array $mods Theme mods.
     * @return array
     */
    public function translate_theme_mods( $mods ) {
        if ( ! function_exists( 'icl_t' ) ) {
            return $mods;
        }
        
        // List of theme mods to translate
        $translate_mods = array(
            'aqualuxe_top_bar_content',
            'aqualuxe_footer_copyright',
        );
        
        foreach ( $translate_mods as $mod ) {
            if ( isset( $mods[ $mod ] ) ) {
                $mods[ $mod ] = icl_t( 'Theme Mod', $mod, $mods[ $mod ] );
            }
        }
        
        return $mods;
    }

    /**
     * Register strings for translation
     */
    public function register_strings() {
        if ( ! function_exists( 'icl_register_string' ) ) {
            return;
        }
        
        // Register theme mods for translation
        $theme_mods = array(
            'aqualuxe_top_bar_content' => get_theme_mod( 'aqualuxe_top_bar_content', __( 'Free shipping on all orders over $50', 'aqualuxe' ) ),
            'aqualuxe_footer_copyright' => get_theme_mod( 'aqualuxe_footer_copyright', __( '© 2025 AquaLuxe. All rights reserved.', 'aqualuxe' ) ),
        );
        
        foreach ( $theme_mods as $mod => $value ) {
            icl_register_string( 'Theme Mod', $mod, $value );
        }
    }

    /**
     * Translate WooCommerce endpoints
     *
     * @param string $url      Endpoint URL.
     * @param string $endpoint Endpoint.
     * @param string $value    Value.
     * @param string $permalink Permalink.
     * @return string
     */
    public function translate_wc_endpoints( $url, $endpoint, $value, $permalink ) {
        if ( ! function_exists( 'icl_object_id' ) || ! defined( 'ICL_LANGUAGE_CODE' ) ) {
            return $url;
        }
        
        // Get endpoint translations
        global $woocommerce_wpml;
        
        if ( ! is_object( $woocommerce_wpml ) || ! property_exists( $woocommerce_wpml, 'endpoints' ) ) {
            return $url;
        }
        
        if ( isset( $woocommerce_wpml->endpoints->endpoints_strings[ ICL_LANGUAGE_CODE ][ $endpoint ] ) ) {
            $endpoint_translation = $woocommerce_wpml->endpoints->endpoints_strings[ ICL_LANGUAGE_CODE ][ $endpoint ];
            $url = str_replace( $endpoint, $endpoint_translation, $url );
        }
        
        return $url;
    }
}

// Initialize WPML compatibility
AquaLuxe_WPML::get_instance();