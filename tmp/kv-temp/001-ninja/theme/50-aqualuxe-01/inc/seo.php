<?php
/**
 * AquaLuxe SEO Functions
 *
 * Functions for SEO optimization
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add Schema.org structured data
 */
function aqualuxe_add_schema_markup() {
    // Organization schema
    $schema = array(
        '@context'  => 'https://schema.org',
        '@type'     => 'Organization',
        'name'      => get_bloginfo( 'name' ),
        'url'       => home_url(),
        'logo'      => get_custom_logo() ? wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ) : '',
        'contactPoint' => array(
            '@type'     => 'ContactPoint',
            'telephone' => get_theme_mod( 'aqualuxe_contact_phone', '' ),
            'email'     => get_theme_mod( 'aqualuxe_contact_email', '' ),
            'contactType' => 'customer service'
        ),
        'sameAs'    => array(
            get_theme_mod( 'aqualuxe_social_facebook', '' ),
            get_theme_mod( 'aqualuxe_social_twitter', '' ),
            get_theme_mod( 'aqualuxe_social_instagram', '' ),
            get_theme_mod( 'aqualuxe_social_youtube', '' ),
            get_theme_mod( 'aqualuxe_social_linkedin', '' ),
            get_theme_mod( 'aqualuxe_social_pinterest', '' ),
        )
    );

    // Filter empty social links
    $schema['sameAs'] = array_filter( $schema['sameAs'] );

    // Output the schema markup
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}
add_action( 'wp_footer', 'aqualuxe_add_schema_markup' );

/**
 * Add WebSite schema
 */
function aqualuxe_add_website_schema() {
    $schema = array(
        '@context'  => 'https://schema.org',
        '@type'     => 'WebSite',
        'name'      => get_bloginfo( 'name' ),
        'url'       => home_url(),
        'potentialAction' => array(
            '@type'       => 'SearchAction',
            'target'      => home_url( '/?s={search_term_string}' ),
            'query-input' => 'required name=search_term_string'
        )
    );

    // Output the schema markup
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}
add_action( 'wp_footer', 'aqualuxe_add_website_schema' );

/**
 * Add BreadcrumbList schema
 */
function aqualuxe_add_breadcrumb_schema() {
    if ( ! is_singular() ) {
        return;
    }

    $breadcrumbs = array();
    $position = 1;

    // Home
    $breadcrumbs[] = array(
        '@type'    => 'ListItem',
        'position' => $position,
        'name'     => __( 'Home', 'aqualuxe' ),
        'item'     => home_url()
    );
    $position++;

    // Category/taxonomy
    if ( is_singular( 'post' ) ) {
        $categories = get_the_category();
        if ( ! empty( $categories ) ) {
            $category = $categories[0];
            $breadcrumbs[] = array(
                '@type'    => 'ListItem',
                'position' => $position,
                'name'     => $category->name,
                'item'     => get_term_link( $category )
            );
            $position++;
        }
    } elseif ( is_singular( 'product' ) && function_exists( 'wc_get_product_category_list' ) ) {
        $product_id = get_the_ID();
        $product_categories = get_the_terms( $product_id, 'product_cat' );
        if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
            $category = $product_categories[0];
            $breadcrumbs[] = array(
                '@type'    => 'ListItem',
                'position' => $position,
                'name'     => $category->name,
                'item'     => get_term_link( $category )
            );
            $position++;
        }
    }

    // Current page
    $breadcrumbs[] = array(
        '@type'    => 'ListItem',
        'position' => $position,
        'name'     => get_the_title(),
        'item'     => get_permalink()
    );

    $schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $breadcrumbs
    );

    // Output the schema markup
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}
add_action( 'wp_footer', 'aqualuxe_add_breadcrumb_schema' );

/**
 * Add Article schema for blog posts
 */
function aqualuxe_add_article_schema() {
    if ( ! is_singular( 'post' ) ) {
        return;
    }

    $post_id = get_the_ID();
    $author_id = get_post_field( 'post_author', $post_id );
    $author_name = get_the_author_meta( 'display_name', $author_id );
    $author_url = get_author_posts_url( $author_id );
    $post_date = get_post_time( 'c', true, $post_id );
    $post_modified = get_post_modified_time( 'c', true, $post_id );
    $featured_image = get_the_post_thumbnail_url( $post_id, 'full' );

    $schema = array(
        '@context'         => 'https://schema.org',
        '@type'            => 'Article',
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id'   => get_permalink( $post_id )
        ),
        'headline'         => get_the_title( $post_id ),
        'datePublished'    => $post_date,
        'dateModified'     => $post_modified,
        'author'           => array(
            '@type' => 'Person',
            'name'  => $author_name,
            'url'   => $author_url
        ),
        'publisher'        => array(
            '@type' => 'Organization',
            'name'  => get_bloginfo( 'name' ),
            'logo'  => array(
                '@type'  => 'ImageObject',
                'url'    => get_custom_logo() ? wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ) : '',
                'width'  => 600,
                'height' => 60
            )
        )
    );

    // Add featured image if available
    if ( $featured_image ) {
        $schema['image'] = array(
            '@type'  => 'ImageObject',
            'url'    => $featured_image,
            'width'  => 1200,
            'height' => 630
        );
    }

    // Output the schema markup
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}
add_action( 'wp_footer', 'aqualuxe_add_article_schema' );

