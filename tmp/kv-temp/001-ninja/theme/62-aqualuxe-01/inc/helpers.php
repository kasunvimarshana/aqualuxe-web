<?php
/**
 * AquaLuxe Helper Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Check if dark mode is enabled
 *
 * @return bool
 */
function aqualuxe_is_dark_mode() {
    // Check if dark mode module is active
    $module_loader = AquaLuxe_Module_Loader::instance();
    $dark_mode_module = $module_loader->get_active_module( 'dark-mode' );
    
    if ( $dark_mode_module ) {
        return $dark_mode_module->is_dark_mode();
    }
    
    return false;
}

/**
 * Get theme option
 *
 * @param string $option Option name
 * @param mixed  $default Default value
 * @return mixed
 */
function aqualuxe_get_option( $option, $default = null ) {
    return get_theme_mod( $option, $default );
}

/**
 * Get asset URL
 *
 * @param string $path Asset path
 * @return string
 */
function aqualuxe_get_asset_url( $path ) {
    $assets = AquaLuxe_Assets::instance();
    return $assets->get_asset_path( $path );
}

/**
 * Get asset version
 *
 * @param string $path Asset path
 * @return string
 */
function aqualuxe_get_asset_version( $path ) {
    $assets = AquaLuxe_Assets::instance();
    return $assets->get_asset_version( $path );
}

/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists( 'WooCommerce' );
}

/**
 * Get sidebar position
 *
 * @return string
 */
function aqualuxe_get_sidebar_position() {
    return aqualuxe_get_option( 'aqualuxe_sidebar_position', 'right' );
}

/**
 * Check if sidebar should be displayed
 *
 * @return bool
 */
function aqualuxe_display_sidebar() {
    $sidebar_position = aqualuxe_get_sidebar_position();
    
    if ( 'none' === $sidebar_position ) {
        return false;
    }
    
    // Don't display sidebar on WooCommerce pages if shop sidebar is disabled
    if ( aqualuxe_is_woocommerce_active() && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
        return aqualuxe_get_option( 'aqualuxe_shop_sidebar', true );
    }
    
    return is_active_sidebar( 'sidebar-1' );
}

/**
 * Get container class
 *
 * @return string
 */
function aqualuxe_get_container_class() {
    return 'container mx-auto px-4';
}

/**
 * Get content class
 *
 * @return string
 */
function aqualuxe_get_content_class() {
    $classes = 'site-content';
    
    if ( aqualuxe_display_sidebar() ) {
        $sidebar_position = aqualuxe_get_sidebar_position();
        
        if ( 'left' === $sidebar_position ) {
            $classes .= ' flex flex-wrap lg:flex-nowrap flex-row-reverse';
        } else {
            $classes .= ' flex flex-wrap lg:flex-nowrap';
        }
    }
    
    return $classes;
}

/**
 * Get main content class
 *
 * @return string
 */
function aqualuxe_get_main_content_class() {
    $classes = 'site-main w-full';
    
    if ( aqualuxe_display_sidebar() ) {
        $classes .= ' lg:w-3/4 lg:pr-8';
    }
    
    return $classes;
}

/**
 * Get sidebar class
 *
 * @return string
 */
function aqualuxe_get_sidebar_class() {
    $classes = 'widget-area w-full lg:w-1/4 mt-8 lg:mt-0';
    
    $sidebar_position = aqualuxe_get_sidebar_position();
    
    if ( 'left' === $sidebar_position ) {
        $classes .= ' lg:pr-8';
    }
    
    return $classes;
}

/**
 * Get post thumbnail
 *
 * @param string $size Thumbnail size
 * @return string
 */
function aqualuxe_get_post_thumbnail( $size = 'large' ) {
    if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
        return;
    }
    
    return get_the_post_thumbnail( null, $size );
}

/**
 * Get post thumbnail URL
 *
 * @param string $size Thumbnail size
 * @return string
 */
function aqualuxe_get_post_thumbnail_url( $size = 'large' ) {
    if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
        return '';
    }
    
    return get_the_post_thumbnail_url( null, $size );
}

/**
 * Get excerpt
 *
 * @param int $length Excerpt length
 * @return string
 */
function aqualuxe_get_excerpt( $length = 25 ) {
    $excerpt = get_the_excerpt();
    
    if ( ! $excerpt ) {
        $excerpt = get_the_content();
    }
    
    $excerpt = strip_shortcodes( $excerpt );
    $excerpt = strip_tags( $excerpt );
    $excerpt = substr( $excerpt, 0, $length );
    $excerpt = substr( $excerpt, 0, strripos( $excerpt, ' ' ) );
    $excerpt = trim( $excerpt );
    $excerpt .= '...';
    
    return $excerpt;
}

/**
 * Get blog layout
 *
 * @return string
 */
function aqualuxe_get_blog_layout() {
    return aqualuxe_get_option( 'aqualuxe_blog_layout', 'grid' );
}

/**
 * Get blog columns
 *
 * @return int
 */
function aqualuxe_get_blog_columns() {
    return aqualuxe_get_option( 'aqualuxe_blog_columns', 3 );
}

/**
 * Get blog excerpt length
 *
 * @return int
 */
function aqualuxe_get_blog_excerpt_length() {
    return aqualuxe_get_option( 'aqualuxe_blog_excerpt_length', 25 );
}

/**
 * Get shop columns
 *
 * @return int
 */
function aqualuxe_get_shop_columns() {
    return aqualuxe_get_option( 'aqualuxe_shop_columns', 4 );
}

/**
 * Get products per page
 *
 * @return int
 */
function aqualuxe_get_products_per_page() {
    return aqualuxe_get_option( 'aqualuxe_products_per_page', 12 );
}

/**
 * Get related products count
 *
 * @return int
 */
function aqualuxe_get_related_products_count() {
    return aqualuxe_get_option( 'aqualuxe_related_products_count', 4 );
}

/**
 * Get copyright text
 *
 * @return string
 */
function aqualuxe_get_copyright_text() {
    $copyright_text = aqualuxe_get_option( 'aqualuxe_copyright_text', sprintf( __( '© %s AquaLuxe. All rights reserved.', 'aqualuxe' ), date( 'Y' ) ) );
    
    return apply_filters( 'aqualuxe_copyright_text', $copyright_text );
}

/**
 * Get social links
 *
 * @return array
 */
function aqualuxe_get_social_links() {
    $social_links = array(
        'facebook'  => aqualuxe_get_option( 'aqualuxe_facebook_url', '' ),
        'twitter'   => aqualuxe_get_option( 'aqualuxe_twitter_url', '' ),
        'instagram' => aqualuxe_get_option( 'aqualuxe_instagram_url', '' ),
        'linkedin'  => aqualuxe_get_option( 'aqualuxe_linkedin_url', '' ),
        'youtube'   => aqualuxe_get_option( 'aqualuxe_youtube_url', '' ),
        'pinterest' => aqualuxe_get_option( 'aqualuxe_pinterest_url', '' ),
    );
    
    return array_filter( $social_links );
}

/**
 * Get contact info
 *
 * @return array
 */
