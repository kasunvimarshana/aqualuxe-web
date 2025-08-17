<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function aqualuxe_body_classes( $classes ) {
    // Adds a class of hfeed to non-singular pages.
    if ( ! is_singular() ) {
        $classes[] = 'hfeed';
    }

    // Adds a class if WooCommerce is active
    if ( aqualuxe_is_woocommerce_active() ) {
        $classes[] = 'woocommerce-active';
    } else {
        $classes[] = 'woocommerce-inactive';
    }

    // Add class for dark mode preference
    if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) && $_COOKIE['aqualuxe_dark_mode'] === 'true' ) {
        $classes[] = 'dark-mode';
    }

    // Add class for sidebar presence
    if ( aqualuxe_has_sidebar() ) {
        $classes[] = 'has-sidebar';
    } else {
        $classes[] = 'no-sidebar';
    }

    // Add class for page template
    if ( is_page_template() ) {
        $template_slug = get_page_template_slug();
        $template_parts = explode( '/', $template_slug );
        $template_name = str_replace( '.php', '', end( $template_parts ) );
        $classes[] = 'page-template-' . sanitize_html_class( $template_name );
    }

    return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

/**
 * Check if the current page should display a sidebar
 *
 * @return bool True if sidebar should be displayed
 */
function aqualuxe_has_sidebar() {
    // Default to false
    $has_sidebar = false;
    
    // Check for blog/archive pages
    if ( is_home() || is_archive() || is_search() ) {
        $has_sidebar = true;
    }
    
    // Check for single posts
    if ( is_singular( 'post' ) ) {
        $has_sidebar = true;
    }
    
    // Check for WooCommerce pages
    if ( aqualuxe_is_woocommerce_active() ) {
        if ( is_shop() || is_product_category() || is_product_tag() ) {
            $has_sidebar = true;
        }
    }
    
    // Allow filtering of the sidebar display
    return apply_filters( 'aqualuxe_has_sidebar', $has_sidebar );
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aqualuxe_pingback_header() {
    if ( is_singular() && pings_open() ) {
        printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
    }
}
add_action( 'wp_head', 'aqualuxe_pingback_header' );

/**
 * Add schema.org structured data
 */
function aqualuxe_schema_org() {
    // Default schema
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => get_bloginfo( 'name' ),
        'url' => home_url(),
    ];
    
    // Modify schema based on page type
    if ( is_singular( 'post' ) ) {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'url' => get_permalink(),
            'datePublished' => get_the_date( 'c' ),
            'dateModified' => get_the_modified_date( 'c' ),
            'author' => [
                '@type' => 'Person',
                'name' => get_the_author(),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => get_bloginfo( 'name' ),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => get_custom_logo_url(),
                ],
            ],
        ];
        
        // Add featured image if available
        if ( has_post_thumbnail() ) {
            $schema['image'] = [
                '@type' => 'ImageObject',
                'url' => get_the_post_thumbnail_url( null, 'full' ),
            ];
        }
    } elseif ( is_page() ) {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => get_the_title(),
            'description' => get_the_excerpt(),
            'url' => get_permalink(),
        ];
    } elseif ( aqualuxe_is_woocommerce_active() && is_product() ) {
        global $product;
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => get_the_title(),
            'description' => get_the_excerpt(),
            'sku' => $product->get_sku(),
            'brand' => [
                '@type' => 'Brand',
                'name' => get_bloginfo( 'name' ),
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
                'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => get_permalink(),
            ],
        ];
        
        // Add product image
        if ( has_post_thumbnail() ) {
            $schema['image'] = get_the_post_thumbnail_url( null, 'full' );
        }
    }
    
    // Allow filtering of schema data
    $schema = apply_filters( 'aqualuxe_schema_org', $schema );
    
    // Output the schema
    if ( ! empty( $schema ) ) {
        echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
    }
}
add_action( 'wp_head', 'aqualuxe_schema_org' );

/**
 * Get URL of custom logo
 *
 * @return string URL of the logo or empty string if no logo is set
 */
function get_custom_logo_url() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $logo_url = '';
    
    if ( $custom_logo_id ) {
        $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
    }
    
    return $logo_url;
}

