<?php
/**
 * SEO improvements for AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Add schema.org structured data for products
 */
function aqualuxe_add_product_schema() {
    if ( ! is_singular( 'product' ) ) {
        return;
    }

    global $product;

    if ( ! is_a( $product, 'WC_Product' ) ) {
        $product = wc_get_product( get_the_ID() );
        if ( ! is_a( $product, 'WC_Product' ) ) {
            return;
        }
    }

    $schema = array(
        '@context'    => 'https://schema.org/',
        '@type'       => 'Product',
        'name'        => $product->get_name(),
        'description' => wp_strip_all_tags( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ),
        'sku'         => $product->get_sku(),
        'mpn'         => $product->get_meta( '_aqualuxe_mpn', true ) ?: $product->get_sku(),
        'brand'       => array(
            '@type' => 'Brand',
            'name'  => wp_strip_all_tags( $product->get_meta( '_aqualuxe_brand', true ) ?: get_bloginfo( 'name' ) ),
        ),
    );

    // Add product image
    if ( has_post_thumbnail() ) {
        $image_id  = get_post_thumbnail_id();
        $image_url = wp_get_attachment_image_url( $image_id, 'full' );
        if ( $image_url ) {
            $schema['image'] = $image_url;
        }
    }

    // Add product offers
    if ( $product->is_in_stock() ) {
        $availability = 'https://schema.org/InStock';
    } else {
        $availability = 'https://schema.org/OutOfStock';
    }

    $schema['offers'] = array(
        '@type'           => 'Offer',
        'price'           => $product->get_price(),
        'priceCurrency'   => get_woocommerce_currency(),
        'priceValidUntil' => date( 'Y-m-d', strtotime( '+1 year' ) ),
        'availability'    => $availability,
        'url'             => get_permalink(),
    );

    // Add product reviews
    if ( $product->get_review_count() ) {
        $schema['aggregateRating'] = array(
            '@type'       => 'AggregateRating',
            'ratingValue' => $product->get_average_rating(),
            'reviewCount' => $product->get_review_count(),
        );
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}
add_action( 'wp_head', 'aqualuxe_add_product_schema' );

/**
 * Add breadcrumb structured data
 */
function aqualuxe_add_breadcrumb_schema() {
    if ( function_exists( 'woocommerce_breadcrumb' ) && ( is_shop() || is_product_category() || is_product_tag() || is_product() ) ) {
        // WooCommerce already adds breadcrumb schema
        return;
    }

    if ( is_front_page() ) {
        return;
    }

    $breadcrumbs = array();
    $breadcrumbs[] = array(
        '@type'    => 'ListItem',
        'position' => 1,
        'name'     => __( 'Home', 'aqualuxe' ),
        'item'     => home_url(),
    );

    $position = 2;

    if ( is_category() || is_tag() || is_tax() ) {
        $term = get_queried_object();
        $breadcrumbs[] = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => $term->name,
            'item'     => get_term_link( $term ),
        );
    } elseif ( is_singular() ) {
        $post_type = get_post_type();
        
        if ( $post_type !== 'page' && $post_type !== 'post' ) {
            $post_type_obj = get_post_type_object( $post_type );
            $archive_link  = get_post_type_archive_link( $post_type );
            
            if ( $archive_link ) {
                $breadcrumbs[] = array(
                    '@type'    => 'ListItem',
                    'position' => $position,
                    'name'     => $post_type_obj->labels->name,
                    'item'     => $archive_link,
                );
                $position++;
            }
        } elseif ( $post_type === 'post' ) {
            $breadcrumbs[] = array(
                '@type'    => 'ListItem',
                'position' => $position,
                'name'     => __( 'Blog', 'aqualuxe' ),
                'item'     => get_permalink( get_option( 'page_for_posts' ) ),
            );
            $position++;
        }

        $breadcrumbs[] = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => get_the_title(),
            'item'     => get_permalink(),
        );
    } elseif ( is_post_type_archive() ) {
        $post_type = get_post_type();
        $post_type_obj = get_post_type_object( $post_type );
        
        $breadcrumbs[] = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => $post_type_obj->labels->name,
            'item'     => get_post_type_archive_link( $post_type ),
        );
    } elseif ( is_search() ) {
        $breadcrumbs[] = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => sprintf( __( 'Search results for: %s', 'aqualuxe' ), get_search_query() ),
            'item'     => get_search_link( get_search_query() ),
        );
    } elseif ( is_404() ) {
        $breadcrumbs[] = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => __( 'Page not found', 'aqualuxe' ),
            'item'     => home_url( $_SERVER['REQUEST_URI'] ),
        );
    }

    $schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $breadcrumbs,
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}
add_action( 'wp_head', 'aqualuxe_add_breadcrumb_schema' );

