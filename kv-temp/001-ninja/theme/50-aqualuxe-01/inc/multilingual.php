<?php
/**
 * Multilingual support for AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Initialize multilingual support
 */
function aqualuxe_multilingual_init() {
    // Check if WPML is active
    if ( function_exists( 'icl_object_id' ) ) {
        // Add WPML compatibility
        add_action( 'after_setup_theme', 'aqualuxe_wpml_compatibility' );
    }

    // Register strings for translation
    add_action( 'after_setup_theme', 'aqualuxe_register_strings_for_translation' );
}
add_action( 'init', 'aqualuxe_multilingual_init' );

/**
 * Add WPML compatibility
 */
function aqualuxe_wpml_compatibility() {
    // Add theme support for WPML
    add_theme_support( 'wpml-compatibility' );
    
    // Add support for WPML language switcher
    add_filter( 'wp_nav_menu_items', 'aqualuxe_add_language_switcher_to_menu', 10, 2 );
    
    // Add support for translating theme mods
    add_filter( 'theme_mod_aqualuxe_hero_title', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_hero_subtitle', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_hero_button_text', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_hero_secondary_button_text', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_featured_products_title', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_featured_products_subtitle', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_featured_products_button_text', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_about_title', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_about_subtitle', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_about_content', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_about_button_text', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_services_title', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_services_subtitle', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_services_button_text', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_testimonials_title', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_testimonials_subtitle', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_blog_title', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_blog_subtitle', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_blog_button_text', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_newsletter_title', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_newsletter_subtitle', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_partners_title', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_partners_subtitle', 'aqualuxe_translate_theme_mod' );
    add_filter( 'theme_mod_aqualuxe_footer_copyright', 'aqualuxe_translate_theme_mod' );
}

/**
 * Register strings for translation with WPML String Translation
 */
function aqualuxe_register_strings_for_translation() {
    if ( function_exists( 'icl_register_string' ) ) {
        // Register theme mods for translation
        $theme_mods_to_translate = array(
            'aqualuxe_hero_title' => __( 'Hero Title', 'aqualuxe' ),
            'aqualuxe_hero_subtitle' => __( 'Hero Subtitle', 'aqualuxe' ),
            'aqualuxe_hero_button_text' => __( 'Hero Button Text', 'aqualuxe' ),
            'aqualuxe_hero_secondary_button_text' => __( 'Hero Secondary Button Text', 'aqualuxe' ),
            'aqualuxe_featured_products_title' => __( 'Featured Products Title', 'aqualuxe' ),
            'aqualuxe_featured_products_subtitle' => __( 'Featured Products Subtitle', 'aqualuxe' ),
            'aqualuxe_featured_products_button_text' => __( 'Featured Products Button Text', 'aqualuxe' ),
            'aqualuxe_about_title' => __( 'About Title', 'aqualuxe' ),
            'aqualuxe_about_subtitle' => __( 'About Subtitle', 'aqualuxe' ),
            'aqualuxe_about_content' => __( 'About Content', 'aqualuxe' ),
            'aqualuxe_about_button_text' => __( 'About Button Text', 'aqualuxe' ),
            'aqualuxe_services_title' => __( 'Services Title', 'aqualuxe' ),
            'aqualuxe_services_subtitle' => __( 'Services Subtitle', 'aqualuxe' ),
            'aqualuxe_services_button_text' => __( 'Services Button Text', 'aqualuxe' ),
            'aqualuxe_testimonials_title' => __( 'Testimonials Title', 'aqualuxe' ),
            'aqualuxe_testimonials_subtitle' => __( 'Testimonials Subtitle', 'aqualuxe' ),
            'aqualuxe_blog_title' => __( 'Blog Title', 'aqualuxe' ),
            'aqualuxe_blog_subtitle' => __( 'Blog Subtitle', 'aqualuxe' ),
            'aqualuxe_blog_button_text' => __( 'Blog Button Text', 'aqualuxe' ),
            'aqualuxe_newsletter_title' => __( 'Newsletter Title', 'aqualuxe' ),
            'aqualuxe_newsletter_subtitle' => __( 'Newsletter Subtitle', 'aqualuxe' ),
            'aqualuxe_partners_title' => __( 'Partners Title', 'aqualuxe' ),
            'aqualuxe_partners_subtitle' => __( 'Partners Subtitle', 'aqualuxe' ),
            'aqualuxe_footer_copyright' => __( 'Footer Copyright', 'aqualuxe' ),
        );
        
        foreach ( $theme_mods_to_translate as $mod_name => $mod_title ) {
            $mod_value = get_theme_mod( $mod_name );
            if ( $mod_value ) {
                icl_register_string( 'Theme Mod', $mod_name, $mod_value );
            }
        }
        
        // Register service items for translation
        $services = get_theme_mod( 'aqualuxe_services' );
        if ( is_array( $services ) ) {
            foreach ( $services as $index => $service ) {
                if ( isset( $service['title'] ) ) {
                    icl_register_string( 'Theme Services', 'service_title_' . $index, $service['title'] );
                }
                if ( isset( $service['description'] ) ) {
                    icl_register_string( 'Theme Services', 'service_description_' . $index, $service['description'] );
                }
            }
        }
        
        // Register testimonials for translation
        $testimonials = get_theme_mod( 'aqualuxe_testimonials' );
        if ( is_array( $testimonials ) ) {
            foreach ( $testimonials as $index => $testimonial ) {
                if ( isset( $testimonial['content'] ) ) {
                    icl_register_string( 'Theme Testimonials', 'testimonial_content_' . $index, $testimonial['content'] );
                }
                if ( isset( $testimonial['author'] ) ) {
                    icl_register_string( 'Theme Testimonials', 'testimonial_author_' . $index, $testimonial['author'] );
                }
                if ( isset( $testimonial['position'] ) ) {
                    icl_register_string( 'Theme Testimonials', 'testimonial_position_' . $index, $testimonial['position'] );
                }
            }
        }
    }
}

