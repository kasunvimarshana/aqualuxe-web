<?php
/**
 * Template Functions
 *
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
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

    // Adds a class of no-sidebar when there is no sidebar present.
    if ( ! is_active_sidebar( 'sidebar-1' ) ) {
        $classes[] = 'no-sidebar';
    }

    // Add dark mode class if enabled
    $dark_mode = get_theme_mod( 'aqualuxe_dark_mode_default', false );
    if ( $dark_mode ) {
        $classes[] = 'dark-mode-default';
    }

    // Add class for WooCommerce pages
    if ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {
        $classes[] = 'woocommerce-page';
    }

    return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

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
 * Custom excerpt length
 */
function aqualuxe_excerpt_length( $length ) {
    if ( is_admin() ) {
        return $length;
    }
    return get_theme_mod( 'aqualuxe_excerpt_length', 25 );
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length' );

/**
 * Custom excerpt more
 */
function aqualuxe_excerpt_more( $more ) {
    if ( is_admin() ) {
        return $more;
    }
    return '&hellip;';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Add custom read more link
 */
function aqualuxe_read_more_link() {
    if ( ! is_admin() ) {
        return '<div class="read-more-wrapper mt-4"><a href="' . esc_url( get_permalink() ) . '" class="read-more-link inline-flex items-center text-primary-600 hover:text-primary-700 font-medium transition-colors">' . esc_html__( 'Read More', 'aqualuxe' ) . '<svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></a></div>';
    }
}

/**
 * Filter the content to add read more link
 */
function aqualuxe_content_more_link( $content ) {
    if ( ! is_singular() && ! is_admin() && ! str_contains( $content, 'class="read-more-wrapper"' ) ) {
        $content .= aqualuxe_read_more_link();
    }
    return $content;
}
add_filter( 'the_content', 'aqualuxe_content_more_link' );

/**
 * Add theme support for WooCommerce
 */
function aqualuxe_woocommerce_setup() {
    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => 300,
        'single_image_width'    => 600,
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 5,
        ),
    ) );

    // Add support for WC features
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_setup' );

/**
 * Disable WooCommerce default styles
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add custom WooCommerce wrapper
 */
function aqualuxe_woocommerce_wrapper_start() {
    echo '<div class="woocommerce-wrapper container mx-auto px-4 py-8">';
}

function aqualuxe_woocommerce_wrapper_end() {
    echo '</div>';
}

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper' );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end' );
add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_start' );
add_action( 'woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_end' );

/**
 * Remove WooCommerce sidebar
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar' );

/**
 * Custom WooCommerce cart link
 */
function aqualuxe_woocommerce_cart_link() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    $cart_count = WC()->cart->get_cart_contents_count();
    $cart_url   = wc_get_cart_url();
    
    return sprintf(
        '<a href="%s" class="cart-link" title="%s">%s <span class="cart-count">%s</span></a>',
        esc_url( $cart_url ),
        esc_attr__( 'View your shopping cart', 'aqualuxe' ),
        esc_html__( 'Cart', 'aqualuxe' ),
        esc_html( $cart_count )
    );
}

/**
 * Update cart count via AJAX
 */
function aqualuxe_woocommerce_cart_count_fragments( $fragments ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return $fragments;
    }
    
    $cart_count = WC()->cart->get_cart_contents_count();
    
    $fragments['.cart-count'] = '<span class="cart-count">' . esc_html( $cart_count ) . '</span>';
    
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_count_fragments' );

/**
 * Preload fonts
 */
function aqualuxe_preload_fonts() {
    ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Merriweather:wght@400;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Merriweather:wght@400;700&display=swap"></noscript>
    <?php
}
add_action( 'wp_head', 'aqualuxe_preload_fonts', 1 );

/**
 * Add schema markup
 */
function aqualuxe_schema_markup() {
    if ( is_singular( 'post' ) ) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'Article',
            'headline' => get_the_title(),
            'author'   => array(
                '@type' => 'Person',
                'name'  => get_the_author(),
            ),
            'datePublished' => get_the_date( 'c' ),
            'dateModified'  => get_the_modified_date( 'c' ),
            'description'   => get_the_excerpt(),
        );
        
        if ( has_post_thumbnail() ) {
            $schema['image'] = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
    }
}
add_action( 'wp_head', 'aqualuxe_schema_markup' );

/**
 * Add Open Graph meta tags
 */
function aqualuxe_open_graph_meta() {
    if ( is_singular() ) {
        global $post;
        
        $title = get_the_title();
        $description = get_the_excerpt() ? get_the_excerpt() : get_bloginfo( 'description' );
        $image = has_post_thumbnail() ? get_the_post_thumbnail_url( $post->ID, 'full' ) : '';
        $url = get_permalink();
        
        echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
        echo '<meta property="og:type" content="article">' . "\n";
        echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
        
        if ( $image ) {
            echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
        }
        
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
        
        if ( $image ) {
            echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
        }
    }
}
add_action( 'wp_head', 'aqualuxe_open_graph_meta' );

/**
 * Add critical CSS inline
 */
function aqualuxe_critical_css() {
    $critical_css = get_theme_mod( 'aqualuxe_critical_css', '' );
    
    if ( ! empty( $critical_css ) ) {
        echo '<style id="aqualuxe-critical-css">' . wp_strip_all_tags( $critical_css ) . '</style>';
    }
}
add_action( 'wp_head', 'aqualuxe_critical_css', 1 );

/**
 * Add security headers
 */
function aqualuxe_security_headers() {
    if ( ! is_admin() ) {
        header( 'X-Content-Type-Options: nosniff' );
        header( 'X-Frame-Options: SAMEORIGIN' );
        header( 'X-XSS-Protection: 1; mode=block' );
        header( 'Referrer-Policy: strict-origin-when-cross-origin' );
    }
}
add_action( 'send_headers', 'aqualuxe_security_headers' );

/**
 * Remove WordPress version from head
 */
remove_action( 'wp_head', 'wp_generator' );

/**
 * Clean up WordPress head
 */
function aqualuxe_clean_head() {
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
}
add_action( 'init', 'aqualuxe_clean_head' );

/**
 * Add defer attribute to scripts
 */
function aqualuxe_defer_scripts( $tag, $handle, $src ) {
    $defer_scripts = array( 'aqualuxe-script' );
    
    if ( in_array( $handle, $defer_scripts, true ) ) {
        return str_replace( '<script ', '<script defer ', $tag );
    }
    
    return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_defer_scripts', 10, 3 );

/**
 * Optimize embeds
 */
function aqualuxe_optimize_embeds() {
    if ( ! is_admin() ) {
        wp_deregister_script( 'wp-embed' );
    }
}
add_action( 'wp_footer', 'aqualuxe_optimize_embeds' );