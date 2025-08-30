<?php
/**
 * AquaLuxe SEO Breadcrumbs Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/SEO
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Display breadcrumbs
 *
 * @param array $args Breadcrumbs arguments
 * @return void
 */
function aqualuxe_seo_breadcrumbs( $args = array() ) {
    // Parse arguments
    $args = wp_parse_args( $args, array(
        'container' => 'nav',
        'container_class' => 'aqualuxe-breadcrumbs',
        'container_id' => '',
        'item_class' => 'aqualuxe-breadcrumbs-item',
        'separator' => '/',
        'separator_class' => 'aqualuxe-breadcrumbs-separator',
        'home_text' => __( 'Home', 'aqualuxe' ),
        'home_url' => home_url( '/' ),
        'show_on_home' => false,
        'show_current' => true,
        'before_current' => '<span class="aqualuxe-breadcrumbs-current">',
        'after_current' => '</span>',
        'echo' => true,
    ) );
    
    // Get breadcrumbs
    $breadcrumbs = aqualuxe_seo_get_breadcrumbs( $args );
    
    // Check if breadcrumbs exist
    if ( empty( $breadcrumbs ) ) {
        return;
    }
    
    // Build output
    $output = '';
    
    // Add container
    if ( ! empty( $args['container'] ) ) {
        $output .= '<' . $args['container'];
        
        // Add container class
        if ( ! empty( $args['container_class'] ) ) {
            $output .= ' class="' . esc_attr( $args['container_class'] ) . '"';
        }
        
        // Add container ID
        if ( ! empty( $args['container_id'] ) ) {
            $output .= ' id="' . esc_attr( $args['container_id'] ) . '"';
        }
        
        // Add aria attributes
        $output .= ' aria-label="' . esc_attr__( 'Breadcrumbs', 'aqualuxe' ) . '"';
        
        $output .= '>';
    }
    
    // Add breadcrumbs list
    $output .= '<ol class="aqualuxe-breadcrumbs-list" itemscope itemtype="https://schema.org/BreadcrumbList">';
    
    // Add breadcrumbs items
    foreach ( $breadcrumbs as $index => $breadcrumb ) {
        // Add item
        $output .= '<li class="' . esc_attr( $args['item_class'] ) . '" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        
        // Check if current item
        if ( $index === count( $breadcrumbs ) - 1 && $args['show_current'] ) {
            // Add current item
            $output .= $args['before_current'];
            $output .= '<span itemprop="name">' . esc_html( $breadcrumb['name'] ) . '</span>';
            $output .= $args['after_current'];
        } else {
            // Add link
            $output .= '<a href="' . esc_url( $breadcrumb['url'] ) . '" itemprop="item">';
            $output .= '<span itemprop="name">' . esc_html( $breadcrumb['name'] ) . '</span>';
            $output .= '</a>';
        }
        
        // Add position
        $output .= '<meta itemprop="position" content="' . ( $index + 1 ) . '">';
        
        // Add separator
        if ( $index < count( $breadcrumbs ) - 1 ) {
            $output .= '<span class="' . esc_attr( $args['separator_class'] ) . '">' . $args['separator'] . '</span>';
        }
        
        $output .= '</li>';
    }
    
    $output .= '</ol>';
    
    // Close container
    if ( ! empty( $args['container'] ) ) {
        $output .= '</' . $args['container'] . '>';
    }
    
    // Echo or return
    if ( $args['echo'] ) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Get breadcrumbs
 *
 * @param array $args Breadcrumbs arguments
 * @return array
 */
function aqualuxe_seo_get_breadcrumbs( $args = array() ) {
    // Parse arguments
    $args = wp_parse_args( $args, array(
        'home_text' => __( 'Home', 'aqualuxe' ),
        'home_url' => home_url( '/' ),
        'show_on_home' => false,
    ) );
    
    // Initialize breadcrumbs
    $breadcrumbs = array();
    
    // Check if we're on the homepage
    if ( is_front_page() || is_home() ) {
        // Check if we should show breadcrumbs on the homepage
        if ( ! $args['show_on_home'] ) {
            return $breadcrumbs;
        }
        
        // Add home
        $breadcrumbs[] = array(
            'name' => $args['home_text'],
            'url' => $args['home_url'],
        );
        
        return $breadcrumbs;
    }
    
    // Add home
    $breadcrumbs[] = array(
        'name' => $args['home_text'],
        'url' => $args['home_url'],
    );
    
    // Check if we're on a single post or page
    if ( is_singular() ) {
        // Get post
        $post = get_queried_object();
        
        // Get post type
        $post_type = get_post_type();
        
        // Check if post type has archive
        if ( $post_type !== 'page' && get_post_type_archive_link( $post_type ) ) {
            // Get post type object
            $post_type_obj = get_post_type_object( $post_type );
            
            // Add post type archive
            $breadcrumbs[] = array(
                'name' => $post_type_obj->labels->name,
                'url' => get_post_type_archive_link( $post_type ),
            );
        }
        
        // Check if post has categories
        if ( $post_type === 'post' ) {
            // Get post categories
            $categories = get_the_category( $post->ID );
            
            // Check if categories exist
            if ( ! empty( $categories ) ) {
                // Get primary category
                $category = $categories[0];
                
                // Check if category has parents
                if ( $category->parent ) {
                    // Get category ancestors
                    $ancestors = get_ancestors( $category->term_id, 'category' );
                    
                    // Reverse ancestors
                    $ancestors = array_reverse( $ancestors );
                    
                    // Add ancestors
                    foreach ( $ancestors as $ancestor ) {
                        $breadcrumbs[] = array(
                            'name' => get_cat_name( $ancestor ),
                            'url' => get_category_link( $ancestor ),
                        );
                    }
                }
                
                // Add category
                $breadcrumbs[] = array(
                    'name' => $category->name,
                    'url' => get_category_link( $category->term_id ),
                );
            }
        }
        
        // Check if post has parent
        if ( $post->post_parent ) {
            // Get post ancestors
            $ancestors = get_post_ancestors( $post->ID );
            
            // Reverse ancestors
            $ancestors = array_reverse( $ancestors );
            
            // Add ancestors
            foreach ( $ancestors as $ancestor ) {
                $breadcrumbs[] = array(
                    'name' => get_the_title( $ancestor ),
                    'url' => get_permalink( $ancestor ),
                );
            }
        }
        
        // Add post
        $breadcrumbs[] = array(
            'name' => get_the_title( $post->ID ),
            'url' => get_permalink( $post->ID ),
        );
    }
    
    // Check if we're on a category or tag archive
    elseif ( is_category() || is_tag() || is_tax() ) {
        // Get term
        $term = get_queried_object();
        
        // Get taxonomy
        $taxonomy = $term->taxonomy;
        
        // Get taxonomy object
        $taxonomy_obj = get_taxonomy( $taxonomy );
        
        // Check if taxonomy has archive
        if ( $taxonomy_obj->public ) {
            // Check if term has parents
            if ( $term->parent ) {
                // Get term ancestors
                $ancestors = get_ancestors( $term->term_id, $taxonomy );
                
                // Reverse ancestors
                $ancestors = array_reverse( $ancestors );
                
                // Add ancestors
                foreach ( $ancestors as $ancestor ) {
                    $breadcrumbs[] = array(
                        'name' => get_term( $ancestor, $taxonomy )->name,
                        'url' => get_term_link( $ancestor, $taxonomy ),
                    );
                }
            }
            
            // Add term
            $breadcrumbs[] = array(
                'name' => $term->name,
                'url' => get_term_link( $term ),
            );
        }
    }
    
    // Check if we're on a post type archive
    elseif ( is_post_type_archive() ) {
        // Get post type
        $post_type = get_query_var( 'post_type' );
        
        // Get post type object
        $post_type_obj = get_post_type_object( $post_type );
        
        // Add post type archive
        $breadcrumbs[] = array(
            'name' => $post_type_obj->labels->name,
            'url' => get_post_type_archive_link( $post_type ),
        );
    }
    
    // Check if we're on a date archive
    elseif ( is_date() ) {
        // Check if we're on a year archive
        if ( is_year() ) {
            // Add year
            $breadcrumbs[] = array(
                'name' => get_the_time( 'Y' ),
                'url' => get_year_link( get_the_time( 'Y' ) ),
            );
        }
        
        // Check if we're on a month archive
        elseif ( is_month() ) {
            // Add year
            $breadcrumbs[] = array(
                'name' => get_the_time( 'Y' ),
                'url' => get_year_link( get_the_time( 'Y' ) ),
            );
            
            // Add month
            $breadcrumbs[] = array(
                'name' => get_the_time( 'F' ),
                'url' => get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ),
            );
        }
        
        // Check if we're on a day archive
        elseif ( is_day() ) {
            // Add year
            $breadcrumbs[] = array(
                'name' => get_the_time( 'Y' ),
                'url' => get_year_link( get_the_time( 'Y' ) ),
            );
            
            // Add month
            $breadcrumbs[] = array(
                'name' => get_the_time( 'F' ),
                'url' => get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ),
            );
            
            // Add day
            $breadcrumbs[] = array(
                'name' => get_the_time( 'd' ),
                'url' => get_day_link( get_the_time( 'Y' ), get_the_time( 'm' ), get_the_time( 'd' ) ),
            );
        }
    }
    
    // Check if we're on an author archive
    elseif ( is_author() ) {
        // Get author
        $author = get_queried_object();
        
        // Add author
        $breadcrumbs[] = array(
            'name' => $author->display_name,
            'url' => get_author_posts_url( $author->ID ),
        );
    }
    
    // Check if we're on a search page
    elseif ( is_search() ) {
        // Add search
        $breadcrumbs[] = array(
            'name' => sprintf(
                /* translators: %s: search query */
                __( 'Search Results for "%s"', 'aqualuxe' ),
                get_search_query()
            ),
            'url' => get_search_link( get_search_query() ),
        );
    }
    
    // Check if we're on a 404 page
    elseif ( is_404() ) {
        // Add 404
        $breadcrumbs[] = array(
            'name' => __( 'Page Not Found', 'aqualuxe' ),
            'url' => '',
        );
    }
    
    return $breadcrumbs;
}

