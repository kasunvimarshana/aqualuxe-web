<?php
/**
 * AquaLuxe SEO Enhancements
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AquaLuxe_SEO {
    
    /**
     * Initialize SEO features
     */
    public static function init() {
        add_action( 'wp_head', array( __CLASS__, 'add_meta_tags' ), 1 );
        add_action( 'wp_head', array( __CLASS__, 'add_schema_markup' ), 2 );
        add_action( 'wp_head', array( __CLASS__, 'add_open_graph_tags' ), 3 );
        add_filter( 'wp_title', array( __CLASS__, 'optimize_title' ), 10, 2 );
        add_filter( 'excerpt_more', array( __CLASS__, 'custom_excerpt_more' ) );
        add_filter( 'excerpt_length', array( __CLASS__, 'custom_excerpt_length' ), 999 );
    }
    
    /**
     * Add meta tags
     */
    public static function add_meta_tags() {
        // Meta description
        if ( is_singular() ) {
            global $post;
            $description = get_post_meta( $post->ID, '_aqualuxe_meta_description', true );
            if ( ! $description ) {
                $description = wp_trim_words( $post->post_content, 30 );
            }
            echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
        }
        
        // Meta keywords
        if ( is_singular() ) {
            global $post;
            $keywords = get_post_meta( $post->ID, '_aqualuxe_meta_keywords', true );
            if ( $keywords ) {
                echo '<meta name="keywords" content="' . esc_attr( $keywords ) . '">' . "\n";
            }
        }
        
        // Canonical URL
        if ( is_singular() ) {
            global $post;
            echo '<link rel="canonical" href="' . esc_url( get_permalink( $post->ID ) ) . '">' . "\n";
        }
        
        // Viewport
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
        
        // Theme color
        echo '<meta name="theme-color" content="#0077BE">' . "\n";
    }
    
    /**
     * Add schema markup
     */
    public static function add_schema_markup() {
        if ( is_front_page() ) {
            self::add_website_schema();
        } elseif ( is_singular( 'product' ) ) {
            self::add_product_schema();
        } elseif ( is_singular() ) {
            self::add_article_schema();
        } elseif ( is_shop() || is_product_category() || is_product_tag() ) {
            self::add_product_collection_schema();
        }
    }
    
    /**
     * Add website schema markup
     */
    private static function add_website_schema() {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => get_bloginfo( 'name' ),
            'url' => home_url(),
            'description' => get_bloginfo( 'description' ),
        );
        
        echo '<script type="application/ld+json">' . json_encode( $schema ) . '</script>' . "\n";
    }
    
    /**
     * Add product schema markup
     */
    private static function add_product_schema() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }
        
        global $product;
        if ( ! $product ) {
            return;
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->get_name(),
            'description' => $product->get_short_description() ?: wp_trim_words( $product->get_description(), 30 ),
            'image' => wp_get_attachment_url( $product->get_image_id() ),
            'offers' => array(
                '@type' => 'Offer',
                'price' => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
                'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => $product->get_permalink(),
            ),
        );
        
        // Add brand if available
        $brand = get_post_meta( $product->get_id(), '_aqualuxe_brand', true );
        if ( $brand ) {
            $schema['brand'] = array(
                '@type' => 'Brand',
                'name' => $brand,
            );
        }
        
        // Add category if available
        $categories = wp_get_post_terms( $product->get_id(), 'product_cat' );
        if ( ! empty( $categories ) ) {
            $schema['category'] = $categories[0]->name;
        }
        
        echo '<script type="application/ld+json">' . json_encode( $schema ) . '</script>' . "\n";
    }
    
    /**
     * Add article schema markup
     */
    private static function add_article_schema() {
        global $post;
        if ( ! $post ) {
            return;
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id' => get_permalink( $post->ID ),
            ),
            'headline' => get_the_title( $post->ID ),
            'description' => wp_trim_words( $post->post_content, 30 ),
            'datePublished' => get_the_date( 'c', $post->ID ),
            'dateModified' => get_the_modified_date( 'c', $post->ID ),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author_meta( 'display_name', $post->post_author ),
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo( 'name' ),
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => get_custom_logo() ? wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0] : '',
                ),
            ),
        );
        
        // Add image if available
        if ( has_post_thumbnail( $post->ID ) ) {
            $schema['image'] = array(
                '@type' => 'ImageObject',
                'url' => get_the_post_thumbnail_url( $post->ID, 'full' ),
                'width' => 1200,
                'height' => 800,
            );
        }
        
        echo '<script type="application/ld+json">' . json_encode( $schema ) . '</script>' . "\n";
    }
    
    /**
     * Add product collection schema markup
     */
    private static function add_product_collection_schema() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => is_shop() ? __( 'Shop', 'aqualuxe' ) : get_the_archive_title(),
            'url' => get_current_url(),
        );
        
        // Add products to the collection
        $products = wc_get_products( array(
            'limit' => 12,
            'orderby' => 'date',
            'order' => 'DESC',
        ) );
        
        $items = array();
        foreach ( $products as $index => $product ) {
            $items[] = array(
                '@type' => 'ListItem',
                'position' => $index + 1,
                'url' => $product->get_permalink(),
            );
        }
        
        $schema['itemListElement'] = $items;
        
        echo '<script type="application/ld+json">' . json_encode( $schema ) . '</script>' . "\n";
    }
    
    /**
     * Add Open Graph tags
     */
    public static function add_open_graph_tags() {
        // Open Graph prefix
        echo '<meta prefix="og: http://ogp.me/ns#" property="og:locale" content="' . get_locale() . '">' . "\n";
        
        // Site name
        echo '<meta prefix="og: http://ogp.me/ns#" property="og:site_name" content="' . get_bloginfo( 'name' ) . '">' . "\n";
        
        // Title
        echo '<meta prefix="og: http://ogp.me/ns#" property="og:title" content="' . esc_attr( wp_get_document_title() ) . '">' . "\n";
        
        // Description
        if ( is_singular() ) {
            global $post;
            $description = get_post_meta( $post->ID, '_aqualuxe_meta_description', true );
            if ( ! $description ) {
                $description = wp_trim_words( $post->post_content, 30 );
            }
            echo '<meta prefix="og: http://ogp.me/ns#" property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
        }
        
        // URL
        echo '<meta prefix="og: http://ogp.me/ns#" property="og:url" content="' . esc_url( get_current_url() ) . '">' . "\n";
        
        // Type
        if ( is_front_page() ) {
            echo '<meta prefix="og: http://ogp.me/ns#" property="og:type" content="website">' . "\n";
        } elseif ( is_singular( 'product' ) ) {
            echo '<meta prefix="og: http://ogp.me/ns#" property="og:type" content="product">' . "\n";
        } else {
            echo '<meta prefix="og: http://ogp.me/ns#" property="og:type" content="article">' . "\n";
        }
        
        // Image
        if ( is_singular() ) {
            global $post;
            if ( has_post_thumbnail( $post->ID ) ) {
                $image_url = get_the_post_thumbnail_url( $post->ID, 'full' );
                echo '<meta prefix="og: http://ogp.me/ns#" property="og:image" content="' . esc_url( $image_url ) . '">' . "\n";
                echo '<meta prefix="og: http://ogp.me/ns#" property="og:image:secure_url" content="' . esc_url( $image_url ) . '">' . "\n";
            }
        }
        
        // Twitter Card
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr( wp_get_document_title() ) . '">' . "\n";
        
        if ( is_singular() ) {
            global $post;
            $description = get_post_meta( $post->ID, '_aqualuxe_meta_description', true );
            if ( ! $description ) {
                $description = wp_trim_words( $post->post_content, 30 );
            }
            echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
            
            if ( has_post_thumbnail( $post->ID ) ) {
                $image_url = get_the_post_thumbnail_url( $post->ID, 'full' );
                echo '<meta name="twitter:image" content="' . esc_url( $image_url ) . '">' . "\n";
            }
        }
    }
    
    /**
     * Optimize title
     */
    public static function optimize_title( $title, $sep ) {
        if ( is_feed() ) {
            return $title;
        }
        
        global $page, $paged;
        
        // Add the blog name
        $title .= get_bloginfo( 'name', 'display' );
        
        // Add the blog description for the home/front page
        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) ) {
            $title .= " $sep $site_description";
        }
        
        // Add a page number if necessary
        if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
            $title .= " $sep " . sprintf( __( 'Page %s', 'aqualuxe' ), max( $paged, $page ) );
        }
        
        return $title;
    }
    
    /**
     * Custom excerpt more
     */
    public static function custom_excerpt_more( $more ) {
        return '...';
    }
    
    /**
     * Custom excerpt length
     */
    public static function custom_excerpt_length( $length ) {
        return 30;
    }
}

// Initialize
AquaLuxe_SEO::init();