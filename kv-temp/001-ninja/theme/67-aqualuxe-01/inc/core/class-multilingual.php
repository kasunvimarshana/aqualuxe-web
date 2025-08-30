<?php
/**
 * Multilingual Class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

/**
 * Multilingual Class
 * 
 * This class handles multilingual support for the theme.
 */
class Multilingual {
    /**
     * Instance of this class
     *
     * @var Multilingual
     */
    private static $instance = null;

    /**
     * Current language
     *
     * @var string
     */
    private $current_language = '';

    /**
     * Available languages
     *
     * @var array
     */
    private $available_languages = [];

    /**
     * Get the singleton instance
     *
     * @return Multilingual
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
        $this->init_hooks();
        $this->setup_languages();
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Add language switcher to header
        add_action( 'aqualuxe_header_top', [ $this, 'add_language_switcher' ], 20 );
        
        // Add language switcher to footer
        add_action( 'aqualuxe_footer_bottom', [ $this, 'add_language_switcher' ], 20 );
        
        // Add language class to body
        add_filter( 'body_class', [ $this, 'add_language_class' ] );
        
        // Filter menu locations based on language
        add_filter( 'theme_mod_nav_menu_locations', [ $this, 'filter_nav_menu_locations' ] );
        
        // Filter customizer options based on language
        add_filter( 'theme_mod_aqualuxe_footer_copyright', [ $this, 'filter_footer_copyright' ] );
        
        // Filter blog page title based on language
        add_filter( 'theme_mod_aqualuxe_blog_title', [ $this, 'filter_blog_title' ] );
        
        // Filter blog page subtitle based on language
        add_filter( 'theme_mod_aqualuxe_blog_subtitle', [ $this, 'filter_blog_subtitle' ] );
        
        // Filter read more text based on language
        add_filter( 'theme_mod_aqualuxe_blog_read_more_text', [ $this, 'filter_read_more_text' ] );
        
        // Filter WooCommerce strings based on language
        if ( class_exists( 'WooCommerce' ) ) {
            add_filter( 'woocommerce_product_add_to_cart_text', [ $this, 'filter_add_to_cart_text' ] );
            add_filter( 'woocommerce_product_single_add_to_cart_text', [ $this, 'filter_add_to_cart_text' ] );
            add_filter( 'woocommerce_checkout_place_order_text', [ $this, 'filter_place_order_text' ] );
        }
    }

    /**
     * Setup languages
     *
     * @return void
     */
    private function setup_languages() {
        // Get available languages
        if ( function_exists( 'pll_languages_list' ) ) {
            // Polylang
            $this->available_languages = pll_languages_list( [ 'fields' => 'slug' ] );
            $this->current_language = pll_current_language();
        } elseif ( function_exists( 'icl_get_languages' ) ) {
            // WPML
            $languages = icl_get_languages( 'skip_missing=0' );
            $this->available_languages = array_keys( $languages );
            $this->current_language = ICL_LANGUAGE_CODE;
        } else {
            // Default
            $this->available_languages = [ get_locale() ];
            $this->current_language = get_locale();
        }
    }