/**
 * Translate theme mod values
 *
 * @param string $value The theme mod value.
 * @return string
 */
function aqualuxe_translate_theme_mod( $value ) {
    if ( function_exists( 'icl_t' ) && ! empty( $value ) ) {
        $mod_name = current_filter();
        $mod_name = str_replace( 'theme_mod_', '', $mod_name );
        return icl_t( 'Theme Mod', $mod_name, $value );
    }
    return $value;
}

/**
 * Add language switcher to menu
 *
 * @param string $items Menu items HTML.
 * @param object $args Menu arguments.
 * @return string
 */
function aqualuxe_add_language_switcher_to_menu( $items, $args ) {
    if ( function_exists( 'icl_get_languages' ) && $args->theme_location === 'primary' ) {
        $languages = icl_get_languages( 'skip_missing=0' );
        
        if ( ! empty( $languages ) ) {
            $items .= '<li class="menu-item menu-item-language menu-item-has-children">';
            
            // Get current language
            $current_language = '';
            foreach ( $languages as $language ) {
                if ( $language['active'] ) {
                    $current_language = $language;
                    break;
                }
            }
            
            if ( $current_language ) {
                $items .= '<a href="#" class="current-language">';
                if ( $current_language['country_flag_url'] ) {
                    $items .= '<img src="' . esc_url( $current_language['country_flag_url'] ) . '" alt="' . esc_attr( $current_language['language_code'] ) . '" class="language-flag">';
                }
                $items .= '<span>' . esc_html( $current_language['language_code'] ) . '</span>';
                $items .= '</a>';
            }
            
            $items .= '<ul class="sub-menu language-dropdown">';
            
            foreach ( $languages as $language ) {
                $items .= '<li class="menu-item' . ( $language['active'] ? ' current-language-item' : '' ) . '">';
                $items .= '<a href="' . esc_url( $language['url'] ) . '">';
                if ( $language['country_flag_url'] ) {
                    $items .= '<img src="' . esc_url( $language['country_flag_url'] ) . '" alt="' . esc_attr( $language['language_code'] ) . '" class="language-flag">';
                }
                $items .= '<span class="language-name">' . esc_html( $language['native_name'] ) . '</span>';
                $items .= '</a>';
                $items .= '</li>';
            }
            
            $items .= '</ul>';
            $items .= '</li>';
        }
    }
    
    return $items;
}

/**
 * Get translated URL
 *
 * @param string $url The URL to translate.
 * @param string $language_code The language code.
 * @return string
 */
function aqualuxe_get_translated_url( $url, $language_code = '' ) {
    if ( function_exists( 'icl_object_id' ) ) {
        $url_obj = parse_url( $url );
        $post_id = url_to_postid( $url );
        
        if ( $post_id ) {
            // If URL is a post/page, get its translation
            $translated_id = icl_object_id( $post_id, get_post_type( $post_id ), true, $language_code );
            if ( $translated_id ) {
                return get_permalink( $translated_id );
            }
        } elseif ( isset( $url_obj['path'] ) && $url_obj['path'] !== '/' ) {
            // Try to find if it's a custom URL that needs translation
            global $sitepress;
            if ( isset( $sitepress ) ) {
                // Get all languages
                $languages = $sitepress->get_active_languages();
                
                // Check if the URL contains a language code
                foreach ( $languages as $lang_code => $language ) {
                    $language_url = $sitepress->language_url( $lang_code );
                    if ( strpos( $url, $language_url ) === 0 ) {
                        // URL already contains language code, replace it
                        $target_language_url = $sitepress->language_url( $language_code );
                        return str_replace( $language_url, $target_language_url, $url );
                    }
                }
                
                // If no language code found, add it
                return $sitepress->language_url( $language_code );
            }
        }
    }
    
    return $url;
}

/**
 * Get current language
 *
 * @return string
 */
function aqualuxe_get_current_language() {
    if ( function_exists( 'icl_get_current_language' ) ) {
        return icl_get_current_language();
    }
    return get_locale();
}

/**
 * Check if a post has translation
 *
 * @param int    $post_id The post ID.
 * @param string $language_code The language code.
 * @return bool
 */
function aqualuxe_has_translation( $post_id, $language_code = '' ) {
    if ( function_exists( 'icl_object_id' ) ) {
        $translated_id = icl_object_id( $post_id, get_post_type( $post_id ), false, $language_code );
        return ! empty( $translated_id );
    }
    return true;
}