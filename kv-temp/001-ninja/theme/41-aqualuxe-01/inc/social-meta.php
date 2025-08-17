<?php
/**
 * Social Media Meta Tags
 *
 * Functions for adding Open Graph and Twitter Card meta tags.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class AquaLuxe_Social_Meta
 */
class AquaLuxe_Social_Meta {

    /**
     * Constructor
     */
    public function __construct() {
        // Add Open Graph and Twitter Card meta tags
        add_action( 'wp_head', array( $this, 'add_social_meta_tags' ), 5 );
    }

    /**
     * Add Open Graph and Twitter Card meta tags
     */
    public function add_social_meta_tags() {
        // Skip if Yoast SEO is active
        if ( defined( 'WPSEO_VERSION' ) ) {
            return;
        }
        
        // Skip if Rank Math SEO is active
        if ( class_exists( 'RankMath' ) ) {
            return;
        }
        
        // Skip if All in One SEO Pack is active
        if ( class_exists( 'All_in_One_SEO_Pack' ) ) {
            return;
        }
        
        // Get meta data
        $meta = $this->get_meta_data();
        
        // Output meta tags
        $this->output_meta_tags( $meta );
    }

    /**
     * Get meta data
     *
     * @return array Meta data.
     */
    private function get_meta_data() {
        $meta = array();
        
        // Default values
        $meta['title'] = wp_get_document_title();
        $meta['description'] = get_bloginfo( 'description' );
        $meta['url'] = home_url( $_SERVER['REQUEST_URI'] );
        $meta['site_name'] = get_bloginfo( 'name' );
        $meta['locale'] = get_locale();
        $meta['type'] = 'website';
        $meta['image'] = '';
        $meta['image_width'] = '';
        $meta['image_height'] = '';
        $meta['twitter_card'] = 'summary_large_image';
        $meta['twitter_site'] = '';
        $meta['twitter_creator'] = '';
        
        // Get Twitter username from theme options
        $twitter_username = get_theme_mod( 'aqualuxe_twitter_username' );
        
        if ( $twitter_username ) {
            $meta['twitter_site'] = '@' . str_replace( '@', '', $twitter_username );
        }
        
        // Get default image from theme options
        $default_image_id = get_theme_mod( 'aqualuxe_default_social_image' );
        
        if ( $default_image_id ) {
            $default_image = wp_get_attachment_image_src( $default_image_id, 'full' );
            
            if ( $default_image ) {
                $meta['image'] = $default_image[0];
                $meta['image_width'] = $default_image[1];
                $meta['image_height'] = $default_image[2];
            }
        }
        
        // Front page
        if ( is_front_page() ) {
            $meta['title'] = get_bloginfo( 'name' );
            $meta['description'] = get_bloginfo( 'description' );
            
            // Get featured image from front page
            $front_page_id = get_option( 'page_on_front' );
            
            if ( $front_page_id && has_post_thumbnail( $front_page_id ) ) {
                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $front_page_id ), 'full' );
                
                if ( $image ) {
                    $meta['image'] = $image[0];
                    $meta['image_width'] = $image[1];
                    $meta['image_height'] = $image[2];
                }
            }
        }
        
        // Blog page
        elseif ( is_home() ) {
            $blog_page_id = get_option( 'page_for_posts' );
            
            if ( $blog_page_id ) {
                $meta['title'] = get_the_title( $blog_page_id );
                $meta['description'] = get_the_excerpt( $blog_page_id );
                
                // Get featured image from blog page
                if ( has_post_thumbnail( $blog_page_id ) ) {
                    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $blog_page_id ), 'full' );
                    
                    if ( $image ) {
                        $meta['image'] = $image[0];
                        $meta['image_width'] = $image[1];
                        $meta['image_height'] = $image[2];
                    }
                }
            }
        }
        
        // Single post
        elseif ( is_singular( 'post' ) ) {
            $meta['title'] = get_the_title();
            $meta['description'] = $this->get_excerpt_by_id( get_the_ID() );
            $meta['type'] = 'article';
            $meta['url'] = get_permalink();
            
            // Get author Twitter username
            $author_twitter = get_the_author_meta( 'twitter' );
            
            if ( $author_twitter ) {
                $meta['twitter_creator'] = '@' . str_replace( '@', '', $author_twitter );
            }
            
            // Get featured image
            if ( has_post_thumbnail() ) {
                $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
                
                if ( $image ) {
                    $meta['image'] = $image[0];
                    $meta['image_width'] = $image[1];
                    $meta['image_height'] = $image[2];
                }
            }
            
            // Add article meta
            $meta['article:published_time'] = get_the_date( 'c' );
            $meta['article:modified_time'] = get_the_modified_date( 'c' );
            $meta['article:author'] = get_the_author();
            
            // Add article section (category)
            $categories = get_the_category();
            
            if ( $categories ) {
                $meta['article:section'] = $categories[0]->name;
            }
            
            // Add article tags
            $tags = get_the_tags();
            
            if ( $tags ) {
                $meta['article:tag'] = array();
                
                foreach ( $tags as $tag ) {
                    $meta['article:tag'][] = $tag->name;
                }
            }
        }
        
        // Single page
        elseif ( is_singular( 'page' ) ) {
            $meta['title'] = get_the_title();
            $meta['description'] = $this->get_excerpt_by_id( get_the_ID() );
            $meta['url'] = get_permalink();
            
            // Get featured image
            if ( has_post_thumbnail() ) {
                $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
                
                if ( $image ) {
                    $meta['image'] = $image[0];
                    $meta['image_width'] = $image[1];
                    $meta['image_height'] = $image[2];
                }
            }
        }
        
        // WooCommerce product
        elseif ( function_exists( 'is_product' ) && is_product() ) {
            $product = wc_get_product();
            
            if ( $product ) {
                $meta['title'] = $product->get_name();
                $meta['description'] = wp_strip_all_tags( $product->get_short_description() );
                $meta['type'] = 'product';
                $meta['url'] = get_permalink();
                
                // Add product meta
                $meta['product:price:amount'] = $product->get_price();
                $meta['product:price:currency'] = get_woocommerce_currency();
                
                if ( $product->is_in_stock() ) {
                    $meta['product:availability'] = 'in stock';
                } else {
                    $meta['product:availability'] = 'out of stock';
                }
                
                // Get product image
                if ( has_post_thumbnail() ) {
                    $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
                    
                    if ( $image ) {
                        $meta['image'] = $image[0];
                        $meta['image_width'] = $image[1];
                        $meta['image_height'] = $image[2];
                    }
                }
            }
        }
        
        // Category archive
        elseif ( is_category() ) {
            $category = get_queried_object();
            
            $meta['title'] = single_cat_title( '', false );
            $meta['description'] = wp_strip_all_tags( category_description() );
            $meta['url'] = get_category_link( $category->term_id );
        }
        
        // Tag archive
        elseif ( is_tag() ) {
            $tag = get_queried_object();
            
            $meta['title'] = single_tag_title( '', false );
            $meta['description'] = wp_strip_all_tags( tag_description() );
            $meta['url'] = get_tag_link( $tag->term_id );
        }
        
        // Author archive
        elseif ( is_author() ) {
            $author_id = get_query_var( 'author' );
            
            $meta['title'] = get_the_author_meta( 'display_name', $author_id );
            $meta['description'] = wp_strip_all_tags( get_the_author_meta( 'description', $author_id ) );
            $meta['url'] = get_author_posts_url( $author_id );
            
            // Get author Twitter username
            $author_twitter = get_the_author_meta( 'twitter', $author_id );
            
            if ( $author_twitter ) {
                $meta['twitter_creator'] = '@' . str_replace( '@', '', $author_twitter );
            }
        }
        
        // WooCommerce shop page
        elseif ( function_exists( 'is_shop' ) && is_shop() ) {
            $shop_page_id = wc_get_page_id( 'shop' );
            
            if ( $shop_page_id ) {
                $meta['title'] = get_the_title( $shop_page_id );
                $meta['description'] = get_the_excerpt( $shop_page_id );
                $meta['url'] = get_permalink( $shop_page_id );
                
                // Get featured image from shop page
                if ( has_post_thumbnail( $shop_page_id ) ) {
                    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $shop_page_id ), 'full' );
                    
                    if ( $image ) {
                        $meta['image'] = $image[0];
                        $meta['image_width'] = $image[1];
                        $meta['image_height'] = $image[2];
                    }
                }
            }
        }
        
        // WooCommerce product category
        elseif ( function_exists( 'is_product_category' ) && is_product_category() ) {
            $category = get_queried_object();
            
            $meta['title'] = single_term_title( '', false );
            $meta['description'] = wp_strip_all_tags( category_description() );
            $meta['url'] = get_term_link( $category );
            
            // Get category thumbnail
            $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
            
            if ( $thumbnail_id ) {
                $image = wp_get_attachment_image_src( $thumbnail_id, 'full' );
                
                if ( $image ) {
                    $meta['image'] = $image[0];
                    $meta['image_width'] = $image[1];
                    $meta['image_height'] = $image[2];
                }
            }
        }
        
        // WooCommerce product tag
        elseif ( function_exists( 'is_product_tag' ) && is_product_tag() ) {
            $tag = get_queried_object();
            
            $meta['title'] = single_term_title( '', false );
            $meta['description'] = wp_strip_all_tags( tag_description() );
            $meta['url'] = get_term_link( $tag );
        }
        
        // Search results
        elseif ( is_search() ) {
            $meta['title'] = sprintf( __( 'Search results for: %s', 'aqualuxe' ), get_search_query() );
            $meta['description'] = sprintf( __( 'Search results for: %s', 'aqualuxe' ), get_search_query() );
            $meta['url'] = get_search_link( get_search_query() );
        }
        
        // 404 page
        elseif ( is_404() ) {
            $meta['title'] = __( 'Page not found', 'aqualuxe' );
            $meta['description'] = __( 'The page you are looking for does not exist.', 'aqualuxe' );
            $meta['url'] = home_url( $_SERVER['REQUEST_URI'] );
        }
        
        // Limit description length
        if ( isset( $meta['description'] ) ) {
            $meta['description'] = wp_trim_words( $meta['description'], 30, '...' );
        }
        
        return $meta;
    }

    /**
     * Output meta tags
     *
     * @param array $meta Meta data.
     */
    private function output_meta_tags( $meta ) {
        // Open Graph meta tags
        echo '<meta property="og:title" content="' . esc_attr( $meta['title'] ) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr( $meta['description'] ) . '" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url( $meta['url'] ) . '" />' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr( $meta['site_name'] ) . '" />' . "\n";
        echo '<meta property="og:locale" content="' . esc_attr( $meta['locale'] ) . '" />' . "\n";
        echo '<meta property="og:type" content="' . esc_attr( $meta['type'] ) . '" />' . "\n";
        
        // Image meta tags
        if ( ! empty( $meta['image'] ) ) {
            echo '<meta property="og:image" content="' . esc_url( $meta['image'] ) . '" />' . "\n";
            
            if ( ! empty( $meta['image_width'] ) && ! empty( $meta['image_height'] ) ) {
                echo '<meta property="og:image:width" content="' . esc_attr( $meta['image_width'] ) . '" />' . "\n";
                echo '<meta property="og:image:height" content="' . esc_attr( $meta['image_height'] ) . '" />' . "\n";
            }
        }
        
        // Article meta tags
        if ( $meta['type'] === 'article' ) {
            if ( ! empty( $meta['article:published_time'] ) ) {
                echo '<meta property="article:published_time" content="' . esc_attr( $meta['article:published_time'] ) . '" />' . "\n";
            }
            
            if ( ! empty( $meta['article:modified_time'] ) ) {
                echo '<meta property="article:modified_time" content="' . esc_attr( $meta['article:modified_time'] ) . '" />' . "\n";
            }
            
            if ( ! empty( $meta['article:author'] ) ) {
                echo '<meta property="article:author" content="' . esc_attr( $meta['article:author'] ) . '" />' . "\n";
            }
            
            if ( ! empty( $meta['article:section'] ) ) {
                echo '<meta property="article:section" content="' . esc_attr( $meta['article:section'] ) . '" />' . "\n";
            }
            
            if ( ! empty( $meta['article:tag'] ) ) {
                foreach ( $meta['article:tag'] as $tag ) {
                    echo '<meta property="article:tag" content="' . esc_attr( $tag ) . '" />' . "\n";
                }
            }
        }
        
        // Product meta tags
        if ( $meta['type'] === 'product' ) {
            if ( ! empty( $meta['product:price:amount'] ) ) {
                echo '<meta property="product:price:amount" content="' . esc_attr( $meta['product:price:amount'] ) . '" />' . "\n";
            }
            
            if ( ! empty( $meta['product:price:currency'] ) ) {
                echo '<meta property="product:price:currency" content="' . esc_attr( $meta['product:price:currency'] ) . '" />' . "\n";
            }
            
            if ( ! empty( $meta['product:availability'] ) ) {
                echo '<meta property="product:availability" content="' . esc_attr( $meta['product:availability'] ) . '" />' . "\n";
            }
        }
        
        // Twitter Card meta tags
        echo '<meta name="twitter:card" content="' . esc_attr( $meta['twitter_card'] ) . '" />' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr( $meta['title'] ) . '" />' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr( $meta['description'] ) . '" />' . "\n";
        
        if ( ! empty( $meta['twitter_site'] ) ) {
            echo '<meta name="twitter:site" content="' . esc_attr( $meta['twitter_site'] ) . '" />' . "\n";
        }
        
        if ( ! empty( $meta['twitter_creator'] ) ) {
            echo '<meta name="twitter:creator" content="' . esc_attr( $meta['twitter_creator'] ) . '" />' . "\n";
        }
        
        if ( ! empty( $meta['image'] ) ) {
            echo '<meta name="twitter:image" content="' . esc_url( $meta['image'] ) . '" />' . "\n";
        }
    }

    /**
     * Get excerpt by post ID
     *
     * @param int $post_id Post ID.
     * @return string Post excerpt.
     */
    private function get_excerpt_by_id( $post_id ) {
        $post = get_post( $post_id );
        
        if ( ! $post ) {
            return '';
        }
        
        if ( $post->post_excerpt ) {
            return $post->post_excerpt;
        }
        
        $content = $post->post_content;
        $content = strip_shortcodes( $content );
        $content = excerpt_remove_blocks( $content );
        $content = wp_strip_all_tags( $content );
        $content = str_replace( ']]>', ']]&gt;', $content );
        $excerpt_length = apply_filters( 'excerpt_length', 55 );
        $excerpt_more = apply_filters( 'excerpt_more', ' [&hellip;]' );
        $content = wp_trim_words( $content, $excerpt_length, $excerpt_more );
        
        return $content;
    }
}

// Initialize the class
new AquaLuxe_Social_Meta();