/**
 * Add Product schema for WooCommerce products
 */
function aqualuxe_add_product_schema() {
    if ( ! is_singular( 'product' ) || ! function_exists( 'wc_get_product' ) ) {
        return;
    }

    $product_id = get_the_ID();
    $product = wc_get_product( $product_id );

    if ( ! $product ) {
        return;
    }

    $schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'Product',
        'name'        => $product->get_name(),
        'description' => wp_strip_all_tags( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ),
        'sku'         => $product->get_sku(),
        'brand'       => array(
            '@type' => 'Brand',
            'name'  => get_bloginfo( 'name' )
        ),
        'offers'      => array(
            '@type'         => 'Offer',
            'price'         => $product->get_price(),
            'priceCurrency' => get_woocommerce_currency(),
            'availability'  => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'url'           => get_permalink( $product_id ),
            'priceValidUntil' => date( 'Y-m-d', strtotime( '+1 year' ) )
        )
    );

    // Add product image
    if ( $product->get_image_id() ) {
        $image_url = wp_get_attachment_image_url( $product->get_image_id(), 'full' );
        if ( $image_url ) {
            $schema['image'] = $image_url;
        }
    }

    // Add product rating
    if ( $product->get_rating_count() > 0 ) {
        $schema['aggregateRating'] = array(
            '@type'       => 'AggregateRating',
            'ratingValue' => $product->get_average_rating(),
            'reviewCount' => $product->get_review_count()
        );
    }

    // Output the schema markup
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}
add_action( 'wp_footer', 'aqualuxe_add_product_schema' );

/**
 * Add Open Graph meta tags
 */