function aqualuxe_get_contact_info() {
    $contact_info = array(
        'phone'   => aqualuxe_get_option( 'aqualuxe_phone', '' ),
        'email'   => aqualuxe_get_option( 'aqualuxe_email', '' ),
        'address' => aqualuxe_get_option( 'aqualuxe_address', '' ),
    );
    
    return array_filter( $contact_info );
}

/**
 * Get page title
 *
 * @return string
 */
function aqualuxe_get_page_title() {
    if ( is_home() ) {
        if ( get_option( 'page_for_posts' ) ) {
            return get_the_title( get_option( 'page_for_posts' ) );
        } else {
            return __( 'Blog', 'aqualuxe' );
        }
    } elseif ( is_archive() ) {
        return get_the_archive_title();
    } elseif ( is_search() ) {
        return sprintf( __( 'Search Results for: %s', 'aqualuxe' ), get_search_query() );
    } elseif ( is_404() ) {
        return __( 'Page Not Found', 'aqualuxe' );
    } else {
        return get_the_title();
    }
}

/**
 * Get breadcrumbs
 *
 * @return string
 */
function aqualuxe_get_breadcrumbs() {
    if ( is_front_page() ) {
        return;
    }
    
    $breadcrumbs = array();
    
    // Home
    $breadcrumbs[] = array(
        'text' => __( 'Home', 'aqualuxe' ),
        'url'  => home_url(),
    );
    
    if ( is_home() ) {
        // Blog
        $breadcrumbs[] = array(
            'text' => __( 'Blog', 'aqualuxe' ),
            'url'  => '',
        );
    } elseif ( is_category() ) {
        // Category
        $breadcrumbs[] = array(
            'text' => single_cat_title( '', false ),
            'url'  => '',
        );
    } elseif ( is_tag() ) {
        // Tag
        $breadcrumbs[] = array(
            'text' => single_tag_title( '', false ),
            'url'  => '',
        );
    } elseif ( is_author() ) {
        // Author
        $breadcrumbs[] = array(
            'text' => get_the_author(),
            'url'  => '',
        );
    } elseif ( is_year() ) {
        // Year
        $breadcrumbs[] = array(
            'text' => get_the_date( 'Y' ),
            'url'  => '',
        );
    } elseif ( is_month() ) {
        // Month
        $breadcrumbs[] = array(
            'text' => get_the_date( 'F Y' ),
            'url'  => '',
        );
    } elseif ( is_day() ) {
        // Day
        $breadcrumbs[] = array(
            'text' => get_the_date(),
            'url'  => '',
        );
    } elseif ( is_singular( 'post' ) ) {
        // Single post
        $categories = get_the_category();
        
        if ( ! empty( $categories ) ) {
            $category = $categories[0];
            
            $breadcrumbs[] = array(
                'text' => $category->name,
                'url'  => get_category_link( $category->term_id ),
            );
        }
        
        $breadcrumbs[] = array(
            'text' => get_the_title(),
            'url'  => '',
        );
    } elseif ( is_singular( 'page' ) ) {
        // Page
        $breadcrumbs[] = array(
            'text' => get_the_title(),
            'url'  => '',
        );
    } elseif ( is_search() ) {
        // Search
        $breadcrumbs[] = array(
            'text' => sprintf( __( 'Search Results for: %s', 'aqualuxe' ), get_search_query() ),
            'url'  => '',
        );
    } elseif ( is_404() ) {
        // 404
        $breadcrumbs[] = array(
            'text' => __( 'Page Not Found', 'aqualuxe' ),
            'url'  => '',
        );
    }
    
    // WooCommerce
    if ( aqualuxe_is_woocommerce_active() ) {
        if ( is_shop() ) {
            // Shop
            $breadcrumbs = array(
                array(
                    'text' => __( 'Home', 'aqualuxe' ),
                    'url'  => home_url(),
                ),
                array(
                    'text' => __( 'Shop', 'aqualuxe' ),
                    'url'  => '',
                ),
            );
        } elseif ( is_product_category() ) {
            // Product category
            $breadcrumbs = array(
                array(
                    'text' => __( 'Home', 'aqualuxe' ),
                    'url'  => home_url(),
                ),
                array(
                    'text' => __( 'Shop', 'aqualuxe' ),
                    'url'  => get_permalink( wc_get_page_id( 'shop' ) ),
                ),
                array(
                    'text' => single_cat_title( '', false ),
                    'url'  => '',
                ),
            );
        } elseif ( is_product_tag() ) {
            // Product tag
            $breadcrumbs = array(
                array(
                    'text' => __( 'Home', 'aqualuxe' ),
                    'url'  => home_url(),
                ),
                array(
                    'text' => __( 'Shop', 'aqualuxe' ),
                    'url'  => get_permalink( wc_get_page_id( 'shop' ) ),
                ),
                array(
                    'text' => single_tag_title( '', false ),
                    'url'  => '',
                ),
            );
        } elseif ( is_product() ) {
            // Product
            $breadcrumbs = array(
                array(
                    'text' => __( 'Home', 'aqualuxe' ),
                    'url'  => home_url(),
                ),
                array(
                    'text' => __( 'Shop', 'aqualuxe' ),
                    'url'  => get_permalink( wc_get_page_id( 'shop' ) ),
                ),
                array(
                    'text' => get_the_title(),
                    'url'  => '',
                ),
            );
        } elseif ( is_cart() ) {
            // Cart
            $breadcrumbs = array(
                array(
                    'text' => __( 'Home', 'aqualuxe' ),
                    'url'  => home_url(),
                ),
                array(
                    'text' => __( 'Shop', 'aqualuxe' ),
                    'url'  => get_permalink( wc_get_page_id( 'shop' ) ),
                ),
                array(
                    'text' => __( 'Cart', 'aqualuxe' ),
                    'url'  => '',
                ),
            );
        } elseif ( is_checkout() ) {
            // Checkout
            $breadcrumbs = array(
                array(
                    'text' => __( 'Home', 'aqualuxe' ),
                    'url'  => home_url(),
                ),
                array(
                    'text' => __( 'Shop', 'aqualuxe' ),
                    'url'  => get_permalink( wc_get_page_id( 'shop' ) ),
                ),
                array(
                    'text' => __( 'Checkout', 'aqualuxe' ),
                    'url'  => '',
                ),
            );
        } elseif ( is_account_page() ) {
            // Account
            $breadcrumbs = array(
                array(
                    'text' => __( 'Home', 'aqualuxe' ),
                    'url'  => home_url(),
                ),
                array(
                    'text' => __( 'Shop', 'aqualuxe' ),
                    'url'  => get_permalink( wc_get_page_id( 'shop' ) ),
                ),
                array(
                    'text' => __( 'My Account', 'aqualuxe' ),
                    'url'  => '',
                ),
            );
        }
    }
    
    return apply_filters( 'aqualuxe_breadcrumbs', $breadcrumbs );
}

/**
 * Get schema markup
 *
 * @return string
 */
