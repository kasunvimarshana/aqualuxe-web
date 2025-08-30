<?php
/**
 * Template part for displaying Open Graph tags
 *
 * @link https://ogp.me/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get site information
$site_name = get_bloginfo( 'name' );
$site_description = get_bloginfo( 'description' );
$site_url = home_url( '/' );
$logo = \AquaLuxe\Helpers\Utils::get_theme_logo();

// Default Open Graph tags
$og_tags = [
    'og:site_name' => $site_name,
    'og:locale' => get_locale(),
];

// Twitter Card tags
$twitter_tags = [
    'twitter:card' => 'summary_large_image',
];

// Page-specific tags
if ( is_singular() ) {
    // Single post or page
    $og_tags['og:type'] = 'article';
    $og_tags['og:title'] = get_the_title();
    $og_tags['og:description'] = get_the_excerpt();
    $og_tags['og:url'] = get_permalink();
    
    // Add featured image if available
    if ( has_post_thumbnail() ) {
        $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
        if ( $image ) {
            $og_tags['og:image'] = $image[0];
            $og_tags['og:image:width'] = $image[1];
            $og_tags['og:image:height'] = $image[2];
        }
    }
    
    // Add article tags
    $og_tags['article:published_time'] = get_the_date( 'c' );
    $og_tags['article:modified_time'] = get_the_modified_date( 'c' );
    
    // Add author
    $og_tags['article:author'] = get_the_author();
    
    // Twitter tags
    $twitter_tags['twitter:title'] = get_the_title();
    $twitter_tags['twitter:description'] = get_the_excerpt();
    
    if ( has_post_thumbnail() ) {
        $twitter_tags['twitter:image'] = $image[0];
    }
} elseif ( is_archive() ) {
    // Archive page
    $og_tags['og:type'] = 'website';
    $og_tags['og:title'] = get_the_archive_title();
    $og_tags['og:description'] = get_the_archive_description();
    $og_tags['og:url'] = get_permalink();
    
    // Twitter tags
    $twitter_tags['twitter:title'] = get_the_archive_title();
    $twitter_tags['twitter:description'] = get_the_archive_description();
} elseif ( is_search() ) {
    // Search page
    $og_tags['og:type'] = 'website';
    $og_tags['og:title'] = sprintf( __( 'Search Results for: %s', 'aqualuxe' ), get_search_query() );
    $og_tags['og:description'] = $site_description;
    $og_tags['og:url'] = get_search_link();
    
    // Twitter tags
    $twitter_tags['twitter:title'] = sprintf( __( 'Search Results for: %s', 'aqualuxe' ), get_search_query() );
    $twitter_tags['twitter:description'] = $site_description;
} elseif ( is_front_page() ) {
    // Front page
    $og_tags['og:type'] = 'website';
    $og_tags['og:title'] = $site_name;
    $og_tags['og:description'] = $site_description;
    $og_tags['og:url'] = $site_url;
    
    // Add logo if available
    if ( $logo ) {
        $og_tags['og:image'] = $logo;
    }
    
    // Twitter tags
    $twitter_tags['twitter:title'] = $site_name;
    $twitter_tags['twitter:description'] = $site_description;
    
    if ( $logo ) {
        $twitter_tags['twitter:image'] = $logo;
    }
} else {
    // Default fallback
    $og_tags['og:type'] = 'website';
    $og_tags['og:title'] = $site_name;
    $og_tags['og:description'] = $site_description;
    $og_tags['og:url'] = $site_url;
    
    // Add logo if available
    if ( $logo ) {
        $og_tags['og:image'] = $logo;
    }
    
    // Twitter tags
    $twitter_tags['twitter:title'] = $site_name;
    $twitter_tags['twitter:description'] = $site_description;
    
    if ( $logo ) {
        $twitter_tags['twitter:image'] = $logo;
    }
}

// Output Open Graph tags
foreach ( $og_tags as $property => $content ) {
    if ( ! empty( $content ) ) {
        echo '<meta property="' . esc_attr( $property ) . '" content="' . esc_attr( $content ) . '" />' . "\n";
    }
}

// Output Twitter Card tags
foreach ( $twitter_tags as $name => $content ) {
    if ( ! empty( $content ) ) {
        echo '<meta name="' . esc_attr( $name ) . '" content="' . esc_attr( $content ) . '" />' . "\n";
    }
}