function aqualuxe_add_open_graph_tags() {
    global $post;

    if ( ! is_singular() ) {
        return;
    }

    $post_id = get_the_ID();
    $title = get_the_title( $post_id );
    $description = wp_strip_all_tags( get_the_excerpt( $post_id ) );
    $url = get_permalink( $post_id );
    $site_name = get_bloginfo( 'name' );
    $image = get_the_post_thumbnail_url( $post_id, 'full' );

    // Default image if no featured image is set
    if ( ! $image ) {
        $image = get_theme_mod( 'aqualuxe_default_og_image', '' );
    }

    // Basic Open Graph tags
    echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '" />' . "\n";

    if ( $image ) {
        echo '<meta property="og:image" content="' . esc_url( $image ) . '" />' . "\n";
        echo '<meta property="og:image:secure_url" content="' . esc_url( str_replace( 'http://', 'https://', $image ) ) . '" />' . "\n";
        
        // Get image dimensions
        $image_id = get_post_thumbnail_id( $post_id );
        if ( $image_id ) {
            $image_data = wp_get_attachment_image_src( $image_id, 'full' );
            if ( $image_data ) {
                echo '<meta property="og:image:width" content="' . esc_attr( $image_data[1] ) . '" />' . "\n";
                echo '<meta property="og:image:height" content="' . esc_attr( $image_data[2] ) . '" />' . "\n";
            }
        }
    }

    // Content type
    if ( is_singular( 'post' ) ) {
        echo '<meta property="og:type" content="article" />' . "\n";
        
        // Article specific tags
        $post_date = get_post_time( 'c', true, $post_id );
        $post_modified = get_post_modified_time( 'c', true, $post_id );
        
        echo '<meta property="article:published_time" content="' . esc_attr( $post_date ) . '" />' . "\n";
        echo '<meta property="article:modified_time" content="' . esc_attr( $post_modified ) . '" />' . "\n";
        
        // Article categories as tags
        $categories = get_the_category( $post_id );
        if ( ! empty( $categories ) ) {
            foreach ( $categories as $category ) {
                echo '<meta property="article:section" content="' . esc_attr( $category->name ) . '" />' . "\n";
            }
        }
        
        // Article tags
        $tags = get_the_tags( $post_id );
        if ( ! empty( $tags ) ) {
            foreach ( $tags as $tag ) {
                echo '<meta property="article:tag" content="' . esc_attr( $tag->name ) . '" />' . "\n";
            }
        }
    } elseif ( is_singular( 'product' ) && function_exists( 'wc_get_product' ) ) {
        echo '<meta property="og:type" content="product" />' . "\n";
        
        // Product specific tags
        $product = wc_get_product( $post_id );
        if ( $product ) {
            echo '<meta property="product:price:amount" content="' . esc_attr( $product->get_price() ) . '" />' . "\n";
            echo '<meta property="product:price:currency" content="' . esc_attr( get_woocommerce_currency() ) . '" />' . "\n";
            echo '<meta property="product:availability" content="' . esc_attr( $product->is_in_stock() ? 'in stock' : 'out of stock' ) . '" />' . "\n";
        }
    } else {
        echo '<meta property="og:type" content="website" />' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_add_open_graph_tags', 5 );

/**
 * Add Twitter Card meta tags
 */
function aqualuxe_add_twitter_card_tags() {
    global $post;

    if ( ! is_singular() ) {
        return;
    }

    $post_id = get_the_ID();
    $title = get_the_title( $post_id );
    $description = wp_strip_all_tags( get_the_excerpt( $post_id ) );
    $twitter_username = get_theme_mod( 'aqualuxe_twitter_username', '' );
    $image = get_the_post_thumbnail_url( $post_id, 'full' );

    // Default image if no featured image is set
    if ( ! $image ) {
        $image = get_theme_mod( 'aqualuxe_default_twitter_image', '' );
    }

    // Basic Twitter Card tags
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '" />' . "\n";

    if ( $twitter_username ) {
        echo '<meta name="twitter:site" content="@' . esc_attr( $twitter_username ) . '" />' . "\n";
    }

    if ( $image ) {
        echo '<meta name="twitter:image" content="' . esc_url( $image ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_add_twitter_card_tags', 5 );

/**
 * Add canonical URL
 */
function aqualuxe_add_canonical_url() {
    if ( ! is_singular() ) {
        return;
    }

    $canonical_url = get_permalink();
    echo '<link rel="canonical" href="' . esc_url( $canonical_url ) . '" />' . "\n";
}
add_action( 'wp_head', 'aqualuxe_add_canonical_url', 5 );

/**
 * Add meta description
 */
function aqualuxe_add_meta_description() {
    global $post;

    // Don't add meta description if Yoast SEO is active
    if ( defined( 'WPSEO_VERSION' ) ) {
        return;
    }

    if ( is_singular() ) {
        $description = wp_strip_all_tags( get_the_excerpt( $post->ID ) );
        if ( ! $description ) {
            $description = wp_trim_words( wp_strip_all_tags( $post->post_content ), 30, '...' );
        }
    } elseif ( is_category() || is_tag() || is_tax() ) {
        $description = wp_strip_all_tags( term_description() );
    } elseif ( is_home() || is_front_page() ) {
        $description = get_bloginfo( 'description' );
    }

    if ( ! empty( $description ) ) {
        echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_add_meta_description', 1 );

/**
 * Add robots meta tag
 */
function aqualuxe_add_robots_meta() {
    // Don't add robots meta if Yoast SEO is active
    if ( defined( 'WPSEO_VERSION' ) ) {
        return;
    }

    $robots = array();

    if ( is_search() || is_404() ) {
        $robots[] = 'noindex';
        $robots[] = 'nofollow';
    } elseif ( is_author() || is_date() ) {
        $robots[] = 'noindex';
        $robots[] = 'follow';
    } else {
        $robots[] = 'index';
        $robots[] = 'follow';
    }

    // Allow filtering of robots meta
    $robots = apply_filters( 'aqualuxe_robots_meta', $robots );

    if ( ! empty( $robots ) ) {
        echo '<meta name="robots" content="' . esc_attr( implode( ', ', $robots ) ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_add_robots_meta', 1 );

/**
 * Add customizer options for SEO
 */
function aqualuxe_seo_customizer_options( $wp_customize ) {
    // Add SEO section
    $wp_customize->add_section( 'aqualuxe_seo', array(
        'title'    => __( 'SEO Options', 'aqualuxe' ),
        'priority' => 120,
    ) );

    // Twitter username
    $wp_customize->add_setting( 'aqualuxe_twitter_username', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'aqualuxe_twitter_username', array(
        'label'    => __( 'Twitter Username (without @)', 'aqualuxe' ),
        'section'  => 'aqualuxe_seo',
        'type'     => 'text',
    ) );

    // Default OG image
    $wp_customize->add_setting( 'aqualuxe_default_og_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'aqualuxe_default_og_image', array(
        'label'    => __( 'Default Open Graph Image', 'aqualuxe' ),
        'description' => __( 'Used when no featured image is set', 'aqualuxe' ),
        'section'  => 'aqualuxe_seo',
    ) ) );

    // Default Twitter image
    $wp_customize->add_setting( 'aqualuxe_default_twitter_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'aqualuxe_default_twitter_image', array(
        'label'    => __( 'Default Twitter Card Image', 'aqualuxe' ),
        'description' => __( 'Used when no featured image is set', 'aqualuxe' ),
        'section'  => 'aqualuxe_seo',
    ) ) );
}
add_action( 'customize_register', 'aqualuxe_seo_customizer_options' );