function aqualuxe_get_schema_markup() {
    $schema = '';
    
    if ( is_singular( 'post' ) ) {
        $schema = 'itemscope itemtype="https://schema.org/BlogPosting"';
    } elseif ( is_singular( 'page' ) ) {
        $schema = 'itemscope itemtype="https://schema.org/WebPage"';
    } elseif ( is_singular( 'product' ) ) {
        $schema = 'itemscope itemtype="https://schema.org/Product"';
    } elseif ( is_author() ) {
        $schema = 'itemscope itemtype="https://schema.org/Person"';
    } elseif ( is_search() ) {
        $schema = 'itemscope itemtype="https://schema.org/SearchResultsPage"';
    } else {
        $schema = 'itemscope itemtype="https://schema.org/WebSite"';
    }
    
    return apply_filters( 'aqualuxe_schema_markup', $schema );
}

/**
 * Get Open Graph meta tags
 *
 * @return array
 */
function aqualuxe_get_open_graph_meta_tags() {
    $meta_tags = array();
    
    // Default tags
    $meta_tags['og:site_name'] = get_bloginfo( 'name' );
    $meta_tags['og:locale'] = get_locale();
    
    if ( is_singular() ) {
        $meta_tags['og:type'] = 'article';
        $meta_tags['og:title'] = get_the_title();
        $meta_tags['og:description'] = get_the_excerpt();
        $meta_tags['og:url'] = get_permalink();
        
        if ( has_post_thumbnail() ) {
            $meta_tags['og:image'] = get_the_post_thumbnail_url( null, 'large' );
        }
        
        $meta_tags['article:published_time'] = get_the_date( 'c' );
        $meta_tags['article:modified_time'] = get_the_modified_date( 'c' );
        
        if ( is_singular( 'post' ) ) {
            $categories = get_the_category();
            
            if ( ! empty( $categories ) ) {
                $meta_tags['article:section'] = $categories[0]->name;
            }
            
            $tags = get_the_tags();
            
            if ( ! empty( $tags ) ) {
                $tag_names = array();
                
                foreach ( $tags as $tag ) {
                    $tag_names[] = $tag->name;
                }
                
                $meta_tags['article:tag'] = implode( ', ', $tag_names );
            }
        }
    } elseif ( is_home() || is_archive() || is_search() ) {
        $meta_tags['og:type'] = 'website';
        $meta_tags['og:title'] = aqualuxe_get_page_title();
        $meta_tags['og:description'] = get_bloginfo( 'description' );
        $meta_tags['og:url'] = aqualuxe_get_current_url();
    }
    
    return apply_filters( 'aqualuxe_open_graph_meta_tags', $meta_tags );
}

/**
 * Get current URL
 *
 * @return string
 */
function aqualuxe_get_current_url() {
    global $wp;
    return home_url( add_query_arg( array(), $wp->request ) );
}

/**
 * Get related posts
 *
 * @param int $post_id Post ID
 * @param int $count   Number of posts
 * @return array
 */
function aqualuxe_get_related_posts( $post_id = null, $count = 3 ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $categories = get_the_category( $post_id );
    
    if ( empty( $categories ) ) {
        return array();
    }
    
    $category_ids = array();
    
    foreach ( $categories as $category ) {
        $category_ids[] = $category->term_id;
    }
    
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $count,
        'post__not_in'   => array( $post_id ),
        'category__in'   => $category_ids,
    );
    
    $query = new WP_Query( $args );
    
    return $query->posts;
}

/**
 * Get related products
 *
 * @param int $product_id Product ID
 * @param int $count      Number of products
 * @return array
 */
function aqualuxe_get_related_products( $product_id = null, $count = 4 ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $related_products = array();
    
    if ( function_exists( 'wc_get_related_products' ) ) {
        $related_products = wc_get_related_products( $product_id, $count );
    }
    
    return $related_products;
}

/**
 * Get post navigation
 *
 * @return array
 */
function aqualuxe_get_post_navigation() {
    $navigation = array();
    
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    
    if ( $prev_post ) {
        $navigation['prev'] = array(
            'title' => get_the_title( $prev_post->ID ),
            'url'   => get_permalink( $prev_post->ID ),
        );
    }
    
    if ( $next_post ) {
        $navigation['next'] = array(
            'title' => get_the_title( $next_post->ID ),
            'url'   => get_permalink( $next_post->ID ),
        );
    }
    
    return $navigation;
}

/**
 * Get pagination
 *
 * @param array $args Arguments
 * @return string
 */
function aqualuxe_get_pagination( $args = array() ) {
    $defaults = array(
        'total'     => 1,
        'current'   => 1,
        'show_all'  => false,
        'prev_text' => __( '&laquo; Previous', 'aqualuxe' ),
        'next_text' => __( 'Next &raquo;', 'aqualuxe' ),
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    return paginate_links( $args );
}

/**
 * Get comments pagination
 *
 * @param array $args Arguments
 * @return string
 */
function aqualuxe_get_comments_pagination( $args = array() ) {
    $defaults = array(
        'prev_text' => __( '&laquo; Previous', 'aqualuxe' ),
        'next_text' => __( 'Next &raquo;', 'aqualuxe' ),
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    return paginate_comments_links( $args );
}

/**
 * Get post meta
 *
 * @return array
 */
function aqualuxe_get_post_meta() {
    $meta = array();
    
    $meta['author'] = array(
        'text' => get_the_author(),
        'url'  => get_author_posts_url( get_the_author_meta( 'ID' ) ),
    );
    
    $meta['date'] = array(
        'text' => get_the_date(),
        'url'  => get_day_link( get_post_time( 'Y' ), get_post_time( 'm' ), get_post_time( 'j' ) ),
    );
    
    $categories = get_the_category();
    
    if ( ! empty( $categories ) ) {
        $meta['category'] = array(
            'text' => $categories[0]->name,
            'url'  => get_category_link( $categories[0]->term_id ),
        );
    }
    
    $meta['comments'] = array(
        'text' => sprintf( _n( '%s Comment', '%s Comments', get_comments_number(), 'aqualuxe' ), get_comments_number() ),
        'url'  => get_comments_link(),
    );
    
    return $meta;
}

/**
 * Get post tags
 *
 * @return array
 */
function aqualuxe_get_post_tags() {
    $tags = get_the_tags();
    
    if ( ! $tags ) {
        return array();
    }
    
    $post_tags = array();
    
    foreach ( $tags as $tag ) {
        $post_tags[] = array(
            'name' => $tag->name,
            'url'  => get_tag_link( $tag->term_id ),
        );
    }
    
    return $post_tags;
}

/**
 * Get post categories
 *
 * @return array
 */
function aqualuxe_get_post_categories() {
    $categories = get_the_category();
    
    if ( ! $categories ) {
        return array();
    }
    
    $post_categories = array();
    
    foreach ( $categories as $category ) {
        $post_categories[] = array(
            'name' => $category->name,
            'url'  => get_category_link( $category->term_id ),
        );
    }
    
    return $post_categories;
}

/**
 * Get product categories
 *
 * @param int $product_id Product ID
 * @return array
 */
function aqualuxe_get_product_categories( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $terms = get_the_terms( $product_id, 'product_cat' );
    
    if ( ! $terms ) {
        return array();
    }
    
    $product_categories = array();
    
    foreach ( $terms as $term ) {
        $product_categories[] = array(
            'name' => $term->name,
            'url'  => get_term_link( $term ),
        );
    }
    
    return $product_categories;
}

/**
 * Get product tags
 *
 * @param int $product_id Product ID
 * @return array
 */
function aqualuxe_get_product_tags( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $terms = get_the_terms( $product_id, 'product_tag' );
    
    if ( ! $terms ) {
        return array();
    }
    
    $product_tags = array();
    
    foreach ( $terms as $term ) {
        $product_tags[] = array(
            'name' => $term->name,
            'url'  => get_term_link( $term ),
        );
    }
    
    return $product_tags;
}

/**
 * Get product attributes
 *
 * @param int $product_id Product ID
 * @return array
 */
function aqualuxe_get_product_attributes( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return array();
    }
    
    $attributes = array();
    
    if ( $product->has_attributes() ) {
        $product_attributes = $product->get_attributes();
        
        foreach ( $product_attributes as $attribute ) {
            if ( $attribute->get_visible() ) {
                $name = $attribute->get_name();
                
                if ( $attribute->is_taxonomy() ) {
                    $terms = wp_get_post_terms( $product_id, $name, array( 'fields' => 'names' ) );
                    $value = apply_filters( 'woocommerce_attribute', implode( ', ', $terms ), $attribute, $terms );
                } else {
                    $value = $attribute->get_options();
                    $value = implode( ', ', $value );
                }
                
                $attributes[ wc_attribute_label( $name ) ] = $value;
            }
        }
    }
    
    return $attributes;
}

/**
 * Get product gallery images
 *
 * @param int $product_id Product ID
 * @return array
 */
function aqualuxe_get_product_gallery_images( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return array();
    }
    
    $attachment_ids = $product->get_gallery_image_ids();
    
    if ( ! $attachment_ids ) {
        return array();
    }
    
    $gallery_images = array();
    
    foreach ( $attachment_ids as $attachment_id ) {
        $gallery_images[] = array(
            'url'       => wp_get_attachment_url( $attachment_id ),
            'thumbnail' => wp_get_attachment_image_url( $attachment_id, 'thumbnail' ),
            'alt'       => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
        );
    }
    
    return $gallery_images;
}

