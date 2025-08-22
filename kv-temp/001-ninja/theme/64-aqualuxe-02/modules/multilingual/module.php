<?php
/**
 * AquaLuxe Multilingual Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * AquaLuxe Multilingual Module Class
 */
class AquaLuxe_Module_Multilingual {
    /**
     * Constructor
     */
    public function __construct() {
        // Add language switcher to header
        add_action( 'aqualuxe_header_top_bar_right', [ $this, 'language_switcher' ] );
        
        // Add language class to body
        add_filter( 'body_class', [ $this, 'body_class' ] );
        
        // Add language to HTML tag
        add_filter( 'language_attributes', [ $this, 'language_attributes' ] );
        
        // Register scripts and styles
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        
        // Add AJAX handler
        add_action( 'wp_ajax_aqualuxe_switch_language', [ $this, 'switch_language_ajax' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_switch_language', [ $this, 'switch_language_ajax' ] );
    }

    /**
     * Get current language
     *
     * @return string Current language code
     */
    public function get_current_language() {
        // Check if WPML is active
        if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
            return ICL_LANGUAGE_CODE;
        }
        
        // Check if Polylang is active
        if ( function_exists( 'pll_current_language' ) ) {
            return pll_current_language();
        }
        
        // Check if TranslatePress is active
        if ( function_exists( 'trp_get_languages' ) ) {
            global $TRP_LANGUAGE;
            return $TRP_LANGUAGE;
        }
        
        // Check if cookie is set
        if ( isset( $_COOKIE['aqualuxe_language'] ) ) {
            return sanitize_text_field( $_COOKIE['aqualuxe_language'] );
        }
        
        // Default to WordPress locale
        $locale = get_locale();
        return substr( $locale, 0, 2 );
    }

    /**
     * Get languages
     *
     * @return array Languages
     */
    public function get_languages() {
        // Check if WPML is active
        if ( defined( 'ICL_LANGUAGE_CODE' ) && function_exists( 'icl_get_languages' ) ) {
            $wpml_languages = icl_get_languages( 'skip_missing=0' );
            $languages = [];
            
            foreach ( $wpml_languages as $code => $language ) {
                $languages[ $code ] = $language['native_name'];
            }
            
            return $languages;
        }
        
        // Check if Polylang is active
        if ( function_exists( 'pll_languages_list' ) && function_exists( 'pll_languages_list' ) ) {
            $pll_languages = pll_languages_list( [ 'fields' => 'slug' ] );
            $pll_names = pll_languages_list( [ 'fields' => 'name' ] );
            $languages = [];
            
            foreach ( $pll_languages as $key => $code ) {
                $languages[ $code ] = $pll_names[ $key ];
            }
            
            return $languages;
        }
        
        // Check if TranslatePress is active
        if ( function_exists( 'trp_get_languages' ) ) {
            $trp_languages = trp_get_languages();
            $languages = [];
            
            foreach ( $trp_languages as $code => $name ) {
                $languages[ $code ] = $name;
            }
            
            return $languages;
        }
        
        // Default languages
        return [
            'en' => 'English',
            'fr' => 'Français',
            'de' => 'Deutsch',
            'es' => 'Español',
            'it' => 'Italiano',
        ];
    }

    /**
     * Get language URL
     *
     * @param string $language Language code
     * @return string Language URL
     */
    public function get_language_url( $language ) {
        // Check if WPML is active
        if ( defined( 'ICL_LANGUAGE_CODE' ) && function_exists( 'icl_get_languages' ) ) {
            $wpml_languages = icl_get_languages( 'skip_missing=0' );
            
            if ( isset( $wpml_languages[ $language ] ) ) {
                return $wpml_languages[ $language ]['url'];
            }
        }
        
        // Check if Polylang is active
        if ( function_exists( 'pll_home_url' ) ) {
            return pll_home_url( $language );
        }
        
        // Check if TranslatePress is active
        if ( function_exists( 'trp_get_url_for_language' ) ) {
            return trp_get_url_for_language( $language, false );
        }
        
        // Default to adding query parameter
        return add_query_arg( 'lang', $language );
    }

    /**
     * Language switcher
     */
    public function language_switcher() {
        // Get languages
        $languages = $this->get_languages();
        
        if ( empty( $languages ) ) {
            return;
        }
        
        // Get current language
        $current_language = $this->get_current_language();
        
        // Output language switcher
        ?>
        <div class="language-switcher">
            <button class="language-switcher-toggle" aria-expanded="false">
                <?php echo aqualuxe_get_icon( 'globe' ); ?>
                <span class="language-switcher-current"><?php echo esc_html( $current_language ); ?></span>
                <?php echo aqualuxe_get_icon( 'chevron-down' ); ?>
            </button>
            <ul class="language-switcher-dropdown">
                <?php foreach ( $languages as $code => $name ) : ?>
                    <li class="language-switcher-item<?php echo $code === $current_language ? ' is-active' : ''; ?>">
                        <a href="<?php echo esc_url( $this->get_language_url( $code ) ); ?>" data-language="<?php echo esc_attr( $code ); ?>">
                            <?php echo esc_html( $name ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }

    /**
     * Add language class to body
     *
     * @param array $classes Body classes
     * @return array Modified body classes
     */
    public function body_class( $classes ) {
        $classes[] = 'lang-' . sanitize_html_class( $this->get_current_language() );
        return $classes;
    }

    /**
     * Add language to HTML tag
     *
     * @param string $output Language attributes
     * @return string Modified language attributes
     */
    public function language_attributes( $output ) {
        $language = $this->get_current_language();
        
        if ( ! empty( $language ) ) {
            $output = preg_replace( '/lang="[^"]*"/', 'lang="' . esc_attr( $language ) . '"', $output );
        }
        
        return $output;
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        // Get the asset manager instance
        $assets = AquaLuxe_Assets::instance();
        
        // Enqueue script
        $assets->enqueue_script( 'aqualuxe-multilingual', 'js/multilingual.js', [ 'jquery' ], true );
        
        // Localize script
        wp_localize_script(
            'aqualuxe-multilingual',
            'aqualuxeMultilingual',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'aqualuxe-multilingual' ),
                'currentLanguage' => $this->get_current_language(),
            ]
        );
    }

    /**
     * Switch language AJAX
     */
    public function switch_language_ajax() {
        // Check nonce
        if ( ! check_ajax_referer( 'aqualuxe-multilingual', 'nonce', false ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ] );
        }
        
        // Get language
        $language = isset( $_POST['language'] ) ? sanitize_text_field( $_POST['language'] ) : '';
        
        if ( empty( $language ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid language.', 'aqualuxe' ) ] );
        }
        
        // Set cookie
        setcookie( 'aqualuxe_language', $language, time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
        
        // Get language URL
        $url = $this->get_language_url( $language );
        
        wp_send_json_success( [ 'url' => $url ] );
    }

    /**
     * Check if dark mode is active
     *
     * @return bool
     */
    public function is_dark_mode() {
        return false;
    }
}