/**
 * Get breadcrumbs JSON-LD
 *
 * @param array $args Breadcrumbs arguments
 * @return string
 */
function aqualuxe_seo_get_breadcrumbs_json_ld( $args = array() ) {
    // Get breadcrumbs
    $breadcrumbs = aqualuxe_seo_get_breadcrumbs( $args );
    
    // Check if breadcrumbs exist
    if ( empty( $breadcrumbs ) ) {
        return '';
    }
    
    // Build JSON-LD
    $json_ld = array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array(),
    );
    
    // Add breadcrumbs items
    foreach ( $breadcrumbs as $index => $breadcrumb ) {
        $json_ld['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $breadcrumb['name'],
            'item' => $breadcrumb['url'],
        );
    }
    
    // Encode JSON-LD
    $json_ld = wp_json_encode( $json_ld );
    
    // Build output
    $output = '<script type="application/ld+json">' . $json_ld . '</script>';
    
    return $output;
}

/**
 * Display breadcrumbs JSON-LD
 *
 * @param array $args Breadcrumbs arguments
 * @return void
 */
function aqualuxe_seo_breadcrumbs_json_ld( $args = array() ) {
    // Get breadcrumbs JSON-LD
    $json_ld = aqualuxe_seo_get_breadcrumbs_json_ld( $args );
    
    // Echo JSON-LD
    echo $json_ld;
}