/**
 * Get product price HTML
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_price_html( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return $product->get_price_html();
}

/**
 * Get product rating HTML
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_rating_html( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    if ( ! wc_review_ratings_enabled() ) {
        return '';
    }
    
    return wc_get_rating_html( $product->get_average_rating() );
}

/**
 * Get product stock status
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_stock_status( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return $product->get_stock_status();
}

/**
 * Get product stock quantity
 *
 * @param int $product_id Product ID
 * @return int
 */
function aqualuxe_get_product_stock_quantity( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    return $product->get_stock_quantity();
}

/**
 * Get product SKU
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_sku( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return $product->get_sku();
}

/**
 * Get product dimensions
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_dimensions( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return wc_format_dimensions( $product->get_dimensions( false ) );
}

/**
 * Get product weight
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_weight( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return $product->get_weight();
}

/**
 * Get product type
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_type( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return $product->get_type();
}

/**
 * Check if product is on sale
 *
 * @param int $product_id Product ID
 * @return bool
 */
function aqualuxe_is_product_on_sale( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return false;
    }
    
    return $product->is_on_sale();
}

/**
 * Check if product is in stock
 *
 * @param int $product_id Product ID
 * @return bool
 */
function aqualuxe_is_product_in_stock( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return false;
    }
    
    return $product->is_in_stock();
}

/**
 * Check if product is purchasable
 *
 * @param int $product_id Product ID
 * @return bool
 */
function aqualuxe_is_product_purchasable( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return false;
    }
    
    return $product->is_purchasable();
}

/**
 * Check if product is featured
 *
 * @param int $product_id Product ID
 * @return bool
 */
function aqualuxe_is_product_featured( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return false;
    }
    
    return $product->is_featured();
}

/**
 * Check if product is virtual
 *
 * @param int $product_id Product ID
 * @return bool
 */
function aqualuxe_is_product_virtual( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return false;
    }
    
    return $product->is_virtual();
}

/**
 * Check if product is downloadable
 *
 * @param int $product_id Product ID
 * @return bool
 */
function aqualuxe_is_product_downloadable( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return false;
    }
    
    return $product->is_downloadable();
}

/**
 * Check if product is variable
 *
 * @param int $product_id Product ID
 * @return bool
 */
function aqualuxe_is_product_variable( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return false;
    }
    
    return $product->is_type( 'variable' );
}

/**
 * Check if product is grouped
 *
 * @param int $product_id Product ID
 * @return bool
 */
function aqualuxe_is_product_grouped( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return false;
    }
    
    return $product->is_type( 'grouped' );
}

/**
 * Check if product is external
 *
 * @param int $product_id Product ID
 * @return bool
 */
function aqualuxe_is_product_external( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return false;
    }
    
    return $product->is_type( 'external' );
}

/**
 * Get cart URL
 *
 * @return string
 */
function aqualuxe_get_cart_url() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    return wc_get_cart_url();
}

/**
 * Get checkout URL
 *
 * @return string
 */
function aqualuxe_get_checkout_url() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    return wc_get_checkout_url();
}

/**
 * Get account URL
 *
 * @return string
 */
function aqualuxe_get_account_url() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    return wc_get_account_endpoint_url( 'dashboard' );
}

/**
 * Get shop URL
 *
 * @return string
 */
function aqualuxe_get_shop_url() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    return get_permalink( wc_get_page_id( 'shop' ) );
}

/**
 * Get cart count
 *
 * @return int
 */
function aqualuxe_get_cart_count() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    return WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
}

/**
 * Get cart total
 *
 * @return string
 */
function aqualuxe_get_cart_total() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    return WC()->cart ? WC()->cart->get_cart_total() : '';
}

/**
 * Get currency symbol
 *
 * @return string
 */
function aqualuxe_get_currency_symbol() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    return get_woocommerce_currency_symbol();
}

/**
 * Get currency
 *
 * @return string
 */
function aqualuxe_get_currency() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    return get_woocommerce_currency();
}

/**
 * Get currency position
 *
 * @return string
 */
function aqualuxe_get_currency_position() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    return get_option( 'woocommerce_currency_pos' );
}

/**
 * Format price
 *
 * @param float $price Price
 * @return string
 */
function aqualuxe_format_price( $price ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    return wc_price( $price );
}

/**
 * Get product categories
 *
 * @param array $args Arguments
 * @return array
 */
function aqualuxe_get_product_categories_list( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    $defaults = array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $categories = get_terms( $args );
    
    if ( is_wp_error( $categories ) ) {
        return array();
    }
    
    return $categories;
}

/**
 * Get product tags
 *
 * @param array $args Arguments
 * @return array
 */