/**
 * Add organization schema
 */
function aqualuxe_add_organization_schema() {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => get_bloginfo( 'name' ),
        'url'      => home_url(),
    );

    // Add logo
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
        if ( $logo_url ) {
            $schema['logo'] = $logo_url;
        }
    }

    // Add social profiles
    $social_profiles = array();
    
    // These would typically come from theme options or customizer settings
    $facebook  = get_theme_mod( 'aqualuxe_facebook_url' );
    $twitter   = get_theme_mod( 'aqualuxe_twitter_url' );
    $instagram = get_theme_mod( 'aqualuxe_instagram_url' );
    $youtube   = get_theme_mod( 'aqualuxe_youtube_url' );
    $linkedin  = get_theme_mod( 'aqualuxe_linkedin_url' );
    
    if ( $facebook ) {
        $social_profiles[] = $facebook;
    }
    if ( $twitter ) {
        $social_profiles[] = $twitter;
    }
    if ( $instagram ) {
        $social_profiles[] = $instagram;
    }
    if ( $youtube ) {
        $social_profiles[] = $youtube;
    }
    if ( $linkedin ) {
        $social_profiles[] = $linkedin;
    }
    
    if ( ! empty( $social_profiles ) ) {
        $schema['sameAs'] = $social_profiles;
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}
add_action( 'wp_head', 'aqualuxe_add_organization_schema' );

/**
 * Add local business schema
 */
function aqualuxe_add_local_business_schema() {
    // Only add on contact page or homepage
    if ( ! is_page( 'contact' ) && ! is_front_page() ) {
        return;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'LocalBusiness',
        'name'     => get_bloginfo( 'name' ),
        'url'      => home_url(),
        'address'  => array(
            '@type'           => 'PostalAddress',
            'streetAddress'   => '123 Aquarium Street',
            'addressLocality' => 'Ocean City',
            'addressRegion'   => 'CA',
            'postalCode'      => '90210',
            'addressCountry'  => 'US',
        ),
        'telephone' => '+15551234567',
        'email'     => 'info@aqualuxe.example.com',
        'openingHoursSpecification' => array(
            array(
                '@type'     => 'OpeningHoursSpecification',
                'dayOfWeek' => array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'),
                'opens'     => '09:00',
                'closes'    => '18:00',
            ),
            array(
                '@type'     => 'OpeningHoursSpecification',
                'dayOfWeek' => 'Saturday',
                'opens'     => '10:00',
                'closes'    => '16:00',
            ),
            array(
                '@type'     => 'OpeningHoursSpecification',
                'dayOfWeek' => 'Sunday',
                'opens'     => '00:00',
                'closes'    => '00:00',
            ),
        ),
    );

    // Add logo
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
        if ( $logo_url ) {
            $schema['logo'] = $logo_url;
            $schema['image'] = $logo_url;
        }
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}
add_action( 'wp_head', 'aqualuxe_add_local_business_schema' );

/**
 * Add article schema for blog posts
 */