/**
 * Get WooCommerce breadcrumbs
 *
 * @param array $args Breadcrumbs arguments
 * @return array
 */
function aqualuxe_seo_get_woocommerce_breadcrumbs( $args = array() ) {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return array();
    }
    
    // Parse arguments
    $args = wp_parse_args( $args, array(
        'home_text' => __( 'Home', 'aqualuxe' ),
        'home_url' => home_url( '/' ),
        'shop_text' => __( 'Shop', 'aqualuxe' ),
        'shop_url' => get_permalink( wc_get_page_id( 'shop' ) ),
        'show_on_home' => false,
    ) );
    
    // Initialize breadcrumbs
    $breadcrumbs = array();
    
    // Check if we're on the homepage
    if ( is_front_page() || is_home() ) {
        // Check if we should show breadcrumbs on the homepage
        if ( ! $args['show_on_home'] ) {
            return $breadcrumbs;
        }
        
        // Add home
        $breadcrumbs[] = array(
            'name' => $args['home_text'],
            'url' => $args['home_url'],
        );
        
        return $breadcrumbs;
    }
    
    // Add home
    $breadcrumbs[] = array(
        'name' => $args['home_text'],
        'url' => $args['home_url'],
    );
    
    // Check if we're on the shop page
    if ( is_shop() ) {
        // Add shop
        $breadcrumbs[] = array(
            'name' => $args['shop_text'],
            'url' => $args['shop_url'],
        );
    }
    
    // Check if we're on a product category page
    elseif ( is_product_category() ) {
        // Add shop
        $breadcrumbs[] = array(
            'name' => $args['shop_text'],
            'url' => $args['shop_url'],
        );
        
        // Get term
        $term = get_queried_object();
        
        // Check if term has parents
        if ( $term->parent ) {
            // Get term ancestors
            $ancestors = get_ancestors( $term->term_id, 'product_cat' );
            
            // Reverse ancestors
            $ancestors = array_reverse( $ancestors );
            
            // Add ancestors
            foreach ( $ancestors as $ancestor ) {
                $breadcrumbs[] = array(
                    'name' => get_term( $ancestor, 'product_cat' )->name,
                    'url' => get_term_link( $ancestor, 'product_cat' ),
                );
            }
        }
        
        // Add term
        $breadcrumbs[] = array(
            'name' => $term->name,
            'url' => get_term_link( $term ),
        );
    }
    
    // Check if we're on a product tag page
    elseif ( is_product_tag() ) {
        // Add shop
        $breadcrumbs[] = array(
            'name' => $args['shop_text'],
            'url' => $args['shop_url'],
        );
        
        // Get term
        $term = get_queried_object();
        
        // Add term
        $breadcrumbs[] = array(
            'name' => $term->name,
            'url' => get_term_link( $term ),
        );
    }
    
    // Check if we're on a product page
    elseif ( is_product() ) {
        // Add shop
        $breadcrumbs[] = array(
            'name' => $args['shop_text'],
            'url' => $args['shop_url'],
        );
        
        // Get product
        $product = wc_get_product( get_queried_object_id() );
        
        // Get product categories
        $categories = wc_get_product_terms( $product->get_id(), 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) );
        
        // Check if categories exist
        if ( ! empty( $categories ) ) {
            // Get primary category
            $category = $categories[0];
            
            // Check if category has parents
            if ( $category->parent ) {
                // Get category ancestors
                $ancestors = get_ancestors( $category->term_id, 'product_cat' );
                
                // Reverse ancestors
                $ancestors = array_reverse( $ancestors );
                
                // Add ancestors
                foreach ( $ancestors as $ancestor ) {
                    $breadcrumbs[] = array(
                        'name' => get_term( $ancestor, 'product_cat' )->name,
                        'url' => get_term_link( $ancestor, 'product_cat' ),
                    );
                }
            }
            
            // Add category
            $breadcrumbs[] = array(
                'name' => $category->name,
                'url' => get_term_link( $category ),
            );
        }
        
        // Add product
        $breadcrumbs[] = array(
            'name' => $product->get_name(),
            'url' => get_permalink( $product->get_id() ),
        );
    }
    
    // Check if we're on a WooCommerce page
    elseif ( is_woocommerce() ) {
        // Add shop
        $breadcrumbs[] = array(
            'name' => $args['shop_text'],
            'url' => $args['shop_url'],
        );
        
        // Check if we're on the cart page
        if ( is_cart() ) {
            // Add cart
            $breadcrumbs[] = array(
                'name' => __( 'Cart', 'aqualuxe' ),
                'url' => wc_get_cart_url(),
            );
        }
        
        // Check if we're on the checkout page
        elseif ( is_checkout() ) {
            // Add checkout
            $breadcrumbs[] = array(
                'name' => __( 'Checkout', 'aqualuxe' ),
                'url' => wc_get_checkout_url(),
            );
        }
        
        // Check if we're on the account page
        elseif ( is_account_page() ) {
            // Add account
            $breadcrumbs[] = array(
                'name' => __( 'My Account', 'aqualuxe' ),
                'url' => wc_get_page_permalink( 'myaccount' ),
            );
        }
    }
    
    return $breadcrumbs;
}