function aqualuxe_get_product_tags_list( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    $defaults = array(
        'taxonomy'   => 'product_tag',
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $tags = get_terms( $args );
    
    if ( is_wp_error( $tags ) ) {
        return array();
    }
    
    return $tags;
}

/**
 * Get product attributes
 *
 * @param array $args Arguments
 * @return array
 */
function aqualuxe_get_product_attributes_list( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    $defaults = array(
        'orderby' => 'name',
        'order'   => 'ASC',
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $attributes = wc_get_attribute_taxonomies();
    
    return $attributes;
}

/**
 * Get product attribute terms
 *
 * @param int   $attribute_id Attribute ID
 * @param array $args         Arguments
 * @return array
 */
function aqualuxe_get_product_attribute_terms( $attribute_id, $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    $defaults = array(
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $attribute = wc_get_attribute( $attribute_id );
    
    if ( ! $attribute ) {
        return array();
    }
    
    $terms = get_terms( array(
        'taxonomy'   => $attribute->slug,
        'hide_empty' => $args['hide_empty'],
        'orderby'    => $args['orderby'],
        'order'      => $args['order'],
    ) );
    
    if ( is_wp_error( $terms ) ) {
        return array();
    }
    
    return $terms;
}

/**
 * Get product brands
 *
 * @param array $args Arguments
 * @return array
 */
function aqualuxe_get_product_brands( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    $defaults = array(
        'taxonomy'   => 'product_brand',
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $brands = get_terms( $args );
    
    if ( is_wp_error( $brands ) ) {
        return array();
    }
    
    return $brands;
}

/**
 * Get featured products
 *
 * @param int $count Number of products
 * @return array
 */
function aqualuxe_get_featured_products( $count = 4 ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $count,
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
                'operator' => 'IN',
            ),
        ),
    );
    
    $query = new WP_Query( $args );
    
    return $query->posts;
}

/**
 * Get sale products
 *
 * @param int $count Number of products
 * @return array
 */
function aqualuxe_get_sale_products( $count = 4 ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $count,
        'meta_query'     => array(
            'relation' => 'OR',
            array(
                'key'     => '_sale_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'NUMERIC',
            ),
            array(
                'key'     => '_min_variation_sale_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'NUMERIC',
            ),
        ),
    );
    
    $query = new WP_Query( $args );
    
    return $query->posts;
}

/**
 * Get best selling products
 *
 * @param int $count Number of products
 * @return array
 */
function aqualuxe_get_best_selling_products( $count = 4 ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $count,
        'meta_key'       => 'total_sales',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
    );
    
    $query = new WP_Query( $args );
    
    return $query->posts;
}

/**
 * Get top rated products
 *
 * @param int $count Number of products
 * @return array
 */
function aqualuxe_get_top_rated_products( $count = 4 ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $count,
        'meta_key'       => '_wc_average_rating',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
    );
    
    $query = new WP_Query( $args );
    
    return $query->posts;
}

/**
 * Get recent products
 *
 * @param int $count Number of products
 * @return array
 */
function aqualuxe_get_recent_products( $count = 4 ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $count,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    
    $query = new WP_Query( $args );
    
    return $query->posts;
}

/**
 * Get products by category
 *
 * @param int   $category_id Category ID
 * @param int   $count       Number of products
 * @param array $args        Arguments
 * @return array
 */
function aqualuxe_get_products_by_category( $category_id, $count = 4, $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    $defaults = array(
        'post_type'      => 'product',
        'posts_per_page' => $count,
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $category_id,
            ),
        ),
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $query = new WP_Query( $args );
    
    return $query->posts;
}

/**
 * Get products by tag
 *
 * @param int   $tag_id Tag ID
 * @param int   $count  Number of products
 * @param array $args   Arguments
 * @return array
 */
function aqualuxe_get_products_by_tag( $tag_id, $count = 4, $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    $defaults = array(
        'post_type'      => 'product',
        'posts_per_page' => $count,
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_tag',
                'field'    => 'term_id',
                'terms'    => $tag_id,
            ),
        ),
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $query = new WP_Query( $args );
    
    return $query->posts;
}

/**
 * Get products by attribute
 *
 * @param string $attribute Attribute
 * @param string $value     Value
 * @param int    $count     Number of products
 * @param array  $args      Arguments
 * @return array
 */
function aqualuxe_get_products_by_attribute( $attribute, $value, $count = 4, $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    $defaults = array(
        'post_type'      => 'product',
        'posts_per_page' => $count,
        'tax_query'      => array(
            array(
                'taxonomy' => $attribute,
                'field'    => 'slug',
                'terms'    => $value,
            ),
        ),
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $query = new WP_Query( $args );
    
    return $query->posts;
}

/**
 * Get products by brand
 *
 * @param int   $brand_id Brand ID
 * @param int   $count    Number of products
 * @param array $args     Arguments
 * @return array
 */
function aqualuxe_get_products_by_brand( $brand_id, $count = 4, $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    $defaults = array(
        'post_type'      => 'product',
        'posts_per_page' => $count,
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_brand',
                'field'    => 'term_id',
                'terms'    => $brand_id,
            ),
        ),
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $query = new WP_Query( $args );
    
    return $query->posts;
}

/**
 * Get products by price
 *
 * @param float  $min_price Min price
 * @param float  $max_price Max price
 * @param int    $count     Number of products
 * @param array  $args      Arguments
 * @return array
 */
function aqualuxe_get_products_by_price( $min_price, $max_price, $count = 4, $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    $defaults = array(
        'post_type'      => 'product',
        'posts_per_page' => $count,
        'meta_query'     => array(
            array(
                'key'     => '_price',
                'value'   => array( $min_price, $max_price ),
                'compare' => 'BETWEEN',
                'type'    => 'NUMERIC',
            ),
        ),
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $query = new WP_Query( $args );
    
    return $query->posts;
}

/**
 * Get products by search
 *
 * @param string $search Search term
 * @param int    $count  Number of products
 * @param array  $args   Arguments
 * @return array
 */
function aqualuxe_get_products_by_search( $search, $count = 4, $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    $defaults = array(
        'post_type'      => 'product',
        'posts_per_page' => $count,
        's'              => $search,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $query = new WP_Query( $args );
    
    return $query->posts;
}

/**
 * Get products by IDs
 *
 * @param array $ids  Product IDs
 * @param array $args Arguments
 * @return array
 */
function aqualuxe_get_products_by_ids( $ids, $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    $defaults = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post__in'       => $ids,
        'orderby'        => 'post__in',
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $query = new WP_Query( $args );
    
    return $query->posts;
}

/**
 * Get product cross sells
 *
 * @param int $product_id Product ID
 * @return array
 */
function aqualuxe_get_product_cross_sells( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return array();
    }
    
    return $product->get_cross_sell_ids();
}

/**
 * Get product up sells
 *
 * @param int $product_id Product ID
 * @return array
 */
function aqualuxe_get_product_up_sells( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return array();
    }
    
    return $product->get_upsell_ids();
}

/**
 * Get product variations
 *
 * @param int $product_id Product ID
 * @return array
 */
function aqualuxe_get_product_variations( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product || ! $product->is_type( 'variable' ) ) {
        return array();
    }
    
    return $product->get_available_variations();
}

/**
 * Get product grouped products
 *
 * @param int $product_id Product ID
 * @return array
 */
function aqualuxe_get_product_grouped_products( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product || ! $product->is_type( 'grouped' ) ) {
        return array();
    }
    
    return $product->get_children();
}