    /**
     * Add language switcher
     *
     * @return void
     */
    public function add_language_switcher() {
        // Only show if we have multiple languages
        if ( count( $this->available_languages ) <= 1 ) {
            return;
        }
        
        // Check if we should show the language switcher
        $show_in_header = get_theme_mod( 'aqualuxe_language_switcher_header', true );
        $show_in_footer = get_theme_mod( 'aqualuxe_language_switcher_footer', true );
        
        if ( ( current_action() === 'aqualuxe_header_top' && ! $show_in_header ) || 
             ( current_action() === 'aqualuxe_footer_bottom' && ! $show_in_footer ) ) {
            return;
        }
        
        // Get language switcher style
        $style = get_theme_mod( 'aqualuxe_language_switcher_style', 'dropdown' );
        
        // Start output
        echo '<div class="language-switcher language-switcher-' . esc_attr( $style ) . '">';
        
        if ( function_exists( 'pll_the_languages' ) ) {
            // Polylang
            $args = [
                'dropdown'   => $style === 'dropdown',
                'show_flags' => get_theme_mod( 'aqualuxe_language_switcher_flags', true ),
                'show_names' => get_theme_mod( 'aqualuxe_language_switcher_names', true ),
            ];
            
            pll_the_languages( $args );
        } elseif ( function_exists( 'icl_get_languages' ) ) {
            // WPML
            $languages = icl_get_languages( 'skip_missing=0' );
            
            if ( $style === 'dropdown' ) {
                echo '<select class="language-switcher-select" onchange="location = this.value;">';
                
                foreach ( $languages as $language ) {
                    $selected = $language['active'] ? ' selected="selected"' : '';
                    echo '<option value="' . esc_url( $language['url'] ) . '"' . $selected . '>';
                    
                    if ( get_theme_mod( 'aqualuxe_language_switcher_flags', true ) ) {
                        echo '<img src="' . esc_url( $language['country_flag_url'] ) . '" alt="' . esc_attr( $language['language_code'] ) . '" /> ';
                    }
                    
                    if ( get_theme_mod( 'aqualuxe_language_switcher_names', true ) ) {
                        echo esc_html( $language['native_name'] );
                    } else {
                        echo esc_html( strtoupper( $language['language_code'] ) );
                    }
                    
                    echo '</option>';
                }
                
                echo '</select>';
            } else {
                echo '<ul class="language-switcher-list">';
                
                foreach ( $languages as $language ) {
                    $active = $language['active'] ? ' class="active"' : '';
                    echo '<li' . $active . '>';
                    echo '<a href="' . esc_url( $language['url'] ) . '">';
                    
                    if ( get_theme_mod( 'aqualuxe_language_switcher_flags', true ) ) {
                        echo '<img src="' . esc_url( $language['country_flag_url'] ) . '" alt="' . esc_attr( $language['language_code'] ) . '" /> ';
                    }
                    
                    if ( get_theme_mod( 'aqualuxe_language_switcher_names', true ) ) {
                        echo esc_html( $language['native_name'] );
                    } else {
                        echo esc_html( strtoupper( $language['language_code'] ) );
                    }
                    
                    echo '</a>';
                    echo '</li>';
                }
                
                echo '</ul>';
            }
        } else {
            // Default - just show current locale
            echo '<span class="current-language">' . esc_html( get_locale() ) . '</span>';
        }
        
        echo '</div>';
    }

    /**
     * Add language class to body
     *
     * @param array $classes Body classes.
     * @return array
     */
    public function add_language_class( $classes ) {
        $classes[] = 'lang-' . sanitize_html_class( $this->current_language );
        return $classes;
    }

    /**
     * Filter nav menu locations based on language
     *
     * @param array $locations Menu locations.
     * @return array
     */
    public function filter_nav_menu_locations( $locations ) {
        // Only filter if we have multiple languages
        if ( count( $this->available_languages ) <= 1 ) {
            return $locations;
        }
        
        // Check if we're using Polylang or WPML
        if ( function_exists( 'pll_get_nav_menu' ) ) {
            // Polylang
            foreach ( $locations as $location => $menu_id ) {
                $locations[ $location ] = pll_get_nav_menu( $menu_id );
            }
        } elseif ( function_exists( 'icl_object_id' ) && $this->current_language ) {
            // WPML
            foreach ( $locations as $location => $menu_id ) {
                $locations[ $location ] = icl_object_id( $menu_id, 'nav_menu', true, $this->current_language );
            }
        }
        
        return $locations;
    }

    /**
     * Filter footer copyright based on language
     *
     * @param string $copyright Footer copyright.
     * @return string
     */
    public function filter_footer_copyright( $copyright ) {
        // Only filter if we have multiple languages
        if ( count( $this->available_languages ) <= 1 ) {
            return $copyright;
        }
        
        // Get language-specific copyright
        $lang_copyright = get_theme_mod( 'aqualuxe_footer_copyright_' . $this->current_language, '' );
        
        if ( ! empty( $lang_copyright ) ) {
            return $lang_copyright;
        }
        
        return $copyright;
    }

    /**
     * Filter blog title based on language
     *
     * @param string $title Blog title.
     * @return string
     */
    public function filter_blog_title( $title ) {
        // Only filter if we have multiple languages
        if ( count( $this->available_languages ) <= 1 ) {
            return $title;
        }
        
        // Get language-specific blog title
        $lang_title = get_theme_mod( 'aqualuxe_blog_title_' . $this->current_language, '' );
        
        if ( ! empty( $lang_title ) ) {
            return $lang_title;
        }
        
        return $title;
    }

    /**
     * Filter blog subtitle based on language
     *
     * @param string $subtitle Blog subtitle.
     * @return string
     */
    public function filter_blog_subtitle( $subtitle ) {
        // Only filter if we have multiple languages
        if ( count( $this->available_languages ) <= 1 ) {
            return $subtitle;
        }
        
        // Get language-specific blog subtitle
        $lang_subtitle = get_theme_mod( 'aqualuxe_blog_subtitle_' . $this->current_language, '' );
        
        if ( ! empty( $lang_subtitle ) ) {
            return $lang_subtitle;
        }
        
        return $subtitle;
    }

