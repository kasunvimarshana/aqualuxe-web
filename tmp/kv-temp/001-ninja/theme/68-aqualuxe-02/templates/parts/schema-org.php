<?php
/**
 * Template part for displaying schema.org markup
 *
 * @link https://schema.org/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get site information
$site_name = get_bloginfo( 'name' );
$site_description = get_bloginfo( 'description' );
$site_url = home_url( '/' );
$logo = \AquaLuxe\Helpers\Utils::get_theme_logo();

// Base schema
$schema = [
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => $site_name,
    'description' => $site_description,
    'url' => $site_url,
];

// Add search action
$schema['potentialAction'] = [
    '@type' => 'SearchAction',
    'target' => $site_url . '?s={search_term_string}',
    'query-input' => 'required name=search_term_string',
];

// Add organization schema
$organization_schema = [
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => $site_name,
    'url' => $site_url,
];

// Add logo if available
if ( $logo ) {
    $organization_schema['logo'] = $logo;
}

// Add contact information
$contact_info = \AquaLuxe\Helpers\Utils::get_theme_contact_info();
if ( ! empty( $contact_info ) ) {
    if ( isset( $contact_info['phone'] ) ) {
        $organization_schema['telephone'] = $contact_info['phone']['value'];
    }
    
    if ( isset( $contact_info['email'] ) ) {
        $organization_schema['email'] = $contact_info['email']['value'];
    }
    
    if ( isset( $contact_info['address'] ) ) {
        $organization_schema['address'] = [
            '@type' => 'PostalAddress',
            'streetAddress' => $contact_info['address']['value'],
        ];
    }
}

// Add social profiles
$social_links = \AquaLuxe\Helpers\Utils::get_theme_social_links();
if ( ! empty( $social_links ) ) {
    $social_urls = [];
    
    foreach ( $social_links as $network => $link ) {
        $social_urls[] = $link['url'];
    }
    
    $organization_schema['sameAs'] = $social_urls;
}

// Page-specific schema
if ( is_singular( 'post' ) ) {
    // Blog post schema
    $post_schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => get_the_title(),
        'description' => get_the_excerpt(),
        'url' => get_permalink(),
        'datePublished' => get_the_date( 'c' ),
        'dateModified' => get_the_modified_date( 'c' ),
        'author' => [
            '@type' => 'Person',
            'name' => get_the_author(),
            'url' => get_author_posts_url( get_the_author_meta( 'ID' ) ),
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => $site_name,
            'url' => $site_url,
        ],
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => get_permalink(),
        ],
    ];
    
    // Add featured image if available
    if ( has_post_thumbnail() ) {
        $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
        if ( $image ) {
            $post_schema['image'] = [
                '@type' => 'ImageObject',
                'url' => $image[0],
                'width' => $image[1],
                'height' => $image[2],
            ];
        }
    }
    
    // Add publisher logo if available
    if ( $logo ) {
        $post_schema['publisher']['logo'] = [
            '@type' => 'ImageObject',
            'url' => $logo,
        ];
    }
    
    // Output post schema
    echo '<script type="application/ld+json">' . wp_json_encode( $post_schema ) . '</script>';
} elseif ( is_singular( 'product' ) && \AquaLuxe\Core\Theme::get_instance()->is_woocommerce_active() ) {
    // Product schema is handled by WooCommerce
} else {
    // Output website schema
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}

// Output organization schema
echo '<script type="application/ld+json">' . wp_json_encode( $organization_schema ) . '</script>';