/**
 * Get product reviews
 *
 * @param int $product_id Product ID
 * @param int $count      Number of reviews
 * @return array
 */
function aqualuxe_get_product_reviews( $product_id = null, $count = 5 ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $args = array(
        'post_id' => $product_id,
        'number'  => $count,
        'status'  => 'approve',
        'type'    => 'review',
    );
    
    $comments = get_comments( $args );
    
    return $comments;
}

/**
 * Get product review count
 *
 * @param int $product_id Product ID
 * @return int
 */
function aqualuxe_get_product_review_count( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    return $product->get_review_count();
}

/**
 * Get product rating count
 *
 * @param int $product_id Product ID
 * @return int
 */
function aqualuxe_get_product_rating_count( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    return $product->get_rating_count();
}

/**
 * Get product average rating
 *
 * @param int $product_id Product ID
 * @return float
 */
function aqualuxe_get_product_average_rating( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    return $product->get_average_rating();
}

/**
 * Get product rating counts
 *
 * @param int $product_id Product ID
 * @return array
 */
function aqualuxe_get_product_rating_counts( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return array();
    }
    
    return $product->get_rating_counts();
}

/**
 * Get product downloads
 *
 * @param int $product_id Product ID
 * @return array
 */
function aqualuxe_get_product_downloads( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product || ! $product->is_downloadable() ) {
        return array();
    }
    
    return $product->get_downloads();
}

/**
 * Get product permalink
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_permalink( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return $product->get_permalink();
}

/**
 * Get product add to cart URL
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_add_to_cart_url( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return $product->add_to_cart_url();
}

/**
 * Get product add to cart text
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_add_to_cart_text( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return $product->add_to_cart_text();
}

/**
 * Get product add to cart description
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_add_to_cart_description( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return $product->add_to_cart_description();
}

/**
 * Get product short description
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_short_description( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return $product->get_short_description();
}

/**
 * Get product description
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_description( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return $product->get_description();
}

/**
 * Get product regular price
 *
 * @param int $product_id Product ID
 * @return float
 */
function aqualuxe_get_product_regular_price( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    return $product->get_regular_price();
}

/**
 * Get product sale price
 *
 * @param int $product_id Product ID
 * @return float
 */
function aqualuxe_get_product_sale_price( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    return $product->get_sale_price();
}

/**
 * Get product price
 *
 * @param int $product_id Product ID
 * @return float
 */
function aqualuxe_get_product_price( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    return $product->get_price();
}

/**
 * Get product price suffix
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_price_suffix( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return $product->get_price_suffix();
}

/**
 * Get product price including tax
 *
 * @param int   $product_id Product ID
 * @param float $price      Price
 * @return float
 */
function aqualuxe_get_product_price_including_tax( $product_id = null, $price = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    if ( ! $price ) {
        $price = $product->get_price();
    }
    
    return wc_get_price_including_tax( $product, array( 'price' => $price ) );
}

/**
 * Get product price excluding tax
 *
 * @param int   $product_id Product ID
 * @param float $price      Price
 * @return float
 */
function aqualuxe_get_product_price_excluding_tax( $product_id = null, $price = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    if ( ! $price ) {
        $price = $product->get_price();
    }
    
    return wc_get_price_excluding_tax( $product, array( 'price' => $price ) );
}

/**
 * Get product tax class
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_tax_class( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return $product->get_tax_class();
}

/**
 * Get product tax status
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_tax_status( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return $product->get_tax_status();
}

/**
 * Get product shipping class
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_shipping_class( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return $product->get_shipping_class();
}

/**
 * Get product shipping class ID
 *
 * @param int $product_id Product ID
 * @return int
 */
function aqualuxe_get_product_shipping_class_id( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    return $product->get_shipping_class_id();
}

/**
 * Get product shipping is taxable
 *
 * @param int $product_id Product ID
 * @return bool
 */
function aqualuxe_is_product_shipping_taxable( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return false;
    }
    
    return $product->is_shipping_taxable();
}

/**
 * Get product shipping required
 *
 * @param int $product_id Product ID
 * @return bool
 */
function aqualuxe_is_product_shipping_required( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return false;
    }
    
    return $product->needs_shipping();
}

/**
 * Get product sold individually
 *
 * @param int $product_id Product ID
 * @return bool
 */
function aqualuxe_is_product_sold_individually( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return false;
    }
    
    return $product->is_sold_individually();
}

/**
 * Get product backorders allowed
 *
 * @param int $product_id Product ID
 * @return bool
 */
function aqualuxe_is_product_backorders_allowed( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return false;
    }
    
    return $product->backorders_allowed();
}

/**
 * Get product backorders required
 *
 * @param int $product_id Product ID
 * @return bool
 */
function aqualuxe_is_product_backorders_required( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return false;
    }
    
    return $product->backorders_require_notification();
}

/**
 * Get product manage stock
 *
 * @param int $product_id Product ID
 * @return bool
 */
function aqualuxe_is_product_manage_stock( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return false;
    }
    
    return $product->managing_stock();
}

/**
 * Get product stock quantity
 *
 * @param int $product_id Product ID
 * @return int
 */
function aqualuxe_get_product_stock_quantity( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    return $product->get_stock_quantity();
}

/**
 * Get product stock status
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_stock_status( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return $product->get_stock_status();
}

/**
 * Get product low stock amount
 *
 * @param int $product_id Product ID
 * @return int
 */
function aqualuxe_get_product_low_stock_amount( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    return $product->get_low_stock_amount();
}

/**
 * Get product weight
 *
 * @param int $product_id Product ID
 * @return float
 */
function aqualuxe_get_product_weight( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    return $product->get_weight();
}

/**
 * Get product length
 *
 * @param int $product_id Product ID
 * @return float
 */
function aqualuxe_get_product_length( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    return $product->get_length();
}

/**
 * Get product width
 *
 * @param int $product_id Product ID
 * @return float
 */
function aqualuxe_get_product_width( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    return $product->get_width();
}

/**
 * Get product height
 *
 * @param int $product_id Product ID
 * @return float
 */
function aqualuxe_get_product_height( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    return $product->get_height();
}

/**
 * Get product dimensions
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_dimensions( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return wc_format_dimensions( $product->get_dimensions( false ) );
}

/**
 * Get product purchase note
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_purchase_note( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return $product->get_purchase_note();
}

/**
 * Get product reviews allowed
 *
 * @param int $product_id Product ID
 * @return bool
 */
function aqualuxe_is_product_reviews_allowed( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return false;
    }
    
    return $product->get_reviews_allowed();
}

/**
 * Get product menu order
 *
 * @param int $product_id Product ID
 * @return int
 */
function aqualuxe_get_product_menu_order( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    return $product->get_menu_order();
}

/**
 * Get product virtual
 *
 * @param int $product_id Product ID
 * @return bool
 */
function aqualuxe_is_product_virtual( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return false;
    }
    
    return $product->is_virtual();
}

/**
 * Get product downloadable
 *
 * @param int $product_id Product ID
 * @return bool
 */