/**
 * Add Open Graph meta tags
 */
function aqualuxe_open_graph_tags() {
    // Default Open Graph data
    $og_title = get_bloginfo( 'name' );
    $og_description = get_bloginfo( 'description' );
    $og_url = home_url();
    $og_type = 'website';
    $og_image = get_custom_logo_url();
    
    // Modify based on page type
    if ( is_singular() ) {
        $og_title = get_the_title();
        $og_description = get_the_excerpt();
        $og_url = get_permalink();
        $og_type = is_single() ? 'article' : 'website';
        
        if ( has_post_thumbnail() ) {
            $og_image = get_the_post_thumbnail_url( null, 'full' );
        }
    } elseif ( is_archive() ) {
        $og_title = get_the_archive_title();
        $og_description = get_the_archive_description();
        $og_url = get_permalink();
    }
    
    // Output Open Graph tags
    echo '<meta property="og:title" content="' . esc_attr( $og_title ) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $og_description ) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $og_url ) . '" />' . "\n";
    echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '" />' . "\n";
    
    if ( $og_image ) {
        echo '<meta property="og:image" content="' . esc_url( $og_image ) . '" />' . "\n";
    }
    
    // Add site name
    echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
}
add_action( 'wp_head', 'aqualuxe_open_graph_tags' );

/**
 * Add Twitter Card meta tags
 */
function aqualuxe_twitter_card_tags() {
    // Default Twitter Card data
    $twitter_card = 'summary_large_image';
    $twitter_title = get_bloginfo( 'name' );
    $twitter_description = get_bloginfo( 'description' );
    $twitter_image = get_custom_logo_url();
    
    // Modify based on page type
    if ( is_singular() ) {
        $twitter_title = get_the_title();
        $twitter_description = get_the_excerpt();
        
        if ( has_post_thumbnail() ) {
            $twitter_image = get_the_post_thumbnail_url( null, 'full' );
        }
    } elseif ( is_archive() ) {
        $twitter_title = get_the_archive_title();
        $twitter_description = get_the_archive_description();
    }
    
    // Output Twitter Card tags
    echo '<meta name="twitter:card" content="' . esc_attr( $twitter_card ) . '" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( $twitter_title ) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $twitter_description ) . '" />' . "\n";
    
    if ( $twitter_image ) {
        echo '<meta name="twitter:image" content="' . esc_url( $twitter_image ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_twitter_card_tags' );

/**
 * Add custom viewport meta tag
 */
function aqualuxe_viewport_meta() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">' . "\n";
}
add_action( 'wp_head', 'aqualuxe_viewport_meta', 0 );

/**
 * Add theme color meta tag for mobile browsers
 */
function aqualuxe_theme_color_meta() {
    $theme_color = get_theme_mod( 'aqualuxe_theme_color', '#0073aa' );
    echo '<meta name="theme-color" content="' . esc_attr( $theme_color ) . '">' . "\n";
}
add_action( 'wp_head', 'aqualuxe_theme_color_meta' );

/**
 * Add preload for critical assets
 */
function aqualuxe_preload_assets() {
    // Preload main CSS
    echo '<link rel="preload" href="' . esc_url( aqualuxe_asset_path( '/css/main.css' ) ) . '" as="style">' . "\n";
    
    // Preload main JS
    echo '<link rel="preload" href="' . esc_url( aqualuxe_asset_path( '/js/main.js' ) ) . '" as="script">' . "\n";
    
    // Preload logo if available
    $logo_url = get_custom_logo_url();
    if ( $logo_url ) {
        echo '<link rel="preload" href="' . esc_url( $logo_url ) . '" as="image">' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_preload_assets', 1 );

/**
 * Add DNS prefetch for external resources
 */
function aqualuxe_dns_prefetch() {
    echo '<meta http-equiv="x-dns-prefetch-control" content="on">' . "\n";
    echo '<link rel="dns-prefetch" href="//s.w.org">' . "\n";
    
    // Add more prefetch domains as needed
    // echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
}
add_action( 'wp_head', 'aqualuxe_dns_prefetch', 0 );