/**
 * Display WooCommerce breadcrumbs
 *
 * @param array $args Breadcrumbs arguments
 * @return void
 */
function aqualuxe_seo_woocommerce_breadcrumbs( $args = array() ) {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    // Parse arguments
    $args = wp_parse_args( $args, array(
        'container' => 'nav',
        'container_class' => 'aqualuxe-breadcrumbs woocommerce-breadcrumb',
        'container_id' => '',
        'item_class' => 'aqualuxe-breadcrumbs-item',
        'separator' => '/',
        'separator_class' => 'aqualuxe-breadcrumbs-separator',
        'home_text' => __( 'Home', 'aqualuxe' ),
        'home_url' => home_url( '/' ),
        'shop_text' => __( 'Shop', 'aqualuxe' ),
        'shop_url' => get_permalink( wc_get_page_id( 'shop' ) ),
        'show_on_home' => false,
        'show_current' => true,
        'before_current' => '<span class="aqualuxe-breadcrumbs-current">',
        'after_current' => '</span>',
        'echo' => true,
    ) );
    
    // Get breadcrumbs
    $breadcrumbs = aqualuxe_seo_get_woocommerce_breadcrumbs( $args );
    
    // Check if breadcrumbs exist
    if ( empty( $breadcrumbs ) ) {
        return;
    }
    
    // Build output
    $output = '';
    
    // Add container
    if ( ! empty( $args['container'] ) ) {
        $output .= '<' . $args['container'];
        
        // Add container class
        if ( ! empty( $args['container_class'] ) ) {
            $output .= ' class="' . esc_attr( $args['container_class'] ) . '"';
        }
        
        // Add container ID
        if ( ! empty( $args['container_id'] ) ) {
            $output .= ' id="' . esc_attr( $args['container_id'] ) . '"';
        }
        
        // Add aria attributes
        $output .= ' aria-label="' . esc_attr__( 'Breadcrumbs', 'aqualuxe' ) . '"';
        
        $output .= '>';
    }
    
    // Add breadcrumbs list
    $output .= '<ol class="aqualuxe-breadcrumbs-list" itemscope itemtype="https://schema.org/BreadcrumbList">';
    
    // Add breadcrumbs items
    foreach ( $breadcrumbs as $index => $breadcrumb ) {
        // Add item
        $output .= '<li class="' . esc_attr( $args['item_class'] ) . '" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        
        // Check if current item
        if ( $index === count( $breadcrumbs ) - 1 && $args['show_current'] ) {
            // Add current item
            $output .= $args['before_current'];
            $output .= '<span itemprop="name">' . esc_html( $breadcrumb['name'] ) . '</span>';
            $output .= $args['after_current'];
        } else {
            // Add link
            $output .= '<a href="' . esc_url( $breadcrumb['url'] ) . '" itemprop="item">';
            $output .= '<span itemprop="name">' . esc_html( $breadcrumb['name'] ) . '</span>';
            $output .= '</a>';
        }
        
        // Add position
        $output .= '<meta itemprop="position" content="' . ( $index + 1 ) . '">';
        
        // Add separator
        if ( $index < count( $breadcrumbs ) - 1 ) {
            $output .= '<span class="' . esc_attr( $args['separator_class'] ) . '">' . $args['separator'] . '</span>';
        }
        
        $output .= '</li>';
    }
    
    $output .= '</ol>';
    
    // Close container
    if ( ! empty( $args['container'] ) ) {
        $output .= '</' . $args['container'] . '>';
    }
    
    // Echo or return
    if ( $args['echo'] ) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Get WooCommerce breadcrumbs JSON-LD
 *
 * @param array $args Breadcrumbs arguments
 * @return string
 */
function aqualuxe_seo_get_woocommerce_breadcrumbs_json_ld( $args = array() ) {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return '';
    }
    
    // Get breadcrumbs
    $breadcrumbs = aqualuxe_seo_get_woocommerce_breadcrumbs( $args );
    
    // Check if breadcrumbs exist
    if ( empty( $breadcrumbs ) ) {
        return '';
    }
    
    // Build JSON-LD
    $json_ld = array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array(),
    );
    
    // Add breadcrumbs items
    foreach ( $breadcrumbs as $index => $breadcrumb ) {
        $json_ld['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $breadcrumb['name'],
            'item' => $breadcrumb['url'],
        );
    }
    
    // Encode JSON-LD
    $json_ld = wp_json_encode( $json_ld );
    
    // Build output
    $output = '<script type="application/ld+json">' . $json_ld . '</script>';
    
    return $output;
}

/**
 * Display WooCommerce breadcrumbs JSON-LD
 *
 * @param array $args Breadcrumbs arguments
 * @return void
 */
function aqualuxe_seo_woocommerce_breadcrumbs_json_ld( $args = array() ) {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    // Get breadcrumbs JSON-LD
    $json_ld = aqualuxe_seo_get_woocommerce_breadcrumbs_json_ld( $args );
    
    // Echo JSON-LD
    echo $json_ld;
}