function aqualuxe_is_product_downloadable( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return false;
    }
    
    return $product->is_downloadable();
}

/**
 * Get product download limit
 *
 * @param int $product_id Product ID
 * @return int
 */
function aqualuxe_get_product_download_limit( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    return $product->get_download_limit();
}

/**
 * Get product download expiry
 *
 * @param int $product_id Product ID
 * @return int
 */
function aqualuxe_get_product_download_expiry( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    return $product->get_download_expiry();
}

/**
 * Get product downloads
 *
 * @param int $product_id Product ID
 * @return array
 */
function aqualuxe_get_product_downloads( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return array();
    }
    
    return $product->get_downloads();
}

/**
 * Get product image ID
 *
 * @param int $product_id Product ID
 * @return int
 */
function aqualuxe_get_product_image_id( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    return $product->get_image_id();
}

/**
 * Get product gallery image IDs
 *
 * @param int $product_id Product ID
 * @return array
 */
function aqualuxe_get_product_gallery_image_ids( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return array();
    }
    
    return $product->get_gallery_image_ids();
}

/**
 * Get product image
 *
 * @param int    $product_id Product ID
 * @param string $size       Image size
 * @return string
 */
function aqualuxe_get_product_image( $product_id = null, $size = 'woocommerce_thumbnail' ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    return $product->get_image( $size );
}

/**
 * Get product image URL
 *
 * @param int    $product_id Product ID
 * @param string $size       Image size
 * @return string
 */
function aqualuxe_get_product_image_url( $product_id = null, $size = 'woocommerce_thumbnail' ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    $image_id = $product->get_image_id();
    
    if ( ! $image_id ) {
        return wc_placeholder_img_src( $size );
    }
    
    return wp_get_attachment_image_url( $image_id, $size );
}

/**
 * Get product gallery images
 *
 * @param int    $product_id Product ID
 * @param string $size       Image size
 * @return array
 */
function aqualuxe_get_product_gallery_images( $product_id = null, $size = 'woocommerce_thumbnail' ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return array();
    }
    
    $gallery_image_ids = $product->get_gallery_image_ids();
    
    if ( ! $gallery_image_ids ) {
        return array();
    }
    
    $gallery_images = array();
    
    foreach ( $gallery_image_ids as $gallery_image_id ) {
        $gallery_images[] = array(
            'id'        => $gallery_image_id,
            'url'       => wp_get_attachment_image_url( $gallery_image_id, $size ),
            'thumbnail' => wp_get_attachment_image_url( $gallery_image_id, 'thumbnail' ),
            'alt'       => get_post_meta( $gallery_image_id, '_wp_attachment_image_alt', true ),
        );
    }
    
    return $gallery_images;
}

/**
 * Get product gallery images HTML
 *
 * @param int    $product_id Product ID
 * @param string $size       Image size
 * @return string
 */
function aqualuxe_get_product_gallery_images_html( $product_id = null, $size = 'woocommerce_thumbnail' ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    $gallery_image_ids = $product->get_gallery_image_ids();
    
    if ( ! $gallery_image_ids ) {
        return '';
    }
    
    $gallery_images_html = '';
    
    foreach ( $gallery_image_ids as $gallery_image_id ) {
        $gallery_images_html .= wp_get_attachment_image( $gallery_image_id, $size );
    }
    
    return $gallery_images_html;
}

/**
 * Get product featured image
 *
 * @param int    $product_id Product ID
 * @param string $size       Image size
 * @return string
 */
function aqualuxe_get_product_featured_image( $product_id = null, $size = 'woocommerce_thumbnail' ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    $image_id = $product->get_image_id();
    
    if ( ! $image_id ) {
        return wc_placeholder_img( $size );
    }
    
    return wp_get_attachment_image( $image_id, $size );
}

/**
 * Get product featured image URL
 *
 * @param int    $product_id Product ID
 * @param string $size       Image size
 * @return string
 */
function aqualuxe_get_product_featured_image_url( $product_id = null, $size = 'woocommerce_thumbnail' ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    $image_id = $product->get_image_id();
    
    if ( ! $image_id ) {
        return wc_placeholder_img_src( $size );
    }
    
    return wp_get_attachment_image_url( $image_id, $size );
}

/**
 * Get product featured image ID
 *
 * @param int $product_id Product ID
 * @return int
 */
function aqualuxe_get_product_featured_image_id( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    return $product->get_image_id();
}

/**
 * Get product featured image alt
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_featured_image_alt( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    $image_id = $product->get_image_id();
    
    if ( ! $image_id ) {
        return '';
    }
    
    return get_post_meta( $image_id, '_wp_attachment_image_alt', true );
}

/**
 * Get product featured image caption
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_featured_image_caption( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    $image_id = $product->get_image_id();
    
    if ( ! $image_id ) {
        return '';
    }
    
    $image = get_post( $image_id );
    
    if ( ! $image ) {
        return '';
    }
    
    return $image->post_excerpt;
}

/**
 * Get product featured image title
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_featured_image_title( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    $image_id = $product->get_image_id();
    
    if ( ! $image_id ) {
        return '';
    }
    
    $image = get_post( $image_id );
    
    if ( ! $image ) {
        return '';
    }
    
    return $image->post_title;
}

/**
 * Get product featured image description
 *
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_get_product_featured_image_description( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    $image_id = $product->get_image_id();
    
    if ( ! $image_id ) {
        return '';
    }
    
    $image = get_post( $image_id );
    
    if ( ! $image ) {
        return '';
    }
    
    return $image->post_content;
}

/**
 * Get product featured image metadata
 *
 * @param int $product_id Product ID
 * @return array
 */
function aqualuxe_get_product_featured_image_metadata( $product_id = null ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return array();
    }
    
    $image_id = $product->get_image_id();
    
    if ( ! $image_id ) {
        return array();
    }
    
    return wp_get_attachment_metadata( $image_id );
}

/**
 * Get product featured image dimensions
 *
 * @param int    $product_id Product ID
 * @param string $size       Image size
 * @return array
 */
function aqualuxe_get_product_featured_image_dimensions( $product_id = null, $size = 'woocommerce_thumbnail' ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return array();
    }
    
    $image_id = $product->get_image_id();
    
    if ( ! $image_id ) {
        return array();
    }
    
    $image = wp_get_attachment_image_src( $image_id, $size );
    
    if ( ! $image ) {
        return array();
    }
    
    return array(
        'width'  => $image[1],
        'height' => $image[2],
    );
}

/**
 * Get product featured image width
 *
 * @param int    $product_id Product ID
 * @param string $size       Image size
 * @return int
 */
function aqualuxe_get_product_featured_image_width( $product_id = null, $size = 'woocommerce_thumbnail' ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    $image_id = $product->get_image_id();
    
    if ( ! $image_id ) {
        return 0;
    }
    
    $image = wp_get_attachment_image_src( $image_id, $size );
    
    if ( ! $image ) {
        return 0;
    }
    
    return $image[1];
}

/**
 * Get product featured image height
 *
 * @param int    $product_id Product ID
 * @param string $size       Image size
 * @return int
 */