function aqualuxe_add_article_schema() {
    if ( ! is_singular( 'post' ) ) {
        return;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'Article',
        'headline' => get_the_title(),
        'url'      => get_permalink(),
        'datePublished' => get_the_date( 'c' ),
        'dateModified'  => get_the_modified_date( 'c' ),
        'author' => array(
            '@type' => 'Person',
            'name'  => get_the_author(),
            'url'   => get_author_posts_url( get_the_author_meta( 'ID' ) ),
        ),
        'publisher' => array(
            '@type' => 'Organization',
            'name'  => get_bloginfo( 'name' ),
            'logo'  => array(
                '@type' => 'ImageObject',
                'url'   => wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ),
            ),
        ),
    );

    // Add featured image
    if ( has_post_thumbnail() ) {
        $image_id  = get_post_thumbnail_id();
        $image_url = wp_get_attachment_image_url( $image_id, 'full' );
        if ( $image_url ) {
            $schema['image'] = $image_url;
        }
    }

    // Add categories as keywords
    $categories = get_the_category();
    if ( ! empty( $categories ) ) {
        $keywords = array();
        foreach ( $categories as $category ) {
            $keywords[] = $category->name;
        }
        $schema['keywords'] = implode( ', ', $keywords );
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}
add_action( 'wp_head', 'aqualuxe_add_article_schema' );

/**
 * Add canonical URLs
 */