    /**
     * Filter read more text based on language
     *
     * @param string $text Read more text.
     * @return string
     */
    public function filter_read_more_text( $text ) {
        // Only filter if we have multiple languages
        if ( count( $this->available_languages ) <= 1 ) {
            return $text;
        }
        
        // Get language-specific read more text
        $lang_text = get_theme_mod( 'aqualuxe_blog_read_more_text_' . $this->current_language, '' );
        
        if ( ! empty( $lang_text ) ) {
            return $lang_text;
        }
        
        return $text;
    }

    /**
     * Filter add to cart text based on language
     *
     * @param string $text Add to cart text.
     * @return string
     */
    public function filter_add_to_cart_text( $text ) {
        // Only filter if we have multiple languages
        if ( count( $this->available_languages ) <= 1 ) {
            return $text;
        }
        
        // Get language-specific add to cart text
        $lang_text = get_theme_mod( 'aqualuxe_add_to_cart_text_' . $this->current_language, '' );
        
        if ( ! empty( $lang_text ) ) {
            return $lang_text;
        }
        
        return $text;
    }

    /**
     * Filter place order text based on language
     *
     * @param string $text Place order text.
     * @return string
     */
    public function filter_place_order_text( $text ) {
        // Only filter if we have multiple languages
        if ( count( $this->available_languages ) <= 1 ) {
            return $text;
        }
        
        // Get language-specific place order text
        $lang_text = get_theme_mod( 'aqualuxe_place_order_text_' . $this->current_language, '' );
        
        if ( ! empty( $lang_text ) ) {
            return $lang_text;
        }
        
        return $text;
    }

    /**
     * Get current language
     *
     * @return string
     */
    public function get_current_language() {
        return $this->current_language;
    }

    /**
     * Get available languages
     *
     * @return array
     */
    public function get_available_languages() {
        return $this->available_languages;
    }

    /**
     * Check if multilingual is active
     *
     * @return boolean
     */
    public function is_multilingual_active() {
        return count( $this->available_languages ) > 1;
    }

    /**
     * Get language name
     *
     * @param string $language Language code.
     * @return string
     */
    public function get_language_name( $language ) {
        if ( function_exists( 'pll_languages_list' ) ) {
            // Polylang
            $languages = pll_languages_list( [ 'fields' => 'name' ] );
            $slugs = pll_languages_list( [ 'fields' => 'slug' ] );
            $key = array_search( $language, $slugs, true );
            
            if ( false !== $key && isset( $languages[ $key ] ) ) {
                return $languages[ $key ];
            }
        } elseif ( function_exists( 'icl_get_languages' ) ) {
            // WPML
            $languages = icl_get_languages( 'skip_missing=0' );
            
            if ( isset( $languages[ $language ] ) ) {
                return $languages[ $language ]['native_name'];
            }
        }
        
        return $language;
    }

    /**
     * Get language URL
     *
     * @param string $language Language code.
     * @return string
     */
    public function get_language_url( $language ) {
        if ( function_exists( 'pll_home_url' ) ) {
            // Polylang
            return pll_home_url( $language );
        } elseif ( function_exists( 'icl_get_languages' ) ) {
            // WPML
            $languages = icl_get_languages( 'skip_missing=0' );
            
            if ( isset( $languages[ $language ] ) ) {
                return $languages[ $language ]['url'];
            }
        }
        
        return home_url();
    }

    /**
     * Get translated post ID
     *
     * @param int    $post_id Post ID.
     * @param string $language Language code.
     * @return int
     */
    public function get_translated_post_id( $post_id, $language = '' ) {
        if ( empty( $language ) ) {
            $language = $this->current_language;
        }
        
        if ( function_exists( 'pll_get_post' ) ) {
            // Polylang
            return pll_get_post( $post_id, $language );
        } elseif ( function_exists( 'icl_object_id' ) ) {
            // WPML
            return icl_object_id( $post_id, get_post_type( $post_id ), true, $language );
        }
        
        return $post_id;
    }

    /**
     * Get translated term ID
     *
     * @param int    $term_id Term ID.
     * @param string $taxonomy Taxonomy.
     * @param string $language Language code.
     * @return int
     */
    public function get_translated_term_id( $term_id, $taxonomy, $language = '' ) {
        if ( empty( $language ) ) {
            $language = $this->current_language;
        }
        
        if ( function_exists( 'pll_get_term' ) ) {
            // Polylang
            return pll_get_term( $term_id, $language );
        } elseif ( function_exists( 'icl_object_id' ) ) {
            // WPML
            return icl_object_id( $term_id, $taxonomy, true, $language );
        }
        
        return $term_id;
    }
}