function aqualuxe_get_product_featured_image_height( $product_id = null, $size = 'woocommerce_thumbnail' ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return 0;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return 0;
    }
    
    $image_id = $product->get_image_id();
    
    if ( ! $image_id ) {
        return 0;
    }
    
    $image = wp_get_attachment_image_src( $image_id, $size );
    
    if ( ! $image ) {
        return 0;
    }
    
    return $image[2];
}

/**
 * Get product featured image srcset
 *
 * @param int    $product_id Product ID
 * @param string $size       Image size
 * @return string
 */
function aqualuxe_get_product_featured_image_srcset( $product_id = null, $size = 'woocommerce_thumbnail' ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    $image_id = $product->get_image_id();
    
    if ( ! $image_id ) {
        return '';
    }
    
    return wp_get_attachment_image_srcset( $image_id, $size );
}

/**
 * Get product featured image sizes
 *
 * @param int    $product_id Product ID
 * @param string $size       Image size
 * @return string
 */
function aqualuxe_get_product_featured_image_sizes( $product_id = null, $size = 'woocommerce_thumbnail' ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '';
    }
    
    $image_id = $product->get_image_id();
    
    if ( ! $image_id ) {
        return '';
    }
    
    return wp_get_attachment_image_sizes( $image_id, $size );
}

/**
 * Get product featured image attributes
 *
 * @param int    $product_id Product ID
 * @param string $size       Image size
 * @return array
 */
function aqualuxe_get_product_featured_image_attributes( $product_id = null, $size = 'woocommerce_thumbnail' ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return array();
    }
    
    $image_id = $product->get_image_id();
    
    if ( ! $image_id ) {
        return array();
    }
    
    return wp_get_attachment_image_attributes( $image_id, $size );
}

/**
 * Get product featured image data
 *
 * @param int    $product_id Product ID
 * @param string $size       Image size
 * @return array
 */
function aqualuxe_get_product_featured_image_data( $product_id = null, $size = 'woocommerce_thumbnail' ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return array();
    }
    
    $image_id = $product->get_image_id();
    
    if ( ! $image_id ) {
        return array(
            'id'        => 0,
            'url'       => wc_placeholder_img_src( $size ),
            'thumbnail' => wc_placeholder_img_src( 'thumbnail' ),
            'alt'       => '',
            'title'     => '',
            'caption'   => '',
            'width'     => 0,
            'height'    => 0,
            'srcset'    => '',
            'sizes'     => '',
        );
    }
    
    $image = wp_get_attachment_image_src( $image_id, $size );
    $image_thumbnail = wp_get_attachment_image_src( $image_id, 'thumbnail' );
    $image_post = get_post( $image_id );
    
    return array(
        'id'        => $image_id,
        'url'       => $image[0],
        'thumbnail' => $image_thumbnail[0],
        'alt'       => get_post_meta( $image_id, '_wp_attachment_image_alt', true ),
        'title'     => $image_post->post_title,
        'caption'   => $image_post->post_excerpt,
        'width'     => $image[1],
        'height'    => $image[2],
        'srcset'    => wp_get_attachment_image_srcset( $image_id, $size ),
        'sizes'     => wp_get_attachment_image_sizes( $image_id, $size ),
    );
}

/**
 * Get product gallery images data
 *
 * @param int    $product_id Product ID
 * @param string $size       Image size
 * @return array
 */
function aqualuxe_get_product_gallery_images_data( $product_id = null, $size = 'woocommerce_thumbnail' ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return array();
    }
    
    $gallery_image_ids = $product->get_gallery_image_ids();
    
    if ( ! $gallery_image_ids ) {
        return array();
    }
    
    $gallery_images_data = array();
    
    foreach ( $gallery_image_ids as $gallery_image_id ) {
        $image = wp_get_attachment_image_src( $gallery_image_id, $size );
        $image_thumbnail = wp_get_attachment_image_src( $gallery_image_id, 'thumbnail' );
        $image_post = get_post( $gallery_image_id );
        
        $gallery_images_data[] = array(
            'id'        => $gallery_image_id,
            'url'       => $image[0],
            'thumbnail' => $image_thumbnail[0],
            'alt'       => get_post_meta( $gallery_image_id, '_wp_attachment_image_alt', true ),
            'title'     => $image_post->post_title,
            'caption'   => $image_post->post_excerpt,
            'width'     => $image[1],
            'height'    => $image[2],
            'srcset'    => wp_get_attachment_image_srcset( $gallery_image_id, $size ),
            'sizes'     => wp_get_attachment_image_sizes( $gallery_image_id, $size ),
        );
    }
    
    return $gallery_images_data;
}

/**
 * Get product images data
 *
 * @param int    $product_id Product ID
 * @param string $size       Image size
 * @return array
 */
function aqualuxe_get_product_images_data( $product_id = null, $size = 'woocommerce_thumbnail' ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return array();
    }
    
    $images_data = array();
    
    // Featured image
    $image_id = $product->get_image_id();
    
    if ( $image_id ) {
        $image = wp_get_attachment_image_src( $image_id, $size );
        $image_thumbnail = wp_get_attachment_image_src( $image_id, 'thumbnail' );
        $image_post = get_post( $image_id );
        
        $images_data[] = array(
            'id'        => $image_id,
            'url'       => $image[0],
            'thumbnail' => $image_thumbnail[0],
            'alt'       => get_post_meta( $image_id, '_wp_attachment_image_alt', true ),
            'title'     => $image_post->post_title,
            'caption'   => $image_post->post_excerpt,
            'width'     => $image[1],
            'height'    => $image[2],
            'srcset'    => wp_get_attachment_image_srcset( $image_id, $size ),
            'sizes'     => wp_get_attachment_image_sizes( $image_id, $size ),
        );
    } else {
        $images_data[] = array(
            'id'        => 0,
            'url'       => wc_placeholder_img_src( $size ),
            'thumbnail' => wc_placeholder_img_src( 'thumbnail' ),
            'alt'       => '',
            'title'     => '',
            'caption'   => '',
            'width'     => 0,
            'height'    => 0,
            'srcset'    => '',
            'sizes'     => '',
        );
    }
    
    // Gallery images
    $gallery_image_ids = $product->get_gallery_image_ids();
    
    if ( $gallery_image_ids ) {
        foreach ( $gallery_image_ids as $gallery_image_id ) {
            $image = wp_get_attachment_image_src( $gallery_image_id, $size );
            $image_thumbnail = wp_get_attachment_image_src( $gallery_image_id, 'thumbnail' );
            $image_post = get_post( $gallery_image_id );
            
            $images_data[] = array(
                'id'        => $gallery_image_id,
                'url'       => $image[0],
                'thumbnail' => $image_thumbnail[0],
                'alt'       => get_post_meta( $gallery_image_id, '_wp_attachment_image_alt', true ),
                'title'     => $image_post->post_title,
                'caption'   => $image_post->post_excerpt,
                'width'     => $image[1],
                'height'    => $image[2],
                'srcset'    => wp_get_attachment_image_srcset( $gallery_image_id, $size ),
                'sizes'     => wp_get_attachment_image_sizes( $gallery_image_id, $size ),
            );
        }
    }
    
    return $images_data;
}