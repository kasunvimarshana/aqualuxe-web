<?php
/**
 * Multilingual Template Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/Multilingual
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Add language switcher to header
 *
 * @return void
 */
function aqualuxe_multilingual_header_switcher() {
    $multilingual_module = aqualuxe_get_module( 'multilingual' );
    
    if ( $multilingual_module && isset( $multilingual_module->settings['switcher_position'] ) && 'header' === $multilingual_module->settings['switcher_position'] ) {
        aqualuxe_language_switcher();
    }
}
add_action( 'aqualuxe_header_after_navigation', 'aqualuxe_multilingual_header_switcher' );

/**
 * Add language switcher to footer
 *
 * @return void
 */
function aqualuxe_multilingual_footer_switcher() {
    $multilingual_module = aqualuxe_get_module( 'multilingual' );
    
    if ( $multilingual_module && isset( $multilingual_module->settings['switcher_position'] ) && 'footer' === $multilingual_module->settings['switcher_position'] ) {
        aqualuxe_language_switcher();
    }
}
add_action( 'aqualuxe_footer_before_widgets', 'aqualuxe_multilingual_footer_switcher' );

/**
 * Add RTL body class
 *
 * @param array $classes Body classes.
 * @return array
 */
function aqualuxe_multilingual_body_class( $classes ) {
    if ( aqualuxe_is_rtl_language() ) {
        $classes[] = 'rtl';
    }
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_multilingual_body_class' );

/**
 * Add language attributes to HTML tag
 *
 * @param string $output Language attributes.
 * @return string
 */
function aqualuxe_multilingual_language_attributes( $output ) {
    $current_language = aqualuxe_get_current_language();
    
    if ( isset( $current_language['locale'] ) ) {
        $lang_code = substr( $current_language['locale'], 0, 2 );
        $output = str_replace( 'lang="', 'lang="' . esc_attr( $lang_code ), $output );
        
        // Add dir attribute
        if ( isset( $current_language['is_rtl'] ) && $current_language['is_rtl'] ) {
            $output .= ' dir="rtl"';
        } else {
            $output .= ' dir="ltr"';
        }
    }
    
    return $output;
}
add_filter( 'language_attributes', 'aqualuxe_multilingual_language_attributes' );

/**
 * Add RTL stylesheet
 *
 * @return void
 */
function aqualuxe_multilingual_rtl_stylesheet() {
    $multilingual_module = aqualuxe_get_module( 'multilingual' );
    
    if ( $multilingual_module && isset( $multilingual_module->settings['rtl_support'] ) && $multilingual_module->settings['rtl_support'] && aqualuxe_is_rtl_language() ) {
        wp_enqueue_style(
            'aqualuxe-rtl',
            AQUALUXE_THEME_URI . 'modules/multilingual/assets/css/rtl.css',
            array(),
            $multilingual_module->get_version()
        );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_multilingual_rtl_stylesheet' );

/**
 * Add hreflang links to head
 *
 * @return void
 */
function aqualuxe_multilingual_hreflang_links() {
    $enabled_languages = aqualuxe_get_enabled_languages();
    
    if ( empty( $enabled_languages ) ) {
        return;
    }
    
    foreach ( $enabled_languages as $locale => $language ) {
        $lang_code = substr( $locale, 0, 2 );
        $url = aqualuxe_get_language_url( $locale );
        
        echo '<link rel="alternate" hreflang="' . esc_attr( $lang_code ) . '" href="' . esc_url( $url ) . '" />' . "\n";
    }
    
    // Add x-default hreflang
    $multilingual_module = aqualuxe_get_module( 'multilingual' );
    
    if ( $multilingual_module && isset( $multilingual_module->settings['default_language'] ) ) {
        $default_language = $multilingual_module->settings['default_language'];
        $default_url = aqualuxe_get_language_url( $default_language );
        
        echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $default_url ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_multilingual_hreflang_links' );

/**
 * Filter the document title
 *
 * @param string $title Document title.
 * @return string
 */
function aqualuxe_multilingual_document_title( $title ) {
    // If WPML or Polylang is active, they will handle the title translation
    if ( defined( 'ICL_LANGUAGE_CODE' ) || function_exists( 'pll_current_language' ) ) {
        return $title;
    }
    
    // Simple translation for demo purposes
    $current_language = aqualuxe_get_current_language_code();
    
    // This is a simplified example. In a real theme, you would use proper translation files.
    $translations = array(
        'fr_FR' => array(
            'Home' => 'Accueil',
            'Blog' => 'Blog',
            'About' => 'À propos',
            'Contact' => 'Contact',
        ),
        'es_ES' => array(
            'Home' => 'Inicio',
            'Blog' => 'Blog',
            'About' => 'Acerca de',
            'Contact' => 'Contacto',
        ),
        'de_DE' => array(
            'Home' => 'Startseite',
            'Blog' => 'Blog',
            'About' => 'Über uns',
            'Contact' => 'Kontakt',
        ),
    );
    
    if ( isset( $translations[ $current_language ] ) ) {
        foreach ( $translations[ $current_language ] as $english => $translated ) {
            $title = str_replace( $english, $translated, $title );
        }
    }
    
    return $title;
}
add_filter( 'document_title', 'aqualuxe_multilingual_document_title' );

/**
 * Filter the site title
 *
 * @param string $title Site title.
 * @return string
 */
function aqualuxe_multilingual_site_title( $title ) {
    // If WPML or Polylang is active, they will handle the title translation
    if ( defined( 'ICL_LANGUAGE_CODE' ) || function_exists( 'pll_current_language' ) ) {
        return $title;
    }
    
    // Get translated site title if available
    $translated_title = get_option( 'aqualuxe_site_title_' . aqualuxe_get_current_language_code() );
    
    if ( $translated_title ) {
        return $translated_title;
    }
    
    return $title;
}
add_filter( 'option_blogname', 'aqualuxe_multilingual_site_title' );

/**
 * Filter the site description
 *
 * @param string $description Site description.
 * @return string
 */
function aqualuxe_multilingual_site_description( $description ) {
    // If WPML or Polylang is active, they will handle the description translation
    if ( defined( 'ICL_LANGUAGE_CODE' ) || function_exists( 'pll_current_language' ) ) {
        return $description;
    }
    
    // Get translated site description if available
    $translated_description = get_option( 'aqualuxe_site_description_' . aqualuxe_get_current_language_code() );
    
    if ( $translated_description ) {
        return $translated_description;
    }
    
    return $description;
}
add_filter( 'option_blogdescription', 'aqualuxe_multilingual_site_description' );

/**
 * Filter menu items for translation
 *
 * @param array $items Menu items.
 * @return array
 */
function aqualuxe_multilingual_nav_menu_items( $items ) {
    // If WPML or Polylang is active, they will handle the menu translation
    if ( defined( 'ICL_LANGUAGE_CODE' ) || function_exists( 'pll_current_language' ) ) {
        return $items;
    }
    
    // Simple translation for demo purposes
    $current_language = aqualuxe_get_current_language_code();
    
    // This is a simplified example. In a real theme, you would use proper translation files.
    $translations = array(
        'fr_FR' => array(
            'Home' => 'Accueil',
            'Blog' => 'Blog',
            'About' => 'À propos',
            'Contact' => 'Contact',
            'Products' => 'Produits',
            'Services' => 'Services',
        ),
        'es_ES' => array(
            'Home' => 'Inicio',
            'Blog' => 'Blog',
            'About' => 'Acerca de',
            'Contact' => 'Contacto',
            'Products' => 'Productos',
            'Services' => 'Servicios',
        ),
        'de_DE' => array(
            'Home' => 'Startseite',
            'Blog' => 'Blog',
            'About' => 'Über uns',
            'Contact' => 'Kontakt',
            'Products' => 'Produkte',
            'Services' => 'Dienstleistungen',
        ),
    );
    
    if ( isset( $translations[ $current_language ] ) ) {
        foreach ( $items as &$item ) {
            if ( isset( $translations[ $current_language ][ $item->title ] ) ) {
                $item->title = $translations[ $current_language ][ $item->title ];
            }
        }
    }
    
    return $items;
}
add_filter( 'wp_nav_menu_objects', 'aqualuxe_multilingual_nav_menu_items' );

/**
 * Add language information to post classes
 *
 * @param array $classes Post classes.
 * @return array
 */
function aqualuxe_multilingual_post_class( $classes ) {
    $current_language = aqualuxe_get_current_language();
    
    if ( isset( $current_language['locale'] ) ) {
        $classes[] = 'lang-' . strtolower( substr( $current_language['locale'], 0, 2 ) );
        
        if ( isset( $current_language['is_rtl'] ) && $current_language['is_rtl'] ) {
            $classes[] = 'lang-rtl';
        } else {
            $classes[] = 'lang-ltr';
        }
    }
    
    return $classes;
}
add_filter( 'post_class', 'aqualuxe_multilingual_post_class' );

/**
 * Add language meta tags
 *
 * @return void
 */
function aqualuxe_multilingual_meta_tags() {
    $current_language = aqualuxe_get_current_language();
    
    if ( isset( $current_language['locale'] ) ) {
        echo '<meta name="language" content="' . esc_attr( substr( $current_language['locale'], 0, 2 ) ) . '">' . "\n";
        echo '<meta property="og:locale" content="' . esc_attr( str_replace( '_', '-', $current_language['locale'] ) ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_multilingual_meta_tags' );

/**
 * Add language information to body tag
 *
 * @param array $classes Body classes.
 * @return array
 */
function aqualuxe_multilingual_language_body_class( $classes ) {
    $current_language = aqualuxe_get_current_language();
    
    if ( isset( $current_language['locale'] ) ) {
        $classes[] = 'lang-' . strtolower( substr( $current_language['locale'], 0, 2 ) );
    }
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_multilingual_language_body_class' );

/**
 * Add language switcher to mobile menu
 *
 * @param string $items Menu items.
 * @param object $args Menu arguments.
 * @return string
 */
function aqualuxe_multilingual_mobile_menu( $items, $args ) {
    // Only add to mobile menu
    if ( isset( $args->theme_location ) && 'mobile' === $args->theme_location ) {
        $switcher = aqualuxe_get_language_switcher();
        
        if ( $switcher ) {
            $items .= '<li class="menu-item menu-item-language-switcher">' . $switcher . '</li>';
        }
    }
    
    return $items;
}
add_filter( 'wp_nav_menu_items', 'aqualuxe_multilingual_mobile_menu', 10, 2 );

/**
 * Filter date format based on language
 *
 * @param string $format Date format.
 * @return string
 */
function aqualuxe_multilingual_date_format( $format ) {
    $current_language = aqualuxe_get_current_language_code();
    
    // Date format by language
    $date_formats = array(
        'fr_FR' => 'd/m/Y',
        'es_ES' => 'd/m/Y',
        'de_DE' => 'd.m.Y',
        'en_US' => 'm/d/Y',
        'en_GB' => 'd/m/Y',
    );
    
    if ( isset( $date_formats[ $current_language ] ) ) {
        return $date_formats[ $current_language ];
    }
    
    return $format;
}
add_filter( 'option_date_format', 'aqualuxe_multilingual_date_format' );

/**
 * Filter time format based on language
 *
 * @param string $format Time format.
 * @return string
 */
function aqualuxe_multilingual_time_format( $format ) {
    $current_language = aqualuxe_get_current_language_code();
    
    // Time format by language
    $time_formats = array(
        'fr_FR' => 'H:i',
        'es_ES' => 'H:i',
        'de_DE' => 'H:i',
        'en_US' => 'g:i a',
        'en_GB' => 'H:i',
    );
    
    if ( isset( $time_formats[ $current_language ] ) ) {
        return $time_formats[ $current_language ];
    }
    
    return $format;
}
add_filter( 'option_time_format', 'aqualuxe_multilingual_time_format' );