function aqualuxe_add_canonical_url() {
    if ( ! is_singular() ) {
        return;
    }

    $canonical_url = get_permalink();
    
    // If using Yoast SEO or similar plugins, they will handle canonical URLs
    if ( ! function_exists( 'wpseo_init' ) && ! function_exists( 'rank_math' ) ) {
        echo '<link rel="canonical" href="' . esc_url( $canonical_url ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_add_canonical_url', 1 );

/**
 * Add social media meta tags
 */
function aqualuxe_add_social_meta_tags() {
    if ( ! is_singular() ) {
        return;
    }

    global $post;

    $title = get_the_title();
    $description = wp_strip_all_tags( get_the_excerpt() ? get_the_excerpt() : wp_trim_words( $post->post_content, 55, '...' ) );
    $url = get_permalink();
    
    $image = '';
    if ( has_post_thumbnail() ) {
        $image = wp_get_attachment_image_url( get_post_thumbnail_id(), 'large' );
    }

    // Open Graph meta tags
    echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
    echo '<meta property="og:type" content="' . ( is_singular( 'post' ) ? 'article' : 'website' ) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
    
    if ( $image ) {
        echo '<meta property="og:image" content="' . esc_url( $image ) . '" />' . "\n";
    }

    // Twitter Card meta tags
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '" />' . "\n";
    
    if ( $image ) {
        echo '<meta name="twitter:image" content="' . esc_url( $image ) . '" />' . "\n";
    }

    // Twitter site username (from theme options)
    $twitter_username = get_theme_mod( 'aqualuxe_twitter_username' );
    if ( $twitter_username ) {
        echo '<meta name="twitter:site" content="@' . esc_attr( $twitter_username ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_add_social_meta_tags' );

/**
 * Add hreflang tags for multilingual support
 */
function aqualuxe_add_hreflang_tags() {
    // Only add if multilingual support is enabled
    if ( ! function_exists( 'aqualuxe_get_available_languages' ) ) {
        return;
    }

    $languages = aqualuxe_get_available_languages();
    $current_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    
    foreach ( $languages as $lang_code => $lang_name ) {
        $lang_url = aqualuxe_get_translated_url( $current_url, $lang_code );
        echo '<link rel="alternate" hreflang="' . esc_attr( $lang_code ) . '" href="' . esc_url( $lang_url ) . '" />' . "\n";
    }
    
    // Add x-default hreflang tag
    $default_lang = aqualuxe_get_default_language();
    $default_url = aqualuxe_get_translated_url( $current_url, $default_lang );
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $default_url ) . '" />' . "\n";
}
add_action( 'wp_head', 'aqualuxe_add_hreflang_tags' );

/**
 * Add XML sitemap support
 */
function aqualuxe_add_xml_sitemap() {
    // If Yoast SEO or similar plugins are active, they will handle sitemaps
    if ( function_exists( 'wpseo_init' ) || function_exists( 'rank_math' ) ) {
        return;
    }

    // Add sitemap to robots.txt
    add_filter( 'robots_txt', function( $output ) {
        $output .= "\nSitemap: " . home_url( '/sitemap.xml' ) . "\n";
        return $output;
    });

    // Register sitemap rewrite rule
    add_action( 'init', function() {
        add_rewrite_rule( '^sitemap\.xml$', 'index.php?aqualuxe_sitemap=1', 'top' );
    });

    // Register sitemap query var
    add_filter( 'query_vars', function( $vars ) {
        $vars[] = 'aqualuxe_sitemap';
        return $vars;
    });

    // Generate sitemap content
    add_action( 'template_redirect', function() {
        if ( get_query_var( 'aqualuxe_sitemap' ) ) {
            header( 'Content-Type: application/xml; charset=UTF-8' );
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            
            // Add homepage
            echo '<url>';
            echo '<loc>' . home_url( '/' ) . '</loc>';
            echo '<lastmod>' . date( 'c', strtotime( get_lastpostmodified( 'GMT' ) ) ) . '</lastmod>';
            echo '<changefreq>daily</changefreq>';
            echo '<priority>1.0</priority>';
            echo '</url>';
            
            // Add posts
            $posts = get_posts( array(
                'post_type'      => 'post',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
            ) );
            
            foreach ( $posts as $post ) {
                echo '<url>';
                echo '<loc>' . get_permalink( $post->ID ) . '</loc>';
                echo '<lastmod>' . date( 'c', strtotime( $post->post_modified_gmt ) ) . '</lastmod>';
                echo '<changefreq>weekly</changefreq>';
                echo '<priority>0.8</priority>';
                echo '</url>';
            }
            
            // Add pages
            $pages = get_posts( array(
                'post_type'      => 'page',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
            ) );
            
            foreach ( $pages as $page ) {
                echo '<url>';
                echo '<loc>' . get_permalink( $page->ID ) . '</loc>';
                echo '<lastmod>' . date( 'c', strtotime( $page->post_modified_gmt ) ) . '</lastmod>';
                echo '<changefreq>monthly</changefreq>';
                echo '<priority>0.6</priority>';
                echo '</url>';
            }
            
            // Add products if WooCommerce is active
            if ( class_exists( 'WooCommerce' ) ) {
                $products = get_posts( array(
                    'post_type'      => 'product',
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                ) );
                
                foreach ( $products as $product ) {
                    echo '<url>';
                    echo '<loc>' . get_permalink( $product->ID ) . '</loc>';
                    echo '<lastmod>' . date( 'c', strtotime( $product->post_modified_gmt ) ) . '</lastmod>';
                    echo '<changefreq>weekly</changefreq>';
                    echo '<priority>0.8</priority>';
                    echo '</url>';
                }
                
                // Add product categories
                $product_cats = get_terms( array(
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => true,
                ) );
                
                foreach ( $product_cats as $product_cat ) {
                    echo '<url>';
                    echo '<loc>' . get_term_link( $product_cat ) . '</loc>';
                    echo '<changefreq>weekly</changefreq>';
                    echo '<priority>0.7</priority>';
                    echo '</url>';
                }
            }
            
            // Add categories
            $categories = get_categories( array(
                'hide_empty' => true,
            ) );
            
            foreach ( $categories as $category ) {
                echo '<url>';
                echo '<loc>' . get_category_link( $category->term_id ) . '</loc>';
                echo '<changefreq>weekly</changefreq>';
                echo '<priority>0.5</priority>';
                echo '</url>';
            }
            
            echo '</urlset>';
            exit;
        }
    });
}
add_action( 'after_setup_theme', 'aqualuxe_add_xml_sitemap' );